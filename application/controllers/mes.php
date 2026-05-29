<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mes extends MY_Controller
{
    
    function mes()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_mes'));
    }
    function index()
    {
        $this->transaksi();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function user(){
        $data = [
            'title'     => 'User',
            'id'        => $this->session->userdata('id'),
            'get_user'  => $this->model_mes->get_user(),
            'url'       => 'mes/user_tambah'
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/user', $data);
        $this->load->view('mes/footer');
    }

    public function user_tambah(){
        $data = [
            'userid'    => $this->input->post('userid'),
            'nama_user' => $this->input->post('nama_user'),
            'status'    => $this->input->post('status'),
            'created_at'=> $this->model_outlet_transaksi->timezone(),
            'created_by'=> $this->session->userdata('id'),
            'signature' => md5($this->model_outlet_transaksi->timezone())
        ];

        $this->db->insert('mes.m_user', $data);
        redirect('mes/user');
    }

    public function user_edit($signature){
        $data = [
            'title'     => 'User',
            'url'       => 'mes/user_update',
            'get_user'  => $this->model_mes->get_user($signature),
            'get_user_all'  => $this->model_mes->get_user()
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/user_edit', $data);
        $this->load->view('mes/footer');
    }

    public function user_update(){
        
        $data = [
            'userid'    => $this->input->post('userid'),
            'nama_user' => $this->input->post('nama_user'),
            'status'    => $this->input->post('status'),
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('mes.m_user', $data);
        redirect('mes/user');
    }

    public function user_delete($signature){
        
        $data = [
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
            'deleted_at'=>$this->model_outlet_transaksi->timezone(),
            'deleted_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('mes.m_user', $data);
        redirect('mes/user');
    }

    public function store(){
        $data = [
            'title'     => 'Store',
            'id'        => $this->session->userdata('id'),
            'get_store'  => $this->model_mes->get_store(),
            'url'       => 'mes/store_tambah'
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/store', $data);
        $this->load->view('mes/footer');
    }

    public function store_tambah(){
        $data = [
            'storeid'    => $this->input->post('storeid'),
            'nama_store' => $this->input->post('nama_store'),
            'status'     => $this->input->post('status'),
            'created_at' => $this->model_outlet_transaksi->timezone(),
            'created_by' => $this->session->userdata('id'),
            'signature'  => md5($this->model_outlet_transaksi->timezone())
        ];

        $this->db->insert('mes.m_store', $data);
        redirect('mes/store');
    }

    public function store_edit($signature){
        $data = [
            'title'      => 'Store',
            'url'        => 'mes/store_update',
            'get_store'  => $this->model_mes->get_store($signature),
            'get_store_all'  => $this->model_mes->get_store()
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/store_edit', $data);
        $this->load->view('mes/footer');
    }

    public function store_update(){
        
        $data = [
            'storeid'    => $this->input->post('storeid'),
            'nama_store' => $this->input->post('nama_store'),
            'status'    => $this->input->post('status'),
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('mes.m_store', $data);
        redirect('mes/store');
    }

    public function store_delete($signature){
        
        $data = [
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
            'deleted_at'=>$this->model_outlet_transaksi->timezone(),
            'deleted_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('mes.m_store', $data);
        redirect('mes/store');
    }

    public function olshop(){
        $data = [
            'title'     => 'Olshop',
            'id'        => $this->session->userdata('id'),
            'get_olshop'  => $this->model_mes->get_olshop(),
            'url'       => 'mes/olshop_tambah'
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/olshop', $data);
        $this->load->view('mes/footer');
    }

    public function olshop_tambah(){
        $data = [
            'olshopid'    => $this->input->post('olshopid'),
            'nama_olshop' => $this->input->post('nama_olshop'),
            'status'     => $this->input->post('status'),
            'created_at' => $this->model_outlet_transaksi->timezone(),
            'created_by' => $this->session->userdata('id'),
            'signature'  => md5($this->model_outlet_transaksi->timezone())
        ];

        $this->db->insert('mes.m_olshop', $data);
        redirect('mes/olshop');
    }

    public function olshop_edit($signature){
        $data = [
            'title'         => 'Olshop',
            'url'           => 'mes/olshop_update',
            'get_olshop'    => $this->model_mes->get_olshop($signature),
            'get_olshop_all'=> $this->model_mes->get_olshop(),
            'signature'     => $signature
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/olshop_edit', $data);
        $this->load->view('mes/footer');
    }

    public function olshop_update(){
        
        $data = [
            'olshopid'    => $this->input->post('olshopid'),
            'nama_olshop' => $this->input->post('nama_olshop'),
            'status'    => $this->input->post('status'),
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('mes.m_olshop', $data);
        redirect('mes/olshop');
    }

    public function olshop_delete($signature){
        
        $data = [
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
            'deleted_at'=>$this->model_outlet_transaksi->timezone(),
            'deleted_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('mes.m_olshop', $data);
        redirect('mes/olshop');
    }

    public function product(){
        $data = [
            'title'     => 'Product',
            'id'        => $this->session->userdata('id'),
            'get_product'  => $this->model_mes->get_product(),
            'url'       => 'mes/product_tambah',
            'url_import'       => 'mes/product_import',
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/product', $data);
        $this->load->view('mes/footer');
    }

    public function template_product(){        
        $query = "
            select  '' as productid, '' as nama_product, '' as harga, '' as discount, '' as satuan_1, '' as unit_1, '' as satuan_2, '' as unit_2
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Product.csv');
    } 

    public function template_sku_olshop(){        
        $query = "
            select  '' as olshopid, '' as skuid, '' as nama_sku, '' as status_jual, '' as status_aktif, '' as productid, '' as qty_rule, '' as status_jual_product, '' as status_aktif_product
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Sku_Olshop.csv');
    } 

    public function product_import(){

        if (!is_dir('./assets/uploads/mes/import/')) {
            @mkdir('./assets/uploads/mes/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/mes/import/';
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

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/mes/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('mes','refresh');
            }            

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {    
                    
                    $productid = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $nama_product = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $harga = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $discount = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $satuan1 = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $unit1 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $satuan2 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $unit2 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();

                    if($productid == null || $productid == '' || $nama_product == null || $nama_product == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom productid atau nama_product, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if(strlen("$productid") == '5')
                    {
                        $productidx = '0'.$productid;
                    }else{
                        $productidx = $productid;
                    } 

                    $created_at = $this->model_outlet_transaksi->timezone();
                    $signature = md5($this->model_outlet_transaksi->timezone().$productidx);

                    // cek productid
                    $cek_productid = $this->model_mes->get_productid($productidx);

                    if ($cek_productid->num_rows() > 0) {
                        
                        $data = [
                            'productid'      => $productidx,
                            'nama_product'   => $nama_product,
                            'harga'          => $harga,
                            'discount'       => $discount,
                            'satuan1'        => trim($satuan1),
                            'unit1'          => $unit1,
                            'satuan2'        => trim($satuan2),
                            'unit2'          => $unit2,
                            'signature'      => $signature,
                            'created_at'     => $created_at,
                            'created_by'     => $this->session->userdata('id')
                        ];
                        $this->db->where('productid', $productidx);
                        $this->db->update('mes.m_product',$data);

                    }else{
                        
                        $data = [
                            'productid'      => $productidx,
                            'nama_product'   => $nama_product,
                            'harga'          => $harga,
                            'discount'       => $discount,
                            'satuan1'        => trim($satuan1),
                            'unit1'          => $unit1,
                            'satuan2'        => trim($satuan2),
                            'unit2'          => $unit2,
                            'signature'      => $signature,
                            'created_at'     => $created_at,
                            'created_by'     => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('mes.m_product',$data);
                    }
                }
            }
        }else{
           
        };

        redirect('mes/product');
    }

    public function product_tambah(){
        $data = [
            'productid'    => $this->input->post('productid'),
            'nama_product' => $this->input->post('nama_product'),
            'harga' => $this->input->post('harga'),
            'discount' => $this->input->post('discount'),
            'satuan1' => $this->input->post('satuan1'),
            'unit1' => $this->input->post('unit1'),
            'satuan2' => $this->input->post('satuan2'),
            'unit2' => $this->input->post('unit2'),
            'created_at' => $this->model_outlet_transaksi->timezone(),
            'created_by' => $this->session->userdata('id'),
            'signature'  => md5($this->model_outlet_transaksi->timezone())
        ];

        $this->db->insert('mes.m_product', $data);
        redirect('mes/product');
    }

    public function product_edit($signature){
        $data = [
            'title'         => 'Olshop',
            'url'           => 'mes/product_update',
            'get_product'   => $this->model_mes->get_product($signature),
            'get_product_all'  => $this->model_mes->get_product(),
            'signature'     => $signature
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/product_edit', $data);
        $this->load->view('mes/footer');
    }

    public function product_update(){
        
        $data = [
            'productid'    => $this->input->post('productid'),
            'nama_product' => $this->input->post('nama_product'),
            'harga' => $this->input->post('harga'),
            'discount' => $this->input->post('discount'),
            'satuan1' => $this->input->post('satuan1'),
            'unit1' => $this->input->post('unit1'),
            'satuan2' => $this->input->post('satuan2'),
            'unit2' => $this->input->post('unit2'),
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
            'status_gimmick'=>  $this->input->post('status_gimmick'),
        ];


        // var_dump($data);
        // die;
        // echo "signature : ".$this->input->post('signature');
        // die;

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('mes.m_product', $data);
        redirect('mes/product');
    }

    public function product_delete($signature){
        
        $data = [
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
            'deleted_at'=>$this->model_outlet_transaksi->timezone(),
            'deleted_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('mes.m_product', $data);
        redirect('mes/product');
    }

    public function sku_olshop(){
        $data = [
            'title'     => 'Sku Olshop',
            'id'        => $this->session->userdata('id'),
            'get_sku_olshop'  => $this->model_mes->get_sku_olshop(),
            'url'       => 'mes/sku_olshop_tambah',
            'url_import'       => 'mes/sku_olshop_import',
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/sku_olshop', $data);
        $this->load->view('mes/footer');
    }

    public function sku_olshop_import(){

        if (!is_dir('./assets/uploads/mes/import/')) {
            @mkdir('./assets/uploads/mes/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/mes/import/';
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

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/mes/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('mes','refresh');
            }            

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                $signature_log = md5(rand());

                for ($row = 2; $row <= $highestRow; $row++) {    
                    
                    $olshopid = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $skuid = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $nama_sku = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $status_jual = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $status_aktif = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $productid = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $qty_rule = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $status_jual_product = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $status_aktif_product = $worksheet->getCellByColumnAndRow(8, $row)->getValue();

                    if($olshopid == null || $olshopid == '' || $skuid == null || $skuid == '' || $nama_sku == null || $nama_sku == '' || $status_aktif == null || $status_aktif == '' || $productid == null || $productid == '' || $qty_rule == null || $qty_rule == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong di baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if(strlen("$productid") == '5')
                    {
                        $productidx = '0'.$productid;
                    }else{
                        $productidx = $productid;
                    } 

                    $created_at = $this->model_outlet_transaksi->timezone();
                    $signature = md5($this->model_outlet_transaksi->timezone().$productidx.rand());

                    // cek productid
                    $cek_sku_olshop = $this->model_mes->get_sku_olshop_import($olshopid, $skuid, $productidx);

                    if ($cek_sku_olshop->num_rows() > 0) {
                        
                        $data = [
                            'olshopid'              => $olshopid,
                            'skuid'                 => $skuid,
                            'nama_sku'              => $nama_sku,
                            'status_jual'           => $status_jual,
                            'status_aktif'          => $status_aktif,
                            'productid'             => $productidx,
                            'qty_rule'              => $qty_rule,
                            'status_jual_product'   => $status_jual_product,
                            'status_aktif_product'  => $status_aktif_product,
                            'signature'             => $signature,
                            'signature_log'         => $signature_log,
                            'created_at'            => $created_at,
                            'created_by'            => $this->session->userdata('id'),
                            'updated_at'            => $created_at,
                            'updated_by'            => $this->session->userdata('id')
                        ];
                        $this->db->where('productid', $productidx);
                        $this->db->where('olshopid', $olshopid);
                        $this->db->where('skuid', $skuid);
                        $this->db->update('mes.import_sku_olshop',$data);

                    }else{
                        
                        $data = [
                            'olshopid'              => $olshopid,
                            'skuid'                 => $skuid,
                            'nama_sku'              => $nama_sku,
                            'status_jual'           => $status_jual,
                            'status_aktif'          => $status_aktif,
                            'productid'             => $productidx,
                            'qty_rule'              => $qty_rule,
                            'status_jual_product'   => $status_jual_product,
                            'status_aktif_product'  => $status_aktif_product,
                            'signature'             => $signature,
                            'signature_log'         => $signature_log,
                            'created_at'            => $created_at,
                            'created_by'            => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('mes.import_sku_olshop',$data);
                    }
                }
            }
        }

        // echo "signature_log : ".$signature_log;
        $get_data = $this->model_mes->get_import_sku_olshop_group_olshopid_skuid($signature_log)->result();

        foreach ($get_data as $key) {
            // echo "olshopid_1 : ".$key->olshopid."<br>";
            // echo "skuid_1 : ".$key->skuid."<br><br>";

            $signature = md5(rand());

            $cek_sku_olshop = $this->model_mes->get_sku_olshop_by_olshopid_skuid($key->olshopid, $key->skuid);

            if ($cek_sku_olshop->num_rows() > 0) {
                
                $data = [
                    'olshopid'      => $key->olshopid,
                    'skuid'         => $key->skuid,
                    'nama_sku'      => $key->nama_sku,
                    'status_jual'   => $key->status_jual,
                    'status_aktif'  => $key->status_aktif,
                    'created_at'    => $created_at,
                    'created_by'    => $this->session->userdata('id'),
                    'updated_at'    => $created_at,
                    'updated_by'    => $this->session->userdata('id'),
                    'signature'     => $signature     
                ];

                $this->db->where('olshopid', $key->olshopid);
                $this->db->where('skuid', $key->skuid);
                $this->db->update('mes.m_sku_olshop', $data);

            }else{

                $data = [
                    'olshopid'      => $key->olshopid,
                    'skuid'         => $key->skuid,
                    'nama_sku'      => $key->nama_sku,
                    'status_jual'   => $key->status_jual,
                    'status_aktif'  => $key->status_aktif,
                    'created_at'    => $created_at,
                    'created_by'    => $this->session->userdata('id'),
                    'signature'     => $signature     
                ];

                $this->db->insert('mes.m_sku_olshop', $data);
            }
        }


        // start sku_olshop_detail
        $get_data = $this->model_mes->get_import_sku_olshop_group_olshopid_skuid_productid($signature_log)->result();
        foreach ($get_data as $key) {
            // echo "olshopid : ".$key->olshopid."<br>";
            // echo "productid : ".$key->productid."<br>";
            // echo "skuid : ".$key->skuid."<br><br>";

            $signature = md5(rand());
            
            $cek_sku_olshop_detail = $this->model_mes->get_sku_olshop_detail_by_olshopid_skuid_productid($key->olshopid, $key->skuid, $key->productid);

            if ($cek_sku_olshop_detail->num_rows() > 0) {
                $data = [
                    'olshopid'      => $key->olshopid,
                    'skuid'         => $key->skuid,
                    'productid'     => $key->productid,
                    'qty_rule'      => $key->qty_rule,
                    'status_jual'   => $key->status_jual,
                    'status_aktif'  => $key->status_aktif,
                    'created_at'    => $created_at,
                    'created_by'    => $this->session->userdata('id'),
                    'updated_at'    => $created_at,
                    'updated_by'    => $this->session->userdata('id'),
                    'signature'     => $signature     
                ];

                $this->db->where('olshopid', $key->olshopid);
                $this->db->where('skuid', $key->skuid);
                $this->db->where('productid', $key->productid);
                $this->db->update('mes.m_sku_olshop_detail', $data);

            }else{

                $data = [
                    'olshopid'      => $key->olshopid,
                    'skuid'         => $key->skuid,
                    'productid'     => $key->productid,
                    'qty_rule'      => $key->qty_rule,
                    'status_jual'   => $key->status_jual,
                    'status_aktif'  => $key->status_aktif,
                    'created_at'    => $created_at,
                    'created_by'    => $this->session->userdata('id'),
                    'signature'     => $signature     
                ];

                $this->db->insert('mes.m_sku_olshop_detail', $data);

            }
        
        }

        redirect('mes/sku_olshop');
    }

    public function sku_olshop_tambah(){
        $data = [
            'skuid'    => $this->input->post('skuid'),
            'nama_sku'    => $this->input->post('nama_sku'),
            // 'productid'    => $this->input->post('productid'),
            // 'nama_product' => $this->input->post('nama_product'),
            'olshopid' => $this->input->post('olshop'),
            'qty_rule' => $this->input->post('qty_rule'),
            'status_jual' => $this->input->post('status_jual'),
            'status_aktif' => $this->input->post('status_aktif'),
            'created_at' => $this->model_outlet_transaksi->timezone(),
            'created_by' => $this->session->userdata('id'),
            'signature'  => md5($this->model_outlet_transaksi->timezone())
        ];

        $this->db->insert('mes.m_sku_olshop', $data);
        redirect('mes/sku_olshop');
    }

    public function sku_olshop_edit($signature){
        $data = [
            'title'             => 'Olshop',
            'url'               => 'mes/sku_olshop_update',
            'get_sku_olshop'    => $this->model_mes->get_sku_olshop($signature),
            'get_sku_olshop_all'=> $this->model_mes->get_sku_olshop(),
            'signature'         => $signature
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/sku_olshop_edit', $data);
        $this->load->view('mes/footer');
    }

    public function sku_olshop_update(){
        
        $data = [
            'skuid'    => $this->input->post('skuid'),
            'nama_sku'    => $this->input->post('nama_sku'),
            'productid'    => $this->input->post('productid'),
            'nama_product' => $this->input->post('nama_product'),
            'qty_rule' => $this->input->post('qty_rule'),
            'status_jual' => $this->input->post('status_jual'),
            'status_aktif' => $this->input->post('status_aktif'),
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('mes.m_sku_olshop', $data);
        redirect('mes/sku_olshop');
    }

    public function sku_olshop_delete($signature){
        
        $data = [
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
            'deleted_at'=>$this->model_outlet_transaksi->timezone(),
            'deleted_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('mes.m_sku_olshop', $data);
        redirect('mes/sku_olshop');
    }

    public function sku_olshop_add($signature){

        $get_skuid = $this->model_mes->get_sku_olshop($signature)->row()->skuid;

        $data = [
            'title'      => 'Olshop',
            'url'        => 'mes/sku_olshop_add_proses/'.$signature,
            'get_sku_olshop'  => $this->model_mes->get_sku_olshop($signature),
            'get_sku_olshop_detail'  => $this->model_mes->get_sku_olshop_detail($get_skuid)
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/sku_olshop_add', $data);
        $this->load->view('mes/footer');
    }

    public function sku_olshop_add_proses($signature){

        $data = [
            'skuid' => $this->input->post('skuid'),
            'productid' => $this->input->post('productid'),
            'qty_rule' => $this->input->post('qty_rule'),
            'status_jual' => $this->input->post('status_jual'),
            'status_aktif' => $this->input->post('status_aktif'),
            'signature' => md5($this->model_outlet_transaksi->timezone()),
            'created_at'=> $this->model_outlet_transaksi->timezone(),
            'created_by'=> $this->session->userdata('id'),
        ];

        $this->db->insert('mes.m_sku_olshop_detail', $data);
        redirect('mes/sku_olshop_add/'.$signature);


    }

    public function transaksi(){

        $year_params = date('Y')+1;
        // $periode2 = date($year_params.'-m-d');

        $data = [
            'title'     => 'Transaksi / Proses Master',
            'id'        => $this->session->userdata('id'),
            'get_transaksi'  => $this->model_mes->get_transaksi('','',''),
            'url'       => 'mes/transaksi_tambah',
            'url_import'=> 'mes/import_transaksi',
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/transaksi', $data);
        $this->load->view('mes/footer');
    }

    public function transaksi_tambah(){

        $data = [
            'tgl_proses'    => $this->input->post('tgl_proses'),
            'no_proses'     => $this->model_mes->generate_transaksi($this->input->post('tgl_proses')),
            'storeid'       => $this->input->post('store'),
            'olshopid'      => $this->input->post('olshop'),
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $this->session->userdata('id'),
            'signature'     => md5($this->model_outlet_transaksi->timezone())
        ];

        $this->db->insert('mes.t_proses_master', $data);
        redirect('mes/transaksi');
    }

    public function transaksi_edit($signature){

        $data = [
            'title'      => 'Olshop | Edit Proses Transaksi',
            'url'        => 'mes/transaksi_update',
            'get_sku_olshop'  => $this->model_mes->get_sku_olshop($signature),
            'get_sku_olshop_all'  => $this->model_mes->get_sku_olshop(),
            'get_transaksi'  => $this->model_mes->get_transaksi($signature,'',''),
            'signature' => $signature
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/transaksi_edit', $data);
        $this->load->view('mes/footer');
    }

    public function transaksi_update(){
        
        $data = [
            'tgl_proses'    => $this->input->post('tgl_proses'),
            'olshopid'    => $this->input->post('olshop'),
            'storeid'    => $this->input->post('store'),
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('mes.t_proses_master', $data);
        redirect('mes/transaksi');
    }

    public function transaksi_delete($signature){
        
        $data = [
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
            'deleted_at'=>$this->model_outlet_transaksi->timezone(),
            'deleted_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('mes.t_proses_master', $data);
        redirect('mes/transaksi');
    }

    public function transaksi_add($signature){

        $get_no_proses = $this->model_mes->get_transaksi($signature,'','')->row()->no_proses;

        $data = [
            'title'      => 'Transaksi / Add Invoice Header',
            'url'        => 'mes/transaksi_add_proses/'.$signature,
            'get_transaksi'  => $this->model_mes->get_transaksi($signature),
            'get_transaksi_detail'  => $this->model_mes->get_transaksi_detail($get_no_proses)
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/transaksi_add', $data);
        $this->load->view('mes/footer');
    }

    public function transaksi_add_proses($signature){

        $data = [
            'no_proses' => $this->input->post('no_proses'),
            'tgl_invoice' => $this->input->post('tgl_invoice'),
            'no_invoice' => $this->input->post('no_invoice'),
            'customer' => $this->input->post('customer'),
            'kurir'         => $this->input->post('kurir'),
            'no_resi'       => $this->input->post('no_resi'),
            'signature' => md5($this->model_outlet_transaksi->timezone()),
            'created_at'=> $this->model_outlet_transaksi->timezone(),
            'created_by'=> $this->session->userdata('id'),
        ];

        $this->db->insert('mes.t_proses_detail', $data);
        redirect('mes/transaksi_add/'.$signature);

    }

    public function transaksi_sku_add($signature){

        $get_id_transaksi_detail = $this->model_mes->get_transaksi_detail_by_signature($signature)->row()->id;
        $olshopid = $this->model_mes->get_transaksi_detail_by_signature($signature)->row()->olshopid;

        $data = [
            'title'      => 'Transaksi / Add SKU',
            'url'        => 'mes/transaksi_sku_add_proses/'.$signature,
            'get_transaksi_detail_by_signature'  => $this->model_mes->get_transaksi_detail_by_signature($signature),
            'get_transaksi_sku'  => $this->model_mes->get_transaksi_sku($get_id_transaksi_detail, $olshopid),
            'signature_proses_detail'   => $signature
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/transaksi_sku_add', $data);
        $this->load->view('mes/footer');
    }

    public function transaksi_sku_add_proses($signature){

        $data = [
            'id_invoice' => $this->input->post('id_invoice'),
            'skuid' => $this->input->post('sku_olshop'),
            'qty_sku' => $this->input->post('qty_sku'),
            'signature' => md5($this->model_outlet_transaksi->timezone()),
            'created_at'=> $this->model_outlet_transaksi->timezone(),
            'created_by'=> $this->session->userdata('id'),
        ];

        $this->db->insert('mes.t_proses_sku', $data);
        redirect('mes/transaksi_sku_add/'.$signature);
    }

    public function transaksi_sku_edit($signature_sku, $signature_invoice){
        
        // $get_no_proses = $this->model_mes->get_transaksi_detail_by_signature($signature_invoice)->row()->no_proses;
        $get_signature_header = $this->model_mes->get_transaksi_detail_by_signature($signature_invoice)->row()->signature_header;

        $olshopid = $this->model_mes->get_transaksi_detail_by_signature($signature_invoice)->row()->olshopid;
        $get_id_transaksi_detail = $this->model_mes->get_transaksi_detail_by_signature($signature_invoice)->row()->id;

        // echo "id : ".$get_id_transaksi_detail;

        $data = [
            'title'      => 'Transaksi / Invoice Header',
            'url'        => 'mes/transaksi_sku_edit_proses/',
            'get_transaksi_header'  => $this->model_mes->get_transaksi($get_signature_header),
            'get_transaksi'  => $this->model_mes->get_transaksi_detail_by_signature($signature_invoice),
            'get_transaksi_sku'  => $this->model_mes->get_transaksi_sku($get_id_transaksi_detail, $olshopid),
            'signature_invoice' => $signature_invoice,
            'signature_sku' => $signature_sku,
            'get_proses_sku'   => $this->model_mes->get_proses_sku($signature_sku)
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/transaksi_sku_edit', $data);
        $this->load->view('mes/footer');
    }

    public function transaksi_sku_edit_proses(){

        // echo "sku_olshop : ".$this->input->post('sku_olshop');
        // echo "qty_sku : ".$this->input->post('qty_sku');
        // echo "signature_sku : ".$this->input->post('signature_sku');
        // echo "signature_invoice : ".$this->input->post('signature_invoice');

        // die;

        $data = [
            'skuid' => $this->input->post('sku_olshop'),
            'qty_sku' => $this->input->post('qty_sku'),
            'created_at'=> $this->model_outlet_transaksi->timezone(),
            'created_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $this->input->post('signature_sku'));
        $this->db->update('mes.t_proses_sku', $data);
        redirect('mes/transaksi_sku_edit/'.$this->input->post('signature_sku').'/'.$this->input->post('signature_invoice'));
    }

    public function transaksi_detail_edit($signature){
        
        $get_no_proses = $this->model_mes->get_transaksi_detail_by_signature($signature)->row()->no_proses;
        $get_signature_header = $this->model_mes->get_transaksi_detail_by_signature($signature)->row()->signature_header;

        $data = [
            'title'      => 'Transaksi / Invoice Header',
            'url'        => 'mes/transaksi_detail_update/',
            'get_transaksi_header'  => $this->model_mes->get_transaksi($get_signature_header),
            'get_transaksi'  => $this->model_mes->get_transaksi_detail_by_signature($signature),
            'get_transaksi_detail'  => $this->model_mes->get_transaksi_detail($get_no_proses),
            'signature' => $signature
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/transaksi_detail_edit', $data);
        $this->load->view('mes/footer');
    }
    
    public function transaksi_detail_update(){
      
        $data = [
            'tgl_invoice'    => $this->input->post('tgl_invoice'),
            'no_invoice'    => $this->input->post('no_invoice'),
            'customer'    => $this->input->post('customer'),
            'kurir'    => $this->input->post('kurir'),
            'no_resi'    => $this->input->post('no_resi'),
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('mes.t_proses_detail', $data);
        redirect('mes/transaksi_detail_edit/'. $this->input->post('signature'));
    }

    

    public function posting(){

        $periode_1 = '1900-00-00';
        $periode_2 = '0000-00-00';

        $data = [
            'title'                 => 'Transaksi / Proses Posting',
            'id'                    => $this->session->userdata('id'),
            // 'get_transaksi'         => $this->model_mes->get_transaksi('', $periode_1, $periode_2),
            'get_transaksi'         => $this->model_mes->get_transaksi('', '', ''),
            // 'get_proses_posting'    => $this->model_mes->get_proses_posting_default(),
            'url'                   => 'mes/posting_search',
            'periode_1'             => $periode_1,
            'periode_2'             => $periode_2,
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/posting', $data);
        $this->load->view('mes/footer');
    }

    public function posting_search(){

        $periode_1 = $this->input->post('periode_1');
        $periode_2 = $this->input->post('periode_2');
        
        if($this->session->flashdata('per_1') && $this->session->flashdata('per_2')){
            $periode_1 = $this->session->flashdata('per_1');
            $periode_2 = $this->session->flashdata('per_2');
        }

        $data = [
            'title'     => 'Transaksi / Proses Posting',
            'id'        => $this->session->userdata('id'),
            'get_transaksi'  => $this->model_mes->get_transaksi('', $periode_1, $periode_2),
            'get_proses_posting'  => $this->model_mes->get_proses_posting_default(),
            'url'       => 'mes/posting_search',
            'periode_1' => $periode_1,
            'periode_2' => $periode_2,
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/posting', $data);
        $this->load->view('mes/footer');
    }

    public function posting_preview($signature){

        $get_posting_preview = $this->model_mes->get_posting_preview($signature);
        foreach ($get_posting_preview->result() as $key) {
            $no_proses = $key->no_proses;
            $storeid = $key->storeid;
            $olshopid = $key->olshopid;
            $created_at = $key->created_at;
            $status_posting = $key->status_posting;
            $tgl_posting = $key->tgl_posting;
            $nama_store = $this->model_mes->get_store_by_storeid($storeid)->row()->nama_store;
            $nama_olshop = $this->model_mes->get_olshop_by_olshopid($olshopid)->row()->nama_olshop;
        }

        $data = [
            'title'     => 'Transaksi / Preview Posting',
            'id'        => $this->session->userdata('id'),
            'get_posting_preview' => $get_posting_preview,
            'no_proses' => $no_proses,
            'storeid' => $storeid,
            'nama_store' => $nama_store,
            'olshopid' => $olshopid,
            'nama_olshop' => $nama_olshop,
            'created_at' => $created_at,
            'updated_at' => $created_at,
            'status_posting' => $status_posting,
            'tgl_posting' => $tgl_posting,
            'signature' => $signature
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/posting_preview', $data);
        $this->load->view('mes/footer');

    }

    public function posting_submit($signature){
        $proses_posting = $this->model_mes->proses_posting($signature);
        redirect('mes/posting_preview/'.$signature);
    }

    public function proses_gudang()
    {
        if($this->input->post('from'))
        {    
            $advanced['from']   = $this->input->post('from');
            $advanced['to']     = $this->input->post('to');
        }else{
            $advanced = null;
        }

        $periode_1 = '1900-00-00';
        $periode_2 = '0000-00-00';

        $data = [
            'title'                 => 'Transaksi / Proses Gudang',
            'id'                    => $this->session->userdata('id'),
            'url_search'            => 'mes/proses_gudang',
            'get_proses_posting'    => $this->model_mes->get_proses_posting('', $periode_1, $periode_2),
            'get_piutang_detail'    => $this->model_mes->get_piutang_detail_search($advanced),
            'from'                  => ($this->input->post('from')) ? $this->input->post('from') : '',
            'to'                    => ($this->input->post('to')) ? $this->input->post('to') : '',
            'url'                   => 'mes/proses_gudang_search',
            'url_update'            => 'mes/proses_gudang_update',
            'periode_1'             => $periode_1,
            'periode_2'             => $periode_2,
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/proses_gudang', $data);
        $this->load->view('mes/footer');
    }

    public function proses_gudang_search(){

        $periode_1 = $this->input->post('periode_1');
        $periode_2 = $this->input->post('periode_2');

        
        if($this->session->flashdata('per_1') && $this->session->flashdata('per_2')){
            $periode_1 = $this->session->flashdata('per_1');
            $periode_2 = $this->session->flashdata('per_2');
        }

        $data = [
            'title'     => 'Transaksi / Proses Gudang',
            'id'        => $this->session->userdata('id'),
            'get_proses_posting'  => $this->model_mes->get_proses_posting('', $periode_1, $periode_2),
            'get_piutang_detail' => $this->model_mes->get_piutang_detail(),
            'url'       => 'mes/proses_gudang_search',
            'url_search'       => 'mes/proses_gudang_search',
            'url_update'=> 'mes/proses_gudang_update',
            'from' => $periode_1,
            'to' => $periode_2,
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/proses_gudang', $data);
        $this->load->view('mes/footer');
    }

    public function proses_gudang_update(){
        $id_postings = $this->input->post('options');

        if (!$this->input->post('options')) {
            redirect('mes/proses_gudang');
        }        

        $signature = md5($this->model_outlet_transaksi->timezone());
        $created_at = $this->model_outlet_transaksi->timezone();

        // $this->db->trans_start();

        foreach($id_postings as $id_posting)
        { 
            $no_proses = $this->db->get_where('mes.t_proses_posting', array('id' => $id_posting))->row()->no_proses;
            $no_pesanan_gudang = $this->db->get_where('mes.t_proses_posting', array('id' => $id_posting))->row()->no_pesanan_gudang;
            $productid = $this->db->get_where('mes.t_proses_posting', array('id'=> $id_posting))->row()->productid;
            $qty = $this->db->get_where('mes.t_proses_posting', array('id' => $id_posting))->row()->qty;
            $nama_product = $this->db->get_where('mes.t_proses_posting', array('id' => $id_posting))->row()->nama_product;


            $data = [
                'id_posting' => $id_posting,
                'no_proses' => $no_proses,
                'productid' => $productid,
                'qty' => $qty,
                'nama_product' => $nama_product,
                'no_pesanan_gudang' => $no_pesanan_gudang,
                'signature' => $signature,
                'created_at'=> $created_at,
                'created_by'=> $this->session->userdata('id'),
            ];

            $this->db->insert('mes.t_proses_gudang_log', $data);
        }

        // $this->db->trans_complete();

        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan proses gudang update. mungkin karena internet. sistem akan melakukan rollback ke sebelum proses gudang";
        //     die;
        // }

        redirect('mes/proses_gudang_preview/'.$signature);

    }   

    public function proses_gudang_preview($signature){
        $data = [
            'title'     => 'Transaksi / Proses Gudang Preview',
            'id'        => $this->session->userdata('id'),
            'get_proses_gudang_log' => $this->model_mes->get_proses_gudang_log($signature),
            'get_proses_gudang_log_group' => $this->model_mes->get_proses_gudang_log_group($signature),
            'signature' => $signature,
            'no_proses' => $this->model_mes->get_proses_gudang_log($signature)->row()->no_proses,
            'url'       => 'mes/proses_gudang_save',
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/proses_gudang_preview', $data);
        $this->load->view('mes/footer');
    }

    public function proses_gudang_save(){
        $signature_gudang = $this->input->post('signature');
        $signature = md5($this->model_outlet_transaksi->timezone());
        $created_at = $this->model_outlet_transaksi->timezone();

        // $this->db->trans_start();

        $no_pesanan_gudang = $this->model_mes->generate_pesanan_gudang($this->input->post('tgl_pesanan_gudang'));

        $header = [
            'tgl_pesanan_gudang'=> $this->input->post('tgl_pesanan_gudang'),
            'no_pesanan_gudang' => $no_pesanan_gudang,
            'no_proses' => $this->input->post('no_proses'),
            'signature'         => $signature,
            'signature_gudang'  => $signature_gudang,
            'created_at'        => $created_at,
            'created_by'        => $this->session->userdata('id'),
        ];

        $proses_header = $this->db->insert('mes.t_proses_piutang', $header);

        $id_piutang = $this->db->insert_id();
        
        // get detail produk gudang

        // $get_gudang_log = $this->model_mes->get_gudang_log($signature_gudang);
        $get_gudang_log = $this->model_mes->get_proses_gudang_log_group($signature_gudang);
        foreach ($get_gudang_log->result() as $a) {

            $detail = [
                'id_piutang'    => $id_piutang,
                'productid'     => $a->productid,
                'nama_product'  => $a->nama_product,
                'qty'           => $a->qty,
                'box'           => $a->box,
                'sachet'        => $a->sachet,
                'signature'     => $signature,
                'created_at'    => $created_at,
                'created_by'    => $this->session->userdata('id')
            ];

            $this->db->insert('mes.t_proses_piutang_detail', $detail);
        }

        // update tabel_proses_posting
        $get_id_posting = $this->model_mes->get_proses_gudang_log($signature_gudang)->result();
        foreach ($get_id_posting as $a) {
            // echo "id_posting : ".$a->id_posting;
            // update proses posting
            $update_posting = [
                'no_pesanan_gudang' => $no_pesanan_gudang,
                'updated_at'        => $created_at,
                'updated_by'        => $this->session->userdata('id')
            ];
            $this->db->where('id', $a->id_posting);
            $this->db->update('mes.t_proses_posting', $update_posting);
        }

        // $this->db->trans_complete();

        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan proses gudang save. mungkin karena internet. sistem akan melakukan rollback ke sebelum proses gudang save";
        //     die;
        // }

        redirect('mes/proses_gudang');

    }

    public function piutang(){
        // $periode_1 = '1900-00-00';
        // $periode_2 = '0000-00-00';

        $data = [
            'title'     => 'Transaksi / Piutang',
            'id'        => $this->session->userdata('id'),
            'get_piutang'  => $this->model_mes->get_piutang(),
            'url'       => 'mes/piutang_proses'
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/piutang', $data);
        $this->load->view('mes/footer');
    }

    public function piutang_proses(){

        if (!$this->input->post('options')) {
            redirect('mes/piutang');
        }     
        
        if (!is_dir('./assets/uploads/mes/')) {
            @mkdir('./assets/uploads/mes/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/mes/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);


        foreach ($this->input->post('options') as $signature) {

            if ($this->input->post('tgl_faktur')) {
                $tgl_faktur_params = $this->input->post('tgl_faktur');
            }else{
                $tgl_faktur_params = $this->model_mes->get_piutang($signature)->row()->tgl_faktur;
            }

            if ($this->input->post('no_faktur')) {
                $no_faktur_params = $this->input->post('no_faktur');
            }else{
                $no_faktur_params = $this->model_mes->get_piutang($signature)->row()->no_faktur;
            }

            if ($this->input->post('nilai_faktur')) {
                $nilai_faktur_params = $this->input->post('nilai_faktur');
            }else{
                $nilai_faktur_params = $this->model_mes->get_piutang($signature)->row()->nilai_faktur;
            }

            if ($this->input->post('tgl_bayar')) {
                $tgl_bayar_params = $this->input->post('tgl_bayar');
            }else{
                $tgl_bayar_params = $this->model_mes->get_piutang($signature)->row()->tgl_bayar;
            }

            if ($this->input->post('bayar')) {
                $bayar_params = $this->input->post('bayar');
                $status_bayar = 1;
            }else{
                $bayar_params = $this->model_mes->get_piutang($signature)->row()->bayar;
                $status_bayar = $this->model_mes->get_piutang($signature)->row()->status_bayar;
            }

            if ($this->input->post('transfer')) {
                $transfer_params = $this->input->post('transfer');
            }else{
                $transfer_params = $this->model_mes->get_piutang($signature)->row()->transfer;
            }

            if ($this->upload->do_upload('bukti_transfer')) 
            {
                $upload_data = $this->upload->data();
                $bukti_transfer_params = $upload_data['orig_name'];
            }else{
                $bukti_transfer_params = $this->model_mes->get_piutang($signature)->row()->bukti_transfer;
            }

            $data = [
                'tgl_faktur'   => $tgl_faktur_params,
                'no_faktur'    => $no_faktur_params,
                'nilai_faktur' => $nilai_faktur_params,
                'tgl_bayar'    => $tgl_bayar_params,
                'bayar'        => $bayar_params,
                'status_bayar' => $status_bayar,
                'transfer'     => $transfer_params,
                'bukti_transfer'=> $bukti_transfer_params,
                'updated_at'   => $this->model_outlet_transaksi->timezone()
            ];

            $this->db->where('signature', $signature);
            $this->db->update('mes.t_proses_piutang', $data);
        }

        redirect('mes/piutang');

    }

    public function piutang_detail($signature){
        $data = [
            'title'       => 'Transaksi / Piutang Detail',
            'id'          => $this->session->userdata('id'),
            'tgl_pesanan_gudang' => $this->model_mes->get_piutang($signature)->row()->tgl_pesanan_gudang,
            'no_pesanan_gudang'  => $this->model_mes->get_piutang($signature)->row()->no_pesanan_gudang,
            'email_at'           => $this->model_mes->get_piutang($signature)->row()->email_at,
            'email_to'           => $this->model_mes->get_piutang($signature)->row()->email_to,
            'get_piutang_detail' => $this->model_mes->get_piutang_detail($signature),
            'get_piutang_detail' => $this->model_mes->get_piutang_detail($signature),
            'signature'   => $signature,
            'url'         => 'mes/piutang_email',
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/piutang_detail', $data);
        $this->load->view('mes/footer');
    }

    public function piutang_email(){

        $this->load->model('model_relokasi');
        $this->model_relokasi->email();
        $signature_piutang = $this->input->post('signature');
        $from = 'suffy@muliaputramandiri.net';
        $to = $this->input->post('email_to');
        $cc = 'suffy.mpm@gmail.com';

        $no_pesanan_gudang = $this->model_mes->get_piutang($signature_piutang)->row()->no_pesanan_gudang;
        $signature = $this->model_mes->get_piutang($signature_piutang)->row()->signature;

        $subject = "Faktur MES (MPM E-commerce System | ".$no_pesanan_gudang;

        $data = [
            'no_pesanan_gudang' => $no_pesanan_gudang,
            'olshop' => $this->model_mes->get_olshop_store($no_pesanan_gudang)->row()->nama_olshop,
            'store'  => $this->model_mes->get_olshop_store($no_pesanan_gudang)->row()->nama_store,
            'signature' => $signature,
            'piutang_detail'    => $this->model_mes->get_piutang_detail($signature_piutang)
        ];
        $message = $this->load->view("mes/email_npg",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            $update = [
                'email_at'  => $this->model_outlet_transaksi->timezone(),
                'email_to'  => $to,
                'status_email'  => 1
            ];
            $this->db->where('signature', $signature_piutang);
            $this->db->update('mes.t_proses_piutang', $update);
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('mes/piutang_detail/'.$signature,'refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('mes/piutang_detail/'.$signature,'refresh');
        }

    }

    public function piutang_konfirmasi($signature){
        
        $no_pesanan_gudang = $this->model_mes->get_piutang($signature)->row()->no_pesanan_gudang;
        $data = [
            'title'       => 'Transaksi / Proses Konfirmasi Faktur',
            'id'          => $this->session->userdata('id'),
            'no_pesanan_gudang' => $no_pesanan_gudang,
            'olshop' => $this->model_mes->get_olshop_store($no_pesanan_gudang)->row()->nama_olshop,
            'store'  => $this->model_mes->get_olshop_store($no_pesanan_gudang)->row()->nama_store,
            'tgl_faktur'  => $this->model_mes->get_piutang($signature)->row()->tgl_faktur,
            'no_faktur'  => $this->model_mes->get_piutang($signature)->row()->no_faktur,
            'nilai_faktur'  => $this->model_mes->get_piutang($signature)->row()->nilai_faktur,
            'konfirmasi_faktur_at'  => $this->model_mes->get_piutang($signature)->row()->konfirmasi_faktur_at,
            'status_konfirmasi_faktur'  => $this->model_mes->get_piutang($signature)->row()->status_konfirmasi_faktur,
            'detail' => $this->model_mes->get_piutang_detail($signature),
            'signature'   => $signature,
            'url'         => 'mes/piutang_konfirmasi_proses',
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/piutang_konfirmasi', $data);
        $this->load->view('mes/footer');
    }

    public function piutang_konfirmasi_proses(){
        $updated_at = $this->model_outlet_transaksi->timezone();
        $data = [
            'tgl_faktur'   => $this->input->post('tgl_faktur'),
            'no_faktur'    => $this->input->post('no_faktur'),
            'nilai_faktur' => $this->input->post('nilai_faktur'),
            'updated_at'   => $updated_at,
            'konfirmasi_faktur_at'   => $updated_at,
            'status_konfirmasi_faktur' => 1,
            'konfirmasi_faktur_by'   => $this->session->userdata('id'),
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('mes.t_proses_piutang', $data);

        redirect('mes/piutang_konfirmasi/'.$this->input->post('signature'));
    }

    public function pdf_gudang($signature){
        $this->load->library('mypdf');

        $no_proses = $this->model_mes->get_piutang($signature)->row()->no_proses;
        $data = [
            'npg'   => $this->model_mes->get_piutang($signature)->row()->no_pesanan_gudang,
            'tgl_pesanan_gudang'   => $this->model_mes->get_piutang($signature)->row()->tgl_pesanan_gudang,
            'nama_store'   => $this->model_mes->get_proses_master_by_npg($no_proses)->row()->nama_store,
            'nama_olshop'   => $this->model_mes->get_proses_master_by_npg($no_proses)->row()->nama_olshop,
            'detail' => $this->model_mes->get_piutang_detail($signature)
        ];

        $generate_pdf = $this->mypdf->generate('mes/npg',$data,'npg','A4','portrait');

    }

    public function raw_data(){

        $periode_1 = '1900-00-00';
        $periode_2 = '0000-00-00';

        $signature = md5($this->model_outlet_transaksi->timezone());

        $data = [
            'title'     => 'Report / Raw Data',
            'id'        => $this->session->userdata('id'),
            'get_raw_data'  => $this->model_mes->get_raw_data($periode_1, $periode_2, $signature),
            'url'       => 'mes/raw_data_search',
            'periode_1' => $periode_1,
            'periode_2' => $periode_2,
            'signature' => $signature
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/raw_data', $data);
        $this->load->view('mes/footer');

    }

    public function raw_data_search(){

        $periode_1 = $this->input->post('periode_1');
        $periode_2 = $this->input->post('periode_2');
        // $signature = $this->input->post('signature');

        // echo 'periode_1 :'.$periode_1; die;
        $signature = md5($this->model_outlet_transaksi->timezone());
        
        if($this->session->flashdata('per_1') && $this->session->flashdata('per_2')){
            $periode_1 = $this->session->flashdata('per_1');
            $periode_2 = $this->session->flashdata('per_2');
        }

        $data = [
            'title'     => 'Report / Raw Data',
            'id'        => $this->session->userdata('id'),
            'get_raw_data'  => $this->model_mes->get_raw_data($periode_1, $periode_2, $signature),
            'url'       => 'mes/raw_data_search',
            'periode_1' => $periode_1,
            'periode_2' => $periode_2,
            'signature' => $signature,
        ];
        $this->load->view('mes/header');
        $this->load->view('mes/raw_data', $data);
        $this->load->view('mes/footer');
    }

    public function export_raw_data($signature){        
        $query = "
            select * 
            from mes.report_raw_data a 
            where a.signature = '$signature'
            ORDER BY a.no_pesanan_gudang, a.no_invoice, a.skuid, a.productid
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Report Raw Data.csv');
    }

    public function template_transaksi(){        
        // $query = "
        //     select  '' as tanggal, '' as invoice, '' as pembeli, '' as kurir, '' as resi, '' as skuid, '' as qty_sku
        // ";

        // $hsl = $this->db->query($query);
        // query_to_csv($hsl,TRUE,'Template.csv');

        $query = "
            select '' as tanggal, '' as invoice, '' as pembeli, '' as kurir, '' as resi, '' as skuid, '' as qty_sku, '' as harga
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'tanggal(m/d/y)', 'invoice', 'pembeli', 'kurir', 'resi', 'skuid', 'qty_sku', 'harga'
        ));
        $this->excel_generator->set_column(array
        ( 
            'tanggal', 'invoice', 'pembeli', 'kurir', 'resi', 'skuid', 'qty_sku', 'harga'
        ));
        $this->excel_generator->set_width(array(10, 10, 20, 20, 20, 20, 20, 20)); 
        $this->excel_generator->exportTo2007('Template MES 2025'); 

    }

    public function import_transaksi(){

        $store = $this->input->post('store');
        $olshop = $this->input->post('olshop');
        
        if (!is_dir('./assets/uploads/mes/import/')) {
            @mkdir('./assets/uploads/mes/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/mes/import/';
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

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/mes/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('mes','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    // $tanggal = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $tanggal = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $unix_date = ($tanggal - 25569) * 86400;
                    $excel_date = 25569 + ($unix_date / 86400);
                    $unix_date = ($excel_date - 25569) * 86400;
                    $tanggal_final = gmdate("Y-m-d", $unix_date);



                    $invoice = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                    // echo "invoice : ".$invoice;

                    // die;

                    $pembeli = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    // $storeid = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    // $olshopid = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $kurir = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $resi = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $skuid = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $qty_sku = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $harga = $worksheet->getCellByColumnAndRow(7, $row)->getValue();

                    if($tanggal == null || $tanggal == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom Tanggal, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if($invoice == null || $invoice == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom Invoice, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if($pembeli == null || $pembeli == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom Pembeli, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }                    

                    if($kurir == null || $kurir == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom Kurir, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if($skuid == null || $skuid == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom SkuId, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if($qty_sku == null || $qty_sku == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom QtySku, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }          
                    
                    if($harga == null || $harga == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom Harga, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    $data = [
                        // 'tanggal'      => date('Y-m-d',strtotime($tanggal)),
                        'tanggal'      => $tanggal_final,
                        'invoice'      => $invoice,
                        'pembeli'      => $pembeli,
                        'storeid'      => $store,
                        'olshopid'     => $olshop,
                        'kurir'        => $kurir,
                        'resi'         => $resi,
                        'skuid'        => $skuid,
                        'qty_sku'      => $qty_sku,
                        'harga'        => $harga,
                        'created_at'   => $created_at,
                        'created_by'   => $this->session->userdata('id'),
                        'signature'    => $signature
                    ];
                    $this->db->insert('mes.t_import_draft',$data);
                    // echo 'a'; die;
                }
            }
        }else{
           
        };

        redirect('mes/draft_import/'.$signature);
    }

    public function draft_import($signature)
    {
        // $no_pesanan_gudang = $this->model_mes->get_piutang($signature)->row()->no_pesanan_gudang;
        $data = [
            'title'       => 'Transaksi / Draft Import',
            'id'          => $this->session->userdata('id'),
            'get_import'  => $this->model_mes->get_draft_import($signature, ''),
            'signature'   => $signature,
            'url'         => 'mes/import_draft_commit',
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/draft_import', $data);
        $this->load->view('mes/footer');
    }

    public function import_draft_commit(){
        $id_imports = $this->input->post('options');
        $signature_import = $this->input->post('signature');

        if (!$this->input->post('options')) {
            redirect('mes/draft_import/'.$signature_import);
        }        

        $signature = md5($this->model_outlet_transaksi->timezone());
        $created_at = $this->model_outlet_transaksi->timezone();

        // var_dump($id_imports);
        // echo "<br>signature_import = $signature_import";

        // die;

        foreach($id_imports as $id_import)
        {
            $get_data_draft = $this->model_mes->get_draft_import($signature_import, $id_import);
            foreach ($get_data_draft->result() as $key) {
                $tanggal = $key->tanggal;
                $invoice = $key->invoice;
                $pembeli = $key->pembeli;
                $storeid = $key->storeid;
                $olshopid = $key->olshopid;
                $kurir = $key->kurir;
                $resi = $key->resi;
                $skuid = $key->skuid;
                $qty_sku = $key->qty_sku;
                $harga = $key->harga;
            }
            $data = [
                'tanggal'       => $tanggal,
                'invoice'       => $invoice,
                'pembeli'       => $pembeli,
                'storeid'       => $storeid,
                'olshopid'      => $olshopid,
                'kurir'         => $kurir,
                'resi'          => $resi,
                'skuid'         => $skuid,
                'qty_sku'       => $qty_sku,
                'harga'         => $harga,
                'signature'     => $signature_import,
                'created_at'    => $created_at,
                'created_by'    => $this->session->userdata('id'),
                'signature'     => $signature
            ];
            $this->db->insert('mes.t_import_preview_temp', $data);
        }

        $this->model_mes->insert_temp_to_preview($signature);
        redirect('mes/preview_import/'.$signature);
    }


    public function preview_import($signature)
    {
        // $no_pesanan_gudang = $this->model_mes->get_piutang($signature)->row()->no_pesanan_gudang;
        $data = [
            'title'       => 'Transaksi / Preview Import',
            'id'          => $this->session->userdata('id'),
            'get_import'  => $this->model_mes->get_preview_import($signature),
            'signature'   => $signature,
            'url'         => 'mes/commit_import',
        ];

        $this->load->view('mes/header');
        $this->load->view('mes/preview_import', $data);
        $this->load->view('mes/footer');
    }

    public function commit_import(){
        // echo 'a'; die;
        $id_imports = $this->input->post('options');
        $signature_import = $this->input->post('signature');

        if (!$this->input->post('options')) {
            redirect('mes/draft_import/'.$signature_import);
        } 

        $get_data = $this->model_mes->get_preview_import($signature_import);
        foreach ($get_data->result() as $key) {
            $storeid = $key->storeid;
            $olshopid = $key->olshopid;
        }

        $tgl_proses = date('Y-m-d');
        $no_proses = $this->model_mes->generate_transaksi($tgl_proses);

        $header = [
            'tgl_proses'    => $tgl_proses,
            'no_proses'     => $no_proses,
            'storeid'       => $storeid,
            'olshopid'      => $olshopid,
            'signature'     => md5($this->model_outlet_transaksi->timezone()),
            'signature_import'  => $signature_import,
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $this->session->userdata('id')
        ];

        $this->db->insert('mes.t_proses_master', $header);
        $id_header = $this->db->insert_id();
        // $no_proses = $this->model_mes->get_proses_master_by_id($id_header)->row()->no_proses;
        $get_detail = $this->model_mes->get_preview_import_group_invoice($signature_import)->result();

        foreach ($get_detail as $a) {
            $detail = [
                'no_proses'   => $no_proses,
                'no_invoice'  => $a->invoice,
                'tgl_invoice' => $a->tanggal,
                'customer'    => $a->pembeli,
                'kurir'       => $a->kurir,
                'no_resi'     => $a->resi,
                'total_harga' => $a->total_harga,
                'qty_sku'     => $a->total_qty,
                'signature'   => md5($this->model_outlet_transaksi->timezone()),
                'created_at'  => $this->model_outlet_transaksi->timezone(),
                'created_by'  => $this->session->userdata('id')
            ];

            $this->db->insert('mes.t_proses_detail', $detail);
            $id_invoice = $this->db->insert_id();

            // get id_invoice


            // get skuid by no_invoice
            $get_skuid = $this->model_mes->get_import_preview_by_invoice_n_signature($a->invoice, $signature_import)->result();
            // var_dump($get_skuid);

            foreach ($get_skuid as $a) {
                
                $sku = [
                    'id_invoice'    => $id_invoice,
                    'skuid'         => $a->skuid,
                    'qty_sku'       => $a->qty_sku,
                    'harga'         => $a->harga,
                    'signature'     => md5(rand().$this->model_outlet_transaksi->timezone()),
                    'created_at'    => $this->model_outlet_transaksi->timezone(),
                    'created_by'    => $this->session->userdata('id')
                ];

                $this->db->insert('mes.t_proses_sku', $sku);
                
            }
            // echo 'd';die;
        }
    
        redirect('mes/transaksi');

    }

    public function proses_gudang_delete($signature){
        
        $data = [
            'updated_at'=> $this->model_outlet_transaksi->timezone(),
            'updated_by'=> $this->session->userdata('id'),
            'deleted_at'=>$this->model_outlet_transaksi->timezone(),
            'deleted_by'=> $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('mes.t_proses_piutang', $data);
        redirect('mes/proses_gudang');
    }

    public function update_status_gimmick()
    {
        $this->model_mes->update_status_gimmick();
        echo "<script>alert('update status gimmick selesai'); </script>";
        redirect('mes/product','refresh');
        
    }


}
?>
