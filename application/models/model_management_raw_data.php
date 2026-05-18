<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_raw_data extends CI_Model
{
    public function get_akses_principal_by_userid($id)
    {
        $query = "
            select *
            from site.detail_principal a
            where a.userid = $id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_list_raw($params_status_principal = "", $supp = "", $keterangan = "")
    {
        if ($params_status_principal == 1) {
            $supp = $supp;
        } else {
            $supp = $this->session->userdata('supp');
        }
        if ($keterangan == 'Closing Bulanan'){
            $params_order = 'order by b.created_at desc';
        }else{
            $params_order = 'order by b.urutan asc';
        }

        $id = $this->session->userdata('id');

        if ($supp == 000) { // ketika user mpm
            // $sql = "
            //     SELECT b.id, a.userid, a.region, b.nama, b.target, b.target_csv, b.keterangan, c.NAMASUPP 
            //     FROM site.map_akses_region a
            //     INNER JOIN db_raw.t_list_raw b on a.region = b.region
            //     LEFT JOIN mpm.tabsupp c on b.supp = c.supp 
            //     WHERE userid = $id and a.status = 1 and b.status = 1 and b.supp != 901 and target_csv is not null
            //     order by b.urutan asc, b.id desc
            // ";

            $sql = "
                SELECT b.id,a.userid, a.region, b.nama, b.target, b.target_csv, b.keterangan, c.NAMASUPP, d.filename, b.created_at,
                    IF(b.created_at >= DATE_SUB(CURDATE(), INTERVAL 5 DAY),'new','') AS status, d.signature
                FROM site.map_akses_region a
                INNER JOIN db_raw.t_list_raw b on a.region = b.region
                LEFT JOIN mpm.tabsupp c on b.supp = c.supp 
                LEFT JOIN (
                    SELECT a.*
                    FROM db_raw.attachment_t_list_raw a
                    INNER JOIN (
                        SELECT id_t_list_raw, MAX(id) AS max_id
                        FROM db_raw.attachment_t_list_raw
                        GROUP BY id_t_list_raw
                    )b ON a.id_t_list_raw = b.id_t_list_raw AND a.id = b.max_id
                ) d ON b.id = d.id_t_list_raw
                WHERE userid = $id and a.status = 1 and b.status = 1 and b.supp != 901 and target_csv is not null and b.KETERANGAN = '$keterangan'
                $params_order
            ";
        } else {
            $sql = "
                SELECT b.id, a.userid, a.region, b.nama, b.target, b.target_csv, b.keterangan, c.NAMASUPP, d.filename, b.created_at,
                    IF(b.created_at >= DATE_SUB(CURDATE(), INTERVAL 5 DAY),'new','') AS status, d.signature 
                FROM site.map_akses_region a
                INNER JOIN db_raw.t_list_raw b on a.region = b.region
                LEFT JOIN mpm.tabsupp c on b.supp = c.supp
                LEFT JOIN (
                    SELECT a.*
                    FROM db_raw.attachment_t_list_raw a
                    INNER JOIN (
                        SELECT id_t_list_raw, MAX(id) AS max_id
                        FROM db_raw.attachment_t_list_raw
                        GROUP BY id_t_list_raw
                    )b ON a.id_t_list_raw = b.id_t_list_raw AND a.id = b.max_id
                ) d ON b.id = d.id_t_list_raw
                WHERE userid = $id and a.status = 1 and b.status = 1 and b.supp in ($supp) and target_csv is not null and b.KETERANGAN = '$keterangan'
                order by b.urutan asc, b.created_at desc
            ";
        }

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        // die;
        return $this->db->query($sql);
    }

    // public function get_tbl_tabcomp($username)
    // {
    //     $sql = "
    //         select a.branch_name, a.nama_comp, a.kode_comp, a.site_code
    //         from mpm.tbl_tabcomp a
    //         where a.kode_comp = '$username'
    //     ";
    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";
    //     return $this->db->query($sql);
    // }

    public function insert_attachment_t_list_raw($data)
    {
        $proses = $this->db->insert('db_raw.attachment_t_list_raw', $data);
        return $this->db->insert_id();
    }

    public function get_user_by_id($id)
    {
        $query = "
            select *
            from site.master_user a
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_attachment_t_list_raw($signature)
    {
        $query = "
            select *
            from db_raw.attachment_t_list_raw a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    // public function get_attachment_t_list_raw_by_id($id)
    // {
    //     $query = "
    //         select *
    //         from db_raw.attachment_t_list_raw a
    //         left join db_raw.t_list_raw b
    //         on a.id_t_list_raw = b.id
    //         where a.id_t_list_raw = $id
    //     ";
    //     return $this->db->query($query);
    // }
}