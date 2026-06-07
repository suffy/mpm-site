<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_retur extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/kalimantan','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_retur'));

        
        $id = $this->session->userdata('id');
        // if ($id != 547 && $id != 297) {
        //     $link = base_url('management_office');
        //     echo "
        //     <script>
        //     alert('Maaf, Kami sedang melakukan maintenance.'); 
        //     window.location = '$link';
        //     </script>";
        // }
    }
    // function management_retur()
    // {
    //     $logged_in= $this->session->userdata('logged_in');
    //     if(!isset($logged_in) || $logged_in != TRUE)
    //     {
    //         redirect('login_sistem/kalimantan','refresh');
    //     }
    //     set_time_limit(0);
    //     $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    //     $this->load->helper(array('url', 'csv'));
    //     $this->load->model(array('model_outlet_transaksi','model_management_retur'));

        
    //     $id = $this->session->userdata('id');
    //     // if ($id != 547 && $id != 297) {
    //     //     $link = base_url('management_office');
    //     //     echo "
    //     //     <script>
    //     //     alert('Maaf, Kami sedang melakukan maintenance.'); 
    //     //     window.location = '$link';
    //     //     </script>";
    //     // }
    // }

    // function navbar($data){
    //     if ($this->session->userdata('level') === '4') { // jika dp
    //         $this->load->view('management_office/top_header_dp', $data);
    //     }elseif ($this->session->userdata('level') === '3') { // jika principal
    //         $this->load->view('management_office/top_header_principal', $data);
    //     }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales 
    //         $this->load->view('management_office/top_header_principal_nosales', $data);
    //     }elseif ($this->session->userdata('level') === "3b") { // jika principal hanya raw data, claim, rpd 
    //         $this->load->view('management_office/top_header_principal_rawdata', $data);
    //     }elseif ($this->session->userdata('level') === "3c") { // jika principal raw_data dan retur dan rpd = RSPH = ghozali yoseph sudarsono
    //         $this->load->view('management_office/top_header_principal_rawdata_retur', $data);
    //     }elseif ($this->session->userdata('level') === "3d") { // jika principal rpd
    //         $this->load->view('management_office/top_header_principal_rpd', $data);
    //     }elseif ($this->session->userdata('level') === '5') { // jika dp mpi
    //         $this->load->view('management_office/top_header_dp_mpi', $data);
    //     }else{
    //         $this->load->view('management_office/top_header', $data);
    //     }
    // }
    
    function index()
    {
        $this->dashboard();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function dashboard(){

        $userid = $this->input->post('branch');
        $data = [
            'title'                 => 'Nota Retur',
            'url'                   => 'management_retur/dashboard',
            'url_import'            => 'management_retur/import',
            'url_export'            => 'management_retur/export',
            'url_data_retur'        => 'management_retur/data_retur',
            'url_coretax'            => 'management_retur/import_coretax',
            'userid'                => $userid,
            'get_retur'             => $this->model_management_retur->get_retur($userid),
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/dashboard', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/dashboard', $data);

    }

    public function export(){
        $userid = $this->input->post('userid');
        $supp = $this->input->post('supp');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $update = $this->input->post('update');
        // echo "update : ".$update;
        // echo "bulan : ".$from;
        // echo "tahun : ".$to;
        // echo "userid : ".$userid;
        // echo "supp : ".$supp;

        // die;

        if ($update == 'update_ref') {

            $get_customerid = $this->management_retur->get_customerid($userid);

            if (!$get_customerid->num_rows() > 0) {
                $this->session->set_flashdata("pesan", "Customer tidak ditemukan!! Silakan hubungi IT");
                redirect('management_retur/dashboard');
                die;
            }

            $customerid = $get_customerid->row()->customerid;

            $update = $this->model_management_retur->update_trans_ref_by_tglbuat_userid($customerid,$from,$to,$userid);

            if ($update == 1) {
                $this->session->set_flashdata("pesan_success", "Update Ref Success");
            } else {
                $this->session->set_flashdata("pesan_success", "Update Ref Gagal");
            }
            redirect('management_retur/dashboard/');

        }elseif ($update == 'update_nodo_beli (nota retur)') {
            // update nodo beli
            $bulan_from = substr($from, 5, 2);
            $tahun_from = substr($from, 0, 4);
            $bulan_to = substr($to, 5, 2);
            $tahun_to = substr($to, 0, 4);

            if ($bulan_from != $bulan_to && $tahun_from != $tahun_to) {
                $this->session->set_flashdata("pesan", "periode from dan to berbeda");
                redirect('management_retur/dashboard');
            }
            $iduser = $this->session->userdata('id');
            $this->load->model('model_management_inventory');
            $romawi = $this->model_management_inventory->getRomawi($bulan_from);
            
            // proses update nodo_beli
            $this->model_management_retur->update_nodo_beli($supp,$from,$to,$iduser,$romawi);
            $this->session->set_flashdata("pesan_success", "Update Nodo Beli Berhasil");
            redirect('management_retur/dashboard/');
        } else {
            // export excel
            $this->model_management_retur->export_retur_dashboard($userid,$supp, $from, $to);
        }
    }

    public function detail_nota_retur($id){

        $data = [
            'title'             => 'Detail Nota Retur',
            'url'               => 'management_retur/update_detail_nota_retur',
            'url_update'        => 'management_retur/update_detail_produk_nota_retur',
            'get_retur_by_id'   => $this->model_management_retur->get_retur_by_id($id),
            'id'                => $id
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/detail_nota_retur', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/detail_nota_retur', $data);

    }

    public function update_detail_nota_retur(){
        $data = [
            'nodo'          => $this->input->post('nodo'),
            'nodo_beli'     => $this->input->post('nodo_beli'),
            'noseri'        => $this->input->post('noseri'),
            'noseri_beli'   => $this->input->post('noseri_beli'),
            'nopo'          => $this->input->post('nopo'),
            'tgldo_beli'    => $this->input->post('tgldo_beli'),
            'tgldo'         => $this->input->post('tgldo'),
            'tglbuat'       => $this->input->post('tglbuat'),
            'tgl_beli'      => $this->input->post('tgl_beli'),
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('mpm.trans', $data);

        $data2 = [
            'kodeprod'      => $this->input->post('kodeprod'),
            'banyak'        => $this->input->post('banyak'),
            'harga'         => $this->input->post('harga'),
            'harga_beli'    => $this->input->post('harga_beli'),
            'diskon'        => $this->input->post('diskon'),
            'diskon_beli'   => $this->input->post('diskon_beli'),
        ];

        $jum_row = count($data2['kodeprod']);

        for ($i=0; $i < $jum_row ; $i++) {
            $update = array(
                'banyak'        => $data2['banyak'][$i],
                'harga'         => $data2['harga'][$i],
                'harga_beli'    => $data2['harga_beli'][$i],
                'diskon'        => $data2['diskon'][$i],
                'diskon_beli'   => $data2['diskon_beli'][$i],
            );

            $this->db->where('id_ref', $this->input->post('id'));
            $this->db->where('kodeprod', $data2['kodeprod'][$i]);
            $this->db->update('mpm.trans_detail', $update);
        }

        $this->session->set_flashdata("pesan_success", "Update Success");
        redirect('management_retur/detail_nota_retur/'.$this->input->post('id'), 'refresh');
    }


    public function delete_nota_retur($id){
        // echo "id : ".$id;

        $data = [
            "deleted"   => 1,
        ];

        $this->db->where("id", $id);
        $this->db->update("mpm.trans", $data);
        redirect("management_retur/dashboard");
    }

    public function master_dbsls(){
        $data = [
            'title'             => 'Master DBSLS',
            'get_master_dbsls'  => $this->model_management_retur->get_master_dbsls('','','100'),
            'url'               => 'management_retur/import_dbsls'
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/content', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/content', $data);
    }

    public function export_master_dbsls(){
        $query="
        select 	a.customerid, a.tanggal, a.productid, a.nama_customer, a.nama_product, a.brandid,
                a.nama_brand, a.ref, a.no_seri_pajak, replace(REPLACE(a.no_seri_pajak,'.',''),'-','') as no_seri_pajak_murni, a.qty_kecil, a.retur, a.beli, a.jual, a.disc_cabang, a.disc_beli
        from management_retur.master_dbsls a
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export / Template Master DBSLS.csv');
    }

    public function truncate_master_dbsls(){
        $this->db->query("truncate management_retur.master_dbsls");
        redirect('management_retur/master_dbsls');
    }

    public function import_dbsls(){
        if (!is_dir('./assets/uploads/management_retur/')) {
            @mkdir('./assets/uploads/management_retur/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/management_retur/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file'))
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_retur/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_retur/master_dbsls','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());


            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $customerid = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    // $tanggal = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tanggal = gmdate("d-m-Y", ($worksheet->getCellByColumnAndRow(1, $row)->getValue() - 25569) * 86400);

                    $productid = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_customer = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $nama_product = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $brandid = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $nama_brand = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $ref = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $no_seri_pajak = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $qty_kecil = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $retur = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $beli = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $jual = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $disc_cabang = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $disc_beli = $worksheet->getCellByColumnAndRow(14, $row)->getValue();

                    if(strlen("$productid") == '5')
                    {
                        $productidx = '0'.$productid;
                    }else{
                        $productidx = $productid;
                    }

                    $no_seri_pajakx = substr($no_seri_pajak,0,3).'.'.substr($no_seri_pajak,3,3).'-'.substr($no_seri_pajak,6,2).'.'.substr($no_seri_pajak,8,10);

                    $data = [
                        'customerid'   => $customerid,
                        'tanggal'      => date('Y-m-d',strtotime($tanggal)),
                        // 'tanggal'      => $tanggal,
                        'productid'    => $productidx,
                        'nama_customer'=> $nama_customer,
                        'nama_product' => $nama_product,
                        'brandid'      => $brandid,
                        'nama_brand'   => $nama_brand,
                        'ref'          => $ref,
                        'no_seri_pajak'=> $no_seri_pajakx,
                        'qty_kecil'    => $qty_kecil,
                        'retur'        => ($retur) ? $retur : 0,
                        'beli'         => $beli,
                        'jual'         => $jual,
                        'disc_cabang'  => $disc_cabang,
                        'disc_beli'    => ($disc_beli) ? $disc_beli : 0,
                        'signature'    => $signature,
                        'created_at'   => $created_at,
                        'created_by'   => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_retur.master_dbsls',$data);
                }
            }
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        redirect('management_retur/master_dbsls');

    }

    public function ajuan_retur()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');

        if (empty($from) || empty($to)) {
            $from = date('Y-m-d');
            $to = date('Y-m-d');
        }

        $data = [
            'title'             => 'Ajuan Retur',
            'get_ajuan_retur'   => $this->model_management_retur->get_ajuan_retur($from, $to),
            'url'               => "management_retur/join_ajuan/",
            'url_search'        => '',
            'from'              => $from,
            'to'                => $to
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/ajuan_retur', $data);
        // $this->load->view('kalimantan/footer');
        $this->render('management_retur/ajuan_retur', $data);
    }

    // public function join_ajuan($versi = '0'){
    //     $id = $this->input->post('options');
    //     // var_dump($id);

    //     $code = '';
    //     foreach($id as $idx)
    //     {
    //         $code.=",".$idx;

    //         // get signature dari salah satu id
    //         if ($versi == 2) {
    //             $signature = $this->model_management_retur->get_signature_by_id($idx, $versi)->row()->signature;
    //         } else {
    //             $signature = $this->model_management_retur->get_signature_by_id($idx, $versi)->row()->signature;
    //         }


    //     }

    //     $id_join = preg_replace('/,/', '', $code,1) ;

    //     $data = [
    //         'title'                     => 'Comparing LPK VS Ajuan Retur DP',
    //         'get_product_ajuan_retur'   => $this->model_management_retur->get_product_ajuan_retur($id_join, 1, $signature, $versi),
    //         'url'                       => 'management_retur/update_qty_lpk',
    //         'url_search'                => '',
    //         'signature_ajuan_retur'     => $signature,
    //         'branch_name'               => $this->model_management_retur->get_company_by_signature($signature, $versi)->row()->branch_name,
    //         'nama_comp'                 => $this->model_management_retur->get_company_by_signature($signature, $versi)->row()->nama_comp,
    //         'signature'                 => $signature
    //     ];

    //     $this->navbar($data);
    //     $this->load->view('kalimantan/header_full_width', $data);
    //     $this->load->view('management_retur/comparing_product_ajuan_retur', $data);
    //     $this->load->view('kalimantan/footer');

    // }

    public function comparing_product_ajuan($signature){

        $get_id_pengajuan_by_signature = $this->model_management_retur->get_id_pengajuan_by_signature($signature);

        if (!$get_id_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_retur/ajuan_retur');
            die;
        }else{
            $id_ajuan = $get_id_pengajuan_by_signature->row()->id;
            $versi = $get_id_pengajuan_by_signature->row()->versi;
            $no_pengajuan = $get_id_pengajuan_by_signature->row()->no_pengajuan;
        }       

        $data_pengajuan = [
            'id_pengajuan'          => $id_ajuan,
            'no_pengajuan'          => $no_pengajuan,
            'signature_pengajuan'   => $signature,
            'versi'                 => $versi,
            'created_at'            => $this->model_outlet_transaksi->timezone(),
            'created_by'            => $this->session->userdata('id'),
        ];

        # cek pengajuan, jika ada data di update 
        // echo "signature : ".$signature;
        // die;
        $cek_pengajuan = $this->db->select('*')->where('signature_pengajuan', $signature)->get('management_retur.pengajuan');
        if ($cek_pengajuan->num_rows() >= 1) {
            // echo "ada";
            // die;
            $this->db->where('signature_pengajuan', $signature);
            $this->db->where('no_pengajuan', $no_pengajuan);
            $this->db->update('management_retur.pengajuan', $data_pengajuan);
        } else {
            // echo "tidak ada";
            // die;
            $this->db->insert('management_retur.pengajuan', $data_pengajuan);
        }

        $data = [
            'title'                     => 'Comparing LPK VS Ajuan Retur DP',
            'get_product_ajuan_retur'   => $this->model_management_retur->get_product_ajuan_retur($id_ajuan, $signature, $versi),
            'url'                       => "management_retur/update_qty_lpk/",
            'url_search'                => '',
            'signature_ajuan_retur'     => $signature,
            'branch_name'               => $get_id_pengajuan_by_signature->row()->branch_name,
            'nama_comp'                 => $get_id_pengajuan_by_signature->row()->nama_comp,
            'signature'                 => $signature,
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/comparing_product_ajuan_retur', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/comparing_product_ajuan_retur', $data); 
    }

    public function update_qty_lpk(){
        $signature = $this->input->post('signature_ajuan_retur');
        $id = $this->input->post('id');
        $qty_lpk = $this->input->post('qty_lpk');

        $get_id_pengajuan_by_signature = $this->model_management_retur->get_id_pengajuan_by_signature($signature);
        // var_dump($get_id_pengajuan_by_signature);die;
        $id_ajuan = $get_id_pengajuan_by_signature->row()->id;
        $versi = $get_id_pengajuan_by_signature->row()->versi;
        $no_pengajuan = $get_id_pengajuan_by_signature->row()->no_pengajuan;
        // var_dump($id_ajuan);die;

        # pilih database
        if ($versi == 'V2') {
            $db = 'management_inventory.pengajuan_retur_detail';
        } else {
            $db = 'db_temp.t_temp_produk_pengajuan_retur';
        }

        # update qty_lpk
        $count = count($this->input->post('qty_lpk'));
        for($i=0; $i < $count; $i++) {
            $data = [
                "qty_lpk"   => $qty_lpk[$i]
            ];
            $this->db->where("id", $id[$i]);
            $this->db->update("$db", $data);
        }

        # cek apakah ada qty_lpk yang belum di update
        $cek = $this->model_management_retur->cek_qty_lpk($id_ajuan, $versi);
        if ($cek->num_rows() > 0) {
            // die;
            echo "<script>alert('masih ditemukan qty lpk yang NULL, harap lengkapi qty lpk di semua row'); </script>";
            redirect("management_retur/comparing_product_ajuan/$signature");
        }else {
            #cek data di pengajuan_detail
            $data = $this->model_management_retur->get_product_ajuan_retur($id_ajuan, $signature, $versi)->result();
            $cek_pengajuan = $this->db->select('*')->where('signature_pengajuan', $signature)->get('management_retur.pengajuan_detail');
            
            if ($cek_pengajuan->num_rows() >= 1) {
                # update management_retur.pengajuan_detail
                foreach ($data as $key) {
                    $insert = [
                        'id_pengajuan'          => $id_ajuan,
                        'no_pengajuan'          => $no_pengajuan,
                        'kodeprod'              => $key->kodeprod,
                        'namaprod'              => $key->namaprod,
                        'batch_number'          => $key->batch_number,
                        'jumlah'                => $key->jumlah,
                        'qty_lpk'               => $key->qty_lpk,
                        'expired_date'          => $key->expired_date,
                        'signature_pengajuan'   => $signature,
                        'versi'                 => $versi,
                        'created_at'            => $this->model_outlet_transaksi->timezone(),
                        'created_by'            => $this->session->userdata('id'),
                    ];
                    $this->db->where('kodeprod', $key->kodeprod);
                    $this->db->where('batch_number', $key->batch_number);
                    $this->db->where('no_pengajuan', $get_id_pengajuan_by_signature->row()->no_pengajuan);
                    $this->db->where('signature_pengajuan', $signature);
                    $this->db->update('management_retur.pengajuan_detail', $insert);
                }
            } else {
                // echo "tidak";
                // die;
                # insert management_retur.pengajuan_detail
                foreach ($data as $key) {
                    $max_year = date('Y',strtotime($this->model_outlet_transaksi->timezone()));
                    if (substr($key->batch_number,-2) >= 20 && substr($key->batch_number,-2) <= substr($max_year,-2)) {
                        $tahun =  '20'.substr($key->batch_number,-2);
                    } else {
                        $tahun = 'Null';
                    }
                    $insert = [
                        'id_pengajuan'          => $id_ajuan,
                        'no_pengajuan'          => $no_pengajuan,
                        'kodeprod'              => $key->kodeprod,
                        'namaprod'              => $key->namaprod,
                        'batch_number'          => $key->batch_number,
                        'tahun'                 => $tahun,
                        'jumlah'                => $key->jumlah,
                        'qty_lpk'               => $key->qty_lpk,
                        'expired_date'          => $key->expired_date,
                        'signature_pengajuan'   => $signature,
                        'versi'                 => $versi,
                        'created_at'            => $this->model_outlet_transaksi->timezone(),
                        'created_by'            => $this->session->userdata('id'),
                    ];
                    $this->db->insert('management_retur.pengajuan_detail', $insert);
                }
            }

            redirect("management_retur/product_ajuan/$signature");
        }

    }

    public function product_ajuan($signature){

        $get_id_pengajuan_by_signature = $this->model_management_retur->get_id_pengajuan_by_signature($signature);
        if (!$get_id_pengajuan_by_signature->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Proses yang anda inginkan gagal dijalankan. Silahkan ulangi kembali !! data not found");
            redirect('management_retur/ajuan_retur');
            die;
        }else{
            $id_ajuan = $get_id_pengajuan_by_signature->row()->id;
            $versi = $get_id_pengajuan_by_signature->row()->versi;
        }  

        $data = [
            'title'                     => 'Product Ajuan Retur',
            'get_product_ajuan_retur'   => $this->model_management_retur->get_pengajuan_detail_retur($signature, $id_ajuan, $versi),
            'url'                       => "management_retur/search_dbsls/",
            'url_reset'                 => "management_retur/reset_product/$signature",
            'signature_ajuan_retur'     => $signature,
            'branch_name'               => $get_id_pengajuan_by_signature->row()->branch_name,
            'nama_comp'                 => $get_id_pengajuan_by_signature->row()->nama_comp,
            'versi'                     => $versi,
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/product_ajuan_retur', $data);
        // $this->load->view('kalimantan/footer');
        $this->render('management_retur/product_ajuan_retur', $data);
    }

    public function search_dbsls(){
        # delete tabel log_search_master_dbsls
        $user_login = $this->session->userdata('id');
        $this->db->query("delete from management_retur.log_search_master_dbsls where created_by = $user_login");

        session_start();
        $signature_ajuan_retur = $this->input->post('signature_ajuan_retur');

        $checklist = $this->input->post('options');
        // var_dump($checklist);die;
        $id_checklist = implode(',',$checklist);
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $userid = $this->input->post('branch');
        $group = $this->input->post('group');
        $customerid = $this->model_management_retur->get_customerid_byid($userid)->row()->kode_lang;
        $signature = md5($this->model_outlet_transaksi->timezone());

        # validasi produk ceklist
        if (!$checklist) {
            // echo 'a';
            $this->session->set_flashdata('pesan', 'Tidak ada satupun kodeproduk yang di checklist');
            redirect("management_retur/product_ajuan/$signature_ajuan_retur");
            die;
        }

        # validasi periode
        $batas_periode_atas = date('Ym', strtotime("+12 months", strtotime($from)));
        $batas_periode_bawah = date('Ym', strtotime($from));

        if (date('Ym', strtotime($to)) >= $batas_periode_atas) {
            $this->session->set_flashdata('pesan', 'Periode Melebih Batas 12 Bulan');
            redirect("management_retur/product_ajuan/$signature_ajuan_retur");
            die;

        } else if (date('Ym', strtotime($to)) <= $batas_periode_bawah) {
            $this->session->set_flashdata('pesan', 'Periode Tidak Sesuai');
            redirect("management_retur/product_ajuan/$signature_ajuan_retur");
            die;
        }

        # get data pengajuan detail
        $datax = $this->db->query("
            SELECT kodeprod, jumlah, qty_lpk, batch_number, tahun
            FROM management_retur.pengajuan_detail
            WHERE id IN ($id_checklist) and signature_pengajuan = '$signature_ajuan_retur'
        ")->result();
        // var_dump($datax);die;

        # input log_search_master_dbsls
        foreach($datax as $kode)
        {
            $get_master_dbsls_noseri = $this->model_management_retur->get_master_dbsls_noseri($customerid, $kode->kodeprod,'', $from, $to);

            foreach ($get_master_dbsls_noseri->result() as $a) {
                if ($a->brandid == 11012 || $a->brandid == 012) {
                    $qty_kecil = $a->qty1;
                    $qty_retur = $a->qty_retur1;
                } else {
                    $qty_kecil = $a->qty_kecil;
                    $qty_retur = $a->retur;
                }

                $insert = [
                    'customerid'    => $a->customerid,
                    'nama_customer' => $a->nama_customer,
                    'no_seri_pajak' => $a->no_seri_pajak,
                    'productid'     => $a->productid,
                    'batch_number'  => $kode->batch_number,
                    'tahun'         => $kode->tahun,
                    'qty_kecil'     => $qty_kecil,
                    'retur'         => $qty_retur,
                    'tanggal'       => $a->tanggal,
                    'ref'           => $a->ref,
                    'qty_ajuan_retur'   => $kode->jumlah,
                    'qty_lpk'       => $kode->qty_lpk,
                    'selisih_qty'   => $qty_kecil - $qty_retur - $kode->qty_lpk,
                    'userid'        => $userid,
                    'brandid'       => $a->brandid,
                    'signature'     => $signature,
                    'signature_ajuan_retur' => $signature_ajuan_retur,
                    'created_at'    => $this->model_outlet_transaksi->timezone(),
                    'created_by'    => $this->session->userdata('id')
                ];
                $this->db->insert('management_retur.log_search_master_dbsls', $insert);

                $nama_customer = $a->nama_customer;
            }
        }

        $data = [
            'get_raw_rekomendasi'   => $this->model_management_retur->get_raw_rekomendasi($signature),
            'get_rekomendasi'       => $this->model_management_retur->get_rekomendasi($signature, $group),
            'title'                 => 'Rekomendasi',
            'url'                   => "management_retur/create_draft_nota_retur/",
            'customerid'            => $customerid,
            'nama_customer'         => $nama_customer,
            'userid'                => $userid,
            'data_customer'         => $this->model_management_retur->get_customerid_byid($userid),
            'signature'             => $signature,
            'signature_ajuan_retur' => $signature_ajuan_retur,
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/rekomendasi', $data);
        // $this->load->view('kalimantan/footer');
        $this->render('management_retur/rekomendasi', $data);

    }

    public function create_draft_nota_retur(){
        $no_seri_pajak          = $this->input->post('no_seri_pajak');
        $customerid             = $this->input->post('customerid');
        $nama_customer          = $this->input->post('nama_customer');
        $userid                 = $this->input->post('userid');
        $signature              = $this->input->post('signature');
        $signature_ajuan_retur  = $this->input->post('signature_ajuan_retur');
        $created_at             = $this->model_outlet_transaksi->timezone();

        $get_data_nota_retur = $this->model_management_retur->get_data_nota_retur($signature_ajuan_retur, $no_seri_pajak, $customerid);
        // var_dump($get_data_nota_retur->result());die;

        # update pengajuan detail
        foreach ($get_data_nota_retur->result() as $a ) {
            $data = [
                'noseri'            => $a->no_seri_pajak,
                'ref'               => $a->ref,
                'tgldo'             => $a->tanggal,
                'retur'             => $a->retur,
                'noseri_beli'       => $a->no_inv,
                'tgldo_beli'        => $a->tgl_terima,
                'qty_kecil'         => $a->qty_kecil,
                'beli'              => $a->beli,
                'jual'              => $a->jual,
                'disc_cabang'       => $a->disc_cabang,
                'disc_beli'         => $a->disc_persen,
            ];
            
            $this->db->where('kodeprod', $a->productid);
            $this->db->where('batch_number', $a->batch_number);
            $this->db->where('signature_pengajuan', $signature_ajuan_retur);
            $this->db->where('noseri', null);
            $this->db->update('management_retur.pengajuan_detail', $data);

            $updated = $this->db->affected_rows();
            if ($updated) {
                # get pengajuan detail
                $this->db->select('id, id_pengajuan');
                $this->db->where('kodeprod', $a->productid);
                $this->db->where('batch_number', $a->batch_number);
                $this->db->where('signature_pengajuan', $signature_ajuan_retur);
                $this->db->where('noseri', $a->no_seri_pajak);
                $updatedId = $this->db->get('management_retur.pengajuan_detail');
                // echo $updatedId;

                # insert temp proses
                $data2 = [
                    'id_pengajuan'  => $updatedId->row()->id_pengajuan,
                    'id_pengajuan_detail'   => $updatedId->row()->id,
                    'signature_pengajuan'   => "$signature_ajuan_retur",
                    'created_at'    => $created_at,
                    'created_by'    => $this->session->userdata('id'),
                ];
                $this->db->insert('management_retur.pengajuan_temp_proses', $data2);
            }
        }

        # get data customer
        $get_customerid_byid = $this->model_management_retur->get_customerid_byid($userid);
        if($get_customerid_byid->num_rows() > 0){
            $customerid = $get_customerid_byid->row()->kode_lang;
            $npwp = $get_customerid_byid->row()->npwp;
            $email = $get_customerid_byid->row()->email;
            $nama_wp = $get_customerid_byid->row()->nama_wp;
            $alamat_wp = $get_customerid_byid->row()->alamat_wp;
        }else{
            $customerid = '';
            $npwp = '';
            $email = '';
            $nama_wp = '';
        }

        $get_draft_nota_retur_product = $this->model_management_retur->get_draft_nota_retur_product($signature_ajuan_retur);

        $data = [
            'get_draft_nota_retur_product'  => $get_draft_nota_retur_product,
            'title'                         => 'Create Draft Nota Retur',
            'url'                           => "management_retur/preview_draft_nota_retur/",
            'back_url'                      => "management_retur/product_ajuan/$signature_ajuan_retur/",
            'signature'                     => $signature,
            'signature_ajuan_retur'         => $signature_ajuan_retur,
            'no_seri_pajak'                 => $no_seri_pajak,
            'customerid'                    => $customerid,
            'nama_customer'                 => $nama_customer,
            'userid'                        => $userid,
            'npwp'                          => $npwp,
            'email'                         => $email,
            'nama_wp'                       => $nama_wp,
            'alamat_wp'                     => $alamat_wp,
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/create_draft_nota_retur', $data);
        // $this->load->view('kalimantan/footer');
        $this->render('management_retur/create_draft_nota_retur', $data);
    }

    public function preview_draft_nota_retur(){
        $signature  = $this->input->post('signature');
        $signature_ajuan_retur  = $this->input->post('signature_ajuan_retur');
        $created_at = $this->model_outlet_transaksi->timezone();
        // var_dump($signature_ajuan_retur);die;

        # update pengajuan_detail
        $count = count($this->input->post('kodeprod'));
        $data2 = array();
        for($i=0; $i < $count; $i++) {
            # update pengajuan_detail
            $this->db->select('tgl_terima');
            $this->db->where('no_inv',$this->input->post('noseri_beli')[$i]);
            $t_ap_master = $this->db->get('dbsls.t_ap_master');

            $data = [
                'noseri_beli'   => $this->input->post('noseri_beli')[$i],
                'tgldo_beli'    => $t_ap_master->row()->tgl_terima,
                'beli'          => $this->input->post('beli')[$i],
                'jual'          => $this->input->post('jual')[$i],
                'disc_cabang'   => $this->input->post('disc_cabang')[$i],
                'disc_beli'     => $this->input->post('disc_beli')[$i],
            ];
            $this->db->where('kodeprod', $this->input->post('kodeprod')[$i]);
            $this->db->where('noseri', $this->input->post('noseri')[$i]);
            $this->db->where('signature_pengajuan', $signature_ajuan_retur);
            $this->db->update('management_retur.pengajuan_detail', $data);
            
            # data
            $data2 = [
                'nodo'              => $this->input->post('nodo'),
                'nodo_beli'         => $this->input->post('nodo_beli'),
                'nopo'              => $this->input->post('nopo'),
                'tglbuat'           => $this->input->post('tglbuat'),
                'tgl_beli'          => $this->input->post('tgl_beli'),
                'customerid'        => $this->input->post('customerid'),
                'nama_customer'     => $this->input->post('nama_customer'),
                'userid'            => $this->input->post('userid'),
                'npwp'              => $this->input->post('npwp'),
                'nama_wp'           => $this->input->post('nama_wp'),
                'email'             => $this->input->post('email'),
                'alamat_wp'         => $this->input->post('alamat_wp'),
                'signature'         => $this->input->post('signature'),
                'signature_ajuan_retur' => $this->input->post('signature_ajuan_retur'),
            ];
        };
        // die;

        $data = [
            'title'             => 'Preview Draft Nota Retur',
            'url'               => "management_retur/submit_draft_nota_retur/",
            'data'              => $data2,
            'data_detail'       => $this->model_management_retur->get_data_preview($signature_ajuan_retur),
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/preview_draft_nota_retur', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/preview_draft_nota_retur', $data);

    }

    public function submit_draft_nota_retur(){
        $signature = $this->input->post('signature');
        $signature_ajuan_retur = $this->input->post('signature_ajuan_retur');
        $data_nota = $this->model_management_retur->get_draft_nota_retur($signature_ajuan_retur);

        # 1. mengupdate master dbsls
        foreach ($data_nota->result() as $a) {
            $data = [
                'retur' => $a->returx,
                'updated_at' => $a->created_at,
            ];

            $this->db->where('no_seri_pajak', $a->noseri);
            $this->db->where('productid', $a->kodeprod);
            $this->db->where('customerid', $this->input->post('customerid'));
            $this->db->update('management_retur.master_dbsls', $data);
        }

        # 2. insert ke mpm.trans dan trans_detail
        $data_nota_trans = $this->model_management_retur->get_draft_nota_retur($signature_ajuan_retur, 1);
        foreach ($data_nota_trans->result() as $key) {
            if (trim($key->nodo_beli) == '' || trim($key->nodo_beli) == null) {
                $flag_sortir = 1;
            } else {
                $flag_sortir = 0;
            }

            $data = [
                "company"       => $this->input->post('nama_customer'),
                "noseri"        => $key->noseri,
                "noseri_beli"   => $key->noseri_beli,
                "supp"          => $key->supp,
                "nopo"          => $this->input->post('nopo'),
                "ref"           => $key->ref,
                "tgldo"         => $key->tgldo,
                "nodo"          => $this->input->post('nodo'),
                "nodo_beli"     => $this->input->post('nodo_beli'),
                "tgldo_beli"    => $key->tgldo_beli,
                "tgl_beli"      => $this->input->post('tgl_beli'),
                "created"       => $key->created_at,
                "created_by"    => $key->created_by,
                "tglbuat"       => $this->input->post('tglbuat'),
                "tipe"          => 'R',
                "userid"        => $this->input->post('userid'),
                "npwp"          => $this->input->post('npwp'),
                "nama_wp"       => $this->input->post('nama_wp'),
                "email"         => $this->input->post('email'),
                "alamat_wp"     => $this->input->post('alamat_wp'),
                "flag_sortir"   => $flag_sortir,
                "id_pengajuan"  => $key->id_pengajuan,
                "no_pengajuan"  => $key->no_pengajuan,
                "versi"         => $key->versi,
            ];

            $insert_trans = $this->db->insert('mpm.trans', $data);

            # insert trans_detail
            if ($insert_trans) {
                $id_ref = $this->db->insert_id();
                $query = $this->model_management_retur->get_draft_nota_retur($signature_ajuan_retur, '', $key->noseri);

                foreach ($query->result() as $a) {
                    $bruto = ($a->qty_lpk) * $a->beli;
                    $diskon = ($a->qty_lpk * $a->beli) * $a->disc_beli/100;

                    $data = [
                        'id_ref'        => $id_ref,
                        'kodeprod'      => $a->kodeprod,
                        'namaprod'      => $a->namaprod,
                        'banyak'        => $a->qty_lpk * -1,
                        'harga'         => $a->jual,
                        'harga_beli'    => $a->beli,
                        'diskon'        => $a->disc_cabang,
                        'diskon_beli'   => $a->disc_beli,
                        "bruto"         => $bruto,
                        "disc"          => $diskon,
                        "dpp"           => $bruto-$diskon,
                        'kode_prc'      => $a->kode_prc,
                        'supp'          => $a->supp,
                        'userid'        => $this->input->post('userid'),
                        'created'       => $a->created_at,
                        'created_by'    => $key->created_by
                    ];

                    $this->db->insert('mpm.trans_detail', $data);
                }

                $trans_detail= $this->model_management_retur->get_totaltrans($id_ref, $query->row()->supp);

                $dataz = array(
                    'tot_bruto' => $trans_detail->row()->tot_bruto,
                    'tot_disc' => $trans_detail->row()->tot_disc,
                    'tot_dpp' => $trans_detail->row()->tot_dpp
                );

                $this->db->where('id', $id_ref);
                $this->db->update('mpm.trans', $dataz);
            }

            # 3. update data ke ajuan retur
            $data = [
                "no_ajuan_retur"    => $key->no_pengajuan,
                "signature_ajuan_retur" => $signature_ajuan_retur,
                "signature_draft_nota_retur"    => $signature,
                "noseri"        => $key->noseri,
                "noseri_beli"   => $key->noseri_beli,
                "created_at"    => $key->created_at,
                "created_by"    => $key->created_by
            ];

            $proses = $this->db->insert('management_retur.ajuan_vs_nota_retur', $data);
        }

        # 4. delete pengajuan_temp_proses
        $this->db->where('signature_pengajuan', $signature_ajuan_retur);
        $this->db->delete('management_retur.pengajuan_temp_proses');

        redirect('management_retur/ajuan_retur');
    }

    public function nota_retur(){
        $data = [
            'title'       => 'daftar nota retur',
            'get_data'    => $this->model_management_retur->get_nota_retur(),
            'url'         => 'management_retur/import_dbsls'
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/nota_retur', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/nota_retur', $data);
    }

    public function export_nota_retur(){
        $query="
            select *
            from management_retur.temp_draft_nota_retur a
            where a.deleted_at is null
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export Nota Retur.csv');
    }

    public function update_data_dbsls(){

        $update_data = $this->model_management_retur->update_data_dbsls();
        if ($update_data) {
            echo "<script>alert('berhasil perbaharui data retur di master dbsls'); </script>";
            redirect('management_retur/master_dbsls','refresh');
            die;
        }

        echo "<script>alert('gagal perbaharui data retur di master dbsls'); </script>";
        redirect('management_retur/master_dbsls','refresh');
    }

    public function truncate_nota_retur(){
        $this->db->query("truncate management_retur.temp_draft_nota_retur");
        redirect("management_retur/nota_retur");
    }

    public function export_template(){
        $query = "
            select '' as 'tanggal_faktur(m/d/y)', '' as 'noseri_pembelian', '' as 'noseri_penjualan', '' as 'kodeprod', '' as 'nama produk', '' as 'qty', '' as 'harga_satuan', '' as 'diskon', '' as 'harga_beli', '' as 'diskon_beli', '' as 'tanggal_nr(m/d/y)', '' as 'no_retur_pembelian', '' as 'no_tanda_terima', '' as 'no_retur_penjualan', '' as 'no_pengajuan'

        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'tanggal_faktur(m/d/y)', 'noseri_pembelian', 'noseri_penjualan', 'kodeprod', 'nama produk', 'qty', 'harga_satuan', 'diskon', 'harga_beli', 'diskon_beli', 'tanggal_nr(m/d/y)', 'no_retur_pembelian', 'no_tanda_terima', 'no_retur_penjualan', 'no_pengajuan'

        ));
        $this->excel_generator->set_column(array
        (
            'tanggal_faktur(m/d/y)', 'noseri_pembelian', 'noseri_penjualan', 'kodeprod', 'nama produk', 'qty', 'harga_satuan', 'diskon', 'harga_beli', 'diskon_beli', 'tanggal_nr(m/d/y)', 'no_retur_pembelian', 'no_tanda_terima', 'no_retur_penjualan', 'no_pengajuan'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
        $this->excel_generator->exportTo2007('Template Import Retur Website');
    }

    public function import(){
        $supp = $this->input->post('supp');
        $branch = $this->input->post('branch');
        // var_dump($branch);die;
        $created_at = $this->model_outlet_transaksi->timezone();
        $get_user = $this->model_management_retur->get_user($branch);

        if (!is_dir('./assets/uploads/management_retur/')) {
            @mkdir('./assets/uploads/management_retur/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/management_retur/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_retur/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_retur/master_dbsls','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = 'NR-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $tanggal_faktur = gmdate("d-m-Y", ($worksheet->getCellByColumnAndRow(0, $row)->getValue() - 25569) * 86400);
                    $noseri_pembelian = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $noseri_penjualan = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $kodeprod = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $namaprod = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $qty = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $harga_satuan = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $diskon = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $harga_beli = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $diskon_beli = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $tanggal_nr = gmdate("d-m-Y", ($worksheet->getCellByColumnAndRow(10, $row)->getValue() - 25569) * 86400);
                    $no_retur_pembelian = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $no_tanda_terima = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $no_retur_penjualan = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $no_pengajuan = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());

                    if(strlen("$kodeprod") == '5')
                    {
                        $kodeprodx = '0'.$kodeprod;
                    }else{
                        $kodeprodx = $kodeprod;
                    }

                    if ($noseri_penjualan != null || $noseri_penjualan != '') {
                        $temp_pajak_penjualan = $this->db->query("
                                SELECT no_seri_pajak, jual, disc_cabang
                                FROM management_retur.master_dbsls
                                where no_seri_pajak = '$noseri_penjualan' and productid = '$kodeprodx'
                                group by productid
                        ");

                        if ($temp_pajak_penjualan->num_rows() == 1) {
                            if ($harga_satuan == null || $harga_satuan == '') {
                                $harga_satuanx = $temp_pajak_penjualan->row()->jual;
                            } else {
                                $harga_satuanx = $harga_satuan;
                            }

                            if ($diskon == null || $diskon == '') {
                                $diskonx = $temp_pajak_penjualan->row()->disc_cabang;
                            } else {
                                $diskonx = $diskon;
                            }

                        } else {
                            $harga_satuanx  = $harga_satuan;
                            $diskonx    = $diskon;
                        }
                    }

                    if ($noseri_pembelian != null || $noseri_pembelian != '') {
                        $temp_pajak_pembelian = $this->db->query("
                            select a.*, b.productid, b.beli, b.jual, b.disc_persen
                            from (
                                SELECT no_inv
                                FROM management_retur.temp_pajak_masukan
                                where no_sj = '$noseri_pembelian' or no_inv = '$noseri_pembelian'
                            ) a LEFT JOIN dbsls.t_ap_product_detail b on a.no_inv = b.no_inv
                            where productid = '$kodeprodx'
                            group by productid
                        ");

                        if ($temp_pajak_pembelian->num_rows() == 1) {
                            $noseri_pembelianx  = $temp_pajak_pembelian->row()->no_inv;

                            if ($harga_beli == null || $harga_beli == '') {
                                $harga_belix = $temp_pajak_pembelian->row()->beli;
                            } else {
                                $harga_belix = $harga_beli;
                            }

                            if ($diskon_beli == null || $diskon_beli == '') {
                                $diskon_belix = $temp_pajak_pembelian->row()->disc_persen;
                            } else {
                                $diskon_belix = $diskon_beli;
                            }

                        } else {
                            $noseri_pembelianx  = $noseri_pembelian;
                            $harga_belix        = $harga_beli;
                            $diskon_belix       = $diskon_beli;
                        }

                        $data = [
                            'no_pengajuan'          => $no_pengajuan,
                            'supp'                  => $supp,
                            'userid'                => $branch,
                            'noseri_penjualan'      => $noseri_penjualan,
                            'noseri_pembelian'      => $noseri_pembelianx,
                            'no_retur_penjualan'    => $no_retur_penjualan,
                            'no_retur_pembelian'    => $no_retur_pembelian,
                            'no_tanda_terima'       => $no_tanda_terima,
                            'company'               => $get_user->row()->company,
                            'npwp'                  => $get_user->row()->npwp,
                            'nama_wp'               => $get_user->row()->nama_wp,
                            'alamat_wp'             => $get_user->row()->alamat_wp,
                            'email'                 => $get_user->row()->email,
                            'tanggal_faktur'        => date('Y-m-d',strtotime($tanggal_faktur)),
                            'tanggal_nr'            => date('Y-m-d',strtotime($tanggal_nr)),
                            'kodeprod'              => $kodeprodx,
                            'qty'                   => $qty,
                            'harga_satuan'          => $harga_satuanx,
                            'harga_beli'            => $harga_belix,
                            'diskon'                => $diskonx,
                            'diskon_beli'           => $diskon_belix,
                            'created_at'            => $created_at,
                            'created_by'            => $this->session->userdata('id'),
                            'signature'             => $signature
                        ];

                        $this->db->insert('management_retur.import_retur',$data);
                    }
                }
            }

            $this->import_preview($signature);
        }else{
            var_dump($this->upload->display_errors());
            die;
        };
        // redirect('management_retur');
    }

    public function import_preview($signature) {
        $data = [
            'title'       => 'Import Preview',
            'get_data'    => $this->model_management_retur->get_import_nr($signature),
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/import_preview', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/import_preview', $data);
    }

    public function import_update()
    {
        $signature = $this->input->post('signature');
        $data = [
            'id'                => $this->input->post('id'),
            'tanggal_faktur'    => $this->input->post('tanggal_faktur'),
            'noseri_pembelian'  => $this->input->post('noseri_pembelian'),
            'noseri_penjualan'  => $this->input->post('noseri_penjualan'),
            'kodeprod'          => $this->input->post('kodeprod'),
            'qty'               => $this->input->post('qty'),
            'harga_satuan'      => $this->input->post('harga_satuan'),
            'diskon'            => $this->input->post('diskon'),
            'harga_beli'        => $this->input->post('harga_beli'),
            'diskon_beli'       => $this->input->post('diskon_beli'),
            'tanggal_nr'        => $this->input->post('tanggal_nr'),
        ];

        $jum_row = count($data['id']);

        for ($i=0; $i < $jum_row ; $i++) {
            $update = array(
                'id'                => $data['id'][$i],
                'tanggal_faktur'    => $data['tanggal_faktur'][$i],
                'noseri_pembelian'  => $data['noseri_pembelian'][$i],
                'noseri_penjualan'  => $data['noseri_penjualan'][$i],
                'kodeprod'          => $data['kodeprod'][$i],
                'qty'               => $data['qty'][$i],
                'harga_satuan'      => $data['harga_satuan'][$i],
                'diskon'            => $data['diskon'][$i],
                'harga_beli'        => $data['harga_beli'][$i],
                'diskon_beli'       => $data['diskon_beli'][$i],
                'tanggal_nr'        => $data['tanggal_nr'][$i],
            );

            $this->db->where('id', $data['id'][$i]);
            $this->db->where('signature', $this->input->post('signature'));
            $this->db->update('management_retur.import_retur', $update);
        }

        $this->session->set_flashdata("pesan_success", "Update Success");
        redirect("management_retur/import_preview/$signature", 'refresh');
    }

    public function import_submit($signature)
    {
        $get_import_header = $this->model_management_retur->get_import_header($signature);

        foreach ($get_import_header->result() as $a)
        {
            $noseri_penjualan = $a->noseri_penjualan;

            if ($a->no_retur_pembelian == '' || $a->no_retur_pembelian == null) {
                $flag_sortir = 1;
            } else {
                $flag_sortir = 0;
            }
            
            $pengajuan_v1 =$this->db->select('id')->from('db_temp.t_temp_pengajuan_retur')->where('no_pengajuan', $a->no_pengajuan)->get();
            if ($pengajuan_v1->num_rows() > 0) {
                $id_pengajuan = $pengajuan_v1->row()->id;
                $versi = 'V1';
            } else {
                $pengajuan_v2 =$this->db->select('id')->from('management_inventory.pengajuan_retur')->where('no_pengajuan', $a->no_pengajuan)->get();
                if ($pengajuan_v2) {
                    $id_pengajuan = $pengajuan_v2->row()->id;
                    $versi = 'V2';
                } else {
                    $id_pengajuan = '';
                    $versi = '';
                }
            }
            $data_trans = [
                "no_pengajuan"      => $a->no_pengajuan,
                "id_pengajuan"      => $id_pengajuan,
                "versi"             => $versi,
                "noseri"            => $a->noseri_penjualan,
                "noseri_beli"       => $a->noseri_pembelian,
                "userid"            => $a->userid,
                "company"           => $a->company,
                "nama_wp"           => $a->nama_wp,
                "npwp"              => $a->npwp,
                "alamat_wp"         => $a->alamat_wp,
                "email"             => $a->email,
                "supp"              => $a->supp,
                "nopo"              => $a->no_tanda_terima,
                "nodo"              => $a->no_retur_penjualan,
                "nodo_beli"         => $a->no_retur_pembelian,
                "tgldo"             => $a->tanggal_faktur,
                "tgldo_beli"        => $a->tanggal_faktur,
                "tgl_beli"          => $a->tanggal_nr,
                "tglbuat"           => $a->tanggal_nr,
                "created"           => $a->created_at,
                "created_by"        => $a->created_by,
                "tipe"              => "R",
                'flag_sortir'       => $flag_sortir
            ];

            $this->db->insert("mpm.trans", $data_trans);
            $id_trans = $this->db->insert_id();

            $get_import_by_id = $this->model_management_retur->get_import_by_id($signature, $noseri_penjualan);
            foreach ($get_import_by_id->result() as $a) {
                $bruto = ($a->qty) * $a->harga_beli;
                $diskona = ($a->qty * $a->harga_beli) * $a->diskon_beli/100;
                $get_produk = $this->model_management_retur->get_produk($a->kodeprod);

                $data_trans_detail = [
                    "id_ref"            => $id_trans,
                    "supp"              => $a->supp,
                    "kodeprod"          => $a->kodeprod,
                    "namaprod"          => $get_produk->row()->NAMAPROD,
                    "banyak"            => (int)$a->qty * -1,
                    "harga"             => $a->harga_satuan,
                    "harga_beli"        => $a->harga_beli,
                    "diskon"            => $a->diskon,
                    "diskon_beli"       => $a->diskon_beli,
                    "bruto"             => $bruto,
                    "disc"              => $diskona,
                    "dpp"               => $bruto-$diskona,
                    "kode_prc"          => $get_produk->row()->KODE_PRC,
                    "userid"            => $a->userid,
                ];
                $this->db->insert("mpm.trans_detail", $data_trans_detail);
            }

            $trans_detail= $this->model_management_retur->get_totaltrans($id_trans, $a->supp);
            $dataz = array(
                'tot_bruto' => $trans_detail->row()->tot_bruto,
                'tot_disc' => $trans_detail->row()->tot_disc,
                'tot_dpp' => $trans_detail->row()->tot_dpp
            );

            $this->db->where('id', $id_trans);
            $this->db->update('mpm.trans', $dataz);
        }
        $this->session->set_flashdata("pesan", "import berhasil");

        redirect('management_retur');
    }

    public function data_retur()
    {
        date_default_timezone_set('Asia/Jakarta');        
        $userid = $this->input->post('branch');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $created_sds = $this->db->select('created_at')->from('management_retur.temp_t_sales_master')->get();
        
        $data = [
            'title'             => 'Pengajuan Retur Vs Nota Retur',
            'get_data_retur'    => $this->model_management_retur->get_data_retur($userid, $from, $to),
            'userid'            => $userid,
            'useridx'           => ($userid) ? $userid : 'null',
            'from'              => $from,
            'to'                => $to,
            'created_at_sds'    => $created_sds->row()->created_at
        ];

        // $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/data_retur', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/data_retur', $data);
    }

    public function update_data_retur(){
        $this->model_management_retur->tarik_data_retur_sds();
        redirect('management_retur/data_retur');
    }

    public function export_data_retur($params, $userid = null, $from = null, $to = null){
        if ($params == 1) {
            $filename = 'Export_pengajuan_retur.csv';
        } elseif ($params == 2) {
            $filename = 'Export_pengajuan_retur_onprogress.csv';
        } elseif ($params == 3) {
            $filename = 'Export_pengajuan_retur_finish.csv';
        }

        $hsl = $this->model_management_retur->export_data_retur( $params, $userid, $from, $to);
        query_to_csv($hsl,TRUE,"$filename");
    }

    public function reset($signature){
        $data_pengajuan = $this->db->get_where('management_retur.pengajuan', array('signature_pengajuan' => "$signature"));
        
        if ($data_pengajuan->num_rows() > 0) {
            $id_pengajuan   = $data_pengajuan->row()->id_pengajuan;
            $no_pengajuan   = $data_pengajuan->row()->no_pengajuan;
            $versi          = $data_pengajuan->row()->versi;
            # delete trans
            $data = [
                'deleted'   => 1,
                'modified'  => $this->model_outlet_transaksi->timezone(),
                'modified_by'   => $this->session->userdata('id'),
            ];

            $this->db->where('id_pengajuan', $id_pengajuan);
            $this->db->where('no_pengajuan', $no_pengajuan);
            $this->db->where('versi', $versi);
            $update =$this->db->update('mpm.trans ', $data);

            if ($update) {
                # delete pengajuan_detail
                $this->db->where('id_pengajuan', $id_pengajuan);
                $this->db->where('no_pengajuan', $no_pengajuan);
                $this->db->where('versi', $versi);
                $this->db->where('signature_pengajuan', $signature);
                $this->db->delete('management_retur.pengajuan_detail');

                # delete ajuan_vs_nota_retur
                $data = [
                    'deleted_at'    => $this->model_outlet_transaksi->timezone(),
                    'deleted_by'    => $this->session->userdata('id'),
                ];
                $this->db->where('signature_ajuan_retur', $signature);
                $this->db->update('management_retur.ajuan_vs_nota_retur', $data);

                $this->session->set_flashdata("pesan_berhasil", "Reseting Data Berhasil");
                redirect('management_retur/ajuan_retur');
            } else {
                $this->session->set_flashdata("pesan_gagal", "Reseting Data Gagal");
                redirect('management_retur/ajuan_retur');
            }
        } else {
            $this->session->set_flashdata("pesan_gagal", "Reseting Data Gagal, Infokan ke tim IT");
            redirect('management_retur/ajuan_retur');
        }

    }

    public function reset_product($signature_ajuan_retur) {

        $data_proses = $this->db->get_where('management_retur.pengajuan_temp_proses', array('signature_pengajuan' => "$signature_ajuan_retur"));

        if ($data_proses->num_rows() > 0) {
            foreach ($data_proses->result() as $key => $value) {
                # update pengajuan_detail
                $data = [
                    'noseri'            => null,
                    'ref'               => null,
                    'tgldo'             => null,
                    'retur'             => null,
                    'noseri_beli'       => null,
                    'tgldo_beli'        => null,
                    'qty_kecil'         => null,
                    'beli'              => null,
                    'jual'              => null,
                    'disc_cabang'       => null,
                    'disc_beli'         => null,
                ];

                $this->db->where('id', $value->id_pengajuan_detail);
                $this->db->where('signature_pengajuan', $value->signature_pengajuan);
                $this->db->update('management_retur.pengajuan_detail', $data);
            }
            
            # delete pengajuan_temp_proses
            $this->db->where('signature_pengajuan', $signature_ajuan_retur);
            $this->db->delete('management_retur.pengajuan_temp_proses');

            redirect("management_retur/product_ajuan/$signature_ajuan_retur");
        } else {
            redirect("management_retur/product_ajuan/$signature_ajuan_retur");
        }
    }

    public function report_progress_nota_retur($branch = 'all', $from = '', $to = '')
    {
        $this->load->model('model_master_data');
        $branch = $this->input->get('branch');
        $from = $this->input->get('from');
        $to = $this->input->get('to');


        if ($branch == 'all' || $branch == '') {
            $branch_ovveride = '000';
        }else{
            $branch_ovveride = $branch;
        }

        $data_user = $this->model_master_data->get_username_by_id($branch_ovveride);
        if ($data_user->num_rows() > 0) {
            $company = $data_user->row()->company;
        }else{
            $company = 'ALL DP';
        }

        $data_all = $this->model_management_retur->pengajuan_retur_join_trans($branch_ovveride, $from, $to, 'all');
        $total_data_all = $data_all->num_rows();
        $data_done = $this->model_management_retur->pengajuan_retur_join_trans($branch_ovveride, $from, $to, 'done');
        $total_data_done = $data_done->num_rows();
        
        
        $data = [
            'title'             => 'Report Progress Nota Retur',
            'url'               => 'report_progress_nota_retur',
            'get_data_all'      => $data_all,
            'total_data_all'    => $total_data_all,
            'get_data_done'     => $data_done,
            'total_data_done'   => $total_data_done,
            'branch'            => $branch,
            'from'              => $from,
            'to'                => $to,
            'company'           => $company,
        ];

        // $this->navbar($data);
        // $this->load->view('management_claim/css', $data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('management_retur/report_progress_nota_retur', $data);
        // $this->load->view('kalimantan/footer');

        $this->render('management_retur/report_progress_nota_retur', $data);
    }

    public function report_progress_nota_retur_export($branch = '', $from = '', $to = '', $status = '')
    {
        if ($status == 'done') {
            $filename = "data all";
        }else{
            $filename = "data all";
        }
        $get_data = $this->model_management_retur->pengajuan_retur_join_trans($branch, $from, $to, $status);
        query_to_csv($get_data,TRUE,$filename.".csv");
    }

    public function branch_dp(){

        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/branch_dp?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> Pilih DP </option>";
            echo "<option value=000> ALL DP </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["id"] ."' customerid='" . $tiapbranch["kode_lang"] . "' >";
                echo $tiapbranch["company"]." - ".$tiapbranch["username"]." - ".$tiapbranch["id"];
                echo "</option>";
            }
        }
    }

    public function import_coretax()
    {
        if (!is_dir('./assets/uploads/management_retur/')) {
            @mkdir('./assets/uploads/management_retur/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/management_retur/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file_coretax'))
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_retur/$filename");

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = 'IC-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {
                    
                    $principal = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $no_faktur = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tgl_faktur = gmdate("d-m-Y", ($worksheet->getCellByColumnAndRow(2, $row)->getValue() - 25569) * 86400);
                    $nodo_retur = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $tgl_retur = gmdate("d-m-Y", ($worksheet->getCellByColumnAndRow(4, $row)->getValue() - 25569) * 86400);
                    $nilai_retur_dpp = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nilai_retur_ppn = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $nilai_retur_ppnbm = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $no_coretax = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());

                    if ($principal != '' || $principal != null) {
                        $data = [
                            'principal'     => $principal,
                            'no_faktur'     => $no_faktur,
                            'tgl_faktur'    => date('Y-m-d',strtotime($tgl_faktur)),
                            'nodo_retur'    => $nodo_retur,
                            'tgl_retur'     => date('Y-m-d',strtotime($tgl_retur)),
                            'nilai_retur_dpp'   => $nilai_retur_dpp,
                            'nilai_retur_ppn'   => $nilai_retur_ppn,
                            'nilai_retur_ppnbm' => $nilai_retur_ppnbm,
                            'no_coretax'    => $no_coretax,
                            'signature'     => $signature,
                            'created_at'    => $this->model_outlet_transaksi->timezone(),
                            'created_by'    => $this->session->userdata('id')
                        ];
                        $this->db->insert('management_retur.import_coretax',$data);
                    }
                }
            }
            $this->session->set_flashdata("pesan_success", "Update Coretax Success");
            $this->model_management_retur->update_trans_coretax($signature);
        }else{
            $this->session->set_flashdata("pesan_success", "Update Coretax Gagal");
            $this->upload->display_errors();
            die;
        };

        redirect('management_retur');
    }

    public function export_template_coretax(){
        $query = "
            select '' as 'nama', '' as 'nomor_faktur', '' as 'tanggal_faktur', '' as 'nomor_dokumen_retur', '' as 'tanggal_retur', '' as 'nilai_retur_dpp', '' as 'nilai_retur_ppn', '' as 'nilai_retur_ppnbm', '' as 'no_retur_coretax'

        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'nama', 'nomor_faktur', 'tanggal_faktur', 'nomor_dokumen_retur', 'tanggal_retur', 'nilai_retur_dpp', 'nilai_retur_ppn', 'nilai_retur_ppnbm', 'no_retur_coretax'

        ));
        $this->excel_generator->set_column(array
        (
            'nama', 'nomor_faktur', 'tanggal_faktur', 'nomor_dokumen_retur', 'tanggal_retur', 'nilai_retur_dpp', 'nilai_retur_ppn', 'nilai_retur_ppnbm', 'no_retur_coretax'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10));
        $this->excel_generator->exportTo2007('Template Import Retur Coretax');
    }
}
?>
