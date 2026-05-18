<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Olshop extends MY_Controller
{
    function olshop()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper(array('url','csv'));
        $this->load->model(array('model_olshop','M_menu','model_outlet_transaksi'));
        $this->load->database();
    }

    public function dashboard(){
        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'olshop/import_olshop',
            'title'         => 'Onlineshop | Tokopedia - Shopee',
            'get_label'     => $this->M_menu->get_label(),
            'get_header'   => $this->model_olshop->get_header(),
            'get_report'   => $this->model_olshop->get_report(),

        ];
      
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('olshop/dashboard',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function import_olshop(){
        $path = './assets/file/olshop/file/';
        $created_at = $this->model_outlet_transaksi->timezone();
        $file = $this->input->post('file');
        $olshop = $this->input->post('olshop');

        //jika belum ada folder, maka create folder
        if (!is_dir($path)) {
            @mkdir($path, 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = $path;
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            var_dump($this->upload->display_errors());
            // echo "gagal upload";
            $filename = '';
        }else{

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];

            $data_header = [
                'filename'  => $filename,
                'olshop'    => $olshop,
                'created_at'=> $created_at,
                'created_by'=> $this->session->userdata('id'),
                'updated_at'=> $created_at,
                'updated_by'=> $this->session->userdata('id'),
                'signature_header' => $olshop.'-'.md5($created_at),
            ];

            $insert_history = $this->db->insert('site.t_olshop_header', $data_header);
            $id_header = $this->db->insert_id();

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load($path.$filename);

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('stok/dashboard','refresh');
            }


            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $tgl_olshop_original      = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $tgl_olshop     = strftime('%Y-%m-%d',strtotime($tgl_olshop_original));  
                    $inv_olshop      = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $pembeli_olshop  = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $kodeprod_olshop = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $qty_olshop = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

                    $data = [
                        'id_ref'         => $id_header,
                        'tgl_olshop'     => $tgl_olshop,
                        'inv_olshop'     => $inv_olshop,
                        'pembeli_olshop' => $pembeli_olshop,
                        'kodeprod_olshop'=> $kodeprod_olshop,
                        'qty_olshop'     => $qty_olshop,
                        'created_by'     => $this->session->userdata('id'),
                        'created_at'     => $created_at,
                        'updated_by'     => $this->session->userdata('id'),
                        'updated_at'     => $created_at,
                        'signature_detail'      => $id_header.'-'.md5($created_at),
                    ];

                    $this->db->insert('site.t_olshop_detail',$data);
                }
            }

            $update = "
                update site.t_olshop_detail a
                set a.namaprod_olshop = (
                    select b.namaprod_olshop
                    from site.map_olshop_product b
                    where a.kodeprod_olshop = b.kodeprod_olshop and b.olshop = '$olshop'
                    group by b.kodeprod_olshop
                ), a.harga_retail = (
                    select b.harga_retail
                    from site.map_olshop_product b
                    where a.kodeprod_olshop = b.kodeprod_olshop and b.olshop = '$olshop'
                    group by b.kodeprod_olshop
                )
                where a.id_ref = $id_header
            ";

            // echo "<pre>";
            // print_r($update);
            // echo "</pre>";

            $this->db->query($update);

            $this->db->where('id', $id_header);
            $get_signature_header = $this->db->get('site.t_olshop_header')->row();
            $signature_header = $get_signature_header->signature_header;

            // cek apakah mapping berhasil
            $query_cek_mapping = "
                select *
                from site.t_olshop_detail a
                where a.id_ref = 2 and a.namaprod_olshop is null and a.id_ref = $id_header
            ";
            $proses_cek_mapping = $this->db->query($query_cek_mapping);
            if ($proses_cek_mapping->num_rows() > 0) {

                $this->session->set_flashdata('msg_error', 'Gagal Mapping Data ... !');
                
            }else{

                $this->session->set_flashdata('msg_success', 'Berhasil Mapping Data ... !');
            }
            
            redirect('olshop/detail_history/'.$signature_header);
        }
    }

    public function detail_history($signature_header){

        $get_id_ref = $this->model_olshop->get_id_ref($signature_header)->row();
        $id_ref = $get_id_ref->id;
        $olshop = $get_id_ref->olshop;

        $data = [
            'url'           => 'olshop/save_detail_history',
            'title'         => 'Onlineshop | detail upload',
            'get_label'     => $this->M_menu->get_label(),
            'get_summary'   => $this->model_olshop->get_summary($id_ref, $olshop),
            'get_detaill_by_signature'   => $this->model_olshop->get_detaill_by_signature($signature_header)
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('olshop/detail_history',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function draft_pb($signature_header){

        $get_id_ref = $this->model_olshop->get_id_ref($signature_header)->row();
        $id_ref = $get_id_ref->id;
        $olshop = $get_id_ref->olshop;
        
        // cek apakah sudah ada datanya di t_olshop_generate
        $get_data_ambil_barang = $this->model_olshop->get_data_ambil_barang($signature_header)->row();

        if ($get_data_ambil_barang) 
        { // jika ada
            $no_barang_diambil = $get_data_ambil_barang->no_pengambilan;
            $generate_code = $get_data_ambil_barang->generate_code;
            // cek apakah sudah ambil barang dari gudang ? 
            if ($no_barang_diambil) { // jika sudah
                 
                // echo "x";
                redirect('olshop/status_pengambilan/'.$signature_header);

            }else{ // jika belum

                $data = [
                    'url'           => 'olshop/proses_pengambilan_barang',
                    'title'         => 'Onlineshop | draft pengambilan barang',
                    'generate_code' => $generate_code,
                    'get_label'     => $this->M_menu->get_label(),
                    'get_summary'   => $this->model_olshop->get_summary($id_ref, $olshop)
                ];
        
                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar',$data);
                $this->load->view('template_claim/top_content',$data);
                $this->load->view('olshop/draft_pb',$data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
            }

        }else{ // jika null

            $generate_code = $this->model_olshop->generate_code('draft_pb', $signature_header, $olshop);

            $data = [
                'url'           => 'olshop/proses_pengambilan_barang',
                'title'         => 'Onlineshop | draft pengambilan barang',
                'generate_code' => $generate_code,
                'get_label'     => $this->M_menu->get_label(),
                'get_summary'   => $this->model_olshop->get_summary($id_ref, $olshop)
            ];

            $this->load->view('template_claim/top_header');
            $this->load->view('template_claim/header');
            $this->load->view('template_claim/sidebar',$data);
            $this->load->view('template_claim/top_content',$data);
            $this->load->view('olshop/draft_pb',$data);
            $this->load->view('template_claim/bottom_content');
            $this->load->view('template_claim/footer');
        }
    }

    public function proses_pengambilan_barang(){
        $pic = $this->input->post('pic');
        $tanggal_pengambilan = $this->input->post('tanggal_pengambilan');
        $generate_draft = $this->input->post('generate_draft');
        $signature_header = $this->input->post('signature_header');

        $data = [
            "pic"   => $this->input->post('pic'),
            "tanggal_pengambilan"   => $this->input->post('tanggal_pengambilan'),
            "no_pengambilan"    => str_replace("DRAFT","",$generate_draft),
            "updated_at"    => $this->model_outlet_transaksi->timezone(),
            "updated_by"    => $this->session->userdata('id')
        ];

        $this->db->where('signature_header', $signature_header);
        $this->db->update('site.t_olshop_generate', $data);

        $this->session->set_flashdata('msg_success', 'Berhasil Pengambilan Barang ... !');
        redirect('olshop/status_pengambilan/'.$signature_header);

    }

    public function export_status_pengambilan_barang($signature_header){
        $get_id_ref = $this->model_olshop->get_id_ref($signature_header)->row();
        $id_ref = $get_id_ref->id;
        $generate_code = $get_id_ref->generate_code;

        $query = "
            select 	b.kodeprod_mpm, c.namaprod, /*a.qty_olshop, b.qty1, */sum(a.qty_olshop * b.qty1) as total_qty
            from site.t_olshop_detail a LEFT JOIN
            (
                select a.olshop, a.kodeprod_olshop, a.kodeprod_mpm, a.namaprod_olshop, a.qty1, a.harga_retail, a.namaprod_mpm
                from site.map_olshop_product a
            )b on a.kodeprod_olshop = b.kodeprod_olshop LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
            )c on b.kodeprod_mpm = c.kodeprod
            where a.id_ref = $id_ref
            group by b.kodeprod_mpm
        ";

        $export = $this->db->query($query);
        query_to_csv($export,TRUE,'Pengambilan Barang '.$generate_code.'.csv');

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

    public function email_status_pengambilan_barang($signature_header){
        $get_id_ref = $this->model_olshop->get_id_ref($signature_header)->row();
        $id_ref = $get_id_ref->id;

        $get_data_ambil_barang = $this->model_olshop->get_data_ambil_barang($signature_header)->row();
        $no_barang_diambil = $get_data_ambil_barang->no_pengambilan;

        $generate_csv_pengambilan_barang = $this->model_olshop->generate_csv_pengambilan_barang($signature_header);

        $from = "suffy@muliaputramandiri.com";
        $to = "suffy.mpm@gmail.com";
        $cc = "suffy@muliaputramandiri.com";
        $subject = "MPM Site|Onlineshop - $no_barang_diambil";

        // $message = $this->load->view("retur/email_retur",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message('FYI');
        $this->email->attach('assets/file/olshop/email/'.str_replace('/','_',$no_barang_diambil).'.csv');
        $send = $this->email->send();
        echo $this->email->print_debugger();
        if ($send) {
        
            $this->session->set_flashdata('msg_success', 'send email berhasil. check kembali email anda ... !');
            redirect('olshop/status_pengambilan/'.$signature_header);

        }else{
            
            $this->session->set_flashdata('msg_failed', 'send email failed. info kan IT untuk troubleshooting ... !');
            redirect('olshop/status_pengambilan/'.$signature_header);
        }

    }

    public function status_pengambilan($signature_header){        

        $get_data_ambil_barang = $this->model_olshop->get_data_ambil_barang($signature_header)->row();
        $no_barang_diambil = $get_data_ambil_barang->no_pengambilan;
        $pic = $get_data_ambil_barang->pic;
        $tanggal_pengambilan = $get_data_ambil_barang->tanggal_pengambilan;

        $get_id_ref = $this->model_olshop->get_id_ref($signature_header)->row();
        $id_ref = $get_id_ref->id;
        $olshop = $get_id_ref->olshop;

        $data = [
            'url'           => 'olshop/proses_pengambilan_barang',
            'title'         => 'Onlineshop | status pengambilan barang',
            'no_barang_diambil' => $no_barang_diambil,
            'pic' => $pic,
            'tanggal_pengambilan' => $tanggal_pengambilan,
            'get_label'     => $this->M_menu->get_label(),
            'get_summary'   => $this->model_olshop->get_summary($id_ref, $olshop)
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('olshop/status_pengambilan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function export_pdf($signature_header){
        $this->load->library('mypdf');

        $get_id_ref = $this->model_olshop->get_id_ref($signature_header)->row();
        $id_ref = $get_id_ref->id;
        $olshop = $get_id_ref->olshop;

        $data = [
            'get_summary'   => $this->model_olshop->get_summary($id_ref, $olshop)
        ];

        $generate_pdf = $this->mypdf->generate('olshop/template_pdf_olshop_summary',$data,'export','A4','portrait');

    }

    public function update_modal_invoice()
    {
        $faktur_sds = $this->input->post('faktur_sds'); 
        $tanggal_faktur = $this->input->post('tanggal_faktur');
        $signature_header = $this->input->post('signature_header');
        $nominal = $this->input->post('nominal');
        $id_ref = $this->input->post('id_ref');
        $capture_faktur = $this->input->post('capture_faktur');

        $path = './assets/file/olshop/faktur/';
        $created_at = $this->model_outlet_transaksi->timezone();
            
        //jika belum ada folder, maka create folder
        if (!is_dir($path)) {
            @mkdir($path, 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = $path;
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('capture_faktur')) {
            var_dump($this->upload->display_errors());
            // echo "gagal upload";
            $filename = '';
            // die;
        }else{

        // echo "else";
        // die;

        $data = array('upload_data' => $this->upload->data());
        $file = $data['upload_data']['full_path'];
        chmod($file, 0777);

        //mengambil
        $upload_data = $this->upload->data();
        $filename = $upload_data['file_name'];

        $data_header = [
            'id_ref'        => $id_ref,
            'faktur_sds'    => $faktur_sds,
            'tanggal_faktur'=> $tanggal_faktur,
            'nominal_faktur'=> $nominal,
            'capture_faktur'=> $filename,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id'),
            'updated_at'    => $created_at,
            'updated_by'    => $this->session->userdata('id'),
            'signature'     => $this->session->userdata('id').'-'.md5($created_at),
            'signature_header' => $signature_header
        ];

        // var_dump($data_header);
        // die;

        $insert_history = $this->db->insert('site.t_olshop_history_invoice', $data_header);
        $id_header = $this->db->insert_id();

        // var_dump($insert_history);
        // die;

        $this->session->set_flashdata('msg_success', 'Berhasil Upload data ... !');
        redirect('olshop/history_invoice/'.$signature_header);

       }

        
    }

    public function history_invoice($signature_header = ''){

        if ($signature_header == '') {            
            $params_id_ref = '';
        }else{
            $get_id_ref = $this->model_olshop->get_id_ref($signature_header)->row();
            $id_ref = $get_id_ref->id;
            $params_id_ref = $id_ref;
        }

        $data = [
            'url'           => 'olshop/proses_pengambilan_barang',
            'title'         => 'Onlineshop | history invoice',
            'get_label'     => $this->M_menu->get_label(),
            'get_history_invoice'   => $this->model_olshop->get_history_invoice($params_id_ref)
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('olshop/history_invoice',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function delete_history_invoice($signature){
        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('site.t_olshop_history_invoice', $data);

        $this->session->set_flashdata('msg_success', 'Data deleted ... !');
        redirect('olshop/history_invoice');

    }

    public function penarikan_saldo(){
        $data = [
            'url'           => 'olshop/proses_penarikan_saldo',
            'title'         => 'Onlineshop | penarikan saldo',
            'get_label'     => $this->M_menu->get_label(),
            'get_history_invoice'   => $this->model_olshop->get_history_invoice(),
            'get_history_penarikan_saldo'   => $this->model_olshop->get_history_penarikan_saldo(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('olshop/penarikan_saldo',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_penarikan_saldo(){

        $no_penarikan = $this->model_olshop->generate_code_penarikan();
        $data = [
            "nominal"   => $this->input->post('nominal'),
            "tanggal_penarikan_saldo"   => $this->input->post('tanggalPenarikan'),
            "no_penarikan_saldo"   => $no_penarikan,
            "no_rekening"   => $this->input->post('no_rekening'),
            "pemilik_rekening"   => $this->input->post('pemilik_rekening'),
            "catatan"   => $this->input->post('catatan'),
            "created_at"    => $this->model_outlet_transaksi->timezone(),
            "created_by"    => $this->session->userdata('id'),
            "updated_at"    => $this->model_outlet_transaksi->timezone(),
            "updated_by"    => $this->session->userdata('id'),
        ];

        $this->db->insert('site.t_olshop_penarikan_saldo', $data);
        $id_ref = $this->db->insert_id();

        $request = $this->input->post('options');
        $code = '';
        foreach($request as $kode)
        {
            $get_faktur_sds = $this->db->get_where('site.t_olshop_history_invoice', array(
                'id'    => $kode
            ))->row();

            $detail = [
                "id_ref" => $id_ref,
                "faktur_sds" => $get_faktur_sds->faktur_sds,
            ];

            $this->db->insert('site.t_olshop_penarikan_saldo_detail', $detail);
        }

        $this->session->set_flashdata('msg_success', 'Data tersimpan ... !');
        redirect('olshop/penarikan_saldo/');

    }

    public function update_courier(){

        $data = [
            'courier'   => $this->input->post('courier')
        ];

        $this->db->where('inv_olshop', $this->input->post('inv_olshop_courier'));
        $this->db->update('site.t_olshop_detail', $data);
        redirect('olshop/dashboard');
    }

    public function update_resi(){

        $data = [
            'resi'   => $this->input->post('resi')
        ];

        $this->db->where('inv_olshop', $this->input->post('inv_olshop_resi'));
        $this->db->update('site.t_olshop_detail', $data);
        redirect('olshop/dashboard');
    }

}

