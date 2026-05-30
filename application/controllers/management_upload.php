<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_upload extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_management_upload', 'model_sales_omzet'));
        $this->dataUpload = $this->model_management_upload->get_dataUpload($this->session->userdata('id'));

        
    }
    // function management_upload()
    // {
    //     $logged_in= $this->session->userdata('logged_in');
    //     if(!isset($logged_in) || $logged_in != TRUE)
    //     {
    //         redirect('login/','refresh');
    //     }
    //     set_time_limit(0);
    //     $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    //     $this->load->helper(array('url', 'csv'));
    //     $this->load->model(array('model_management_upload', 'model_sales_omzet'));
    //     $this->dataUpload = $this->model_management_upload->get_dataUpload($this->session->userdata('id'));
    // }

    // function navbar($data)
    // {
    //     if ($this->session->userdata('level') === '4') { // jika dp
    //         $this->load->view('management_office/top_header_dp', $data);
    //     }elseif ($this->session->userdata('level') === '3') { // jika principal
    //         $this->load->view('management_office/top_header_principal', $data);
    //     }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales
    //         $this->load->view('management_office/top_header_principal_nosales', $data);
    //     }elseif ($this->session->userdata('level') === "3b") { // jika principal hanya raw data, claim, rpd
    //         $this->load->view('management_office/top_header_principal_rawdata', $data);
    //     }elseif ($this->session->userdata('level') === "3c") { // jika principal raw_data dan retur dan rpd = RSPH = ghozali yoseph sudarsono
    //         $this->load->view('management_office/top_header_principal_rawdata_retur', $data);
    //     }elseif ($this->session->userdata('level') === "3d") { // jika principal rpd
    //         $this->load->view('management_office/top_header_principal_rpd', $data);
    //     }elseif ($this->session->userdata('level') === '5') { // jika dp mpi
    //         $this->load->view('management_office/top_header_dp_mpi', $data);
    //     }else{
    //         $this->load->view('management_office/top_header', $data);
    //     }
    // }
    function index()
    {
        // if ($this->session->userdata('username') != 'millax'){
        // // ($this->session->userdata('username') == 'bdg' && $this->session->userdata('username') == 'jk1' && $this->session->userdata('username') == 'srg') { // jika dp
        //     //tampilkan alert kalau sedang maintenance
            // echo "<script>alert('Maaf Urgent !!! sedang ada maintenance');document.location='javascript:history.back()'</script>";
                                
        //     redirect('management_office/dashboard');
        //     die;
        // }
        $this->dashboard();
    }

    public function dashboard()
    {
        $this->session->unset_userdata('upload');
        $tahun = '2025';
        $bulan = '12';

        // Ambil data upload
        $query_upload = $this->model_management_upload->get_mpm_upload_by_id($this->session->userdata('id'), $tahun, $bulan);

        $max_tahun = ($query_upload->num_rows() > 0) ? 2026 : 2025;
        // echo $max_tahun;die;

        $data = [
            'title' => 'Data Upload',
            'url'   => 'management_upload/proses_validasi_upload',
            'data_upload' => $this->dataUpload,
            'data_uploadhistory' => $this->model_management_upload->get_dataUpload_all_status($this->session->userdata('id')),
            'max_tahun' => $max_tahun
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/css');
        // $this->load->view('management_upload/dashboard', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_upload/dashboard', $data);
    }

    public function proses_validasi_upload()
    {
        // cek antrian upload data dan raw data
        $proses = $this->model_management_upload->get_temp_portal_akses()->row()->proses;
        $status_antrian = $this->model_management_upload->get_temp_portal_akses()->row()->status;
        if ($status_antrian == '1') {
            $this->session->set_flashdata("pesan_gagal", "Mohon menunggu sedang ada antrian proses $proses, Terima Kasih");
            redirect('management_upload');
        }

        if (!is_dir('./assets/uploads/zip/')) {
            @mkdir('./assets/uploads/zip/', 0777);
        }

        //konfigurasi upload zip
        $config['upload_path'] = './assets/uploads/zip';
        $config['allowed_types'] = 'zip|ZIP|xls|csv|xlsx';
        $config['max_size'] = '*';
        $config['overwrite'] = 'TRUE';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            var_dump($this->upload->display_errors());
            die;
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
        } else {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $nocab = substr($upload_data['orig_name'], 2, 2);
            $tanggal = substr($upload_data['orig_name'], 6, 2);
            $bulan = substr($upload_data['orig_name'], 4, 2);
            $tahun = $this->input->post('year');

            $data = [
                'userid' => $this->session->userdata('id'),
                'lastupload' => $this->model_sales_omzet->timezone2(),
                'filename' => $filename,
                'tanggal' => $tanggal,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'status' => '0',
                'status_closing' => $this->input->post('status_closing')
            ];

            // cek kesesuaian filename upload
            if (strlen($filename) >= '13') {
                $this->session->set_flashdata("pesan_gagal", "File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");
                redirect('management_upload');
            } 
            
            // cek kesesuain nomor cabang
            $nocab_tabcomp = $this->model_management_upload->get_tbl_tabcomp($this->session->userdata('username'),$nocab);
            if ($nocab_tabcomp->num_rows() < 0) {
                $this->session->set_flashdata("pesan_gagal", "File anda tidak sesuai dengan nomor cabang !");
                redirect('management_upload');
            };

            // cek file upload berdasarkan tanggal dan status closing
            $tanggal_berjalan = $this->model_sales_omzet->timezone2();
            $batas_bulan_upload = date('m', strtotime($tanggal_berjalan. '-7 day'));
            $batas_tahun_upload = date('Y', strtotime($tanggal_berjalan. '-7 day'));

            $periode_input = (int)($tahun . sprintf("%02d", $bulan));
            $periode_batas = (int)($batas_tahun_upload . sprintf("%02d", $batas_bulan_upload));

            // if ($bulan == $batas_bulan_upload && $tahun == $batas_tahun_upload) {
            //     $data_historyUpload = $this->model_management_upload->get_dataUpload_status_closing($data);
                
            //     // cek history closing
            //     if ($data_historyUpload->num_rows() > 0) {
            //         $filename_historyUpload = $data_historyUpload->row('filename');
            //         $this->session->set_flashdata("pesan_gagal", "Anda sudah melakukan upload data closing bulan $bulan tahun $tahun dengan file $filename_historyUpload, jika ingin revisi silakan hubung tim IT !");
            //         redirect('management_upload');
            //     } 

            //     // cek tanggal
            //     if(!empty($data_historyUpload->row('tanggal'))){
            //         if ($tanggal < $$this->dataUpload->row()->tanggal) {
            //             $this->session->set_flashdata("pesan_gagal", "Upload gagal. Silahkan upload file terbaru !!");
            //             redirect('management_upload');
            //         }
            //     }
            // } else if ($bulan < $batas_bulan_upload && $tahun <= $batas_tahun_upload) {
            //     $data_historyUpload = $this->model_management_upload->get_dataUpload_status_closing($data);

            //     // cek history closing
            //     if ($data_historyUpload->num_rows() > 0) {
            //         $filename_historyUpload = $data_historyUpload->row('filename');
            //         $this->session->set_flashdata("pesan_gagal", "Anda sudah melakukan upload data closing bulan $bulan tahun $tahun dengan file $filename_historyUpload, jika ingin revisi silakan hubung tim IT !");
            //         redirect('management_upload');
            //     }
            // } elseif ($bulan > $batas_bulan_upload && $tahun >= $batas_tahun_upload) {
            //     $batas['bulan'] = $batas_bulan_upload;
            //     $batas['tahun'] = $batas_tahun_upload;
            //     $data_historyUpload = $this->model_management_upload->get_dataUpload_status_closing_before($data,$batas);
                
            //     // cek history closing
            //     if ($data_historyUpload->num_rows() < 0) {
            //         $filename_historyUpload = $data_historyUpload->row('filename');
            //         $this->session->set_flashdata("pesan_gagal", "Anda sudah melakukan upload data closing bulan $batas_bulan_upload tahun $batas_tahun_upload !");
            //         redirect('management_upload');
            //     } 
            // } else {
            //     $this->session->set_flashdata("pesan_gagal", "Data anda tidak sesuai, Silakan hubungi tim IT !");
            //     redirect('management_upload');
            // }

            if ($periode_input == $periode_batas) {
                // echo "masuk periode input = periode batas<br>";die;
                // KASUS: User upload untuk bulan yang baru saja lewat (Closing periode Desember 2025)
                $data_historyUpload = $this->model_management_upload->get_dataUpload_status_closing($data);
                if ($data_historyUpload->num_rows() > 0) {
                    $filename = $data_historyUpload->row('filename');
                    $this->session->set_flashdata("pesan_gagal", "Anda sudah upload closing bulan $bulan-$tahun ($filename). Hubungi IT untuk revisi.");
                    redirect('management_upload');
                }
                
            } else if ($periode_input < $periode_batas) {
                // KASUS: User upload untuk bulan-bulan yang sudah lama lewat (Backdate)
                $data_historyUpload = $this->model_management_upload->get_dataUpload_status_closing($data);
                if ($data_historyUpload->num_rows() > 0) {
                    $this->session->set_flashdata("pesan_gagal", "Periode $bulan-$tahun sudah closing.");
                    redirect('management_upload');
                }
            } else if ($periode_input > $periode_batas) {
                // KASUS: User upload untuk bulan berjalan atau masa depan (Januari 2026 ke atas)
                // Cek apakah bulan sebelumnya sudah di-upload?
                $batas['bulan'] = $batas_bulan_upload;
                $batas['tahun'] = $batas_tahun_upload;
                $data_history_before = $this->model_management_upload->get_dataUpload_status_closing_before($data, $batas);
                
                // Jika bulan sebelumnya (Desember 2025) BELUM di-upload, maka tidak boleh loncat ke Januari
                if ($data_history_before->num_rows() < 0) {
                    $this->session->set_flashdata("pesan_gagal", "Gagal! Anda harus upload data closing bulan $batas_bulan_upload-$batas_tahun_upload terlebih dahulu.");
                    redirect('management_upload');
                }

            } else {
                // Kondisi pengaman jika ada data tidak valid
                $this->session->set_flashdata("pesan_gagal", "Data tidak sesuai, silakan hubungi tim IT!");
                redirect('management_upload');
            }

            // proses extract zip dan menyimpan ke data tampung db_upload
            $this->proses_extract_zip($data);
            
        }
    }

    public function proses_extract_zip($data)
    {
        // var_dump($data);die;
        $zip = new ZipArchive;
        $file = "C:/xampp/htdocs/cisk/assets/uploads/zip/" . $data['filename'];
        //echo $file;

        $openZip = $zip->open($file);

        if ($openZip === TRUE) {
            $nocab = substr($data['filename'], 2, 2);
            if ($zip->setPassword("DELTOMED")) {
                if (!$zip->extractTo('./assets/uploads/unzip/' . $nocab)) {
                    echo "Extraction failed (wrong password?)";
                }
            }
            $zip->close();
        } else {
            die("Failed opening archive: " . @$zip->getStatusString());
        }

        // proses insert data ke tampung db_upload
        $this->model_management_upload->proses_insert_db_upload($data);

        // proses insert ke mpm upload
        $data['omzet'] = $this->model_management_upload->total_omzet($data)->row('omzet');
        $this->db->insert('mpm.upload', $data);

        $this->session->set_userdata('upload', $data);
        redirect('management_upload/preview_upload');
    }

    public function preview_upload()
    {
        $data = [
            'title' => 'Data Upload',
            'url_simpan' => 'management_upload/simpan_upload',
            'url_reupload' => 'management_upload/re_upload',
            'data_upload' => $this->session->userdata('upload'),
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_claim/css');
        // $this->load->view('management_upload/preview_upload', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_upload/preview_upload', $data);
    }

    public function simpan_upload()
    {
        $data = $this->model_management_upload->get_dataUpload_all_status($this->session->userdata('id'));
        $this->model_management_upload->submitOmzet($data);
        
        $update = [
            'status' => '1',
        ];
        $this->db->where('id', $data->row()->id)->update('mpm.upload', $update);
        
        $this->session->set_flashdata("pesan_success", "Data yang anda upload sudah masuk ke website !");
        redirect('management_upload');
    }
    public function re_upload()
    {
        $data = $this->model_management_upload->get_dataUpload_all_status($this->session->userdata('id'));
        $update = [
            'status' => '2',
        ];

        $this->db->where('id', $data->row()->id)->update('mpm.upload', $update);
        redirect('management_upload');
    }
}