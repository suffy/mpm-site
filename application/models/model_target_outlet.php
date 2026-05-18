<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_target_outlet extends CI_Model 
{
    public function __construct()
    {
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->userid = $this->session->userdata('id');
    }
    public function get_target_outlet()
    {
        $query = "
            select *
            from site.target_outlet a
        ";
        return $this->db->query($query);
    }

    public function input_target_outlet($data)
    {
        $proses =$this->db->insert('site.target_outlet', $data);
        return $this->db->insert_id();
    }

    public function get_master_outlet()
    {
        $query = "
            select *
            from site.target_master_outlet a
        ";
        return $this->db->query($query);
    }

    public function get_master_outlet_by_kode_outlet($kode_outlet)
    {
        $query = "
            select *
            from site.target_master_outlet a
            where a.kode_outlet = '$kode_outlet'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function update_master_outlet($tahun, $site_code)
    {

        $signature = 'outlet-' . rand() . md5($this->created_at) . date('Ymd');

        $query = "
            insert into site.target_master_outlet
            select '', $tahun, concat(a.kode_comp, a.kode_lang) as kode_outlet, a.nama_lang, concat(a.kode_comp, a.nocab) as site_code, a.kode_type, a.kodesalur, '$this->created_at', '$this->userid', '$signature'
            from data$tahun.tblang a 
            where concat(a.kode_comp, a.nocab) = '$site_code'
            group by concat(a.kode_comp, a.kode_lang)
        ";
        return $this->db->query($query);
    }

    public function master_site($site_code)
    {
        if ($site_code) {
            $params_site_code = "where a.site_code = '$site_code'";
        }else{
            $params_site_code = "";
        }
        $query = "
            select *
            from site.master_site a
            $params_site_code 
        ";

        return $this->db->query($query);
    }

    public function delete_master_outlet($site_code)
    {
        $query = "delete from site.target_master_outlet where site_code = '$site_code'";
        return $this->db->query($query);
    }

    public function insert_master_tracking($data)
    {
        $proses =$this->db->insert('site.target_master_tracking', $data);
        return $this->db->insert_id();
    }

    public function get_master_tracking($signature = "")
    {
        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }
        $query = "
            select a.*, b.username
            from site.target_master_tracking a left join site.master_user b on a.created_by = b.id
            where a.deleted_at is null $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function update_master_tracking($data, $id_tracking)
    {
        $proses =$this->db->update('site.target_master_tracking', $data, array('id' => $id_tracking));
        return $proses;
    }

    public function get_tracking_detail_by_id_tracking($id_tracking)
    {
        $query = "
            select a.*, b.username
            from site.target_master_tracking_detail a left join site.master_user b on a.created_by = b.id
            where a.deleted_at is null and a.id_tracking = $id_tracking
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_tracking_detail($data)
    {
        $proses =$this->db->insert('site.target_master_tracking_detail', $data);
        return $this->db->insert_id();
    }

    public function get_tracking_detail($signature = "")
    {
        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }
        $query = "
            select a.*, b.username
            from site.target_master_tracking_detail a left join site.master_user b on a.created_by = b.id
            where a.deleted_at is null $params
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function update_tracking_detail($data, $id_tracking_detail)
    {
        $proses =$this->db->update('site.target_master_tracking_detail', $data, array('id' => $id_tracking_detail));
        return $proses;
    }

    public function get_master_tracking_by_id($id)
    {
        $query = "
            select *
            from site.target_master_tracking a
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function insert_start_tracking($data)
    {
        $id_tracking = $data['id_tracking'];
        $tahun = $data['tahun'];
        $kode_outlet = $data['kode_outlet'];
        $bulan = $data['bulan_join'];
        $kodeprod = $data['kodeprod'];
        $target_value = $data['target_value'];

        $query = "
            insert into site.target_start_tracking
            select  '', $id_tracking, a.kode_outlet, round(sum(a.actual_value)) as actual_value, $target_value, 
                    (sum(a.actual_value) - $target_value) as gap, 
                    (sum(a.actual_value) - $target_value) / $target_value * 100 as gap_persen,
                    '$this->created_at', '$this->userid'
            from 
            (
                select concat(a.kode_comp, a.kode_lang) as kode_outlet, sum(a.tot1) as actual_value
                from data$tahun.fi a 
                where concat(a.kode_comp, a.kode_lang) in ('$kode_outlet') and a.bulan in ($bulan) and a.kodeprod in ($kodeprod)
                union all 
                select concat(a.kode_comp, a.kode_lang), sum(a.tot1) as actual_value
                from data$tahun.ri a 
                where concat(a.kode_comp, a.kode_lang) in ('$kode_outlet') and a.bulan in ($bulan) and a.kodeprod in ($kodeprod)
            )a
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_dashboard_loyalty()
    {
        $query  = "
            select 	a.nama_tracking, a.from, a.to, b.kode_outlet, b.nama_outlet, b.kode_type, b.kode_class,
                    c.actual_value, c.target_value, c.gap, c.gap_persen
            from site.target_master_tracking a left join (
                select a.id, a.id_tracking, a.kode_outlet, a.nama_outlet, a.kode_type, a.kode_class
                from site.target_master_tracking_detail a
            )b on a.id = b.id_tracking left join (
                select a.id_tracking, a.kode_outlet, a.actual_value, a.target_value, a.gap, a.gap_persen
                from site.target_start_tracking a 
                where a.created_at = (
                    select max(a.created_at)
                    from site.target_start_tracking a
                )
            )c on b.kode_outlet = c.kode_outlet
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function count_tracking()
    {
        $query = "
            select count(*) as count_tracking
            from site.target_master_tracking a 
            where a.deleted_at is null
        ";
        return $this->db->query($query);
    }

    public function count_tracking_detail()
    {
        $query = "
            select count(*) as count_tracking
            from site.target_master_tracking_detail a
            where a.deleted_at is null
        ";
        return $this->db->query($query);
    }

    public function get_po(){
        $query = "
            select a.id, a.supp, a.tipe, a.nopo, date(a.tglpo) as tglpo, a.company, a.total_value, a.kode_alamat, a.status, b.*, c.namasupp, a.signature, a.open
            from mpm.po a inner join (
                select a.site_code, a.branch_name, a.nama_comp
                from site.master_site a 
                where a.region in ('kalimantan','sulawesi') 
            )b on a.kode_alamat = b.site_code left join site.master_supplier c 
                on a.supp = c.supp
            where a.deleted = 0
            and
            ((
                year(a.tglpesan) in (year(date(now()))) and month(a.tglpesan) in (month(date(now())))
            ) or
            (
                year(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                month(a.tglpesan) in (date_format(date(now()) - INTERVAL '3' MONTH,'%m'))
            ))
            ORDER BY a.tglpo desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_retur()
    {
        $query = "
            select a.*, b.nama_comp, c.namasupp
            from management_inventory.pengajuan_retur a inner join (
                select a.site_code, a.branch_name, a.nama_comp
                from site.master_site a 
                where a.region in ('kalimantan','sulawesi') 
            )b on a.site_code = b.site_code left join (
                select a.supp, a.namasupp
                from site.master_supplier a
            )c on a.supp = c.supp
            where a.deleted is null and 
            ((
                year(a.tanggal_pengajuan) in (year(date(now()))) and month(a.tanggal_pengajuan) in (month(date(now())))
            ) or
            (
                year(a.tanggal_pengajuan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                month(a.tanggal_pengajuan) in (date_format(date(now()) - INTERVAL '3' MONTH,'%m'))
            ))
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_claim()
    {
        $query = "
            select 	a.id, a.kategori, a.supp, b.namasupp, a.`from`, a.`to`, a.nama_program, a.nomor_surat, a.syarat, 
                    a.duedate, a.upload_jpg, a.upload_pdf, a.upload_template_program,
                    a.status_validasi, a.signature, c.status as `status`, c.nama_status, c.signature as signature_ajuan,
                    c.site_code, d.branch_name, d.nama_comp, a.created_by, e.username, c.nomor_ajuan, c.status_data_final,
                    c.status_hardcopy, c.nama_status_hardcopy, c.file_hardcopy, c.nomor_hardcopy, c.status_keikutsertaan, c.nama_status_keikutsertaan,
                    c.status_internal, g.nama_status as nama_status_internal, f.username as pic_userid_username
            from management_claim.registrasi_program a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )b on a.supp = b.supp LEFT JOIN (
                select *
                from management_claim.ajuan_claim a 
                where a.deleted is null
                
            )c on a.id = c.id_program INNER JOIN (
                select *
                from site.master_site a 
                where a.region in ('KALIMANTAN', 'SULAWESI')
            )d on c.site_code = d.site_code LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )e on a.created_by = e.id LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )f on c.pic_userid = f.id left join management_claim.master_status_internal g
	           on c.status_internal = g.id
            where a.deleted is null
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_kalender_data(){    
        $query = "
            select a.*
            from management_office.kalender_data a inner join (
                select a.site_code, branch_name, a.nama_comp 
                from site.master_site a 
                where a.region in ('KALIMANTAN','SULAWESI')
            )b on a.kode = b.site_code
        ";
        return $this->db->query($query);
    }

}