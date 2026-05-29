<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Step extends MY_Controller
{    
  // Konfigurasi target step ideal
  private $step_ideal_harian = 10000;   // 10.000 steps per hari
  private $step_ideal_bulanan = 300000; // 300.000 steps per bulan (30 hari)
  
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

    // Ambil data top 3 divisi berdasarkan average steps
    $top_divisi = $this->model_step->get_top3_divisi_by_average();

    // ambil data master karyawan
    $data_karyawan = $this->model_step->get_karyawan_by_username($this->session->userdata('username'));
    if ($data_karyawan->num_rows() > 0) {
      $nama_lengkap = $data_karyawan->row()->nama_lengkap;
      $departement = $data_karyawan->row()->departement;
      $divisi = $data_karyawan->row()->divisi;
      $jenis_kelamin = $data_karyawan->row()->jenis_kelamin;
    }

    $data = [
      'title' => 'Step Form',
      'url' => 'step/form_save',
      'get_data' => $this->model_step->get_step_employee(),
      'nama_lengkap' => $nama_lengkap,
      'jenis_kelamin' => $jenis_kelamin,
      'departement' => $departement,
      'divisi' => $divisi,
      'total_steps' => isset($stats->total_steps) ? $stats->total_steps : 0,
      'avg_steps' => isset($stats->avg_steps) ? round($stats->avg_steps) : 0,
      'max_steps' => isset($stats->max_steps) ? $stats->max_steps : 0,
      'total_months' => isset($stats->total_months) ? $stats->total_months : 0,
      'chart_labels' => $chart_data['labels'],
      'chart_values' => $chart_data['values'],
      'top_users' => $top_users,
      'top_divisi' => $top_divisi,
      'step_ideal_harian' => $this->step_ideal_harian,
      'step_ideal_bulanan' => $this->step_ideal_bulanan,
    ];

    $this->render('step/form', $data);
  }

  public function form_save()
  {
    $weight = $this->input->post('weight');
    $height = $this->input->post('height');
    $month = $this->input->post('month');
    $step = $this->input->post('step');
    $capture = $this->upload_config();

    $data = [
      'weight' => $weight,
      'height' => $height,
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
    $target_bulanan = $this->step_ideal_bulanan;

    $html = '';
    $rank = 1;
    foreach ($top_users->result() as $user) {
      $persen = round(($user->total_steps / $target_bulanan) * 100);
      $badge = ($user->total_steps >= $target_bulanan) 
        ? '<span class="badge bg-success">✓ Tercapai</span>' 
        : '<span class="badge bg-warning text-dark">' . $persen . '%</span>';
      
      $html .= '
        <div class="col-md-4 mb-3">
          <div class="d-flex align-items-center p-3 border rounded">
            <div class="me-3">
              ' . ($rank == 1 ? '<span class="fs-1">🥇</span>' : ($rank == 2 ? '<span class="fs-1">🥈</span>' : '<span class="fs-1">🥉</span>')) . '
            </div>
            <div class="d-flex justify-content-between w-100 align-items-center">
              <div>
                <h6 class="mb-0 fw-bold">' . $user->username . '</h6>
                <small class="text-muted">' . number_format($user->total_steps) . ' steps</small>
              </div>
              <div class="text-end">
                ' . $badge . '
                <div><a href="' . base_url('assets/uploads/step/' . $user->capture) . '" target="_blank" class="btn btn-link btn-sm p-0 mt-1" style="font-size: 11px;">view image</a></div>
              </div>
            </div>
          </div>
        </div>';
      $rank++;
    }

    echo $html;
  }

  public function get_top3_divisi_ajax($month = null)
  {
    $top_divisi = $this->model_step->get_top3_divisi_by_average($month);

    if ($top_divisi->num_rows() > 0) {
      $html = '';
      $rank = 1;
      foreach ($top_divisi->result() as $divisi) {
        $avg_steps_formatted = number_format($divisi->avg_steps);
        $total_steps_formatted = number_format($divisi->total_steps);

        $html .= '
          <div class="col-md-4 mb-3">
            <div class="d-flex align-items-center p-3 border rounded">
              <div class="me-3">
                ' . ($rank == 1 ? '<span class="fs-1">🥇</span>' : ($rank == 2 ? '<span class="fs-1">🥈</span>' : '<span class="fs-1">🥉</span>')) . '
              </div>
              <div class="w-100">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="mb-0 fw-bold">' . $divisi->divisi . '</h6>
                    <small class="text-muted">
                      Rata-rata: ' . $avg_steps_formatted . ' steps
                    </small>
                  </div>
                  <div class="text-end">
                    <small class="text-muted d-block">
                      Total: ' . $total_steps_formatted . ' steps
                    </small>
                    <small class="text-muted">
                      ' . $divisi->total_member . ' member
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>';
        $rank++;
      }
    } else {
      $html = '<div class="col-12 text-center text-muted p-4">Belum ada data untuk ditampilkan</div>';
    }

    echo $html;
  }

}