<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_inventory extends CI_Model
{

    // public function get_sitecode($id){

    //     if ($this->session->userdata('level') == 4)
    //     {
    //         $query = "
    //             select a.username, b.branch_name, b.kode_comp, b.nama_comp, b.site_code
    //             from mpm.user a INNER JOIN
    //             (
    //                 select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.kode_comp
    //                 from mpm.tbl_tabcomp a
    //                 where a.status = 1 and a.status_claim = 1
    //                 GROUP BY concat(a.kode_comp, a.nocab)
    //             )b on a.username = b.kode_comp
    //             where a.id =$id
    //         ";

    //         echo "<pre>";
    //         print_r($query);
    //         echo "</pre>";

    //         die;

    //         return  $this->db->query($query);


    //     }else
    //     {
    //         return $this->db->query("select 1");
    //     }
    // }

  public function get_sitecode()
  {
    $id = $this->session->userdata('id');
    $username = $this->session->userdata('username');
    if($username == 'suffy' || $username == 'melinda' || $username == 'milla')
    {
        $params_where = "";
    }else{
        $params_where = "where a.userid = $id";
    }

    $query = "
        select site_code, a.branch_name, a.nama_comp
        from site.master_site_with_user a
        $params_where
    ";

    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    // die;
    $proses = $this->db->query($query);
    return $proses;
  }

    public function get_pengajuan($signature = '', $kode_alamat = "", $advanced = ""){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }

        if ($kode_alamat == "'MPI-01'") {
            $params_alamat = " and a.site_code like 'MPI-%'";
        }else if($kode_alamat != "") {
            $params_alamat = "and a.site_code in ($kode_alamat)";
        }else{
            $params_alamat = "";
        }

        if ($this->session->userdata('username') == 'hendy_deltomed' || $this->session->userdata('username') == 'janny' || $this->session->userdata('username') == 'celvin' || $this->session->userdata('username') == 'etta' || $this->session->userdata('username') == 'indrampm' || $this->session->userdata('username') == 'indravalentino' || $this->session->userdata('username') == 'zul') {
            $session_supp = '001';
        }else{

            $session_supp = $this->session->userdata('supp');
        }

        if ($session_supp == '000' || $session_supp == null) {
            $params_supp = "";
        }else{
            $supp_paramsx = '';
            $get_supp = $this->get_supp_principal_akses($this->session->userdata('id'));
            if ($get_supp->num_rows()>0) {
                foreach ($get_supp->result() as $a) {
                    $supp_params = $a->supp;
                    $supp_paramsx.=",'".$supp_params."'";
                }
                $akses_supp = preg_replace('/,/', '', $supp_paramsx,1);
                $params_supp = "and a.supp in ($akses_supp)";
            }else{
                $params_supp="and a.supp in ($session_supp)";
            }
        }

        if ($advanced) { // memakai fitur pencarian
            // echo "pencarian started";
            // die;

            if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'aletheia') {
                $params_linda = "";
            }else{
                $params_linda = "";
            }

            if ($this->session->userdata('username') == 'rani') {
                $params_rani = "";
            }else{
                $params_rani = "";
            }

            $from = $advanced["from"];
            $to = $advanced["to"];
            $status = $advanced["status"];

            if ($status == 0) {
                $params_status = "";
            }else{
                $params_status = "and a.status = $status";
            }
            $params_date = "and (a.tanggal_pengajuan between '$from 00:00:00' and '$to 23:59:59')";

            $limit = "";
        }else{
            //  echo "pencarian shutdown";
            // die;
            $params_date = "";
            $params_status = "";

            if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'aletheia') {
                $params_linda = "";
            }else{
                $params_linda = "";
            }

            if ($this->session->userdata('username') == 'rani') {
                $params_rani = "and a.status in (8,9,11,12,13)";
            }else{
                $params_rani = "";
            }

            $limit = "Limit 1000";
        }

        // jika user pabrik / yuni
        if ($this->session->userdata('username') == 'yuni') {
            $params_pabrik = "and a.status in (6,8)";
        }else{
            $params_pabrik = "";
        }



        $query = "
            select 	a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.key_account, date(a.tanggal_pengajuan) as tanggal_pengajuan, a.tanggal_nrb, a.file, a.file_2, a.file_3,
                    a.status, a.nama_status, a.signature, a.deleted, a.tipe,
                    if(b.branch_name is null, d.company, b.branch_name) as branch_name, if(b.nama_comp is null, d.company, b.nama_comp) as nama_comp, d.company,
                    c.namasupp, a.created_by, a.digital_signature, a.created_at, a.verifikasi_at, a.verifikasi_by, a.verifikasi_signature,
                    e.username as verifikasi_username, a.principal_area_at, a.principal_area_by, a.principal_area_signature, a.file_principal_area,
                    a.catatan_principal_area, f.username as principal_area_username,
                    a.status_principal_ho, a.nama_status_principal_ho,
                    a.principal_ho_at, a.principal_ho_by, a.principal_ho_signature, a.file_principal_ho,
                    a.catatan_principal_ho, g.username as principal_ho_username, a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba,
                    proses_kirim_barang_at, a.proses_kirim_barang_by, h.username as username_kirim_barang, a.file_pengiriman,
                    a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.file_terima_barang, a.terima_barang_at,
                    i.username as username_terima_barang,
                    a.tanggal_pemusnahan, a.nama_pemusnahan, a.no_pemusnahan, a.file_pemusnahan, a.foto_pemusnahan_1, a.foto_pemusnahan_2, a.video,
                    a.pemusnahan_at, a.pemusnahan_by, j.username as username_pemusnahan, k.noseri, k.noseri_beli, a.video, a.terima_barang_by, a.validasi_pemusnahan_by, l.username as username_validasi_pemusnahan, a.validasi_pemusnahan_at, a.is_file_folder_retur,
                    date_add(date(a.principal_ho_at), interval 60 day) as deadline_kirim_barang,
                    datediff(
                        date_add(date(a.principal_ho_at), interval 60 day), 
                        curdate()
                    ) as sisa_hari
            from    management_inventory.pengajuan_retur a LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code LEFT JOIN (
                select a.supp, a.namasupp
                from mpm.tabsupp a
                union all
                select '001-herbal' as supp, 'DELTOMED_HERBAL' as namasupp
                union all
                select '001-herbana' as supp, 'DELTOMED_HERBANA' as namasupp
                union all
                select '001-GT' as supp, 'DELTOMED-GT' as namasupp
                union all
                select '001-MTI' as supp, 'DELTOMED-MTI' as namasupp
                union all
                select '001-NKA' as supp, 'DELTOMED-NKA' as namasupp
                union all
                select '001-GT-PHARMA' as supp, 'DELTOMED-GT-PHARMA' as namasupp
                union all
                select '001-RTD' as supp, 'DELTOMED-RTD' as namasupp
            )c on a.supp = c.supp left join
            (
                select a.id, a.username, a.company
                from mpm.user a
            )d on IF(LENGTH(a.site_code) = 5 , substr(a.site_code, 1, 3), a.site_code)= d.username left join
            (
                select a.id, a.username, a.company
                from mpm.user a
            )e on a.verifikasi_by = e.id left join
            (
                select a.id, a.username, a.company
                from mpm.user a
            )f on a.principal_area_by = f.id left join
            (
                select a.id, a.username, a.company
                from mpm.user a
            )g on a.principal_ho_by = g.id left join
            (
                select a.id, a.username, a.company
                from mpm.user a
            )h on a.proses_kirim_barang_by = h.id left join
            (
                select a.id, a.username, a.company
                from mpm.user a
            )i on a.terima_barang_by = i.id left join
            (
                select a.id, a.username, a.company
                from mpm.user a
            )j on a.pemusnahan_by = j.id left join (
                select a.no_ajuan_retur, a.noseri, a.noseri_beli
                from management_retur.ajuan_vs_nota_retur a
                GROUP BY a.no_ajuan_retur
            )k on a.no_pengajuan = k.no_ajuan_retur left join
            (
                select a.id, a.username, a.company
                from mpm.user a
            )l on a.validasi_pemusnahan_by = l.id
            where a.deleted is null $params $params_alamat $params_supp $params_date $params_status $params_pabrik $params_linda $params_rani
            order by a.id desc
            $limit
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }


    public function generate($created_at)
    {

        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        // $query = "
        //     select a.no_pengajuan, substr(a.no_pengajuan,5,3) as urut
        //     from management_inventory.pengajuan_retur a left join (
        //         select a.id, a.username
        //         from mpm.user a
        //     )b on a.created_by = b.id
        //     where year(a.tanggal_pengajuan) = $tahun_now and month(a.tanggal_pengajuan) = $bulan_now and a.no_pengajuan is not null
        //     ORDER BY a.id desc
        //     limit 1
        // ";

        // $query = "
        //     select a.no_pengajuan, substr(a.no_pengajuan,5,3) as urut
        //     from management_inventory.pengajuan_retur a
        //     where year(a.tanggal_pengajuan) = $tahun_now and month(a.tanggal_pengajuan) = $bulan_now and a.no_pengajuan is not null
        //     ORDER BY a.no_pengajuan desc
        //     limit 1
        // ";

        $query = "
            select a.no_pengajuan, substr(a.no_pengajuan,5,3) as urut,
                    replace(substr(a.no_pengajuan,5,4), '/','') as urut_new,
                    length(replace(substr(a.no_pengajuan,5,4), '/','')) as urut_new_length
                    from management_inventory.pengajuan_retur a
            where year(a.tanggal_pengajuan) = $tahun_now and month(a.tanggal_pengajuan) = $bulan_now and a.no_pengajuan is not null
            ORDER BY urut_new_length desc, urut_new desc
        ";



        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $no_pengajuan_current = $this->db->query($query);
        if ($no_pengajuan_current->num_rows() > 0) {

            $params_urut = $no_pengajuan_current->row()->urut_new + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "RTR-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "RTR-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "RTR-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "RTR-001/MPM/$romawi/$tahun_now";
        }

        // echo "generate : ".$generate;

        // die;

        // echo $generate;
        // die;
        return $generate;
    }

    public function generate_spbr($created_at)
    {
        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        $query = "
            select a.no_terima_barang, substr(a.no_terima_barang,6,3) as urut,
                replace(substr(a.no_terima_barang,5,4), '/','') as urut_new,
                length(replace(substr(a.no_terima_barang,6,4), '/','')) as urut_new_length
			from management_inventory.pengajuan_retur a
			where year(IF(a.terima_barang_at is null, a.validasi_pemusnahan_at, a.terima_barang_at)) = '$tahun_now' and month(IF(a.terima_barang_at is null, a.validasi_pemusnahan_at, a.terima_barang_at)) = '$bulan_now' and a.no_terima_barang is not null and a.supp LIKE '%001%' and a.no_terima_barang LIKE '%SPBR%'            
            ORDER BY urut_new_length desc, urut_new desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;


        $no_pengajuan_current = $this->db->query($query);
        if ($no_pengajuan_current->num_rows() > 0) {

            $params_urut = $no_pengajuan_current->row()->urut_new + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "SPBR/00$params_urut/SCM-BS/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "SPBR/0$params_urut/SCM-BS/$romawi/$tahun_now";
            }else{
                $generate = "SPBR/$params_urut/SCM-BS/$romawi/$tahun_now";
            }
        }else{
            $generate = "SPBR/001/SCM-BS/$romawi/$tahun_now";
        }

        // echo "generate : ".$generate;

        // die;

        // echo $generate;
        // die;
        return $generate;
    }

    public function generate_spbr_pemusnahan($created_at)
    {
        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        $query = "
            select a.no_terima_barang, substr(a.no_terima_barang,6,3) as urut,
                replace(substr(a.no_pengajuan,5,4), '/','') as urut_new,
                length(replace(substr(a.no_terima_barang,6,4), '/','')) as urut_new_length
			from management_inventory.pengajuan_retur a
			where year(a.validasi_pemusnahan_at) = '$tahun_now' and month(a.validasi_pemusnahan_at) = '$bulan_now' and a.no_terima_barang is not null and a.supp LIKE '%001%'and a.no_terima_barang LIKE '%SPBR%'            
			ORDER BY urut_new_length desc, urut_new desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;


        $no_pengajuan_current = $this->db->query($query);
        if ($no_pengajuan_current->num_rows() > 0) {

            $params_urut = $no_pengajuan_current->row()->urut_new + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "SPBR/00$params_urut/SCM-BS/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "SPBR/0$params_urut/SCM-BS/$romawi/$tahun_now";
            }else{
                $generate = "SPBR/$params_urut/SCM-BS/$romawi/$tahun_now";
            }
        }else{
            $generate = "SPBR/001/SCM-BS/$romawi/$tahun_now";
        }

        // echo "generate : ".$generate;

        // die;

        // echo $generate;
        // die;
        return $generate;
    }

    public function generate_berita_acara($tanggal_pemusnahan, $signature)
    {
        $this->db->select('no_pemusnahan');
        $this->db->where('signature', $signature);
        $no_pemusnahan = $this->db->get('management_inventory.pengajuan_retur')->row()->no_pemusnahan;

        if ($no_pemusnahan) {
            return $no_pemusnahan;
        } else {
            $bulan_now = date('m',strtotime($tanggal_pemusnahan));
            $romawi = $this->getRomawi($bulan_now);
            $tahun_now = date('Y');

            $query = "
                select a.no_pemusnahan, substr(a.no_pemusnahan,1,3) as urut
                from management_inventory.pengajuan_retur a
                where year(a.tanggal_pemusnahan) = '$tahun_now' and month(a.tanggal_pemusnahan) = '$bulan_now' and a.no_pemusnahan is not null
                ORDER BY a.no_pemusnahan desc
                LIMIT 1
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
            // die;


            $no_pengajuan_current = $this->db->query($query);
            if ($no_pengajuan_current->num_rows() > 0) {

                $params_urut = $no_pengajuan_current->row()->urut + 1;
                // echo $params_urut;

                if (strlen($params_urut) === 1) {
                    $generate = "00$params_urut/$romawi/$tahun_now";
                }elseif (strlen($params_urut) === 2) {
                    $generate = "0$params_urut/$romawi/$tahun_now";
                }else{
                    $generate = "$params_urut/$romawi/$tahun_now";
                }
            }else{
                $generate = "001/$romawi/$tahun_now";
            }

            // echo "generate : ".$generate;

            // die;

            // echo $generate;
            // die;
            return $generate;
        }
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

    public function get_user($id){
        $query = "
            select *
            from mpm.user a where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function cek_status($signature){
        $query = "
            select *
            from management_inventory.pengajuan_retur a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail($id_pengajuan){
        $query_cek = "
            select a.supp, a.tipe
            from management_inventory.pengajuan_retur a
            where a.id = $id_pengajuan
        ";

        $params_supp = $this->db->query($query_cek)->row()->supp;
        $params_tipe = $this->db->query($query_cek)->row()->tipe;

        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, c.nama_alasan, a.qty_approval, a.keterangan_principal_area, a.qty_approval_ho, a.keterangan_principal_ho, a.qty_pemusnahan, a.keterangan_pemusnahan, a.qty_final, a.keterangan_final,
                a.qty_lpk, a.qty_tolak, b.h_dp, (a.jumlah * b.h_dp) as rbp
        from management_inventory.pengajuan_retur_detail a LEFT JOIN
        (
            select a.kodeprod, a.namaprod, a.h_dp
            from site.master_product_with_harga a
        )b on a.kodeprod = b.kodeprod left join (
            select a.kode_alasan, a.nama_alasan
            from management_inventory.master_alasan a
            where a.supp = '$params_supp' and a.tipe = '$params_tipe'
            GROUP BY a.kode_alasan
        )c on a.alasan = c.kode_alasan
        where a.deleted is null and a.id_pengajuan = $id_pengajuan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_approv($id_pengajuan, $supp, $tipe){
        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area,
                a.qty_approval_ho, a.keterangan_principal_ho, a.qty_pemusnahan, a.keterangan_pemusnahan, a.qty_final, a.keterangan_final,
                a.qty_lpk, b.h_dp, (a.jumlah * b.h_dp) as rbp, c.nama_alasan
        from management_inventory.pengajuan_retur_detail a LEFT JOIN
        (
            select a.kodeprod, a.namaprod, b.h_dp
            from mpm.tabprod a left join (
                select a.kodeprod, a.h_dp
                from mpm.prod_detail a
                where a.tgl = (
                    select max(b.tgl)
                    from mpm.prod_detail b
                    where a.kodeprod = b.kodeprod
                    GROUP BY b.kodeprod
                )
            )b on a.kodeprod = b.kodeprod
        )b on a.kodeprod = b.kodeprod left join (
			select a.kode_alasan, a.nama_alasan
			from management_inventory.master_alasan a
			where a.supp ='$supp' and a.tipe = '$tipe'
		)c on a.alasan = c.kode_alasan
        where a.deleted is null and a.status = '3' and a.id_pengajuan = $id_pengajuan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_email_ho($id_pengajuan){
        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area, a.qty_approval_ho, a.keterangan_principal_ho
        from management_inventory.pengajuan_retur_detail a LEFT JOIN
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )b on a.kodeprod = b.kodeprod
        where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_filter($id_pengajuan){
        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area, a.qty_approval_ho, a.keterangan_principal_ho
        from management_inventory.pengajuan_retur_detail a LEFT JOIN
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )b on a.kodeprod = b.kodeprod
        where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.qty_approval > 0 and a.status = 3
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_accordion($id_pengajuan){

        $query_cek = "
            select a.supp, a.tipe
            from management_inventory.pengajuan_retur a
            where a.id = $id_pengajuan
        ";

        $params_supp = $this->db->query($query_cek)->row()->supp;
        $params_tipe = $this->db->query($query_cek)->row()->tipe;

        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, c.nama_alasan, a.qty_approval, a.keterangan_principal_area,
			    a.qty_approval_ho, a.keterangan_principal_ho, b.h_dp, (a.jumlah * b.h_dp) as rbp, a.qty_final, a.keterangan_final, a.qty_tolak
        from management_inventory.pengajuan_retur_detail a LEFT JOIN
        (
            select a.kodeprod, a.namaprod, b.h_dp
            from mpm.tabprod a left join (
                select a.kodeprod, a.h_dp
                from mpm.prod_detail a
                where a.tgl = (
                    select max(b.tgl)
                    from mpm.prod_detail b
                    where a.kodeprod = b.kodeprod
                    GROUP BY b.kodeprod
                )
            )b on a.kodeprod = b.kodeprod
        )b on a.kodeprod = b.kodeprod left join (
            select a.kode_alasan, a.nama_alasan
            from management_inventory.master_alasan a
            where a.supp = '$params_supp' and a.tipe = '$params_tipe'
            GROUP BY a.kode_alasan
        )c on a.alasan = c.kode_alasan
        where a.deleted is null and a.id_pengajuan = $id_pengajuan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf($id_pengajuan)
    {
        $query = "
            select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status, a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.qty_approval_ho, a.qty_pemusnahan, a.qty_final, a.qty_lpk, a.qty_tolak, a.keterangan_principal_area, a.keterangan_principal_ho, a.keterangan_pemusnahan, a.keterangan_final
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan
            order by b.namaprod, a.batch_number asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf_group_kodeprod($id_pengajuan)
    {
        $query = "
            select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status, a.deskripsi, b.namaprod, a.signature, a.alasan, sum(a.qty_approval_ho) as qty_approval_ho, sum(a.qty_pemusnahan) as qty_pemusnahan, sum(a.qty_final) as qty_final, sum(a.qty_lpk) as qty_lpk, sum(a.qty_tolak) as qty_tolak, a.keterangan_principal_ho, a.keterangan_final
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3 and a.qty_approval_ho != 0
            group by a.kodeprod, a.batch_number
            order by b.namaprod asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf_persetujuan($id_pengajuan)
    {
        $query = "
            select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status, a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval_ho, a.qty_final, a.qty_lpk, a.qty_tolak, a.keterangan_principal_ho
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3 and a.qty_approval_ho != 0 and (a.qty_final is not null and a.qty_final != 0)
            order by b.namaprod, a.batch_number asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf_persetujuan_group_kodeprod($id_pengajuan){

        $query = "
            select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status, a.deskripsi, b.namaprod, a.signature, a.alasan, sum(a.qty_approval_ho) as qty_approval_ho, sum(a.qty_final) as qty_final, sum(a.qty_lpk) as qty_lpk, sum(a.qty_tolak) as qty_tolak, a.keterangan_principal_ho
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3 and a.qty_approval_ho != 0 and (a.qty_final is not null and a.qty_final != 0)
            group by a.kodeprod, a.batch_number
            order by b.namaprod asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf_pemusnahan($id_pengajuan)
    {
        $query = "
            select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status, a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval_ho, a.qty_final, a.qty_lpk, a.qty_tolak, a.qty_pemusnahan, a.keterangan_principal_ho
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3 and a.qty_approval_ho != 0 and (a.qty_pemusnahan is not null and a.qty_pemusnahan != 0)
            order by b.namaprod, a.batch_number asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf_pemusnahan_group_kodeprod($id_pengajuan){

        $query = "
            select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status, a.deskripsi, b.namaprod, a.signature, a.alasan, sum(a.qty_approval_ho) as qty_approval_ho, sum(a.qty_final) as qty_final, sum(a.qty_lpk) as qty_lpk, sum(a.qty_tolak) as qty_tolak, sum(a.qty_pemusnahan) as qty_pemusnahan, a.keterangan_principal_ho
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3 and a.qty_approval_ho != 0 and (a.qty_final is not null and a.qty_final != 0)
            group by a.kodeprod, a.batch_number
            order by b.namaprod asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf_penolakan($id_pengajuan)
    {
        $query = "
            select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status, a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval_ho, a.qty_final, a.qty_lpk, a.qty_tolak, a.keterangan_principal_ho
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3 and (a.qty_tolak is not null and a.qty_tolak != 0)
            order by b.namaprod, a.batch_number asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf_penolakan_group_kodeprod($id_pengajuan){

        $query = "
            select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status, a.deskripsi, b.namaprod, a.signature, a.alasan, sum(a.qty_approval_ho) as qty_approval_ho, sum(a.qty_final) as qty_final, sum(a.qty_lpk) as qty_lpk, sum(a.qty_tolak) as qty_tolak, a.keterangan_principal_ho
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3 and (a.qty_tolak is not null and a.qty_tolak != 0)
            group by a.kodeprod, a.batch_number
            order by b.namaprod asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    // public function get_pengajuan_detail_summary($id_pengajuan){
    //     $query = "
    //         select count(a.kodeprod) as count_kodeprod, sum(a.jumlah * b.h_dp) as value_rbp, sum(a.jumlah) as sum_qty_pengajuan
    //         from management_inventory.pengajuan_retur_detail a LEFT JOIN
    //         (
    //             select b.h_dp, b.kodeprod
    //             from mpm.prod_detail b
    //             where b.tgl = (
    //                 select max(c.tgl)
    //                 from mpm.prod_detail c
    //                 where b.kodeprod = c.kodeprod
    //                 GROUP BY c.kodeprod
    //             )
    //             GROUP BY b.kodeprod
    //         )b on a.kodeprod = b.kodeprod
    //         where a.deleted is null and a.id_pengajuan = $id_pengajuan
    //     ";
    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";
    //     return $this->db->query($query);
    // }

    public function get_pengajuan_detail_summary($id_pengajuan)
    {
        $query = "
            select count(a.kodeprod) as count_kodeprod, sum(if(c.status >= 4, a.qty_approval, a.jumlah) * if(a.kodeprod = 060106 || a.kodeprod = 060107 || a.kodeprod = 060108, 12, 1) * b.h_dp) as value_rbp, sum(if(c.status >= 4, a.qty_approval, a.jumlah)) as sum_qty_pengajuan
            from management_inventory.pengajuan_retur_detail a LEFT JOIN
            (
                select b.h_dp, b.kodeprod
                from mpm.prod_detail b
                where b.tgl = (
                    select max(c.tgl)
                    from mpm.prod_detail c
                    where b.kodeprod = c.kodeprod
                    GROUP BY c.kodeprod
                )
                GROUP BY b.kodeprod
            )b on a.kodeprod = b.kodeprod
            left join management_inventory.pengajuan_retur c on a.id_pengajuan = c.id
            where a.deleted is null and a.id_pengajuan = $id_pengajuan and (a.`status` not in (4) or a.`status` is null)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_email($site_code){
        // untuk membedakan ajuan mpi, penta dan dp
        if (strlen($site_code) >5) {
            $username = $site_code;
        } else {
            $username = substr($site_code,0,3);
        }

        $query = "
            select a.email, a.username, a.company
            from mpm.user a
            where a.username = '$username'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_pengajuan_by_status(){
        $query = "
            select a.supp, b.namasupp, a.status, a.nama_status, sum(c.jumlah) as total_jumlah, sum(c.`value`) as total_value
            from management_inventory.pengajuan_retur a left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp LEFT JOIN
            (
                select a.id_pengajuan, a.kodeprod, sum(a.jumlah) as jumlah, b.h_dp, sum(a.jumlah * b.h_dp) as value
                from management_inventory.pengajuan_retur_detail a LEFT JOIN
                (
                    select a.kodeprod, a.namaprod, b.h_dp
                    from mpm.tabprod a INNER JOIN (
                        select b.kodeprod, b.h_dp
                        from mpm.prod_detail b
                        where b.tgl = (
                            select max(c.tgl)
                            from mpm.prod_detail c
                            where b.kodeprod = c.kodeprod
                            GROUP BY c.kodeprod
                        )
                    )b on a.kodeprod = b.kodeprod
                )b on a.kodeprod = b.kodeprod
                GROUP BY a.id_pengajuan
            )c on a.id = c.id_pengajuan
            GROUP BY a.supp, a.status
        ";


        return $this->db->query($query);
    }

    public function get_pengajuan_breakdown($breakdown){

        if ($breakdown == 'site_code') {
            $params_breakdown = "group by a.site_code";
        }elseif($breakdown == 'status'){
            $params_breakdown = "group by a.status";
        }

        $query = "
            select a.supp, b.namasupp, a.status, a.nama_status, count(*) as total_ajuan, sum(c.jumlah) as total_jumlah, sum(c.`value`) as total_value, a.site_code, d.branch_name, d.nama_comp
            from management_inventory.pengajuan_retur a left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp LEFT JOIN
            (
                select a.id_pengajuan, a.kodeprod, sum(a.jumlah) as jumlah, b.h_dp, sum(a.jumlah * b.h_dp) as value
                from management_inventory.pengajuan_retur_detail a LEFT JOIN
                (
                    select a.kodeprod, a.namaprod, b.h_dp
                    from mpm.tabprod a INNER JOIN (
                        select b.kodeprod, b.h_dp
                        from mpm.prod_detail b
                        where b.tgl = (
                            select max(c.tgl)
                            from mpm.prod_detail c
                            where b.kodeprod = c.kodeprod
                            GROUP BY c.kodeprod
                        )
                    )b on a.kodeprod = b.kodeprod
                )b on a.kodeprod = b.kodeprod
                GROUP BY a.id_pengajuan
            )c on a.id = c.id_pengajuan LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code
            $params_breakdown
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_pengajuan_detail_by_id($id){
        $query = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_product($kodeprod){
        $query = "
            select *
            from mpm.tabprod a
            where a.kodeprod = '$kodeprod'
        ";

        return $this->db->query($query);
    }

    public function get_email_to_retur_by_site_code($site_code, $supp){

        $query = "
            select b.username, b.email
            from management_inventory.mapping_area_retur a INNER JOIN (
                select a.id, a.username, a.email
                from mpm.user a
            )b on a.userid = b.id
            where a.supp = '$supp' and a.site_code = '$site_code'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function get_principal_area($site_code, $supp, $key_account){

        if ($supp != '001-NKA') {
            $query = "
                select b.username, b.email
                from management_inventory.mapping_area_retur a INNER JOIN (
                    select a.id, a.username, a.email
                    from mpm.user a
                )b on a.userid = b.id
                where a.supp = '$supp' and a.deleted_at is null and a.status_ho is null
            ";
        } else {
            $query = "
                select b.username, b.email
                from management_inventory.mapping_key_account a INNER JOIN (
                    select a.id, a.username, a.email
                    from mpm.user a
                )b on a.userid = b.id
                where a.key_account = '$key_account' and a.deleted_at is null
            ";
        }

        // echo "<pre>";
        // echo "$query";
        // echo "<pre>";
        // die;

        return $this->db->query($query);

    }

    public function get_email_ho_to_retur_by_site_code($site_code, $supp){

        $query = "
            select b.username, b.email
            from management_inventory.mapping_area_retur a INNER JOIN (
                select a.id, a.username, a.email
                from mpm.user a
            )b on a.userid = b.id
            where a.supp = '$supp' and a.site_code = '$site_code' and a.status_ho = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function get_email_cc_retur_by_site_code($site_code, $supp){

        $query = "
            select b.username, b.email
            from management_inventory.mapping_area_retur a INNER JOIN (
                select a.id, a.username, a.email
                from mpm.user a
            )b on a.userid = b.id
            where a.supp = $supp and a.site_code = '$site_code'
        ";

        return $this->db->query($query);

    }

    public function get_principal_akses($userid){
        $query = "
            select *
            from management_inventory.mapping_area_retur a
            where a.userid = $userid and a.deleted_by is null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "<pre>";

        return $this->db->query($query);
    }

    public function get_supp_principal_akses($userid){
        $query = "
            select *
            from management_inventory.mapping_area_retur a
            where a.userid = $userid
            group by a.supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "<pre>";

        return $this->db->query($query);
    }

    public function get_level_ho($site_code, $supp, $status_ho){
        $userid = $this->session->userdata('id');
        if ($status_ho == 1) {
            $params_status_ho = "and a.status_ho = 1";
        } else {
            $params_status_ho = "";
        }
        
        $query = "
            select *
            from management_inventory.mapping_area_retur a
            where a.site_code = '$site_code' and a.userid = $userid and a.supp = '$supp' and deleted_at is null $params_status_ho
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_group($sign_principal_ho_date, $userid_for_group_approval, $principal_for_group_approval){
        $query = "
            select 	a.id,a.no_pengajuan, a.tipe, a.site_code, a.nama, a.tanggal_pengajuan, a.signature, a.nama_status,
                    c.total_qty_approval, d.nama_comp, a.supp
            from management_inventory.pengajuan_retur a INNER JOIN (
                select a.supp, a.site_code
                from management_inventory.mapping_area_retur a
                where a.userid = $userid_for_group_approval and a.status_ho = 1
            )b on a.supp = b.supp and a.site_code = b.site_code LEFT JOIN (
                select 	a.id_pengajuan, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.alasan,
                        a.nama_outlet, sum(a.qty_approval) as total_qty_approval, a.keterangan_principal_area
                from management_inventory.pengajuan_retur_detail a
                where a.qty_approval is not null
                GROUP BY a.id_pengajuan
            )c on a.id = c.id_pengajuan LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code
            where a.`status` = 4
        ";

        return $this->db->query($query);

    }

    public function get_pengajuan_by_signature($signature){
        $query = "
            select 	a.id, a.no_pengajuan, a.tipe, a.site_code, a.nama, a.supp, a.key_account, a.tanggal_pengajuan, a.file, a.file_2, a.file_3,
                    a.`status`, a.nama_status, b.namasupp, c.branch_name, c.nama_comp,
                    a.principal_area_at, a.principal_area_by, a.file_principal_area, a.catatan_principal_area, d.principal_area_name,
                    a.verifikasi_at, a.verifikasi_by, e.verifikasi_mpm_name,
                    a.principal_ho_at, a.principal_ho_by, a.file_principal_ho, a.catatan_principal_ho, f.principal_ho_name,
                    a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.proses_kirim_barang_at, a.file_pengiriman,
                    a.tanggal_pemusnahan, a.nama_pemusnahan, a.file_pemusnahan, a.foto_pemusnahan_1, a.foto_pemusnahan_2, a.video, a.pemusnahan_at,
                    a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.file_terima_barang, a.terima_barang_at, g.last_updated_name, a.last_updated, a.created_by, a.keterangan_lain, a.no_pemusnahan
            from management_inventory.pengajuan_retur a left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp left join (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )c on a.site_code = c.site_code left join (
                select a.id, a.username as principal_area_name
                from mpm.user a
            )d on a.principal_area_by = d.id left join (
                select a.id, a.username as verifikasi_mpm_name
                from mpm.user a
            )e on a.verifikasi_by = e.id left join (
                select a.id, a.username as principal_ho_name
                from mpm.user a
            )f on a.principal_ho_by = f.id left join (
                select a.id, a.username as last_updated_name
                from mpm.user a
            )g on a.last_updated_by = g.id
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_qty_pengajuan_by_id_product($id_product){
        $query = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id = $id_product
        ";
        return $this->db->query($query);
    }

    public function get_traffic(){
        $query = "
            select *
            from management_inventory.traffic a
            ORDER BY a.id desc
            limit 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_traffic($site_code = '', $created_by, $status_import)
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $data = [
            "site_code"         => $site_code,
            "created_by"        => $created_by,
            "created_at"        => $created_at,
            "status_generate"   => $status_import
        ];

        $insert = $this->db->insert("management_inventory.traffic", $data);
        return $insert;
    }

    public function get_nama_status_by_id($id){
        if ($id == 1) {
            $nama_status = "PENDING DP";
        }elseif ($id == 2) {
            $nama_status = "PENDING MPM";
        }elseif ($id == 3) {
            $nama_status = "PENDING PRINCIPAL AREA";
        }elseif ($id == 4) {
            $nama_status = "PENDING PRINCIPAL HO";
        }elseif ($id == 5) {
            $nama_status = "PENDING KIRIM BARANG";
        }elseif ($id == 6) {
            $nama_status = "PENDING TERIMA BARANG";
        }elseif ($id == 7) {
            $nama_status = "PENDING PEMUSNAHAN";
        }elseif ($id == 8) {
            $nama_status = "BARANG DITERIMA";
        }elseif ($id == 9) {
            $nama_status = "PEMUSNAHAN OLEH DP";
        }elseif ($id == 10) {
            $nama_status = "REJECT PRINCIPAL HO";
        }elseif ($id == 11) {
            $nama_status = "RETUR SAMPLE";
        }elseif ($id == 13) {
            $nama_status = "REJECT";
        }

        return $nama_status;
    }

    public function cek_hak_akses($site_code, $supp, $userid, $status_ho, $key_account){
        if ($status_ho == 0) {
            $params_status_ho = "and a.status_ho is null";
        }else{
            $params_status_ho = "and a.status_ho = 1";
        }

        // if ($supp == "001-NKA") {
        //     $query = "
        //         select a.id, a.userid
        //         from management_inventory.mapping_key_account a
        //         where a.key_account = '$key_account' and a.userid = $userid and a.deleted_at is null
        //     ";
        // } else {
        // }
        $query = "
            select a.id, a.supp, a.site_code, a.userid, a.status_ho
            from management_inventory.mapping_area_retur a
            where a.site_code = '$site_code' and a.supp ='$supp' and a.userid = $userid and a.deleted_at is null $params_status_ho
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return  $this->db->query($query);

    }

    public function get_user_surdon($userid = '')
    {
        $query = "
            select a.username, a.email
            from mpm.user a
            where a.id = $userid and (a.name like '%PT. SURYA DONASIN%' or a.name like '%PT. SUPRALITA MANDIRI%')
        ";

        //         echo "<pre>";
        //         print_r($query);
        //         echo "</pre>";
        return $this->db->query($query);
    }

    public function get_user_mpi($userid = '')
    {
        $query = "
            select a.username, a.email
            from mpm.user a
            where a.id = $userid and (a.username like '%mpi%' or a.name like '%PT. MILLENNIUM PHARMACON INTERNATIONAL%')
        ";

        //         echo "<pre>";
        //         print_r($query);
        //         echo "</pre>";
        return $this->db->query($query);
    }

    public function get_user_penta($userid = '')
    {
        $query = "
            select a.username, a.email
            from mpm.user a
            where a.id = $userid and (a.username like '%penta%')
        ";

        //         echo "<pre>";
        //         print_r($query);
        //         echo "</pre>";
        return $this->db->query($query);
    }

    public function get_product_rtd($kodeprodx)
    {
        $this->db->select('*');
        $this->db->from('mpm.tabprod');
        $this->db->where('new_divisi', 'RTD');
        $this->db->where('kodeprod', "$kodeprodx");
        return $this->db->get();
    }

    public function get_master_mapping_area($supp = '', $pic = '', $area = '')
    {
        if ($supp != '' && $pic != '' && $area != '') {
            $params = "where a.supp = '$supp' and a.userid = '$pic' and a.site_code = '$area'";
        } else {
            $params = "";
        }

        $query = "
            SELECT a.*,
            CASE
                WHEN c.username = 'yuniah' THEN
                    'Pabrik'
                WHEN c.username = 'putri' THEN
                'Pabrik'
                WHEN a.status_ho = '1' THEN
                    'Principal HO'
                ELSE
                    'Principal Area'
            END as status,
            IF(length(a.site_code) > 5, a.site_code, b.nama_comp) as nama_comp,
            c.username
            FROM
            (
                SELECT *
                FROM  management_inventory.mapping_area_retur a
                WHERE a.deleted_at is null and a.userid is not null
            )a
            LEFT JOIN
            (
                SELECT a.site_code, a.nama_comp
                FROM mpm.tbl_tabcomp a
                WHERE a.status = 1
            )b on a.site_code = b.site_code
            LEFT JOIN
            (
                SELECT a.id, a.username, a.name, a.company, a.branch_name
                FROM mpm.`user` a
            )c on a.userid = c.id
            $params
            ORDER BY b.site_code asc
        ";

        //         echo "<pre>";
        //         print_r($query);
        //         echo "</pre>";

        return $this->db->query($query);

    }

    public function get_retur_log_email($id_pengajuan = '')
    {
        $query = "
            SELECT a.*, b.no_pengajuan, b.signature
            FROM management_inventory.pengajuan_retur_log_email a
            INNER JOIN management_inventory.pengajuan_retur b on a.id_pengajuan = b.id
            WHERE a.id in (
                SELECT MAX(c.id)
                FROM management_inventory.pengajuan_retur_log_email c
                GROUP BY c.id_pengajuan, c.status
            ) and a.id_pengajuan = '$id_pengajuan'
            ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_retur_log($id_pengajuan)
    {
        $query = "
            SELECT *
            FROM management_inventory.pengajuan_retur_log a
            WHERE a.id_pengajuan = '$id_pengajuan'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_key_account()
    {
        // $query = "
        //     select a.key_account, b.username, b.email
        //     from management_inventory.mapping_key_account a left join
        //     (
        //         select a.id, a.username, a.email
        //         from site.master_user a
        //     )b on a.userid = b.id
        //     where a.userid is not null
        // ";

        $query = "
            select a.key_account, b.username, b.email
            from management_inventory.mapping_key_account a left join
            (
                select a.id, a.username, a.email
                from site.master_user a
            )b on a.userid = b.id
            GROUP BY a.key_account
            ORDER BY a.key_account
        ";
        return $this->db->query($query);
    }

    public function get_pic_area_terkait($site_code, $supp, $key_account)
    {
        if ($supp == '001-NKA') {
            $query = "
                select b.username
                from management_inventory.mapping_key_account a LEFT JOIN site.master_user b
                    on a.userid = b.id
                where a.key_account = '$key_account' and a.deleted_at is null
            ";
        } else {
            $query = "
                select b.username
                from management_inventory.mapping_area_retur a LEFT JOIN site.master_user b
                    on a.userid = b.id
                where a.site_code = '$site_code' and a.supp = '$supp' and a.status_ho is null and b.id not in (515,857,1048,588) and a.deleted_by is null
            ";
        }

        return $this->db->query($query);
    }

    public function get_pic_ho_terkait($site_code, $supp)
    {
        $query = "
            select b.username
            from management_inventory.mapping_area_retur a LEFT JOIN site.master_user b
                on a.userid = b.id
            where a.site_code = '$site_code' and a.supp = '$supp' and a.status_ho = 1 and b.id not in (515,857,789,1106,588,1048)
        ";

        return $this->db->query($query);
    }

    public function update_pengajuan_retur_detail($data, $id_pengajuan)
    {
        $this->db->where('id_pengajuan', $id_pengajuan);
        $this->db->update('management_inventory.pengajuan_retur_detail', $data);
        return $this->db->affected_rows();
    }

    public function get_pengajuan_retur_detail_by_id_pengajuan($id_pengajuan)
    {
        $query = "
            select *
            from management_inventory.pengajuan_retur_detail a
            where a.id_pengajuan = $id_pengajuan and a.deleted is null
        ";
        return $this->db->query($query);
    }


}