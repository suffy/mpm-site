<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Target_outlet extends MY_Controller
{    
    function target_outlet()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_target_outlet'));
        $this->email_tim = 'tim@test.com, tim2@test.com';
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->userid = $this->session->userdata('id');
    }

    function navbar($data)
    {
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

    public function target()
    {
        $data = [
            'title'     => 'Tracking Loyalty | Input Target',
            'url'       => 'target_outlet/target_proses',
            'get_data'  => $this->model_target_outlet->get_target_outlet()
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/target', $data);
        $this->load->view('kalimantan/footer');
    }
    
    public function target_proses()
    {
        $kode_outlet = $this->input->post('kode_outlet');

        $data = [
            'kode_outlet'   => $kode_outlet,
            'created_at'    => $this->created_at,
            'created_by'    => $this->userid
        ];

        $insert = $this->model_target_outlet->input_target_outlet($data);
        $this->session->set_flashdata("pesan_success", "Input Target Berhasil");
        redirect('target_outlet/target', 'refresh');
    }

    public function master_outlet()
    {
        $data = [
            'title'     => 'Master Data | Master Outlet Nasional',
            'url'       => 'target_outlet/master_outlet_proses',
            'get_data'  => $this->model_target_outlet->get_master_outlet()
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/master_outlet', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_outlet_proses()
    {
        $tahun = $this->input->post('tahun');
        $site_code = $this->input->post('site_code');

        // cek site_code valid atau tidak
        $cek = $this->model_target_outlet->master_site($site_code);
        if (!$cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update data gagal. Site Code Tidak Valid");
            redirect('target_outlet/master_outlet', 'refresh');
            die;
        }

        $delete_master_outlet = $this->model_target_outlet->delete_master_outlet($site_code);

        $update = $this->model_target_outlet->update_master_outlet($tahun, $site_code);
        $this->session->set_flashdata("pesan_success", "Update Master Outlet Berhasil");
        redirect('target_outlet/master_outlet', 'refresh');

    }

    

    public function master_site_registered()
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

    public function master_tracking()
    {
        $data = [
            'title'     => 'Master Data | Master Tracking',
            'url'       => 'target_outlet/master_tracking_proses',
            'get_data'  => $this->model_target_outlet->get_master_tracking()
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/master_tracking', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_tracking_proses()
    {
        $tahun = $this->input->post('tahun');
        $nama_tracking = $this->input->post('nama_tracking');
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        $signature = 'tracking-' . rand() . md5($this->created_at) . date('Ymd');

        // echo "tahun : ".$tahun;
        // echo "nama_tracking : ".$nama_tracking;
        // echo "from : ".$from;
        // echo "to : ".$to;
        // echo "signature : ".$signature;

        // die;



        $data = [
            'from'          => $from,
            'to'            => $to,
            'nama_tracking' => $nama_tracking,
            'created_at'    => $this->created_at,
            'created_by'    => $this->userid,
            'signature'     => $signature
        ];

        $this->model_target_outlet->insert_master_tracking($data);
        $this->session->set_flashdata("pesan_success", "Insert Tracking Berhasil");
        redirect('target_outlet/master_tracking', 'refresh');
    }

    public function delete_master_tracking($signature)
    {
        // celek signature valid atau tidak
        $get_data = $this->model_target_outlet->get_master_tracking($signature);
        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Delete gagal. Data not found");
            redirect('target_outlet/master_tracking', 'refresh');
        }

        $id_tracking = $get_data->row()->id;

        $data = [
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->userid,
            'deleted_at'    => $this->created_at,
            'deleted_by'    => $this->userid
        ];

        $this->model_target_outlet->update_master_tracking($data, $id_tracking);
        $this->session->set_flashdata("pesan_success", "Delete Tracking Berhasil");
        redirect('target_outlet/master_tracking', 'refresh');

    }

    public function tracking_detail($signature)
    {
        if ($signature == '') {
            redirect('target_outlet/master_tracking', 'refresh');
        }
        
        $get_master_tracking = $this->model_target_outlet->get_master_tracking($signature);

        if(!$get_master_tracking->num_rows() > 0){
            redirect('target_outlet/master_tracking', 'refresh');
            die;
        }

        $id_tracking = $get_master_tracking->row()->id;

        $data = [
            'title'     => 'Master Tracking | detail',
            'url'       => 'target_outlet/tracking_detail_proses',
            'url_update_target_value' => 'target_outlet/update_target_value',
            'get_data'  => $this->model_target_outlet->get_tracking_detail_by_id_tracking($id_tracking),
            'id_tracking' => $id_tracking,
            'signature' => $signature
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/tracking_detail', $data);
        $this->load->view('kalimantan/footer');
    }

    public function tracking_detail_proses()
    {
        $kode_outlet = $this->input->post('kode_outlet');
        $target_value = $this->input->post('target_value');
        $kodeprod = $this->input->post('kodeprod');
        $id_tracking = $this->input->post('id_tracking');
        $signature_tracking= $this->input->post('signature');

        $data = [
            'id_tracking' => $id_tracking,
            'kode_outlet' => $kode_outlet,
            'target_value' => $target_value,
            'kodeprod' => $kodeprod,
            'created_at'    => $this->created_at,
            'created_by'    => $this->userid,
            'signature'     => 'tracking-detail-' . rand() . md5($this->created_at) . date('Ymd')
        ];

        $this->model_target_outlet->insert_tracking_detail($data);
        $this->session->set_flashdata("pesan_success", "Insert Tracking Detail Berhasil");
        redirect('target_outlet/tracking_detail/' . $signature_tracking . '', 'refresh');

    }

    public function delete_tracking_detail($signature)
    {
        // cek signature valid atau tidak
        $get_data = $this->model_target_outlet->get_tracking_detail($signature);
        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Delete gagal. Data not found");
            redirect('target_outlet/tracking_detail/' . $signature, 'refresh');
        }

        $id_tracking_detail = $get_data->row()->id;

        $id_tracking = $get_data->row()->id_tracking;
        $get_tracking = $this->model_target_outlet->get_master_tracking_by_id($id_tracking);
        $signature_tracking = $get_tracking->row()->signature;

        $data = [
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->userid,
            'deleted_at'    => $this->created_at,
            'deleted_by'    => $this->userid
        ];

        $this->model_target_outlet->update_tracking_detail($data, $id_tracking_detail);
        $this->session->set_flashdata("pesan_success", "Delete Tracking Detail Berhasil");
        redirect('target_outlet/tracking_detail/'.$signature_tracking, 'refresh');
    }

    public function generate_tracking_detail($signature)
    {
        // cek signature valid atau tidak
        $get_data = $this->model_target_outlet->get_tracking_detail($signature);
        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Delete gagal. Data not found");
            redirect('target_outlet/tracking_detail/' . $signature, 'refresh');
        }

        $id_tracking_detail = $get_data->row()->id;

        $kode_outlet = $get_data->row()->kode_outlet;
        $get_data_master_outlet = $this->model_target_outlet->get_master_outlet_by_kode_outlet($kode_outlet);
        if ($get_data_master_outlet->num_rows() > 0) {
            $nama_outlet = $get_data_master_outlet->row()->nama_outlet;
            $kode_type = $get_data_master_outlet->row()->kode_type;
            $kode_class = $get_data_master_outlet->row()->kodesalur;
        }else{
            $this->session->set_flashdata("pesan", "Generate gagal. Data not found");
            redirect('target_outlet/tracking_detail/' . $signature, 'refresh');
        }

        $id_tracking = $get_data->row()->id_tracking;
        $get_tracking = $this->model_target_outlet->get_master_tracking_by_id($id_tracking);
        $signature_tracking = $get_tracking->row()->signature;

        $data = [
            'nama_outlet'   => $nama_outlet,
            'kode_type'     => $kode_type,
            'kode_class'    => $kode_class,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->userid,
        ];

        $this->model_target_outlet->update_tracking_detail($data, $id_tracking_detail);
        $this->session->set_flashdata("pesan_success", "Generate Tracking Detail Berhasil");
        redirect('target_outlet/tracking_detail/'.$signature_tracking, 'refresh');
    }

    public function tracking_sales()
    {
        $data = [
            'title'     => 'Master Data | Tracking Sales',
            'url'       => 'target_outlet/master_tracking_proses',
            'get_data'  => $this->model_target_outlet->get_master_tracking()
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/tracking_sales', $data);
        $this->load->view('kalimantan/footer');
    }

    public function start_tracking($signature)
    {
        $get_master_tracking = $this->model_target_outlet->get_master_tracking($signature);
        if (!$get_master_tracking->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('target_outlet/master_tracking', 'refresh');
        }else
        {
            $id_tracking = $get_master_tracking->row()->id;
            $nama_tracking = $get_master_tracking->row()->nama_tracking;
            $from = $get_master_tracking->row()->from;
            $to = $get_master_tracking->row()->to;
        }   

        $tahun_from = substr($from, 0, 4);
        $tahun_to = substr($to, 0, 4);
        $bulan_from = (int) substr($from, 5, 2);
        $bulan_to = (int) substr($to, 5, 2);

        // echo "id_tracking : ".$id_tracking;
        // echo "nama_tracking : ".$nama_tracking;
        // echo "from : ".$from;
        // echo "to : ".$to;

        // echo "tahun_from : ".$tahun_from;
        // echo "tahun_to : ".$tahun_to;
        // echo "bulan_from : ".$bulan_from;
        // echo "bulan_to : ".$bulan_to;

        // die;


        if ($tahun_from == $tahun_to) {
            $tahun = $tahun_from;
        }else{
            $this->session->set_flashdata("pesan", "Tracking gagal. Tahun From dan Tahun To harus sama");
            redirect('target_outlet/master_tracking', 'refresh');
        }

        $selisih = $bulan_to - $bulan_from;

        $bulan = '';
        for ($i=$bulan_from; $i <= $bulan_to  ; $i++) 
        { 
            $bulan.= ','.$i;
        }
        $bulan_join = preg_replace('/,/', '', $bulan,1);
        
        $get_tracking_detail_by_id_tracking = $this->model_target_outlet->get_tracking_detail_by_id_tracking($id_tracking);
        if (!$get_tracking_detail_by_id_tracking->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('target_outlet/master_tracking', 'refresh');
        }else
        {

            foreach ($get_tracking_detail_by_id_tracking->result() as $a) 
            {    
                $kode_outlet = $a->kode_outlet;
                $kodeprod = $a->kodeprod;
                $target_value = $a->target_value;

                $data = [
                    'id_tracking'   => $id_tracking,
                    'tahun'         => $tahun,
                    'bulan_join'    => $bulan_join,
                    'kodeprod'      => $kodeprod,
                    'target_value'  => $target_value,
                    'kode_outlet'   => $kode_outlet
                ];

                $this->model_target_outlet->insert_start_tracking($data);
            }
        }   

        $this->session->set_flashdata("pesan_success", "Start Tracking Berhasil");
        redirect('target_outlet/master_tracking', 'refresh');

    }

    public function dashboard_loyalty()
    {

        $get_count_tracking = $this->model_target_outlet->count_tracking();
        if ($get_count_tracking->num_rows() > 0) {
            $count_tracking = $get_count_tracking->row()->count_tracking;
        }else{
            $count_tracking = 0;
        }

        $get_count_tracking_detail = $this->model_target_outlet->count_tracking_detail();
        if ($get_count_tracking_detail->num_rows() > 0) {
            $count_tracking_detail = $get_count_tracking_detail->row()->count_tracking;
        }else{
            $count_tracking_detail = 0;
        }

        $data = [
            'title'         => 'Dashboard Loyalty',
            'url'           => 'target_outlet/dashboard',
            'get_data'      => $this->model_target_outlet->get_dashboard_loyalty(),
            'count_tracking'=> $count_tracking,
            'count_tracking_detail'=> $count_tracking_detail
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/dashboard_loyalty', $data);
        $this->load->view('kalimantan/footer');
    }

    public function dashboard_po()
    {
        $get_count_tracking = $this->model_target_outlet->count_tracking();
        if ($get_count_tracking->num_rows() > 0) {
            $count_tracking = $get_count_tracking->row()->count_tracking;
        }else{
            $count_tracking = 0;
        }

        $get_count_tracking_detail = $this->model_target_outlet->count_tracking_detail();
        if ($get_count_tracking_detail->num_rows() > 0) {
            $count_tracking_detail = $get_count_tracking_detail->row()->count_tracking;
        }else{
            $count_tracking_detail = 0;
        }

        $data = [
            'title'         => 'Dashboard PO',
            'url'           => 'target_outlet/dashboard',
            'get_data'      => $this->model_target_outlet->get_po(),
            'count_tracking'=> $count_tracking,
            'count_tracking_detail'=> $count_tracking_detail
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/dashboard_po', $data);
        $this->load->view('kalimantan/footer');
    }

    public function dashboard_retur()
    {
        $get_count_tracking = $this->model_target_outlet->count_tracking();
        if ($get_count_tracking->num_rows() > 0) {
            $count_tracking = $get_count_tracking->row()->count_tracking;
        }else{
            $count_tracking = 0;
        }

        $get_count_tracking_detail = $this->model_target_outlet->count_tracking_detail();
        if ($get_count_tracking_detail->num_rows() > 0) {
            $count_tracking_detail = $get_count_tracking_detail->row()->count_tracking;
        }else{
            $count_tracking_detail = 0;
        }

        $data = [
            'title'         => 'Dashboard Retur',
            'url'           => 'target_outlet/dashboard',
            'get_data'      => $this->model_target_outlet->get_retur(),
            'count_tracking'=> $count_tracking,
            'count_tracking_detail'=> $count_tracking_detail
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/dashboard_retur', $data);
        $this->load->view('kalimantan/footer');
    }

    public function dashboard_claim()
    {
        $get_count_tracking = $this->model_target_outlet->count_tracking();
        if ($get_count_tracking->num_rows() > 0) {
            $count_tracking = $get_count_tracking->row()->count_tracking;
        }else{
            $count_tracking = 0;
        }

        $get_count_tracking_detail = $this->model_target_outlet->count_tracking_detail();
        if ($get_count_tracking_detail->num_rows() > 0) {
            $count_tracking_detail = $get_count_tracking_detail->row()->count_tracking;
        }else{
            $count_tracking_detail = 0;
        }

        $data = [
            'title'         => 'Dashboard Claim',
            'url'           => 'target_outlet/dashboard',
            'get_data'      => $this->model_target_outlet->get_claim(),
            'count_tracking'=> $count_tracking,
            'count_tracking_detail'=> $count_tracking_detail
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/dashboard_claim', $data);
        $this->load->view('kalimantan/footer');
    }

    public function kalender_data()
    {
        $get_count_tracking = $this->model_target_outlet->count_tracking();
        if ($get_count_tracking->num_rows() > 0) {
            $count_tracking = $get_count_tracking->row()->count_tracking;
        }else{
            $count_tracking = 0;
        }

        $get_count_tracking_detail = $this->model_target_outlet->count_tracking_detail();
        if ($get_count_tracking_detail->num_rows() > 0) {
            $count_tracking_detail = $get_count_tracking_detail->row()->count_tracking;
        }else{
            $count_tracking_detail = 0;
        }

        $data = [
            'title'         => 'Kalender Data',
            'url'           => 'target_outlet/dashboard',
            'get_data'      => $this->model_target_outlet->get_kalender_data(),
            'count_tracking'=> $count_tracking,
            'count_tracking_detail'=> $count_tracking_detail
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('target/kalender_data', $data);
        $this->load->view('kalimantan/footer');
    }
}
?>
