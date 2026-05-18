<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mti extends MY_Controller
{    
    function mti()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_mti'));

        $this->email_tim = 'deltomed@test.com';
        $this->created_by = $this->session->userdata('id');
        $this->tahun_folder = 2025;
    }

    function index()
    {
        $this->dashboard();
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

    public function dashboard()
    {
        $bulan = $this->input->get('bulan');

       

        // herbal        
        $get_kodeprod_herbal = $this->model_mti->get_kodeprod_by_group('G0101','001');
        if($get_kodeprod_herbal->num_rows() > 0)
        {
            foreach ($get_kodeprod_herbal->result_array() as $a) 
            {
                $kodeprod_herbal[] = $a['kodeprod'];
            }
            $kodeprod_herbal_string = implode(', ', $kodeprod_herbal);
        }

        

         // candy        
        $get_kodeprod_candy = $this->model_mti->get_kodeprod_by_group('G0102','001');
        if($get_kodeprod_candy->num_rows() > 0)
        {
            foreach ($get_kodeprod_candy->result_array() as $a) 
            {
                $kodeprod_candy[] = $a['kodeprod'];
            }
            $kodeprod_candy_string = implode(', ', $kodeprod_candy);
        }

         // ALL DELTOMED PRODUCT        
        $get_kodeprod_deltomed = $this->model_mti->get_kodeprod_by_group('','001');
        if($get_kodeprod_deltomed->num_rows() > 0)
        {
            foreach ($get_kodeprod_deltomed->result_array() as $a) 
            {
                $kodeprod_deltomed[] = $a['kodeprod'];
            }
            $kodeprod_deltomed_string = implode(', ', $kodeprod_deltomed);
        }

        // all product all principal
        $get_kodeprod_all = $this->model_mti->get_kodeprod_by_group('','');
        if($get_kodeprod_all->num_rows() > 0)
        {
            foreach ($get_kodeprod_all->result_array() as $a) 
            {
                $kodeprod_all[] = $a['kodeprod'];
            }
            $kodeprod_all_string = implode(', ', $kodeprod_all);
        }

        // kode_type_mti
        $get_kode_type_mti = $this->model_mti->get_kode_type_by_sektor('mti');
        if($get_kode_type_mti->num_rows() > 0)
        {
            foreach ($get_kode_type_mti->result_array() as $a) 
            {   
                $kode_type_mti[] = '"' . $a['kode_type'] . '"';
            }
            $get_kode_type_mti_string = implode(', ', $kode_type_mti);
        }

        // kode_type_apotek
        $get_kode_type_apotik = $this->model_mti->get_kode_type_by_sektor('apotik');
        if($get_kode_type_apotik->num_rows() > 0)
        {
            foreach ($get_kode_type_apotik->result_array() as $a) 
            {   
                $kode_type_apotik[] = '"' . $a['kode_type'] . '"';
            }
            $get_kode_type_apotik_string = implode(', ', $kode_type_apotik);
        }

    

        $data = [
            "title" => "Dashboard MTI",
            'url'   => 'mti/dashboard',
            'get_herbal'  => $this->model_mti->get_data($bulan, $kodeprod_herbal_string, $get_kode_type_mti_string),
            'get_candy'  => $this->model_mti->get_data($bulan, $kodeprod_candy_string, $get_kode_type_mti_string),
            'get_deltomed_mti'  => $this->model_mti->get_data($bulan, $kodeprod_deltomed_string, $get_kode_type_mti_string),
            'get_all_principal_mti'  => $this->model_mti->get_data($bulan, $kodeprod_all_string, $get_kode_type_mti_string),
            'get_herbal_apotik'  => $this->model_mti->get_data($bulan, $kodeprod_herbal_string, $get_kode_type_apotik_string),
            'get_candy_apotik'  => $this->model_mti->get_data($bulan, $kodeprod_candy_string, $get_kode_type_apotik_string),
            'get_deltomed_apotik'  => $this->model_mti->get_data($bulan, $kodeprod_deltomed_string, $get_kode_type_apotik_string),
        ];
        $this->view($data, false, "dashboard");
    }

    private function view($data, $flag_accordion, $view)
    {
        $data = [
            "navbar"        => $this->navbar($data),
            "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
            "css"           => $this->load->view('management_claim/css', $data),
            "view"          => $this->load->view('mti/'.$view.'', $data),
            "footer"        => $this->load->view('kalimantan/footer')
        ];
        return $data;       
    }
}
?>
