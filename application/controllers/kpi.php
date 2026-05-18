<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kpi extends MY_Controller
{
    function kpi()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv', 'download'));
        $this->load->model(array('model_outlet_transaksi', 'model_kpi'));
    }
    function index()
    {
        $this->manage_activity();
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
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function manage_activity()
    {
        $data = [
            'title'                     => 'KPI S&P',
            'get_event'                 => $this->model_kpi->get_event(),
            'get_market_survey'         => $this->model_kpi->get_market_survey(),
            'get_visibility'            => $this->model_kpi->get_visibility(),
            'get_pemerataan_product'    => $this->model_kpi->get_pemerataan_product(),
            'url_event'                 => 'kpi/event_tambah',
            'url_market_survey'         => 'kpi/market_survey_tambah',
            'url_visibility'            => 'kpi/visibility_tambah',
            'url_pemerataan'            => 'kpi/pemerataan_product_tambah',
            'url_search'                => 'kpi/workspace',
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/manage_activity', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_data()
    {
        $data = [
            'title'                             => 'KPI S&P | Master Data',
            'get_master_team_member'            => $this->model_kpi->get_master_team_member(),
            'get_master_team_member_struktural' => $this->model_kpi->get_master_team_member_struktural(),
            'get_master_perhitungan'            => $this->model_kpi->get_master_perhitungan(),
            'get_master_brand'                  => $this->model_kpi->get_master_brand(),
            'url_master_team_member'            => 'kpi/master_team_member_tambah',
            'url_master_team_member_struktural' => 'kpi/master_team_member_struktural_tambah',
            'url_master_perhitungan'            => 'kpi/master_perhitungan_tambah',
            'url_master_brand'                  => 'kpi/master_brand_tambah',
        ];

        $this->navbar($data);
        // $this->load->view('management_kpi/css');
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/master_data', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_team_member_tambah()
    {
        $userid     = $this->input->post('user_event');
        $rank       = $this->input->post('rank');
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature  = 'team-member' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $cek_existing = $this->model_kpi->get_master_team_member($userid);

        if ($cek_existing->num_rows() > 0) {
            // proses update data
            $data = [
                'userid'        => $userid,
                'rank'          => $rank,
                'signature'     => $signature,
                'updated_at'    => $created_at,
                'updated_by'    => $this->session->userdata('id'),
            ];
            $this->db->where('userid', $userid);
            $this->db->update('site.kpi_master_team_member', $data);
            $this->session->set_flashdata("pesan_success_master_team_member", "Update Data Successfully");
            redirect('kpi/master_data#master-team-member');
            die;
        }else{
            // proses insert
            $data = [
                'userid'        => $userid,
                'rank'          => $rank,
                'signature'     => $signature,
                'created_at'    => $created_at,
                'created_by'    => $this->session->userdata('id'),
                'updated_at'    => $created_at,
                'updated_by'    => $this->session->userdata('id'),
            ];

            $this->db->insert('site.kpi_master_team_member', $data);
            $this->session->set_flashdata("pesan_success_master_team_member", "Insert Data Successfully");
            redirect('kpi/master_data#master-team-member');
            die;
        }
    }

    public function master_perhitungan_tambah()
    {
        $category   = $this->input->post('category');
        $kuartal   = $this->input->post('kuartal');
        $parameter   = $this->input->post('parameter');
        $minimum_target   = $this->input->post('minimum_target');
        $bobot   = $this->input->post('bobot');
        $rank   = $this->input->post('rank');

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature  = 'master-perhitungan-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $cek_existing = $this->model_kpi->get_master_perhitungan($category, $kuartal, $rank);

        if ($cek_existing->num_rows() > 0) {
            // proses update data
            $data = [
                'kuartal'         => $kuartal,
                'parameter'       => $parameter,
                'min_target'      => $minimum_target,
                'bobot'           => $bobot,
                'signature'       => $signature,
                'updated_at'      => $created_at,
                'updated_by'      => $this->session->userdata('id'),
            ];

            $this->db->where('category', $category);
            $this->db->where('kuartal', $kuartal);
            $this->db->where('rank', $rank);
            $this->db->update('site.kpi_master_perhitungan', $data);

            $this->session->set_flashdata("pesan_success_master_perhitungan", "Update Data Successfully");
            redirect('kpi/master_data#master-perhitungan');
            die;
        }else{
            // proses insert
            $data = [
                'category'        => $category,
                'kuartal'         => $kuartal,
                'parameter'       => $parameter,
                'min_target'      => $minimum_target,
                'bobot'           => $bobot,
                'rank'            => $rank,
                'signature'       => $signature,
                'created_at'      => $created_at,
                'created_by'      => $this->session->userdata('id'),
                'updated_at'      => $created_at,
                'updated_by'      => $this->session->userdata('id'),
            ];
            $this->db->insert('site.kpi_master_perhitungan', $data);
            $this->session->set_flashdata("pesan_success_master_perhitungan", "Insert Data Successfully");
            redirect('kpi/master_data#master-perhitungan');
            die;
        }
    }

    public function master_team_member_struktural_tambah()
    {
        $userid             = $this->input->post('team_member');
        $userid_approval    = $this->input->post('team_member_approval');
        $created_at         = $this->model_outlet_transaksi->timezone();
        $signature          = 'team-member_struktural' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $cek_existing = $this->model_kpi->get_master_team_member_struktural_by_userid($userid);
        if ($cek_existing->num_rows() > 0) {
            // update
            $data = [
                'userid'            => $userid,
                'userid_approval'   => $userid_approval,
                'signature'         => $signature,
                'updated_at'        => $created_at,
                'updated_by'        => $this->session->userdata('id'),
            ];
            $this->db->where('userid', $userid);
            $this->db->update('site.kpi_master_team_member_struktural', $data);
            $this->session->set_flashdata("pesan_success_master_team_member_struktural", "Update Data Successfully");
            redirect('kpi/master_data#master-team-member-struktural', 'refresh');
        }else{
            // insert
            $data = [
                'userid'            => $userid,
                'userid_approval'   => $userid_approval,
                'signature'         => $signature,
                'created_at'        => $created_at,
                'created_by'        => $this->session->userdata('id'),
                'updated_at'        => $created_at,
                'updated_by'        => $this->session->userdata('id'),
            ];
            $this->db->insert('site.kpi_master_team_member_struktural', $data);
            $this->session->set_flashdata("pesan_success_master_team_member_struktural", "Insert Data Successfully");
            redirect('kpi/master_data#master-team-member-struktural', 'refresh');
        }
    }

    public function master_brand_tambah()
    {
        $brand   = $this->input->post('brand');
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature  = 'brand' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $cek_existing = $this->model_kpi->get_master_brand($brand);
        if ($cek_existing->num_rows() > 0) {

            // update
            $data = [
                'brand'         => $brand,
                'signature'     => $signature,
                'updated_at'    => $created_at,
                'updated_by'    => $this->session->userdata('id'),
            ];

            $this->db->where('brand', $brand);
            $this->db->update('site.kpi_master_brand', $data);
            $this->session->set_flashdata("pesan_success_master_brand", "Update Data Successfully");
            redirect('kpi/master_data#master_brand', 'refresh');
            die;
        }else{
            // insert
            $data = [
                'brand'         => $brand,
                'signature'     => $signature,
                'created_at'    => $created_at,
                'created_by'    => $this->session->userdata('id'),
                'updated_at'    => $created_at,
                'updated_by'    => $this->session->userdata('id'),
            ];
            $this->db->insert('site.kpi_master_brand', $data);
            $this->session->set_flashdata("pesan_success_master_brand", "Submit Data Successfully");
            redirect('kpi/master_data#master_brand', 'refresh');
            die;
        }
    }

    public function update_event_user($signature)
    {
        // cek signature
        $cek = $this->model_kpi->get_event_user_by_signature($signature);
        if (!$cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan_gagal_event_user", "Update Data Gagal");
            redirect('kpi/manage_activity#event_user', 'refresh');
        }

        $data = [
            'flag_active'   => ($this->model_kpi->get_event_user_by_signature($signature)->row()->flag_active == 1) ? 0 : 1,
            'updated_at'    => $this->model_outlet_transaksi->timezone(),
            'updated_by'    => $this->session->userdata('id'),
        ];
        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_master_team_member', $data);
        $this->session->set_flashdata("pesan_success_event_user", "Update Data Successfully");
        redirect('kpi/manage_activity#event_user', 'refresh');
    }

    public function verifikasi_event($signature)
    {
        // cek signature event
        $get_data = $this->model_kpi->get_event_by_signature($signature);
        if (!$get_data->num_rows() > 0) {

            if ($signature == 'list')
            {
                $no_pelaporan_event = '';
                $event_from = '';
                $event_to = '';
                $lokasi_event = '';
                $nama_event = '';
                $omzet = 0;
                $attach_1 = '';
                $attach_2 = '';
                $attach_3 = '';
                $status = '';
                $nama_status = '';
                $cost_ratio = '';
                $crowd = 0;
                $brand = '';
                $biaya = 0;
                $signature = '';
                $username = '';
                $name = '';
                $email = '';
                $jabatan = '';
            }else{
                $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Data not found");
                redirect('kpi/manage_activity#event', 'refresh');
            }
        }else{
            $no_pelaporan_event = $get_data->row()->no_pelaporan_event;
            $event_from = $get_data->row()->event_from;
            $event_to = $get_data->row()->event_to;
            $lokasi_event = $get_data->row()->lokasi_event;
            $nama_event = $get_data->row()->nama_event;
            $omzet = $get_data->row()->omzet;
            $attach_1 = $get_data->row()->attach_1;
            $attach_2 = $get_data->row()->attach_2;
            $attach_3 = $get_data->row()->attach_3;
            $status = $get_data->row()->status;
            $nama_status = $get_data->row()->nama_status;
            $cost_ratio = $get_data->row()->cost_ratio;
            $crowd = $get_data->row()->crowd;
            $brand = $get_data->row()->brand;
            $biaya = $get_data->row()->biaya;
            $signature = $get_data->row()->signature;
            $username = $get_data->row()->username;
            $name = $get_data->row()->name;
            $email = $get_data->row()->email;
            $jabatan = $get_data->row()->jabatan;
        }

        $data = [
            'title'             => 'Verifikasi Event',
            'url'               => 'kpi/verifikasi_event_update',
            'signature'         => $signature,
            'no_pelaporan_event'=> $no_pelaporan_event,
            'event_from'        => $event_from,
            'event_to'          => $event_to,
            'lokasi_event'      => $lokasi_event,
            'nama_event'        => $nama_event,
            'omzet'             => $omzet,
            'attach_1'          => $attach_1,
            'attach_2'          => $attach_2,
            'attach_3'          => $attach_3,
            'status'            => $status,
            'nama_status'       => $nama_status,
            'cost_ratio'        => $cost_ratio,
            'crowd'             => $crowd,
            'brand'             => $brand,
            'biaya'             => $biaya,
            'username'          => $username,
            'name'              => $name,
            'email'             => $email,
            'jabatan'           => $jabatan,
            'get_event'         => $this->model_kpi->get_event(),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/verifikasi_event', $data);
        $this->load->view('kalimantan/footer');
    }

    public function generate_report($kuartal = '', $tahun = '')
    {
        $kuartal    = $this->input->get('kuartal');
        $tahun      = trim($this->input->get('tahun'));
        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');

        # insert data jika ada nilai kuartal dan tahun
        if ($kuartal && $tahun) {
            // $this->model_kpi->insert_generate_report_event($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->insert_generate_report_rank_spo($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->insert_generate_report_rank_asps($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->insert_generate_report_rank_rsph($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->insert_generate_report_log($kuartal, $tahun, $created_at, $created_by);
        }

        $data = [
            'title'                 => 'Generate Report',
            'url'                   => 'generate_report',
            'get_report_point_tim'  => $this->model_kpi->get_generate_report_tim_by_userid($kuartal, $tahun, $created_at, $created_by),
            'get_perhitungan'       => $this->model_kpi->get_perhitungan('event', $kuartal),
            'get_report_point'      => $this->model_kpi->get_generate_report_by_userid($kuartal, $tahun, $created_at, $created_by),
            'get_review'            => $this->model_kpi->get_event_tim_by_userid($kuartal, $tahun, $created_by),
        ];
        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/dashboard_generate_report', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_perhitungan_detail($signature)
    {
        // echo "signature : ".$signature;
        $get_master_perhitungan_by_signature = $this->model_kpi->get_master_perhitungan_by_signature($signature);
        if ($get_master_perhitungan_by_signature->num_rows() > 0) {
            $id_header = $get_master_perhitungan_by_signature->row()->id;
            $category = $get_master_perhitungan_by_signature->row()->category;
            $parameter = $get_master_perhitungan_by_signature->row()->parameter;
            $min_target = $get_master_perhitungan_by_signature->row()->min_target;
            $signature = $get_master_perhitungan_by_signature->row()->signature;
        }else{
            $this->session->set_flashdata("pesan", "Proses Anda Gagal. Data not found");
            redirect('kpi/manage_activity#master-perhitungan', 'refresh');
        }

        $data = [
            'title'     => 'Detail Perhitungan KPI',
            'url'       => 'kpi/master_perhitungan_detail_tambah',
            'category'  => $category,
            'parameter' => $parameter,
            'min_target'=> $min_target,
            'signature' => $signature,
            'id_header' => $id_header,
            'get_data'  => $this->model_kpi->get_master_perhitungan_detail_by_id_header($id_header),
        ];
        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/master_perhitungan_detail', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_perhitungan_detail_tambah()
    {
        $kpi = $this->input->post('kpi');
        $point = $this->input->post('point');
        $signature = $this->input->post('signature');
        $id_header = $this->input->post('id_header');

        $created_at = $this->model_outlet_transaksi->timezone();

        $cek_existing = $this->model_kpi->get_master_perhitungan_detail_by_id_header_n_kpi($id_header, $kpi);
        if ($cek_existing->num_rows() > 0) {

            // update data
            $data = [
                'point'         => $point,
                'updated_at'    => $created_at,
                'updated_by'    => $this->session->userdata('id'),
            ];

            $this->db->where('kpi', $kpi);
            $this->db->where('id_master_perhitungan', $id_header);
            $this->db->update('site.kpi_master_perhitungan_detail', $data);

            $this->session->set_flashdata("pesan_success", "Update Data Successfully");
            redirect('kpi/master_perhitungan_detail/'.$signature, 'refresh');

        }else{
            for ($i=0; $i <= $kpi; $i++) {
                $cek_existing2 = $this->model_kpi->get_master_perhitungan_detail_by_id_header_n_kpi($id_header, $i);
                if ($cek_existing2->num_rows() <= 0) {
                    // insert
                    $data = [
                        'id_master_perhitungan' => $id_header,
                        'kpi'                   => $i,
                        'point'                 => $point,
                        'created_at'            => $created_at,
                        'created_by'            => $this->session->userdata('id'),
                        'updated_at'            => $created_at,
                        'updated_by'            => $this->session->userdata('id'),
                    ];
                    $this->db->insert('site.kpi_master_perhitungan_detail', $data);

                    $this->session->set_flashdata("pesan_success", "Insert Data Successfully");
                }
            }
            redirect('kpi/master_perhitungan_detail/'.$signature, 'refresh');
        }
    }

    public function verifikasi_event_update()
    {
        $signature = $this->input->post('signature');
        $approval = $this->input->post('approval');

        $status_supervisi = $this->input->post('status_supervisi');
        $keterangan_supervisi = $this->input->post('keterangan_supervisi');

        $created_at = $this->model_outlet_transaksi->timezone();

        // cek signature
        $cek = $this->model_kpi->get_event_by_signature($signature);
        if (!$cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Data not found");
            redirect('kpi/verifikasi_event/'.$signature, 'refresh');
        }else{
            $userid_pelaksana = $cek->row()->created_by;
        }

        // cek hak akses
        $cek_hak_approval = $this->model_kpi->get_master_team_member_struktural_by_userid_and_head($userid_pelaksana,$this->session->userdata('id'));
        if (!$cek_hak_approval->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Anda tidak diijinkan memverifikasi data ini");
            redirect('kpi/verifikasi_event/'.$signature, 'refresh');
        }

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kpi/';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload("foto_supervisi"))
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];
        }else{
            $filename = "";
        };

        // echo "status_supervisi : ".$status_supervisi;

        if ($status_supervisi == 1) {
            // cek apakah foto_supervisi dan keterangan_supervisi ada
            if ($filename == "" || $keterangan_supervisi == "") {
                $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Anda belum mengisi foto supervisi dan keterangan supervisi");
                redirect('kpi/verifikasi_event/'.$signature, 'refresh');
            }
        }

        // die;
        $data = [
            'status'                => $approval,
            'nama_status'           => $this->model_kpi->nama_status_event($approval),
            'status_supervisi_spo'  => $status_supervisi,
            'foto_supervisi_spo'    => $filename,
            'keterangan_supervisi_spo'   => $keterangan_supervisi,
            'supervisi_spo_at'      => $created_at,
            'supervisi_spo_by'      => $this->session->userdata('id'),
            'updated_at'            => $created_at,
            'updated_by'            => $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_event', $data);

        $this->session->set_flashdata("pesan_success", "Verifikasi data berhasil");
        redirect('kpi/verifikasi_event/'.$signature, 'refresh');
    }

    public function workspace(){
        $data = [
            'title'                 => 'Input Pelaporan By Bulan',
            'get_workspace'         => $this->model_kpi->get_workspace(),
            'url'                   => 'kpi/workspace_tambah',
            'url_search'            => 'kpi/workspace',
        ];

        $this->navbar($data);
        // $this->load->view('management_kpi/css');
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/workspace', $data);
        $this->load->view('kalimantan/footer');
    }

    public function workspace_tambah(){
        $periode    = $this->input->post('periode');
        $bulan      = substr($periode, 5, 2);
        $tahun      = substr($periode, 0, 4);
        $kategori   = $this->input->post('kategori');
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature  = 'Workspace-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $get_workspace = $this->model_kpi->get_workspace($tahun, $bulan, $kategori);
        if ($get_workspace->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Anda tidak dapat menambahkan kategori yang sama di periode ini. Silahkan manage workspace atau tambah di periode lain");
            redirect('kpi/workspace', 'refresh');
        }

        $data = [
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'kategori'      => $kategori,
            'status_review' => 1,
            'nama_status_review' => 'Pending Review',
            'signature'     => $signature,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id'),
        ];

        $this->db->insert('site.workspace_list', $data);
        $this->session->set_flashdata("pesan_success", "Submit Data Successfully");
        redirect('kpi/workspace', 'refresh');

    }

    public function manage_workspace($signature)
    {
        $get_workspace_by_signature = $this->model_kpi->get_workspace_by_signature($signature);
        if ($get_workspace_by_signature->num_rows() > 0) {
            $kategori       = $get_workspace_by_signature->row()->kategori;
            $id_workspace   = $get_workspace_by_signature->row()->id;
            $tahun          = $get_workspace_by_signature->row()->tahun;
            $bulan          = $get_workspace_by_signature->row()->bulan;
        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/workspace', 'refresh');
            die;
        }

        if ($kategori == 'event')
        {
            $view = 'management_kpi/event';
            $data = [
                'title'                 => 'Pelaporan Event',
                'get_data'              => $this->model_kpi->get_event($id_workspace),
                'url'                   => 'kpi/event_tambah',
                'url_search'            => 'kpi/manage_workspace',
                'id_workspace'          => $id_workspace,
                'signature_workspace'   => $signature,
                'get_tahun'             => $tahun,
                'get_bulan'             => $bulan,
            ];
        }elseif($kategori == 'market_survey')
        {
            $view = 'management_kpi/market_survey';
            $data = [
                'title'                 => 'Market Survey',
                'get_data'              => $this->model_kpi->get_market_survey_by_id_workspace($id_workspace),
                'url'                   => 'kpi/market_survey_tambah',
                'url_search'            => 'kpi/manage_workspace',
                'id_workspace'          => $id_workspace,
                'signature_workspace'   => $signature,
                'get_tahun'             => $tahun,
                'get_bulan'             => $bulan,
            ];
        }elseif($kategori == 'channel_baru')
        {
            $view = 'management_kpi/channel_baru';
            $data = [
                'title'                 => 'Pengembangan Channel Baru',
                'get_data'              => $this->model_kpi->get_channel_baru_by_id_workspace($id_workspace),
                'url'                   => 'kpi/channel_baru_tambah',
                'url_search'            => 'kpi/manage_workspace',
                'id_workspace'          => $id_workspace,
                'signature_workspace'   => $signature,
                'get_tahun'             => $tahun,
                'get_bulan'             => $bulan,
            ];
        }

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view($view, $data);
        $this->load->view('kalimantan/footer');

    }

    public function delete_workspace($signature)
    {
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.workspace_list', $data);

        $this->session->set_flashdata("pesan_success", "Delete workspace Successfully");
        redirect('kpi/workspace', 'refresh');
    }

    public function event_tambah()
    {
        $nama_event     = $this->input->post('nama_event');
        $from           = $this->input->post('from');
        $to             = $this->input->post('to');
        $lokasi_event   = $this->input->post('lokasi_event');
        $ref_perdin     = $this->input->post('ref_perdin');
        $omzet          = $this->input->post('omzet');
        $biaya          = $this->input->post('biaya');
        $cost_ratio     = $this->input->post('cost_ratio');
        $crowd          = $this->input->post('crowd');
        $brand          = $this->input->post('brand');
        $created_at     = $this->model_outlet_transaksi->timezone();
        $signature      = 'Event-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $status = 1;
        $nama_status_event = $this->model_kpi->nama_status_event($status);

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kpi/';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach1'))
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['file_name'];
        }else{
            $this->session->set_flashdata("pesan", "Upload Failed with meesage : " . $this->upload->display_errors());
            redirect('kpi/manage_activity#event', 'refresh');
            die;
        };

        if ($this->upload->do_upload('attach2'))
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['file_name'];
        }else{
            $this->session->set_flashdata("pesan", "Upload Failed with meesage : " . $this->upload->display_errors());
            redirect('kpi/manage_activity#event', 'refresh');
            die;
        };

        if ($this->upload->do_upload('attach3'))
        {
            $upload_data = $this->upload->data();
            $filename_attach3 = $upload_data['file_name'];
        }else{
            $this->session->set_flashdata("pesan", "Upload Failed with meesage : " . $this->upload->display_errors());
            redirect('kpi/manage_activity#event', 'refresh');
            die;
        };

        // cek apakah user termasuk event_user
        $get_master_team_member = $this->model_kpi->get_master_team_member($this->session->userdata('id'));

        if (!$get_master_team_member->num_rows() > 0) {
                $this->session->set_flashdata("pesan", "Submit event gagal. Karena anda tidak terdaftar sebagai event user");
                redirect('kpi/manage_activity#event', 'refresh');
        }

        $no_pelaporan_event = $this->model_kpi->generate($created_at);

        $data = [
            'no_pelaporan_event'    => $no_pelaporan_event,
            'event_from'            => $from,
            'event_to'              => $to,
            'lokasi_event'          => $lokasi_event,
            'nama_event'            => $nama_event,
            'referensi_rpd'         => $ref_perdin,
            'omzet'                 => $omzet,
            'biaya'                 => $biaya,
            'cost_ratio'            => $cost_ratio,
            'crowd'                 => $crowd,
            'brand'                 => $brand,
            'attach_1'              => $filename_attach1,
            'attach_2'              => $filename_attach2,
            'attach_3'              => $filename_attach3,
            'status'                => $status,
            'nama_status'           => $nama_status_event,
            'created_by'            => $this->session->userdata('id'),
            'created_at'            => $created_at,
            'signature'             => $signature,
        ];

        $this->db->insert('site.kpi_event', $data);
        $this->session->set_flashdata("pesan_success", "Pelaporan Event Berhasil");
        redirect('kpi/manage_activity#event', 'refresh');
    }

    public function delete_event($signature, $signature_workspace){
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_event', $data);

        $id_workspace = $this->model_kpi->get_workspace_by_signature($signature_workspace)->row()->id;

        $this->update_event_summary($id_workspace);

        $this->session->set_flashdata("pesan_success", "Delete event Successfully");
        redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
    }

    public function edit_event($signature_event, $signature_workspace){

        $get_workspace_by_signature = $this->model_kpi->get_workspace_by_signature($signature_workspace);
        if ($get_workspace_by_signature->num_rows() > 0) {
            $id_workspace = $get_workspace_by_signature->row()->id;
            $get_tahun = $get_workspace_by_signature->row()->tahun;
            $get_bulan = $get_workspace_by_signature->row()->bulan;
        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
        }

        $get_event_by_signature = $this->model_kpi->get_event_by_signature($signature_event);
        if ($get_event_by_signature->num_rows() > 0) {
            $id_event       = $get_event_by_signature->row()->id;
            $nama_event     = $get_event_by_signature->row()->nama_event;
            $lokasi_event   = $get_event_by_signature->row()->lokasi_event;
            $omzet          = $get_event_by_signature->row()->omzet;
            $biaya          = $get_event_by_signature->row()->biaya;
            $cost_ratio     = $get_event_by_signature->row()->cost_ratio;
            $crowd          = $get_event_by_signature->row()->crowd;
            $brand          = $get_event_by_signature->row()->brand;
            $event_from     = $get_event_by_signature->row()->event_from;
            $event_to       = $get_event_by_signature->row()->event_to;
            $attach_1       = $get_event_by_signature->row()->attach_1;
            $attach_2       = $get_event_by_signature->row()->attach_2;
            $attach_3       = $get_event_by_signature->row()->attach_3;
            $status         = $get_event_by_signature->row()->status;
            $nama_status    = $get_event_by_signature->row()->nama_status;
        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
        }

        $data = [
            'title'                 => 'Pelaporan Event',
            'get_data'              => $get_event_by_signature,
            'url'                   => 'kpi/update_event',
            'url_search'            => 'kpi/manage_workspace',
            'id_workspace'          => $id_workspace,
            'signature_workspace'   => $signature_workspace,
            'signature_event'       => $signature_event,
            'get_tahun'             => $get_tahun,
            'get_bulan'             => $get_bulan,
            'nama_event'            => $nama_event,
            'lokasi_event'          => $lokasi_event,
            'omzet'                 => $omzet,
            'event_from'            => $event_from,
            'event_to'              => $event_to,
            'biaya'                 => $biaya,
            'omzet'                 => $omzet,
            'cost_ratio'            => $cost_ratio,
            'crowd'                 => $crowd,
            'brand'                 => $brand,
            'attach_1'              => $attach_1,
            'attach_2'              => $attach_2,
            'attach_3'              => $attach_3,
            'status'                => $status,
            'nama_status'           => $nama_status,
            'id_event'              => $id_event
        ];

        $this->navbar($data);
        $this->load->view('management_kpi/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/edit_event', $data);
        $this->load->view('kalimantan/footer');

    }

    public function update_event()
    {
        $nama_event     = $this->input->post('nama_event');
        $from           = $this->input->post('from');
        $to             = $this->input->post('to');
        $id_workspace   = $this->input->post('id_workspace');
        $lokasi_event   = $this->input->post('lokasi_event');
        $ref_perdin     = $this->input->post('ref_perdin');
        $omzet          = $this->input->post('omzet');
        $signature_workspace    = $this->input->post('signature_workspace');
        $signature_event    = $this->input->post('signature_event');
        $status         = $this->input->post('status');
        $nama_status    = $this->input->post('nama_status');
        $id_event       = $this->input->post('id_event');
        $biaya          = $this->input->post('biaya');
        $cost_ratio     = $this->input->post('cost_ratio');
        $crowd          = $this->input->post('crowd');
        $brand          = $this->input->post('brand');

        $created_at = $this->model_outlet_transaksi->timezone();

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kpi/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach_1'))
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach1 = $this->input->post('attach1_old');
        };

        if ($this->upload->do_upload('attach_2'))
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach2 = $this->input->post('attach2_old');
        };

        if ($this->upload->do_upload('attach_3'))
        {
            $upload_data = $this->upload->data();
            $filename_attach3 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach3 = $this->input->post('attach3_old');
        };

        $data = [
            'event_from'            => $from,
            'event_to'              => $to,
            'lokasi_event'          => $lokasi_event,
            'nama_event'            => $nama_event,
            'referensi_rpd'         => $ref_perdin,
            'omzet'                 => $omzet,
            'biaya'                 => $biaya,
            'cost_ratio'            => $cost_ratio,
            'crowd'                 => $crowd,
            'brand'                 => $brand,
            'attach_1'              => $filename_attach1,
            'attach_2'              => $filename_attach2,
            'attach_3'              => $filename_attach3,
            'status'                => $status,
            'nama_status'           => $nama_status,
            'updated_by'            => $this->session->userdata('id'),
            'updated_at'            => $created_at,
        ];

        $this->db->where('id', $id_event);
        $this->db->update('site.kpi_event', $data);

        $this->update_event_summary($id_workspace);

        $this->session->set_flashdata("pesan_success", "Update Event Berhasil");
        redirect('kpi/edit_event/'.$signature_event.'/'.$signature_workspace, 'refresh');
    }

    public function review_event($signature_event, $signature_workspace)
    {

        $get_event_by_signature = $this->model_kpi->get_event_by_signature($signature_event);
        if ($get_event_by_signature->num_rows() > 0) {
            $no_pelaporan_event = $get_event_by_signature->row()->no_pelaporan_event;
            $id_workspace       = $get_event_by_signature->row()->id_workspace;
            $id_event           = $get_event_by_signature->row()->id;
            $nama_event         = $get_event_by_signature->row()->nama_event;
            $event_from         = $get_event_by_signature->row()->event_from;
            $event_to           = $get_event_by_signature->row()->event_to;
            $lokasi_event       = $get_event_by_signature->row()->lokasi_event;
            $referensi_rpd      = $get_event_by_signature->row()->referensi_rpd;
            $omzet              = $get_event_by_signature->row()->omzet;
            $attach_1           = $get_event_by_signature->row()->attach_1;
            $attach_2           = $get_event_by_signature->row()->attach_2;
            $attach_3           = $get_event_by_signature->row()->attach_3;

        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
            die;
        }

        $data = [
            'title'                 => 'Review Event | Berikan Masukkan Anda disini',
            'url'                   => 'kpi/review_event_tambah',
            'url_search'            => 'kpi/workspace',
            'signature_workspace'   => $signature_workspace,
            'signature_event'       => $signature_event,
            'id_workspace'          => $id_workspace,
            'id_event'              => $id_event,
            'no_pelaporan_event'    => $no_pelaporan_event,
            'get_data_review'       => $this->model_kpi->get_review_event_by_id_event($id_event),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/review_event', $data);
        $this->load->view('kalimantan/footer');

    }

    public function review_event_tambah()
    {
        $review     = $this->input->post('review');
        $point      = $this->input->post('point');
        $id_event   = $this->input->post('id_event');
        $id_workspace = $this->input->post('id_workspace');
        $created_at = $this->model_outlet_transaksi->timezone();

        $signature_event = $this->input->post('signature_event');
        $signature_workspace = $this->input->post('signature_workspace');

        $signature = 'Review-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'review'    => $review,
            'point'     => $point,
            'id_event'  => $id_event,
            'id_workspace'  => $id_workspace,
            'created_at'=> $created_at,
            'created_by'=> $this->session->userdata('id'),
            'signature' => $signature
        ];

        $this->db->insert('site.kpi_review_event', $data);

        $update = [
            'status'        => 2,
            'nama_status'   => 'REVIEWED',
        ];
        $this->db->where('id', $id_event);
        $this->db->update('site.kpi_event', $update);

        // echo "id_event : " . $id_event;
        // die;

        $update_count_review = $this->model_kpi->update_count_review($id_workspace);
        $update_average_point = $this->model_kpi->update_average_point($id_workspace);

        $this->session->set_flashdata("pesan_success", "Review Event Berhasil");
        redirect('kpi/review_event/'.$signature_event.'/'.$signature_workspace, 'refresh');

    }

    public function delete_review_event($signature, $signature_workspace, $signature_event)
    {
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_review_event', $data);

        $id_workspace = $this->model_kpi->get_workspace_by_signature($signature_workspace)->row()->id;

        $update_count_event = $this->model_kpi->update_count_event($id_workspace);
        $update_count_review = $this->model_kpi->update_count_review($id_workspace);
        $update_average_point = $this->model_kpi->update_average_point($id_workspace);

        // var_dump($id_workspace);
        // var_dump($update_count_event);
        // var_dump($update_count_review);
        // var_dump($update_average_point);

        // die;

        $this->session->set_flashdata("pesan_success", "Delete event Successfully");
        redirect('kpi/review_event/'.$signature_event.'/'.$signature_workspace, 'refresh');
    }

    public function market_survey_tambah(){
        $nama_toko              = $this->input->post('nama_toko');
        $pic_toko               = $this->input->post('pic_toko');
        $area                   = $this->input->post('area');
        $alamat                 = $this->input->post('alamat');
        $survey_from            = $this->input->post('from');
        $survey_to              = $this->input->post('to');
        $keterangan             = $this->input->post('keterangan');

        $status = 1;
        $nama_status = $this->model_kpi->nama_status_event($status);

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'MarketSurvey-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kpi/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach1'))
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach1 = '';
        };

        if ($this->upload->do_upload('attach2'))
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach2 = '';
        };

        if ($this->upload->do_upload('attach3'))
        {
            $upload_data = $this->upload->data();
            $filename_attach3 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach3 = '';
        };

        $no_pelaporan = $this->model_kpi->generate_market_survey($created_at);

        $data = [
            'no_pelaporan'  => $no_pelaporan,
            'survey_from'   => $survey_from,
            'survey_to'     => $survey_to,
            'nama_toko'     => $nama_toko,
            'pic_toko'      => $pic_toko,
            'area'          => $area,
            'alamat'        => $alamat,
            'keterangan'    => $keterangan,
            'attach_1'      => $filename_attach1,
            'attach_2'      => $filename_attach2,
            'attach_3'      => $filename_attach3,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'created_by'    => $this->session->userdata('id'),
            'created_at'    => $created_at,
            'signature'     => $signature,
        ];

        $this->db->insert('site.kpi_market_survey', $data);

        $this->session->set_flashdata("pesan_success", "Pelaporan Market Survey Berhasil");
        redirect('kpi#survey', 'refresh');
    }

    public function delete_market_survey($signature, $signature_workspace){
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_market_survey', $data);

        $id_workspace = $this->model_kpi->get_workspace_by_signature($signature_workspace)->row()->id;

        $update_count_market_survey = $this->model_kpi->update_count_market_survey($id_workspace);
        $update_count_review_market_survey = $this->model_kpi->update_count_review_market_survey($id_workspace);
        $update_average_point_market_survey = $this->model_kpi->update_average_point_market_survey($id_workspace);

        $this->session->set_flashdata("pesan_success", "Delete Market Survey Successfully");
        redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
    }

    public function edit_market_survey($signature_market_survey, $signature_workspace){
        $get_workspace_by_signature = $this->model_kpi->get_workspace_by_signature($signature_workspace);
        if ($get_workspace_by_signature->num_rows() > 0) {
            $id_workspace = $get_workspace_by_signature->row()->id;
            $get_tahun = $get_workspace_by_signature->row()->tahun;
            $get_bulan = $get_workspace_by_signature->row()->bulan;
        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
            die;
        }

        $get_market_survey_by_signature = $this->model_kpi->get_market_survey_by_signature($signature_market_survey);
        if ($get_market_survey_by_signature->num_rows() > 0) {
            $id_market_survey   = $get_market_survey_by_signature->row()->id;
            $tanggal            = $get_market_survey_by_signature->row()->tanggal;
            $nama_toko          = $get_market_survey_by_signature->row()->nama_toko;
            $alamat             = $get_market_survey_by_signature->row()->alamat;
            $keterangan         = $get_market_survey_by_signature->row()->keterangan;
            $attach_1           = $get_market_survey_by_signature->row()->attach_1;
            $attach_2           = $get_market_survey_by_signature->row()->attach_2;
            $status             = $get_market_survey_by_signature->row()->status;
            $nama_status        = $get_market_survey_by_signature->row()->nama_status;
            $signature_market_survey = $get_market_survey_by_signature->row()->signature;
        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
            die;
        }

        $data = [
            'title'                 => 'Edit Market Survey',
            'get_data'              => $get_market_survey_by_signature,
            'url'                   => 'kpi/update_market_survey',
            'url_search'            => 'kpi/manage_workspace',
            'id_workspace'          => $id_workspace,
            'signature_workspace'   => $signature_workspace,
            'get_tahun'             => $get_tahun,
            'get_bulan'             => $get_bulan,
            'nama_toko'             => $nama_toko,
            'alamat'                => $alamat,
            'tanggal'               => $tanggal,
            'keterangan'            => $keterangan,
            'attach_1'              => $attach_1,
            'attach_2'              => $attach_2,
            'status'                => $status,
            'nama_status'           => $nama_status,
            'signature_market_survey' => $signature_market_survey,
            'id_market_survey'      => $id_market_survey
        ];

        $this->navbar($data);
        $this->load->view('management_kpi/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/edit_market_survey', $data);
        $this->load->view('kalimantan/footer');
    }

    public function update_market_survey(){
        $nama_toko          = $this->input->post('nama_toko');
        $alamat             = $this->input->post('alamat');
        $tanggal            = $this->input->post('tanggal');
        $keterangan         = $this->input->post('keterangan');
        $id_workspace       = $this->input->post('id_workspace');
        $signature_market_survey = $this->input->post('signature_market_survey');
        $signature_workspace= $this->input->post('signature_workspace');
        $status             = $this->input->post('status');
        $nama_status        = $this->input->post('nama_status');
        $id_market_survey   = $this->input->post('id_market_survey');

        $created_at = $this->model_outlet_transaksi->timezone();

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kpi/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach_1'))
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach1 = $this->input->post('attach1_old');
        };

        if ($this->upload->do_upload('attach_2'))
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach2 = $this->input->post('attach2_old');
        };

        $data = [
            'tanggal'       => $tanggal,
            'alamat'        => $alamat,
            'nama_toko'     => $nama_toko,
            'keterangan'    => $keterangan,
            'attach_1'      => $filename_attach1,
            'attach_2'      => $filename_attach2,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'updated_by'    => $this->session->userdata('id'),
            'updated_at'    => $created_at
        ];

        $this->db->where('id', $id_market_survey);
        $this->db->update('site.kpi_market_survey', $data);

        $update_count_event = $this->model_kpi->update_count_event($id_workspace);
        $this->session->set_flashdata("pesan_success", "Market Survey updated successfully");
        redirect('kpi/edit_market_survey/'.$signature_market_survey.'/'.$signature_workspace, 'refresh');
    }

    public function review_market_survey($signature_market_survey, $signature_workspace){

        $get_market_survey_by_signature = $this->model_kpi->get_market_survey_by_signature($signature_market_survey);
        if ($get_market_survey_by_signature->num_rows() > 0) {

            $no_pelaporan   = $get_market_survey_by_signature->row()->no_pelaporan;
            $tanggal        = $get_market_survey_by_signature->row()->tanggal;
            $lokasi         = $get_market_survey_by_signature->row()->lokasi;
            $keterangan     = $get_market_survey_by_signature->row()->keterangan;
            $attach_1       = $get_market_survey_by_signature->row()->attach_1;
            $attach_2       = $get_market_survey_by_signature->row()->attach_2;
            $status         = $get_market_survey_by_signature->row()->status;
            $nama_status    = $get_market_survey_by_signature->row()->nama_status;
            $id_workspace   = $get_market_survey_by_signature->row()->id_workspace;
            $id_market_survey= $get_market_survey_by_signature->row()->id;

        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
            die;
        }

        $data = [
            'title'                 => 'Review Market Survey | Berikan Masukkan Anda disini',
            'url'                   => 'kpi/review_market_survey_tambah',
            'url_search'            => 'kpi/workspace',
            'signature_workspace'   => $signature_workspace,
            'signature_market_survey' => $signature_market_survey,
            'id_workspace'          => $id_workspace,
            'id_market_survey'      => $id_market_survey,
            'no_pelaporan'          => $no_pelaporan,
            'get_data_review'       => $this->model_kpi->get_review_market_survey_by_id_market_survey($id_market_survey),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/review_market_survey', $data);
        $this->load->view('kalimantan/footer');

    }

    public function review_market_survey_tambah(){
        $review     = $this->input->post('review');
        $point      = $this->input->post('point');
        $id_market_survey   = $this->input->post('id_market_survey');
        $id_workspace = $this->input->post('id_workspace');
        $created_at = $this->model_outlet_transaksi->timezone();

        $signature_market_survey = $this->input->post('signature_market_survey');
        $signature_workspace = $this->input->post('signature_workspace');

        $signature = 'Review-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'review'            => $review,
            'point'             => $point,
            'id_market_survey'  => $id_market_survey,
            'id_workspace'      => $id_workspace,
            'created_at'        => $created_at,
            'created_by'        => $this->session->userdata('id'),
            'signature'         => $signature
        ];

        $this->db->insert('site.kpi_review_market_survey', $data);

        $update = [
            'status'        => 2,
            'nama_status'   => 'REVIEWED',
        ];
        $this->db->where('id', $id_market_survey);
        $this->db->update('site.kpi_market_survey', $update);

        $update_count_review_market_survey = $this->model_kpi->update_count_review_market_survey($id_workspace);
        $update_average_point_market_survey = $this->model_kpi->update_average_point_market_survey($id_workspace);

        $this->session->set_flashdata("pesan_success", "Review Event Berhasil");
        redirect('kpi/review_market_survey/'.$signature_market_survey.'/'.$signature_workspace, 'refresh');

    }

    public function reload_workspace($signature_workspace)
    {
        $get_workspace_by_signature = $this->model_kpi->get_workspace_by_signature($signature_workspace);
        if ($get_workspace_by_signature->num_rows() > 0) {
            $kategori       = $get_workspace_by_signature->row()->kategori;
            $id_workspace   = $get_workspace_by_signature->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/workspace', 'refresh');
            die;
        }

        if ($kategori == 'event') {
            $update_count_event = $this->model_kpi->update_count_event($id_workspace);
            $update_count_review = $this->model_kpi->update_count_review($id_workspace);
            $update_average_point = $this->model_kpi->update_average_point($id_workspace);
        }

        if ($kategori == 'market_survey') {
            $update_count_market_survey = $this->model_kpi->update_count_market_survey($id_workspace);
            $update_count_review_market_survey = $this->model_kpi->update_count_review_market_survey($id_workspace);
            $update_average_point_market_survey = $this->model_kpi->update_average_point_market_survey($id_workspace);
        }

        $this->session->set_flashdata("pesan_success", "reload successfully");
        redirect('kpi/workspace', 'refresh');

    }

    public function delete_review_market_survey($signature, $signature_workspace, $signature_market_survey){
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_review_market_survey', $data);

        $id_workspace = $this->model_kpi->get_workspace_by_signature($signature_workspace)->row()->id;

        $update_count_market_survey = $this->model_kpi->update_count_market_survey($id_workspace);
        $update_count_review_market_survey = $this->model_kpi->update_count_review_market_survey($id_workspace);
        $update_average_point_market_survey = $this->model_kpi->update_average_point_market_survey($id_workspace);

        $this->session->set_flashdata("pesan_success", "Delete event Successfully");
        redirect('kpi/review_market_survey/'.$signature_market_survey.'/'.$signature_workspace, 'refresh');
    }

    public function channel_baru_tambah(){
        $nama_toko              = $this->input->post('nama_toko');
        $alamat                 = $this->input->post('alamat');
        $sektor                 = $this->input->post('sektor');
        $tanggal                = $this->input->post('tanggal');
        $value_transaksi        = $this->input->post('value_transaksi');
        $site_code              = $this->input->post('site_code');
        $signature_workspace    = $this->input->post('signature_workspace');
        $id_workspace           = $this->input->post('id_workspace');

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'noo-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kpi/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach1'))
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach1 = '';
        };

        if ($this->upload->do_upload('attach2'))
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach2 = '';
        };

        $no_pelaporan = $this->model_kpi->generate_channel_baru($created_at);

        $data = [
            'id_workspace'          => $id_workspace,
            'no_pelaporan'          => $no_pelaporan,
            'tanggal'               => $tanggal,
            'nama_toko'             => $nama_toko,
            'alamat'                => $alamat,
            'value_transaksi'       => $value_transaksi,
            'sektor'                => $sektor,
            'site_code'             => $site_code,
            'attach_1'              => $filename_attach1,
            'attach_2'              => $filename_attach2,
            'status'                => 1,
            'nama_status'           => 'PENDING REVIEW',
            'created_by'            => $this->session->userdata('id'),
            'created_at'            => $created_at,
            'signature'             => $signature,
        ];
        $this->db->insert('site.kpi_channel_baru', $data);

        $this->update_channel_baru($id_workspace);
        $this->session->set_flashdata("pesan_success", "Pelaporan Channel Baru Berhasil");
        redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
    }

    public function edit_channel_baru($signature_channel_baru, $signature_workspace){
        $get_workspace_by_signature = $this->model_kpi->get_workspace_by_signature($signature_workspace);
        if ($get_workspace_by_signature->num_rows() > 0) {
            $id_workspace = $get_workspace_by_signature->row()->id;
            $get_tahun = $get_workspace_by_signature->row()->tahun;
            $get_bulan = $get_workspace_by_signature->row()->bulan;
        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
            die;
        }

        $get_channel_baru_by_signature = $this->model_kpi->get_channel_baru_by_signature($signature_channel_baru);
        if ($get_channel_baru_by_signature->num_rows() > 0) {
            $id_channel_baru    = $get_channel_baru_by_signature->row()->id;
            $tanggal            = $get_channel_baru_by_signature->row()->tanggal;
            $nama_toko          = $get_channel_baru_by_signature->row()->nama_toko;
            $alamat             = $get_channel_baru_by_signature->row()->alamat;
            $sektor             = $get_channel_baru_by_signature->row()->sektor;
            $site_code          = $get_channel_baru_by_signature->row()->site_code;
            $value_transaksi    = $get_channel_baru_by_signature->row()->value_transaksi;
            $attach_1           = $get_channel_baru_by_signature->row()->attach_1;
            $attach_2           = $get_channel_baru_by_signature->row()->attach_2;
            $status             = $get_channel_baru_by_signature->row()->status;
            $nama_status        = $get_channel_baru_by_signature->row()->nama_status;
            $signature_channel  = $get_channel_baru_by_signature->row()->signature;
        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
            die;
        }

        $data = [
            'title'                 => 'Edit Channel Baru',
            'get_data'              => $get_channel_baru_by_signature,
            'url'                   => 'kpi/update_market_survey',
            'url_search'            => 'kpi/manage_workspace',
            'id_workspace'          => $id_workspace,
            'signature_workspace'   => $signature_workspace,
            'get_tahun'             => $get_tahun,
            'get_bulan'             => $get_bulan,
            'nama_toko'             => $nama_toko,
            'alamat'                => $alamat,
            'sektor'                => $sektor,
            'site_code'             => $site_code,
            'tanggal'               => $tanggal,
            'value_transaksi'       => $value_transaksi,
            'signature_channel'     => $signature_channel,
            'attach_1'              => $attach_1,
            'attach_2'              => $attach_2,
            'status'                => $status,
            'nama_status'           => $nama_status,
            'id_channel_baru'       => $id_channel_baru
        ];

        $this->navbar($data);
        $this->load->view('management_kpi/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/edit_channel_baru', $data);
        $this->load->view('kalimantan/footer');
    }

    public function delete_channel_baru($signature, $signature_workspace){
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_channel_baru', $data);

        $id_workspace = $this->model_kpi->get_workspace_by_signature($signature_workspace)->row()->id;

        $this->update_channel_baru($id_workspace);

        $this->session->set_flashdata("pesan_success", "Delete Channel Baru Successfully");
        redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
    }

    public function update_channel_baru($id_workspace)
    {
        $update_count_channel_baru = $this->model_kpi->update_count_channel_baru($id_workspace);
        $update_count_review_channel_baru = $this->model_kpi->update_count_review_channel_baru($id_workspace);
        $update_average_point_channel_baru = $this->model_kpi->update_average_point_channel_baru($id_workspace);
        // die;
    }

    public function update_event_summary($id_workspace)
    {
        $update_count_event = $this->model_kpi->update_count_event($id_workspace);
        $update_count_review = $this->model_kpi->update_count_review($id_workspace);
        $update_average_point = $this->model_kpi->update_average_point($id_workspace);
        // die;
    }

    public function update_market_survey_summary($id_workspace)
    {
        $update_count_market_survey = $this->model_kpi->update_count_market_survey($id_workspace);
        $update_count_review_market_survey = $this->model_kpi->update_count_review_market_survey($id_workspace);
        $update_average_point_market_survey = $this->model_kpi->update_average_point_market_survey($id_workspace);
        // die;
    }

    public function review_channel_baru($signature_channel_baru, $signature_workspace){

        $get_channel_baru_by_signature = $this->model_kpi->get_channel_baru_by_signature($signature_channel_baru);
        if ($get_channel_baru_by_signature->num_rows() > 0) {

            $no_pelaporan   = $get_channel_baru_by_signature->row()->no_pelaporan;
            $nama_toko      = $get_channel_baru_by_signature->row()->nama_toko;
            $tanggal        = $get_channel_baru_by_signature->row()->tanggal;
            $lokasi         = $get_channel_baru_by_signature->row()->lokasi;
            $keterangan     = $get_channel_baru_by_signature->row()->keterangan;
            $attach_1       = $get_channel_baru_by_signature->row()->attach_1;
            $attach_2       = $get_channel_baru_by_signature->row()->attach_2;
            $status         = $get_channel_baru_by_signature->row()->status;
            $nama_status    = $get_channel_baru_by_signature->row()->nama_status;
            $id_workspace   = $get_channel_baru_by_signature->row()->id_workspace;
            $id_channel_baru= $get_channel_baru_by_signature->row()->id;

        }else{
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('kpi/manage_workspace/'.$signature_workspace, 'refresh');
            die;
        }

        $data = [
            'title'                 => 'Review Channel Baru | Berikan Masukkan Anda disini',
            'url'                   => 'kpi/review_channel_baru_tambah',
            'url_search'            => 'kpi/workspace',
            'signature_workspace'   => $signature_workspace,
            'signature_channel_baru' => $signature_channel_baru,
            'id_workspace'          => $id_workspace,
            'id_channel_baru'       => $id_channel_baru,
            'no_pelaporan'          => $no_pelaporan,
            'get_data_review'       => $this->model_kpi->get_review_channel_baru_by_id_channel_baru($id_channel_baru),
        ];

        $this->navbar($data);
        $this->load->view('management_kpi/css');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/review_channel_baru', $data);
        $this->load->view('kalimantan/footer');

    }

    public function review_channel_baru_tambah(){
        $review     = $this->input->post('review');
        $point      = $this->input->post('point');
        $id_channel_baru   = $this->input->post('id_channel_baru');
        $id_workspace = $this->input->post('id_workspace');
        $created_at = $this->model_outlet_transaksi->timezone();

        $signature_channel_baru = $this->input->post('signature_channel_baru');
        $signature_workspace = $this->input->post('signature_workspace');

        $signature = 'Review-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'review'            => $review,
            'point'             => $point,
            'id_channel_baru'   => $id_channel_baru,
            'id_workspace'      => $id_workspace,
            'created_at'        => $created_at,
            'created_by'        => $this->session->userdata('id'),
            'signature'         => $signature
        ];

        $this->db->insert('site.kpi_review_channel_baru', $data);

        $update = [
            'status'        => 2,
            'nama_status'   => 'REVIEWED',
        ];
        $this->db->where('id', $id_channel_baru);
        $this->db->update('site.kpi_channel_baru', $update);

        $this->update_channel_baru($id_workspace);

        $this->session->set_flashdata("pesan_success", "Review Event Berhasil");
        redirect('kpi/review_channel_baru/'.$signature_channel_baru.'/'.$signature_workspace, 'refresh');

    }

    public function delete_review_channel_baru($signature, $signature_workspace, $signature_channel_baru){
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_review_channel_baru', $data);

        $id_workspace = $this->model_kpi->get_workspace_by_signature($signature_workspace)->row()->id;

        $this->update_channel_baru($id_workspace);

        $this->session->set_flashdata("pesan_success", "Delete event Successfully");
        redirect('kpi/review_channel_baru/'.$signature_channel_baru.'/'.$signature_workspace, 'refresh');
    }

    public function site_code(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');
        $userid = 297;
        $tahun = date('Y');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/site_code?token=11f3a8a682c1e8d097ae60d72ecf07c7&X-API-KEY=123&userid=297&tahun=$tahun",
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
            $datasbranch = $array_response['data'];
            // var_dump($datasbranch);die;
            echo "<option value=''> -- Pilih DP -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["site_code"] ."' >";
                echo $tiapbranch["site_code"]." - ".$tiapbranch["nama_comp"];
                echo "</option>";
            }
        }
    }

    public function master_team_member()
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
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_team_member?" . http_build_query($params),
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
                echo "<option value='". $r["userid"] ."' name='" . $r["name"] . "' >";
                echo $r["name"] . " ___ " . $r["email"]. " ___ " . $r["jabatan"];
                echo "</option>";
            }
        }
    }

    public function master_brand()
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
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_brand_gt?" . http_build_query($params),
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
            echo "<option value=''> -- Pilih Brand -- </option>";

            foreach ($result as $key => $r)
            {
                echo "<option value='". $r["brand"] . "' >";
                echo $r["brand"];
                echo "</option>";
            }
        }
    }

    public function verifikasi_market_survey($signature)
    {
        // cek signature market_survey
        $get_data = $this->model_kpi->get_market_survey_by_signature($signature);
        if (!$get_data->num_rows() > 0) {

            if ($signature == 'list')
            {
                $signature = '';
            } else {
                $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Data not found");
                redirect('kpi/manage_activity#market_survey', 'refresh');
            }
        } else {
            $signature = $get_data->row()->signature;
        }

        $data = [
            'title'             => 'Verifikasi Market Survey',
            'url'               => 'kpi/verifikasi_market_survey_update',
            'signature'         => $signature,
            'get_data'          => $get_data,
            'get_data_table'    => $this->model_kpi->get_market_survey(),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/verifikasi_market_survey', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_market_survey_update()
    {
        $signature = $this->input->post('signature');
        $approval = $this->input->post('approval');

        $created_at = $this->model_outlet_transaksi->timezone();

        // cek signature
        $cek = $this->model_kpi->get_market_survey_by_signature($signature);
        if (!$cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Data not found");
            redirect('kpi/verifikasi_market_survey/'.$signature, 'refresh');
        }else{
            $userid_pelaksana = $cek->row()->created_by;
        }

        // cek hak akses
        $cek_hak_approval = $this->model_kpi->get_master_team_member_struktural_by_userid_and_head($userid_pelaksana,$this->session->userdata('id'));
        if (!$cek_hak_approval->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Anda tidak diijinkan memverifikasi data ini");
            redirect('kpi/verifikasi_market_survey/'.$signature, 'refresh');
        }

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        // die;
        $data = [
            'status'            => $approval,
            'nama_status'       => $this->model_kpi->nama_status_event($approval),
            'verifikasi_at'     => $created_at,
            'verifikasi_by'     => $this->session->userdata('id'),
            'updated_at'        => $created_at,
            'updated_by'        => $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_market_survey', $data);

        $this->session->set_flashdata("pesan_success", "Verifikasi data berhasil");
        redirect('kpi/verifikasi_market_survey/'.$signature, 'refresh');
    }

    public function dashboard_surveyor()
    {
        $kuartal    = $this->input->get('kuartal');
        $tahun      = trim($this->input->get('tahun'));
        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');

        # cek kuartal dan tahun 
        if ($kuartal && $tahun) {
            $this->model_kpi->insert_dashboard_market_survey_rank_spo($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->insert_dashboard_market_survey_rank_asps($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->update_dashboard_market_survey_point($kuartal, $created_at, $created_by);
            $this->model_kpi->insert_dashboard_market_survey_log($kuartal, $tahun, $created_at, $created_by);
        }

        $data = [
            'title'     => 'Dashboard Market Survey',
            'url'       => 'dashboard_surveyor',
            'get_report_point_tim'  => $this->model_kpi->get_dashboard_market_survey_tim_by_userid($kuartal, $tahun, $created_at, $created_by),
            'get_perhitungan'       => $this->model_kpi->get_perhitungan('surveyor', $kuartal),
            'get_report_point'      => $this->model_kpi->get_dashboard_market_survey_by_userid($kuartal, $tahun, $created_at, $created_by),
            'get_review'            => $this->model_kpi->get_market_survey_tim_by_userid($kuartal, $tahun, $created_by),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/dashboard_market_survey', $data);
        $this->load->view('kalimantan/footer');
    }

    public function pemerataan_product_tambah()
    {
        $status = 1;
        $nama_status = $this->model_kpi->nama_status_event($status);

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'PemerataanProduct-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kpi/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach1'))
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach1 = '';
        };

        if ($this->upload->do_upload('attach2'))
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach2 = '';
        };

        if ($this->upload->do_upload('attach3'))
        {
            $upload_data = $this->upload->data();
            $filename_attach3 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach3 = '';
        };

        $no_pelaporan = $this->model_kpi->generate_pemerataan_product($created_at);

        $data = [
            'no_pelaporan'  => $no_pelaporan,
            'tanggal'       => $_POST['from'],
            'nama_toko'     => $_POST['nama_toko'],
            'alamat'        => $_POST['alamat'],
            'product_kompetitor'    => $_POST['product_kompetitor'],
            'product_existing'      => $_POST['product_existing'  ],
            'attach_1'      => $filename_attach1,
            'attach_2'      => $filename_attach2,
            'attach_3'      => $filename_attach3,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'created_by'    => $this->session->userdata('id'),
            'created_at'    => $created_at,
            'signature'     => $signature,
        ];

        $this->db->insert('site.kpi_pemerataan_product', $data);

        $this->session->set_flashdata("pesan_success", "Pelaporan Pemerataan Product Non OB DP Berhasil");
        redirect('kpi#pemerataan', 'refresh');
    }

    public function verifikasi_pemerataan_product($signature)
    {
        // cek signature pemerataan_product
        $get_data = $this->model_kpi->get_pemerataan_product_by_signature($signature);
        if (!$get_data->num_rows() > 0) {

            if ($signature == 'list')
            {
                $signature = '';
            } else {
                $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Data not found");
                redirect('kpi/manage_activity#pemerataan_product', 'refresh');
            }
        } else {
            $signature = $get_data->row()->signature;
        }

        $data = [
            'title'             => 'Verifikasi Market Survey',
            'url'               => 'kpi/verifikasi_pemerataan_product_update',
            'signature'         => $signature,
            'get_data'          => $get_data,
            'get_data_table'    => $this->model_kpi->get_pemerataan_product(),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/verifikasi_pemerataan_product', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_pemerataan_product_update()
    {
        $signature = $this->input->post('signature');
        $approval = $this->input->post('approval');

        $created_at = $this->model_outlet_transaksi->timezone();

        // cek signature
        $cek = $this->model_kpi->get_pemerataan_product_by_signature($signature);
        if (!$cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Data not found");
            redirect('kpi/verifikasi_pemerataan_product/'.$signature, 'refresh');
        }else{
            $userid_pelaksana = $cek->row()->created_by;
        }

        // cek hak akses
        $cek_hak_approval = $this->model_kpi->get_master_team_member_struktural_by_userid_and_head($userid_pelaksana,$this->session->userdata('id'));
        if (!$cek_hak_approval->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Anda tidak diijinkan memverifikasi data ini");
            redirect('kpi/verifikasi_pemerataan_product/'.$signature, 'refresh');
        }

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        // die;
        $data = [
            'status'            => $approval,
            'nama_status'       => $this->model_kpi->nama_status_event($approval),
            'verifikasi_at'     => $created_at,
            'verifikasi_by'     => $this->session->userdata('id'),
            'updated_at'        => $created_at,
            'updated_by'        => $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_pemerataan_product', $data);

        $this->session->set_flashdata("pesan_success", "Verifikasi data berhasil");
        redirect('kpi/verifikasi_pemerataan_product/'.$signature, 'refresh');
    }

    public function dashboard_pemerataan_product()
    {
        $kuartal    = $this->input->get('kuartal');
        $tahun      = trim($this->input->get('tahun'));
        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');

        # cek kuartal dan tahun 
        if ($kuartal && $tahun) {
            $this->model_kpi->insert_dashboard_pemerataan_product_rank_spo($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->insert_dashboard_pemerataan_product_rank_asps($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->update_dashboard_pemerataan_product_point($kuartal, $created_at, $created_by);
            $this->model_kpi->insert_dashboard_pemerataan_product_log($kuartal, $tahun, $created_at, $created_by);
        }

        $data = [
            'title'     => 'Dashboard Spreading (Pemerataan Product)',
            'url'       => 'dashboard_pemerataan_product',
            'get_report_point_tim'  => $this->model_kpi->get_dashboard_pemerataan_product_tim_by_userid($kuartal, $tahun, $created_at, $created_by),
            'get_perhitungan'       => $this->model_kpi->get_perhitungan('pemerataan_product', $kuartal),
            'get_report_point'      => $this->model_kpi->get_dashboard_pemerataan_product_by_userid($kuartal, $tahun, $created_at, $created_by),
            'get_review'            => $this->model_kpi->get_pemerataan_product_tim_by_userid($kuartal, $tahun, $created_by),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/dashboard_pemerataan_product', $data);
        $this->load->view('kalimantan/footer');
    }

    public function visibility_tambah(){
        foreach ($_POST['brand'] as $brand)
        {
            $checksub[] = $brand;
        }   
        $branding = implode(', ', $checksub);

        $status = 1;
        $nama_status = $this->model_kpi->nama_status_event($status);

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'Visibility-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kpi/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach1'))
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach1 = '';
        };

        if ($this->upload->do_upload('attach2'))
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach2 = '';
        };

        if ($this->upload->do_upload('attach3'))
        {
            $upload_data = $this->upload->data();
            $filename_attach3 = $upload_data['file_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach3 = '';
        };

        $no_pelaporan = $this->model_kpi->generate_visibility($created_at);

        $data = [
            'no_pelaporan'  => $no_pelaporan,
            'tanggal'       => $_POST['from'],
            'nama_toko'     => $_POST['nama_toko'],
            'alamat'        => $_POST['alamat'],
            'branding'      => $branding,
            'attach_1'      => $filename_attach1,
            'attach_2'      => $filename_attach2,
            'attach_3'      => $filename_attach3,
            'status'        => $status,
            'nama_status'   => $nama_status,
            'created_by'    => $this->session->userdata('id'),
            'created_at'    => $created_at,
            'signature'     => $signature,
        ];

        $this->db->insert('site.kpi_visibility', $data);

        $this->session->set_flashdata("pesan_success", "Pelaporan Visibility/Branding OB DP Berhasil");
        redirect('kpi#visibility', 'refresh');
    }

    public function verifikasi_visibility($signature)
    {
        // cek signature visibility
        $get_data = $this->model_kpi->get_visibility_by_signature($signature);
        if (!$get_data->num_rows() > 0) {

            if ($signature == 'list')
            {
                $signature = '';
            } else {
                $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Data not found");
                redirect('kpi/manage_activity#visibility', 'refresh');
            }
        } else {
            $signature = $get_data->row()->signature;
        }

        $data = [
            'title'             => 'Verifikasi Market Survey',
            'url'               => 'kpi/verifikasi_visibility_update',
            'signature'         => $signature,
            'get_data'          => $get_data,
            'get_data_table'    => $this->model_kpi->get_visibility(),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/verifikasi_visibility', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_visibility_update()
    {
        $signature = $this->input->post('signature');
        $approval = $this->input->post('approval');

        $created_at = $this->model_outlet_transaksi->timezone();

        // cek signature
        $cek = $this->model_kpi->get_visibility_by_signature($signature);
        if (!$cek->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Data not found");
            redirect('kpi/verifikasi_visibility/'.$signature, 'refresh');
        }else{
            $userid_pelaksana = $cek->row()->created_by;
        }

        // cek hak akses
        $cek_hak_approval = $this->model_kpi->get_master_team_member_struktural_by_userid_and_head($userid_pelaksana,$this->session->userdata('id'));
        if (!$cek_hak_approval->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Verifikasi Data Gagal. Anda tidak diijinkan memverifikasi data ini");
            redirect('kpi/verifikasi_visibility/'.$signature, 'refresh');
        }

        if (!is_dir('./assets/uploads/kpi/')) {
            @mkdir('./assets/uploads/kpi/', 0777);
        }

        // die;
        $data = [
            'status'            => $approval,
            'nama_status'       => $this->model_kpi->nama_status_event($approval),
            'verifikasi_at'     => $created_at,
            'verifikasi_by'     => $this->session->userdata('id'),
            'updated_at'        => $created_at,
            'updated_by'        => $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.kpi_visibility', $data);

        $this->session->set_flashdata("pesan_success", "Verifikasi data berhasil");
        redirect('kpi/verifikasi_visibility/'.$signature, 'refresh');
    }

    public function dashboard_visibility()
    {
        $kuartal    = $this->input->get('kuartal');
        $tahun      = trim($this->input->get('tahun'));
        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');

        # cek kuartal dan tahun 
        if ($kuartal && $tahun) {
            $this->model_kpi->insert_dashboard_visibility_rank_spo($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->insert_dashboard_visibility_rank_asps($kuartal, $tahun, $created_at, $created_by);
            $this->model_kpi->update_dashboard_visibility_point($kuartal, $created_at, $created_by);
            $this->model_kpi->insert_dashboard_visibility_log($kuartal, $tahun, $created_at, $created_by);
        }

        $data = [
            'title'     => 'Dashboard Spreading (Visibility)',
            'url'       => 'dashboard_visibility',
            'get_report_point_tim'  => $this->model_kpi->get_dashboard_visibility_tim_by_userid($kuartal, $tahun, $created_at, $created_by),
            'get_perhitungan'       => $this->model_kpi->get_perhitungan('visibility', $kuartal),
            'get_report_point'      => $this->model_kpi->get_dashboard_visibility_by_userid($kuartal, $tahun, $created_at, $created_by),
            'get_review'            => $this->model_kpi->get_visibility_tim_by_userid($kuartal, $tahun, $created_by),
        ];

        $this->navbar($data);
        $this->load->view('management_claim/css', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_kpi/dashboard_visibility', $data);
        $this->load->view('kalimantan/footer');
    }

}
?>
