<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Step extends MY_Controller
{    
  public function __construct()
  {
    parent::__construct();
    $this->data['page_title'] = 'Step';

    $logged_in = $this->session->userdata('logged_in');
    if (!isset($logged_in) || $logged_in != TRUE) {
      redirect('login_sistem/', 'refresh');
    }
    set_time_limit(0);

    $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    $this->load->helper(array('url', 'csv'));
    $this->load->model(array('model_outlet_transaksi', 'model_step'));
    $this->created_at = $this->model_outlet_transaksi->timezone();
    $this->created_by = $this->session->userdata('id');
  }

  public function index()
  {
    // Ambil data statistik dari model
    $stats = $this->model_step->get_step_statistics();

    // Ambil data untuk chart (peringkat steps per bulan)
    $chart_data = $this->model_step->get_step_ranking_by_month();

    // Ambil data top 3 username dengan step terbanyak
    $top_users = $this->model_step->get_top3_by_month();

    // ambil data master karyawan
    $data_karyawan = $this->model_step->get_karyawan_by_username($this->session->userdata('username'));
    if ($data_karyawan->num_rows() > 0) {
      $nama_lengkap = $data_karyawan->row()->nama_lengkap;
      $departement = $data_karyawan->row()->departement;
      $divisi = $data_karyawan->row()->divisi;
    }

    $data = [
      'title' => 'Dashboard Step Counter',
      'url' => 'step/form_save',
      'get_data' => $this->model_step->get_step_employee(),
      'username' => $this->session->userdata('username'),
      'nama_lengkap' => isset($nama_lengkap) ? $nama_lengkap : '',
      'departement' => isset($departement) ? $departement : '',
      'divisi' => isset($divisi) ? $divisi : '',
      'total_steps' => isset($stats->total_steps) ? $stats->total_steps : 0,
      'avg_steps' => isset($stats->avg_steps) ? round($stats->avg_steps) : 0,
      'max_steps' => isset($stats->max_steps) ? $stats->max_steps : 0,
      'total_months' => isset($stats->total_months) ? $stats->total_months : 0,
      'chart_labels' => $chart_data['labels'],
      'chart_values' => $chart_data['values'],
      'top_users' => $top_users,
    ];

    $this->render('step/form', $data);
  }

  public function form_save()
  {
    // $weight = $this->input->post('weight');
    // $height = $this->input->post('height');
    $month = $this->input->post('month');
    $step = $this->input->post('step');
    $capture = $this->upload_config();

    $data = [
      // 'weight' => $weight,
      // 'height' => $height,
      'month' => $month,
      'steps' => $step,
      'capture' => $capture,
      'created_at' => $this->created_at,
      'created_by' => $this->created_by,
      'userid' => $this->created_by
    ];

    $this->model_step->insert_step($data);
    redirect('step/', 'refresh');
  }

  public function upload_config()
  {
    $upload_path = FCPATH . 'assets/uploads/step/';

    if (!is_dir($upload_path)) {
      mkdir($upload_path, 0777, true);
    }

    // Cek apakah ada file yang diupload
    if (!isset($_FILES['capture']) || $_FILES['capture']['error'] == UPLOAD_ERR_NO_FILE) {
      return false;
    }

    // Lanjutkan dengan config upload
    $this->load->library('upload');
    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = '*';
    $config['max_size'] = 2048000;
    $config['overwrite'] = false;
    $config['encrypt_name'] = true;

    $this->upload->initialize($config);

    if ($this->upload->do_upload('capture')) {
      $upload_data = $this->upload->data();
      return $upload_data['file_name'];
    } else {
      return false;
    }
  }

  public function get_top3_by_month_ajax($month = null)
  {
    $top_users = $this->model_step->get_top3_by_month($month);

    $html = '';
    $rank = 1;
    foreach ($top_users->result() as $user) {
      // Buat URL gambar
      $image_url = base_url('assets/uploads/step/' . $user->capture);
      
      $html .= '
        <div class="col-md-4 mb-3">
          <div class="d-flex align-items-center p-3 bg-light rounded">
            <div class="me-3">
              ' . ($rank == 1 ? '<span class="fs-1">🥇</span>' : ($rank == 2 ? '<span class="fs-1">🥈</span>' : '<span class="fs-1">🥉</span>')) . '
            </div>
            <div class="d-flex justify-content-between w-100 align-items-center">
              <div>
                <h6 class="mb-0 fw-bold">' . $user->username . '</h6>
                <small class="text-muted">' . number_format($user->total_steps) . ' steps</small>
              </div>
              <div>
                <a href="' . $image_url . '" target="_blank" style="font-size: 12px; text-decoration: none; color: var(--bs-light-text-emphasis); font-weight: bold; border: 2px solid; border-radius: 25px; padding: 5px 10px;">view image</a>
              </div>
            </div>
          </div>
        </div>';
      $rank++;
    }

    echo $html;
  }

}