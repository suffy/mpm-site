<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_report extends MY_Controller
{    
    function management_report()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_bonus', 'model_management_report'));
    }
    function index()
    {
        $this->dashboard();
    }

    public function dashboard(){
        $data = [
            'title'             => 'Dashboard',
            'get_master_data'   => $this->model_management_bonus->get_master_data(),
            'url'               => 'management_bonus/import_master_data'
        ];

        $this->load->view('management_report/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_report/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }
}
?>
