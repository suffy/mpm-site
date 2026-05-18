<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_rpd extends MY_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'Perjalanan Dinas';
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv', 'download'));
        $this->load->model(array('model_outlet_transaksi', 'model_management_rpd'));
    }

    // function management_rpd()
    // {       
    //     $logged_in= $this->session->userdata('logged_in');
    //     if(!isset($logged_in) || $logged_in != TRUE)
    //     {
    //         redirect('login_sistem/','refresh');
    //     }
    //     set_time_limit(0);
    //     $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    //     $this->load->helper(array('url', 'csv', 'download'));
    //     $this->load->model(array('model_outlet_transaksi', 'model_management_rpd'));

    //     // if ($this->session->userdata('username') != 'boby' && $this->session->userdata('username') != 'milla' && $this->session->userdata('username') != 'ilham') { // jika dp
    //     //     redirect('management_office');
    //     //     die;
    //     // }
    // }

    function index()
    {
        $this->dashboard();
        // if ($this->session->userdata('id') == '749') {
        //     $this->dashboard();
        // }else {
        //     redirect('management_office/','refresh');
        // }
    }
    
    public function dashboard(){
        $data = [
            'title'             => 'Dashboard',
            'get_pengajuan'   => $this->model_management_rpd->get_pengajuan(),
            'url'               => 'management_bonus/import_master_data'
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_report/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    public function pengajuan(){
        // if ($this->session->userdata('username') != 'milla' && $this->session->userdata('username') != 'suffy' && $this->session->userdata('username') != 'ilham'  && $this->session->userdata('username') != 'Tri') {
        //     echo "<script>alert('Management RPD Sedang di Maintenance'); </script>";
        //     redirect('management_office/','refresh');
        // }

        $this->load->model('model_master_data');

        $get_username_by_id = $this->model_master_data->get_username_by_id($this->session->userdata('id'));
        if ($get_username_by_id->num_rows() > 0) {
            $name = $get_username_by_id->row()->name;
            $jabatan = $get_username_by_id->row()->jabatan;
            $level_karyawan = $get_username_by_id->row()->level_karyawan;
        }else{
            $name = "-";
            $jabatan = "-";
            $level_karyawan = "-";
        }

        $data = [
            'title'         => 'Rencana Perjalanan Dinas - Input Pengajuan',
            'get_pengajuan' => $this->model_management_rpd->get_pengajuan(),
            'url'           => 'management_rpd/pengajuan_tambah',
            'name'          => $name,
            'jabatan'       => $jabatan,
            'level_karyawan' => $level_karyawan    
        ];
        
        $this->render('management_rpd/pengajuan', $data);
    }

    public function pengajuan_tambah(){

        if(!$this->input->post('pelaksana')){
            redirect('management_rpd/pengajuan');
            die;
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'RPD-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $attachment_radius_perjalanan = $this->input->post('attachment_radius_perjalanan');

        if (!is_dir('./assets/file/rpd/')) {
            @mkdir('./assets/file/rpd/', 0777);
        }
        $config['upload_path'] = './assets/file/rpd/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '*';
        $config['overwrite'] = 'false';

        $this->load->library('upload', $config);
        if(!empty('attachment_radius_perjalanan')){
            if($this->upload->do_upload('attachment_radius_perjalanan')){
                $uploadData     = $this->upload->data();
                $filename       = $uploadData['file_name'];
            }else{ 

                $filename = null;
                // echo "aaaa";
                // die;
                // $this->session->set_flashdata("pesan", "RPD anda gagal. Ada kesalahan di Attachment");
                // redirect('management_rpd/pengajuan/');
                // die;
            }
        }

        // die;


        $data = [
            'pelaksana'                     => $this->input->post('pelaksana'),
            'jabatan'                       => $this->input->post('jabatan'),
            'maksud_perjalanan_dinas'       => $this->input->post('maksud_perjalanan_dinas'),
            'tanggal_berangkat'             => $this->input->post('tanggal_berangkat'),
            'tempat_berangkat'              => $this->input->post('tempat_berangkat'),
            'tanggal_tiba'                  => $this->input->post('tanggal_tiba'),
            'tempat_tiba'                   => $this->input->post('tempat_tiba'),
            'tanggal_mulai'                 => $this->input->post('tanggal_mulai'),
            'tanggal_akhir'                 => $this->input->post('tanggal_akhir'),
            'radius_perjalanan'             => $this->input->post('radius_perjalanan'),
            'status'                        => 6,
            'nama_status'                   => 'pending user',
            'attachment_radius_perjalanan'  => $filename,
            'created_at'                    => $created_at,
            'created_by'                    => $this->session->userdata('id'),
            'signature'                     => $signature
        ];
        //    var_dump($data); die;
        $this->db->insert('management_rpd.pengajuan', $data);
        $id = $this->db->insert_id();       
        
        // phpinfo();
        $signature = $this->model_management_rpd->get_pengajuan($id)->row()->signature;

        $this->session->set_flashdata("pesan_success", "pengajuan rpd anda terbentuk. Namun lengkapi aktivitas terlebih dahulu dan ajukan ke atasan");

        redirect('management_rpd/aktivitas/'.$signature);
    }

    public function aktivitas($signature){
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1         = $key->username_verifikasi1;
            $userid_verifikasi1           = $key->userid_verifikasi1;
            $username_verifikasi2         = $key->username_verifikasi2;
            $userid_verifikasi2           = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at           = $key->verifikasi2_at;
            $verifikasi1_at           = $key->verifikasi1_at;
            $verifikasi1_keterangan           = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan           = $key->verifikasi2_keterangan;
            $verifikasi1_ttd           = $key->verifikasi1_ttd;
            $verifikasi2_ttd           = $key->verifikasi2_ttd;
            $jumlah_verifikasi           = $key->jumlah_verifikasi;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Input Rincian Aktivitas',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/aktivitas_tambah',
            'url_verifikasi'              => 'management_rpd/verifikasi_pengajuan',
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
            'verifikasi1_ttd'          => $verifikasi1_ttd,
            'verifikasi2_ttd'          => $verifikasi2_ttd,
            'jumlah_verifikasi'          => $jumlah_verifikasi,
        ];
        
        $this->render('management_rpd/aktivitas', $data);

    }

    public function aktivitas_tambah(){
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature_pengajuan = $this->input->post('signature_pengajuan');
        $signature = 'RPD-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'aktivitas'          => $this->input->post('aktivitas'),
            'id_pengajuan'       => $this->input->post('id_pengajuan'),
            'detail_aktivitas'   => $this->input->post('detail_aktivitas'),
            'tanggal_aktivitas'  => $this->input->post('tanggal_aktivitas'),
            'biaya'              => $this->input->post('biaya'),
            'status_claim'       => $this->input->post('status_claim'),
            'keterangan'         => $this->input->post('keterangan'),
            'created_at'         => $created_at,
            'signature'          => $signature
        ];

        $this->db->insert('management_rpd.aktivitas', $data);
        redirect('management_rpd/aktivitas/'.$signature_pengajuan);
    }

    public function verifikasi_pengajuan(){
        
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }

        $level = $this->session->userdata('level');
        if ($level == '3d' || $level == '3c' || $level == '3b' ) {
            redirect("management_rpd/verifikasi_pengajuan_delto/$signature_pengajuan");
            die;
        } 

        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $created_at                 = $key->created_at;
            $no_rpd                     = $key->no_rpd;
            $verifikasi1_by             = $key->userid_verifikasi1;
            $verifikasi2_by             = $key->userid_verifikasi2;
            $created_by                 = $key->created_by;
        }

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        // var_dump($get_aktivitas);
        $this->db->select('username');
        $this->db->where('id', $verifikasi1_by);
        $username_verif1 = $this->db->get('mpm.user')->row()->username;
        // var_dump($username_verif1);
        // die;

        if (!$get_aktivitas->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "Pengajuan RPD Gagal. Silahkan isi aktivitas terlebih dahulu");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan);
        }
        // generate no_rpd
        $potensi_nomor_rpd = $this->model_management_rpd->generate($created_at);
        
        // cek apakah $potensi_nomor_rpd exists / sudah ada di database
        $cek_existing_norpd = $this->model_management_rpd->get_pengajuan_by_no_rpd($potensi_nomor_rpd);
        if ($cek_existing_norpd->num_rows() > 0) {
            // echo'ada';die;
            $this->session->set_flashdata("pesan", "Something wrong. Please try again");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan);
        }else
        { 
            $update = [
                "no_rpd"    => $this->model_management_rpd->generate($created_at),
            ];
            $this->db->where('id', $id);
            $this->db->update('management_rpd.pengajuan', $update);
        }
        // end generate rpd
    
        // cek approval
        $cek_existing_approval = $this->model_management_rpd->get_approval_by_userid($created_by);
        if ($cek_existing_approval->num_rows() > 0) 
        {
            $update_approval = [
                "verifikasi1_by"  => $verifikasi1_by,
                "verifikasi2_by"  => $verifikasi2_by,
                "status"        => 1,
                "nama_status"   => "pending atasan 1"  . ' - ' . $username_verif1,
            ];

            $this->db->where('id', $id);
            $this->db->update('management_rpd.pengajuan', $update_approval);

            // menjumlahkan total biaya
            $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;
            
            if ($total_biaya >= 1000000) {
                $jumlah_verifikasi = 2;
            }else{
                $jumlah_verifikasi = 1;
            }
            $update_jumlah_verifikasi = [
                "jumlah_verifikasi" => $jumlah_verifikasi,
            ];
            $this->db->where('id', $id);
            $this->db->update('management_rpd.pengajuan', $update_jumlah_verifikasi);
            redirect('management_rpd/email_verifikasi1/'.$signature_pengajuan);
            die;

        }else
        {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Tidak Ada Approval Atasan");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan);
            die;
        }
    }

    public function verifikasi_pengajuan_delto($signature_pengajuan)
    {
        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }else
        {
            $pelaksana                  = $get_pengajuan_by_signature->row()->pelaksana;
            $maksud_perjalanan_dinas    = $get_pengajuan_by_signature->row()->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $get_pengajuan_by_signature->row()->tanggal_berangkat;
            $tempat_berangkat           = $get_pengajuan_by_signature->row()->tempat_berangkat;
            $tanggal_tiba               = $get_pengajuan_by_signature->row()->tanggal_tiba;
            $tempat_tiba                = $get_pengajuan_by_signature->row()->tempat_tiba;
            $id                         = $get_pengajuan_by_signature->row()->id;
            $created_at                 = $get_pengajuan_by_signature->row()->created_at;
            $no_rpd                     = $get_pengajuan_by_signature->row()->no_rpd;
            $verifikasi1_by             = $get_pengajuan_by_signature->row()->userid_verifikasi1;
            $verifikasi2_by             = $get_pengajuan_by_signature->row()->userid_verifikasi2;
            $created_by                 = $get_pengajuan_by_signature->row()->created_by;
            // $verifikasi3_by             = $get_pengajuan_by_signature->row()->userid_verifikasi3;
        }
        
        // echo '='.$created_by; die;
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);

        if (!$get_aktivitas->num_rows() > 0) {

            echo "<h3>Result</h3>";
            echo "Permintaan Verifikasi ditolak !!<br><br>";
            echo "Anda belum mengisi satu pun aktivitas<br>";

            echo "<br>anda akan di redirect ke menu awal dalam 5 detik";
            header('Refresh: 5; URL='.base_url().'management_rpd/aktivitas/'.$signature_pengajuan);
            die;

        }
        // generate no_rpd
        $potensi_nomor_rpd = $this->model_management_rpd->generate($created_at);

        // cek apakah $potensi_nomor_rpd exists / sudah ada di database
        $cek_existing_norpd = $this->model_management_rpd->get_pengajuan_by_no_rpd($potensi_nomor_rpd);
        // echo 'cek'. $cek_existing_norpd; die;
        if ($cek_existing_norpd->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Something wrong. Please try again");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan);
        }else
        {
            // echo 'generate'. $potensi_nomor_rpd; die;   
            $update = [
                "no_rpd"    => $potensi_nomor_rpd
            ];

            $this->db->where('id', $id);
            $this->db->update('management_rpd.pengajuan', $update);
        }
        // end generate rpd
        
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        // get master limit
        $get_master_limit = $this->model_management_rpd->get_master_limit()->row()->max_limit;
        
        // cek approval
        $cek_existing_approval = $this->model_management_rpd->get_approval_by_userid($created_by);
        if ($cek_existing_approval->num_rows() > 0) 
        {
            $userid_verifikasi1 = $cek_existing_approval->row()->userid_verifikasi1;
            $userid_verifikasi2 = $cek_existing_approval->row()->userid_verifikasi2;
            $userid_verifikasi3 = $cek_existing_approval->row()->userid_verifikasi3;
            $username_verifikasi1 = $cek_existing_approval->row()->username_verifikasi1;
            $username_verifikasi2 = $cek_existing_approval->row()->username_verifikasi2;
            $username_verifikasi3 = $cek_existing_approval->row()->username_verifikasi3;

            if ($get_master_limit != null) 
            {
                if ($total_biaya >= $get_master_limit) 
                {
                    // echo 'master_limit'. $get_master_limit; die;
                    $update_approval = [
                        "verifikasi1_by"  => $userid_verifikasi2,
                        "verifikasi2_by"  => $userid_verifikasi3,
                        "status"        => 1,
                        "nama_status"   => "pending atasan 1" . ' - ' . $username_verifikasi2,
                        "jumlah_verifikasi" => 2
                    ];
                }else
                {
                    $update_approval = [
                        "verifikasi1_by"    => $userid_verifikasi1,
                        "verifikasi2_by"    => $userid_verifikasi2,
                        "status"            => 1,
                        "nama_status"       => "pending atasan 1" . ' - ' . $username_verifikasi1,
                        "jumlah_verifikasi" => 2
                    ];
                }

                // var_dump($update_approval);die;
                // echo "userid : ".$userid_verifikasi1;
                // echo "useid : ".$userid_verifikasi2;
                // echo "useid : ".$userid_verifikasi3;
                // die;
                $this->db->where('id', $id);
                $this->db->update('management_rpd.pengajuan', $update_approval);
            
                redirect('management_rpd/email_verifikasi1/'.$signature_pengajuan);
                die;
            }else
            {
                $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan Cek Master Limit");
                redirect('management_rpd/aktivitas/'.$signature_pengajuan);
                die;
            }

        }else
        {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Tidak Ada Approval Atasan");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan);
            die;
        }
    }

    public function verifikasi1($signature_pengajuan){

        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }

        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1       = $key->username_verifikasi1;
            $userid_verifikasi1         = $key->userid_verifikasi1;
            $username_verifikasi2       = $key->username_verifikasi2;
            $userid_verifikasi2         = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at             = $key->verifikasi2_at;
            $verifikasi1_at             = $key->verifikasi1_at;
            $verifikasi1_keterangan     = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan     = $key->verifikasi2_keterangan;
            $created_by                 = $key->created_by;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/verifikasi1_update',
            'url2'                      => "management_rpd/signature_digital/$signature_pengajuan/verifikasi1",
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'      => $username_verifikasi1,
            'userid_verifikasi1'        => $userid_verifikasi1,
            'username_verifikasi2'      => $username_verifikasi2,
            'userid_verifikasi2'        => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'            => $verifikasi2_at,
            'verifikasi1_at'            => $verifikasi1_at,
            'verifikasi1_keterangan'    => $verifikasi1_keterangan,
            'verifikasi2_keterangan'    => $verifikasi2_keterangan,
            'created_by'                => $created_by,
        ];
        
        $this->render('management_rpd/verifikasi', $data);
    }

    public function verifikasi1_update(){
        $status_verifikasi = $this->input->post('status_verifikasi');
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }

        $verifikasi2_by = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->row()->userid_verifikasi2;
        $this->db->select('username');
        $this->db->where('id', $verifikasi2_by);
        $username_verif2 = $this->db->get('mpm.user')->row()->username;

        $verifikasi1_by = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->row()->userid_verifikasi1;
        $this->db->select('username');
        $this->db->where('id', $verifikasi1_by);
        $username_verif1 = $this->db->get('mpm.user')->row()->username;

        $jumlah_verifikasi = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->row()->jumlah_verifikasi;
        $this->db->select('jumlah_verifikasi');

        // echo $jumlah_verifikasi; die;
        if ($status_verifikasi == 0) {
            $verifikasi_name = 'reject';
            $status = 9;
            $nama_status = 'reject atasan 1' . ' - ' . $username_verif1;
        }elseif ($status_verifikasi == 1) {
            if ($jumlah_verifikasi == 1) {
                $verifikasi_name = 'approve';
                $status = 3;
                $nama_status = 'pending akomodasi';
            }elseif ($jumlah_verifikasi == 2) {
                if($verifikasi1_by == $verifikasi2_by){
                    $verifikasi_name = 'approve';
                    $status = 3;
                    $nama_status = 'pending akomodasi';
                }else{
                    $verifikasi_name = 'approve';
                    $status = 2;
                    $nama_status = 'pending atasan 2' . ' - ' . $username_verif2;
                }
            }
        }

        $keterangan_verifikasi = $this->input->post('keterangan_verifikasi');

        $data = [
            "verifikasi1_by"            => $this->session->userdata('id'),
            "verifikasi1_at"            => $this->model_outlet_transaksi->timezone(),
            'verifikasi1_status'        => $status_verifikasi,
            'verifikasi1_name'          => $verifikasi_name,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'verifikasi1_keterangan'    => $keterangan_verifikasi,
            'verifikasi1_ttd'           => $this->session->userdata('username').'-signature.png'
        ];

        $this->db->where('signature', $signature_pengajuan);
        $this->db->update('management_rpd.pengajuan', $data);

        if($verifikasi1_by == $verifikasi2_by && $status_verifikasi == 1){
            $data = [
                "verifikasi1_by"            => $this->session->userdata('id'),
                "verifikasi1_at"            => $this->model_outlet_transaksi->timezone(),
                'verifikasi1_status'        => $status_verifikasi,
                'verifikasi1_name'          => $verifikasi_name,
                'verifikasi1_keterangan'    => $keterangan_verifikasi,
                'verifikasi1_ttd'           => $this->session->userdata('username').'-signature.png',
                "verifikasi2_by"            => $this->session->userdata('id'),
                "verifikasi2_at"            => $this->model_outlet_transaksi->timezone(),
                'verifikasi2_status'        => $status_verifikasi,
                'verifikasi2_name'          => $verifikasi_name,
                'status'                    => '3',
                'nama_status'               => 'pending akomodasi',
                'verifikasi2_keterangan'    => $keterangan_verifikasi,
                'verifikasi2_ttd'           => $this->session->userdata('username').'-signature.png'
            ];

            $this->db->where('signature', $signature_pengajuan);
            $this->db->update('management_rpd.pengajuan', $data);
        }

        // var_dump($status);die;
        if ($status == 2) {
            redirect('management_rpd/email_verifikasi2/'.$signature_pengajuan);
        }elseif ($status == 3) {
            // echo 'a'; die; 
            if($this->session->userdata('username') == 'hendy_deltomed' || $this->session->userdata('username') == 'yayang'){
                redirect('management_rpd/email_status_verifikasi/'.$signature_pengajuan);
            }else{
                redirect('management_rpd/verifikasi1/'.$signature_pengajuan);   
            }
        }else{
             redirect('management_rpd/verifikasi1/'.$signature_pengajuan);
        }
        
    }

    public function verifikasi2($signature_pengajuan){
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1       = $key->username_verifikasi1;
            $userid_verifikasi1         = $key->userid_verifikasi1;
            $username_verifikasi2       = $key->username_verifikasi2;
            $userid_verifikasi2         = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at             = $key->verifikasi2_at;
            $verifikasi1_at             = $key->verifikasi1_at;
            $verifikasi1_keterangan     = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan     = $key->verifikasi2_keterangan;
            $created_by                 = $key->created_by;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/verifikasi2_update',
            'url2'                      => "management_rpd/signature_digital/$signature_pengajuan/verifikasi2",
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'      => $username_verifikasi1,
            'userid_verifikasi1'        => $userid_verifikasi1,
            'username_verifikasi2'      => $username_verifikasi2,
            'userid_verifikasi2'        => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'            => $verifikasi2_at,
            'verifikasi1_at'            => $verifikasi1_at,
            'verifikasi1_keterangan'    => $verifikasi1_keterangan,
            'verifikasi2_keterangan'    => $verifikasi2_keterangan,
            'created_by'                => $created_by,
        ];
        
        $this->render('management_rpd/verifikasi', $data);
    }

    public function verifikasi2_update(){
        $status_verifikasi = $this->input->post('status_verifikasi');
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }

        $verifikasi2_by = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->row()->userid_verifikasi2;
        $this->db->select('username');
        $this->db->where('id', $verifikasi2_by);
        $username_verif2 = $this->db->get('mpm.user')->row()->username;

        if ($status_verifikasi == 0) {
            $verifikasi_name = 'reject';
            $status = 10;
            $nama_status = 'reject atasan 2'. ' - ' . $username_verif2;
        }elseif ($status_verifikasi == 1) {
            $verifikasi_name = 'approve';
            $status = 3;
            $nama_status = 'pending akomodasi';
        }

        // echo "status : ".$status;
        // die;

        $keterangan_verifikasi = $this->input->post('keterangan_verifikasi');

        $data = [
            "verifikasi2_by"            => $this->session->userdata('id'),
            "verifikasi2_at"            => $this->model_outlet_transaksi->timezone(),
            'verifikasi2_status'        => $status_verifikasi,
            'verifikasi2_name'          => $verifikasi_name,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'verifikasi2_keterangan'    => $keterangan_verifikasi,
            'verifikasi2_ttd'           => $this->session->userdata('username').'-signature.png'
        ];

        $this->db->where('signature', $signature_pengajuan);
        $this->db->update('management_rpd.pengajuan', $data);

        if ($status == 3) {
            if($this->session->userdata('username') == 'hendy_deltomed' || $this->session->userdata('username') == 'yayang'){
                redirect('management_rpd/email_status_verifikasi/'.$signature_pengajuan);
            }else{
                redirect('management_rpd/verifikasi2/'.$signature_pengajuan);   
            }
        }else{
             redirect('management_rpd/verifikasi2/'.$signature_pengajuan);
        }
        
    }

    public function email_status_verifikasi($signature_pengajuan)
    { //email untuk cc Admin

        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }
        
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1         = $key->username_verifikasi1;
            $userid_verifikasi1           = $key->userid_verifikasi1;
            $username_verifikasi2         = $key->username_verifikasi2;
            $userid_verifikasi2           = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at           = $key->verifikasi2_at;
            $verifikasi1_at           = $key->verifikasi1_at;
            $verifikasi1_keterangan           = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan           = $key->verifikasi2_keterangan;
            $created_by           = $key->created_by;
            $jumlah_verifikasi           = $key->jumlah_verifikasi;
            $jabatan           = $key->jabatan;
            $tanggal_mulai           = $key->tanggal_mulai;
            $tanggal_akhir           = $key->tanggal_akhir;
            $userid_pelaksana           = $key->userid_pelaksana;
            $keterangan              = $key->keterangan;
        }

        // echo "jumlah_verifikasi : ".$jumlah_verifikasi;
        // die;

        $from = "suffy@muliaputramandiri.net";
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $email_to = $this->model_management_rpd->get_user($userid_pelaksana, '')->row()->email;
        // $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email.',suffy@muliaputramandiri.com,ratri@muliaputramandiri.com,nanita@muliaputramandiri.com';
        $email_cc = $this->model_management_rpd->get_user($created_by, '')->row()->email.',admin.ba@deltomed.com,imas.sariningsih@deltomed.com, milla@muliaputramandiri.com';

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $subject = "MPM Site | RPD : $no_rpd | $nama_status";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi Approve',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
            'jabatan'                   => $jabatan,
            'tanggal_mulai'             => $tanggal_mulai,
            'tanggal_akhir'             => $tanggal_akhir,
            'userid_pelaksana'          => $userid_pelaksana,
            'keterangan'                => $keterangan
        ];

        $message = $this->load->view("management_rpd/email_status_verifikasi",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // echo $this->email->print_debugger(); die;
        
        $this->db->select('supp');
        $this->db->where('id', $userid_pelaksana);
        $supp_pelaksana = $this->db->get('mpm.user')->row()->supp;
        // echo $supp_pelaksana;die;

        if ($supp_pelaksana == 001) {
            $send = $this->email->send();
            if ($send) {
                $this->session->set_flashdata("pesan_success", "Verifikasi Berhasil dan Pengiriman Email Berhasil");
                redirect('management_rpd/verifikasi2/'.$signature_pengajuan);
            }else{
                echo "<script>alert('pengiriman email gagal'); </script>";
                redirect('management_rpd/verifikasi2/'.$signature_pengajuan);
            } 
        } else {
            redirect('management_rpd/verifikasi2/'.$signature_pengajuan);
        }
       
    }

    public function email_verifikasi1($signature_pengajuan)
    {
        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Email yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }
        
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1         = $key->username_verifikasi1;
            $userid_verifikasi1           = $key->userid_verifikasi1;
            $username_verifikasi2         = $key->username_verifikasi2;
            $userid_verifikasi2           = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at           = $key->verifikasi2_at;
            $verifikasi1_at           = $key->verifikasi1_at;
            $verifikasi1_keterangan           = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan           = $key->verifikasi2_keterangan;
            $created_by           = $key->created_by;
            $jumlah_verifikasi           = $key->jumlah_verifikasi;
        }

        // echo "jumlah_verifikasi : ".$jumlah_verifikasi;
        // die;

        $from = "suffy@muliaputramandiri.net";
        // $email_to = 'milla@muliaputramandiri.com';
        // $cc = 'suffy.mpm@gmail.com';

        $email_to = $this->model_management_rpd->get_user($userid_verifikasi1, '')->row()->email;
        // $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email.',suffy@muliaputramandiri.com,ratri@muliaputramandiri.com,nanita@muliaputramandiri.com';
        $email_cc = $this->model_management_rpd->get_user($created_by, '')->row()->email.',suffy@muliaputramandiri.com,milla@muliaputramandiri.com';

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $subject = "MPM Site | RPD : $no_rpd | $nama_status";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi 1',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/verifikasi2_update',
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
            
        ];

        $message = $this->load->view("management_rpd/email_verifikasi1",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        
        // print_r($this->email->print_debugger()); die;
        if ($send)
        {
            $this->session->set_flashdata("pesan_success", "Pengajuan RPD dan Pengiriman Email Berhasil");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');
        }else
        {
            $this->session->set_flashdata("pesan", "Pengajuan RPD Berhasil Namun Pengiriman Email Gagal");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');
        }        
    }

    public function email_verifikasi2($signature_pengajuan)
    {
        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }

        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) 
        {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1       = $key->username_verifikasi1;
            $userid_verifikasi1         = $key->userid_verifikasi1;
            $username_verifikasi2       = $key->username_verifikasi2;
            $userid_verifikasi2         = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at             = $key->verifikasi2_at;
            $verifikasi1_at             = $key->verifikasi1_at;
            $verifikasi1_keterangan     = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan     = $key->verifikasi2_keterangan;
            $created_by                 = $key->created_by;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $from = "suffy@muliaputramandiri.net";
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $email_to = $this->model_management_rpd->get_user($userid_verifikasi2, '')->row()->email;
        // $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email.',suffy@muliaputramandiri.com,ratri@muliaputramandiri.com,nanita@muliaputramandiri.com';
        $email_cc = $this->model_management_rpd->get_user($created_by, '')->row()->email.',suffy@muliaputramandiri.com, milla@muliaputramandiri.com';

        $subject = "MPM Site | RPD : $no_rpd | $nama_status";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        // $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi 2',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/verifikasi2_update',
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
        ];

        $message = $this->load->view("management_rpd/email_verifikasi2",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send)
        {
            $this->session->set_flashdata("pesan_success", "Verifikasi RPD dan Pengiriman Email Berhasil");
            redirect('management_rpd/verifikasi1/'.$signature_pengajuan,'refresh');
        }else
        {
            $this->session->set_flashdata("pesan", "Verifikasi RPD Berhasil Namun Pengiriman Email Gagal");
            redirect('management_rpd/verifikasi1/'.$signature_pengajuan,'refresh');
        }
    }

    public function aktivitas_delete_soft($signature_aktivitas, $signature_pengajuan)
    {
        $data = [
            "deleted_by"    => $this->session->userdata('id'),
            "deleted_at"    => $this->model_outlet_transaksi->timezone()
        ];

        $this->db->where("signature", $signature_aktivitas);
        $this->db->update("management_rpd.aktivitas", $data);

        redirect("management_rpd/aktivitas/".$signature_pengajuan);
    }

    public function pengajuan_delete_soft($signature)
    {
        $status = $this->model_management_rpd->get_pengajuan_bysignature($signature)->row()->status;
        $this->db->select('status');
        // var_dump( $status);die;
        $data = [
            "deleted_by"    => $this->session->userdata('id'),
            "deleted_at"    => $this->model_outlet_transaksi->timezone()
        ];

        if ($this->session->userdata('username') == 'imas' || $this->session->userdata('username') == 'admin_deltomed' ) 
        { // kalau admin bisa delete
            $this->db->where("signature", $signature);
            $this->db->update("management_rpd.pengajuan", $data);
            $this->session->set_flashdata("pesan_success", "Deleting Data Successfully");
            redirect("management_rpd/pengajuan");
        }else // kalau selain admin
        { 
            if ($status == 6) // status = 6 pending user
            { 
                $this->db->where("signature", $signature);
                $this->db->update("management_rpd.pengajuan", $data);
                $this->session->set_flashdata("pesan_success", "Updating Data Successfully");
                redirect("management_rpd/pengajuan");
            }else
            {
                $this->session->set_flashdata("pesan", "RPD Tidak Bisa Di Hapus, Karena Sudah Diajukan");
                redirect("management_rpd/pengajuan");
            }   
        }
    }

    public function realisasi($signature)
    {
        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }else
        {
            $no_rpd                     = $get_pengajuan_by_signature->row()->no_rpd;
            $pelaksana                  = $get_pengajuan_by_signature->row()->pelaksana;
            $maksud_perjalanan_dinas    = $get_pengajuan_by_signature->row()->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $get_pengajuan_by_signature->row()->tanggal_berangkat;
            $tempat_berangkat           = $get_pengajuan_by_signature->row()->tempat_berangkat;
            $tanggal_tiba               = $get_pengajuan_by_signature->row()->tanggal_tiba;
            $tempat_tiba                = $get_pengajuan_by_signature->row()->tempat_tiba;
            $id                         = $get_pengajuan_by_signature->row()->id;
            $status                     = $get_pengajuan_by_signature->row()->status;
            $nama_status                = $get_pengajuan_by_signature->row()->nama_status;
            $username_verifikasi1       = $get_pengajuan_by_signature->row()->username_verifikasi1;
            $userid_verifikasi1         = $get_pengajuan_by_signature->row()->userid_verifikasi1;
            $username_verifikasi2       = $get_pengajuan_by_signature->row()->username_verifikasi2;
            $userid_verifikasi2         = $get_pengajuan_by_signature->row()->userid_verifikasi2;
            $verifikasi1_name           = $get_pengajuan_by_signature->row()->verifikasi1_name;
            $verifikasi2_name           = $get_pengajuan_by_signature->row()->verifikasi2_name;
            $verifikasi2_at             = $get_pengajuan_by_signature->row()->verifikasi2_at;
            $verifikasi1_at             = $get_pengajuan_by_signature->row()->verifikasi1_at;
            $verifikasi1_keterangan     = $get_pengajuan_by_signature->row()->verifikasi1_keterangan;
            $verifikasi2_keterangan     = $get_pengajuan_by_signature->row()->verifikasi2_keterangan;
            $verifikasi1_ttd            = $get_pengajuan_by_signature->row()->verifikasi1_ttd;
            $verifikasi2_ttd            = $get_pengajuan_by_signature->row()->verifikasi2_ttd;
            $jumlah_verifikasi          = $get_pengajuan_by_signature->row()->jumlah_verifikasi;
            $verifikasi1_status         = $get_pengajuan_by_signature->row()->verifikasi1_status;
            $verifikasi2_status         = $get_pengajuan_by_signature->row()->verifikasi2_status;
        }

        if ($jumlah_verifikasi == 1) 
        {
            if ($verifikasi1_status == '' || $verifikasi1_status == 0) 
            {
                $this->session->set_flashdata("pesan", "RPD belum di approve atau ditolak. Sehingga pengisian Realisasi belum bisa dilakukan !!");
                redirect('management_rpd/pengajuan');
                die;
            }
        }elseif ($jumlah_verifikasi == 2) 
        {
            if ($verifikasi2_status == '' || $verifikasi2_status == 0 || $verifikasi1_status == 0) 
            {
                $this->session->set_flashdata("pesan", "RPD belum di approve atau ditolak. Sehingga pengisian Realisasi belum bisa dilakukan !!");
                redirect('management_rpd/pengajuan');
                die;
            }
        }

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Input Realisasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/realisasi_proses',
            'signature_pengajuan'       => $signature,
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'      => $username_verifikasi1,
            'userid_verifikasi1'        => $userid_verifikasi1,
            'username_verifikasi2'      => $username_verifikasi2,
            'userid_verifikasi2'        => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'            => $verifikasi2_at,
            'verifikasi1_at'            => $verifikasi1_at,
            'verifikasi1_keterangan'    => $verifikasi1_keterangan,
            'verifikasi2_keterangan'    => $verifikasi2_keterangan,
            'verifikasi1_ttd'           => $verifikasi1_ttd,
            'verifikasi2_ttd'           => $verifikasi2_ttd,
            'jumlah_verifikasi'         => $jumlah_verifikasi,
        ];
        
        $this->render('management_rpd/realisasi', $data);

    }

    // public function realisasi_proses()
    // {
    //     $signature_pengajuan = $this->input->post('signature_pengajuan');
    //     $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
    //     if (!$get_pengajuan_by_signature->num_rows() > 0) 
    //     {
    //         $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
    //         redirect('management_rpd/pengajuan');
    //         die;
    //     }

    //     $data = array();
    //     $count = count($this->input->post('keterangan_realisasi'));
    //     for($i=0; $i < $count; $i++) 
    //     {
    //         if(!empty($_FILES['attachment']['name'][$i]))
    //         {
    //             $_FILES['file']['name']     = $_FILES['attachment']['name'][$i];
    //             $_FILES['file']['type']     = $_FILES['attachment']['type'][$i];
    //             $_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
    //             $_FILES['file']['error']    = $_FILES['attachment']['error'][$i];
    //             $_FILES['file']['size']     = $_FILES['attachment']['size'][$i];
        
    //             $config['upload_path']      = './assets/file/rpd/';
    //             $config['allowed_types']    = '*';
    //             $config['max_size']         = '*';
    //             $config['overwrite']        = false;
    //             $config['file_name']        = $_FILES['attachment']['name'][$i];
        
    //             $this->load->library('upload',$config); 
        
    //             if($this->upload->do_upload('file'))
    //             {
    //                 $uploadData = $this->upload->data();
    //                 $filename = $uploadData['client_name'];
    //                 $link = $uploadData['full_path'];
    //             }
    //             $data = [
    //                 'status_realisasi'      => $this->input->post('status_realisasi')[$i+1],
    //                 'keterangan_realisasi'  => $this->input->post('keterangan_realisasi')[$i],
    //                 'attachment_realisasi'  => $filename,
    //                 'attachment_link'       => substr_replace($link,"",0,21),
    //             ];
    //         }
    //         else 
    //         {
    //             $data = [
    //                 'status_realisasi'      => $this->input->post('status_realisasi')[$i+1],
    //                 'keterangan_realisasi'  => $this->input->post('keterangan_realisasi')[$i],
    //             ];
    //         }
            
    //         $this->db->where('signature', $this->input->post('signature_aktivitas')[$i]);
    //         $this->db->update('management_rpd.aktivitas', $data);
    //     }

    //     $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
    //     if (!$get_pengajuan_by_signature->num_rows() > 0) 
    //     {
    //         $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
    //         redirect('management_rpd/pengajuan');
    //         die;
    //     }else
    //     {
    //         $update = [
    //             'status_realisasi'  => 1,
    //             'realisasi_at'      => $this->model_outlet_transaksi->timezone(),
    //             'status'            => 5,
    //             'nama_status'       => 'finish',
    
    //         ];
    
    //         $this->db->where('signature', $signature_pengajuan);
    //         $update = $this->db->update('management_rpd.pengajuan', $update);
    //         if($update)
    //         {
    //             $this->session->set_flashdata("pesan_success", "Pengisian Realisasi Berhasil");
    //             redirect('management_rpd/realisasi/'.$signature_pengajuan);
    //         }else
    //         {
    //             $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !!");
    //         }
    //     }

    // }

    public function realisasi_proses()
    {
        // echo "masuk sini"; die;
        $signature_pengajuan = $this->input->post('signature_pengajuan');
        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_by_signature($signature_pengajuan);

        if (!$get_pengajuan_by_signature->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }else
        {
            $data = array();
            $count = count($this->input->post('keterangan_realisasi'));
            for($i=0; $i < $count; $i++) 
            {
                if(!empty($_FILES['attachment']['name'][$i]))
                {
                    $_FILES['file']['name']     = $_FILES['attachment']['name'][$i];
                    $_FILES['file']['type']     = $_FILES['attachment']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                    $_FILES['file']['error']    = $_FILES['attachment']['error'][$i];
                    $_FILES['file']['size']     = $_FILES['attachment']['size'][$i];
            
                    $config['upload_path']      = './assets/file/rpd/';
                    $config['allowed_types']    = '*';
                    $config['max_size']         = '*';
                    $config['overwrite']        = false;
                    $config['file_name']        = $_FILES['attachment']['name'][$i];
            
                    $this->load->library('upload',$config); 
            
                    if($this->upload->do_upload('file'))
                    {
                        $uploadData = $this->upload->data();
                        $filename = $uploadData['client_name'];
                        $link = $uploadData['full_path'];
                    }
                    $data = [
                        'status_realisasi'      => $this->input->post('status_realisasi')[$i+1],
                        'keterangan_realisasi'  => $this->input->post('keterangan_realisasi')[$i],
                        'attachment_realisasi'  => $filename,
                        'attachment_link'       => substr_replace($link,"",0,21),
                    ];
                }
                else 
                {
                    $data = [
                        'status_realisasi'      => $this->input->post('status_realisasi')[$i+1],
                        'keterangan_realisasi'  => $this->input->post('keterangan_realisasi')[$i],
                    ];
                }
                
                $this->db->where('signature', $this->input->post('signature_aktivitas')[$i]);
                $this->db->update('management_rpd.aktivitas', $data);
            }

            $update = [
                'status_realisasi'  => 1,
                'realisasi_at'      => $this->model_outlet_transaksi->timezone(),
                'status'            => 5,
                'nama_status'       => 'finish',
    
            ];

            $update_data_pengajuan = $this->model_management_rpd->update_pengajuan($signature_pengajuan, $update);
            
            if($update_data_pengajuan)
            {
                $this->session->set_flashdata("pesan_success", "Pengisian Realisasi Berhasil");
                redirect('management_rpd/realisasi/'.$signature_pengajuan);
            }else
            {
                $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !!");
            }
        }

    }

    public function input_akomodasi($signature)
    {
        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }else
        {
            $no_rpd                     = $get_pengajuan_by_signature->row()->no_rpd;
            $pelaksana                  = $get_pengajuan_by_signature->row()->pelaksana;
            $jabatan                    = $get_pengajuan_by_signature->row()->jabatan;
            $id                         = $get_pengajuan_by_signature->row()->id;
            $status                     = $get_pengajuan_by_signature->row()->status;
            $nama_status                = $get_pengajuan_by_signature->row()->nama_status;
            $radius_perjalanan          = $get_pengajuan_by_signature->row()->radius_perjalanan;
            $attachment_radius_perjalanan = $get_pengajuan_by_signature->row()->attachment_radius_perjalanan;
            $attachment_akomodasi       = $get_pengajuan_by_signature->row()->attachment_akomodasi;
            $keterangan_akomodasi       = $get_pengajuan_by_signature->row()->keterangan_akomodasi;
        }

        $get_aktivitas = $this->model_management_rpd->get_pengajuan_akomodasi($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Input Akomodasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'jabatan'                   => $jabatan,
            'url'                       => 'management_rpd/input_akomodasi_proses',
            'signature_pengajuan'       => $signature,
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'radius_perjalanan'         => $radius_perjalanan,
            'attachment_radius_perjalanan' => $attachment_radius_perjalanan,
            'attachment_akomodasi'     => $attachment_akomodasi,
            'keterangan_akomodasi'     => $keterangan_akomodasi
        ];
        
        $this->render('management_rpd/input_akomodasi', $data);
    }

    public function input_akomodasi_proses()
    {
        // echo 'A';
        // $this->db->trans_start();
        $signature_pengajuan = $this->input->post('signature_pengajuan');
        $get_pengajuan_by_signature = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan);
        if (!$get_pengajuan_by_signature->num_rows() > 0) 
        {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_rpd/pengajuan');
            die;
        }

        if (!is_dir('./assets/file/rpd/')) 
        {
            @mkdir('./assets/file/rpd/', 0777);
        }
        $signature_pengajuan = $this->input->post('signature_pengajuan');
        $attachment_akomodasi = $this->input->post('attachment_akomodasi');
        $keterangan_akomodasi  = $this->input->post('keterangan_akomodasi');
        

        $config['upload_path'] = './assets/file/rpd/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        $config['overwrite'] = 'false';

        $this->load->library('upload', $config);

        if($this->upload->do_upload('attachment_akomodasi'))
        {
            $uploadData     = $this->upload->data();
            $filename       = $uploadData['file_name'];
        }
        $data = [
            'attachment_akomodasi'  => $filename,
            'keterangan_akomodasi'  => $keterangan_akomodasi,
            'status'                => 4,
            'nama_status'           => 'pending realisasi',

        ];
        // var_dump($data); die;
        $this->db->where('signature', $signature_pengajuan);
        $this->db->update('management_rpd.pengajuan', $data);

        // redirect('management_rpd/pengajuan');
        redirect('management_rpd/email_verifikasi_akomodasi/'.$signature_pengajuan);
        die;
    }

    public function email_verifikasi_akomodasi($signature_pengajuan)
    {    
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) 
        {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1         = $key->username_verifikasi1;
            $userid_verifikasi1           = $key->userid_verifikasi1;
            $username_verifikasi2         = $key->username_verifikasi2;
            $userid_verifikasi2           = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at           = $key->verifikasi2_at;
            $verifikasi1_at           = $key->verifikasi1_at;
            $verifikasi1_keterangan           = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan           = $key->verifikasi2_keterangan;
            $created_by           = $key->created_by;
            $jumlah_verifikasi           = $key->jumlah_verifikasi;
            $jabatan           = $key->jabatan;
            $tanggal_mulai           = $key->tanggal_mulai;
            $tanggal_akhir           = $key->tanggal_akhir;
            $userid_pelaksana        = $key->userid_pelaksana;
        }

        // echo "jumlah_verifikasi : ".$jumlah_verifikasi;
        // die;

        $from = "suffy@muliaputramandiri.net";
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $email_to = $this->model_management_rpd->get_user($userid_pelaksana, '')->row()->email;
        // $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email.',suffy@muliaputramandiri.com,ratri@muliaputramandiri.com,nanita@muliaputramandiri.com';
        $email_cc = $this->model_management_rpd->get_user($created_by, '')->row()->email.',admin.ba@deltomed.com, imas.sariningsih@deltomed.com, milla@muliaputramandiri.com';

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $subject = "MPM Site | RPD : $no_rpd | $nama_status";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        // echo "userid_verifikasi1 : ".$userid_verifikasi1;
        // echo "email_to : ".$email_to;
        // echo "email_cc : ".$email_cc;

        // die;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Input Akomodasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
            'jabatan'                   => $jabatan,
            'tanggal_mulai'             => $tanggal_mulai,
            'tanggal_akhir'             => $tanggal_akhir,
            'userid_pelaksana'          => $userid_pelaksana
            
        ];

        $message = $this->load->view("management_rpd/email_akomodasi",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        // echo $this->email->print_debugger(); die;
        if ($send)
        {
            $this->session->set_flashdata("pesan_success", "Input Akomodasi dan Pengiriman Email Berhasil");
            redirect('management_rpd/input_akomodasi/'.$signature_pengajuan);
        }else
        {
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('management_rpd/input_akomodasi/'.$signature_pengajuan);
        }
    }

    public function generate_pdf($signature)
    {
        $this->load->library('mypdf');

        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature)->result();
        foreach ($get_pengajuan as $key) 
        {
            $no_rpd                         = $key->no_rpd;
            $pelaksana                      = $key->pelaksana;
            $jabatan                        = $key->jabatan;
            $level_karyawan                 = $key->level_karyawan;
            $maksud_perjalanan_dinas        = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat              = $key->tanggal_berangkat;
            $tempat_berangkat               = $key->tempat_berangkat;
            $tanggal_tiba                   = $key->tanggal_tiba;
            $tempat_tiba                    = $key->tempat_tiba;
            $id                             = $key->id;
            $status                         = $key->status;
            $nama_status                    = $key->nama_status;
            $username_verifikasi1           = $key->username_verifikasi1;
            $userid_verifikasi1             = $key->userid_verifikasi1;
            $username_verifikasi2           = $key->username_verifikasi2;
            $userid_verifikasi2             = $key->userid_verifikasi2;
            $verifikasi1_name               = $key->verifikasi1_name;
            $verifikasi2_name               = $key->verifikasi2_name;
            $verifikasi2_at                 = $key->verifikasi2_at;
            $verifikasi1_at                 = $key->verifikasi1_at;
            $verifikasi1_keterangan         = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan         = $key->verifikasi2_keterangan;
            $verifikasi1_ttd                = $key->verifikasi1_ttd;
            $verifikasi2_ttd                = $key->verifikasi2_ttd;
            $jumlah_verifikasi              = $key->jumlah_verifikasi;
            $tanggal_mulai                  = $key->tanggal_mulai;
            $tanggal_akhir                  = $key->tanggal_akhir;
            $verifikasi1_status              = $key->verifikasi1_status;
            $verifikasi2_status              = $key->verifikasi2_status;
            $verifikasi1_name              = $key->verifikasi1_name;
            $verifikasi2_name              = $key->verifikasi2_name;
        }

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        // die;
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'jabatan'                   => $jabatan,
            'level_karyawan'            => $level_karyawan,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/realisasi_proses',
            'signature_pengajuan'       => $signature,
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'cek_realisasi'             => $this->model_management_rpd->cek_realisasi($id),
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'      => $username_verifikasi1,
            'userid_verifikasi1'        => $userid_verifikasi1,
            'username_verifikasi2'      => $username_verifikasi2,
            'userid_verifikasi2'        => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'            => $verifikasi2_at,
            'verifikasi1_at'            => $verifikasi1_at,
            'verifikasi1_keterangan'    => $verifikasi1_keterangan,
            'verifikasi2_keterangan'    => $verifikasi2_keterangan,
            'verifikasi1_ttd'           => $verifikasi1_ttd,
            'verifikasi2_ttd'           => $verifikasi2_ttd,
            'jumlah_verifikasi'         => $jumlah_verifikasi,
            'tanggal_mulai'             => $tanggal_mulai,
            'tanggal_akhir'             => $tanggal_akhir,
            'verifikasi1_status'        => $verifikasi1_status,
            'verifikasi2_status'        => $verifikasi2_status
        ];

        $filename_pdf = $no_rpd;

        $generate_pdf = $this->mypdf->generate('management_rpd/template_rpd',$data,$filename_pdf,'A4','landscape');

    }

    public function generate_excel($signature)
    {
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                         = $key->no_rpd;
        }
        
        $query = "
            select 	a.no_rpd, a.pelaksana, a.maksud_perjalanan_dinas, a.tanggal_berangkat, a.tempat_berangkat, a.tanggal_tiba, a.tempat_tiba, 
                    a.`status`, a.nama_status, a.jumlah_verifikasi, 
                    a.verifikasi1_by, a.verifikasi1_at, a.verifikasi1_status, a.verifikasi1_name, a.verifikasi1_keterangan,
                    a.verifikasi2_by, a.verifikasi2_at, a.verifikasi2_status, a.verifikasi2_name, a.verifikasi2_keterangan,
                    b.*
            from management_rpd.pengajuan a LEFT JOIN (
                select 	a.id_pengajuan, a.aktivitas, a.tanggal_aktivitas, a.detail_aktivitas, a.biaya, a.keterangan, a.status_realisasi, a.keterangan_realisasi,
                            a.attachment_realisasi
                from management_rpd.aktivitas a 
                where a.deleted_at is null
            )b on a.id = b.id_pengajuan
            where a.signature = '$signature'
        ";

        query_to_csv($this->db->query($query),TRUE, 'pengajuan_'.$no_rpd.'_'.date('YmdHis').'.csv',TRUE);

    }

    public function signature_digital()
    {    
        $signature = $this->uri->segment('3');
        $url = $this->uri->segment('4');
        $data = [
            'title'           => 'Digital Signature',
            'url'             => "management_rpd/signature_digital_proses/$signature/$url",
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/signature_digital', $data);
        $this->load->view('kalimantan/footer');
    }
    

    public function signature_digital_proses()
    {    
        $signature = $this->uri->segment('3');
        $url = $this->uri->segment('4');
        $folderPath = './assets/uploads/signature/';  
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $file = $folderPath . $this->session->userdata('username') . '-signature.' .$image_type;
        file_put_contents($file, $image_base64);
        redirect("management_rpd/$url/$signature");

    }

    public function master_data()
    {
        $username = $this->session->userdata('username');
        if ($username == 'milla' || $username == 'suffy' || $username == 'evlin' || $username == 'ratri' || $username == 'admin_deltomed' || $username == 'imas' || $username == 'rifqi')  
        {
            $status_authorized = true;
        }else
        {
            $status_authorized = false;
        }
        
        $data = [
            'title'                             => 'Master Data Aproval', 
            'get_master_data_approval'          => $this->model_management_rpd->get_approval_by_userid(),
            'url_master_data_approval'          => 'management_rpd/master_data_approval_tambah',
            'status_authorized'                 => $status_authorized
        ];
        // var_dump($status_authorized);
        // echo 'status_authorized :'.$status_authorized;die;

        $this->render('management_rpd/master_data', $data);
    }

    public function master_data_approval_tambah()
    {
        $id                    = $this->input->post('userid_pelaksana');
        $userid_verifikasi1    = $this->input->post('userid_verifikasi1');
        $userid_verifikasi2    = $this->input->post('userid_verifikasi2');
        $userid_verifikasi3    = $this->input->post('userid_verifikasi3');
        $created_at            = $this->model_outlet_transaksi->timezone();
        $created_by            = $this->session->userdata('id');
        $kode_company          = $this->session->userdata('kode_company');

        // cek kode_company userid_pelaksana
        $cek_company_pelaksana = $this->model_management_rpd->get_user($id, '');
        if ($cek_company_pelaksana->num_rows() > 0){
            $company_pelaksana = $cek_company_pelaksana->row($id)->kode_company;
            if ($company_pelaksana == NULL || $company_pelaksana == "" || $kode_company == NULL || $kode_company == "")
            {
                // echo 'masuk Gagal'; echo $company_pelaksana; die;
                $this->session->set_flashdata("pesan_gagal_update_master_data_approval", "Tidak dapat merubah data, Kode Company NULL");
                redirect('management_rpd/master_data', 'refresh');
                die;
            }elseif($company_pelaksana != $kode_company)
            {
                // echo 'masuk gabisa ngubah data'; echo $company_pelaksana.' '; echo $kode_company; die;
                $this->session->set_flashdata("pesan_gagal_update_master_data_approval", "Tidak dapat merubah data, karena bukan karyawan anda");
                redirect('management_rpd/master_data', 'refresh');
                die;
            }else
            {
                // cek approval user
                $cek_existing = $this->model_management_rpd->get_approval_by_userid($id);
                if ($cek_existing->num_rows() > 0) 
                {
                    // update
                    $data = [
                        'userid_pelaksana'            => $id,
                        'userid_verifikasi1'          => $userid_verifikasi1,
                        'userid_verifikasi2'          => $userid_verifikasi2,
                        'userid_verifikasi3'          => $userid_verifikasi3,
                        'updated_at'                  => $created_at,
                        'updated_by'                  => $this->session->userdata('id'),

                    ];
                    $this->db->where('userid_pelaksana', $id);
                    $send = $this->db->update('management_rpd.m_karyawan', $data);
                    if ($send)
                    {
                        $this->session->set_flashdata("pesan_success_update_master_data_approval", "Update Data Successfully");
                        redirect('management_rpd/master_data', 'refresh');
                    }else
                    {
                        $this->session->set_flashdata("pesan_gagal_update_master_data_approval", "Update Data Gagal");
                        redirect('management_rpd/master_data', 'refresh'); die;
                    }
                }else
                {
                    // insert
                    $data = [
                        'userid_pelaksana'            => $id,
                        'userid_verifikasi1'          => $userid_verifikasi1,
                        'userid_verifikasi2'          => $userid_verifikasi2,
                        'userid_verifikasi3'          => $userid_verifikasi3,
                        'created_at'                  => $created_at,
                        'created_by'                  => $created_by,
                        'updated_at'                  => $created_at,
                        'updated_by'                  => $this->session->userdata('id'),
                    ];
                    $send = $this->db->insert('management_rpd.m_karyawan', $data);
                    if ($send)
                    {
                        $this->session->set_flashdata("pesan_success_update_master_data_approval", "Insert Data Successfully");
                        redirect('management_rpd/master_data', 'refresh');
                    }else
                    {
                        $this->session->set_flashdata("pesan_gagal_update_master_data_approval", "Insert Data Gagal");
                        redirect('management_rpd/master_data', 'refresh'); die;
                    }
                }
            }
        }
    }

    public function master_limit()
    {
        $username       = $this->session->userdata('username');
        if ($username == 'milla' || $username == 'evlin')  
        {
            $status_authorized = true;
        }else
        {
            $status_authorized = false;
        }

        $get_limit = $this->model_management_rpd->get_master_limit();
        if ($get_limit->num_rows() > 0) {
            $limit = $get_limit->row()->max_limit;
        }else{
            $limit = 0;
        }
        
        $data = [
            'title'                             => 'Master Data Limit', 
            'url_master_limit'                   => 'management_rpd/master_limit_tambah',
            'limit'                             => $limit,
            'status_authorized'                 => $status_authorized
        ];
        
        $this->render('management_rpd/master_limit', $data);
    }

    public function master_limit_tambah()
    {
        // insert
        $created_at = $this->model_outlet_transaksi->timezone();
        $data = [
            'max_limit'            => $this->input->post('max_limit'),
            'created_at'           => $created_at,
            'created_by'           => $this->session->userdata('id'),
            'updated_at'           => $created_at,
            'updated_by'           => $this->session->userdata('id'),
        ];
        $send = $this->db->update('management_rpd.master_limit', $data);
        if ($send)
        {
            $this->session->set_flashdata("pesan_success_update_master_data_approval", "Insert Data Successfully");
            redirect('management_rpd/master_limit', 'refresh');
        }else
        {
            $this->session->set_flashdata("pesan_gagal_update_master_data_approval", "Insert Data Gagal");
            redirect('management_rpd/master_limit', 'refresh'); die;
        }
    }

    public function master_karyawan(){
        $kode_company   = $this->session->userdata('kode_company');
        $username       = $this->session->userdata('username');
        
        if ($username == 'milla' || $username == 'suffy' || $username == 'evlin' || $username == 'admin_deltomed')  
        {
            $status_authorized = true;
        }else
        {
            $status_authorized = false;
        }

        $data = [
            'title'                             => 'Master Karyawan', 
            'get_master_karyawan'               => $this->model_management_rpd->get_user('', $kode_company),
            'url_master_karyawan'               => 'management_rpd/update_master_karyawan',
            'status_authorized'                 => $status_authorized,
        ];
        $this->render('management_rpd/master_karyawan', $data);
    }

    public function update_master_karyawan()
    {
        $id                = $this->input->post('userid_karyawan');
        $email             = $this->input->post('email');
        $jabatan           = $this->input->post('jabatan');
        $level_karyawan    = $this->input->post('level_karyawan');
        $status_karyawan   = $this->input->post('status_karyawan');
        $kode_apps         = $this->input->post('kode_apps');
        $modified          = $this->model_outlet_transaksi->timezone();
        $modified_by       = $this->session->userdata('id');

        $cek_existing = $this->model_management_rpd->get_user($id, '');
            if ($cek_existing->num_rows() > 0) 
            {
                // update
                $data = [
                    'email'            => $email,
                    'jabatan'          => $jabatan,
                    'level_karyawan'   => $level_karyawan,
                    'kode_apps'        => $kode_apps,
                    'active'           => $status_karyawan,
                    'modified'         => $modified,
                    'modified_by'      => $modified_by
                ];

                // var_dump($data);die;

                $this->db->where('id', $id);
                $send = $this->db->update('mpm.user', $data);
                if ($send)
                {
                    $this->session->set_flashdata("pesan_success_update_master_data_approval", "Update Data Successfully");
                    redirect('management_rpd/master_karyawan', 'refresh');
                }else
                {
                    $this->session->set_flashdata("pesan_gagal_update_master_data_approval", "Update Data Gagal");
                    redirect('management_rpd/master_karyawan', 'refresh'); die;
                }
            }else
            {
                $this->session->set_flashdata("pesan_gagal_update_master_data_approval", "Update Data Gagal");
                redirect('management_rpd/master_karyawan', 'refresh'); die;
            }
    }

    public function mpm_user()
    {
        $id = $this->input->post('id');
        // echo "id : ".$id;
        // return $id;
        // die;
        
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key,
            'id'     => $id
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/mpm_user?" . http_build_query($params),
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
            $array_response = json_decode($response, true);
            $result = $array_response['data'];

            foreach ($result as $key => $r)
            {
                $email = $r["email"];
                $jabatan = $r["jabatan"];
                $level_karyawan = $r["level_karyawan"];
            }

            $data = [
                'email'            => $email,
                'jabatan'          => $jabatan,
                'level_karyawan'   => $level_karyawan
            ];

            echo json_encode($data);

            // echo "<option value=''> -- Pilih User -- </option>";

            // foreach ($result as $key => $r)
            // {
            //     echo "<option value='". $r["id"] . "' >";
            //     echo $r["username"] . " - " . $r["email"]. " - " . $r["id"];
            //     echo "</option>";
            // }
        }
    }
}
?>
