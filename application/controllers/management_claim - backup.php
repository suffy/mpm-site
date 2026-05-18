<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_claim extends MY_Controller
{    
    function management_claim()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_claim','M_helpdesk','model_inventory'));

        $cek_auth_user = $this->model_management_claim->get_sitecode($this->session->userdata('id'));

        if (!$cek_auth_user->num_rows() > 0) {
            echo "anda tidak diizinkan mengakses menu ini<br>";
            ?>
            <a href="<?= base_url() ?>"> klik disini untuk logout</a>
            <?php 
            die;
        }

    }
    function index()
    {
        $this->ajuan_claim();
    }

    function navbar($data){
        if ($this->session->userdata('level') === '4') { // jika dp
            // echo "a";
            // die;
            $this->load->view('management_office/top_header_dp', $data);
        }elseif ($this->session->userdata('level') === '3') { // jika principal
            // echo "b";
            // die;
            $this->load->view('management_office/top_header_principal', $data);
        }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales
            // echo "c";
            // die;
            $this->load->view('management_office/top_header_principal_nosales', $data);
        }else{
            // echo "d";
            // die;
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function dashboard(){
        $data = [
            'title'                 => 'management claim | dashboard',
            'get_registrasi_program'=> $this->model_management_claim->get_registrasi_program_by_id(),
            'get_site_code_by_program'=> $this->model_management_claim->get_site_code_by_program(),
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    public function registrasi_program(){
        $data = [
            'title'                 => 'management claim | registrasi program',
            'get_registrasi_program'=> $this->model_management_claim->get_registrasi_program_by_id(),
            'url'                   => 'management_claim/registrasi_program_save'
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/registrasi_program', $data);
        $this->load->view('kalimantan/footer');
    }

    public function registrasi_program_save(){
        $kategori       = $this->input->post('kategori');
        $supp           = $this->input->post('supp');
        $nama_program   = $this->input->post('nama_program');
        $syarat         = $this->input->post('syarat');
        $from           = $this->input->post('from');
        $to             = $this->input->post('to');
        $nomor_surat    = $this->input->post('nomor_surat');
        $duedate        = $this->input->post('duedate');

        $created_at     = $this->model_outlet_transaksi->timezone();
        $signature      = md5($this->model_outlet_transaksi->timezone());

        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('template_program')) 
        {
            $upload_data = $this->upload->data();
            $filename_template_program = $upload_data['orig_name'];

        }else{
            // var_dump($this->upload->display_errors());
            // die;
        };

        if ($this->upload->do_upload('upload_jpg')) 
        {
            $upload_data = $this->upload->data();
            $filename_jpg = $upload_data['orig_name'];

        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_jpg = '';
        };

        if ($this->upload->do_upload('upload_pdf')) 
        {
            $upload_data = $this->upload->data();
            $filename_pdf = $upload_data['orig_name'];

        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        $data = [
            'kategori'      => $kategori,
            'supp'          => $supp,
            'from'          => $from,
            'to'            => $to,
            'nama_program'  => $nama_program,
            'nomor_surat'   => $nomor_surat,
            'syarat'        => $syarat,
            'duedate'       => $duedate,
            'upload_jpg'    => $filename_jpg,
            'upload_template_program'    => $filename_template_program,
            'upload_pdf'    => $filename_pdf,
            'signature'     => $signature,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id'),
        ];

        $proses = $this->db->insert('management_claim.registrasi_program', $data);
        if ($proses) {
            $this->session->set_flashdata("pesan_success", "registrasi program berhasil");
            redirect('management_claim/registrasi_program/'.$signature);
        }

    }

    public function ajuan_claim(){

        if($this->input->get('from')){
            
            $advanced['from']   = $this->input->get('from');
            $advanced['to']     = $this->input->get('to');
            $advanced['supp']   = $this->input->get('supp');
            $advanced['site_code']   = '';
            $advanced['kategori']   = '';
        
        }else{
            $advanced = "";
        }

        $data = [
            'title'     => 'management claim | ajuan claim',
            'url'       => 'ajuan_claim',
            'get_data'  => $this->model_management_claim->get_registrasi_program_by_supp_date($advanced)
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/ajuan_claim', $data);
        $this->load->view('kalimantan/footer');

    }

    public function form_ajuan_claim($signature_program){

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
            $supp                       = $get_registrasi_program_by_signature->row()->supp;
            $username                   = $this->model_master_data->get_username_by_id($get_registrasi_program_by_signature->row()->created_by)->row()->username;
        }

        if ($kategori == 'bonus_barang') {
            $url = 'management_claim/import_bonus_barang';
        }elseif ($kategori == 'diskon_herbal') {
            $url = 'management_claim/import_diskon';
        }else{
            $url = 'management_claim/ajuan_claim_save';
        }

        $get_ajuan_claim_by_id_program_and_user = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->session->userdata('id'));
        if ($get_ajuan_claim_by_id_program_and_user->num_rows > 0) 
        {
            $get_ajuan_claim_by_id_program = $this->model_management_claim->get_ajuan_claim_by_id_program($id_program);

            if ($get_ajuan_claim_by_id_program->num_rows > 0) {
                $nama_comp          = $this->model_master_data->get_tabcomp_by_site_code($get_ajuan_claim_by_id_program->row()->site_code)->row()->nama_comp;
                $nama_pengirim      = $get_ajuan_claim_by_id_program->row()->nama_pengirim;
                $email_pengirim     = $get_ajuan_claim_by_id_program->row()->email_pengirim;
                $ajuan_excel        = $get_ajuan_claim_by_id_program->row()->ajuan_excel;
                $ajuan_zip          = $get_ajuan_claim_by_id_program->row()->ajuan_zip;
                $created_at         = $get_ajuan_claim_by_id_program->row()->created_at;
                $nama_status        = $get_ajuan_claim_by_id_program->row()->nama_status;
                $signature_ajuan    = $get_ajuan_claim_by_id_program->row()->signature;
                
                $id_verifikasi      = $get_ajuan_claim_by_id_program->row()->id_verifikasi;

            }else{
                $nama_comp          = "";
                $nama_pengirim      = "";
                $email_pengirim     = "";
                $ajuan_excel        = "";
                $ajuan_zip          = "";
                $created_at         = "";
                $nama_status        = "";
                $signature_ajuan    = "";
                $id_verifikasi      = "";
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

        }else{
            $nama_comp          = "";
            $nama_pengirim      = "";
            $email_pengirim     = "";
            $ajuan_excel        = "";
            $ajuan_zip          = "";
            $created_at         = "";
            $nama_status        = "";
            $signature_ajuan    = "";
            $id_verifikasi      = "";
            
            $verifikasi_signature   = "";
            $verifikasi_keterangan  = "";
            $verifikasi_file        = "";
            $verifikasi_created_at  = "";
            $verifikasi_username    = "";

        }

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
            'signature_ajuan'           => $signature_ajuan,    
            'supp'                      => $supp,    
            'site_code'                 => $this->model_management_claim->get_sitecode($this->session->userdata('id')),
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/accordion', $data);
        $this->load->view('management_claim/form_ajuan_claim', $data);
        $this->load->view('kalimantan/footer');

    }

    public function ajuan_claim_save(){
        $site_code          = $this->input->post('site_code');
        $branch_name        = $this->input->post('branch_name');
        $nama_comp          = $this->input->post('nama_comp');
        $nama_pengirim      = $this->input->post('nama_pengirim');
        $email_pengirim     = $this->input->post('email_pengirim');
        $ajuan_excel        = $this->input->post('ajuan_excel');
        $ajuan_zip          = $this->input->post('ajuan_zip');
        $signature_program  = $this->input->post('signature_program');

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = md5($this->model_outlet_transaksi->timezone());

        $id_program = $this->model_management_claim->get_registrasi_program('', $signature_program)->row()->id;

        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('ajuan_excel')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['orig_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        if ($this->upload->do_upload('ajuan_zip')) 
        {
            $upload_data = $this->upload->data();
            $filename_zip = $upload_data['orig_name'];

        }else{
            // var_dump($this->upload->display_errors());
            // die;
        };

        $this->db->trans_start();
        $data = [
            'nomor_ajuan'       => $this->model_management_claim->generate($this->input->post('from_site'), $created_at),
            'branch_name'       => $branch_name,
            'nama_comp'         => $nama_comp,
            'site_code'         => $site_code,
            'nama_pengirim'     => $nama_pengirim,
            'email_pengirim'    => $email_pengirim,
            'ajuan_excel'       => $ajuan_excel,
            'ajuan_zip'         => $ajuan_zip,
            'ajuan_excel'       => $filename_excel,
            'ajuan_zip'         => $filename_zip,
            'id_program'        => $id_program,
            'status'            => 1,
            'nama_status'       => 'PENDING MPM',
            'signature'         => $signature,
            'created_at'        => $created_at,
            'created_by'        => $this->session->userdata('id'),
        ];

        $this->db->insert('management_claim.ajuan_claim', $data);
        // $id_ajuan = $this->db->insert_id();

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan update. silahkan ulangi kembali";
            redirect('management_claim/ajuan_claim/'.$signature_program);
            die;
        }

        $this->session->set_flashdata("pesan_success", "Pengajuan claim berhasil");
        redirect('management_claim/form_ajuan_claim/'.$signature_program);
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

        $this->db->trans_start();
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

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan update. rollback active";
            die;
        }

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
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('ajuan_revisi')) 
        {
            $upload_data = $this->upload->data();
            $filename_revisi = $upload_data['orig_name'];

        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        $cek = $this->model_management_claim->cek_revisi_by_signature_ajuan($signature_ajuan);
        if ($cek->num_rows() > 0) {
            
            $this->db->trans_start();
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
            $this->db->trans_complete();

        }else{

            $this->db->trans_start();
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
            $this->db->trans_complete();

        }

        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan update revisi. rollback active";
            die;
        }

        redirect('management_claim/ajuan_claim');

    }

    public function validasi_excel($sign){

    }

    public function verifikasi_mpm($signature_program, $signature_ajuan){

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
            $id_verifikasi  = $get_ajuan_by_signature->row()->id_verifikasi;
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
            'title'                     => 'management claim | Verifikasi MPM',
            'url'                       => 'management_claim/verifikasi_mpm_save',
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
            'nama_comp'                 => $nama_comp,      
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
            'site_code'                 => $this->model_management_claim->get_sitecode($this->session->userdata('id')),   
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/accordion', $data);
        $this->load->view('management_claim/verifikasi_mpm', $data);
        $this->load->view('kalimantan/footer');

    }

    public function verifikasi_mpm_save(){

        $status             = $this->input->post('status');
        $keterangan         = $this->input->post('keterangan');
        $signature_program  = $this->input->post('signature_program');
        $signature_ajuan    = $this->input->post('signature_ajuan');
        $created_at         = $this->model_outlet_transaksi->timezone();
        $signature          = md5($this->model_outlet_transaksi->timezone());


        $get_registrasi_program_by_signature = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
        $id_program         = $get_registrasi_program_by_signature->row()->id;

        // echo "id_program : ".$id_program;
        // echo "signature_ajuan : ".$signature_ajuan;
        // die;

        $get_ajuan_claim_by_id_program = $this->model_management_claim->get_ajuan_claim_by_id_program_and_signature($id_program, $signature_ajuan);
        $id_ajuan           = $get_ajuan_claim_by_id_program->row()->id;
        $nomor_ajuan        = $get_ajuan_claim_by_id_program->row()->nomor_ajuan;

        $nama_status        = $this->model_management_claim->get_status($status);

        if (!is_dir('./assets/uploads/management_claim/')) {
            @mkdir('./assets/uploads/management_claim/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename = '';
        };

        $this->db->trans_start();
        $data = [
            'id_program'         => $id_program,
            'id_ajuan'           => $id_ajuan,
            'nomor_ajuan'        => $nomor_ajuan,
            'keterangan'         => $keterangan,
            'status'             => $status,
            'nama_status'        => $nama_status,
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

        // echo "status : ".$status;
        // echo "params_created_at : ".$params_created_at;
        // echo "id_ajuan : ".$id_ajuan;

        // die;

        $update = [
            'status'           => $status,
            'nama_status'      => $nama_status,
            'keterangan_mpm'   => $keterangan,
            'file_mpm'         => $filename,
            'pic_mpm'          => $this->session->userdata('id'),
            'mpm_at'           => $created_at,
            'last_updated_at'  => $created_at,
            'id_verifikasi'    => $id_verifikasi,
            'created_at'       => $params_created_at,
        ];

        $this->db->where('id', $id_ajuan);
        $this->db->update('management_claim.ajuan_claim', $update);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan update. silahkan ulangi kembali";
            redirect('management_claim/verifikasi_mpm/'.$signature_program);
        }

        // $this->session->set_flashdata("pesan_success", "update berhasil");
        // redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);

        redirect('management_claim/email_status/'.$signature_program.'/'.$signature_ajuan);
        die;
        
    }

    public function routing($signature_program, $signature_ajuan = ''){
        // cek status
        $get_ajuan_by_signature = $this->model_management_claim->get_ajuan_by_signature($signature_ajuan);
        if ($get_ajuan_by_signature->num_rows() > 0) {
            $status = $get_ajuan_by_signature->row()->status;
        }else{
            $status = 0;
        }        

        // echo "status : ".$status;
        // echo "signature_program : ".$signature_program;
        // die;

        if ($status == 0) { //belum mengajukan claim
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/form_ajuan_claim/'.$signature_program);
            }else{                
                redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
            }
        }elseif ($status == 1 || $status == 2) { //pending mpm
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/form_ajuan_claim/'.$signature_program);
            }else{                
                redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
            }
        }elseif($status == 3){ //reject mpm
            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_claim/form_ajuan_claim/'.$signature_program);
            }else{                
                redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature_ajuan);
            }
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
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('revisi_excel')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['orig_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        if ($this->upload->do_upload('revisi_zip')) 
        {
            $upload_data = $this->upload->data();
            $filename_zip = $upload_data['orig_name'];
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        $this->db->trans_start();
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

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan revisi. silahkan ulangi kembali";
            redirect('management_claim/form_revisi_claim/'.$signature_program.'/'.$signature_ajuan);
            die;
        }

        $this->session->set_flashdata("pesan_success", "Revisi claim berhasil");
        redirect('management_claim/form_revisi_claim/'.$signature_program.'/'.$signature_ajuan);
    }

    public function export_template_bonus_barang(){

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
        $this->excel_generator->exportTo2007('Template Claim khusus Bonus Barang'); 
    }

    public function export_template_diskon(){

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
        $this->excel_generator->exportTo2007('Template Claim khusus Diskon'); 
    }
    
    public function import_bonus_barang(){

        $this->load->model(array('model_management_inventory', 'model_master_data'));

        $site_code_header   = $this->input->post('site_code');
        $branch_name        = $this->input->post('branch_name');
        $nama_comp          = $this->input->post('nama_comp');
        $nama_pengirim      = $this->input->post('nama_pengirim');
        $email_pengirim     = $this->input->post('email_pengirim');
        $supp               = $this->input->post('supp');
        $ajuan_excel        = $this->input->post('ajuan_excel');
        $ajuan_zip          = $this->input->post('ajuan_zip');
        $signature_program  = $this->input->post('signature_program');
        $signature = 'CLAIMIMPORT-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/management_claim/import/')) {
            @mkdir('./assets/uploads/management_claim/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/import/';
        // $config['allowed_types'] = 'xls|xlsx';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '5120';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('ajuan_zip')) 
        {
            $upload_data = $this->upload->data();
            $orig_name_zip = $upload_data['orig_name'];
            $file_name_zip = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $file_name_zip = '';
        };

        if ($this->upload->do_upload('ajuan_excel')) 
        {
            $upload_data = $this->upload->data();
            $orig_name = $upload_data['orig_name'];
            $file_name = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_claim/import/$file_name");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('mes','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 100000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 100 ROW.");
                    redirect('management_claim/form_ajuan_claim/'.$signature_program);
                }

                for ($row = 2; $row <= $highestRow; $row++) {          
                
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
                    $get_namaprod = $this->model_master_data->get_product_by_kodeprod_n_supp($kodeprodx, $supp);

                    if ($get_namaprod->num_rows() > 0) {
                        $namaprod = $get_namaprod->row()->NAMAPROD; 
                        $validasi_kodeprod = 0;  
                    }else{
                        $namaprod = '';
                        $validasi_kodeprod = 1;
                    }

                    // cek sub
                    $get_tabcomp = $this->model_master_data->get_tabcomp_by_site_code($site_code_header);

                    if ($get_tabcomp->num_rows() > 0) {
                        $sub = $get_tabcomp->row()->sub;
                    }else{
                        $sub = '';
                    }

                    // validasi tabcomp
                    $get_tabcomp_by_site_code_and_sub = $this->model_master_data->get_tabcomp_by_site_code_and_sub($site_code, $sub);

                    if ($get_tabcomp_by_site_code_and_sub->num_rows() > 0) {
                        $nama_comp = $get_tabcomp_by_site_code_and_sub->row()->nama_comp;
                        $branch_name = $get_tabcomp_by_site_code_and_sub->row()->branch_name;
                        $validasi_site_code = 0;
                    }else{
                        $nama_comp = '';
                        $branch_name = '';
                        $validasi_site_code = 1;
                    }
                    // validasi nomor surat program
                    $get_program = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
                    if ($get_program->num_rows() > 0) {
                        $nomor_surat = $get_program->row()->nomor_surat;
                        if ($nomor_surat == $nomor_surat_program) {
                            $id_program = $get_program->row()->id;
                            $nama_program = $get_program->row()->nama_program;
                            $validasi_nomor_surat = 0;
                        }else{
                            $id_program = '';
                            $nama_program = '';
                            $validasi_nomor_surat = 1;
                        }
                    }
                    // validasi class
                    $get_tabsalur = $this->model_master_data->get_tabsalur_by_kode_class($kode_class);
                    if ($get_tabsalur->num_rows() > 0) {
                        $nama_class = $get_tabsalur->row()->group;
                        $validasi_class = 0;
                    }else{
                        $nama_class = '';
                        $validasi_class = 1;
                    }

                    $validasi_row = $validasi_kodeprod + $validasi_nomor_surat + $validasi_site_code + $validasi_class;

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
                        'site_code_header'         => $site_code_header,
                        'branch_name'              => $branch_name,
                        'nama_comp'                => $nama_comp,
                        'nama_pengirim'            => $nama_pengirim,
                        'email_pengirim'           => $email_pengirim,
                        'ajuan_excel'              => $file_name,
                        'ajuan_zip'                => $file_name_zip,
                    ];
                    $this->db->insert('management_claim.import_bonus_barang',$data);
                }
            }

            $this->session->set_flashdata("pesan_success", "Import Successfully. Cek kembali data anda di bawah ini");
            redirect('management_claim/preview_import_bonus_barang/'.$signature_program.'/'.$signature);

        }else{

            $this->session->set_flashdata("pesan", "Import Gagal :".$this->upload->display_errors());
            redirect('management_claim/form_ajuan_claim/'.$signature_program.'/'.$signature);
        };

    }

    public function preview_import_bonus_barang($signature_program, $signature){

        $get_count_validasi_failed = $this->model_management_claim->get_count_validasi_failed($signature_program, $signature);
        if ($get_count_validasi_failed->num_rows() > 0) {
            $total_failed = $get_count_validasi_failed->row()->total;
        }else{
            $total_failed = 0;
        }

        $get_count_import = $this->model_management_claim->get_count_import($signature_program, $signature);
        if ($get_count_import->num_rows() > 0) {
            $total_row = $get_count_import->row()->total;
        }else{
            $total_row = 0;
        }

        $get_count_validasi_success = $this->model_management_claim->get_count_validasi_success($signature_program, $signature);
        if ($get_count_validasi_success->num_rows() > 0) {
            $total_success = $get_count_validasi_success->row()->total;
        }else{
            $total_success = 0;
        }

        $get_sum_import_bonus = $this->model_management_claim->get_sum_import_bonus($signature_program, $signature);
        if ($get_sum_import_bonus->num_rows() > 0) {
            $total_qty_jual     = $get_sum_import_bonus->row()->total_qty_jual;
            $total_qty_bonus    = $get_sum_import_bonus->row()->total_qty_bonus;
            $total_value_jual   = $get_sum_import_bonus->row()->total_value_jual ;
            $total_value_bonus  = $get_sum_import_bonus->row()->total_value_bonus ;
        }else{
            $total_success = 0;
        }

        $get_data = $this->model_management_claim->get_preview_import($signature_program, $signature);
        if ($get_data->num_rows() > 0) {
            $nama_pengirim = $get_data->row()->nama_pengirim;
            $email_pengirim = $get_data->row()->email_pengirim;
        }else{
            $nama_pengirim = '';
            $email_pengirim = '';
        }

        $data = [
            'title'                 => 'management claim | preview import claim bonus barang',
            'url'                   => 'management_claim/import_bonus_barang_save',
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
            'get_preview_import'    => $this->model_management_claim->get_preview_import_failed($signature_program, $signature),
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/accordion', $data);
        $this->load->view('management_claim/preview_import_bonus_barang', $data);
        $this->load->view('kalimantan/footer');

    }

    public function proses_pengajuan($signature_program, $signature){

        $this->load->model('model_master_data');

        // validasi apakah semua row tidak ada error
        $get_count_import = $this->model_management_claim->get_count_import($signature_program, $signature);
        if ($get_count_import->num_rows() > 0) {
            $total_row = $get_count_import->row()->total;
        }else{
            $total_row = 0;
        }

        $get_count_validasi_success = $this->model_management_claim->get_count_validasi_success($signature_program, $signature);
        if ($get_count_validasi_success->num_rows() > 0) {
            $total_success = $get_count_validasi_success->row()->total;
        }else{
            $total_success = 0;
        }

        $get_preview_import = $this->model_management_claim->get_preview_import($signature_program, $signature);
        if ($get_preview_import->num_rows() > 0) {
            $site_code_header = $get_preview_import->row()->site_code_header;

            $branch_name            = $this->model_master_data->get_tabcomp_by_site_code($site_code_header)->row()->branch_name;
            $nama_comp              = $this->model_master_data->get_tabcomp_by_site_code($site_code_header)->row()->nama_comp;

            $nama_pengirim          = $get_preview_import->row()->nama_pengirim;
            $email_pengirim         = $get_preview_import->row()->email_pengirim;
            $ajuan_excel            = $get_preview_import->row()->ajuan_excel;
            $ajuan_zip              = $get_preview_import->row()->ajuan_zip;
            $id_program             = $get_preview_import->row()->id_program;
            $signature_import       = $get_preview_import->row()->signature;
            $created_at             = $get_preview_import->row()->created_at;
        }

        if ($total_row == $total_success) {

            $get_ajuan_claim_by_id_program_and_user = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->session->userdata('id'));

            if ($get_ajuan_claim_by_id_program_and_user->num_rows() > 0) 
            {
                $get_ajuan_claim_by_id_program = $this->model_management_claim->get_ajuan_claim_by_id_program($id_program);
                $signature_ajuan = $get_ajuan_claim_by_id_program->row()->signature;

                if ($get_ajuan_claim_by_id_program->num_rows() > 0) {
                    // update data
                    $data = [
                        'branch_name'       => $branch_name,
                        'nama_comp'         => $nama_comp,
                        'site_code'         => $site_code_header,
                        'nama_pengirim'     => $nama_pengirim,
                        'email_pengirim'    => $email_pengirim,
                        'ajuan_excel'       => $ajuan_excel,
                        'ajuan_zip'         => $ajuan_zip,
                        'id_program'        => $id_program,
                        'status'            => 2,
                        'nama_status'       => 'PENDING MPM',
                        'created_at'        => $created_at,
                        'created_by'        => $this->session->userdata('id'),
                    ];
                    $this->db->where('signature', $signature_ajuan);
                    $this->db->update('management_claim.ajuan_claim', $data);
                    redirect('management_claim/email_status/'.$signature_program.'/'.$signature_ajuan);
                    die;

                }
            }else{
                
                // insert data
                $data = [
                    'nomor_ajuan'       => $this->model_management_claim->generate($this->input->post('from_site'), $created_at),
                    'branch_name'       => $branch_name,
                    'nama_comp'         => $nama_comp,
                    'site_code'         => $site_code_header,
                    'nama_pengirim'     => $nama_pengirim,
                    'email_pengirim'    => $email_pengirim,
                    'ajuan_excel'       => $ajuan_excel,
                    'ajuan_zip'         => $ajuan_zip,
                    'id_program'        => $id_program,
                    'status'            => 2,
                    'nama_status'       => 'PENDING MPM',
                    'signature'         => $signature,
                    'created_at'        => $created_at,
                    'created_by'        => $this->session->userdata('id'),
                ];
                $this->db->insert('management_claim.ajuan_claim', $data);
                redirect('management_claim/email_status/'.$signature_program.'/'.$signature);
                die;
            }

        }else{
            $this->session->set_flashdata("pesan", "Import Failed. Harap infokan ini ke IT");
            redirect('management_claim/preview_import_bonus_barang/'.$signature_program.'/'.$signature);
        }
    }

    public function import_diskon(){

        $this->load->model(array('model_management_inventory', 'model_master_data'));

        $site_code_header   = $this->input->post('site_code');
        $branch_name        = $this->input->post('branch_name');
        $nama_comp          = $this->input->post('nama_comp');
        $nama_pengirim      = $this->input->post('nama_pengirim');
        $email_pengirim     = $this->input->post('email_pengirim');
        $supp               = $this->input->post('supp');
        $ajuan_excel        = $this->input->post('ajuan_excel');
        $ajuan_zip          = $this->input->post('ajuan_zip');
        $signature_program  = $this->input->post('signature_program');
        $signature = 'CLAIMIMPORT-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/management_claim/import/')) {
            @mkdir('./assets/uploads/management_claim/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_claim/import/';
        // $config['allowed_types'] = 'xls|xlsx';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '5120';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('ajuan_zip')) 
        {
            $upload_data = $this->upload->data();
            $orig_name_zip = $upload_data['orig_name'];
            $file_name_zip = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $file_name_zip = '';
        };

        if ($this->upload->do_upload('ajuan_excel')) 
        {
            $upload_data = $this->upload->data();
            $orig_name = $upload_data['orig_name'];
            $file_name = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_claim/import/$file_name");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('mes','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 100000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 100 ROW.");
                    redirect('management_claim/form_ajuan_claim/'.$signature_program);
                }

                for ($row = 2; $row <= $highestRow; $row++) {          
                
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
                    $get_namaprod = $this->model_master_data->get_product_by_kodeprod_n_supp($kodeprodx, $supp);

                    if ($get_namaprod->num_rows() > 0) {
                        $namaprod = $get_namaprod->row()->NAMAPROD; 
                        $validasi_kodeprod = 0;  
                    }else{
                        $namaprod = '';
                        $validasi_kodeprod = 1;
                    }

                    // cek sub
                    $get_tabcomp = $this->model_master_data->get_tabcomp_by_site_code($site_code_header);

                    if ($get_tabcomp->num_rows() > 0) {
                        $sub = $get_tabcomp->row()->sub;
                    }else{
                        $sub = '';
                    }

                    // validasi tabcomp
                    $get_tabcomp_by_site_code_and_sub = $this->model_master_data->get_tabcomp_by_site_code_and_sub($site_code, $sub);

                    if ($get_tabcomp_by_site_code_and_sub->num_rows() > 0) {
                        $nama_comp = $get_tabcomp_by_site_code_and_sub->row()->nama_comp;
                        $branch_name = $get_tabcomp_by_site_code_and_sub->row()->branch_name;
                        $validasi_site_code = 0;
                    }else{
                        $nama_comp = '';
                        $branch_name = '';
                        $validasi_site_code = 1;
                    }
                    // validasi nomor surat program
                    $get_program = $this->model_management_claim->get_registrasi_program_by_signature($signature_program);
                    if ($get_program->num_rows() > 0) {
                        $nomor_surat = $get_program->row()->nomor_surat;
                        if ($nomor_surat == $nomor_surat_program) {
                            $id_program = $get_program->row()->id;
                            $nama_program = $get_program->row()->nama_program;
                            $validasi_nomor_surat = 0;
                        }else{
                            $id_program = '';
                            $nama_program = '';
                            $validasi_nomor_surat = 1;
                        }
                    }
                    // validasi class
                    $get_tabsalur = $this->model_master_data->get_tabsalur_by_kode_class($kode_class);
                    if ($get_tabsalur->num_rows() > 0) {
                        $nama_class = $get_tabsalur->row()->group;
                        $validasi_class = 0;
                    }else{
                        $nama_class = '';
                        $validasi_class = 1;
                    }

                    $validasi_row = $validasi_kodeprod + $validasi_nomor_surat + $validasi_site_code + $validasi_class;

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
                        'created_by'               => $this->session->userdata('id'),
                        'signature'                => $signature,
                        'signature_program'        => $signature_program,
                        'site_code_header'         => $site_code_header,
                        'branch_name'              => $branch_name,
                        'nama_comp'                => $nama_comp,
                        'nama_pengirim'            => $nama_pengirim,
                        'email_pengirim'           => $email_pengirim,
                        'ajuan_excel'              => $file_name,
                        'ajuan_zip'                => $file_name_zip,
                    ];
                    $this->db->insert('management_claim.import_diskon',$data);
                }
            }

            $this->session->set_flashdata("pesan_success", "Import Successfully. Cek kembali data anda di bawah ini");
            redirect('management_claim/preview_import_diskon/'.$signature_program.'/'.$signature);

        }else{

            $this->session->set_flashdata("pesan", "Import Gagal :".$this->upload->display_errors());
            redirect('management_claim/form_ajuan_claim/'.$signature_program.'/'.$signature);
        };

    }

    public function preview_import_diskon($signature_program, $signature){

        $get_count_validasi_failed = $this->model_management_claim->get_count_validasi_failed_diskon($signature_program, $signature);
        if ($get_count_validasi_failed->num_rows() > 0) {
            $total_failed = $get_count_validasi_failed->row()->total;
        }else{
            $total_failed = 0;
        }

        $get_count_import = $this->model_management_claim->get_count_import_diskon($signature_program, $signature);
        if ($get_count_import->num_rows() > 0) {
            $total_row = $get_count_import->row()->total;
        }else{
            $total_row = 0;
        }

        $get_count_validasi_success = $this->model_management_claim->get_count_validasi_success_diskon($signature_program, $signature);
        if ($get_count_validasi_success->num_rows() > 0) {
            $total_success = $get_count_validasi_success->row()->total;
        }else{
            $total_success = 0;
        }

        $get_sum_import_diskon = $this->model_management_claim->get_sum_import_diskon($signature_program, $signature);
        if ($get_sum_import_diskon->num_rows() > 0) {
            $total_qty_jual             = $get_sum_import_diskon->row()->total_qty_jual;
            $total_value_jual           = $get_sum_import_diskon->row()->total_value_jual;
            $total_disc_principal   = $get_sum_import_diskon->row()->total_disc_principal;
            $total_disc_cabang          = $get_sum_import_diskon->row()->total_disc_cabang;
            $total_disc_extra           = $get_sum_import_diskon->row()->total_disc_extra;
            $total_disc_cash            = $get_sum_import_diskon->row()->total_disc_cash;
            $total_disc_claim           = $get_sum_import_diskon->row()->total_disc_claim;
        }else{
            $total_success = 0;
        }

        $get_data = $this->model_management_claim->get_preview_import_diskon($signature_program, $signature);
        if ($get_data->num_rows() > 0) {
            $nama_pengirim  = $get_data->row()->nama_pengirim;
            $email_pengirim = $get_data->row()->email_pengirim;
        }else{
            $nama_pengirim  = '';
            $email_pengirim = '';
        }

        $data = [
            'title'                         => 'management claim | preview import claim diskon',
            'url'                           => 'management_claim/import_diskon_save',
            'total_failed'                  => $total_failed,
            'total_row'                     => $total_row,
            'total_success'                 => $total_success,
            'total_qty_jual'                => $total_qty_jual,
            'total_qty_jual'                => $total_qty_jual,
            'total_value_jual'              => $total_value_jual,
            'total_disc_principal'          => $total_disc_principal,
            'total_disc_cabang'             => $total_disc_cabang,
            'total_disc_extra'              => $total_disc_extra,
            'total_disc_cash'               => $total_disc_cash,
            'total_disc_claim'              => $total_disc_claim,
            'signature_program'             => $signature_program,
            'signature'                     => $signature,
            'nama_pengirim'                 => $nama_pengirim,
            'email_pengirim'                => $email_pengirim,
            'get_preview_import'            => $this->model_management_claim->get_preview_import_failed_diskon($signature_program, $signature),
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/accordion', $data);
        $this->load->view('management_claim/preview_import_diskon', $data);
        $this->load->view('kalimantan/footer');

    }

    public function proses_pengajuan_diskon($signature_program, $signature){

        $this->load->model('model_master_data');

        // validasi apakah semua row tidak ada error
        $get_count_import = $this->model_management_claim->get_count_import_diskon($signature_program, $signature);
        if ($get_count_import->num_rows() > 0) {
            $total_row = $get_count_import->row()->total;
        }else{
            $total_row = 0;
        }

        $get_count_validasi_success = $this->model_management_claim->get_count_validasi_success_diskon($signature_program, $signature);
        if ($get_count_validasi_success->num_rows() > 0) {
            $total_success = $get_count_validasi_success->row()->total;
        }else{
            $total_success = 0;
        }

        $get_preview_import = $this->model_management_claim->get_preview_import_diskon($signature_program, $signature);
        if ($get_preview_import->num_rows() > 0) {
            $site_code_header = $get_preview_import->row()->site_code_header;

            $branch_name            = $this->model_master_data->get_tabcomp_by_site_code($site_code_header)->row()->branch_name;
            $nama_comp              = $this->model_master_data->get_tabcomp_by_site_code($site_code_header)->row()->nama_comp;

            $nama_pengirim          = $get_preview_import->row()->nama_pengirim;
            $email_pengirim         = $get_preview_import->row()->email_pengirim;
            $ajuan_excel            = $get_preview_import->row()->ajuan_excel;
            $ajuan_zip              = $get_preview_import->row()->ajuan_zip;
            $id_program             = $get_preview_import->row()->id_program;
            $signature_import       = $get_preview_import->row()->signature;
            $created_at             = $get_preview_import->row()->created_at;
        }

        // echo "signature_import : ".$signature_import;
        // die;

        if ($total_row == $total_success) {

            // cek apakah sudah ada ajuan claim
            $get_ajuan_claim_by_id_program_and_user = $this->model_management_claim->get_ajuan_claim_by_id_program_and_user($id_program, $this->session->userdata('id'));
            // die;
            if ($get_ajuan_claim_by_id_program_and_user->num_rows() > 0) 
            {
                $get_ajuan_claim_by_id_program = $this->model_management_claim->get_ajuan_claim_by_id_program($id_program);
                $signature_ajuan = $get_ajuan_claim_by_id_program->row()->signature;
                // echo "signature_ajuan : ".$signature_ajuan;
                // die;
                if ($get_ajuan_claim_by_id_program->num_rows() > 0) {
                    // update data
                    $data = [
                        'branch_name'       => $branch_name,
                        'nama_comp'         => $nama_comp,
                        'site_code'         => $site_code_header,
                        'nama_pengirim'     => $nama_pengirim,
                        'email_pengirim'    => $email_pengirim,
                        'ajuan_excel'       => $ajuan_excel,
                        'ajuan_zip'         => $ajuan_zip,
                        'id_program'        => $id_program,
                        'status'            => 2,
                        'nama_status'       => 'PENDING MPM',
                        'created_at'        => $created_at,
                        'created_by'        => $this->session->userdata('id'),
                    ];
                    $this->db->where('signature', $signature_ajuan);
                    $this->db->update('management_claim.ajuan_claim', $data);

                    redirect('management_claim/email_status/'.$signature_program.'/'.$signature_ajuan);
                    die;
                }
            }else{
                // insert data
                $data = [
                    'nomor_ajuan'       => $this->model_management_claim->generate($this->input->post('from_site'), $created_at),
                    'branch_name'       => $branch_name,
                    'nama_comp'         => $nama_comp,
                    'site_code'         => $site_code_header,
                    'nama_pengirim'     => $nama_pengirim,
                    'email_pengirim'    => $email_pengirim,
                    'ajuan_excel'       => $ajuan_excel,
                    'ajuan_zip'         => $ajuan_zip,
                    'id_program'        => $id_program,
                    'status'            => 2,
                    'nama_status'       => 'PENDING MPM',
                    'signature'         => $signature_import,
                    'created_at'        => $created_at,
                    'created_by'        => $this->session->userdata('id'),
                ];
                $this->db->insert('management_claim.ajuan_claim', $data);
                redirect('management_claim/email_status/'.$signature_program.'/'.$signature);
                die;
            }
        }else{
            $this->session->set_flashdata("pesan", "Import Failed. Harap infokan ini ke IT");
            redirect('management_claim/preview_import_bonus_barang/'.$signature_program.'/'.$signature);
        }
    }

    public function email_status($signature_program, $signature){
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        // echo "signature_program : ".$signature_program;
        // echo "signature : ".$signature;
        // die;
        $get_ajuan_claim = $this->model_management_claim->get_ajuan_claim($signature_program);
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
            $nama_status        = $get_ajuan_by_signature->row()->nama_status;
            $created_at         = $get_ajuan_by_signature->row()->created_at;
            $id_verifikasi      = $get_ajuan_by_signature->row()->id_verifikasi;
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
            'nama_pengirim'                 => $nama_pengirim,
            'email_pengirim'                => $email_pengirim,
            'ajuan_excel'                   => $ajuan_excel,
            'ajuan_zip'                     => $ajuan_zip,
            'created_at'                    => $created_at,
            'signature_program'             => $signature_program,
            'signature'                     => $signature,
            'verifikasi_keterangan'         => $verifikasi_keterangan,
            'verifikasi_created_at'         => $verifikasi_created_at,
            'verifikasi_username'           => $verifikasi_username,
        ];

        // die;

        $from   = "suffy@muliaputramandiri.com";
        $to     = "suffy@muliaputramandiri.com";
        $cc     = "suffy@muliaputramandiri.com";

        $message = $this->load->view("management_claim/email_status",$data,TRUE);
        $subject = "MPM SITE | CLAIM : $nomor_surat | ".$nama_status;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) 
        {
            $this->session->set_flashdata("pesan_success", "Pengiriman email berhasil. Terima kasih");    
            if ($status == '2') {
                redirect('management_claim/form_ajuan_claim/'.$signature_program);
                die;
            }elseif ($status == '1'){
                redirect('management_claim/verifikasi_mpm/'.$signature_program.'/'.$signature);
            }
            

        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
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

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/report', $data);
        $this->load->view('kalimantan/footer');

    }

    public function report_proses(){
     
        $supp       = $this->input->get('supp');
        $kategori   = $this->input->get('kategori');
        $site_code  = $this->input->get('site_code');
        $from       = $this->input->get('from');
        $to         = $this->input->get('to');

        $advanced = [
            'supp'       => $supp,
            'site_code'  => $site_code,
            'from'       => $from,
            'to'         => $to,
            'kategori'   => $kategori,
        ];

        $get_registrasi_program_by_supp_date = $this->model_management_claim->get_registrasi_program_by_supp_date($advanced);

        if ($get_registrasi_program_by_supp_date->num_rows() > 0) {

            $signature_ajuan_gabung = '';
            foreach ($get_registrasi_program_by_supp_date->result() as $a) {
                $signature_ajuan_gabung.=",'".$a->signature_ajuan."'";
                $signature_ajuan_join= preg_replace('/,/', '', $signature_ajuan_gabung,1) ;
            }

            $query = "
                select 	a.nama_program, a.nama_pengirim, a.email_pengirim, a.nomor_surat_program, a.site_code_header, 
                        a.site_code, a.branch_name, a.nama_comp, a.ajuan_excel, a.ajuan_zip, a.no_sales, a.tgl_sales, a.kode_class,
                        a.nama_class, a.kode_customer, a.kodeprod, a.namaprod, a.qty_jual, a.qty_bonus, a.value_jual, a.value_bonus
                from management_claim.import_bonus_barang a 
                where a.signature in ($signature_ajuan_join)
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
        }else{
            
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('management_claim/report');

        }

        // die;

         

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
    
        
    }


}
?>
