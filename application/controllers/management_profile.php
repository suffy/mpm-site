<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_profile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'Management Profile';
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/management_asset','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
    }
    
    function index()
    {
        $this->signature();
    }

    public function signature()
    {
        $data = [
            'title'     => 'Digital Signature',
            'url'       => 'management_profile/signature_tambah',
        ];
        
        $this->render('management_profile/signature', $data);
    }

    public function signature_tambah()
    {
        $id = $this->session->userdata('id');
        $username = $this->session->userdata('username');
        // var_dump($id);die;

        $folderPath = './assets/uploads/signature/';        
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $username. '-signature.' .$image_type;
        file_put_contents($file, $image_base64);
        
        redirect("management_profile/signature/",'refresh');
    }

    public function signature_digital(){
        
        $data = [
            'title'     => 'Digital Signature',
            'url'       => "management_profile/signature_tambah",
        ];

        $this->render_multiple(
            array(
                'template_claim/top_header_signature',
                'management_rpd/signature_digital'
            ),
            $data
        );
    }
}