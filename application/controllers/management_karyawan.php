<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class management_karyawan extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $logged_in = $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_karyawan', 'model_relokasi'));

        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->created_by = $this->session->userdata('id');
        $this->username = $this->session->userdata('username');
    }

    function index()
    {
        $this->input_management_karyawan();
    }

    public function input_management_karyawan() 
    {

        $raw_status = $this->input->post('status');
        $is_search = $this->input->post('search');

        if ($is_search && !empty($raw_status)) {
            // Simpan JKT88 untuk dikirim balik ke View agar dropdown tidak reset
            $view_search = $raw_status; 
            // Potong jadi 88 untuk kebutuhan query
            $form_search = substr($raw_status, 3);
        } else {
            $view_search = null;
            $form_search = null;
        }

        if ($is_search == 2) {
            $this->export_data_all_karyawan($form_search);
        }
        // $submit_search = $this->input->post('submit');
        // echo "Submit Search: $is_search\n"; // Debugging line, bisa dihapus nanti
        // die;

        // $get_site_code = $this->model_management_karyawan->get_site_code($this->username);
        // if ($get_site_code->num_rows() > 0) {
        //     $site_code = $get_site_code->row()->site_code;
        // }else{
        //     $site_code = "";
        // }

        $get_user = $this->model_management_karyawan->get_user($this->username);
        $site_code  = $get_user->row()->company_site_code;

        if ($site_code != '') {
            // echo "Site Code: $site_code\n";die;
            $get_sub_company = $this->model_management_karyawan->get_sub($site_code, $form_search);
            if($get_sub_company->num_rows() > 0) {
                $sub_company = $get_sub_company->row()->sub;
                $sub_branch = $get_sub_company->row()->nama_comp;
            }else{
                $sub_company = "";
                $sub_branch = "";
            }
        }else{
            $sub_company = "";
            $sub_branch = "";
        }

        $get_ho = $this->model_management_karyawan->get_ho($sub_company);
        if ($get_ho->num_rows() > 0) {
            $ho = $get_ho->row()->branch_name;
        } else {
            $ho = "";
        }

        $data = [
            'title'              => 'Personalia',
            'url'                => 'management_karyawan/save_input_karyawan',
            'url_search'         => 'management_karyawan/input_management_karyawan',
            'get_username'       => $get_user,
            'get_ho'             => $get_ho,
            'sub_branch'         => $sub_branch,
            'site_code'          => $site_code,
            'search'             => $view_search,
            'count_reimbusement' => $this->model_management_karyawan->count_reimbusement(),
            'get_data'           => $this->model_management_karyawan->get_all_karyawan($sub_company, $this->username, $form_search),
            'username'           => $this->username
        ];

        // $this->view($data, false, 'input_management_karyawan');
        $this->render('management_karyawan/input_management_karyawan', $data);
    }

    public function save_input_karyawan()
    {
        $action = $this->input->post('button_action');
        $email_gmail = $this->input->post('email', true);
        $username_karyawan = $this->input->post('username', true);

        $get_atasan = $this->model_management_karyawan->get_atasan($this->input->post('username', true));
        if ($get_atasan->num_rows() > 0) {
            $atasan1 = $get_atasan->row()->name_verifikasi1;
        } else {
            $atasan1 = null;
        }

        // echo "Atasan1: $atasan1\n"; // Debugging line, bisa dihapus nanti
        // die;

        // ================= AMBIL DATA =================
        $data = [
            'site_code'            => $this->input->post('kode_dp', true),
            'username_web'         => $username_karyawan,
            'nomor_kepegawaian'    => $this->input->post('no_kepegawaian', true),
            'nama_lengkap'         => $this->input->post('nama_lengkap', true),
            'jenis_kelamin'        => $this->input->post('jenis_kelamin', true),
            'tempat_lahir'         => $this->input->post('tempat_lahir', true),
            'tanggal_lahir'        => $this->formatTanggal($this->input->post('tanggal_lahir', true)),
            'golongan_darah'       => $this->input->post('golongan_darah', true),
            'status_perkawinan'    => $this->input->post('status_perkawinan', true),
            'agama'                => $this->input->post('agama', true),
            'alamat_ktp'           => $this->input->post('alamat', true),
            'alamat_domisili'      => $this->input->post('alamat_domisili', true),
            'email'                => $email_gmail,
            'email_perusahaan'     => $this->input->post('email_perusahaan', true), // boleh kosong
            'phone'                => $this->input->post('phone', true),
            'nama_kontak_darurat'  => $this->input->post('nama_kontak_darurat', true),
            'nomor_kontak_darurat' => $this->input->post('nomor_kontak_darurat', true),
            'status_karyawan'      => $this->input->post('status_karyawan', true),
            'tanggal_mulai_kerja'  => $this->formatTanggal($this->input->post('tanggal_mulai_kerja', true)),
            'tgl_mulai_kontrak'    => $this->formatTanggal($this->input->post('tgl_mulai_kontrak', true)),
            'tgl_selesai_kontrak'  => $this->formatTanggal($this->input->post('tgl_selesai_kontrak', true)),
            'tgl_karyawan_tetap'   => $this->formatTanggal($this->input->post('tgl_karyawan_tetap', true)),
            'nomor_ktp'            => $this->input->post('nomor_ktp', true),
            'nomor_kk'             => $this->input->post('nomor_kk', true),
            'npwp'                 => $this->input->post('npwp', true),
            'nama_bank'            => $this->input->post('nama_bank', true),
            'nomor_rekening'       => $this->input->post('nomor_rekening', true),
            'nama_rekening'        => $this->input->post('nama_rekening', true),
            'departement'          => $this->input->post('departement', true),
            'divisi'               => $this->input->post('divisi', true),
            'job_level'            => $this->input->post('job_level', true),
            'nama_atasan_langsung' => $atasan1,
            'created_at'           => $this->created_at,
            'created_by'           => $this->created_by,
            'signature'            => 'kry_sig-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd'),
        ];

        // ================= CEK DUPLIKASI KTP =================
        if ($this->model_management_karyawan->check_ktp_exists($data['nomor_ktp'])) {
            $this->session->set_flashdata('pesan', 'Gagal! Nomor KTP sudah ada.');
            redirect('management_karyawan/input_management_karyawan');
            exit;
        }

        // ================= UPLOAD FILE =================
        foreach (['file_ktp', 'file_kk', 'file_npwp'] as $file) {
            $data[$file] = $this->attachment_config($file);
        }

        if ($action == 2 && !$this->isDataLengkap($data)) {
            $data['flag_status'] = 1;
            $data['nama_status'] = 'Draft';
            $sessin_flash = $this->session->set_flashdata('pesan_success', 'Data karyawan tidak lengkap, data disimpan dan status diubah menjadi Draft. Silakan lengkapi data sebelum diajukan ke HRD.');
        }elseif ($action == 2 && $this->isDataLengkap($data)) {
            $data['flag_status'] = 2;
            $data['nama_status'] = 'Pending HRD';
            $sessin_flash = $this->session->set_flashdata('pesan_success', 'Data karyawan lengkap dan berhasil diajukan ke HRD!');
        }elseif ($action == 1) {
            $data['flag_status'] = 1;
            $data['nama_status'] = 'Draft';
            $sessin_flash = $this->session->set_flashdata('pesan_success', 'Data karyawan berhasil disimpan sebagai Draft!');
        }

        // ================= INSERT =================
        $id_karyawan = $this->model_management_karyawan->insert_karyawan($data);

        if (!empty($email_gmail)) {
            $data_user = [
                'email'       => $email_gmail
            ];

            // contoh pakai id_karyawan
            $this->model_management_karyawan->update_user_by_username_karyawan($username_karyawan, $data_user);
        }

        // ================= DETAIL =================
        $this->handlePendidikan($id_karyawan);
        $this->handleKeluarga($id_karyawan);
        $this->handleAsuransi($id_karyawan);

        if ($id_karyawan) {
            $sessin_flash;
        } else {
            $this->session->set_flashdata('pesan', 'Gagal menyimpan data karyawan!');
        }

        redirect('management_karyawan/input_management_karyawan');
    }

    private function isDataLengkap($data)
    {
        // var_dump($data);
        // die;
        $exclude = ['email_perusahaan', 'tgl_mulai_kontrak', 'tgl_selesai_kontrak', 'tgl_karyawan_tetap', 'tanggal_selesai_kerja'];

        foreach ($data as $key => $value) {
            if (in_array($key, $exclude)) {
                // echo "Exclude key: $key\n";
                // echo "Value: $value\n";
                continue;
            }

            if ($value === null || $value === '') {
                return false;
            }
        }

        return true;
    }

    private function attachment_config($field_name)
    {
        // Pastikan folder sudah ada
        $upload_path = './assets/uploads/karyawan/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config['upload_path']      = $upload_path;
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $config['file_name']        = time() . '_' . $field_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!empty($_FILES[$field_name]['name'])) {
            if ($this->upload->do_upload($field_name)) {
                $uploadData = $this->upload->data();
                return $uploadData['file_name']; // sukses upload
            } else {
                // Jika gagal upload, bisa simpan error log atau null
                // log_message('error', 'Upload gagal: ' . $this->upload->display_errors());
                return null;
            }
        } else {
            return null; // Jika tidak ada file
        }
    }

    public function edit_management_karyawan($signature)
    {
        // Get data karyawan berdasarkan signature
        $karyawan = $this->model_management_karyawan->get_karyawan_by_signature($signature);
        
        // Cek apakah data ditemukan
        if (!$karyawan) {
            $this->session->set_flashdata('pesan', 'Data karyawan tidak ditemukan!');
            redirect('management_karyawan/input_management_karyawan');
            return;
        }

        $status_action = $this->input->get('status_action');
        if ($status_action == 'approve' && $karyawan->flag_status == 2) {
            // Hanya admin yang boleh approve
            if ($this->username == 'ratri' || $this->username == 'millax') {
                $data_update = [
                    'flag_status' => 3,
                    'nama_status' => 'Completed',
                    'updated_at'  => $this->created_at,
                    'updated_by'  => $this->created_by,
                ];
                $this->model_management_karyawan->update_karyawan_by_id($karyawan->id, $data_update);
                $this->session->set_flashdata('pesan_success', 'Data karyawan berhasil diapprove!');
            } else {
                $this->session->set_flashdata('pesan', 'Anda tidak memiliki izin untuk mengapprove data ini!');
            }
            redirect('management_karyawan/edit_management_karyawan/' . $signature);
            return;
        }elseif ($status_action == 'approve' && $karyawan->flag_status != 2) {
            $this->session->set_flashdata('pesan', 'Data karyawan tidak dalam status yang dapat diapprove!');
            redirect('management_karyawan/edit_management_karyawan/' . $signature);
            return;
        }

        // echo 'username : '.$this->username;
        // echo '<br>';
        // echo 'username_web : '.$karyawan->username_web;die;

        // Cek apakah user yang sedang login adalah pemilik data
        if ($this->username == 'ratri' || $this->username == 'millax') {
            // Admin boleh mengedit data siapa saja
        }
        elseif ($karyawan->username_web != $this->username) {
            $this->session->set_flashdata('pesan', 'Anda tidak memiliki izin untuk mengedit data ini!');
            redirect('management_karyawan/input_management_karyawan');
            return;
        }

        $data = [
            'title'             => 'Edit Data Karyawan',
            'karyawan'          => $karyawan,
            'list_pendidikan'   => $this->model_management_karyawan->get_pendidikan_by_karyawan_id($karyawan->id),
            'list_keluarga'     => $this->model_management_karyawan->get_keluarga_by_karyawan_id($karyawan->id),
            'list_asuransi'     => $this->model_management_karyawan->get_asuransi_by_karyawan_id($karyawan->id),
            'get_username'      => $this->model_management_karyawan->get_username($this->username),
            'username'          => $this->username,

        ];
        
        $this->render('management_karyawan/edit_management_karyawan', $data);
    }

    public function update_karyawan()
    {
        $signature   = $this->input->post('signature', true);
        $id_karyawan = $this->input->post('id_karyawan', true);
        $action = $this->input->post('button_action');
        $email_gmail = $this->input->post('email', true);

        $get_username_by_id_karyawan = $this->model_management_karyawan->get_username_by_id_karyawan($id_karyawan);
        if ($get_username_by_id_karyawan->num_rows() > 0) {
            $username_karyawan = $get_username_by_id_karyawan->row()->username_web;
        } else {
            $username_karyawan = null;
        }

        $get_atasan = $this->model_management_karyawan->get_atasan($username_karyawan);
        if ($get_atasan->num_rows() > 0) {
            $atasan1 = $get_atasan->row()->name_verifikasi1;
        } else {
            $atasan1 = null;
        }
        

        // Ambil data POST utama karyawan
        $data_karyawan = [
            'nomor_kepegawaian'          => $this->input->post('no_kepegawaian', true),
            'nama_lengkap'               => $this->input->post('nama_lengkap', true),
            'jenis_kelamin'              => $this->input->post('jenis_kelamin', true),
            'tempat_lahir'               => $this->input->post('tempat_lahir', true),
            'tanggal_lahir'              => $this->input->post('tanggal_lahir', true),
            'golongan_darah'             => $this->input->post('golongan_darah', true),
            'status_perkawinan'          => $this->input->post('status_perkawinan', true),
            'agama'                      => $this->input->post('agama', true),
            'alamat_ktp'                 => $this->input->post('alamat', true),
            'alamat_domisili'            => $this->input->post('alamat_domisili', true),
            'email'                      => $email_gmail,
            'email_perusahaan'           => $this->input->post('email_perusahaan', true),
            'phone'                      => $this->input->post('phone', true),
            'nama_kontak_darurat'        => $this->input->post('nama_kontak_darurat', true),
            'nomor_kontak_darurat'       => $this->input->post('nomor_kontak_darurat', true),
            'status_karyawan'            => $this->input->post('status_karyawan', true),
            'tanggal_mulai_kerja'        => $this->formatTanggal($this->input->post('tanggal_mulai_kerja', true)),
            'tgl_mulai_kontrak'          => $this->formatTanggal($this->input->post('tgl_mulai_kontrak', true)),
            'tgl_selesai_kontrak'        => $this->formatTanggal($this->input->post('tgl_selesai_kontrak', true)),
            'tgl_karyawan_tetap'         => $this->formatTanggal($this->input->post('tgl_karyawan_tetap', true)),
            'tanggal_selesai_kerja'      => $this->formatTanggal($this->input->post('tanggal_selesai_kerja', true)),
            'nomor_ktp'                  => $this->input->post('nomor_ktp', true),
            'nomor_kk'                   => $this->input->post('nomor_kk', true),
            'npwp'                       => $this->input->post('npwp', true),
            'nama_bank'                  => $this->input->post('nama_bank', true),
            'nomor_rekening'             => $this->input->post('nomor_rekening', true),
            'nama_rekening'              => $this->input->post('nama_rekening', true),
            'departement'                => $this->input->post('departement', true),
            'divisi'                     => $this->input->post('divisi', true),
            'job_level'                  => $this->input->post('job_level', true),
            'nama_atasan_langsung'       => $atasan1,
            'updated_at'                 => date('Y-m-d H:i:s'),
            'updated_by'                 => $this->created_by,
        ];

        // if ($action == 2 && !$this->isDataLengkap($data_karyawan)) {
        //     // echo "<pre>";
        //     // print_r($data_karyawan);die;
        //     $this->session->set_flashdata(
        //         'pesan',
        //         'Gagal! Data karyawan belum lengkap untuk diajukan ke HRD.'
        //     );
        //     redirect('management_karyawan/edit_management_karyawan/' . $signature);
        //     die;
        // }

        // Upload file jika ada
        $files = ['file_ktp','file_kk','file_npwp'];
        foreach ($files as $file) {
            if (!empty($_FILES[$file]['name'])) {
                $upload_result = $this->attachment_config($file);
                if ($upload_result) {
                    $data_karyawan[$file] = $upload_result;
                }
            }
        }

        if (!empty($email_gmail)) {
            $data_user = [
                'email'       => $email_gmail
            ];

            // contoh pakai id_karyawan
            $this->model_management_karyawan->update_user_by_username_karyawan($username_karyawan, $data_user);
        }

        // Update data karyawan
        $update = $this->model_management_karyawan->update_karyawan($signature, $data_karyawan);

        $this->handlePendidikan($id_karyawan);
        $this->handleKeluarga($id_karyawan);
        $this->handleAsuransi($id_karyawan);

        if ($action == 2 && !$this->isDataLengkap($data_karyawan)) {
            $data['flag_status'] = 1;
            $data['nama_status'] = 'Draft';
            $sessin_flash = $this->session->set_flashdata('pesan_success', 'Data karyawan tidak lengkap, data disimpan dan status diubah menjadi Draft. Silakan lengkapi data sebelum diajukan ke HRD.');
        }elseif ($action == 2 && $this->isDataLengkap($data_karyawan)) {
            $data['flag_status'] = 2;
            $data['nama_status'] = 'Pending HRD';
            $sessin_flash = $this->session->set_flashdata('pesan_success', 'Data karyawan lengkap dan berhasil diajukan ke HRD!');
        }elseif ($action == 1) {
            $data['flag_status'] = 1;
            $data['nama_status'] = 'Draft';
            $sessin_flash = $this->session->set_flashdata('pesan_success', 'Data karyawan berhasil disimpan sebagai Draft!');
        }

        $this->model_management_karyawan->update_karyawan_by_id($id_karyawan, $data);

        // Flash message dan redirect
        if ($update) {
            $sessin_flash;
        } else {
            $this->session->set_flashdata('pesan', 'Gagal update data karyawan!');
        }
        
        redirect('management_karyawan/edit_management_karyawan/' . $signature);
    }

    private function isRowEmpty($row, $fields)
    {
        foreach ($fields as $f) {
            if (!empty($row[$f])) {
                return false;
            }
        }
        return true;
    }

    private function handlePendidikan($id_karyawan)
    {
        $list = $this->input->post('pendidikan');
        if (empty($list)) return;

        foreach ($list as $row) {

            if ($this->isRowEmpty($row, ['jenjang','institusi','jurusan'])) {
                continue;
            }

            if (!empty($row['id'])) {

                if (!empty($row['deleted'])) {
                    $this->model_management_karyawan->softDelete_m_pendidikan($row['id']);
                } else {
                    $data = [
                        'id_karyawan'            => $id_karyawan,
                        'pendidikan_terakhir'    => $row['jenjang'],
                        'institusi_pendidikan'   => $row['institusi'],
                        'jurusan'                => $row['jurusan'],
                    ];
                    $this->model_management_karyawan->update_m_pendidikan($row['id'], $data);
                }

            } else {
                if (empty($row['deleted'])) {
                    $data = [
                        'id_karyawan'            => $id_karyawan,
                        'pendidikan_terakhir'    => $row['jenjang'],
                        'institusi_pendidikan'   => $row['institusi'],
                        'jurusan'                => $row['jurusan'],
                        'created_at'             => $this->created_at,
                        'created_by'             => $this->created_by
                    ];
                    $this->model_management_karyawan->insert_m_pendidikan($data);
                }
            }
        }
    }

    private function handleKeluarga($id_karyawan)
    {
        $list = $this->input->post('keluarga');
        if (empty($list)) return;

        foreach ($list as $row) {

            if ($this->isRowEmpty($row, ['nama','hubungan','pendidikan','pekerjaan'])) {
                continue;
            }

            if (!empty($row['id'])) {

                if (!empty($row['deleted'])) {
                    $this->model_management_karyawan->softDelete_m_keluarga($row['id']);
                } else {
                    $data = [
                        'id_karyawan' => $id_karyawan,
                        'nama' => $row['nama'],
                        'hubungan' => $row['hubungan'],
                        'pendidikan' => $row['pendidikan'],
                        'pekerjaan' => $row['pekerjaan'],
                    ];
                    $this->model_management_karyawan->update_m_keluarga($row['id'], $data);
                }

            } else {
                if (empty($row['deleted'])) {
                    $data = [
                        'id_karyawan' => $id_karyawan,
                        'nama' => $row['nama'],
                        'hubungan' => $row['hubungan'],
                        'pendidikan' => $row['pendidikan'],
                        'pekerjaan' => $row['pekerjaan'],
                        'created_at' => $this->created_at,
                        'created_by' => $this->created_by
                    ];
                    $this->model_management_karyawan->insert_m_keluarga($data);
                }
            }
        }
    }

    private function handleAsuransi($id_karyawan)
    {
        $list = $this->input->post('asuransi');

        if (empty($list)) return;

        foreach ($list as $row) {

            if ($this->isRowEmpty($row, ['nomor_kartu','nomor_polis','plan','nomor_peserta'])) {
                continue;
            }

            if (!empty($row['id'])) {

                if (!empty($row['deleted'])) {
                    $this->model_management_karyawan->softDelete_m_asuransi($row['id']);
                } else {
                    $data = [
                        'id_karyawan' => $id_karyawan,
                        'nomor_kartu_asuransi' => $row['nomor_kartu'],
                        'nomor_polis_asuransi' => $row['nomor_polis'],
                        'plan_asuransi' => $row['plan'],
                        'nomor_peserta_asuransi' => $row['nomor_peserta'],
                    ];
                    $this->model_management_karyawan->update_m_asuransi($row['id'], $data);
                }

            } else {
                if (empty($row['deleted'])) {
                    $data = [
                        'id_karyawan' => $id_karyawan,
                        'nomor_kartu_asuransi' => $row['nomor_kartu'],
                        'nomor_polis_asuransi' => $row['nomor_polis'],
                        'plan_asuransi' => $row['plan'],
                        'nomor_peserta_asuransi' => $row['nomor_peserta'],
                        'created_at' => $this->created_at,
                        'created_by' => $this->created_by
                    ];
                    $this->model_management_karyawan->insert_m_asuransi($data);
                }
            }
        }
    }

    public function get_dp_by_perusahaan()
    {
        $kode_perusahaan = $this->input->post('kode_perusahaan');
        $sub = substr($kode_perusahaan, -2);
        $data = $this->model_management_karyawan->get_dp_by_perusahaan($sub);
        echo json_encode($data);
    }

    public function export_pdf($signature)
    {   
        // Get data karyawan berdasarkan signature
        $karyawan = $this->model_management_karyawan->get_karyawan_by_signature($signature);
        
        // Cek apakah data ditemukan
        if (!$karyawan) {
            $this->session->set_flashdata('pesan', 'Data karyawan tidak ditemukan!');
            redirect('management_karyawan/input_management_karyawan');
            return;
        }

        // Cek apakah user yang sedang login adalah pemilik data
        if ($this->username == 'ratri' || $this->username == 'millax') {
            // Admin boleh mengedit data siapa saja
        }
        elseif ($karyawan->username_web != $this->username) {
            $this->session->set_flashdata('pesan', 'Anda tidak memiliki izin untuk export data ini!');
            redirect('management_karyawan/input_management_karyawan');
            return;
        }

        $data = [
            'karyawan'          => $karyawan,
            'list_pendidikan'   => $this->model_management_karyawan->get_pendidikan_by_karyawan_id($karyawan->id),
            'list_keluarga'     => $this->model_management_karyawan->get_keluarga_by_karyawan_id($karyawan->id),
            // 'list_asuransi'     => $this->model_management_karyawan->get_asuransi_by_karyawan_id($karyawan->id),
        ];

        $this->load->library('mypdf');
        // Output PDF ke browser
        $generate_pdf = $this->mypdf->generate('management_karyawan/generate_karyawan_pdf', $data, 'A4', 'portrait');
    }


    // public function export_excel($signature)
    // {
    //     // Get data karyawan berdasarkan signature
    //     $karyawan = $this->model_management_karyawan->get_karyawan_by_signature($signature);
        
    //     // Cek apakah data ditemukan
    //     if (!$karyawan) {
    //         $this->session->set_flashdata('pesan', 'Data karyawan tidak ditemukan!');
    //         redirect('management_karyawan/input_management_karyawan');
    //         return;
    //     }

    //     // Cek apakah user yang sedang login adalah pemilik data
    //     if ($this->username == 'ratri' || $this->username == 'milla') {
    //         // Admin boleh mengedit data siapa saja
    //     }
    //     elseif ($karyawan->username_web != $this->username) {
    //         $this->session->set_flashdata('pesan', 'Anda tidak memiliki izin untuk export data ini!');
    //         redirect('management_karyawan/input_management_karyawan');
    //         return;
    //     }

    //     $data = [
    //         'karyawan'          => $karyawan,
    //         'list_pendidikan'   => $this->model_management_karyawan->get_pendidikan_by_karyawan_id($karyawan->id),
    //         'list_keluarga'     => $this->model_management_karyawan->get_keluarga_by_karyawan_id($karyawan->id),
    //         'list_asuransi'     => $this->model_management_karyawan->get_asuransi_by_karyawan_id($karyawan->id),
    //     ];

    //     $this->load->library('myexcel');
    //     // Output Excel ke browser
    //     $generate_excel = $this->myexcel->generate('management_karyawan/generate_karyawan_excel', $data, 'Karyawan_' . $karyawan->nama_lengkap);
    // }

    public function export_data_all_karyawan($status)
    {
        // username yang boleh upload semua data
        $super_user = ['ratri', 'millax'];

        if (!in_array($this->username, $super_user)) {
            if ($this->username != $super_user[0] && $this->username != $super_user[1]) {
                $this->session->set_flashdata('pesan',"Anda tidak memiliki izin untuk mengakses fitur ini!");
                redirect('management_karyawan', 'refresh');
                exit;
            }
        }
        
        $data_karyawan = $this->model_management_karyawan->get_all_karyawan('' , $this->username, $status)->result_array();    

        if ($this->username == 'ratri' || $this->username == 'millax') 
        {
            $selected_columns = [
                'branch_name',
                'nama_comp',
                'site_code',
                'username_web',
                'nomor_kepegawaian',
                'nama',
                'nama_lengkap',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'golongan_darah',
                'status_perkawinan',
                'agama',
                'alamat_ktp',
                'phone',
                'alamat_domisili',
                'nama_kontak_darurat',
                'nomor_kontak_darurat',
                'nomor_ktp',
                'nomor_kk',
                'npwp',
                'nomor_rekening',
                'nama_rekening',
                'nama_bank',
                'email',
                'email_perusahaan',
                'departement',
                'divisi',
                'job_level',
                'status_karyawan',
                'nama_atasan_langsung',
                'tanggal_mulai_kerja',
                'tanggal_selesai_kerja',
                'tgl_mulai_kontrak',
                'tgl_selesai_kontrak',
                'tgl_karyawan_tetap'
            ];

        }else{
            $selected_columns = [
                'branch_name',
                'nama_comp',
                'nama_lengkap',
                'departement',
                'divisi',
                'job_level'
            ];
        }

        // 4. Header CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Export_karyawan.csv');
        $output = fopen('php://output', 'w');

        // 5. Tulis header
        fputcsv($output, $selected_columns);

        // 6. Tulis isi data
        foreach ($data_karyawan as $row) {
            $line = [];
            foreach ($selected_columns as $col) {
                $line[] = isset($row[$col]) ? $row[$col] : ''; // Handle null atau kolom tidak ada
            }
            fputcsv($output, $line);
        }

        fclose($output);
        exit;
    }

    public function export_csv($signature)
    {
        $karyawan = $this->model_management_karyawan->get_karyawan_by_signature($signature);
        $pendidikan = $this->model_management_karyawan->get_pendidikan_by_karyawan_id($karyawan->id);
        $keluarga   = $this->model_management_karyawan->get_keluarga_by_karyawan_id($karyawan->id);
        $asuransi   = $this->model_management_karyawan->get_asuransi_by_karyawan_id($karyawan->id);

        $pendidikan = is_array($pendidikan) ? $pendidikan : [];
        $keluarga   = is_array($keluarga)   ? $keluarga   : [];
        $asuransi   = is_array($asuransi)   ? $asuransi   : [];

        $data = [];

        // DATA PRIBADI
        // ======================
        $data[] = ['DATA PRIBADI'];
        $data[] = [];

        $data[] = ['Nama Lengkap', $karyawan->nama_lengkap];
        $data[] = ['Username Web', $karyawan->username_web];
        $data[] = ['Site Code', $karyawan->site_code];
        $data[] = ['Sub Branch / DP', $karyawan->nama_comp];
        $data[] = ['No. Kepegawaian', $karyawan->nomor_kepegawaian];
        $data[] = ['Jenis Kelamin', $karyawan->jenis_kelamin];
        $data[] = ['Tempat Lahir', $karyawan->tempat_lahir];
        $data[] = ['Tanggal Lahir', $karyawan->tanggal_lahir];
        $data[] = ['Golongan Darah', $karyawan->golongan_darah];
        $data[] = ['Status Perkawinan', $karyawan->status_perkawinan];
        $data[] = ['Agama', $karyawan->agama];
        $data[] = ['Alamat KTP', $karyawan->alamat_ktp];
        $data[] = ['Alamat Domisili', $karyawan->alamat_domisili];
        $data[] = ['Email Pribadi', $karyawan->email];
        $data[] = ['Email Perusahaan', $karyawan->email_perusahaan];
        $data[] = ['No. HP', $karyawan->phone];
        $data[] = [
            'Kontak Darurat',
            $karyawan->nama_kontak_darurat . ' (' . $karyawan->nomor_kontak_darurat . ')'
        ];
        $data[] = [];


        $data[] = ['DATA KEPEGAWAIAN'];
        $data[] = [];

        $data[] = ['Status Karyawan', $karyawan->status_karyawan];
        $data[] = ['Tanggal Mulai Kerja', $karyawan->tanggal_mulai_kerja];
        $data[] = ['Tanggal Selesai Kerja', $karyawan->tanggal_selesai_kerja];
        $data[] = ['Departement', $karyawan->departement];
        $data[] = ['Divisi', $karyawan->divisi];
        $data[] = ['Job Level', $karyawan->job_level];
        $data[] = ['Atasan Langsung', $karyawan->nama_atasan_langsung];

        $data[] = [];


        // ======================
        // PENDIDIKAN
        // ======================
        $data[] = ['PENDIDIKAN'];
        $data[] = ['Jenjang', 'Institusi', 'Jurusan'];

        foreach ($pendidikan as $p) {
            $data[] = [
                $p->pendidikan_terakhir,
                $p->institusi_pendidikan,
                $p->jurusan
            ];
        }

        $data[] = [];

        // ======================
        // KELUARGA
        // ======================
        $data[] = ['DATA KELUARGA'];
        $data[] = ['Nama', 'Hubungan', 'Pendidikan', 'Pekerjaan'];

        foreach ($keluarga as $k) {
            $data[] = [
                $k->nama,
                $k->hubungan,
                $k->pendidikan,
                $k->pekerjaan
            ];
        }

        $data[] = [];

        // ======================
        // ASURANSI
        // ======================
        $data[] = ['ASURANSI'];
        $data[] = ['No Kartu', 'No Polis', 'Plan', 'No Peserta'];

        foreach ($asuransi as $a) {
            $data[] = [
                $a->nomor_kartu_asuransi,
                $a->nomor_polis_asuransi,
                $a->plan_asuransi,
                $a->nomor_peserta_asuransi
            ];
        }

        $filename = 'Data_Karyawan_' . $karyawan->nama_lengkap . '.csv';

        array_to_csv($data, $filename);
    }

    public function download_template()
    {
        // Query untuk template kosong
        $query = "
            SELECT  '' as site_code,
                    '' as username,
                    '' as no_kepegawaian,
                    '' as nama_lengkap,
                    '' as jenis_kelamin,
                    '' as tempat_lahir,
                    '' as tanggal_lahir,
                    '' as golongan_darah,
                    '' as status_perkawinan,
                    '' as agama,
                    '' as alamat,
                    '' as alamat_domisili,
                    '' as email,
                    '' as email_perusahaan,
                    '' as phone,
                    '' as nama_kontak_darurat,
                    '' as nomor_kontak_darurat,
                    '' as status_karyawan,
                    '' as tanggal_mulai_kerja,
                    '' as nomor_ktp,
                    '' as nomor_kk,
                    '' as npwp,
                    '' as nama_bank,
                    '' as nomor_rekening,
                    '' as nama_rekening,
                    '' as departement,
                    '' as divisi,
                    '' as job_level
        ";
        
        $hasil = $this->db->query($query);   

        $this->excel_generator->set_query($hasil);

        // Set Header (Yang tampil di baris pertama Excel)
        $this->excel_generator->set_header(array(
            'Site Code*',
            'Username Web*',
            'No Kepegawaian*',
            'Nama Lengkap*',
            'Jenis Kelamin* (Laki-laki/Perempuan)',
            'Tempat Lahir*',
            'Tanggal Lahir* (YYYY-MM-DD)',
            'Golongan Darah* (A/B/AB/O)',
            'Status Perkawinan* (Kawin/Belum Kawin/Duda/Janda)',
            'Agama*',
            'Alamat KTP*',
            'Alamat Domisili*',
            'Email Gmail* (@gmail.com)',
            'Email Perusahaan',
            'Nomor HP*',
            'Nama Kontak Darurat*',
            'No Kontak Darurat*',
            'Status Karyawan* (Tetap/Kontrak/PHL)',
            'Tgl Mulai Kerja* (YYYY-MM-DD)',
            'Nomor KTP*',
            'Nomor KK*',
            'NPWP*',
            'Bank*',
            'No Rekening*',
            'Nama Rekening*',
            'Departement*',
            'Divisi*',
            'Job Level*'
        ));

        // Set Column (Mapping dari query)
        $this->excel_generator->set_column(array(
            'site_code',
            'username',
            'no_kepegawaian',
            'nama_lengkap',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'golongan_darah',
            'status_perkawinan',
            'agama',
            'alamat',
            'alamat_domisili',
            'email',
            'email_perusahaan',
            'phone',
            'nama_kontak_darurat',
            'nomor_kontak_darurat',
            'status_karyawan',
            'tanggal_mulai_kerja',
            'nomor_ktp',
            'nomor_kk',
            'npwp',
            'nama_bank',
            'nomor_rekening',
            'nama_rekening',
            'departement',
            'divisi'
        ));

        // Set Width untuk setiap kolom (dalam satuan karakter)
        $this->excel_generator->set_width(array(12,12,15,20,15,15,20,20,20,10,30,30,25,25,15,20,15,15,20,18,18,20,12,15,20,15,15,15));

        $this->excel_generator->exportTo2007('Template_Import_Karyawan'); 
    }

    public function import_excel()
    {
        // 1. Upload file
        $this->attachment_config('karyawan');

        if (!$this->upload->do_upload('file_excel')) {
            $this->session->set_flashdata(
                'pesan',
                'Gagal upload file: ' . $this->upload->display_errors()
            );
            redirect('management_karyawan');
        }

        $upload_data = $this->upload->data();
        $file_path  = $upload_data['full_path'];

        // 2. Load Excel
        $this->load->library('excel');
        $object = PHPExcel_IOFactory::load($file_path);

        // 3. Validasi sheet
        if ($object->getSheetCount() > 1) {
            $this->session->set_flashdata('pesan', 'File harus 1 sheet saja');
            redirect('management_karyawan');
        }

        $worksheet      = $object->getActiveSheet();
        $highestRow     = $worksheet->getHighestRow();
        $highestColumn  = $worksheet->getHighestColumn();

        // AD = 30 kolom (sesuai template)
        if ($highestColumn != 'AB') {
            $this->session->set_flashdata(
                'pesan',
                'Jumlah kolom tidak sesuai template'
            );
            redirect('management_karyawan');
        }

        if ($highestRow <= 1) {
            $this->session->set_flashdata('pesan', 'Data Excel kosong');
            redirect('management_karyawan');
        }

        // 4. Penampung validasi
        $error_rows = [];
        $ktp_excel  = [];

        // 5. Loop data (mulai baris 2)
        for ($row = 2; $row <= $highestRow; $row++) {

            // --- Kolom wajib ---
            $site_code       = trim($worksheet->getCellByColumnAndRow(0,  $row)->getValue());
            $username        = trim($worksheet->getCellByColumnAndRow(1,  $row)->getValue());
            $nomor_ktp       = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());

            // 5.1 Validasi wajib isi
            if (empty($site_code) || empty($username) || empty($nomor_ktp)) {
                $this->session->set_flashdata("pesan", "Gagal upload file excel. Kolom site_code, username, nomor_ktp tidak boleh kosong (di baris ke-$row)");
                redirect('management_karyawan','refresh');
            }

            // username yang boleh upload semua data
            $super_user = ['ratri', 'millax'];

            if (!in_array($this->username, $super_user)) {
                if ($this->username != $username) {
                    $this->session->set_flashdata('pesan',"Gagal upload file excel. Username di baris ke-$row tidak sesuai dengan username yang login.");
                    redirect('management_karyawan', 'refresh');
                    exit;
                }
            }

            $get_atasan = $this->model_management_karyawan->get_atasan($username);
            if ($get_atasan->num_rows() > 0) {
                $atasan1 = $get_atasan->row()->name_verifikasi1;
            } else {
                $atasan1 = null;
            }

            // duplikat excel
            if (in_array($nomor_ktp, $ktp_excel)) {
                $this->session->set_flashdata("pesan", "Gagal upload file excel. Nomor KTP duplikat di baris ke-$row");
                redirect('management_karyawan','refresh');
            }
            $ktp_excel[] = $nomor_ktp;

            // duplikat database (PAKAI MODEL)
            if ($this->model_management_karyawan->check_ktp_exists($nomor_ktp)) {
                $this->session->set_flashdata("pesan", "Gagal upload file excel. Nomor KTP sudah ada di database (di baris ke-$row)");
                redirect('management_karyawan','refresh');
            }

            $cell = $worksheet->getCellByColumnAndRow(6,  $row); // Kolom tanggal_lahir (indeks 6)
            $cellValue = $cell->getValue();
            if (is_numeric($cellValue)) {
                // echo "is_numeric : ".$cellValue."<br>";
                $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                $tanggal_lahir = date('Y-m-d', $unixTimestamp); // Format as needed
                // echo "tanggal : ".$tanggal."<br>";

                // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                $tanggal_ym = date('Y-m', $unixTimestamp);
            } else {
                $tanggal_lahir = $cellValue;
                // echo "tanggal : ".$tanggal."<br>";
                // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                if (strtotime($tanggal_lahir)) {
                    $tanggal_ym = date('Y-m', strtotime($tanggal_lahir));
                }
            }

            //tanggal mulai kerja
            $cell_tmk = $worksheet->getCellByColumnAndRow(18,  $row); // Kolom tanggal_mulai_kerja (indeks 18)
            $cellValue_tmk = $cell_tmk->getValue();
            if (is_numeric($cellValue_tmk)) {
                $unixTimestamp_tmk = ($cellValue_tmk - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                $tanggal_mulai_kerja = date('Y-m-d', $unixTimestamp_tmk); // Format as needed
            } else {
                $tanggal_mulai_kerja = $cellValue_tmk;
            }

            // 6. Mapping semua kolom
            $data = [
                'site_code'              => $site_code,
                'username_web'           => $username,
                'nomor_kepegawaian'      => trim($worksheet->getCellByColumnAndRow(2,  $row)->getValue()),
                'nama_lengkap'           => trim($worksheet->getCellByColumnAndRow(3,  $row)->getValue()),
                'jenis_kelamin'          => trim($worksheet->getCellByColumnAndRow(4,  $row)->getValue()),
                'tempat_lahir'           => trim($worksheet->getCellByColumnAndRow(5,  $row)->getValue()),
                'tanggal_lahir'          => $tanggal_lahir,
                'golongan_darah'         => trim($worksheet->getCellByColumnAndRow(7,  $row)->getValue()),
                'status_perkawinan'      => trim($worksheet->getCellByColumnAndRow(8,  $row)->getValue()),
                'agama'                  => trim($worksheet->getCellByColumnAndRow(9, $row)->getValue()),
                'alamat_ktp'             => trim($worksheet->getCellByColumnAndRow(10, $row)->getValue()),
                'alamat_domisili'        => trim($worksheet->getCellByColumnAndRow(11, $row)->getValue()),
                'email'                  => trim($worksheet->getCellByColumnAndRow(12, $row)->getValue()),
                'email_perusahaan'       => trim($worksheet->getCellByColumnAndRow(13, $row)->getValue()),
                'phone'                  => trim($worksheet->getCellByColumnAndRow(14, $row)->getValue()),
                'nama_kontak_darurat'    => trim($worksheet->getCellByColumnAndRow(15, $row)->getValue()),
                'nomor_kontak_darurat'   => trim($worksheet->getCellByColumnAndRow(16, $row)->getValue()),
                'status_karyawan'        => trim($worksheet->getCellByColumnAndRow(17, $row)->getValue()),
                'tanggal_mulai_kerja'    => $tanggal_mulai_kerja,
                'nomor_ktp'              => $nomor_ktp,
                'nomor_kk'               => trim($worksheet->getCellByColumnAndRow(20, $row)->getValue()),
                'npwp'                   => trim($worksheet->getCellByColumnAndRow(21, $row)->getValue()),
                'nama_bank'              => trim($worksheet->getCellByColumnAndRow(22, $row)->getValue()),
                'nomor_rekening'         => trim($worksheet->getCellByColumnAndRow(23, $row)->getValue()),
                'nama_rekening'          => trim($worksheet->getCellByColumnAndRow(24, $row)->getValue()),
                'departement'            => trim($worksheet->getCellByColumnAndRow(25, $row)->getValue()),
                'divisi'                 => trim($worksheet->getCellByColumnAndRow(26, $row)->getValue()),
                'job_level'              => trim($worksheet->getCellByColumnAndRow(27, $row)->getValue()),
                'nama_atasan_langsung'   => $atasan1,
                'created_at'             => $this->created_at,
                'created_by'             => $this->created_by,
                'signature'              => 'kry_sig-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd'),
            ];

            $id_karyawan = $this->model_management_karyawan->insert_karyawan($data);

            // $get_data_by_id = $this->model_management_karyawan->get_data_by_id($id_karyawan);
            if ($this->isDataLengkap($data)) {
                $data = [
                    'flag_status' => 2,
                    'nama_status' => 'Pending HRD'
                ];
            } else {
                $data = [
                    'flag_status' => 1,
                    'nama_status' => 'Draft'
                ];
            }
            $this->model_management_karyawan->update_karyawan_by_id($id_karyawan, $data);
        }

        // 7. Summary
        $berhasil = ($highestRow - 1) - count($error_rows);
        $gagal    = count($error_rows);

        $this->session->set_flashdata('pesan_success',"Import selesai. Berhasil: $berhasil | Gagal: $gagal");

        if ($gagal > 0) {
            $this->session->set_flashdata('error_rows', $error_rows);
        }

        redirect('management_karyawan');
    }

    private function formatTanggal($tanggal)
    {
        if (empty($tanggal)) {
            return null;
        }

        return date('Y-m-d', strtotime($tanggal));
    }

    public function reimbursement()
    {

        $id_karyawan = $this->input->post('id_karyawan');
        $start_date  = $this->input->post('bulan_from');
        $end_date    = $this->input->post('bulan_to');
        $is_submit   = $this->input->post('submit');

        if ($this->input->post('search') == 2) {
            $this->export_reimbursement_excel();
            return;
        }

        $get_user = $this->model_management_karyawan->get_user($this->username);
        $site_code  = $get_user->row()->company_site_code;

        if ($site_code != 'MPMHO') {
            $this->session->set_flashdata('pesan', 'Anda tidak memiliki izin untuk mengakses fitur ini!');
            redirect('management_karyawan');
            return;
        }
        
        if ($start_date == '' && $end_date == '' && $id_karyawan == '') {
            $id_karyawan = $this->model_management_karyawan->get_id_karyawan_by_username($this->username)->row()->id;
            $start_date = ''; // Awal bulan ini
            $end_date   = ''; // Akhir bulan ini
        } else {
            $start_date = date('Y-m-01', strtotime($start_date));
            $end_date   = date('Y-m-t', strtotime($end_date)); // t = last day of month
        }

        $data = [
            'title' => 'Form Reimbursement',
            'url'   => 'management_karyawan/reimbursement_submit',
            'url_search' => 'reimbursement',
            'get_username' => $this->model_management_karyawan->get_username($this->username),
            'list_karyawan' => $this->model_management_karyawan->get_karyawan_aktif($this->username),
            'list_kategori' => $this->model_management_karyawan->get_kategori_reimbursement(),
            'reimbursement_list' => $this->model_management_karyawan->get_history_reimbursement_by_karyawan($id_karyawan, $start_date, $end_date)
        ];

        $this->render('management_karyawan/form_reimbursement', $data);
    }


    public function reimbursement_submit()
    {
        $id_karyawan  = $this->input->post('id_karyawan');
        $nominal      = $this->input->post('nominal');
        $keterangan   = $this->input->post('keterangan');
        $tanggal_nota = $this->input->post('tanggal_nota');
        $id_kategori     = $this->input->post('id_kategori');

        $jumlah = preg_replace('/[^0-9]/', '', $nominal);

        if ($this->username != 'ratri' && $this->username != 'milla') 
        {
            $this->session->set_flashdata('error', 'Anda tidak memiliki izin untuk input data reimbursement!');
            redirect('management_karyawan/reimbursement');
            return;
        }

        if (empty($id_karyawan) || empty($jumlah) || empty($keterangan)) {
            $this->session->set_flashdata('error', 'Semua field wajib diisi!');
            redirect('management_karyawan/reimbursement');
            return;
        }

        // Upload
        $this->attachment_config('reimbursement');

        $file_name = null;
        if (!empty($_FILES['file_nota']['name'])) {
            if (!$this->upload->do_upload('file_nota')) {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('management_karyawan/reimbursement');
                return;
            }
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
        }

        // Insert header
        $data = [
            'id_karyawan' => $id_karyawan,
            'no_pengajuan'      => $this->model_management_karyawan->generate($this->created_at),
            'tanggal_pengajuan' => $tanggal_nota,
            'total' => $jumlah,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->created_by
        ];

        $insert = $this->model_management_karyawan->insert_reimbursement($data);

        // Insert detail
        if ($insert) {
            $this->model_management_karyawan->insert_reimbursement_detail([
                'id_reimbursement' => $insert,
                'id_kategori' => $id_kategori,
                'tanggal_nota' => $tanggal_nota,
                'nominal' => $jumlah,
                'keterangan' => $keterangan,
                'file_nota' => $file_name,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->created_by
            ]);
        }

        $get = $this->model_management_karyawan->get_reimbursement_by_id($insert)->row();

        $data_reimbursement = [
            'no_pengajuan' => $get->no_pengajuan,
            'nama_lengkap' => $get->nama_lengkap,
            'nama_kategori' => $get->nama_kategori,
            'tanggal_pengajuan' => $get->tanggal_pengajuan,
            'nominal' => $get->total, 
            'keterangan' => $get->keterangan,
            'get' => $get
        ];

        // var_dump($data_reimbursement);die;

        // send email
        $email_data = [
        'from'      => 'milla@muliaputramandiri.com',
        'from_name' => 'PT. Mulia Putra Mandiri',
        'to'        => 'millarosianad2@gmail.com',
        'subject'   => 'Pengajuan Reimbursement - ' . $get->no_pengajuan . ' - ' . $get->nama_kategori,
        'message'   => $this->load->view("management_karyawan/email_reimbursement",$data_reimbursement,TRUE)
        ];

        // SEND EMAIL
        $send = $this->model_relokasi->send_email($email_data, 'biop');

        if ($send) {
            $this->session->set_flashdata('pesan_success', 'Reimbursement berhasil disimpan! dan Email Berhasil Dikirim');
            redirect('management_karyawan/reimbursement');
        } else {
            $this->session->set_flashdata('pesan', "Data berhasil disimpan, Email gagal terkirim: ".$this->email->print_debugger());
            redirect('management_karyawan/reimbursement');
        }

        // $this->session->set_flashdata('success', 'Reimbursement berhasil disimpan!');
        
    }

    public function search_reimbursement()
    {
        // echo 'masuk search';die;
        $id_karyawan = $this->input->post('id_karyawan');
        $start_date  = $this->input->post('bulan_from');
        $end_date    = $this->input->post('bulan_to');

        $data = $this->model_management_karyawan->get_history_reimbursement_by_karyawan($id_karyawan, $start_date, $end_date);


        // echo json_encode($data);
    }

    public function approve_reimbursement($id)
    {

        if ($this->username != 'nanita' && $this->username != 'milla') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki izin untuk melakukan aksi ini!');
            redirect('management_karyawan/reimbursement');
            return;
        }

        echo 'ID yang diterima: ' . $id; // Debug: pastikan ID diterima dengan benar
        // die;

        $this->db->where('id', $id);
        $this->db->update('site.reimbursement', [
            'status' => 2 // Released
        ]);

        redirect('management_karyawan/reimbursement');
        // echo json_encode(['success' => true]);
    }

    public function approve_all()
    {
        if ($this->username != 'nanita' && $this->username != 'millax') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki izin untuk melakukan aksi ini!');
            redirect('management_karyawan/reimbursement');
            return;
        }

        $this->db->where('status', 1); // pending
        $this->db->update('site.reimbursement', [
            'status' => 2 // released
        ]);

        $this->session->set_flashdata('success', 'Semua data berhasil di-release');
        redirect('management_karyawan/reimbursement');
    }

    public function count_pending()
    {
        // 🔥 bersihin semua output sebelumnya
        ob_clean();

        $this->output->set_content_type('application/json');

        $this->db->where('status', 1);
        $count = $this->db->count_all_results('site.reimbursement');

        echo json_encode(['total' => (int)$count]);
        exit;
    }

    // public function reimbursement_karyawan($signature) 
    // {
    //     $karyawan = $this->model_management_karyawan->get_karyawan_by_signature($signature);
        
    //     // Cek apakah data ditemukan
    //     if (!$karyawan) {
    //         $this->session->set_flashdata('pesan', 'Data karyawan tidak ditemukan!');
    //         redirect('management_karyawan/input_management_karyawan');
    //         return;
    //     }

    //     $id_karyawan = $karyawan->id;

    //     // Cek apakah user yang sedang login adalah pemilik data
    //     if ($this->username == 'ratri' || $this->username == 'millax') {
    //         // Admin boleh mengedit data siapa saja
    //     }
    //     elseif ($karyawan->username_web != $this->username) {
    //         $this->session->set_flashdata('pesan', 'Anda tidak memiliki izin untuk export data ini!');
    //         redirect('management_karyawan/input_management_karyawan');
    //         return;
    //     }

    //     $karyawan = null;
    //     $history  = [];

    //     if ($id_karyawan) {
    //         $karyawan = $this->model_management_karyawan->get_karyawan_by_id($id_karyawan);
    //         $history  = $this->model_management_karyawan->get_history_reimbursement_by_karyawan($id_karyawan, '', '');
    //     }

    //     $data = [
    //         'title'          => 'Form Reimbursement',
    //         'url'            => 'management_karyawan/reimbursement_submit',
    //         'id_karyawan'    => $id_karyawan,
    //         'karyawan'       => $karyawan,
    //         'get_username'  => $this->model_management_karyawan->get_username($this->username),
    //         'list_karyawan'  => $this->model_management_karyawan->get_karyawan_aktif(),
    //         'history'        => $history,
    //         'signature'      => $signature
    //     ];

    //     $this->render('management_karyawan/form_reimbursement', $data);
    // }

    // public function reimbursement_submit_karyawan()
    // {
    //     // echo 'disini';
    //     // die;
    //     $id_karyawan = $this->input->post('id_karyawan');
    //     $nominal      = $this->input->post('nominal');
    //     $keterangan   = $this->input->post('keterangan');
    //     $signature    = $this->input->post('signature');
    //     $tanggal_nota   = $this->input->post('tanggal_nota');

    //     $jumlah = preg_replace('/[^0-9]/', '', $nominal);

    //     // echo 'id_karyawan: ' . $id_karyawan . '<br>';
    //     // echo 'jumlah: ' . $jumlah . '<br>';
    //     // echo 'keterangan: ' . $keterangan . '<br>';
    //     // echo 'signature: ' . $signature . '<br>';
    //     // die;

    //     if (empty($id_karyawan) || empty($jumlah) || empty($keterangan)) {
    //         $this->session->set_flashdata('pesan', 'Semua field wajib diisi!');
    //         redirect('management_karyawan/reimbursement/' . $signature);
    //         return;
    //     }

    //     // Upload file
    //     $this->attachment_config('reimbursement');

    //     if (!$this->upload->do_upload('file_nota')) {
    //         $this->session->set_flashdata('error', 'Gagal upload file: ' . $this->upload->display_errors('', ''));
    //         redirect('management_karyawan/reimbursement/' . $signature);
    //     }

    //     $upload_data   = $this->upload->data();
    //     // $file_nota_path = 'uploads/reimbursement/' . $upload_data['file_name'];


    //     $data = [
    //         'id_karyawan'       => $id_karyawan,
    //         'no_pengajuan'      => $this->model_management_karyawan->generate($this->created_at),
    //         'tanggal_pengajuan' => $tanggal_nota,
    //         'total'             => $jumlah,
    //         'status'            => 1,
    //         'created_at'        => $this->created_at,
    //         'created_by'        => $this->created_by
    //     ];

    //     var_dump($data);die;

    //     $insert = $this->model_management_karyawan->insert_reimbursement($data);

    //     $data_detail = [
    //         'id_reimbursement' => $insert,
    //         'tanggal_nota' => $tanggal_nota,
    //         'nominal' => $jumlah,
    //         'file_nota' => $upload_data['file_name'],
    //         'created_at' => $this->created_at,
    //         'created_by' => $this->created_by
    //     ];

    //     $insert_detail = $this->model_management_karyawan->insert_reimbursement_detail($data_detail);

    //     if ($insert) {
    //         $this->session->set_flashdata('pesan_success', 'Reimbursement berhasil disimpan!');
    //     } else {
    //         $this->session->set_flashdata('pesan', 'Gagal mengajukan reimbursement!');
    //     }

    //     redirect('management_karyawan/reimbursement/' . $this->input->post('signature'));
    // }

    public function export_reimbursement_excel()
    {
        $start = $this->input->post('bulan_from');
        $end   = $this->input->post('bulan_to');

        if ($start == '' || $end == '') {
            $tahun = date('Y');
            $start = $tahun . '-01';
            $end   = $tahun . '-12';
        }

        $start_date = date('Y-m-01', strtotime($start));
        $end_date   = date('Y-m-t', strtotime($end));

        $data['rows'] = $this->model_management_karyawan
            ->get_export_reimbursement($start_date, $end_date);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report_reimbursement.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view(
            'management_karyawan/export_reimbursement_excel',
            $data
        );
    }

}