<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_claim extends CI_Model 
{

    public function __construct() {
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->created_by = $this->session->userdata('id');
    }

    public function get_registrasi_program($kode_alamat = '', $signature = ''){

        if ($signature) {
            $params_where = "where a.signature = '$signature'";
        }else{
            $params_where = "";
        }

        if ($kode_alamat) {
            $params_alamat = "and a.site_code in ($kode_alamat)";
        }else{
            $params_alamat = "";
        }

        $query = "
        select 	a.id, d.id as id_ajuan, f.id as id_revisi, a.kategori, a.supp, b.namasupp, 
                a.from, a.to, a.nama_program, a.nomor_surat, a.syarat, 
                a.upload_jpg, a.upload_pdf, a.signature, a.duedate, c.username,
                d.nama_pengirim, d.email_pengirim, d.ajuan_excel, d.ajuan_zip, d.created_at as tgl_kirim_ajuan_claim,
                e.nama_comp, d.status, d.nama_status, d.signature as signature_ajuan, d.nomor_ajuan,
                d.keterangan_mpm, d.file_mpm, d.mpm_at, d.keterangan_principal_area, d.file_principal_area, d.principal_area_at, 
                d.keterangan_principal_ho, d.file_principal_ho, d.principal_ho_at, d.pic_mpm, g.username as pic_mpm_by
        from management_claim.registrasi_program a LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )b on a.supp = b.supp left join 
        (
            select a.id, a.username
            from mpm.user a 
        )c on a.created_by = c.id LEFT JOIN 
        (
            select  a.site_code, a.id, a.nama_pengirim, a.email_pengirim, a.ajuan_excel, a.ajuan_zip, a.id_program, a.created_at, a.created_by,
                    a.status, a.nama_status, a.signature, a.nomor_ajuan, a.keterangan_mpm, a.file_mpm, a.mpm_at, a.keterangan_principal_area, 
                    a.file_principal_area, a.principal_area_at, a.keterangan_principal_ho, a.file_principal_ho, a.principal_ho_at, a.pic_mpm
            from    management_claim.ajuan_claim a 
            where a.deleted is null $params_alamat
        )d on a.id = d.id_program LEFT JOIN (
            select  concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from    mpm.tbl_tabcomp a 
            where   a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )e on d.site_code = e.site_code LEFT JOIN
        (
            select *
            from management_claim.revisi_ajuan a 
        )f on d.id = f.id_ajuan left join 
        (
            select a.id, a.username
            from mpm.user a 
        )g on d.pic_mpm = g.id
        $params_where
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_registrasi_program_by_signature_ajuan($signature = ''){

        if ($signature) {
            $params_where = "where d.signature = '$signature'";
        }else{
            $params_where = "";
        }

        $query = "
            select 	a.id, a.kategori, a.supp, b.namasupp, a.from, a.to, a.nama_program, 
                    a.nomor_surat, a.syarat, a.upload_jpg, a.upload_pdf, a.signature, a.duedate, c.username,
                    d.nama_pengirim, d.email_pengirim, d.ajuan_excel, d.ajuan_zip, d.created_at as tgl_kirim_ajuan_claim,e.nama_comp, d.status, d.nama_status, 
                    d.signature as signature_ajuan, d.nomor_ajuan, a.created_by, a.segment
            from management_claim.registrasi_program a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp left join 
            (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id LEFT JOIN 
            (
                select  a.site_code, a.id, a.nama_pengirim, a.email_pengirim, a.ajuan_excel, a.ajuan_zip, a.id_program, a.created_at, a.created_by,
                        a.status, a.nama_status, a.signature, a.nomor_ajuan
                from management_claim.ajuan_claim a 
                where a.deleted is null
            )d on a.id = d.id_program LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )e on d.site_code = e.site_code
            $params_where
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    

    public function get_registrasi_program_by_id($id =''){

        $session_user = $this->session->userdata('id');

        if ($id) {
            $params = "and a.id = $id";
        }else{
            $params = "";
        }

        $query = "
            select 	a.id, a.kategori, a.supp, b.namasupp, a.from, a.to, a.nama_program, a.nomor_surat, a.syarat, 
                    a.upload_jpg, a.upload_pdf, a.signature, a.duedate, a.upload_template_program, a.created_by,
                    c.username, a.created_at
            from management_claim.registrasi_program a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp left join 
            (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.deleted is null and a.created_by = $session_user
            $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_registrasi_program_by_signature($signature_program = '')
    {
        if ($signature_program) {
            $params_signature = "and a.signature = '$signature_program'";
        }else{
            $params_signature = "";
        }

        $query = "
            select  a.*, b.namasupp, c.username, c.email, d.nama_kategori, e.nama_status_validasi, e.keterangan, 
                    f.nama_template, f.filename, a.tahun_folder
            from management_claim.registrasi_program a left join site.master_supplier b 
                on a.supp = b.supp left join site.master_user c 
                on a.updated_by = c.id left join management_claim.master_kategori d
                on a.kategori = d.id left join management_claim.master_flag_validasi e
                on a.flag_validasi = e.id left join management_claim.master_template f
                on a.id_template = f.id
            where a.deleted_at is null
            $params_signature
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_registrasi_program_by_nomor_surat($nomor_surat)
    {
        $query = "
            select *
            from management_claim.registrasi_program a 
            where a.nomor_surat = '$nomor_surat'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_registrasi_program_by_nomor_surat_exception($nomor_surat, $id)
    {
        $query = "
            select *
            from management_claim.registrasi_program a 
            where a.nomor_surat = '$nomor_surat' and a.id not in ($id)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function generate($from_site, $created_at){

        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        // $query = "
        //     select a.nomor_ajuan, substr(a.nomor_ajuan,5,3) as urut, a.created_by, a.created_at
        //     from management_claim.ajuan_claim a
        //     where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.nomor_ajuan is not null
        //     ORDER BY substr(a.nomor_ajuan,5,3) desc
        //     limit 1
        // ";

        $query = "
            select a.nomor_ajuan, a.urut
            from 
            (
                select 	a.nomor_ajuan, 
                        if(right(substr(a.nomor_ajuan,5,4),1)='/',concat('0',substr(a.nomor_ajuan,5,3)),substr(a.nomor_ajuan,5,4)) as urut, 			
                        a.created_by, a.created_at
                from management_claim.ajuan_claim a
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
                $generate = "CLM-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "CLM-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "CLM-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "CLM-001/MPM/$romawi/$tahun_now";
        }
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

    public function get_verifikasi_ajuan($signature_ajuan){

        // <option value="3"> On MPM Check </option>
        // <option value="4"> On Principal Check </option>
        // <option value="5"> Reject Principal </option>
        // <option value="6"> Approve </option>
        // <option value="7"> DP Kirim DN (Debit Note / Faktur Pajak) </option>
        // <option value="8"> Finance (Principal kirim ke MPM) </option>
        // <option value="9"> Finance (MPM kirim ke DP) </option>

        $query = "
            select 	a.id, a.nomor_ajuan, a.`status`, 
                    a.nama_status,
                    a.tanggal, a.catatan_verifikasi, a.created_at, a.created_by,b.username,
                    a.signature, a.signature_ajuan
            from management_claim.verifikasi_ajuan a left join (
                select a.id, a.username
                from mpm.user a
            )b on a.created_by = b.id
            where a.signature_ajuan = '$signature_ajuan'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_data_user($id){
        $query = "
            select *
            from mpm.user a 
            where a.id = $id
        ";

        return $this->db->query($query);
    }

    public function get_site_code_by_program($id =''){

        if ($id) {
            $params = "where a.id_program = $id";
        }else{
            $params = "";
        }

        $query = "
            select a.site_code, a.nomor_ajuan, a.nama_status, b.id, c.nama_comp
            from management_claim.ajuan_claim a LEFT JOIN 
            (
                select a.id
                from management_claim.registrasi_program a 
            )b on a.id_program = b.id left join (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )c on a.site_code = c.site_code
            $params
            GROUP BY a.site_code
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_site_code_by_program_site_code($id, $site_code){

        // if ($id) {
        //     $params = "where a.id_program = $id";
        // }else{
        //     $params = "";
        // }

        $query = "
            select a.site_code, a.nomor_ajuan, a.nama_status, a.id
            from management_claim.ajuan_claim a LEFT JOIN 
            (
                select a.id
                from management_claim.registrasi_program a 
            )b on a.id_program = b.id
            where a.id_program = $id and a.site_code = '$site_code'
            
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_site($site_code = ""){

        if ($site_code) {
            $params = "where a.site_code ='$site_code'";
        }else{
            $params = "";
        }

        // $query = "
        //     select a.site_code, a.branch_name, a.nama_comp, a.status_ho, a.status_claim
        //     from 
        //     (
        //         select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan, a.status_ho, a.status_claim
        //         from mpm.tbl_tabcomp a 
        //         where a.status = 1
        //         GROUP BY concat(a.kode_comp, a.nocab)
        //     )a inner join (
        //         select concat(a.kode_comp, a.nocab) as site_code
        //         from db_dp.t_dp a 
        //         where a.tahun = 2023 and a.`status` = 1
        //     )b on a.site_code = b.site_code
        //     $params
        //     ORDER BY a.urutan asc
        // ";

        $query = "
            select *
            from site.master_site a
            $params
        ";

        return $this->db->query($query);
    }

    public function get_sitecode($id){
        $tahun_now = "2026";

        if ($this->session->userdata('level') == 4) 
        {
            $query = "
                select a.username, b.branch_name, b.nama_comp, b.site_code, b.sub
                from site.master_user a INNER JOIN 
                (
                    select a.site_code, a.branch_name, a.nama_comp, a.sub
                    from site.master_site a INNER JOIN (
                        select concat(a.kode_comp, a.nocab) as site_code
                        from db_dp.t_dp a
                        where tahun = $tahun_now and a.`status` = 1
                    )b on a.site_code = b.site_code
                )b on a.username = left(b.site_code,3)
                where a.id = $id
            ";
            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
            // die;
            return  $this->db->query($query);   
        }else
        {    
            return $this->db->query("select 1");
        }
    }

    public function get_site_code_by_sub($sub)
    {
        $tahun_now = "2024";
        $query = "            
            select a.site_code, a.branch_name, a.nama_comp, a.sub
            from site.master_site a INNER JOIN (
                select concat(a.kode_comp, a.nocab) as site_code
                from db_dp.t_dp a
                where tahun = $tahun_now and a.`status` = 1
            )b on a.site_code = b.site_code
            where a.sub = $sub
        ";

        return $this->db->query($query);
    }

    public function cek_user_pengajuan($signature_ajuan){
        $userid = $this->session->userdata('id');
        $query = "
            select *
            from management_claim.ajuan_claim a
            where a.signature = '$signature_ajuan' and a.created_by = $userid
        ";

        return $this->db->query($query);
    }

    public function cek_revisi_by_signature_ajuan($signature_ajuan){
        $userid = $this->session->userdata('id');
        $query = "
            select *
            from management_claim.revisi_ajuan a
            where a.signature_ajuan = '$signature_ajuan' and a.created_by = $userid
        ";

        return $this->db->query($query);
    }

    public function cek_status_validasi_excel($signature_program){
        $query = "
            select *
            from management_claim.registrasi_program a 
            where a.signature = '$signature_program'
        ";

        return $this->db->query($query);
    }

  public function get_registrasi_program_by_supp_date($advanced = null)
  {
    $id = $this->session->userdata('id');
    $level = $this->session->userdata('level');
    $supp = $this->session->userdata('supp');

    // echo "supp : ".$supp;
    // echo "level : ".$level;
    // die;

    if($level == 10) // jika user bukan dp
    {
      $params_created_by = "";
      $params_where_ajuan_created_by = "";
      $params_site_code = "";
      $params_region_principal = "";
      $params_supp = "";

    }elseif($level == 3 || $level == '3a' || $level == '3b' || $level == '3c' || $level == '3d') // jika user bukan dp
    {
      // echo "level";
      $params_created_by = "";
      $params_where_ajuan_created_by = "";
      $params_site_code = "";
      $params_region_principal = "";

      $get_region = $this->get_region_by_userid($id);
      if ($get_region->num_rows() > 0) 
      {
        $site_code = '';
        foreach ($get_region->result() as $r) {
            $site_code.= ",'".$r->site_code."'";
        }
        $params_region_principal = "and a.site_code in (".preg_replace('/,/', '', $site_code,1).")";
      }else{
        $params_region_principal = "and a.site_code in ('xxx')";
      }
      $params_supp = "and a.supp in ($supp)";
    }elseif($level == 4 || $level == 5) // jika user adalah dp
    { 
      $params_created_by = "";
      $params_where_ajuan_created_by = "and a.created_by = $id";
      $params_site_code = "and a.site_code = "."'".$this->model_master_data->get_tabcomp_by_kode_comp($this->session->userdata('username'))->row()->site_code."'";
      $params_pic_principal = "";
      $params_region_principal = "";
      $params_supp = "";
    }

    if(!$advanced) // jika tidak memilih periode tanggal, supp, pic, dan status
    { 
      $params_periode = "";
      $params_pic = "";
      $params_supp = $params_supp;
      $params_limit = "limit 0";
      $params_delete = "where a.deleted_at is null";
      $params_region_principal = $params_region_principal;
      $params_kategori = "";
    }else // kalau tidak memilih apapun
    { 
      $from = $advanced['from'];
      $to = $advanced['to'];
      $site_code = $advanced['site_code'];
      $pic = $advanced['pic'];
      $supp = $advanced['supp'];
      $params_periode = "and (a.from >= '$from' and a.to <= '$to')";
      $params_pic = ($pic == 'all') ? "" : "and (a.created_by = '$pic')";
      $params_supp = ($supp == 'all') ? "" : "and (a.supp = '$supp')";
      $params_limit = "";
      $params_site_code = ($site_code) ? "and (a.site_code = '$site_code')" : "";
      $params_delete = ($advanced['flag_delete'] == '') ? "where a.deleted_at is null" : "where a.deleted_at is not null";            
      $params_region_principal = $params_region_principal;
      $params_kategori = ($advanced['kategori'] == 'all') ? "" : "and (a.kategori = '$advanced[kategori]')";
    }

    // die;

    $query = "
      select 	a.id, a.kategori, a.supp, b.namasupp, a.`from`, a.`to`, a.nama_program, a.nomor_surat, a.syarat, 
              a.duedate, a.upload_jpg, a.upload_pdf, a.upload_template_program, a.status_validasi, a.signature,
              a.segment,
              c.status as `status`, h.nama_status, c.signature as signature_ajuan, c.site_code, d.branch_name, d.nama_comp, a.created_by, e.username, c.nomor_ajuan, c.status_data_final, c.status_hardcopy, c.nama_status_hardcopy, c.file_hardcopy, c.nomor_hardcopy, c.status_keikutsertaan, c.nama_status_keikutsertaan,
              c.status_internal, i.nama_status as nama_status_internal, f.username as pic_userid_username, 
              if(g.nama_kategori is null, a.kategori, g.nama_kategori) as nama_kategori, c.deleted_at, j.duedate_response, a.tahun_folder
      from management_claim.registrasi_program a left join site.master_supplier b 
        on a.supp = b.supp left join (
        select *
        from management_claim.ajuan_claim a 
        $params_delete
        $params_where_ajuan_created_by $params_site_code $params_region_principal
      )c on a.id = c.id_program left join site.master_site d on c.site_code = d.site_code
      left join site.master_user e on a.updated_by = e.id
      left join site.master_user f on c.pic_userid = f.id
      left join management_claim.master_kategori g on a.kategori = g.id
      left join management_claim.master_status h on c.status = h.id
      left join management_claim.master_status_internal i on c.status_internal = i.id
      left join management_claim.log_aktivitas_claim j on c.id_log = j.id
      where a.deleted is null $params_periode $params_pic $params_supp $params_kategori                  
      $params_limit 
    ";

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";

    return $this->db->query($query);
  }

    public function get_region_by_userid($userid)
    {
        $query = "
            select *
            from management_claim.master_region a 
            where a.pic_principal_1 = $userid or a.pic_principal_2 = $userid
        ";
        return $this->db->query($query);
    }

    public function get_ajuan_by_signature($signature){
        $query = "
            select 	*
            from management_claim.ajuan_claim a
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_status($id)
    {
        if ($id) {
            $params = "where a.id = $id";
        }else{
            return null;
        }
        
        $query = "
            select a.*
            from management_claim.master_status a 
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_status_internal($id = "", $user = "")
    {
        if ($id) {
            $params = "and a.id in ($id)";
        }else{
            $params = "";
        }

        if ($user) {
            $params_user = "and a.user in ('DP','$user')";
        }else{
            $params_user = "";
        }

        $query = "
            select a.*
            from management_claim.master_status_internal a 
            where a.active = 1
            $params $params_user
            order by a.id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_status_mti($id){

        if ($id == 1) {
            $nama_status = "PENDING MPI";
        }elseif($id == 2){
            $nama_status = "PENDING KAM";
        }elseif($id == 3){
            $nama_status = "PENDING HEAD OF MTI";
        }elseif($id == 4){
            $nama_status = "REJECT HEAD OF MTI";
        }elseif($id == 5){
            $nama_status = "PENDING FINANCE";
        }elseif($id == 6){
            $nama_status = "APPROVE FINANCE";
        }elseif($id == 7){
            $nama_status = "REJECT FINANCE";
        }

        return $nama_status;
    }

    public function get_status_hardcopy($id){

        if ($id == 1) {
            $nama_status = "PENDING DP";
        }elseif($id == 2){
            $nama_status = "PENDING MPM";
        }elseif($id == 3){
            $nama_status = "REJECT MPM";
        }elseif($id == 4){
            $nama_status = "PENDING PRINCIPAL";
        }elseif($id == 5){
            $nama_status = "REJECT PRINCIPAL";
        }elseif($id == 6){
            $nama_status = "APPROVE";
        }

        return $nama_status;
    }

    public function get_status_hardcopy_mti($id){

        if ($id == 1) {
            $nama_status = "PENDING MPI";
        }elseif($id == 2){
            $nama_status = "PENDING MPM";
        }elseif($id == 3){
            $nama_status = "TERIMA MPM";
        }elseif($id == 4){
            $nama_status = "PENDING PRINCIPAL/KAM";
        }elseif($id == 5){
            $nama_status = "PENDING PRINCIPAL/FINANCE";
        }elseif($id == 6){
            $nama_status = "APPROVE";
        }elseif($id == 7){
            $nama_status = "REJECT";
        }

        return $nama_status;
    }

    public function get_revisi_by_id_ajuan($id_ajuan){
        $query = "
            select *
            from management_claim.revisi_ajuan a
            where a.id_ajuan = '$id_ajuan'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_ajuan_claim_by_id_program($id_program)
    {
        $query = "
            select a.*, b.branch_name, b.nama_comp
            from management_claim.ajuan_claim a left join site.master_site b 
			    on a.site_code = b.site_code
            where a.id_program in ($id_program) and a.nomor_ajuan is not null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_ajuan_claim_join_registrasi_program_by_id_program($id_program)
    {
        $query = "
            select a.*, b.*
            from management_claim.ajuan_claim a left join (
                select a.id as id_program, a.nomor_surat, a.kategori
                from management_claim.registrasi_program a 
            )b on a.id_program = b.id_program
            where a.id_program in ($id_program) and a.nomor_ajuan is not null
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_ajuan_claim_by_id_program_and_signature($id_program, $signature){
        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id_program in ($id_program) and a.signature in ('$signature')
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_verifikasi_by_id($id = ''){
        
        if ($id) {
            $params = "where a.id = $id";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.*
            from management_claim.verifikasi_ajuan a left join (
                select a.id, a.username
                from mpm.user a
            )b on a.created_by = b.id
            $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function get_ajuan_claim_by_id_program_and_user($id_program, $id_user)
    {
        $query = "
            select *
            from management_claim.ajuan_claim a
            where a.deleted_at is null and a.id_program = $id_program and a.created_by = $id_user
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_revisi_ajuan_by_id_ajuan($id_ajuan){

        $query = "
            select a.*, b.username
            from management_claim.revisi_ajuan a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id
            where a.id_ajuan = $id_ajuan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_preview_import($signature_program, $signature){
        $query = "
            select *
            from management_claim.import_bonus_barang a
            where a.signature_program = '$signature_program' and a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die; 
        
        return $this->db->query($query);
    }

    public function get_preview_import_diskon($id_header){
        $query = "
            select *
            from management_claim.import_diskon a
            where a.id_header = $id_header
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die; 
        
        return $this->db->query($query);
    }

    public function get_preview_import_failed($signature_program, $signature){
        $query = "
            select *
            from management_claim.import_bonus_barang a
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row > 0
            limit 100
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        
        return $this->db->query($query);
    }

    public function get_preview_import_failed_diskon($signature_program, $signature){
        $query = "
            select *
            from management_claim.import_diskon a
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row > 0
            limit 100
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        
        return $this->db->query($query);
    }

    public function get_preview_import_failed_diskon_by_idheader($id_header){
        $query = "
            select *
            from management_claim.import_diskon a
            where a.id_header = $id_header and a.validasi_row > 0
            
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        
        return $this->db->query($query);
    }

    public function get_count_validasi_failed($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row > 0
        ";
        return $this->db->query($query);
    }

    public function get_count_import($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.signature_program = '$signature_program' and a.signature = '$signature'
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function get_count_validasi_success($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row = 0
        ";
        return $this->db->query($query);
    }

    public function get_sum_import_bonus($signature_program, $signature){
        $query = "
            select sum(a.qty_jual) as total_qty_jual, sum(a.qty_bonus) as total_qty_bonus, sum(a.value_jual) as total_value_jual, sum(a.value_bonus) as total_value_bonus 
            from management_claim.import_bonus_barang a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row = 0
        ";
        return $this->db->query($query);
    }

    public function get_sum_import_diskon($id_header){
        $query = "
            select  sum(a.qty_jual) as total_qty_jual, sum(a.value_jual) as total_value_jual, sum(a.disc_principal) as total_disc_principal, sum(a.disc_cabang) as total_disc_cabang, 
                    sum(a.disc_extra) as total_disc_extra, sum(a.disc_cash) as total_disc_cash, sum(a.disc_claim) as total_disc_claim
            from management_claim.import_diskon a 
            where a.id_header = $id_header
        ";
        return $this->db->query($query);
    }

    public function get_import_bonus_by_signature($signature){
        $query = "
            select *
            from management_claim.import_bonus_barang a 
            where a.signature in ($signature)
        ";

        return $this->db->query($query);
    }

    public function insert_traffic_import($site_code = '', $created_by, $status_import)
    {

        $created_at = $this->model_outlet_transaksi->timezone();

        $data = [
            "site_code"     => $site_code,
            "created_by"    => $created_by,
            "created_at"    => $created_at,
            "status_import" => $status_import
        ];

        $insert = $this->db->insert("management_claim.traffic_import", $data);
        return $insert;

    }

    public function get_traffic_import()
    {
        $query = "select * from management_claim.traffic_import a order by a.id desc limit 1";
        return $this->db->query($query);

    }

    public function insert_traffic($site_code = '', $created_by, $status_import)
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $data = [
            "site_code"         => $site_code,
            "created_by"        => $created_by,
            "created_at"        => $created_at,
            "status_import"     => $status_import
        ];

        $insert = $this->db->insert("management_claim.traffic_import", $data);
        return $insert;
    }

    public function get_dashboard($advanced){

        $id = $this->session->userdata('id');

        if ($advanced) {
            $supp = $advanced['supp'];
            $from = $advanced['from'];
            $to = $advanced['to'];
            $kategori = $advanced['kategori'];

            if ($kategori) {
                $params_kategori = "and a.kategori = '$kategori'";
            }else{
                $params_kategori = "";
            }


            // kalau user mpm
            $level = $this->session->userdata('level');
            if($level == 10){
                $params_created_by = "and a.created_by = $id";
                $params_where_ajuan_created_by = "";
            }else{
                $params_created_by = "";
                $params_where_ajuan_created_by = "where a.created_by = $id";
            }

            $params = "where a.supp = '$supp' and date(a.from) between '$from' and '$to' $params_kategori $params_created_by";
        }else{
            // kalau user mpm
            $level = $this->session->userdata('level');
            if($level == 10){
                $params_created_by = "where a.created_by = $id";
                $params_where_ajuan_created_by = "";
            }else{
                $params_created_by = "";
                $params_where_ajuan_created_by = "where a.created_by = $id";
            }

            $params_site_code = '';
            $params_kategori = '';

            $params = $params_created_by;
        }

        $query = "
        select 	a.id, a.kategori, a.supp, b.namasupp, a.`from`, a.`to`, a.nama_program, a.nomor_surat, a.syarat, 
                a.duedate, a.upload_jpg, a.upload_pdf, a.upload_template_program,
                a.status_validasi, a.signature, c.status as `status`, c.nama_status, c.signature as signature_ajuan,
                c.site_code, d.branch_name, d.nama_comp, a.created_by, e.username, c.nomor_ajuan
        from management_claim.registrasi_program a LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a 
        )b on a.supp = b.supp LEFT JOIN (
            select *
            from management_claim.ajuan_claim a 
            $params_where_ajuan_created_by $params_site_code
        )c on a.id = c.id_program LEFT JOIN (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on c.site_code = d.site_code LEFT JOIN (
            select a.id, a.username
            from mpm.user a 
        )e on a.created_by = e.id
        $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_registrasi_program_by_supp_kategori_periode($advanced){

        $supp               = $advanced['supp'];
        $kategori           = $advanced['kategori'];
        $from               = $advanced['from'];
        $to                 = $advanced['to'];
        $pic                = $advanced['pic'];
        // $site_code_join     = $advanced['site_code_join'];

        if ($supp) {
            $params_supp = "and a.supp = '$supp'";
        }else{
            // $params_supp = "where a.supp = '123'";
            $params_supp = "";
        }

        if ($kategori) {
            $params_kategori = "and a.kategori = '$kategori'";
        }else{
            $params_kategori = '';
        }

        if ($from) {
            $params_periode = "and date(a.from) between '$from' and '$to'";
        }else{
            $params_periode = '';
        }

        if ($pic == "all") {
            $params_pic = '';
        }else{
            $params_pic = "and a.created_by = '$pic'";
        }

        if ($kategori == "all") {
            $params_kategori = "";
        }else{
            $params_kategori = "and a.kategori = '$kategori'";
        }

        // $query = "
        //     select a.*, b.namasupp, c.username
        //     from management_claim.registrasi_program a left join (
        //         select a.supp, a.namasupp
        //         from mpm.tabsupp a
        //     )b on a.supp = b.supp left join (
        //         select a.id, a.username
        //         from mpm.user a 
        //     )c on a.created_by = c.id
        //     where a.deleted is null
        //     $params_supp $params_kategori $params_periode $params_pic
        // ";

        $query = "
            select a.*, b.namasupp, c.username, d.count_dp_eligible, e.count_dp_claimed
            from management_claim.registrasi_program a left join
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp left join (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id left join (
                select a.id_program, count(*) as count_dp_eligible
                from management_claim.registrasi_program_site_code a
                group by a.id_program
            )d on a.id = d.id_program left join (
                select a.id_program, count(*) as count_dp_claimed
                from management_claim.ajuan_claim a 
                where a.nomor_ajuan is not null
                group by a.id_program
            )e on a.id = e.id_program
            where a.deleted is null
            $params_supp $params_kategori $params_periode $params_pic
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_ajuan_claim_group_subbranch_by_idprogram($id_program){

        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id_program in ($id_program)       
            GROUP BY a.site_code
        ";

        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_ajuan_claim_group_subbranch_by_idprogram_sitecode($id_program, $site_code_join = ''){

        if ($site_code_join) {
            $params_site_code_join = "and a.site_code in ($site_code_join)";
        }else{
            $params_site_code_join = '';
        }

        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id_program in ($id_program) $params_site_code_join    
            GROUP BY a.site_code
        ";

        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_ajuan_claim_by_idprogram_sitecode($id_program, $site_code){
        $query = "
            select a.*, count(b.id) as count_verifikasi
            from management_claim.ajuan_claim a left join (
                select *
                from management_claim.verifikasi_ajuan a 
            )b on a.id = b.id_ajuan
            where a.id_program = $id_program and a.site_code = '$site_code'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function summary_ajuan_claim_group_status_by_idprogram($id_program){
        $query = "
            select	a.nama_status, count(a.status) as count_status
            from management_claim.ajuan_claim a
            where a.id_program = $id_program
            GROUP BY a.status
        ";
        return $this->db->query($query);
    }

    public function get_site_code_by_userid($username){
        $tahun = date('Y');
        $query = "
            select a.site_code, a.branch_name, a.nama_comp
            from 
            (
                select  concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.status = 1 and (concat(a.kode_comp, a.nocab) <> 'JMI22' and concat(a.kode_comp, a.nocab) <> 'PTK32')
                GROUP BY concat(a.kode_comp, a.nocab)
            )a INNER JOIN (
                select concat(a.kode_comp, a.nocab) as site_code
                from db_dp.t_dp a 
                where a.tahun = $tahun and a.`status` = 1
            )b on  a.site_code = b.site_code
            where a.kode_comp = '$username'
            limit 1
        ";   
        return $this->db->query($query);
    }    

    public function get_buletin_program($signature = ''){
        
        $userid = $this->session->userdata('id');

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = '';
        }

        $query = "
            select a.*, b.namasupp, c.count, d.count as total_download
            from management_claim.buletin_program a left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )b on a.supp = b.supp left join (
                select a.id_buletin, count(a.id_buletin) as count
                from management_claim.log_buletin_program a 
                where a.created_by = $userid
                GROUP BY a.id_buletin
            )c on a.id = c.id_buletin left join (
                select a.id_buletin, count(a.id_buletin) as count
                from management_claim.log_buletin_program a 
                GROUP BY a.id_buletin
            )d on a.id = d.id_buletin
            where a.deleted_at is null $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_registrasi_program_mti($signature = ''){
        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = '';
        }
        $query = "
            select a.*, b.username, b.name, b.email, if(a.supp = '001-herbana','HERBANA',c.namasupp) as namasupp
            from management_claim.registrasi_program_mti a left join 
            (
                select a.id, a.username, a.name, a.email
                from mpm.user a 
            )b on a.userid_kam = b.id left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )c on a.supp = c.supp
            where a.deleted_at is null $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_user_kam($userid_kam = '', $supp = ''){
        if ($userid_kam) {
            $params_user = "and a.userid_kam = '$userid_kam'";
        }else{
            $params_user = '';
        }

        if ($supp) {
            $params_supp = "and a.supp = '$supp'";
        }else{
            $params_supp = "";
        }

        $query = "
            select a.*, b.username, b.name, b.email, if(a.supp = '001-herbana', 'HERBANA', c.namasupp) as namasupp
            from management_claim.master_kam a left join 
            (
                select a.id, a.username, a.name, a.email
                from mpm.user a 
            )b on a.userid_kam = b.id left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )c on a.supp = c.supp
            where a.deleted_at is null $params_user $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_master_user_finance($userid_finance = ''){
        if ($userid_finance) {
            $params_user = "and a.userid_finance = '$userid_finance'";
        }else{
            $params_user = '';
        }


        $query = "
            select a.*, b.username, b.name, b.email
            from management_claim.master_finance a left join 
            (
                select a.id, a.username, a.name, a.email
                from mpm.user a 
            )b on a.userid_finance = b.id
            where a.deleted_at is null $params_user
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_master_brand($brand = ''){
        if ($brand) {
            $params = "and a.brand = '$brand'";
        }else{
            $params = '';
        }
        $query = "
            select a.*, b.*
            from management_claim.master_brand a left join (
                select a.id, a.username, a.name
                from mpm.user a 
            )b on a.created_by = b.id
            where a.deleted_at is null $params
            order by a.brand asc
        ";
        return $this->db->query($query);
    }

    public function get_master_account($account = ''){
        if ($account) {
            $params = "and a.account = '$account'";
        }else{
            $params = '';
        }
        $query = "
            select a.*, b.*
            from management_claim.master_account a left join (
                select a.id as userid, a.username, a.name, a.email
                from mpm.user a
            )b on a.created_by = b.userid
            where a.deleted_at is null $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_import_registrasi_program_mti($signature){
        $query = "
            select a.*, b.username, b.name, b.email, c.namasupp
            from management_claim.temp_import_registrasi_kam_mti a left join (
                select a.id, a.username, a.name, a.email
                from mpm.user a 
            )b on a.userid_kam = b.id left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )c on a.supp = c.supp
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function cek_validasi_temp_registrasi_mti($signature){
        $query = "
            select *
            from management_claim.temp_import_registrasi_kam_mti a 
            where a.deleted_at is null and a.signature = '$signature' and a.validasi_row < 3
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }
    
    public function get_registrasi_join_ajuan_mti($advanced){

        $level  = $this->session->userdata('level');
        $userid = $this->session->userdata('id'); 
        // echo "level : ".$level;

        if ($level == 5 || $level == 4 ) {
            $params_dp = "and a.created_by = $userid";
        }else{
            $params_dp = '';
        }

        $supp = $advanced['supp'];
        $from = $advanced['from'];
        $to = $advanced['to'];
        $status = $advanced['status'];

        $params_supp = ($supp != 'all') ? "and a.supp = '$supp'" : '';
        $params_periode= ($from && $to) ? "and a.from >= '$from' and a.to <= '$to'"  : '';
        $params_status = ($status != 'all') ? "and c.`status` = '$status'" : '';
        

        // echo "supp : ".$supp;
        // echo "<br>";
        // echo "params_supp : ".$params_supp;
        // die;

        $query = "
            select a.*, b.*, c.*, d.namasupp
            from management_claim.registrasi_program_mti a LEFT JOIN (
                select a.id, a.username, a.name, a.email
                from mpm.user a 
            )b on a.userid_kam = b.id left join (
                select 	a.id as ajuan_id, a.id_program, a.nomor_ajuan, a.branch_name, a.nama_comp,
                        a.nama_pengirim, a.email_pengirim, a.site_code, a.attach_1, a.attach_2, a.`status`, 
                        a.nama_status, a.verifikasi_at, a.verifikasi_by, a.verifikasi_note, a.verifikasi_file,
                        a.tanggal_claim, a.status_hardcopy, a.nama_status_hardcopy, a.file_hardcopy,
                        a.tanggal_kirim_hardcopy, a.nama_pengirim_hardcopy, a.email_pengirim_hardcopy,
                        a.tanggal_terima_hardcopy, a.terima_hardcopy_by, a.update_terima_hardcopy_at,
                        a.file_tanda_terima_hardcopy_ke_principal, a.tanda_terima_hardcopy_ke_principal_by,
                        a.tanda_terima_hardcopy_ke_principal_nama, a.tanggal_tanda_terima_hardcopy_ke_principal,
                        a.update_tanda_terima_hardcopy_ke_principal, a.signature as signature_ajuan, a.status_dp, a.nama_status_dp,
                        a.status_hardcopy_dp, a.nama_status_hardcopy_dp
                from 	management_claim.ajuan_claim_mti a 
                where   a.deleted_at is null $params_dp
            )c on a.id = c.id_program left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )d on a.supp = d.supp
            where a.deleted_at is null $params_supp $params_periode $params_status
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_registrasi_join_ajuan_mti_by_id_user($id_program, $userid){
        $query = "
            select a.*, b.*, c.*
            from management_claim.registrasi_program_mti a LEFT JOIN (
                select a.id, a.username, a.name, a.email
                from mpm.user a 
            )b on a.userid_kam = b.id left join (
                select 	a.id as ajuan_id, a.id_program, a.nomor_ajuan, a.branch_name, a.nama_comp,
                        a.nama_pengirim, a.email_pengirim, a.site_code, a.attach_1, a.attach_2, a.`status`, 
                        a.nama_status, a.verifikasi_at, a.verifikasi_by, a.verifikasi_note, a.verifikasi_file,
                        a.tanggal_claim, a.status_hardcopy, a.nama_status_hardcopy, a.file_hardcopy,
                        a.tanggal_kirim_hardcopy, a.nama_pengirim_hardcopy, a.email_pengirim_hardcopy,
                        a.tanggal_terima_hardcopy, a.terima_hardcopy_by, a.update_terima_hardcopy_at,
                        a.file_tanda_terima_hardcopy_ke_principal, a.tanda_terima_hardcopy_ke_principal_by,
                        a.tanda_terima_hardcopy_ke_principal_nama, a.tanggal_tanda_terima_hardcopy_ke_principal,
                        a.update_tanda_terima_hardcopy_ke_principal, a.signature as signature_ajuan
                from 	management_claim.ajuan_claim_mti a 
            )c on a.id = c.id_program
            where a.deleted_at is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_ajuan_claim_mti($signature = ''){

        if($signature){
            $params = "and a.signature = '$signature'";
        }else{
            $params = '';
        }

        $query = "
            select *
            from management_claim.ajuan_claim_mti a 
            where a.deleted_at is null $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_ajuan_claim_mti_by_id_program_user($id_program, $userid){

        $query = "
            select *
            from management_claim.ajuan_claim_mti a 
            where a.deleted_at is null and a.id_program = $id_program and a.created_by = '$userid'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function generate_mti($from_site, $created_at){

        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        $query = "
            select a.nomor_ajuan, substr(a.nomor_ajuan,9,3) as urut, a.created_by, a.created_at
            from management_claim.ajuan_claim_mti a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.nomor_ajuan is not null
            ORDER BY a.id desc
            limit 1
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
                $generate = "CLM_MTI-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "CLM_MTI-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "CLM_MTI-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "CLM_MTI-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    public function get_verifikasi_mti_by_id($id){
        $query = "
            select a.*, b.name, b.email, b.username
            from management_claim.verifikasi_mpm_mti a left join (
                select a.id, a.username, a.name, a.email
                from mpm.user a 
            )b on a.created_by = b.id
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_master_account_by_signature($signature){

        $query = "
            select *
            from management_claim.master_account a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_master_kam_by_signature($signature){
        $query = "
            select *
            from management_claim.master_kam a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_master_finance_by_signature($signature){
        $query = "
            select *
            from management_claim.master_finance a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_master_mapping_account(){
        $query = "
            select a.*, b.*, c.*, d.`name` as created_by
            from management_claim.mapping_account_kam a left join (
                select a.id, a.username, a.name, a.email
                from mpm.user a 
            )b on a.kam_userid = b.id LEFT JOIN (
                select a.id, a.account
                from management_claim.master_account a 
                where a.deleted_at is null
            )c on a.account_id = c.id left join (
                select a.id, a.username, a.name
                from mpm.user a 
            )d on a.created_by = d.id
            where a.deleted_at is null
        ";
        return $this->db->query($query);
    }

    public function get_mapping_account_by_signature($signature){
        $query = "
            select *
            from management_claim.mapping_account_kam a 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_master_brand_by_signature($signature){
        $query = "
            select *
            from management_claim.master_brand a 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_mapping_account_by_user_dan_account($userid_kam, $account_id){
        $query = "
            select *
            from management_claim.mapping_account_kam a 
            where a.deleted_at is null and a.kam_userid = '$userid_kam' and a.account_id = '$account_id'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_account_by_id($id){
        $query = "
            select *
            from management_claim.master_account a 
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_registrasi_program_mti_by_signature($signature){
        $query = "
            select *
            from management_claim.registrasi_program_mti a 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_ajuan_claim_mti_by_signature($signature){
        $query = "
            select *
            from management_claim.ajuan_claim_mti a
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_registrasi_program_regular($from = null, $to = null){

        if($from != null && $to != null){
            $params = "and a.from >= '$from' and a.to <= '$to'";            
            $params_limit = "";
        }else{
            $params = "";
            $params_limit = "limit 100";
        }

        $query = "
             select a.id, a.kategori, e.nama_kategori, a.supp, a.from, a.to, a.nama_program, a.nomor_surat, a.syarat, a.duedate, a.upload_jpg, 
                    a.upload_pdf, a.upload_template_program, date(a.created_at) as created_at, a.created_by,
                    b.namasupp, a.signature, a.first_hand, date(a.updated_at) as updated_at, a.updated_by, 
                    if(d.username is null, c.username, d.username) as username, 
                    if(d.name is null, c.name, d.name) as name, a.pic, a.id_template, f.filename, a.tahun_folder
            from management_claim.registrasi_program a left join site.master_supplier b 
                on a.supp = b.supp left join site.master_user c 
                on a.created_by = c.id left join site.master_user d
                on a.updated_by = d.id left join management_claim.master_kategori e 
                on a.kategori = e.id left join management_claim.master_template f 
                on a.id_template = f.id
            where a.deleted is null $params 
            order by a.id desc $params_limit
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_site($tahun){
        $query = "
            select a.id, a.username, a.company, a.alamat, a.status_ho, b.branch_name, b.nama_comp, b.site_code
            from 
            (
                select 	a.id, a.username, a.company, b.kode_alamat, b.alamat, b.status_ho
                from	 	mpm.user a LEFT JOIN
                (
                    SELECT	a.username, a.kode_alamat, a.alamat, a.`status`, a.status_ho
                    from mpm.t_alamat a
                    where a.`status` = 1
                )b on a.username = b.username
                where 	a.active = 1
                group by a.username
            )a INNER JOIN 
            (
                select a.site_code, a.branch_name, a.nama_comp, a.kode_comp, urutan
                FROM
                (
                    select 	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.kode_comp, a.urutan
                    from 		mpm.tbl_tabcomp a
                    where		a.`status` = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER JOIN 
                (
                    select concat(a.kode_comp, a.nocab) as site_code
                    from db_dp.t_dp a 
                    where a.tahun = $tahun and a.`status` = 1
                )b on a.site_code = b.site_code            
            )b on a.username = b.kode_comp
            ORDER BY urutan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_registrasi_program_site_code($id_program){
        $query = "
            select 	a.id, a.id_program, a.site_code, a.created_at, a.created_by,
                    b.nomor_surat, b.nama_program, c.branch_name, c.nama_comp
            from management_claim.registrasi_program_site_code a LEFT JOIN (
                select a.id as id_program, a.nomor_surat, a.nama_program
                from management_claim.registrasi_program a 
            )b on a.id_program = b.id_program LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )c on a.site_code = c.site_code
            where a.id_program = $id_program
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_registrasi_program_product($id_program){
        $query = "
            select 	a.id, a.id_program, a.kodeprod, a.created_at, a.created_by,
			        b.nomor_surat, b.nama_program, c.namaprod, c.grup, c.subgroup
            from management_claim.registrasi_program_product a LEFT JOIN (
                select a.id as id_program, a.nomor_surat, a.nama_program
                from management_claim.registrasi_program a 
            )b on a.id_program = b.id_program LEFT JOIN (
                select a.kodeprod, a.namaprod, a.grup, a.subgroup
                from mpm.tabprod a 
                GROUP BY a.kodeprod
            )c on a.kodeprod = c.kodeprod
            where a.id_program = $id_program
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_product_by_supp($supp){
        $query = "
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, b.nama_group, c.nama_sub_group
            from mpm.tabprod a left join (
                select a.kode_group, a.nama_group
                from mpm.tbl_group a 
            )b on a.grup = b.kode_group LEFT JOIN (
                select a.sub_group, a.nama_sub_group
                from db_produk.t_sub_group a 
            )c on a.subgroup = c.sub_group
            where a.active = 1 and a.supp = '$supp'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_user($id = null){
        if ($id) {
            $params = "where a.id = $id";
        }else{
            $params = "";
        }

        $query = "
            select *
            from site.master_user a 
            $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_mapping_struktur_approval(){

        $query = "
            select  a.*, b.`name` as name_approval, b.jabatan as jabatan_approval, b.email as email_approval, b.username as username_approval, 
                    c.`name` as name_head, c.jabatan as jabatan_head, c.email as email_head, c.username as username_head, d.username as username_updated
            from management_claim.mapping_struktur_approval a left join (
                select a.id, a.username, a.email, a.name, a.jabatan
                from mpm.user a 
            )b on a.userid = b.id left join (
                select a.id, a.username, a.email, a.name, a.jabatan
                from mpm.user a 
            )c on a.userid_head = c.id left join (
                select a.id, a.username, a.email, a.name, a.jabatan
                from mpm.user a 
            )d on a.updated_by = d.id
            where a.deleted_at is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_mapping_approval_by_signature($signature){
        $query = "
            select *
            from management_claim.mapping_struktur_approval a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_master_region($id = null)
    {
        if ($id) {
            $params_id = "and a.id = $id";
        }else{
            $params_id = "";
        }

        $query = "
        select 	a.*, b.branch_name, b.nama_comp, 
                c.name as name_principal_1, c.username as username_principal_1, c.email as email_principal_1,
                d.name as name_principal_2, d.username as username_principal_2, d.email as email_principal_2,
                e.name as name_mpm, e.username as username_mpm, e.email as email_mpm,
                f.name as name_created_by
        from management_claim.master_region a left join site.master_site b
            on a.site_code = b.site_code left join site.master_user c 
            on a.pic_principal_1 = c.id left join site.master_user d 
            on a.pic_principal_2 = d.id left join site.master_user e 
            on a.pic_mpm = e.id  left join site.master_user f 
            on a.created_by = f.id 
            where a.deleted_at is null
            $params_id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pic_region_by_site_code($site_code){

        $query = "
            select a.*, b.name as name_mpm, c.name as name_principal, d.name as name_finance
            from management_claim.master_region a left join (
                select a.id, a.username, a.email, a.name, a.jabatan
                from mpm.user a
            )b on a.userid_mpm = b.id left join (
                select a.id, a.username, a.email, a.name, a.jabatan
                from mpm.user a
            )c on a.userid_principal = c.id left join (
                select a.id, a.username, a.email, a.name, a.jabatan
                from mpm.user a
            )d on a.userid_finance = d.id
            where a.site_code = '$site_code' 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_mapping_stuktural_by_userid($userid){
        $query = "
            select 	a.*, a.userid_head, 
                    b.username as userid_head_username, b.name as userid_head_name, b.email as userid_head_email
            from management_claim.mapping_struktur_approval a left join site.master_user b 
                on a.userid_head = b.id
            where a.deleted_at is null and a.userid = $userid
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_mapping_stuktural_by_userid_head($userid){
        $query = "
            select 	a.*, a.userid_head, 
                    b.username as userid_head_username, b.name as userid_head_name, b.email as userid_head_email
            from management_claim.mapping_struktur_approval a left join site.master_user b 
                on a.userid_head = b.id
            where a.deleted_at is null and a.userid_head = $userid
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_region_by_site_code($site_code)
    {
        $query = "
            select *
            from management_claim.master_region a
            where a.site_code = '$site_code'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_log($id_program = '', $id_ajuan = '', $status = '', $status_internal ='', $function = '', $keterangan = '', $file = '')
    {
        $data = [
            'id_registrasi'     => ($id_program) ? $id_program : '',
            'id_ajuan'          => ($id_ajuan) ? $id_ajuan : '',
            'status'            => ($status) ? $status : '',
            'status_internal'   => ($status_internal) ? $status_internal : '',
            'function'          => ($function) ? $function : '',
            'keterangan'        => ($keterangan) ? $keterangan : '',
            'file'              => ($file) ? $file : '',
            'created_at'        => $this->created_at,
            'created_by'        => $this->session->userdata('id'),
        ];
        return ($this->db->insert('management_claim.log_aktivitas_claim', $data)) ? $this->db->insert_id() : false;
    }

    public function log_aktivitas_claim_by_id_ajuan($id_ajuan)
    {
        $query = "
            select a.keterangan, b.username
            from management_claim.log_aktivitas_claim a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id
            where a.id_ajuan = $id_ajuan
            ORDER BY a.id desc
            limit 1
        ";
        return $this->db->query($query);
    }

    public function get_log_aktivitas_by_id_ajuan($id_ajuan = "", $created_by = "")
    {
        if ($id_ajuan) {
            $params = "where a.id_ajuan = $id_ajuan ORDER BY a.id asc";
        }else{
            $params = "";
        }

        if ($created_by) {
            $params_userid = "and a.created_by = $created_by";
        }else{
            $params_userid = "";
        }

        $query = "
            select 	a.*, d.namasupp, c.branch_name, c.nama_comp, c.site_code, b.nomor_surat, 
                    b.nama_program, c.nomor_ajuan, c.tanggal_claim, e.username,
                    f.nama_status, g.nama_status as nama_status_internal, b.duedate, h.username as on_duty_username,
                    g.user as status_internal_pic, c.status_approval, a.tahun_folder
            from management_claim.log_aktivitas_claim a left join (
                select a.*
                from management_claim.registrasi_program a
            )b on a.id_registrasi = b.id inner join (
                select a.*
                from management_claim.ajuan_claim a
                where a.deleted_at is null
                $params_userid
            )c on a.id_ajuan = c.id left join site.master_supplier d 
                on b.supp = d.supp left join site.master_user e 
                on a.created_by = e.id left join (
                select a.*
                from management_claim.master_status a
            )f on a.status = f.id left join (
                select a.*
                from management_claim.master_status_internal a
            )g on a.status_internal = g.id left join site.master_user h
                on a.pic_on_duty = h.id
            $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_ajuan_claim_by_id($id)
    {
        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id = $id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function get_principal()
    {
        $supp = $this->session->userdata('supp');
        $level = $this->session->userdata('level');

        if($level == 5)
        {
            $params_supp = "a.supp in (001)";
        }else{
            if ($supp == '000') {
                $params_supp = "a.supp in (001,002,005,012,013,015,025,026,027)";
                // $params_herbana = "union all";
            }else{
                $params_supp = "a.supp = '$supp'";
            }
        }

        // echo "params_supp : ".$params_supp;
        // die;

        $query = "
            select *
            from site.master_supplier a
            where $params_supp
        ";

        // echo "<pre>";
        // print_r($query); 
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_master_region($data)
    {
        $this->db->insert('management_claim.master_region', $data);
        return $this->db->insert_id();
    }

    public function update_master_region($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('management_claim.master_region', $data);
        return $this->db->affected_rows();
    }

  public function get_master_region_by_site_code_supp_segment_pic_principal($site_code, $supp, $segment, $pic_principal_1, $pic_principal_2)
  {
    $query = "
        select *
        from management_claim.master_region a 
        where a.site_code = '$site_code' and a.supp = '$supp' and a.segment = '$segment' and a.pic_principal_1 = '$pic_principal_1' and a.pic_principal_2 = '$pic_principal_2'
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";       
    // die;

    return $this->db->query($query);
  }

    public function get_master_region_by_signature($signature)
    {
        $query = "
            select *
            from management_claim.master_region a 
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }
    
    public function get_master_template($signature = "")
    {
        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.namasupp, c.nama_kategori, d.username, a.tahun_folder
            from management_claim.master_template a left join site.master_supplier b 
                on a.supp = b.supp left join 
                (
                    select a.id, a.nama_kategori
                    from management_claim.master_kategori a 
                )c on a.id_kategori = c.id left join site.master_user d 
                on a.updated_by = d.id
            where a.deleted_at is null $params
        ";
        return $this->db->query($query);
    }

    public function get_master_kategori($signature = '')
    {
        if($signature)
        {
            $params = "and a.signature = '$signature'";
        }else{
            $params = '';
        }

        $query = "
            select a.*, b.username
            from management_claim.master_kategori a left join site.master_user b 
                on a.updated_by = b.id
            where a.deleted_at is null $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_kategori_by_nama_kategori($nama_kategori)
    {
        $query = "
            select a.*, b.username
            from management_claim.master_kategori a left join site.master_user b 
                on a.updated_by = b.id
            where a.nama_kategori = '$nama_kategori'
        ";
        return $this->db->query($query);
    }

    public function insert_master_kategori($data)
    {
        $this->db->insert('management_claim.master_kategori', $data);
        return $this->db->insert_id();
    }

    public function insert_master_template($data)
    {
        $this->db->insert('management_claim.master_template', $data);
        return $this->db->insert_id();
    }

    public function update_master_template($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('management_claim.master_template', $data);
        return $this->db->affected_rows();
    }

    public function update_master_kategori($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('management_claim.master_kategori', $data);
        return $this->db->affected_rows();
    }

  public function get_master_segment()
  {
    $query = "
      select a.*, b.namasupp, c.username
      from management_claim.master_segment a left join site.master_supplier b 
        on a.supp = b.supp left join site.master_user c
        on a.updated_by = c.id
      where a.deleted_at is null 
    ";
    return $this->db->query($query);
  }

    public function get_master_segment_by_supp_segment($supp, $segment)
    {
        $query = "
            select a.*
            from management_claim.master_segment a 
            where a.supp = '$supp' and a.nama_segment = '$segment'
        ";
        return $this->db->query($query);
    }

    public function insert_master_segment($data)
    {
        $this->db->insert('management_claim.master_segment', $data);
        return $this->db->insert_id();
    }

    public function insert_registrasi_program($data)
    {
        $this->db->insert('management_claim.registrasi_program', $data);
        return $this->db->insert_id();
    }

    public function update_registrasi_program($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('management_claim.registrasi_program', $data);
        return $this->db->affected_rows();
    }

    public function insert_ajuan_claim($data)
    {
        $this->db->insert('management_claim.ajuan_claim', $data);
        return $this->db->insert_id();
    }
    public function insert_log_claim($data)
    {
        $this->db->insert('management_claim.log_aktivitas_claim', $data);
        return $this->db->insert_id();
    }

    public function update_log_claim($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('management_claim.log_aktivitas_claim', $data);
        return $this->db->affected_rows();
    }

    public function insert_log_error($data)
    {
        $this->db->insert('management_claim.log_error', $data);
        return $this->db->insert_id();
    }

    public function update_ajuan_claim($data, $id)
    {
        // var_dump($data);
        // var_dump($id);
        $this->db->where('id', $id);
        $this->db->update('management_claim.ajuan_claim', $data);
        return $id;
    }

  public function get_master_region_by_site_code_supp_segment($site_code, $supp, $segment)
  {
    $query = "
      select *
      from management_claim.master_region a 
      where a.deleted_at is null and a.site_code = '$site_code' and a.supp = '$supp' and a.segment = '$segment'
    ";

    // echo $query;
    // die;

    return $this->db->query($query);
  }

    public function get_log_aktivitas_by_onduty($id_ajuan)
    {
        $query = "
            select a.*, b.username, b.email
            from management_claim.log_aktivitas_claim a left join site.master_user b 
                on a.pic_on_duty = b.id
            where a.id_ajuan = $id_ajuan and a.on_duty_finish = 0
            order by a.id desc
            limit 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_log_aktivitas_by_id($id = "")
    {

        if ($id) {
           $params = "where a.id = $id";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.username, b.email, concat(a.keterangan, ' from : ',b.username) as log_keterangan
            from management_claim.log_aktivitas_claim a left join site.master_user b 
                on a.pic_on_duty = b.id
            $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_log_aktivitas_by_ref_log($id_log)
    {
        $query = "
            select *
            from management_claim.log_aktivitas_claim a 
            where a.ref_log = $id_log
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function get_product_by_kodeprod_n_supp($kodeprod, $supp){

        $query = "
            select *
            from site.master_product a 
            where a.kodeprod = '$kodeprod' and a.supp = '$supp'
        ";

        return $this->db->query($query);

    }

    public function get_tabcomp_by_site_code($site_code = ''){

        if ($site_code) {
            $params = "where a.site_code = '$site_code'";
        }else{
            $params = "";
        }

        $query = "
            select *
            from site.master_site a 
            $params
        ";

        return $this->db->query($query);
    }

    public function get_tabcomp_by_site_code_and_sub($site_code, $sub){

        $query = "
            select *
            from site.master_site a
            where a.site_code = '$site_code' and a.sub = '$sub'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 

        return $this->db->query($query);
    }

    public function get_tabsalur_by_kode_class($kodesalur = ''){
        if ($kodesalur) {
            $params = "where a.kode = '$kodesalur'";
        }else{
            $params = "";
        }

        $query = "
            select a.kode, a.jenis, a.group
            from mpm.tbl_tabsalur a
            $params
        ";
        
        return $this->db->query($query);
    }

    public function get_master_flag_validasi($id = '')
    {
        if ($id) {
            $params_id = "and a.id = $id";
        }else{
            $params_id = "";
        }
        $query = "
            select *
            from management_claim.master_flag_validasi a
            where a.active = 1 $params_id
        ";
        return $this->db->query($query);
    }

    public function get_master_kategori_by_id($id)
    {
        $query = "
            select *
            from management_claim.master_kategori a
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_log_aktivitas_group_status_internal($from='', $to='', $kategori = '', $breakdown = '')
    {
        if ($from && $to) {
            $params_periode = "and date(c.from) >= '$from' and date(c.to) <= '$to'";
        }
        else{
            $params_periode = "";
        }

        if ($kategori == 'all' || $kategori == '') {
            $params_kategori = "";
        }
        else{
            $params_kategori = "and c.kategori = '$kategori'";
        }

        if ($breakdown) {
            $params_breakdown = "GROUP BY c.$breakdown";
        }
        else{
            $params_breakdown = "";
        }

        $query = "
            select 	a.status_internal, b.nama_status as nama_status_internal, count(*) as count, c.kategori
            from management_claim.log_aktivitas_claim a left join management_claim.master_status_internal b 
                on a.status_internal = b.id left join management_claim.registrasi_program c 
                on a.id_registrasi = c.id left join management_claim.ajuan_claim d 
	            on a.id_ajuan = d.id
            where a.on_duty_finish = 0 and d.deleted_at is null $params_kategori $params_periode
            GROUP BY a.status_internal
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_log_aktivitas_group_status_internal_and_principal($from='', $to='', $kategori = '', $breakdown = '')
    {
        if ($from && $to) {
            $params_periode = "and date(c.from) >= '$from' and date(c.to) <= '$to'";
        }
        else{
            $params_periode = "";
        }

        if ($kategori == 'all' || $kategori == '') {
            $params_kategori = "";
        }
        else{
            $params_kategori = "and c.kategori = '$kategori'";
        }

        if ($breakdown) {
            $params_breakdown = "GROUP BY c.$breakdown";
        }
        else{
            $params_breakdown = "";
        }

        $query = "
            select 	a.status_internal, b.nama_status as nama_status_internal, count(*) as count, c.kategori, c.supp, e.namasupp
            from management_claim.log_aktivitas_claim a left join management_claim.master_status_internal b 
                on a.status_internal = b.id left join management_claim.registrasi_program c 
                on a.id_registrasi = c.id left join management_claim.ajuan_claim d 
	            on a.id_ajuan = d.id left join site.master_supplier e 
	            on c.supp = e.supp
            where a.on_duty_finish = 0 and d.deleted_at is null $params_kategori $params_periode
            GROUP BY a.status_internal, c.supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_log_aktivitas_group_status_internal_and_principal_and_kategori($from='', $to='', $kategori = '', $breakdown = '')
    {
        if ($from && $to) {
            $params_periode = "and date(c.from) >= '$from' and date(c.to) <= '$to'";
        }
        else{
            $params_periode = "";
        }

        if ($kategori == 'all' || $kategori == '') {
            $params_kategori = "";
        }
        else{
            $params_kategori = "and c.kategori = '$kategori'";
        }

        if ($breakdown) {
            $params_breakdown = "GROUP BY c.$breakdown";
        }
        else{
            $params_breakdown = "";
        }

        $query = "
            select 	a.status_internal, b.nama_status as nama_status_internal, count(*) as count, c.kategori, c.supp, e.namasupp,  f.nama_kategori
            from management_claim.log_aktivitas_claim a left join management_claim.master_status_internal b 
                on a.status_internal = b.id left join management_claim.registrasi_program c 
                on a.id_registrasi = c.id left join management_claim.ajuan_claim d 
	            on a.id_ajuan = d.id left join site.master_supplier e 
	            on c.supp = e.supp left join management_claim.master_kategori f 
	            on c.kategori = f.id
            where a.on_duty_finish = 0 and d.deleted_at is null $params_kategori $params_periode
            GROUP BY a.status_internal, c.supp, c.kategori
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_log_aktivitas_group_status_internal_and_principal_and_kategori_and_noajuan($from='', $to='', $kategori = '', $breakdown = '')
    {
        if ($from && $to) {
            $params_periode = "and date(c.from) >= '$from' and date(c.to) <= '$to'";
        }
        else{
            $params_periode = "";
        }

        if ($kategori == 'all' || $kategori == '') {
            $params_kategori = "";
        }
        else{
            $params_kategori = "and c.kategori = '$kategori'";
        }

        if ($breakdown) {
            $params_breakdown = "GROUP BY c.$breakdown";
        }
        else{
            $params_breakdown = "";
        }

        $query = "
            select 	a.status_internal, b.nama_status as nama_status_internal, count(*) as count, c.kategori, c.supp, e.namasupp,  f.nama_kategori, d.id, d.nomor_ajuan, d.site_code, g.branch_name, g.nama_comp, c.nomor_surat, c.nama_program, c.signature as signature_program, d.signature as signature_ajuan, h.username, a.time_response, a.duedate_response
            from management_claim.log_aktivitas_claim a left join management_claim.master_status_internal b 
                on a.status_internal = b.id left join management_claim.registrasi_program c 
                on a.id_registrasi = c.id left join management_claim.ajuan_claim d 
	            on a.id_ajuan = d.id left join site.master_supplier e 
	            on c.supp = e.supp left join management_claim.master_kategori f 
	            on c.kategori = f.id left join site.master_site g 
	            on d.site_code = g.site_code left join mpm.user h 
	            on a.pic_on_duty = h.id
            where a.on_duty_finish = 0 and d.deleted_at is null $params_kategori $params_periode
            GROUP BY a.status_internal, c.supp, c.kategori, d.id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_log_aktivitas_by_status_internal($status_internal)
    {
        $query = "
           select 	b.nama_status as nama_status_internal, e.nama_status, c.username, a.id_ajuan, a.id_registrasi, d.nomor_ajuan,
                    a.on_duty_finish, d.site_code, f.nama_comp, g.nama_program, g.nomor_surat, g.kategori, h.nama_kategori, 
                    g.supp, i.namasupp, a.updated_at, g.signature as signature_program, d.signature as signature_ajuan, g.upload_pdf
            from management_claim.log_aktivitas_claim a left join management_claim.master_status_internal b 
                on a.status_internal = b.id left join site.master_user c 
                on a.pic_on_duty = c.id left join management_claim.ajuan_claim d 
                on a.id_ajuan = d.id left join management_claim.master_status e 
                on a.status = e.id left join site.master_site f 
                on d.site_code = f.site_code left join management_claim.registrasi_program g 
                on a.id_registrasi = g.id left join management_claim.master_kategori h 
                on g.kategori = h.id left join site.master_supplier i 
                on g.supp = i.supp
            where a.on_duty_finish = 0 and a.status_internal = $status_internal and d.deleted_at is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_log_aktivitas_by_status_internal_and_kategori($status_internal, $kategori)
    {
        $query = "
           select 	b.nama_status as nama_status_internal, e.nama_status, c.username, a.id_ajuan, a.id_registrasi, d.nomor_ajuan,
                    a.on_duty_finish, d.site_code, f.nama_comp, g.nama_program, g.nomor_surat, g.kategori, h.nama_kategori, 
                    g.supp, i.namasupp, a.updated_at, g.signature as signature_program, d.signature as signature_ajuan, g.upload_pdf
            from management_claim.log_aktivitas_claim a left join management_claim.master_status_internal b 
                on a.status_internal = b.id left join site.master_user c 
                on a.pic_on_duty = c.id left join management_claim.ajuan_claim d 
                on a.id_ajuan = d.id left join management_claim.master_status e 
                on a.status = e.id left join site.master_site f 
                on d.site_code = f.site_code left join management_claim.registrasi_program g 
                on a.id_registrasi = g.id left join management_claim.master_kategori h 
                on g.kategori = h.id left join site.master_supplier i 
                on g.supp = i.supp
            where a.on_duty_finish = 0 and a.status_internal = $status_internal and d.deleted_at is null and g.kategori = '$kategori'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_registrasi_by_from_to($from, $to)
    {
        $query = "
            select a.*, b.namasupp, c.nama_kategori, d.username as created_by_username, e.count
            from management_claim.registrasi_program a left join site.master_supplier b 
                on a.supp = b.supp left join management_claim.master_kategori c 
                on a.kategori = c.id left join site.master_user d 
                on a.created_by = d.id left join (
                    select a.id_program, count(*) as count
                    from management_claim.ajuan_claim a
                    where a.status_keikutsertaan = 1
                    group by a.id_program
                )e on a.id = e.id_program
            where date(a.from) >= '$from' and date(a.to) <= '$to' and a.deleted is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query); 
    }

    public function insert_header_import($data)
    {
        $this->db->insert('management_claim.header_import', $data);
        return $this->db->insert_id();
    }

    public function insert_bonus_barang($data)
    {
        $this->db->insert('management_claim.import_bonus_barang', $data);
        return $this->db->insert_id();
    }

    public function get_count_validasi_failed_bonus_barang($id_header){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.id_header = $id_header and a.validasi_row > 0
        ";
        return $this->db->query($query);
    }

    public function get_count_validasi_failed_diskon($id_header){
        $query = "
            select count(*) as total
            from management_claim.import_diskon a 
            where a.id_header = $id_header and a.validasi_row > 0
        ";
        return $this->db->query($query);
    }

    public function get_count_validasi_success_bonus_barang($id_header){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.id_header = $id_header and a.validasi_row = 0
        ";
        return $this->db->query($query);
    }

    public function get_count_validasi_success_diskon($id_header){
        $query = "
            select count(*) as total
            from management_claim.import_diskon a 
            where a.id_header = $id_header and a.validasi_row = 0
        ";
        return $this->db->query($query);
    }

    public function get_count_import_bonus_barang($id_header){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.id_header = $id_header
        ";
        return $this->db->query($query);
    }

    public function get_count_import_diskon($id_header){
        $query = "
            select count(*) as total
            from management_claim.import_diskon a 
            where a.id_header = $id_header
        ";
        return $this->db->query($query);
    }

    public function get_sum_import_bonus_barang($id_header){
        $query = "
            select sum(a.qty_jual) as total_qty_jual, sum(a.qty_bonus) as total_qty_bonus, sum(a.value_jual) as total_value_jual, sum(a.value_bonus) as total_value_bonus 
            from management_claim.import_bonus_barang a 
            where a.id_header = $id_header
        ";
        return $this->db->query($query);
    }

    public function get_preview_import_bonus_barang($id_header){
        $query = "
            select *
            from management_claim.import_bonus_barang a
            where a.id_header = $id_header
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die; 
        
        return $this->db->query($query);
    }

    public function get_preview_import_bonus_barang_failed($id_header){
        $query = "
            select *
            from management_claim.import_bonus_barang a
            where a.id_header = $id_header and a.validasi_row > 0
            limit 100
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        
        return $this->db->query($query);
    }

    public function get_ajuan_claim_join_registrasi($id_program)
    {
        $query = "
            select 	a.*, b.branch_name, b.nama_comp, c.kategori, a.deleted_at, c.nomor_surat, c.nama_program
            from management_claim.ajuan_claim a left join site.master_site b 
                on a.site_code = b.site_code left join (
                    select a.id, a.kategori, a.nomor_surat, a.nama_program
                    from management_claim.registrasi_program a 
                    where a.id in ($id_program)
                )c on a.id_program = c.id
            where a.id_program in ($id_program)
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_registrasi_program_by_only_id_program($id_program)
    {
        $query = "
            select *
            from management_claim.registrasi_program a 
            where a.id in ($id_program)
        ";
        return $this->db->query($query);
    }

    public function get_registrasi_program_by_only_id_program_groupby_kategori($id_program)
    {
        $query = "
            select *
            from management_claim.registrasi_program a 
            where a.id in ($id_program)
            group by a.kategori
        ";
        return $this->db->query($query);
    }

    public function insert_temp_master_outlet($site_code, $tahun, $bulan, $kode_type)
    {
        $this->db->query("delete from management_claim.temp_master_outlet where created_by = '$this->created_by' ");

        $query = "
            insert into management_claim.temp_master_outlet
            select 	'', concat(a.kode_comp, a.nocab) as site_code, 
                    concat(a.kode_comp, a.kode_lang) as kode_outlet, a.nama_lang as nama_outlet_fi, 
                    a.kode_type as kode_type_fi, a.kodesalur as kode_class_fi,
                    '$this->created_at' as created_at, '$this->created_by' as created_by
            from data$tahun.fi a 
            where a.bulan in ($bulan) and concat(a.kode_comp, a.nocab) = '$site_code' and a.kode_type = '$kode_type'
            group by concat(a.kode_comp, a.kode_lang)
            
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_temp_master_outlet()
    {
        $query = "
            select *
            from management_claim.temp_master_outlet
            where created_by = '$this->created_by'
            order by created_at desc
        ";
        
        return $this->db->query($query);
    }

    public function get_temp_master_outlet_join_master_outlet($site_code)
    {
        $query = "
            select a.*, if(a.kode_outlet = b.kode_outlet, 'registered',null) as status_register
            from management_claim.temp_master_outlet a left join (
                select * from management_claim.master_outlet a
                where a.site_code = '$site_code'
            )b on a.kode_outlet = b.kode_outlet
            where a.created_by = '$this->created_by' 
            order by a.created_at desc 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        
        return $this->db->query($query);
    }

    public function get_temp_master_outlet_join_master_outlet_where_null($site_code)
    {
        $query = "
            select a.*, if(a.kode_outlet = b.kode_outlet, 'registered',null) as status_register
            from management_claim.temp_master_outlet a left join (
                select * from management_claim.master_outlet a
                where a.site_code = '$site_code'
            )b on a.kode_outlet = b.kode_outlet
            where a.created_by = '$this->created_by' and b.kode_outlet is null
            order by a.created_at desc 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        
        return $this->db->query($query);
    }

    public function get_temp_master_outlet_by_kode_outlet($kode_outlet)
    {
        $query = "
            select *
            from management_claim.temp_master_outlet
            where created_by = '$this->created_by' and kode_outlet = '$kode_outlet'
            order by created_at desc
        ";
        return $this->db->query($query);
    }

    public function insert_master_outlet($data)
    {
        $this->db->insert('management_claim.master_outlet', $data);
        return $this->db->insert_id();
    }

    public function get_master_outlet_by_kode_outlet($kode_outlet)
    {
        $query = "
            select *
            from management_claim.master_outlet a
            where a.kode_outlet = '$kode_outlet'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_outlet($site_code)
    {
        $query = "
            select *
            from management_claim.master_outlet a
            where a.site_code = '$site_code'
        ";
        echo "<pre>";
        echo $query;
        echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_outlet_by_created_by($id)
    {
        $query = "
            select a.*, b.branch_name, b.nama_comp
            from management_claim.master_outlet a left join (
                select *
                from site.master_site a 
            )b on a.site_code = b.site_code
            where a.created_by = $id
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_outlet_lengkap($site_code)
    {
        $query = "
            select *
            from management_claim.master_outlet a
            where a.site_code = '$site_code' and a.no_ktp is not null and a.file_ktp is not null and a.no_npwp is not null and a.file_npwp is not null and a.alamat is not null and a.no_telp is not null
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_outlet_lengkap_by_created_by($id)
    {
        $query = "
            select *
            from management_claim.master_outlet a
            where a.created_by = '$id' and a.no_ktp is not null and a.file_ktp is not null and a.no_npwp is not null and a.file_npwp is not null and a.alamat is not null and a.no_telp is not null
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_master_outlet($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('management_claim.master_outlet', $data);
        return $this->db->affected_rows();
    }

    public function get_master_outlet_by_signature($signature)
    {
        $query = "
            select *
            from management_claim.master_outlet a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_loyalty_peserta_by_id_program($id_program, $site_code)
    {
        $query = "
            select a.*, b.kode_outlet, b.file_skp, b.paket, c.file_ktp, c.file_npwp, c.signature as signature_outlet, b.signature as signature_detail, c.nama_outlet
            from management_claim.loyalty_peserta a inner join (
                select *
                from management_claim.loyalty_peserta_detail a
                where a.deleted_at is null
            )b on a.id = b.id_ref left join (
                select *
                from management_claim.master_outlet a
                where 	a.created_by = '$this->created_by' and 
                        a.no_ktp is not null and a.file_ktp is not null and a.no_npwp is not null and a.file_npwp is not null and a.alamat is not null and a.no_telp is not null 
            )c on b.kode_outlet = c.kode_outlet 
            where a.created_by = '$this->created_by' and a.id_program = $id_program
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_loyalty_peserta_by_id_program_where_skp_lengkap($id_program, $site_code)
    {
        $query = "
            select a.*, b.kode_outlet, b.file_skp, b.paket, b.signature as signature_detail, c.nama_outlet
            from management_claim.loyalty_peserta a left join (
                select *
                from management_claim.loyalty_peserta_detail a
                where a.deleted_at is null
            )b on a.id = b.id_ref left join (
                select *
                from management_claim.master_outlet a
                where 	a.created_by = '$this->created_by' and 
                        a.no_ktp is not null and a.file_ktp is not null and a.no_npwp is not null and a.file_npwp is not null and a.alamat is not null and a.no_telp is not null 
            )c on b.kode_outlet = c.kode_outlet 
            where a.created_by = '$this->created_by' and a.id_program = $id_program and (b.file_skp is not null or b.paket is not null)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_loyalty_peserta_by_id_program_and_created_by($id_program, $created_by)
    {
        $query = "
            select *
            from management_claim.loyalty_peserta a 
            where a.id_program = $id_program and a.created_by = $created_by
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_peserta_loyalty($data)
    {
        $this->db->insert('management_claim.loyalty_peserta', $data);
        return $this->db->insert_id();
    }

    public function update_peserta_loyalty($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('management_claim.loyalty_peserta', $data);
        return $this->db->affected_rows();
    }

    public function get_loyalty_peserta_detail_by_id_ref_and_kode_outlet($id_ref, $kode_outlet)
    {
        $query = "
            select *
            from management_claim.loyalty_peserta_detail a 
            where a.id_ref = $id_ref and a.kode_outlet = '$kode_outlet' and a.deleted_at is null
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_loyalty_peserta_detail_by_id_ref_and_kode_outlet_any_data($id_ref, $kode_outlet)
    {
        $query = "
            select *
            from management_claim.loyalty_peserta_detail a 
            where a.id_ref = $id_ref and a.kode_outlet = '$kode_outlet'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function insert_peserta_loyalty_detail($data)
    {
        $this->db->insert('management_claim.loyalty_peserta_detail', $data);
        return $this->db->insert_id();
    }

    public function get_master_type()
    {
        $query = "
            select *
            from mpm.tbl_bantu_type a 
        ";
        return $this->db->query($query);
    }

    public function get_loyalty_peserta_detail_by_signature($signature, $site_code)
    {
        $query = "
            select a.*, b.nama_outlet, b.no_ktp, b.file_ktp, b.no_npwp, b.file_npwp, b.alamat, b.no_telp
            from management_claim.loyalty_peserta_detail a left join (
                select a.*
                from management_claim.master_outlet a 
                where a.site_code = '$site_code'
            )b on a.kode_outlet = b.kode_outlet 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_loyalty_peserta_detail_by_signature_and_created_by($signature, $created_by)
    {
        $query = "
            select a.*, b.nama_outlet, b.no_ktp, b.file_ktp, b.no_npwp, b.file_npwp, b.alamat, b.no_telp
            from management_claim.loyalty_peserta_detail a left join (
                select a.*
                from management_claim.master_outlet a 
                where a.created_by = '$created_by'
            )b on a.kode_outlet = b.kode_outlet 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_registrai_program_by_signature_simple($signature)
    {
        $query = "
            select *
            from management_claim.registrasi_program a 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function update_peserta_loyalty_detail($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('management_claim.loyalty_peserta_detail', $data);
        return $this->db->affected_rows();
    }

    public function get_user_by_username($username)
    {
        $query = "
            select *
            from mpm.user a 
            where a.username = '$username'
        ";
        return $this->db->query($query);
    }

    public function export_peserta_loyalty($id_program)
    {
        $query = "
            select e.nomor_surat, e.nama_program, d.branch_name, d.nama_comp, c.site_code, b.kode_outlet, c.nama_outlet, b.file_skp, b.paket, if(a.updated_at is null, date(a.created_at), date(a.updated_at)) as updated_at
            from management_claim.loyalty_peserta a left join (
                select *
                from management_claim.loyalty_peserta_detail a
                where a.deleted_at is null
            )b on a.id = b.id_ref left join (
                select *
                from management_claim.master_outlet a
            )c on b.kode_outlet = c.kode_outlet left join (
                select a.*
                from site.master_site a 
            )d on c.site_code = d.site_code left join (
                select *
                from management_claim.registrasi_program a 
                where a.kategori = 1
            )e on a.id_program = e.id
            where a.id_program = $id_program
        ";
        return $this->db->query($query);
    }

    public function get_log_aktivitas_by_id_ajuan_new($id_ajuan)
    {
        $query = "
            select *
            from management_claim.log_aktivitas_claim a 
            where a.id_ajuan = $id_ajuan and a.on_duty_finish = 0 and a.pic_on_duty in (444, 812, 18)
        ";
        return $this->db->query($query);
    }

    public function get_master_region_nka_by_key_account($key_account)
    {
        // echo "key_account : $key_account";
        $query = "
            SELECT 
                a.*,
                b.username as username_kam, b.email as email_kam,
                c.username as username_mpm, c.email as email_mpm,
                d.username as username_admin_mpm, d.email as email_admin_mpm
            FROM
            (
                select *
                from management_claim.master_region_nka a 
                where a.key_account = '$key_account' and a.deleted_at is null
            )a
            left join site.master_user b 
                on a.pic_principal = b.id
            left join site.master_user c
                on a.pic_mpm = c.id
            left join site.master_user d
                on a.pic_admin_mpm = d.id
        ";

        return $this->db->query($query);
    }

    public function get_ajuan_claim_nka_by_search($advanced = null)
    {
        $periode_start  =  $advanced['from'];
        $periode_end    =  $advanced['to'];
        $kategori    =  $advanced['kategori'];
        $key_account    =  $advanced['key_account'];

        $params_periode = $periode_start != null ? "WHERE a.periode_start BETWEEN '$periode_start' and '$periode_end'" : '';
        $params_kategori = $kategori != null ? " and a.kategori = '$kategori'" : ''; 
        $params_key_account = $key_account != null ? " and a.key_account = '$key_account'" : ''; 

        $query = "
            SELECT a.*, b.username as on_duty_name
            FROM management_claim.ajuan_claim_nka a
            LEFT JOIN site.master_user b 
                on a.pic_on_duty = b.id
            $params_periode $params_kategori $params_key_account
        ";        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_ajuan_claim_nka()
    {
        $query = "
            SELECT *
            FROM management_claim.ajuan_claim_nka a
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_ajuan_claim_nka_by_username($username)
    {
        $query = "
            SELECT a.*, b.username as on_duty_name
            FROM management_claim.ajuan_claim_nka a
            LEFT JOIN site.master_user b 
                on a.pic_on_duty = b.id
            WHERE a.site_code = '$username'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        // die;
        return $this->db->query($query);
    }

  public function get_ajuan_claim_nka_by_username_userid($username, $userid, $from = "", $to = "", $channel = "", $kategori = "")
  {
    // Escape input untuk mencegah SQL Injection
    $username = $this->db->escape_str($username);
    $userid = $this->db->escape_str($userid);
    $from = $this->db->escape_str($from);
    $to = $this->db->escape_str($to);
    $channel = $this->db->escape_str($channel);
    $kategori = $this->db->escape_str($kategori);
    
    // Build kondisi WHERE
    $where_conditions = [];
    
    // Hak akses (kecuali suffy)
    if($this->session->userdata('username') != 'suffy' && $this->session->userdata('username') != 'penta-ho' && $this->session->userdata('username') != 'PENTA-HO') 
    {
        $where_conditions[] = "(a.site_code = '$username' or a.pic_on_duty = $userid or a.pic_principal = $userid or a.pic_mpm = $userid or a.pic_admin_mpm = $userid)";
    }
    
    // Filter tanggal
    if(!empty($from) && !empty($to)) {
        $where_conditions[] = "DATE(a.periode_start) BETWEEN '$from' AND '$to'";
    }
    
    // Filter channel
    if(!empty($channel) && $channel != 'all') {
        $where_conditions[] = "a.channel = '$channel'";
    }
    
    // Filter kategori
    if(!empty($kategori) && $kategori != 'all') {
        $where_conditions[] = "a.kategori = '$kategori'";
    }
    
    $query = "
        select 	a.nama_comp, a.nomor_ajuan, a.nomor_klaim, a.nomor_invoice,
                a.channel, a.kategori, a.key_account, a.periode_start, a.periode_end,
                a.site_code, a.pic_nama, a.pic_email, a.keterangan,
                a.nominal_dpp, a.`status`, a.nama_status, 
                a.pic_principal, a.username_principal, a.email_principal, a.principal_status, a.principal_nama_status, a.principal_keterangan,
                a.pic_mpm, a.username_mpm, a.email_mpm, a.mpm_status, a.mpm_nama_status, a.mpm_keterangan,
                a.pic_admin_mpm, a.username_admin_mpm, a.email_admin_mpm, a.admin_mpm_status, a.admin_mpm_nama_status, a.admin_mpm_keterangan,
                a.pic_on_duty, b.username as on_duty_name, 
                date(a.created_at) as created_at, date(a.principal_at) as principal_at, date(a.mpm_at) as mpm_at, date(a.admin_mpm_at) as admin_mpm_at,
                date(a.revisi_at) as revisi_at,
                datediff(date(a.principal_at), date(a.created_at)) as lt_principal,
                datediff(date(a.mpm_at), date(a.principal_at)) as lt_mpm,
                datediff(date(a.admin_mpm_at), date(a.mpm_at)) as lt_admin_mpm,
                datediff(date(a.revisi_at), date(a.admin_mpm_at)) as lt_revisi, a.signature
        from management_claim.ajuan_claim_nka a
        left join site.master_user b on a.pic_on_duty = b.id
        left join site.master_site c on a.site_code = c.site_code
    ";
    if(!empty($where_conditions)) {
        $query .= " where " . implode(" AND ", $where_conditions);
    }

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    // die;
    
    return $this->db->query($query);
  }

  public function get_ajuan_claim_nka_for_export($username, $userid, $from, $to, $channel, $kategori)
  {        
    if($username != 'suffy' && $username != 'penta-ho' && $username != 'PENTA-HO') 
    {
      $params_username = "where a.site_code = '$username' or a.pic_on_duty = $userid or a.pic_principal = $userid or a.pic_mpm = $userid or a.pic_admin_mpm = $userid";
    }else{
      $params_username = "where a.deleted_at is null ";
    }

    if(!empty($from) && !empty($to)) {
      $params_tanggal = "and DATE(a.periode_start) BETWEEN '$from' AND '$to'";
    }
    
    if(!empty($channel) && $channel != 'all') {
      $params_channel = "and a.channel = '$channel'";
    }else{
      $params_channel = "";
    }
    
    if(!empty($kategori) && $kategori != 'all') {
      $params_kategori = "and a.kategori = '$kategori'";
    }else{
      $params_kategori = "";
    }
    
    $query = "
        select 	a.nama_comp, a.nomor_ajuan, a.nomor_klaim, a.nomor_invoice,
                a.channel, a.kategori, a.key_account, a.periode_start, a.periode_end,
                a.site_code, a.pic_nama, a.pic_email, a.keterangan,
                a.nominal_dpp, a.`status`, a.nama_status, 
                a.pic_principal, a.username_principal, a.email_principal, a.principal_status, a.principal_nama_status, a.principal_keterangan,
                a.pic_mpm, a.username_mpm, a.email_mpm, a.mpm_status, a.mpm_nama_status, a.mpm_keterangan,
                a.pic_admin_mpm, a.username_admin_mpm, a.email_admin_mpm, a.admin_mpm_status, a.admin_mpm_nama_status, a.admin_mpm_keterangan,
                a.pic_on_duty, b.username as on_duty_name, 
                date(a.created_at) as created_at, date(a.principal_at) as principal_at, date(a.mpm_at) as mpm_at, date(a.admin_mpm_at) as admin_mpm_at,
                date(a.revisi_at) as revisi_at,
                datediff(date(a.principal_at), date(a.created_at)) as lt_principal,
                datediff(date(a.mpm_at), date(a.principal_at)) as lt_mpm,
                datediff(date(a.admin_mpm_at), date(a.mpm_at)) as lt_admin_mpm,
                datediff(date(a.revisi_at), date(a.admin_mpm_at)) as lt_revisi
        from management_claim.ajuan_claim_nka a
        left join site.master_user b on a.pic_on_duty = b.id
        left join site.master_site c on a.site_code = c.site_code
        $params_username $params_tanggal $params_channel $params_kategori
    ";

    // $query = "select 1";

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    // die;

    
    return $this->db->query($query);
  }

  public function test()
  {
    $query = "
            select 	a.id
            from management_claim.registrasi_program a 
            limit 10
        ";

        return $this->db->query($query);
  }


    // public function get_ajuan_claim_nka_by_signature($signature)
    // {
    //     $query = "
    //         SELECT a.*, b.branch_name, b.nama_comp, b.email as email_dp , c.username as username_kam, d.username as username_mpm, e.username as username_admin_mpm
    //         FROM
    //         (
    //             SELECT *
    //             FROM management_claim.ajuan_claim_nka a
    //             WHERE a.signature = '$signature'
    //         )a LEFT JOIN
    //         (
    //             SELECT a.*
    //             FROM site.master_site_with_user a
    //         )b on a.site_code = b.site_code
    //         LEFT JOIN
    //         (
    //             SELECT a.id, a.username
    //             FROM mpm.user a
    //         )c on a.pic_principal_area = c.id
    //         LEFT JOIN
    //         (
    //             SELECT a.id, a.username
    //             FROM mpm.user a
    //         )d on a.pic_mpm = d.id
    //         LEFT JOIN
    //         (
    //             SELECT a.id, a.username
    //             FROM mpm.user a
    //         )e on a.pic_admin_mpm = e.id
    //     ";

    //     echo "<pre>";
    //     print_r($query);
    //     echo "</pre>";
    //     // die;
    //     return $this->db->query($query);
    // }

    public function get_ajuan_claim_nka_by_signature($signature)
    {
        $query = "
            select  a.id, a.nomor_ajuan, a.nomor_klaim, a.nomor_invoice, a.channel, a.kategori,
                    a.key_account, a.keterangan, a.nominal_dpp, a.pic_nama, a.pic_email,
                    a.site_code, a.nama_comp, a.periode_start, a.periode_end,
                    a.attachment, a.`status`, a.nama_status, a.pic_on_duty, b.username as username_on_duty,
                    a.pic_principal, a.username_principal, a.email_principal, a.principal_keterangan, 
                    a.principal_at, a.principal_status, a.principal_nama_status,
                    a.pic_mpm, a.username_mpm, a.email_mpm, a.mpm_keterangan, a.mpm_at, a.mpm_status, a.mpm_nama_status,
                    a.pic_admin_mpm, a.username_admin_mpm, a.email_admin_mpm, 
                    a.admin_mpm_keterangan, a.admin_mpm_at, a.admin_mpm_status, a.admin_mpm_nama_status,
                    a.created_at, a.created_by, a.deleted_at, a.updated_at, a.updated_by, a.signature
            from management_claim.ajuan_claim_nka a left join site.master_user b 
			on a.pic_on_duty = b.id
            where a.signature = '$signature' 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_log_aktivitas_nka_by_id_ajuan($id_ajuan)
    {
        if ($id_ajuan) {
            $params = "where a.id_ajuan = $id_ajuan";
        }else{
            $params = "";
        }

        $query = "
            select 	a.*
            from management_claim.log_aktivitas_claim_nka a 
            $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_username($id)
    {
        $query = "
            select 	a.username, a.email
            from mpm.user a 
            where a.id in ($id)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_log_aktivitas_nka_by_id_ajuan_with_sorting_desc($id_ajuan)
    {
        if ($id_ajuan) {
            $params = "where a.id_ajuan = $id_ajuan";
        }else{
            $params = "";
        }

        $query = "
            select 	a.*, b.username, b.email, c.username as on_duty_username, c.email as on_duty_email
            from management_claim.log_aktivitas_claim_nka a 
            left join site.master_user b 
                on a.created_by = b.id
            left join site.master_user c
                on a.pic_on_duty = c.id
            $params
            ORDER by a.id desc
            LIMIT 1 OFFSET 1;

        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function routing_akses_nka_by_userid($userid)
    {
        $query = "
            Select *
            From management_claim.routing_akses_nka a
            where a.userid = $userid
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function generate_nka($created_at)
    {
        $bulan_now = date('m', strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            SELECT 
                a.nomor_ajuan,
                CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(a.nomor_ajuan, '/', 1), '-', -1) AS UNSIGNED) as urut,
                a.created_by, 
                a.created_at
            FROM management_claim.ajuan_claim_nka a
            WHERE YEAR(a.created_at) = $tahun_now 
            AND MONTH(a.created_at) = $bulan_now 
            AND a.nomor_ajuan IS NOT NULL
            ORDER BY a.id DESC
            LIMIT 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $nomor_ajuan_current = $this->db->query($query);

        if ($nomor_ajuan_current->num_rows() > 0) {
            $params_urut = $nomor_ajuan_current->row()->urut + 1;

            // format dengan leading zero sampai 3 digit
            $params_urut_format = str_pad($params_urut, 4, "0", STR_PAD_LEFT);

            $generate = "CLM_NKA-$params_urut_format/MPM/$romawi/$tahun_now";
        } else {
            $generate = "CLM_NKA-0001/MPM/$romawi/$tahun_now";
        }

        return $generate;
    }

    public function insert_and_getId($table, $data) {
        $this->db->insert($table, $data);                 // insert ke tabel
        return $this->db->insert_id();                     // ambil ID terakhir
    }

  public function get_master_kategori_nka($channel)
  {
    $query = "
      select a.*
      from management_claim.master_kategori_nka a
      where a.deleted_at is null and a.channel ='$channel'
      order by a.nama_kategori
    ";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    return $this->db->query($query);
  }

    public function get_master_region_nka_by_channel_and_key_account($channel, $key_account)
    {
        if ($key_account == null || $key_account == '') {
            $params = "";
        } else {
            $params = "and a.key_account = '$key_account'";
        }

        $query = "
            select 	a.channel, a.key_account, a.pic_principal, a.pic_mpm, a.pic_admin_mpm,
                    b.username as username_principal, b.email as email_principal,
                    c.username as username_mpm, c.email as email_mpm,
                    d.username as username_admin_mpm, d.email as email_admin_mpm
            from management_claim.master_region_nka a 
                left join site.master_user b 
                    on a.pic_principal = b.id
                left join site.master_user c 
                    on a.pic_mpm = c.id
                left join site.master_user d 
                    on a.pic_admin_mpm = d.id
            where a.deleted_at is null and a.channel = '$channel' $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_master_region_nka_by_channel_key_account_userid_role($channel, $key_account, $userid, $role)
    {
        if ($key_account == null || $key_account == '') {
            $params_key_account = "";
        } else {
            $params_key_account = "and a.key_account = '$key_account'";
        }
        

        if ($role == 'kam') {
            $params_role = "and a.pic_principal = $userid";
        } else if ($role == 'mpm') {
            $params_role = "and a.pic_mpm = $userid";
        } else {
            $params_role = "and a.pic_admin_mpm = $userid";
        }

        $query = "
            select *
            from management_claim.master_region_nka a 
            where a.deleted_at is null and a.channel = '$channel' 
            $params_key_account
            $params_role
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

  public function get_key_account()
  {
    $query = "
      select a.key_account
      from management_claim.master_region_nka a
      WHERE a.key_account is not null and a.pic_principal is not null and a.channel = 'NKA'
      GROUP BY a.key_account
      ORDER BY a.key_account
    ";
    return $this->db->query($query);
  }

  public function get_key_account_by_channel($channel)
  {
    $query = "
      select a.id, a.key_account, channel
      from management_claim.master_region_nka a
      WHERE a.key_account is not null and a.pic_principal is not null and a.channel = '$channel'
      GROUP BY a.key_account
      ORDER BY a.key_account
    ";
    return $this->db->query($query);
  }

    public function get_registrasi_program_by_supp_kategori_periode_none_ajuan($supp, $kategori, $from, $to)
    {
        if($kategori == 'all')
        {
            $params_kategori = "";
        }else{
            $params_kategori = "and a.kategori = '$kategori'";
        }
        $query = "
            select a.id, a.kategori, a.nomor_surat, a.nama_program, b.nama_kategori, c.namasupp, a.from, a.to
            from management_claim.registrasi_program a left join (
                select a.id, a.nama_kategori
                from management_claim.master_kategori a  
            )b on a.kategori = b.id left join (
                select a.supp, a.namasupp
                from site.master_supplier a 
            )c on a.supp = c.supp
            where a.supp = '$supp' $params_kategori and date(a.from) between '$from' and '$to'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_availability($id_program, $site_code)
    {
        $query = "
            select 	b.nomor_surat, b.nama_program, a.site_code, a.branch_name, a.nama_comp, 
                    c.nomor_ajuan, c.email_pengirim, c.nama_pengirim, c.nama_status, c.status_keikutsertaan,
                    c.on_duty, c.created_at, c.updated_at
            from site.master_site a join (
                select a.id, a.nomor_surat, a.nama_program, a.kategori, b.nama_kategori
                from management_claim.registrasi_program a left join management_claim.master_kategori b 
                    on a.kategori = b.id
                where a.id in ($id_program)
            )b
            left join (
                select 	a.id, a.id_program, a.nomor_ajuan, a.site_code, 
                        a.email_pengirim, a.nama_pengirim,
                        a.`status`, a.status_internal, if(a.status_keikutsertaan = 1, 'Ya', if(a.status_keikutsertaan = 0, 'Tidak', '')) as status_keikutsertaan,
                        c.username as on_duty, a.created_at, a.updated_at, d.nama_status
                from management_claim.ajuan_claim a inner join (
                    select a.site_code, a.branch_name, a.nama_comp
                    from site.master_site a 
                    where a.site_code in ($site_code)
                )b on a.site_code = b.site_code left join (
                    select a.id, a.username
                    from site.master_user a 
                )c on a.pic_userid = c.id left join (
                    select a.id, a.nama_status
                    from management_claim.master_status_internal a 
                )d on a.status_internal = d.id
                where a.id_program in ($id_program)
            )c on a.site_code = c.site_code
            where a.site_code not like 'PENTA%' and a.site_code not like 'MPI%'
            ORDER BY b.id desc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
        
    }

    public function get_site_code_by_username($username)
    {
        $query = "
            select a.site_code, a.nama_comp
            from site.master_site a 
            where a.site_code = '$username'
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

  public function get_master_kategori_list()
  {
    $query = "
        select a.nama_kategori
        from management_claim.master_kategori_nka a 
    ";
    // echo "<pre>";
    // echo $query;
    // echo "</pre>";
    return $this->db->query($query);
  }

    public function get_status_claim_nka($status)
    {
        $query = "
            select a.`status`, a.nama_status
            from management_claim.master_status_claim_nka a
            where a.status = $status
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

}