<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Penta extends MY_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'Penta';

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

        // $array_type = ['penta_sales'];
        // foreach ($array_type as $key => $value) {
        //     // $token = $this->get_token($value);
        //     echo "token penta_sales : ".$token; 
        //     // echo "<br>";
        //     $sales_ext = $this->model_penta->get_penta_sales_ext($token,$tahun,$bulan);
            
        // }

        $token = $this->get_token('penta_sales');

        // echo "token penta_sales : ".$token;
        $sales_ext = $this->model_penta->get_penta_sales_ext($token,$tahun,$bulan);

        echo "sales_ext : ".$sales_ext;

        if($sales_ext)
        {
            // $sales_ext = $this->model_penta->get_penta_sales_ext($token,$tahun,$bulan);
            $join_sales = $this->model_penta->join_sales($id_log, $sales_ext);
            if($join_sales)
            {
                $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
                redirect('penta/log_sales', 'refresh');
                die;
            }else{
                $this->session->set_flashdata("pesan", "Gagal join sales & master customer. Silahkan ulangi kembali atau hubungi IT");
                redirect('penta/log_sales', 'refresh');
                die;
            }
        }else{
            $this->session->set_flashdata("pesan", "Gagal tarik master customer penta. Silahkan ulangi kembali atau hubungi IT");
            redirect('penta/log_sales', 'refresh');
            die;
        }

        // die;

        

        // if($this->session->userdata('id')==297){

        // }else{
        //     $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        //     redirect('penta/log_sales', 'refresh');
        // }

        


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

        // $array_type = ['penta_sales','batam','gt'];
        $array_type = ['penta_sales'];

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
        
        // $array_type = ['penta_sales','batam','gt'];
        $array_type = ['penta_sales'];

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

    public function get_penta_customer()
    {
        $signature = 'penta-customer-palu' . rand() . md5($this->created_at) . date('Ymd');

        // generate token
        $get_token = $this->model_penta->get_token();
        $data = [
            "token" => $get_token['activity_token'],
            "expired_at" => $get_token['expired_at'],
            "created_at" => $this->created_at,
            "created_by" => $this->userid,
            "signature" => $signature
        ];

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        $this->db->insert('site.penta_log_customer', $data);
        $id_log = $this->db->insert_id();

        // die;
        
        if ($id_log) 
        {
            $result = $this->model_penta->get_penta_customer($data['token'], $signature, $id_log, 'palu');

            $temp = $this->model_penta->get_temp_outlet();

            foreach ($temp as $row)
            {   
                // echo "disini ";die;
                $cek = $this->model_penta->cek_outlet($row->org_id, $row->location);
                if ($cek->num_rows() == 0)
                {  
                    // echo "disini ";die;
                    $this->model_penta->insert_outlet($row, $signature, $this->userid);
                }
                    // echo "disini else";die;
                $this->session->set_flashdata("pesan_success", "Penarikan data berhasil.");
                redirect('penta/request_customer', 'refresh');
                
            }
        }else{
            $this->session->set_flashdata("pesan", "Penarikan data gagal tidak ada id_log. Silahkan coba lagi");
            redirect('penta/request_customer', 'refresh');
        }


    }


    public function request_customer()
    {

        $tahun = (int) date('Y');
        $bulan = (int) date('m');
        // $bulan = 2;
        $area_id = '485';

        // tentukan bulan sebelumnya
        if ($bulan == 1) {
            $bulan_cek = 12;
            $tahun_cek = $tahun - 1;
        } else {
            $bulan_cek = $bulan - 1;
            $tahun_cek = $tahun;
        }

        $get = $this->model_penta->get_mpm_upload(1146, $bulan_cek, $tahun_cek);

        if ($get->num_rows() > 0 && $get->row()->status_closing == 1) {
            // kalau bulan sebelumnya sudah closing → pakai bulan sekarang
            $bulan_final = $bulan;
            $tahun_final = $tahun;
        } else {
            // kalau belum closing → tetap pakai bulan sebelumnya
            $bulan_final = $bulan_cek;
            $tahun_final = $tahun_cek;
        }

        $data = [
            'title'                  => 'Request Customer',
            'url'                    => 'penta/request_customer',
            'get_data_log_customer'  => $this->model_penta->get_log_penta_customer(),
            'get_data_customer'      => $this->model_penta->get_customer(),
            // 'get_summary'            => $this->model_penta->get_mmm_penta_sales_palu_summary($tahun_final, $bulan_final, $area_id),
            'get_customer_summary'   => $this->model_penta->get_penta_customer_summary($area_id)
        ];        
        $this->render('penta/request_customer', $data);
    }

    public function edit_customer($id)
    {
        $get_customer = $this->model_penta->get_customer_by_id($id);

        if (!$get_customer->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('penta/request_customer');
        }

        $data = [
            'title'     => 'Edit Customer',
            'url'       => 'penta/update_customer/'.$id,
            'row'       => $get_customer->row(), 
            'get_spot'  => $this->model_penta->get_spot(),
            'get_type'  => $this->model_penta->get_type(),
            'get_class' => $this->model_penta->get_class()
        ];        
        $this->render('penta/edit_customer', $data);
    }

    public function update_customer($id)
    {
        $data = [
            "location" => $this->input->post('location'),
            "city" => $this->input->post('city'),
            "province" => $this->input->post('province'),
            "salesman_name" => $this->input->post('salesman_name'),
            "bill_ship_cust_name" => $this->input->post('bill_ship_cust_name'),
            "typeid" => $this->input->post('typeid'),
            "classid" => $this->input->post('classid'),
            "spot" => $this->input->post('spot'),
            "address1" => $this->input->post('address1'),
            "address2" => $this->input->post('address2'),
            "address3" => $this->input->post('address3'),
        ];

        $this->db->where('id', $id);
        $this->db->update('site.penta_outlet', $data);

        $this->session->set_flashdata('pesan_success', 'Data berhasil diupdate');
        redirect('penta/edit_customer/'.$id, 'refresh');
    }

    public function request_sales()
    {

        $tahun = (int) date('Y');
        $bulan = (int) date('m');
        // $bulan = 2;
        $area_id = '485';

        // tentukan bulan sebelumnya
        if ($bulan == 1) {
            $bulan_cek = 12;
            $tahun_cek = $tahun - 1;
        } else {
            $bulan_cek = $bulan - 1;
            $tahun_cek = $tahun;
        }

        $get = $this->model_penta->get_mpm_upload(1146, $bulan_cek, $tahun_cek);

        if ($get->num_rows() > 0 && $get->row()->status_closing == 1) {
            // kalau bulan sebelumnya sudah closing → pakai bulan sekarang
            $bulan_final = $bulan;
            $tahun_final = $tahun;
        } else {
            // kalau belum closing → tetap pakai bulan sebelumnya
            $bulan_final = $bulan_cek;
            $tahun_final = $tahun_cek;
        }

        $data = [
            'title'                  => 'Request Sales Palu',
            'url'                    => 'penta/request_sales',
            'get_master_product_summary' => $this->model_penta->get_master_product_summary(),
            'get_log_sales'             => $this->model_penta->get_log_sales('PV4PL'),
            'get_master_product'        => $this->model_penta->get_master_product(),
        ];        
        $this->render('penta/request_sales', $data);
    }

    public function update_status_sales($signature)
    {
        // echo "signature : ".$signature; die;
        // echo $dp_bridging;die;
        $get_log_sales_bysignature = $this->model_penta->get_log_sales_bysignature($signature);
        if(!$get_log_sales_bysignature->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Data upload tidak ditemukan");
            redirect('penta/request_sales','refresh');
        }

        $id_upload = $get_log_sales_bysignature->row()->id_upload;
        $status_closing = $get_log_sales_bysignature->row()->status_closing;

        // $get_mpm_upload = $this->model_bridging->get_mpm_upload($id_upload);
        if($id_upload == '' || $id_upload == null)
        {
            $this->session->set_flashdata("pesan", "Tidak Bisa Melakukan Closing Karena Data Upload Tidak Ditemukan");
            redirect('penta/request_sales');
        }

        if($status_closing == 0)
        {
            $data = [
                "status_closing" => 1,
            ];
            $this->model_bridging->update_mpm_upload($data, $id_upload);

        }else{
             $this->session->set_flashdata("pesan", "Tidak Bisa Merubah Status Closing Silahkan Hubungi IT");
            redirect('penta/request_sales','refresh');
        }

        $this->session->set_flashdata("pesan_success", "Data berhasil diupdate");
        redirect('penta/request_sales','refresh');
    }

    public function edit_product($id)
    {
        $get_product = $this->model_penta->get_product_by_id($id);

        if (!$get_product->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('penta/request_product', 'refresh');
        }

        $data = [
            'title'     => 'Edit Product',
            'url'       => 'penta/update_product/'.$id,
            'row'       => $get_product->row()
        ];        
        $this->render('penta/edit_product', $data);
    }

    public function update_product($id)
    {
        // echo "id : ".$id; die;
        $kode_produk_mpm = $this->input->post('kode_produk_mpm');
        $nama_produk_mpm = $this->input->post('nama_produk_mpm');
        if ($kode_produk_mpm == '' || $kode_produk_mpm == null) {
            $this->session->set_flashdata("pesan", "Kode Produk MPM tidak boleh kosong");
            redirect('penta/edit_product/'.$id, 'refresh');
        }

        $get_product = $this->model_penta->get_product_by_kodeprod($kode_produk_mpm);
        if (!$get_product->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Kode Produk ".$kode_produk_mpm." tidak ditemukan di database MPM. Silahkan input kode produk yang benar");
            redirect('penta/edit_product/'.$id, 'refresh');
        }else{
            $nama_produk_mpm = $get_product->row()->namaprod;
        }

        $data = [
            "kode_produk_mpm"     => $kode_produk_mpm,
            "nama_produk_mpm"     => $nama_produk_mpm,
            "qty"                 => $this->input->post('qty'),

            "updated_at"          => date('Y-m-d H:i:s'),
            "updated_by"          => $this->userid
        ];

        $this->db->where('id', $id);
        $this->db->update('site.penta_master_produk_sales', $data);

        $this->session->set_flashdata('pesan_success','Data Product berhasil diupdate');

        redirect('penta/edit_product/'.$id, 'refresh');
    }

    public function get_penta_sales_palu()
    {
        $tahun = (int) date('Y');
        $bulan = (int) date('m');

        $signature = 'penta-sales-palu' . rand() . md5($this->created_at) . date('Ymd');
        $area_id = '485';

        // generate token
        $get_token = $this->model_penta->get_token();
        $data = [
            "token" => $get_token['activity_token'],
            "site_code" => 'PV4PL',
            "created_at" => $this->created_at,
            "created_by" => $this->userid,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "signature" => $signature
        ];

        $this->db->insert('site.penta_log_sales_palu', $data);
        $id_log = $this->db->insert_id();
        

        // tentukan bulan sebelumnya
        if ($bulan == 1) {
            $bulan_cek = 12;
            $tahun_cek = $tahun - 1;
        } else {
            $bulan_cek = $bulan - 1;
            $tahun_cek = $tahun;
        }

        $get = $this->model_penta->get_mpm_upload(1146, $bulan_cek, $tahun_cek);

        if ($get->num_rows() > 0 && $get->row()->status_closing == 1) {
            // kalau bulan sebelumnya sudah closing → pakai bulan sekarang
            $bulan_final = $bulan;
            $tahun_final = $tahun;
        } else {
            // kalau belum closing → tetap pakai bulan sebelumnya
            $bulan_final = $bulan_cek;
            $tahun_final = $tahun_cek;
        }


        list($id_log, $signature) = $this->model_penta->get_penta_sales_detail_palu($data['token'],$tahun_final,$bulan_final, $signature, $id_log, 'palu');
        // die;
        
        $get_sales = $this->model_penta->get_penta_sales_palu_by_tahun_bulan($tahun_final, $bulan_final, $area_id);

        if (!$get_sales->num_rows() > 0){

            $this->insert_sales_palu($id_log, $bulan_final, $tahun_final);

            // $insert_sales = $this->model_penta->insert_penta_sales_palu($tahun_final,$bulan_final, $signature, $id_log);
        }elseif($get_sales->num_rows() > 0){

            $this->model_penta->delete_penta_sales_palu($tahun_final, $bulan_final, $area_id);
            $this->insert_sales_palu($id_log, $bulan_final, $tahun_final);
            // $insert_sales = $this->model_penta->insert_penta_sales_palu($tahun_final,$bulan_final, $signature, $id_log);
        }

        // insert and check peoduct 
        $cek_product_penta = $this->model_penta->cek_product_penta_from_sales($id_log, $bulan_final, $area_id);
        
        $insert_temp_firi = $this->insert_penta_firi($tahun_final, $bulan_final, $id_log);

        $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
        redirect('penta/request_sales', 'refresh');
    }

    public function insert_sales_palu($id_log, $bulan_final, $tahun_final)
    {
        // $bulan_final = sprintf("%02d", $bulan_final);

        $bulan = sprintf("%02d", $bulan_final);
        $tahun = $tahun_final;

        $periode = $tahun . '-' . $bulan;

        // $periode = '2026-05';[]
        // $id_log = 42;

        $get_temp_sales_palu = $this->model_penta->get_temp_sales_palu_by_idlog($id_log);

        if(!$get_temp_sales_palu->num_rows() > 0){
            echo "Gagal insert sales palu, tidak ada data temp sales palu untuk id_log : ".$id_log;
            die;
        }

        foreach ($get_temp_sales_palu->result() as $key) 
        {
            // cek kodeproduk dan qty
            // $item_id_vend_penta = (strlen($key->item_id_vend) == 5) ? '0' . $key->item_id_vend : $key->item_id_vend;
            $kode_produk_penta = $key->kode_produk;

            $get_kodeprod = $this->model_penta->get_master_product_penta_by_kodeprod($kode_produk_penta);
            if($get_kodeprod->num_rows() > 0) {
                $is_valid_kodeprod = 1;
                $item_id_vend = $get_kodeprod->row()->kode_produk_mpm; // update dengan format yang benar
                // cek qty master
                if($get_kodeprod->row()->qty != null && $get_kodeprod->row()->qty != '' && $get_kodeprod->row()->qty != 0){
                    // echo "ambil data yang null"; echo "<br>";
                    // qty dikonversi
                    $qty = $key->qty * $get_kodeprod->row()->qty;
                }else{
                    // echo "ambil data yang disini"; echo "<br>";
                    // qty asli
                    $qty = $key->qty;
                }
            }else{
                $is_valid_kodeprod = 0;
                $item_id_vend = $key->item_id_vend; // tetap pakai format lama jika tidak ditemukan di master product
                $qty = $key->qty; // tetap pakai qty asli jika tidak ditemukan di master product
            }

            // cek tanggal
            $is_valid_tanggal = 1; // Nilai default valid

            if (is_numeric($key->tanggal_invoice)) {
                // echo "didalem sini";
                $unixTimestamp = ($key->tanggal_invoice - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                $tanggal_ym = date('Y-m', $unixTimestamp);
                // Bandingkan dengan $month
                if ($tanggal_ym !== $periode) {
                    $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                }
            } else {
                // echo "tanggal dari sini"; echo "<br>";
                $tanggal = $key->tanggal_invoice;
                // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                if (strtotime($tanggal)) {
                    $tanggal_ym = date('Y-m', strtotime($tanggal));
                    
                    if ($tanggal_ym !== $periode) {
                        // echo "validnya dari sini";echo "<br>";
                        $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                    }
                } else {
                    // Jika bukan format tanggal yang valid, tandai tidak valid
                    $is_valid_tanggal = 0;
                }
            }

            // cek outlet
            $get_outlet = $this->model_penta->get_master_outlet_penta_by_kodeoutlet($key->kode_outlet);

            // default
            $is_valid_outlet = 0;
            $is_valid_type   = 0;
            $is_valid_class  = 0;
            $is_valid_spot   = 0;

            if ($get_outlet->num_rows() > 0) {

                $outlet = $get_outlet->row(); // ambil 1 data

                $is_valid_outlet = 1;

                // cek TYPE
                if (!empty($outlet->typeid)) {
                    $is_valid_type = 1;
                }

                // cek CLASS
                if (!empty($outlet->classid)) {
                    $is_valid_class = 1;
                }

                // cek SPOT
                if (!empty($outlet->spot)) {
                    $is_valid_spot = 1;
                }
            }

            // echo "" ."is_valid_kodeprod : ".$is_valid_kodeprod." - kodeprod : ".$key->item_id_vend; echo "<br>";
            // echo "" ."is_valid_tanggal : ".$is_valid_tanggal."; tanggal : ".$tanggal; echo "<br>";
            // echo "" ."is_valid_outlet : ".$is_valid_outlet."; kode_outlet : ".$key->kode_outlet; echo "<br>";
            // echo "" ."is_valid_type : ".$is_valid_type."; typeid : ".$outlet->typeid; echo "<br>";
            // echo "" ."is_valid_class : ".$is_valid_class."; classid : ".$outlet->classid; echo "<br>";
            // echo "" ."is_valid_spot : ".$is_valid_spot."; spot : ".$outlet->spot; echo "<br>";
            // echo "---------------------------------"; echo "<br>";
            // echo "qty : ".$key->qty." - konversi qty : ".$qty; echo "<br>";
            // die;

            $data_insert = [
                "id_log"            => $id_log,
                "bulan"             => $key->bulan,
                "tahun"             => $key->tahun,
                "principal_id"      => $key->principal_id,
                "area_id"           => $key->area_id,
                "nama_area"         => $key->nama_area,
                "tanggal_invoice"   => $key->tanggal_invoice,
                "nomor_invoice"     => $key->nomor_invoice,
                "nomor_sales_order" => $key->nomor_sales_order,
                "customer_po_number"=> $key->customer_po_number,
                "kode_outlet"       => $key->kode_outlet,
                "kode_outlet_lama"  => $key->kode_outlet_lama,
                "nama_outlet"       => $key->nama_outlet,
                "category_produk"   => $key->category_produk,
                "sales_order_line"  => $key->sales_order_line,
                "kode_produk"       => $key->kode_produk,
                "kode_produk_lama"  => $key->kode_produk_lama,
                "inventory_item_id" => $key->inventory_item_id,
                "item_id_vend"      => $item_id_vend, // update dengan format yang benar atau tetap pakai format lama jika tidak ditemukan di master product
                "id_item_sapora"    => $key->id_item_sapora,
                "category_product_principal" => $key->category_product_principal,
                "nama_produk"       => $key->nama_produk,
                "qty"               => $qty,
                "uom"               => $key->uom,
                "price"             => $key->price,
                "total_disc"        => $key->total_disc,
                "total_vat"         => $key->total_vat,
                "total_gross"       => $key->total_gross, // karena total gross belum termasuk ppn, jadi dikalikan 1.11 untuk mendapatkan total gross yang sudah termasuk ppn
                "total_net"         => $key->total_net,
                "bonus"             => $key->bonus,

                "total_discount_value_distributor" => $key->total_discount_value_distributor,
                "total_discount_value_prinsipal"   => $key->total_discount_value_prinsipal,
                "total_discount_value_extra"       => $key->total_discount_value_extra,

                "disc_persen_distributor_val_1" => $key->disc_persen_distributor_val_1,
                "discount_value_distributor_1"  => $key->discount_value_distributor_1,
                "nomor_discount_distributor_val_1" => $key->nomor_discount_distributor_val_1,

                "disc_persen_distributor_val_2" => $key->disc_persen_distributor_val_2,
                "discount_value_distributor_2"  => $key->discount_value_distributor_2,
                "nomor_discount_distributor_val_2" => $key->nomor_discount_distributor_val_2,

                "disc_persen_distributor_val_3" => $key->disc_persen_distributor_val_3,
                "discount_value_distributor_3"  => $key->discount_value_distributor_3,
                "nomor_discount_distributor_val_3" => $key->nomor_discount_distributor_val_3,

                "disc_persen_prinsipal_val_1" => $key->disc_persen_prinsipal_val_1,
                "discount_value_prinsipal_1"  => $key->discount_value_prinsipal_1,
                "nomor_discount_prinsipal_val_1" => $key->nomor_discount_prinsipal_val_1,

                "disc_persen_prinsipal_val_2" => $key->disc_persen_prinsipal_val_2,
                "discount_value_prinsipal_2"  => $key->discount_value_prinsipal_2,
                "nomor_discount_prinsipal_val_2" => $key->nomor_discount_prinsipal_val_2,

                "disc_persen_prinsipal_val_3" => $key->disc_persen_prinsipal_val_3,
                "discount_value_prinsipal_3"  => $key->discount_value_prinsipal_3,
                "nomor_discount_prinsipal_val_3" => $key->nomor_discount_prinsipal_val_3,

                "disc_persen_extra_val_1" => $key->disc_persen_extra_val_1,
                "discount_value_extra_1"  => $key->discount_value_extra_1,
                "nomor_discount_extra_val_1" => $key->nomor_discount_extra_val_1,

                "disc_persen_extra_val_2" => $key->disc_persen_extra_val_2,
                "discount_value_extra_2"  => $key->discount_value_extra_2,
                "nomor_discount_extra_val_2" => $key->nomor_discount_extra_val_2,

                "disc_persen_extra_val_3" => $key->disc_persen_extra_val_3,
                "discount_value_extra_3"  => $key->discount_value_extra_3,
                "nomor_discount_extra_val_3" => $key->nomor_discount_extra_val_3,

                "batch"            => $key->batch,
                "type_data"        => $key->type_data,
                "nama_sales"       => $key->nama_sales,
                "type_promo"       => $key->type_promo,
                "keterangan_promo" => $key->keterangan_promo,
                "dpl"              => $key->dpl,

                // tambahan
                "sales_id"         => $key->sales_id,
                "sales_name"       => $key->sales_name,

                // VALIDASI
                "is_valid_tanggal" => $is_valid_tanggal,
                "is_valid_kodeprod" => $is_valid_kodeprod,
                "is_valid_customer"  => $is_valid_outlet,
                "is_valid_type"    => $is_valid_type,
                "is_valid_class"   => $is_valid_class,
                "is_valid_spot"    => $is_valid_spot,

                "created_at"       => $key->created_at,
                "created_by"       => $key->created_by,
                "signature"        => $key->signature
            ];

            $this->db->insert('site.penta_sales_palu', $data_insert);
        }

        // redirect('penta/request_sales', 'refresh');

        // echo "<pre>";
        // var_dump($get_temp_sales_palu->result());
        // print_r($get_temp_sales_palu);
        // echo "</pre>";die;
    }


    public function insert_penta_firi($tahun, $bulan_final, $id_log)
    {
        $kode_comp = 'PV4';
        $nocab = 'PL';
        $site_code = 'PV4PL';

        // $bulan_final = 5;
        $bulan = sprintf("%02d", $bulan_final);
        // $tahun = 2026;
        // $bulan = '05';
        // $id_log = 42;

        $get_penta_sales_palu = $this->model_penta->get_penta_sales_palu_where_is_valid_false($tahun, $bulan, '485');
        if ($get_penta_sales_palu->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Terdapat data sales yang tidak valid sehingga sales tidak dapat diproses ke FiRi. Silahkan Cek Customer dan Produk.");
            // echo "Terdapat data sales yang tidak valid sehingga sales tidak dapat diproses ke FiRi. Silahkan Cek Customer dan Produk.";
            redirect('penta/request_sales', 'refresh');
            die;
        }

        $delete_fi = $this->model_penta->delete_fi_palu($kode_comp, $nocab, $tahun, $bulan);
        $delete_ri = $this->model_penta->delete_ri_palu($kode_comp, $nocab, $tahun, $bulan);
        // update produk di
        $proses_fi = $this->model_penta->insert_fi_palu($kode_comp, $nocab, $tahun, $bulan);
        $proses_fi = $this->model_penta->insert_fi_palu_bonus($kode_comp, $nocab, $tahun, $bulan);
        $proses_ri = $this->model_penta->insert_ri_palu($kode_comp, $nocab, $tahun, $bulan);
        $proses_ri = $this->model_penta->insert_ri_palu_bonus($kode_comp, $nocab, $tahun, $bulan);

        $delete_tblang = $this->model_penta->delete_tblang_palu($tahun, $nocab);
        $delete_tabsales = $this->model_penta->delete_tabsales_palu($tahun, $nocab);

        $insert_tblang = $this->model_penta->insert_tblang($tahun, $site_code);
        $update_spot_tblang = $this->model_penta->update_spot_tblang($tahun, $site_code);
        $insert_tabsales = $this->model_penta->insert_tabsales($tahun, $site_code);


        // insert mpm.upload
        $get_data_result = $this->model_penta->get_result($site_code, $tahun, $bulan);
        if($get_data_result->num_rows() > 0)
        {
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_value = 0;            
        }

        $insert_upload = [
            "userid"        => '1146',
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun,
            "bulan"         => $bulan,
            "status_closing" => 0
        ];
        $id_upload = $this->model_penta->insert_upload($insert_upload);

        
        $data = [
            'id_upload'   => $id_upload,
            // 'site_code'   => $site_code,
            'total_gross' => $total_value
        ];

        $update_log_sales =  $this->model_penta->update_log_sales_palu($data, $id_log);

    }

    public function download_master_produk()
    {
        $query = "
            SELECT 
                kode_produk_penta,
                item_id_vend_penta,
                nama_produk_penta,
                uom,
                kode_produk_mpm,
                nama_produk_mpm,
                qty,
                tabel
            FROM site.penta_master_produk_sales
            ORDER BY kode_produk_penta ASC
        ";

        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);

        // HEADER EXCEL
        $this->excel_generator->set_header(array(
            'kode_produk_penta',
            'item_id_vend_penta',
            'nama_produk_penta',
            'uom',
            'kode_produk_mpm',
            'nama_produk_mpm',
            'qty',
            'tabel'
        ));

        // FIELD DATABASE
        $this->excel_generator->set_column(array(
            'kode_produk_penta',
            'item_id_vend_penta',
            'nama_produk_penta',
            'uom',
            'kode_produk_mpm',
            'nama_produk_mpm',
            'qty',
            'tabel'
        ));

        // WIDTH KOLOM
        $this->excel_generator->set_width(array(25, 20, 40, 10, 20, 40, 10, 20));

        // EXPORT
        $this->excel_generator->exportTo2007('Download Master Produk Penta');
    }

    public function download_master_outlet()
{
    $query = "
        SELECT 
            org_id,
            org_name,
            location,
            site_use_id,
            bill_ship_cust_name,
            prefix,
            address1,
            address2,
            address3,
            city,
            province,
            primary_salesrep_id,
            salesman_name,
            typeid,
            classid,
            spot
        FROM site.penta_outlet
        where org_id = 485
        ORDER BY org_name ASC
    ";

    $hasil = $this->db->query($query);

    $this->excel_generator->set_query($hasil);

    // HEADER EXCEL
    $this->excel_generator->set_header(array(
        'org_id',
        'org_name',
        'location',
        'site_use_id',
        'bill_ship_cust_name',
        'prefix',
        'address1',
        'address2',
        'address3',
        'city',
        'province',
        'primary_salesrep_id',
        'salesman_name',
        'typeid',
        'classid',
        'spot'
    ));

    // FIELD DATABASE
    $this->excel_generator->set_column(array(
        'org_id',
        'org_name',
        'location',
        'site_use_id',
        'bill_ship_cust_name',
        'prefix',
        'address1',
        'address2',
        'address3',
        'city',
        'province',
        'primary_salesrep_id',
        'salesman_name',
        'typeid',
        'classid',
        'spot'
    ));

    // WIDTH COLUMN
    $this->excel_generator->set_width(array(15, 30, 20, 20, 40, 15, 50, 50, 50, 25, 25, 25, 35, 15, 15, 15));

    // EXPORT
    $this->excel_generator->exportTo2007('Download Master Outlet Penta');
}

}
?>
