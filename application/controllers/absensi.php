<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Absensi extends MY_Controller 
{
    function absensi()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv', 'download'));
        $this->load->model(array('model_outlet_transaksi', 'model_absensi', 'model_relokasi'));
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->created_by = $this->session->userdata('id');
        $this->username = $this->session->userdata('username');
    }

    function index()
    {
        $this->catatan_kehadiran();
    }

    function navbar($data){
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

    public function catatan_kehadiran()
    {
        $data_absensi = $this->model_absensi->data_absensi_transaksi($this->session->userdata('id'));
        foreach ($data_absensi->result() as $key) {
            $flag_terlambat[] = $key->flag_terlambat;
        }

        $data = [
            'title'             => 'Absensi Karyawan - Catatan Kehadiran',
            'get_karyawan'      => $this->model_absensi->get_karyawan($this->session->userdata('id')),
            'data_absensi'      => $data_absensi,
            'url'               => 'absensi/cek_absensi',
            'total_hadir'       => $data_absensi->num_rows(),
            'total_terlambat'   => ($data_absensi->num_rows() > 0)?array_sum($flag_terlambat):0,   
            'get_absensi'       => $this->model_absensi->get_absensi($this->created_by),
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('absensi/catatan_kehadiran', $data);
        $this->load->view('kalimantan/footer');
    }

    public function cek_absensi()
    {
        $month  = $this->input->get('bulan');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];
        $signature = 'absensi-'. md5($this->created_by.$tahun.$bulan);

        // echo $bulan;
        // echo $tahun;
        // echo $signature; die;

        //jika data di absensi_transaksi tidak ada (karena data di table absensi kosong)
        $get_absensi_by_signature = $this->model_absensi->data_absensi_transaksi_by_signature($signature);
        // echo $get_absensi_by_signature->num_rows();die;
        if ($get_absensi_by_signature->num_rows() == 0) {
            $get_name_karyawan = $this->model_absensi->get_user($this->created_by)->row()->name;
            $this->session->set_flashdata("pesan", "Absensi Karyawan " . $get_name_karyawan ." ". $bulan . "-" . $tahun . " Tidak Ada!");
            redirect('absensi');
        }

        // get_userid 
        $get_userid = $get_absensi_by_signature->row()->userid;
        $flag_weekend = $this->model_absensi->get_m_karyawan($get_userid)->row()->flag_weekend;

        // get terlambar
        $get_terlambat = $this->model_absensi->get_absensi_transaksi_terlambat($signature);

        // get total hari kerja tanpa weekend
        $get_hari_kerja = $this->model_absensi->get_absensi_transaksi_hari_kerja($signature, $flag_weekend);

        // get absensi transaksi yang tidak lengkap
        $tidak_lengkap = $this->model_absensi->get_absensi_transaksi_tidak_lengkap($signature, $flag_weekend);
        
        // get absensi transaksi yang tidak lengkap tapi keterangan nya null
        $tidak_lengkap_and_keterangan_null = $this->model_absensi->get_absensi_transaksi_tidak_lengkap_and_keterangan_null($signature, $flag_weekend);

        // get absensi transaksi yang tidak lengkap tapi keterangan nya null
        $terlambat_and_keterangan_null = $this->model_absensi->get_absensi_transaksi_terlambat_and_keterangan_null($signature, $flag_weekend);

        $data = [
            'title'             => 'Absensi Karyawan - Input Absensi',
            'data_absensi'      => $get_absensi_by_signature,
            'url'               => 'absensi/cek_absensi/',
            'url_update'        => 'absensi/update_absensi',
            'url_delete'        => 'absensi/delete_keterangan',
            'userid_karyawan'   => $this->created_by,
            'signature'         => $signature,
            'total_hari_kerja'  => $get_hari_kerja->num_rows(),
            'total_tidak_lengkap'  => $tidak_lengkap->num_rows(),
            'total_terlambat'    => $get_terlambat->num_rows() ? $get_terlambat->num_rows() : 0,
            'bulan'             => $month,
            'flag_weekend'      => $flag_weekend ? $flag_weekend : 0,
            'total_tidak_lengkap_and_keterangan_null' => $tidak_lengkap_and_keterangan_null->num_rows(),
            'total_terlambat_and_keterangan_null' => $terlambat_and_keterangan_null->num_rows(),

        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('absensi/cek_absensi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function update_absensi()
    {
        $tanggal            = $this->input->post('tanggal');
        $simpan             = $this->input->post('submit');
        $id_absensi         = $this->input->post('id_absensi');
        $keterangan         = $this->input->post('keterangan');
        $bulan              = $this->input->post('bulan');
        $userid_karyawan    = $this->input->post('userid_karyawan');
        $signature          = $this->input->post('signature');
        $simpan             = $this->input->post('submit');
        $created_at         = $this->model_outlet_transaksi->timezone();

        // echo date('m', strtotime($tanggal[0])); die;
        // echo '<br>';
        // var_dump($bulan);die;


        //jika data di absensi_transaksi tidak ada (karena data di table absensi kosong)
        $get_absensi_by_signature = $this->model_absensi->data_absensi_transaksi_by_signature($signature);
        // echo $get_absensi_by_signature->num_rows();die;
        if ($get_absensi_by_signature->num_rows() == 0) {
            $get_name_karyawan = $this->model_absensi->get_user($this->created_by)->row()->name;
            $this->session->set_flashdata("pesan", "Absensi Karyawan " . $get_name_karyawan ." ". $bulan . "-" . $tahun . " Tidak Ada!");
            redirect('absensi');
        }

        // get_userid 
        $get_userid = $get_absensi_by_signature->row()->userid;
        $flag_weekend = $this->model_absensi->get_m_karyawan($get_userid)->row()->flag_weekend;

        // get terlambar
        $get_terlambat = $this->model_absensi->get_absensi_transaksi_terlambat($signature);

        // get total hari kerja tanpa weekend
        $get_hari_kerja = $this->model_absensi->get_absensi_transaksi_hari_kerja($signature, $flag_weekend);

        // get total hadir kerja actual_masuk or actual_keluar is not null
        $get_hadir_kerja = $this->model_absensi->get_absensi_transaksi_hadir_kerja($signature, $userid_karyawan);

        // get absensi transaksi yang tidak lengkap
        $tidak_lengkap = $this->model_absensi->get_absensi_transaksi_tidak_lengkap($signature, $flag_weekend);
        
        // get absensi transaksi yang tidak lengkap tapi keterangan nya null
        $tidak_lengkap_and_keterang_null = $this->model_absensi->get_absensi_transaksi_tidak_lengkap_and_keterangan_null($signature, $flag_weekend);

        $data_absensi = $this->model_absensi->data_absensi_by_signature($this->created_by, $signature);
        if ($data_absensi->num_rows() == 0) {
            $data = [
                'userid'        => $userid_karyawan,
                'bulan'         => date('m', strtotime($tanggal[0])),
                'tahun'         => date('Y', strtotime($tanggal[0])),
                'hadir'         => $get_hadir_kerja->num_rows(),
                'terlambat'     => $get_terlambat->num_rows() > 0 ? $get_terlambat->num_rows() : 0,
                'flag_status'   => 0,
                'status'        => 'Pending User',
                'signature'     => $signature,
                'created_at'    => $created_at,
                'created_by'    => $this->session->userdata('id'),
                'tidak_lengkap' => $tidak_lengkap->num_rows() > 0 ? $tidak_lengkap->num_rows() : 0,
                'total_hari_kerja' => $get_hari_kerja->num_rows(),
            ];
            $this->db->insert('site.absensi', $data);
        }

        // echo "simpan = " . $simpan;
        // die;

        if($simpan == 0) // kalau klik button update sementara lakukan update
        {
            // update berdasarkan keterangan diisi
            for ($i=0; $i < count($id_absensi) ; $i++) 
            {
                $data = [
                    'updated_by'    => $this->created_by,
                    'updated_at'    => $created_at,
                    'keterangan'    => $keterangan[$i]
                ];
                $this->db->where('id', $id_absensi[$i]);
                $this->db->where('userid', $userid_karyawan);
                $this->db->update('site.absensi_transaksi', $data);
            }

            if ($data_absensi->num_rows() > 0) 
            {
                $data = [
                    'hadir'         => $get_hadir_kerja->num_rows(),
                    'updated_at'    => $created_at,
                    'updated_by'    => $this->session->userdata('id'), 
                    "tidak_lengkap" => $tidak_lengkap->num_rows() > 0 ? $tidak_lengkap->num_rows() : 0,
                    'terlambat'     => $get_terlambat->num_rows() > 0 ? $get_terlambat->num_rows() : 0,
                ];

                $this->db->where('userid', $userid_karyawan);
                $this->db->where('signature', $signature);
                $this->db->update('site.absensi', $data);
            } 

            // die;
            
            $this->session->set_flashdata("pesan_success", "Simpan Data Successfully");
            redirect('absensi/cek_absensi?bulan='.$bulan); 
        }else
        {   
            // pastikan tidak ada keterangan null
            // flag_status menjadi 1, dan generate no_generate_report

            $data = [
                "hadir"         => $get_hadir_kerja->num_rows(),
                "flag_status"   => 1,
                "status"        => "Pending Approval",
                "tidak_lengkap" => $tidak_lengkap->num_rows() > 0 ? $tidak_lengkap->num_rows() : 0,
                'terlambat'     => $get_terlambat->num_rows() > 0 ? $get_terlambat->num_rows() : 0,
            ];

            $this->db->where('userid', $userid_karyawan);
            $this->db->where('signature', $signature);
            $this->db->update('site.absensi', $data);

            
            $this->session->set_flashdata("pesan_success", "Submit Data Successfully");
            // redirect('absensi/email_verifikasi_atasan/'.$signature);
            // redirect('absensi/cek_absensi?bulan='.$bulan);
        }
    }

    public function email_verifikasi_atasan($signature)
    {
        // echo 'a';die;
        // echo "email_verifikasi_atasan".$signature; die;
        $Bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $get_m_karyawan = $this->model_absensi->get_user($this->created_by);
        if ($get_m_karyawan->num_rows() == 0) {
            $this->session->set_flashdata("pesan", "Data Karyawan Tidak Ada!");
            redirect('absensi');
        }else {

            $get_name_karyawan = $get_m_karyawan->row()->name;
            $atasan_karyawan = $get_m_karyawan->row()->username_atasan;
            $email_karyawan = $get_m_karyawan->row()->email_karyawan;
            $email_atasan = $get_m_karyawan->row()->email_atasan;

            // if ($get_m_karyawan->row()->id == 685) { //jika karyawan pak zul
            //     // echo 'a';
            //     $get_name_karyawan = $get_m_karyawan->row()->name;
            //     $atasan_karyawan = 'Fardison';
            //     $email_atasan = 'fardison@muliaputramandiri.com';
            //     $email_karyawan = $get_m_karyawan->row()->email_karyawan;
            // }else{
            //     // echo 'b';die;
            //     $get_name_karyawan = $get_m_karyawan->row()->name;
            //     $atasan_karyawan = $get_m_karyawan->row()->username_atasan;
            //     $email_karyawan = $get_m_karyawan->row()->email_karyawan;
            //     $email_atasan = $get_m_karyawan->row()->email_atasan;
            // }
        }
        
        //jika data di absensi_transaksi tidak ada (karena data di table absensi kosong)
        $get_absensi_by_signature = $this->model_absensi->data_absensi_transaksi_by_signature($signature);
        $bulan = $get_absensi_by_signature->row()->bulan;
        // echo $bulan;die;
        $tahun = $get_absensi_by_signature->row()->tahun;
        $Bulan_Indo = $Bulan[(int)$bulan-1];
        // echo $get_absensi_by_signature->num_rows();die;
        if ($get_absensi_by_signature->num_rows() == 0) {
            $this->session->set_flashdata("pesan", "Absensi Karyawan " . $get_name_karyawan . " Tidak Ada!");
            redirect('absensi');
        }

        $get_data_absensi = $this->model_absensi->data_absensi_by_signature($this->created_by, $signature);
        if ($get_data_absensi->num_rows() > 0) {
            $status_approval = $get_data_absensi->row()->status;
            $hadir = $get_data_absensi->row()->hadir;
            $terlambat = $get_data_absensi->row()->terlambat;
            $total_hari_kerja = $get_data_absensi->row()->total_hari_kerja;
            $created_at = $get_data_absensi->row()->created_at;
            $tidak_lengkap = $get_data_absensi->row()->tidak_lengkap;
        }else{
            $status_approval = "";
        }

        $data = [
            "name_karyawan" => $get_name_karyawan,
            'atasan_karyawan' => $atasan_karyawan, 
            'nama_karyawan' => $get_name_karyawan,
            'signature' => $signature,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'bulan_indo' => $Bulan_Indo,
            'total_hari_kerja' => $total_hari_kerja,
            'created_at' => $created_at,
            'tidak_lengkap' => $tidak_lengkap,

        ];

        $this->model_relokasi->email();
        $from   = "suffy@muliaputramandiri.net";
        $to     = $email_atasan;
        $cc     = "mahitalampm86@gmail.com,".$email_karyawan;

        $message = $this->load->view("absensi/email_verifikasi_atasan",$data,TRUE);
        $subject = "MPM Site | Absensi : $get_name_karyawan"." | ". $Bulan_Indo ." ". $tahun ."| $status_approval - ".$atasan_karyawan;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->cc($cc);

        $send = $this->email->send();
        // print_r($this->email->print_debugger()); die;
        if (strpos($this->email->print_debugger(), 'successfully') > 0) 
        {
            $this->session->set_flashdata("pesan_success", "Email Terkirim Ke Atasan dan Submit Data Successfully");
            redirect('absensi/cek_absensi?bulan='.$tahun.'-'.$bulan);
        }else{
            $this->session->set_flashdata("pesan", "Email Gagal Terkirim");
            redirect('absensi/cek_absensi?bulan='.$tahun.'-'.$bulan);
        }

    }

    public function edit_terlambat($id, $signature,$month)
    {
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];
        $cek = $this->model_absensi->get_absensi_by_userid_signature_tahun_bulan($this->created_by, $signature, $tahun, $bulan);
        // die;
        if ($cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Edit Izin Failed. Status absensi masih dalam kondisi tidak boleh mengubah data");
            redirect('absensi/cek_absensi/?bulan='.$month);
        }  
        // echo "id : ".$id; die;
        // cek apakah signature dan id_absensi ada di database
        $get_data = $this->model_absensi->get_absensi_transaksi_by_signature_and_id_absensi($id, $signature);
        $get_flag_status_absensi = $get_data->row()->flag_status_absensi;
        $flag_terlambat = $get_data->row()->flag_terlambat;
        $actual_masuk = $get_data->row()->actual_masuk;
        $actual_keluar = $get_data->row()->actual_keluar;
        $jam_keluar_kantor = $get_data->row()->jam_keluar_kantor;
        // echo $get_flag_status_absensi;die;

        if ($get_data->num_rows() > 0) {
            $get_absensi_log_perubahan_status = $this->model_absensi->get_absensi_log_perubahan_status($id);
            $flag_status_absensi_sebelum = $get_absensi_log_perubahan_status->row()->flag_status_absensi_sebelum;
            $flag_status_absensi_sesudah = $get_absensi_log_perubahan_status->row()->flag_status_absensi_sesudah;

            if($flag_status_absensi_sesudah == null){
                $flag_status_absensi_sesudah_final = '1';
            }else{
                $flag_status_absensi_sesudah_final = $flag_status_absensi_sebelum;
            }
            
            $flag_terlambat_sebelum = $get_absensi_log_perubahan_status->row()->flag_terlambat_sebelum;
            $flag_terlambat_sesudah = $get_absensi_log_perubahan_status->row()->flag_terlambat_sesudah;
            if($flag_terlambat_sesudah == null){
                if($flag_terlambat == "0" && ($actual_masuk == "" || $actual_keluar == "" || $jam_keluar_kantor > $actual_keluar)){
                    $flag_terlambat_sesudah_final = "0";
                }elseif($flag_terlambat == "1" && ($actual_masuk == "" || $actual_keluar == "")){
                    $flag_terlambat_sesudah_final = "0";
                }else{
                    $flag_terlambat_sesudah_final = "1";
                }
            }else{
                $flag_terlambat_sesudah_final = $flag_terlambat_sebelum;
            }
            
            if($get_flag_status_absensi == 0){
                $nama_status = "izin";
            }else{
                $nama_status = null;
            }
            
            // insert data ke tabel log_perubahan_status
            $data = [
                "id_absensi_transaksi"  => $id,
                "flag_status_absensi_sebelum"   => $get_absensi_log_perubahan_status->num_rows() == null ? $get_flag_status_absensi : $flag_status_absensi_sesudah,
                "flag_status_absensi_sesudah"   => $flag_status_absensi_sesudah_final,
                "flag_terlambat_sebelum"        => $get_absensi_log_perubahan_status->num_rows() == null ? $flag_terlambat : $flag_terlambat_sesudah,
                "flag_terlambat_sesudah"        => $flag_terlambat_sesudah_final,
                "created_by"                    => $this->created_by,
                "created_at"                    => $this->created_at
            ];
            $this->db->insert('site.absensi_log_perubahan_status', $data);
            
            // update data absensi_transaksi
            $data = [
                "flag_status_absensi" => "$flag_status_absensi_sesudah_final",
                "nama_status" => "$nama_status",
                "flag_terlambat" => "$flag_terlambat_sesudah_final",
                "updated_by" => $this->created_by,
                "updated_at" => $this->created_at
            ];
            $this->db->where('id', $id);
            $this->db->where('signature', $signature);
            $this->db->update('site.absensi_transaksi', $data);

            $this->session->set_flashdata("pesan_success", "Update Izin Successfully");
            redirect('absensi/cek_absensi/?bulan='.$month);
        } else{
            $this->session->set_flashdata("pesan", "Update Izin Failed. Please Try Again or Contact Administrator");
            redirect('absensi/cek_absensi/?bulan='.$month);
        }
    }

    public function delete_keterangan($id, $signature,$month)
    {
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];
        $cek = $this->model_absensi->get_absensi_by_userid_signature_tahun_bulan($this->created_by, $signature, $tahun, $bulan);
        if ($cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Delete Failed. Status absensi masih dalam kondisi tidak boleh mengubah data");
            redirect('absensi/cek_absensi/?bulan='.$month);
        }

        // cek apakah signature dan id_absensi ada di database
        $get_data = $this->model_absensi->get_absensi_transaksi_by_signature_and_id_absensi($id, $signature);
        if ($get_data->num_rows() > 0) {
            $data = [
                "keterangan" => NULL,
                "updated_by" => $this->created_by,
                "updated_at" => $this->created_at
            ];
            $this->db->where('id', $id);
            $this->db->where('signature', $signature);
            $this->db->update('site.absensi_transaksi', $data);

            // echo "signature : ".$signature;

            $this->session->set_flashdata("pesan_success", "Delete Successfully");
            redirect('absensi/cek_absensi/?bulan='.$month);
        } else{
            $this->session->set_flashdata("pesan", "Delete Failed. Please Try Again or Contact Administrator");
            redirect('absensi/cek_absensi/?bulan='.$month);
        }

    }

    public function verifikasi()
    {
        $userid_karyawan = $this->input->get('karyawan');
        $month = $this->input->get('bulan');
        
        $data = [
            'title'         => "Verifikasi Absensi Team Member",
            'get_karyawan'  => $this->model_absensi->get_karyawan($this->session->userdata('id')),
            'url'           => 'absensi/verifikasi',
            'url2'          => 'absensi/verifikasi_hrd_proses',
            'get_absensi_by_userverifikasi' => $this->model_absensi->get_absensi_by_verifikasi($this->created_by, $month),
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('absensi/verifikasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_atasan()
    {
        $signature  = $this->uri->segment(3);
         //jika data di absensi_transaksi tidak ada (karena data di table absensi kosong)
        $get_absensi_by_signature = $this->model_absensi->data_absensi_transaksi_by_signature($signature);
        // echo $get_absensi_by_signature->num_rows();die;
        if ($get_absensi_by_signature->num_rows() == 0) {
            $get_name_karyawan = $this->model_absensi->get_user($this->created_by)->row()->name;
            $this->session->set_flashdata("pesan", "Absensi Karyawan " . $get_name_karyawan ." ". $bulan . "-" . $tahun . " Tidak Ada!");
            redirect('absensi');
        }

        // get_userid 
        $get_userid = $get_absensi_by_signature->row()->userid;
        $flag_weekend = $this->model_absensi->get_m_karyawan($get_userid)->row()->flag_weekend;
        $flag_status  = $get_absensi_by_signature->row()->flag_status;
        
        // if ($get_absensi_by_signature->row()->flag_status > 1) {
        //     $this->session->set_flashdata("pesan", "Anda Tidak Diizinkan Verifikasi Absensi Ini!");
        //     redirect('absensi/verifikasi');
        //     die;
        // } 
        
        $get_karyawan = $this->model_absensi->get_user($get_userid);

        if ($get_karyawan->row()->userid_verifikasi1 != $this->session->userdata('id')) {
            $this->session->set_flashdata("pesan", "Anda Tidak Diizinkan Verifikasi Absensi Ini!");
            redirect('absensi/verifikasi');
            die;
        }
        // if ($get_karyawan->row()->id == 685) { // jika pak zul
        //     $this->session->userdata('id') == 306;
        // }elseif($get_karyawan->row()->userid_verifikasi1 != $this->session->userdata('id')) {
        //     $this->session->set_flashdata("pesan", "Anda Tidak Diizinkan Verifikasi Absensi Ini!");
        //     redirect('absensi/verifikasi');
        //     die;
        // }

        // get terlambar
        $get_terlambat = $this->model_absensi->get_absensi_transaksi_terlambat($signature);

        // echo "get_terlambat : ". $get_terlambat->num_rows();

        // get total hari kerja tanpa weekend
        $get_hari_kerja = $this->model_absensi->get_absensi_transaksi_hari_kerja($signature, $flag_weekend);

        // get absensi transaksi yang tidak lengkap
        $tidak_lengkap = $this->model_absensi->get_absensi_transaksi_tidak_lengkap($signature, $flag_weekend);

        // get absensi transaksi yang tidak lengkap tapi keterangan nya null
        $tidak_lengkap_and_keterangan_null = $this->model_absensi->get_absensi_transaksi_tidak_lengkap_and_keterangan_null($signature, $flag_weekend);

        // get absensi transaksi yang tidak lengkap tapi keterangan nya null
        $terlambat_and_keterangan_null = $this->model_absensi->get_absensi_transaksi_terlambat_and_keterangan_null($signature, $flag_weekend);

        
        $data = [
            'title'                 => "Verifikasi Absensi Karyawan - ".$get_karyawan->row()->name,
            'data_absensi'          => $get_absensi_by_signature,
            'url'                   => 'absensi/verifikasi_atasan_proses',
            'url2'                  => "absensi/signature_digital/$signature/verifikasi_atasan",
            'userid_verifikasi1'    => $get_karyawan->row()->userid_verifikasi1,
            'signature'             => $signature,
            'total_hadir'           => $get_absensi_by_signature->row()->hadir,
            'tidak_lengkap'         => $tidak_lengkap->num_rows() > 0 ? $tidak_lengkap->row()->tidak_lengkap : 0,
            'total_terlambat'       => $get_terlambat->num_rows() > 0 ? $get_terlambat->num_rows() : 0,
            'total_tidak_lengkap_and_keterang_null' => $tidak_lengkap_and_keterangan_null->num_rows(),
            'flag_weekend'          => $flag_weekend,
            'flag_status'           => $flag_status
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('absensi/verifikasi_atasan', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_atasan_proses()
    {
        $status_verifikasi  = $this->input->post('status_verifikasi');
        $signature          = $this->input->post('signature');

        $data = [
            'flag_status'           => ($status_verifikasi == 1) ? 2 : 9,
            'status'                => ($status_verifikasi == 1) ? "Approved" : "Rejected",
            'verifikasi_status'     => $status_verifikasi,
            'verifikasi_keterangan' => $this->input->post('verifikasi_ket'),
            'verifikasi_by'         => $this->session->userdata('id'),
            'verifikasi_at'         => $this->model_outlet_transaksi->timezone(),
            'updated_by'            => $this->session->userdata('id'),
            'updated_at'            => $this->model_outlet_transaksi->timezone(),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.absensi', $data);

        $this->session->set_flashdata("pesan_success", "Verifikasi Absensi Successfully");
        redirect("absensi/verifikasi");
    }

    public function verifikasi_hrd()
    {
        if (($this->username != 'ratri') && ($this->username != 'nanita') && ($this->username != 'suffy') && ($this->username != 'milla')) {
            $link = base_url('absensi');
            echo "
            <script>
            alert('Maaf, Anda tidak dapet mengakses menu ini.'); 
            window.location = '$link';
            </script>";
        }

        $month = $this->input->get('bulan');

        $data = [
            'title'         => "Verifikasi Absensi Karyawan - HRD",
            'get_karyawan'  => $this->model_absensi->get_karyawan($this->session->userdata('id')),
            'url'           => 'absensi/verifikasi_hrd',
            'url2'          => 'absensi/verifikasi_hrd_proses',
            'data_absensi'  => $this->model_absensi->data_absensi($month),
            'month'         => $month,
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('absensi/verifikasi_hrd', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_hrd_proses()
    {
        $signature          = $this->input->post('signature');
        $flag_status        = $this->input->post('flag_status');
        $no_generate_report = $this->input->post('no_generate_report');
        $bulan              = $this->input->post('bulan');
        $tahun              = $this->input->post('tahun');

        // var_dump($flag_status);die;

        if ($this->username == "ratri"){
            for ($i=0; $i < count($flag_status) ; $i++) { 
                if ($flag_status[$i] == 3) {
                    $this->session->set_flashdata("pesan", "Anda Sudah Closing Report Ini !");
                    redirect("absensi/verifikasi_hrd");
                    die;
                }
                if ($flag_status[$i] != 2 ) {
                    $this->session->set_flashdata("pesan", "Anda Tidak Bisa Closing Report Ini, Karena Ada Status Belum Approved !");
                    redirect("absensi/verifikasi_hrd");
                    die;
                }
            }
        }else{
            $this->session->set_flashdata("pesan", "Anda Tidak Memiliki Akses untuk Closing Report Ini !");
            redirect("absensi/verifikasi_hrd");
            die;
        }
        
        for ($i=0; $i < count($signature); $i++) { 

            $generate_report = $this->model_absensi->generate_report($bulan[$i], $tahun[$i]);
            $data = [
                'no_generate_report'    => $generate_report,
                'flag_status'           => 3,
                'status'                => "Finish",
                'updated_at'            => $this->model_outlet_transaksi->timezone(),
                'updated_by'            => $this->session->userdata('id'),
            ];
    
            $this->db->where('signature', $signature[$i]);
            $this->db->update('site.absensi', $data);
        }

        $this->session->set_flashdata("pesan_success", "Closing Report Successfully");
        redirect('absensi/verifikasi_hrd');
    }

    public function report_backup()
    {
        // if (($this->username != 'ratri') && ($this->username != 'nanita')) {
        //     $link = base_url('absensi');
        //     echo "
        //     <script>
        //     alert('Maaf, Anda tidak dapet mengakses menu ini.'); 
        //     window.location = '$link';
        //     </script>";
        // }

        $userid_karyawan = $this->input->get('karyawan');
        $tahun = $this->input->get('tahun');

        $data = [
            'title'         => "Report Absensi Karyawan",
            'get_karyawan'  => $this->model_absensi->get_karyawan($this->session->userdata('id')),
            'url'           => "absensi/report",
            'data_report'   => $this->model_absensi->report_data_absensi($userid_karyawan, $tahun),
            // 'signature'     => $signature

        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('absensi/report', $data);
        $this->load->view('kalimantan/footer');
    }

    public function report()
    {
        // if (($this->username != 'ratri') && ($this->username != 'nanita')) {
        //     $link = base_url('absensi');
        //     echo "
        //     <script>
        //     alert('Maaf, Anda tidak dapet mengakses menu ini.'); 
        //     window.location = '$link';
        //     </script>";
        // }

        $userid_karyawan = $this->input->get('karyawan');
        $tahun = $this->input->get('tahun');
        $group_by = $this->input->get('group_by');

        // echo "userid_karyawan : ".$userid_karyawan;

        if ($userid_karyawan == 'all') {
            $flag_weekend = null;
        }elseif($userid_karyawan == ''){
            $flag_weekend = null;        
        }else{
            $flag_weekend = $this->model_absensi->get_m_karyawan($userid_karyawan)->row()->flag_weekend;
        }

        // echo "userid karyawan" . $userid_karyawan;
        // echo "tahun" . $tahun;
        // echo "group by" . $group_by;

        if ($group_by == 'none') {
            $flag_hidden_bulan = 1;
        }else{
            $flag_hidden_bulan = 0;
        }


        $data = [
            'title'         => "Report Absensi Karyawan",
            'get_karyawan'  => $this->model_absensi->get_karyawan($this->session->userdata('id')),
            'url'           => "absensi/report",
            'data_report'   => $this->model_absensi->report_data_absensi($userid_karyawan, $tahun, $group_by, $flag_weekend),
            'flag_hidden_bulan' => $flag_hidden_bulan,
            'tahun'         => $tahun,
            // 'signature'     => $signature

        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('absensi/report_new', $data);
        $this->load->view('kalimantan/footer');
    }

    public function detail_absensi_by_month($month, $userid)
    {
        // echo 'month' .$month; echo 'userid'.$userid; die;
        $data_absensi_transaksi_by_month_and_userid = $this->model_absensi->get_absensi_transaksi_by_month_and_userid($month, $userid);
        if ($data_absensi_transaksi_by_month_and_userid->num_rows() == 0)
        {
            $get_name_karyawan = $this->model_absensi->get_user($userid)->row()->name;
            $this->session->set_flashdata("pesan", "Absensi Karyawan " . $get_name_karyawan ." ".$month. " Tidak Ada!");
            redirect('absensi/verifikasi_hrd/?bulan='.$month);
        }

        $signature = $data_absensi_transaksi_by_month_and_userid->row()->signature;
        foreach ($data_absensi_transaksi_by_month_and_userid->result() as $key) {
            $flag_terlambat[] = $key->flag_terlambat;
        }

        $get_absensi_by_signature = $this->model_absensi->data_absensi_transaksi_by_signature($signature);
        $get_userid = $get_absensi_by_signature->row()->userid;
        $flag_weekend = $this->model_absensi->get_m_karyawan($get_userid)->row()->flag_weekend;
        
        // get terlambar
        $get_terlambat = $this->model_absensi->get_absensi_transaksi_terlambat($signature);

        // get absensi transaksi yang tidak lengkap
        $tidak_lengkap = $this->model_absensi->get_absensi_transaksi_tidak_lengkap($signature, $flag_weekend);
                
        // get total hari kerja tanpa weekend
        $get_hari_kerja = $this->model_absensi->get_absensi_transaksi_hari_kerja($signature, $flag_weekend, $get_userid);
        
        // get absensi transaksi yang tidak lengkap tapi keterangan nya null
        $tidak_lengkap_and_keterangan_null = $this->model_absensi->get_absensi_transaksi_tidak_lengkap_and_keterangan_null($signature, $flag_weekend);

        // get absensi transaksi yang tidak lengkap tapi keterangan nya null
        $terlambat_and_keterangan_null = $this->model_absensi->get_absensi_transaksi_terlambat_and_keterangan_null($signature, $flag_weekend);

        $data = [
            'title'             => "Report Absensi Karyawan",
            'data_absensi'      => $data_absensi_transaksi_by_month_and_userid,
            'total_hadir'       => $data_absensi_transaksi_by_month_and_userid->num_rows(),
            'total_terlambat'   => ($data_absensi_transaksi_by_month_and_userid->num_rows() > 0)?array_sum($flag_terlambat):0,
            'total_hari_kerja'  => $get_hari_kerja->num_rows(),
            'total_tidak_lengkap'  => $tidak_lengkap->num_rows(),
            'total_terlambat'    => $get_terlambat->num_rows() ? $get_terlambat->num_rows() : 0,
            'flag_weekend'      => $flag_weekend ? $flag_weekend : 0,
            'total_tidak_lengkap_and_keterangan_null' => $tidak_lengkap_and_keterangan_null->num_rows(),
            'total_terlambat_and_keterangan_null' => $terlambat_and_keterangan_null->num_rows(),
            'signature'         => $signature,
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('absensi/detail_absensi', $data);
        $this->load->view('kalimantan/footer');

    }

    // public function detail_absensi($signature)
    // {
    //     $bulan = $this->input->get('bulan');
    //     echo $bulan;
    //     $data_absensi = $this->model_absensi->data_absensi_transaksi_by_signature($signature);
    //     foreach ($data_absensi->result() as $key) {
    //         $flag_terlambat[] = $key->flag_terlambat;
    //         $tanggal = $key->tanggal;
    //     }

    //     // redirect('absensi/cek_absensi/?bulan='.$bulan);
    //     $get_absensi_by_signature = $this->model_absensi->data_absensi_transaksi_by_signature($signature);
    //     $get_userid = $get_absensi_by_signature->row()->userid;
    //     $flag_weekend = $this->model_absensi->get_m_karyawan($get_userid)->row()->flag_weekend;
        
    //     // get terlambar
    //     $get_terlambat = $this->model_absensi->get_absensi_transaksi_terlambat($signature);

    //     // get absensi transaksi yang tidak lengkap
    //     $tidak_lengkap = $this->model_absensi->get_absensi_transaksi_tidak_lengkap($signature, $flag_weekend);
                
    //     // get total hari kerja tanpa weekend
    //     $get_hari_kerja = $this->model_absensi->get_absensi_transaksi_hari_kerja($signature, $flag_weekend, $get_userid);
        
    //     // get absensi transaksi yang tidak lengkap tapi keterangan nya null
    //     $tidak_lengkap_and_keterangan_null = $this->model_absensi->get_absensi_transaksi_tidak_lengkap_and_keterangan_null($signature, $flag_weekend);

    //     // get absensi transaksi yang tidak lengkap tapi keterangan nya null
    //     $terlambat_and_keterangan_null = $this->model_absensi->get_absensi_transaksi_terlambat_and_keterangan_null($signature, $flag_weekend);

    //     $data = [
    //         'title'             => "Report Absensi Karyawan",
    //         'data_absensi'      => $data_absensi,
    //         'total_hadir'       => $data_absensi->num_rows(),
    //         'total_terlambat'   => ($data_absensi->num_rows() > 0)?array_sum($flag_terlambat):0,
    //         'total_hari_kerja'  => $get_hari_kerja->num_rows(),
    //         'total_tidak_lengkap'  => $tidak_lengkap->num_rows(),
    //         'total_terlambat'    => $get_terlambat->num_rows() ? $get_terlambat->num_rows() : 0,
    //         'flag_weekend'      => $flag_weekend ? $flag_weekend : 0,
    //         'total_tidak_lengkap_and_keterangan_null' => $tidak_lengkap_and_keterangan_null->num_rows(),
    //         'total_terlambat_and_keterangan_null' => $terlambat_and_keterangan_null->num_rows(),
    //         'signature'         => $signature,
    //     ];

    //     $this->navbar($data);
    //     $this->load->view('kalimantan/header_full_width', $data);
    //     $this->load->view('management_claim/css');
    //     $this->load->view('absensi/detail_absensi', $data);
    //     $this->load->view('kalimantan/footer');
    // }

    // public function detail_absensi($signature)
    // {
    //     $data_absensi = $this->model_absensi->data_absensi_transaksi_by_signature($signature);
    //     foreach ($data_absensi->result() as $key) {
    //         $flag_terlambat[] = $key->flag_terlambat;
    //         $tanggal = $key->tanggal;
    //     }

    //     $bulan = date('Y', strtotime($tanggal))."-".date('m', strtotime($tanggal));

    //     redirect('absensi/cek_absensi/?bulan='.$bulan);


    //     // // get total hari kerja tanpa weekend
    //     // $get_hari_kerja = $this->model_absensi->get_absensi_transaksi_hari_kerja_no_userid($signature);
    //     // // get absensi transaksi yang membutuhkan input keterangan
    //     // $get_data_need_keterangan = $this->model_absensi->get_absensi_transaksi_no_information($signature);
        
    //     // $data = [
    //     //     'title'             => "Report Absensi Karyawan",
    //     //     'url'               => "absensi/report",
    //     //     'data_absensi'      => $data_absensi,
    //     //     'total_hadir'       => $data_absensi->num_rows(),
    //     //     'total_terlambat'   => ($data_absensi->num_rows() > 0)?array_sum($flag_terlambat):0,
    //     //     'total_hari_kerja'  => $get_hari_kerja->num_rows(),
    //     //     'total_hari_no_information'  => $get_data_need_keterangan->num_rows(),
    //     //     'signature'         => $signature,
    //     //     'bulan'             => $bulan
    //     // ];

    //     // $this->navbar($data);
    //     // $this->load->view('kalimantan/header_full_width', $data);
    //     // $this->load->view('management_claim/css');
    //     // $this->load->view('absensi/detail_absensi', $data);
    //     // $this->load->view('kalimantan/footer');
    // }
    
    public function signature_digital()
    {    
        $signature = $this->uri->segment('3');
        $url = $this->uri->segment('4');
        $data = [
            'title'           => 'Digital Signature',
            'url'             => "absensi/signature_digital_proses/$signature/$url",
        ];

        $this->navbar($data);
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('absensi/signature_digital', $data);
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
        redirect("absensi/$url/$signature");
    }

}
?>