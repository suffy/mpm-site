<?php

use GuzzleHttp\Subscriber\Redirect;

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Upload_file extends MY_Controller
{
    function upload_file()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation'));
        $this->load->helper('url', 'form');
        $this->load->model('model_upload_file');
        $this->load->model('model_sales_omzet');
        $this->load->model('M_menu');
        $this->load->database();
    }

    function index()
    {
        $this->db->select('*');
        $this->db->from('site.temp_portal_akses');
        $this->db->where('proses', 'raw_data');
        $this->db->order_by('id', 'DESC');
        $portal = $this->db->get()->row();

        // var_dump($portal->status);die;
        if ($portal->status == 1 ) {
            $message = "Sedang proses raw data. Silahkan coba beberapa saat lagi atau hubungi IT !";
            $url = base_url('dashboard_dummy');
            echo ("<script LANGUAGE='JavaScript'>
                window.alert('$message');
                window.location.href='$url';
                </script>");
        } 
        
        // $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
        // echo "<script type='text/javascript'>alert('$message');
        //     window.location.href = 'login/home';
        //     </script>";
        $id = $this->session->userdata('id');

        // proses check upload
        $check = $this->db->query("select * from mpm.upload where userid = $id order by id desc limit 1")->row_array();
        $id_upload = $check['id'];
        $flag_check = $check['flag_check'];

        // echo $flag_check;
        // die;
        // var_dump($id_upload);die;
        if ($flag_check == 1 || $flag_check == 3 || $flag_check == 2) {
            $this->reset_flag();
        } elseif ($flag_check == 2) {
            $this->proses_extract_zip();
        } elseif ($flag_check == 4) {
            redirect("upload_file/alert_success/$id_upload");
        } else {
            $this->view_upload();
        }
    }

    public function info_upload()
    {
        $this->load->view('info_upload');
    }

    public function view_upload()
    {
        
        $this->load->library('form_validation');

        $data = [
            'query' => $this->model_upload_file->cek_upload_terakhir(),
            'get_label' => $this->M_menu->get_label(),
            'title' => 'Data Upload',
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_file/upload_form_view', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function file_upload()
    {
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

            // die;
            // mengambil data file upload
            $data = array('upload_data' => $this->upload->data());
            $zip = new ZipArchive;
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $nocab = substr($upload_data['orig_name'], 2, 2);
            $tanggal = substr($upload_data['orig_name'], 6, 2);
            $year  = $this->input->post('year');
            $month = substr($upload_data['orig_name'], 4, 2);
            $status_closing  = $this->input->post('status_closing');

            // echo strlen($filename);
            // die;

            // proses cek kesesuaian file upload
            if (strlen($filename) >= '13') {
                echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
                // die;
                redirect('upload_file/', 'refresh');
            } else {
                // echo "aa";
                // die;
                $data = [
                    'upload_data' => $upload_data,
                    'filename' => $filename,
                    'nocab' => $nocab,
                    'tanggal' => $tanggal,
                    'year' => $year,
                    'month' => $month,
                    'status_closing' => $status_closing,
                ];
                
                if ($this->session->userdata('id') == 547 || $this->session->userdata('id') == 749) {
                    # code...
                    $upl = [
                        'userid' => $this->session->userdata('id'),
                        'lastupload' => $this->model_sales_omzet->timezone2(),
                        'filename' => $filename,
                        'bulan' => $month,
                        'tahun' => $year,
                        'status' => '0',
                        'tanggal' => $tanggal,
                        'flag_check' => '1',
                    ];
                    $this->db->insert('mpm.upload', $upl);
                    $this->proses_extract_zip();
                } else {
                    # code...
                    $this->cek_kesesuaian_upload($data);
                }
                
            }
        }
    }

    public function cek_kesesuaian_upload($data)
    {
        $this->model_upload_file->cek_upload_terakhir($data);
        $this->model_upload_file->cek_kesesuaian_upload($data);
    }

    public function proses_extract_zip()
    {
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set('Asia/Jakarta');

        $id = $this->session->userdata('id');
        $check = $this->db->query("select * from mpm.upload where userid = $id order by id desc limit 1")->row_array();
        $id_upload = $check['id'];
        $lastupload = $check['lastupload'];
        $filename = $check['filename'];
        $nocab = substr($check['filename'], 2, 2);
        $year = $check['tahun'];
        $month = $check['bulan'];
        $tanggal = $check['tanggal'];
        $flag_check = $check['flag_check'];
        $status_closing = $check['status_closing'];

        // membuat folder
        if (!is_dir('./assets/uploads/zip/')) {
            @mkdir('./assets/uploads/zip/', 0777);
        }

        $config['upload_path'] = './assets/uploads/zip';
        $config['allowed_types'] = array('zip', 'ZIP');
        $config['max_size'] = '';
        $config['overwrite'] = 'TRUE';

        $this->load->library('upload', $config);
        $zip = new ZipArchive;
        $file = "D:/xampp/htdocs/cisk/assets/uploads/zip/" . $filename;
        //echo $file;

        $openZip = $zip->open($file);

        if ($openZip === TRUE) {
            if ($zip->setPassword("DELTOMED")) {
                if (!$zip->extractTo('./assets/uploads/unzip/' . $nocab . '')) {

                    echo "Extraction failed (wrong password?)";
                } else {
                    $pesan_extract = "Extraction Berhasil";
                }
            }

            $zip->close();
        } else {
            die("Failed opening archive: " . @$zip->getStatusString() . " (code: " . $zip_status . ")");
        }

        $data = [
            'get_label' => $this->M_menu->get_label(),
            'title' => 'Data Upload',
            'nocab' => $nocab,
            'tahun' => $year,
            'bulan' => $month,
            'tanggal' => $tanggal,
            'pesan' => $pesan_extract,
            'filenamezip' => $filename,
            'lastupload' => $lastupload,
        ];

        // var_dump($data);die;

        $query = $this->model_upload_file->proses_data($data);
        $data['omzet'] = $query['omzet'];
        $data['id'] = $query['id'];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_file/upload_diproses', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function prosesOmzet()
    {
        $id = $this->session->userdata('id');
        $check = $this->db->query("select * from mpm.upload where userid = $id order by id desc limit 1")->row_array();
        $id_upload = $check['id'];
        $lastupload = $check['lastupload'];
        $filename = $check['filename'];
        $nocab = substr($check['filename'], 2, 2);
        $year = $check['tahun'];
        $month = $check['bulan'];
        $tanggal = $check['tanggal'];

        $data = [
            'nocab' => $nocab,
            'tahun' => $year,
            'bulan' => $month,
            'tanggal' => $tanggal,
            'id_upload' => $id_upload,
            'status_closing' => $this->input->post('status_closing'),
        ];

        if ($nocab == '0L' || $nocab == '1S') {
            $this->model_upload_file->submitOmzet_supralita($data);
        } else {
            $this->model_upload_file->submitOmzet($data);
        }
        
    }

    public function alert_success()
    {
        
        $data['id_upload'] = $this->uri->segment('3');
        // var_dump($data['id_upload']);die;

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('upload_file/submitOmzet', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function simpanOmzet()
    {
        $id = $this->uri->segment('3');
        // echo($id);die;
        $post['flag_check'] = '0';
        $this->db->where('id', $id);
        $this->db->update('mpm.upload', $post);

        redirect('upload_file');
    }

    public function reset_flag()
    {
        $userid = $this->session->userdata('id');
        $this->db->select('id');
        $this->db->where('userid', $userid);
        $this->db->order_by('id','DESC');
        $upload = $this->db->get('mpm.upload')->row();

        $this->db->set('flag_check','0');
        $this->db->where('id', $upload->id);
        $this->db->update('mpm.upload');

        redirect('upload_file');
    }
}
