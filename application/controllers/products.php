<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Products extends MY_Controller
{
public function __construct()
{
  parent::__construct();
  $this->data['page_title'] = 'Kenaikan Harga';
  $logged_in= $this->session->userdata('logged_in');
  if(!isset($logged_in) || $logged_in != TRUE)
  {
      redirect('login_sistem/','refresh');
  }
  set_time_limit(0);

  $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
  $this->load->helper(array('url', 'csv'));
  $this->load->model(array('model_outlet_transaksi','model_products'));
  $this->email_tim = 'suffy@muliaputramandiri.com';
  $this->created_at = $this->model_outlet_transaksi->timezone();
  $this->userid = $this->session->userdata('id');

  // create folder
  if (!is_dir('./assets/uploads/kenaikan_harga/')) {
      @mkdir('./assets/uploads/kenaikan_harga/', 0777);
  }

}

public function kenaikan_harga()
{        
  $data = [
    'title' => 'Create Ticket Kenaikan Harga',
    'url_input' => 'products/kenaikan_harga_input',
    'get_principal' => $this->model_products->get_principal(),
    'get_data'  => $this->model_products->get_ticket_kenaikan_harga()
  ];        

  $this->render('product/kenaikan_harga', $data);
}

public function kenaikan_harga_input()
{
  $supp = $this->input->post('supp');
  $keterangan = $this->input->post('keterangan');
  $memo_id = $this->input->post('memo_id');
  $tgl_naik = $this->input->post('tgl_naik');
  $tgl_memo = $this->input->post('tgl_memo');

  // echo "supp : ".$supp;
  // echo "keterangan : ".$keterangan;
  // echo "memo_id : ".$memo_id;
  // echo "tgl_naik : ".$tgl_naik;
  // echo "tgl_memo : ".$tgl_memo;
  // die;

  $this->load->library('upload');
  
  //konfigurasi upload
  $config['upload_path'] = './assets/uploads/kenaikan_harga/';
  $config['allowed_types'] = '*';
  $config['max_size'] = '*';
  $config['encrypt_name'] = FALSE;

  if (isset($_FILES['file'])) {
      if ($_FILES['file']['error'] == 0) {
          $this->upload->initialize($config);
          if ($this->upload->do_upload('file')) {
              $data = $this->upload->data();
              $filename = $data['file_name'];
          } else {
              echo $this->upload->display_errors();
              die;
          }
      }
  }

  if (isset($_FILES['attachments'])) {
      $count = count($_FILES['attachments']['name']);
      
      for ($i = 0; $i < $count; $i++) {
          if ($_FILES['attachments']['error'][$i] == 0) {
              $_FILES['attachment']['name']     = $_FILES['attachments']['name'][$i];
              $_FILES['attachment']['type']     = $_FILES['attachments']['type'][$i];
              $_FILES['attachment']['tmp_name'] = $_FILES['attachments']['tmp_name'][$i];
              $_FILES['attachment']['error']    = $_FILES['attachments']['error'][$i];
              $_FILES['attachment']['size']     = $_FILES['attachments']['size'][$i];

              $this->upload->initialize($config);
              if ($this->upload->do_upload('attachment')) {
                  $data = $this->upload->data();
                  $filename_attachments[] = $data['file_name'];
              }
          }
      } 
  } else {
      $filename_attachments = null;
  }

  $nomor_ticket = $this->model_products->generate_nomor_ticket($supp, $this->model_outlet_transaksi->timezone());

  $data = [
      'nomor_ticket'  => $nomor_ticket,
      'supp'          => $supp,
      'keterangan'    => $keterangan,
      'file'          => $filename,
      'memo_id'       => $memo_id,
      'tgl_naik'      => $tgl_naik,
      'tgl_memo'      => $tgl_memo,
      'attachments'   => ($filename_attachments != null) ? json_encode($filename_attachments) : null,
      'status'        => 1,
      'nama_status'   => 'pending administrator',
      'on_duty'       => 12,
      'signature'     => 'tiket-harga' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd'),
      'created_by'    => $this->session->userdata('id'),
      'created_at'    => $this->model_outlet_transaksi->timezone()
  ];

  $insert = $this->model_products->insert_ticket_kenaikan_harga($data);

  if ($insert) {
      $this->session->set_flashdata('pesan_success', 'Data berhasil disimpan');
      redirect('products/kenaikan_harga');
  }else{
      $this->session->set_flashdata('pesan', 'Data gagal disimpan');
      redirect('products/kenaikan_harga');
  }
  // print_r($data);

}

public function kenaikan_harga_header($signature)
{
  // menentukan cluster
  // echo "signature : ".$signature;
  $cek = $this->model_products->get_ticket_kenaikan_harga($signature);
  if ($cek->num_rows() == 0) {
      $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
      redirect('products/kenaikan_harga');
  }
  $id_ticket = $cek->row()->id;
  $get_data = $this->model_products->get_kenaikan_harga_header_by_id_ticket($id_ticket);
  $all_site_codes = array();
  if ($get_data->num_rows() > 0) {
      foreach ($get_data->result() as $key) {
          $site_code_json  = $key->site_code;
          // Validasi dan decode JSON
          if (!empty($site_code_json)) {
              $decoded_sites = json_decode($site_code_json, true);
              
              if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_sites)) {
                  $all_site_codes = array_merge($all_site_codes, $decoded_sites);
              }
          }
          // echo "Raw JSON: " . $site_code_json . "<br>";
      }
  }

  // Hapus duplikat dan re-index array
  $unique_site_codes = array_values(array_unique($all_site_codes));

  $data = [
      'title' => 'Create Cluster DP',
      'url' => 'products/kenaikan_harga_header_proses',
      'get_site_code' => $this->model_products->get_site_code($unique_site_codes),
      'id_ticket' => $id_ticket,            
      'get_data_header' => $get_data,
      'signature_ticket' => $signature,
      'data_ticket' => $this->model_products->get_ticket_kenaikan_harga_by_id($id_ticket)
  ];        
  
  $this->render('product/kenaikan_harga_header', $data);
}

  public function kenaikan_harga_header_proses()
  {
    $id_ticket = $this->input->post('id_ticket');        
    $label = $this->input->post('label');        
    // $tanggal_aktif = $this->input->post('tanggal_aktif');        
    $options = $this->input->post('options');
    $signature_ticket = $this->input->post('signature_ticket');
    $signature = 'header' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

      $data = [
          'id_ticket' => $id_ticket,
          'label' => $label,
          // 'tanggal_aktif' => $tanggal_aktif,
          'site_code' => json_encode($options),
          'signature' => $signature,
          'created_at' => $this->model_outlet_transaksi->timezone(),
          'created_by' => $this->session->userdata('id')
      ];

      $input = $this->model_products->input_kenaikan_harga_header($data);
      if ($input) {
          $this->session->set_flashdata('pesan_success', 'Data berhasil disimpan');
          redirect('products/kenaikan_harga_header/' . $signature_ticket);
      }else{
          $this->session->set_flashdata('pesan', 'Data gagal disimpan');
          redirect('products/kenaikan_harga');
      }
  }

  public function kenaikan_harga_header_delete($signature)
  {
    $cek = $this->model_products->get_kenaikan_harga_header($signature);
    if ($cek->num_rows() == 0) {
        $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
        redirect('products/kenaikan_harga');
    }
    $id = $cek->row()->id;
    $signature_ticket = $cek->row()->signature_ticket;

    $data = [
      'deleted_at' => $this->created_at,
      'deleted_by' => $this->userid,
      'updated_at' => $this->created_at,
      'updated_by' => $this->userid
    ];

    $update = $this->model_products->update_kenaikan_harga_header($data, $id);

    if ($update) {
        $this->session->set_flashdata('pesan_success', 'Data berhasil dihapus');
        redirect('products/kenaikan_harga_header/'.$signature_ticket);
    }else{
        $this->session->set_flashdata('pesan', 'Data gagal dihapus');
        redirect('products/kenaikan_harga_header/'.$signature_ticket);
    }
  }

  public function kenaikan_harga_product($signature)
  {
      // echo "signature : " . $signature;
      $cek = $this->model_products->get_kenaikan_harga_header($signature);
      if ($cek->num_rows() == 0) {
          $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
          redirect('products/kenaikan_harga');
      }
      $id_header = $cek->row()->id;
      $id_ticket = $cek->row()->id_ticket;
      // echo "id_header : " . $id_header;
      // echo "id_ticket : " . $id_ticket;

      $get_data = $this->model_products->get_ticket_kenaikan_harga_by_id($id_ticket);
      if($get_data->num_rows() == 0){
          $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
          redirect('products/kenaikan_harga');
      }
      $supp = $get_data->row()->supp;
      $signature_ticket = $get_data->row()->signature;

      $get_data_detail = $this->model_products->get_kenaikan_harga_detail_by_id_header($id_header);

      $data = [
        'title' => 'Create Harga Product By Cluster',
        'url' => 'products/kenaikan_harga_product_proses',
        'url_import' => 'products/kenaikan_harga_product_import_proses',
        'get_data_product' => $this->model_products->get_kodeprod_by_supp($supp),
        'signature_ticket' => $signature_ticket,
        'id_header' => $id_header,
        'signature_header' => $signature,
        'get_data_detail' => $get_data_detail,
        'supp' => $supp,
        'data_ticket' => $get_data,
        'data_header' => $cek
      ];        
      
      $this->render('product/kenaikan_harga_product', $data);

  }

    public function kenaikan_harga_product_proses()
    {
        $id_header = $this->input->post('id_header');
        $signature_header = $this->input->post('signature_header');
        $kodeprod = $this->input->post('kodeprod');
        $harga_jual_grosir = $this->input->post('harga_jual_grosir');
        $harga_jual_retail = $this->input->post('harga_jual_retail');
        $harga_jual_motoris_retail = $this->input->post('harga_jual_motoris_retail');
        $harga_jual_mt = $this->input->post('harga_jual_mt');

        $signature = 'product' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        // echo "id_header : " . $id_header;
        // echo "signature_header : " . $signature_header;
        // echo "kodeprod : " . $kodeprod;
        // echo "harga_jual_grosir : " . $harga_jual_grosir;
        // echo "harga_jual_retail : " . $harga_jual_retail;
        // echo "harga_jual_motoris_retail : " . $harga_jual_motoris_retail;
        // echo "harga_jual_mt : " . $harga_jual_mt;

        $data = [
            'id_header' => $id_header,
            'kodeprod' => $kodeprod,
            'harga_jual_grosir' => $harga_jual_grosir,
            'harga_jual_retail' => $harga_jual_retail,
            'harga_jual_motoris_retail' => $harga_jual_motoris_retail,
            'harga_jual_mt' => $harga_jual_mt,
            'created_at' => $this->model_outlet_transaksi->timezone(),
            'created_by' => $this->session->userdata('id'),
            'signature' => $signature
        ];
        $insert = $this->model_products->input_kenaikan_harga_detail($data);

        if ($insert) {
            $this->session->set_flashdata('pesan_success', 'Data berhasil disimpan');
            redirect('products/kenaikan_harga_product/' . $signature_header);
        }else{
            $this->session->set_flashdata('pesan', 'Data gagal disimpan');
            redirect('products/kenaikan_harga_product/' . $signature_header);
        }

    }

    public function template_import_kenaikan_harga($signature_header, $signature_ticket)
    {
        // echo "signature_header : " . $signature_header;
        // echo "signature_ticket : " . $signature_ticket;

        $cek_header = $this->model_products->get_kenaikan_harga_header($signature_header);
        if($cek_header->num_rows() == 0){
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
            redirect('products/kenaikan_harga');
        }

        $cek_ticket = $this->model_products->get_ticket_kenaikan_harga($signature_ticket);
        if($cek_ticket->num_rows() == 0){
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
            redirect('products/kenaikan_harga');
        }

        $query = "
            select  '' as kodeprod, '' as harga_jual_grosir, '' as harga_jual_retail, 
                    '' as harga_jual_motoris_retail, '' as harga_jual_mt
        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'kodeprod', 'harga_jual_grosir', 'harga_jual_retail', 'harga_jual_motoris_retail', 'harga_jual_mt'
        ));
        $this->excel_generator->set_column(array
        (
            'kodeprod', 'harga_jual_grosir', 'harga_jual_retail', 'harga_jual_motoris_retail', 'harga_jual_mt'
        ));
        $this->excel_generator->set_width(array(15,15,15,15,15));
        $this->excel_generator->exportTo2007('Template Import Kenaikan Harga');
    }

    public function kenaikan_harga_product_import_proses()
    {
        $supp = $this->input->post('supp');
        $signature_header = $this->input->post('signature_header');
        $signature_ticket = $this->input->post('signature_ticket');
        // echo "supp : " . $supp;
        // echo "signature_header : " . $signature_header;
        // echo "signature_ticket : " . $signature_ticket;

        $cek_header = $this->model_products->get_kenaikan_harga_header($signature_header);
        if($cek_header->num_rows() == 0){
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
            redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
        }

        $id_header = $cek_header->row()->id;

        $cek_ticket = $this->model_products->get_ticket_kenaikan_harga($signature_ticket);
        if($cek_ticket->num_rows() == 0){
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
            redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
        }

        $supp_database = $cek_ticket->row()->supp;
        if ($supp != $supp_database) {
            $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
            redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
        }

        // die;

        // $file = $this->input->post('file');
        if (!is_dir('./assets/uploads/kenaikan_harga/import')) {
            @mkdir('./assets/uploads/kenaikan_harga/import/', 0777);
        }
        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/kenaikan_harga/import/';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true; 

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file'))
        {
            echo $this->upload->display_errors();
            die;
        }

        if ($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/kenaikan_harga/import/$file_name");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1)
            {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            foreach ($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 100)
                {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 100 ROW.");
                    redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++)
                {
                    $kodeprod  = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $harga_jual_grosir  = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $harga_jual_retail  = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $harga_jual_motoris_retail  = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $harga_jual_mt = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());

                    // validasi kodeprod
                    if(strlen("$kodeprod") == '5')
                    {
                        $params_kodeprod = '0'.$kodeprod;
                    }else
                    {
                        $params_kodeprod = $kodeprod;
                    }

                    $get_kodeprod = $this->model_products->get_kodeprod_by_supp($supp);
                    if (!$get_kodeprod->num_rows() > 0)
                    {
                        $this->session->set_flashdata("pesan", "masukkan kodeproduk yang sesuai dengan standar MPM. Anda memasukkan kodeproduk : $kodeprod");
                        redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
                        die;
                    }

                    $signature = 'product' . rand() . md5($this->created_at) . date('Ymd');

                    $cek_exist = $this->model_products->get_kenaikan_harga_detail_by_id_header_n_kodeprod($id_header, $params_kodeprod);
                    if ($cek_exist->num_rows() > 0) {
                        // lakukan update
                        // echo "lakukan update";
                        $data = [
                            'harga_jual_grosir' => $harga_jual_grosir,
                            'harga_jual_retail' => $harga_jual_retail,
                            'harga_jual_motoris_retail' => $harga_jual_motoris_retail,
                            'harga_jual_mt' => $harga_jual_mt,
                            'updated_at' => $this->model_outlet_transaksi->timezone(),
                            'updated_by' => $this->session->userdata('id'),
                            'deleted_at' => null,
                            'deleted_by' => null
                        ];
                        // print_r($data);
                        $update = $this->model_products->update_kenaikan_harga_detail($data, $id_header, $params_kodeprod);

                    }else{
                        // echo "lakukan input";
                        $data = [
                            'id_header' => $id_header,
                            'kodeprod' => $params_kodeprod,
                            'harga_jual_grosir' => $harga_jual_grosir,
                            'harga_jual_retail' => $harga_jual_retail,
                            'harga_jual_motoris_retail' => $harga_jual_motoris_retail,
                            'harga_jual_mt' => $harga_jual_mt,
                            'created_at' => $this->model_outlet_transaksi->timezone(),
                            'created_by' => $this->session->userdata('id'),
                            'signature' => $signature
                        ];
                        // print_r($data);
                        $input = $this->model_products->input_kenaikan_harga_detail($data);
                        if(!$input){
                            $this->session->set_flashdata("pesan", "Gagal memasukkan data");
                            redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
                        }
                    }                    
                }

                $this->session->set_flashdata("pesan_success", "Import data selesai. Selalu cek ulang data anda");
                redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
            }
        }else{
            $this->session->set_flashdata("pesan", "File yang anda upload bukan file excel");
            redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
        }

    }

    public function kenaikan_harga_detail_delete($signature, $signature_header)
    {
        $cek = $this->model_products->get_kenaikan_harga_detail_by_signature($signature);
        if($cek->num_rows() == 0){
            $this->session->set_flashdata("pesan", "Data tidak ditemukan");
            redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
        }
        $id = $cek->row()->id;
        $kodeprod = $cek->row()->kodeprod;

        $data = [
            'deleted_at' => $this->model_outlet_transaksi->timezone(),
            'deleted_by' => $this->session->userdata('id'),
        ];

        $update = $this->model_products->update_kenaikan_harga_detail_by_id($data, $id);
        if($update){
            $this->session->set_flashdata("pesan_success", "Data berhasil dihapus");
            redirect('products/kenaikan_harga_product/'.$signature_header, 'refresh');
        }
    }

  public function monitoring($signature)
  {
    $get_monitoring = $this->model_products->get_monitoring($signature);
    if($get_monitoring->num_rows() == 0){
        $this->session->set_flashdata("pesan", "Data tidak ditemukan");
        redirect('products/kenaikan_harga', 'refresh');
    }

    $id_ticket = $get_monitoring->row()->id;
    $namasupp = $get_monitoring->row()->namasupp;
    $nomor_ticket = $get_monitoring->row()->nomor_ticket;
    $keterangan = $get_monitoring->row()->keterangan;
    $file = $get_monitoring->row()->file;
    $attachments = $get_monitoring->row()->attachments;
    $nama_status = $get_monitoring->row()->nama_status;
    $status = $get_monitoring->row()->status;
    $on_duty = $get_monitoring->row()->on_duty;
    $on_duty_username = $get_monitoring->row()->on_duty_username;
    $created_at = $get_monitoring->row()->created_at;
    $created_by_username = $get_monitoring->row()->created_by_username;        
    
    $get_header = $this->model_products->get_kenaikan_harga_header_by_id_ticket($id_ticket);
    

    if($get_header->num_rows() > 0){

      //truncate
      $truncate = $this->model_products->truncate_temp_monitoring_get();

      // masukkan ke dalam tabel
      foreach ($get_header->result() as $a) {
        $site_codes = json_decode($a->site_code);
        if(is_array($site_codes)) 
        {
          foreach($site_codes as $site_code) 
          {
            $data_insert = array(
              'id_ticket' => $id_ticket,
              'id_header' => $a->id,
              'site_code_registered' => $site_code,
              'created_at'  => $this->created_at,
              'created_by'  => $this->userid
            );
            $insert = $this->model_products->insert_temp_monitoring_get($data_insert);
          }
        }
      }
    }

    // die;

    $get_hit_api_get = $this->model_products->get_hit_api_get($id_ticket);
    $get_monitoring_feedback = $this->model_products->get_monitoring_feedback_by_id_ticket($id_ticket);        

    $data = [
        'title' => 'Monitoring Kenaikan Harga',
        'namasupp' => $namasupp,
        'nomor_ticket' => $nomor_ticket,
        'keterangan' => $keterangan,
        'file' => $file,
        'attachments' => $attachments,
        'nama_status' => $nama_status,
        'on_duty_username' => $on_duty_username,
        'created_at' => $created_at,
        'created_by_username' => $created_by_username,
        'get_header' => $get_header,
        'url' => 'products/monitoring_proses',
        'id_ticket' => $id_ticket,
        'status' => $status,
        'on_duty' => $on_duty,
        'get_hit_api_get' => $get_hit_api_get,
        'get_monitoring_feedback' => $get_monitoring_feedback,
        'signature_ticket' => $signature,
        'get_not_in' => $this->model_products->get_site_code_not_in_get_api(),
    ];        
    $this->render('product/monitoring', $data);
  }

  public function monitoring_proses()
  {
    $id_ticket = $this->input->post('id_ticket');
    $status = $this->input->post('status');
    $get_data = $this->model_products->get_ticket_kenaikan_harga_by_id($id_ticket);
    if($get_data->num_rows() == 0){
        $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
        redirect('products/kenaikan_harga');
    }
    $created_by = $get_data->row()->created_by;

    $btnKirim = $this->input->post('btnKirim');     

    if($status == 2){
        $status = 2;
        $nama_status = 'PENDING APPROVAL';
        $on_duty = $created_by;
    }elseif($status == 10){
        $status = 10;
        $nama_status = 'APPROVED';
        $on_duty = 12;
    }elseif($status == 99){
        $status = 99;
        $nama_status = 'REJECT';
        $on_duty = 12;
    }elseif($status == 20){
        $status = 20;
        $nama_status = 'OPEN API';
        $on_duty = 12;
    }elseif($status == 90){
        $status = 90;
        $nama_status = 'CLOSE API';
        $on_duty = 12;
    }

    $data = [
        'status'        => $status,
        'nama_status'   => $nama_status,
        'on_duty'       => $on_duty,
        'updated_at'    => $this->model_outlet_transaksi->timezone(),
        'updated_by'    => $this->session->userdata('id')
    ];

    $update = $this->model_products->update_ticket_kenaikan_harga($data, $id_ticket);
    if($update){
        $this->session->set_flashdata('pesan_success', 'Data berhasil disimpan');
        redirect('products/kenaikan_harga', 'refresh');
    }

  }

    public function report_product_nasional()
    {
        $data = [
            'title' => 'Report Product Nasional',
            'url_export' => 'products/report_product_nasional_export',
            'get_data' => $this->model_products->get_report_product_nasional()
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('product/report_product_nasional', $data);
        $this->load->view('kalimantan/footer');
    }

    public function report_product_nasional_export()
    {
        $get_data = $this->model_products->get_report_product_nasional();

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'namasupp','kodeprod','namaprod', 'nama_group', 'nama_sub_group', 'tanggal_aktif','label','harga_jual_grosir','harga_jual_retail','harga_jual_motoris_retail','harga_jual_mt', 'nomor_ticket'
        ));
        $this->excel_generator->set_column(array
        (
            'namasupp','kodeprod','namaprod', 'nama_group', 'nama_sub_group', 'tanggal_aktif','label','harga_jual_grosir','harga_jual_retail','harga_jual_motoris_retail','harga_jual_mt', 'nomor_ticket'
        ));
        $this->excel_generator->set_width(array(15,15,15,15,15,15,15,15,15,15,15,15));
        $this->excel_generator->exportTo2007('Export Product Nasional');
    }

  // public function updating_monitoring_feedback($signature_ticket)
  // {
  //   $get_ticket = $this->model_products->get_ticket_by_signature($signature_ticket);
  //   if($get_ticket->num_rows() == 0){
  //       $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
  //       redirect('products/kenaikan_harga');
  //   }

  //   $id_ticket = $get_ticket->row()->id;
  //   // echo "id_ticket : " . $id_ticket;
  //   // die;

  //   $get_header = $this->model_products->get_kenaikan_harga_header_by_id_ticket($id_ticket);

  //   $all_site_codes = [];
  //   foreach ($get_header->result() as $key => $value) {
  //       $site_codes_array = json_decode($value->site_code, true);
  //       if (is_array($site_codes_array)) {
  //           $all_site_codes = array_merge($all_site_codes, $site_codes_array);
  //       }
  //   }
  //   $site_code = "'" . implode("','", $all_site_codes) . "'";

  //   // echo "<pre>";
  //   // print_r($site_code);
  //   // echo "</pre>";
  //   // die;
        
  //   //delete from kenaikan_harga_monitoring_feedback by id_ticket
  //   $delete = $this->model_products->delete_kenaikan_harga_monitoring_feedback_by_id_ticket($id_ticket);
  //   $delete = $this->model_products->delete_kenaikan_harga_monitoring_detail_feedback_by_id_ticket($id_ticket);

  //   foreach ($get_header->result() as $key) 
  //   {
  //       //count site_code
  //       $count_site_code = json_decode($key->site_code, true);
  //       // echo "<pre>";
  //       // print_r($count_site_code);
  //       // echo "</pre>";
  //       // die;

  //       for ($i=0; $i < count($count_site_code); $i++) 
  //       { 
  //           $get_data_feedback = $this->model_products->get_feedback_by_idticket_idheader_site_code($id_ticket, $key->id, $count_site_code[$i]);

  //           $data = [
  //               'id_ticket' => $id_ticket,
  //               'id_header' => $key->id,
  //               'site_code' => $count_site_code[$i],
  //               'site_code_feedback' => $get_data_feedback->num_rows() > 0 ? $get_data_feedback->row()->site_code: '',
  //               'created_at' => $this->model_outlet_transaksi->timezone(),
  //               'created_by' => $this->session->userdata('id')
  //           ];
  //           // echo "<pre>data_feedback";
  //           // print_r($data);
  //           // echo "</pre>";
  //           // die;     
  //           $insert = $this->model_products->insert_kenaikan_harga_monitoring_feedback($data);

  //           // die;

  //           // get_kenaikan_harga_detail_by_id_header_no_master_product
  //           // echo "get_kenaikan_harga_detail_by_id_header";
  //           $get_detail = $this->model_products->get_kenaikan_harga_detail_by_id_header_no_master_product($key->id);
  //           if($get_detail->num_rows() > 0)
  //           {
  //               $data_detail = [];
  //               foreach ($get_detail->result() as $d) {
  //                   $data_detail[] = [
  //                       'id_header' => $d->id_header,
  //                       'kodeprod' => $d->kodeprod,
  //                       'harga_jual_grosir' => $d->harga_jual_grosir,
  //                       'harga_jual_retail' => $d->harga_jual_retail,
  //                       'harga_jual_motoris_retail' => $d->harga_jual_motoris_retail,
  //                       'harga_jual_mt' => $d->harga_jual_mt,
  //                   ];
  //               }
  //           }

  //           // echo "<pre>data_detail";
  //           // print_r($data_detail);
  //           // echo "</pre>";
  //           // die;

  //           // get kenaikan_harga_feedback
  //           // echo "get_feedback_by_id_ticket_id_header";
  //           $get_feedback = $this->model_products->get_feedback_by_id_ticket_id_header($id_ticket, $key->id);
  //           if($get_feedback->num_rows() > 0)
  //           {
  //               // echo "ada data feedback";
  //               $id_header_feedback = $get_feedback->row()->id;
  //               // echo "id_header_feedback : " . $id_header_feedback;

  //               $get_detail_feedback = $this->model_products->get_detail_feedback_by_id_header($id_header_feedback);
  //               if($get_detail_feedback->num_rows() > 0)
  //               {
  //                   $data_detail_feedback = [];
  //                   foreach ($get_detail_feedback->result() as $d) {
  //                       $data_detail_feedback[] = [
  //                           'id_header' => $d->id_header,
  //                           'kodeprod' => $d->kodeprod,
  //                           'harga_jual_grosir' => $d->harga_jual_grosir,
  //                           'harga_jual_retail' => $d->harga_jual_retail,
  //                           'harga_jual_motoris_retail' => $d->harga_jual_motoris_retail,
  //                           'harga_jual_mt' => $d->harga_jual_mt,
  //                       ];
  //                   }
  //               }else{
  //                   // echo "tidak ada data detail feedback";
  //               }
  //           }else{
  //               // echo "tidak ada data feedback";
  //               $data_detail_feedback = [];
  //           }

  //           // echo "<hr>";

  //           // MENGGABUNGKAN $data_detail dan $data_detail_feedback
  //           $merged_data = [];
            
  //           // Jika kedua array ada datanya
  //           if (!empty($data_detail) && !empty($data_detail_feedback)) 
  //           {
  //               // echo "jika kedua array ada datanya";
  //               // echo "<pre>data_detail";
  //               // print_r($data_detail);
  //               // echo "</pre>";
  //               // Gabungkan berdasarkan kodeprod
  //               foreach ($data_detail as $detail) 
  //               {
  //                   // echo "<pre>detail";
  //                   // print_r($detail);
  //                   // echo "</pre>";

  //                   foreach ($data_detail_feedback as $feedback) 
  //                   {

  //                       // echo "<pre>feedback";
  //                       // print_r($feedback);
  //                       // echo "</pre>";

  //                       if ($detail['kodeprod'] == $feedback['kodeprod']) 
  //                       {
  //                           $merged_data = [
  //                               'id_header' => $insert, 
  //                               'id_ticket' => $id_ticket,
  //                               'kodeprod' => $detail['kodeprod'],
  //                               'harga_jual_grosir' => $detail['harga_jual_grosir'],
  //                               'harga_jual_retail' => $detail['harga_jual_retail'],
  //                               'harga_jual_motoris_retail' => $detail['harga_jual_motoris_retail'],
  //                               'harga_jual_mt' => $detail['harga_jual_mt'],
  //                               'harga_jual_grosir_feedback' => $feedback['harga_jual_grosir'],
  //                               'harga_jual_retail_feedback' => $feedback['harga_jual_retail'],
  //                               'harga_jual_motoris_retail_feedback' => $feedback['harga_jual_motoris_retail'],
  //                               'harga_jual_mt_feedback' => $feedback['harga_jual_mt'],
  //                               'created_at' => $this->model_outlet_transaksi->timezone(),
  //                               'created_by' => $this->session->userdata('id')
  //                           ];
  //                           echo "<pre>merged_data";
  //                           print_r($merged_data);
  //                           echo "</pre>";
  //                           // die;
  //                           $insert_detail = $this->model_products->insert_kenaikan_harga_monitoring_detail_feedback($merged_data);
  //                           break;
  //                       }
  //                   }
  //               }
  //           } 

  //           // die;
  //           // Jika hanya data_detail yang ada
  //           elseif (!empty($data_detail)) 
  //           {   
  //               // echo "jika data_detail ada dan data_detail_feedback tidak ada<BR />";
  //               foreach ($data_detail as $detail) 
  //               {
  //                   $merged_data = [
  //                       'id_header' => $insert, 
  //                       'id_ticket' => $id_ticket,
  //                       'kodeprod' => $detail['kodeprod'],
  //                       'harga_jual_grosir' => $detail['harga_jual_grosir'],
  //                       'harga_jual_retail' => $detail['harga_jual_retail'],
  //                       'harga_jual_motoris_retail' => $detail['harga_jual_motoris_retail'],
  //                       'harga_jual_mt' => $detail['harga_jual_mt'],
  //                       'created_at' => $this->model_outlet_transaksi->timezone(),
  //                       'created_by' => $this->session->userdata('id')
  //                   ];

  //                   // echo "<pre>";
  //                   // print_r($merged_data);
  //                   // echo "</pre>";

  //                   $insert_detail = $this->model_products->insert_kenaikan_harga_monitoring_detail_feedback($merged_data);
  //               }
  //               // echo "<pre>";
  //               // print_r($merged_data);
  //               // echo "</pre>";
  //           }
  //       }
  //   }

  //   redirect('products/monitoring/'.$signature_ticket);

  // }

