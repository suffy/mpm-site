<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_office extends CI_Model 
{
    public function get_kalender_data(){    
        $query = "select * from management_office.kalender_data";
        return $this->db->query($query);
    }

    public function monitoring_count(){
        $query = "select * from management_office.monitoring_count a where a.created_at = (select max(b.created_at) from management_office.monitoring_count b)";
        return $this->db->query($query);
    }

    public function monitoring_kam(){
        $query = "
            select *
            from site.dashboard_mti a
        ";
        return $this->db->query($query);
    }

    public function update_data_kam($data){
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

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

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

        // echo "<pre>";
        // print_r($query2);
        // echo "</pre>";

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

        // echo "<pre>";
        // print_r($query3);
        // echo "</pre>";

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

        // echo "<pre>";
        // print_r($query4);
        // echo "</pre>";

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

        // echo "<pre>";
        // print_r($query_deltomed);
        // echo "</pre>";

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

        // echo "<pre>";
        // print_r($query_d1_ph);
        // echo "</pre>";

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

        // echo "<pre>";
        // print_r($query_d2_ph);
        // echo "</pre>";

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

        // echo "<pre>";
        // print_r($query_rtd_ph);
        // echo "</pre>";

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

        // echo "<pre>";
        // print_r($query_total_ph);
        // echo "</pre>";

        $proses = $this->db->query($query_total_ph);

        return true;

        

        // $query5 = "
        //     insert into site.dashboard_mti_breakdown_site_code  
        //     select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'herbal' as divisi, a.site_code, b.branch_name, b.nama_comp, a.omzet, a.unit, '$created_at' as created_at
        //     from 
        //     (
        //         select site_code, sum(a.omzet) as omzet, sum(a.unit) as unit
        //         from 
        //         (
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.fi a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal)
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //             union all 
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.ri a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal)
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //         )a GROUP BY site_code
        //     )a left join (
        //         select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan
        //         from mpm.tbl_tabcomp a 
        //         where a.`status` = 1
        //         GROUP BY concat(a.kode_comp, a.nocab)
        //     )b on a.site_code = b.site_code
        //     ORDER BY b.urutan
        // ";

        // echo "<pre>";
        // print_r($query5);
        // echo "</pre>";

        // $proses = $this->db->query($query5);

        // $query6 = "
        //     insert into site.dashboard_mti_breakdown_site_code  
        //     select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'candy' as divisi, a.site_code, b.branch_name, b.nama_comp, a.omzet, a.unit, '$created_at' as created_at
        //     from 
        //     (
        //         select site_code, sum(a.omzet) as omzet, sum(a.unit) as unit
        //         from 
        //         (
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.fi a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($candy)
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //             union all 
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.ri a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($candy)
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //         )a GROUP BY site_code
        //     )a left join (
        //         select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan
        //         from mpm.tbl_tabcomp a 
        //         where a.`status` = 1
        //         GROUP BY concat(a.kode_comp, a.nocab)
        //     )b on a.site_code = b.site_code
        //     ORDER BY b.urutan
        // ";

        // echo "<pre>";
        // print_r($query6);
        // echo "</pre>";

        // $proses = $this->db->query($query6);

        // $query6 = "
        //     insert into site.dashboard_mti_breakdown_site_code  
        //     select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'RTD' as divisi, a.site_code, b.branch_name, b.nama_comp, a.omzet, a.unit, '$created_at' as created_at
        //     from 
        //     (
        //         select site_code, sum(a.omzet) as omzet, sum(a.unit) as unit
        //         from 
        //         (
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.fi a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ('010121')
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //             union all 
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.ri a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ('010121')
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //         )a GROUP BY site_code
        //     )a left join (
        //         select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan
        //         from mpm.tbl_tabcomp a 
        //         where a.`status` = 1
        //         GROUP BY concat(a.kode_comp, a.nocab)
        //     )b on a.site_code = b.site_code
        //     ORDER BY b.urutan
        // ";

        // echo "<pre>";
        // print_r($query6);
        // echo "</pre>";

        // $proses = $this->db->query($query6);

        
        

        // return $proses;

    }

    public function monitoring_sell_out(){
        $query = "
            select *
            from site.temp_sell_out a
        ";
        return $this->db->query($query);
    }

    public function monitoring_sell_out_deltomed_segment(){
        $query = "
            select *
            from site.temp_soprod_deltomed_segment a
        ";
        return $this->db->query($query);
    }

    public function get_temp_chart_get_omzet_by_bulan($tahun, $bulan, $userid, $created_at)
    {
        $query = "
            select *
            from site.temp_chart_get_omzet_by_bulan a
            where a.tahun = $tahun and a.bulan = $bulan and a.created_by = $userid and a.created_at = '$created_at'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_omzet_by_bulan($tahun, $bulan, $site_code, $kodeprod, $created_by, $created_at)
    {
        $query = "
            insert into site.temp_chart_get_omzet_by_bulan
            select a.site_code, a.branch_name, a.nama_comp, sum(a.value) as omzet, $tahun, $bulan, '$created_at', $created_by
            from site.master_firi_v1 a 
            where a.tahun = $tahun and a.bulan = $bulan and a.site_code in ($site_code) and a.kodeprod in ($kodeprod)
            GROUP BY a.site_code
            ORDER BY sum(a.value)  desc
            limit 20
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

  public function get_kalender_by_bulan($tahun, $bulan, $site_code)
  {
    $query = "
        SELECT  SUBSTR(a.filename, 3, 2) AS nocab, a.tahun, a.bulan, a.userid, c.username, a.omzet, 
                a.lastupload, a.status_closing, d.branch_name, d.nama_comp, a.tanggal, 
                if(d.stok_valid = 1 and d.sds_murni = 1, a.tanggal, e.lastupload_stock) as tanggal_stok, 
                a.filename, d.region
        FROM mpm.upload a
        INNER JOIN (
            SELECT userid, MAX(id) as max_id
            FROM mpm.upload
            WHERE tahun = $tahun AND bulan = $bulan
            GROUP BY userid
        ) b ON a.userid = b.userid AND a.id = b.max_id 
        LEFT JOIN (
            SELECT a.id, a.username
            FROM site.master_user a
        ) c ON a.userid = c.id 
        INNER JOIN (
            SELECT a.site_code, a.branch_name, a.nama_comp, a.stok_valid, a.sds_murni, a.region
            FROM site.master_site a
            where a.site_code in ($site_code)       
        ) d ON c.username = LEFT(d.site_code, 3)
        LEFT JOIN (
            SELECT b.site_code, a.total_stock_on_pcs, a.bulan, 
            IF(a.bulan = MONTH(b.lastupload), DATE_FORMAT(a.created_at, '%d'), DAY(LAST_DAY(CURDATE() - INTERVAL 1 MONTH))) AS lastupload_stock
            FROM site.stock_history_import a
            INNER JOIN (
                SELECT site_code, MAX(created_at) AS lastupload
                FROM site.stock_history_import
                WHERE tahun = $tahun AND bulan = $bulan
                GROUP BY site_code
            ) b ON a.site_code = b.site_code AND a.created_at = b.lastupload
            WHERE a.tahun = $tahun AND a.bulan = $bulan
        ) e ON d.site_code = e.site_code
        WHERE a.tahun = $tahun AND a.bulan = $bulan 
        ORDER BY d.branch_name = 'JAVAS KARYA TRIPTA' desc, 
        d.branch_name = 'JAYA BAKTI RAHARJA' desc,
        d.branch_name = 'JAVAS TRIPTA GEMALA' desc,
        d.branch_name = 'SOLO' desc,
        d.branch_name = 'PT. DUTA INTRA YASA' desc,
        d.branch_name = 'PT.JAVAS TRIPTA MANDALA/JTM' desc,
        d.branch_name = 'JAVAS TRIPTA SEJAHTERA' desc,
        d.branch_name = 'JAVAS BALI LESTARI' desc,
        d.region = 'SUMATERA' desc,
        d.nama_comp;
    ";

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";die;
    return $this->db->query($query);
  }

    public function get_temp_chart_get_omzet_by_produk_bulan($tahun, $bulan, $userid, $created_at)
    {
        $query = "
            select *
            from site.temp_chart_get_omzet_by_produk_bulan a
            where a.tahun = $tahun and a.bulan = $bulan and a.created_by = $userid and a.created_at = '$created_at'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_omzet_by_produk_bulan($tahun, $bulan, $site_code, $kodeprod, $created_by, $created_at)
    {
        $query = "
            insert into site.temp_chart_get_omzet_by_produk_bulan
            select a.kodeprod, a.namaprod, sum(a.value) as omzet, $tahun, $bulan, '$created_at', $created_by
            from site.master_firi_v1 a 
            where a.tahun = $tahun and a.bulan = $bulan and a.site_code in ($site_code) and a.kodeprod in ($kodeprod)
            GROUP BY a.kodeprod
            ORDER BY sum(a.value)  desc
            limit 7
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_temp_chart_get_omzet_by_kode_type($tahun, $bulan, $userid, $created_at)
    {
        $query = "
            select *
            from site.temp_chart_get_omzet_by_kode_type a
            where a.tahun = $tahun and a.bulan = $bulan and a.created_by = $userid and a.created_at = '$created_at'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_omzet_by_kode_type($tahun, $bulan, $site_code, $kodeprod, $created_by, $created_at)
    {
        $query = "
            insert into site.temp_chart_get_omzet_by_kode_type
            select a.kode_type, a.nama_type, a.sektor, a.segment, sum(a.value) as omzet, $tahun, $bulan, '$created_at', $created_by
            from site.master_firi_v1_tipe_class a 
            where a.tahun = $tahun and a.bulan = $bulan and a.site_code in ($site_code) and a.kodeprod in ($kodeprod)
            GROUP BY a.kode_type
            ORDER BY sum(a.value) desc
            limit 10
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_temp_chart_get_omzet_by_segment($tahun, $bulan, $userid, $created_at)
    {
        $query = "
            select *
            from site.temp_chart_get_omzet_by_segment a
            where a.tahun = $tahun and a.bulan = $bulan and a.created_by = $userid and a.created_at = '$created_at'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_omzet_by_segment($tahun, $bulan, $site_code, $kodeprod, $created_by, $created_at)
    {
        $query = "
            insert into site.temp_chart_get_omzet_by_segment
            select a.segment, sum(a.value) as omzet, $tahun, $bulan, '$created_at', $created_by
            from site.master_firi_v1_tipe_class a 
            where a.tahun = $tahun and a.bulan = $bulan and a.site_code in ($site_code) and a.kodeprod in ($kodeprod)
            GROUP BY a.segment
            ORDER BY sum(a.value) desc
            limit 3
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_temp_chart_get_omzet_by_tahun($tahun, $userid, $created_at)
    {
        $query = "
            select *
            from site.temp_chart_get_omzet_by_tahun a
            where a.tahun = $tahun and a.created_by = $userid and a.created_at = '$created_at'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_omzet_by_tahun($tahun, $site_code, $kodeprod, $created_by, $created_at)
    {
        $query = "
        insert into site.temp_chart_get_omzet_by_tahun
             select if(a.bulan = 1, 'jan',
                    If(a.bulan = 2, 'feb',
                    If(a.bulan = 3, 'mar',
                    IF(a.bulan = 4, 'apr',
                    IF(a.bulan = 5, 'mei',
                    IF(a.bulan = 6, 'jun',
                    IF(a.bulan = 7, 'jul',
                    IF(a.bulan = 8, 'agu',
                    IF(a.bulan = 9, 'sep',
                    IF(a.bulan = 10, 'okt',
                    IF(a.bulan = 11, 'nov',
                    IF(a.bulan = 12, 'des',
                    a.bulan)))))))))))) as bulan,
                    sum(a.`value`) as omzet, $tahun, '$created_at', $created_by
            from site.master_firi_v1 a 
            where a.tahun = $tahun and a.site_code in ($site_code) and a.kodeprod in ($kodeprod)
            GROUP BY a.bulan
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }
    
    public function get_master_team_apps($userid)
    {
        $query = "
            select *
            from site.master_team_apps a 
            where a.userid = $userid
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_data_doi($site_code, $kodeprod)
    {
        $query = "
            select a.*
            from site.dashboard_doi a 
            where a.site_code in ($site_code) and a.kodeprod in ($kodeprod)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function total_omzet($site_code, $kodeprod, $tahun, $bulan)
    {
        $query = "
            select sum(a.omzet) as total_omzet 
            from 
            (
                select sum(a.tot1) as omzet
                from data$tahun.fi a 
                where concat(a.kode_comp, a.nocab) in ($site_code) and a.kodeprod in ($kodeprod) and a.bulan in ($bulan)
                union all 
                select sum(a.tot1) as omzet
                from data$tahun.ri a 
                where concat(a.kode_comp, a.nocab) in ($site_code) and a.kodeprod in ($kodeprod) and a.bulan in ($bulan)
            )a
        ";
        return $this->db->query($query);
    }

    public function total_po($site_code, $tahun, $bulan)
    {
        $query = "
            select sum(a.total_value) as total_po
            from mpm.po a 
            where year(a.tglpo) = $tahun and month(a.tglpo) = $bulan and a.kode_alamat in ($site_code) and a.deleted = 0
        ";
        return $this->db->query($query);
    }

    public function summary_doi($site_code, $kodeprod)
    {
        $supp = $this->session->userdata('supp');

        if($supp != '000')
        {
            return false;
        }
        
        // $query = "
        //     select round(avg(a.doi_unit)) as avg_doi, min(CAST(a.doi_unit as integer)) as min_doi,
        //     max(CAST(a.doi_unit as integer)) as max_doi
        //     from site.dashboard_doi a left join (
        //         select a.supp, a.namasupp
        //         from site.master_supplier a
        //     )b on a.supp = b.supp
        //     where a.site_code in ($site_code) and a.kodeprod in ($kodeprod)
        // ";
        $query = "
            select a.supp, a.namasupp, sum(a.stock_in_value) / sum(a.avg_value) * 30 as doi
            from site.dashboard_doi a left join (
                select a.supp, a.namasupp
                from site.master_supplier a
            )b on a.supp = b.supp
            where a.site_code in ($site_code) and a.kodeprod in ($kodeprod)
            group by a.supp
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function generate_analisa_piutang($tanggal, $kodelang)
    {
        // echo "tanggal : ".$tanggal;
        // echo "kode_lang : ".$kodelang;

        if(!$kodelang)
        {
            return false;
        }

        $this->load->model('model_spk');
        // cek exist data where tanggal = current
        $get_analisa_piutang_temp = $this->model_spk->get_analisa_piutang_temp($tanggal);
        if ($get_analisa_piutang_temp->num_rows() > 0) {
            // echo "aaa";die;
            $get_data = $this->model_spk->get_analisa_piutang($kodelang);
        }else{
            $conn = $this->model_spk->cek_koneksi_sql_server();    
            if ($conn) {
                // echo 'a';die;
                $get_analisa_piutang_sql_server = $this->model_spk->get_analisa_piutang_sql_server($tanggal, $conn);
                if ($get_analisa_piutang_sql_server) {
                    $this->model_spk->insert_analisa_piutang($get_analisa_piutang_sql_server, $tanggal);
                }
                
                $get_data = $this->model_spk->get_analisa_piutang($kodelang);

            }else{
                echo 'Koneksi SQL Server Gagal, Sehingga data yang disajikan mungkin tidak update';

                $this->session->set_flashdata('pesan', 'Koneksi SQL Server Gagal, Sehingga data yang disajikan mungkin tidak update');
                $get_data = '';
            }   
        }
    }

    public function get_analisa_piutang($kodelang)
    {
        $supp = $this->session->userdata('supp');

        if($supp != '000')
        {
            return false;
        }

        if ($kodelang) {
            $params = "where a.customerid in ($kodelang)";
            $query = "
                select  sum(saldo) as total, count(distinct(a.group_descr)) as count
                from site.analisa_piutang_temp a 
                $params
            ";
            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        } else {
            return false;
        }

        // $query = "
        //     select  sum(saldo) as total, count(distinct(a.group_descr)) as count
        //     from site.analisa_piutang_temp a 
        //     $params
        // ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_kodelang_by_site_code($site_code)
    {
        $query = "
            select *
            from site.master_site_with_user a
            where a.site_code in ($site_code)
            group by a.kode_lang
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }
 
    public function get_master_company($site_code)
    {
        $query = "
            select 
            a.site_code, 
            a.branch_name, 
            a.nama_comp, 
            a.region, 
            IF(a.sds_murni = 1, 'True', 'False') AS sds_murni, 
            IF(a.sds_bridging = 1, 'True', 'False') AS sds_bridging, 
            IF(a.excel = 1, 'True', 'False') AS excel, 
            IF(a.spot = 1, 'True', 'False') AS spot, 
            IF(a.bridging_sales = 1, 'True', 'False') AS bridging_sales, 
            IF(a.bridging_stok = 1, 'True', 'False') AS bridging_stok, 
            IF(a.stok_valid = 1, 'True', 'False') AS stok_valid
            from site.master_site a
            where a.site_code in ($site_code)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_konfirmasi_informasi($data)
    {
        $query= "
            select *
            from site.konfirmasi_informasi a
            where a.userid = $data
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function insert_konfirmasi_informasi($data)
    {
        $this->db->insert('site.konfirmasi_informasi', $data);
        return $this->db->insert_id();
    }

    public function get_pending_biop($userid = null)
    {

        if($userid)
        {
            $params = "and a.pic_on_duty = $userid";
        }else{
            $params = "";
        }

        $query = "
            select	a.`status`, a.nama_status,
                    count(*) as count, a.pic_on_duty,
                    u.username as nama_user,
                    max(IF(a.nama_status = 'pending admin biop', datediff(curdate(), a.updated_at),
                    IF(a.nama_status = 'pending atasan 1', datediff(curdate(), a.admin_claim_at),
                    IF(a.nama_status = 'pending atasan 2', datediff(curdate(), a.atasan1_at),
                    IF(a.nama_status = 'pending admin finance', datediff(curdate(), a.atasan2_at),
                    IF(a.nama_status = 'pending head finance',datediff(curdate(), a.admin_finance_at),
                    NULL)))))) as max_pending_days,
                    round(avg(
                    IF(a.nama_status = 'pending admin biop', datediff(curdate(), a.updated_at),
                    IF(a.nama_status = 'pending atasan 1', datediff(curdate(), a.admin_claim_at),
                    IF(a.nama_status = 'pending atasan 2', datediff(curdate(), a.atasan1_at),
                    IF(a.nama_status = 'pending admin finance', datediff(curdate(), a.atasan2_at),
                    IF(a.nama_status = 'pending head finance', datediff(curdate(), a.admin_finance_at),
                    NULL)))))), 1) AS avg_pending_days
            from site.biop_header a left join mpm.user u
                on a.pic_on_duty = u.id
            where a.deleted_at is null and a.status not in (1,2,7) $params
            group by a.nama_status, u.username
            order by a.nama_status, count desc
        ";
        // echo "<pre> "; print_r($query); echo "</pre>";
        // die;
        return $this->db->query($query);
    }

  public function get_karyawan_by_username($username)
  {
    $query = "
      select *
      from site.karyawan a 
      where a.username_web = '$username'
    ";
    // echo "<pre>$query</pre>";
    // die;
    return $this->db->query($query);
  }

  public function get_team_member($userid)
  {
    $query = "
      select a.userid_pelaksana, b.username, b.active, b.id as userid
      from management_rpd.m_karyawan a inner join site.master_user b 
      on a.userid_pelaksana = b.id
      where 	(a.userid_pelaksana = $userid or a.userid_verifikasi1 = $userid or a.userid_verifikasi2 = $userid or
      a.userid_verifikasi3 = $userid) and b.active = 1
    ";
    return $this->db->query($query);
  }

  public function get_absensi_by_userid_and_date($userid, $tanggal)
  {
    $query = "
      select a.userid, a.tanggal, a.actual_masuk, a.actual_keluar, a.flag_terlambat, b.username
      from site.absensi_transaksi a inner join site.master_user b 
        on a.userid  = b.id
      where a.userid in ($userid) and a.tanggal = '$tanggal' and b.active = 1
    ";
    // echo "<pre>$query</pre>";
    return $this->db->query($query);
  }

  public function get_activity_by_username_and_date($username, $tanggal)
  {
    $query = "
      select a.username, 'market_visit' as type, a.district, a.result
      from dbrest.mpm_market_visit a 
      where a.username in ($username) and date(a.created_at) = '$tanggal'
      union all 
      select a.username, a.type, a.district, a.result
      from dbrest.mpm_activity a 
      where date(a.created_at) = '$tanggal' and a.username in ($username) 
    ";
    return $this->db->query($query);
  }

  public function get_absensi_join_activity($userid, $username, $tanggal)
  {
    $query = "
      select a.userid, a.tanggal, a.actual_masuk, a.actual_keluar, a.flag_terlambat, b.username, c.count, b.id,      
            d.address as address_masuk, d.latitude as latitude_masuk, d.longitude as longitude_masuk, 
            concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', d.image) as image_masuk, 
            e.address as address_keluar, e.latitude as latitude_keluar, e.longitude as longitude_keluar,
            concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', e.image) as image_keluar
      from site.absensi_transaksi a inner join site.master_user b 
        on a.userid  = b.id left join
        (
          select a.username, sum(count) as count
          from 
          (
            select a.username, 'market_visit' as type, a.district, a.result, count(*) as count
            from dbrest.mpm_market_visit a 
            where a.username in ($username) and date(a.created_at) = '$tanggal'
            group by a.username
            union all 
            select a.username, a.type, a.district, a.result, count(*) as count
            from dbrest.mpm_activity a 
            where date(a.created_at) = '$tanggal' and a.username in ($username) 
            group by a.username
          )a group by a.username
        )c on b.username = c.username left join (
          select a.username, a.image, a.latitude, a.longitude, a.address, a.created_at,
          date_format(a.created_at, '%H:%i:%s') as jam
          from dbrest.absensi_log a 
          where date(a.created_at) = '$tanggal' and a.username in ($username) 
        )d on b.username = d.username and a.actual_masuk = d.jam left join (
          select a.username, a.image, a.latitude, a.longitude, a.address, a.created_at,
          date_format(a.created_at, '%H:%i:%s') as jam
          from dbrest.absensi_log a 
          where date(a.created_at) = '$tanggal' and a.username in ($username) 
        )e on b.username = e.username and a.actual_keluar = e.jam
      where a.userid in ($userid) and 
      a.tanggal = '$tanggal' and 
      b.active = 1
    ";
    // echo "<pre>$query</pre>";
    return $this->db->query($query);
  }

  public function get_absensi_join_activity_from_to($userid, $username, $from, $to)
  {
    $query = "
      select a.userid, a.tanggal, a.actual_masuk, a.actual_keluar, a.flag_terlambat, b.username, c.count, b.id, a.status_hari, a.keterangan
      from site.absensi_transaksi a inner join site.master_user b 
        on a.userid  = b.id left join
        (
          select a.username, sum(count) as count
          from 
          (
            select a.username, 'market_visit' as type, a.district, a.result, count(*) as count
            from dbrest.mpm_market_visit a 
            where a.username in ($username) and date(a.created_at) between '$from' and '$to'
            group by a.username
            union all 
            select a.username, a.type, a.district, a.result, count(*) as count
            from dbrest.mpm_activity a 
            where a.username in ($username) and date(a.created_at) between '$from' and '$to' 
            group by a.username
          )a group by a.username
        )c on b.username = c.username
      where a.userid in ($userid) and 
      a.tanggal between '$from' and '$to' and 
      b.active = 1 and a.status_hari not in (0)
    ";
    // echo "<pre>$query</pre>";
    return $this->db->query($query);
  }

  public function get_menu_by_userid($userid)
  {
    $query = "
      select a.menu, a.uri
      from site.dashboard_menu a 
      where a.is_active = 1 and a.deleted_at is null and a.userid = $userid
      order by a.menu = 'portal raw data' desc, a.menu = 'analisa piutang' desc,
            a.menu = 'sell out product' desc 
    ";
    // echo "<pre>$query</pre>";
    return $this->db->query($query);
  }

  public function get_summary_activity_by_username_and_from_to($username, $from, $to)
  {
    $query ="
      select a.username, a.type, count(*) as count
      from dbrest.mpm_activity a 
      where a.username in ($username) and date(a.created_at) between '$from' and '$to' 
      group by a.username, a.type
      ORDER BY a.username, count desc
    ";
    return $this->db->query($query);
  }

}