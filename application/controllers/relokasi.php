<?php

use FontLib\Table\Type\post;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Relokasi extends MY_Controller
{
    
    function relokasi()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_relokasi', 'model_inventory', 'M_menu', 'M_helpdesk'));
    }
    function index()
    {
        $this->pengajuan();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function pengajuan(){
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Pengajuan Relokasi Stock',
            'get_label'   => $this->M_menu->get_label(),
            'site_code'   => $this->M_helpdesk->get_sitecode(),
            'history_relokasi'     => $this->model_relokasi->history_relokasi($kode_alamat,''),
            'url'         => 'relokasi/proses_pengajuan'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('relokasi/pengajuan', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_pengajuan(){

        $signature = md5($this->model_outlet_transaksi->timezone());

        $data = [
            'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan'),
            'from_site'         => $this->input->post('from_site'),
            'to_site'           => $this->input->post('to_site'),
            'nama'              => $this->input->post('nama'),
            'principal'         => $this->input->post('principal'),
            'alasan'            => $this->input->post('alasan'),
            'created_at'        => $this->model_outlet_transaksi->timezone(),
            'created_by'        => $this->session->userdata('id'),
            'no_relokasi'       => $this->model_relokasi->generate($this->input->post('from_site'),$this->input->post('tanggal_pengajuan')),
            'signature'         => $signature,
            'status'            => 1,
            'nama_status'       => 'DRAFT',
        ];

        $this->db->insert('site.t_relokasi_header', $data);
        echo '<script>alert("Data terbentuk, silahkan masukkan detail produk yang ingin di retur !");</script>';
        redirect('relokasi/produk_pengajuan/'.$signature.'/'.$this->input->post('principal'),'refresh');

    }

    public function produk_pengajuan($signature){

        $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;
        $principal = $this->model_relokasi->get_data_relokasi_header($signature)->row()->principal;

        $status = $this->model_relokasi->get_data_relokasi_header($signature)->row()->status;

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Tambah Produk Relokasi',
            'get_label'   => $this->M_menu->get_label(),
            'url'         => 'relokasi/proses_produk_pengajuan',
            'url_upload'  => 'relokasi/upload_produk',
            'history_produk' => $this->model_relokasi->history_produk($get_id_ref_relokasi_header),
            'id_ref'      => $this->model_relokasi->get_data_relokasi_header($signature)->row()->id,
            'signature'   => $signature,
            'principal'   => $principal,
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('relokasi/produk_pengajuan', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function download_template(){
        $query="
            select '' as kodeprod, '' as qty_kecil
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'template_relokasi.csv');
    }

    public function upload_produk(){
        $id_ref = $this->input->post('id_ref');
        $signature = $this->input->post('signature');
        $principal = $this->input->post('principal');

        if (!is_dir('./assets/uploads/relokasi/' . date('Ym') . '/')) {
        @mkdir('./assets/uploads/relokasi/' . date('Ym') . '/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/relokasi/' . date('Ym') . '';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            // echo "masuk";
            // die;
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $file_type = $upload_data['file_type'];
            //   echo "filename : ".$filename."<br>";
            //   echo "file_type : ".$file_type;
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/relokasi/" . date('Ym') . "/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('retur','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $kodeprod = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $qty = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                    if($kodeprod == null || $kodeprod == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom kodeprod, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if(strlen("$kodeprod") == '5')
                    {
                        $kodeprodx = '0'.$kodeprod;
                    }else{
                        $kodeprodx = $kodeprod;
                    } 

                    if ($principal == '001-herbal') 
                    {
                        $grup = array('G0101','G0102');
                        $this->db->where_in('grup', $grup);
                        $this->db->where('kodeprod', $kodeprodx);
                        $cek = $this->db->get('mpm.tabprod');

                        if (!$cek->num_rows() > 0) {
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda kesalahan kodeprod di baris ke $row. Pastikan kodeprod sesuai group principal. Silahkan cek kembali');
                            </script>";
                            redirect('relokasi/produk_pengajuan/'.$signature.'/'.$principal);
                            die;
                        }
                    }elseif ($principal == '001-herbana') 
                    {
                        $this->db->where('grup', 'G0103');
                        $this->db->where('kodeprod', $kodeprodx);
                        $cek = $this->db->get('mpm.tabprod');

                        if (!$cek->num_rows() > 0) {
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda kesalahan kodeprod di baris ke $row. Pastikan kodeprod sesuai group principal. Silahkan cek kembali');
                            </script>";
                            redirect('relokasi/produk_pengajuan/'.$signature.'/'.$principal);
                            die;
                        }
                    }else
                    {
                        $this->db->where('supp', $principal);
                        $this->db->where('kodeprod', $kodeprodx);
                        $cek = $this->db->get('mpm.tabprod');

                        if (!$cek->num_rows() > 0) {
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda kesalahan kodeprod di baris ke $row. Pastikan kodeprod sesuai group principal. Silahkan cek kembali');
                            </script>";
                            redirect('relokasi/produk_pengajuan/'.$signature.'/'.$principal);
                            die;
                        }
                    }

                    $data = [
                        'kodeprod'      => $kodeprodx,
                        'qty'           => $qty,
                        'id_ref'        => $id_ref,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id'),
                    ];
                    $this->db->insert('site.t_relokasi_detail',$data);

                }
            }
        }

        redirect('relokasi/produk_pengajuan/'.$signature.'/'.$principal);
    }

    public function proses_produk_pengajuan(){
        $kodeprod = $this->input->post('kodeprod');
        $qty = $this->input->post('qty');
        $id_ref = $this->input->post('id_ref');
        $signature = $this->input->post('signature');
        $principal = $this->input->post('principal');
        $data = [
            'kodeprod'  => $this->input->post('kodeprod'),
            'qty'       => $this->input->post('qty'),
            'id_ref'    => $this->input->post('id_ref'),
            'created_by'=> $this->session->userdata('id'),
            'created_at'=> $this->model_outlet_transaksi->timezone()
        ];

        $this->db->insert('site.t_relokasi_detail', $data);
        redirect('relokasi/produk_pengajuan/'.$signature.'/'.$principal);

    }

    public function delete_produk($kodeprod, $signature, $supp){
        
        $data = array(
            'deleted'   => '1',
            'updated_at'    => $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
        );
        $this->db->where('kodeprod', $kodeprod);
        $this->db->update('site.t_relokasi_detail', $data); 



        redirect('relokasi/produk_pengajuan/'.$signature.'/'.$supp);
    }

    public function approval_supplychain($signature){
        
        // 1. status dari DRAFT menjadi OPEN
        $data = [
            'status'        => '2',
            'nama_status'   => 'NEED SUPPLYCHAIN APPROVAL',
        ];
        $this->db->where('signature', $signature);
        $this->db->update('site.t_relokasi_header', $data);
        
        // 2. mengirim email ke supplychain head;

        redirect('relokasi/email_supplychain/'.$signature);

    }

    public function email_supplychain($signature){
        // echo 'start email';

        // from linda@muliaputramandiri.com
        $from = "suffy@muliaputramandiri.com";

        // to fakhrul@muliaputramandiri.com
        $to = 'suffy.yanuar@gmail.com';

        // cc email_dp
        $get_from_site= substr($this->model_relokasi->get_data_relokasi_header($signature)->row()->from_site,0,3);
        $get_email_from_site = $this->model_relokasi->get_email_from_username($get_from_site)->row()->email;
        // $cc = $get_email_from_site;
        $cc = 'suffy.mpm@gmail.com';
        
        $no_relokasi= $this->model_relokasi->get_data_relokasi_header($signature)->row()->no_relokasi;
        $subject = "MPM Site | Ajuan Relokasi : $no_relokasi | NEED SUPPLYCHAIN APPROVAL";
        // echo "no_relokasi : ".$no_relokasi;

        $this->model_relokasi->email();

        $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;

        $data = [
            'no_relokasi'       => $this->model_relokasi->history_relokasi('',$signature)->row()->no_relokasi,
            'tanggal_pengajuan' => $this->model_relokasi->history_relokasi('',$signature)->row()->tanggal_pengajuan,
            'from_nama_comp'    => $this->model_relokasi->history_relokasi('',$signature)->row()->from_nama_comp,
            'to_nama_comp'      => $this->model_relokasi->history_relokasi('',$signature)->row()->to_nama_comp,
            'nama'              => $this->model_relokasi->history_relokasi('',$signature)->row()->nama,
            'namasupp'          => $this->model_relokasi->history_relokasi('',$signature)->row()->namasupp,
            'nama_status'       => $this->model_relokasi->history_relokasi('',$signature)->row()->nama_status,
            'alasan'            => $this->model_relokasi->history_relokasi('',$signature)->row()->alasan,
            'history_produk'    => $this->model_relokasi->history_produk($get_id_ref_relokasi_header),
            'signature'         => $signature
        ];
        $message = $this->load->view("relokasi/email_supplychain",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('relokasi/preview_relokasi/'.$signature,'refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('relokasi/preview_relokasi/'.$signature,'refresh');
        }
    }

    public function approval_finance($signature){
        
        // 1. status dari DRAFT menjadi OPEN
        $data = [
            'status'        => '3',
            'nama_status'   => 'NEED FINANCE APPROVAL',
        ];
        $this->db->where('signature', $signature);
        $this->db->update('site.t_relokasi_header', $data);
        
        // 2. mengirim email ke supplychain head;

        redirect('relokasi/email_finance/'.$signature);
    }

    public function email_finance($signature){
        // echo 'start email';

        // from linda@muliaputramandiri.com
        $from = "suffy@muliaputramandiri.com";

        // to hwiryanto@muliaputramandiri.com
        $to = 'suffy.yanuar@gmail.com';

        // cc email_dp
        $get_from_site= substr($this->model_relokasi->get_data_relokasi_header($signature)->row()->from_site,0,3);
        $get_email_from_site = $this->model_relokasi->get_email_from_username($get_from_site)->row()->email;
        // $cc = $get_email_from_site;
        $cc = 'suffy.mpm@gmail.com';
        
        $no_relokasi= $this->model_relokasi->get_data_relokasi_header($signature)->row()->no_relokasi;
        $subject = "MPM Site | Ajuan Relokasi : $no_relokasi | NEED FINANCE APPROVAL";
        // echo "no_relokasi : ".$no_relokasi;

        $this->model_relokasi->email();

        $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;

        $data = [
            'no_relokasi'       => $this->model_relokasi->history_relokasi('',$signature)->row()->no_relokasi,
            'tanggal_pengajuan' => $this->model_relokasi->history_relokasi('',$signature)->row()->tanggal_pengajuan,
            'from_nama_comp'    => $this->model_relokasi->history_relokasi('',$signature)->row()->from_nama_comp,
            'to_nama_comp'      => $this->model_relokasi->history_relokasi('',$signature)->row()->to_nama_comp,
            'nama'              => $this->model_relokasi->history_relokasi('',$signature)->row()->nama,
            'namasupp'          => $this->model_relokasi->history_relokasi('',$signature)->row()->namasupp,
            'nama_status'       => $this->model_relokasi->history_relokasi('',$signature)->row()->nama_status,
            'alasan'            => $this->model_relokasi->history_relokasi('',$signature)->row()->alasan,
            'history_produk'    => $this->model_relokasi->history_produk($get_id_ref_relokasi_header),
            'signature'         => $signature
        ];
        $message = $this->load->view("relokasi/email_finance",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('relokasi/preview_relokasi/'.$signature,'refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('relokasi/preview_relokasi/'.$signature,'refresh');
        }
    }

    public function preview_relokasi($signature){

        $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;
        $principal = $this->model_relokasi->get_data_relokasi_header($signature)->row()->principal;
        
        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Preview Relokasi',
            'get_label'   => $this->M_menu->get_label(),
            'site_code'   => $this->M_helpdesk->get_sitecode(),
            // 'get_relokasi_header'=> $this->model_relokasi->get_data_relokasi_header($signature),
            'history_produk' => $this->model_relokasi->history_produk($get_id_ref_relokasi_header),
            'history_relokasi'     => $this->model_relokasi->history_relokasi('',$signature),
            'url'         => 'relokasi/proses_pengajuan',
            'signature'   => $signature,
            'principal'   => $principal,
            'status'      => $this->model_relokasi->get_data_relokasi_header($signature)->row()->status,
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('relokasi/preview_relokasi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function generate_pdf($signature){

        $this->load->library('mypdf');
        

        $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;
        $created_by = $this->model_relokasi->get_data_relokasi_header($signature)->row()->created_by;

        $this->db->where('id', $created_by);
        $username = $this->db->get('mpm.user')->row()->username;



        $filename_pdf = 'relokasi.pdf';
        $data = [
            'no_relokasi'       => $this->model_relokasi->history_relokasi('',$signature)->row()->no_relokasi,
            'tanggal_pengajuan' => $this->model_relokasi->history_relokasi('',$signature)->row()->tanggal_pengajuan,
            'from_nama_comp'    => $this->model_relokasi->history_relokasi('',$signature)->row()->from_nama_comp,
            'to_nama_comp'      => $this->model_relokasi->history_relokasi('',$signature)->row()->to_nama_comp,
            'nama'              => $this->model_relokasi->history_relokasi('',$signature)->row()->nama,
            'namasupp'          => $this->model_relokasi->history_relokasi('',$signature)->row()->namasupp,
            'status'            => $this->model_relokasi->history_relokasi('',$signature)->row()->status,
            'nama_status'       => $this->model_relokasi->history_relokasi('',$signature)->row()->nama_status,
            'alasan'            => $this->model_relokasi->history_relokasi('',$signature)->row()->alasan,
            'approve_supplychain_at'       => $this->model_relokasi->history_relokasi('',$signature)->row()->approve_supplychain_at,
            'approve_finance_at'       => $this->model_relokasi->history_relokasi('',$signature)->row()->approve_finance_at,
            'detail'            => $this->model_relokasi->history_produk($get_id_ref_relokasi_header),
            'signature'         => $signature,
            'username'          => $username,
        ];

        $generate_pdf = $this->mypdf->generate('relokasi/template_pdf',$data,$filename_pdf,'A4','portrait');
    } 

    public function register_signature(){

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Retur | Register Signature',
            'get_label' => $this->M_menu->get_label(),
            'site_code' => $this->M_helpdesk->get_sitecode()
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('relokasi/register_signature');
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function register_signature_proses(){

        // cek apakah sudah ada di database
        $folderPath = './assets/uploads/signature/';        
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $this->session->userdata('username') . '-signature.' .$image_type;
        file_put_contents($file, $image_base64);
        redirect('relokasi/pengajuan');
        // echo "signature uploaded successsfully";
    }

    public function faktur_retur($signature){


        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Nota Retur',
            'get_label'   => $this->M_menu->get_label(),
            'site_code'   => $this->M_helpdesk->get_sitecode(),
            'get_trans'     => $this->model_relokasi->get_trans($this->model_relokasi->get_data_relokasi_header($signature)->row()->no_relokasi),
            'url'         => 'relokasi/proses_pengajuan'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('relokasi/faktur_retur', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    
    }

    public function upload_surat_jalan(){
        $id_relokasi = $this->input->post('id_relokasi');

        // echo "id_relokasi : ".$id_relokasi;
        // die;

        if (!is_dir('./assets/uploads/relokasi/surat_jalan/')) {
        @mkdir('./assets/uploads/relokasi/surat_jalan/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/relokasi/surat_jalan/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $file_type = $upload_data['file_type'];            

            $created_at = $this->model_outlet_transaksi->timezone();

            $data = [
                'file_surat_jalan'  => $filename
            ];
            $this->db->where('id', $id_relokasi);
            $this->db->update('site.t_relokasi_header', $data);
        }else{
            var_dump($this->upload->display_errors());
            die;
        }

        redirect('relokasi/pengajuan');
    }

}
?>
