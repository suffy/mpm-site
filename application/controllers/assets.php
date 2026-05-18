<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assets extends MY_Controller
{
    function assets()
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
        $this->load->model('M_assets');
        $this->load->model('M_menu');
        $this->load->model('model_sales_omzet');
        $this->load->model('model_rpd');
        $this->load->model('M_purchase_requistion');
        $this->load->database();

    }

    public function get_data()
    {
        $id = $_GET['id'];
        // $data['get_nopr'] = $this->M_assets->getNo_pr($id)->row();
        $data['get_pr'] = $this->M_assets->get_pr($id);
        echo json_encode($data);
    }

    public function my_asset()
    {
        $userid = $this->session->userdata('id');
        $data = [
            'url' => 'assets/purchase_requistion_simpan',
            'title' => 'My Asset',
            'get_label' => $this->M_menu->get_label(),
            'asset' => $this->M_assets->my_asset(),
            'pr' => $this->M_purchase_requistion->purchase_requistion_asset($userid)->result(),
            'konfirmasi' => $this->M_assets->konfirmasi_asset()
        ];
        // echo "<pre>";
        // var_dump($this->M_assets->my_asset());
        // echo "</spre>";
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets/my_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function purchase_requistion_simpan()
    {
        $no_pr = $this->M_assets->generate_no_pr();

        $data = [
            // 'tanggal' => $this->input->post('tanggal'),
            'no_pr' => $no_pr,
            'divisi' => $this->input->post('divisi'),
            'barang' => $this->input->post('barang'),
            'keterangan' => $this->input->post('keterangan'),
            'created_by' => $this->session->userdata('id'),
            'created_at' => $this->model_sales_omzet->timezone2(),
        ];

        $this->M_assets->simpan('site.t_asset_purchase_requistion',$data);

        $this->email_pr($no_pr);
    }

    public function konfirmasi_asset()
    {
        $id = $this->session->userdata('id');
        $id_asset = $this->uri->segment('3');
        $id_mutasi = $this->uri->segment('4');

        $sql = "
            update mpm.asset_mutasi a
            set a.status = '2'
            where id = $id_mutasi
        ";

        $update_mutasi = $this->db->query($sql);

        $sql2 = "
                update mpm.asset a
                set a.userid_mutasi = (
                    SELECT userid FROM mpm.asset_mutasi
                    WHERE id = $id_mutasi and userid = $id
                )
                where a.id = $id_asset
            ";

        $update = $this->db->query($sql2);

        redirect("assets/my_asset/",'refresh');
    }

    // ==================================================== purchase asset ============================================== //
    public function purchase_asset()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'assets/purchase_asset_input_barang/',
            'title' => 'Purchase Assets',
            'get_label' => $this->M_menu->get_label(),
            'data_purchase_asset' => $this->M_assets->data_purchase_asset()->result()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/purchase_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function purchase_asset_input_barang()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'assets/purchase_asset_input_barang_temp/',
            'url2' => 'assets/konfirm_purchase_asset/',
            'title' => 'Purchase Assets',
            'get_label' => $this->M_menu->get_label(),
            'barang' => $this->M_assets->showbarang()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets/input_purchase_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function purchase_asset_input_barang_temp()
    {
        $tax = $this->input->post('tax');
        $jumlah = $this->input->post('jb');
        $harga =$this->input->post('harga');
        $sub_harga =  $jumlah*$harga;
        $sub_tax = $sub_harga*$tax/100;

        $data = [
            'nama_barang' => $this->input->post('nb'),
            'tipe' => $this->input->post('tipe'),
            'jumlah' => $jumlah,
            'harga' => $harga,
            'tax' => $sub_tax,
            'created_by' => $this->session->userdata('id'),
            'created_at' => $this->model_sales_omzet->timezone2(),
        ];

        $this->M_assets->simpan('site.t_asset_purchase_asset_temp',$data);

        redirect('assets/purchase_asset_input_barang/');
    }

    public function purchase_asset_delete_barang_temp()
    {
        $id = $this->uri->segment('3');
        $this->M_assets->delete('site.t_asset_purchase_asset_temp',$id);
        redirect('assets/purchase_asset_input_barang/');

    }

    public function konfirm_purchase_asset()
    {
        $id = $this->session->userdata('id');
        $query = $this->db->query("select * from site.t_asset_purchase_asset_temp where created_by= $id");
        if($query->num_rows()==0){
            redirect('assets/purchase_asset/','refresh');
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'assets/purchase_asset_simpan/',
            'title' => 'Konfirmasi Assets',
            'get_label' => $this->M_menu->get_label(),
            'no_pr' =>$this->M_assets->getNo_pr()->result(),
            // 'user' =>$this->M_assets->getUser()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets/konfirm_purchase_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function purchase_asset_simpan()
    {
        $userid = $this->session->userdata('id');
        $no_po = $this->M_assets->generate_no_pof();
        $hasil = $this->db->query("select * from site.t_asset_purchase_asset where no_po ='$no_po'");
        $created_at= $this->model_sales_omzet->timezone2();

        if ($hasil->num_rows() > 0)
        {
            echo "Input Gagal, Nomer PO sudah digunakan silahkan coba lagi !!";

            } else {
                // $this->load->library('upload'); // Load librari upload
                // $config['upload_path'] = './assets/file/bukti_permintaan/';
                // $config['allowed_types'] = 'gif|jpg|png';
                // $config['max_size']  = '2048';
                // $config['encrypt_name']	= TRUE;
                // $this->upload->initialize($config);

                // // if(!$this->upload->do_upload('file'))
                // // {
                // //     $error = $this->upload->display_errors();
                // //     // menampilkan pesan error
                // //     print_r($error);

                // // }else{
                //     $upload_data = $this->upload->data();
                //     $filename = $upload_data["file_name"];
                // // }

            $data = [
                'no_po' => $no_po,
                'no_pr' => $this->input->post('no_pr'),
                'nama_toko' => $this->input->post('nt'),
                'alamat' => $this->input->post('alamat'),
                'telp' => $this->input->post('telp'),
                'fax' => $this->input->post('fax'),
                'attn' => $this->input->post('attn'),
                'tgl_po' => $this->input->post('tgl'),
                'user_req' => $this->input->post('user_req'),
                // 'upload_req' => $filename,
                'created_at' => $created_at,
                'created_by' => $userid,
            ];
            $this->M_assets->simpan('site.t_asset_purchase_asset',$data);

            $this->M_assets->simpan_asset_detail($data);

        }
        redirect('assets/purchase_asset/');
    }

    public function pengajuan_pdf ()
    {
        $pdf = $this->uri->segment(3);
        $no_po = $this->uri->segment(4);
        $this->load->library('dompdf_gen');

        $data = [
            'no' => substr($no_po,0,3),
            'bln' => substr($no_po,6,2),
            'thn' => substr($no_po,8,4),
            'toko' => $this->db->query("select * from site.t_asset_purchase_asset where no_po = '$no_po'")->result(),
            'barang' => $this->db->query("select * from site.t_asset_purchase_asset_detail where no_po = '$no_po'")->result(),
            'total' => $this->db->query("select sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total
            FROM site.t_asset_purchase_asset_detail where no_po = '$no_po'")->result(),
        ];

        if ($pdf == '1') {
            # code...
            $this->load->view('assets/report_pengajuan_1', $data);
        } else {
            # code...
            $this->load->view('assets/report_pengajuan_2', $data);
        }
        
        $paper_size = 'A4';
        $orientation = 'potrait';
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size. $orientation);

        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("Report_pegajuan_$no_po.pdf", array('attachment'=>0));
    }

    // ========================================================================================================================
    // =================================================== Penyerahan Asset ===================================================
    public function penyerahan_asset()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'assets/simpan_penyerahan_asset/',
            'title' => 'Penyerahan Assets',
            'get_label' => $this->M_menu->get_label(),
            'penyerahan_asset' => $this->M_assets->get_penyerahan_asset(),
            'pr' => $this->M_assets->get_pr(),
            'user' => $this->M_assets->getUser()->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/penyerahan_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function simpan_penyerahan_asset()
    {
        $no_po = $this->input->post('no_po');
        $data = [
            'no_pr' => $this->input->post('no_pr'),
            'tgl_pengiriman' => $this->input->post('tanggal'),
            'ekspedisi' => $this->input->post('ekspedisi'),
            'resi' => $this->input->post('resi'),
            'userid_penerima' => $this->input->post('penerima'),
            'harga' => $this->input->post('harga'),
            'status' => $this->input->post('status'),
            'created_by' => $this->session->userdata('id'),
            'created_at' => $this->model_sales_omzet->timezone2(),
        ];

        if ($no_po === 'automatic') {
            $data['no_po'] = $this->M_assets->generate_no_pof();
            // var_dump($data['no_po']);die;
            // $this->db->select('*');
            // $this->db->from('site.t_asset_purchase_asset');
            // $this->db->limit('1');
            // $params_nopo = '';
        }else{
            $data['no_po'] = $this->input->post('no_po');
        }        
        
        // var_dump($data['no_po']);
        // die;

        $purchase_header = [
            'no_po' => $data['no_po'],
            'nama_toko' => '-',
            'alamat' => '-',
            'telp' => '-',
            'fax' => '-',
            'attn' => '-',
            'tgl_po' => '-',
            'user_req' => '-',
            'created_by' => $this->session->userdata('id'),
            'created_at' => $this->model_sales_omzet->timezone2(),
        ];

        $purchase_detail = [
            'no_po' => $data['no_po'],
            'nama_barang' => '-',
            'tipe' => '-',
            'jumlah' => '-',
            'harga' => '-',
            'sub_harga' => '-',
            'tax' => '-',
            'created_by' => $this->session->userdata('id'),
            'created_at' => $this->model_sales_omzet->timezone2(),
        ];

        $this->M_assets->simpan('site.t_asset_penyerahan_asset',$data);
        $this->M_assets->simpan('site.t_asset_purchase_asset',$purchase_header);
        $this->M_assets->simpan('site.t_asset_purchase_asset_detail',$purchase_detail);
        redirect('assets/penyerahan_asset');
    }
    // ========================================================================================================================
    // =================================================== Input Asset ========================================================
    public function view_asset()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'assets/tambah_asset/',
            'url2' => 'assets/export_asset/',
            'title' => 'Table Asset',
            'get_label' => $this->M_menu->get_label(),
            'group' => $this->M_assets->getGrupassetcombo(),
            'pr' => $this->M_assets->get_pr(),
            'asset' => $this->M_assets->view_asset()->result()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets/view_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function tambah_asset()
    {
        $kode = $this->input->post('nick_voucher');
        if ($kode == '') {
            $kodex = $this->input->post('no_voucher');
        } else {
            $kodex = $kode;
        }

        $created_date = $this->model_sales_omzet->timezone2();

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/file/faktur_asset/';
        $config['allowed_types'] = 'pdf';
        $config['max_size']  = '*';
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file'))
        {
            $filename = '';
            // $error = $this->upload->display_errors();
            // menampilkan pesan error
            // print_r($error);
        }else{
            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];
        }

        $asset = array(
            'voucher' => $this->input->post('no_voucher'),
            'kode' => $kodex,
            'nourut' => $this->input->post('nourut'),
            'grupid' => $this->input->post('grup'),
            'namabarang' => $this->input->post('nama_barang'),
            'jumlah' => $this->input->post('jum_barang'),
            'untuk' => $this->input->post('keperluan'),
            'tglperol' => $this->input->post('tgl_payroll'),
            'gol' => $this->input->post('gol'),
            'np' => $this->input->post('nilai_perolehan'),
            'sn' => $this->input->post('sn'),
            'upload_faktur' => $filename,
            'no_po' => $this->input->post('no_po'),
            'no_pr' => $this->input->post('no_pr'),
            'created_by' => $this->session->userdata('id'),
            'created' => $created_date
        );

        // var_dump($asset);die;

        $proses = $this->M_assets->simpan('mpm.asset',$asset);

        if ($proses) {
            echo '<script>alert("Data berhasil di simpan);</script>';
            redirect('assets/view_asset','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('assets/view_asset','refresh');
        }
    }
    
    public function detail_asset(){
        $data = [
            'url' => 'assets/view_asset/',
            'title' => 'Detail Asset',
            'get_label' => $this->M_menu->get_label(),
            'asset' => $this->M_assets->view_asset($this->uri->segment('3'))->row(),
            'history' => $this->M_assets->history_asset(),
            'pr' => $this->M_assets->get_pr(),
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets/detail_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function update_asset(){
        $id = $this->uri->segment(3);
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/file/faktur_asset/';
        $config['allowed_types'] = 'pdf';    
        $config['max_size']  = '*';
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file'))
        { 
            $this->db->select('*');
            $this->db->where('id', $id);
            $proses =  $this->db->get('mpm.asset')->row();
            $filename = $proses->upload_faktur;
            // $error = $this->upload->display_errors();
            // menampilkan pesan error
            // print_r($error);
        }else{
            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];
        }

        $data = array(
            'id' => $id,    
            'no_po' => $this->input->post('nopo'),
            'no_pr' => $this->input->post('no_pr'),
            'nj' => $this->input->post('nj'),
            'np' => $this->input->post('np'),
            'sn' => $this->input->post('sn'),
            'tgljual' => $this->input->post('tj'),
            'deskripsi' => $this->input->post('deskripsi'),
            'upload_faktur' => $filename,
            'modified_by' => $this->session->userdata('id'),
            'modified' => $this->model_sales_omzet->timezone2()
        );

        $proses=$this->M_assets->edit('mpm.asset',$data);

    	if ($proses){
            echo "<script>alert('UPDATE BERHASIL'); </script>";
            redirect(base_url()."assets/detail_asset/$id",'refresh');
        } else {
            echo "<script>alert('UPDATE GAGAL'); </script>";
            redirect(base_url()."assets/detail_asset/$id",'refresh');
        }
    }

    public function delete_asset(){
        $id = $this->uri->segment('3');

        $this->db->set('deleted', '1');
        $this->db->where('id', $id);
        $this->db->update('mpm.asset'); 
        redirect('assets/view_asset','refresh');
    }

    public function export_asset(){

        $from  =  $this->input->post('from');
        $to =  $this->input->post('to');

        $query="
        select 	a.grupid, a.kode, a.namabarang, a.jumlah, a.untuk, a.tglperol, a.gol, a.np, a.wilayah, a.koreksi, a.tglkoreksi, 
                a.nj, a.tgljual, a.deskripsi, a.upload_faktur, a.sn, a.no_po, a.no_pr
        from mpm.asset a 
        where a.tglperol between '$from' and '$to'
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Assets.csv");

    }

    public function qrcode(){
        //mencari uri segment

        $id = $this->uri->segment(3);
        //echo "id : ".$id;

        //query mencari 'kode, untuk, status' dari userid
        $this->db->where('id = '.$id);
        $query = $this->db->get('mpm.asset');
        foreach ($query->result() as $row) {
            $kode = $row->kode;
            $namabarang = $row->namabarang;
            $untuk = $row->untuk;
            $sn = $row->sn;
        //echo "supplier : ".$supplier;
        }


        $this->load->library('ci_qr_code');
        $this->config->load('qr_code');
        $qr_code_config = array(); 
        $qr_code_config['cacheable']  = $this->config->item('cacheable');
        $qr_code_config['cachedir']   = $this->config->item('cachedir');
        $qr_code_config['imagedir']   = $this->config->item('imagedir');
        $qr_code_config['errorlog']   = $this->config->item('errorlog');
        $qr_code_config['ciqrcodelib']  = $this->config->item('ciqrcodelib');
        $qr_code_config['quality']    = $this->config->item('quality');
        $qr_code_config['size']     = $this->config->item('size');
        $qr_code_config['black']    = $this->config->item('black');
        $qr_code_config['white']    = $this->config->item('white');

        $this->ci_qr_code->initialize($qr_code_config);

        $image_name = 'qr_code_test.png';

        //$params['data'] = "kode : ".br(1).base_url()."All_assets_2/detail_assets_2/".$id;
        
        $data = "kode asset : $kode\nnama asset : $namabarang\nPIC : $untuk\nS/N : $sn\nLihat Detail : ".base_url()."assets/detail_asset/".$id."";


        $params['data'] = $data;
        

        $params['level'] = "B";
        $params['size'] = "5";

        if($this->input->post('display_format') == 'image')
        {

            $params['savename'] = FCPATH.$qr_code_config['imagedir'].$image_name;
            $this->ci_qr_code->generate($params); 
            $this->data['qr_code_image_url'] = base_url().$qr_code_config['imagedir'].$image_name;
            // Display the QR Code here on browser uncomment the below line
            //echo '<img src="'.base_url().$qr_code_config['imagedir'].$image_name.'" />'; 
            $this->load->view('qr_code', $this->data); 
        }
        else
        {
            header("Content-Type: image/png"); 
            $this->ci_qr_code->generate($params);
        } 
    }
    // ========================================================================================================================

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
        $url = base_url().'assets/my_asset';
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

        $message = $this->load->view("assets/email_pr",$detail,TRUE);

        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        // $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/rpd/'.$filename_pdf.'.pdf');
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