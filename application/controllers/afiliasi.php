<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Afiliasi extends MY_Controller
{    
  public function __construct()
  {
    parent::__construct();
    $this->data['page_title'] = 'Afiliasi';

    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login_sistem/','refresh');
    }
    set_time_limit(0);

    $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    $this->load->helper(array('url', 'csv'));
    $this->load->model(array('model_outlet_transaksi','model_afiliasi'));
    $this->created_at = $this->model_outlet_transaksi->timezone();
    $this->created_by = $this->session->userdata('id');
    $this->username = $this->session->userdata('username');
    // echo "a";
    // die;
  }

  public function index()
  {    
    $get_master_karyawan = $this->model_afiliasi->get_master_karyawan_by_nama($this->username);
    if($get_master_karyawan->num_rows() > 0)
    {
      $id_jabatan = $get_master_karyawan->row()->id_jabatan;
    }else{
      $this->session->set_flashdata('error', 'data anda tidak ditemukan');
      redirect('management_office/dashboard');
      die;
    }

    // echo "id_jabatan : ".$id_jabatan;

    $month = $this->input->get('month');
    if(!$month || $month == date('Y-m'))
    {
      $month = date('Y-m');
      $day_now = date('d'); 
      $today = date('Y-m-d');
    }else{
      $day_now = date('t', strtotime($month . '-01'));
      $today = date('Y-m-d', strtotime($month . '-01'));
    }

    $total_hari = $this->model_afiliasi->get_total_hari($month);
    // echo "total_hari : ".$total_hari;
    $current_month = date('Y-m');
    // echo "current_month : ".$current_month;

    $get_current_week = $this->model_afiliasi->get_current_week();
    // echo "get_current_week : ".$get_current_week;

    // Hitung 3 bulan terakhir untuk ditampilkan di header
    // $month1 = date('M Y', strtotime('-2 month', strtotime($current_month . '-01')));
    // $month2 = date('M Y', strtotime('-1 month', strtotime($current_month . '-01')));
    // $month3 = date('M Y', strtotime($month . '-01'));

    $data = [
        'title'         => 'Monitoring Activity Afiliasi',
        // 'title_2'       => 'Summary Activity',
        'get_activity'  => $this->model_afiliasi->get_activity_by_pelaksana_jabatan_month($this->created_by, $id_jabatan, $month),
        'month'         => $month,
        'total_hari'    => $total_hari,
        'get_report'    => $this->model_afiliasi->get_report_activity_by_month($month),
        // 'month1_label'  => $month1,
        // 'month2_label'  => $month2,
        // 'month3_label'  => $month3,
        'today'         => $today,
        'current_week'  => $get_current_week,
        'harian'        => $this->model_afiliasi->get_activity_harian_is_null($this->created_by, $month, $day_now),        
        // 'mingguan'      => $this->model_afiliasi->get_activity_mingguan_is_null($this->created_by, $month, $get_current_week),       
        'bulanan'      => $this->model_afiliasi->get_activity_bulanan_is_null($this->created_by, $month),        
        'not_bulanan_harian'=> $this->model_afiliasi->get_activity_not_bulanan_harian($this->created_by, $month),       
    ];
    
    $this->render('afiliasi/form_activity', $data);
  }

  public function save_activity()
  {
      $activities = $this->input->post('activity');
      $month      = $this->input->post('month');

      $id_karyawan = $this->model_afiliasi->get_master_karyawan_by_nama($this->username)->row()->id;
      // echo "id_karyawan : " . $id_karyawan . "<br>";die;

      // Ambil semua data existing bulan ini (termasuk yang sudah di-soft delete)
      $existing_data = $this->model_afiliasi->get_activity_by_userid_month($this->created_by, $month)->result();

      // Mapping data existing supaya tidak query berulang-ulang
      $existing_map = [];
      foreach ($existing_data as $row) {
          $existing_map[$row->id_activity][$row->day_number] = $row;
      }
      // echo "<pre>"; print_r($existing_map); echo "</pre>";die;

      $checked_map = [];

      if (!empty($activities)) {
          foreach ($activities as $id_activity => $days) {
              foreach ($days as $day_number => $value) {

                  if ($value == '1') {

                      $checked_map[$id_activity][$day_number] = true;

                      // Jika sudah ada di database
                      if (isset($existing_map[$id_activity][$day_number])) {

                          $row = $existing_map[$id_activity][$day_number];

                          // Jika pernah di-soft delete → restore
                          if ($row->deleted_at != NULL) {
                              $this->model_afiliasi->restore_activity($this->created_by,$id_activity,$month,$day_number);
                          }
                      } else {
                          // Jika belum ada sama sekali → INSERT
                          // $date = $month . '-' . str_pad($day_number, 2, '0', STR_PAD_LEFT);
                          // $week = date('W', strtotime($date));

                          // get week from date
                          $date = $month . '-' . $day_number;
                          if (strtotime($date) === false) {
                              echo "Format tanggal tidak valid";
                              // insert data gagal
                              return false;
                          } else {
                              $week = date('W', strtotime($date));
                              // echo "Week: " . $week;
                          }

                          $data = [
                              'id_karyawan'   => $id_karyawan,
                              'id_web'        => $this->created_by,
                              'id_activity'   => $id_activity,
                              'day_number'    => $day_number,
                              'week_number'   => $week,
                              'month'         => $month,
                              'created_at'    => $this->created_at,
                              'created_by'    => $this->created_by,
                              'updated_at'    => $this->created_at,
                              'updated_by'    => $this->created_by
                          ];

                          $this->model_afiliasi->insert_to_table('site.afiliasi_activity', $data);
                      }
                  }
              }
          }
      }

      // =================================
      // SOFT DELETE YANG DI-UNCHECK
      // =================================
      foreach ($existing_data as $row) {

          $id_activity = $row->id_activity;
          $day_number  = $row->day_number;

          // Jika sebelumnya ada tapi sekarang tidak dicentang
          if (!isset($checked_map[$id_activity][$day_number])) {

              // Hanya soft delete jika belum terhapus
              if ($row->deleted_at == NULL) {
                  $this->model_afiliasi->delete_activity($this->created_by, $id_activity,$month,$day_number);
              }
          }
      }
      
      $this->session->set_flashdata('pesan_success', 'Data berhasil diupdate');
      redirect('afiliasi?month=' . $month);
  }

  public function test_calendar()
  {
      $this->load->view('afiliasi/test_calendar');
  }

  public function monthly_planning()
  {
      $get_master_karyawan = $this->model_afiliasi->get_master_karyawan_by_nama($this->username);
      if($get_master_karyawan->num_rows() > 0)
      {
          $id_karyawan = $get_master_karyawan->row()->id;
          $id_jabatan = $get_master_karyawan->row()->id_jabatan;
          $nama_jabatan = $get_master_karyawan->row()->nama_jabatan;
          $nama_divisi = $get_master_karyawan->row()->nama_divisi;
          $nama = $get_master_karyawan->row()->nama;
      } else {
          $this->session->set_flashdata('error', 'anda tidak diijinkan mengakses menu ini. Silahkan hubungi IT');
          redirect('management_office/dashboard');
          die;
      }

      $selected_date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');
      $selected_month = date('m', strtotime($selected_date));
      $selected_year = date('Y', strtotime($selected_date));

      // Parse tanggal untuk mendapatkan nama bulan dan tahun
      $date_obj = new DateTime($selected_date);
      $current_month_name = $date_obj->format('F Y');

      $plan = $this->model_afiliasi->get_activity_plan_by_date($selected_date); // untuk mengetahui plan apa saja yang ada di tanggal tersebut
      
      // Ambil ALL plan untuk seluruh bulan menggunakan function yang sudah ada
      $all_plans_query = $this->model_afiliasi->get_activity_plan_by_month($selected_year, $selected_month);
      
      // Convert result object ke array
      $all_plans = [];
      if ($all_plans_query->num_rows() > 0) {
          $all_plans = $all_plans_query->result_array();
      }
      
      // Ambil count plan per tanggal
      $count_plan = $this->model_afiliasi->get_activity_plan_group_by_month($selected_month);

      // Format ulang count_plan menjadi array asosiatif yang mudah diakses
      $formatted_count_plan = [];
      foreach ($count_plan as $cp) {
          $formatted_count_plan[] = [
              'date' => $cp['date'],
              'count' => $cp['count']
          ];
      }

      $message = $this->session->flashdata('message');

      $reminder_bulanan = $this->model_afiliasi->get_master_activity_not_in_activity_plan_bulanan($id_jabatan, $id_karyawan, $selected_month);
      $reminder_harian = $this->model_afiliasi->get_master_activity_not_in_activity_plan_harian($id_jabatan, $id_karyawan, $selected_date);
      $reminder_not_harian_bulanan = $this->model_afiliasi->get_master_activity_not_in_activity_plan_not_harian_bulanan($id_jabatan, $id_karyawan, $selected_month);

      $data = [
          'title' => 'Monthly Planning',
          'nama_jabatan' => $nama_jabatan,
          'nama_divisi' => $nama_divisi,
          'nama' => $nama,
          'url' => 'afiliasi/monthly_planning_save',
          'url_import' => 'afiliasi/monthly_planning_import',
          'get_activity' => $this->model_afiliasi->get_activity_by_pelaksana_jabatan($id_jabatan),
          'selected_date' => $selected_date,
          'current_month_name' => $current_month_name,
          'reminder_bulanan' => $reminder_bulanan,
          'reminder_harian' => $reminder_harian,
          'reminder_not_harian_bulanan' => $reminder_not_harian_bulanan,
          'plan' => $plan, // Plan untuk tanggal yang dipilih (result array)
          'all_plans' => $all_plans, // SEMUA plan untuk bulan ini (sudah dalam bentuk array)
          'form_data' => [],
          'message' => $message,
          'count_plan' => $formatted_count_plan,
      ];

      $this->render_multiple(
          array(
              'afiliasi/style',
              'afiliasi/monthly_planning',
              'afiliasi/alert',
              'afiliasi/calendar',
              'afiliasi/modal_activity',
              'afiliasi/script',
          ),
          $data
      );
  }

  

  public function get_activities_by_date()
  {
      $this->output->set_content_type('application/json');
      
      $date = $this->input->get('date');
      if (!$date) {
          echo json_encode(['success' => false, 'error' => 'Tanggal tidak ditemukan']);
          return;
      }
      
      $get_master_karyawan = $this->model_afiliasi->get_master_karyawan_by_nama($this->username);
      if($get_master_karyawan->num_rows() > 0) {
          $id_karyawan = $get_master_karyawan->row()->id;
      } else {
          echo json_encode(['success' => false, 'error' => 'Data karyawan tidak ditemukan']);
          return;
      }
      
      $activities = $this->model_afiliasi->get_activity_plan_by_date_for_calendar($date, $id_karyawan);
      echo json_encode(['success' => true, 'data' => $activities, 'date' => $date]);
  }

  public function monthly_planning_save()
  {
      // Cek apakah ini AJAX request atau bukan
      $is_ajax = $this->input->is_ajax_request();
      
      $date = $this->input->post('selected_date');
      $keterangan = $this->input->post('keterangan');
      $activity_id = $this->input->post('activity_id');
      
      // Validasi input
      if(empty($date) || empty($activity_id)) {
          $message = 'Tanggal dan aktivitas harus diisi';
          if($is_ajax) {
              echo json_encode(['success' => false, 'message' => $message]);
              return;
          } else {
              $this->session->set_flashdata('pesan', $message);
              redirect('afiliasi/monthly_planning/?date=' . $date);
              return;
          }
      }
      
      // get_master_karyawan_by_nama
      $get_master_karyawan = $this->model_afiliasi->get_master_karyawan_by_nama($this->username);
      if($get_master_karyawan->num_rows() > 0) {
          $id_karyawan = $get_master_karyawan->row()->id;
      } else {
          $message = 'Data karyawan anda tidak ditemukan';
          if($is_ajax) {
              echo json_encode(['success' => false, 'message' => $message]);
              return;
          } else {
              $this->session->set_flashdata('pesan', $message);
              redirect('afiliasi/monthly_planning/?date=' . $date);
              return;
          }
      }
      
      // Ambil nama activity dari database
      $activity = $this->model_afiliasi->get_activity_by_id($activity_id);
      
      if(!$activity) {
          $message = 'Aktivitas tidak ditemukan';
          if($is_ajax) {
              echo json_encode(['success' => false, 'message' => $message]);
              return;
          } else {
              $this->session->set_flashdata('pesan', $message);
              redirect('afiliasi/monthly_planning/?date=' . $date);
              return;
          }
      }
      
      $nama_activity = $activity->nama_activity;
      
      // Cek apakah aktivitas sudah ada di tanggal tersebut untuk karyawan ini
      $cek_duplicate = $this->model_afiliasi->check_duplicate_activity($id_karyawan, $activity_id, $date);
      if($cek_duplicate) {
          $message = 'Aktivitas sudah direncanakan untuk tanggal ini';
          if($is_ajax) {
              echo json_encode(['success' => false, 'message' => $message]);
              return;
          } else {
              $this->session->set_flashdata('pesan', $message);
              redirect('afiliasi/monthly_planning/?date=' . $date);
              return;
          }
      }
      
      $data = [
          'id_karyawan' => $id_karyawan,
          'id_activity' => $activity_id,
          'title'       => $nama_activity,
          'date'        => $date,
          'keterangan'  => $keterangan,
          'created_by'  => $this->created_by,
          'created_at'  => $this->created_at
      ];
      
      $insert = $this->model_afiliasi->insert_to_table('site.afiliasi_activity_plan', $data);
      
      if($insert) {
          $message = 'Data berhasil disimpan';
          if($is_ajax) {
              echo json_encode(['success' => true, 'message' => $message]);
              return;
          } else {
              $this->session->set_flashdata('pesan_success', $message);
              redirect('afiliasi/monthly_planning/?date=' . $date);
              return;
          }
      } else {
          $message = 'Gagal menyimpan data';
          if($is_ajax) {
              echo json_encode(['success' => false, 'message' => $message]);
              return;
          } else {
              $this->session->set_flashdata('pesan', $message);
              redirect('afiliasi/monthly_planning/?date=' . $date);
              return;
          }
      }
  }

  public function export_monthly_planning($date)
  {
    // echo "date : " . $date; //2026-02-11
    $month = date('m', strtotime($date));
    // echo "month : " . $month; //02
    $year = date('Y', strtotime($date));
    // echo "year : " . $year; //2026

    $query = $this->model_afiliasi->get_activity_plan_by_month($year, $month);

    $this->excel_generator->set_query($query);
    $this->excel_generator->set_header(array
    (
      'nama', 'nama_divisi', 'nama_jabatan', 'date', 'title', 'keterangan', 'created_at'
    ));
    $this->excel_generator->set_column(array
    ( 
      'nama', 'nama_divisi', 'nama_jabatan', 'date', 'title', 'keterangan', 'created_at'
    ));
    $this->excel_generator->set_width(array(10,10,10,10,10,10,10)); 
    $this->excel_generator->exportTo2007('export monthly planning : ' . $year . '-' . $month);

  }

  public function delete_monthly_plan()
  {
      // Cek apakah ini AJAX request
      $is_ajax = $this->input->is_ajax_request();
      
      $activity_id = $this->input->post('activity_id');
      $selected_date = $this->input->post('selected_date');
      
      // Validasi input
      if(empty($activity_id) || empty($selected_date)) {
          $message = 'Data tidak lengkap';
          if($is_ajax) {
              echo json_encode(['success' => false, 'message' => $message]);
              return;
          } else {
              $this->session->set_flashdata('error', $message);
              redirect('afiliasi/monthly_planning/?date=' . $selected_date);
              return;
          }
      }
      
      // Cek apakah activity plan ada
      $check = $this->model_afiliasi->get_activity_plan_by_id($activity_id);
      if($check->num_rows() > 0) {
          $activity_data = $check->row();
          $created_by = $activity_data->created_by;
          
          // Cek hak akses
          if($created_by != $this->created_by) {
              $message = 'Delete data gagal. Anda tidak memiliki hak akses untuk menghapus data ini.';
              if($is_ajax) {
                  echo json_encode(['success' => false, 'message' => $message]);
                  return;
              } else {
                  $this->session->set_flashdata('error', $message);
                  redirect('afiliasi/monthly_planning/?date=' . $selected_date);
                  return;
              }
          } else {
              $data = [
                  'deleted_at' => $this->created_at,
                  'deleted_by' => $this->created_by,
                  'updated_at' => $this->created_at,
                  'updated_by' => $this->created_by
              ];
              
              $update = $this->model_afiliasi->update_to_table('site.afiliasi_activity_plan', $data, $activity_id);
              
              if($update) {
                  $message = 'Data berhasil dihapus';
                  if($is_ajax) {
                      echo json_encode(['success' => true, 'message' => $message]);
                      return;
                  } else {
                      $this->session->set_flashdata('pesan_success', $message);
                      redirect('afiliasi/monthly_planning/?date=' . $selected_date);
                      return;
                  }
              } else {
                  $message = 'Delete data gagal. Data tidak ditemukan.';
                  if($is_ajax) {
                      echo json_encode(['success' => false, 'message' => $message]);
                      return;
                  } else {
                      $this->session->set_flashdata('error', $message);
                      redirect('afiliasi/monthly_planning/?date=' . $selected_date);
                      return;
                  }
              }
          }
      } else {
          $message = 'Delete data gagal. Data tidak ditemukan.';
          if($is_ajax) {
              echo json_encode(['success' => false, 'message' => $message]);
              return;
          } else {
              $this->session->set_flashdata('error', $message);
              redirect('afiliasi/monthly_planning/?date=' . $selected_date);
              return;
          }
      }
  }

  public function export_template_import()
  {
    $query = "select '' as id_activity, '' as keterangan, '' as tanggal";
    $result = $this->db->query($query);

    $this->excel_generator->set_query($result);
    $this->excel_generator->set_header(array
    (
      'id_activity', 'keterangan', 'tanggal'
    ));
    $this->excel_generator->set_column(array
    ( 
      'id_activity', 'keterangan', 'tanggal'
    ));
    $this->excel_generator->set_width(array(10,10,10)); 
    $this->excel_generator->exportTo2007('export template import');

  }

  public function export_master_activity()
  {
    $query = $this->model_afiliasi->get_master_activity();

    $this->excel_generator->set_query($query);
    $this->excel_generator->set_header(array
    (
      'nomor activity', 'nama_activity', 'pelaksana', 'alat_kerja', 'frekuensi'
    ));
    $this->excel_generator->set_column(array
    ( 
      'id', 'nama_activity', 'pelaksana', 'alat_kerja', 'frekuensi'
    ));
    $this->excel_generator->set_width(array(5,30,10,10,10)); 
    $this->excel_generator->exportTo2007('export master activity');

  }

  public function monthly_planning_import()
  {
      $date = $this->input->post('date');
      $signature = 'afiliasi-' . md5($this->created_by . $this->model_outlet_transaksi->timezone());
      // 1️⃣ Upload file
      $this->attachment_config();

      if (!$this->upload->do_upload('file')) {
          $this->session->set_flashdata('pesan', $this->upload->display_errors());
          redirect('afiliasi/monthly_planning/?date=' . $date);
      }

      $upload_data = $this->upload->data();
      $file_path   = $upload_data['full_path'];

      // 2️⃣ Load Excel
      $this->load->library('excel');
      $object = PHPExcel_IOFactory::load($file_path);

      if ($object->getSheetCount() > 1) {
          $this->session->set_flashdata('pesan', 'File harus 1 sheet saja');
          redirect('afiliasi/monthly_planning/?date=' . $date);
      }

      $worksheet     = $object->getActiveSheet();
      $highestRow    = $worksheet->getHighestRow();
      $highestColumn = $worksheet->getHighestColumn();

      if ($highestColumn != 'C') {
          $this->session->set_flashdata('pesan', 'Jumlah kolom tidak sesuai template');
          redirect('afiliasi/monthly_planning/?date=' . $date);
      }

      if ($highestRow <= 1) {
          $this->session->set_flashdata('pesan', 'Data Excel kosong');
          redirect('afiliasi/monthly_planning/?date=' . $date);
      }

      if ($highestRow > 500) {
          $this->session->set_flashdata('pesan', 'Maksimal 500 baris');
          redirect('afiliasi/monthly_planning/?date=' . $date);
      }

      // 3️⃣ Ambil id_jabatan
      $get_master_karyawan = $this->model_afiliasi->get_master_karyawan_by_nama($this->username);
      $id_jabatan = $get_master_karyawan->row()->id_jabatan;
      $id_karyawan = $get_master_karyawan->row()->id;

      // 4️⃣ Kumpulkan semua id_activity dari excel
      $activity_list = [];

      for ($row = 2; $row <= $highestRow; $row++) {
          $id_activity = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
          if (!empty($id_activity)) {
              $activity_list[] = $id_activity;
          }
      }

      $activity_list = array_unique($activity_list);

      // echo "activity_list : ";
      // print_r($activity_list);
      // echo "<br>";
      // die;
      // 5️⃣ Ambil semua activity valid sekali query
      $activity_valid = $this->model_afiliasi->get_activity_by_ids_and_jabatan($activity_list, $id_jabatan);

      $activity_map = [];
      foreach ($activity_valid as $a) {
          // $activity_map[$a['id']] = 1;
          $activity_map[$a['id']] = $a['nama_activity'];
      }

      // 6️⃣ Siapkan data insert batch
      $data_insert = [];

      for ($row = 2; $row <= $highestRow; $row++) {

          $id_activity = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
          $keterangan  = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
          $cellValue   = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

          if (empty($id_activity) && empty($keterangan) && empty($cellValue)) {
              continue; // skip baris kosong
          }

          // Convert tanggal excel
          // if (is_numeric($cellValue)) {
          //     $unixTimestamp = ($cellValue - 25569) * 86400;
          //     $tanggal = date('Y-m-d', $unixTimestamp);
          // } else {
          //     $tanggal = date('Y-m-d', strtotime($cellValue));
          // }

          $is_valid_tanggal = 1; // Nilai default valid

          if (is_numeric($cellValue)) {
              // echo "numeric: " . $cellValue . "<br>";die;
              $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
              $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
          } else {
              // echo "string: " . $cellValue . "<br>";die;
              $tanggal = $cellValue;

              $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d'];
              $dateObj = false;

              foreach ($formats as $format) {
                  $dateObj = DateTime::createFromFormat($format, $tanggal);
                  if ($dateObj !== false) {
                      break;
                  }
              }
              if ($dateObj !== false) {
                  $tanggal = $dateObj->format('Y-m-d');
              } else {
                  $is_valid_tanggal = 0;
              }
          }

          $is_valid_activity = isset($activity_map[$id_activity]) ? 1 : 0;

          $data_insert[] = [
              'id_karyawan'       => $id_karyawan,
              'id_web'            => $this->created_by,
              'id_activity'       => $id_activity,
              'title'             => $is_valid_activity ? $activity_map[$id_activity] : null,
              'keterangan'        => $keterangan,
              'date'              => $tanggal,
              'created_by'        => $this->created_by,
              'created_at'        => $this->created_at,
              'is_valid_activity' => $is_valid_activity,
              'is_valid_tanggal'  => $is_valid_tanggal,
              'signature'         => $signature
          ];
      }
      // echo "<pre>";
      // echo "data_insert : ";
      // print_r($data_insert);
      // echo "</pre>";
      // die;

      // 7️⃣ Insert batch sekali jalan
      if (!empty($data_insert)) {
          $this->db->insert_batch('site.afiliasi_import_temp', $data_insert);
      }

      $this->session->set_flashdata('pesan_success', 'Import berhasil');
      redirect('afiliasi/preview_monthly_planning_import?signature=' . $signature);
  }
  
  public function attachment_config()
  {
    // upload file attachment
    // create folder based on kategori
    if (!is_dir('./assets/uploads/afiliasi/')) {
        @mkdir('./assets/uploads/afiliasi/', 0777);
    }

    $this->load->library('upload'); // Load librari upload        
    $config['upload_path'] = './assets/uploads/afiliasi/';
    $config['allowed_types'] = '*';    
    $config['max_size']  = '2048000';
    $config['overwrite'] = false;
    $config['encrypt_name'] = false;
    $config['file_name'] = rand(1000, 9999);

    $proses = $this->upload->initialize($config);
    return $proses;
  }

  public function preview_monthly_planning_import()
  {
    $signature = $this->input->get('signature');
    // echo "signature : " . $signature;die;

    if (empty($signature)) {
        $this->session->set_flashdata('error', 'Signature tidak ditemukan');
        redirect('afiliasi/monthly_planning');
    }

    $get_data = $this->model_afiliasi->get_import_temp_by_signature($signature);

    if ($get_data->num_rows() == 0) {
        $this->session->set_flashdata("pesan", "Data kosong. Silahkan import ulang.");
        redirect('afiliasi/monthly_planning');
    }

    $get_summary = $this->model_afiliasi->get_summary_monthly_planning_temp($signature);

    $is_invalid = $this->model_afiliasi->get_invalid_monthly_planning($signature);

    $params_invalid = ($is_invalid->num_rows() > 0) ? 1 : 0;

    $data_by_tanggal = [];
      foreach ($get_data->result() as $row) {
          $data_by_tanggal[$row->date][] = $row;
      }

    $data = [
      'title'            => 'Preview Monthly Planning Import',
      'url'              => 'afiliasi/import_monthly_planning_final/?signature=' . $signature,
      "data_by_tanggal"  => $data_by_tanggal,
      'data_import'      => $get_data,
      'get_summary'      => $get_summary,
      'params_invalid'   => $params_invalid
    ];
    

    $this->render_multiple(
      array(
          'afiliasi/style',
          'afiliasi/preview_monthly_planning_import'
      ),
      $data
    );
  }

  public function import_monthly_planning_final()
  {
      $signature  = $this->input->get('signature');
      // echo "signature : " . $signature;die;

      // 1️⃣ Cek masih ada invalid?
      $check_invalid = $this->model_afiliasi->get_invalid_monthly_planning($signature);

      if ($check_invalid->num_rows() > 0) 
      {
        $this->session->set_flashdata("pesan", "Masih ada activity yang tidak valid. Tidak bisa melanjutkan.");
        redirect('afiliasi/preview_monthly_planning_import?signature='.$signature);
      }

     // 2️⃣ Ambil data temp
      $get_temp = $this->model_afiliasi->get_import_temp_by_signature($signature);

      if ($get_temp->num_rows() == 0) {
          $this->session->set_flashdata("pesan", "Data temp tidak ditemukan.");
          redirect('afiliasi/preview_monthly_planning_import?signature='.$signature);
      }

      // 3️⃣ Bentuk array $data untuk insert
      $data = [];

      foreach ($get_temp->result() as $row) {
          $data[] = [
              'id_karyawan'   => $row->id_karyawan,
              'id_web'        => $this->created_by,
              'id_activity'   => $row->id_activity,
              'title'         => $row->title,
              'keterangan'    => $row->keterangan,
              'date'          => $row->date,
              'created_by'    => $this->created_by,
              'created_at'    => $this->created_at
          ];
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      // die;

      // 4️⃣ Kirim ke model untuk insert + delete
      $insert = $this->model_afiliasi->insert_monthly_planning_batch($data);

      if ($insert) {
          $this->session->set_flashdata("pesan_success","Data berhasil disimpan.");
      } else {
          $this->session->set_flashdata("pesan","Gagal menyimpan data.");
      }

      redirect('afiliasi/monthly_planning');

  }

  // public function import_monthly_planning_final()
  // {
  //     $signature  = $this->input->post('signature');
  //     $created_by = $this->created_by;

  //     // 1️⃣ Cek invalid
  //     $check_invalid = $this->model_afiliasi
  //         ->get_invalid_monthly_planning($signature);

  //     if ($check_invalid->num_rows() > 0) {
  //         $this->session->set_flashdata("pesan",
  //             "Masih ada activity yang tidak valid.");
  //         redirect('afiliasi/preview_import_monthly_planning/'.$signature);
  //     }

  //     // 2️⃣ Ambil data temp
  //     $get_temp = $this->model_afiliasi
  //         ->get_temp_by_signature($signature);

  //     if ($get_temp->num_rows() == 0) {
  //         $this->session->set_flashdata("pesan",
  //             "Data temp tidak ditemukan.");
  //         redirect('afiliasi');
  //     }

  //     // 3️⃣ Bentuk array $data untuk insert
  //     $data = [];

  //     foreach ($get_temp->result() as $row) {
  //         $data[] = [
  //             'id_activity'   => $row->id_activity,
  //             'nama_activity' => $row->nama_activity,
  //             'keterangan'    => $row->keterangan,
  //             'tanggal'       => $row->tanggal,
  //             'created_by'    => $created_by,
  //             'created_at'    => date('Y-m-d H:i:s')
  //         ];
  //     }

  //     // 4️⃣ Kirim ke model untuk insert + delete
  //     $insert = $this->model_afiliasi
  //         ->insert_monthly_planning_batch($data, $signature);

  //     if ($insert) {
  //         $this->session->set_flashdata("pesan_success",
  //             "Data berhasil disimpan.");
  //     } else {
  //         $this->session->set_flashdata("pesan",
  //             "Gagal menyimpan data.");
  //     }

  //     redirect('afiliasi');
  // }

  // public function preview_import_monthly_planning()
  // {
  //     $created_by = $this->created_by;

  //     $get_data = $this->model_afiliasi->get_monthly_planning_temp($created_by);

  //     if ($get_data->num_rows() == 0) {
  //         $this->session->set_flashdata("pesan", "Data kosong. Silahkan import ulang.");
  //         redirect('monthly_planning');
  //     }

  //     $get_summary = $this->model_afiliasi->get_summary_monthly_planning_temp($created_by);

  //     $is_invalid = $this->model_afiliasi->get_invalid_monthly_planning($created_by);

  //     $params_invalid = ($is_invalid->num_rows() > 0) ? 1 : 0;

  //     // 🔹 Group by tanggal
  //     $data_by_tanggal = [];
  //     foreach ($get_data->result() as $row) {
  //         $data_by_tanggal[$row->tanggal][] = $row;
  //     }

  //     $data = [
  //         "title"          => "Preview Monthly Planning",
  //         "url"            => "afiliasi/import_monthly_planning_final",
  //         "data_by_tanggal"=> $data_by_tanggal,
  //         "get_summary"    => $get_summary,
  //         "params_invalid" => $params_invalid
  //     ];

  //     $this->render('afiliasi/preview_import_monthly_planning', $data);
  // }

  public function export_activity($date)
  {
    // echo "date : " . $date; //2026-02-11
    // $month = date('m', strtotime($date));
    // echo "month : " . $month; //02
    // $year = date('Y', strtotime($date));
    // echo "year : " . $year; //2026

    $query = $this->model_afiliasi->get_activity_by_date($this->created_by, $date);

    $this->excel_generator->set_query($query);
    $this->excel_generator->set_header(array
    (
      'nama', 'nama_jabatan', 'nama_activity', 'alat_kerja', 'frekuensi', 'date', 'created_at'
    ));
    $this->excel_generator->set_column(array
    ( 
      'nama', 'nama_jabatan', 'nama_activity', 'alat_kerja', 'frekuensi', 'date', 'created_at'
    ));
    $this->excel_generator->set_width(array(10,10,10,10,10,10,10)); 
    $this->excel_generator->exportTo2007('export activity_' . $date);

  }

    
}
?>