//   public function updating_monitoring_feedback($signature_ticket)
// {
//     $get_ticket = $this->model_products->get_ticket_by_signature($signature_ticket);
//     if($get_ticket->num_rows() == 0){
//         $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
//         redirect('products/kenaikan_harga');
//     }

//     $id_ticket = $get_ticket->row()->id;
    
//     $get_header = $this->model_products->get_kenaikan_harga_header_by_id_ticket($id_ticket);

//     $all_site_codes = [];
//     foreach ($get_header->result() as $key => $value) {
//         $site_codes_array = json_decode($value->site_code, true);
//         if (is_array($site_codes_array)) {
//             $all_site_codes = array_merge($all_site_codes, $site_codes_array);
//         }
//     }
//     $site_code = "'" . implode("','", $all_site_codes) . "'";
        
//     //delete from kenaikan_harga_monitoring_feedback by id_ticket
//     $delete = $this->model_products->delete_kenaikan_harga_monitoring_feedback_by_id_ticket($id_ticket);
//     $delete = $this->model_products->delete_kenaikan_harga_monitoring_detail_feedback_by_id_ticket($id_ticket);

//     foreach ($get_header->result() as $key) 
//     {
//         //count site_code
//         $count_site_code = json_decode($key->site_code, true);
        
//         // Ambil data detail MASTER (harga yang di-request) untuk header ini
//         $get_detail_master = $this->model_products->get_kenaikan_harga_detail_by_id_header_no_master_product($key->id);
        
