<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_dashboard extends MY_Controller
{    
    function management_dashboard()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_management_dashboard'));
    }
    function index()
    {
        $this->target();
    }

    function navbar($data){
        if ($this->session->userdata('level') === '4') { // jika dp
            $this->load->view('management_office/top_header_dp', $data);
        }elseif ($this->session->userdata('level') === '3') { // jika principal
            $this->load->view('management_office/top_header_principal', $data);
        }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales
            $this->load->view('management_office/top_header_principal_nosales', $data);
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function target($bulan = ""){

        $delete = $this->input->get('delete');
        $btn_target = $this->input->get('btn_target');
        if ($delete == "Delete") {
            // $this->model_management_dashboard->delete_target();
        }elseif ($btn_target == "export") {
            $this->export_target($this->input->get('bulan'));
            die;
        }

        if ($this->input->get('bulan')) {
            $params_tahun = substr($this->input->get('bulan'), 0, 4);
            $params_bulan = substr($this->input->get('bulan'), 5, 2);
        }else{
            $params_tahun = date('Y');
            $params_bulan = date('m');
        }

        $data = [
            'title'             => 'Generate Target Baru',
            'url'               => 'management_dashboard/generate_target',
            'url_search'        => 'management_dashboard/target',
            'url_save'          => 'management_dashboard/save_target',
            'get_master_target' => $this->model_management_dashboard->get_master_target($params_tahun, $params_bulan)
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_dashboard/target', $data);
        $this->load->view('kalimantan/footer');
    }    

    public function generate_target(){
        $bulan = $this->input->post('bulan');
        $params_tahun = substr($bulan, 0, 4);
        $params_bulan = substr($bulan, 5, 2);

        $get_master_target = $this->model_management_dashboard->get_master_target($params_tahun, $params_bulan);
        if ($get_master_target->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "data sudah ada. Jika ingin menimpa data, silahkan hapus data terlebih dahulu");
            redirect('management_dashboard/target/', 'refresh');
            die;
        }

        $generate_target = $this->model_management_dashboard->generate_target($bulan);
        $this->session->set_flashdata("pesan_success", "generate data selesai. Anda sudah bisa  mengisi data target.");
        redirect('management_dashboard/target?bulan='.$bulan.'', 'refresh');
        die;
    }

    public function save_target(){

        $bulan = $this->input->post('bulan');
        $id = $this->input->post('options'); 
        foreach ($id as $id_target) {
            $data = [
                'target_value_be'   => $this->input->post('target_value_be')[$id_target],
                'target_value_poh'  => $this->input->post('target_value_poh')[$id_target],
                'target_principal'  => $this->input->post('target_principal')[$id_target],
                'target_ot_kpi'     => $this->input->post('target_ot_kpi')[$id_target],
                'target_ot_otsc'    => $this->input->post('target_ot_otsc')[$id_target],
            ];
            $this->db->update('site.dashboard_master_target', $data, ['id' => $id_target]);
        }

        $this->session->set_flashdata("pesan_success", "update data selesai");
        if ($bulan) {
            redirect('management_dashboard/target?bulan='.$bulan, 'refresh');
        }else{
            redirect('management_dashboard/target', 'refresh');
        }
        die;
    }

    public function dashboard($bulan = ""){

        $btn_dashboard = $this->input->get('btn_dashboard');
        if ($btn_dashboard == "export") {
            $this->export_dashboard($this->input->get('bulan'));
            die;
        }


        if ($this->input->get('bulan')) {
            $params_tahun = substr($this->input->get('bulan'), 0, 4);
            $params_bulan = substr($this->input->get('bulan'), 5, 2);
        }else{
            $params_tahun = date('Y');
            $params_bulan = date('m');
        }

        $data = [
            'title'             => 'Dashboard',
            'url'               => 'management_dashboard/generate_dashboard',
            'url_search'        => 'management_dashboard/dashboard',
            'url_save'          => 'management_dashboard/save_target',
            'get_dashboard_report_sales' => $this->model_management_dashboard->get_dashboard_report_sales($params_tahun, $params_bulan)
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_dashboard/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    public function generate_dashboard(){
        $bulan = $this->input->post('bulan');
        $params_tahun = substr($bulan, 0, 4);
        $params_bulan = substr($bulan, 5, 2);

        $delete_temp_sales = $this->model_management_dashboard->delete_temp_sales($params_tahun, $params_bulan);
        $delete_temp_sales_mundur = $this->model_management_dashboard->delete_temp_sales_mundur($params_tahun, $params_bulan);

        $get_master_site = $this->model_management_dashboard->get_master_site();
        $site_code = '';
        foreach ($get_master_site->result() as $b) {
            $site_code.= ","."'".$b->site_code."'";
        }
        $params_site_code = preg_replace('/,/', '', $site_code,1);
        
        $get_master_divisi = $this->model_management_dashboard->get_master_divisi();
        foreach ($get_master_divisi->result() as $a) {
            $nama_divisi = $a->nama_divisi;
            $get_kodeprod_by_divisi = $this->model_management_dashboard->get_kodeprod_by_divisi($nama_divisi);

            $kodeprod = '';
            foreach ($get_kodeprod_by_divisi->result() as $b) {
                $kodeprod.= ","."'".$b->kodeprod."'";
            }
            $params_kodeprod = preg_replace('/,/', '', $kodeprod,1);

            $generate_dashboard_by_divisi_n_produk = $this->model_management_dashboard->generate_dashboard_by_divisi_n_produk($params_tahun, $params_bulan, $nama_divisi, $params_kodeprod, $params_site_code);

            $generate_ot_mundur_by_divisi_n_produk = $this->model_management_dashboard->generate_ot_mundur_by_divisi_n_produk($params_tahun, $params_bulan, $nama_divisi, $params_kodeprod, $params_site_code);
        }
        
        $delete_report_sales = $this->model_management_dashboard->delete_report_sales($params_tahun, $params_bulan);
        $generate_report_sales = $this->model_management_dashboard->generate_report_sales($params_tahun, $params_bulan);


        
        // die;

        redirect('management_dashboard/dashboard?bulan='.$bulan.'', 'refresh');

    }

    public function export_target($bulan){

        $params_tahun = substr($bulan, 0, 4);
        $params_bulan = substr($bulan, 5, 2);

        $query = "
            select 	a.tahun, a.bulan, a.site_code, b.branch_name, b.nama_comp, a.divisi, 
                    a.target_value_be, a.target_value_poh, a.target_principal, a.target_ot_kpi, a.target_ot_otsc,
                    a.created_at, c.username as created_by, 
                    a.updated_at, d.username as updated_by
            from site.dashboard_master_target a LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code left join (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id left join (
                select a.id, a.username
                from mpm.user a 
            )d on a.updated_by = d.id
            where a.tahun = $params_tahun and a.bulan = $params_bulan
        ";

        $hasil = $this->db->query($query); 

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'tahun','bulan','site_code','branch_name','nama_comp','divisi','target_value_be','target_value_poh','target_principal','target_ot_kpi','target_ot_otsc','created_at','created_by','updated_at','updated_by'
        ));
        $this->excel_generator->set_column(array
        ( 
            'tahun','bulan','site_code','branch_name','nama_comp','divisi','target_value_be','target_value_poh','target_principal','target_ot_kpi','target_ot_otsc','created_at','created_by','updated_at','updated_by'
        ));
        $this->excel_generator->set_width(array(10,20,10,20,20,20,20,20,20,20,20,20,20,20,20,20)); 
        $this->excel_generator->exportTo2007('Download Target - '.$bulan); 
        
    }

    public function export_dashboard($bulan){

        $params_tahun = substr($bulan, 0, 4);
        $params_bulan = substr($bulan, 5, 2);

        $query = "
            select a.*, b.*, c.username as updated_by
            from site.dashboard_report_sales a left join (
                select 	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from 	mpm.tbl_tabcomp a 
                where 	a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code left join (
                select a.id, a.username, a.email
                from mpm.user a 
            )c on a.updated_by = c.id
            where a.tahun = $params_tahun and a.bulan = $params_bulan
        ";

        $hasil = $this->db->query($query); 

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'tahun','bulan','site_code','branch_name','nama_comp','divisi', 'target_principal', 'target_poh', 'realisasi_poh',
            'ach_poh', 'target_value_be', 'realisasi_be', 'ach_be', 'target_ot', 'realisasi_ot', 'ach_ot', 'ot_mundur', 'realisasi_ot_berjalan', 'ach_ot_mundur', 'updated_at', 'updated_by'
        ));
        $this->excel_generator->set_column(array
        ( 
            'tahun','bulan','site_code','branch_name','nama_comp','divisi', 'target_principal', 'target_poh', 'realisasi_poh',
            'ach_poh', 'target_value_be', 'realisasi_be', 'ach_be', 'target_ot', 'realisasi_ot', 'ach_ot', 'ot_mundur', 'realisasi_ot_berjalan', 'ach_ot_mundur', 'updated_at', 'updated_by'
        ));
        $this->excel_generator->set_width(array(10,20,10,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20)); 
        $this->excel_generator->exportTo2007('Download Dashboard - '.$bulan); 
        
    }

}
?>
