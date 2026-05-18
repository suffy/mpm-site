<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_portal_raw extends CI_Model
{
    public function get_list_raw($params_status_principal = "", $supp = "")
    {
        if ($params_status_principal == 1) {
            $supp = $supp;
        } else {
            $supp = $this->session->userdata('supp');
        }

        $id = $this->session->userdata('id');

        if ($supp == 000) { // ketika user mpm
            $sql = "
                SELECT a.userid, a.region, b.nama, b.target, b.target_csv, b.keterangan, c.NAMASUPP 
                FROM site.map_akses_region a
                INNER JOIN db_raw.t_list_raw b on a.region = b.region
                LEFT JOIN mpm.tabsupp c on b.supp = c.supp 
                WHERE userid = $id and a.status = 1 and b.status = 1 and b.supp != 901 and target_csv is not null
                order by b.urutan asc, b.id desc
            ";
        } else {
            $sql = "
                SELECT a.userid, a.region, b.nama, b.target, b.target_csv, b.keterangan, c.NAMASUPP 
                FROM site.map_akses_region a
                INNER JOIN db_raw.t_list_raw b on a.region = b.region
                LEFT JOIN mpm.tabsupp c on b.supp = c.supp 
                WHERE userid = $id and a.status = 1 and b.status = 1 and b.supp in ($supp) and target_csv is not null
                order by b.urutan asc, b.id desc
            ";
        }

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";8
        // die;
        return $this->db->query($sql);
    }

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

}?>