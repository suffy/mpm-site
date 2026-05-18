<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_mes extends CI_Model 
{
    public function get_user($signature = ''){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from mes.m_user a
            where a.deleted_at is null
            $params
        ";
        return $this->db->query($query);
    }

    public function get_store($signature = ''){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from mes.m_store a
            where a.deleted_at is null
            $params
        ";
        return $this->db->query($query);
    }

    public function get_store_by_storeid($storeid){

        $query = "
            select a.nama_store 
            from mes.m_store a
            where a.storeid = '$storeid'
        ";
        return $this->db->query($query);
    }

    public function get_olshop($signature = ''){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from mes.m_olshop a
            where a.deleted_at is null
            $params
        ";
        return $this->db->query($query);
    }

    public function get_olshop_by_olshopid($olshopid){

        $query = "
            select a.nama_olshop 
            from mes.m_olshop a
            where a.olshopid = '$olshopid'
        ";
        return $this->db->query($query);
    }

    public function get_product($signature = ''){

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from mes.m_product a
            $params
        ";
        return $this->db->query($query);
    }

    public function get_sku_olshop($signature = ''){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, count(b.skuid) as count_product, c.nama_olshop
            from mes.m_sku_olshop a LEFT JOIN (
                select a.id, a.skuid, a.productid, a.qty_rule, a.olshopid, a.status_gimmick
                from mes.m_sku_olshop_detail a
                where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
            )b on a.skuid = b.skuid and a.olshopid = b.olshopid LEFT JOIN 
            (
                select a.olshopid, a.nama_olshop
                from mes.m_olshop a 
            )c on a.olshopid = c.olshopid
            where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00') $params
            GROUP BY a.skuid, a.olshopid
        ";

        return $this->db->query($query);
    }

    public function get_sku_olshop_detail($skuid){

        $query = "
            select * 
            from mes.m_sku_olshop_detail a left join mes.m_product b 
                on a.productid = b.productid
            where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00') and a.skuid = '$skuid'
        ";
        return $this->db->query($query);
    }

    public function get_transaksi($signature = '', $periode_1 = '', $periode_2 = ''){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }
        
        if ($periode_1 == '' && $periode_2 == '') {
            // echo "xxxxxxxxxxxx";
            $params_periode = "";
            $params_limit = "limit 100";
        }else{
            $params_periode = "and a.tgl_proses BETWEEN '$periode_1' and '$periode_2'";
            $params_limit = "";
        }

        // $query = "
        //     select a.*, b.username, count(c.no_invoice) as count_invoice, d.nama_store, e.nama_olshop
        //     from mes.t_proses_master a left join mpm.user b 
        //         on a.created_by = b.id LEFT JOIN mes.t_proses_detail c 
        //         on a.no_proses = c.no_proses left join 
        //         (
        //             select a.storeid, a.nama_store
        //             from mes.m_store a 
        //             where a.deleted_at is null
        //         )d on a.storeid = d.storeid left join 
        //         (
        //             select a.olshopid, a.nama_olshop
        //             from mes.m_olshop a
        //             where a.deleted_at is null
        //         )e on a.olshopid = e.olshopid
        //     where a.deleted_at is null $params_periode
        //     $params
        //     GROUP BY a.id
        //     ORDER BY a.id desc 
        //     limit 1
        // ";
        
        $query = "
            select  a.id, a.tgl_proses, a.no_proses, a.storeid, a.olshopid, 
                    a.status_posting, a.tgl_posting, a.signature, a.signature_import, 
                    a.created_at, a.created_by, b.count_invoice, c.username,
                    d.nama_store, e.nama_olshop
            from mes.t_proses_master a left join (
                select a.no_proses, count(a.no_invoice) as count_invoice
                from mes.t_proses_detail a 
                GROUP BY a.no_proses
            )b on a.no_proses = b.no_proses left join site.master_user c 
                on a.created_by = c.id left join 
                (
                    select a.storeid, a.nama_store
                    from mes.m_store a 
                    where a.deleted_at is null
                )d on a.storeid = d.storeid left join (
                    select a.olshopid, a.nama_olshop
                    from mes.m_olshop a
                    where a.deleted_at is null
                )e on a.olshopid = e.olshopid
                where a.deleted_at is null $params_periode
                $params
                order by a.id desc
                $params_limit
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function generate_transaksi($tgl_proses){

        $bulan_transaksi = date('m',strtotime($tgl_proses));
        $romawi = $this->getRomawi($bulan_transaksi);
        $tahun_transaksi = date('Y',strtotime($tgl_proses));
        $query = "
            select a.tgl_proses, a.no_proses, substr(a.no_proses,5,3) as urut
            from mes.t_proses_master a
            where year(a.tgl_proses) = $tahun_transaksi and month(a.tgl_proses) = $bulan_transaksi
            ORDER BY a.id desc
            limit 1
        ";

        $no_proses_current = $this->db->query($query);
        if ($no_proses_current->num_rows() > 0) {
            
            $params_urut = $no_proses_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "TRX-00$params_urut/MPM/$romawi/$tahun_transaksi";
            }elseif (strlen($params_urut) === 2) {
                $generate = "TRX-0$params_urut/MPM/$romawi/$tahun_transaksi";
            }else{
                $generate = "TRX-$params_urut/MPM/$romawi/$tahun_transaksi";
            }
        }else{
            $generate = "TRX-001/MPM/$romawi/$tahun_transaksi";
        }
        return $generate;
    }

    function getRomawi($bln){
        switch ($bln){
            case 1: 
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public function get_transaksi_detail($no_proses){

        $query = "
            select a.*, count(b.skuid) as count_sku 
            from mes.t_proses_detail a left join mes.t_proses_sku b 
                on a.id = b.id_invoice 
            where a.deleted_at is null and a.no_proses = '$no_proses'
            group by a.id
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_transaksi_detail_by_signature($signature){

        $query = "
            select a.*, b.olshopid, b.storeid, c.nama_olshop, d.nama_store, b.signature as signature_header
            from mes.t_proses_detail a LEFT JOIN 
            (
                select a.no_proses, a.storeid, a.olshopid, a.signature
                from mes.t_proses_master a
            )b on a.no_proses = b.no_proses LEFT JOIN
            (
                select a.olshopid, a.nama_olshop
                from mes.m_olshop a 
            )c on b.olshopid = c.olshopid LEFT JOIN 
            (
                select a.storeid, a.nama_store
                from mes.m_store a 
            )d on b.storeid = d.storeid
            where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00') and a.signature = '$signature'
        
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_transaksi_sku($id_transaksi_detail, $olshopid){

        $query = "
            select a.*, b.*, c.* 
            from mes.t_proses_sku a left join 
            (
                select a.skuid, a.olshopid, a.nama_sku, a.qty_rule
                from mes.m_sku_olshop a
            )b on a.skuid = b.skuid left join mpm.user c
                on a.created_by = c.id
            where a.deleted_at is null and a.id_invoice = $id_transaksi_detail and b.olshopid = '$olshopid'
        ";

        // echo "<pre><br><br><br>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_posting_preview($signature){
        

        $query = "
        select  a.tgl_proses, a.no_proses, a.storeid, a.olshopid, b.no_invoice, b.tgl_invoice, b.customer,
                c.skuid, c.qty_sku, d.productid, d.qty_rule, (c.qty_sku * d.qty_rule) as qty, e.nama_product, 
                f.username, a.created_at, a.status_posting, a.tgl_posting
        from mes.t_proses_master a LEFT JOIN (
            select a.id, a.no_proses, a.no_invoice, a.tgl_invoice, a.customer
            from mes.t_proses_detail a 
            where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
            group by a.no_proses, a.no_invoice
        )b on a.no_proses = b.no_proses LEFT JOIN 
        (
            select a.id, a.id_invoice, a.skuid, a.qty_sku
            from mes.t_proses_sku a 
            where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
        )c on b.id = c.id_invoice LEFT JOIN 
        (
            select a.skuid, a.olshopid, a.nama_sku, b.productid, b.qty_rule
            from mes.m_sku_olshop a left join 
            (
                select a.id, a.skuid, a.olshopid, a.productid, a.qty_rule
                from mes.m_sku_olshop_detail a
                where a.status_aktif = 1 and (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
            )b on a.skuid = b.skuid and a.olshopid = b.olshopid		
        )d on c.skuid = d.skuid and a.olshopid = d.olshopid LEFT JOIN 
        (
            select a.productid, a.nama_product
            from mes.m_product a 
            where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
        )e on d.productid = e.productid LEFT JOIN mpm.user f 
        on a.created_by = f.id
        where a.signature = '$signature' and (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
        ";

        // echo "<pre><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function proses_posting($signature)
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');

        // $this->db->trans_start();

        $query = "
            select  a.tgl_proses, a.no_proses, a.storeid, a.olshopid, 
                b.no_invoice, b.tgl_invoice, b.customer,
				c.skuid, c.qty_sku, d.productid, d.qty_rule, (c.qty_sku * d.qty_rule) as qty, e.nama_product, f.username
            from mes.t_proses_master a LEFT JOIN (
                select a.id, a.no_proses, a.no_invoice, a.tgl_invoice, a.customer
                from mes.t_proses_detail a 
                where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
                group by a.no_proses, a.no_invoice
            )b on a.no_proses = b.no_proses LEFT JOIN 
            (
                select a.id, a.id_invoice, a.skuid, a.qty_sku
                from mes.t_proses_sku a 
                where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
            )c on b.id = c.id_invoice LEFT JOIN 
            (
                select a.skuid, a.olshopid, a.nama_sku, b.productid, b.qty_rule
                from mes.m_sku_olshop a left join 
                (
                    select a.id, a.skuid, a.olshopid, a.productid, a.qty_rule
                    from mes.m_sku_olshop_detail a
                    where a.status_aktif = 1 and (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
                )b on a.skuid = b.skuid and a.olshopid = b.olshopid
            )d on c.skuid = d.skuid and a.olshopid = d.olshopid LEFT JOIN 
            (
                select a.productid, a.nama_product
                from mes.m_product a 
                where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
            )e on d.productid = e.productid LEFT JOIN mpm.user f 
            on a.created_by = f.id
            where a.signature = '$signature' and (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
        ";
        
        // echo "<pre><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $data_posting = $this->db->query($query);

        foreach ($data_posting->result() as $key) {
            $insert_posting = [
                'tgl_proses'    => $key->tgl_proses,
                'no_proses'     => $key->no_proses,
                'storeid'       => $key->storeid,
                'olshopid'      => $key->olshopid,
                'no_invoice'    => $key->no_invoice,
                'tgl_invoice'   => $key->tgl_invoice,
                'customer'      => $key->customer,
                'skuid'         => $key->skuid,
                'qty_sku'       => $key->qty_sku,
                'productid'     => $key->productid,
                'qty_rule'      => $key->qty_rule,
                'qty'           => $key->qty,
                'nama_product'  => $key->nama_product,
                'username'      => $key->username,
                'signature'     => $signature,
                'created_at'    => $created_at,
                'created_by'    => $created_by
            ];
            
            $this->db->insert('mes.t_proses_posting', $insert_posting);
        }

        $data = [
            'status_posting' => 1,
            'tgl_posting'   => $created_at,
            'updated_at'    => $created_at,
            'updated_by'    => $created_by
        ];

        $this->db->where('signature', $signature);
        $update = $this->db->update('mes.t_proses_master', $data);

        // $this->db->trans_complete();

        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan proses posting. mungkin karena internet. sistem akan melakukan rollback ke sebelum proses posting";
        //     die;
        // }

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function get_proses_posting($signature = '', $periode_1 = '', $periode_2 = ''){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }
        
        if ($periode_1 == '' && $periode_2 == '') {
            $params_periode = "";
        }else{
            $params_periode = "and a.tgl_proses BETWEEN '$periode_1' and '$periode_2'";
        }

        $query = "
        select a.*, b.*, c.nama_olshop, d.nama_store, e.username
        from mes.t_proses_posting a LEFT JOIN 
        (
            select a.no_proses, a.tgl_posting, a.status_posting
            from mes.t_proses_master a 
            where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
        )b on a.no_proses = b.no_proses left join 
        (
            select a.olshopid, a.nama_olshop
            from mes.m_olshop a
        )c on a.olshopid = c.olshopid left join 
        (
            select a.storeid, a.nama_store
            from mes.m_store a 
        )d on a.storeid = d.storeid left join 
        (
            select a.id, a.username
            from mpm.user a
        )e on a.created_by = e.id
        where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00') and a.productid is not null and (a.no_pesanan_gudang is null or a.no_pesanan_gudang = '') $params_periode
        $params
        ";

        // echo "<br><br><br><pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_proses_posting_default($signature = '', $periode_1 = '', $periode_2 = ''){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }
        
        if ($periode_1 == '' && $periode_2 == '') {
            $params_periode = "";
        }else{
            $params_periode = "and a.tgl_proses BETWEEN '$periode_1' and '$periode_2'";
        }

        $query = "
        select a.*, b.*, c.nama_olshop, d.nama_store, e.username
        from mes.t_proses_posting a LEFT JOIN 
        (
            select a.no_proses, a.tgl_posting, a.status_posting
            from mes.t_proses_master a 
            where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00')
        )b on a.no_proses = b.no_proses left join 
        (
            select a.olshopid, a.nama_olshop
            from mes.m_olshop a
        )c on a.olshopid = c.olshopid left join 
        (
            select a.storeid, a.nama_store
            from mes.m_store a 
        )d on a.storeid = d.storeid left join 
        (
            select a.id, a.username
            from mpm.user a
        )e on a.created_by = e.id
        where (a.deleted_at is null or a.deleted_at = '0000-00-00 00:00:00') and a.productid is not null $params_periode
        $params
        order by a.id desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_proses_gudang_log($signature = ''){
        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }
        $query = "
            select *
            from mes.t_proses_gudang_log a 
            $params
            order by a.id desc
        ";
        return $this->db->query($query);
    }

    public function get_proses_gudang_log_group($signature){
        // $query = "
        //     select a.productid, a.nama_product, sum(a.qty) as qty
        //     from mes.t_proses_gudang_log a 
        //     where a.signature = '$signature'
        //     group by a.productid
        // ";

        $query = "
        select  a.productid, a.nama_product, a.qty, b.satuan1, b.unit1, b.satuan2, b.unit2, floor(a.qty/b.unit1) as box,
                a.qty%b.unit1 as sachet
        from 
        (
            select a.productid, a.nama_product, sum(a.qty) as qty
            from mes.t_proses_gudang_log a 
            where a.signature = '$signature'
            group by a.productid
        )a LEFT JOIN (
            select a.productid, a.satuan1, a.satuan2, a.unit1, a.unit2
            from mes.m_product a 
            where a.deleted_at is null
        )b on a.productid = b.productid
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function generate_pesanan_gudang($tgl_pesanan_gudang){

        $bulan_transaksi = date('m',strtotime($tgl_pesanan_gudang));
        $romawi = $this->getRomawi($bulan_transaksi);
        $tahun_transaksi = date('Y',strtotime($tgl_pesanan_gudang));
        $query = "
            select a.tgl_pesanan_gudang, a.no_pesanan_gudang, substr(a.no_pesanan_gudang,5,3) as urut
            from mes.t_proses_piutang a
            where year(a.tgl_pesanan_gudang) = $tahun_transaksi and month(a.tgl_pesanan_gudang) = $bulan_transaksi
            ORDER BY a.id desc
            limit 1
        ";

        $no_proses_current = $this->db->query($query);
        if ($no_proses_current->num_rows() > 0) {
            
            $params_urut = $no_proses_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "NPG-00$params_urut/MPM/$romawi/$tahun_transaksi";
            }elseif (strlen($params_urut) === 2) {
                $generate = "NPG-0$params_urut/MPM/$romawi/$tahun_transaksi";
            }else{
                $generate = "NPG-$params_urut/MPM/$romawi/$tahun_transaksi";
            }
        }else{
            $generate = "NPG-001/MPM/$romawi/$tahun_transaksi";
        }
        return $generate;
    }

    public function get_gudang_log($signature){
        $query = "
            select *
            from mes.t_proses_gudang_log a 
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_piutang($signature = ''){
        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }
        $query = "
            select a.*, b.username
            from mes.t_proses_piutang a LEFT JOIN
            (
                select a.id, a.username
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_piutang_detail($signature = ''){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.*, c.username
            from mes.t_proses_piutang a inner join (
                select a.id_piutang, a.productid, a.nama_product, a.qty, a.box, a.sachet
                from mes.t_proses_piutang_detail a
            )b on a.id = b.id_piutang left join (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.deleted_at is null $params          
            ORDER BY b.productid, a.id desc
            limit 1000
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_piutang_detail_search($advanced)
    {
        if ($advanced) 
        {
            $from = $advanced['from'];
            $to = $advanced['to'];

            if ($from && $to) {
                $params_tgl = " and a.tgl_pesanan_gudang between '$from' and '$to' ";
                $params_limit = '';
            }else{
                $params_tgl = "";
            }
        }else{
            $params_tgl = "";
            $params_limit = "limit 10";
        }
        $query = "
            select a.*, b.*, c.username
            from mes.t_proses_piutang a inner join (
                select a.id_piutang, a.productid, a.nama_product, a.qty, a.box, a.sachet
                from mes.t_proses_piutang_detail a
            )b on a.id = b.id_piutang left join (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.deleted_at is null $params_tgl          
            ORDER BY a.id desc
            $params_limit
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_olshop_store($npg){
        
        $query = "
            select b.nama_olshop, c.nama_store
            from mes.t_proses_posting a LEFT JOIN mes.m_olshop b 
                on a.olshopid = b.olshopid LEFT JOIN mes.m_store c 
                on a.storeid = c.storeid
            where a.no_pesanan_gudang = '$npg' 
            ORDER BY a.id desc
            limit 1
        ";
        return $this->db->query($query);
    }

    public function get_raw_data($periode_1, $periode_2, $signature){
        $query = "
        insert into mes.report_raw_data
        select 	a.tgl_proses, a.no_proses, b.tgl_invoice, b.no_invoice, b.customer, a.storeid, g.nama_store, a.olshopid, h.nama_olshop, 
                b.kurir, b.no_resi, c.skuid, d.nama_sku, c.qty_sku, if(d.status_gimmick = 1, 0, c.harga) as harga_sku, d.productid, e.nama_product, d.qty_rule, (c.qty_sku * d.qty_rule) as qty, a.status_posting, a.tgl_posting, f.no_pesanan_gudang, f.tgl_pesanan_gudang, e.harga, e.discount, (e.harga * e.discount / 100 * i.qty) as rp_discount, (e.harga * i.qty) as bruto, (e.harga * i.qty) - (e.harga * e.discount / 100 * i.qty) as netto,
                f.no_faktur, f.tgl_faktur, f.nilai_faktur, f.bayar, f.transfer, f.tgl_bayar, '$signature', b.total_harga
        from mes.t_proses_master a LEFT JOIN (
            select  a.id, a.no_proses, a.no_invoice, a.tgl_invoice, 
                    a.customer, a.kurir, a.no_resi, a.status_post, a.tgl_post, a.total_harga
            from mes.t_proses_detail a
        )b on a.no_proses = b.no_proses LEFT JOIN (
            select a.id_invoice, a.skuid, a.qty_sku, a.harga
            from mes.t_proses_sku a
        )c on b.id = c.id_invoice left join 
        (
            select a.skuid, a.olshopid, a.nama_sku, b.productid, b.qty_rule, b.status_gimmick
            from mes.m_sku_olshop a left join 
            (
                select a.id, a.skuid, a.olshopid, a.productid, a.qty_rule, a.status_gimmick
                from mes.m_sku_olshop_detail a
                where  a.status_aktif = 1 and a.deleted_at is null
            )b on a.skuid = b.skuid and a.olshopid = b.olshopid
        )d on c.skuid = d.skuid and a.olshopid = d.olshopid LEFT JOIN (
            select a.productid, a.nama_product, a.harga, a.discount
            from mes.m_product a 
        )e on d.productid = e.productid LEFT JOIN (
            select a.id, a.no_proses, a.no_pesanan_gudang, a.tgl_pesanan_gudang, a.no_faktur, a.tgl_faktur, a.bayar, a.tgl_bayar, a.bukti_transfer, a.nilai_faktur, a.email_to, a.email_at, a.status_email, a.transfer
            from mes.t_proses_piutang a
        )f on a.no_proses = f.no_proses LEFT JOIN
        (
            select a.storeid, a.nama_store
            from mes.m_store a
        )g on a.storeid = g.storeid LEFT JOIN
        (
            select a.olshopid, a.nama_olshop
            from mes.m_olshop a
        )h on a.olshopid = h.olshopid LEFT JOIN
        (
            select a.id_piutang, a.productid, a.qty, a.box, a.sachet
            from mes.t_proses_piutang_detail a
        )i on f.id = i.id_piutang  and d.productid = i.productid
        where a.deleted_at is null and a.tgl_proses between '$periode_1' and '$periode_2'
        ";

        $this->db->query($query);

        // echo "<br><br><br><br><pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $this->db->where('signature', $signature);
        return $this->db->get('mes.report_raw_data');

    }

    public function get_draft_import($signature, $id_import, $leftorinner = ''){

        if ($id_import) {
            $params = "and a.id = $id_import";
        }else{
            $params = "";
        }

        if ($leftorinner) {
            $params_leftorinner = 'inner';
        }else{
            $params_leftorinner = 'left';
        }

        $query = "
        select 	a.id, a.tanggal, a.invoice, a.pembeli, a.storeid, 
                b.nama_store, a.olshopid, c.nama_olshop, a.kurir, a.resi, 
                a.skuid, d.nama_sku, a.qty_sku, a.harga
        from mes.t_import_draft a $params_leftorinner join 
        (
            select a.storeid, a.nama_store
            from mes.m_store a 
        )b on a.storeid = b.storeid $params_leftorinner JOIN
        (
            select a.olshopid, a.nama_olshop
            from mes.m_olshop a
        )c on a.olshopid = c.olshopid $params_leftorinner JOIN 
        (
            select a.skuid, a.olshopid, a.nama_sku
            from mes.m_sku_olshop a
        )d on a.skuid = d.skuid and a.olshopid = d.olshopid
        where a.signature = '$signature' $params
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_preview_import($signature){

        $query = "select * from mes.t_import_preview a where a.signature = '$signature'";
        return $this->db->query($query);
    }

    public function insert_temp_to_preview($signature){
        
        // echo "signature : ".$signature;

        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id'); 

        $query = "
        insert into mes.t_import_preview
        select 	'', a.id, a.tanggal, a.invoice, a.pembeli, a.storeid, b.nama_store, a.olshopid, c.nama_olshop, a.kurir, a.resi, 
                a.skuid, d.nama_sku, sum(a.qty_sku) as qty_sku, sum(a.harga) as harga, 
                '$created_at', '$created_by', '$signature'
        from mes.t_import_preview_temp a inner join 
        (
            select a.storeid, a.nama_store
            from mes.m_store a 
        )b on a.storeid = b.storeid inner JOIN
        (
            select a.olshopid, a.nama_olshop
            from mes.m_olshop a
        )c on a.olshopid = c.olshopid inner JOIN 
        (
            select a.skuid, a.olshopid, a.nama_sku
            from mes.m_sku_olshop a
        )d on a.skuid = d.skuid and a.olshopid = d.olshopid
        where a.signature = '$signature'
        GROUP BY a.invoice, a.skuid
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_proses_master_by_id($id){

        $query = "select * from mes.t_proses_master a where a.id = $id";
        return $this->db->query($query);
   
    }

    public function get_preview_import_group_invoice($signature){

        $query = "
            select  a.invoice, sum(a.harga) as total_harga, sum(a.qty_sku) as total_qty,
                    a.tanggal, a.pembeli, a.kurir, a.resi
            from mes.t_import_preview a 
            where a.signature = '$signature' 
            group by invoice";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";

            // die;
        return $this->db->query($query);
    }

    public function get_import_preview_by_invoice_n_signature($invoice, $signature){
        $query = "
            select *
            from mes.t_import_preview a 
            where a.signature = '$signature' and a.invoice = '$invoice'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_proses_sku($signature_sku){
        $query = "select * from mes.t_proses_sku a where a.signature = '$signature_sku'";
        return $this->db->query($query);
    }

    public function get_productid($productid){

        $query = "
            select *
            from mes.m_product a 
            where a.productid = '$productid'
        ";

        return $this->db->query($query);
    }

    public function get_sku_olshop_import($olshopid, $skuid, $productid){
        $query = "
            select *
            from mes.import_sku_olshop a
            where a.olshopid = '$olshopid' and a.skuid = '$skuid' and a.productid = '$productid'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_import_sku_olshop_group_olshopid_skuid($signature_log){
        $query = "
            select *
            from mes.import_sku_olshop a 
            where a.signature_log = '$signature_log'
            GROUP BY a.olshopid, a.skuid
        ";
        return $this->db->query($query);
    }

    public function get_import_sku_olshop_group_olshopid_skuid_productid($signature_log){
        $query = "
            select *
            from mes.import_sku_olshop a 
            where a.signature_log = '$signature_log'
            GROUP BY a.olshopid, a.skuid, a.productid
        ";
        return $this->db->query($query);
    }

    public function get_sku_olshop_by_olshopid_skuid($olshopid, $skuid){

        $query = "
            select *
            from mes.m_sku_olshop a 
            where a.olshopid = '$olshopid' and a.skuid = '$skuid'
        ";

        return $this->db->query($query);
    }

    public function get_sku_olshop_detail_by_olshopid_skuid_productid($olshopid, $skuid, $productid){

        $query = "
            select *
            from mes.m_sku_olshop_detail a 
            where a.olshopid = '$olshopid' and a.skuid = '$skuid' and a.productid = '$productid'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_proses_master_by_npg($no_proses){
        $query = "
            select a.no_proses, b.nama_olshop, c.nama_store
            from mes.t_proses_master a LEFT JOIN 
            (
                select a.olshopid, a.nama_olshop
                from mes.m_olshop a
            )b on a.olshopid = b.olshopid LEFT JOIN 
            (
                select a.storeid, a.nama_store
                from mes.m_store a 
            )c on a.storeid = c.storeid
            where a.no_proses = '$no_proses'
        ";
        return $this->db->query($query);
    }

    public function update_status_gimmick()
    {
        $query = "
            update mes.m_sku_olshop_detail a left join (
                select a.productid, a.status_gimmick
                from mes.m_product a 
            )b on a.productid = b.productid 
            set a.status_gimmick = b.status_gimmick
        ";
        return $this->db->query($query);
    }

}