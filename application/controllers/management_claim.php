<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_claim extends MY_Controller
{    
  public function __construct()
  {
    parent::__construct();
    $this->data['page_title'] = 'Management Claim';

    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login_sistem/','refresh');
    }
    set_time_limit(0);

    $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    $this->load->helper(array('url', 'csv'));
    $this->load->model(array('model_outlet_transaksi','model_management_claim','M_helpdesk','model_inventory', 'model_master_data'));
      // cek traffic
    $traffic = $this->model_management_claim->get_traffic_import();
    if($traffic->num_rows() > 0)
    {
        $status_import = $traffic->row()->status_import;
        $created_at = $traffic->row()->created_at;

        date_default_timezone_set('Asia/Jakarta');
        $waktu_awal  =strtotime($created_at);
        $waktu_akhir =strtotime(date('Y-m-d H:i:s')); // bisa juga waktu sekarang now()
                    
        //menghitung selisih dengan hasil detik
        $diff    =$waktu_akhir - $waktu_awal;
        if ($diff > 300 && $status_import == 1) {
            $this->model_management_claim->insert_traffic($this->session->userdata('username'), $this->session->userdata('id'), 0);
        }   
    }  

    $this->email_tim = 'ismi.aulia@muliaputramandiri.com, ambar@muliaputramandiri.com, dea@muliaputramandiri.com, adm.sls.delto@gmail.com, admin.ka@deltomed.co.id';
    // $this->email_tim = 'tim@test.com, tim2@test.com';
    $this->email_head_mti = 'head_mti@test.com';
    $this->email_finance = 'finance@test.com';
    $this->created_at = $this->model_outlet_transaksi->timezone();
    $this->created_by = $this->session->userdata('id');
    $this->username = $this->session->userdata('username');
    $this->tahun_folder = 2025;
  }

    function index()
    {
        $this->ajuan_claim();
    }

    function navbar($data)
    {
        // echo "level : ".$this->session->userdata('level');
        if ($this->session->userdata('level') === '4') { // jika dp
            $this->load->view('management_office/top_header_dp', $data);
        }elseif ($this->session->userdata('level') === '3') { // jika principal
            $this->load->view('management_office/top_header_principal', $data);
        }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales 
            $this->load->view('management_office/top_header_principal_nosales', $data);
        }elseif ($this->session->userdata('level') === "3b") { // jika principal hanya raw data, claim, rpd 
            $this->load->view('management_office/top_header_principal_rawdata', $data);
        }elseif ($this->session->userdata('level') === "3c") { // jika principal raw_data dan retur dan rpd = RSPH = ghozali yoseph sudarsono
            $this->load->view('management_office/top_header_principal_rawdata_retur', $data);
        }elseif ($this->session->userdata('level') === "3d") { // jika principal rpd
            $this->load->view('management_office/top_header_principal_rpd', $data);
        }elseif ($this->session->userdata('level') === '5') { // jika dp mpi
            $this->load->view('management_office/top_header_dp_mpi', $data);
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function form_ajuan_claim($signature_program)
    {
        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_registrasi_program_by_signature->num_rows > 0) 
        {
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');            
        }else
        {
            $id_program = $get_registrasi_program_by_signature->row()->id;
            $kategori   = ($get_registrasi_program_by_signature->row()->nama_kategori) ? $get_registrasi_program_by_signature->row()->nama_kategori : $get_registrasi_program_by_signature->row()->kategori;
            $namasupp   = $this->model_master_data->get_namasupp_by_supp($get_registrasi_program_by_signature->row()->supp)->row()->namasupp;
            $id_kategori  = $get_registrasi_program_by_signature->row()->kategori;
            $from         = $get_registrasi_program_by_signature->row()->from;
            $to           = $get_registrasi_program_by_signature->row()->to;
            $nama_program = $get_registrasi_program_by_signature->row()->nama_program;
            $nomor_surat  = $get_registrasi_program_by_signature->row()->nomor_surat;
            $syarat       = $get_registrasi_program_by_signature->row()->syarat;
            $duedate      = $get_registrasi_program_by_signature->row()->duedate;
            $upload_jpg   = $get_registrasi_program_by_signature->row()->upload_jpg;
            $upload_pdf   = $get_registrasi_program_by_signature->row()->upload_pdf;
            $segment      = $get_registrasi_program_by_signature->row()->segment;
            $upload_template_program = $get_registrasi_program_by_signature->row()->upload_template_program;
            $supp     = $get_registrasi_program_by_signature->row()->supp;
            $username = $this->model_master_data->get_username_by_id($get_registrasi_program_by_signature->row()->created_by)->row()->username;
            $status_validasi = $get_registrasi_program_by_signature->row()->status_validasi;
            if ($status_validasi == null) {
                $params_status_validasi = 1;
            }else{
                $params_status_validasi = $status_validasi;
            }

            if ($params_status_validasi == 1) {
                $params_folder = "import";
            }else{
                $params_folder = "";
            }

            $nama_status_validasi = $get_registrasi_program_by_signature->row()->nama_status_validasi;
            $keterangan = $get_registrasi_program_by_signature->row()->keterangan;
            $nama_template = $get_registrasi_program_by_signature->row()->nama_template;
            $filename = $get_registrasi_program_by_signature->row()->filename;
            $pic = $get_registrasi_program_by_signature->row()->pic;            
        }

        if ($nama_status_validasi == "TRUE") 
        { 
            if ($params_status_validasi == '1') 
            {
                if ($kategori == 'bonus_barang') 
                {
                    $url = 'management_claim/import_bonus_barang';
                }elseif ($kategori == 'diskon_herbal' || $kategori == 'diskon_candy' || $kategori == 'diskon') 
                {
                    $url = 'management_claim/import_diskon';
                }else
                {
                    $url = 'management_claim/ajuan_claim_save';
                }
            }else
            {
                $url = 'management_claim/ajuan_claim_save';
            }

        }else{
            $url = 'management_claim/ajuan_claim_save';
        }
                
        $get_ajuan_claim_by_id_program_and_user = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->session->userdata('id'));
        if ($get_ajuan_claim_by_id_program_and_user->num_rows > 0) 
        {
            $id_ajuan           = $get_ajuan_claim_by_id_program_and_user->row()->id;
            $branch_name        = $get_ajuan_claim_by_id_program_and_user->row()->branch_name;
            $nama_comp          = $get_ajuan_claim_by_id_program_and_user->row()->nama_comp;
            $site_code          = $get_ajuan_claim_by_id_program_and_user->row()->site_code;
            $nama_pengirim      = $get_ajuan_claim_by_id_program_and_user->row()->nama_pengirim;
            $email_pengirim     = $get_ajuan_claim_by_id_program_and_user->row()->email_pengirim;
            $ajuan_excel        = $get_ajuan_claim_by_id_program_and_user->row()->ajuan_excel;
            $ajuan_zip          = $get_ajuan_claim_by_id_program_and_user->row()->ajuan_zip;
            $tanggal_claim      = $get_ajuan_claim_by_id_program_and_user->row()->tanggal_claim;
            $created_at         = $get_ajuan_claim_by_id_program_and_user->row()->created_at;
            $nama_status        = $get_ajuan_claim_by_id_program_and_user->row()->nama_status;
            $signature_ajuan    = $get_ajuan_claim_by_id_program_and_user->row()->signature;
            $nomor_ajuan        = $get_ajuan_claim_by_id_program_and_user->row()->nomor_ajuan;            
            $id_verifikasi      = $get_ajuan_claim_by_id_program_and_user->row()->id_verifikasi;
            $mpm_at             = $get_ajuan_claim_by_id_program_and_user->row()->mpm_at;
            $status_internal    = $get_ajuan_claim_by_id_program_and_user->row()->status_internal;
            $nama_status_internal   = $get_ajuan_claim_by_id_program_and_user->row()->nama_status_internal;
            $status   = $get_ajuan_claim_by_id_program_and_user->row()->status;   
        }else{
            $id_ajuan = "";
        }

        // $created_at     = $this->model_outlet_transaksi->timezone();  
        $today_params = date('Y-m-d', strtotime($this->created_at)); // bisa juga waktu sekarang now()
        //menghitung selisih dengan hasil detik
        $selisih = strtotime($duedate) - strtotime($today_params);        

        if ($id_ajuan) {
            $get_pic = $this->model_management_claim->get_log_aktivitas_by_onduty($id_ajuan);
            if ($get_pic->num_rows > 0) {
                $pic_on_duty = $get_pic->row()->username;
                $email_on_duty = $get_pic->row()->email;
            }else{
                $pic_on_duty = "";
                $email_on_duty = "";
            }
        }else{
            $pic_on_duty = "";
            $email_on_duty = "";
            $id_ajuan = "";
        }

        echo "today_params : ".$today_params;
        echo "selisih : ".$selisih;
        echo "pic_on_duty : ".$pic_on_duty;
        echo "email_on_duty : ".$email_on_duty;
        echo "id_ajuan : ".$id_ajuan;

        // die;
        

        $data = [
            'title'                     => 'management claim | form ajuan claim',            
            'url'                       => $url,
            'kategori'                  => $kategori,      
            'namasupp'                  => $namasupp,      
            'from'                      => $from,      
            'to'                        => $to,      
            'nama_program'              => $nama_program,      
            'nomor_surat'               => $nomor_surat,      
            'syarat'                    => $syarat,      
            'duedate'                   => $duedate,      
            'upload_jpg'                => $upload_jpg,      
            'upload_pdf'                => $upload_pdf,      
            'username'                  => $username,     
            'signature_program'         => $signature_program,   
            'selisih_duedate'           => $selisih, 
            'site_code_form'            => $this->model_management_claim->get_sitecode($this->session->userdata('id')),
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        // $this->load->view('management_claim/accordion', $data);
        $this->load->view('management_claim/form_ajuan_claim', $data);
        $this->load->view('kalimantan/footer');
    }

    public function export_template_diskon($signature_program)
    {

        $nomor_surat = $this->model_management_claim->get_registrasi_program_by_signature($signature_program)->row()->nomor_surat;
        // echo $nomor_surat;
        // die;

        $query = "
            select '' as nomor_surat_program, '' as site_code, '' as no_sales, '' as jumlah, '' as tgl_sales, '' as kode_class, '' as kode_customer, '' as nama_customer, '' as kodeprod, '' as qty_jual, '' as value_jual, '' as diskon_principal, '' as diskon_cabang, '' as diskon_extra, '' as diskon_cash, '' as disc_yang_di_claim
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'nomor_surat_program', 'site_code', 'no_sales', 'tgl_sales(m/d/y)', 'kode_class', 'kode_customer', 'nama_customer', 'kodeprod', 'qty_jual', 'value_jual', 'diskon_principal', 'diskon_cabang', 'diskon_extra', 'diskon_cash', 'disc_yang_di_claim'
        ));
        $this->excel_generator->set_column(array
        ( 
            'nomor_surat_program', 'site_code', 'no_sales', 'tgl_sales', 'kode_class', 'kode_customer', 'nama_customer', 'kodeprod', 'qty_jual', 'value_jual', 'diskon_principal', 'diskon_cabang', 'diskon_extra', 'diskon_cash', 'disc_yang_di_claim'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Template Claim khusus Diskon | Nomor Surat : '.$nomor_surat); 


    }

    public function dashboard()
    {
        $session_username = $this->session->userdata('username');

        $get_tabcomp_by_kode_comp = $this->model_master_data->get_tabcomp_by_kode_comp($session_username);
        if ($get_tabcomp_by_kode_comp->num_rows() > 0) {
            $sub = $get_tabcomp_by_kode_comp->row()->sub;
        }else{
            $sub = '';
        }

        // validasi tabcomp
        $get_tabcomp_by_sub = $this->model_master_data->get_tabcomp_by_sub($sub);

        if ($get_tabcomp_by_sub->num_rows() > 0) {
            
            $site_codex = '';
            foreach ($get_tabcomp_by_sub->result() as $a) {
                $site_codex.= ',"'.$a->site_code.'"';
            }
            $site_code_join = preg_replace('/,/', '', $site_codex,1);

        }else{
            
        }

        if($this->input->get('from')){
            
            $advanced['from']               = $this->input->get('from');
            $advanced['to']                 = $this->input->get('to');
            $advanced['supp']               = $this->input->get('supp');
            $advanced['kategori']           = $this->input->get('kategori');
            $advanced['pic']                = $this->input->get('pic');
            $advanced['site_code_join']     = $site_code_join;


            // echo "supp : ".$this->input->get('supp');
            $export = $this->input->get('export');
            // echo "Export : ".$export;

            if ($export) {
                $this->export_dashboard($advanced);
                die;
            }
        }else{
            // $advanced['from']            = '';
            $advanced['from']               = date('2023-m-01');
            // $advanced['to']              = '';
            $advanced['to']                 = date('Y-m-d');
            // $advanced['supp']            = '';
            $advanced['supp']               = '';
            // $advanced['kategori']        = '';
            $advanced['kategori']           = '';
            $advanced['pic']                = '';
            $advanced['site_code_join']     = '';
        }

        $get_registrasi_program_by_supp_kategori_periode = $this->model_management_claim->get_registrasi_program_by_supp_kategori_periode($advanced);

        if ($get_registrasi_program_by_supp_kategori_periode->num_rows() > 0) {
            $code = '';
            foreach ($get_registrasi_program_by_supp_kategori_periode->result() as $a) {
                $code.= ','.$a->id;
            }
            $id_program = preg_replace('/,/', '', $code,1);
        }else{
            $id_program = '0';
        }

        $data = [
            'title'                                             => 'management claim | Dashboard',
            'url'                                               => 'dashboard',
            'get_registrasi_program_by_supp_kategori_periode'   => $get_registrasi_program_by_supp_kategori_periode,
            'get_ajuan_claim_group_subbranch_by_idprogram'      => $this->model_management_claim->get_ajuan_claim_group_subbranch_by_idprogram_sitecode($id_program, $site_code_join),

        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    public function export_dashboard($advanced){

        // var_dump($advanced);

        $from = $advanced['from'];
        $to = $advanced['to'];
        $supp = $advanced['supp'];
        $site_code_join = $advanced['site_code_join'];
        $kategori = $advanced['kategori'];
        $pic = $advanced['pic'];

        // echo "site_code_join : ".$site_code_join;

        if ($pic == "all") {
            $params_pic = "";
        }else{
            $params_pic = "and a.created_by = '$pic'";
        }

        if ($kategori == "all") {
            $params_kategori = "";
        }else{
            $params_kategori = "and a.kategori = '$kategori'";
        }

        // die;

        $query = "
            select 	c.namasupp, a.kategori, a.nama_program, a.nomor_surat, a.duedate as deadline, a.syarat, d.username as pic,
                    b.site_code, b.branch_name, b.nama_comp, b.nama_status, b.nama_pengirim, b.email_pengirim, b.status_keikutsertaan, b.nama_status_keikutsertaan
            from management_claim.registrasi_program a LEFT JOIN (
                select a.id_program, a.site_code, a.branch_name, a.nama_comp, a.nama_status, a.email_pengirim, a.nama_pengirim, a.created_at, a.status_keikutsertaan, a.nama_status_keikutsertaan
                from management_claim.ajuan_claim a 
                where a.deleted is null and a.site_code in ($site_code_join)
            )b on a.id = b.id_program LEFT JOIN (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )c on a.supp = c.supp LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )d on a.created_by = d.id
            where a.supp = $supp and a.deleted is null and date(a.from) between '$from' and '$to' $params_pic $params_kategori
        ";
        $hasil = $this->db->query($query);  

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'namasupp', 'kategori', 'nama_program', 'nomor_surat', 'deadline', 'syarat', 'pic', 'site_code', 'branch_name', 'nama_comp', 'nama_status', 'nama_pengirim', 'email_pengirim', 'status_keikutsertaan', 'nama_status_keikutsertaan'
        ));
        $this->excel_generator->set_column(array
        ( 
            'namasupp', 'kategori', 'nama_program', 'nomor_surat', 'deadline', 'syarat', 'pic', 'site_code', 'branch_name', 'nama_comp', 'nama_status', 'nama_pengirim', 'email_pengirim', 'status_keikutsertaan', 'nama_status_keikutsertaan'
        ));
        $this->excel_generator->set_width(array(10,10,30,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Export Dashboard');  
    }

    public function registrasi_program($from = '', $to = '')
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        // echo "from : ".$from;
        
        $data = [
            'title'         => 'registrasi program',
            'get_data'      => $this->model_management_claim->get_registrasi_program_regular($from, $to),
            'url'           => 'management_claim/registrasi_program_save',
            'url_search'    => 'registrasi_program',
            'url_deadline'  => 'management_claim/updated_deadline',
            'get_principal' => $this->model_management_claim->get_principal(),
        ];

        // $this->view($data, false, "registrasi_program");        
        $this->render('management_claim/registrasi_program', $data);
    }
    public function edit_registrasi_program($signature_program)
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $id_program = $get_registrasi_program['id_program'];
        // var_dump($get_registrasi_program);


        $data = [
            'title'         => 'management claim | registrasi program',
            'url'           => 'management_claim/registrasi_program_update',
            'get_principal' => $this->model_management_claim->get_principal(),

            'nomor_surat'   => $get_registrasi_program['nomor_surat'],
            'nama_program'  => $get_registrasi_program['nama_program'],
            'duedate'       => $get_registrasi_program['duedate'],
            'from'          => $get_registrasi_program['from'],
            'to'            => $get_registrasi_program['to'],
            'upload_pdf'    => $get_registrasi_program['upload_pdf'],
            'namasupp'      => $get_registrasi_program['namasupp'],
            'kategori'      => $get_registrasi_program['kategori'],
            'nama_status_validasi'    => $get_registrasi_program['nama_status_validasi'],
            'segment'       => $get_registrasi_program['segment'],
            'pic'           => $get_registrasi_program['pic'],
            'nama_template' => $get_registrasi_program['nama_template'],
            'signature_program' => $signature_program,
            'id_program'    => $id_program,
            'tahun_folder'  => $get_registrasi_program['tahun_folder'],
            
        ];

        // $this->view($data, false, "edit_registrasi_program");
        $this->render('management_claim/edit_registrasi_program', $data);
    }

    public function master_flag_validasi()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = getenv('USER_ID');

        $supp = $this->input->post('supp');

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'supp'      => $supp,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_flag_validasi?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            echo "<option value=''> Validasi ? </option>";
            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            foreach ($datas as $key => $a)
            {
                echo "<option value='". $a["id"]. "' >";
                echo $a["nama_status_validasi"];
                echo "</option>";
            }
        }
    }

    public function master_segment()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = getenv('USER_ID');

        $supp = $this->input->post('supp');

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'supp'      => $supp,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_segment?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            echo "<option value=''> Segment ? </option>";
            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            foreach ($datas as $key => $a)
            {
                echo "<option value='". $a["nama_segment"]. "' >";
                echo $a["nama_segment"];
                echo "</option>";
            }
        }
    }

    public function master_flag_pic()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = getenv('USER_ID');

        $supp = $this->input->post('supp');
        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'supp'      => $supp,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_flag_pic?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            echo "<option value=''> First PIC ? </option>";
            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            foreach ($datas as $key => $a)
            {
                echo "<option value='". $a["status_pic"]. "' >";
                echo $a["status_pic"];
                echo "</option>";
            }
        }
    }

    public function master_template()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = getenv('USER_ID');

        $supp = $this->input->post('supp');
        $segment = $this->input->post('segment');
        $kategori = $this->input->post('kategori');

        // $supp = '001';
        // $segment = 'GT';
        // $kategori = '1';

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'supp'      => $supp,
            'segment'   => $segment,
            'kategori'  => $kategori,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_template?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            echo "<option value=''> Template ? </option>";
            echo "<option value='false'> no template </option>";
            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            foreach ($datas as $key => $a)
            {
                echo "<option value='". $a["id"]. "' >";
                echo $a["nama_template"]." (".$a["filename"].")";
                echo "</option>";
            }
        }
    }

    public function master_type_outlet()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $supp = $this->input->post('supp');

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/type_outlet?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            echo "<option value=''> Tipe Outlet ? </option>";
            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            foreach ($datas as $key => $a)
            {
                echo "<option value='". $a["kode_type"]. "' >";
                echo $a["kode_type"]. " - ".$a["nama_type"];
                echo "</option>";
            }
        }
    }

    public function manage_registrasi_program($signature_program, $tahun = '')
    {
        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_registrasi_program_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "manage data gagal dijalankan !! data not found");
            redirect('management_claim/registrasi_program');
            die;
        }else{
            $id_program = $get_registrasi_program_by_signature->row()->id;
        }

        $tahun = $this->input->get('tahun');
        if ($tahun) {
            $signature_program = $this->input->get('signature_program');        
            $get_master_site = $this->model_management_claim->get_master_site($tahun);
        }else{
            $tahun = '1900';
            $get_master_site = $this->model_management_claim->get_master_site($tahun);   
        }

        $data = [
            'title'                 => 'Manage Site Code Register Program',
            'title2'                => 'Input DP',
            'get_registrasi_program'=> $this->model_management_claim->get_registrasi_program_site_code($id_program),    
            'url'                   => 'management_claim/manage_registrasi_program',
            'url_save'              => 'management_claim/manage_registrasi_program_save',
            'signature_program'     => $signature_program,
            'master_site'           => $get_master_site,
            'tahun'                 => $tahun
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('management_claim/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/manage_registrasi_program', $data);
        $this->load->view('kalimantan/footer');

    }

    public function manage_registrasi_program_product($signature_program, $tahun = ''){

        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_registrasi_program_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "manage data gagal dijalankan !! data not found");
            redirect('management_claim/registrasi_program');
            die;
        }else{
            $id_program = $get_registrasi_program_by_signature->row()->id;
            $supp = $get_registrasi_program_by_signature->row()->supp;
        }

        $tahun = $this->input->get('tahun');
        if ($tahun) {
            $signature_program = $this->input->get('signature_program');        
            $get_master_site = $this->model_management_claim->get_master_site($tahun);
        }else{
            $tahun = '1900';
            $get_master_site = $this->model_management_claim->get_master_site($tahun);   
        }

        $data = [
            'title'                 => 'Manage Product Register Program',
            'title2'                => 'Pilih Product',
            'get_registrasi_program_product'=> $this->model_management_claim->get_registrasi_program_product($id_program),    
            'get_product_by_supp'=> $this->model_management_claim->get_product_by_supp($supp),    
            'url'                   => 'management_claim/manage_registrasi_program_product',
            'url_save'              => 'management_claim/manage_registrasi_program_product_save',
            'signature_program'     => $signature_program,
            'master_site'           => $get_master_site,
            'tahun'                 => $tahun
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('management_claim/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/manage_registrasi_program_product', $data);
        $this->load->view('kalimantan/footer');

    }

    public function manage_registrasi_program_save(){
        $id = $this->input->post('options');
        $site_code = $this->input->post('site_code');
        $signature_program = $this->input->post('signature_program');
        $created_at = $this->model_outlet_transaksi->timezone();

        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_registrasi_program_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "request anda gagal dijalankan !! data not found");
            redirect('management_claim/registrasi_program');
            die;
        }else{
            $id_program = $get_registrasi_program_by_signature->row()->id;
        }

        $count = count($id);

        for ($i=0; $i < $count ; $i++) { 
            // echo "id : ".$id[$i];
            // echo "<br>";
            // echo "site_code : ".$site_code[$i];
            // echo "<hr>";

            $data = [
                'site_code'     => $site_code[$i],
                'created_at'    => $created_at,
                'created_by'    => $this->session->userdata('id'),
                'id_program'    => $id_program
            ];
            $this->db->insert('management_claim.registrasi_program_site_code', $data);
        }

        redirect('management_claim/manage_registrasi_program/'.$signature_program, 'refresh');

        // $id = $this->input->post('options');


    }

    public function manage_registrasi_program_product_save(){
        $id = $this->input->post('options');
        $kodeprod = $this->input->post('kodeprod');
        $signature_program = $this->input->post('signature_program');
        $created_at = $this->model_outlet_transaksi->timezone();

        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_registrasi_program_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "request anda gagal dijalankan !! data not found");
            redirect('management_claim/registrasi_program');
            die;
        }else{
            $id_program = $get_registrasi_program_by_signature->row()->id;
        }

        $count = count($id);

        for ($i=0; $i < $count ; $i++) { 
            // echo "id : ".$id[$i];
            // echo "<br>";
            // echo "site_code : ".$site_code[$i];
            // echo "<hr>";

            $data = [
                'kodeprod'      => $kodeprod[$i],
                'created_at'    => $created_at,
                'created_by'    => $this->session->userdata('id'),
                'id_program'    => $id_program
            ];
            $this->db->insert('management_claim.registrasi_program_product', $data);
        }

        redirect('management_claim/manage_registrasi_program_product/'.$signature_program, 'refresh');

    }

    public function registrasi_program_save(){
        $supp           = $this->input->post('supp');
        $kategori       = $this->input->post('kategori');
        $flag_validasi  = $this->input->post('flag_validasi');
        $segment        = $this->input->post('segment');
        $pic            = $this->input->post('pic');
        $id_template    = $this->input->post('id_template');

        $from           = $this->input->post('from');
        $to             = $this->input->post('to');
        $nomor_surat    = $this->input->post('nomor_surat');
        $nama_program   = $this->input->post('nama_program');
        $duedate        = $this->input->post('duedate');
        $signature      = 'reg-claim-'.rand().md5($this->created_at.rand());

        $get_flag_validasi = $this->model_management_claim->get_master_flag_validasi($flag_validasi);
        if (!$get_flag_validasi->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "ada kesalahan saat input status validasi. Silahkan ulangi kembali.");
            redirect('management_claim/registrasi_program');
            die;
        }
        $nama_status_validasi = $get_flag_validasi->row()->nama_status_validasi;

        $this->cek_kesesuaian_input_registrasi($supp, $kategori, $nama_status_validasi, $id_template, $segment);

        
        $get_data = $this->model_management_claim->get_registrasi_program_by_nomor_surat($nomor_surat);
        if ($get_data->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "registrasi program gagal. Ditemukan nomor surat yang sama. Silahkan gunakan nomor surat lain.");
            redirect('management_claim/registrasi_program');
            die;
        }

        $init_upload = $this->attachment_config("registrasi_program");

        if ($this->upload->do_upload('upload_pdf')) 
        {
            $upload_data = $this->upload->data();
            $filename_pdf = $upload_data['file_name'];
        }else{
            $this->session->set_flashdata("pesan", "registrasi program gagal. Karena upload pdf gagal.");
            redirect('management_claim/registrasi_program');
            die;
        };

        $data = [
            'supp'          => $supp,
            'kategori'      => $kategori,
            'flag_validasi' => $flag_validasi,
            'segment'       => $segment,
            'pic'           => $pic,
            'id_template'   => $id_template,
            'from'          => $from,
            'to'            => $to,
            'nomor_surat'   => $nomor_surat,
            'nama_program'  => $nama_program,
            'duedate'       => $duedate,
            'upload_pdf'    => $filename_pdf,
            'signature'     => $signature,
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
            'tahun_folder'  => $this->tahun_folder
        ];

        $insert = $this->model_management_claim->insert_registrasi_program($data);
        if ($insert) {
            $this->session->set_flashdata("pesan_success", "Insert registrasi program success");
            redirect('management_claim/registrasi_program/'.$signature);
        }else{
            $this->session->set_flashdata("pesan", "Failed. Please try again");
            redirect('management_claim/registrasi_program/'.$signature);
        }
    }

    private function cek_kesesuaian_input_registrasi($supp, $kategori, $nama_status_validasi, $id_template, $segment)
    {
        $get_kategori = $this->model_management_claim->get_master_kategori_by_id($kategori);

        if (!$get_kategori->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "ada kesalahan saat input kategori. Silahkan ulangi kembali.");
            redirect('management_claim/registrasi_program');
        }else{
            $nama_kategori = $get_kategori->row()->nama_kategori;
        }

        if ($supp != '001') {
            if (($kategori == '2' || $kategori == '3' || $kategori == '4' || $kategori == '5') && $nama_status_validasi == 'TRUE' && $id_template <> 'false') 
            {
                return true;
            }elseif (($kategori <> '2' || $kategori <> '3' || $kategori <> '4' || $kategori <> '5') && $nama_status_validasi == 'FALSE') 
            {
                return true;
            }else{
                $message = "kategori : ".$nama_kategori.", nama_status_validasi : ".$nama_status_validasi.", template : ".$id_template. ". Kombinasi ini tidak diijinkan. Jika merasa ada alasan yang sesuai, silahkan hubungi IT terkait";
                $this->session->set_flashdata("pesan", $message);
                redirect('management_claim/registrasi_program');
            }
        }else{ // ini akan diubah karena selain gt boleh pakai validasi
            if ($nama_status_validasi == 'TRUE') {

                if ($segment == 'GT') {
                    $this->session->set_flashdata("pesan", "tidak boleh memilih validasi TRUE untuk principal Deltomed dan Segment GT");
                    redirect('management_claim/registrasi_program');
                }
                // die; 
            }
        }
    }

    public function registrasi_program_update()
    {
        $supp           = $this->input->post('supp');
        $kategori       = $this->input->post('kategori');
        $flag_validasi  = $this->input->post('flag_validasi');
        $segment        = $this->input->post('segment');
        $pic            = $this->input->post('pic');
        $id_template    = $this->input->post('id_template');

        $from           = $this->input->post('from');
        $to             = $this->input->post('to');
        $nomor_surat    = $this->input->post('nomor_surat');
        $nama_program   = $this->input->post('nama_program');
        $duedate        = $this->input->post('duedate');
        $upload_pdf_old = $this->input->post('upload_pdf_old');
        $id_program     = $this->input->post('id_program');

        // echo "supp : ".$supp."<br>";
        // echo "kategori : ".$kategori."<br>";
        // echo "flag_validasi : ".$flag_validasi."<br>";
        // echo "segment : ".$segment."<br>";
        // echo "pic : ".$pic."<br>";
        // echo "id_template : ".$id_template."<br>";
        // echo "from : ".$from."<br>";
        // echo "to : ".$to."<br>";
        // echo "nomor_surat : ".$nomor_surat."<br>";
        // echo "nama_program : ".$nama_program."<br>";
        // echo "duedate : ".$duedate."<br>";
        // echo "upload_pdf_old : ".$upload_pdf_old."<br>";
        // echo "id_program : ".$id_program."<br>";

        // cek created_by based on id_program 
        $get_data = $this->model_management_claim->get_registrasi_program_by_id($id_program);
        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "maaf program ini bukan program yang anda buat.");
            redirect('management_claim/registrasi_program');
        }


        $get_flag_validasi = $this->model_management_claim->get_master_flag_validasi($flag_validasi);
        if (!$get_flag_validasi->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "ada kesalahan saat input status validasi. Silahkan ulangi kembali.");
            redirect('management_claim/registrasi_program');
            die;
        }
        $nama_status_validasi = $get_flag_validasi->row()->nama_status_validasi;

        $this->cek_kesesuaian_input_registrasi($supp, $kategori, $nama_status_validasi, $id_template);

        $get_data = $this->model_management_claim->get_registrasi_program_by_nomor_surat_exception($nomor_surat, $id_program);
        if ($get_data->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "registrasi program gagal. Ditemukan nomor surat yang sama. Silahkan gunakan nomor surat lain.");
            redirect('management_claim/registrasi_program');
            die;
        }

        $init_upload = $this->attachment_config("registrasi_program");

        if ($this->upload->do_upload('upload_pdf')) 
        {
            $upload_data = $this->upload->data();
            $filename_pdf = $upload_data['file_name'];
        }else{
            $filename_pdf = $upload_pdf_old;
        };

        $data = [
            'supp'          => $supp,
            'kategori'      => $kategori,
            'flag_validasi' => $flag_validasi,
            'segment'       => $segment,
            'pic'           => $pic,
            'id_template'   => $id_template,
            'from'          => $from,
            'to'            => $to,
            'nomor_surat'   => $nomor_surat,
            'nama_program'  => $nama_program,
            'duedate'       => $duedate,
            'upload_pdf'    => $filename_pdf,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
            'tahun_folder'  => $this->tahun_folder
        ];

        $update = $this->model_management_claim->update_registrasi_program($data, $id_program);
        if ($update) {
            $this->session->set_flashdata("pesan_success", "Update Program Success");
            redirect('management_claim/registrasi_program/'.$signature);
        }else{
            $this->session->set_flashdata("pesan", "Failed. Please try again");
            redirect('management_claim/registrasi_program/'.$signature);
        }
    }

  public function ajuan_claim()
  {
    $this->load->model('model_master_data');
    $site_code = $this->model_master_data->get_tabcomp_by_kode_comp($this->session->userdata('username'));
    if ($site_code->num_rows() > 0) 
    {
        $site_code = $site_code->row()->site_code;
    }else{
        $site_code = '';
    }

    // echo "site_code : ".$site_code; 
    // die;

    $flag_delete = $this->input->get('flag_delete');

    if($this->input->get('from'))
    {    
      $advanced['from']           = $this->input->get('from');
      $advanced['to']             = $this->input->get('to');
      $advanced['supp']           = $this->input->get('supp');
      $advanced['site_code']      = $site_code;
      $advanced['kategori']       = $this->input->get('kategori');
      $advanced['pic']            = $this->input->get('pic');
      // $advanced['status']         = $this->input->get('status');
      $advanced['flag_delete']    = $flag_delete;
    }
    else
    {
      $advanced = null;
      // $get_data = null;
    }

    $get_data = $this->model_management_claim->get_registrasi_program_by_supp_date($advanced);

    $submit = $this->input->get('submit');
    if($submit == "export")
    {
      $this->export_ajuan_claim($advanced);
      die;
    }

    $data = [
        'title'         => 'ajuan claim',
        'url'           => 'ajuan_claim',
        'get_principal' => $this->model_management_claim->get_principal(),
        // 'get_data'      => $this->model_management_claim->get_registrasi_program_by_supp_date($advanced),
        'get_data'      => $get_data,
        'flag_delete'   => $flag_delete,
        'tahun_folder'  => $this->tahun_folder
    ];

    $this->render('management_claim/ajuan_claim', $data);
  }

    public function export_ajuan_claim($advanced)
    {
        $query = $this->model_management_claim->get_registrasi_program_by_supp_date($advanced);            
        $this->excel_generator->set_query($query);
        $this->excel_generator->set_header(array
        (
            'principal', 'nomor_surat', 'nama_program', 'nama_kategori', 'from', 'to', 'duedate program', 'site_code', 
            'branch_name', 'nama_comp', 'nomor_ajuan', 'nama_status', 'nama_status_internal', 'pic_userid_username',
            'duedate_response' 
        ));
        $this->excel_generator->set_column(array
        ( 
            'namasupp', 'nomor_surat', 'nama_program', 'nama_kategori', 'from', 'to', 'duedate', 'site_code', 
            'branch_name', 'nama_comp', 'nomor_ajuan', 'nama_status', 'nama_status_internal', 'pic_userid_username',
            'duedate_response' 
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('export claim'); 

    }

    public function ajuan_claim_save()
    {
        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) 
        {
            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) 
            {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/ajuan_claim');
                die;
            }else
            {
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
            }
        }else
        {
            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
        }

        $site_code          = $this->input->post('site_code');
        $branch_name        = $this->input->post('branch_name');
        $nama_comp          = $this->input->post('nama_comp');
        $nama_pengirim      = $this->input->post('nama_pengirim');
        $email_pengirim     = $this->input->post('email_pengirim');
        $signature_program  = $this->input->post('signature_program');  
        $status_data_final  = $this->input->post('status_data_final');       
        $nomor_ajuan        = $this->input->post('nomor_ajuan'); 
        $signature          = 'ajuan-claim-'.rand().md5($this->created_at.rand());

        $get_data = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "pengajuan claim failed. Please try again");
            redirect('management_claim/form_ajuan_claim/'.$signature_program);
        }else
        {
            $first_pic  = $get_data->row()->pic;
            $id_program = $get_data->row()->id;
            $kategori   = $get_data->row()->kategori;
            $updated_by = $get_data->row()->updated_by;
            $segment    = $get_data->row()->segment;
            $supp       = $get_data->row()->supp;

            // jika first pic adalah mpm, maka $pic_userid adalah updated_program_by
            // jika selain mpm, maka akan mengikuti master_struktural
            if ($first_pic == 'MPM') {
                $pic_userid = $updated_by;
                $status_internal = 9; // 9 artinya pending admin mpm
            }else{
                $status_internal = 2;  // 2 artinya pending principal
                $get_pic = $this->model_management_claim->get_master_region_by_site_code_supp_segment($site_code, $supp, $segment);
                if ($get_pic -> num_rows() > 0)
                {    
                    $pic_userid = $get_pic->row()->pic_principal;
                    // mungkin kedepan mau di pakai. ini konsep multi pic on duty
                    // $pic_userid = '';
                    // foreach ($get_pic->result() as $a) 
                    // {
                    //     $pic_userid.=",".$a->pic_principal;
                    // }
                    // $pic_userid = preg_replace('/,/', '', $pic_userid,1);
                }else{
                    $this->session->set_flashdata("pesan", "pengajuan claim gagal. data mapping pic tidak ditemukan. silahkan hubungi  PIC MPM terkait");
                    redirect('management_claim/form_ajuan_claim/'.$signature_program);
                }
            }
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config($kategori);        

        if ($this->upload->do_upload('ajuan_excel')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];
        }else
        {
            $filename_excel = '';
        };

        if ($this->upload->do_upload('ajuan_zip')) 
        {
            $upload_data = $this->upload->data();
            $filename_zip = $upload_data['file_name'];
        }else
        {
            $filename_zip = '';
        };

        // nomor ajuan
        // echo "nomor_ajuan : ".$nomor_ajuan;
        $status = 2; // on progress

        $is_exist = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->created_by);
        if (!$is_exist->num_rows() > 0) 
        {
            // insert data baru
            $nomor_ajuan = $this->model_management_claim->generate($site_code, $this->created_at);

            $data = [
                "nomor_ajuan"   => $nomor_ajuan,
                "branch_name"   => $branch_name,
                "nama_comp"     => $nama_comp,
                "nama_pengirim" => $nama_pengirim,
                "email_pengirim"=> $email_pengirim,
                "site_code"     => $site_code,
                "id_program"    => $id_program,
                "ajuan_excel"   => $filename_excel,
                "ajuan_zip"     => $filename_zip,
                'status'        => $status,
                'nama_status'   => ($this->model_management_claim->get_status($status)->num_rows() > 0) ? $this->model_management_claim->get_status($status)->row()->nama_status : '',
                'status_internal'   => $status_internal,
                'nama_status_internal'  => ($this->model_management_claim->get_status_internal($status_internal)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '',
                'signature'     => $signature,
                'tanggal_claim' => $this->created_at,
                'pic_userid'    => $pic_userid,
                'created_at'    => $this->created_at,
                'created_by'    => $this->created_by,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
                'signature'     => $signature
            ];

            $insert = $this->model_management_claim->insert_ajuan_claim($data);

            if ($insert) {

                $data_log = [
                    "id_registrasi" => $id_program,
                    "id_ajuan"      => $insert,
                    "status"        => $status,
                    "status_internal"   => $status_internal,
                    "function"      => "ajuan_claim_save",
                    "file"          => $filename_excel,
                    "file_zip"      => $filename_zip,
                    "signature"     => 'log-'.rand().md5($this->created_at.rand()),
                    "created_at"    => $this->created_at,
                    "created_by"    => $this->created_by,
                    "updated_at"    => $this->created_at,
                    "updated_by"    => $this->created_by,
                    "on_duty_finish"=> 0,
                    "pic_on_duty"   => $pic_userid
                ];
                $insert_log = $this->model_management_claim->insert_log_claim($data_log);
                if ($insert_log) 
                {
                    // update ajuan_claim set id_log
                    $update_ajuan = [
                        "id_log"    => $insert_log,
                    ];
                    $update = $this->model_management_claim->update_ajuan_claim($update_ajuan, $insert);

                    $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
                    $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");
                    redirect('management_claim/form_ajuan_claim/'.$signature_program);
                    die;
                }else{
                    $log_error = [
                        "id_registrasi" => $id_program,
                        "id_ajuan"      => $insert,
                        "created_at"    => $this->created_at,
                        "created_by"    => $this->created_by,
                    ];
                    $insert_log_error = $this->model_management_claim->insert_log_error($log_error);
                    $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
                    $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");
                    redirect('management_claim/form_ajuan_claim/'.$signature_program);
                    die;
                }
            }
            

        }else{

            echo "update_data";
            die;
            // update data 
            $id_ajuan = $is_exist->row()->id;

            $data = [
                "nama_pengirim" => $nama_pengirim,
                "email_pengirim"=> $email_pengirim,
                "ajuan_excel"   => $filename_excel,
                "ajuan_zip"     => $filename_zip,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
            ];

            $update = $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);

            if ($update) {

                $data_log = [
                    "id_registrasi" => $id_program,
                    "id_ajuan"      => $insert,
                    "status"        => $status,
                    "status_internal"   => $status_internal,
                    "function"      => "ajuan_claim_save",
                    "file"          => $filename_excel,
                    "file_zip"      => $filename_zip,
                    "signature"     => 'log-'.rand().md5($this->created_at.rand()),
                    "created_at"    => $this->created_at,
                    "created_by"    => $this->created_by,
                    "updated_at"    => $this->created_at,
                    "updated_by"    => $this->created_by,
                    "on_duty_finish"=> 0,
                    "pic_on_duty"   => $pic_userid
                ];
                $insert_log = $this->model_management_claim->insert_log_claim($data_log);
                if ($insert_log) {
                    $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
                    // redirect('management_claim/email_status/'.$signature_program.'/'.$signature);
                    $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");
                    redirect('management_claim/form_ajuan_claim/'.$signature_program);
                    die;
                }else{
                    $log_error = [
                        "id_registrasi" => $id_program,
                        "id_ajuan"      => $insert,
                        "created_at"    => $this->created_at,
                        "created_by"    => $this->created_by,
                    ];
                    $insert_log_error = $this->model_management_claim->insert_log_error($log_error);
                    $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
                    $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");
                    redirect('management_claim/form_ajuan_claim/'.$signature_program);
                    die;
                }
            }

        }


        die;


        

        




        

        // cek registrasi
        // $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        // if ($get_registrasi_program_by_signature->num_rows > 0) 
        // {
        //     $id_program  = $get_registrasi_program_by_signature->row()->id;
        //     $kategori    = $get_registrasi_program_by_signature->row()->kategori;
        //     $namasupp    = $this->model_master_data->get_namasupp_by_supp($get_registrasi_program_by_signature->row()->supp)->row()->NAMASUPP;
        //     $from        = $get_registrasi_program_by_signature->row()->from;
        //     $to          = $get_registrasi_program_by_signature->row()->to;
        //     $nama_program= $get_registrasi_program_by_signature->row()->nama_program;
        //     $nomor_surat = $get_registrasi_program_by_signature->row()->nomor_surat;
        //     $syarat      = $get_registrasi_program_by_signature->row()->syarat;
        //     $duedate     = $get_registrasi_program_by_signature->row()->duedate;
        //     $upload_jpg  = $get_registrasi_program_by_signature->row()->upload_jpg;
        //     $upload_pdf  = $get_registrasi_program_by_signature->row()->upload_pdf;
        //     $upload_template_program = $get_registrasi_program_by_signature->row()->upload_template_program;
        //     $supp               = $get_registrasi_program_by_signature->row()->supp;
        //     $username = $this->model_master_data->get_username_by_id($get_registrasi_program_by_signature->row()->created_by)->row()->username;
        //     $first_hand         = $get_registrasi_program_by_signature->row()->first_hand;
        //     $program_created_by = $get_registrasi_program_by_signature->row()->created_by; 
        //     $status_validasi    = $get_registrasi_program_by_signature->row()->status_validasi;
        //     if ($status_validasi == null) {
        //         $params_status_validasi = 1;
        //     }else{
        //         $params_status_validasi = $status_validasi;
        //     }
        //     if ($params_status_validasi == 1) {
        //         $params_folder = "import";
        //     }else{
        //         $params_folder = null;
        //     }
        // }else
        // {
        //     $this->session->set_flashdata("pesan", "data not found");
        //     redirect('management_claim/ajuan_claim/');
        //     die;
        // }

        $status                 = 2; // ini akan menjadi on progress
        $params_tanggal_claim   = $created_at;

        // echo "first_hand : ".$first_hand;
        // jika first_hand kosong, maka akan diarahkan ke mpm
        if ($first_hand == '') {
            $first_hand = 'mpm';
        }

        // echo "first_hand : ".$first_hand;
        // die;  
        $get_pic_region_by_site_code = $this->model_management_claim->get_pic_region_by_site_code($site_code);
        if ($get_pic_region_by_site_code->num_rows() > 0) 
        {
            if ($first_hand == "principal") 
            {
                $status_internal = 2; // ini akan menjadi pending principal area
                $pic_userid = $get_pic_region_by_site_code->row()->userid_principal;
            }elseif ($first_hand == "mpm") 
            {
                $status_internal = 9; // ini akan menjadi pending admin mpm
                // jika kedepannya konsep diubah menjadi setiap admin akan memegang site_code masing-masing
                // $pic_userid = $get_pic_region_by_site_code->row()->userid_mpm;
                $pic_userid = $program_created_by;
            }else
            {
                $this->session->set_flashdata("pesan", "pengajuan gagal. data mapping pic region not found. silahkan infokan ini ke PIC terkait.");
                redirect('management_claim/ajuan_claim/');
            }
        }else
        {
            $this->session->set_flashdata("pesan", "pengajuan gagal. data mapping pic region not found. silahkan infokan ini ke PIC terkait.");
            redirect('management_claim/ajuan_claim/');
        }

        // echo "first_hand : ".$first_hand."<br>";
        // echo "status_internal : ".$status_internal."<br>";
        // echo "status : ".$status."<br>";
        // echo "pic_userid : ".$pic_userid;
        // die;

        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('ajuan_excel')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_excel = '';
        };

        if ($this->upload->do_upload('ajuan_zip')) 
        {
            $upload_data = $this->upload->data();
            $filename_zip = $upload_data['file_name'];

        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_zip = '';
        };

        // echo "filename_zip : ".$filename_zip;
        // die;

        $get_ajuan_claim_by_id_program_and_user = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->session->userdata('id'));

        if ($get_ajuan_claim_by_id_program_and_user->num_rows() > 0) 
        {
            $id_ajuan = $get_ajuan_claim_by_id_program_and_user->row()->id;
            // update data
            $data = [
                'branch_name'       => $get_ajuan_claim_by_id_program_and_user->row()->branch_name,
                'nama_comp'         => $get_ajuan_claim_by_id_program_and_user->row()->nama_comp,
                'site_code'         => $get_ajuan_claim_by_id_program_and_user->row()->site_code,
                'nama_pengirim'     => $get_ajuan_claim_by_id_program_and_user->row()->nama_pengirim,
                'email_pengirim'    => $get_ajuan_claim_by_id_program_and_user->row()->email_pengirim,
                'ajuan_excel'       => $filename_excel,
                'ajuan_zip'         => $filename_zip,
                'status'            => $status,
                'nama_status'       => ($this->model_management_claim->get_status($status)->num_rows() > 0) ? $this->model_management_claim->get_status($status)->row()->nama_status : '',
                'status_internal'   => $status_internal,
                'nama_status_internal'=> ($this->model_management_claim->get_status_internal($status_internal)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '',
                'pic_userid'        => $pic_userid,
                'status_data_final' => 1,
                'tanggal_claim'     => $params_tanggal_claim,
                'created_at'        => $created_at,
                'created_by'        => $this->session->userdata('id'),
                'folder_file'       => $params_folder
            ];
            $this->db->where('signature', $get_ajuan_claim_by_id_program_and_user->row()->signature);
            $this->db->update('management_claim.ajuan_claim', $data);

            $insert_log = $this->model_management_claim->insert_log($id_program, $id_ajuan, 2, $status_internal, 'ajuan_claim_save', '', $filename_excel.'-'.$filename_zip);

            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->session->userdata('id'), 0);

            redirect('management_claim/email_status/'.$signature_program.'/'.$get_ajuan_claim_by_id_program_and_user->row()->signature);
            die;

        }else
        {
            $data = [
                'nomor_ajuan'       => $this->model_management_claim->generate($this->input->post('from_site'), $created_at),
                'branch_name'       => $branch_name,
                'nama_comp'         => $nama_comp,
                'site_code'         => $site_code,
                'nama_pengirim'     => $nama_pengirim,
                'email_pengirim'    => $email_pengirim,
                'ajuan_excel'       => $filename_excel,
                'ajuan_zip'         => $filename_zip,
                'id_program'        => $id_program,
                'status'            => $status,
                'nama_status'       => ($this->model_management_claim->get_status($status)->num_rows() > 0) ? $this->model_management_claim->get_status($status)->row()->nama_status : '',
                'status_internal'   => $status_internal,
                'nama_status_internal'=> ($this->model_management_claim->get_status_internal($status_internal)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '',
                'pic_userid'        => $pic_userid,
                'status_data_final' => 1,
                'signature'         => $signature,
                'tanggal_claim'     => $params_tanggal_claim,
                'created_at'        => $created_at,
                'created_by'        => $this->session->userdata('id'),
                'folder_file'       => $params_folder
            ];
            $this->db->insert('management_claim.ajuan_claim', $data);
            $id_ajuan = $this->db->insert_id();

            $insert_log = $this->model_management_claim->insert_log($id_program, $id_ajuan, 2, $status_internal, 'ajuan_claim_save', '', $filename_excel.'-'.$filename_zip);

            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->session->userdata('id'), 0);

            redirect('management_claim/email_status/'.$signature_program.'/'.$signature);
            die;
        }
    }

    public function delete_ajuan($signature){

        $created_at = $this->model_outlet_transaksi->timezone();
        $data = [
            'deleted'   => 1,
            'deleted_at'    => $created_at
        ];

        $this->db->where('signature', $signature);
        $this->db->update('management_claim.ajuan_claim', $data);
        redirect('management_claim/ajuan_claim');
    }

    public function delete_ajuan_claim($signature){

        $get_ajuan_by_signature = $this->get_ajuan_by_signature($signature);
        $id_ajuan = $get_ajuan_by_signature['id_ajuan'];
        $id_program = $get_ajuan_by_signature['id_program'];

        $get_program = $this->model_management_claim->get_registrasi_program_by_only_id_program($id_program);
        if (!$get_program->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Program tidak ditemukan");
            redirect('management_claim/ajuan_claim');
        }

        $created_by = $get_program->row()->created_by;
        
        if ($created_by <> $this->created_by) {
            $this->session->set_flashdata("pesan", "Maaf, hapus claim gagal. Claim hanya dapat dihapus oleh PIC program terkait");
            redirect('management_claim/ajuan_claim');
        }

        $data = [
            'deleted'       => 1,
            'deleted_at'    => $this->created_at,
            'deleted_by'    => $this->created_by,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
        ];

        $update = $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);
        $this->session->set_flashdata("pesan_success", "delete berhasil");
        redirect('management_claim/ajuan_claim');
    }

    public function undelete_ajuan_claim($signature){

        $get_ajuan_by_signature = $this->get_ajuan_by_signature($signature);
        $id_ajuan = $get_ajuan_by_signature['id_ajuan'];
        $id_program = $get_ajuan_by_signature['id_program'];

        $is_exist = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->created_by);
        if ($is_exist->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "undelete gagal. Anda sudah pernah mengajukan claim program ini sebelumnya. 1 program tidak boleh lebih dari 1 ajuan claim");
            redirect('management_claim/ajuan_claim');

        }

        $data = [
            'deleted'       => 0,
            'deleted_at'    => null,
            'deleted_by'    => null,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
        ];

        $update = $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);
        $this->session->set_flashdata("pesan_success", "undelete berhasil");
        redirect('management_claim/ajuan_claim');
    }

    public function verifikasi_ajuan_claim($signature){


        $cek_user_pengajuan = $this->model_management_claim->cek_user_pengajuan($signature);
        
        if ($cek_user_pengajuan->num_rows > 0) {
            redirect('management_claim/ajuan_revisi/'.$signature);
        }

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $signature_program = $this->model_management_claim->get_registrasi_program_by_signature_ajuan($signature)->row()->signature;

        $data = [
            'title'                     => 'management claim | verifikasi ajuan claim',
            'get_registrasi_program'    => $this->model_management_claim->get_registrasi_program($kode_alamat, $signature_program),
            'get_verifikasi_ajuan'      => $this->model_management_claim->get_verifikasi_ajuan($signature),
            'url'                       => 'management_claim/ajuan_claim_update',
            'signature_ajuan'           => $signature,          
            'site_code'                 => $this->M_helpdesk->get_sitecode(),
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/verifikasi_ajuan_claim', $data);
        $this->load->view('kalimantan/footer');

    }

    public function ajuan_claim_update(){
        $signature = md5($this->model_outlet_transaksi->timezone());
        $nomor_ajuan  = $this->input->post('nomor_ajuan');
        $status  = $this->input->post('status');
        if ($status == 3) {
            $nama_status = 'On MPM Check';
        }elseif ($status == 4) {
            $nama_status = 'On Principal Check';
        }elseif ($status == 5) {
            $nama_status = 'Reject Principal';
        }elseif ($status == 6) {
            $nama_status = 'Approve';
        }elseif ($status == 7) {
            $nama_status = 'DP Kirim DN (Debit Note / Faktur Pajak)';
        }elseif ($status == 8) {
            $nama_status = 'Finance (Principal kirim ke MPM)';
        }elseif ($status == 9) {
            $nama_status = 'Finance (MPM kirim ke DP)';
        }

        $tanggal  = $this->input->post('tanggal');
        $signature_ajuan  = $this->input->post('signature_ajuan');
        $catatan_verifikasi  = $this->input->post('catatan_verifikasi');

        // $this->db->trans_start();
        $data = [
            "nomor_ajuan"           => $nomor_ajuan,
            "status"                => $status,
            "nama_status"           => $nama_status,
            "tanggal"               => $tanggal,
            "catatan_verifikasi"    => $catatan_verifikasi,
            "created_at"            => $this->model_outlet_transaksi->timezone(),
            "created_by"            => $this->session->userdata('id'),
            "signature"             => $signature,
            "signature_ajuan"       => $signature_ajuan
        ];

        $this->db->insert('management_claim.verifikasi_ajuan', $data);

        $update = [
            'status'        => $status,
            'nama_status'   => $nama_status,
            'pic_mpm'       => $this->model_management_claim->get_data_user($this->session->userdata('id'))->row()->username,
        ];
        $this->db->where('signature', $signature_ajuan);
        $this->db->update('management_claim.ajuan_claim', $update);

        // $this->db->trans_complete();

        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan update. rollback active";
        //     die;
        // }

        redirect('management_claim/ajuan_claim');
    }

    public function truncate_registrasi_program(){
        $query = "
            truncate management_claim.registrasi_program
        ";
        $this->db->query($query);
        redirect('management_claim/registrasi_program');
    }

    public function site(){
        $data = [
            'title'     => 'management claim | site',
            'get_site'  => $this->model_management_claim->get_site(),
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/site', $data);
        $this->load->view('kalimantan/footer');
    }

    public function update_site($site_code){
        $cek = $this->model_management_claim->get_site($site_code)->row()->status_claim;
        if ($cek == null || $cek == 0) {
            $status_claim = 1;
        }else{
            $status_claim = 0;
        }

        $data = [
            "status_claim"  => $status_claim
        ];
        $this->db->where("concat(kode_comp, nocab) = '$site_code'");
        $this->db->update('mpm.tbl_tabcomp', $data);
        redirect('management_claim/site');

    }

    public function ajuan_revisi($signature){
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $signature_program = $this->model_management_claim->get_registrasi_program_by_signature_ajuan($signature)->row()->signature;

        $data = [
            'title'                     => 'management claim | verifikasi ajuan claim',
            'get_registrasi_program'    => $this->model_management_claim->get_registrasi_program($kode_alamat, $signature_program),
            'get_verifikasi_ajuan'      => $this->model_management_claim->get_verifikasi_ajuan($signature),
            'url'                       => 'management_claim/ajuan_revisi_update',
            'signature_ajuan'           => $signature,          
            'site_code'                 => $this->M_helpdesk->get_sitecode(),
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/ajuan_revisi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function ajuan_revisi_update(){
        $signature_ajuan = $this->input->post('signature_ajuan');
        $nomor_ajuan = $this->input->post('nomor_ajuan');
        $ajuan_revisi = $this->input->post('ajuan_revisi');
        $catatan_revisi = $this->input->post('catatan_revisi');

        // echo "signature_ajuan : ".$signature_ajuan;
        // echo "nomor_ajuan : ".$nomor_ajuan;
        // echo "catatan_revisi : ".$catatan_revisi;

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = md5($this->model_outlet_transaksi->timezone());

        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('ajuan_revisi')) 
        {
            $upload_data = $this->upload->data();
            $filename_revisi = $upload_data['file_name'];

        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        $cek = $this->model_management_claim->cek_revisi_by_signature_ajuan($signature_ajuan);
        if ($cek->num_rows() > 0) {
            
            // $this->db->trans_start();
            $data = [
                "upload_revisi"         => $filename_revisi,
                "catatan_revisi"        => $catatan_revisi,
                "nomor_ajuan"           => $nomor_ajuan,
                "created_at"            => $this->model_outlet_transaksi->timezone(),
                "created_by"            => $this->session->userdata('id'),
                "signature"             => $signature,
                "signature_ajuan"       => $signature_ajuan
            ];

            $this->db->where('signature_ajuan', $signature_ajuan);
            $this->db->update('management_claim.revisi_ajuan', $data);
            // $this->db->trans_complete();

        }else{

            // $this->db->trans_start();
            $data = [
                "upload_revisi"         => $filename_revisi,
                "catatan_revisi"        => $catatan_revisi,
                "nomor_ajuan"           => $nomor_ajuan,
                "created_at"            => $this->model_outlet_transaksi->timezone(),
                "created_by"            => $this->session->userdata('id'),
                "signature"             => $signature,
                "signature_ajuan"       => $signature_ajuan
            ];

            $this->db->insert('management_claim.revisi_ajuan', $data);
            // $this->db->trans_complete();

        }

        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan update revisi. rollback active";
        //     die;
        // }

        redirect('management_claim/ajuan_claim');

    }

    public function verifikasi_mpm($signature_program, $signature_ajuan)
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $username = $get_registrasi_program['username'];
        $username_email = $get_registrasi_program['username_email'];
        
        $get_ajuan_by_signature = $this->get_ajuan_by_signature($signature_ajuan);

        // die;
        $id_ajuan = $get_ajuan_by_signature['id_ajuan'];
        // $ajuan_by = $get_ajuan_by_signature['ajuan_by'];
        // $ajuan_by_email = $get_ajuan_by_signature['ajuan_by_email'];

        if ($id_ajuan) 
        {
            $get_pic = $this->model_management_claim->get_log_aktivitas_by_onduty($id_ajuan);
            if ($get_pic->num_rows > 0) {
                $pic_on_duty = $get_pic->row()->username;
                $email_on_duty = $get_pic->row()->email;
            }else{
                $pic_on_duty = $username;
                $email_on_duty = $username_email;
            }
        }else{
            $pic_on_duty = "";
            $email_on_duty = "";
        }

        // echo "pic_on_duty : ".$pic_on_duty;

        // status authorized
        if ($pic_on_duty == $this->session->userdata('username')) {
            $status_authorized = true;
        }else{
            $status_authorized = false;
        }


        $data = [
            'title'                     => 'management claim | Verifikasi MPM',
            'url'                       => 'management_claim/verifikasi_mpm_save',
            'signature_program'         => $signature_program,            
            'signature_ajuan'           => $signature_ajuan,   
            'kategori'                  => $get_registrasi_program['kategori'],      
            'namasupp'                  => $get_registrasi_program['namasupp'],      
            'from'                      => $get_registrasi_program['from'],      
            'to'                        => $get_registrasi_program['to'],      
            'nama_program'              => $get_registrasi_program['nama_program'],      
            'nomor_surat'               => $get_registrasi_program['nomor_surat'],          
            'duedate'                   => $get_registrasi_program['duedate'],      
            'upload_pdf'                => $get_registrasi_program['upload_pdf'],      
            'username'                  => $get_registrasi_program['username'],    
            'params_status_validasi'    => $get_registrasi_program['params_status_validasi'],
            'params_folder'             => $get_registrasi_program['params_folder'],
            'segment'                   => $get_registrasi_program['segment'],
            'nama_status_validasi'      => $get_registrasi_program['nama_status_validasi'],
            'keterangan'                => $get_registrasi_program['keterangan'],
            'nama_template'             => $get_registrasi_program['nama_template'],
            'filename'                  => $get_registrasi_program['filename'],
            'pic'                       => $get_registrasi_program['pic'],
            'id_kategori'               => $get_registrasi_program['id_kategori'],               
            'nama_pengirim'             => $get_ajuan_by_signature['nama_pengirim'],      
            'email_pengirim'            => $get_ajuan_by_signature['email_pengirim'],      
            'ajuan_excel'               => $get_ajuan_by_signature['ajuan_excel'],      
            'ajuan_zip'                 => $get_ajuan_by_signature['ajuan_zip'],         
            'created_at'                => $get_ajuan_by_signature['created_at'],      
            'nama_status'               => $get_ajuan_by_signature['nama_status'],      
            'nama_status_internal'      => $get_ajuan_by_signature['nama_status_internal'],  
            'nomor_ajuan'               => $get_ajuan_by_signature['nomor_ajuan'],      
            'site_code'                 => $get_ajuan_by_signature['site_code'], 
            'tanggal_claim'             => $get_ajuan_by_signature['tanggal_claim'],
            'branch_name'               => $get_ajuan_by_signature['branch_name'],
            'nama_comp'                 => $get_ajuan_by_signature['nama_comp'],
            'id_log'                    => $get_ajuan_by_signature['id_log'],
            'pic_on_duty'               => $pic_on_duty,
            'email_on_duty'             => $email_on_duty,
            'status_authorized'         => $status_authorized,
            'get_log'                   => $this->model_management_claim->get_log_aktivitas_by_id_ajuan($id_ajuan),
            'get_status_internal'       => $this->model_management_claim->get_status_internal('5,11,21,22,1',''),
            'tahun_folder'              => $get_registrasi_program['tahun_folder'],
            'tahun_folder_ajuan'        => $get_ajuan_by_signature['tahun_folder'],
        ];

        // echo "<pre>";print_r($data);echo "</pre>";

        // $this->view($data, true, "verifikasi_mpm");

        $this->render_multiple(
            array(
                'management_claim/accordion',
                'management_claim/verifikasi_mpm'
            ),
            $data
        );

    }

    public function verifikasi_mpm_save()
    {
        $status_internal    = $this->input->post('status_internal');
        $keterangan         = $this->input->post('keterangan');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');
        $id_log             = $this->input->post('id_log');
        $signature          = 'ver-claim-'.rand().md5($this->created_at.rand());

        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) 
        {
            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) 
            {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
                die;
            }else
            {
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
            }
        }else
        {
            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
        }

        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        // var_dump($get_registrasi_program);

        $id_program = $get_registrasi_program['id_program'];
        $id_kategori = $get_registrasi_program['id_kategori'];
        $kategori = $get_registrasi_program['kategori'];
        $pembuat_program = $get_registrasi_program['updated_by'];

        $ajuan_claim = $this->model_management_claim->get_ajuan_claim_by_id_program_and_signature($id_program, $signature_ajuan);

        if (!$ajuan_claim->num_rows > 0) {
            $this->session->set_flashdata("pesan", "Proses anda gagal. Data not found");
            redirect('management_claim/ajuan_claim/');    
        }else{
            $id_ajuan = $ajuan_claim->row()->id;
            $ajuan_by = $ajuan_claim->row()->created_by;
            $site_code = $ajuan_claim->row()->site_code;
        }
        
        // inisialisasi upload
        $init_upload = $this->attachment_config($id_kategori);        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else
        {
            $filename = '';
        };

        // echo "status_internal : ".$status_internal;
        // die;
        if ($status_internal == 22 || $status_internal == 5) // 22 = REJECT ADMIN MPM (REVISION), 5 = pending kirim hardcopy
        { 
            // kalau reject, maka akan dikembalikan ke dp untuk revisi
            $userid_head = $ajuan_by;
            $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
            $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
            $status = 1; // status menjadi pending dp

        }elseif ($status_internal == 11 || $status_internal == 21 ) // 11 = APPROVE ADMIN MPM, 21 = PROSES DN (FINANCE)
        { 
            $userid_head = $pembuat_program;
            $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
            $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
            $status = 18; // status menjadi done

        }elseif ($status_internal == 1 ) // 1 = pending dp
        { 
            $userid_head = $ajuan_by;
            $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
            $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
            $status = 1; // status menjadi pending dp

        }else{ // jika lainny
            $this->session->set_flashdata("pesan", "Status verifikasi anda gagal. Silahkan pilih status sesuai rule yang benar");
            redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
            die;
        }

        // echo "id_log : ".$id_log."<br>";
        // echo "id_program : ".$id_program."<br>";
        // echo "id_ajuan : ".$id_ajuan."<br>";
        // echo "status : ".$status."<br>";
        // echo "status_internal : ".$status_internal."<br>";
        // echo "keterangan : ".$keterangan."<br>";
        // echo "filename : ".$filename."<br>";

        // die;

        // echo "status_internal : ".$status_internal;
        $time_response = ($this->model_management_claim->get_status_internal($status_internal) ? $this->model_management_claim->get_status_internal($status_internal)->row()->time_response : 0);
        $duedate_response = date('Y-m-d', strtotime('+'.$time_response.' days', strtotime($this->created_at)));
        
        // insert log
        $data_log = [
            "ref_log"       => $id_log,
            "id_registrasi" => $id_program,
            "id_ajuan"      => $id_ajuan,
            "status"        => $status,
            "status_internal"   => $status_internal,
            "keterangan"    => $keterangan,
            "function"      => "verifikasi_mpm_save",
            "file"          => $filename,
            "signature"     => 'log-'.rand().md5($this->created_at.rand()),
            "created_at"    => $this->created_at,
            "created_by"    => $this->created_by,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->created_by,
            "on_duty_finish"=> 0,
            "pic_on_duty"   => $userid_head,
            "level_on_duty" => $level_on_duty,            
            'time_response' => $time_response,
            'duedate_response' => $duedate_response
        ];
        $insert_log = $this->model_management_claim->insert_log_claim($data_log);

        // update ajuan_claim
        $update_ajuan = [
            "id_log"            => $insert_log,
            "pic_userid"        => $userid_head,
            "status"            => $status,
            "status_internal"   => $status_internal,
            "updated_at"        => $this->created_at,
            "updated_by"        => $this->created_by
        ];
        $proses_update_ajuan = $this->model_management_claim->update_ajuan_claim($update_ajuan, $id_ajuan);

        $update_log = [
            "on_duty_finish"    => 1,
            "updated_by"        => $this->created_by,
            "updated_at"        => $this->created_at
        ];
        $this->model_management_claim->update_log_claim($update_log, $id_log);

        
        $this->session->set_flashdata("pesan_success", "verifikasi anda berhasil");
        redirect('management_claim/email/'.$signature_program.'/'.$signature_ajuan);
        // redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
        die;
    }

    public function verifikasi_mpm_save_old()
    {
        $status_internal    = $this->input->post('status_internal');
        $keterangan         = $this->input->post('keterangan');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');
        $created_at         = $this->model_outlet_transaksi->timezone();
        $signature          = md5($this->model_outlet_transaksi->timezone());

        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);

        if(!$get_registrasi_program_by_signature->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. Data not found !! ");
            redirect('management_claim/ajuan_claim/');
            die;
        }else
        {
            $id_program         = $get_registrasi_program_by_signature->row()->id;
            $program_created_by = $get_registrasi_program_by_signature->row()->created_by; 
        }

        $get_ajuan_claim_by_id_program = $this->model_management_claim->get_ajuan_claim_by_id_program_and_signature($id_program, $signature_ajuan);

        if(!$get_ajuan_claim_by_id_program->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. Data not found !! ");
            redirect('management_claim/ajuan_claim/');
            die;
        }else
        {
            $id_ajuan    = $get_ajuan_claim_by_id_program->row()->id;
            $nomor_ajuan = $get_ajuan_claim_by_id_program->row()->nomor_ajuan;
            $site_code   = $get_ajuan_claim_by_id_program->row()->site_code;
            $ajuan_by    = $get_ajuan_claim_by_id_program->row()->created_by;
            $username_dp = $this->model_master_data->get_username_by_id($ajuan_by)->row()->username;
        }

        // cek pic userid
        $get_pic_region_by_site_code = $this->model_management_claim->get_pic_region_by_site_code($site_code);
        if ($get_pic_region_by_site_code->num_rows() > 0) 
        {    
            $userid_principal = $get_pic_region_by_site_code->row()->userid_principal;

            // userid_mpm diperlukan untuk kedepannya saja
            // $userid_mpm = $get_pic_region_by_site_code->row()->userid_mpm;
            $pic_userid = $program_created_by;

        }else{
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. data mapping pic region not found. silahkan infokan ini ke PIC terkait.");
            redirect('management_claim/ajuan_claim/');
        }

        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        /* config upload */
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);
        /* end config upload */

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename = '';
        };

        // echo "status_internal : ".$status_internal;
        // die;

        if ($status_internal == 1) // jika status_internal == 1 atau pending dp, maka $status == 1 dan tanggal_claim == null
        {
            $status = 1;
            $params_created_at = NULL;
            $pic_userid = $ajuan_by; // pic di kembalikan ke dp
        }elseif ($status_internal == 2) // jika status_internal == 2 atau pending principal area, maka $status == 2
        {
            $status = 2;
            $params_created_at = $created_at;
            $pic_userid = $userid_principal;
        }elseif ($status_internal == 4) // jika status_internal == 4 atau APPROVE PRINCIPAL AREA, maka $status == 2
        {
            $status = 2;
            $params_created_at = $created_at;
            $pic_userid = $program_created_by; // dikembalikan ke pembuat program
        }
        elseif ($status_internal == 5 || $status_internal == 7) // jika status_internal == 5 / pending hardcopy dp || 7 yaitu reject hardcopy dp, maka $status == 3
        {
            $status = 3; // status 3 = pending hardcopy dp
            $params_created_at = $created_at;
            $pic_userid = $ajuan_by; // pic di kembalikan ke dp
        }elseif ($status_internal == 10) // jika status_internal == 10 / reject admin mpm
        {
            $status = 1;
            $params_created_at = NULL;
            $pic_userid = $ajuan_by; // pic di kembalikan ke dp
        }elseif ($status_internal == 8 || $status_internal == 9 || $status_internal == 11 || $status_internal == 12 || $status_internal == 13 || $status_internal == 14 || $status_internal == 15 || $status_internal == 16 || $status_internal == 17 || $status_internal == 20) 
        // 8 = approve hardcopy, 9 = pending admin mpm, 11 = approve admin mpm, 12 = pending principal ho
        {
            $status = 2;
            $params_created_at = $created_at;
            $pic_userid = $program_created_by;
        }else
        {
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. Data not found !! ");
            redirect('management_claim/ajuan_claim/');
            die;
        }

        // echo "status_internal : ".$status_internal;
        // echo "pic_userid : ".$pic_userid;
        // echo "status : ".$status;
        // die;
        
        $data = [
            'id_program'         => $id_program,
            'id_ajuan'           => $id_ajuan,
            'nomor_ajuan'        => $nomor_ajuan,
            'keterangan'         => $keterangan,
            'status_internal'    => $status_internal,
            'nama_status_internal'=> ($this->model_management_claim->get_status_internal($status_internal)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '',
            'file'               => $filename,
            'signature'          => $signature,
            'signature_program'  => $signature_program,
            'signature_ajuan'    => $signature_ajuan,
            'created_at'         => $created_at,
            'created_by'         => $this->session->userdata('id'),
        ];

        $this->db->insert('management_claim.verifikasi_ajuan', $data);
        $id_verifikasi = $this->db->insert_id();

        $update = [
            'status'            => $status,
            'nama_status'       => ($this->model_management_claim->get_status($status)->num_rows() > 0) ? $this->model_management_claim->get_status($status)->row()->nama_status : '',
            'status_internal'   => $status_internal,
            'nama_status_internal'=> ($this->model_management_claim->get_status_internal($status_internal)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '',
            'keterangan_mpm'    => $keterangan,
            'file_mpm'          => $filename,
            'pic_mpm'           => $this->session->userdata('id'),
            'mpm_at'            => $created_at,
            'last_updated_at'   => $created_at,
            'id_verifikasi'     => $id_verifikasi,
            'tanggal_claim'     => $params_created_at,
            'pic_userid'        => $pic_userid
        ];

        $this->db->where('id', $id_ajuan);
        $this->db->update('management_claim.ajuan_claim', $update);

        $insert_log = $this->model_management_claim->insert_log($id_program, $id_ajuan, $status, $status_internal, 'verifikasi_mpm_save', $keterangan, $filename);

        redirect('management_claim/email_status/'.$signature_program.'/'.$signature_ajuan);
        die;
    }

    public function verifikasi_principal($signature_program, $signature_ajuan)
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $get_ajuan_by_signature = $this->get_ajuan_by_signature($signature_ajuan);
        $id_ajuan = $get_ajuan_by_signature['id_ajuan'];

        if ($id_ajuan) 
        {
            $get_pic = $this->model_management_claim->get_log_aktivitas_by_onduty($id_ajuan);
            if ($get_pic->num_rows > 0) {
                $pic_on_duty = $get_pic->row()->username;
                $email_on_duty = $get_pic->row()->email;
            }else{
                $pic_on_duty = "";
                $email_on_duty = "";
            }
        }else{
            $pic_on_duty = "";
            $email_on_duty = "";
        }

        // status authorized
        if ($pic_on_duty == $this->session->userdata('username')) {
            $status_authorized = true;
        }else{
            $status_authorized = false;
        }

        $data = [
            'title'                     => 'management claim | Verifikasi Principal',
            'url'                       => 'management_claim/verifikasi_principal_save',
            'signature_program'         => $signature_program,            
            'signature_ajuan'           => $signature_ajuan,   
            'kategori'                  => $get_registrasi_program['kategori'],      
            'namasupp'                  => $get_registrasi_program['namasupp'],      
            'from'                      => $get_registrasi_program['from'],      
            'to'                        => $get_registrasi_program['to'],      
            'nama_program'              => $get_registrasi_program['nama_program'],      
            'nomor_surat'               => $get_registrasi_program['nomor_surat'],          
            'duedate'                   => $get_registrasi_program['duedate'],      
            'upload_pdf'                => $get_registrasi_program['upload_pdf'],      
            'username'                  => $get_registrasi_program['username'],    
            'params_status_validasi'    => $get_registrasi_program['params_status_validasi'],
            'params_folder'             => $get_registrasi_program['params_folder'],
            'segment'                   => $get_registrasi_program['segment'],
            'nama_status_validasi'      => $get_registrasi_program['nama_status_validasi'],
            'keterangan'                => $get_registrasi_program['keterangan'],
            'nama_template'             => $get_registrasi_program['nama_template'],
            'filename'                  => $get_registrasi_program['filename'],
            'pic'                       => $get_registrasi_program['pic'],
            'id_kategori'               => $get_registrasi_program['id_kategori'],               
            'nama_pengirim'             => $get_ajuan_by_signature['nama_pengirim'],      
            'email_pengirim'            => $get_ajuan_by_signature['email_pengirim'],      
            'ajuan_excel'               => $get_ajuan_by_signature['ajuan_excel'],      
            'ajuan_zip'                 => $get_ajuan_by_signature['ajuan_zip'],         
            'created_at'                => $get_ajuan_by_signature['created_at'],      
            'nama_status'               => $get_ajuan_by_signature['nama_status'],      
            'nama_status_internal'      => $get_ajuan_by_signature['nama_status_internal'],  
            'nomor_ajuan'               => $get_ajuan_by_signature['nomor_ajuan'],      
            'site_code'                 => $get_ajuan_by_signature['site_code'], 
            'tanggal_claim'             => $get_ajuan_by_signature['tanggal_claim'],
            'branch_name'               => $get_ajuan_by_signature['branch_name'],
            'nama_comp'                 => $get_ajuan_by_signature['nama_comp'],
            'id_log'                    => $get_ajuan_by_signature['id_log'],
            'pic_on_duty'               => $pic_on_duty,
            'email_on_duty'             => $email_on_duty,
            'status_authorized'         => $status_authorized,
            'get_log'                   => $this->model_management_claim->get_log_aktivitas_by_id_ajuan($id_ajuan),            
            'get_status_internal'       => $this->model_management_claim->get_status_internal('4,23',''),       
            'tahun_folder'              => $get_registrasi_program['tahun_folder'],
            'tahun_folder_ajuan'        => $get_ajuan_by_signature['tahun_folder'],
        ];


        $this->render_multiple(
            array(
                'management_claim/accordion',
                'management_claim/verifikasi_principal'
            ),
            $data
        );

    }

    public function get_registrasi_program($signature_program)
    {
        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_registrasi_program_by_signature->num_rows > 0) 
        {
            // echo "jika tidak ";
            // die;
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');            
        }else
        {
            // echo "jika ada ";
            // die;
            $supp = $get_registrasi_program_by_signature->row()->supp;
            $created_by = $get_registrasi_program_by_signature->row()->created_by;
            
            $status_validasi = $get_registrasi_program_by_signature->row()->status_validasi;
            if ($status_validasi == null) {
                $params_status_validasi = 1;
            }else{
                $params_status_validasi = $status_validasi;
            }

            if ($params_status_validasi == 1) {
                $params_folder = "import";
            }else{
                $params_folder = "";
            }

            $data = [
                "id_program"    => $get_registrasi_program_by_signature->row()->id,
                "id_kategori"   => $get_registrasi_program_by_signature->row()->kategori,
                "kategori"      => ($get_registrasi_program_by_signature->row()->nama_kategori) ? $get_registrasi_program_by_signature->row()->nama_kategori : $get_registrasi_program_by_signature->row()->kategori,
                "namasupp"      => $this->model_master_data->get_namasupp_by_supp($supp)->row()->namasupp,                
                "from"          => $get_registrasi_program_by_signature->row()->from,
                "to"            => $get_registrasi_program_by_signature->row()->to,
                "nama_program"  => $get_registrasi_program_by_signature->row()->nama_program,
                "nomor_surat"   => $get_registrasi_program_by_signature->row()->nomor_surat,
                "syarat"        => $get_registrasi_program_by_signature->row()->syarat,
                "duedate"       => $get_registrasi_program_by_signature->row()->duedate,
                "upload_pdf"    => $get_registrasi_program_by_signature->row()->upload_pdf,
                "segment"       => $get_registrasi_program_by_signature->row()->segment,
                "upload_template_program" => $get_registrasi_program_by_signature->row()->upload_template_program,
                "supp"          => $get_registrasi_program_by_signature->row()->supp,
                "username"      => $this->model_management_claim->get_user($created_by)->row()->username,
                "status_validasi" => $get_registrasi_program_by_signature->row()->status_validasi,
                "params_status_validasi" => $params_status_validasi,
                "params_folder" => $params_folder,
                "nama_status_validasi" => $get_registrasi_program_by_signature->row()->nama_status_validasi,
                "keterangan" => $get_registrasi_program_by_signature->row()->keterangan,
                "nama_template" => $get_registrasi_program_by_signature->row()->nama_template,
                "filename" => $get_registrasi_program_by_signature->row()->filename,
                "pic" => $get_registrasi_program_by_signature->row()->pic,
                "updated_by" => $get_registrasi_program_by_signature->row()->updated_by, 
                "username_email"      => $this->model_management_claim->get_user($created_by)->row()->email,
                "tahun_folder" => $get_registrasi_program_by_signature->row()->tahun_folder
            ];
        }

        return $data;
        
    }

    public function get_ajuan_by_signature($signature_ajuan)
    {

        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature_ajuan);
        if (!$get_ajuan_by_signature->num_rows > 0) 
        {
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');              
        }else
        {
            $status = ($get_ajuan_by_signature->row()->status) ? $get_ajuan_by_signature->row()->status : 0;
            $site_code = $get_ajuan_by_signature->row()->site_code;
            $status_internal = $get_ajuan_by_signature->row()->status_internal;
            $data = [
                "created_at"        => $get_ajuan_by_signature->row()->created_at,
                "signature_ajuan"   => $get_ajuan_by_signature->row()->signature,
                "nama_pengirim"     => $get_ajuan_by_signature->row()->nama_pengirim,
                "email_pengirim"    => $get_ajuan_by_signature->row()->email_pengirim,
                "ajuan_excel"       => $get_ajuan_by_signature->row()->ajuan_excel,
                "ajuan_zip"         => $get_ajuan_by_signature->row()->ajuan_zip,
                "status"            => $get_ajuan_by_signature->row()->status,
                // "nama_status"       => $this->model_management_claim->get_status($status)->row()->nama_status,
                "nama_status"       => ($status) ? $this->model_management_claim->get_status($status)->row()->nama_status :'null',
                "id_ajuan"          => $get_ajuan_by_signature->row()->id,
                "nomor_ajuan"       => $get_ajuan_by_signature->row()->nomor_ajuan,
                "tanggal_claim"     => $get_ajuan_by_signature->row()->tanggal_claim,
                "site_code"         => $site_code,
                "branch_name"       => $this->model_management_claim->get_site($site_code)->row()->branch_name,
                "nama_comp"         => $this->model_management_claim->get_site($site_code)->row()->nama_comp,
                "id_log"            => $get_ajuan_by_signature->row()->id_log,
                // "log_keterangan"    => $this->model_management_claim->get_log_aktivitas_by_id($get_ajuan_by_signature->row()->id_log)->row()->log_keterangan,
                "log_keterangan"    => ($this->model_management_claim->get_log_aktivitas_by_id($get_ajuan_by_signature->row()->id_log)->num_rows > 0) ? $this->model_management_claim->get_log_aktivitas_by_id($get_ajuan_by_signature->row()->id_log)->row()->log_keterangan : 'null',
                "status_internal"   => $status_internal,
                "nama_status_internal" => $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status,
                "id_program"        => $get_ajuan_by_signature->row()->id_program,
                "status_keikutsertaan" => $get_ajuan_by_signature->row()->status_keikutsertaan,
                "pic_userid"        => $get_ajuan_by_signature->row()->pic_userid,
                
                "nomor_hardcopy"       => $get_ajuan_by_signature->row()->nomor_hardcopy,
                "tanggal_kirim_hardcopy"       => $get_ajuan_by_signature->row()->tanggal_kirim_hardcopy,
                "nama_pengirim_hardcopy"       => $get_ajuan_by_signature->row()->nama_pengirim_hardcopy,
                "email_pengirim_hardcopy"       => $get_ajuan_by_signature->row()->email_pengirim_hardcopy,
                "ajuan_by"       => $this->model_management_claim->get_user($get_ajuan_by_signature->row()->created_by)->row()->username,
                "tahun_folder"       => $get_ajuan_by_signature->row()->tahun_folder
            ];
        }

        return $data;
    }

    private function view($data, $flag_accordion, $view)
    {
        if ($flag_accordion == true) {
            $data = [
                "navbar"        => $this->navbar($data),
                "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
                "css"           => $this->load->view('management_claim/css', $data),
                "accordion"     => $this->load->view('management_claim/accordion', $data),
                "view"          => $this->load->view('management_claim/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        }else{
            $data = [
                "navbar"        => $this->navbar($data),
                "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
                "css"           => $this->load->view('management_claim/css', $data),
                "view"          => $this->load->view('management_claim/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        }
        return $data;       
    }

    public function verifikasi_principal_save()
    {
        /* inisial variabel */
        $status_internal    = $this->input->post('status_internal');
        $keterangan         = $this->input->post('keterangan');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');
        $id_log             = $this->input->post('id_log');
        $created_at         = $this->created_at;
        $created_by         = $this->created_by;
        $signature          = 'ver-claim-'.rand().md5($this->created_at.rand());
        /* end inisial variabel */

        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) 
        {
            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) 
            {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
                die;
            }else
            {
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
            }
        }else
        {
            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
        }

        $registrasi_program = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$registrasi_program->num_rows > 0) 
        {
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');            
        }else
        {
            $id_program = $registrasi_program->row()->id;
            $kategori   = $registrasi_program->row()->kategori;
            // pembuat program
            $pembuat_program = $registrasi_program->row()->updated_by;
            $supp = $registrasi_program->row()->supp;
            $segment = $registrasi_program->row()->segment;
        }

        $ajuan_claim = $this->model_management_claim->get_ajuan_claim_by_id_program_and_signature($id_program, $signature_ajuan);

        if (!$ajuan_claim->num_rows > 0) {
            $this->session->set_flashdata("pesan", "Proses anda gagal. Data not found");
            redirect('management_claim/ajuan_claim/');    
        }else{
            $id_ajuan = $ajuan_claim->row()->id;
            $ajuan_by = $ajuan_claim->row()->created_by;
            $site_code = $ajuan_claim->row()->site_code;
        }
        
        // inisialisasi upload
        $init_upload = $this->attachment_config($kategori);        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else
        {
            $filename = '';
        };

        // inisialiasi default status_approval
        $status_approval = 0; // status approval 0

        // inisialisasi pic head
        // cari dulu level_on_duty di tabel log_aktivitas_claim
        $get_log = $this->model_management_claim->get_log_aktivitas_by_id($id_log);
        if (!$get_log->num_rows > 0) {
            $this->session->set_flashdata("pesan", "Proses anda gagal. Data not found");
            redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
        }else{
            $level_on_duty = $get_log->row()->level_on_duty;
            // echo "level_on_duty : ".$level_on_duty;
        }

        // die;

        $get_region = $this->model_management_claim->get_master_region_by_site_code_supp_segment($site_code, $supp, $segment);
        if (!$get_region->num_rows > 0) {
            $this->session->set_flashdata("pesan", "Proses anda gagal. Data not found");
            redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
        }else{
            $pic_principal_1 = $get_region->row()->pic_principal_1;
            $pic_principal_2 = $get_region->row()->pic_principal_2;
            // echo "pic_principal_1 : ".$pic_principal_1;
            // echo "pic_principal_2 : ".$pic_principal_2;
        }

        // die;

        // list status = 3,4,23
        if ($status_internal == 4) // status_internal 4 = APPROVE PRINCIPAL (BACK TO DP)
        { 
            // kalau level_on_duty = 1, maka jadikan berdasarkan master region yang nama kolomnya pic_principal_2 sebagai userid_head
            if ($level_on_duty == 1) // level on duty 1 = PRINCIPAL
            {
                $userid_head = $pic_principal_2;
                $level_on_duty = 2;
            }elseif($level_on_duty == 2) // di level on duty 2, maka yg terbaru akan di langsungkan ke mpm
            {
                $userid_head = $ajuan_by; // sebelumnya jika principal di level on duty 2, maka userid_head = dikembalikan ke dp
                // $userid_head = $ajuan_by;
                // $userid_head = $pembuat_program; // sekarang di langsungkan ke mpm
                // $status_internal = 9; // status internal menjadi pending admin mpm
                $status_internal = 1; // status internal menjadi pending dp
                $level_on_duty = 0;
                $status_approval = 1;
            }else{
                $this->session->set_flashdata("pesan", "Proses anda gagal. Data not found");
                redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
            }

            $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
            $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
            $status = 2; // status tetap on process

            // die;
            // // cek struktur approval
            // $get_master_struktural = $this->model_management_claim->get_mapping_stuktural_by_userid($this->created_by);
            // if ($get_master_struktural -> num_rows > 0) // kalau atasan ditemukan
            // {                
            //     $userid_head = $get_master_struktural->row()->userid_head;
            //     $userid_head_name = $get_master_struktural->row()->userid_head_name;
            //     $userid_head_email = $get_master_struktural->row()->userid_head_email;
            //     $status = 2; // status tetap on process

            // }else // kalau atasan not found
            // {
            //     $userid_head = $ajuan_by; // akan di alihkan ke pembuat ajuan
            //     $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
            //     $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
            //     $status = 1; // status menjadi pending dp
            //     $status_internal = 1; // status internal 1 adalah pending dp
            //     $status_approval = 1; // status approval 1 = pending
            // }            

        }elseif ($status_internal == 23) // status_internal 23 = REJECT PRINCIPAL (REVISION)
        { 

            // kalau level_on_duty = 1, maka jadikan berdasarkan master region yang nama kolomnya pic_principal_2 sebagai userid_head
            if ($level_on_duty == 1) // level on duty 1 = PRINCIPAL
            {
                $userid_head = $ajuan_by;
                $status = 1; // kembali ke pending dp
                $level_on_duty = 0;
            }elseif($level_on_duty == 2)
            {
                $userid_head = $pic_principal_1;
                $status = 2; // tetap on progress
                $level_on_duty = 1;
            }else{
                $this->session->set_flashdata("pesan", "Proses anda gagal. Data not found");
                redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
            }

            $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
            $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
            

            // $get_master_struktural = $this->model_management_claim->get_mapping_stuktural_by_userid_head($this->created_by);
            // if ($get_master_struktural -> num_rows > 0){ // kalau bawahan ditemukan{
            //     $userid_head = $get_master_struktural->row()->userid;
            //     $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
            //     $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
            //     $status = 2; // status tetap on process
            // }else{ // kalau bawahan not found
            //     $userid_head = $ajuan_by; // akan di alihkan ke pembuat ajuan
            //     $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
            //     $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
            //     $status = 1; // status menjadi Pending DP
            // }


            // echo "id_log : ".$id_log;

            // echo "aaA";
            // die;



        }elseif ($status_internal == 3) // status_internal 3 = REJECT PRINCIPAL (CLOSED)
        { 
            $this->session->set_flashdata("pesan", "Status verifikasi anda gagal. Silahkan pilih status sesuai rule yang benar");
            redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
            die;

            // $status = 16; // status 16 = REJECT
            // // update ajuan_claim
            // $update_ajuan = [
            //     "pic_userid"        => $userid_head,
            //     "status"            => $status,
            //     "status_internal"   => $status_internal,
            //     "updated_at"        => $this->created_at,
            //     "updated_by"        => $this->created_by
            // ];
            // $proses_update_ajuan = $this->model_management_claim->update_ajuan_claim($update_ajuan, $id_ajuan);
            // $this->session->set_flashdata("pesan_success", "verifikasi anda berhasil");
            // redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
            // die;

        }else{ // jika reject
            $this->session->set_flashdata("pesan", "Status verifikasi anda gagal. Silahkan pilih status sesuai rule yang benar");
            redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
            die;
        }

        // echo "id_log : ".$id_log."<br>";
        // echo "id_program : ".$id_program."<br>";
        // echo "id_ajuan : ".$id_ajuan."<br>";
        // echo "status : ".$status."<br>";
        // echo "status_internal : ".$status_internal."<br>";
        // echo "keterangan : ".$keterangan."<br>";

        // die;

        $time_response = ($this->model_management_claim->get_status_internal($status_internal) ? $this->model_management_claim->get_status_internal($status_internal)->row()->time_response : 0);
        $duedate_response = date('Y-m-d', strtotime('+'.$time_response.' days', strtotime($this->created_at)));
            
            // $data_log = [
            //     "id_registrasi" => $id_program,
            //     "id_ajuan"      => $proses,
            //     "status"        => $status,
            //     "status_internal"   => $status_internal,
            //     "function"      => "form_dp_save",
            //     "file"          => $filename_excel,
            //     "file_zip"      => $filename_zip,
            //     "signature"     => 'log-'.rand().md5($this->created_at.rand()),
            //     "created_at"    => $this->created_at,
            //     "created_by"    => $this->created_by,
            //     "updated_at"    => $this->created_at,
            //     "updated_by"    => $this->created_by,
            //     "on_duty_finish"=> 0,
            //     "pic_on_duty"   => $pic_userid,
            //     "level_on_duty" => 1,                
            //     'time_response' => $time_response,
            //     'duedate_response' => $duedate_response
            // ];

 
        // insert log
        $data_log = [
            "ref_log"       => $id_log,
            "id_registrasi" => $id_program,
            "id_ajuan"      => $id_ajuan,
            "status"        => $status,
            "status_internal"   => $status_internal,
            "keterangan"    => $keterangan,
            "function"      => "verifikasi_principal_save",
            "file"          => $filename,
            "signature"     => 'log-'.rand().md5($this->created_at.rand()),
            "created_at"    => $this->created_at,
            "created_by"    => $this->created_by,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->created_by,
            "on_duty_finish"=> 0,
            "pic_on_duty"   => $userid_head,
            "level_on_duty" => $level_on_duty,
            "time_response" => $time_response,
            "duedate_response" => $duedate_response
        ];
        $insert_log = $this->model_management_claim->insert_log_claim($data_log);

        // update ajuan_claim
        $update_ajuan = [
            "id_log"            => $insert_log,
            "status_approval"   => $status_approval,
            "pic_userid"        => $userid_head,
            "status"            => $status,
            "status_internal"   => $status_internal,
            "updated_at"        => $this->created_at,
            "updated_by"        => $this->created_by
        ];
        $proses_update_ajuan = $this->model_management_claim->update_ajuan_claim($update_ajuan, $id_ajuan);

        $update_log = [
            "on_duty_finish"    => 1,
            "updated_by"        => $this->created_by,
            "updated_at"        => $this->created_at
        ];
        $this->model_management_claim->update_log_claim($update_log, $id_log);

        $params_signature = $signature_ajuan ? $signature_ajuan : $signature;
        redirect('management_claim/email/'.$signature_program.'/'.$params_signature);

        die;

        $this->session->set_flashdata("pesan_success", "verifikasi anda berhasil");
        redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
        die;
    }

    public function form_dp($signature_program, $signature_ajuan = '')
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $id_program = $get_registrasi_program['id_program'];
        $nama_status_validasi = $get_registrasi_program['nama_status_validasi'];
        $id_kategori = $get_registrasi_program['id_kategori'];
        $kategori = $get_registrasi_program['kategori'];
        $duedate = $get_registrasi_program['duedate'];

        $get_site_code = $this->model_management_claim->get_sitecode($this->session->userdata('id'));
        if ($get_site_code->num_rows() > 0) {
            $site_code = $get_site_code->row()->site_code;
        }else{
            $site_code = "";
        }

        // echo "kategori : ".$kategori;
        if ($kategori == 'Loyalty') { // untuk kategori loyalty.. cek tabel kepesertaan
            $get_loyalty_peserta_by_id_program = $this->model_management_claim->get_loyalty_peserta_by_id_program_where_skp_lengkap($id_program, $site_code);

            if (!$get_loyalty_peserta_by_id_program->num_rows() > 0) 
            {
                $id_loyalty_peserta = null;

            }else{
                $id_loyalty_peserta = $get_loyalty_peserta_by_id_program->row()->id;
            }
        }else{
            $id_loyalty_peserta = null;
        }

        // echo "id_loyalty_peserta : ".$id_loyalty_peserta;

        if ($nama_status_validasi == "TRUE" && $id_kategori == 2) {
            $url = "management_claim/import_bonus_barang";
        }elseif ($nama_status_validasi == "TRUE" && ($id_kategori == 3 || $id_kategori == 4 || $id_kategori == 5)) {
            $url = "management_claim/import_diskon";
        }else{
            $url = "management_claim/form_dp_save";
        }

        if ($signature_ajuan) 
        {
            $get_ajuan = $this->get_ajuan_by_signature($signature_ajuan);

            $created_at     = $get_ajuan['created_at'];
            $nama_pengirim  = $get_ajuan['nama_pengirim'];
            $email_pengirim = $get_ajuan['email_pengirim'];
            $ajuan_excel    = $get_ajuan['ajuan_excel'];
            $ajuan_zip      = $get_ajuan['ajuan_zip'];
            $status         = $get_ajuan['status'];
            // $nama_status    = $this->model_management_claim->get_status($status)->row()->nama_status;
            $nama_status    = ($this->model_management_claim->get_status($status) ? $this->model_management_claim->get_status($status)->row()->nama_status : '');
            $id_ajuan       = $get_ajuan['id_ajuan'];
            $nomor_ajuan    = $get_ajuan['nomor_ajuan'];
            $tanggal_claim  = $get_ajuan['tanggal_claim'];                
            $site_code      = $get_ajuan['site_code'];
            $branch_name    = $this->model_management_claim->get_site($site_code)->row()->branch_name;
            $nama_comp      = $this->model_management_claim->get_site($site_code)->row()->nama_comp;
            $id_log         = $get_ajuan['id_log'];
            $status_internal = $get_ajuan['status_internal'];
            $nama_status_internal = $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status;
            $status_keikutsertaan = $get_ajuan['status_keikutsertaan'];
            $tahun_folder_ajuan = $get_ajuan['tahun_folder'];     

            $get_pic = $this->model_management_claim->get_log_aktivitas_by_onduty($id_ajuan);
            if ($get_pic->num_rows > 0) {
                $pic_on_duty = $get_pic->row()->username;
                $email_on_duty = $get_pic->row()->email;
            }else{
                $pic_on_duty = "";
                $email_on_duty = "";
            }

            // status authorized
            if ($pic_on_duty == $this->session->userdata('username')) {
                $status_authorized = true;
            }else{
                if (!$pic_on_duty) { // dianggap masih baru atau belum pernah claim
                    $status_authorized = true;
                }else{
                    $status_authorized = false;
                }
            }
        }else{
            // kalau uri signature_ajuan kosong, maka cek secara database
            $is_exist = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->created_by);
            if (!$is_exist->num_rows() > 0) 
            {
                $id_ajuan = -1;
                $id_log = "";
                $nama_status = "";
                $nama_status_internal = "";
                $nomor_ajuan = "";
                $branch_name = "";
                $nama_comp = "";
                $site_code = "";
                $nama_pengirim = "";
                $email_pengirim = "";
                $tanggal_claim = "";
                $created_at = "";
                $ajuan_excel = "";
                $ajuan_zip = "";
                $pic_on_duty = "";
                $email_on_duty = "";
                $status_authorized = true;
                $status_keikutsertaan = NULL;
                $status_internal = "";
                $tahun_folder_ajuan = "";
            } else{

                $status_authorized = true;
                $status = $is_exist->row()->status;
                $nama_status = $this->model_management_claim->get_status($status)->row()->nama_status;
                $status_internal = $is_exist->row()->status_internal;
                $nama_status_internal = $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status;
                $nomor_ajuan = $is_exist->row()->nomor_ajuan;
                $site_code = $is_exist->row()->site_code;
                $branch_name = "";
                $nama_comp = "";
                $nama_pengirim = $is_exist->row()->nama_pengirim;
                $email_pengirim = $is_exist->row()->email_pengirim;
                $tanggal_claim = $is_exist->row()->tanggal_claim;
                $created_at = $is_exist->row()->created_at;
                $ajuan_excel = $is_exist->row()->ajuan_excel;
                $ajuan_zip = $is_exist->row()->ajuan_zip;
                $status_keikutsertaan = $is_exist->row()->status_keikutsertaan;
                $tahun_folder_ajuan = $is_exist->row()->tahun_folder;

                $id_ajuan = $is_exist->row()->id;
                // mencari pic on duty terupdate yaitu yang on_duty_finish = 0
                $get_pic = $this->model_management_claim->get_log_aktivitas_by_onduty($id_ajuan);
                if ($get_pic->num_rows > 0) {
                    $pic_on_duty = $get_pic->row()->username;
                    $email_on_duty = $get_pic->row()->email;
                }else{
                    $pic_on_duty = "";
                    $email_on_duty = "";
                }

                // status authorized
                if ($pic_on_duty == $this->session->userdata('username')) {
                    $status_authorized = true;
                }else{
                    $status_authorized = false;
                }

                $id_log = $is_exist->row()->id_log;

                $signature_ajuan = $is_exist->row()->signature;
            }
        }

        $get_site_code = $this->model_management_claim->get_sitecode($this->session->userdata('id'));
        if ($get_site_code->num_rows() > 0) {
            $site_code = $get_site_code->row()->site_code;
        }else{
            $site_code = "";
        }

        $today_params = date('Y-m-d', strtotime($this->created_at)); // bisa juga waktu sekarang now()
        $selisih = strtotime($duedate) - strtotime($today_params);        

        $data = [
            'title'                     => 'management claim | Form DP',
            // 'url'                       => 'management_claim/form_dp_save',
            'url'                       => $url,
            'signature_program'         => $signature_program,            
            'signature_ajuan'           => $signature_ajuan,   
            'site_code'                 => $site_code,
            'kategori'                  => $get_registrasi_program['kategori'],      
            'namasupp'                  => $get_registrasi_program['namasupp'],      
            'from'                      => $get_registrasi_program['from'],      
            'to'                        => $get_registrasi_program['to'],      
            'nama_program'              => $get_registrasi_program['nama_program'],      
            'nomor_surat'               => $get_registrasi_program['nomor_surat'],      
            'duedate'                   => $get_registrasi_program['duedate'],      
            'upload_pdf'                => $get_registrasi_program['upload_pdf'],      
            'username'                  => $get_registrasi_program['username'],    
            'signature_program'         => $signature_program,      
            'signature_ajuan'           => $signature_ajuan,      
            'params_status_validasi'    => $get_registrasi_program['params_status_validasi'],
            'params_folder'             => $get_registrasi_program['params_folder'],
            'segment'                   => $get_registrasi_program['segment'],
            'nama_status_validasi'      => $get_registrasi_program['nama_status_validasi'],
            'keterangan'                => $get_registrasi_program['keterangan'],
            'nama_template'             => $get_registrasi_program['nama_template'],
            'filename'                  => $get_registrasi_program['filename'],
            'pic'                       => $get_registrasi_program['pic'],
            'nama_status'               => $nama_status,
            'nama_status_internal'      => $nama_status_internal,
            'status_internal'           => $status_internal,
            'nomor_ajuan'               => $nomor_ajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'nama_pengirim'             => $nama_pengirim,
            'email_pengirim'            => $email_pengirim,
            'tanggal_claim'             => $tanggal_claim,
            'created_at'                => $created_at,
            'ajuan_excel'               => $ajuan_excel,
            'ajuan_zip'                 => $ajuan_zip,
            'pic_on_duty'               => $pic_on_duty,
            'email_on_duty'             => $email_on_duty,
            'status_authorized'         => $status_authorized,
            'id_kategori'               => $get_registrasi_program['id_kategori'],
            'id_log'                    => $id_log,
            'get_log'                   => $this->model_management_claim->get_log_aktivitas_by_id_ajuan($id_ajuan, $this->created_by),            
            'get_status_internal'       => $this->model_management_claim->get_status_internal('',''),    
            'selisih'                   => $selisih,
            'status_keikutsertaan'      => $status_keikutsertaan,
            'id_loyalty_peserta'        => $id_loyalty_peserta,
            'tahun_folder'              => $get_registrasi_program['tahun_folder'],
            'tahun_folder_ajuan'        => $tahun_folder_ajuan,
        ];

        // $this->view($data, true, "form_dp");
        $this->render_multiple(
            array(
                'management_claim/accordion',
                'management_claim/form_dp'
            ),
            $data
        );
    }

  public function form_dp_save()
  {
      /* inisial variabel */
      $signature_program  = $this->input->post('signature_program');
      $signature_ajuan    = $this->input->post('signature_ajuan');
      $id_log             = $this->input->post('id_log');
      $nama               = $this->input->post('nama');
      $email              = $this->input->post('email');
      $site_code          = $this->input->post('site_code');
      $first_pic          = $this->input->post('first_pic');
      $status_validasi    = $this->input->post('status_validasi');
      $created_at         = $this->created_at;
      $created_by         = $this->created_by;
      $signature          = 'ajuan-claim-'.rand().md5($this->created_at.rand());
      /* end inisial variabel */

      // cek traffic
      $traffic = $this->model_management_claim->get_traffic_import();
      if ($traffic->num_rows() > 0) 
      {
          $status_traffic = $traffic->row()->status_import;
          if ($status_traffic == 1) 
          {
              $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
              redirect('management_claim/form_dp/'.$signature_program);
              die;
          }else
          {
              $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
          }
      }else
      {
          $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
      }

      $registrasi_program = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
      if (!$registrasi_program->num_rows > 0) 
      {
          $this->session->set_flashdata("pesan", "data not found");
          redirect('management_claim/ajuan_claim/');            
      }else
      {
          $id_program = $registrasi_program->row()->id;
          $kategori   = $registrasi_program->row()->kategori;
          $supp   = $registrasi_program->row()->supp;
          $segment   = $registrasi_program->row()->segment;
          // pembuat program
          $pembuat_program = $registrasi_program->row()->updated_by;
          $nama_status_validasi = $registrasi_program->row()->nama_status_validasi;
      }

      $status_approval = 0;
      if ($signature_ajuan) {
          $ajuan_claim = $this->model_management_claim->get_ajuan_claim_by_id_program_and_signature($id_program, $signature_ajuan);

          if (!$ajuan_claim->num_rows > 0) {
              $this->session->set_flashdata("pesan", "Proses anda gagal. Data not found");
              redirect('management_claim/ajuan_claim/');    
          }else{
              $id_ajuan = $ajuan_claim->row()->id;
              $ajuan_by = $ajuan_claim->row()->created_by;
              $nomor_ajuan = $ajuan_claim->row()->nomor_ajuan;
              $status_approval = $ajuan_claim->row()->status_approval;
          }
      }else{
          // kalau uri signature_ajuan kosong, maka cek secara database
          $is_exist = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->created_by);
          if (!$is_exist->num_rows() > 0) 
          {
              $id_ajuan = "";
              $ajuan_by = "";
              $id_log = "";
              $nama_status = "";
              $nama_status_internal = "";
              $nomor_ajuan = "";
              $branch_name = "";
              $nama_comp = "";
              $nama_pengirim = "";
              $email_pengirim = "";
              $tanggal_claim = "";
              $created_at = "";
              $ajuan_excel = "";
              $ajuan_zip = "";
              $pic_on_duty = "";
              $email_on_duty = "";
              $status_authorized = true;    
          }else{

              $id_ajuan = $is_exist->row()->id;
              $id_log = $is_exist->row()->id_log;
              $nomor_ajuan = $is_exist->row()->nomor_ajuan;
              $status_approval = $is_exist->row()->status_approval;
          }
      }
      
      // inisialisasi upload
      $init_upload = $this->attachment_config($kategori);        
      if ($this->upload->do_upload('ajuan_excel')) 
      {
          $upload_data = $this->upload->data();
          $filename_excel = $upload_data['file_name'];
      }else
      {
          var_dump($this->upload->display_errors());
          die;
      };

      if ($this->upload->do_upload('ajuan_zip')) 
      {
          $upload_data = $this->upload->data();
          $filename_zip = $upload_data['file_name'];
      }else
      {
          var_dump($this->upload->display_errors());
          die;
      };

      // echo "first_pic : ".$first_pic;
      // die;

      if ($first_pic == 'PRINCIPAL') {
        $status = 2; //2 = on progress
        $status_internal = 2; //2 = pending principal 
        $level_on_duty = 1;

        $get_pic = $this->model_management_claim->get_master_region_by_site_code_supp_segment($site_code, $supp, $segment);
        if (!$get_pic->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses anda gagal dikarenakan DP anda belum mempunyai PIC Principal terkait. Silahkan capture pesan ini dan infokan ke PIC MPM untuk ditindaklanjuti");
            redirect('management_claim/form_dp/'.$signature_program.'/'.$signature_ajuan);
            die;
        }else{
            $pic_userid = $get_pic->row()->pic_principal_1;
        }
      }elseif($first_pic == 'MPM'){
          $status = 2; //2 = on progress
          $status_internal = 9; //9 = pending admin mpm
          $pic_userid = $pembuat_program;
          $level_on_duty = 0;
      }else{
          $status = 2; //2 = on progress
          $status_internal = 9; //9 = pending admin mpm
          $pic_userid = $pembuat_program;
          $level_on_duty = 0;
      }

      // kalau status approval == 1, maka pic_userid = pembuat_program
      if ($status_approval == 1) {
          $pic_userid = $pembuat_program;
          $level_on_duty = 0;
          $status_internal = 9; // 9 = pending admin mpm
      }

      $is_exist = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->created_by);

      // var_dump($is_exist);die;

      if (!$is_exist->num_rows() > 0){
          $nomor_ajuan = $this->model_management_claim->generate($site_code, $this->created_at);
          // echo "nomor_ajuan : ".$nomor_ajuan;
          // die;            

          $data = [
              "nomor_ajuan"   => $nomor_ajuan,
              "nama_pengirim" => $nama,
              "email_pengirim"=> $email,
              "site_code"     => $site_code,
              "id_program"    => $id_program,
              "ajuan_excel"   => $filename_excel,
              "ajuan_zip"     => $filename_zip,
              'status'        => $status,
              'status_internal'=> $status_internal,
              'tanggal_claim' => $this->created_at,
              'pic_userid'    => $pic_userid,
              'created_at'    => $this->created_at,
              'created_by'    => $this->created_by,
              'updated_at'    => $this->created_at,
              'updated_by'    => $this->created_by,
              'signature'     => $signature,
              'status_keikutsertaan' => 1,
          ];

          $proses = $this->model_management_claim->insert_ajuan_claim($data);
      }else {
          $nomor_ajuan = $is_exist->row()->nomor_ajuan;
          if (!$nomor_ajuan) {
              $nomor_ajuan = $this->model_management_claim->generate($site_code, $this->created_at);
          }

          $data = [
              "nomor_ajuan"   => $nomor_ajuan,
              "nama_pengirim" => $nama,
              "email_pengirim"=> $email,
              "ajuan_excel"   => $filename_excel,
              "ajuan_zip"     => $filename_zip,
              'status'        => $status,
              'status_internal'=> $status_internal,
              'pic_userid'    => $pic_userid,
              'updated_at'    => $this->created_at,
              'updated_by'    => $this->created_by,
              'status_keikutsertaan' => 1
          ];
          $proses = $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);

      }

      if ($proses) {

          $time_response = ($this->model_management_claim->get_status_internal($status_internal) ? $this->model_management_claim->get_status_internal($status_internal)->row()->time_response : 0);
          $duedate_response = date('Y-m-d', strtotime('+'.$time_response.' days', strtotime($this->created_at)));

          // echo "time_response : ".$time_response."<br>";
          // die;

          $data_log = [
              "id_registrasi" => $id_program,
              "id_ajuan"      => $proses,
              "status"        => $status,
              "status_internal"   => $status_internal,
              "function"      => "form_dp_save",
              "file"          => $filename_excel,
              "file_zip"      => $filename_zip,
              "signature"     => 'log-'.rand().md5($this->created_at.rand()),
              "created_at"    => $this->created_at,
              "created_by"    => $this->created_by,
              "updated_at"    => $this->created_at,
              "updated_by"    => $this->created_by,
              "on_duty_finish"=> 0,
              "pic_on_duty"   => $pic_userid,
              "level_on_duty" => 1,                
              'time_response' => $time_response,
              'duedate_response' => $duedate_response
          ];
          $insert_log = $this->model_management_claim->insert_log_claim($data_log);
          if ($insert_log) 
          {
              // update ajuan_claim set id_log
              $update_ajuan = [
                  "id_log"    => $insert_log,
              ];
              $update = $this->model_management_claim->update_ajuan_claim($update_ajuan, $proses);

              $update_log = [
                  "on_duty_finish"    => 1,
                  "updated_by"        => $this->created_by,
                  "updated_at"        => $this->created_at
              ];
              $this->model_management_claim->update_log_claim($update_log, $id_log);

              $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);

              $params_signature = $signature_ajuan ? $signature_ajuan : $signature;
              redirect('management_claim/email/'.$signature_program.'/'.$params_signature);

          }else{
              $log_error = [
                  "id_registrasi" => $id_program,
                  "id_ajuan"      => $proses,
                  "created_at"    => $this->created_at,
                  "created_by"    => $this->created_by,
              ];
              $insert_log_error = $this->model_management_claim->insert_log_error($log_error);
              $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
              $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");
              redirect('management_claim/form_dp/'.$signature_program);
              die;
          }
      }else{
          $this->session->set_flashdata("pesan", "Proses anda gagal. Silahkan cek kembali data anda");
          redirect('management_claim/form_dp/'.$signature_program);
          die;
      }
  }

    public function email($signature_program, $signature_ajuan)
    {
        // echo "email";
        // die;
        $this->load->model('model_relokasi');
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $nomor_surat = $get_registrasi_program['nomor_surat'];
        $kategori = $get_registrasi_program['kategori'];
        $nama_program = $get_registrasi_program['nama_program'];
        $updated_by = $get_registrasi_program['updated_by'];
        $email_program = $this->model_management_claim->get_user($updated_by)->row()->email;
        $namasupp = $get_registrasi_program['namasupp'];

        $get_ajuan = $this->get_ajuan_by_signature($signature_ajuan);
        $id_ajuan = $get_ajuan['id_ajuan'];
        $nomor_ajuan = $get_ajuan['nomor_ajuan'];
        $branch_name = $get_ajuan['branch_name'];
        $nama_comp = $get_ajuan['nama_comp'];
        $site_code = $get_ajuan['site_code'];
        $email_pengirim = $get_ajuan['email_pengirim'];
        $pic_userid = $get_ajuan['pic_userid'];
        $log_keterangan = $get_ajuan['log_keterangan'];
        $nomor_hardcopy = $get_ajuan['nomor_hardcopy'];
        $nama_pengirim_hardcopy = $get_ajuan['nama_pengirim_hardcopy'];
        $tanggal_kirim_hardcopy = $get_ajuan['tanggal_kirim_hardcopy'];
        $email_pengirim_hardcopy = $get_ajuan['email_pengirim_hardcopy'];
        $email_on_duty = $this->model_management_claim->get_user($pic_userid)->row()->email;
        $username_on_duty = $this->model_management_claim->get_user($pic_userid)->row()->username;
        

        $status = $get_ajuan['status'];
        $nama_status = ($this->model_management_claim->get_status($status)->row()->nama_status) ? $this->model_management_claim->get_status($status)->row()->nama_status : '';

        $status_internal = $get_ajuan['status_internal'];
        $nama_status_internal = ($this->model_management_claim->get_status_internal($status_internal)->row()->nama_status) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '';

        $this->model_relokasi->email();
        $from   = "suffy@muliaputramandiri.net";
        $to     = $email_pengirim.",".$email_on_duty;
        $cc     = $email_program;

        $data = [
            "nomor_surat"   => $nomor_surat,
            "namasupp"   => $namasupp,
            "nomor_ajuan"   => $nomor_ajuan,
            "status"        => $nama_status,
            "status_internal"        => $nama_status_internal,
            "kategori"      => $kategori,
            "nama_program"      => $nama_program,
            "branch_name"   => $branch_name,
            "nama_comp"     => $nama_comp,
            "signature_program" => $signature_program,
            "signature_ajuan" => $signature_ajuan,
            "username_on_duty" => $username_on_duty,
            "log_keterangan" => $log_keterangan,
            "nomor_hardcopy" => $nomor_hardcopy,
            "nama_pengirim_hardcopy" => $nama_pengirim_hardcopy,
            "tanggal_kirim_hardcopy" => $tanggal_kirim_hardcopy,
            "email_pengirim_hardcopy" => $email_pengirim_hardcopy,
            "log" => $this->model_management_claim->get_log_aktivitas_by_id_ajuan($id_ajuan),
        ];

        $message = $this->load->view("management_claim/email",$data,TRUE);
        $subject = "MPM SITE | Monitoring Claim : $nomor_ajuan | ".$branch_name;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();    
        if ($send) 
        {
            $this->session->set_flashdata("pesan_success", "Send Email Berhasil");
            redirect('management_claim/routing/'.$signature_program.'/'.$signature_ajuan);            
        }else{
            $this->session->set_flashdata("pesan", "Send Email Gagal");
            redirect('management_claim/routing/'.$signature_program.'/'.$signature_ajuan);               
        }
        die;
    }

    public function verifikasi_principal_save_old()
    {
        $status_internal    = $this->input->post('status_internal');
        $keterangan         = $this->input->post('keterangan');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');
        $created_at         = $this->model_outlet_transaksi->timezone();
        $signature          = md5($this->model_outlet_transaksi->timezone());
        $userid             = $this->session->userdata('id');

        // cek signature program
        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if(!$get_registrasi_program_by_signature->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. Data not found !! ");
            redirect('management_claim/ajuan_claim/');
            die;
        }else
        {
            $id_program         = $get_registrasi_program_by_signature->row()->id;
            $pic_program        = $get_registrasi_program_by_signature->row()->created_by;
            $program_created_by = $get_registrasi_program_by_signature->row()->created_by; 
        }

        // echo "pic_program : ".$pic_program;
        // die;

        // cek ajuan
        $get_ajuan_claim_by_id_program = $this->model_management_claim->get_ajuan_claim_by_id_program_and_signature($id_program, $signature_ajuan);
        if(!$get_ajuan_claim_by_id_program->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. Data not found !! ");
            redirect('management_claim/ajuan_claim/');
            die;
        }else
        {
            $id_ajuan           = $get_ajuan_claim_by_id_program->row()->id;
            $nomor_ajuan        = $get_ajuan_claim_by_id_program->row()->nomor_ajuan;
            $status_current     = $get_ajuan_claim_by_id_program->row()->status;
            $nama_status_current = $get_ajuan_claim_by_id_program->row()->nama_status;
            $status_internal_current = $get_ajuan_claim_by_id_program->row()->status_internal;
            $nama_status_internal_current = $get_ajuan_claim_by_id_program->row()->nama_status_internal;
            $created_by         = $get_ajuan_claim_by_id_program->row()->created_by;
            $site_code          = $get_ajuan_claim_by_id_program->row()->site_code;
        }        

        // echo "status_internal : ".$status_internal;
        // die;

        if ($status_internal == "4") // jika status approve, maka cek mapping_struktur_approval
        { 
            $get_mapping_stuktural_by_userid = $this->model_management_claim->get_mapping_stuktural_by_userid($userid);
            if ($get_mapping_stuktural_by_userid->num_rows() > 0) 
            {
                // echo "a";
                // die;
                $pic_userid = $get_mapping_stuktural_by_userid->row()->userid_head;
                $status = 2; // status tetap on process
                $status_internal = 2; // status internal tetap 2 karena masih di principal
                // echo "pic_userid : ".$pic_userid;
                // die;
            }else
            {
                // echo "atasannya sudah habis dan dikembalikan ke pic program";
                // die;
                $status_internal = 4; // status menjadi approve principal area, tahap ini mungkin saja admin mpm akan me-rekap seluruh data untuk di berikan ke finance
                $status = 2; // status tetap on process
                $pic_userid = $pic_program;      
                
                // echo "pic_userid : ".$pic_userid;
                // die;
                
            }
        }elseif ($status_internal == '3'){ // jika status reject
            $status = 1;
            $pic_userid = $created_by;
        }else
        {
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. Silahkan ulangi kembali !! ");
            redirect('management_claim/ajuan_claim/');
            die;
        }

        // echo "status_internal : ".$status_internal;
        // echo "pic_userid : ".$pic_userid;
        // die;

        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            $filename = '';
        };

        $data = [
            'id_program'         => $id_program,
            'id_ajuan'           => $id_ajuan,
            'nomor_ajuan'        => $nomor_ajuan,
            'keterangan'         => $keterangan,
            'status_internal'    => $status_internal,
            'nama_status_internal'=> ($this->model_management_claim->get_status_internal($status)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status)->row()->nama_status : '',
            'status'             => $status,
            'nama_status'       => ($this->model_management_claim->get_status($status)->num_rows() > 0) ? $this->model_management_claim->get_status($status)->row()->nama_status : '',
            'file'               => $filename,
            'signature'          => $signature,
            'signature_program'  => $signature_program,
            'signature_ajuan'    => $signature_ajuan,
            'created_at'         => $created_at,
            'created_by'         => $this->session->userdata('id'),
        ];

        $this->db->insert('management_claim.verifikasi_ajuan', $data);
        $id_verifikasi = $this->db->insert_id();

        if ($status == '1') {
            $params_created_at = NULL;
        }else{
            $params_created_at = $created_at;
        }

        $update = [
            'status'           => $status,
            'nama_status'       => ($this->model_management_claim->get_status($status)->num_rows() > 0) ? $this->model_management_claim->get_status($status)->row()->nama_status : '',
            'status_internal'  => $status_internal,
            'nama_status_internal'=> ($this->model_management_claim->get_status_internal($status_internal)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '',
            'pic_userid'       => $pic_userid,
            'keterangan_mpm'   => $keterangan,
            'file_mpm'         => $filename,
            'pic_mpm'          => $this->session->userdata('id'),
            'mpm_at'           => $created_at,
            'last_updated_at'  => $created_at,
            'id_verifikasi'    => $id_verifikasi,
            'tanggal_claim'    => $params_created_at,
        ];

        $this->db->where('id', $id_ajuan);
        $this->db->update('management_claim.ajuan_claim', $update);

        $insert_log = $this->model_management_claim->insert_log($id_program, $id_ajuan, $status, $status_internal, 'verifikasi_principal_save', $keterangan, $filename);

        redirect('management_claim/email_status/'.$signature_program.'/'.$signature_ajuan);
        die;
        
    }

    public function routing($signature_program, $signature_ajuan = '')
    {
        // $get_ajuan = $this->get_ajuan_by_signature($signature_ajuan);
        // $status = $get_ajuan['status'];
        // $status_internal = $get_ajuan['status_internal'];
        // cek status
        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature_ajuan);
        if ($get_ajuan_by_signature->num_rows() > 0) {
            $status_internal = $get_ajuan_by_signature->row()->status_internal;
            $status = $get_ajuan_by_signature->row()->status;
        }else{
            $status_internal = 0;
            $status = 0;
        }        

        // echo "status_internal : ".$status_internal;
        // echo "status : ".$status;
        // echo "signature_program : ".$signature_program;
        // echo "level : ".$this->session->userdata('level');
        // die;

        if ($status_internal == 0) //belum mengajukan claim
        { 
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                // redirect('management_claim/form_ajuan_claim/'.$signature_program);
                redirect('management_claim/form_dp/'.$signature_program.'/'.$signature_ajuan);
            }else{                
                if ($signature_ajuan) {
                    redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
                }else{
                    $this->session->set_flashdata("pesan", "belum ada pengajuan dari dp");
                    redirect('management_claim/ajuan_claim/');
                }
            }
        }elseif ($status_internal == 1 || $status_internal == 10 || $status_internal == 22) // PENDING DP, 22 = reject admin mpm
        {   
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/form_dp/'.$signature_program.'/'.$signature_ajuan);
            }elseif ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/form_dp/'.$signature_program.'/'.$signature_ajuan);
            }else{                
                redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
            }
        }
        elseif ($status_internal == 2 || $status_internal == 23 || $status_internal == 4) // 2=PENDING PRINCIPAL, 3=REVISI PRINCIPAL
        { 
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                // redirect('management_claim/form_dp/'.$signature_program.'/'.$signature_ajuan);
                redirect('management_claim/form_dp/'.$signature_program.'/'.$signature_ajuan);
            }else{                
                redirect('management_claim/verifikasi_principal/'.$signature_program.'/'.$signature_ajuan);
            }
        }elseif($status_internal == 5 || $status_internal == 6 || $status_internal == 7 || $status_internal == 8 || $status_internal == 20 || $status_internal == 21){ // PENDING HARDCOPY DP, pending terima hardcopy, reject hardcopy, approve hardcopy, HARDCOPY DITERIMA
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/hardcopy/'.$signature_program.'/'.$signature_ajuan);
            }else{                
                redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
            }
        }elseif($status_internal == 9 || $status_internal == 11 || $status_internal == 12 || $status_internal == 14 || $status_internal == 15 || $status_internal == 17 || $status_internal == 26){ // PENDING admin mpm, 11 = approve admin mpm, 12 = pending principal ho, 14 = approve principal ho, 15 = pending finance mpm, 17 = approve finance mpm
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/form_dp/'.$signature_program.'/'.$signature_ajuan);
            }else{                
                redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
            }
        }elseif($status_internal == 13){ // pending finance mpm
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/form_ajuan_claim/'.$signature_program);
            }else{                
                redirect('management_claim/verifikasi_finance_mpm/'.$signature_program.'/'.$signature_ajuan);
            }
        }
    }

    public function routing_hardcopy($signature_program, $signature_ajuan = ''){
        // cek status
        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature_ajuan);
        if ($get_ajuan_by_signature->num_rows() > 0) {
            $status_hardcopy = $get_ajuan_by_signature->row()->status_hardcopy;
        }else{
            $status_hardcopy = 0;
        }        

        // echo "status_hardcopy : ".$status_hardcopy;
        // echo "signature_program : ".$signature_program;
        // die;

        if ($signature_ajuan == null) {
            $this->session->set_flashdata("pesan", "anda belum mengajukan claim di program ini");
            redirect('management_claim/ajuan_claim/');
        }

        if ($status_hardcopy == 0) { //belum mengajukan claim
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/hardcopy/'.$signature_program.'/'.$signature_ajuan);
            }else{                
                if ($signature_ajuan) {
                    redirect('management_claim/verifikasi_hardcopy/'.$signature_program.'/'.$signature_ajuan);
                }else{
                    $this->session->set_flashdata("pesan", "belum ada pengajuan dari dp");
                    redirect('management_claim/ajuan_claim/');
                }
            }
        }elseif ($status_hardcopy == 1 || $status_hardcopy == 2 || $status_hardcopy == 3 || $status_hardcopy == 4 || $status_hardcopy == 5) { //pending mpm
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/hardcopy/'.$signature_program.'/'.$signature_ajuan);
            }else{                
                redirect('management_claim/verifikasi_hardcopy/'.$signature_program.'/'.$signature_ajuan);
            }
        }else{
            // echo "a";
            // die;
        }
    }

    public function form_revisi_claim($signature_program, $signature_ajuan){

        $this->load->model('model_master_data');

        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if ($get_registrasi_program_by_signature->num_rows > 0) {
            $id_program                 = $get_registrasi_program_by_signature->row()->id;
            $kategori                   = $get_registrasi_program_by_signature->row()->kategori;
            $namasupp                   = $this->model_master_data->get_namasupp_by_supp($get_registrasi_program_by_signature->row()->supp)->row()->NAMASUPP;
            $from                       = $get_registrasi_program_by_signature->row()->from;
            $to                         = $get_registrasi_program_by_signature->row()->to;
            $nama_program               = $get_registrasi_program_by_signature->row()->nama_program;
            $nomor_surat                = $get_registrasi_program_by_signature->row()->nomor_surat;
            $syarat                     = $get_registrasi_program_by_signature->row()->syarat;
            $duedate                    = $get_registrasi_program_by_signature->row()->duedate;
            $upload_jpg                 = $get_registrasi_program_by_signature->row()->upload_jpg;
            $upload_pdf                 = $get_registrasi_program_by_signature->row()->upload_pdf;
            $upload_template_program    = $get_registrasi_program_by_signature->row()->upload_template_program;
            $username                   = $this->model_master_data->get_username_by_id($get_registrasi_program_by_signature->row()->created_by)->row()->username;
        }
        
      
        $get_ajuan_claim_by_id_program_and_user = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->session->userdata('id'));
        if ($get_ajuan_claim_by_id_program_and_user->num_rows > 0) 
        {
            $nama_comp      = $this->model_master_data->get_tabcomp_by_site_code($get_ajuan_claim_by_id_program_and_user->row()->site_code)->row()->nama_comp;
            $nama_pengirim  = $get_ajuan_claim_by_id_program_and_user->row()->nama_pengirim;
            $email_pengirim = $get_ajuan_claim_by_id_program_and_user->row()->email_pengirim;
            $ajuan_excel    = $get_ajuan_claim_by_id_program_and_user->row()->ajuan_excel;
            $ajuan_zip      = $get_ajuan_claim_by_id_program_and_user->row()->ajuan_zip;
            $created_at     = $get_ajuan_claim_by_id_program_and_user->row()->created_at;
            $nama_status    = $get_ajuan_claim_by_id_program_and_user->row()->nama_status;
            $id_ajuan       = $get_ajuan_claim_by_id_program_and_user->row()->id;
            $id_revisi      = $get_ajuan_claim_by_id_program_and_user->row()->id_revisi;
            
            $id_verifikasi      = $get_ajuan_claim_by_id_program_and_user->row()->id_verifikasi;

            if ($id_verifikasi) {
                $get_verifikasi_by_id = $this->model_management_claim->get_verifikasi_by_id($id_verifikasi);
                if ($get_verifikasi_by_id->num_rows > 0) {
                    $verifikasi_signature = $get_verifikasi_by_id->row()->signature;
                    $verifikasi_keterangan = $get_verifikasi_by_id->row()->keterangan;
                    $verifikasi_file = $get_verifikasi_by_id->row()->file;
                    $verifikasi_created_at = $get_verifikasi_by_id->row()->created_at;
                    $verifikasi_username = $get_verifikasi_by_id->row()->username;
                }
            }else{
                $verifikasi_signature   = "";
                $verifikasi_keterangan  = "";
                $verifikasi_file        = "";
                $verifikasi_created_at  = "";
                $verifikasi_username    = "";
            }

        }else{
            $nama_comp      = "";
            $nama_pengirim  = "";
            $email_pengirim = "";
            $ajuan_excel    = "";
            $ajuan_zip      = "";
            $created_at     = "";
            $nama_status    = "";
            $id_verifikasi  = "";
            
            $verifikasi_signature   = "";
            $verifikasi_keterangan  = "";
            $verifikasi_file        = "";
            $verifikasi_created_at  = "";
            $verifikasi_username    = "";
        }

        $get_revisi_ajuan_by_id_ajuan = $this->model_management_claim->get_revisi_ajuan_by_id_ajuan($id_ajuan);
        if ($get_revisi_ajuan_by_id_ajuan->num_rows > 0) {
            $revisi_nama_pengirim = $get_revisi_ajuan_by_id_ajuan->row()->nama_pengirim;
            $revisi_email_pengirim = $get_revisi_ajuan_by_id_ajuan->row()->email_pengirim;
            $revisi_excel = $get_revisi_ajuan_by_id_ajuan->row()->revisi_excel;
            $revisi_zip = $get_revisi_ajuan_by_id_ajuan->row()->revisi_zip;
            $revisi_created_at = $get_revisi_ajuan_by_id_ajuan->row()->created_at;
            $revisi_username = $get_revisi_ajuan_by_id_ajuan->row()->username;
            $revisi_signature = $get_revisi_ajuan_by_id_ajuan->row()->signature;
        }else{
            $revisi_nama_pengirim = "";
            $revisi_email_pengirim = "";
            $revisi_excel = "";
            $revisi_zip = "";
            $revisi_created_at = "";
            $revisi_username = "";
            $revisi_signature = "";
        }

        $data = [
            'title'                     => 'management claim | form revisi claim',            
            'url'                       => 'management_claim/revisi_claim_save',
            'kategori'                  => $kategori,      
            'namasupp'                  => $namasupp,      
            'from'                      => $from,      
            'to'                        => $to,      
            'nama_program'              => $nama_program,      
            'nomor_surat'               => $nomor_surat,      
            'syarat'                    => $syarat,      
            'duedate'                   => $duedate,      
            'upload_jpg'                => $upload_jpg,      
            'upload_pdf'                => $upload_pdf,      
            'username'                  => $username,      
            'nama_comp'                 => $nama_comp,      
            'nama_pengirim'             => $nama_pengirim,      
            'email_pengirim'            => $email_pengirim,      
            'ajuan_excel'               => $ajuan_excel,      
            'ajuan_zip'                 => $ajuan_zip,      
            'signature_program'         => $signature_program,      
            'created_at'                => $created_at,      
            'nama_status'               => $nama_status,        
            'verifikasi_signature'      => $verifikasi_signature,      
            'verifikasi_keterangan'     => $verifikasi_keterangan,      
            'verifikasi_file'           => $verifikasi_file,      
            'verifikasi_created_at'     => $verifikasi_created_at,      
            'verifikasi_username'       => $verifikasi_username,      
            'upload_template_program'   => $upload_template_program,   
            'revisi_nama_pengirim'      => $revisi_nama_pengirim,   
            'revisi_email_pengirim'     => $revisi_email_pengirim,   
            'revisi_excel'              => $revisi_excel,   
            'revisi_zip'                => $revisi_zip,   
            'revisi_created_at'         => $revisi_created_at,   
            'revisi_username'           => $revisi_username,   
            'revisi_signature'          => $revisi_signature,   
            'signature_ajuan'           => $signature_ajuan,   
            'site_code'                 => $this->model_management_claim->get_sitecode($this->session->userdata('id')),
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/accordion', $data);
        $this->load->view('management_claim/form_revisi_claim', $data);
        $this->load->view('kalimantan/footer');

    }

    public function revisi_claim_save(){
        // $site_code          = $this->input->post('site_code');
        // $branch_name        = $this->input->post('branch_name');
        // $nama_comp          = $this->input->post('nama_comp');
        $nama_pengirim      = $this->input->post('nama_pengirim');
        $email_pengirim     = $this->input->post('email_pengirim');
        $revisi_excel       = $this->input->post('ajuan_excel');
        $revisi_zip         = $this->input->post('revisi_zip');
        $keterangan         = $this->input->post('keterangan');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = md5($this->model_outlet_transaksi->timezone());

        $id_ajuan = $this->model_management_claim->get_ajuan_by_signature($signature_ajuan)->row()->id;

        // echo "<pre>";
        // echo "site_code : ".$site_code."<br>";
        // echo "branch_name : ".$branch_name."<br>";
        // echo "nama_comp : ".$nama_comp."<br>";
        // echo "nama_pengirim : ".$nama_pengirim."<br>";
        // echo "email_pengirim : ".$email_pengirim."<br>";
        // echo "revisi_excel : ".$revisi_excel."<br>";
        // echo "revisi_zip : ".$revisi_zip."<br>";
        // echo "signature_program : ".$signature_program."<br>";
        // echo "created_at : ".$created_at."<br>";
        // echo "signature : ".$signature."<br>";
        // echo "id_program : ".$id_program."<br>";
        // echo "</pre>";

        // die;


        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('revisi_excel')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        if ($this->upload->do_upload('revisi_zip')) 
        {
            $upload_data = $this->upload->data();
            $filename_zip = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        // $this->db->trans_start();
        $data = [
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'keterangan'        => $keterangan,
            'revisi_excel'      => $filename_excel,
            'revisi_zip'        => $filename_zip,
            'id_ajuan'          => $id_ajuan,
            'signature'         => $signature,
            'signature_program' => $signature_program,
            'signature_ajuan'   => $signature_ajuan,
            'created_at'        => $created_at,
            'created_by'        => $this->session->userdata('id'),
        ];

        $this->db->insert('management_claim.revisi_ajuan', $data);
        $id_revisi = $this->db->insert_id();

        $update = [
            'status'           => 2,
            'nama_status'      => 'PENDING MPM',
            'id_revisi'        => $id_revisi
        ];

        $this->db->where('id', $id_ajuan);
        $this->db->update('management_claim.ajuan_claim', $update);

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan revisi. silahkan ulangi kembali";
        //     redirect('management_claim/form_revisi_claim/'.$signature_program.'/'.$signature_ajuan);
        //     die;
        // }

        $this->session->set_flashdata("pesan_success", "Revisi claim berhasil");
        redirect('management_claim/form_revisi_claim/'.$signature_program.'/'.$signature_ajuan);
    }

    public function export_template_bonus_barang($signature_program){

        $nomor_surat = $this->model_management_claim->get_registrasi_program_by_signature($signature_program)->row()->nomor_surat;

        $query = "
            select '' as nomor_surat_program, '' as site_code, '' as no_sales, '' as jumlah, '' as tgl_sales, '' as kode_class, '' as kode_customer, '' as nama_customer, '' as kodeprod, '' as qty_jual, '' as qty_bonus, '' as value_jual, '' as value_bonus
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'nomor_surat_program', 'site_code', 'no_sales', 'tgl_sales(m/d/y)', 'kode_class', 'kode_customer', 'nama_customer', 'kodeprod', 'qty_jual', 'qty_bonus', 'value_jual', 'value_bonus'
        ));
        $this->excel_generator->set_column(array
        ( 
            'nomor_surat_program', 'site_code', 'no_sales', 'tgl_sales', 'kode_class', 'kode_customer', 'nama_customer', 'kodeprod', 'qty_jual', 'qty_bonus', 'value_jual', 'value_bonus'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Template Claim khusus Bonus Barang | Nomor Surat : '.$nomor_surat); 
    }

    public function export_template_bonus_barang_new(){

        $query = "
            select '' as nomor_surat_program, '' as site_code, '' as no_sales, '' as jumlah, '' as tgl_sales, '' as kode_class, '' as kode_customer, '' as nama_customer, '' as kodeprod, '' as qty_jual, '' as qty_bonus, '' as value_jual, '' as value_bonus
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'nomor_surat_program', 'site_code', 'no_sales', 'tgl_sales(m/d/y)', 'kode_class', 'kode_customer', 'nama_customer', 'kodeprod', 'qty_jual', 'qty_bonus', 'value_jual', 'value_bonus'
        ));
        $this->excel_generator->set_column(array
        ( 
            'nomor_surat_program', 'site_code', 'no_sales', 'tgl_sales', 'kode_class', 'kode_customer', 'nama_customer', 'kodeprod', 'qty_jual', 'qty_bonus', 'value_jual', 'value_bonus'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Template Claim khusus Bonus Barang new'); 
    }
    
    public function import_bonus_barang()
    {
        $signature_program  = $this->input->post('signature_program');
        // $signature_ajuan    = $this->input->post('signature_ajuan');
        $site_code_header   = $this->input->post('site_code');
        $id_log             = $this->input->post('id_log');
        $nama               = $this->input->post('nama');
        $email              = $this->input->post('email');
        $first_pic          = $this->input->post('first_pic');
        $status_validasi    = $this->input->post('status_validasi');
        $created_at         = $this->created_at;
        $created_by         = $this->created_by;

        // echo "signature_ajuan : ".$signature_ajuan;
        // die;

        $registrasi_program = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$registrasi_program->num_rows > 0) 
        {
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/form_dp/'.$signature_program);           
        }else
        {
            $id_program = $registrasi_program->row()->id;
            $kategori   = $registrasi_program->row()->kategori;
            $supp   = $registrasi_program->row()->supp;
            $segment   = $registrasi_program->row()->segment;
            // pembuat program
            $pembuat_program = $registrasi_program->row()->updated_by;
            $nama_status_validasi = $registrasi_program->row()->nama_status_validasi;
            $nomor_surat = $registrasi_program->row()->nomor_surat;
            $nama_program = $registrasi_program->row()->nama_program;
        }

        // echo "nomor_surat : ".$nomor_surat;
        // echo "nama_program : ".$nama_program;
        // die;


        $data_header = [
            'kategori'      => $kategori,
            'id_program'    => $id_program,
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];
        $insert_header = $this->model_management_claim->insert_header_import($data_header);

        
        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) {

            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/form_dp/'.$signature_program);
                die;
            }else{
                $insert_traffic = $this->model_management_claim->insert_traffic_import($site_code_header, $this->created_by, 1);
            }
        }else{
            $insert_traffic = $this->model_management_claim->insert_traffic_import($site_code, $this->created_by, 1);
        }

        // die;
        // inisialisasi upload
        $init_upload = $this->attachment_config($kategori);    
        
        if ($this->upload->do_upload('ajuan_zip')) 
        {
            $upload_data = $this->upload->data();
            $filename_zip = $upload_data['file_name'];
        }else
        {
            var_dump($this->upload->display_errors());
            die;
        };

        if ($this->upload->do_upload('ajuan_excel')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_claim/$this->tahun_folder/$kategori/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_claim/form_dp/'.$signature_program,'refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 25200) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 25200 ROW.");
                    redirect('management_claim/form_dp/'.$signature_program);
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('management_claim/form_dp/'.$signature_program);
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $nomor_surat_program    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $site_code              = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $no_sales               = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $tgl_sales              = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());

                    $unix_date          = ($tgl_sales - 25569) * 86400;
                    $excel_date         = 25569 + ($unix_date / 86400);
                    $unix_date          = ($excel_date - 25569) * 86400;
                    $tgl_sales_final    = gmdate("Y-m-d", $unix_date);

                    $kode_class     = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $kode_customer  = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_customer  = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $kodeprod       = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $qty_jual       = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $qty_bonus      = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $value_jual     = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $value_bonus    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());

                    if(strlen("$kodeprod") == '5')
                    {
                        $kodeprodx = '0'.$kodeprod;
                    }else{
                        $kodeprodx = $kodeprod;
                    } 
                    // validasi kodeprod
                    $get_namaprod = $this->model_management_claim->get_product_by_kodeprod_n_supp($kodeprodx, $supp);

                    if ($get_namaprod->num_rows() > 0) {
                        $namaprod = $get_namaprod->row()->namaprod; 
                        $validasi_kodeprod = 0;  
                    }else{
                        $namaprod = '';
                        $validasi_kodeprod = 1;
                    }

                    // cek sub
                    $get_tabcomp = $this->model_management_claim->get_tabcomp_by_site_code($site_code_header);

                    if ($get_tabcomp->num_rows() > 0) {
                        $sub = $get_tabcomp->row()->sub;
                    }else{
                        $sub = '';
                    }

                    // validasi tabcomp
                    $get_tabcomp_by_site_code_and_sub = $this->model_management_claim->get_tabcomp_by_site_code_and_sub($site_code, $sub);

                    if ($get_tabcomp_by_site_code_and_sub->num_rows() > 0) {
                        $nama_comp = $get_tabcomp_by_site_code_and_sub->row()->nama_comp;
                        $branch_name = $get_tabcomp_by_site_code_and_sub->row()->branch_name;
                        $validasi_site_code = 0;
                    }else{
                        $nama_comp = '';
                        $branch_name = '';
                        $validasi_site_code = 1;
                    }
                                        
                    if ($nomor_surat == $nomor_surat_program) {
                        // echo "cocok";
                        $validasi_nomor_surat = 0;
                    }else{
                        // echo "tidak cocok";
                        $id_program = '';
                        $nama_program = '';
                        $validasi_nomor_surat = 1;
                    }

                    // validasi class
                    $get_tabsalur = $this->model_management_claim->get_tabsalur_by_kode_class($kode_class);
                    if ($get_tabsalur->num_rows() > 0) {
                        $nama_class = $get_tabsalur->row()->group;
                        $validasi_class = 0;
                    }else{
                        $nama_class = '';
                        $validasi_class = 1;
                    }

                    $validasi_row = $validasi_kodeprod + $validasi_nomor_surat + $validasi_site_code + $validasi_class;                    
                    $signature = 'ajuan-import-'.rand().md5($this->created_at.rand());

                    $data = [
                        'nomor_surat_program'      => $nomor_surat_program,
                        'id_program'               => $id_program,
                        'nama_program'             => $nama_program,
                        'site_code'                => $site_code,
                        'no_sales'                 => $no_sales,
                        'tgl_sales'                => $tgl_sales_final,
                        'kode_class'               => $kode_class,
                        'nama_class'               => $nama_class,
                        'kode_customer'            => $kode_customer,
                        'nama_customer'            => $nama_customer,
                        'kodeprod'                 => $kodeprod,
                        'namaprod'                 => $namaprod,
                        'qty_jual'                 => $qty_jual,
                        'qty_bonus'                => $qty_bonus,
                        'value_jual'               => $value_jual,
                        'value_bonus'              => $value_bonus,
                        'validasi_kodeprod'        => $validasi_kodeprod,
                        'validasi_site_code'       => $validasi_site_code,
                        'validasi_nomor_surat'     => $validasi_nomor_surat,
                        'validasi_class'           => $validasi_class,
                        'validasi_row'             => $validasi_row,
                        'created_at'               => $created_at,
                        'created_by'               => $this->session->userdata('id'),
                        'signature'                => $signature,
                        'signature_program'        => $signature_program,
                        // 'signature_ajuan'          => $signature_ajuan,
                        'site_code_header'         => $site_code_header,
                        'branch_name'              => $branch_name,
                        'nama_comp'                => $nama_comp,
                        'nama_pengirim'            => $nama,
                        'email_pengirim'           => $email,
                        'ajuan_excel'              => $filename_excel,
                        'ajuan_zip'                => $filename_zip,
                        'status_data_final'        => $status_data_final,
                        'id_header'                => $insert_header,
                        'tahun_folder'             => $this->tahun_folder
                    ];
                    $this->db->insert('management_claim.import_bonus_barang',$data);
                }

                // echo "cccc";
                // die;
            }

            $insert_traffic = $this->model_management_claim->insert_traffic_import($site_code_header, $this->created_by, 0);
            $this->session->set_flashdata("pesan_success", "Cek hasil import anda pada tabel di bawah ini.");
            redirect('management_claim/preview_import_bonus_barang/'.$signature_program.'/'.$signature.'/'.$insert_header);

        }else
        {
            var_dump($this->upload->display_errors());
            die;
        };
    }

    public function preview_import_bonus_barang($signature_program, $signature, $insert_header)
    {
        if (!$signature) {
            $this->session->set_flashdata("pesan", "Claim anda gagal. Ada kesalahan data. Pastikan mengirim data sesuai template yang ditentukan");
            redirect('management_claim/form_dp/'.$signature_program);
        }
        $get_failed = $this->model_management_claim->get_count_validasi_failed_bonus_barang($insert_header);
        if ($get_failed->num_rows() > 0) {
            $total_failed = $get_failed->row()->total;
        }else{
            $total_failed = 0;
        }

        $get_count = $this->model_management_claim->get_count_import_bonus_barang($insert_header);
        if ($get_count->num_rows() > 0) {
            $total_row = $get_count->row()->total;
        }else{
            $total_row = 0;
        }

        $get_success = $this->model_management_claim->get_count_validasi_success_bonus_barang($insert_header);
        if ($get_success->num_rows() > 0) {
            $total_success = $get_success->row()->total;
        }else{
            $total_success = 0;
        }

        $get_sum = $this->model_management_claim->get_sum_import_bonus_barang($insert_header);
        if ($get_sum->num_rows() > 0) {
            $total_qty_jual     = $get_sum->row()->total_qty_jual;
            $total_qty_bonus    = $get_sum->row()->total_qty_bonus;
            $total_value_jual   = $get_sum->row()->total_value_jual ;
            $total_value_bonus  = $get_sum->row()->total_value_bonus ;
        }else{
            $total_success = 0;
        }

        $get_data = $this->model_management_claim->get_preview_import_bonus_barang($insert_header);
        if ($get_data->num_rows() > 0) {
            $nama_pengirim = $get_data->row()->nama_pengirim;
            $email_pengirim = $get_data->row()->email_pengirim;
            $status_data_final = $get_data->row()->status_data_final;
        }else{
            $nama_pengirim = '';
            $email_pengirim = '';
            $status_data_final = '';
        }

        $data = [
            'title'                 => 'preview import bonus barang',
            // 'url'                   => 'management_claim/import_bonus_barang_save',
            'url'                   => 'management_claim/proses_pengajuan',
            'total_failed'          => $total_failed,
            'total_row'             => $total_row,
            'total_success'         => $total_success,
            'total_qty_jual'        => $total_qty_jual,
            'total_qty_bonus'       => $total_qty_bonus,
            'total_qty_jual'        => $total_qty_jual,
            'total_value_jual'      => $total_value_jual,
            'total_value_bonus'     => $total_value_bonus,
            'signature_program'     => $signature_program,
            'signature'             => $signature,
            'nama_pengirim'         => $nama_pengirim,
            'email_pengirim'        => $email_pengirim,
            'status_data_final'     => $status_data_final,
            'get_preview_import'    => $this->model_management_claim->get_preview_import_bonus_barang_failed($insert_header),
            'insert_header'         => $insert_header
        ];

        $this->view($data, false, "preview_import_bonus_barang");
    }

    public function proses_pengajuan()
    {
        $signature_program = $this->input->post('signature_program');
        $signature = $this->input->post('signature');
        $insert_header = $this->input->post('insert_header');

        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) 
        {
            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) 
            {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/preview_import_bonus_barang/'.$signature_program.'/'.$signature.'/'.$insert_header);
                die;
            }else
            {
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->session->userdata('id'), 1);
            }
        }else
        {
            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->session->userdata('id'), 1);
        }

        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $pembuat_program = $get_registrasi_program['updated_by'];
        $id_program = $get_registrasi_program['id_program'];

        // validasi apakah semua row tidak ada error
        $get_count_import = $this->model_management_claim->get_count_import_bonus_barang($insert_header);
        if ($get_count_import->num_rows() > 0) 
        {
            $total_row = $get_count_import->row()->total;
        }else
        {
            $total_row = 0;
        }

        $get_count_validasi_success = $this->model_management_claim->get_count_validasi_success_bonus_barang($insert_header);
        if ($get_count_validasi_success->num_rows() > 0) 
        {
            $total_success = $get_count_validasi_success->row()->total;
        }else
        {
            $total_success = 0;
        }

        $get_preview_import = $this->model_management_claim->get_preview_import_bonus_barang($insert_header);
        if ($get_preview_import->num_rows() > 0) 
        {
            $site_code_header = $get_preview_import->row()->site_code_header;
            $branch_name      = $this->model_management_claim->get_tabcomp_by_site_code($site_code_header)->row()->branch_name;
            $nama_comp        = $this->model_management_claim->get_tabcomp_by_site_code($site_code_header)->row()->nama_comp;
            $nama_pengirim    = $get_preview_import->row()->nama_pengirim;
            $email_pengirim   = $get_preview_import->row()->email_pengirim;
            $ajuan_excel      = $get_preview_import->row()->ajuan_excel;
            $ajuan_zip        = $get_preview_import->row()->ajuan_zip;
            $id_program       = $get_preview_import->row()->id_program;
            $signature_import = $get_preview_import->row()->signature;
            $created_at       = $get_preview_import->row()->created_at;
            $status_data_final= $get_preview_import->row()->status_data_final;
        }

        $status = 2; //2 = on progress
        $status_internal = 9; //9 = pending admin mpm
        $pic_userid = $pembuat_program;
        $level_on_duty = 0;

        $is_exist = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->created_by);
        if ($is_exist->num_rows() > 0) {

            // echo "kalau sudah ada data, maka update ";
            // die;

            // kalau sudah ada data, maka update 

            $id_ajuan    = $is_exist->row()->id;
            $nomor_ajuan = $is_exist->row()->nomor_ajuan;
            $signature_ajuan = $is_exist->row()->signature;

            if (!$nomor_ajuan) {
                $nomor_ajuan = $this->model_management_claim->generate($site_code, $this->created_at);
            }

            $id_log = $is_exist->row()->id_log;
            // echo "id_log : ".$id_log;

            // die;

            $data = [
                "nomor_ajuan"   => $nomor_ajuan,
                "nama_pengirim" => $nama_pengirim,
                "email_pengirim"=> $email_pengirim,
                "site_code"     => $site_code_header,
                "id_program"    => $id_program,
                "ajuan_excel"   => $ajuan_excel,
                "ajuan_zip"     => $ajuan_zip,
                'status'        => $status,
                'status_internal'=> $status_internal,
                'tanggal_claim' => $this->created_at,
                'pic_userid'    => $pic_userid,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
                'id_import_header' => $insert_header,
                'status_keikutsertaan' => 1,
                'tahun_folder'  => $this->tahun_folder
            ];

            $proses = $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);

            // die;
            // $this->session->set_flashdata("pesan", "pengajuan anda gagal. ada kesalahan saat import data sebelumnya, silahkan hub IT terkait untuk memperbaikinya");
            // redirect('management_claim/form_dp/'.$signature_program);
        }else
        {
            $nomor_ajuan = $this->model_management_claim->generate($site_code_header, $this->created_at);
            $signature = 'ajuan-claim-'.rand().md5($this->created_at.rand());
            
            $data = [
                "nomor_ajuan"   => $nomor_ajuan,
                "nama_pengirim" => $nama_pengirim,
                "email_pengirim"=> $email_pengirim,
                "site_code"     => $site_code_header,
                "id_program"    => $id_program,
                "ajuan_excel"   => $ajuan_excel,
                "ajuan_zip"     => $ajuan_zip,
                'status'        => $status,
                'status_internal'=> $status_internal,
                'tanggal_claim' => $this->created_at,
                'pic_userid'    => $pic_userid,
                'created_at'    => $this->created_at,
                'created_by'    => $this->created_by,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
                'signature'     => $signature,
                'id_import_header' => $insert_header,
                'status_keikutsertaan' => 1,
                'tahun_folder'  => $this->tahun_folder
            ];

            $proses = $this->model_management_claim->insert_ajuan_claim($data);
        }
        // echo "proses : ".$proses."<br>";
        // echo "nomor_ajuan : ".$nomor_ajuan."<br>";

        // die;

        if ($proses) 
        {
            // echo "sudah di proses";
            // die;

            $time_response = ($this->model_management_claim->get_status_internal($status_internal) ? $this->model_management_claim->get_status_internal($status_internal)->row()->time_response : 0);
            $duedate_response = date('Y-m-d', strtotime('+'.$time_response.' days', strtotime($this->created_at)));

            // echo "status_internal : ".$status_internal."<br>";
            // echo "time_response : ".$time_response."<br>";
            // echo "duedate_response : ".$duedate_response."<br>";
            // die;

            // echo "a";
            // die;
            $data_log = [
                "id_registrasi" => $id_program,
                "id_ajuan"      => $proses,
                "status"        => $status,
                "status_internal"   => $status_internal,
                "function"      => "proses_pengajuan",
                "file"          => $ajuan_excel,
                "file_zip"      => $ajuan_zip,
                "signature"     => 'log-'.rand().md5($this->created_at.rand()),
                "created_at"    => $this->created_at,
                "created_by"    => $this->created_by,
                "updated_at"    => $this->created_at,
                "updated_by"    => $this->created_by,
                "on_duty_finish"=> 0,
                "pic_on_duty"   => $pic_userid,
                "level_on_duty" => 1,     
                'time_response' => $time_response,
                'duedate_response' => $duedate_response,
                'tahun_folder'     => $this->tahun_folder
            ];

            $insert_log = $this->model_management_claim->insert_log_claim($data_log);
            // echo "insert_log : ".$insert_log;
            // die;
            if ($insert_log) 
            {
                // update ajuan_claim set id_log
                $update_ajuan = [
                    "id_log"    => $insert_log,
                ];
                $update = $this->model_management_claim->update_ajuan_claim($update_ajuan, $proses);

                $update_log = [
                    "on_duty_finish"    => 1,
                    "updated_by"        => $this->created_by,
                    "updated_at"        => $this->created_at
                ];
                $this->model_management_claim->update_log_claim($update_log, $id_log);
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
                $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");

                // echo "signature : ".$signature;
                // echo "<br>";
                // echo "signature_program : ".$signature_program;

                // die;

                redirect('management_claim/email/'.$signature_program.'/'.$signature_ajuan);
                // redirect('management_claim/form_dp/'.$signature_program);
                die;
            }else
            {
                $log_error = [
                    "id_registrasi" => $id_program,
                    "id_ajuan"      => $proses,
                    "created_at"    => $this->created_at,
                    "created_by"    => $this->created_by,
                ];
                $insert_log_error = $this->model_management_claim->insert_log_error($log_error);
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
                $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");
                redirect('management_claim/form_dp/'.$signature_program);
                die;
            }
        }else
        {
            $this->session->set_flashdata("pesan", "Proses anda gagal. Silahkan cek kembali data anda");
            redirect('management_claim/form_dp/'.$signature_program);
            die;
        }
    }

    public function import_diskon()
    {
        $signature_program  = $this->input->post('signature_program');
        $site_code_header   = $this->input->post('site_code');
        $id_log             = $this->input->post('id_log');
        $nama               = $this->input->post('nama');
        $email              = $this->input->post('email');
        $first_pic          = $this->input->post('first_pic');
        $status_validasi    = $this->input->post('status_validasi');
        $created_at         = $this->created_at;
        $created_by         = $this->created_by;

        $registrasi_program = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$registrasi_program->num_rows > 0) 
        {
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/form_dp/'.$signature_program);      
        }else
        {
            $id_program = $registrasi_program->row()->id;
            $kategori   = $registrasi_program->row()->kategori;
            $supp   = $registrasi_program->row()->supp;
            $segment   = $registrasi_program->row()->segment;
            // pembuat program
            $pembuat_program = $registrasi_program->row()->updated_by;
            $nama_status_validasi = $registrasi_program->row()->nama_status_validasi;
            $nomor_surat = $registrasi_program->row()->nomor_surat;
            $nama_program = $registrasi_program->row()->nama_program;
        }

        $data_header = [
            'kategori'      => $kategori,
            'id_program'    => $id_program,
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];
        $insert_header = $this->model_management_claim->insert_header_import($data_header);
        
        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) {

            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/form_dp/'.$signature_program);
                die;
            }else{
                $insert_traffic = $this->model_management_claim->insert_traffic_import($site_code_header, $this->created_by, 1);
            }
        }else{
            $insert_traffic = $this->model_management_claim->insert_traffic_import($site_code, $this->created_by, 1);
        }

        // die;
        // inisialisasi upload
        $init_upload = $this->attachment_config($kategori);    
        
        if ($this->upload->do_upload('ajuan_zip')) 
        {
            $upload_data = $this->upload->data();
            $filename_zip = $upload_data['file_name'];
        }else
        {
            var_dump($this->upload->display_errors());
            die;
        };

        if ($this->upload->do_upload('ajuan_excel')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_claim/$this->tahun_folder/$kategori/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_claim/form_dp/'.$signature_program,'refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('management_claim/form_dp/'.$signature_program);
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('management_claim/form_dp/'.$signature_program);
                }
                

                for ($row = 2; $row <= $highestRow; $row++) 
                {          
                    $nomor_surat_program    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $site_code              = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $no_sales               = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $tgl_sales              = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());

                    $unix_date          = ($tgl_sales - 25569) * 86400;
                    $excel_date         = 25569 + ($unix_date / 86400);
                    $unix_date          = ($excel_date - 25569) * 86400;
                    $tgl_sales_final    = gmdate("Y-m-d", $unix_date);

                    $kode_class     = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $kode_customer  = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_customer  = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $kodeprod       = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $qty_jual       = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $value_jual     = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $disc_principal = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $disc_extra     = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $disc_cash      = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_claim     = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());

                    if(strlen("$kodeprod") == '5')
                    {
                        $kodeprodx = '0'.$kodeprod;
                    }else{
                        $kodeprodx = $kodeprod;
                    } 
                    // validasi kodeprod
                    $get_namaprod = $this->model_management_claim->get_product_by_kodeprod_n_supp($kodeprodx, $supp);

                    if ($get_namaprod->num_rows() > 0) {
                        $namaprod = $get_namaprod->row()->namaprod; 
                        $validasi_kodeprod = 0;  
                    }else{
                        $namaprod = '';
                        $validasi_kodeprod = 1;
                    }

                    // cek sub
                    $get_tabcomp = $this->model_management_claim->get_tabcomp_by_site_code($site_code_header);

                    if ($get_tabcomp->num_rows() > 0) {
                        $sub = $get_tabcomp->row()->sub;
                    }else{
                        $sub = '';
                    }

                    // validasi tabcomp
                    $get_tabcomp_by_site_code_and_sub = $this->model_management_claim->get_tabcomp_by_site_code_and_sub($site_code, $sub);

                    if ($get_tabcomp_by_site_code_and_sub->num_rows() > 0) {
                        $nama_comp = $get_tabcomp_by_site_code_and_sub->row()->nama_comp;
                        $branch_name = $get_tabcomp_by_site_code_and_sub->row()->branch_name;
                        $validasi_site_code = 0;
                    }else{
                        $nama_comp = '';
                        $branch_name = '';
                        $validasi_site_code = 1;
                    }
                                        
                    if ($nomor_surat == $nomor_surat_program) {
                        // echo "cocok";
                        $validasi_nomor_surat = 0;
                    }else{
                        // echo "tidak cocok";
                        $id_program = '';
                        $nama_program = '';
                        $validasi_nomor_surat = 1;
                    }
                    // validasi class
                    $get_tabsalur = $this->model_management_claim->get_tabsalur_by_kode_class($kode_class);
                    if ($get_tabsalur->num_rows() > 0) {
                        $nama_class = $get_tabsalur->row()->group;
                        $validasi_class = 0;
                    }else{
                        $nama_class = '';
                        $validasi_class = 1;
                    }
                                        // validasi class
                    $get_tabsalur = $this->model_management_claim->get_tabsalur_by_kode_class($kode_class);
                    if ($get_tabsalur->num_rows() > 0) {
                        $nama_class = $get_tabsalur->row()->group;
                        $validasi_class = 0;
                    }else{
                        $nama_class = '';
                        $validasi_class = 1;
                    }

                    $validasi_row = $validasi_kodeprod + $validasi_nomor_surat + $validasi_site_code + $validasi_class;                
                    $signature = 'ajuan-import-'.rand().md5($this->created_at.rand());

                    $data = [
                        'nomor_surat_program'      => $nomor_surat_program,
                        'id_program'               => $id_program,
                        'nama_program'             => $nama_program,
                        'site_code'                => $site_code,
                        'no_sales'                 => $no_sales,
                        'tgl_sales'                => $tgl_sales_final,
                        'kode_class'               => $kode_class,
                        'nama_class'               => $nama_class,
                        'kode_customer'            => $kode_customer,
                        'nama_customer'            => $nama_customer,
                        'kodeprod'                 => $kodeprod,
                        'namaprod'                 => $namaprod,
                        'qty_jual'                 => $qty_jual,
                        'value_jual'               => $value_jual,
                        'disc_principal'           => $disc_principal,
                        'disc_cabang'              => $disc_cabang,
                        'disc_cash'                => $disc_cash,
                        'disc_extra'               => $disc_extra,
                        'disc_claim'               => $disc_claim,
                        'validasi_kodeprod'        => $validasi_kodeprod,
                        'validasi_site_code'       => $validasi_site_code,
                        'validasi_nomor_surat'     => $validasi_nomor_surat,
                        'validasi_class'           => $validasi_class,
                        'validasi_row'             => $validasi_row,
                        'created_at'               => $created_at,
                        'created_by'               => $this->created_by,
                        'signature'                => $signature,
                        'signature_program'        => $signature_program,
                        'site_code_header'         => $site_code_header,
                        'branch_name'              => $branch_name,
                        'nama_comp'                => $nama_comp,
                        'nama_pengirim'            => $nama,
                        'email_pengirim'           => $email,
                        'ajuan_excel'              => $filename_excel,
                        'ajuan_zip'                => $filename_zip,
                        'status_data_final'        => 1,
                        'id_header'                => $insert_header,
                        'tahun_folder'             => $this->tahun_folder
                    ];
                    $this->db->insert('management_claim.import_diskon',$data);

                    // die;

                }
            }

            $insert_traffic = $this->model_management_claim->insert_traffic_import($site_code_header, $this->created_by, 0);
            $this->session->set_flashdata("pesan_success", "Cek hasil import anda pada tabel di bawah ini.");
            redirect('management_claim/preview_import_diskon/'.$signature_program.'/'.$signature.'/'.$insert_header);

        }else
        {
            var_dump($this->upload->display_errors());
            die;
        };
    }

    public function preview_import_diskon($signature_program, $signature, $insert_header)
    {
        // echo "insert_header : ".$insert_header;
        // die;


        if (!$signature) {
            $this->session->set_flashdata("pesan", "Claim anda gagal. Ada kesalahan data. Pastikan mengirim data sesuai template yang ditentukan");
            redirect('management_claim/form_dp/'.$signature_program);
        }

        $get_failed = $this->model_management_claim->get_count_validasi_failed_diskon($insert_header);
        if ($get_failed->num_rows() > 0) {
            $total_failed = $get_failed->row()->total;
        }else{
            $total_failed = 0;
        }


        $get_count = $this->model_management_claim->get_count_import_diskon($insert_header);
        if ($get_count->num_rows() > 0) {
            $total_row = $get_count->row()->total;
        }else{
            $total_row = 0;
        }

        $get_success = $this->model_management_claim->get_count_validasi_success_diskon($insert_header);
        if ($get_success->num_rows() > 0) {
            $total_success = $get_success->row()->total;
        }else{
            $total_success = 0;
        }

        $get_sum = $this->model_management_claim->get_sum_import_diskon($insert_header);
        if ($get_sum->num_rows() > 0) {
            $total_qty_jual             = $get_sum->row()->total_qty_jual;
            $total_value_jual           = $get_sum->row()->total_value_jual;
            $total_disc_principal   = $get_sum->row()->total_disc_principal;
            $total_disc_cabang          = $get_sum->row()->total_disc_cabang;
            $total_disc_extra           = $get_sum->row()->total_disc_extra;
            $total_disc_cash            = $get_sum->row()->total_disc_cash;
            $total_disc_claim           = $get_sum->row()->total_disc_claim;
        }else{
            $total_success = 0;
        }

        $get_data = $this->model_management_claim->get_preview_import_diskon($insert_header);
        if ($get_data->num_rows() > 0) {
            $nama_pengirim = $get_data->row()->nama_pengirim;
            $email_pengirim = $get_data->row()->email_pengirim;
            $status_data_final = $get_data->row()->status_data_final;
        }else{
            $nama_pengirim = '';
            $email_pengirim = '';
            $status_data_final = '';
        }

        $data = [
            'title'                 => 'preview import diskon',
            'url'                   => 'management_claim/proses_pengajuan_diskon',
            'total_failed'          => $total_failed,
            'total_row'             => $total_row,
            'total_success'         => $total_success,
            'total_qty_jual'        => $total_qty_jual,
            'total_qty_jual'        => $total_qty_jual,
            'total_value_jual'      => $total_value_jual,
            'total_disc_principal'  => $total_disc_principal,
            'total_disc_cabang'     => $total_disc_cabang,
            'total_disc_extra'      => $total_disc_extra,
            'total_disc_cash'       => $total_disc_cash,
            'total_disc_claim'      => $total_disc_claim,
            'signature_program'     => $signature_program,
            'signature'             => $signature,
            'nama_pengirim'         => $nama_pengirim,
            'email_pengirim'        => $email_pengirim,
            'status_data_final'     => $status_data_final,
            // 'get_preview_import'    => $this->model_management_claim->get_preview_import_failed_diskon($signature_program, $signature),
            'get_preview_import'    => $this->model_management_claim->get_preview_import_failed_diskon_by_idheader($insert_header),
            'insert_header'         => $insert_header
        ];

        $this->view($data, false, "preview_import_diskon");
    }

    public function proses_pengajuan_diskon()
    {
        $signature_program = $this->input->post('signature_program');
        $signature = $this->input->post('signature');
        $insert_header = $this->input->post('insert_header');

        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) 
        {
            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) 
            {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/preview_import_diskon/'.$signature_program.'/'.$signature.'/'.$insert_header);
                die;
            }else
            {
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->session->userdata('id'), 1);
            }
        }else
        {
            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->session->userdata('id'), 1);
        }

        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $pembuat_program = $get_registrasi_program['updated_by'];
        $id_program = $get_registrasi_program['id_program'];

        // validasi apakah semua row tidak ada error
        $get_count_import = $this->model_management_claim->get_count_import_diskon($insert_header);
        if ($get_count_import->num_rows() > 0) 
        {
            $total_row = $get_count_import->row()->total;
        }else
        {
            $total_row = 0;
        }

        $get_count_validasi_success = $this->model_management_claim->get_count_validasi_success_diskon($insert_header);
        if ($get_count_validasi_success->num_rows() > 0) 
        {
            $total_success = $get_count_validasi_success->row()->total;
        }else
        {
            $total_success = 0;
        }

        $get_preview_import = $this->model_management_claim->get_preview_import_diskon($insert_header);
        if ($get_preview_import->num_rows() > 0) 
        {
            $site_code_header = $get_preview_import->row()->site_code_header;
            $branch_name      = $this->model_management_claim->get_tabcomp_by_site_code($site_code_header)->row()->branch_name;
            $nama_comp        = $this->model_management_claim->get_tabcomp_by_site_code($site_code_header)->row()->nama_comp;
            $nama_pengirim    = $get_preview_import->row()->nama_pengirim;
            $email_pengirim   = $get_preview_import->row()->email_pengirim;
            $ajuan_excel      = $get_preview_import->row()->ajuan_excel;
            $ajuan_zip        = $get_preview_import->row()->ajuan_zip;
            $id_program       = $get_preview_import->row()->id_program;
            $signature_import = $get_preview_import->row()->signature;
            $created_at       = $get_preview_import->row()->created_at;
            $status_data_final= $get_preview_import->row()->status_data_final;
        }

        $status = 2; //2 = on progress
        $status_internal = 9; //9 = pending admin mpm
        $pic_userid = $pembuat_program;
        $level_on_duty = 0;

        $is_exist = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->created_by);
        if ($is_exist->num_rows() > 0) 
        {
            // kalau sudah ada data, maka update 

            $id_ajuan    = $is_exist->row()->id;
            $nomor_ajuan = $is_exist->row()->nomor_ajuan;
            $signature_ajuan = $is_exist->row()->signature;

            // echo "if exists ";
            // echo "id_ajuan : ".$id_ajuan;   
            // echo "nomor_ajuan : ".$nomor_ajuan;
            // echo "signature_ajuan : ".$signature_ajuan;
            // die;

            if (!$nomor_ajuan) {
                $nomor_ajuan = $this->model_management_claim->generate($site_code, $this->created_at);
            }

            $id_log = $is_exist->row()->id_log;
            // echo "id_log : ".$id_log;

            $data = [
                "nomor_ajuan"   => $nomor_ajuan,
                "nama_pengirim" => $nama_pengirim,
                "email_pengirim"=> $email_pengirim,
                "site_code"     => $site_code_header,
                "id_program"    => $id_program,
                "ajuan_excel"   => $ajuan_excel,
                "ajuan_zip"     => $ajuan_zip,
                'status'        => $status,
                'status_internal'=> $status_internal,
                'tanggal_claim' => $this->created_at,
                'pic_userid'    => $pic_userid,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
                'id_import_header' => $insert_header,
                'status_keikutsertaan' => 1,
                'tahun_folder'  => $this->tahun_folder
            ];

            $proses = $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);

        }else
        {
            $nomor_ajuan = $this->model_management_claim->generate($site_code_header, $this->created_at);
            $signature = 'ajuan-claim-'.rand().md5($this->created_at.rand());
            
            $data = [
                "nomor_ajuan"   => $nomor_ajuan,
                "nama_pengirim" => $nama_pengirim,
                "email_pengirim"=> $email_pengirim,
                "site_code"     => $site_code_header,
                "id_program"    => $id_program,
                "ajuan_excel"   => $ajuan_excel,
                "ajuan_zip"     => $ajuan_zip,
                'status'        => $status,
                'status_internal'=> $status_internal,
                'tanggal_claim' => $this->created_at,
                'pic_userid'    => $pic_userid,
                'created_at'    => $this->created_at,
                'created_by'    => $this->created_by,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
                'signature'     => $signature,
                'id_import_header' => $insert_header,
                'status_keikutsertaan' => 1,
                'tahun_folder'  => $this->tahun_folder
            ];

            $proses = $this->model_management_claim->insert_ajuan_claim($data);
        }

        if ($proses) 
        {
            $time_response = ($this->model_management_claim->get_status_internal($status_internal) ? $this->model_management_claim->get_status_internal($status_internal)->row()->time_response : 0);
            $duedate_response = date('Y-m-d', strtotime('+'.$time_response.' days', strtotime($this->created_at)));

            $data_log = [
                "id_registrasi" => $id_program,
                "id_ajuan"      => $proses,
                "status"        => $status,
                "status_internal"   => $status_internal,
                "function"      => "proses_pengajuan_diskon",
                "file"          => $ajuan_excel,
                "file_zip"      => $ajuan_zip,
                "signature"     => 'log-'.rand().md5($this->created_at.rand()),
                "created_at"    => $this->created_at,
                "created_by"    => $this->created_by,
                "updated_at"    => $this->created_at,
                "updated_by"    => $this->created_by,
                "on_duty_finish"=> 0,
                "pic_on_duty"   => $pic_userid,
                "level_on_duty" => 1,
                "time_response" => $time_response,
                "duedate_response" => $duedate_response,
                "tahun_folder"  => $this->tahun_folder
            ];

            $insert_log = $this->model_management_claim->insert_log_claim($data_log);
            // echo "insert_log : ".$insert_log;
            // die;
            if ($insert_log) 
            {
                // update ajuan_claim set id_log
                $update_ajuan = [
                    "id_log"    => $insert_log,
                ];
                $update = $this->model_management_claim->update_ajuan_claim($update_ajuan, $proses);

                $update_log = [
                    "on_duty_finish"    => 1,
                    "updated_by"        => $this->created_by,
                    "updated_at"        => $this->created_at
                ];
                $this->model_management_claim->update_log_claim($update_log, $id_log);

                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
                $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");

                redirect('management_claim/email/'.$signature_program.'/'.$signature_ajuan);
                // redirect('management_claim/form_dp/'.$signature_program);
                // die;
            }else
            {
                $log_error = [
                    "id_registrasi" => $id_program,
                    "id_ajuan"      => $proses,
                    "created_at"    => $this->created_at,
                    "created_by"    => $this->created_by,
                ];
                $insert_log_error = $this->model_management_claim->insert_log_error($log_error);
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 0);
                $this->session->set_flashdata("pesan_success", "pengajuan anda sudah masuk. Silahkan monitor status pengajuan anda secara berkala");
                redirect('management_claim/form_dp/'.$signature_program);
                die;
            }
        }else
        {
            $this->session->set_flashdata("pesan", "Proses anda gagal. Silahkan cek kembali data anda");
            redirect('management_claim/form_dp/'.$signature_program);
            die;
        }
    }

    public function email_status($signature_program, $signature)
    {
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if ($get_registrasi_program_by_signature->num_rows() > 0) {
            $kategori               = $get_registrasi_program_by_signature->row()->kategori;
            $from                   = $get_registrasi_program_by_signature->row()->from;
            $to                     = $get_registrasi_program_by_signature->row()->to;
            $nomor_surat            = $get_registrasi_program_by_signature->row()->nomor_surat;
            $nama_program           = $get_registrasi_program_by_signature->row()->nama_program;
            $syarat                 = $get_registrasi_program_by_signature->row()->syarat;
            $duedate                = $get_registrasi_program_by_signature->row()->duedate;
            $upload_jpg             = $get_registrasi_program_by_signature->row()->upload_jpg;
            $upload_pdf             = $get_registrasi_program_by_signature->row()->upload_pdf;
            $upload_template_program = $get_registrasi_program_by_signature->row()->upload_template_program;
            $email_register_program = $get_registrasi_program_by_signature->row()->email;
            $namasupp               = $get_registrasi_program_by_signature->row()->namasupp;
            $status_validasi    = $get_registrasi_program_by_signature->row()->status_validasi;
            
            if ($status_validasi == null) {
                $params_status_validasi = 1;
            }else{
                $params_status_validasi = $status_validasi;
            }

            if ($params_status_validasi == 1) {
                $params_folder = "import";
            }else{
                $params_folder = "";
            }
        }

        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature);
        if ($get_ajuan_by_signature->num_rows() > 0) 
        {
            $id_ajuan           = $get_ajuan_by_signature->row()->id;
            $nomor_ajuan        = $get_ajuan_by_signature->row()->nomor_ajuan;
            $branch_name        = $get_ajuan_by_signature->row()->branch_name;
            $nama_comp          = $get_ajuan_by_signature->row()->nama_comp;
            $nama_pengirim      = $get_ajuan_by_signature->row()->nama_pengirim;
            $email_pengirim     = $get_ajuan_by_signature->row()->email_pengirim;
            $site_code          = $get_ajuan_by_signature->row()->site_code;
            $ajuan_excel        = $get_ajuan_by_signature->row()->ajuan_excel;
            $ajuan_zip          = $get_ajuan_by_signature->row()->ajuan_zip;
            $status_data_final  = $get_ajuan_by_signature->row()->status_data_final;
            $tanggal_claim      = $get_ajuan_by_signature->row()->tanggal_claim;
            $nama_status        = $get_ajuan_by_signature->row()->nama_status;
            $created_at         = $get_ajuan_by_signature->row()->created_at;
            $id_verifikasi      = $get_ajuan_by_signature->row()->id_verifikasi;
            $status             = $get_ajuan_by_signature->row()->status;
            $nama_status        = $get_ajuan_by_signature->row()->nama_status;
            $status_internal    = $get_ajuan_by_signature->row()->status_internal;
            $nama_status_internal = $get_ajuan_by_signature->row()->nama_status_internal;
            $pic_userid         = $get_ajuan_by_signature->row()->pic_userid;

            // kirim hardcopy
            $file_hardcopy      = $get_ajuan_by_signature->row()->file_hardcopy;
            $nomor_hardcopy     = $get_ajuan_by_signature->row()->nomor_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_by_signature->row()->tanggal_kirim_hardcopy;
            $nama_pengirim_hardcopy = $get_ajuan_by_signature->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_by_signature->row()->email_pengirim_hardcopy;
            $update_kirim_hardcopy_at = $get_ajuan_by_signature->row()->update_kirim_hardcopy_at;

            //terima hardcopy
            $tanggal_terima_hardcopy = $get_ajuan_by_signature->row()->tanggal_terima_hardcopy;
            $terima_hardcopy_by = $get_ajuan_by_signature->row()->terima_hardcopy_by;
            $terima_hardcopy_nama = $this->model_management_claim->get_user($terima_hardcopy_by)->row()->username;
            $update_terima_hardcopy_at = $get_ajuan_by_signature->row()->update_terima_hardcopy_at;

            // penyerahan hardcopy ke principal 
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_by_signature->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_by_signature->row()->tanda_terima_hardcopy_ke_principal_nama;
            $update_tanda_terima_hardcopy_ke_principal = $get_ajuan_by_signature->row()->update_tanda_terima_hardcopy_ke_principal;
        }

        if ($id_verifikasi) 
        {
            $get_verifikasi_by_id = $this->model_management_claim->get_verifikasi_by_id($id_verifikasi);
            if ($get_verifikasi_by_id->num_rows > 0) 
            {
                $verifikasi_signature = $get_verifikasi_by_id->row()->signature;
                $verifikasi_keterangan = $get_verifikasi_by_id->row()->keterangan;
                $verifikasi_file = $get_verifikasi_by_id->row()->file;
                $verifikasi_created_at = $get_verifikasi_by_id->row()->created_at;
                $verifikasi_username = $get_verifikasi_by_id->row()->username;
            }
            else
            {
                $verifikasi_signature   = "";
                $verifikasi_keterangan  = "";
                $verifikasi_file        = "";
                $verifikasi_created_at  = "";
                $verifikasi_username    = "";
            }
        }

        $data_log = $this->model_management_claim->log_aktivitas_claim_by_id_ajuan($id_ajuan); 
        if ($data_log->num_rows() > 0) {
            $keterangan = $data_log->row()->keterangan.' by '.$data_log->row()->username;
        }else{
            $keterangan = '';
        }

        $data = [
            'nomor_ajuan'           => $nomor_ajuan,
            'branch_name'           => $branch_name,
            'nama_comp'             => $nama_comp,
            'kategori'              => $kategori,
            'namasupp'              => $namasupp,
            'periode'               => $from.' - '.$to,
            'nomor_surat'           => $nomor_surat,
            'nama_program'          => $nama_program,
            'upload_jpg'            => $upload_jpg,
            'upload_pdf'            => $upload_pdf,
            'status_data_final'     => $status_data_final,
            'nama_pengirim'         => $nama_pengirim,
            'email_pengirim'        => $email_pengirim,
            'ajuan_excel'           => $ajuan_excel,
            'ajuan_zip'             => $ajuan_zip,
            'tanggal_claim'         => $tanggal_claim,
            'created_at'            => $created_at,
            'signature_program'     => $signature_program,
            'signature'             => $signature,
            'verifikasi_keterangan' => $verifikasi_keterangan,
            'verifikasi_created_at' => $verifikasi_created_at,
            'verifikasi_username'   => $verifikasi_username,
            'status'                => $status,
            'nama_status'           => $nama_status,
            'status_internal'       => $status_internal,
            'nama_status_internal'  => $nama_status_internal,
            'params_folder'         => $params_folder,
            'keterangan'            => $keterangan,
            'file_hardcopy'         => $file_hardcopy,
            'nomor_hardcopy'        => $nomor_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'update_kirim_hardcopy_at' => $update_kirim_hardcopy_at,
            'tanggal_terima_hardcopy' => $tanggal_terima_hardcopy,
            'terima_hardcopy_nama' => $terima_hardcopy_nama,
            'update_terima_hardcopy_at' => $update_terima_hardcopy_at,
            'tanggal_tanda_terima_hardcopy_ke_principal' => $tanggal_tanda_terima_hardcopy_ke_principal,
            'tanda_terima_hardcopy_ke_principal_nama' => $tanda_terima_hardcopy_ke_principal_nama,
            'update_tanda_terima_hardcopy_ke_principal' => $update_tanda_terima_hardcopy_ke_principal
        ];

        $from   = "suffy@muliaputramandiri.net";
        $to     = $email_pengirim;
        $cc     = $email_register_program.','.$this->email_tim;

        $message = $this->load->view("management_claim/email_status",$data,TRUE);
        $subject = "MPM SITE | CLAIM : $nomor_surat | ".$branch_name." | ".$nama_status;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        if ($send) 
        {
            // $this->session->set_flashdata("pesan_success", "Pengiriman email berhasil. Terima kasih");    
            // if ($status == '2') {
            //     redirect('management_claim/form_ajuan_claim/'.$signature_program);
            //     die;
            // }

            redirect('management_claim/routing/'.$signature_program.'/'.$signature);
            
        }else{
            echo "<script>alert('pengiriman email gagal. Namun mungkin data anda sudah masuk'); </script>";
            die;
        }

    }

    public function download_raw($signature_ajuan){
        // echo "signature_ajuan : ".$signature_ajuan;

        $query = "
            select 	a.nama_program, a.nama_pengirim, a.email_pengirim, a.nomor_surat_program, a.site_code_header, 
                    a.site_code, a.branch_name, a.nama_comp, a.ajuan_excel, a.ajuan_zip, a.no_sales, a.tgl_sales, a.kode_class,
                    a.nama_class, a.kode_customer, a.kodeprod, a.namaprod, a.qty_jual, a.qty_bonus, a.value_jual, a.value_bonus
            from management_claim.import_bonus_barang a 
            where a.signature = '$signature_ajuan'
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
            'tgl_sales','kode_class','nama_class','kode_customer','kodeprod','namaprod','qty_jual','qty_bonus','value_jual','value_bonus'
        ));
        $this->excel_generator->set_column(array
        ( 
            'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
            'tgl_sales','kode_class','nama_class','kode_customer','kodeprod','namaprod','qty_jual','qty_bonus','value_jual','value_bonus'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Raw Data'); 


    }

    public function report(){

        if($this->input->get('from')){
            
            $advanced['from']   = $this->input->get('from');
            $advanced['to']     = $this->input->get('to');
            $advanced['supp'] = $this->input->get('supp');
        
        }else{
            $advanced = "";
        }

        $data = [
            'title'     => 'management claim | reporting',
            'url'       => 'report_proses',
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/report', $data);
        $this->load->view('kalimantan/footer');

    }

    public function report_proses()
    { 
        $supp       = $this->input->get('supp');
        $kategori   = $this->input->get('kategori');
        $site_code  = $this->input->get('site_code');
        $from       = $this->input->get('from');
        $to         = $this->input->get('to');
        $status     = $this->input->get('status');
        $pic        = $this->input->get('pic');

        $advanced = [
            'supp'       => $supp,
            'site_code'  => $site_code,
            'from'       => $from,
            'to'         => $to,
            'kategori'   => $kategori,
            'status'     => $status,
            'pic'        => $pic
        ];

        $get_registrasi_program_by_supp_date = $this->model_management_claim->get_registrasi_program_by_supp_date($advanced);
        
        if ($get_registrasi_program_by_supp_date->num_rows() > 0) 
        {
            $signature_ajuan_gabung = '';
            foreach ($get_registrasi_program_by_supp_date->result() as $a) {
                $signature_ajuan_gabung.=",'".$a->signature_ajuan."'";
                $signature_ajuan_join= preg_replace('/,/', '', $signature_ajuan_gabung,1) ;
            }

            // echo "kategori : ".$kategori;
            if ($kategori == 'bonus_barang') {
                $query = "
                select 	a.nama_program, a.nama_pengirim, a.email_pengirim, a.nomor_surat_program, a.site_code_header, 
                        a.site_code, a.branch_name, a.nama_comp, a.ajuan_excel, a.ajuan_zip, a.no_sales, a.tgl_sales, a.kode_class,
                        a.nama_class, a.kode_customer,a.nama_customer, a.kodeprod, a.namaprod, a.qty_jual, a.qty_bonus, a.value_jual, a.value_bonus
                from management_claim.import_bonus_barang a 
                where a.signature in ($signature_ajuan_join)
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
            // die;

            // die;

                $hasil = $this->db->query($query);  

                $this->excel_generator->set_query($hasil);
                $this->excel_generator->set_header(array
                (
                    'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
                    'tgl_sales','kode_class','nama_class','kode_customer','nama_customer','kodeprod','namaprod','qty_jual','qty_bonus','value_jual','value_bonus'
                ));
                $this->excel_generator->set_column(array
                ( 
                    'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
                    'tgl_sales','kode_class','nama_class','kode_customer', 'nama_customer','kodeprod','namaprod','qty_jual','qty_bonus','value_jual','value_bonus'
                ));
                $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
                $this->excel_generator->exportTo2007('Download Raw Data');  
            }elseif ($kategori == 'diskon_herbal' || $kategori == 'diskon_candy' || $kategori == 'diskon') {
                $query = "
                select 	a.nama_program, a.nama_pengirim, a.email_pengirim, a.nomor_surat_program, a.site_code_header, 
                        a.site_code, a.branch_name, a.nama_comp, a.ajuan_excel, a.ajuan_zip, a.no_sales, a.tgl_sales, a.kode_class,
                        a.nama_class, a.kode_customer, a.nama_customer, a.kodeprod, a.namaprod, a.qty_jual, a.value_jual, a.disc_principal, a.disc_cabang, a.disc_extra, a.disc_cash, a.disc_claim
                from management_claim.import_diskon a 
                where a.signature in ($signature_ajuan_join)
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";

            // die;

                $hasil = $this->db->query($query);  

                $this->excel_generator->set_query($hasil);
                $this->excel_generator->set_header(array
                (
                    'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
                    'tgl_sales','kode_class','nama_class','kode_customer','a.nama_customer', 'kodeprod','namaprod','qty_jual','value_jual','disc_principal','disc_cabang','disc_extra','disc_cash','disc_claim'
                ));
                $this->excel_generator->set_column(array
                ( 
                    'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
                    'tgl_sales','kode_class','nama_class','kode_customer', 'nama_customer', 'kodeprod','namaprod','qty_jual','value_jual','disc_principal','disc_cabang','disc_extra','disc_cash','disc_claim'
                ));
                $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
                $this->excel_generator->exportTo2007('Download Raw Data');  
            }else{

                $this->session->set_flashdata("pesan", "Data not found");
                redirect('management_claim/report');
            
            }

            
        }else{
            
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('management_claim/report');

        }        
    }

    public function export_master_site($site_code){
        
        $this->load->model('model_master_data');

        $tahun_now = date('Y');

        $get_tabcomp_by_site_code = $this->model_master_data->get_tabcomp_by_site_code($site_code);
        if ($get_tabcomp_by_site_code->num_rows() > 0) {
            $sub = $get_tabcomp_by_site_code->row()->sub;
            // echo "sub : ".$sub;
        }

        $query = "
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a INNER JOIN (
                select concat(a.kode_comp, a.nocab) as site_code
                from db_dp.t_dp a 
                where a.tahun = $tahun_now and a.`status` = 1
            )b on concat(a.kode_comp, a.nocab) = b.site_code
            where a.status = 1 and a.sub = '$sub'
        ";
        $hasil = $this->db->query($query);  

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code','branch_name','nama_comp'
        ));
        $this->excel_generator->set_column(array
        ( 
            'site_code','branch_name','nama_comp'
        ));
        $this->excel_generator->set_width(array(10,20,20)); 
        $this->excel_generator->exportTo2007('Download Master Site');  
    }

    public function export_master_class(){
        
        $query = "
            select a.kode, a.group as nama_class
            from mpm.tbl_tabsalur a 
            where a.kode in ('RT','SO','SW','WS')
        ";
        $hasil = $this->db->query($query);  

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'kode','nama_class'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kode','nama_class'
        ));
        $this->excel_generator->set_width(array(10,20)); 
        $this->excel_generator->exportTo2007('Download Master Class');  
    }

    public function delete_registrasi_program($signature){
        $data = [
            'deleted'   => 1
        ];
        
        $this->db->update('management_claim.registrasi_program', $data, array('signature' => $signature));
        $this->session->set_flashdata("pesan_success", "delete berhasil");
        redirect('management_claim/registrasi_program/');
    }

    public function hardcopy($signature_program, $signature_ajuan)
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $id_program = $get_registrasi_program['id_program'];

        $get_ajuan_by_signature = $this->get_ajuan_by_signature($signature_ajuan);
        $id_ajuan = $get_ajuan_by_signature['id_ajuan'];
        $ajuan_by = $get_ajuan_by_signature['ajuan_by'];
        $ajuan_by_email = $get_ajuan_by_signature['nama_pengirim'];

        if ($id_ajuan) 
        {
            $get_pic = $this->model_management_claim->get_log_aktivitas_by_onduty($id_ajuan);
            if ($get_pic->num_rows > 0) {
                $pic_on_duty = $get_pic->row()->username;
                $email_on_duty = $get_pic->row()->email;
            }else{
                $pic_on_duty = $ajuan_by;
                $email_on_duty = $ajuan_by_email;
            }
        }else{
            $pic_on_duty = $ajuan_by;
            $email_on_duty = "";
        }

        // status authorized
        if ($pic_on_duty == $this->session->userdata('username')) {
            $status_authorized = true;
        }else{
            $status_authorized = false;
        }

        $data = [
            'title'                     => 'management claim | Update Resi Pengiriman',            
            'url'                       => 'management_claim/update_hardcopy',
            'signature_program'         => $signature_program,            
            'signature_ajuan'           => $signature_ajuan,   
            'kategori'                  => $get_registrasi_program['kategori'],      
            'namasupp'                  => $get_registrasi_program['namasupp'],      
            'from'                      => $get_registrasi_program['from'],      
            'to'                        => $get_registrasi_program['to'],      
            'nama_program'              => $get_registrasi_program['nama_program'],      
            'nomor_surat'               => $get_registrasi_program['nomor_surat'],          
            'duedate'                   => $get_registrasi_program['duedate'],      
            'upload_pdf'                => $get_registrasi_program['upload_pdf'],      
            'username'                  => $get_registrasi_program['username'],    
            'params_status_validasi'    => $get_registrasi_program['params_status_validasi'],
            'params_folder'             => $get_registrasi_program['params_folder'],
            'segment'                   => $get_registrasi_program['segment'],
            'nama_status_validasi'      => $get_registrasi_program['nama_status_validasi'],
            'keterangan'                => $get_registrasi_program['keterangan'],
            'nama_template'             => $get_registrasi_program['nama_template'],
            'filename'                  => $get_registrasi_program['filename'],
            'pic'                       => $get_registrasi_program['pic'],
            'id_kategori'               => $get_registrasi_program['id_kategori'],               
            'nama_pengirim'             => $get_ajuan_by_signature['nama_pengirim'],      
            'email_pengirim'            => $get_ajuan_by_signature['email_pengirim'],      
            'ajuan_excel'               => $get_ajuan_by_signature['ajuan_excel'],      
            'ajuan_zip'                 => $get_ajuan_by_signature['ajuan_zip'],         
            'created_at'                => $get_ajuan_by_signature['created_at'],      
            'nama_status'               => $get_ajuan_by_signature['nama_status'],      
            'nama_status_internal'      => $get_ajuan_by_signature['nama_status_internal'],  
            'nomor_ajuan'               => $get_ajuan_by_signature['nomor_ajuan'],      
            'site_code'                 => $get_ajuan_by_signature['site_code'], 
            'tanggal_claim'             => $get_ajuan_by_signature['tanggal_claim'],
            'branch_name'               => $get_ajuan_by_signature['branch_name'],
            'nama_comp'                 => $get_ajuan_by_signature['nama_comp'],
            'id_log'                    => $get_ajuan_by_signature['id_log'],
            'pic_on_duty'               => $pic_on_duty,
            'email_on_duty'             => $email_on_duty,
            'status_authorized'         => $status_authorized,
            'get_log'                   => $this->model_management_claim->get_log_aktivitas_by_id_ajuan($id_ajuan),
            'get_status_internal'       => $this->model_management_claim->get_status_internal('5,8,11,21,22',''),
        ];

        $this->view($data, true, "hardcopy");
    }

    public function update_hardcopy()
    {
        $signature_ajuan            = $this->input->post('signature_ajuan');
        $signature_program          = $this->input->post('signature_program');
        $nama_pengirim_hardcopy     = $this->input->post('nama_pengirim_hardcopy');
        $email_pengirim_hardcopy    = $this->input->post('email_pengirim_hardcopy');
        $nomor_hardcopy             = $this->input->post('nomor_hardcopy');
        $tanggal_kirim_hardcopy     = $this->input->post('tanggal_kirim_hardcopy');
        $file_resi                  = $this->input->post('file_resi');
        $id_log                     = $this->input->post('id_log');

        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) 
        {
            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) 
            {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/hardcopy/'.$signature_program.'/'.$signature_ajuan);
                die;
            }else
            {
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
            }
        }else
        {
            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->created_by, 1);
        }


        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        // var_dump($get_registrasi_program);

        $id_program = $get_registrasi_program['id_program'];
        $id_kategori = $get_registrasi_program['id_kategori'];
        $kategori = $get_registrasi_program['kategori'];
        $pembuat_program = $get_registrasi_program['updated_by'];

        $ajuan_claim = $this->model_management_claim->get_ajuan_claim_by_id_program_and_signature($id_program, $signature_ajuan);

        if (!$ajuan_claim->num_rows > 0) {
            $this->session->set_flashdata("pesan", "Proses anda gagal. Data not found");
            redirect('management_claim/hardcopy/'.$signature_program.'/'.$signature_ajuan);    
        }else{
            $id_ajuan = $ajuan_claim->row()->id;
            $ajuan_by = $ajuan_claim->row()->created_by;
            $site_code = $ajuan_claim->row()->site_code;
        }
        
        // inisialisasi upload
        $init_upload = $this->attachment_config($id_kategori);        
        if ($this->upload->do_upload('file_resi')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else
        {
            $filename = '';
        };

        $userid_head = $pembuat_program;
        $userid_head_name = $this->model_management_claim->get_user($userid_head)->row()->username;
        $userid_head_email = $this->model_management_claim->get_user($userid_head)->row()->email;
        $status = 2; // status menjadi on progress
        $status_internal = 9; // status internal menjadi pending admin mpm

        // echo "id_log : ".$id_log."<br>";
        // echo "id_program : ".$id_program."<br>";
        // echo "id_ajuan : ".$id_ajuan."<br>";
        // echo "status : ".$status."<br>";
        // echo "status_internal : ".$status_internal."<br>";
        // echo "filename : ".$filename."<br>";

        // die;

        $time_response = ($this->model_management_claim->get_status_internal($status_internal) ? $this->model_management_claim->get_status_internal($status_internal)->row()->time_response : 0);
        $duedate_response = date('Y-m-d', strtotime('+'.$time_response.' days', strtotime($this->created_at)));
 
        // insert log
        $data_log = [
            "ref_log"       => $id_log,
            "id_registrasi" => $id_program,
            "id_ajuan"      => $id_ajuan,
            "status"        => $status,
            "status_internal"   => $status_internal,
            "function"      => "update_hardcopy",
            "file"          => $filename,
            "signature"     => 'log-'.rand().md5($this->created_at.rand()),
            "created_at"    => $this->created_at,
            "created_by"    => $this->created_by,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->created_by,
            "on_duty_finish"=> 0,
            "pic_on_duty"   => $userid_head,
            "level_on_duty" => 0,
            "time_response" => $time_response,
            "duedate_response" => $duedate_response
        ];
        $insert_log = $this->model_management_claim->insert_log_claim($data_log);

        // update ajuan_claim
        $update_ajuan = [
            "id_log"            => $insert_log,
            "pic_userid"        => $userid_head,
            "status"            => $status,
            "status_internal"   => $status_internal,
            "updated_at"        => $this->created_at,
            "updated_by"        => $this->created_by,
            "nomor_hardcopy"    => $nomor_hardcopy,
            "tanggal_kirim_hardcopy"=>$tanggal_kirim_hardcopy,
            "nama_pengirim_hardcopy"=>$nama_pengirim_hardcopy,
            "email_pengirim_hardcopy"=>$email_pengirim_hardcopy
        ];
        $proses_update_ajuan = $this->model_management_claim->update_ajuan_claim($update_ajuan, $id_ajuan);

        $update_log = [
            "on_duty_finish"    => 1,
            "updated_by"        => $this->created_by,
            "updated_at"        => $this->created_at
        ];
        $this->model_management_claim->update_log_claim($update_log, $id_log);

        $this->session->set_flashdata("pesan_success", "verifikasi anda berhasil");
        redirect('management_claim/email/'.$signature_program.'/'.$signature_ajuan);
        // redirect('management_claim/hardcopy/'.$signature_program.'/'.$signature_ajuan);
        die;


















        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if ($get_registrasi_program_by_signature->num_rows > 0) 
        {
            $id_program     = $get_registrasi_program_by_signature->row()->id;
            $program_created_by = $get_registrasi_program_by_signature->row()->created_by; 
        }else
        {
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');
            die;
        }

        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature_ajuan);
        if ($get_ajuan_by_signature->num_rows > 0) 
        {
            $id_ajuan       = $get_ajuan_by_signature->row()->id;
            $pic_userid     = $get_ajuan_by_signature->row()->pic_userid;
            if ($pic_userid <> $this->session->userdata('id')) 
            {
                $status_authorized = false;
            }else{
                $status_authorized = true;
            }
        }else
        {
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');
            die;
        }


        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file_resi')) 
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        $status = 2; // status kembali menjadi on progress
        $status_internal = 6; // 6 = pending terima hardcopy
        $pic_userid = $program_created_by;

        $data = [
            'nama_pengirim_hardcopy'  => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'nomor_hardcopy'          => $nomor_hardcopy,
            'status_hardcopy'         => 1,
            'nama_status_hardcopy'    => 'PENDING MPM',
            'file_hardcopy'           => $file_name,
            'update_kirim_hardcopy_at'=> $created_at,
            'tanggal_kirim_hardcopy'  => $tanggal_kirim_hardcopy,
            'status'                  => $status,
            'nama_status'             => ($this->model_management_claim->get_status($status)->num_rows() > 0) ? $this->model_management_claim->get_status($status)->row()->nama_status : '',
            'status_internal'         => $status_internal,
            'nama_status_internal'    => ($this->model_management_claim->get_status_internal($status_internal)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '',
            'pic_userid'              => $pic_userid

        ];

        $this->db->where('id', $id_ajuan);
        $this->db->update('management_claim.ajuan_claim', $data);

        $insert_log = $this->model_management_claim->insert_log($id_program, $id_ajuan, $status, $status_internal, 'update_hardcopy', $nomor_hardcopy, $file_name);

        redirect('management_claim/email_status/'.$signature_program.'/'.$signature_ajuan);
        die;
    
    }

    public function email_hardcopy($signature_program, $signature){
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $get_ajuan_claim = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if ($get_ajuan_claim->num_rows() > 0) {
            $kategori               = $get_ajuan_claim->row()->kategori;
            $namasupp               = $get_ajuan_claim->row()->namasupp;
            $from                   = $get_ajuan_claim->row()->from;
            $to                     = $get_ajuan_claim->row()->to;
            $nomor_surat            = $get_ajuan_claim->row()->nomor_surat;
            $nama_program           = $get_ajuan_claim->row()->nama_program;
            $syarat                 = $get_ajuan_claim->row()->syarat;
            $duedate                = $get_ajuan_claim->row()->duedate;
            $upload_jpg             = $get_ajuan_claim->row()->upload_jpg;
            $upload_pdf             = $get_ajuan_claim->row()->upload_pdf;
            $upload_template_program = $get_ajuan_claim->row()->upload_template_program;
            $email_register_program = $get_ajuan_claim->row()->email;
        }
        

        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature);
        if ($get_ajuan_by_signature->num_rows() > 0) {
            $nomor_ajuan        = $get_ajuan_by_signature->row()->nomor_ajuan;
            $branch_name        = $get_ajuan_by_signature->row()->branch_name;
            $nama_comp          = $get_ajuan_by_signature->row()->nama_comp;
            $nama_pengirim      = $get_ajuan_by_signature->row()->nama_pengirim;
            $email_pengirim     = $get_ajuan_by_signature->row()->email_pengirim;
            $site_code          = $get_ajuan_by_signature->row()->site_code;
            $ajuan_excel        = $get_ajuan_by_signature->row()->ajuan_excel;
            $ajuan_zip          = $get_ajuan_by_signature->row()->ajuan_zip;
            $status             = $get_ajuan_by_signature->row()->status;
            $status_data_final  = $get_ajuan_by_signature->row()->status_data_final;
            $tanggal_claim      = $get_ajuan_by_signature->row()->tanggal_claim;
            $nama_status        = $get_ajuan_by_signature->row()->nama_status;
            $created_at         = $get_ajuan_by_signature->row()->created_at;
            $id_verifikasi      = $get_ajuan_by_signature->row()->id_verifikasi;

            $status_hardcopy                        = $get_ajuan_by_signature->row()->status_hardcopy;
            $nama_status_hardcopy                   = $get_ajuan_by_signature->row()->nama_status_hardcopy;
            $file_hardcopy                          = $get_ajuan_by_signature->row()->file_hardcopy;
            $nomor_hardcopy                         = $get_ajuan_by_signature->row()->nomor_hardcopy;
            $tanggal_kirim_hardcopy                 = $get_ajuan_by_signature->row()->tanggal_kirim_hardcopy;
            $nama_pengirim_hardcopy                 = $get_ajuan_by_signature->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy                = $get_ajuan_by_signature->row()->email_pengirim_hardcopy;
            $update_kirim_hardcopy_at               = $get_ajuan_by_signature->row()->update_kirim_hardcopy_at;
            $tanggal_terima_hardcopy                = $get_ajuan_by_signature->row()->tanggal_terima_hardcopy;
            $terima_hardcopy_by                     = $get_ajuan_by_signature->row()->terima_hardcopy_by;
            $update_terima_hardcopy_at              = $get_ajuan_by_signature->row()->update_terima_hardcopy_at;
            $file_tanda_terima_hardcopy_ke_principal= $get_ajuan_by_signature->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanda_terima_hardcopy_ke_principal_by  = $get_ajuan_by_signature->row()->tanda_terima_hardcopy_ke_principal_by;
            $tanda_terima_hardcopy_ke_principal_nama= $get_ajuan_by_signature->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal   = $get_ajuan_by_signature->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $update_tanda_terima_hardcopy_ke_principal    = $get_ajuan_by_signature->row()->update_tanda_terima_hardcopy_ke_principal;


        }
        if ($id_verifikasi) {
                $get_verifikasi_by_id = $this->model_management_claim->get_verifikasi_by_id($id_verifikasi);
                if ($get_verifikasi_by_id->num_rows > 0) {
                    $verifikasi_signature = $get_verifikasi_by_id->row()->signature;
                    $verifikasi_keterangan = $get_verifikasi_by_id->row()->keterangan;
                    $verifikasi_file = $get_verifikasi_by_id->row()->file;
                    $verifikasi_created_at = $get_verifikasi_by_id->row()->created_at;
                    $verifikasi_username = $get_verifikasi_by_id->row()->username;
                }
            }else{
                $verifikasi_signature   = "";
                $verifikasi_keterangan  = "";
                $verifikasi_file        = "";
                $verifikasi_created_at  = "";
                $verifikasi_username    = "";
            }

        $data = [
            'nomor_ajuan'                   => $nomor_ajuan,
            'branch_name'                   => $branch_name,
            'nama_comp'                     => $nama_comp,
            'kategori'                      => $kategori,
            'namasupp'                      => $namasupp,
            'periode'                       => $from.' - '.$to,
            'nomor_surat'                   => $nomor_surat,
            'nama_program'                  => $nama_program,
            'upload_jpg'                    => $upload_jpg,
            'upload_pdf'                    => $upload_pdf,
            'nama_status'                   => $nama_status,
            'status_data_final'             => $status_data_final,
            'nama_pengirim'                 => $nama_pengirim,
            'email_pengirim'                => $email_pengirim,
            'ajuan_excel'                   => $ajuan_excel,
            'ajuan_zip'                     => $ajuan_zip,
            'tanggal_claim'                 => $tanggal_claim,
            'created_at'                    => $created_at,
            'signature_program'             => $signature_program,
            'signature'                     => $signature,
            'verifikasi_keterangan'         => $verifikasi_keterangan,
            'verifikasi_created_at'         => $verifikasi_created_at,
            'verifikasi_username'           => $verifikasi_username,

            'nama_status_hardcopy'          => $nama_status_hardcopy,
            'file_hardcopy'                 => $file_hardcopy,
            'nomor_hardcopy'                => $nomor_hardcopy,
            'email_pengirim_hardcopy'       => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy'        => $tanggal_kirim_hardcopy,
            'nama_pengirim_hardcopy'        => $nama_pengirim_hardcopy,
            'update_kirim_hardcopy_at'      => $update_kirim_hardcopy_at,
            'tanggal_terima_hardcopy'       => $tanggal_terima_hardcopy,
            'update_terima_hardcopy_at'     => $update_terima_hardcopy_at,
            'tanda_terima_hardcopy_ke_principal_nama'     => $tanda_terima_hardcopy_ke_principal_nama,
            'file_tanda_terima_hardcopy_ke_principal'     => $file_tanda_terima_hardcopy_ke_principal,
            'tanggal_tanda_terima_hardcopy_ke_principal'  => $tanggal_tanda_terima_hardcopy_ke_principal,

        ];

        // die;

        $from   = "suffy@muliaputramandiri.net";
        $to     = $email_pengirim_hardcopy;
        $cc     = $email_register_program;

        $message = $this->load->view("management_claim/email_hardcopy",$data,TRUE);
        $subject = "MPM SITE | CLAIM : $nomor_surat | ".$branch_name." | ".$nama_status;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) 
        {
            $this->session->set_flashdata("pesan_success", "Data anda sudah masuk dan pengiriman email berhasil. Terima kasih");  
            redirect('management_claim/routing_hardcopy/'.$signature_program.'/'.$signature);
            
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }
    }

    public function verifikasi_hardcopy($signature_program, $signature_ajuan)
    {
        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if ($get_registrasi_program_by_signature->num_rows > 0) 
        {
            $id_program     = $get_registrasi_program_by_signature->row()->id;
            $kategori       = $get_registrasi_program_by_signature->row()->kategori;
            $namasupp       = $this->model_master_data->get_namasupp_by_supp($get_registrasi_program_by_signature->row()->supp)->row()->NAMASUPP;
            $from           = $get_registrasi_program_by_signature->row()->from;
            $to             = $get_registrasi_program_by_signature->row()->to;
            $nama_program   = $get_registrasi_program_by_signature->row()->nama_program;
            $nomor_surat    = $get_registrasi_program_by_signature->row()->nomor_surat;
            $syarat         = $get_registrasi_program_by_signature->row()->syarat;
            $duedate        = $get_registrasi_program_by_signature->row()->duedate;
            $upload_jpg     = $get_registrasi_program_by_signature->row()->upload_jpg;
            $upload_pdf     = $get_registrasi_program_by_signature->row()->upload_pdf;
            $program_created_by = $get_registrasi_program_by_signature->row()->created_by;

            $username       = $this->model_master_data->get_username_by_id($get_registrasi_program_by_signature->row()->created_by)->row()->username;

            $status_validasi = $get_registrasi_program_by_signature->row()->status_validasi;
            if ($status_validasi == null) {
                $params_status_validasi = 1;
            }else{
                $params_status_validasi = $status_validasi;
            }

            if ($params_status_validasi == 1) {
                $params_folder = "import";
            }else{
                $params_folder = "";
            }
        }else
        {
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');
            die;
        }

        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature_ajuan);
        if ($get_ajuan_by_signature->num_rows > 0) 
        {
            $created_at     = $get_ajuan_by_signature->row()->created_at;
            $signature_ajuan= $get_ajuan_by_signature->row()->signature;
            $nama_comp      = $get_ajuan_by_signature->row()->nama_comp;
            $nama_pengirim  = $get_ajuan_by_signature->row()->nama_pengirim;
            $email_pengirim = $get_ajuan_by_signature->row()->email_pengirim;
            $ajuan_excel    = $get_ajuan_by_signature->row()->ajuan_excel;
            $ajuan_zip      = $get_ajuan_by_signature->row()->ajuan_zip;
            $nama_status    = $get_ajuan_by_signature->row()->nama_status;
            $id_ajuan       = $get_ajuan_by_signature->row()->id;
            $nomor_ajuan    = $get_ajuan_by_signature->row()->nomor_ajuan;
            $tanggal_claim  = $get_ajuan_by_signature->row()->tanggal_claim;
            $branch_name    = $get_ajuan_by_signature->row()->branch_name;
            $nama_comp      = $get_ajuan_by_signature->row()->nama_comp;
            $site_code      = $get_ajuan_by_signature->row()->site_code;
            $id_verifikasi  = $get_ajuan_by_signature->row()->id_verifikasi;

            $status_hardcopy            = $get_ajuan_by_signature->row()->status_hardcopy;
            $nama_status_hardcopy       = $get_ajuan_by_signature->row()->nama_status_hardcopy;
            $file_hardcopy              = $get_ajuan_by_signature->row()->file_hardcopy;
            $nomor_hardcopy             = $get_ajuan_by_signature->row()->nomor_hardcopy;
            $tanggal_kirim_hardcopy     = $get_ajuan_by_signature->row()->tanggal_kirim_hardcopy;
            $nama_pengirim_hardcopy     = $get_ajuan_by_signature->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy    = $get_ajuan_by_signature->row()->email_pengirim_hardcopy;
            $update_kirim_hardcopy_at   = $get_ajuan_by_signature->row()->update_kirim_hardcopy_at;
            $tanggal_terima_hardcopy    = $get_ajuan_by_signature->row()->tanggal_terima_hardcopy;
            $terima_hardcopy_by         = $get_ajuan_by_signature->row()->terima_hardcopy_by;
            $update_terima_hardcopy_at  = $get_ajuan_by_signature->row()->update_terima_hardcopy_at;
            $file_tanda_terima_hardcopy_ke_principal= $get_ajuan_by_signature->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanda_terima_hardcopy_ke_principal_by  = $get_ajuan_by_signature->row()->tanda_terima_hardcopy_ke_principal_by;
            $tanda_terima_hardcopy_ke_principal_nama= $get_ajuan_by_signature->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_by_signature->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $update_tanda_terima_hardcopy_ke_principal = $get_ajuan_by_signature->row()->update_tanda_terima_hardcopy_ke_principal;

            $terima_hardcopy_nama = $this->model_management_claim->get_user($terima_hardcopy_by)->row()->username;
            $status_internal = $get_ajuan_by_signature->row()->status_internal;
            $nama_status_internal = $get_ajuan_by_signature->row()->nama_status_internal;
            $pic_userid = $get_ajuan_by_signature->row()->pic_userid;

            if ($pic_userid == '') {
                $pic_userid = $program_created_by;
            }
            
            if ($pic_userid <> $this->session->userdata('id'))
            {
                $status_authorized = false;
            }else{
                $status_authorized = true;
            }
        }

        if ($id_verifikasi) 
        {
            $get_verifikasi_by_id = $this->model_management_claim->get_verifikasi_by_id($id_verifikasi);
            if ($get_verifikasi_by_id->num_rows > 0) {
                $verifikasi_signature = $get_verifikasi_by_id->row()->signature;
                $verifikasi_keterangan = $get_verifikasi_by_id->row()->keterangan;
                $verifikasi_file = $get_verifikasi_by_id->row()->file;
                $verifikasi_created_at = $get_verifikasi_by_id->row()->created_at;
                $verifikasi_username = $get_verifikasi_by_id->row()->username;
            }
        }else
        {
            $verifikasi_signature = '';
            $verifikasi_keterangan = '';
            $verifikasi_file = '';
            $verifikasi_created_at = '';
            $verifikasi_username = '';
        }

        $data = [
            'title'                     => 'management claim | Verifikasi Hardcopy MPM',
            'url'                       => 'management_claim/verifikasi_hardcopy_save',
            'signature_program'         => $signature_program,            
            'signature_ajuan'           => $signature_ajuan,   
            'site_code'                 => $this->model_management_claim->get_sitecode($this->session->userdata('id')),
            'kategori'                  => $kategori,      
            'namasupp'                  => $namasupp,      
            'from'                      => $from,      
            'to'                        => $to,      
            'nama_program'              => $nama_program,      
            'nomor_surat'               => $nomor_surat,      
            'syarat'                    => $syarat,      
            'duedate'                   => $duedate,      
            'upload_jpg'                => $upload_jpg,      
            'upload_pdf'                => $upload_pdf,      
            'username'                  => $username,       
            'nama_pengirim'             => $nama_pengirim,      
            'email_pengirim'            => $email_pengirim,      
            'ajuan_excel'               => $ajuan_excel,      
            'ajuan_zip'                 => $ajuan_zip,      
            'signature_program'         => $signature_program,      
            'signature_ajuan'           => $signature_ajuan,      
            'created_at'                => $created_at,      
            'nama_status'               => $nama_status,      
            'verifikasi_signature'      => $verifikasi_signature,      
            'verifikasi_keterangan'     => $verifikasi_keterangan,      
            'verifikasi_file'           => $verifikasi_file,      
            'verifikasi_created_at'     => $verifikasi_created_at,      
            'verifikasi_username'       => $verifikasi_username,      
            'nomor_ajuan'               => $nomor_ajuan,      
            'site_code_form'            => $this->model_management_claim->get_sitecode($this->session->userdata('id')),  
            'status_hardcopy'           => $status_hardcopy,
            'nama_status_hardcopy'      => $nama_status_hardcopy,
            'file_hardcopy'             => $file_hardcopy,
            'nomor_hardcopy'            => $nomor_hardcopy,
            'tanggal_kirim_hardcopy'    => $tanggal_kirim_hardcopy,
            'nama_pengirim_hardcopy'    => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy'   => $email_pengirim_hardcopy,
            'update_kirim_hardcopy_at'  => $update_kirim_hardcopy_at,
            'tanggal_terima_hardcopy'   => $tanggal_terima_hardcopy,
            'terima_hardcopy_by'        => $terima_hardcopy_by,
            'update_terima_hardcopy_at' => $update_terima_hardcopy_at,
            'file_tanda_terima_hardcopy_ke_principal'    => $file_tanda_terima_hardcopy_ke_principal,
            'tanda_terima_hardcopy_ke_principal_by'      => $tanda_terima_hardcopy_ke_principal_by,
            'tanda_terima_hardcopy_ke_principal_nama'    => $tanda_terima_hardcopy_ke_principal_nama,
            'tanggal_tanda_terima_hardcopy_ke_principal' => $tanggal_tanda_terima_hardcopy_ke_principal,
            'update_tanda_terima_hardcopy_ke_principal'  => $update_tanda_terima_hardcopy_ke_principal,

            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'tanggal_claim'             => $tanggal_claim,
            'terima_hardcopy_nama'      => $terima_hardcopy_nama,
            'status_internal'           => $status_internal,
            'nama_status_internal'      => $nama_status_internal,
            'status_authorized'         => $status_authorized,
            'params_status_validasi'    => $params_status_validasi,
            'params_folder'             => $params_folder
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('management_claim/accordion', $data);
        $this->load->view('management_claim/verifikasi_hardcopy', $data);
        $this->load->view('kalimantan/footer');

    }

    public function verifikasi_hardcopy_save()
    {
        $status_internal        = $this->input->post('status_internal');
        $tanggal_terima_hardcopy= $this->input->post('tanggal_terima_hardcopy');
        $file_tanda_terima_hardcopy_ke_principal    = $this->input->post('file_tanda_terima_hardcopy_ke_principal');
        $tanda_terima_hardcopy_ke_principal_nama    = $this->input->post('tanda_terima_hardcopy_ke_principal_nama');
        $tanggal_tanda_terima_hardcopy_ke_principal = $this->input->post('tanggal_tanda_terima_hardcopy_ke_principal');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');
        $created_at         = $this->model_outlet_transaksi->timezone();
        // die;

        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);

        if(!$get_registrasi_program_by_signature->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. Data not found !! ");
            redirect('management_claim/ajuan_claim/');
            die;
        }else{
            $id_program         = $get_registrasi_program_by_signature->row()->id;
            $program_created_by = $get_registrasi_program_by_signature->row()->created_by; 
        }

        $get_ajuan_claim_by_id_program = $this->model_management_claim->get_ajuan_claim_by_id_program_and_signature($id_program, $signature_ajuan);

        if(!$get_ajuan_claim_by_id_program->num_rows() > 0){
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. Data not found !! ");
            redirect('management_claim/ajuan_claim/');
            die;
        }else{
            $id_ajuan    = $get_ajuan_claim_by_id_program->row()->id;
            $nomor_ajuan = $get_ajuan_claim_by_id_program->row()->nomor_ajuan;
            $site_code   = $get_ajuan_claim_by_id_program->row()->site_code;
            $ajuan_by    = $get_ajuan_claim_by_id_program->row()->created_by;
            $username_dp = $this->model_master_data->get_username_by_id($ajuan_by)->row()->username;
        }

        // cek pic userid
        $get_pic_region_by_site_code = $this->model_management_claim->get_pic_region_by_site_code($site_code);
        if ($get_pic_region_by_site_code->num_rows() > 0) 
        {    
            $userid_principal = $get_pic_region_by_site_code->row()->userid_principal;
            $pic_userid = $program_created_by;

        }else{
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. data mapping pic region not found. silahkan infokan ini ke PIC terkait.");
            redirect('management_claim/ajuan_claim/');
        }

        // echo "status_internal : ".$status_internal;
        // echo "id_ajuan : ".$id_ajuan;
        // echo "nomor_ajuan : ".$nomor_ajuan;
        // echo "site_code : ".$site_code;
        // echo "userid_principal : ".$userid_principal;
        // echo "pic_userid : ".$pic_userid;
        // die;

        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file_tanda_terima_hardcopy_ke_principal')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename = '';
            $filename = $this->input->post('file_tanda_terima_hardcopy_ke_principal_old');
        };

        if ($status_internal == '7') // jika reject hardcopy
        {
            $status = 3; // status menjadi pending hardcopy dp
            $pic_userid = $ajuan_by;
        }else
        {
            $status = 2; // status tetap on progress
        }

        $data = [
            'status_hardcopy'                          => $status_hardcopy,
            'nama_status_hardcopy'                     => $nama_status_hardcopy,
            'tanggal_terima_hardcopy'                  => $tanggal_terima_hardcopy,
            'terima_hardcopy_by'                       => $this->session->userdata('id'),
            'update_terima_hardcopy_at'                => $created_at,
            'file_tanda_terima_hardcopy_ke_principal'  => $filename,
            'tanda_terima_hardcopy_ke_principal_by'    => $this->session->userdata('id'),
            'tanda_terima_hardcopy_ke_principal_nama'  => $tanda_terima_hardcopy_ke_principal_nama,
            'tanggal_tanda_terima_hardcopy_ke_principal'=> $tanggal_tanda_terima_hardcopy_ke_principal,            
            'last_updated_at'                          => $created_at,
            'status'                                   => $status,
            'nama_status'=> ($this->model_management_claim->get_status($status)->num_rows() > 0) ? $this->model_management_claim->get_status($status)->row()->nama_status : '',
            'status_internal'                          => $status_internal,
            'nama_status_internal'=> ($this->model_management_claim->get_status_internal($status_internal)->num_rows() > 0) ? $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status : '',
        ];

        $this->db->where('id', $id_ajuan);
        $this->db->update('management_claim.ajuan_claim', $data);

        $insert_log = $this->model_management_claim->insert_log($id_program, $id_ajuan, $status, $status_internal, 'verifikasi_hardcopy_save', $keterangan, $filename);

        redirect('management_claim/email_status/'.$signature_program.'/'.$signature_ajuan);
        die;
        
    }

    public function flag_keikutsertaan($signature_program, $signature_ajuan = '')
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);

        $status = 17; // 17 = tidak ikut, ada di tabel master_status
        $nama_status = $this->model_management_claim->get_status($status)->row()->nama_status;

        $status_internal = 1;
        $nama_status_internal = $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status;

        if ($signature_ajuan == '') {

            $username = $this->session->userdata('username');

            $get_site_code_by_userid = $this->model_management_claim->get_site_code_by_userid($username);
            if ($get_site_code_by_userid->num_rows() > 0) {
                $site_code = $get_site_code_by_userid->row()->site_code;
            }else{
                $this->session->set_flashdata("pesan", "Proses gagal karena hanya DP yang bisa menentukan keikutsertaan program");
                redirect('management_claim/ajuan_claim/');
                die;
            }
            
            $signature  = 'ajuan-claim-'.rand().md5($this->created_at.rand());

            $data = [
                'site_code'         => $site_code,
                'status'            => $status,
                'nama_status'       => $nama_status,
                'status_internal'   => $status_internal,
                'nama_status_internal' => $nama_status_internal, 
                'signature'         => $signature,
                'status_keikutsertaan' => 0,
                'nama_status_keikutsertaan' => 'tidak ikut',
                'id_program'        => $get_registrasi_program['id_program'],
                'created_at'        => $this->created_at,
                'created_by'        => $this->created_by,
            ];
            $this->model_management_claim->insert_ajuan_claim($data);
            $this->session->set_flashdata("pesan_success", "Input flag status berhasil");
            redirect('management_claim/ajuan_claim/');
            die;

        }else{

            $get_ajuan_by_signature = $this->get_ajuan_by_signature($signature_ajuan);
            $id_ajuan = $get_ajuan_by_signature['id_ajuan'];
            
            $data = [
                'status'                    => $status,
                'nama_status'               => $nama_status,
                'status_internal'           => $status_internal,
                'nama_status_internal'      => $nama_status_internal,
                'status_keikutsertaan'      => 0,
                'nama_status_keikutsertaan' => 'tidak ikut',
                'updated_at'                => $this->created_at,
                'updated_by'                => $this->created_by
            ];

            $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);
            $this->session->set_flashdata("pesan_success", "Update flag status berhasil");
            redirect('management_claim/ajuan_claim/');
            die;
        }

    }

    public function buletin_program(){
        redirect('management_claim/ajuan_claim');
        $data = [
            'title'   => 'management claim | buletin program',
            'get_data'=> $this->model_management_claim->get_buletin_program(),
            'url'     => 'management_claim/buletin_program_save'
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);

        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/buletin_program', $data);
        $this->load->view('kalimantan/footer');
    }

    public function buletin_program_save(){
        $supp       = $this->input->post('supp');
        $periode    = $this->input->post('periode');
        $keterangan = $this->input->post('keterangan');

        $params_tahun = substr($periode, 0, 4);
        $params_bulan = substr($periode, 5, 2);

        $created_at     = $this->model_outlet_transaksi->timezone();
        $signature      = md5($this->model_outlet_transaksi->timezone());

        if (!is_dir('./assets/uploads/management_claim/buletin_program/')) {
            @mkdir('./assets/uploads/management_claim/buletin_program/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/buletin_program/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attachment')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        $data = [
            'supp'          => $supp,
            'tahun'         => $params_tahun,
            'bulan'         => $params_bulan,
            'keterangan'    => $keterangan,
            'file'          => $filename,
            'signature'     => $signature,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id'),
        ];

        $proses = $this->db->insert('management_claim.buletin_program', $data);
        if ($proses) {
            $this->session->set_flashdata("pesan_success", "buletin program created successfully");
            redirect('management_claim/buletin_program');
        }
    }

    public function delete_buletin($signature){

        if ($this->session->userdata('username') != 'ismi') {
            $this->session->set_flashdata("pesan", "you are not allowed to delete buletin program");
            redirect('management_claim/buletin_program');
            die;
        }

        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $update = $this->db->update('management_claim.buletin_program', $data);
        if($update){
            $this->session->set_flashdata("pesan_success", "buletin program deleted successfully");
            redirect('management_claim/buletin_program');
        }else{
            $this->session->set_flashdata("pesan", "buletin program updated failed");
            redirect('management_claim/buletin_program');
        }
    }

    public function edit_buletin($signature){

        if ($this->session->userdata('username') != 'ismi') {
            $this->session->set_flashdata("pesan", "you are not allowed to edit buletin program");
            redirect('management_claim/buletin_program');
            die;
        }

        $get_data = $this->model_management_claim->get_buletin_program($signature);
        if($get_data->num_rows() > 0){
            $supp = $get_data->row()->supp;
            $bulan = $get_data->row()->bulan;
            $tahun = $get_data->row()->tahun;
            $month = $tahun.'-'.$bulan;
            $keterangan = $get_data->row()->keterangan;
            $file = $get_data->row()->file;
            $signature = $get_data->row()->signature;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/buletin_program');
        }

        $data = [
            'title'   => 'management claim | edit buletin program',
            'get_data'=> $get_data,
            'url'     => 'management_claim/buletin_program_update',
            'supp'    => $supp,
            'month'   => $month,
            'file'    => $file,
            'keterangan' => $keterangan,
            'signature' => $signature
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);

        $this->load->view('management_kpi/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/edit_buletin_program', $data);
        $this->load->view('kalimantan/footer');
    }

    public function buletin_program_update(){
        $supp       = $this->input->post('supp');
        $periode    = $this->input->post('periode');
        $keterangan = $this->input->post('keterangan');
        $old_attachment = $this->input->post('old_attachment');
        $signature  = $this->input->post('signature');

        // echo "signature : ".$signature;
        // die;

        $params_tahun = substr($periode, 0, 4);
        $params_bulan = substr($periode, 5, 2);

        $created_at     = $this->model_outlet_transaksi->timezone();

        if (!is_dir('./assets/uploads/management_claim/buletin_program/')) {
            @mkdir('./assets/uploads/management_claim/buletin_program/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/buletin_program/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attachment')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename = $old_attachment;
        };

        $data = [
            'supp'          => $supp,
            'tahun'         => $params_tahun,
            'bulan'         => $params_bulan,
            'keterangan'    => $keterangan,
            'file'          => $filename,
            'signature'     => $signature,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id'),
        ];
        $this->db->where('signature', $signature);
        $proses = $this->db->update('management_claim.buletin_program', $data);
        if ($proses) {
            $this->session->set_flashdata("pesan_success", "buletin program created successfully");
            redirect('management_claim/buletin_program');
        }
    }

    public function tracking_download_buletin($signature){

        $get_buletin_program = $this->model_management_claim->get_buletin_program($signature);
        if ($get_buletin_program->num_rows() > 0) {
            $id_buletin   = $get_buletin_program->row()->id;
            $filename     = $get_buletin_program->row()->file;  
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/buletin_program');
            die;
        }

        // die;

        $data = [
            'id_buletin'    => $id_buletin,
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $this->session->userdata('id'),
        ];

        $this->db->insert('management_claim.log_buletin_program', $data);
        redirect('./assets/uploads/management_claim/buletin_program/'.$filename);
    }

    public function registrasi_program_mti(){
        $data = [
            'title'                 => 'registrasi program MPI',
            'title2'                => 'import program MTI',
            'title3'                => 'upload SKP & Trading Term',
            'get_registrasi_program_mti'=> $this->model_management_claim->get_registrasi_program_mti(),
            'url'                   => 'management_claim/registrasi_program_mti_save',
            'url_import'            => 'management_claim/registrasi_program_mti_import',
            'url_file'              => 'management_claim/registrasi_program_mti_import_file',
            'url_file_trading_term' => 'management_claim/registrasi_program_mti_import_file_trading_term',
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('management_claim/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/registrasi_program_mti', $data);
        $this->load->view('kalimantan/footer');
    }

  public function master_data()
  {
    if ($this->session->userdata('level') != '10') 
    {
        $this->session->set_flashdata("pesan", "you are not an admin");
        redirect('management_claim/ajuan_claim');
    }

    $data = [
        'title'                     => 'Master Data',           
        'url_mapping_struktural'    => 'management_claim/mapping_struktural_save',
        'url_master_region'         => 'management_claim/master_region_save',
        'url_master_template'       => 'management_claim/master_template_save',
        'url_master_kategori'       => 'management_claim/master_kategori_save',
        'url_master_segment'       => 'management_claim/master_segment_save',
        'get_master_region'         => $this->model_management_claim->get_master_region(),
        'get_principal'             => $this->model_management_claim->get_principal(),
        // 'get_mapping_struktur_approval'   => $this->model_management_claim->get_mapping_struktur_approval(),
        'master_template'           => $this->model_management_claim->get_master_template(),
        'master_kategori'           => $this->model_management_claim->get_master_kategori(),
        'master_segment'            => $this->model_management_claim->get_master_segment(),
    ];

    // $this->view($data, false, "master_data");
    $this->render('management_claim/master_data', $data);
  }

    public function master_template_save()
    {
        $supp = $this->input->post('supp');
        $segment = $this->input->post('segment');
        $kategori = $this->input->post('kategori');
        $nama_template = $this->input->post('nama_template');
        $file_template = $this->input->post('file_template');
        $signature = "template-".md5($supp.$segment.$kategori.$nama_template)."-".$this->created_by."-".rand(1000, 9999);

        // echo "supp : ".$supp.'<br>';
        // echo "segment : ".$segment.'<br>';
        // echo "kategori : ".$kategori.'<br>';
        // echo "nama_template : ".$nama_template;


        if (!is_dir('./assets/uploads/management_claim/'.$this->tahun_folder.'/template')) {
            @mkdir('./assets/uploads/management_claim/'.$this->tahun_folder.'/template', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/'.$this->tahun_folder.'/template';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $config['file_name'] = $this->tahun_folder."-".rand(1000, 9999)."-".$this->created_by."-".$nama_template;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file_template')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            redirect('management_claim/master_data#master-template');
            die;
        };

        $data = [
            'supp'          => $supp,
            'segment'       => $segment,
            'id_kategori'   => $kategori,
            'nama_template' => $nama_template,
            'filename'      => $filename,
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
            'signature'     => $signature
        ];

        $this->model_management_claim->insert_master_template($data);
        $this->session->set_flashdata("pesan_success_template", "insert template successfully");
        redirect('management_claim/master_data#master-template');
    }
    public function master_kategori_save()
    {
        $nama_kategori = $this->input->post('nama_kategori');
        $signature = 'master_kategori-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $cek_existing = $this->model_management_claim->get_master_kategori_by_nama_kategori($nama_kategori);
        if ($cek_existing->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "insert kategori gagal. data already exist");
            redirect('management_claim/master_data#master-kategori');
            die;
        }

        $data = [
            'nama_kategori' => $nama_kategori,
            'signature'     => $signature,
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
        ];

        $this->model_management_claim->insert_master_kategori($data);
        $this->session->set_flashdata("pesan_success_kategori", "insert kategori successfully");
        redirect('management_claim/master_data#master-kategori');

    }

    public function master_segment_save()
    {
        $supp = $this->input->post('supp');
        $segment = $this->input->post('segment');
        $signature = 'master_segment-' . rand() . md5($this->created_at) . date('Ymd');

        $cek_existing = $this->model_management_claim->get_master_segment_by_supp_segment($supp, $segment);
        if ($cek_existing->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "insert segment gagal. data already exist");
            redirect('management_claim/master_data#master-segment');
            die;
        }

        $data = [
            'supp'          => $supp,
            'nama_segment'  => $segment,
            'signature'     => $signature,
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
        ];

        $this->model_management_claim->insert_master_segment($data);
        $this->session->set_flashdata("pesan_success", "insert segment successfully");
        redirect('management_claim/master_data#master-segment');

    }

    public function mapping_struktural_save(){
        $userid_approval = $this->input->post('userid_approval');
        $userid_head = $this->input->post('userid_head');
        $created_at = $this->model_outlet_transaksi->timezone();

        // echo "userid_approval : " . $userid_approval . "<br>";
        // echo "userid_head : " . $userid_head . "<br>";

        // die;

        $signature = 'mapping_approval-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $cek_existing = $this->model_management_claim->get_mapping_stuktural_by_userid($userid_approval);
        if ($cek_existing->num_rows() > 0) {
            // update
            $data = [
                'userid_head'   => $userid_head,
                'updated_at'    => $created_at,
                'updated_by'    => $this->session->userdata('id'),
            ];
            $this->db->where('userid', $userid_approval);
            $this->db->update('management_claim.mapping_struktur_approval', $data);
            $this->session->set_flashdata("pesan_success_mapping_struktural", "update successfully");
            redirect('management_claim/master_data/#mapping_struktural', 'refresh');
        }else{
            // insert
            $data = [
                'userid'        => $userid_approval,
                'userid_head'   => $userid_head,
                'created_at'    => $this->model_outlet_transaksi->timezone(),
                'created_by'    => $this->session->userdata('id'),
                'updated_at'    => $created_at,
                'updated_by'    => $this->session->userdata('id'),
                'signature'     => $signature
            ];

            $this->db->insert('management_claim.mapping_struktur_approval', $data);
            $this->session->set_flashdata("pesan_success_mapping_struktural", "insert successfully");
            redirect('management_claim/master_data/#mapping_struktural', 'refresh');
        }
        
    }

    public function delete_mapping_approval($signature){

        $get_mapping_approval_by_signature = $this->model_management_claim->get_mapping_approval_by_signature($signature);
        if (!$get_mapping_approval_by_signature->num_rows() > 0) {
             $this->session->set_flashdata("pesan", "Delete Gagal. Data Not Found");
            redirect('management_claim/master_data/#mapping_approval', 'refresh');
            die;
        }

        $data = [
            'deleted_at'        => $this->model_outlet_transaksi->timezone(),
            'deleted_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.mapping_struktur_approval', $data);
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('management_claim/master_data/#mapping_approval', 'refresh');
    }

    public function master_region_save()
    {
        $site_code = $this->input->post('site_code');
        $supp = $this->input->post('supp');
        $segment = $this->input->post('segment');
        $pic_mpm = $this->input->post('pic_mpm');
        $pic_principal_1 = $this->input->post('pic_principal_1');
        $pic_principal_2 = $this->input->post('pic_principal_2');

        // echo "site_code : ".$site_code;
        // echo "supp : ".$supp;
        // echo "segment : ".$segment;
        // echo "pic_mpm : ".$pic_mpm;
        // echo "pic_principal_1 : ".$pic_principal_1;
        // echo "pic_principal_2 : ".$pic_principal_2;

        // echo $this->created_at;

        // die;
        $signature = 'master-region-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $cek_existing = $this->model_management_claim->get_master_region_by_site_code_supp_segment_pic_principal($site_code, $supp, $segment, $pic_principal_1, $pic_principal_2);
        if ($cek_existing->num_rows() > 0) {

            $id = $cek_existing->row()->id;
            // lakukan update
            $data = [
                'site_code'     => $site_code,
                'supp'          => $supp,
                'segment'       => $segment,
                'pic_mpm'       => $pic_mpm,
                'pic_principal_1' => $pic_principal_1,
                'pic_principal_2' => $pic_principal_2,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
            ];

            $this->model_management_claim->update_master_region($data, $id);
            $this->session->set_flashdata("pesan_success", "update successfully");

            // die;
            redirect('management_claim/master_data/#master_region', 'refresh');

        }else{
            // lakukan insert
            $data = [
                'site_code'     => $site_code,
                'supp'          => $supp,
                'segment'       => $segment,
                'pic_mpm'       => $pic_mpm,
                'pic_principal_1' => $pic_principal_1,
                'pic_principal_2' => $pic_principal_2,
                'created_at'    => $this->created_at,
                'created_by'    => $this->created_by,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
                'signature'     => $signature
            ];

            $this->model_management_claim->insert_master_region($data);
            $this->session->set_flashdata("pesan_success", "insert successfully");
            redirect('management_claim/master_data/#master_region', 'refresh');
        }

        
    }

    public function edit_registrasi_program_mti($signature){

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature);

        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program = $get_registrasi_program_mti->row()->id;
            $supp = $get_registrasi_program_mti->row()->supp;
            $nomor_surat  = $get_registrasi_program_mti->row()->nomor_surat;
            $userid_kam  = $get_registrasi_program_mti->row()->userid_kam;
            $account  = $get_registrasi_program_mti->row()->account;
            $area  = $get_registrasi_program_mti->row()->area;
            $brand  = $get_registrasi_program_mti->row()->brand;
            $item  = $get_registrasi_program_mti->row()->item;
            $mekanisme  = $get_registrasi_program_mti->row()->mekanisme;
            $expose  = $get_registrasi_program_mti->row()->expose;
            $from  = $get_registrasi_program_mti->row()->from;
            $to  = $get_registrasi_program_mti->row()->to;
            $file_skp  = $get_registrasi_program_mti->row()->file_skp;
            $file_trading_term  = $get_registrasi_program_mti->row()->file_trading_term;
            $name = $get_registrasi_program_mti->row()->name;

        }else{
            $this->session->set_flashdata("pesan", "Data anda tidak ditemukan");
            redirect('management_claim/registrasi_program_mti');
        }

        $data = [
            'title'                 => 'registrasi program MTI',
            'title2'                => 'import program MTI',
            'title3'                => 'upload SKP & Trading Term',
            'get_registrasi_program_mti'=> $get_registrasi_program_mti,
            'url'                   => 'management_claim/registrasi_program_mti_update',
            'supp'                  => $supp,
            'nomor_surat'           => $nomor_surat,
            'userid_kam'            => $userid_kam,
            'account'               => $account,
            'area'                  => $area,
            'brand'                 => $brand,
            'item'                  => $item,
            'mekanisme'             => $mekanisme,
            'expose'                => $expose,
            'from'                  => $from,
            'to'                    => $to,
            'file_skp'              => $file_skp,
            'file_trading_term'     => $file_trading_term,
            'name'                  => $name,
            'signature_program'     => $signature,
            'id_program'            => $id_program
        ];  

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('management_claim/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/edit_registrasi_program_mti', $data);
        $this->load->view('kalimantan/footer');
    }

    public function registrasi_program_mti_update(){
        $id_program = $this->input->post('id_program');
        $signature_program = $this->input->post('signature_program');
        $supp = $this->input->post('supp');
        $nomor_surat  = $this->input->post('nomor_surat');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $userid_kam  = $this->input->post('userid_kam');
        $account = $this->input->post('account');
        $area = $this->input->post('area');
        $brand = $this->input->post('brand');
        $item = $this->input->post('item');
        $mekanisme = $this->input->post('mekanisme');
        $expose = $this->input->post('expose');

        $data = [
            'supp'                  => $this->input->post('supp'),
            'nomor_surat'           => $this->input->post('nomor_surat'),
            'userid_kam'            => $this->input->post('userid_kam'),
            'account'               => $this->input->post('account'),
            'area'                  => $this->input->post('area'),
            'brand'                 => $this->input->post('brand'),
            'item'                  => $this->input->post('item'),
            'mekanisme'             => $this->input->post('mekanisme'),
            'expose'                => $this->input->post('expose'),
            'from'                  => $this->input->post('from'),
            'to'                    => $this->input->post('to'),
            'updated_at'            => $this->model_outlet_transaksi->timezone(),
            'updated_by'            => $this->session->userdata('id'),
        ];

        $this->db->where('id', $id_program);
        $this->db->update('management_claim.registrasi_program_mti', $data);
        $this->session->set_flashdata("pesan_success", "Update registrasi program successfully");
        redirect('management_claim/edit_registrasi_program_mti/'.$signature_program, 'refresh');
    }

    public function registrasi_program_mti_import(){
        $signature = 'importmti-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');
        
        // cek traffic
        $traffic = $this->model_management_claim->get_traffic_import();
        if ($traffic->num_rows() > 0) {

            $status_traffic = $traffic->row()->status_import;
            if ($status_traffic == 1) {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_claim/registrasi_program_mti');
                die;
            }else{
                $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->session->userdata('id'), 1);
            }

        }else{
            $insert_traffic = $this->model_management_claim->insert_traffic_import('', $this->session->userdata('id'), 1);
        }
        // die;

        $this->load->model(array('model_management_inventory', 'model_master_data'));

        if (!is_dir('./assets/uploads/management_claim/import/')) {
            @mkdir('./assets/uploads/management_claim/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/import/';
        // $config['allowed_types'] = 'xls|xlsx';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_claim/import/$file_name");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_claim/registrasi_program_mti','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 100) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 100 ROW.");
                    redirect('management_claim/form_ajuan_claim/'.$signature_program);
                }

                for ($row = 2; $row <= $highestRow; $row++) {                          
                    $principal_code   = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nomor_surat      = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $userid_kam       = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $account          = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $area             = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $brand            = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $item             = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mekanisme        = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $expose           = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $from             = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    
                    $unix_date        = ($from - 25569) * 86400;
                    $excel_date       = 25569 + ($unix_date / 86400);
                    $unix_date        = ($excel_date - 25569) * 86400;
                    $from_final       = gmdate("Y-m-d", $unix_date);
                    
                    $to               = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $unix_date        = ($to - 25569) * 86400;
                    $excel_date       = 25569 + ($unix_date / 86400);
                    $unix_date        = ($excel_date - 25569) * 86400;
                    $to_final         = gmdate("Y-m-d", $unix_date);

                    $get_userid_kam = $this->model_management_claim->get_master_user_kam($userid_kam, $principal_code);
                    if ($get_userid_kam->num_rows() > 0) {
                        $validasi_userid_kam = 1;  
                    }else{
                        $validasi_userid_kam = 0;
                    }

                    // validasi brand
                    $get_master_brand = $this->model_management_claim->get_master_brand($brand);
                    if ($get_master_brand->num_rows() > 0) {
                        $validasi_brand = 1;  
                    }else{
                        $validasi_brand = 0;
                    }

                    // validasi account
                    $get_master_account = $this->model_management_claim->get_master_account($account);
                    if ($get_master_account->num_rows() > 0) { // jika ditemukan maka lanjut cek mapping
                        $id_account = $get_master_account->row()->id;
                        $get_mapping_account = $this->model_management_claim->get_mapping_account_by_user_dan_account($userid_kam, $id_account);
                        if ($get_mapping_account->num_rows() > 0) { // jika ditemukan maka validasi OK atau 1
                            $validasi_account = 1;
                        }else{
                            $validasi_account = 0;
                        }
                    }else{
                        $validasi_account = 0;
                    }

                    // die;

                    $validasi_row = $validasi_userid_kam + $validasi_brand + $validasi_account;

                    $data = [
                        'supp'                     => $principal_code,
                        'nomor_surat'              => $nomor_surat,
                        'userid_kam'               => $userid_kam,
                        'account'                  => $account,
                        'area'                     => $area,
                        'brand'                    => $brand,
                        'item'                     => $item,
                        'mekanisme'                => $mekanisme,
                        'expose'                   => $expose,
                        'from'                     => $from_final,
                        'to'                       => $to_final,
                        'validasi_userid_kam'      => $validasi_userid_kam,
                        'validasi_brand'           => $validasi_brand,
                        'validasi_account'         => $validasi_account,
                        'validasi_row'             => $validasi_row,
                        'created_at'               => $created_at,
                        'created_by'               => $this->session->userdata('id'),
                        'signature'                => $signature
                    ];
                    $this->db->insert('management_claim.temp_import_registrasi_kam_mti',$data);
                }
            }

            $insert_traffic = $this->model_management_claim->insert_traffic_import($site_code_header, $this->session->userdata('id'), 0);
            $this->session->set_flashdata("pesan_success", "Anda masuk ke halaman Preview. Cek kembali data anda. Lalu Klik Submit Data");
            redirect('management_claim/preview_import_registrasi_program_mti/'.$signature);

        }else{

            $this->session->set_flashdata("pesan", "Import Gagal :".$this->upload->display_errors());
            redirect('management_claim/registrasi_program_mti/'.$signature);
        };
    }

    public function preview_import_registrasi_program_mti($signature){

        // cek apakah masih ada  data yang failed
        $cek_validasi_temp_registrasi_mti = $this->model_management_claim->cek_validasi_temp_registrasi_mti($signature);
        if ($cek_validasi_temp_registrasi_mti->num_rows() > 0) {
            $flag_invalid = 1;
        }else{
            $flag_invalid = 0;
        }

        $data = [
            'title'                                 => 'management claim | preview import registrasi program mti',
            'get_import_registrasi_program_mti'     => $this->model_management_claim->get_import_registrasi_program_mti($signature),
            'url'                                   => 'management_claim/submit_import_registrasi_program_mti',
            'url_import'                            => 'management_claim/registrasi_program_mti_import',
            'signature'                             => $signature,
            'flag_invalid'                          => $flag_invalid
        ];
        $this->navbar($data);
        $this->load->view('management_claim/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/preview_import_registrasi_program_mti', $data);
        $this->load->view('kalimantan/footer');
    }

    public function submit_import_registrasi_program_mti(){
        $signature = $this->input->post('signature');
        $get_import_registrasi_program_mti = $this->model_management_claim->get_import_registrasi_program_mti($signature);
        if ($get_import_registrasi_program_mti->num_rows() > 0) {
            foreach ($get_import_registrasi_program_mti->result() as $a) {
                
                $signature_single = 'reg-mti-import-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');
                $data = [
                    'supp'                     => $a->supp,
                    'nomor_surat'              => $a->nomor_surat,
                    'userid_kam'               => $a->userid_kam,
                    'account'                  => $a->account,
                    'area'                     => $a->area,
                    'brand'                    => $a->brand,
                    'item'                     => $a->item,
                    'mekanisme'                => $a->mekanisme,
                    'expose'                   => $a->expose,
                    'from'                     => $a->from,
                    'to'                       => $a->to,
                    'created_at'               => $a->created_at,
                    'created_by'               => $a->created_by,
                    'signature'                => $signature_single
                ];
                $this->db->insert('management_claim.registrasi_program_mti',$data);
            }
        }

        $this->session->set_flashdata("pesan_success", "Import Program MPI Berhasil");
        redirect('management_claim/registrasi_program_mti', 'refresh');

    }

    public function registrasi_program_mti_import_file(){
        $id = $this->input->post('options');

        if (!is_dir('./assets/uploads/management_claim/mti/')) {
            @mkdir('./assets/uploads/management_claim/mti/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/mti/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('skp')) 
        {
            $upload_data = $this->upload->data();
            $filename_skp = $upload_data['file_name'];

        }else{
            $filename_skp = '';
        };

        if ($this->upload->do_upload('trading_term')) 
        {
            $upload_data = $this->upload->data();
            $filename_trading_term = $upload_data['file_name'];

        }else{
            $filename_trading_term = '';
        };
        
        if(!$id){
            $this->session->set_flashdata("pesan", "Update SKP & Trading Term Gagal. Anda belum checklist data satupun");
            redirect('management_claim/registrasi_program_mti', 'refresh');
        }

        $count = count($id);
        for ($i=0; $i < $count ; $i++) 
        { 
            if (!empty($filename_skp) && !empty($filename_trading_term)) {
                $data = [
                    'file_skp'          => $filename_skp,
                    'file_trading_term' => $filename_trading_term,
                    'updated_at'        => $this->model_outlet_transaksi->timezone(),
                    'updated_by'        => $this->session->userdata('id')
                ];
            }elseif (!empty($filename_skp) && empty($filename_trading_term)) {
                $data = [
                    'file_skp'          => $filename_skp,
                    'updated_at'        => $this->model_outlet_transaksi->timezone(),
                    'updated_by'        => $this->session->userdata('id')
                ];
            }elseif(empty($filename_skp) && !empty($filename_trading_term)){
                $data = [
                    'file_trading_term' => $filename_trading_term,
                    'updated_at'        => $this->model_outlet_transaksi->timezone(),
                    'updated_by'        => $this->session->userdata('id')
                ];
            }else{
                $this->session->set_flashdata("pesan", "Update SKP & Trading Term Gagal. Anda belum mengupload data apapun");
                redirect('management_claim/registrasi_program_mti', 'refresh');
            } 
            $this->db->where('id', $id[$i]);
            $this->db->update('management_claim.registrasi_program_mti', $data);
        }

        $this->session->set_flashdata("pesan_success", "Update SKP Berhasil");
        redirect('management_claim/registrasi_program_mti', 'refresh');
        
    }

    public function registrasi_program_mti_import_file_trading_term(){
        $id = $this->input->post('options');

        if (!is_dir('./assets/uploads/management_claim/mti/')) {
            @mkdir('./assets/uploads/management_claim/mti/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/mti/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('trading_term')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];

        }else{
            $this->session->set_flashdata("pesan", "Import Gagal :".$this->upload->display_errors());
            redirect('management_claim/registrasi_program_mti', 'refresh');
            die;
        };
        
        if(!$id){
            $this->session->set_flashdata("pesan", "Update Trading Term Gagal. Anda belum checklist data satupun");
            redirect('management_claim/registrasi_program_mti', 'refresh');
        }

        $count = count($id);
        for ($i=0; $i < $count ; $i++) { 
            $data = [
                'file_trading_term' => $filename,
                'updated_at'        => $this->model_outlet_transaksi->timezone(),
                'updated_by'        => $this->session->userdata('id')
            ];
            $this->db->where('id', $id[$i]);
            $this->db->update('management_claim.registrasi_program_mti', $data);
        }

        $this->session->set_flashdata("pesan_success", "Update Trading Term Berhasil");
        redirect('management_claim/registrasi_program_mti', 'refresh');
    }

    public function delete_registrasi_program_mti($signature){

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature);
        if (!$get_registrasi_program_mti->num_rows() > 0) {
             $this->session->set_flashdata("pesan", "Delete Gagal. Data Not Found");
            redirect('management_claim/registrasi_program_mti', 'refresh');
            die;
        }

        $data = [
            'deleted_at'        => $this->model_outlet_transaksi->timezone(),
            'deleted_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.registrasi_program_mti', $data);
        $this->session->set_flashdata("pesan_success", "Delete Program MTI Berhasil");
        redirect('management_claim/registrasi_program_mti', 'refresh'); 
    }

    public function export_template_registrasi_mti(){

        $query = "
            select '' as principal_code, '' as nomor_surat, '' as userid_kam, '' as account, '' as area, '' as brand, '' as item, '' as mekanisme, '' as expose, '' as 'from', '' as 'to'
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'principal_code', 'nomor_surat', 'userid_kam', 'account', 'area', 'brand', 'item', 'mekanisme', 'expose', 'from(m/d/y)', 'to(m/d/y)'
        ));
        $this->excel_generator->set_column(array
        ( 
            'principal_code', 'nomor_surat', 'userid_kam', 'account', 'area', 'brand', 'item', 'mekanisme', 'expose', 'from', 'to'
        ));
        $this->excel_generator->set_width(array(10,15,15,15,15,15,15,15,15,15,15)); 
        $this->excel_generator->exportTo2007('Template Claim khusus Registrasi Program MTI'); 
    }

    public function registrasi_program_mti_save(){
        $supp = $this->input->post('supp');
        $nomor_surat = $this->input->post('nomor_surat');
        $userid_kam = $this->input->post('userid_kam');
        $account = $this->input->post('account');
        $area = $this->input->post('area');
        $brand = $this->input->post('brand');
        $item = $this->input->post('item');
        $mekanisme = $this->input->post('mekanisme');
        $expose = $this->input->post('expose');
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        $nama_account = $this->model_management_claim->get_master_account_by_id($account)->row()->account;

        // echo "supp : ".$supp."<br>";
        // echo "nomor_surat : ".$nomor_surat."<br>";
        // echo "userid_kam : ".$userid_kam."<br>";
        // echo "account : ".$account."<br>";
        // echo "area : ".$area."<br>";
        // echo "brand : ".$brand."<br>";
        // echo "item : ".$item."<br>";
        // echo "mekanisme : ".$mekanisme."<br>";
        // echo "expose : ".$expose."<br>";
        // echo "from : ".$from."<br>";
        // echo "to : ".$to."<br>";
        // die;

        $signature = 'mticlaim-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'supp'          => $supp,
            'nomor_surat'   => $nomor_surat,
            'userid_kam'    => $userid_kam,
            'account'       => $nama_account,
            'area'          => $area,
            'brand'         => $brand,
            'item'          => $item,
            'mekanisme'     => $mekanisme,
            'expose'        => $expose,
            'from'          => $from,
            'to'            => $to,
            'signature'     => $signature,
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $this->session->userdata('id'),
        ];

        $this->db->insert('management_claim.registrasi_program_mti', $data);
        $this->session->set_flashdata("pesan_success", "Tambah Program Berhasil");
        redirect('management_claim/registrasi_program_mti', 'refresh');

    }

    public function ajuan_claim_mti(){

        if($this->input->get('from') || $this->input->get('to') || $this->input->get('supp') || $this->input->get('status')){
            
            $advanced['from']   = $this->input->get('from');
            $advanced['to']     = $this->input->get('to');
            $advanced['supp']   = $this->input->get('supp');
            $advanced['status']   = $this->input->get('status');
        
        }else{
            $advanced['from']   = '';
            $advanced['to']     = '';
            $advanced['supp']   = 'all';
            $advanced['status']   = 'all';
        }

        // var_dump($advanced);
        // die;

        $data = [
            'title'     => 'management claim | ajuan claim MPI',
            'url'       => '',
            'get_data'  => $this->model_management_claim->get_registrasi_join_ajuan_mti($advanced)
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/ajuan_claim_mti', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_user_kam()
    {

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/master_user_kam?token=11f3a8a682c1e8d097ae60d72ecf07c7&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih User -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["userid_kam"] ."' name='" . $tiapbranch["name"] . "' >";
                echo $tiapbranch["name"] . " ___ " . $tiapbranch["email"]. " ___ " . $tiapbranch["userid_kam"];
                echo "</option>";
            }
        }
    }

    public function master_site(){

        $curl = curl_init();

        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = getenv('USER_ID');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key,
            'tahun'     => 2025,
            'userid'    => $userid
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/site_code?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            // var_dump($datas);die;
            echo "<option value=''> subbranch ? </option>";

            foreach ($datas as $key => $data)
            {
                echo "<option value='". $data["site_code"] ."' name='" . $data["site_code"] . "' >";
                echo $data["branch_name"].' - '.$data["nama_comp"].' - '.$data["site_code"];
                echo "</option>";
            }
        }
    }

    public function master_user_principal()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key,
            'kode_company' => '001'
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_user_mpm?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $result = $array_response['data'];
            echo "<option value=''> user ? </option>";

            foreach ($result as $key => $r)
            {
                echo "<option value='". $r["id"] . "' >";
                echo $r["username"] . " - " . $r["email"];
                echo "</option>";
            }
        }
    }

    public function master_user_mpm()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        // kode_company di comment karena API ini digunakan juga di Master Karyawan untuk RPD
        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key
            // 'kode_company' => "000"
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_user_mpm?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } 
        else 
        {
            $array_response = json_decode($response, true);
            $result = $array_response['data'];
            echo "<option value=''> user ? </option>";

            foreach ($result as $key => $r)
            {
                echo "<option value='". $r["id"] . "' >";
                echo $r["username"] . " - " . $r["email"];
                echo "</option>";
            }
        }
    }

    public function master_account_mti()
    {

        $curl = curl_init();
        // $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_account_mti?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Account -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["id"] ."' id='" . $tiapbranch["id"] . "' >";
                echo $tiapbranch["account"];
                echo "</option>";
            }
        }
    }

    public function master_brand_mti(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/master_brand_mti?token=11f3a8a682c1e8d097ae60d72ecf07c7&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Brand -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["brand"] ."' brand='" . $tiapbranch["brand"] . "' >";
                echo $tiapbranch["brand"];
                echo "</option>";
            }
        }
    }

    public function master_kam_single(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_user_kam_single?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $data = $array_response['data'];
            // var_dump($data);die;
            echo "<option value=''> -- Pilih Kam -- </option>";

            foreach ($data as $key => $single)
            {
                echo "<option value='". $single["userid_kam"] ."' name='" . $single["name"] . "' >";
                echo $single["name"] . " ___ " . $single["email"]. " ___ " . $single["userid_kam"];
                echo "</option>";
            }
        }
    }

    public function master_kam_by_supp(){

        $curl = curl_init();

        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key,
            'supp' => $this->input->post('supp')
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_user_kam?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih User -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["userid_kam"] ."' name='" . $tiapbranch["name"] . "' >";
                echo $tiapbranch["name"] . " ___ " . $tiapbranch["email"]. " ___ " . $tiapbranch["userid_kam"];
                echo "</option>";
            }
        }
    }

    public function master_kategori()
    {

        // echo "ini master kategori";
        $curl = curl_init();

        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = getenv('USER_ID');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/kategori_claim?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
        echo "cURL Error #:" . $err;

        } else {

            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            echo "<option value=''> kategori ? </option>";
            echo "<option value='all'> all </option>";
            foreach ($datas as $key => $data)
            {
                echo "<option value='". $data["id"] ."' name='" . $data["nama_kategori"] . "' >";
                echo $data["nama_kategori"];
                echo "</option>";
            }
        }
    }

    public function mapping_account_kam(){

        $curl = curl_init();
        // $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $userid = $this->input->post('userid');

        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key,
            'userid' => $this->input->post('userid')
        );

        // $userid = 297;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/mapping_account_kam?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $data = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih User -- </option>";

            foreach ($data as $key => $single)
            {
                echo "<option value='". $single["account_id"] ."' name='" . $single["account"] . "' >";
                echo $single["account"];
                echo "</option>";
            }
        }
    }

    public function routing_mti($signature_program = '', $signature_ajuan = ''){
        $get_ajuan_claim_mti = $this->model_management_claim->get_ajuan_claim_mti($signature_ajuan);
        if ($get_ajuan_claim_mti->num_rows() > 0) {
            $status = $get_ajuan_claim_mti->row()->status;
        }else{
            $status = 0;
        }

        // echo "status : ".$status;
        // die;

        if ($status == 3) { // pending head of mti
            if ($this->session->userdata('level') == 5) { // jika yg login adalah DP
                redirect('management_claim/form_ajuan_claim_mti/'.$signature_program);
            }else{                
                if ($signature_ajuan) {
                    redirect('management_claim/verifikasi_head_kam_mti/'.$signature_program.'/'.$signature_ajuan);
                }else{
                    $this->session->set_flashdata("pesan", "belum ada pengajuan dari dp");
                    redirect('management_claim/ajuan_claim_mti/');
                }
            }
        }else if ($status == 5) { // pending finance
            if ($this->session->userdata('level') == 5) { // jika yg login adalah DP
                redirect('management_claim/form_ajuan_claim_mti/'.$signature_program);
            }else{                
                if ($signature_ajuan) {
                    redirect('management_claim/verifikasi_mti_finance/'.$signature_program.'/'.$signature_ajuan);
                }else{
                    $this->session->set_flashdata("pesan", "belum ada pengajuan dari dp");
                    redirect('management_claim/ajuan_claim_mti/');
                }
            }
        }else{
            if ($this->session->userdata('level') == 5) { // jika yg login adalah DP
                redirect('management_claim/form_ajuan_claim_mti/'.$signature_program);
            }else{                
                if ($signature_ajuan) {
                    redirect('management_claim/verifikasi_mpm_mti/'.$signature_program.'/'.$signature_ajuan);
                }else{
                    $this->session->set_flashdata("pesan", "belum ada pengajuan dari dp");
                    redirect('management_claim/ajuan_claim_mti/');
                }
            }
        }

        
    }

    public function routing_hardcopy_mti($signature_program, $signature_ajuan = ''){
        // cek status
        $get_ajuan_claim_mti = $this->model_management_claim->get_ajuan_claim_mti($signature_ajuan);
        if ($get_ajuan_claim_mti->num_rows() > 0) {
            $status_hardcopy = $get_ajuan_claim_mti->row()->status_hardcopy;
        }else{
            $status_hardcopy = 0;
        }      

        // echo "status_hardcopy : ".$status_hardcopy;
        // die;

        if ($signature_ajuan == null) {
            $this->session->set_flashdata("pesan", "belum ada pengajuan claim di program ini");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        if ($this->session->userdata('level') == 5) { // jika yg login adalah DP
            redirect('management_claim/hardcopy_mti/'.$signature_program.'/'.$signature_ajuan);
        }else{                
            if ($signature_ajuan) {
                redirect('management_claim/verifikasi_hardcopy_mti/'.$signature_program.'/'.$signature_ajuan);
            }else{
                $this->session->set_flashdata("pesan", "belum ada pengajuan dari dp");
                redirect('management_claim/ajuan_claim_mti/');
            }
        }
    }

    public function form_ajuan_claim_mti($signature_program = ''){
        // echo "signature_program : ".$signature_program;
        $this->load->model('model_management_inventory');

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        $get_ajuan_claim_mti_by_id_program_user = $this->model_management_claim->get_ajuan_claim_mti_by_id_program_user($id_program, $this->session->userdata('id'));
        if ($get_ajuan_claim_mti_by_id_program_user->num_rows() > 0) {
            $nomor_ajuan    = $get_ajuan_claim_mti_by_id_program_user->row()->nomor_ajuan;
            $branch_name    = $get_ajuan_claim_mti_by_id_program_user->row()->branch_name;
            $nama_comp      = $get_ajuan_claim_mti_by_id_program_user->row()->nama_comp;
            $site_code      = $get_ajuan_claim_mti_by_id_program_user->row()->site_code;
            $nama_pengirim  = $get_ajuan_claim_mti_by_id_program_user->row()->nama_pengirim;
            $email_pengirim = $get_ajuan_claim_mti_by_id_program_user->row()->email_pengirim;
            $site_code      = $get_ajuan_claim_mti_by_id_program_user->row()->site_code;
            $attach_1       = $get_ajuan_claim_mti_by_id_program_user->row()->attach_1;
            $attach_2       = $get_ajuan_claim_mti_by_id_program_user->row()->attach_2;
            $status         = $get_ajuan_claim_mti_by_id_program_user->row()->status;
            $nama_status    = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status;
            $verifikasi_by  = $get_ajuan_claim_mti_by_id_program_user->row()->verifikasi_by;
            $verifikasi_at  = $get_ajuan_claim_mti_by_id_program_user->row()->verifikasi_at;
            $verifikasi_note            = $get_ajuan_claim_mti_by_id_program_user->row()->verifikasi_note;
            $verifikasi_file            = $get_ajuan_claim_mti_by_id_program_user->row()->verifikasi_file;
            $tanggal_claim              = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_claim;
            $status_hardcopy            = $get_ajuan_claim_mti_by_id_program_user->row()->status_hardcopy;
            $nama_status_hardcopy       = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status_hardcopy;
            $file_hardcopy              = $get_ajuan_claim_mti_by_id_program_user->row()->file_hardcopy;
            $nomor_hardcopy             = $get_ajuan_claim_mti_by_id_program_user->row()->nomor_hardcopy;
            $nama_pengirim_hardcopy     = $get_ajuan_claim_mti_by_id_program_user->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy    = $get_ajuan_claim_mti_by_id_program_user->row()->email_pengirim_hardcopy;
            $update_kirim_hardcopy_at   = $get_ajuan_claim_mti_by_id_program_user->row()->update_kirim_hardcopy_at;
            $tanggal_terima_hardcopy    = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_terima_hardcopy;
            $terima_hardcopy_by         = $get_ajuan_claim_mti_by_id_program_user->row()->terima_hardcopy_by;
            $update_terima_hardcopy_at  = $get_ajuan_claim_mti_by_id_program_user->row()->update_terima_hardcopy_at;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti_by_id_program_user->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanda_terima_hardcopy_ke_principal_by = $get_ajuan_claim_mti_by_id_program_user->row()->tanda_terima_hardcopy_ke_principal_by;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti_by_id_program_user->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $signature_ajuan        = $get_ajuan_claim_mti_by_id_program_user->row()->signature;
            $status_dp              = $get_ajuan_claim_mti_by_id_program_user->row()->status_dp;
            $nama_status_dp         = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status_dp;
            $nama_pengirim          = $get_ajuan_claim_mti_by_id_program_user->row()->nama_pengirim;
            $email_pengirim          = $get_ajuan_claim_mti_by_id_program_user->row()->email_pengirim;
            $tanggal_claim          = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_claim;
            $created_at          = $get_ajuan_claim_mti_by_id_program_user->row()->created_at;
            $attach_1          = $get_ajuan_claim_mti_by_id_program_user->row()->attach_1;
            $nama_status_hardcopy_dp = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status_hardcopy_dp;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_kirim_hardcopy;
            $id_verifikasi = $get_ajuan_claim_mti_by_id_program_user->row()->id_verifikasi;

        }else{
            $nomor_ajuan = '';
            $branch_name = '';
            $nama_comp   = '';
            $nama_pengirim = '';
            $email_pengirim = '';
            $site_code   = '';
            $attach_1    = '';
            $attach_2    = '';
            $status      = '';
            $nama_status = '';
            $verifikasi_by = '';
            $verifikasi_at = '';
            $verifikasi_note = '';
            $verifikasi_file = '';
            $tanggal_claim = '';
            $status_hardcopy = '';
            $nama_status_hardcopy = '';
            $file_hardcopy = '';
            $nomor_hardcopy = '';
            $nama_pengirim_hardcopy = '';
            $email_pengirim_hardcopy = '';
            $update_kirim_hardcopy_at = '';
            $tanggal_terima_hardcopy = '';
            $terima_hardcopy_by = '';
            $update_terima_hardcopy_at = '';
            $file_tanda_terima_hardcopy_ke_principal = '';
            $tanda_terima_hardcopy_ke_principal_by = '';
            $tanda_terima_hardcopy_ke_principal_nama = '';
            $tanggal_tanda_terima_hardcopy_ke_principal = '';
            $signature_ajuan = '';
            $status_dp = '';
            $nama_status_dp = '';
            $nama_pengirim = '';
            $email_pengirim = '';
            $tanggal_claim = '';
            $created_at = '';

            $nama_status_hardcopy_dp = '';
            $nama_pengirim_hardcopy = '';
            $email_pengirim_hardcopy = '';
            $tanggal_kirim_hardcopy = '';
            $file_hardcopy = '';
            $tanggal_terima_hardcopy = '';

            $id_verifikasi = '';
        }

        // echo "id_verifikasi : ".$id_verifikasi;
        // die;
        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        // echo "branch_name : ".$branch_name;
        // echo "nama_comp : ".$nama_comp;
        // echo "site_code : ".$site_code;
        // die;

        $data = [
            'title'             => 'Pengajuan Claim MPI',
            'url'               => 'management_claim/form_ajuan_claim_mti_proses',
            'nomor_ajuan'       => $nomor_ajuan,
            'account'           => $account,
            'area'              => $area,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'namasupp'          => $namasupp,
            'branch_name'       => $branch_name,
            'nama_comp'         => $nama_comp,
            'site_code_db'      => $site_code,
            'status'            => $status,
            'nama_status'       => $nama_status,
            'status_dp'         => $status_dp,
            'nama_status_dp'    => $nama_status_dp,
            'nomor_surat'       => $nomor_surat,
            'tanggal_claim'     => $tanggal_claim,
            'created_at'        => $created_at,
            'nama_pengirim'        => $nama_pengirim,
            'email_pengirim'        => $email_pengirim,
            'tanggal_claim'        => $tanggal_claim,
            'attach_1'        => $attach_1,
            'attach_2'        => $attach_2,
            
            'site_code'         => $this->model_management_inventory->get_sitecode(),
            'signature_program' => $signature_program,
            'id_program'        => $id_program,

            'nama_status_hardcopy_dp' => $nama_status_hardcopy_dp,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'file_hardcopy' => $file_hardcopy,
            'tanggal_terima_hardcopy' => $tanggal_terima_hardcopy,

            'status_verifikasi' => $status_verifikasi,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'attach_1_verifikasi' => $attach_1_verifikasi,
            'created_at_verifikasi' => $created_at_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('management_claim/accordion_mti_dp', $data);
        $this->load->view('management_claim/form_ajuan_claim_mti', $data);
        $this->load->view('kalimantan/footer');
    }

    public function form_ajuan_claim_mti_proses(){

        $nama_pengirim = $this->input->post('nama_pengirim');
        $email_pengirim = $this->input->post('email_pengirim');
        $signature_program = $this->input->post('signature_program');
        $id_program = $this->input->post('id_program');
        $site_code = $this->input->post('site_code');
        $branch_name = $this->input->post('branch_name');
        $nama_comp = $this->input->post('nama_comp');
        
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'mticlaim-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/management_claim/mti/')) {
            @mkdir('./assets/uploads/management_claim/mti/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/mti/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

         if ($this->upload->do_upload('file_skp')) 
        {
            $upload_data = $this->upload->data();
            $filename_skp = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        // echo "filename_skp : ".$filename_skp;
        // die;

        $get_ajuan_claim_mti_by_id_program_user = $this->model_management_claim->get_ajuan_claim_mti_by_id_program_user($id_program, $this->session->userdata('id'));

        if ($get_ajuan_claim_mti_by_id_program_user->num_rows() > 0) {
            $nomor_ajuan    = $get_ajuan_claim_mti_by_id_program_user->row()->nomor_ajuan;
            $status         = $get_ajuan_claim_mti_by_id_program_user->row()->status;
            $id_ajuan       = $get_ajuan_claim_mti_by_id_program_user->row()->id;
            $signature_ajuan_old = $get_ajuan_claim_mti_by_id_program_user->row()->signature;
            
            if ($status != 1) { // jika bukan status pending dp, maka dp tidak dapat update data
                $this->session->set_flashdata("pesan", "update data gagal di karenakan status ajuan claim anda bukan pending dp");
                redirect('management_claim/form_ajuan_claim_mti/'.$signature_program);
                die;
            }
            $data = [
                'nama_pengirim' => $nama_pengirim,
                'email_pengirim'=> $email_pengirim,
                'tanggal_claim' => $created_at,
                'attach_1'      => $filename,
                'attach_2'      => $filename_skp,
                'status'        => 2,
                'nama_status'   => 'PENDING KAM',
                'status_dp'     => 2,
                'nama_status_dp'=> 'ON PROCESS',
                'updated_at'    => $created_at,
                'updated_by'    => $this->session->userdata('id')
            ];

            $this->db->where('id', $id_ajuan);
            $update = $this->db->update("management_claim.ajuan_claim_mti", $data);
            redirect('management_claim/email_status_mti/'.$signature_program."/".$signature_ajuan_old);

        }else{
            $nomor_ajuan = $this->model_management_claim->generate_mti($this->input->post('from_site'), $created_at);
            // echo "nomor_ajuan : ".$nomor_ajuan;

            $data = [
                'nomor_ajuan'   => $nomor_ajuan,
                'site_code'     => $site_code,
                'branch_name'   => $branch_name,
                'nama_comp'     => $nama_comp,
                'nama_pengirim' => $nama_pengirim,
                'email_pengirim'=> $email_pengirim,
                'tanggal_claim' => $created_at,
                'attach_1'      => $filename,
                'attach_2'      => $filename_skp,
                'signature'     => $signature,
                'id_program'    => $id_program,
                'status'        => 2,
                'nama_status'   => 'PENDING KAM',
                'status_dp'     => 2,
                'nama_status_dp'=> 'ON PROCESS',
                'created_at'    => $created_at,
                'created_by'    => $this->session->userdata('id')
            ];

            $insert = $this->db->insert("management_claim.ajuan_claim_mti", $data);
            $this->session->set_flashdata("pesan_success", "pelaporan claim berhasil");
            // redirect('management_claim/form_ajuan_claim_mti/'.$signature_program);
            
            redirect('management_claim/email_status_mti/'.$signature_program."/".$signature);

        }
    }

    public function email_status_mti($signature_program, $signature){

        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
        }else{
            echo "tidak ada";
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_id_program_user = $this->model_management_claim->get_ajuan_claim_mti_by_id_program_user($id_program, $this->session->userdata('id'));
        if ($get_ajuan_claim_mti_by_id_program_user->num_rows() > 0) {
            $nomor_ajuan    = $get_ajuan_claim_mti_by_id_program_user->row()->nomor_ajuan;
            $branch_name    = $get_ajuan_claim_mti_by_id_program_user->row()->branch_name;
            $nama_comp      = $get_ajuan_claim_mti_by_id_program_user->row()->nama_comp;
            $site_code      = $get_ajuan_claim_mti_by_id_program_user->row()->site_code;
            $nama_pengirim  = $get_ajuan_claim_mti_by_id_program_user->row()->nama_pengirim;
            $email_pengirim = $get_ajuan_claim_mti_by_id_program_user->row()->email_pengirim;
            $site_code      = $get_ajuan_claim_mti_by_id_program_user->row()->site_code;
            $attach_1       = $get_ajuan_claim_mti_by_id_program_user->row()->attach_1;
            $attach_2       = $get_ajuan_claim_mti_by_id_program_user->row()->attach_2;
            $status         = $get_ajuan_claim_mti_by_id_program_user->row()->status;
            $nama_status    = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status;
            $verifikasi_by  = $get_ajuan_claim_mti_by_id_program_user->row()->verifikasi_by;
            $verifikasi_at  = $get_ajuan_claim_mti_by_id_program_user->row()->verifikasi_at;
            $verifikasi_note            = $get_ajuan_claim_mti_by_id_program_user->row()->verifikasi_note;
            $verifikasi_file            = $get_ajuan_claim_mti_by_id_program_user->row()->verifikasi_file;
            $tanggal_claim              = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_claim;
            $status_hardcopy            = $get_ajuan_claim_mti_by_id_program_user->row()->status_hardcopy;
            $nama_status_hardcopy       = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status_hardcopy;
            $file_hardcopy              = $get_ajuan_claim_mti_by_id_program_user->row()->file_hardcopy;
            $nomor_hardcopy             = $get_ajuan_claim_mti_by_id_program_user->row()->nomor_hardcopy;
            $nama_pengirim_hardcopy     = $get_ajuan_claim_mti_by_id_program_user->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy    = $get_ajuan_claim_mti_by_id_program_user->row()->email_pengirim_hardcopy;
            $update_kirim_hardcopy_at   = $get_ajuan_claim_mti_by_id_program_user->row()->update_kirim_hardcopy_at;
            $tanggal_terima_hardcopy    = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_terima_hardcopy;
            $terima_hardcopy_by         = $get_ajuan_claim_mti_by_id_program_user->row()->terima_hardcopy_by;
            $update_terima_hardcopy_at  = $get_ajuan_claim_mti_by_id_program_user->row()->update_terima_hardcopy_at;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti_by_id_program_user->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanda_terima_hardcopy_ke_principal_by = $get_ajuan_claim_mti_by_id_program_user->row()->tanda_terima_hardcopy_ke_principal_by;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti_by_id_program_user->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $signature_ajuan        = $get_ajuan_claim_mti_by_id_program_user->row()->signature;
            $status_dp              = $get_ajuan_claim_mti_by_id_program_user->row()->status_dp;
            $nama_status_dp         = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status_dp;
            $nama_pengirim          = $get_ajuan_claim_mti_by_id_program_user->row()->nama_pengirim;
            $email_pengirim         = $get_ajuan_claim_mti_by_id_program_user->row()->email_pengirim;
            $tanggal_claim          = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_claim;
            $created_at             = $get_ajuan_claim_mti_by_id_program_user->row()->created_at;
            $attach_1               = $get_ajuan_claim_mti_by_id_program_user->row()->attach_1;
            $attach_2               = $get_ajuan_claim_mti_by_id_program_user->row()->attach_2;
            $id_verifikasi          = $get_ajuan_claim_mti_by_id_program_user->row()->id_verifikasi;

            $nama_status_hardcopy_dp = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status_hardcopy_dp;
            $nama_status_hardcopy = $get_ajuan_claim_mti_by_id_program_user->row()->nama_status_hardcopy;
            $nama_pengirim_hardcopy = $get_ajuan_claim_mti_by_id_program_user->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_claim_mti_by_id_program_user->row()->email_pengirim_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_kirim_hardcopy;
            $file_hardcopy = $get_ajuan_claim_mti_by_id_program_user->row()->file_hardcopy;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti_by_id_program_user->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti_by_id_program_user->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti_by_id_program_user->row()->file_tanda_terima_hardcopy_ke_principal;
            
        }else{
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        // echo "signature : ".$signature;
        // echo "signature_ajuan : ".$signature_ajuan;
        // die;

        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        $data = [
            'branch_name'       => $branch_name.' - '.$nama_comp.' - '.$site_code,
            'nama_status_dp'    => $nama_status_dp,
            'namasupp'          => $namasupp,
            'nomor_ajuan'       => $nomor_ajuan,
            'area'              => $area,
            'account'           => $account,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'name'              => $name,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'attach_1'          => $attach_1,
            'attach_2'          => $attach_2,
            'tanggal_claim'     => $tanggal_claim,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
            'nama_status_hardcopy_dp' => $nama_status_hardcopy_dp,
            'nama_status_hardcopy' => $nama_status_hardcopy,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'file_hardcopy' => $file_hardcopy,
        ];

        $from   = "suffy@muliaputramandiri.net";
        $to     = $email;

        $cc     = $email_pengirim.','.$this->email_tim;

        $message = $this->load->view("management_claim/email_status_mti",$data,TRUE);
        $subject = "MPM SITE | CLAIM-MTI : $nomor_ajuan | ".$branch_name." | ".$nama_status_dp;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if ($send) 
        {
            $this->session->set_flashdata("pesan_success", "pelaporan claim dan pengiriman email sukses");
            redirect('management_claim/form_ajuan_claim_mti/'.$signature_program.'/'.$signature);
            die;
        }else{
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun mungkin saja pelaporan claim berhasil. Cek kembali data anda untuk memastikannya");
            redirect('management_claim/form_ajuan_claim_mti/'.$signature_program.'/'.$signature);
            die;
        }
    }

    public function email_status_mti_to_kam($signature_program, $signature){
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }else{
            $id_verifikasi = $get_ajuan_claim_mti_by_signature->row()->id_verifikasi;
            $status = $get_ajuan_claim_mti_by_signature->row()->status;
            $nama_status = $get_ajuan_claim_mti_by_signature->row()->nama_status;
            $branch_name = $get_ajuan_claim_mti_by_signature->row()->branch_name;
            $nama_comp = $get_ajuan_claim_mti_by_signature->row()->nama_comp;
            $site_code = $get_ajuan_claim_mti_by_signature->row()->site_code;
            $nama_status_dp = $get_ajuan_claim_mti_by_signature->row()->nama_status_dp;
            $nomor_ajuan = $get_ajuan_claim_mti_by_signature->row()->nomor_ajuan;
            $nama_pengirim = $get_ajuan_claim_mti_by_signature->row()->nama_pengirim;
            $email_pengirim = $get_ajuan_claim_mti_by_signature->row()->email_pengirim;
            $attach_1 = $get_ajuan_claim_mti_by_signature->row()->attach_1;
            $attach_2 = $get_ajuan_claim_mti_by_signature->row()->attach_2;
            $tanggal_claim = $get_ajuan_claim_mti_by_signature->row()->tanggal_claim;
            $nama_status_hardcopy = $get_ajuan_claim_mti_by_signature->row()->nama_status_hardcopy;
            $nama_status_hardcopy_dp = $get_ajuan_claim_mti_by_signature->row()->nama_status_hardcopy_dp;
            $nama_pengirim_hardcopy = $get_ajuan_claim_mti_by_signature->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_claim_mti_by_signature->row()->email_pengirim_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti_by_signature->row()->tanggal_kirim_hardcopy;
            $file_hardcopy = $get_ajuan_claim_mti_by_signature->row()->file_hardcopy;
        }

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
        }else{
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        // echo "signature : ".$signature;
        // echo "signature_ajuan : ".$signature_ajuan;
        // die;

        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        $data = [
            'branch_name'       => $branch_name.' - '.$nama_comp.' - '.$site_code,
            'nama_status_dp'    => $nama_status,
            'namasupp'          => $namasupp,
            'nomor_ajuan'       => $nomor_ajuan,
            'area'              => $area,
            'account'           => $account,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'name'              => $name,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'attach_1'          => $attach_1,
            'attach_2'          => $attach_2,
            'tanggal_claim'     => $tanggal_claim,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
            'nama_status_hardcopy_dp' => $nama_status_hardcopy_dp,
            'nama_status_hardcopy' => $nama_status_hardcopy,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'file_hardcopy' => $file_hardcopy,
        ];

        $from   = "suffy@muliaputramandiri.net";
        $to     = $this->email_tim;

        // $email_tim = 'ismi.aulia@muliaputramandiri.com, adi@muliaputramandiri.com, ambar@muliaputramandiri.com, dea@muliaputramandiri.com, adm.sls.delto@gmail.com, admin.ka@deltomed.co.id';
        $cc     = $email_pengirim.','.$this->email_tim;
        
        if ($status == 3) { // jika pending head of mti
            $to = $this->email_head_mti;
            $cc = $this->email_tim;
        }if ($status == 4) { // jika reject head of mti
            $to = $email;
            $cc = $this->email_tim;
        }if ($status == 5) { // jika pending finance
            $to = $this->email_finance;
            $cc = $this->email_tim;
        }else if ($status == 6) { // jika approve finance
            $to = $this->email_tim;
            $cc = $this->email_tim;
        }

        // echo "status : ".$status."<br>";
        // echo "nama_status : ".$nama_status."<br>";
        // echo "from : ".$from."<br>";
        // echo "to : ".$to."<br>";
        // echo "cc : ".$cc."<br>";

        // die;

        $message = $this->load->view("management_claim/email_status_mti",$data,TRUE);
        $subject = "MPM SITE | CLAIM-MTI : $nomor_ajuan | ".$branch_name." | ".$nama_status_dp;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if ($send) 
        {
            $this->session->set_flashdata("pesan_success", "verifikasi claim dan pengiriman email sukses");
        }else{
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun mungkin saja pelaporan claim berhasil. Cek kembali data anda untuk memastikannya");
        }

        if ($status == 6 || $status == 7) {
            redirect('management_claim/verifikasi_mti_finance/'.$signature_program.'/'.$signature);
            die;
        }else if ($status == 4 || $status == 5) {
            redirect('management_claim/verifikasi_head_kam_mti/'.$signature_program.'/'.$signature);
            die;
        }else{
            redirect('management_claim/verifikasi_mpm_mti/'.$signature_program.'/'.$signature);
            die;
        }        
    }

    public function email_status_mti_finance($signature_program, $signature){
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
        }else{
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        $get_ajuan_claim_mti = $this->model_management_claim->get_ajuan_claim_mti($signature);
        if ($get_ajuan_claim_mti->num_rows() > 0) {
            echo "ada";
            $nomor_ajuan    = $get_ajuan_claim_mti->row()->nomor_ajuan;
            $branch_name    = $get_ajuan_claim_mti->row()->branch_name;
            $nama_comp      = $get_ajuan_claim_mti->row()->nama_comp;
            $site_code      = $get_ajuan_claim_mti->row()->site_code;
            $nama_pengirim  = $get_ajuan_claim_mti->row()->nama_pengirim;
            $email_pengirim = $get_ajuan_claim_mti->row()->email_pengirim;
            $site_code      = $get_ajuan_claim_mti->row()->site_code;
            $attach_1       = $get_ajuan_claim_mti->row()->attach_1;
            $attach_2       = $get_ajuan_claim_mti->row()->attach_2;
            $status         = $get_ajuan_claim_mti->row()->status;
            $nama_status    = $get_ajuan_claim_mti->row()->nama_status;
            $verifikasi_by  = $get_ajuan_claim_mti->row()->verifikasi_by;
            $verifikasi_at  = $get_ajuan_claim_mti->row()->verifikasi_at;
            $verifikasi_note            = $get_ajuan_claim_mti->row()->verifikasi_note;
            $verifikasi_file            = $get_ajuan_claim_mti->row()->verifikasi_file;
            $tanggal_claim              = $get_ajuan_claim_mti->row()->tanggal_claim;
            $status_hardcopy            = $get_ajuan_claim_mti->row()->status_hardcopy;
            $nama_status_hardcopy       = $get_ajuan_claim_mti->row()->nama_status_hardcopy;
            $file_hardcopy              = $get_ajuan_claim_mti->row()->file_hardcopy;
            $nomor_hardcopy             = $get_ajuan_claim_mti->row()->nomor_hardcopy;
            $nama_pengirim_hardcopy     = $get_ajuan_claim_mti->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy    = $get_ajuan_claim_mti->row()->email_pengirim_hardcopy;
            $update_kirim_hardcopy_at   = $get_ajuan_claim_mti->row()->update_kirim_hardcopy_at;
            $tanggal_terima_hardcopy    = $get_ajuan_claim_mti->row()->tanggal_terima_hardcopy;
            $terima_hardcopy_by         = $get_ajuan_claim_mti->row()->terima_hardcopy_by;
            $update_terima_hardcopy_at  = $get_ajuan_claim_mti->row()->update_terima_hardcopy_at;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanda_terima_hardcopy_ke_principal_by = $get_ajuan_claim_mti->row()->tanda_terima_hardcopy_ke_principal_by;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $signature_ajuan        = $get_ajuan_claim_mti->row()->signature;
            $status_dp              = $get_ajuan_claim_mti->row()->status_dp;
            $nama_status_dp         = $get_ajuan_claim_mti->row()->nama_status_dp;
            $nama_pengirim          = $get_ajuan_claim_mti->row()->nama_pengirim;
            $email_pengirim         = $get_ajuan_claim_mti->row()->email_pengirim;
            $tanggal_claim          = $get_ajuan_claim_mti->row()->tanggal_claim;
            $created_at             = $get_ajuan_claim_mti->row()->created_at;
            $attach_1               = $get_ajuan_claim_mti->row()->attach_1;
            $id_verifikasi          = $get_ajuan_claim_mti->row()->id_verifikasi;

            $nama_status_hardcopy_dp = $get_ajuan_claim_mti->row()->nama_status_hardcopy_dp;
            $nama_status_hardcopy = $get_ajuan_claim_mti->row()->nama_status_hardcopy;
            $nama_pengirim_hardcopy = $get_ajuan_claim_mti->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_claim_mti->row()->email_pengirim_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti->row()->tanggal_kirim_hardcopy;
            $file_hardcopy = $get_ajuan_claim_mti->row()->file_hardcopy;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->file_tanda_terima_hardcopy_ke_principal;
            
        }else{
            $this->session->set_flashdata("pesan", "email gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        $data = [
            'branch_name'       => $branch_name.' - '.$nama_comp.' - '.$site_code,
            'nama_status_dp'    => $nama_status_dp,
            'namasupp'          => $namasupp,
            'nomor_ajuan'       => $nomor_ajuan,
            'area'              => $area,
            'account'           => $account,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'name'              => $name,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'attach_1'          => $attach_1,
            'tanggal_claim'     => $tanggal_claim,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
            'nama_status_hardcopy_dp' => $nama_status_hardcopy_dp,
            'nama_status_hardcopy' => $nama_status_hardcopy,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'file_hardcopy' => $file_hardcopy,
        ];

        $from   = "suffy@muliaputramandiri.net";
        $to     = $email_pengirim;

        // $email_tim = 'ismi.aulia@muliaputramandiri.com, adi@muliaputramandiri.com, ambar@muliaputramandiri.com, dea@muliaputramandiri.com, adm.sls.delto@gmail.com, admin.ka@deltomed.co.id';
        $email_tim = 'suffy.mpm@gmail.com';

        $cc     = $email_pengirim.','.$email_tim;

        $message = $this->load->view("management_claim/email_status_mti",$data,TRUE);
        $subject = "MPM SITE | CLAIM-MTI : $nomor_ajuan | ".$branch_name." | ".$nama_status_dp;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if ($send) 
        {
            $this->session->set_flashdata("pesan_success", "email success");
            redirect('management_claim/ajuan_claim_mti/');     
        }else{
            echo "failed";
        }
    }

    public function verifikasi_mpm_mti($signature_program, $signature_ajuan){
        
        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }
        
        $userid = $this->session->userdata('id');
        if ($userid != $get_registrasi_program_mti_by_signature->row()->userid_kam) {
            $this->session->set_flashdata("pesan", "User Kam Tidak Sesuai !!");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature_ajuan);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $this->load->model('model_management_inventory');

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        $get_ajuan_claim_mti = $this->model_management_claim->get_ajuan_claim_mti($signature_ajuan);
        if ($get_ajuan_claim_mti->num_rows() > 0) {
            $id_ajuan     = $get_ajuan_claim_mti->row()->id;
            $nomor_ajuan  = $get_ajuan_claim_mti->row()->nomor_ajuan;
            $branch_name  = $get_ajuan_claim_mti->row()->branch_name;
            $nama_comp    = $get_ajuan_claim_mti->row()->nama_comp;
            $site_code    = $get_ajuan_claim_mti->row()->site_code;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $tanggal_claim= $get_ajuan_claim_mti->row()->tanggal_claim;
            $nama_pengirim= $get_ajuan_claim_mti->row()->nama_pengirim;
            $email_pengirim= $get_ajuan_claim_mti->row()->email_pengirim;
            $attach_1     = $get_ajuan_claim_mti->row()->attach_1;
            $attach_2     = $get_ajuan_claim_mti->row()->attach_2;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $status_dp    = $get_ajuan_claim_mti->row()->status_dp;
            $nama_status_dp= $get_ajuan_claim_mti->row()->nama_status_dp;
            $created_at   = $get_ajuan_claim_mti->row()->created_at;
            $id_verifikasi = $get_ajuan_claim_mti->row()->id_verifikasi;
            $nama_status_hardcopy_dp = $get_ajuan_claim_mti->row()->nama_status_hardcopy_dp;
            $nama_status_hardcopy = $get_ajuan_claim_mti->row()->nama_status_hardcopy;
            $nama_pengirim_hardcopy = $get_ajuan_claim_mti->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_claim_mti->row()->email_pengirim_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti->row()->tanggal_kirim_hardcopy;
            $file_hardcopy = $get_ajuan_claim_mti->row()->file_hardcopy;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanggal_terima_hardcopy = $get_ajuan_claim_mti->row()->tanggal_terima_hardcopy;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        $data = [
            'title'             => 'Verifikasi MPI oleh KAM',
            'url'               => 'management_claim/verifikasi_mpm_mti_save',
            'nomor_ajuan'       => $nomor_ajuan,
            'account'           => $account,
            'area'              => $area,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'namasupp'          => $namasupp,
            'branch_name'       => $branch_name,
            'nama_comp'         => $nama_comp,
            'site_code_db'      => $site_code,
            'status'            => $status,
            'nama_status'       => $nama_status,
            'nomor_surat'       => $nomor_surat,
            'tanggal_claim'     => $tanggal_claim,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'attach_1'          => $attach_1,
            'attach_2'          => $attach_2,
            'status_dp'         => $status_dp,
            'nama_status_dp'    => $nama_status_dp,
            'created_at'        => $created_at,
            'signature_program' => $signature_program,
            'signature_ajuan'   => $signature_ajuan,
            'id_program'        => $id_program,
            'id_ajuan'          => $id_ajuan,
            'status_verifikasi' => $status_verifikasi,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'attach_1_verifikasi' => $attach_1_verifikasi,
            'created_at_verifikasi' => $created_at_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
            'nama_status_hardcopy_dp' => $nama_status_hardcopy_dp,
            'nama_status_hardcopy' => $nama_status_hardcopy,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'file_hardcopy' => $file_hardcopy,
            'tanda_terima_hardcopy_ke_principal_nama' => $tanda_terima_hardcopy_ke_principal_nama,
            'tanggal_tanda_terima_hardcopy_ke_principal' => $tanggal_tanda_terima_hardcopy_ke_principal,
            'file_tanda_terima_hardcopy_ke_principal' => $file_tanda_terima_hardcopy_ke_principal,
            'tanggal_terima_hardcopy' => $tanggal_terima_hardcopy
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('management_claim/accordion_mti', $data);
        $this->load->view('management_claim/verifikasi_mpm_mti', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_head_kam_mti($signature_program, $signature_ajuan){

        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }
        
        $username = $this->session->userdata('username');
        if ($username != 'jimmy') {
            $this->session->set_flashdata("pesan", "User Head MTI Tidak Sesuai !!");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature_ajuan);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $this->load->model('model_management_inventory');

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        $get_ajuan_claim_mti = $this->model_management_claim->get_ajuan_claim_mti($signature_ajuan);
        if ($get_ajuan_claim_mti->num_rows() > 0) {
            $id_ajuan     = $get_ajuan_claim_mti->row()->id;
            $nomor_ajuan  = $get_ajuan_claim_mti->row()->nomor_ajuan;
            $branch_name  = $get_ajuan_claim_mti->row()->branch_name;
            $nama_comp    = $get_ajuan_claim_mti->row()->nama_comp;
            $site_code    = $get_ajuan_claim_mti->row()->site_code;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $tanggal_claim= $get_ajuan_claim_mti->row()->tanggal_claim;
            $nama_pengirim= $get_ajuan_claim_mti->row()->nama_pengirim;
            $email_pengirim= $get_ajuan_claim_mti->row()->email_pengirim;
            $attach_1     = $get_ajuan_claim_mti->row()->attach_1;
            $attach_2     = $get_ajuan_claim_mti->row()->attach_2;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $status_dp    = $get_ajuan_claim_mti->row()->status_dp;
            $nama_status_dp= $get_ajuan_claim_mti->row()->nama_status_dp;
            $created_at   = $get_ajuan_claim_mti->row()->created_at;
            $id_verifikasi = $get_ajuan_claim_mti->row()->id_verifikasi;
            $nama_status_hardcopy_dp = $get_ajuan_claim_mti->row()->nama_status_hardcopy_dp;
            $nama_status_hardcopy = $get_ajuan_claim_mti->row()->nama_status_hardcopy;
            $nama_pengirim_hardcopy = $get_ajuan_claim_mti->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_claim_mti->row()->email_pengirim_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti->row()->tanggal_kirim_hardcopy;
            $file_hardcopy = $get_ajuan_claim_mti->row()->file_hardcopy;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanggal_terima_hardcopy = $get_ajuan_claim_mti->row()->tanggal_terima_hardcopy;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        $data = [
            'title'             => 'Verifikasi MPI oleh Head of MTI',
            'url'               => 'management_claim/verifikasi_mpm_mti_save',
            'nomor_ajuan'       => $nomor_ajuan,
            'account'           => $account,
            'area'              => $area,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'namasupp'          => $namasupp,
            'branch_name'       => $branch_name,
            'nama_comp'         => $nama_comp,
            'site_code_db'      => $site_code,
            'status'            => $status,
            'nama_status'       => $nama_status,
            'nomor_surat'       => $nomor_surat,
            'tanggal_claim'     => $tanggal_claim,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'attach_1'          => $attach_1,
            'attach_2'          => $attach_2,
            'status_dp'         => $status_dp,
            'nama_status_dp'    => $nama_status_dp,
            'created_at'        => $created_at,
            'signature_program' => $signature_program,
            'signature_ajuan'   => $signature_ajuan,
            'id_program'        => $id_program,
            'id_ajuan'          => $id_ajuan,
            'status_verifikasi' => $status_verifikasi,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'attach_1_verifikasi' => $attach_1_verifikasi,
            'created_at_verifikasi' => $created_at_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
            'nama_status_hardcopy_dp' => $nama_status_hardcopy_dp,
            'nama_status_hardcopy' => $nama_status_hardcopy,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'file_hardcopy' => $file_hardcopy,
            'tanda_terima_hardcopy_ke_principal_nama' => $tanda_terima_hardcopy_ke_principal_nama,
            'tanggal_tanda_terima_hardcopy_ke_principal' => $tanggal_tanda_terima_hardcopy_ke_principal,
            'file_tanda_terima_hardcopy_ke_principal' => $file_tanda_terima_hardcopy_ke_principal,
            'tanggal_terima_hardcopy'    => $tanggal_terima_hardcopy,
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('management_claim/accordion_mti', $data);
        $this->load->view('management_claim/verifikasi_head_kam_mti', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_mpm_mti_save(){
        $status             = $this->input->post('status');
        $keterangan         = $this->input->post('keterangan');
        $id_program         = $this->input->post('id_program');
        $id_ajuan           = $this->input->post('id_ajuan');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');

        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature_ajuan);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature          = 'mticlaim-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/management_claim/mti/')) {
            @mkdir('./assets/uploads/management_claim/mti/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/mti/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename = '';
        };

        $nama_status = $this->model_management_claim->get_status_mti($status);

        if ($status == 1) { // jika status 1 maka PENDING MPI
            $status_dp = 1;
            $nama_status_dp = 'PENDING MPI';

        }elseif($status == 9){ // jika REJECT KAM
            $status_dp = 9;
            $nama_status_dp = 'REJECT';
        }elseif($status == 6){ // jika APPROVE FINANCE
            $status_dp = 3;
            $nama_status_dp = 'APPROVE';
        }elseif($status == 7){ // jika REJECT FINANCE
            $status_dp = 4;
            $nama_status_dp = 'REJECT';
        }else{
            $status_dp = 2;
            $nama_status_dp = 'ON PROCESS';
        }

        $data = [
            'id_program'    => $id_program,
            'id_ajuan'      => $id_ajuan,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'attach_1'      => $filename,
            'keterangan'    => $keterangan,
            'signature'     => $signature,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id'),
        ];

        $insert = $this->db->insert("management_claim.verifikasi_mpm_mti", $data);
        
        $data_update = [
            'status'        => $status,
            'nama_status'   => $nama_status,
            'status_dp'     => $status_dp,
            'nama_status_dp'=> $nama_status_dp,
            'id_verifikasi' => $this->db->insert_id(),
            'updated_at'    => $created_at,
            'updated_by'    => $this->session->userdata('id'),
        ];
        $this->db->where('id', $id_ajuan);
        $this->db->update("management_claim.ajuan_claim_mti", $data_update);

        // $this->session->set_flashdata("pesan_success", "update verifikasi berhasil");
        redirect('management_claim/email_status_mti_to_kam/'.$signature_program."/".$signature_ajuan);
    }

    public function master_kam(){
        $data = [
            'title'                 => 'Master User KAM',
            'get_master_user_kam'   => $this->model_management_claim->get_master_user_kam(),
            'url'                   => 'management_claim/master_kam_save',
            'url_delete'            => 'management_claim/master_kam_delete_checklist',
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('management_claim/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/master_kam', $data);
        $this->load->view('kalimantan/footer');
    }
    
    public function master_account(){
        $data = [
            'title'                 => 'Master Account',
            'get_master_account'    => $this->model_management_claim->get_master_account(),
            'url'                   => 'management_claim/master_account_save',
            'url_delete'            => 'management_claim/master_account_delete_checklist',
            // 'url_relasi'            => $this->model_management_claim->get_master_account(),
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('management_claim/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/master_account', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_account_save(){

        $account = $this->input->post('account');
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'account-'.md5($created_at);

        $data = [
            'account'     => $account,
            'created_at'  => $created_at,
            'created_by'  => $this->session->userdata('id'),
            'signature'   => $signature,
        ];

        $this->db->insert('management_claim.master_account', $data);
        $this->session->set_flashdata("pesan_success", "insert master account berhasil");
        redirect('management_claim/master_data/#master_account', 'refresh');
    }

    public function master_brand(){
        $data = [
            'title'                 => 'Master Brand',
            'get_master_brand'      => $this->model_management_claim->get_master_brand(),
            'url'                   => 'management_claim/master_brand_save',
            'url_delete'            => 'management_claim/master_brand_delete_checklist',
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('management_claim/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/master_brand', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_kam_save(){
        $supp = $this->input->post('supp');
        $userid_kam = $this->input->post('userid_kam');
        $signature = 'masterkam-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'supp'         => $supp,
            'userid_kam'   => $userid_kam,
            'created_at'   => $this->model_outlet_transaksi->timezone(),
            'created_by'   => $this->session->userdata('id'),
            'signature'    => $signature
        ];

        $this->db->insert('management_claim.master_kam', $data);
        $this->session->set_flashdata("pesan_success", "insert successfully");
        redirect('management_claim/master_data/#master_kam', 'refresh');

    }

    public function delete_master_kam($signature){

        $get_master_kam_by_signature = $this->model_management_claim->get_master_kam_by_signature($signature);
        if (!$get_master_kam_by_signature->num_rows() > 0) {
             $this->session->set_flashdata("pesan", "Delete Gagal. Data Not Found");
            redirect('management_claim/master_data/#master_kam', 'refresh');
            die;
        }

        $data = [
            'deleted_at'        => $this->model_outlet_transaksi->timezone(),
            'deleted_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.master_kam', $data);
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('management_claim/master_data/#master_kam', 'refresh');
    }

    public function master_kam_delete_checklist(){
        $id = $this->input->post('options');
        $count = count($id);
        // echo "count : ".$count;
        for ($i=0; $i < $count ; $i++) 
        { 
            $data = [
                'deleted_at'        => $this->model_outlet_transaksi->timezone(),
                'deleted_by'        => $this->session->userdata('id')
            ];
            $this->db->where('id', $id[$i]);
            $this->db->update('management_claim.master_kam', $data);
        }
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('management_claim/master_data/#master_kam', 'refresh');
    }

    public function verifikasi_mti_finance($signature_program, $signature_ajuan){

        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $userid = $this->session->userdata('id');
        $master_finance = $this->db->select('*')->from('management_claim.master_finance')->where('userid_finance', $userid)->get();
        if ($master_finance->num_rows() == 0) {
            $this->session->set_flashdata("pesan", "User Finance Tidak Sesuai !!");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature_ajuan);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $this->load->model('model_management_inventory');

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        $get_ajuan_claim_mti = $this->model_management_claim->get_ajuan_claim_mti($signature_ajuan);
        if ($get_ajuan_claim_mti->num_rows() > 0) {
            $id_ajuan     = $get_ajuan_claim_mti->row()->id;
            $nomor_ajuan  = $get_ajuan_claim_mti->row()->nomor_ajuan;
            $branch_name  = $get_ajuan_claim_mti->row()->branch_name;
            $nama_comp    = $get_ajuan_claim_mti->row()->nama_comp;
            $site_code    = $get_ajuan_claim_mti->row()->site_code;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $tanggal_claim= $get_ajuan_claim_mti->row()->tanggal_claim;
            $nama_pengirim= $get_ajuan_claim_mti->row()->nama_pengirim;
            $email_pengirim= $get_ajuan_claim_mti->row()->email_pengirim;
            $attach_1     = $get_ajuan_claim_mti->row()->attach_1;
            $attach_2     = $get_ajuan_claim_mti->row()->attach_2;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $status_dp    = $get_ajuan_claim_mti->row()->status_dp;
            $nama_status_dp= $get_ajuan_claim_mti->row()->nama_status_dp;
            $created_at   = $get_ajuan_claim_mti->row()->created_at;
            $id_verifikasi = $get_ajuan_claim_mti->row()->id_verifikasi;
            $nama_status_hardcopy_dp = $get_ajuan_claim_mti->row()->nama_status_hardcopy_dp;
            $nama_status_hardcopy = $get_ajuan_claim_mti->row()->nama_status_hardcopy;
            $nama_pengirim_hardcopy = $get_ajuan_claim_mti->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_claim_mti->row()->email_pengirim_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti->row()->tanggal_kirim_hardcopy;
            $file_hardcopy = $get_ajuan_claim_mti->row()->file_hardcopy;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->file_tanda_terima_hardcopy_ke_principal;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        $data = [
            'title'             => 'Verifikasi MPI oleh Finance',
            'url'               => 'management_claim/verifikasi_mpm_mti_save',
            'nomor_ajuan'       => $nomor_ajuan,
            'account'           => $account,
            'area'              => $area,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'namasupp'          => $namasupp,
            'branch_name'       => $branch_name,
            'nama_comp'         => $nama_comp,
            'site_code_db'      => $site_code,
            'status'            => $status,
            'nama_status'       => $nama_status,
            'nomor_surat'       => $nomor_surat,
            'tanggal_claim'     => $tanggal_claim,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'attach_1'          => $attach_1,
            'attach_2'          => $attach_2,
            'status_dp'         => $status_dp,
            'nama_status_dp'    => $nama_status_dp,
            'created_at'        => $created_at,
            'signature_program' => $signature_program,
            'signature_ajuan'   => $signature_ajuan,
            'id_program'        => $id_program,
            'id_ajuan'          => $id_ajuan,
            'status_verifikasi' => $status_verifikasi,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'attach_1_verifikasi' => $attach_1_verifikasi,
            'created_at_verifikasi' => $created_at_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
            'nama_status_hardcopy_dp' => $nama_status_hardcopy_dp,
            'nama_status_hardcopy' => $nama_status_hardcopy,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'file_hardcopy' => $file_hardcopy,
            'tanda_terima_hardcopy_ke_principal_nama' => $tanda_terima_hardcopy_ke_principal_nama,
            'tanggal_tanda_terima_hardcopy_ke_principal' => $tanggal_tanda_terima_hardcopy_ke_principal,
            'file_tanda_terima_hardcopy_ke_principal' => $file_tanda_terima_hardcopy_ke_principal,
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('management_claim/accordion_mti', $data);
        $this->load->view('management_claim/verifikasi_mpi_finance', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_mti_finance_save(){

        $status             = $this->input->post('status');
        $keterangan         = $this->input->post('keterangan');
        $id_program         = $this->input->post('id_program');
        $id_ajuan           = $this->input->post('id_ajuan');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');

        $created_at = $this->model_outlet_transaksi->timezone();

        $signature          = 'mticlaim-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/management_claim/mti/')) {
            @mkdir('./assets/uploads/management_claim/mti/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/mti/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename = '';
        };

        $nama_status = $this->model_management_claim->get_status_mti($status);

        if ($status == 6) { // jika approve finance

            $status_dp = 3;
            $nama_status_dp = 'APPROVE';

        }else{
            $status_dp = 2;
            $nama_status_dp = 'ON PROCESS';
        }

        $data = [
            'id_program'    => $id_program,
            'id_ajuan'      => $id_ajuan,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'attach_1'      => $filename,
            'keterangan'    => $keterangan,
            'signature'     => $signature,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id'),
        ];

        $insert = $this->db->insert("management_claim.verifikasi_mpm_mti", $data);
        
        $data_update = [
            'status'        => $status,
            'nama_status'   => $nama_status,
            'status_dp'     => $status_dp,
            'nama_status_dp'=> $nama_status_dp,
            'id_verifikasi' => $this->db->insert_id(),
            'updated_at'    => $created_at,
            'updated_by'    => $this->session->userdata('id'),
        ];
        $this->db->where('id', $id_ajuan);
        $this->db->update("management_claim.ajuan_claim_mti", $data_update);

        $this->session->set_flashdata("pesan_success", "update verifikasi berhasil");
        // redirect('management_claim/verifikasi_next/'.$signature_program.'/'.$signature_ajuan);
        redirect('management_claim/email_status_mti_finance/'.$signature_program."/".$signature_ajuan);
        
    }

    public function hardcopy_mti($signature_program, $signature_ajuan){

        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature_ajuan);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "verifikasi data gagal dijalankan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $this->load->model('model_management_inventory');

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        $get_ajuan_claim_mti = $this->model_management_claim->get_ajuan_claim_mti($signature_ajuan);
        if ($get_ajuan_claim_mti->num_rows() > 0) {
            $id_ajuan     = $get_ajuan_claim_mti->row()->id;
            $nomor_ajuan  = $get_ajuan_claim_mti->row()->nomor_ajuan;
            $branch_name  = $get_ajuan_claim_mti->row()->branch_name;
            $nama_comp    = $get_ajuan_claim_mti->row()->nama_comp;
            $site_code    = $get_ajuan_claim_mti->row()->site_code;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $tanggal_claim= $get_ajuan_claim_mti->row()->tanggal_claim;
            $nama_pengirim= $get_ajuan_claim_mti->row()->nama_pengirim;
            $email_pengirim= $get_ajuan_claim_mti->row()->email_pengirim;
            $attach_1     = $get_ajuan_claim_mti->row()->attach_1;
            $attach_2     = $get_ajuan_claim_mti->row()->attach_2;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $status_dp    = $get_ajuan_claim_mti->row()->status_dp;
            $nama_status_dp= $get_ajuan_claim_mti->row()->nama_status_dp;
            $created_at   = $get_ajuan_claim_mti->row()->created_at;
            $id_verifikasi = $get_ajuan_claim_mti->row()->id_verifikasi;
            $nama_status_hardcopy_dp = $get_ajuan_claim_mti->row()->nama_status_hardcopy_dp;
            $nama_status_hardcopy = $get_ajuan_claim_mti->row()->nama_status_hardcopy;
            $nama_pengirim_hardcopy = $get_ajuan_claim_mti->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_claim_mti->row()->email_pengirim_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti->row()->tanggal_kirim_hardcopy;
            $file_hardcopy = $get_ajuan_claim_mti->row()->file_hardcopy;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanggal_terima_hardcopy = $get_ajuan_claim_mti->row()->tanggal_terima_hardcopy;
            $status_hardcopy = $get_ajuan_claim_mti->row()->status_hardcopy;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        $data = [
            'title'             => 'Proses Pengiriman Hardcopy mpi',
            'url'               => 'management_claim/hardocopy_mti_proses',
            'nomor_ajuan'       => $nomor_ajuan,
            'account'           => $account,
            'area'              => $area,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'namasupp'          => $namasupp,
            'branch_name'       => $branch_name,
            'nama_comp'         => $nama_comp,
            'site_code_db'      => $site_code,
            'status'            => $status,
            'nama_status'       => $nama_status,
            'nomor_surat'       => $nomor_surat,
            'tanggal_claim'     => $tanggal_claim,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'attach_1'          => $attach_1,
            'attach_2'          => $attach_2,
            'status_dp'         => $status_dp,
            'nama_status_dp'    => $nama_status_dp,
            'created_at'        => $created_at,
            'signature_program' => $signature_program,
            'signature_ajuan'   => $signature_ajuan,
            'id_program'        => $id_program,
            'id_ajuan'          => $id_ajuan,
            'status_verifikasi' => $status_verifikasi,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'attach_1_verifikasi' => $attach_1_verifikasi,
            'created_at_verifikasi' => $created_at_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
            'nama_status_hardcopy_dp' => $nama_status_hardcopy_dp,
            'nama_status_hardcopy' => $nama_status_hardcopy,
            'nama_pengirim_hardcopy' => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy' => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy' => $tanggal_kirim_hardcopy,
            'file_hardcopy' => $file_hardcopy,
            'tanda_terima_hardcopy_ke_principal_nama' => $tanda_terima_hardcopy_ke_principal_nama,
            'tanggal_tanda_terima_hardcopy_ke_principal' => $tanggal_tanda_terima_hardcopy_ke_principal,
            'file_tanda_terima_hardcopy_ke_principal' => $file_tanda_terima_hardcopy_ke_principal,
            'tanggal_terima_hardcopy' => $tanggal_terima_hardcopy,
            'status_hardcopy' => $status_hardcopy,
            'site_code'         => $this->model_management_inventory->get_sitecode(),
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('management_claim/accordion_mti_dp', $data);
        $this->load->view('management_claim/hardcopy_mti', $data);
        $this->load->view('kalimantan/footer');
    }

    public function hardocopy_mti_proses(){
        
        $nama_pengirim_hardcopy     = $this->input->post('nama_pengirim_hardcopy');
        $email_pengirim_hardcopy    = $this->input->post('email_pengirim_hardcopy');
        $noresi                     = $this->input->post('noresi');
        $tanggal_kirim              = $this->input->post('tanggal_kirim');
        $signature_program          = $this->input->post('signature_program');
        $signature_ajuan            = $this->input->post('signature_ajuan');
        $id_program                 = $this->input->post('id_program');
        $id_ajuan                   = $this->input->post('id_ajuan');

        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "pelaporan hardcopy gagal disimpan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature_ajuan);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "pelaporan hardcopy gagal disimpan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $created_at = $this->model_outlet_transaksi->timezone();

        if (!is_dir('./assets/uploads/management_claim/mti/')) {
            @mkdir('./assets/uploads/management_claim/mti/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/mti/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        $data = [
            'nama_pengirim_hardcopy'     => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy'    => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy'     => $tanggal_kirim,
            'nomor_hardcopy'             => $noresi,
            'update_kirim_hardcopy_at'   => $created_at,
            'file_hardcopy'              => $filename,
            'status_hardcopy_dp'         => 2,
            'nama_status_hardcopy_dp'       => 'PENDING MPM',
            'status_hardcopy'            => 2,
            'nama_status_hardcopy'       => 'PENDING MPM',
        ];

        $this->db->where('id', $id_ajuan);
        $this->db->update('management_claim.ajuan_claim_mti', $data);

        $this->session->set_flashdata("pesan_success", "update pelaporan hardcopy berhasil");
        redirect('management_claim/hardcopy_mti/'.$signature_program.'/'.$signature_ajuan);

    }

    public function verifikasi_hardcopy_mti($signature_program, $signature_ajuan){
        $this->load->model('model_management_inventory');
        $userid = $this->session->userdata('id');

        $get_registrasi_program_mti = $this->model_management_claim->get_registrasi_program_mti($signature_program);
        if ($get_registrasi_program_mti->num_rows() > 0) {
            $id_program     = $get_registrasi_program_mti->row()->id;
            $namasupp       = $get_registrasi_program_mti->row()->namasupp;
            $nomor_surat    = $get_registrasi_program_mti->row()->nomor_surat;
            $name           = $get_registrasi_program_mti->row()->name;
            $email          = $get_registrasi_program_mti->row()->email;
            $account        = $get_registrasi_program_mti->row()->account;
            $area           = $get_registrasi_program_mti->row()->area;
            $brand          = $get_registrasi_program_mti->row()->brand;
            $item           = $get_registrasi_program_mti->row()->item;
            $mekanisme      = $get_registrasi_program_mti->row()->mekanisme;
            $expose         = $get_registrasi_program_mti->row()->expose;
            $from           = $get_registrasi_program_mti->row()->from;
            $to             = $get_registrasi_program_mti->row()->to;
            // echo "nomor_surat : ".$nomor_surat;
            // echo "name : ".$name;
            // echo "email : ".$email;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim_mti/');
        }

        // if ($userid != $get_registrasi_program_mti->row()->userid_kam) {
        //     $this->session->set_flashdata("pesan", "User Kam Tidak Sesuai !!");
        //     redirect('management_claim/ajuan_claim_mti/');
        //     die;
        // }

        $get_ajuan_claim_mti = $this->model_management_claim->get_ajuan_claim_mti($signature_ajuan);
        if ($get_ajuan_claim_mti->num_rows() > 0) {
            $id_ajuan     = $get_ajuan_claim_mti->row()->id;
            $nomor_ajuan  = $get_ajuan_claim_mti->row()->nomor_ajuan;
            $branch_name  = $get_ajuan_claim_mti->row()->branch_name;
            $nama_comp    = $get_ajuan_claim_mti->row()->nama_comp;
            $site_code    = $get_ajuan_claim_mti->row()->site_code;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $tanggal_claim= $get_ajuan_claim_mti->row()->tanggal_claim;
            $nama_pengirim= $get_ajuan_claim_mti->row()->nama_pengirim;
            $email_pengirim= $get_ajuan_claim_mti->row()->email_pengirim;
            $attach_1     = $get_ajuan_claim_mti->row()->attach_1;
            $attach_2     = $get_ajuan_claim_mti->row()->attach_2;
            $status       = $get_ajuan_claim_mti->row()->status;
            $nama_status  = $get_ajuan_claim_mti->row()->nama_status;
            $status_dp    = $get_ajuan_claim_mti->row()->status_dp;
            $nama_status_dp= $get_ajuan_claim_mti->row()->nama_status_dp;
            $created_at   = $get_ajuan_claim_mti->row()->created_at;
            $id_verifikasi = $get_ajuan_claim_mti->row()->id_verifikasi;
            $file_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->file_tanda_terima_hardcopy_ke_principal;
            $status_hardcopy = $get_ajuan_claim_mti->row()->status_hardcopy;
            $nama_status_hardcopy = $get_ajuan_claim_mti->row()->nama_status_hardcopy;
            $status_hardcopy_dp = $get_ajuan_claim_mti->row()->status_hardcopy_dp;
            $nama_status_hardcopy_dp = $get_ajuan_claim_mti->row()->nama_status_hardcopy_dp;
            $nama_pengirim_hardcopy = $get_ajuan_claim_mti->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy = $get_ajuan_claim_mti->row()->email_pengirim_hardcopy;
            $tanggal_kirim_hardcopy = $get_ajuan_claim_mti->row()->tanggal_kirim_hardcopy;
            $tanggal_terima_hardcopy = $get_ajuan_claim_mti->row()->tanggal_terima_hardcopy;
            $file_hardcopy = $get_ajuan_claim_mti->row()->file_hardcopy;
            $tanda_terima_hardcopy_ke_principal_nama = $get_ajuan_claim_mti->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_claim_mti->row()->tanggal_tanda_terima_hardcopy_ke_principal;
        }

        // echo "id_ajuan : ".$id_ajuan;
        // die;

        if ($id_verifikasi) {
            $get_verifikasi_mti_by_id = $this->model_management_claim->get_verifikasi_mti_by_id($id_verifikasi);
            if ($get_verifikasi_mti_by_id->num_rows() > 0) {
                $status_verifikasi = $get_verifikasi_mti_by_id->row()->status;
                $nama_status_verifikasi = $get_verifikasi_mti_by_id->row()->nama_status;
                $attach_1_verifikasi = $get_verifikasi_mti_by_id->row()->attach_1;
                $keterangan_verifikasi = $get_verifikasi_mti_by_id->row()->keterangan;
                $created_at_verifikasi = $get_verifikasi_mti_by_id->row()->created_at;
                $name_verifikasi = $get_verifikasi_mti_by_id->row()->name;
                $email_verifikasi = $get_verifikasi_mti_by_id->row()->email;
            }else{
                $status_verifikasi = '';
                $nama_status_verifikasi = '';
                $attach_1_verifikasi = '';
                $keterangan_verifikasi = '';
                $created_at_verifikasi = '';
                $name_verifikasi = '';
                $email_verifikasi = '';
            }
        }else{
            $status_verifikasi = '';
            $nama_status_verifikasi = '';
            $attach_1_verifikasi = '';
            $keterangan_verifikasi = '';
            $created_at_verifikasi = '';
            $name_verifikasi = '';
            $email_verifikasi = '';
        }

        $data = [
            'title'             => 'Verifikasi Hardcopy',
            'url'               => 'management_claim/verifikasi_hardcopy_mti_save',
            'nomor_ajuan'       => $nomor_ajuan,
            'account'           => $account,
            'area'              => $area,
            'brand'             => $brand,
            'item'              => $item,
            'mekanisme'         => $mekanisme,
            'expose'            => $expose,
            'from'              => $from,
            'to'                => $to,
            'namasupp'          => $namasupp,
            'branch_name'       => $branch_name,
            'nama_comp'         => $nama_comp,
            'site_code_db'         => $site_code,
            'status'            => $status,
            'nama_status'       => $nama_status,
            'nomor_surat'       => $nomor_surat,
            'tanggal_claim'     => $tanggal_claim,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'attach_1'          => $attach_1,
            'attach_2'          => $attach_2,
            'status_dp'         => $status_dp,
            'nama_status_dp'    => $nama_status_dp,
            'created_at'        => $created_at,
            'signature_program' => $signature_program,
            'signature_ajuan'   => $signature_ajuan,
            'id_program'        => $id_program,
            'id_ajuan'          => $id_ajuan,
            'status_verifikasi' => $status_verifikasi,
            'nama_status_verifikasi' => $nama_status_verifikasi,
            'keterangan_verifikasi' => $keterangan_verifikasi,
            'attach_1_verifikasi' => $attach_1_verifikasi,
            'created_at_verifikasi' => $created_at_verifikasi,
            'name_verifikasi' => $name_verifikasi,
            'email_verifikasi' => $email_verifikasi,
            'file_tanda_terima_hardcopy_ke_principal'   => $file_tanda_terima_hardcopy_ke_principal,
            'status_hardcopy'   => $status_hardcopy,
            'nama_status_hardcopy'   => $nama_status_hardcopy,
            'status_hardcopy_dp'   => $status_hardcopy_dp,
            'nama_status_hardcopy_dp'   => $nama_status_hardcopy_dp,
            'nama_pengirim_hardcopy'   => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy'   => $email_pengirim_hardcopy,
            'tanggal_kirim_hardcopy'   => $tanggal_kirim_hardcopy,
            'tanggal_terima_hardcopy'   => $tanggal_terima_hardcopy,
            'file_hardcopy'   => $file_hardcopy,
            'tanda_terima_hardcopy_ke_principal_nama'   => $tanda_terima_hardcopy_ke_principal_nama,
            'tanggal_tanda_terima_hardcopy_ke_principal'    => $tanggal_tanda_terima_hardcopy_ke_principal,
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('management_claim/accordion_mti', $data);
        $this->load->view('management_claim/verifikasi_hardcopy_mti', $data);
        $this->load->view('kalimantan/footer');

    }

    public function verifikasi_hardcopy_mti_save(){
        $status_hardcopy = $this->input->post('status');
        $tanggal_terima_hardcopy = $this->input->post('tanggal_terima_hardcopy');
        $nama_penerima = $this->input->post('nama_penerima');
        $tanggal_serah_terima = $this->input->post('tanggal_serah_terima');

        $nama_status_hardcopy = $this->model_management_claim->get_status_hardcopy_mti($status_hardcopy);

        $signature_program          = $this->input->post('signature_program');
        $signature_ajuan            = $this->input->post('signature_ajuan');
        $id_program                 = $this->input->post('id_program');
        $id_ajuan                   = $this->input->post('id_ajuan');

        $get_registrasi_program_mti_by_signature = $this->model_management_claim->get_registrasi_program_mti_by_signature($signature_program);
        if (!$get_registrasi_program_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "update hardcopy gagal disimpan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $get_ajuan_claim_mti_by_signature = $this->model_management_claim->get_ajuan_claim_mti_by_signature($signature_ajuan);
        if (!$get_ajuan_claim_mti_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "update hardcopy gagal disimpan !! data not found");
            redirect('management_claim/ajuan_claim_mti/');
            die;
        }

        $created_at = $this->model_outlet_transaksi->timezone();

        if (!is_dir('./assets/uploads/management_claim/mti/')) {
            @mkdir('./assets/uploads/management_claim/mti/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/mti/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }else{
            $filename = $this->input->post('file_old');
        };

        if ($status_hardcopy == 1) { // jika pending mpi
            $status_hardcopy_dp = 1;
            $nama_status_hardcopy_dp = 'PENDING MPI';
        }else if ($status_hardcopy == 2) { // jika approve
            $status_hardcopy_dp = 2;
            $nama_status_hardcopy_dp = 'PENDING MPM';
        }else if ($status_hardcopy == 3 || $status_hardcopy == 4 || $status_hardcopy == 5) { // jika terima mpm, pending principal/kam, pending principal/finance
            $status_hardcopy_dp = 3;
            $nama_status_hardcopy_dp = 'ON PROCESS';
        }else if ($status_hardcopy == 6) { // jika approve
            $status_hardcopy_dp = 6;
            $nama_status_hardcopy_dp = 'APPROVE';
        }else if ($status_hardcopy == 7) { // jika reject
            $status_hardcopy_dp = 7;
            $nama_status_hardcopy_dp = 'REJECT';
        }

        $data = [
            'status_hardcopy'        => $status_hardcopy,
            'nama_status_hardcopy'   => $nama_status_hardcopy,
            'status_hardcopy_dp'     => $status_hardcopy_dp,
            'nama_status_hardcopy_dp'=> $nama_status_hardcopy_dp,
            'tanggal_terima_hardcopy'=> $tanggal_terima_hardcopy,
            'terima_hardcopy_by'     => $this->session->userdata('id'),
            'update_terima_hardcopy_at' => $created_at,
            'file_tanda_terima_hardcopy_ke_principal'   => $filename,
            'tanda_terima_hardcopy_ke_principal_nama'   => $nama_penerima,
            'tanggal_tanda_terima_hardcopy_ke_principal'   => $tanggal_serah_terima,
            'updated_at' => $created_at
        ];  

        $this->db->where('id', $id_ajuan);
        $this->db->update('management_claim.ajuan_claim_mti', $data);

        $this->session->set_flashdata("pesan_success", "update hardcopy berhasil");
        redirect('management_claim/verifikasi_hardcopy_mti/'.$signature_program.'/'.$signature_ajuan);
    }

    public function delete_master_account($signature){

        // cek signature 
        $get_master_account_by_signature = $this->model_management_claim->get_master_account_by_signature($signature);
        if (!$get_master_account_by_signature->num_rows() > 0) {
             $this->session->set_flashdata("pesan", "Delete Gagal. Data Not Found");
            redirect('management_claim/master_data/#master_account', 'refresh');
        }

        $data = [
            'deleted_at'        => $this->model_outlet_transaksi->timezone(),
            'deleted_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.master_account', $data);
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('management_claim/master_data/#master_account', 'refresh');
    }

    public function mapping_account_save(){
        $kam = $this->input->post('kam');
        $account = $this->input->post('account');
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'mapping_account-'.md5($created_at);

        // echo "kam : ".$kam;
        // echo "account : ".$account;
        // die;

        $data = [
            'kam_userid'     => $kam,
            'account_id'     => $account,
            'created_at'     => $created_at,
            'created_by'     => $this->session->userdata('id'),
            'signature'      => $signature
        ];

        $this->db->insert('management_claim.mapping_account_kam', $data);
        $this->session->set_flashdata("pesan_success", "Insert Berhasil");
        redirect('management_claim/master_data/#mapping_account', 'refresh');

    }

    public function delete_master_mapping_account($signature){

        // cek signature 
        $get_mapping_account_by_signature = $this->model_management_claim->get_mapping_account_by_signature($signature);
        if (!$get_mapping_account_by_signature->num_rows() > 0) {
             $this->session->set_flashdata("pesan", "Delete Gagal. Data Not Found");
            redirect('management_claim/master_data/#mapping_account', 'refresh');
        }

        $data = [
            'deleted_at'        => $this->model_outlet_transaksi->timezone(),
            'deleted_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.mapping_account_kam', $data);
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('management_claim/master_data/#mapping_account', 'refresh');
    }

    public function master_brand_save(){
        $brand = $this->input->post('brand');
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'master_brand-'.md5($created_at);

        $data = [
            'brand'         => $brand,
            'created_at'     => $created_at,
            'created_by'     => $this->session->userdata('id'),
            'signature'      => $signature
        ];

        $this->db->insert('management_claim.master_brand', $data);
        $this->session->set_flashdata("pesan_success", "Insert Berhasil");
        redirect('management_claim/master_data/#master_brand', 'refresh');

    }

    public function delete_master_brand($signature){

        // cek signature 
        $get_master_brand_by_signature = $this->model_management_claim->get_master_brand_by_signature($signature);
        if (!$get_master_brand_by_signature->num_rows() > 0) {
             $this->session->set_flashdata("pesan", "Delete Gagal. Data Not Found");
            redirect('management_claim/master_data/#master_brand', 'refresh');
        }

        $data = [
            'deleted_at'        => $this->model_outlet_transaksi->timezone(),
            'deleted_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.master_brand', $data);
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('management_claim/master_data/#master_brand', 'refresh');
    }

    public function master_finance_save(){
        $userid_finance = $this->input->post('userid_finance');
        $signature = 'masterfinance-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'userid_finance'    => $userid_finance,
            'created_at'        => $this->model_outlet_transaksi->timezone(),
            'created_by'        => $this->session->userdata('id'),
            'signature'         => $signature
        ];

        $this->db->insert('management_claim.master_finance', $data);
        $this->session->set_flashdata("pesan_success", "insert successfully");
        redirect('management_claim/master_data/#master_finance', 'refresh');

    }

    public function delete_master_finance($signature){

        $get_master_finance_by_signature = $this->model_management_claim->get_master_finance_by_signature($signature);
        if (!$get_master_finance_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Delete Gagal. Data Not Found");
            redirect('management_claim/master_data/#master_finance', 'refresh');
            die;
        }

        $data = [
            'deleted_at'        => $this->model_outlet_transaksi->timezone(),
            'deleted_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.master_finance', $data);
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('management_claim/master_data/#master_finance', 'refresh');
    }

    public function master_finance_delete_checklist(){
        $id = $this->input->post('options');
        $count = count($id);
        // echo "count : ".$count;
        for ($i=0; $i < $count ; $i++) 
        { 
            $data = [
                'deleted_at'        => $this->model_outlet_transaksi->timezone(),
                'deleted_by'        => $this->session->userdata('id')
            ];
            $this->db->where('id', $id[$i]);
            $this->db->update('management_claim.master_finance', $data);
        }
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('management_claim/master_data/#master_finance', 'refresh');
    }

    public function export_ajuan_claim_mti(){
        $query = "
            select 	c.namasupp, b.nomor_surat, b.from, b.to, b.account, b.brand, 
                    a.site_code, a.branch_name, a.nama_comp, a.tanggal_claim, a.nama_status, a.nama_status_hardcopy,
                    d.username as kam, d.created_at as tgl_proses_kam, CONCAT(datediff(d.created_at, a.tanggal_claim),' hari') as proses_kam, e.username as head_of_mti, e.created_at as tgl_proses_head_of_mti,
                    CONCAT(datediff(e.created_at, d.created_at),' hari') as proses_head_of_mti,
                    f.username as finance, f.created_at as tgl_proses_finance, 
                    CONCAT(datediff(f.created_at, e.created_at),' hari') as proses_finance
            from    management_claim.ajuan_claim_mti a LEFT JOIN 
            (
                select 	a.id, a.supp, a.nomor_surat, a.userid_kam, a.account, a.area, a.brand, a.item, a.mekanisme, a.expose, 
                        a.from, a.to 
                from    management_claim.registrasi_program_mti a
            )b on a.id_program = b.id LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )c on b.supp = c.supp LEFT JOIN
            (
                SELECT a.id_ajuan, a.status, MAX(a.created_at) as created_at, a.created_by, b.username 
                FROM management_claim.verifikasi_mpm_mti a
                LEFT JOIN mpm.user b on a.created_by = b.id
                WHERE a.status in (1,3)
                GROUP BY a.id_ajuan
            )d on a.id = d.id_ajuan LEFT JOIN
            (
                SELECT a.id_ajuan, a.status, MAX(a.created_at) as created_at, a.created_by, b.username 
                FROM management_claim.verifikasi_mpm_mti a
                LEFT JOIN mpm.user b on a.created_by = b.id
                WHERE a.status in (4,5)
                GROUP BY a.id_ajuan
            )e on a.id = e.id_ajuan
            LEFT JOIN
            (
                SELECT a.id_ajuan, a.status, MAX(a.created_at) as created_at, a.created_by, b.username 
                FROM management_claim.verifikasi_mpm_mti a
                LEFT JOIN mpm.user b on a.created_by = b.id
                WHERE a.status in (6,7)
                GROUP BY a.id_ajuan
            )f on a.id = f.id_ajuan
        ";

        $hasil = $this->db->query($query);  

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'namasupp', 'nomor_surat', 'periode_awal', 'periode_akhir', 'account', 'brand', 'site_code', 'tanggal_claim', 'nama_status', 'nama_status_hardcopy',  'kam', 'tgl_proses_kam', 'proses_kam', 'head_of_mti', 'tgl_proses_head_of_mti', 'proses_head_of_mti',
            'finance', 'tgl_proses_finance', 'proses_finance'
        ));
        $this->excel_generator->set_column(array
        ( 
            'namasupp', 'nomor_surat', 'from', 'to', 'account', 'brand', 'site_code', 'tanggal_claim', 'nama_status', 'nama_status_hardcopy',  'kam', 'tgl_proses_kam', 'proses_kam', 'head_of_mti', 'tgl_proses_head_of_mti', 'proses_head_of_mti',
            'finance', 'tgl_proses_finance', 'proses_finance'
        ));
        $this->excel_generator->set_width(array(15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15)); 
        $this->excel_generator->exportTo2007('Export Claim MPI'); 
    }

    public function delete_registrasi_program_site_code($signature_program){
        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_registrasi_program_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "manage data gagal dijalankan !! data not found");
            redirect('management_claim/registrasi_program');
            die;
        }else{
            $id_program = $get_registrasi_program_by_signature->row()->id;
        }

        $query = "
            delete from management_claim.registrasi_program_site_code 
            where id_program = $id_program
        ";
        $this->db->query($query);
        redirect('management_claim/manage_registrasi_program/'.$signature_program);
    }

    public function delete_registrasi_program_product($signature_program){
        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if (!$get_registrasi_program_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "manage data gagal dijalankan !! data not found");
            redirect('management_claim/registrasi_program');
            die;
        }else{
            $id_program = $get_registrasi_program_by_signature->row()->id;
        }

        $query = "
            delete from management_claim.registrasi_program_product 
            where id_program = $id_program
        ";
        $this->db->query($query);
        redirect('management_claim/manage_registrasi_program_product/'.$signature_program);
    }

    // public function flag_keikutsertaan($signature_program, $signature_ajuan = '')
    // {
    //     $get_registrasi_program = $this->get_registrasi_program($signature_program);

    //     $status = 17; // 17 = tidak ikut, ada di tabel master_status
    //     $nama_status = $this->model_management_claim->get_status($status)->row()->nama_status;

    //     $status_internal = 1;
    //     $nama_status_internal = $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status;

    //     if ($signature_ajuan == '') {

    //         $username = $this->session->userdata('username');

    //         $get_site_code_by_userid = $this->model_management_claim->get_site_code_by_userid($username);
    //         if ($get_site_code_by_userid->num_rows() > 0) {
    //             $site_code = $get_site_code_by_userid->row()->site_code;
    //         }else{
    //             $site_code = '';
    //         }
            
    //         $signature  = 'ajuan-claim-'.rand().md5($this->created_at.rand());

    //         $data = [
    //             'site_code'         => $site_code,
    //             'status'            => $status,
    //             'nama_status'       => $nama_status,
    //             'status_internal'   => $status_internal,
    //             'nama_status_internal' => $nama_status_internal, 
    //             'signature'         => $signature,
    //             'status_keikutsertaan' => 0,
    //             'nama_status_keikutsertaan' => 'tidak ikut',
    //             'id_program'        => $get_registrasi_program['id_program'],
    //             'created_at'        => $this->created_at,
    //             'created_by'        => $this->created_by,
    //         ];
    //         $this->model_management_claim->insert_ajuan_claim($data);
    //         $this->session->set_flashdata("pesan_success", "Input flag status berhasil");
    //         redirect('management_claim/ajuan_claim/');
    //         die;

    //     }else{

    //         $get_ajuan_by_signature = $this->get_ajuan_by_signature($signature_ajuan);
    //         $id_ajuan = $get_ajuan_by_signature['id_ajuan'];
            
    //         $data = [
    //             'status'                    => $status,
    //             'nama_status'               => $nama_status,
    //             'status_internal'           => $status_internal,
    //             'nama_status_internal'      => $nama_status_internal,
    //             'status_keikutsertaan'      => 0,
    //             'nama_status_keikutsertaan' => 'tidak ikut',
    //             'updated_at'                => $this->created_at,
    //             'updated_by'                => $this->created_by
    //         ];

    //         $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);
    //         $this->session->set_flashdata("pesan_success", "Update flag status berhasil");
    //         redirect('management_claim/ajuan_claim/');
    //         die;
    //     }

    // }

    public function flag_keikutsertaan_reset($signature_program, $signature_ajuan = "")
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);

        $status = 1; // 1 = pending dp, ada di tabel master_status
        $nama_status = $this->model_management_claim->get_status($status)->row()->nama_status;

        $status_internal = 1; // 1 = pending dp, ada di tabel master_status_internal
        $nama_status_internal = $this->model_management_claim->get_status_internal($status_internal)->row()->nama_status;

        if ($signature_ajuan == '') {
            $username = $this->session->userdata('username');
            $get_site_code_by_userid = $this->model_management_claim->get_site_code_by_userid($username);
            if ($get_site_code_by_userid->num_rows() > 0) {
                $site_code = $get_site_code_by_userid->row()->site_code;
            }else{
                $site_code = '';
            }
            
            $signature  = 'ajuan-claim-'.rand().md5($this->created_at.rand());
            $data = [
                'site_code'         => $site_code,
                'status'            => $status,
                'nama_status'       => $nama_status,
                'status_internal'   => $status_internal,
                'nama_status_internal' => $nama_status_internal, 
                'signature'         => $signature,
                'status_keikutsertaan' => 1,
                'nama_status_keikutsertaan' => 'ikut',
                'id_program'        => $get_registrasi_program['id_program'],
                'created_at'        => $this->created_at,
                'created_by'        => $this->created_by,
            ];
            $this->model_management_claim->insert_ajuan_claim($data);
            $this->session->set_flashdata("pesan_success", "Input flag status berhasil");
            redirect('management_claim/ajuan_claim/');
            die;

        }else{

            $get_ajuan_by_signature = $this->get_ajuan_by_signature($signature_ajuan);
            $id_ajuan = $get_ajuan_by_signature['id_ajuan'];
            
            $data = [
                'status'                    => $status,
                'nama_status'               => $nama_status,
                'status_internal'           => $status_internal,
                'nama_status_internal'      => $nama_status_internal,
                'status_keikutsertaan'      => 1,
                'nama_status_keikutsertaan' => 'ikut',
                'updated_at'                => $this->created_at,
                'updated_by'                => $this->created_by
            ];

            $this->model_management_claim->update_ajuan_claim($data, $id_ajuan);
            $this->session->set_flashdata("pesan_success", "Update flag status berhasil");
            redirect('management_claim/ajuan_claim/');
            die;
        }
    }

    public function updated_deadline()
    {
        $id = $this->input->post('options');
        $deadline = $this->input->post('deadline');

        $count = count($id);
        // echo "count : ".$count;

        for ($i=0; $i < $count ; $i++) { 
          
            $data = [
                'duedate'       => $deadline,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by
            ];
            $this->db->where('id', $id[$i]);
            // $this->db->update('management_claim.registrasi_program', $data);
            $this->model_management_claim->update_registrasi_program($data, $id[$i]);
        }

        $this->session->set_flashdata("pesan_success", "Update data berhasil. Total Row : $count");
        redirect('management_claim/registrasi_program');
        die;
    }
    
    public function verifikasi_finance_mpm($signature_program, $signature_ajuan)
    {
        $this->load->model('model_master_data');

        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        if ($get_registrasi_program_by_signature->num_rows > 0) {
            $id_program     = $get_registrasi_program_by_signature->row()->id;
            $kategori       = $get_registrasi_program_by_signature->row()->kategori;
            $namasupp       = $this->model_master_data->get_namasupp_by_supp($get_registrasi_program_by_signature->row()->supp)->row()->NAMASUPP;
            $from           = $get_registrasi_program_by_signature->row()->from;
            $to             = $get_registrasi_program_by_signature->row()->to;
            $nama_program   = $get_registrasi_program_by_signature->row()->nama_program;
            $nomor_surat    = $get_registrasi_program_by_signature->row()->nomor_surat;
            $syarat         = $get_registrasi_program_by_signature->row()->syarat;
            $duedate        = $get_registrasi_program_by_signature->row()->duedate;
            $upload_jpg     = $get_registrasi_program_by_signature->row()->upload_jpg;
            $upload_pdf     = $get_registrasi_program_by_signature->row()->upload_pdf;
            $username       = $this->model_master_data->get_username_by_id($get_registrasi_program_by_signature->row()->created_by)->row()->username;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');
            die;
        }

        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature_ajuan);
        if ($get_ajuan_by_signature->num_rows > 0) {
            $created_at     = $get_ajuan_by_signature->row()->created_at;
            $signature_ajuan= $get_ajuan_by_signature->row()->signature;
            $nama_comp      = $get_ajuan_by_signature->row()->nama_comp;
            $nama_pengirim  = $get_ajuan_by_signature->row()->nama_pengirim;
            $email_pengirim = $get_ajuan_by_signature->row()->email_pengirim;
            $ajuan_excel    = $get_ajuan_by_signature->row()->ajuan_excel;
            $ajuan_zip      = $get_ajuan_by_signature->row()->ajuan_zip;
            $nama_status    = $get_ajuan_by_signature->row()->nama_status;
            $id_ajuan       = $get_ajuan_by_signature->row()->id;
            $nomor_ajuan    = $get_ajuan_by_signature->row()->nomor_ajuan;

            $tanggal_claim  = $get_ajuan_by_signature->row()->tanggal_claim;
            $branch_name    = $get_ajuan_by_signature->row()->branch_name;
            $nama_comp      = $get_ajuan_by_signature->row()->nama_comp;
            $site_code      = $get_ajuan_by_signature->row()->site_code;

            $id_verifikasi  = $get_ajuan_by_signature->row()->id_verifikasi;

            $status_hardcopy                        = $get_ajuan_by_signature->row()->status_hardcopy;
            $nama_status_hardcopy                   = $get_ajuan_by_signature->row()->nama_status_hardcopy;
            $file_hardcopy                          = $get_ajuan_by_signature->row()->file_hardcopy;
            $nomor_hardcopy                         = $get_ajuan_by_signature->row()->nomor_hardcopy;
            $tanggal_kirim_hardcopy                 = $get_ajuan_by_signature->row()->tanggal_kirim_hardcopy;
            $nama_pengirim_hardcopy                 = $get_ajuan_by_signature->row()->nama_pengirim_hardcopy;
            $email_pengirim_hardcopy                = $get_ajuan_by_signature->row()->email_pengirim_hardcopy;
            $update_kirim_hardcopy_at               = $get_ajuan_by_signature->row()->update_kirim_hardcopy_at;
            $tanggal_terima_hardcopy                = $get_ajuan_by_signature->row()->tanggal_terima_hardcopy;
            $terima_hardcopy_by                     = $get_ajuan_by_signature->row()->terima_hardcopy_by;
            $update_terima_hardcopy_at              = $get_ajuan_by_signature->row()->update_terima_hardcopy_at;
            $file_tanda_terima_hardcopy_ke_principal= $get_ajuan_by_signature->row()->file_tanda_terima_hardcopy_ke_principal;
            $tanda_terima_hardcopy_ke_principal_by  = $get_ajuan_by_signature->row()->tanda_terima_hardcopy_ke_principal_by;
            $tanda_terima_hardcopy_ke_principal_nama= $get_ajuan_by_signature->row()->tanda_terima_hardcopy_ke_principal_nama;
            $tanggal_tanda_terima_hardcopy_ke_principal = $get_ajuan_by_signature->row()->tanggal_tanda_terima_hardcopy_ke_principal;
            $update_tanda_terima_hardcopy_ke_principal = $get_ajuan_by_signature->row()->update_tanda_terima_hardcopy_ke_principal;

            $terima_hardcopy_nama                    = $this->model_management_claim->get_user($terima_hardcopy_by)->row()->username;

            $status_internal = $get_ajuan_by_signature->row()->status_internal;
            $nama_status_internal = $get_ajuan_by_signature->row()->nama_status_internal;
        }

        if ($id_verifikasi) {
            $get_verifikasi_by_id = $this->model_management_claim->get_verifikasi_by_id($id_verifikasi);
            if ($get_verifikasi_by_id->num_rows > 0) {
                $verifikasi_signature = $get_verifikasi_by_id->row()->signature;
                $verifikasi_keterangan = $get_verifikasi_by_id->row()->keterangan;
                $verifikasi_file = $get_verifikasi_by_id->row()->file;
                $verifikasi_created_at = $get_verifikasi_by_id->row()->created_at;
                $verifikasi_username = $get_verifikasi_by_id->row()->username;
            }
        }else{
            $verifikasi_signature = '';
            $verifikasi_keterangan = '';
            $verifikasi_file = '';
            $verifikasi_created_at = '';
            $verifikasi_username = '';
        }

        $data = [
            'title'                     => 'management claim | Verifikasi Finance MPM',
            'url'                       => 'management_claim/verifikasi_finance_mpm_save',
            'signature_program'         => $signature_program,            
            'signature_ajuan'           => $signature_ajuan,   
            'site_code'                 => $this->model_management_claim->get_sitecode($this->session->userdata('id')),
            'kategori'                  => $kategori,      
            'namasupp'                  => $namasupp,      
            'from'                      => $from,      
            'to'                        => $to,      
            'nama_program'              => $nama_program,      
            'nomor_surat'               => $nomor_surat,      
            'syarat'                    => $syarat,      
            'duedate'                   => $duedate,      
            'upload_jpg'                => $upload_jpg,      
            'upload_pdf'                => $upload_pdf,      
            'username'                  => $username,       
            'nama_pengirim'             => $nama_pengirim,      
            'email_pengirim'            => $email_pengirim,      
            'ajuan_excel'               => $ajuan_excel,      
            'ajuan_zip'                 => $ajuan_zip,      
            'signature_program'         => $signature_program,      
            'signature_ajuan'           => $signature_ajuan,      
            'created_at'                => $created_at,      
            'nama_status'               => $nama_status,      
            'verifikasi_signature'      => $verifikasi_signature,      
            'verifikasi_keterangan'     => $verifikasi_keterangan,      
            'verifikasi_file'           => $verifikasi_file,      
            'verifikasi_created_at'     => $verifikasi_created_at,      
            'verifikasi_username'       => $verifikasi_username,      
            'nomor_ajuan'               => $nomor_ajuan,      
            'site_code'                 => $this->model_management_claim->get_sitecode($this->session->userdata('id')),   

            'tanggal_claim'             => $tanggal_claim,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,

            'status_hardcopy'           => $status_hardcopy,
            'nama_status_hardcopy'      => $nama_status_hardcopy,
            'file_hardcopy'             => $file_hardcopy,
            'nomor_hardcopy'            => $nomor_hardcopy,
            'tanggal_kirim_hardcopy'    => $tanggal_kirim_hardcopy,
            'nama_pengirim_hardcopy'    => $nama_pengirim_hardcopy,
            'email_pengirim_hardcopy'   => $email_pengirim_hardcopy,
            'update_kirim_hardcopy_at'  => $update_kirim_hardcopy_at,
            'tanggal_terima_hardcopy'   => $tanggal_terima_hardcopy,
            'terima_hardcopy_by'        => $terima_hardcopy_by,
            'update_terima_hardcopy_at' => $update_terima_hardcopy_at,            
            'terima_hardcopy_nama'      => $terima_hardcopy_nama,
            'file_tanda_terima_hardcopy_ke_principal'    => $file_tanda_terima_hardcopy_ke_principal,
            'tanda_terima_hardcopy_ke_principal_by'      => $tanda_terima_hardcopy_ke_principal_by,
            'tanda_terima_hardcopy_ke_principal_nama'    => $tanda_terima_hardcopy_ke_principal_nama,
            'tanggal_tanda_terima_hardcopy_ke_principal' => $tanggal_tanda_terima_hardcopy_ke_principal,
            'update_tanda_terima_hardcopy_ke_principal'  => $update_tanda_terima_hardcopy_ke_principal,
            'status_internal'           => $status_internal,
            'nama_status_internal'      => $nama_status_internal,
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('management_claim/accordion', $data);
        $this->load->view('management_claim/verifikasi_finance_mpm', $data);
        $this->load->view('kalimantan/footer');

    }

    public function dashboard_multiple()
    {
        $data = [
            'title' => 'monitoring claim | reporting',
            'url'   => 'management_claim/dashboard_multiple_proses',
        ];

        $this->view($data, false, "dashboard_multiple");
    }

    public function dashboard_multiple_proses()
    {
        $supp = $this->input->post('supp');
        $kategori = $this->input->post('kategori');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $pic = $this->input->post('pic');

        $advanced = [
            'supp'      => $supp,
            'kategori'  => $kategori,
            'from'      => $from,
            'to'        => $to,
            'pic'       => $pic
        ];

        $data = [
            'title'     => 'monitoring claim | data registrasi program',
            'url'       => 'management_claim/dashboard_result',
            // 'get_data'  => $this->model_management_claim->get_registrasi_program_by_supp_kategori_periode($advanced),
            'get_data'  => $this->model_management_claim->get_registrasi_program_regular($advanced),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/dashboard_multiple_proses', $data);
        $this->load->view('kalimantan/footer');

    }

    public function dashboard_result()
    {
        $id = $this->input->post('options');
        $created_at = $this->model_outlet_transaksi->timezone();
        $count = count($id);

        $id_program = '';
        for ($i=0; $i < $count ; $i++) 
        { 
            $id_program.= ','.$id[$i];
        }
        $id_program = preg_replace('/,/', '', $id_program,1);

        $data = [
            'url'       => 'management_claim/dashboard_result_proses',
            'title'     => 'monitoring claim | list pelaporan claim dp',
            'get_data'  => $this->model_management_claim->get_ajuan_claim_join_registrasi_program_by_id_program($id_program),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/dashboard_result', $data);
        $this->load->view('kalimantan/footer');
    }

    public function dashboard_result_proses()
    {

        $id = $this->input->post('options');

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature_export = md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $kategori = $this->input->post('kategori');
        $userid = $this->session->userdata('id');

        $count = count($id);

        // echo "count : ".$count;
        // die;

        $id_ajuan = '';
        $params_signature = '';
        for ($i=0; $i < $count ; $i++) 
        { 
            $kategori_terpilih = $kategori[$i];

            // echo "kategori_terpilih : ".$kategori_terpilih;
            // die;


            $get_ajuan_claim_by_id = $this->model_management_claim->get_ajuan_claim_by_id($id[$i]);
            if ($get_ajuan_claim_by_id->num_rows() > 0) 
            {
                $signature_ajuan = $get_ajuan_claim_by_id->row()->signature;
                $params_signature.= ',"'.$get_ajuan_claim_by_id->row()->signature.'"';

                // echo "signature_ajuan : " . $signature_ajuan . "<br>";
                // die;

                if ($kategori_terpilih == 'bonus_barang' || $kategori_terpilih == '2') 
                {
                    $query = "
                        insert into management_claim.temp_export_bonus_barang
                        select 	'', a.nama_program, a.nama_pengirim, a.email_pengirim, a.nomor_surat_program, a.site_code_header, 
                                a.site_code, a.branch_name, a.nama_comp, a.ajuan_excel, a.ajuan_zip, a.no_sales, a.tgl_sales, a.kode_class,
                                a.nama_class, a.kode_customer,a.nama_customer, a.kodeprod, a.namaprod, a.qty_jual, a.qty_bonus, a.value_jual, a.value_bonus, $id[$i], '$created_at', $userid, '$signature_export'
                        from management_claim.import_bonus_barang a 
                        where a.signature in ('$signature_ajuan')
                    ";

                    $proses = $this->db->query($query);

                    
                }elseif ($kategori_terpilih == 'diskon_herbal' || $kategori == 'diskon_candy' || $kategori == 'diskon' || $kategori == '3' || $kategori == '4' || $kategori == '5') {
                    $query = "
                        insert into management_claim.temp_export_diskon
                        select 	'', a.nama_program, a.nama_pengirim, a.email_pengirim, a.nomor_surat_program, a.site_code_header, 
                                a.site_code, a.branch_name, a.nama_comp, a.ajuan_excel, a.ajuan_zip, a.no_sales, a.tgl_sales, a.kode_class,
                                a.nama_class, a.kode_customer, a.nama_customer, a.kodeprod, a.namaprod, a.qty_jual, a.value_jual, a.disc_principal, a.disc_cabang, a.disc_extra, a.disc_cash, a.disc_claim, $id[$i], '$created_at', $userid, '$signature_export'
                        from management_claim.import_diskon a 
                        where a.signature in ('$signature_ajuan')
                    ";
                    $proses = $this->db->query($query);

                    $query_export_diskon = "
                        select *
                        from management_claim.temp_export_diskon a 
                        where a.signature_export = '$signature_export'
                    ";

                    $export_diskon = $this->db->query($query_export_diskon);
                    query_to_csv($export_diskon,TRUE,'Data Diskon.csv');
                }

            }

        }

        
        $query_export_bonus_barang = "
            select *
            from management_claim.temp_export_bonus_barang a 
            where a.signature_export = '$signature_export'
        ";

        $export = $this->db->query($query_export_bonus_barang);
        query_to_csv($export,TRUE,'Data Bonus Barang.csv');
    }

    public function log_aktivitas($signature)
    {
        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature);
        if ($get_ajuan_by_signature->num_rows() > 0) {
            $id_ajuan = $get_ajuan_by_signature->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');
            die;
        }

        $get_data = $this->model_management_claim->get_log_aktivitas_by_id_ajuan($id_ajuan);
        if ($get_data->num_rows() > 0) {
            $namasupp = $get_data->row()->namasupp;
            $branch_name = $get_data->row()->branch_name;
            $nama_comp = $get_data->row()->nama_comp;
            $nama_comp = $get_data->row()->nama_comp;
        }

        $data = [
            'url'       => '',
            'title'     => 'monitoring claim | Log Aktivitas',
            'get_data'  => $get_data,
            'signature' => $signature,
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/log_aktivitas', $data);
        $this->load->view('kalimantan/footer');
    }

    public function log_aktivitas_export($signature)
    {
        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature);
        if ($get_ajuan_by_signature->num_rows() > 0) {
            $id_ajuan = $get_ajuan_by_signature->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/ajuan_claim/');
            die;
        }

        $get_data = $this->model_management_claim->get_log_aktivitas_by_id_ajuan($id_ajuan);
        if ($get_data->num_rows() > 0) {
            
            $this->excel_generator->set_query($get_data);
            $this->excel_generator->set_header(array
            (
                'namasupp', 'branch_name', 'nama_comp', 'site_code', 'nomor_surat', 'nama_program', 'duedate', 'nomor_ajuan', 'tanggal_claim', 'created_at', 'username', 'nama_status', 'nama_status_internal', 'keterangan'
            ));
            $this->excel_generator->set_column(array
            ( 
                'namasupp', 'branch_name', 'nama_comp', 'site_code', 'nomor_surat', 'nama_program', 'duedate', 'nomor_ajuan', 'tanggal_claim', 'created_at', 'username', 'nama_status', 'nama_status_internal', 'keterangan' 
            ));
            $this->excel_generator->set_width(array(10, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20)); 
            $this->excel_generator->exportTo2007('Download Log');

        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/log_aktivitas/'.$signature);
            die;
        }


        

    }

    public function export_data($signature)
    {
        $get_registrasi_program_by_signature_ajuan = $this->model_management_claim->get_registrasi_program_by_signature_ajuan($signature);
        if ($get_registrasi_program_by_signature_ajuan->num_rows() > 0) {
            $kategori = $get_registrasi_program_by_signature_ajuan->row()->kategori;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/dashboard_multiple');
            die;
        }

        if ($kategori == 'bonus_barang')
        {
            $query = "
                select 	a.nama_program, a.nama_pengirim, a.email_pengirim, a.nomor_surat_program, a.site_code_header, 
                        a.site_code, a.branch_name, a.nama_comp, a.ajuan_excel, a.ajuan_zip, a.no_sales, a.tgl_sales, a.kode_class,
                        a.nama_class, a.kode_customer,a.nama_customer, a.kodeprod, a.namaprod, a.qty_jual, a.qty_bonus, a.value_jual, a.value_bonus, b.company
                from management_claim.import_bonus_barang a left join (
                    select a.username, a.company
                    from site.master_user a 
                )b on left(a.site_code, 3) = b.username
                where a.signature in ('$signature')
            ";

            $hasil = $this->db->query($query);  

            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
                'tgl_sales','kode_class','nama_class','kode_customer','nama_customer','kodeprod','namaprod','qty_jual','qty_bonus','value_jual','value_bonus', 'company'
            ));
            $this->excel_generator->set_column(array
            ( 
                'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
                'tgl_sales','kode_class','nama_class','kode_customer', 'nama_customer','kodeprod','namaprod','qty_jual','qty_bonus','value_jual','value_bonus', 'company'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
            $this->excel_generator->exportTo2007('Download Import Bonus Barang');  
            die;
        }elseif ($kategori == 'diskon_herbal' || $kategori == 'diskon_candy' || $kategori == 'diskon')
        {
            $query = "
                select 	a.nama_program, a.nama_pengirim, a.email_pengirim, a.nomor_surat_program, a.site_code_header, 
                        a.site_code, a.branch_name, a.nama_comp, a.ajuan_excel, a.ajuan_zip, a.no_sales, a.tgl_sales, a.kode_class,
                        a.nama_class, a.kode_customer, a.nama_customer, a.kodeprod, a.namaprod, a.qty_jual, a.value_jual, a.disc_principal, a.disc_cabang, a.disc_extra, a.disc_cash, a.disc_claim, b.company
                from management_claim.import_diskon a left join (
                    select a.username, a.company
                    from site.master_user a 
                )b on left(a.site_code, 3) = b.username         
                where a.signature in ('$signature')
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
            // die;

            $hasil = $this->db->query($query);  

            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
                'tgl_sales','kode_class','nama_class','kode_customer','nama_customer','kodeprod','namaprod','qty_jual','value_jual','disc_principal','disc_cabang','disc_extra','disc_cash','disc_claim', 'company'
            ));
            $this->excel_generator->set_column(array
            ( 
                'nama_program','nama_pengirim','email_pengirim','nomor_surat_program','site_code_header','site_code','branch_name','nama_comp','ajuan_excel','ajuan_zip','no_sales',
                'tgl_sales','kode_class','nama_class','kode_customer','nama_customer','kodeprod','namaprod','qty_jual','value_jual','disc_principal','disc_cabang','disc_extra','disc_cash','disc_claim', 'company'
            ));
            $this->excel_generator->set_width(array(10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10,10 )); 
            $this->excel_generator->exportTo2007('Download Import Diskon');  
            die;
        }else{
            $this->session->set_flashdata("pesan", "data not found");
            redirect('management_claim/dashboard_multiple');
            die;
        }
    }

    public function delete_master_region($signature)
    {
        // cek signature
        $cek = $this->model_management_claim->get_master_region_by_signature($signature);
        // die;
        if ($cek->num_rows() > 0) {
            $id = $cek->row()->id;

            $data = [
                'deleted_at'    => $this->created_at,
                'deleted_by'    => $this->created_by,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
            ];

            $this->model_management_claim->update_master_region($data, $id);
            $this->session->set_flashdata("pesan_success_region", "Delete Master Region successfully");
            redirect('management_claim/master_data/#master_region', 'refresh');
        }else{
            $this->session->set_flashdata("pesan_region", "Delete Failed.. Data not found");
            redirect('management_claim/master_data/#master_region', 'refresh');
        }

    }

    public function delete_master_kategori($signature)
    {
        // cek signature
        $cek = $this->model_management_claim->get_master_kategori($signature);
        // die;
        if ($cek->num_rows() > 0) {
            $id = $cek->row()->id;

            $data = [
                'deleted_at'    => $this->created_at,
                'deleted_by'    => $this->created_by,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
            ];

            $this->model_management_claim->update_master_kategori($data, $id);
            $this->session->set_flashdata("pesan_success_kategori", "Delete Master Kategori successfully");
            redirect('management_claim/master_data/#master_kategori', 'refresh');
        }else{
            
            $this->session->set_flashdata("pesan_kategori", "Delete Failed.. Data not found");
            redirect('management_claim/master_data/#master_kategori', 'refresh');
        }

    }

    public function delete_master_template($signature)
    {
        // cek signature
        $cek = $this->model_management_claim->get_master_template($signature);

        if ($cek->num_rows() > 0) {
            $id = $cek->row()->id;

            $data = [
                'deleted_at'    => $this->created_at,
                'deleted_by'    => $this->created_by,
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
            ];

            $this->model_management_claim->update_master_template($data, $id);
            $this->session->set_flashdata("pesan_success_template", "Delete Master Template successfully");
            redirect('management_claim/master_data/#master_template', 'refresh');
        }else{
            $this->session->set_flashdata("pesan_template", "Delete Failed.. Data not found");
            redirect('management_claim/master_data/#master_template', 'refresh');
        }

    }

    public function attachment_config($kategori)
    {
        // upload file attachment
        // create folder based on kategori
        if (!is_dir('./assets/uploads/management_claim/2025/'.$kategori)) {
            @mkdir('./assets/uploads/management_claim/2025/'.$kategori, 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/2025/'.$kategori;
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $config['file_name'] = $this->tahun_folder."-".rand(1000, 9999)."-".$this->created_by."-".$kategori;

        $proses = $this->upload->initialize($config);
        return $proses;
    }

    public function action_principal(){

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $status_principal_ho_terpilih = $this->input->post('status_principal_ho_terpilih');
        $supp = $this->input->post('supp');
        $signature = $this->input->post('signature');
        // $signature = 'RTR-17740fff259797d26a67b11f34dd02636b69520231104';
        // $signature = '1';

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://localhost:81/restapi/api/master_data/action_pengajuan_retur?&token=$token&status_principal_ho_terpilih=$status_principal_ho_terpilih&supp=$supp&signature=$signature&X-API-KEY=123",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataaction = $array_response['data'];

            // echo "<option value=''> -- Pilih Kabupaten -- </option>";

            foreach ($dataaction as $key => $tiap_action)
            {
                echo "<option value='". $tiap_action["id_status"] ."' id_action='" . $tiap_action["action_retur"] . "' >";
                echo $tiap_action["action_retur"];
                echo "</option>";
            }
        }
    }

    public function monitoring($from = '', $to = '', $breakdown = '')
    {
        // echo $this->session->userdata('username');
        // die;

        if($this->session->userdata('username') != 'suffy' && $this->session->userdata('username') != 'adi')
        {
            redirect('management_claim/ajuan_claim');
        }

     

        $from           = $this->input->get('from');
        $to             = $this->input->get('to');
        $breakdown      = $this->input->get('breakdown');
        $kategori       = $this->input->get('kategori');

        // echo "from : ".$from."<br>";
        // echo "to : ".$to."<br>";
        // echo "breakdown : ".$breakdown."<br>";
        // echo "kategori : ".$kategori."<br>";
        // die;

        if($breakdown == "nomor_surat")
        {
            $data = $this->monitoring_by_program($from, $to);
            if ($data) {
                $view = "monitoring_by_program";
                $url = "management_claim/monitoring_by_program_detail_multiple";
            }else{
                echo '<script type="text/javascript">
                    alert("Data tidak ditemukan");
                    window.location = "monitoring";
                </script>';
            }
            
        }else{
            $data = $this->model_management_claim->get_log_aktivitas_group_status_internal($from, $to, $kategori, $breakdown);
            $data_by_principal = $this->model_management_claim->get_log_aktivitas_group_status_internal_and_principal($from, $to, $kategori, $breakdown);
            $data_by_principal_kategori = $this->model_management_claim->get_log_aktivitas_group_status_internal_and_principal_and_kategori($from, $to, $kategori, $breakdown);
            $data_by_principal_kategori_noajuan = $this->model_management_claim->get_log_aktivitas_group_status_internal_and_principal_and_kategori_and_noajuan($from, $to, $kategori, $breakdown);
            $view = "monitoring_new";
            $url = "monitoring";

            $data = [
                "data" => $data,
                "data_by_principal" => $data_by_principal,
                "data_by_principal_kategori" => $data_by_principal_kategori,
                "data_by_principal_kategori_noajuan" => $data_by_principal_kategori_noajuan,
            ];
        }

        // echo "breakdown : ".$breakdown;
        
        $data = [
            'title'         => 'Monitoring Claim',
            'url'           => $url,
            'data'          => $data,
            'breakdown'     => $breakdown
        ];

        // $this->view($data, false, $view);
        $this->render('management_claim/'.$view, $data);  
    }

    public function monitoring_by_program($from, $to)
    {
        $get_data = $this->model_management_claim->get_registrasi_by_from_to($from, $to);
        if ($get_data->num_rows() > 0) {
            // var_dump($get_data);
            return $get_data;

        }else{
            return false;
        }

    }

    public function monitoring_by_program_detail_multiple()
    {
        $id = $this->input->post('options');
        $validurl = $this->input->post('url');
        $count = count($id);

        // echo "count : ".$count;
        // echo "validurl : ".$validurl;

        // die;
        $id_program = '';
        for ($i=0; $i < $count ; $i++) 
        { 
            $id_program.= ','.$id[$i];
        }
        $id_program = preg_replace('/,/', '', $id_program,1);

        // echo "id_program : ".$id_program;die;

        $get_kategori = $this->model_management_claim->get_registrasi_program_by_only_id_program_groupby_kategori($id_program);
        if ($get_kategori->num_rows() <> 1) {
            // echo "xxx";
            $this->session->set_flashdata('pesan', 'Silahkan pilih program dengan kategori yang sama');
            redirect($validurl);
        }

        $id_kategori = $get_kategori->row()->kategori;

        // echo "get_kategori : ".$get_kategori->num_rows();

        $data = [
            'title'         => 'Monitoring Claim',
            'url'           => 'management_claim/monitoring_by_program_detail_raw_data',
            'data'          => $this->model_management_claim->get_ajuan_claim_join_registrasi($id_program),
            // 'breakdown'     => $breakdown,
            'id_kategori'   => $id_kategori,
            'validurl'      => $validurl
        ];

        $this->render('management_claim/monitoring_by_program_detail_multiple', $data);
        // $this->view($data, false, 'monitoring_by_program_detail_multiple');

    }

    public function monitoring_by_program_detail($signature_program)
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $id_program = $get_registrasi_program['id_program'];
        $id_kategori = $get_registrasi_program['id_kategori'];

        $from           = $this->input->get('from');
        $to             = $this->input->get('to');
        $breakdown      = $this->input->get('breakdown');

        // echo "breakdown : ".$breakdown;
        // die;

        if($breakdown == "nomor_surat")
        {
            $data = $this->monitoring_by_program($from, $to);
            $view = "monitoring_by_program";
        }elseif($breakdown == "status_internal")
        {
            $data = $this->model_management_claim->get_log_aktivitas_group_status_internal($from, $to, $breakdown);
            $view = "monitoring";
        }else{
            $data = $this->model_management_claim->get_log_aktivitas_group_status_internal($from, $to, $breakdown);
            $view = "monitoring";
        }

        $data = [
            'title'         => 'Monitoring Claim',
            'url'           => 'management_claim/monitoring_by_program_detail_raw_data',
            'data'          => $this->model_management_claim->get_ajuan_claim_join_registrasi($id_program),
            'breakdown'     => $breakdown,
            'id_kategori'      => $id_kategori
        ];

        $this->view($data, false, 'monitoring_by_program_detail');

    }

    public function monitoring_by_program_detail_raw_data(){
        $id = $this->input->post('options');
        $id_kategori = $this->input->post('id_kategori');
        $validurl = $this->input->post('url');

        $count = count($id);

        // echo "<pre>";
        // echo "id_kategori : ".$id_kategori;
        // echo "</pre>";
        // echo "validurl : ".$validurl;
        // echo "<br>";
        // // die;
        // echo "count : ".$count;
        // echo "<pre>";
        // var_dump($id);
        // echo "</pre>";
        // die;

        $id_import_header = '';
        for ($i=0; $i < $count ; $i++) 
        { 
            $id_import_header.= ','.$id[$i];
        }
        $id_import_header = preg_replace('/,/', '', $id_import_header,1);

        // echo "id_import_header : ".$id_import_header;
        // die;
        if ($id_kategori == 2) {
            $this->export_raw_bonus_barang($id_import_header);
        }elseif ($id_kategori == 3 || $id_kategori == 4 || $id_kategori == 5) {
            $this->export_raw_diskon($id_import_header);
        }else{
            $this->session->set_flashdata('pesan', 'data not found');
            redirect($validurl);
        }
    }

    public function export_raw_bonus_barang($id)
    {
        $query = "
            select 	b.nomor_surat, b.nama_program, a.site_code_header, 
                    a.site_code, a.branch_name, a.nama_comp,
                    a.no_sales, a.tgl_sales, a.kode_class, a.nama_class,
                    a.kode_customer, a.nama_customer, a.kodeprod, a.namaprod,
                    a.qty_jual, a.qty_bonus, a.value_jual, a.value_bonus
            from management_claim.import_bonus_barang a  left join management_claim.registrasi_program b 
                on a.id_program = b.id
            where a.id_header in ($id)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $export = $this->db->query($query);
        query_to_csv($export,TRUE,'Export Bonus Barang.csv');
    }

    public function export_raw_diskon($id)
    {
        $query = "
            select 	b.nomor_surat, b.nama_program, a.site_code_header, 
                    a.site_code, a.branch_name, a.nama_comp,
                    a.no_sales, a.tgl_sales, a.kode_class, a.nama_class,
                    a.kode_customer, a.nama_customer, a.kodeprod, a.namaprod,
                    a.qty_jual, a.value_jual, a.disc_principal, a.disc_cabang, a.disc_extra, a.disc_cash, a.disc_claim
            from management_claim.import_diskon a  left join management_claim.registrasi_program b 
                on a.id_program = b.id
            where a.id_header in ($id)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $export = $this->db->query($query);
        query_to_csv($export,TRUE,'Export Bonus Barang.csv');
    }

    public function generate_master_outlet()
    {
        $get_site_code = $this->model_management_claim->get_sitecode($this->session->userdata('id'));
        if ($get_site_code->num_rows() > 0) {
            $site_code = $get_site_code->row()->site_code;
            $sub = $get_site_code->row()->sub;
        }else{
            $site_code = "";
            $sub = "";
        }

        $get_site_code_by_sub = $this->model_management_claim->get_site_code_by_sub($sub);

        $data = [
            'url'       => 'management_claim/generate_master_outlet_result',
            'title'     => 'Retrieve Outlet Based on Sales',
            // 'site_code' => $site_code,
            'site_code' => $get_site_code_by_sub,
            'url_insert'=> 'management_claim/insert_outlet',
            'get_data'  => '',
            'month'     => '',
            // 'get_master_outlet'  => $this->model_management_claim->get_master_outlet_lengkap($site_code),
            'flag_result'   => 0,
            'get_master_type' => $this->model_management_claim->get_master_type()
        ];
        $this->view($data, false, "generate_master_outlet");
    }

    public function generate_master_outlet_result()
    {
        $site_code = $this->input->post('site_code');
        $month = $this->input->post('month');
        $kode_type = $this->input->post('kode_type');

        $tahun = substr($month, 0, 4);
        $bulan = substr($month, 5, 2);

        $insert_outlet = $this->model_management_claim->insert_temp_master_outlet($site_code, $tahun, $bulan, $kode_type);
        if ($insert_outlet) 
        {
            $flag_result = 1;
        }else{
            $flag_result = 0;
        }

        $get_data_where_null = $this->model_management_claim->get_temp_master_outlet_join_master_outlet_where_null($site_code);
        if ($get_data_where_null->num_rows() > 0) {
            $flag_button_save = 1;
        }else{
            $flag_button_save = 0;
        }

        $data = [
            'url'       => 'management_claim/generate_master_outlet_save',
            'title'     => 'Retrieve data based on sales',
            "get_data"  => $this->model_management_claim->get_temp_master_outlet_join_master_outlet($site_code),
            'site_code' => $site_code,
            'month'     => $month,
            'url_insert'=> 'management_claim/insert_master_outlet',
            // 'get_master_outlet'  => $this->model_management_claim->get_master_outlet_lengkap($site_code),
            'flag_result'   => $flag_result,
            'flag_button_save' => $flag_button_save
        ];
        $this->view($data, false, "generate_master_outlet_result");
    }

    public function insert_master_outlet()
    {
        $kode_outlet = $this->input->post('kode_outlet');
        $site_code = $this->input->post('site_code');
        $count = count($kode_outlet);

        if (empty($kode_outlet)) {
            $this->session->set_flashdata("pesan", "Input data gagal. Anda belum memilih data apapun.");
            redirect('management_claim/generate_master_outlet/');
        }

        // echo "site_code : ".$site_code;
        // die;

        for ($i=0; $i < $count ; $i++) { 
        
            // cek exist
            $cek_exist = $this->model_management_claim->get_master_outlet_by_kode_outlet($kode_outlet[$i])->num_rows();
            if ($cek_exist > 0) {
                
            }else
            {
                $signature = "master_outlet-" . rand() . md5($this->created_at) . date('Ymd');

                // echo "signature : ".$signature;
                // echo "kode_outlet : ".$kode_outlet[$i]."<br>";

                $data = [
                    "site_code" => $site_code,
                    "kode_outlet" => $kode_outlet[$i],
                    "nama_outlet" => $this->model_management_claim->get_temp_master_outlet_by_kode_outlet($kode_outlet[$i])->row()->nama_outlet_fi,
                    "created_at" => $this->created_at,
                    "created_by" => $this->created_by,
                    "signature" => $signature
                ];

                $this->model_management_claim->insert_master_outlet($data);

                // echo "<hr>";

            }            

        }

        // $this->session->set_flashdata('pesan_success', 'import data master outlet berhasil. Anda akan diarahkan ke master outlet');
        redirect('management_claim/master_outlet');

    }

    public function master_outlet()
    {
        $get_site_code = $this->model_management_claim->get_sitecode($this->session->userdata('id'));
        if ($get_site_code->num_rows() > 0) {
            $site_code = $get_site_code->row()->site_code;
        }else{
            $site_code = "";
        }

        $data = [
            'url'       => 'management_claim/master_outlet_update',
            'title'     => 'Database outlet',
            'site_code' => $site_code,
            'get_data'  => $this->model_management_claim->get_master_outlet_by_created_by($this->session->userdata('id')),
            'tahun_folder'=> $this->tahun_folder
        ];

        $this->view($data, false, "master_outlet");
    }

    public function master_outlet_update()
    {
         

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/master_outlet/';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $proses = $this->upload->initialize($config);



        $id = $this->input->post('options');
        // var_dump($id);


        foreach ($id as $key) {
            
            $no_ktp = $this->input->post('no_ktp')[$key];
            echo "no ktp : ".$no_ktp;

            foreach ($this->input->post('file_ktp') as $file_ktp => $fileObject)
            {
                if (!empty($fileObject['file_ktp'])) {


                    // $init_upload = $this->attachment_config("registrasi_program");
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload($fileObject['file_ktp'])) {
                        $errors = $this->upload->display_errors();                        
                        print_r($error);
                    } else {
                        $fileName[] = $this->upload->data();
                        var_dump($fileName);
                    }
                }else{
                    echo "xxxxxxx";
                }
            }





            die;

            if ($this->upload->do_upload('file_ktp')) 
            {
                $upload_data = $this->upload->data();
                $filename_ktp = $upload_data['file_name'];
                echo "ada file";
                echo $filename_ktp;
            }else{
                $error = $this->upload->display_errors();
                echo "error";
                print_r($error);
            };

        }

        // if ($this->upload->do_upload('file_ktp')) 
        // {
        //     $upload_data = $this->upload->data();
        //     $filename_ktp = $upload_data['file_name'];
        //     echo "ada file";
        //     echo $filename_ktp;
        // }else{
        //     $error = $this->upload->display_errors();
        //     echo "error";
        //     print_r($error);
        // };
        
        die;



        foreach ($id as $id_outlet) {

            
            
            
            $no_ktp = $this->input->post('no_ktp')[$id_outlet];
            $no_npwp = $this->input->post('no_npwp')[$id_outlet];
            $alamat = $this->input->post('alamat')[$id_outlet];
            $no_telp = $this->input->post('no_telp')[$id_outlet];
            $file_ktp_old = $this->input->post('file_ktp_old')[$id_outlet];
            $file_npwp_old = $this->input->post('file_npwp_old')[$id_outlet];
            $file_ktp = $this->input->post('file_ktp');

            var_dump($file_ktp);

            // die;


            if ($this->upload->do_upload('file_ktp')) 
            {
                echo "ada file";
                $upload_data = $this->upload->data();
                $filename_ktp = $upload_data['file_name'];
            }else{
                $error = $this->upload->display_errors();
                print_r($error);
                echo "tidak ada file";
                $filename_ktp = $file_ktp_old;
            };

            echo "filename_ktp : ".$filename_ktp;
            die;

            if ($this->upload->do_upload('file_npwp')) 
            {
                $upload_data = $this->upload->data();
                $filename_npwp = $upload_data['file_name'];
            }else{
                $filename_npwp = $file_npwp_old;
            };

            $data = [
                "no_ktp"        => $no_ktp,
                "no_npwp"       => $no_npwp,
                "alamat"        => $alamat,
                "no_telp"       => $no_telp,
                "file_ktp"      => $filename_ktp,
                "file_npwp"     => $filename_npwp,
                "updated_at"    => $this->created_at,
                "updated_by"    => $this->created_by
            ];

            $this->model_management_claim->update_master_outlet($data, $id_outlet);

        }

        $this->session->set_flashdata('pesan_success', 'update data master outlet berhasil. Pastikan data sudah sesuai');
        redirect('management_claim/master_outlet');

        
    }

    public function master_outlet_detail($signature)
    {

        $get_master_outlet = $this->model_management_claim->get_master_outlet_by_signature($signature);
        if ($get_master_outlet->num_rows() > 0) {

            $kode_outlet = $get_master_outlet->row()->kode_outlet;
            $nama_outlet = $get_master_outlet->row()->nama_outlet;
            $no_ktp = $get_master_outlet->row()->no_ktp;
            $file_ktp = $get_master_outlet->row()->file_ktp;
            $no_npwp = $get_master_outlet->row()->no_npwp;
            $file_npwp = $get_master_outlet->row()->file_npwp;
            $alamat = $get_master_outlet->row()->alamat;
            $no_telp = $get_master_outlet->row()->no_telp;
        }

        $data = [
            'url'       => 'management_claim/master_outlet_detail_save',
            'title'     => 'Outlet Information',
            'signature' => $signature,
            'kode_outlet'    => $kode_outlet,
            'nama_outlet'    => $nama_outlet,
            'no_ktp'    => $no_ktp,
            'file_ktp'  => $file_ktp,
            'no_npwp'   => $no_npwp,
            'file_npwp' => $file_npwp,
            'alamat'    => $alamat,
            'no_telp'   => $no_telp,
            'tahun_folder'=> $this->tahun_folder
        ];

        $this->view($data, false, "master_outlet_detail");
    }

    public function master_outlet_detail_save()
    {
        $init_upload = $this->attachment_config("master_outlet");
        $signature = $this->input->post('signature');
        $no_ktp = $this->input->post('no_ktp');
        $no_npwp = $this->input->post('no_npwp');
        $alamat = $this->input->post('alamat');
        $no_telp = $this->input->post('no_telp');
        $file_ktp_old = $this->input->post('file_ktp_old');
        $file_npwp_old = $this->input->post('file_npwp_old');

        $get_data = $this->model_management_claim->get_master_outlet_by_signature($signature);
        
        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "ada kesalahan saat input data. Silahkan ulangi kembali.");
            redirect('management_claim/master_outlet_detail/'.$signature);
            die;
        }

        $id_outlet = $get_data->row()->id;

        if ($this->upload->do_upload('file_ktp')) 
        {
            $upload_data = $this->upload->data();
            $filename_ktp = $upload_data['file_name'];
        }else{
            $filename_ktp = $file_ktp_old;
        };

        if ($this->upload->do_upload('file_npwp')) 
        {
            $upload_data = $this->upload->data();
            $filename_npwp = $upload_data['file_name'];
        }else{
            $filename_npwp = $file_npwp_old;
        };

        $data = [
            'no_ktp'        => $no_ktp,
            'file_ktp'      => $filename_ktp,
            'no_npwp'       => $no_npwp,
            'file_npwp'     => $filename_npwp,
            'alamat'        => $alamat,
            'no_telp'       => $no_telp,
            'updated_at'    => $this->created_at,
        ];
        
        $this->model_management_claim->update_master_outlet($data, $id_outlet);
        
        $this->session->set_flashdata('pesan_success', 'update data master outlet berhasil. Pastikan data sudah sesuai');
        redirect('management_claim/master_outlet_detail/'.$signature);

    }

    public function registrasi_peserta_loyalty($signature_program)
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $id_program = $get_registrasi_program['id_program'];
        $nomor_surat = $get_registrasi_program['nomor_surat'];
        $nama_program = $get_registrasi_program['nama_program'];
        $namasupp = $get_registrasi_program['namasupp'];
        $periode = $get_registrasi_program['from'].' s/d '.$get_registrasi_program['to'];
        $duedate = $get_registrasi_program['duedate'];
        // var_dump($get_registrasi_program);

        $get_site_code = $this->model_management_claim->get_sitecode($this->session->userdata('id'));
        if ($get_site_code->num_rows() > 0) {
            $site_code = $get_site_code->row()->site_code;
            $branch_name = $get_site_code->row()->branch_name;
            $nama_comp = $get_site_code->row()->nama_comp;
        }else{
            $site_code = "";
            $branch_name = "";
            $nama_comp = "";
        }

        $data = [
            'url'       => 'management_claim/registrasi_peserta_loyalty_update',
            'title'     => 'Peserta program : '.$nomor_surat,
            'title2'    => 'database outlet dengan data lengkap "'.$branch_name.' - '.$nama_comp. ' ('.$site_code.')"',
            'site_code' => $site_code,
            'get_master_outlet'  => $this->model_management_claim->get_master_outlet_lengkap_by_created_by($this->session->userdata('id')),
            'get_peserta'  => $this->model_management_claim->get_loyalty_peserta_by_id_program($id_program, $site_code),
            'id_program'=> $id_program,
            'signature_program' => $signature_program,
            'folder'    => 'loyalty_skp_'.$id_program,
            'tahun_folder' => $this->tahun_folder
        ];

    $this->view($data, false, "peserta_loyalty");

    }

    public function registrasi_peserta_loyalty_detail($signature_program, $signature_peserta)
    {

        $get_program = $this->model_management_claim->get_registrai_program_by_signature_simple($signature_program);
        if (!$get_program->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "ada kesalahan saat input data. Silahkan ulangi kembali.");
            redirect('management_claim/registrasi_peserta_loyalty/'.$signature_program);
            die;
        }

        $id_program = $get_program->row()->id;

        $get_site_code = $this->model_management_claim->get_sitecode($this->session->userdata('id'));
        if ($get_site_code->num_rows() > 0) {
            $site_code = $get_site_code->row()->site_code;
        }else{
            $site_code = "";
        }

        $get_data = $this->model_management_claim->get_loyalty_peserta_detail_by_signature_and_created_by($signature_peserta, $this->session->userdata('id'));

        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "ada kesalahan saat input data. Silahkan ulangi kembali.");
            redirect('management_claim/registrasi_peserta_loyalty/'.$signature_peserta);
            die;
        }

        $kode_outlet = $get_data->row()->kode_outlet;
        $nama_outlet = $get_data->row()->nama_outlet;
        $no_ktp = $get_data->row()->no_ktp;
        $file_ktp = $get_data->row()->file_ktp;
        $no_npwp = $get_data->row()->no_npwp;
        $file_npwp = $get_data->row()->file_npwp;
        $alamat = $get_data->row()->alamat;
        $no_telp = $get_data->row()->no_telp;
        $file_skp = $get_data->row()->file_skp;
        $paket = $get_data->row()->paket;
        $id = $get_data->row()->id;
        $id_ref = $get_data->row()->id_ref;

        $data = [
            'url'               => 'management_claim/registrasi_peserta_loyalty_detail_save',
            'title'             => 'Detail Peserta Loyalty, SKP, dan Paket',
            'signature_peserta' => $signature_peserta,
            'signature_program' => $signature_program,
            'kode_outlet'       => $kode_outlet,
            'nama_outlet'       => $nama_outlet,
            'no_ktp'            => $no_ktp,
            'file_ktp'          => $file_ktp,
            'no_npwp'           => $no_npwp,
            'file_npwp'         => $file_npwp,
            'alamat'            => $alamat,
            'no_telp'           => $no_telp,
            'file_skp'          => $file_skp,
            'paket'             => $paket,
            'id'                => $id,
            'id_ref'            => $id_ref,
            'id_program'        => $id_program,
            'folder'            => 'loyalty_skp_'.$id_program,
            'tahun_folder'      => $this->tahun_folder
        ];

        $this->view($data, false, "registrasi_peserta_loyalty_detail");
    }

    public function registrasi_peserta_loyalty_detail_save()
    {
        $id = $this->input->post('id');
        $paket = $this->input->post('paket');
        $id_program = $this->input->post('id_program');
        $signature_program = $this->input->post('signature_program');
        $signature_peserta = $this->input->post('signature_peserta');
        
        // echo "id : ".$id;
        // echo "paket : ".$paket;
        // echo "id_program : ".$id_program;

        $init_upload = $this->attachment_config("loyalty_skp_".$id_program);

        if ($this->upload->do_upload('file_skp')) 
        {
            $upload_data = $this->upload->data();
            $filename_skp = $upload_data['file_name'];
        }else{
            $this->session->set_flashdata("pesan", "upload skp gagal.");
            redirect('management_claim/registrasi_peserta_loyalty_detail/'.$signature_program.'/'.$signature_peserta);
            die;
        };

        $data = [
            'file_skp' => $filename_skp,
            'paket'    => $paket,
            'updated_at' => $this->created_at,
            'updated_by' => $this->created_by
        ];

        $this->model_management_claim->update_peserta_loyalty_detail($data, $id);
        $this->session->set_flashdata("pesan_success", "data berhasil disimpan.");
        redirect('management_claim/registrasi_peserta_loyalty_detail/'.$signature_program.'/'.$signature_peserta);
    }

    public function registrasi_peserta_loyalty_update()
    {
        $id = $this->input->post('id');
        $id_program = $this->input->post('id_program');
        $signature = md5($this->created_at).'_'.$id_program.'_'.$this->created_by;
        $signature_program = $this->input->post('signature_program');
        $site_code = $this->input->post('site_code');

        if (empty($id)) {
            $this->session->set_flashdata("pesan", "Input data gagal. Anda belum memilih data apapun.");
            redirect('management_claim/registrasi_peserta_loyalty/'.$signature_program);
        }

        // die;
        
        // echo "site_code : ".$site_code;

        // cek apakah sudah ada peserta di tabel loyalty_peserta 
        $get_data = $this->model_management_claim->get_loyalty_peserta_by_id_program_and_created_by($id_program, $this->created_by);
        if($get_data->num_rows() > 0) {
            $data = [
                'updated_at'    => $this->created_at,
                'updated_by'    => $this->created_by,
            ];

            $id_loyalty_peserta = $this->model_management_claim->update_peserta_loyalty($data, $get_data->row()->id);
        }else{
            $data = [
                'id_program'    => $id_program,
                'signature'     => $signature,
                'created_at'    => $this->created_at,
                'created_by'    => $this->created_by
            ];

            $id_loyalty_peserta = $this->model_management_claim->insert_peserta_loyalty($data);
        }       

        foreach ($id as $key) 
        {
            $no_ktp = $this->input->post('no_ktp')[$key];
            $file_ktp = $this->input->post('file_ktp')[$key];
            $kode_outlet = $this->input->post('kode_outlet')[$key];
         
            // cek apakah sudah ada di tabel loyalty_peserta_detail
            $get_data_detail = $this->model_management_claim->get_loyalty_peserta_detail_by_id_ref_and_kode_outlet_any_data($id_loyalty_peserta, $kode_outlet);
            if($get_data_detail->num_rows() > 0) {
                $detail = [
                    'updated_at'    => $this->created_at,
                    'updated_by'    => $this->created_by,
                    'deleted_at'    => null,
                    'deleted_by'    => null
                ];

                $this->model_management_claim->update_peserta_loyalty_detail($detail, $get_data_detail->row()->id);
            }else{
                $signature_detail = md5($this->created_at).'_'.$kode_outlet.'_'.$this->created_by;
                $detail = [
                    'id_ref'        => $id_loyalty_peserta,
                    'kode_outlet'   => $kode_outlet,
                    'created_at'    => $this->created_at,
                    'created_by'    => $this->created_by,
                    'signature'     => $signature_detail
                ];
                $this->model_management_claim->insert_peserta_loyalty_detail($detail);
            }            
        }

        $this->session->set_flashdata('pesan_success', 'update data peserta loyalty berhasil. Pastikan data sudah sesuai');
        redirect('management_claim/registrasi_peserta_loyalty/'.$signature_program);

    }

    public function delete_peserta_loyalty($signature, $signature_program)
    {
        $get_site_code = $this->model_management_claim->get_sitecode($this->session->userdata('id'));
        if ($get_site_code->num_rows() > 0) {
            $site_code = $get_site_code->row()->site_code;
        }else{
            $site_code = "";
        }

        $get_data = $this->model_management_claim->get_loyalty_peserta_detail_by_signature($signature, $site_code);

        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "ada kesalahan saat input data. Silahkan ulangi kembali.");
            redirect('management_claim/registrasi_peserta_loyalty/'.$signature_program);
            die;
        }

        $id = $get_data->row()->id;

        $data = [
            'deleted_at'    => $this->created_at,
            'deleted_by'    => $this->created_by
        ];
        $this->model_management_claim->update_peserta_loyalty_detail($data, $id);

        $this->session->set_flashdata('pesan_success', 'delete data peserta loyalty berhasil. Pastikan data sudah sesuai');
        redirect('management_claim/registrasi_peserta_loyalty/'.$signature_program);


    }

    public function peserta_loyalty($signature_ajuan)
    {
        $get_data = $this->get_ajuan_by_signature($signature_ajuan);
        // var_dump($get_data);
        $id_program = $get_data['id_program'];
        $ajuan_by = $get_data['ajuan_by'];
        
        echo "id_program : ".$id_program;
        echo "<br>";
        echo "ajuan_by : ".$ajuan_by;

        $id_user = $this->model_management_claim->get_user_by_username($ajuan_by)->row()->id;
        $get_site_code = $this->model_management_claim->get_sitecode($id_user);

        echo "id_user : ".$id_user;
        echo "site_code : ".$get_site_code;

        $get_peserta_loyalty = $this->model_management_claim->get_loyalty_peserta_by_id_program($id_program, $get_site_code->row()->site_code);


        // if ($get_peserta_loyalty->num_rows() > 0) {
        //     $peserta_loyalty = $get_peserta_loyalty->result();
        // }else{
        //     $peserta_loyalty = array();
        // }


    }

    public function download_peserta_loyalty($signature_program)
    {
        $get_registrasi_program = $this->get_registrasi_program($signature_program);
        $id_program = $get_registrasi_program['id_program'];
        $nomor_surat = $get_registrasi_program['nomor_surat'];

        $export = $this->model_management_claim->export_peserta_loyalty($id_program); 
    
        $this->excel_generator->set_query($export);
        $this->excel_generator->set_header(array
        (
            'nomor_surat', 'nama_program', 'branch_name', 'nama_comp', 'site_code', 'kode_outlet', 'nama_outlet', 'file_skp', 'paket', 'updated_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'nomor_surat', 'nama_program', 'branch_name', 'nama_comp', 'site_code', 'kode_outlet', 'nama_outlet', 'file_skp', 'paket', 'updated_at'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Peserta Loyalty : '.$nomor_surat); 
        
    }

    public function download_file_outlet($signature, $tipe)
    {
        $this->load->helper('download');
        $get_data = $this->model_management_claim->get_master_outlet_by_signature($signature);
        if ($get_data->num_rows() == 0) {
            return false;
        }

        if ($tipe == 'ktp') {
            $filename_tipe = 'ktp';            
            $filename = $get_data->row()->file_ktp;
        }elseif($tipe == 'npwp') {
            $filename_tipe = 'npwp';
            $filename = $get_data->row()->file_npwp;
        }
        else{
            $filename_tipe = '';
            $filename = '';
        }

        $kode_outlet = $get_data->row()->kode_outlet;

        $img_url = base_url().'assets/uploads/management_claim/'. $this->tahun_folder .'/master_outlet/'. $filename;
        $data = file_get_contents($img_url);
        $name = $get_data->row()->kode_outlet."-".$filename_tipe.".jpg";
        force_download($name, $data);
    }

    public function track_click_claim()
    {
        // Pastikan request adalah AJAX
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        // Ambil data POST
        $log_id = $this->input->post('log_id');
        $user_id = $this->input->post('user_id');

        // Validasi data
        if (empty($log_id) || empty($user_id)) {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid data'));
            return;
        }

        // Load model
        // $this->load->model('tracking_model');

        // Simpan data tracking
        $data = array(
            'log_id' => $log_id,
            'userid' => $user_id,
            'created_at' => $this->model_outlet_transaksi->timezone()
        );

        // $result = $this->tracking_model->insert_tracking($data);
        $result = $this->db->insert('management_claim.track_click_claim', $data);

        if ($result) 
        {
            $get_log = $this->model_management_claim->get_log_aktivitas_by_id($log_id);   
            if($get_log->num_rows() > 0)
            {
                $log = $get_log->row();
                $get_log_aktivitas_by_id_ajuan_new = $this->model_management_claim->get_log_aktivitas_by_id_ajuan_new($log->id_ajuan);

                if($get_log_aktivitas_by_id_ajuan_new->num_rows() > 0)
                {
                    $pic_on_duty = $get_log_aktivitas_by_id_ajuan_new->row()->pic_on_duty;
                    if($pic_on_duty != $user_id) {
                        echo json_encode(array('status' => 'error', 'message' => 'Failed to save tracking data'));
                        return;
                    }

                    $status = 2;
                    $status_internal = 26;
                    $time_response = ($this->model_management_claim->get_status_internal($status_internal) ? $this->model_management_claim->get_status_internal($status_internal)->row()->time_response : 0);
                    $duedate_response = date('Y-m-d', strtotime('+'.$time_response.' days', strtotime($this->created_at)));

                    $data_log = [
                        'ref_log' => $log_id,
                        'id_registrasi' => $log->id_registrasi,
                        'id_ajuan'  => $log->id_ajuan,
                        'status'    => $status,
                        'status_internal' => $status_internal,
                        'function'  => 'track_click_claim',
                        'file'  => $log->file,
                        'file_zip'  => $log->file_zip,
                        'signature' => md5($user_id.$log_id.$this->created_at),
                        'created_at' => $this->created_at,
                        'created_by' => $user_id,
                        'updated_by' => $user_id,
                        'pic_on_duty' => $user_id,
                        'on_duty_finish' => 0,
                        'level_on_duty' => 1,
                        'time_response' => $time_response,
                        'duedate_response' => $duedate_response
                    ];
                    $insert_log = $this->model_management_claim->insert_log_claim($data_log);

                    $update_ajuan = [
                        "id_log"            => $insert_log,
                        "pic_userid"        => $user_id,
                        "status"            => $status,
                        "status_internal"   => $status_internal,
                        "updated_at"        => $this->created_at,
                        "updated_by"        => $this->created_by
                    ];
                    $proses_update_ajuan = $this->model_management_claim->update_ajuan_claim($update_ajuan, $log->id_ajuan);

                    $update_log = [
                        "on_duty_finish"    => 1,
                        "updated_by"        => $this->created_by,
                        "updated_at"        => $this->created_at
                    ];
                    $this->model_management_claim->update_log_claim($update_log, $log_id);
                }
            }

            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to save tracking data'));
        }
    }

  public function ajuan_claim_nka()
  {
    $from = $this->input->get('from');
    $to = $this->input->get('to');
    $channel = $this->input->get('channel_filter');
    $kategori = $this->input->get('kategori');

    $submit = $this->input->get('submit');
    // echo "submit : ".$submit;
    if($submit == 'export')
    {
      $this->export_ajuan_claim_nka_v2($from, $to, $channel, $kategori);
      return;
    }
    $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_username_userid($this->username, $this->created_by, $from, $to, $channel, $kategori);
    
    $data = [
      'title'         => 'Pengajuan Claim NKA',
      'url'           => 'management_claim/ajuan_claim_nka_save',
      'url_search'    => 'management_claim/ajuan_claim_nka',
      'url_detail'    => 'management_claim/ajuan_claim_nka_detail',
      // 'key_account'   => $this->model_management_claim->get_key_account_by_channel(),
      'get_data'      => $get_data,
      'get_site_code' => $this->model_management_claim->get_site_code_by_username($this->username),
      'get_kategori'  => $this->model_management_claim->get_master_kategori_list()
    ];
    
    // $this->render('management_claim/ajuan_claim_nka', $data);
    $this->render_multiple(
      array(
        'management_claim/css/style_nka.php',
        'management_claim/ajuan_claim_nka',
        'management_claim/tabel_ajuan_nka'
      ),
      $data
    );
  }


  public function export_ajuan_claim_nka_v2($from, $to, $channel, $kategori)
  {
    $get_data = $this->model_management_claim->get_ajuan_claim_nka_for_export($this->session->userdata('username'), $this->created_by, $from, $to, $channel, $kategori); 

    $this->excel_generator->set_query($get_data);
    $this->excel_generator->set_header(array
    (
      'nama_comp','nomor_ajuan','nomor_klaim','nomor_invoice','channel','kategori','key_account','periode_start','periode_end',
      'pic_nama','pic_email','nominal_dpp','keterangan','nama_status',
      'username_principal', 'email_principal', 'principal_nama_status', 'principal_keterangan',
      'username_mpm', 'email_mpm', 'mpm_nama_status', 'mpm_keterangan',
      'username_admin_mpm', 'email_admin_mpm', 'admin_mpm_nama_status', 'admin_mpm_keterangan',
      'on_duty_name', 'created_at', 'principal_at', 'mpm_at', 'admin_mpm_at', 'revisi_at',
      'lt_principal', 'lt_mpm', 'lt_admin_mpm', 'lt_revisi'
    ));
    $this->excel_generator->set_column(array
    ( 
      'nama_comp','nomor_ajuan','nomor_klaim','nomor_invoice','channel','kategori','key_account','periode_start','periode_end',
      'pic_nama','pic_email','nominal_dpp','keterangan','nama_status',
      'username_principal', 'email_principal', 'principal_nama_status', 'principal_keterangan',
      'username_mpm', 'email_mpm', 'mpm_nama_status', 'mpm_keterangan',
      'username_admin_mpm', 'email_admin_mpm', 'admin_mpm_nama_status', 'admin_mpm_keterangan',
      'on_duty_name', 'created_at', 'principal_at', 'mpm_at', 'admin_mpm_at', 'revisi_at',
      'lt_principal', 'lt_mpm', 'lt_admin_mpm', 'lt_revisi'
    ));
    $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 

    $this->excel_generator->exportTo2007('Export Claim NKA'); 




  }

  public function export_ajuan_claim_nka_v3()
  {
    // echo "aa";
    // die;
    // $get_data = $this->model_management_claim->get_ajuan_claim_nka_for_export($this->session->userdata('username'), $this->created_by, $from, $to, $channel, $kategori); 

    // $this->excel_generator->set_query($get_data);
    // $this->excel_generator->set_header(array
    // (
    //   'nama_comp','nomor_ajuan','nomor_klaim','nomor_invoice','channel','kategori','key_account','periode_start','periode_end',
    //   'pic_nama','pic_email','nominal_dpp','keterangan','nama_status',
    //   'username_principal', 'email_principal', 'principal_nama_status', 'principal_keterangan',
    //   'username_mpm', 'email_mpm', 'mpm_nama_status', 'mpm_keterangan',
    //   'username_admin_mpm', 'email_admin_mpm', 'admin_mpm_nama_status', 'admin_mpm_keterangan',
    //   'on_duty_name', 'created_at', 'principal_at', 'mpm_at', 'admin_mpm_at', 'revisi_at',
    //   'lt_principal', 'lt_mpm', 'lt_admin_mpm', 'lt_revisi'
    // ));
    // $this->excel_generator->set_column(array
    // ( 
    //   'nama_comp','nomor_ajuan','nomor_klaim','nomor_invoice','channel','kategori','key_account','periode_start','periode_end',
    //   'pic_nama','pic_email','nominal_dpp','keterangan','nama_status',
    //   'username_principal', 'email_principal', 'principal_nama_status', 'principal_keterangan',
    //   'username_mpm', 'email_mpm', 'mpm_nama_status', 'mpm_keterangan',
    //   'username_admin_mpm', 'email_admin_mpm', 'admin_mpm_nama_status', 'admin_mpm_keterangan',
    //   'on_duty_name', 'created_at', 'principal_at', 'mpm_at', 'admin_mpm_at', 'revisi_at',
    //   'lt_principal', 'lt_mpm', 'lt_admin_mpm', 'lt_revisi'
    // ));
    // $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 

    // $this->excel_generator->exportTo2007('Export Claim NKA'); 


    // $query = $this->model_management_claim->test();       
    $query = "
            select 	a.id
            from management_claim.registrasi_program a 
            limit 10
        ";

        $data = $this->db->query($query);

        //export to csv

        $this->excel_generator->set_query($data);
        $this->excel_generator->set_header(array
        (
            'id',
        ));
        $this->excel_generator->set_column(array
        ( 
            'id'
        ));
        $this->excel_generator->set_width(array(10)); 
        $this->excel_generator->exportTo2007('export claim');


  }


  public function ajuan_claim_nka_save()
  {
    $channel = $this->input->post('channel');
    $kategori = $this->input->post('kategori');
    $key_account = $this->input->post('key_account');
    $site_code = $this->input->post('site_code');
    $attachment = $this->upload_persyaratan_retur_nka($kategori);
    $nomor_ajuan = $this->model_management_claim->generate_nka($this->created_at);
    $signature = 'ajuan-claim-nka'.rand().md5($this->created_at.rand());

    // echo "channel: ".$channel."<br>";
    // echo "kategori: ".$kategori."<br>";
    // echo "key_account: ".$key_account."<br>";
    // echo "site_code: ".$site_code."<br>";
    // die;

    $get_data= $this->model_management_claim->get_master_region_nka_by_channel_and_key_account($channel, $key_account);
    foreach($get_data->result() as $data){
      $pic_principal[] = $data->pic_principal;
      $username_principal[] = $data->username_principal;
      $email_principal[] = $data->email_principal;
      $pic_mpm[] = $data->pic_mpm;
      $username_mpm[] = $data->username_mpm;
      $email_mpm[] = $data->email_mpm;
      $pic_admin_mpm[] = $data->pic_admin_mpm;
      $username_admin_mpm[] = $data->username_admin_mpm;
      $email_admin_mpm[] = $data->email_admin_mpm;
    }
    $pic_principal = implode(',', $pic_principal);
    $username_principal = implode(',', $username_principal);
    $email_principal = implode(',', $email_principal);
    $pic_mpm = implode(',', $pic_mpm);
    $username_mpm = implode(',', $username_mpm);
    $email_mpm = implode(',', $email_mpm);
    $pic_admin_mpm = implode(',', $pic_admin_mpm);
    $username_admin_mpm = implode(',', $username_admin_mpm);
    $email_admin_mpm = implode(',', $email_admin_mpm);

    // echo "pic_principal : ". $pic_principal;
    // die;
    $get_tabcomp = $this->model_management_claim->get_tabcomp_by_site_code($site_code);
    if ($get_tabcomp->num_rows > 0) {
      $nama_comp = $get_tabcomp->row()->nama_comp;
    }else{
      // tampilkan pesan gagal, redirect 
      $this->session->set_flashdata('error', 'Site code tidak ditemukan');
      redirect('management_claim/ajuan_claim_nka');
      die;
    }

    // jika channel = NKA, maka status = 1, jika pharma maka status menjadi 5
    if($channel == 'nka' || $channel == 'nka_herbana')
    {
      $status = 1; // pending kam principal
    }elseif($channel == 'pharma')
    {
      $status = 5; // pending kam mpm
    }

    // $status = 1;
    $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
    
    $data = [
      'nomor_ajuan'   => $nomor_ajuan,
      'nomor_klaim'   => $this->input->post('no_klaim'),
      'nomor_invoice' => $this->input->post('no_invoice'),
      'channel'       => $channel,
      'kategori'      => $kategori,
      'key_account'   => !empty($key_account) ? $key_account : null,
      'keterangan'    => $this->input->post('keterangan'),
      'nominal_dpp'   => $this->input->post('nominal_dpp'),
      'pic_nama'      => $this->input->post('nama'),
      'pic_email'     => $this->input->post('email'),
      'site_code'     => $site_code,
      'nama_comp'     => $nama_comp,
      'periode_start' => $this->input->post('from'),
      'periode_end'   => $this->input->post('to'),
      'attachment'    => $attachment,
      'status'        => $status,
      'nama_status'   => $nama_status,
      'pic_on_duty'   => $pic_principal,
      'pic_principal' => $pic_principal,
      'username_principal' => $username_principal,
      'email_principal' => $email_principal,
      'pic_mpm'       => $pic_mpm,
      'username_mpm'  => $username_mpm,
      'email_mpm'     => $email_mpm,
      'pic_admin_mpm' => $pic_admin_mpm,
      'username_admin_mpm' => $username_admin_mpm,
      'email_admin_mpm' => $email_admin_mpm,
      'signature'     => $signature,
      'created_at'    => $this->created_at,
      'created_by'    => $this->created_by
    ];
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
    // die;

    $insert_id = $this->model_management_claim->insert_and_getId('management_claim.ajuan_claim_nka',$data); 
    
    // menambahkan insert_id ke dalam $data
    $data['id_ajuan'] = $insert_id;
    
    $data_log = [
        'id_ajuan'      => $insert_id,
        'status'        => $status,
        'nama_status'   => $nama_status,
        'keterangan'    => $this->input->post('keterangan'),
        'signature'     => 'log-ajuan-claim-nka'.rand().md5($this->created_at.rand()),
        'created_at'    => $this->created_at,
        'created_by'    => $this->created_by,
        "updated_at"    => $this->created_at,
        "updated_by"    => $this->created_by,
        'pic_on_duty'   => $pic_principal,
        'on_duty_finish'    => 0,
    ];

    $insert_id_log = $this->model_management_claim->insert_and_getId('management_claim.log_aktivitas_claim_nka',$data_log);

    $this->email_pengajuan_claim_nka_v2($data);
    // redirect('management_claim/ajuan_claim_nka');
  }

  public function email_pengajuan_claim_nka_v2($data)
  {
    if ($data['status'] == 1) {
        $email_to = $data['email_principal'];
        $data['username_to'] = $data['username_principal'];
        $email_cc = $data['email_mpm'] . ',' . $data['email_admin_mpm'];
        $username_cc = $data['username_mpm'] . ',' . $data['username_admin_mpm'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        $data['keterangan'] = $data['keterangan'];
    }elseif ($data['status'] == 11) { // reject kam principal, maka email to = pic pembuat klaim
        $email_to = $data['pic_email'];
        $data['username_to'] = $data['nama_comp'];
        $email_cc = $data['email_mpm'] . ',' . $data['email_admin_mpm'] . ',' . $data['email_principal'];
        $username_cc = $data['username_mpm'] . ',' . $data['username_admin_mpm'] . ',' . $data['username_principal'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        // $data['keterangan'] = $data['principal_keterangan'];
        $data['keterangan'] = $data['keterangan'];
    }elseif ($data['status'] == 2) { // pending mpm
        $email_to = $data['email_mpm'];
        $data['username_to'] = $data['username_mpm'];
        $email_cc = $data['pic_email'] . ',' . $data['email_admin_mpm'] . ',' . $data['email_principal'];
        // $username_cc = $data['pic_nama'] . ',' . $data['username_admin_mpm'] . ',' . $data['username_principal'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        $data['keterangan'] = $data['keterangan'];
        // $data['keterangan'] = $data['principal_keterangan'];
    }elseif ($data['status'] == 12) { // reject mpm, maka email to = pic pembuat klaim
        $email_to = $data['pic_email'];
        $data['username_to'] = $data['nama_comp'];
        $email_cc = $data['email_mpm'] . ',' . $data['email_admin_mpm'] . ',' . $data['email_principal'];
        $username_cc = $data['username_mpm'] . ',' . $data['username_admin_mpm'] . ',' . $data['username_principal'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        $data['keterangan'] = $data['mpm_keterangan'];
    }elseif ($data['status'] == 3) { // pending admin mpm
        $email_to = $data['email_admin_mpm'];
        $data['username_to'] = $data['username_admin_mpm'];
        $email_cc = $data['pic_email'] . ',' . $data['email_mpm'] . ',' . $data['email_principal'];
        // $username_cc = $data['pic_nama'] . ',' . $data['username_admin_mpm'] . ',' . $data['username_principal'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        $data['keterangan'] = $data['keterangan'];
    }elseif ($data['status'] == 13) { // reject admin mpm, maka email to = pic pembuat klaim
        $email_to = $data['pic_email'];
        $data['username_to'] = $data['nama_comp'];
        $email_cc = $data['email_mpm'] . ',' . $data['email_admin_mpm'] . ',' . $data['email_principal'];
        // $username_cc = $data['username_mpm'] . ',' . $data['username_admin_mpm'] . ',' . $data['username_principal'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        // $data['keterangan'] = $data['admin_mpm_keterangan'];
        $data['keterangan'] = $data['keterangan'];
    }elseif ($data['status'] == 4) { // approve admin mpm
        $email_to = $data['pic_email'];
        $data['username_to'] = $data['nama_comp'];
        $email_cc = $data['email_mpm'] . ',' . $data['email_admin_mpm'] . ',' . $data['email_principal'];
        // $username_cc = $data['username_mpm'] . ',' . $data['username_admin_mpm'] . ',' . $data['username_principal'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        // $data['keterangan'] = $data['admin_mpm_keterangan'];
        $data['keterangan'] = $data['keterangan'];
    }elseif ($data['status'] == 5) {
        $email_to = $data['email_principal'];
        $data['username_to'] = $data['username_principal'];
        $email_cc = $data['email_mpm'] . ',' . $data['email_admin_mpm'];
        $username_cc = $data['username_mpm'] . ',' . $data['username_admin_mpm'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        $data['keterangan'] = $data['keterangan'];
    }elseif ($data['status'] == 6) { // pending principal
        $email_to = $data['email_mpm'];
        $data['username_to'] = $data['username_mpm'];
        $email_cc = $data['pic_email'] . ',' . $data['email_admin_mpm'] . ',' . $data['email_principal'];
        // $username_cc = $data['pic_nama'] . ',' . $data['username_admin_mpm'] . ',' . $data['username_principal'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        $data['keterangan'] = $data['keterangan'];
        // $data['keterangan'] = $data['principal_keterangan'];
    }elseif ($data['status'] == 16) { // reject principal, maka email to = pic pembuat klaim
        $email_to = $data['pic_email'];
        $data['username_to'] = $data['nama_comp'];
        $email_cc = $data['email_mpm'] . ',' . $data['email_admin_mpm'] . ',' . $data['email_principal'];
        $username_cc = $data['username_mpm'] . ',' . $data['username_admin_mpm'] . ',' . $data['username_principal'];
        $nomor_ajuan = $data['nomor_ajuan'];
        $nama_status = $data['nama_status'];
        $data['keterangan'] = $data['mpm_keterangan'];
    }

    $data['link_web_mpm'] = base_url('management_claim/ajuan_claim_nka_detail/' . $data['signature']);

    $email = [
        'from'      => 'suffy@muliaputramandiri.net',
        'email_to'  => $email_to,
        'email_cc'  => $email_cc,
        'subject'   => "MPM SITE | Claim NKA :" . $nomor_ajuan . " | " . $nama_status,
        'message'   => $this->load->view("management_claim/email_pengajuan_claim_nka",$data,TRUE)
    ];

    // echo "<pre>";
    // print_r($email);
    // echo "</pre>";
    // die;

    $this->config_email_claim_nka($data, $email);
    redirect('management_claim/ajuan_claim_nka');

  }

  function config_email_claim_nka($data, $email)
  {
    $this->load->model('model_relokasi');
    $config = $this->model_relokasi->email();

    $this->email->from($config['smtp_user'],'PT. Mulia Putra Mandiri');
    $this->email->to($email['email_to']);
    $this->email->cc($email['email_cc']);
    $this->email->subject($email['subject']);
    $this->email->message($email['message']);
    // $send = $this->email->send();

    // echo "<pre>";
    // print_r($this->email->print_debugger());
    // echo "</pre>";
    // die;

    if (strpos($this->email->print_debugger(), 'successfully') > 0) {
        $status_email      = 1;
        $nama_status_email = 'Terkirim';
        
        $this->session->set_flashdata("pesan_success", "Data berhasil disimpan dan email berhasil terkirim. Terima kasih");
    }else{
        $status_email       = 9;
        $nama_status_email  = 'Gagal Terkirim';
        $this->session->set_flashdata("pesan", "Data berhasil disimpan namun email tidak terkirim. Untuk memastikannya cek kembali data anda di menu Claim ini. Terima kasih");
    }
  }

  public function email_pengajuan_claim_nka($signature)
  {
      $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
      if ($get_data->num_rows() == 0) {
          return false;
      }
      
      $get_pic = $this->model_management_claim->get_master_region_nka_by_channel_and_key_account($get_data->row()->channel, $get_data->row()->key_account);
      foreach ($get_pic->result() as $pic_principal){
          $email_kam[] = $pic_principal->email_kam;
      }

      $email_kam = implode(',', $email_kam);

      if ($get_data->row()->status == 1) { // pending kam, user kirim email ke principal
          $username = $get_pic->row()->username_kam;
          $email = $email_kam;
          $email_cc = $get_pic->row()->email_mpm . ','  . $get_pic->row()->email_admin_mpm . ',' . $get_data->row()->pic_email . ',' . $get_data->row()->email_dp;
      } else if ($get_data->row()->status == 2) { // pending MPM, kam kirim email ke MPM
          $username = $get_pic->row()->username_mpm;
          $email = $get_pic->row()->email_mpm;
          $email_cc = $email_kam . ','  . $get_pic->row()->email_admin_mpm . ',' . $get_data->row()->pic_email . ',' . $get_data->row()->email_dp;
      } else if ($get_data->row()->status == 4) { // pending Admin MPM, pic mpm kirim email ke admin MPM
          $username = $get_pic->row()->username_admin_mpm;
          $email = $get_pic->row()->email_admin_mpm;
          $email_cc = $get_pic->row()->email_mpm . ','  . $email_kam . ',' . $get_data->row()->pic_email . ',' . $get_data->row()->email_dp;
      } else { // reject
          $username = $get_pic->row()->pic_nama;
          $email = $get_data->row()->pic_email . ',' . $get_data->row()->email_dp;
          $email_cc = $get_pic->row()->email_mpm . ','  . $email_kam . ',' . $get_data->row()->pic_email . ',' . $get_data->row()->email_dp;
      }
      
      $get_log = $this->model_management_claim->get_log_aktivitas_nka_by_id_ajuan($get_data->row()->id);
      $data = [
          'get_data'      => $get_data,
          'get_data_log'  => $get_log,
          'username'      => $username
      ];
      
      foreach ($get_log->result() as $key_log => $value) {
          foreach ($this->model_management_claim->get_username($value->created_by)->result()as $key_username) {
              $user[$key_log][] = $key_username->username ;
          }
          foreach ($this->model_management_claim->get_username($value->pic_on_duty)->result()as $key_username) {
              $pic[$key_log][]= $key_username->username ;
          }
      }

      $data['user'] = $user;
      $data['pic'] = $pic;

      $email =[
          'from'      => 'suffy@muliaputramandiri.com',
          'to'        => $email,
          // 'to'        => 'ilhammsyah@gmail.com',
          'email_cc'  => $email_cc,
          // 'email_cc'  => 'ilhammsyah@gmail.com',
          'subject'   => "MPM SITE | Claim :" . $get_data->row()->nomor_ajuan . " | " . $get_data->row()->nama_status,
          'message'   => $this->load->view("management_claim/email_pengajuan_claim_nka",$data,TRUE)
      ];

      $this->config_email_claim_nka($data, $email);
  }

  public function ajuan_claim_nka_detail($signature)
  {
    $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
    if($get_data->num_rows() < 1){
      $this->session->set_flashdata('pesan', 'Maaf, Data tidak ditemukan');
      redirect('management_claim/ajuan_claim_nka');
    }

    $username_on_duty = $get_data->row()->username_on_duty;
    // echo "username_on_duty : ".$username_on_duty;

    if($username_on_duty == $this->session->userdata('username')){
      $can_approve = true;
    }else{
      $can_approve = false;
    }
    // echo "can_approve : ".$can_approve;

    $status = $get_data->row()->status;
    // echo "status : ".$status;
    // die;
    if($status == 1) // //pending kam principal
    {
        $view = 'management_claim/form_response_nka';
        $url = 'management_claim/proses_nka_kam';
    }elseif($status == 2)
    {
        $view = 'management_claim/form_response_nka';
        $url = 'management_claim/proses_nka_mpm';
    }elseif($status == 3)
    {
        $view = 'management_claim/form_response_nka';
        $url = 'management_claim/proses_nka_admin_mpm';
    }elseif($status == 4)
    {
        $view = 'management_claim/form_response_nka';
        $url = 'management_claim/proses_nka_admin_mpm';
    }elseif($status == 5)
    {
        $view = 'management_claim/form_response_nka';
        $url = 'management_claim/proses_nka_kam';
    }elseif($status == 6)
    {
        $view = 'management_claim/form_response_nka';
        $url = 'management_claim/proses_nka_mpm';
    }elseif($status == 11)
    {
        $view = 'management_claim/form_revisi_nka';
        $url = 'management_claim/proses_nka_revisi';
    }elseif($status == 12)
    {
        $view = 'management_claim/form_revisi_nka';
        $url = 'management_claim/proses_nka_revisi';
    }elseif($status == 13)
    {
        $view = 'management_claim/form_revisi_nka';
        $url = 'management_claim/proses_nka_revisi';
    }elseif($status == 15)
    {
        $view = 'management_claim/form_revisi_nka';
        $url = 'management_claim/proses_nka_revisi';
    }elseif($status == 16)
    {
        $view = 'management_claim/form_revisi_nka';
        $url = 'management_claim/proses_nka_revisi';
    }

    // echo "status : ".$status;
    // echo "view : ".$view;
    // echo "url : ".$url;

    $get_log = $this->model_management_claim->get_log_aktivitas_nka_by_id_ajuan($get_data->row()->id);
    $data = [
        'title'     => 'Ajuan Claim NKA Detail',
        'url'       => "$url",
        'signature' => "$signature",
        'get_log'   => $get_log,
        'get_data'  => $get_data,
        'can_approve'=> $can_approve,
        'username_on_duty'=> $username_on_duty,
        'status'    => $status,
        'nama_status' => $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status 
    ];

    foreach ($get_log->result() as $key_log => $value) {
        foreach ($this->model_management_claim->get_username($value->created_by)->result()as $key_username) {
            $user[$key_log][] = $key_username->username ;
        }
        foreach ($this->model_management_claim->get_username($value->pic_on_duty)->result()as $key_username) {
            $pic[$key_log][]= $key_username->username ;
        }
    }

    $data['user'] = $user;
    $data['pic'] = $pic;

    $this->render_multiple(
        array(
            'management_claim/accordion_nka',
            $view
        ),
        $data
    );

  }

    public function proses_nka_kam()
    {
        $signature = $this->input->post('signature');

        $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
        if($get_data->num_rows() < 1){
            $this->session->set_flashdata('pesan', 'Maaf, Data tidak ditemukan');
            redirect('management_claim/ajuan_claim_nka');
        }

        $channel = $get_data->row()->channel;
        $key_account = $get_data->row()->key_account;
        $pic_nama = $get_data->row()->pic_nama;
        $pic_userid = $get_data->row()->created_by;
        $pic_email = $get_data->row()->pic_email;
        $nama_comp = $get_data->row()->nama_comp;
        $nomor_ajuan = $get_data->row()->nomor_ajuan;
        $nomor_klaim = $get_data->row()->nomor_klaim;
        $nomor_invoice = $get_data->row()->nomor_invoice;
        $kategori = $get_data->row()->kategori;
        $site_code = $get_data->row()->site_code;
        $periode_start = $get_data->row()->periode_start;
        $periode_end = $get_data->row()->periode_end;
        $nominal_dpp = $get_data->row()->nominal_dpp;
        $keterangan = $get_data->row()->keterangan;

        // echo "channel : ".$channel;
        // echo "key_account : ".$key_account;

        // get pic onduty berikutnyaa 
        $get_data_pic= $this->model_management_claim->get_master_region_nka_by_channel_and_key_account($channel, $key_account);
        foreach($get_data_pic->result() as $data){
            $pic_principal[] = $data->pic_principal;
            $username_principal[] = $data->username_principal;
            $email_principal[] = $data->email_principal;
            $pic_mpm[] = $data->pic_mpm;
            $username_mpm[] = $data->username_mpm;
            $email_mpm[] = $data->email_mpm;
            $pic_admin_mpm[] = $data->pic_admin_mpm;
            $username_admin_mpm[] = $data->username_admin_mpm;
            $email_admin_mpm[] = $data->email_admin_mpm;
        }
        $pic_principal = implode(',', $pic_principal);
        $username_principal = implode(',', $username_principal);
        $email_principal = implode(',', $email_principal);
        $pic_mpm = implode(',', $pic_mpm);
        $username_mpm = implode(',', $username_mpm);
        $email_mpm = implode(',', $email_mpm);
        $pic_admin_mpm = implode(',', $pic_admin_mpm);
        $username_admin_mpm = implode(',', $username_admin_mpm);
        $email_admin_mpm = implode(',', $email_admin_mpm);

        $action = $this->input->post('action');

        // echo "action : ".$action;
        // die;

        if($action == 1) // jika approved
        {
            // jika channel == nka, maka status = 2, kalau pharma maka status = 6

            if($channel == 'nka')
            {
                $status = 2; // status menjadi pending mpm
                $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
                // $view = 'management_claim/form_response_nka';
                $pic_on_duty = $pic_mpm;
                $username_on_duty = $username_mpm;
                $pic_email = $email_mpm;
            }elseif($channel == 'nka_herbana'){
                $status = 6; // status menjadi pending principal
                $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
                // $view = 'management_claim/form_response_nka';
                $pic_on_duty = $pic_mpm;
                $username_on_duty = $username_mpm;
                $pic_email = $email_mpm;

                // die;
            }elseif($channel == 'pharma'){
                $status = 6; // status menjadi pending principal
                $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
                // $view = 'management_claim/form_response_nka';
                $pic_on_duty = $pic_mpm;
                $username_on_duty = $username_mpm;
                $pic_email = $email_mpm;
            }

            
        }elseif($action == 0) // jika reject 
        {
            // jika channel == nka, maka status = 11, jika pharma, maka status = 15

            // echo "channel : ".$channel;
            // die;
            if($channel == 'nka' || $channel == 'nka_herbana') {
                $status = 11; // reject kam principal
                $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
                // $view = 'management_claim/form_response_nka';
                $pic_on_duty = $pic_userid;
                $username_on_duty = $pic_nama;
                $pic_email = $pic_email;


                // echo "status : ".$status;
                // echo "nama_status : ".$nama_status;
                // echo "pic_on_duty : ".$pic_on_duty;
                // echo "username_on_duty : ".$username_on_duty;
                // echo "pic_email : ".$pic_email;
                // die;


            }elseif($channel == 'pharma'){
                $status = 15; // reject kam mpm
                $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
                // $view = 'management_claim/form_response_nka';
                $pic_on_duty = $pic_userid;
                $username_on_duty = $pic_nama;
                $pic_email = $pic_email;
            }   
        }

        $data = [
            'status'        => $status,
            'nama_status'   => $nama_status,
            'nomor_ajuan'   => $nomor_ajuan,
            'pic_on_duty'   => $pic_on_duty,
            'pic_email'     => $pic_email,
            'pic_nama'      => $pic_nama,
            'nama_comp'     => $nama_comp,
            'email_mpm'     => $email_mpm,
            'username_mpm'  => $username_mpm,
            'email_admin_mpm' => $email_admin_mpm,
            'username_admin_mpm' => $username_admin_mpm,
            'email_principal' => $email_principal,
            'pic_principal' => $pic_principal,
            'username_principal' => $this->model_management_claim->get_user($pic_principal)->row()->username,
            'principal_keterangan' => $this->input->post('keterangan'),
            'principal_at'  => $this->created_at,
            'principal_status'    => $action,
            'principal_nama_status'=> $action == 1 ? 'Approved' : 'Rejected',
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
            'signature'     => $signature,
            'nomor_klaim'   => $nomor_klaim,
            'nomor_invoice' => $nomor_invoice,
            'channel' => $channel,
            'kategori' => $kategori,
            'key_account' => $key_account,
            'site_code' => $site_code,
            'periode_start' => $periode_start,
            'periode_end' => $periode_end,
            'nominal_dpp' => $nominal_dpp,
            'keterangan' => $keterangan
        ];
        // echo "<pre>"; print_r($data); echo "</pre>";die;
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.ajuan_claim_nka', $data);

        $data_log = [
            'id_ajuan'      => $get_data->row()->id,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'keterangan'    => $this->input->post('keterangan'),
            'signature'     => 'log-ajuan-claim-nka'.rand().md5($this->created_at.rand()),
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->created_by,
            'pic_on_duty'   => $pic_on_duty,
            'on_duty_finish'    => 0,
        ];

        $insert_id = $this->model_management_claim->insert_and_getId('management_claim.log_aktivitas_claim_nka',$data_log);

        $this->email_pengajuan_claim_nka_v2($data);
        redirect("management_claim/ajuan_claim_nka_detail/$signature");
    }

    public function proses_nka_revisi()
    {
        $signature = $this->input->post('signature');

        $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
        if($get_data->num_rows() < 1){
            $this->session->set_flashdata('pesan', 'Maaf, Data tidak ditemukan');
            redirect('management_claim/ajuan_claim_nka');
        }

        $nomor_ajuan = $get_data->row()->nomor_ajuan;
        $no_klaim = $this->input->post('no_klaim');
        $no_invoice = $this->input->post('no_invoice');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $keterangan = $this->input->post('keterangan');
        $nominal_dpp = $this->input->post('nominal_dpp');
        $kategori = $this->input->post('kategori');
        $attachment = $this->upload_persyaratan_retur_nka($kategori);

        // die;
        $channel = $get_data->row()->channel;
        $key_account = $get_data->row()->key_account;
        $nama_comp = $get_data->row()->nama_comp;
        $site_code = $get_data->row()->site_code;

        // get pic onduty berikutnya
        $get_data_pic= $this->model_management_claim->get_master_region_nka_by_channel_and_key_account($channel, $key_account);
        foreach($get_data_pic->result() as $data){
            $pic_principal[] = $data->pic_principal;
            $username_principal[] = $data->username_principal;
            $email_principal[] = $data->email_principal;
            $pic_mpm[] = $data->pic_mpm;
            $username_mpm[] = $data->username_mpm;
            $email_mpm[] = $data->email_mpm;
            $pic_admin_mpm[] = $data->pic_admin_mpm;
            $username_admin_mpm[] = $data->username_admin_mpm;
            $email_admin_mpm[] = $data->email_admin_mpm;
        }
        $pic_principal = implode(',', $pic_principal);
        $username_principal = implode(',', $username_principal);
        $email_principal = implode(',', $email_principal);
        $pic_mpm = implode(',', $pic_mpm);
        $username_mpm = implode(',', $username_mpm);
        $email_mpm = implode(',', $email_mpm);
        $pic_admin_mpm = implode(',', $pic_admin_mpm);
        $username_admin_mpm = implode(',', $username_admin_mpm);
        $email_admin_mpm = implode(',', $email_admin_mpm);

        $pic_nama = $get_data->row()->pic_nama;
        $pic_email = $get_data->row()->pic_email;
        $pic_userid = $get_data->row()->created_by;
        $status = $get_data->row()->status;

        // echo "channel : ".$channel;
        // echo "key_account : ".$key_account;
        // echo "pic_nama : ".$pic_nama;
        // echo "pic_userid : ".$pic_userid;
        // echo "status : ".$status;
        // echo "pic_principal : ".$pic_principal;
        // echo "username_principal : ".$username_principal;
        // echo "pic_mpm : ".$pic_mpm;
        // echo "username_mpm : ".$username_mpm;
        // echo "pic_admin_mpm : ".$pic_admin_mpm;
        // echo "username_admin_mpm : ".$username_admin_mpm;

        if($status == 11) // REJECT KAM PRINCIPAL
        {
            $next_status = 1; // pending kam principal
            $pic_on_duty = $pic_principal;
            
        }elseif($status == 12) // REJECT MPM
        {
            $next_status = 2; // PENDING MPM
            $pic_on_duty = $pic_mpm;
        }elseif($status == 13) // REJECT ADMIN MPM
        {
            $next_status = 3; // PENDING ADMIN MPM
            $pic_on_duty = $pic_admin_mpm;
        }elseif($status == 15) // REJECT KAM MPM
        {
            $next_status = 5; // PENDING KAM MPM
            $pic_on_duty = $pic_principal;
        }elseif($status == 16) // REJECT PRINCIPAL
        {
            $next_status = 6; // PENDING KAM MPM
            $pic_on_duty = $pic_mpm;
        }elseif($status == 3) // PENDING ADMIN MPM
        {
            $next_status = 3; // PENDING ADMIN MPM
            $pic_on_duty = $pic_admin_mpm;
        }

        // echo "status : ".$status;
        // echo "next_status : ".$next_status;
        // echo "pic_on_duty : ".$pic_on_duty;

        // die;

        $data_update = [
            'nomor_ajuan'   => $nomor_ajuan,
            'nomor_klaim'   => $no_klaim,
            'nomor_invoice' => $no_invoice,
            'channel'       => $channel,
            'kategori'      => $kategori,
            'key_account'   => $key_account,
            'signature'     => $signature,
            'status'        => $next_status,
            'nama_status'   => $this->model_management_claim->get_status_claim_nka($next_status)->row()->nama_status,
            'pic_on_duty'   => $pic_on_duty,
            'pic_email'     => $pic_email,
            'pic_nama'      => $pic_nama,
            'pic_principal' => $pic_principal,
            'username_principal' => $username_principal,
            'email_principal' => $email_principal,
            'pic_mpm'       => $pic_mpm,
            'username_mpm'  => $username_mpm,
            'email_mpm'     => $email_mpm,
            'pic_admin_mpm' => $pic_admin_mpm,
            'username_admin_mpm' => $username_admin_mpm,
            'email_admin_mpm' => $email_admin_mpm,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
            'nama_comp'     => $nama_comp,
            'site_code'     => $site_code,
            'periode_start' => $from,
            'periode_end'   => $to,
            'keterangan'    => $keterangan,
            'nominal_dpp'   => $nominal_dpp,
            'attachment'    => $attachment, 
            'revisi_at'     => $this->created_at,
        ];

        // echo "<pre>";
        // echo "signature : ".$signature;
        // print_r($data_update);
        // echo "</pre>";
        // die;
        
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.ajuan_claim_nka', $data_update);

        $data_log = [
            'id_ajuan'      => $get_data->row()->id,
            'status'        => $next_status,
            'nama_status'   => $this->model_management_claim->get_status_claim_nka($next_status)->row()->nama_status,
            'keterangan'    => $this->input->post('keterangan'),
            'signature'     => 'log-ajuan-claim-nka'.rand().md5($this->created_at.rand()),
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->created_by,
            'pic_on_duty'   => $pic_on_duty,
            'on_duty_finish'=> 0,
        ];

        $insert_id = $this->model_management_claim->insert_and_getId('management_claim.log_aktivitas_claim_nka',$data_log);
        // die;
        
        $this->email_pengajuan_claim_nka_v2($data_update);
        redirect("management_claim/ajuan_claim_nka_detail/$signature");
    }

    public function dashboard_nka()
    {
        if($this->session->userdata('level') == '5'){
            redirect('management_claim/ajuan_claim_nka');
            return ;
        }
        // die;

        $this->load->model('model_management_inventory');

        $flag_delete = $this->input->get('flag_delete');
        
        if($this->input->get('submit') == 'search')
        {    
            $advanced['from']           = $this->input->get('from');
            $advanced['to']             = $this->input->get('to');
            $advanced['channel']        = $this->input->get('channel');
            $advanced['kategori']       = $this->input->get('kategori');
        }
        else
        {
            $advanced = null;
        }

        if($this->input->get('submit') == 'export')
        {
            $this->export_ajuan_claim_nka($advanced);
        }

        $data = [
            'title'         => 'Management Claim | Dashboard Ajuan Claim NKA',
            'url'           => 'management_claim/dashboard_nka',
            'url_akses'     => 'management_claim/ajuan_claim_nka_detail',
            'get_data'      => $this->model_management_claim->get_ajuan_claim_nka_by_search($advanced),
        ];

        $this->render('management_claim/dashboard_ajuan_claim_nka', $data);

    }

    public function export_ajuan_claim_nka($advanced)
    {
        $query = $this->model_management_claim->get_ajuan_claim_nka_by_search($advanced);            
        $this->excel_generator->set_query($query);
        $this->excel_generator->set_header(array
        (
            'No Klaim', 'No Invoice', 'Channel', 'Kategori', 'Periode', 'Katerangan', 'Nominal DPP', 'Site_Code', 'PIC', 'PIC_EMAIL', 'Status' 
        ));
        $this->excel_generator->set_column(array
        ( 
            'no_klaim', 'no_invoice', 'channel', 'kategori', 'periode', 'keterangan', 'nominal_dpp', 'site_code', 'pic_nama', 
            'pic_email', 'nama_status'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('export claim'); 
    }

    public function proses_nka_mpm()
    {
        $signature = $this->input->post('signature');
        $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
        if($get_data->num_rows() < 1){
            $this->session->set_flashdata('pesan', 'Maaf, Data tidak ditemukan');
            redirect('management_claim/ajuan_claim_nka');
        }

        $channel = $get_data->row()->channel;
        $key_account = $get_data->row()->key_account;
        $pic_nama = $get_data->row()->pic_nama;
        $pic_userid = $get_data->row()->created_by;
        $pic_email = $get_data->row()->pic_email;
        $nama_comp = $get_data->row()->nama_comp;
        $nomor_ajuan = $get_data->row()->nomor_ajuan;
        $nomor_klaim = $get_data->row()->nomor_klaim;
        $nomor_invoice = $get_data->row()->nomor_invoice;
        $kategori = $get_data->row()->kategori;
        $site_code = $get_data->row()->site_code;
        $periode_start = $get_data->row()->periode_start;
        $periode_end = $get_data->row()->periode_end;
        $nominal_dpp = $get_data->row()->nominal_dpp;
        $keterangan = $get_data->row()->keterangan;

        // echo "channel : ".$channel;
        // echo "key_account : ".$key_account;

        // get pic onduty berikutnyaa 
        $get_data_pic= $this->model_management_claim->get_master_region_nka_by_channel_and_key_account($channel, $key_account);
        foreach($get_data_pic->result() as $data){
            $pic_principal[] = $data->pic_principal;
            $username_principal[] = $data->username_principal;
            $email_principal[] = $data->email_principal;
            $pic_mpm[] = $data->pic_mpm;
            $username_mpm[] = $data->username_mpm;
            $email_mpm[] = $data->email_mpm;
            $pic_admin_mpm[] = $data->pic_admin_mpm;
            $username_admin_mpm[] = $data->username_admin_mpm;
            $email_admin_mpm[] = $data->email_admin_mpm;
        }
        $pic_principal = implode(',', $pic_principal);
        $username_principal = implode(',', $username_principal);
        $email_principal = implode(',', $email_principal);
        $pic_mpm = implode(',', $pic_mpm);
        $username_mpm = implode(',', $username_mpm);
        $email_mpm = implode(',', $email_mpm);
        $pic_admin_mpm = implode(',', $pic_admin_mpm);
        $username_admin_mpm = implode(',', $username_admin_mpm);
        $email_admin_mpm = implode(',', $email_admin_mpm);

        $action = $this->input->post('action');
        // echo "action : ".$action;
        // die;

        if($action == 1) // jika approved
        {
            $status = 3; // pending admin mpm
            $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
            // $view = 'management_claim/form_response_nka';
            $pic_on_duty = $pic_admin_mpm;
            $username_on_duty = $username_admin_mpm;
            $pic_email = $email_admin_mpm;
        }elseif($action == 0) // jika reject 
        {
            if($channel == 'nka'){
                $status = 12; // reject mpm
                $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
                // $view = 'management_claim/form_response_nka';
                $pic_on_duty = $pic_userid;
                $username_on_duty = $pic_nama;
                $pic_email = $pic_email;
            }elseif($channel == 'nka_herbana'){
                $status = 16; // reject principal
                $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
                // $view = 'management_claim/form_response_nka';
                $pic_on_duty = $pic_userid;
                $username_on_duty = $pic_nama;
                $pic_email = $pic_email;
            }elseif($channel == 'pharma'){
                $status = 16; // reject principal
                $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
                // $view = 'management_claim/form_response_nka';
                $pic_on_duty = $pic_userid;
                $username_on_duty = $pic_nama;
                $pic_email = $pic_email;
            }
            
        }

        // echo "status: ".$status;
        // echo "nama_status: ".$nama_status;
        // echo "pic_on_duty: ".$pic_on_duty;
        // echo "username_on_duty: ".$username_on_duty;
        // die;


        $data = [
            'status'        => $status,
            'nama_status'   => $nama_status,
            'nomor_ajuan'   => $nomor_ajuan,
            'pic_on_duty'   => $pic_on_duty,
            'pic_email'     => $pic_email,
            'pic_nama'      => $pic_nama,
            'nama_comp'     => $nama_comp,
            'email_mpm'     => $email_mpm,
            'username_mpm'  => $username_mpm,
            'email_admin_mpm' => $email_admin_mpm,
            'username_admin_mpm' => $username_admin_mpm,
            'email_principal' => $email_principal,
            'pic_principal' => $pic_principal,
            'username_principal' => $this->model_management_claim->get_user($pic_principal)->row()->username,
            // 'principal_keterangan' => $this->input->post('keterangan'),
            // 'principal_at'  => $this->created_at,
            // 'principal_status'    => $action,
            // 'principal_nama_status'=> $action == 1 ? 'Approved' : 'Rejected',
            'mpm_keterangan' => $this->input->post('keterangan'),
            'mpm_at'  => $this->created_at,
            'mpm_status'    => $action,
            'mpm_nama_status'=> $action == 1 ? 'Approved' : 'Rejected',
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
            'signature'     => $signature,
            'nomor_klaim'   => $nomor_klaim,
            'nomor_invoice' => $nomor_invoice,
            'channel' => $channel,
            'kategori' => $kategori,
            'key_account' => $key_account,
            'site_code' => $site_code,
            'periode_start' => $periode_start,
            'periode_end' => $periode_end,
            'nominal_dpp' => $nominal_dpp,
            'keterangan' => $keterangan,
        ];
        // echo "<pre>"; print_r($data); echo "</pre>";die;


        // $data = [
        //     'status'        => $status,
        //     'nama_status'   => $nama_status,
        //     'pic_on_duty'   => $pic_on_duty,
        //     'pic_mpm'       => $this->created_by,
        //     'username_mpm'  => $this->model_management_claim->get_user($this->created_by)->row()->username,
        //     'mpm_keterangan'=> $this->input->post('keterangan'),
        //     'mpm_at'        => $this->created_at,
        //     'mpm_status'    => $action,
        //     'mpm_nama_status'=> $action == 1 ? 'Approved' : 'Rejected',
        //     'updated_at'    => $this->created_at,
        //     'updated_by'    => $this->created_by,
        // ];
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die;
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.ajuan_claim_nka', $data);

        $data_log = [
            'id_ajuan'      => $get_data->row()->id,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'keterangan'    => $this->input->post('keterangan'),
            'signature'     => 'log-ajuan-claim-nka'.rand().md5($this->created_at.rand()),
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->created_by,
            'pic_on_duty'   => $pic_on_duty,
            'on_duty_finish'    => 0,
        ];

        $insert_id = $this->model_management_claim->insert_and_getId('management_claim.log_aktivitas_claim_nka',$data_log);

        $this->email_pengajuan_claim_nka_v2($data);
        
        redirect("management_claim/ajuan_claim_nka_detail/$signature");

        // $signature = $this->input->post('signature');
        // $flag_status = $this->input->post('status');
        // $status = $flag_status == 1 ? 4 : 5;
        // $nama_status = $flag_status == 1 ? 'pending admin mpm' : 'reject mpm (closed)';
        
        // $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
        // // cek status
        // if ($get_data->row()->status != 2) {
        //     $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan proses ini karena status pengajuan saat ini masih ' . $get_data->row()->nama_status);
        //     redirect("management_claim/ajuan_claim_nka_detail/$signature");
        // }

        // $filter = $this->model_management_claim->get_master_region_nka_by_channel_key_account_userid_role($get_data->row()->channel, $get_data->row()->key_account, $this->created_by, 'mpm');
        // if ($filter->num_rows() < 1) {
        //     $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses data claim ini !');
        //     redirect("management_claim/ajuan_claim_nka_detail/$signature");
        // }

        // $get_pic = $this->model_management_claim->get_master_region_nka_by_channel_and_key_account($get_data->row()->channel, $get_data->row()->key_account);
        // $pic = $flag_status == 1 ? $get_pic->row()->pic_admin_mpm : $get_data->row()->created_by;

        // $data = [
        //     'status'        => $status,
        //     'nama_status'   => $nama_status,
        //     'pic_on_duty'   => $pic,
        //     'pic_mpm'       => $this->session->userdata('id'),
        //     'keterangan_mpm' => $this->input->post('keterangan'),
        //     'mpm_at'        => $this->created_at,
        //     'updated_at'    => $this->created_at,
        //     'updated_by'    => $this->session->userdata('id'),
        // ];
        // $this->db->where('signature', $signature);
        // $this->db->update('management_claim.ajuan_claim_nka', $data);

        // $data_log = [
        //     'id_ajuan'      => $get_data->row()->id,
        //     'status'        => $status,
        //     'nama_status'   => $nama_status,
        //     'keterangan'    => $this->input->post('keterangan'),
        //     'signature'     => 'log-ajuan-claim-nka'.rand().md5($this->created_at.rand()),
        //     'created_at'    => $this->created_at,
        //     'created_by'    => $this->created_by,
        //     "updated_at"    => $this->created_at,
        //     "updated_by"    => $this->created_by,
        //     'pic_on_duty'   => $pic,
        //     'on_duty_finish'    => 0,
        // ];

        // $insert_id = $this->model_management_claim->insert_and_getId('management_claim.log_aktivitas_claim_nka',$data_log);
        // $this->email_pengajuan_claim_nka($signature);
        // redirect("management_claim/ajuan_claim_nka_detail/$signature");
    }

    public function proses_nka_admin_mpm()
    {
        $signature = $this->input->post('signature');
        $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
        if($get_data->num_rows() < 1){
            $this->session->set_flashdata('pesan', 'Maaf, Data tidak ditemukan');
            redirect('management_claim/ajuan_claim_nka');
        }

        $channel = $get_data->row()->channel;
        $key_account = $get_data->row()->key_account;
        $pic_nama = $get_data->row()->pic_nama;
        $pic_userid = $get_data->row()->created_by;
        $pic_email = $get_data->row()->pic_email;
        $nama_comp = $get_data->row()->nama_comp;
        $nomor_ajuan = $get_data->row()->nomor_ajuan;
        $nomor_klaim = $get_data->row()->nomor_klaim;
        $nomor_invoice = $get_data->row()->nomor_invoice;
        $kategori = $get_data->row()->kategori;
        $site_code = $get_data->row()->site_code;
        $periode_start = $get_data->row()->periode_start;
        $periode_end = $get_data->row()->periode_end;
        $nominal_dpp = $get_data->row()->nominal_dpp;
        $keterangan = $get_data->row()->keterangan;

        // echo "channel : ".$channel;
        // echo "key_account : ".$key_account;

        // get pic onduty berikutnyaa 
        $get_data_pic= $this->model_management_claim->get_master_region_nka_by_channel_and_key_account($channel, $key_account);
        foreach($get_data_pic->result() as $data){
            $pic_principal[] = $data->pic_principal;
            $username_principal[] = $data->username_principal;
            $email_principal[] = $data->email_principal;
            $pic_mpm[] = $data->pic_mpm;
            $username_mpm[] = $data->username_mpm;
            $email_mpm[] = $data->email_mpm;
            $pic_admin_mpm[] = $data->pic_admin_mpm;
            $username_admin_mpm[] = $data->username_admin_mpm;
            $email_admin_mpm[] = $data->email_admin_mpm;
        }
        $pic_principal = implode(',', $pic_principal);
        $username_principal = implode(',', $username_principal);
        $email_principal = implode(',', $email_principal);
        $pic_mpm = implode(',', $pic_mpm);
        $username_mpm = implode(',', $username_mpm);
        $email_mpm = implode(',', $email_mpm);
        $pic_admin_mpm = implode(',', $pic_admin_mpm);
        $username_admin_mpm = implode(',', $username_admin_mpm);
        $email_admin_mpm = implode(',', $email_admin_mpm);

        $action = $this->input->post('action');
        // echo "action : ".$action;
        // die;

        if($action == 1) // jika approved
        {
            $status = 4; // approved admin mpm
            $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
            // $view = 'management_claim/form_response_nka';
            $pic_on_duty = $pic_userid;
            $username_on_duty = $pic_nama;
            $pic_email = $pic_email;
        }elseif($action == 0) // jika reject 
        {
            // echo "ada disini";
            $status = 13; // reject admin mpm
            $nama_status = $this->model_management_claim->get_status_claim_nka($status)->row()->nama_status;
            // $view = 'management_claim/form_response_nka';
            $pic_on_duty = $pic_userid;
            $username_on_duty = $pic_nama;
            $pic_email = $pic_email;
        }

        $data = [
            'status'        => $status,
            'nama_status'   => $nama_status,
            'nomor_ajuan'   => $nomor_ajuan,
            'pic_on_duty'   => $pic_on_duty,
            'pic_email'     => $pic_email,
            'pic_nama'      => $pic_nama,
            'nama_comp'     => $nama_comp,
            'email_mpm'     => $email_mpm,
            'username_mpm'  => $username_mpm,
            'email_admin_mpm' => $email_admin_mpm,
            'username_admin_mpm' => $username_admin_mpm,
            'email_principal' => $email_principal,
            'pic_principal' => $pic_principal,
            'username_principal' => $this->model_management_claim->get_user($pic_principal)->row()->username,
            'admin_mpm_keterangan' => $this->input->post('keterangan'),
            'admin_mpm_at'  => $this->created_at,
            'admin_mpm_status'    => $action,
            'admin_mpm_nama_status'=> $action == 1 ? 'Approved' : 'Rejected',
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by,
            'signature'     => $signature,
            'nomor_klaim'   => $nomor_klaim,
            'nomor_invoice' => $nomor_invoice,
            'channel' => $channel,
            'kategori' => $kategori,
            'key_account' => $key_account,
            'site_code' => $site_code,
            'periode_start' => $periode_start,
            'periode_end' => $periode_end,
            'nominal_dpp' => $nominal_dpp,
            'keterangan' => $keterangan
        ];
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        // die;

        // die;
        $this->db->where('signature', $signature);
        $this->db->update('management_claim.ajuan_claim_nka', $data);

        $data_log = [
            'id_ajuan'      => $get_data->row()->id,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'keterangan'    => $this->input->post('keterangan'),
            'signature'     => 'log-ajuan-claim-nka'.rand().md5($this->created_at.rand()),
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->created_by,
            'pic_on_duty'   => $pic_on_duty,
            'on_duty_finish'    => 0,
        ];

        $insert_id = $this->model_management_claim->insert_and_getId('management_claim.log_aktivitas_claim_nka',$data_log);

        $this->email_pengajuan_claim_nka_v2($data);
        
        redirect("management_claim/ajuan_claim_nka_detail/$signature");
    }

    public function proses_nka_revisi_old()
    {
        $signature = $this->input->post('signature');
        $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
        // cek status
        if ($get_data->row()->status != 3 && $get_data->row()->status != 5 && $get_data->row()->status != 7) {
            $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan revisi karena status pengajuan saat ini masih ' . $get_data->row()->nama_status);
            redirect("management_claim/ajuan_claim_nka_detail/$signature");
        }
        
        $id_ajuan = $get_data->row()->id;
        $kategori = $this->input->post('kategori');
        $channel = $this->input->post('channel');
        $attachment = $this->upload_persyaratan_retur_nka($kategori);
        $data_log = $this->model_management_claim->get_log_aktivitas_nka_by_id_ajuan_with_sorting_desc($id_ajuan);

        // var_dump($data_log->row());die;

        $data = [
            'kategori'      => $kategori,
            'channel'       => $channel,
            'nominal_dpp'   => $this->input->post('nominal_dpp'),
            'periode_start' => $this->input->post('from'),
            'periode_end'   => $this->input->post('to'),
            'attachment'    => $attachment,
            'status'        => $data_log->row()->status,
            'nama_status'   => $data_log->row()->nama_status,
            'pic_on_duty'   => $data_log->row()->pic_on_duty,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->session->userdata('id'),
            ];

        $this->db->where('signature', $signature);
        $this->db->update('management_claim.ajuan_claim_nka', $data);

        $data_log_insert = [
            'id_ajuan'      => $id_ajuan,
            'status'        => $data_log->row()->status,
            'nama_status'   => $data_log->row()->nama_status,
            'keterangan'    => $this->input->post('keterangan'),
            'signature'     => 'log-ajuan-claim-nka'.rand().md5($this->created_at.rand()),
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->created_by,
            'pic_on_duty'   => $data_log->row()->pic_on_duty,
            'on_duty_finish'    => 0,
        ];

        $insert_id = $this->model_management_claim->insert_and_getId('management_claim.log_aktivitas_claim_nka',$data_log_insert);
        $this->email_pengajuan_claim_nka_revisi($signature);
        redirect("management_claim/ajuan_claim_nka_detail/$signature");
    }

  public function upload_persyaratan_retur_nka($kategori)
  {
    // echo "kategori : $kategori";
    // echo "<pre>";
    // print_r($_FILES);
    // echo "</pre>";

    // die;

    // upload file
    if (!is_dir('./assets/uploads/management_claim/nka')) {
        @mkdir('./assets/uploads/management_claim/nka', 0777);
    }

    if (!is_dir("./assets/uploads/management_claim/nka/$kategori")) {
        @mkdir("./assets/uploads/management_claim/nka/$kategori", 0777);
    }

    $this->load->library('upload'); // Load librari upload  
    $upload_path = "./assets/uploads/management_claim/nka/$kategori";
    $filename = [];

    // Loop semua file input yang ada di $_FILES
    foreach ($_FILES as $input_name => $file_info) {
        // Pastikan file input ada dan file diupload (nama file tidak kosong)
        if (!empty($file_info['name'])) {
            $ext = pathinfo($file_info['name'], PATHINFO_EXTENSION);
            $original_name = pathinfo($file_info['name'], PATHINFO_FILENAME);

            // Buat nama file baru, gabungkan nama asli + timestamp
            $new_filename = "$input_name - $original_name.$ext";

            $config = [
                'upload_path'   => $upload_path,
                'allowed_types' => '*',
                'max_size'      => '*',
                'file_name'     => $new_filename,
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload($input_name)) {
                $upload_data = $this->upload->data();
                $filename[$input_name] = $upload_data['file_name'];
            } else {
                $error = $this->upload->display_errors();
                // Bisa simpan log error atau set flashdata
            }
        } else {
            $filename[$input_name] = $this->input->post($input_name.'_old');
        }
    }

    $attachment = json_encode($filename);

    // echo $attachment;
    // die;

    return $attachment;
  }

  public function master_kategori_nka()
  {
    $channel = $this->input->post('channel');
    $response = $this->model_management_claim->get_master_kategori_nka($channel);
    if(!$response || $response->num_rows() == 0) {
      echo "<option value=''> -- kategori tidak ditemukan -- </option>";
      return;
    }

    $options = "<option value=''> -- Pilih Kategori -- </option>";       
    foreach ($response->result_array() as $row) {
      $nama_kategori = isset($row['nama_kategori']) ? $row['nama_kategori'] : '';
      $value = htmlspecialchars($nama_kategori, ENT_QUOTES, 'UTF-8');
      $display = htmlspecialchars($nama_kategori, ENT_QUOTES, 'UTF-8');   
      $options .= "<option value='".$nama_kategori."'>".$display."</option>";
    }
    echo $options;
  }

  public function master_key_account()
  {
    $channel = $this->input->post('channel');
    
    $response = $this->model_management_claim->get_key_account_by_channel($channel);
    if(!$response || $response->num_rows() == 0) {
      echo "<option value=''> -- key account tidak ditemukan -- </option>";
      return;
    }

    $options = "<option value=''> -- Pilih Key Account -- </option>";       
    foreach ($response->result_array() as $row) {
      $key_account = isset($row['key_account']) ? $row['key_account'] : '';
      $value = htmlspecialchars($key_account, ENT_QUOTES, 'UTF-8');
      $display = htmlspecialchars($key_account, ENT_QUOTES, 'UTF-8');   
      $options .= "<option value='".$key_account."'>".$display."</option>";
    }
    echo $options;

  }
    
    public function email_pengajuan_claim_nka_revisi($signature)
    {
        $get_data = $this->model_management_claim->get_ajuan_claim_nka_by_signature($signature);
        $get_pic = $this->model_management_claim->get_master_region_nka_by_channel_and_key_account($get_data->row()->channel, $get_data->row()->key_account);
        $data_log = $this->model_management_claim->get_log_aktivitas_nka_by_id_ajuan_with_sorting_desc($get_data->row()->id);
        $get_log = $this->model_management_claim->get_log_aktivitas_nka_by_id_ajuan($get_data->row()->id);

        $data = [
            'get_data'      => $get_data,
            'get_data_log'  => $this->model_management_claim->get_log_aktivitas_nka_by_id_ajuan($get_data->row()->id),
            'username'      => $data_log->row()->username
        ];

        foreach ($get_log->result() as $key_log => $value) {
            foreach ($this->model_management_claim->get_username($value->created_by)->result()as $key_username) {
                $user[$key_log][] = $key_username->username ;
            }
            foreach ($this->model_management_claim->get_username($value->pic_on_duty)->result()as $key_username) {
                $pic[$key_log][]= $key_username->username ;
            }
        }

        $data['user'] = $user;
        $data['pic'] = $pic;

        $email =[
            'from'      => 'suffy@muliaputramandiri.com',
            'to'        => $data_log->row()->email,
            'email_cc'  => $get_pic->row()->email_mpm . ','  . $get_pic->row()->email_kam . ',' . $get_data->row()->pic_email . ',' . $get_data->row()->email_dp,
            // 'to'        => 'ilhammsyah@gmail.com',
            // 'email_cc'  => 'ilhammsyah@gmail.com',
            'subject'   => "MPM SITE | Revisi Claim :" . $get_data->row()->nomor_ajuan . " | " . $get_data->row()->nama_status,
            'message'   => $this->load->view("management_claim/email_pengajuan_claim_nka",$data,TRUE)
        ];

        $this->config_email_claim_nka($data, $email);
    }
    

    public function report_availability()
    {
        $supp = $this->input->post('supp');
        $kategori = $this->input->post('kategori');
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        // echo "supp : " . $supp . "<br>";
        // echo "kategori : " . $kategori . "<br>";
        // echo "from : " . $from . "<br>";
        // echo "to : " . $to . "<br>";

        if($supp && $kategori && $from && $to){
            $get_program = $this->model_management_claim->get_registrasi_program_by_supp_kategori_periode_none_ajuan($supp, $kategori, $from, $to);
        }else{
            $get_program = [];
        }

        $data = [
            'title' => 'Monitoring Klaim Cabang',
            'get_principal' => $this->model_management_claim->get_principal(),
            'url' => 'management_claim/report_availability',
            'url_search' => 'management_claim/report_availability_search',
            'get_program' => $get_program
        ];        
        $this->render('management_claim/report_availability', $data);   
    }

    public function report_availability_search()
    {
        $this->load->model(array('model_spk','model_management_sales'));
        $id_program = implode(",", $this->input->post('options'));
        // echo $id_program;

        // jika count id_program lebih dari 3 maka die
        if (count(explode(",", $id_program)) > 5) {
            $this->session->set_flashdata("pesan", "Pilih maksimal 5 program");
            redirect('management_claim/report_availability');
        }

        $site_code = $this->model_spk->get_site_code($this->session->userdata('id'));
        // echo $site_code;
        // die;

        $hasil = $this->model_management_claim->get_availability($id_program, $site_code);
        // if($hasil->num_rows() > 0){
        //     $hasil = $hasil->result();
        // }else{
        //     $hasil = [];
        // }

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'nomor_surat', 'nama_program', 'site_code', 'branch_name', 'nama_comp', 'nomor_ajuan',
            'email_pengirim', 'nama_pengirim', 'nama_status', 'status_keikutsertaan',
            'on_duty', 'created_at', 'updated_at'
        ));
        $this->excel_generator->set_column(array
        (
            'nomor_surat', 'nama_program', 'site_code', 'branch_name', 'nama_comp', 'nomor_ajuan',
            'email_pengirim', 'nama_pengirim', 'nama_status', 'status_keikutsertaan',
            'on_duty', 'created_at', 'updated_at'
        ));
        $this->excel_generator->set_width(array(15,15,15,15,15,15,15,15,15,15,15,15,15));
        $this->excel_generator->exportTo2007('report_availability');

    }


}
?>
