<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Broadcast extends MY_Controller
{
    
    function broadcast()
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
        $this->load->model(array('M_menu','model_broadcast','model_outlet_transaksi','M_import'));
        $this->load->database();
    }

    function index()
    {

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Broadcast Whatsapp',
            'get_label'   => $this->M_menu->get_label(),
            'get_contact' => $this->model_broadcast->get_contact()->result(), 
            'url'         => 'broadcast/preview_broadcast'
        ];

        // var_dump($data['get_contact']);
        // die;
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('broadcast/dashboard', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function preview_broadcast(){
        // $message = $this->input->post('message');
        // echo "message : ".$message;

        $created_by = $this->session->userdata('id');
        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Broadcast Whatsapp',
            'get_label'   => $this->M_menu->get_label(),
            'get_contact' => $this->model_broadcast->get_contact()->result(), 
            'url'         => 'broadcast/send_broadcast',

            'message'       => $this->input->post('message'),
            'get_contact'   => $this->model_broadcast->get_contact()->result(),
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $created_by
        ];
        
        $signature = $this->model_broadcast->insert_broadcast($data);

        // echo "insert_broadcast : ".$insert_broadcast;
        // die;

        $update = $this->model_broadcast->update_broadcast($signature);


        $data = [
            'title'       => 'Broadcast Whatsapp',
            'get_label'   => $this->M_menu->get_label(),
            'url'         => 'broadcast/send_broadcast',
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $created_by,
            'get_preview'   => $this->model_broadcast->get_broadcast_preview($signature)->result(),
            'signature'     => $this->model_broadcast->get_broadcast_preview($signature)->row()->signature
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('broadcast/preview', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function send_broadcast($signature){
        $data = [
            'get_preview' => $this->model_broadcast->get_broadcast_preview($signature)->result()
        ];
        $send_broadcast = $this->model_broadcast->send_broadcast($data);



    }

    public function import_proses()
    {
        if (!is_dir('./assets/file/broadcast_kontak/')) {
            @mkdir('./assets/file/broadcast_kontak/', 0777);
        }

        $id = $this->session->userdata('id');
        $date = $this->model_outlet_transaksi->timezone();
        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/file/broadcast_kontak';
        $config['allowed_types'] = '*';
        $config['max_size']  = '*';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        // Load konfigurasi uploadnya
        if($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/file/broadcast_kontak/$filename");

            // ------------------------------------ kontak --------------------------------------
            foreach($object->getWorksheetIterator() as $worksheet)
                {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++)
                    {
                        $nama = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $no_wa = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                        $data = [
                            'nama' => $nama,
                            'no_wa' => '0'.$no_wa,
                            'created_at' => $date,
                            'created_by' => $id,
                        ];
                        $insert = $this->M_import->insert("site.t_broadcast_contact",$data);
                    }      
                }
                echo "<script>alert('Import Berhasil !'); </script>";
                redirect("broadcast",'refresh');
        }else{
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            redirect('broadcast');
        }
    }

    public function clear_contact()
    {
        $id = $this->session->userdata('id');
        $this->db->where('created_by', $id);
        $this->db->delete('site.t_broadcast_contact');

        redirect('broadcast');
    }

    public function warpin_eligible()
    {
        $get_cabang = $this->model_broadcast->get_cabang()->result();

        foreach ($get_cabang as $key) {
            $latitude = $key->latitude;
            $longitude = $key->longitude;
            $kodepos = $key->kodepos;

            $data = [
                "latitude" => $latitude,
                "longitude" => $longitude,
                "kodepos" => $kodepos,
            ];
            $this->model_broadcast->warpin_eligible($data);
        }
    }

    // public function warpin_store_generate(){

    //     $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
    //     foreach ($get_cabang as $key) {

    //         echo "proses generate ... ".$key->site_code;
    //         $this->warpin_store($key->site_code);
    //         echo "<hr>";

    //     }
    // }

    public function warpin_store()
    {
        $get_cabang = $this->model_broadcast->get_cabang()->result();

        foreach ($get_cabang as $key) {
            $originalid = $key->site_code;
            $company_id = '4';
            $name = $key->company;
            $latitude = $key->latitude;
            $longitude = $key->longitude;
            $address = $key->alamat;
            $contact_name = $key->contact_name;
            $contact_msisdn = $key->contact_msisdn;
            $sla_delivery = $key->sla_delivery;

            $data = [
                "original_id" => $originalid,
                "company_id" => $company_id,
                "name" => $name,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "address" => $address,
                "contact_name" => $contact_name,
                "contact_msisdn" => $contact_msisdn,
                "sla_delivery" => $sla_delivery,
                "extra"         => [
                    "distributor_id" => "mpm"
                ]
            ];

            echo json_encode($data);
            // die;
            $proses = $this->model_broadcast->warpin_store($data);
            echo "<pre>";
            var_dump($proses);
            echo "</pre>";
        }
    }

    public function warpin_product_generate_update(){

        $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        foreach ($get_cabang as $key) {

            echo "proses generate ... ".$key->site_code;
            $this->warpin_product_update($key->site_code);
            echo "<hr>";

        }
    }

    public function warpin_product_update($site_code)
    {
        // $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        // foreach ($get_cabang as $key) {

            $get_product_site = $this->model_broadcast->get_product_site($site_code)->result();

            foreach ($get_product_site as $key) {
                $get_satuan = $this->model_broadcast->get_satuan($key->kodeprod)->row();

                
                $datas[] = [
                    "sku"               => $key->kode_prc,
                    "id"                => (int)$key->kodeprod,
                    "name"              => $key->namaprod,
                    "image_url"         => $key->apps_images,
                    "is_active"         => ($key->status_aktif) == 1 ? true : false,
                    "price"             => (float)$key->harga_jual_warpin,
                    "promotion_price"   => (float)$key->harga_jual_warpin,
                    "quantity"          => 100,
                    "uom"   => [
                        'id'            => (int)$key->id_satuan_jual_warpin,
                        'name'          => $key->satuan_jual_warpin,
                        'description'   => '',
                    ],
                    "uom_old"   => [
                        'id'            => (int)$key->id_satuan_jual_warpin,
                        'name'          => $key->satuan_jual_warpin,
                        'description'   => '',
                    ],
                    "category" => [
                        "id"            => 1,
                        "name"          => $key->nama_group,
                        "description"   => $key->nama_sub_group,
                    ]
                ];
            }

            $data = [
                "store" =>
                [
                    "id"            => $key->site_code,
                    "company_id"    => 4,
                    "name"          => $key->company
                ], "products" => $datas
            ];

            echo json_encode($data);
            // echo "<hr>";
            // die;
            // echo "<pre>";
            // var_dump($data);
            // echo "</pre>";
            // echo "<hr>";

            // die;
            $proses = $this->model_broadcast->warpin_product_update($data);
            // echo "<pre>";
            // var_dump($proses);
            // echo "</pre>";
        // }
    }

    public function warpin_product_generate(){

        $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        foreach ($get_cabang as $key) {

            echo "proses generate ... ".$key->site_code;
            $this->warpin_product($key->site_code);
            echo "<hr>";

        }
    }

    public function warpin_product_generate_new(){

        
        $get_cabang = $this->model_broadcast->get_cabang()->result();
        foreach ($get_cabang as $key) {

            $signature = md5($this->model_outlet_transaksi->timezone()).'-product-'.$key->site_code;
            $this->warpin_product_new($key->site_code, $signature);
        }
    }

    public function warpin_product_new($site_code,$signature)
    {
        $get_detail_product = $this->model_broadcast->get_detail_product($site_code, 'product')->result();
        foreach ($get_detail_product as $key) {            

            $data_log = [
                "endpoint"  => "product",
                "kodeprod"  => $key->kodeprod,
                "flag_proses"  => 0,
                "created_at"    => $this->model_outlet_transaksi->timezone(),
                "created_by"    => $this->session->userdata('id'),
                "signature"     => $signature
            ];

            $this->db->insert('site.log_warpin_api_new', $data_log);

            $datas[] = [
                "sku"                   => $key->kodeprod,
                "name"                  => $key->apps_namaprod,
                "status"                => ($key->status_aktif_warpin == 1) ? "Enabled" : "Disabled" ,
                "price"                 => (float)$key->harga_jual_warpin,
                "minimum_order_quantity"=> 1,
                "category_id"           => 17,
                "description"           => str_replace("\r\n", "", $key->apps_deskripsi),
                "important_information" => '-',
                "images"                => [
                    $key->apps_images
                ],
                "shipping_id" => [1],
                "dimension_package_height" => null,
                "dimension_package_length" => null,
                "dimension_package_width" => null,
                "weight" => 1,
                "display_windows" => [],
                "variants" => [
                    [
                        "sku"       => $key->kodeprod."-dus",
                        "price"     => (float)$key->harga_jual_warpin,
                        "weight"    => 1,
                        "status"    => ($key->status_aktif_warpin == 1) ? "Enabled" : "Disabled" ,
                        "image"     => $key->apps_images,
                        "variant_attributes" => [
                            [
                                "attribute_code"    => "uom",
                                "attribute_value"   => "dus"
                            ]
                        ]
                    ]
                ]                
            ];
        }

        $data = [
            "products" => $datas
        ];

        echo json_encode($data);

        $get_token = $this->db->get_where('site.m_site_warpin', array(
            'site_code' => $site_code
        ))->row();

        $token = $get_token->token;

        // echo "site_code : ".$site_code;
        // echo "token : ".$token;
        

        
        $proses = $this->model_broadcast->warpin_product_new($data, $signature, $token);
        // echo "<pre>";
        // var_dump($proses);
        // echo "</pre>";
    
    }

    public function warpin_product($site_code)
    {
        // $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        // foreach ($get_cabang as $key) {

            $get_product_site = $this->model_broadcast->get_product_site($site_code)->result();

            foreach ($get_product_site as $key) {
                $get_satuan = $this->model_broadcast->get_satuan($key->kodeprod)->row();

                
                $datas[] = [
                    "sku"               => $key->kode_prc,
                    "id"                => (int)$key->kodeprod,
                    "name"              => $key->namaprod,
                    "image_url"         => $key->apps_images,
                    "is_active"         => ($key->status_aktif) == 1 ? true : false,
                    "price"             => (float)$key->harga_jual_warpin,
                    "promotion_price"   => (float)$key->harga_jual_warpin,
                    "quantity"          => 100,
                    "uom"   => [
                        'id'            => (int)$key->id_satuan_jual_warpin,
                        'name'          => $key->satuan_jual_warpin,
                        'description'   => '',
                    ],
                    "uom_old"   => [
                        'id'            => (int)$key->id_satuan_jual_warpin,
                        'name'          => $key->satuan_jual_warpin,
                        'description'   => '',
                    ],
                    "category" => [
                        "id"            => 1,
                        "name"          => $key->nama_group,
                        "description"   => $key->nama_sub_group,
                    ]
                ];
            }

            $data = [
                "store" =>
                [
                    "id"            => $key->site_code,
                    "company_id"    => 4,
                    "name"          => $key->company
                ], "products" => $datas
            ];

            echo json_encode($data);
            // echo "<hr>";
            // die;
            // echo "<pre>";
            // var_dump($data);
            // echo "</pre>";
            // echo "<hr>";

            // die;
            $proses = $this->model_broadcast->warpin_product($data);
            // echo "<pre>";
            // var_dump($proses);
            // echo "</pre>";
        // }
    }

    public function warpin_stock_generate(){

        $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        foreach ($get_cabang as $key) {

            // echo "proses generate ... ".$key->site_code;
            $this->warpin_stock($key->site_code);
            // echo "<hr>";

        }
    }

    public function warpin_stock_new(){

        $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        foreach ($get_cabang as $key) {

            $signature = md5($this->model_outlet_transaksi->timezone()).'-stock-'.$key->site_code;
            $this->warpin_stock_generate_new($key->site_code, $signature);

        }
    }

    public function warpin_stock_generate_new($site_code, $signature)
    {
        $tahun = date('Y');

        $get_token = $this->db->get_where('site.m_site_warpin', array(
            'site_code' => $site_code
        ))->row();

        $token = $get_token->token;
        $loc_code = $get_token->loc_code;
        $vendor_code = $get_token->vendor_code;


        $get_detail_product = $this->model_broadcast->get_detail_product($site_code, 'stock')->result();
        
        foreach ($get_detail_product as $key) {        

            $data_log = [
                "endpoint"  => "stock",
                "kodeprod"  => $key->kodeprod,
                "flag_proses"  => 0,
                "created_at"    => $this->model_outlet_transaksi->timezone(),
                "created_by"    => $this->session->userdata('id'),
                "signature"     => $signature
            ];

            $this->db->insert('site.log_warpin_api_new', $data_log);
        
            // var_dump($key);
            $datas[] = [
                "loc_code"  => $loc_code,
                "sku"       => $key->kodeprod."-dus",
                "qty"       => 100,
            ];

        }

        
        $data = [
            "stocks"   => $datas
        ];

        // echo "site_code : ".$site_code;
        // die;

        echo json_encode($data);

        
        // die;
        $proses = $this->model_broadcast->warpin_stock($data, $token, $signature);
        echo "<pre>";
        var_dump($proses);
        echo "</pre>";
    }

    // public function order_confirmation($id_order_status = ''){
    public function order_confirmation($signature = ''){
        
        $get_data_order = $this->model_broadcast->get_data_order('site.t_erp_order_status', $signature)->result();
        
        // die;
        foreach ($get_data_order as $key) {
            $from_aplikasi = $key->from_aplikasi;
        }

        // echo $from_aplikasi;
        if ($from_aplikasi == 'WARPIN') {
            $this->warpin_confirmation($get_data_order);
        }

    }

    public function warpin_confirmation($get_data_order)
    {
        foreach ($get_data_order as $key) {
            $status = $key->status_erp;
            $invoice_aplikasi = $key->invoice_aplikasi;
            $created_at = $key->created_at;
            $created_at_custom = date("Y-m-d", strtotime($key->created_at)).'T'.date("h:i:s", strtotime($key->created_at));
            $id_status = $key->id;
        }

        // mencari nama status
        $get_nama_status_order = $this->model_broadcast->get_nama_status_order($status);
        // mencari entity id
        $get_entity_id = $this->db->get_where('site.t_warpin_order', array('order_number' => $invoice_aplikasi))->row();
        $entity_id = $get_entity_id->entity_id;
        $loc_code = $get_entity_id->loc_code;
        $vendor_code = $get_entity_id->vendor_code;

        // echo "loc_code : ".$loc_code;
        // echo "vendor_code : ".$vendor_code;
        $get_token = $this->db->get_where('site.m_site_warpin', array(
            'loc_code' => $loc_code,
            'vendor_code' => $vendor_code
        ))->row();

        $token = $get_token->token;
        // echo "token : ".$token;
        // die;

        $signature = md5($this->model_outlet_transaksi->timezone()).'-order.confirm';
        // die;

        // echo "status : ".$status;
        // die;

        if ($status == 2 || $status == 3 || $status == 4) {
            $data = [
                "orders" => [
                    [
                        "order_id" => $entity_id
                    ]
                ]
            ];
            $endpoint = "confirm";
        }elseif($status == 5){
            // return 'order.delivery';
            $endpoint = "ship";
            $data = [
                "orders" => [
                    [
                        "order_id" => $entity_id,
                        "track_number" => null
                    ]
                ]
            ];

        }elseif($status == 6){
            $endpoint = "delivered";
            $data = [
                "orders" => [
                    [
                        "order_id" => $entity_id
                    ]
                ]
            ];

        }elseif($status == 7){
            $endpoint = "canceldelivery";
            $data = [
                "orders" => [
                    [
                        "order_id" => $entity_id
                    ]
                ]
            ];
        }elseif($status == 9){
            $endpoint = "cancel";
            $data = [
                "orders" => [
                    [
                        "order_id" => $entity_id
                    ]
                ]
            ];
        }

        echo json_encode($data);
        // die;

        $data_log = [
            "endpoint"      => $endpoint,
            "kodeprod"      => null,
            "flag_proses"   => 0,
            "created_at"    => $this->model_outlet_transaksi->timezone(),
            "created_by"    => $this->session->userdata('id'),
            "signature"     => $signature
        ];

        $this->db->insert('site.log_warpin_api_new', $data_log);

        $proses = $this->model_broadcast->warpin_confirmation($data, $signature, $endpoint, $token);
        echo "<pre>";
        var_dump($proses);
        echo "</pre>";
    }    
    
}
?>
