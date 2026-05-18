<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class purchase_requistion extends MY_Controller
{
    function __construct()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_purchase_requistion');
        $this->load->model('M_menu');
        $this->load->model('model_sales_omzet');
        $this->load->model('model_rpd');
        $this->load->database();

    }

    public function index()
    {
        $this->purchase_requistion();
    }

    public function get_data()
    {
        $id = $_GET['id'];
        $data['pr'] = $this->M_purchase_requistion->purchase_requistion_asset_by_id($id)->result();
        // $data['nopr'] = $this->M_purchase_requistion->purchase_requistion_asset_by_nopr($id)->result();
        echo json_encode($data);
    }

    public function purchase_requistion()
    {
        $userid = $this->session->userdata('id');

        if ($userid == '297' || $userid == '547' || $userid == '634' || $userid == '231' || $userid == '134') {
            $pr = $this->M_purchase_requistion->purchase_requistion_asset()->result();
        }else{
            $pr = $this->M_purchase_requistion->purchase_requistion_asset_by_m_karyawan($userid)->result();
        }
        
        $data = [
            'url' => 'purchase_requistion/purchase_requistion_simpan',
            'title' => 'Purchase Request',
            'get_label' => $this->M_menu->get_label(),
            'pr' => $pr,
            'pr_pribadi' => $this->M_purchase_requistion->purchase_requistion_asset($userid)->result(),
        ];
        // echo "<pre>";
        // var_dump($this->M_purchase_requistion->my_asset());
        // echo "</spre>";
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('purchase_requistion/index',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function purchase_requistion_simpan()
    {
        $no_pr = $this->M_purchase_requistion->generate_no_pr();

        $data = [
            // 'tanggal' => $this->input->post('tanggal'),
            'no_pr' => $no_pr,
            'divisi' => $this->input->post('divisi'),
            'barang' => $this->input->post('barang'),
            'keterangan' => $this->input->post('keterangan'),
            'created_by' => $this->session->userdata('id'),
            'created_at' => $this->model_sales_omzet->timezone2(),
        ];

        $this->M_purchase_requistion->simpan('site.t_asset_purchase_requistion',$data);

        $this->email_pr($no_pr);
    }

    public function purchase_requistion_konfirm_atasan()
    {
        $id = $this->input->post('id');
        $simpan = $this->input->post('simpan');
        if ($simpan == 1) {
            $nama_status = 'approved atasan (menunggu suggestion spec (IT))';
        } else {
            # code...
            $nama_status = 'rejected atasan';
        }

        $data = [
            'userid_atasan' => $this->session->userdata('id'),
            'username_atasan' => $this->session->userdata('username'),
            'tgl_konfirmasi_atasan' => $this->model_sales_omzet->timezone2(),
            'keterangan_atasan' => $this->input->post('keterangan_atasan'),
            'status' => $simpan,
            'nama_status' => $nama_status,
            'updated_at' => $this->model_sales_omzet->timezone2(),
            'updated_by' => $this->session->userdata('id'),
        ];
        $this->db->where('id', $id);
        $this->db->update('site.t_asset_purchase_requistion', $data);

        redirect("purchase_requistion/",'refresh');
    }


    public function purchase_requistion_konfirm_it()
    {
        // var_dump($a);die;
        $id = $this->input->post('id');
        $tanggal = $this->model_sales_omzet->timezone2();
        // var_dump($jml_product);die;
        $simpan = $this->input->post('simpan');
        if ($simpan == 2) {
            $nama_status = 'spec suggested (menunggu final approval (finance))';
        } else {
            # code...
            $nama_status = 'rejected it';
        }
        
        $data = [
            'spesifikasi' => $this->input->post('spesifikasi'),
            'userid_it' => $this->session->userdata('id'),
            'username_it' => $this->session->userdata('username'),
            'tgl_konfirmasi_it' => $this->model_sales_omzet->timezone2(),
            'keterangan_it' => $this->input->post('keterangan_it'),
            'status' => $simpan,
            'nama_status' => $nama_status,
            'flag_bypass' => $this->input->post('bypass'),
            'updated_by' => $this->session->userdata('id'),
            'updated_at' => $tanggal,
        ];
        $this->db->where('id',$id);
        $this->db->update('site.t_asset_purchase_requistion',$data);
        
        redirect("purchase_requistion/",'refresh');
    }

    public function purchase_requistion_konfirm_purchasing()
    {
        // var_dump($a);die;
        $id = $this->input->post('id');
        $tanggal = $this->model_sales_omzet->timezone2();
        // var_dump($jml_product);die;
        $simpan = $this->input->post('simpan');

        $header = [
            'userid_purchasing' => $this->session->userdata('id'),
            'username_purchasing' => $this->session->userdata('username'),
            'tgl_konfirmasi_purchasing' => $this->model_sales_omzet->timezone2(),
            'keterangan_purchasing' => $this->input->post('keterangan_purchasing'),
            'status' => $simpan,
            'nama_status' => 'Final approval (finance)',
            'updated_by' => $this->session->userdata('id'),
            'updated_at' => $tanggal,
        ];
        $this->db->where('id',$id);
        $this->db->update('site.t_asset_purchase_requistion',$header);

        redirect("purchase_requistion/",'refresh');
    }
    
    public function purchase_requistion_konfirm_finance()
    {
        $id = $this->input->post('id');
        $simpan = $this->input->post('simpan');
        if ($simpan == 4) {
            $status = 4;
            $nama_status = 'approved finance';
        } else {
            $this->db->select('status,flag_bypass');
            $this->db->from('site.t_asset_purchase_requistion');
            $this->db->where('id', $id);

            $pr = $this->db->get()->row();
            $status = $pr->status - 1;
            $nama_status = 'rejected finance';
        }

        $data = [
            'userid_finance' => $this->session->userdata('id'),
            'username_finance' => $this->session->userdata('username'),
            'tgl_konfirmasi_finance' => $this->model_sales_omzet->timezone2(),
            'keterangan_finance' => $this->input->post('keterangan_finance'),
            'status' => $status,
            'nama_status' => $nama_status,
            'updated_at' => $this->model_sales_omzet->timezone2(),
            'updated_by' => $this->session->userdata('id'),
        ];
        $this->db->where('id', $id);
        $this->db->update('site.t_asset_purchase_requistion', $data);

        redirect("purchase_requistion/",'refresh');
    }

    public function download_pdf()
    {
        $this->load->library('mypdf');
        $kode = $this->uri->segment('3');
        $nomer = $this->uri->segment('4');
        $no_pr = $kode.'/'.$nomer;
        // var_dump($no_pr);die;
        $data_pr = $this->M_purchase_requistion->purchase_requistion_asset_by_id($no_pr)->row();
        // var_dump($data_pr);die;
        $generate_pdf = $this->mypdf->generate('purchase_requistion/template_pdf_pr',$data_pr,$no_pr,'A4','portrait');

    }
    
    public function email(){
        $this->load->library('email');
        $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        // $config['smtp_user']    = 'support@muliaputramandiri.com';
        $config['smtp_user']    = 'suffy@muliaputramandiri.net';
        // $config['smtp_pass']    = 'support123!@#';
        $config['smtp_pass']    = 'vruzinbjlnsgzagy';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
    }

    public function email_pr($no_pr)
	{
        $url = base_url().'purchase_requistion';
        // var_dump($signature);die;
        $this->db->select('*');
        $this->db->where('no_pr', $no_pr);
        $data_pr = $this->db->get('site.t_asset_purchase_requistion')->row();
        $userid = $data_pr->created_by;
        // var_dump($data_pr);die;

        // var_dump($data_pr);
        // $filename = $get_kode->kode;
        // $filename_pdf = str_replace("/","_", $filename);
        $filename = $data_pr->no_pr;
        $filename_pdf = str_replace("/","_", $filename);
        // echo "filename_pdf : ".$filename_pdf;
        // die;

        $data_karyawan = $this->model_rpd->getMaster_karyawan('',$userid)->row();
        // var_dump($data_karyawan);
        // die;

        if (!$data_karyawan) {
            echo "<script>alert('Pengiriman email error. Silahkan hubungi IT !'); window.location.replace('$url');</script>";
            die;
        }

        // var_dump($data_karyawan);die;
        $email_karyawan = $data_karyawan->email_karyawan;
        $email_atasan = $data_karyawan->email_atasan;

        // var_dump($biaya);die;

		$detail = [
            'atasan_id' => $data_karyawan->atasan_id,
            'nama_atasan' => $data_karyawan->nama_atasan,
			'kode' => $no_pr,
            'divisi' => $data_pr->divisi,
            'nama_karyawan' => $data_karyawan->nama_karyawan,
            'keterangan' => $data_pr->keterangan,
		];

        // var_dump($detail);die;

        $from = "ilhammsyah@gmail.com";
        $to = "ilhammsyah@gmail.com";
        // $cc = "ilhammsyah@gmail.com,suffy@muliaputramandiri.com";
        $subject = "Mulia Putra Mandiri | Purchase Requistion";

        $message = $this->load->view("purchase_requistion/email_pr",$detail,TRUE);

        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        // $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets_new/file/rpd/'.$filename_pdf.'.pdf');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('Pengajuan asset sudah berhasil dan diteruskan ke atasan anda untuk proses approval'); window.location.replace('$url');</script>";
        }else{
            echo "<script>alert('Pengiriman email pengajuan Pengajuan asset gagal'); window.location.replace('$url');</script>";
        }
	}
}
?>