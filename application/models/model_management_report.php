<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_report extends CI_Model 
{
    public function get_master_data(){
        $query = "
            select 	a.id, a.nama_program, a.site_code, a.kodeprod, a.qty_bonus,
                    b.branch_name, b.nama_comp, c.namaprod
            from management_bonus.master_data a LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code LEFT JOIN 
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
            )c on a.kodeprod = c.kodeprod
        ";
        return $this->db->query($query);
    }

}