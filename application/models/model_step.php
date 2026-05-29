<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_step extends CI_Model 
{
  public function get_step_employee()
  {
    $query = "
      select  a.*, b.username, c.username as validate_username, 
              d.username as created_username, e.username as updated_username,
              f.departement, f.divisi, f.jenis_kelamin
      from site.step_employee a left join site.master_user b 
        on a.userid = b.id left join site.master_user c
        on a.validate_by = c.id left join site.master_user d 
        on a.created_by = d.id left join site.master_user e 
        on a.updated_by = e.id left join site.karyawan f 
        on b.username = f.username_web
    ";

    return $this->db->query($query);
  }

  public function insert_step($data)
  {
    // cek dulu apakah sudah ada di tabel, kalau userid dan month sama maka update saja
    $cek = $this->db->get_where('site.step_employee', ['userid' => $data['userid'], 'month' => $data['month']])->row_array();

    if ($cek) {
      $this->db->where('userid', $data['userid']);
      $this->db->where('month', $data['month']);
      $this->db->update('site.step_employee', $data);
      return;
    } else {
      $this->db->insert('site.step_employee', $data);
      return;
    }
  }

  public function get_step_statistics()
  {
    $query = "
      SELECT 
        SUM(steps) as total_steps, 
        AVG(steps) as avg_steps, 
        MAX(steps) as max_steps, 
        COUNT(DISTINCT month) as total_months 
      FROM site.step_employee
    ";

    return $this->db->query($query)->row();
  }

  public function get_step_ranking_by_month()
  {
    $query = "
      SELECT 
        month, 
        SUM(steps) as total_steps 
      FROM site.step_employee 
      GROUP BY month 
      ORDER BY month ASC
    ";

    $result = $this->db->query($query);
    
    $labels = array();
    $values = array();
    
    foreach ($result->result() as $row) {
      $labels[] = $row->month;
      $values[] = (int)$row->total_steps;
    }
    
    return array(
      'labels' => $labels,
      'values' => $values
    );
  }

  public function get_top3_by_month($month = null)
  {
    if ($month == null) {
      $query_month = "
        SELECT DISTINCT month 
        FROM site.step_employee 
        ORDER BY month DESC 
        LIMIT 1
      ";
      $month_result = $this->db->query($query_month)->row();
      $month = isset($month_result->month) ? $month_result->month : date('Y-m');
    }
    
    $query = "
      SELECT 
        b.username,
        a.month,
        SUM(a.steps) as total_steps,              
        a.capture
      FROM site.step_employee a 
      LEFT JOIN site.master_user b ON a.userid = b.id
      WHERE a.month = '$month'
      GROUP BY b.username, a.month
      ORDER BY total_steps DESC
      LIMIT 3
    ";

    return $this->db->query($query);
  }

  public function get_top3_divisi_by_average($month = null)
  {
    if ($month == null) {
      $query_month = "
        SELECT DISTINCT month 
        FROM site.step_employee 
        ORDER BY month DESC 
        LIMIT 1
      ";
      $month_result = $this->db->query($query_month)->row();
      $month = isset($month_result->month) ? $month_result->month : date('Y-m');
    }

    $query = "
      SELECT 
        f.divisi,
        AVG(a.steps) as avg_steps,
        COUNT(DISTINCT a.userid) as total_member,
        SUM(a.steps) as total_steps
      FROM site.step_employee a 
      LEFT JOIN site.master_user b ON a.userid = b.id
      LEFT JOIN site.karyawan f ON b.username = f.username_web
      WHERE f.divisi IS NOT NULL AND f.divisi != ''
        AND a.month = '$month'
      GROUP BY f.divisi
      ORDER BY avg_steps DESC
      LIMIT 3
    ";

    return $this->db->query($query);
  }

  public function get_karyawan_by_username($username)
  {
    $query = "
      select *
      from site.karyawan a 
      where a.username_web = '$username'
    ";
    return $this->db->query($query);
  }

}