//         // Ambil data FEEDBACK untuk header ini
//         $get_feedback_header = $this->model_products->get_feedback_by_id_ticket_id_header($id_ticket, $key->id);
        
//         for ($i=0; $i < count($count_site_code); $i++) 
//         { 
//             $current_site_code = $count_site_code[$i];
            
//             // Cek apakah ada feedback untuk site code ini
//             $get_data_feedback = $this->model_products->get_feedback_by_idticket_idheader_site_code($id_ticket, $key->id, $current_site_code);

//             // Insert ke monitoring_feedback
//             $data = [
//                 'id_ticket' => $id_ticket,
//                 'id_header' => $key->id,
//                 'site_code' => $current_site_code,
//                 'site_code_feedback' => $get_data_feedback->num_rows() > 0 ? $get_data_feedback->row()->site_code: '',
//                 'created_at' => $this->model_outlet_transaksi->timezone(),
//                 'created_by' => $this->session->userdata('id')
//             ];
            
//             $insert_id_monitoring_feedback = $this->model_products->insert_kenaikan_harga_monitoring_feedback($data);
            
//             // Siapkan array untuk detail master
//             $data_detail_master = [];
//             if($get_detail_master->num_rows() > 0)
//             {
//                 foreach ($get_detail_master->result() as $d) {
//                     $data_detail_master[$d->kodeprod] = [
//                         'harga_jual_grosir' => $d->harga_jual_grosir,
//                         'harga_jual_retail' => $d->harga_jual_retail,
//                         'harga_jual_motoris_retail' => $d->harga_jual_motoris_retail,
//                         'harga_jual_mt' => $d->harga_jual_mt,
//                     ];
//                 }
//             }
            
