<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Barcode extends MY_Controller
{    
    function barcode()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_barcode'));
        $this->email_tim = 'tim@test.com, tim2@test.com';
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->userid = $this->session->userdata('id');
    }

    function navbar($data)
    {
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

    public function index()
    {
        $this->upload();
    }

    public function upload()
    {
        $this->load->library('upload');

        $data = [
            'title'     => 'Barcode | Permintaan Print Barcode',
            'url'       => 'barcode/upload_proses',
            'get_data'  => $this->model_barcode->get_barcode_request()
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('barcode/upload', $data);
        $this->load->view('kalimantan/footer');
    }

    public function upload_proses()
    {
        $nama   = $this->input->post('nama');
        $hp  = $this->input->post('hp');
        $alamat_penerima  = $this->input->post('alamat_penerima');
        $total_barcode  = $this->input->post('total_barcode');
        $file  = $this->input->post('file');

        $signature = 'barcode-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/barcode/')) {
            @mkdir('./assets/uploads/barcode/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/barcode/';
        $config['allowed_types'] = '*';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
        }else{
            var_dump($this->upload->display_errors());
            // $this->session->set_flashdata("pesan", "Submit gagal. Pastikan file yang diupload berformat .xml / .zip");
            // redirect('barcode/upload', 'refresh'); 
            die;
        };

        $total_jam = ceil($total_barcode / 800);

        $data = [
            "nama" => $nama,
            "hp" => $hp,
            "alamat_penerima" => $alamat_penerima,
            "file" => $file_name,
            "total_barcode" => $total_barcode,
            "total_jam" => $total_jam,
            "status"    => 1,
            "nama_status" => "Pending",
            "signature" => $signature,
            "created_at" => $this->created_at,
            "created_by" => $this->userid
        ];

        $this->model_barcode->insert_request($data);
        $this->session->set_flashdata("pesan_success", "submit data berhasil. Silahkan tunggu informasi selanjutnya");
        redirect('barcode/upload', 'refresh');        
    }

    public function update_status($signature)
    {

        if ($this->session->userdata('username') <> 'milla' && $this->session->userdata('username') <> 'suffy') 
        {
            $this->session->set_flashdata("pesan", "Update Gagal. User anda tidak diizinkan mengupdate status barcode");
            redirect('barcode', 'refresh');
        }

        // cek status terakhir
        $get_status = $this->model_barcode->get_barcode_request($signature);
        
        if (!$get_status->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('barcode/upload', 'refresh');
        }else{
            $status = $get_status->row()->status;
            $id = $get_status->row()->id;
        }

        if ($status == 1) { // jika pending, maka ubah menjadi finish
            $params_status = 2;  // status 2 = finish
            $nama_status = "finish";
        }elseif ($status == 2) { // jika pending, maka ubah menjadi finish
            $params_status = 3;  // status 2 = finish
            $nama_status = "ditolak";
        }else{
            $params_status = 1; // status 2 = finish
            $nama_status = "pending";
        }

        $data = [
            "status"    => $params_status,
            "nama_status" => $nama_status,
            "updated_at" => $this->created_at,
            "updated_by" => $this->userid
        ];

        $this->model_barcode->update_barcode_request($data, $id);
        $this->session->set_flashdata("pesan_success", "Update berhasil");
        redirect('barcode/upload', 'refresh');
    }

}
?>
