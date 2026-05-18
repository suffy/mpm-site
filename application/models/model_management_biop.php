<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_biop extends CI_Model 
{
    public function getAll_User($id)
    {
        if ($id) {
            $where = "and a.id = $id";
        } else {
            $where = '';
        }
        
        // $query="
        //     select a.id, a.username, a.email, a.jabatan 
        //     from mpm.user a
        //     where level not in (4,5,6) and a.supp = 000 and a.active = 1 $where
        //     order by username
        // ";

        $query = "
            select 	a.id, a.username, a.email, a.jabatan, 
                    b.userid_verifikasi1, b.userid_verifikasi2, b.userid_admin_finance, b.userid_head_finance,
                    c.username as username_verifikasi1,
                    d.username as username_verifikasi2,
                    e.username as username_admin_finance,
                    f.username as username_head_finance,
                    g.username as username_admin_biop
            from mpm.user a left join (
                select 	a.userid_pelaksana, a.userid_verifikasi1, 
                        a.userid_verifikasi2, a.userid_admin_claim, 
                        a.userid_admin_finance, a.userid_head_finance
                from management_rpd.m_karyawan a 
            )b on a.id = b.userid_pelaksana left join site.master_user c 
                on b.userid_verifikasi1 = c.id left join site.master_user d 
                on b.userid_verifikasi2 = d.id left join site.master_user e 
                on b.userid_admin_finance = e.id left join site.master_user f 
                on b.userid_head_finance = f.id left join site.master_user g
                on b.userid_admin_claim = g.id
            where a.level not in (4,5,6) and a.supp = 000 and a.active = 1 $where
            order by username
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->result() : array();
    }

    public function get_User($id)
    {   
        $query="
            select a.id, a.username, a.email, a.jabatan 
            from mpm.user a
            where a.supp = 000 and a.active = 1 and a.id = '$id'
            order by username
            ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->row() : array();
    }
    public function generate($created_at)
    {

        $bulan_now = date('m', strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            SELECT 
                a.no_ajuan,
                CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(a.no_ajuan, '/', 1), '-', -1) AS UNSIGNED) as urut,
                a.created_by, 
                a.created_at
            FROM site.biop_header a
            WHERE YEAR(a.created_at) = $tahun_now 
            AND MONTH(a.created_at) = $bulan_now 
            AND a.no_ajuan IS NOT NULL
            ORDER BY a.id DESC
            LIMIT 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $nomor_ajuan_current = $this->db->query($query);

        if ($nomor_ajuan_current->num_rows() > 0) {
            $params_urut = $nomor_ajuan_current->row()->urut + 1;

            // format dengan leading zero sampai 3 digit
            $params_urut_format = str_pad($params_urut, 4, "0", STR_PAD_LEFT);

            $generate = "BIOP-$params_urut_format/MPM/$romawi/$tahun_now";
        } else {
            $generate = "BIOP-0001/MPM/$romawi/$tahun_now";
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

    public function getAll_ajuan_biop()
    {
        // $query = "
        //     SELECT a.id, a.no_ajuan, a.userid, a.jabatan, a.from, a.to, a.status, a.nama_status, a.total_biaya, a.total_biaya_adjustment, a.pic_on_duty, a.signature, a.created_by, a.created_at, a.deleted_by, a.deleted_at,
        //     b.username as pic_name, c.username as pic_on_duty_name
        //     FROM site.biop_header a
        //     LEFT JOIN site.master_user b 
        //         on a.userid = b.id
        //     LEFT JOIN site.master_user c 
        //         on a.pic_on_duty = c.id
        // "; 

        $userid = $this->session->userdata('id');

        $query = "
            select 	a.id, a.no_ajuan, a.userid, a.jabatan, a.from, a.to, a.status, 
                    a.nama_status, a.total_biaya, a.total_biaya_adjustment, a.pic_on_duty, 
                    a.signature, a.created_by, a.created_at, a.deleted_by, a.deleted_at,
                    b.username as pic_name, c.username as pic_on_duty_name,
                    d.userid_pelaksana, d.userid_verifikasi1, d.userid_verifikasi2, 
                    d.userid_verifikasi3, d.userid_admin_claim,
                    d.userid_admin_finance, d.userid_head_finance
            from site.biop_header a left join site.master_user b 
                on a.userid = b.id left join site.master_user c 
                on a.pic_on_duty = c.id left join 
                (
                    select 	a.userid_pelaksana, a.userid_verifikasi1, a.userid_verifikasi2, a.userid_verifikasi3, a.userid_admin_claim,
                            a.userid_admin_finance, a.userid_head_finance
                    from 	management_rpd.m_karyawan a
                )d on a.userid = d.userid_pelaksana
            where 	a.userid in ($userid) or d.userid_pelaksana in ($userid) or d.userid_verifikasi1 in ($userid) or d.userid_verifikasi2 in ($userid)
                    or d.userid_verifikasi3 in ($userid) or d.userid_admin_claim in ($userid)
                    or d.userid_admin_finance in ($userid) or d.userid_head_finance in ($userid)
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->result() : array();
    }

    public function getAll_ajuan_biop_by_userid($userid)
    {
        // $query = "
        //     select a.id, a.no_ajuan, a.userid, a.jabatan, a.from, a.to, a.status, a.nama_status, a.total_biaya, a.total_biaya_adjustment, a.pic_on_duty, a.signature, a.created_by, a.created_at, a.deleted_by, a.deleted_at,
        //     b.username as pic_name, c.username as pic_on_duty_name
        //     FROM site.biop_header a
        //     LEFT JOIN site.master_user b 
        //         on a.userid = b.id
        //     LEFT JOIN site.master_user c 
        //         on a.pic_on_duty = c.id
        //     WHERE a.userid = $userid
        // ";

        $query = "
            select 	a.id, a.no_ajuan, a.userid, a.jabatan, a.from, a.to, a.status, 
                    a.nama_status, a.total_biaya, a.total_biaya_adjustment, a.pic_on_duty, 
                    a.signature, a.created_by, a.created_at, a.deleted_by, a.deleted_at,
                    b.username as pic_name, c.username as pic_on_duty_name,
                    d.userid_pelaksana, d.userid_verifikasi1, d.userid_verifikasi2, 
                    d.userid_verifikasi3, d.userid_admin_claim,
                    d.userid_admin_finance, d.userid_head_finance, a.total_biaya - a.total_biaya_adjustment as selisih
            from site.biop_header a left join site.master_user b 
                on a.userid = b.id left join site.master_user c 
                on a.pic_on_duty = c.id left join 
                (
                    select 	a.userid_pelaksana, a.userid_verifikasi1, a.userid_verifikasi2, a.userid_verifikasi3, a.userid_admin_claim,
                            a.userid_admin_finance, a.userid_head_finance
                    from 	management_rpd.m_karyawan a
                )d on a.userid = d.userid_pelaksana
            where 	(status <> 1 and (a.userid in ($userid) or d.userid_pelaksana in ($userid) or d.userid_verifikasi1 in ($userid) or d.userid_verifikasi2 in ($userid)
                    or d.userid_verifikasi3 in ($userid) or d.userid_admin_claim in ($userid)
                    or d.userid_admin_finance in ($userid) or d.userid_head_finance in ($userid)) or status = 1 and a.userid = $userid) and a.deleted_at is null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->result() : array();
    }

    public function get_ajuan_biop_by_signature($signature)
    {
        $query = "
            select  a.id, a.no_ajuan, a.userid, a.jabatan, a.from, a.to, a.status, a.nama_status, 
                    a.total_biaya, a.pic_on_duty, a.tanggal_uang_keluar, a.signature, 
                    a.digital_signature, a.admin_claim_at, a.admin_claim_by, a.admin_claim_signature, 
                    a.atasan1_at, a.atasan1_by, a.atasan1_signature, a.atasan2_at, a.atasan2_by, 
                    a.atasan2_signature, a.admin_finance_at, a.admin_finance_by, 
                    a.admin_finance_signature, a.head_finance_at, a.head_finance_by, 
                    a.head_finance_signature, a.created_by, a.created_at, a.deleted_by, 
                    a.deleted_at, b.username as pic_name, c.username as username_on_duty
            from site.biop_header a
            left join site.master_user b 
                on a.userid = b.id left join site.master_user c 
	            on a.pic_on_duty = c.id
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->row() : array();
    }

    public function insert_and_getId($table, $data) {
        $this->db->insert($table, $data);                 // insert ke tabel
        return $this->db->insert_id();                     // ambil ID terakhir
    }

    public function update($table, $params, $data) {
        $this->db->where($params);
        $this->db->update($table, $data);                 // ambil ID terakhir
    }
    
    public function getAll_kategori_biop()
    {
        $query = "
            SELECT a.id, a.nama_kategori, a.created_at, a.created_by, a.deleted_at, a.deleted_by
            FROM site.biop_kategori a
            WHERE a.deleted_at is null
            ORDER BY a.id asc, a.nama_kategori asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->result() : array();
    }

    public function get_kategori_biop_by_id($id_kategori)
    {
        $query = "
            SELECT a.id, a.nama_kategori, a.created_at, a.created_by, a.deleted_at, a.deleted_by
            FROM site.biop_kategori a
            WHERE a.deleted_at is null and a.id = $id_kategori
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->row() : array();
    }

    public function getAll_biop_detail_by_signature($signature)
    {
        $query = "
            select  a.id, a.id_biop, a.tanggal, a.id_kategori, a.nama_kategori, a.biaya, 
                    a.keterangan, a.keterangan_tempat, a.biaya_admin_biop, a.keterangan_admin_biop, 
                    a.biaya_atasan1, a.keterangan_atasan1, a.biaya_atasan2, a.keterangan_atasan2, 
                    a.biaya_admin_finance, a.keterangan_admin_finance, a.biaya_head_finance, 
                    a.keterangan_head_finance, a.attachment, a.bbm_km, a.bbm_liter, a.jamuan_tempat, 
                    a.jamuan_alamat, a.jamuan_jenis, a.jamuan_nama_perusahaan, a.jamuan_pic, 
                    a.jamuan_pic_jabatan, a.jamuan_jenis_perusahaan, a.flag_tolak, a.signature, 
                    a.created_at, a.created_by, a.deleted_at, a.deleted_by,
                    a.flag_tolak_admin_biop, a.flag_tolak_atasan1, a.flag_tolak_atasan2, 
                    a.flag_tolak_admin_finance, a.flag_tolak_head_finance
            from site.biop_detail a
            where a.deleted_at is null and a.signature = '$signature' 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->result() : array();
    }

    public function get_biop_detail_by_id($id)
    {
        $query = "
            SELECT a.id, a.id_biop, a.tanggal, a.id_kategori, a.nama_kategori, a.biaya, a.keterangan, a.keterangan_tempat, a.biaya_admin_claim, a.keterangan_admin_claim, a.biaya_atasan1, a.keterangan_atasan1, a.biaya_atasan2, a.keterangan_atasan2, a.attachment, a.bbm_km, a.bbm_liter, a.jamuan_tempat, a.jamuan_alamat, a.jamuan_jenis, a.jamuan_nama_perusahaan, a.jamuan_pic, a.jamuan_pic_jabatan, a.jamuan_jenis_perusahaan, a.signature, a.created_at, a.created_by, a.deleted_at, a.deleted_by
            FROM site.biop_detail a
            WHERE a.deleted_at is null and a.id = '$id'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->row() : array();
    }

    public function total_biaya_biop($data)
    {
         // pastikan $data ada isinya
        if (empty($data)) {
            return 0;
        }

        $total = 0;
        foreach ($data as $key) {
            $total += $key->biaya; // langsung jumlahkan tanpa array tambahan
        }

        return $total;
    }

    public function total_biaya_biop_admin_claim($data)
    {
         // pastikan $data ada isinya
        if (empty($data)) {
            return 0;
        }

        $total = 0;
        foreach ($data as $key) {
            if ($key->flag_tolak_admin_biop == 0) {
                $total += $key->biaya_admin_biop; // langsung jumlahkan tanpa array tambahan
            }
        }

        return $total;
    }

    public function total_biaya_biop_atasan1($data)
    {
         // pastikan $data ada isinya
        if (empty($data)) {
            return 0;
        }

        $total = 0;
        foreach ($data as $key) {
            if ($key->flag_tolak == 0) {
                $total += $key->biaya_atasan1; // langsung jumlahkan tanpa array tambahan
            }
        }

        return $total;
    }

    public function total_biaya_biop_atasan2($data)
    {
         // pastikan $data ada isinya
        if (empty($data)) {
            return 0;
        }

        $total = 0;
        foreach ($data as $key) {
            if ($key->flag_tolak == 0) {
                $total += $key->biaya_atasan2; // langsung jumlahkan tanpa array tambahan
            }
        }

        return $total;
    }

    public function total_biaya_biop_admin_finance($data)
    {
         // pastikan $data ada isinya
        if (empty($data)) {
            return 0;
        }

        $total = 0;
        foreach ($data as $key) {
            if ($key->flag_tolak == 0) {
                $total += $key->biaya_admin_finance; // langsung jumlahkan tanpa array tambahan
            }
        }

        return $total;
    }

    public function get_pic_on_duty_by_userid($userid)
    {
        $query = "
            SELECT a.id, a.userid_pelaksana as userid, a.userid_verifikasi1, a.userid_verifikasi2, a.userid_admin_claim, a.userid_admin_finance, a.userid_head_finance
            FROM management_rpd.m_karyawan a
            where a.userid_pelaksana = $userid
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->row() : array();
    }

    public function get_pic_on_duty_by_userid_and_status($userid, $status)
    {
        $query = "
            SELECT a.id, a.userid_pelaksana as userid, a.userid_verifikasi1, a.userid_verifikasi2, a.userid_admin_claim, a.userid_admin_finance, a.userid_head_finance
            FROM management_rpd.m_karyawan a
            where a.userid_pelaksana = $userid
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        if ($status == 'pending user') {
            $userid = $this->db->query($query)->row('userid');
        } elseif ($status == 'pending admin biop') {
            $userid = $this->db->query($query)->row('userid_admin_claim');
        } elseif ($status == 'pending atasan 1') {
            $userid = $this->db->query($query)->row('userid_verifikasi1');
        } elseif ($status == 'pending atasan 2') {
            $userid = $this->db->query($query)->row('userid_verifikasi2');
        } elseif ($status == 'pending admin finance') {
            $userid = $this->db->query($query)->row('userid_admin_finance');
        } elseif ($status == 'pending head finance') {
            $userid = $this->db->query($query)->row('userid_head_finance');
        } else {
            $userid = array();
        }
        
        return $userid;
    }

    public function get_data_biop_grouped_tanggal($signature) 
    {
        $query = "
            select  a.id_biop,
                    a.tanggal,
                    GROUP_CONCAT(a.nama_kategori SEPARATOR ', ') AS keterangan_biaya,
                    SUM(CASE WHEN a.nama_kategori = 'tol' THEN a.biaya_head_finance ELSE 0 END) AS tol,
                    SUM(CASE WHEN a.nama_kategori = 'parkir' THEN a.biaya_head_finance ELSE 0 END) AS parkir,
                    MAX(a.bbm_km) AS bbm_km,
                    MAX(a.bbm_liter) AS bbm_liter,
                    SUM(CASE WHEN a.nama_kategori = 'bbm' THEN a.biaya_head_finance ELSE 0 END) AS bbm_rp,
                    SUM(CASE WHEN a.nama_kategori = 'makan' THEN a.biaya_head_finance ELSE 0 END) AS makan,
                    SUM(CASE WHEN a.nama_kategori = 'jamuan' THEN a.biaya_head_finance ELSE 0 END) AS jamuan,
                    SUM(CASE WHEN a.nama_kategori = 'meeting' THEN a.biaya_head_finance ELSE 0 END) AS meeting,
                    SUM(CASE WHEN a.nama_kategori = 'hotel' OR a.nama_kategori = 'transportasi' OR a.nama_kategori = 'perjalanan dinas' THEN a.biaya_head_finance ELSE 0 END) AS perjalanan_dinas,
                    SUM(CASE WHEN a.nama_kategori = 'service kendaraan' THEN a.biaya_head_finance ELSE 0 END) AS service_kendaraan,
                    SUM(CASE WHEN a.nama_kategori = 'stationery' THEN a.biaya_head_finance ELSE 0 END) AS stationery,
                    SUM(CASE WHEN a.nama_kategori = 'lain-lain' THEN a.biaya_head_finance ELSE 0 END) AS lain,
                    MAX(a.keterangan_tempat) as keterangan_tempat
            from site.biop_detail a
            where a.signature = '$signature' and a.deleted_at is null and a.flag_tolak_head_finance = 0
            group by a.id_biop, a.tanggal
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->result() : array();
    }

    public function get_biop_header_join_detail($id)
    {
        $query = "
            select 	a.no_ajuan, a.userid, c.username, a.jabatan, a.from, a.to, a.nama_status, a.pic_on_duty, 
                    d.username as username_on_duty,
                    b.*
            from site.biop_header a left join (
                select 	a.id_biop, a.tanggal, a.nama_kategori, a.biaya as nominal_user, 
                        a.keterangan, a.keterangan_tempat, 
                        a.biaya_admin_biop as nominal_admin, a.keterangan_admin_biop, if(a.flag_tolak_admin_biop=1,'no',if(a.flag_tolak_admin_biop=0,'ok',null)) as flag_admin,
                        a.biaya_atasan1 as nominal_atasan1, a.keterangan_atasan1, if(a.flag_tolak_atasan1=1,'no',if(a.flag_tolak_atasan1=0,'ok',null)) as flag_atasan1,
                        a.biaya_atasan2 as nominal_atasan2, a.keterangan_atasan2, if(a.flag_tolak_atasan2=1,'no',if(a.flag_tolak_atasan2=0,'ok',null)) as flag_atasan2,
                        a.biaya_admin_finance as nominal_finance, a.keterangan_admin_finance as keterangan_finance, if(a.flag_tolak_admin_finance=1,'no',if(a.flag_tolak_admin_finance=0,'ok',null)) as flag_finance,
                        a.biaya_head_finance as nominal_head_finance, a.keterangan_head_finance, if(a.flag_tolak_head_finance=1,'no',if(a.flag_tolak_head_finance=0,'ok',null)) as flag_head_finance,
                        a.bbm_km, a.bbm_liter, 
                        a.jamuan_tempat, a.jamuan_alamat, a.jamuan_jenis, a.jamuan_nama_perusahaan,
                        a.jamuan_pic, a.jamuan_pic_jabatan, a.jamuan_jenis_perusahaan
                from site.biop_detail a 
                where a.id_biop = $id
            )b on a.id = b.id_biop left join site.master_user c 
                on a.userid = c.id left join site.master_user d 
                on a.pic_on_duty = d.id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function update_tanggal_uang_keluar_biop($signature, $tanggal_uang_keluar)
    {
        $query = "
            update site.biop_header a
            set a.tanggal_uang_keluar = '$tanggal_uang_keluar'
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_approval_by_userid($id)
    {
        if ($id) {
            $params_id = "and a.userid_pelaksana = $id";
        }else{
            $params_id = "";
        }
        $query = "
                SELECT 
                    a.userid_pelaksana, b.username AS username_pelaksana, b.email AS email_pelaksana,
                    a.userid_verifikasi1, c.username AS username_verifikasi1, c.email AS email_atasan_1,
                    a.userid_verifikasi2, d.username AS username_verifikasi2, d.email AS email_atasan_2,
                    a.userid_admin_claim, e.username AS username_admin_claim, e.email AS email_admin_claim,
                    a.userid_admin_finance, f.email AS email_admin_finance,
                    a.userid_head_finance, g.email AS email_head_finance
                FROM management_rpd.m_karyawan a
                LEFT JOIN site.master_user b ON a.userid_pelaksana     = b.id
                LEFT JOIN site.master_user c ON a.userid_verifikasi1   = c.id
                LEFT JOIN site.master_user d ON a.userid_verifikasi2   = d.id
                LEFT JOIN site.master_user e ON a.userid_admin_claim   = e.id
                LEFT JOIN site.master_user f ON a.userid_admin_finance = f.id
                LEFT JOIN site.master_user g ON a.userid_head_finance  = g.id
                WHERE b.active = 1 $params_id
                ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    // public function get_pengeluaran_biop_by_signature($signature)
    // {
    //     $query = "
    //        SELECT 
    //         SUM(IF(a.nama_kategori IN ('tol','bbm','parkir'), a.biaya_head_finance, 0)) AS biaya_bbm,
    //         SUM(IF(a.nama_kategori = 'jamuan', a.biaya_head_finance, 0)) AS biaya_jamuan,
    //         SUM(IF(a.nama_kategori = 'meeting', a.biaya_head_finance, 0)) AS biaya_meeting,
    //         SUM(IF(a.nama_kategori IN ('makan','transportasi','hotel','perjalanan dinas'), a.biaya_head_finance, 0)) AS biaya_perjalanan_dinas,
    //         SUM(IF(a.nama_kategori = 'service kendaraan', a.biaya_head_finance, 0)) AS biaya_service_kendaraan,
    //         SUM(IF(a.nama_kategori = 'stationery', a.biaya_head_finance, 0)) AS biaya_stationery,
    //         SUM(IF(a.nama_kategori = 'lain_lain', a.biaya_head_finance, 0)) AS biaya_lain_lain,

    //         GROUP_CONCAT(IF(a.nama_kategori IN ('tol','bbm','parkir'), a.keterangan, NULL) SEPARATOR ', ') AS ket_bbm,
    //         GROUP_CONCAT(IF(a.nama_kategori = 'jamuan', a.keterangan, NULL) SEPARATOR ', ') AS ket_jamuan,
    //         GROUP_CONCAT(IF(a.nama_kategori = 'meeting', a.keterangan, NULL) SEPARATOR ', ') AS ket_meeting,
    //         GROUP_CONCAT(IF(a.nama_kategori IN ('makan','transportasi','hotel','perjalanan dinas'), a.keterangan, NULL) SEPARATOR ', ') AS ket_perjalanan_dinas,
    //         GROUP_CONCAT(IF(a.nama_kategori = 'service kendaraan', a.keterangan, NULL) SEPARATOR ', ') AS ket_service_kendaraan,
    //         GROUP_CONCAT(IF(a.nama_kategori = 'stationery', a.keterangan, NULL) SEPARATOR ', ') AS ket_stationery,
    //         GROUP_CONCAT(IF(a.nama_kategori = 'lain_lain', a.keterangan, NULL) SEPARATOR ', ') AS ket_lain_lain

    //     FROM site.biop_detail a
    //     WHERE a.signature = '$signature' AND a.deleted_at IS NULL AND a.flag_tolak_head_finance = 0;

    //     ";

    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";
    //     // die;
    //     $data = $this->db->query($query);
    //     return $data ? $this->db->query($query)->row() : array();
    // }

    public function total_biaya_biop_by_signature($signature, $role)
    {
        if($role == 'admin_claim'){
            $params_biaya = 'sum(a.biaya_admin_biop) AS total_biaya';
            $params_role = 'and a.flag_tolak_admin_biop = 0';
        }elseif($role == 'atasan1'){
            $params_biaya = 'sum(a.biaya_atasan1) AS total_biaya';
            $params_role = 'and a.flag_tolak_atasan1 = 0';
        }elseif($role == 'atasan2'){
            $params_biaya = 'sum(a.biaya_atasan2) AS total_biaya';
            $params_role = 'and a.flag_tolak_atasan2 = 0';
        }elseif($role == 'admin_finance'){
            $params_biaya = 'sum(a.biaya_admin_finance) AS total_biaya';
            $params_role = 'and a.flag_tolak_admin_finance = 0';
        }elseif($role == 'head_finance'){
            $params_biaya = 'sum(a.biaya_head_finance) AS total_biaya';
            $params_role = 'and a.flag_tolak_head_finance = 0';
        }else{
            $params_role = '';
        }
        $query = "
            SELECT 
                $params_biaya
            FROM site.biop_detail a
            WHERE a.signature = '$signature' AND a.deleted_at IS NULL $params_role;
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $data = $this->db->query($query);
        return $data ? $this->db->query($query)->row('total_biaya') : 0;
    }

    // public function get_biop_detail_raw($signature)
    // {
    //     return $this->db
    //         ->where('signature', $signature)
    //         ->where('deleted_at', null)
    //         ->get('site.biop_detail')
    //         ->result();
    // }

    public function get_biop_detail_raw($signature)
    {
        $sql = "
            SELECT *
            FROM site.biop_detail a
            WHERE a.signature = '$signature' AND a.deleted_at IS NULL
        ";

        return $this->db->query($sql, array($signature))->result();
    }




} ?>