//             // Siapkan array untuk detail feedback (hanya untuk site code ini)
//             $data_detail_feedback = [];
//             if($get_feedback_header->num_rows() > 0)
//             {
//                 $id_header_feedback = $get_feedback_header->row()->id;
//                 $get_detail_feedback = $this->model_products->get_detail_feedback_by_id_header($id_header_feedback);
                
//                 if($get_detail_feedback->num_rows() > 0)
//                 {
//                     foreach ($get_detail_feedback->result() as $d) {
//                         $data_detail_feedback[$d->kodeprod] = [
//                             'harga_jual_grosir' => $d->harga_jual_grosir,
//                             'harga_jual_retail' => $d->harga_jual_retail,
//                             'harga_jual_motoris_retail' => $d->harga_jual_motoris_retail,
//                             'harga_jual_mt' => $d->harga_jual_mt,
//                         ];
//                     }
//                 }
//             }
            
//             // Gabungkan data per kodeprod
//             if (!empty($data_detail_master)) 
//             {
//                 foreach ($data_detail_master as $kodeprod => $master_data) 
//                 {
//                     $merged_data = [
//                         'id_header' => $insert_id_monitoring_feedback, // Gunakan ID dari monitoring_feedback
//                         'id_ticket' => $id_ticket,
//                         'kodeprod' => $kodeprod,
//                         'harga_jual_grosir' => $master_data['harga_jual_grosir'],
//                         'harga_jual_retail' => $master_data['harga_jual_retail'],
//                         'harga_jual_motoris_retail' => $master_data['harga_jual_motoris_retail'],
//                         'harga_jual_mt' => $master_data['harga_jual_mt'],
//                         'created_at' => $this->model_outlet_transaksi->timezone(),
//                         'created_by' => $this->session->userdata('id')
//                     ];
                    
