<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_biop extends MY_Controller
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
        $this->load->model(array('model_outlet_transaksi','model_management_biop', 'model_relokasi'));

        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->created_by = $this->session->userdata('id');
    }


    function index()
    {
        $this->ajuan_biop();
    }

    private function view($data, $flag_accordion, $view)
    {
        if ($flag_accordion === 'detail') {
            $data = [
                "navbar"        => $this->navbar($data),
                "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
                "css"           => $this->load->view('management_claim/css', $data),
                "accordion"     => $this->load->view('management_biop/accordion_for_user', $data),
                "view"          => $this->load->view('management_biop/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        } elseif ($flag_accordion === true) {
            $data = [
                "navbar"        => $this->navbar($data),
                "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
                "css"           => $this->load->view('management_claim/css', $data),
                "accordion"     => $this->load->view('management_biop/accordion', $data),
                "view"          => $this->load->view('management_biop/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        } else {
            $data = [
                "navbar"        => $this->navbar($data),
                "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
                "css"           => $this->load->view('management_claim/css', $data),
                // "accordion"     => $this->load->view('management_biop/accordion_for_user', $data),
                "view"          => $this->load->view('management_biop/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        }
        return $data;       
    }

    function filter_data_biop($data)
    {
        if (count($data) <= 0 ) {
            $this->session->set_flashdata('pesan', 'Maaf, Pengajuan biop tidak ditemukan');
            redirect("management_biop/ajuan_biop");
        }
    }

    function filter_data_biop_detail($data)
    {
        if (count($data) < 1) {
            $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan proses karena data biop anda masih kosong. Silakan isi data terlebih dahulu');
            redirect("management_biop/ajuan_biop_proses/$data->signature");
        }
    }

    function get_biop_detail_by_id()
    {
        $id = $_GET['id'];
        $data = $this->model_management_biop->get_biop_detail_by_id($id);
        echo json_encode($data);
    }

    public function ajuan_biop()
    {
        $data = [
            'title'         => 'Klaim Biop',
            'url'           => 'management_biop/ajuan_biop_save',
            'url_proses'    => 'management_biop/ajuan_biop_proses',
            'url_proses'    => 'management_biop/dashboard_routing',
            'url_detail'    => 'management_biop/detail_biop',
            'pic'           => $this->model_management_biop->getAll_User($this->session->userdata('id')),
            'get_data'      => $this->model_management_biop->getAll_ajuan_biop_by_userid($this->session->userdata('id')),
        ];

        $this->render('management_biop/ajuan_biop', $data);
    }

    public function ajuan_biop_save()
    {
        // verifikasi from dan to harus di bulan yang sama
        $from_month = date("m", strtotime($this->input->post('from')));
        $to_month   = date("m", strtotime($this->input->post('to')));

        $current_date = strtotime(date('Y-m-01')); // tanggal 1 bulan ini
        $min_date_allowed = strtotime("-1 months", $current_date); // batas minimal 2 bulan ke belakang

        if ($from_month != $to_month) {
            $this->session->set_flashdata('pesan', 'Proses di tolak ! Tanggal awal dan akhir harus di bulan yang sama');
            redirect("management_biop/ajuan_biop");
        }
        
        if ($this->input->post('from') > $this->input->post('to')) {
            $this->session->set_flashdata('pesan', 'Maaf, Tanggal awal harus lebih kecil dari tanggal akhir');
            redirect("management_biop/ajuan_biop");
        }

        if($this->input->post('from') < date('Y-m-d', $min_date_allowed)){
            $this->session->set_flashdata('pesan', 'Proses ditolak! Tanggal tidak boleh lebih dari 1 bulan ke belakang!.');
            redirect("management_biop/ajuan_biop");
        }

        // verifikasi jabatan, admin_biop, admin_finance, head_finance, atasan1, atasan2
        $jabatan = $this->input->post('jabatan');
        $admin_biop = $this->input->post('admin_biop');
        $admin_finance = $this->input->post('admin_finance');
        $head_finance = $this->input->post('head_finance');
        $atasan1 = $this->input->post('atasan1');
        $atasan2 = $this->input->post('atasan2');

        echo "jabatan : $jabatan, admin_biop : $admin_biop, admin_finance : $admin_finance, head_finance : $head_finance, atasan1 : $atasan1, atasan2 : $atasan2";

        // jika ada yang kosong
        if (empty($jabatan) || empty($admin_biop) || empty($admin_finance) || empty($head_finance) || empty($atasan1) || empty($atasan2)) {
            $this->session->set_flashdata('pesan', 'Maaf, Jabatan, Admin Biop, Admin Finance, Head Finance, Atasan 1, Atasan 2 harus terisi');
            redirect("management_biop/ajuan_biop");
        }

        $data = [
            'no_ajuan'      => $this->model_management_biop->generate($this->created_at),
            'userid'        => $this->input->post('pic'),
            'jabatan'       => $this->input->post('jabatan'),
            'from'          => $this->input->post('from'),
            'to'            => $this->input->post('to'),
            'status'        => '1',
            'nama_status'   => 'pending user',
            'pic_on_duty'   => $this->session->userdata('id'),
            'signature'     => 'ajuan-biop'.rand().md5($this->created_at.rand()),
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];

        $insert_id = $this->model_management_biop->insert_and_getId('site.biop_header',$data);
        redirect('management_biop/ajuan_biop_proses/'.$data['signature']);
    }

    public function ajuan_biop_proses($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);
        // echo "status : ".$get_biop->status;die;

        // authorization on duty
        $userid_onduty = $get_biop->pic_on_duty;
        $pic_name = $get_biop->username_on_duty;
        $nama_status = $get_biop->nama_status;
        if($userid_onduty == $this->session->userdata('id'))
        {
            $is_authorized = true;
        }else{
            $is_authorized = false;
        }

        $data = [
            'title'             => 'Detail Biop',
            'url_input_data'    => 'management_biop/ajuan_biop_data_add',
            'url_delete_data'   => 'management_biop/ajuan_biop_data_delete/',
            'url_proses_user'   => "management_biop/ajuan_biop_proses_save/$signature",
            'url_dashboard'     => "management_biop/ajuan_biop",
            'signature'         => $signature,
            'get_kategori'      => $this->model_management_biop->getAll_kategori_biop(),
            'get_biop'          => $get_biop,
            'get_data_biop'     => $this->model_management_biop->getAll_biop_detail_by_signature($signature),
            'is_authorized'     => $is_authorized,
            'pic_name'          => $pic_name,
            'nama_status'       => $nama_status
        ];

        $data['total_biaya'] = $this->model_management_biop->total_biaya_biop($data['get_data_biop']);
        // $this->view($data, 'detail', 'ajuan_biop_proses');

        $this->render_multiple(
            array(
                'management_biop/accordion_for_user',
                'management_biop/ajuan_biop_proses'
            ),
            $data
        );

    }

    public function ajuan_biop_data_add()
    {
        $signature = $this->input->post('signature');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // echo $get_biop;die;

        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

        // filter berdasarkan status pending pic
        if ($get_biop->status != 1) {
            $this->session->set_flashdata('pesan', 'Maaf, Anda tidak dapat menambahkan data biop dikarenakan status pengajuan saat ini ' . $get_biop['nama_status']);
            redirect("management_biop/ajuan_biop_proses/$signature");
        }
        
        $get_kategori =  $this->model_management_biop->get_kategori_biop_by_id($this->input->post('kategori'));
        $attachment = $this->upload_attachment($get_kategori->nama_kategori);

        if (!empty($from) && !empty($to)) {
            $date1 = new DateTime($from);
            $date2 = new DateTime($to);
            $diff = $date1->diff($date2);

            for ($i=0; $i < $diff->days; $i++) { 
                $data_detail = [
                    'id_biop'       => $get_biop->id,
                    'tanggal'       => date('Y-m-d', strtotime($from . "+$i day")),
                    'id_kategori'   => $get_kategori->id,
                    'nama_kategori' => $get_kategori->nama_kategori,
                    'biaya'         => $this->input->post('biaya') / $diff->days,
                    'keterangan'    => $this->input->post('keterangan'),
                    'keterangan_tempat'    => $this->input->post('keterangan_tempat'),
                    'attachment'    => $attachment,
                    'signature'     => $signature,
                    'created_at'    => $this->created_at,
                    'created_by'    => $this->created_by
                ];

                $insert_id = $this->model_management_biop->insert_and_getId('site.biop_detail',$data_detail);
            }

        } else {
            $data_detail = [
                'id_biop'       => $get_biop->id,
                'tanggal'       => $this->input->post('tanggal'),
                'id_kategori'   => $get_kategori->id,
                'nama_kategori' => $get_kategori->nama_kategori,
                'biaya'         => $this->input->post('biaya'),
                'keterangan'    => $this->input->post('keterangan'),
                'keterangan_tempat'    => $this->input->post('keterangan_tempat'),
                'attachment'    => $attachment,
                'bbm_km'        => $this->input->post('km') !== false ? $this->input->post('km') : null,
                'bbm_liter'     => $this->input->post('liter') !== false ? $this->input->post('liter') : null,
                "jamuan_tempat" => $this->input->post('jamuan_tempat') !== false ? $this->input->post('jamuan_tempat') : null,
                "jamuan_alamat" => $this->input->post('jamuan_alamat') !== false ? $this->input->post('jamuan_alamat') : null,
                "jamuan_jenis"  => $this->input->post('jamuan_jenis') !== false ? $this->input->post('jamuan_jenis') : null,
                "jamuan_nama_perusahaan"    => $this->input->post('jamuan_nama_perusahaan') !== false ? $this->input->post('jamuan_nama_perusahaan') : null,
                "jamuan_pic"    => $this->input->post('jamuan_pic') !== false ? $this->input->post('jamuan_pic') : null,
                "jamuan_pic_jabatan"        => $this->input->post('jamuan_jabatan') !== false ? $this->input->post('jamuan_jabatan') : null,
                "jamuan_jenis_perusahaan"   => $this->input->post('jamuan_jenis_perusahaan') !== false ? $this->input->post('jamuan_jenis_perusahaan') : null,
                'signature'     => $signature,
                'created_at'    => $this->created_at,
                'created_by'    => $this->created_by
            ];

            $insert_id = $this->model_management_biop->insert_and_getId('site.biop_detail',$data_detail);
        }
        
        $this->session->set_flashdata('pesan_success', 'Data biop berhasil ditambahkan');
        redirect("management_biop/ajuan_biop_proses/$signature");
    }

    public function upload_attachment($kategori)
    {
        // upload file
        if (!is_dir('./assets/uploads/management_biop')) {
            @mkdir('./assets/uploads/management_biop', 0777);
        }

        if (!is_dir("./assets/uploads/management_biop/$kategori")) {
            @mkdir("./assets/uploads/management_biop/$kategori", 0777);
        }

        $this->load->library('upload'); // Load librari upload  
        $upload_path = "./assets/uploads/management_biop/$kategori";
        $filename = [];

        // Loop semua file input yang ada di $_FILES
        foreach ($_FILES as $input_name => $file_info) {
            // Pastikan file input ada dan file diupload (nama file tidak kosong)
            if (!empty($file_info['name'])) {
                $ext = pathinfo($file_info['name'], PATHINFO_EXTENSION);
                $original_name = pathinfo($file_info['name'], PATHINFO_FILENAME);

                // Buat nama file baru, gabungkan nama asli + timestamp
                $new_filename = "$input_name - $original_name.$ext";

                $config = [
                    'upload_path'   => $upload_path,
                    'allowed_types' => '*',
                    'max_size'      => '*',
                    'file_name'     => $new_filename,
                ];

                $this->upload->initialize($config);

                if ($this->upload->do_upload($input_name)) {
                    $upload_data = $this->upload->data();
                    $filename[$input_name] = $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    // Bisa simpan log error atau set flashdata
                }
            } else {
                $filename[$input_name] = $this->input->post($input_name.'_old');
            }
        }

        $attachment = json_encode($filename);

        return $attachment;
    }

    public function ajuan_biop_data_delete($signature, $id_detail)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter berdasarkan status pending pic
        if ($get_biop->status != 1) {
            $this->session->set_flashdata('pesan', 'Maaf, Anda tidak dapat menghapus data biop dikarenakan status pengajuan saat ini ' . $get_biop->nama_status);
            redirect("management_biop/ajuan_biop_proses/$signature");
        }

        $update = [
            'deleted_by'    => $this->created_by,
            'deleted_at'    => $this->created_at
        ];

        $params = [
            'MD5(id)' => $id_detail
        ];

        $this->model_management_biop->update('site.biop_detail', $params, $update);

        $this->session->set_flashdata('pesan_success', 'Data biop berhasil dihapus');
        redirect("management_biop/ajuan_biop_proses/$signature");
    }

    public function ajuan_biop_proses_save($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter berdasarkan status pending pic
        if ($get_biop->status != 1) { // jika status nya bukan pending user, maka tidak bisa melakukan proses
            $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan proses karena status pengajuan saat ini ' . $get_biop->nama_status);
            redirect("management_biop/ajuan_biop_proses/$signature");
        }

        $get_data_biop = $this->model_management_biop->getAll_biop_detail_by_signature($signature);
        // filter berdasarkan data biop, jika data kosong tidak bisa melakukan proses
        $this->filter_data_biop_detail($get_data_biop);

        // cek apakah sudah ada signature
        $cek_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        if (!file_exists($cek_signature)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect('management_biop/ajuan_biop/');
            die;
        } else {
            $digital_signature = $this->session->userdata('username').'-signature.png';
        }
        
        // update biop
        $update = [
            'status'        => 2,
            'nama_status'   => 'pending admin biop',
            'total_biaya'   => $this->model_management_biop->total_biaya_biop($get_data_biop),
            'digital_signature'   => $digital_signature,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by
        ];

        // get pic on duty
        $update['pic_on_duty']  = $this->model_management_biop->get_pic_on_duty_by_userid_and_status($this->created_by, $update['nama_status']);
        
        $params = [
            'id'        => $get_biop->id,
            'signature' => $signature
        ];

        $this->model_management_biop->update('site.biop_header', $params, $update);

        // insert log history
        $insert_log = [
            'id_ajuan'      => $get_biop->id,
            'status'        => $update['status'],
            'nama_status'   => $update['nama_status'],
            'pic_on_duty'   => $update['pic_on_duty'],
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];

        $this->model_management_biop->insert_and_getId('site.biop_log',$insert_log);

        $this->send_email_biop($signature, 'user');
    }

    public function dashboard_biop()
    {
        $data = [
            'title'     => 'Dashboard Biop',
            'url'       => 'management_biop/dashboard_routing',
            'get_data'  => $this->model_management_biop->getAll_ajuan_biop(),
        ];

        // $this->view($data, false, 'dashboard_biop');
        $this->render('management_biop/dashboard_biop', $data);
    }

    function dashboard_routing($signature)
    { 
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

        // echo $get_biop->nama_status;
        // die;

        if ($get_biop->nama_status == 'pending user') {
            if ($get_biop->userid == $this->created_by) {
                redirect("management_biop/ajuan_biop_proses/$signature");
            } else {
                // $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses ' . $get_biop->nama_status);
                // redirect("management_biop/dashboard_biop");
                redirect('management_biop/ajuan_biop_proses/'.$signature);
            }
        } else if ($get_biop->nama_status == 'pending admin biop') {
            if ($get_biop->pic_on_duty == $this->created_by) {
                redirect("management_biop/ajuan_biop_verifikasi_admin_claim/$signature");
            } else {
                // $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses ' . $get_biop->nama_status);
                // redirect("management_biop/dashboard_biop");
                redirect('management_biop/ajuan_biop_proses/'.$signature);
            }
        } else if ($get_biop->nama_status == 'pending atasan 1') {
            if ($get_biop->pic_on_duty == $this->created_by) {
                redirect("management_biop/ajuan_biop_verifikasi_atasan1/$signature");
            } else {
                // $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses ' . $get_biop->nama_status);
                // redirect("management_biop/dashboard_biop");
                redirect('management_biop/ajuan_biop_verifikasi_admin_claim/'.$signature);
            }
        } else if ($get_biop->nama_status == 'pending atasan 2') {
            if ($get_biop->pic_on_duty == $this->created_by) {
                redirect("management_biop/ajuan_biop_verifikasi_atasan2/$signature");
            } else {
                // $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses ' . $get_biop->nama_status);
                // redirect("management_biop/dashboard_biop");
                redirect("management_biop/ajuan_biop_verifikasi_atasan1/$signature");
            }
        } else if ($get_biop->nama_status == 'pending admin finance') {
            if ($get_biop->pic_on_duty == $this->created_by) {
                redirect("management_biop/ajuan_biop_verifikasi_admin_finance/$signature");
            } else {
                // $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses ' . $get_biop->nama_status);
                // redirect("management_biop/dashboard_biop");
                redirect("management_biop/ajuan_biop_verifikasi_atasan2/$signature");
            }
        } else if ($get_biop->nama_status == 'pending head finance') {
            if ($get_biop->pic_on_duty == $this->created_by) {
                redirect("management_biop/ajuan_biop_verifikasi_head_finance/$signature");
            } else {
                // $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses ' . $get_biop->nama_status);
                // redirect("management_biop/dashboard_biop");
                redirect("management_biop/ajuan_biop_verifikasi_admin_finance/$signature");
            }
        }else if ($get_biop->nama_status == 'approved') {
            if ($get_biop->pic_on_duty == $this->created_by) {
                redirect("management_biop/ajuan_biop_verifikasi_head_finance/$signature");
            } elseif($this->created_by == 587) { // jika admin finance
                // $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses ' . $get_biop->nama_status);
                // redirect("management_biop/dashboard_biop");
                redirect("management_biop/ajuan_biop_verifikasi_admin_finance/$signature");
            }else{
                redirect("management_biop/ajuan_biop_verifikasi_head_finance/$signature");
            }
        } else {
            $this->session->set_flashdata('pesan', 'Maaf, Anda tidak memiliki akses untuk proses ' . $get_biop->nama_status);
            redirect("management_biop/dashboard_biop");
        }
    }

    public function ajuan_biop_verifikasi_admin_claim($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

        // authorization on duty
        $userid_onduty = $get_biop->pic_on_duty;
        $pic_name = $get_biop->username_on_duty;
        $nama_status = $get_biop->nama_status;
        if($userid_onduty == $this->session->userdata('id'))
        {
            $is_authorized = true;
        }else{
            $is_authorized = false;
        }

        $data = [
            'title'             => 'Verifikasi Admin Biop',
            'url_admin_proses'  => "management_biop/ajuan_biop_verifikasi_admin_claim_save/$signature",
            'url_dashboard'     => "management_biop/ajuan_biop",
            'url_delete_data'   => 'management_biop/ajuan_biop_data_delete/',
            'url_revisi'        => "management_biop/revisi_biop/$signature",
            'signature'         => $signature,
            'get_biop'          => $get_biop,
            'get_data_biop'     => $this->model_management_biop->getAll_biop_detail_by_signature($signature),
            'is_authorized'     => $is_authorized,
            'pic_name'          => $pic_name,
            'nama_status'       => $nama_status
        ];

        // $data['total_biaya'] = $this->model_management_biop->total_biaya_biop($data['get_data_biop']);

        // $this->view($data, true, 'ajuan_biop_verifikasi_admin_claim');
        $this->render_multiple(
            array(
                'management_biop/accordion',
                'management_biop/ajuan_biop_verifikasi_admin_claim',
            ),
            $data
        );
        // $this->render('management_biop/ajuan_biop_verifikasi_admin_claim', $data);
    }

    public function ajuan_biop_verifikasi_admin_claim_save($signature)
    {   
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

        if ($get_biop->status != 2) { // jika status nya bukan pending admin biop, maka tidak bisa melakukan proses
            $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan proses karena status pengajuan saat ini ' . $get_biop->nama_status);
            redirect("management_biop/ajuan_biop_verifikasi_admin_claim/$signature");
        }

        $id_detail = $this->input->post('id_detail');
        $biaya_admin_biop = $this->input->post('biaya_admin_biop');
        $keterangan_admin_biop = $this->input->post('keterangan_admin_biop');
        $status = $this->input->post('status');

        // echo "status : ";
        // print_r($status);
        // die;

        $total_biaya_adjusment = 0;

        $btn_proses = $this->input->post('proses');
        if($btn_proses == 0)
        {
            $status_header = 1;
            $nama_status_header = "pending user";
            $flashdata = 'Revisi Biop berhasil dikirim ke user';
        }else{
            $status_header = 3;
            $nama_status_header = "pending atasan 1";
            // $flashdata = 'Data biop berhasil di proses ke atasan';
        }

        for ($i=0; $i < count($id_detail) ; $i++) { 
            $update_detail = [
                'biaya_admin_biop'     => $biaya_admin_biop[$i],
                'keterangan_admin_biop'=> $keterangan_admin_biop[$i],
                'flag_tolak_admin_biop'=> $status[$i],
                'created_at'           => $this->created_at,
                'created_by'           => $this->created_by,
            ];

            // $total_biaya_adjusment += $biaya_admin_biop[$i];
            
            $params = [
                'id' => $id_detail[$i]
            ];

            $this->model_management_biop->update('site.biop_detail', $params, $update_detail);
        }

        $total_biaya_adjusment = $this->model_management_biop->total_biaya_biop_by_signature($signature, 'admin_claim');
        // echo "total biaya adjusment : ".$total_biaya_adjusment;
        // die;

        // update biop
        $update = [
            'status'        => $status_header,
            'nama_status'   => $nama_status_header,
            'total_biaya_adjustment'    => $total_biaya_adjusment,
            'admin_claim_at'    => $this->created_at,
            'admin_claim_by'    => $this->created_by,
            'admin_claim_signature'    => $this->session->userdata('username').'-signature.png',
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by
        ];

         // get pic on duty
        $update['pic_on_duty']  = $this->model_management_biop->get_pic_on_duty_by_userid_and_status($get_biop->userid, $update['nama_status']);

        $params = [
            'id'        => $get_biop->id,
            'signature' => $signature
        ];

        $this->model_management_biop->update('site.biop_header', $params, $update);

        // insert log history
        $insert_log = [
            'id_ajuan'      => $get_biop->id,
            'status'        => $update['status'],
            'nama_status'   => $update['nama_status'],
            'pic_on_duty'   => $update['pic_on_duty'],
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];

        $this->model_management_biop->insert_and_getId('site.biop_log',$insert_log);

        if ($btn_proses != 0) { // Jika BUKAN Revisi
            $this->send_email_biop($signature, 'admin_claim');
        }

        $this->session->set_flashdata('pesan_success', $flashdata);
        redirect("management_biop/ajuan_biop_verifikasi_admin_claim/$signature");
    }

    public function ajuan_biop_verifikasi_atasan1($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

        // authorization on duty
        $userid_onduty = $get_biop->pic_on_duty;
        $pic_name = $get_biop->username_on_duty;
        $nama_status = $get_biop->nama_status;
        if($userid_onduty == $this->session->userdata('id'))
        {
            $is_authorized = true;
        }else{
            $is_authorized = false;
        }

        $data = [
            'title'             => 'Verifikasi Atasan 1',
            'url_atasan1_proses'=> "management_biop/ajuan_biop_verifikasi_atasan1_save/$signature",
            'url_dashboard'     => "management_biop/ajuan_biop",
            'url_delete_data'   => 'management_biop/ajuan_biop_data_delete/',
            'signature'         => $signature,
            'get_biop'          => $get_biop,
            'get_data_biop'     => $this->model_management_biop->getAll_biop_detail_by_signature($signature),
	        'url_revisi'        => "management_biop/revisi_biop/$signature",
            'is_authorized'     => $is_authorized,
            'pic_name'          => $pic_name,
            'nama_status'       => $nama_status
        ];

        $data['total_biaya'] = $this->model_management_biop->total_biaya_biop_admin_claim($data['get_data_biop']);

        // $this->view($data, true, 'ajuan_biop_verifikasi_atasan1');

        $this->render_multiple(
            array(
                'management_biop/accordion',
                'management_biop/ajuan_biop_verifikasi_atasan1'
            ),
            $data
        );
    }

    public function ajuan_biop_verifikasi_atasan1_save($signature)
    {   
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

        if ($get_biop->status != 3) { // jika status nya bukan pending verifikasi atasan 1, maka tidak bisa melakukan proses
            $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan proses karena status pengajuan saat ini ' . $get_biop->nama_status);
            redirect("management_biop/ajuan_biop_verifikasi_atasan1/$signature");
        }

        // if(empty($id_detail) || empty($biaya_atasan1) || empty($keterangan_atasan1) || empty($status)) {
        //     $this->session->set_flashdata('pesan', 'Maaf, Data biop belum lengkap, silahkan cek kembali data biop anda!');
        //     redirect("management_biop/ajuan_biop_verifikasi_atasan1/$signature");
        // }

        //variable
        $id_detail = $this->input->post('id_detail');
        $biaya_atasan1 = $this->input->post('biaya_atasan1');
        $keterangan_atasan1 = $this->input->post('keterangan_atasan1');
        $status = $this->input->post('status');
        $total_biaya_adjusment = 0;

        $btn_proses = $this->input->post('proses');
        // echo "btn_proses = ".$btn_proses;
        // die;
        if($btn_proses == 0)
        {
            $status_header1 = 1;
            $nama_status_header1 = "pending user";
            $flashdata = 'Revisi Biop berhasil dikirim ke user';
        }else{
            $status_header1 = 4;
            $nama_status_header1 = "pending atasan 2";
            $status_header2 = 5;
            $nama_status_header2 = "pending admin finance";
            // $flashdata = 'Data Biop berhasil di proses';
        }

        // cek atasan 1 dan atasan 2
        $atasan  = $this->model_management_biop->get_pic_on_duty_by_userid($get_biop->userid);
        if($atasan->userid_verifikasi1 == $atasan->userid_verifikasi2)
        {
            $this->update_atasan1($get_biop, $id_detail, $biaya_atasan1, $keterangan_atasan1, $status, $total_biaya_adjusment, $status_header1, $nama_status_header1, $signature);
            $this->update_atasan2($get_biop, $id_detail, $biaya_atasan1, $keterangan_atasan1, $status, $total_biaya_adjusment, $status_header2, $nama_status_header2, $signature);
        } else {
            $this->update_atasan1($get_biop, $id_detail, $biaya_atasan1, $keterangan_atasan1, $status, $total_biaya_adjusment, $status_header1, $nama_status_header1, $signature);
        }

        if ($btn_proses != 0 && $atasan->userid_verifikasi1 != $atasan->userid_verifikasi2) { // Jika BUKAN Revisi
            $this->send_email_biop($signature, 'atasan1');
        }elseif($btn_proses != 0 && $atasan->userid_verifikasi1 == $atasan->userid_verifikasi2){
            $this->send_email_biop($signature, 'atasan2');   
        }

        $this->session->set_flashdata('pesan_success', $flashdata);
        redirect("management_biop/ajuan_biop_verifikasi_atasan1/$signature");
    }

    function update_atasan1($get_biop, $id_detail, $biaya_atasan1, $keterangan_atasan1, $status, $total_biaya_adjusment, $status_header, $nama_status_header, $signature) 
    {
        // echo "a";
        // die;
        for ($i=0; $i < count($id_detail) ; $i++) { 
            $update_detail = [
                'biaya_atasan1'     => $biaya_atasan1[$i],
                'keterangan_atasan1'=> $keterangan_atasan1[$i],
                'flag_tolak_atasan1'        => $status[$i],
                'created_at'        => $this->created_at,
                'created_by'        => $this->created_by
            ];

            // $total_biaya_adjusment += $biaya_atasan1[$i];
            
            $params = [
                'id' => $id_detail[$i]
            ];

            // echo "<pre>"; print_r($update_detail); echo "</pre>";
            // echo "<pre>"; print_r($params); echo "</pre>";
            
            $this->model_management_biop->update('site.biop_detail', $params, $update_detail);
        }

        // die;
        
        $total_biaya_adjusment = $this->model_management_biop->total_biaya_biop_by_signature($signature, 'atasan1');

        // update biop
        $update = [
            'status'        => $status_header,
            'nama_status'   => $nama_status_header,
            'total_biaya_adjustment'   => $total_biaya_adjusment,
            'atasan1_at'    => $this->created_at,
            'atasan1_by'    => $this->created_by,
            'atasan1_signature'    => $this->session->userdata('username').'-signature.png',
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by
        ];

         // get pic on duty
        $update['pic_on_duty']  = $this->model_management_biop->get_pic_on_duty_by_userid_and_status($get_biop->userid, $update['nama_status']);

        $params = [
            'id'        => $get_biop->id,
            'signature' => $get_biop->signature
        ];

        $this->model_management_biop->update('site.biop_header', $params, $update);

        // insert log history
        $insert_log = [
            'id_ajuan'      => $get_biop->id,
            'status'        => $update['status'],
            'nama_status'   => $update['nama_status'],
            'pic_on_duty'   => $update['pic_on_duty'],
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];

        $this->model_management_biop->insert_and_getId('site.biop_log',$insert_log);
    }

    public function ajuan_biop_verifikasi_atasan2($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

        // authorization on duty
        $userid_onduty = $get_biop->pic_on_duty;
        $pic_name = $get_biop->username_on_duty;
        $nama_status = $get_biop->nama_status;
        if($userid_onduty == $this->session->userdata('id'))
        {
            $is_authorized = true;
        }else{
            $is_authorized = false;
        }

        $data = [
            'title'             => 'Verifikasi Atasan 2',
            'url_atasan2_proses'  => "management_biop/ajuan_biop_verifikasi_atasan2_save/$signature",
            'url_dashboard'     => "management_biop/ajuan_biop",
            'url_delete_data'   => 'management_biop/ajuan_biop_data_delete/',
            'signature'         => $signature,
            'get_biop'          => $get_biop,
            'get_data_biop'     => $this->model_management_biop->getAll_biop_detail_by_signature($signature),
	        'url_revisi'        => "management_biop/revisi_biop/$signature",
            'is_authorized'     => $is_authorized,
            'pic_name'          => $pic_name,
            'nama_status'       => $nama_status
        ];

        $data['total_biaya'] = $this->model_management_biop->total_biaya_biop_atasan1($data['get_data_biop']);

        // $this->view($data, true, 'ajuan_biop_verifikasi_atasan2');

        $this->render_multiple(
            array(
                'management_biop/accordion',
                'management_biop/ajuan_biop_verifikasi_atasan2'
            ),
            $data
        );

    }

    public function ajuan_biop_verifikasi_atasan2_save($signature)
    {   
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);
        
        if ($get_biop->status != 4) { // jika status nya bukan pending verifikasi atasan 2, maka tidak bisa melakukan proses
            $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan proses karena status pengajuan saat ini ' . $get_biop->nama_status);
            redirect("management_biop/ajuan_biop_verifikasi_atasan2/$signature");
        }

        //variable
        $id_detail = $this->input->post('id_detail');
        $biaya_atasan2 = $this->input->post('biaya_atasan2');
        $keterangan_atasan2 = $this->input->post('keterangan_atasan2');
        $status = $this->input->post('status');
        $total_biaya_adjusment = 0;

        $btn_proses = $this->input->post('proses');
        // echo "btn_proses = ".$btn_proses;
        // die;
        if($btn_proses == 0)
        {
            $status_header = 1;
            $nama_status_header = "pending user";
            $flashdata = 'Revisi Biop berhasil dikirim ke user';
        }else{
            $status_header = 5;
            $nama_status_header = "pending admin finance";
            // $flashdata = 'Data biop berhasil di proses ke admin finance';
        }

        $this->update_atasan2($get_biop, $id_detail, $biaya_atasan2, $keterangan_atasan2, $status, $total_biaya_adjusment, $status_header, $nama_status_header, $signature);

        if ($btn_proses != 0) { // Jika BUKAN Revisi
            $this->send_email_biop($signature, 'atasan2');
        }
        $this->session->set_flashdata('pesan_success', $flashdata);
        redirect("management_biop/ajuan_biop_verifikasi_atasan2/$signature");
    }
 
    function update_atasan2($get_biop, $id_detail, $biaya_atasan2, $keterangan_atasan2, $status, $total_biaya_adjusment, $status_header, $nama_status_header, $signature) {
        for ($i=0; $i < count($id_detail) ; $i++) { 
            $update_detail = [
                'biaya_atasan2'     => $biaya_atasan2[$i],
                'keterangan_atasan2'    => $keterangan_atasan2[$i],
                'flag_tolak_atasan2'        => $status[$i],
                'created_at'        => $this->created_at,
                'created_by'        => $this->created_by
            ];

            // $total_biaya_adjusment += $biaya_atasan2[$i];
            
            $params = [
                'id' => $id_detail[$i]
            ];

            // echo "<pre>";
            // print_r($update_detail);
            // print_r($params);
            // echo "</pre>";

            $this->model_management_biop->update('site.biop_detail', $params, $update_detail);
        }

        // die;

        $total_biaya_adjusment = $this->model_management_biop->total_biaya_biop_by_signature($signature, 'atasan2');
        
        // update biop
        $update = [
            'status'        => $status_header,
            'nama_status'   => $nama_status_header,
            'total_biaya_adjustment'   => $total_biaya_adjusment,
            'atasan2_at'    => $this->created_at,
            'atasan2_by'    => $this->created_by,
            'atasan2_signature'    => $this->session->userdata('username').'-signature.png',
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by
        ];

         // get pic on duty
        $update['pic_on_duty']  = $this->model_management_biop->get_pic_on_duty_by_userid_and_status($get_biop->userid, $update['nama_status']);

        $params = [
            'id'        => $get_biop->id,
            'signature' => $get_biop->signature
        ];

        $this->model_management_biop->update('site.biop_header', $params, $update);

        // insert log history
        $insert_log = [
            'id_ajuan'      => $get_biop->id,
            'status'        => $update['status'],
            'nama_status'   => $update['nama_status'],
            'pic_on_duty'   => $update['pic_on_duty'],
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];

        $this->model_management_biop->insert_and_getId('site.biop_log',$insert_log);

    }

    public function ajuan_biop_verifikasi_admin_finance($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

         // authorization on duty
        $userid_onduty = $get_biop->pic_on_duty;
        $pic_name = $get_biop->username_on_duty;
        $nama_status = $get_biop->nama_status;
        if($userid_onduty == $this->session->userdata('id'))
        {
            $is_authorized = true;
        }else{
            $is_authorized = false;
        }

        $data = [
            'title'                     => 'Verifikasi Admin Finance',
            'url_admin_finance_proses'  => "management_biop/ajuan_biop_verifikasi_admin_finance_save/$signature",
            'url_dashboard'             => "management_biop/ajuan_biop",
            'url_revisi'                => "management_biop/revisi_biop/$signature",
            'url_delete_data'           => 'management_biop/ajuan_biop_data_delete/',
            'url_update_tanggal'        => "management_biop/ajuan_biop_data_update_tanggal/$signature",
            'signature'                 => $signature,
            'get_biop'                  => $get_biop,
            'get_data_biop'             => $this->model_management_biop->getAll_biop_detail_by_signature($signature),
	        'url_revisi'                => "management_biop/revisi_biop/$signature",
            'is_authorized'             => $is_authorized,
            'pic_name'                  => $pic_name,
            'nama_status'               => $nama_status
        ];

        // $data['total_biaya'] = $this->model_management_biop->total_biaya_biop_atasan2($data['get_data_biop']);

        // $this->view($data, true, 'ajuan_biop_verifikasi_admin_finance');

        $this->render_multiple(
            array(
                'management_biop/accordion',
                'management_biop/ajuan_biop_verifikasi_admin_finance'
            ),
            $data
        );

    }

    public function ajuan_biop_verifikasi_admin_finance_save($signature)
    {   
        // echo 'disini';die;
        $tanggal_uang_keluar = $this->input->post('tanggal_uang_keluar');
        // echo "tanggal uang keluar : ".$tanggal_uang_keluar;
        // die;
        if(empty($tanggal_uang_keluar)) {
            $tanggal_uang_keluar = null;
        }
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

        if ($get_biop->status != 5) { // jika status nya bukan pending admin finance, maka tidak bisa melakukan proses
            $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan proses karena status pengajuan saat ini ' . $get_biop->nama_status);
            redirect("management_biop/ajuan_biop_verifikasi_admin_finance/$signature");
        }

        //update data biop detail
        $id_detail = $this->input->post('id_detail');
        $biaya_admin_finance = $this->input->post('biaya_admin_finance');
        $keterangan_admin_finance = $this->input->post('keterangan_admin_finance');
        $status = $this->input->post('status');
        $total_biaya_adjusment = 0;

        $btn_proses = $this->input->post('proses');
        if($btn_proses == 0)
        {
            $status_header = 1;
            $nama_status_header = "pending user";
            $flashdata = 'Revisi Biop berhasil dikirim ke user';
        }elseif($btn_proses == 1)
        {
            $status_header = 6;
            $nama_status_header = "pending head finance";
            // $flashdata = 'Data Biop berhasil di proses ke head finance';
        }else{
            $status_header = 5;
            $nama_status_header = "pending admin finance";
            $flashdata = 'Data Biop berhasil di disimpan';
        }

        if(empty($id_detail) || empty($biaya_admin_finance) || empty($keterangan_admin_finance) || empty($status)) {
            $this->session->set_flashdata('pesan', 'Maaf, Data tidak boleh kosong');
            redirect("management_biop/ajuan_biop_verifikasi_admin_finance/$signature");
        }

        for ($i=0; $i < count($id_detail) ; $i++) { 
            $update_detail = [
                'biaya_admin_finance'     => $biaya_admin_finance[$i],
                'keterangan_admin_finance'    => $keterangan_admin_finance[$i],
                'flag_tolak_admin_finance'        => $status[$i],
                'created_at'        => $this->created_at,
                'created_by'        => $this->created_by
            ];

            // $total_biaya_adjusment += $biaya_admin_finance[$i];
            
            $params = [
                'id' => $id_detail[$i]
            ];
            
            $this->model_management_biop->update('site.biop_detail', $params, $update_detail);
        }
        
        $total_biaya_adjusment = $this->model_management_biop->total_biaya_biop_by_signature($signature, 'admin_finance');
        // echo "total biaya adjusment : ".$total_biaya_adjusment;

        // update biop
        $update = [
            'status'        => $status_header,
            'nama_status'   => $nama_status_header,
            'total_biaya_adjustment'    => $total_biaya_adjusment,
            'tanggal_uang_keluar'       => $tanggal_uang_keluar,
            'admin_finance_at'    => $this->created_at,
            'admin_finance_by'    => $this->created_by,
            'admin_finance_signature'    => $this->session->userdata('username').'-signature.png',
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by
        ];

         // get pic on duty
        $update['pic_on_duty']  = $this->model_management_biop->get_pic_on_duty_by_userid_and_status($get_biop->userid, $update['nama_status']);

        $params = [
            'id'        => $get_biop->id,
            'signature' => $signature
        ];

        $this->model_management_biop->update('site.biop_header', $params, $update);

        // insert log history
        $insert_log = [
            'id_ajuan'      => $get_biop->id,
            'status'        => $update['status'],
            'nama_status'   => $update['nama_status'],
            'pic_on_duty'   => $update['pic_on_duty'],
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];

        $this->model_management_biop->insert_and_getId('site.biop_log',$insert_log);

        if ($btn_proses == 1) { // Jika BUKAN Revisi
            $this->send_email_biop($signature, 'admin_finance');
        }
        $this->session->set_flashdata('pesan_success', $flashdata);
        redirect("management_biop/ajuan_biop_verifikasi_admin_finance/$signature");
    }

    public function ajuan_biop_verifikasi_head_finance($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

         // authorization on duty
        $userid_onduty = $get_biop->pic_on_duty;
        $pic_name = $get_biop->username_on_duty;
        $nama_status = $get_biop->nama_status;

        if($userid_onduty == $this->session->userdata('id'))
        {
            $is_authorized = true;
        }else{
            $is_authorized = false;
        }

        $data = [
            'title'             => 'Verifikasi Admin Finance',
            'url_head_finance_proses'  => "management_biop/ajuan_biop_verifikasi_head_finance_save/$signature",
            'url_dashboard'     => "management_biop/ajuan_biop",
            'url_delete_data'   => 'management_biop/ajuan_biop_data_delete/',
            'signature'         => $signature,
            'get_biop'          => $get_biop,
            'get_data_biop'     => $this->model_management_biop->getAll_biop_detail_by_signature($signature),
	        'url_revisi'        => "management_biop/revisi_biop/$signature",
            'is_authorized'     => $is_authorized,
            'pic_name'          => $pic_name,
            'nama_status'       => $nama_status
        ];

        $data['total_biaya'] = $this->model_management_biop->total_biaya_biop_admin_finance($data['get_data_biop']);

        // $this->view($data, true, 'ajuan_biop_verifikasi_head_finance');

        $this->render_multiple(
            array(
                'management_biop/accordion',
                'management_biop/ajuan_biop_verifikasi_head_finance'
            ),
            $data
        );

    }

    public function ajuan_biop_verifikasi_head_finance_save($signature)
    {   
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);
        
        if ($get_biop->status != 6) { // jika status nya bukan pending head finance, maka tidak bisa melakukan proses
            $this->session->set_flashdata('pesan', 'Maaf, Anda belum bisa melakukan proses karena status pengajuan saat ini ' . $get_biop->nama_status);
            redirect("management_biop/ajuan_biop_verifikasi_head_finance/$signature");
        }

        //update data biop detail
        $id_detail = $this->input->post('id_detail');
        $biaya_head_finance = $this->input->post('biaya_head_finance');
        $keterangan_head_finance = $this->input->post('keterangan_head_finance');
        $status = $this->input->post('status');
        $total_biaya_adjusment = 0;

        $btn_proses = $this->input->post('proses');
        if($btn_proses == 0)
        {
            $status_header = 1;
            $nama_status_header = "pending user";
            $flashdata = 'Revisi Biop berhasil dikirim ke user';
        }else{
            $status_header = 7;
            $nama_status_header = "approved";
            // $flashdata = 'Data biop approved';
        }

        for ($i=0; $i < count($id_detail) ; $i++) { 
            $update_detail = [
                'biaya_head_finance'     => $biaya_head_finance[$i],
                'keterangan_head_finance'    => $keterangan_head_finance[$i],
                'flag_tolak_head_finance'    => $status[$i],
                'created_at'    => $this->created_at,
                'created_by'    => $this->created_by
            ];

            // $total_biaya_adjusment += $biaya_head_finance[$i];
            
            $params = [
                'id' => $id_detail[$i]
            ];
            
            $this->model_management_biop->update('site.biop_detail', $params, $update_detail);
        }

        $total_biaya_adjusment = $this->model_management_biop->total_biaya_biop_by_signature($signature, 'head_finance');
        
        // update biop
        $update = [
            'status'        => $status_header,
            'nama_status'   => $nama_status_header,
            'total_biaya_adjustment'   => $total_biaya_adjusment,
            'head_finance_at'    => $this->created_at,
            'head_finance_by'    => $this->created_by,
            'head_finance_signature'    => $this->session->userdata('username').'-signature.png',
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by
        ];

         // get pic on duty
        $update['pic_on_duty']  = $get_biop->userid;

        $params = [
            'id'        => $get_biop->id,
            'signature' => $signature
        ];

        $this->model_management_biop->update('site.biop_header', $params, $update);

        // insert log history
        $insert_log = [
            'id_ajuan'      => $get_biop->id,
            'status'        => $update['status'],
            'nama_status'   => $update['nama_status'],
            'pic_on_duty'   => $update['pic_on_duty'],
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];

        $this->model_management_biop->insert_and_getId('site.biop_log',$insert_log);

        if ($btn_proses != 0) { // Jika BUKAN Revisi
            $this->send_email_biop($signature, 'head_finance');
        }
        $this->session->set_flashdata('pesan_success', $flashdata);
        redirect("management_biop/ajuan_biop_verifikasi_head_finance/$signature");
    }

    // public function detail_biop($signature)
    // {
    //     $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
    //     // filter data tidak ditemukan
    //     $this->filter_data_biop($get_biop);
        
    //     $data = [
    //         'title'                 => 'Detail Biop',
    //         'get_biop'              => $get_biop,
    //         'get_data_biop_grouped' => $this->model_management_biop->get_data_biop_grouped_tanggal($signature),
    //         'get_data_biop'         => $this->model_management_biop->getAll_biop_detail_by_signature($signature),
    //         'get_pengeluran_biop'   => $this->model_management_biop->get_pengeluaran_biop_by_signature($signature),
    //         'pic'                   => $this->model_management_biop->get_user($get_biop->created_by),
    //         'admin_claim'           => $this->model_management_biop->get_user($get_biop->admin_claim_by),
    //         'atasan1'               => $this->model_management_biop->get_user($get_biop->atasan1_by),
    //         'atasan2'               => $this->model_management_biop->get_user($get_biop->atasan2_by),
    //         'admin_finance'         => $this->model_management_biop->get_user($get_biop->admin_finance_by),
    //         'head_finance'          => $this->model_management_biop->get_user($get_biop->head_finance_by),
    //         'signature'             => $signature
    //     ];
            
    //     $this->view($data, false, 'detail_biop');
    // }

    // public function detail_biop($signature)
    // {
    //     $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
    //     $this->filter_data_biop($get_biop);

    //     $biop_details = $this->model_management_biop->get_pengeluaran_biop_by_signature($signature);

    //     $kategori = [
    //         'biaya_bbm' => [
    //             'items' => ['tol','bbm','parkir'],
    //             'label' => 'Biaya BBM/Tol/Parkir'
    //         ],
    //         'biaya_jamuan' => [
    //             'items' => ['jamuan'],
    //             'label' => 'Biaya Jamuan'
    //         ],
    //         'biaya_meeting' => [
    //             'items' => ['meeting'],
    //             'label' => 'Biaya Meeting'
    //         ],
    //         'biaya_perjalanan_dinas' => [
    //             'items' => ['makan','transportasi','hotel','perjalanan dinas'],
    //             'label' => 'Biaya Perjalanan Dinas (Transportasi, Hotel, Uang Makan)'
    //         ],
    //         'biaya_service_kendaraan' => [
    //             'items' => ['service kendaraan'],
    //             'label' => 'Biaya Service Kendaraan'
    //         ],
    //         'biaya_stationery' => [
    //             'items' => ['stationery'],
    //             'label' => 'Biaya Stationery'
    //         ],
    //         'biaya_lain_lain' => [
    //             'items' => ['lain_lain'],
    //             'label' => 'Biaya Lain-lain'
    //         ],
    //     ];

    //     // init
    //     foreach ($kategori as $key => $info) {
    //         $kategori[$key]['total'] = 0;
    //         $kategori[$key]['ket']   = [];
    //     }

    //     // group
    //     foreach ($biop_details as $row) {
    //         foreach ($kategori as $key => $info) {
    //             if (in_array($row->nama_kategori, $info['items'])) {
    //                 $kategori[$key]['total'] += $row->biaya_head_finance;
    //                 $kategori[$key]['ket'][] = $row->keterangan;
    //             }
    //         }
    //     }

    //     $data = [
    //         'title'                 => 'Detail Biop',
    //         'signature'             => $signature,
    //         'kategori'              => $kategori,
    //         'get_data_biop_grouped' => $this->model_management_biop->get_data_biop_grouped_tanggal($signature),
    //         'get_data_biop'         => $this->model_management_biop->getAll_biop_detail_by_signature($signature),
    //         'get_biop'              => $get_biop,
    //         'pic'                   => $this->model_management_biop->get_user($get_biop->created_by),
    //         'admin_claim'           => $this->model_management_biop->get_user($get_biop->admin_claim_by),
    //         'atasan1'               => $this->model_management_biop->get_user($get_biop->atasan1_by),
    //         'atasan2'               => $this->model_management_biop->get_user($get_biop->atasan2_by),
    //         'admin_finance'         => $this->model_management_biop->get_user($get_biop->admin_finance_by),
    //         'head_finance'          => $this->model_management_biop->get_user($get_biop->head_finance_by),
    //     ];

    //     $this->view($data, false, 'detail_biop');
    // }

    // public function detail_biop_last($signature)
    // {
    //     // Ambil data utama
    //     $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
    //     $this->filter_data_biop($get_biop);

    //     // Ambil raw detail (tanpa WHERE flag)
    //     $biop_details = $this->model_management_biop->get_biop_detail_raw($signature);

    //     // pengelompokan kategori untuk bukti pengeluaran
    //     $kategori = [
    //         'biaya_bbm' => [
    //             'items' => ['tol','bbm','parkir'],
    //             'label' => 'Biaya BBM/Tol/Parkir',
    //             'total' => 0,
    //         ],
    //         'biaya_jamuan' => [
    //             'items' => ['jamuan'],
    //             'label' => 'Biaya Jamuan',
    //             'total' => 0,
    //         ],
    //         'biaya_meeting' => [
    //             'items' => ['meeting'],
    //             'label' => 'Biaya Meeting',
    //             'total' => 0,
    //         ],
    //         'biaya_perjalanan_dinas' => [
    //             'items' => ['makan','transportasi','hotel','perjalanan dinas'],
    //             'label' => 'Biaya Perjalanan Dinas (Transportasi, Hotel, Uang Makan)',
    //             'total' => 0,
    //         ],
    //         'biaya_service_kendaraan' => [
    //             'items' => ['service kendaraan'],
    //             'label' => 'Biaya Service Kendaraan',
    //             'total' => 0,
    //         ],
    //         'biaya_stationery' => [
    //             'items' => ['stationery'],
    //             'label' => 'Biaya Stationery',
    //             'total' => 0,
    //         ],
    //         'biaya_lain_lain' => [
    //             'items' => ['lain_lain'],
    //             'label' => 'Biaya Lain-lain',
    //             'total' => 0,
    //         ],
    //     ];

    //     // GROUPING PER TANGGAL & KATEGORI
    //     $grouped = [];

    //     foreach ($biop_details as $row) 
    //     {
    //         // ============================
    //         // 1. TENTUKAN APPROVAL TERAKHIR
    //         // ============================
    //         $last_flag  = null;
    //         $biaya_last = null;

    //         if ($row->flag_tolak_head_finance !== null) {
    //             $last_flag  = $row->flag_tolak_head_finance;
    //             $biaya_last = $row->biaya_head_finance;
    //         }
    //         elseif ($row->flag_tolak_admin_finance !== null) {
    //             $last_flag  = $row->flag_tolak_admin_finance;
    //             $biaya_last = $row->biaya_admin_finance;
    //         }
    //         elseif ($row->flag_tolak_atasan2 !== null) {
    //             $last_flag  = $row->flag_tolak_atasan2;
    //             $biaya_last = $row->biaya_atasan2;
    //         }
    //         elseif ($row->flag_tolak_atasan1 !== null) {
    //             $last_flag  = $row->flag_tolak_atasan1;
    //             $biaya_last = $row->biaya_atasan1;
    //         }
    //         elseif ($row->flag_tolak_admin_biop !== null) {
    //             $last_flag  = $row->flag_tolak_admin_biop;
    //             $biaya_last = $row->biaya_admin_biop;
    //         }
    //         else {
    //             // semua masih null → belum ada approval
    //             $last_flag  = null;
    //             $biaya_last = $row->biaya;
    //         }

    //         // ============================
    //         // 2. CHECK FLAG TERAKHIR
    //         // ============================
    //         if ($last_flag === "1") {
    //             continue; // DITOLAK → skip baris ini
    //         }

    //         // Jika approve → gunakan biaya terakhir
    //         $biaya = $biaya_last;

    //         // ============================
    //         // 3. PENGHITUNGAN KATEGORI (TOTAL)
    //         // ============================
    //         foreach ($kategori as $k => $info) {
    //             if (in_array($row->nama_kategori, $info['items'])) {
    //                 $kategori[$k]['total'] += $biaya;
    //             }
    //         }

    //         // Key grup
    //         $key = $row->id_biop . '_' . $row->tanggal;

    //         // INIT (OBJECT)
    //         if (!isset($grouped[$key])) {
    //             $grouped[$key] = (object)[
    //                 'tanggal'          => $row->tanggal,
    //                 'keterangan_biaya' => [],
    //                 'tol'              => 0,
    //                 'parkir'           => 0,
    //                 'bbm_km'           => 0,
    //                 'bbm_liter'        => 0,
    //                 'bbm_rp'           => 0,
    //                 'makan'            => 0,
    //                 'jamuan'           => 0,
    //                 'meeting'          => 0,
    //                 'perjalanan_dinas' => 0,
    //                 'service_kendaraan'=> 0,
    //                 'stationery'       => 0,
    //                 'lain'             => 0,
    //                 'keterangan_tempat'=> $row->keterangan_tempat,
    //             ];
    //         }

    //         // Keterangan kategori
    //         $grouped[$key]->keterangan_biaya[] = $row->nama_kategori;

    //         // ============================
    //         // 4. KATEGORI → KOLOM DETAIL
    //         // ============================
    //         if ($row->nama_kategori === 'tol') {
    //             $grouped[$key]->tol += $biaya;
    //         }
    //         elseif ($row->nama_kategori === 'parkir') {
    //             $grouped[$key]->parkir += $biaya;
    //         }
    //         elseif ($row->nama_kategori === 'bbm') {
    //             $grouped[$key]->bbm_km    = $row->bbm_km;
    //             $grouped[$key]->bbm_liter = $row->bbm_liter;
    //             $grouped[$key]->bbm_rp   += $biaya;
    //         }
    //         elseif ($row->nama_kategori === 'makan') {
    //             $grouped[$key]->makan += $biaya;
    //         }
    //         elseif ($row->nama_kategori === 'jamuan') {
    //             $grouped[$key]->jamuan += $biaya;
    //         }
    //         elseif ($row->nama_kategori === 'meeting') {
    //             $grouped[$key]->meeting += $biaya;
    //         }
    //         elseif (
    //             $row->nama_kategori === 'transportasi' ||
    //             $row->nama_kategori === 'hotel' ||
    //             $row->nama_kategori === 'perjalanan dinas'
    //         ) {
    //             $grouped[$key]->perjalanan_dinas += $biaya;
    //         }
    //         elseif ($row->nama_kategori === 'service kendaraan') {
    //             $grouped[$key]->service_kendaraan += $biaya;
    //         }
    //         elseif ($row->nama_kategori === 'stationery') {
    //             $grouped[$key]->stationery += $biaya;
    //         }
    //         else {
    //             $grouped[$key]->lain += $biaya;
    //         }
    //     }

    //     // Convert associative → indexed
    //     $grouped = array_values($grouped);

    //     // KIRIM KE VIEW
    //     $data = [
    //         'title'                 => 'Detail Biop',
    //         'signature'             => $signature,
    //         'get_data_biop_grouped' => $grouped,
    //         'get_data_biop'         => $biop_details,
    //         'get_biop'              => $get_biop,
    //         'kategori'              => $kategori,
    //         'pic'                   => $this->model_management_biop->get_user($get_biop->created_by),
    //         'admin_claim'           => $this->model_management_biop->get_user($get_biop->admin_claim_by),
    //         'atasan1'               => $this->model_management_biop->get_user($get_biop->atasan1_by),
    //         'atasan2'               => $this->model_management_biop->get_user($get_biop->atasan2_by),
    //         'admin_finance'         => $this->model_management_biop->get_user($get_biop->admin_finance_by),
    //         'head_finance'          => $this->model_management_biop->get_user($get_biop->head_finance_by),
    //     ];

    //     $this->view($data, false, 'detail_biop');
    // }

    public function detail_biop($signature)
    {
        // Data utama
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        $this->filter_data_biop($get_biop);

        // Detail raw
        $biop_details = $this->model_management_biop->get_biop_detail_raw($signature);

        // Mapping kategori total
        $kategori = $this->mapping_kategori_bukti_pengeluaran();

        // Hasil grouping
        $grouped = [];
        $filtered_biop = [];

        foreach ($biop_details as $row)
        {   
            
            // 1. Ambil approval terakhir
            $approval = $this->get_last_approval($row);

            // Skip jika ditolak
            if ($approval['flag'] == "1") {
                continue;
            }

            // $biaya = isset($approval['biaya']) ? $approval['biaya'] : 0;

            $biaya = $approval['biaya'];
            $row->biaya_approved = $biaya; // tambahkan field baru untuk view
            $filtered_biop[] = $row;

            // 2. Tambah ke total kategori
            // $this->add_to_kategori_total($kategori, $row->nama_kategori, $biaya);
            foreach ($kategori as $k => $info) {
                if (in_array($row->nama_kategori, $info['items'])) {
                    $kategori[$k]['total'] += $biaya;
                }
            }

            // 3. Grouping berdasarkan id + tanggal
            $key = $row->id_biop . '_' . $row->tanggal;

            // if (!isset($grouped[$key])) {
            //     $grouped[$key] = $this->init_group_object($row);
            // }

            // INIT (OBJECT)
            if (!isset($grouped[$key])) {
                $grouped[$key] = (object)[
                    'tanggal'          => $row->tanggal,
                    'keterangan_biaya' => [],
                    'tol'              => 0,
                    'parkir'           => 0,
                    'bbm_km'           => 0,
                    'bbm_liter'        => 0,
                    'bbm_rp'           => 0,
                    'makan'            => 0,
                    'jamuan'           => 0,
                    'meeting'          => 0,
                    'perjalanan_dinas' => 0,
                    'service_kendaraan'=> 0,
                    'stationery'       => 0,
                    'lain'             => 0,
                    'keterangan_tempat'=> $row->keterangan_tempat,
                ];
            }

            // Simpan nama kategori
            $grouped[$key]->keterangan_biaya[] = $row->nama_kategori;

            // 4. Mapping kategori ke kolom
            $this->map_to_group_column($grouped[$key], $row, $biaya);
        }

        $grouped = array_values($grouped);
        // echo "<pre>";
        // var_dump($grouped);
        // echo "</pre>";die;

        // Load View
        $data = [
            'title'                 => 'Detail Biop',
            'signature'             => $signature,
            'get_data_biop_grouped' => $grouped,
            'get_data_biop'         => $filtered_biop,
            'get_biop'              => $get_biop,
            'kategori'              => $kategori,
            'pic'                   => $this->model_management_biop->get_user($get_biop->created_by),
            'admin_claim'           => $this->model_management_biop->get_user($get_biop->admin_claim_by),
            'atasan1'               => $this->model_management_biop->get_user($get_biop->atasan1_by),
            'atasan2'               => $this->model_management_biop->get_user($get_biop->atasan2_by),
            'admin_finance'         => $this->model_management_biop->get_user($get_biop->admin_finance_by),
            'head_finance'          => $this->model_management_biop->get_user($get_biop->head_finance_by),
        ];

        // $this->view($data, false, 'detail_biop');

        $this->render('management_biop/detail_biop', $data);

    }

    private function mapping_kategori_bukti_pengeluaran()
    {
        return [
            'biaya_bbm' => [
                'items' => ['tol','bbm','parkir'],
                'label' => 'Biaya BBM/Tol/Parkir',
                'total' => 0,
            ],
            'biaya_jamuan' => [
                'items' => ['jamuan'],
                'label' => 'Biaya Jamuan',
                'total' => 0,
            ],
            'biaya_meeting' => [
                'items' => ['meeting'],
                'label' => 'Biaya Meeting',
                'total' => 0,
            ],
            'biaya_perjalanan_dinas' => [
                'items' => ['makan','transportasi','hotel','perjalanan dinas'],
                'label' => 'Biaya Perjalanan Dinas (Transportasi, Hotel, Uang Makan)',
                'total' => 0,
            ],
            'biaya_service_kendaraan' => [
                'items' => ['service kendaraan'],
                'label' => 'Biaya Service Kendaraan',
                'total' => 0,
            ],
            'biaya_stationery' => [
                'items' => ['stationery'],
                'label' => 'Biaya Stationery',
                'total' => 0,
            ],
            'biaya_lain_lain' => [
                'items' => ['lain-lain'],
                'label' => 'Biaya Lain-lain',
                'total' => 0,
            ],
        ];
    }

    private function get_last_approval($row)
    {
        $levels = [
            ['flag' => 'flag_tolak_head_finance',  'biaya' => 'biaya_head_finance'],
            ['flag' => 'flag_tolak_admin_finance', 'biaya' => 'biaya_admin_finance'],
            ['flag' => 'flag_tolak_atasan2',       'biaya' => 'biaya_atasan2'],
            ['flag' => 'flag_tolak_atasan1',       'biaya' => 'biaya_atasan1'],
            ['flag' => 'flag_tolak_admin_biop',    'biaya' => 'biaya_admin_biop'],
        ];

        foreach ($levels as $lvl) {
            if ($row->{$lvl['flag']} !== null) {
                return [
                    'flag'  => $row->{$lvl['flag']},
                    'biaya' => $row->{$lvl['biaya']},
                ];
            }
        }

        return ['flag' => null, 'biaya' => $row->biaya];
    }

    private function map_to_group_column(&$group, $row, $biaya)
    {
        $map = [
            'tol'                => 'tol',
            'parkir'             => 'parkir',
            'bbm'                => 'bbm_rp',
            'makan'              => 'makan',
            'jamuan'             => 'jamuan',
            'meeting'            => 'meeting',
            'transportasi'       => 'perjalanan_dinas',
            'hotel'              => 'perjalanan_dinas',
            'perjalanan dinas'   => 'perjalanan_dinas',
            'service kendaraan'  => 'service_kendaraan',
            'stationery'         => 'stationery',
        ];

        $column = isset($map[$row->nama_kategori]) ? $map[$row->nama_kategori] : 'lain';

        // jika kategori bbm → punya km & liter
        if ($row->nama_kategori === 'bbm') {
            $group->bbm_km    = $row->bbm_km;
            $group->bbm_liter = $row->bbm_liter;
        }

        $group->{$column} += $biaya;
    }

    public function revisi_biop($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);
        $status = $get_biop->status;

        // echo "status : ".$status;
        // die;

        if($status == '2') // pending admin biop
        {
            $url_redirect = "ajuan_biop_verifikasi_admin_claim";
        }

        // update biop
        $update = [
            'status'        => 1,
            'nama_status'   => 'pending user',
            'total_biaya'   => null,
            'total_biaya_adjustment'    => null,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->created_by
        ];

         // get pic on duty
        $update['pic_on_duty']  = $get_biop->userid;

        $params = [
            'id'        => $get_biop->id,
            'signature' => $signature
        ];

        $this->model_management_biop->update('site.biop_header', $params, $update);

        // insert log history
        $insert_log = [
            'id_ajuan'      => $get_biop->id,
            'status'        => $update['status'],
            'nama_status'   => $update['nama_status'],
            'pic_on_duty'   => $update['pic_on_duty'],
            'created_at'    => $this->created_at,
            'created_by'    => $this->created_by
        ];

        $this->model_management_biop->insert_and_getId('site.biop_log',$insert_log);

        $this->session->set_flashdata('pesan_success', 'Data biop berhasil di proses ke user');
        redirect("management_biop/$url_redirect/$signature");
    }

    public function export_biop_by_signature($signature)
    {
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        // filter data tidak ditemukan
        $this->filter_data_biop($get_biop);

         // authorization on duty
        $id = $get_biop->id;

        $get_data = $this->model_management_biop->get_biop_header_join_detail($id);

        // print_r($get_data);
        // die;

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'no_ajuan', 'username', 'jabatan', 'from', 'to', 'nama_status', 'username_on_duty', 'tanggal', 'nama_kategori',
            'nominal_user', 'keterangan', 'keterangan_tempat', 
            'nominal_admin', 'keterangan_admin_biop', 'flag_admin',
            'nominal_atasan1', 'keterangan_atasan1', 'flag_atasan1',
            'nominal_atasan2', 'keterangan_atasan2', 'flag_atasan2',
            'nominal_finance', 'keterangan_finance', 'flag_finance',
            'nominal_head_finance', 'keterangan_head_finance', 'flag_head_finance',
            'bbm_km', 'bbm_liter', 'jamuan_tempat', 'jamuan_alamat', 'jamuan_jenis',
            'jamuan_nama_perusahaan', 'jamuan_pic', 'jamuan_pic_jabatan', 'jamuan_jenis_perusahaan'
        ));
        $this->excel_generator->set_column(array
        (
            'no_ajuan', 'username', 'jabatan', 'from', 'to', 'nama_status', 'username_on_duty', 'tanggal', 'nama_kategori',
            'nominal_user', 'keterangan', 'keterangan_tempat', 
            'nominal_admin', 'keterangan_admin_biop', 'flag_admin',
            'nominal_atasan1', 'keterangan_atasan1', 'flag_atasan1',
            'nominal_atasan2', 'keterangan_atasan2', 'flag_atasan2',
            'nominal_finance', 'keterangan_finance', 'flag_finance',
            'nominal_head_finance', 'keterangan_head_finance', 'flag_head_finance',
            'bbm_km', 'bbm_liter', 'jamuan_tempat', 'jamuan_alamat', 'jamuan_jenis',
            'jamuan_nama_perusahaan', 'jamuan_pic', 'jamuan_pic_jabatan', 'jamuan_jenis_perusahaan'
        ));
        $this->excel_generator->set_width(array(15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15));
        $this->excel_generator->exportTo2007('Biop Export');

    }

    public function ajuan_biop_data_update_tanggal($signature) {

        $tanggal_uang_keluar = $this->input->post('tanggal_uang_keluar');
        // echo 'TANGGAL UANG KELUAR : '.$tanggal_uang_keluar;
        // echo "<pre>";
        // echo "update tanggal biop";
        // echo "<br>signature : ".$signature;
        // die;
        $this->model_management_biop->update_tanggal_uang_keluar_biop($signature, $tanggal_uang_keluar);
        $this->session->set_flashdata('pesan_success', 'Tanggal biop berhasil di update');
        redirect("management_biop/ajuan_biop_verifikasi_admin_finance/$signature");
    }

    function filter_email_biop($data)
    {
        if (count($data) <= 0 || $data == null) {
            $this->session->set_flashdata('pesan', 'Maaf, Approval biop tidak ditemukan');
            redirect("management_biop/ajuan_biop");
        }
    }

    public function send_email_biop($signature, $role)
    {
        // --- Ambil data ---
        $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
        $this->filter_data_biop($get_biop);

        $approval = $this->model_management_biop->get_approval_by_userid($get_biop->userid)->row();
        $this->filter_email_biop($approval);

        $get_detail = $this->model_management_biop->getAll_biop_detail_by_signature($signature);
        $this->filter_data_biop_detail($get_detail);

        $from = $approval->email_pelaksana;
        $no_ajuan = $get_biop->no_ajuan;

        $data = [
            'no_ajuan'          => $no_ajuan,
            'username'          => $get_biop->pic_name,
            'username_on_duty'  => $get_biop->username_on_duty,
            'jabatan'           => $get_biop->jabatan,
            'from'              => $get_biop->from,
            'to'                => $get_biop->to,
            'signature'         => $signature,
            'get_detail'        => $get_detail
        ];

        // ROLE HANDLING
        if ($role == "user") {
            $to         = $approval->email_admin_claim;
            $subject    = "Pengajuan BIOP | $no_ajuan | Pending Admin Claim";
            // $message_to = $this->load->view("management_biop/email_biop",$data,TRUE);

            $flash_message = 'Email Terkirim, Data Biop berhasil diproses ke Admin Claim';
            $redirect = "management_biop/ajuan_biop_proses/$signature";
        }

        else if ($role == "admin_claim") {
            $to         = $approval->email_atasan_1;
            $subject    = "Pengajuan BIOP | $no_ajuan | Pending Atasan 1";
            // $message_to = $this->load->view("management_biop/email_biop",$data,TRUE);

            $flash_message = 'Email Terkirim, Data Biop berhasil diproses ke Atasan 1';
            $redirect = "management_biop/ajuan_biop_verifikasi_admin_claim/$signature";
        }

        else if ($role == "atasan1") {
            $to         = $approval->email_atasan_2;
            $subject    = "Pengajuan BIOP | $no_ajuan | Pending Atasan 2";
            // $message_to = $this->load->view("management_biop/email_biop",$data,TRUE);

            $flash_message = 'Email Terkirim, Data Biop berhasil diproses ke Atasan 2';
            $redirect = "management_biop/ajuan_biop_verifikasi_atasan1/$signature";
        }

        else if ($role == "atasan2") {
            $to         = $approval->email_admin_finance;
            $subject    = "Pengajuan BIOP | $no_ajuan | Pending Admin Finance";
            // $message_to = $this->load->view("management_biop/email_biop",$data,TRUE);

            $flash_message = 'Email Terkirim, Data Biop berhasil diproses ke Admin Finance';
            $redirect = "management_biop/ajuan_biop_verifikasi_atasan2/$signature";
        }

        else if ($role == "admin_finance") {
            $to         = $approval->email_head_finance;
            $subject    = "Pengajuan BIOP | $no_ajuan | Pending Head Finance";
            // $message_to = $this->load->view("management_biop/email_biop",$data,TRUE);

            $flash_message = 'Email Terkirim, Data Biop berhasil diproses ke Head Finance';
            $redirect = "management_biop/ajuan_biop_verifikasi_admin_finance/$signature";
        }
        
        else if ($role == "head_finance") {
            $to         = $from;
            $subject    = "Pengajuan BIOP | $no_ajuan | Approved by Head Finance";
            // $message_to = $this->load->view("management_biop/email_biop",$data,TRUE);

            $flash_message = 'Email Terkirim, Data Biop berhasil di Approved';
            $redirect = "management_biop/ajuan_biop_verifikasi_head_finance/$signature";
        }

        else {
            show_error("Role tidak dikenal");
        }

        // $from = $this->email->from($from, 'PT. Mulia Putra Mandiri');
        // $to = $this->email->to("zaidan@muliaputramandiri.com");
        // $cc = $this->email->cc("millarosianad2@gmail.com");
        // $subject = $this->email->subject($subject);
        // $this->email->message($message_to);
        // $this->email->message($this->load->view("management_biop/email_biop",$data,TRUE));

        // $email_data =[
        //     'from'      => 'milla@muliaputramandiri.com',
        //     'to'        => $to,
        //     'cc'        => 'millarosianad2@gmail.com, '.$from,
        //     'subject'   => $subject,
        //     'message'   => $this->load->view("management_biop/email_biop",$data,TRUE)
        // ];
        $email_data = [
        'from'      => 'milla@muliaputramandiri.com',
        'from_name' => 'PT. Mulia Putra Mandiri',
        'to'        => $to,
        'cc'        => 'millarosianad2@gmail.com, '.$from,
        'subject'   => $subject,
        'message'   => $this->load->view("management_biop/email_biop",$data,TRUE)
        ];

        // SEND EMAIL
        $send = $this->model_relokasi->send_email($email_data, 'biop');
        // redirect($redirect);

        if ($send) {
            $this->session->set_flashdata('pesan_success', $flash_message);
        } else {
            $this->session->set_flashdata('pesan',
                "Email gagal terkirim: ".$this->email->print_debugger()
            );
        }

        redirect($redirect);
    }

    // public function send_email_biop($signature, $role)
    // {
    //     // 1. Ambil data BI-OP
    //     $get_biop = $this->model_management_biop->get_ajuan_biop_by_signature($signature);
    //     $this->filter_data_biop($get_biop);

    //     $approval = $this->model_management_biop->get_approval_by_userid($get_biop->userid)->row();
    //     $this->filter_email_biop($approval);

    //     $get_detail = $this->model_management_biop->getAll_biop_detail_by_signature($signature);
    //     $this->filter_data_biop_detail($get_detail);

    //     // echo "<pre>";
    //     // print_r($approval);
    //     // echo "</pre>"; 
    //     $from = $approval->email_pelaksana;
    //     $no_ajuan = $get_biop->no_ajuan;

    //     // echo "Mengirim email biop untuk no ajuan : ".$no_ajuan." dengan role : ".$role."<br>";
    //     // echo "Dari : ".$from."<br>";die;

    //     $data = [
    //         'no_ajuan' => $no_ajuan,
    //         'username' => $get_biop->pic_name,
    //         'username_on_duty' => $get_biop->username_on_duty,
    //         'jabatan' => $get_biop->jabatan,
    //         'from' => $get_biop->from,
    //         'to' => $get_biop->to,
    //         'signature' => $signature,
    //         'get_detail' => $get_detail
    //     ];

    //     // 2. Mapping role → to, subject, message
    //     $role_map = [
    //         'admin_claim' => [
    //             'to' => $approval->email_admin_claim,
    //             'subject' => "Pengajuan BIOP | $no_ajuan | Pending Admin Claim",
    //             'message' => $this->load->view("management_biop/email_admin_claim",$data,TRUE),
    //             'log_message' => $this->session->set_flashdata('pesan_success', 'Email Terkirim, Data Biop berhasil di proses ke admin'),
    //             'redirect' => redirect("management_biop/ajuan_biop_proses/$signature")
    //         ],

    //         'atasan1' => [
    //             'to' => $approval->email_atasan_1,
    //             'subject' => "Pengajuan BIOP | $no_ajuan | Pending Atasan 1",
    //             'message' => $this->load->view("management_biop/email_ajuan_biop_verifikasi_atasan1",$data,TRUE),
    //             'log_message' => $this->session->set_flashdata('pesan_success', 'Email Terkirim, Data Biop berhasil di proses ke Atasan 1'),
    //             'redirect' => redirect("management_biop/ajuan_biop_verifikasi_atasan1/$signature")
    //         ],

    //         'atasan2' => [
    //             'to' => $approval->email_atasan_2,
    //             'subject' => "Pengajuan BIOP | $no_ajuan | Pending Atasan 2",
    //             'message' => $this->load->view("management_biop/email_ajuan_biop_verifikasi_atasan2",$data,TRUE),
    //             'log_message' => $this->session->set_flashdata('pesan_success', 'Email Terkirim, Data Biop berhasil di proses ke Atasan 2'),
    //             'redirect' => redirect("management_biop/ajuan_biop_verifikasi_atasan2/$signature")
    //         ],

    //         'admin_finance' => [
    //             'to' => $approval->email_admin_finance,
    //             'subject' => "Pengajuan BIOP | $no_ajuan | Pending Admin Finance",
    //             'message' => $this->load->view("management_biop/email_ajuan_biop_verifikasi_admin_finance",$data,TRUE),
    //             'log_message' => $this->session->set_flashdata('pesan_success', 'Email Terkirim, Data Biop berhasil di proses ke Admin Finance'),
    //             'redirect' => redirect("management_biop/ajuan_biop_verifikasi_admin_finance/$signature")
    //         ],

    //         'head_finance' => [
    //             'to' => $approval->email_head_finance,
    //             'subject' => "Pengajuan BIOP | $no_ajuan | Pending Head Finance",
    //             'message' => $this->load->view("management_biop/email_juan_biop_verifikasi_head_finance",$data,TRUE),
    //             'log_message' => $this->session->set_flashdata('pesan_success', 'Email Terkirim, Data Biop berhasil di proses ke Head Finance'),
    //             'redirect' => redirect("management_biop/ajuan_biop_verifikasi_head_finance/$signature")
    //         ],
    //     ];

    //     // Role tidak ditemukan
    //     if (!isset($role_map[$role])) {
    //         show_error("Role email BIOP tidak dikenal.");
    //     }

    //     // Ambil konfigurasi role
    //     $to      = $role_map[$role]['to'];
    //     $subject = $role_map[$role]['subject'];
    //     $message = $role_map[$role]['message'];

    //     // CC default
    //     $cc = "millarosianad2@gmail.com";
    //     $this->load->model('model_relokasi');
    //     $config = $this->model_relokasi->email();
    //     $this->email->from($from, 'PT. Mulia Putra Mandiri');

    //     // To (support array/string)
    //     $this->email->to(is_array($to) ? implode(',', $to) : $to);

    //     // CC (support array/string/null)
    //     $this->email->cc($cc);

    //     // Subject & Message
    //     $this->email->subject($subject);
    //     $this->email->message($message);

    //     // Send
    //     if ($this->email->send()) {
    //         $log_message = $role_map[$role]['log_message'];
    //         $redirect = $role_map[$role]['redirect'];
    //         // return true;
    //     } else {
    //         log_message('error', 'Pengajuan Biop Berhasil, Email BIOP gagal: ' . $this->email->print_debugger());
    //         $redirect = $role_map[$role]['redirect'];
    //         // return false;
    //     }
    // }

}?>