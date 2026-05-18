<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_asset extends CI_Model 
{   
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

    public function get_list_pengajuan($kode)
    {
        $tglawal= $kode['from'];
        $tglakhir = $kode['to'];

        $sql="
            select a.id,a.no_po, a.user_req, a.upload_req, b.username
            from db_temp.t_temp_pengajuan_asset a LEFT JOIN mpm.`user` b
            on a.user_req = b.id
            where (tgl_po >= '$tglawal' and tgl_po <= '$tglakhir')
            ";
        return $this->db->query($sql);
    }
    
    public function getPengajuan($nopo = "")
    {
        if ($nopo != '') {
            $nopox = "where no_po = '$nopo'";
        } else {
            $nopox = "";
        }

        $sql="
            select a.id,a.no_po, a.user_req, a.upload_req, b.username
            from db_temp.t_temp_pengajuan_asset a LEFT JOIN mpm.`user` b
            on a.user_req = b.id
            $nopox
            ";
        return $this->db->query($sql);
    }

    public function getAssets_sds($kode)
    {   
        $userid = $this->session->userdata('id');
        $from = $kode['from'];
        $to = $kode['to'];
        $dt = $kode['dt'];
        $created_at = $kode['created_at'];
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        // echo "<pre><br><br><br><br><br>";
        // print_r($nv);
        // print_r($from);
        // print_r($to);
        // echo "</pre>";
        if ($dt == '1' ){
            if ($conn) {
                echo "<script>
                alert('Koneksi dengan Server SDS Berhasil');
                </script>";
                $sql1 = "
                        SELECT	a.*
                        FROM    dbsls.dbo.t_gl_kas_detail a
                        WHERE   a.coa_id in ('1120000110','1120000120','1120000130','1120000140','1120000150','1120000160', '1120000170') and  (a.tgl_entry >= '$from' and a.tgl_entry <= '$to')
                ";
    
                $query = sqlsrv_query($conn, $sql1);

                $this->db->query("delete from db_temp.t_temp_asset_sds_kas where created_by = $userid");

                if ($query) {
                    while ($data = sqlsrv_fetch_array($query)){
                        $data = array(
                                    'siteid' => $data['siteid'],
                                    'no_voucher' => $data['no_voucher'],
                                    'nourut' => $data['nourut'],
                                    'coa_id' => $data['coa_id'],
                                    'cos_description' => $data['cos_description'],
                                    'tipe_trans' => $data['tipe_trans'],
                                    'tipe_bukti' => $data['tipe_bukti'],
                                    'tipe_kas' => $data['tipe_kas'],
                                    'tgl_trans' => $data['tgl_trans']->format('Y-m-d H:i:s'),
                                    'debet' => $data['debet'],
                                    'kredit' => $data['kredit'],
                                    'keterangan' => $data['keterangan'],
                                    'currency_id' => $data['currency_id'],
                                    'userid' => $data['userid'],
                                    'tgl_entry' => $data['tgl_entry']->format('Y-m-d H:i:s'),
                                    'nick_voucher' => $data['nick_voucher'],
                                    'created_by' => $userid,
                                    'created_at' => $created_at
                                );

                        $this->db->insert('db_temp.t_temp_asset_sds_kas',$data);
                    }
                }
            }
        }elseif ($dt == '2' ){
            if ($conn) {
                echo "<script>
                alert('Koneksi dengan Server SDS Berhasil');
                </script>";
                $sql1 = "
                        SELECT	a.*
                        FROM    dbsls.dbo.t_gl_jurnal a
                        WHERE   a.coa_id in ('1120000110','1120000120','1120000130','1120000140','1120000150','1120000160', '1120000170') and  (a.tgl_entry >= '$from' and a.tgl_entry <= '$to')
                ";
    
                $query = sqlsrv_query($conn, $sql1);

                $this->db->query("delete from db_temp.t_temp_asset_sds_jurnal where created_by = $userid");

                if ($query) {
                    while ($data = sqlsrv_fetch_array($query)){
                        $data = array(
                                        // 'siteid' => $data['siteid'],
                                        // 'no_voucher' => $data['nojurnal'],
                                        // 'nourut' => $data['nourut'],
                                        // 'coa_id' => $data['coa_id'],
                                        // 'cos_description' => $data['description'],
                                        // 'tgl_trans' => $data['tgl_trans']->format('Y-m-d H:i:s'),
                                        // 'debet' => $data['debet'],
                                        // 'kredit' => $data['kredit'],
                                        // 'keterangan' => $data['keterangan'],
                                        // 'currency_id' => $data['currency_id'],
                                        // 'userid' => $data['userid'],
                                        // 'tgl_entry' => $data['tgl_entry']->format('Y-m-d H:i:s'),
                                        // 'nick_voucher' => $data['nick_voucher'],
                                        // 'created_by' => $userid,
                                        // 'created_at' => $created_at

                                        'siteid' =>$data['siteid'],
                                        'nojurnal' =>$data['nojurnal'],
                                        'coa_id' =>$data['coa_id'],
                                        'nourut' =>$data['nourut'],
                                        'description' =>$data['description'],
                                        'tgl_trans' =>$data['tgl_trans']->format('Y-m-d H:i:s'),
                                        'debet' =>$data['debet'],
                                        'kredit' =>$data['kredit'],
                                        'keterangan' =>$data['keterangan'],
                                        'currency_id' =>$data['currency_id'],
                                        'rate_currency' =>$data['rate_currency'],
                                        'group_saldo' =>$data['group_saldo'],
                                        'tgl_entry' =>$data['tgl_entry']->format('Y-m-d H:i:s'),
                                        'userid' =>$data['userid'],
                                        'flag_jurnal' =>$data['flag_jurnal'],
                                        'created_by' => $userid,
                                        'created_at' =>  $created_at
                                    );
    
                        $this->db->insert('db_temp.t_temp_asset_sds_jurnal',$data);
                    }
                }
            }
        }
    }

    public function getAssets_temp($userid, $data)
    {
        if ($data == 1) {
            $table = 'db_temp.t_temp_asset_sds_kas';
        } else {
            $table = 'db_temp.t_temp_asset_sds_jurnal';
        }
    
        $sql = "
            SELECT * FROM $table
            WHERE created_by = $userid AND nojurnal not in (SELECT b.kode FROM mpm.asset b)
        ";
        return $this->db->query("$sql");
    }
    
    public function getAssets_temp_by_voucher($id, $data)
    {
        $userid = $this->session->userdata('id');
        if ($data == 1) {
            $sql = "
                SELECT	*
                FROM db_temp.t_temp_asset_sds_kas
                where created_by = $userid and concat(no_voucher,nourut) = '$id'";

            $hasil = $this->db->query($sql);
        } else {
            $sql = "
                SELECT	*
                FROM db_temp.t_temp_asset_sds_jurnal
                where created_by = $userid and concat(nojurnal,nourut) = '$id'";

            $hasil = $this->db->query($sql);
        }

        return $hasil;
    }

    public function getAsset_mutasi($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $proses =  $this->db->get('mpm.asset_mutasi');
        return $proses;
    }

    public function my_asset(){
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
                where a.userid = $id and a.tgl_mutasi = (
                    select max(b.tgl_mutasi)
                    from mpm.asset_mutasi b
                    where b.userid = $id
                )
            ) c on a.id = c.id_asset and a.userid_mutasi = c.userid
            where a.userid_mutasi = $id and status = '2'
                ";
        $hasil = $this->db->query($sql);
        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
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

    public function save_konfirmasi_asset($data){
        $id=$this->session->userdata('id');
        $id_asset = $data['id_asset'];
        $id_mutasi = $data['id_mutasi'];

    	$sql = "
            Update mpm.asset_mutasi a
            set a.status = '2'
            where id = $id_mutasi
        ";

        $update_mutasi = $this->db->query($sql);

        $sql2 = "
                update mpm.asset a
                set a.userid_mutasi = (
                    SELECT userid FROM mpm.asset_mutasi
                    WHERE id = $id_mutasi and userid = $id
                )
                where a.id = $id_asset 
            ";

        $update = $this->db->query($sql2);
        /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";
        */
    }

    public function view_asset($id_asset = ''){
        if ($id_asset == '') {
            $id_assets = '';
        }else{
            $id_assets = "and id = $id_asset";
        }

        $sql = "
                SELECT a.*, b.namagrup, c.username as username_req, d.username as username_mutasi
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
                    LEFT JOIN
                    (
                            select a.id, a.username
                            from mpm.`user` a
                    )c on a.userid_req = c.id
                    LEFT JOIN
                    (
                            select a.id, a.username
                            from mpm.`user` a
                    )d on a.userid_mutasi = d.id
                order by id desc
                ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil;
        } else {
            return array();
        }
                
            /* END PROSES TAMPIL KE WEBSITE */
      
    }

    public function input($table, $data){

        $this->db->insert($table, $data);
        return $this->db->insert_id(); 
        // $signature = $this->get_signature($this->db->insert_id());
        // return $signature;
    }

    public function edit($table, $data){
        // var_dump($data);die;
        $this->db->where('id', $data['id']);
        return $this->db->update($table, $data); 
        // $signature = $this->get_signature($this->db->insert_id());
        // return $signature;
    }

    public function delete($table, $data)
    {
        $this->db->set('deleted', 1);
        $this->db->where('id', $data['id']);
        return $this->db->update($table, $data); 
    }
    
    public function history_asset(){

        $id = $this->uri->segment(3);
        $sql = "
                SELECT a.id, b.id as id_mutasi, a.namabarang, b.`userid`, b.tgl_mutasi, b.alasan_mutasi, b.alasan_approve, b.bukti_upload,  b.bukti_upload2, b.status, c.username, c.email
                from 
                    (
                        select a.id, a.namabarang
                        from mpm.asset a
                    )a
                    INNER JOIN
                    (
                        select a.id, a.id_asset, a.`userid`, a.alasan_mutasi, a.alasan_approve, a.tgl_mutasi, a.bukti_upload, a.bukti_upload2, a.status, a.deleted
                        from mpm.asset_mutasi a
                        where id_asset = $id
                    ) b on a.id = b.id_asset
                    left join
                    (
                        select id, username, email
                        from mpm.user
                    )c on b.userid = c.id
                where a.id = $id  and b.deleted = 0
                order by b.tgl_mutasi desc
                ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function proses_delete_mutasi($id_mutasi){
    	
    	$this->db->query("
        delete from mpm.asset_mutasi
        where id = $id_mutasi");
    }

// ===================================PENGAJUAN ASSETS=========================================

    public function showbarang(){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $id=$this->session->userdata('id');
        $this->db->select('*');
        $this->db->where('created_by', $id);
        $hasil = $this->db->get('db_temp.t_temp_pengajuan_asset_temp');
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
        
    }

    public function input_barang_pengajuan() {
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $id=$this->session->userdata('id');
        $tax = $this->input->post('tax');
        $jumlah = $this->input->post('jb');
        $harga =$this->input->post('harga');
        $sub_harga =  $jumlah*$harga;
        $sub_tax = $sub_harga*$tax/100;

        $post['nama_barang']= $this->input->post('nb');
        $post['jumlah']= $this->input->post('jb');
        $post['harga']= $this->input->post('harga');
        $post['tax']= $sub_tax;
        $post['created_by']= $id;
        $post['tipe']= $this->input->post('tipe');
        $post['created_date']=date('Y-m-d h:i:s');
        $this->db->insert('db_temp.t_temp_pengajuan_asset_temp',$post);

        redirect('assets_2/pengajuan_assets/');
    }

    public function delete_barang_pengajuan() {
        $id = $this->uri->segment('3');
        $hasil = $this->db->query("Delete From db_temp.t_temp_pengajuan_asset_temp where `id` = $id ");
        
        redirect('assets_2/pengajuan_assets/');
    }

    public function save_pengajuan($data) {
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $id=$this->session->userdata('id');
        $upload = $data['upload'];
        $no_po = $this->input->post('np');    
        $hasil = $this->db->query("select * from db_temp.t_temp_pengajuan_asset where no_po ='$no_po'");
        
        if ($hasil->num_rows() > 0) 
        {
            echo "Input Gagal, Nomer PO sudah digunakan silahkan coba lagi !!";
            
        } else {
                $post['no_po']= $no_po;
                $post['nama_toko']= $this->input->post('nt');
                $post['alamat']= $this->input->post('alamat');
                $post['telp']= $this->input->post('telp');
                $post['fax']= $this->input->post('fax');
                $post['attn']= $this->input->post('attn');
                $post['tgl_po']=$this->input->post('tgl');
                $post['user_req']=$this->input->post('user_req');
                $post['upload_req']= $upload;
                $post['created_date']=date('Y-m-d h:i:s');
                $post['created_by']= $id;
                $this->db->insert('db_temp.t_temp_pengajuan_asset',$post);

                $created_date = date('Y-m-d h:i:s');

                $sql =  "
                        INSERT INTO db_temp.t_temp_pengajuan_asset_detail
                        SELECT a.no_po, b.nama_barang, b.tipe, b.jumlah, b.harga, b.sub_harga, b.tax,  $id, '$created_date'
                        FROM
                            (	
                                SELECT no_po, created_by
                                FROM db_temp.t_temp_pengajuan_asset
                                WHERE created_by = $id and created_date =(SELECT MAX(created_date) as created_date from db_temp.t_temp_pengajuan_asset WHERE created_by = $id) )a
                                LEFT JOIN
                            (
                                SELECT nama_barang, tipe, jumlah, harga,(jumlah*harga) as sub_harga, tax, created_by
                                from db_temp.t_temp_pengajuan_asset_temp
                                WHERE created_by = $id)b
                        on a.created_by = b.created_by
            
                        ";
                $this->db->query($sql);

                $this->db->where('created_by', $id)
                        ->delete('db_temp.t_temp_pengajuan_asset_temp');
                        
                redirect('assets_2/view_pengajuan/');
        }
    }

    public function view_pengajuan(){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        // $id=$this->session->userdata('id');
        $sql = "
                SELECT a.no_po, a. nama_toko, a.alamat, a.telp, a.tgl_po, a.upload_req, b.sub_harga, b.sub_tax, b.total, b.created_date, c.username
                FROM
                    (	
                        SELECT a.no_po, a.nama_toko, a.alamat, a.upload_req, a.tgl_po, a.telp, a.user_req
                        FROM db_temp.t_temp_pengajuan_asset a
                    )a
                    LEFT JOIN
                    (	SELECT no_po, nama_barang, sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total, created_date
                        FROM db_temp.t_temp_pengajuan_asset_detail
                        GROUP BY no_po
                    )b on a.no_po = b.no_po
                    LEFT JOIN
                    (
                        SELECT id, username
                        FROM mpm.user
                    )c on a.user_req = c.id
                ORDER BY b.created_date desc
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