//                     // Tambahkan data feedback jika ada untuk kodeprod ini
//                     if (isset($data_detail_feedback[$kodeprod])) {
//                         $merged_data['harga_jual_grosir_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_grosir'];
//                         $merged_data['harga_jual_retail_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_retail'];
//                         $merged_data['harga_jual_motoris_retail_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_motoris_retail'];
//                         $merged_data['harga_jual_mt_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_mt'];
//                     }
                    
//                     $insert_detail = $this->model_products->insert_kenaikan_harga_monitoring_detail_feedback($merged_data);
//                 }
//             }
//         }
//     }
    
//     redirect('products/monitoring/'.$signature_ticket);
// }

  // public function updating_monitoring_feedback($signature_ticket)
  // {
  //   $get_ticket = $this->model_products->get_ticket_by_signature($signature_ticket);
  //   if($get_ticket->num_rows() == 0){
  //       $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
  //       redirect('products/kenaikan_harga');
  //   }

  //   $id_ticket = $get_ticket->row()->id;
    
  //   $get_header = $this->model_products->get_kenaikan_harga_header_by_id_ticket($id_ticket);

  //   $all_site_codes = [];
  //   foreach ($get_header->result() as $key => $value) {
  //       $site_codes_array = json_decode($value->site_code, true);
  //       if (is_array($site_codes_array)) {
  //           $all_site_codes = array_merge($all_site_codes, $site_codes_array);
  //       }
  //   }
        
  //   //delete from kenaikan_harga_monitoring_feedback by id_ticket
  //   $delete = $this->model_products->delete_kenaikan_harga_monitoring_feedback_by_id_ticket($id_ticket);
  //   $delete = $this->model_products->delete_kenaikan_harga_monitoring_detail_feedback_by_id_ticket($id_ticket);

  //   foreach ($get_header->result() as $key) 
  //   {
  //       //count site_code
  //       $count_site_code = json_decode($key->site_code, true);
        
  //       // Ambil data detail MASTER (harga yang di-request) untuk header ini
  //       $get_detail_master = $this->model_products->get_kenaikan_harga_detail_by_id_header_no_master_product($key->id);
        
  //       for ($i=0; $i < count($count_site_code); $i++) 
  //       { 
  //           $current_site_code = $count_site_code[$i];
            
  //           // Cek apakah ada feedback untuk site code ini
  //           $get_data_feedback = $this->model_products->get_feedback_by_idticket_idheader_site_code($id_ticket, $key->id, $current_site_code);
            
  //           // Insert ke monitoring_feedback
  //           $data = [
  //               'id_ticket' => $id_ticket,
  //               'id_header' => $key->id,
  //               'site_code' => $current_site_code,
  //               'site_code_feedback' => $get_data_feedback->num_rows() > 0 ? $get_data_feedback->row()->site_code: '',
  //               'created_at' => $this->model_outlet_transaksi->timezone(),
  //               'created_by' => $this->session->userdata('id')
  //           ];
            
  //           $insert_id_monitoring_feedback = $this->model_products->insert_kenaikan_harga_monitoring_feedback($data);
            
  //           // Siapkan array untuk detail master
  //           $data_detail_master = [];
  //           if($get_detail_master->num_rows() > 0)
  //           {
  //               foreach ($get_detail_master->result() as $d) {
  //                   $data_detail_master[$d->kodeprod] = [
  //                       'harga_jual_grosir' => $d->harga_jual_grosir,
  //                       'harga_jual_retail' => $d->harga_jual_retail,
  //                       'harga_jual_motoris_retail' => $d->harga_jual_motoris_retail,
  //                       'harga_jual_mt' => $d->harga_jual_mt,
  //                   ];
  //               }
  //           }
            
  //           // Hanya ambil data feedback jika site code ini ADA di tabel feedback
  //           $data_detail_feedback = [];
  //           if($get_data_feedback->num_rows() > 0)
  //           {
  //               // Ambil id_header_feedback dari hasil query
  //               $feedback_data = $get_data_feedback->row();
  //               $id_header_feedback = $feedback_data->id; // Asumsi kolom id dari tabel feedback
                
  //               $get_detail_feedback = $this->model_products->get_detail_feedback_by_id_header($id_header_feedback);
                
  //               if($get_detail_feedback->num_rows() > 0)
  //               {
  //                   foreach ($get_detail_feedback->result() as $d) {
  //                       $data_detail_feedback[$d->kodeprod] = [
  //                           'harga_jual_grosir' => $d->harga_jual_grosir,
  //                           'harga_jual_retail' => $d->harga_jual_retail,
  //                           'harga_jual_motoris_retail' => $d->harga_jual_motoris_retail,
  //                           'harga_jual_mt' => $d->harga_jual_mt,
  //                       ];
  //                   }
  //               }
  //           }
            
  //           // Gabungkan data per kodeprod
  //           if (!empty($data_detail_master)) 
  //           {
  //               foreach ($data_detail_master as $kodeprod => $master_data) 
  //               {
  //                   $merged_data = [
  //                       'id_header' => $insert_id_monitoring_feedback,
  //                       'id_ticket' => $id_ticket,
  //                       'kodeprod' => $kodeprod,
  //                       'harga_jual_grosir' => $master_data['harga_jual_grosir'],
  //                       'harga_jual_retail' => $master_data['harga_jual_retail'],
  //                       'harga_jual_motoris_retail' => $master_data['harga_jual_motoris_retail'],
  //                       'harga_jual_mt' => $master_data['harga_jual_mt'],
  //                       'created_at' => $this->model_outlet_transaksi->timezone(),
  //                       'created_by' => $this->session->userdata('id')
  //                   ];
                    
  //                   // Tambahkan data feedback HANYA jika ada untuk site code ini
  //                   if (!empty($data_detail_feedback) && isset($data_detail_feedback[$kodeprod])) {
  //                       $merged_data['harga_jual_grosir_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_grosir'];
  //                       $merged_data['harga_jual_retail_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_retail'];
  //                       $merged_data['harga_jual_motoris_retail_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_motoris_retail'];
  //                       $merged_data['harga_jual_mt_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_mt'];
  //                   }
                    
  //                   $insert_detail = $this->model_products->insert_kenaikan_harga_monitoring_detail_feedback($merged_data);
  //               }
  //           }
  //       }
  //   }
    
  //   redirect('products/monitoring/'.$signature_ticket);
  // }

  // application/controllers/products.php

