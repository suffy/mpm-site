<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_sales extends MY_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'Management Sales';
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_sales','model_portal_raw'));

        $this->email_tim = 'suffy.yanuar@gmail.com';
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->session_id = $this->session->userdata('id');
        $this->session_supp = $this->session->userdata('supp');
        $this->tahun_folder = 2025;
    }

    function index()
    {
        $this->sell_out_product();
    }

    public function sell_out_product()
    {
        $get_principal = $this->model_management_sales->get_principal($this->session_id);   
        if (!$get_principal->num_rows() > 0) {
            $get_principal = $this->model_management_sales->get_principal_by_supp($this->session_supp);
        }

        $principal = "";
        foreach ($get_principal->result() as $a) {
            $principal.=",".$a->supp;
        }
        $supp = preg_replace('/,/', '', $principal,1);
        // echo "supp : ".$supp;

        $get_kodeprod = $this->model_management_sales->get_master_product($supp);

        $source = $this->model_management_sales->get_log_sell_out_product();

        $data = [
            'title'     => 'sell out product',            
            'title2'    => 'product list',            
            'url'       => 'management_sales/sell_out_product_proses',
            'year'      => $this->model_management_sales->get_year(),
            'principal' => $get_principal,
            'source'    => $source, 
            'kodeprod'  => $get_kodeprod
        ];

        $this->render('management_sales/sell_out_product', $data);
    }

    public function sell_out_product_proses()
    {
        
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $breakdown = $this->input->post('breakdown');
        $source = $this->input->post('source');
        $process = $this->input->post('process');
        $options = $this->input->post('options');

        $count = count($options);

        // echo "breakdown : ".$breakdown;
        // die;

        $get_using_log = $this->model_management_sales->get_using_log_sell_out_product($source);
        if (!$get_using_log->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "source data tidak valid");
            redirect('management_sales/sell_out_product');
            die;
        }

        if ($get_using_log->row()->status_using == 1) {
            $this->session->set_flashdata("pesan", "source data sedang digunakan oleh orang lain. Silahkan pilih source lainnya");
            redirect('management_sales/sell_out_product');
            die;
        }

        if(empty($options)){
            $this->session->set_flashdata("pesan", "kodeproduk tidak boleh kosong");
            redirect('management_sales/sell_out_product');
        }

        //get region by map_akses_region
        $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($this->session_id);
        if ($get_region->num_rows() > 0) {
            // $get_region = $this->model_management_sales->get_region_by_map_akses_region($this->session_id);
            // $this->session->set_flashdata("pesan", "user anda belum terdaftar di database region kami");
            // redirect('management_sales/sell_out_product');
            $region = 'all';
        }else{
            $get_region = $this->model_management_sales->get_region_by_map_akses_region($this->session_id);
            if (!$get_region->num_rows() > 0) {
                $this->session->set_flashdata("pesan", "user anda belum terdaftar di database region kami");
                redirect('management_sales/sell_out_product');
            }

            $params_region = "";
            foreach ($get_region->result() as $r) 
            {
                $params_region.= ",".'"'.$r->region.'"';
                $region = preg_replace('/,/', '', $params_region,1);
                if ($params_region == 'all') {
                    $region = 'all';
                }else{
                    $region = $region;
                }   
            }
        }

        $get_site_code_by_region = $this->model_management_sales->get_site_code_by_region($region);

        $count_site_code = count($get_site_code_by_region->result());

        $site_code = "";
        foreach ($get_site_code_by_region->result() as $s) {
            $site_code.= ",'".$s->site_code."'";
        }
        $site_code = preg_replace('/,/', '', $site_code,1);
        // echo "site_code : ".$site_code;
        // die;

        $source_name = $get_using_log->row()->name_table;
        $update_source = $this->model_management_sales->update_log_sell_out_product($source, 1);

        $kodeprod = "";
        foreach ($options as $key) {
            $kodeprod.= ",".$key;
        }

        $kodeprod = preg_replace('/,/', '', $kodeprod,1);

        if ($breakdown == 'v1') // subbranch,bulan
        {
            // echo "v1"."\n";die;
            $format_time = date('YmdHis', strtotime($this->created_at));
            $id_log = $this->model_management_sales->create_table_temp_report_selloutproduct($format_time, $count, $count_site_code, $kodeprod);
            $data = $this->model_management_sales->get_sales_by_site_code_bulan($source_name, $kodeprod, $from, $to, $format_time, $site_code);

            $get_summary_total = $this->model_management_sales->get_summary_total("temp_report_sell_out_product_$format_time");
            if ($get_summary_total->num_rows() > 0) {
                $total_value = $get_summary_total->row()->total_value;
                $total_unit = $get_summary_total->row()->total_unit;
                $count_row = $get_summary_total->row()->count_row;
            }
            $data_update = [
                'total_value' => $total_value,
                'total_unit' => $total_unit,
                'count_row' => $count_row,
            ];
            $update_log = $this->model_management_sales->update_temp_sell_out_product_log($data_update, $id_log);

            $get_log = $this->model_management_sales->get_temp_sell_out_product_log($id_log);
            if ($get_log->num_rows() > 0) {
                $total_value = $get_log->row()->total_value;
                $total_unit = $get_log->row()->total_unit;
                $count_kodeprod = $get_log->row()->count_kodeprod;
                $count_site_code = $get_log->row()->count_site_code;
                // echo "total_value : ".$total_value." total_unit : ".$total_unit;
            }

            $data = [
                'title'         => 'sell out product | breakdown subbranch, bulan',
                'data'          => $data,
                'filename'      => "temp_report_sell_out_product_$format_time",
                'total_value'   => $total_value,
                'total_unit'    => $total_unit,
                'count_kodeprod' => $count_kodeprod,
                'count_site_code' => $count_site_code,
                'count_row'     => $count_row
            ];

            $update_source = $this->model_management_sales->update_log_sell_out_product($source, 0);
                        
            $this->render('management_sales/get_sales_by_site_code', $data);
        }  
        
        elseif ($breakdown == 'v2') // subbranch, bulan, kodeprod
        {
            // echo "v2"."\n";die;
            $format_time = date('YmdHis', strtotime($this->created_at));
            $id_log = $this->model_management_sales->create_table_temp_report_selloutproduct_kodeprod($format_time, $count, $kodeprod, $count_site_code);
            $data = $this->model_management_sales->get_sales_by_site_code_bulan_kodeprod($source_name, $kodeprod, $from, $to, $format_time, $site_code);
            $filename = "temp_report_sell_out_product_kodeprod_$format_time";

            $get_summary_total = $this->model_management_sales->get_summary_total($filename);
            if ($get_summary_total->num_rows() > 0) {
                $total_value = $get_summary_total->row()->total_value;
                $total_unit = $get_summary_total->row()->total_unit;
                $count_row = $get_summary_total->row()->count_row;
            }
            $data_update = [
                'total_value' => $total_value,
                'total_unit' => $total_unit,
                'count_row' => $count_row,
            ];
            $update_log = $this->model_management_sales->update_temp_sell_out_product_log($data_update, $id_log);

            $get_log = $this->model_management_sales->get_temp_sell_out_product_log($id_log);
            if ($get_log->num_rows() > 0) {
                $total_value = $get_log->row()->total_value;
                $total_unit = $get_log->row()->total_unit;
                $count_kodeprod = $get_log->row()->count_kodeprod;
                $count_site_code = $get_log->row()->count_site_code;
                // echo "total_value : ".$total_value." total_unit : ".$total_unit;
            }

            $data = [
                'title'         => 'sell out product | breakdown subbranch, bulan, kodeproduk',
                'data'          => $data,
                'filename'      => $filename,
                'total_value'   => $total_value,
                'total_unit'    => $total_unit,
                'count_kodeprod' => $count_kodeprod,
                'count_site_code' => $count_site_code,
                'count_row'     => $count_row
            ];

            $update_source = $this->model_management_sales->update_log_sell_out_product($source, 0);

            $this->navbar($data);
            $this->load->view('kalimantan/header_full_width', $data);
            $this->load->view('management_claim/css');
            $this->load->view('management_sales/get_sales_by_site_code_kodeprod', $data);
            $this->load->view('kalimantan/footer');
        }  

        elseif ($breakdown == 'v3') // subbranch, bulan, kodeprod, tipe, class
        {
            // echo "v3"."\n";die;
            $format_time = date('YmdHis', strtotime($this->created_at));
            $id_log = $this->model_management_sales->create_table_temp_report_selloutproduct_kodeprod_tipe_class($format_time, $count, $kodeprod, $count_site_code);
            $data = $this->model_management_sales->get_sales_by_site_code_bulan_kodeprod_tipe_class($source_name, $kodeprod, $from, $to, $format_time, $site_code);
            $filename = "temp_report_sell_out_product_kodeprod_tipe_class_$format_time";


            $get_summary_total = $this->model_management_sales->get_summary_total($filename);
            if ($get_summary_total->num_rows() > 0) {
                $total_value = $get_summary_total->row()->total_value;
                $total_unit = $get_summary_total->row()->total_unit;
                $count_row = $get_summary_total->row()->count_row;
            }
            $data_update = [
                'total_value' => $total_value,
                'total_unit' => $total_unit,
                'count_row' => $count_row,
            ];
            $update_log = $this->model_management_sales->update_temp_sell_out_product_log($data_update, $id_log);

            $get_log = $this->model_management_sales->get_temp_sell_out_product_log($id_log);
            if ($get_log->num_rows() > 0) {
                $total_value = $get_log->row()->total_value;
                $total_unit = $get_log->row()->total_unit;
                $count_kodeprod = $get_log->row()->count_kodeprod;
                $count_site_code = $get_log->row()->count_site_code;
                // echo "total_value : ".$total_value." total_unit : ".$total_unit;
            }

            $data = [
                'title'         => 'sell out product | breakdown subbranch, bulan, kodeproduk, tipe, class',
                'data'          => $data,
                'filename'      => $filename,
                'total_value'   => $total_value,
                'total_unit'    => $total_unit,
                'count_kodeprod' => $count_kodeprod,
                'count_site_code' => $count_site_code,
                'count_row'     => $count_row
            ];

            $update_source = $this->model_management_sales->update_log_sell_out_product($source, 0);

            $this->navbar($data);
            $this->load->view('kalimantan/header_full_width', $data);
            $this->load->view('management_claim/css');
            $this->load->view('management_sales/get_sales_by_site_code_kodeprod_tipe_class', $data);
            $this->load->view('kalimantan/footer');
        }  


    }

    public function export($filename)
    {
        $query = "
            select site_code, branch_name, nama_comp, bulan, tahun, value, unit, created_at
            from site.$filename a
            order by site_code, branch_name, nama_comp
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'branch_name', 'nama_comp','bulan', 'tahun', 'value','unit','created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'site_code', 'branch_name', 'nama_comp','bulan', 'tahun', 'value','unit','created_at'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('sell out product : '.$filename); 

    }

    public function export_horizontal($filename)
    {
        $query = "
            select 	site_code, branch_name, nama_comp, tahun,
                    sum(if(a.bulan = 1, a.unit, 0)) as u1,
                    sum(if(a.bulan = 2, a.unit, 0)) as u2,
                    sum(if(a.bulan = 3, a.unit, 0)) as u3,
                    sum(if(a.bulan = 4, a.unit, 0)) as u4,
                    sum(if(a.bulan = 5, a.unit, 0)) as u5,
                    sum(if(a.bulan = 6, a.unit, 0)) as u6,
                    sum(if(a.bulan = 7, a.unit, 0)) as u7,
                    sum(if(a.bulan = 8, a.unit, 0)) as u8,
                    sum(if(a.bulan = 9, a.unit, 0)) as u9,
                    sum(if(a.bulan = 10, a.unit, 0)) as u10,
                    sum(if(a.bulan = 11, a.unit, 0)) as u11,
                    sum(if(a.bulan = 12, a.unit, 0)) as u12,
                    
                    sum(if(a.bulan = 1, a.value, 0)) as v1,
                    sum(if(a.bulan = 2, a.value, 0)) as v2,
                    sum(if(a.bulan = 3, a.value, 0)) as v3,
                    sum(if(a.bulan = 4, a.value, 0)) as v4,
                    sum(if(a.bulan = 5, a.value, 0)) as v5,
                    sum(if(a.bulan = 6, a.value, 0)) as v6,
                    sum(if(a.bulan = 7, a.value, 0)) as v7,
                    sum(if(a.bulan = 8, a.value, 0)) as v8,
                    sum(if(a.bulan = 9, a.value, 0)) as v9,
                    sum(if(a.bulan = 10, a.value, 0)) as v10,
                    sum(if(a.bulan = 11, a.value, 0)) as v11,
                    sum(if(a.bulan = 12, a.value, 0)) as v12,
                    
                    sum(if(a.bulan = 1, a.trans, 0)) as t1,
                    sum(if(a.bulan = 2, a.trans, 0)) as t2,
                    sum(if(a.bulan = 3, a.trans, 0)) as t3,
                    sum(if(a.bulan = 4, a.trans, 0)) as t4,
                    sum(if(a.bulan = 5, a.trans, 0)) as t5,
                    sum(if(a.bulan = 6, a.trans, 0)) as t6,
                    sum(if(a.bulan = 7, a.trans, 0)) as t7,
                    sum(if(a.bulan = 8, a.trans, 0)) as t8,
                    sum(if(a.bulan = 9, a.trans, 0)) as t9,
                    sum(if(a.bulan = 10, a.trans, 0)) as t10,
                    sum(if(a.bulan = 11, a.trans, 0)) as t11,
                    sum(if(a.bulan = 12, a.trans, 0)) as t12,
                    a.created_at
            from site.$filename a
            GROUP BY a.site_code
            order by site_code, branch_name, nama_comp
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'branch_name', 'nama_comp', 'tahun', 'u1', 'u2', 'u3', 'u4', 'u5', 'u6', 'u7', 'u8', 'u9', 'u10', 'u11', 'u12', 'v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v7', 'v8', 'v9', 'v10', 'v11', 'v12', 't1', 't2', 't3', 't4', 't5', 't6', 't7', 't8', 't9', 't10', 't11', 't12', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'site_code', 'branch_name', 'nama_comp', 'tahun', 'u1', 'u2', 'u3', 'u4', 'u5', 'u6', 'u7', 'u8', 'u9', 'u10', 'u11', 'u12', 'v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v7', 'v8', 'v9', 'v10', 'v11', 'v12', 't1', 't2', 't3', 't4', 't5', 't6', 't7', 't8', 't9', 't10', 't11', 't12', 'created_at'
        ));
        $this->excel_generator->set_width(array(10,10,10,10, 10,10,10,10,10,10,10,10,10,10,10,10,  10,10,10,10,10,10,10,10,10,10,10,10,  10,10,10,10,10,10,10,10,10,10,10,10, 10)); 
        $this->excel_generator->exportTo2007('sell out product horizontal : '.$filename); 

    }

    public function export_by_kodeprod($filename)
    {
        $query = "
            select  site_code, branch_name, nama_comp, bulan, tahun, a.kodeprod, a.namaprod, 
                    a.namasupp as principal, a.nama_group, a.nama_sub_group, value, unit, created_at
            from site.$filename a
            order by branch_name, nama_comp
        ";
        $hasil = $this->db->query($query);

        query_to_csv($hasil,TRUE,"selloutproduct_$filename.csv");
    }

    public function export_by_kodeprod_backup($filename)
    {
        $query = "
            select  site_code, branch_name, nama_comp, bulan, tahun, a.kodeprod, a.namaprod, 
                    a.namasupp as principal, a.nama_group, a.nama_sub_group, value, unit, created_at
            from site.$filename a
            order by branch_name, nama_comp
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'branch_name', 'nama_comp','bulan','tahun', 'kodeprod', 'namaprod', 'principal', 'nama_group', 'nama_sub_group', 'value','unit','created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'site_code', 'branch_name', 'nama_comp','bulan','tahun', 'kodeprod', 'namaprod', 'principal', 'nama_group', 'nama_sub_group', 'value','unit','created_at'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('sell out product : '.$filename); 
    }

    public function export_by_kodeprod_horizontal($filename)
    {
        $query = "
            select 	a.site_code, a.branch_name, a.nama_comp, a.kodeprod, a.namaprod,
                    a.namasupp, a.nama_group, a.nama_sub_group, a.tahun,
                    sum(if(a.bulan = 1, a.`value`, 0)) as v1,
                    sum(if(a.bulan = 2, a.`value`, 0)) as v2,
                    sum(if(a.bulan = 3, a.`value`, 0)) as v3,
                    sum(if(a.bulan = 4, a.`value`, 0)) as v4,
                    sum(if(a.bulan = 5, a.`value`, 0)) as v5,
                    sum(if(a.bulan = 6, a.`value`, 0)) as v6,
                    sum(if(a.bulan = 7, a.`value`, 0)) as v7,
                    sum(if(a.bulan = 8, a.`value`, 0)) as v8,
                    sum(if(a.bulan = 9, a.`value`, 0)) as v9,
                    sum(if(a.bulan = 10, a.`value`, 0)) as v10,
                    sum(if(a.bulan = 11, a.`value`, 0)) as v11,
                    sum(if(a.bulan = 12, a.`value`, 0)) as v12,
                    sum(if(a.bulan = 1, a.`unit`, 0)) as u1,
                    sum(if(a.bulan = 2, a.`unit`, 0)) as u2,
                    sum(if(a.bulan = 3, a.`unit`, 0)) as u3,
                    sum(if(a.bulan = 4, a.`unit`, 0)) as u4,
                    sum(if(a.bulan = 5, a.`unit`, 0)) as u5,
                    sum(if(a.bulan = 6, a.`unit`, 0)) as u6,
                    sum(if(a.bulan = 7, a.`unit`, 0)) as u7,
                    sum(if(a.bulan = 8, a.`unit`, 0)) as u8,
                    sum(if(a.bulan = 9, a.`unit`, 0)) as u9,
                    sum(if(a.bulan = 10, a.`unit`, 0)) as u10,
                    sum(if(a.bulan = 11, a.`unit`, 0)) as u11,
                    sum(if(a.bulan = 12, a.`unit`, 0)) as u12,
                    sum(if(a.bulan = 1, a.`trans`, 0)) as t1,
                    sum(if(a.bulan = 2, a.`trans`, 0)) as t2,
                    sum(if(a.bulan = 3, a.`trans`, 0)) as t3,
                    sum(if(a.bulan = 4, a.`trans`, 0)) as t4,
                    sum(if(a.bulan = 5, a.`trans`, 0)) as t5,
                    sum(if(a.bulan = 6, a.`trans`, 0)) as t6,
                    sum(if(a.bulan = 7, a.`trans`, 0)) as t7,
                    sum(if(a.bulan = 8, a.`trans`, 0)) as t8,
                    sum(if(a.bulan = 9, a.`trans`, 0)) as t9,
                    sum(if(a.bulan = 10, a.`trans`, 0)) as t10,
                    sum(if(a.bulan = 11, a.`trans`, 0)) as t11,
                    sum(if(a.bulan = 12, a.`trans`, 0)) as t12
            from site.$filename a 
            GROUP BY a.site_code, a.kodeprod
            ";

        $hasil = $this->db->query($query);   
        query_to_csv($hasil,TRUE,"selloutproduct_$filename.csv");
    }

    public function export_by_kodeprod_horizontal_backup($filename)
    {
        $query = "
            select 	a.site_code, a.branch_name, a.nama_comp, a.kodeprod, a.namaprod,
                    a.namasupp, a.nama_group, a.nama_sub_group, a.tahun,
                    sum(if(a.bulan = 1, a.`value`, 0)) as v1,
                    sum(if(a.bulan = 2, a.`value`, 0)) as v2,
                    sum(if(a.bulan = 3, a.`value`, 0)) as v3,
                    sum(if(a.bulan = 4, a.`value`, 0)) as v4,
                    sum(if(a.bulan = 5, a.`value`, 0)) as v5,
                    sum(if(a.bulan = 6, a.`value`, 0)) as v6,
                    sum(if(a.bulan = 7, a.`value`, 0)) as v7,
                    sum(if(a.bulan = 8, a.`value`, 0)) as v8,
                    sum(if(a.bulan = 9, a.`value`, 0)) as v9,
                    sum(if(a.bulan = 10, a.`value`, 0)) as v10,
                    sum(if(a.bulan = 11, a.`value`, 0)) as v11,
                    sum(if(a.bulan = 12, a.`value`, 0)) as v12,
                    sum(if(a.bulan = 1, a.`unit`, 0)) as u1,
                    sum(if(a.bulan = 2, a.`unit`, 0)) as u2,
                    sum(if(a.bulan = 3, a.`unit`, 0)) as u3,
                    sum(if(a.bulan = 4, a.`unit`, 0)) as u4,
                    sum(if(a.bulan = 5, a.`unit`, 0)) as u5,
                    sum(if(a.bulan = 6, a.`unit`, 0)) as u6,
                    sum(if(a.bulan = 7, a.`unit`, 0)) as u7,
                    sum(if(a.bulan = 8, a.`unit`, 0)) as u8,
                    sum(if(a.bulan = 9, a.`unit`, 0)) as u9,
                    sum(if(a.bulan = 10, a.`unit`, 0)) as u10,
                    sum(if(a.bulan = 11, a.`unit`, 0)) as u11,
                    sum(if(a.bulan = 12, a.`unit`, 0)) as u12,
                    sum(if(a.bulan = 1, a.`trans`, 0)) as t1,
                    sum(if(a.bulan = 2, a.`trans`, 0)) as t2,
                    sum(if(a.bulan = 3, a.`trans`, 0)) as t3,
                    sum(if(a.bulan = 4, a.`trans`, 0)) as t4,
                    sum(if(a.bulan = 5, a.`trans`, 0)) as t5,
                    sum(if(a.bulan = 6, a.`trans`, 0)) as t6,
                    sum(if(a.bulan = 7, a.`trans`, 0)) as t7,
                    sum(if(a.bulan = 8, a.`trans`, 0)) as t8,
                    sum(if(a.bulan = 9, a.`trans`, 0)) as t9,
                    sum(if(a.bulan = 10, a.`trans`, 0)) as t10,
                    sum(if(a.bulan = 11, a.`trans`, 0)) as t11,
                    sum(if(a.bulan = 12, a.`trans`, 0)) as t12
            from site.$filename a 
            GROUP BY a.site_code, a.kodeprod
            ";

        $hasil = $this->db->query($query);   

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'branch_name', 'nama_comp', 'kodeprod', 'namaprod', 'namasupp', 'nama_group', 'nama_sub_group', 'tahun', 'v1','v2','v3','v4','v5','v6','v7','v8','v9','v10','v11','v12','u1','u2','u3','u4','u5','u6','u7','u8','u9','u10','u11','u12', 't1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12'
        ));

        $this->excel_generator->set_column(array
        ( 
            'site_code', 'branch_name', 'nama_comp', 'kodeprod','namaprod','namasupp', 'nama_group', 'nama_sub_group', 'tahun', 'v1','v2','v3','v4','v5','v6','v7','v8','v9','v10','v11','v12','u1','u2','u3','u4','u5','u6','u7','u8','u9','u10','u11','u12', 't1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10, 10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('sell out product horizontal : '.$filename);

    }

    public function export_by_kodeprod_tipe_class($filename)
    {
        $query = "
            select  site_code, branch_name, nama_comp, bulan, tahun, a.kodeprod, a.namaprod, 
                    a.namasupp as principal, a.nama_group, a.nama_sub_group, 
                    a.kode_type, a.nama_type, a.sektor, a.segment, a.kodesalur, a.namasalur, a.groupsalur,
                    value, unit, created_at
            from site.$filename a
            order by branch_name, nama_comp
        ";
        $hasil = $this->db->query($query);   
    
        query_to_csv($hasil,TRUE,"selloutproduct_$filename.csv");
    }

    public function export_by_kodeprod_tipe_class_backup($filename)
    {
        $query = "
            select  site_code, branch_name, nama_comp, bulan, tahun, a.kodeprod, a.namaprod, 
                    a.namasupp as principal, a.nama_group, a.nama_sub_group, 
                    a.kode_type, a.nama_type, a.sektor, a.segment, a.kodesalur, a.namasalur, a.groupsalur,
                    value, unit, created_at
            from site.$filename a
            order by branch_name, nama_comp
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'branch_name', 'nama_comp','bulan','tahun', 'kodeprod', 'namaprod', 'principal', 'nama_group', 'nama_sub_group',
            'kode_type', 'nama_type', 'sektor', 'segment', 'kodesalur', 'namasalur', 'groupsalur', 'value','unit','created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'site_code', 'branch_name', 'nama_comp','bulan','tahun', 'kodeprod', 'namaprod', 'principal', 'nama_group', 'nama_sub_group',
            'kode_type', 'nama_type', 'sektor', 'segment', 'kodesalur', 'namasalur', 'groupsalur', 'value','unit','created_at'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('sell out product by kodeprod tipe class : '.$filename); 
    }

    public function export_by_kodeprod_tipe_class_horizontal($filename)
    {
        $query = "
            select 	a.site_code, a.branch_name, a.nama_comp, a.kodeprod, a.namaprod,
                    a.namasupp, a.nama_group, a.nama_sub_group, 
                    a.kode_type, a.nama_type, a.sektor, a.segment, a.kodesalur, a.namasalur, a.groupsalur,
                    a.tahun,
                    sum(if(a.bulan = 1, a.`value`, 0)) as v1,
                    sum(if(a.bulan = 2, a.`value`, 0)) as v2,
                    sum(if(a.bulan = 3, a.`value`, 0)) as v3,
                    sum(if(a.bulan = 4, a.`value`, 0)) as v4,
                    sum(if(a.bulan = 5, a.`value`, 0)) as v5,
                    sum(if(a.bulan = 6, a.`value`, 0)) as v6,
                    sum(if(a.bulan = 7, a.`value`, 0)) as v7,
                    sum(if(a.bulan = 8, a.`value`, 0)) as v8,
                    sum(if(a.bulan = 9, a.`value`, 0)) as v9,
                    sum(if(a.bulan = 10, a.`value`, 0)) as v10,
                    sum(if(a.bulan = 11, a.`value`, 0)) as v11,
                    sum(if(a.bulan = 12, a.`value`, 0)) as v12,
                    sum(if(a.bulan = 1, a.`unit`, 0)) as u1,
                    sum(if(a.bulan = 2, a.`unit`, 0)) as u2,
                    sum(if(a.bulan = 3, a.`unit`, 0)) as u3,
                    sum(if(a.bulan = 4, a.`unit`, 0)) as u4,
                    sum(if(a.bulan = 5, a.`unit`, 0)) as u5,
                    sum(if(a.bulan = 6, a.`unit`, 0)) as u6,
                    sum(if(a.bulan = 7, a.`unit`, 0)) as u7,
                    sum(if(a.bulan = 8, a.`unit`, 0)) as u8,
                    sum(if(a.bulan = 9, a.`unit`, 0)) as u9,
                    sum(if(a.bulan = 10, a.`unit`, 0)) as u10,
                    sum(if(a.bulan = 11, a.`unit`, 0)) as u11,
                    sum(if(a.bulan = 12, a.`unit`, 0)) as u12,
                    sum(if(a.bulan = 1, a.`trans`, 0)) as t1,
                    sum(if(a.bulan = 2, a.`trans`, 0)) as t2,
                    sum(if(a.bulan = 3, a.`trans`, 0)) as t3,
                    sum(if(a.bulan = 4, a.`trans`, 0)) as t4,
                    sum(if(a.bulan = 5, a.`trans`, 0)) as t5,
                    sum(if(a.bulan = 6, a.`trans`, 0)) as t6,
                    sum(if(a.bulan = 7, a.`trans`, 0)) as t7,
                    sum(if(a.bulan = 8, a.`trans`, 0)) as t8,
                    sum(if(a.bulan = 9, a.`trans`, 0)) as t9,
                    sum(if(a.bulan = 10, a.`trans`, 0)) as t10,
                    sum(if(a.bulan = 11, a.`trans`, 0)) as t11,
                    sum(if(a.bulan = 12, a.`trans`, 0)) as t12
            from site.$filename a 
            GROUP BY a.site_code, a.kodeprod, a.kode_type, a.kodesalur
            ";

        $hasil = $this->db->query($query);   
        query_to_csv($hasil,TRUE,"selloutproduct_$filename.csv");
    }

    public function export_by_kodeprod_tipe_class_horizontal_backup($filename)
    {
        $query = "
            select 	a.site_code, a.branch_name, a.nama_comp, a.kodeprod, a.namaprod,
                    a.namasupp, a.nama_group, a.nama_sub_group, 
                    a.kode_type, a.nama_type, a.sektor, a.segment, a.kodesalur, a.namasalur, a.groupsalur,
                    a.tahun,
                    sum(if(a.bulan = 1, a.`value`, 0)) as v1,
                    sum(if(a.bulan = 2, a.`value`, 0)) as v2,
                    sum(if(a.bulan = 3, a.`value`, 0)) as v3,
                    sum(if(a.bulan = 4, a.`value`, 0)) as v4,
                    sum(if(a.bulan = 5, a.`value`, 0)) as v5,
                    sum(if(a.bulan = 6, a.`value`, 0)) as v6,
                    sum(if(a.bulan = 7, a.`value`, 0)) as v7,
                    sum(if(a.bulan = 8, a.`value`, 0)) as v8,
                    sum(if(a.bulan = 9, a.`value`, 0)) as v9,
                    sum(if(a.bulan = 10, a.`value`, 0)) as v10,
                    sum(if(a.bulan = 11, a.`value`, 0)) as v11,
                    sum(if(a.bulan = 12, a.`value`, 0)) as v12,
                    sum(if(a.bulan = 1, a.`unit`, 0)) as u1,
                    sum(if(a.bulan = 2, a.`unit`, 0)) as u2,
                    sum(if(a.bulan = 3, a.`unit`, 0)) as u3,
                    sum(if(a.bulan = 4, a.`unit`, 0)) as u4,
                    sum(if(a.bulan = 5, a.`unit`, 0)) as u5,
                    sum(if(a.bulan = 6, a.`unit`, 0)) as u6,
                    sum(if(a.bulan = 7, a.`unit`, 0)) as u7,
                    sum(if(a.bulan = 8, a.`unit`, 0)) as u8,
                    sum(if(a.bulan = 9, a.`unit`, 0)) as u9,
                    sum(if(a.bulan = 10, a.`unit`, 0)) as u10,
                    sum(if(a.bulan = 11, a.`unit`, 0)) as u11,
                    sum(if(a.bulan = 12, a.`unit`, 0)) as u12,
                    sum(if(a.bulan = 1, a.`trans`, 0)) as t1,
                    sum(if(a.bulan = 2, a.`trans`, 0)) as t2,
                    sum(if(a.bulan = 3, a.`trans`, 0)) as t3,
                    sum(if(a.bulan = 4, a.`trans`, 0)) as t4,
                    sum(if(a.bulan = 5, a.`trans`, 0)) as t5,
                    sum(if(a.bulan = 6, a.`trans`, 0)) as t6,
                    sum(if(a.bulan = 7, a.`trans`, 0)) as t7,
                    sum(if(a.bulan = 8, a.`trans`, 0)) as t8,
                    sum(if(a.bulan = 9, a.`trans`, 0)) as t9,
                    sum(if(a.bulan = 10, a.`trans`, 0)) as t10,
                    sum(if(a.bulan = 11, a.`trans`, 0)) as t11,
                    sum(if(a.bulan = 12, a.`trans`, 0)) as t12
            from site.$filename a 
            GROUP BY a.site_code, a.kodeprod, a.kode_type, a.kodesalur
            ";

        $hasil = $this->db->query($query);   

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'branch_name', 'nama_comp', 'kodeprod', 'namaprod', 'namasupp', 'nama_group', 'nama_sub_group', 
            'kode_type', 'nama_type', 'sektor', 'segment', 'kodesalur', 'namasalur', 'groupsalur',
            'tahun', 'v1','v2','v3','v4','v5','v6','v7','v8','v9','v10','v11','v12','u1','u2','u3','u4','u5','u6','u7','u8','u9','u10','u11','u12', 't1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12'
        ));

        $this->excel_generator->set_column(array
        ( 
            'site_code', 'branch_name', 'nama_comp', 'kodeprod','namaprod','namasupp', 'nama_group', 'nama_sub_group', 
            'kode_type', 'nama_type', 'sektor', 'segment', 'kodesalur', 'namasalur', 'groupsalur',
            'tahun', 'v1','v2','v3','v4','v5','v6','v7','v8','v9','v10','v11','v12','u1','u2','u3','u4','u5','u6','u7','u8','u9','u10','u11','u12', 't1','t2','t3','t4','t5','t6','t7','t8','t9','t10','t11','t12'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10, 10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('sell out product by kodeprod tipe class horizontal : '.$filename);

    }

    public function history_penarikan()
    {
        $data = [
            'title'     => 'sell out product | history penarikan data',      
            'url'       => 'management_sales/history_penarikan_proses',
            'data'      => $this->model_management_sales->get_history_penarikan(),
        ];        
        $this->render('management_sales/history_penarikan', $data);
    }

    public function master_principal()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        // kode_company di comment karena API ini digunakan juga di Master Karyawan untuk RPD
        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key,
            'userid' => $this->session_id
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_principal?" . http_build_query($params),
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
            // echo "<option value=''> user ? </option>";

            foreach ($result as $key => $r)
            {
                echo "<option value='". $r["supp"] . "' >";
                echo $r["namasupp"];
                echo "</option>";
            }
        }
    }

    public function master_source()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
       
        $breakdown = $this->input->post('breakdown');

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'breakdown'      => $breakdown,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_source_selloutproduct?" . http_build_query($params),
            // CURLOPT_URL => "http://localhost:81/restapi/api/master_data/master_source_selloutproduct?" . http_build_query($params),
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
            // echo "<option value=''> Pastikan memilih breakdown terlebih dahulu </option>";
            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            foreach ($datas as $key => $a)
            {
                echo "<option value='". $a["id"]. "' >";
                echo $a["name_table"]. " - ". $a["created_at"]. " - ". $a["nama_status_using"];
                echo "</option>";
            }
        }
    }


    public function cooking(){
        // echo "Fish"."\n";
        // yield;
        // echo "Chicken"."\n";
        // yield;
        // echo "Beef"."\n";
        // yield;
        // echo "Mutton"."\n";
        // yield;
        // echo "Pork"."\n";
        // yield;
        // echo "Rice"."\n";
        // yield;
        // echo "Noodles"."\n";
        // yield;

        echo "Fish"."\n";
        while(true){
            $string = yield;
            echo $string . PHP_EOL;
        }
    }

    

    public function test(){
        // $task = $this->cooking();
        // $current = $task->current();
        // echo $current;
        // $next = $task->next();
        // echo $next;
        // $last = $task->next();
        // echo $last;

        $task = $this->cooking();
        $task->send("Potatoes");
        $task->send("Sauces");
    }

}
?>
