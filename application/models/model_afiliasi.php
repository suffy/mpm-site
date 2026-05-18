<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_afiliasi extends CI_Model 
{
  public function get_activity_by_pelaksana_jabatan_month($userid, $id_jabatan, $month)
  {
    // Membuat dinamis untuk 20 hari
    $day_columns = '';
    for ($i = 1; $i <= 31; $i++) {
        $day_columns .= "max(case when a.day_number = $i then 1 else 0 end) as day_$i";
        if ($i < 31) {
            $day_columns .= ", ";
        }
    }
    
    $query = "
        select  a.id, 
                a.nama_activity, 
                a.pelaksana, 
                a.alat_kerja,
                a.frekuensi,
                b.deleted_at,
                b.day_1, b.day_2, b.day_3, b.day_4, b.day_5,
                b.day_6, b.day_7, b.day_8, b.day_9, b.day_10,
                b.day_11, b.day_12, b.day_13, b.day_14, b.day_15,
                b.day_16, b.day_17, b.day_18, b.day_19, b.day_20,
                b.day_21, b.day_22, b.day_23, b.day_24, b.day_25,
                b.day_26, b.day_27, b.day_28, b.day_29, b.day_30, b.day_31
        from site.afiliasi_master_activity a left join (
            select 
                id_activity, deleted_at,
                $day_columns
            from site.afiliasi_activity a
            where id_web = $userid AND month = '$month' and deleted_at IS NULL
            group by id_activity
        ) b ON a.id = b.id_activity
        where find_in_set($id_jabatan, a.pelaksana) > 0
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    return $this->db->query($query);
  }

  public function get_total_hari($month)
  {
      // tanggal hari ini
      $date = date('Y-m-d');
      $month_now = date('Y-m');
    // $month_now = '2026-02';
    //   echo "month : ".$month."<br>";
    //   echo "month_now : ".$month_now."<br>";
      $total_hari = date('t', strtotime($month . '-01'));
      
      if($month_now == $month)
      {
          $total_hari = date('d');
      }
    //   echo "total_hari : ".$total_hari;       
      return $total_hari;
  }

  public function get_master_karyawan_by_nama($nama)
  {
    // $query = "
    //     select a.id, a.nama, a.id_jabatan
    //     from site.afiliasi_master_karyawan a 
    //     where a.nama = '$nama'
    // ";

    $query = "
      select a.id, a.nama, a.id_jabatan, b.nama_jabatan, b.id_divisi, c.nama_divisi
      from site.afiliasi_master_karyawan a left join site.afiliasi_master_jabatan b 
        on a.id_jabatan = b.id left join site.afiliasi_master_divisi c 
        on b.id_divisi = c.id
      where a.nama = '$nama'
    ";
    
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";

    return $this->db->query($query);

  }

  public function get_activity_plan_by_date_for_calendar($date, $id_karyawan)
  {
      $query = "
          SELECT 
              ap.id,
              ap.activity_id,
              ap.keterangan,
              ap.created_at,
              ma.nama_activity as title,
              mk.nama,
              md.nama as nama_divisi,
              mj.nama as nama_jabatan
          FROM site.afiliasi_activity_plan ap
          JOIN site.afiliasi_master_activity ma ON ma.id = ap.activity_id AND ma.deleted_at IS NULL
          JOIN site.master_karyawan mk ON mk.id = ap.created_by
          JOIN site.master_divisi md ON md.id = mk.id_divisi
          JOIN site.master_jabatan mj ON mj.id = mk.id_jabatan
          WHERE ap.deleted_at IS NULL 
              AND ap.date = '$date'
              AND ap.created_by = $id_karyawan
          ORDER BY ap.created_at DESC
      ";
      
      return $this->db->query($query)->result_array();
  }

  public function insert_to_table($table, $data)
  {
      $this->db->insert($table, $data);
      return $this->db->insert_id();
  }

  public function get_activity_by_month($month)
  {
      $query = "
          select * 
          from site.afiliasi_activity a 
          where a.month = '$month'
      ";
      return $this->db->query($query);
  }

  public function get_activity_by_userid_id_activity_month($userid, $id_activity, $month, $day_number)
  {
      $query = "
          select *
          from site.afiliasi_activity a 
          where a.id_web = $userid and 
          a.id_activity = $id_activity and 
          a.`month` = '$month' and 
          a.day_number = $day_number
      ";
    //   echo "<pre>";
    //   print_r($query);
    //   echo "</pre>";die;
      return $this->db->query($query);
  }

  public function get_report_activity_by_month($month)
  {
      // Get 3 bulan terakhir termasuk bulan saat ini
      $month_before = date('Y-m', strtotime('-1 month', strtotime($month)));
      $month_before2 = date('Y-m', strtotime('-2 month', strtotime($month)));

      $query = "
          select 
              a.id_activity,
              b.nama_activity,
              b.alat_kerja,
              b.frekuensi,
              c.username,
              d.nama_divisi,
              d.nama_jabatan,
              -- Count untuk bulan 1 (terlama)
              SUM(CASE WHEN a.month = '$month_before2' THEN 1 ELSE 0 END) as count_month1,
              -- Count untuk bulan 2 (tengah)
              SUM(CASE WHEN a.month = '$month_before' THEN 1 ELSE 0 END) as count_month2,
              -- Count untuk bulan 3 (terbaru)
              SUM(CASE WHEN a.month = '$month' THEN 1 ELSE 0 END) as count_month3,
              -- Total count
              COUNT(a.id) as total_count
          from site.afiliasi_activity a 
          left join site.afiliasi_master_activity b ON a.id_activity = b.id 
          left join site.master_user c ON a.id_web = c.id 
          left join (
              select 
                  a.nama,
                  a.id_jabatan,
                  b.nama_jabatan,
                  c.nama_divisi
              from site.afiliasi_master_karyawan a 
              left join site.afiliasi_master_jabatan b ON a.id_jabatan = b.id 
              left join site.afiliasi_master_divisi c ON b.id_divisi = c.id
          ) d ON c.username = d.nama
          where a.month in ('$month', '$month_before', '$month_before2')
              and a.deleted_at IS NULL
          group by a.id_activity, c.username
          order by d.nama_divisi, d.nama_jabatan, c.username, b.nama_activity
      ";

      return $this->db->query($query);
  }
    
  public function get_frekuensi_in_activity()
  {
      $query = "
          select frekuensi
          from site.afiliasi_master_activity
          group by frekuensi
      ";
      return $this->db->query($query);
  }

  public function get_activity_harian_is_null($userid, $month, $day_number)
  {
      $query = "
          select a.id, a.nama_activity, a.pelaksana, a.alat_kerja, a.frekuensi, b.id_activity
          from site.afiliasi_master_activity a  left join (
              select	id_activity
              from site.afiliasi_activity a
              where id_web = $userid AND month = '$month' and a.day_number = $day_number and a.deleted_at IS NULL
              group by id_activity
          )b on a.id = b.id_activity
          where find_in_set(1, a.pelaksana) > 0 and a.frekuensi = 'harian' and b.id_activity is null 
      ";
    //   echo "<pre>"; print_r($query); echo "</pre>";
      return $this->db->query($query);
  }

  public function get_activity_mingguan_is_null($userid, $month, $week_number)
  {
      $query = "
        select a.id, a.nama_activity, a.pelaksana, a.alat_kerja, a.frekuensi, b.id_activity
        from site.afiliasi_master_activity a  left join (
            select	id_activity
            from site.afiliasi_activity a
            where id_web = $userid and month = '$month' and a.week_number = $week_number and a.deleted_at IS NULL
            group by id_activity
        )b on a.id = b.id_activity
        where find_in_set(1, a.pelaksana) > 0 and a.frekuensi = 'mingguan' and b.id_activity is null 
      ";
      echo "<pre>"; print_r($query); echo "</pre>";
      return $this->db->query($query);
  }

  public function get_activity_not_bulanan_harian($userid, $month)
  {
      $query = "
        select a.id, a.nama_activity, a.pelaksana, a.alat_kerja, a.frekuensi, b.id_activity
        from site.afiliasi_master_activity a  left join (
            select	id_activity
            from site.afiliasi_activity a
            where id_web = $userid and month = '$month' and a.deleted_at IS NULL
            group by id_activity
        )b on a.id = b.id_activity
        where find_in_set(1, a.pelaksana) > 0 and a.frekuensi not in ('harian','bulanan') and b.id_activity is null 
      ";
      // echo "<pre>"; print_r($query); echo "</pre>";
      return $this->db->query($query);
  }

  public function get_activity_bulanan_is_null($userid, $month)
  {
    $query = "
      select a.id, a.nama_activity, a.pelaksana, a.alat_kerja, a.frekuensi, b.id_activity
      from site.afiliasi_master_activity a  left join (
        select	id_activity
        from site.afiliasi_activity a
        where id_web = $userid and month = '$month' and a.deleted_at IS NULL
        group by id_activity
      )b on a.id = b.id_activity
      where find_in_set(1, a.pelaksana) > 0 and a.frekuensi = 'bulanan' and b.id_activity is null
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query);
  }

  public function get_current_week()
  {
      date_default_timezone_set('Asia/Jakarta');
      $first_day_week = date('W', strtotime(date('Y-m-01')));
      $current_week = date('W');
      $week_in_month = $current_week - $first_day_week + 1;

      // echo "<hr>";
      // echo "first_day_week : " . $first_day_week . "<br>";
      // echo "current_week : " . $current_week . "<br>";
      // echo "week_in_month : " . $week_in_month . "<br>";

      return $week_in_month;

  }

  public function get_activity_plan_by_date($date)
  {
    $query = "
      select  a.id, a.id_karyawan, a.id_activity, a.date, a.title, a.keterangan, a.created_at, a.created_by, 
              b.nama, b.nama_divisi, b.nama_jabatan
      from site.afiliasi_activity_plan a left join (
        select a.id, a.nama, a.id_jabatan, b.nama_jabatan, b.id_divisi, c.nama_divisi
        from site.afiliasi_master_karyawan a left join site.afiliasi_master_jabatan b 
          on a.id_jabatan = b.id left join site.afiliasi_master_divisi c 
          on b.id_divisi = c.id
      )b on a.id_karyawan = b.id
      WHERE a.date = '$date' and a.deleted_at is null
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query)->result_array();
  }

  public function get_activity_plan_group_by_month($month)
  {
    $query = "
      select  a.date, count(*) as count
      from site.afiliasi_activity_plan a
      WHERE a.deleted_at is null and month(a.date) = $month
      GROUP BY a.date
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query)->result_array();
  }


  public function get_activity_plan_by_month($year, $month)
  {
  $query = "
      select 	a.id_activity, 
              b.nama, b.nama_divisi, b.nama_jabatan,
              a.date, a.title, a.keterangan, a.created_at
      from site.afiliasi_activity_plan a left join (
      select a.id, a.nama, a.id_jabatan, b.nama_jabatan, b.id_divisi, c.nama_divisi
      from site.afiliasi_master_karyawan a left join site.afiliasi_master_jabatan b 
          on a.id_jabatan = b.id left join site.afiliasi_master_divisi c 
          on b.id_divisi = c.id
      )b on a.id_karyawan = b.id 
      where year(a.date) = $year and month(a.date) = $month and a.deleted_at is null
  ";
  return $this->db->query($query);
  }

  public function get_activity_plan_by_id($id)
  {
    $query = "select * from site.afiliasi_activity_plan a WHERE a.id = $id";
    return $this->db->query($query);
  }

  public function update_to_table($table, $data, $id) {
    $this->db->where('id', $id);
    $this->db->update($table, $data);
    return $this->db->affected_rows();
  }

  public function import_monthly_planning($filename, $created_at, $created_by)
  {

    // echo "created_at : ".$created_at;
    // echo "created_by : ".$created_by;

    // die;

    $this->load->library('excel');
    $object = PHPExcel_IOFactory::load("assets/uploads/afiliasi/$filename");

    $jumlahSheet = $object->getSheetCount();
    if ($jumlahSheet > 1) {
        echo "jumlah_sheet : ".$jumlahSheet;
        echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
        redirect('management_claim/form_dp/'.$signature_program,'refresh');
    }
    else{
      foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 25000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 25000 ROW.");
                    redirect('management_claim/form_dp/'.$signature_program);
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('management_claim/form_dp/'.$signature_program);
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $id_activity    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $keterangan     = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tanggal        = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                    $unix_date        = ($tanggal - 25569) * 86400;
                    $excel_date       = 25569 + ($unix_date / 86400);
                    $unix_date        = ($excel_date - 25569) * 86400;
                    $tgl_sales_final  = gmdate("Y-m-d", $unix_date);

                    echo "id_activity : ".$id_activity;
                    echo "<br>";
                    echo "keterangan : ".$keterangan;
                    echo "<br>";
                    echo "tanggal : ".$tanggal;
                    echo "<br>";
                    echo "unix_date : ".$unix_date;
                    echo "<br>";
                    echo "excel_date : ".$excel_date;
                    echo "<br>";
                    echo "tgl_sales_final : ".$tgl_sales_final;
                    echo "<br>";
                    echo "<br>";

                    $data = [
                        'id_activity'   => $id_activity,
                        'keterangan'    => $keterangan,
                        'tanggal'       => $tgl_sales_final,
                        'created_at'    => $this->created_at,
                        'created_by'    => $this->created_by
                    ];

                    $this->db->insert('site.afiliasi_import_temp', $data);
                    
                    // $validasi_row = $validasi_kodeprod + $validasi_nomor_surat + $validasi_site_code + $validasi_class;                    
                    // $signature = 'ajuan-import-'.rand().md5($this->created_at.rand());

                    // $data = [
                    //     'nomor_surat_program'      => $nomor_surat_program,
                    // ];
                    // $this->db->insert('management_claim.import_bonus_barang',$data);
                }

                // echo "cccc";
                // die;
            }
    }
  }

    public function get_activity_by_pelaksana_jabatan($id_jabatan)
    {
      // echo "id_jabatan : ".$id_jabatan;
      $query = "
          select  a.id, 
                  a.nama_activity, 
                  a.pelaksana
          from site.afiliasi_master_activity a
          where FIND_IN_SET($id_jabatan, a.pelaksana) > 0
      ";
      // echo "<pre>"; print_r($query); echo "</pre";
      return $this->db->query($query)->result();
    }


    public function get_activity_by_id($id)
    {
        $query = "
            SELECT  a.id, 
                    a.nama_activity, 
                    a.pelaksana, 
                    a.alat_kerja,
                    a.frekuensi
            FROM site.afiliasi_master_activity a
            WHERE a.id = $id
        ";

        return $this->db->query($query)->row();
    }

  public function get_activity_plan_by_id_karyawan_month($id_jabatan, $id_karyawan, $month)
  {
    $query = "
        select a.id, a.nama_activity
        from site.afiliasi_master_activity a
        left join site.afiliasi_activity_plan b on (
            a.id = b.id_activity 
            and b.id_karyawan = $id_karyawan
            and DATE(b.date) = '$month'
            and (b.deleted_at IS NULL or b.deleted_at = '0000-00-00 00:00:00')
        )
        where FIND_IN_SET($id_jabatan, a.pelaksana) and b.id IS NULL
    ";
    echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query)->result_array();
  }

  public function get_master_activity_not_in_activity_plan_bulanan($id_jabatan, $id_karyawan, $month)
  {  
    $query = "
      select a.id, a.nama_activity, a.frekuensi
      from site.afiliasi_master_activity a
      left join site.afiliasi_activity_plan b on (
        a.id = b.id_activity 
        and b.id_karyawan = $id_karyawan
        and month(b.date) = $month
        and (b.deleted_at IS NULL or b.deleted_at = '0000-00-00 00:00:00')
      )
      where FIND_IN_SET($id_jabatan, a.pelaksana) and b.id IS NULL and a.frekuensi = 'bulanan'
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query)->result_array();
  }

  public function get_master_activity_not_in_activity_plan_harian($id_jabatan, $id_karyawan, $date)
  {  
    $query = "
      select a.id, a.nama_activity, a.frekuensi
      from site.afiliasi_master_activity a
      left join site.afiliasi_activity_plan b on (
        a.id = b.id_activity 
        and b.id_karyawan = $id_karyawan
        and date(b.date) = '$date'
        and (b.deleted_at IS NULL or b.deleted_at = '0000-00-00 00:00:00')
      )
      where FIND_IN_SET($id_jabatan, a.pelaksana) and b.id IS NULL and a.frekuensi = 'harian'
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query)->result_array();
  }

  public function get_master_activity_not_in_activity_plan_not_harian_bulanan($id_jabatan, $id_karyawan, $month)
  {  
    $query = "
      select a.id, a.nama_activity, a.frekuensi
      from site.afiliasi_master_activity a
      left join site.afiliasi_activity_plan b on (
        a.id = b.id_activity 
        and b.id_karyawan = $id_karyawan
        and month(b.date) = $month
        and (b.deleted_at IS NULL or b.deleted_at = '0000-00-00 00:00:00')
      )
      where FIND_IN_SET($id_jabatan, a.pelaksana) and b.id IS NULL and a.frekuensi not in ('harian','bulanan')
    ";
    // echo "<pre>"; print_r($query); echo "</pre>";
    return $this->db->query($query)->result_array();
  }

    public function get_id_activity_by_pelaksana_jabatan($id_jabatan, $id_activity)
    {
        $query = "
            SELECT  a.id, 
                    a.nama_activity, 
                    a.pelaksana
            FROM site.afiliasi_master_activity a
            WHERE FIND_IN_SET($id_jabatan, a.pelaksana) > 0 and a.id = $id_activity
        ";

        // echo "<pre>"; print_r($query); echo "</pre>";
        return $this->db->query($query);
    }

    public function get_activity_by_ids_and_jabatan($ids, $id_jabatan)
    {
        if (empty($ids)) {
            return [];
        }

        // Amankan ID supaya tidak injection
        $ids = array_map('intval', $ids);
        $id_list = implode(',', $ids);
        $id_jabatan = intval($id_jabatan);

        $query = "
            SELECT id, nama_activity, pelaksana
            FROM site.afiliasi_master_activity
            WHERE id IN ($id_list)
            AND FIND_IN_SET($id_jabatan, pelaksana) > 0
        ";
        // echo "<pre>"; print_r($query); echo "</pre>";die;
        return $this->db->query($query)->result_array();
    }

    public function get_import_temp_by_signature($signature)
    {
        $query = "
            SELECT *
            FROM site.afiliasi_import_temp
            WHERE signature = '$signature'
            order by date ASC
        ";
        // echo "<pre>"; print_r($query); echo "</pre>";die;
        return $this->db->query($query);
    }   

    public function get_summary_monthly_planning_temp($signature)
    {
        $query = "
            SELECT 
                COUNT(*) as total_row,
                SUM(CASE WHEN is_valid_activity = 1 THEN 1 ELSE 0 END) as valid_activity,
                SUM(CASE WHEN is_valid_activity = 0 THEN 1 ELSE 0 END) as invalid_activity
            FROM site.afiliasi_import_temp
            WHERE signature = '$signature'
        ";

        return $this->db->query($query);
    }

    public function get_invalid_monthly_planning($signature)
    {
        $query = "
            SELECT id
            FROM site.afiliasi_import_temp
            WHERE signature = '$signature'
            AND (is_valid_activity = 0 OR is_valid_tanggal = 0)
        ";

        return $this->db->query($query);
    }

    // public function check_invalid_by_signature($signature)
    // {
    //     $query = "
    //         SELECT id
    //         FROM site.afiliasi_import_temp
    //         WHERE signature = '$signature'
    //         AND is_valid_activity = 0
    //     ";

    //     return $this->db->query($query, [$signature]);
    // }

    public function insert_monthly_planning_batch($data, $signature)
    {
        $this->db->trans_start();

        // Insert batch
        if (!empty($data)) {
            $this->db->insert_batch('site.afiliasi_activity_plan', $data);
        }

        // Delete temp berdasarkan signature
//         $this->db->delete('site.afiliasi_import_temp', [
//  'signature' => $signature
//         ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function delete_activity($user_id, $id_activity, $month, $day_number)
    {
        
        $query = "
            UPDATE site.afiliasi_activity
            SET deleted_at = NOW(),
                deleted_by = $user_id
            WHERE id_web = $user_id
            AND id_activity = $id_activity
            AND month = '$month'
            AND day_number = $day_number
            AND deleted_at IS NULL
        ";
        return $this->db->query($query);
    }

    public function get_activity_by_userid_month($user_id, $month)
    {
        $query = "
            select * 
            from site.afiliasi_activity a 
            where a.month = '$month' and a.id_web = $user_id
        ";
        return $this->db->query($query);
    }

    public function restore_activity($user_id, $id_activity, $month, $day_number)
    {
        $query = "
            UPDATE site.afiliasi_activity
            SET deleted_at = NULL,
                deleted_by = NULL
            WHERE id_web = $user_id
            AND id_activity = $id_activity
            AND month = '$month'
            AND day_number = $day_number
            AND deleted_at IS NOT NULL
        ";

        return $this->db->query($query);
    }

    public function get_activity_by_date($userid, $month)
    {
        $query = "
            select c.nama, c.nama_jabatan, b.nama_activity, b.alat_kerja, b.frekuensi, CONCAT(a.month, '-', LPAD(a.day_number, 2, '0')) AS date, a.created_at
            from site.afiliasi_activity a 
            left join (
                select a.id, a.nama_activity, a.frekuensi, a.alat_kerja
                from site.afiliasi_master_activity a
            )b on a.id_activity = b.id 
            left join (
                select a.id, a.nama, a.id_jabatan, b.nama_jabatan
                from site.afiliasi_master_karyawan a left join site.afiliasi_master_jabatan b 
                on a.id_jabatan = b.id
            )c on a.id_karyawan = c.id
            WHERE a.deleted_at is null and a.id_web = $userid and month = '$month'
        ";

        // echo "<pre>"; print_r($query); echo "</pre>";
        // die;
        return $this->db->query($query);
    }

  public function get_master_activity()
  {
    $query = "
      select a.id, a.nama_activity, a.pelaksana, a.alat_kerja, a.frekuensi
      from site.afiliasi_master_activity a 
      where a.deleted_at is null
    ";
    return $this->db->query($query);
  }


}