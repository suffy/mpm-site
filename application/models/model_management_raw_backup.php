<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_raw extends CI_Model 
{
    public function get_raw_draft($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "raw_kendari"; 
        }elseif ($site_code == 'PBNP9') {
            $params_site_code = "raw_pangkalanbun"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from management_raw.$params_site_code a
            $params
        ";
        return $this->db->query($query);
    }

    public function update_kodeproduk($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.kodeproduk = concat(0,a.kodeproduk)
            where a.signature = '$signature' and length(a.kodeproduk) = 5 
        ";
        $update = $this->db->query($query);

        $query2 = "
            update management_raw.$params_site_code a 
            set a.kodeprodukprincipal = concat(0,a.kodeprodukprincipal)
            where a.signature = '$signature' and length(a.kodeprodukprincipal) = 5 
        ";
        $update2 = $this->db->query($query2);

        if ($update2) {
            return $update2;
        }else{
            return array();
        }
    }

    public function update_kodeproduk_bontang($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.productid = concat(0,a.productid)
            where a.signature = '$signature' and length(a.productid) = 5 
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_kodeproduk_pangkalanbun($signature){

        $query = "
            update management_raw.raw_pangkalanbun a 
            set a.kode = concat(0,a.productid)
            where a.signature = '$signature' 
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_kodeproduk_left($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }


        $query = "
            update management_raw.$params_site_code a 
            set a.kodeproduk = left(a.kodeproduk, 6)
            where a.signature = '$signature' and length(a.kodeproduk) > 6 
        ";
        $update = $this->db->query($query);

        $query2 = "
            update management_raw.$params_site_code a 
            set a.kodeprodukprincipal = left(a.kodeprodukprincipal, 6)
            where a.signature = '$signature' and length(a.kodeprodukprincipal) > 6
        ";
        $update2 = $this->db->query($query2);

        if ($update2) {
            return $update2;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', '', a.*, '', '', '', '', '', '', '', '', '', '', b.supp, b.grupprod, '', '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprodukprincipal = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_barabai($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', '', a.*, '', '', '', '', '', '', '', '', '', '', b.supp, b.grupprod, '', '', '', '', '', '', '', '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprodukprincipal = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_banjarmasin($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', '', a.*, '', '', '', '', '', '', '', '', '', '', b.supp, b.grupprod, '', '', '', '', '', '', '', '', '', '' 
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprodukprincipal = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_samarinda($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
            $params_site_code2 = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
            $params_site_code2 = "raw_pontianak"; 
        }
        

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, '', '', '', '', '', '', b.supp, b.grupprod, '', '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.productid = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.namaproduk = (
                select b.namaprod
                from mpm.tabprod b 
                where a.kodeproduk = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.kodeproduk = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.kodeproduk = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.kodeproduk = b.kodeprod
            ), a.map_groupdiv = (
                select b.new_groupdiv
                from mpm.tabprod b 
                where a.kodeprodukprincipal = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_samarinda($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.nama_invoice = (
                select b.namaprod
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_groupdiv = (
                select b.new_groupdiv
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_branch($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.branch_name = (
                select b.branch_name
                from mpm.tbl_tabcomp b 
                where b.status = 1 and a.site_code = concat(b.kode_comp, b.nocab)
                group by concat(b.kode_comp, b.nocab)
            ),a.nama_comp = (
                select b.nama_comp
                from mpm.tbl_tabcomp b 
                where b.status = 1 and a.site_code = concat(b.kode_comp, b.nocab)
                group by concat(b.kode_comp, b.nocab)
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function get_log_upload($site_code){
        
        if ($site_code) {
            $params = "and a.site_code = '$site_code'";
        }else{
            $params = "";
        }

        $query = "
            select  a.site_code, a.filename, a.created_at, a.created_by, a.signature, a.count_raw, a.count_mapping, a.tahun, a.bulan, a.omzet_raw, a.omzet_web,
                    b.branch_name, b.nama_comp, c.username
            from management_raw.log_upload a LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.type_file = 'raw_sales' $params
            order by a.id desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_log_customer_upload($site_code = ''){

        if ($site_code) {
            $params = "and a.site_code = '$site_code'";
        }else{
            $params = "";
        }

        $query = "
            select a.site_code, a.filename, a.count_raw, a.count_mapping, a.created_at, a.created_by, a.signature, b.branch_name, b.nama_comp, c.username
            from management_raw.log_upload a LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.type_file = 'raw_customer' $params
            order by a.id desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        // if ($this->db->query($query)->num_rows() > 0) {
        //     return $this->db->query($query);
        // }else{
        //     return array();
        // }        

        return $this->db->query($query);
    }

    public function get_raw_customer_draft($signature = ''){

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from management_raw.raw_customer_banjarmasin a
            $params
        ";
        return $this->db->query($query);
    }

    public function get_raw_customer_draft_barabai($signature = ''){

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from management_raw.raw_customer_barabai a
            $params
        ";
        return $this->db->query($query);
    }

    public function trim_customer_banjarmasin($signature){
        $query = "
        update management_raw.raw_customer_banjarmasin a 
        set a.kode_type = trim(a.kode_type), a.kode_class = trim(a.kode_class), a.customer_id = trim(a.customer_id), a.customer_id_nd6 = trim(a.customer_id_nd6)
        where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function trim_customer_barabai($signature){
        $query = "
        update management_raw.raw_customer_barabai a 
        set a.kode_type = trim(a.kode_type), a.kode_class = trim(a.kode_class), a.customer_id = trim(a.customer_id), a.customer_id_nd6 = trim(a.customer_id_nd6)
        where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        die;

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_type_n_class($signature){
        $query = "
        update management_raw.raw_customer_banjarmasin a 
        set a.nama_type = (
            select b.nama_type
            from mpm.tbl_bantu_type b
            where a.kode_type = b.kode_type
        ), a.sektor = (
            select b.sektor
            from mpm.tbl_bantu_type b 
            where a.kode_type = b.kode_type
        ), a.segment = (
            select b.segment
            from mpm.tbl_bantu_type b 
            where a.kode_type = b.kode_type
        ), a.nama_class = (
            select c.jenis
            from mpm.tbl_tabsalur c 
            where a.kode_class = c.kode
        ), a.group_class = (
            select c.group
            from mpm.tbl_tabsalur c 
            where a.kode_class = c.kode
        )
        where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_type_n_class_barabai($signature){
        $query = "
        update management_raw.raw_customer_barabai a 
        set a.nama_type = (
            select b.nama_type
            from mpm.tbl_bantu_type b
            where a.kode_type = b.kode_type
        ), a.sektor = (
            select b.sektor
            from mpm.tbl_bantu_type b 
            where a.kode_type = b.kode_type
        ), a.segment = (
            select b.segment
            from mpm.tbl_bantu_type b 
            where a.kode_type = b.kode_type
        ), a.nama_class = (
            select c.jenis
            from mpm.tbl_tabsalur c 
            where a.kode_class = c.kode
        ), a.group_class = (
            select c.group
            from mpm.tbl_tabsalur c 
            where a.kode_class = c.kode
        )
        where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        die;

        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_customer_banjarmasin($signature){
        $query = "
        insert into management_raw.inner_raw_customer_banjarmasin
        select '', a.*
        from management_raw.raw_customer_banjarmasin a 
        where a.signature = '$signature'
        group by a.customer_id_nd6
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_customer_barabai($signature){
        $query = "
        insert into management_raw.inner_raw_customer_barabai
        select '', a.*
        from management_raw.raw_customer_barabai a 
        where a.signature = '$signature'
        group by a.customer_id_nd6
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_inner_customer_id_banjarmasin($signature){

        $query = "
        update management_raw.inner_raw_banjarmasin a 
        set a.customer_id = (
            select b.customer_id
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_customer = (
            select b.nama_customer
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.alamat = (
            select b.alamat
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_type = (
            select b.kode_type
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_type = (
            select b.nama_type
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.sektor = (
            select b.sektor
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.segment = (
            select b.segment
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_class = (
            select b.kode_class
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_class = (
            select b.nama_class
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.group_class = (
            select b.group_class
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_kota = (
            select b.kode_kota
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_kota = (
            select b.nama_kota
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_kecamatan = (
            select b.kode_kecamatan
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_kecamatan = (
            select b.nama_kecamatan
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_kelurahan = (
            select b.kode_kelurahan
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_kelurahan = (
            select b.nama_kelurahan
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        )
        where a.signature = '$signature'
        
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_inner_customer_id_barabai($signature){

        $query = "
        update management_raw.inner_raw_barabai a 
        set a.customer_id = (
            select b.customer_id
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_customer = (
            select b.nama_customer
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.alamat = (
            select b.alamat
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.kode_type = (
            select b.kode_type
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_type = (
            select b.nama_type
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.sektor = (
            select b.sektor
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.segment = (
            select b.segment
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.kode_class = (
            select b.kode_class
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_class = (
            select b.nama_class
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.group_class = (
            select b.group_class
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.kode_kota = (
            select b.kode_kota
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_kota = (
            select b.nama_kota
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.kode_kecamatan = (
            select b.kode_kecamatan
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_kecamatan = (
            select b.nama_kecamatan
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_kelurahan = (
            select b.kode_kelurahan
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_kelurahan = (
            select b.nama_kelurahan
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        )
        where a.signature = '$signature'
        
        ";
        $update = $this->db->query($query);


        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";



        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_inner_customer_id_batulicin($signature){

        $query = "
        update management_raw.inner_raw_batulicin a 
        set a.customer_id = (
            select b.customer_id
            from management_raw.raw_customer_batulicin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_customer = (
            select b.nama_customer
            from management_raw.raw_customer_batulicin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.alamat = (
            select b.alamat
            from management_raw.raw_customer_batulicin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        )
        where a.signature = '$signature'
        
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_master_customer_batulicin($signature){

        $query = "
            select a.kodecustomer, a.customer_id, a.namacustomer, a.alamatcustomer
            from management_raw.inner_raw_batulicin a
            where a.signature = '$signature' and a.customer_id is null
            group by a.kodecustomer
        ";

        $get_customer_null =  $this->db->query($query);

        if ($get_customer_null->num_rows() > 0) {

            foreach ($get_customer_null->result() as $key) {
                $query = "
                    select right(customer_id,5) as customer_id
                    from management_raw.raw_customer_batulicin a
                    order by a.id desc
                ";

                $get_customer_last = $this->db->query($query);

                if ($get_customer_last->num_rows() > 0) {
                    $last_customer_id = $get_customer_last->row()->customer_id;
                }else{
                    $last_customer_id = 0000;
                }

                $insert = [
                    'customer_id_nd6'   => $key->kodecustomer,
                    'customer_id'       => "1".sprintf('%05d',intval($last_customer_id) + 1),
                    'nama_customer'     => $key->namacustomer,
                    'alamat'            => $key->alamatcustomer,
                ];
                $this->db->insert('management_raw.raw_customer_batulicin', $insert);
            }

            return 1;
        }else{
            return array();
        }

    }

    public function insert_mpm_upload($site_code, $signature, $omzet = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }

        $query_tanggal = "
            select  a.tanggal, if(length(max(day(a.tanggal))) = 1, concat('0',max(day(a.tanggal))), max(day(a.tanggal))) as hari, if(length(max(month(a.tanggal))) = 1, concat('0',max(month(a.tanggal))), max(month(a.tanggal))) as bulan, max(year(a.tanggal)) as tahun  
            from management_raw.$params_site_code a
            where a.signature = '$signature'
        ";

        // die;

        $get_hari = $this->db->query($query_tanggal)->row()->hari;
        $get_bulan = $this->db->query($query_tanggal)->row()->bulan;
        $get_tahun = $this->db->query($query_tanggal)->row()->tahun;

        // die;
        $created_at = $this->model_outlet_transaksi->timezone();

        // die;

        $cek_userid  = "
            select id
            from mpm.user a
            where a.username = left('$site_code',3)
        ";

        // die;

        $userid = $this->db->query($cek_userid)->row()->id;

        $insert = [
            'userid'            => $userid,
            'lastupload'        => $created_at,
            'filename'          => 'NON SDS',
            'tanggal'           => $get_hari,
            'bulan'             => $get_bulan,
            'tahun'             => $get_tahun,
            'status'            => 1,
            'status_closing'    => 0,
            'omzet'             => $omzet,
            'flag_check'        => 0
        ];

        return $this->db->insert('mpm.upload', $insert);

    }

    public function update_tanggal($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.tanggal = str_to_date(a.tanggal,'%m/%d/%Y')
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function delete_tabel($site_code, $signature){
        $nocab = substr($site_code, 3, 2);

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and concat(kode_comp, nocab) = '$site_code'
        ";
        $this->db->query($query);

        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and concat(kode_comp, nocab) = '$site_code'
        ";
        $delete = $this->db->query($query);

        $query = "
            delete from data$tahun.tblang_temp
            where concat(kode_comp, nocab) = '$site_code'
        ";
        $delete = $this->db->query($query);

        $query = "
            delete from data$tahun.tabsales
            where nocab = '$nocab'
        ";
        $delete = $this->db->query($query);

        $query = "
            delete from data$tahun.tbkota
            where concat(kode_comp, nocab) = '$site_code'
        ";
        $delete = $this->db->query($query);

        $query = "
            delete from data$tahun.tabtype
            where nocab = '$nocab'
        ";
        $delete = $this->db->query($query);

        return $delete;
    }

    public function insert_fi_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 	'07' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, a.kodesalesman as kodesales, 
                'SSJ' as kode_comp, 'BAT' as kode_kota, 
                case 		
                    when a.namacustomer LIKE '%APOTIK%' then 'APT'		
                    when a.namacustomer LIKE '%APOTEK%' then 'APT'	
                    when a.channel like '%MODERN OUTLET (LOKAL)%' then 'MML'
                    when NAMACUSTOMER LIKE'%PT.%' then 'PBF'		
                    else 'TKL'		
                end as kode_type,
                a.customer_id as kode_lang,
                '' as kode_rayon,
                a.kodeprodukprincipal as kodeprod,
                a.supp as supp,
                day(a.tanggal) as hrdok,
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,	
                year(a.tanggal) as thndok,
                a.namaproduk as namaprod,
                a.map_group as groupprod,
                if(a.grossamount*1.11='0','0',a.qtysoldpcs/b.satuan) as banyak,		
                if(a.grossamount*1.11/a.qtysoldpcs is null,0,round(a.grossamount*1.11/(a.qtysoldpcs/b.satuan),2)) as harga,	
                round(a.totallinediscount*1.11,2) as potongan,		
                round(a.grossamount*1.11,2)as tot1,		
                '' as jum_promo, 		
                if(a.grossamount='0',concat(a.kodeprodukprincipal,' ',a.qtysoldpcs),'') as keterangan,  		
                '' as user_isi,		
                '' as jam_isi,		
                '' as tgl_isi, 		
                '' as user_edit, 		
                '' as jam_edit, 		
                '' as tgl_edit,		
                '' as user_del, 		
                '' as jam_del, 		
                '' as tgl_del, 		
                '' as no, 		
                '' as backup, 		
                '' as no_urut, 		
                'PST' as kode_gdg, 		
                '' as nama_gdg,
                case		
                    when a.channel LIKE'%SEMI GROSIR%' THEN 'SWS'		
                    when a.channel LIKE'%MINI MARKET%' THEN 'WS'		
                else 'RT' end as kodesalur,		
                '' as kodebonus,		
                '' as namabonus, 		
                '' as grupbonus, 		
                '' as unitbonus, 		
                a.namasalesman as lampiran, 		
                '' as h_beli, 		
                '' as kodearea, 		
                a.alamatcustomer as namaarea, 		
                '' as pinjam, 		
                '' as jualbanyak, 		
                '' as jualpinjam, 		
                '' as harga_excl, 		
                '' as tot1_excl, 		
                a.namacustomer as nama_lang,		
                'D2' as nocab,		
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
                ' ' as siteid,		
                ' ' as qty1,		
                ' ' as qty2,		
                ' ' as qty3,		
                ' ' as qty_bonus,		
                ' ' as flag_bonus,		
                ' ' as disc_persen,		
                ' ' as disc_rp,		
                ' ' as disc_value,		
                ' ' as disc_cabang,		
                ' ' as disc_prinsipal,		
                ' ' as disc_xtra,		
                ' ' as rp_cabang,		
                ' ' as rp_prinsipal,		
                ' ' as rp_xtra,		
                ' ' as bonus,		
                ' ' as prinsipalid,		
                ' ' as ex_no_sales,		
                ' ' as status_retur,		
                ' ' as ref,		
                ' ' as term_payment,		
                ' ' as tipe_kl				

        from management_raw.inner_raw_batulicin a LEFT JOIN pmu.mapping b 
        on a.kodeprodukprincipal = b.kodeprod
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and b.nocab = 'd2' and a.tipetrans = 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_fi_banjarmasin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.fi
        select  '08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, 
					a.kodesalesman as kodesales, 'SSM' as kode_comp, a.kode_kota AS kode_kota, 
					a.kode_type as kode_type, a.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod,
					a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
					if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
					if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
					a.namaproduk as namaprod, a.groupprod, a.qtysoldtotalpcs as banyak,
					(a.grossamount*1.11) / a.qtysoldtotalpcs as harga, '' as potongan,
					(a.grossamount*1.11) as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
					'' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
					'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
					'' as unitbonus, '' as lampiran, (a.grossamount*1.11) / a.qtysoldtotalpcs as h_beli, '' as kodearea, '' as namarea,
					'' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, a.namacustomer as namalang, 
					right(a.site_code,2) as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
					'SSM001' as siteid, '' as qty1, '' as qty2, '' as qty3, '' as qty_bonus, '' as flag_bonus, '' as disc_persen, '' as disc_rp, 
					'' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
					'' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
					'' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_banjarmasin a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipetrans = 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_banjarmasin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select  '08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, 
					a.kodesalesman as kodesales, 'SSM' as kode_comp, a.kode_kota AS kode_kota, 
					a.kode_type as kode_type, a.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod,
					a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
					if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
					if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
					a.namaproduk as namaprod, a.groupprod, a.qtysoldtotalpcs as banyak,
					(a.grossamount*1.11) / a.qtysoldtotalpcs as harga, '' as potongan,
					(a.grossamount*1.11) as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
					'' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
					'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
					'' as unitbonus, '' as lampiran, (a.grossamount*1.11) / a.qtysoldtotalpcs as h_beli, '' as kodearea, '' as namarea,
					'' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, a.namacustomer as namalang, 
					right(a.site_code,2) as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
					'SSM001' as siteid, '' as qty1, '' as qty2, '' as qty3, '' as qty_bonus, '' as flag_bonus, '' as disc_persen, '' as disc_rp, 
					'' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
					'' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
					'' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_banjarmasin a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipetrans <> 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_fi_barabai($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.fi
        select  '08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, 
                a.kodesalesman as kodesales, 'BRB' as kode_comp, a.kode_kota AS kode_kota, 
                a.kode_type as kode_type, a.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod,
                a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
                if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
                if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
                a.namaproduk as namaprod, a.groupprod, a.qtysoldpcs as banyak,
                (a.grossamount*1.11) / a.qtysoldpcs as harga, '' as potongan,
                (a.grossamount*1.11) as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
                '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
                'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
                '' as unitbonus, '' as lampiran, (a.grossamount*1.11) / a.qtysoldpcs as h_beli, '' as kodearea, a.alamat as namarea,
                '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, a.namacustomer as namalang, 
                right(a.site_code,2) as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
                'BRB001' as siteid, '' as qty1, '' as qty2, '' as qty3, '' as qty_bonus, '' as flag_bonus, '' as disc_persen, '' as disc_rp, 
                '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
                '' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_barabai a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipetrans = 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_ri_barabai($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select  '08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, 
                a.kodesalesman as kodesales, 'BRB' as kode_comp, a.kode_kota AS kode_kota, 
                a.kode_type as kode_type, a.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod,
                a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
                if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
                if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
                a.namaproduk as namaprod, a.groupprod, a.qtysoldpcs as banyak,
                (a.grossamount*1.11) / a.qtysoldpcs as harga, '' as potongan,
                (a.grossamount*1.11) as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
                '' as user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
                'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
                '' as unitbonus, '' as lampiran, (a.grossamount*1.11) / a.qtysoldpcs as h_beli, '' as kodearea, '' as namarea,
                '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, a.namacustomer as namalang, 
                right(a.site_code,2) as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
                'BRB001' as siteid, '' as qty1, '' as qty2, '' as qty3, '' as qty_bonus, '' as flag_bonus, '' as disc_persen, '' as disc_rp, 
                '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
                '' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_barabai a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipetrans <> 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_fi_samarinda($site_code, $signature){

        if ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }
        
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.fi
        select  '07' as kddokjdi, a.no_sales as nodokjdi, a.no_sales as nodokacu, a.tanggal as tgldokjdi,
                a.salesmanid as kodesales, left(a.site_code, 3) as kode_comp, a.kotaid as kode_kota,
                a.typeid as kode_type, a.customerid as kode_lang, '' as koderayon, a.productid as kodeprod,
                a.supp as supp, if(length(day(a.tanggal)) = 1, concat('0', day(a.tanggal)), day(a.tanggal))  as hrdok,
                if(length(month(a.tanggal)) = 1, concat('0', month(a.tanggal)), month(a.tanggal))  as blndok,
                year(a.tanggal) as thndok, a.nama_invoice, a.map_group as groupprod, a.qty_kecil as banyak,
                (a.rp_kotor / a.qty_kecil) as harga, a.rp_discount as potongan, a.rp_kotor as tot1, 0 as jum_promo,
                '' as keterangan, '' as user_isi,		
                '' as jam_isi,		
                '' as tgl_isi, 		
                '' as user_edit, 		
                '' as jam_edit, 		
                '' as tgl_edit,		
                '' as user_del, 		
                '' as jam_del, 		
                '' as tgl_del, 		
                '' as no, 		
                '' as backup, 		
                '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, a.classid as kodesalur, '' as kodebonus,		
                '' as namabonus, '' as grupbonus, '' as unitbonus, 	a.nama_salesman as lampiran, (a.rp_kotor / a.qty_kecil) as h_beli,
                '' as kodearea, a.alamat as namaarea, '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 		
                a.nama_customer, right(a.site_code, 2) as nocab, 
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,	
                a.siteid, '' as qty1, '' as qty2, a.qty_kecil as qty3,
                ' ' as qty_bonus,		
                ' ' as flag_bonus,		
                ' ' as disc_persen,		
                ' ' as disc_rp,		
                ' ' as disc_value,		
                ' ' as disc_cabang,		
                ' ' as disc_prinsipal,		
                ' ' as disc_xtra,		
                ' ' as rp_cabang,		
                ' ' as rp_prinsipal,		
                ' ' as rp_xtra,		
                ' ' as bonus,		
                ' ' as prinsipalid,		
                ' ' as ex_no_sales,		
                ' ' as status_retur,		
                ' ' as ref,		
                ' ' as term_payment,		
                ' ' as tipe_kl	
        from management_raw.$params_site_code a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipe_trans = 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_ri_samarinda($site_code, $signature){
        if ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.ri
        select  '07' as kddokjdi, a.no_sales as nodokjdi, a.no_sales as nodokacu, a.tanggal as tgldokjdi,
                a.salesmanid as kodesales, left(a.site_code, 3) as kode_comp, a.kotaid as kode_kota,
                a.typeid as kode_type, a.customerid as kode_lang, '' as koderayon, a.productid as kodeprod,
                a.supp as supp, if(length(day(a.tanggal)) = 1, concat('0', day(a.tanggal)), day(a.tanggal)) as hrdok,
                if(length(month(a.tanggal)) = 1, concat('0', month(a.tanggal)), month(a.tanggal)) as blndok,
                year(a.tanggal) as thndok, a.nama_invoice, a.map_group as groupprod, a.qty_kecil as banyak,
                (a.rp_kotor / a.qty_kecil) as harga, a.rp_discount as potongan, a.rp_kotor as tot1, 0 as jum_promo,
                '' as keterangan, '' as user_isi,		
                '' as jam_isi,		
                '' as tgl_isi, 		
                '' as user_edit, 		
                '' as jam_edit, 		
                '' as tgl_edit,		
                '' as user_del, 		
                '' as jam_del, 		
                '' as tgl_del, 		
                '' as no, 		
                '' as backup, 		
                '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, a.classid as kodesalur, '' as kodebonus,		
                '' as namabonus, '' as grupbonus, '' as unitbonus, 	a.nama_salesman as lampiran, (a.rp_kotor / a.qty_kecil) as h_beli,
                '' as kodearea, a.alamat as namaarea, '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, 		
                a.nama_customer, right(a.site_code, 2) as nocab, 
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,	
                a.siteid, '' as qty1, '' as qty2, a.qty_kecil as qty3,
                ' ' as qty_bonus,		
                ' ' as flag_bonus,		
                ' ' as disc_persen,		
                ' ' as disc_rp,		
                ' ' as disc_value,		
                ' ' as disc_cabang,		
                ' ' as disc_prinsipal,		
                ' ' as disc_xtra,		
                ' ' as rp_cabang,		
                ' ' as rp_prinsipal,		
                ' ' as rp_xtra,		
                ' ' as bonus,		
                ' ' as prinsipalid,		
                ' ' as ex_no_sales,		
                ' ' as status_retur,		
                ' ' as ref,		
                ' ' as term_payment,		
                ' ' as tipe_kl	
        from management_raw.$params_site_code a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipe_trans = 'retur'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_ri_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 	'07' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, a.kodesalesman as kodesales, 
                'SSJ' as kode_comp, 'bat' as kode_kota, 
                case 		
                    when a.namacustomer LIKE '%APOTIK%' then 'APT'		
                    when a.namacustomer LIKE '%APOTEK%' then 'APT'	
                    when a.channel like '%MODERN OUTLET (LOKAL)%' then 'MML'
                    when NAMACUSTOMER LIKE'%PT.%' then 'PBF'		
                    else 'TKL'		
                end as kode_type,
                a.customer_id as kode_lang,
                '' as kode_rayon,
                a.kodeprodukprincipal as kodeprod,
                a.supp as supp,
                day(a.tanggal) as hrdok,
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
                year(a.tanggal) as thndok,
                a.namaproduk as namaprod,
                a.map_group as groupprod,
                if(a.grossamount*1.11='0','0',a.qtysoldpcs/b.satuan) as banyak,		
                if(a.grossamount*1.11/a.qtysoldpcs is null,0,round(a.grossamount*1.11/(a.qtysoldpcs/b.satuan),2)) as harga,	
                round(a.totallinediscount*1.11,2) as potongan,		
                round(a.grossamount*1.11,2)as tot1,		
                '' as jum_promo, 		
                if(a.grossamount='0',concat(a.kodeprodukprincipal,' ',a.qtysoldpcs),'') as keterangan,  		
                '' as user_isi,		
                '' as jam_isi,		
                '' as tgl_isi, 		
                '' as user_edit, 		
                '' as jam_edit, 		
                '' as tgl_edit,		
                '' as user_del, 		
                '' as jam_del, 		
                '' as tgl_del, 		
                '' as no, 		
                '' as backup, 		
                '' as no_urut, 		
                'PST' as kode_gdg, 		
                '' as nama_gdg,
                case		
                    when a.channel LIKE'%SEMI GROSIR%' THEN 'SWS'		
                    when a.channel LIKE'%MINI MARKET%' THEN 'WS'		
                else 'RT' end as kodesalur,		
                '' as kodebonus,		
                '' as namabonus, 		
                '' as grupbonus, 		
                '' as unitbonus, 		
                a.namasalesman as lampiran, 		
                '' as h_beli, 		
                '' as kodearea, 		
                a.alamatcustomer as namaarea, 		
                '' as pinjam, 		
                '' as jualbanyak, 		
                '' as jualpinjam, 		
                '' as harga_excl, 			
                a.namacustomer as nama_lang,		
                'D2' as nocab,		
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
                ' ' as siteid,		
                ' ' as qty1,		
                ' ' as qty2,		
                ' ' as qty3,		
                ' ' as qty_bonus,		
                ' ' as flag_bonus,		
                ' ' as disc_persen,		
                ' ' as disc_rp,		
                ' ' as disc_value,		
                ' ' as disc_cabang,		
                ' ' as disc_prinsipal,		
                ' ' as disc_xtra,		
                ' ' as rp_cabang,		
                ' ' as rp_prinsipal,		
                ' ' as rp_xtra,		
                ' ' as bonus,		
                ' ' as prinsipalid,		
                ' ' as ex_no_sales,		
                ' ' as status_retur,		
                ' ' as ref,		
                ' ' as term_payment,		
                ' ' as tipe_kl				

        from management_raw.inner_raw_batulicin a LEFT JOIN pmu.mapping b 
        on a.kodeprodukprincipal = b.kodeprod
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and b.nocab = 'd2' and a.tipetrans <> 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tblang_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;
        
        $insert = "
            insert into data$tahun.tblang_temp
            select a.kode_comp, a.kode_kota, a.kode_type, trim(a.kode_lang), a.koderayon, a.nama_lang,
					a.namaarea as alamat1, '' as alamat2, '' as telp, 
					'' as kodepos,		
					'' as tgl,		
					'' as npwp,		
					'0' as bts_utang,		
					'0' as sales01,		
					'0' as sales02,		
					'0' as sales03,		
					'0' as sales04,		
					'0' as sales05,		
					'0' as sales06,		
					'0' as sales07,		
					'0' as sales08,		
					'0' as sales09,		
					'0' as sales10,		
					'0' as sales11,		
					'0' as sales12,		
					'0' as ket,		
					'0' as debit,		
					'0' as kredit,		
					a.kodesalur as kodesalur,		
					'0' as top,		
					'Y' as aktif,		
					'' as tgl_aktif,		
					'T' as ppn,		
					'0' as kode_lama,		
					'1' as jum_dok,		
					'0' as statjual,		
					'0' as limit1,		
					'' as tglnaktif,		
					'' as ALAMAT_WP,		
					'' as NILAI_PPN,		
					'' as NAMA_WP,		
					'' as NEWFLD,		
					a.nocab as nocab,		
					'' as kodelang_copy,		
					'' as id_provinsi,		
					'' as nama_provinsi,		
					'' as id_kota,		
					'' as nama_kota,		
					'' as id_kecamatan,		
					'' as nama_kecamatan,		
					'' as id_kelurahan,		
					'' as nama_kelurahan,		
					'' as credit_limit,		
					'' as tipe_bayar,		
					'' as phone,		
					'' AS last_updated,		
					'' as status_blacklist,		
					'' as status_payment,		
					'' as CUSTID,		
					'' as COMPID,		
					'' as LATITUDE,		
					'' as LONGITUDE,		
					'' as FOTO_DISP,		
					'' as FOTO_TOKO	
            from data$tahun.fi a 
            where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            GROUP BY concat(a.kode_comp, a.kode_lang)
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";
        // die;
        $this->db->query($insert);

        $get_data = "
            select *
            from data$tahun.tblang_temp a 
            where concat(a.kode_comp, a.nocab) = '$site_code'
        ";
        $proses_get_data = $this->db->query($get_data);
        if ($proses_get_data->num_rows() > 0) {
            
            foreach ($proses_get_data->result() as $a) {
                $kode_lang = $a->kode_lang;

                if ($kode_lang) {
                    # code...
                }


            }

        }


    }

    public function insert_tabsales_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tabsales
            select *
            from 
            (
                select  a.kodesales,		
                        a.lampiran as namasales,		
                        ''AS koderayon,		
                        'S'AS `status`,		
                        a.namaarea AS alamat1,		
                        a.namaarea AS alamat2,		
                        ''AS NO_TELP,		
                        '' AS KODEPOS,		
                        '' AS PROPINSI,		
                        '' AS DATA1,		
                        '' AS TAHAP,		
                        '' AS FILEID,		
                        '' AS NAMA_DEPO,		
                        a.kode_kota,		
                        '' AS KODE_GDG,		
                        '' AS NAMA_GDG,		
                        'Y' AS AKTIF,		
                        a.nocab
                from data$tahun.fi a 
                where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
                union all
                select  a.kodesales,		
                        a.lampiran as namasales,		
                        ''AS koderayon,		
                        'S'AS `status`,		
                        a.namaarea AS alamat1,		
                        a.namaarea AS alamat2,		
                        ''AS NO_TELP,		
                        '' AS KODEPOS,		
                        '' AS PROPINSI,		
                        '' AS DATA1,		
                        '' AS TAHAP,		
                        '' AS FILEID,		
                        '' AS NAMA_DEPO,		
                        a.kode_kota,		
                        '' AS KODE_GDG,		
                        '' AS NAMA_GDG,		
                        'Y' AS AKTIF,		
                        a.nocab
                from data$tahun.ri a 
                where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            )a GROUP BY a.kodesales
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota
            select  KODE_COMP AS KODE_COMP, 		
                    a.kode_kota AS KODE_KOTA,		
                    'BATULICIN' AS NAMA_KOTA,		
                    a.nocab as nocab		
            from data$tahun.fi a 
            where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            GROUP BY a.kode_kota
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_samarinda($site_code, $signature){

        if ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        // $bulan = $this->db->query($get_tahun)->row()->bulan;

        $nocab = substr($site_code, 3, 2);
        $kode_comp = substr($site_code, 0, 3);

        $insert = "
            insert into data$tahun.tbkota
            select '$kode_comp', a.kotaid, a.nama_kota, '$nocab'
            from management_raw.$params_site_code a 
            where a.signature = '$signature'
            GROUP BY a.kotaid
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_banjarmasin($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        // $bulan = $this->db->query($get_tahun)->row()->bulan;

        $nocab = substr($site_code, 3, 2);
        $kode_comp = substr($site_code, 0, 3);

        $insert = "
            insert into data$tahun.tbkota
            select '$kode_comp', a.kode_kota, a.nama_kota, '$nocab'
            from management_raw.$params_site_code a 
            where a.signature = '$signature'
            GROUP BY a.kode_kota
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function get_log_upload_by_signature($signature){

        $query = "select * from management_raw.log_upload a where a.signature = '$signature'";
        return $this->db->query($query);

    }

    public function get_omzet_web($tahun, $bulan, $site_code){
        $query = "
            select sum(omzet) as omzet
            from 
            (
                select sum(a.tot1) as omzet
                from data$tahun.fi a 
                where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
                union all
                select sum(a.tot1) as omzet
                from data$tahun.ri a 
                where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            )a
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_raw_customer($site_code, $customer_id_nd6){
        if ($site_code == 'BRBS0') {
            $params_site_code = "raw_customer_barabai"; 
        }elseif($site_code == 'SSMS9'){
            $params_site_code = "raw_customer_banjarmasin";
        }
        $query = "
            select *
            from management_raw.$params_site_code a 
            where a.customer_id_nd6 = '$customer_id_nd6'
        ";
        return $this->db->query($query);
    }

    public function get_summary_raw_sales($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select count(*) as count_raw, sum(a.grossamount * 1.11) as raw_bruto, sum(a.grossamount) as raw_exclude_ppn 
            from management_raw.$params_site_code a
            $params
        ";
        return $this->db->query($query);
    }

    public function get_summary_raw_sales_samarinda($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select count(*) as count_raw, sum(a.rp_kotor) as raw_bruto, sum(a.rp_netto) as raw_netto 
            from management_raw.$params_site_code a
            $params
        ";
        return $this->db->query($query);
    }

    public function get_summary_raw_sales_pangkalanbun($site_code, $signature = ''){

        if ($site_code == 'PBNP9') {
            $params_site_code = "raw_pangkalanbun"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select count(*) as count_raw, sum(a.penjualan) as raw_bruto, 0 as raw_netto 
            from management_raw.$params_site_code a
            $params
        ";
        return $this->db->query($query);
    }

    public function update_kodeproduk_kendari($site_code, $signature){

        if ($site_code == 'CSSK3') {
            $params_site_code = "raw_kendari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.kodeprod = concat(0,a.kodeprod)
            where a.signature = '$signature' and length(a.kodeprod) = 5 
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_kendari($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
            $params_site_code2 = "raw_kendari"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprod = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_kendari($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.namaprod = (
                select b.namaprod
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_tanggal_kendari($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.tgldokjdi = str_to_date(a.tgldokjdi,'%m/%d/%Y')
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 				
        KDDOKJDI as KDOKJDI,				
        NODOKJDI as NODOKJDI,				
        NODOKACU as NODOKACU,				
        tgldokjdi as TGLDOKJDI,				
        KODEJAJA as KODESALES,				
        'CSS' as KODE_COMP,				
        AREA as KODE_KOTA,				
        case 				
        when `desclang` like'%Apotik%' or `desclang` like'%Apotek%' THEN 'APT'				
        when `desclang` like'%Mart%'or `desclang` like '%MM%' THEN 'MML'				
        when `desclang` like'%Supermarket%'or `desclang` like '%Super market%' THEN 'MMN'				
        when `desclang` like'%PT%' THEN 'PBF'				
                        
                        
        ELSE 'TKL'				
        END 				
        as KODE_TYPE,				
        kodelang as KODE_LANG,				
        '' as KODERAYON,				
        c.KODEPROD as KODEPROD,				
        IF(LEFT(c.KODEPROD,2)='06','005',IF(LEFT(c.KODEPROD,2)='02','002','001')) as SUPP,				
        right(TGLDOKJDI,2) as HRDOK,				
        $bulan as BLNDOK,				
        left(TGLDOKJDI,4) as THNDOK,				
        c.NAMAPROD as NAMAPROD,				
        c.grupprod as GROUPPROD,				
        BANYAK*b.SATUAN as BANYAK,				
        round((HNA*1.11)/b.SATUAN)  as HARGA,				
        round(potongan*1.11) as POTONGAN,				
        round(TOTHNA*1.11) as TOT1,				
        '' as JUM_PROMO, 				
        if(BANYS <> '0',concat(c.KODEPROD,' ',BANYS),'') as KETERANGAN, 				
        '' as USER_ISI,				
        '' as JAM_ISI,				
        '' as TGL_ISI, 				
        '' as USER_EDIT, 				
        '' as JAM_EDIT, 				
        '' as TGL_EDIT,				
        '' as USER_DEL, 				
        '' as JAM_DEL, 				
        '' as TGL_DEL, 				
        '' as NO, 				
        '' as BACKUP, 				
        '' as NO_URUT, 				
        'PST' as KODE_GDG, 				
        '' as NAMA_GDG, 				
        IF(CLASS='RETAIL','RT','WS') as KODESALUR, 				
        '' as KODEBONUS,				
        '' as NAMABONUS, 				
        '' as GRUPBONUS, 				
        '' as UNITBONUS, 				
        NAMAJAJA as LAMPIRAN, 				
        '' as H_BELI, 				
        '' as KODEAREA, 				
        ALMTLANG as NAMAAREA, 				
        '' as PINJAM, 				
        '' as JUALBANYAK, 				
        '' as JUALPINJAM, 				
        '' as HARGA_EXCL, 				
        '' as TOT1_EXCL, 				
        NAMALANG as NAMA_LANG,				
        'K3' as NOCAB,				
        $bulan as BULAN,				
        ' ' as siteid,				
        ' ' as qty1,				
        ' ' as qty2,				
        ' ' as qty3,				
        ' ' as qty_bonus,				
        ' ' as flag_bonus,				
        ' ' as disc_persen,				
        ' ' as disc_rp,				
        ' ' as disc_value,				
        ' ' as disc_cabang,				
        ' ' as disc_prinsipal,				
        ' ' as disc_xtra,				
        ' ' as rp_cabang,				
        ' ' as rp_prinsipal,				
        ' ' as rp_xtra,				
        ' ' as bonus,				
        ' ' as prinsipalid,				
        ' ' as ex_no_sales,				
        ' ' as status_retur,				
        ' ' as ref,				
        ' ' as term_payment,				
        ' ' as tipe_kl				
                        
        from management_raw.inner_raw_kendari a 				
        left JOIN pmu.mapping b on a.kodeprod=b.`code`				
        LEFT JOIN mpm.tabprod c ON a.kodeprod=c.kodeprod				
        where nocab='K3' AND concat(banyak,hna,tothna) not like'%-%' and a.signature = '$signature' and year(a.tgldokjdi) = $tahun and month(a.tgldokjdi) = $bulan 	
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 				
        KDDOKJDI as KDOKJDI,				
        NODOKJDI as NODOKJDI,				
        NODOKACU as NODOKACU,				
        tgldokjdi as TGLDOKJDI,				
        KODEJAJA as KODESALES,				
        'CSS' as KODE_COMP,				
        AREA as KODE_KOTA,				
        case 				
        when `desclang` like'%Apotik%' or `desclang` like'%Apotek%' THEN 'APT'				
        when `desclang` like'%Mart%'or `desclang` like '%MM%' THEN 'MML'				
        when `desclang` like'%Supermarket%'or `desclang` like '%Super market%' THEN 'MMN'				
        when `desclang` like'%PT%' THEN 'PBF'				
                        
                        
        ELSE 'TKL'				
        END 				
        as KODE_TYPE,				
        kodelang as KODE_LANG,				
        '' as KODERAYON,				
        c.KODEPROD as KODEPROD,				
        IF(LEFT(c.KODEPROD,2)='06','005',IF(LEFT(c.KODEPROD,2)='02','002','001')) as SUPP,				
        right(TGLDOKJDI,2) as HRDOK,				
        $bulan as BLNDOK,				
        left(TGLDOKJDI,4) as THNDOK,				
        c.NAMAPROD as NAMAPROD,				
        c.grupprod as GROUPPROD,				
        BANYAK*b.SATUAN as BANYAK,				
        round((HNA*1.11)/b.SATUAN)  as HARGA,				
        round(potongan*1.11) as POTONGAN,				
        round(TOTHNA*1.11) as TOT1,				
        '' as JUM_PROMO, 				
        if(BANYS <> '0',concat(c.KODEPROD,' ',BANYS),'') as KETERANGAN, 				
        '' as USER_ISI,				
        '' as JAM_ISI,				
        '' as TGL_ISI, 				
        '' as USER_EDIT, 				
        '' as JAM_EDIT, 				
        '' as TGL_EDIT,				
        '' as USER_DEL, 				
        '' as JAM_DEL, 				
        '' as TGL_DEL, 				
        '' as NO, 				
        '' as BACKUP, 				
        '' as NO_URUT, 				
        'PST' as KODE_GDG, 				
        '' as NAMA_GDG, 				
        IF(CLASS='RETAIL','RT','WS') as KODESALUR, 				
        '' as KODEBONUS,				
        '' as NAMABONUS, 				
        '' as GRUPBONUS, 				
        '' as UNITBONUS, 				
        NAMAJAJA as LAMPIRAN, 				
        '' as H_BELI, 				
        '' as KODEAREA, 				
        ALMTLANG as NAMAAREA, 				
        '' as PINJAM, 				
        '' as JUALBANYAK, 				
        '' as JUALPINJAM, 				
        '' as HARGA_EXCL, 				
        NAMALANG as NAMA_LANG,				
        'K3' as NOCAB,				
        $bulan as BULAN,				
        ' ' as siteid,				
        ' ' as qty1,				
        ' ' as qty2,				
        ' ' as qty3,				
        ' ' as qty_bonus,				
        ' ' as flag_bonus,				
        ' ' as disc_persen,				
        ' ' as disc_rp,				
        ' ' as disc_value,				
        ' ' as disc_cabang,				
        ' ' as disc_prinsipal,				
        ' ' as disc_xtra,				
        ' ' as rp_cabang,				
        ' ' as rp_prinsipal,				
        ' ' as rp_xtra,				
        ' ' as bonus,				
        ' ' as prinsipalid,				
        ' ' as ex_no_sales,				
        ' ' as status_retur,				
        ' ' as ref,				
        ' ' as term_payment,				
        ' ' as tipe_kl				
                        
        from management_raw.inner_raw_kendari a 				
        left JOIN pmu.mapping b on a.kodeprod=b.`code`				
        LEFT JOIN mpm.tabprod c ON a.kodeprod=c.kodeprod				
        where nocab='K3' AND concat(banyak,hna,tothna) like'%-%' and a.signature = '$signature' and year(a.tgldokjdi) = $tahun and month(a.tgldokjdi) = $bulan 				
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tblang_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
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
            'K3' as NOCAB,				
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
            '' as FOTO_TOKO				
                            
            FROM(				
                SELECT CONCAT(KODE_COMP,KODE_LANG,max(BULAN)) as mapp				
                FROM DATA$tahun.fi 
                WHERE NOCAB= 'K3' 				
                GROUP BY kode_comp,KODE_LANG 				
            )A				
            LEFT JOIN 				
            (				
            SELECT * FROM(				
                SELECT *, CONCAT(KODE_COMP, KODE_LANG,BULAN) as mapp			
                FROM DATA$tahun.fi  				
                WHERE NOCAB='K3' 				
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
            'K3' as NOCAB,				
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
            '' as FOTO_TOKO				
                            
            FROM(				
                SELECT CONCAT(KODE_COMP,KODE_LANG,max(BULAN)) as mapp				
                FROM DATA$tahun.ri  				
                WHERE NOCAB= 'K3' 				
                GROUP BY kode_comp,KODE_LANG 				
            )A				
            LEFT JOIN 				
            (				
            SELECT * FROM(SELECT *,CONCAT(KODE_COMP,KODE_LANG,BULAN) as mapp
                FROM DATA$tahun.ri
                WHERE NOCAB= 'K3' 				
                GROUP BY MAPP 				
                )A				
            )C USING(MAPP)				
            )a group by kode_comp,kode_lang	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tabsales_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tabsales			
            SELECT 				
            a.KODESALES,				
            a.lampiran as NAMASALES,				
            ''AS KODERAYON,				
            'S'AS `STATUS`,				
            'KENDARI' AS ALAMAT1,				
            'KENDARI' AS ALAMAT2,				
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
            FROM data$tahun.fi a				
            inner JOIN 				
            (				
            SELECT kodesales, MAX(concat(kodesales,bulan)) times 				
            FROM data$tahun.fi 				
            where nocab = 'K3' 				
            GROUP BY KODESALES				
            ) b				
                ON b.times=concat(a.KODESALES,a.BULAN)				
            where nocab = 'K3' 				
            GROUP BY kodesales
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota		
            select 				
            KODE_COMP AS KODE_COMP, 				
            KODE_KOTA AS KODE_KOTA,				
            CASE 				
            WHEN KODE_KOTA=101	THEN 'DALAM KOTA'			
            WHEN KODE_KOTA=201	THEN 'UNAAHA'			
            WHEN KODE_KOTA=202	THEN 'KOLAKA'			
            WHEN KODE_KOTA=203	THEN 'ASERA'			
            WHEN KODE_KOTA=204	THEN 'BAU-BAU'			
            WHEN KODE_KOTA=205	THEN 'RAHA'			
            WHEN KODE_KOTA=206	THEN 'BOMBANA'			
            WHEN KODE_KOTA=208	THEN 'KONAWE SELATAN'			
                            
            ELSE 'KENDARI'				
            END AS NAMA_KOTA,				
            nocab AS NOCAB				
            from data$tahun.fi				
            where concat(kode_comp, nocab) = '$site_code'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_mpm_upload_kendari($site_code, $signature, $omzet = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }

        $query_tanggal = "
            select  a.tgldokjdi, if(length(max(day(a.tgldokjdi))) = 1, concat('0',max(day(a.tgldokjdi))), max(day(a.tgldokjdi))) as hari, if(length(max(month(a.tgldokjdi))) = 1, concat('0',max(month(a.tgldokjdi))), max(month(a.tgldokjdi))) as bulan, max(year(a.tgldokjdi)) as tahun  
            from management_raw.$params_site_code a
            where a.signature = '$signature'
        ";

        // die;

        $get_hari = $this->db->query($query_tanggal)->row()->hari;
        $get_bulan = $this->db->query($query_tanggal)->row()->bulan;
        $get_tahun = $this->db->query($query_tanggal)->row()->tahun;

        // die;
        $created_at = $this->model_outlet_transaksi->timezone();

        // die;

        $cek_userid  = "
            select id
            from mpm.user a
            where a.username = left('$site_code',3)
        ";

        // die;

        $userid = $this->db->query($cek_userid)->row()->id;

        $insert = [
            'userid'            => $userid,
            'lastupload'        => $created_at,
            'filename'          => 'NON SDS',
            'tanggal'           => $get_hari,
            'bulan'             => $get_bulan,
            'tahun'             => $get_tahun,
            'status'            => 1,
            'status_closing'    => 0,
            'omzet'             => $omzet,
            'flag_check'        => 0
        ];

        return $this->db->insert('mpm.upload', $insert);

    }
}