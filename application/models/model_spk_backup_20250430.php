<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_spk extends CI_Model 
{
    public function __construct() 
    {
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->userid = $this->session->userdata('id');
    }

    public function get_produk_by_supp($supp = "")
    {
        if ($supp == "") {
            $params_supp = "";
        }else{
            $params_supp = " and a.supp = '$supp'";
        }
        
        $query = "
            select *
            from site.master_product a
            where a.active = 1 and a.produksi = 1 $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_produk_by_kodeprod($kodeprod)
    {                
        $query = "
            select *
            from site.master_product a
            where a.active = 1 and a.kodeprod = '$kodeprod'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function fix_kodeprod_length($kodeprod)
    {
        if(strlen($kodeprod) == '5')
        {
            $params_kodeprod = '0'.$kodeprod;
        }else
        {
            $params_kodeprod = $kodeprod;
        } 
        return $params_kodeprod;
    }

    public function get_keranjang_belanja($signature)
    {
        $query = "
            select a.*, b.*, c.namaprod, c.moq, d.namasupp
            from site.temp_spk a left join (
                select *
                from site.temp_spk_detail
            )b on a.id = b.id_header left join (
                select a.kodeprod, a.namaprod, a.moq
                from mpm.tabprod a 
            )c on b.kodeprod = c.kodeprod inner join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )d on b.supp = d.supp
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_temp_alokasi($signature)
    {
        $query = "
            select a.*, b.*, c.namaprod, d.namasupp
            from site.temp_alokasi a left join (
                select *
                from site.temp_alokasi_detail
            )b on a.id = b.id_header left join (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
            )c on b.kodeprod = c.kodeprod inner join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )d on b.supp = d.supp
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }
    
    public function get_temp_spk_join_temp_spk_detail_by_userid($userid)
    {
        $query = "
            select 	a.id, a.site_code, a.flag_selesai, a.signature, 
                    a.created_at, a.created_by, a.deleted_at, a.deleted_by, a.updated_at, a.updated_by, 
                    b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, 
                    c.namaprod, c.moq, d.namasupp, b.signature as signature_detail
            from site.temp_spk a inner join (
                select *
                from site.temp_spk_detail a
                where a.deleted_at is null
            )b on a.id = b.id_header left join (
                select a.kodeprod, a.namaprod, a.moq
                from mpm.tabprod a 
            )c on b.kodeprod = c.kodeprod inner join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )d on b.supp = d.supp
            where a.created_by = $userid and a.flag_selesai is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_temp_alokasi_by_userid($userid)
    {
        $query = "
            select  a.id, a.site_code, a.kode_alamat, b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, c.namasupp, 
                    d.namaprod, a.flag_selesai, a.signature, b.signature as signature_detail,
                    e.nama_comp as nama_comp_header, f.nama_comp as nama_comp_tujuan
            from site.temp_alokasi a left join (
                select a.id_header, a.id, a.supp, a.kodeprod, a.jml_karton, a.signature
                from site.temp_alokasi_detail a
                where a.deleted_at is null
            )b on a.id = b.id_header left join site.master_supplier c 
                on b.supp = c.supp left join site.master_product d 
                on b.kodeprod = d.kodeprod left join site.master_site e 
                on a.site_code = e.site_code left join site.master_site f
                on a.kode_alamat = f.site_code
            where a.created_by = $userid and a.flag_selesai is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_temp_alokasi_join_temp_alokasi_detail_by_userid_supp_group_by_kode_alamat($userid, $supp)
    {
        // $query = "
        //     select  a.id, a.site_code, a.kode_alamat, b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, c.namasupp, 
        //             d.namaprod, a.flag_selesai, a.signature, b.signature as signature_detail,
        //             e.nama_comp as nama_comp_header, f.nama_comp as nama_comp_tujuan, 
        //             left(a.site_code, 3) as username, left(a.kode_alamat, 3) as username_kode_alamat, g.id as userid_tujuan,
        //             g.npwp, g.company, g.email
        //     from site.temp_alokasi a left join (
        //         select a.id_header, a.id, a.supp, a.kodeprod, a.jml_karton, a.signature
        //         from site.temp_alokasi_detail a
        //         where a.deleted_at is null
        //     )b on a.id = b.id_header left join site.master_supplier c 
        //         on b.supp = c.supp left join site.master_product d 
        //         on b.kodeprod = d.kodeprod left join site.master_site e 
        //         on a.site_code = e.site_code left join site.master_site f
        //         on a.kode_alamat = f.site_code left join site.master_user g 
        //         on left(a.kode_alamat, 3) = g.username
        //     where a.created_by = $userid and a.flag_selesai is null and b.supp = '$supp'
        //     group by a.kode_alamat
        // ";
        // $query = "
        //     select  a.id, a.site_code, a.kode_alamat, b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, c.namasupp, 
        //             d.namaprod, a.flag_selesai, a.signature, b.signature as signature_detail,
        //             e.nama_comp as nama_comp_header, f.nama_comp as nama_comp_tujuan, 
        //             if(left(a.site_code, 3) = 'MPI', a.site_code, left(a.site_code, 3)) as username,
        //             if(left(a.kode_alamat, 3) = 'MPI', a.kode_alamat, left(a.kode_alamat, 3)) as  username_kode_alamat,
        //             g.id as userid_tujuan,
        //             if(g.company ='MPI', h.company, g.company) as company, if(g.company ='MPI', h.npwp, g.npwp) as npwp, g.email
        //     from site.temp_alokasi a left join (
        //         select a.id_header, a.id, a.supp, a.kodeprod, a.jml_karton, a.signature
        //         from site.temp_alokasi_detail a
        //         where a.deleted_at is null
        //     )b on a.id = b.id_header left join site.master_supplier c 
        //         on b.supp = c.supp left join site.master_product d 
        //         on b.kodeprod = d.kodeprod left join site.master_site e 
        //         on a.site_code = e.site_code left join site.master_site f
        //         on a.kode_alamat = f.site_code left join site.master_user g 
        //         on if(left(a.kode_alamat, 3) = 'MPI', a.kode_alamat, left(a.kode_alamat, 3)) = g.username left join site.master_user h
		// 		on a.kode_alamat = h.username
        //     where a.created_by = $userid and a.flag_selesai is null and b.supp = '$supp'
        //     group by a.kode_alamat
        // ";


        $query = "
            select  a.id, a.site_code, a.kode_alamat, b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, c.namasupp, 
                    d.namaprod, a.flag_selesai, a.signature, b.signature as signature_detail,
                    e.nama_comp as nama_comp_header, f.nama_comp as nama_comp_tujuan, 
                    if(left(a.site_code, 3) = 'MPI', a.site_code, 
                    if(left(a.site_code, 5) = 'PENTA', a.site_code, 
                    left(a.site_code, 3))) as username,
                    if(left(a.kode_alamat, 3) = 'MPI', a.kode_alamat, 
                    if(left(a.kode_alamat, 5) = 'PENTA', a.kode_alamat, 
                    left(a.kode_alamat, 3))) as  username_kode_alamat,
                    g.id as userid_tujuan,
                    if(g.company ='MPI', h.company, g.company) as company, if(g.company ='MPI', h.npwp, g.npwp) as npwp, g.email
            from site.temp_alokasi a left join (
                select a.id_header, a.id, a.supp, a.kodeprod, a.jml_karton, a.signature
                from site.temp_alokasi_detail a
                where a.deleted_at is null
            )b on a.id = b.id_header left join site.master_supplier c 
                on b.supp = c.supp left join site.master_product d 
                on b.kodeprod = d.kodeprod left join site.master_site e 
                on a.site_code = e.site_code left join site.master_site f
                on a.kode_alamat = f.site_code left join site.master_user g 
                on 
                    if(left(a.kode_alamat, 3) = 'MPI', a.kode_alamat, 
                    if(left(a.kode_alamat, 5) = 'PENTA', a.kode_alamat, 		
                    left(a.kode_alamat, 3))) = g.username left join site.master_user h
                on a.kode_alamat = h.username
            where a.created_by = $userid and a.flag_selesai is null and b.supp = '$supp'
            group by a.kode_alamat
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_temp_alokasi_join_temp_alokasi_detail_by_userid_supp_kode_alamat($userid, $supp, $kode_alamat)
    {
        $query = "
            select  a.id, a.site_code, a.kode_alamat, b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, c.namasupp, 
                    d.namaprod, a.flag_selesai, a.signature, b.signature as signature_detail,
                    e.nama_comp as nama_comp_header, f.nama_comp as nama_comp_tujuan, b.total_berat, b.total_volume,
                    if(g.average_karton is null,0,g.average_karton) as average_karton, 
                    round(b.jml_karton / if(g.average_karton is null,0,g.average_karton),2) as ratio
            from site.temp_alokasi a left join (
                select a.id_header, a.id, a.supp, a.kodeprod, a.jml_karton, a.signature, a.master_berat, a.total_berat, a.master_volume, a.total_volume
                from site.temp_alokasi_detail a
                where a.deleted_at is null
            )b on a.id = b.id_header left join site.master_supplier c 
                on b.supp = c.supp left join site.master_product d 
                on b.kodeprod = d.kodeprod left join site.master_site e 
                on a.site_code = e.site_code left join site.master_site f
                on a.kode_alamat = f.site_code left join (
                    select a.kodeprod, abs(a.average_karton) as average_karton
                    from site.temp_average_spk a 
                    where a.site_code = '$kode_alamat'
                )g on b.kodeprod = g.kodeprod
            where a.created_by = $userid and a.flag_selesai is null and b.supp = '$supp' and a.kode_alamat = '$kode_alamat'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton($userid)
    {
        $query = "
            select 	a.id, a.site_code, a.flag_selesai, a.signature, 
                    a.created_at, a.created_by, a.deleted_at, a.deleted_by, a.updated_at, a.updated_by, 
                    b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, 
                    c.namaprod, d.namasupp, b.signature as signature_detail, b.total_volume, b.total_berat, e.moq_us
            from site.temp_spk a inner join (
                select *
                from site.temp_spk_detail a
                where a.deleted_at is null and a.jml_karton > 0
            )b on a.id = b.id_header left join (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
            )c on b.kodeprod = c.kodeprod inner join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )d on b.supp = d.supp
            left join
            (
                select a.site_code, a.moq_us
                from mpm.tbl_tabcomp a
                where a.active = 1
            )e on a.site_code = e.site_code
            where a.created_by = $userid and a.flag_selesai is null
            order by b.supp, b.kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_keranjang_belanja_detail_by_id_header($id_header)
    {
        $query = "
            select *
            from site.temp_spk_detail a 
            where a.id_header = $id_header and deleted_at is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_temp_spk_detail_by_id_header_and_kodeprod($id_header, $kodeprod)
    {
        $query = "
            select *
            from site.temp_spk_detail a 
            where a.id_header = $id_header and a.kodeprod = '$kodeprod' and deleted_at is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_keranjang_alokasi_detail_by_id_header_and_kodeprod($id_header, $kodeprod)
    {
        $query = "
            select *
            from site.temp_alokasi_detail a 
            where a.id_header = $id_header and a.kodeprod = '$kodeprod' and deleted_at is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_keranjang_belanja_header_by_signature($signature)
    {
        $query = "
            select *
            from site.temp_spk a 
            where a.signature = '$signature'
        ";

        return $this->db->query($query);
    }

    public function get_temp_spk_detail_by_id_header_group_by_supp($id_header)
    {
        $query = "
            select a.id_header, a.supp, b.namasupp
            from site.temp_spk_detail a inner join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )b on a.supp = b.supp
            where a.id_header = $id_header and deleted_at is null and a.jml_karton > 0
            group by a.supp
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_keranjang_alokasi_detail_by_id_header_group_by_supp($id_header)
    {
        $query = "
            select a.id_header, a.supp, b.namasupp
            from site.temp_alokasi_detail a inner join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )b on a.supp = b.supp
            where a.id_header = $id_header and deleted_at is null
            group by a.supp
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_temp_alokasi_detail_group_by_supp()
    {
        $query = "
            select a.id_header, a.supp, b.namasupp
            from site.temp_alokasi_detail a inner join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )b on a.supp = b.supp
            where deleted_at is null
            group by a.supp
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_temp_spk_detail_by_supp_id_header_site_code($supp, $id_header, $site_code)
    {
        $query = "
            select 	a.id, a.id_header, a.kodeprod, a.jml_karton, 
                    a.created_at, a.created_by, a.deleted_at, a.deleted_by, a.signature,
                    b.namaprod, round(a.jml_karton * a.master_berat,0) as berat_produk, 
                    round(a.jml_karton * a.master_volume,2) as volume_produk, if(c.average_karton is null,0,c.average_karton) as average_karton, 
                    round(a.jml_karton / if(c.average_karton is null,0,c.average_karton),2) as ratio, b.kode_prc, a.master_volume, a.master_berat, b.isisatuan, b.h_dp, a.pp_unit, a.actual_po_bulan_ini, a.selisih_po
            from site.temp_spk_detail a left join (
                select a.kodeprod, a.namaprod, a.kode_prc, a.isisatuan, b.h_dp
                from mpm.tabprod a left join (
                    select b.kodeprod, b.h_dp 
                    from mpm.prod_detail b 
                    where b.tgl = (
                        select max(c.tgl)
                        from mpm.prod_detail c 
                        where b.kodeprod = c.kodeprod
                        group by c.kodeprod
                    )
                )b on a.kodeprod = b.kodeprod
            )b on a.kodeprod = b.kodeprod left join (
                select a.kodeprod, abs(a.average_karton) as average_karton
                from site.temp_average_spk a 
                where a.site_code = '$site_code'
            )c on a.kodeprod = c.kodeprod
            where a.deleted_at is null and a.supp = '$supp' and a.id_header = $id_header and a.jml_karton > 0
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);

    }

    public function get_sum_in_temp_spk_detail_by_supp_id_header($supp, $id_header)
    {
        $query = "
            select 	a.id, a.id_header, a.kodeprod, sum(a.jml_karton) as jml_karton, 
                    a.created_at, a.created_by, a.deleted_at, a.deleted_by, a.signature,
                    sum(a.total_berat) as berat_produk, 
			        round(sum(a.total_volume),2) as volume_produk
            from site.temp_spk_detail a
            where a.deleted_at is null and a.supp = '$supp' and a.id_header = $id_header
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_keranjang_belanja_detail_by_signature($signature)
    {
        $query = "
            select *
            from site.temp_spk_detail a 
            where a.deleted_at is null and a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query); 
    }

    public function generate_sales($cycle)
    {
        $truncate = $this->db->query("truncate site.temp_sales_spk");

        for ($i=$cycle; $i >= 1 ; $i--) 
        { 
            $params_tahun = date('Y', strtotime("-$i month"));
            $params_bulan = date('m', strtotime("-$i month"));

            $query = "
                insert into site.temp_sales_spk
                select '', a.site_code, $params_tahun as tahun, a.bulan, a.kodeprod, sum(a.banyak) as total_unit, '$this->created_at' as created_at, '$this->userid' as created_by
                from 
                (
                    select concat(a.kode_comp, a.nocab) as site_code, a.kodeprod, a.banyak, a.bulan
                    from data$params_tahun.fi a
                    where a.bulan in ($params_bulan)
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, a.kodeprod, a.banyak, a.bulan
                    from data$params_tahun.ri a 
                    where a.bulan in ($params_bulan)
                )a GROUP BY a.site_code, a.kodeprod
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
            $proses = $this->db->query($query);
        }
        return $proses;
    }

    public function generate_average($cycle)
    {        
        $truncate = $this->db->query("truncate site.temp_average_spk");
        $query = "
            insert into site.temp_average_spk
            select  '', a.site_code, a.tahun, a.bulan, a.kodeprod, round(sum(a.total_unit)/$cycle,2) as total_unit, 
                    round(round(sum(a.total_unit)/6,2) / b.isisatuan,2) average_in_karton,
                    '$this->created_at' as created_at, '$this->userid' as created_by
            from site.temp_sales_spk a left join 
            (
                select a.kodeprod, a.namaprod, a.qty1, a.qty2, a.qty3, a.kecil, a.isisatuan
                from mpm.tabprod a 
            )b on a.kodeprod = b.kodeprod
            GROUP BY a.site_code, a.kodeprod
        ";

        $proses = $this->db->query($query);
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $proses;
    }

    public function get_average_sales()
    {
        $query = "
            select a.site_code, b.branch_name, b.nama_comp, a.tahun, a.bulan, a.kodeprod, c.namaprod, a.average_unit, a.average_karton, a.created_at, a.created_by
            from site.temp_average_spk a left join (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.branch
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code left join (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
            )c on a.kodeprod = c.kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_master_berat_volume_in_keranjang_belanja_by_id_and_kodeprod($id, $kodeprod)
    {
        $query = "
            update site.temp_spk_detail a 
            set a.master_berat = (
                select b.berat
                from mpm.tabprod b
                where b.kodeprod = '$kodeprod'
            ), a.master_volume = (
                select b.volume
                from mpm.tabprod b
                where b.kodeprod = '$kodeprod'
            )
            where a.id = $id and a.kodeprod = '$kodeprod'
        ";
        return $this->db->query($query);
    }

    public function update_master_berat_volume_in_keranjang_alokasi_by_id_and_kodeprod($id, $kodeprod)
    {
        $query = "
            update site.temp_alokasi_detail a 
            set a.master_berat = (
                select b.berat
                from mpm.tabprod b
                where b.kodeprod = '$kodeprod'
            ), a.master_volume = (
                select b.volume
                from mpm.tabprod b
                where b.kodeprod = '$kodeprod'
            )
            where a.id = $id and a.kodeprod = '$kodeprod'
        ";
        return $this->db->query($query);
    }

    public function update_berat_volume_in_keranjang_belanja_by_id($id)
    {
        $query = "
            update site.temp_spk_detail a 
            set a.total_berat = round(a.master_berat * a.jml_karton),
            a.total_volume = round(a.master_volume * a.jml_karton,2)
            where a.id = $id 
        ";
        return $this->db->query($query);
    }

    public function get_tabprod_spk($kodeprod)
    {
        $query = "
            select *
            from site.master_product a
            where a.active = 1 and a.produksi = 1 and a.kodeprod = '$kodeprod'
        ";
        return $this->db->query($query);
    }

    public function get_tabprod_by_kodeprod_supp($kodeprod, $supp)
    {
        $query = "
            select *
            from site.master_product a
            where a.active = 1 and a.kodeprod = '$kodeprod' and a.supp = '$supp'
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function get_alamat_by_userid($userid, $kode_alamat = '')
    {
        if ($kode_alamat == '') {
            $params_kode_alamat = '';
        }else{
            $params_kode_alamat = "and a.kode_alamat = '$kode_alamat'";
        }
        $query = "
            select  a.id, a.name, a.company, a.npwp, a.address, a.email, b.*, c.*
            from mpm.user a left join (
                select a.username, a.kode_alamat, a.alamat, a.`status`, a.status_ho
                from mpm.t_alamat a 
                where a.status = 1 $params_kode_alamat
            )b on a.username = b.username inner join (
                select a.*
                from site.master_site a
            )c on b.kode_alamat = c.site_code
            where a.id = $userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_mapping_dc_by_site_code($site_code)
    {
        $query = "
            select *
            from site.map_dc_po a
            where a.site_code = '$site_code'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function update_flag_selesai_by_id($id)
    {
        $query = "
            update site.temp_spk a 
            set a.flag_selesai = 1 
            where a.id = $id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_temp_alokasi_original_excel()
    {
        $this->db->query("truncate site.temp_alokasi_original_excel");
        $query = "
            select *
            from site.temp_alokasi_original_excel a 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_temp_alokasi_original_excel_group_by_site_code()
    {
        // $this->db->query("truncate site.temp_alokasi_original_excel");
        $query = "
            select site_code
            from site.temp_alokasi_original_excel a 
            GROUP BY a.site_code
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_temp_alokasi_original_excel_by_site_code($site_code)
    {
        $query = "
            select *
            from site.temp_alokasi_original_excel a 
            where a.site_code = '$site_code'
        ";
        return $this->db->query($query);    
    }

    public function get_temp_alokasi_original_excel_by_site_code_group_by_kode_alamat($site_code)
    {
        $query = "
            select *
            from site.temp_alokasi_original_excel a 
            where a.site_code = '$site_code'
            group by a.kode_alamat
        ";
        return $this->db->query($query);
    }

    public function insert_temp_alokasi_original_excel($data)
    {
        $this->db->insert('site.temp_alokasi_original_excel', $data);
        return $this->db->insert_id();
    }

    public function insert_po($data)
    {
        $this->db->insert('mpm.po', $data);
        return $this->db->insert_id();
    }

    public function insert_po_detail($data)
    {
        $this->db->insert('mpm.po_detail', $data);
        return $this->db->insert_id();
    }

    public function truncate()
    {
        $this->db->query("truncate site.temp_alokasi_original_excel");
        $this->db->query("truncate site.temp_alokasi");
        $this->db->query("truncate site.temp_alokasi_detail");
    }

    public function get_temp_alokasi_original_excel_by_site_code_and_kode_alamat_group_by_kodeprod($site_code, $kode_alamat)
    {
        $query = "
            select *
            from site.temp_alokasi_original_excel a 
            where a.site_code = '$site_code' and a.kode_alamat ='$kode_alamat'
        ";
        return $this->db->query($query);
    }

    public function insert_temp_alokasi($data)
    {
        $this->db->insert('site.temp_alokasi', $data);
        return $this->db->insert_id();
    }

    public function insert_temp_alokasi_detail($data)
    {
        $this->db->insert('site.temp_alokasi_detail', $data);
        return $this->db->insert_id();
    }

    public function update_temp_alokasi_detail_by_id($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("site.temp_alokasi_detail", $data);
        return true;
    }

    public function update_berat_volume_temp_alokasi_detail_by_id($id)
    {
        $query = "
            update site.temp_alokasi_detail a 
            set a.total_berat = round(a.master_berat * a.jml_karton),
            a.total_volume = round(a.master_volume * a.jml_karton,2)
            where a.id = $id 
        ";
        return $this->db->query($query);
    }

    public function get_alamat_by_kode_alamat_username($kode_alamat, $username)
    {
        $query = "
            select *
            from mpm.t_alamat a 
            where a.kode_alamat = '$kode_alamat' and a.username = '$username'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_user_by_username($username)
    {
        $query = "
            select *
            from site.master_user a 
            where a.username = '$username'
        ";

        return $this->db->query($query);
    }

    public function get_temp_alokasi_detail_by_supp_id_header_site_code($supp, $id_header, $site_code)
    {
        $query = "
            select 	a.id, a.id_header, a.kodeprod, a.jml_karton, 
                    a.created_at, a.created_by, a.deleted_at, a.deleted_by, a.signature,
                    b.namaprod, round(a.jml_karton * a.master_berat,0) as berat_produk, 
                    round(a.jml_karton * a.master_volume,2) as volume_produk, if(c.average_karton is null,0,c.average_karton) as average_karton, 
                    round(a.jml_karton / if(c.average_karton is null,0,c.average_karton),2) as ratio, b.kode_prc, a.master_volume, a.master_berat, b.isisatuan, b.h_dp
            from site.temp_alokasi_detail a left join site.master_product_with_harga b 
                on a.kodeprod = b.kodeprod left join (
                    select a.kodeprod, abs(a.average_karton) as average_karton
                    from site.temp_average_spk a 
                    where a.site_code = '$site_code'
            )c on a.kodeprod = c.kodeprod
            where a.deleted_at is null and a.supp = '$supp' and a.id_header = $id_header and a.jml_karton > 0
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);

    }

    public function update_temp_alokasi_flag_selesai_by_id($id)
    {
        $query = "
            update site.temp_alokasi a 
            set a.flag_selesai = 1 
            where a.id = $id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_po($advanced)
    {
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $params_site_code = " and a.kode_alamat in ($kode_alamat) ";

        // echo "params_site_code : ".$params_site_code;

        $tahun = date("Y");
        if ($advanced) 
        {
            $site_code  = $advanced['site_code'];

            if ($site_code == "all") {
                $params_site_code = "and a.kode_alamat in ($kode_alamat)";
            }else{
                $params_site_code = " and a.kode_alamat in ('$site_code') ";
            }

            $limit = $advanced['limit'];
            if ($limit) {
                $params_limit = " limit $limit ";
            }else{
                $params_limit = " limit 100 ";
            }

            $from = $advanced['from'];
            $to = $advanced['to'];
            $year = date('Y', strtotime($from));

            if ($from && $to) {
                $params_tgl = " and date(a.tglpesan) between '$from' and '$to' ";
                $params_tahun = "year(a.tglpesan) = $year";
            }else{
                $params_tgl = "";
            }

            $flag_delete = $advanced['flag_delete'];

            if ($flag_delete) {
                $params_flag_delete = " and a.deleted = $flag_delete ";
            }else{
                $params_flag_delete = " and a.deleted = 0 ";
            }

        }else{
            $params_site_code = $params_site_code;
            $params_limit = " limit 100 ";
            $params_tgl = "";
            $params_flag_delete = " and a.deleted = 0 ";
            $params_tahun = "year(a.tglpesan) = $tahun";
        }


        $query = "
            select 	a.id, a.userid, a.supp, a.nopo, a.tglpo, a.tglpesan, a.tipe, a.open, a.status, a.status_approval,
                    a.status_override, a.company, a.npwp, a.email, a.alamat, a.alamat_kirim,
                    a.note, a.po_ref, a.lock, a.kode_alamat, a.total_value, b.namasupp, c.branch_name,
                    if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp, a.signature, year(a.tglpesan) as tahun, a.is_pp_approval
            from mpm.po a left join site.master_supplier b 
                on a.supp = b.supp left join site.master_site c 
                on a.kode_alamat = c.site_code
            where $params_tahun $params_site_code $params_tgl $params_flag_delete
            ORDER BY a.id desc 
            $params_limit
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function delete_po($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("mpm.po", $data);
        return true;
    }

    public function delete_po_detail($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("mpm.po_detail", $data);
        return true;
    }

    public function get_po_by_signature_tahun($signature, $tahun)
    {
        $query = "
            select *
            from mpm.po a 
            where a.signature = '$signature' and year(a.tglpesan) = $tahun
        ";

        return $this->db->query($query);
    }

    public function get_po_by_signature($signature)
    {
        $query = "
            select a.*, b.namasupp, c.branch_name, c.nama_comp
            from mpm.po a left join site.master_supplier b 
                on a.supp = b.supp left join site.master_site c
                on a.kode_alamat = c.site_code
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        
        // die;

        return $this->db->query($query);
    }

    public function get_po_with_signature($signature)
    {
        $query = "
            select *
            from mpm.po a 
            where a.signature = '$signature'
        ";

        return $this->db->query($query);
    }
    public function get_po_detail_by_id_po($id_po)
    {
        $query = "
            select 	a.*, b.username, c.isisatuan
            from mpm.po_detail a left join site.master_user b 
                on a.updated_by = b.id left join site.master_product c 
				on a.kodeprod = c.kodeprod
            where a.deleted= 0 and a.id_ref = $id_po
            order by a.kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die; 
        return $this->db->query($query);
    }

    public function get_po_detail_by_id_po_include_delete($id_po)
    {
        $query = "
            select 	a.*, b.username, c.isisatuan
            from mpm.po_detail a left join site.master_user b 
                on a.updated_by = b.id left join site.master_product c 
				on a.kodeprod = c.kodeprod
            where a.id_ref = $id_po
            order by a.kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die; 
        return $this->db->query($query);
    }

    public function get_po_detail_by_id_po_kodeprod($id_po, $kodeprod)
    {
        $query = "
            select 	a.*, b.username
            from mpm.po_detail a left join site.master_user b 
                on a.updated_by = b.id
            where a.deleted = 0 and a.id_ref = $id_po and a.kodeprod = '$kodeprod'
            order by a.kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 

        // die;
        return $this->db->query($query);
    }

    public function get_master_product_with_harga($kodeprod, $supp = "")
    {

        $supp ? $params_supp = "and a.supp = '$supp'" : $params_supp = "";
        $query = "
            select *
            from site.master_product_with_harga a
            where a.active = 1 and a.kodeprod = '$kodeprod' $params_supp
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 

        // die;
        return $this->db->query($query);
    }

    public function update_po_detail($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("mpm.po_detail", $data);
        return true;
    }

    public function update_po($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("mpm.po", $data);
        return true;
    }

    public function update_karton_in_po_detail($id, $isisatuan)
    {
        if ($isisatuan == 0 || $isisatuan == "" || $isisatuan == null) {
            $isisatuan = 1;
        }else{
            $isisatuan = $isisatuan;
        }
        $query = "
            update mpm.po_detail a 
            set a.banyak_karton = a.banyak / $isisatuan
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function generate_nopo($supp, $tglpesan)
    {
        $this->load->model("model_management_inventory");

        $tahun_pesan = substr($tglpesan, 0, 4);
        $bulan_pesan = substr($tglpesan, 5, 2);
        $romawi = $this->model_management_inventory->getRomawi($bulan_pesan);

        $query = "
            select a.nopo, substr(a.nopo,4,3) as urut
            from mpm.po a 
            where  a.deleted = 0 and year(a.tglpesan) = $tahun_pesan and month(a.tglpesan) = $bulan_pesan and a.supp = '$supp' and a.nopo is not null
            ORDER BY substr(a.nopo,4,3) desc 
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $get_prefix = $this->get_master_principal($supp);
        if ($get_prefix->num_rows() > 0) {
            $prefix = $get_prefix->row()->prefix_po;
        }else{
            $prefix = "";
        }

        // die;

        $no_pengajuan_current = $this->db->query($query);
        if ($no_pengajuan_current->num_rows() > 0) {
            
            $params_urut = $no_pengajuan_current->row()->urut + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "$prefix"."000$params_urut/MPM/$romawi/$tahun_pesan";
            }elseif (strlen($params_urut) === 2) {
                $generate = "$prefix"."00$params_urut/MPM/$romawi/$tahun_pesan";
            }else{
                $generate = "$prefix"."0$params_urut/MPM/$romawi/$tahun_pesan";
            }
        }else{
            $generate = "$prefix"."0001/MPM/$romawi/$tahun_pesan";
        }

        // echo "generate : ".$generate;
        // die;

        return $generate;
    }

    public function get_master_principal($supp = "")
    {
        if ($supp) {
            $params_supp = "where a.supp = '$supp'";
        }else{
            $params_supp = "";
        }

        $query = "
            select a.*, b.username
            from site.master_supplier a left join site.master_user b 
                on a.updated_by = b.id
            $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_total_value_po_detail_by_id_ref($id_ref)
    {
        $query = "
            select sum(a.banyak*a.harga) as total_value
            from mpm.po_detail a 
            where a.id_ref = $id_ref and a.deleted = 0
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_master_berat_volume_in_po_detail_by_id_and_kodeprod($id, $kodeprod)
    {
        $query = "
            update mpm.po_detail a 
            set a.berat = (
                select b.berat
                from mpm.tabprod b
                where b.kodeprod = '$kodeprod'
            ), a.volume = (
                select b.volume
                from mpm.tabprod b
                where b.kodeprod = '$kodeprod'
            )
            where a.id = $id and a.kodeprod = '$kodeprod'
        ";
        return $this->db->query($query);
    }

    public function insert_log_po_outstanding($data)
    {
        $this->db->insert("site.log_po_outstanding", $data);
        return $this->db->insert_id();
    }
    public function get_log_po_outstanding()
    {
        $query = "
            select a.*, b.username
            from site.log_po_outstanding a left join site.master_user b 
                on a.created_by = b.id
        ";
        return $this->db->query($query);
    }

    public function truncate_delete_po_outstanding($tahun)
    {
        $this->db->query("truncate db_po.t_temp_report_po_update");
        $this->db->query("truncate db_po.t_temp_do_po_outstanding");
        $this->db->query("truncate db_po.t_temp_do_po_outstanding_us");

        $this->db->query("delete from db_po.t_po_outstanding_deltomed where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_us where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_intrafood where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_marguna where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_jaya where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_strive where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_hni where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_mdj where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_all where year(tglpo) = $tahun");
    }

    public function insert_do_deltomed_by_tahun($tahun)
    {
        $insert_do_deltomed = "
        insert into db_po.t_temp_do_po_outstanding
        select 	a.nodo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo, 
                str_to_date(a.tgldo,'%d/%m/%Y') as tgldo,'$tgl',297
        from db_po.t_do_deltomed a
        where year(str_to_date(a.tgldo,'%d/%m/%Y')) >= $tahun
        ";
        return $this->db->query($insert_do_deltomed);
    }

    public function insert_do_us_by_tahun($tahun)
    {
        $sql_replace = "
        update db_po.t_do_us a
        set a.kodeprod = replace(a.kodeprod, '.','')
        where kodeprod like '%.'
        ";
        $proses_sql_replace = $this->db->query($sql_replace);

        // echo "<pre>";
        // print_r($sql_replace);
        // echo "</pre>";

        $insert_do_us = "
        insert into db_po.t_temp_do_po_outstanding_us
        select 	a.nodo, a.tgldo, a.kodeprod,a.nopo,
                if(b.satuan_box is null, a.qty_pemenuhan, a.qty_pemenuhan * b.satuan_box) as qty_pemenuhan,
                '$this->created_at', '$this->user_id'
        FROM
        (
            select 	a.nodo, a.tgldo,
                    a.kodeprod, sum(a.banyak) as qty_pemenuhan, a.nopo
            from 	db_po.t_do_us a
            where year(a.tgldo) >= $tahun
            GROUP BY a.nopo, a.kodeprod
        )a LEFT JOIN
        (
            select a.kodeprod, a.satuan_box, a.`status`
            from db_produk.t_product_po a
            where a.`status` = 1
        )b on a.kodeprod = b.kodeprod
        ";
        return $this->db->query($insert_do_us);

        // echo "<pre>";
        // print_r($insert_do_us);
        // echo "</pre>";
    }

    public function insert_po_by_tahun($tahun)
    {
        $insert_po_nasional = "
        INSERT INTO db_po.t_temp_report_po_update
        select 	'', a.id, b.id as id_po_detail,a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, a.tglpesan,
                a.created, a.modified, a.created_by, a.modified_by, a.tipe, a.`open`, a.open_by, a.open_date,
                a.company, a.npwp, a.email, a.alamat, a.ambil, a.note, a.note_acc, a.`status`, a.status_approval,
                a.alasan_approval, a.po_ref, a.`lock`, a.kode_alamat, b.kodeprod, b.namaprod, 
                b.banyak, b.banyak_karton, b.`backup`, b.harga, b.kode_prc, b.berat, b.volume, b.stock_akhir, 
                b.rata, b.git, b.doi, b.status_terima, b.tanggal_terima, b.tanggal_terima_created_date, a.userid, a.deleted,297,'$tgl'
        from 	mpm.po a INNER JOIN 
        (
                select a.*
                from mpm.po_detail a
                where a.deleted = 0
        )b on a.id = b.id_ref 
        where	 a.deleted = 0 and b.deleted = 0 and
        left(a.nopo,4) <> '/MPM' and 
        a.nopo not like '%batal%' and year(a.tglpo) >= $tahun 
        ";
        return $this->db->query($insert_po_nasional);
        // echo "<pre>";
        // print_r($insert_po_nasional);
        // echo "</pre>";

    }

    public function insert_po_outstanding_deltomed($tahun)
    {
        $insert_po_outstanding_deltomed = "
        insert into db_po.t_po_outstanding_deltomed
        select 	a.branch_name, a.nama_comp,a.company, a.tglpo, a.nopo,a.tipe, a.kodeprod, a.namaprod, 
                a.banyak as qty_po, b.qty as qty_pemenuhan, a.harga,(a.banyak*a.harga) as value_po,(b.qty*a.harga) as value_pemenuhan,
                a.berat, a.volume, b.tgldo, b.nodo, (b.qty / a.banyak) * 100 as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,
                a.po_ref, a.tanggal_terima, datediff(a.tanggal_terima,b.tgldo) as leadtime_proses_kirim,
                (a.banyak - b.qty) as outstanding_po,a.kode_alamat,'$this->created_at', '$this->user_id'
        from
        (
            select 	d.branch_name, d.nama_comp, a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,e.kodeprod_deltomed, 
                    a.po_ref, a.berat, a.status_terima, a.tanggal_terima, a.kode_alamat, a.volume
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, (a.berat * a.banyak_karton) as berat, a.status_terima, a.tanggal_terima, a.kode_alamat, a.volume
                from 	db_po.t_temp_report_po_update a  
                where   a.supp ='001' and year(a.tglpo) in ($tahun)                        
            )a LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select  a.kodeprod, a.namaprod, a.kodeprod_deltomed
                from    mpm.tabprod a
                where   supp = 001
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN 
        (
            select a.nodo, a.kodedp, a.company, a.kodeprod_deltomed, a.namaprod, a.qty, a.nopo, tgldo
            from db_po.t_temp_do_po_outstanding a
        )b on a.nopo = b.nopo and a.kodeprod_deltomed = b.kodeprod_deltomed 
        order by nama_comp, kodeprod
        ";
        return $this->db->query($insert_po_outstanding_deltomed);
        // echo "<pre>";
        // print_r($insert_po_outstanding_deltomed);
        // echo "</pre>";

        // die;
    }

    public function insert_po_outstanding_us($tahun)
    {
        $insert_po_outstanding_us = "
        insert into db_po.t_po_outstanding_us
        select 	a.company, a.branch_name, a.nama_comp, date(a.tglpo) as tglpo,a.nopo,a.tipe, a.kodeprod,  
                a.namaprod, a.banyak as qty_po, a.berat, a.volume,
                b.qty_pemenuhan,b.tgldo, b.nodo, (b.qty_pemenuhan / a.banyak * 100) as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,  
                a.tanggal_terima, datediff(a.tanggal_terima, b.tgldo) as leadtime_proses_kirim, 
                (a.banyak - b.qty_pemenuhan) as outstanding_po, a.kode_alamat,'$this->created_at', '$this->user_id'
        FROM 
        ( 
            select 	d.branch_name, d.nama_comp, 
                    a.nopo, a.tglpo, a.tipe, 
                    a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,a.po_ref,a.tanggal_terima, a.kode_alamat,a.berat,a.volume
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, 
                        a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, a.tanggal_terima, a.kode_alamat,a.berat,a.volume
                from 	db_po.t_temp_report_po_update a
                where    a.supp ='005' and year(a.tglpo) in ($tahun)
            )a LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN
        (
            select 	a.nodo, a.tgldo, a.kodeprod,a.nopo, qty_pemenuhan
            FROM db_po.t_temp_do_po_outstanding_us a	
        )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod
        order by nama_comp, kodeprod
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_us);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_us);
    }

    public function insert_po_outstanding_intrafood($tahun)
    {
        $insert_po_outstanding_intrafood = "
        insert into db_po.t_po_outstanding_intrafood
        select 	a.id_ref,b.branch_name, b.nama_comp, a.company, date(a.tglpo) as tglpo,
                a.nopo,a.tipe,a.kodeprod,a.namaprod,a.banyak,
                a.banyak_karton, if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) as pemenuhan_unit, 
                if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) as pemenuhan_karton, c.tanggal_kirim, c.tanggal_tiba, 
                (if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) / a.banyak * 100) as fulfilment_unit, 
                (if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) / a.banyak_karton * 100) as fulfilment_karton,
                datediff(c.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, c.status_closed, a.tanggal_terima,
                datediff(a.tanggal_terima, c.tanggal_kirim) as leadtime_proses_kirim, 
                (a.banyak - if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit)) as outstanding_po_unit,
                (a.banyak_karton - if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton)) as outstanding_po_karton, a.kode_alamat,'$this->created_at', '$this->user_id'
        from
        (
            select 	a.id, a.id_ref, a.id_po_detail, a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, 
                    a.tglpesan, a.created, a.tipe, a.company, a.alamat, a.kode_alamat, 
                    a.kodeprod, a.namaprod, a.banyak, a.banyak_karton, 
                    a.harga, a.kode_prc, a.berat, a.volume, a.stock_akhir, a.rata, a.git, a.doi, 
                    a.status_terima, a.tanggal_terima, a.userid, a.deleted
            from db_po.t_temp_report_po_update a
            where a.supp = 012
        )a LEFT JOIN
        (
            select	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from	mpm.tbl_tabcomp a
            where 	a.`status` = 1
            group by site_code
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select 	a.id_po, a.no_asn, a.batch_number, a.nodo, a.ed, 
                    a.kodeprod, sum(a.jumlah_unit) as pemenuhan_unit, sum(a.jumlah_karton) as pemenuhan_karton, a.status_pemenuhan,
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, a.tanggal_tiba, a.keterangan, a.status_closed, a.signature,
                    a.created_date, a.created_by, a.last_updated, a.last_updated_by
            from    mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )c on a.id_ref = c.id_po and a.kodeprod = c.kodeprod
        ";

        // echo "<pre>";
        // print_r($insert_po_outstanding_intrafood);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_intrafood);
    }

    public function insert_po_outstanding_marguna($tahun)
    {
        $insert_po_outstanding_marguna = "
        insert into db_po.t_po_outstanding_marguna
        select 	a.id_ref,b.branch_name, b.nama_comp, a.company, date(a.tglpo) as tglpo,
                a.nopo,a.tipe,a.kodeprod,a.namaprod,a.banyak,
                a.banyak_karton, if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) as pemenuhan_unit, 
                if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) as pemenuhan_karton, c.tanggal_kirim, c.tanggal_tiba, 
                (if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) / a.banyak * 100) as fulfilment_unit, 
                (if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) / a.banyak_karton * 100) as fulfilment_karton,
                datediff(c.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, c.status_closed, a.tanggal_terima,
                datediff(a.tanggal_terima, c.tanggal_kirim) as leadtime_proses_kirim, 
                (a.banyak - if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit)) as outstanding_po_unit,
                (a.banyak_karton - if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton)) as outstanding_po_karton, a.kode_alamat,'$this->created_at', '$this->user_id'
        from
        (
            select 	a.id, a.id_ref, a.id_po_detail, a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, 
                    a.tglpesan, a.created, a.tipe, a.company, a.alamat, a.kode_alamat, 
                    a.kodeprod, a.namaprod, a.banyak, a.banyak_karton, 
                    a.harga, a.kode_prc, a.berat, a.volume, a.stock_akhir, a.rata, a.git, a.doi, 
                    a.status_terima, a.tanggal_terima, a.userid, a.deleted
            from db_po.t_temp_report_po_update a
            where a.supp = 002
        )a LEFT JOIN
        (
            select	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from	mpm.tbl_tabcomp a
            where 	a.`status` = 1
            group by site_code
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select 	a.id_po, a.no_asn, a.batch_number, a.nodo, a.ed, 
                    a.kodeprod, sum(a.jumlah_unit) as pemenuhan_unit, sum(a.jumlah_karton) as pemenuhan_karton, a.status_pemenuhan,
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, a.tanggal_tiba, a.keterangan, a.status_closed, a.signature,
                    a.created_date, a.created_by, a.last_updated, a.last_updated_by
            from    mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )c on a.id_ref = c.id_po and a.kodeprod = c.kodeprod
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_marguna);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_marguna);
    }

    public function insert_po_outstanding_jaya($tahun)
    {
        $insert_po_outstanding_jaya_agung = "
        insert into db_po.t_po_outstanding_jaya
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$this->created_at', '$this->user_id'
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=004 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_jaya_agung);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_jaya_agung);

    }

    public function insert_po_outstanding_strive($tahun)
    {
        $insert_po_outstanding_strive = "
        insert into db_po.t_po_outstanding_strive
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$this->created_at', '$this->user_id'
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=013 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_strive);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_strive);
    }

    public function insert_po_outstanding_hni($tahun)
    {
        $insert_po_outstanding_hni = "
        insert into db_po.t_po_outstanding_hni
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$this->created_at', '$this->user_id'
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=014 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_hni);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_hni);
    }

    public function insert_po_outstanding_mdj($tahun)
    {
        $insert_po_outstanding_mdj = "
        insert into db_po.t_po_outstanding_mdj
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$this->created_at', '$this->user_id'
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=015 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_mdj);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_mdj);
    }

    public function update_lock_po($data, $id_menu)
    {
        $this->db->where("id_menu", $id_menu);
        $this->db->update("db_temp.t_traffic", $data);
        return $this->db->affected_rows();
    }

    public function update_log_po_outstanding($data, $id_log)
    {
        $this->db->where("id", $id_log);
        $this->db->update("site.log_po_outstanding", $data);
        return $this->db->affected_rows();
    }

    public function update_master_principal($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("mpm.tabsupp", $data);
        return $this->db->affected_rows();
    }

    public function get_master_produk()
    {
        $query = "
            select *
            from site.master_product_with_harga a
        ";
        return $this->db->query($query);
    }

    public function update_master_produk($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("mpm.tabprod", $data);
        return $this->db->affected_rows();
    }

    public function get_supplier_by_supp($supp)
    {
        $query = "
            select *
            from site.master_supplier a
            where a.supp ='$supp'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_total_po($tahun, $bulan, $supp = "")
    {
        if ($supp) {
            $params_supp = " and a.supp = '$supp' ";
        }else{
            $params_supp = "";
        }

        $query = "
            select sum(a.total_value) as total_value, count(*) as count_po
            from mpm.po a 
            where a.deleted = 0 and year(a.tglpo) = $tahun and month(a.tglpo) = $bulan $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_total_po_groupby_supp($tahun, $bulan)
    {
        $query = "
            select a.supp, b.namasupp, a.total_value, a.count_po
            from 
            (
                select a.supp, sum(a.total_value) as total_value, count(*) as count_po
                from mpm.po a 
                where a.deleted = 0 and year(a.tglpo) = $tahun and month(a.tglpo) = $bulan 
                GROUP BY a.supp
            )a left join site.master_supplier b
                on a.supp = b.supp
        ";
        return $this->db->query($query);
    }
    
    public function insert_status_locked($id_po, $is_locked)
    {
        $this->db->insert('site.temp_spk_locked', ['id_po' => $id_po, 'is_locked' => $is_locked]);
        return $this->db->insert_id();
    }

    public function get_status_locked()
    {
        $query = "
            select *
            from site.temp_spk_locked a 
            order by a.id desc
            limit 1
        ";
        return $this->db->query($query);
    }

    public function get_purchase_plan()
    {
        $query = "
            SELECT a.*, b.namaprod, c.branch_name, c.nama_comp
            FROM site.spk_purchase_plan a
            LEFT JOIN site.master_product b on a.kodeprod = b.kodeprod
            LEFT JOIN site.master_site c on a.site_code = c.site_code
            ORDER BY a.bulan desc, c.nama_comp asc, a.kodeprod asc
            LIMIT 100
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_purchase_plan_with_bulan_sitecode_kodeprod($bulan, $site_code, $params_kodeprod)
    {
        $query = "
            SELECT *
            FROM site.spk_purchase_plan a
            where a.bulan = $bulan and a.site_code = '$site_code' and a.kodeprod = $params_kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_po_detail_actual_po_bulan($data)
    {
        $id_po = $data['id_po'];
        $site_code = $data['site_code'];
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];

        $query = "
            SELECT *
            FROM site.po_detail a
            WHERE a.id_ref = $id_po
        ";

        $po_detail = $this->db->query($query);

        foreach ($po_detail->result() as $key) {
            $sql = "
                SELECT b.kodeprod, SUM(b.banyak) as banyak
                FROM
                (
                    SELECT a.id
                    FROM mpm.po a
                    WHERE a.kode_alamat = '$site_code' and MONTH(a.tglpesan) = '$bulan' and YEAR(a.tglpesan) = '$tahun' and a.deleted = 0
                )a LEFT JOIN 
                (
                    SELECT a.id_ref, a.kodeprod, a.banyak 
                    from mpm.po_detail a
                    WHERE a.deleted_at is null and a.kodeprod = $key->kodeprod
                )b on a.id = b.id_ref
                GROUP BY b.kodeprod
            ";

            $data_actual = $this->db->query($sql);

            $update = [
                'actual_po_bulan_ini' => $data_actual->row('banyak') == null ? 0 : $data_actual->row('banyak')
            ];
            $this->db->where('id', $$key->id);
            $this->db->update('mpm.po_detail', $update);
        }
    }

    public function update_po_detail_pp_unit_and_selisih_po($data)
    {
        $id_po = $data['id_po'];
        $periode = $data['periode'];
        $site_code = $data['site_code'];
        
         // start update pp unit dan selisi_po, selisih_po berdasarkan (po_unit - actual_po_bulan_berjalan)
        $query = "
            UPDATE mpm.po_detail a
            LEFT JOIN (
                SELECT *
                FROM site.spk_purchase_plan a
                WHERE a.bulan = '$periode' and a.site_code = '$site_code'
                )b on a.kodeprod = b.kodeprod
            SET a.pp_unit = b.pp_unit,
            a.selisih_po = a.pp_unit - (a.actual_po_bulan_ini + a.banyak)
            where a.id_ref = $id_po
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        $this->db->query($query);
    }

    public function update_po_is_pp_approval($data)
    {
        $id_po = $data['id_po'];
        $periode = $data['periode'];
        $site_code = $data['site_code'];
        // start mengambil data po_detail untuk mengecek produk yang melibihi pp_unit
        $query = "
            select *
            from mpm.po_detail a
            inner join 
            (
                select a.kodeprod
                from site.spk_purchase_plan a
                where a.bulan = '$periode' and a.site_code = '$site_code'
            )b on a.kodeprod = b.kodeprod
            where a.id_ref = $id_po and a.pp_unit != 0 and a.selisih_po < 0
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        $data_po_detail = $this->db->query($query);

        // var_dump($data_po_detail);die;

        if ($data_po_detail->num_rows() < 1) {
            $data = [
                'is_pp_approval' =>  0
            ];
            $this->db->where('id', $id_po);
            $this->db->update('mpm.po', $data);
        } else {
            $data = [
                'is_pp_approval' =>  1
            ];
            $this->db->where('id', $id_po);
            $this->db->update('mpm.po', $data);
        }
        // end mengambil data po_detail untuk mengecek produk yang melibihi pp_unit
    }

    public function actual_po_bulan($data)
    {
        $site_code = $data['site_code'];
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];

        $query = "
            SELECT a.id, b.id as id_detail, b.kodeprod, SUM(b.banyak) as banyak
            FROM
            (
                SELECT a.id
                FROM mpm.po a
                WHERE a.kode_alamat = '$site_code' and MONTH(a.tglpo) = '$bulan' and YEAR(a.tglpo) = '$tahun' and a.deleted = 0
            )a LEFT JOIN 
            (
                SELECT a.id, a.id_ref, a.kodeprod, a.banyak 
                from mpm.po_detail a
                WHERE a.deleted_at is null
            )b on a.id = b.id_ref
            GROUP BY b.kodeprod
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function update_spk_purchase_plan($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.spk_purchase_plan', $data);
    }

    public function update_po_detail_actual_po_bulan_temp_spk($data)
    {
        $id_header = $data['id'];
        $site_code = $data['site_code'];
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];

        $query = "
            SELECT *
            FROM site.temp_spk_detail a
            WHERE a.id_header = $id_header
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        $spk_detail = $this->db->query($query);

        foreach ($spk_detail->result() as $key) {
            $sql = "
                SELECT a.id, b.id as id_detail, b.kodeprod, SUM(b.banyak) as banyak
                FROM
                (
                    SELECT a.id
                    FROM mpm.po a
                    WHERE a.kode_alamat = '$site_code' and MONTH(a.tglpesan) = '$bulan' and YEAR(a.tglpesan) = '$tahun' and a.deleted = 0
                )a LEFT JOIN 
                (
                    SELECT a.id, a.id_ref, a.kodeprod, a.banyak 
                    from mpm.po_detail a
                    WHERE a.deleted_at is null and a.kodeprod = $key->kodeprod
                )b on a.id = b.id_ref
                GROUP BY b.kodeprod
            ";

            echo "<pre>";
            print_r($sql);
            echo "</pre>";

            $data_actual = $this->db->query($sql);

            $update = [
                'actual_po_bulan_ini' => $data_actual->row('banyak') == null ? 0 : $data_actual->row('banyak')
            ];
            $this->db->where('id', $key->id);
            $this->db->update('site.temp_spk_detail', $update);
        }
    }

    public function update_po_detail_pp_unit_and_selisih_po_temp_spk($data)
    {
        $id_po = $data['id'];
        $periode = $data['periode'];
        $site_code = $data['site_code'];
        
         // start update pp unit dan selisi_po, selisih_po berdasarkan (po_unit - actual_po_bulan_berjalan)
        $query = "
            UPDATE site.temp_spk_detail a
            LEFT JOIN (
                SELECT *
                FROM site.spk_purchase_plan a
                WHERE a.bulan = '$periode' and a.site_code = '$site_code'
            )b on a.kodeprod = b.kodeprod
            LEFT JOIN (
                SELECT a.kodeprod, a.isisatuan
                FROM mpm.tabprod a
            )C on a.kodeprod = c.kodeprod
            SET a.pp_unit = b.pp_unit,
            a.selisih_po = b.pp_unit - (a.actual_po_bulan_ini + (a.jml_karton * c.isisatuan))
            where a.id_header = $id_po
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        $this->db->query($query);
    }

    public function get_principal($supp)
    {
        if($supp == '000')
        {
            $params_supp = "";
        }else{
            $params_supp = " and a.supp = '$supp' ";
        }

        $query = "
            select a.supp, a.namasupp
            from site.master_supplier a 
            where a.supp in ('001','005')
        ";
        return $this->db->query($query);
    }

    public function get_site_code($userid)
    {
        //get region by map_akses_region
        $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($userid);
        if ($get_region->num_rows() > 0) {
            // $get_region = $this->model_management_sales->get_region_by_map_akses_region($userid);
            // $this->session->set_flashdata("pesan", "user anda belum terdaftar di database region kami");
            // redirect('management_sales/sell_out_product');
            $region = 'all';
        }else{
            $get_region = $this->model_management_sales->get_region_by_map_akses_region($userid);
            if (!$get_region->num_rows() > 0) {
                $this->session->set_flashdata("pesan", "user anda belum terdaftar di database region kami");
                redirect('management_sales/sell_out_product');
            }

            $params_region = "";
            foreach ($get_region->result() as $r) 
            {
                $params_region.= ",".'"'.$r->region.'"';
                $region = preg_replace('/,/', '', $params_region,1);
                if ($params_region == 'all') {
                    $region = 'all';
                }else{
                    $region = $region;
                }   
            }
        }

        $get_site_code_by_region = $this->model_management_sales->get_site_code_by_region($region);

        $count_site_code = count($get_site_code_by_region->result());

        $site_code = "";
        foreach ($get_site_code_by_region->result() as $s) {
            $site_code.= ",'".$s->site_code."'";
        }
        $site_code = preg_replace('/,/', '', $site_code,1);
        // echo "site_code : ".$site_code;
        // die;

        return $site_code;

    }
}