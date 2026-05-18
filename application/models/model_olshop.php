<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_olshop extends CI_Model
{
    public function get_header(){
        $query = "
        select 	a.id, a.olshop, a.filename, a.signature_header, a.created_at, a.created_by, a.updated_at, a.updated_by, 
                a.deleted_at, a.deleted_by,	b.tgl_olshop, b.inv_olshop, b.pembeli_olshop, 
                sum(b.qty_olshop) as total_qty_olshop, c.username, count(distinct(b.inv_olshop)) as total_invoice,
                count(distinct(b.kodeprod_olshop)) as total_produk, 
                IFNULL(d.no_pengambilan,IFNULL(d.generate_code,'belum ada data')) as status_pengambilan,
                e.faktur_sds, e.tanggal_faktur
        from site.t_olshop_header a LEFT JOIN
        (
            select 	a.id, a.id_ref, a.tgl_olshop, a.inv_olshop, a.pembeli_olshop, a.kodeprod_olshop, a.qty_olshop,
                    a.created_at, a.created_by, a.updated_at, a.updated_by, a.deleted_at, a.deleted_by, a.signature_detail
            from site.t_olshop_detail a 
        )b on a.id = b.id_ref LEFT JOIN (
            select a.id, a.username
            from mpm.user a 
        )c on a.updated_by = c.id LEFT JOIN 
        (
            select a.generate_code, a.no_pengambilan, a.signature_header
            from site.t_olshop_generate a
        )d on a.signature_header = d.signature_header LEFT JOIN 
        (
            select a.faktur_sds, a.tanggal_faktur, a.capture_faktur, a.signature, a.signature_header
            from site.t_olshop_history_invoice a
            where a.deleted_at is null
        )e on a.signature_header = e.signature_header
        GROUP BY a.id
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_detaill_by_signature($signature_header){

        $query = "
        select 	a.id, a.filename, a.signature_header, a.olshop,
                b.id_ref, b.kodeprod_olshop, b.inv_olshop, b.qty_olshop,
                b.pembeli_olshop, b.tgl_olshop, b.deleted_at, b.deleted_by,
                c.username, a.updated_by, a.updated_at, b.namaprod_olshop
        from site.t_olshop_header a LEFT JOIN 
        (
            select 	a.id, a.id_ref, a.kodeprod_olshop, a.inv_olshop, a.qty_olshop,
                    a.pembeli_olshop, a.tgl_olshop, deleted_at, a.deleted_by, a.namaprod_olshop
            from site.t_olshop_detail a 
        )b on a.id = b.id_ref LEFT JOIN 
        (
            select a.id, a.username
            from mpm.user a
        )c on a.updated_by = c.id
        where a.signature_header = '$signature_header'
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_summary($id_ref_history, $olshop){
        // $query = "
        //     select 	b.kodeprod_mpm, b.kodeprod_olshop, b.namaprod_olshop, c.namaprod, a.qty_olshop, b.qty, sum(a.qty_olshop * b.qty) as total_qty
        //     from site.t_olshop_detail a LEFT JOIN
        //     (
        //         select a.olshop, a.kodeprod_olshop, a.kodeprod_mpm, a.namaprod_olshop, a.qty, a.harga_retail, a.namaprod_mpm
        //         from site.map_olshop_product a
        //         where a.olshop = '$olshop'
        //     )b on a.kodeprod_olshop = b.kodeprod_olshop LEFT JOIN
        //     (
        //         select a.kodeprod, a.namaprod
        //         from mpm.tabprod a 
        //     )c on b.kodeprod_mpm = c.kodeprod
        //     where a.id_ref = $id_ref_history
        //     group by b.kodeprod_mpm
        //     order by b.kodeprod_olshop
        // ";

        $query = "
            select  a.kodeprod_mpm, a.kodeprod_olshop, a.namaprod_olshop, a.namaprod, 
                    sum(a.qty_olshop) as qty_olshop, sum(a.qty) as qty, sum(a.total_qty) as total_qty
            from 
            (
            select 	b.kodeprod_mpm, b.kodeprod_olshop, b.namaprod_olshop, c.namaprod, 
                    a.qty_olshop, b.qty, (a.qty_olshop * b.qty) as total_qty
            from site.t_olshop_detail a LEFT JOIN
            (
                select a.olshop, a.kodeprod_olshop, a.kodeprod_mpm, a.namaprod_olshop, a.qty, a.harga_retail, a.namaprod_mpm
                from site.map_olshop_product a
                where a.olshop = 'tokopedia'
            )b on a.kodeprod_olshop = b.kodeprod_olshop LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
            )c on b.kodeprod_mpm = c.kodeprod
            where a.id_ref = $id_ref_history
            order by b.kodeprod_olshop
            )a GROUP BY a.kodeprod_mpm
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);

    }

    public function get_id_ref($signature_header){
        $this->db->where('signature_header', $signature_header);
        return $this->db->get('site.t_olshop_header');
    }

    public function generate_code($type, $signature_header, $olshop){

        $created_at = $this->model_outlet_transaksi->timezone();
        $tahun = date('Y');
        $bulan = date('m');

        $cek_signature_yang_sama = "
            SELECT *
            FROM site.t_olshop_generate a
            where a.type = '$type' and a.signature_header = '$signature_header'
            order by a.id desc
        ";

        $proses_cek_signature_yang_sama = $this->db->query($cek_signature_yang_sama);
        if($proses_cek_signature_yang_sama->num_rows() > 0)
        {
            return $proses_cek_signature_yang_sama->row()->generate_code;       
        }else
        {
            $cek_type_yang_sama = "
                SELECT *
                FROM site.t_olshop_generate a
                where a.type = '$type'
                order by a.id desc
            ";

            $proses_cek_type_yang_sama = $this->db->query($cek_type_yang_sama);

            if($proses_cek_type_yang_sama->num_rows() > 0){

                $urut = substr($proses_cek_type_yang_sama->row()->generate_code, -3) + 1;
                if (strlen($urut) === 1) {
                    $params_urut = '00'.$urut;
                }else if (strlen($urut) === 2){
                    $params_urut = '0'.$urut;
                }else{
                    $params_urut = $urut;
                }

                $generate = "DRAFTMPM-".$olshop."/".$tahun.$bulan."-".$params_urut;

            }else{
                $generate = "DRAFTMPM-".$olshop."/".$tahun.$bulan."-001";
            }            
        }

        $signature = $type."-".md5($created_at);

        $data = [
            "generate_code" => $generate,
            "type"          => $type,
            "signature_header" => $signature_header,
            "created_at"    => $created_at,
            "created_by"    => $this->session->userdata('id'),
            "signature"     => $signature
        ];
        $insert = $this->db->insert('site.t_olshop_generate', $data);
        
        return $generate;

    }

    public function get_data_ambil_barang($signature_header){
        $this->db->where('signature_header', $signature_header);
        return $this->db->get('site.t_olshop_generate');
    }

    public function generate_csv_pengambilan_barang($signature_header){

        $get_id_ref = $this->model_olshop->get_id_ref($signature_header)->row();
        $id_ref = $get_id_ref->id;
        // $generate_code = $get_id_ref->generate_code;

        $get_data_ambil_barang = $this->model_olshop->get_data_ambil_barang($signature_header)->row();
        $no_barang_diambil = $get_data_ambil_barang->no_pengambilan;

        // echo "no_barang_diambil: ".$no_barang_diambil;
        // die;

        $query = "
            select  a.kodeprod_mpm, a.kodeprod_olshop, a.namaprod_olshop, a.namaprod, 
                    sum(a.qty_olshop) as qty_olshop, sum(a.qty) as qty, sum(a.total_qty) as total_qty
            from 
            (
            select 	b.kodeprod_mpm, b.kodeprod_olshop, b.namaprod_olshop, c.namaprod, 
                    a.qty_olshop, b.qty, (a.qty_olshop * b.qty) as total_qty
            from site.t_olshop_detail a LEFT JOIN
            (
                select a.olshop, a.kodeprod_olshop, a.kodeprod_mpm, a.namaprod_olshop, a.qty, a.harga_retail, a.namaprod_mpm
                from site.map_olshop_product a
                where a.olshop = 'tokopedia'
            )b on a.kodeprod_olshop = b.kodeprod_olshop LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
            )c on b.kodeprod_mpm = c.kodeprod
            where a.id_ref = $id_ref
            order by b.kodeprod_olshop
            )a GROUP BY a.kodeprod_mpm
        ";

        // $query = "
        //     select 	b.kodeprod_mpm as kodeprod, c.namaprod, /*a.qty_olshop, b.qty, */sum(a.qty_olshop * b.qty1) as total_qty
        //     from site.t_olshop_detail a LEFT JOIN
        //     (
        //         select a.olshop, a.kodeprod_olshop, a.kodeprod_mpm, a.namaprod_olshop, a.qty, a.harga_retail, a.namaprod_mpm
        //         from site.map_olshop_product a
        //     )b on a.kodeprod_olshop = b.kodeprod_olshop LEFT JOIN
        //     (
        //         select a.kodeprod, a.namaprod
        //         from mpm.tabprod a 
        //     )c on b.kodeprod_mpm = c.kodeprod
        //     where a.id_ref = $id_ref
        //     group by b.kodeprod_mpm
        // ";

        $hasil = $this->db->query($query);
        // print_r($sql);
        // die();


        $file = fopen(APPPATH . '/../assets/file/olshop/email/'.str_replace('/','_',$no_barang_diambil).'.csv', 'wb');

        $csv_fields=array();
        $csv_fields[] = 'kodeprod';
        $csv_fields[] = 'namaprod';
        $csv_fields[] = 'total qty';
        fputcsv($file, $csv_fields);

        foreach ($hasil->result() as $row)
        {
            $kodeprod = $row->kodeprod;
            $namaprod = $row->namaprod;
            $total_qty = $row->total_qty;
            fputcsv($file, array($kodeprod,$namaprod,$total_qty));        
        }

    }

    public function get_history_invoice($id_ref = ""){

        if ($id_ref == "") {
            $params_id_ref = "";
        }else{
            $params_id_ref = "and a.id_ref = $id_ref";
        }

        $query = "
        select 	a.id, a.id_ref, a.faktur_sds, a.tanggal_faktur, a.nominal_faktur,a.capture_faktur, 
                a.signature_header, a.signature, a.created_at, a.created_by, 
                a.updated_at, a.updated_by, a.deleted_at, a.deleted_by
        from 	site.t_olshop_history_invoice a
        where   a.deleted_at is null $params_id_ref
        order by a.id desc
        ";

        return $this->db->query($query);

    }

    public function get_history_penarikan_saldo(){

        $query = "
            select a.id, a.no_penarikan_saldo, a.nominal, a.tanggal_penarikan_saldo, a.no_rekening, a.pemilik_rekening, 
            a.catatan, a.created_at, a.created_by, b.faktur_sds
            from site.t_olshop_penarikan_saldo a INNER JOIN 
            (
                select a.id, a.id_ref, a.faktur_sds
                from site.t_olshop_penarikan_saldo_detail a 
            )b on a.id = b.id_ref
            where a.deleted_at is null
            order by a.id desc
        ";
        return $this->db->query($query);
    }

    public function generate_code_penarikan(){

        $tahun = date('Y');
        $bulan = date('m');

        $cek_data_terakhir = "
            SELECT *
            FROM site.t_olshop_penarikan_saldo a
            where year(a.tanggal_penarikan_saldo) = $tahun and month(a.tanggal_penarikan_saldo) = $bulan
            order by a.id desc  
            limit 1
        ";

        $proses_cek_data_terakhir = $this->db->query($cek_data_terakhir);

        if ($proses_cek_data_terakhir->num_rows > 0) {
            
            $urut = substr($proses_cek_data_terakhir->row()->no_penarikan_saldo, -3) + 1;
            if (strlen($urut) === 1) {
                $params_urut = '00'.$urut;
            }else if (strlen($urut) === 2){
                $params_urut = '0'.$urut;
            }else{
                $params_urut = $urut;
            }

            $no_penarikan = "MPM-OLSHOP-TARIKSALDO/".$tahun.$bulan."-".$params_urut;
            
        }else{   
            $no_penarikan = "MPM-OLSHOP-TARIKSALDO/".$tahun.$bulan."-001";
        }        
        return $no_penarikan;
    }

    public function get_report(){
        $query = "
        select 	b.courier, b.resi, a.signature_header,b.id_ref, b.tgl_olshop, b.inv_olshop, b.pembeli_olshop, b.qty_olshop, 
                b.namaprod_olshop, b.kodeprod_olshop, c.kodeprod_mpm, e.harga_retail, 
                (c.qty * b.qty_olshop) as qty, (c.qty * b.qty_olshop) * e.harga_retail as sub_total, 
                f.namaprod, d.faktur_sds, g.nominal
        from site.t_olshop_header a INNER JOIN
        (
            select	a.id_ref, a.tgl_olshop, a.inv_olshop, a.pembeli_olshop, a.qty_olshop, 
                    a.namaprod_olshop, a.kodeprod_olshop, courier, resi
            from site.t_olshop_detail a
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.kodeprod_olshop, a.kodeprod_mpm, a.qty
            from site.map_olshop_product a
        )c on b.kodeprod_olshop = c.kodeprod_olshop LEFT JOIN
        (
            select a.tanggal_faktur, a.capture_faktur, a.faktur_sds, a.signature_header, a.nominal_faktur
            from site.t_olshop_history_invoice a 
        )d on a.signature_header = d.signature_header LEFT JOIN
        (
            select a.kodeprod_olshop, a.kodeprod_mpm, a.harga_retail
            from site.map_olshop_product a
            GROUP BY a.kodeprod_mpm
        )e on c.kodeprod_mpm = e.kodeprod_mpm LEFT JOIN(
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )f on c.kodeprod_mpm = f.kodeprod LEFT JOIN
        (
            select a.nominal, a.tanggal_penarikan_saldo, b.faktur_sds
            from site.t_olshop_penarikan_saldo a INNER JOIN site.t_olshop_penarikan_saldo_detail b 
            on a.id = b.id_ref
        )g on d.faktur_sds = g.faktur_sds
        ORDER BY b.inv_olshop
        ";

        return $this->db->query($query);
    }


}