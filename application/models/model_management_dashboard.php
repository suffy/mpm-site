<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_dashboard extends CI_Model 
{
    public function get_master_target($tahun = "", $bulan = ""){
        if ($tahun == "" && $bulan == "") {
            $params_periode = "";
        }else{
            $params_periode = "where a.tahun = '$tahun' and a.bulan = '$bulan'";
        }
        $query = "
            select	a.*, b.*, c.username as created_username, d.username as updated_username
            from 	site.dashboard_master_target a left join (
                select 	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from 	mpm.tbl_tabcomp a 
                where 	a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code left join (
                select a.id, a.username, a.email
                from mpm.user a 
            )c on a.created_by = c.id left join (
                select a.id, a.username, a.email
                from mpm.user a 
            )d on a.updated_by = d.id
            $params_periode
        ";
        return $this->db->query($query);
    }

    public function generate_target($bulan){

        $params_tahun = substr($bulan, 0, 4);
        $params_bulan = substr($bulan, 5, 2);

        $get_master_site = $this->get_master_site();
        foreach ($get_master_site->result() as $a) {

            $get_master_divisi = $this->get_master_divisi();
            foreach ($get_master_divisi->result() as $b) {

                $data = [
                    'site_code' => $a->site_code,
                    'divisi'    => $b->nama_divisi,
                    'tahun'     => $params_tahun,
                    'bulan'     => $params_bulan,
                    'target_value_be' => 0,
                    'target_value_poh' => 0,
                    'target_principal' => 0,
                    'target_ot_kpi' => 0,
                    'target_ot_otsc' => 0,
                    // 'target_ot_mundur' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->session->userdata('id'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('id'),
                ];
                $this->db->insert('site.dashboard_master_target', $data);

            }
        }
        return true;
    }

    public function get_master_site(){
        $query = "
            select *
            from site.dashboard_master_site a 
            where a.status_aktif = 1
            group by a.site_code
        ";

        return $this->db->query($query);
    }

    public function get_master_divisi(){
        $query = "
            select *
            from site.dashboard_master_divisi a 
            where a.status_aktif = 1
            group by a.nama_divisi
        ";

        return $this->db->query($query);
    }

    public function get_master_type($segment = ''){

        if ($segment) {
            $params_segment = "where a.segment = '$segment'";
        }else{
            $params_segment = "";
        }

        $query = "
            select *
            from mpm.tbl_bantu_type a 
            $params_segment
        ";

        return $this->db->query($query);
    }

    public function get_kodeprod_by_divisi($divisi){
        if ($divisi == 'd1' || $divisi == 'gt herbal' || $divisi == 'mt herbal' || $divisi == 'ph herbal') {
            $get_produk = "
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
                where a.grup = 'G0101'
            ";
        }elseif($divisi == 'd2' || $divisi == 'gt candy' || $divisi == 'mt candy' || $divisi == 'ph candy'){
            $get_produk = "
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
                where a.supp = '001' and a.grup = 'G0102'
            ";
        }elseif($divisi == 'gt all' || $divisi == 'mt all' || $divisi == 'ph all'){
            $get_produk = "
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
                where a.supp = '001' and  a.grup in ('G0101','G0102','G0103')
            ";
        }elseif($divisi == 'herbana'){
            $get_produk = "
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
                where a.supp = '001' and  a.grup = 'G0103'
            ";
        }elseif($divisi == 'marguna'){
            $get_produk = "
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
                where a.supp = '002'
            ";
        }elseif($divisi == 'us'){
            $get_produk = "
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
                where a.supp = '005'
            ";
        }

        return $this->db->query($get_produk);
    }

    public function generate_dashboard_by_divisi_n_produk($params_tahun, $params_bulan, $divisi, $params_kodeprod, $params_site_code){
        
        $created_at = $this->model_outlet_transaksi->timezone();
        $id = $this->session->userdata('id');

        if ($divisi == 'd1') {

            $query = "
                insert into site.dashboard_temp_sales
                select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total) as total_value, sum(a.ot) as total_ot, '$created_at', $id
                from
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                    from data$params_tahun.fi a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                    from data$params_tahun.ri a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                )a group by a.site_code
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";

            // die;
        }elseif ($divisi == 'd2') {

            $query = "
                insert into site.dashboard_temp_sales
                select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total) as total_value, sum(a.ot) as total_ot, '$created_at', $id
                from
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                    from data$params_tahun.fi a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                    from data$params_tahun.ri a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                )a group by a.site_code
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        }elseif ($divisi == 'gt all' || $divisi == 'gt herbal' || $divisi == 'gt candy') {

            $get_master_type = $this->get_master_type('GT');

            $kode_type = '';
            foreach ($get_master_type->result() as $a) {
                $kode_type.= ","."'".$a->kode_type."'";
            }
            $params_kode_type = preg_replace('/,/', '', $kode_type,1);

            $query = "
                insert into site.dashboard_temp_sales
                select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total) as total_value, sum(a.ot) as total_ot, '$created_at', $id
                from
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                    from data$params_tahun.fi a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                    group by concat(a.kode_comp, a.nocab)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                    from data$params_tahun.ri a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                    group by concat(a.kode_comp, a.nocab)
                )a group by a.site_code
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        }elseif ($divisi == 'herbana') {

            $query = "
                insert into site.dashboard_temp_sales
                select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total) as total_value, sum(a.ot) as total_ot, '$created_at', $id
                from
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                    from data$params_tahun.fi a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                    from data$params_tahun.ri a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                )a group by a.site_code
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        }elseif ($divisi == 'marguna') {

            $query = "
                insert into site.dashboard_temp_sales
                select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total) as total_value, sum(a.ot) as total_ot, '$created_at', $id
                from
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                    from data$params_tahun.fi a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                    from data$params_tahun.ri a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                )a group by a.site_code
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        }elseif ($divisi == 'mt all' || $divisi == 'mt candy' || $divisi == 'mt herbal') {

            $get_master_type = $this->get_master_type('MT');

            $kode_type = '';
            foreach ($get_master_type->result() as $a) {
                $kode_type.= ","."'".$a->kode_type."'";
            }
            $params_kode_type = preg_replace('/,/', '', $kode_type,1);

            $query = "
                insert into site.dashboard_temp_sales
                select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total) as total_value, sum(a.ot) as total_ot, '$created_at', $id
                from
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                    from data$params_tahun.fi a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                    group by concat(a.kode_comp, a.nocab)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                    from data$params_tahun.ri a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                    group by concat(a.kode_comp, a.nocab)
                )a group by a.site_code
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        }elseif ($divisi == 'ph all' || $divisi == 'ph candy' || $divisi == 'ph herbal') {

            $get_master_type = $this->get_master_type('PH');

            $kode_type = '';
            foreach ($get_master_type->result() as $a) {
                $kode_type.= ","."'".$a->kode_type."'";
            }
            $params_kode_type = preg_replace('/,/', '', $kode_type,1);

            $query = "
                insert into site.dashboard_temp_sales
                select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total) as total_value, sum(a.ot) as total_ot, '$created_at', $id
                from
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                    from data$params_tahun.fi a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                    group by concat(a.kode_comp, a.nocab)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                    from data$params_tahun.ri a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                    group by concat(a.kode_comp, a.nocab)
                )a group by a.site_code
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        }elseif ($divisi == 'us') {

            $query = "
                insert into site.dashboard_temp_sales
                select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total) as total_value, sum(a.ot) as total_ot, '$created_at', $id
                from
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                    from data$params_tahun.fi a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                    from data$params_tahun.ri a 
                    where a.bulan = $params_bulan and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                    group by concat(a.kode_comp, a.nocab)
                )a group by a.site_code
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        }

        return $this->db->query($query);
    }

    public function generate_ot_mundur_by_divisi_n_produk($params_tahun, $params_bulan, $divisi, $params_kodeprod, $params_site_code){
        
        $created_at = $this->model_outlet_transaksi->timezone();
        $id = $this->session->userdata('id');

        $tahun_dashboard = $params_tahun; 
        $bulan_dashboard = $params_bulan;
        
        // echo "tahun_dashboard : ".$tahun_dashboard;
        // echo "bulan dashboard : ".$bulan_dashboard;

        $bulan_avg = $params_bulan - 6;
        // echo "bulan_avg : ".$bulan_avg;

        if ($bulan_avg < 0) {
            
            $tahun_avg_x = $params_tahun - 1;
            $bulan_avg_a = $params_bulan - 6;
            $bulan_avg_ax = 12 + $bulan_avg_a;
            for ($i = $bulan_avg_ax; $i <= 12; $i++) {
                $bulan_avg_ay[] = $i;
            }
            $bulan_avg_a = implode(', ', $bulan_avg_ay);
            // echo "bulan_avg_a : ".$bulan_avg_a;
            // echo "tahun_avg_x : ".$tahun_avg_x;

            if ($divisi == 'd1' || $divisi == 'd2' || $divisi == 'herbana' || $divisi == 'marguna' || $divisi == 'us')
            {

                $query = "  
                    insert into site.dashboard_temp_sales_mundur
                    select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total)/6 as avg_value, sum(a.ot)/6 as avg_ot, '$created_at', $id
                    from
                    (
                        select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                        from data$tahun_avg_x.fi a 
                        where a.bulan in ($bulan_avg_a) and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                        group by concat(a.kode_comp, a.nocab)
                        union all 
                        select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                        from data$tahun_avg_x.ri a 
                        where a.bulan in ($bulan_avg_a) and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod)
                        group by concat(a.kode_comp, a.nocab)
                    )a group by a.site_code
                ";
                return $this->db->query($query);
                // echo "<pre>";
                // print_r($query);
                // echo "</pre>";
                // die;
            }elseif ($divisi == 'gt all' || $divisi == 'gt herbal' || $divisi == 'gt candy'){

                $get_master_type = $this->get_master_type('GT');

                $kode_type = '';
                foreach ($get_master_type->result() as $a) {
                    $kode_type.= ","."'".$a->kode_type."'";
                }
                $params_kode_type = preg_replace('/,/', '', $kode_type,1);

                $query = "
                    insert into site.dashboard_temp_sales_mundur
                    select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total)/6 as avg_value, sum(a.ot)/6 as avg_ot, '$created_at', $id
                    from
                    (
                        select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                        from data$tahun_avg_x.fi a 
                        where a.bulan in ($bulan_avg_a) and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                        group by concat(a.kode_comp, a.nocab)
                        union all 
                        select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                        from data$tahun_avg_x.ri a 
                        where a.bulan in ($bulan_avg_a) and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                        group by concat(a.kode_comp, a.nocab)
                    )a group by a.site_code
                ";
                return $this->db->query($query);
                // echo "<pre>";
                // print_r($query);
                // echo "</pre>";
            }elseif ($divisi == 'mt all' || $divisi == 'mt candy' || $divisi == 'mt herbal') {

                $get_master_type = $this->get_master_type('MT');

                $kode_type = '';
                foreach ($get_master_type->result() as $a) {
                    $kode_type.= ","."'".$a->kode_type."'";
                }
                $params_kode_type = preg_replace('/,/', '', $kode_type,1);

                $query = "
                    insert into site.dashboard_temp_sales_mundur
                    select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total)/6 as avg_value, sum(a.ot)/6 as avg_ot, '$created_at', $id
                    from
                    (
                        select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                        from data$tahun_avg_x.fi a 
                        where a.bulan in ($bulan_avg_a) and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                        group by concat(a.kode_comp, a.nocab)
                        union all 
                        select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                        from data$tahun_avg_x.ri a 
                        where a.bulan in ($bulan_avg_a) and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                        group by concat(a.kode_comp, a.nocab)
                    )a group by a.site_code
                ";
                return $this->db->query($query);
                // echo "<pre>";
                // print_r($query);
                // echo "</pre>";
            }elseif ($divisi == 'ph all' || $divisi == 'ph candy' || $divisi == 'ph herbal') {

                $get_master_type = $this->get_master_type('PH');

                $kode_type = '';
                foreach ($get_master_type->result() as $a) {
                    $kode_type.= ","."'".$a->kode_type."'";
                }
                $params_kode_type = preg_replace('/,/', '', $kode_type,1);

                $query = "
                    insert into site.dashboard_temp_sales_mundur
                    select '', $params_tahun as tahun, $params_bulan as bulan, a.site_code, '$divisi' as nama_divisi, sum(a.total)/6 as avg_value, sum(a.ot)/6 as avg_ot, '$created_at', $id
                    from
                    (
                        select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                        from data$tahun_avg_x.fi a 
                        where a.bulan in ($bulan_avg_a) and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                        group by concat(a.kode_comp, a.nocab)
                        union all 
                        select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as total, null
                        from data$tahun_avg_x.ri a 
                        where a.bulan in ($bulan_avg_a) and concat(a.kode_comp, a.nocab) in ($params_site_code) and a.kodeprod in ($params_kodeprod) and a.kode_type in ($params_kode_type)
                        group by concat(a.kode_comp, a.nocab)
                    )a group by a.site_code
                ";
                return $this->db->query($query);
                // echo "<pre>";
                // print_r($query);
                // echo "</pre>";
            }


        }else{



        }

        
    }

    public function generate_report_sales($tahun, $bulan){

        $created_at = $this->model_outlet_transaksi->timezone();
        $id = $this->session->userdata('id');

        $query = "
        insert into site.dashboard_report_sales
        select 	'', a.site_code, a.divisi, a.tahun, a.bulan, 
                a.target_principal, a.target_value_poh, b.total_value as realisasi_poh, (b.total_value + a.target_value_poh) / a.target_principal as ach_poh,
                a.target_value_be, b.total_value as realisasi_be, (b.total_value / a.target_value_be) as ach_be,
                a.target_ot_kpi, b.total_ot as realisasi_ot, (b.total_ot / a.target_ot_kpi) as ach_ot,
                c.avg_ot, b.total_ot, (b.total_ot / c.avg_ot) as ach_ot_mundur,
                '$created_at', $id
        from site.dashboard_master_target a LEFT JOIN (
            select a.tahun, a.bulan, a.nama_divisi, a.site_code, a.total_value, a.total_ot
            from site.dashboard_temp_sales a 
            where a.tahun = $tahun and a.bulan = $bulan
        )b on a.site_code = b.site_code and a.divisi = b.nama_divisi LEFT JOIN (
            select a.tahun, a.bulan, a.nama_divisi, a.site_code, a.avg_value, a.avg_ot
            from site.dashboard_temp_sales_mundur a 
            where a.tahun = $tahun and a.bulan = $bulan
        )c on a.site_code = c.site_code and a.divisi = c.nama_divisi
        where a.tahun = $tahun and a.bulan = $bulan
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function generate_report_sales_breakdown_userid($tahun, $bulan){

        $created_at = $this->model_outlet_transaksi->timezone();
        $id = $this->session->userdata('id');

        $query = "
        insert into site.dashboard_report_sales
        select 	'', e.username, a.divisi, a.tahun, a.bulan, 
                sum(a.target_principal), sum(a.target_value_poh), sum(b.total_value) as realisasi_poh,
				(sum(b.total_value) + sum(a.target_value_poh)) / sum(a.target_principal) as ach_poh,
                sum(a.target_value_be), sum(b.total_value) as realisasi_be, (sum(b.total_value) / sum(a.target_value_be)) as ach_be,
                sum(a.target_ot_kpi), sum(b.total_ot) as realisasi_ot, (sum(b.total_ot) / sum(a.target_ot_kpi)) as ach_ot,
                sum(c.avg_ot), sum(b.total_ot), (sum(b.total_ot) / sum(c.avg_ot)) as ach_ot_mundur,
                '$created_at', $id
        from site.dashboard_master_target a LEFT JOIN (
            select a.tahun, a.bulan, a.nama_divisi, a.site_code, a.total_value, a.total_ot
            from site.dashboard_temp_sales a 
            where a.tahun = $tahun and a.bulan = $bulan
        )b on a.site_code = b.site_code and a.divisi = b.nama_divisi LEFT JOIN (
            select a.tahun, a.bulan, a.nama_divisi, a.site_code, a.avg_value, a.avg_ot
            from site.dashboard_temp_sales_mundur a 
            where a.tahun = $tahun and a.bulan = $bulan
        )c on a.site_code = c.site_code and a.divisi = c.nama_divisi INNER JOIN (
            select a.site_code, a.userid
            from site.dashboard_mapping_user a
            where a.deleted_at is null and a.userid = $id 
        )d on a.site_code = d.site_code LEFT JOIN (
            select a.id, a.username, a.email
            from mpm.user a 
        )e on d.userid = e.id
        where a.tahun = $tahun and a.bulan = $bulan
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function delete_temp_sales($tahun, $bulan){
        $query = "delete from site.dashboard_temp_sales where tahun = $tahun and bulan = $bulan";
        return $this->db->query($query);
    }

    public function delete_temp_sales_mundur($tahun, $bulan){
        $query = "delete from site.dashboard_temp_sales_mundur where tahun = $tahun and bulan = $bulan";
        return $this->db->query($query);
    }

    public function delete_report_sales($tahun, $bulan){
        $query = "delete from site.dashboard_report_sales where tahun = $tahun and bulan = $bulan";
        return $this->db->query($query);
    }

    public function get_dashboard_report_sales($tahun, $bulan){

        if ($tahun == "" && $bulan == "") {
            $params_periode = "";
        }else{
            $params_periode = "where a.tahun = $tahun and a.bulan = $bulan";
        }

        $query = "
            select a.*, b.*, c.*
            from site.dashboard_report_sales a left join (
                select 	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from 	mpm.tbl_tabcomp a 
                where 	a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code left join (
                select a.id, a.username, a.email
                from mpm.user a 
            )c on a.updated_by = c.id
            $params_periode
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

}