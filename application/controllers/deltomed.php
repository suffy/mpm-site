<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deltomed extends MY_Controller
{    
    function deltomed()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_deltomed'));

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
        $from = $this->input->get('from');
        $to   = $this->input->get('to');

        $submit = $this->input->get('submit');

        // echo "submit : ".$submit;

        if ($submit == "export") {
            $this->model_deltomed->export_spreading($from, $to);
            return;
        }

        if ($submit == "export_by_products") {
            $this->model_deltomed->export_spreading_products($from, $to);
            return;
        }
        

        // echo "from : ".$from." to : ".$to;

        $data = [
            "title" => "Deltomed Spreading",
            'url'   => 'deltomed/dashboard',
            'get_data'  => $this->model_deltomed->get_spreading_post($from, $to),
        ];
        $this->view($data, false, "dashboard");
    }

    private function view($data, $flag_accordion, $view)
    {
        $data = [
            "navbar"        => $this->navbar($data),
            "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
            "css"           => $this->load->view('management_claim/css', $data),
            "view"          => $this->load->view('deltomed/'.$view.'', $data),
            "footer"        => $this->load->view('kalimantan/footer')
        ];
        return $data;       
    }

    public function posting()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');

        // echo "from : ".$from." to : ".$to;

        $data = [
            "title" => "Daily Activity MPM AREA",
            'url'   => 'deltomed/posting',
            'get_data'  => $this->model_deltomed->get_posting_post($from, $to),
        ];
        $this->view($data, false, "posting");
    }
}
?>
