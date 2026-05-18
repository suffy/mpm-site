<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_finance extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->session_id = $this->session->userdata('id');
        $this->created_at = $this->model_outlet_transaksi->timezone();
    }

	public function get_customer_sds()
  {
    $query = "
        select 	a.customerid, a.nama_customer, b.username, c.branch_name, c.nama_comp, c.site_code
        from dbsls.m_customer a left join site.master_user b 
            on a.customerid = concat(1,b.kode_lang) left join site.master_site_with_user c 
            on b.id = c.userid
        where b.active =1 and c.site_code is not null and b.username not like '%MPI%'
        order by a.nama_customer asc
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    return $this->db->query($query);
  }

  public function get_master_user($tahun, $site_code = '')
  {
    if($site_code)
    {
      $params_site_code = "and concat(a.kode_comp, a.nocab) = '$site_code'";
    }else{
      $params_site_code = "";
    }
    $query = "
      select a.id, a.username, a.kode_lang, a.company, concat(b.kode_comp, b.nocab) as site_code, c.nama_comp
      from site.master_user a inner join (
        select a.kode_comp, a.nocab
        from db_dp.t_dp a 
        where a.tahun = $tahun $params_site_code
        group by concat(a.kode_comp, a.nocab)
      )b on a.username = b.kode_comp left join site.master_site c 
        on concat(b.kode_comp, b.nocab) = c.site_code
      where a.`level` = 4 and a.active = 1
      order by a.company asc
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    return $this->db->query($query);
  }

  public function get_spk($year, $month, $limit = '', $params_subbranch = '', $from = '', $to = '')
  {
    if ($limit) {
        $params_limit = " limit $limit ";
    }else{
        $params_limit = "limit 1000";
    }

    // if ($customerid) {
    //     $params_customer = " and a.kode_lang = right('$customerid',5)";
    // }else{
    //     $params_customer = "";
    // }

    // echo "params_subbranch : ".$params_subbranch;
    if ($params_subbranch) {
      $params_site_code = " and a.kode_alamat = '$params_subbranch'"; 

      $kode_lang = $this->get_master_user($year, $params_subbranch);
      if ($kode_lang->num_rows() > 0) {
        $kode_lang = $kode_lang->row()->kode_lang;
        // echo "kode_lang : ".$kode_lang;
        $params_customer = " and a.kode_lang = '$kode_lang'";
        // $params_customer = " and a.kode_lang = right('$kode_lang->row()->kode_lang',5)";
      }else{
        $params_customer = "";
      }
    }else{
        $params_site_code = "";
        $params_customer = "";
    }

    // echo "params_customer : ".$params_customer;

    if ($from && $to) {
        $params_periode = " and a.tglpesan between '$from' and '$to'";
    }else{
        $params_periode = "";
    }

    // echo "params_limit : ".$params_limit;
    // echo "params_customer : ".$params_customer;
    // die;

    $query = "
        select 	a.id, a.company, a.supp, date(a.tglpesan) as tglorder, date(a.tglpo) as tglpo, 
                a.nopo,a.total_value, a.kode_alamat, a.status, 
                b.bank_garansi, b.cl, b.kode_lang, 
                c.saldoakhir, c.jt, d.total_value_current_month,
                e.namasupp, a.open, a.status_approval, a.open_by, f.username,
                a.total_value + if(d.total_value_current_month is null,0,d.total_value_current_month) as total_estimasi, 
                a.signature, DATE_FORMAT(a.tglpesan, '%Y%m') AS periode
        from mpm.po a left join
        (
            select a.id, a.username, a.kode_lang, a.bank_garansi, a.cl
            from mpm.user a 
            where a.active = 1 $params_customer
        )b on a.userid = b.id left join (
            select kode_lang, sum(saldoakhir) as saldoakhir, sum(jt) as jt
            FROM
            (
                select  if(right(a.kode_lang,5) = '20252','20156',
                        if(right(a.kode_lang,5) = '20251','20250',
                        if(right(a.kode_lang,5) = '20268','20256',
                        if(right(a.kode_lang,5) = '20267','20256',
                        right(a.kode_lang,5))))) as kode_lang, 
                        a.saldoakhir, a.jt
                from    db_analisis.t_temp_piutang a 
            )a GROUP BY kode_lang      
        )c on b.kode_lang = c.kode_lang left join (
            select a.userid, sum(a.total_value) as total_value_current_month
            from mpm.po a 
            where year(a.tglpo) = $year and month(a.tglpo) = $month and a.deleted = 0
            GROUP BY a.userid
        )d on a.userid = d.userid left join (
            select a.supp, a.namasupp
            from site.master_supplier a 
        )e on a.supp = e.supp left join site.master_user f 
      on a.open_by = f.id
      where a.deleted = 0
        $params_periode
        $params_site_code
        order by a.id desc
        $params_limit 
    ";

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";

    return $this->db->query($query);
  }

    public function update_piutang_from_dbsls()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        // echo "created_at : ".$created_at;
        // die;
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "obherbal12!@");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            echo "koneksi berhasil <br />";
        } else {
            echo "Koneksi ke server sds gagal.<br />";
            // die(print_r(sqlsrv_errors(), true));
            alert("Koneksi ke server sds gagal.Silahkan ulangi kembali.");
            die;
        }

        $this->db->query("truncate table db_analisis.t_temp_piutang");

        // $datanya = "select productid,nama_product,jual from jts.dbo.m_product where left(productid,2) = '01'";
        $sql = "
            select '1' + a.kode_lang as kode_lang,saldoakhir,jt  
            from
            (
                select kode_lang, sum(dokument-bayar) as saldoakhir
                FROM
                (
                    select  SUBSTRING(customerid,2,5) kode_lang,                              
                            CASE 
                            WHEN SUBSTRING(no_sales,1,1) = 'R' then dokument * -1 else dokument
                            end as dokument, bayar
                    from    dbsls.dbo.t_ar_ink_master 
                    where   dokument-bayar>0 
                    )a GROUP BY kode_lang
            )a inner join
            (	
                select kode_lang, sum(dokument-bayar) as jt
                FROM
                (
                    select  SUBSTRING(customerid,2,5) kode_lang,                              
                            CASE 
                            WHEN SUBSTRING(no_sales,1,1) = 'R' then dokument * -1 else dokument
                            end as dokument, bayar, tgl_tempo,datediff(day, tgl_tempo, GETDATE()) * -1 as c
                    from    dbsls.dbo.t_ar_ink_master 
                    where   dokument-bayar>0  
                )a  where c<108
                GROUP BY kode_lang
            )b on a.kode_lang = b.kode_lang
            ORDER BY a.kode_lang
        ";
        $query = sqlsrv_query($conn, $sql); 

        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        
        // $sql = "delete from db_analisis.t_temp_piutang where id = $id";
        // $proses= $this->db->query($sql);
        if ($query) 
        {
            while ($data = sqlsrv_fetch_array($query)){
                $kode_lang =  $data['kode_lang'];
                $saldoakhir = $data['saldoakhir'];
                $jt = $data['jt'];
                $sql = "insert into db_analisis.t_temp_piutang
                        select $kode_lang, $saldoakhir, $jt, $this->session_id, '$created_at'
                ";
                $proses= $this->db->query($sql);
            }
        }  
        sqlsrv_close($conn);

        return $proses;
    }

    public function update_po($id_po, $data)
    {
        $this->db->where('id', $id_po);
        $this->db->update('mpm.po', $data);
        return $this->db->affected_rows();
    }

    public function get_max_piutang_date()
    {
        $query = "
            select max(last_updated) as last_updated
            from db_analisis.t_temp_piutang
        ";
        return $this->db->query($query);
    }

    

}

/* End of file model_sales.php */
/* Location: ./application/models/model_sales.php */