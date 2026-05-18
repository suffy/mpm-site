<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_purchase_requistion extends CI_Model 
{   
    public function purchase_requistion_asset($userid = ''){

        if ($userid == '') {
            $where = '';
        } else {
            $where = "where a.created_by = $userid";
        }
        
        $sql = "
            SELECT a.*, b.username FROM site.t_asset_purchase_requistion a 
            LEFT JOIN mpm.user b on a.created_by = b.id
            $where
            order by a.id desc
        ";

        return $this->db->query($sql);
    }

    public function purchase_requistion_asset_by_id($id = ''){
        if ($id != '') {
            $where = "where a.id = '$id' or a.no_pr = '$id'";
        } else {
            $where = "";
        }

        $sql = "
            SELECT a.*, b.username
            FROM site.t_asset_purchase_requistion a 
            LEFT JOIN mpm.user b on a.created_by = b.id
            $where
        ";

        return $this->db->query($sql);
    }

    // public function purchase_requistion_asset_by_nopr($nopr){
    //     $sql = "
    //         SELECT a.*, b.username
    //         FROM site.t_asset_purchase_requistion a 
    //         LEFT JOIN mpm.user b on a.created_by = b.id
    //         where a.no_pr = '$nopr'
    //     ";

    //     return $this->db->query($sql);
    // }

    public function purchase_requistion_asset_by_m_karyawan($userid){
        $sql = "
            SELECT a.*, b.username FROM site.t_asset_purchase_requistion a 
            LEFT JOIN mpm.user b on a.created_by = b.id 
            INNER JOIN site.m_karyawan c on a.created_by = c.userid
            where c.atasan_id = $userid
            order by a.id desc
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        return $this->db->query($sql);
    }

    public function generate_no_pr(){
        $cek_jumlah = $this->db->get("site.t_asset_purchase_requistion")->num_rows();
        if (!$cek_jumlah) {
            $reserve_nomor_params = "MPM_PR/" . date('Y') . date('m') . "-001";
        } else {
            // echo "ada";
            $sql = "
                select right(a.no_pr,3) as urut
                from site.t_asset_purchase_requistion a
                GROUP BY a.no_pr
                ORDER BY right(a.no_pr,3) desc
                limit 1
            ";

            // MPM_LBM/202209-099

            // URUT = 099
            // URUT = 99
            // RESERVE_NOMOR = 99 + 1 = 100
            // RESERVE_NOMOR_PARAMS = MPM_LBM/202209-002
            // RESERVE_NOMOR_PARAMS = MPM_LBM/202209-100


            $proses = $this->db->query($sql)->row();
            $reserve_nomor = $proses->urut + 1;
            if (strlen($reserve_nomor) === 1) {
                $reserve_nomor_params = "MPM_PR/" . date('Y') . date('m') . "-00" . $reserve_nomor;
            } else if (strlen($reserve_nomor) === 2) {
                $reserve_nomor_params = "MPM_PR/" . date('Y') . date('m') . "-0" . $reserve_nomor;
            } else {
                $reserve_nomor_params = "MPM_PR/" . date('Y') . date('m') . "-" . $reserve_nomor;
            }
            // echo "reserve_nomor_params : ".$reserve_nomor_params;
        }

        // echo "reserve_nomor_params : ".$reserve_nomor_params;
        // die;

        return $reserve_nomor_params;
    }

    public function simpan($table,$data) {
        $this->db->insert($table,$data);
    }
}?>