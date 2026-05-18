<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_office extends MY_Controller
{    
  public function __construct()
  {
    parent::__construct();
    // Set data khusus untuk controller ini
    $this->data['page_title'] = 'Management Office';

    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login_sistem/','refresh');
    }
    set_time_limit(0);

    $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    $this->load->helper(array('url', 'csv'));
    $this->load->model(array('model_outlet_transaksi', 'model_management_office', 'model_inventory', 'M_helpdesk', 'model_dashboard_dummy', 'model_monitor'));
    $this->session_id = $this->session->userdata('id');
    $this->session_supp = $this->session->userdata('supp');
    $this->session_username = $this->session->userdata('username');
    $this->created_at = $this->model_outlet_transaksi->timezone();
    $this->tahun = date('Y');
    $this->bulan = date('m');
  }

  public function export_sell_out()
  {
      $id=$this->session->userdata('id');
      $query="
          select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, groupsalur,sektor,segment,
                  unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                  unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                  value_1,value_2,value_3,value_4,value_5,value_6,
                  value_7,value_8,value_9,value_10,value_11,value_12, 
                  ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                  ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                  ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                  ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
          from   site.temp_sell_out
          ORDER BY urutan asc
      ";                            
      $hasil = $this->db->query($query);
      query_to_csv($hasil,TRUE,"Sell Out Product Automatic.csv");
  }

    public function export_sell_out_us()
    {
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, `group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   site.temp_soprod_us
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product Us Automatic.csv");
    }

    public function export_sell_out_deltomed_segment()
    {
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod, namaprod, segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12             
            from   site.temp_soprod_deltomed_segment
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product Deltomed Automatic.csv");
    }

    public function update_data_kam()
    {
        $this->load->model('model_mti');
        $data_source = [
            'herbal'    => $this->model_mti->get_kodeprod_by_group('G0101'),
            'candy'     => $this->model_mti->get_kodeprod_by_group_exception('G0102', '010121'),
            'kode_type' => $this->model_mti->get_kode_type_by_sektor('MTI'),
            'kode_type_ph' => $this->model_mti->get_kode_type_by_segment('PH'),
            'all_principal'  => $this->model_mti->get_kodeprod_by_supp(),
        ];

        $update = $this->model_management_office->update_data_kam($data_source);

        if ($update) {
            redirect('management_office/');
        }else{
            echo "something happen. Please call IT";
        }        

    }

    public function kalender_data()
    {
        $this->load->model('model_master_data');
        // $site_code = $this->model_master_data->get_tabcomp_by_kode_comp($this->session->userdata('username'))->row()->site_code;
        $site_code = $this->model_master_data->get_tabcomp_by_kode_comp($this->session->userdata('username'));
        if ($site_code->num_rows() > 0) {
            $site_code = $site_code->row()->site_code;
        }else{
            $site_code = '';
        }

        $data = [         
            'get_kalender_by_bulan' => $this->model_management_office->get_kalender_by_bulan($this->tahun,$this->bulan,"'".$site_code."'")->result(),
        ];

        // Render view
        $this->render('management_office/kalender_data', $data);
    }

    public function generate_doi()
    {
        // truncate hanya untuk development
        $this->db->query("TRUNCATE TABLE site.dashboard_average_sales");
        $this->db->query("TRUNCATE TABLE site.dashboard_stock_akhir");
        $this->db->query("TRUNCATE TABLE site.dashboard_doi");

        // get average
        $tahun_bulan_end = date('Ym');

        // mundur 6 bulan kebelakang
        $tahun_bulan_start = date('Ym', mktime(0, 0, 0, date('m')-6, date('d'), date('Y')));

        // jika beda tahun
        if(substr($tahun_bulan_end,0,4) != substr($tahun_bulan_start,0,4))
        {
            $tahun_bulan_now = substr($tahun_bulan_now,0,4);
            $tahun_bulan_sebelum = substr($tahun_bulan_sebelum,0,4);
        }else{
            $bulan_start = substr($tahun_bulan_start,4,2);
            $bulan_end = substr($tahun_bulan_end,4,2);

            for($i = (int)$bulan_start; $i < (int)$bulan_end; $i++){
                $bulan[$i] = $i;
            }
            $bulan_string = implode(',', $bulan);
            // echo "bulan_string : ".$bulan_string;
            $tahun = substr($tahun_bulan_start,0,4);
            // echo "tahun : ".$tahun;

            $query = "
                insert into site.dashboard_average_sales
                select '', a.site_code, a.kodeprod, round(sum(a.banyak)/6) as avg_unit, '[$bulan_string]' as bulan, '$this->created_at', $this->session_id
                from 
                (
                    select concat(a.kode_comp, a.nocab) as site_code, a.kodeprod, sum(a.banyak) as banyak
                    from data$tahun.fi a 
                    where a.bulan in ($bulan_string)
                    group by concat(a.kode_comp, a.nocab), a.kodeprod
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, a.kodeprod, sum(a.banyak) as banyak
                    from data$tahun.ri a 
                    where a.bulan in ($bulan_string)
                    group by concat(a.kode_comp, a.nocab), a.kodeprod
                )a group by a.site_code, a.kodeprod
            ";
            // echo "<pre>".$query."</pre>";
            $proses = $this->db->query($query);
        }
        // get average end

        // cari cara membuat stock akhir
        $query = "
            insert into site.dashboard_stock_akhir
            select '', b.site_code, a.kodeprod, a.stok, $tahun, $bulan_end, '$this->created_at', $this->session_id
            from 
            (
                select a.nocab,  a.kodeprod, sum(a.stok_akhir) as stok, substr(a.bulan,3) as bulan
                from data$tahun.st a
                where kode_gdg in ('pst',1) and (a.GUDANG_ID is NULL or a.GUDANG_ID in (1)) and substr(a.bulan,3) = $bulan_end
                group by a.nocab, a.kodeprod
            )a left join (
                select a.site_code, a.branch_name, a.nama_comp
                from site.master_site a
                where length(a.site_code) = 5
            )b on a.nocab = right(b.site_code, 2)
        ";
        echo "<pre>".$query."</pre>";
        $proses = $this->db->query($query);


        // membuat doi
        $query = "
            insert into site.dashboard_doi
            select  '', b.site_code, d.branch_name, d.nama_comp, a.supp, a.kodeprod, a.namaprod,
                    b.avg_unit, c.stock, round(c.stock / b.avg_unit * 30) as doi_unit,
                    '$this->created_at', $this->session_id
            from site.master_product_with_harga a left join (
                select a.site_code, a.kodeprod, a.avg_unit
                from site.dashboard_average_sales a  
            )b on a.kodeprod = b.kodeprod left join (
                select a.site_code, a.kodeprod, a.stock
                from site.dashboard_stock_akhir a  
            )c on a.kodeprod = c.kodeprod and b.site_code = c.site_code left join (
                select a.site_code, a.branch_name, a.nama_comp
                from site.master_site a 
            )d on b.site_code = d.site_code
            where a.active = 1 and a.supp in (001,005) and a.namaprod not like 'GIMMICK%' and a.grup <> 'GIMMICK'
        ";
        $proses = $this->db->query($query);
    }

  public function dashboard()
  {    
    $this->load->driver('cache');
    $this->load->model('model_management_sales');
    $this->load->model('model_spk');

    $supp = $this->session->userdata('supp');

    // echo "supp : ".$supp;
    // die;

    $month = $this->input->get('month');
    $month = $month ? $month : date('Y-m');
    $tahun = substr($month, 0, 4);
    $bulan = substr($month, 5, 2);
    $created_at = date('Y-m-d');

    $get_view = $this->model_management_office->get_master_team_apps($this->session_id);
    $view_aktivitas_mpm = "";
    foreach ($get_view->result() as $a) {
        $view_aktivitas_mpm = $a->view_aktivitas_mpm;
    }

    if(empty($view_aktivitas_mpm)){
        $cache_key_region = 'region-' . md5(json_encode($this->session_id));
    }else{
        $cache_key_region = $view_aktivitas_mpm . '-' . md5(json_encode($view_aktivitas_mpm));
    }

    $cached_result = $this->cache->file->get($cache_key_region);
  
    if ($cached_result !== FALSE) {
        $result = $cached_result;
    } else 
    {
      $get_principal = $this->model_management_sales->get_principal($this->session_id);   
      if (!$get_principal->num_rows() > 0) {
        $get_principal = $this->model_management_sales->get_principal_by_supp($this->session_supp);
      }

      $principal = "";
      foreach ($get_principal->result() as $a) {
        $principal .= "," . $a->supp;
      }
      $supp = preg_replace('/,/', '', $principal, 1);

      $get_kodeprod = $this->model_management_sales->get_master_product($supp);
      $kodeprod = "";
      foreach ($get_kodeprod->result() as $a) {
        $kodeprod.=",".$a->kodeprod;
      }
      $kodeprod = preg_replace('/,/', '', $kodeprod,1);

      //get region by map_akses_region
      $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($this->session_id);
      if ($get_region->num_rows() > 0) {
        $region = 'all';
      }else{
        $get_region = $this->model_management_sales->get_region_by_map_akses_region($this->session_id);
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

      $get_temp_chart_get_omzet_by_bulan = $this->model_management_office->get_temp_chart_get_omzet_by_bulan($tahun, $bulan, $this->session_id, $created_at);
      if (!$get_temp_chart_get_omzet_by_bulan->num_rows() > 0) {
          // echo "data tidak ada, lakukan insert";
          $insert_omzet_by_bulan = $this->model_management_office->insert_omzet_by_bulan($tahun,$bulan,$site_code,$kodeprod, $this->session_id, $created_at);

          $get_temp_chart_get_omzet_by_bulan_recall = $this->model_management_office->get_temp_chart_get_omzet_by_bulan($tahun, $bulan, $this->session_id, $created_at);
      }else{
          $get_temp_chart_get_omzet_by_bulan_recall = $get_temp_chart_get_omzet_by_bulan;
      }

      $get_total_omzet = $this->model_management_office->total_omzet($site_code, $kodeprod, $tahun, $bulan);
      $get_total_po = $this->model_management_office->total_po($site_code, $tahun, $bulan);

      $kalender_data = $this->model_management_office->get_kalender_by_bulan($tahun,$bulan,$site_code,$kodeprod);
      // $data_doi = $this->model_management_office->get_data_doi($site_code, $kodeprod);
      // $summary_doi = $this->model_management_office->summary_doi($site_code, $kodeprod);
      // if($summary_doi) {
      //     $summary_doi = $summary_doi->result();
      // }else{
      //     $summary_doi = array();
      // }

      if ($region != 'all') {
          $get_kodelang = $this->model_spk->get_kodelang_by_region($region)->result();

          if(count($get_kodelang) == 0) 
          {
            $get_kodelang = $this->model_management_office->get_kodelang_by_site_code($site_code);
            if ($get_kodelang->num_rows() > 0) {
              foreach ($get_kodelang->result() as $key) {
                $kodelang[] = '1'.$key->kode_lang;
              }
              $kodelang = implode(',', $kodelang);
            }else{
                $kodelang = '';
            }
          }else{
            foreach ($get_kodelang as $key) {
              $kodelang[] = '1'.$key->kode_lang;
            }
            $kodelang = implode(',', $kodelang);
          }                
      } else 
      {
        $get_kodelang = $this->model_management_office->get_kodelang_by_site_code($site_code);
        if ($get_kodelang->num_rows() > 0) {
            foreach ($get_kodelang->result() as $key) {
                $kodelang[] = '1'.$key->kode_lang;
            }
            $kodelang = implode(',', $kodelang);
        }else{
            $kodelang = '';
        }
      }

      $generate_analisa_piutang = $this->model_management_office->generate_analisa_piutang($created_at, $kodelang);

      $get_analisa_piutang = $this->model_management_office->get_analisa_piutang($kodelang);
      $result = [
          'supp' => $supp,
          'kodeprod' => $kodeprod,
          'region' => $region,
          'site_code' => $site_code,
          'kalender_data'     => $kalender_data->result(),
          // 'data_doi'          => $data_doi ? $data_doi->result() : [],
          'get_total_omzet'   => $get_total_omzet->row()->total_omzet,
          'get_total_po'      => $get_total_po->row()->total_po,
          // 'summary_doi'       => $summary_doi,
          'total_ar'          => $get_analisa_piutang ? $get_analisa_piutang->row()->total : 0,
          'count'             => $get_analisa_piutang ? $get_analisa_piutang->row()->count : 0,
      ];
      $save_result = $this->cache->file->save($cache_key_region, $result, 10800);
    }
    $data = [
      'get_kalender_by_bulan' => $result['kalender_data'],
      'title'             => 'Dashboard',
      'url'               => 'management_bonus/import_master_data',
      'get_kalender_by_bulan' => $result['kalender_data'],
      // 'get_doi'           => $result['data_doi'],
      'get_doi'           => [],
      'get_total_omzet'   => $result['get_total_omzet'],
      'get_total_po'      => $result['get_total_po'],
      // 'summary_doi'       => $result['summary_doi'],
      'summary_doi'       => [],
      'total_ar'          => $result['total_ar'],
      'count'             => $result['count'],
      'created_at'        => $created_at,
      'supp'              => $this->session->userdata('supp')
    ];

    $this->render_multiple(
      array(
        'management_office/dashboard_doi',
        // 'management_office/chart_sales',
        'management_office/master_company',
        // 'management_office/tabel_doi',
        'management_office/kalender_data'
      ),
      $data
    );
  }

    public function export_doi()
    {
        $this->load->model('model_management_sales');

        $get_principal = $this->model_management_sales->get_principal($this->session_id);   
        if (!$get_principal->num_rows() > 0) {
            $get_principal = $this->model_management_sales->get_principal_by_supp($this->session_supp);
        }

        $principal = "";
        foreach ($get_principal->result() as $a) {
            $principal .= "," . $a->supp;
        }
        $supp = preg_replace('/,/', '', $principal, 1);
        // echo "supp : " . $supp . "<br>";

        // get data product
        $get_kodeprod = $this->model_management_sales->get_master_product($supp);
        $kodeprod = "";
        foreach ($get_kodeprod->result() as $a) {
            $kodeprod.=",".$a->kodeprod;
        }
        $kodeprod = preg_replace('/,/', '', $kodeprod,1);
        // echo "kodeprod : ".$kodeprod;

        //get region by map_akses_region
        $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($this->session_id);
        if ($get_region->num_rows() > 0) {
            $region = 'all';
        }else{
            $get_region = $this->model_management_sales->get_region_by_map_akses_region($this->session_id);
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
        // echo "region : ".$region;

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

        $data = $this->model_management_office->get_data_doi($site_code, $kodeprod);
        query_to_csv($data,TRUE,"export doi.csv");
    }

    public function export_master_company()
    {
        $this->load->model('model_management_sales');

        //get region by map_akses_region
        $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($this->session_id);
        if ($get_region->num_rows() > 0) {
            $region = 'all';
        }else{
            $get_region = $this->model_management_sales->get_region_by_map_akses_region($this->session_id);
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
        // echo "region : ".$region;

        // jika username login adalah BM, seperti bagio, adi, dan sebagainya
        if ($this->session->userdata('username') == 'bagio' || $this->session->userdata('username') == 'bisman' || $this->session->userdata('username') == 'adhi' || $this->session->userdata('username') == 'rasyid') {
            $get_data = $this->model_management_sales->get_site_code_by_sub_region($region);
            $count_site_code = count($get_data->result());
            $site_code = "";
            foreach ($get_data->result() as $s) {
                $site_code.= ",'".$s->site_code."'";
            }
            $site_code = preg_replace('/,/', '', $site_code,1);
            // echo "site_code : ".$site_code;die;
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

        $data = $this->model_management_office->get_master_company($site_code);
        query_to_csv($data,TRUE,"export master company.csv");

    }
    public function info_old()
    {
        // cek jika sudah exist, lanjut ke dashboard
        // if($this->model_management_office->get_konfirmasi_informasi($this->session_id)->num_rows() > 0)
        // {
        //     redirect('management_office/dashboard');
        //     die;
        // }

        // cek apakah $this->session_id ada di dalam tabel 

        // $cek = $this->model_management_office->get_pending_biop($this->session_id)->num_rows();
        // if($cek > 0)
        // {
        //     $get_data = $this->model_management_office->get_pending_biop();
        // }

        // die;

        // $this->load->view('info_closing');


        // 1. Ambil SEMUA data pending dari semua user (tanpa parameter)
        $all_pending = $this->model_management_office->get_pending_biop()->result();

        // 2. Gunakan array_column untuk mengambil semua ID PIC yang ada di hasil query
        $pic_ids = array_column($all_pending, 'pic_on_duty');

        echo "$this->session_id"; //433

        // 3. Cek apakah session_id user saat ini ada di dalam list tersebut
        if (in_array($this->session_id, $pic_ids)) 
        {
            echo "ada";
            // Jika ada, tampilkan view dan kirim semua datanya
            // $data['list_pending'] = $all_pending;
            // $this->load->view('info_closing', $data);
        } 
        else 
        {
            echo "tidak ada";
            // Jika user tidak punya pendingan sama sekali, langsung ke dashboard
            // redirect('management_office/dashboard');
        }



        
    }

  public function info()
  {
    // get pending biop
    $all_pending = $this->model_management_office->get_pending_biop()->result();
    $pic_ids = [];
    foreach ($all_pending as $row) {
        $pic_ids[] = (int)$row->pic_on_duty;
    }
    if (in_array((int)$this->session_id, $pic_ids)) 
    {
        $data['list_pending'] = $all_pending;
        $this->load->view('info_biop', $data);
    } 
    else 
    {
      if($this->session->userdata('supp') == '000' || $this->session->userdata('supp') == '001' || $this->session->userdata('level') == '3b' || $this->session->userdata('level') =='3c')
      {
        if($this->model_management_office->get_konfirmasi_informasi($this->session_id)->num_rows() > 0)
        {
            redirect('management_office/dashboard_new');
            die;
        }else{
          $this->load->view('info_menu_baru', $data);
        }
      }else{
        redirect('management_office/dashboard_new');
      }
    }
  }

    public function cek_karyawan()
    {
      $query = $this->model_management_office->get_karyawan_by_username($this->session_username);

      if($query->num_rows() > 0)
      {
        redirect('management_office/dashboard');
      }else{
        $this->load->view('info_karyawan', $data);
      }
    }

  public function insert_konfirmasi_informasi()
  {
    $data = [
        'userid' => $this->session_id,
        'created_at' => $this->model_outlet_transaksi->timezone(),
        'flag_confirm' => 1 
    ];

    $this->model_management_office->insert_konfirmasi_informasi($data);
    redirect ('management_office/dashboard_new');
  }

  public function dashboard_new()
  {    
    $this->load->model('model_management_sales');
    $today = date('Y-m-d');
    // $today = '2026-04-17';
    // echo "today : ".$today;

    $get_team_member = $this->model_management_office->get_team_member($this->session_id);
    if($get_team_member->num_rows() > 0){
      foreach ($get_team_member->result() as $a) {
        $userid[] = $a->userid;
        $team_member[] = "'".$a->username."'";
      }
    }else{
      redirect('management_office/dashboard');
    }

    $team_member = implode(",", $team_member);
    $userid = implode(",", $userid);
    $get_absensi = $this->model_management_office->get_absensi_join_activity($userid, $team_member, $today);
    $get_activity = $this->model_management_office->get_activity_by_username_and_date($team_member, $today);
    $get_menu = $this->model_management_office->get_menu_by_userid($this->session_id);
    if($get_menu->num_rows() > 0)
    {
     $get_menu = $get_menu; 
    }else{
      $get_menu = $this->model_management_office->get_menu_by_userid(999);
    }


    //get region by map_akses_region
    $get_region = $this->model_management_sales->get_region_by_map_akses_region_nasional($this->session_id);
    if ($get_region->num_rows() > 0) {
    $region = 'all';
    }else{
    $get_region = $this->model_management_sales->get_region_by_map_akses_region($this->session_id);
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

    // jika username login adalah BM, seperti bagio, adi, dan sebagainya
    if ($this->session->userdata('username') == 'bagio' || $this->session->userdata('username') == 'bisman' || $this->session->userdata('username') == 'adhi' || $this->session->userdata('username') == 'rasyid') {
    $get_data = $this->model_management_sales->get_site_code_by_sub_region($region);
    // $count_site_code = count($get_data->result());
    $site_code = "";
    foreach ($get_data->result() as $s) {
        $site_code.= ",'".$s->site_code."'";
    }
    $site_code = preg_replace('/,/', '', $site_code,1);
    }else
    {
    $get_site_code_by_region = $this->model_management_sales->get_site_code_by_region($region);
    // $count_site_code = count($get_site_code_by_region->result());
    $site_code = "";
    foreach ($get_site_code_by_region->result() as $s) {
        $site_code.= ",'".$s->site_code."'";
    }
    $site_code = preg_replace('/,/', '', $site_code,1);
    }

    $get_kalendar = $this->model_management_office->get_kalender_by_bulan($this->tahun, $this->bulan, $site_code);

    $data = [
      'title' => 'Dashboard New',
      'team_member' => $team_member,
      'get_absensi' => $get_absensi,
      'get_activity' => $get_activity,
      'get_menu' => $get_menu,
      'today' => $today,
      'get_kalendar' => $get_kalendar
    ];

    $this->render_multiple(array(
        'management_office/dashboard_new',
      ),
      $data
    );
  }

  public function all_team_member()
  {
    $today = date('Y-m-d');
    $today = '2026-03-17';

    $from = $this->input->get('from');
    $to = $this->input->get('to');

    // echo "from : ".$this->input->get('from');
    // echo "to : ".$this->input->get('to');

    $get_team_member = $this->model_management_office->get_team_member($this->session_id);
    if($get_team_member->num_rows() > 0){
        foreach ($get_team_member->result() as $a) {
          $userid[] = $a->userid;
          $team_member[] = "'".$a->username."'";
        }
    }else{
      redirect('management_office/dashboard');
    }    
    $team_member = implode(",", $team_member);
    $userid = implode(",", $userid);
    $get_absensi = $this->model_management_office->get_absensi_join_activity_from_to($userid, $team_member, $from, $to);
    $get_summary = $this->model_management_office->get_summary_activity_by_username_and_from_to($team_member, $from, $to);

    $data = [
      'title' => 'All Team Member',
      'url' => 'management_office/all_team_member',
      'get_absensi' => $get_absensi,
      'get_summary' => $get_summary
    ];

    $this->render_multiple(array(
        'management_office/all_team_member',
      ),
      $data
    );
  }

  public function detail_activity($userid, $tanggal)
  {
    $data = [
      'title' => 'Detail Absensi & Activity',
      'url' => 'management_office/detail_activity',
    ];

    $this->render_multiple(array(
        'management_office/detail_activity',
      ),
      $data
    );
  }

  public function reports()
  {
    $data = [
      'title' => 'List of Reports',
      'url' => 'management_office/detail_activity',
    ];

    $this->render_multiple(array(
        'management_office/report_lists',
      ),
      $data
    );
  }
    
}
?>
