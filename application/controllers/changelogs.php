<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Changelogs extends MY_Controller
{    
    public function __construct()
    {
        // echo 'disini'; die;
        parent::__construct();
        // Set data khusus untuk controller ini
        $this->data['page_title'] = 'Changelogs';

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_changelogs'));
        $this->session_id = $this->session->userdata('id');
        $this->username = $this->session->userdata('username');
        $this->session_supp = $this->session->userdata('supp');
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->tahun = date('Y');
        $this->bulan = date('m');
    }

    public function index()
    {
        $this->logs();
    }   
    

    public function logs()
    {
        // echo 'disini'; die;
        $data = [
            'title' => 'Management Informasi',
            'url'   => 'changelogs/save_changelogs',
            'function_names' => $this->model_changelogs->get_menu(),
            'logs'  => $this->model_changelogs->get_all_changelogs(),
            'aktif_per_fn'   => $this->model_changelogs->get_aktif_per_function(),
        ];

        $this->render('changelogs/logs', $data);
    }

    public function save_changelogs()
    {
        $function_ids = $this->input->post('function_ids');

        if (empty($function_ids)) {
            $this->session->set_flashdata('pesan', 'Pilih minimal 1 menu.');
            redirect('changelogs/logs');
            return;
        }

        $base_path = './assets/uploads/changelog/'.$this->tahun;
        
        if (!is_dir($base_path)) {
            @mkdir($base_path, 0777, true);
        }

        // Handle upload foto (opsional)
        $foto_name = null;
        if (!empty($_FILES['foto']['name'])) {
            $config = array(
                'upload_path'   => $base_path,
                'allowed_types' => 'jpg|jpeg|png|webp|gif',
                'max_size'      => 2048,  // KB bukan bytes
                'file_name'     => 'changelog_' . time(),
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();       // ← ambil semua data
                $foto_name   = $upload_data['file_name'];   // ← ambil file_name saja
            } else {
                $this->session->set_flashdata('pesan', $this->upload->display_errors('', ''));
                redirect('changelogs/logs');
                return;
            }
        }

        $data = array(
            'title'        => $this->input->post('title'),
            'id_function'  => implode(',', $function_ids),
            'changes'      => $this->input->post('changes'),
            'foto'         => $foto_name,
            'created_by'   => $this->session_id,
            'created_at'   => $this->created_at,
            'status_aktif' => 0,
            'signature'    => 'CLOGS-' . md5('CL' . implode(',', $function_ids) . $this->username . ' - ' . date('d M Y, H:i')),
        );

        $history_id = $this->model_changelogs->insert_changelogs($data);

        if ($history_id) {
            $this->model_changelogs->insert_changelogs_detail($history_id, $function_ids);
            $this->session->set_flashdata('pesan_success', 'Data berhasil disimpan.');
        } else {
            $this->session->set_flashdata('pesan', 'Data gagal disimpan.');
        }

        redirect('changelogs/logs');
    }

    // public function toggle_change_status($id, $status)
    // {
    //     $status = (int)$status; // 0 atau 1

    //     if ($status === 1) {
    //         // Cek apakah sudah 3 aktif
    //         $aktif_count = $this->model_changelogs->count_aktif_changelogs();
    //         if ($aktif_count >= 3) {
    //             $this->session->set_flashdata('pesan', 'Batas maksimal 3 update aktif. Nonaktifkan salah satu terlebih dahulu.');
    //             redirect('changelogs/logs');
    //             return;
    //         }
    //     }

    //     $this->model_changelogs->update_changelogs_status($id, $status);
    //     $msg = ($status === 1) ? 'Update berhasil diaktifkan.' : 'Update berhasil dinonaktifkan.';
    //     $this->session->set_flashdata('pesan_success', $msg);
    //     redirect('changelogs/logs');
    // }

    public function toggle_change_status($signature, $status)
    {
        $status = (int)$status;

        $get_id_function = $this->model_changelogs->get_id_function_by_signature($signature);
        if ($get_id_function->num_rows() == 0) {
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan.');
            redirect('changelogs/logs');
            return;
        }

        $id_function = $get_id_function->row()->id_function;

        if ($status === 1) {
            // Cek per function — ada function yang sudah 3 aktif?
            $max_aktif = $this->model_changelogs->count_aktif_per_function($id_function);
            if ($max_aktif >= 3) {
                $this->session->set_flashdata('pesan', 
                    'Batas maksimal 3 update aktif per menu. Nonaktifkan salah satu di menu yang sama terlebih dahulu.'
                );
                redirect('changelogs/logs');
                return;
            }
        }

        $this->model_changelogs->update_changelogs_status($signature, $status);
        $msg = ($status === 1) ? 'Update berhasil diaktifkan.' : 'Update berhasil dinonaktifkan.';
        $this->session->set_flashdata('pesan_success', $msg);
        redirect('changelogs/logs');
    }

    public function changelogs_delete($signature)
    {
        // echo 's$signature : '.$signature;die;
        $this->model_changelogs->delete_changelogs($signature, $this->session_id);

        $this->session->set_flashdata('pesan_success','Data berhasil dihapus');
        redirect('changelogs/logs');
    }

    public function history_update()
    {
        $id_function = $this->input->get('menu') ?: null; // null = semua menu

        // echo 'id_function: '.$id_function;
        
        $history_raw = $this->model_changelogs->get_read_history_all($this->session_id, $id_function);

        // Grouping by tanggal
        $grouped = [];
        foreach ($history_raw as $row) {
            $grouped[$row->tanggal][] = $row;
        }

        $data['history']       = $grouped;
        $data['menu_list']     = $this->model_changelogs->get_read_function_list($this->session_id);
        $data['active_filter'] = $id_function;

        // $this->load->view('changelogs/history_update', $data);
        $this->render('changelogs/history_update', $data);
    }

    // public function get_detail_history()
    // {
    //     $id = $this->input->get('id');
    //     if (empty($id)) {
    //         echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    //         return;
    //     }
    //     $detail = $this->model_changelogs->get_history_detail($id);
    //     if (empty($detail)) {
    //         echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    //         return;
    //     }
    //     echo json_encode([
    //         'status'  => 'ok',
    //         'title'   => $detail->title,
    //         'changes' => nl2br(htmlspecialchars($detail->changes)),
    //         'tanggal' => date('d M Y, H:i', strtotime($detail->created_at)),
    //     ]);
    // }

    public function get_detail_history()
    {
        $this->output->set_content_type('application/json');
        $this->output->enable_profiler(FALSE);

        $id = $this->input->get('id');
        
        if (empty($id)) {
            echo json_encode(array('status' => 'error', 'message' => 'ID tidak valid'));
            return;
        }

        $detail = $this->model_changelogs->get_history_detail($id);
        if (empty($detail)) {
            echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
            return;
        }

        // Cek apakah sudah dibaca user ini
        $is_read = $this->model_changelogs->check_is_read($this->session_id, $id);

        $foto_url = '';
        if (!empty($detail->foto)) {
            $tahun    = date('Y', strtotime($detail->created_at));
            $foto_url = base_url('assets/uploads/changelog/'.$tahun.'/'.$detail->foto);
        }

        echo json_encode(array(
            'status'   => 'ok',
            'title'    => $detail->title,
            'changes'  => nl2br(htmlspecialchars($detail->changes)),
            'tanggal'  => date('d M Y, H:i', strtotime($detail->created_at)),
            'is_read'  => (int)$is_read,
            'foto_url' => $foto_url,
        ));
    }

    public function mark_single_read()
    {
        $this->output->set_content_type('application/json');
        $this->output->enable_profiler(FALSE);

        $id = $this->input->post('id');
        // $id_function = $this->input->post('id_function'); // ✅ ambil ini

        if (empty($id)) {
            echo json_encode(array('status' => 'error', 'message' => 'ID tidak valid'));
            return;
        }

        $this->model_changelogs->mark_single_read($this->session_id, $id);
        echo json_encode(array('status' => 'ok'));
    }

    // public function detail($signature)
    // {
    //     $log = $this->model_changelogs->get_id_function_by_signature($signature);

    //     if (!$log) {
    //         $this->session->set_flashdata('pesan', 'Data tidak ditemukan.');
    //         redirect('changelogs/logs');
    //         return;
    //     }

    //     $id_history = $log->row()->id;
    //     echo 'id_history: '.$id_history; die;
    //     $readers = $this->model_changelogs->get_readers_by_history($id_history);

    //     $data = [
    //         'title'   => 'Detail Changelog',
    //         'log'     => $log,
    //         'readers' => $readers
    //     ];

    //     $this->render('changelogs/detail', $data);
    // }

    // public function reset_read($history_id)
    // {
    //     $this->model_changelogs->reset_read_log($history_id);

    //     $this->session->set_flashdata('pesan_success', 'Log pembacaan berhasil direset.');
    //     redirect($_SERVER['HTTP_REFERER']);
    // }

    public function detail_changelog($signature)
    {
        $changelog = $this->model_changelogs->get_changelog_by_signature($signature);
        if (empty($changelog)) {
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan.');
            redirect('changelogs/logs');
            return;
        }

        $data = array(
            'title'     => 'Detail Changelog',
            'changelog' => $changelog,
            'readers'   => $this->model_changelogs->get_readers_by_history_id($changelog->id),
        );
        $this->render('changelogs/detail_changelog', $data);
    }

    public function reset_changelog_read($signature)
    {
        $changelog = $this->model_changelogs->get_changelog_by_signature($signature);
        if (empty($changelog)) {
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan.');
            redirect('changelogs/logs');
            return;
        }

        // echo 'Reset log baca untuk changelog ID: '.$changelog->id; die;

        $this->model_changelogs->delete_logs_read_by_history($changelog->id);
        $this->session->set_flashdata('pesan_success', 'Log baca berhasil direset. Popup akan muncul kembali.');
        redirect('changelogs/detail_changelog/'.$signature);
    }
}
