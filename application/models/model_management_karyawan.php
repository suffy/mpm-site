<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model_management_karyawan extends CI_Model {

  public function get_site_code($username) 
  {
      $query = "
          select a.site_code
          from site.karyawan a 
          WHERE a.username_web = '$username'
      ";

      // $query = "
      //     select b.site_code, b.branch_name, b.sub
      //     from mpm.user a 
      //     left join mpm.tbl_tabcomp b 
      //     on a.company_site_code = b.site_code
      //     WHERE a.id = $userid
      // ";
      
      $this->db->query($query);
      // echo $this->db->last_query(); die;
      return $this->db->query($query);
  }
    
  public function get_username($username) 
  {
      if ($username == 'ratri' || $username == 'millax') {
          $params_username = "";
      }else {
          $params_username = " and username = '$username' ";
      }

      $query = "
          SELECT * 
          from mpm.user a 
          where active = '1' $params_username and (kode_lang is null or kode_lang = '')
      ";
      
      $this->db->query($query);
      // echo $this->db->last_query(); die;
      return $this->db->query($query)->result();
  }

  public function get_sub($site_code)
  {
      $query = "
          SELECT a.sub
          from mpm.tbl_tabcomp a 
          where site_code = '$site_code' 
      ";
      return $this->db->query($query);
  }

  public function get_sub_company($site_code) 
  {
      // echo "<!-- Site Code di model: $site_code -->";die;
      $query = "
          SELECT a.sub
          from mpm.tbl_tabcomp a 
          where site_code = '$site_code'
      ";
      return $this->db->query($query);

  }

  public function get_all_karyawan($sub = '', $username, $form_search) 
  {
      // 1. Prioritaskan form_search jika ada isinya
      if (!empty($form_search)) {
          $sub = $form_search;
      }

      $params_sub = ""; // Default kosong (untuk Ratri/Milla agar bisa lihat semua)

      // 2. Logika Filter
      if ($username == 'ratri' || $username == 'millax') {
          // Jika Ratri/Milla login:
          // Cukup cek apakah dia lagi pakai form_search atau tidak
          if ($form_search != null) {
              $params_sub = " and b.sub = '$sub' ";
          } 
          // Jika $sub kosong, $params_sub tetap "" (maka query akan ambil semua data)
      } else {
          // Jika USER BIASA login:
          if ($sub != '') {
              // User biasa filter berdasarkan pencarian
              $params_sub = " and b.sub = '$sub' ";
          } else {
              // User biasa filter berdasarkan username (site_code) jika pencarian kosong
              $params_sub = " and LEFT(a.site_code, 3) = '$username' ";
          }
      }

      $query = "
          SELECT a.*, b.branch_name, b.nama_comp
          FROM site.karyawan a
          LEFT JOIN mpm.tbl_tabcomp b ON a.site_code = b.site_code
          where b.active = 1 $params_sub and a.deleted_at is null
      ";

      // Debugging
      // echo '<pre>' . $query . '</pre>';die;
      return $this->db->query($query);
  }

  public function get_karyawan_by_signature($signature) 
  {
      $query = "
          SELECT a.*, c.nama_comp, c.branch_name, b.id as id_pendidikan, b.pendidikan_terakhir, b.institusi_pendidikan, b.jurusan
          FROM site.karyawan a
          left join site.m_pendidikan b
          on a.id = b.id_karyawan
          LEFT JOIN mpm.tbl_tabcomp c 
          ON a.site_code = c.site_code
          WHERE a.signature = '$signature'
      ";
      // echo '<pre>';
      // echo $query;
      // echo '</pre>';
      // die;
      $result = $this->db->query($query);
      // echo $this->db->last_query(); die;
      
      if ($result->num_rows() > 0) {
          return $result->row();
      } else {
          return false;
      }
  }

    public function get_pendidikan_by_karyawan_id($id_karyawan) {
        $query = "
            SELECT *
            FROM site.m_pendidikan
            WHERE id_karyawan = $id_karyawan and deleted is null
        ";

        $result = $this->db->query($query);
        
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function get_keluarga_by_karyawan_id($id_karyawan) {
        $query = "
            SELECT *
            FROM site.m_keluarga
            WHERE id_karyawan = $id_karyawan and deleted is null
        ";

        $result = $this->db->query($query);
        
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function get_asuransi_by_karyawan_id($id_karyawan) {
        $query = "
            SELECT *
            FROM site.m_asuransi
            WHERE id_karyawan = $id_karyawan and deleted is null
        ";

        $result = $this->db->query($query);
        
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function insert_karyawan($data) 
    {
        $this->db->insert('site.karyawan', $data);
        return $this->db->insert_id();
    }

    public function update_karyawan($signature, $data_karyawan) 
    {
        $this->db->where('signature', $signature);
        return $this->db->update('site.karyawan', $data_karyawan);
    }

    public function softDelete_m_pendidikan($id) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_pendidikan', ['deleted' => 1]);
    }

    public function update_m_pendidikan($id, $data) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_pendidikan', $data);
    }

    public function insert_m_pendidikan($data) 
    {
        return $this->db->insert('site.m_pendidikan', $data);
    }
    

    public function softDelete_m_keluarga($id) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_keluarga', ['deleted' => 1]);
    }

    public function update_m_keluarga($id, $data) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_keluarga', $data);
    }

    public function insert_m_keluarga($data) 
    {
        return $this->db->insert('site.m_keluarga', $data);
    }

    public function softDelete_m_asuransi($id) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_asuransi', ['deleted' => 1]);
    }

    public function update_m_asuransi($id, $data) 
    {
        $this->db->where('id', $id);
        return $this->db->update('site.m_asuransi', $data);
    }

    public function insert_m_asuransi($data) 
    {
        return $this->db->insert('site.m_asuransi', $data);
    }
    
    public function get_dp_by_perusahaan($sub)
    {
        $query = "
            SELECT *
            FROM mpm.tbl_tabcomp a
            WHERE a.sub = '$sub' AND active = 1
        ";
        
        return $this->db->query($query)->result();
    }

    public function check_ktp_exists($ktp) 
    {
        // Menggunakan query SQL manual
        $sql = "
            SELECT nomor_ktp 
            FROM site.karyawan 
            WHERE nomor_ktp = '$ktp' and nomor_ktp is not null and nomor_ktp != ''
            LIMIT 1";
        
        // Eksekusi query dengan binding (?) agar aman dari SQL Injection
        $query = $this->db->query($sql, array($ktp));

        if ($query->num_rows() > 0) {
            return true; // Data ditemukan (duplikat)
        } else {
            return false; // Data belum ada
        }
    }

    public function get_data_by_id($id) {
        $query = "
            select *
            from site.karyawan a 
            left join site.m_pendidikan b 
            on a.id = b.id_karyawan and b.deleted is null
            left join site.m_keluarga c 
            on a.id = c.id_karyawan and c.deleted is null
            and a.email_perusahaan is not null and a.tanggal_mulai_kerja is not null
            and b.id_karyawan is not null and c.id_karyawan is not null
            WHERE a.id = '$id' 
            ";

        // $query = "
        //     select *
        //     from site.karyawan a 
        //     WHERE a.id = '$id' and a.email_perusahaan is not null 
        // ";

        // echo '<pre>';
        // echo $query;
        // echo '</pre>';
        // die;

        return $this->db->query($query);
    }

    public function update_karyawan_by_id($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('site.karyawan', $data);
    }

    public function get_atasan($username) 
    {
        $query = "
            SELECT a.userid_pelaksana, a.userid_verifikasi1, b.name as name_pelaksana, c.name as name_verifikasi1
            FROM management_rpd.m_karyawan a
            LEFT JOIN (
                        SELECT a.id, a.username, a.active, a.name
                        FROM mpm.`user` a
            )b ON a.userid_pelaksana = b.id
            LEFT JOIN (
                        SELECT a.id, a.username, a.active, a.name 
                        FROM mpm.`user` a
            )c ON a.userid_verifikasi1 = c.id
            WHERE b.username in ('$username')
        ";
        
        $this->db->query($query);
        // echo $this->db->last_query(); die;
        return $this->db->query($query);
    }

    public function get_username_by_id_karyawan($id_karyawan) 
    {
        $query = "
            SELECT username_web
            FROM site.karyawan 
            WHERE id = $id_karyawan
        ";
        
        $this->db->query($query);
        // echo $this->db->last_query(); die;
        return $this->db->query($query);
    }
}