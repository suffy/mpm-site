<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Management_raw_data extends MY_Controller
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
        $this->load->model(array('model_outlet_transaksi','model_management_raw_data', 'model_sales_omzet','model_management_office'));
        $this->userid = $this->session->userdata('id');
        $this->username = $this->session->userdata('username');

        if($this->session->userdata('supp') == '000' && $this->session->userdata('level') == 10)
        {
            $this->cek_karyawan();
        }

    }

    public function cek_karyawan()
    {
      $query = $this->model_management_office->get_karyawan_by_username($this->username);

      if($query->num_rows() > 0)
      {
        // redirect('management_raw_data');
      }else{
        $this->load->view('info_karyawan');
      }
    }

    // function management_raw_data()
    // {       
    //     $logged_in= $this->session->userdata('logged_in');
    //     if(!isset($logged_in) || $logged_in != TRUE)
    //     {
    //         redirect('login_sistem/','refresh');
    //     }
    //     set_time_limit(0);
        
    //     $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    //     $this->load->helper(array('url', 'csv'));
    //     $this->load->model(array('model_outlet_transaksi','model_management_raw_data', 'model_sales_omzet'));
    //     $this->userid = $this->session->userdata('id');
    //     $this->username = $this->session->userdata('username');
    // }

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
        // echo "aaa";
        // die;
        $cek_principal_akses = $this->model_management_raw_data->get_akses_principal_by_userid($this->userid);

        if ($cek_principal_akses->num_rows() > 0) {
            foreach ($cek_principal_akses->result() as $key) {
                $supp[] = $key->supp;
            }
            $supp = implode(",", $supp);
            $params_status_principal = 1;
        } else {    
            $supp = $this->session->userdata('supp');
            $params_status_principal = 0;
        }

        $data = [
            "title" => "Portal Raw Data",
            'url'   => 'management_raw_data/dashboard',
            'url_upload' => 'management_raw_data/attachment_config',
            'list_data_harian' => $this->model_management_raw_data->get_list_raw($params_status_principal, $supp, "Harian")->result(),
            'list_data_closing_bulanan' => $this->model_management_raw_data->get_list_raw($params_status_principal, $supp, "Closing Bulanan")->result(),
            'username' => $this->username
        ];

        // $this->view($data, false, "dashboard");
        $this->render('management_raw_data/dashboard', $data);
    }

    private function view($data, $flag_accordion, $view)
    {
        $data = [
            "navbar"        => $this->navbar($data),
            "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
            "css"           => $this->load->view('management_claim/css', $data),
            "view"          => $this->load->view('management_raw_data/'.$view.'', $data),
            "footer"        => $this->load->view('kalimantan/footer')
        ];
        return $data;       
    }

    public function attachment_config($id)
    {

        if ($this->username != 'rifqi') {
            $this->session->set_flashdata('pesan', 'Anda tidak memiliki hak akses untuk uplaod file');
            die;
        }
        
        $path = './assets/file/berita_acara/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $this->load->library('upload');
        $files = $_FILES;
        $count = count($_FILES['berita_acara']['name']);

        $uploaded_filenames = [];

        for ($i = 0; $i < $count; $i++) {
            $_FILES['file']['name'] = $files['berita_acara']['name'][$i];
            $_FILES['file']['type'] = $files['berita_acara']['type'][$i];
            $_FILES['file']['tmp_name'] = $files['berita_acara']['tmp_name'][$i];
            $_FILES['file']['error'] = $files['berita_acara']['error'][$i];
            $_FILES['file']['size'] = $files['berita_acara']['size'][$i];

            $config['upload_path']   = $path;
            $config['allowed_types'] = '*';
            $config['max_size']      = '2048000';
            $config['encrypt_name']  = false;
            $config['overwrite']     = false;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
                $uploaded_filenames[] = $upload_data['orig_name']; // pakai original name
            }
        }

        // Simpan 1 baris ke database
        if (!empty($uploaded_filenames)) {
            $data = [
                'id_t_list_raw' => $id,
                'filename' => json_encode($uploaded_filenames), // bisa juga pakai implode(",", $uploaded_filenames)
                'signature' => md5($id),
                'created_at' => $this->model_sales_omzet->timezone2(),
            ];
            $this->model_management_raw_data->insert_attachment_t_list_raw($data);
        }else {
            $this->session->set_flashdata('pesan', 'File gagal diupload');
        }

        redirect('management_raw_data/dashboard');
    }

    public function download_file()
    {
        $folder = $this->uri->segment(3);
        $file = $this->uri->segment(4);

        $username = $this->model_management_raw_data->get_user_by_id($this->userid)->row()->username;

        $move_folder = md5($username);
        if (!file_exists("./assets/file/portal_raw/$move_folder/")) {
            mkdir("./assets/file/portal_raw/$move_folder/", 0777, true);
        }

        $source = base_url("assets/file/portal_raw/raw_data/$folder/$file"); 
        $destination = "./assets/file/portal_raw/$move_folder/$file"; 

        if( !copy($source, $destination) ) { 
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('File Tidak Ditemukan');
            window.location.href='';
            </script>");
            redirect(base_url("portal_raw"));
        } 
        else { 
            redirect(base_url("assets/file/portal_raw/$move_folder/$file"));
        } 
    }

    public function download_zip($signature)
    {
        $this->load->library('zip');

        // $this->db->from('db_raw.attachment_t_list_raw');
        // $this->db->where('signature', $signature);
        // $data = $this->db->get()->result();

        $data = $this->model_management_raw_data->get_attachment_t_list_raw($signature)->result();
        // var_dump($data);die;

        if (empty($data)) {
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
            redirect('management_raw_data');
        }

        $file_list = [];

        foreach ($data as $row) {
            $decoded = json_decode($row->filename, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $file_list = array_merge($file_list, $decoded);
            } else {
                $file_list[] = $row->filename;
            }
        }

        foreach ($file_list as $filename) {
            $path = FCPATH . 'assets/file/berita_acara/' . $filename;
            if (file_exists($path)) {
                $this->zip->read_file($path);
            }else{
                $this->session->set_flashdata('pesan', 'File Berita Acara tidak ditemukan');
                redirect('management_raw_data');
            }
        }

        $zip_name = 'berita_acara.zip';
        $this->zip->download($zip_name);
        }


}
