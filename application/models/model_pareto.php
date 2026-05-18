<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_pareto extends CI_Model 
{
  public function __construct() 
  {
    $this->created_at = $this->model_outlet_transaksi->timezone();
    $this->userid = $this->session->userdata('id');
  }

  public function get_pareto($tahun, $site_code = "", $supp, $periode, $type, $class)
  {

    if($site_code){
      $params_site_code = "and a.site_code ='$site_code'";
    }else{
      $params_site_code = "";
    }

    if($periode)
    {
      $params_periode = "and a.periode = '$periode'"; 
    }else {
      // return false;
      $params_periode = "";
    }

    if($type)
    {
      $params_type = "and a.include_pharma = '$type'"; 
    }else{
      // return false;
      $params_type = "";
    }

    if($class)
    {
      $params_class = "and a.include_ritel = '$class'"; 
    }else{
      // return false;
      $params_class = "";
    }

    $query = "
      select  a.id, a.site_code, a.branch_name,a.nama_comp, a.outlet, a.nama_outlet,
              a.kode_class, a.nama_class, a.kode_type, a.nama_type, CAST(a.omzet/1000000 AS SIGNED INTEGER) as omzet,
              a.periode, a.include_pharma, a.tahun
      from site.pareto_account_sales_comulative a 
      where a.tahun in ($tahun) and a.supp ='$supp' $params_site_code $params_periode $params_type $params_class
      order by a.tahun asc, CAST(a.omzet AS SIGNED INTEGER) desc 
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";

    // die;
    return $this->db->query($query);
  }

  public function get_master_site()
  {
    $region = $this->get_region();

    $site_code = $this->get_site_code_by_region($region);

    $query = "
      select a.site_code, a.branch_name, a.nama_comp, a.region
      from site.master_site a 
      where a.active = 1 and a.region is not null and a.region <> 'PENTA' and a.site_code in ($site_code)
      ORDER BY 	a.region = 'JAKARTA' desc, a.region = 'JABAR' desc,
                a.region = 'JATENG' desc, a.region ='JATIM' desc,
                a.region ='BALI' desc,
                a.region = 'SUMATERA' desc, a.region ='KALIMANTAN' desc,
                a.region = 'SULAWESI' desc
    ";
    return $this->db->query($query);
  }

  public function get_region()
  {
    $this->load->model('model_management_sales');

    // echo "aaa";
    // die;

    $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($this->session->userdata('id'));
    if ($get_region->num_rows() > 0) {
      $region = 'all';
    }else{
      $get_region = $this->model_management_sales->get_region_by_map_akses_region($this->session->userdata('id'));
      if (!$get_region->num_rows() > 0) {
          redirect('management_office/kalender_data');
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
    return $region;
  }

  public function get_site_code_by_region($region)
  {
    // jika username login adalah BM, seperti bagio, adi, dan sebagainya
    if ($this->session->userdata('username') == 'bagio' || $this->session->userdata('username') == 'bisman' || $this->session->userdata('username') == 'adhi' || $this->session->userdata('username') == 'rasyid') {
      $get_data = $this->model_management_sales->get_site_code_by_sub_region($region);
      $count_site_code = count($get_data->result());
      $site_code = "";
      foreach ($get_data->result() as $s) {
          $site_code.= ",'".$s->site_code."'";
      }
      $site_code = preg_replace('/,/', '', $site_code,1);
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
    return $site_code;
  }

  public function get_master_principal($supp)
  {
    if($supp == '000')
    {
      $params = "where a.supp in (001,005)";
    }else{
      $params = "where a.supp = '$supp'";
    }

    $query = "
      select a.supp, a.namasupp
      from site.master_supplier a 
      $params
    ";
    return $this->db->query($query);
  }

  public function truncate()
  {
    $query = "truncate site.pareto_account_sales";
    return $this->db->query($query);
  }

  public function truncate_current()
  {
    $query = "truncate site.pareto_account_sales_current";
    return $this->db->query($query);
  }

  public function truncate_comulative()
  {
    $query = "truncate site.pareto_account_sales_comulative";
    return $this->db->query($query);
  }

  public function update_data($year, $site_code = "", $supp, $periode, $type, $class)
  {
    if($periode == 'q1')
    {
      $params_periode = "where a.bulan in (1,2,3)";
    }else if($periode == 'q2')
    {
      $params_periode = "where a.bulan in (4,5,6)";
    }else if($periode == 'q3')
    {
      $params_periode = "where a.bulan in (7,8,9)";
    }else if($periode == 'q4')
    {
      $params_periode = "where a.bulan in (10,11,12)";
    }else{
      $params_periode = "where a.bulan in (1,2,3,4,5,6,7,8,9,10,11,12)";
    }

    if($type == 'include_pharma')
    {
      $params_type = "";
    }else{
      $params_type = "and a.kode_type not in ('PBF','TOB','APT','APC','HM','HP','HPM','MM','MML','MMN','SM','SMB','SML','SMN','SMR','SW','SWL','MTP')";
    }

    if($class == 'include ritel')
    {
      $params_class = "";  
    }else{
      $params_class = "and a.kodesalur not in ('RT','R','R1','R2','R3','02','03','04')";
    }

    $query = "
      insert into site.pareto_account_sales
      select  '', a.site_code, '', '', a.outlet, a.nama_lang, a.kodesalur, '', a.kode_type, '', 
              $year, sum(a.omzet), '$supp', '', '$periode', '$type', '$class'
      from 
      (
          select 	concat(a.kode_comp, a.kode_lang) as outlet, sum(a.tot1) as omzet, a.nama_lang, a.kodesalur, a.kode_type, 
                  concat(a.kode_comp, a.nocab) as site_code, a.supp
          from data$year.fi a 
          $params_periode and a.kodeprod in (
            select a.kodeprod
            from site.master_product a 
            where a.supp = $supp
          ) /*and a.kode_comp ='pkb'*/
          $params_type
          $params_class
          group by concat(a.kode_comp, a.kode_lang)
          union all 
          select 	concat(a.kode_comp, a.kode_lang) as outlet, sum(a.tot1) as omzet, a.nama_lang, a.kodesalur, a.kode_type, 
                  concat(a.kode_comp, a.nocab) as site_code, a.supp
          from data$year.ri a 
          $params_periode and a.kodeprod in (
            select a.kodeprod
            from site.master_product a 
            where a.supp = $supp
          ) /*and a.kode_comp ='pkb'*/
          $params_type
          $params_class
          group by concat(a.kode_comp, a.kode_lang)
      )a GROUP BY a.outlet
      ORDER BY sum(a.omzet) desc;
    ";

    echo "<pre>";
    print_r($query);
    echo "<hr>";
    echo "</pre>";

    return $this->db->query($query);
  }

  public function update_data_current($year, $site_code = "", $supp, $periode, $type, $class)
  {
    if($periode == 'q1')
    {
      $params_periode = "where a.bulan in (1,2,3)";
    }else if($periode == 'q2')
    {
      $params_periode = "where a.bulan in (4,5,6)";
    }else if($periode == 'q3')
    {
      $params_periode = "where a.bulan in (7,8,9)";
    }else if($periode == 'q4')
    {
      $params_periode = "where a.bulan in (10,11,12)";
    }else{
      $params_periode = "where a.bulan in (1,2,3,4,5,6,7,8,9,10,11,12)";
    }

    if($type == 'include_pharma')
    {
      $params_type = "";
    }else{
      $params_type = "and a.kode_type not in ('PBF','TOB','APT','APC','HM','HP','HPM','MM','MML','MMN','SM','SMB','SML','SMN','SMR','SW','SWL','MTP')";
    }

    if($class == 'include ritel')
    {
      $params_class = "";  
    }else{
      $params_class = "and a.kodesalur not in ('RT','R','R1','R2','R3','02','03','04')";
    }

    $query = "
      insert into site.pareto_account_sales_current
      select  '', a.site_code, '', '', a.outlet, a.nama_lang, a.kodesalur, '', a.kode_type, '', 
              $year, sum(a.omzet), '$supp', '', '$periode', '$type', '$class'
      from 
      (
          select 	concat(a.kode_comp, a.kode_lang) as outlet, sum(a.tot1) as omzet, a.nama_lang, a.kodesalur, a.kode_type, 
                  concat(a.kode_comp, a.nocab) as site_code, a.supp
          from data$year.fi a 
          $params_periode and a.kodeprod in (
            select a.kodeprod
            from site.master_product a 
            where a.supp = $supp
          ) /*and a.kode_comp ='pkb'*/
          $params_type
          $params_class
          group by concat(a.kode_comp, a.kode_lang)
          union all 
          select 	concat(a.kode_comp, a.kode_lang) as outlet, sum(a.tot1) as omzet, a.nama_lang, a.kodesalur, a.kode_type, 
                  concat(a.kode_comp, a.nocab) as site_code, a.supp
          from data$year.ri a 
          $params_periode and a.kodeprod in (
            select a.kodeprod
            from site.master_product a 
            where a.supp = $supp
          ) /*and a.kode_comp ='pkb'*/
          $params_type
          $params_class
          group by concat(a.kode_comp, a.kode_lang)
      )a GROUP BY a.outlet
      ORDER BY sum(a.omzet) desc;
    ";

    echo "<pre>";
    print_r($query);
    echo "<hr>";
    echo "</pre>";

    return $this->db->query($query);

  }

  public function update_branch($flag)
  {
    if($flag)
    {
      $params = "pareto_account_sales_current";
    }else{
      $params = "pareto_account_sales";
    }

    $query = "
      UPDATE site.$params a
      JOIN site.master_site b ON a.site_code = b.site_code
      SET a.branch_name = b.branch_name,
          a.nama_comp = b.nama_comp
    ";

    return $this->db->query($query);
  }

  public function update_class($flag)
  {
    if($flag)
    {
      $params = "pareto_account_sales_current";
    }else{
      $params = "pareto_account_sales";
    }

    $query = "
      update site.$params a inner join mpm.tbl_tabsalur b 
        on a.kode_class = b.kode 
      set a.nama_class = b.jenis
    ";
    return $this->db->query($query);
  }

  public function update_principal($flag)
  {
    if($flag)
    {
      $params = "pareto_account_sales_current";
    }else{
      $params = "pareto_account_sales";
    }

    $query = "
      update site.$params a left join mpm.tabsupp b 
        on a.supp = b.supp 
      set a.namasupp = b.namasupp
    ";
    return $this->db->query($query);
  }

  public function update_type($flag)
  {
    if($flag)
    {
      $params = "pareto_account_sales_current";
    }else{
      $params = "pareto_account_sales";
    }

    $query = "
      update site.$params a left join mpm.tbl_bantu_type b 
        on a.kode_type = b.kode_type 
      set a.nama_type = b.nama_type
    ";
    return $this->db->query($query);
  }

  public function update_data_comulative()
  {
    $query = "
      insert into site.pareto_account_sales_comulative
      select '', a.site_code, a.branch_name, a.nama_comp, a.outlet, a.nama_outlet, a.kode_class,
            a.nama_class, a.kode_type, a.nama_type, a.tahun, a.omzet, a.supp,
            a.namasupp, a.periode, a.include_pharma, a.include_ritel 
      from site.pareto_account_sales a
      union all
      select '', a.site_code, a.branch_name, a.nama_comp, a.outlet, a.nama_outlet, a.kode_class,
            a.nama_class, a.kode_type, a.nama_type, a.tahun, a.omzet, a.supp,
            a.namasupp, a.periode, a.include_pharma, a.include_ritel 
      from site.pareto_account_sales_current a
    ";
    return $this->db->query($query);
  }

  public function get_rank_mti()
  {
    $query = "
      select  a.id as actual_rank, a.sub_group, a.count_sub_group as actual_count_sub_group, 
              a.outlet,
              a.bruto as actual_bruto, a.tahun, b.id as rank, 
              b.bruto, b.count_sub_group, date(a.created_at) as created_at
      from site.pareto_rank_2026 a left join site.pareto_rank_2025 b on 
        a.sub_group = b.sub_group
      ORDER BY a.id asc
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query);
  }

  public function get_master_outlet_mti()
  {
    $query = "
      select 	a.site_code, a.outlet, a.nama_outlet, a.is_active, a.sub_group,
              a.created_at, a.created_by, a.updated_at, a.updated_by,
              a.deleted_at, a.deleted_by, b.branch_name, b.nama_comp
      from site.pareto_master_outlet_mti a left join site.master_site b 
        on a.site_code = b.site_code
    ";
    return $this->db->query($query);
  }

  public function insert($table, $data)
  {
    $this->db->insert($table, $data);
    return $this->db->insert_id();
  }

  public function truncate_master_outlet_mti()
  {
    $query = "truncate site.pareto_master_outlet_mti";
    // echo $query;
    return $this->db->query($query);
  }

  public function get_pareto_rank_actual($id)
  {
    $query = "
      select *
      from site.pareto_rank_2026 a 
      where a.id = $id
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query);
  }

  public function get_pareto_omzet_outlet_sub_group_actual($id)
  {
    $query = "
      select *
      from site.pareto_omzet_outlet_sub_group_2026 a 
      where a.id = $id
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query);
  }

  // public function get_pareto_omzet_outlet_sub_group_actual_by_sub_group($sub_group)
  // {
  //   $query = "
  //     select b.branch_name, b.nama_comp, a.site_code, a.outlet, a.nama_outlet, a.kode_type, SUM(CAST(a.bruto AS DECIMAL(15,2))) as bruto, a.sub_group, a.created_at, a.tahun, a.bulan
  //     from site.pareto_omzet_outlet_sub_group_2026 a left join site.master_site b 
  //       on a.site_code = b.site_code
  //     where a.sub_group = '$sub_group'
  //     group by a.bulan
  //     union all
  //     select b.branch_name, b.nama_comp, a.site_code, a.outlet, a.nama_outlet, a.kode_type, SUM(CAST(a.bruto AS DECIMAL(15,2))) as bruto, a.sub_group, a.created_at, a.tahun, a.bulan
  //     from site.pareto_omzet_outlet_sub_group_2025 a left join site.master_site b 
  //       on a.site_code = b.site_code
  //     where a.sub_group = '$sub_group'
  //     group by a.bulan
  //   ";
    
  //   return $this->db->query($query);
  // }

  // public function get_pareto_omzet_outlet_sub_group_actual_by_sub_group($sub_group)
  // {
  //     $query = "
  //         select 
  //             b.branch_name, 
  //             b.nama_comp, 
  //             a.site_code, 
  //             a.outlet, 
  //             a.nama_outlet, 
  //             a.kode_type, 
  //             SUM(CAST(a.bruto AS DECIMAL(15,2))) as bruto, 
  //             a.sub_group, 
  //             a.created_at, 
  //             a.tahun, 
  //             a.bulan
  //         from site.pareto_omzet_outlet_sub_group_2026 a 
  //         left join site.master_site b on a.site_code = b.site_code
  //         where a.sub_group = '$sub_group'
  //         group by a.bulan, a.outlet, a.nama_outlet, a.kode_type, a.tahun, a.sub_group, a.created_at, b.branch_name, b.nama_comp, a.site_code
          
  //         union all
          
  //         select 
  //             b.branch_name, 
  //             b.nama_comp, 
  //             a.site_code, 
  //             a.outlet, 
  //             a.nama_outlet, 
  //             a.kode_type, 
  //             SUM(CAST(a.bruto AS DECIMAL(15,2))) as bruto, 
  //             a.sub_group, 
  //             a.created_at, 
  //             a.tahun, 
  //             a.bulan
  //         from site.pareto_omzet_outlet_sub_group_2025 a 
  //         left join site.master_site b on a.site_code = b.site_code
  //         where a.sub_group = '$sub_group'
  //         group by a.bulan, a.outlet, a.nama_outlet, a.kode_type, a.tahun, a.sub_group, a.created_at, b.branch_name, b.nama_comp, a.site_code
          
  //         order by tahun desc, bulan, outlet
  //     ";
      
  //     return $this->db->query($query);
  // }

  public function get_perbandingan_omzet_by_sub_group($sub_group)
  {
      $query = "
          SELECT 
              b.branch_name, 
              b.nama_comp, 
              a.outlet, 
              a.nama_outlet, 
              a.kode_type, 
              a.sub_group,
              a.bulan,
              MAX(CASE WHEN a.tahun = 2025 THEN a.bruto ELSE 0 END) as bruto_2025,
              MAX(CASE WHEN a.tahun = 2026 THEN a.bruto ELSE 0 END) as bruto_2026,
              (MAX(CASE WHEN a.tahun = 2026 THEN a.bruto ELSE 0 END) - 
              MAX(CASE WHEN a.tahun = 2025 THEN a.bruto ELSE 0 END)) as selisih,
              CASE 
                  WHEN MAX(CASE WHEN a.tahun = 2025 THEN a.bruto ELSE 0 END) > 0 
                  THEN ((MAX(CASE WHEN a.tahun = 2026 THEN a.bruto ELSE 0 END) - 
                        MAX(CASE WHEN a.tahun = 2025 THEN a.bruto ELSE 0 END)) / 
                        MAX(CASE WHEN a.tahun = 2025 THEN a.bruto ELSE 0 END)) * 100 
                  ELSE 0 
              END as pertumbuhan
          FROM (
              -- Data 2026
              SELECT 
                  a.site_code, 
                  a.outlet, 
                  a.nama_outlet, 
                  a.kode_type, 
                  a.sub_group,
                  a.tahun,
                  a.bulan,
                  SUM(CAST(a.bruto AS DECIMAL(15,2))) as bruto
              FROM site.pareto_omzet_outlet_sub_group_2026 a 
              WHERE a.sub_group = '$sub_group'
              GROUP BY a.outlet, a.nama_outlet, a.kode_type, a.sub_group, a.tahun, a.bulan, a.site_code
              
              UNION ALL
              
              -- Data 2025
              SELECT 
                  a.site_code, 
                  a.outlet, 
                  a.nama_outlet, 
                  a.kode_type, 
                  a.sub_group,
                  a.tahun,
                  a.bulan,
                  SUM(CAST(a.bruto AS DECIMAL(15,2))) as bruto
              FROM site.pareto_omzet_outlet_sub_group_2025 a 
              WHERE a.sub_group = '$sub_group'
              GROUP BY a.outlet, a.nama_outlet, a.kode_type, a.sub_group, a.tahun, a.bulan, a.site_code
          ) a
          LEFT JOIN site.master_site b ON a.site_code = b.site_code
          GROUP BY 
              b.branch_name, 
              b.nama_comp, 
              a.outlet, 
              a.nama_outlet, 
              a.kode_type, 
              a.sub_group,
              a.bulan
          ORDER BY a.bulan, a.outlet
      ";
      
      return $this->db->query($query);
  }



  public function get_pareto_rank_by_sub_group($sub_group)
  {
    $query = "
      select *
      from site.pareto_rank_2026 a 
      where a.sub_group = '$sub_group'
      union all
      select *
      from site.pareto_rank_2025 a 
      where a.sub_group = '$sub_group'
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query);
  }


}