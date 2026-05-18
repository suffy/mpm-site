<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Finance extends MY_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'Finance';
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_management_office', 'model_management_claim','model_finance'));
        $this->session_id = $this->session->userdata('id');
        $this->session_supp = $this->session->userdata('supp');
        $this->tahun = date('Y');
        $this->bulan = date('m');
        $this->created_at = $this->model_outlet_transaksi->timezone();
    }

  function list_data()
  {
    $subbranch = $this->input->get('subbranch');
    // echo "subbranch : ".$subbranch;
    $from = $this->input->get('from');
    $to = $this->input->get('to');

    // echo "from : ".$from;
    // echo "to : ".$to;
    $get_max_piutang_date = $this->model_finance->get_max_piutang_date();

    if($get_max_piutang_date->num_rows() > 0){
        $max_piutang_date = $get_max_piutang_date->row()->last_updated;
    }else{
        $max_piutang_date = "belum ada data";
    }

    if ($subbranch) {
        $params_subbranch = $subbranch;
    }else{            
        $params_subbranch = ''; 
    }

    // echo "params_subbranch : ".$params_subbranch;

    $time = $this->model_outlet_transaksi->timezone();
    $periode = date('Ym', strtotime($time));

    $data = [
      'title' => 'Open Credit Limit',
      'url'   => 'list_data',
      // 'get_customer_sds' => $this->model_finance->get_customer_sds(),
      'get_customer' => $this->model_finance->get_master_user($this->tahun),
      'get_data'  => $this->model_finance->get_spk($this->tahun, $this->bulan, '', $params_subbranch, $from, $to),
      'max_piutang_date' => $max_piutang_date,
      'periode_now' => $periode
    ];    
    $this->render('finance/open_credit_limit', $data);
  }

  public function detail_po($signature)
  {
      $this->load->model('model_spk');
      // echo "signature : ".$signature;
      $get_po = $this->model_spk->get_po_by_signature($signature);
      if (!$get_po->num_rows() > 0)
      {
          $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
          redirect('finance/index');
      }else{
          $nopo = $get_po->row()->nopo;
          // $get_po = $this->model_spk->get_po_by_signature($signature);
          $id_po = $get_po->row()->id;
          $namasupp = $get_po->row()->namasupp;
          $branch_name = $get_po->row()->branch_name;
          $nama_comp = $get_po->row()->nama_comp;
          $npwp = $get_po->row()->npwp;
          $email = $get_po->row()->email;
          $kode_alamat = $get_po->row()->kode_alamat;
          $company = $get_po->row()->company;
          $alamat_kirim = $get_po->row()->alamat_kirim;
          $alamat = $get_po->row()->alamat;
          $email = $get_po->row()->email;
          $tipe = $get_po->row()->tipe;
          $tglpesan = $get_po->row()->tglpesan;
          $flag_open = $get_po->row()->open;
          $open_date = $get_po->row()->open_date;
          $status = $get_po->row()->status;
          $status_approval = $get_po->row()->status_approval;
          $alasan_approval = $get_po->row()->alasan_approval;
          $note = $get_po->row()->note;
          $po_ref = $get_po->row()->po_ref;
          $is_pp_approval = $get_po->row()->is_pp_approval;
          $pp_approved_file = $get_po->row()->pp_approved_file;

          // echo "nopo : ".$nopo;
          // echo "id_po : ".$id_po;
      }

      $data = [
          'title'         => 'List Order Detail',
          'url'           => 'spk/list_order_detail',
          'get_data'      => $this->model_spk->get_po_detail_by_id_po_include_delete($id_po),
          'nopo'          => $nopo,
          'namasupp'      => $namasupp,
          'branch_name'   => $branch_name,
          'nama_comp'     => $nama_comp,
          'npwp'          => $npwp,
          'email'         => $email,
          'kode_alamat'   => $kode_alamat,
          'company'       => $company,
          'alamat_kirim'  => $alamat_kirim,
          'alamat'        => $alamat,
          'email'         => $email,
          'tipe'          => $tipe,
          'tglpesan'      => $tglpesan,
          'flag_open'     => $flag_open,
          'status'        => $status,
          'status_approval' => $status_approval,
          'alasan_approval' => $alasan_approval,
          'open_date'     => $open_date,
          'signature'     => $signature,
          'note'          => $note,
          'po_ref'        => $po_ref,
          'id_po'         => $id_po,
          'is_pp_approval'    => $is_pp_approval,
          'pp_approved_file'  => $pp_approved_file
      ];
      // $this->navbar($data);
      // $this->load->view('kalimantan/header_full_width', $data);
      // $this->load->view('management_claim/css');
      // $this->load->view('spk/accordion_list_order_detail', $data);
      // $this->load->view('finance/list_order_detail', $data);
      // $this->load->view('kalimantan/footer');

      
      // $this->render('finance/list_order_detail', $data);

      $this->render_multiple(
          array(
              'spk/accordion_list_order_detail',
              'finance/list_order_detail'
          ),
          $data
      );

  }

  public function unlock($signature)
  {
      if($this->session->userdata('username') != 'nanita' && $this->session->userdata('username') != 'hendra' && $this->session->userdata('username') != 'suffyx'){
          $this->session->set_flashdata("pesan", "Anda tidak diijinkan untuk mengunlock data ini");
          redirect('finance/list_data');
          die;            
      }

      $this->load->model('model_spk');
      // echo "signature : ".$signature;
      $get_po = $this->model_spk->get_po_by_signature($signature);
      if (!$get_po->num_rows() > 0)
      {
          $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
          redirect('finance/list_data');
      }

      $id_po = $get_po->row()->id;

      // echo "id_po : ".$id_po;

      $data = [
          "open"  => '1',
          "lock"  => '1',
          "open_by" => $this->session_id,
          "open_date" => $this->created_at
      ];

      $update = $this->model_finance->update_po($id_po, $data);

      if ($update) {
          $this->session->set_flashdata("pesan_success", "Update Status Berhasil");
      }else{

          $this->session->set_flashdata("pesan", "ada kesalahan. Silahkan ulangi kembali");
      }

      redirect('finance/list_data');       

  }

  public function update_piutang_from_dbsls()
  {
      $this->model_finance->update_piutang_from_dbsls();
      $this->session->set_flashdata("pesan_success", "Update Piutang Berhasil");
      redirect('finance/list_data');
  }

    private function view($data, $flag_accordion, $view)
    {
        if ($flag_accordion == true) {
            $data = [
                "navbar"        => $this->navbar($data),
                "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
                "css"           => $this->load->view('management_claim/css', $data),
                "accordion"     => $this->load->view('management_claim/accordion', $data),
                "view"          => $this->load->view('finance/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        }else{
            $data = [
                "navbar"        => $this->navbar($data),
                "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
                "css"           => $this->load->view('management_claim/css', $data),
                "view"          => $this->load->view('finance/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        }
        return $data;       
    }

    
}
?>
