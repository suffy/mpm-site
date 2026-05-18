<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Penta extends MY_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'Management Raw Data';

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_penta'));
        $this->email_tim = 'tim@test.com, tim2@test.com';
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->userid = $this->session->userdata('id');
    }

    public function list_token()
    {
        $data = [
            'title'     => 'Integration | Request Token',
            'url'       => 'penta/get_penta_sales_all',
            'get_data'  => $this->model_penta->get_penta_token()
        ];        
        $this->render('penta/list_token', $data);
    }

    public function log_sales()
    {
        $pilih_bulan = $this->input->post('bulan');
        $tahun = substr($pilih_bulan,0,4);
        $bulan = (int) substr($pilih_bulan,5,2);

        // echo "tahun : ".$tahun;
        // echo "bulan : ".$bulan;
        // die;

        if ($pilih_bulan) {
            $params_tahun = $tahun;
            $params_bulan = $bulan;
        }else{
            $params_tahun = date('Y');
            $params_bulan = date('m');
        }

        $data = [
            'title'     => 'Integration | History Penarikan Sales',
            'url'       => 'penta/log_sales',
            'get_data'  => $this->model_penta->get_penta_log_by_tahun_bulan_limit($params_tahun, $params_bulan, 10),
            'pilih_bulan' => $pilih_bulan
        ];        
        $this->render('penta/log_sales', $data);
    }

    public function log_sales_batam()
    {
        $pilih_bulan = $this->input->post('bulan');
        $tahun = substr($pilih_bulan,0,4);
        $bulan = (int) substr($pilih_bulan,5,2);

        // echo "tahun : ".$tahun;
        // echo "bulan : ".$bulan;
        // die;

        if ($pilih_bulan) {
            $params_tahun = $tahun;
            $params_bulan = $bulan;
        }else{
            $params_tahun = date('Y');
            $params_bulan = date('m');
        }

        $data = [
            'title'     => 'History Penarikan Sales Batam',
            'url'       => 'penta/log_sales_batam',
            'get_data'  => $this->model_penta->get_penta_log_by_tahun_bulan_limit($params_tahun, $params_bulan, 10),
            'pilih_bulan' => $pilih_bulan
        ];        
        $this->render('penta/log_sales_batam', $data);
    }

    public function get_penta_sales()
    {
        $get_bulan = $this->input->post('bulan');
        $tahun = substr($get_bulan,0,4);
        $bulan = (int) substr($get_bulan,5,2);

        // get hari ini, contoh selasa = 2
        $hari_ini = date('w');
        $this->get_token();

        $get_token = $this->model_penta->get_token_active();
        if ($get_token->num_rows() > 0) {
            $token = $get_token->row()->token;
        }else{
            $token = null;  
        }

        $id_log = $this->model_penta->get_penta_sales($token,$tahun,$bulan);

        if ($id_log) 
        {
            $get_sum = $this->model_penta->get_sum_sales_origin($id_log);
            if ($get_sum->num_rows() > 0) {
                $total_gross = $get_sum->row()->total_gross;
                $total_net = $get_sum->row()->total_net;
                $data = [
                    'total_gross' => $total_gross,
                    'total_net' => $total_net
                ];
                $update_log_sales =  $this->model_penta->update_log_sales($data, $id_log);                
                $update_length_kodeprod =  $this->model_penta->update_length_kodeprod($id_log);
            }    
        }

        $sales_ext = $this->model_penta->get_penta_sales_ext($token,$tahun,$bulan);
 
        $join_sales = $this->model_penta->join_sales($id_log, $sales_ext, $hari_ini);

        $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        redirect('penta/log_sales', 'refresh');
    }

    public function get_penta_batam()
    {
        $get_bulan = $this->input->post('bulan');
        $tahun = substr($get_bulan,0,4);
        $bulan = (int) substr($get_bulan,5,2);

        // get hari ini, contoh selasa = 2
        $hari_ini = date('w');
        $token = $this->get_token('batam');

        $id_log = $this->model_penta->get_penta_sales($token,$tahun,$bulan);

        if ($id_log) 
        {
            $get_sum = $this->model_penta->get_sum_sales_origin($id_log);
            if ($get_sum->num_rows() > 0) {
                $total_gross = $get_sum->row()->total_gross;
                $total_net = $get_sum->row()->total_net;
                $data = [
                    'total_gross' => $total_gross,
                    'total_net' => $total_net
                ];
                $update_log_sales =  $this->model_penta->update_log_sales($data, $id_log);                
                $update_length_kodeprod =  $this->model_penta->update_length_kodeprod($id_log);
            }    
        }

        $sales_ext = $this->model_penta->get_penta_sales_ext($token,$tahun,$bulan);
        $join_sales = $this->model_penta->join_sales($id_log, $sales_ext, $hari_ini);

        $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        redirect('penta/log_sales', 'refresh');
    }

    public function get_penta_sales_all()
    {
        $get_bulan = $this->input->post('bulan');
        $tahun = substr($get_bulan,0,4);
        $bulan = (int) substr($get_bulan,5,2);
        $signature = 'penta-sales-' . rand() . md5($this->created_at) . date('Ymd');

        $data = [
            "created_at" => $this->created_at,
            "created_by" => $this->userid,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "signature" => $signature
        ];

        echo "<pre>";
        print_r($data);
        echo "</pre>";

        // die;

        $this->db->insert('site.penta_log_sales', $data);
        $id_log = $this->db->insert_id();

        // $array_type = ['penta_sales','batam','gt'];
        $array_type = ['penta_sales'];

        foreach ($array_type as $key => $value) {
            $token = $this->get_token($value);
            echo "token : ".$token; 
            echo "<br>";
            $tarik_sales = $this->model_penta->get_penta_sales($token, $tahun, $bulan, $signature, $id_log);
            
        }

        $get_sum = $this->model_penta->get_sum_sales_origin($id_log);
        if ($get_sum->num_rows() > 0) {
            $total_gross = $get_sum->row()->total_gross;
            $total_net = $get_sum->row()->total_net;
            $data = [
                'total_gross' => $total_gross,
                'total_net' => $total_net
            ];
            $update_log_sales =  $this->model_penta->update_log_sales($data, $id_log);                
            $update_length_kodeprod =  $this->model_penta->update_length_kodeprod($id_log);
        }    

        $array_type = ['penta_sales'];
        foreach ($array_type as $key => $value) {
            $token = $this->get_token($value);
            echo "token : ".$token; 
            echo "<br>";
            $sales_ext = $this->model_penta->get_penta_sales_ext($token,$tahun,$bulan);
            
        }

        // $sales_ext = $this->model_penta->get_penta_sales_ext($token,$tahun,$bulan);
        $join_sales = $this->model_penta->join_sales($id_log, $sales_ext);

        $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        redirect('penta/log_sales', 'refresh');


        // $token = $this->get_token('batam');
    }

    public function get_token($type = null)
    {        
        $get_token = $this->model_penta->get_token($type);
        
        $data = [
            "token" => $get_token['activity_token'],
            "expired_at" => $get_token['expired_at'],
            "token_type" => $get_token['token_type'],
            "created_at" => $this->created_at,
            "created_by" => $this->userid
        ];

        $id = $this->model_penta->insert_token($data);
        return $data['token'];
        // $get_penta_token = $this->model_penta->get_penta_token($id);
        // $token = $get_penta_token->row()->token;
        // return $token;
    }

    public function export_sales($signature)
    {
        $get_penta_log = $this->model_penta->get_penta_log($signature);
        if (!$get_penta_log->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('penta/log_sales');
        }else
        {
            if (strpos($signature, 'penta-sales-ext') !== false) { // jika yang diklik adalah master outlet
                $this->model_penta->export_master_outlet($signature);
            }else
            {
                $flag_closing = $get_penta_log->row()->flag_closing;
                if($flag_closing == 0){
                    // echo "a"; die;
                    // $this->model_penta->export_sales($signature);
                    $this->model_penta->export_sales_new($signature);
                    // $this->model_penta->export_sales_join_origin($signature);
                }else{
                    // echo "b"; die;
                    // $this->model_penta->export_sales_closing($signature);
                    $this->model_penta->export_sales_new($signature);
                    // $this->model_penta->export_sales_join_origin($signature);
                }
                
            }
        }
    }

    public function sales_dashboard()
    {
        $pilih_bulan = $this->input->post('bulan');

        $tahun = substr($pilih_bulan,0,4);
        $bulan = (int) substr($pilih_bulan,5,2);

        if ($tahun) {
            $params_tahun = $tahun;
        }else{
            $params_tahun = (int)date('Y');
            // $params_tahun = 0;
        }

        if ($bulan > 0) {
            $params_bulan = (int)$bulan;
        }else{
            $params_bulan = (int)date('m');
            // $params_bulan = 0;
        }

        $get_penta_log = $this->model_penta->get_penta_log_by_tahun_bulan_limit($params_tahun, $params_bulan, 1);
        if ($get_penta_log->num_rows() > 0) {
            $id_log = $get_penta_log->row()->id;
        }else{
            $id_log = 0;
        }

        // die;
        if ($id_log > 0) {
            $get_data = $this->model_penta->get_sales_origin_by_id_log($id_log);
            $get_summary_sales_by_tanggal = $this->model_penta->summary_sales_by_id_log_group_tanggal($id_log, 10);
            $get_summary_sales_by_product = $this->model_penta->summary_sales_by_id_log_group_product($id_log, 10);
            $get_summary_sales_by_outlet = $this->model_penta->summary_sales_by_id_log_group_outlet($id_log, 10);
            $get_summary_sales = $this->model_penta->summary_sales_by_id_log($id_log);
            if ($get_summary_sales->num_rows() > 0) {
                $total_net = $get_summary_sales->row()->total_net;
                $total_gross = $get_summary_sales->row()->total_gross;
            }else{
                $total_net = null;
                $total_gross = null;
            }
        }else {
            $total_net = null;
            $total_gross = null;
            $get_data = $this->model_penta->get_penta_sales_origin_closing('', $id_log);
            $get_summary_sales_by_tanggal = $this->model_penta->summary_sales_by_id_log_group_tanggal_closing($id_log, 10);
            $get_summary_sales_by_product = $this->model_penta->summary_sales_by_id_log_group_product_closing($id_log, 10);
            $get_summary_sales_by_outlet = $this->model_penta->summary_sales_by_id_log_group_outlet_closing($id_log, 10);
        }
        
        $data = [
            'title'     => "Sales Penta ".($this->model_penta->get_bulan($params_bulan) == 0 ? '' : $this->model_penta->get_bulan($params_bulan)) . ' ' . ($params_tahun == 0 ? '' : $params_tahun), 
            'url'       => 'penta/sales_dashboard',
            'get_data'  => $get_data,
            'total_net' => $total_net,
            'total_gross' => $total_gross,
            'periode'   => $this->model_penta->get_bulan($params_bulan),
            'get_summary_sales_by_tanggal' => $get_summary_sales_by_tanggal,
            'get_summary_sales_by_product' => $get_summary_sales_by_product,
            'get_summary_sales_by_outlet' => $get_summary_sales_by_outlet,
            'pilih_bulan' => $pilih_bulan
        ];
        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/css');
        // $this->load->view('penta/sales_dashboard', $data);
        // $this->load->view('kalimantan/footer');
        
        $this->render('penta/sales_dashboard', $data);
    }

    public function log_stock()
    {
        $data = [
            'title'     => 'Integration | History Penarikan Stock',
            'url'       => 'penta/list_penta_stock',
            'get_data'  => $this->model_penta->get_penta_log_stock()
        ];        
        $this->render('penta/log_stock', $data);
    }

    public function get_penta_stock()
    {

        $this->get_token();
        
        $get_token = $this->model_penta->get_token_active();
        if ($get_token->num_rows() > 0) {
            $token = $get_token->row()->token;
        }else{
            $token = null;  
        }

        $tahun = date('Y');
        $bulan = (int)date('m');

        $id_log = $this->model_penta->get_penta_stock($token,$tahun,$bulan);

        if ($id_log) 
        {
            $get_sum = $this->model_penta->get_sum_stock_origin($id_log);
            if ($get_sum->num_rows() > 0) {
                $total_qty = $get_sum->row()->total_qty;
                $total_value = $get_sum->row()->total_value;

                $data = [
                    'total_qty' => $total_qty,
                    'total_value' => $total_value
                ];

                $update_log_stock =  $this->model_penta->update_log_stock($data, $id_log);
                
                $update_length_kodeprod_stock =  $this->model_penta->update_length_kodeprod_stock($id_log);


            }    
        }

        $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        redirect('penta/log_stock', 'refresh');
    }

    public function get_penta_stock_new()
    {
        $tahun = date('Y');
        $bulan = (int) date('m');
        $signature = 'penta-stock-' . rand() . md5($this->created_at) . date('Ymd');

        $data = [
            "created_at" => $this->created_at,
            "created_by" => $this->userid,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "signature" => $signature
        ];

        $this->db->insert('site.penta_log_stock', $data);
        $id_log = $this->db->insert_id();

        $array_type = ['penta_sales','batam','gt'];
        
        foreach ($array_type as $key => $value) {
            $token_data = $this->model_penta->get_token($value);
            $token = $token_data['activity_token'];

            // DEBUG (opsional)
            // echo "token : ".$token."<br>";
            // echo "token : ".$token; 
            // var_dump($token);
            // echo "<br>";
            $tarik_stock = $this->model_penta->get_penta_stock($token, $tahun, $bulan, $signature, $id_log);
            
        }

        $get_sum = $this->model_penta->get_sum_stock_origin($id_log);
        if ($get_sum->num_rows() > 0) {
            $total_qty = $get_sum->row()->total_qty;
            $total_value = $get_sum->row()->total_value;

            $data = [
                'total_qty' => $total_qty,
                'total_value' => $total_value
            ];

            $update_log_stock =  $this->model_penta->update_log_stock($data, $id_log);
            
            $update_length_kodeprod_stock =  $this->model_penta->update_length_kodeprod_stock($id_log);
        }

        $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        redirect('penta/log_stock', 'refresh');
    }


    public function export_stock($signature)
    {
        $get_penta_log_stock = $this->model_penta->get_penta_log_stock($signature);
        if (!$get_penta_log_stock->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('penta/log_stock');
        }

        $this->model_penta->export_stock($signature);

    }

    public function update_status($signature)
    {
        if ($this->session->userdata('username') <> 'milla' && $this->session->userdata('username') <> 'rifqi' && $this->session->userdata('username') <> 'suffy') 
        {
            $this->session->set_flashdata("pesan", "Update Gagal. User anda tidak diizinkan mengupdate status closing penta");
            redirect('penta/log_sales', 'refresh');
        }

        $get_penta_log = $this->model_penta->get_penta_log($signature);
        if (!$get_penta_log->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('penta/log_sales');
        }

        $flag_closing = (int)$get_penta_log->row()->flag_closing;
        $id_log = $get_penta_log->row()->id;
        $bulan = $get_penta_log->row()->bulan;
        $tahun = $get_penta_log->row()->tahun;

        if ($flag_closing == 0) {
            $params_closing = 1;
            $get_sales = $this->model_penta->get_penta_sales_origin_closing($signature, $id_log);
            if (!$get_sales->num_rows() > 0){
                $this->model_penta->insert_penta_sales_origin_closing($signature, $id_log);
            }elseif($get_sales->num_rows() > 0){
                $this->model_penta->delete_penta_sales_origin_closing($bulan, $tahun);
                $this->model_penta->insert_penta_sales_origin_closing($signature, $id_log);
            }
        }else{
            $params_closing = 0;
        }

        $data = [
            "flag_closing" => $params_closing,
            "updated_at" => $this->created_at,
            "updated_by" => $this->userid
        ];

        $update = $this->model_penta->update_log_sales($data, $id_log);
        

        $this->session->set_flashdata("pesan_success", "update data selesai");
        redirect('penta/log_sales');
    }

    public function stock_doi()
    {
        $pilih_bulan = $this->input->post('bulan');

        $tahun = substr($pilih_bulan,0,4);
        $bulan = (int) substr($pilih_bulan,5,2);

        if ($tahun) {
            $params_tahun = $tahun;
        }else{
            // $params_tahun = date('Y');
            $params_tahun = 0;
        }

        if ($bulan > 0) {
            $params_bulan = (int)$bulan;
        }else{
            // $params_bulan = (int)date('m');
            $params_bulan = 0;
        }

        $data = [
            'title'     => "Stock dan Doi Penta ".($this->model_penta->get_bulan($params_bulan) == 0 ? '' : $this->model_penta->get_bulan($params_bulan)) . ' ' . ($params_tahun == 0 ? '' : $params_tahun), 
            'url'       => 'penta/proses_stock_doi',
            'periode'   => $this->model_penta->get_bulan($params_bulan),
            'pilih_bulan' => $pilih_bulan
        ];
    
        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/css');
        // $this->load->view('penta/stok_doi', $data);
        // $this->load->view('kalimantan/footer');
        $this->render('penta/stok_doi', $data);
    }

    public function proses_stock_doi()
    {
        $periode = $this->input->post('bulan');
        $created_at = $this->model_outlet_transaksi->timezone();
        $tahun = substr($periode,0,4); //2025
        $bulan = (int) substr($periode,5,2); // 01
        $newDate_avg = date('Y-m', strtotime($periode. ' -6 months')); // mencari periode -6 months // 2024-07
        $tahun_avg = substr($newDate_avg,0,4); // 2024
        $bulan_avg = (int) substr($newDate_avg,5,2); // 07
        $bulan_berjalan = $bulan - 1;

        // echo "<br>";
        // echo "periode : ".$periode;
        // echo "<br>";
        // echo "tahun : ".$tahun;
        // echo "<br>";
        // echo "bulan : ".$bulan;
        // echo "<br>";
        // echo "newDate_avg : ".$newDate_avg;
        // echo "<br>";
        // echo "tahun_avg : ".$tahun_avg;
        // echo "<br>";
        // echo "bulan_avg : ".$bulan_avg;
        // echo "<br>";
        // echo "bulan_berjalan : ".$bulan_berjalan;
        // echo "<br>";die;

        // mencari max created_at
        $cari_created_at = $this->model_penta->search_max_created_at_from_penta_stock_origin($tahun, $bulan);
        if($cari_created_at-> num_rows > 0){
            $max_created_at = $cari_created_at->row()->max_created_at;
        }else{
            $this->session->set_flashdata("pesan", "Tidak Bisa Menarik Stok Dan Doi Karena Created_at Kosong");
            redirect('penta/stock_doi');
            die;
        }

        // proses insert data ke penta_stock_dan_doi_report
        $this->model_penta->insert_penta_temp_stock_dan_doi($tahun, $bulan, $created_at, $max_created_at, $this->userid);
        
        $get_data_penta_temp_stock_dan_doi = $this->model_penta->get_data_penta_temp_stock_dan_doi($this->userid, $created_at);
        if($get_data_penta_temp_stock_dan_doi-> num_rows > 0){
            $id_log = $get_data_penta_temp_stock_dan_doi->row()->id_log;
            $signature = $get_data_penta_temp_stock_dan_doi->row()->signature;
        }else{
            $this->session->set_flashdata("pesan", "Tidak Bisa Menarik Stok Dan Doi Karena Data Stok Kosong");
            redirect('penta/stock_doi');
            die;
        }

        //cek apakah data sales bulan terakhir sebelumnya sudah ada di tabel closing atau belum
        $this->model_penta->get_data_penta_sales_origin_closing($tahun, $bulan_berjalan);
        if (!$this->model_penta->get_data_penta_sales_origin_closing($tahun, $bulan_berjalan)->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Tidak Bisa Menarik Stok Dan Doi Periode ".$periode. ". Karena Tidak Ditemukan. Silahkan Hubungi IT!");
            redirect('penta/stock_doi');
        }

        //insert_penta_temp_sales_origin_closing
        $this->model_penta->insert_penta_temp_sales_origin_closing($bulan_avg, $tahun, $tahun_avg, $bulan_berjalan);

        //insert_penta_stock_dan_doi_report
        $this->model_penta->insert_penta_stock_dan_doi_report($bulan, $tahun, $id_log, $created_at, $this->userid, $signature);

        $get_data = $this->model_penta->get_penta_stock_dan_doi_report($this->userid, $bulan, $tahun);

        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('penta/stock_doi');
        }

        $data = [
            'title'         => "Stock dan Doi Penta ".$periode,
            'get_data'      => $get_data,
            'periode'       => $periode
        ];
        
        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/css');
        // $this->load->view('penta/proses_stock_doi', $data);
        // $this->load->view('kalimantan/footer');
        $this->render('penta/proses_stock_doi', $data);
    }

    public function export_stok_dan_doi($periode)
    {
        $tahun = substr($periode,0,4); //2025
        $bulan = (int) substr($periode,5,2); // 01
        $get_data = $this->model_penta->get_penta_stock_dan_doi_report($this->userid, $bulan, $tahun);

        if (!$get_data->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('penta/stock_doi');
        }

        $this->model_penta->export_penta_stock_dan_doi_report($this->userid, $bulan, $tahun);

    }

    public function mg_mpm_penta()
    {
        $data = [
            'title'     => 'MG x MPM x PENTA',
            'url'       => 'penta/mg_mpm_penta',
            'get_data'  => $this->model_penta->get_data_mg_mpm_penta(),
        ];
        
        $this->render('penta/mg_mpm_penta', $data);
    }

    public function get_penta_stock_all()
    {
        $this->get_token();
        $get_token = $this->model_penta->get_token_active();
        if ($get_token->num_rows() > 0) {
            $token = $get_token->row()->token;
        }else{
            $token = null;  
        }

        $tahun = date('Y');
        $bulan = (int)date('m');

        $id_log = $this->model_penta->get_penta_stock_all($token,$tahun,$bulan);

        if ($id_log) 
        {
            $get_sum = $this->model_penta->get_sum_stock_all($id_log);
            if ($get_sum->num_rows() > 0) {
                $total_qty = $get_sum->row()->total_qty;
                $total_value = $get_sum->row()->total_value;

                $data = [
                    'total_qty' => $total_qty,
                    'total_value' => $total_value
                ];

                $update_log_stock =  $this->model_penta->update_log_stock_all($data, $id_log);
                
                // $update_length_kodeprod_stock =  $this->model_penta->update_length_kodeprod_stock($id_log);


            }    
        }

        $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        redirect('penta/log_stock_all', 'refresh');
    }

    public function get_penta_stock_all_new()
    {
        $tahun = date('Y');
        $bulan = (int) date('m');
        $signature = 'penta-stock-all' . rand() . md5($this->created_at) . date('Ymd');

        $data = [
            "created_at" => $this->created_at,
            "created_by" => $this->userid,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "signature" => $signature
        ];

        $this->db->insert('site.penta_log_stock_all', $data);
        $id_log = $this->db->insert_id();

        $this->model_penta->delete_penta_stock_all($bulan, $tahun);
        
        $array_type = ['penta_sales','batam','gt'];

        foreach ($array_type as $key => $value) {
            $token_data = $this->model_penta->get_token($value);
            $token = $token_data['activity_token'];

            $tarik_stock = $this->model_penta->get_penta_stock_all($token, $tahun, $bulan, $signature, $id_log);
            
        }

        $get_sum = $this->model_penta->get_sum_stock_all($id_log);
        if ($get_sum->num_rows() > 0) {
            $total_qty = $get_sum->row()->total_qty;
            $total_value = $get_sum->row()->total_value;

            $data = [
                'total_qty' => $total_qty,
                'total_value' => $total_value
            ];

            $update_log_stock =  $this->model_penta->update_log_stock_all($data, $id_log);

        }

        $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        redirect('penta/log_stock_all', 'refresh');
    }

    public function log_stock_all()
    {
        $data = [
            'title'     => 'Integration | History Penarikan Stock All',
            'url'       => 'penta/list_penta_stock_all',
            'get_data'  => $this->model_penta->get_penta_log_stock_all()
        ];        
        $this->render('penta/log_stock_all', $data);
    }

    public function export_stock_all($signature)
    {
        $get_penta_log_stock = $this->model_penta->get_penta_log_stock_all($signature);
        if (!$get_penta_log_stock->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('penta/log_stock_all');
        }

        $this->model_penta->export_stock_all($signature);

    }
}
?>
