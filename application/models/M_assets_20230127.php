<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_assets extends CI_Model
{
    public function my_asset(){
        $id = $this->session->userdata('id');
        $sql = "
                SELECT a.id, a.no_po, a.no_pr, b.tgl_pengiriman, a.namabarang, b.userid_penerima, c.username, c.email
                from 
                (
                    select a.id, a.namabarang, a.no_po, a.no_pr
                    from mpm.asset a
                )a
                INNER JOIN
                (
                    select a.*
                    from site.t_asset_penyerahan_asset a
                    where a.flag = 1
                ) b on a.no_po = b.no_po
                left join
                (
                    select id, username, email
                    from mpm.`user`
                )c on b.userid_penerima = c.id
                where b.userid_penerima = $id
                order by b.tgl_pengiriman desc
            ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function konfirmasi_asset(){
        $id = $this->session->userdata('id');
        $sql = "
            SELECT a.*, c.id as id_mutasi, c.tgl_mutasi, c.userid, c.bukti_upload, c.bukti_upload2, c.status
            from
            (
                select *
                from mpm.asset a
                where a.deleted = 0
            )a
            LEFT JOIN
            (
                select a.id, a.id_asset, a.userid, a.tgl_mutasi, a.bukti_upload, a.bukti_upload2, a.status
                from mpm.asset_mutasi a
                where a.status = 1
            ) c on a.id = c.id_asset
            where c.userid = $id
                ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0)
        {
            return $hasil->result();
        } else {
            return array();
        }
        //  echo "<pre><br><br><br><br><br>";
        //                     print_r($sql);
        //                     echo "</pre>";
    }

    // =================================== purchase assets =========================================

    public function data_purchase_asset(){
        $sql = "
                SELECT a.id, a.no_pr, a.no_po, a. nama_toko, a.alamat, a.telp, a.tgl_po, a.upload_req, a.fax, a.attn, b.id as id_barang, b.nama_barang, b.tipe, b.sub_harga, b.sub_tax, b.total, b.created_at, c.username
                FROM
                    (
                        SELECT a.id, a.no_pr, a.no_po, a.nama_toko, a.alamat, a.upload_req, a.tgl_po, a.telp, a.user_req, a.fax, a.attn
                        FROM site.t_asset_purchase_asset a
                    )a
                    INNER JOIN
                    (	SELECT id, no_po, nama_barang, tipe, sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total, created_at
                        FROM site.t_asset_purchase_asset_detail
                        GROUP BY no_po
                    )b on a.no_po = b.no_po
                    LEFT JOIN
                    (
                        SELECT id, username
                        FROM mpm.user
                    )c on a.user_req = c.id
                ORDER BY a.id desc
            ";

            // echo "<pre><br><br><br><br>";
            // print_r($sql);
            // echo "</pre>";

        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0)
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function get_pr($id = ''){
        if ($id != '') {
            $where = "where a.id = '$id' or a.no_po = '$id' or concat(a.no_po,'-',a.id_barang) = '$id'";
        } else {
            $where = "";
        }

        $sql = "
                SELECT a.*, b.username_penerima
                FROM
                (
                    SELECT a.id, a.no_pr, a.no_po, a. nama_toko, a.alamat, a.telp, a.tgl_po, a.upload_req, a.fax, a.attn, b.id as id_barang, b.nama_barang, b.tipe, b.sub_harga, b.sub_tax, b.total, b.created_at, c.username
                    FROM
                    (
                            SELECT a.id, a.no_pr, a.no_po, a.nama_toko, a.alamat, a.upload_req, a.tgl_po, a.telp, a.user_req, a.fax, a.attn
                            FROM site.t_asset_purchase_asset a
                    )a
                    INNER JOIN
                    (	
                            SELECT id, no_po, nama_barang, tipe, sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total, created_at
                            FROM site.t_asset_purchase_asset_detail
                            GROUP BY no_po, id
                    )b on a.no_po = b.no_po
                    LEFT JOIN
                    (
                            SELECT id, username
                            FROM mpm.user
                    )c on a.user_req = c.id
                )a 
                LEFT JOIN
                (
                    SELECT a.*, b.username as username_penerima
                    FROM
                    (
                        SELECT * 
                        FROM site.t_asset_penyerahan_asset
                    )a
                    LEFT JOIN
                    (
                            SELECT id, username
                            FROM mpm.user
                    )b on a.userid_penerima = b.id
                )b on concat (a.no_po,'-',a.id_barang) = b.no_po
                    
                $where
                ORDER BY a.id = 15 desc, a.id desc
                
            ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0)
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function get_pr_asset($id = ''){

        $sql = "
                SELECT a.*, b.username_penerima
                FROM
                (
                    SELECT a.id, a.no_pr, a.no_po, a. nama_toko, a.alamat, a.telp, a.tgl_po, a.upload_req, a.fax, a.attn, b.id as id_barang, b.nama_barang, b.tipe, b.sub_harga, b.sub_tax, b.total, b.created_at, c.username
                    FROM
                    (
                            SELECT a.id, a.no_pr, a.no_po, a.nama_toko, a.alamat, a.upload_req, a.tgl_po, a.telp, a.user_req, a.fax, a.attn
                            FROM site.t_asset_purchase_asset a
                    )a
                    INNER JOIN
                    (	
                            SELECT id, no_po, nama_barang, tipe, sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total, created_at
                            FROM site.t_asset_purchase_asset_detail
                            GROUP BY no_po, id
                    )b on a.no_po = b.no_po
                    LEFT JOIN
                    (
                            SELECT id, username
                            FROM mpm.user
                    )c on a.user_req = c.id
                )a 
                LEFT JOIN
                (
                    SELECT a.*, b.username as username_penerima
                    FROM
                    (
                        SELECT * 
                        FROM site.t_asset_penyerahan_asset
                    )a
                    LEFT JOIN
                    (
                            SELECT id, username
                            FROM mpm.user
                    )b on a.userid_penerima = b.id
                )b on concat (a.no_po,'-',a.id_barang) = b.no_po          
                WHERE concat (a.no_po,'-',a.id_barang) not in (SELECT a.no_po from mpm.asset a
                WHERE a. no_po is not null and no_po not in ('Hanya Mutasi'))
                and a.no_po not in (SELECT a.no_po from mpm.asset a
                WHERE a. no_po is not null and no_po not in ('Hanya Mutasi'))
                ORDER BY a.id = 15 desc, a.id desc
                
            ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0)
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function showbarang(){
        $id=$this->session->userdata('id');
        $this->db->select('*');
        $this->db->where('created_by', $id);
        $hasil = $this->db->get('site.t_asset_purchase_asset_temp');
        if ($hasil->num_rows() > 0)
        {
            return $hasil->result();
        } else {
            return array();
        }

    }

    public function simpan($table,$data) {
        $this->db->insert($table,$data);
    }

    public function simpan_asset_detail($data) {
        // echo 'a'; die;
        $created_by = $data['created_by'];
        $created_at = $data['created_at'];
        $no_po = $data['no_po'];
        $sql =  "
                INSERT INTO site.t_asset_purchase_asset_detail
                SELECT '' as id,'$no_po', nama_barang, tipe, jumlah, harga,(jumlah*harga) as sub_harga, tax, $created_by, '$created_at'
                from site.t_asset_purchase_asset_temp
                WHERE created_by = $created_by
            ";
        $this->db->query($sql);

        $this->db->where('created_by', $created_by)
                ->delete('site.t_asset_purchase_asset_temp');
    }

    public function edit($table, $data){
        $this->db->where('id', $data['id']);
        return $this->db->update($table, $data); 
    }

    public function delete($table,$id) {
        $this->db->where('id', $id);
        $this->db->delete($table);
    }

    public function getNo_pr($no_pr = '')
    {
        if ($no_pr != '') {
            $where = "and no_pr = '$no_pr'";
        } else {
            $where = "";
        }

        $sql="
                select a.*, b.username from site.t_asset_purchase_requistion a
                left join mpm.user b on a.created_by = b.id
                where a.status = 4 $where
            ";

        return $this->db->query($sql);
    }

    // ==============================================================================================
    // ======================================== Penyerahan Asset ====================================
    public function data_penyerahan_asset(){
        $sql = "
                SELECT a.id, a.no_pr, concat(a.no_po,'-',b.id) as no_po, a. nama_toko, a.alamat, a.telp, a.tgl_po, a.upload_req, a.fax, a.attn, b.id as id_barang, b.nama_barang, b.tipe, b.sub_harga, b.sub_tax, b.total, b.created_at, c.username
                FROM
                    (
                        SELECT a.id, a.no_pr, a.no_po, a.nama_toko, a.alamat, a.upload_req, a.tgl_po, a.telp, a.user_req, a.fax, a.attn
                        FROM site.t_asset_purchase_asset a
                    )a
                    INNER JOIN
                    (	SELECT id, no_po, nama_barang, tipe, sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total, created_at
                        FROM site.t_asset_purchase_asset_detail
                        GROUP BY id
                    )b on a.no_po = b.no_po
                    LEFT JOIN
                    (
                        SELECT id, username
                        FROM mpm.user
                    )c on a.user_req = c.id
                ORDER BY a.id desc
            ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0)
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function get_penyerahan_asset()
    {
        $sql = "
            select a.*, b.username from site.t_asset_penyerahan_asset a
            left join mpm.`user` b on a.userid_penerima = b.id
        ";

        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0)
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function get_history_penyerahan_asset($no_po)
    {
        $sql = "
            select a.*, b.username from site.t_asset_penyerahan_asset a
            left join mpm.`user` b on a.userid_penerima = b.id
            where a.no_po = '$no_po'
        ";

        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0)
        {
            return $hasil->result();
        } else {
            return array();
        }
    }
    // ==============================================================================================
    // ========================================== Input Asset =======================================
    public function generate_no_pof(){
        $cek_jumlah = $this->db->get("site.t_asset_purchase_asset")->num_rows();
        if (!$cek_jumlah) {
            $reserve_nomor_params = "MPM_POF/" . date('Y') . date('m') . "-001";
        } else {
            // echo "ada";
            $sql = "
                select right(a.no_po,3) as urut
                from site.t_asset_purchase_asset a
                GROUP BY a.no_po
                ORDER BY a.id desc, right(a.no_po,3) desc
                limit 1
            ";

            // MPM_LBM/202209-099

            // URUT = 099
            // URUT = 99
            // RESERVE_NOMOR = 99 + 1 = 100
            // RESERVE_NOMOR_PARAMS = MPM_LBM/202209-002
            // RESERVE_NOMOR_PARAMS = MPM_LBM/202209-100


            $proses = $this->db->query($sql)->row();
            $reserve_nomor = $proses->urut + 1;
            if (strlen($reserve_nomor) === 1) {
                $reserve_nomor_params = "MPM_POF/" . date('Y') . date('m') . "-00" . $reserve_nomor;
            } else if (strlen($reserve_nomor) === 2) {
                $reserve_nomor_params = "MPM_POF/" . date('Y') . date('m') . "-0" . $reserve_nomor;
            } else {
                $reserve_nomor_params = "MPM_POF/" . date('Y') . date('m') . "-" . $reserve_nomor;
            }
            // echo "reserve_nomor_params : ".$reserve_nomor_params;
        }

        // echo "reserve_nomor_params : ".$reserve_nomor_params;
        // die;

        return $reserve_nomor_params;
    }

    public function view_asset($id_asset = ''){
        if ($id_asset == '') {
            $id_assets = '';
        }else{
            $id_assets = "and id = $id_asset";
        }

        $sql = "
                SELECT a.*, b.namagrup
                from
                    (
                        select a.*
                        from mpm.asset a
                        where a.deleted < 1 $id_assets
                    )a
                    LEFT JOIN
                    (
                        select a.id, a.namagrup
                        from mpm.grupasset a
                    )b on a.grupid = b.id
                order by id desc
                ";
        $hasil = $this->db->query($sql);
        // var_dump($hasil->num_rows());die;
        if ($hasil->num_rows() > 1)
        {
            return $hasil->result();
        } else {
            return $hasil;
        }
    }

    public function getGrupassetcombo()
    {
        $sql='select id,namagrup from mpm.grupasset';
        return $this->db->query($sql);
    }

    public function getUser()
    {
        $sql='
            select id,username, email from mpm.user
            where level not in (4,5,6) and supp = 000 and active = 1
            order by username
            ';
        return $this->db->query($sql);
    }

    public function history_asset(){

        $id = $this->uri->segment(3);
        $sql = "
                SELECT a.id, b.tgl_pengiriman, b.status, a.namabarang, b.userid_penerima, c.username, c.email
                from 
                (
                        select a.id, a.namabarang, a.no_po, a.no_pr
                        from mpm.asset a
                )a
                INNER JOIN
                (
                        select a.*
                        from site.t_asset_penyerahan_asset a
                ) b on a.no_po = b.no_po OR a.no_pr = b.no_pr
                left join
                (
                        select id, username, email
                        from mpm.`user`
                )c on b.userid_penerima = c.id
                where a.id = $id
                order by b.tgl_pengiriman desc
            ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }
}
?>