public function updating_monitoring_feedback($signature_ticket)
{
    $get_ticket = $this->model_products->get_ticket_by_signature($signature_ticket);
    if($get_ticket->num_rows() == 0){
        $this->session->set_flashdata('pesan', 'Data tidak ditemukan');
        redirect('products/kenaikan_harga');
    }

    $id_ticket = $get_ticket->row()->id;
    
    $get_header = $this->model_products->get_kenaikan_harga_header_by_id_ticket($id_ticket);

    $all_site_codes = [];
    foreach ($get_header->result() as $key => $value) {
        $site_codes_array = json_decode($value->site_code, true);
        if (is_array($site_codes_array)) {
            $all_site_codes = array_merge($all_site_codes, $site_codes_array);
        }
    }
        
    //delete from kenaikan_harga_monitoring_feedback by id_ticket
    $delete = $this->model_products->delete_kenaikan_harga_monitoring_feedback_by_id_ticket($id_ticket);
    $delete = $this->model_products->delete_kenaikan_harga_monitoring_detail_feedback_by_id_ticket($id_ticket);

    foreach ($get_header->result() as $key) 
    {
        //count site_code
        $count_site_code = json_decode($key->site_code, true);
        
        // Ambil data detail MASTER (harga yang di-request) untuk header ini
        $get_detail_master = $this->model_products->get_kenaikan_harga_detail_by_id_header_no_master_product($key->id);
        
        for ($i=0; $i < count($count_site_code); $i++) 
        { 
            $current_site_code = $count_site_code[$i];
            
            // Cek apakah ada feedback untuk site code ini (ambil yang TERBARU)
            $get_data_feedback = $this->model_products->get_feedback_by_idticket_idheader_site_code($id_ticket, $key->id, $current_site_code);
            
            // Insert ke monitoring_feedback
            $data = [
                'id_ticket' => $id_ticket,
                'id_header' => $key->id,
                'site_code' => $current_site_code,
                'site_code_feedback' => $get_data_feedback->num_rows() > 0 ? $get_data_feedback->row()->site_code: '',
                'created_at' => $this->model_outlet_transaksi->timezone(),
                'created_by' => $this->session->userdata('id')
            ];
            
            $insert_id_monitoring_feedback = $this->model_products->insert_kenaikan_harga_monitoring_feedback($data);
            
            // Siapkan array untuk detail master
            $data_detail_master = [];
            if($get_detail_master->num_rows() > 0)
            {
                foreach ($get_detail_master->result() as $d) {
                    $data_detail_master[$d->kodeprod] = [
                        'harga_jual_grosir' => $d->harga_jual_grosir,
                        'harga_jual_retail' => $d->harga_jual_retail,
                        'harga_jual_motoris_retail' => $d->harga_jual_motoris_retail,
                        'harga_jual_mt' => $d->harga_jual_mt,
                    ];
                }
            }
            
            // Hanya ambil data feedback jika site code ini ADA di tabel feedback
            $data_detail_feedback = [];
            if($get_data_feedback->num_rows() > 0)
            {
                // Ambil id_feedback dari hasil query (yang terbaru)
                $feedback_data = $get_data_feedback->row();
                $id_feedback = $feedback_data->id;
                
                // Ambil detail feedback berdasarkan id_feedback
                $get_detail_feedback = $this->model_products->get_detail_feedback_by_id_feedback($id_feedback);
                
                if($get_detail_feedback->num_rows() > 0)
                {
                    foreach ($get_detail_feedback->result() as $d) {
                        $data_detail_feedback[$d->kodeprod] = [
                            'harga_jual_grosir' => $d->harga_jual_grosir,
                            'harga_jual_retail' => $d->harga_jual_retail,
                            'harga_jual_motoris_retail' => $d->harga_jual_motoris_retail,
                            'harga_jual_mt' => $d->harga_jual_mt,
                        ];
                    }
                }
            }
            
            // Gabungkan data per kodeprod
            if (!empty($data_detail_master)) 
            {
                foreach ($data_detail_master as $kodeprod => $master_data) 
                {
                    $merged_data = [
                        'id_header' => $insert_id_monitoring_feedback,
                        'id_ticket' => $id_ticket,
                        'kodeprod' => $kodeprod,
                        'harga_jual_grosir' => $master_data['harga_jual_grosir'],
                        'harga_jual_retail' => $master_data['harga_jual_retail'],
                        'harga_jual_motoris_retail' => $master_data['harga_jual_motoris_retail'],
                        'harga_jual_mt' => $master_data['harga_jual_mt'],
                        'created_at' => $this->model_outlet_transaksi->timezone(),
                        'created_by' => $this->session->userdata('id')
                    ];
                    
                    // Tambahkan data feedback HANYA jika ada untuk site code ini
                    if (!empty($data_detail_feedback) && isset($data_detail_feedback[$kodeprod])) {
                        $merged_data['harga_jual_grosir_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_grosir'];
                        $merged_data['harga_jual_retail_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_retail'];
                        $merged_data['harga_jual_motoris_retail_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_motoris_retail'];
                        $merged_data['harga_jual_mt_feedback'] = $data_detail_feedback[$kodeprod]['harga_jual_mt'];
                    }
                    
                    $insert_detail = $this->model_products->insert_kenaikan_harga_monitoring_detail_feedback($merged_data);
                }
            }
        }
    }
    
    redirect('products/monitoring/'.$signature_ticket);
}

}
?>
