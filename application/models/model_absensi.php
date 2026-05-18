<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model_absensi extends CI_Model
{

    public function get_karyawan($id)
    {
        $session_user = $this->session->userdata('username');

        if ($session_user == 'nanita' || $session_user == 'ratri') {
            $params = "";
        }else{
            $params = "and (a.id = $id or d.userid_verifikasi1 = $id)";
        }

        // if ($session_user == 'nanita' || $session_user == 'ratri') {
        //     $params = "";
        // }elseif ($session_user == 'fakhrul') {
        //     $params = "and (a.id = $id or d.userid_verifikasi1 = $id or d.userid_pelaksana in (759, 561, 812, 444))";
        // }else{
        //     $params = "and (a.id = $id or d.userid_verifikasi1 = $id)";
        // }

        $query = "
            select a.id, a.userid_absensi, c.userid as id_absensi, a.username, a.name as nama, d.userid_verifikasi1, a.active
            from site.master_user a
            left join (
                select *
                from site.absensi_karyawan_shd
            )b on a.id = b.userid_web
            left join (
                select *
                from site.absensi_karyawan 
            )c on a.userid_absensi = c.userid
            left join (
                select *
                from management_rpd.m_karyawan
            )d on a.id = d.userid_pelaksana
            where a.kode_company = 000 and a.active = 1 $params
            order by a.name asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function data_absensi_transaksi($userid)
    {
        // $this->db->select('absensi_transaksi.*, user.name');
        // $this->db->from('site.absensi_transaksi');
        // $this->db->join('mpm.user', 'mpm.user.id = site.absensi_transaksi.userid', 'left');
        // $this->db->where('user.id', $userid);
        // $query = $this->db->get();
        // // print_r($this->db->last_query());
        // return $query;

        $query = "
            select a.*, b.name, c.hadir, c.flag_status, c.tidak_lengkap, if(a.jam_masuk_kantor < a.actual_masuk, 1, 0) as flag_terlambat_final
            from site.absensi_transaksi a left join site.master_user b 
                on a.userid = b.id left join site.absensi c 
                on a.signature = c.signature
            where b.id = $userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function data_absensi_transaksi_by_signature($signature = null)
    {
        $query = "
            select a.*, b.name, c.hadir, c.flag_status, c.tidak_lengkap, if(a.jam_masuk_kantor < a.actual_masuk, 1, 0) as flag_terlambat_final, 
            if(month(a.tanggal) < 10,concat('0',month(a.tanggal)), month(a.tanggal)) as bulan, year(a.tanggal) as tahun
            from site.absensi_transaksi a left join site.master_user b 
                on a.userid = b.id left join site.absensi c 
                on a.signature = c.signature
            where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_absensi_transaksi_terlambat($signature)
    {
        // $query = "
        //     select a.*, b.name, c.hadir, c.flag_status, c.no_information
        //     from site.absensi_transaksi a left join site.master_user b 
        //         on a.userid = b.id left join site.absensi c 
        //         on a.signature = c.signature
        //     where a.signature = '$signature' and a.jam_masuk_kantor < a.actual_masuk
        // ";

        $query = "
            select a.*
            from site.absensi_transaksi a 
            where a.signature = '$signature' and a.flag_terlambat = 1
        ";

        return $this->db->query($query);
    }

    // public function get_absensi_transaksi_hari_kerja($signature = null, $flag_weekend = null)
    // {
    //     $userid = $this->session->userdata('id');
    //     if ($signature) {
    //         $params_signature = "and a.signature = '$signature' ";
    //     }else{
    //         $params_signature = "";
    //     }

    //     if ($flag_weekend == 1) {
    //         $params_hari_libur = "and a.status_hari not in (0)";
    //     }else{
    //         $params_hari_libur = "and a.status_hari not in (6,0)";
    //     }

    //     $query = "
    //         select *
    //         from site.absensi_transaksi a 
    //         where  a.userid = $userid $params_signature $params_hari_libur
    //     ";
    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";
    //     return $this->db->query($query);
    // }

    public function get_absensi_transaksi_hari_kerja($signature = null, $flag_weekend = null, $userid = null)
    {
        if ($userid != null) {
            $params_userid = "$userid ";
        }else{
            $params_userid = $this->session->userdata('id');
        }

        if ($signature) {
            $params_signature = "and a.signature = '$signature' ";
        }else{
            $params_signature = "";
        }

        if ($flag_weekend == 1) {
            $params_hari_libur = "and a.status_hari not in (0)";
        }else{
            $params_hari_libur = "and a.status_hari not in (6,0)";
        }

        $query = "
            select *
            from site.absensi_transaksi a 
            where  a.userid = $params_userid $params_signature $params_hari_libur
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_hadir_kerja ($signature = null, $userid = null)
    {
        if ($userid != null) {
            $params_userid = "$userid ";
        }else{
            $params_userid = $this->session->userdata('id');
        }

        if ($signature) {
            $params_signature = "and a.signature = '$signature' ";
        }else{
            $params_signature = "";
        }

        $query = "
            select *
            from site.absensi_transaksi a
            where a.userid = $params_userid $params_signature and (actual_masuk is not null or actual_keluar is not null)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_hari_kerja_no_userid($signature = null)
    {
        $userid = $this->session->userdata('id');
        if ($signature) {
            $params_signature = "and a.signature = '$signature' ";
        }else{
            $params_signature = "";
        }
        $query = "
            select *
            from site.absensi_transaksi a 
            where a.status_hari not in (6,0) $params_signature
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_m_karyawan($userid_pelaksana)
    {
        $query = "
            select *
            from management_rpd.m_karyawan a
            where a.userid_pelaksana = $userid_pelaksana
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_tidak_lengkap($signature, $flag_weekend = null)
    {
        if ($flag_weekend == 1) {
            $params_hari_libur = "and a.status_hari not in (0)";
        }else{
            $params_hari_libur = "and a.status_hari not in (6,0)";
        }

        $query = "
            select a.*, b.name, c.hadir, c.flag_status, c.tidak_lengkap
            from site.absensi_transaksi a left join site.master_user b 
                on a.userid = b.id left join site.absensi c 
                on a.signature = c.signature
            where a.signature = '$signature' $params_hari_libur and a.flag_terlambat = 0 and (a.actual_masuk is null or a.actual_keluar is null or (a.actual_keluar < (select jam_keluar from site.absensi_jam_kerja))) and (a.flag_status_absensi is null or a.flag_status_absensi = 0)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_tidak_lengkap_and_keterangan_null($signature, $flag_weekend = null)
    {
        if ($flag_weekend == 1) {
            $params_hari_libur = "and a.status_hari not in (0)";
        }else{
            $params_hari_libur = "and a.status_hari not in (6,0)";
        }

        $query = "
            select a.*, b.name, c.hadir, c.flag_status, c.tidak_lengkap
            from site.absensi_transaksi a left join site.master_user b 
                on a.userid = b.id left join site.absensi c 
                on a.signature = c.signature
            where a.signature = '$signature' $params_hari_libur and a.flag_terlambat = 0 and (a.actual_masuk is null or a.actual_keluar is null) and (a.keterangan is null or a.keterangan = '')
        
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_terlambat_and_keterangan_null($signature, $flag_weekend = null)
    {
        if ($flag_weekend == 1) {
            $params_hari_libur = "and a.status_hari not in (0)";
        }else{
            $params_hari_libur = "and a.status_hari not in (6,0)";
        }

        $query = "
            select a.*, b.name, c.hadir, c.flag_status, c.tidak_lengkap
            from site.absensi_transaksi a left join site.master_user b 
                on a.userid = b.id left join site.absensi c 
                on a.signature = c.signature
            where a.signature = '$signature' $params_hari_libur and a.flag_terlambat = 1 and (a.keterangan is null or a.keterangan = '')
        
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }
    
    public function get_absensi_transaksi_no_information($userid = '', $signature = null)
    {
        if ($userid != null) {
            $params_userid = "$userid ";
        }else{
            $params_userid = $this->session->userdata('id');
        }
        if ($signature) {
            $params_signature = "and a.signature = '$signature' ";
        }else{
            $params_signature = "";
        }

        $query = "
            select *
            from site.absensi_transaksi a  
            where a.status_hari not in (6,0) and a.userid = $params_userid and
            (a.actual_masuk is null or a.actual_keluar is null or a.flag_terlambat = 1) and (a.keterangan = '' or a.keterangan is null) $params_signature
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_no_information_origin($signature = null)
    {
        $userid = $this->session->userdata('id');
        if ($signature) {
            $params_signature = "and a.signature = '$signature' ";
        }else{
            $params_signature = "";
        }

        $query = "
            select *
            from site.absensi_transaksi a  
            where a.status_hari not in (6,0) and a.userid = $userid and
            (a.actual_masuk is null or a.actual_keluar is null) $params_signature
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_master_join_absensi_transaksi($userid, $bulan, $tahun)
    {
        $query = "
            SELECT a.userid, a.userid_absensi, a.tanggal, a.waktu_masuk, b.waktu_keluar, TIMEDIFF(b.waktu_keluar, a.waktu_masuk) as total_jam_kerja
            FROM
            (
                SELECT b.id as userid, a.userid as userid_absensi, DATE(a.waktu) as tanggal, MIN(time(a.waktu)) as waktu_masuk
                FROM site.absensi_master a
                left join site.master_user b on a.userid = b.userid_absensi
                where b.id = '$userid' and year(a.waktu) = '$tahun' and month(a.waktu) = '$bulan' AND a.status = 0
                GROUP BY a.userid, DATE(a.waktu)
            )a
            LEFT JOIN
            (
                SELECT b.id as userid, a.userid as userid_absensi, DATE(a.waktu) as tanggal, MAX(time(a.waktu)) as waktu_keluar
                FROM site.absensi_master a
                left join site.master_user b on a.userid = b.userid_absensi
                where b.id = '$userid' and  year(a.waktu) = '$tahun' and month(a.waktu) = '$bulan' AND a.status in (1, 5)
                GROUP BY a.userid, DATE(a.waktu)
            )b on a.userid = b.userid and a.tanggal = b.tanggal
            LEFT JOIN(
                SELECT *
                FROM site.absensi_transaksi a
			)c on a.userid_absensi = c.userid_absensi and a.tanggal = c.tanggal
            where c.tanggal is null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function insert_absensi_transaksi($data)
    {
        $this->db->insert('site.absensi_transaksi', $data); 
        return $this->db->insert_id();
    }

    public function get_absensi_master_join_absensi_transaksi_shd($userid, $bulan, $tahun)
    {
        $query = "
            select a.*
            from (
                    select b.id as userid, b.userid_absensi, b.username_shd, a.attendance_date, a.checkin_time, a.checkout_time, TIMEDIFF(a.checkout_time, a.checkin_time) as total_jam_kerja, a.username_employee
                    from site.absensi_master_shd a
                    left join site.master_user b 
                    on a.username_employee = b.username_shd
                    where b.id = '$userid' and year(a.attendance_date) = '$tahun' and month(a.attendance_date) = '$bulan'
                    GROUP BY a.attendance_date 
            )a LEFT JOIN(
                    SELECT *
                    FROM site.absensi_transaksi a
            )b on a.userid = b.userid and a.attendance_date = b.tanggal 
            where b.tanggal is null
            ORDER BY a.attendance_date asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_master_jam_kerja()
    {
        $this->db->select('*');
        $this->db->from('site.absensi_jam_kerja');
        $query = $this->db->get();
        // print_r($this->db->last_query());
        // die;
        return $query;
    }

    public function report_data_absensi_backup($userid = null, $tahun = null)
    {
        if  ($userid) {
            $params_user = 'and a.userid = '.$userid;
        } else {
            $params_user = '';
        }

        if  ($tahun) {
            $params_tahun = 'and a.tahun = '.$tahun;
        } else {
            $params_tahun = '';
        }

        $query = "
            select *
            from site.absensi a
            left join site.master_user b
            on b.id = a.userid
            where a.flag_status = '3' $params_user $params_tahun
            ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);

        // if ($userid) {
        //     $params1 = $this->db->where('absensi.userid',$userid);
        // }

        // if ($tahun) {
        //     $params2 = $this->db->where("absensi.tahun = $tahun");
        // }

        // $this->db->select('absensi.*, user.name');
        // $this->db->from('site.absensi');
        // $this->db->join('mpm.user', 'mpm.user.id = site.absensi.userid', 'left');
        // $this->db->where('site.absensi.flag_status',  '3');
        // $params1;
        // $params2;

        // $query = $this->db->get();
        // print_r($this->db->last_query());die;
        
    }

    public function report_data_absensi($userid = null, $tahun = null, $group_by = null, $flag_weekend = null)
    {

        // echo "userid : ",$userid;

        if($userid == 'all')
        {
            $params_user = '';
            $params_group_user = ', a.userid';
        }
        elseif ($userid != 'all' && $userid != null) {
            $params_user = 'and a.userid = '.$userid;
            $params_group_user = '';

        } else {
            $params_user = '';
            $params_group_user = ', a.userid';
        }

        if  ($tahun) {
            // $params_tahun = 'and a.tahun = '.$tahun;
            $params_tahun = 'and year(a.tanggal) = '.$tahun;
        } else {
            $params_tahun = '';
        }

        if  ($group_by == "none") {
            $params_group_by = '';
        } else {
            $params_group_by = ' , a.bulan';
        }

        if ($flag_weekend == 1) {
            $params_hari_libur = "a.status_hari not in (0)";
        }else{
            $params_hari_libur = "a.status_hari not in (6,0)";
        }

        // $query = "
        //     select *
        //     from site.absensi a
        //     left join site.master_user b
        //     on b.id = a.userid
        //     where a.flag_status = '3' $params_user $params_tahun
        //     ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $query = "
            select 	a.userid, a.bulan, sum(a.total_hari_kerja) as total_hari_kerja, 
                    sum(a.total_kehadiran) as total_kehadiran, sum(a.total_terlambat) as total_terlambat, 
			        sum(a.total_tidak_lengkap) as total_tidak_lengkap, b.username, b.name
            from
            (
                select a.userid, date_format(a.tanggal, '%m-%Y') as bulan, count(*) as total_hari_kerja, '' as total_tidak_lengkap, '' as total_terlambat, '' as total_kehadiran
                from site.absensi_transaksi a 
                where  $params_hari_libur $params_tahun $params_user
                group by date_format(a.tanggal, '%m-%Y') $params_group_user
                union all 
                select a.userid, date_format(a.tanggal, '%m-%Y') as bulan, '', count(*) as total_tidak_lengkap, '' as total_terlambat, '' as total_kehadiran
                from site.absensi_transaksi a 
                where $params_hari_libur $params_tahun and a.flag_terlambat = 0 and (a.actual_masuk is null or a.actual_keluar is null) and (a.flag_status_absensi is null or a.flag_status_absensi = 0) $params_user
                group by date_format(a.tanggal, '%m-%Y') $params_group_user
                union all
                select a.userid, date_format(a.tanggal, '%m-%Y') as bulan, '' as total_hari_kerja, '' as total_tidak_lengkap, count(*) as total_terlambat, '' as total_kehadiran
                from site.absensi_transaksi a 
                where  $params_hari_libur $params_tahun and a.flag_terlambat = 1 $params_user
                group by date_format(a.tanggal, '%m-%Y') $params_group_user
                union all 
                select a.userid, date_format(a.tanggal, '%m-%Y') as bulan, '', '', '', count(*) as total_kehadiran
                from site.absensi_transaksi a 
                where $params_hari_libur $params_tahun and (a.actual_masuk is not null or a.actual_keluar is not null) $params_user
                group by date_format(a.tanggal, '%m-%Y') $params_group_user
            )a left join site.master_user b on a.userid = b.id 
            group by a.userid
            $params_group_by
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

        // if ($userid) {
        //     $params1 = $this->db->where('absensi.userid',$userid);
        // }

        // if ($tahun) {
        //     $params2 = $this->db->where("absensi.tahun = $tahun");
        // }

        // $this->db->select('absensi.*, user.name');
        // $this->db->from('site.absensi');
        // $this->db->join('mpm.user', 'mpm.user.id = site.absensi.userid', 'left');
        // $this->db->where('site.absensi.flag_status',  '3');
        // $params1;
        // $params2;

        // $query = $this->db->get();
        // print_r($this->db->last_query());die;
        
    }

    public function data_absensi_by_signature($userid = null, $signature = null)
    {
        
        // $this->db->select('*');
        // $this->db->from('site.absensi');
        // $this->db->where('userid', $userid);
        // $this->db->where('signature', $signature);

        $query = "
            select *
            from site.absensi a 
            where a.userid = $userid and a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
        // return $query;
    }

    public function verifikasi_data_absensi($userid = null, $month = null)
    {
        if ($userid) {
            $params1 = $this->db->where('absensi.userid',$userid);
        }

        if ($month) {
            $bulan      = explode('-', $month)[1];
            $tahun      = explode('-', $month)[0];
            $params2 = $this->db->where("absensi.bulan = $bulan and absensi.tahun = $tahun");
        }

        $this->db->select('absensi.*, user.name');
        $this->db->from('site.absensi');
        $this->db->join('mpm.user', 'mpm.user.id = site.absensi.userid', 'left');
        $params1;
        $params2;
        $query = $this->db->get();
        // print_r($this->db->last_query());
        return $query;
    }

    public function data_absensi($month = null)
    {
        if ($month) {
            $bulan      = explode('-', $month)[1];
            $tahun      = explode('-', $month)[0];
            $params1    = "WHERE a.bulan = '$bulan' and a.tahun = '$tahun'";
        } else {
            $params1    = "";
        }

        $query = "
            SELECT a.id, a.userid_absensi, a.username_shd, a.name, b.no_generate_report,
            b.bulan, b.tahun, b.hadir, b.terlambat, b.flag_status, b.status, b.signature, b.total_hari_kerja
            FROM
            (
                SELECT *
                FROM mpm.`user` a
                WHERE (a.userid_absensi is not null or a.username_shd is not null) and a.active = '1'  and a.kode_company = 000 and a.username not in ('yayang', 'hendra', 'nanita')
            )a
            LEFT JOIN
            (
                SELECT *
                FROM site.absensi a
                $params1
            ) b on a.id = b.userid
            order by a.name asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_flag_terlambat_absensi_transaksi($userid, $signature)
    {

        $query = "
            select a.*
            from site.absensi_transaksi a
            where a.userid = '$userid' and a.signature = '$signature' and a.flag_terlambat = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_tabel_absensi_view_by_signature($userid, $bulan = '', $tahun = '', $signature = '')
    {
        if($userid){
            $params_userid = "where b.id = $userid";
        }else{
            $params_userid = '';
        }

        if($bulan != null){
            $params = " and year(a.waktu) = '$tahun' and month(a.waktu) = '$bulan'";
        }else{
            $params = "";
        }

        if($signature != null){
            $params_signature = "and a.signature = '$signature'";
        }else{
            $params_signature = "";
        }

        $query = "
            select *
            from site.absensi_transaksi a
            left join (
                    select *
                    from mpm.`user` a
		    )b on a.userid = b.userid_absensi
            $params_userid $params $params_signature
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $proses = $this->db->query($query);
        return $proses;
    }

    public function get_user($id = '')
    {
        if($id)
        {
            $params_id = "where c.id = $id";
        }else
        {
            $params_id = '';
        }

        $query = "
             select c.id, c.username, c.name, c.userid_absensi,c.email as email_karyawan, a.userid_verifikasi1, d.username as username_atasan, d.email as email_atasan
            from management_rpd.m_karyawan a
            LEFT JOIN(
                SELECT *
                FROM  mpm.user a
            )c on c.id = a.userid_pelaksana
            left join (
                SELECT *
                FROM mpm.user a
            )d on d.id = a.userid_verifikasi1
            $params_id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);

    }

    public function generate_report($bulan, $tahun)
    {
        $query = "
            select a.no_generate_report, max(substr(a.no_generate_report,9,3)) as urut
            from site.absensi a
            where a.bulan = '$bulan' and a.tahun = '$tahun' and a.no_generate_report is not null
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $no_current = $this->db->query($query);
        if ($no_current->num_rows() > 0) {
            $params_urut = $no_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "Absensi-00$params_urut/MPM/$bulan/$tahun";
            }elseif (strlen($params_urut) === 2) {
                $generate = "Absensi-0$params_urut/MPM/$bulan/$tahun";
            }else{
                $generate = "Absensi-$params_urut/MPM/$bulan/$tahun";
            }
        }else{
            $generate = "Absensi-001/MPM/$bulan/$tahun";
        }
        // die;
        return $generate;
    }

    public function cek_absensi_karyawan_shd($userid){

        $query = "
            select *
            from site.absensi_karyawan_shd a
            WHERE a.userid_web = $userid
        ";

        return $this->db->query($query);
    }

    public function insert_absensi_karyawan_shd($response)
    {
        for ($i=0; $i < count($response['data']) ; $i++) 
        { 
            $username_employee = $response['data'][$i]["username_employee"];
            $employee_name     = $response['data'][$i]["employee_name"];

            $query = "
                select a.username_employee
                from site.absensi_karyawan_shd a
                where a.username_employee = '$username_employee'
            ";
            
            $proses = $this->db->query($query);
            // print_r($proses->result_array());
            // die;
            
            if ($proses->num_rows() == 0) {
                $data = [
                    'username_employee'     => $username_employee,
                    'employee_name'         => $employee_name,
                    'created_at'            => $this->model_outlet_transaksi->timezone(),
                    'created_by'            => $this->session->userdata('id'),
                ];
                $this->db->insert('site.absensi_karyawan_shd', $data);

            }   
        }
        $this->update_absensi_karyawan_shd();
    }

    function update_absensi_karyawan_shd()
    {
        $query = "
            update site.absensi_karyawan_shd a
            LEFT JOIN mpm.user b
            on b.username = a.username_employee or a.employee_name = b.name
            set a.userid_web = b.id
            WHERE a.userid_web is null and b.kode_company = 000
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
        // $this->db->query("UPDATE site.absensi_karyawan_shd SET updated_at = NOW(), updated_by = $this->session->userdata('id')");
    }

    public function insert_absensi_temp_master_shd($response)
    {
        $truncate_absensi   = $this->db->truncate('site.absensi_temp_master_shd');
        for ($i=0; $i < count($response['data']) ; $i++)
        { 
            $data = [
                'username_employee'     => $response['data'][$i]["username_employee"],
                'employee_name'         => $response['data'][$i]["employee_name"],
                'attendance_date'       => $response['data'][$i]["attendance_date"],
                'company_name'          => $response['data'][$i]["company_name"],
                'site_id'               => $response['data'][$i]["site_id"],
                'site_name'             => $response['data'][$i]["site_name"],
                'jabatan_name'          => $response['data'][$i]["jabatan_name"],
                'checkin_time'          => $response['data'][$i]["checkin_time"],
                'checkout_time'         => $response['data'][$i]["checkout_time"],
                'latitude_checkin'      => $response['data'][$i]["latitude_checkin"],
                'longitude_checkin'     => $response['data'][$i]["longitude_checkin"],
                'latitude_checkout'     => $response['data'][$i]["latitude_checkout"],
                'longitude_checkout'    => $response['data'][$i]["longitude_checkout"],
                'location_checkin'      => $response['data'][$i]["location_checkin"],
                'location_checkout'     => $response['data'][$i]["location_checkout"],
                'created_at'            => $this->model_outlet_transaksi->timezone(),
                'created_by'            => $this->session->userdata('id'),
            ];

            $this->db->insert('site.absensi_temp_master_shd', $data);
            // return $this->db->insert_id();
        }      
    }

    public function insert_absensi_master_shd($tahun, $bulan)
    {
        $query = "
            insert into site.absensi_master_shd
            select a.*
            from site.absensi_temp_master_shd a
            LEFT JOIN( 
                SELECT *
                from site.absensi_master_shd a
                where year(a.attendance_date) = '$tahun' and month(a.attendance_date) = '$bulan'
            )b on a.attendance_date = b.attendance_date and a.username_employee = b.username_employee
            WHERE b.attendance_date is null
        ";

        return $this->db->query($query);
    }

    public function get_absensi_transaksi_by_signature_and_id_absensi($id, $signature)
    {
        $userid = $this->session->userdata('id');
        $query = "
            select *
            from site.absensi_transaksi a 
            where a.signature = '$signature' and a.id = '$id' and a.userid = '$userid'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_absensi($userid = '')
    {
        if ($userid) {
            $params_userid = "where a.userid = $userid";
        }else{
            $params_userid = "";
        }

        $query = "
            select a.*, b.username
            from site.absensi a left join site.master_user b 
                on a.userid = b.id
            $params_userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
        
    }

    public function get_absensi_by_verifikasi($userid_verifikasi, $bulan = '')
    {
        // if ($userid_verifikasi == 515) {
        //     $params_userid = "$userid_verifikasi or a.userid in (759, 561, 812, 444)";
        // }else{
        //     $params_userid = "$userid_verifikasi";
        // }

        if ($bulan) {
            $tahun = substr($bulan, 0, 4);
            $bulan = substr($bulan, 5, 2);
            $params_tahun = "and a.tahun = '$tahun'";
            $params_bulan = "and a.bulan = '$bulan'";
        }else{
            $params_tahun = "";
            $params_bulan = "";
        }

        $query = "
            select a.*, b.*, c.username
            from site.absensi a left join (
                select * 
                from management_rpd.m_karyawan a 
            )b on a.userid = b.userid_pelaksana left join site.master_user c 
                on a.userid = c.id 
            where b.userid_verifikasi1 = $userid_verifikasi $params_tahun $params_bulan
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_absensi_by_userid_signature_tahun_bulan($userid, $signature, $tahun, $bulan)
    {
        $query = "
            select *
            from site.absensi a 
            where 	a.userid = $userid and 
                    a.signature = '$signature' and 
                    a.tahun = '$tahun' and 
                    a.bulan = $bulan and a.flag_status in (1,2,3)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_by_month_and_userid($month, $userid)
    {
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];
        $query = "
            select a.*, b.name
            from site.absensi_transaksi a 
            left join site.master_user b
            on a.userid = b.id
            where a.userid = $userid and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan
        ";
        return $this->db->query($query);
    }

    public function get_absensi_log_perubahan_status($id)
    {
        $query = "
            select *
            from site.absensi_log_perubahan_status a 
            where a.id_absensi_transaksi = $id
            order by a.id desc
            limit 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }


    public function get_absensi_by_tahun_bulan_userid($tahun, $bulan, $userid)
    {
        $query = "
            select *
            from site.absensi a 
            where a.tahun = $tahun and a.bulan = $bulan and a.userid = $userid
        ";
        return $this->db->query($query);
    }


}