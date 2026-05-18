<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pareto extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->data['page_title'] = 'Pareto';
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login_sistem/','refresh');
    }
    set_time_limit(0);

    $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    $this->load->helper(array('url', 'csv'));
    $this->load->model(array('model_outlet_transaksi','model_pareto'));
    $this->email_tim = 'suffy@muliaputramandiri.com';
    $this->created_at = $this->model_outlet_transaksi->timezone();
    $this->userid = $this->session->userdata('id');
  }

  public function index()
  {
    // echo "hello";
    // die;

    $tahun = $this->input->get('tahun');
    $site_code = $this->input->get('site_code');
    $supp = $this->input->get('supp');
    $periode = $this->input->get('periode');
    $type = $this->input->get('type');
    $class = $this->input->get('class');
    $supp = $this->input->get('supp');

    // echo "type : ".$type;
    // echo "class : ".$class;

    if(empty($tahun))
    {
      $tahun = date('Y');
      $flag_export = false;
    }else{
      $flag_export = true;
    }

    // echo "hellos";
    // die;

    $data = [
      'title' => 'Pareto Account Management',
      'url'   => 'pareto/index',
      'get_master_principal' => $this->model_pareto->get_master_principal($this->session->userdata('supp')),
      'get_master_site' => $this->model_pareto->get_master_site(),
      'get_data'  => $this->model_pareto->get_pareto($tahun, $site_code, $supp, $periode, $type, $class),
      'tahun' => $tahun,
      'supp' => $supp,
      'flag_export' => $flag_export,
      'periode' => $periode,
      'type' => $type,
      'class' => $class,
    ];        

    // echo "xxx";
    // die;

    $this->render('pareto/index', $data);
  }

  public function export_raw_data($tahun, $supp)
  {
    // $get_data = $this->model_pareto->get_pareto($tahun,'', $supp);
    // $hsl = $this->db->query($get_data);

    // die;
    // query_to_csv($get_data,FALSE,'Export Data.csv');    

    // Validasi parameter
    if (empty($tahun) || empty($supp)) {
        show_error('Parameter tahun dan supplier tidak boleh kosong');
        return;
    }
    
    // Ambil data dari model
    $get_data = $this->model_pareto->get_pareto($tahun, '', $supp);
    
    // Cek apakah $get_data berisi query string atau object result
    if (is_string($get_data)) {
      // echo "aaA";
      // die;
        // Jika berupa string query, jalankan query
        $query = $this->db->query($get_data);
        $result = $query->result_array();
    } else {
      // echo "bbb";
      // die;
        // Jika sudah berupa result object
        $result = $get_data->result_array();
    }
    
    // Cek apakah ada data
    if (empty($result)) {
        $this->session->set_flashdata('pesan_warning', 'Tidak ada data untuk diekspor');
        redirect('pareto/index?tahun=' . $tahun);
        return;
    }
    
    // Panggil function query_to_csv untuk export
    query_to_csv($get_data, TRUE, 'Export_Data_' . $tahun . '_' . $supp . '.csv');

  }

  public function update_data()
  {
    $periode = ['q1', 'q2', 'q3', 'q4', 'all'];
    $type = ['include_pharma', 'exclude_pharma'];
    $class = ['include_ritel', 'exclude_ritel'];
    $supp = ['001', '005'];
    $tahun = [2025];

    $this->model_pareto->truncate();

    foreach($periode as $p)
    {
      foreach ($type as $t) {
        foreach ($class as $c) {
          foreach ($tahun as $y) {
            foreach ($supp as $s) {
              $this->model_pareto->update_data($y, '', $s, $p, $t, $c);
            }          
          }   
        }             
      }      
    }

    $this->model_pareto->update_branch(false);
    $this->model_pareto->update_class(false);
    $this->model_pareto->update_principal(false);
    $this->model_pareto->update_type(false);

  }

  public function update_data_current()
  {
    $periode = ['q1', 'q2', 'q3', 'q4', 'all'];
    $type = ['include_pharma', 'exclude_pharma'];
    $class = ['include_ritel', 'exclude_ritel'];
    $supp = ['001', '005'];
    $tahun = [2026];

    $this->model_pareto->truncate_current();

    foreach($periode as $p)
    {
      foreach ($type as $t) {
        foreach ($class as $c) {
          foreach ($tahun as $y) {
            foreach ($supp as $s) {
              // echo "p : ".$p;
              // echo "t : ".$t;
              $this->model_pareto->update_data_current($y, '', $s, $p, $t, $c);
            }          
          }   
        }             
      }      
    }

    $this->model_pareto->update_branch(true);
    $this->model_pareto->update_class(true);
    $this->model_pareto->update_principal(true);
    $this->model_pareto->update_type(true);
  }

  public function update_data_comulative()
  {
    $runcate = $this->model_pareto->truncate_comulative();
    $this->model_pareto->update_data_comulative();
  }

  public function rank_mti()
  {
    $get_data = $this->model_pareto->get_rank_mti();
    $data = [
      'title' => 'Rank MTI',
      'url'   => 'pareto/rank_mt',
      'get_data' => $get_data,
      'data_date' => $get_data->row()->created_at
    ];        

    $this->render('pareto/rank_mti', $data);
  }

  public function master_outlet_mti()
  {
    $data = [
      'title' => 'Master Outlet MTI',
      'url'   => 'pareto/master_outlet_mti_save',
      'url_import' => 'pareto/master_outlet_mti_import',
      'get_data' => $this->model_pareto->get_master_outlet_mti(),
    ];        

    $this->render('pareto/master_outlet_mti', $data);
  }

  public function master_outlet_mti_save()
  {
    $site_code = $this->input->post('site_code');
    $outlet = $this->input->post('outlet');
    $nama_outlet = $this->input->post('nama_outlet');  

    $data = [
      'site_code' => $site_code,
      'outlet' => $outlet,
      'nama_outlet' => $nama_outlet,
      'created_at' => $this->created_at,
      'created_by' => $this->userid,
      'is_active' => 1
    ];
    $this->model_pareto->insert('site.pareto_master_outlet_mti', $data);
    $this->session->set_flashdata('pesan_success', 'Data berhasil disimpan.');
    redirect('pareto/master_outlet_mti');
  }

  public function truncate_master_outlet_mti()
  {
    $truncate = $this->model_pareto->truncate_master_outlet_mti();
    if($truncate)
    {
      $this->session->set_flashdata('pesan_success', 'Data berhasil dihapus.');
      redirect('pareto/master_outlet_mti');
    }else{
      $this->session->set_flashdata('pesan', 'Data gagal dihapus.');
      redirect('pareto/master_outlet_mti');
    }
  }  

  public function export_template_master_outlet_mti()
  {
    $query = "
      select '' as site_code, '' as outlet, '' as nama_outlet, '' as sub_group
    ";
    $hasil = $this->db->query($query);   

    $this->excel_generator->set_query($hasil);
    $this->excel_generator->set_header(array
    (
      'site_code', 'outlet', 'nama_outlet', 'sub_group'
    ));
    $this->excel_generator->set_column(array
    ( 
      'site_code', 'outlet', 'nama_outlet', 'sub_group'
    ));
    $this->excel_generator->set_width(array(15,15,15,15)); 
    $this->excel_generator->exportTo2007('Template Master Outlet MTI'); 
  }

  public function master_outlet_mti_import()
  {
    // inisialisasi upload
    $init_upload = $this->attachment_config();    
    
    if ($this->upload->do_upload('file')) 
    {
      $upload_data = $this->upload->data();
      $filename = $upload_data['file_name'];

      $this->load->library('excel');
      $object = PHPExcel_IOFactory::load("assets/uploads/pareto/$filename");

      $jumlahSheet = $object->getSheetCount();
      if ($jumlahSheet > 1) {
          echo "jumlah_sheet : ".$jumlahSheet;
          echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
          redirect('pareto/master_outlet_mti');
      }

      foreach ($object->getWorksheetIterator() as $worksheet) 
      {
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        if ($highestRow > 2000) {
          $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 2000 ROW.");
          redirect('pareto/master_outlet_mti');
        }

        if ($highestRow <= 1) {
          $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
          redirect('pareto/master_outlet_mti');
        }

        for ($row = 2; $row <= $highestRow; $row++) 
        {   
          $site_code    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
          $outlet       = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
          $nama_outlet  = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
          $sub_group    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());

          $data = [
              'site_code' => $site_code,
              'outlet'    => $outlet,
              'nama_outlet' => $nama_outlet,
              'sub_group'   => $sub_group,
              'is_active' => 1,
              'created_at' => $this->created_at,
              'created_by' => $this->userid
          ];
          $this->model_pareto->insert('site.pareto_master_outlet_mti',$data);
        }
      }

      $this->session->set_flashdata('pesan_success', 'Data berhasil disimpan.');
      redirect('pareto/master_outlet_mti');
    }else
    {
      $this->session->set_flashdata('pesan', 'Data gagal disimpan.' . $this->upload->display_errors());
      redirect('pareto/master_outlet_mti');
      return false;
    };
  }

  public function export_master_outlet_mti()
  {
    $data = $this->model_pareto->get_master_outlet_mti();

    $this->excel_generator->set_query($data);
    $this->excel_generator->set_header(array
    (
      'site_code', 'outlet', 'nama_outlet', 'sub_group'
    ));
    $this->excel_generator->set_column(array
    ( 
      'site_code', 'outlet', 'nama_outlet', 'sub_group'
    ));
    $this->excel_generator->set_width(array(15,15,15,15)); 
    $this->excel_generator->exportTo2007('Master Outlet MTI');
  }

  public function attachment_config()
  {
    $path = './assets/uploads/pareto/';

    if (!is_dir($path)) {
        @mkdir($path, 0777);
    }

    $this->load->library('upload'); // Load librari upload        
    $config['upload_path'] = $path;
    $config['allowed_types'] = '*';    
    $config['max_size']  = '2048000';
    $config['overwrite'] = false;
    $config['encrypt_name'] = false;

    $proses = $this->upload->initialize($config);
    return $proses;
  }

  // public function rank_mti_detail($id)
  // {
  //   // echo "id : ".$id."<br>";
  //   $get_pareto_rank_actual  = $this->model_pareto->get_pareto_rank_actual($id);
  //   $id_ref = $get_pareto_rank_actual->row()->id_ref;
  //   $sub_group = $get_pareto_rank_actual->row()->sub_group;

  //   $get_data_rank = $this->model_pareto->get_pareto_rank_by_sub_group($sub_group);

  //   $get_pareto_omzet_outlet_sub_group_actual = $this->model_pareto->get_pareto_omzet_outlet_sub_group_actual($id_ref);
  //   $sub_group = $get_pareto_omzet_outlet_sub_group_actual->row()->sub_group;

  //   $get_pareto_omzet_outlet_sub_group_actual_by_sub_group = $this->model_pareto->get_pareto_omzet_outlet_sub_group_actual_by_sub_group($sub_group);

  //   $data = [
  //     'title' => 'Rank MTI Detail',
  //     'url'   => 'pareto/rank_mt',
  //     'get_data' => $get_pareto_omzet_outlet_sub_group_actual_by_sub_group,
  //     'data_date' => $get_pareto_omzet_outlet_sub_group_actual_by_sub_group->row()->created_at,
  //     'get_data_rank' => $get_data_rank
  //   ];        

  //   $this->render('pareto/rank_mti_detail', $data);

  // }

  // public function rank_mti_detail($id)
  // {
  //   $get_pareto_rank_actual = $this->model_pareto->get_pareto_rank_actual($id);
  //   $id_ref = $get_pareto_rank_actual->row()->id_ref;
  //   $sub_group = $get_pareto_rank_actual->row()->sub_group;

  //   $get_data_rank = $this->model_pareto->get_pareto_rank_by_sub_group($sub_group);

  //   $get_pareto_omzet_outlet_sub_group_actual = $this->model_pareto->get_pareto_omzet_outlet_sub_group_actual($id_ref);
  //   $sub_group = $get_pareto_omzet_outlet_sub_group_actual->row()->sub_group;

  //   $get_pareto_omzet_outlet_sub_group_actual_by_sub_group = $this->model_pareto->get_pareto_omzet_outlet_sub_group_actual_by_sub_group($sub_group);

  //   // Hitung total untuk footer
  //   $total_bruto = 0;
  //   foreach($get_pareto_omzet_outlet_sub_group_actual_by_sub_group->result() as $row) {
  //       $total_bruto += $row->bruto;
  //   }

  //   $data = [
  //       'title' => 'Rank MTI Detail - ' . $sub_group,
  //       'url'   => 'pareto/rank_mt',
  //       'get_data' => $get_pareto_omzet_outlet_sub_group_actual_by_sub_group,
  //       'total_bruto' => $total_bruto,
  //       'sub_group' => $sub_group,
  //       'data_date' => $get_pareto_omzet_outlet_sub_group_actual->row()->created_at ? $get_pareto_omzet_outlet_sub_group_actual->row()->created_at : '',
  //       'get_data_rank' => $get_data_rank
  //   ];        

  //   $this->render('pareto/rank_mti_detail', $data);
  // }


  public function rank_mti_detail($id)
  {
    $get_pareto_rank_actual = $this->model_pareto->get_pareto_rank_actual($id);
    $id_ref = $get_pareto_rank_actual->row()->id_ref;
    $sub_group = $get_pareto_rank_actual->row()->sub_group;

    $get_pareto_omzet_outlet_sub_group_actual = $this->model_pareto->get_pareto_omzet_outlet_sub_group_actual($id_ref);
    
    // Panggil function baru untuk perbandingan
    $get_perbandingan = $this->model_pareto->get_perbandingan_omzet_by_sub_group($sub_group);

    // Hitung total untuk footer
    $total_2025 = 0;
    $total_2026 = 0;
    foreach($get_perbandingan->result() as $row) {
        $total_2025 += $row->bruto_2025;
        $total_2026 += $row->bruto_2026;
    }

    $data = [
        'title' => 'Rank MTI Detail - ' . $sub_group,
        'url'   => 'pareto/rank_mt',
        'get_data' => $get_perbandingan,
        'total_2025' => $total_2025,
        'total_2026' => $total_2026,
        'sub_group' => $sub_group,
        'data_date' => $get_pareto_omzet_outlet_sub_group_actual->row()->created_at
    ];        

    $this->render('pareto/rank_mti_detail', $data);
  }

}
?>
