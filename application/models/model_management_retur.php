<?php

use Svg\Tag\Group;

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_retur extends CI_Model
{
    public function get_master_dbsls($customerid = '', $productid ='', $limit = ''){

        if ($customerid) {
            $params_customerid = "where a.customerid = '$customerid'";
        }else{
            $params_customerid = "";
        }

        if ($productid) {
            $params_productid = "and a.productid = '$productid'";
        }else{
            $params_productid = "";
        }

        if ($limit) {
            $params_limit = "limit $limit";
        }else{
            $params_limit = "";
        }

        $query = "
            select * from management_retur.master_dbsls a
            $params_customerid $params_productid $params_limit
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_master_dbsls_noseri($customerid = '', $productid ='', $limit = '', $from = '', $to = ''){

        if ($customerid) {
            $params_customerid = "where a.customerid = '$customerid'";
        }else{
            $params_customerid = "";
        }

        if ($productid) {
            $params_productid = "and a.productid = '$productid'";
        }else{
            $params_productid = "";
        }

        if ($limit) {
            $params_limit = "limit $limit";
        }else{
            $params_limit = "";
        }

        if ($from == '' || $to == '') {
            $params_tahun = "";
        }else{
            // $params_tahun = "and (DATE_FORMAT(a.tanggal, '%Y-%m') BETWEEN '$from' and '$to') and (DATE_FORMAT(a.tanggal, '%Y-%m') BETWEEN '$from' and '$to')";
            $params_tahun = "and (DATE_FORMAT(a.tanggal, '%Y-%m') BETWEEN '$from' and '$to')";
        }

        $query = "
        SELECT a.*, 
        IF((a.brandid = 11012 or a.brandid = 012) and CONCAT(year(a.tanggal),IF(LENGTH(month(a.tanggal)) = 1, CONCAT(0,month(a.tanggal)), month(a.tanggal))) >= 202007, (a.qty_kecil * b.qty1), a.qty_kecil) as qty1,
        IF((a.brandid = 11012 or a.brandid = 012) and CONCAT(year(a.tanggal),IF(LENGTH(month(a.tanggal)) = 1, CONCAT(0,month(a.tanggal)), month(a.tanggal))) >= 202007, (a.retur * b.qty1), a.retur) as qty_retur1
            FROM
            (
                SELECT *
                FROM management_retur.master_dbsls a
                WHERE id IN (
                    select MAX(id) as id from management_retur.master_dbsls a
                    $params_customerid $params_productid $params_limit $params_tahun
                    GROUP BY a.ref
                )
            )a
            LEFT JOIN management_retur.mapping_product_qty b
            on a.productid = b.kodeprod
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_ajuan_retur($from, $to)
    {
        // echo "from : $from, to : $to";
        if($from && $to){
            $params_periode = "and a.tanggal_pengajuan between '$from' and '$to'";
        }else{
            $params_periode = "";
        }
        // die;

        $query = "
        SELECT a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`,
                a.nama_status, a.file_principal, a.tanggal_approval, a.keterangan_lain, a.signature,
                b.branch_name, b.nama_comp,
                if(a.supp ='001-herbal', 'DELTOMED-HERBAL',if(a.supp='001-herbana','DELTOMED-HERBANA',c.namasupp)) as principal,
                count(d.signature_draft_nota_retur) as count_nota_retur, a.no_terima, versi
        FROM
        (
        select 	a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`,
                a.nama_status, a.file_principal, a.tanggal_approval, a.keterangan_lain, a.signature, a.no_terima_barang as no_terima, 'V2' as versi
        from management_inventory.pengajuan_retur a
        where a.`status` in (8, 9, 11, 12) $params_periode
        

        /*UNION ALL

        select 	a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`,
                a.nama_status, a.file_principal, a.tanggal_approval, a.keterangan_lain, a.signature, a.no_terima, 'V1' as versi
        from db_temp.t_temp_pengajuan_retur a
        where a.`status` in (8, 9, 10, 11)*/
        )a
        LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code
        LEFT JOIN
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )c on a.supp = c.supp
        LEFT JOIN
        (
            SELECT a.signature_ajuan_retur, a.signature_draft_nota_retur
            from management_retur.ajuan_vs_nota_retur a
            where deleted_at is null
        )d on a.signature = d.signature_ajuan_retur
        GROUP BY a.id, a.versi
        ORDER BY a.tanggal_pengajuan desc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_id_pengajuan_by_signature($signature){

        $query = "
            select a.*, b.branch_name, b.nama_comp
            from
            (
                select *
                from
                (
                    select 	a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`,
                        a.nama_status, a.file_principal, a.tanggal_approval, a.keterangan_lain, a.signature, a.no_terima_barang as no_terima, 'V2' as versi
                    from management_inventory.pengajuan_retur a
                    where a.signature = '$signature'

                    UNION ALL

                    select 	a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`,
                            a.nama_status, a.file_principal, a.tanggal_approval, a.keterangan_lain, a.signature, a.no_terima, 'V1' as versi
                    from db_temp.t_temp_pengajuan_retur a
                    where a.signature = '$signature'
                )a
                where a.signature = '$signature'
            )a
            LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
        ";

        // print_r($query);
        return $this->db->query($query);
    }

    public function get_product_ajuan_retur($id_ajuan, $signature, $versi){

        //status = 3 artinya sudah verified oleh linda
        $query = "
            SELECT a.id, a.kodeprod, b.namaprod, a.batch_number, a.tahun, a.expired_date, a.jumlah,
                    a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.kode_produksi, a.`status`,
                    a.nama_status, a.deskripsi, a.qty_approval_ho, a.qty_final, a.qty_lpk
            FROM
            (
                select a.id, a.id_pengajuan, a.kodeprod, a.batch_number, IF(SUBSTR(batch_number,-2) < 20 || SUBSTR(batch_number,-2) > SUBSTR(YEAR(CURRENT_DATE),-2), 'NULL', CONCAT(20,SUBSTR(batch_number,-2))) as tahun,
					a.expired_date, sum(if(a.kodeprod like '12%', a.total_pcs, a.jumlah )) as jumlah,
					a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.kode_produksi, a.`status`,
					a.nama_status, a.deskripsi, sum(a.qty_lpk) as qty_approval_ho, sum(a.qty_lpk) as qty_final, sum(a.qty_lpk) as qty_lpk, 'V1' as versi, a.deleted
                from db_temp.t_temp_produk_pengajuan_retur a
                where a.id_pengajuan in ($id_ajuan) and a.deleted is null and a.status = 3
                GROUP BY a.kodeprod, a.batch_number

                UNION ALL

                select a.id, a.id_pengajuan, a.kodeprod, a.batch_number, IF(SUBSTR(batch_number,-2) < 20 || SUBSTR(batch_number,-2) > SUBSTR(YEAR(CURRENT_DATE),-2), 'NULL', CONCAT(20,SUBSTR(batch_number,-2))) as tahun,
					a.expired_date, sum(a.qty_approval) as jumlah,
					a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.kode_produksi, a.`status`,
					a.nama_status, a.deskripsi, sum(a.qty_approval_ho) as qty_approval_ho, sum(a.qty_final) as qty_final, sum(a.qty_lpk) as qty_lpk, 'V2' as versi, a.deleted
                from management_inventory.pengajuan_retur_detail a
                where a.id_pengajuan in ($id_ajuan) and a.deleted is null and a.status = 3
                GROUP BY a.kodeprod, a.batch_number

            )a
            LEFT JOIN (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.versi = '$versi'
            GROUP BY a.kodeprod, a.batch_number
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_pengajuan_detail_retur($signature, $id_ajuan, $versi) {
        $query = "
            SELECT a.id, a.kodeprod, b.namaprod, a.batch_number, a.tahun, a.expired_date, a.jumlah, a.qty_lpk, a.noseri
            FROM
            (
                select *
                from management_retur.pengajuan_detail a
                where a.id_pengajuan = '$id_ajuan' and a.signature_pengajuan = '$signature' and a.versi = '$versi' 
            )a
            LEFT JOIN 
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.versi = '$versi'
            GROUP BY a.kodeprod, a.batch_number
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_no_seri_pajak($customerid, $productid){

        $query = "
            select *
            from management_retur.master_dbsls a
            where a.customerid = '$customerid' and a.productid = '$productid'
        ";

        return $this->db->query($query);

    }

    public function get_raw_rekomendasi($signature){
        $query = "
        select a.customerid, a.nama_customer, a.no_seri_pajak, a.productid, a.qty_kecil, a.retur, a.qty_ajuan_retur, a.selisih_qty, a.qty_lpk, a.batch_number, b.namaprod
        from management_retur.log_search_master_dbsls a left join (
            select kodeprod, namaprod
            from mpm.tabprod
        )b on a.productid = b.kodeprod
        where a.signature = '$signature' 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_rekomendasi($signature, $group){
        if ($group == 1) {
            $group_by = '';
        } elseif ($group == 2) {
            $group_by = ',a.tahun';
        }

        $query = "
            SELECT a.tanggal,a.customerid, a.nama_customer, a.no_seri_pajak, sum(a.count_no_seri_pajak) as count_no_seri_pajak, a.ref, a.productid,
                    a.batch_number, sum(a.qty_kecil), a.retur, sum(a.qty_lpk), sum(a.selisih_qty) as selisih_qty, tahun
            FROM
            (
                SELECT a.*
                FROM
                (
                    select a.tanggal,a.customerid, a.nama_customer, a.no_seri_pajak, count(a.no_seri_pajak) as count_no_seri_pajak, a.ref, a.productid,
                            a.batch_number, a.qty_kecil, a.retur, sum(a.qty_lpk) as qty_lpk,
                            a.qty_kecil - (retur + sum(a.qty_lpk)) as selisih_qty, a.tahun
                    from    management_retur.log_search_master_dbsls a
                    where   a.signature = '$signature'
                    GROUP BY a.customerid, a.no_seri_pajak, a.productid $group_by
                )a WHERE a.selisih_qty >= 0
            )a
            WHERE a.selisih_qty >= 0
            GROUP BY a.customerid, a.no_seri_pajak $group_by
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_draft_nota_retur_product($signature_pengajuan){
        $query = "
        select a.*, c.kode_prc, c.supp as brandid
        from(
            select id, kodeprod, namaprod, tahun, noseri, ref, noseri_beli, qty_kecil, sum(jumlah) as jumlah,
                    sum(qty_lpk) as qty_lpk, beli, jual, disc_cabang, disc_beli, retur, tgldo
            from management_retur.pengajuan_detail
            where signature_pengajuan = '$signature_pengajuan' and noseri is not null
            group by kodeprod, noseri
        )a inner join 
        (
            select id_pengajuan, id_pengajuan_detail
            from management_retur.pengajuan_temp_proses
            where signature_pengajuan = '$signature_pengajuan'
        )b on a.id = b.id_pengajuan_detail
        left join
        (
            select a.kodeprod, a.namaprod, a.kode_prc, a.supp
            from mpm.tabprod a
        )c on a.kodeprod = c.kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_data_nota_retur($signature_ajuan_retur, $no_seri_pajak, $customerid){
        $query = "
            SELECT a.no_seri_pajak, a.ref, a.productid, a.batch_number, a.qty_kecil, a.retur, a.qty_ajuan_retur, a.qty_lpk,
            a.tanggal, a.brandid, 
            IF((a.brandid = 11012 or a.brandid = 012) and CONCAT(year(a.tanggal),if(LENGTH(month(a.tanggal)) = 1, CONCAT(0,month(a.tanggal)), month(a.tanggal))) >= 202007, (a.jual/c.qty1), a.jual) as jual, 
            a.disc_cabang, b.no_inv,
            IF((a.brandid = 11012 or a.brandid = 012) and CONCAT(year(a.tanggal),if(LENGTH(month(a.tanggal)) = 1, CONCAT(0,month(a.tanggal)), month(a.tanggal))) >= 202007, (b.beli/c.qty1), b.beli) as beli, b.disc_persen, b.tgl_terima
            FROM
            (
                SELECT a.*, b.jual, b.disc_cabang
                FROM
                (
                    SELECT no_seri_pajak, ref, productid, batch_number, qty_kecil, retur, qty_ajuan_retur, qty_lpk, tanggal, brandid
                    FROM management_retur.log_search_master_dbsls
                    WHERE no_seri_pajak = '$no_seri_pajak' and signature_ajuan_retur = '$signature_ajuan_retur' and productid in (
                        SELECT a.productid
                        FROM
                        (
                            select productid, a.qty_kecil - (retur + sum(a.qty_lpk)) as selisih_qty
                            from    management_retur.log_search_master_dbsls a
                            where   no_seri_pajak = '$no_seri_pajak' and signature_ajuan_retur = '$signature_ajuan_retur'
                            GROUP BY a.customerid, a.no_seri_pajak, a.productid
                        )a WHERE selisih_qty >= 0
                    )
                )a
                LEFT JOIN
                (
                    SELECT no_seri_pajak, ref, productid, jual, disc_cabang
                    FROM management_retur.master_dbsls
                    WHERE no_seri_pajak = '$no_seri_pajak' and customerid = '$customerid'
                )b on a.productid = b.productid
            )a
            LEFT JOIN
            (
                SELECT a.no_inv, a.no_sj, a.tgl_terima, b.beli, b.disc_persen, b.productid
                FROM
                (
                    SELECT no_inv, no_sj, tgl_terima
                    FROM dbsls.t_ap_master
                )a
                LEFT JOIN
                (
                    SELECT no_inv, beli, disc_persen, productid
                    FROM dbsls.t_ap_product_detail
                )b on a.no_inv = b.no_inv
            )b on a.ref = b.no_sj and a.productid = b.productid
            left join
            (
                select *
                from management_retur.mapping_product_qty
            )c on a.productid = c.kodeprod
            ORDER BY a.batch_number
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_company_by_signature($signature){

        $query = "
        select a.site_code, b.branch_name, b.nama_comp
        FROM
        (
            SELECT a.site_code, a.signature
            from db_temp.t_temp_pengajuan_retur a
            where a.signature = '$signature'
            UNION ALL
            SELECT a.site_code, a.signature
            from management_inventory.pengajuan_retur a
            where a.signature = '$signature'
        )a LEFT JOIN
        (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_draft_nota_retur($signature_ajuan_retur, $group = '', $noseri = ''){
        $userid = $this->session->userdata('id');

        if ($group) {
            $groupx = "Group By noseri";
        } else {
            $groupx = '';
        }

        if ($noseri) {
            $noserix = "and noseri = '$noseri'";
        } else {
            $noserix = '';
        }
        

        $query = "
        SELECT a.*, b.supp, b.kode_prc,
        if((b.supp = 11012 or b.supp = 012) and (CONCAT(year(a.tgldo),if(LENGTH(month(a.tgldo)) = 1, CONCAT(0,month(a.tgldo)), month(a.tgldo))) >= 202007),ROUND((a.qty_lpk + a.retur)/c.qty1, 1), a.qty_lpk + a.retur) as returx
        FROM
        (
            SELECT id, id_pengajuan, kodeprod, namaprod, noseri, noseri_beli, ref, tgldo, qty_kecil, SUM(retur) as retur, SUM(jumlah) as jumlah, SUM(qty_lpk) as qty_lpk, beli, jual, disc_cabang, disc_beli, no_pengajuan, versi, signature_pengajuan, created_at, created_by, tgldo_beli
            FROM management_retur.pengajuan_detail
            WHERE id in (
                SELECT a.id_pengajuan_detail FROM management_retur.pengajuan_temp_proses a
                WHERE a.signature_pengajuan = '$signature_ajuan_retur' and a.created_by = $userid
                )
            $noserix
            group by kodeprod, noseri
        ) a
        LEFT JOIN
        (
            SELECT supp, kodeprod, kode_prc
            FROM mpm.tabprod
        )b on a.kodeprod = b.kodeprod
        LEFT JOIN
        (
            select *
            from management_retur.mapping_product_qty
        )c on a.kodeprod = c.kodeprod
        $groupx
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }
    public function get_customerid_byid($userid){
        $query = "
            select a.id, a.username, a.company, a.email, a.npwp, a.nama_wp, a.alamat_wp, concat('1', a.kode_lang) as kode_lang
            from mpm.user a
            where a.id = $userid
        ";
        return $this->db->query($query);
    }

    public function get_noajuan($signature_ajuan_retur, $versi = '0'){
        if ($versi == '2') {
            $db = "management_inventory.pengajuan_retur";
        } else {
            $db = "db_temp.t_temp_pengajuan_retur";
        }

        $query = "
            select *
            from $db a
            where a.signature = '$signature_ajuan_retur'
        ";

        return $this->db->query($query);
    }

    public function get_nota_retur(){
        $query = "
            select *
            from management_retur.temp_draft_nota_retur a
            where a.deleted_at is null
        ";
        // print_r($query);
        return $this->db->query($query);
    }

    public function cek_qty_lpk($id_ajuan, $versi){
        $query = "
            select *
            from
            (
                select a.id, a.id_pengajuan, a.kodeprod, a.batch_number, a.expired_date, a.jumlah,
                        a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.kode_produksi, a.`status`,
                        a.nama_status, a.deskripsi, a.qty_lpk, 'V1' as versi, a.deleted
                from db_temp.t_temp_produk_pengajuan_retur a
                where a.id_pengajuan in ($id_ajuan) and a.deleted is null and a.status = '3'
                group by a.kodeprod, a.batch_number

                UNION ALL

                select a.id, a.id_pengajuan, a.kodeprod, a.batch_number, a.expired_date, a.qty_approval as jumlah,
                            a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.kode_produksi, a.`status`,
                            a.nama_status, a.deskripsi, a.qty_lpk, 'V2' as versi, a.deleted
                from management_inventory.pengajuan_retur_detail a
                where a.id_pengajuan in ($id_ajuan) and a.deleted is null 
                and a.keterangan_principal_area != 'ajukan sampling' and a.status = '3'
                group by a.kodeprod, a.batch_number
            )a where a.qty_lpk is null and a.versi = '$versi'
        ";
        // echo '<pre>';
        // print_r($query);die;
        // echo '</pre>';
        return $this->db->query($query);
    }

    public function update_data_dbsls(){
        $query = "
            insert into management_retur.master_dbsls
            select 	'', a.customerid, a.tanggal, a.productid, a.nama_customer, a.nama_product, a.brandid, a.nama_brand, a.ref, a.no_seri_pajak,
                    a.qty_kecil, (-1)* if((a.brandid = 11012 or a.brandid = 012) and (CONCAT(year(a.tanggal),if(LENGTH(month(a.tanggal)) = 1, CONCAT(0,month(a.tanggal)), month(a.tanggal))) >= 202007), ROUND(b.banyak/c.qty1, 1), b.banyak) as banyak, a.beli, a.jual, a.disc_cabang, a.disc_beli,
                    '','','','','','','',''
            from management_retur.master_dbsls_original a LEFT JOIN
            (
                select a.company, a.nodo, a.nodo_beli, a.noseri, a.supp, b.kodeprod, sum(b.banyak) as banyak
                from mpm.trans a INNER JOIN mpm.trans_detail b
                    on a.id = b.id_ref
                where a.deleted =0 and b.deleted = 0
                GROUP BY a.noseri, b.kodeprod
            )b on a.no_seri_pajak = b.noseri and a.productid = b.kodeprod
            left join management_retur.mapping_product_qty c on a.productid = c.kodeprod
        ";
        return $this->db->query($query);
    }

    public function get_signature_by_id($idx, $versi = '0'){
        if ($versi == 2) {
            $sql = $this->db->query("select signature from management_retur.pengajuan_retur a where a.id = $idx");
        } else {
            $sql = $this->db->query("select signature from db_temp.t_temp_pengajuan_retur a where a.id = $idx");
        }

        return $sql;
    }

    public function get_retur($userid = ""){

        if ($userid) {
            $params = "and a.userid = $userid";
        }else{
            $params = "";
        }

        $query = "
        select 	a.id,a.supp,a.userid,a.company,a.tipe,a.deleted,tglbuat,a.created,
                a.nodo_beli, nodo, date_format(tglbuat,'%d %M %Y, %T') as tglbuat,
                date_format(a.tgldo,'%Y-%m-%d') as tgldo, a.tgldo_beli,
                noseri, a.nopo, noseri_beli, a.no_coretax
        from 	mpm.trans a inner join mpm.user b
                    on a.userid=b.id
        where date_format(tglbuat,'%Y-%m-%d') and deleted=0 $params
        order by a.tglbuat desc, a.company asc
        limit 1000
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_retur_by_id($id){

        if ($id) {
            $params = "and a.id = $id";
        }else{
            $params = "";
        }

        $query = "
            select 	a.id, a.supp, a.userid, a.company, a.tipe, a.deleted, tglbuat, a.created,
                    a.nodo_beli, nodo, date_format(a.tgldo,'%Y-%m-%d') as tgldo,
                    noseri, noseri_beli, nopo, tgldo_beli, tgl_beli, c.*, d.namaprod, a.no_coretax
            from 	mpm.trans a inner join mpm.user b
                        on a.userid=b.id LEFT JOIN
                        (
                            select 	a.id_ref, a.kodeprod, a.kode_prc, a.banyak, a.disc, a.diskon, a.diskon_beli, a.dpp,
                                    a.harga, a.harga_beli
                            from mpm.trans_detail a
                            where a.deleted = 0
                        )c on a.id = c.id_ref   left join
                        (
                            select a.kodeprod, a.namaprod
                            from mpm.tabprod a
                        )d on c.kodeprod = d.kodeprod
            where date_format(tglbuat,'%Y-%m-%d') and deleted=0 $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_user($id){
        $query = "
            select *
            from mpm.user a
            where a.id = $id
        ";

        return $this->db->query($query);
    }

    public function get_import_nr($signature){
        $query = "
            select a.*,b.namaprod, c.namasupp
            from
            (
                select *
                from management_retur.import_retur a
                where a.signature = '$signature'
            )a
            left join mpm.tabprod b on a.kodeprod = b.kodeprod
            left join mpm.tabsupp c on a.supp = c.supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_import_header($signature){
        $query = "
            select *
            from management_retur.import_retur a
            where a.signature = '$signature'
            group by a.noseri_penjualan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_import_by_id($signature, $noseri_penjualan){
        $query = "
            select *
            from management_retur.import_retur a
            where a.signature = '$signature' and a.noseri_penjualan = '$noseri_penjualan'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_produk($kodeprod){
        $query = "
            select *
            from mpm.tabprod a
            where a.kodeprod = '$kodeprod'
        ";

        return $this->db->query($query);
    }

    public function get_totaltrans($id_ref, $supp) {
        if ($supp == 001) {
            $sql = "
                SELECT round(sum(a.bruto)) as tot_bruto,
                sum(a.disc) as tot_disc,
                round(sum(a.dpp)) as tot_dpp
                FROM mpm.trans_detail a
                WHERE a.id_ref = $id_ref
                GROUP BY a.id_ref
            ";
        }
        elseif ($supp == 002) {
            $sql = "
                SELECT floor(sum(a.bruto)) as tot_bruto,
                sum(floor(a.disc))as tot_disc,
                floor(sum(a.dpp)) as tot_dpp
                FROM mpm.trans_detail a
                WHERE a.id_ref = $id_ref
                GROUP BY a.id_ref
            ";
        }
        elseif ($supp == 005) {
            $sql = "
                select *
                from
                (
                    select 	floor(sum(a.bruto)) as tot_bruto
                    from 	mpm.trans_detail a
                    where 	id_ref= $id_ref and
                            deleted=0
                )a,
                (
                    select sum(potongan) as tot_disc
                    from
                    (
                        select 	sum(a.disc) as potongan
                        from 	mpm.trans_detail a
                        where 	id_ref= $id_ref and
                                deleted=0 and a.kodeprod in (select b.kodeprod from db_produk.t_product_retur b where b.aktif = 1)
                        union all
                        select 	sum(banyak*floor(harga_beli*a.diskon_beli/100)*-1) as potongan
                        from 	mpm.trans_detail a
                        where 	id_ref= $id_ref and
                                deleted=0 and a.kodeprod not in (select b.kodeprod from db_produk.t_product_retur b where b.aktif = 1)
                    )a
                )b,
                (
                    select sum(x) as tot_dpp
                    from
                    (
                        select 	floor(sum(a.bruto)) as x
                        from 	mpm.trans_detail a
                        where 	id_ref= $id_ref and
                                deleted=0
                        union all
                        select sum(potongan*-1) as x
                        from
                        (
                            select 	sum(a.disc) as potongan
                            from 	mpm.trans_detail a
                            where 	id_ref= $id_ref and
                                    deleted=0 and a.kodeprod in (select b.kodeprod from db_produk.t_product_retur b where b.aktif = 1)
                            union all
                            select 	sum(banyak*floor(harga_beli*a.diskon_beli/100)*-1) as potongan
                            from 	mpm.trans_detail a
                            where 	id_ref= $id_ref and
                                    deleted=0 and a.kodeprod not in (select b.kodeprod from db_produk.t_product_retur b where b.aktif = 1)
                        )a
                    )a
                )c
            ";
        }
        elseif ($supp == 012) {
            $sql = "
                SELECT sum(floor(a.bruto)) as tot_bruto,
                sum(a.disc) as tot_disc,
                sum(floor(a.dpp)) as tot_dpp
                FROM mpm.trans_detail a
                WHERE a.id_ref = $id_ref
                GROUP BY a.id_ref
            ";
        }
        else {
            $sql = "
                SELECT floor(sum(a.bruto)) as tot_bruto,
                sum(a.disc) as tot_disc,
                floor(sum(a.dpp)) as tot_dpp
                FROM mpm.trans_detail a
                WHERE a.id_ref = $id_ref
                GROUP BY a.id_ref
            ";
        }

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        // die;

        return $this->db->query($sql);
    }

    public function tarik_data_retur_sds() {
        date_default_timezone_set('Asia/Jakarta');        
		$created_at = date('Y-m-d H:i:s');
        // $sql_count_temp = "select count(siteid) as count from management_retur.temp_t_sales_master";
        // $count_temp = $this->db->query($sql_count_temp)->row()->count;

        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn){
            if(($result = sqlsrv_query($conn,"
                SELECT	count(siteid) as count
                FROM    dbsls.dbo.t_sales_master
                where retur = 1 and proses = 1
                ")) !== false){
                while( $obj = sqlsrv_fetch_object( $result )) {
                    // if ($count_temp < $obj->count) {
                        $this->db->query("truncate management_retur.temp_t_sales_master");

                        if(($result = sqlsrv_query($conn,"
                            SELECT	*
                            FROM    dbsls.dbo.t_sales_master
                            where retur = 1 and proses = 1
                            ")) !== false){
                            while( $obj = sqlsrv_fetch_object( $result )) {
                                $data = array(
                                    'siteid' => $obj->siteid,
                                    'no_sales' => $obj->no_sales,
                                    // 'tanggal' => $obj->tanggal,
                                    'flag_dealer' => $obj->flag_dealer,
                                    'salesmanid' => $obj->salesmanid,
                                    'supervisorid' => $obj->supervisorid,
                                    'customerid' => $obj->customerid,
                                    'type_bayar' => $obj->type_bayar,
                                    'term_payment' => $obj->term_payment,
                                    'garansi' => $obj->garansi,
                                    'term_garansi' => $obj->term_garansi,
                                    'ref' => $obj->ref,
                                    'keterangan' => $obj->keterangan,
                                    'no_polisi' => $obj->no_polisi,
                                    'no_rangka' => $obj->no_rangka,
                                    'nilai_invoice' => $obj->nilai_invoice,
                                    'userid' => $obj->userid,
                                    // 'tgl_created' => $obj->tgl_created,
                                    'status' => $obj->status,
                                    'proses' => $obj->proses,
                                    'counter_print' => $obj->counter_print,
                                    'retur' => $obj->retur,
                                    'user_app' => $obj->user_app,
                                    // 'date_app' => $obj->date_app,
                                    'flag_masalah' => $obj->flag_masalah,
                                    'flag_overdue' => $obj->flag_overdue,
                                    'flag_overlimit' => $obj->flag_overlimit,
                                    'flag_blacklist' => $obj->flag_blacklist,
                                    'flag_top' => $obj->flag_top,
                                    'flag_stock' => $obj->flag_stock,
                                    'program' => $obj->program,
                                    'dp_value' => $obj->dp_value,
                                    // 'tgl_periode' => $obj->tgl_periode,
                                    'tipe_sales' => $obj->tipe_sales,
                                    'tipe_trans' => $obj->tipe_trans,
                                    'tipe_tax' => $obj->tipe_tax,
                                    'no_seri_pajak' => $obj->no_seri_pajak,
                                    'counter_seri_pajak' => $obj->counter_seri_pajak,
                                    'ex_no_sales' => $obj->ex_no_sales,
                                    'p_status' => $obj->p_status,
                                    'proses_sp' => $obj->proses_sp,
                                    'tipe_order' => $obj->tipe_order,
                                    'status_retur' => $obj->status_retur,
                                    'categoryid' => $obj->categoryid,
                                    // 'created_date' => $obj->created_date,
                                    // 'tgl_po' => $obj->tgl_po,
                                    // 'debitur_id' => $obj->debitur_id,
                                    // 'b_no_sales' => $obj->b_no_sales,
                                    // 'flag_sp_tt' => $obj->flag_sp_tt,
                                    // 'flag_konsinyasi' => $obj->flag_konsinyasi,
                                    // 'tanggal_fjp_bi' => $obj->tanggal_fjp_bi,
                                    // 'areaid' => $obj->areaid,
                                    // 'area_desc' => $obj->area_desc,
                                    // 'kl' => $obj->kl,
                                    // 'user_pass_approve' => $obj->user_pass_approve,
                                    // 'no_seri_pajak_bonus' => $obj->no_seri_pajak_bonus,
                                    // 'ket_1' => $obj->ket_1,
                                    // 'ket_2' => $obj->ket_2,
                                    // 'ket_3' => $obj->ket_3,
                                    // 'debit_1' => $obj->debit_1,
                                    // 'debit_2' => $obj->debit_2,
                                    // 'debit_3' => $obj->debit_3,
                                    // 'debit_4' => $obj->debit_4,
                                    // 'kredit_1' => $obj->kredit_1,
                                    // 'kredit_2' => $obj->kredit_2,
                                    // 'kredit_3' => $obj->kredit_3,
                                    // 'kredit_4' => $obj->kredit_4,
                                    // 'no_sj' => $obj->no_sj,
                                    // 'no_doi_plan' => $obj->no_doi_plan,
                                    // 'signature' => $obj->signature,
                                    // 'kupon_memo' => $obj->kupon_memo,
                                    // 'kupon_count_kupon_actual' => $obj->kupon_count_kupon_actual,
                                    // 'kupon_count_kupon_kumlatif' => $obj->kupon_count_kupon_kumlatif,
                                    // 'do_dari_retur' => $obj->do_dari_retur,
                                    'alasan_retur_id' => $obj->alasan_retur_id,
                                    'alasan_retur' => $obj->alasan_retur,
                                    // 'no_ajuan_relokasi' => $obj->no_ajuan_relokasi,
                                    // 'gudangid' => $obj->gudangid,
                                    // 'gudang_desc' => $obj->gudang_desc,
                                    // 'tipe_jual' => $obj->tipe_jual,
                                    // 'alasan_jual' => $obj->alasan_jual,
                                    'created_at' => "$created_at",
                                );
                            $this->db->insert('management_retur.temp_t_sales_master',$data);
                            }
                        }
                    // }
                }
            }
        }
    }

    public function get_data_retur($userid = null, $from = null, $to = null) {

        if ($userid) {
            $site_code = "
                select if(b.site_code is null, a.username, b.site_code) as site_code
                from mpm.user a left join
                (
                    select site_code, kode_comp
                    from mpm.tbl_tabcomp
                    where status = 1 and active = 1
                )b on a.username = b.kode_comp
                where a.id = $userid
            ";

            $site = $this->db->query($site_code)->row()->site_code;
            $sitex = "and site_code = '$site'";
        } else {
            $sitex = '';
        }

        if ($from && $to) {
            $periode = "and tanggal_pengajuan between '$from' and '$to'";
        } else {
            $periode = "";
        }

        $count_retur = "
            SELECT COUNT(a.id) as total_retur
            FROM
            (
                SELECT id, site_code, tanggal_pengajuan, no_pengajuan, 'V1' as versi
                FROM db_temp.t_temp_pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 10, 11, 12) $sitex $periode
                UNION ALL
                SELECT id, site_code, tanggal_pengajuan, no_pengajuan, 'V2' as versi
                FROM management_inventory.pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $sitex $periode
            )a
        ";

        $total_retur = $this->db->query($count_retur)->row()->total_retur;

        $count_progress = "
            select count(*) as total_progress
            from
            (
                SELECT id, site_code, tanggal_pengajuan, no_terima, no_pengajuan, 'V1' as versi
                FROM db_temp.t_temp_pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 10, 11) $sitex $periode
                UNION ALL
                SELECT id, site_code, tanggal_pengajuan, no_terima_barang as no_terima, no_pengajuan, 'V2' as versi
                FROM management_inventory.pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $sitex $periode
            )a
            INNER JOIN
            (
                SELECT nopo, id_pengajuan, no_pengajuan, versi
                FROM mpm.trans
                WHERE deleted = 0
                GROUP BY id_pengajuan, no_pengajuan, versi
            )b on a.id = b.id_pengajuan and a.no_pengajuan = b.no_pengajuan and a.versi = b.versi
        ";

        // echo "<pre>";
        // print_r($count_progress);
        // echo "</pre>";

        $total_progress = $this->db->query($count_progress)->row()->total_progress;

        echo "total_progress : ".$total_progress;

        $count_finish = "
            Select count(b.nodo_beli) as total_finish
            from
            (
                    SELECT id, site_code, tanggal_pengajuan, no_terima, no_pengajuan, 'V1' as versi
                    FROM db_temp.t_temp_pengajuan_retur
                    WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 10, 11, 12) $sitex $periode
                    UNION ALL
                    SELECT id, site_code, tanggal_pengajuan, no_terima_barang as no_terima, no_pengajuan, 'V2' as versi
                    FROM management_inventory.pengajuan_retur
                    WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $sitex $periode
            )a
            INNER JOIN
            (
                SELECT *
                FROM
                (
                    SELECT nopo, nodo_beli, id_pengajuan, no_pengajuan, versi
                    FROM mpm.trans
                    WHERE deleted = 0
                    GROUP BY id_pengajuan, no_pengajuan, versi
                )a INNER JOIN
                (
                    SELECT no_sales, keterangan, ex_no_sales
                    FROM management_retur.temp_t_sales_master
                )b on a.nopo = b.ex_no_sales and a.nodo_beli = b.keterangan
                GROUP BY id_pengajuan, no_pengajuan, versi
            )b on a.id = b.id_pengajuan and a.no_pengajuan = b.no_pengajuan and a.versi = b.versi
        ";

        $total_finish = $this->db->query($count_finish)->row()->total_finish;

        echo "total_finish : ".$total_finish;

        $sql = "
            select a.*, b.company, b.nodo_beli, b.tglbuat, $total_retur as total_retur, $total_progress as total_progress, $total_finish as total_finish, if(c.nama_comp is null, d.company, c.nama_comp) as nama_comp
            from
            (
                SELECT id, site_code, tanggal_pengajuan, no_terima, no_pengajuan, 'V1' as versi, signature
                FROM db_temp.t_temp_pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 10, 11, 12) $sitex $periode
                UNION ALL
                SELECT id, site_code, tanggal_pengajuan, no_terima_barang as no_terima, no_pengajuan, 'V2' as versi, signature
                FROM management_inventory.pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $sitex $periode
            )a
            LEFT JOIN
            (
                SELECT *
                FROM
                (
                    SELECT company, nopo, nodo_beli, tglbuat, id_pengajuan, no_pengajuan, versi
                    FROM mpm.trans
                    WHERE deleted = 0
                    GROUP BY id_pengajuan, no_pengajuan, versi
                )a INNER JOIN
                (
                    SELECT no_sales, keterangan, ex_no_sales
                    FROM management_retur.temp_t_sales_master
                )b on a.nopo = b.ex_no_sales and a.nodo_beli = b.keterangan
                GROUP BY id_pengajuan, no_pengajuan, versi
            )b on a.id = b.id_pengajuan and a.no_pengajuan = b.no_pengajuan and a.versi = b.versi
            LEFT JOIN
            (
                select site_code, nama_comp
                from mpm.tbl_tabcomp
                where status = 1
            )c on a.site_code = c.site_code
            LEFT JOIN
            (
                select username, company
                from mpm.user
            )d on a.site_code = d.username

        ";

        // echo "<pre>";
        // print_r($total_retur);
        // echo "</pre>";
        // die;

        return $this->db->query($sql);
    }

    public function get_data_preview($signature_ajuan_retur) {
        $userid = $this->session->userdata('id');

        $sql = "
        SELECT a.*, (qty_kecil - (retur + qty_lpk)) as selisih_qty
        FROM
        (
            SELECT id, id_pengajuan, kodeprod, namaprod, qty_kecil, retur, SUM(jumlah) as jumlah, SUM(qty_lpk) as qty_lpk, beli, jual, disc_cabang, disc_beli, signature_pengajuan
            FROM management_retur.pengajuan_detail
            WHERE signature_pengajuan = '$signature_ajuan_retur' and id in (
                SELECT id_pengajuan_detail
                FROM management_retur.pengajuan_temp_proses
                WHERE signature_pengajuan = '$signature_ajuan_retur' and created_by = $userid
            )
            GROUP BY noseri, kodeprod
        )a
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        return $this->db->query($sql);
        
    }

    public function export_data_retur($params, $userid = null, $from = null, $to = null) {
        if ($userid != 'null') {
            $site_code = "
            select if(b.site_code is null, a.username, b.site_code) as site_code
            from mpm.user a left join
            (
                select site_code, kode_comp
                    from mpm.tbl_tabcomp
                    where status = 1 and active = 1
                )b on a.username = b.kode_comp
                where a.id = $userid
            ";

            $site = $this->db->query($site_code)->row()->site_code;
            $sitex = "and site_code = '$site'";
        } else {
            $sitex = '';
        }
        
        if ($from && $to) {
            $periode = "and tanggal_pengajuan between '$from' and '$to'";
        } else {
            $periode = "";
        }

        $sql1 = "
            select a.no_pengajuan, a.tipe, a.site_code, a.nama, a.tanggal_pengajuan, a.tanggal_approval, a.tanggal_kirim_barang, a.tanggal_pemusnahan, a.tanggal_terima, a.no_terima, b.noseri, b.noseri_beli, b.company, b.nopo, b.ref, b.tgldo, b.tgldo_beli, b.nodo_beli, b.tgl_beli, b.tglbuat
            from
            (
                SELECT id, site_code, '' as tipe, nama, tanggal_pengajuan, tanggal_approval, tanggal_kirim_barang, tanggal_pemusnahan, tanggal_terima, no_terima, no_pengajuan, 'V1' as versi
                FROM db_temp.t_temp_pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 10, 11, 12) $sitex $periode
                UNION ALL
                SELECT id, site_code, tipe, nama, tanggal_pengajuan, tanggal_approval, tanggal_kirim_barang, tanggal_pemusnahan, tanggal_terima_barang as tanggal_terima, no_terima_barang as no_terima, no_pengajuan, 'V2' as versi
                FROM management_inventory.pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $sitex $periode
            )a
            LEFT JOIN
            (
                SELECT noseri, noseri_beli, company, nopo, ref, tgldo, tgldo_beli, nodo_beli, tgl_beli, tglbuat, id_pengajuan, no_pengajuan, versi
                FROM mpm.trans
                WHERE deleted = 0
                GROUP BY id_pengajuan, no_pengajuan, versi
            )b on a.id = b.id_pengajuan and a.no_pengajuan = b.no_pengajuan and a.versi = b.versi
        ";

        $sql2 = "
            SELECT d.no_pengajuan, d.tipe, d.site_code, d.nama, d.tanggal_pengajuan, d.tanggal_approval, d.tanggal_kirim_barang, d.tanggal_pemusnahan, d.tanggal_terima, d.no_terima, d.noseri, d.noseri_beli, d.company, d.nopo, d.ref, d.tgldo, d.tgldo_beli, d.nodo_beli, d.tgl_beli, d.tglbuat
            FROM
            (
                select a.id, a.no_pengajuan, a.tipe, a.site_code, a.nama, a.tanggal_pengajuan, a.tanggal_approval, a.tanggal_kirim_barang, a.tanggal_pemusnahan, a.tanggal_terima, a.no_terima, a.versi, b.noseri, b.noseri_beli, b.company, b.nopo, b.ref, b.tgldo, b.tgldo_beli, b.nodo_beli, b.tgl_beli, b.tglbuat
                from
                (
                    SELECT id, site_code, '' as tipe, nama, tanggal_pengajuan, tanggal_approval, tanggal_kirim_barang, tanggal_pemusnahan, tanggal_terima, no_terima, no_pengajuan, 'V1' as versi
                    FROM db_temp.t_temp_pengajuan_retur
                    WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 10, 11, 12) $sitex $periode
                    UNION ALL
                    SELECT id, site_code, tipe, nama, tanggal_pengajuan, tanggal_approval, tanggal_kirim_barang, tanggal_pemusnahan, tanggal_terima_barang as tanggal_terima, no_terima_barang as no_terima, no_pengajuan, 'V2' as versi
                    FROM management_inventory.pengajuan_retur
                    WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $sitex $periode
                )a
                INNER JOIN
                (
                    SELECT noseri, noseri_beli, company, nopo, ref, tgldo, tgldo_beli, nodo_beli, tgl_beli, tglbuat, id_pengajuan, no_pengajuan, versi
                    FROM mpm.trans
                    WHERE deleted = 0
                    GROUP BY id_pengajuan, no_pengajuan, versi
                )b on a.id = b.id_pengajuan and a.no_pengajuan = b.no_pengajuan and a.versi = b.versi
            )d
            WHERE NOT EXISTS (
                Select a.site_code, a.tanggal_pengajuan, a.no_terima, a.no_pengajuan
                from
                (
                    SELECT id, site_code, tanggal_pengajuan, no_terima, no_pengajuan, 'V1' as versi
                    FROM db_temp.t_temp_pengajuan_retur
                    WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 10, 11, 12) $sitex $periode
                    UNION ALL
                    SELECT id, site_code, tanggal_pengajuan, no_terima_barang as no_terima, no_pengajuan, 'V2' as versi
                    FROM management_inventory.pengajuan_retur
                    WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $sitex $periode
                )a
                INNER JOIN
                (
                    SELECT *
                    FROM
                    (
                            SELECT nopo, nodo_beli, id_pengajuan, no_pengajuan, versi
                            FROM mpm.trans
                            WHERE deleted = 0
                            GROUP BY id_pengajuan, no_pengajuan, versi
                    )a INNER JOIN
                    (
                            SELECT no_sales, keterangan, ex_no_sales
                            FROM management_retur.temp_t_sales_master
                    )b on a.nopo = b.ex_no_sales and a.nodo_beli = b.keterangan
                    GROUP BY id_pengajuan, no_pengajuan, versi
                )b on a.id = b.id_pengajuan and a.no_pengajuan = b.no_pengajuan and a.versi = b.versi
                WHERE a.no_pengajuan = d.no_pengajuan AND a.id = d.id and a.versi = d.versi
            )
        ";

        $sql3 = "
            select a.no_pengajuan, a.tipe, a.site_code, a.nama, a.tanggal_pengajuan, a.tanggal_approval, a.tanggal_kirim_barang, a.tanggal_pemusnahan, a.tanggal_terima, a.no_terima, b.noseri, b.noseri_beli, b.company, b.nopo, b.ref, b.tgldo, b.tgldo_beli, b.nodo_beli, b.tgl_beli, b.tglbuat
            from
            (
                SELECT id, site_code, '' as tipe, nama, tanggal_pengajuan, tanggal_approval, tanggal_kirim_barang, tanggal_pemusnahan, tanggal_terima, no_terima, no_pengajuan, 'V1' as versi
                FROM db_temp.t_temp_pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 10, 11, 12) $sitex $periode
                UNION ALL
                SELECT id, site_code, tipe, nama, tanggal_pengajuan, tanggal_approval, tanggal_kirim_barang, tanggal_pemusnahan, tanggal_terima_barang as tanggal_terima, no_terima_barang as no_terima, no_pengajuan, 'V2' as versi
                FROM management_inventory.pengajuan_retur
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $sitex $periode
            )a
            INNER JOIN
            (
                SELECT *
                FROM
                (
                    SELECT noseri, noseri_beli, company, nopo, ref, tgldo, tgldo_beli, nodo_beli, tgl_beli, tglbuat, id_pengajuan, no_pengajuan, versi
                    FROM mpm.trans
                    WHERE deleted = 0
                    GROUP BY id_pengajuan, no_pengajuan, versi
                )a INNER JOIN
                (
                    SELECT no_sales, keterangan, ex_no_sales
                    FROM management_retur.temp_t_sales_master
                )b on a.nopo = b.ex_no_sales and a.nodo_beli = b.keterangan
                GROUP BY id_pengajuan, no_pengajuan, versi
            )b on a.id = b.id_pengajuan and a.no_pengajuan = b.no_pengajuan and a.versi = b.versi
        ";

        if ($params == 1) {
            $query = $sql1;
        } elseif ($params == 2) {
            $query = $sql2;
        } elseif ($params == 3) {
            $query = $sql3;
        }

        $pengajuan_retur = $this->db->query($query);

        return $pengajuan_retur;
        
    }

    public function pengajuan_retur_join_trans($branch = null, $from = null, $to = null, $status)
    {
        if ($status == 'done') {
            $params_status = "where b.nodo_beli is not null and b.nodo_beli <> ''";
        }else{
            $params_status = "";
        }

        // echo "branch : ".$branch;

        if ($branch != '000') {
            // echo "Aaa";
            $site_code = "
                select if(b.site_code is null, a.username, b.site_code) as site_code
                from mpm.user a left join
                (
                    select site_code, kode_comp
                    from mpm.tbl_tabcomp
                    where status = 1 and active = 1
                )b on a.username = b.kode_comp
                where a.id = '$branch'
            ";
            $params_site_code = "and site_code = '$branch'";
        }else{
            $params_site_code = '';
        }

        if ($from && $to) {
            $params_periode = "and tanggal_pengajuan between '$from' and '$to'";
        } else {
            $params_periode = "";
        }

        $query = "
            select  a.supp, c.namasupp, a.no_pengajuan, a.tipe, a.status, a.nama_status, a.site_code, a.nama, date(a.tanggal_pengajuan) as tanggal_pengajuan, 
                    a.tanggal_approval, 
                    a.principal_area_at, a.verifikasi_at, a.principal_ho_at, a.tanggal_kirim_barang, a.tanggal_terima, a.tanggal_pemusnahan,
                    a.no_terima, b.noseri, b.noseri_beli, b.company, b.nopo, b.ref, b.tgldo, b.tgldo_beli, b.nodo_beli, b.tgl_beli, b.tglbuat,
                    d.branch_name, d.nama_comp
            from
            (			
                SELECT  id, site_code, tipe, nama, tanggal_pengajuan, 
                        tanggal_approval, 
                        a.principal_area_at, a.principal_ho_at, a.verifikasi_at, 
                        a.tanggal_kirim_barang, a.tanggal_terima_barang as tanggal_terima, a.tanggal_pemusnahan,
                        no_terima_barang as no_terima, no_pengajuan, 'V2' as versi, a.supp, a.status, a.nama_status
                FROM management_inventory.pengajuan_retur a
                WHERE (deleted is null or deleted = 0) and `status` in (8, 9, 11, 12) $params_site_code $params_periode
            )a LEFT JOIN
            (
                SELECT noseri, noseri_beli, company, nopo, ref, tgldo, tgldo_beli, nodo_beli, tgl_beli, tglbuat, id_pengajuan, no_pengajuan, versi
                FROM mpm.trans a
                WHERE deleted = 0
                GROUP BY id_pengajuan, no_pengajuan, versi
            )b on a.id = b.id_pengajuan and a.no_pengajuan = b.no_pengajuan left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
                union all 
                select '001-herbal' as supp, 'DELTOMED_HERBAL' as namasupp
                union all 
                select '001-herbana' as supp, 'DELTOMED_HERBANA' as namasupp
                union all 
                select '001-GT' as supp, 'DELTOMED-GT' as namasupp
                union all 
                select '001-MTI' as supp, 'DELTOMED-MTI' as namasupp
                union all 
                select '001-NKA' as supp, 'DELTOMED-NKA' as namasupp
                union all 
                select '001-GT-PHARMA' as supp, 'DELTOMED-GT-PHARMA' as namasupp 
            )c on a.supp = c.supp left join (
            select  concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on a.site_code = d.site_code
        $params_status
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);

    }

    public function get_customerid_by_userid($userid)
    {
        $query = "
            select concat(1, a.kode_lang) as customerid
            from mpm.user a
            where a.id = $userid
        ";

        return $this->db->query($query);
    }

    public function update_trans_ref_by_tglbuat_userid($customerid,$from,$to,$userid)
    {
        $query = "
            update mpm.trans a
            set a.ref = (
                select b.ref
                from management_retur.master_dbsls b
                where a.noseri = b.no_seri_pajak and b.customerid = $customerid
                GROUP BY b.ref, b.no_seri_pajak
            )
            where (a.tglbuat between '$from' and '$to') and a.userid = $userid and deleted = 0 and a.ref is null
        ";
        
        $proses = $this->db->query($query);
        if ($proses) {
            return 1;
        } else {
            return 0;
        }
        
    }

    public function export_retur_dashboard($userid,$supp, $from, $to)
    {   
        // echo 'disini'; die;
        if ($userid) {
            $params = "and a.userid = $userid";
        }else{
            $params = "";
        }

        $query="
            select 	a.company, REPLACE(a.tgldo_beli, '-', '/') as tgldo_beli, REPLACE(a.tgldo, '-', '/') as tgldo, a.nopo, a.nodo, a.ref, 
            a.nodo_beli, if(SUBSTRING(a.noseri,4,1) = '.', a.noseri,  concat('''',a.noseri)) as noseri, if(SUBSTRING(a.noseri_beli,4,1) = '.', 
            a.noseri_beli,  concat('''',a.noseri_beli)) as noseri_beli, b.kodeprod, b.namaprod, abs(b.banyak) as qty, b.harga, b.harga_beli, 
            b.diskon, b.diskon_beli, REPLACE(a.tglbuat, '-', '/') as tglbuat, a.no_pengajuan
            from mpm.trans a inner JOIN mpm.trans_detail b
                    on a.id = b.id_ref
            where  a.supp = '$supp' and a.deleted = 0 and b.deleted = 0 and (a.tglbuat between '$from' and '$to') $params
            ORDER BY a.tglbuat desc, a.company asc, a.nopo, b.kodeprod
        ";

        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'company', 'tgldo_beli', 'tgldo', 'nopo', 'nodo', 'ref', 'nodo_beli', 'noseri', 'noseri_beli', 'kodeprod', 'namaprod', 'qty', 'harga', 'harga_beli', 'diskon', 'diskon_beli', 'tglbuat', 'no_pengajuan'
        ));
            
        $this->excel_generator->set_column(array
        (
            'company', 'tgldo_beli', 'tgldo', 'nopo', 'nodo', 'ref', 'nodo_beli', 'noseri', 'noseri_beli', 'kodeprod', 'namaprod', 'qty', 'harga', 'harga_beli', 'diskon', 'diskon_beli', 'tglbuat', 'no_pengajuan'
        ));       
        
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
        $this->excel_generator->exportTo2007('export'); 
    }

    public function update_nodo_beli($supp,$from,$to,$iduser,$romawi)
    {
        $query_update ="
            CALL management_retur.update_nodo_beli_sortir($supp,'$from','$to',$iduser,'$romawi');
        ";

        return $this->db->query($query_update);
    }

    public function update_trans_coretax($signature)
    {
        $query = "
            UPDATE mpm.trans a
            SET a.no_coretax = (
                SELECT b.no_coretax b
                FROM management_retur.import_coretax b
                WHERE b.signature = '$signature' and b.nodo_retur = a.nodo_beli
            )
            WHERE a.no_coretax is NULL
        ";
        return $this->db->query($query);
    }
}