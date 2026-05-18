<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_warpin extends CI_Model
{

    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            'base_uri'  => 'http://localhost:81/restapi/api/master_data/',
            'auth'      => ['admin','1234']
        ]);
        // $this->_client = new Client([
        //     'base_uri'  => 'https://midas-staging.warungpintar.co/webhook/v1/',
        //     'auth'      => ['admin','1234']
        // ]);
    }

    public function test_api()
    {
        $response = $this->_client->request('GET', 'site', [
            'query' => [
                'X-API-KEY' => '123',
                'token'     => '11f3a8a682c1e8d097ae60d72ecf07c7',
                'kode'      => 'mlg27'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(),true);

        var_dump($result);

    }

    public function test_api_post()
    {
        $response = $this->_client->request('POST', 'listcabang', [
            'query' => [
                'X-API-KEY' => '123',
                'token'     => '11f3a8a682c1e8d097ae60d72ecf07c7',
                'kode'      => 'mlg27'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(),true);

        var_dump($result);

    }

    public function get_warpin_action(){
        $this->db->where('status', 1);
        return $this->db->get('site.t_warpin_action');
    }

    public function get_warpin_log(){
        $this->db->order_by('id','desc');
        return $this->db->get('site.log_warpin_api');
    }

    public function get_warpin_coverage(){
        $query = "
        select a.site_code, a.coverage, a.created_at, b.branch_name, b.nama_comp
        from site.m_warpin_coverage a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, urutan
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab) 
        )b on a.site_code = b.site_code
        ORDER BY b.urutan
        ";
        return $this->db->query($query);
    }

    public function get_warpin_order(){
        return $this->db->get('site.t_warpin_order');
    }

    public function get_erp_order_status($signature)
    {
        // return $this->db->get_where('site.t_erp_order_status', array('signature_mpm' => $signature));
        $query = "
        select 	a.id, a.from_aplikasi, a.invoice_aplikasi, a.signature_mpm, a.invoice_sds, a.status_erp, a.nama_status_erp,
				a.tanggal_update, a.payment_total, a.signature, b.kodeprod, b.namaprod, 
                b.total_qty_pemenuhan, b.satuan_pemenuhan, b.total_qty_pemenuhan, b.satuan_pemenuhan,
                b.total_qty_pemenuhan_pcs, b.satuan_pemenuhan_pcs, b.hna, b.total_harga,
                b.status_cancel, b.alasan_cancel
        from site.t_erp_order_status a LEFT JOIN
        (
            select 	a.id_order_status, a.kodeprod, a.namaprod, a.total_qty_order, a.satuan_order, a.total_qty_pemenuhan, a.satuan_pemenuhan, a.total_qty_pemenuhan_pcs, a.satuan_pemenuhan_pcs, a.hna, a.total_harga, a.status_cancel, a.alasan_cancel, a.created_at
            from site.t_erp_order_status_detail a
        )b on a.id = b.id_order_status
        where a.signature_mpm = '$signature' 
        order by a.id asc
        ";

        // echo "<pre><br><br><br>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_warpin_order_detail($signature){
        // $query = "
        // select b.id, b.product_id, b.sku, b.uom, b.product_quantity, b.price_unit, c.namaprod, a.status_erp, a.signature,
        // a.client_so_number, a.merchant_name, a.merchant_shop_name, a.merchant_shop_address, a.merchant_phone, a.merchant_shop_postal_code, d.status_erp, d.nama_status_erp
        // from site.t_warpin_order a INNER JOIN 
        // (
        //     select *, if(length(a.product_id = 5), concat('0', a.product_id), a.product_id) as product_id_format
        //     from site.t_warpin_order_detail a
        // )b on a.id = b.id_order LEFT JOIN 
        // (
        //     select a.kodeprod, a.namaprod
        //     from mpm.tabprod a 
        // )c on b.product_id_format = c.kodeprod LEFT JOIN
        // (
        //     select 	a.status_erp, a.nama_status_erp, a.signature_mpm
        //     from 	site.t_erp_order_status a 
        //     where 	a.signature_mpm = '9342ff8d414a06a2b3f8b6ecbef1624b' and 
        //             a.id = (
        //                 select 	max(a.id)
        //                 from 	site.t_erp_order_status a 
        //                 where 	a.signature_mpm = '9342ff8d414a06a2b3f8b6ecbef1624b' 
        //             )
        // )d on a.signature = d.signature_mpm
        // where a.signature = '$signature'
        // ";

        $query = "
        select 	a.entity_id, a.order_number as client_so_number, a.order_date, a.status, 
                a.loc_code, a.vendor_code, a.acceptance_deadline, 
                a.shipping_method, a.payment_method, a.customer_name as merchant_name,
                a.customer_name as merchant_shop_name,
                a.customer_email, a.expedition_provider, a.expedition_service,
                a.subtotal, a.grand_total, a.shipping_amount, a.remark,
                a.generate_awb_method, a.shipping_address_street as merchant_shop_address, 
                a.shipping_address_city, a.shipping_address_region, 
                a.shipping_address_postcode as merchant_shop_postal_code, a.shipping_address_telephone as merchant_phone,
                a.created_at, a.updated_at, a.deleted_at, a.signature, 
                b.id, b.sku, b.vendor_sku as uom, b.name, b.qty as product_quantity, 
                b.price as price_unit, b.subtotal, b.discount_amount, b.remark, 
                b.kodeprod as product_id,
                c.kodeprod, c.namaprod, c.apps_namaprod,
                d.status_erp, d.nama_status_erp
        from site.t_warpin_order a INNER JOIN 
        (
            select 	a.id, a.order_ref, a.parent_item_id, a.sku, left(a.sku, 6) as kodeprod, 
                    a.vendor_sku, a.name, a.qty, a.price, a.subtotal, a.discount_amount, a.remark
            from 	site.t_warpin_order_detail a
        )b on a.id = b.order_ref LEFT JOIN 
        (
            select a.kodeprod, a.namaprod, a.apps_namaprod
            from mpm.tabprod a 
        )c on b.kodeprod = c.kodeprod LEFT JOIN
        (
            select 	a.status_erp, a.nama_status_erp, a.signature_mpm
            from 	site.t_erp_order_status a 
            where 	a.signature_mpm = '$signature' and 
                    a.id = (
                        select 	max(a.id)
                        from 	site.t_erp_order_status a 
                        where 	a.signature_mpm = '$signature' 
                    )
        )d on a.signature = d.signature_mpm

        where a.signature = '$signature'
        ";

        // echo "<pre><br><br><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_last_erp_status($signature){
        $query = "
            select *
            from site.t_erp_order_status a
            where a.signature_mpm = '$signature'
            order by id desc
            limit 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_kodeprod_by_id($id){
        $query = "
            select if(length(a.product_id = 5), concat('0',a.product_id), a.product_id) as product_id
            from site.t_warpin_order_detail a
            where a.id = $id
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_namaprod_by_kodeprod($kodeprod){
        $query = "
            select namaprod
            from mpm.tabprod a
            where a.kodeprod = if(length($kodeprod)=5,concat('0',$kodeprod),$kodeprod)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_warpin_order_detail_by_id($id){
        $query = "
            select * from site.t_warpin_order_detail a
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_warpin_report(){
        $query = "
        select  a.entity_id, a.order_number, a.order_date, a.status, 
                a.loc_code, a.vendor_code, a.acceptance_deadline, 
                a.shipping_method, a.payment_method, a.customer_name,
                a.customer_email, a.expedition_provider, a.expedition_service,
                a.subtotal, a.grand_total, a.shipping_amount, a.remark,
                a.generate_awb_method, a.shipping_address_street, 
                a.shipping_address_city, a.shipping_address_region, 
                a.shipping_address_postcode, a.shipping_address_telephone,
                a.created_at, a.updated_at, a.deleted_at, a.signature,
                b.*
        from site.t_warpin_order a left join 
        (
            select a.site_code, a.branch, a.subbranch, a.loc_code, a.vendor_code
            from site.m_site_warpin a 
        )b on a.loc_code = b.loc_code and a.vendor_code = b.vendor_code 
        ";

        echo "<pre><br><br><br>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    
}
