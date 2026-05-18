<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_inventory extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'Management Inventory';

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_retur', 'model_management_inventory', 'model_inventory', 'M_helpdesk', 'model_relokasi'));

        // cek traffic
        $traffic = $this->model_management_inventory->get_traffic();
        if($traffic->num_rows() > 0){
            $status_generate = $traffic->row()->status_generate;
            $created_at = $traffic->row()->created_at;

            // date_default_timezone_set('Asia/Jakarta');
            $waktu_awal  =strtotime($created_at);
            $waktu_akhir =strtotime(date('Y-m-d H:i:s')); // bisa juga waktu sekarang now()

            // echo "waktu_awal : ".$waktu_awal;
            // echo "<br>";
            // echo "waktu_akhir : ".$waktu_akhir;

            //menghitung selisih dengan hasil detik
            $diff    =$waktu_akhir - $waktu_awal;
            if ($diff > 300) {
                $this->model_management_inventory->insert_traffic($this->session->userdata('username'), $this->session->userdata('id'), 0);
            }
        }
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->email_admin = "linda@muliaputramandiri.com,  melindawps19@gmail.com";

        $username = $this->session->userdata('username');

        $left = substr($username, 0, 5);

        // if ($left == 'PENTA') {
        //     $link = base_url('management_office');
        //     echo "
        //     <script>
        //     alert('Kami beritahukan bahwa pada saat ini penggunaan menu retur sedang mengalami perbaikan Maintenance (pemeliharaan)'); 
        //     window.location = '$link';
        //     </script>";
        // }

        // if ($username != 'rani' && $username != 'melinda' && $username != 'sarno') {
        //     $link = base_url('management_office');
        //     echo "
        //     <script>
        //     alert('Kami beritahukan bahwa pada saat ini penggunaan menu retur sedang mengalami perbaikan Maintenance (pemeliharaan)'); 
        //     window.location = '$link';
        //     </script>";
        // }
    }

    // function management_inventory()
    // {
    //     $logged_in= $this->session->userdata('logged_in');
    //     if(!isset($logged_in) || $logged_in != TRUE)
    //     {
    //         redirect('login_sistem/','refresh');
    //     }
    //     set_time_limit(0);

    //     $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    //     $this->load->helper(array('url', 'csv'));
    //     $this->load->model(array('model_outlet_transaksi','model_retur', 'model_management_inventory', 'model_inventory', 'M_helpdesk'));

    //     // cek traffic
    //     $traffic = $this->model_management_inventory->get_traffic();
    //     if($traffic->num_rows() > 0){
    //         $status_generate = $traffic->row()->status_generate;
    //         $created_at = $traffic->row()->created_at;

    //         // date_default_timezone_set('Asia/Jakarta');
    //         $waktu_awal  =strtotime($created_at);
    //         $waktu_akhir =strtotime(date('Y-m-d H:i:s')); // bisa juga waktu sekarang now()

    //         // echo "waktu_awal : ".$waktu_awal;
    //         // echo "<br>";
    //         // echo "waktu_akhir : ".$waktu_akhir;

    //         //menghitung selisih dengan hasil detik
    //         $diff    =$waktu_akhir - $waktu_awal;
    //         if ($diff > 300) {
    //             $this->model_management_inventory->insert_traffic($this->session->userdata('username'), $this->session->userdata('id'), 0);
    //         }
    //     }
    //     $this->created_at = $this->model_outlet_transaksi->timezone();
    //     $this->email_admin = "linda@muliaputramandiri.com,  melindawps19@gmail.com";

    //     $username = $this->session->userdata('username');

    //     $left = substr($username, 0, 5);
       
    //     // if ($left == 'PENTA') {
    //     //     $link = base_url('management_office');
    //     //     echo "
    //     //     <script>
    //     //     alert('Kami beritahukan bahwa pada saat ini penggunaan menu retur sedang mengalami perbaikan Maintenance (pemeliharaan)'); 
    //     //     window.location = '$link';
    //     //     </script>";
    //     // }

    //     // if ($username != 'rani' && $username != 'melinda' && $username != 'sarno') {
    //     //     $link = base_url('management_office');
    //     //     echo "
    //     //     <script>
    //     //     alert('Kami beritahukan bahwa pada saat ini penggunaan menu retur sedang mengalami perbaikan Maintenance (pemeliharaan)'); 
    //     //     window.location = '$link';
    //     //     </script>";
    //     // }
    // }

    function index()
    {
        $this->dashboard();
    }

  public function dashboard()
  {
    if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5 || $this->session->userdata('level') == 5) { // jika dp
      redirect('management_inventory/pengajuan_retur');
      die;
    }else if ($this->session->userdata('level') == 3) { // jika principal
      $get_kode_alamat = $this->model_management_inventory->get_principal_akses($this->session->userdata('id'));
      $code = '';
      foreach ($get_kode_alamat->result() as $key) {
          $code.= ","."'".$key->site_code."'";
      }
      $kode_alamat = preg_replace('/,/', '', $code,1);
    }else if ($this->session->userdata('username') == 'zul') { // jika zul
      $get_kode_alamat = $this->model_management_inventory->get_principal_akses($this->session->userdata('id'));
      $code = '';
      foreach ($get_kode_alamat->result() as $key) {
          $code.= ","."'".$key->site_code."'";
      }
      $kode_alamat = preg_replace('/,/', '', $code,1);
    }else{
      $get_kode_alamat = $this->model_inventory->get_kode_alamat();
      $code = '';
      foreach ($get_kode_alamat as $key) {
          $code.= ","."'".$key->kode_alamat."'";
      }
      $kode_alamat = preg_replace('/,/', '', $code,1);
    }

    if($this->input->get('from'))
    {
      $advanced['from']   = $this->input->get('from');
      $advanced['to']     = $this->input->get('to');
      $advanced['status'] = $this->input->get('status');
      $advanced['type']   = $this->input->get('type');

      if ($this->input->get('type') == 2) {
          // echo "ini export : ".$this->input->get('type');
          $this->export_by_date_status($this->input->get('from'), $this->input->get('to'), $this->input->get('status'));
          die;
      } else if ($this->input->get('type') == 3) {
          // echo "ini export : ".$this->input->get('type');
          $this->export_log_by_date_status($this->input->get('from'), $this->input->get('to'), $this->input->get('status'));
          die;
      }
    }else{
      $advanced = "";
    }

    $data = [
      'title'           => 'Pengajuan Retur - Dashboard',
      'get_pengajuan'   => $this->model_management_inventory->get_pengajuan("", $kode_alamat, $advanced),
      'url'             => 'management_inventory/pengajuan_retur_proses',
      'url_search'      => '',
      'url_override'    => 'management_inventory/form_override_status',
      'site_code'       => $this->model_management_inventory->get_sitecode(),
    ];

    $this->render('management_inventory/dashboard', $data);
  }

    public function form_override_status($signature)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "override gagal dijalankan !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }else{
            $no_pengajuan       = $get_pengajuan_by_signature->row()->no_pengajuan;
            $namasupp           = $get_pengajuan_by_signature->row()->namasupp;
            $branch_name        = $get_pengajuan_by_signature->row()->branch_name;
            $nama_comp          = $get_pengajuan_by_signature->row()->nama_comp;
            $site_code          = $get_pengajuan_by_signature->row()->site_code;
            $status             = $get_pengajuan_by_signature->row()->status;
            $nama_status        = $get_pengajuan_by_signature->row()->nama_status;
            $tanggal_pengajuan  = $get_pengajuan_by_signature->row()->tanggal_pengajuan;
            $file               = $get_pengajuan_by_signature->row()->file;
            $verifikasi_mpm_name= $get_pengajuan_by_signature->row()->verifikasi_mpm_name;
            $verifikasi_at      = $get_pengajuan_by_signature->row()->verifikasi_at;
            $principal_area_at  = $get_pengajuan_by_signature->row()->principal_area_at;
            $principal_area_name= $get_pengajuan_by_signature->row()->principal_area_name;
            $catatan_principal_area = $get_pengajuan_by_signature->row()->catatan_principal_area;
            $file_principal_area= $get_pengajuan_by_signature->row()->file_principal_area;
            $principal_ho_at    = $get_pengajuan_by_signature->row()->principal_ho_at;
            $principal_ho_name  = $get_pengajuan_by_signature->row()->principal_ho_name;
            $catatan_principal_ho = $get_pengajuan_by_signature->row()->catatan_principal_ho;
            $file_principal_ho  = $get_pengajuan_by_signature->row()->file_principal_ho;
            $tanggal_kirim_barang = $get_pengajuan_by_signature->row()->tanggal_kirim_barang;
            $nama_ekspedisi     = $get_pengajuan_by_signature->row()->nama_ekspedisi;
            $est_tanggal_tiba   = $get_pengajuan_by_signature->row()->est_tanggal_tiba;
            $file_pengiriman    = $get_pengajuan_by_signature->row()->file_pengiriman;
            $proses_kirim_barang_at = $get_pengajuan_by_signature->row()->proses_kirim_barang_at;
            $tanggal_pemusnahan = $get_pengajuan_by_signature->row()->tanggal_pemusnahan;
            $nama_pemusnahan    = $get_pengajuan_by_signature->row()->nama_pemusnahan;
            $file_pemusnahan    = $get_pengajuan_by_signature->row()->file_pemusnahan;
            $foto_pemusnahan_1  = $get_pengajuan_by_signature->row()->foto_pemusnahan_1;
            $foto_pemusnahan_2  = $get_pengajuan_by_signature->row()->foto_pemusnahan_2;
            $video              = $get_pengajuan_by_signature->row()->video;
            $pemusnahan_at      = $get_pengajuan_by_signature->row()->pemusnahan_at;
            $tanggal_terima_barang = $get_pengajuan_by_signature->row()->tanggal_terima_barang;
            $nama_penerima      = $get_pengajuan_by_signature->row()->nama_penerima;
            $no_terima_barang   = $get_pengajuan_by_signature->row()->no_terima_barang;
            $file_terima_barang = $get_pengajuan_by_signature->row()->file_terima_barang;
            $terima_barang_at   = $get_pengajuan_by_signature->row()->terima_barang_at;
            $last_updated       = $get_pengajuan_by_signature->row()->last_updated;
            $last_updated_name  = $get_pengajuan_by_signature->row()->last_updated_name;
            $keterangan_lain  = $get_pengajuan_by_signature->row()->keterangan_lain;
        }

        $data = [
            'title'                 => 'Pengajuan Retur - Override Status',
            'url'                   => 'management_inventory/proses_override_status',
            'signature'             => $signature,
            'no_pengajuan'          => $no_pengajuan,
            'namasupp'              => $namasupp,
            'branch_name'           => $branch_name,
            'nama_comp'             => $nama_comp,
            'site_code'             => $site_code,
            'status'                => $status,
            'nama_status'           => $nama_status,
            'tanggal_pengajuan'     => $tanggal_pengajuan,
            'file'                  => $file,
            'verifikasi_mpm_name'   => $verifikasi_mpm_name,
            'verifikasi_at'         => $verifikasi_at,
            'principal_area_at'     => $principal_area_at,
            'principal_area_name'   => $principal_area_name,
            'catatan_principal_area'=> $catatan_principal_area,
            'file_principal_area'   => $file_principal_area,
            'principal_ho_at'       => $principal_ho_at,
            'principal_ho_name'     => $principal_ho_name,
            'catatan_principal_ho'  => $catatan_principal_ho,
            'file_principal_ho'     => $file_principal_ho,
            'tanggal_kirim_barang'  => $tanggal_kirim_barang,
            'nama_ekspedisi'        => $nama_ekspedisi,
            'est_tanggal_tiba'      => $est_tanggal_tiba,
            'file_pengiriman'       => $file_pengiriman,
            'proses_kirim_barang_at'=> $proses_kirim_barang_at,
            'tanggal_pemusnahan'    => $tanggal_pemusnahan,
            'nama_pemusnahan'       => $nama_pemusnahan,
            'file_pemusnahan'       => $file_pemusnahan,
            'foto_pemusnahan_1'     => $foto_pemusnahan_1,
            'foto_pemusnahan_2'     => $foto_pemusnahan_2,
            'video'                 => $video,
            'pemusnahan_at'         => $pemusnahan_at,
            'tanggal_terima_barang' => $tanggal_terima_barang,
            'nama_penerima'         => $nama_penerima,
            'no_terima_barang'      => $no_terima_barang,
            'file_terima_barang'    => $file_terima_barang,
            'terima_barang_at'      => $terima_barang_at,
            'last_updated'          => $last_updated,
            'last_updated_name'     => $last_updated_name,
            'keterangan_lain'       => $keterangan_lain,
            'get_pengajuan'         => $get_pengajuan_by_signature 
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/css');
        // $this->load->view('management_inventory/form_override_status', $data);
        // $this->load->view('kalimantan/footer');
        $this->render('management_inventory/form_override_status', $data);
    }

    public function proses_override_status()
    {
        $updated_at = $this->model_outlet_transaksi->timezone();
        $userid     = $this->session->userdata('id');
        $username   = $this->session->userdata('username');
        if ($username != 'linda' && $username != 'suffy' && $username != 'melinda') {
            $this->session->set_flashdata("pesan", "anda tidak diijinkan melakukan proses ini");
            redirect('management_inventory/dashboard');
            die;
        }

        $signature = $this->input->post('signature');
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "override gagal dijalankan !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }else{

            $id = $get_pengajuan_by_signature->row()->id;
        }
        $status = $this->input->post('status');

        if ($status == 1) { // jika memilih pending dp
            $data = [
                'tanggal_pengajuan' => NULL,
                'status'            => $status,
                'nama_status'       => $this->model_management_inventory->get_nama_status_by_id($status),
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 2) { // jika memilih pending mpm
            $data = [
                'status'            => $status,
                'nama_status'       => $this->model_management_inventory->get_nama_status_by_id($status),
                'verifikasi_at'     => NULL,
                'verifikasi_by'     => NULL,
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 3) { // jika memilih pending principal area
            $data = [
                'status'            => $status,
                'nama_status'       => $this->model_management_inventory->get_nama_status_by_id($status),
                'principal_area_at' => NULL,
                'principal_area_by' => NULL,
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 4) { // jika memilih pending principal ho
            $data = [
                'status'            => $status,
                'nama_status'       => $this->model_management_inventory->get_nama_status_by_id($status),
                'principal_ho_at'   => NULL,
                'principal_ho_by'   => NULL,
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 5) { // jika memilih pending kirim barang
            $data = [
                'status'                => $status,
                'nama_status'           => $this->model_management_inventory->get_nama_status_by_id($status),
                'tanggal_kirim_barang'  => NULL,
                'nama_ekspedisi'        => NULL,
                'est_tanggal_tiba'      => NULL,
                'proses_kirim_barang_at'=> NULL,
                'proses_kirim_barang_by'=> NULL,
                'file_pengiriman'       => NULL,
                'nama_ekspedisi'        => NULL,
                'last_updated'          => $updated_at,
                'last_updated_by'       => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 6) { // jika memilih pending terima barang
            $data = [
                'status'                => $status,
                'nama_status'           => $this->model_management_inventory->get_nama_status_by_id($status),
                'tanggal_terima_barang' => NULL,
                'nama_penerima'         => NULL,
                'no_terima_barang'      => NULL,
                'file_terima_barang'    => NULL,
                'terima_barang_at'      => NULL,
                'terima_barang_by'      => NULL,
                'last_updated'          => $updated_at,
                'last_updated_by'       => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 7) { // jika memilih pending pemusnahan
            $data = [
                'status'                => $status,
                'nama_status'           => $this->model_management_inventory->get_nama_status_by_id($status),
                'tanggal_pemusnahan'    => NULL,
                'nama_pemusnahan'       => NULL,
                'file_pemusnahan'       => NULL,
                'foto_pemusnahan_1'     => NULL,
                'foto_pemusnahan_2'     => NULL,
                'video'                 => NULL,
                'pemusnahan_at'         => NULL,
                'pemusnahan_by'         => NULL,
                'last_updated'          => $updated_at,
                'last_updated_by'       => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 11) { // jika memilih retur sample
            $data = [
                'status'                => $status,
                'nama_status'           => $this->model_management_inventory->get_nama_status_by_id($status),
                'last_updated'          => $updated_at,
                'last_updated_by'       => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 12) { // jika memilih pending principal ho
            $data = [
                'deleted'           => 1,
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }elseif ($status == 13) { // jika memilih reject
            $data = [
                'status'                => $status,
                'nama_status'           => $this->model_management_inventory->get_nama_status_by_id($status),
                'last_updated'          => $updated_at,
                'last_updated_by'       => $userid,
                'keterangan_lain'   => $this->input->post('keterangan_overide')
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }else{
            $this->session->set_flashdata("pesan", "proses anda gagal. Status yang anda pilih belum tersedia saat ini");
            redirect('management_inventory/form_override_status/'.$signature);
            die;
        }

    }

    public function export_by_date_status($from, $to, $status)
    {

        if ($this->session->userdata('level') == 3) { // jika principal

            $get_kode_alamat = $this->model_management_inventory->get_principal_akses($this->session->userdata('id'));
            $code = '';
            foreach ($get_kode_alamat->result() as $key) {
                $code.= ","."'".$key->site_code."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);

        }else{

            $get_kode_alamat = $this->model_inventory->get_kode_alamat();
            $code = '';
            foreach ($get_kode_alamat as $key) {
                $code.= ","."'".$key->kode_alamat."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);

        }

        // echo "kode_alamat : ".$kode_alamat;

        // die;

        if ($status == 0) {
            $params_status = "";
        }else{
            $params_status = "and a.status = '$status'";
        }

        $supp = $this->session->userdata('supp');
        if ($supp == '000') {
            $params_supp = "";
        }else{
            $params_supp = "and a.supp = '$supp'";
        }

        $query = "
        select 	a.nama_status, a.no_pengajuan, a.site_code,
                if(d.branch_name is null,i.name,d.branch_name) as branch_name,
                if(d.nama_comp is null, i.company, d.nama_comp) as nama_comp, a.tipe, a.key_account,
                e.namasupp, date(a.tanggal_pengajuan) as tanggal_pengajuan,
                b.kodeprod, c.namaprod, b.jumlah as qty_pengajuan, b.qty_approval, b.qty_approval_ho, b.qty_tolak, (b.qty_tolak * c.h_dp) as nrb_tolak, b.keterangan_final as keterangan_pabrik, b.satuan, b.batch_number, b.expired_date, b.nama_outlet, b.keterangan, b.keterangan_principal_area, b.alasan,
                a.principal_area_at, f.username as principal_area_name, f.email as principal_area_email, a.catatan_principal_area,
                a.verifikasi_at, g.username as mpm_name, g.email as mpm_email,
                a.principal_ho_at, h.username as principal_ho_name, h.email as principal_ho_email,a.catatan_principal_ho,
                a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.proses_kirim_barang_at,
                a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.terima_barang_at,
                a.tanggal_pemusnahan, a.pemusnahan_at, a.validasi_pemusnahan_at, a.deleted,
                CASE
                    WHEN 5 THEN date_add(date(a.principal_ho_at), interval 60 day)
                    WHEN 7 THEN date_add(date(a.principal_ho_at), interval 90 day)
                    ELSE null
                END as deadline,
                CASE
                    WHEN 5 THEN 
                    datediff(
                        date_add(date(a.principal_ho_at), interval 60 day), 
                        curdate()
                    )
                    WHEN 7 THEN 
                    datediff(
                        date_add(date(a.principal_ho_at), interval 90 day), 
                        curdate()
                    )
                    ELSE null
                END as sisa_hari
        from management_inventory.pengajuan_retur a INNER JOIN
        (
            select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah, a.satuan, a.qty_approval, a.qty_approval_ho, a.qty_tolak, a.nama_outlet, a.keterangan, a.keterangan_principal_area, a.alasan, a.batch_number, a.expired_date, 
            a.keterangan_final
            from management_inventory.pengajuan_retur_detail a
            where a.deleted is null
        )b on a.id = id_pengajuan LEFT JOIN
        (
            select a.kodeprod, a.namaprod, a.h_dp
            from site.master_product_with_harga a
        )c on b.kodeprod = c.kodeprod LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on a.site_code = d.site_code LEFT JOIN (
            select a.supp, a.namasupp
            from mpm.tabsupp a
            union all
            select '001-herbal' as supp, 'DELTOMED_HERBAL' as namasupp
            union all
            select '001-herbana' as supp, 'DELTOMED_HERBANA' as namasupp
            union all
            select '001-GT' as supp, 'DELTOMED-GT' as namasupp
            union all
            select '001-MTI' as supp, 'DELTOMED-MTI' as namasupp
            union all
            select '001-NKA' as supp, 'DELTOMED-NKA' as namasupp
            union all
            select '001-GT-PHARMA' as supp, 'DELTOMED-GT-PHARMA' as namasupp
            union all
            select '001-RTD' as supp, 'DELTOMED-RTD' as namasupp
        )e on a.supp = e.supp LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )f on a.principal_area_by = f.id LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )g on a.verifikasi_by = g.id LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )h on a.principal_ho_by = h.id LEFT JOIN
		(
			select a.id, a.username, a.name, a.company, a.kode_alamat
			from mpm.user a
		)i on a.site_code = i.kode_alamat
        where a.tanggal_pengajuan between '$from 00:00:00' and '$to 23:59:59' $params_status and a.site_code in ($kode_alamat) $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Export Pengajuan Retur.csv');
    }

    public function export_log_by_date_status($from, $to, $status)
    {
        if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5) { // jika dp
            redirect('management_inventory/pengajuan_retur');
            die;
        }else if ($this->session->userdata('level') == 3) 
        { // jika principal
            $get_kode_alamat = $this->model_management_inventory->get_principal_akses($this->session->userdata('id'));
            $code = '';
            foreach ($get_kode_alamat->result() as $key) {
                $code.= ","."'".$key->site_code."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);
        }else
        {
            $get_kode_alamat = $this->model_inventory->get_kode_alamat();
            $code = '';
            foreach ($get_kode_alamat as $key) {
                $code.= ","."'".$key->kode_alamat."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);
        }

        $supp = $this->session->userdata('supp');
        if ($supp == '000') {
            $params_supp = "";
        }else{
            $params_supp = "and b.supp = '$supp'";
        }

        $query = "
            SELECT a.id_pengajuan, b.no_pengajuan, b.site_code, b.supp, a.status, a.nama_status, a.status_email, a.nama_status_email, a.created_at, a.created_by
            FROM management_inventory.pengajuan_retur_log_email a
            INNER JOIN management_inventory.pengajuan_retur b on a.id_pengajuan = b.id
            WHERE a.id in (
                SELECT MAX(c.id)
                FROM management_inventory.pengajuan_retur_log_email c
                GROUP BY c.id_pengajuan, c.status
            ) and b.tanggal_pengajuan between '$from 00:00:00' and '$to 23:59:59' and b.site_code in ($kode_alamat) $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export Retur Log.csv');
    }

  public function pengajuan_retur()
  {
    // jika user principal maka redirect
    if ($this->session->userdata('level') == 3) { 
        redirect('management_inventory');
        die;
    }

    $get_kode_alamat = $this->model_inventory->get_kode_alamat();
    $code = '';
    foreach ($get_kode_alamat as $key) {
        $code.= ","."'".$key->kode_alamat."'";
    }
    $kode_alamat = preg_replace('/,/', '', $code,1);
    // echo "kode_alamat : ".$kode_alamat;
    // die;

    // cek user surdon, mpi, penta dan dp
    $user_surdon = $this->model_management_inventory->get_user_surdon($this->session->userdata('id'));
    if ($user_surdon->num_rows() > 0) {
        $status_surdon = 1;
    } else {
        $status_surdon = 0;
    }

    $user_mpi = $this->model_management_inventory->get_user_mpi($this->session->userdata('id'));
    if ($user_mpi->num_rows() > 0) {
        $status_mpi = 1;
    } else {
        $status_mpi = 0;
    }

    $user_penta = $this->model_management_inventory->get_user_penta($this->session->userdata('id'));
    if ($user_penta->num_rows() > 0) {
        $status_penta = 1;
    } else {
        $status_penta = 0;
    }

    if($this->input->get('from')){

        $advanced['from']   = $this->input->get('from');
        $advanced['to']     = $this->input->get('to');
        $advanced['status'] = $this->input->get('status');
        $advanced['type']   = $this->input->get('btn_type');

        if ($this->input->get('btn_type') == 2) {
            // echo "ini export : ".$this->input->get('type');
            $this->export_by_date_status($this->input->get('from'), $this->input->get('to'), $this->input->get('status'));
            die;
        } 

    }else{
        $advanced = "";
    }

    $data = [
        'title'             => 'Pengajuan Retur',
        'get_pengajuan'     => $this->model_management_inventory->get_pengajuan("", $kode_alamat, $advanced),
        'key_account'       => $this->model_management_inventory->get_key_account(),
        'url'               => 'management_inventory/pengajuan_retur_proses',
        'url_search'        => '',
        'status_mpi'        => $status_mpi,
        'status_penta'      => $status_penta,
        'status_surdon'     => $status_surdon,
        'min_nrb'           => date('Y-m-d', strtotime($this->model_outlet_transaksi->timezone(). ' - 2 month')),
        'site_code'         => $this->model_management_inventory->get_sitecode(),
        'username'          => $this->session->userdata('username')
    ];

    $this->render('management_inventory/pengajuan_retur', $data);
  }

    public function pengajuan_retur_proses()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'RTR-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $tgl_nrb = $this->input->post('tgl_nrb');
        $batas_nrb = date('Y-m-d', strtotime($created_at. ' - 2 month'));

        // echo "batas_nrb : ".$batas_nrb;
        // die;

        if ($tgl_nrb) {
            if ($tgl_nrb < $batas_nrb) {
                $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Tanggal Nota Retur Barang (NRB) tidak boleh melebihi 2 bulan");
                redirect('management_inventory/pengajuan_retur/');
                die;
            } 
        } 

        // cek apakah sudah ada signature
        $cek_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        if (!file_exists($cek_signature)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect('management_inventory/pengajuan_retur/');
            die;
        } else {
            $digital_signature = $this->session->userdata('username').'-signature.png';
        }

        $this->load->library('upload'); // Load librari upload    
        // upload file
        if (!is_dir('./assets/file/retur/email_capture')) {
            @mkdir('./assets/file/retur/email_capture', 0777);
        }

        $config['upload_path'] = './assets/file/retur/email_capture';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        $this->upload->initialize($config);

        // if ($this->upload->do_upload('ajuan_zip')) 
        // {
        //     $upload_data = $this->upload->data();
        //     $filename_zip = $upload_data['file_name'];
        // }else
        // {
        //     var_dump($this->upload->display_errors());
        //     die;
        // };

        // $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file1')) {
            $filename1 = NULL;
        } else {

            // $data = array('upload_data' => $this->upload->data());
            // $file = $data['upload_data']['full_path'];
            // chmod($file, 0777);
            // $upload_data = $this->upload->data();
            // $filename1 = $upload_data['file_name'];

            $upload_data = $this->upload->data();
            $filename1 = $upload_data['file_name'];
        }

        if (!is_dir('./assets/file/retur/tanda_terima')) {
            @mkdir('./assets/file/retur/tanda_terima', 0777);
        }

        $config['upload_path'] = './assets/file/retur/tanda_terima';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        // $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file2')) {
            $filename2 = NULL;
        } else {

            // $data = array('upload_data' => $this->upload->data());
            // $file2 = $data['upload_data']['full_path'];
            // chmod($file2, 0777);
            // $upload_data2 = $this->upload->data();
            // $filename2 = $upload_data2['file_name'];

            $upload_data2 = $this->upload->data();
            $filename2 = $upload_data2['file_name'];

        }

        if (!is_dir('./assets/file/retur/foto')) {
            @mkdir('./assets/file/retur/foto', 0777);
        }

        $config['upload_path'] = './assets/file/retur/foto';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->upload->initialize($config);

        // $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file3')) {
            $filename3 = NULL;
        } else {

            // $data = array('upload_data' => $this->upload->data());
            // $file3 = $data['upload_data']['full_path'];
            // chmod($file3, 0777);
            // $upload_data3 = $this->upload->data();
            // $filename3 = $upload_data3['file_name'];
            $upload_data3 = $this->upload->data();
            $filename3 = $upload_data3['file_name'];
        }

        // end upload file
        
        $data = [
            'site_code'         => $this->input->post('site_code'),
            'file'              => $filename1,
            'file_2'            => $filename2,
            'file_3'            => $filename3,
            'supp'              => $this->input->post('supp'),
            'key_account'       => (!$this->input->post('key_account')) ? NULL : $this->input->post('key_account'),
            'nama'              => $this->input->post('nama'),
            'tipe'              => $this->input->post('tipe'),
            'status'            => '1',
            'nama_status'       => 'PENDING DP',
            // 'tanggal_pengajuan' => $created_at,
            'tanggal_nrb'       => ($tgl_nrb == true) ? $tgl_nrb : null,
            'digital_signature' => $digital_signature,
            'created_at'        => $created_at,
            'created_by'        => $this->session->userdata('id'),
            'signature'         => $signature,
            'last_updated'      => $created_at,
            'last_updated_by'   => $this->session->userdata('id'),
            'is_file_folder_retur'  => 1
        ];

        $this->db->insert('management_inventory.pengajuan_retur', $data);

        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$this->input->post('supp'));

    }

    public function pengajuan_retur_detail($signature, $supp)
    {
        $get_pengajuan      = $this->model_management_inventory->get_pengajuan($signature);
        $id_pengajuan       = $get_pengajuan->row()->id;
        $supp               = $get_pengajuan->row()->supp;
        $tipe               = $get_pengajuan->row()->tipe;
        $tanggal_pengajuan  = $get_pengajuan->row()->tanggal_pengajuan;
        $signature          = $get_pengajuan->row()->signature;

        // echo "tipe : " . $tipe;
        // die;

        // penghitungan jumlah produk, value, dan jumalh qty
        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
            $sum_qty_pengajuan = $a->sum_qty_pengajuan;
        }
        // end penghitungan jumlah produk, value, dan jumalh qty

        $data = [
            'title'                      => 'Pending DP',
            'url'                        => 'management_inventory/pengajuan_retur_detail_proses',
            'url_import'                 => 'management_inventory/pengajuan_retur_detail_import',
            'get_pengajuan'              => $get_pengajuan,
            'get_pengajuan_detail_accordion'    => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            'url_pengajuan'              => 'management_inventory/bridging_to_principal_area',
            'url_revisi'                 => 'management_inventory/revisi_nrb',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'id_pengajuan'               => $id_pengajuan,
            'supp'                       => $supp,
            'tipe'                       => $tipe,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'tanggal'                    => $this->created_at,
            'batas_revisi_nrb'           => date('Y-m-d', strtotime(($tanggal_pengajuan ? $tanggal_pengajuan:$this->created_at). ' + 1 month')),
            'signature'                  => $signature,
            'count_kodeprod'             => $count_kodeprod,
            'sum_qty_pengajuan'          => $sum_qty_pengajuan,
            'value_rbp'                  => $value_rbp
        ];

        $this->render_multiple(
            array(
                'management_inventory/accordion',
                'management_inventory/pengajuan_retur_detail'
            ),
            $data
        );   
    }

    public function delete_product($signature_detail, $supp, $signature)
    {
        $data = [
            "deleted"    => 1,
        ];

        $this->db->where('signature', $signature_detail);
        $this->db->update('management_inventory.pengajuan_retur_detail', $data);

        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
    }

    public function pengajuan_retur_detail_proses()
    {
        $signature_detail = 'RTR-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            "id_pengajuan"      => $this->input->post('id_pengajuan'),
            "kodeprod"          => $this->input->post('kodeprod'),
            "batch_number"      => $this->input->post('batch_number'),
            "satuan"            => $this->input->post('satuan'),
            "expired_date"      => $this->input->post('ed'),
            "jumlah"            => $this->input->post('jumlah'),
            "nama_outlet"       => $this->input->post('nama_outlet'),
            "alasan"            => $this->input->post('alasan_retur'),
            "keterangan"        => $this->input->post('keterangan'),
            "created_at"        => $this->model_outlet_transaksi->timezone(),
            "created_by"        => $this->session->userdata('id'),
            "signature"         => $signature_detail
        ];

        $this->db->insert('management_inventory.pengajuan_retur_detail', $data);
        redirect('management_inventory/pengajuan_retur_detail/'.$this->input->post('signature').'/'.$this->input->post('supp'));

    }

    public function revisi_nrb()
    {
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');

        $get_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "override gagal dijalankan !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $tanggal = $this->created_at;
        $tanggal_pengajuan = $get_pengajuan->row()->tanggal_pengajuan;
        $batas_revisi_nrb = date('Y-m-d', strtotime($tanggal_pengajuan. ' + 1 month'));

        if ($tanggal > $batas_revisi_nrb) {
            $this->session->set_flashdata("pesan", "Tanggal NRB gagal diupdate. Karena sudah melebihi batas revisi 1 bulan. Terima kasih");
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }

        $this->load->library('upload'); // Load librari upload    
        // upload file
        if (!is_dir('./assets/file/retur/email_capture')) {
            @mkdir('./assets/file/retur/email_capture', 0777);
        }

        $config['upload_path'] = './assets/file/retur/email_capture';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file1')) {
            $filename1 = NULL;
        } else {
            $upload_data = $this->upload->data();
            $filename1 = $upload_data['file_name'];
        }

        $data = [
            "tanggal_nrb" => $this->input->post('tanggal_nrb'),
            'file'        => $filename1,
        ];

        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);

        $this->session->set_flashdata("pesan_success", "Tanggal NRB berhasil diupdate. Terima kasih");
        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
    }

    public function proses_mpm()
    {
        $signature = $this->input->post('signature');
        $created_at = $this->model_outlet_transaksi->timezone();

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $supp = $get_pengajuan->supp;
        $get_no_pengajuan = $get_pengajuan($signature)->no_pengajuan;
        if ($get_no_pengajuan == '' || $get_no_pengajuan == NULL) {
            $no_ajuan = $this->model_management_inventory->generate($created_at);
        }else{
            $no_ajuan = $get_no_pengajuan;
        }

        $data = [
            "status"            => 2,
            "nama_status"       => "PROSES MPM",
            "no_pengajuan"      => $no_ajuan,
            'last_updated'      => $created_at,
            'last_updated_by'   => $this->session->userdata('id')
        ];

        $this->db->where("signature", $signature);
        $this->db->update("management_inventory.pengajuan_retur", $data);

        $this->email_pengajuan($signature);

        redirect("management_inventory/pengajuan_retur_detail/$signature/$supp");
        die;

    }

    public function email_pengajuan($signature)
    {
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $site_code = $get_pengajuan->site_code;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $supp = $get_pengajuan->supp;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();
        $data = [
            'get_pengajuan_detail'  => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'      => $no_pengajuan,
            'branch_name'       => $get_pengajuan->branch_name,
            'nama_comp'         => $get_pengajuan->nama_comp,
            'site_code'         => $site_code,
            'namasupp'          => $get_pengajuan->namasupp,
            'tanggal_pengajuan' => $get_pengajuan->tanggal_pengajuan,
            'nama'              => $get_pengajuan->nama,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $get_pengajuan->nama_status,
            'created_by'        => $get_pengajuan->created_by,
            'file'              => $get_pengajuan->file,
            'id_pengajuan'      => $id_pengajuan,
            'supp'              => $supp,
            'signature'         => $signature,
            'count_kodeprod'    => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'         => $get_pengajuan_detail_summary->value_rbp,

        ];

        $from = "suffy@muliaputramandiri.com";
        $to = 'suffy.yanuar@gmail.com';
        $cc = 'suffy.mpm@gmail.com';
        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email.' ,linda@muliaputramandiri.com, ilham@muliaputramandiri.com';
        $subject = "MPM SITE | RETUR : $no_pengajuan | PROSES PRINCIPAL HO";
        $message = $this->load->view("management_inventory/email_pengajuan",$data,TRUE);

        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        // simpan informasi email terkirim atau gagal
        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_pengajuan',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $get_pengajuan->nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_pengajuan',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);
        //end simpan informasi email
    }

    public function verifikasi_retur($signature, $supp)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan  = $this->model_management_inventory->get_pengajuan($signature, "");
        $id_pengajuan   = $get_pengajuan->row()->id;
        $supp           = $get_pengajuan->row()->supp;
        $verifikasi_at  = $get_pengajuan->row()->verifikasi_at;

        $data = [
            'title'                      => 'Pending MPM',
            'url'                        => 'management_inventory/verifikasi_retur_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan'              => $get_pengajuan,
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'       => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            'supp'                       => $supp,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
        ];

        $this->render_multiple(
            array(
                'management_inventory/accordion',
                'management_inventory/verifikasi_retur_detail'
            ),
            $data
        );

    }

    public function verifikasi_retur_proses()
    {
        $signature = $this->input->post('signature');
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $supp = $this->input->post('supp');
        $id = $this->input->post('options');

        foreach ($id as $id_product) {

            $qty_pengajuan = $this->model_management_inventory->get_qty_pengajuan_by_id_product($id_product)->row()->jumlah;

            if ($this->input->post('status_approval') == '3') {
                $nama_status = 'verified';
            }else{
                $nama_status ='not verified';
                $qty_pengajuan = NULL;
            }

            if ($supp == '001-herbana' || $supp == '002' || $supp == '004' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015') {
                $data = [
                    "status"        => $this->input->post('status_approval'),
                    "nama_status"   => $nama_status,
                    "deskripsi"     => $this->input->post('deskripsi'),
                    "qty_approval"  => $qty_pengajuan
                ];
            }else{
                $data = [
                    "status"        => $this->input->post('status_approval'),
                    "nama_status"   => $nama_status,
                    "deskripsi"     => $this->input->post('deskripsi'),
                ];
            }

            $this->db->where('id', $id_product);
            $this->db->update('management_inventory.pengajuan_retur_detail', $data);
        }
        redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
    }

    public function bridging_to_principal_area()
    {
        $signature  = $this->input->post('signature');
        $suppx      = $this->input->post('supp');
        $tipe       = $this->input->post('tipe');
        $created_at = $this->model_outlet_transaksi->timezone();

        $data_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row();
        $id_pengajuan   = $data_pengajuan->id;
        $get_site_code  = $data_pengajuan->site_code;
        $key_account    = $data_pengajuan->key_account;

        // cek jumlah produk, tidak boleh 0 dan tidak boleh lebih dari 50
        $get_total_produk = $this->model_management_inventory->get_pengajuan_retur_detail_by_id_pengajuan($id_pengajuan)->num_rows();
        if ($get_total_produk > 50) {
            $this->session->set_flashdata("pesan", "Submit pengajuan gagal dijalankan karena total produk sudah melebihi 50 !");
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        }elseif($get_total_produk == 0){
            $this->session->set_flashdata("pesan", "Anda belum menginputkan satu produk !");
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        }

        // cek traffic pengajuan retur
        $traffic = $this->model_management_inventory->get_traffic();
        if($traffic->num_rows() > 0){
            $traffic = $traffic->row()->status_generate;

            if ($traffic == 1) {
                $this->session->set_flashdata("pesan", "Proses yang anda lakukan gagal dikarenakan server sedang sibuk. Silahkan coba lagi nanti");
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
                die;
            }else{
                $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 1);
            }

        }else{
            $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 1);
        }

        if($suppx == "001-GT" || $suppx == "001-GT-PHARMA" || $suppx == "001-MTI" || $suppx == "001-NKA" || $suppx == "001-RTD"){
            $supp = "001";
        }else{
            $supp = $suppx;
        }

        // echo "suppx: ".$suppx;
        // echo "supp: ".$supp;
        // die;

        // cek apakah sudah ada signature
        $cek_dp_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        if (!file_exists($cek_dp_signature)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
            die;
        }
        // end cek signature

        // cek pic khusus delto dan us
        if ($supp == '001' || $supp == '005') {
            // cek apakah ada mapping di mapping_area_retur atau mapping_key_account
            $get_principal_area = $this->model_management_inventory->get_principal_area($get_site_code, $suppx, $key_account);
            if(!$get_principal_area->num_rows() > 0){
                $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Tidak ditemukan PIC Principal Area. Capture pesan ini dan koordinasikan ini kepada tim terkait");
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
                die;
            }
        }
        // end cek pic

        // cek apakah satuan ada yang null
        $query_cek_satuan = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id_pengajuan = $id_pengajuan and (a.satuan = '' or a.satuan is null) and a.deleted is null
        ";

        $cek = $this->db->query($query_cek_satuan);

        if($cek->num_rows() > 0){
            $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan produk yang tidak mempunyai satuan yaitu : ".$cek->row()->kodeprod);
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
            die;
        }

        // cek apakah kodeprod madu lama 
        $query_kodeprod_madu = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id_pengajuan = $id_pengajuan and a.kodeprod in (060011, 060012, 060073) 
        ";

        $cek = $this->db->query($query_kodeprod_madu);

        if($cek->num_rows() > 0){
            $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan produk madu yang sudah tidak aktif: ".$cek->row()->kodeprod);
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
            die;
        }

        // cek apakah ada tanggal 2036-x-x
        $query_cek_tanggal = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id_pengajuan = $id_pengajuan and a.deleted is null and year(a.expired_date) = 2036
        ";

        $cek = $this->db->query($query_cek_tanggal);

        if($cek->num_rows() > 0){
            $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan expired date yang mempunyai format salah yaitu : ".$cek->row()->expired_date);
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
            die;
        }

        // die;

        // cek ke master alasan
        $query_cek_alasan = "
            select a.alasan, b.kode_alasan
            from management_inventory.pengajuan_retur_detail a left JOIN 
            (
                select a.kode_alasan, a.nama_alasan
                from management_inventory.master_alasan a
                where a.supp = '$suppx' and a.tipe = '$tipe' and a.deleted_at is null
            )b on a.alasan = b.kode_alasan
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and b.kode_alasan is null
        ";

        // echo "<pre>";
        // print_r($query_cek_alasan);
        // echo "</pre>";
        // die;

        $cek = $this->db->query($query_cek_alasan);

        if($cek->num_rows() > 0){
            $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal !. <strong>Ditemukan alasan yang tidak sesuai dengan Tipe Retur yang anda pilih, yaitu : ".$cek->row()->alasan."</strong>");
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
            die;
        }

        // cek kodeprod
        if ($supp == '001-herbana') {
            $params_supp = "001";
            $params_group = "and b.grup = 'G0103'";
        } elseif ($supp == '001-RTD') {
            $params_supp = "001";
            $params_group = "and b.new_divisi = 'RTD'";
        } else{
            $params_supp = "$supp";
            $params_group = "";
        }
        // die;
        $query_cek = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id_pengajuan = $id_pengajuan and a.deleted is null and a.kodeprod not in (
                select b.kodeprod
                from mpm.tabprod b
                where b.supp = '$params_supp' $params_group
            )
        ";
        // echo "<pre>";
        // print_r($query_cek);
        // echo "</pre>";
        // die;

        $cek = $this->db->query($query_cek);
        if($cek->num_rows() > 0){
            $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan produk yang tidak seharusnya yaitu : ".$cek->row()->kodeprod);
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
            die;
        }
        // end cek kodeprod

        // start cek retur khusus
        if ($tipe == 'retur_khusus') {
            $this->bridging_to_principal_ho($signature, $suppx, $tipe);
            die;
        }
        // end cek retur khusus

        // start cek kodeprod berdasarkan master exeception
        if ($tipe != 'retur_administrasi') {
            $query_cek = "
                select a.kodeprod, a.total_jumlah, b.unit
                from (
                    select a.kodeprod, a.jumlah as total_jumlah
                    from management_inventory.pengajuan_retur_detail a 
                    where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.deleted is null
                )a inner join management_inventory.master_execption b 
                        on a.kodeprod = b.kodeprod
                where a.kodeprod = b.kodeprod and a.total_jumlah > b.unit
            ";

            $cek = $this->db->query($query_cek);
            $produk_exception = $cek->row()->kodeprod;
            $batas_unit = $cek->row()->unit;

            // echo "<pre>";
            // print_r($query_cek);
            // echo "produk exception : $produk_exception";
            // echo "</pre>";
            // die;

            if($cek->num_rows() > 0){
                $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
                $this->session->set_flashdata("pesan", "Kode produk <strong>$produk_exception ditolak karena melebihi limit</strong> yang diijinkan. <strong>Limit yang diijinkan adalah $batas_unit </strong>");
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
                die;
            }
        // end cek kodeprod berdasarkan master exeception
        }

        // cek ED berdasakar tipe reguler dan kategori ed
        if ($tipe == "reguler" || $tipe == "retur_kategori_ed")
        {
            $cek_selisih = "
                select a.kodeprod, a.nama_outlet, a.batch_number, a.expired_date, NOW(), DATEDIFF(NOW(),a.expired_date) as selisih,
                if(b.ed_after is null, 31, b.ed_after) as ed_after, if(b.ed_before is null, 90, b.ed_before) as ed_before
                from management_inventory.pengajuan_retur_detail a
                LEFT JOIN mpm.tabprod b on a.kodeprod = b.kodeprod
                where a.id_pengajuan = $id_pengajuan and a.deleted is null
            ";

            // echo "<pre>";
            // print_r($cek_selisih);
            // echo "</pre>";

            // die;

            $proses_cek_selisih = $this->db->query($cek_selisih)->result();
            foreach($proses_cek_selisih as $a){
                $selisih_ed = $a->selisih;
                $kodeprod_ed = $a->kodeprod;
                $batch_number_ed = $a->batch_number;
                $expired_date_ed = $a->expired_date;
                $ed_after = $a->ed_after;
                $ed_before_originial = $a->ed_before;
                $selisih_ed_posifif = $a->selisih * -1;
                $ed_before = $a->ed_before * -1;
                $bulan_after = intval($ed_after / 30);
                $bulan_before = intval(($ed_before * -1) / 30);
                // echo "<pre>";
                // echo "expired_date_ed : ".$expired_date_ed."<br>";
                // echo "selisih_ed : ".$selisih_ed."<br>";
                // echo "ed_after : ".$ed_after."<br>";
                // echo "ed_before : ".$ed_before."<br>";
                // echo "</pre>";

                // die;

                if ($selisih_ed > $ed_after) {
                    // echo "aaa";
                    $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
                    // $this->session->set_flashdata("pesan", "Proses pengajuan gagal. <br><br>Ditemukan ED yang melebihi batas $bulan_after bulan yaitu kodeprod : ".$kodeprod_ed. ' , batch_number : '.$batch_number_ed. ' , ed : '.$expired_date_ed. ' ,selisih : '.$selisih_ed. ' hari setelah ED');
                    $this->session->set_flashdata("pesan", "kode produk <strong>$kodeprod_ed $batch_number_ed ditolak</strong>, karena selisih hari kalender adalah <strong>$selisih_ed hari</strong>. Ketentuan yang benar adalah <strong>tidak boleh melebihi $ed_after hari kalender.</strong>");
                    redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
                    die;
                }
                else if ($selisih_ed <= $ed_before) {
                    // echo "bbb";
                    $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
                    // $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. <br><br>Ditemukan produk yang melebihi $bulan_before bulan sebelum ED yaitu kodeprod : ".$kodeprod_ed. ' , batch_number : '.$batch_number_ed. ' , ed : '.$expired_date_ed. ' ,selisih : '.abs($selisih_ed). ' hari sebelum ED');
                    $this->session->set_flashdata("pesan", "kode produk <strong>$kodeprod_ed $batch_number_ed ditolak</strong>, karena selisih hari kalender adalah <strong>$selisih_ed_posifif hari</strong>. Ketentuan yang benar adalah <strong>tidak boleh lebih dari $ed_before_originial hari kalender.</strong>");
                    redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
                    die;
                }else{
                    // echo "lolos dengan selisih : ".$selisih_ed;
                }
            }
        }
        // end cek ED berdasakar tipe reguler dan kategori ed

        $get_no_pengajuan = $data_pengajuan->no_pengajuan;
        if ($get_no_pengajuan == '' || $get_no_pengajuan == NULL) {
            $no_ajuan = $this->model_management_inventory->generate($created_at);
        }else{
            $no_ajuan = $get_no_pengajuan;
        }

        // die;

        // cek supp, jika delto (001-gt, 001-mt) dan us maka status pending principal area. jika supp selain delto maka status ke pending mpm
        if ($supp == '002' || $supp == '004' || $supp == '012' || $supp == '013' || $supp == '015' || $supp == '001-herbana') {
            $data = [
                "status"            => 2,
                "tanggal_pengajuan" => $created_at,
                "nama_status"       => "PENDING MPM",
                "no_pengajuan"      => $no_ajuan,
                "verifikasi_at"     => NULL
            ];
        }else{
            $data = [
                "status"            => 3,
                "tanggal_pengajuan" => $created_at,
                "nama_status"       => "PENDING PRINCIPAL AREA",
                "no_pengajuan"      => $no_ajuan,
                'last_updated'      => $created_at,
                'last_updated_by'   => $this->session->userdata('id')
            ];
        }
        $this->db->where("signature", $signature);
        $this->db->update("management_inventory.pengajuan_retur", $data);
        // end cek supp

        $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);

        if ($suppx == '002' || $suppx == '004' || $suppx == '012' || $suppx == '013' || $suppx == '015' || $suppx == '001-herbana') {

            // redirect('management_inventory/email_proses_principal_ho/'.$signature);
            // redirect('management_inventory/email_pengajuan_new/'.$signature);
            $this->email_proses_mpm($signature);

        }else{
            $this->email_proses_principal_area_new($signature);
        }
        redirect("management_inventory/pengajuan_retur_detail/$signature/$suppx");
    }

    public function bridging_to_principal_ho($signature, $suppx, $tipe)
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $data_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row();
        $id_pengajuan   = $data_pengajuan->id;
        $get_site_code  = $data_pengajuan->site_code;

        // cek jumlah row penginputan item produk 
        // $data_pengajuan_detail = $this->model_management_inventory->get_pengajuan_detail($id_pengajuan);
        // if ($data_pengajuan_detail->num_rows() > 251) {
        //     $this->session->set_flashdata("pesan", "Submit pengajuan gagal dijalankan karena total produk sudah melebihi 250 !");
        //     redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //     die;
        // }
        // end cek jumlah row penginputan item produk 

        // cek traffic pengajuan retur
        // $traffic = $this->model_management_inventory->get_traffic();
        // if($traffic->num_rows() > 0){
        //     $traffic = $traffic->row()->status_generate;

        //     if ($traffic == 1) {
        //         $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
        //         redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //         die;
        //     }else{
        //         $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 1);
        //     }

        // }else{
        //     $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 1);
        // }
        // end cek traffic

        // die;

        if($suppx == "001-GT" || $suppx == "001-GT-PHARMA" || $suppx == "001-MTI" || $suppx == "001-NKA" || $suppx == "001-RTD"){
            $supp = "001";
        }else{
            $supp = $suppx;
        }

        // cek apakah sudah ada signature
        // $cek_dp_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        // if (!file_exists($cek_dp_signature)) {
        //     $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
        //     $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
        //     redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //     die;
        // }
        // end cek signature

        // cek apakah ada produknya
        // $query_cek_product_null = "
        //     select *
        //     from management_inventory.pengajuan_retur_detail a
        //     where a.id_pengajuan = $id_pengajuan and a.deleted is null
        // ";
        // $proses_query_cek_product_null = $this->db->query($query_cek_product_null);
        // if($proses_query_cek_product_null->num_rows() == 0){
        //     $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Silahkan masukkan product terlebih dahulu");
        //     redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //     die;
        // }
        // end cek produk

        // cek apakah satuan ada yang null
        // $query_cek_satuan = "
        //     select *
        //     from management_inventory.pengajuan_retur_detail a
        //     where a.id_pengajuan = $id_pengajuan and (a.satuan = '' or a.satuan is null) and a.deleted is null
        // ";

        // $cek = $this->db->query($query_cek_satuan);

        // if($cek->num_rows() > 0){
        //     $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan produk yang tidak mempunyai satuan yaitu : ".$cek->row()->kodeprod);
        //     redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //     die;
        // }

        // cek apakah ada tanggal 2036-x-x
        // $query_cek_tanggal = "
        //     select *
        //     from management_inventory.pengajuan_retur_detail a
        //     where a.id_pengajuan = $id_pengajuan and a.deleted is null and year(a.expired_date) = 2036
        // ";

        // $cek = $this->db->query($query_cek_tanggal);

        // if($cek->num_rows() > 0){
        //     $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan expired date yang mempunyai format salah yaitu : ".$cek->row()->expired_date);
        //     redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //     die;
        // }

        // cek ke master alasan
        // $query_cek_alasan = "
        //     select a.alasan, b.kode_alasan
        //     from management_inventory.pengajuan_retur_detail a left JOIN 
        //     (
        //         select *
        //         from management_inventory.master_alasan a
        //         where a.supp = '$suppx' and a.tipe = '$tipe' and a.deleted_at is null
        //     )b on a.alasan = b.kode_alasan
        //     where a.id_pengajuan = $id_pengajuan and b.kode_alasan is null
        // ";

        // $cek = $this->db->query($query_cek_alasan);

        // if($cek->num_rows() > 0){
        //     $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan data yang mempunyai alasan tidak sesuai ketentuan, yaitu : ".$cek->row()->alasan);
        //     redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //     die;
        // }

        // cek kodeprod berdasarkan supp
        // if ($supp == '001-herbana') {
        //     $params_supp = "001";
        //     $params_group = "and b.grup = 'G0103'";
        // } elseif ($supp == '001-RTD') {
        //     $params_supp = "001";
        //     $params_group = "and b.new_divisi = 'RTD'";
        // } else{
        //     $params_supp = "$supp";
        //     $params_group = "";
        // }
        
        // $query_cek = "
        //     select *
        //     from management_inventory.pengajuan_retur_detail a
        //     where a.id_pengajuan = $id_pengajuan and a.deleted is null and a.kodeprod not in (
        //         select b.kodeprod
        //         from mpm.tabprod b
        //         where b.supp = '$params_supp' $params_group
        //     )
        // ";
        // echo "<pre>";
        // print_r($query_cek);
        // echo "</pre>";die;

        // $cek = $this->db->query($query_cek);
        // if($cek->num_rows() > 0){
        //     $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan produk yang tidak seharusnya yaitu : ".$cek->row()->kodeprod);
        //     redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //     die;
        // }
        // end cek kodeprod berdasarkan supp

        // cek kodeprod berdasarkan master exeception
        // $query_cek = "
        //     SELECT *
        //     FROM
        //     (
        //         select *
        //         from management_inventory.pengajuan_retur_detail a
        //         where a.id_pengajuan = $id_pengajuan and deleted is null
        //     )a
        //     INNER JOIN management_inventory.master_execption b on a.kodeprod = b.kodeprod and a.jumlah > b.unit
        // ";

        // $cek = $this->db->query($query_cek);
        // if($cek->num_rows() > 0){
        //     $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
        //     $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan produk khusus yang jumlahnya melebihi batas ketentuan : ".$cek->row()->kodeprod);
        //     redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //     die;
        // }
        // end cek kodeprod berdasarkan master exeception

        // cek ED berdasarkan tipe reguler dan retur kategori ed
        // if ($tipe == "reguler" || $tipe == "retur kategori ed")
        // {
        //     $cek_selisih = "
        //         select a.kodeprod, a.nama_outlet, a.batch_number, a.expired_date, NOW(), DATEDIFF(NOW(),a.expired_date) as selisih,
        //         if(b.ed_after is null, 30, b.ed_after) as ed_after, if(b.ed_before is null, 90, b.ed_before) as ed_before
        //         from management_inventory.pengajuan_retur_detail a
        //         LEFT JOIN mpm.tabprod b on a.kodeprod = b.kodeprod
        //         where a.id_pengajuan = $id_pengajuan and a.deleted is null
        //     ";

        //     // echo "<pre>";
        //     // print_r($cek_selisih);
        //     // echo "</pre>";

        //     // die;

        //     $proses_cek_selisih = $this->db->query($cek_selisih)->result();
        //     foreach($proses_cek_selisih as $a){
        //         $selisih_ed = $a->selisih;
        //         $kodeprod_ed = $a->kodeprod;
        //         $batch_number_ed = $a->batch_number;
        //         $expired_date_ed = $a->expired_date;
        //         $ed_after = $a->ed_after;
        //         $ed_before = $a->ed_before * -1;
        //         $bulan_after = intval($ed_after / 30);
        //         $bulan_before = intval(($ed_before * -1) / 30);
        //         // echo "<pre>";
        //         // echo "expired_date_ed : ".$expired_date_ed."<br>";
        //         // echo "selisih_ed : ".$selisih_ed."<br>";
        //         // echo "ed_after : ".$ed_after."<br>";
        //         // echo "ed_before : ".$ed_before."<br>";
        //         // echo "</pre>";

        //         // die;

        //         if ($selisih_ed > $ed_after) {
        //             // echo "aaa";
        //             $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
        //             $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. <br><br>Ditemukan ED yang melebihi batas $bulan_after bulan yaitu kodeprod : ".$kodeprod_ed. ' , batch_number : '.$batch_number_ed. ' , ed : '.$expired_date_ed. ' ,selisih : '.$selisih_ed. ' hari setelah ED');
        //             redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //             die;
        //         }
        //         else if ($selisih_ed <= $ed_before) {
        //             // echo "bbb";
        //             $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
        //             $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. <br><br>Ditemukan produk yang melebihi $bulan_before bulan sebelum ED yaitu kodeprod : ".$kodeprod_ed. ' , batch_number : '.$batch_number_ed. ' , ed : '.$expired_date_ed. ' ,selisih : '.abs($selisih_ed). ' hari sebelum ED');
        //             redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
        //             die;
        //         }
        //     }
        // }
        // end cek ED berdasarkan tipe reguler dan retur kategori ed
        
        // simpan data header ke pengajuan retur
        $get_no_pengajuan = $data_pengajuan->no_pengajuan;
        if ($get_no_pengajuan == '' || $get_no_pengajuan == NULL) {
            $no_ajuan = $this->model_management_inventory->generate($created_at);
        }else{
            $no_ajuan = $get_no_pengajuan;
        }

        // simpan data header ke pengajuan retur
        $data = [
            "status"            => 4,
            "tanggal_pengajuan" => $created_at,
            "nama_status"       => "PENDING PRINCIPAL HO",
            "no_pengajuan"      => $no_ajuan,
            'last_updated'      => $created_at,
            'last_updated_by'   => $this->session->userdata('id')
        ];
        $this->db->where("signature", $signature);
        $this->db->update("management_inventory.pengajuan_retur", $data);
        // end simpan data header ke pengajuan retur

        // update pengajuan retur detail
        $query = "
            update management_inventory.pengajuan_retur_detail a
            set a.qty_approval = a.jumlah,
                a.status = 3,
                a.nama_status = 'verified'
            where a.id_pengajuan = $id_pengajuan and a.deleted is null
        ";
        
        $this->db->query($query);
        // end update pengajuan retur detail

        $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);
        $this->email_proses_principal_ho($signature);
        redirect("management_inventory/pengajuan_retur_detail/$signature/$suppx");
    }

    public function principal_area($signature, $supp)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "anda di redirect ke halaman awal !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan      = $this->model_management_inventory->get_pengajuan($signature);
        $id_pengajuan       = $get_pengajuan->row()->id;
        $site_code          = $get_pengajuan->row()->site_code;
        $supp               = $get_pengajuan->row()->supp;
        $key_account        = $get_pengajuan->row()->key_account;
        $principal_area_at  = $get_pengajuan->row()->principal_area_at;

        $cek_hak_akses = $this->model_management_inventory->cek_hak_akses($site_code, $supp, $this->session->userdata('id'), 0, $key_account);
        if ($cek_hak_akses->num_rows() > 0) {
            $params_hak_akses = 1;
        }else{
            $params_hak_akses = 0;
        }

        $pic_area_terkait = $this->model_management_inventory->get_pic_area_terkait($site_code, $supp, $key_account);
        if ($pic_area_terkait->num_rows() > 0) {
            $username_pic_terkait = $pic_area_terkait->row()->username;
        }else{
            $username_pic_terkait = "tidak ditemukan. data pic belum di mapping. Harap infokan ini ke administrator retur.";
        }

        $data = [
            'title'                      => 'Pending Principal Area',
            'url'                        => 'management_inventory/principal_area_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'url_proses_mpm'             => 'management_inventory/proses_bridging_mpm',
            'get_pengajuan'              => $get_pengajuan,
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'    => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            'supp'                       => $supp,
            'signature'                  => $signature,
            'principal_area_at'          => $principal_area_at,
            'params_hak_akses'           => $params_hak_akses,
            'username_pic_terkait'       => $username_pic_terkait
        ];

        $this->render_multiple(
            array(
                'management_inventory/accordion',
                'management_inventory/principal_area_new'
            ),
            $data
        );

    }

    public function principal_area_proses()
    {
        $signature = $this->input->post('signature');

        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "update gagal dijalankan !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        // $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        // foreach ($get_pengajuan->result() as $a) {
        //     $id_pengajuan   = $a->id;
        // }

        $supp = $this->input->post('supp');
        $id = $this->input->post('options');
        $status_approval = $this->input->post('status_approval');

        $keterangan_principal_area = $this->input->post("keterangan_principal_area");
        // echo $keterangan_principal_area;

        $data = array();
        $status_approval = $this->input->post('status_approval');

        if ($status_approval == 12) { // approve all

            // $this->db->trans_start();
            foreach ($id as $id_product) {
                $update = "
                    update management_inventory.pengajuan_retur_detail a
                    set a.qty_approval = a.jumlah,
                        a.keterangan_principal_area = '$keterangan_principal_area'
                    where a.id = $id_product and a.deleted is null
                ";

                $proses_update = $this->db->query($update);
            }

        }

        elseif ($status_approval == 13) { // reject all

            foreach ($id as $id_product) {

                $data = [
                    "qty_approval"  => 0,
                    "keterangan_principal_area" => $keterangan_principal_area
                ];

                $this->db->where("id", $id_product);
                $this->db->update("management_inventory.pengajuan_retur_detail", $data);
            }

        }elseif ($status_approval == 11) { // approve partial

            foreach ($id as $id_product) {

                $data = [
                    "qty_approval"  => $this->input->post('qty_approval')[$id_product],
                    "keterangan_principal_area" => $keterangan_principal_area
                ];

                $this->db->where("id", $id_product);
                $this->db->update("management_inventory.pengajuan_retur_detail", $data);
            }
        }
        redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
    }

    public function principal_area_revision($signature, $supp)
    {
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature, "");
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tipe                       = $a->tipe;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Principal Area Revision',
            'url'                        => 'management_inventory/principal_area_revision_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tipe'                       => $tipe,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_inventory/principal_area_revision', $data);
        $this->load->view('kalimantan/footer');

    }

    public function principal_area_revision_proses()
    {
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        $id = $this->input->post('options');

        // echo "signature : ".$signature;
        // echo "supp : ".$supp;
        // var_dump($id);
        // die;

        // $this->db->trans_start();

        foreach ($id as $id_product) {

            if ($this->input->post('status_approval') == 1) {
                $nama_status = 'approve_principal_area';
            }else{
                $nama_status ='not reject_principal_area';
            }

            $data = [
                "status"        => $this->input->post('status_approval'),
                "nama_status"   => $nama_status,
                "deskripsi"     => $this->input->post('deskripsi'),
            ];

            $this->db->where('id', $id_product);
            $this->db->update('management_inventory.pengajuan_retur_detail', $data);
        }

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam verifikasi product retur. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
    }

    public function principal_ho($signature, $supp)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan      = $this->model_management_inventory->get_pengajuan($signature);
        $id_pengajuan       = $get_pengajuan->row()->id;
        $site_code          = $get_pengajuan->row()->site_code;
        $supp               = $get_pengajuan->row()->supp;
        $principal_ho_at    = $get_pengajuan->row()->principal_ho_at;
        $tipe               = $get_pengajuan->row()->tipe;

        $pic_ho_terkait = $this->model_management_inventory->get_pic_ho_terkait($site_code, $supp);
        if ($pic_ho_terkait->num_rows() > 0) {
            $username_pic_terkait = $pic_ho_terkait->row()->username;
        }else{
            $username_pic_terkait = "tidak ditemukan. data pic belum di mapping. Harap infokan ini ke administrator retur.";
        }

        $data = [
            'title'                      => 'Pending Principal HO',
            'url'                        => 'management_inventory/principal_ho_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan'              => $get_pengajuan,
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'    => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            'supp'                       => $supp,
            'signature'                  => $signature,
            'principal_ho_at'            => $principal_ho_at,
            'status_ho'                  => $this->model_management_inventory->get_level_ho($site_code, $supp, '1'),
            'username_pic_terkait'       => $username_pic_terkait,
            'tipe'                       => $tipe
        ];

        $this->render_multiple(
            array(
                'management_inventory/accordion',
                'management_inventory/principal_ho'
            ),
            $data
        );

        // $this->load->view('management_office/top_header', $data);

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/css');
        // $this->load->view('management_inventory/accordion', $data);
        // $this->load->view('management_inventory/principal_ho', $data);
        // $this->load->view('kalimantan/footer');
    }

    public function group_principal_ho()
    {
        $sign_principal_ho_date         = $this->input->post('sign_principal_ho_date');
        $userid_for_group_approval      = $this->input->post('userid_for_group_approval');
        $principal_for_group_approval   = $this->input->post('principal_for_group_approval');

        $data = [
            'url'                            => 'management_inventory/group_principal_ho_proses',
            'get_pengajuan'                  => $this->model_management_inventory->get_pengajuan_group($sign_principal_ho_date, $userid_for_group_approval, $principal_for_group_approval),
            'sign_principal_ho_date'         => $this->input->post('sign_principal_ho_date'),
            'userid_for_group_approval'      => $this->input->post('userid_for_group_approval'),
            'principal_for_group_approval'   => $this->input->post('principal_for_group_approval'),
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_inventory/group_principal_ho', $data);
        $this->load->view('kalimantan/footer');

    }

    public function principal_ho_proses()
    {
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        // update pengajuan retur detail
        $id_detail = $this->input->post('id_detail');
        $qty_approval_ho = $this->input->post('qty_approval_ho');
        $keterangan_principal_ho = $this->input->post('keterangan');

        for ($i=0; $i < count($id_detail); $i++) {
            $data = [
                'qty_approval_ho'           => $qty_approval_ho[$i],
                'keterangan_principal_ho'   => $keterangan_principal_ho[$i],
            ];
            $this->db->where('id', $id_detail[$i]);
            $this->db->update('management_inventory.pengajuan_retur_detail', $data);
        }
        // end update pengajuan retur detail

        // update pengajuan retur
        $file = $this->input->post('file');
        $status_principal_ho = $this->input->post('status_principal_ho');
        if ($status_principal_ho == 14) { // jika approve
            $nama_status_principal_ho = "APPROVE";

            $status = $this->input->post('status');
            if ($status == 5) {
                $nama_status = "PENDING KIRIM BARANG";
            }elseif($status == 7){
                $nama_status = "PENDING PEMUSNAHAN";
            }elseif($status == 11){
                $nama_status = "RETUR SAMPLE";
            }else{
                $status = 10;
                $nama_status = "REJECT PRINCIPAL HO";
            }

        }else{
            // echo "aaa";
            $nama_status_principal_ho = "REJECT";
            $status = 10;
            $nama_status = "REJECT PRINCIPAL HO";
        }

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        // $this->db->trans_start();

        $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
        if (!file_exists($file)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect("management_inventory/principal_ho/$signature/$supp");
            die;
        }

        $data = [
            'status_principal_ho'           => $status_principal_ho,
            'nama_status_principal_ho'      => $nama_status_principal_ho,
            'file_principal_ho'             => $filename,
            'catatan_principal_ho'          => $this->input->post('catatan_principal_ho'),
            'status'                        => $status,
            'nama_status'                   => $nama_status,
            'principal_ho_at'               => $created_at,
            'principal_ho_by'               => $this->session->userdata('id'),
            'principal_ho_signature'        => $this->session->userdata('username').'-signature.png',
            'last_updated'                  => $created_at,
            'last_updated_by'               => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);
        // end pengajuan retur

        $this->email_principal_ho_success($this->input->post('signature'));
        redirect("management_inventory/principal_ho/$signature/$supp");
    }

    public function update_status_pengajuan($signature, $status, $supp){

        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        // cek apakah mpm sudah ada signature
        $cek_mpm_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        if (!file_exists($cek_mpm_signature)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
            die;
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
        }
        // cek apakah ada status yang null
        $query = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id_pengajuan = $id_pengajuan and a.status is null and a.deleted is null and (a.qty_approval is not null and a.qty_approval > 0)
        ";

        $cek = $this->db->query($query);

        if ($cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>masih ditemukan data yang belum di verifikasi. yaitu : ".$cek->row()->kodeprod. " , batch : ".$cek->row()->batch_number. " , outlet : ".$cek->row()->nama_outlet);
            redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
            die;
        }

        if ($status == 1) {
            $nama_status = 'PENDING DP';
            $data = [
                "status"                => $status,
                "nama_status"           => $nama_status,
                "tanggal_pengajuan"     => NULL,
                "verifikasi_signature"  => NULL,
                "verifikasi_at"         => $created_at,
                "verifikasi_by"         => $this->session->userdata('id'),
                'last_updated'          => $created_at,
                'last_updated_by'       => $this->session->userdata('id')
            ];
            $this->db->where('signature', $signature);
            $this->db->update('management_inventory.pengajuan_retur', $data);

            redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
            die;


        }elseif ($status == 3) {

            if ($supp == '005') {
                $status = 4;
                $nama_status = "PENDING PRINCIPAL HO";
            }else{
                $status = 4;
                $nama_status = "PENDING PRINCIPAL HO";
            }
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        // $this->db->trans_start();

        $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
        if (file_exists($file)) {
            $verifikasi_signature = $this->session->userdata('username').'-signature.png';
        }else{
            $verifikasi_signature = '';
        }

        $data = [
            "status"                => $status,
            "nama_status"           => $nama_status,
            "verifikasi_signature"  => $verifikasi_signature,
            "verifikasi_at"         => $created_at,
            "verifikasi_by"         => $this->session->userdata('id'),
            'last_updated'          => $created_at,
            'last_updated_by'       => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);

        // update qty approval
        if ($supp == '002' || $supp == '004' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015' || $supp == '001-herbana') {
            $update_qty_approval = "
                update management_inventory.pengajuan_retur_detail a
                set a.qty_approval = a.jumlah
                where a.id_pengajuan = $id_pengajuan
            ";
            $this->db->query($update_qty_approval);
        }

        if ($supp == '005' || $supp == '001-GT' || $supp == '001-MTI' || $supp == '001-GT-PHARMA' || $supp == "001-RTD") {
            // redirect('management_inventory/email_proses_principal_ho/'.$signature);
            $this->email_proses_principal_ho($signature);
        }else{
            // $this->generate_pdf($signature, $supp, 1);
            // redirect('management_inventory/email_proses_principal_ho/'.$signature);
            $this->email_proses_principal_ho($signature);
        }

        redirect("management_inventory/verifikasi_retur/$signature/$supp");
    }

    public function email_proses_principal_area($signature){
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $site_code = $get_pengajuan->site_code;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $supp = $get_pengajuan->supp;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $get_pengajuan->nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $get_pengajuan->signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'                 => $get_pengajuan_detail_summary->value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
        ];

        $from   = "suffy@muliaputramandiri.com";
        $to     = 'suffy.yanuar@gmail.com';
        $cc     = 'suffy.mpm@gmail.com';
        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email.',linda@muliaputramandiri.com, ilham@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | PROSES PRINCIPAL AREA";
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();
        $message = $this->load->view("management_inventory/email_proses_principal_area",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_proses_principal_area',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $get_pengajuan->nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_proses_principal_area',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);

        redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);

    }

    public function email_proses_principal_area_new($signature)
    {
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $site_code = $get_pengajuan->site_code;
        $supp = $get_pengajuan->supp;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $nama_status = $get_pengajuan->nama_status;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        $get_email  = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp);
        if ($get_email->num_rows() > 0) {
            foreach ($get_email->result() as $a) {
                $email[]    = $a->email;
                $username[] = $a->username;
            }
            $get_email_to       = implode(',', array_unique($email));
            $get_username_to    = implode(',', array_unique($username));
        } else {
            $get_email_to       = '';
            $get_username_to    = '';
        }

        // echo "get_email_to : ".$get_email_to;

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $get_pengajuan->site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $get_pengajuan->supp,
            'signature'                 => $get_pengajuan->signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'                 => $get_pengajuan_detail_summary->value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
            'username_email'            => $get_username_to
        ];

        $from       = "suffy@muliaputramandiri.net";
        $to         = $get_email_to;
        // $email_cc   = $this->model_management_inventory->get_email($site_code)->row()->email . ",linda@muliaputramandiri.com, suffy@muliaputramandiri.com, fakhrul@muliaputramandiri.com, ilham@muliaputramandiri.com";
        $email_cc   = $this->model_management_inventory->get_email($site_code)->row()->email . ",".$this->email_admin;

        // echo "get_email_to : ".$get_email_to;
        // echo "get_username_to : ".$get_username_to;
        // echo "email_cc : ".$email_cc;
        // die;

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $message = $this->load->view("management_inventory/email_proses_principal_area_new",$data,TRUE);
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_proses_principal_area_new',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_proses_principal_area_new',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);
    }

    public function email_proses_mpm($signature)
    {
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan  = $get_pengajuan->id;
        $no_pengajuan  = $get_pengajuan->no_pengajuan;
        $site_code     = $get_pengajuan->site_code;
        $supp          = $get_pengajuan->supp;
        $nama_status   = $get_pengajuan->nama_status;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        if ($supp == '002' || $supp == '004' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015') {
            $get_email_retur = $this->model_retur->get_email_principal($supp);
            foreach ($get_email_retur as $key) {
                $get_email_to = $key->email;
            }
        }else{
            $get_email  = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp);
            if ($get_email->num_rows() > 0) {
                foreach ($get_email->result() as $a) {
                    $email[]    = $a->email;
                    $username[] = $a->username;
                }
                $get_email_to       = implode(',', array_unique($email));
                $get_username_to    = implode(',', array_unique($username));
            } else {
                $get_email_to       = '';
                $get_username_to    = '';
            }
        }

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'                 => $get_pengajuan_detail_summary->value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
            'username_email'            => $get_username_to
        ];

        $from   = "suffy@muliaputramandiri.net";
        $to     = $get_email_to;
        // $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ",linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com, ilham@muliaputramandiri.com";
        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ",".$this->email_admin;

        // echo "get_email_to : ".$get_email_to;
        // echo "get_username_to : ".$get_username_to;
        // echo "email_cc : ".$email_cc;
        // die;

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();
        $message = $this->load->view("management_inventory/email_proses_mpm",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_proses_mpm',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_proses_mpm',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);
    }

    public function email_proses_principal_ho($signature)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $this->load->model('model_retur');

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $site_code = $get_pengajuan->site_code;
        $supp = $get_pengajuan->supp;
        $no_pengajuan = $get_pengajuan->no_pengajuan;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        if ($supp == '002' || $supp == '004' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015' || $supp == '001-herbana') {
            $get_email_retur = $this->model_retur->get_email_principal($supp);
            foreach ($get_email_retur as $key) {
                $email_principal_ho = $key->email;
            }
            $this->generate_pdf($signature, $supp, 1);
            $attach = $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        }else{
            $get_email_principal = $this->model_management_inventory->get_email_ho_to_retur_by_site_code($site_code, $supp);
            if ($get_email_principal->num_rows() > 0) {
                foreach ($get_email_principal->result() as $a) {
                    $email[]    = $a->email;
                    $username[] = $a->username;
                }
                $email_principal_ho       = implode(',', array_unique($email));
                $username_principal_ho    = implode(',', array_unique($username));
            } else {
                $email_principal_ho       = '';
                $username_principal_ho    = '';
            }

            $attach = '';
        }

        // echo "email_principal_ho : ".$email_principal_ho;

        // die;

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail_email_ho($id_pengajuan),
            'get_pengajuan_detail_filter'   => $this->model_management_inventory->get_pengajuan_detail_filter($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $get_pengajuan->nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'                 => $get_pengajuan_detail_summary->value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
        ];

        $from = "suffy@muliaputramandiri.com";
        $to = $email_principal_ho;
        // $cc = 'suffy.mpm@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ', '.$this->email_admin;

        $subject = "MPM SITE | RETUR : $no_pengajuan | PENDING PRINCIPAL HO";
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_proses_principal_ho",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_proses_principal_ho',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $get_pengajuan->nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_proses_principal_ho',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];
        $this->db->insert('site.email_report', $data_email);

    }

    public function email_principal_ho_success($signature){
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $site_code = $get_pengajuan->site_code;
        $supp = $get_pengajuan->supp;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $nama_status = $get_pengajuan->nama_status;

        // echo "principal_area_at : ".$principal_area_at;
        // die;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        $get_email  = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp);
        if ($get_email->num_rows() > 0) {
            foreach ($get_email->result() as $a) {
                $email[]    = $a->email;
                $username[] = $a->username;
            }
            $get_email_cc       = implode(',', array_unique($email));
            $get_username_cc    = implode(',', array_unique($username));
        } else {
            $get_email_cc       = '';
            $get_username_cc    = '';
        }


        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_filter'   => $this->model_management_inventory->get_pengajuan_detail_filter($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'                 => $get_pengajuan_detail_summary->value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'status_principal_ho'       => $get_pengajuan->status_principal_ho,
            'nama_status_principal_ho'  => $get_pengajuan->nama_status_principal_ho,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
        ];

        $from = "suffy@muliaputramandiri.com";
        $email_to = $this->model_management_inventory->get_email($site_code)->row()->email;

        // echo "email_to : ".$email_to;
        // echo "email_cc : ".$get_email_cc;

        // die;

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $message = $this->load->view("management_inventory/email_principal_success",$data,TRUE);
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($email_to);
        $this->email->cc($get_email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_principal_ho_success',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_principal_ho_success',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $email_to,
            'cc' => $get_email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $email_to.','.$get_email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);
    }

    public function email_kirim_barang($signature){
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $supp = $get_pengajuan->supp;
        $site_code = $get_pengajuan->site_code;
        $nama_status = $get_pengajuan->nama_status;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        $get_email  = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp);
        if ($get_email->num_rows() > 0) {
            foreach ($get_email->result() as $a) {
                $email[]    = $a->email;
                $username[] = $a->username;
            }
            $get_email_to       = implode(',', array_unique($email));
            $get_username_to    = implode(',', array_unique($username));
        } else {
            $get_email_to       = '';
            $get_username_to    = '';
        }


        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_filter'      => $this->model_management_inventory->get_pengajuan_detail_filter($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $get_pengajuan->site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'                 => $get_pengajuan_detail_summary->value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'proses_kirim_barang_at'    => $get_pengajuan->proses_kirim_barang_at,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
        ];

        $from = "suffy@muliaputramandiri.com";
        // $to = 'suffy.yanuar@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email;

        // echo "email_to : $get_email_to <br>";
        // echo "email_cc : $email_cc <br>";

        // die;

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_kirim_barang",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($get_email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_kirim_barang',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_kirim_barang',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $get_email_to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $get_email_to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);
    }

    public function email_terima_barang($signature){
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $site_code = $get_pengajuan->site_code;
        $supp = $get_pengajuan->supp;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $terima_barang_by = $get_pengajuan->terima_barang_by;

        // echo "principal_area_at : ".$principal_area_at;
        // die;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        if (!$get_pengajuan_detail_summary->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }else{
            $count_kodeprod = $get_pengajuan_detail_summary->row()->count_kodeprod;
            $value_rbp = $get_pengajuan_detail_summary->row()->value_rbp;
        }

        $get_email_to       = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp);
        if ($get_email_to->num_rows() > 0) {
            foreach ($get_email_to->result() as $a) {
                $email[]    = $a->email;
                $username[] = $a->username;
            }
            $email_to       = implode(',', array_unique($email));
            $username_to    = implode(',', array_unique($username));
        }else{
            $email_to = '';
            $username_to = '';
        }

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_filter'   => $this->model_management_inventory->get_pengajuan_detail_filter($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $get_pengajuan->site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $get_pengajuan->nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'proses_kirim_barang_at'    => $get_pengajuan->proses_kirim_barang_at,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
            'terima_barang_by'          => $terima_barang_by,
        ];

        $from = "suffy@muliaputramandiri.com";
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $get_email_principal_terima_barang = $this->model_management_inventory->get_user($terima_barang_by);
        if ($get_email_principal_terima_barang->num_rows() > 0) {
            $email_principal_terima_barang = ",".$get_email_principal_terima_barang->row()->email;
        }else{
            $email_principal_terima_barang = "";
        }

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ', linda@muliaputramandiri.com, ilham@muliaputramandiri.com'.$email_principal_terima_barang;

        $subject = "MPM SITE | RETUR : $no_pengajuan | BARANG DITERIMA OLEH PRINCIPAL";
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_terima_barang",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($email_cc);
        $this->email->cc($email_to);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();

        // echo "email_principal_terima_barang : ".$email_principal_terima_barang;
        // echo "email_cc : ".$email_cc;
        // echo "get_email_to : ".$get_email_to;
        // die;

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_terima_barang',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $get_pengajuan->nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_terima_barang',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $email_cc,
            'cc' => $email_to,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $email_cc.','.$email_to)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);
    }

    public function kirim_barang($signature, $supp){

        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan          = $this->model_management_inventory->get_pengajuan($signature);
        $id_pengajuan           = $get_pengajuan->row()->id;
        $site_code              = $get_pengajuan->row()->site_code;
        $signature              = $get_pengajuan->row()->signature;

        $data = [
            'title'                     => 'Pending Kirim Barang',
            'url'                       => 'management_inventory/kirim_barang_proses',
            'url_pengajuan'             => 'management_inventory/proses_mpm',
            'get_pengajuan'             => $get_pengajuan,
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'    => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            'site_code'                 => $site_code,
            'supp'                      => $get_pengajuan->row()->supp,
            'signature'                 => $signature,
            'tanggal_kirim_barang'      => $get_pengajuan->row()->tanggal_kirim_barang
        ];

        $this->render_multiple(
            array(
                'management_inventory/accordion',
                'management_inventory/kirim_barang'
            ),
            $data
        );        
    }

    public function kirim_barang_proses(){

        $signature =  $this->input->post('signature');
        $supp =  $this->input->post('supp');
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $file = $this->input->post('file');

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        // $this->db->trans_start();

        $data = [
            'file_pengiriman'         => $filename,
            'tanggal_kirim_barang'    => $this->input->post('tanggal_kirim_barang'),
            'nama_ekspedisi'          => $this->input->post('nama_ekspedisi'),
            'est_tanggal_tiba'        => $this->input->post('est_tanggal_tiba'),
            'status'                  => 6,
            'nama_status'             => "PENDING TERIMA BARANG",
            'proses_kirim_barang_at'  => $created_at,
            'proses_kirim_barang_by'  => $this->session->userdata('id'),
            'last_updated'            => $created_at,
            'last_updated_by'         => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);

        $this->email_kirim_barang($signature);
        redirect("management_inventory/kirim_barang/$signature/$supp");
    }

    public function terima_barang($signature, $supp)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan  = $this->model_management_inventory->get_pengajuan($signature);
        $id_pengajuan   = $get_pengajuan->row()->id;
        $site_code      = $get_pengajuan->row()->site_code;
        $tipe           = $get_pengajuan->row()->tipe;
        
        $get_log_pengajuan = $this->model_management_inventory->get_pengajuan_retur_log($id_pengajuan);

        $data = [
            'title'                 => 'Pending Terima Barang',
            'url'                   => 'management_inventory/terima_barang_proses',
            'url_pengajuan'         => 'management_inventory/proses_mpm',
            'get_pengajuan'         => $get_pengajuan,
            'get_pengajuan_detail'  => $this->model_management_inventory->get_pengajuan_detail_approv($id_pengajuan, $supp, $tipe),
            'get_pengajuan_detail_accordion'    => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            'supp'                  => $supp,
            'signature'             => $signature,
            'status_ho'             => $this->model_management_inventory->get_level_ho($site_code, $supp, ''),
            'tanggal'               => $this->model_outlet_transaksi->timezone(),
            'batas_revisi'          => $get_log_pengajuan->num_rows() == 0 ? $this->model_outlet_transaksi->timezone() : date('Y-m-d', strtotime($get_log_pengajuan->row()->created_at. ' + 14 days'))
        ];


        $this->render_multiple(
            array(
                'management_inventory/accordion',
                'management_inventory/terima_barang'
            ),
            $data
        );   
    }

    public function terima_barang_proses()
    {
        $id_detail = $this->input->post('id_detail');
        $qty_approval_ho = $this->input->post('qty_approval_ho');
        $qty_final = $this->input->post('qty_final');
        $keterangan_final = $this->input->post('keterangan');
        $no_lpk = $this->input->post('nomor_lpk');
        $file = $this->input->post('file');
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');

        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        // cek batas revisi
        $get_pengajuan  = $this->model_management_inventory->get_pengajuan($signature);
        $id_pengajuan   = $get_pengajuan->row()->id;
        $get_log_pengajuan = $this->model_management_inventory->get_pengajuan_retur_log($id_pengajuan);

        $batas_revisi = date('Y-m-d', strtotime($get_log_pengajuan->row()->created_at. ' + 14 days'));

        if (substr($supp, 0, 3) == 001) {
            if ($created_at > $batas_revisi){
                $this->session->set_flashdata("pesan", "Update gagal dijalankan karena sudah melebihi batas revisi 14 Hari");
                redirect("management_inventory/terima_barang/$signature/$supp");
                die;
            }
        }
        // end cek batas revisi

        // update detail pengajuan retur
        for ($i=0; $i < count($id_detail); $i++) {
            $data = [
                'qty_final'         => $qty_final[$i],
                'qty_tolak'         => $qty_approval_ho[$i] - $qty_final[$i],
                'keterangan_final'  => $keterangan_final[$i],
            ];
            $this->db->where('id', $id_detail[$i]);
            $this->db->update('management_inventory.pengajuan_retur_detail', $data);
        }

        // update header pengajuan retur
        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }

        if ($no_lpk == null || $no_lpk == '') {
            $spbr = $this->model_management_inventory->generate_spbr($created_at);
        } else {
            $spbr = $no_lpk;
        }

        $data = [
            'file_terima_barang'     => $filename,
            'tanggal_terima_barang'  => $this->input->post('tanggal_terima_barang'),
            'nama_penerima'          => $this->input->post('nama_penerima'),
            'no_terima_barang'       => $spbr,
            'status'                 => 8,
            'nama_status'            => "BARANG DITERIMA",
            'terima_barang_at'       => $created_at,
            'terima_barang_by'       => $this->session->userdata('id'),
            'last_updated'           => $created_at,
            'last_updated_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);

        $this->log($signature);
        $this->email_terima_barang($signature);
        
        $this->session->set_flashdata("pesan_success", "Update berhasil.");
        redirect("management_inventory/terima_barang/$signature/$supp");
    }

    public function log($signature)
    {
        $get_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature);

        $data_header = [
            'id_pengajuan'  => $get_pengajuan->row()->id,
            'status'        => $get_pengajuan->row()->status,
            'nama_status'   => $get_pengajuan->row()->nama_status,
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $this->session->userdata('id')
        ];

        $this->db->insert('management_inventory.pengajuan_retur_log', $data_header);
        $id_log = $this->db->insert_id();

        $get_pengajuan_detail = $this->model_management_inventory->get_pengajuan_detail($get_pengajuan->row()->id);

        foreach ($get_pengajuan_detail->result() as $key) {
            $data_detail = [
                'id_log'        => $id_log,
                'kodeprod'      => $key->kodeprod,
                'namaprod'      => $key->namaprod,
                'batch_number'  => $key->batch_number,
                'expired_date'  => $key->expired_date,
                'satuan'        => $key->satuan,
                'jumlah'        => $key->jumlah,
                'qty_approval'  => $key->qty_approval,
                'qty_final'     => $key->qty_final,
                'keterangan_final'    => $key->keterangan_final,
                'qty_tolak'     => $key->qty_tolak,
            ];
            $this->db->insert('management_inventory.pengajuan_retur_log_detail', $data_detail);
        }
    }

    public function generate_pdf($signature, $supp, $save_pdf = 0){
        $this->load->library('mypdf');

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tipe                       = $a->tipe;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $company                    = $a->company;
            $digital_signature          = $a->digital_signature;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Generate Pdf',
            'url'                        => 'management_inventory/terima_barang_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_pdf($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tipe'                       => $tipe,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
            'company'                    => $company,
            'digital_signature'          => $digital_signature,
        ];

        $filename_pdf = $no_pengajuan;

        if ($supp == '005') {
            $generate_pdf = $this->mypdf->generate('management_inventory/template_pdf_us',$data,$filename_pdf,'A4','landscape');
        }elseif($supp == '001-GT' || $supp == '001-MTI' || $supp == '001-NKA' || $supp == '001-herbana' || $supp == '001-GT-PHARMA'|| $supp == "001-RTD"){
            $generate_pdf = $this->mypdf->generate('management_inventory/template_pdf_retur_principal_deltomed',$data,$filename_pdf,'A4','landscape');
        }elseif($supp == '002' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015' || $supp == '004'){
            if ($save_pdf == 1) {
                # code...
                $generate_pdf = $this->mypdf->download('management_inventory/template_pdf_universal',$data,str_replace('/','_',$filename_pdf),'A4','landscape');
            } else {
                # code...
                $generate_pdf = $this->mypdf->generate('management_inventory/template_pdf_universal',$data,$filename_pdf,'A4','landscape');
            }
        }
    }

    public function generate_pdf_spbr($signature, $filename)
    {
        $this->load->library('mypdf');
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        
        $cek_revisi = $this->model_management_inventory->get_pengajuan_retur_log($get_pengajuan->row()->id);

        if ($cek_revisi->num_rows() > 1) {
            $revisi = ' (Revisi ' . ($cek_revisi->num_rows()-1) . ')';
        } else {
            $revisi = '';
        }
        
        $data = [
            'title'                      => 'Generate Pdf',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_pdf_persetujuan($get_pengajuan->row()->id),
            'site_code'                  => $get_pengajuan->row()->site_code,
            'no_pengajuan'               => $get_pengajuan->row()->no_pengajuan,
            'tanggal_terima_barang'      => $get_pengajuan->row()->tanggal_terima_barang,
            'no_terima_barang'           => $get_pengajuan->row()->no_terima_barang,
            'company'                    => $get_pengajuan->row()->company,
            'revisi'                     => $revisi
        ];

        $this->mypdf->generate('management_inventory/template_pdf_retur_principal_deltomed_spbr',$data,$filename,'A4','portrait');
    }

    public function generate_pdf_spbr_penolakan($signature, $filename)
    {
        $this->load->library('mypdf');
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);

        $cek_revisi = $this->model_management_inventory->get_pengajuan_retur_log($get_pengajuan->row()->id);

        if ($cek_revisi->num_rows() > 1) {
            $revisi = ' (Revisi ' . ($cek_revisi->num_rows()-1) . ')';
        } else {
            $revisi = '';
        }

        $data = [
            'title'                      => 'Generate Pdf',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_pdf_penolakan($get_pengajuan->row()->id),
            'site_code'                  => $get_pengajuan->row()->site_code,
            'no_pengajuan'               => $get_pengajuan->row()->no_pengajuan,
            'tanggal_terima_barang'      => $get_pengajuan->row()->tanggal_terima_barang,
            'no_terima_barang'           => $get_pengajuan->row()->no_terima_barang,
            'company'                    => $get_pengajuan->row()->company,
            'revisi'                     => $revisi
        ];

        $this->mypdf->generate('management_inventory/template_pdf_retur_principal_deltomed_spbr_penolakan',$data,$filename,'A4','portrait');
    }

    public function generate_pdf_spbr_group_kodeprod($signature, $filename)
    {
        $this->load->library('mypdf');
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        
        $cek_revisi = $this->model_management_inventory->get_pengajuan_retur_log($get_pengajuan->row()->id);

        if ($cek_revisi->num_rows() > 1) {
            $revisi = ' (Revisi ' . ($cek_revisi->num_rows()-1) . ')';
        } else {
            $revisi = '';
        }

        $data = [
            'title'                 => 'Generate Pdf',
            'get_pengajuan_detail'  => $this->model_management_inventory->get_pengajuan_detail_pdf_persetujuan_group_kodeprod($get_pengajuan->row()->id),
            'site_code'             => $get_pengajuan->row()->site_code,
            'no_pengajuan'          => $get_pengajuan->row()->no_pengajuan,
            'tanggal_terima_barang' => $get_pengajuan->row()->tanggal_terima_barang,
            'no_terima_barang'      => $get_pengajuan->row()->no_terima_barang,
            'company'               => $get_pengajuan->row()->company,
            'revisi'                => $revisi
        ];

        $this->mypdf->generate('management_inventory/template_pdf_retur_principal_deltomed_spbr_group_kodeprod',$data,$filename,'A4','portrait');
    }

    public function generate_pdf_spbr_penolakan_group_kodeprod($signature, $filename)
    {
        $this->load->library('mypdf');
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        
        $cek_revisi = $this->model_management_inventory->get_pengajuan_retur_log($get_pengajuan->row()->id);

        if ($cek_revisi->num_rows() > 1) {
            $revisi = ' (Revisi ' . ($cek_revisi->num_rows()-1) . ')';
        } else {
            $revisi = '';
        }
        
        $data = [
            'title'                      => 'Generate Pdf',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_pdf_penolakan_group_kodeprod($get_pengajuan->row()->id),
            'site_code'                  => $get_pengajuan->row()->site_code,
            'no_pengajuan'               => $get_pengajuan->row()->no_pengajuan,
            'tanggal_terima_barang'      => $get_pengajuan->row()->tanggal_terima_barang,
            'no_terima_barang'           => $get_pengajuan->row()->no_terima_barang,
            'company'                    => $get_pengajuan->row()->company,
            'revisi'                     => $revisi
        ];

        $this->mypdf->generate('management_inventory/template_pdf_retur_principal_deltomed_spbr_penolakan_group_kodeprod',$data,$filename,'A4','portrait');
    }

    public function pemusnahan($signature, $supp)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan  = $this->model_management_inventory->get_pengajuan($signature);
        if (!$get_pengajuan->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }
        
        $id_pengajuan   = $get_pengajuan->row()->id;
        $supp           = $get_pengajuan->row()->supp;
        $tipe           = $get_pengajuan->row()->tipe;

        $data = [
            'title'                      => 'Pending Pemusnahan',
            'url'                        => 'management_inventory/pemusnahan_proses',
            'url_berita_acara'           => "management_inventory/update_qty_pemusnahan/$signature/$supp",
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan'              => $get_pengajuan,
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_approv($id_pengajuan, $supp, $tipe),
            'get_pengajuan_detail_accordion'    => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            'site_code'                  => $get_pengajuan->row()->site_code,
            'supp'                       => $supp,
            'signature'                  => $signature,
            'tanggal_pemusnahan'         => $get_pengajuan->row()->tanggal_pemusnahan,
            'pemusnahan_at'              => $get_pengajuan->row()->pemusnahan_at,
        ];

        $this->render_multiple(
            array(
                'management_inventory/accordion',
                'management_inventory/pemusnahan'
            ),
            $data
        );
    }

    public function update_qty_pemusnahan($signature, $supp)
    {
        $id_detail              = $this->input->post('id_detail');
        $qty_approval_ho        = $this->input->post('qty_approval_ho');
        $qty_pemusnahan         = $this->input->post('qty_pemusnahan');
        $keterangan_pemusnahan  = $this->input->post('keterangan_pemusnahan');
        $tanggal_pemusnahan     = $this->input->post('tanggal_pemusnahan');

        // update pengajuan retur
        
        $data = [
            'tanggal_pemusnahan'    => $tanggal_pemusnahan,
            'nama_pemusnahan'       => $this->input->post('nama_pemusnahan'),
            'no_pemusnahan'         => $this->model_management_inventory->generate_berita_acara($tanggal_pemusnahan, $signature),
            'last_updated'          => $this->created_at,
            'last_updated_by'       => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);

        // update detail pengajuan retur
        if (substr($supp, 0, 3) == 001) {
            for ($i=0; $i < count($id_detail); $i++) {
                $data = [
                    'qty_pemusnahan'    => $qty_pemusnahan[$i],
                    'keterangan_pemusnahan'    => $keterangan_pemusnahan[$i],
                ];
                $this->db->where('id', $id_detail[$i]);
                $this->db->update('management_inventory.pengajuan_retur_detail', $data);
            }
        } else {
            for ($i=0; $i < count($id_detail); $i++) {
                $data = [
                    'qty_pemusnahan'    => $qty_pemusnahan[$i],
                    'keterangan_pemusnahan' => $keterangan_pemusnahan[$i],
                    'qty_final'         => $qty_pemusnahan[$i],
                    'keterangan_final'  => $keterangan_pemusnahan[$i],
                    'qty_tolak'         => $qty_approval_ho[$i] - $qty_pemusnahan[$i],
                ];
                $this->db->where('id', $id_detail[$i]);
                $this->db->update('management_inventory.pengajuan_retur_detail', $data);
            }
        }
        $this->session->set_flashdata("pesan_success", "Berita acara berhasil dibuat");
        redirect("management_inventory/pemusnahan/$signature/$supp");
    }

    public function generate_pdf_berita_acara($signature)
    {
        $this->load->library('mypdf');
        $get_pengajuan  = $this->model_management_inventory->get_pengajuan($signature);
        $filename       = 'BAP_'.str_replace('/', '', $get_pengajuan->row()->no_pengajuan);
        
        $data = [
            'title'                      => 'Generate Pdf',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_pdf($get_pengajuan->row()->id),
            'no_pengajuan'               => $get_pengajuan->row()->no_pengajuan,
            'no_pemusnahan'              => $get_pengajuan->row()->no_pemusnahan,
            'company'                    => $get_pengajuan->row()->company,
            'tanggal_pemusnahan'         => $get_pengajuan->row()->tanggal_pemusnahan,
            'nama_pemusnahan'            => $get_pengajuan->row()->nama_pemusnahan,
        ];

        $this->mypdf->generate('management_inventory/template_pdf_berita_acara',$data,$filename,'A4','portrait');
    }

    public function pemusnahan_proses()
    {
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $no_pemusnahan = $get_pengajuan_by_signature->row()->no_pemusnahan;
        $nama_pemusnahan = $get_pengajuan_by_signature->row()->nama_pemusnahan;
        if ($no_pemusnahan == null || $nama_pemusnahan == null) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan generate berita acara terlebih dahulu !!");
            redirect("management_inventory/pemusnahan/$signature/$supp");
            die;
        }

        $file = $this->input->post('file');

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_pemusnahan')) {
            $filename_berita_acara = '';
            var_dump($this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename_berita_acara = $upload_data['file_name'];
        }

        // echo "filename_berita_acara : ".$filename_berita_acara;
        // die;

        if (!$this->upload->do_upload('foto_pemusnahan_1')) {
            $filename_foto_1 = '';
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename_foto_1 = $upload_data['file_name'];
        }

        if (!$this->upload->do_upload('foto_pemusnahan_2')) {
            $filename_foto_2 = '';
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename_foto_2 = $upload_data['file_name'];
        }

        if (!$this->upload->do_upload('video')) {
            $filename_video = '';
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename_video = $upload_data['file_name'];
        }

        $data = [
            'file_pemusnahan'       => $filename_berita_acara,
            'foto_pemusnahan_1'     => $filename_foto_1,
            'foto_pemusnahan_2'     => $filename_foto_2,
            'video'                 => $filename_video,
            'status'                => 9,
            'nama_status'           => "PEMUSNAHAN OLEH DP",
            'pemusnahan_at'         => $this->model_outlet_transaksi->timezone(),
            'pemusnahan_by'         => $this->session->userdata('id'),
            'last_updated'          => $this->model_outlet_transaksi->timezone(),
            'last_updated_by'       => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);

        $this->email_pemusnahan($signature);
        redirect("management_inventory/pemusnahan/$signature/$supp");
    }

    public function email_pemusnahan($signature)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $supp = $get_pengajuan->supp;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $site_code = $get_pengajuan->site_code;
        $nama_status = $get_pengajuan->nama_status;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        // echo "supp : ".$supp;
        if ($supp == '002' || $supp == '004' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015') {
            $get_email_retur = $this->model_retur->get_email_principal($supp);
            foreach ($get_email_retur as $key) {
                $email_to = $key->email;
            }
            // $attach = $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        } else {
            $get_email_to       = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp);
            if ($get_email_to->num_rows() > 0) {
                foreach ($get_email_to->result() as $a) {
                    $email[]    = $a->email;
                    $username[] = $a->username;
                }
                $email_to       = implode(',', array_unique($email));
            }else{
                $email_to = '';
            }
        }

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'tipe'                      => $get_pengajuan->tipe,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $get_pengajuan->site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'                 => $get_pengajuan_detail_summary->value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'proses_kirim_barang_at'    => $get_pengajuan->proses_kirim_barang_at,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
            'tanggal_pemusnahan'        => $get_pengajuan->tanggal_pemusnahan,
            'nama_pemusnahan'           => $get_pengajuan->nama_pemusnahan,
            'file_pemusnahan'           => $get_pengajuan->file_pemusnahan,
            'foto_pemusnahan_1'         => $get_pengajuan->foto_pemusnahan_1,
            'foto_pemusnahan_2'         => $get_pengajuan->foto_pemusnahan_2,
        ];

        $from = "suffy@muliaputramandiri.com";
        $to =  $email_to;
        $cc = 'suffy.mpm@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email.' ,linda@muliaputramandiri.com, ilham@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_pemusnahan",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_pemusnahan',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_pemusnahan',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);

    }

    public function validasi_pemusnahan($signature, $supp)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan  = $this->model_management_inventory->get_pengajuan($signature);
        $id_pengajuan   = $get_pengajuan->row()->id;
        $site_code      = $get_pengajuan->row()->site_code;
        $supp           = $get_pengajuan->row()->supp;
        $tipe           = $get_pengajuan->row()->tipe;

        $data = [
            'title'                      => 'Validasi Pemusnahan',
            'url'                        => 'management_inventory/validasi_pemusnahan_proses',
            'get_pengajuan'              => $get_pengajuan,
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_approv($id_pengajuan, $supp, $tipe),
            'get_pengajuan_detail_accordion'    => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            'site_code'                  => $site_code,
            'supp'                       => $supp,
            'status_ho'                  => $this->model_management_inventory->get_level_ho($site_code, $supp, ''),
            'signature'                  => $signature,
        ];

        // $this->load->view('management_office/top_header', $data);
        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/css');
        // $this->load->view('management_inventory/accordion', $data);
        // $this->load->view('management_inventory/validasi_pemusnahan', $data);
        // $this->load->view('kalimantan/footer');
        $this->render_multiple(
            array(
                'management_inventory/accordion',
                'management_inventory/validasi_pemusnahan'
            ),
            $data
        );
    }

    public function validasi_pemusnahan_proses()
    {
        $id_detail = $this->input->post('id_detail');
        $qty_pemusnahan = $this->input->post('qty_pemusnahan');
        $qty_final = $this->input->post('qty_final');
        $keterangan_final = $this->input->post('keterangan_final');
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');

        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        // update detail pengajuan retur
        for ($i=0; $i < count($id_detail); $i++) {
            $data = [
                'qty_final'           => $qty_final[$i],
                'qty_tolak'         => $qty_pemusnahan[$i] - $qty_final[$i],
                'keterangan_final'    => $keterangan_final[$i],
            ];
            $this->db->where('id', $id_detail[$i]);
            $this->db->update('management_inventory.pengajuan_retur_detail', $data);
        }

        $data = [
            'no_terima_barang'       => $this->model_management_inventory->generate_spbr($created_at),
            'status'                 => 12,
            'nama_status'            => "PEMUSNAHAN TERVALIDASI",
            'validasi_pemusnahan_at'    => $created_at,
            'validasi_pemusnahan_by'    => $this->session->userdata('id'),
            'last_updated'           => $created_at,
            'last_updated_by'        => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);

        $this->email_validasi_pemusnahan($signature);
        
        // $this->session->set_flashdata("pesan_success", "Update berhasil, SPBR Pemusnahan berhasil dibuat");
        redirect("management_inventory/validasi_pemusnahan/$signature/$supp");
    }
    
    public function generate_pdf_spbr_pemusnahan($signature, $filename)
    {
        $this->load->library('mypdf');
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        
        $cek_revisi = $this->model_management_inventory->get_pengajuan_retur_log($get_pengajuan->row()->id);

        if ($cek_revisi->num_rows() > 1) {
            $revisi = ' (Revisi ' . ($cek_revisi->num_rows()-1) . ')';
        } else {
            $revisi = '';
        }
        
        $data = [
            'title'                      => 'Generate Pdf',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_pdf_pemusnahan($get_pengajuan->row()->id),
            'site_code'                  => $get_pengajuan->row()->site_code,
            'nama_comp'                  => $get_pengajuan->row()->nama_comp,
            'no_pengajuan'               => $get_pengajuan->row()->no_pengajuan,
            'no_terima_barang'           => $get_pengajuan->row()->no_terima_barang,
            'company'                    => $get_pengajuan->row()->company,
            'tanggal'                    => $get_pengajuan->row()->validasi_pemusnahan_at,
            'revisi'                     => $revisi
        ];

        $this->mypdf->generate('management_inventory/template_pdf_retur_principal_deltomed_spbr_pemusnahan',$data,$filename,'A4','portrait');
    }

    public function generate_pdf_spbr_pemusnahan_group_kodeprod($signature, $filename)
    {
        $this->load->library('mypdf');
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        
        $cek_revisi = $this->model_management_inventory->get_pengajuan_retur_log($get_pengajuan->row()->id);

        if ($cek_revisi->num_rows() > 1) {
            $revisi = ' (Revisi ' . ($cek_revisi->num_rows()-1) . ')';
        } else {
            $revisi = '';
        }

        $data = [
            'title'                 => 'Generate Pdf',
            'get_pengajuan_detail'  => $this->model_management_inventory->get_pengajuan_detail_pdf_pemusnahan_group_kodeprod($get_pengajuan->row()->id),
            'site_code'             => $get_pengajuan->row()->site_code,
            'nama_comp'             => $get_pengajuan->row()->nama_comp,
            'no_pengajuan'          => $get_pengajuan->row()->no_pengajuan,
            'no_terima_barang'      => $get_pengajuan->row()->no_terima_barang,
            'company'               => $get_pengajuan->row()->company,
            'tanggal'               => $get_pengajuan->row()->validasi_pemusnahan_at,
            'revisi'                => $revisi
        ];

        $this->mypdf->generate('management_inventory/template_pdf_retur_principal_deltomed_spbr_pemusnahan_group_kodeprod',$data,$filename,'A4','portrait');
    }

    public function email_validasi_pemusnahan($signature)
    {
        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $supp = $get_pengajuan->supp;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $site_code = $get_pengajuan->site_code;
        $nama_status = $get_pengajuan->nama_status;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        // echo "supp : ".$supp;
        if ($supp == '002' || $supp == '004' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015') {
            $get_email_retur = $this->model_retur->get_email_principal($supp);
            foreach ($get_email_retur as $key) {
                $get_email_to = $key->email;
            }
            // $attach = $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        }

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'tipe'                      => $get_pengajuan->tipe,
            'branch_name'               => $get_pengajuan->branch_name,
            'nama_comp'                 => $get_pengajuan->nama_comp,
            'site_code'                 => $get_pengajuan->site_code,
            'namasupp'                  => $get_pengajuan->namasupp,
            'tanggal_pengajuan'         => $get_pengajuan->tanggal_pengajuan,
            'nama'                      => $get_pengajuan->nama,
            'status'                    => $get_pengajuan->status,
            'nama_status'               => $nama_status,
            'created_by'                => $get_pengajuan->created_by,
            'file'                      => $get_pengajuan->file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $get_pengajuan->verifikasi_at,
            'verifikasi_username'       => $get_pengajuan->verifikasi_username,
            'count_kodeprod'            => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'                 => $get_pengajuan_detail_summary->value_rbp,
            'principal_area_at'         => $get_pengajuan->principal_area_at,
            'principal_area_signature'  => $get_pengajuan->principal_area_signature,
            'principal_area_username'   => $get_pengajuan->principal_area_username,
            'file_principal_area'       => $get_pengajuan->file_principal_area,
            'catatan_principal_area'    => $get_pengajuan->catatan_principal_area,
            'principal_ho_at'           => $get_pengajuan->principal_ho_at,
            'principal_ho_signature'    => $get_pengajuan->principal_ho_signature,
            'principal_ho_username'     => $get_pengajuan->principal_ho_username,
            'file_principal_ho'         => $get_pengajuan->file_principal_ho,
            'catatan_principal_ho'      => $get_pengajuan->catatan_principal_ho,
            'tanggal_kirim_barang'      => $get_pengajuan->tanggal_kirim_barang,
            'nama_ekspedisi'            => $get_pengajuan->nama_ekspedisi,
            'est_tanggal_tiba'          => $get_pengajuan->est_tanggal_tiba,
            'file_pengiriman'           => $get_pengajuan->file_pengiriman,
            'proses_kirim_barang_at'    => $get_pengajuan->proses_kirim_barang_at,
            'username_kirim_barang'     => $get_pengajuan->username_kirim_barang,
            'tanggal_terima_barang'     => $get_pengajuan->tanggal_terima_barang,
            'nama_penerima'             => $get_pengajuan->nama_penerima,
            'no_terima_barang'          => $get_pengajuan->no_terima_barang,
            'file_terima_barang'        => $get_pengajuan->file_terima_barang,
            'terima_barang_at'          => $get_pengajuan->terima_barang_at,
            'username_terima_barang'    => $get_pengajuan->username_terima_barang,
            'tanggal_pemusnahan'        => $get_pengajuan->tanggal_pemusnahan,
            'nama_pemusnahan'           => $get_pengajuan->nama_pemusnahan,
            'file_pemusnahan'           => $get_pengajuan->file_pemusnahan,
            'foto_pemusnahan_1'         => $get_pengajuan->foto_pemusnahan_1,
            'foto_pemusnahan_2'         => $get_pengajuan->foto_pemusnahan_2,
            'username_validasi_pemusnahan'  => $get_pengajuan->username_validasi_pemusnahan,
            'validasi_pemusnahan_at'    => $get_pengajuan->validasi_pemusnahan_at
        ];

        $from = "suffy@muliaputramandiri.com";
        $to =  $get_email_to;
        $cc = 'suffy.mpm@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email.' ,linda@muliaputramandiri.com, ilham@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_validasi_pemusnahan",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. SPBR Pemusnahan berhasil dibuat Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.. SPBR Pemusnahan berhasil dibuat Terima kasih");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_pemusnahan',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_pemusnahan',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);

    }

    public function routing($signature)
    {

        $status = $this->model_management_inventory->cek_status($signature)->row()->status;
        $supp = $this->model_management_inventory->cek_status($signature)->row()->supp;
        $suppx = substr($supp,0,3);
        $no_pengajuan = $this->model_management_inventory->cek_status($signature)->row()->no_pengajuan;

        // echo "status : ".$status;
        // echo "supp : ".$supp;
        // echo "no_pengajuan : ".$no_pengajuan;
        // die;

        if ($status == 1) {

            if ($this->session->userdata('level') == 3) {
                $this->session->set_flashdata("pesan", "Ajuan Retur ini belum diselesaikan oleh DP");
                redirect('management_inventory');
                die;
            }
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }elseif ($status == 2) { // proses mpm
            if ($this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'suffy'|| $this->session->userdata('username') == 'melinda') {
                redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
            }
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }elseif ($status == 3) { // principal area

            if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5) { // jika yg login adalah DP
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
            }

        }elseif ($status == 4) { // proses principal ho

            if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5) { // jika yg login adalah DP
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/principal_ho/'.$signature.'/'.$supp);
            }

        }elseif ($status == 5) {
            redirect('management_inventory/kirim_barang/'.$signature.'/'.$supp);
        }elseif ($status == 6) {

            if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5) {
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/terima_barang/'.$signature.'/'.$supp);
            }

        }elseif ($status == 7) { // pending pemusnahan
            if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5 || $this->session->userdata('level') == 5) {
                redirect('management_inventory/pemusnahan/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }
        }elseif ($status == 8) {
            if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5) {
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/terima_barang/'.$signature.'/'.$supp);
            }
        }elseif ($status == 9) {
            if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5) {
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                if ($suppx == 001) {
                    redirect('management_inventory/validasi_pemusnahan/'.$signature.'/'.$supp);
                } else {
                    redirect('management_inventory/pemusnahan/'.$signature.'/'.$supp);
                }
            }

        }elseif ($status == 10) {
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }elseif ($status == 11) { // proses principal ho

            if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 5) { // jika yg login adalah DP
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/principal_ho/'.$signature.'/'.$supp);
            }
        }elseif ($status == 12) {
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }elseif ($status == 13) { // proses reject
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }
    }

    public function signature_digital()
    {
        $data = [
            'title'           => 'Digital Signature',
            'get_pengajuan'   => $this->model_management_inventory->get_pengajuan(),
            'url'             => 'management_inventory/signature_digital_proses',
            'site_code'       => $this->model_management_inventory->get_sitecode(),
        ];

        // $this->load->view('management_office/top_header', $data);
        // $this->navbar($data);
        // $this->load->view('template_claim/top_header_signature');
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_inventory/signature_digital', $data);
        // $this->load->view('kalimantan/footer');

        $this->render_multiple(
          array(
            'template_claim/top_header_signature',
            'management_inventory/signature_digital',
          ),
          $data
        );
    }


    public function signature_digital_proses()
    {

        $folderPath = './assets/uploads/signature/';
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $file = $folderPath . $this->session->userdata('username') . '-signature.' .$image_type;
        file_put_contents($file, $image_base64);
        redirect('management_inventory/signature_digital', 'refresh');

    }

    public function export_report($status)
    {

        $query="
            select 	a.status, a.nama_status, a.no_pengajuan, a.site_code, d.branch_name, d.nama_comp, a.nama, a.supp, a.tanggal_pengajuan,
                    b.kodeprod, c.namaprod, b.jumlah
            from management_inventory.pengajuan_retur a LEFT JOIN
            (
                select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah
                from management_inventory.pengajuan_retur_detail a
            )b on a.id = id_pengajuan LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )c on b.kodeprod = c.kodeprod LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code
            where a.status = $status
        ";
        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Export.csv');

    }

    public function export_report_site($site_code)
    {

        $query="
            select 	a.status, a.nama_status, a.no_pengajuan, a.site_code, d.branch_name, d.nama_comp, a.nama, a.supp, a.tanggal_pengajuan,
                    b.kodeprod, c.namaprod, b.jumlah
            from management_inventory.pengajuan_retur a LEFT JOIN
            (
                select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah
                from management_inventory.pengajuan_retur_detail a
            )b on a.id = id_pengajuan LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )c on b.kodeprod = c.kodeprod LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code
            where a.site_code = '$site_code'
        ";
        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Export.csv');

    }

    public function export_by_signature($signature)
    {
        $no_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row()->no_pengajuan;
        if ($no_pengajuan) {
            $params_no_pengajuan = $no_pengajuan ;
        }else{
            $params_no_pengajuan = "draft";
        }

        $query = "
        select 	a.nama_status, a.no_pengajuan, a.site_code, d.branch_name, d.nama_comp, a.nama,
                e.namasupp, a.tanggal_pengajuan,
                b.kodeprod, c.namaprod,
                b.batch_number, b.expired_date, b.nama_outlet, b.alasan, b.jumlah,
                b.qty_approval, b.qty_approval_ho, b.satuan,
                a.principal_area_at, f.username as principal_area_name, f.email as principal_area_email,
                a.verifikasi_at, g.username as mpm_name, g.email as mpm_email,
                a.principal_ho_at, h.username as principal_ho_name, h.email as principal_ho_email,
                a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.proses_kirim_barang_at,
                a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.terima_barang_at,
                a.tanggal_pemusnahan, a.pemusnahan_at
        from management_inventory.pengajuan_retur a INNER JOIN
        (
            select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah, a.satuan, a.qty_approval, a.qty_approval_ho, a.batch_number, a.expired_date, a.nama_outlet, a.alasan
            from management_inventory.pengajuan_retur_detail a
            where a.deleted is null
        )b on a.id = id_pengajuan LEFT JOIN
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )c on b.kodeprod = c.kodeprod LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on a.site_code = d.site_code LEFT JOIN (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )e on a.supp = e.supp LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )f on a.principal_area_by = f.id LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )g on a.verifikasi_by = g.id LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )h on a.principal_ho_by = h.id
        where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,"$params_no_pengajuan.csv");
    }

    public function export_sortir_by_signature($signature)
    {

        $no_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row()->no_pengajuan;
        // echo $no_pengajuan;
        // die;

        $query = "
        select 	a.nama_status, a.no_pengajuan, a.site_code, d.branch_name, d.nama_comp, a.nama,
                e.namasupp, a.tanggal_pengajuan,
                b.kodeprod, c.namaprod, b.qty_approval, b.qty_approval_ho, b.satuan,
                a.principal_area_at, f.username as principal_area_name, f.email as principal_area_email,
                a.verifikasi_at, g.username as mpm_name, g.email as mpm_email,
                a.principal_ho_at, h.username as principal_ho_name, h.email as principal_ho_email,
                a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.proses_kirim_barang_at,
                a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.terima_barang_at,
                a.tanggal_pemusnahan, a.pemusnahan_at
        from management_inventory.pengajuan_retur a INNER JOIN
        (
            select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah, a.satuan, a.qty_approval, a.qty_approval_ho
            from management_inventory.pengajuan_retur_detail a
            where a.deleted is null and a.qty_approval > 0 and a.status = 3
        )b on a.id = id_pengajuan LEFT JOIN
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )c on b.kodeprod = c.kodeprod LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on a.site_code = d.site_code LEFT JOIN (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )e on a.supp = e.supp LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )f on a.principal_area_by = f.id LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )g on a.verifikasi_by = g.id LEFT JOIN
        (
            select a.id, a.username, a.email
            from mpm.user a
        )h on a.principal_ho_by = h.id
        where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,"$no_pengajuan.csv");
    }

    public function export_template_pengajuan_retur()
    {
        $query = "
            select '' as kodeprod, '' as batch_number, '' as expired_date, '' as jumlah, '' as nama_outlet, '' as alasan_retur, '' as keterangan
        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'kodeprod', 'batch_number', 'expired_date(m/d/y)', 'jumlah', 'nama_outlet', 'alasan_retur', 'keterangan'
        ));
        $this->excel_generator->set_column(array
        (
            'kodeprod', 'batch_number', 'expired_date', 'jumlah', 'nama_outlet', 'alasan_retur', 'keterangan'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10));
        $this->excel_generator->exportTo2007('Template Pengajuan Retur 2023');
    }

    public function pengajuan_retur_detail_import()
    {
        $this->load->library('session');

        $signature_ajuan = $this->input->post('signature');
        $supp = $this->input->post('supp');

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature_ajuan);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan = $a->id;
            $supp = $a->supp;
            // $signature = $a->signature;
            $created_at = $a->created_at;
        }

        if (!is_dir('./assets/uploads/retur/import/')) {
            @mkdir('./assets/uploads/retur/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/retur/import/';
        $config['allowed_types'] = 'xls|xlsx';
        // $config['allowed_types'] = '*';
        $config['max_size']  = '2048';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/retur/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('mes','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 50) {
                    $this->session->set_flashdata("pesan", "Import Gagal karena data excel sudah melebihi batas 50 rows !");
                    redirect('management_inventory/pengajuan_retur_detail/'.$signature_ajuan.'/'.$supp);
                } else {
                    for ($row = 2; $row <= $highestRow; $row++) {

                        $kodeprod = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                        $batch_number = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                        $expired_date = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                        $unix_date = ($expired_date - 25569) * 86400;
                        $excel_date = 25569 + ($unix_date / 86400);
                        $unix_date = ($excel_date - 25569) * 86400;
                        $expired_date_final = gmdate("Y-m-d", $unix_date);

                        $jumlah = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                        $nama_outlet = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                        $alasan_retur = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                        $keterangan = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                        // echo "kodeprod : ".$kodeprod;
                        // die;

                        if(strlen("$kodeprod") == '5')
                        {
                            $kodeprodx = '0'.$kodeprod;
                        }else{
                            $kodeprodx = $kodeprod;
                        }

                        $satuan = trim($this->model_management_inventory->get_product($kodeprodx)->row()->kecil);

                        $signature_detail = 'RTR-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

                        $data = [
                            'id_pengajuan'      => $id_pengajuan,
                            'kodeprod'          => $kodeprodx,
                            'batch_number'      => $batch_number,
                            'expired_date'      => $expired_date_final,
                            'jumlah'            => $jumlah,
                            'alasan'            => $alasan_retur,
                            'nama_outlet'       => $nama_outlet,
                            'keterangan'        => $keterangan,
                            'satuan'            => $satuan,
                            // 'supp'              => $supp,
                            'filename'          => $filename,
                            'created_at'        => $created_at,
                            'created_by'        => $this->session->userdata('id'),
                            'signature'         => $signature_detail
                        ];

                        // cek kodeproduk berdasarkan supp
                        if ($supp == "001-RTD") {
                            $cek_produk = $this->model_management_inventory->get_product_rtd($kodeprodx);
                            if ($cek_produk->num_rows() > 0) {
                                $this->db->insert('management_inventory.pengajuan_retur_detail',$data);
                            }
                        }else{
                            $this->db->insert('management_inventory.pengajuan_retur_detail',$data);
                        }
                    }
                }
            }

            $this->session->set_flashdata("pesan_success", "Import Success");

        }else{
            $this->session->set_flashdata("pesan", "Import Gagal :".$this->upload->display_errors());
        };

        redirect('management_inventory/pengajuan_retur_detail/'.$signature_ajuan.'/'.$supp);
    }

    public function preview_pengajuan_retur()
    {
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($this->input->post('signature'));
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tipe                       = $a->tipe;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Pengajuan Retur - Preview',
            'url'                        => 'management_inventory/pengajuan_retur_detail_proses',
            'url_import'                 => 'management_inventory/pengajuan_retur_detail_import',
            // 'url_pengajuan'              => 'management_inventory/proses_mpm',
            'url_pengajuan'              => 'management_inventory/preview_pengajuan_retur',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'id_pengajuan'               => $id_pengajuan,
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tipe'                       => $tipe,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
        ];

        $this->navbar($data);
        $this->load->view('management_inventory/preview_pengajuan_retur', $data);
        $this->load->view('kalimantan/footer');


    }

    public function proses_bridging_mpm()
    {
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');

        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Update gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $created_at = $this->model_outlet_transaksi->timezone();

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
        }

        // cek apakah principal area sudah ada signature
        $cek_principal_area_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        if (!file_exists($cek_principal_area_signature)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
            die;
        }

        // cek apakah ada qty approval yang null
        $query = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id_pengajuan = $id_pengajuan and a.qty_approval is null and a.deleted is null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $cek = $this->db->query($query);

        // echo $cek->num_rows();
        // die;

        if ($cek->num_rows() > 0) {

            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>QTY approval masih ditemukan qty approval NULL. yaitu : ".$cek->row()->kodeprod. " , batch : ".$cek->row()->batch_number. " , outlet : ".$cek->row()->nama_outlet);
            redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
            die;

        }else{

            $data = [
                "status"                    => 2,
                "nama_status"               => "PENDING MPM",
                "principal_area_signature"  => $this->session->userdata('username').'-signature.png',
                "principal_area_at"         => $created_at,
                "principal_area_by"         => $this->session->userdata('id'),
                'last_updated'              => $created_at,
                'last_updated_by'           => $this->session->userdata('id')
            ];
            $this->db->where("signature", $signature);
            $this->db->update("management_inventory.pengajuan_retur", $data);

            // $this->session->set_flashdata("pesan_success", "Proses Success. Terima kasih");
        }

        $this->email_pengajuan_new($signature);
        redirect("management_inventory/principal_area/$signature/$supp");


    }

    public function email_pengajuan_new($signature)
    {
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row();
        $id_pengajuan = $get_pengajuan->id;
        $no_pengajuan = $get_pengajuan->no_pengajuan;
        $supp = $get_pengajuan->supp;
        $site_code = $get_pengajuan->site_code;
        $nama_status = $get_pengajuan->nama_status;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan)->row();

        $get_email  = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp);
        if ($get_email->num_rows() > 0) {
            foreach ($get_email->result() as $a) {
                $email[]    = $a->email;
                $username[] = $a->username;
            }
            $get_email_to       = implode(',', array_unique($email));
            $get_username_to    = implode(',', array_unique($username));
        } else {
            $get_email_to       = '';
            $get_username_to    = '';
        }

        $data = [
            'get_pengajuan_detail'  => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'          => $no_pengajuan,
            'branch_name'           => $get_pengajuan->branch_name,
            'nama_comp'             => $get_pengajuan->nama_comp,
            'site_code'             => $get_pengajuan->site_code,
            'namasupp'              => $get_pengajuan->namasupp,
            'tanggal_pengajuan'     => $get_pengajuan->tanggal_pengajuan,
            'nama'                  => $get_pengajuan->nama,
            'status'                => $get_pengajuan->status,
            'nama_status'           => $nama_status,
            // 'created_by'            => $created_by,
            'file'                  => $get_pengajuan->file,
            'id_pengajuan'          => $id_pengajuan,
            'supp'                  => $supp,
            'signature'             => $signature,
            'count_kodeprod'        => $get_pengajuan_detail_summary->count_kodeprod,
            'value_rbp'             => $get_pengajuan_detail_summary->value_rbp,

            'verifikasi_at'         => $get_pengajuan->verifikasi_at,
            'verifikasi_username'   => $get_pengajuan->verifikasi_username,
            'principal_area_at'     => $get_pengajuan->principal_area_at,
            'principal_area_username' => $get_pengajuan->principal_area_username,
            'principal_ho_at'       => $get_pengajuan->principal_ho_at,
            'principal_ho_username' => $get_pengajuan->principal_ho_username,
        ];

        $from = "suffy@muliaputramandiri.com";
        // rilis, to nya linda@muliaputramandiri.com
        $to = 'linda@muliaputramandiri.com';
        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . "," . $this->email_admin;

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $config = $this->model_relokasi->email();
        $message = $this->load->view("management_inventory/email_pengajuan_new",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        // $this->email->to('ilhammsyah@gmail.com');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();

        if (strpos($this->email->print_debugger(), 'successfully') > 0) {
            $status_email      = 1;
            $nama_status_email = 'Terkirim';

            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            $status_email       = 9;
            $nama_status_email  = 'Gagal Terkirim';
            $this->session->set_flashdata("pesan", "pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini..");
            echo "<script>alert('pengiriman email gagal. Namun sangat mungkin proses yang anda lakukan sudah berhasil. Untuk memastikannya cek kembali data anda di menu retur ini.'); </script>";
        }

        $data_log = [
            'function'          => 'management_inventory/email_pengajuan_new',
            'id_pengajuan'      => $id_pengajuan,
            'status'            => $get_pengajuan->status,
            'nama_status'       => $nama_status,
            'status_email'      => $status_email,
            'nama_status_email' => $nama_status_email,
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id')
        ];
        $this->db->insert("management_inventory.pengajuan_retur_log_email", $data_log);

        $data_email = [
            'function' => 'management_inventory/email_pengajuan_new',
            'user_email' => $config["smtp_user"],
            'from' => $from,
            'to' => $to,
            'cc' => $email_cc,
            'subject' => $subject,
            'jam_email' => $this->created_at,
            'jumlah_email_terkirim' => count(explode(',', $to.','.$email_cc)),
            'status' => $status_email,
            'nama_status' => $nama_status_email,
            'print_debugger' => $this->email->print_debugger(),
            'created_at' => $this->created_at,
            'created_by' => $this->session->userdata('id'),
        ];

        $this->db->insert('site.email_report', $data_email);
    }

    public function delete_detail($signature, $supp)
    {
        $id_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row()->id;

        // $query = "
        //     delete from management_inventory.pengajuan_retur_detail
        //     where id_pengajuan = $id_pengajuan
        // ";

        $data = [
            'deleted' => 1
        ];
        $update = $this->model_management_inventory->update_pengajuan_retur_detail($data, $id_pengajuan);

        $this->session->set_flashdata("pesan_success", "berhasil menghapus seluruh product");
        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
    }

    public function delete_pengajuan($signature)
    {

        $get_pengajuan_by_signature = $this->model_management_inventory->get_pengajuan_by_signature($signature);
        if (!$get_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Delete data gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_inventory/dashboard');
            die;
        }

        $id_pengajuan = $get_pengajuan_by_signature->row()->id;
        $status = $get_pengajuan_by_signature->row()->status;
        $created_by = $get_pengajuan_by_signature->row()->created_by;

        // echo "id_pengajuan : ".$id_pengajuan;
        // echo "status : ".$status;
        // echo "created_by : ".$created_by;

        if ($created_by != $this->session->userdata('id')) {
            $this->session->set_flashdata("pesan", "Delete data gagal dijalankan. Anda tidak diijinkan menghapus data !!");
            redirect('management_inventory/dashboard');
        }

        if ($status == 1) { // jika status pending dp, masih bisa di hapus
            $created_at = $this->model_outlet_transaksi->timezone();
            $data = [
                'deleted'           => 1,
                'last_updated'      => $created_at,
                'last_updated_by'   => $this->session->userdata('id')
            ];

            $this->db->where('id', $id_pengajuan);
            $this->db->update('management_inventory.pengajuan_retur', $data);

            $this->session->set_flashdata("pesan_success", "delete berhasil");
            redirect('management_inventory/pengajuan_retur');
        }else{
            $this->session->set_flashdata("pesan", "delete pengajuan gagal. Anda tidak diijinkan menghapus pengajuan ini");
            redirect('management_inventory/pengajuan_retur');
        }

        

    }

    public function master_data()
    {
        $id = $this->session->userdata('id');
        if ($id != 547 && $id != 297 && $id != 588 && $id != 857 && $id != 1048) {
            $link = base_url('management_office');
            echo "
            <script>
            alert('Maaf, Anda tidak dapet mengakses menu ini.'); 
            window.location = '$link';
            </script>";
        }

        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/site_code_dp_and_mpi?" . http_build_query($params),
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

        $array_response = json_decode($response, true);
        
        $data = [
            'title'         => 'RETUR | Master Data',
            'url_tambah'    => 'management_inventory/master_mapping_area_tambah',
            'area'          => $array_response["data"],
            'get_master_mapping_area'   => $this->model_management_inventory->get_master_mapping_area(),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_inventory/master_data', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_user_mpm()
    {

        $curl = curl_init();

        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_user_mpm?" . http_build_query($params),
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
            echo "<option value=''> -- Pilih User -- </option>";

            foreach ($result as $key => $r)
            {
                echo "<option value='". $r["id"] ."' name='" . $r["username"] . "' >";
                echo $r["username"] . " ___ " . $r["name"];
                echo "</option>";
            }
        }
    }

    public function master_mapping_area_tambah()
    {
        $supp       = $this->input->post('supp');
        $pic        = $this->input->post('user');
        $area       = $this->input->post('options');
        $status     = $this->input->post('status');
        $created_at = $this->model_outlet_transaksi->timezone();

        for ($s=0; $s < count($supp) ; $s++){
            for ($i=0; $i < count($area) ; $i++) {
                $cek_mapping_area = $this->model_management_inventory->get_master_mapping_area($supp[$s], $pic, explode(" ",$area[$i])[0]);
                if ($cek_mapping_area->num_rows() == 0) {
                    // insert
                    $data = [
                        'supp'          => $supp[$s],
                        'site_code'     => explode(" ",$area[$i])[0],
                        'userid'        => $pic,
                        'status_ho'     => $status,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id'),
                    ];
                    $this->db->insert('management_inventory.mapping_area_retur', $data);
                } else {
                    $data = [
                        'supp'          => $supp[$s],
                        'site_code'     => explode(" ",$area[$i])[0],
                        'userid'        => $pic,
                        'status_ho'     => $status,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id'),
                    ];
                    $this->db->where('supp', $supp[$s]);
                    $this->db->where('site_code', explode(" ",$area[$i])[0]);
                    $this->db->where('userid', $pic);
                    $this->db->update('management_inventory.mapping_area_retur', $data);
                }
            }
        }
        $this->session->set_flashdata("pesan_success_master_mapping_area", "Submit Data Successfully");
        redirect('management_inventory/master_data');
    }

    public function master_mapping_area_delete()
    {
        $id_mapping = $this->uri->segment(3);
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id'),
        ];
        $this->db->where('md5(id)', $id_mapping);
        $this->db->update('management_inventory.mapping_area_retur', $data);
        $this->session->set_flashdata("pesan_success_master_mapping_area", "Delete Data Successfully");
        redirect('management_inventory/master_data#master-mapping-area');
    }

    public function retur_log($id_pengajuan)
    {
        $data = [
            'title' => 'Pengajuan Retur - Log Email',
            'url'   => 'management_inventory/retur_log',
            'get_retur_log_email'   => $this->model_management_inventory->get_retur_log_email($id_pengajuan),
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('management_inventory/log', $data);
        $this->load->view('kalimantan/footer');
    }

    public function retur_log_proses($id_pengajuan)
    {
        $userid = $this->session->userdata('id');
        if ($userid != 547 && $userid != 297 && $userid != 588 && $userid != 857 && $userid != 1048) {
            $this->session->set_flashdata("pesan", "Anda Tidak Diizinkan Melakukan Proses Ini");
            redirect("management_inventory/retur_log/$id_pengajuan");
        }

        $get_log = $this->model_management_inventory->get_retur_log_email($id_pengajuan);
        $signature = $get_log->row()->signature;
        $function = explode('/', $get_log->row()->function)[1];

        $this->$function($signature);
        redirect("management_inventory/retur_log/$id_pengajuan");
    }

    public function master_tipe()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $supp = $this->input->post('supp');   
        $username = $this->input->post('username');   

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'supp'      => $supp,
            'username'    => $username
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_tipe?" . http_build_query($params),
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
            $dataalasan = $array_response['data'];
            // var_dump($dataalasan);die;
            echo "<option value=''> -- Pilih Tipe -- </option>";

            foreach ($dataalasan as $key => $a)
            {
                echo "<option value='". $a["tipe"] . "' >";
                echo $a["tipe"];
                echo "</option>";
            }
        }
    }

    public function satuan()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        // echo "satuan";
        // die;

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'kodeprod'  => $this->input->post('kodeprod'),
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/satuan?" . http_build_query($params),
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

            var_dump($array_response);

            $datasatuan = $array_response['data'];
            // echo "<option value=''> -- Pilih Kabupaten -- </option>";

            foreach ($datasatuan as $key => $tiap_satuan)
            {
                echo "<option value='". $tiap_satuan["kecil"] ."' id_satuan='" . $tiap_satuan["kecil"] . "' >";
                echo $tiap_satuan["kecil"];
                echo "</option>";
            }
        }
    }

    public function master_alasan()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = getenv('USER_ID');

        // echo "api key : " . getenv('API_KEY');
        // var_dump($api_key);

        $api_url = "http://site.muliaputramandiri.com/";
        $supp = $this->input->post('supp');
        $tipe = $this->input->post('tipe');
        $api_key = '123';
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'supp'      => $supp,
            'tipe'      => $tipe
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url ."restapi/api/master_data/alasan_retur_new?" . http_build_query($params),
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/alasan_retur_new?token=$token&X-API-KEY=$api_key&tipe=$tipe&supp=001-GT",
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
            $dataalasan = $array_response['data'];
            // var_dump($dataalasan);
            // die;
            echo "<option value=''> -- Pilih Alasan -- </option>";

            foreach ($dataalasan as $key => $tiap_alasan)
            {
                echo "<option value='". $tiap_alasan["kode_alasan"] . "' >";
                echo $tiap_alasan["nama_alasan"];
                echo "</option>";
            }
        }
    }

    public function kodeprod(){

        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = getenv('USER_ID');

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $supp = $this->input->post('supp');
        $api_url = "http://site.muliaputramandiri.com/";
        $api_key = '123';

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'supp'      => $supp,
        );        

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/kodeprod?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datakodeprod = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Kodeprod -- </option>";

            foreach ($datakodeprod as $key => $tiap_kodeprod)
            {
                echo "<option value='". $tiap_kodeprod["KODEPROD"] ."' id_kodeprod='" . $tiap_kodeprod["KODEPROD"] . "' >";
                echo $tiap_kodeprod["KODEPROD"]." - ".$tiap_kodeprod["NAMAPROD"]." - ".$tiap_kodeprod["kecil"];
                echo "</option>";
            }
        }
    }

    public function kodeprod_origin(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kodeprod?token=$token&supp=$supp&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datakodeprod = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Kodeprod -- </option>";

            foreach ($datakodeprod as $key => $tiap_kodeprod)
            {
                echo "<option value='". $tiap_kodeprod["KODEPROD"] ."' id_kodeprod='" . $tiap_kodeprod["KODEPROD"] . "' >";
                echo $tiap_kodeprod["KODEPROD"]." - ".$tiap_kodeprod["NAMAPROD"]." - ".$tiap_kodeprod["kecil"];
                echo "</option>";
            }
        }
    }

    // public function action_pengajuan_retur()
    // {
    //     $curl = curl_init();
    //     $api_url = getenv('API_URL');
    //     $token = getenv('API_TOKEN');
    //     $api_key = getenv('API_KEY');
    //     $userid = getenv('USER_ID');

    //     $supp = $this->input->post('supp');        
    //     $signature = $this->input->post('signature');

    //     $token = "11f3a8a682c1e8d097ae60d72ecf07c7";
    //     $api_key = "123";
    //     // $api_url = "http://localhost:81/";

    //     $params = array(
    //         'token'     => $token,
    //         'X-API-KEY' => $api_key,
    //         'supp'      => $supp,
    //         'status_principal_ho_terpilih' => $this->input->post('status_principal_ho_terpilih'),
    //         'signature' => $signature
    //     );

    //     curl_setopt_array($curl, array(
    //         // CURLOPT_URL => $api_url . "restapi/api/master_data/action_pengajuan_retur?" . http_build_query($params),
    //         // CURLOPT_URL => $api_url . "restapi/api/master_data/action_pengajuan_retur?" . http_build_query($params),
    //         CURLOPT_URL => $api_url . "restapi/api/master_data/action_pengajuan_retur?" . http_build_query($params),
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "GET",
    //     ));

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);

    //     curl_close($curl);

    //     if ($err) {
    //     echo "cURL Error #:" . $err;
    //     } else {
    //         $array_response = json_decode($response, true);
    //         $dataaction = $array_response['data'];

    //         // echo "<option value=''> -- Pilih Kabupaten -- </option>";

    //         foreach ($dataaction as $key => $tiap_action)
    //         {
    //             echo "<option value='". $tiap_action["id_status"] ."' id_action='" . $tiap_action["action_retur"] . "' >";
    //             echo $tiap_action["action_retur"];
    //             echo "</option>";
    //         }
    //     }
    // }

    public function action_pengajuan_retur()
    {
        $curl = curl_init();

        $api_url = getenv('API_URL');
        $token = "11f3a8a682c1e8d097ae60d72ecf07c7";
        $api_key = "123";

        $supp = $this->input->post('supp');
        $signature = $this->input->post('signature');
        $status = $this->input->post('status_principal_ho_terpilih');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key,
            'supp' => $supp,
            'status_principal_ho_terpilih' => $status,
            'signature' => $signature
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/action_pengajuan_retur?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $array_response = json_decode($response, true);

        if(isset($array_response['data'])){
            foreach ($array_response['data'] as $tiap_action){
                echo "<option value='".$tiap_action["id_status"]."'>";
                echo $tiap_action["action_retur"];
                echo "</option>";
            }
        }else{
            echo "<option value=''>Data tidak ditemukan</option>";
        }
    }

    public function is_file_exist($filename, $email_capture = null)
    {
        // echo 'disini'; die;
        $filename = trim($filename);
        $base_physical_path = FCPATH;
        // $base_physical_path = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR;
        // echo 'filename : '.$filename;
        // echo 'email_capture : '.$email_capture;die;
        // echo $base_physical_path;die;
        if($email_capture == 1)
        {
            $path = $base_physical_path . 'assets/file/retur/email_capture/' . $filename;
            $path_alternate = "D:/backup/retur/email_capture/" . $filename;
            $path_alternate_2 = $base_physical_path . 'assets/file/retur/' . $filename;
        }else{
            $path = $base_physical_path . 'assets/file/retur/' . $filename;
            $path_alternate = "D:/backup/retur/" . $filename;
            $path_alternate_2 = $base_physical_path . 'assets/file/retur/email_capture/' . $filename;
        }

        // echo 'path : '.$path;
        // echo '<br>';
        // echo 'path_alternatif : '.$path_alternate;
        // echo '<br>';
        // echo 'path_alternatif : '.$path_alternate_2;
        // echo '<br>';die;

        // cek dimana file exist
        if(file_exists($path))
        {
            // echo "exist a";die;
            $path = $path;
        }else if(file_exists($path_alternate))
        {
            // echo "exist b";die;
            $path = $path_alternate;
        }else if(file_exists($path_alternate_2))
        {
            // echo "exist c";die;
            $path = $path_alternate_2;
        }else{
            // echo "gaada";die;
            return null;
        }
        
        // header('Content-Type: application/octet-stream');
        // header("Content-Transfer-Encoding: Binary");
        // header("Content-disposition: attachment; filename=\"" . basename($path) . "\"");
        // readfile($path);

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        header('Content-Length: ' . filesize($path));

        readfile($path);
        exit;

    }

    // public function is_file_exist($filename, $email_capture = null)
    // {
    //     // 1. Bersihkan filename dari spasi yang tidak disengaja
    //     $filename = trim($filename);
    //     $base_physical_path = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR;
    //     $final_path = null;

    //     // 2. Tentukan urutan path yang akan dicek
    //     if ($email_capture == 1) {
    //         $path1 = $base_physical_path . 'assets/file/retur/email_capture/' . $filename;
    //         $path2 = "D:/backup/retur/email_capture/" . $filename;
    //         $path3 = $base_physical_path . 'assets/file/retur/' . $filename;
    //     } else {
    //         $path1 = $base_physical_path . 'assets/file/retur/' . $filename;
    //         $path2 = "D:/backup/retur/" . $filename;
    //         $path3 = $base_physical_path . 'assets/file/retur/email_capture/' . $filename;
    //     }

    //     // 3. Cek keberadaan file tanpa melakukan 'echo' atau 'die'
    //     if (file_exists($path1)) {
    //         $final_path = $path1;
    //     } else if (file_exists($path2)) {
    //         $final_path = $path2;
    //     } else if (file_exists($path3)) {
    //         $final_path = $path3;
    //     }

    //     echo 'email capture : ' . $email_capture;
    //     echo '<br>';
    //     echo $final_path;die;

    //     // 4. Proses Eksekusi jika file ditemukan
    //     if ($final_path) {
    //         // Bersihkan output buffer untuk mencegah file rusak/corrupt
    //         if (ob_get_level()) ob_end_clean();

    //         header('Content-Description: File Transfer');
    //         header('Content-Type: application/octet-stream');
    //         header("Content-Disposition: attachment; filename=\"" . basename($final_path) . "\"");
    //         header('Expires: 0');
    //         header('Cache-Control: must-revalidate');
    //         header('Pragma: public');
    //         header('Content-Length: ' . filesize($final_path));
            
    //         readfile($final_path);
    //         // echo 'file ditemukan';die;
    //         exit;
    //     } else {
    //         // Jika benar-benar tidak ada, berikan pesan error atau redirect
    //         show_404(); // Atau pesan "File tidak ditemukan"
    //     }
    // }
}
?>
