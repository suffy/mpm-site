<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_mti extends CI_Model 
{
    public function get_summary(){
        
    }

    // public function get_kodeprod_by_group($group = '')
    // {
    //     if ($group == '') {
    //         $where = "where supp <>'BSP'";
    //     } else {
    //         $where = "where a.grup = '$group' and supp <> 'BSP'";
    //     }

    //     $sql_kodeprod = "
    //         select kodeprod
    //         from mpm.tabprod a
    //         $where
    //     ";

    //     return $this->db->query($sql_kodeprod);

    //     $proses_kodeprod = $this->db->query($sql_kodeprod)->result_array();
    //     foreach ($proses_kodeprod as $a) {
    //         $kodeprodx[] = $a['kodeprod'];
    //     }

    //     $kodeprod = implode(', ', $kodeprodx);
    //     return $kodeprod;
    // }

    // public function get_kode_type_by_sektor($sektor)
    // {
    //     if ($sektor == '') {
    //         $where = "";
    //     } else {
    //         $where = "where a.sektor = '$sektor'";
    //     }

    //     $sql_kode_type = "
    //         select a.kode_type
    //         from mpm.tbl_bantu_type a
    //         $where
    //     ";

    //     $proses_kode_type = $this->db->query($sql_kode_type)->result_array();
    //     foreach ($proses_kode_type as $a) {
    //         $kode_typex[] = "'" . $a['kode_type'] . "'";
    //     }

    //     $kode_type = implode(", ", $kode_typex);
    //     echo "kode type: " . $kode_type;
    //     return $kode_type;
    // }

    public function get_kode_type_by_segment($segment)
    {
        if ($segment == '') {
            $where = "";
        } else {
            $where = "where a.segment = '$segment'";
        }

        $sql_kode_type = "
            select a.kode_type
            from mpm.tbl_bantu_type a
            $where
        ";

        $proses_kode_type = $this->db->query($sql_kode_type)->result_array();
        foreach ($proses_kode_type as $a) {
            $kode_typex[] = "'" . $a['kode_type'] . "'";
        }

        $kode_type = implode(", ", $kode_typex);
        return $kode_type;
    }   

    public function get_kodeprod_by_group_exception($group, $exception)
    {
        if ($group == '') {
            $where = "where supp <>'BSP'";
        } else {
            $where = "where a.grup = '$group' and supp <> 'BSP'";
        }

        $sql_kodeprod = "
            select kodeprod
            from mpm.tabprod a
            $where and a.kodeprod not in ($exception)
        ";

        // echo "<pre>";
        // print_r($sql_kodeprod);
        // echo "</pre>";

        // die;

        $proses_kodeprod = $this->db->query($sql_kodeprod)->result_array();
        foreach ($proses_kodeprod as $a) {
            $kodeprodx[] = $a['kodeprod'];
        }

        $kodeprod = implode(', ', $kodeprodx);
        return $kodeprod;
    }

    public function get_kodeprod_by_supp($supp = '')
    {
        if ($supp == '') {
            $where = "where supp <>'BSP'";
        } else {
            $where = "where a.supp = '$supp'";
        }

        $sql_kodeprod = "
            select kodeprod
            from mpm.tabprod a
            $where
        ";

        // echo "<pre>";
        // print_r($sql_kodeprod);
        // echo "</pre>";

        // die;

        $proses_kodeprod = $this->db->query($sql_kodeprod)->result_array();
        foreach ($proses_kodeprod as $a) {
            $kodeprodx[] = $a['kodeprod'];
        }

        $kodeprod = implode(', ', $kodeprodx);
        echo "kodeprod : " . $kodeprod;
        return $kodeprod;
    }

    public function get_mti()
    {
        $query = "
            select * from site.dashboard_mti a where a.created_at = (select max(a.created_at) from site.dashboard_mti a)
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_mti_breakdown_site_code($divisi)
    {
        $query = "
            select * 
            from site.dashboard_mti_breakdown_site_code a 
            where a.divisi = '$divisi' and a.created_at = (select max(b.created_at) from site.dashboard_mti_breakdown_site_code b where b.divisi = '$divisi')
        ";
        $proses = $this->db->query($query);
        return $proses;
    }

    public function update_data_mti($data){
        $tahun_saat_ini = date('Y');
        $bulan_saat_ini = date('m');
        // $tahun_saat_ini = 2023;
        // $bulan_saat_ini = 05;
        $herbal = $data['herbal'];
        $candy = $data['candy'];
        $kode_type = $data['kode_type'];
        $kode_type_ph = $data['kode_type_ph'];
        $all_principal = $data['all_principal'];
        $created_at = $this->model_outlet_transaksi->timezone();

        // echo "kode_type : ".$kode_type;
        // echo "<br>";
        // echo "kode_type_ph : ".$kode_type_ph;

        // die;

        $truncate = $this->db->query('truncate site.dashboard_mti');

        $query = "
            insert into site.dashboard_mti
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI', 'D1' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal)
            )a
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        $proses = $this->db->query($query);

        $query2 = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI',  'D2 (exclude rtd)' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($candy)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($candy)
            )a
        ";

        echo "<pre>";
        print_r($query2);
        echo "</pre>";

        $proses = $this->db->query($query2);

        $query3 = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI', 'RTD' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in (010121) and a.kodeprod in ($herbal, $candy, '010121')
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in (010121) and a.kodeprod in ($herbal, $candy, '010121')
            )a
        ";

        echo "<pre>";
        print_r($query3);
        echo "</pre>";

        $proses = $this->db->query($query3);

        $query4 = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI', 'TOTAL' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal, $candy, '010121')
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal, $candy, '010121')
            )a
        ";

        echo "<pre>";
        print_r($query4);
        echo "</pre>";

        $proses = $this->db->query($query4);

        $query_deltomed = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI', 'ALL-PRINCIPAL' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($all_principal)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($all_principal)
            )a
        ";

        echo "<pre>";
        print_r($query_deltomed);
        echo "</pre>";

        $proses = $this->db->query($query_deltomed);

        $query_d1_ph = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'PH', 'D1' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($herbal)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($herbal)
            )a
        ";

        echo "<pre>";
        print_r($query_d1_ph);
        echo "</pre>";

        $proses = $this->db->query($query_d1_ph);

        $query_d2_ph = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'PH',  'D2 (exclude rtd)' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($candy)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($candy)
            )a
        ";

        echo "<pre>";
        print_r($query_d2_ph);
        echo "</pre>";

        $proses = $this->db->query($query_d2_ph);

        $query_rtd_ph = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'PH', 'RTD' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in (010121) and a.kodeprod in ($herbal, $candy, '010121')
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in (010121) and a.kodeprod in ($herbal, $candy, '010121')
            )a
        ";

        echo "<pre>";
        print_r($query_rtd_ph);
        echo "</pre>";

        $proses = $this->db->query($query_rtd_ph);

        $query_total_ph = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'PH', 'TOTAL' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($herbal, $candy, '010121')
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($herbal, $candy, '010121')
            )a
        ";

        echo "<pre>";
        print_r($query_total_ph);
        echo "</pre>";

        $proses = $this->db->query($query_total_ph);

    

        return $proses;

    }

    public function get_data($bulan, $kodeprod, $kode_type)
    {   
        $tahun = substr($bulan, 0, 4);
        $bulan = substr($bulan, 5, 2);

        if ($bulan && $tahun && $kodeprod && $kode_type) {
            // echo "aaa";
            // die;
            $query = "
                select sum(a.total_unit) as total_unit, sum(a.total_value) as total_value
                from (
                    select sum(a.banyak) as total_unit, sum(a.tot1) as total_value
                    from data$tahun.fi a 
                    where a.kodeprod in ($kodeprod) and a.kode_type in ($kode_type) and a.bulan = $bulan
                    union all 
                    select sum(a.banyak) as total_unit, sum(a.tot1) as total_value
                    from data$tahun.ri a 
                    where a.kodeprod in ($kodeprod) and a.kode_type in ($kode_type) and a.bulan = $bulan
                )a
            ";
        }else{
            $query = "
                select 0 as total_unit, 0 as total_value
            ";
        }
        
            return $this->db->query($query);

    }

    public function get_kodeprod_by_group($group = '', $supp = '')
    {
        if ($group == '') {
            $params_group = "";
        } else {
            $params_group = "and a.grup = '$group'";
        }

        if($supp == '') {
            $params_supp = "";
        }else{
            $params_supp = "and a.supp = '$supp'";
        }

        $query = "
            select kodeprod
            from mpm.tabprod a
            where a.supp <> 'bsp' $params_group $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_kode_type_by_sektor($sektor = '')
    {
        if ($sektor == '') {
            $params = "";
        } else {
            $params = "where a.sektor = '$sektor'";
        }

        $query = "
            select a.kode_type, a.nama_type, a.sektor, a.segment
            from mpm.tbl_bantu_type a
            $params
        ";      

        // print_r($query);

        return $this->db->query($query);
    }

}