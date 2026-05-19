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

    // =============== palu ===============
    public function get_mpm_upload($id, $bulan, $tahun)
    {

        $query = "
            select *
            from mpm.upload a 
            where a.userid = $id and a.bulan = $bulan and a.tahun = $tahun and a.status_closing = 1
            order by a.id desc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_penta_sales_palu_by_tahun_bulan($tahun, $bulan, $area_id)
    {
        
        $query = "
            select *
            from site.penta_sales_palu a
            where a.tahun = $tahun and bulan = $bulan and a.area_id = $area_id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function delete_penta_sales_palu($tahun, $bulan, $area_id)
    {
        $query = "
            delete 
            from site.penta_sales_palu 
            where bulan = $bulan and tahun = $tahun and area_id = $area_id
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_sum_sales_palu($id_log)
    {
        $query = "
            select sum(a.total_net) as total_net, sum(a.total_gross) as total_gross
            from site.penta_sales_palu a 
            where a.id_log = $id_log
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    } 

    public function get_temp_sales_palu_by_idlog($id_log)
    {
        $query = "
            select *
            from site.temp_penta_sales_palu a
            where a.id_log = $id_log
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }
    public function insert_penta_sales_palu($tahun, $bulan, $signature, $id_log)
    {
        $query = "
            INSERT INTO site.penta_sales_palu (id_log, bulan, tahun, principal_id, area_id, nama_area, tanggal_invoice, nomor_invoice, nomor_sales_order,
            customer_po_number, kode_outlet, kode_outlet_lama, nama_outlet, category_produk, sales_order_line, kode_produk, kode_produk_lama, inventory_item_id,
            item_id_vend, id_item_sapora, category_product_principal, nama_produk, qty, uom, price, total_disc, total_vat, total_gross, total_net, bonus, 
            total_discount_value_distributor, total_discount_value_prinsipal, total_discount_value_extra, disc_persen_distributor_val_1, discount_value_distributor_1, 
            nomor_discount_distributor_val_1, disc_persen_distributor_val_2, discount_value_distributor_2, nomor_discount_distributor_val_2, 
            disc_persen_distributor_val_3, discount_value_distributor_3, nomor_discount_distributor_val_3, disc_persen_prinsipal_val_1, discount_value_prinsipal_1, 
            nomor_discount_prinsipal_val_1, disc_persen_prinsipal_val_2, discount_value_prinsipal_2, nomor_discount_prinsipal_val_2, disc_persen_prinsipal_val_3, 
            discount_value_prinsipal_3, nomor_discount_prinsipal_val_3, disc_persen_extra_val_1, discount_value_extra_1, nomor_discount_extra_val_1, 
            disc_persen_extra_val_2, discount_value_extra_2, nomor_discount_extra_val_2, disc_persen_extra_val_3, discount_value_extra_3, nomor_discount_extra_val_3, 
            batch, type_data, nama_sales, type_promo, keterangan_promo, dpl, created_at, created_by, signature)

            SELECT id_log, bulan, tahun, principal_id, area_id, nama_area, tanggal_invoice, nomor_invoice, nomor_sales_order, customer_po_number, kode_outlet, 
            kode_outlet_lama, nama_outlet, category_produk, sales_order_line, kode_produk, kode_produk_lama, inventory_item_id, item_id_vend, id_item_sapora, 
            category_product_principal, nama_produk, qty, uom, price, total_disc, total_vat, total_gross, total_net, bonus, total_discount_value_distributor, 
            total_discount_value_prinsipal, total_discount_value_extra, disc_persen_distributor_val_1, discount_value_distributor_1, nomor_discount_distributor_val_1, 
            disc_persen_distributor_val_2, discount_value_distributor_2, nomor_discount_distributor_val_2, disc_persen_distributor_val_3, discount_value_distributor_3, 
            nomor_discount_distributor_val_3, disc_persen_prinsipal_val_1, discount_value_prinsipal_1, nomor_discount_prinsipal_val_1, disc_persen_prinsipal_val_2, 
            discount_value_prinsipal_2, nomor_discount_prinsipal_val_2, disc_persen_prinsipal_val_3, discount_value_prinsipal_3, nomor_discount_prinsipal_val_3, 
            disc_persen_extra_val_1, discount_value_extra_1, nomor_discount_extra_val_1, disc_persen_extra_val_2, discount_value_extra_2, nomor_discount_extra_val_2, 
            disc_persen_extra_val_3, discount_value_extra_3, nomor_discount_extra_val_3, batch, type_data, nama_sales, type_promo, keterangan_promo, dpl, created_at, 
            created_by, signature
            FROM site.temp_penta_sales_palu
            WHERE signature = '$signature' and id_log = $id_log and bulan = $bulan and tahun = $tahun
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);

    }

    public function update_log_sales_palu($data, $id_log)
    {
        $this->db->where('id', $id_log);
        $this->db->update('site.penta_log_sales_palu', $data);
        return true;
    }

    public function get_penta_customer($token, $signature, $id_log, $area)
    {
        $url_penta = getenv('PENTA_API').'list/outlet/'.$area;
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


        if (!isset($array_response['data'])) {
            return false;
        }

        $this->db->truncate('site.temp_penta_outlet'); //trucate table sebelum insert data baru
 
        foreach ($array_response['data'] as $key)
        {
            $org_id             = $key["org_id"];
            $org_name           = $key["org_name"];
            $location           = $key["location"];
            $site_use_id        = $key["site_use_id"];
            $bill_ship_cust_name= $key["bill_ship_cust_name"];
            $prefix             = $key["prefix"];
            $address1           = $key["address1"];
            $address2           = $key["address2"];
            $address3           = $key["address3"];
            $city               = $key["city"];
            $province           = $key["province"];
            $primary_salesrep_id= $key["primary_salesrep_id"];
            $salesman_name      = $key["salesman_name"];

            $data = [
                "id_log"                => $id_log,
                "org_id"                => $org_id,
                "org_name"              => $org_name,
                "location"              => $location,
                "site_use_id"           => $site_use_id,
                "bill_ship_cust_name"   => $bill_ship_cust_name,
                "prefix"                => $prefix,
                "address1"              => $address1,
                "address2"              => $address2,
                "address3"              => $address3,
                "city"                  => $city,
                "province"              => $province,
                "primary_salesrep_id"   => $primary_salesrep_id,
                "salesman_name"         => $salesman_name,
                "created_at"            => $this->created_at,
                "created_by"            => $this->userid,
                "signature"             => $signature
            ];
            
            
            $this->db->insert('site.temp_penta_outlet', $data);
        }
        // die;
        return true;
        // return $id_log;
        // return array($id_log, $signature);
        // echo "success : " . $id_log;
        // die;
    }

    public function get_temp_outlet()
    {
        $query = "
                select *
                from site.temp_penta_outlet
            ";
        return $this->db->query($query)->result();

        // return $this->db->get('site.temp_penta_outlet')->result();
    }

    public function cek_outlet($org_id, $location)
    {
        $query = "
            select *
            from site.penta_outlet
            where org_id = $org_id and location = $location
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function insert_outlet($row, $signature, $userid)
    {
        $data = (array)$row;

        unset($data['id']);

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userid;
        $data['signature']  = $signature;

        $this->db->insert('site.penta_outlet', $data);
    }

    public function get_log_penta_customer()
    {
         $query = "
            select a.*, b.username
            from site.penta_log_customer a left join site.master_user b 
                on a.created_by = b.id
            ORDER BY a.id desc
            limit 10
        ";
        return $this->db->query($query);
    }
    

    public function get_customer()
    {
        $query = "
            select *
            from site.penta_outlet
        ";
        return $this->db->query($query);
    }

    public function get_customer_by_id($id)
    {
        $query = "
            select *
            from site.penta_outlet a
            where a.id = '$id'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }
    
    public function delete_fi_palu($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete 
            from data$tahun.fi
            where kode_comp = '$kode_comp' and nocab = '$nocab' and thndok = '$tahun' and bulan = '$bulan'
            ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
        
    }

    public function delete_ri_palu($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete 
            from data$tahun.ri
            where kode_comp = '$kode_comp' and nocab = '$nocab' and thndok = '$tahun' and bulan = '$bulan'
        ";
        return $this->db->query($query);
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
    }

    public function insert_fi_palu($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                a.nomor_invoice as nodokjdi, 
                a.nomor_invoice as nodokacu, 
                a.tanggal_invoice as tgldokjdi,
                a.sales_id as kodesales,
                '$kode_comp' as kode_comp, 
                '' as kode_kota, 
                b.typeid as kode_type,
                b.location as kode_lang, 
                '' as koderayon, 
                a.item_id_vend as kodeprod, 
                c.supp, 
                DATE_FORMAT(a.tanggal_invoice, '%d') as hrdok,
                DATE_FORMAT(a.tanggal_invoice, '%m') as blndok, 
                year(a.tanggal_invoice) as thndok, 
                c.namaprod, 
                c.grupprod as groupprod,
                a.qty as banyak, 
                round(a.total_gross*1.11/a.qty,2) as harga, 
                round(a.total_disc*1.11,2) as potongan,
                round(a.total_gross*1.11,2) as tot1,
                '' as jum_promo, 
                '' as keterangan, 
                '' as user_isi, 
                '' as jam_isi, 
                '' as tgl_isi,
                '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                b.classid as kodesalur, 
                '' as kodebonus, '' as namabonus, '' as grupbonus, 
                '' as unitbonus, 
                a.sales_name as lampiran,
                '' as h_beli, 
                '' as kodearea, 
                b.address1 as namaarea,
                '' as pinjam, 
                '' as jualbanyak, 
                '' as jualpinjam, 
                '' as harga_excl, 
                '' as tot1_excl, 
                b.bill_ship_cust_name as namalang, 
                '$nocab' as nocab, 
                '$bulan' as bulan,
                '' as siteid, 
                '' as qty1, 
                '' as qty2, 
                '' as qty3, 
                '' as qty_bonus, 
                '' as flag_bonus, 
                '' as disc_persen,
                '' as disc_rp, 
                '' as disc_value, 
                a.disc_persen_extra_val_1 as disc_cabang, 
                a.disc_persen_prinsipal_val_1 as disc_prinsipal, 
                a.disc_persen_prinsipal_val_2 as disc_xtra,
                round(a.discount_value_extra_1*1.11,2) as rp_cabang, 
                round(a.discount_value_prinsipal_1*1.11,2) as rp_prinsipal, 
                round(a.discount_value_prinsipal_2*1.11,2) as rp_xtra, 
                '' as bonus, 
                concat('11', c.supp) as principalid,
                '' as ex_no_sales, 
                '' as status_retur, 
                '' as ref,
                '' as term_payment, 
                '' as tipe_kl, a.disc_persen_prinsipal_val_3 as disc_cod, round(a.discount_value_prinsipal_3*1.11,2) as rp_cod, '' as beban_bonus, 
                '' as disc_add_percent, '' as subarea_id
        from site.penta_sales_palu a left join (
            select a.location, a.bill_ship_cust_name, a.address1, a.primary_salesrep_id, a.salesman_name, a.typeid, a.classid, a.spot
            from site.penta_outlet a
            where a.org_id = 485
        )b on a.kode_outlet = b.location left join (
            select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
            from site.master_product_with_harga a 
        )c on a.item_id_vend = c.kodeprod
        where a.type_data = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.is_valid_customer = 1 and is_valid_type = 1 and is_valid_class = 1 
        and is_valid_spot = 1 and bulan = $bulan and tahun = $tahun and a.area_id = 485
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        // echo "<br>";
        return $this->db->query($query);
    }

    public function insert_fi_palu_bonus($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                a.nomor_invoice as nodokjdi, 
                a.nomor_invoice as nodokacu, 
                a.tanggal_invoice as tgldokjdi,
                a.sales_id as kodesales,
                '$kode_comp' as kode_comp, 
                '' as kode_kota, 
                b.typeid as kode_type,
                b.location as kode_lang, 
                '' as koderayon, 
                a.item_id_vend as kodeprod, 
                c.supp, 
                DATE_FORMAT(a.tanggal_invoice, '%d') as hrdok,
                DATE_FORMAT(a.tanggal_invoice, '%m') as blndok, 
                year(a.tanggal_invoice) as thndok, 
                c.namaprod, 
                c.grupprod as groupprod,
                '' as banyak, 
                (a.total_gross/a.qty) as harga, 
                '' as potongan, 
                '' as tot1, 
                '' as jum_promo, 
                '' as keterangan, 
                '' as user_isi, 
                '' as jam_isi, 
                '' as tgl_isi,
                '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                b.classid as kodesalur, 
                '' as kodebonus, '' as namabonus, '' as grupbonus, 
                'a.bonus' as unitbonus, 
                a.sales_name as lampiran,
                '' as h_beli, 
                '' as kodearea, 
                b.address1 as namaarea,
                '' as pinjam, 
                '' as jualbanyak, 
                '' as jualpinjam, 
                '' as harga_excl, 
                '' as tot1_excl, 
                b.bill_ship_cust_name as namalang, 
                '$nocab' as nocab, 
                '$bulan' as bulan,
                '' as siteid, 
                '' as qty1, 
                '' as qty2, 
                '' as qty3, 
                a.bonus as qty_bonus, 
                '1' as flag_bonus, 
                '' as disc_persen,
                '' as disc_rp, 
                '' as disc_value, 
                '' as disc_cabang, 
                '' as disc_prinsipal, 
                '' as disc_xtra,
                '' as rp_cabang, 
                '' as rp_prinsipal, 
                '' as rp_xtra, 
                '' as bonus, 
                concat('11', c.supp) as principalid,
                '' as ex_no_sales, 
                '' as status_retur, 
                '' as ref,
                '' as term_payment, 
                '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                '' as disc_add_percent, '' as subarea_id
        from site.penta_sales_palu a left join (
            select a.location, a.bill_ship_cust_name, a.address1, a.primary_salesrep_id, a.salesman_name, a.typeid, a.classid, a.spot
            from site.penta_outlet a
            where a.org_id = 485
        )b on a.kode_outlet = b.location left join (
            select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
            from site.master_product_with_harga a 
        )c on a.item_id_vend = c.kodeprod
        where a.type_data = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.is_valid_customer = 1 and is_valid_type = 1 and is_valid_class = 1 
        and is_valid_spot = 1 and bulan = $bulan and tahun = $tahun and a.area_id = 485 and a.bonus > 0
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // echo "<br>";
        return $this->db->query($query);
    }

    public function insert_ri_palu($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                a.nomor_invoice as nodokjdi, 
                a.nomor_invoice as nodokacu, 
                a.tanggal_invoice as tgldokjdi,
                a.sales_id as kodesales,
                '$kode_comp' as kode_comp, 
                '' as kode_kota, 
                b.typeid as kode_type,
                b.location as kode_lang, 
                '' as koderayon, 
                a.item_id_vend as kodeprod, 
                c.supp, 
                DATE_FORMAT(a.tanggal_invoice, '%d') as hrdok,
                DATE_FORMAT(a.tanggal_invoice, '%m') as blndok, 
                year(a.tanggal_invoice) as thndok, 
                c.namaprod, 
                c.grupprod as groupprod,
                a.qty as banyak, 
                (a.total_gross/a.qty) as harga, 
                a.total_disc*1.11 as potongan, 
                a.total_gross*1.11 as tot1, 
                '' as jum_promo, 
                '' as keterangan, 
                '' as user_isi, 
                '' as jam_isi, 
                '' as tgl_isi,
                '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                b.classid as kodesalur, 
                '' as kodebonus, '' as namabonus, '' as grupbonus, 
                '' as unitbonus, 
                a.sales_name as lampiran,
                '' as h_beli, 
                '' as kodearea, 
                b.address1 as namaarea,
                '' as pinjam, 
                '' as jualbanyak, 
                '' as jualpinjam, 
                '' as harga_excl,
                b.bill_ship_cust_name as namalang, 
                '$nocab' as nocab, 
                '$bulan' as bulan,
                '' as siteid, 
                '' as qty1, 
                '' as qty2, 
                '' as qty3, 
                '' as qty_bonus, 
                '' as flag_bonus, 
                '' as disc_persen,
                '' as disc_rp, 
                '' as disc_value, 
                a.disc_persen_extra_val_1 as disc_cabang, 
                a.disc_persen_prinsipal_val_1 as disc_prinsipal, 
                a.disc_persen_prinsipal_val_2 as disc_xtra,
                a.discount_value_extra_1*1.11 as rp_cabang, 
                a.discount_value_prinsipal_1*1.11 as rp_prinsipal, 
                a.discount_value_prinsipal_2*1.11 as rp_xtra, 
                '' as bonus, 
                concat('11', c.supp) as principalid,
                '' as ex_no_sales, 
                '' as status_retur, 
                '' as ref,
                '' as term_payment, 
                '' as tipe_kl, a.disc_persen_prinsipal_val_3 as disc_cod, a.discount_value_prinsipal_3*1.11 as rp_cod, '' as beban_bonus, 
                '' as disc_add_percent, '' as subarea_id
        from site.penta_sales_palu a left join (
            select a.location, a.bill_ship_cust_name, a.address1, a.primary_salesrep_id, a.salesman_name, a.typeid, a.classid, a.spot
            from site.penta_outlet a
            where a.org_id = 485
        )b on a.kode_outlet = b.location left join (
            select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
            from site.master_product_with_harga a 
        )c on a.item_id_vend = c.kodeprod
        where a.type_data = 'Return' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.is_valid_customer = 1 and is_valid_type = 1 and is_valid_class = 1 
        and is_valid_spot = 1 and bulan = $bulan and tahun = $tahun and a.area_id = 485
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // echo "<br>";
        return $this->db->query($query);
    }

    public function insert_ri_palu_bonus($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                a.nomor_invoice as nodokjdi, 
                a.nomor_invoice as nodokacu, 
                a.tanggal_invoice as tgldokjdi,
                a.sales_id as kodesales,
                '$kode_comp' as kode_comp, 
                '' as kode_kota, 
                b.typeid as kode_type,
                b.location as kode_lang, 
                '' as koderayon, 
                a.item_id_vend as kodeprod, 
                c.supp, 
                DATE_FORMAT(a.tanggal_invoice, '%d') as hrdok,
                DATE_FORMAT(a.tanggal_invoice, '%m') as blndok, 
                year(a.tanggal_invoice) as thndok, 
                c.namaprod, 
                c.grupprod as groupprod,
                '' as banyak, 
                (a.total_gross/a.qty) as harga, 
                '' as potongan, 
                '' as tot1, 
                '' as jum_promo, 
                '' as keterangan, 
                '' as user_isi, 
                '' as jam_isi, 
                '' as tgl_isi,
                '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                b.classid as kodesalur, 
                '' as kodebonus, '' as namabonus, '' as grupbonus, 
                'a.bonus' as unitbonus, 
                a.sales_name as lampiran,
                '' as h_beli, 
                '' as kodearea, 
                b.address1 as namaarea,
                '' as pinjam, 
                '' as jualbanyak, 
                '' as jualpinjam, 
                '' as harga_excl,
                b.bill_ship_cust_name as namalang, 
                '$nocab' as nocab, 
                '$bulan' as bulan,
                '' as siteid, 
                '' as qty1, 
                '' as qty2, 
                '' as qty3, 
                a.bonus as qty_bonus, 
                '1' as flag_bonus, 
                '' as disc_persen,
                '' as disc_rp, 
                '' as disc_value, 
                '' as disc_cabang, 
                '' as disc_prinsipal, 
                '' as disc_xtra,
                '' as rp_cabang, 
                '' as rp_prinsipal, 
                '' as rp_xtra, 
                '' as bonus, 
                concat('11', c.supp) as principalid,
                '' as ex_no_sales, 
                '' as status_retur, 
                '' as ref,
                '' as term_payment, 
                '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                '' as disc_add_percent, '' as subarea_id
        from site.penta_sales_palu a left join (
            select a.location, a.bill_ship_cust_name, a.address1, a.primary_salesrep_id, a.salesman_name, a.typeid, a.classid, a.spot
            from site.penta_outlet a
            where a.org_id = 485
        )b on a.kode_outlet = b.location left join (
            select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
            from site.master_product_with_harga a 
        )c on a.item_id_vend = c.kodeprod
        where a.type_data = 'Return' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.is_valid_customer = 1 and is_valid_type = 1 and is_valid_class = 1 
        and is_valid_spot = 1 and bulan = $bulan and tahun = $tahun and a.area_id = 485 and a.bonus < 0
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // echo "<br>";
        return $this->db->query($query);
    }

    public function delete_tblang_palu($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_palu($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_tblang($tahun, $site_code)
    {
        $query = "
            insert into data$tahun.tblang
            select * from(				
                SELECT				
                KODE_COMP,				
                KODE_KOTA,				
                KODE_TYPE,				
                KODE_LANG,				
                KODERAYON,				
                NAMA_LANG,				
                NAMAAREA as ALAMAT1,				
                '' as ALAMAT2,				
                '' as TELP,				
                '' as KODEPOS,				
                '' as TGL,				
                '' as NPWP,				
                '0' as BTS_UTANG,				
                '0' as SALES01,				
                '0' as SALES02,				
                '0' as SALES03,				
                '0' as SALES04,				
                '0' as SALES05,				
                '0' as SALES06,				
                '0' as SALES07,				
                '0' as SALES08,				
                '0' as SALES09,				
                '0' as SALES10,				
                '0' as SALES11,				
                '0' as SALES12,				
                '0' as KET,				
                '0' as DEBIT,				
                '0' as KREDIT,				
                KODESALUR as KODESALUR,				
                '0' as TOP,				
                'Y' as AKTIF,				
                '' as TGL_AKTIF,				
                'T' as PPN,				
                '0' as KODE_LAMA,				
                '1' as JUM_DOK,				
                '0' as STATJUAL,				
                '0' as LIMIT1,				
                '' as TGLNAKTIF,				
                '' as ALAMAT_WP,				
                '' as NILAI_PPN,				
                '' as NAMA_WP,				
                '' as NEWFLD,				
                nocab as NOCAB,				
                '' as kodelang_copy,				
                '' as id_provinsi,				
                '' as nama_provinsi,				
                '' as id_kota,				
                '' as nama_kota,				
                '' as id_kecamatan,				
                '' as nama_kecamatan,				
                '' as id_kelurahan,				
                '' as nama_kelurahan,				
                '' as phone,				
                '' as tipe_bayar,				
                '' as credit_limit,				
                '' AS last_updated,				
                '' as status_blacklist,				
                '' as status_payment,				
                '' as CUSTID,				
                '' as COMPID,				
                '' as LATITUDE,				
                '' as LONGITUDE,				
                '' as FOTO_DISP,				
                '' as FOTO_TOKO,
                '' as KODE_SPOT,
                '' as subarea_id
                FROM(				
                    SELECT CONCAT(KODE_COMP,KODE_LANG,max(BULAN)) as mapp				
                    FROM data$tahun.fi 
                    WHERE concat(kode_comp, nocab) = '$site_code' 				
                    GROUP BY kode_comp,KODE_LANG 				
                )A				
                LEFT JOIN 				
                (				
                SELECT * FROM(				
                    SELECT *, CONCAT(KODE_COMP, KODE_LANG,BULAN) as mapp			
                    FROM data$tahun.fi   				
                    WHERE concat(kode_comp, nocab) = '$site_code'				
                    GROUP BY MAPP 				
                    )A				
                )C USING(MAPP)				
                union ALL				
                SELECT				
                KODE_COMP,				
                KODE_KOTA,				
                KODE_TYPE,				
                KODE_LANG,				
                KODERAYON,				
                NAMA_LANG,				
                NAMAAREA as ALAMAT1,				
                '' as ALAMAT2,				
                '' as TELP,				
                '' as KODEPOS,				
                '' as TGL,				
                '' as NPWP,				
                '0' as BTS_UTANG,				
                '0' as SALES01,				
                '0' as SALES02,				
                '0' as SALES03,				
                '0' as SALES04,				
                '0' as SALES05,				
                '0' as SALES06,				
                '0' as SALES07,				
                '0' as SALES08,				
                '0' as SALES09,				
                '0' as SALES10,				
                '0' as SALES11,				
                '0' as SALES12,				
                '0' as KET,				
                '0' as DEBIT,				
                '0' as KREDIT,				
                KODESALUR as KODESALUR,				
                '0' as TOP,				
                'Y' as AKTIF,				
                '' as TGL_AKTIF,				
                'T' as PPN,				
                '0' as KODE_LAMA,				
                '1' as JUM_DOK,				
                '0' as STATJUAL,				
                '0' as LIMIT1,				
                '' as TGLNAKTIF,				
                '' as ALAMAT_WP,				
                '' as NILAI_PPN,				
                '' as NAMA_WP,				
                '' as NEWFLD,				
                nocab as NOCAB,				
                '' as kodelang_copy,				
                '' as id_provinsi,				
                '' as nama_provinsi,				
                '' as id_kota,				
                '' as nama_kota,				
                '' as id_kecamatan,				
                '' as nama_kecamatan,				
                '' as id_kelurahan,				
                '' as nama_kelurahan,				
                '' as phone,				
                '' as tipe_bayar,				
                '' as credit_limit,				
                '' AS last_updated,				
                '' as status_blacklist,				
                '' as status_payment,				
                '' as CUSTID,				
                '' as COMPID,				
                '' as LATITUDE,				
                '' as LONGITUDE,				
                '' as FOTO_DISP,				
                '' as FOTO_TOKO,
                '' as KODE_SPOT,
                '' as subarea_id
                FROM(				
                    SELECT CONCAT(KODE_COMP,KODE_LANG,max(BULAN)) as mapp				
                    FROM data$tahun.ri  				
                    WHERE concat(kode_comp, nocab) = '$site_code'				
                    GROUP BY kode_comp,KODE_LANG 				
                )A				
                LEFT JOIN 				
                (				
                SELECT * FROM(SELECT *,CONCAT(KODE_COMP,KODE_LANG,BULAN) as mapp
                    FROM data$tahun.ri  				
                    WHERE concat(kode_comp, nocab) = '$site_code' 				
                    GROUP BY MAPP 				
                    )A				
                )C USING(MAPP)				
                )a group by kode_comp,kode_lang
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;

        return $this->db->query($query);
    }

    public function update_spot_tblang($tahun, $site_code)
    {   
        $query = "
            update data$tahun.tblang a 
            inner join site.penta_outlet b 
            on a.kode_lang = b.location
            set a.KODE_SPOT = b.spot
            WHERE concat(a.kode_comp, a.nocab) = '$site_code' and b.org_id = 485
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_tabsales($tahun, $site_code)
    {
        $query = "
            insert into data$tahun.tabsales
            SELECT	a.KODESALES,				
                    a.lampiran as NAMASALES,				
                    '' AS KODERAYON,				
                    'S'AS `STATUS`,				
                    '' AS ALAMAT1,				
                    '' AS ALAMAT2,				
                    ''AS NO_TELP,				
                    '' AS KODEPOS,				
                    '' AS PROPINSI,				
                    '' AS DATA1,				
                    '' AS TAHAP,				
                    '' AS FILEID,				
                    '' AS NAMA_DEPO,				
                    KODE_KOTA,				
                    '' AS KODE_GDG,				
                    '' AS NAMA_GDG,				
                    'Y' AS AKTIF,				
                    NOCAB 				
            FROM data$tahun.fi a inner JOIN 				
            (				
                SELECT kodesales, MAX(concat(kodesales,bulan)) times 				
                FROM data$tahun.fi 				
                where concat(kode_comp, nocab) = '$site_code'				
                GROUP BY KODESALES				
            )b ON b.times=concat(a.KODESALES,a.BULAN)				
            where concat(kode_comp, nocab) = '$site_code'				
            GROUP BY kodesales
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_product_penta_by_kodeprod($kodeprod)
    {
        $query = "
            select *
            from site.penta_master_produk_sales a
            where a.kode_produk_penta = '$kodeprod'
        ";
        return $this->db->query($query);
    }

    public function get_master_outlet_penta_by_kodeoutlet($kodeoutlet)
    {
        $query = "
            select *
            from site.penta_outlet a
            where a.location = '$kodeoutlet'
        ";
        return $this->db->query($query);
    }

    public function get_penta_sales_palu_where_is_valid_false($tahun, $bulan, $area_id)
    {
        $query = "
            select 	*
            from site.penta_sales_palu a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0  or a.is_valid_class = 0 or a.is_valid_type = 0 or a.is_valid_spot = 0
            and a.tahun = $tahun and a.bulan = $bulan and a.area_id = $area_id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_penta_customer_summary($area_id)
    {
        $query = "
            select a.org_name, a.location, a.bill_ship_cust_name, a.address1, a.typeid, a.classid, a.spot
            from site.penta_outlet a
            WHERE a.org_id = $area_id and (a.typeid IS NULL OR a.typeid = '' OR a.classid IS NULL OR a.classid = '' OR a.spot IS NULL OR a.spot = '')

        ";
        return $this->db->query($query);
    }

    public function get_result($site_code, $tahun, $bulan)
    {
        $query = "
            select sum(a.total_unit) as total_unit, sum(a.total_value) as total_value
            from 
            (
                select sum(a.banyak) as total_unit, sum(a.tot1) as total_value
                from data$tahun.fi a 
                where concat(a.kode_comp, a.nocab) = '$site_code' and a.bulan = $bulan
                union all 
                select sum(a.banyak) as total_unit, sum(a.tot1) as total_value
                from data$tahun.ri a 
                where concat(a.kode_comp, a.nocab) = '$site_code' and a.bulan = $bulan
            )a
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_upload($data)
    {
        $proses = $this->db->insert('mpm.upload', $data);
        return $this->db->insert_id();
    }

    public function get_log_sales($site_code)
    {
        $query = "
            select a.*, b.status_closing
            from site.penta_log_sales_palu a
            left join mpm.upload b 
            on a.id_upload = b.id
            where a.site_code = '$site_code'
            order by a.id desc
            limit 100
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_log_sales_bysignature($signature = "")
    {
        if($signature)
        {
            $params_signature = "where signature = '$signature'";
        }else{
            $params_signature = "";
        }
        $query = "
            select a.*, b.status_closing
            from site.penta_log_sales_palu a
            left join mpm.upload b 
            on a.id_upload = b.id
            $params_signature
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_product()
    {
        $query = "
            select *
            from site.penta_master_produk_sales a
        ";
        return $this->db->query($query);
    }

    public function get_master_product_summary()
    {
        $query = "
            select *
            from site.penta_master_produk_sales a 
            where a.qty is null or a.qty = '' or a.kode_produk_mpm is null or a.kode_produk_mpm = ''
        ";
        return $this->db->query($query);
    }

    public function get_product_by_id($id)
    {
        $query = "
            select *
            from site.penta_master_produk_sales a
            where a.id = '$id'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_product_by_kodeprod($kodeprod)
    {
        $query = "
            select *
            from site.master_product a
            where a.kodeprod = '$kodeprod'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_penta_sales_detail_palu($token, $tahun, $bulan, $signature, $id_log, $area)
    {
        // echo 'disini';die;
        $url_penta = getenv('PENTA_API').'list/sales_detail_discount/'.$tahun.'/'.$bulan.'/'.$area;
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
            $this->db->truncate('site.temp_penta_sales_palu'); // truncate table sebelum insert data baru

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

                $total_discount_value_distributor = $key["total_discount_value_distributor"];
                $total_discount_value_prinsipal = $key["total_discount_value_prinsipal"];
                $total_discount_value_extra = $key["total_discount_value_extra"];

                // distributor
                $disc_persen_distributor_val_1 = $key["disc_persen_distributor_val_1"];
                $discount_value_distributor_1 = $key["discount_value_distributor_1"];
                $nomor_discount_distributor_val_1 = $key["nomor_discount_distributor_val_1"];

                $disc_persen_distributor_val_2 = $key["disc_persen_distributor_val_2"];
                $discount_value_distributor_2 = $key["discount_value_distributor_2"];
                $nomor_discount_distributor_val_2 = $key["nomor_discount_distributor_val_2"];

                $disc_persen_distributor_val_3 = $key["disc_persen_distributor_val_3"];
                $discount_value_distributor_3 = $key["discount_value_distributor_3"];
                $nomor_discount_distributor_val_3 = $key["nomor_discount_distributor_val_3"];

                // prinsipal
                $disc_persen_prinsipal_val_1 = $key["disc_persen_prinsipal_val_1"];
                $discount_value_prinsipal_1 = $key["discount_value_prinsipal_1"];
                $nomor_discount_prinsipal_val_1 = $key["nomor_discount_prinsipal_val_1"];

                $disc_persen_prinsipal_val_2 = $key["disc_persen_prinsipal_val_2"];
                $discount_value_prinsipal_2 = $key["discount_value_prinsipal_2"];
                $nomor_discount_prinsipal_val_2 = $key["nomor_discount_prinsipal_val_2"];

                $disc_persen_prinsipal_val_3 = $key["disc_persen_prinsipal_val_3"];
                $discount_value_prinsipal_3 = $key["discount_value_prinsipal_3"];
                $nomor_discount_prinsipal_val_3 = $key["nomor_discount_prinsipal_val_3"];

                // extra
                $disc_persen_extra_val_1 = $key["disc_persen_extra_val_1"];
                $discount_value_extra_1 = $key["discount_value_extra_1"];
                $nomor_discount_extra_val_1 = $key["nomor_discount_extra_val_1"];

                $disc_persen_extra_val_2 = $key["disc_persen_extra_val_2"];
                $discount_value_extra_2 = $key["discount_value_extra_2"];
                $nomor_discount_extra_val_2 = $key["nomor_discount_extra_val_2"];

                $disc_persen_extra_val_3 = $key["disc_persen_extra_val_3"];
                $discount_value_extra_3 = $key["discount_value_extra_3"];
                $nomor_discount_extra_val_3 = $key["nomor_discount_extra_val_3"];

                $batch = $key["batch"];
                $type_data = $key["type_data"];
                $nama_sales = $key["nama_sales"];
                $type_promo = $key["type_promo"];
                $keterangan_promo = $key["keterangan_promo"];
                $dpl = $key["dpl"];

                $sales_id = $key["sales_id"];
                $sales_name = $key["sales_name"];

                $data = [
                    "id_log" => $id_log,
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

                    "total_discount_value_distributor" => $total_discount_value_distributor,
                    "total_discount_value_prinsipal" => $total_discount_value_prinsipal,
                    "total_discount_value_extra" => $total_discount_value_extra,

                    "disc_persen_distributor_val_1" => $disc_persen_distributor_val_1,
                    "discount_value_distributor_1" => $discount_value_distributor_1,
                    "nomor_discount_distributor_val_1" => $nomor_discount_distributor_val_1,
                    "disc_persen_distributor_val_2" => $disc_persen_distributor_val_2,
                    "discount_value_distributor_2" => $discount_value_distributor_2,
                    "nomor_discount_distributor_val_2" => $nomor_discount_distributor_val_2,
                    "disc_persen_distributor_val_3" => $disc_persen_distributor_val_3,
                    "discount_value_distributor_3" => $discount_value_distributor_3,
                    "nomor_discount_distributor_val_3" => $nomor_discount_distributor_val_3,

                    "disc_persen_prinsipal_val_1" => $disc_persen_prinsipal_val_1,
                    "discount_value_prinsipal_1" => $discount_value_prinsipal_1,
                    "nomor_discount_prinsipal_val_1" => $nomor_discount_prinsipal_val_1,
                    "disc_persen_prinsipal_val_2" => $disc_persen_prinsipal_val_2,
                    "discount_value_prinsipal_2" => $discount_value_prinsipal_2,
                    "nomor_discount_prinsipal_val_2" => $nomor_discount_prinsipal_val_2,
                    "disc_persen_prinsipal_val_3" => $disc_persen_prinsipal_val_3,
                    "discount_value_prinsipal_3" => $discount_value_prinsipal_3,
                    "nomor_discount_prinsipal_val_3" => $nomor_discount_prinsipal_val_3,

                    "disc_persen_extra_val_1" => $disc_persen_extra_val_1,
                    "discount_value_extra_1" => $discount_value_extra_1,
                    "nomor_discount_extra_val_1" => $nomor_discount_extra_val_1,
                    "disc_persen_extra_val_2" => $disc_persen_extra_val_2,
                    "discount_value_extra_2" => $discount_value_extra_2,
                    "nomor_discount_extra_val_2" => $nomor_discount_extra_val_2,
                    "disc_persen_extra_val_3" => $disc_persen_extra_val_3,
                    "discount_value_extra_3" => $discount_value_extra_3,
                    "nomor_discount_extra_val_3" => $nomor_discount_extra_val_3,

                    "batch" => $batch,
                    "type_data" => $type_data,
                    "nama_sales" => $nama_sales,
                    "type_promo" => $type_promo,
                    "keterangan_promo" => $keterangan_promo,
                    "dpl" => $dpl,

                    "sales_id" => $sales_id,
                    "sales_name" => $sales_name,

                    "created_at" => $this->created_at,
                    "created_by" => $this->userid,
                    "signature" => $signature
                ];

                $this->db->insert('site.temp_penta_sales_palu', $data);
            }

            // die;
            
            // return $id_log;
            return array($id_log, $signature);
            // echo "success : " . $id_log;
            // die;

        }else{
            echo "error : " . $err;
            die;
        }
    }

    public function cek_product_penta_from_sales($id_log, $bulan, $area_id)
    {
        $query = "
            select b.*, a.id_log, a.bulan, a.tahun, a.item_id_vend, a.kode_produk, a.nama_produk,  a.uom, c.kodeprod, c.NAMAPROD
            from site.penta_sales_palu a
            left join (
                select a.kode_produk_penta, a.kode_produk_mpm, a.nama_produk_penta as nama_produk_penta
                from site.penta_master_produk_sales a
            )b on a.kode_produk = b.kode_produk_penta
            left join mpm.tabprod c 
            on if(LENGTH(a.item_id_vend) = 5, concat('0',a.item_id_vend), a.item_id_vend) = c.kodeprod
            where a.id_log = $id_log and a.bulan = $bulan and area_id = $area_id
            group by a.kode_produk
        ";

        $proses =  $this->db->query($query);

        foreach ($proses->result() as $a)
        {
            $kode_produk_penta = $a->kode_produk;
            $item_id_vend_penta = $a->item_id_vend;
            $nama_produk_penta = $a->nama_produk;
            $uom               = $a->uom;
            $kode_produk_mpm   = $a->kodeprod;
            $nama_produk_mpm   = $a->NAMAPROD;

            // CEK: kalau belum ada di master
            if (empty($a->kode_produk_penta))
            {
                $data = [
                    "kode_produk_penta" => $kode_produk_penta,
                    "item_id_vend_penta" => $item_id_vend_penta,
                    "nama_produk_penta" => $nama_produk_penta,
                    "uom"               => $uom,
                    "kode_produk_mpm"   => $kode_produk_mpm,
                    "nama_produk_mpm"   => $nama_produk_mpm,
                    "tabel"             => 'penta_sales_palu',
                    "created_at"        => date('Y-m-d H:i:s'),
                    "created_by"        => $this->userid,
                ];

                $this->db->insert('site.penta_master_produk_sales', $data);
            }
        }

    }

    public function get_spot()
    {
        $query = "
            select *
            from site.master_spot a
            group by a.kode_spot_mapping
        ";
        return $this->db->query($query);
    }

    public function get_class()
    {
        $query = "
            select *
            from mpm.tbl_tabsalur a
            where a.active = 1
        ";
        return $this->db->query($query);
    }

    public function get_type()
    {
        $query = "
            select *
            from mpm.tbl_bantu_type a
            where a.active = 1
        ";
        return $this->db->query($query);
    }

}