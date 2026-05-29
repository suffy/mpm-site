<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_bridging extends CI_Model 
{
    public function __construct() {
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->created_by = $this->session->userdata('id');
    }

    public function get_bridging_list_subbranch($signature = '')
    {
        if($signature)
        {
            $params_signature = " where a.signature = '$signature' and a.deleted_by is null";
        }else{
            $params_signature = "where a.deleted_by is null";
        }

        $query = "
            select a.site_code, a.signature, b.branch_name, b.nama_comp
            from site.bridging_list_subbranch a left join (
                select a.site_code, a.branch_name, a.nama_comp
                from site.master_site a
            )b on a.site_code = b.site_code
            $params_signature
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_bridging_hak_akses_by_site_code_userid($site_code, $userid)
    {
        $query = "
            select *
            from site.bridging_hak_akses a 
            where a.deleted_at is null and a.site_code = '$site_code' and a.userid = $userid
        ";  
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_bontang_import()
    {
        $this->db->query("drop table if exists site.bridging_bontang_import");

        $create_table = "
            create table if not exists site.bridging_bontang_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                distributor varchar(255),
                cabang varchar(255),
                tipetrans varchar(255),
                divisi varchar(255),
                principal varchar(255),
                productgroup1 varchar(255),
                productgroup2 varchar(255),
                productgroup3 varchar(255),
                brand varchar(255),
                kodeproduk varchar(255),
                kodevarian varchar(255),
                kodeprodukprincipal varchar(255),
                namaproduk varchar(255),
                packaging varchar(255),
                productclass varchar(255),
                kodecustomer varchar(255),
                namacustomer varchar(255),
                alamatcustomer text,
                area varchar(255),
                subarea varchar(255),
                channel varchar(255),
                subchannel varchar(255),
                customergroup varchar(255),
                keyaccount varchar(255),
                kodesalesman varchar(255),
                namasalesman varchar(255),
                kodesalesco varchar(255),
                namasalesco varchar(255),
                kodespv varchar(255),
                namaspv varchar(255),
                tahunbulan varchar(255),
                bulan varchar(255),
                tanggal varchar(255),
                weekno varchar(255),
                nomornota varchar(255),
                salesmethod varchar(255),
                sellingtype varchar(255),
                qtysold varchar(255),
                qtysoldcrt varchar(255),
                qtysolduom1 varchar(255),
                qtysolduom2 varchar(255),
                qtysolduom3 varchar(255),
                qtysolduom4 varchar(255),
                qtysoldtotalpcs varchar(255),
                freegoodtotalpcs varchar(255),
                tonnage varchar(255),
                volume varchar(255),
                grossamount varchar(255),
                linediscount1 varchar(255),
                linediscount2 varchar(255),
                linediscount3 varchar(255),
                linediscount4 varchar(255),
                linediscount5 varchar(255),
                totallinediscount varchar(255),
                discountnota1 varchar(255),
                discountnota2 varchar(255),
                discountnota3 varchar(255),
                totaldiscountnota varchar(255),
                dpp varchar(255),
                ppn varchar(255),
                ppnbm varchar(255),
                tax varchar(255),
                netamount varchar(255),
                warehouse varchar(255),
                customerpo varchar(255),
                customerjoindate varchar(255),
                nofakturpajak varchar(255),
                tanggalfakturpajak varchar(255),
                nomorfakturproforma varchar(255),
                tanggalfakturproforma varchar(255),
                term varchar(255),
                uom1 varchar(255),
                uom2 varchar(255),
                uom3 varchar(255),
                uom4 varchar(255),
                isiuom1 varchar(255),
                isiuom2 varchar(255),
                isiuom3 varchar(255),
                sellingprice varchar(255),
                cogs varchar(255),
                sellingpriceinkg varchar(255),
                caseweightinkg varchar(255),
                qtyordertotalpcs varchar(255),
                tslqtysoldnfg varchar(255),
                tslconvpcstoctn varchar(255),
                tsltonnagesoldfg varchar(255),
                `end` varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_bontang_import($data)
    {
        $this->db->insert('site.bridging_bontang_import', $data);
        return $this->db->affected_rows();
    }

    public function get_bontang_import()
    {
        $query = "
            select *
            from site.bridging_bontang_import a
        ";
        return $this->db->query($query);
    }

    public function get_bontang_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.grossamount) as sumgrossamount, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_bontang_import a
        ";
        return $this->db->query($query);
    }

    public function get_master_product_by_kodeprod($kodeprod)
    {
        $query = "
            select *
            from site.master_product a
            where a.kodeprod = '$kodeprod'
        ";
        return $this->db->query($query);
    }

    public function create_table_bontang_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_bontang_import_customer");

        $create_table = "
            create table if not exists site.bridging_bontang_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_bontang_import_customer($data)
    {
        $this->db->insert('site.bridging_bontang_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_bontang_import_customer()
    {
        $query = "
            select *
            from site.bridging_bontang_import_customer a
        ";
        return $this->db->query($query);
    }

    public function add_unique_bontang_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_bontang_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function get_bontang_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_bontang_import_customer a
        ";
        return $this->db->query($query);
    }


    public function get_type($kode_type)
    {
        $query = "
            select a.kode_type, a.nama_type, a.sektor, a.segment
            from mpm.tbl_bantu_type a 
            where length(a.kode_type) = 3 and a.kode_type = '$kode_type'
        ";
        return $this->db->query($query);
    }

    public function get_class($kode)
    {
        $query = "
            select a.kode, a.jenis, a.group
            from mpm.tbl_tabsalur a
            where a.kode = '$kode'
        ";

        // $query = "
        //     select a.kode, a.jenis, a.group
        //     from mpm.tbl_tabsalur a
        //     where a.kode in ('RT','SW','WS','SO') and a.kode = '$kode'
        // ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_bontang($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldtotalpcs as banyak, (a.grossamount * 1.11) / a.qtysoldtotalpcs as harga, '' as potongan, 
                    (a.grossamount * 1.11) as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount * 1.11)/a.qtysoldtotalpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT2/(a.GROSSAMOUNT-a.LINEDISCOUNT1))*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT3/(a.GROSSAMOUNT-a.LINEDISCOUNT1-a.LINEDISCOUNT2))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 * 1.11 as rp_cabang, a.LINEDISCOUNT2 * 1.11 as rp_prinsipal, a.LINEDISCOUNT3 * 1.11 as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, a.LINEDISCOUNT4 as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_bontang_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_bontang_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_bontang_bonus($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    0 as banyak, (a.grossamount * 1.11) / a.qtysoldtotalpcs as harga, '' as potongan, 
                    '' as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, a.freegoodtotalpcs as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount * 1.11)/a.qtysoldtotalpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, a.freegoodtotalpcs as qty_bonus, '1' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                    '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_bontang_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_bontang_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.freegoodtotalpcs !=0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_bontang($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldtotalpcs as banyak, (a.grossamount * 1.11)/a.qtysoldtotalpcs as harga, '' as potongan, 
                    (a.grossamount * 1.11) as tot1, '' as jum_promo, 
                    '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi, '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut, 'PST' as kode_gdg, 
                    '' as nama_gdg, b.class_id as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus, 	
                    a.freegoodtotalpcs as unitbonus, namasalesman as lampiran, 
                    (a.grossamount * 1.11) / a.qtysoldtotalpcs as h_beli, '' as kodearea, b.alamat as namarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as nama_lang, 
                    '$nocab' as nocab, '$bulan' as bulan, '' as siteid, '' as qty1, 
                    '' as qty2, 
                    '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen, '' as disc_rp,  
                    '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT2/(a.GROSSAMOUNT-a.LINEDISCOUNT1))*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT3/(a.GROSSAMOUNT-a.LINEDISCOUNT1-a.LINEDISCOUNT2))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 * 1.11 as rp_cabang, a.LINEDISCOUNT2 * 1.11 as rp_prinsipal, a.LINEDISCOUNT3 * 1.11 as rp_xtra,
                    '' as bonus, concat('11',c.supp) as prinsipalid, '' as ex_no_sales, '' as status_retur, '' as ref, 
                    '' as term_payment, '' as tipe_kl, a.LINEDISCOUNT4 as disc_cod, '' as rp_cod, '' as beban_bonus, '' as disc_add_percen, '' as subarea_id  
            from site.bridging_bontang_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_bontang_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans != 'sales'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_fi_bontang($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_bontang($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function input_bridging_log($data)
    {
        $proses = $this->db->insert('site.bridging_log', $data);
        return $this->db->insert_id();
    }

    public function get_bridging_log($id = "")
    {
        if($id)
        {
            $params_id = "where id = $id";
        }else{
            $params_id = "";
        }
        $query = "
            select *
            from site.bridging_log
            $params_id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_bridging_log_by_site_code($site_code)
    {
        $query = "
            select a.*, b.status_closing
            from site.bridging_log a
            left join mpm.upload b 
            on a.id_upload = b.id
            where a.site_code = '$site_code'
            order by a.id desc
            limit 100
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_bontang($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_bontang($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_bontang($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_tblang($tahun, $site_code)
    {
        $query = "
            insert into data$tahun.tblang
            select * from(				
                SELECT				
                KODE_COMP,				
                KODE_KOTA,				
                KODE_TYPE,				
                KODE_LANG,				
                KODERAYON,				
                NAMA_LANG,				
                NAMAAREA as ALAMAT1,				
                '' as ALAMAT2,				
                '' as TELP,				
                '' as KODEPOS,				
                '' as TGL,				
                '' as NPWP,				
                '0' as BTS_UTANG,				
                '0' as SALES01,				
                '0' as SALES02,				
                '0' as SALES03,				
                '0' as SALES04,				
                '0' as SALES05,				
                '0' as SALES06,				
                '0' as SALES07,				
                '0' as SALES08,				
                '0' as SALES09,				
                '0' as SALES10,				
                '0' as SALES11,				
                '0' as SALES12,				
                '0' as KET,				
                '0' as DEBIT,				
                '0' as KREDIT,				
                KODESALUR as KODESALUR,				
                '0' as TOP,				
                'Y' as AKTIF,				
                '' as TGL_AKTIF,				
                'T' as PPN,				
                '0' as KODE_LAMA,				
                '1' as JUM_DOK,				
                '0' as STATJUAL,				
                '0' as LIMIT1,				
                '' as TGLNAKTIF,				
                '' as ALAMAT_WP,				
                '' as NILAI_PPN,				
                '' as NAMA_WP,				
                '' as NEWFLD,				
                nocab as NOCAB,				
                '' as kodelang_copy,				
                '' as id_provinsi,				
                '' as nama_provinsi,				
                '' as id_kota,				
                '' as nama_kota,				
                '' as id_kecamatan,				
                '' as nama_kecamatan,				
                '' as id_kelurahan,				
                '' as nama_kelurahan,				
                '' as phone,				
                '' as tipe_bayar,				
                '' as credit_limit,				
                '' AS last_updated,				
                '' as status_blacklist,				
                '' as status_payment,				
                '' as CUSTID,				
                '' as COMPID,				
                '' as LATITUDE,				
                '' as LONGITUDE,				
                '' as FOTO_DISP,				
                '' as FOTO_TOKO,
                '' as KODE_SPOT,
                '' as subarea_id
                FROM(				
                    SELECT CONCAT(KODE_COMP,KODE_LANG,max(BULAN)) as mapp				
                    FROM data$tahun.fi 
                    WHERE concat(kode_comp, nocab) = '$site_code' 				
                    GROUP BY kode_comp,KODE_LANG 				
                )A				
                LEFT JOIN 				
                (				
                SELECT * FROM(				
                    SELECT *, CONCAT(KODE_COMP, KODE_LANG,BULAN) as mapp			
                    FROM data$tahun.fi   				
                    WHERE concat(kode_comp, nocab) = '$site_code'				
                    GROUP BY MAPP 				
                    )A				
                )C USING(MAPP)				
                union ALL				
                SELECT				
                KODE_COMP,				
                KODE_KOTA,				
                KODE_TYPE,				
                KODE_LANG,				
                KODERAYON,				
                NAMA_LANG,				
                NAMAAREA as ALAMAT1,				
                '' as ALAMAT2,				
                '' as TELP,				
                '' as KODEPOS,				
                '' as TGL,				
                '' as NPWP,				
                '0' as BTS_UTANG,				
                '0' as SALES01,				
                '0' as SALES02,				
                '0' as SALES03,				
                '0' as SALES04,				
                '0' as SALES05,				
                '0' as SALES06,				
                '0' as SALES07,				
                '0' as SALES08,				
                '0' as SALES09,				
                '0' as SALES10,				
                '0' as SALES11,				
                '0' as SALES12,				
                '0' as KET,				
                '0' as DEBIT,				
                '0' as KREDIT,				
                KODESALUR as KODESALUR,				
                '0' as TOP,				
                'Y' as AKTIF,				
                '' as TGL_AKTIF,				
                'T' as PPN,				
                '0' as KODE_LAMA,				
                '1' as JUM_DOK,				
                '0' as STATJUAL,				
                '0' as LIMIT1,				
                '' as TGLNAKTIF,				
                '' as ALAMAT_WP,				
                '' as NILAI_PPN,				
                '' as NAMA_WP,				
                '' as NEWFLD,				
                nocab as NOCAB,				
                '' as kodelang_copy,				
                '' as id_provinsi,				
                '' as nama_provinsi,				
                '' as id_kota,				
                '' as nama_kota,				
                '' as id_kecamatan,				
                '' as nama_kecamatan,				
                '' as id_kelurahan,				
                '' as nama_kelurahan,				
                '' as phone,				
                '' as tipe_bayar,				
                '' as credit_limit,				
                '' AS last_updated,				
                '' as status_blacklist,				
                '' as status_payment,				
                '' as CUSTID,				
                '' as COMPID,				
                '' as LATITUDE,				
                '' as LONGITUDE,				
                '' as FOTO_DISP,				
                '' as FOTO_TOKO,
                '' as KODE_SPOT,
                '' as subarea_id
                FROM(				
                    SELECT CONCAT(KODE_COMP,KODE_LANG,max(BULAN)) as mapp				
                    FROM data$tahun.ri  				
                    WHERE concat(kode_comp, nocab) = '$site_code'				
                    GROUP BY kode_comp,KODE_LANG 				
                )A				
                LEFT JOIN 				
                (				
                SELECT * FROM(SELECT *,CONCAT(KODE_COMP,KODE_LANG,BULAN) as mapp
                    FROM data$tahun.ri  				
                    WHERE concat(kode_comp, nocab) = '$site_code' 				
                    GROUP BY MAPP 				
                    )A				
                )C USING(MAPP)				
                )a group by kode_comp,kode_lang
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;

        return $this->db->query($query);
    }

    public function insert_tabsales($tahun, $site_code)
    {
        $query = "
            insert into data$tahun.tabsales
            SELECT	a.KODESALES,				
                    a.lampiran as NAMASALES,				
                    '' AS KODERAYON,				
                    'S'AS `STATUS`,				
                    '' AS ALAMAT1,				
                    '' AS ALAMAT2,				
                    ''AS NO_TELP,				
                    '' AS KODEPOS,				
                    '' AS PROPINSI,				
                    '' AS DATA1,				
                    '' AS TAHAP,				
                    '' AS FILEID,				
                    '' AS NAMA_DEPO,				
                    KODE_KOTA,				
                    '' AS KODE_GDG,				
                    '' AS NAMA_GDG,				
                    'Y' AS AKTIF,				
                    NOCAB 				
            FROM data$tahun.fi a inner JOIN 				
            (				
                SELECT kodesales, MAX(concat(kodesales,bulan)) times 				
                FROM data$tahun.fi 				
                where concat(kode_comp, nocab) = '$site_code'				
                GROUP BY KODESALES				
            )b ON b.times=concat(a.KODESALES,a.BULAN)				
            where concat(kode_comp, nocab) = '$site_code'				
            GROUP BY kodesales
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_tbkota($tahun, $nocab)
    {
        $query = "
            insert into data$tahun.tbkota
            select 	KODE_COMP AS KODE_COMP, 		
                    KODE_KOTA AS KODE_KOTA,		
                    KODE_KOTA AS NAMA_KOTA,		
                    '$nocab' AS NOCAB		
            from data$tahun.fi		
            where nocab = '$nocab'	
            group by KODE_COMP,KODE_KOTA	
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        
        return $this->db->query($query);
    }

    public function get_bontang_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_bontang_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_userid_by_kode_comp($kode_comp)
    {
        $query = "
            select 	*
            from site.master_user a
            where a.username = '$kode_comp'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_mpm_upload_where_closing_by_userid($userid)
    {
        $query = "
            select a.id, a.userid, a.lastupload, a.filename, a.`status`, a.status_closing, a.tahun, a.bulan
            from mpm.upload a 
            where a.userid = $userid and a.status_closing = 1
            ORDER BY a.id desc
            limit 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_result($site_code, $tahun, $bulan)
    {
        $query = "
            select sum(a.total_unit) as total_unit, sum(a.total_value) as total_value
            from 
            (
                select sum(a.banyak) as total_unit, sum(a.tot1) as total_value
                from data$tahun.fi a 
                where concat(a.kode_comp, a.nocab) = '$site_code' and a.bulan = $bulan
                union all 
                select sum(a.banyak) as total_unit, sum(a.tot1) as total_value
                from data$tahun.ri a 
                where concat(a.kode_comp, a.nocab) = '$site_code' and a.bulan = $bulan
            )a
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_bridging_log($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.bridging_log', $data);
        return $this->db->affected_rows();
    }

    public function insert_upload($data)
    {
        $proses = $this->db->insert('mpm.upload', $data);
        return $this->db->insert_id();
    }

    public function get_bontang_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_bontang_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function create_table_samarinda_import()
    {
        $this->db->query("drop table if exists site.bridging_samarinda_import");

        $create_table = "
            create table if not exists site.bridging_samarinda_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                distributor varchar(255),
                cabang varchar(255),
                tipetrans varchar(255),
                divisi varchar(255),
                principal varchar(255),
                productgroup1 varchar(255),
                productgroup2 varchar(255),
                productgroup3 varchar(255),
                brand varchar(255),
                kodeproduk varchar(255),
                kodevarian varchar(255),
                kodeprodukprincipal varchar(255),
                namaproduk varchar(255),
                packaging varchar(255),
                productclass varchar(255),
                kodecustomer varchar(255),
                namacustomer varchar(255),
                alamatcustomer text,
                area varchar(255),
                subarea varchar(255),
                channel varchar(255),
                subchannel varchar(255),
                customergroup varchar(255),
                keyaccount varchar(255),
                kodesalesman varchar(255),
                namasalesman varchar(255),
                kodesalesco varchar(255),
                namasalesco varchar(255),
                kodespv varchar(255),
                namaspv varchar(255),
                tahunbulan varchar(255),
                bulan varchar(255),
                tanggal varchar(255),
                weekno varchar(255),
                nomornota varchar(255),
                salesmethod varchar(255),
                sellingtype varchar(255),
                qtysold varchar(255),
                qtysoldcrt varchar(255),
                qtysolduom1 varchar(255),
                qtysolduom2 varchar(255),
                qtysolduom3 varchar(255),
                qtysolduom4 varchar(255),
                qtysoldtotalpcs varchar(255),
                freegoodtotalpcs varchar(255),
                tonnage varchar(255),
                volume varchar(255),
                grossamount varchar(255),
                linediscount1 varchar(255),
                linediscount2 varchar(255),
                linediscount3 varchar(255),
                linediscount4 varchar(255),
                linediscount5 varchar(255),
                totallinediscount varchar(255),
                discountnota1 varchar(255),
                discountnota2 varchar(255),
                discountnota3 varchar(255),
                totaldiscountnota varchar(255),
                dpp varchar(255),
                ppn varchar(255),
                ppnbm varchar(255),
                tax varchar(255),
                netamount varchar(255),
                warehouse varchar(255),
                customerpo varchar(255),
                customerjoindate varchar(255),
                nofakturpajak varchar(255),
                tanggalfakturpajak varchar(255),
                nomorfakturproforma varchar(255),
                tanggalfakturproforma varchar(255),
                term varchar(255),
                uom1 varchar(255),
                uom2 varchar(255),
                uom3 varchar(255),
                uom4 varchar(255),
                isiuom1 varchar(255),
                isiuom2 varchar(255),
                isiuom3 varchar(255),
                sellingprice varchar(255),
                cogs varchar(255),
                sellingpriceinkg varchar(255),
                caseweightinkg varchar(255),
                qtyordertotalpcs varchar(255),
                tslqtysoldnfg varchar(255),
                tslconvpcstoctn varchar(255),
                tsltonnagesoldfg varchar(255),
                `end` varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_samarinda_import($data)
    {
        $this->db->insert('site.bridging_samarinda_import', $data);
        return $this->db->affected_rows();
    }

    public function get_samarinda_import()
    {
        $query = "
            select *
            from site.bridging_samarinda_import a
        ";
        return $this->db->query($query);
    }

    public function get_samarinda_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.grossamount) as sumgrossamount, 
                    FORMAT(sum(a.qtysoldtotalpcs),0) as sumqty, 
                    format(sum(a.freegoodtotalpcs),0) as sumfreegood,
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_samarinda_import a
        ";
        return $this->db->query($query);
    }

    public function get_samarinda_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_samarinda_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_samarinda_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_samarinda_import_customer");

        $create_table = "
            create table if not exists site.bridging_samarinda_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_samarinda_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_samarinda_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_samarinda_import_customer($data)
    {
        $this->db->insert('site.bridging_samarinda_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_samarinda_import_customer()
    {
        $query = "
            select *
            from site.bridging_samarinda_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_samarinda_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_samarinda_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_samarinda_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_samarinda_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_fi_samarinda($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldtotalpcs as banyak, (a.grossamount * 1.11) / a.qtysoldtotalpcs as harga, '' as potongan, 
                    (a.grossamount * 1.11) as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount * 1.11)/a.qtysoldtotalpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT2/(a.GROSSAMOUNT-a.LINEDISCOUNT1))*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT3/(a.GROSSAMOUNT-a.LINEDISCOUNT1-a.LINEDISCOUNT2))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 * 1.11 as rp_cabang, a.LINEDISCOUNT2 * 1.11 as rp_prinsipal, a.LINEDISCOUNT3 * 1.11 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, a.LINEDISCOUNT4 as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_samarinda_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_samarinda_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_samarinda_bonus($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    0 as banyak, (a.grossamount * 1.11) / a.qtysoldtotalpcs as harga, '' as potongan, 
                    '' as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, a.freegoodtotalpcs as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount * 1.11)/a.qtysoldtotalpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, a.freegoodtotalpcs as qty_bonus, '1' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                    '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_samarinda_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_samarinda_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.freegoodtotalpcs !=0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_samarinda($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldtotalpcs as banyak, (a.grossamount * 1.11)/a.qtysoldtotalpcs as harga, '' as potongan, 
                    (a.grossamount * 1.11) as tot1, '' as jum_promo, 
                    '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi, '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut, 'PST' as kode_gdg, 
                    '' as nama_gdg, b.class_id as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus, 	
                    a.freegoodtotalpcs as unitbonus, namasalesman as lampiran, 
                    (a.grossamount * 1.11) / a.qtysoldtotalpcs as h_beli, '' as kodearea, b.alamat as namarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as nama_lang, 
                    '$nocab' as nocab, '$bulan' as bulan, '' as siteid, '' as qty1, 
                    '' as qty2, 
                    '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen, '' as disc_rp,  
                    '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT2/(a.GROSSAMOUNT-a.LINEDISCOUNT1))*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT3/(a.GROSSAMOUNT-a.LINEDISCOUNT1-a.LINEDISCOUNT2))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 * 1.11 as rp_cabang, a.LINEDISCOUNT2 * 1.11 as rp_prinsipal, a.LINEDISCOUNT3 * 1.11 as rp_xtra, '' as bonus, concat('11',c.supp) as prinsipalid, '' as ex_no_sales, '' as status_retur, '' as ref, 
                    '' as term_payment, '' as tipe_kl, a.LINEDISCOUNT4 as disc_cod, '' as rp_cod, '' as beban_bonus, '' as disc_add_percen, '' as subarea_id
            from site.bridging_samarinda_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_samarinda_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans != 'sales'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_fi_samarinda($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_samarinda($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_samarinda($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_samarinda($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_samarinda($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_kolaka_import()
    {
        $this->db->query("drop table if exists site.bridging_kolaka_import");

        $create_table = "
            create table if not exists site.bridging_kolaka_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                siteid varchar(255),
                nosales varchar(255),
                tanggal_sales varchar(255),
                salesmanid varchar(255),
                nama_salesman varchar(255),
                customerid varchar(255),
                nama_customer varchar(255),
                productid varchar(255),
                product_descr varchar(255),
                flag_retur varchar(255),
                flag_bonus varchar(255),
                harga varchar(255),
                qty varchar(255),
                bruto varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra text,
                rp_xtra varchar(255),
                disc_cash varchar(255),
                rp_cash varchar(255),
                netto varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function get_kolaka_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_kolaka_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_kolaka_import($data)
    {
        $this->db->insert('site.bridging_kolaka_import', $data);
        return $this->db->affected_rows();
    }

    public function get_kolaka_import()
    {
        $query = "
            select *
            from site.bridging_kolaka_import a
        ";
        return $this->db->query($query);
    }

    public function get_kolaka_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_kolaka_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_kolaka_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.bruto) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_kolaka_import a
        ";
        return $this->db->query($query);
    }

    public function create_table_kolaka_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_kolaka_import_customer");

        $create_table = "
            create table if not exists site.bridging_kolaka_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_kolaka_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_kolaka_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_kolaka_import_customer($data)
    {
        $this->db->insert('site.bridging_kolaka_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_kolaka_import_customer()
    {
        $query = "
            select *
            from site.bridging_kolaka_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_kolaka_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_kolaka_import_customer a
        ";
        return $this->db->query($query);
    }

    public function delete_fi_kolaka($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_kolaka($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_kolaka($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.flag_bonus = 1, '0', a.qty) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.flag_bonus = 1, '0', a.bruto) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.flag_bonus = 1, a.qty, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.flag_bonus = 1, a.qty, '') as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_kolaka_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_kolaka_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_kolaka($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.flag_bonus = 1, '0', a.qty) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.flag_bonus = 1, '0', a.bruto) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.flag_bonus = 1, a.qty, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.flag_bonus = 1, a.qty, '') as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_kolaka_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_kolaka_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_kolaka($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_kolaka($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_kolaka($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_kendari_import()
    {
        $this->db->query("drop table if exists site.bridging_kendari_import");

        $create_table = "
            create table if not exists site.bridging_kendari_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                siteid varchar(255),
                nosales varchar(255),
                tanggal_sales varchar(255),
                salesmanid varchar(255),
                nama_salesman varchar(255),
                customerid varchar(255),
                nama_customer varchar(255),
                productid varchar(255),
                product_descr varchar(255),
                flag_retur varchar(255),
                flag_bonus varchar(255),
                harga varchar(255),
                qty varchar(255),
                bruto varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra text,
                rp_xtra varchar(255),
                disc_cash varchar(255),
                rp_cash varchar(255),
                netto varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function get_kendari_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_kendari_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_kendari_import($data)
    {
        $this->db->insert('site.bridging_kendari_import', $data);
        return $this->db->affected_rows();
    }

    public function get_kendari_import()
    {
        $query = "
            select *
            from site.bridging_kendari_import a
        ";
        return $this->db->query($query);
    }

    public function get_kendari_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_kendari_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_kendari_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.bruto) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_kendari_import a
        ";
        return $this->db->query($query);
    }

    public function create_table_kendari_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_kendari_import_customer");

        $create_table = "
            create table if not exists site.bridging_kendari_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>"; die;
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_kendari_import_customer($column)
    {
        
        $query = "
            ALTER TABLE site.bridging_kendari_import_customer
            ADD UNIQUE ($column);
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_kendari_import_customer($data)
    {
        $this->db->insert('site.bridging_kendari_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_kendari_import_customer()
    {
        $query = "
            select *
            from site.bridging_kendari_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_kendari_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_kendari_import_customer a
        ";
        return $this->db->query($query);
    }

    public function delete_fi_kendari($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_ri_kendari($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_fi_kendari($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.flag_bonus = 1, '0', a.qty) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.flag_bonus = 1, '0', a.bruto) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.flag_bonus = 1, a.qty, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.flag_bonus = 1, a.qty, '') as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_kendari_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_kendari_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_ri_kendari($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.flag_bonus = 1, '0', a.qty) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.flag_bonus = 1, '0', a.bruto) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.flag_bonus = 1, a.qty, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.flag_bonus = 1, a.qty, '') as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_kendari_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_kendari_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tblang_kendari($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tabsales_kendari($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_kendari($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_baubau_import()
    {
        $this->db->query("drop table if exists site.bridging_baubau_import");

        $create_table = "
            create table if not exists site.bridging_baubau_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                siteid varchar(255),
                nosales varchar(255),
                tanggal_sales varchar(255),
                salesmanid varchar(255),
                nama_salesman varchar(255),
                customerid varchar(255),
                nama_customer varchar(255),
                productid varchar(255),
                product_descr varchar(255),
                flag_retur varchar(255),
                flag_bonus varchar(255),
                harga varchar(255),
                qty varchar(255),
                bruto varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra text,
                rp_xtra varchar(255),
                disc_cash varchar(255),
                rp_cash varchar(255),
                netto varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function get_baubau_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_baubau_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function get_baubau_import()
    {
        $query = "
            select *
            from site.bridging_baubau_import a
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        return $this->db->query($query);
    }

    public function get_baubau_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_baubau_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_baubau_import($data)
    {
        $this->db->insert('site.bridging_baubau_import', $data);
        return $this->db->affected_rows();
    }

    public function get_baubau_import_summary()
    {
        $query = "
            select 	count(*) as count, SUM(IF(a.flag_bonus = 0, a.bruto, 0)) AS sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_baubau_import a
        ";
        return $this->db->query($query);
    }

    public function delete_fi_baubau($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_ri_baubau($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_fi_baubau($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.flag_bonus = 1, '0', a.qty) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.flag_bonus = 1, '0', a.bruto) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.flag_bonus = 1, a.qty, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.flag_bonus = 1, a.qty, '') as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_baubau_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_baubau_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_ri_baubau($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.flag_bonus = 1, '0', a.qty) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.flag_bonus = 1, '0', a.bruto) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.flag_bonus = 1, a.qty, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.flag_bonus = 1, a.qty, '') as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_baubau_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_baubau_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tblang_baubau($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tabsales_baubau($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_baubau($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab';
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }


    public function create_table_baubau_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_baubau_import_customer");

        $create_table = "
            create table if not exists site.bridging_baubau_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>"; die;
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_baubau_import_customer($column)
    {
        
        $query = "
            ALTER TABLE site.bridging_baubau_import_customer
            ADD UNIQUE ($column);
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_baubau_import_customer($data)
    {
        $this->db->insert('site.bridging_baubau_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_baubau_import_customer()
    {
        $query = "
            select *
            from site.bridging_baubau_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_baubau_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_baubau_import_customer a
        ";
        return $this->db->query($query);
    }

    public function create_table_mms_makasar_import()
    {
        $this->db->query("drop table if exists site.bridging_mms_makasar_import");

        $create_table = "
            create table if not exists site.bridging_mms_makasar_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                no varchar(255),
                tanggal varchar(255),
                nota_manual varchar(255),
                nama_pelanggan varchar(255),
                kode_barang varchar(255),
                nama_barang varchar(255),
                raw_qty1 varchar(255),
                raw_besar varchar(255),
                raw_qty2 varchar(255),
                raw_sedang varchar(255),
                raw_qty3 varchar(255),
                raw_kecil varchar(255),
                m_qty1 varchar(255),
                m_besar varchar(255),
                m_qty2 varchar(255),
                m_sedang varchar(255),
                m_qty3 varchar(255),
                m_kecil varchar(255),
                r_qty1 varchar(255),
                r_qty2 varchar(255),
                r_qty3 varchar(255),
                total_unit varchar(255),
                harga varchar(255),
                jumlah varchar(255),
                Tot_Diskon varchar(255),
                Netto varchar(255),
                persen_global varchar(255),
                netto_2 varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra varchar(255),
                rp_xtra varchar(255),
                Pot_Rp varchar(255),
                salesman_id varchar(255),
                nama_salesman varchar(255),
                ALAMAT varchar(255),
                KOTA varchar(255),
                customerid varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";die;
        
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_mms_makasar_import($data)
    {
        $this->db->insert('site.bridging_mms_makasar_import', $data);
        return $this->db->affected_rows();
    }

    public function get_mms_makasar_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_mms_makasar_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function get_mms_makasar_import()
    {
        $query = "
            select *
            from site.bridging_mms_makasar_import a
        ";
        return $this->db->query($query);
    }

    public function get_mms_makasar_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_mms_makasar_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_mms_makasar_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.jumlah) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_mms_makasar_import a
        ";
        return $this->db->query($query);
    }

    public function delete_fi($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_ri($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_fi_mms_makasar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nota_manual as nodokjdi, 
                    a.nota_manual as nodokacu, 
                    a.tanggal as tgldokjdi,
                    a.salesman_id as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.kode_barang as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, 
                    year(a.tanggal) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.disc_cabang = 100, '0', a.total_unit) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.disc_cabang = 100, '0', a.jumlah) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.disc_cabang = 100, a.total_unit, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.disc_cabang = 100, a.total_unit, '') as qty_bonus, 
                    if(a.disc_cabang = 100, 1, '') as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mms_makasar_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mms_makasar_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kode_barang = c.kodeprod
            where a.jumlah >= 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_ri_mms_makasar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                    a.nota_manual as nodokjdi, 
                    a.nota_manual as nodokacu, 
                    a.tanggal as tgldokjdi,
                    a.salesman_id as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.kode_barang as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, 
                    year(a.tanggal) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.disc_cabang = 100, '0', a.total_unit) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.disc_cabang = 100, '0', a.jumlah) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.disc_cabang = 100, a.total_unit, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl,
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.disc_cabang = 100, a.total_unit, '') as qty_bonus, 
                    if(a.disc_cabang = 100, 1, '') as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mms_makasar_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mms_makasar_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kode_barang = c.kodeprod
            where a.jumlah < 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tblang($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tabsales($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_mms_makasar_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_mms_makasar_import_customer");

        $create_table = "
            create table if not exists site.bridging_mms_makasar_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>"; die;
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_mms_makasar_import_customer($column)
    {
        
        $query = "
            ALTER TABLE site.bridging_mms_makasar_import_customer
            ADD UNIQUE ($column);
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_mms_makasar_import_customer($data)
    {
        $this->db->insert('site.bridging_mms_makasar_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_mms_makasar_import_customer()
    {
        $query = "
            select *
            from site.bridging_mms_makasar_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mms_makasar_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_mms_makasar_import_customer a
        ";
        return $this->db->query($query);
    }

    public function create_table_mms_bone_import()
    {
        $this->db->query("drop table if exists site.bridging_mms_bone_import");

        $create_table = "
            create table if not exists site.bridging_mms_bone_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                no varchar(255),
                tanggal varchar(255),
                nota_manual varchar(255),
                nama_pelanggan varchar(255),
                kode_barang varchar(255),
                nama_barang varchar(255),
                raw_qty1 varchar(255),
                raw_besar varchar(255),
                raw_qty2 varchar(255),
                raw_sedang varchar(255),
                raw_qty3 varchar(255),
                raw_kecil varchar(255),
                m_qty1 varchar(255),
                m_besar varchar(255),
                m_qty2 varchar(255),
                m_sedang varchar(255),
                m_qty3 varchar(255),
                m_kecil varchar(255),
                r_qty1 varchar(255),
                r_qty2 varchar(255),
                r_qty3 varchar(255),
                total_unit varchar(255),
                harga varchar(255),
                jumlah varchar(255),
                Tot_Diskon varchar(255),
                Netto varchar(255),
                persen_global varchar(255),
                netto_2 varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra varchar(255),
                rp_xtra varchar(255),
                Pot_Rp varchar(255),
                salesman_id varchar(255),
                nama_salesman varchar(255),
                ALAMAT varchar(255),
                KOTA varchar(255),
                customerid varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";die;
        
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_mms_bone_import($data)
    {
        $this->db->insert('site.bridging_mms_bone_import', $data);
        return $this->db->affected_rows();
    }

    public function get_mms_bone_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_mms_bone_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function get_mms_bone_import()
    {
        $query = "
            select *
            from site.bridging_mms_bone_import a
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_mms_bone_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_mms_bone_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_mms_bone_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.jumlah) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_mms_bone_import a
        ";
        return $this->db->query($query);
    }

    public function insert_fi_mms_bone($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nota_manual as nodokjdi, 
                    a.nota_manual as nodokacu, 
                    a.tanggal as tgldokjdi,
                    a.salesman_id as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.kode_barang as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, 
                    year(a.tanggal) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.disc_cabang = 100, '0', a.total_unit) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.disc_cabang = 100, '0', a.jumlah) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.disc_cabang = 100, a.total_unit, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.disc_cabang = 100, a.total_unit, '') as qty_bonus, 
                    if(a.disc_cabang = 100, 1, '') as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mms_bone_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mms_bone_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kode_barang = c.kodeprod
            where a.jumlah >= 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_ri_mms_bone($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                    a.nota_manual as nodokjdi, 
                    a.nota_manual as nodokacu, 
                    a.tanggal as tgldokjdi,
                    a.salesman_id as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.kode_barang as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, 
                    year(a.tanggal) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.disc_cabang = 100, '0', a.total_unit) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.disc_cabang = 100, '0', a.jumlah) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.disc_cabang = 100, a.total_unit, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl,
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.disc_cabang = 100, a.total_unit, '') as qty_bonus, 
                    if(a.disc_cabang = 100, 1, '') as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mms_bone_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mms_bone_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kode_barang = c.kodeprod
            where a.jumlah < 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function create_table_mms_bone_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_mms_bone_import_customer");

        $create_table = "
            create table if not exists site.bridging_mms_bone_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>"; die;
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_mms_bone_import_customer($column)
    {
        
        $query = "
            ALTER TABLE site.bridging_mms_bone_import_customer
            ADD UNIQUE ($column);
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_mms_bone_import_customer($data)
    {
        $this->db->insert('site.bridging_mms_bone_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_mms_bone_import_customer()
    {
        $query = "
            select *
            from site.bridging_mms_bone_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mms_bone_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_mms_bone_import_customer a
        ";
        return $this->db->query($query);
    }

    public function create_table_mms_parepare_import()
    {
        $this->db->query("drop table if exists site.bridging_mms_parepare_import");

        $create_table = "
            create table if not exists site.bridging_mms_parepare_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                no varchar(255),
                tanggal varchar(255),
                nota_manual varchar(255),
                nama_pelanggan varchar(255),
                kode_barang varchar(255),
                nama_barang varchar(255),
                raw_qty1 varchar(255),
                raw_besar varchar(255),
                raw_qty2 varchar(255),
                raw_sedang varchar(255),
                raw_qty3 varchar(255),
                raw_kecil varchar(255),
                m_qty1 varchar(255),
                m_besar varchar(255),
                m_qty2 varchar(255),
                m_sedang varchar(255),
                m_qty3 varchar(255),
                m_kecil varchar(255),
                r_qty1 varchar(255),
                r_qty2 varchar(255),
                r_qty3 varchar(255),
                total_unit varchar(255),
                harga varchar(255),
                jumlah varchar(255),
                Tot_Diskon varchar(255),
                Netto varchar(255),
                persen_global varchar(255),
                netto_2 varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra varchar(255),
                rp_xtra varchar(255),
                Pot_Rp varchar(255),
                salesman_id varchar(255),
                nama_salesman varchar(255),
                ALAMAT varchar(255),
                KOTA varchar(255),
                customerid varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";die;
        
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function get_mms_parepare_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_mms_parepare_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_mms_parepare_import($data)
    {
        $this->db->insert('site.bridging_mms_parepare_import', $data);
        return $this->db->affected_rows();
    }

    public function get_mms_parepare_import()
    {
        $query = "
            select *
            from site.bridging_mms_parepare_import a
        ";
        return $this->db->query($query);
    }

    public function get_mms_parepare_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_mms_parepare_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_mms_parepare_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.jumlah) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_mms_parepare_import a
        ";
        return $this->db->query($query);
    }

    public function delete_fi_mms_parepare($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_ri_mms_parepare($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_fi_mms_parepare($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nota_manual as nodokjdi, 
                    a.nota_manual as nodokacu, 
                    a.tanggal as tgldokjdi,
                    a.salesman_id as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.kode_barang as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, 
                    year(a.tanggal) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.disc_cabang = 100, '0', a.total_unit) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.disc_cabang = 100, '0', a.jumlah) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.disc_cabang = 100, a.total_unit, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.disc_cabang = 100, a.total_unit, '') as qty_bonus, 
                    if(a.disc_cabang = 100, 1, '') as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mms_parepare_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mms_parepare_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kode_barang = c.kodeprod
            where a.jumlah >= 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_ri_mms_parepare($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                    a.nota_manual as nodokjdi, 
                    a.nota_manual as nodokacu, 
                    a.tanggal as tgldokjdi,
                    a.salesman_id as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.kode_barang as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, 
                    year(a.tanggal) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.disc_cabang = 100, '0', a.total_unit) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.disc_cabang = 100, '0', a.jumlah) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.disc_cabang = 100, a.total_unit, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl,
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.disc_cabang = 100, a.total_unit, '') as qty_bonus, 
                    if(a.disc_cabang = 100, 1, '') as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mms_parepare_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mms_parepare_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kode_barang = c.kodeprod
            where a.jumlah < 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tblang_mms_parepare($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tabsales_mms_parepare($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_mms_parepare($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }


    public function create_table_mms_parepare_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_mms_parepare_import_customer");

        $create_table = "
            create table if not exists site.bridging_mms_parepare_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>"; die;
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_mms_parepare_import_customer($column)
    {
        
        $query = "
            ALTER TABLE site.bridging_mms_parepare_import_customer
            ADD UNIQUE ($column);
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_mms_parepare_import_customer($data)
    {
        $this->db->insert('site.bridging_mms_parepare_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_mms_parepare_import_customer()
    {
        $query = "
            select *
            from site.bridging_mms_parepare_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mms_parepare_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_mms_parepare_import_customer a
        ";
        return $this->db->query($query);
    }

    public function create_table_pekanbaru_import()
    {
        $this->db->query("drop table if exists site.bridging_pekanbaru_import");

        $create_table = "
            create table if not exists site.bridging_pekanbaru_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                nota varchar(255),
                no varchar(255),
                tgl varchar(255),
                tgljatuh varchar(255),
                kode varchar(255),
                kodewila varchar(255),
                kodenpwp varchar(255),
                pof varchar(255),
                lks varchar(255),
                cash varchar(255),
                kodebara varchar(255),
                namabara varchar(255),
                gol varchar(255),
                merk varchar(255),
                satuan varchar(255),
                qty varchar(255),
                rasio varchar(255),
                qtykecil varchar(255),
                qtybonus varchar(255),
                harga varchar(255),
                discpersen varchar(255),
                discisi1 varchar(255),
                discisi2 varchar(255),
                discpcs varchar(255),
                discnom varchar(255),
                jumlah varchar(255),
                ppn varchar(255),
                catat1 varchar(255),
                catat2 varchar(255),
                username varchar(255),
                auditname varchar(255),
                tglinput varchar(255),
                ket varchar(255),
                terpilih varchar(255),
                audit varchar(255),
                kategori varchar(255),
                kodeout varchar(255),
                printsp varchar(255),
                printspb varchar(255),
                panjar varchar(255),
                ongkos varchar(255),
                ongppn varchar(255),
                notappn varchar(255),
                jenisjual varchar(255),
                jenisdisc varchar(255),
                persenppn varchar(255),
                poin1 varchar(255),
                nota_po varchar(255),
                urut_po varchar(255),
                opname varchar(255),
                sn varchar(255),
                ref varchar(255),
                km varchar(255),
                garansi varchar(255),
                check1 varchar(255),
                qtypo varchar(255),
                bayar varchar(255),
                eppn varchar(255),
                hrgvalas varchar(255),
                kodevalas varchar(255),
                kurs varchar(255),
                kodealias varchar(255),
                nobatch varchar(255),
                tgl_exp varchar(255),
                kondisi varchar(255),
                tglretur varchar(255),
                divisi varchar(255),
                discisi3 varchar(255),
                discpcs2 varchar(255),
                discpcs3 varchar(255),
                dbox varchar(255),
                card varchar(255),
                namacard varchar(255),
                namabank varchar(255),
                konsinyasi varchar(255),
                cekst varchar(255),
                cekop varchar(255),
                hapus varchar(255),
                hrgdasar varchar(255),
                hrgdisc varchar(255),
                upload varchar(255),
                notapanjar varchar(255),
                potopanjar varchar(255),
                potoppn varchar(255),
                jam varchar(255),
                bd varchar(255),
                expedisi varchar(255),
                notadok varchar(255),
                tgldok varchar(255),
                fn varchar(255),
                qtybruto varchar(255),
                kadar varchar(255),
                reward varchar(255),
                printdo varchar(255),
                realisasi varchar(255),
                materai varchar(255),
                fee varchar(255),
                pph varchar(255),
                jlh_pph varchar(255),
                kodearea varchar(255),
                nama varchar(255),
                alamat1 varchar(255),
                kota varchar(255),
                barkode varchar(255),
                namapof varchar(255),
                jalur varchar(255),
                DISCISI1_RP varchar(255),
                DISCISI2_RP varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function get_pekanbaru_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_pekanbaru_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_pekanbaru_import($data)
    {
        $this->db->insert('site.bridging_pekanbaru_import', $data);
        return $this->db->affected_rows();
    }

    public function get_pekanbaru_import()
    {
        $query = "
            select *
            from site.bridging_pekanbaru_import a
        ";
        return $this->db->query($query);
    }

    public function get_pekanbaru_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_pekanbaru_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pekanbaru_import_summary_old()
    {
        $query = "
            select 	count(*) as count, sum(a.bruto) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_pekanbaru_import a
        ";
        return $this->db->query($query);
    }

    public function get_pekanbaru_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.jumlah) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_pekanbaru_import a
        ";
        return $this->db->query($query);
    }

    public function delete_fi_pekanbaru($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_pekanbaru($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_pekanbaru_old($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    a.qty as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    a.bruto as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    '' as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    '' as qty_bonus, 
                    '' as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen
            from site.bridging_pekanbaru_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_pekanbaru_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.flag_retur = 0
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_pekanbaru_bonus_old($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    '' as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    '' as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    a.qty_bonus as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    '' as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    '' as disc_cabang, 
                    '' as disc_prinsipal, 
                    '' as disc_xtra,
                    '' as rp_cabang, 
                    '' as rp_prinsipal, 
                    '' as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen
            from site.bridging_pekanbaru_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_pekanbaru_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.flag_retur = 0 and a.flag_bonus = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    // public function insert_fi_pekanbaru($kode_comp, $nocab, $tahun, $bulan)
    // {

    //     $query = "
    //         insert into data$tahun.fi
    //         select 	'07' as kddokjdi, 
    //                 a.nota as nodokjdi, 
    //                 a.nota as nodokacu, 
    //                 a.tgl as tgldokjdi,
    //                 a.pof as kodesales, 
    //                 '$kode_comp' as kode_comp, 
    //                 b.kota_id as kode_kota, 
    //                 b.type_id as kode_type,
    //                 b.customer_id as kode_lang, 
    //                 '' as koderayon, 
    //                 a.kodebara as kodeprod, 
    //                 c.supp, 
    //                 day(a.tgl) as hrdok,
    //                 DATE_FORMAT(a.tgl, '%m') as blndok, 
    //                 year(a.tgl) as thndok, 
    //                 c.namaprod, 
    //                 c.grupprod as groupprod,
    //                 a.qtykecil as banyak, 
    //                 a.harga*1.11 as harga, 
    //                 '' as potongan, 
    //                 a.jumlah*1.11 as tot1, 
    //                 '' as jum_promo, 
    //                 '' as keterangan, 
    //                 '' as user_isi, 
    //                 '' as jam_isi, 
    //                 '' as tgl_isi,
    //                 '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
    //                 '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
    //                 '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
    //                 b.class_id as kodesalur, 
    //                 '' as kodebonus, '' as namabonus, '' as grupbonus, 
    //                 '' as unitbonus, 
    //                 a.namapof as lampiran,
    //                 '' as h_beli, 
    //                 '' as kodearea, 
    //                 b.alamat as namaarea,
    //                 '' as pinjam, 
    //                 '' as jualbanyak, 
    //                 '' as jualpinjam, 
    //                 '' as harga_excl, 
    //                 '' as tot1_excl, 
    //                 b.nama_customer as namalang, 
    //                 '$nocab' as nocab, 
    //                 '$bulan' as bulan,
    //                 '' as siteid, 
    //                 '' as qty1, 
    //                 '' as qty2, 
    //                 '' as qty3, 
    //                 '' as qty_bonus, 
    //                 '' as flag_bonus, 
    //                 '' as disc_persen,
    //                 '' as disc_rp, 
    //                 '' as disc_value, 
    //                 a.DISCISI1 as disc_cabang, 
    //                 a.DISCISI2 as disc_prinsipal, 
    //                 '' as disc_xtra,
    //                 '' as rp_cabang, 
    //                 a.DISCNOM as rp_prinsipal, 
    //                 '' as rp_xtra, 
    //                 '' as bonus, 
    //                 concat('11', c.supp) as principalid,
    //                 '' as ex_no_sales, 
    //                 '' as status_retur, 
    //                 '' as ref,
    //                 '' as term_payment, 
    //                 '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
    //                 '' as disc_add_percen
    //         from site.bridging_pekanbaru_import a left join (
    //             select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
    //             from site.bridging_pekanbaru_import_customer a
    //         )b on a.kode = b.mapping_uli left join (
    //             select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
    //             from site.master_product_with_harga a 
    //         )c on a.kodebara = c.kodeprod
    //         where a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
    //     ";

    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";die;
    //     return $this->db->query($query);
    // }

    // public function insert_fi_pekanbaru_bonus($kode_comp, $nocab, $tahun, $bulan)
    // {

    //     $query = "
    //         insert into data$tahun.fi
    //         select 	'07' as kddokjdi, 
    //                 a.nota as nodokjdi, 
    //                 a.nota as nodokacu, 
    //                 a.tgl as tgldokjdi,
    //                 a.pof as kodesales, 
    //                 '$kode_comp' as kode_comp, 
    //                 b.kota_id as kode_kota, 
    //                 b.type_id as kode_type,
    //                 b.customer_id as kode_lang, 
    //                 '' as koderayon, 
    //                 a.kodebara as kodeprod, 
    //                 c.supp, 
    //                 day(a.tgl) as hrdok,
    //                 DATE_FORMAT(a.tgl, '%m') as blndok, 
    //                 year(a.tgl) as thndok, 
    //                 c.namaprod, 
    //                 c.grupprod as groupprod,
    //                 '' as banyak, 
    //                 a.harga*1.11 as harga, 
    //                 '' as potongan, 
    //                 '' as tot1, 
    //                 '' as jum_promo, 
    //                 '' as keterangan, 
    //                 '' as user_isi, 
    //                 '' as jam_isi, 
    //                 '' as tgl_isi,
    //                 '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
    //                 '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
    //                 '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
    //                 b.class_id as kodesalur, 
    //                 '' as kodebonus, '' as namabonus, '' as grupbonus, 
    //                 a.qtybonus as unitbonus, 
    //                 a.namapof as lampiran,
    //                 '' as h_beli, 
    //                 '' as kodearea, 
    //                 b.alamat as namaarea,
    //                 '' as pinjam, 
    //                 '' as jualbanyak, 
    //                 '' as jualpinjam, 
    //                 '' as harga_excl, 
    //                 '' as tot1_excl, 
    //                 b.nama_customer as namalang, 
    //                 '$nocab' as nocab, 
    //                 '$bulan' as bulan,
    //                 '' as siteid, 
    //                 '' as qty1, 
    //                 '' as qty2, 
    //                 '' as qty3, 
    //                 a.qtybonus as qty_bonus, 
    //                 '1' as flag_bonus, 
    //                 '' as disc_persen,
    //                 '' as disc_rp, 
    //                 '' as disc_value, 
    //                 '' as disc_cabang, 
    //                 '' as disc_prinsipal, 
    //                 '' as disc_xtra,
    //                 '' as rp_cabang, 
    //                 '' as rp_prinsipal, 
    //                 '' as rp_xtra, 
    //                 '' as bonus, 
    //                 concat('11', c.supp) as principalid,
    //                 '' as ex_no_sales, 
    //                 '' as status_retur, 
    //                 '' as ref,
    //                 '' as term_payment, 
    //                 '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
    //                 '' as disc_add_percen
    //         from site.bridging_pekanbaru_import a left join (
    //             select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
    //             from site.bridging_pekanbaru_import_customer a
    //         )b on a.kode = b.mapping_uli left join (
    //             select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
    //             from site.master_product_with_harga a 
    //         )c on a.kodebara = c.kodeprod
    //         where a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.qtybonus > 0
    //     ";

    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";die;
    //     return $this->db->query($query);
    // }

    public function insert_fi_pekanbaru($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nota as nodokjdi, 
                    a.nota as nodokacu, 
                    a.tgl as tgldokjdi,
                    a.pof as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.kodebara as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tgl, '%d') as hrdok,
                    DATE_FORMAT(a.tgl, '%m') as blndok, 
                    year(a.tgl) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    a.qtykecil as banyak, 
                    a.harga*1.11 as harga, 
                    '' as potongan, 
                    if(discisi2 = 100, 0, a.jumlah*1.11) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    '' as unitbonus, 
                    a.namapof as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    '' as qty_bonus, 
                    '' as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.DISCISI1 as disc_cabang, 
                    a.DISCISI2 as disc_prinsipal, 
                    '' as disc_xtra,
                    (a.DISCISI1_RP*1.11) as rp_cabang, 
                    (a.DISCISI2_RP*1.11) as rp_prinsipal, 
                    (a.DISCNOM*1.11) as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,
                    '' as subarea_id
            from site.bridging_pekanbaru_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_pekanbaru_import_customer a
            )b on a.kode = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodebara = c.kodeprod
            where a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.DISCISI2 != 100
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_fi_pekanbaru_bonus($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nota as nodokjdi, 
                    a.nota as nodokacu, 
                    a.tgl as tgldokjdi,
                    a.pof as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.kodebara as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tgl, '%d') as hrdok,
                    DATE_FORMAT(a.tgl, '%m') as blndok, 
                    year(a.tgl) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    '' as banyak, 
                    a.harga*1.11 as harga, 
                    '' as potongan, 
                    '' as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(discisi2 = 100, a.qtykecil, a.qtybonus) as unitbonus, 
                    a.namapof as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(discisi2 = 100, a.qtykecil, a.qtybonus) as qty_bonus, 
                    '1' as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    '' as disc_cabang, 
                    '' as disc_prinsipal, 
                    '' as disc_xtra,
                    '' as rp_cabang, 
                    '' as rp_prinsipal, 
                    '' as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,
                    '' as subarea_id
            from site.bridging_pekanbaru_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_pekanbaru_import_customer a
            )b on a.kode = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodebara = c.kodeprod
            where a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1 and a.qtybonus > 0 or a.DISCISI2 = 100
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tblang_pekanbaru($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_pekanbaru($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_pekanbaru($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_pekanbaru_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_pekanbaru_import_customer");

        $create_table = "
            create table if not exists site.bridging_pekanbaru_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_pekanbaru_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_pekanbaru_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_pekanbaru_import_customer($data)
    {
        $this->db->insert('site.bridging_pekanbaru_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_bridging_log_bysignature($signature = "")
    {
        if($signature)
        {
            $params_signature = "where signature = '$signature'";
        }else{
            $params_signature = "";
        }
        $query = "
            select a.*, b.status_closing
            from site.bridging_log a
            left join mpm.upload b 
            on a.id_upload = b.id
            $params_signature
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_mpm_upload($data, $id_upload)
    {
        $this->db->where('id', $id_upload);
        $this->db->update('mpm.upload', $data);
        return $this->db->affected_rows();
    }

    public function get_mpm_user($username)
    {
        $query = "
            select a.id
            from mpm.user a
            where a.username = '$username'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_mpm_upload($id, $bulan, $tahun, $status_closing)
    {
        if($status_closing)
        {
            $params_status_closing = "and a.status_closing = $status_closing";
        }else{
            $params_status_closing = "";
        }
        $query = "
            select *
            from mpm.upload a 
            where a.userid = $id and a.bulan = $bulan and a.tahun = $tahun $params_status_closing
            order by a.id desc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pekanbaru_import_customer()
    {
        $query = "
            select *
            from site.bridging_pekanbaru_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_pekanbaru_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_pekanbaru_import_customer a
        ";
        return $this->db->query($query);
    }

    public function create_table_sup_makasar_import()
    {
        $this->db->query("drop table if exists site.bridging_sup_makasar_import");

        $create_table = "
            create table if not exists site.bridging_sup_makasar_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                siteid varchar(255),
                nosales varchar(255),
                tanggal_sales varchar(255),
                salesmanid varchar(255),
                nama_salesman varchar(255),
                customerid varchar(255),
                nama_customer varchar(255),
                productid varchar(255),
                product_descr varchar(255),
                flag_retur varchar(255),
                flag_bonus varchar(255),
                harga varchar(255),
                qty varchar(255),
                bruto varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra text,
                rp_xtra varchar(255),
                disc_cash varchar(255),
                rp_cash varchar(255),
                netto varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function get_sup_makasar_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_sup_makasar_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function create_table_sup_makasar_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_sup_makasar_import_customer");

        $create_table = "
            create table if not exists site.bridging_sup_makasar_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>"; die;
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_sup_makasar_import_customer($column)
    {
        
        $query = "
            ALTER TABLE site.bridging_sup_makasar_import_customer
            ADD UNIQUE ($column);
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_sup_makasar_import($data)
    {
        $this->db->insert('site.bridging_sup_makasar_import', $data);
        return $this->db->affected_rows();
    }

    public function insert_sup_makasar_import_customer($data)
    {
        $this->db->insert('site.bridging_sup_makasar_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_sup_makasar_import_customer()
    {
        $query = "
            select *
            from site.bridging_sup_makasar_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_sup_makasar_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_sup_makasar_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_sup_makasar_import()
    {
        $query = "
            select *
            from site.bridging_sup_makasar_import a
        ";
        return $this->db->query($query);
    }

    public function get_sup_makasar_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_sup_makasar_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_sup_makasar_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.bruto) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_sup_makasar_import a
        ";
        return $this->db->query($query);
    }

    public function delete_fi_sup_makasar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_ri_sup_makasar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_fi_sup_makasar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'07' as KDOKJDI,			
            nosales as NODOKJDI,			
            nosales as NODOKACU,			
            tanggal_sales as TGLDOKJDI,			
            salesmanid as KODESALES,			
            '$kode_comp' as KODE_COMP,			
            '' as KODE_KOTA, 	
            b.type_id AS KODE_TYPE,			
            b.customer_id as KODE_LANG,		
            '' as KODERAYON,			
            c.KODEPROD as KODEPROD,			
            c.supp as SUPP, 			
            RIGHT(tanggal_sales,2) as HRDOK,			
            '$bulan' as BLNDOK,			
            LEFT(tanggal_sales,4) as THNDOK,			
            c.NAMAPROD as NAMAPROD,			
            c.GRUPPROD as GROUPPROD,			
            if(flag_bonus = 1, '0', qty) AS BANYAK,			
            harga AS HARGA,			
            '' as POTONGAN,			
            bruto as TOT1,			
            '' as JUM_PROMO, 			
            '' as KETERANGAN,  			
            '' as USER_ISI,			
            '' as JAM_ISI,			
            '' as TGL_ISI, 			
            '' as USER_EDIT, 			
            '' as JAM_EDIT, 			
            '' as TGL_EDIT,			
            '' as USER_DEL, 			
            '' as JAM_DEL, 			
            '' as TGL_DEL, 			
            '' as NO, 			
            '' as BACKUP, 			
            '' as NO_URUT, 			
            '' as KODE_GDG, 
            '' as NAMA_GDG, 			
            b.class_id AS KODESALUR,			
            '' as KODEBONUS,			
            '' as NAMABONUS, 			
            '' as GRUPBONUS, 			
            if(flag_bonus = 1, qty, '') as UNITBONUS, 			
            nama_salesman as LAMPIRAN, 			
            '' as H_BELI, 			
            '' as KODEAREA, 			
            b.alamat as NAMAAREA, 
            '' as PINJAM, 			
            '' as JUALBANYAK, 			
            '' as JUALPINJAM, 			
            '' as HARGA_EXCL, 			
            '' as TOT1_EXCL, 			
            b.nama_customer as NAMA_LANG,			
            '$nocab' as NOCAB,			
            '$bulan' as BULAN,			
            ' ' as siteid,			
            ' ' as qty1,			
            ' ' as qty2,			
            ' ' as qty3,			
            if(flag_bonus = 1, qty, '') as qty_bonus,			
            flag_bonus as flag_bonus,			
            ' ' as disc_persen,			
            ' ' as disc_rp,			
            ' ' as disc_value,			
            disc_cabang  as disc_cabang,			
            disc_prinsipal as disc_prinsipal,			
            disc_xtra as disc_xtra,			
            rp_cabang as rp_cabang,			
            rp_prinsipal as rp_prinsipal,			
            rp_xtra as rp_xtra,			
            ' ' as bonus,			
            ' ' as prinsipalid,			
            ' ' as ex_no_sales,			
            ' ' as status_retur,			
            ' ' as ref,			
            ' ' as term_payment,			
            ' ' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, ' ' as beban_bonus, ' ' as disc_add_percen, '' as subarea_id			

            from site.bridging_sup_makasar_import a	left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_sup_makasar_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 0
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_ri_sup_makasar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'07' as KDOKJDI,			
            nosales as NODOKJDI,			
            nosales as NODOKACU,			
            tanggal_sales as TGLDOKJDI,			
            salesmanid as KODESALES,			
            '$kode_comp' as KODE_COMP,			
            '' as KODE_KOTA, 	
            b.type_id AS KODE_TYPE,			
            b.customer_id as KODE_LANG,		
            '' as KODERAYON,			
            c.KODEPROD as KODEPROD,			
            c.supp as SUPP, 			
            RIGHT(tanggal_sales,2) as HRDOK,			
            '$bulan' as BLNDOK,			
            LEFT(tanggal_sales,4) as THNDOK,			
            c.NAMAPROD as NAMAPROD,			
            c.GRUPPROD as GROUPPROD,			
            if(flag_bonus = 1, '0', qty) AS BANYAK,			
            harga AS HARGA,			
            '' as POTONGAN,			
            bruto as TOT1,			
            '' as JUM_PROMO, 			
            '' as KETERANGAN,  			
            '' as USER_ISI,			
            '' as JAM_ISI,			
            '' as TGL_ISI, 			
            '' as USER_EDIT, 			
            '' as JAM_EDIT, 			
            '' as TGL_EDIT,			
            '' as USER_DEL, 			
            '' as JAM_DEL, 			
            '' as TGL_DEL, 			
            '' as NO, 			
            '' as BACKUP, 			
            '' as NO_URUT, 			
            '' as KODE_GDG, 
            '' as NAMA_GDG, 			
            b.class_id AS KODESALUR,			
            '' as KODEBONUS,			
            '' as NAMABONUS, 			
            '' as GRUPBONUS, 			
            if(flag_bonus = 1, qty, '') as UNITBONUS, 			
            nama_salesman as LAMPIRAN, 			
            '' as H_BELI, 			
            '' as KODEAREA, 			
            b.alamat as NAMAAREA, 
            '' as PINJAM, 			
            '' as JUALBANYAK, 			
            '' as JUALPINJAM, 			
            '' as HARGA_EXCL, 			
            b.nama_customer as NAMA_LANG,			
            '$nocab' as NOCAB,			
            '$bulan' as BULAN,			
            ' ' as siteid,			
            ' ' as qty1,			
            ' ' as qty2,			
            ' ' as qty3,			
            if(flag_bonus = 1, qty, '') as qty_bonus,			
            flag_bonus as flag_bonus,			
            ' ' as disc_persen,			
            ' ' as disc_rp,			
            ' ' as disc_value,			
            disc_cabang  as disc_cabang,			
            disc_prinsipal as disc_prinsipal,			
            disc_xtra as disc_xtra,			
            rp_cabang as rp_cabang,			
            rp_prinsipal as rp_prinsipal,			
            rp_xtra as rp_xtra,			
            ' ' as bonus,			
            ' ' as prinsipalid,			
            ' ' as ex_no_sales,			
            ' ' as status_retur,			
            ' ' as ref,			
            ' ' as term_payment,			
            ' ' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, ' ' as beban_bonus, ' ' as disc_add_percen, '' as subarea_id			

            from site.bridging_sup_makasar_import a	left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_sup_makasar_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tblang_sup_makasar($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tabsales_sup_makasar($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_sup_makasar($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_tarakan_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_tarakan_import_customer");

        $create_table = "
            create table if not exists site.bridging_tarakan_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>"; die;
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_tarakan_import_customer($column)
    {
        
        $query = "
            ALTER TABLE site.bridging_tarakan_import_customer
            ADD UNIQUE ($column);
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_tarakan_import_customer($data)
    {
        $this->db->insert('site.bridging_tarakan_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_tarakan_import_customer()
    {
        $query = "
            select *
            from site.bridging_tarakan_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_tarakan_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_tarakan_import_customer a
        ";
        return $this->db->query($query);
    }

    public function create_table_tarakan_import()
    {
        $this->db->query("drop table if exists site.bridging_tarakan_import");

        $create_table = "
            create table if not exists site.bridging_tarakan_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                siteid varchar(255),
                nosales varchar(255),
                tanggal_sales varchar(255),
                salesmanid varchar(255),
                nama_salesman varchar(255),
                customerid varchar(255),
                nama_customer varchar(255),
                productid varchar(255),
                product_descr varchar(255),
                flag_retur varchar(255),
                flag_bonus varchar(255),
                harga varchar(255),
                qty varchar(255),
                bruto varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra text,
                rp_xtra varchar(255),
                disc_cash varchar(255),
                rp_cash varchar(255),
                netto varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function get_tarakan_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_tarakan_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_tarakan_import($data)
    {
        $this->db->insert('site.bridging_tarakan_import', $data);
        return $this->db->affected_rows();
    }

    public function get_tarakan_import()
    {
        $query = "
            select *
            from site.bridging_tarakan_import a
        ";
        return $this->db->query($query);
    }

    public function get_tarakan_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_tarakan_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_tarakan_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.bruto) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_tarakan_import a
        ";
        return $this->db->query($query);
    }

    public function delete_fi_tarakan($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_ri_tarakan($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_fi_tarakan($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'07' as KDOKJDI,			
            nosales as NODOKJDI,			
            nosales as NODOKACU,			
            tanggal_sales as TGLDOKJDI,			
            salesmanid as KODESALES,			
            '$kode_comp' as KODE_COMP,			
            b.kota_id as KODE_KOTA, 	
            b.type_id AS KODE_TYPE,			
            b.customer_id as KODE_LANG,		
            '' as KODERAYON,			
            c.KODEPROD as KODEPROD,			
            c.supp as SUPP, 			
            RIGHT(tanggal_sales,2) as HRDOK,			
            '$bulan' as BLNDOK,			
            LEFT(tanggal_sales,4) as THNDOK,			
            c.NAMAPROD as NAMAPROD,			
            c.GRUPPROD as GROUPPROD,			
            if(flag_bonus = 1, '0', qty) AS BANYAK,			
            harga AS HARGA,			
            '' as POTONGAN,			
            bruto as TOT1,			
            '' as JUM_PROMO, 			
            '' as KETERANGAN,  			
            '' as USER_ISI,			
            '' as JAM_ISI,			
            '' as TGL_ISI, 			
            '' as USER_EDIT, 			
            '' as JAM_EDIT, 			
            '' as TGL_EDIT,			
            '' as USER_DEL, 			
            '' as JAM_DEL, 			
            '' as TGL_DEL, 			
            '' as NO, 			
            '' as BACKUP, 			
            '' as NO_URUT, 			
            '' as KODE_GDG, 
            '' as NAMA_GDG, 			
            b.class_id AS KODESALUR,			
            '' as KODEBONUS,			
            '' as NAMABONUS, 			
            '' as GRUPBONUS, 			
            if(flag_bonus = 1, qty, '') as UNITBONUS, 			
            nama_salesman as LAMPIRAN, 			
            '' as H_BELI, 			
            '' as KODEAREA, 			
            b.alamat as NAMAAREA, 
            '' as PINJAM, 			
            '' as JUALBANYAK, 			
            '' as JUALPINJAM, 			
            '' as HARGA_EXCL, 			
            '' as TOT1_EXCL, 			
            b.nama_customer as NAMA_LANG,			
            '$nocab' as NOCAB,			
            '$bulan' as BULAN,			
            ' ' as siteid,			
            ' ' as qty1,			
            ' ' as qty2,			
            ' ' as qty3,			
            if(flag_bonus = 1, qty, '') as qty_bonus,			
            flag_bonus as flag_bonus,			
            ' ' as disc_persen,			
            ' ' as disc_rp,			
            ' ' as disc_value,			
            disc_cabang  as disc_cabang,			
            disc_prinsipal as disc_prinsipal,			
            disc_xtra as disc_xtra,			
            rp_cabang as rp_cabang,			
            rp_prinsipal as rp_prinsipal,			
            rp_xtra as rp_xtra,			
            ' ' as bonus,			
            ' ' as prinsipalid,			
            ' ' as ex_no_sales,			
            ' ' as status_retur,			
            ' ' as ref,			
            ' ' as term_payment,			
            ' ' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, ' ' as beban_bonus, ' ' as disc_add_percen, '' as subarea_id			

            from site.bridging_tarakan_import a	left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_tarakan_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 0
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_ri_tarakan($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'07' as KDOKJDI,			
            nosales as NODOKJDI,			
            nosales as NODOKACU,			
            tanggal_sales as TGLDOKJDI,			
            salesmanid as KODESALES,			
            '$kode_comp' as KODE_COMP,			
            '' as KODE_KOTA, 	
            b.type_id AS KODE_TYPE,			
            b.customer_id as KODE_LANG,		
            '' as KODERAYON,			
            c.KODEPROD as KODEPROD,			
            c.supp as SUPP, 			
            RIGHT(tanggal_sales,2) as HRDOK,			
            '$bulan' as BLNDOK,			
            LEFT(tanggal_sales,4) as THNDOK,			
            c.NAMAPROD as NAMAPROD,			
            c.GRUPPROD as GROUPPROD,			
            if(flag_bonus = 1, '0', qty) AS BANYAK,			
            harga AS HARGA,			
            '' as POTONGAN,			
            bruto as TOT1,			
            '' as JUM_PROMO, 			
            '' as KETERANGAN,  			
            '' as USER_ISI,			
            '' as JAM_ISI,			
            '' as TGL_ISI, 			
            '' as USER_EDIT, 			
            '' as JAM_EDIT, 			
            '' as TGL_EDIT,			
            '' as USER_DEL, 			
            '' as JAM_DEL, 			
            '' as TGL_DEL, 			
            '' as NO, 			
            '' as BACKUP, 			
            '' as NO_URUT, 			
            '' as KODE_GDG, 
            '' as NAMA_GDG, 			
            b.class_id AS KODESALUR,			
            '' as KODEBONUS,			
            '' as NAMABONUS, 			
            '' as GRUPBONUS, 			
            if(flag_bonus = 1, qty, '') as UNITBONUS, 			
            nama_salesman as LAMPIRAN, 			
            '' as H_BELI, 			
            '' as KODEAREA, 			
            b.alamat as NAMAAREA, 
            '' as PINJAM, 			
            '' as JUALBANYAK, 			
            '' as JUALPINJAM, 			
            '' as HARGA_EXCL, 			
            b.nama_customer as NAMA_LANG,			
            '$nocab' as NOCAB,			
            '$bulan' as BULAN,			
            ' ' as siteid,			
            ' ' as qty1,			
            ' ' as qty2,			
            ' ' as qty3,			
            if(flag_bonus = 1, qty, '') as qty_bonus,			
            flag_bonus as flag_bonus,			
            ' ' as disc_persen,			
            ' ' as disc_rp,			
            ' ' as disc_value,			
            disc_cabang  as disc_cabang,			
            disc_prinsipal as disc_prinsipal,			
            disc_xtra as disc_xtra,			
            rp_cabang as rp_cabang,			
            rp_prinsipal as rp_prinsipal,			
            rp_xtra as rp_xtra,			
            ' ' as bonus,			
            ' ' as prinsipalid,			
            ' ' as ex_no_sales,			
            ' ' as status_retur,			
            ' ' as ref,			
            ' ' as term_payment,			
            ' ' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, ' ' as beban_bonus, ' ' as disc_add_percen, '' as subarea_id			

            from site.bridging_tarakan_import a	left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_tarakan_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tblang_tarakan($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_tarakan($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_tarakan($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_berau_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_berau_import_customer");

        $create_table = "
            create table if not exists site.bridging_berau_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>"; die;
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_berau_import_customer($column)
    {
        
        $query = "
            ALTER TABLE site.bridging_berau_import_customer
            ADD UNIQUE ($column);
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_berau_import_customer($data)
    {
        $this->db->insert('site.bridging_berau_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_berau_import_customer()
    {
        $query = "
            select *
            from site.bridging_berau_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_berau_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_berau_import_customer a
        ";
        return $this->db->query($query);
    }

    public function create_table_berau_import()
    {
        $this->db->query("drop table if exists site.bridging_berau_import");

        $create_table = "
            create table if not exists site.bridging_berau_import(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                siteid varchar(255),
                nosales varchar(255),
                tanggal_sales varchar(255),
                salesmanid varchar(255),
                nama_salesman varchar(255),
                customerid varchar(255),
                nama_customer varchar(255),
                productid varchar(255),
                product_descr varchar(255),
                flag_retur varchar(255),
                flag_bonus varchar(255),
                harga varchar(255),
                qty varchar(255),
                bruto varchar(255),
                disc_cabang varchar(255),
                rp_cabang varchar(255),
                disc_prinsipal varchar(255),
                rp_prinsipal varchar(255),
                disc_xtra text,
                rp_xtra varchar(255),
                disc_cash varchar(255),
                rp_cash varchar(255),
                netto varchar(255),
                is_valid_kodeprod int(1),
                is_valid_tanggal int(1),
                is_valid_customer int(1),
                id_bridging_log int(11)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function get_berau_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_berau_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_berau_import($data)
    {
        $this->db->insert('site.bridging_berau_import', $data);
        return $this->db->affected_rows();
    }

    public function get_berau_import()
    {
        $query = "
            select *
            from site.bridging_berau_import a
        ";
        return $this->db->query($query);
    }

    public function get_berau_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_berau_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_berau_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.bruto) as sum_bruto, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_berau_import a
        ";
        return $this->db->query($query);
    }

    public function delete_fi_berau($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_ri_berau($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_fi_berau($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.fi
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    day(a.tanggal_sales) as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.flag_bonus = 1, '0', a.qty) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.flag_bonus = 1, '0', a.bruto) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.flag_bonus = 1, a.qty, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    '' as tot1_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.flag_bonus = 1, a.qty, '') as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, disc_cash as disc_cod, rp_cash as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_berau_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_berau_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 0 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function insert_ri_berau($kode_comp, $nocab, $tahun, $bulan)
    {

        $query = "
            insert into data$tahun.ri
            select 	'07' as kddokjdi, 
                    a.nosales as nodokjdi, 
                    a.nosales as nodokacu, 
                    a.tanggal_sales as tgldokjdi,
                    a.salesmanid as kodesales, 
                    '$kode_comp' as kode_comp, 
                    b.kota_id as kode_kota, 
                    b.type_id as kode_type,
                    b.customer_id as kode_lang, 
                    '' as koderayon, 
                    a.productid as kodeprod, 
                    c.supp, 
                    DATE_FORMAT(a.tanggal_sales, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal_sales, '%m') as blndok, 
                    year(a.tanggal_sales) as thndok, 
                    c.namaprod, 
                    c.grupprod as groupprod,
                    if(a.flag_bonus = 1, '0', a.qty) as banyak, 
                    a.harga as harga, 
                    '' as potongan, 
                    if(a.flag_bonus = 1, '0', a.bruto) as tot1, 
                    '' as jum_promo, 
                    '' as keterangan, 
                    '' as user_isi, 
                    '' as jam_isi, 
                    '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, 
                    '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, 
                    b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, 
                    if(a.flag_bonus = 1, a.qty, '') as unitbonus, 
                    a.nama_salesman as lampiran,
                    '' as h_beli, 
                    '' as kodearea, 
                    b.alamat as namaarea,
                    '' as pinjam, 
                    '' as jualbanyak, 
                    '' as jualpinjam, 
                    '' as harga_excl, 
                    b.nama_customer as namalang, 
                    '$nocab' as nocab, 
                    '$bulan' as bulan,
                    '' as siteid, 
                    '' as qty1, 
                    '' as qty2, 
                    '' as qty3, 
                    if(a.flag_bonus = 1, a.qty, '') as qty_bonus, 
                    a.flag_bonus as flag_bonus, 
                    '' as disc_persen,
                    '' as disc_rp, 
                    '' as disc_value, 
                    a.disc_cabang as disc_cabang, 
                    a.disc_prinsipal as disc_prinsipal, 
                    a.disc_xtra as disc_xtra,
                    a.rp_cabang as rp_cabang, 
                    a.rp_prinsipal as rp_prinsipal, 
                    a.rp_xtra as rp_xtra, 
                    '' as bonus, 
                    concat('11', c.supp) as principalid,
                    '' as ex_no_sales, 
                    '' as status_retur, 
                    '' as ref,
                    '' as term_payment, 
                    '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_berau_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_berau_import_customer a
            )b on a.customerid = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.productid = c.kodeprod
            where a.flag_retur = 1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tblang_berau($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function delete_tabsales_berau($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_berau($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_history_import($site_code)
    {
        
        $query = "
            select a.*, b.nama_comp
            from site.stock_history_import a
            left join site.master_site b 
            on a.site_code = b.site_code
            WHERE a.site_code = '$site_code'
            order by a.id desc
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_bridging_hak_akses_userid($userid)
    {
        $query = "
            select *
            from site.bridging_hak_akses a 
            where a.deleted_at is null and a.userid = $userid
        ";  
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function input_log_history($data)
    {
        $this->db->insert('site.stock_history_import', $data);
        return $this->db->insert_id();
    }

    public function insert_stock_import_detail($data)
    {
        $this->db->insert('site.stock_import_detail', $data);
        return $this->db->affected_rows();
    }

    public function get_stock_import_detail($signature)
    {
        $query = "
            select *
            from site.stock_import_detail a
            where a.signature = '$signature'
            order by a.kodeprod asc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_stock_import_where_is_valid_false($signature)
    {
        $query = "
            select 	*
            from site.stock_import_detail a
            where a.is_valid_kodeprod = 0 and a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_stock_import_detail_summary($signature)
    {
        $query = "
            select 	count(*) as count,
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod
            from site.stock_import_detail a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_history_import_by_id($id_log) 
    {
        $query = "
            select a.*, concat(right(a.tahun,2), a.bulan) as tahunbulan
            from site.stock_history_import a
            where a.id = '$id_log'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_site($site_code) 
    {
        $query = "
            select LEFT(a.site_code,3) as kode_comp, RIGHT(a.site_code,2) as nocab
            from site.master_site a
            where a.site_code = '$site_code'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; die;
        return $this->db->query($query);
    }

    public function delete_st($kode_comp, $nocab, $tahunbulan, $tahun) 
    {
        $query = "
            delete from data$tahun.st 
            where nick_site = '$kode_comp' and nocab = '$nocab' and bulan = '$tahunbulan'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        // die;
        return $this->db->query($query);
    }

    public function get_stock_import_detail_by_id_history($id_history)
    {
        $query = "
            select *
            from site.stock_import_detail a
            where a.id_history = '$id_history'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        // die;
        return $this->db->query($query);
    }

    public function get_stock_import_detail_mms_by_id_history($id_history)
    {
        $query = "
            select *
            from site.stock_import_detail_mms a
            where a.id_history = '$id_history'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        // die;
        return $this->db->query($query);
    }

    public function insert_st($tahun, $data) 
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // echo "<br>";
        // echo $tahun;die;
        $this->db->insert('data'.$tahun.'.st', $data);
        return $this->db->affected_rows();
    }

    public function get_history_import_by_site_code($site_code) 
    {
        $query = "
            select *
            from site.stock_history_import a
            where a.site_code = '$site_code' and a.deleted_by is null
            order by concat(a.tahun, a.bulan) desc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_temp_portal_akses()
    {
        $sql = "
            SELECT *
            FROM site.temp_portal_akses a
            ORDER BY a.id DESC
        "; 
        return $this->db->query($sql);
    }

    public function get_result_stock($site_code, $tahun, $bulan)
    {
        $query = "
            select sum(a.stok_akhir) as total_unit
            from data$tahun.st a 
            where concat(a.nick_site, a.nocab) = '$site_code' and a.bulan = $bulan
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function update_history_import($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('site.stock_history_import', $data);
        // echo $this->db->last_query();die;
        // return $this->db->affected_rows();
    }

    public function total_value($signature)
    {
        $query = "
            select supp, round(sum(a.qty_kecil),2) as total_value_kecil, SUM(a.qty_besar) as total_value_besar
            from site.stock_import_detail a 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_master_product_harga_by_kodeprod($kodeprod)
    {
        $query = "
            select a.*
            from site.master_product_with_harga a
            where a.kodeprod = '$kodeprod' 
        ";

        return $this->db->query($query);
    }

    public function get_master_product_harga_by_kodeprod_in($kodeprod_list)
    {
        if (empty($kodeprod_list)) return [];

        $kodeprod_in = implode("','", $kodeprod_list);
        $query = "
            select a.kodeprod, a.kode_prc, a.namaprod, a.supp, a.besar, a.sedang, a.kecil, a.h_dp, a.qty1, a.qty2, a.qty3
            from site.master_product_with_harga a
            where a.kodeprod in ('$kodeprod_in')
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query)->result_array();
    }

    public function insert_stock_import_detail_batch($data)
    {
        if (!empty($data)) {
            $this->db->insert_batch('site.stock_import_detail', $data);
        }
    }


    public function get_supplier()
    {
        $query = "
            select a.supp, a.namasupp
            from site.master_supplier a
            order by a.supp 
        ";
        return $this->db->query($query);
    }

    public function get_total_omzet_stock($signature)
    {
        $query = "
            select sum(a.qty_kecil*harga) as total_value
            from site.stock_import_detail a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_value_group_by_supp($signature)
    {
        $query = "
            select a.supp, round(sum(a.qty_kecil),2) as total_value_kecil, round(sum(a.qty_besar),2) as total_value_besar
            from site.stock_import_detail a
            where a.signature = '$signature'
            group by a.supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function update_spot_tblang($tahun, $site_code)
    {
        if ($site_code == 'SMB2C') {
            $params_site_code = "bridging_bontang_import_customer"; 
        }elseif ($site_code == 'SAM2A') {
            $params_site_code = "bridging_samarinda_import_customer"; 
        }elseif ($site_code == 'KLK1V') {
            $params_site_code = "bridging_kolaka_import_customer"; 
        }elseif ($site_code == 'PUM1N') {
            $params_site_code = "bridging_kendari_import_customer";
        }elseif ($site_code == 'BBU1W') {
            $params_site_code = "bridging_baubau_import_customer"; 
        }elseif ($site_code == 'MMS1O') {
            $params_site_code = "bridging_mms_makasar_import_customer"; 
        }elseif ($site_code == 'BNE1T') {
            $params_site_code = "bridging_mms_bone_import_customer";
        }elseif ($site_code == 'PRE1U') {
            $params_site_code = "bridging_mms_parepare_import_customer"; 
        }elseif ($site_code == 'PKB51') {
            $params_site_code = "bridging_pekanbaru_import_customer"; 
        }elseif ($site_code == 'SUP2G') {
            $params_site_code = "bridging_sup_makasar_import_customer";
        }elseif ($site_code == 'KBT1E') {
            $params_site_code = "bridging_tarakan_import_customer"; 
        }elseif ($site_code == 'KBB1F') {
            $params_site_code = "bridging_berau_import_customer"; 
        }elseif ($site_code == 'AJP2V') {
            $params_site_code = "bridging_palu_import_customer";  
        }elseif ($site_code == 'MMRE1') {
            $params_site_code = "bridging_mmm_makassar_import_customer"; 
        }elseif ($site_code == 'MMBE2') {
            $params_site_code = "bridging_mmm_bone_import_customer"; 
        }elseif ($site_code == 'PAME3') {
            $params_site_code = "bridging_mmm_palopo_import_customer"; 
        }elseif ($site_code == 'MMPE4') {
            $params_site_code = "bridging_mmm_pare_import_customer"; 
        }elseif ($site_code == 'BULE5') {   
             $params_site_code = "bridging_mmm_bulukumba_import_customer";          
        }
        
        $query = "
            update data$tahun.tblang a 
            inner join site.$params_site_code b 
            on a.kode_lang = b.customer_id
            set a.KODE_SPOT = b.spot_id
            WHERE concat(a.kode_comp, a.nocab) = '$site_code'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_stock_import_detail_mms_batch($data)
    {
        if (!empty($data)) {
            $this->db->insert_batch('site.stock_import_detail_mms', $data);
        }
    }

    public function get_stock_import_detail_mms($signature)
    {
        $query = "
            select *
            from site.stock_import_detail_mms a
            where a.signature = '$signature'
            order by a.kodeprod asc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_stock_import_where_is_valid_false_mms($signature)
    {
        $query = "
            select 	*
            from site.stock_import_detail_mms a
            where a.is_valid_kodeprod = 0 and a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function total_value_mms($signature)
    {
        $query = "
            select supp, round(sum(a.total_qty3),2) as total_value_kecil, SUM(a.total_qty1) as total_value_besar
            from site.stock_import_detail_mms a 
            where a.signature = '$signature' and a.is_valid_kodeprod = 1
        ";
        return $this->db->query($query);
    }

    public function get_value_group_by_supp_mms($signature)
    {
        $query = "
            select a.supp, round(sum(a.total_qty3),2) as total_value_kecil, round(sum(a.total_qty1),2) as total_value_besar
            from site.stock_import_detail_mms a
            where a.signature = '$signature'
            group by a.supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_stock_import_detail_summary_mms($signature)
    {
        $query = "
            select 	count(*) as count,
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod
            from site.stock_import_detail_mms a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_total_omzet_stock_mms($signature)
    {
        $query = "
            select sum(a.qty_kecil*harga) as total_value
            from site.stock_import_detail_mms a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function create_table_palu_import()
    {
        $this->db->query("drop table if exists site.bridging_palu_import");

        $create_table = "
            create table if not exists site.bridging_palu_import(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            distributor varchar(255),
            cabang varchar(255),
            tipetrans varchar(255),
            divisi varchar(255),
            principal varchar(255),
            productgroup1 varchar(255),
            productgroup2 varchar(255),
            productgroup3 varchar(255),
            brand varchar(255),
            kodeproduk varchar(255),
            kodevarian varchar(255),
            kodeprodukprincipal varchar(255),
            namaproduk varchar(255),
            packaging varchar(255),
            productclass varchar(255),
            kodecustomer varchar(255),
            namacustomer varchar(255),
            alamatcustomer text,
            area varchar(255),
            subarea varchar(255),
            channel varchar(255),
            subchannel varchar(255),
            customergroup varchar(255),
            keyaccount varchar(255),
            kodesalesman varchar(255),
            namasalesman varchar(255),
            kodesalesco varchar(255),
            namasalesco varchar(255),
            kodespv varchar(255),
            namaspv varchar(255),
            tahunbulan varchar(255),
            bulan varchar(255),
            tanggal varchar(255),
            weekno varchar(255),
            nomornota varchar(255),
            salesmethod varchar(255),
            sellingtype varchar(255),
            qtysold varchar(255),
            kartonutuh varchar(255),
            qtysoldpcs varchar(255),
            freegoodpcs varchar(255),
            tonnage varchar(255),
            volume_ltr varchar(255),
            grossamount varchar(255),
            linediscount1 varchar(255),
            linediscount2 varchar(255),
            linediscount3 varchar(255),
            linediscount4 varchar(255),
            linediscount5 varchar(255),
            totallinediscount varchar(255),
            discountnota1 varchar(255),
            discountnota2 varchar(255),
            discountnota3 varchar(255),
            totaldiscountnota varchar(255),
            dpp varchar(255),
            ppn varchar(255),
            ppnbm varchar(255),
            tax3 varchar(255),
            netamount varchar(255),
            warehouse varchar(255),
            customerpo varchar(255),
            customerjoindate varchar(255),
            nofakturpajak varchar(255),
            tanggalfakturpajak varchar(255),
            nomorfakturproforma varchar(255),
            tanggalfakturproforma varchar(255),
            cogs varchar(255),
            case_weight_kg varchar(255),
            tslqtysoldnfg varchar(255),
            tslconvpcstoctn varchar(255),
            tsltonnagesoldfg varchar(255),
            `end` varchar(255),
            is_valid_kodeprod int(1),
            is_valid_tanggal int(1),
            is_valid_customer int(1),
            id_bridging_log int(11)
            );
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_palu_import($data)
    {
        $this->db->insert('site.bridging_palu_import', $data);
        return $this->db->affected_rows();
    }

    public function get_palu_import()
    {
        $query = "
            select *
            from site.bridging_palu_import a
        ";
        return $this->db->query($query);
    }

    public function get_palu_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.grossamount) as sumgrossamount, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_palu_import a
        ";
        return $this->db->query($query);
    }

    public function get_palu_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_palu_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_palu_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_palu_import_customer");

        $create_table = "
            create table if not exists site.bridging_palu_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_palu_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_palu_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_palu_import_customer($data)
    {
        $this->db->insert('site.bridging_palu_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_palu_import_customer()
    {
        $query = "
            select *
            from site.bridging_palu_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_palu_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_palu_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_palu_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_palu_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_fi_palu($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    (a.qtysoldpcs-freegoodpcs) as banyak, (a.grossamount * 1.11) / a.qtysoldpcs as harga, '' as potongan, 
                    (qtysoldpcs-freegoodpcs) * (a.grossamount / a.qtysoldpcs)*1.11 as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount * 1.11)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT2/(a.GROSSAMOUNT-a.LINEDISCOUNT1))*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT3/(a.GROSSAMOUNT-a.LINEDISCOUNT2))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 * 1.11 as rp_cabang, a.LINEDISCOUNT2 * 1.11 as rp_prinsipal, a.LINEDISCOUNT3 * 1.11 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT1))*100,1) as disc_cod, LINEDISCOUNT5 * 1.11 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_palu_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_palu_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_palu_bonus($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    0 as banyak, (a.grossamount * 1.11) / a.qtysoldpcs as harga, '' as potongan, 
                    '' as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, a.FREEGOODPCS as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount * 1.11)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, a.FREEGOODPCS as qty_bonus, '1' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                    '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_palu_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_palu_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.FREEGOODPCS >=1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_palu($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    (a.qtysoldpcs-freegoodpcs) as banyak, (a.grossamount * 1.11) / a.qtysoldpcs as harga, '' as potongan, 
                    (qtysoldpcs-freegoodpcs) * (a.grossamount / a.qtysoldpcs)*1.11 as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount * 1.11)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam,  '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT2/(a.GROSSAMOUNT-a.LINEDISCOUNT1))*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT3/(a.GROSSAMOUNT-a.LINEDISCOUNT2))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 * 1.11 as rp_cabang, a.LINEDISCOUNT2 * 1.11 as rp_prinsipal, a.LINEDISCOUNT3 * 1.11 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT1))*100,1) as disc_cod, LINEDISCOUNT5 * 1.11 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_palu_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_palu_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans != 'sales'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_fi_palu($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_palu($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_palu($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_palu($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_palu($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    //MAKASSAR//
    public function create_table_mmm_makassar_import()
    {
        $this->db->query("drop table if exists site.bridging_mmm_makassar_import");

        $create_table = "
            create table if not exists site.bridging_mmm_makassar_import(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            distributor varchar(255),
            cabang varchar(255),
            tipetrans varchar(255),
            divisi varchar(255),
            principal varchar(255),
            productgroup1 varchar(255),
            productgroup2 varchar(255),
            productgroup3 varchar(255),
            brand varchar(255),
            kodeproduk varchar(255),
            kodevarian varchar(255),
            kodeprodukprincipal varchar(255),
            namaproduk varchar(255),
            packaging varchar(255),
            productclass varchar(255),
            kodecustomer varchar(255),
            namacustomer varchar(255),
            alamatcustomer text,
            area varchar(255),
            subarea varchar(255),
            channel varchar(255),
            subchannel varchar(255),
            customergroup varchar(255),
            keyaccount varchar(255),
            kodesalesman varchar(255),
            namasalesman varchar(255),
            kodesalesco varchar(255),
            namasalesco varchar(255),
            kodespv varchar(255),
            namaspv varchar(255),
            tahunbulan varchar(255),
            bulan varchar(255),
            tanggal varchar(255),
            weekno varchar(255),
            nomornota varchar(255),
            salesmethod varchar(255),
            sellingtype varchar(255),
            qtysold varchar(255),
            kartonutuh varchar(255),
            qtysoldpcs varchar(255),
            freegoodpcs varchar(255),
            tonnage varchar(255),
            volume_ltr varchar(255),
            grossamount varchar(255),
            linediscount1 varchar(255),
            linediscount2 varchar(255),
            linediscount3 varchar(255),
            linediscount4 varchar(255),
            linediscount5 varchar(255),
            totallinediscount varchar(255),
            discountnota1 varchar(255),
            discountnota2 varchar(255),
            discountnota3 varchar(255),
            totaldiscountnota varchar(255),
            dpp varchar(255),
            ppn varchar(255),
            ppnbm varchar(255),
            tax3 varchar(255),
            netamount varchar(255),
            warehouse varchar(255),
            customerpo varchar(255),
            customerjoindate varchar(255),
            nofakturpajak varchar(255),
            tanggalfakturpajak varchar(255),
            nomorfakturproforma varchar(255),
            tanggalfakturproforma varchar(255),
            cogs varchar(255),
            case_weight_kg varchar(255),
            tslqtysoldnfg varchar(255),
            tslconvpcstoctn varchar(255),
            tsltonnagesoldfg varchar(255),
            `end` varchar(255),
            is_valid_kodeprod int(1),
            is_valid_tanggal int(1),
            is_valid_customer int(1),
            id_bridging_log int(11)
            );
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_mmm_makassar_import($data)
    {
        $this->db->insert('site.bridging_mmm_makassar_import', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_makassar_import()
    {
        $query = "
            select *
            from site.bridging_mmm_makassar_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_makassar_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.grossamount) as sumgrossamount, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_mmm_makassar_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_makassar_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_mmm_makassar_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_mmm_makassar_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_mmm_makassar_import_customer");

        $create_table = "
            create table if not exists site.bridging_mmm_makassar_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_mmm_makassar_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_mmm_makassar_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_mmm_makassar_import_customer($data)
    {
        $this->db->insert('site.bridging_mmm_makassar_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_makassar_import_customer()
    {
        $query = "
            select *
            from site.bridging_mmm_makassar_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_makassar_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_mmm_makassar_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_makassar_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_mmm_makassar_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_makassar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldpcs as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    a.grossamount as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_makassar_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_makassar_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_makassar_bonus($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    0 as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    '' as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, a.FREEGOODPCS as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, a.FREEGOODPCS as qty_bonus, '1' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                    '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mmm_makassar_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_makassar_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.FREEGOODPCS >=1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_mmm_makassar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    (a.qtysoldpcs) as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    (a.grossamount) as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam,  '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_makassar_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_makassar_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans != 'sales'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_fi_mmm_makassar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_mmm_makassar($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_mmm_makassar($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_mmm_makassar($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_mmm_makassar($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    ///BONE///
        //MAKASSAR//
    public function create_table_mmm_bone_import()
    {
        $this->db->query("drop table if exists site.bridging_mmm_bone_import");

        $create_table = "
            create table if not exists site.bridging_mmm_bone_import(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            distributor varchar(255),
            cabang varchar(255),
            tipetrans varchar(255),
            divisi varchar(255),
            principal varchar(255),
            productgroup1 varchar(255),
            productgroup2 varchar(255),
            productgroup3 varchar(255),
            brand varchar(255),
            kodeproduk varchar(255),
            kodevarian varchar(255),
            kodeprodukprincipal varchar(255),
            namaproduk varchar(255),
            packaging varchar(255),
            productclass varchar(255),
            kodecustomer varchar(255),
            namacustomer varchar(255),
            alamatcustomer text,
            area varchar(255),
            subarea varchar(255),
            channel varchar(255),
            subchannel varchar(255),
            customergroup varchar(255),
            keyaccount varchar(255),
            kodesalesman varchar(255),
            namasalesman varchar(255),
            kodesalesco varchar(255),
            namasalesco varchar(255),
            kodespv varchar(255),
            namaspv varchar(255),
            tahunbulan varchar(255),
            bulan varchar(255),
            tanggal varchar(255),
            weekno varchar(255),
            nomornota varchar(255),
            salesmethod varchar(255),
            sellingtype varchar(255),
            qtysold varchar(255),
            kartonutuh varchar(255),
            qtysoldpcs varchar(255),
            freegoodpcs varchar(255),
            tonnage varchar(255),
            volume_ltr varchar(255),
            grossamount varchar(255),
            linediscount1 varchar(255),
            linediscount2 varchar(255),
            linediscount3 varchar(255),
            linediscount4 varchar(255),
            linediscount5 varchar(255),
            totallinediscount varchar(255),
            discountnota1 varchar(255),
            discountnota2 varchar(255),
            discountnota3 varchar(255),
            totaldiscountnota varchar(255),
            dpp varchar(255),
            ppn varchar(255),
            ppnbm varchar(255),
            tax3 varchar(255),
            netamount varchar(255),
            warehouse varchar(255),
            customerpo varchar(255),
            customerjoindate varchar(255),
            nofakturpajak varchar(255),
            tanggalfakturpajak varchar(255),
            nomorfakturproforma varchar(255),
            tanggalfakturproforma varchar(255),
            cogs varchar(255),
            case_weight_kg varchar(255),
            tslqtysoldnfg varchar(255),
            tslconvpcstoctn varchar(255),
            tsltonnagesoldfg varchar(255),
            `end` varchar(255),
            is_valid_kodeprod int(1),
            is_valid_tanggal int(1),
            is_valid_customer int(1),
            id_bridging_log int(11)
            );
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_mmm_bone_import($data)
    {
        $this->db->insert('site.bridging_mmm_bone_import', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_bone_import()
    {
        $query = "
            select *
            from site.bridging_mmm_bone_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_bone_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.grossamount) as sumgrossamount, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_mmm_bone_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_bone_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_mmm_bone_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_mmm_bone_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_mmm_bone_import_customer");

        $create_table = "
            create table if not exists site.bridging_mmm_bone_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_mmm_bone_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_mmm_bone_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_mmm_bone_import_customer($data)
    {
        $this->db->insert('site.bridging_mmm_bone_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_bone_import_customer()
    {
        $query = "
            select *
            from site.bridging_mmm_bone_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_bone_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_mmm_bone_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_bone_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_mmm_bone_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_bone($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldpcs as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    a.grossamount as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_bone_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_bone_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_bone_bonus($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    0 as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    '' as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, a.FREEGOODPCS as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, a.FREEGOODPCS as qty_bonus, '1' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                    '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mmm_bone_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_bone_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.FREEGOODPCS >=1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_mmm_bone($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    (a.qtysoldpcs) as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    (a.grossamount) as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam,  '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_bone_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_bone_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans != 'sales'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_fi_mmm_bone($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_mmm_bone($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_mmm_bone($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_mmm_bone($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_mmm_bone($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

/// PARE-PARE ///
   public function create_table_mmm_pare_import()
    {
        $this->db->query("drop table if exists site.bridging_mmm_pare_import");

        $create_table = "
            create table if not exists site.bridging_mmm_pare_import(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            distributor varchar(255),
            cabang varchar(255),
            tipetrans varchar(255),
            divisi varchar(255),
            principal varchar(255),
            productgroup1 varchar(255),
            productgroup2 varchar(255),
            productgroup3 varchar(255),
            brand varchar(255),
            kodeproduk varchar(255),
            kodevarian varchar(255),
            kodeprodukprincipal varchar(255),
            namaproduk varchar(255),
            packaging varchar(255),
            productclass varchar(255),
            kodecustomer varchar(255),
            namacustomer varchar(255),
            alamatcustomer text,
            area varchar(255),
            subarea varchar(255),
            channel varchar(255),
            subchannel varchar(255),
            customergroup varchar(255),
            keyaccount varchar(255),
            kodesalesman varchar(255),
            namasalesman varchar(255),
            kodesalesco varchar(255),
            namasalesco varchar(255),
            kodespv varchar(255),
            namaspv varchar(255),
            tahunbulan varchar(255),
            bulan varchar(255),
            tanggal varchar(255),
            weekno varchar(255),
            nomornota varchar(255),
            salesmethod varchar(255),
            sellingtype varchar(255),
            qtysold varchar(255),
            kartonutuh varchar(255),
            qtysoldpcs varchar(255),
            freegoodpcs varchar(255),
            tonnage varchar(255),
            volume_ltr varchar(255),
            grossamount varchar(255),
            linediscount1 varchar(255),
            linediscount2 varchar(255),
            linediscount3 varchar(255),
            linediscount4 varchar(255),
            linediscount5 varchar(255),
            totallinediscount varchar(255),
            discountnota1 varchar(255),
            discountnota2 varchar(255),
            discountnota3 varchar(255),
            totaldiscountnota varchar(255),
            dpp varchar(255),
            ppn varchar(255),
            ppnbm varchar(255),
            tax3 varchar(255),
            netamount varchar(255),
            warehouse varchar(255),
            customerpo varchar(255),
            customerjoindate varchar(255),
            nofakturpajak varchar(255),
            tanggalfakturpajak varchar(255),
            nomorfakturproforma varchar(255),
            tanggalfakturproforma varchar(255),
            cogs varchar(255),
            case_weight_kg varchar(255),
            tslqtysoldnfg varchar(255),
            tslconvpcstoctn varchar(255),
            tsltonnagesoldfg varchar(255),
            `end` varchar(255),
            is_valid_kodeprod int(1),
            is_valid_tanggal int(1),
            is_valid_customer int(1),
            id_bridging_log int(11)
            );
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_mmm_pare_import($data)
    {
        $this->db->insert('site.bridging_mmm_pare_import', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_pare_import()
    {
        $query = "
            select *
            from site.bridging_mmm_pare_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_pare_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.grossamount) as sumgrossamount, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_mmm_pare_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_pare_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_mmm_pare_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_mmm_pare_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_mmm_pare_import_customer");

        $create_table = "
            create table if not exists site.bridging_mmm_pare_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_mmm_pare_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_mmm_pare_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_mmm_pare_import_customer($data)
    {
        $this->db->insert('site.bridging_mmm_pare_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_pare_import_customer()
    {
        $query = "
            select *
            from site.bridging_mmm_pare_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_pare_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_mmm_pare_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_pare_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_mmm_pare_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_pare($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldpcs as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    a.grossamount as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_pare_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_pare_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_pare_bonus($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    0 as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    '' as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, a.FREEGOODPCS as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, a.FREEGOODPCS as qty_bonus, '1' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                    '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mmm_pare_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_pare_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.FREEGOODPCS >=1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_mmm_pare($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    (a.qtysoldpcs) as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    (a.grossamount) as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam,  '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_pare_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_pare_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans != 'sales'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_fi_mmm_pare($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_mmm_pare($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_mmm_pare($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_mmm_pare($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_mmm_pare($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }
/// PALOPO ///
   public function create_table_mmm_palopo_import()
    {
        $this->db->query("drop table if exists site.bridging_mmm_palopo_import");

        $create_table = "
            create table if not exists site.bridging_mmm_palopo_import(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            distributor varchar(255),
            cabang varchar(255),
            tipetrans varchar(255),
            divisi varchar(255),
            principal varchar(255),
            productgroup1 varchar(255),
            productgroup2 varchar(255),
            productgroup3 varchar(255),
            brand varchar(255),
            kodeproduk varchar(255),
            kodevarian varchar(255),
            kodeprodukprincipal varchar(255),
            namaproduk varchar(255),
            packaging varchar(255),
            productclass varchar(255),
            kodecustomer varchar(255),
            namacustomer varchar(255),
            alamatcustomer text,
            area varchar(255),
            subarea varchar(255),
            channel varchar(255),
            subchannel varchar(255),
            customergroup varchar(255),
            keyaccount varchar(255),
            kodesalesman varchar(255),
            namasalesman varchar(255),
            kodesalesco varchar(255),
            namasalesco varchar(255),
            kodespv varchar(255),
            namaspv varchar(255),
            tahunbulan varchar(255),
            bulan varchar(255),
            tanggal varchar(255),
            weekno varchar(255),
            nomornota varchar(255),
            salesmethod varchar(255),
            sellingtype varchar(255),
            qtysold varchar(255),
            kartonutuh varchar(255),
            qtysoldpcs varchar(255),
            freegoodpcs varchar(255),
            tonnage varchar(255),
            volume_ltr varchar(255),
            grossamount varchar(255),
            linediscount1 varchar(255),
            linediscount2 varchar(255),
            linediscount3 varchar(255),
            linediscount4 varchar(255),
            linediscount5 varchar(255),
            totallinediscount varchar(255),
            discountnota1 varchar(255),
            discountnota2 varchar(255),
            discountnota3 varchar(255),
            totaldiscountnota varchar(255),
            dpp varchar(255),
            ppn varchar(255),
            ppnbm varchar(255),
            tax3 varchar(255),
            netamount varchar(255),
            warehouse varchar(255),
            customerpo varchar(255),
            customerjoindate varchar(255),
            nofakturpajak varchar(255),
            tanggalfakturpajak varchar(255),
            nomorfakturproforma varchar(255),
            tanggalfakturproforma varchar(255),
            cogs varchar(255),
            case_weight_kg varchar(255),
            tslqtysoldnfg varchar(255),
            tslconvpcstoctn varchar(255),
            tsltonnagesoldfg varchar(255),
            `end` varchar(255),
            is_valid_kodeprod int(1),
            is_valid_tanggal int(1),
            is_valid_customer int(1),
            id_bridging_log int(11)
            );
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_mmm_palopo_import($data)
    {
        $this->db->insert('site.bridging_mmm_palopo_import', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_palopo_import()
    {
        $query = "
            select *
            from site.bridging_mmm_palopo_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_palopo_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.grossamount) as sumgrossamount, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_mmm_palopo_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_palopo_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_mmm_palopo_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_mmm_palopo_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_mmm_palopo_import_customer");

        $create_table = "
            create table if not exists site.bridging_mmm_palopo_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_mmm_palopo_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_mmm_palopo_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_mmm_palopo_import_customer($data)
    {
        $this->db->insert('site.bridging_mmm_palopo_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_palopo_import_customer()
    {
        $query = "
            select *
            from site.bridging_mmm_palopo_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_palopo_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_mmm_palopo_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_palopo_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_mmm_palopo_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_palopo($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldpcs as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    a.grossamount as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_palopo_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_palopo_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_palopo_bonus($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    0 as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    '' as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, a.FREEGOODPCS as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, a.FREEGOODPCS as qty_bonus, '1' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                    '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mmm_palopo_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_palopo_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.FREEGOODPCS >=1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_mmm_palopo($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    (a.qtysoldpcs) as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    (a.grossamount) as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam,  '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_palopo_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_palopo_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans != 'sales'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_fi_mmm_palopo($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_mmm_palopo($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_mmm_palopo($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_mmm_palopo($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_mmm_palopo($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }
/// BULUKUMBA ///
   public function create_table_mmm_bulukumba_import()
    {
        $this->db->query("drop table if exists site.bridging_mmm_bulukumba_import");

        $create_table = "
            create table if not exists site.bridging_mmm_bulukumba_import(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            distributor varchar(255),
            cabang varchar(255),
            tipetrans varchar(255),
            divisi varchar(255),
            principal varchar(255),
            productgroup1 varchar(255),
            productgroup2 varchar(255),
            productgroup3 varchar(255),
            brand varchar(255),
            kodeproduk varchar(255),
            kodevarian varchar(255),
            kodeprodukprincipal varchar(255),
            namaproduk varchar(255),
            packaging varchar(255),
            productclass varchar(255),
            kodecustomer varchar(255),
            namacustomer varchar(255),
            alamatcustomer text,
            area varchar(255),
            subarea varchar(255),
            channel varchar(255),
            subchannel varchar(255),
            customergroup varchar(255),
            keyaccount varchar(255),
            kodesalesman varchar(255),
            namasalesman varchar(255),
            kodesalesco varchar(255),
            namasalesco varchar(255),
            kodespv varchar(255),
            namaspv varchar(255),
            tahunbulan varchar(255),
            bulan varchar(255),
            tanggal varchar(255),
            weekno varchar(255),
            nomornota varchar(255),
            salesmethod varchar(255),
            sellingtype varchar(255),
            qtysold varchar(255),
            kartonutuh varchar(255),
            qtysoldpcs varchar(255),
            freegoodpcs varchar(255),
            tonnage varchar(255),
            volume_ltr varchar(255),
            grossamount varchar(255),
            linediscount1 varchar(255),
            linediscount2 varchar(255),
            linediscount3 varchar(255),
            linediscount4 varchar(255),
            linediscount5 varchar(255),
            totallinediscount varchar(255),
            discountnota1 varchar(255),
            discountnota2 varchar(255),
            discountnota3 varchar(255),
            totaldiscountnota varchar(255),
            dpp varchar(255),
            ppn varchar(255),
            ppnbm varchar(255),
            tax3 varchar(255),
            netamount varchar(255),
            warehouse varchar(255),
            customerpo varchar(255),
            customerjoindate varchar(255),
            nofakturpajak varchar(255),
            tanggalfakturpajak varchar(255),
            nomorfakturproforma varchar(255),
            tanggalfakturproforma varchar(255),
            cogs varchar(255),
            case_weight_kg varchar(255),
            tslqtysoldnfg varchar(255),
            tslconvpcstoctn varchar(255),
            tsltonnagesoldfg varchar(255),
            `end` varchar(255),
            is_valid_kodeprod int(1),
            is_valid_tanggal int(1),
            is_valid_customer int(1),
            id_bridging_log int(11)
            );
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function insert_mmm_bulukumba_import($data)
    {
        $this->db->insert('site.bridging_mmm_bulukumba_import', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_bulukumba_import()
    {
        $query = "
            select *
            from site.bridging_mmm_bulukumba_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_bulukumba_import_summary()
    {
        $query = "
            select 	count(*) as count, sum(a.grossamount) as sumgrossamount, 
                    sum(if(a.is_valid_kodeprod = 0, 1, 0)) as invalid_kodeprod,
                    sum(if(a.is_valid_kodeprod = 1, 1, 0)) as valid_kodeprod,
                    sum(if(a.is_valid_tanggal = 0, 1, 0)) as invalid_tanggal,
                    sum(if(a.is_valid_customer = 0, 1, 0)) as invalid_customer,
                    sum(if(a.is_valid_customer = 1, 1, 0)) as valid_customer,
                    sum(if(a.is_valid_tanggal = 1, 1, 0)) as valid_tanggal
            from site.bridging_mmm_bulukumba_import a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_bulukumba_import_where_is_valid_false()
    {
        $query = "
            select 	*
            from site.bridging_mmm_bulukumba_import a
            where a.is_valid_kodeprod = 0 or a.is_valid_tanggal = 0 or a.is_valid_customer = 0 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_mmm_bulukumba_import_customer()
    {
        $this->db->query("drop table if exists site.bridging_mmm_bulukumba_import_customer");

        $create_table = "
            create table if not exists site.bridging_mmm_bulukumba_import_customer(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                kategori varchar(255),
                nama_site varchar(255),
                regional varchar(255),
                customer_id varchar(255),
                mapping_uli varchar(255),
                mapping_nd6 varchar(255),
                mapping_warung_pintar varchar(255),
                mapping_pbf varchar(255),
                prefix varchar(255),
                nama_customer varchar(255),
                alamat varchar(255),
                tipe_bayar varchar(255),
                top varchar(255),
                status_konsinyasi varchar(255),
                status_fuguh varchar(255),
                kelurahan_id varchar(255),
                nama_kelurahan varchar(255),
                kecamatan_id varchar(255),
                nama_kecamatan varchar(255),
                kota_id varchar(255),
                nama_kota varchar(255),
                propinsi_id varchar(255),
                nama_propinsi varchar(255),
                kode_pos varchar(255),
                telp varchar(255),
                fax varchar(255),
                email varchar(255),
                head_office_id varchar(255),
                nama_head_office varchar(255),
                company_id varchar(255),
                nama_company varchar(255),
                branch_id varchar(255),
                nama_branch_office varchar(255),
                site_id varchar(255),
                segment_id varchar(255),
                nama_segment varchar(255),
                type_id varchar(255),
                nama_type varchar(255),
                class_id varchar(255),
                `class` varchar(255),
                spot_id varchar(255),
                no_ktp varchar(255),
                kartu_keluarga varchar(255),
                pln varchar(255),
                nama_penghubung varchar(255),
                alamat_penghubung varchar(255),
                telp_penghubung varchar(255),
                hubungan varchar(255),
                latitude varchar(255),
                longitude varchar(255),
                member varchar(255),
                black_list varchar(255),
                aktif varchar(255),
                show_alamat_pkp varchar(255),
                data_create varchar(255),
                pbf_izin_no_tdp_tgl varchar(255),
                pbf_izin_no_tdp varchar(255),
                pbf_izin_no_siup_tgl varchar(255),
                pbf_izin_no_siup varchar(255),
                pbf_izin_no_sito_tgl varchar(255),
                pbf_izin_no_sito varchar(255),
                pbf_izin_no_sipa_tgl varchar(255),
                pbf_izin_no_sipa varchar(255),
                pbf_izin_no_sia_tgl varchar(255),
                pbf_izin_no_sia varchar(255),
                pbf_izin_no_nib_tgl varchar(255),
                pbf_izin_no_nib varchar(255),
                pbf_izin_no_cdob_tgl varchar(255),
                pbf_izin_no_cdob varchar(255),
                pbf_asis_apoteker_tgl_sipa varchar(255),
                pbf_asis_apoteker_tgl_lahir varchar(255),
                pbf_asis_apoteker_telpon varchar(255),
                pbf_asis_apoteker_no_sipa varchar(255),
                pbf_asis_apoteker_no_ktp varchar(255),
                pbf_asis_apoteker_email varchar(255),
                pbf_asis_apoteker_nama varchar(255),
                pbf_asis_apoteker_alamat varchar(255),
                pbf_apoteker_tgl_sipa varchar(255),
                pbf_apoteker_tgl_lahir varchar(255),
                pbf_apoteker_telpon varchar(255),
                pbf_apoteker_no_sipa varchar(255),
                pbf_apoteker_no_ktp varchar(255),
                pbf_apoteker_nama varchar(255),
                pbf_apoteker_alamat varchar(255),
                pbf_apoteker_email varchar(255),
                is_valid_type_id int(1),
                is_valid_class_id int(1)
            )
        ";
        // echo "<pre>";
        // print_r($create_table);
        // echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function add_unique_mmm_bulukumba_import_customer($column)
    {
        $query = "
            ALTER TABLE site.bridging_mmm_bulukumba_import_customer
            ADD UNIQUE ($column);
        ";
        $add_unique = $this->db->query($query);
        return $add_unique;
    }

    public function insert_mmm_bulukumba_import_customer($data)
    {
        $this->db->insert('site.bridging_mmm_bulukumba_import_customer', $data);
        return $this->db->affected_rows();
    }

    public function get_mmm_bulukumba_import_customer()
    {
        $query = "
            select *
            from site.bridging_mmm_bulukumba_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_bulukumba_import_customer_summary()
    {
        $query = "
            select 	count(*) as count, 
                    sum(if(a.is_valid_type_id = 0, 1, 0)) as invalid_type_id,
                    sum(if(a.is_valid_class_id = 0, 1, 0)) as invalid_class_id
            from site.bridging_mmm_bulukumba_import_customer a
        ";
        return $this->db->query($query);
    }

    public function get_mmm_bulukumba_customer($customer)
    {
        $query = "
            select a.customer_id, a.mapping_uli, a.nama_customer
            from site.bridging_mmm_bulukumba_import_customer a 
            where a.mapping_uli = '$customer'
        ";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_bulukumba($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    a.qtysoldpcs as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    a.grossamount as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_bulukumba_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_bulukumba_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_fi_mmm_bulukumba_bonus($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.fi
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    0 as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    '' as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, a.FREEGOODPCS as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, a.FREEGOODPCS as qty_bonus, '1' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                    '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11', c.supp) as principalid,
                    '' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, '' as disc_cod, '' as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen, '' as subarea_id
            from site.bridging_mmm_bulukumba_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_bulukumba_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans = 'sales' and a.FREEGOODPCS >=1 and a.is_valid_tanggal = 1 and a.is_valid_kodeprod = 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_ri_mmm_bulukumba($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            insert into data$tahun.ri
            select 	'08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi,
                    a.kodesalesman as kodesales, '$kode_comp' as kode_comp, b.kota_id as kode_kota, b.type_id as kode_type,
                    b.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod, c.supp, 
                    DATE_FORMAT(a.tanggal, '%d') as hrdok,
                    DATE_FORMAT(a.tanggal, '%m') as blndok, year(a.tanggal) as thndok, c.namaprod, c.grupprod as groupprod,
                    (a.qtysoldpcs) as banyak, (a.grossamount) / a.qtysoldpcs as harga, '' as potongan, 
                    (a.grossamount) as tot1, '' as jum_promo, '' as keterangan, '' as user_isi, 
                    '' as jam_isi, '' as tgl_isi,
                    '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, 
                    '' as backup, '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, b.class_id as kodesalur, 
                    '' as kodebonus, '' as namabonus, '' as grupbonus, '0' as unitbonus, 
                    a.namasalesman as lampiran,
                    (a.grossamount)/a.qtysoldpcs as h_beli, '' as kodearea, b.alamat as namaarea,
                    '' as pinjam, '' as jualbanyak, '' as jualpinjam,  '' as tot1_excl, 
                    b.nama_customer as namalang, '$nocab' as nocab, '$bulan' as bulan,
                    '' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen,
                    '' as disc_rp, '' as disc_value, round((a.LINEDISCOUNT1/a.GROSSAMOUNT)*100,1) as disc_cabang, 
                    round((a.LINEDISCOUNT3/a.GROSSAMOUNT)*100,1) as disc_prinsipal, 
                    round((a.LINEDISCOUNT4/(a.GROSSAMOUNT-a.LINEDISCOUNT3))*100,1) as disc_xtra,
                    a.LINEDISCOUNT1 as rp_cabang, a.LINEDISCOUNT3 as rp_prinsipal, a.LINEDISCOUNT4 as rp_xtra,
                    '' as bonus, concat('11', c.supp) as principalid,'' as ex_no_sales, '' as status_retur, '' as ref,
                    '' as term_payment, '' as tipe_kl, round((a.LINEDISCOUNT5/(a.GROSSAMOUNT-a.LINEDISCOUNT3-a.LINEDISCOUNT4))*100,1) as disc_cod, LINEDISCOUNT5 as rp_cod, '' as beban_bonus, 
                    '' as disc_add_percen,  '' as subarea_id
            from site.bridging_mmm_bulukumba_import a left join (
                select a.customer_id, a.mapping_uli, a.nama_customer, a.kota_id, a.type_id, a.class_id, a.alamat
                from site.bridging_mmm_bulukumba_import_customer a
            )b on a.kodecustomer = b.mapping_uli left join (
                select a.kodeprod, a.namaprod, a.supp, a.namasupp, a.h_dp, a.grupprod
                from site.master_product_with_harga a 
            )c on a.kodeprodukprincipal = c.kodeprod
            where a.tipetrans != 'sales'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_fi_mmm_bulukumba($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.fi
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_ri_mmm_bulukumba($kode_comp, $nocab, $tahun, $bulan)
    {
        $query = "
            delete from data$tahun.ri
            where bulan = $bulan and kode_comp = '$kode_comp' and nocab = '$nocab'
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tblang_mmm_bulukumba($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tblang where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tabsales_mmm_bulukumba($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tabsales where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_tbkota_mmm_bulukumba($tahun, $nocab)
    {
        $query = "
            delete from data$tahun.tbkota where nocab='$nocab' ;
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }



}
