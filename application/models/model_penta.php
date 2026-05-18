<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_penta extends CI_Model 
{
    public function __construct() 
    {
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->userid = '999';
    }
    public function get_token($tipe_token = null)
    {
        $url_penta_verify = getenv('PENTA_API').'verify';

        if($tipe_token == 'batam')
        {
            // $penta_token = getenv('PENTA_TOKEN_FOR_BATAM');
            $penta_token = getenv('PENTA_2026');
        }elseif($tipe_token == 'gt')
        {
            // $penta_token = getenv('PENTA_TOKEN_FOR_GT');
            $penta_token = getenv('PENTA_2026');
        }elseif($tipe_token == 'penta_sales')
        {
            // $penta_token = getenv('PENTA_TOKEN');
            $penta_token = getenv('PENTA_2026');
        }else{
            // $penta_token = getenv('PENTA_TOKEN');
            $penta_token = getenv('PENTA_2026');
        }

        // echo "tipe token : ".$tipe_token;
        // echo "penta token : ".$penta_token;
        // die;

        $curl = curl_init();

        $authorization = "Authorization: Bearer $penta_token";

        curl_setopt($curl, CURLOPT_URL, $url_penta_verify);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $array_response = json_decode($result, true);
        return $array_response;

    }

    public function insert_token($data)
    {
        $this->db->insert('site.penta_token', $data);
        return $this->db->insert_id();
    }

    public function get_penta_token($id = "")
    {
        if ($id) {
            $params_id = "where a.id = '$id'";
        }else{
            $params_id = "";
        }

        $query = "
            select a.*, b.username
            from site.penta_token a left join site.master_user b 
                on a.created_by = b.id
            $params_id
            ORDER BY a.id desc
            limit 10
        ";
        return $this->db->query($query);
    }

    public function get_token_active()
    {
        $query = "
            select *
            from site.penta_token a 
            ORDER BY a.id desc 
            limit 1
        ";
        return $this->db->query($query);
    }

    public function get_penta_sales($token, $tahun, $bulan, $signature, $id_log)
    {
        $url_penta = getenv('PENTA_API').'list/sales/'.$tahun.'/'.$bulan;
        $curl = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($curl, CURLOPT_URL, $url_penta);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $array_response = json_decode($result, true);
        // echo "<pre>";
        // print_r($array_response);
        // echo "</pre>";

        if (isset($array_response['data'])) 
        {

            foreach ($array_response['data'] as $key) 
            {
                $bulan = $key["bulan"];
                $tahun = $key["tahun"];
                $principal_id = $key["principal_id"];
                $area_id = $key["area_id"];
                $nama_area = $key["nama_area"];
                $tanggal_invoice = $key["tanggal_invoice"];
                $nomor_invoice = $key["nomor_invoice"];
                $nomor_sales_order = $key["nomor_sales_order"];
                $customer_po_number = $key["customer_po_number"];
                $kode_outlet = $key["kode_outlet"];
                $kode_outlet_lama = $key["kode_outlet_lama"];
                $nama_outlet = $key["nama_outlet"];
                $category_produk = $key["category_produk"];
                $sales_order_line = $key["sales_order_line"];
                $kode_produk = $key["kode_produk"];
                $kode_produk_lama = $key["kode_produk_lama"];
                $inventory_item_id = $key["inventory_item_id"];
                $item_id_vend = $key["item_id_vend"];
                $id_item_sapora = $key["id_item_sapora"];
                $category_product_principal = $key["category_product_principal"];
                $nama_produk = $key["nama_produk"];
                $qty = $key["qty"];
                $uom = $key["uom"];
                $price = $key["price"];
                $total_disc = $key["total_disc"];
                $total_vat = $key["total_vat"];
                $total_gross = $key["total_gross"];
                $total_net = $key["total_net"];
                $bonus = $key["bonus"];
                $discount_value_distributor = $key["discount_value_distributor"];
                $discount_value_prinsipal = $key["discount_value_prinsipal"];
                $discount_value_extra = $key["discount_value_extra"];
                $discount_persen_distributor = $key["discount_persen_distributor"];
                $discount_persen_prinsipal = $key["discount_persen_prinsipal"];
                $discount_persen_extra = $key["discount_persen_extra"];
                $nomor_discount_distributor = $key["nomor_discount_distributor"];
                $nomor_discount_prinsipal = $key["nomor_discount_prinsipal"];
                $nomor_discount_extra = $key["nomor_discount_extra"];
                $type_data = $key["type_data"];
                $nama_sales = $key["nama_sales"];
                $batch = $key["batch"];
                $type_promo = $key["type_promo"];
                $keterangan_promo = $key["keterangan_promo"];

                $data = [
                    "id_log"=> $id_log,
                    "bulan" => $bulan,
                    "tahun" => $tahun,
                    "principal_id" => $principal_id,
                    "area_id" => $area_id,
                    "nama_area" => $nama_area,
                    "tanggal_invoice" => $tanggal_invoice,
                    "nomor_invoice" => $nomor_invoice,
                    "nomor_sales_order" => $nomor_sales_order,
                    "customer_po_number" => $customer_po_number,
                    "kode_outlet" => $kode_outlet,
                    "kode_outlet_lama" => $kode_outlet_lama,
                    "nama_outlet" => $nama_outlet,
                    "category_produk" => $category_produk,
                    "sales_order_line" => $sales_order_line,
                    "kode_produk" => $kode_produk,
                    "kode_produk_lama" => $kode_produk_lama,
                    "inventory_item_id" => $inventory_item_id,
                    "item_id_vend" => $item_id_vend,
                    "id_item_sapora" => $id_item_sapora,
                    "category_product_principal" => $category_product_principal,
                    "nama_produk" => $nama_produk,
                    "qty" => $qty,
                    "uom" => $uom,
                    "price" => $price,
                    "total_disc" => $total_disc,
                    "total_vat" => $total_vat,
                    "total_gross" => $total_gross,
                    "total_net" => $total_net,
                    "bonus" => $bonus,
                    "discount_value_distributor" => $discount_value_distributor,
                    "discount_value_prinsipal" => $discount_value_prinsipal,
                    "discount_value_extra" => $discount_value_extra,
                    "discount_persen_distributor" => $discount_persen_distributor,
                    "discount_persen_prinsipal" => $discount_persen_prinsipal,
                    "discount_persen_extra" => $discount_persen_extra,
                    "nomor_discount_distributor" => $nomor_discount_distributor,
                    "nomor_discount_prinsipal" => $nomor_discount_prinsipal,
                    "nomor_discount_extra" => $nomor_discount_extra,
                    "type_data" => $type_data,
                    "nama_sales" => $nama_sales,
                    "batch" => $batch,
                    "type_promo" => $type_promo,
                    "keterangan_promo" => $keterangan_promo,
                    "created_at" => $this->created_at,
                    "created_by" => $this->userid,
                    "signature" => $signature
                ];

                $this->db->insert('site.penta_sales_origin', $data);
            }
            
            return $id_log;

        }else{
            // return $err;
            $this->session->set_flashdata("pesan", "something failed with error code : " . $err);
            redirect('penta/list_token', 'refresh');
        }
    }

    public function get_penta_sales_ext($token, $tahun, $bulan)
    {
        $url_penta = getenv('PENTA_API').'list/sales_ext/'.$tahun.'/'.$bulan;
        // echo "<br>";
        // echo "url_penta : ".$url_penta;
        // echo "<br>";
        // echo "token : "; echo $token;
        $curl = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($curl, CURLOPT_URL, $url_penta);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $array_response = json_decode($result, true);

        // echo "<pre>";
        // print_r($url_penta);
        // print_r($result);
        // print_r($err);
        // echo "array response : ";
        // print_r($array_response);
        // echo "</pre>";

        if (isset($array_response['data'])) 
        {

            $signature = 'penta-sales-ext' . rand() . md5($this->created_at) . date('Ymd');

            $data = [
                "created_at" => $this->created_at,
                "created_by" => $this->userid,
                "token" => $token,
                "tahun" => $tahun,
                "bulan" => $bulan,
                "signature" => $signature
            ];

            $this->db->insert('site.penta_log_sales', $data);
            $id_log = $this->db->insert_id();

            foreach ($array_response['data'] as $key) 
            {
                $nomor_invoice = $key["nomor_invoice"];
                $tanggal_invoice = $key["tanggal_invoice"];
                $bulan = $key["bulan"];
                $tahun = $key["tahun"];
                $area_id = $key["area_id"];
                $nama_area = $key["nama_area"];
                $kode_outlet = $key["kode_outlet"];
                $kode_outlet_lama = $key["kode_outlet_lama"];
                $nama_outlet = $key["nama_outlet"];
                $address = $key["address"];
                $province = $key["province"];
                $city = $key["city"];
                $npwp = $key["npwp"];
                $reference_invoice_return = $key["reference_invoice_return"];
                $reference_date_return = $key["reference_date_return"];
                $channel = $key["channel"];

                $data = [
                    "id_log"=> $id_log,
                    "nomor_invoice" => $nomor_invoice,
                    "tanggal_invoice" => $tanggal_invoice,
                    "bulan" => $bulan,
                    "tahun" => $tahun,
                    "area_id" => $area_id,
                    "nama_area" => $nama_area,
                    "kode_outlet" => $kode_outlet,
                    "kode_outlet_lama" => $kode_outlet_lama,
                    "nama_outlet" => $nama_outlet,
                    "address" => $address,
                    "province" => $province,
                    "city" => $city,
                    "npwp" => $npwp,
                    "reference_invoice_return" => $reference_invoice_return,
                    "reference_date_return" => $reference_date_return,
                    "channel" => $channel,
                    "created_at" => $this->created_at,
                    "created_by" => $this->userid,
                    "signature" => $signature
                ];

                // echo "<pre>";
                // print_r($data);
                // echo "</pre>";

                $this->db->insert('site.penta_sales_ext_origin', $data);
            }
            
            return $id_log;

            // $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
            // redirect('penta/log_sales', 'refresh');

        }else{
            return $err;
            // $this->session->set_flashdata("pesan", "data not found with error code : " . $err);
            // redirect('penta/list_token', 'refresh');
        }
    }

    public function get_penta_log($signature = "")
    {

        if ($signature) {
            $params_signature = "where a.signature = '$signature'";
        }else{
            $params_signature = "";
        }

        $query = "
            select a.*, b.username
            from site.penta_log_sales a left join site.master_user b 
                on a.created_by = b.id
            $params_signature
            order by a.id desc
            limit 10
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;

        return $this->db->query($query);
    }

    public function get_penta_log_stock($signature = "")
    {

        if ($signature) {
            $params_signature = "where a.signature = '$signature'";
        }else{
            $params_signature = "";
        }

        $query = "
            select a.*, b.username
            from site.penta_log_stock a left join site.master_user b 
                on a.created_by = b.id
            $params_signature
            order by a.id desc
            limit 1000
        ";
        return $this->db->query($query);
    }

    public function export_sales($signature)
    {
        // $query = "
        //     select *
        //     from site.penta_sales_origin a 
        //     where a.signature = '$signature'
        // "; 
        $query = "
            select *
            from site.penta_sales_join a 
            where a.signature = '$signature'
        "; 

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export.csv');
    }

    // public function export_sales_new($signature)
    // {
    //     $query = "
    //         select 
    //                 a.bulan, a.tahun, a.principal_id, a.area_id, a.nama_area, a.tanggal_invoice, 
    //                 a.nomor_invoice, a.nomor_sales_order, a.customer_po_number,
    //                 a.kode_outlet, a.kode_outlet_lama, a.nama_outlet, a.category_produk, 
    //                 a.sales_order_line, a.kode_produk, a.kode_produk_lama,
    //                 a.inventory_item_id, a.item_id_vend, a.id_item_sapora,
    //                 a.category_product_principal, a.nama_produk, a.qty, a.uom,
    //                 a.price, a.total_disc, a.total_vat, a.total_gross, a.total_net, a.bonus, 
    //                 a.discount_value_distributor, a.discount_value_prinsipal, a.discount_value_extra,
    //                 a.discount_persen_distributor, a.discount_persen_prinsipal, a.discount_persen_extra,
    //                 a.nomor_discount_prinsipal, a.nomor_discount_extra,
    //                 a.type_data, a.nama_sales, a.batch, a.type_promo, a.address,
    //                 a.province, a.city, a.npwp, a.channel
    //         from site.penta_sales_join a 
    //         where a.signature = '$signature'
    //     "; 

    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";
    //     // die;

    //     $hasil = $this->db->query($query);  

    //     // Process the data to convert JSON to dynamic columns
    //     $processed_data = array();
    //     $global_max_batch = 0;
    //     $base_headers = array(
    //         'bulan', 'tahun', 'principal_id', 'area_id', 'nama_area', 'tanggal_invoice', 
    //         'nomor_invoice', 'nomor_sales_order', 'customer_po_number',
    //         'kode_outlet', 'kode_outlet_lama', 'nama_outlet', 'category_produk', 
    //         'sales_order_line', 'kode_produk', 'kode_produk_lama',
    //         'inventory_item_id', 'item_id_vend', 'id_item_sapora',
    //         'category_product_principal', 'nama_produk', 'qty', 'uom',
    //         'price', 'total_disc', 'total_vat', 'total_gross', 'total_net', 'bonus', 
    //         'discount_value_distributor', 'discount_value_prinsipal', 'discount_value_extra',
    //         'discount_persen_distributor', 'discount_persen_prinsipal', 'discount_persen_extra',
    //         'nomor_discount_prinsipal', 'nomor_discount_extra',
    //         'type_data', 'nama_sales', 'batch', 'type_promo', 'address',
    //         'province', 'city', 'npwp', 'channel', 'max_batch'  // TAMBAHKAN max_batch DI SINI
    //     );

    //     // First pass: find maximum number of batch items globally
    //     foreach ($hasil->result_array() as $row) {
    //         $batch = json_decode($row['batch'], true);
    //         if (is_array($batch)) {
    //             $global_max_batch = max($global_max_batch, count($batch));
    //         }
    //     }

    //     // Add dynamic headers for batch
    //     $dynamic_headers = array();
    //     for ($i = 1; $i <= $global_max_batch; $i++) {      
    //         $dynamic_headers[] = 'batch number_' . $i;
    //         $dynamic_headers[] = 'expirate date_' . $i;
    //         $dynamic_headers[] = 'qty_' . $i;
    //     }

    //     $all_headers = array_merge($base_headers, $dynamic_headers);

    //     // Second pass: process the data
    //     foreach ($hasil->result_array() as $row) 
    //     {
    //         $new_row = array();
            
    //         // Copy original fields
    //         foreach ($base_headers as $header) {
    //             if ($header == 'max_batch') {
    //                 // Hitung max_batch untuk row ini
    //                 $batch = json_decode($row['batch'], true);
    //                 $new_row[$header] = is_array($batch) ? count($batch) : 0;
    //             } else {
    //                 $new_row[$header] = isset($row[$header]) ? $row[$header] : '';
    //             }
    //         }

    //         // Process batch JSON
    //         $batch = json_decode($row['batch'], true);
    //         if (is_array($batch)) {
    //             $counter = 1;

    //             foreach ($batch as $surat) {
    //                 $new_row['batch number_' . $counter] = isset($surat['batch number']) ? $surat['batch number'] : '';
    //                 $new_row['expirate date_' . $counter] = isset($surat['expirate date']) ? $surat['expirate date'] : '';
    //                 $new_row['qty_' . $counter] = isset($surat['qty']) ? $surat['qty'] : '';
    //                 $counter++;
    //             }
    //         }

    //         // Fill empty columns for rows with fewer batch items
    //         for ($i = (is_array($batch) ? count($batch) : 0) + 1; $i <= $global_max_batch; $i++) {
    //             $new_row['batch number_' . $i] = '';
    //             $new_row['expirate date_' . $i] = '';
    //             $new_row['qty_' . $i] = '';
    //         }

    //         $processed_data[] = $new_row;
    //     }

    //     // Create custom CI_DB_result object
    //     $custom_result = new CI_DB_result($this->db->conn_id);
        
    //     // Use reflection to set protected properties
    //     $reflection = new ReflectionClass($custom_result);
        
    //     $result_array_prop = $reflection->getProperty('result_array');
    //     $result_array_prop->setAccessible(true);
    //     $result_array_prop->setValue($custom_result, $processed_data);
        
    //     $result_object_prop = $reflection->getProperty('result_object');
    //     $result_object_prop->setAccessible(true);
        
    //     // Convert to objects
    //     $result_objects = array();
    //     foreach ($processed_data as $row) {
    //         $result_objects[] = (object)$row;
    //     }
    //     $result_object_prop->setValue($custom_result, $result_objects);
        
    //     $num_rows_prop = $reflection->getProperty('num_rows');
    //     $num_rows_prop->setAccessible(true);
    //     $num_rows_prop->setValue($custom_result, count($processed_data));

    //     $this->excel_generator->set_query($custom_result);
    //     $this->excel_generator->set_header($all_headers);
    //     $this->excel_generator->set_column($all_headers);
        
    //     // Set dynamic widths
    //     $widths = array_fill(0, count($all_headers), 15);
    //     $this->excel_generator->set_width($widths);
        
    //     $this->excel_generator->exportTo2007('data');
    // }

    public function export_sales_new($signature)
    {

        $supp =$this->session->userdata('supp');
        if($supp == '001')
        {
            $params = " and (b.supp != '005' or b.supp is null)";
        }else{
            $params = "";
        }
        // die;

        $query = "
            select 
                    a.bulan, a.tahun, a.principal_id, a.area_id, a.nama_area, a.tanggal_invoice, 
                    a.nomor_invoice, a.nomor_sales_order, a.customer_po_number,
                    a.kode_outlet, a.kode_outlet_lama, a.nama_outlet, a.category_produk, 
                    a.sales_order_line, a.kode_produk, a.kode_produk_lama,
                    a.inventory_item_id, a.item_id_vend, a.id_item_sapora,
                    a.category_product_principal, a.nama_produk, a.qty, a.uom,
                    a.price, a.total_disc, a.total_vat, a.total_gross, a.total_net, a.bonus, 
                    a.discount_value_distributor, a.discount_value_prinsipal, a.discount_value_extra,
                    a.discount_persen_distributor, a.discount_persen_prinsipal, a.discount_persen_extra,
                    a.nomor_discount_prinsipal, a.nomor_discount_extra,
                    a.type_data, a.nama_sales, a.batch, a.type_promo, a.address,
                    a.province, a.city, a.npwp, a.channel, a.reference_invoice_return, a.reference_date_return, 
                    a.nomor_discount_distributor, a.dpl_do_disc_princ
            from site.penta_sales_join a left join (
                select a.kodeprod, a.namaprod, a.supp
                from site.master_product a 
            )b on a.item_id_vend = b.kodeprod
            where a.signature = '$signature' $params
        "; 

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $hasil = $this->db->query($query);  
        // query_to_csv($hasil,TRUE,'Export Sales.csv');
        $this->process_and_export_sales($hasil);
    }

    // FILE: application/models/Model_Penta.php

    private function process_and_export_sales($query_result)
    {
        if ($query_result->num_rows() == 0) {
            return; 
        }

        $processed_data = [];
        $result_array = $query_result->result_array();

        // Loop untuk memproses setiap baris
        foreach ($result_array as $row) {
            
            // 1. Parsing discount_persen_distributor
            $discounts_distributor = json_decode($row['discount_persen_distributor'], true);
            $row['discount_persen_distributor'] = $this->format_discounts($discounts_distributor);

            // 2. Parsing discount_persen_prinsipal
            $discounts_prinsipal = json_decode($row['discount_persen_prinsipal'], true);
            $row['discount_persen_prinsipal'] = $this->format_discounts($discounts_prinsipal);

            // 3. Parsing discount_persen_extra
            $discounts_extra = json_decode($row['discount_persen_extra'], true);
            $row['discount_persen_extra'] = $this->format_discounts($discounts_extra);

            $processed_data[] = $row;
        }

        // --- Kode Export CSV Manual (tetap sama) ---

        $filename = 'Export Sales.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        // Ambil header kolom
        if (!empty($processed_data)) {
            $headers = array_keys($processed_data[0]);
            fputcsv($output, $headers); // Tulis header
        }

        // Tulis data baris demi baris
        foreach ($processed_data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit; 
    }

    // Fungsi pembantu format_discounts() tetap sama dan bisa menangani ketiga kolom
    private function format_discounts($discounts)
    {
        if (is_array($discounts) && !empty($discounts)) {
            $discount_values = [];
            
            foreach ($discounts as $disc_item) {
                if (isset($disc_item['disc_per'])) {
                    // Pastikan nilai adalah float
                    $discount_values[] = (float)$disc_item['disc_per'];
                }
            }
            
            if (!empty($discount_values)) {
                // Menggabungkan dengan '+'
                return implode('+', $discount_values); 
            }
        }
        
        // Jika array kosong atau tidak valid
        return '0';
    }

    public function export_master_outlet($signature)
    {
        $query = "
            select  a.nomor_invoice, a.tanggal_invoice, a.bulan, a.tahun, a.area_id, a.nama_area, a.kode_outlet, 
                    a.kode_outlet_lama, a.nama_outlet, a.address, a.province, a.city, a.npwp, a.channel
            from site.penta_sales_ext_origin a 
            where a.signature = '$signature'
        "; 

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export Master Outlet.csv');
    }


    public function export_sales_closing($signature)
    {
        // echo $signature; die;
        // $query = "
        //     select *
        //     from site.penta_sales_origin_closing a 
        //     where a.signature = '$signature'
        // "; 
        $query = "
            select *
            from site.penta_sales_join a 
            where a.signature = '$signature'
        "; 
    
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export.csv');
    }

    public function get_sales_origin($tahun, $bulan, $signature = "")
    {
        if ($signature) {
            $params_signature = "and a.signature = '$signature'";
        }else{
            $params_signature = "";
        }
        $query = "
            select *
            from site.penta_sales_origin a 
            where a.tahun = $tahun and a.bulan = $bulan
            $params_signature
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function summary_sales($tahun, $bulan, $signature)
    {
        $query = "
            select sum(a.total_net) as total_net, sum(a.total_gross) as total_gross, tahun, bulan
            from site.penta_sales_origin a 
            where a.tahun = $tahun and a.bulan = $bulan
            and a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function summary_sales_by_product($tahun, $bulan, $signature)
    {
        $query = "
            select a.item_id_vend as kodeprod, b.namaprod, sum(a.total_net) as total_net, sum(a.total_gross) as total_gross
            from site.penta_sales_origin a left join site.master_product b 
                on a.item_id_vend = b.kodeprod
            where a.tahun = $tahun and a.bulan = $bulan
            and a.signature = '$signature'
            group by a.item_id_vend
            ORDER BY sum(a.total_net) desc
            limit 10
        ";

        return $this->db->query($query);
    }

    public function summary_sales_by_outlet($tahun, $bulan, $signature)
    {
        $query = "
            select a.kode_outlet as kode_outlet, a.nama_outlet, b.namaprod, sum(a.total_net) as total_net, sum(a.total_gross) as total_gross
            from site.penta_sales_origin a left join site.master_product b 
                on a.item_id_vend = b.kodeprod
            where a.tahun = $tahun and a.bulan = $bulan
            and a.signature = '$signature'
            group by a.kode_outlet
            ORDER BY sum(a.total_net) desc
            limit 10
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_summary_sales_by_tanggal($tahun, $bulan, $signature)
    {
        $query = "
             select a.tanggal_invoice, sum(a.total_net) as total_net
            from site.penta_sales_origin a 
            where a.tahun = $tahun and a.bulan = $bulan
            and a.signature = '$signature'
            GROUP BY a.tanggal_invoice
            ORDER BY a.tanggal_invoice desc 
            limit 10

        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_bulan($bulan)
    {
        switch ($bulan) {
            case 1:
                $get_bulan = "Januari";
                break;
            case 2:
                $get_bulan = "Februari";
                break;
            case 3:
                $get_bulan = "Maret";
                break;
            case 4:
                $get_bulan = "April";
                break;
            case 5:
                $get_bulan = "Mei";
                break;
            case 6:
                $get_bulan = "Juni";
                break;
            case 7:
                $get_bulan = "Juli";
                break;
            case 8: 
                $get_bulan = "Agustus";
                break;
            case 9:
                $get_bulan = "September";
                break;
            case 10:
                $get_bulan = "Oktober";
                break;
            case 11:
                $get_bulan = "November";
                break;
            case 12:
                $get_bulan = "Desember";
                break;
            case 0:
                $get_bulan = " ";
                break;
        }

        return $get_bulan;
    }

    public function update_log_sales($data, $id_log)
    {
        $this->db->where('id', $id_log);
        $this->db->update('site.penta_log_sales', $data);
        return true;
    }

    public function get_sum_sales_origin($id_log)
    {
        $query = "
            select sum(a.total_net) as total_net, sum(a.total_gross) as total_gross
            from site.penta_sales_origin a 
            where a.id_log = $id_log
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    } 

    public function update_sales_origin($data, $id_log)
    {
        $this->db->where('id_log', $id_log);
        $this->db->update('site.penta_sales_origin', $data);
        return true;
    }

    public function update_length_kodeprod($id_log)
    {
        $query = "
            update site.penta_sales_origin a
            set a.item_id_vend = concat('0',a.item_id_vend)
            where length(a.item_id_vend) = 5
        ";
        return $this->db->query($query);
    }

    public function get_penta_stock($token, $tahun, $bulan, $signature, $id_log)
    {
        $url_penta = getenv('PENTA_API').'list/stock/';
        $curl = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($curl, CURLOPT_URL, $url_penta);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $array_response = json_decode($result, true);

        if (isset($array_response['data'])) 
        {

            // $signature = 'penta-stock-' . rand() . md5($this->created_at) . date('Ymd');

            // $data = [
            //     "created_at" => $this->created_at,
            //     "created_by" => $this->userid,
            //     "token" => $token,
            //     "tahun" => $tahun,
            //     "bulan" => $bulan,
            //     "signature" => $signature
            // ];

            // $this->db->insert('site.penta_log_stock', $data);
            // $id_log = $this->db->insert_id();

            foreach ($array_response['data'] as $key) 
            {
                $principal_name = $key["principal_name"];
                $area_id = $key["area_id"];
                $nama_area = $key["nama_area"];
                $inventory_item_id = $key["inventory_item_id"];
                $kode_produk = $key["kode_produk"];
                $item_id_vend = $key["item_id_vend"];
                $nama_produk = $key["nama_produk"];
                $qty = $key["qty"];
                $uom = $key["uom"];
                $batch = $key["batch"];
                $tanggal_penerimaan = $key["tanggal_penerimaan"];
                $update_terakhir = $key["update_terakhir"];
                $hna = $key["hna"];
                $expired_date = $key["expired_date"];

                $data = [
                    "id_log"=> $id_log,
                    "bulan" => $bulan,
                    "tahun" => $tahun,
                    "principal_name" => $principal_name,
                    "area_id" => $area_id,
                    "nama_area" => $nama_area,
                    "inventory_item_id" => $inventory_item_id,
                    "kode_produk" => $kode_produk,
                    "item_id_vend" => $item_id_vend,
                    "nama_produk" => $nama_produk,
                    "qty" => $qty,
                    "uom" => $uom,
                    "batch" => $batch,
                    "tanggal_penerimaan" => $tanggal_penerimaan,
                    "update_terakhir" => $update_terakhir,
                    "hna" => $hna,
                    "expired_date" => $expired_date,
                    "created_at" => $this->created_at,
                    "created_by" => $this->userid,
                    "signature" => $signature
                ];

                $this->db->insert('site.penta_stock_origin', $data);
            }
            
            return $id_log;

            // $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
            // redirect('penta/log_sales', 'refresh');

        }else{
            // return $err;
            $this->session->set_flashdata("pesan", "data not found with error code : " . $err);
            redirect('penta/list_token', 'refresh');
        }
    }

    public function get_sum_stock_origin($id_log)
    {
        $query = "
            select sum(a.qty) as total_qty, sum(a.qty * a.hna) as total_value
            from site.penta_stock_origin a 
            where a.id_log = $id_log
        ";
        return $this->db->query($query);
    } 

    public function update_log_stock($data, $id_log)
    {
        $this->db->where('id', $id_log);
        $this->db->update('site.penta_log_stock', $data);
        return true;
    }

    public function update_length_kodeprod_stock($id_log)
    {
        $query = "
            update site.penta_stock_origin a
            set a.item_id_vend = concat('0',a.item_id_vend)
            where length(a.item_id_vend) = 5 and a.id_log = $id_log
        ";
        return $this->db->query($query);
    }

    public function export_stock($signature)
    {
        $query = "
            select *
            from site.penta_stock_origin a 
            where a.signature = '$signature'
        "; 
    
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export Stock.csv');
    }

    public function get_penta_log_by_tahun_bulan_limit($tahun, $bulan, $limit = "")
    {
        if ($limit) {
            $params_limit = " limit $limit ";
        }else{
            $params_limit = "";
        }
        // $query = "
        //     select a.*, b.username
        //     from site.penta_log_sales a left join site.master_user b 
        //         on a.created_by = b.id
        //     where a.tahun = $tahun and a.bulan = $bulan and a.deleted_at is null and a.signature not like 'penta-sales-ext%'
        //     order by a.id desc
        //     $params_limit
        // ";
        // $query = "
        //     select a.*, b.username
        //     from site.penta_log_sales a left join site.master_user b 
        //         on a.created_by = b.id
        //     where a.tahun = $tahun and a.bulan = $bulan and a.deleted_at is null and a.total_gross is not null
        //     order by a.id desc
        //     $params_limit
        // ";

        $query = "
            select a.*, b.username
            from site.penta_log_sales a left join site.master_user b 
                on a.created_by = b.id
            where a.deleted_at is null and a.total_gross is not null and a.tahun = $tahun and a.bulan = $bulan
            order by a.id desc
            $params_limit
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_sales_origin_by_id_log($id_log)
    {
        // $query = "
        //     select *
        //     from site.penta_sales_origin a 
        //     where a.id_log = $id_log
        //     limit 20
        // ";

        $query = "
            select a.tahun, a.bulan, a.area_id, a.nama_area, sum(a.total_gross) as total_gross
            from site.penta_sales_origin a 
            where a.id_log = $id_log
            GROUP BY a.area_id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function summary_sales_by_id_log($id_log)
    {
        $query = "
            select sum(a.total_net) as total_net, sum(a.total_gross) as total_gross, tahun, bulan
            from site.penta_sales_origin a 
            where a.id_log = $id_log
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function summary_sales_by_id_log_group_tanggal($id_log, $limit = "")
    {
        if ($limit) {
            $params_limit = " limit $limit ";
        }else{
            $params_limit = "";
        }
        $query = "
             select a.tanggal_invoice, sum(a.total_net) as total_net
            from site.penta_sales_origin a 
            where a.id_log = $id_log
            GROUP BY a.tanggal_invoice
            ORDER BY a.tanggal_invoice desc 
            $params_limit
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function summary_sales_by_id_log_group_product($id_log, $limit = "")
    {
        if ($limit) {
            $params_limit = " limit $limit ";
        }else{
            $params_limit = "";
        }
        $query = "
            select a.item_id_vend as kodeprod, b.namaprod, sum(a.total_net) as total_net, sum(a.total_gross) as total_gross
            from site.penta_sales_origin a left join site.master_product b 
                on a.item_id_vend = b.kodeprod
            where a.id_log = $id_log
            group by a.item_id_vend
            ORDER BY sum(a.total_net) desc
            $params_limit
        ";

        return $this->db->query($query);
    }

    public function summary_sales_by_id_log_group_outlet($id_log, $limit = "")
    {
        if ($limit) {
            $params_limit = " limit $limit ";
        }else{
            $params_limit = "";
        }
        $query = "
            select a.kode_outlet as kode_outlet, a.nama_outlet, b.namaprod, sum(a.total_net) as total_net, sum(a.total_gross) as total_gross
            from site.penta_sales_origin a left join site.master_product b 
                on a.item_id_vend = b.kodeprod
            where a.id_log = $id_log
            group by a.kode_outlet
            ORDER BY sum(a.total_net) desc
            $params_limit
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function summary_sales_by_id_log_group_tanggal_closing($id_log, $limit = "")
    {
        if ($limit) {
            $params_limit = " limit $limit ";
        }else{
            $params_limit = "";
        }
        $query = "
             select a.tanggal_invoice, sum(a.total_net) as total_net
            from site.penta_sales_origin_closing a 
            where a.id_log = $id_log
            GROUP BY a.tanggal_invoice
            ORDER BY a.tanggal_invoice desc 
            $params_limit
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function summary_sales_by_id_log_group_product_closing($id_log, $limit = "")
    {
        if ($limit) {
            $params_limit = " limit $limit ";
        }else{
            $params_limit = "";
        }
        $query = "
            select a.item_id_vend as kodeprod, b.namaprod, sum(a.total_net) as total_net, sum(a.total_gross) as total_gross
            from site.penta_sales_origin_closing a left join site.master_product b 
                on a.item_id_vend = b.kodeprod
            where a.id_log = $id_log
            group by a.item_id_vend
            ORDER BY sum(a.total_net) desc
            $params_limit
        ";

        return $this->db->query($query);
    }

    public function summary_sales_by_id_log_group_outlet_closing($id_log, $limit = "")
    {
        if ($limit) {
            $params_limit = " limit $limit ";
        }else{
            $params_limit = "";
        }
        $query = "
            select a.kode_outlet as kode_outlet, a.nama_outlet, b.namaprod, sum(a.total_net) as total_net, sum(a.total_gross) as total_gross
            from site.penta_sales_origin_closing a left join site.master_product b 
                on a.item_id_vend = b.kodeprod
            where a.id_log = $id_log
            group by a.kode_outlet
            ORDER BY sum(a.total_net) desc
            $params_limit
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_penta_sales_origin_closing($signature = '', $id_log)
    {
        if ($signature){
            $params_signature = "and signature = '$signature'";
        }else{
            $params_signature = "";
        }
        
        $query = "
            select *
            from site.penta_sales_origin_closing
            where id_log = '$id_log' $params_signature
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_penta_sales_origin_closing($signature, $id_log)
    {
        $query = "
            insert into site.penta_sales_origin_closing
            select 	'', '$id_log' as id_log, a.bulan, a.tahun, a.principal_id, a.area_id, a.nama_area, a.tanggal_invoice, a.nomor_invoice,
                    a.nomor_sales_order, a.customer_po_number, a.kode_outlet, a.kode_outlet_lama,
                    a.nama_outlet, a.category_produk, a.sales_order_line, a.kode_produk, a.kode_produk_lama,
                    a.inventory_item_id, a.item_id_vend, a.id_item_sapora, a.category_product_principal,
                    a.nama_produk, a.qty, a.uom, a.price, a.total_disc, a.total_vat, a.total_gross, a.total_net,
                    a.bonus, a.discount_value_distributor, a.discount_value_prinsipal,
                    a.discount_value_extra, a.discount_persen_distributor, a.discount_persen_prinsipal, 
                    a.discount_persen_extra, a.nomor_discount_distributor, a.nomor_discount_prinsipal, 
                    a.nomor_discount_extra, a.type_data, a.nama_sales, a.batch, a.type_promo,
                    a.keterangan_promo, a.dpl_do_disc_princ, a.address, a.province, a.city, a.npwp, a.channel, NOW() as created_at, 
                    '749' as created_by, a.reference_invoice_return, a.reference_date_return,  '$signature' as signature, '' as divisi
            from site.penta_sales_join a
            where signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function search_max_created_at_from_penta_stock_origin($tahun, $bulan)
    {
        // mencari max created_at
        $query = "
            select  max(a.created_at) as max_created_at
            from    site.penta_stock_origin a
            where  a.tahun = $tahun and a.bulan = $bulan
            ";

        // echo '<pre>';
        // print_r($query);
        // echo '</pre>';
        // die;
        return $this->db->query($query);
    }

    public function insert_penta_temp_stock_dan_doi($tahun, $bulan, $created_at, $max_created_at, $created_by)
    { 

        $this->db->query("truncate site.penta_temp_stock_dan_doi");

        // insert stok terakhir
        $sql_insert = "
                insert into site.penta_temp_stock_dan_doi
                select 	a.id, a.id_log, a.bulan, a.tahun, a.principal_name, a.area_id, a.nama_area, a.inventory_item_id, a.kode_produk, a.item_id_vend,
                        a.nama_produk, sum(a.qty), a.uom, a.batch, a.tanggal_penerimaan, a.update_terakhir, a.hna, a.expired_date, '$created_at' as created_at, $created_by, a.signature
                from 	site.penta_stock_origin a
                where 	a.tahun = $tahun and a.bulan = $bulan and a.created_at = '$max_created_at'
                group by a.area_id, a.kode_produk
            ";
            
        $proses_insert = $this->db->query($sql_insert);

    }

    public function get_data_penta_temp_stock_dan_doi($created_by, $created_at)
    {
        $query = "
            select *
            from site.penta_temp_stock_dan_doi a
            where a.created_by = '$created_by' and a.created_at = '$created_at'
        ";
        return $this->db->query($query);
    }

    public function get_data_penta_sales_origin_closing($tahun, $bulan_berjalan)
    {
        if ($bulan_berjalan == 0) {
            $params_bulan = 12;
            $params_tahun = $tahun - 1;
        }else{
            $params_bulan = $bulan_berjalan;
            $params_tahun = $tahun;
        }

        $query = "
            select *
            from site.penta_sales_origin_closing a
            where a.bulan = $params_bulan and a.tahun = $params_tahun
        ";

        return $this->db->query($query);
    }

    public function insert_penta_temp_sales_origin_closing($bulan_avg, $tahun, $tahun_avg, $bulan_berjalan)
    {
        // echo 'bulan avg : '.$bulan_avg;
        // echo '<br>';
        // echo 'bulan : '.$bulan;
        // echo '<br>';
        // echo 'tahun : '.$tahun;
        // echo '<br>';
        // echo 'tahun avg : '.$tahun_avg;
        // echo '<br>';die;

        $this->db->query("truncate site.penta_temp_sales_origin_closing");

        if ($bulan_avg >= 7)
        {
            // sales_closing_tahun sebelum
            $sql_insert_closing = "
                insert into site.penta_temp_sales_origin_closing
                select '', id_log, bulan, tahun, area_id, nama_area, tanggal_invoice, kode_produk, inventory_item_id, item_id_vend,
                        kode_produk_lama, nama_produk, sum(qty) as total_unit, sum(qty)/6 as avg_unit, uom,  price, sum(a.total_gross) as total_value_gross, sum(a.total_gross)/6 as avg_value_gross , sum(a.total_net) as total_value_net, 
                        sum(a.total_net)/6 as avg_value_net
                from site.penta_sales_origin_closing a
                where a.tahun = $tahun_avg and (a.bulan between $bulan_avg and 12)
                group by a.area_id, a.kode_produk 
                ORDER BY a.area_id, kode_produk asc
            ";
            $proses_insert_closing = $this->db->query($sql_insert_closing);

            // echo '<br>';
            // print_r($sql_insert_closing);
            // $bulan_berjalan = $bulan - 1;

            if($bulan_berjalan > 0)
            {
                // sales_tahun_dipilih
                $sql_insert_berjalan = "
                    insert into site.penta_temp_sales_origin_closing
                    select '', id_log, bulan, tahun, area_id, nama_area, tanggal_invoice, kode_produk, inventory_item_id, item_id_vend,
                            kode_produk_lama, nama_produk, sum(qty) as total_unit, sum(qty)/6 as avg_unit, uom, price, sum(a.total_gross) as total_value_gross, sum(a.total_gross)/6 as avg_value_gross , sum(a.total_net) as total_value_net, 
                            sum(a.total_net)/6 as avg_value_net 
                    from site.penta_sales_origin_closing a
                    where a.tahun = $tahun and (a.bulan between 1 and $bulan_berjalan )
                    group by a.area_id, a.kode_produk 
                    ORDER BY a.area_id, kode_produk asc
                ";

                $proses_insert_berjalan = $this->db->query($sql_insert_berjalan);
            }

            // echo '<br>';
            // print_r($sql_insert_berjalan);

        }elseif($bulan_avg < 7){
            // sales_berjalan
            // $bulan_berjalan = $bulan - 1;

            $sql_insert = "
                insert into site.penta_temp_sales_origin_closing
                select '', id_log, bulan, tahun, area_id, nama_area, tanggal_invoice, kode_produk, inventory_item_id, item_id_vend,
                        kode_produk_lama, nama_produk, sum(qty) as total_unit, sum(qty)/6 as avg_unit, uom,  price, sum(a.total_gross) as total_value_gross, 
                        sum(a.total_gross)/6 as avg_value_gross , sum(a.total_net) as total_value_net, sum(a.total_net)/6 as avg_value_net
                from site.penta_sales_origin_closing a
                where a.tahun = $tahun and (a.bulan between $bulan_avg and $bulan_berjalan )
                group by a.area_id, a.kode_produk 
                ORDER BY a.area_id, kode_produk asc
            ";

            $proses_insert = $this->db->query($sql_insert);
        }
    }

    public function insert_penta_stock_dan_doi_report($bulan, $tahun, $id_log, $created_at, $created_by, $signature)
    {
        // echo "a"; die;
        // insert stok_dan_doi report
        $sql_insert = "
            insert into site.penta_stock_dan_doi_report
            select '', a.id_log, a.bulan, a.tahun, a.area_id, a.nama_area, a.inventory_item_id, a.kode_produk, 
			    a.item_id_vend, a.nama_produk, a.qty, a.uom, b.avg_unit, round((a.qty / b.avg_unit) * 30) as doi_unit, 
                a.batch, a.hna, a.created_at, a.created_by, a.signature
            from (
                select '$id_log' as id_log, '$bulan' as bulan, '$tahun' as tahun, b.area_id, b.nama_area, a.inventory_item_id, a.kode_produk, 
                        a.item_id_vend, a.nama_produk, c.qty, c.uom, c.batch, c.hna, '$created_at' as created_at, '$created_by' as created_by, '$signature' as signature
                from site.penta_master_produk a 
                join (
                    select *
                    from (
                        select a.area_id, a.nama_area 
                        from site.penta_temp_stock_dan_doi a 
                        GROUP BY a.area_id 
                        union all 
                        select b.area_id, b.nama_area 
                        from site. penta_temp_sales_origin_closing b
                        GROUP BY b.area_id 
                    )a GROUP BY a.area_id
                )b left join (
                    select a.id_log, a.bulan, a.tahun, a.area_id, a.nama_area, a.inventory_item_id, a.kode_produk, 
                            a. item_id_vend, a.nama_produk, a.qty, a.uom, batch, a.hna, a.created_by, a.signature
                    from site.penta_temp_stock_dan_doi a
                )c on a.kode_produk = c.kode_produk and b.area_id = c.area_id
                ORDER BY b.area_id, a.kode_produk
            )a LEFT JOIN (
                select a.area_id, a.nama_area, a.tanggal_invoice, a.kode_produk, a.inventory_item_id, a.item_id_vend,
                    a.kode_produk_lama, a.nama_produk, sum(total_unit) as total_unit, sum(total_unit)/6 as avg_unit, 
                    a. uom, a.price, a.total_value_gross, a.avg_value_gross, a.total_value_net, a.avg_value_net
                from site.penta_temp_sales_origin_closing a
                group by a.area_id, a.kode_produk
            )b on a.kode_produk = b.kode_produk and a.area_id = b.area_id
            ";
            
        $proses_insert = $this->db->query($sql_insert);

        // print_r($sql_insert); 
        // die;

    }

    public function get_penta_stock_dan_doi_report($userid, $bulan, $tahun)
    {
        $query = "
            select *
            from site.penta_stock_dan_doi_report a
            where a.created_at = (SELECT MAX(created_at) FROM site.penta_stock_dan_doi_report WHERE created_by = $userid) 
            and created_by = $userid and a.bulan = $bulan and a.tahun = $tahun
            limit 10
        ";
        // 

        return $this->db->query($query);
    }

    public function export_penta_stock_dan_doi_report($userid, $bulan, $tahun)
    {
        $query = "
            select a.id, a.id_log, a.bulan, a.tahun, a.area_id, a.nama_area, a.inventory_item_id, a.kode_produk, 
                a.item_id_vend, a.nama_produk, a.qty, a.uom, a.avg_unit, a.doi_unit, a.batch, a.created_at, a.created_by
            from site.penta_stock_dan_doi_report a
            where a.created_at = (SELECT MAX(created_at) FROM site.penta_stock_dan_doi_report WHERE created_by = $userid) 
            and created_by = $userid and a.bulan = $bulan and a.tahun = $tahun
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'penta_stock_dan_doi.csv');
    }

    public function join_sales($id_log, $id_log_ext = "")
    {
        $query = "
            insert into site.penta_sales_join
            select 	'', a.bulan, a.tahun, a.principal_id, a.area_id, a.nama_area, a.tanggal_invoice, a.nomor_invoice,
                    a.nomor_sales_order, a.customer_po_number, a.kode_outlet, a.kode_outlet_lama,
                    a.nama_outlet, a.category_produk, a.sales_order_line, a.kode_produk, a.kode_produk_lama,
                    a.inventory_item_id, a.item_id_vend, a.id_item_sapora, a.category_product_principal,
                    a.nama_produk, a.qty, a.uom, a.price, a.total_disc, a.total_vat, a.total_gross, a.total_net,
                    a.bonus, a.discount_value_distributor, a.discount_value_prinsipal,
                    a.discount_value_extra, a.discount_persen_distributor, a.discount_persen_prinsipal, 
                    a.discount_persen_extra, a.nomor_discount_distributor, a.nomor_discount_prinsipal, 
                    a.nomor_discount_extra, a.type_data, a.nama_sales, a.batch, a.type_promo,
                    a.keterangan_promo, a.dpl_do_disc_princ, b.address, b.province, b.city, b.npwp, b.channel, 
                    b.reference_invoice_return, b.reference_date_return, a.signature
            from site.penta_sales_origin a left join (
                select 	a.nomor_invoice, a.address, a.province, a.city, a.npwp, a.channel, a.reference_invoice_return, a.reference_date_return
                from site.penta_sales_ext_origin a 
                where a.id_log = $id_log_ext
                limit 10000
            )b on a.nomor_invoice = b.nomor_invoice
            where a.id_log = $id_log
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_penta_sales_origin_closing($bulan, $tahun)
    {
        $query = "
            delete 
            from site.penta_sales_origin_closing 
            where bulan = $bulan and tahun = $tahun
        ";
        return $this->db->query($query);
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
    }

    public function get_token_for_sds()
    {
        $url_penta_verify = getenv('PENTA_API_FOR_SDS').'verify';
        $penta_token = getenv('PENTA_TOKEN_FOR_SDS');
        $curl = curl_init();

        $authorization = "Authorization: Bearer $penta_token";

        curl_setopt($curl, CURLOPT_URL, $url_penta_verify);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $array_response = json_decode($result, true);
        return $array_response;

    }

    public function insert_token_for_sds($data)
    {
        $this->db->insert('site.penta_token_for_sds', $data);
        return $this->db->insert_id();
    }

    public function get_penta_header_by_tgl_order($tgl_order)
    {
        $query = "
            select 	a.id, a.no_so, a.tgl_order, a.id_pelanggan, a.nama_pelanggan,
                    a.kode_cabang, a.top, a.id_salesman, a.created_at, a.created_by,
                    a.updated_at, a.updated_by
            from dbrest.penta_mg_so_header a 
            where a.tgl_order = '$tgl_order' and a.deleted_at is null
            limit 1
        ";
        return $this->db->query($query);
    }

    public function get_penta_detail_by_id_header($id_header)
    {
        $query = "
            select 	a.id, a.id_header, a.id_produk, a.desc_produk, a.uom, a.qty, 
                    a.disc_percent_1, a.disc_value_1, a.disc_letter_1,
                    a.disc_percent_2, a.disc_value_2, a.disc_letter_2,
                    a.disc_percent_3, a.disc_value_3, a.disc_letter_3,
                    a.disc_percent_4, a.disc_value_4, a.disc_letter_4,
                    a.disc_value, a.gross_sales, a.net_sales, a.ar_value, a.disc_total_principal
            from dbrest.penta_mg_so_detail a 
            where a.id_header = $id_header
        ";
        return $this->db->query($query);
    }

    public function update_penta_header($tanggal, $status)
    {
        $query = "
            update dbrest.penta_mg_so_header a 
            set a.response = '$status'
            where a.deleted_at is null
        ";
        return $this->db->query($query);
    }

    public function get_penta_header_where_response_null()
    {
        $query = "
            select a.tgl_order
            from dbrest.penta_mg_so_header a 
            where (a.response is null or a.response = '') and a.deleted_at is null
            GROUP BY a.tgl_order 
        ";
        return $this->db->query($query);
    }

    public function get_data_mg_mpm_penta()
    {
        $query = "
            select *
            from dbrest.penta_mg_so_header a;
        ";
        return $this->db->query($query);
    }

    public function get_penta_sales_join_origin($bulan, $tahun)
    {   
        $query = "
            select *
            from site.penta_sales_join_origin
            where bulan = $bulan and tahun = $tahun
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }


    public function insert_penta_sales_join_origin($signature, $id_log, $id_log_ext)
    {
        $query = "
            insert into site.penta_sales_join_origin
            select 	'', a.id_log, a.bulan, a.tahun, a.principal_id, a.area_id, a.nama_area, a.tanggal_invoice, a.nomor_invoice,
                    a.nomor_sales_order, a.customer_po_number, a.kode_outlet, a.kode_outlet_lama,
                    a.nama_outlet, a.category_produk, a.sales_order_line, a.kode_produk, a.kode_produk_lama,
                    a.inventory_item_id, a.item_id_vend, a.id_item_sapora, a.category_product_principal,
                    a.nama_produk, a.qty, a.uom, a.price, a.total_disc, a.total_vat, a.total_gross, a.total_net,
                    a.bonus, a.discount_value_distributor, a.discount_value_prinsipal,
                    a.discount_value_extra, a.discount_persen_distributor, a.discount_persen_prinsipal, 
                    a.discount_persen_extra, a.nomor_discount_distributor, a.nomor_discount_prinsipal, 
                    a.nomor_discount_extra, a.type_data, a.nama_sales, a.batch, a.type_promo,
                    a.keterangan_promo, a.dpl_do_disc_princ, b.address, b.province, b.city, b.npwp, b.channel, a.signature
            from site.penta_sales_origin a left join (
                select 	a.nomor_invoice, a.address, a.province, a.city, a.npwp, a.channel
                from site.penta_sales_ext_origin a 
                where a.id_log = $id_log_ext
            )b on a.nomor_invoice = b.nomor_invoice
            where a.id_log = $id_log and a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function delete_penta_sales_join_origin($bulan, $tahun)
    {
        $query = "
            delete 
            from site.penta_sales_join_origin 
            where bulan = $bulan and tahun = $tahun
        ";
        return $this->db->query($query);
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
    }


    public function export_sales_join_origin($signature)
    {

        $supp =$this->session->userdata('supp');
        if($supp == '001')
        {
            $params = " and (b.supp != '005' or b.supp is null)";
        }else{
            $params = "";
        }
        // die;

        $query = "
            select 
                    a.bulan, a.tahun, a.principal_id, a.area_id, a.nama_area, a.tanggal_invoice, 
                    a.nomor_invoice, a.nomor_sales_order, a.customer_po_number,
                    a.kode_outlet, a.kode_outlet_lama, a.nama_outlet, a.category_produk, 
                    a.sales_order_line, a.kode_produk, a.kode_produk_lama,
                    a.inventory_item_id, a.item_id_vend, a.id_item_sapora,
                    a.category_product_principal, a.nama_produk, a.qty, a.uom,
                    a.price, a.total_disc, a.total_vat, a.total_gross, a.total_net, a.bonus, 
                    a.discount_value_distributor, a.discount_value_prinsipal, a.discount_value_extra,
                    a.discount_persen_distributor, a.discount_persen_prinsipal, a.discount_persen_extra,
                    a.nomor_discount_prinsipal, a.nomor_discount_extra,
                    a.type_data, a.nama_sales, a.batch, a.type_promo, a.address,
                    a.province, a.city, a.npwp, a.channel, a.nomor_discount_distributor, a.keterangan_promo, a.dpl_do_disc_princ
            from site.penta_sales_join_origin a left join (
                select a.kodeprod, a.namaprod, a.supp
                from site.master_product a 
            )b on a.item_id_vend = b.kodeprod
            where a.signature = '$signature' $params
        "; 

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $hasil = $this->db->query($query);  
        query_to_csv($hasil,TRUE,'Export Sales.csv');
    }

    public function get_flag_hari($tanggal)
    {
        $query = "
            select a.hari
            from site.absensi_master_tanggal a
            where a.tanggal = '$tanggal'
        ";

        return $this->db->query($query);
    }

    public function delete_penta_stock_all($bulan, $tahun)
    {
        $query = "
            delete 
            from site.penta_stock_all
            where bulan = $bulan and tahun = $tahun
        ";
        return $this->db->query($query);
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
    }

    public function update_log_stock_all($data, $id_log)
    {
        $this->db->where('id', $id_log);
        $this->db->update('site.penta_log_stock_all', $data);
        return true;
    }

    public function get_sum_stock_all($id_log)
    {
        $query = "
            select sum(a.qty) as total_qty, sum(a.qty * a.hna) as total_value
            from site.penta_stock_all a 
            where a.id_log = $id_log
        ";
        return $this->db->query($query);
    }

    public function get_penta_stock_all($token, $tahun, $bulan, $signature, $id_log)
    {
        // echo "get_penta_stock_all";die;
        $url_penta = getenv('PENTA_API').'list/stock/all';
        $curl = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($curl, CURLOPT_URL, $url_penta);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $array_response = json_decode($result, true);

        if (isset($array_response['data'])) 
        {

            // $signature = 'penta-stock-' . rand() . md5($this->created_at) . date('Ymd');

            // $data = [
            //     "created_at" => $this->created_at,
            //     "created_by" => $this->userid,
            //     "token" => $token,
            //     "tahun" => $tahun,
            //     "bulan" => $bulan,
            //     "signature" => $signature
            // ];

            // $this->db->insert('site.penta_log_stock_all', $data);
            // $id_log = $this->db->insert_id();

            foreach ($array_response['data'] as $key) 
            {
                $nama_area = $key["nama_area"];
                $principal = $key["principal"];
                $subinventory_code = $key["subinventory_code"];
                $kode_produk = $key["kode_produk"];
                $nama_produk = $key["nama_produk"];
                $divisi = $key["divisi"];
                $hna = $key["hna"];
                $qty = $key["qty"];
                $uom = $key["uom"];
                $batch = $key["batch"];
                $tanggal_penerimaan = $key["tanggal_penerimaan"];
                $expired_date = $key["expired_date"];

                $data = [
                    "id_log"=> $id_log,
                    "bulan" => $bulan,
                    "tahun" => $tahun,
                    "nama_area" => $nama_area,
                    "principal" => $principal,
                    "subinventory_code" => $subinventory_code,
                    "kode_produk" => $kode_produk,
                    "nama_produk" => $nama_produk,
                    "divisi" => $divisi,
                    "qty" => $qty,
                    "uom" => $uom,
                    "batch" => $batch,
                    "tanggal_penerimaan" => $tanggal_penerimaan,
                    "hna" => $hna,
                    "expired_date" => $expired_date,
                    "created_at" => $this->created_at,
                    "created_by" => $this->userid,
                    "signature" => $signature
                ];

                $this->db->insert('site.penta_stock_all', $data);
            }
            
            return $id_log;

            // $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
            // redirect('penta/log_sales', 'refresh');

        }else{
            echo "error : " . $err;
            die;
        }
    }

    public function get_penta_log_stock_all($signature = "")
    {

        if ($signature) {
            $params_signature = "where a.signature = '$signature'";
        }else{
            $params_signature = "";
        }

        $query = "
            select a.*, b.username
            from site.penta_log_stock_all a left join site.master_user b 
                on a.created_by = b.id
            $params_signature
            order by a.id desc
            limit 1000
        ";
        return $this->db->query($query);
    }

    public function export_stock_all($signature)
    {
        $query = "
            select a.bulan, a.tahun, a.nama_area, a.principal, a.subinventory_code, a.kode_produk, a.nama_produk, a.divisi, a.qty, a.uom, a.batch, 
            a.tanggal_penerimaan, a.hna, a.expired_date
            from site.penta_stock_all a 
            where a.signature = '$signature'
        "; 
    
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export Stock All.csv');
    }

    public function get_penta_sales_origin_closing_by_tahun_bulan($tahun, $bulan)
    {
        
        $query = "
            select *
            from site.penta_sales_origin_closing a
            where a.tahun = $tahun and bulan = $bulan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }
}