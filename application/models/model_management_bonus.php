<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_bonus extends CI_Model 
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

    public function get_data_tracking($site_code, $nama_program){

        $query = "
        select 	a.nama_program, a.site_code, a.kodeprod, a.namaprod, b.qty_bonus, c.branch_name, c.nama_comp, 
                if(d.qty_penggantian is null, 0, d.qty_penggantian) as qty_penggantian,
                b.qty_bonus - if(d.qty_penggantian is null, 0, d.qty_penggantian) as sisa, a.closed, a.signature
        from 
        (
            select a.nama_program, a.site_code, b.kodeprod, b.namaprod, a.closed, a.signature
            from 
            (
                select a.nama_program, a.site_code, a.kodeprod, a.closed, a.signature
                from management_bonus.master_data a 
                where a.site_code = '$site_code' and a.nama_program = '$nama_program'
                GROUP BY a.site_code
            )a join (
                    select a.kodeprod, a.namaprod
                    from mpm.tabprod a 
                    where a.supp = 005 and a.active = 1
            )b ORDER BY b.kodeprod
        )a LEFT JOIN 
        (
            select a.nama_program, a.site_code, a.kodeprod, a.qty_bonus
            from management_bonus.master_data a
            where a.site_code = '$site_code' and a.nama_program = '$nama_program' 
        )b on a.kodeprod = b.kodeprod left join 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )c on a.site_code = c.site_code left join 
        (
            select a.nama_program, a.site_code, a.nodo, a.tgldo, a.kodeprod, sum(a.qty_penggantian) as qty_penggantian
            from management_bonus.tracking a 
            where a.site_code = '$site_code' and a.nama_program = '$nama_program' 
            GROUP BY a.kodeprod
        )d on a.site_code = d.site_code and a.nama_program = d.nama_program and a.kodeprod = d.kodeprod
        ORDER BY a.kodeprod
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_history_tracking(){
        $query = "
        select 	a.id, a.nama_program, a.site_code, a.nodo, a.tgldo, a.kodeprod, a.qty_penggantian, 
                a.created_at, a.signature, a.keterangan, b.nama_comp, c.closed
        from management_bonus.tracking a left join (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            group by concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code LEFT JOIN 
        (
            select a.site_code, a.nama_program, a.closed
            from management_bonus.master_data a 
            GROUP BY a.site_code, a.nama_program
        )c on a.site_code = c.site_code and a.nama_program = c.nama_program
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_nodo(){
        $query = "
            select a.nodo
            from management_bonus.tracking a
            GROUP BY a.nodo
        ";

        return $this->db->query($query);
    }


    public function get_data_qty_penggantian($site_code,$kodeprod,$nodo){
        $query = "
            select a.site_code, a.nodo, a.tgldo, a.kodeprod, a.qty_penggantian
            from management_bonus.tracking a
            where a.site_code ='$site_code' and a.kodeprod = '$kodeprod' and a.nodo = '$nodo'
        ";

        return $this->db->query($query);
    }

    public function get_body(){
        // $query = "
        //     select a.nama_program, a.site_code, a.nodo, a.kodeprod, sum(a.qty_bonus) as qty_bonus, sum(a.qty_penggantian) as qty_penggantian
        //     from management_bonus.tracking a
        //     GROUP BY a.nama_program, a.site_code, a.kodeprod
        // ";

        $query = "
        select 	a.nama_program, a.site_code, a.nodo, a.kodeprod, b.qty_bonus, 
                sum(a.qty_penggantian) as qty_penggantian, b.qty_bonus - sum(a.qty_penggantian) as sisa, c.branch_name, c.nama_comp, d.namaprod
        from management_bonus.tracking a LEFT JOIN
        (
            select a.nama_program, a.site_code, a.kodeprod, sum(a.qty_bonus) as qty_bonus
            from management_bonus.master_data a 
            GROUP BY a.nama_program, a.site_code, a.kodeprod
        )b on a.nama_program = b.nama_program and a.site_code = b.site_code and a.kodeprod = b.kodeprod LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )c on a.site_code = c.site_code left join (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
            where a.supp = 005
        )d on a.kodeprod = d.kodeprod
        GROUP BY a.nama_program, a.site_code, a.kodeprod
        ";

        return $this->db->query($query);
    }

    public function get_qty($nodo, $site_code){
        $query = "
            select a.nama_program, a.site_code, a.nodo, a.kodeprod, sum(a.qty_penggantian) as qty, a.keterangan
            from management_bonus.tracking a
            where a.site_code = '$site_code' and a.nodo = '$nodo'
            GROUP BY a.nama_program, a.kodeprod
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_qty_single($nodo, $site_code, $kodeprod){
        $query = "
            select a.nama_program, a.site_code, a.nodo, a.kodeprod, sum(a.qty_penggantian) as qty, a.keterangan
            from management_bonus.tracking a
            where a.site_code = '$site_code' and a.nodo = '$nodo' and a.kodeprod = '$kodeprod'
            GROUP BY a.nama_program
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_status($site_code, $nama_program, $signature){
        $query = "
            select *
            from management_bonus.master_data a 
            where a.nama_program = '$nama_program' and a.site_code = '$site_code' and a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_data_tracking_by_nodo($nodo, $signature, $site_code, $nama_program){

        $query = "
        select 	a.nama_program, a.site_code, a.kodeprod, a.namaprod, b.qty_bonus, c.branch_name, c.nama_comp, 
                if(d.qty_penggantian is null, 0, d.qty_penggantian) as qty_penggantian,
                b.qty_bonus - if(d.qty_penggantian is null, 0, d.qty_penggantian) as sisa, a.closed, a.signature, d.keterangan, d.nodo, d.signature as signature_tracking
        from 
        (
            select a.nama_program, a.site_code, b.kodeprod, b.namaprod, a.closed, a.signature
            from 
            (
                select a.nama_program, a.site_code, a.kodeprod, a.closed, a.signature
                from management_bonus.master_data a 
                where a.site_code = '$site_code' and a.nama_program = '$nama_program'
                GROUP BY a.site_code
            )a join (
                    select a.kodeprod, a.namaprod
                    from mpm.tabprod a 
                    where a.supp = 005 and a.active = 1
            )b ORDER BY b.kodeprod
        )a LEFT JOIN 
        (
            select a.nama_program, a.site_code, a.kodeprod, a.qty_bonus
            from management_bonus.master_data a
            where a.site_code = '$site_code' and a.nama_program = '$nama_program'
        )b on a.kodeprod = b.kodeprod left join 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )c on a.site_code = c.site_code left join 
        (
            select a.nama_program, a.site_code, a.nodo, a.tgldo, a.kodeprod, sum(a.qty_penggantian) as qty_penggantian, a.keterangan, a.signature
            from management_bonus.tracking a 
            where a.site_code = '$site_code' and a.nama_program = '$nama_program' and a.nodo = '260020000' and a.signature = '29cd85a971b554cf8475d5862e917bc8'
            GROUP BY a.kodeprod
        )d on a.site_code = d.site_code and a.nama_program = d.nama_program and a.kodeprod = d.kodeprod
        ORDER BY a.kodeprod
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_data_tracking_single($nodo, $signature){

        $query = "
            select *
            from management_bonus.tracking a 
            where a.nodo = '$nodo' and a.signature = '$signature'        
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function get_data_tracking_by_kodeprod($kodeprod, $signature){

        $query = "
            select *
            from management_bonus.tracking a 
            where a.kodeprod = '$kodeprod' and a.signature = '$signature'        
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

}