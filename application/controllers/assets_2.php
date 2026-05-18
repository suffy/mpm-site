<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assets_2 extends MY_Controller
{
    function assets_2()
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
        $this->load->model('model_asset');
        $this->load->model('M_menu');
        $this->load->model('model_sales_omzet');
        $this->load->database();

    }

    public function index()
    {
        $this->my_asset();
    }

    function get_data()
    {
        $id = $_POST['id'];
        $data['get_assets_kas'] = $this->model_asset->getAssets_temp_by_voucher($id,1)->row();
        $data['get_assets_jurnal'] = $this->model_asset->getAssets_temp_by_voucher($id,2)->row();
        $data['get_pengajuan'] = $this->model_asset->getPengajuan($id)->row();
        echo json_encode($data);
    }

    function get_list_nopo(){
        $kode = [
            'from' => $_POST['from'],
            'to' => date('Y-m-d'),
        ];
        $data = $this->model_asset->get_list_pengajuan($kode)->result();
        echo json_encode($data);
    }

    function get_list_user(){
        $data = $this->model_asset->getUser()->result();
        echo json_encode($data);
    }

    function get_assets_sds(){

        $kode = [
            'from' => $_POST['from'],
            'to' => $_POST['to'],
            'dt' => $_POST['data_table'],
            'created_at' => $this->model_sales_omzet->timezone2()
        ];

        
        $sds = $this->model_asset->getAssets_sds($kode);
        // var_dump($sds);die;
    }

    function get_assets(){
        $userid = $this->session->userdata('id');
        $data = $_POST['data_table'];
        $data = $this->model_asset->getAssets_temp($userid, $data)->result();
        // var_dump($data);die;
        echo json_encode($data);
    }

    public function view_assets(){
        
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'assets_2/tambah_assets/',
            'url2' => 'assets_2/export_assets/',
            'title' => 'Table Asset',
            'get_label' => $this->M_menu->get_label(),
            'group' => $this->model_asset->getGrupassetcombo(),
            'asset' => $this->model_asset->view_asset()->result()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets_2/view_assets',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    // public function input_assets(){

    //     $from = $this->input->post('from');
    //     $to = $this->input->post('to');
    //     $dt = $this->input->post('data_table');

    //     if ($from > $to) {
    //         $url = base_url().'assets_2/view_assets/';
    //         echo "<script>alert('Tanggal tidak sesuai, Silahkan input tanggal dengan benar !'); window.location.replace('$url');</script>";
    //     }

    //     $kode = [
    //         'nv' => $this->input->post('nv'),
    //         'from' => $from,
    //         'to' => $to,
    //         'dt' => $dt,
    //         'created_at' => $this->model_sales_omzet->timezone2()
    //     ];

    //     $data = [
    //         'url' => 'assets_2/input_assets_hasil/',
    //         'title' => 'Tambah Asset',
    //         'get_label' => $this->M_menu->get_label(),
    //         'assets_sds' => $this->model_asset->getAssets_sds($kode)->result(),
    //         'group' => $this->model_asset->getGrupassetcombo(),
    //         'no_po' => $this->model_asset->getPengajuan(''),
    //         'user'=> $this->model_asset->getUser(),
    //     ];
        
    //     if($dt == 1 ){
    //         $view = 'assets_2/input_assets';
    //     }elseif($dt == 2){
    //         $view = 'assets_2/input_assets_jurnal';
    //     }
        
    //     $this->load->view('template_claim/top_header');
    //     $this->load->view('template_claim/header');
    //     $this->load->view('template_claim/sidebar',$data);
    //     $this->load->view('template_claim/top_content',$data);
    //     $this->load->view("$view",$data);
    //     $this->load->view('template_claim/bottom_content');
    //     $this->load->view('template_claim/footer');
    // }

    public function tambah_assets()
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
            'no_pengajuan' => $this->input->post('nopo'),
            'userid_req' => $this->input->post('user_req'),
            'userid_mutasi' => $this->input->post('user_mutasi'),
            'created_by' => $this->session->userdata('id'),
            'created' => $created_date
        );
        
        $proses = $this->model_asset->input('mpm.asset',$asset);
        
        $mutasi = array(
            'id_asset' =>$proses,
            'userid' => $this->input->post('user_mutasi'),
            'tgl_mutasi' => $created_date,
            'alasan_mutasi' => 'baru',
            'status' => '2',
            'created_by' => $this->session->userdata('id'),
            'created_date' => $created_date
        );
        
        $proses = $this->model_asset->input('mpm.asset_mutasi',$mutasi);

        if ($proses) {
            echo '<script>alert("Data berhasil di simpan);</script>';
            redirect('assets_2/view_assets','refresh');
            
        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('assets_2/view_assets','refresh');
        }
    }

    public function detail_assets(){

        $this->load->library('form_validation');
        $data = [
            'url' => 'assets_2/view_assets/',
            'title' => 'Detail Asset',
            'get_label' => $this->M_menu->get_label(),
            'asset' => $this->model_asset->view_asset($this->uri->segment('3'))->row(),
            'history' => $this->model_asset->history_asset(),
            'user'=> $this->model_asset->getUser(),
            'no_po'=> $this->model_asset->getPengajuan()
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets_2/detail_assets',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function update_assets(){
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
            'no_pengajuan' => $this->input->post('nopo'),
            'nj' => $this->input->post('nj'),
            'np' => $this->input->post('np'),
            'sn' => $this->input->post('sn'),
            'tgljual' => $this->input->post('tj'),
            'deskripsi' => $this->input->post('deskripsi'),
            'upload_faktur' => $filename,
            'modified_by' => $this->session->userdata('id'),
            'modified' => $this->model_sales_omzet->timezone2()
        );

        $proses=$this->model_asset->edit('mpm.asset',$data);

    	if ($proses){
            echo "<script>alert('UPDATE BERHASIL'); </script>";
            redirect(base_url()."assets_2/detail_assets/$id",'refresh');
        } else {
            echo "<script>alert('UPDATE GAGAL'); </script>";
            redirect(base_url()."assets_2/detail_assets/$id",'refresh');
        }
    }

    public function input_mutasi_assets(){

        $id_asset = $this->uri->segment('3');
        $asset = $this->model_asset->view_asset($id_asset)->row();
        $link = base_url()."assets_2/detail_assets/$id_asset";
        // var_dump($id_asset);die;
        if ($asset->sn == ''){
            $message = "INPUT GAGAL ! S/N HARAP DIISI !";
            echo "<script type='text/javascript'>alert('$message');
            window.location = '$link';
            </script>";
        }else{
            $this->load->library('upload'); // Load librari upload        
            $config['upload_path'] = './assets/file/bukti_mutasi/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';    
            $config['max_size']  = '*';
            $config['encrypt_name']	= TRUE;
            $this->upload->initialize($config);
    
            if(!$this->upload->do_upload('file'))
            { 
                $filename = '';
            
            }else{
    
                //kalau berhasil upload file1, lakukan upload file2
    
                $upload_data = $this->upload->data();
                $filename = $upload_data["file_name"];
    
                // echo $filename;
    
                if(!$this->upload->do_upload('file2'))
                { 
                    $filename2 = '';
                    // $error = $this->upload->display_errors();
                    // menampilkan pesan error
                    // print_r($error);
                
                }else{
                    $upload_data = $this->upload->data();
                    $filename2 = $upload_data["file_name"];
    
                    // echo $filename2;
                }
    
                $data = array(
                    'id_asset' => $id_asset,
                    'userid' => $this->input->post('user'),
                    'tgl_mutasi' => $this->input->post('tm'),
                    'bukti_upload' => $filename,
                    'bukti_upload2' => $filename2,
                    'alasan_mutasi' => $this->input->post('am'),
                    'created_by' => $this->session->userdata('id'),
                    'created_date' => $this->model_sales_omzet->timezone2()
                );
                
                $proses = $this->model_asset->input('mpm.asset_mutasi',$data);
                
                if ($proses){
                    echo "<script>alert('MUTASI BERHASIL'); </script>";
                    redirect(base_url()."assets_2/detail_assets/$id_asset",'refresh');
                } else {
                    echo "<script>alert('MUTASI GAGAL'); </script>";
                    redirect(base_url()."assets_2/detail_assets/$id_asset",'refresh');
                }
            }
        }
    }

    // public function edit_mutasi_assets(){
    //     $id_assets_mutasi = $this->input->post('id_assets_mutasi');
    //     $proses = $this->model_asset->getAsset_mutasi($id_assets_mutasi)->row();
    //     $id_assets = $proses->id_asset;
    //     // var_dump($id_assets_mutasi);die;

    //     $this->load->library('upload'); // Load librari upload        
    //     $config['upload_path'] = './assets/file/bukti_mutasi/';
    //     $config['allowed_types'] = 'gif|jpg|png';    
    //     $config['max_size']  = '2048';
    //     $config['encrypt_name']	= TRUE;
    //     $this->upload->initialize($config);

    //     if(!$this->upload->do_upload('file'))
    //     { 
    //         $filename = $proses->bukti_upload;
            
    //     }else{

    //         //kalau berhasil upload file1, lakukan upload file2

    //         $upload_data = $this->upload->data();
    //         $filename = $upload_data["file_name"];
    //     }

    //     if(!$this->upload->do_upload('file2'))
    //     { 
    //         $filename2 = $proses->bukti_upload2;
    //         // $error = $this->upload->display_errors();
    //         // menampilkan pesan error
    //         // print_r($error);
            
    //         }else{
    //             $upload_data = $this->upload->data();
    //             $filename2 = $upload_data["file_name"];

    //             // echo $filename2;
    //         }
        
    //     $data = array(
    //         'id' => $proses->id,
    //         'userid' => $this->input->post('userid'),
    //         'tgl_mutasi' => $this->input->post('tm'),
    //         'alasan_mutasi' => $this->input->post('am'),
    //         'bukti_upload' => $filename,
    //         'bukti_upload2' => $filename2,
    //         'modified_by' => $this->session->userdata('id'),
    //         'modified_date' => $this->model_sales_omzet->timezone2()
    //     );

    //     $proses =$this->model_asset->edit('mpm.asset_mutasi',$data);
    //     if ($proses){
    //         echo "<script type='text/javascript'>alert('UPDATE BERHASIL');
    //             window.location = 'http://site.muliaputramandiri.com/cisk/assets_2/detail_assets/$id_assets';
    //             </script>";
    //     } else {
    //         echo "<script type='text/javascript'>alert('UPDATE GAGAL');
    //             window.location = 'http://site.muliaputramandiri.com/cisk/assets_2/detail_assets/$id_assets';
    //             </script>";
    //     }
    // }

    public function approv_mutasi_assets(){
        $id_approv = $this->input->post('id_approv');
        $data = array(
            'id' => $id_approv,
            'alasan_approve' => $this->input->post('approv'),
            'status' => '1'
        );
        
        $this->model_asset->edit('mpm.asset_mutasi',$data);

        $proses = $this->model_asset->getAsset_mutasi($id_approv)->row();
        $id_assets = $proses->id_asset;

        $data2 = array(
            'id' => $id_assets,
            'userid_mutasi' => '0'
        );

        $this->model_asset->edit('mpm.asset',$data2);
        redirect("assets_2/detail_assets/$id_assets",'refresh');
    }

    public function delete_mutasi(){
        $id_asset = $this->uri->segment('3');
        $data = array(
            'id' => $this->uri->segment('4'),
        );
        
        $this->model_asset->delete('mpm.asset_mutasi',$data);
        redirect("assets_2/detail_assets/$id_asset",'refresh');

    }

    public function delete_assets(){
        $data = [
            'id' => $this->uri->segment('3'),
            'deleted' => '1'
        ];

        $this->model_asset->edit('mpm.asset',$data);
        redirect('assets_2/view_assets','refresh');
    }

    public function export_assets(){

        $from  =  $this->input->post('from');
        $to =  $this->input->post('to');

        $query="
        select 	a.grupid, a.kode, a.namabarang, a.jumlah, a.untuk, a.tglperol, a.gol, a.np, a.wilayah, a.koreksi, a.tglkoreksi, 
                a.nj, a.tgljual, a.deskripsi, a.upload_faktur, a.sn, a.no_pengajuan
        from mpm.asset a 
        where a.tglperol between '$from' and '$to'
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Assets.csv");

    }

    // ==============================Pengajuan Asset========================= //
    public function pengajuan_assets(){
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets_2/input_barang_pengajuan/',
            'url2'       => 'assets_2/confirm_pengajuan/',
            'title'     => 'Pengajuan Assets',
            'get_label' => $this->M_menu->get_label(),
            'proses'    => $this->model_asset->showbarang()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets_2/pengajuan_assets',$data);
        $this->load->view('assets_2/check_pengajuan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function input_barang_pengajuan(){
        $this->model_asset->input_barang_pengajuan();
    }

    public function delete_barang_pengajuan(){

        $this->model_asset->delete_barang_pengajuan();
    }

    public function confirm_pengajuan(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id = $this->session->userdata('id');
        $query = $this->db->query("select * from db_temp.t_temp_pengajuan_asset_temp where created_by= $id");
        if($query->num_rows()==0){
            redirect('assets_2/pengajuan_assets/','refresh');
        }
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets_2/simpan_pengajuan/',
            'title'     => 'Pengajuan Assets',
            'get_label' => $this->M_menu->get_label(),
            'user'=>$this->model_asset->getUser()
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets_2/confirm_pengajuan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    // public function input_pengajuan(){
    //     $this->model_asset->pengajuan_asset();
    // }
    
    public function simpan_pengajuan(){
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/file/bukti_permintaan/';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['encrypt_name']	= TRUE;
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file'))
        { 
            $error = $this->upload->display_errors();
            // menampilkan pesan error
            print_r($error);
        
        }else{
            $upload_data = $this->upload->data();
            $filename = $upload_data["file_name"];
            $data = array(
                'upload'  => $filename
            );
        $this->model_asset->save_pengajuan($data);
        }
    }

    public function view_pengajuan(){
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets_2/input_pengajuan/',
            'title'     => 'Pengajuan Assets',
            'get_label' => $this->M_menu->get_label(),
            'proses' => $this->model_asset->view_pengajuan()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('assets_2/view_pengajuan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
    
    public function pengajuan_pdf (){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $no_po = $this->uri->segment(3);
        $data['no'] = substr($no_po,0,3);
        $data['bln'] = substr($no_po,6,2);
        $data['thn'] = substr($no_po,8,4);
        $this->load->library('dompdf_gen');
        $data['toko'] = $this->db->query("select * from db_temp.t_temp_pengajuan_asset where no_po = '$no_po'")->result();
        $data['barang'] = $this->db->query("select * from db_temp.t_temp_pengajuan_asset_detail where no_po = '$no_po'")->result();
        $data['total'] = $this->db->query("select sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total
        FROM db_temp.t_temp_pengajuan_asset_detail where no_po = '$no_po'")->result();

        $this->load->view('assets_2/report_pengajuan', $data);
        $paper_size = 'A4';
        $orientation = 'potrait';
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size. $orientation);

        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("Report_pegajuan_$no_po.pdf", array('attachment'=>0));

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
        
        $data = "kode asset : $kode\nnama asset : $namabarang\nPIC : $untuk\nS/N : $sn\nLihat Detail : ".base_url()."assets_2/detail_assets/".$id."";


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

    public function my_asset(){
        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'assets_2/my_asset/',
            'title'         => 'My Asset',
            'get_label'     => $this->M_menu->get_label(),
            'asset'         => $this->model_asset->my_asset(),
            'konfirmasi'    => $this->model_asset->konfirmasi_asset()
        ];
        // echo "<pre>";
        // var_dump($this->model_asset->my_asset());
        // echo "</spre>";
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('assets_2/my_asset',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function konfirmasi_asset(){
        $data = array(
            'id_asset' => $this->uri->segment('3'),
            'id_mutasi' => $this->uri->segment('4'),
            'alasan_approv' => $this->input->post('approv')
        );

        $this->model_asset->save_konfirmasi_asset($data);
        redirect("assets_2/my_asset/",'refresh');
    }
}
?>