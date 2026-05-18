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
        // $query = "
        //     select 	a.id, a.site_code, a.flag_selesai, a.signature, 
        //             a.created_at, a.created_by, a.deleted_at, a.deleted_by, a.updated_at, a.updated_by, 
        //             b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, 
        //             c.namaprod, c.moq, d.namasupp, b.signature as signature_detail
        //     from site.temp_spk a inner join (
        //         select *
        //         from site.temp_spk_detail a
        //         where a.deleted_at is null
        //     )b on a.id = b.id_header left join (
        //         select a.kodeprod, a.namaprod, a.moq
        //         from mpm.tabprod a 
        //     )c on b.kodeprod = c.kodeprod inner join (
        //         select a.supp, a.namasupp
        //         from mpm.tabsupp a 
        //     )d on b.supp = d.supp
        //     where a.created_by = $userid and a.flag_selesai is null
        // ";
        $query = "
            select 	a.id, a.site_code, a.flag_selesai, a.signature, 
                    a.created_at, a.created_by, a.deleted_at, a.deleted_by, a.updated_at, a.updated_by, 
                    b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, 
                    c.namaprod, c.moq, d.namasupp, b.signature as signature_detail
            from site.temp_spk a inner join (
                select *
                from site.temp_spk_detail a
                where a.deleted_at is null
            )b on a.id = b.id_header left join site.master_product c 
                on b.kodeprod = c.kodeprod inner join site.master_supplier d
                on b.supp = d.supp
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

  public function get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton($userid, $site_code, $bulan)
  {
      $query = "
          select 	a.id, a.site_code, a.flag_selesai, a.signature, 
                  a.created_at, a.created_by, a.deleted_at, a.deleted_by, a.updated_at, a.updated_by, 
                  b.id as id_detail, b.supp, b.kodeprod, b.jml_karton, 
                  c.namaprod, d.namasupp, b.signature as signature_detail, b.total_volume, b.total_berat, e.moq_us, 
                  if(f.pp_karton is null, 0, f.pp_karton) as pp_karton, 
                  b.jml_karton / if(f.pp_karton is null, 0, f.pp_karton) as ratio, 
                  if(f.pp_karton is null, 0, f.pp_karton) - b.jml_karton as selisih
          from site.temp_spk a inner join (
              select *
              from site.temp_spk_detail a
              where a.deleted_at is null and a.jml_karton > 0
          )b on a.id = b.id_header 
          left join site.master_product c on b.kodeprod = c.kodeprod 
          inner join site.master_supplier d on b.supp = d.supp
          left join site.master_site e on a.site_code = e.site_code
          left join (
              select a.kodeprod, a.pp_karton
              from site.spk_purchase_plan a 
              where a.bulan = '$bulan' and a.site_code = '$site_code'
          )f on b.kodeprod = f.kodeprod
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
        echo "<pre>";
        print_r($query);
        echo "</pre>";

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
                    round(a.jml_karton / if(c.average_karton is null,0,c.average_karton),2) as ratio, b.kode_prc, a.master_volume, a.master_berat, b.isisatuan, b.h_dp
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
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

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
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_tabprod_by_kodeprod_supp($kodeprod, $supp)
    {
        $query = "
            select *
            from site.master_product a
            where a.active = 1 and a.kodeprod = '$kodeprod' and a.supp = '$supp'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
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

    // public function get_po($advanced)
    // {
    //     $supp = $this->session->userdata('supp');
    //     if($supp == '000')
    //     {
    //         $params_supp = "";
    //     }else{
    //         $params_supp = " and a.supp = '$supp' ";
    //     }

    //     $get_kode_alamat = $this->model_inventory->get_kode_alamat();
    //     $code = '';
    //     foreach ($get_kode_alamat as $key) {
    //         $code.= ","."'".$key->kode_alamat."'";
    //     }
    //     $kode_alamat = preg_replace('/,/', '', $code,1);

    //     $params_site_code = " and a.kode_alamat in ($kode_alamat) ";

    //     // echo "params_site_code : ".$params_site_code;

    //     $tahun = date("Y");
    //     if ($advanced) 
    //     {
    //         $site_code  = $advanced['site_code'];

    //         if ($site_code == "all") {
    //             $params_site_code = "and a.kode_alamat in ($kode_alamat)";
    //         }else{
    //             $params_site_code = " and a.kode_alamat in ('$site_code') ";
    //         }

    //         $limit = $advanced['limit'];
    //         if ($limit) {
    //             $params_limit = " limit $limit ";
    //         }else{
    //             $params_limit = " limit 100 ";
    //         }

    //         $from = $advanced['from'];
    //         $to = $advanced['to'];
    //         $year = date('Y', strtotime($from));

    //         if ($from && $to) {
    //             $params_tgl = " and date(a.tglpesan) between '$from' and '$to' ";
    //             $params_tahun = "year(a.tglpesan) = $year";
    //         }else{
    //             $params_tgl = "";
    //         }

    //         $flag_delete = $advanced['flag_delete'];

    //         if ($flag_delete) {
    //             $params_flag_delete = " and a.deleted = $flag_delete ";
    //         }else{
    //             $params_flag_delete = " and a.deleted = 0 ";
    //         }

    //     }else{
    //         $params_site_code = $params_site_code;
    //         $params_limit = " limit 100 ";
    //         $params_tgl = "";
    //         $params_flag_delete = " and a.deleted = 0 ";
    //         $params_tahun = "year(a.tglpesan) = $tahun";
    //     }


    //     $query = "
    //         select 	a.id, a.userid, a.supp, a.nopo, a.tglpo, a.tglpesan, a.tipe, a.open, a.status, a.status_approval,
    //                 a.status_override, a.company, a.npwp, a.email, a.alamat, a.alamat_kirim,
    //                 a.note, a.po_ref, a.lock, a.kode_alamat, a.total_value, b.namasupp, c.branch_name,
    //                 if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp, a.signature, year(a.tglpesan) as tahun, a.is_pp_approval
    //         from mpm.po a left join site.master_supplier b 
    //             on a.supp = b.supp left join site.master_site c 
    //             on a.kode_alamat = c.site_code
    //         where $params_tahun $params_site_code $params_tgl $params_flag_delete $params_supp
    //         ORDER BY a.id desc 
    //         $params_limit
    //     ";

    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";

    //     return $this->db->query($query);

    // }

  public function get_po_new($advanced)
  {
    $id = $this->session->userdata('id');
    $supp = $this->session->userdata('supp');
    // echo "supp : ".$supp;
    if($supp == '000')
    {
        $params_supp = "";
        $flag_nopo = "";
    }else{
        $params_supp = " and a.supp = '$supp' ";
        $flag_nopo = "and a.nopo is not null";
    }

    //get region by map_akses_region
    $this->load->model('model_management_sales');
    $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($id);
    if ($get_region->num_rows() > 0) {
        $region = 'all';
    }else{
        $get_region = $this->model_management_sales->get_region_by_map_akses_region($id);
        if ($this->session->userdata('level') != 4) {

            if($this->session->userdata('username') != 'april_deltomed')
            {
                if (!$get_region->num_rows() > 0) {
                    redirect('management_office/kalender_data');
                }
            }
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
    // echo "region : ".$region;

    if ($this->session->userdata('level') != 4) {
        
        // jika username login adalah BM, seperti bagio, adi, dan sebagainya
        if ($this->session->userdata('username') == 'bagio' || $this->session->userdata('username') == 'bisman' || $this->session->userdata('username') == 'adhi' || $this->session->userdata('username') == 'rasyid') {
            $get_data = $this->model_management_sales->get_site_code_by_sub_region($region);
            $count_site_code = count($get_data->result());
            $site_code = "";
            foreach ($get_data->result() as $s) {
                $site_code.= ",'".$s->site_code."'";
            }
            $site_code = preg_replace('/,/', '', $site_code,1);
            // echo "site_code : ".$site_code;
        }else
        {
            $get_site_code_by_region = $this->model_management_sales->get_site_code_by_region($region);
            $count_site_code = count($get_site_code_by_region->result());
            $site_code = "";
            foreach ($get_site_code_by_region->result() as $s) {
                $site_code.= ",'".$s->site_code."'";
            }
            $site_code = preg_replace('/,/', '', $site_code,1);
        }

        $params_site_code = " and a.kode_alamat in ($site_code) ";
        
    }else{
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        $params_site_code = " and a.kode_alamat in ($kode_alamat) ";
    }


    $tahun = date("Y");
    if ($advanced) 
    {

        if ($site_code == "all") {
            $params_site_code = "and a.kode_alamat in ($site_code)";
        }else{
            $params_site_code = " and a.kode_alamat in ($site_code) ";
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
        select 	a.id, a.userid, a.supp, a.nopo, date(a.tglpo) as tglpo, date(a.tglpesan) as tglpesan, a.tipe, a.open, a.status, a.status_approval,
                a.status_override, a.company, a.npwp, a.email, a.alamat, a.alamat_kirim,
                a.note, a.po_ref, a.lock, a.kode_alamat, a.total_value, b.namasupp, c.branch_name,
                if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp, a.signature, year(a.tglpesan) as tahun, a.is_pp_approval
        from mpm.po a left join site.master_supplier b 
            on a.supp = b.supp left join site.master_site c 
            on a.kode_alamat = c.site_code
        where $params_tahun $params_site_code $params_tgl $params_flag_delete $params_supp $flag_nopo
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
    // echo "<pre>$query</pre>";
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

    public function update_temp_spk_detail($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("site.temp_spk_detail", $data);
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
            where a.bulan = '$bulan' and a.site_code = '$site_code' and a.kodeprod = '$params_kodeprod'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_po_detail_by_id_ref($id_po)
    {
        $query = "
            SELECT *
            FROM site.po_detail a
            WHERE a.id_ref = $id_po
        ";

        return $this->db->query($query);
    }

    public function get_po_kodeprod_dan_banyak($data)
    {
        $site_code = $data['site_code'];
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];
        $kodeprod = $data['kodeprod'];

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
                WHERE a.deleted_at is null and a.kodeprod = $kodeprod
            )b on a.id = b.id_ref
            GROUP BY b.kodeprod
        ";

        return $this->db->query($sql);
    }

    public function update_po_detail_pp_unit_and_selisih_po_by_id_ref($data)
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
            a.selisih_po = b.pp_unit - (a.actual_po_bulan_ini + a.banyak)
            where a.id_ref = $id_po
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        $this->db->query($query);
    }

    public function get_po_detail_by_id_ref_pp_unit_selisih_po($data)
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

        return $this->db->query($query);
    }

  public function actual_po_bulan($data)
  {
    $site_code = $data['site_code'];
    $bulan = $data['bulan'];
    $tahun = $data['tahun'];

    $query = "
      select a.id, b.id_ref, b.kodeprod, sum(b.banyak_karton) as banyak_karton
      from mpm.po a left join (
        select a.id, a.id_ref, a.kodeprod, a.banyak_karton 
        from mpm.po_detail a
        where a.deleted_at is null
      )b on a.id = b.id_ref
      where a.kode_alamat = '$site_code' and month(a.tglpo) = '$bulan' and year(a.tglpo) = '$tahun' and a.deleted = 0
      group by b.kodeprod
    ";


      // $query = "
      //     SELECT a.id, b.id as id_detail, b.kodeprod, SUM(b.banyak_karton) as banyak_karton
      //     FROM
      //     (
      //         SELECT a.id
      //         FROM mpm.po a
      //         WHERE a.kode_alamat = '$site_code' and MONTH(a.tglpo) = '$bulan' and YEAR(a.tglpo) = '$tahun' and a.deleted = 0
      //     )a LEFT JOIN 
      //     (
      //         SELECT a.id, a.id_ref, a.kodeprod, a.banyak_karton 
      //         from mpm.po_detail a
      //         WHERE a.deleted_at is null
      //     )b on a.id = b.id_ref
      //     GROUP BY b.kodeprod
      // ";

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";

    return $this->db->query($query);
  }

    public function get_temp_spk_detail_by_id_header($id_header)
    {
        $query = "
            SELECT *
            FROM site.temp_spk_detail a
            WHERE a.id_header = $id_header
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function update_temp_spk_detail_pp_unit_and_selisih_po_by_id_header($data)
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
            $params_supp = "where a.supp in ('001','005')";
        }else{
            $params_supp = " where a.supp = '$supp' ";
        }

        $query = "
            select a.supp, a.namasupp
            from site.master_supplier a 
            $params_supp
        ";
        return $this->db->query($query);
    }

    public function get_site_code($userid)
    {
        //get region by map_akses_region
        $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($userid);
        if ($get_region->num_rows() > 0) {
            $region = 'all';
        }else{
            $get_region = $this->model_management_sales->get_region_by_map_akses_region($userid);
            if (!$get_region->num_rows() > 0) {
                // $this->session->set_flashdata("pesan", "user anda belum terdaftar di database region kami");
                // redirect('spk/po_outstanding');
                $get_kode_alamat = $this->model_inventory->get_kode_alamat();
                $code = '';
                foreach ($get_kode_alamat as $key) {
                    $code.= ","."'".$key->kode_alamat."'";
                }
                $kode_alamat = preg_replace('/,/', '', $code,1);

                // echo "kode_alamat : ".$kode_alamat;
                // die;

                return $kode_alamat;

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

            // print_r($region);
        }

        // die;

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

    public function get_do_deltomed()
    {
        $query = "
            select a.tgldo, count(distinct(a.nodo)) as count_nodo, count(distinct(a.nopo)) as count_nopo, b.count_surat_jalan
            from site.t_do_deltomed a  left join (
                select count(*) as count_surat_jalan, a.tgldo
                from   site.surat_jalan_deltomed a
                where a.deleted_at is null
                group by a.tgldo
            )b on a.tgldo = b.tgldo
            group by a.tgldo
            order by a.tgldo desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }
    public function get_t_do_deltomed_by_tgldo($tgldo)
    {
        $sql = "
            select * from site.t_do_deltomed a
            where a.tgldo = date_format('$tgldo', '%d/%m/%Y')
        ";
        return $this->db->query($sql);
    }
    
    public function update_nopo()
    {
        $query = "
            update db_po.t_do_deltomed a
            set a.nopo = replace(a.nopo,'.1',''),
                    a.nopo = replace(a.nopo,'.2',''),
                    a.nopo = replace(a.nopo,'.3',''),
                    a.nopo = replace(a.nopo,'.4',''),
                    a.nopo = replace(a.nopo,'.5',''),
                    a.nopo = replace(a.nopo,'.6',''),
                    a.nopo = replace(a.nopo,'.7',''),
                    a.nopo = replace(a.nopo,'.8',''),
                    a.nopo = replace(a.nopo,'.9',''),
                    a.nopo = replace(a.nopo,'.10','')
            ";

        $this->db->query($query);
    }

    public function update_nopo_dl()
    {
        $query = "
                update db_po.t_do_deltomed a
                set a.nopo = replace(a.nopo,'D0','DL0')
                where a.nopo like 'D0%'
            ";

        $this->db->query($query);
    }

    public function delete_t_do_deltomed_by_tgldo($tgl)
    {
        $sql = "delete from site.t_do_deltomed where tgldo = '$tgl'";
        $this->db->query($sql);
    }

    // public function get_po_with_nopo($advanced)
    // {        
    //     // $tahun = date("Y");
    //     if ($advanced) 
    //     {
    //         $site_code  = $advanced['site_code'];
    //         $params_site_code = " and a.kode_alamat in ($site_code) ";
    //         $from = $advanced['from'];
    //         $to = $advanced['to'];
    //         $params_tgl = " and date(a.tglpesan) between '$from' and '$to' ";
    //         $supp = $advanced['supp'];
    //         $params_supp = ($supp == 'all') ? "" : "and a.supp = '$supp'";

    //         $flag_delete = $advanced['flag_delete'];

    //         if ($flag_delete) {
    //             $params_flag_delete = " and a.deleted = $flag_delete ";
    //         }else{
    //             $params_flag_delete = " and a.deleted = 0 ";
    //         }

    //     }else{
    //         echo "else";
    //         $params_site_code = " and a.kode_alamat in ($site_code) ";
    //         $params_tgl = "";
    //         $params_flag_delete = " and a.deleted = 0 ";
    //         $params_tahun = " and year(a.tglpesan) = $tahun";
    //         $params_supp = "";
    //     }

    //     $query = "
    //         select 	md5(a.id) as id, a.id as po_id, a.userid, a.supp, a.nopo, 
    //                 a.tglpo, a.tglpesan, a.tipe, a.open, a.status, a.status_approval,
    //                 a.status_override, a.company, a.npwp, a.email, a.alamat, a.alamat_kirim,
    //                 a.note, a.po_ref, a.lock, a.kode_alamat, a.total_value, b.namasupp, c.branch_name,
    //                 if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp, 
    //                 a.signature, year(a.tglpesan) as tahun, a.is_pp_approval, d.count_do
    //         from mpm.po a left join site.master_supplier b 
    //             on a.supp = b.supp left join site.master_site c 
    //             on a.kode_alamat = c.site_code left join (
    //                 select a.nopo, count(distinct(a.nodo)) as count_do
    //                 from site.t_do_deltomed a
    //                 where a.nopo not in ('') and a.nopo not like '%2022' and a.nopo not like '%2023'
    //                 GROUP BY a.nopo
    //                 union all
    //                 select a.nopo, count(distinct(a.nodo)) as count_do
    //                 from db_po_new.t_do_us a 
    //                 where a.nopo not in ('') and a.nopo not like '%2022' and a.nopo not like '%2023' 
    //                 GROUP BY a.nopo
    //             )d on a.nopo = d.nopo
    //         where a.nopo is not null $params_site_code $params_tgl $params_flag_delete $params_supp
    //         ORDER BY a.id desc 
    //     ";

    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";

    //     return $this->db->query($query);
    // }

    public function get_po_with_nopo($advanced)
    {        
        // $tahun = date("Y");
        if ($advanced) 
        {
            $site_code  = $advanced['site_code'];
            $params_site_code = " and a.kode_alamat in ($site_code) ";
            $from = $advanced['from'];
            $to = $advanced['to'];
            $params_tgl = " and date(a.tglpesan) between '$from' and '$to' ";
            $supp = $advanced['supp'];
            $params_supp = ($supp == 'all') ? "" : "and a.supp = '$supp'";

            $flag_delete = $advanced['flag_delete'];

            if ($flag_delete) {
                $params_flag_delete = " and a.deleted = $flag_delete ";
            }else{
                $params_flag_delete = " and a.deleted = 0 ";
            }

        }else{
            echo "else";
            $params_site_code = " and a.kode_alamat in ($site_code) ";
            $params_tgl = "";
            $params_flag_delete = " and a.deleted = 0 ";
            $params_tahun = " and year(a.tglpesan) = $tahun";
            $params_supp = "";
        }

        $query = "
            select 	md5(a.id) as id, a.id as po_id, a.userid, a.supp, a.nopo, 
                    a.tglpo, a.tglpesan, a.tipe, a.open, a.status, a.status_approval,
                    a.status_override, a.company, a.npwp, a.email, a.alamat, a.alamat_kirim,
                    a.note, a.po_ref, a.lock, a.kode_alamat, a.total_value, b.namasupp, c.branch_name,
                    if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp, 
                    a.signature, year(a.tglpesan) as tahun, a.is_pp_approval, d.count_do, e.tanggal_terima,
                    d.list_nodo
            from mpm.po a left join site.master_supplier b 
                on a.supp = b.supp left join site.master_site c 
                on a.kode_alamat = c.site_code left join (
                    select a.nopo, count(distinct(a.nodo)) as count_do, GROUP_CONCAT(DISTINCT a.nodo) as list_nodo
                    from site.t_do_deltomed a
                    where a.nopo not in ('') and a.nopo not like '%2022' and a.nopo not like '%2023'
                    GROUP BY a.nopo
                    union all
                    select a.nopo, count(distinct(a.nodo)) as count_do, GROUP_CONCAT(DISTINCT a.nodo) as list_nodo
                    from db_po_new.t_do_us a 
                    where a.nopo not in ('') and a.nopo not like '%2022' and a.nopo not like '%2023' 
                    GROUP BY a.nopo
                )d on a.nopo = d.nopo left join (
                    select a.nodo, a.nopo, b.tanggal_terima
                    from 
                    (
                        select a.nodo, a.nopo
                        from site.t_do_deltomed a 
                        GROUP BY a.nodo
                    )a left join (
                        select a.nodo, a.tanggal_terima, a.created_at
                        from site.konfirmasi_do a
                        GROUP BY a.nodo
                    )b on a.nodo = b.nodo 
                    group by a.nopo
                )e on a.nopo = e.nopo
            where a.nopo is not null $params_site_code $params_tgl $params_flag_delete $params_supp
            ORDER BY a.id desc 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_po_by_id($signature)
    {
        $query = "
            select * 
            from mpm.po a 
            where md5(a.id) = '$signature'
        "; 
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_do($id, $nopo)
    {
        $sql = "
            select a.id, b.nodo, a.kodeprod, a.namaprod, b.qty, str_to_date(b.tgldo,'%d/%m/%Y') as tgldo, c.tanggal_terima, b.batch_number
            from
            (
                select a.id, a.kodeprod, b.kodeprod_deltomed, a.status_terima, a.tanggal_terima, a.namaprod, a.deleted
                from mpm.po_detail a 
                left join mpm.tabprod b on a.kodeprod = b.kodeprod
                where a.id_ref = $id and a.deleted = 0
            )a 
            left join
            (
                select *
                from site.t_do_deltomed a
                where a.nopo = '$nopo'
            )b on a.kodeprod_deltomed = b.kodeprod_delto
            left join
            (
                select *
                from mpm.konfirmasi_do
            )c on a.id = c.id_po_detail and b.batch_number = c.batch_number
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        
        return $this->db->query($sql);
    }

    public function get_po_detail_join_do_join_konfirmasi_do($id, $nopo)
    {
        $sql = "
            select a.id, b.nodo, a.kodeprod, a.namaprod, b.qty, str_to_date(b.tgldo,'%d/%m/%Y') as tgldo, c.tanggal_terima, b.batch_number
            from
            (
                select a.id, a.kodeprod, b.kodeprod_deltomed, a.status_terima, a.tanggal_terima, a.namaprod, a.deleted
                from mpm.po_detail a 
                left join mpm.tabprod b on a.kodeprod = b.kodeprod
                where a.id_ref = $id and a.deleted = 0
            )a 
            left join
            (
                select *
                from site.t_do_deltomed a
                where a.nopo = '$nopo'
            )b on a.kodeprod_deltomed = b.kodeprod_delto
            left join
            (
                select *
                from mpm.konfirmasi_do
            )c on a.id = c.id_po_detail and b.batch_number = c.batch_number
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        
        return $this->db->query($sql);
    }

    public function get_do_deltomed_by_nopo($nopo)
    {
        // $query = "
        //     select 	a.nodo, str_to_date(a.tgldo,'%d/%m/%Y') as tgldo, a.kodedp, a.company, 
        //             a.kodeprod_delto, a.namaprod, a.qty, a.nopo,
        //             a.batch_number, a.ed, c.tanggal_terima, c.id, b.kodeprod, c.qty as qty_terima
        //     from site.t_do_deltomed a 
        //     left join (
        //         select a.kodeprod, a.kodeprod_deltomed
        //         from site.master_product a
        //         where a.supp = 001
        //     )b on a.kodeprod_delto = b.kodeprod_deltomed left join (
        //         select 	a.id, a.id_po_detail, a.nodo, a.tgldo, 
        //                 a.kodeprod, a.namaprod, a.batch_number, a.qty,
        //                 a.tanggal_terima
        //         from site.konfirmasi_do a
        //     )c on a.nodo = c.nodo and b.kodeprod = c.kodeprod and a.batch_number = c.batch_number
        //     where a.nopo = '$nopo'
        // ";
        $query = "
            select 	a.nodo, a.tgldo, a.kodedp, a.company, 
                    a.kodeprod_delto, a.namaprod, a.qty, a.nopo,
                    a.batch_number, a.ed, c.tanggal_terima, c.id, b.kodeprod, c.qty as qty_terima,
                    d.`status`, d.nama_status, d.kode_surat_jalan
            from site.t_do_deltomed a 
            left join (
                select a.kodeprod, a.kodeprod_deltomed
                from site.master_product a
                where a.supp = 001
            )b on a.kodeprod_delto = b.kodeprod_deltomed left join (
                select 	a.id, a.id_po_detail, a.nodo, a.tgldo, 
                        a.kodeprod, a.namaprod, a.batch_number, a.qty,
                        a.tanggal_terima
                from site.konfirmasi_do a
            )c on a.nodo = c.nodo and b.kodeprod = c.kodeprod and a.batch_number = c.batch_number left join (
                select a.nodo, a.kode_surat_jalan, a.`status`, a.nama_status
                from site.surat_jalan_deltomed a 
                where a.nopo = '$nopo'
            )d on a.nodo = d.nodo
            where a.nopo = '$nopo'
        ";

        // echo "<pre>";
        // print_r($query);    
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_do_us_by_nopo($nopo)
    {
        // $query = "
        //     select 	a.nodo, str_to_date(a.tgldo,'%Y%m%d') as tgldo, a.nopo, a.kodeprod, a.namaprod, a.banyak as qty, 
        //             '' as batch_number, b.tanggal_terima, b.qty as qty_terima
        //     from db_po_new.t_do_us a left join (
        //         select a.nodo, a.kodeprod, a.batch_number, a.tanggal_terima, a.qty
        //         from site.konfirmasi_do a
        //     )b on a.nodo = b.nodo and a.kodeprod = b.kodeprod
        //     where a.nopo = '$nopo'      
        // ";

        // membuat dummy batch number us
        // $query = "
        //      select a.nodo, str_to_date(a.tgldo,'%Y%m%d') as tgldo, a.nopo, a.kodeprod, a.namaprod, a.banyak as qty, 
        //             b.tanggal_terima, b.qty as qty_terima, 
        //             if(b.batch_number is null, concat(a.nodo,a.tgldo,a.kodeprod,a.id), b.batch_number) as batch_number, 'belum tersedia' as batch_number_info
        //     from db_po_new.t_do_us a left join (
        //         select a.nodo, a.kodeprod, a.batch_number, a.tanggal_terima, a.qty
        //         from site.konfirmasi_do a
        //     )b on a.nodo = b.nodo and a.kodeprod = b.kodeprod and concat(a.nodo,a.tgldo,a.kodeprod,a.id) = b.batch_number
        //     where a.nopo = '$nopo'  
        // ";

        // $query = "
        //      select a.nodo, str_to_date(a.tgldo,'%Y%m%d') as tgldo, a.nopo, a.kodeprod, a.namaprod, a.banyak as qty, 
        //             b.tanggal_terima, b.qty as qty_terima, 
        //             if(b.batch_number is null, concat(a.nodo,a.tgldo,a.kodeprod,a.id), b.batch_number) as batch_number, 
        //             'belum tersedia' as batch_number_info
        //     from db_po_new.t_do_us a left join (
        //         select a.nodo, a.kodeprod, a.batch_number, a.tanggal_terima, a.qty
        //         from site.konfirmasi_do a
        //     )b on a.nodo = b.nodo and a.kodeprod = b.kodeprod and concat(a.nodo,a.tgldo,a.kodeprod,a.id) = b.batch_number
        //     where a.nopo = '$nopo'  
        // ";

        // $query = "
        //     select 	a.nodo, str_to_date(a.tgldo,'%d/%m/%Y') as tgldo, a.kodedp, a.company, 
        //             a.kodeprod_delto, a.namaprod, a.qty, a.nopo,
        //             a.batch_number, a.ed, c.tanggal_terima, c.id, b.kodeprod, c.qty as qty_terima,
        //             d.`status`, d.nama_status, d.kode_surat_jalan
        //     from site.t_do_deltomed a 
        //     left join (
        //         select a.kodeprod, a.kodeprod_deltomed
        //         from site.master_product a
        //         where a.supp = 001
        //     )b on a.kodeprod_delto = b.kodeprod_deltomed left join (
        //         select 	a.id, a.id_po_detail, a.nodo, a.tgldo, 
        //                 a.kodeprod, a.namaprod, a.batch_number, a.qty,
        //                 a.tanggal_terima
        //         from site.konfirmasi_do a
        //     )c on a.nodo = c.nodo and b.kodeprod = c.kodeprod and a.batch_number = c.batch_number left join (
        //         select a.nodo, a.kode_surat_jalan, a.`status`, a.nama_status
        //         from site.surat_jalan_deltomed a 
        //         where a.nopo = '$nopo'
        //     )d on a.nodo = d.nodo
        //     where a.nopo = '$nopo'
        // ";

        $query = "
            select a.nodo, str_to_date(a.tgldo,'%Y%m%d') as tgldo, a.nopo, a.kodeprod, a.namaprod, a.banyak as qty, 
                    b.tanggal_terima, b.qty as qty_terima, 
                    a.batch_number, a.ed,
                    c.`status`, c.nama_status, c.kode_surat_jalan
            from db_po_new.t_do_us a left join (
                select a.nodo, a.kodeprod, a.batch_number, a.tanggal_terima, a.qty
                from site.konfirmasi_do a
            )b on a.nodo = b.nodo and a.kodeprod = b.kodeprod and a.batch_number = b.batch_number left join (
                select a.nodo, a.kode_surat_jalan, a.`status`, a.nama_status
                from site.surat_jalan a 
                where a.nopo = '$nopo'
            )c on a.nodo = c.nodo
            where a.nopo = '$nopo'  
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_data_konfirmasi_do_by_id_batch_nodo($id_po_detail, $batch_number, $nodo)
    {
        $sql = "
            select *
            from mpm.konfirmasi_do a
            where a.id_po_detail = $id_po_detail and a.batch_number = '$batch_number' and a.nodo = '$nodo'
        ";

        return $this->db->query($sql);
    }

    public function get_konfirmasi_do($data)
    {
        $nodo = $data['nodo'];
        $kodeprod = $data['kodeprod'];
        $batch_number = $data['batch_number'];
        // die;

        $query = "
            select *
            from site.konfirmasi_do a
            where a.nodo = '$nodo' and a.kodeprod = '$kodeprod' and a.batch_number = '$batch_number'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function input_konfirmasi_do($data)
    {
        $this->db->insert('site.konfirmasi_do', $data);
        return $this->db->insert_id();
    }

    public function update_konfirmasi_do($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.konfirmasi_do', $data);
    }

  public function update_po_detail_actual_po_bulan($data, $actual_po)
  {
    foreach ($actual_po->result() as $key) {
      $update = [
        'actual_po_bulan_ini' => $key->banyak_karton
      ];
      $this->db->where('id_ref', $data['id_po']);
      $this->db->where('kodeprod', $key->kodeprod);
      $this->db->update('mpm.po_detail', $update);
    }
  }

  public function update_po_detail_pp_unit_and_selisih_po($data)
  {
    $id_po = $data['id_po'];
    $periode = $data['periode'];
    $site_code = $data['site_code'];
      
    // start update pp unit dan selisi_po, selisih_po berdasarkan (po_unit - actual_po_bulan_berjalan)
    // $query = "
    //     UPDATE mpm.po_detail a
    //     LEFT JOIN (
    //         SELECT *
    //         FROM site.spk_purchase_plan a
    //         WHERE a.bulan = '$periode' and a.site_code = '$site_code'
    //         )b on a.kodeprod = b.kodeprod
    //     SET a.pp_unit = b.pp_unit,
    //     a.selisih_po = a.pp_unit - (a.actual_po_bulan_ini + a.banyak)
    //     where a.id_ref = $id_po
    // ";
    $query = "
        UPDATE mpm.po_detail a
        LEFT JOIN (
            SELECT *
            FROM site.spk_purchase_plan a
            WHERE a.bulan = '$periode' and a.site_code = '$site_code'
            )b on a.kodeprod = b.kodeprod
        SET a.pp_karton = b.pp_karton,
        a.selisih_po = a.pp_karton - (a.actual_po_bulan_ini + a.banyak_karton)
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
    
    public function get_po_do_konfirmasi_do($from, $to, $site_code, $supp)
    {
        $query = "
            select 	a.nopo, date(a.tglpo) as tglpo, b.namasupp, c.branch_name,
                    if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp,
                    d.nodo, d.tgldo, d.kodeprod, d.kodeprod_delto, d.qty, e.tanggal_terima, e.qty as qty_terima
            from mpm.po a left join site.master_supplier b 
                on a.supp = b.supp left join site.master_site c 
                on a.kode_alamat = c.site_code left join (
                    select a.nopo, a.nodo, a.tgldo, a.kodeprod, a.kodeprod_delto, a.batch_number, a.qty
                    from site.t_do_deltomed a
                    where 	a.nopo not in ('') and a.nopo not like '%2021' and 
                                a.nopo not like '%2022' and a.nopo not like '%2023' and 
                                a.nopo not like '%CANCEL' and 
                                a.nopo not like '%REVISI'
                )d on a.nopo = d.nopo left join (
                    select a.nodo, a.kodeprod, a.batch_number, a.tanggal_terima, a.qty
                    from site.konfirmasi_do a 
                )e on d.nodo = e.nodo and d.batch_number = e.batch_number and d.kodeprod = e.kodeprod
            where a.nopo is not null  and date(a.tglpo) between '$from' and '$to' and a.kode_alamat in ($site_code) and a.deleted = 0 and a.supp = '$supp'
            ORDER BY a.id desc 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_po_do_konfirmasi_do_us($from, $to, $site_code, $supp)
    {
        // $query = "
        //     select  a.nopo, date(a.tglpo) as tglpo, b.namasupp, c.branch_name,
        //             if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp,
        //             d.nodo, d.tgldo, d.kodeprod, d.qty, d.tanggal_terima, d.qty_terima
        //     from mpm.po a left join site.master_supplier b 
        //             on a.supp = b.supp left join site.master_site c 
        //             on a.kode_alamat = c.site_code left join (
        //                 select 	a.nodo, str_to_date(a.tgldo,'%Y%m%d') as tgldo, a.nopo, 
        //                         a.kodeprod, a.namaprod, a.banyak as qty, b.tanggal_terima, b.qty as qty_terima, 
        //                         if(b.batch_number is null, concat(a.nodo,a.tgldo,a.kodeprod,a.id), b.batch_number) as batch_number, 'belum tersedia' as batch_number_info
        //                 from db_po_new.t_do_us a left join (
        //                     select a.nodo, a.kodeprod, a.batch_number, a.tanggal_terima, a.qty
        //                     from site.konfirmasi_do a
        //                 )b on a.nodo = b.nodo and a.kodeprod = b.kodeprod and a.batch_number = b.batch_number                        
        //                 order by a.kodeprod
        //             )d on a.nopo = d.nopo
        //     where 	a.nopo is not null  and date(a.tglpo) between '$from' and '$to' and 
        //             a.kode_alamat in ($site_code) and a.deleted = 0 and a.supp = '005' 
        //     order by a.nopo asc, d.kodeprod
        
        // ";

        $query = "
            select  a.nopo, date(a.tglpo) as tglpo, b.namasupp, c.branch_name,
                    if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp,
                    d.nodo, d.tgldo, d.kodeprod, d.qty, d.tanggal_terima, d.qty_terima
            from mpm.po a left join site.master_supplier b 
                    on a.supp = b.supp left join site.master_site c 
                    on a.kode_alamat = c.site_code left join (
                        select 	a.nodo, str_to_date(a.tgldo,'%Y%m%d') as tgldo, a.nopo, 
                                a.kodeprod, a.namaprod, a.banyak as qty, b.tanggal_terima, b.qty as qty_terima, 
                                if(b.batch_number is null, concat(a.nodo,a.tgldo,a.kodeprod,a.id), b.batch_number) as batch_number, 'belum tersedia' as batch_number_info
                        from db_po_new.t_do_us a left join (
                            select a.nodo, a.kodeprod, a.batch_number, a.tanggal_terima, a.qty
                            from site.konfirmasi_do a
                        )b on a.nodo = b.nodo and a.kodeprod = b.kodeprod and a.batch_number = b.batch_number
                    )d on a.nopo = d.nopo
            where 	a.nopo is not null  and a.tglpo >= '$from' and a.tglpo <= '$to' and 
                    a.kode_alamat in ($site_code) and a.deleted = 0 and a.supp = '005' 
            order by a.nopo asc, d.kodeprod
        
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        // $query = "
        //     select 	a.nopo, date(a.tglpo) as tglpo, b.namasupp, c.branch_name,
        //             if(c.nama_comp is null, a.company, c.nama_comp) as nama_comp,
        //             d.nodo, d.tgldo, d.kodeprod, d.kodeprod_delto, d.qty, e.tanggal_terima, e.qty as qty_terima
        //     from mpm.po a left join site.master_supplier b 
        //         on a.supp = b.supp left join site.master_site c 
        //         on a.kode_alamat = c.site_code left join (
        //             select a.nopo, a.nodo, a.tgldo, a.kodeprod, a.kodeprod_delto, a.batch_number, a.qty
        //             from site.t_do_deltomed a
        //             where 	a.nopo not in ('') and a.nopo not like '%2021' and 
        //                         a.nopo not like '%2022' and a.nopo not like '%2023' and 
        //                         a.nopo not like '%CANCEL' and 
        //                         a.nopo not like '%REVISI'
        //         )d on a.nopo = d.nopo left join (
        //             select a.nodo, a.kodeprod, a.batch_number, a.tanggal_terima, a.qty
        //             from site.konfirmasi_do a 
        //         )e on d.nodo = e.nodo and d.batch_number = e.batch_number and d.kodeprod_delto = e.kodeprod
        //     where a.nopo is not null  and date(a.tglpo) between '$from' and '$to' and a.kode_alamat in ($site_code) and a.deleted = 0 and a.supp = '$supp'
        //     ORDER BY a.id desc 
        // ";
        return $this->db->query($query);
    }

    public function insert_do_deltomed($data)
    {
        $this->db->insert('site.t_do_deltomed', $data);
        return $this->db->insert_id();
    }

    public function get_master_product_by_kodeprod_deltomed($kodeprod_deltomed)
    {
        $sql = "
            select *
            from site.master_product a
            where a.kodeprod_deltomed = '$kodeprod_deltomed'
        ";

        return $this->db->query($sql);
    }

    public function get_do_us()
    {
        $query = "
            select 	a.nodo, a.tgldo, a.nopo, 
                    count(DISTINCT(a.nopo)) as count_nopo, 
                    count(DISTINCT(a.nodo)) as count_nodo,
                    b.count_surat_jalan
            from db_po.t_do_us a left join (
                select a.tgldo, count(*) as count_surat_jalan, replace(a.tgldo, '-','') as tgldo_convert
                from	site.surat_jalan a
                where a.deleted_at is null
                group by a.tgldo
            )b on a.tgldo = b.tgldo_convert
            group by a.tgldo
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_do_us_header($data)
    {
        $this->db->insert('db_po.t_do_us_header', $data);
        return $this->db->insert_id();
    }

    public function delete_do_us_header($tgldo)
    {
        $this->db->where('tgldo', $tgldo);
        $this->db->delete('db_po.t_do_us_header');
        return $this->db->affected_rows();
    }

    public function delete_do_us_detail($tgldo)
    {
        $this->db->where('tgldo', $tgldo);
        $this->db->delete('db_po.t_do_us_detail');
        return $this->db->affected_rows();
    }

    public function insert_do_us_detail($data)
    {
        $this->db->insert('db_po.t_do_us_detail', $data);
        return $this->db->insert_id();
    }

    public function delete_do_us($tgldo)
    {
        $this->db->where('tgldo', $tgldo);
        $this->db->delete('db_po.t_do_us');
        return $this->db->affected_rows();
    }

    public function insert_do_us($tgldo, $id, $created)
    {
        $query = "
            insert into db_po.t_do_us
            select	'',a.kode, a.nodo, a.tgldo, b.batch_number, b.ed, a.kodedp, a.company, a.nopo,
                    b.kodeprod, c.namaprod, b.banyak, $id, '$created'
            from 	db_po.t_do_us_header a LEFT JOIN db_po.t_do_us_detail b
                        on a.nodo = b.nodo and a.tgldo = b.tgldo LEFT JOIN 
                        (
                            select a.kodeprod, a.namaprod
                            from site.master_product a 				
                        )c on b.kodeprod = c.kodeprod
            where		a.tgldo = '$tgldo'   
        ";

        // echo "<pre>";  

        return $this->db->query($query);
    }

    public function replace_do_us($tgldo)
    {
        $query = "
            update db_po.t_do_us a
            set a.nopo = replace(a.nopo,'\n','')
            where a.tgldo = '$tgldo'
        ";
        return $this->db->query($query);
    }

    public function get_nodo_us_group_by_nodo_by_tgldo($tgldo)
    {
        $query = "
            select a.nodo, a.nopo
            from db_po.t_do_us a 
            where a.tgldo = '$tgldo'
            GROUP BY a.nodo
        ";
        return $this->db->query($query);
    }   

    public function get_data_po($nopo)
    {
        $query = "
            select a.kode_alamat, a.tglpo, a.company, a.alamat as alamat_po, a.alamat_kirim as alamat_kirim_po
            from mpm.po a 
            where a.nopo = '$nopo'
        ";
        return $this->db->query($query);
    }

    public function get_t_alamat($kode_alamat)
    {
        $query = "            
            select a.kode_alamat, a.alamat 
            from mpm.t_alamat a 
            where a.kode_alamat = '$kode_alamat' and a.`status` = 1
            limit 1
        ";
        return $this->db->query($query);
    }

    public function generate_kode_surat_jalan($created_at){

        $bulan_now = date('m',strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            select a.kode_surat_jalan, a.urut
            from 
            (
                select	a.kode_surat_jalan, 
                        if(right(substr(a.kode_surat_jalan,5,4),1)='/',concat('0',substr(a.kode_surat_jalan,5,3)),substr(a.kode_surat_jalan,5,4)) as urut, 			
                        a.created_by, a.created_at
                from site.surat_jalan a
                where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now
            )a ORDER BY a.urut desc limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $nomor_ajuan_current = $this->db->query($query);
        if ($nomor_ajuan_current->num_rows() > 0) {
            
            $params_urut = $nomor_ajuan_current->row()->urut + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "SURATJALAN-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "SURATJALAN-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "SURATJALAN-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "SURATJALAN-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    public function generate_kode_surat_jalan_safe($created_at) 
    {
        try {
            // Validasi input
            if (empty($created_at)) {
                $created_at = date('Y-m-d H:i:s');
            }
            
            $bulan_now = date('m', strtotime($created_at));
            $romawi = $this->getRomawi($bulan_now);
            $tahun_now = date('Y', strtotime($created_at));
    
            // Query untuk mendapatkan nomor urut terakhir
            $query = "
                SELECT 
                    kode_surat_jalan,
                    CAST(
                        SUBSTRING(
                            SUBSTRING_INDEX(kode_surat_jalan, '/', 1),
                            LOCATE('-', kode_surat_jalan) + 1
                        ) AS UNSIGNED
                    ) AS urut
                FROM site.surat_jalan 
                WHERE YEAR(created_at) = " . (int)$tahun_now . "
                AND MONTH(created_at) = " . (int)$bulan_now . "
                AND kode_surat_jalan LIKE 'DOU-%/MPM/%'
                ORDER BY urut DESC 
                LIMIT 1
            ";
    
            $result = $this->db->query($query);
            
            if ($result && $result->num_rows() > 0) {
                $last_urut = $result->row()->urut;
                $params_urut = $last_urut + 1;
            } else {
                $params_urut = 1;
            }
    
            // Format dengan leading zero untuk 5 digit
            $formatted_urut = str_pad($params_urut, 5, '0', STR_PAD_LEFT);
            
            // Generate kode surat jalan dengan format 5 digit
            $generate = "DOU-{$formatted_urut}/MPM/{$romawi}/{$tahun_now}";
            
            return $generate;
            
        } catch (Exception $e) {
            // Log error atau handle sesuai kebutuhan
            error_log("Error generating kode surat jalan: " . $e->getMessage());
            
            // Return default dengan format 5 digit
            $romawi_default = $this->getRomawi(date('m'));
            $tahun_default = date('Y');
            return "DOU-00001/MPM/{$romawi_default}/{$tahun_default}";
        }
    }

    public function generate_kode_surat_jalan_safe_deltomed($created_at) 
    {
        try {
            // Validasi input
            if (empty($created_at)) {
                $created_at = date('Y-m-d H:i:s');
            }
            
            $bulan_now = date('m', strtotime($created_at));
            $romawi = $this->getRomawi($bulan_now);
            $tahun_now = date('Y', strtotime($created_at));
    
            // Query untuk mendapatkan nomor urut terakhir
            $query = "
                SELECT 
                    kode_surat_jalan,
                    CAST(
                        SUBSTRING(
                            SUBSTRING_INDEX(kode_surat_jalan, '/', 1),
                            LOCATE('-', kode_surat_jalan) + 1
                        ) AS UNSIGNED
                    ) AS urut
                FROM site.surat_jalan_deltomed 
                WHERE YEAR(created_at) = " . (int)$tahun_now . "
                AND MONTH(created_at) = " . (int)$bulan_now . "
                AND kode_surat_jalan LIKE 'DOD-%/MPM/%'
                ORDER BY urut DESC 
                LIMIT 1
            ";
    
            $result = $this->db->query($query);
            
            if ($result && $result->num_rows() > 0) {
                $last_urut = $result->row()->urut;
                $params_urut = $last_urut + 1;
            } else {
                $params_urut = 1;
            }
    
            // Format dengan leading zero untuk 5 digit
            $formatted_urut = str_pad($params_urut, 5, '0', STR_PAD_LEFT);
            
            // Generate kode surat jalan dengan format 5 digit
            $generate = "DOD-{$formatted_urut}/MPM/{$romawi}/{$tahun_now}";
            
            return $generate;
            
        } catch (Exception $e) {
            // Log error atau handle sesuai kebutuhan
            error_log("Error generating kode surat jalan: " . $e->getMessage());
            
            // Return default dengan format 5 digit
            $romawi_default = $this->getRomawi(date('m'));
            $tahun_default = date('Y');
            return "DOD-00001/MPM/{$romawi_default}/{$tahun_default}";
        }
    }

    public function get_surat_jalan_by_nodo($nodo)
    {
        $query = "
            select *
            from site.surat_jalan a 
            where a.deleted_at is null and a.nodo = '$nodo' 
        ";
        return $this->db->query($query);
    }

    public function get_surat_jalan_by_tgldo($tgldo)
    {
        $query = "
            select  a.*, b.nama_comp, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', c.image) as image,
                    date(c.created_at) as terima_at, c.username as terima_by
            from site.surat_jalan a left join site.master_site b 
                on a.kode_alamat = b.site_code left join 
                (
                    select a.id_surat_jalan, a.image, a.created_at, a.username
                    from dbrest.surat_jalan_terima a
                    where a.principal = 005
                )c on a.id = c.id_surat_jalan
            where a.deleted_at is null and a.tgldo = '$tgldo'
        ";

        // $query = "
        //     select a.*, b.nama_comp
        //     from site.surat_jalan a left join site.master_site b 
        //         on a.kode_alamat = b.site_code
        //     where a.deleted_at is null and a.tgldo = '$tgldo'
        // ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_surat_jalan($data, $tgldo)
    {
        $this->db->where('tgldo', $tgldo);
        $this->db->update('site.surat_jalan', $data);
        return $this->db->affected_rows();
    }

    public function get_surat_jalan_by_kode($kode)
    {
        $query = "
            select *
            from site.surat_jalan 
            where kode_surat_jalan = '$kode'
        ";
        return $this->db->query($query);
    }

    public function get_surat_jalan_detail($id_ref)
    {
        $query = "
            select  a.id,a.kodeprod, a.namaprod, a.banyak, a.total_karton, a.total_karton_berat, a.total_karton_volume, 
                    b.kode_prc
            from site.surat_jalan_detail a left join (
                select a.kodeprod, a.kode_prc
                from site.master_product a 
            )b on a.kodeprod = b.kodeprod
            where id_ref = '$id_ref'
        ";
        return $this->db->query($query);
    }

    public function insert_surat_jalan($data)
    {
        $this->db->insert('site.surat_jalan', $data);
        return $this->db->insert_id();
    }

    function convert_datetime($date)
    {
        $formatted_input = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
        $formatted_date = date('Y-m-d', strtotime($formatted_input));
        return $formatted_date;
    }

    public function insert_surat_jalan_detail_us($id_ref, $nodo, $created_at, $created_by)
    {
        $query = "
            insert into site.surat_jalan_detail
            select 	'', '$id_ref', a.kodeprod, b.namaprod, a.banyak, 
                    a.banyak / b.isisatuan as total_karton, 
                    a.banyak / b.isisatuan * b.berat as total_karton_berat,
                    a.banyak / b.isisatuan * b.volume as total_karton_volume, 
                    a.batch_number, a.ed as ed,
                    '$created_at', '$created_by', '', '', '', ''
            from db_po.t_do_us a left join (
                select 	a.kodeprod, a.kode_prc, a.namaprod, a.berat, a.volume, a.qty1, a.qty2, a.qty3, a.besar, a.sedang, a.kecil,
                        b.satuan_box, a.isisatuan / if(b.satuan_box is null, 1, b.satuan_box) as isisatuan
                from site.master_product a left join (
                    select a.kodeprod, a.satuan_box
                    from db_produk.t_product_po a
                )b on a.kodeprod = b.kodeprod
                where a.supp = 005
            )b on a.kodeprod = b.kodeprod
            where a.nodo = '$nodo' 
            order by a.kodeprod
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_produk($id, $data) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.surat_jalan_detail', $data);
    }

    public function delete_produk($id) {
        $this->db->where('id', $id);
        return $this->db->delete('site.surat_jalan_detail');
    }

    public function get_produk_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('site.surat_jalan_detail');
        return $query->row();
    }

    // Method lain yang mungkin sudah ada
    public function get_all_produk() {
        $query = $this->db->get('site.surat_jalan_detail');
        return $query;
    }

    public function update_surat_jalan_detail($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.surat_jalan_detail', $data);
        return $this->db->affected_rows();
    }

    public function history_helpdesk($form_search, $supp)
    {
        if ($supp == 000) {
            $param_supp = "";
        } else {
            $param_supp = "and a.supp = '$supp'";
        }
        // echo $param_supp;die;
        if ($form_search != null) 
        {
            $from = $form_search['from'];
            $to = $form_search['to'];
            $status = $form_search['status'];

            if ($status == 999) {
                $param_status = "and b.`status` in (0,1,2,3) and deleted_by is null";
            } else {
                $param_status = "and b.`status` in ($status) and deleted_by is null";
            }
            
            $param = "WHERE (a.created_at BETWEEN '$from 00:00:00' and '$to 23:59:00') $param_status $param_supp and deleted_by is null";

        }else{
            // $param_status = "WHERE b.`status` in (0,1,2,3) $param_supp";
            $param = "WHERE b.`status` in (0,1,2,3) $param_supp and deleted_by is null";
        }

            $sql="
                SELECT b.branch_name, b.nama_comp, c.namasupp, a.*
                FROM (
                        SELECT a.*, b.status, b.nama_status, b.created_at as tgl_pesan
                        FROM site.helpdesk a
                        LEFT JOIN 
                        (
                            SELECT a.*
                            FROM site.helpdesk_detail a
                            JOIN (
                                    SELECT id_helpdesk, MAX(created_at) AS max_created
                                    FROM site.helpdesk_detail
                                    GROUP BY id_helpdesk
                            ) latest ON a.id_helpdesk = latest.id_helpdesk AND a.created_at = latest.max_created
                        )b on a.id = b.id_helpdesk
                        $param
                )a LEFT JOIN 
                (
                        SELECT a.site_code, a.branch_name, a.nama_comp
                        FROM mpm.tbl_tabcomp a
                        WHERE a.`status` = 1
                )b on a.site_code = b.site_code
                LEFT JOIN
                (
                        SELECT *
                        FROM mpm.tabsupp a
                )c on a.supp = c.supp
                order by a.created_at desc
            ";

            // echo "<pre><br>";
            // print_r($sql);
            // echo "</pre>";
            // die;

            $proses = $this->db->query($sql);
            return $proses;
    }

    public function history_helpdesk_by_userid($userid, $form_search, $supp)
    {
        if ($supp == 000) {
            $param_supp = "";
        } else {
            $param_supp = "and a.supp = '$supp'";
        }

        if ($form_search != null) {
            $from = $form_search['from'];
            $to = $form_search['to'];
            $status = $form_search['status'];

            if ($status == 999) {
                $param_status = "and b.`status` in (0,1,2,3)";
            } else {
                $param_status = "and b.`status` in ($status)";
            }
            
            $param = "WHERE (a.created_at BETWEEN '$from 00:00:00' and '$to 23:59:00') $param_status $param_supp";
        }else{
            $param = "WHERE b.`status` in (0,1,2,3) $param_supp";
        }

        $sql="
            SELECT a.*, b.branch_name, b.nama_comp, c.supp, c.namasupp
            FROM (
                    SELECT a.*, b.status, b.nama_status, b.created_at as tgl_pesan
                    FROM site.helpdesk a
                LEFT JOIN 
                (
                    SELECT a.*
                    FROM site.helpdesk_detail a
                    JOIN (
                            SELECT id_helpdesk, MAX(created_at) AS max_created
                            FROM site.helpdesk_detail
                            GROUP BY id_helpdesk
                    ) latest ON a.id_helpdesk = latest.id_helpdesk AND a.created_at = latest.max_created
                )b on a.id = b.id_helpdesk
                $param
            )a INNER JOIN 
            (
                SELECT b.*
                FROM 
                (
                    SELECT a.id, a.username
                    FROM mpm.user a
                    where a.id = $userid
                ) a
                INNER JOIN
                (
                    SELECT a.site_code, a.kode_comp, a.branch_name, a.nama_comp
                    FROM mpm.tbl_tabcomp a
                    WHERE a.`status` = 1
                )b on a.username = b.kode_comp
            )b on a.site_code = b.site_code
            LEFT JOIN
            (
                SELECT *
                FROM mpm.tabsupp a
            )c on a.supp = c.supp
            order by a.created_at desc
        ";

        // echo "<pre><br>";
        // print_r($sql);
        // echo "</pre>";
        // die;

        $proses = $this->db->query($sql);
        return $proses;
    }
    public function get_site_code_by_userid($userid)
    {
        if ($userid == 547) {
            $params = "";
        } else {
            $params = "WHERE a.userid = $userid";
        }
        
        $sql = "
                SELECT *
                FROM site.master_site_with_user a
                $params
            ";
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function generate($created_at)
    {

        $bulan_now = date('m',strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            select a.no_tiket, substr(a.no_tiket,5,3) as urut,
                replace(substr(a.no_tiket,5,4), '/','') as urut_new,
                length(replace(substr(a.no_tiket,5,4), '/','')) as urut_new_length
                from site.helpdesk a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_tiket like 'HLP-%'
            ORDER BY urut_new_length desc, urut_new desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $no_pengajuan_current = $this->db->query($query);
        if ($no_pengajuan_current->num_rows() > 0) {

            $params_urut = $no_pengajuan_current->row()->urut_new + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "HLP-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "HLP-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "HLP-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "HLP-001/MPM/$romawi/$tahun_now";
        }

        // echo "generate : ".$generate;

        // die;

        // echo $generate;
        // die;
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

    public function get_helpdesk_by_signature($signature)
    {
        $query = "
            SELECT a.*, b.nama_comp, b.branch_name, c.namasupp
            FROM
            (
                SELECT *
                FROM site.helpdesk a
                WHERE a.signature = '$signature'
            )a
            LEFT JOIN mpm.tbl_tabcomp b on a.site_code = b.site_code
            LEFT JOIN mpm.tabsupp c on a.supp = c.supp
        ";

        return $this->db->query($query);
    }

    public function get_helpdesk_detail_by_id_helpdesk($id)
    {
        $query = "
            SELECT a.*, b.username
            FROM
            (
                SELECT *
                FROM site.helpdesk_detail a
                WHERE a.id_helpdesk = '$id'
            )a
            LEFT JOIN mpm.user b on a.userid = b.id
        ";
        
        return $this->db->query($query);
    }

    public function helpdesk_insert($post)
    {
        $this->db->insert('site.helpdesk', $post);

        return $this->db->insert_id();
    }

    public function helpdesk_detail_insert($post)
    {
        $this->db->insert('site.helpdesk_detail', $post);
        return $this->db->insert_id();
    }

    public function helpdesk_update($data, $signature)
    {
        $this->db->where('signature', $signature);
        $this->db->update('site.helpdesk', $data);
    }

    public function get_t_do_deltomed_group_by_nodo_by_tgldo($tgldo)
    {
        $query = "
            select a.nodo, a.nopo
            from site.t_do_deltomed a 
            where a.tgldo = '$tgldo'
            group by a.nodo
        ";
        return $this->db->query($query);
    }   

    public function get_surat_jalan_deltomed_by_nodo($nodo)
    {
        $query = "
            select *
            from site.surat_jalan_deltomed a 
            where a.deleted_at is null and a.nodo = '$nodo' 
        ";
        return $this->db->query($query);
    }

    public function insert_surat_jalan_deltomed($data)
    {
        $this->db->insert('site.surat_jalan_deltomed', $data);
        return $this->db->insert_id();
    }

    public function insert_surat_jalan_detail_deltomed($id_ref, $nodo, $created_at, $created_by)
    {
        // $query = "
        //     insert into site.surat_jalan_detail_deltomed
        //     select 	'', '$id_ref', a.kodeprod, b.namaprod, a.banyak, 
        //             a.banyak / b.isisatuan as total_karton, 
        //             a.banyak / b.isisatuan * b.berat as total_karton_berat,
        //             a.banyak / b.isisatuan * b.volume as total_karton_volume, 
        //             '$created_at', '$created_by', '', '', '', ''
        //     from db_po.t_do_us a left join (
        //         select 	a.kodeprod, a.kode_prc, a.namaprod, a.berat, a.volume, a.qty1, a.qty2, a.qty3, a.besar, a.sedang, a.kecil,
        //                 b.satuan_box, a.isisatuan / if(b.satuan_box is null, 1, b.satuan_box) as isisatuan
        //         from site.master_product a left join (
        //             select a.kodeprod, a.satuan_box
        //             from db_produk.t_product_po a
        //         )b on a.kodeprod = b.kodeprod
        //         where a.supp = 005
        //     )b on a.kodeprod = b.kodeprod
        //     where a.nodo = '$nodo' 
        //     order by a.kodeprod
        // ";

        $query = "
            insert into site.surat_jalan_detail_deltomed
            select 	'', '$id_ref', a.kodeprod, b.namaprod, a.qty, 
                    a.qty / b.isisatuan as total_karton, 
                    a.qty / b.isisatuan * b.berat as total_karton_berat,
                    a.qty / b.isisatuan * b.volume as total_karton_volume, 
                    a.batch_number, a.ed,
                    '$created_at', '$created_by', '', '', '', ''
            from site.t_do_deltomed a left join (
                select a.kodeprod, a.kode_prc, a.namaprod, a.kodeprod_deltomed, a.isisatuan, a.berat, a.volume
                from site.master_product a 
                where a.supp = 001
            )b on a.kodeprod = b.kodeprod
            where a.nodo = '$nodo' 
            order by a.kodeprod
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_surat_jalan_deltomed_by_tgldo($tgldo)
    {
        $query = "
            select  a.*, b.nama_comp, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', c.image) as image,
                    date(c.created_at) as terima_at, c.username as terima_by
            from site.surat_jalan_deltomed a left join site.master_site b 
                on a.kode_alamat = b.site_code left join
                (
                    select a.id_surat_jalan, a.image, a.created_at, a.username
                    from dbrest.surat_jalan_terima a
                    where a.principal = 001
                )c on a.id = c.id_surat_jalan
            where a.deleted_at is null and a.tgldo = '$tgldo'
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function update_surat_jalan_deltomed($data, $tgldo)
    {
        $this->db->where('tgldo', $tgldo);
        $this->db->update('site.surat_jalan_deltomed', $data);
        return $this->db->affected_rows();
    }

    public function get_surat_jalan_deltomed_by_kode($kode)
    {
        $query = "
            select *
            from site.surat_jalan_deltomed a
            where kode_surat_jalan = '$kode'
        ";
        return $this->db->query($query);
    }

    public function get_surat_jalan_mpm_deltomed_by_kode($kode)
    {
        $query = "
            select 	a.kode_surat_jalan, a.created_at, a.nodo, a.tgldo, a.nopo, a.tglpo, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', b.image) as image, 
                    b.created_at as terima_at, b.username as terima_by
            from site.surat_jalan_deltomed a left join (
                select a.id_surat_jalan, a.image, a.username, a.created_at
                from dbrest.surat_jalan_terima a 
                where a.kode_surat_jalan = '$kode'
            )b on a.id = b.id_surat_jalan
            where a.kode_surat_jalan = '$kode'
        ";
        return $this->db->query($query);
    }

    public function get_surat_jalan_detail_deltomed($id_ref)
    {
        $query = "
            select  a.id,a.kodeprod, a.namaprod, a.banyak, a.total_karton, a.total_karton_berat, a.total_karton_volume, 
                    b.kode_prc, a.batch_number, a.ed
            from site.surat_jalan_detail_deltomed a left join (
                select a.kodeprod, a.kode_prc
                from site.master_product a 
            )b on a.kodeprod = b.kodeprod
            where id_ref = '$id_ref'
        ";
        return $this->db->query($query);
    }

    public function cek_koneksi_sql_server()
    {
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        // $serverName = "192.168.7.11"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "obherbal12!@");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
    
        return $conn;
    }

    public function get_analisa_piutang_sql_server($tanggal, $conn)
    {
        $periode_x=strftime('%Y-%m-%d',strtotime($tanggal));
        $periode_y=strftime('%m/%d/%Y 23:59:59',strtotime($tanggal));
        $awal=substr($periode_x,0,7).'-01';

        // echo "tanggal: ".$tanggal;
        // echo "periode_x: ".$periode_x;
        // echo "periode_y: ".$periode_y;
        // echo "awal : ".$awal;
        // die;

        $customerid = "";
        $sql = "
            SELECT	ref, group_descr, no_polisi, depo_id, customerid, 
                    siteid, nama_site, alamat_site, branchid, nama_branch, 
                    alamat_branch, no_sales, tanggal, term_payment, 
                    tgl_tempo, tgl_tempo2,  ket, salesmanid, 
                    nama_salesman, nama_customer, prefix, alamat, 
                    segmentid, nama_segment, typeid, nama_type, regionalid, 
                    nama_regional, areaid, nama_area, classid, nama_class, 
                    debitur_id, debitur_name, nilai_faktur, bayar, saldo, 
                    umur, lewat, aging, type_bayar, kredit, debit, (debitprev-kreditprev) as saldoawal
            FROM     
            (
                SELECT	data_faktur.ref, 
                        ISNULL(data_faktur.group_descr, data_faktur.nama_customer) AS group_descr, 
                        data_faktur.no_polisi, data_faktur.depo_id, 
                        data_faktur.customerid, data_faktur.siteid, data_faktur.nama_site, data_faktur.alamat_site, data_faktur.branchid, data_faktur.nama_branch, 
                        data_faktur.alamat_branch, data_faktur.no_sales, data_faktur.tanggal, data_faktur.term_payment, data_faktur.tgl_tempo, data_faktur.tgl_tempo2, 
                        data_faktur.ket, data_faktur.salesmanid, data_faktur.nama_salesman, data_faktur.nama_customer, data_faktur.prefix, data_faktur.alamat, 
                        data_faktur.segmentid, data_faktur.nama_segment, data_faktur.typeid, data_faktur.nama_type, data_faktur.regionalid, data_faktur.nama_regional, 
                        data_faktur.areaid, data_faktur.nama_area, data_faktur.classid, data_faktur.nama_class, data_faktur.debitur_id, data_faktur.debitur_name, 
                        data_faktur.debet AS nilai_faktur, 
                        ISNULL(data_ink.Bayar, 0) AS bayar, 
                        (CASE 	WHEN data_faktur.retur = 0 THEN abs(isnull(data_faktur.debet, 0)) - abs(isnull(data_ink.Bayar, 0)) ELSE - (abs(isnull(data_faktur.debet, 0)) - abs(isnull(data_ink.Bayar, 0))) END) AS saldo,
                        data_faktur.umur, data_faktur.lewat, 
                        (
                            CASE 	WHEN data_faktur.lewat <= 0 THEN 'A. Belum Jatuh Tempo' 
                                    WHEN (data_faktur.lewat >= 1) AND (data_faktur.lewat <= 7) THEN 'B. 1 - 7'
                                    WHEN (data_faktur.lewat >= 8) AND (data_faktur.lewat <= 15) THEN 'C. 8 - 15' 
                                    WHEN (data_faktur.lewat >= 16) AND (data_faktur.lewat <= 30) THEN 'D. 16 - 30' 
                                    WHEN (data_faktur.lewat >= 31) AND (data_faktur.lewat <= 45) THEN 'E. 31 - 45'
                                    WHEN (data_faktur.lewat >= 46) AND (data_faktur.lewat <= 60) THEN 'F. 46 - 60' 
                                    WHEN (data_faktur.lewat > 60) THEN 'G. > 60 ' ELSE '' END
                        ) AS aging, 
                        (
                            CASE 	WHEN data_faktur.type_bayar = 1 THEN 'Tunai' 
                                    WHEN data_faktur.type_bayar = 2 THEN 'Kredit' ELSE 'Checq/Giro' END
                        ) AS type_bayar,
                        ISNULL(data_ink_kreditprev.kreditprev, 0) AS kreditprev,
                        ISNULL(data_ink_debitprev.debitprev, 0) AS debitprev,
                        ISNULL(data_ink_kredit.kredit, 0) AS kredit,
                        ISNULL(data_ink_debit.debit, 0) AS debit
                FROM
                (
                    SELECT	t_ar_ink_master.ref, 
                            LEFT(t_ar_ink_master.no_polisi, 3) + '.' + SUBSTRING(t_ar_ink_master.no_polisi, 4, 3) 
                            + '-' + SUBSTRING(t_ar_ink_master.no_polisi, 7, 2) 
                            + '.' + SUBSTRING(t_ar_ink_master.no_polisi, 9, 8) AS no_polisi, 
                            t_ar_ink_master.retur, t_ar_ink_master.customerid, 
                            t_ar_ink_master.siteid, m_setup_site.nama_site, 
                            m_setup_site.alamat_site, m_setup_branch.branchid, 		
                            m_setup_branch.nama_branch, m_setup_branch.alamat_branch, 
                            t_ar_ink_master.no_sales, t_ar_ink_master.tanggal,
                            t_ar_ink_master.type_bayar, t_ar_ink_master.term_payment, 
                            t_ar_ink_master.tgl_tempo, t_ar_ink_master.tanggal + t_ar_ink_master.term_payment AS tgl_tempo2, 
                            (
                                CASE WHEN t_ar_ink_master.retur = 0 THEN t_ar_ink_master.dokument ELSE - t_ar_ink_master.dokument END
                            ) AS debet, 0 AS kredit, 
                            (
                                CASE 	WHEN LEFT(t_ar_ink_master.no_sales, 1) = 'S' THEN 'Faktur' 
                                            WHEN LEFT(t_ar_ink_master.no_sales, 1) = 'R' THEN 'Retur' 
                                            WHEN LEFT(t_ar_ink_master.no_sales, 1) = 'C' THEN 'CN' 
                                            WHEN LEFT(t_ar_ink_master.no_sales, 1) = 'D' THEN 'DN' END
                            ) 
                            + '/' + (
                                CASE 	WHEN t_ar_ink_master.type_bayar = '1' THEN 'COD' 
                                        WHEN t_ar_ink_master.type_bayar = '2' THEN 'Kredit' 
                                        WHEN t_ar_ink_master.type_bayar = '3' THEN 'Giro or Cheq' END
                            )
                            + '/' + t_ar_ink_master.no_sales AS ket, 
                            DATEDIFF(day, t_ar_ink_master.tanggal, CONVERT(DATETIME,'$periode_y', 102)) AS umur, 
                            DATEDIFF(day, t_ar_ink_master.tanggal + t_ar_ink_master.term_payment, CONVERT(DATETIME, '$periode_y', 102)) AS lewat, 
                            t_ar_ink_master.salesmanid, m_sales_salesman.nama_salesman, m_customer.nama_customer, 
                            m_customer.depo_id, m_customer.prefix, m_customer.alamat, m_customer.segmentid, m_customer_segment.nama_segment, 
                            m_customer.typeid, m_customer_type.nama_type, m_customer.regionalid, m_area_regional.nama_regional, m_customer.areaid, 
                            m_area_areasite.nama_area, m_customer.classid, m_customer_class.nama_class, m_customer.debitur_id, m_debitur.debitur_name, 
                            m_customer.group_descr, m_customer.group_id
                    FROM    dbsls.dbo.t_ar_ink_master INNER JOIN dbsls.dbo.m_setup_site 
                                ON dbsls.dbo.t_ar_ink_master.siteid = dbsls.dbo.m_setup_site.siteid 
                            INNER JOIN dbsls.dbo.m_setup_branch 
                                ON dbsls.dbo.m_setup_site.branchid = dbsls.dbo.m_setup_branch.branchid 
                            INNER JOIN dbsls.dbo.m_customer 
                                ON dbsls.dbo.t_ar_ink_master.customerid = dbsls.dbo.m_customer.customerid 
                            INNER JOIN dbsls.dbo.m_sales_salesman 
                                ON 	dbsls.dbo.t_ar_ink_master.salesmanid = dbsls.dbo.m_sales_salesman.salesmanid AND 
                                dbsls.dbo.t_ar_ink_master.siteid = dbsls.dbo.m_sales_salesman.siteid 
                            LEFT OUTER JOIN dbsls.dbo.m_customer_class 
                                ON dbsls.dbo.m_customer.classid = dbsls.dbo.m_customer_class.classid 
                            LEFT OUTER JOIN dbsls.dbo.m_customer_segment 
                                ON dbsls.dbo.m_customer.segmentid = dbsls.dbo.m_customer_segment.segmentid 
                            LEFT OUTER JOIN dbsls.dbo.m_customer_type 
                                ON dbsls.dbo.m_customer.typeid = dbsls.dbo.m_customer_type.typeid 
                            LEFT OUTER JOIN dbsls.dbo.m_debitur 
                                ON 	dbsls.dbo.m_customer.debitur_id = dbsls.dbo.m_debitur.debitur_id AND 
                                dbsls.dbo.m_customer.siteid = dbsls.dbo.m_debitur.siteid 
                            LEFT OUTER JOIN dbsls.dbo.m_area_regional 
                                ON 	dbsls.dbo.m_customer.regionalid = dbsls.dbo.m_area_regional.regionalid AND 
                                dbsls.dbo.m_customer.siteid = dbsls.dbo.m_area_regional.siteid 
                            LEFT OUTER JOIN dbsls.dbo.m_area_areasite 
                                ON 	dbsls.dbo.m_customer.areaid = dbsls.dbo.m_area_areasite.areaid AND 
                                dbsls.dbo.m_customer.regionalid = dbsls.dbo.m_area_areasite.regionalid AND 
                                dbsls.dbo.m_customer.siteid = dbsls.dbo.m_area_areasite.siteid
                    WHERE	(dbsls.dbo.t_ar_ink_master.siteid = 'KPS111') AND 
                            (dbsls.dbo.t_ar_ink_master.tanggal <= CONVERT(DATETIME, '$periode_y', 102)) AND 
                            (dbsls.dbo.t_ar_ink_master.program = 1) $customerid
                ) AS data_faktur LEFT OUTER JOIN
                (
                    SELECT	siteid, no_sales, SUM(bayar_tunai) + SUM(bayar_transfer) + SUM(bayar_giro) AS Bayar
                    FROM    dbsls.dbo.t_ar_ink_detail
                    WHERE   (tgl_ink <= CONVERT(DATETIME, '$periode_y', 102)) AND 
                                (Counter_print >= 1) AND 
                                    (siteid = 'KPS111') AND 
                                (ISNULL(status_giro, '') IN ('', 'C')) AND 
                                    (program = 1)
                    GROUP BY siteid, no_sales
                ) AS data_ink ON data_faktur.no_sales = data_ink.no_sales AND 
                    data_faktur.siteid = data_ink.siteid                 
                    LEFT OUTER JOIN
                (
                        select  siteid, no_sales, sum(bayar_transfer+bayar_giro+bayar_tunai) as kreditprev 
                        from    dbsls.dbo.t_ar_ink_detail
                        where   tgl_transfer<'$awal'
                        group by siteid, no_sales
                ) AS data_ink_kreditprev ON data_faktur.no_sales = data_ink_kreditprev.no_sales and
                        data_faktur.siteid = data_ink_kreditprev.siteid
                        LEFT OUTER JOIN
                (
                        select  siteid, no_sales,
                                        sum( CASE WHEN t_ar_ink_master.retur = 0 THEN dokument ELSE dokument*-1 END ) as debitprev
                        from    dbsls.dbo.t_ar_ink_master
                        where   tanggal<'$awal'
                        group by siteid, no_sales
                ) AS data_ink_debitprev ON data_faktur.no_sales = data_ink_debitprev.no_sales and
                        data_faktur.siteid = data_ink_debitprev.siteid                 
                        LEFT OUTER JOIN
                (
                        select  siteid, no_sales, sum(bayar_transfer+bayar_giro+bayar_tunai) as kredit 
                        from    dbsls.dbo.t_ar_ink_detail
                        where   tgl_transfer>='$awal' and tgl_transfer<='$periode_x'
                        group by siteid, no_sales
                ) AS data_ink_kredit ON data_faktur.no_sales = data_ink_kredit.no_sales and
                        data_faktur.siteid = data_ink_kredit.siteid
                        LEFT OUTER JOIN
                (
                        select  siteid, no_sales,
                                        sum( CASE WHEN t_ar_ink_master.retur = 0 THEN dokument ELSE dokument*-1 END ) as debit
                        from    dbsls.dbo.t_ar_ink_master
                        where   tanggal>='$awal' and tanggal<='$periode_x'
                        group by siteid, no_sales
                ) AS data_ink_debit ON data_faktur.no_sales = data_ink_debit.no_sales and
                        data_faktur.siteid = data_ink_debit.siteid
            ) AS data
            WHERE (saldo <>0) 
            ";

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            
            $query = sqlsrv_query($conn, $sql);    
            return $query;
    }

    public function insert_analisa_piutang($data_sql_server, $tanggal)
    {

        $this->db->query("truncate site.analisa_piutang_temp");

        while ($data = sqlsrv_fetch_array($data_sql_server))
        {
            $data = array(
                'ref'           => $data['ref'],
                'group_descr' => $data['group_descr'],
                'no_polisi' => $data['no_polisi'],
                'depo_id' => $data['depo_id'],
                'customerid' => $data['customerid'],
                'siteid' => $data['siteid'],
                'nama_site' => $data['nama_site'],
                'alamat_site' => $data['alamat_site'],
                'branchid'  => $data['branchid'],
                'nama_branch' => $data['nama_branch'],
                'alamat_branch' => $data['alamat_branch'],
                'no_sales' => $data['no_sales'],
                'tanggal' => $data['tanggal']->format('Y-m-d H:i:s'),
                'term_payment' => $data['term_payment'],
                'tgl_tempo' => $data['tgl_tempo']->format('Y-m-d H:i:s'),
                'tgl_tempo2' => $data['tgl_tempo2']->format('Y-m-d H:i:s'),
                'ket' => $data['ket'],
                'salesmanid' => $data['salesmanid'],
                'nama_salesman' => $data['nama_salesman'],
                'nama_customer' => $data['nama_customer'],
                'prefix' => $data['prefix'],
                'alamat'    => $data['alamat'],
                'segmentid' => $data['segmentid'],
                'nama_segment' => $data['nama_segment'],
                'typeid' => $data['typeid'],
                'nama_type' => $data['nama_type'],
                'regionalid' => $data['regionalid'],
                'nama_regional' => $data['nama_regional'],
                'areaid' => $data['areaid'],
                'nama_area' => $data['nama_area'],
                'classid' => $data['classid'],
                'nama_class' => $data['nama_class'],
                'debitur_id' => $data['debitur_id'],
                'debitur_name' => $data['debitur_name'],
                'nilai_faktur' => $data['nilai_faktur'],
                'bayar' => $data['bayar'],
                'saldo' => $data['saldo'],
                'umur' => $data['umur'],
                'lewat' => $data['lewat'],
                'aging' => $data['aging'],
                'type_bayar' => $data['type_bayar'],
                'kredit' => $data['kredit'],
                'debit' => $data['debit'],
                'saldoawal' => $data['saldoawal'],
                'created_at'    => $tanggal,
            );
            $this->db->insert('site.analisa_piutang_temp', $data);
        }

    }

    public function get_analisa_piutang_temp($tanggal)
    {
        $query = "
            select *
            from site.analisa_piutang_temp a 
            where date(a.created_at) = '$tanggal'
        ";
        return $this->db->query($query);
    }

    public function get_analisa_piutang($kodelang)
    {
        if ($kodelang) {
            $params = "where a.customerid in ($kodelang)";
        } else {
            $params = '';
        }

        $query = "
            select  a.customerid, a.group_descr, 
                    sum(saldoawal) as saldoawal,
                    sum(debit) as debit,
                    sum(kredit) as kredit,
                    SUM(IF(tgl_tempo < 0, saldo, 0)) current,
                    SUM(IF(tgl_tempo = 0, saldo, 0)) duedate,
                    sum(if(substr(a.aging,1,1) = 'A',saldo,0)) as belum_jatuh_tempo,
                    sum(if(substr(a.aging,1,1) = 'B',saldo,0)) as a,
                    sum(if(substr(a.aging,1,1) = 'C',saldo,0)) as b,
                    sum(if(substr(a.aging,1,1) = 'D',saldo,0)) as c,
                    sum(if(substr(a.aging,1,1) = 'E',saldo,0)) as d,
                    sum(if(substr(a.aging,1,1) = 'F',saldo,0)) as e,
                    sum(if(substr(a.aging,1,1) = 'G',saldo,0)) as f,
                    sum(saldo) as 'total'
            from site.analisa_piutang_temp a 
            $params
            GROUP BY group_descr
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_kodelang_by_region($region)
    {
        $query = "
            select *
            from site.master_site_with_user a
            where a.region in ($region)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function export_analisa_piutang($kodelang)
    {
        if ($kodelang) {
            $params = "where customerid in ($kodelang)";
        } else {
            $params = '';
        }

        $query = "
            select group_descr as company, customerid, no_sales,
            sum(if(substr(aging,1,1) = 'B',saldo,0)) as '1-7',
            sum(if(substr(aging,1,1) = 'C',saldo,0)) as '8-15',
            sum(if(substr(aging,1,1) = 'D',saldo,0)) as '16-30',
            sum(if(substr(aging,1,1) = 'E',saldo,0)) as '31-45',
            sum(if(substr(aging,1,1) = 'F',saldo,0)) as '46-60',
            sum(if(substr(aging,1,1) = 'G',saldo,0)) as '>60',
            sum(saldo) as 'total'
            from site.analisa_piutang_temp
            $params
            GROUP BY group_descr, no_sales
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $quer = $this->db->query($query);

        query_to_csv($quer,TRUE,'Export_Analisa_Piutang.csv');
    }

    public function export_analisa_piutang_detail($kodelang)
    {
        if ($kodelang) {
            $params = "where customerid in ($kodelang)";
        } else {
            $params = '';
        }

        $query = "
            select  group_descr as company, customerid, 
                    no_sales, date(a.tanggal) as tanggal, 
                    a.saldo,
                    a.term_payment, a.tgl_tempo, a.ket,
                    a.umur, a.lewat, 
                    a.aging, 
                    sum(saldo) as 'total'
            from site.analisa_piutang_temp a
            $params
            GROUP BY group_descr, no_sales
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $quer = $this->db->query($query);

        query_to_csv($quer,TRUE,'Export_Analisa_Piutang_Detail.csv');
    }

    

    // public function get_analisa_piutang($result)
    // {
    //     // Jika $result sudah berisi data yang ingin diolah
    //     if (!empty($result)) {
    //         // Buat array untuk menampung hasil grouping
    //         $grouped_data = array();
            
    //         foreach ($result as $row) {
    //             $customer_id = $row->customerid;
    //             $group_descr = $row->group_descr;
                
    //             // Jika belum ada di array grouped_data, inisialisasi
    //             if (!isset($grouped_data[$customer_id])) {
    //                 $grouped_data[$customer_id] = array(
    //                     'customerid' => $customer_id,
    //                     'group_descr' => $group_descr,
    //                     'saldoawal' => 0,
    //                     'debit' => 0,
    //                     'kredit' => 0,
    //                     'current' => 0,
    //                     'duedate' => 0,
    //                     'belum_jatuh_tempo' => 0,
    //                     '1_7' => 0,
    //                     '8_15' => 0,
    //                     '16_30' => 0,
    //                     '31_45' => 0,
    //                     '46_60' => 0,
    //                     'gt_60' => 0,
    //                     'total' => 0
    //                 );
    //             }
                
    //             // Akumulasi nilai
    //             $grouped_data[$customer_id]['saldoawal'] += $row->saldoawal;
    //             $grouped_data[$customer_id]['debit'] += $row->debit;
    //             $grouped_data[$customer_id]['kredit'] += $row->kredit;
                
    //             // Hitung umur piutang berdasarkan aging
    //             $aging = substr($row->aging, 1, 1); // Ambil karakter pertama setelah 'G. '
                
    //             switch ($aging) {
    //                 case 'A':
    //                     $grouped_data[$customer_id]['belum_jatuh_tempo'] += $row->saldo;
    //                     break;
    //                 case 'B':
    //                     $grouped_data[$customer_id]['1_7'] += $row->saldo;
    //                     break;
    //                 case 'C':
    //                     $grouped_data[$customer_id]['8_15'] += $row->saldo;
    //                     break;
    //                 case 'D':
    //                     $grouped_data[$customer_id]['16_30'] += $row->saldo;
    //                     break;
    //                 case 'E':
    //                     $grouped_data[$customer_id]['31_45'] += $row->saldo;
    //                     break;
    //                 case 'F':
    //                     $grouped_data[$customer_id]['46_60'] += $row->saldo;
    //                     break;
    //                 case 'G':
    //                     $grouped_data[$customer_id]['gt_60'] += $row->saldo;
    //                     break;
    //             }
                
    //             // Hitung current dan duedate
    //             $tgl_tempo = strtotime($row->tgl_tempo2);
    //             $today = strtotime(date('Y-m-d'));
    //             $diff = $today - $tgl_tempo;
    //             $diff_days = floor($diff / (60 * 60 * 24));
                
    //             if ($diff_days < 0) {
    //                 $grouped_data[$customer_id]['current'] += $row->saldo;
    //             } elseif ($diff_days == 0) {
    //                 $grouped_data[$customer_id]['duedate'] += $row->saldo;
    //             }
                
    //             // Total saldo
    //             $grouped_data[$customer_id]['total'] += $row->saldo;
    //         }
            
    //         // Kembalikan hasil sebagai array of objects
    //         return array_values($grouped_data);
    //     }
        
    //     return array();
    // }

     public function get_email_by_userid($userid)
    {
        $query = "
            SELECT a.email 
            FROM site.master_user a 
            WHERE a.id = $userid
        ";
        $hsl = $this->db->query($query);
        return $hsl;
    }

    public function get_site_by_sub($sub)
    {
        $query = "
            SELECT a.site_code 
            FROM site.master_site a 
            WHERE a.sub = '$sub'
        ";
        $hsl = $this->db->query($query);
        return $hsl;
    }

    public function get_purchase_plan_by_month($month)
    {
        $query = "
            select a.site_code, c.branch_name, c.nama_comp, a.kodeprod, b.namaprod, a.pp_karton
            from site.spk_purchase_plan a left join site.master_product b 
                on a.kodeprod = b.kodeprod left join site.master_site c 
                on a.site_code = c.site_code
            where a.bulan = '$month'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_purchase_plan($data)
    {
        $this->db->insert('site.spk_purchase_plan', $data);
        return $this->db->insert_id();
    }

    public function update_spk_purchase_plan($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.spk_purchase_plan', $data);
    }

  public function get_po_detail_by_id_po_include_delete_join_pp($id_po, $site_code, $bulan)
  {
    $query = "
      select 	a.*, b.username, c.isisatuan, if(d.pp_karton is null, '0', d.pp_karton) as spk_pp_karton
      from mpm.po_detail a left join site.master_user b 
          on a.updated_by = b.id left join site.master_product c 
          on a.kodeprod = c.kodeprod left join 
          (
              select a.site_code, a.kodeprod, a.pp_karton
              from site.spk_purchase_plan a 
              where a.site_code = '$site_code' and a.bulan = '$bulan'
          )d on a.kodeprod = d.kodeprod
      where a.id_ref = $id_po
      order by a.kodeprod
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
     
    return $this->db->query($query);
  }
}