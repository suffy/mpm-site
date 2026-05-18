<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ruang_meeting extends MY_Controller 
{
    function ruang_meeting()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv', 'download'));
        $this->load->model(array('model_outlet_transaksi', 'model_ruang_meeting'));

        // if ($this->session->userdata('username') != 'suffy' && $this->session->userdata('username') != 'milla') { // jika dp
        //     redirect('management_office');
        //     die;
        // }

        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->created_by = $this->session->userdata('id');
        $this->created_by_username = $this->session->userdata('username');
        $this->tanggal_hari_ini  = date("Y-m-d");
    }

    function index()
    {
        $this->dashboard_room();
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

    function dashboard_room()
    {
        // if($this->input->post('from'))
        // {    
        //     $advanced['from']   = $this->input->post('from');
        //     $advanced['to']     = $this->input->post('to');
        // }else{
        //     $advanced = null;
        // }

        $data = [
            'title'                      => 'Ruang Meeting',
            'data_ruang_meeting_semut'   => $this->model_ruang_meeting->get_data_booking('1'),
            'data_ruang_meeting_gajah'   => $this->model_ruang_meeting->get_data_booking('2'),
            'url'                        => 'ruang_meeting/booking_room',
            // 'from'                       => ($this->input->post('from')) ? $this->input->post('from') : '',
            // 'to'                         => ($this->input->post('to')) ? $this->input->post('to') : '',
            'get_data_booking_group_tanggal' => $this->model_ruang_meeting->get_data_booking_group_tanggal()
        ];

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('ruang_meeting/dashboard_room', $data);
        $this->load->view('kalimantan/footer');
    }

    public function booking_room()
    {
        $date = $this->input->get('date');
        $get_data_ruang_meeting = $this->model_ruang_meeting->get_data_ruang_meeting($date, '');

        $data = [
            'title'                      => 'Booking Room',
            'data_ruang_meeting'         => $get_data_ruang_meeting,
            'data_ruang_meeting_semut'   => $this->model_ruang_meeting->get_data_ruang_meeting($date,'1'),
            'data_ruang_meeting_gajah'   => $this->model_ruang_meeting->get_data_ruang_meeting($date,'2'),
            'get_count_booking_semut'    => $this->model_ruang_meeting->get_count_booking($date,'1')->row()->count,
            'get_count_booking_gajah'    => $this->model_ruang_meeting->get_count_booking($date,'2')->row()->count,
            'url'                        => 'ruang_meeting/booking_room?date=' . $date,
            'url_add'                    => 'ruang_meeting/add_booking?date=' . $date,
            'date'                       => $date,
            'session'                    => $this->created_by_username
        ];
        //  var_dump($data); die;

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('ruang_meeting/booking_room', $data);
        $this->load->view('kalimantan/footer');
    }

    public function add_booking()
    {
        $date = $this->input->get('date');
        $jam_id = $this->input->post('jam_id');
        $room = $this->input->post('room');
        $signature = 'MEETING-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if($jam_id == null){
            $this->session->set_flashdata("pesan", "Pilih Jam Meeting Terlebih Dahulu.");
            redirect('ruang_meeting/booking_room?date='.$date,'refresh');
            die;
        }

        $count = count($jam_id);

        for ($i=0; $i < $count ; $i++) { 
            $data = [
                'tanggal'    => $date,
                'room_id'    => $room,
                'jam_id'     => $jam_id[$i],
                'booking_by' => $this->created_by,
                'created_at' => $this->created_at,
                'created_by' => $this->created_by,
                'signature'  => $signature
            ];

            $this->db->insert('site.booking', $data);

        }
        $this->session->set_flashdata("pesan_success", "Booking Ruang Meeting Berhasil.");
        redirect('ruang_meeting/booking_room?date='.$date,'refresh');
        die;
    }

    public function delete_booking($id, $signature)
    {
        $get_booking = $this->model_ruang_meeting->get_booking_by_signature($signature);
        $tanggal = $get_booking->row()->tanggal;
        // var_dump($get_booking);die;
        if ($get_booking->num_rows() > 0) {
            $id_notulen = $get_booking->row()->id_notulen;
            // echo "id_notulen: " . $id_notulen;die;
            if ($id_notulen != null) {
                $this->session->set_flashdata("pesan", "Delete Failed. Karena Notulen telah dibuat.");
                redirect('ruang_meeting/booking_room?date='.$tanggal);
            }

            $data = [
                "updated_at" => $this->created_at,
                "updated_by" => $this->created_by,
                "deleted" => '1',
                "deleted_by" => $this->created_by,
            ];

            $this->db->where('id', $id);
            $this->db->where('signature', $signature);
            $this->db->update('site.booking', $data);

            $this->session->set_flashdata("pesan_success", "Delete Successfully");
            redirect('ruang_meeting/booking_room?date='.$tanggal);
        }else{
            $this->session->set_flashdata("pesan", "Delete Failed. Data tidak ditemukan");
        }

    }

    public function notulen($signature='')
    {
        if ($signature == '') {
            $signature = $this->input->post('signature');
        }

        $peserta = $this->input->post('peserta');
        $notulen = $this->input->post('notulen');


        if ($peserta) {

            $konversi_peserta = implode(",", $peserta);

            // upload attachment
            $init_upload = $this->attachment_config();    
        
            if ($this->upload->do_upload('attachment')) 
            {
                $upload_data = $this->upload->data();
                $filename = $upload_data['file_name'];
            }else
            {
                // var_dump($this->upload->display_errors());
                // die;
            };
            
            $data = [
                "peserta" => $konversi_peserta,
                "isi_notulen" => $notulen,
                "file" => $filename,
                "created_at" => $this->created_at,
                "created_by" => $this->created_by
            ];

            $id_notulen = $this->model_ruang_meeting->insert_notulen($data);

            $data_update = [
                "id_notulen" => $id_notulen,
                "updated_at" => $this->created_at,
                "updated_by" => $this->created_by
            ];

            $this->model_ruang_meeting->update_booking($data_update, $signature);
            
            $this->session->set_flashdata("pesan_success", "insert notulen berhasil");
            redirect('ruang_meeting/notulen/'.$signature);  
        }

        
        $get_booking = $this->model_ruang_meeting->get_booking_by_signature($signature);
        if ($get_booking->num_rows() > 0) {
            $id_notulen = $get_booking->row()->id_notulen;
            // echo "id_notulen: " . $id_notulen;
            if ($id_notulen != null) {

                // cek hak akses buka tabel site.booking_notulen
                $cek_notulen = $this->model_ruang_meeting->get_notulen_by_id_and_peserta($id_notulen, $this->created_by);
                if ($cek_notulen->num_rows() == 0) {
                    $this->session->set_flashdata("pesan", "Anda Tidak Memiliki Akses Untuk Melihat Notulen Ini..");
                    redirect('ruang_meeting');
                }

                $get_notulen = $this->model_ruang_meeting->get_notulen($id_notulen);
                $peserta = explode(",", $get_notulen->row()->peserta);
            }else{
                $get_notulen = null;
            }
        }


        // cek hak akses buka tabel site.booking
        // $cek_booking = $this->model_ruang_meeting->get_booking_by_signature_and_created_by($signature, $this->created_by);
        // if ($cek_booking->num_rows() == 0) {
        //     $this->session->set_flashdata("pesan", "Anda Tidak Memiliki Akses Untuk Melihat Notulen Ini.");
        //     redirect('ruang_meeting');
        // }

        $data = [
            'title'         => 'Notulensi',
            'get_notulen'   => $get_notulen,
            'url'           => 'ruang_meeting/notulen',
            'url_delete'    => 'ruang_meeting/delete_notulen',
            'signature'     => $signature,
        ];  

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('ruang_meeting/notulen', $data);
        $this->load->view('kalimantan/footer');
    }

    public function delete_notulen()
    {
        $signature = $this->input->post('signature');
        $id_notulen = $this->input->post('id_notulen');

        $data = [
            "id_notulen" => null,
            "updated_at" => $this->created_at,
            "updated_by" => $this->created_by
        ];

        $update_booking = $this->model_ruang_meeting->update_booking($data, $signature);
        if (!$update_booking) {
            // echo 'A'; die;
            $this->session->set_flashdata("pesan", "delete notulen gagal. Anda bukan yang mengajukan ruang meeting sebelumnya");
            redirect('ruang_meeting/notulen/'.$signature);  
        }

        $data_notulen = [
            "deleted_at" => $this->created_at,
            "deleted_by" => $this->created_by
        ];
        
        $update_notulen = $this->model_ruang_meeting->update_notulen($data_notulen, $id_notulen);
        if (!$update_booking) {
            $this->session->set_flashdata("pesan", "delete notulen gagal.Anda bukan pembuat notulen sebelumnya");
            redirect('ruang_meeting/notulen/'.$signature);  
        }
        $this->session->set_flashdata("pesan_success", "delete notulen berhasil");
        redirect('ruang_meeting/notulen/'.$signature);  
    }

    public function attachment_config()
    {
        if (!is_dir('./assets/uploads/ruang_meeting/')) {
            @mkdir('./assets/uploads/ruang_meeting/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/ruang_meeting/';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $proses = $this->upload->initialize($config);
        return $proses;
    }
}