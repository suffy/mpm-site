<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model_management_karyawan extends CI_Model {

    public function get_site_code($username) 
    {
        $query = "
            select a.site_code
            from site.karyawan a 
            WHERE a.username_web = '$username'
        ";

        // $query = "
        //     select b.site_code, b.branch_name, b.sub
        //     from mpm.user a 
        //     left join mpm.tbl_tabcomp b 
        //     on a.company_site_code = b.site_code
        //     WHERE a.id = $userid
        // ";
        
        $this->db->query($query);
        // echo $this->db->last_query(); die;
        return $this->db->query($query);
    }
    
    public function get_username($username) 
    {
        if ($username == 'ratri' || $username == 'millax') {
            $params_username = "";
        }else {
            $params_username = " and username = '$username' ";
        }

        $query = "
            SELECT * 
            from mpm.user a 
            where active = '1' $params_username and (kode_lang is null or kode_lang = '')
        ";
        
        $this->db->query($query);
        // echo $this->db->last_query(); die;
        return $this->db->query($query)->result();
    }

    public function get_sub($site_code)
    {
        $query = "
            SELECT a.sub, a.nama_comp
            from mpm.tbl_tabcomp a 
            where site_code = '$site_code' 
        ";
        return $this->db->query($query);
    }
    public function get_sub_company($site_code) 
    {
        // echo "<!-- Site Code di model: $site_code -->";die;
        $query = "
            SELECT a.sub
            from mpm.tbl_tabcomp a 
            where site_code = '$site_code'
        ";
        return $this->db->query($query);

    }

    // public function get_all_karyawan($sub = '', $username, $form_search) 
    // {
    //     if($form_search != null) {
    //         $sub = $form_search;
    //     }

    //     if ($sub == '') {
    //         $params_sub = "where left(a.site_code,3) = '$username' ";
    //     }elseif($username == 'ratri' || $username == 'milla'){
    //         $params_sub = "";
    //     }else {
    //         $params_sub = " where b.sub = '$sub' ";
    //     }
    //     $query = "
    //         SELECT a.*, b.branch_name
    //         from site.karyawan a
    //         left join mpm.tbl_tabcomp b 
    //         on a.site_code = b.site_code
    //         $params_sub
    //     ";
    //     echo '<pre>';
    //     echo $query;
    //     echo '</pre>';

    //     return $this->db->query($query)->result();
    //     // echo $this->db->last_query(); die;
    // }

    public function get_all_karyawan($sub = '', $username, $form_search) 
    {
        // 1. Prioritaskan form_search jika ada isinya
        if (!empty($form_search)) {
            $sub = $form_search;
        }

        $params_sub = ""; // Default kosong (untuk Ratri/Milla agar bisa lihat semua)

        // 2. Logika Filter
        if ($username == 'ratri' || $username == 'millax') {
            // Jika Ratri/Milla login:
            // Cukup cek apakah dia lagi pakai form_search atau tidak
            if ($form_search != null) {
                $params_sub = " and b.sub = '$sub' ";
            } 
            // Jika $sub kosong, $params_sub tetap "" (maka query akan ambil semua data)
        } else {
            // Jika USER BIASA login:
            if ($sub != '') {
                // User biasa filter berdasarkan pencarian
                $params_sub = " and b.sub = '$sub' ";
            } else {
                // User biasa filter berdasarkan username (site_code) jika pencarian kosong
                $params_sub = " and LEFT(a.site_code, 3) = '$username' ";
            }
        }

        $query = "
            SELECT a.*, b.branch_name, b.nama_comp
            FROM site.karyawan a
            LEFT JOIN mpm.tbl_tabcomp b ON a.site_code = b.site_code
            where b.active = 1 $params_sub and a.deleted_at is null
        ";

        // Debugging
        // echo '<pre>' . $query . '</pre>';die;
        return $this->db->query($query);
    }

    public function get_karyawan_by_signature($signature) 
    {
        $query = "
            SELECT a.*, c.nama_comp, c.branch_name, b.id as id_pendidikan, b.pendidikan_terakhir, b.institusi_pendidikan, b.jurusan
            FROM site.karyawan a
            left join site.m_pendidikan b
            on a.id = b.id_karyawan
            LEFT JOIN mpm.tbl_tabcomp c 
            ON a.site_code = c.site_code
            WHERE a.signature = '$signature'
        ";
        // echo '<pre>';
        // echo $query;
        // echo '</pre>';
        // die;
        $result = $this->db->query($query);
        // echo $this->db->last_query(); die;
        
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function get_pendidikan_by_karyawan_id($id_karyawan) {
        $query = "
            SELECT *
            FROM site.m_pendidikan
            WHERE id_karyawan = $id_karyawan and deleted is null
        ";

        $result = $this->db->query($query);
        
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function get_keluarga_by_karyawan_id($id_karyawan) {
        $query = "
            SELECT *
            FROM site.m_keluarga
            WHERE id_karyawan = $id_karyawan and deleted is null
        ";

        $result = $this->db->query($query);
        
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function get_asuransi_by_karyawan_id($id_karyawan) {
        $query = "
            SELECT *
            FROM site.m_asuransi
            WHERE id_karyawan = $id_karyawan and deleted is null
        ";

        $result = $this->db->query($query);
        
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function insert_karyawan($data) 
    {
        $this->db->insert('site.karyawan', $data);
        return $this->db->insert_id();
    }

    public function update_karyawan($signature, $data_karyawan) 
    {
        $this->db->where('signature', $signature);
        return $this->db->update('site.karyawan', $data_karyawan);
    }

    public function softDelete_m_pendidikan($id) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_pendidikan', ['deleted' => 1]);
    }

    public function update_m_pendidikan($id, $data) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_pendidikan', $data);
    }

    public function insert_m_pendidikan($data) 
    {
        return $this->db->insert('site.m_pendidikan', $data);
    }
    

    public function softDelete_m_keluarga($id) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_keluarga', ['deleted' => 1]);
    }

    public function update_m_keluarga($id, $data) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_keluarga', $data);
    }

    public function insert_m_keluarga($data) 
    {
        return $this->db->insert('site.m_keluarga', $data);
    }

    public function softDelete_m_asuransi($id) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_asuransi', ['deleted' => 1]);
    }

    public function update_m_asuransi($id, $data) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_asuransi', $data);
    }

    public function insert_m_asuransi($data) 
    {
        return $this->db->insert('site.m_asuransi', $data);
    }
    
    public function get_dp_by_perusahaan($sub)
    {
        $query = "
            SELECT *
            FROM mpm.tbl_tabcomp a
            WHERE a.sub = '$sub' AND active = 1
        ";
        
        return $this->db->query($query)->result();
    }

    public function check_ktp_exists($ktp) 
    {
        // Menggunakan query SQL manual
        $sql = "
            SELECT nomor_ktp 
            FROM site.karyawan 
            WHERE nomor_ktp = '$ktp' and nomor_ktp is not null and nomor_ktp != ''
            LIMIT 1";
        
        // Eksekusi query dengan binding (?) agar aman dari SQL Injection
        $query = $this->db->query($sql, array($ktp));

        if ($query->num_rows() > 0) {
            return true; // Data ditemukan (duplikat)
        } else {
            return false; // Data belum ada
        }
    }

    public function get_data_by_id($id) {
        $query = "
            select *
            from site.karyawan a 
            left join site.m_pendidikan b 
            on a.id = b.id_karyawan and b.deleted is null
            left join site.m_keluarga c 
            on a.id = c.id_karyawan and c.deleted is null
            and a.email_perusahaan is not null and a.tanggal_mulai_kerja is not null
            and b.id_karyawan is not null and c.id_karyawan is not null
            WHERE a.id = '$id' 
            ";

        // $query = "
        //     select *
        //     from site.karyawan a 
        //     WHERE a.id = '$id' and a.email_perusahaan is not null 
        // ";

        // echo '<pre>';
        // echo $query;
        // echo '</pre>';
        // die;

        return $this->db->query($query);
    }

    public function update_karyawan_by_id($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('site.karyawan', $data);
    }

    public function get_atasan($username) 
    {
        $query = "
            SELECT a.userid_pelaksana, a.userid_verifikasi1, b.name as name_pelaksana, c.name as name_verifikasi1
            FROM management_rpd.m_karyawan a
            LEFT JOIN (
                        SELECT a.id, a.username, a.active, a.name
                        FROM mpm.`user` a
            )b ON a.userid_pelaksana = b.id
            LEFT JOIN (
                        SELECT a.id, a.username, a.active, a.name 
                        FROM mpm.`user` a
            )c ON a.userid_verifikasi1 = c.id
            WHERE b.username in ('$username')
        ";
        
        $this->db->query($query);
        // echo $this->db->last_query(); die;
        return $this->db->query($query);
    }

    public function get_username_by_id_karyawan($id_karyawan) 
    {
        $query = "
            SELECT username_web
            FROM site.karyawan 
            WHERE id = $id_karyawan
        ";
        
        $this->db->query($query);
        // echo $this->db->last_query(); die;
        return $this->db->query($query);
    }

    function update_user_by_username_karyawan($username, $data_user) 
    {
        $this->db->where('username', $username);
        return $this->db->update('mpm.user', $data_user);
        
    }

    public function get_user($username) 
    {
        $query = "
            SELECT company_site_code, username, name
            FROM mpm.user
            WHERE username = '$username'
        ";
        // echo '<pre>';
        // echo $query;
        // echo '</pre>';
        return $this->db->query($query);
    }

    public function get_ho($sub_company) 
    {
        $query = "
            SELECT *
            FROM mpm.tbl_tabcomp
            WHERE sub = '$sub_company' AND active = 1 and status_ho = 1
        ";
        // echo '<pre>';
        // echo $query;
        // echo '</pre>';
        return $this->db->query($query);
    }

    public function insert_reimbursement($data) 
    {
        $this->db->insert('site.reimbursement', $data);
        return $this->db->insert_id();
    }

    public function insert_reimbursement_detail($data) 
    {
        $this->db->insert('site.reimbursement_detail', $data);
        return $this->db->insert_id();
    }

    public function get_kategori_reimbursement() 
    {
        $query = "
            SELECT *
            FROM site.reimbursement_kategori
            ORDER BY nama_kategori ASC
        ";

        return $this->db->query($query)->result();
    }

    public function get_id_karyawan_by_username($username) 
    {
        $query = "
            SELECT id, username_web
            FROM site.karyawan
            WHERE username_web = '$username'
            LIMIT 1
        ";
        // echo '<pre>';
        // echo $query;
        // echo '</pre>';die;
        return $this->db->query($query);
    }

    public function get_karyawan_aktif($username = null) 
    {
        if ($this->session->userdata('username') == 'nanita' || $this->session->userdata('username') == 'ratri') { // Ratri, Milla, Bu Nanita, bisa lihat semua data karyawan
            $params_username = "";
        } else {
            $params_username = "AND username_web = '$username' ";
        }

        $query = "
            SELECT *
            FROM site.karyawan
            WHERE deleted_at IS NULL $params_username and site_code = 'MPMHO'
            ORDER BY nama_lengkap ASC
        ";

        // $this->db->query($query);
        // echo '<pre>';
        // echo $query;
        // echo '</pre>';die;
        return $this->db->query($query)->result();
    }

    public function get_history_reimbursement_by_karyawan($id_karyawan, $start_date = null, $end_date = null) 
    {
        if ($this->session->userdata('username') == 'nanita' || $this->session->userdata('username') == 'ratri') { // Ratri, Milla, Bu Nanita, bisa lihat semua data reimbursement
            $params_id_karyawan = "";
        }else {
            $params_id_karyawan = "and r.id_karyawan = $id_karyawan";
        }

        if ($start_date == '' && $end_date == '') {
            $params_date = "";
        } else {
            $start = date('Y-m-01', strtotime($start_date));
            $end   = date('Y-m-t', strtotime($end_date)); // t = last day of month
            $params_date = "and r.tanggal_pengajuan between '$start' and '$end'";
        }

        $query = "select a.*, b.nama_lengkap, b.departement, b.divisi, b.job_level
                from (
                SELECT r.id, r.no_pengajuan, r.id_karyawan, r.tanggal_pengajuan, r.total, r.status, d.file_nota, k.nama_kategori
                FROM site.reimbursement r
                LEFT JOIN site.reimbursement_detail d
                        ON d.id_reimbursement = r.id
                        AND d.deteled_at IS NULL
                left join site.reimbursement_kategori k
                ON d.id_kategori = k.id
                WHERE r.deleted_at IS NULL $params_id_karyawan $params_date
                )a left join site.karyawan b
                on a.id_karyawan = b.id
                GROUP BY a.id, a.tanggal_pengajuan, a.total, a.status
                ORDER BY a.tanggal_pengajuan DESC
        ";

        // echo '<pre>';
        // echo $query;
        // echo '</pre>';
        return $this->db->query($query)->result();
    }

    public function count_reimbusement() 
    {

        $query = "
            SELECT COUNT(*) as total
            FROM site.reimbursement r
            WHERE r.deleted_at IS NULL and r.status = 1
         ";

        // echo '<pre>';
        // echo $query;
        // echo '</pre>';
        return $this->db->query($query)->row()->total;
    }

    public function get_export_reimbursement($start, $end)
    {
        $query = "
            SELECT
                r.id_karyawan,
                k.nama_lengkap,
                MONTH(rd.tanggal_nota) bulan,
                rd.id_kategori,
                SUM(rd.nominal) total,
                '' as budget

            FROM site.reimbursement_detail rd
            JOIN site.reimbursement r
                ON r.id = rd.id_reimbursement
            JOIN site.karyawan k
                ON k.id = r.id_karyawan

            WHERE rd.tanggal_nota BETWEEN '$start' AND '$end'
            AND r.status = 2

            GROUP BY
                r.id_karyawan,
                MONTH(rd.tanggal_nota),
                rd.id_kategori

            ORDER BY k.nama_lengkap ASC
        ";

        // echo '<pre>';
        // echo $query;
        // echo '</pre>';die;
        return $this->db->query($query)->result();
    }

    public function get_reimbursement_by_id($id_reimbursement) 
    {
        $query = "
            select a.no_pengajuan, a.id, a.id_karyawan, a.tanggal_pengajuan, a.total, a.`status`, b.id_kategori, b.keterangan, c.nama_kategori, d.nama_lengkap
            from site.reimbursement a 
            left join site.reimbursement_detail b 
            on a.id = b.id_reimbursement
            left join site.reimbursement_kategori c 
            on b.id_kategori = c.id
            left join site.karyawan d
            on a.id_karyawan = d.id
            WHERE a.id = '$id_reimbursement'
        ";

        return $this->db->query($query);
    }

    public function generate($created_at)
    {

        $bulan_now = date('m', strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            SELECT 
                a.no_pengajuan,
                CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(a.no_pengajuan, '/', 1), '-', -1) AS UNSIGNED) as urut,
                a.created_by, 
                a.created_at
            FROM site.reimbursement a
            WHERE YEAR(a.created_at) = $tahun_now 
            AND MONTH(a.created_at) = $bulan_now 
            AND a.no_pengajuan IS NOT NULL
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

            $generate = "RBM-$params_urut_format/MPM/$romawi/$tahun_now";
        } else {
            $generate = "RBM-0001/MPM/$romawi/$tahun_now";
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

}