<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test extends MY_Controller
{
    function test()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->database();

    }

    public function index(){
        echo 'hello world';
    }




}
?>