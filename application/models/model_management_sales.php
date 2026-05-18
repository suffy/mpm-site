<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_sales extends CI_Model 
{
    public function __construct() {
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->created_by = $this->session->userdata('id');
    }

    public function get_year()
    {
        $years = range(2020, strftime("%Y", time()));
        return $years;
    }

    public function get_principal($userid)
    {
        $query = "
            select a.supp, b.namasupp
            from site.detail_principal a left join (
                select a.supp, a.namasupp
                from site.master_supplier a 
            )b on a.supp = b.supp
            where a.active = 1 and a.userid = $userid 
        ";
        return $this->db->query($query);
    }

    public function get_principal_by_supp($supp)
    {
        if ($supp == '000') {
            $params_supp = "";
        }else{
            $params_supp = "where a.supp = '$supp'";
        }
        $query = "
            select a.supp, a.namasupp
            from site.master_supplier a 
            $params_supp
        ";
        return $this->db->query($query);
    }

    public function get_master_product($supp)
    {
        $query = "
            select a.kodeprod, a.namaprod, a.grup, a.nama_group, a.subgroup, a.nama_sub_group, a.namasupp
            from site.master_product_with_harga a
            where a.supp in ($supp)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_temp_selloutproduct($format_time)
    {
        $create_table = "
            create table if not exists site.temp_sell_out_product_$format_time(
                site_code varchar(20),
                branch_name varchar(50),
                nama_comp varchar(50),
                region varchar(10),
                kode_lang varchar(20),
                nama_lang varchar(50),
                kode_type varchar(10),
                kodesalur varchar(20),
                kodesales varchar(20),
                kodeprod varchar(20),
                namaprod varchar(50),
                namasupp varchar(50),
                nama_group varchar(50),
                kode_group varchar(20),
                nama_sub_group varchar(50),
                kode_sub_group varchar(20),
                banyak varchar(20),
                tot1 varchar(20),
                bulan varchar(10),
                tgldokjdi varchar(20),
                created_by int(11),
                created_at datetime
            )
        ";
        $create_table = $this->db->query($create_table);

        if ($create_table) {
            $data = [
                'name_table' => "temp_sell_out_product_$format_time",
                'created_at' => $this->created_at,
                'created_by' => $this->created_by,
                'status'    => 1
            ];

            $this->db->insert('site.temp_sell_out_product_log', $data);
        }

        return $create_table;
    }

    public function create_index_temp_selloutproduct($format_time)
    {
        $create_index = "
            create index idx_temp_sell_out_product_$format_time on site.temp_sell_out_product_$format_time(site_code, bulan, kodeprod)
        ";
        $create_index = $this->db->query($create_index);
        return $create_index;
    }


    public function insert_temp_sell_out_product($data, $time)
    {
        $tahun = $data['tahun'];

        $query = "
            insert into site.temp_sell_out_product_$time
            select  a.site_code, b.branch_name, b.nama_comp, b.region, a.kode_lang, a.nama_lang, 
                    a.kode_type, a.kodesalur, a.kodesales, a.kodeprod,
                    c.namaprod, c.namasupp, c.nama_group, c.grup, c.nama_sub_group, c.subgroup,
                    a.banyak, a.tot1, a.bulan, a.tgldokjdi, '$this->created_by', '$time'
            from 
            (
                select 	concat(a.kode_comp, a.nocab) as site_code, 
                        concat(a.kode_comp, a.kode_lang) as kode_lang, a.nama_lang,
                        a.kode_type, a.kodesalur, a.kodesales, a.bulan, a.kodeprod, a.banyak, a.tot1, a.tgldokjdi
                from data$tahun.fi a 
                union all 
                select 	concat(a.kode_comp, a.nocab) as site_code, null as kode_lang, null as nama_lang,
                        a.kode_type, a.kodesalur, a.kodesales, a.bulan, a.kodeprod, a.banyak, a.tot1, a.tgldokjdi
                from data$tahun.ri a 
            )a left join (
                select a.site_code, a.branch_name, a.nama_comp, a.region
                from site.master_site a 
            )b on a.site_code = b.site_code left join (
                select a.kodeprod, a.namaprod, a.namasupp, a.nama_group, a.grup, a.nama_sub_group, a.subgroup
                from site.master_product_with_harga a 
            )c on a.kodeprod = c.kodeprod
        ";
        return $this->db->query($query);
    }

    public function get_log_sell_out_product()
    {
        $query = "
            select a.id, a.name_table, a.created_at, a.status_using
            from site.temp_sell_out_product_log a
            where a.status = 1
            order by a.id desc 
            limit 3
        ";
        return $this->db->query($query);
    }

    public function update_log_sell_out_product($source, $status)
    {
        $query = "
            update site.temp_sell_out_product_log a
            set a.status_using = $status
            where a.id = $source
        ";
        return $this->db->query($query);
    }

    public function get_using_log_sell_out_product($source)
    {
        $query = "
            select a.status_using, a.name_table 
            from site.temp_sell_out_product_log a
            where a.id = $source
        ";
        return $this->db->query($query);
    }

    public function create_table_temp_report_selloutproduct($format_time, $count, $count_site_code, $kodeprod)
    {
        $create_table = "
            create table if not exists site.temp_report_sell_out_product_$format_time(
                site_code varchar(20),
                branch_name varchar(50),
                nama_comp varchar(50),
                bulan varchar(10),
                tahun varchar(10),
                value varchar(255),
                unit varchar(10),
                trans varchar(10),
                created_by int(11),
                created_at datetime
            )
        ";
        $create_table = $this->db->query($create_table);

        if ($create_table) {
            $data = [
                'name_table' => "temp_report_sell_out_product_$format_time",
                'created_at' => $this->created_at,
                'created_by' => $this->created_by,
                'breakdown'  => 'v1' ,
                'count_kodeprod'    => $count,
                'count_site_code'   => $count_site_code,
                'kodeprod'    => $kodeprod,
            ];

            $proses = $this->db->insert('site.temp_sell_out_product_log', $data);
            return $this->db->insert_id();
        }

        // return "temp_report_sell_out_product_$format_time";
    }

    public function get_sales_by_site_code_bulan($source, $kodeprod, $from, $to, $format_time, $site_code = null)
    {
        // echo "a";
        // die;
        if ($site_code) {
            $params_site_code = "and a.site_code in ($site_code)";
        }else{
            $params_site_code = "";
        }
        // echo "source: ".$source;
        $insert = "
            insert into site.temp_report_sell_out_product_$format_time
            select a.site_code, a.branch_name, a.nama_comp, a.bulan, a.tahun, sum(a.value) as value, sum(a.unit) as unit, sum(a.trans) as trans, '$this->created_by', '$this->created_at'
            from site.$source a 
            where a.kodeprod in ($kodeprod) and date(a.tgldokjdi) between '$from' and '$to' $params_site_code
            GROUP BY a.site_code, a.bulan
        ";

        $insert = $this->db->query($insert);
        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";die;

        $query = "
            select *
            from site.temp_report_sell_out_product_$format_time a 
        ";

        return $this->db->query($query);
    }

    public function create_table_temp_report_selloutproduct_kodeprod($format_time, $count, $kodeprod, $count_site_code)
    {
        $create_table = "
            create table if not exists site.temp_report_sell_out_product_kodeprod_$format_time(
                site_code varchar(20),
                branch_name varchar(50),
                nama_comp varchar(50),
                bulan varchar(10),
                tahun varchar(10),
                kodeprod varchar(20),
                namaprod varchar(50),
                namasupp varchar(50),
                nama_group varchar(50),
                nama_sub_group varchar(50),
                value varchar(255),
                unit int(10),
                trans int(10),
                created_by int(11),
                created_at datetime
            )
        ";
        $create_table = $this->db->query($create_table);

        if ($create_table) {
            $data = [
                'name_table' => "temp_report_sell_out_product_kodeprod_$format_time",
                'created_at' => $this->created_at,
                'created_by' => $this->created_by,
                'breakdown'  => 'v2',
                'count_kodeprod'    => $count,
                'kodeprod'    => $kodeprod,
                'count_site_code'   => $count_site_code
            ];

            $this->db->insert('site.temp_sell_out_product_log', $data);
            return $this->db->insert_id();
            // return "temp_report_sell_out_product_kodeprod_$format_time";
        }

    }

    public function create_table_temp_report_selloutproduct_kodeprod_tipe_class($format_time, $count, $kodeprod, $count_site_code)
    {
        $create_table = "
            create table if not exists site.temp_report_sell_out_product_kodeprod_tipe_class_$format_time(
                site_code varchar(20),
                branch_name varchar(50),
                nama_comp varchar(50),
                bulan varchar(10),
                tahun varchar(10),
                kodeprod varchar(20),
                namaprod varchar(50),
                namasupp varchar(50),
                nama_group varchar(50),
                nama_sub_group varchar(50),
                kode_type varchar(50),
                nama_type varchar(50),
                sektor varchar(50),
                segment varchar(50),
                kodesalur varchar(50),
                namasalur varchar(50),
                groupsalur varchar(50),
                value varchar(255),
                unit int(10),
                trans int(10),
                created_by int(11),
                created_at datetime
            )
        ";
        $create_table = $this->db->query($create_table);

        if ($create_table) {
            $data = [
                'name_table' => "temp_report_sell_out_product_kodeprod_tipe_class_$format_time",
                'created_at' => $this->created_at,
                'created_by' => $this->created_by,
                'breakdown'  => 'v3',
                'count_kodeprod'    => $count,
                'kodeprod'    => $kodeprod,
                'count_site_code'   => $count_site_code
            ];

            $this->db->insert('site.temp_sell_out_product_log', $data);
            return $this->db->insert_id();
        }

        // return "temp_report_sell_out_product_kodeprod_$format_time";
    }

    public function get_sales_by_site_code_bulan_kodeprod($source, $kodeprod, $from, $to, $format_time, $site_code = null)
    {
        if ($site_code) {
            $params_site_code = "and a.site_code in ($site_code)";
        }else{
            $params_site_code = "";
        }
        // echo "source: ".$source;
        $insert = "
            insert into site.temp_report_sell_out_product_kodeprod_$format_time
            select a.site_code, a.branch_name, a.nama_comp, a.bulan, a.tahun, a.kodeprod, a.namaprod, a.namasupp,nama_group, nama_sub_group, sum(a.value) as value, sum(a.unit) as unit, sum(a.trans) as trans, '$this->created_by', '$this->created_at'
            from site.$source a 
            where a.kodeprod in ($kodeprod) and date(a.tgldokjdi) between '$from' and '$to' $params_site_code
            GROUP BY a.site_code, a.bulan, a.kodeprod
        ";
        $this->db->query($insert);
        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";
        // die;

        $query = "
            select *
            from site.temp_report_sell_out_product_kodeprod_$format_time a 
            limit 100
        ";
        // print_r($query);
        return $this->db->query($query);
    }

    public function get_sales_by_site_code_bulan_kodeprod_tipe_class($source, $kodeprod, $from, $to, $format_time, $site_code = null)
    {
        if ($site_code) {
            $params_site_code = "and a.site_code in ($site_code)";
        }else{
            $params_site_code = "";
        }
        // echo "<br><br><br>";
        // echo "params_site_code: ".$params_site_code;
        // echo "<br><br><br>";
        // die;

        $insert = "
            insert into site.temp_report_sell_out_product_kodeprod_tipe_class_$format_time
            select a.site_code, a.branch_name, a.nama_comp, a.bulan, a.tahun, a.kodeprod, a.namaprod, a.namasupp,nama_group, nama_sub_group, 
            a.kode_type, a.nama_type, a.sektor, a.segment, a.kodesalur, a.namasalur, a.groupsalur,
            sum(a.value) as value, sum(a.unit) as unit, sum(a.trans) as trans, '$this->created_by', '$this->created_at'
            from site.$source a 
            where a.kodeprod in ($kodeprod) and date(a.tgldokjdi) between '$from' and '$to' $params_site_code
            GROUP BY a.site_code, a.bulan, a.kodeprod, a.kode_type, a.kodesalur
        ";
        $this->db->query($insert);
        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";
        // die;

        $query = "
            select *
            from site.temp_report_sell_out_product_kodeprod_tipe_class_$format_time a 
            limit 100
        ";
        // print_r($query);
        return $this->db->query($query);
    }

    public function get_sales_by_site_code_bulan_kodeprod_horizontal($filename)
    {
        $query = "
            select 	a.site_code, a.branch_name, a.nama_comp, a.kodeprod, a.namaprod,
                    a.namasupp, a.nama_group, a.nama_sub_group, 
                    sum(if(a.bulan = 1, a.`value`, 0)) as v1,
                    sum(if(a.bulan = 2, a.`value`, 0)) as v2,
                    sum(if(a.bulan = 3, a.`value`, 0)) as v3,
                    sum(if(a.bulan = 4, a.`value`, 0)) as v4,
                    sum(if(a.bulan = 5, a.`value`, 0)) as v5,
                    sum(if(a.bulan = 6, a.`value`, 0)) as v6,
                    sum(if(a.bulan = 7, a.`value`, 0)) as v7,
                    sum(if(a.bulan = 8, a.`value`, 0)) as v8,
                    sum(if(a.bulan = 9, a.`value`, 0)) as v9,
                    sum(if(a.bulan = 10, a.`value`, 0)) as v10,
                    sum(if(a.bulan = 11, a.`value`, 0)) as v11,
                    sum(if(a.bulan = 12, a.`value`, 0)) as v12,
                    sum(if(a.bulan = 1, a.`unit`, 0)) as u1,
                    sum(if(a.bulan = 2, a.`unit`, 0)) as u2,
                    sum(if(a.bulan = 3, a.`unit`, 0)) as u3,
                    sum(if(a.bulan = 4, a.`unit`, 0)) as u4,
                    sum(if(a.bulan = 5, a.`unit`, 0)) as u5,
                    sum(if(a.bulan = 6, a.`unit`, 0)) as u6,
                    sum(if(a.bulan = 7, a.`unit`, 0)) as u7,
                    sum(if(a.bulan = 8, a.`unit`, 0)) as u8,
                    sum(if(a.bulan = 9, a.`unit`, 0)) as u9,
                    sum(if(a.bulan = 10, a.`unit`, 0)) as u10,
                    sum(if(a.bulan = 11, a.`unit`, 0)) as u11,
                    sum(if(a.bulan = 12, a.`unit`, 0)) as u12
            from site.$filename a 
            GROUP BY a.site_code, a.kodeprod
            ";
        // print_r($query);
        return $this->db->query($query);
    }

    public function get_history_penarikan()
    {
        $get_max = "
            select max(a.created_at) as max
            from site.temp_sell_out_product_log a
            where a.status is null and a.created_by = $this->created_by
            order by a.created_at desc
            limit 5
        ";
        $max = $this->db->query($get_max)->row()->max;

        $query = "
            select  a.created_at, a.name_table, a.breakdown, if(a.created_at = '$max', 1, 0) as status, a.count_kodeprod, a.kodeprod, 
                    a.total_value, a.total_unit, a.count_site_code, a.count_row
            from site.temp_sell_out_product_log a
            where a.status is null and a.created_by = $this->created_by
            order by a.created_at desc
            limit 5
        ";
        return $this->db->query($query);
    }

    public function get_region_by_map_akses_region_nasional($userid)
    {
        $query = "
            select a.userid, a.region
            from site.map_akses_region a 
            where a.userid = $userid and a.`status` = 1 and (a.region = 'NASIONAL' or a.region = 'NASIONAL_ALAMAT')
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_region_by_map_akses_region($userid)
    {
        $query = "
            select a.userid, a.region
            from site.map_akses_region a 
            where a.userid = $userid and a.`status` = 1
            ORDER BY a.region = 'NASIONAL' desc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_site_code_by_region($region)
    {
        if ($region == 'all') {
            $params_region = "";
        }else{
            $params_region = "and (a.region in ($region) or a.sub_region in ($region))";
        }

        $query = "
            select a.site_code
            from site.master_site a
            where a.active = 1 $params_region
            group by a.site_code
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_site_code_by_sub_region($sub_region)
    {
        $query = "
            select a.site_code
            from site.master_site a
            where a.active = 1 and a.sub_region in ($sub_region)
            group by a.site_code
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_summary_total($filename)
    {
        $query = "
            select sum(a.value) as total_value, sum(a.unit) as total_unit, count(*) as count_row
            from site.$filename a
        ";
        return $this->db->query($query);
    }

    public function update_temp_sell_out_product_log($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.temp_sell_out_product_log', $data);
        return $this->db->affected_rows();
    }

    public function get_temp_sell_out_product_log($id)
    {
        $query = "
            select *
            from site.temp_sell_out_product_log a
            where a.id = $id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

}