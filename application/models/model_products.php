<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_products extends CI_Model 
{
  public function __construct() 
  {
    $this->created_at = $this->model_outlet_transaksi->timezone();
    $this->userid = $this->session->userdata('id');
  }

  public function get_principal()
  {
    $query = "
        select a.supp, a.namasupp
        from site.master_supplier a 
        where a.supp in ('001','004','005','012','013','014','015','024','026','027')
    ";
    return $this->db->query($query);
  }

  public function insert_ticket_kenaikan_harga($data)
  {
    $this->db->insert('site.ticket_kenaikan_harga', $data);
    return $this->db->insert_id();
  }

  public function generate_nomor_ticket($supp, $created_at)
  {
    $bulan_now = date('m',strtotime($created_at));
    $romawi = $this->getRomawi($bulan_now);
    $tahun_now = date('Y');

    // $query = "
    //     select a.nomor_ajuan, a.urut
    //     from 
    //     (
    //         select 	a.nomor_ajuan, 
    //                 if(right(substr(a.nomor_ajuan,5,4),1)='/',concat('0',substr(a.nomor_ajuan,5,3)),substr(a.nomor_ajuan,5,4)) as urut, 			
    //                 a.created_by, a.created_at
    //         from management_claim.ajuan_claim a
    //         where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now
    //     )a ORDER BY a.urut desc limit 1
    // ";
    $query = "
        select a.nomor_ticket, a.urut
        from 
        (
            select 	a.nomor_ticket, 
                    if(right(substr(a.nomor_ticket,5,4),1)='/',concat('0',substr(a.nomor_ticket,5,3)),substr(a.nomor_ticket,5,4)) as urut, 			
                    a.created_by, a.created_at
            from site.ticket_kenaikan_harga a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now
        )a ORDER BY a.urut desc limit 1
    ";

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    // die;

    $nomor_ticket_current = $this->db->query($query);
    if ($nomor_ticket_current->num_rows() > 0) {
        
        $params_urut = $nomor_ticket_current->row()->urut + 1;
        // echo $params_urut;

        if (strlen($params_urut) === 1) {
            $generate = "REQ-00$params_urut/MPM-$supp/$romawi/$tahun_now";
        }elseif (strlen($params_urut) === 2) {
            $generate = "REQ-0$params_urut/MPM-$supp/$romawi/$tahun_now";
        }else{
            $generate = "REQ-$params_urut/MPM-$supp/$romawi/$tahun_now";
        }
    }else{
        $generate = "REQ-001/MPM-$supp/$romawi/$tahun_now";
    }
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

  public function get_ticket_kenaikan_harga($signature = null)
  {
    if ($signature) {
        $params = "and a.signature = '$signature'";
    }else{
        $params = "";
    }
    $query = "
        select 	a.id, a.nomor_ticket, a.supp, c.namasupp, a.keterangan, a.file, a.attachments,a.`status`, a.nama_status, 
                a.on_duty, b.username as on_duty_username, 
                a.created_at, d.username as created_by_username, a.signature
        from site.ticket_kenaikan_harga a left join (
            select a.id, a.username
            from site.master_user a 
        )b on a.on_duty = b.id left join (
            select a.supp, a.namasupp
            from site.master_supplier a 
        )c on a.supp = c.supp left join (
            select a.id, a.username
            from site.master_user a 
        )d on a.created_by = d.id
        where a.deleted_at is null $params
        order by a.id asc
    ";

      // echo "<pre>";
      // print_r($query);
      // echo "</pre>";

    return $this->db->query($query);
  }

  public function get_site_code($site_code)
  {
    $string_result = "'" . implode("','", $site_code) . "'";
    // echo $string_result;    
    $query = "
      select a.site_code, a.branch_name, a.nama_comp, a.region, a.sub, a.sub_region
      from site.master_site a 
      where a.active = 1 
      and a.region <> 'PENTA' 
      and (a.branch_name <> 'PT. PENTA VALENT TBK' and a.branch_name <> 'SUPRALITA MANDIRI')
      and a.site_code not in ($string_result)
      order by a.branch_name = 'JAVAS KARYA TRIPTA' desc, a.branch_name = 'JAYA BAKTI RAHARJA' desc, 
            a.branch_name = 'JAVAS TRIPTA GEMALA' desc, a.branch_name = 'PT. DUTA INTRA YASA' desc, 
            a.branch_name = 'JAVAS TRIPTA SEJAHTERA' desc, 
            a.branch_name = 'PT.JAVAS TRIPTA MANDALA/JTM' desc, 
            a.branch_name = 'JAVAS BALI LESTARI' desc, 
            a.branch_name = 'SOLO' desc, 
            a.branch_name = 'SIDOARJO' desc, 
            a.branch_name = 'BANGKALAN' desc, 	
            a.branch_name = 'PAMEKASAN' desc, 	
            a.branch_name = 'BIMA' desc, 			
            a.branch_name = 'LHOKSEUMAWE' desc, 
            a.branch_name = 'RANTAU PRAPAT ASAHAN' desc, 
            a.branch_name = 'MEDAN' desc, 
            a.branch_name = 'PADANG' desc, 
            a.branch_name = 'BENGKULU' desc, 
            a.branch_name = 'BANGKA BELITUNG' desc, 
            a.branch_name = 'BATAM' desc, 
            a.branch_name = 'TANJUNG PINANG' desc, 
            a.branch_name = 'PEKANBARU' desc, 
            a.branch_name = 'JAMBI' desc, 
            a.branch_name = 'MUARA BUNGO' desc, 
            a.branch_name = 'PALEMBANG' desc, 
            a.branch_name = 'BANDAR LAMPUNG' desc, 
            a.region = 'KALIMANTAN' desc, 
            a.region = 'SULAWESI' desc, 
            a.sub
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";    
    return $this->db->query($query);
  }

    public function input_kenaikan_harga_header($data)
    {
        $this->db->insert('site.kenaikan_harga_header', $data);
        return $this->db->insert_id();
    }

  public function get_kenaikan_harga_header_by_id_ticket($id_ticket)
  {
    $query = "
        select 	a.id, a.id_ticket, a.label, a.site_code, a.signature,
                b.id_header, 
                b.count_product,
                if(b.id_header is not null, TRUE, FALSE) as flag_harga
        from site.kenaikan_harga_header a left join (
            select a.id_header, count(*) as count_product
            from site.kenaikan_harga_detail a
            group by a.id_header 
        )b on a.id = b.id_header
        where a.deleted_at is null and a.id_ticket = $id_ticket
    ";

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    // die;
    return $this->db->query($query);
  }

    public function get_kenaikan_harga_header($signature)
    {
      $query = "
        select  a.id, a.label, a.id_ticket, a.site_code, a.tanggal_aktif, a.created_at, a.created_by, b.username, 
                a.signature, c.signature as signature_ticket
        from site.kenaikan_harga_header a left join (
            select a.id, a.username
            from site.master_user a
        )b on a.created_by = b.id left join (
            select a.id, a.signature
            from site.ticket_kenaikan_harga a 
        )c on a.id_ticket = c.id
        where a.signature = '$signature'
      ";
      
      // echo "<pre>";
      // print_r($query);
      // echo "</pre>";

      // die;
      return $this->db->query($query);
    }

    public function get_kodeprod_by_supp($supp)
    {
        $query = "
            select a.kodeprod, a.namaprod, a.nama_group, a.nama_sub_group
            from site.master_product_with_harga a 
            where a.supp = '$supp' and a.active = 1 and a.namaprod not like 'GIMMICK%' and a.kodeprod not like '0000%' and a.kodeprod not like '999%'
        ";
        return $this->db->query($query);
    }

  public function get_ticket_kenaikan_harga_by_id($id)
  {
    $query = "
      select a.id, a.nomor_ticket, a.supp, a.keterangan, a.file, a.attachments, a.signature, a.created_by, a.created_at, b.namasupp, c.username,a.memo_id, a.tgl_memo, a.tgl_naik
      from site.ticket_kenaikan_harga a left join site.master_supplier b 
        on a.supp = b.supp left join site.master_user c 
        on a.created_by = c.id
      where a.id = $id
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    return $this->db->query($query);
  }

    public function input_kenaikan_harga_detail($data)
    {
        // $this->db->insert('site.kenaikan_harga_detail', $data);
        // return $this->db->insert_id();
        try {
            $this->db->insert('site.kenaikan_harga_detail', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            // Log error untuk debugging
            log_message('error', 'Database Error: ' . $e->getMessage());
            
            // Return false atau custom error code
            return false;
        }
    }

    public function get_kenaikan_harga_detail_by_id_header($id_header)
    {
      $query = "
          select  a.id, a.id_header, a.kodeprod, a.harga_jual_grosir, 
                a.harga_jual_retail, a.harga_jual_motoris_retail, 
                a.harga_jual_mt, a.created_by, a.created_at, b.namaprod, a.signature
        from site.kenaikan_harga_detail a left join (
          select a.kodeprod, a.namaprod
          from site.master_product a 
        )b on a.kodeprod = b.kodeprod
        where a.id_header = $id_header and a.deleted_at is null
      ";
      // echo "<pre>";
      // print_r($query);
      // echo "</pre>";
      return $this->db->query($query);
    }

    public function get_kenaikan_harga_detail_by_id_header_no_master_product($id_header)
    {
        $query = "
            select  a.id, a.id_header, a.kodeprod, a.harga_jual_grosir, 
                    a.harga_jual_retail, a.harga_jual_motoris_retail, 
                    a.harga_jual_mt, a.created_by, a.created_at
            from site.kenaikan_harga_detail a
            where a.id_header = $id_header and a.deleted_at is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function import_kenaikan_harga($data)
    {
        $this->db->insert('site.import_kenaikan_harga', $data);
        return $this->db->insert_id();
    }

    public function get_kenaikan_harga_detail_by_id_header_n_kodeprod($id_header, $kodeprod)
    {
        $query = "
            select a.id, a.id_header, a.kodeprod, a.harga_jual_grosir, 
                    a.harga_jual_retail, a.harga_jual_motoris_retail, 
                    a.harga_jual_mt, a.created_by, a.created_at
            from site.kenaikan_harga_detail a
            where a.id_header = $id_header and a.kodeprod = '$kodeprod'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_kenaikan_harga_detail($data, $id_header, $kodeprod)
    {
        $this->db->where('id_header', $id_header);
        $this->db->where('kodeprod', $kodeprod);
        $update = $this->db->update('site.kenaikan_harga_detail', $data);
        return $update;
    }

    public function get_kenaikan_harga_detail_by_signature($signature)
    {
        $query = "
            select a.id, a.kodeprod
            from site.kenaikan_harga_detail a 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function update_kenaikan_harga_detail_by_id($data, $id)
    {
        $this->db->where('id', $id);
        $update = $this->db->update('site.kenaikan_harga_detail', $data);
        return $update;
    }

    public function get_monitoring($signature)
    {
        $query = "
            select 	a.id, a.nomor_ticket, a.supp, a.keterangan, 
                    a.file, a.attachments, a.`status`, a.nama_status, 
                    a.on_duty, a.created_at, a.created_by,
                    b.namasupp, c.username as created_by_username, 
                    d.username as on_duty_username
            from site.ticket_kenaikan_harga a left join (
                select a.supp, a.namasupp
                from site.master_supplier a 
            )b on a.supp = b.supp left join (
                select a.id, a.username
                from site.master_user a 
            )c on a.created_by = c.id left join (
                select a.id, a.username
                from site.master_user a 
            )d on a.on_duty = d.id
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_ticket_kenaikan_harga($data, $id)
    {
        $this->db->where('id', $id);
        $update = $this->db->update('site.ticket_kenaikan_harga', $data);
        return $update;
    }

    public function get_report_product_nasional()
    {
        $query = "
            select 	a.id, a.id_ticket, a.label, a.tanggal_aktif,
                    b.kodeprod, b.harga_jual_grosir, b.harga_jual_retail, b.harga_jual_motoris_retail, b.harga_jual_mt,
                    c.namasupp, c.namaprod, c.nama_group, c.nama_sub_group,
                    d.nomor_ticket
            from site.kenaikan_harga_header a left join (
                select a.id, a.id_header, a.kodeprod, a.harga_jual_grosir, a.harga_jual_retail, a.harga_jual_motoris_retail, a.harga_jual_mt
                from site.kenaikan_harga_detail a
                where a.deleted_at is null
            )b on a.id = b.id_header left join (
                select a.supp, a.namasupp, a.kodeprod, a.namaprod, a.nama_group, a.nama_sub_group
                from site.master_product a 
            )c on b.kodeprod = c.kodeprod left join (
                select a.id, a.nomor_ticket, a.created_at, a.created_by
                from site.ticket_kenaikan_harga a 
                where a.deleted_at is null
            )d on a.id_ticket = d.id
            where a.deleted_at is null
        ";
        return $this->db->query($query);
    }

  public function get_hit_api_get($id_ticket)
  {
      $query = "
          select a.id, a.id_ticket, a.site_code, a.created_at, b.branch_name, b.nama_comp
          from site.kenaikan_harga_log_get a left join (
              select a.branch_name, a.nama_comp, a.site_code
              from site.master_site a 
          )b on a.site_code = b.site_code
          where a.id_ticket = $id_ticket
          order by a.created_at desc
      ";
      // echo "<pre>";
      // print_r($query);
      // echo "</pre>";
      return $this->db->query($query);
  }

  public function insert_kenaikan_harga_monitoring_feedback($data)
  {
      $insert = $this->db->insert('site.kenaikan_harga_monitoring_feedback', $data);
      return $this->db->insert_id();
  }

  public function get_feedback_by_idticket_idheader_site_code($id_ticket, $id_header, $site_code)
  {
      $query = "
          select a.id, a.site_code, a.created_at
          from site.kenaikan_harga_feedback a
          where a.id_ticket = $id_ticket 
          and a.id_header = $id_header 
          and a.site_code = '$site_code' 
          order by a.created_at desc
          limit 1 
      ";
      // echo "<pre>";
      // print_r($query);
      // echo "</pre>";
      return $this->db->query($query);
  }

  public function get_detail_feedback_by_id_feedback($id_feedback)
  {
      $query = "
          select a.*
          from site.kenaikan_harga_detail_feedback a
          where a.id_header = $id_feedback
      ";
      return $this->db->query($query);
  } 

  public function delete_kenaikan_harga_monitoring_feedback_by_id_ticket($id_ticket)
  {
      $this->db->where('id_ticket', $id_ticket);
      $delete = $this->db->delete('site.kenaikan_harga_monitoring_feedback');
      return $delete;
  }

  public function delete_kenaikan_harga_monitoring_detail_feedback_by_id_ticket($id_ticket)
  {
      $this->db->where('id_ticket', $id_ticket);
      $delete = $this->db->delete('site.kenaikan_harga_monitoring_detail_feedback');
      return $delete;
  }

  public function get_feedback_by_id_ticket_id_header($id_ticket, $id_header)
  {
      $query = "
          select a.id, a.id_ticket, a.id_header, a.nomor_ticket, a.site_code, a.computer_name, a.label, a.tanggal_aktif, a.created_at
          from site.kenaikan_harga_feedback a 
          where a.id_ticket = $id_ticket and a.id_header = $id_header
      ";
      // echo "<pre>";
      // print_r($query);
      // echo "</pre>";
      // die;
      return $this->db->query($query);
  }

  public function get_detail_feedback_by_id_header($id_header)
  {
      $query = "
          select a.id, a.id_header, a.kodeprod, a.harga_jual_grosir, a.harga_jual_retail, a.harga_jual_motoris_retail, a.harga_jual_mt
          from site.kenaikan_harga_detail_feedback a 
          where a.id_header = $id_header
      ";
      // echo "<pre>";
      // print_r($query);
      // echo "</pre>";
      // die;
      return $this->db->query($query);
  }

  public function insert_kenaikan_harga_monitoring_detail_feedback($data)
  {
      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      // die;
      $insert = $this->db->insert('site.kenaikan_harga_monitoring_detail_feedback', $data);
      return $this->db->insert_id();
  }

  public function get_monitoring_feedback_by_id_ticket($id_ticket)
  {
      $query = "
          select a.id, a.id_ticket, a.id_header, a.site_code, a.site_code_feedback, b.*, c.branch_name, c.nama_comp, b.namaprod
          from site.kenaikan_harga_monitoring_feedback a left join (
              select 	a.id_header, a.kodeprod, a.harga_jual_grosir, a.harga_jual_retail, a.harga_jual_motoris_retail, a.harga_jual_mt,
                      a.harga_jual_grosir_feedback, a.harga_jual_retail_feedback, a.harga_jual_motoris_retail_feedback,
                      a.harga_jual_mt_feedback, b.namaprod
              from site.kenaikan_harga_monitoring_detail_feedback a left join site.master_product b on a.kodeprod = b.kodeprod
          )b on a.id = b.id_header left join (
              select a.branch_name, a.nama_comp, a.site_code
              from site.master_site a 
          )c on a.site_code = c.site_code
          where a.id_ticket = $id_ticket
      ";
      // echo "<pre>";
      // print_r($query);
      // echo "</pre>";
      return $this->db->query($query);
  }

    public function get_ticket_by_signature($signature)
    {
        $query = "
            select a.id
            from site.ticket_kenaikan_harga a
            where a.signature = '$signature'    
        ";
        return $this->db->query($query);
    }

    public function update_kenaikan_harga_header($data, $id)
    {
        $this->db->where('id', $id);
        $update = $this->db->update('site.kenaikan_harga_header', $data);
        return $update;
    }

    public function register_header($data)
    {
      echo "<pre>";
      print_r($data->result());
      echo "</pre>";
    
    }

  public function insert_temp_monitoring_get($data)
  {
    $insert = $this->db->insert('site.temp_kenaikan_harga_monitoring_get', $data);
    return $insert;
  }

  public function truncate_temp_monitoring_get()
  {
    $truncate = $this->db->truncate('site.temp_kenaikan_harga_monitoring_get');
    return $truncate;
  }

  public function get_site_code_not_in_get_api()
  {
    $query = "
      select a.site_code_registered, b.nama_comp, b.branch_name
      from site.temp_kenaikan_harga_monitoring_get a left join site.master_site b 
        on a.site_code_registered = b.site_code
      where a.site_code_registered not in (
        select a.site_code
        from site.kenaikan_harga_log_get a 
        where a.id_ticket = 2
      )
    ";
    return $this->db->query($query);
  }

}