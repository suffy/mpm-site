<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload_data_text extends MY_Controller
{    
    function upload_data_text()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_target_principal'));
    }
    function index()
    {
        $data = [
            'title'     => 'Upload Data Txt Afiliasi | Form Upload',
            'url'       => 'target_principal/import_deltomed_target_by_subbranch'
        ];

        $this->load->view('mti/header');
        $this->load->view('upload_data_text/index', $data);
        $this->load->view('mti/footer');
    }

    

    

}
?>
