<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_building_block extends CI_Model 
{

    public function generate_workspace_target_principal($tahun, $bulan, $site_code, $signature){

        $created_at = $this->model_outlet_transaksi->timezone();
        $userid = $this->session->userdata('id');       

        $data = [
            'tahun_building_block'      => $tahun,
            'bulan_building_block'      => $bulan,
            'site_code'                 => $site_code,
            'signature'                 => $signature,
            'created_at'                => $created_at,
            'created_by'                => $userid,
        ];

        $this->db->insert('db_building_block.workspace_target_principal', $data);
        $id_workspace = $this->db->insert_id();
        return $id_workspace;
    }

    public function generate_workspace_target_outlet($data){

        $this->db->insert('db_building_block.workspace_target_outlet', $data);
        $id_workspace = $this->db->insert_id();
        return $id_workspace;
    }

    public function get_data_workspace_target_principal($userid){

        $query = "
            select a.*, b.*, c.branch_name, c.nama_comp
            from db_building_block.workspace_target_principal a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id left join (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )c on a.site_code = c.site_code
            where a.created_by = $userid
        ";
        return $this->db->query($query);
    }

    public function get_data_workspace_target_outlet($userid){

        $query = "
            select a.*, b.*, c.branch_name, c.nama_comp
            from db_building_block.workspace_target_outlet a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id left join (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )c on a.site_code = c.site_code
            where a.created_by = $userid and a.deleted_at is null
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_workspace_target_principal($signature){
        $query = "
            select *
            from db_building_block.workspace_target_principal a
            where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_data_target_principal_by_id_workspace($id_workspace){
        $query = "
            select a.id, a.site_code, a.divisi, a.tahun, a.bulan, a.target_gt, a.average_gt, a.target_mt, a.average_mt, a.target_ph, a.average_ph, 
                    a.source_average, (a.average_gt / a.target_gt) as persen_gt, 
                    (a.average_mt / a.target_mt) as persen_mt, 
                    (a.average_ph / a.target_ph) as persen_ph, 
                    b.*
            from db_building_block.data_target_by_principal a left join (
                select a.site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by a.site_code
            )b on a.site_code = b.site_code
            where a.id_workspace = $id_workspace
        ";
        return $this->db->query($query);
    }

    public function generate_target($tahun, $bulan, $site_code, $id_workspace){

        $created_at = $this->model_outlet_transaksi->timezone();
        $userid = $this->session->userdata('id');

        $get_master_divisi = $this->get_master_divisi();
        
        foreach ($get_master_divisi->result() as $a) {
            
            $data = [
                'site_code'     => $site_code,
                'id_workspace'  => $id_workspace,
                'divisi'        => $a->divisi,
                'tahun'         => $tahun,
                'bulan'         => $bulan,
                'target_gt'     => 0,
                'average_gt'    => 0,
                'target_mt'     => 0,
                'average_mt'    => 0,
                'target_ph'     => 0,
                'average_ph'    => 0,
                'source_average'=> 0,
                'created_at'    => $created_at,
                'created_by'    => $userid,
            ];

            $this->db->insert('db_building_block.data_target_by_principal', $data);
        }
    }

    public function generate_target_outlet($tahun, $bulan, $signature){

        $query = "
            insert into db_building_block.data_target_by_outlet
            select 	'', $tahun, $bulan, a.site_code, a.kode_lang, a.nama_lang, a.kode_type, a.nama_type, a.sektor, a.segment, a.kodesalur,
                    a.namasalur,'' as value_target, '$signature' as signature_workspace, a.created_at, a.created_by, '', '', '', ''
            from db_building_block.temp_raw_outlet a
            where a.signature = '$signature'
            GROUP BY a.kode_lang
        ";

        return $this->db->query($query);
    }

    public function get_data_target_by_outlet($signature_workspace){
        $query = "
            select *
            from db_building_block.data_target_by_outlet a
            where a.signature_workspace = '$signature_workspace'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function generate_raw_outlet($tahun, $bulan, $site_code, $signature){
        $created_at = $this->model_outlet_transaksi->timezone();
        $userid = $this->session->userdata('id');

        $query = "
        insert into db_building_block.temp_raw_outlet
        select 	'',concat(a.kode_comp, a.nocab) as site_code, a.kode_lang, a.nama_lang, 
                a.kode_type, a.nama_type, a.sektor, a.segment, a.kodesalur, a.namasalur,
                a.bruto, a.unit, '$signature', '$created_at', $userid,'','','',''
        from db_building_block.tbl_raw_result a 
        where a.tahun = $tahun and a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
        GROUP BY a.kode_lang 
        ";

        return $this->db->query($query);

    }

    public function get_master_divisi(){
        $query = "
            select *
            from db_building_block.master_divisi a
            where a.active = 1 and a.deleted_at is null
        ";

        return $this->db->query($query);
    }

    public function get_master_user($userid){
        $query = "
            select *
            from db_building_block.master_user a
            where a.userid = '$userid' and a.active = 1
        ";
        // print_r($query);
        return $this->db->query($query);
    }

    public function get_data_target_by_principal($tahun, $bulan, $userid){
        $query = "
            select  a.id, a.site_code, a.divisi, a.tahun, a.bulan, a.target_gt, a.average_gt, a.target_mt, a.average_mt, a.target_ph, a.average_ph, 
                    a.source_average, (a.average_gt / a.target_gt) as persen_gt, 
                    (a.average_mt / a.target_mt) as persen_mt, 
                    (a.average_ph / a.target_ph) as persen_ph, 
                    b.*
            from    db_building_block.data_target_by_principal a left join (

                select a.site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by a.site_code

            )b on a.site_code = b.site_code
            where a.created_by = $userid and a.tahun = $tahun and a.bulan = $bulan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);

    }

    public function get_data_target_by_divisi($tahun, $bulan, $userid, $divisi){
        $query = "
            select a.*, b.*
            from db_building_block.data_target_by_principal a left join (

                select a.site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by a.site_code

            )b on a.site_code = b.site_code
            where a.created_by = $userid and a.tahun = $tahun and a.bulan = $bulan and a.divisi = '$divisi'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;

        return $this->db->query($query);

    }

    public function get_raw_data($tahun, $bulan, $userid){

        $get_master_user = $this->get_master_user($userid);
        $site_code = '';
        foreach ($get_master_user->result() as $a) {
            $site_code.= ",'".$a->site_code."'";
        }
        $site_code = preg_replace('/^,/', '', $site_code,1);

        $query = "
            select *
            from db_building_block.tbl_raw a
            where concat(a.kode_comp, a.nocab) in ($site_code) and a.tahun = $tahun and a.bulan = $bulan
            limit 10
        ";

        // print_r($query);

        return $this->db->query($query);

    }

    public function delete_data($tahun, $bulan, $userid){
        $site_code =$this->get_sitecode_by_userid($userid);

        $query = "
            delete from db_building_block.data_summary_sales
            where tahun = $tahun and bulan = $bulan and site_code in ($site_code)
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_by_divisi($tahun, $bulan, $userid, $divisi){
        
        $created_at = $this->model_outlet_transaksi->timezone();
        $site_code =$this->get_sitecode_by_userid($userid);
        $kodeprod = $this->get_kodeprod_by_divisi($divisi);

        $query = "
            insert into db_building_block.data_summary_sales 
            select  '', concat(a.kode_comp, a.nocab) as site_code, '$divisi', $tahun, $bulan, 
                    sum(if(b.segment = 'GT', a.bruto, 0)) as bruto_gt ,
                    sum(if(b.segment = 'MT', a.bruto, 0)) as bruto_mti,
                    sum(if(b.segment = 'PH', a.bruto, 0)) as bruto_ph, 
                    '$created_at', $userid
            from db_building_block.tbl_raw_result a LEFT JOIN (
                select a.kode_type, a.sektor, a.segment
                from mpm.tbl_bantu_type a 
            )b on a.kode_type = b.kode_type
            where a.tahun = $tahun and a.bulan = $bulan and concat(a.kode_comp, a.nocab) in ($site_code) and a.kodeprod in ($kodeprod)
            group by concat(a.kode_comp, a.nocab)
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function get_sitecode_by_userid($userid){
        $get_master_user = $this->get_master_user($userid);
        $site_code = '';
        foreach ($get_master_user->result() as $a) {
            $site_code.= ",'".$a->site_code."'";
        }
        $site_code = preg_replace('/^,/', '', $site_code,1);
        return $site_code;
    }

    public function get_kodeprod_by_divisi($divisi){
        if ($divisi == 'D1') {
            $query = "
                select a.kodeprod
                from mpm.tabprod a 
                where a.grup = 'G0101'
            ";
        }elseif($divisi == 'D2'){
            $query = "
                select a.kodeprod
                from mpm.tabprod a 
                where a.grup = 'G0102'
            ";
        }elseif($divisi == 'DELTOMED_ALL'){
            $query = "
                select a.kodeprod
                from mpm.tabprod a 
                where a.supp = '001'
            ";
        }elseif($divisi == 'HERBANA'){
            $query = "
                select a.kodeprod
                from mpm.tabprod a 
                where a.grup = 'G0103'
            ";
        }elseif($divisi == 'MARGUNA'){
            $query = "
                select a.kodeprod
                from mpm.tabprod a 
                where a.supp = '002'
            ";
        }elseif($divisi == 'US'){
            $query = "
                select a.kodeprod
                from mpm.tabprod a 
                where a.supp = '005'
            ";
        }elseif($divisi == 'MDJ'){
            $query = "
                select a.kodeprod
                from mpm.tabprod a 
                where a.supp = '015'
            ";
        }

        $proses = $this->db->query($query);

        $kodeprod = '';
        foreach ($proses->result() as $a) {
            $kodeprod.= ",".$a->kodeprod;
        }
        $kodeprod = preg_replace('/,/', '', $kodeprod,1);
        return $kodeprod;
    }

    public function get_temp_summary($userid){

        $site_code =$this->get_sitecode_by_userid($userid);

        $query = "
            select a.*, b.*
            from db_building_block.temp_summary a left join (
                select a.branch_name, a.nama_comp, concat(a.kode_comp,a.nocab) as site_code
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by concat(a.kode_comp,a.nocab)
            )b on a.site_code = b.site_code
            where a.site_code in ($site_code)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function generating_temp_summary($tahun, $bulan, $userid){


        $site_code =$this->get_sitecode_by_userid($userid);

        $query = "
            insert into db_building_block.temp_summary
            select a.*
            from db_building_block.data_summary_sales a
            where a.site_code in ($site_code) and a.tahun = $tahun and a.bulan = $bulan
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function delete_temp_summary($userid){
        return $this->db->query("delete from db_building_block.temp_summary where created_by = $userid");
    }

    public function get_data_summary($userid){

        $site_code =$this->get_sitecode_by_userid($userid);

        $query = "
            select a.*, b.*
            from db_building_block.data_summary_sales a left join (
                select a.branch_name, a.nama_comp, concat(a.kode_comp,a.nocab) as site_code
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by concat(a.kode_comp,a.nocab)
            )b on a.site_code = b.site_code
            where a.site_code in ($site_code)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_data_summary_groupby_bulan_tahun($userid){


        $query = "
            select *
            from db_building_block.data_summary_sales a
            where a.created_by = $userid
            GROUP BY a.tahun, a.bulan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_data_summary_group_tahun_bulan_sitecode_divisi_userid($tahun, $bulan, $site_code, $divisi, $userid){
        $query = "
            select *
            from db_building_block.data_summary_sales a
            where a.tahun = $tahun and a.bulan = $bulan and a.site_code ='$site_code' and a.divisi = '$divisi' and a.created_by = $userid
            order by a.site_code, a.divisi
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;

        return $this->db->query($query);

    }

    public function get_temp_average($signature){
        $query = "
            select a.site_code, a.divisi, avg(a.bruto_gt) as average_gt, avg(a.bruto_mt) as average_mt, avg(a.bruto_ph) as average_ph, a.signature
            from db_building_block.temp_average_target a 
            where a.signature = '$signature'
            GROUP BY a.site_code, a.divisi 
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_temp_average_target_result($signature){
        $query = "
            select *
            from db_building_block.temp_average_target_result a 
            where a.signature_temp_average_target = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_deltomed_all($tahun, $bulan){
        $query = "
            select 	a.site_code, sum(a.target_gt) as sum_target_gt, sum(a.average_gt) as sum_average_gt, 
                    sum(target_mt) as sum_target_mt, sum(average_mt) as sum_average_mt,
                    sum(a.target_ph) as sum_target_ph, sum(a.average_ph) as sum_average_ph
            from db_building_block.data_target_by_principal a
            where a.divisi in ('D1','D2') and a.tahun = $tahun and a.bulan = $bulan
            group by a.site_code
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;

        return $this->db->query($query);

    }

    public function get_data_target_by_id($a){

        $query = "

        ";
        
    }

    

}