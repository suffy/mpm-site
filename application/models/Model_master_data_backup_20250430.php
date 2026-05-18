<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_data extends CI_Model 
{

    public function __construct() 
    {
        $this->load->model('model_outlet_transaksi');
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->userid = 999;
    }
    
    public function get_salesman(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $truncate = "truncate db_master_data.t_salesman";
        $proses_truncate = $this->db->query($truncate);

        if ($proses_truncate) {
            $insert_salesman = "
            insert into db_master_data.t_salesman    
            select a.kodesales,a.kodesales_erp,a.namasales,b.kode,b.branch_name, b.nama_comp, b.sub, b.kode_comp, b.nocab,'$tgl'
            FROM
            (
                select concat(a.KODESALES, a.nocab) as kodesales, a.KODESALES as kodesales_erp, a.NAMASALES as namasales,a.nocab
                from data$tahun_sekarang.tabsales a
                GROUP BY concat(a.KODESALES, a.nocab)
            )a LEFT JOIN 
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub
                FROM
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab, a.sub
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang and `status` =1
                )b on a.kode = b.kode
                ORDER BY nocab
            )b on a.nocab = b.nocab
            ";
            $proses_insert_salesman = $this->db->query($insert_salesman);
        }else{
            return array();
        }

    }

    public function get_customer(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $truncate = "truncate db_master_data.t_customer";
        $proses_truncate = $this->db->query($truncate);
        $truncate_salesman_ob = "truncate db_master_data.t_salesman_ob";
        $proses_truncate_salesman_ob = $this->db->query($truncate_salesman_ob);
        $truncate_salesman = "truncate db_master_data.t_salesman";
        $proses_truncate_salesman = $this->db->query($truncate_salesman);
        $truncate_stock = "truncate db_master_data.t_temp_stock";
        $proses_truncate_stock = $this->db->query($truncate_stock);
        $truncate_credit_limit = "truncate db_master_data.m_customer_creditlimit_prinsipal";
        $proses_truncate_credit_limit = $this->db->query($truncate_credit_limit);

        if ($proses_truncate) {
            $insert_customer = "
            insert into db_master_data.t_customer                
            select 	a.kode_lang,a.nama_lang,a.kodesalur,c.class, a.kode_type, d.nama_type,
                    a.alamat, a.id_provinsi,a.nama_provinsi,
                    a.id_kota,a.nama_kota,a.id_kecamatan,a.nama_kecamatan,a.id_kelurahan,a.nama_kelurahan,a.telp,
                    a.credit_limit, a.status_payment,a.status_blacklist,
                    b.kode,b.branch_name,b.nama_comp,
                    b.sub,b.kode_comp,b.nocab,concat(7,right(substr(a.kode_lang,4,6) * 1234,5)) as code_approval,erp_updated,'$tgl'
            FROM
            (
                select 	concat(a.kode_comp,a.kode_lang) as kode_lang, 
                        a.NAMA_LANG as nama_lang,a.KODESALUR as kodesalur,
                        a.KODE_TYPE as kode_type,a.ALAMAT1 as alamat, 
                        a.id_provinsi, a.nama_provinsi,
                        a.id_kota, a.nama_kota,
                        a.id_kecamatan, a.nama_kecamatan,
                        a.id_kelurahan, a.nama_kelurahan,
                        concat(a.kode_comp,a.nocab) as kode, a.credit_limit,a.tipe_bayar as status_payment, a.telp,
                        a.last_updated as erp_updated, a.status_blacklist
                from    data$tahun_sekarang.tblang a  
                
                where a.kode_comp in ('CLP','DIY','KBM','MGL','PWJ','PWT','TMG','BGR','CKG','CKR','JK1','JK3','JKT','SRG','TGR','BLR','DMK','KDS','PAT','PKL','PML','PWD','SL3','SMG','TGL', 'BD1', 'BDG', 'BJR', 'CJR', 'CRB', 'GRT', 'IDM', 'JBR', 'KNG', 'PUR', 'SBG', 'SKB', 'TSM','BBT','BJN','BYW','GSK','JMB','JTM','LMJ','PRB','SB4','STB','TBN','BLT','CAR','KDR','MDU','MLG','PNG','PSN','TGA','BLT','CAR','KDR','MDU','MLG','PNG','PSN','TGA','LOM','SBL')
                GROUP BY concat(a.kode_comp,a.kode_lang,nocab)

            )a INNER JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub
                FROM
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab, a.sub
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang and `status`= 1
                )b on a.kode = b.kode
                ORDER BY nocab
            )b on a.kode = b.kode LEFT JOIN
            (
                select a.kode,a.jenis,a.group as class
                from mpm.tbl_tabsalur a
            )c on a.kodesalur = c.kode left join
            (
                select a.kode_type, a.nama_type
                from mpm.tbl_bantu_type a
            )d on a.kode_type = d.kode_type
            ";
            $proses_insert_customer = $this->db->query($insert_customer);

            // ('CLP','DIY','KBM','MGL','PWJ','PWT','TMG')

            echo "<pre>";
            print_r($insert_customer);
            echo "</pre>";

            $sql_insert_salesman_ob = "
            insert into db_master_data.t_salesman_ob
            select a.*,'$tgl'
            from data$tahun_sekarang.m_sales_salsman_ob a
            ";
            $proses_sql_insert_salesman_ob = $this->db->query($sql_insert_salesman_ob);

            $insert_salesman = "
            insert into db_master_data.t_salesman    
            select a.kodesales,a.kodesales_erp,a.namasales,b.kode,b.branch_name, b.nama_comp, b.sub, b.kode_comp, b.nocab,'$tgl'
            FROM
            (
                select concat(a.KODESALES, a.nocab) as kodesales, a.KODESALES as kodesales_erp, a.NAMASALES as namasales,a.nocab
                from data$tahun_sekarang.tabsales a
                GROUP BY concat(a.KODESALES, a.nocab)
            )a LEFT JOIN 
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub
                FROM
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab, a.sub
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang and `status` =1
                )b on a.kode = b.kode
                ORDER BY nocab
            )b on a.nocab = b.nocab
            ";
            $proses_insert_salesman = $this->db->query($insert_salesman);
            
            $insert_stock = "
            insert into db_master_data.t_temp_stock
            select nocab, kodeprod, bulan, stock_akhir,'$tgl'
            FROM
            (
                select  nocab,kodeprod,substr(bulan,3) as bulan,
                        sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stock_akhir
                from    data$tahun_sekarang.st a
                group by nocab, kodeprod,bulan
                order by kodeprod
            )a  
            where a.bulan = (
                select max(substr(b.bulan,3)) as bulan
                from data$tahun_sekarang.st b
                where b.nocab = a.nocab
                GROUP BY b.nocab
            )

            ";
            $proses_insert_stock = $this->db->query($insert_stock);

            $insert_credit_limit = "
            insert into db_master_data.m_customer_creditlimit_prinsipal
            select *,'$tgl'
            from data$tahun_sekarang.m_customer_creditlimit_prinsipal a
            where a.kode_comp in ('CLP','DIY','KBM','MGL','PWJ','PWT','TMG','BGR','CKG','CKR','JK1','JK3','JKT','SRG','TGR','BLR','DMK','KDS','PAT','PKL','PML','PWD','SL3','SMG','TGL')

            ";
            $proses_insert_credit_limit = $this->db->query($insert_credit_limit);

        }else{
            return array();
        }

    }

    public function get_monitoring_stock(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');

        $kodeprod = $this->model_per_hari->cari_kodeprod_supp('XXX');
        // $kodeprod = '010074';

        $truncate = "truncate db_master_data.t_temp_monitoring_stock_akhir";
        $proses_truncate = $this->db->query($truncate);
        $truncate_avg = "truncate db_master_data.t_temp_monitoring_stock_avg";
        $proses_truncate_avg = $this->db->query($truncate_avg);
        $truncate_sales_berjalan = "truncate db_master_data.t_temp_monitoring_stock_sales_berjalan";
        $proses_truncate_sales_berjalan = $this->db->query($truncate_sales_berjalan);
        $truncate_upload = "truncate db_master_data.t_temp_monitoring_stock_upload";
        $proses_truncate_upload = $this->db->query($truncate_upload);
        $truncate_git = "truncate db_master_data.t_temp_monitoring_stock_git";
        $proses_truncate_git = $this->db->query($truncate_git);
        $truncate_produk = "truncate db_master_data.t_temp_monitoring_stock_produk";
        $proses_truncate_produk = $this->db->query($truncate_produk);
        $truncate_join = "truncate db_master_data.t_temp_monitoring_stock_join";
        $proses_truncate_join = $this->db->query($truncate_join);
        $truncate_log_user = "truncate db_master_data.t_temp_monitoring_stock_log_user";
        $proses_truncate_log_user = $this->db->query($truncate_log_user);

        if ($proses_truncate) {
            $insert_stock_akhir = "
            insert into db_master_data.t_temp_monitoring_stock_akhir
            select c.kode, a.nocab, a.kodeprod, stock, '$created_date'
            FROM
            (
                select a.nocab, a.kodeprod, a.stock
                FROM
                (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,                            
                            sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stock
                    from    data$tahun_sekarang.st 
                    where 	substr(bulan,3) = $bulan_sekarang and kodeprod in ($kodeprod) and kode_gdg in ('pst',1) 
                    group by nocab, kodeprod,bulan
                    order by kodeprod
                )a 
            )a INNER JOIN
            (
                select a.kode, nocab, a.branch_name, a.nama_comp
                FROM
                (
                    select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.nocab
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and (a.stock <> 2 or a.stock is null)
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER JOIN
                (
                    SELECT concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_sekarang
                )b on a.kode = b.kode
                ORDER BY nocab
            )c on a.nocab = c.nocab
            ORDER BY nocab, kodeprod
            ";
            $proses_insert_stock_akhir = $this->db->query($insert_stock_akhir);


            echo "<pre>";
            print_r($insert_stock_akhir);
            echo "</pre>";

            // $bulan_sekarang = '5';
            $bulan_sebelumnya = $bulan_sekarang - 1;
            $bulan_avg = $bulan_sekarang - 6;
            $tahun_sebelumnya = $tahun_sekarang - 1;

            echo "bulan_sebelumnya : ".$bulan_sebelumnya."<br>";
            echo "bulan_avg : ".$bulan_avg;

            if ($bulan_avg > 0) 
            {            
                for ($i=$bulan_avg; $i < $bulan_sekarang ; $i++) { 
                    $bulan_avg_x[] = $i;
                }            
                $bulan_avg_y = implode(', ', $bulan_avg_x);
                $fi = "
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.fi a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod)
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.ri a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod)
                ";

              
            }elseif($bulan_avg == 0){
                // echo "bulan_sekarang : ".$bulan_sekarang;
                $tahun_avg_x = $tahun_sekarang - 1;
                // echo "tahun_avg_x : ".$tahun_avg_x;

                for ($i=1; $i < $bulan_sekarang ; $i++) { 
                    $bulan_avg_x[] = $i;
                }            
                $bulan_avg_y = implode(', ', $bulan_avg_x);
                // echo "bulan_avg_y : ".$bulan_avg_y;
                
                $fi = "
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_avg_x.fi a
                    where 	bulan in (12) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_avg_x.ri a
                    where 	bulan in (12) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.fi a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.ri a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod) 
                ";
            }else{
                
                $cari_bulan_awal = 12 + $bulan_avg;
                for ($i=$cari_bulan_awal; $i <= 12 ; $i++) { 
                    $bulan_avg_ay[] = $i;
                }      

                $bulan_avg = implode(', ', $bulan_avg_ay);
                echo "bulan_avg : ".$bulan_avg;

                for ($i=1; $i < $bulan_sekarang ; $i++) { 
                    $bulan_avg_x[] = $i;
                }            
                $bulan_avg_y = implode(', ', $bulan_avg_x);
                echo "bulan_avg_y : ".$bulan_avg_y;

                // die;

                $fi = "
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sebelumnya.fi a
                    where 	bulan in ($bulan_avg) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sebelumnya.ri a
                    where 	bulan in ($bulan_avg) and 
                            kodeprod in ($kodeprod) 

                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.fi a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.ri a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod) 
                ";
            }

            $insert_avg = "
                insert into db_master_data.t_temp_monitoring_stock_avg
                SELECT kode, kodeprod, round(sum(banyak)/6) as rata,'$created_date'
                FROM
                (
                    $fi
                )a GROUP BY kode, kodeprod
            ";
            
            echo "<pre>";
            print_r($insert_avg);
            echo "</pre>";
            $proses_insert_avg = $this->db->query($insert_avg);

            // die;

            $insert_sales_berjalan = "
            insert into db_master_data.t_temp_monitoring_stock_sales_berjalan
            select kode, KODEPROD, sum(banyak) as omzet_unit,'$created_date'
            FROM
            (
                select if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, banyak, kodeprod
                from data$tahun_sekarang.fi a
                where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) 
                union all
                select if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, banyak, kodeprod
                from data$tahun_sekarang.ri a
                where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) 
            )a 
            GROUP BY kode, kodeprod
            ";

            echo "<pre>";
            print_r($insert_sales_berjalan);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_sales_berjalan);

            $insert_upload = "   
                insert into db_master_data.t_temp_monitoring_stock_upload         
                select 	substring(filename,3,2) as nocab,date(lastupload) as lastupload, '$created_date'
                from 	mpm.upload
                where 	bulan in ($bulan_sekarang,$bulan_sebelumnya) and tahun = $tahun_sekarang and id in (
                    select max(id)
                    from mpm.upload
                    where tahun = $tahun_sekarang and bulan in ($bulan_sekarang,$bulan_sebelumnya)
                    GROUP BY substring(filename,3,2)
                )and userid not in ('0','289')
            ";

            echo "<pre>";
            print_r($insert_upload);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_upload);

            $insert_git = "
            insert into db_master_data.t_temp_monitoring_stock_git 
            select a.kode_alamat, a.kodeprod, a.git, '$created_date'
            from
            (
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_deltomed a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod
                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_us a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod
                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_pemenuhan_unit) as git
                from 	db_po.t_po_outstanding_intrafood a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_pemenuhan_unit) as git
                from 	db_po.t_po_outstanding_marguna a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_jaya a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_strive a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_hni a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_mdj a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod
            )a
            ";
            echo "<pre>";
            print_r($insert_git);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_git);

            $insert_produk = "
            insert into db_master_data.t_temp_monitoring_stock_produk
            select	b.branch_name, b.nama_comp, a.supp, a.namasupp, a.kodeprod, a.namaprod, a.isisatuan,
                    a.berat, a.berat_gr, a.volume, a.harga, a.`status`, b.kode, '$created_date'
            from
            (
                select a.supp, d.namasupp, a.kodeprod, a.namaprod, a.isisatuan, a.berat, a.berat_gr, a.volume, b.h_dp as harga,e.`status`
                from mpm.tabprod a inner join mpm.prod_detail b 
                    on a.kodeprod = b.kodeprod LEFT JOIN mpm.tabsupp d 
                    on a.supp = d.supp LEFT JOIN 
                    (
                        select a.kodeprod, a.`status`
                        from db_master_data.t_import_ms_setting_product_result a
                    )e on e.kodeprod = a.kodeprod
                where b.tgl = (
                    select max(c.tgl)
                    from mpm.prod_detail c
                    where c.kodeprod = b.kodeprod
                )
                ORDER BY kodeprod
            )a join 
            (
                select a.kode, a.branch_name, a.nama_comp, a.urutan
                from 
                (
                    select if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, a.branch_name, a.nama_comp, a.urutan
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN 
                (
                    select concat(a.kode_comp, a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang
                )b on a.kode = b.kode 	
            )b 
            ORDER BY b.kode, a.kodeprod
            ";

            
            echo "<pre>";
            print_r($insert_produk);
            echo "</pre>";
            $proses_insert_produk = $this->db->query($insert_produk);

            $insert_join = "           
            insert into db_master_data.t_temp_monitoring_stock_join
            select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp, a.kodeprod, a.namaprod, a.isisatuan, a.berat, 
                    a.volume, a.harga, a.status, b.rata, c.stock_akhir_unit, d.sales_berjalan, e.lastupload, f.git,	g.stock_ideal, 
                    '' as stock_ideal_unit, '$created_date'
            from db_master_data.t_temp_monitoring_stock_produk a LEFT JOIN
            (
                select a.kode,a.kodeprod, a.rata
                from db_master_data.t_temp_monitoring_stock_avg a
            )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.stock_akhir_unit
                from db_master_data.t_temp_monitoring_stock_akhir a
            )c on a.kode = c.kode and a.kodeprod = c.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.sales_berjalan
                from db_master_data.t_temp_monitoring_stock_sales_berjalan a
            )d on a.kode = d.kode and a.kodeprod = d.kodeprod LEFT JOIN
            (
                select a.nocab, a.lastupload
                from db_master_data.t_temp_monitoring_stock_upload a
            )e on right(a.kode,2) = e.nocab LEFT JOIN
            (
                select a.kode, a.kodeprod, a.git
                from db_master_data.t_temp_monitoring_stock_git a
            )f on a.kode = f.kode and a.kodeprod = f.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.stock_ideal
                from db_master_data.t_temp_monitoring_stock_ideal a
                where a.`status` = 1
            )g on a.kode = g.kode and a.kodeprod = g.kodeprod

            ";

            echo "<pre>";
            print_r($insert_join);
            echo "</pre>";
            $proses_insert_join = $this->db->query($insert_join);

            // update stock_akhir_unit dan sales_berjalan
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_akhir_unit = 0 where a.stock_akhir_unit is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.rata = 0 where a.rata is null or rata < 0");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.git = 0 where a.git is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.sales_berjalan = 0 where a.sales_berjalan is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_ideal = 21 where a.stock_ideal is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_ideal_unit = round(a.rata / 30 * a.stock_ideal)");
            
            $this->get_monitoring_stock_to_doi($created_date);


        }else{
            return array();
        }

    }

    public function get_monitoring_stock_harian(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');

        $kodeprod = $this->model_per_hari->cari_kodeprod_supp('XXX');
        // $kodeprod = '010074';

        $truncate = "truncate db_master_data.t_temp_monitoring_stock_akhir";
        $proses_truncate = $this->db->query($truncate);
        // $truncate_avg = "truncate db_master_data.t_temp_monitoring_stock_avg";
        // $proses_truncate_avg = $this->db->query($truncate_avg);
        $truncate_sales_berjalan = "truncate db_master_data.t_temp_monitoring_stock_sales_berjalan";
        $proses_truncate_sales_berjalan = $this->db->query($truncate_sales_berjalan);
        $truncate_upload = "truncate db_master_data.t_temp_monitoring_stock_upload";
        $proses_truncate_upload = $this->db->query($truncate_upload);
        $truncate_git = "truncate db_master_data.t_temp_monitoring_stock_git";
        $proses_truncate_git = $this->db->query($truncate_git);
        // $truncate_produk = "truncate db_master_data.t_temp_monitoring_stock_produk";
        // $proses_truncate_produk = $this->db->query($truncate_produk);
        $truncate_join = "truncate db_master_data.t_temp_monitoring_stock_join";
        $proses_truncate_join = $this->db->query($truncate_join);
        // $truncate_log_user = "truncate db_master_data.t_temp_monitoring_stock_log_user";
        // $proses_truncate_log_user = $this->db->query($truncate_log_user);

        // $cari_tanggal_log_user = "
        //     select max(a.created_date) as created_date
        //     from db_master_data.t_temp_monitoring_stock_log_user a
        // ";
        // $proses_cari_tanggal_log_user = $this->db->query($cari_tanggal_log_user)->result();
        // foreach ($proses_cari_tanggal_log_user as $key) {
        //     $created_date_log_user = $key->created_date;
        // }

        // echo "created_date_log_user : ".$created_date_log_user;

        $truncate_log_user = "truncate db_master_data.t_temp_monitoring_stock_log_user";
        $proses_truncate_log_user = $this->db->query($truncate_log_user);

        $insert_log_user = "
            insert into db_master_data.t_temp_monitoring_stock_log_user
            select kode, kodeprod, estimasi_sales, stock_ideal, '$created_date', '', '',''
            from db_master_data.t_temp_monitoring_stock_suggest_po
        ";
        $proses_insert_log_user = $this->db->query($insert_log_user);

        
            $insert_stock_akhir = "
            insert into db_master_data.t_temp_monitoring_stock_akhir
            select c.kode, a.nocab, a.kodeprod, stock, '$created_date'
            FROM
            (
                select a.nocab, a.kodeprod, a.stock
                FROM
                (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,                            
                            sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stock
                    from    data$tahun_sekarang.st 
                    where 	substr(bulan,3) = $bulan_sekarang and kodeprod in ($kodeprod) and kode_gdg in ('pst',1) 
                    group by nocab, kodeprod,bulan
                    order by kodeprod
                )a 
            )a INNER JOIN
            (
                select a.kode, nocab, a.branch_name, a.nama_comp
                FROM
                (
                    select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.nocab
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and (a.stock <> 2 or a.stock is null)
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER JOIN
                (
                    SELECT concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_sekarang
                )b on a.kode = b.kode
                ORDER BY nocab
            )c on a.nocab = c.nocab
            ORDER BY nocab, kodeprod
            ";
            $proses_insert_stock_akhir = $this->db->query($insert_stock_akhir);


            echo "<pre>";
            print_r($insert_stock_akhir);
            echo "</pre>";

            // $bulan_sekarang = '5';
            $bulan_sebelumnya = $bulan_sekarang - 1;
            $bulan_avg = $bulan_sekarang - 6;
            $tahun_sebelumnya = $tahun_sekarang - 1;

          

            $insert_sales_berjalan = "
            insert into db_master_data.t_temp_monitoring_stock_sales_berjalan
            select kode, KODEPROD, sum(banyak) as omzet_unit,'$created_date'
            FROM
            (
                select concat(a.kode_comp, a.nocab) as kode, banyak, kodeprod
                from data$tahun_sekarang.fi a
                where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) 
                union all
                select concat(a.kode_comp, a.nocab) as kode, banyak, kodeprod
                from data$tahun_sekarang.ri a
                where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) 
            )a 
            GROUP BY kode, kodeprod
            ";

            echo "<pre>";
            print_r($insert_sales_berjalan);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_sales_berjalan);

            $insert_upload = "   
                insert into db_master_data.t_temp_monitoring_stock_upload         
                select 	substring(filename,3,2) as nocab,date(lastupload) as lastupload, '$created_date'
                from 	mpm.upload
                where 	bulan in ($bulan_sekarang,$bulan_sebelumnya) and tahun = $tahun_sekarang and id in (
                    select max(id)
                    from mpm.upload
                    where tahun = $tahun_sekarang and bulan in ($bulan_sekarang,$bulan_sebelumnya)
                    GROUP BY substring(filename,3,2)
                )and userid not in ('0','289')
            ";

            echo "<pre>";
            print_r($insert_upload);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_upload);

            $insert_git = "
            insert into db_master_data.t_temp_monitoring_stock_git 
            select a.kode_alamat, a.kodeprod, a.git, '$created_date'
            from
            (
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_deltomed a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod
                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_us a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod
                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_pemenuhan_unit) as git
                from 	db_po.t_po_outstanding_intrafood a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_pemenuhan_unit) as git
                from 	db_po.t_po_outstanding_marguna a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_jaya a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_strive a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_hni a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod
            )a
            ";
            echo "<pre>";
            print_r($insert_git);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_git);


            $insert_join = "           
            insert into db_master_data.t_temp_monitoring_stock_join
            select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp, a.kodeprod, a.namaprod, a.isisatuan, a.berat, 
                    a.volume, a.harga, a.status, b.rata, c.stock_akhir_unit,	d.sales_berjalan, e.lastupload,	f.git,	g.stock_ideal, 
                    '' as stock_ideal_unit, '$created_date'
            from db_master_data.t_temp_monitoring_stock_produk a LEFT JOIN
            (
                select a.kode,a.kodeprod, a.rata
                from db_master_data.t_temp_monitoring_stock_avg a
            )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.stock_akhir_unit
                from db_master_data.t_temp_monitoring_stock_akhir a
            )c on a.kode = c.kode and a.kodeprod = c.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.sales_berjalan
                from db_master_data.t_temp_monitoring_stock_sales_berjalan a
            )d on a.kode = d.kode and a.kodeprod = d.kodeprod LEFT JOIN
            (
                select a.nocab, a.lastupload
                from db_master_data.t_temp_monitoring_stock_upload a
            )e on right(a.kode,2) = e.nocab LEFT JOIN
            (
                select a.kode, a.kodeprod, a.git
                from db_master_data.t_temp_monitoring_stock_git a
            )f on a.kode = f.kode and a.kodeprod = f.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.estimasi_sales, a.stock_ideal
                from db_master_data.t_temp_monitoring_stock_log_user a
                
            )g on a.kode = g.kode and a.kodeprod = g.kodeprod

            ";

            echo "<pre>";
            print_r($insert_join);
            echo "</pre>";
            $proses_insert_join = $this->db->query($insert_join);

            echo "<br><hr>aaaa";
            // die;

            // update stock_akhir_unit dan sales_berjalan
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_akhir_unit = 0 where a.stock_akhir_unit is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.rata = 0 where a.rata is null or rata < 0");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.git = 0 where a.git is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.sales_berjalan = 0 where a.sales_berjalan is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_ideal = 21 where a.stock_ideal is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_ideal_unit = round(a.rata / 30 * a.stock_ideal)");
            
            $this->get_monitoring_stock_to_doi_harian($created_date);


        

    }

    public function get_monitoring_stock_to_doi($created_date){
        die;
        $truncate_doi = "truncate db_master_data.t_temp_monitoring_stock_doi";
        $proses_truncate_doi = $this->db->query($truncate_doi);

        $sql_doi = "
            insert into db_master_data.t_temp_monitoring_stock_doi
            select  a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp,a.kodeprod, a.namaprod, a.isisatuan, a.berat, a.volume, a.harga, a.status, a.lastupload,
                    a.rata, a.stock_akhir_unit, a.git, (a.stock_akhir_unit + a.git) as total_stock, a.sales_berjalan, a.rata as estimasi_sales, (a.rata * a.harga) as estimasi_sales_value, (a.sales_berjalan * a.harga) as actual_sales_value,
                    (a.rata - a.sales_berjalan) as potensi_sales, (a.stock_akhir_unit + a.git) - (a.rata - a.sales_berjalan) as stock_berjalan,
                    round(((a.stock_akhir_unit + a.git) - (a.rata - a.sales_berjalan)) / a.rata * 30) as doi, a.stock_ideal,a.stock_ideal_unit, '$created_date'
            from db_master_data.t_temp_monitoring_stock_join a
            where a.created_date = '$created_date'
            ";
        $proses_sql_doi = $this->db->Query($sql_doi);

        echo "<pre>";
        print_r($sql_doi);
        echo "</pre>";

        $this->get_monitoring_stock_suggest_po($created_date);

    }

    public function get_monitoring_stock_to_doi_harian($created_date){
        $truncate_doi = "truncate db_master_data.t_temp_monitoring_stock_doi";
        $proses_truncate_doi = $this->db->query($truncate_doi);

        $sql_doi = "
            insert into db_master_data.t_temp_monitoring_stock_doi
            select  a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp,a.kodeprod, a.namaprod, a.isisatuan, a.berat, 
                    a.volume, a.harga, a.status, a.lastupload, a.rata, a.stock_akhir_unit, a.git, 
                    (a.stock_akhir_unit + a.git) as total_stock, a.sales_berjalan, b.estimasi_sales, (b.estimasi_sales*a.harga) as estimasi_sales_value, (a.sales_berjalan*a.harga) as actual_sales_value,
                    (b.estimasi_sales - a.sales_berjalan) as potensi_sales, (a.stock_akhir_unit + a.git) - (b.estimasi_sales - a.sales_berjalan) as stock_berjalan,
                    round(((a.stock_akhir_unit + a.git) - (b.estimasi_sales - a.sales_berjalan)) / a.rata * 30) as doi, a.stock_ideal,a.stock_ideal_unit, '$created_date'
            from db_master_data.t_temp_monitoring_stock_join a left join
            (
                select a.kode, a.kodeprod, a.estimasi_sales, a.stock_ideal
                from db_master_data.t_temp_monitoring_stock_log_user a
            )b on a.kode = b.kode and a.kodeprod = b.kodeprod
            where a.created_date = '$created_date'
            ";
        $proses_sql_doi = $this->db->Query($sql_doi);

        echo "<pre>";
        print_r($sql_doi);
        echo "</pre>";

        echo "<hr>bbbb";

        $this->get_monitoring_stock_suggest_po_harian($created_date);

    }

    public function get_monitoring_stock_suggest_po($created_date){
        $truncate_suggest_po = "truncate db_master_data.t_temp_monitoring_stock_suggest_po";
        $proses_truncate_suggest_po = $this->db->query($truncate_suggest_po);
        $sql = "
        insert into db_master_data.t_temp_monitoring_stock_suggest_po
        select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp,a.kodeprod, a.namaprod, a.isisatuan,a.berat,a.volume,
		        a.harga, a.status, a.lastupload, a.rata, a.stock_akhir_unit, a.git, a.total_stock, a.sales_berjalan, a.estimasi_sales, a.estimasi_sales_value, a.actual_sales_value, 
                if(round((1-abs(a.actual_sales_value-a.estimasi_sales_value)/a.estimasi_sales_value)*100,2)<0,0,round((1-abs(a.actual_sales_value-a.estimasi_sales_value)/a.estimasi_sales_value)*100,2)) as akurasi, 
                round((a.actual_sales_value / a.estimasi_sales_value)*100,2) as achievement, 
		        a.potensi_sales, a.stock_berjalan, a.doi, a.stock_ideal, a.stock_ideal_unit, 
		        if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) as pp,
		        a.stock_berjalan + if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) as estimasi_saldo_akhir, 
                round((a.stock_berjalan + if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.rata * 30) as estimasi_doi_akhir,
                round((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) as pp_karton,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.berat) as pp_kg,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.volume) as pp_volume,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.isisatuan * a.harga) as pp_value, '' as signature,'$created_date', '' as last_updated, '' as last_updated_by
        from db_master_data.t_temp_monitoring_stock_doi a
        where a.created_date = '$created_date'
        ";
        $proses = $this->db->query($sql);

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $this->db->query("update db_master_data.t_temp_monitoring_stock_suggest_po a set a.signature = concat(replace(right(a.created_date,8),':',''),'-',md5(concat(a.kode,a.kodeprod)))");

        // $insert_log_user = "
        //     insert into db_master_data.t_temp_monitoring_stock_log_user
        //     select a.kode, a.kodeprod, a.estimasi_sales, a.stock_ideal, a.created_date, a.last_updated_by, a.last_updated, a.last_updated_by
        //     from db_master_data.t_temp_monitoring_stock_suggest_po a
        // ";
        // $proses_insert_log_user = $this->db->query($insert_log_user);
        


    }

    public function get_monitoring_stock_suggest_po_harian($created_date){
        $truncate_suggest_po = "truncate db_master_data.t_temp_monitoring_stock_suggest_po";
        $proses_truncate_suggest_po = $this->db->query($truncate_suggest_po);
        $sql = "
        insert into db_master_data.t_temp_monitoring_stock_suggest_po
        select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp,a.kodeprod, a.namaprod, a.isisatuan,a.berat,a.volume,
		        a.harga,  a.status, a.lastupload, a.rata, a.stock_akhir_unit, a.git, a.total_stock, a.sales_berjalan, a.estimasi_sales, a.estimasi_sales_value, a.actual_sales_value, 
                if(round((1-abs(a.actual_sales_value-a.estimasi_sales_value)/a.estimasi_sales_value)*100,2)<0,0,round((1-abs(a.actual_sales_value-a.estimasi_sales_value)/a.estimasi_sales_value)*100,2)) as akurasi, if(round((1-abs(a.actual_sales_value-a.estimasi_sales_value)/a.estimasi_sales_value)*100,2)<0,0,round((1-abs(a.actual_sales_value-a.estimasi_sales_value)/a.estimasi_sales_value)*100,2)) as achievement, 
		        a.potensi_sales, a.stock_berjalan, a.doi, a.stock_ideal, a.stock_ideal_unit, 
		        if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) as pp,
		        a.stock_berjalan + if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) as estimasi_saldo_akhir, 
                round((a.stock_berjalan + if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.rata * 30) as estimasi_doi_akhir,
                round((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) as pp_karton,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.berat) as pp_kg,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.volume,2) as pp_volume,
                ((round(if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) / a.isisatuan)) * a.isisatuan * a.harga) as pp_value, '' as signature,'$created_date', '' as last_updated, '' as last_updated_by
        from db_master_data.t_temp_monitoring_stock_doi a
        where a.created_date = '$created_date'
        ";
        $proses = $this->db->query($sql);

        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        echo "<hr> : ".$proses;

        if ($proses) {
            
            $this->db->query("update db_master_data.t_temp_monitoring_stock_suggest_po a set a.signature = concat(replace(right(a.created_date,8),':',''),'-',md5(concat(a.kode,a.kodeprod)))");

            echo "<hr>xxxxx";
        }


        $insert_log_user = "
            insert into db_master_data.t_temp_monitoring_stock_log_user
            select a.kode, a.kodeprod, a.estimasi_sales, a.stock_ideal, a.created_date, a.last_updated_by, a.last_updated, a.last_updated_by
            from db_master_data.t_temp_monitoring_stock_suggest_po a
            where a.created_date = '$created_date'
        ";
        $proses_insert_log_user = $this->db->query($insert_log_user);
        


    }

    public function get_customer_intrafood(){
        //query insert
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');

        $truncate = "truncate db_master_data.t_temp_customer_intrafood";
        $proses_truncate = $this->db->query($truncate);
        $truncate = "truncate db_master_data.t_customer_intrafood";
        $proses_truncate = $this->db->query($truncate);

        $sql = "
            insert into db_master_data.t_temp_customer_intrafood
            select concat(a.kode_comp,a.kode_lang) as kode
            from data$tahun_sekarang.fi a
            where bulan = $bulan_sekarang and kodeprod in (
                select kodeprod
                from mpm.tabprod a
                where SUPP = 012
            )
            union ALL
            select concat(a.kode_comp,a.kode_lang) as kode
            from data$tahun_sekarang.ri a
            where bulan = $bulan_sekarang and kodeprod in (
                select kodeprod
                from mpm.tabprod a
                where SUPP = 012
            )
            GROUP BY kode
        ";
        $proses = $this->db->query($sql);
        
        $sql_final = "
        insert into db_master_data.t_customer_intrafood
        select b.*
        from db_master_data.t_temp_customer_intrafood a LEFT JOIN
        (
            select *
            from db_master_data.t_customer a
        )b on a.kode = b.kode_lang
            "; 
        $proses_final = $this->db->query($sql_final);

        //fungsi export csv, simpan ke folder suffy
        redirect('master_data/export_customer_intrafood');
        
    }

    public function insert_traffic($id_menu, $status, $created_at)
    {
        $query = "
            insert into db_temp.t_traffic
            values('', '$id_menu', '$status',297, '$created_at')
        ";
        return $this->db->query($query);
    }

    public function truncate($table_name)
    {
        $this->db->query("truncate $table_name");
    }

    public function delete_table_by_year($table_name, $tahun)
    {
        $query = "
            delete from $table_name where year(tglpo) = $tahun
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function create_table_do_po_outstanding()
    {
        $this->db->query("drop table if exists db_po_new.t_temp_do_po_outstanding");

        $create_table = "
            create table if not exists db_po_new.t_temp_do_po_outstanding(
                nodo varchar(255),
                kodedp varchar(255),
                company varchar(255),
                kodeprod_deltomed varchar(255),
                namaprod varchar(255),
                qty int(11),
                nopo varchar(255),
                tgldo date,
                created_date datetime,
                created_by int(11)
            )
        ";
        echo "<pre>";
        print_r($create_table);
        echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function create_table_do_po_outstanding_us()
    {
        $this->db->query("drop table if exists db_po_new.t_temp_do_po_outstanding_us");

        $create_table = "
            create table if not exists db_po_new.t_temp_do_po_outstanding_us(
                nodo varchar(255),
                tgldo varchar(255),
                kodeprod varchar(255),
                nopo varchar(255),
                qty int(11),                
                created_date datetime,
                created_by int(11)
            )
        ";
        echo "<pre>";
        print_r($create_table);
        echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function create_table_report_po_update()
    {
        $this->db->query("drop table if exists db_po_new.t_temp_report_po_update");

        $create_table = "
            create table if not exists db_po_new.t_temp_report_po_update(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                id_ref bigint(20),
                id_po_detail varchar(255),
                supp varchar(255),
                grup varchar(255),
                nopo varchar(255),
                tglpo date,
                nodo varchar(255),
                tgldo datetime,
                tglpesan datetime,
                created datetime,
                modified datetime,
                created_by int(11),
                modified_by int(11),
                tipe char(1),
                open int(11),
                open_by int(11),
                open_date datetime,
                company varchar(255),
                npwp varchar(255),
                email text,
                alamat text,
                ambil int(11),
                note text,
                note_acc text,
                status int(11),
                status_approval int(11),
                alasan_approval varchar(255),
                po_ref varchar(255),
                `lock` int(11),
                kode_alamat varchar(255),
                kodeprod varchar(255),
                namaprod varchar(255),
                banyak int(11),
                banyak_karton int(11),
                backup int(11),
                harga varchar(255),
                kode_prc varchar(255),
                berat varchar(255),
                volume varchar(255),
                stock_akhir varchar(255),
                rata varchar(255),
                git varchar(255),
                doi varchar(255),
                status_terima varchar(255),
                tanggal_terima date,
                tanggal_terima_created_date datetime,
                userid varchar(255),
                deleted int(11),
                session_user int(11),
                session_date datetime
            )
        ";
        echo "<pre>";
        print_r($create_table);
        echo "</pre>";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function create_index_do_po_outstanding()
    {
        $create_index = "
            create index idx_outstanding_deltomed on db_po_new.t_temp_do_po_outstanding(nopo, kodeprod_deltomed)
        ";
        $create_index = $this->db->query($create_index);
        return $create_index;
    }

    public function create_index_do_po_outstanding_us()
    {
        $create_index = "
            create index idx_outstanding_us on db_po_new.t_temp_do_po_outstanding_us(nopo, kodeprod)
        ";
        $create_index = $this->db->query($create_index);
        return $create_index;
    }

    public function create_index_report_po_update()
    {
        $create_index = "
            create index idx_report_po_update on db_po_new.t_temp_report_po_update(nopo, kode_alamat, kodeprod)
        ";
        $create_index = $this->db->query($create_index);
        return $create_index;
    }

    public function get_po_outstanding_deltomed($created_at, $tahun, $bulan)
    {
        // date_default_timezone_set('Asia/Jakarta'); 
        // $date = date_create(date('Y-m-d H:i:s'));
        // date_add($date, date_interval_create_from_date_string('-7 hours'));
        // $tgl = date_format($date, 'Y-m-d H:i:s'); 
        // // $bln_1 = date('Y-m-d', strtotime($tgl));
        // $tahun_sekarang = date('Y', strtotime($tgl));
        // $bulan_sekarang = date('m', strtotime($tgl));

        // echo "tahun_sekarang : ".$tahun_sekarang."<br>";
        // echo "bulan_sekarang : ".$bulan_sekarang."<br><hr>";

        // $tahun = 2024;
        // $tahun_sekarang = 2024;

        // die;

        // $kunci_traffic = "update db_temp.t_traffic a set a.status = 1, a.created_date = '$created_at' where a.id_menu = 77";
        // $this->db->query($kunci_traffic);

        // $this->db->query("truncate db_po.t_temp_report_po_update");

        // die;

        // deltomed
        // $this->db->query("truncate db_po.t_temp_do_po_outstanding");
        // $this->db->query("delete from db_po.t_po_outstanding_deltomed where year(tglpo) = $tahun");
        
        // us
        // $this->db->query("truncate db_po.t_temp_do_po_outstanding_us");
        // $this->db->query("delete from db_po.t_po_outstanding_us where year(tglpo) = $tahun");
        
        // intrafood
        // $this->db->query("delete from db_po.t_po_outstanding_intrafood where year(tglpo) = $tahun");


        // marguna
        // $this->db->query("delete from db_po.t_po_outstanding_marguna where year(tglpo) = $tahun");
        // $this->db->query("delete from db_po.t_po_outstanding_jaya where year(tglpo) = $tahun");
        // $this->db->query("delete from db_po.t_po_outstanding_strive where year(tglpo) = $tahun");
        // $this->db->query("delete from db_po.t_po_outstanding_hni where year(tglpo) = $tahun");
        // $this->db->query("delete from db_po.t_po_outstanding_mdj where year(tglpo) = $tahun");
        // $this->db->query("delete from db_po.t_po_outstanding_all where year(tglpo) = $tahun");

        die;

        $this->update_do($tgl, $tahun_sekarang);
    }

    public function insert_temp_do_deltomed($created_at, $tahun)
    {
        $query = "
        insert into db_po_new.t_temp_do_po_outstanding
        select 	a.nodo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo, 
                str_to_date(a.tgldo,'%d/%m/%Y') as tgldo,'$created_at',297
        from db_po_new.t_do_deltomed a
        where year(str_to_date(a.tgldo,'%d/%m/%Y')) >= $tahun
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function fixed_do_us()
    {
        $query = "
            update db_po_new.t_do_us a
            set a.kodeprod = replace(a.kodeprod, '.','')
            where kodeprod like '%.'
        ";
        return $this->db->query($query);
    }

    public function insert_temp_do_us($created_at, $tahun)
    {
        $query = "
            insert into db_po_new.t_temp_do_po_outstanding_us
            select 	a.nodo, a.tgldo, a.kodeprod,a.nopo,
                    if(b.satuan_box is null, a.qty_pemenuhan, a.qty_pemenuhan * b.satuan_box) as qty_pemenuhan,
                    '$created_at',297
            FROM
            (
                select 	a.nodo, a.tgldo,
                        a.kodeprod, a.banyak as qty_pemenuhan, a.nopo
                from 	db_po_new.t_do_us a
                where year(a.tgldo) >= $tahun
            )a LEFT JOIN
            (
                select a.kodeprod, a.satuan_box, a.`status`
                from db_produk.t_product_po a
                where a.`status` = 1
            )b on a.kodeprod = b.kodeprod
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_po_nasional($created_at, $tahun)
    {
        $query = "
            INSERT INTO db_po_new.t_temp_report_po_update
            select 	'', a.id, b.id as id_po_detail,a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, a.tglpesan,
                    a.created, a.modified, a.created_by, a.modified_by, a.tipe, a.`open`, a.open_by, a.open_date,
                    a.company, a.npwp, a.email, a.alamat, a.ambil, a.note, a.note_acc, a.`status`, a.status_approval,
                    a.alasan_approval, a.po_ref, a.`lock`, a.kode_alamat, b.kodeprod, b.namaprod, 
                    b.banyak, b.banyak_karton, b.`backup`, b.harga, b.kode_prc, b.berat, b.volume, b.stock_akhir, 
                    b.rata, b.git, b.doi, b.status_terima, b.tanggal_terima, b.tanggal_terima_created_date, a.userid, a.deleted,297,'$created_at'
            from 	mpm.po a INNER JOIN 
            (
                select a.*
                from mpm.po_detail a
                where a.deleted = 0
            )b on a.id = b.id_ref 
            where	 a.deleted = 0 and b.deleted = 0 and
            left(a.nopo,4) <> '/MPM' and 
            a.nopo not like '%batal%' and year(a.tglpo) >= $tahun 
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_po_outstanding_deltomed($created_at, $tahun)
    {
        $query = "
            insert into db_po_new.t_po_outstanding_deltomed
            select 	b.branch_name, b.nama_comp, a.company, a.tglpo,  a.nopo,  a.tipe, a.kodeprod, 
                    c.namaprod, a.banyak, sum(d.qty) as qty_pemenuhan,  a.harga, (a.banyak*a.harga) as value_po,
                    (sum(d.qty) * a.harga) as value_pemenuhan, (a.berat * a.banyak_karton) as berat, a.volume,
                    d.tgldo, d.nodo, (sum(d.qty) / a.banyak) * 100 as fulfilment,
                    datediff(d.tgldo, DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,
                    a.po_ref, a.note, a.tanggal_terima, datediff(a.tanggal_terima,d.tgldo) as leadtime_proses_kirim,
                    (a.banyak - sum(d.qty)) as outstanding_po, a.kode_alamat, 
                    CONCAT('[', 
                        GROUP_CONCAT(
                            CONCAT('{\"nodo\":\"', d.nodo, 
                                '\",\"tgldo\":\"', d.tgldo, 
                                '\",\"kodeprod\":\"', a.kodeprod, 
                                '\",\"kodeprod_deltomed\":\"', d.kodeprod_deltomed, 
                                '\",\"qty\":', d.qty, '}')
                            SEPARATOR ','
                        ), 
                    ']') AS data_surat_jalan,
                    '$created_at', 297
            from 	db_po_new.`t_temp_report_po_update` a left join (
                        select a.site_code, a.branch_name, a.nama_comp
                        from site.master_site a
                    )b on a.kode_alamat = b.site_code left join (
                        select a.kodeprod, a.namaprod, a.kodeprod_deltomed
                        from site.master_product_with_harga a
                    )c on a.kodeprod = c.kodeprod left join (
                        select a.nodo, a.kodedp, a.company, a.kodeprod_deltomed, a.namaprod, a.qty, a.nopo, tgldo
                        from db_po_new.t_temp_do_po_outstanding a
                    )d on a.nopo = d.nopo and c.kodeprod_deltomed = d.kodeprod_deltomed
            where   a.supp ='001' and year(a.tglpo) in ($tahun)
            group by a.nopo, a.kodeprod
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_po_outstanding_us($created_at, $tahun)
    {
        $query = "
            insert into db_po_new.t_po_outstanding_us
            select 	b.branch_name, b.nama_comp, a.company, a.tglpo,  a.nopo,  a.tipe, a.kodeprod, 
                    c.namaprod, a.banyak, sum(d.qty) as qty_pemenuhan,  a.harga, (a.banyak*a.harga) as value_po,
                    (sum(d.qty) * a.harga) as value_pemenuhan, (a.berat * a.banyak_karton) as berat, a.volume,
                    d.tgldo, d.nodo, (sum(d.qty) / a.banyak) * 100 as fulfilment,
                    datediff(d.tgldo, DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,
                    a.po_ref, a.note, a.tanggal_terima, datediff(a.tanggal_terima,d.tgldo) as leadtime_proses_kirim,
                    (a.banyak - sum(d.qty)) as outstanding_po, a.kode_alamat, 
                    CONCAT('[', 
                        GROUP_CONCAT(
                            CONCAT('{\"nodo\":\"', d.nodo, 
                                '\",\"tgldo\":\"', d.tgldo, 
                                '\",\"kodeprod\":\"', a.kodeprod,
                                '\",\"qty\":', d.qty, '}')
                            SEPARATOR ','
                        ), 
                    ']') AS data_surat_jalan,
                    '$created_at', 297
            from 	db_po_new.`t_temp_report_po_update` a left join (
                        select a.site_code, a.branch_name, a.nama_comp
                        from site.master_site a
                    )b on a.kode_alamat = b.site_code left join (
                        select a.kodeprod, a.namaprod
                        from site.master_product_with_harga a
                    )c on a.kodeprod = c.kodeprod left join (
                        select a.nodo, a.tgldo, a.kodeprod, a.qty, a.nopo
                        from db_po_new.t_temp_do_po_outstanding_us a
                    )d on a.nopo = d.nopo and c.kodeprod = d.kodeprod
            where   a.supp ='005' and year(a.tglpo) in ($tahun)
            group by a.nopo, a.kodeprod
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_po_outstanding_all($created_at, $tahun)
    {
        $query = "
            insert into db_po_new.t_po_outstanding_all
            select 	c.namasupp, b.branch_name, b.nama_comp, a.company, a.tglpo,  a.nopo,  a.tipe, a.kodeprod, 
                    c.namaprod, a.banyak, '' as qty_pemenuhan,  a.harga, (a.banyak*a.harga) as value_po,
                    '' as value_pemenuhan, (a.berat * a.banyak_karton) as berat, a.volume,
                    '' as tgldo, '' as nodo, '' as fulfilment,
                    '' as lead_time_proses_do,
                    a.po_ref, a.note, a.tanggal_terima, '' as leadtime_proses_kirim,
                    '' as outstanding_po, a.kode_alamat,'' as data_surat_jalan, '$created_at', 297
            from 	db_po_new.`t_temp_report_po_update` a left join (
                        select a.site_code, a.branch_name, a.nama_comp
                        from site.master_site a
                    )b on a.kode_alamat = b.site_code left join (
                        select a.kodeprod, a.namaprod, a.kodeprod_deltomed, a.namasupp
                        from site.master_product_with_harga a
                    )c on a.kodeprod = c.kodeprod 
            where   a.supp not in ('001','005') and year(a.tglpo) in ($tahun)
            group by a.nopo, a.kodeprod
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function update_do($tgl, $tahun_sekarang){
        
        // $tahun_sekarang = 2023;
        // $tahun_sekarang = 2024;
        echo "ini update do";
        echo "<br>";
        echo "Tahun Sekarang : ". $tahun_sekarang;
        // die;

        $insert_do_deltomed = "
        insert into db_po.t_temp_do_po_outstanding
        select 	a.nodo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo, 
                str_to_date(a.tgldo,'%d/%m/%Y') as tgldo,'$tgl',297
        from db_po.t_do_deltomed a
        where year(str_to_date(a.tgldo,'%d/%m/%Y')) >= $tahun_sekarang
        ";
        $this->db->query($insert_do_deltomed);

        echo "<pre>";
        print_r($insert_do_deltomed);
        echo "</pre>";

        // die;

        // replace kodeprod yg mengandung .
        $sql_replace = "
        update db_po.t_do_us a
        set a.kodeprod = replace(a.kodeprod, '.','')
        where kodeprod like '%.'
        ";
        $proses_sql_replace = $this->db->query($sql_replace);

        echo "<pre>";
        print_r($sql_replace);
        echo "</pre>";

        // die;

        $insert_do_us = "
        insert into db_po.t_temp_do_po_outstanding_us
        select 	a.nodo, a.tgldo, a.kodeprod,a.nopo,
                if(b.satuan_box is null, a.qty_pemenuhan, a.qty_pemenuhan * b.satuan_box) as qty_pemenuhan,
                '$tgl',297
        FROM
        (
            select 	a.nodo, a.tgldo,
                    a.kodeprod, sum(a.banyak) as qty_pemenuhan, a.nopo
            from 	db_po.t_do_us a
            where year(a.tgldo) >= $tahun_sekarang
            GROUP BY a.nopo, a.kodeprod
        )a LEFT JOIN
        (
            select a.kodeprod, a.satuan_box, a.`status`
            from db_produk.t_product_po a
            where a.`status` = 1
        )b on a.kodeprod = b.kodeprod
        ";
        $this->db->query($insert_do_us);

        echo "<pre>";
        print_r($insert_do_us);
        echo "</pre>";

        // die;

        $this->update_po($tgl, $tahun_sekarang);
    }

    public function update_po($tgl, $tahun_sekarang){

        echo "ini update po";
        // die;

        $insert_po_nasional = "
        INSERT INTO db_po.t_temp_report_po_update
        select 	'', a.id, b.id as id_po_detail,a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, a.tglpesan,
                a.created, a.modified, a.created_by, a.modified_by, a.tipe, a.`open`, a.open_by, a.open_date,
                a.company, a.npwp, a.email, a.alamat, a.ambil, a.note, a.note_acc, a.`status`, a.status_approval,
                a.alasan_approval, a.po_ref, a.`lock`, a.kode_alamat, b.kodeprod, b.namaprod, 
                b.banyak, b.banyak_karton, b.`backup`, b.harga, b.kode_prc, b.berat, b.volume, b.stock_akhir, 
                b.rata, b.git, b.doi, b.status_terima, b.tanggal_terima, b.tanggal_terima_created_date, a.userid, a.deleted,297,'$tgl'
        from 	mpm.po a INNER JOIN 
        (
                select a.*
                from mpm.po_detail a
                where a.deleted = 0
        )b on a.id = b.id_ref 
        where	 a.deleted = 0 and b.deleted = 0 and
        left(a.nopo,4) <> '/MPM' and 
        a.nopo not like '%batal%' and year(a.tglpo) >= $tahun_sekarang 
        ";
        $this->db->query($insert_po_nasional);
        echo "<pre>";
        print_r($insert_po_nasional);
        echo "</pre>";

        // die;
        
        $this->mapping_po_outstanding_deltomed($tgl, $tahun_sekarang);
    }

    public function mapping_po_outstanding_deltomed($tgl, $tahun_sekarang){

        echo "ini mapping_po_outstanding_deltomed";
        // die;

        $insert_po_outstanding_deltomed = "
        insert into db_po.t_po_outstanding_deltomed
        select 	a.branch_name, a.nama_comp,a.company, a.tglpo, a.nopo,a.tipe, a.kodeprod, a.namaprod, 
                a.banyak as qty_po, b.qty as qty_pemenuhan, a.harga,(a.banyak*a.harga) as value_po,(b.qty*a.harga) as value_pemenuhan,
                a.berat, a.volume, b.tgldo, b.nodo, (b.qty / a.banyak) * 100 as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,
                a.po_ref, a.tanggal_terima, datediff(a.tanggal_terima,b.tgldo) as leadtime_proses_kirim,
                (a.banyak - b.qty) as outstanding_po,a.kode_alamat,'$tgl', 297
        from
        (
            select 	d.branch_name, d.nama_comp, a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,e.kodeprod_deltomed, 
                    a.po_ref, a.berat, a.status_terima, a.tanggal_terima, a.kode_alamat, a.volume
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, (a.berat * a.banyak_karton) as berat, a.status_terima, a.tanggal_terima, a.kode_alamat, a.volume
                from 	db_po.t_temp_report_po_update a  
                where   a.supp ='001' and year(a.tglpo) in ($tahun_sekarang)
                        
            )a LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_sekarang and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select  a.kodeprod, a.namaprod, a.kodeprod_deltomed
                from    mpm.tabprod a
                where   supp = 001
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN 
        (
            select a.nodo, a.kodedp, a.company, a.kodeprod_deltomed, a.namaprod, a.qty, a.nopo, tgldo
            from db_po.t_temp_do_po_outstanding a
        )b on a.nopo = b.nopo and a.kodeprod_deltomed = b.kodeprod_deltomed 
        order by nama_comp, kodeprod
        ";
        $this->db->query($insert_po_outstanding_deltomed);
        // echo "<pre>";
        // print_r($insert_po_outstanding_deltomed);
        // echo "</pre>";

        // die;

        $this->mapping_po_outstanding_us($tgl, $tahun_sekarang);
    }

    public function mapping_po_outstanding_us($tgl, $tahun_sekarang){

        echo "ini mapping_po_outstanding_us";

        $insert_po_outstanding_us = "
        insert into db_po.t_po_outstanding_us
        select 	a.company, a.branch_name, a.nama_comp, date(a.tglpo) as tglpo,a.nopo,a.tipe, a.kodeprod,  
                a.namaprod, a.banyak as qty_po, a.berat, a.volume,
                b.qty_pemenuhan,b.tgldo, b.nodo, (b.qty_pemenuhan / a.banyak * 100) as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,  
                a.tanggal_terima, datediff(a.tanggal_terima, b.tgldo) as leadtime_proses_kirim, 
                (a.banyak - b.qty_pemenuhan) as outstanding_po, a.kode_alamat,'$tgl',297
        FROM 
        ( 
            select 	d.branch_name, d.nama_comp, 
                    a.nopo, a.tglpo, a.tipe, 
                    a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,a.po_ref,a.tanggal_terima, a.kode_alamat,a.berat,a.volume
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, 
                        a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, a.tanggal_terima, a.kode_alamat,a.berat,a.volume
                from 	db_po.t_temp_report_po_update a
                where    a.supp ='005' and year(a.tglpo) in ($tahun_sekarang)
            )a LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_sekarang and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN
        (
            select 	a.nodo, a.tgldo, a.kodeprod,a.nopo, qty_pemenuhan
            FROM db_po.t_temp_do_po_outstanding_us a	
        )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod
        order by nama_comp, kodeprod
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_us);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_us);

        // die;
        

        $insert_po_outstanding_intrafood = "
        insert into db_po.t_po_outstanding_intrafood
        select 	a.id_ref,b.branch_name, b.nama_comp, a.company, date(a.tglpo) as tglpo,
                a.nopo,a.tipe,a.kodeprod,a.namaprod,a.banyak,
                a.banyak_karton, if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) as pemenuhan_unit, 
                if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) as pemenuhan_karton, c.tanggal_kirim, c.tanggal_tiba, 
                (if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) / a.banyak * 100) as fulfilment_unit, 
                (if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) / a.banyak_karton * 100) as fulfilment_karton,
                datediff(c.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, c.status_closed, a.tanggal_terima,
                datediff(a.tanggal_terima, c.tanggal_kirim) as leadtime_proses_kirim, 
                (a.banyak - if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit)) as outstanding_po_unit,
                (a.banyak_karton - if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton)) as outstanding_po_karton, a.kode_alamat,'$tgl', 297
        from
        (
            select 	a.id, a.id_ref, a.id_po_detail, a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, 
                    a.tglpesan, a.created, a.tipe, a.company, a.alamat, a.kode_alamat, 
                    a.kodeprod, a.namaprod, a.banyak, a.banyak_karton, 
                    a.harga, a.kode_prc, a.berat, a.volume, a.stock_akhir, a.rata, a.git, a.doi, 
                    a.status_terima, a.tanggal_terima, a.userid, a.deleted
            from db_po.t_temp_report_po_update a
            where a.supp = 012
        )a LEFT JOIN
        (
            select	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from	mpm.tbl_tabcomp a
            where 	a.`status` = 1
            group by site_code
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select 	a.id_po, a.no_asn, a.batch_number, a.nodo, a.ed, 
                    a.kodeprod, sum(a.jumlah_unit) as pemenuhan_unit, sum(a.jumlah_karton) as pemenuhan_karton, a.status_pemenuhan,
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, a.tanggal_tiba, a.keterangan, a.status_closed, a.signature,
                    a.created_date, a.created_by, a.last_updated, a.last_updated_by
            from    mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )c on a.id_ref = c.id_po and a.kodeprod = c.kodeprod
        ";

        echo "<pre>";
        print_r($insert_po_outstanding_intrafood);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_intrafood);

        echo "<pre>";
        print_r($insert_po_outstanding_intrafood);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_intrafood);

        $insert_po_outstanding_marguna = "
        insert into db_po.t_po_outstanding_marguna
        select 	a.id_ref,b.branch_name, b.nama_comp, a.company, date(a.tglpo) as tglpo,
                a.nopo,a.tipe,a.kodeprod,a.namaprod,a.banyak,
                a.banyak_karton, if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) as pemenuhan_unit, 
                if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) as pemenuhan_karton, c.tanggal_kirim, c.tanggal_tiba, 
                (if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) / a.banyak * 100) as fulfilment_unit, 
                (if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) / a.banyak_karton * 100) as fulfilment_karton,
                datediff(c.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, c.status_closed, a.tanggal_terima,
                datediff(a.tanggal_terima, c.tanggal_kirim) as leadtime_proses_kirim, 
                (a.banyak - if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit)) as outstanding_po_unit,
                (a.banyak_karton - if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton)) as outstanding_po_karton, a.kode_alamat,'$tgl', 297
        from
        (
            select 	a.id, a.id_ref, a.id_po_detail, a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, 
                    a.tglpesan, a.created, a.tipe, a.company, a.alamat, a.kode_alamat, 
                    a.kodeprod, a.namaprod, a.banyak, a.banyak_karton, 
                    a.harga, a.kode_prc, a.berat, a.volume, a.stock_akhir, a.rata, a.git, a.doi, 
                    a.status_terima, a.tanggal_terima, a.userid, a.deleted
            from db_po.t_temp_report_po_update a
            where a.supp = 002
        )a LEFT JOIN
        (
            select	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from	mpm.tbl_tabcomp a
            where 	a.`status` = 1
            group by site_code
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select 	a.id_po, a.no_asn, a.batch_number, a.nodo, a.ed, 
                    a.kodeprod, sum(a.jumlah_unit) as pemenuhan_unit, sum(a.jumlah_karton) as pemenuhan_karton, a.status_pemenuhan,
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, a.tanggal_tiba, a.keterangan, a.status_closed, a.signature,
                    a.created_date, a.created_by, a.last_updated, a.last_updated_by
            from    mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )c on a.id_ref = c.id_po and a.kodeprod = c.kodeprod
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_marguna);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_marguna);

        $insert_po_outstanding_jaya_agung = "
        insert into db_po.t_po_outstanding_jaya
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun_sekarang and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=004 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun_sekarang)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_jaya_agung);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_jaya_agung);

        $insert_po_outstanding_strive = "
        insert into db_po.t_po_outstanding_strive
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun_sekarang and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=013 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun_sekarang)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_strive);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_strive);

        $insert_po_outstanding_hni = "
        insert into db_po.t_po_outstanding_hni
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun_sekarang and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=014 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun_sekarang)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_hni);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_hni);

        $insert_po_outstanding_mdj = "
        insert into db_po.t_po_outstanding_mdj
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun_sekarang and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=015 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun_sekarang)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_mdj);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_mdj);

        $insert_po_outstanding_all = "
        insert into db_po.t_po_outstanding_all
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297, a.supp
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun_sekarang and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp not in ('001', '005', '012', '002', '004', '013', '014', '015') and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun_sekarang)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_all);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_all);

        $buka_traffic = "update db_temp.t_traffic a set a.status = 0, a.created_date = '$tgl' where a.id_menu = 77";
        $this->db->query($buka_traffic);

    }


    public function proses_schedule_truncate_auto(){
        mysql_query('TRUNCATE db_temp.t_temp_line_sold;');
        mysql_query('TRUNCATE db_temp.t_temp_omzet_dp;');
        mysql_query('TRUNCATE db_temp.t_temp_pengambilan;');
        mysql_query('TRUNCATE db_temp.t_temp_pengambilan_report;');
        mysql_query('TRUNCATE db_temp.t_temp_sales_per_hari;');
        mysql_query('TRUNCATE db_temp.t_temp_soprod;');
        mysql_query('TRUNCATE db_temp.t_verifikasi_harga_temp;');
        mysql_query('TRUNCATE db_temp.t_temp_outlet;');
        mysql_query('TRUNCATE db_temp.t_temp_outlet_transaksi;');
        mysql_query('TRUNCATE db_temp.t_temp_sell_out_nasional;');
        mysql_query('TRUNCATE db_menu.t_menu_temp;');
    }

    public function get_outlet(){

        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 
        // $bln_1 = date('Y-m-d', strtotime($tgl));
        $tahun_sekarang = date('Y', strtotime($tgl));
        
        $this->db->query("truncate db_master_data.master_outlet_$tahun_sekarang");
        
        $insert_outlet = "
        insert into db_master_data.master_outlet_$tahun_sekarang
        select 		a.kode_lang,a.nama_lang,a.kodesalur,c.jenis, c.`group`, 
                    a.kode_type, d.nama_type, d.sektor, d.segment, 
                    a.alamat, a.id_provinsi,a.nama_provinsi,
                    a.id_kota,a.nama_kota,a.id_kecamatan,a.nama_kecamatan,a.id_kelurahan,a.nama_kelurahan,
                    a.KODEPOS, a.TELP, a.tipe_bayar, a.credit_limit, 
                    b.kode,b.branch_name,b.nama_comp,
                    b.sub,b.kode_comp,b.nocab, '$tgl'
        FROM
        (
            select 	concat(a.kode_comp,a.kode_lang) as kode_lang, 
                    a.NAMA_LANG as nama_lang,a.KODESALUR as kodesalur,
                    a.KODE_TYPE as kode_type,a.ALAMAT1 as alamat, 
                    a.id_provinsi, a.nama_provinsi,
                    a.id_kota, a.nama_kota,
                    a.id_kecamatan, a.nama_kecamatan,
                    a.id_kelurahan, a.nama_kelurahan,
                    concat(a.kode_comp,a.nocab) as kode, a.credit_limit,a.status_payment, a.KODEPOS, a.TELP, a.tipe_bayar
            from    data$tahun_sekarang.tblang a
            GROUP BY concat(a.kode_comp,a.kode_lang)
        )a LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub
            FROM
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab, a.sub
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where a.tahun = $tahun_sekarang and `status`= 1
            )b on a.kode = b.kode
            ORDER BY nocab
        )b on a.kode = b.kode LEFT JOIN
        (
            select a.kode, a.jenis, a.`group`
            from mpm.tbl_tabsalur a
        )c on a.kodesalur = c.kode LEFT JOIN
        (
            select a.kode_type, a.nama_type, a.sektor, a.segment
            from mpm.tbl_bantu_type a
        )d on a.kode_type = d.kode_type

        ";

        $this->db->query($insert_outlet);


    }
    
    // insert ke db_temp.t_m_customer

    public function insert_t_m_customer(){
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345!@#$%");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn) {
            echo "<br><br><pre>Koneksi dengan Server SDS berhasil </pre><br />";
            $this->db->query('truncate db_temp.t_m_customer');

                $m_customer = "
                    select customerid, nama_customer, prefix, alamat, kelurahanid, kecamatanid, kotaid, propinsiid,
                            kodepos, telp, fax, email, segmentid, typeid, area_kirim, ktp, kk, pln, nama_dihubungi,
                            alamat_dihubungi, telp_dihubungi, hubungan, siteid, branchid, companyid, headofficeid, 
                            blacklist, prospect, datecreate, member, regionalid, areaid, areakirimid, tipe_tax, classid,
                            member_proses, debitur_id, subareaid, depo_id, group_id, group_descr, top_cust, ppn, materia, 
                            khusus, view_faktur, spot_id, latitude, longitude, Alatitude, Alongitude, mcc, mnc, lac, cid,
                            map_uli_cust, status_outlet, std, denah, id_ktp, tt, flag_multi_ob, status_fjp, customer_maap_delto,
                            customer_maap_us, flag_batch, groupid_discount, customerid_perfeti, Show_alamat_pkp, sales, target,
                            salesVstarget, flag_konsinyasi, SUB_CUSTOMER, KATEGORI_CUSTOMER, inkasoid, nama_inkaso, kolektorid,
                            nama_kolektor, status_ob_bi, fugu_status, limit_kredit
                    from    dbsls.dbo.m_customer  
                    ";
                $query = sqlsrv_query($conn, $m_customer); 
                if ($query) {
                    while ($data = sqlsrv_fetch_array($query)){
                        $data = array(
                            'customerid'    => $data['customerid'],
                            'nama_customer' => $data['nama_customer'],
                            'prefix'    => $data['prefix'],
                            'alamat'    => $data['alamat'],
                            'kelurahanid'   => $data['kelurahanid'],
                            'kecamatanid'   => $data['kecamatanid'],
                            'kotaid'    => $data['kotaid'],
                            'propinsiid'    => $data['propinsiid'],
                            'kodepos'   => $data['kodepos'],
                            'telp'  => $data['telp'],
                            'fax'   => $data['fax'],
                            'email' => $data['email'],
                            'segmentid' => $data['segmentid'],
                            'typeid'    => $data['typeid'],
                            'area_kirim'    => $data['area_kirim'],
                            'ktp'   => $data['ktp'],
                            'kk'    => $data['kk'],
                            'pln'   => $data['pln'],
                            'nama_dihubungi'    => $data['nama_dihubungi'],
                            'alamat_dihubungi'  => $data['alamat_dihubungi'],
                            'telp_dihubungi'    => $data['telp_dihubungi'],
                            'hubungan'  => $data['hubungan'],
                            'siteid'    => $data['siteid'],
                            'branchid'  => $data['branchid'],
                            'companyid' => $data['companyid'],
                            'headofficeid'  => $data['headofficeid'],
                            'blacklist' => $data['blacklist'],
                            'prospect'  => $data['prospect'],
                            'datecreate'    => $data['datecreate']->format('Y-m-d H:i:s'),
                            'member'    => $data['member'],
                            'regionalid'    => $data['regionalid'],
                            'areaid'    => $data['areaid'],
                            'areakirimid'   => $data['areakirimid'],
                            'tipe_tax'  => $data['tipe_tax'],
                            'classid'   => $data['classid'],
                            'member_proses' => $data['member_proses'],
                            'debitur_id'    => $data['debitur_id'],
                            'subareaid' => $data['subareaid'],
                            'depo_id'   => $data['depo_id'],
                            'group_id'  => $data['group_id'],
                            'group_descr'   => $data['group_descr'],
                            'top_cust'  => $data['top_cust'],
                            'ppn'   => $data['ppn'],
                            'materia'   => $data['materia'],
                            'khusus'    => $data['khusus'],
                            'view_faktur'   => $data['view_faktur'],
                            'spot_id'   => $data['spot_id'],
                            'latitude'  => $data['latitude'],
                            'longitude' => $data['longitude'],
                            'Alatitude' => $data['Alatitude'],
                            'Alongitude'    => $data['Alongitude'],
                            'mcc'   => $data['mcc'],
                            'mnc'   => $data['mnc'],
                            'lac'   => $data['lac'],
                            'cid'   => $data['cid'],
                            'map_uli_cust'  => $data['map_uli_cust'],
                            'status_outlet' => $data['status_outlet'],
                            'std'   => $data['std'],
                            'denah' => $data['denah'],
                            'id_ktp'    => $data['id_ktp'],
                            'tt'    => $data['tt'],
                            'flag_multi_ob' => $data['flag_multi_ob'],
                            'status_fjp'    => $data['status_fjp'],
                            'customer_maap_delto'   => $data['customer_maap_delto'],
                            'customer_maap_us'  => $data['customer_maap_us'],
                            'flag_batch'    => $data['flag_batch'],
                            'groupid_discount'  => $data['groupid_discount'],
                            'customerid_perfeti'    => $data['customerid_perfeti'],
                            'Show_alamat_pkp'   => $data['Show_alamat_pkp'],
                            'sales' => $data['sales'],
                            'target'    => $data['target'],
                            'salesVstarget' => $data['salesVstarget'],
                            'flag_konsinyasi'   => $data['flag_konsinyasi'],
                            'SUB_CUSTOMER'  => $data['SUB_CUSTOMER'],
                            'KATEGORI_CUSTOMER' => $data['KATEGORI_CUSTOMER'],
                            'inkasoid'  => $data['inkasoid'],
                            'nama_inkaso'   => $data['nama_inkaso'],
                            'kolektorid'    => $data['kolektorid'],
                            'nama_kolektor' => $data['nama_kolektor'],
                            'status_ob_bi'  => $data['status_ob_bi'],
                            'fugu_status'   => $data['fugu_status'],
                            'limit_kredit'  => $data['limit_kredit'],
                        );
                        
                        $proses = $this->db->insert('db_temp.t_m_customer',$data);
                    }
                    if($proses){
                        $this->insert_t_sales_master();
                }
            }
        }
    }

    // insert ke db_temp.t_sales_master

    public function insert_t_sales_master(){
        $f=strtotime("-6 Months");
        $tahun = date('Y-m-d', $f) ;
        // var_dump($tahun);die;
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345!@#$%");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn) {
            echo "<br><br><pre>Koneksi dengan Server SDS berhasil </pre><br />";
            $this->db->query('truncate db_temp.t_sales_detail');
            $this->db->query('truncate db_temp.t_sales_master');

            $t_sales_master = "
                    SELECT siteid, no_sales, tanggal, flag_dealer, salesmanid, supervisorid, customerid,
                            type_bayar, term_payment, garansi, term_garansi, ref, keterangan, no_polisi, no_rangka,
                            nilai_invoice, userid, tgl_created, status, proses, counter_print, retur, user_app, 
                            CASE
                            WHEN date_app > 1 THEN date_app
                            ELSE 0000-00-00
                            END as date_app, flag_masalah, flag_overdue, flag_overlimit, flag_blacklist, flag_top, flag_stock,
                            program, dp_value, CASE
                            WHEN tgl_periode > 1 THEN tgl_periode
                            ELSE 0000-00-00
                            END as tgl_periode, tipe_sales, tipe_trans, tipe_tax, no_seri_pajak, counter_seri_pajak,
                            ex_no_sales, p_status, proses_sp, tipe_order, status_retur, categoryid, created_date, 
                            CASE
                            WHEN tgl_po > 1 THEN tgl_po
                            ELSE 0000-00-00
                            END as tgl_po,
                            debitur_id, b_no_sales, flag_sp_tt, flag_konsinyasi, tanggal_fjp_bi, areaid, area_desc, kl
                    FROM dbsls.dbo.t_sales_master
                    WHERE created_date >= '$tahun'
                    ";
            $query2 = sqlsrv_query($conn, $t_sales_master); 
            if ($query2) {
                while ($data = sqlsrv_fetch_array($query2)){
                    $data = array(
                        'siteid'  => $data['siteid'],
                        'no_sales'=> $data['no_sales'], 
                        'tanggal' => $data['tanggal']->format('Y-m-d H:i:s'), 
                        'flag_dealer' => $data['flag_dealer'], 
                        'salesmanid'  => $data['salesmanid'], 
                        'supervisorid'=> $data['supervisorid'], 
                        'customerid'  => $data['customerid'],   
                        'type_bayar'  => $data['type_bayar'], 
                        'term_payment'=> $data['term_payment'], 
                        'garansi' => $data['garansi'], 
                        'term_garansi'=> $data['term_garansi'], 
                        'ref'=> $data['ref'], 
                        'keterangan'  => $data['keterangan'], 
                        'no_polisi'   => $data['no_polisi'], 
                        'no_rangka'   => $data['no_rangka'],
                        'nilai_invoice'   => $data['nilai_invoice'], 
                        'userid'  => $data['userid'], 
                        'tgl_created' => $data['tgl_created']->format('Y-m-d H:i:s'), 
                        'status'   => $data['status'],
                        'proses'  => $data['proses'], 
                        'counter_print'   => $data['counter_print'], 
                        'retur'   => $data['retur'], 
                        'user_app'=> $data['user_app'], 
                        'date_app'=> $data['date_app']->format('Y-m-d H:i:s'), 
                        'flag_masalah'=> $data['flag_masalah'], 
                        'flag_overdue'=> $data['flag_overdue'], 
                        'flag_overlimit'  => $data['flag_overlimit'], 
                        'flag_blacklist'  => $data['flag_blacklist'], 
                        'flag_top'=> $data['flag_top'], 
                        'flag_stock'  => $data['flag_stock'],
                        'program' => $data['program'], 
                        'dp_value'=> $data['dp_value'], 
                        'tgl_periode' => $data['tgl_periode']->format('Y-m-d H:i:s'), 
                        'tipe_sales'  => $data['tipe_sales'], 
                        'tipe_trans'  => $data['tipe_trans'], 
                        'tipe_tax'=> $data['tipe_tax'], 
                        'no_seri_pajak'   => $data['no_seri_pajak'], 
                        'counter_seri_pajak'  => $data['counter_seri_pajak'],
                        'ex_no_sales' => $data['ex_no_sales'], 
                        'p_status'=> $data['p_status'], 
                        'proses_sp'   => $data['proses_sp'], 
                        'tipe_order'  => $data['tipe_order'], 
                        'status_retur'=> $data['status_retur'], 
                        'categoryid'  => $data['categoryid'], 
                        'created_date'=> $data['created_date']->format('Y-m-d H:i:s'), 
                        'tgl_po'  => $data['tgl_po']->format('Y-m-d H:i:s'),
                        'debitur_id'  => $data['debitur_id'], 
                        'b_no_sales'  => $data['b_no_sales'], 
                        'flag_sp_tt'  => $data['flag_sp_tt'], 
                        'flag_konsinyasi' => $data['flag_konsinyasi'], 
                        'tanggal_fjp_bi'  => $data['tanggal_fjp_bi'], 
                        'areaid'  => $data['areaid'], 
                        'area_desc'   => $data['area_desc'], 
                        'kl'    => $data['kl']
                    );
                    $proses =$this->db->insert('db_temp.t_sales_master',$data);
                }
                if($proses){
                   $this->insert_t_sales_detail();
                }
            }
        }
    }

     // insert ke db_temp.t_sales_detail

    public function insert_t_sales_detail(){
        $f=strtotime("-6 Months");
        $tahun = date('Y-m-d', $f) ;
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345!@#$%");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn) {
            echo "<br><br><pre>Koneksi dengan Server SDS berhasil </pre><br />";

                $t_sales_detail = "
                SELECT b.siteid, b.no_sales, b.productid, b.nourut, b.type_gudang, b.sat_besar, b.sat_sedang, b.sat_kecil,
                        b.isi_besar, b.isi_sedang, b.isi_kecil, b.qty1, b.qty2, b.qty3, b.qty_kecil, b.sat_bonus, b.qty_bonus,
                        b.flag_bonus, b.flag_garansi, b.disc_persen, b.disc_rp, b.disc_value, b.beli, b.jual, b.nego, b.karoseri,
                        b.ppnbm, b.total_harga, b.flag_delete, b.jasa, b.jasa_id, b.biaya_jasa, b.flag_stock, b.no_mesin,
                        b.no_rangka, b.tahun, b.warna, b.ket_mesin, b.flag_karoseri, b.program, b.disc_cabang, b.disc_prinsipal,
                        b.disc_xtra, b.disc_cod, b.rp_cabang, b.rp_prinsipal, b.rp_xtra, b.rp_cod, b.flag_xtra, b.flag_cod, b.bonus,
                        b.net_prinsipal, b.net_xtra, b.net_cod, b.flag_special_price, b.product_desc, b.harga_beli, b.harga_grosir,
                        b.harga_retail, b.harga_motoris_retail, b.memo_disc_reguler, b.memo_disc_prinsipal, b.memo_disc_extra,
                        b.memo_disc_cod, b.memo_disc_bonus, b.batch_outlet, b.expierd, b.prinsipalid, b.prinsipal, b.nilai_order,
                        b.saldo_piutang, b.nilai_limit, b.piutang_ovedue, b.flag_limit, b.DIRECTION, b.nama_salesman, b.source_data,
                        b.tax_value, b.RP_CBP, b.RP_HNA, b.status_tipe_order, b.areaid, b.area_desc
                FROM
                (
                    SELECT no_sales 
                    FROM dbsls.dbo.t_sales_master
                    WHERE created_date >= '$tahun'
                )a 
                INNER JOIN 
                (
                    SELECT a.siteid, a.no_sales, a.productid, a.nourut, a.type_gudang, a.sat_besar, a.sat_sedang, a.sat_kecil,
                            a.isi_besar, a.isi_sedang, a.isi_kecil, a.qty1, a.qty2, a.qty3, a.qty_kecil, a.sat_bonus, a.qty_bonus,
                            a.flag_bonus, a.flag_garansi, a.disc_persen, a.disc_rp, a.disc_value, a.beli, a.jual, a.nego, a.karoseri,
                            a.ppnbm, a.total_harga, a.flag_delete, a.jasa, a.jasa_id, a.biaya_jasa, a.flag_stock, a.no_mesin,
                            a.no_rangka, a.tahun, a.warna, a.ket_mesin, a.flag_karoseri, a.program, a.disc_cabang, a.disc_prinsipal,
                            a.disc_xtra, a.disc_cod, a.rp_cabang, a.rp_prinsipal, a.rp_xtra, a.rp_cod, a.flag_xtra, a.flag_cod, a.bonus,
                            a.net_prinsipal, a.net_xtra, a.net_cod, a.flag_special_price, a.product_desc, a.harga_beli, a.harga_grosir,
                            a.harga_retail, a.harga_motoris_retail, a.memo_disc_reguler, a.memo_disc_prinsipal, a.memo_disc_extra,
                            a.memo_disc_cod, a.memo_disc_bonus, a.batch_outlet, a.expierd, a.prinsipalid, a.prinsipal, a.nilai_order,
                            a.saldo_piutang, a.nilai_limit, a.piutang_ovedue, a.flag_limit, a.DIRECTION, a.nama_salesman, a.source_data,
                            a.tax_value, a.RP_CBP, a.RP_HNA, a.status_tipe_order, a.areaid, a.area_desc
                    FROM dbsls.dbo.t_sales_detail a
                )b on a.no_sales = b.no_sales
                ";
                $query3 = sqlsrv_query($conn, $t_sales_detail); 
                if ($query3) {
                    while ($data = sqlsrv_fetch_array($query3)){
                        $data = array(
                            'siteid'    => $data['siteid'],
                            'no_sales'  => $data['no_sales'],  
                            'productid' => $data['productid'], 
                            'nourut'    => $data['nourut'],    
                            'type_gudang'   => $data['type_gudang'],   
                            'sat_besar' => $data['sat_besar'], 
                            'sat_sedang'    => $data['sat_sedang'],    
                            'sat_kecil' => $data['sat_kecil'], 
                            'isi_besar' => $data['isi_besar'],
                            'isi_sedang'    => $data['isi_sedang'],    
                            'isi_kecil' => $data['isi_kecil'], 
                            'qty1'  => $data['qty1'],  
                            'qty2'  => $data['qty2'],  
                            'qty3'  => $data['qty3'],  
                            'qty_kecil' => $data['qty_kecil'], 
                            'sat_bonus' => $data['sat_bonus'], 
                            'qty_bonus' => $data['qty_bonus'], 
                            'flag_bonus'    => $data['flag_bonus'],
                            'flag_garansi'  => $data['flag_garansi'],  
                            'disc_persen'   => $data['disc_persen'],   
                            'disc_rp'   => $data['disc_rp'],   
                            'disc_value'    => $data['disc_value'],    
                            'beli'  => $data['beli'],  
                            'jual'  => $data['jual'],  
                            'nego'  => $data['nego'],  
                            'karoseri'  => $data['karoseri'],  
                            'ppnbm' => $data['ppnbm'],
                            'total_harga'   => $data['total_harga'],   
                            'flag_delete'   => $data['flag_delete'],   
                            'jasa'  => $data['jasa'],  
                            'jasa_id'   => $data['jasa_id'],   
                            'biaya_jasa'    => $data['biaya_jasa'],    
                            'flag_stock'    => $data['flag_stock'],    
                            'no_mesin'  => $data['no_mesin'],  
                            'no_rangka' => $data['no_rangka'],  
                            'tahun' => $data['tahun'], 
                            'warna' => $data['warna'], 
                            'ket_mesin' => $data['ket_mesin'], 
                            'flag_karoseri' => $data['flag_karoseri'], 
                            'program'   => $data['program'],   
                            'disc_cabang'   => $data['disc_cabang'],   
                            'disc_prinsipal'    => $data['disc_prinsipal'],    
                            'disc_xtra' => $data['disc_xtra'],
                            'disc_cod'  => $data['disc_cod'],  
                            'rp_cabang' => $data['rp_cabang'], 
                            'rp_prinsipal'  => $data['rp_prinsipal'],  
                            'rp_xtra'   => $data['rp_xtra'],   
                            'rp_cod'    => $data['rp_cod'],    
                            'flag_xtra' => $data['flag_xtra'], 
                            'flag_cod'  => $data['flag_cod'],  
                            'bonus' => $data['bonus'], 
                            'net_prinsipal' => $data['net_prinsipal'],
                            'net_xtra'  => $data['net_xtra'],  
                            'net_cod'   => $data['net_cod'],   
                            'flag_special_price'    => $data['flag_special_price'],    
                            'product_desc'  => $data['product_desc'],  
                            'harga_beli'    => $data['harga_beli'],    
                            'harga_grosir'  => $data['harga_grosir'],  
                            'harga_retail'  => $data['harga_retail'],
                            'harga_motoris_retail'  => $data['harga_motoris_retail'],  
                            'memo_disc_reguler' => $data['memo_disc_reguler'], 
                            'memo_disc_prinsipal'   => $data['memo_disc_prinsipal'],   
                            'memo_disc_extra'   => $data['memo_disc_extra'],   
                            'memo_disc_cod' => $data['memo_disc_cod'],
                            'memo_disc_bonus'   => $data['memo_disc_bonus'],   
                            'batch_outlet'  => $data['batch_outlet'],  
                            'expierd'   => $data['expierd'],   
                            'prinsipalid'   => $data['prinsipalid'],   
                            'prinsipal' => $data['prinsipal'], 
                            'nilai_order'   => $data['nilai_order'],   
                            'saldo_piutang' => $data['saldo_piutang'],
                            'nilai_limit'   => $data['nilai_limit'],   
                            'piutang_ovedue'    => $data['piutang_ovedue'],    
                            'flag_limit'    => $data['flag_limit'],    
                            'DIRECTION' => $data['DIRECTION'], 
                            'nama_salesman' => $data['nama_salesman'], 
                            'source_data'   => $data['source_data'],   
                            'tax_value' => $data['tax_value'],
                            'RP_CBP'    => $data['RP_CBP'],    
                            'RP_HNA'    => $data['RP_HNA'],    
                            'status_tipe_order' => $data['status_tipe_order'], 
                            'areaid'    => $data['areaid'],    
                            'area_desc' => $data['area_desc']
                        );
                        $this->db->insert('db_temp.t_sales_detail',$data);
                }
            }
        }
    }

    // Data Customer Android

    public function master_customer(){
        $sql ="
            SELECT a.id, a.id_user, a.`name`, b.shop_name, b.address, b.provinsi, b.kota, a.phone, a.site_code
            FROM
            (
                SELECT a.id, a.id_user, a.`name`, a.phone, a.site_code
                FROM db_master_data.t_users a
                WHERE a.`server` = 'live'
            )a
            LEFT JOIN 
            (
                SELECT b.id, b.id_user, b.shop_name, b.address, b.provinsi, b.kota
                FROM db_master_data.t_users_address b
                WHERE b.`server` = 'live'
            ) b on a.id_user = b.id_user
            ORDER BY a.id desc
        ";
        $proses= $this->db->query($sql)->result();
        return $proses;
    }

    public function master_customer_by_ID($custID){
        $sql ="
            SELECT a.id, a.id_user, a.`name`, b.shop_name, b.address, b.provinsi, b.kota, b.kecamatan, b.kelurahan, b.kode_pos, a.phone, a.site_code, a.created_at
            FROM
            (
                SELECT a.id, a.id_user, a.`name`, a.phone, a.site_code, a.created_at
                FROM db_master_data.t_users a
                WHERE a.`server` = 'live' and id = '$custID'
            )a
            LEFT JOIN 
            (
                SELECT b.id, b.id_user, b.shop_name, b.address, b.provinsi, b.kota, b.kecamatan, b.kelurahan, b.kode_pos
                FROM db_master_data.t_users_address b
                WHERE b.`server` = 'live'
            ) b on a.id_user = b.id_user
            ORDER BY a.id_user
        ";
        $proses= $this->db->query($sql);
        return $proses;
    }

    public function site_code(){
        $year = date('Y');
        $sql = "
            SELECT a.*
            FROM
            (
                SELECT CONCAT(KODE_COMP,NOCAB) as kode, KODE_COMP, NOCAB, nama_comp FROM mpm.tabcomp a
            )a INNER JOIN
            (
                SELECT CONCAT(KODE_COMP,NOCAB) as kode, KODE_COMP, NOCAB
                FROM db_dp.t_dp
                WHERE tahun = '$year'
            )b on a.kode = b.kode
        ";
        $proses= $this->db->query($sql)->result();
        return $proses;
    }

    public function edit_datacustomer()
    {
        
        $id = $this->input->post('id');
        $kirim = $this->input->post('kirim');

        $post['name']                           =$this->input->post('name');
        $post['phone']                          =$this->input->post('phone');
        $post['site_code']                      =$this->input->post('site_code');
        $post['status_update_erp']             = '1';
        $post['last_updated']                   = date('Y-m-d H:i:s');
        $post['last_updated_erp']               = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('db_master_data.t_users', $post);

        if($kirim == 'ya'){
            redirect("master_data/email_datacustomer/$id");
        }else{
            redirect("master_data/data_customer/$id");
        }

    }
    // ====================================================================================================

    // dashboard sales
    public function dashboard_sales($created_date)
    {
        $tgl = date('Y-m-d');
        $thn_sekarang = date('Y');
        $thn_lalu = date('Y', strtotime('-1 year', strtotime($tgl)));;
        // var_dump($thn_lalu);die;

        $this->db->query('truncate db_temp.t_temp_dashboard_sales');

        $sql = "
        INSERT INTO db_temp.t_temp_dashboard_sales
        SELECT a.*, sum(tot1_1) as tot1_1 ,sum(tot1_2) as tot1_2 ,sum(tot1_3) as tot1_3 ,sum(tot1_4) as tot1_4 ,sum(tot1_5) as tot1_5 ,
                sum(tot1_6) as tot1_6 ,sum(tot1_7) as tot1_7 ,sum(tot1_8) as tot1_8 ,sum(tot1_9) as tot1_9 ,sum(tot1_10) as tot1_10 ,sum(tot1_11) as tot1_11 ,
                sum(tot1_12) as tot1_12 ,sum(tot2_1) as tot2_1 ,sum(tot2_2) as tot2_2 ,sum(tot2_3) as tot2_3 ,sum(tot2_4) as tot2_4 ,sum(tot2_5) as tot2_5 ,
                sum(tot2_6) as tot2_6 ,sum(tot2_7) as tot2_7 ,sum(tot2_8) as tot2_8 ,sum(tot2_9) as tot2_9 ,sum(tot2_10) as tot2_10 ,sum(tot2_11) as tot2_11 ,
                sum(tot2_12) as tot2_12, '$created_date'
        FROM
        (
            SELECT a.kode, a.nocab, a.nama_comp, b.SUPP, b.NAMASUPP
            FROM
            (	
                SELECT CONCAT(kode_comp,nocab) as kode, nocab, nama_comp, active FROM mpm.tbl_tabcomp
                WHERE `status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                SELECT SUPP, NAMASUPP from mpm.tabsupp
                where active = 1 and supp not in ('BSP','xxx','000')
            )b
        )a 
        LEFT JOIN
        (
            SELECT kode, supp, 
                    sum(if(bulan = '01', total, 0)) as tot1_1,
                    sum(if(bulan = '02', total, 0)) as tot1_2,
                    sum(if(bulan = '03', total, 0)) as tot1_3,	
                    sum(if(bulan = '04', total, 0)) as tot1_4,
                    sum(if(bulan = '05', total, 0)) as tot1_5,
                    sum(if(bulan = '06', total, 0)) as tot1_6,
                    sum(if(bulan = '07', total, 0)) as tot1_7,
                    sum(if(bulan = '08', total, 0)) as tot1_8,		
                    sum(if(bulan = '09', total, 0)) as tot1_9,
                    sum(if(bulan = '10', total, 0)) as tot1_10,
                    sum(if(bulan = '11', total, 0)) as tot1_11,
                    sum(if(bulan = '12', total, 0)) as tot1_12
            FROM(
                    select CONCAT(KODE_COMP,NOCAB) as kode, supp, sum(a.tot1) as total, bulan
                    from	data$thn_lalu.fi a
                    GROUP BY kode, supp, bulan
                    union ALL
                    select CONCAT(KODE_COMP,NOCAB) as kode, supp, sum(a.tot1) as total, bulan
                    from	data$thn_lalu.ri a
                    GROUP BY kode, supp, bulan
                )a GROUP BY kode, supp
        )b on a.kode = b.kode  and b.supp = a.supp
        LEFT JOIN
        (
            SELECT kode, supp, 
                    sum(if(bulan = '01', total, 0)) as tot2_1,
                    sum(if(bulan = '02', total, 0)) as tot2_2,
                    sum(if(bulan = '03', total, 0)) as tot2_3,	
                    sum(if(bulan = '04', total, 0)) as tot2_4,
                    sum(if(bulan = '05', total, 0)) as tot2_5,
                    sum(if(bulan = '06', total, 0)) as tot2_6,
                    sum(if(bulan = '07', total, 0)) as tot2_7,
                    sum(if(bulan = '08', total, 0)) as tot2_8,		
                    sum(if(bulan = '09', total, 0)) as tot2_9,
                    sum(if(bulan = '10', total, 0)) as tot2_10,
                    sum(if(bulan = '11', total, 0)) as tot2_11,
                    sum(if(bulan = '12', total, 0)) as tot2_12
            FROM(
                    select CONCAT(KODE_COMP,NOCAB) as kode, supp, sum(a.tot1) as total, bulan
                    from	data$thn_sekarang.fi a
                    GROUP BY kode, supp, bulan
                    union ALL
                    select CONCAT(KODE_COMP,NOCAB) as kode, supp, sum(a.tot1) as total, bulan
                    from	data$thn_sekarang.ri a
                    GROUP BY kode, supp, bulan
                )a GROUP BY kode, supp
        )c on a.kode = c.kode  and c.supp = a.supp
        GROUP BY kode, supp
        ";

        $proses= $this->db->query($sql);
    }

    public function dashboard_stock($data)
    {
        
        $created_date = date("Y-m-d",strtotime($data['created_date']));
        $tahun = substr($created_date,0,4);
        $bulan = substr($created_date,5,2);

        // echo "<pre>";
        // echo "tahun : ".$tahun."<br>";
        // echo "bulan : ".$bulan."<br>";
        // echo "</pre>";
        // die;
        
        // $bulan = 3;

        $this->db->query('truncate db_temp.t_temp_dashboard_stock');

        $bulan_sebelumnya = $bulan - 1;
        $bulan_avg = $bulan - 6;
        if ($bulan_avg >= 0) {            
            for ($i=$bulan_avg + 1; $i <= $bulan ; $i++) { 
                $bulan_avg_x[] = $i;
            }            
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            $fi = "
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun.fi a
                where 	bulan in ($bulan_avg_y)
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun.ri a
                where 	bulan in ($bulan_avg_y) and 
            ";
        }else{
            $tahun_avg_x = $tahun - 1;
            $bulan_avg_a = $bulan - 6;
            $bulan_avg_ax = 12 + $bulan_avg_a;
            for ($i=$bulan_avg_ax + 1; $i <= 12 ; $i++) { 
                $bulan_avg_ay[] = $i;
            }            
            $bulan_avg_a = implode(', ', $bulan_avg_ay);

            for ($i=1; $i <= $bulan ; $i++) { 
                $bulan_avg_x[] = $i;
            }            
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            
            $fi = "
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun_avg_x.fi a
                where 	bulan in ($bulan_avg_a)
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun_avg_x.ri a
                where 	bulan in ($bulan_avg_a)
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun.fi a
                where 	bulan in ($bulan_avg_y)
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun.ri a
                where 	bulan in ($bulan_avg_y)
            ";
        }

        $sql = "
            insert into db_temp.t_temp_dashboard_stock
            SELECT a.kode, a.branch_name, a.nama_comp, a.supp, avg(a.doi_unit) as doi_unit
            FROM(
                select 	a.kode, b.branch_name, b.nama_comp, a.supp, (c.stok / avg_unit*30) as doi_unit, b.urutan
                from
                (
                    select 	kode, right(kode,2) as nocab, KODEPROD, sum(banyak/6) as avg_unit, supp
                    FROM
                    (  
                        $fi           
                    )a GROUP BY kode, kodeprod
                )a inner JOIN 
                (
                    select a.kode, a.branch_name, a.nama_comp, a.urutan
                    from 
                    (
                        select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                        from mpm.tbl_tabcomp a
                        where a.status = 1
                        GROUP BY concat(a.kode_comp,a.nocab)
                    )a left join (
                        select concat(a.kode_comp, a.nocab) as kode, a.kode_comp, a.nocab
                        from db_dp.t_dp a
                        where a.tahun = $tahun and a.status = 1
                    )b on a.kode = b.kode
                )b on a.kode = b.kode LEFT JOIN
                (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                            sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stok
                    from    data$tahun.st 
                    where 	substr(bulan,3) = $bulan and kode_gdg in ('pst',1)
                    group by nocab, kodeprod,bulan
                    order by kodeprod
                )c on a.nocab = c.nocab and a.kodeprod = c.kodeprod left join 
                (
                    select a.supp,a.kodeprod, a.namaprod, a.grup, a.subgroup, b.nama_group, c.nama_sub_group
                    from mpm.tabprod a LEFT JOIN 
                    (
                        select a.kode_group, a.nama_group
                        from mpm.tbl_group a
                    )b on a.grup = b.kode_group LEFT JOIN
                    (
                        select a.sub_group, a.nama_sub_group
                        from db_produk.t_sub_group a
                    )c on a.subgroup = c.sub_group
                )e on a.kodeprod = e.kodeprod
            )a
            GROUP BY kode, supp
            ORDER BY urutan
            ";
        $proses = $this->db->query($sql);
        
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
    }

    public function generate_dashboard($created_date){
        $this->db->query('truncate db_temp.t_temp_dashboard_mpm'); //herbal 2022
        $this->db->query('truncate db_temp.t_temp_dashboard_mpm_candy'); //candy 2022
        $sql = "
        insert into db_temp.t_temp_dashboard_mpm
        select b.branch_name, b.nama_comp, a.kodeprod, d.namasupp as principal, c.namaprod, a.bulan, a.unit, a.ot, '$created_date'
        from
        (
            select site_code,kodeprod,bulan,sum(unit) as unit, sum(ot) as ot
            from
            (
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                from data2022.fi a
                group by site_code, a.kodeprod, a.bulan
                union all
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, null
                from data2022.ri a
                group by site_code, a.kodeprod, a.bulan
            )a group by site_code, a.kodeprod, a.bulan
        )a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, a.supp
            from mpm.tabprod a
            where a.supp = 001 and a.grup ='G0101'
        )c on a.kodeprod = c.kodeprod LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )d on c.supp = d.supp
        ";
        $this->db->query($sql);

        $sql = "
        insert into db_temp.t_temp_dashboard_mpm_candy
        select b.branch_name, b.nama_comp, a.kodeprod, d.namasupp as principal, c.namaprod, a.bulan, a.unit, a.ot, '$created_date'
        from
        (
            select site_code,kodeprod,bulan,sum(unit) as unit, sum(ot) as ot
            from
            (
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                from data2022.fi a
                group by site_code, a.kodeprod, a.bulan
                union all
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, null
                from data2022.ri a
                group by site_code, a.kodeprod, a.bulan
            )a group by site_code, a.kodeprod, a.bulan
        )a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, a.supp
            from mpm.tabprod a
            where a.supp = 001 and a.grup ='G0102'
        )c on a.kodeprod = c.kodeprod LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )d on c.supp = d.supp
        ";
        $this->db->query($sql);
    }

    public function generate_dashboard_tahun_lalu($created_date){
        $this->db->query('truncate db_temp.t_temp_dashboard_mpm_tahun_lalu'); //herbal 2021
        $this->db->query('truncate db_temp.t_temp_dashboard_mpm_candy_tahun_lalu'); //candy 2021
        $sql = "
        insert into db_temp.t_temp_dashboard_mpm_tahun_lalu
        select b.branch_name, b.nama_comp, a.kodeprod, d.namasupp as principal, c.namaprod, a.bulan, a.unit, a.ot, '$created_date'
        from
        (
            select site_code,kodeprod,bulan,sum(unit) as unit, sum(ot) as ot
            from
            (
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                from data2021.fi a
                group by site_code, a.kodeprod, a.bulan
                union all
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, null
                from data2021.ri a
                group by site_code, a.kodeprod, a.bulan
            )a group by site_code, a.kodeprod, a.bulan
        )a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, a.supp
            from mpm.tabprod a
            where a.supp = 001 and a.grup ='G0101'
        )c on a.kodeprod = c.kodeprod LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )d on c.supp = d.supp
        ";
        $this->db->query($sql);

        $sql = "
        insert into db_temp.t_temp_dashboard_mpm_candy_tahun_lalu
        select b.branch_name, b.nama_comp, a.kodeprod, d.namasupp as principal, c.namaprod, a.bulan, a.unit, a.ot, '$created_date'
        from
        (
            select site_code,kodeprod,bulan,sum(unit) as unit, sum(ot) as ot
            from
            (
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                from data2021.fi a
                group by site_code, a.kodeprod, a.bulan
                union all
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, null
                from data2021.ri a
                group by site_code, a.kodeprod, a.bulan
            )a group by site_code, a.kodeprod, a.bulan
        )a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, a.supp
            from mpm.tabprod a
            where a.supp = 001 and a.grup ='G0102'
        )c on a.kodeprod = c.kodeprod LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )d on c.supp = d.supp
        ";
        $this->db->query($sql);
    }

    public function semut_gajah_site($created_date){

        $tahun_sekarang = date('Y');
        $truncate = $this->db->query("truncate db_master_data.t_site");

        if ($truncate) {
            $insert = "
            insert into db_master_data.t_site
            select a.site_code, a.branch_name,a.nama_comp,a.sub,a.provinsi, a.status_ho,a.telp_wa,$created_date
            from 
            (
                select concat(a.kode_comp,a.nocab) as site_code, a.branch_name, a.nama_comp, a.sub, a.status_ho,a.telp_wa, provinsi
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp,a.nocab)
            )a inner join 
            (
                select concat(a.kode_comp,a.nocab ) as site_code
                from db_dp.t_dp a
                where a.tahun = $tahun_sekarang and a.status = 1
            )b on a.site_code = b.site_code
            ";
            $proses = $this->db->query($insert);
            return $proses;
        }else{
            return array();
        }

    }

    // public function insert_t_temp_omzet_profile($data)
    // {
    //     # code...
    //     $year = substr($data['created_date'],0,4);
    //     $bulan = substr($data['created_date'],5,2);
    //     $created_date = "'".$data['created_date']."'";
    //     // var_dump($created_date);die;
    //     $truncate = $this->db->query("truncate db_afiliasi.t_temp_omzet_profile");

    //     if ($truncate) {
    //         $insert = "
    //         INSERT db_afiliasi.t_temp_omzet_profile
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'herbal' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'herbal' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         UNION ALL
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'candy' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'candy' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         UNION ALL
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.supp = 002 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.supp = 002 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         UNION ALL
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'jaya' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.supp = 004 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'jaya' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.supp = 004 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         UNION ALL
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'us' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.supp = 005 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'us' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.supp = 005 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         UNION ALL
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'intrafood' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.supp = 012 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'intrafood' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.supp = 012 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         UNION ALL
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'strive' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.supp = 013 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'strive' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.supp = 013 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         UNION ALL
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'hni' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.supp = 014 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'hni' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.supp = 014 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         UNION ALL
    //         select 	a.kode, a.`group`, SUM(a.omzet)/a.bulan as omzet, $created_date as created_date
    //         from
    //         (
    //                 select 	concat(a.kode_comp, a.nocab) as kode,
    //                                 a.kodeprod, sum(a.tot1) as omzet, 'mdj' as 'group', $bulan as bulan
    //                 from 	data$year.fi a
    //                 WHERE a.supp = 015 
    //                 GROUP BY kode
    //                 union all
    //                 select 	concat(a.kode_comp, a.nocab) as kode, 
    //                                 a.kodeprod,sum(a.tot1) as omzet, 'mdj' as 'group', $bulan as bulan
    //                 from 	data$year.ri a 
    //                 WHERE a.supp = 015 
    //                 GROUP BY kode
    //         )a GROUP BY a.kode
    //         ORDER BY kode;
    //         ";
    //         $proses = $this->db->query($insert);
    //         return $proses;
    //     }else{
    //         return array();
    //     }

    // }

    public function insert_t_temp_omzet_profile($data)
    {
        $this->db->truncate('db_afiliasi.t_temp_omzet_profile');
        # code...
        $tahun_sekarang = date("Y",strtotime($data['created_date']));
        $bulan_sekarang = date("m",strtotime($data['created_date']));
        $created_date = "'".$data['created_date']."'";
        // var_dump($created_date);die;
        
        $bulan_sebelumnya = $bulan_sekarang - 1;
        $bulan_avg = $bulan_sekarang - 6;
        $tahun_sebelumnya = $tahun_sekarang - 1;
        
        // echo "bulan_sebelumnya : ".$bulan_sebelumnya."<br>";
        // echo "bulan_avg : ".$bulan_avg;

        // die;

        if ($bulan_avg > 0) 
        {            
            for ($i=$bulan_avg; $i < $bulan_sekarang ; $i++) { 
                $bulan_avg_x[] = $i;
            }            
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            $sql = "
                INSERT db_afiliasi.t_temp_omzet_profile
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'herbal' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'herbal' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'candy' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                                    a.kodeprod,sum(a.tot1) as omzet, 'candy' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 002 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 002 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'jaya' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 004 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'jaya' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 004 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'us' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 005 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'us' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 005 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'intrafood' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 012 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'intrafood' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 012 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'strive' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 013 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'strive' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 013 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'hni' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 014 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'hni' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 014 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'mdj' as 'group'
                    from 	data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 015 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'mdj' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 015 
                    GROUP BY kode
                )a GROUP BY a.kode
                ORDER BY kode;
                ";

        }elseif($bulan_avg == 0){
            // echo "bulan_sekarang : ".$bulan_sekarang;
            $tahun_avg_x = $tahun_sekarang - 1;
            // echo "tahun_avg_x : ".$tahun_avg_x;

            for ($i=1; $i < $bulan_sekarang ; $i++) { 
                $bulan_avg_x[] = $i;
            }            
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            // echo "bulan_avg_y : ".$bulan_avg_y;

            $sql = "
                    INSERT db_afiliasi.t_temp_omzet_profile
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'herbal' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'herbal' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'herbal' as 'group'
                        from data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'herbal' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    UNION ALL
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'candy' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                        a.kodeprod,sum(a.tot1) as omzet, 'candy' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'candy' as 'group'
                        from data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                        a.kodeprod,sum(a.tot1) as omzet, 'candy' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    UNION ALL
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.supp = 002 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.supp = 002 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.supp = 002 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.supp = 002 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    UNION ALL
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.supp = 004 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.supp = 004 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'jaya' as 'group'
                        from data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.supp = 004 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'jaya' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.supp = 004 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    UNION ALL
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.supp = 005 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.supp = 005 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'us' as 'group'
                        from data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.supp = 005 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'us' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.supp = 005 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    UNION ALL
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.supp = 012 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.supp = 012 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'intrafood' as 'group'
                        from data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.supp = 012 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'intrafood' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.supp = 012 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    UNION ALL
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.supp = 013 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.supp = 013 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'strive' as 'group'
                        from data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.supp = 013 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'strive' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.supp = 013 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    UNION ALL
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.supp = 014 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.supp = 014 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'hni' as 'group'
                        from data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.supp = 014 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'hni' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.supp = 014 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    UNION ALL
                    select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.fi a
                        WHERE bulan in (12) and a.supp = 015 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                        from data$tahun_avg_x.ri a 
                        WHERE bulan in (12) and a.supp = 015 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode,
                                a.kodeprod, sum(a.tot1) as omzet, 'mdj' as 'group'
                        from 	data$tahun_sekarang.fi a
                        WHERE bulan in ($bulan_avg_y) and a.supp = 015 
                        GROUP BY kode
                        union all
                        select 	concat(a.kode_comp, a.nocab) as kode, 
                                a.kodeprod,sum(a.tot1) as omzet, 'mdj' as 'group'
                        from data$tahun_sekarang.ri a 
                        WHERE bulan in ($bulan_avg_y) and a.supp = 015 
                        GROUP BY kode
                    )a GROUP BY a.kode
                    ORDER BY kode
                ";
        }else{
            
            $cari_bulan_awal = 12 + $bulan_avg;
            for ($i=$cari_bulan_awal; $i <= 12 ; $i++) { 
                $bulan_avg_ay[] = $i;
            }      

            $bulan_avg = implode(', ', $bulan_avg_ay);
            echo "bulan_avg : ".$bulan_avg;

            for ($i=1; $i < $bulan_sekarang ; $i++) { 
                $bulan_avg_x[] = $i;
            }            
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            echo "bulan_avg_y : ".$bulan_avg_y;

            // die;
            $sql = "
                INSERT db_afiliasi.t_temp_omzet_profile
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'herbal' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'herbal' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'herbal' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'herbal' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0101') 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'candy' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                                    a.kodeprod,sum(a.tot1) as omzet, 'candy' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'candy' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                                    a.kodeprod,sum(a.tot1) as omzet, 'candy' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.kodeprod in (SELECT KODEPROD FROM mpm.tabprod WHERE grup = 'G0102') 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.supp = 002 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.supp = 002 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 002 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 002 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.supp = 004 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.supp = 004 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'jaya' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 004 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'jaya' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 004 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.supp = 005 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.supp = 005 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'us' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 005 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'us' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 005 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.supp = 012 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.supp = 012 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'intrafood' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 012 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'intrafood' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 012 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.supp = 013 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.supp = 013 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'strive' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 013 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'strive' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 013 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.supp = 014 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.supp = 014 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'hni' as 'group'
                    from data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 014 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'hni' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 014 
                    GROUP BY kode
                )a GROUP BY a.kode
                UNION ALL
                select 	a.kode, a.`group`, SUM(a.omzet)/6 as omzet, $created_date as created_date
                from
                (
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.fi a
                    WHERE bulan in ($bulan_avg) and a.supp = 015 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'marguna' as 'group'
                    from data$tahun_sebelumnya.ri a 
                    WHERE bulan in ($bulan_avg) and a.supp = 015 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode,
                            a.kodeprod, sum(a.tot1) as omzet, 'mdj' as 'group'
                    from 	data$tahun_sekarang.fi a
                    WHERE bulan in ($bulan_avg_y) and a.supp = 015 
                    GROUP BY kode
                    union all
                    select 	concat(a.kode_comp, a.nocab) as kode, 
                            a.kodeprod,sum(a.tot1) as omzet, 'mdj' as 'group'
                    from data$tahun_sekarang.ri a 
                    WHERE bulan in ($bulan_avg_y) and a.supp = 015 
                    GROUP BY kode
                )a GROUP BY a.kode
                ORDER BY kode
            ";
        }

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_kalender_data($region)
    {
        $tgl = date('Y-m-d');
        $bulan = date('m');
        $tahun = date('Y');
        $sql = "
            select a.kode, a.branch_name, a.nama_comp, c.tgl, DATEDIFF(c.tgl,'$tgl') as terlambat
            from
            (
                SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                FROM mpm.tbl_tabcomp a
                where a.`status` = 1 and a.active = 1 and region in ('$region')
                GROUP BY concat(a.kode_comp, a.nocab)
            )a INNER join 
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where a.tahun = $tahun
            )b on a.kode = b.kode
                LEFT JOIN
            (
                select 	concat(a.kode_comp,a.nocab) as kode, 
                                concat(max(a.THNDOK), '-', max(BLNDOK), '-', max(HRDOK)) as tgl
                from data$tahun.fi a
                where bulan in ($bulan)
                GROUP BY concat(a.kode_comp,a.nocab)
                ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
            )c on a.kode = c.kode
            ORDER BY c.tgl asc
        ";

        return $this->db->query($sql);
    }

    public function get_attachment($userid){
        $get_attachment = "
            select a.userid, b.username, b.email, a.region
            from site.map_akses_region a INNER JOIN mpm.user b 
                on a.userid = b.id INNER JOIN
                (
                    select 	a.region
                    from 	mpm.tbl_tabcomp a 
                    where 	a.region is not null and a.active = 1
                    GROUP BY a.region
                )c on a.region = c.region
            where b.active = 1 and a.`status` = 1 and a.userid = $userid
        ";
        // echo "<pre>";
        // print_r($get_attachment);
        // echo "</pre>";
        return $this->db->query($get_attachment);
    }

    public function get_filename_pdf($region){
        // echo $region;
        return $region;
    }

    public function send_email_kalender_data($attach,$userid){
        // echo "<pre>";
        // var_dump($userid);
        // echo "</pre>";
        // die;

        $this->db->select('username,email');
        $this->db->where('id', $userid);
        $data_user = $this->db->get('mpm.user')->row();

        $jumlah_attachment = count($attach);
        // echo "jumlah_attachment : ".$jumlah_attachment;

        for ($i=0; $i < $jumlah_attachment ; $i++) { 
            
            $filename_pdf_params = "kalender_data_".strtolower($attach[$i]).".pdf";
            // echo $filename_pdf_params;
            $this->email->attach('assets/file/pdf/'.$filename_pdf_params);
        }

        $from = "suffy@muliaputramandiri.com";
        $to = "ilhammsyah@gmail.com";
        // $cc = "ilhammsyah@gmail.com";\
        $subject = "MPM SITE | Informasi Harian Otomatis - Kalender Data";
        
        $message = "FYI - $data_user->username" ;

        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        // $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/pdf/kalender_data_'.strtolower($attach[$i]).'.pdf')

        if ( ! $this->email->send()) {
            show_error($this->email->print_debugger());
            return false;
        }else{
            // unlink('assets/file/pdf/'.$filename_pdf_params);
            // return true;
            redirect('master_data/automatic_kalender_data_email_generate');
        }

        // return true;

    }

    public function email(){
        $this->load->library('email');
        $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        // $config['smtp_user']    = 'support@muliaputramandiri.com';
        $config['smtp_user']    = 'suffy@muliaputramandiri.net';
        // $config['smtp_pass']    = 'support123!@#';
        $config['smtp_pass']    = 'vruzinbjlnsgzagy';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
    }

    public function insert_pajak_masukan(){
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345!@#$%");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn) {
            echo "<br><br><pre>Koneksi dengan Server SDS berhasil </pre><br />";
            $this->db->query('truncate management_retur.temp_pajak_masukan');

                $sql = "
                    SELECT
                    t_ap_master.no_inv,
                    t_ap_master.tgl_terima,
                    ISNULL( t_ap_master.no_seri_pajak_ap, t_ap_master.no_inv ) AS no_seri_pajak_ap,
                    ISNULL( t_ap_master.tgl_pajak_ap, t_ap_master.tgl_terima ) AS tgl_pajak_ap,
                    ( CASE WHEN t_ap_master.alasan = '21' THEN 'Faktur' ELSE 'Retur' END ) AS tipe_trans,
                    t_ap_master.type_gudang,
                    t_ap_master.alasan,
                    t_ap_master.warehouseid,
                    t_ap_master.siteid,
                    t_ap_master.karyawanid,
                    t_ap_master.supplierid,
                    t_ap_master.no_sj,
                    t_ap_master.nama_sopir,
                    t_ap_master.no_polisi,
                    t_ap_master.tgl_sj,
                    t_ap_master.nama_tertanda,
                    t_ap_master.keterangan,
                    t_ap_master.flag_terima,
                    t_ap_master.from_po,
                    t_ap_master.flag_print,
                    t_ap_master.flag_proses,
                    t_ap_master.categoryid,
                    t_ap_master.program,
                    t_ap_master.userid,
                    t_ap_master.create_log,
                    t_ap_master.user_app,
                    t_ap_master.date_app,
                    t_ap_master.flag_transfer,
                    t_ap_master.flag_masalah,
                    t_ap_master.to_gudang,
                    t_ap_master.to_siteid,
                    t_ap_master.to_user,
                    t_ap_master.to_date,
                    t_ap_master.to_status,
                    t_ap_master.mesin,
                    CAST ( 0 AS BIT ) AS flag_select,
                    m_setup_site.nama_site,
                    m_warehouse.nama_warehouse AS type_gudang2,
                    m_product_category.nama_category,
                    m_org_employee.nama_karyawan,
                    m_warehouse_supplier.nama_supplier,
                    t_ap_master.alasan + ' ' + m_warehouse_alasangudang.keterangan AS alasan2,
                    LEFT ( t_ap_master.no_inv, 3 ) + '.' + SUBSTRING ( LEFT ( t_ap_master.no_inv, 4 ), 4, 1 ) + '.' + SUBSTRING ( LEFT ( t_ap_master.no_inv, 6 ), 5, 2 ) + '.' + SUBSTRING ( LEFT ( t_ap_master.no_inv, 8 ), 7, 2 ) + '-' + SUBSTRING ( LEFT ( t_ap_master.no_inv, 14 ), 9, 6 ) + '-' + RIGHT ( t_ap_master.no_inv, 4 ) AS no_inv2,
                    t_ap_master.tgl_tempo,
                    (
                    CASE
                            
                            WHEN t_ap_master.tipe_bayar = 'T' THEN
                            'TUNAI' 
                            WHEN t_ap_master.tipe_bayar = 'K' THEN
                            'KREDIT' 
                            WHEN t_ap_master.tipe_bayar = 'G' THEN
                            'GIRO' 
                            WHEN t_ap_master.tipe_bayar = 'T' THEN
                            'TRANSFER' 
                        END 
                        ) AS tipe_bayar,
                        ISNULL( t_ap_master.disc2, 0 ) AS disc2,
                        ISNULL( t_ap_master.PPH22, 0 ) AS PPH22,
                        t_ap_master.bruto,
                        ISNULL( t_ap_master.disc, 0 ) AS disc,
                        t_ap_master.dokument,
                        t_ap_master.flag_lunas,
                        t_ap_master.bayar,
                        t_ap_master.bayar_tunai,
                        t_ap_master.bayar_transfer,
                        t_ap_master.bayar_giro,
                        t_ap_master.flag_proses_ap,
                        t_ap_master.ket,
                        t_ap_master.bruto - ( ISNULL( t_ap_master.disc, 0 ) + ISNULL( t_ap_master.disc2, 0 ) ) AS dpp,
                        ( t_ap_master.bruto - ( ISNULL( t_ap_master.disc, 0 ) + ISNULL( t_ap_master.disc2, 0 ) ) ) - ( t_ap_master.bruto - ( ISNULL( t_ap_master.disc, 0 ) + ISNULL( t_ap_master.disc2, 0 ) ) ) * ISNULL( t_ap_master.ppn_percent_1, 10 ) / 100 AS dpp1,
                        ( t_ap_master.bruto - ( ISNULL( t_ap_master.disc, 0 ) + ISNULL( t_ap_master.disc2, 0 ) ) ) * ISNULL( t_ap_master.ppn_percent_1, 10 ) / 100 AS ppn,
                        t_ap_master.dokument - ISNULL( t_ap_master.PPH22, 0 ) AS dppn,
                        t_ap_master.bruto AS Expr1,
                        ISNULL( t_ap_master.disc, 0 ) + ISNULL( t_ap_master.disc2, 0 ) AS disc,
                        t_ap_master.bruto - ( ISNULL( t_ap_master.disc, 0 ) + ISNULL( t_ap_master.disc2, 0 ) ) + ISNULL( t_ap_master.PPH22, 0 ) AS netto,
                        t_ap_master.prinsipalid,
                        t_ap_master.prinsipal 
                    FROM
                        dbsls.dbo.t_ap_master
                        INNER JOIN dbsls.dbo.m_setup_site ON t_ap_master.siteid = m_setup_site.siteid
                        LEFT OUTER JOIN dbsls.dbo.m_warehouse ON t_ap_master.warehouseid = m_warehouse.warehouseid 
                        AND t_ap_master.siteid = m_warehouse.siteid 
                        AND t_ap_master.type_gudang = m_warehouse.type
                        LEFT OUTER JOIN dbsls.dbo.m_warehouse_alasangudang ON t_ap_master.alasan = m_warehouse_alasangudang.alasan
                        LEFT OUTER JOIN dbsls.dbo.m_org_employee ON t_ap_master.karyawanid = m_org_employee.karyawanid 
                        AND t_ap_master.siteid = m_org_employee.siteid
                        LEFT OUTER JOIN dbsls.dbo.m_product_category ON t_ap_master.categoryid = m_product_category.categoryid
                        LEFT OUTER JOIN dbsls.dbo.m_warehouse_supplier ON t_ap_master.supplierid = m_warehouse_supplier.supplierid 
                    WHERE (t_ap_master.program = 1) 
                    ";

                $query = sqlsrv_query($conn, $sql); 
                if ($query) {
                    while ($data = sqlsrv_fetch_array($query)){
                        $data = array(
                            'no_inv'            => trim($data['no_inv']),
                            'tgl_terima'        => date_format($data['tgl_terima'],'d/m/Y H:i:s'),
                            'no_seri_pajak_ap'  => trim($data['no_seri_pajak_ap']),
                            'tgl_pajak_ap'      => date_format($data['tgl_pajak_ap'],'d/m/Y H:i:s'),
                            'tipe_trans'        => trim($data['tipe_trans']),
                            'type_gudang'       => trim($data['type_gudang']),
                            'alasan'            => trim($data['alasan']),
                            'warehouseid'       => trim($data['warehouseid']),
                            'siteid'            => trim($data['siteid']),
                            'karyawanid'        => trim($data['karyawanid']),
                            'supplierid'        => trim($data['supplierid']),
                            'no_sj'             => trim($data['no_sj']),
                            'nama_sopir'        => trim($data['nama_sopir']),
                            'no_polisi'         => trim($data['no_polisi']),
                            'tgl_sj'            => date_format($data['tgl_sj'],'d/m/Y H:i:s'),
                            'nama_tertanda'     => trim($data['nama_tertanda']),
                            'keterangan'        => trim($data['keterangan']),
                            'flag_terima'       => trim($data['flag_terima']),
                            'from_po'           => trim($data['from_po']),
                            'flag_print'        => trim($data['flag_print']),
                            'flag_proses'       => trim($data['flag_proses']),
                            'categoryid'        => trim($data['categoryid']),
                            'program'           => trim($data['program']),
                            'userid'            => trim($data['userid']),
                            'create_log'        => date_format($data['create_log'],'d/m/Y H:i:s'),
                            'user_app'          => trim($data['user_app']),
                            'date_app'          => trim($data['date_app']),
                            'flag_transfer'     => trim($data['flag_transfer']),
                            'flag_masalah'      => trim($data['flag_masalah']),
                            'to_gudang'         => trim($data['to_gudang']),
                            'to_siteid'         => trim($data['to_siteid']),
                            'to_user'           => trim($data['to_user']),
                            'to_date'           => trim($data['to_date']),
                            'to_status'         => trim($data['to_status']),
                            'mesin'             => trim($data['mesin']),
                            'flag_select'       => trim($data['flag_select']),
                            'nama_site'         => trim($data['nama_site']),
                            'type_gudang2'      => trim($data['type_gudang2']),
                            'nama_category'     => trim($data['nama_category']),
                            'nama_karyawan'     => trim($data['nama_karyawan']),
                            'nama_supplier'     => trim($data['nama_supplier']),
                            'alasan2'           => trim($data['alasan2']),
                            'no_inv2'           => trim($data['no_inv2']),
                            'tgl_tempo'         => date_format($data['tgl_tempo'],'d/m/Y H:i:s'),
                            'tipe_bayar'        => trim($data['tipe_bayar']),
                            'disc2'             => trim($data['disc2']),
                            'PPH22'             => trim($data['PPH22']),
                            'bruto'             => trim($data['bruto']),
                            'disc'              => trim($data['disc']),
                            'dokument'          => trim($data['dokument']),
                            'flag_lunas'        => trim($data['flag_lunas']),
                            'bayar'             => trim($data['bayar']),
                            'bayar_tunai'       => trim($data['bayar_tunai']),
                            'bayar_transfer'    => trim($data['bayar_transfer']),
                            'bayar_giro'        => trim($data['bayar_giro']),
                            'flag_proses_ap'    => trim($data['flag_proses_ap']),
                            'ket'               => trim($data['ket']),
                            'dpp'               => trim($data['dpp']),
                            'dpp1'              => trim($data['dpp1']),
                            'ppn'               => trim($data['ppn']),
                            'dppn'              => trim($data['dppn']),
                            'Expr1'             => trim($data['Expr1']),
                            'disc'              => trim($data['disc']),
                            'netto'             => trim($data['netto']),
                            'prinsipalid'       => trim($data['prinsipalid']),
                            'prinsipal'         => trim($data['prinsipal']),
                        );

                    $proses = $this->db->insert('management_retur.temp_pajak_masukan',$data);
                }
            }
        } else {
            echo "<br><br><pre>Koneksi dengan Server SDS Gagal </pre><br />";
        }
    }

    public function update_sell_out($kodeprod){

        $tahun = date('Y');
        $created_at = $this->model_outlet_transaksi->timezone();

        $truncate = $this->db->query('truncate site.temp_sell_out');
        if ($truncate) {
            $query = "
                insert into site.temp_sell_out
                select  a.site_code, b.branch_name, b.nama_comp, b.sub,a.kodeprod, a.namaprod, a.grup, c.nama_group, a.kodesalur, 
                        d.namasalur, d.groupsalur, a.kode_type, e.nama_type, e.sektor, e.segment,a.subgroup, f.nama_sub_group, 
                        a.kodesales, g.namasales,                

                        sum(if(bulan = '01', trans, 0)) as ot_1,
                        sum(if(bulan = '02', trans, 0)) as ot_2,
                        sum(if(bulan = '03', trans, 0)) as ot_3,	
                        sum(if(bulan = '04', trans, 0)) as ot_4,
                        sum(if(bulan = '05', trans, 0)) as ot_5,
                        sum(if(bulan = '06', trans, 0)) as ot_6,
                        sum(if(bulan = '07', trans, 0)) as ot_7,
                        sum(if(bulan = '08', trans, 0)) as ot_8,		
                        sum(if(bulan = '09', trans, 0)) as ot_9,
                        sum(if(bulan = '10', trans, 0)) as ot_10,
                        sum(if(bulan = '11', trans, 0)) as ot_11,
                        sum(if(bulan = '12', trans, 0)) as ot_12,
                                      
                           
                        sum(if(bulan = '01', unit, 0)) as unit_1,
                        sum(if(bulan = '02', unit, 0)) as unit_2,	
                        sum(if(bulan = '03', unit, 0)) as unit_3,
                        sum(if(bulan = '04', unit, 0)) as unit_4,
                        sum(if(bulan = '05', unit, 0)) as unit_5,
                        sum(if(bulan = '06', unit, 0)) as unit_6,
                        sum(if(bulan = '07', unit, 0)) as unit_7,
                        sum(if(bulan = '08', unit, 0)) as unit_8,
                        sum(if(bulan = '09', unit, 0)) as unit_9,
                        sum(if(bulan = '10', unit, 0)) as unit_10,	
                        sum(if(bulan = '11', unit, 0)) as unit_11,
                        sum(if(bulan = '12', unit, 0)) as unit_12,    
                        
                        sum(if(bulan = '01', value, 0)) as value_1,
                        sum(if(bulan = '02', value, 0)) as value_2,	
                        sum(if(bulan = '03', value, 0)) as value_3,
                        sum(if(bulan = '04', value, 0)) as value_4,
                        sum(if(bulan = '05', value, 0)) as value_5,
                        sum(if(bulan = '06', value, 0)) as value_6,
                        sum(if(bulan = '07', value, 0)) as value_7,
                        sum(if(bulan = '08', value, 0)) as value_8,
                        sum(if(bulan = '09', value, 0)) as value_9,
                        sum(if(bulan = '10', value, 0)) as value_10,	
                        sum(if(bulan = '11', value, 0)) as value_11,
                        sum(if(bulan = '12', value, 0)) as value_12, 
                        
                        sum(if(bulan = '01', ec, 0)) as ec_1,
                        sum(if(bulan = '02', ec, 0)) as ec_2,	
                        sum(if(bulan = '03', ec, 0)) as ec_3,
                        sum(if(bulan = '04', ec, 0)) as ec_4,
                        sum(if(bulan = '05', ec, 0)) as ec_5,
                        sum(if(bulan = '06', ec, 0)) as ec_6,
                        sum(if(bulan = '07', ec, 0)) as ec_7,
                        sum(if(bulan = '08', ec, 0)) as ec_8,
                        sum(if(bulan = '09', ec, 0)) as ec_9,
                        sum(if(bulan = '10', ec, 0)) as ec_10,	
                        sum(if(bulan = '11', ec, 0)) as ec_11,
                        sum(if(bulan = '12', ec, 0)) as ec_12,
                        b.urutan,b.status,0,'$created_at'
                from
                (
                    select 	site_code, a.kodeprod, bulan, outlet, sum(banyak) as unit, sum(tot1) as value, 
                            count(distinct(outlet)) as trans, count(outlet) as ec, kodesalur, a.kode_type, 
                            null, c.namaprod, c.grup, c.subgroup, a.kode_sales, a.kodesales
                    from
                    (
                        select 	concat(a.kode_comp, a.nocab) as site_code, concat(a.kode_comp, a.kode_lang) as outlet,
                                concat(a.kode_comp, a.kode_lang) as outletx, a.banyak, a.tot1, a.kodeprod, a.bulan, a.kodesalur,
                                a.kode_type, concat(a.kodesales, a.nocab) as kode_sales, a.kodesales
                        from 	data$tahun.fi a 
                        where 	a.kodeprod in ($kodeprod)
                        union all
                        select 	concat(a.kode_comp, a.nocab) as site_code, '',
                                    concat(a.kode_comp, a.kode_lang) as outletx, a.banyak, a.tot1, a.kodeprod, a.bulan, a.kodesalur,
                                    a.kode_type, concat(a.kodesales, a.nocab) as kode_sales, a.kodesales
                        from 	data$tahun.ri a 
                        where 	a.kodeprod in ($kodeprod)
                    )a LEFT JOIN
                    (
                        select a.kodeprod, a.namaprod, a.grup, a.subgroup, qty1, qty2, qty3
                        from mpm.tabprod a
                    )c on a.kodeprod = c.kodeprod
                    group by bulan, a.site_code, a.kodeprod, a.kodesalur, a.kode_type 
                )a left join
                (
                    select 	concat(kode_comp, nocab) as site_code, branch_name, nama_comp, sub, urutan,status
                    from     mpm.tbl_tabcomp
                    where   `status` = 1
                    GROUP BY concat(kode_comp, nocab)
                )b on a.site_code = b.site_code LEFT JOIN
                (
                    select a.kode_group, a.nama_group
                    from mpm.tbl_group a
                )c on a.grup = c.kode_group LEFT JOIN
                (
                    select a.kode, a.jenis as namasalur, a.`group` as groupsalur
                    from mpm.tbl_tabsalur a
                )d on a.kodesalur = d.kode LEFT JOIN
                (
                    select a.kode_type, a.nama_type, a.sektor, a.segment
                    from mpm.tbl_bantu_type a
                )e on a.kode_type= e.kode_type LEFT JOIN
                (
                    select a.sub_group, a.nama_sub_group
                    from db_produk.t_sub_group a
                )f on a.subgroup = f.sub_group
                left join
                (
                    select concat(kodesales,nocab) as kode_sales, kodesales, namasales
                    from data$tahun.tabsales
                    group by kode_sales
                )g on a.kode_sales = g.kode_sales
                group by '' , a.site_code, a.kodeprod, groupsalur, kode_type   
            ";

            $proses = $this->db->query($query);
        }

    }

    public function update_sell_out_us($kodeprod){

        $tahun = date('Y');
        $created_at = $this->model_outlet_transaksi->timezone();

        $truncate = $this->db->query('truncate site.temp_soprod_us');
        if ($truncate) {
            $query = "
                insert into site.temp_soprod_us
                select  a.kode,b.branch_name,b.nama_comp, b.sub,
                        a.kodeprod,a.namaprod,a.grup, c.nama_group,a.kodesalur,d.namasalur,d.groupsalur, a.kode_type, e.nama_type, e.sektor as sektor, e.segment,a.subgroup, f.nama_sub_group, a.kodesales, g.namasales,
                        sum(if(bulan = '01', trans, 0)) as ot_1,
                        sum(if(bulan = '02', trans, 0)) as ot_2,
                        sum(if(bulan = '03', trans, 0)) as ot_3,	
                        sum(if(bulan = '04', trans, 0)) as ot_4,
                        sum(if(bulan = '05', trans, 0)) as ot_5,
                        sum(if(bulan = '06', trans, 0)) as ot_6,
                        sum(if(bulan = '07', trans, 0)) as ot_7,
                        sum(if(bulan = '08', trans, 0)) as ot_8,		
                        sum(if(bulan = '09', trans, 0)) as ot_9,
                        sum(if(bulan = '10', trans, 0)) as ot_10,
                        sum(if(bulan = '11', trans, 0)) as ot_11,
                        sum(if(bulan = '12', trans, 0)) as ot_12,
                        sum(if(bulan = '01', unit, 0)) as unit_1,
                        sum(if(bulan = '02', unit, 0)) as unit_2,	
                        sum(if(bulan = '03', unit, 0)) as unit_3,
                        sum(if(bulan = '04', unit, 0)) as unit_4,
                        sum(if(bulan = '05', unit, 0)) as unit_5,
                        sum(if(bulan = '06', unit, 0)) as unit_6,
                        sum(if(bulan = '07', unit, 0)) as unit_7,
                        sum(if(bulan = '08', unit, 0)) as unit_8,
                        sum(if(bulan = '09', unit, 0)) as unit_9,
                        sum(if(bulan = '10', unit, 0)) as unit_10,	
                        sum(if(bulan = '11', unit, 0)) as unit_11,
                        sum(if(bulan = '12', unit, 0)) as unit_12,
                        sum(if(bulan = '01', value, 0)) as value_1,
                        sum(if(bulan = '02', value, 0)) as value_2,	
                        sum(if(bulan = '03', value, 0)) as value_3,
                        sum(if(bulan = '04', value, 0)) as value_4,
                        sum(if(bulan = '05', value, 0)) as value_5,
                        sum(if(bulan = '06', value, 0)) as value_6,
                        sum(if(bulan = '07', value, 0)) as value_7,
                        sum(if(bulan = '08', value, 0)) as value_8,
                        sum(if(bulan = '09', value, 0)) as value_9,
                        sum(if(bulan = '10', value, 0)) as value_10,	
                        sum(if(bulan = '11', value, 0)) as value_11,
                        sum(if(bulan = '12', value, 0)) as value_12,
                        sum(if(bulan = '01', ec, 0)) as ec_1,
                        sum(if(bulan = '02', ec, 0)) as ec_2,	
                        sum(if(bulan = '03', ec, 0)) as ec_3,
                        sum(if(bulan = '04', ec, 0)) as ec_4,
                        sum(if(bulan = '05', ec, 0)) as ec_5,
                        sum(if(bulan = '06', ec, 0)) as ec_6,
                        sum(if(bulan = '07', ec, 0)) as ec_7,
                        sum(if(bulan = '08', ec, 0)) as ec_8,
                        sum(if(bulan = '09', ec, 0)) as ec_9,
                        sum(if(bulan = '10', ec, 0)) as ec_10,	
                        sum(if(bulan = '11', ec, 0)) as ec_11,
                        sum(if(bulan = '12', ec, 0)) as ec_12,
                        b.urutan,b.status,0,'$created_at'
                from
                (
                    
            select kode, a.kodeprod, bulan, outlet, SUM(`banyak`) as unit, SUM(`tot1`) as value, COUNT(DISTINCT(outlet)) as trans, COUNT(outlet) as ec, kodesalur, a.kode_type, null, c.namaprod, c.grup, c.subgroup, a.kode_sales, a.kodesales
            FROM
            (
                select concat(kode_comp, nocab) as kode, CONCAT(KODE_COMP,kode_lang) as outlet, CONCAT(KODE_COMP,kode_lang) as outletx, `banyak`, `tot1`, kodeprod, bulan, kodesalur, kode_type, concat(kodesales,nocab) as kode_sales, kodesales
                from `data$tahun`.`fi`
                where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' 
                union all
                select concat(kode_comp, nocab) as kode, null, CONCAT(KODE_COMP,kode_lang) as outletx, `banyak`, `tot1`, kodeprod, bulan, kodesalur, kode_type, concat(kodesales,nocab) as kode_sales, kodesales
                from `data$tahun`.`ri`
                where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' 
            )a  
            LEFT JOIN
            (
                select a.kodeprod, a.namaprod, a.grup, a.subgroup, qty1, qty2, qty3
                from mpm.tabprod a
            )c on a.kodeprod = c.kodeprod
            group by bulan ,a.kode ,a.kodeprod   ,grup  
                                              
                )a LEFT JOIN
                (
                    select  concat(kode_comp, nocab) as kode, branch_name, nama_comp, sub, urutan,status
                    from     mpm.tbl_tabcomp
                    where   `status` = 1
                    GROUP BY concat(kode_comp, nocab)
                )b on a.kode = b.kode LEFT JOIN
                (
                        select a.kode_group, a.nama_group
                        from mpm.tbl_group a
                )c on a.grup = c.kode_group LEFT JOIN
                (
                        select a.kode, a.jenis as namasalur, a.`group` as groupsalur
                        from mpm.tbl_tabsalur a
                )d on a.kodesalur = d.kode LEFT JOIN
                (
                        select a.kode_type, a.nama_type, a.sektor, a.segment
                        from mpm.tbl_bantu_type a
                )e on a.kode_type= e.kode_type LEFT JOIN
                (
                        select a.sub_group, a.nama_sub_group
                        from db_produk.t_sub_group a
                )f on a.subgroup = f.sub_group
                left join
                (
                    select concat(kodesales,nocab) as kode_sales, kodesales, namasales
                    from data$tahun.tabsales
                    group by kode_sales
                )g on a.kode_sales = g.kode_sales
                group by '', a.kode, a.kodeprod, grup     
            ";

            echo "<pre>";
            print_r($query);
            echo "</pre>";

            $proses = $this->db->query($query);
        }

    }

    public function update_sell_out_deltomed_segment($kodeprod){

        $tahun = date('Y');
        $created_at = $this->model_outlet_transaksi->timezone();

        $truncate = $this->db->query('truncate site.temp_soprod_deltomed_segment');
        if ($truncate) {
            $query = "
                insert into site.temp_soprod_deltomed_segment
                select 	a.kodeprod, a.namaprod, a.segment,
                        sum(if(a.bulan = 1, a.trans, 0)) as ot_1,
                        sum(if(a.bulan = 2, a.trans, 0)) as ot_2,
                        sum(if(a.bulan = 3, a.trans, 0)) as ot_3,
                        sum(if(a.bulan = 4, a.trans, 0)) as ot_4,
                        sum(if(a.bulan = 5, a.trans, 0)) as ot_5,
                        sum(if(a.bulan = 6, a.trans, 0)) as ot_6,
                        sum(if(a.bulan = 7, a.trans, 0)) as ot_7,
                        sum(if(a.bulan = 8, a.trans, 0)) as ot_8,
                        sum(if(a.bulan = 9, a.trans, 0)) as ot_9,
                        sum(if(a.bulan = 10, a.trans, 0)) as ot_10,
                        sum(if(a.bulan = 11, a.trans, 0)) as ot_11,
                        sum(if(a.bulan = 12, a.trans, 0)) as ot_12,
                        sum(if(a.bulan = 1, a.unit, 0)) as unit_1,
                        sum(if(a.bulan = 2, a.unit, 0)) as unit_2,
                        sum(if(a.bulan = 3, a.unit, 0)) as unit_3,
                        sum(if(a.bulan = 4, a.unit, 0)) as unit_4,
                        sum(if(a.bulan = 5, a.unit, 0)) as unit_5,
                        sum(if(a.bulan = 6, a.unit, 0)) as unit_6,
                        sum(if(a.bulan = 7, a.unit, 0)) as unit_7,
                        sum(if(a.bulan = 8, a.unit, 0)) as unit_8,
                        sum(if(a.bulan = 9, a.unit, 0)) as unit_9,
                        sum(if(a.bulan = 10, a.unit, 0)) as unit_10,
                        sum(if(a.bulan = 11, a.unit, 0)) as unit_11,
                        sum(if(a.bulan = 12, a.unit, 0)) as unit_12,
                        sum(if(a.bulan = 1, a.omzet, 0)) as omzet_1,
                        sum(if(a.bulan = 2, a.omzet, 0)) as omzet_2,
                        sum(if(a.bulan = 3, a.omzet, 0)) as omzet_3,
                        sum(if(a.bulan = 4, a.omzet, 0)) as omzet_4,
                        sum(if(a.bulan = 5, a.omzet, 0)) as omzet_5,
                        sum(if(a.bulan = 6, a.omzet, 0)) as omzet_6,
                        sum(if(a.bulan = 7, a.omzet, 0)) as omzet_7,
                        sum(if(a.bulan = 8, a.omzet, 0)) as omzet_8,
                        sum(if(a.bulan = 9, a.omzet, 0)) as omzet_9,
                        sum(if(a.bulan = 10, a.omzet, 0)) as omzet_10,
                        sum(if(a.bulan = 11, a.omzet, 0)) as omzet_11,
                        sum(if(a.bulan = 12, a.omzet, 0)) as omzet_12, '$created_at'		
            from 
            (
                select a.kodeprod, a.bulan, sum(a.banyak) as unit, sum(a.tot1) as omzet, COUNT(DISTINCT(outlet)) as trans, a.kode_type, b.segment, c.namaprod
                from 
                (
                    select concat(a.kode_comp, a.kode_lang) as outlet, a.kodeprod, a.banyak, a.tot1, a.bulan, a.kode_type
                    from data$tahun.fi a 
                    where a.kodeprod in ($kodeprod)
                    union all 
                    select null, a.kodeprod, a.banyak, a.tot1, a.bulan, a.kode_type
                    from data$tahun.ri a 
                    where a.kodeprod in ($kodeprod)
                )a LEFT JOIN (
                    select a.kode_type, a.nama_type, a.sektor, a.segment
                    from mpm.tbl_bantu_type a 
                )b on a.kode_type = b.kode_type LEFT JOIN (
                    select a.kodeprod, a.namaprod
                    from mpm.tabprod a
                )c on a.kodeprod = c.kodeprod
                GROUP BY a.kodeprod, a.bulan, b.segment
            )a GROUP BY a.kodeprod, a.segment     
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";

            // die;

            $proses = $this->db->query($query);
        }

    }

    // =========================== po sds lulyana ===========================

    public function insert_temp_po_sds($created_at){
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345!@#$%");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn) 
        {
            echo "<br><br><pre>Koneksi dengan Server SDS berhasil </pre><br />";
            $this->db->query('truncate db_temp.temp_po_sds');

            $query = "            
                SELECT  t_sales_master.siteid, t_sales_master.no_sales, t_sales_master.tanggal, t_sales_master.customerid, 
                        t_sales_master.tgl_periode, t_sales_master.tipe_sales, t_sales_master.tipe_trans, t_sales_master.tgl_po, 
                        t_sales_detail.productid,
                        t_sales_detail.nourut, t_sales_detail.sat_besar, t_sales_detail.sat_sedang, t_sales_detail.sat_kecil,
                        t_sales_detail.isi_besar, t_sales_detail.isi_sedang, t_sales_detail.isi_kecil, t_sales_detail.qty1,
                        t_sales_detail.qty2, t_sales_detail.qty3, t_sales_detail.qty_kecil, t_sales_detail.sat_bonus,
                        t_sales_detail.qty_bonus, t_sales_detail.flag_bonus, t_sales_detail.flag_garansi, t_sales_detail.disc_persen,
                        t_sales_detail.disc_rp, t_sales_detail.disc_value, t_sales_detail.beli, t_sales_detail.jual,
                        t_sales_detail.nego, t_sales_detail.karoseri, t_sales_detail.ppnbm, t_sales_detail.total_harga,
                        t_sales_detail.flag_delete, t_sales_detail.jasa, t_sales_detail.jasa_id, t_sales_detail.biaya_jasa,
                        t_sales_detail.flag_stock, t_sales_detail.no_mesin, t_sales_detail.no_rangka, t_sales_detail.tahun,
                        t_sales_detail.warna, t_sales_detail.ket_mesin, t_sales_detail.flag_karoseri, t_sales_detail.program,
                        t_sales_detail.disc_cabang, t_sales_detail.disc_prinsipal, t_sales_detail.disc_xtra,
                        t_sales_detail.disc_cod, t_sales_detail.rp_cabang, t_sales_detail.rp_prinsipal,
                        t_sales_detail.rp_xtra, t_sales_detail.rp_cod, m_customer.nama_customer,
                        m_customer.prefix, m_customer.alamat, m_customer.kotaid, m_customer.segmentid, m_customer.typeid, t_sales_detail.ppn_percent_1, t_sales_detail.ppn_percent_2, t_sales_detail.ppn_percent_3
                FROM    dbsls.dbo.t_sales_master 
                LEFT JOIN dbsls.dbo.t_sales_detail ON dbsls.dbo.t_sales_master.no_sales = dbsls.dbo.t_sales_detail.no_sales 
                LEFT JOIN dbsls.dbo.m_customer  ON dbsls.dbo.t_sales_master.customerid = dbsls.dbo.m_customer.customerid 
                WHERE   dbsls.dbo.t_sales_master.customerid IN ( SELECT customerid FROM dbsls.dbo.m_customer WHERE typeid LIKE 'BDG%' ) 
            ";

                echo "<pre>";
                print_r($query);
                echo "</pre>";

                $query = sqlsrv_query($conn, $query); 
                if ($query) 
                {
                    while ($data = sqlsrv_fetch_array($query))
                    {

                        // $tgl_periode = $data['tgl_periode'];
                        // echo "tgl_periode : ".$tgl_periode;
                        // die;

                        $data = array(
                            'siteid'            => trim($data['siteid']),
                            'no_sales'          => trim($data['no_sales']),
                            'tanggal'           => ($data['tanggal']) ? date_format($data['tanggal'],'Y-m-d') : NULL,
                            'customerid'        => trim($data['customerid']),
                            'tgl_periode'       => ($data['tgl_periode']) ? date_format($data['tgl_periode'],'Y-m-d') : NULL,
                            'tipe_sales'        => trim($data['tipe_sales']),
                            'tipe_trans'        => trim($data['tipe_trans']),
                            'tgl_po'            => ($data['tgl_po']) ? date_format($data['tgl_po'],'Y-m-d') : NULL,
                            'productid'         => trim($data['productid']),
                            'nourut'            => trim($data['nourut']),
                            'sat_besar'         => trim($data['sat_besar']),
                            'sat_sedang'        => trim($data['sat_sedang']),
                            'sat_kecil'         => trim($data['sat_kecil']),
                            'isi_besar'         => trim($data['isi_besar']),
                            'isi_sedang'        => trim($data['isi_sedang']),
                            'isi_kecil'         => trim($data['isi_kecil']),
                            'qty1'              => trim($data['qty1']),
                            'qty2'              => trim($data['qty2']),
                            'qty3'              => trim($data['qty3']),
                            'qty_kecil'         => trim($data['qty_kecil']),
                            'sat_bonus'         => trim($data['sat_bonus']),
                            'qty_bonus'         => trim($data['qty_bonus']),
                            'flag_bonus'        => trim($data['flag_bonus']),
                            'flag_garansi'      => trim($data['flag_garansi']),
                            'disc_persen'       => trim($data['disc_persen']),
                            'disc_rp'           => trim($data['disc_rp']),
                            'disc_value'        => trim($data['disc_value']),
                            'beli'              => trim($data['beli']),
                            'jual'              => trim($data['jual']),
                            'nego'              => trim($data['nego']),
                            'karoseri'          => trim($data['karoseri']),
                            'ppnbm'             => trim($data['ppnbm']),
                            'total_harga'       => trim($data['total_harga']),
                            'flag_delete'       => trim($data['flag_delete']),
                            'jasa'              => trim($data['jasa']),
                            'jasa_id'           => trim($data['jasa_id']),
                            'biaya_jasa'        => trim($data['biaya_jasa'] * $data['ppn_percent_2']),
                            'flag_stock'        => trim($data['flag_stock']),
                            'no_mesin'          => trim($data['no_mesin']),
                            'no_rangka'         => trim($data['no_rangka']),
                            'tahun'             => trim($data['tahun']),
                            'warna'             => trim($data['warna']),
                            'ket_mesin'         => trim($data['ket_mesin']),
                            'flag_karoseri'     => trim($data['flag_karoseri']),
                            'program'           => trim($data['program']),
                            'disc_cabang'       => trim($data['disc_cabang']),
                            'disc_prinsipal'    => trim($data['disc_prinsipal']),
                            'disc_xtra'         => trim($data['disc_xtra']),
                            'disc_cod'          => trim($data['disc_cod']),
                            'rp_cabang'         => trim($data['rp_cabang']),
                            'rp_prinsipal'      => trim($data['rp_prinsipal']),
                            'rp_xtra'           => trim($data['rp_xtra']),
                            'rp_cod'            => trim($data['rp_cod']),
                            'nama_customer'     => trim($data['nama_customer']),
                            'prefix'            => trim($data['prefix']),
                            'alamat'            => trim($data['alamat']),
                            'kotaid'            => trim($data['kotaid']),
                            'segmentid'         => trim($data['segmentid']),
                            'typeid'            => trim($data['typeid']),
                            'ppn_percent_1'     => trim($data['ppn_percent_1']),
                            'ppn_percent_2'     => trim($data['ppn_percent_2']),
                            'ppn_percent_3'     => trim($data['ppn_percent_3']),
                            'created_at'        => $created_at
                        );
                        $proses = $this->db->insert('db_temp.temp_po_sds',$data);
                    }


                }else{
                    // return die(print_r(sqlsrv_errors(), true));
                    echo "aaa";
                }
        }else{
            // return die(print_r(sqlsrv_errors(), true));
            echo "bbb";
        }
    }

    public function insert_fi_ri_sds($tanggal)
    {
        $year = date('Y',strtotime($tanggal));
        // $year = '2024';
        $month = date('m',strtotime($tanggal));
        // $month = '12';
        $day = date('d',strtotime($tanggal));

        if ($day <= 7) 
        {
            $bulan_kemarin_unix=strtotime("-1 Months"); //1715089792 // mencari 1 bulan sebelumnya
			$month=date("m", $bulan_kemarin_unix);
        }else{
            $month = $month;
        }

        $sql = "
            DELETE FROM data$year.fi 
            WHERE CONCAT(KODE_COMP, NOCAB) in ('LL101', 'LL202', 'LL303', 'LL41A') and bulan = $month
        ";
        $proses_fi_del = $this->db->query($sql);
        if ($proses_fi_del) {
            echo "<br>delete fi sukses";
        } else {
            echo "<br>delete fi gagal";
        }

        $sql = "
            DELETE FROM data$year.ri 
            WHERE CONCAT(KODE_COMP, NOCAB) in ('LL101', 'LL202', 'LL303', 'LL41A') and bulan = $month
        ";
        $proses_ri_del = $this->db->query($sql);
        if ($proses_ri_del) {
            echo "<br>delete ri sukses";
        } else {
            echo "<br>delete ri gagal";
        }

        $query = "
            insert into data$year.fi
            select  '08', a.no_sales AS NODOKJDI, a.no_sales AS NODOKACU, DATE_FORMAT(a.tanggal, '%Y-%m-%d'),
                    'SALES0001' AS KODESALES,
                    CASE
                        WHEN a.nama_customer LIKE 'LULYANA%' THEN
                        'LL1' 
                        WHEN a.nama_customer LIKE 'PT. AKUR PRATAMA%' THEN
                        'LL2' 
                        WHEN a.nama_customer LIKE 'PT. SETIABUDHI JAYA ABADI%' THEN
                        'LL3' 
                        WHEN a.nama_customer LIKE 'JAYA SENTOSA FARMA' THEN
                        'LL4' 
                    END AS KODE_COMP,
                    'BDG' AS KODE_KOTA,
                    CASE
                        WHEN a.nama_customer LIKE 'LULYANA%' THEN
                        'TKL' ELSE 'SML' 
                    END AS KODE_TYPE, '100001' AS KODE_LANG, '' AS KODERAYON, a.productid, b.supp, DAY ( a.tanggal ),
                    IF(LENGTH(MONTH(a.tanggal)) < 2, concat(0,MONTH(a.tanggal)), MONTH(a.tanggal)), YEAR ( a.tanggal ),
                    b.namaprod, b.kode_prc, a.qty_kecil AS banyak, a.jual AS harga, '0', a.biaya_jasa AS tot1, '0', a.nama_customer AS KETERANGAN,
                    '', '', '', '', '', '', '', '', '', '', '', a.nourut, 'PST', '', 'RT', '', '', '', '0', a.nama_customer, a.jual, '', '', '0', '0', '0', '0', '0', a.nama_customer,
                    CASE
                        WHEN a.nama_customer LIKE 'LULYANA%' THEN
                        '01' 
                        WHEN a.nama_customer LIKE 'PT. AKUR PRATAMA%' THEN
                        '02' 
                        WHEN a.nama_customer LIKE 'PT. SETIABUDHI JAYA ABADI%' THEN
                        '03' 
                        WHEN a.nama_customer LIKE 'JAYA SENTOSA FARMA' THEN
                        '1A' 
                    END AS nocab,
                    IF(LENGTH(MONTH(a.tanggal)) < 2, concat(0,MONTH(a.tanggal)), MONTH(a.tanggal)),
                    CASE
                        WHEN a.nama_customer LIKE 'LULYANA%' THEN
                        'LL101' 
                        WHEN a.nama_customer LIKE 'PT. AKUR PRATAMA%' THEN
                        'LL202' 
                        WHEN a.nama_customer LIKE 'PT. SETIABUDHI JAYA ABADI%' THEN
                        'LL303' 
                        WHEN a.nama_customer LIKE 'JAYA SENTOSA FARMA' THEN
                        'LL41A' 
                    END AS kode_alamat,'0', '0', a.qty_kecil, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 
                    CONCAT(11, b.supp), '0', '0', '0', '0', '0', '', '', '', ''
            FROM
            ( 
                select * 
                from db_temp.temp_po_sds 
                WHERE tipe_trans = 'S' AND YEAR(tanggal) = $year AND MONTH(tanggal) = $month 
            )a LEFT JOIN mpm.tabprod b ON a.productid = b.kodeprod
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        $proses_fi = $this->db->query($query);
        if ($proses_fi) {
            echo "<br>insert fi sukses";
        } else {
            echo "<br>insert fi gagal";
        }

        $query = "
            insert into data$year.ri
            select '08', a.no_sales AS NODOKJDI, a.no_sales AS NODOKACU, DATE_FORMAT(a.tanggal, '%Y-%m-%d'),
                    'SALES0001' AS KODESALES,
                    CASE
                        WHEN a.nama_customer LIKE 'LULYANA%' THEN
                        'LL1' 
                        WHEN a.nama_customer LIKE 'PT. AKUR PRATAMA%' THEN
                        'LL2' 
                        WHEN a.nama_customer LIKE 'PT. SETIABUDHI JAYA ABADI%' THEN
                        'LL3' 
                        WHEN a.nama_customer LIKE 'JAYA SENTOSA FARMA' THEN
                        'LL4' 
                    END AS KODE_COMP, 'BDG' AS KODE_KOTA,
                    CASE
                        WHEN a.nama_customer LIKE 'LULYANA%' THEN
                        'TKL' ELSE 'SML' 
                    END AS KODE_TYPE, '100001' AS KODE_LANG, '' AS KODERAYON, a.productid, b.supp,
                    DAY (a.tanggal), IF(LENGTH(MONTH(a.tanggal)) < 2, concat(0,MONTH(a.tanggal)), MONTH(a.tanggal)),
                    YEAR (a.tanggal), b.namaprod, b.kode_prc, if(a.qty_kecil = 0 , 0, concat('-',a.qty_kecil)) AS banyak, a.jual AS harga,
                    '0', if(a.biaya_jasa = 0, 0,concat('-',a.biaya_jasa)) AS tot1, '0', a.nama_customer AS KETERANGAN,
                    '', '', '', '', '', '', '', '', '', '', '', a.nourut, 'PST', '', 'RT', '', '', '', '0', a.nama_customer, a.jual,
                    '', '', '0', '0', '0', '0', a.nama_customer,
                    CASE
                        WHEN a.nama_customer LIKE 'LULYANA%' THEN
                        '01' 
                        WHEN a.nama_customer LIKE 'PT. AKUR PRATAMA%' THEN
                        '02' 
                        WHEN a.nama_customer LIKE 'PT. SETIABUDHI JAYA ABADI%' THEN
                        '03' 
                        WHEN a.nama_customer LIKE 'JAYA SENTOSA FARMA' THEN
                        '1A' 
                    END AS nocab,
                    IF(LENGTH(MONTH(a.tanggal)) < 2, concat(0,MONTH(a.tanggal)), MONTH(a.tanggal)),
                    CASE
                        WHEN a.nama_customer LIKE 'LULYANA%' THEN
                        'LL101' 
                        WHEN a.nama_customer LIKE 'PT. AKUR PRATAMA%' THEN
                        'LL202' 
                        WHEN a.nama_customer LIKE 'PT. SETIABUDHI JAYA ABADI%' THEN
                        'LL303' 
                        WHEN a.nama_customer LIKE 'JAYA SENTOSA FARMA' THEN
                        'LL41A' 
                    END AS kode_alamat, '0', '0', a.qty_kecil, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0',
                    CONCAT(11, b.supp), '0', '0', '0', '0', '0', '', '', '', ''           
            FROM
            ( 
                SELECT * 
                FROM db_temp.temp_po_sds WHERE tipe_trans = 'R' AND YEAR(tanggal) = $year AND MONTH(tanggal) = $month 
            )a LEFT JOIN mpm.tabprod b ON a.productid = b.kodeprod
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        $proses_ri = $this->db->query($query);
        
        if ($proses_ri) {
            echo "<br>insert ri sukses";
        } else {
            echo "<br>insert ri gagal";
        }
    }

    public function insert_tblang_sds($tanggal)
    {
        $year = date('Y',strtotime($tanggal));
        // $year = '2024';
        $month = date('m',strtotime($tanggal));
        $day = date('d',strtotime($tanggal));

        $sql = "
            DELETE FROM data$year.tblang
            WHERE concat(kode_comp, nocab) in ('LL101', 'LL202', 'LL303','LL41A')
        ";
        $proses_tblang_del = $this->db->query($sql);
        if ($proses_tblang_del) {
            echo "<br>delete tblang sukses";
        } else {
            echo "<br>delete tblang gagal";
        }

        $query = "
            insert into data$year.tblang
            select a.kode_comp, a.kode_kota, a.kode_type, trim(a.kode_lang), a.koderayon, a.nama_lang,
            a.namaarea as alamat1, '' as alamat2, '' as telp, 
            '' as kodepos,		
            '' as tgl,		
            '' as npwp,		
            '0' as bts_utang,		
            '0' as sales01,		
            '0' as sales02,		
            '0' as sales03,		
            '0' as sales04,		
            '0' as sales05,		
            '0' as sales06,		
            '0' as sales07,		
            '0' as sales08,		
            '0' as sales09,		
            '0' as sales10,		
            '0' as sales11,		
            '0' as sales12,		
            '0' as ket,		
            '0' as debit,		
            '0' as kredit,		
            a.kodesalur as kodesalur,		
            '0' as top,		
            'Y' as aktif,		
            '' as tgl_aktif,		
            'T' as ppn,		
            '0' as kode_lama,		
            '1' as jum_dok,		
            '0' as statjual,		
            '0' as limit1,		
            '' as tglnaktif,		
            '' as ALAMAT_WP,		
            '' as NILAI_PPN,		
            '' as NAMA_WP,		
            '' as NEWFLD,		
            a.nocab as nocab,		
            '' as kodelang_copy,		
            '' as id_provinsi,		
            '' as nama_provinsi,		
            '' as id_kota,		
            '' as nama_kota,		
            '' as id_kecamatan,		
            '' as nama_kecamatan,		
            '' as id_kelurahan,		
            '' as nama_kelurahan,		
            '' as credit_limit,		
            '' as tipe_bayar,		
            '' as phone,		
            '' AS last_updated,		
            '' as status_blacklist,		
            '' as status_payment,		
            '' as CUSTID,		
            '' as COMPID,		
            '' as LATITUDE,		
            '' as LONGITUDE,		
            '' as FOTO_DISP,		
            '' as FOTO_TOKO	
            from data$year.fi a 
            where concat(a.kode_comp, a.nocab) in ('LL101', 'LL202', 'LL303', 'LL41A')
            GROUP BY concat(a.kode_comp, a.kode_lang)

            UNION ALL

            select a.kode_comp, a.kode_kota, a.kode_type, trim(a.kode_lang), a.koderayon, a.nama_lang,
            a.namaarea as alamat1, '' as alamat2, '' as telp, 
            '' as kodepos,		
            '' as tgl,		
            '' as npwp,		
            '0' as bts_utang,		
            '0' as sales01,		
            '0' as sales02,		
            '0' as sales03,		
            '0' as sales04,		
            '0' as sales05,		
            '0' as sales06,		
            '0' as sales07,		
            '0' as sales08,		
            '0' as sales09,		
            '0' as sales10,		
            '0' as sales11,		
            '0' as sales12,		
            '0' as ket,		
            '0' as debit,		
            '0' as kredit,		
            a.kodesalur as kodesalur,		
            '0' as top,		
            'Y' as aktif,		
            '' as tgl_aktif,		
            'T' as ppn,		
            '0' as kode_lama,		
            '1' as jum_dok,		
            '0' as statjual,		
            '0' as limit1,		
            '' as tglnaktif,		
            '' as ALAMAT_WP,		
            '' as NILAI_PPN,		
            '' as NAMA_WP,		
            '' as NEWFLD,		
            a.nocab as nocab,		
            '' as kodelang_copy,		
            '' as id_provinsi,		
            '' as nama_provinsi,		
            '' as id_kota,		
            '' as nama_kota,		
            '' as id_kecamatan,		
            '' as nama_kecamatan,		
            '' as id_kelurahan,		
            '' as nama_kelurahan,		
            '' as credit_limit,		
            '' as tipe_bayar,		
            '' as phone,		
            '' AS last_updated,		
            '' as status_blacklist,		
            '' as status_payment,		
            '' as CUSTID,		
            '' as COMPID,		
            '' as LATITUDE,		
            '' as LONGITUDE,		
            '' as FOTO_DISP,		
            '' as FOTO_TOKO	
            from data$year.ri a 
            where concat(a.kode_comp, a.nocab) in ('LL101', 'LL202', 'LL303', 'LL41A')
            GROUP BY concat(a.kode_comp, a.kode_lang)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        $proses_tblang = $this->db->query($query);
        if ($proses_tblang) {
            echo "<br>insert tblang sukses";
        } else {
            echo "<br>insert tblang gagal";
        }
    }

    public function insert_tabsales_sds($tanggal)
    {
        $year = date('Y',strtotime($tanggal));
        // $year = '2024';
        $month = date('m',strtotime($tanggal));
        $day = date('d',strtotime($tanggal));
        
        $sql = "
            DELETE FROM data$year.tabsales
            WHERE kodesales = 'SALES0001'
        ";
        $proses_tabsales_del = $this->db->query($sql);
        if ($proses_tabsales_del) {
            echo "<br>delete tabsales sukses";
        } else {
            echo "<br>delete tabsales gagal";
        }

        $query = "
            insert into data$year.tabsales
            select *
            from 
            (
                select  a.kodesales, a.lampiran as namasales, '' AS koderayon, 'S' AS `status`, a.namaarea AS alamat1, 
                        a.namaarea AS alamat2, '' AS NO_TELP, '' AS KODEPOS, '' AS PROPINSI, '' AS DATA1, '' AS TAHAP,
                        '' AS FILEID, '' AS NAMA_DEPO, a.kode_kota, '' AS KODE_GDG, '' AS NAMA_GDG, 'Y' AS AKTIF, a.nocab
                from data$year.fi a 
                where concat(a.kode_comp, a.nocab) in ('LL101', 'LL202', 'LL303', 'LL41A')
                union all
                select  a.kodesales, a.lampiran as namasales, '' AS koderayon, 'S' AS `status`, a.namaarea AS alamat1,
                        a.namaarea AS alamat2, '' AS NO_TELP, '' AS KODEPOS, '' AS PROPINSI, '' AS DATA1, '' AS TAHAP,
                        '' AS FILEID, '' AS NAMA_DEPO, a.kode_kota, '' AS KODE_GDG, '' AS NAMA_GDG, 'Y' AS AKTIF, a.nocab
                from data$year.ri a 
                where concat(a.kode_comp, a.nocab) in ('LL101', 'LL202', 'LL303', 'LL41A')
            )a GROUP BY a.kodesales, a.nocab
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        $proses_tabsales = $this->db->query($query);
        if ($proses_tabsales) {
            echo "<br>insert tabsales sukses";
        } else {
            echo "<br>insert tabsales gagal";
        }
    }

    // ============================================================================

    public function get_tabcomp_by_kode_comp($kode_comp){
        // $tahun_now = date("Y");
        // $query = "
        //     select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.kode_comp, a.nocab, a.sub
        //     from mpm.tbl_tabcomp a INNER JOIN (
        //         select concat(a.kode_comp, a.nocab) as site_code
        //         from db_dp.t_dp a 
        //         where a.tahun = $tahun_now and a.`status` = 1
        //     )b on concat(a.kode_comp, a.nocab) = b.site_code
        //     where a.`status` = 1 and a.kode_comp = '$kode_comp'
        //     GROUP BY concat(a.kode_comp, a.nocab)
        // ";

        $query = "
            select a.site_code, a.branch_name, a.nama_comp, left(a.site_code,3) as kode_comp, right(a.site_code, 2), a.sub
            from site.master_site a
            where left(a.site_code,3) = '$kode_comp'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        // die;

        return $this->db->query($query);
    }

    public function get_tabcomp_by_sub($sub = ''){
        $tahun_now = date("Y");

        if ($sub) {
            $params_sub = "and a.sub = '$sub'";
        }else{
            $params_sub = "";
        }

        $query = "
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.kode_comp, a.nocab, a.sub
            from mpm.tbl_tabcomp a INNER JOIN (
                select concat(a.kode_comp, a.nocab) as site_code
                from db_dp.t_dp a 
                where a.tahun = $tahun_now and a.`status` = 1
            )b on concat(a.kode_comp, a.nocab) = b.site_code
            where a.`status` = 1 $params_sub
            GROUP BY concat(a.kode_comp, a.nocab)
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 

        return $this->db->query($query);
    }

    public function get_namasupp_by_supp($supp = ''){

        if ($supp) {
            $params_supp = "where a.supp = '$supp'";
        }else{
            $params_supp = "";
        }

        $query = "
            select *
            from mpm.tabsupp a
            $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 

        return $this->db->query($query);
    }

    public function get_username_by_id($id = ''){

        if ($id) {
            $params_id = "where a.id = $id";
        }else{
            $params_id = "";
        }

        $query = "
            select *
            from mpm.user a
            $params_id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 

        return $this->db->query($query);
    }

    public function get_tabcomp_by_site_code($site_code = ''){

        if ($site_code) {
            $params_site_code = "and concat(a.kode_comp, a.nocab) = '$site_code'";
        }else{
            $params_site_code = "";
        }

        $query = "
            select *
            from mpm.tbl_tabcomp a
            where a.status = 1
            $params_site_code
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 

        return $this->db->query($query);
    }

    public function get_tabcomp_by_site_code_and_sub($site_code, $sub){

        $query = "
            select *
            from mpm.tbl_tabcomp a
            where a.status = 1 and concat(a.kode_comp, a.nocab) = '$site_code' and a.sub = '$sub'
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

    public function get_product_by_kodeprod_n_supp($kodeprod, $supp){

        $query = "
            select *
            from mpm.tabprod a
            where a.kodeprod = '$kodeprod' and a.supp = '$supp'
        ";

        return $this->db->query($query);

    }

    public function get_temp_po_sds($created_at){
        $query = "
            select * 
            from db_temp.temp_po_sds a 
            where a.created_at = '$created_at'
        ";
        return $this->db->query($query);
    }

    public function insert_log_po_outstanding($data)
    {
        $this->db->insert("site.log_po_outstanding", $data);
        return $this->db->insert_id();
    }
    public function get_log_po_outstanding()
    {
        $query = "
            select a.*, b.username
            from site.log_po_outstanding a left join site.master_user b 
                on a.created_by = b.id
        ";
        return $this->db->query($query);
    }

    public function truncate_delete_po_outstanding($tahun)
    {
        $this->db->query("truncate db_po.t_temp_report_po_update");
        $this->db->query("truncate db_po.t_temp_do_po_outstanding");
        $this->db->query("truncate db_po.t_temp_do_po_outstanding_us");

        $this->db->query("delete from db_po.t_po_outstanding_deltomed where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_us where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_intrafood where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_marguna where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_jaya where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_strive where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_hni where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_mdj where year(tglpo) = $tahun");
        $this->db->query("delete from db_po.t_po_outstanding_all where year(tglpo) = $tahun");
    }

    public function insert_do_deltomed_by_tahun($tahun)
    {
        $insert_do_deltomed = "
        insert into db_po.t_temp_do_po_outstanding
        select 	a.nodo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo, 
                str_to_date(a.tgldo,'%d/%m/%Y') as tgldo,'$this->created_at','$this->user_id'
        from db_po.t_do_deltomed a
        where year(str_to_date(a.tgldo,'%d/%m/%Y')) >= $tahun
        ";
        return $this->db->query($insert_do_deltomed);
    }

    public function insert_do_us_by_tahun($tahun)
    {
        $sql_replace = "
        update db_po.t_do_us a
        set a.kodeprod = replace(a.kodeprod, '.','')
        where kodeprod like '%.'
        ";
        $proses_sql_replace = $this->db->query($sql_replace);

        // echo "<pre>";
        // print_r($sql_replace);
        // echo "</pre>";

        $insert_do_us = "
        insert into db_po.t_temp_do_po_outstanding_us
        select 	a.nodo, a.tgldo, a.kodeprod,a.nopo,
                if(b.satuan_box is null, a.qty_pemenuhan, a.qty_pemenuhan * b.satuan_box) as qty_pemenuhan,
                '$this->created_at', '$this->user_id'
        FROM
        (
            select 	a.nodo, a.tgldo,
                    a.kodeprod, sum(a.banyak) as qty_pemenuhan, a.nopo
            from 	db_po.t_do_us a
            where year(a.tgldo) >= $tahun
            GROUP BY a.nopo, a.kodeprod
        )a LEFT JOIN
        (
            select a.kodeprod, a.satuan_box, a.`status`
            from db_produk.t_product_po a
            where a.`status` = 1
        )b on a.kodeprod = b.kodeprod
        ";
        return $this->db->query($insert_do_us);

        // echo "<pre>";
        // print_r($insert_do_us);
        // echo "</pre>";
    }

    public function insert_po_by_tahun($tahun)
    {
        $insert_po_nasional = "
        INSERT INTO db_po.t_temp_report_po_update
        select 	'', a.id, b.id as id_po_detail,a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, a.tglpesan,
                a.created, a.modified, a.created_by, a.modified_by, a.tipe, a.`open`, a.open_by, a.open_date,
                a.company, a.npwp, a.email, a.alamat, a.ambil, a.note, a.note_acc, a.`status`, a.status_approval,
                a.alasan_approval, a.po_ref, a.`lock`, a.kode_alamat, b.kodeprod, b.namaprod, 
                b.banyak, b.banyak_karton, b.`backup`, b.harga, b.kode_prc, b.berat, b.volume, b.stock_akhir, 
                b.rata, b.git, b.doi, b.status_terima, b.tanggal_terima, b.tanggal_terima_created_date, a.userid, a.deleted,297,'$tgl'
        from 	mpm.po a INNER JOIN 
        (
                select a.*
                from mpm.po_detail a
                where a.deleted = 0
        )b on a.id = b.id_ref 
        where	 a.deleted = 0 and b.deleted = 0 and
        left(a.nopo,4) <> '/MPM' and 
        a.nopo not like '%batal%' and year(a.tglpo) >= $tahun 
        ";
        return $this->db->query($insert_po_nasional);
        // echo "<pre>";
        // print_r($insert_po_nasional);
        // echo "</pre>";

    }

    // public function insert_po_outstanding_deltomed_old($tahun)
    // {
    //     $insert_po_outstanding_deltomed = "
    //     insert into db_po.t_po_outstanding_deltomed
    //     select 	a.branch_name, a.nama_comp,a.company, a.tglpo, a.nopo,a.tipe, a.kodeprod, a.namaprod, 
    //             a.banyak as qty_po, b.qty as qty_pemenuhan, a.harga,(a.banyak*a.harga) as value_po,(b.qty*a.harga) as value_pemenuhan,
    //             a.berat, a.volume, b.tgldo, b.nodo, (b.qty / a.banyak) * 100 as fulfilment,
    //             datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,
    //             a.po_ref, a.tanggal_terima, datediff(a.tanggal_terima,b.tgldo) as leadtime_proses_kirim,
    //             (a.banyak - b.qty) as outstanding_po,a.kode_alamat,'$this->created_at', '$this->user_id'
    //     from
    //     (
    //         select 	d.branch_name, d.nama_comp, a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
    //                 a.kodeprod, a.banyak, a.harga, e.namaprod,e.kodeprod_deltomed, 
    //                 a.po_ref, a.berat, a.status_terima, a.tanggal_terima, a.kode_alamat, a.volume
    //         from
    //         (
    //             select 	a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
    //                     a.kodeprod, a.banyak, a.harga, a.po_ref, (a.berat * a.banyak_karton) as berat, a.status_terima, a.tanggal_terima, a.kode_alamat, a.volume
    //             from 	db_po.t_temp_report_po_update a  
    //             where   a.supp ='001' and year(a.tglpo) in ($tahun)                        
    //         )a LEFT JOIN
    //         (
    //             select a.id, a.username
    //             from mpm.`user` a
    //         )c on a.userid = c.id LEFT JOIN
    //         (
    //             select a.kode,a.branch_name,a.nama_comp,a.kode_comp
    //             from
    //             (
    //                 select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
    //                 from mpm.tbl_tabcomp a
    //                 where a.`status` = 1
    //                 GROUP BY kode
    //             )a INNER JOIN
    //             (
    //                 select concat(a.kode_comp,a.nocab) as kode
    //                 from db_dp.t_dp a
    //                 where tahun = $tahun and a.`status` = 1
    //             )b on a.kode = b.kode
    //             GROUP BY kode_comp
    //         )d on c.username = d.kode_comp LEFT JOIN
    //         (
    //             select  a.kodeprod, a.namaprod, a.kodeprod_deltomed
    //             from    mpm.tabprod a
    //             where   supp = 001
    //         )e on a.kodeprod = e.kodeprod
    //     )a LEFT JOIN 
    //     (
    //         select a.nodo, a.kodedp, a.company, a.kodeprod_deltomed, a.namaprod, a.qty, a.nopo, tgldo
    //         from db_po.t_temp_do_po_outstanding a
    //     )b on a.nopo = b.nopo and a.kodeprod_deltomed = b.kodeprod_deltomed 
    //     order by nama_comp, kodeprod
    //     ";
    //     return $this->db->query($insert_po_outstanding_deltomed);
    //     // echo "<pre>";
    //     // print_r($insert_po_outstanding_deltomed);
    //     // echo "</pre>";

    //     // die;
    // }

    public function insert_po_outstanding_us_old($tahun)
    {
        $insert_po_outstanding_us = "
        insert into db_po.t_po_outstanding_us
        select 	a.company, a.branch_name, a.nama_comp, date(a.tglpo) as tglpo,a.nopo,a.tipe, a.kodeprod,  
                a.namaprod, a.banyak as qty_po, a.berat, a.volume,
                b.qty_pemenuhan,b.tgldo, b.nodo, (b.qty_pemenuhan / a.banyak * 100) as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,  
                a.tanggal_terima, datediff(a.tanggal_terima, b.tgldo) as leadtime_proses_kirim, 
                (a.banyak - b.qty_pemenuhan) as outstanding_po, a.kode_alamat,'$this->created_at', '$this->user_id'
        FROM 
        ( 
            select 	d.branch_name, d.nama_comp, 
                    a.nopo, a.tglpo, a.tipe, 
                    a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,a.po_ref,a.tanggal_terima, a.kode_alamat,a.berat,a.volume
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, 
                        a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, a.tanggal_terima, a.kode_alamat,a.berat,a.volume
                from 	db_po.t_temp_report_po_update a
                where    a.supp ='005' and year(a.tglpo) in ($tahun)
            )a LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN
        (
            select 	a.nodo, a.tgldo, a.kodeprod,a.nopo, qty_pemenuhan
            FROM db_po.t_temp_do_po_outstanding_us a	
        )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod
        order by nama_comp, kodeprod
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_us);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_us);
    }

    public function insert_po_outstanding_intrafood($tahun)
    {
        $insert_po_outstanding_intrafood = "
        insert into db_po.t_po_outstanding_intrafood
        select 	a.id_ref,b.branch_name, b.nama_comp, a.company, date(a.tglpo) as tglpo,
                a.nopo,a.tipe,a.kodeprod,a.namaprod,a.banyak,
                a.banyak_karton, if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) as pemenuhan_unit, 
                if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) as pemenuhan_karton, c.tanggal_kirim, c.tanggal_tiba, 
                (if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) / a.banyak * 100) as fulfilment_unit, 
                (if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) / a.banyak_karton * 100) as fulfilment_karton,
                datediff(c.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, c.status_closed, a.tanggal_terima,
                datediff(a.tanggal_terima, c.tanggal_kirim) as leadtime_proses_kirim, 
                (a.banyak - if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit)) as outstanding_po_unit,
                (a.banyak_karton - if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton)) as outstanding_po_karton, a.kode_alamat,'$this->created_at', '$this->user_id'
        from
        (
            select 	a.id, a.id_ref, a.id_po_detail, a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, 
                    a.tglpesan, a.created, a.tipe, a.company, a.alamat, a.kode_alamat, 
                    a.kodeprod, a.namaprod, a.banyak, a.banyak_karton, 
                    a.harga, a.kode_prc, a.berat, a.volume, a.stock_akhir, a.rata, a.git, a.doi, 
                    a.status_terima, a.tanggal_terima, a.userid, a.deleted
            from db_po.t_temp_report_po_update a
            where a.supp = 012
        )a LEFT JOIN
        (
            select	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from	mpm.tbl_tabcomp a
            where 	a.`status` = 1
            group by site_code
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select 	a.id_po, a.no_asn, a.batch_number, a.nodo, a.ed, 
                    a.kodeprod, sum(a.jumlah_unit) as pemenuhan_unit, sum(a.jumlah_karton) as pemenuhan_karton, a.status_pemenuhan,
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, a.tanggal_tiba, a.keterangan, a.status_closed, a.signature,
                    a.created_date, a.created_by, a.last_updated, a.last_updated_by
            from    mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )c on a.id_ref = c.id_po and a.kodeprod = c.kodeprod
        ";

        // echo "<pre>";
        // print_r($insert_po_outstanding_intrafood);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_intrafood);
    }

    public function insert_po_outstanding_marguna($tahun)
    {
        $insert_po_outstanding_marguna = "
        insert into db_po.t_po_outstanding_marguna
        select 	a.id_ref,b.branch_name, b.nama_comp, a.company, date(a.tglpo) as tglpo,
                a.nopo,a.tipe,a.kodeprod,a.namaprod,a.banyak,
                a.banyak_karton, if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) as pemenuhan_unit, 
                if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) as pemenuhan_karton, c.tanggal_kirim, c.tanggal_tiba, 
                (if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) / a.banyak * 100) as fulfilment_unit, 
                (if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) / a.banyak_karton * 100) as fulfilment_karton,
                datediff(c.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, c.status_closed, a.tanggal_terima,
                datediff(a.tanggal_terima, c.tanggal_kirim) as leadtime_proses_kirim, 
                (a.banyak - if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit)) as outstanding_po_unit,
                (a.banyak_karton - if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton)) as outstanding_po_karton, a.kode_alamat,'$this->created_at', '$this->user_id'
        from
        (
            select 	a.id, a.id_ref, a.id_po_detail, a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, 
                    a.tglpesan, a.created, a.tipe, a.company, a.alamat, a.kode_alamat, 
                    a.kodeprod, a.namaprod, a.banyak, a.banyak_karton, 
                    a.harga, a.kode_prc, a.berat, a.volume, a.stock_akhir, a.rata, a.git, a.doi, 
                    a.status_terima, a.tanggal_terima, a.userid, a.deleted
            from db_po.t_temp_report_po_update a
            where a.supp = 002
        )a LEFT JOIN
        (
            select	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from	mpm.tbl_tabcomp a
            where 	a.`status` = 1
            group by site_code
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select 	a.id_po, a.no_asn, a.batch_number, a.nodo, a.ed, 
                    a.kodeprod, sum(a.jumlah_unit) as pemenuhan_unit, sum(a.jumlah_karton) as pemenuhan_karton, a.status_pemenuhan,
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, a.tanggal_tiba, a.keterangan, a.status_closed, a.signature,
                    a.created_date, a.created_by, a.last_updated, a.last_updated_by
            from    mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )c on a.id_ref = c.id_po and a.kodeprod = c.kodeprod
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_marguna);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_marguna);
    }

    public function insert_po_outstanding_jaya($tahun)
    {
        $insert_po_outstanding_jaya_agung = "
        insert into db_po.t_po_outstanding_jaya
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$this->created_at', '$this->user_id'
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=004 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_jaya_agung);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_jaya_agung);

    }

    public function insert_po_outstanding_strive($tahun)
    {
        $insert_po_outstanding_strive = "
        insert into db_po.t_po_outstanding_strive
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$this->created_at', '$this->user_id'
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=013 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_strive);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_strive);
    }

    public function insert_po_outstanding_hni($tahun)
    {
        $insert_po_outstanding_hni = "
        insert into db_po.t_po_outstanding_hni
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$this->created_at', '$this->user_id'
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=014 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_hni);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_hni);
    }

    public function insert_po_outstanding_mdj($tahun)
    {
        $insert_po_outstanding_mdj = "
        insert into db_po.t_po_outstanding_mdj
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$this->created_at', '$this->user_id'
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=015 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        // echo "<pre>";
        // print_r($insert_po_outstanding_mdj);
        // echo "</pre>";
        return $this->db->query($insert_po_outstanding_mdj);
    }

    public function update_lock_po($data, $id_menu)
    {
        $this->db->where("id_menu", $id_menu);
        $this->db->update("db_temp.t_traffic", $data);
        return $this->db->affected_rows();
    }

    public function update_log_po_outstanding($data, $id_log)
    {
        $this->db->where("id", $id_log);
        $this->db->update("site.log_po_outstanding", $data);
        return $this->db->affected_rows();
    }    

    public function get_penta_sales($token, $tahun, $bulan)
    {
        $url_penta = getenv('PENTA_API').'list/sales/'.$tahun.'/'.$bulan;
        $curl = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($curl, CURLOPT_URL, $url_penta);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $array_response = json_decode($result, true);

        if (isset($array_response['data'])) 
        {

            $signature = 'penta-sales-' . rand() . md5($this->created_at) . date('Ymd');

            $data = [
                "created_at" => $this->created_at,
                "created_by" => $this->userid,
                "token" => $token,
                "tahun" => $tahun,
                "bulan" => $bulan,
                "signature" => $signature
            ];

            $this->db->insert('site.penta_log_sales', $data);
            $id_log = $this->db->insert_id();

            foreach ($array_response['data'] as $key) 
            {
                $bulan = $key["bulan"];
                $tahun = $key["tahun"];
                $principal_id = $key["principal_id"];
                $area_id = $key["area_id"];
                $nama_area = $key["nama_area"];
                $tanggal_invoice = $key["tanggal_invoice"];
                $nomor_invoice = $key["nomor_invoice"];
                $nomor_sales_order = $key["nomor_sales_order"];
                $customer_po_number = $key["customer_po_number"];
                $kode_outlet = $key["kode_outlet"];
                $kode_outlet_lama = $key["kode_outlet_lama"];
                $nama_outlet = $key["nama_outlet"];
                $category_produk = $key["category_produk"];
                $sales_order_line = $key["sales_order_line"];
                $kode_produk = $key["kode_produk"];
                $kode_produk_lama = $key["kode_produk_lama"];
                $inventory_item_id = $key["inventory_item_id"];
                $item_id_vend = $key["item_id_vend"];
                $id_item_sapora = $key["id_item_sapora"];
                $category_product_principal = $key["category_product_principal"];
                $nama_produk = $key["nama_produk"];
                $qty = $key["qty"];
                $uom = $key["uom"];
                $price = $key["price"];
                $total_disc = $key["total_disc"];
                $total_vat = $key["total_vat"];
                $total_gross = $key["total_gross"];
                $total_net = $key["total_net"];
                $bonus = $key["bonus"];
                $discount_value_distributor = $key["discount_value_distributor"];
                $discount_value_prinsipal = $key["discount_value_prinsipal"];
                $discount_value_extra = $key["discount_value_extra"];
                $discount_persen_distributor = $key["discount_persen_distributor"];
                $discount_persen_prinsipal = $key["discount_persen_prinsipal"];
                $discount_persen_extra = $key["discount_persen_extra"];
                $nomor_discount_distributor = $key["nomor_discount_distributor"];
                $nomor_discount_prinsipal = $key["nomor_discount_prinsipal"];
                $nomor_discount_extra = $key["nomor_discount_extra"];
                $type_data = $key["type_data"];
                $nama_sales = $key["nama_sales"];
                $batch = $key["batch"];
                $type_promo = $key["type_promo"];
                $keterangan_promo = $key["keterangan_promo"];

                $data = [
                    "id_log"=> $id_log,
                    "bulan" => $bulan,
                    "tahun" => $tahun,
                    "principal_id" => $principal_id,
                    "area_id" => $area_id,
                    "nama_area" => $nama_area,
                    "tanggal_invoice" => $tanggal_invoice,
                    "nomor_invoice" => $nomor_invoice,
                    "nomor_sales_order" => $nomor_sales_order,
                    "customer_po_number" => $customer_po_number,
                    "kode_outlet" => $kode_outlet,
                    "kode_outlet_lama" => $kode_outlet_lama,
                    "nama_outlet" => $nama_outlet,
                    "category_produk" => $category_produk,
                    "sales_order_line" => $sales_order_line,
                    "kode_produk" => $kode_produk,
                    "kode_produk_lama" => $kode_produk_lama,
                    "inventory_item_id" => $inventory_item_id,
                    "item_id_vend" => $item_id_vend,
                    "id_item_sapora" => $id_item_sapora,
                    "category_product_principal" => $category_product_principal,
                    "nama_produk" => $nama_produk,
                    "qty" => $qty,
                    "uom" => $uom,
                    "price" => $price,
                    "total_disc" => $total_disc,
                    "total_vat" => $total_vat,
                    "total_gross" => $total_gross,
                    "total_net" => $total_net,
                    "bonus" => $bonus,
                    "discount_value_distributor" => $discount_value_distributor,
                    "discount_value_prinsipal" => $discount_value_prinsipal,
                    "discount_value_extra" => $discount_value_extra,
                    "discount_persen_distributor" => $discount_persen_distributor,
                    "discount_persen_prinsipal" => $discount_persen_prinsipal,
                    "discount_persen_extra" => $discount_persen_extra,
                    "nomor_discount_distributor" => $nomor_discount_distributor,
                    "nomor_discount_prinsipal" => $nomor_discount_prinsipal,
                    "nomor_discount_extra" => $nomor_discount_extra,
                    "type_data" => $type_data,
                    "nama_sales" => $nama_sales,
                    "batch" => $batch,
                    "type_promo" => $type_promo,
                    "keterangan_promo" => $keterangan_promo,
                    "created_at" => $this->created_at,
                    "created_by" => $this->userid,
                    "signature" => $signature
                ];

                $this->db->insert('site.penta_sales_origin', $data);
            }
            
            return $id_log;
            // echo "success : " . $id_log;
            // die;

        }else{
            echo "error : " . $err;
            die;
        }
    }

    public function get_penta_stock($token, $tahun, $bulan)
    {
        $url_penta = getenv('PENTA_API').'list/stock/';
        $curl = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($curl, CURLOPT_URL, $url_penta);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $array_response = json_decode($result, true);

        if (isset($array_response['data'])) 
        {

            $signature = 'penta-stock-' . rand() . md5($this->created_at) . date('Ymd');

            $data = [
                "created_at" => $this->created_at,
                "created_by" => $this->userid,
                "token" => $token,
                "tahun" => $tahun,
                "bulan" => $bulan,
                "signature" => $signature
            ];

            $this->db->insert('site.penta_log_stock', $data);
            $id_log = $this->db->insert_id();

            foreach ($array_response['data'] as $key) 
            {
                $principal_name = $key["principal_name"];
                $area_id = $key["area_id"];
                $nama_area = $key["nama_area"];
                $inventory_item_id = $key["inventory_item_id"];
                $kode_produk = $key["kode_produk"];
                $item_id_vend = $key["item_id_vend"];
                $nama_produk = $key["nama_produk"];
                $qty = $key["qty"];
                $uom = $key["uom"];
                $batch = $key["batch"];
                $tanggal_penerimaan = $key["tanggal_penerimaan"];
                $update_terakhir = $key["update_terakhir"];
                $hna = $key["hna"];
                $expired_date = $key["expired_date"];

                $data = [
                    "id_log"=> $id_log,
                    "bulan" => $bulan,
                    "tahun" => $tahun,
                    "principal_name" => $principal_name,
                    "area_id" => $area_id,
                    "nama_area" => $nama_area,
                    "inventory_item_id" => $inventory_item_id,
                    "kode_produk" => $kode_produk,
                    "item_id_vend" => $item_id_vend,
                    "nama_produk" => $nama_produk,
                    "qty" => $qty,
                    "uom" => $uom,
                    "batch" => $batch,
                    "tanggal_penerimaan" => $tanggal_penerimaan,
                    "update_terakhir" => $update_terakhir,
                    "hna" => $hna,
                    "expired_date" => $expired_date,
                    "created_at" => $this->created_at,
                    "created_by" => $this->userid,
                    "signature" => $signature
                ];

                $this->db->insert('site.penta_stock_origin', $data);
            }
            
            return $id_log;

            // $this->session->set_flashdata("pesan_success", "Penarikan data berhasil. Silahkan tarik data anda");
            // redirect('penta/log_sales', 'refresh');

        }else{
            echo "error : " . $err;
            die;
        }
    }

    public function cek_product_penta($id_log, $bulan)
    {
        $query = "
            select b.*, a.id_log, a.bulan, a.tahun, a.inventory_item_id, a.item_id_vend, a.kode_produk, a.nama_produk, a.hna, a.uom
            from site.penta_stock_origin a
            left join (
                select a.kode_produk as kode_produk_master, a.inventory_item_id as inventory_item_id_master, a.item_id_vend as item_id_vend_master, a.nama_produk as nama_produk_master
                from site.penta_master_produk a
            )b on a.kode_produk = b.kode_produk_master
            where a.id_log = '$id_log' and a.bulan = '$bulan'
            group by a.kode_produk
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>"; 
        $proses =  $this->db->query($query);

        foreach ($proses->result() as $a) {
            $kode_produk = $a->kode_produk;
            $inventory_item_id = $a->inventory_item_id;
            $item_id_vend = $a->item_id_vend;
            $nama_produk = $a->nama_produk;
            $hna = $a->hna;
            $uom = $a->uom;

            $kode_produk_master = $a->kode_produk_master;
            $inventory_item_id_master = $a->inventory_item_id_master;
            $item_id_vend_master = $a->item_id_vend_master;
            $nama_produk_master = $a->nama_produk_master;

            if ($kode_produk == $kode_produk_master && $inventory_item_id == $inventory_item_id_master && $item_id_vend == $item_id_vend_master && $nama_produk == $nama_produk_master) 
            {
                // echo "data sama";
            }else{
                // echo "data tidak sama";
                //Lakukan insert
                $data = [
                    "kode_produk" => $kode_produk,
                    "inventory_item_id" => $inventory_item_id,
                    "item_id_vend" => $item_id_vend,
                    "nama_produk" => $nama_produk,
                    "hna" => $hna,
                    "uom" => $uom
                ];

                $this->db->insert('site.penta_master_produk', $data);
            }
        }

    }

    public function master_product_by_kodeprod($kodeprod = "")
    {
        if ($kodeprod) {
            $params_kodeprod = "and a.kodeprod in ('$kodeprod')";
        }else{
            $params_kodeprod = "";
        }

        $query = "
            select * 
            from site.master_product a 
            where a.active = 1 $params_kodeprod
        ";

        return $this->db->query($query);

    }

    // public function transfer_data_absensi()
    // {
    //     $servername = "backup.muliaputramandiri.com:88";
    //     $username = "mpm";
    //     $password = "mpm123!@#";

    //     // Create connection
    //     $conn = new mysqli($servername, $username, $password);
    //     var_dump($conn);die;
    //     // Check connection
    //     if ($conn->connect_error) {
    //     die("Connection failed: " . $conn->connect_error);
    //     }
    //     $truncate_absensi   = $this->db->truncate('site.absensi_master');
    //     $truncate_karyawan  = $this->db->truncate('site.absensi_karyawan');

    //     $sql_absensi = "SELECT * FROM tarikfp.absensi";
    //     $data_absensi = $conn->query($sql_absensi);

    //     foreach ($data_absensi as $key) {
    //         $this->db->insert('site.absensi_master', $key);
    //     }

    //     $sql_karyawan = "SELECT * FROM tarikfp.karyawan";
    //     $data_karyawan = $conn->query($sql_karyawan);

    //     foreach ($data_karyawan as $key) {
    //         $this->db->insert('site.absensi_karyawan', $key);
    //     }
    // }

    // public function transfer_data_absensi_transaksi($tahun, $bulan, $jam_kerja)
    // {
    //     $query = "
    //         SELECT a.userid, a.userid_absensi, a.tanggal, a.waktu_masuk, b.waktu_keluar, TIMEDIFF(b.waktu_keluar, a.waktu_masuk) as total_jam_kerja
    //         FROM
    //         (
    //             SELECT b.id as userid, a.userid as userid_absensi, DATE(a.waktu) as tanggal, MAX(time(a.waktu)) as waktu_masuk
    //             FROM site.absensi_master a
    //             left join mpm.`user` b on a.userid = b.userid_absensi
    //             where year(a.waktu) = '$tahun' and month(a.waktu) = '$bulan' AND a.status = 0
    //             GROUP BY a.userid, DATE(a.waktu)
    //         )a 
    //         LEFT JOIN
    //         (
    //             SELECT b.id as userid, a.userid as userid_absensi, DATE(a.waktu) as tanggal, MAX(time(a.waktu)) as waktu_keluar
    //             FROM site.absensi_master a
    //             left join mpm.`user` b on a.userid = b.userid_absensi
    //             where year(a.waktu) = '$tahun' and month(a.waktu) = '$bulan' AND a.status in (1, 5)
    //             GROUP BY a.userid, DATE(a.waktu)
    //         )b on a.userid = b.userid and a.tanggal = b.tanggal
    //         LEFT JOIN( 
    //             SELECT *
    //             FROM site.absensi_transaksi a
	// 		)c on a.userid_absensi = c.userid_absensi and a.tanggal = c.tanggal
    //         where c.tanggal is null
    //     ";
        
    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";

    //     $data = $this->db->query($query);

    //     foreach ($data->result() as $key) {
    //         $absensi_master = [
    //             'userid'            => $key->userid,
    //             'userid_absensi'    => $key->userid_absensi,
    //             'tanggal'           => $key->tanggal,
    //             'jam_masuk_kantor'  => $jam_kerja->row()->jam_masuk,
    //             'jam_keluar_kantor' => $jam_kerja->row()->jam_keluar,
    //             'actual_masuk'      => $key->waktu_masuk,
    //             'actual_keluar'     => $key->waktu_keluar,
    //             'total_jam_kerja'   => $key->waktu_keluar == null ? 0 : date("H", strtotime($key->total_jam_kerja)),
    //             'flag_terlambat'    => date("H:i", strtotime($key->waktu_masuk)) > date("H:i", strtotime($jam_kerja->row()->jam_masuk)) ? 1 : 0,
    //             'signature'         => 'absensi-' . md5($key->userid.$tahun.$bulan)
    //         ];

    //         $this->db->insert('site.absensi_transaksi', $absensi_master);
    //     }
    // }

    // public function transfer_data_absensi_shd($tanggal, $tahun, $bulan)
    // {
    //     $postfields = array(
    //         "sign" => "sphere154",
    //         "start_period" => $tahun.'-'.$bulan."-01",
    //         "end_period" => $tahun.'-'.$bulan.'-'.$tanggal
    //     );
    //     // var_dump($postfields);die;
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //     CURLOPT_URL => 'https://admin-hrs.onevour.com/api/public/report_attendance_mpm',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS => json_encode($postfields),
    //     CURLOPT_HTTPHEADER => array(
    //         'Content-Type: application/json'
    //     ),
    //     ));

    //     $response = json_decode(curl_exec($curl), true);
    //     curl_close($curl);

    //     $this->insert_absensi_karyawan_shd($response);
    //     $this->insert_temp_absensi_shd($response);
    //     $this->insert_absensi_master_shd($tahun, $bulan);
    // }

    // public function insert_absensi_karyawan_shd($response)
    // {
    //     for ($i=0; $i < count($response['data']) ; $i++) 
    //     { 
    //         $username_employee = $response['data'][$i]["username_employee"];
    //         $employee_name     = $response['data'][$i]["employee_name"];

    //         $query = "
    //             select a.username_employee
    //             from site.absensi_karyawan_shd a
    //             where a.username_employee = '$username_employee'
    //         ";
            
    //         $proses = $this->db->query($query);
    //         // print_r($proses->result_array());
    //         // die;
            
    //         if ($proses->num_rows() == 0) {
    //             $data = [
    //                 'username_employee'     => $username_employee,
    //                 'employee_name'         => $employee_name,
    //                 'created_at'            => $this->model_outlet_transaksi->timezone(),
    //                 'created_by'            => $this->session->userdata('id'),
    //             ];
    //             $this->db->insert('site.absensi_karyawan_shd', $data);

    //         }   
    //     }
    //     $this->update_absensi_karyawan_shd();
    // }

    // function update_absensi_karyawan_shd()
    // {
    //     $query = "
    //         update site.absensi_karyawan_shd a
    //         LEFT JOIN site.master_user b
    //         on b.username = a.username_employee or a.employee_name = b.name
    //         set a.userid_web = b.id
    //         WHERE a.userid_web is null and b.kode_company = 000
    //     ";

    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";
    //     // die;
    //     return $this->db->query($query);
    // }

    public function transfer_data_absensi($tahun, $bulan)
    {
        $servername = "backup.muliaputramandiri.com:3333";
        $username = "mpm";
        $password = "mpm123!@#";
        // Create connection
        $conn = new mysqli($servername, $username, $password);
        // var_dump($conn);die;
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        $truncate_absensi   = $this->db->truncate('site.absensi_master');
        $truncate_karyawan  = $this->db->truncate('site.absensi_karyawan');
        $truncate_absensi_full_weekend   = $this->db->truncate('site.absensi_master_full_weekend');

        $sql_absensi = "SELECT * FROM tarikfp.absensi";
        $data_absensi = $conn->query($sql_absensi);

        foreach ($data_absensi as $key) {
            $this->db->insert('site.absensi_master', $key);
        }

        $sql_karyawan = "SELECT * FROM tarikfp.karyawan";
        $data_karyawan = $conn->query($sql_karyawan);

        foreach ($data_karyawan as $key) {
            $this->db->insert('site.absensi_karyawan', $key);
        }

        // get seluruh userid yang melakukan fingerprint
        $query = "        
            select a.*
            from site.absensi_master a 
            where year(a.waktu) = $tahun and month(a.waktu) = $bulan and a.userid not in (0)
            GROUP BY a.userid
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $data = $this->db->query($query);

        foreach ($data->result() as $key) {
            $userid = $key->userid;
            
            $query = "
                insert into site.absensi_master_full_weekend
                select '', a.tanggal, hari as status_hari, $userid, b.waktu, b.verifikasi, b.status, b.dikirim
                from site.absensi_master_tanggal a left join 
                (
                    select *
                    from site.absensi_master a
                    where year(a.waktu) = $tahun and month(a.waktu) = $bulan and a.userid = $userid
                )b on a.tanggal = date(b.waktu) 
                where tahun = $tahun and month(a.tanggal) = $bulan
            ";
            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
            $this->db->query($query);
        }

    }

    public function transfer_data_absensi_transaksi($tahun, $bulan, $jam_kerja)
    {   
        $this->db->query("truncate site.absensi_master_full_weekend_temp");

        $query = "
            INSERT INTO site.absensi_master_full_weekend_temp
            SELECT  '', b.id as userid, a.userid as userid_absensi, DATE(a.tanggal) as tanggal, 
                    min(if(time(a.waktu) < '12:00:00', time(a.waktu), null)) as waktu_masuk,
                    max(if(time(a.waktu) > '12:01:00', time(a.waktu), null)) as waktu_keluar,
                    TIMEDIFF(max(if(time(a.waktu) > '12:01:00', time(a.waktu), null)),
                    min(if(time(a.waktu) < '12:00:00', time(a.waktu), null))) as total_jam_kerja,
                    a.status_hari
            FROM    site.absensi_master_full_weekend a left join mpm.`user` b 
                        on a.userid = b.userid_absensi
            where 	year(a.tanggal) = '$tahun' and month(a.tanggal) = '$bulan'  
            GROUP BY date(a.tanggal), a.userid

        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $data = $this->db->query($query);
        $get_data = "
            select *
            from site.absensi_master_full_weekend_temp a
            where a.userid_web is not null 
        ";

        $data = $this->db->query($get_data);

        // echo "<pre>";
        // print_r($get_data);
        // echo "</pre>";

        // die;

        foreach ($data->result() as $key) {
            $userid_web = $key->userid_web;
            $userid_absensi = $key->userid_absensi;
            $tanggal = $key->tanggal;
            $waktu_masuk = $key->waktu_masuk;
            $waktu_keluar = $key->waktu_keluar;
            $total_jam_kerja = $key->total_jam_kerja;
            $status_hari = $key->status_hari;

            // echo "userid_web : ".$userid_web."<br>";
            // echo "userid_absensi : ".$userid_absensi."<br>";
            // echo "tanggal : ".$tanggal."<br>";
            // echo "waktu_masuk : ".$waktu_masuk."<br>";
            // echo "waktu_keluar : ".$waktu_keluar."<br>";
            // echo "total_jam_kerja : ".$total_jam_kerja."<br>";
            // echo "status_hari : ".$status_hari."<br>";
            // echo "<br>";

            $query_cek = "
                select *
                from site.absensi_transaksi a 
                where a.userid = $userid_web and a.tanggal = '$tanggal'
            ";

            echo "<pre>";
            print_r($query_cek);
            echo "</pre>";

            $cek_data = $this->db->query($query_cek);

            if ($cek_data->num_rows() > 0) {
                // update data
                echo "update_data : ".$tanggal."<br>";
                echo "userid_web : ".$userid_web."<br>";
                echo "userid_absensi : ".$userid_absensi."<br>";
                echo "waktu_masuk : ".$waktu_masuk."<br>";
                echo "waktu_keluar : ".$waktu_keluar."<br>";
                
                // update actual_masuk
                $data = [
                    "actual_masuk" => $waktu_masuk,
                    "flag_terlambat" => date("H:i", strtotime($waktu_masuk)) > date("H:i", strtotime($jam_kerja->row()->jam_masuk)) ? 1 : 0,
                    // "actual_keluar" => $waktu_keluar,
                    "total_jam_kerja" => $total_jam_kerja,
                    "status_hari" => $status_hari
                ];

                $where = "`userid` = '$userid_web' AND `tanggal` = '$tanggal' AND (`actual_masuk` is null)";
                $this->db->where($where);
                // $this->db->where("userid", $userid_web);
                // $this->db->where("tanggal", $tanggal);
                // $this->db->where("actual_masuk is null", null, false);
                // // $this->db->or_where("actual_keluar is null", null, false);
                $this->db->update("site.absensi_transaksi", $data);

                print_r($this->db->last_query());

                // update actual_keluar
                $data = [
                    "actual_keluar" => $waktu_keluar,
                    "total_jam_kerja" => $total_jam_kerja,
                    "status_hari" => $status_hari
                ];

                $where = "`userid` = '$userid_web' AND `tanggal` = '$tanggal' AND (`actual_keluar` is null)";
                $this->db->where($where);
                $this->db->update("site.absensi_transaksi", $data);
                print_r($this->db->last_query());

            } else {
                // insert data
                echo "insert_data : ".$tanggal."<br>";
                echo "waktu_masuk : ".date("H:i", strtotime($waktu_masuk));
                echo '<br>';
                echo "jam_masuk_kantor : ".$jam_kerja->row()->jam_masuk;
                echo '<br>';
                $data = [
                    "userid" => $userid_web,
                    "userid_absensi" => $userid_absensi,
                    "tanggal" => $tanggal,
                    "actual_masuk" => $waktu_masuk,
                    "actual_keluar" => $waktu_keluar,
                    "total_jam_kerja" => $total_jam_kerja,
                    "status_hari" => $status_hari,
                    'jam_masuk_kantor'  => $jam_kerja->row()->jam_masuk,
                    'jam_keluar_kantor' => $jam_kerja->row()->jam_keluar,
                    'flag_terlambat'    => date("H:i", strtotime($waktu_masuk)) > date("H:i", strtotime($jam_kerja->row()->jam_masuk)) ? 1 : 0,
                    'signature'         => 'absensi-' . md5($userid_web.$tahun.$bulan),
                ];

                var_dump($data);
                $this->db->insert("site.absensi_transaksi", $data);
            }

        }
    }

    public function transfer_data_absensi_shd($end_tanggal, $tahun, $bulan)
    {
        $postfields = array(
            "sign" => "sphere154",
            "start_period" => $tahun.'-'.$bulan."-01",
            "end_period" => $tahun.'-'.$bulan.'-'.$end_tanggal
        );
        // var_dump($postfields);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://admin-hrs.onevour.com/api/public/report_attendance_mpm',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($postfields),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        $this->insert_absensi_karyawan_shd($response); 
        $this->insert_temp_absensi_shd($response);
        // $this->insert_absensi_master_shd($tahun, $bulan); // ??
        $this->konsolidasi_shade_vs_mpm($tahun, $bulan); // new
    }

    public function insert_absensi_karyawan_shd($response)
    {
        for ($i=0; $i < count($response['data']) ; $i++) 
        { 
            $username_employee = $response['data'][$i]["username_employee"];
            $employee_name     = $response['data'][$i]["employee_name"];

            $query = "
                select a.username_employee
                from site.absensi_karyawan_shd a
                where a.username_employee = '$username_employee'
            ";
            $proses = $this->db->query($query);

            if ($proses->num_rows() == 0) {
                $data = [
                    'username_employee'     => $username_employee,
                    'employee_name'         => $employee_name,
                    'created_at'            => $this->model_outlet_transaksi->timezone(),
                    'created_by'            => $this->session->userdata('id'),
                ];
                $this->db->insert('site.absensi_karyawan_shd', $data);

            }   
        }
        $this->update_absensi_karyawan_shd();
    }

    function update_absensi_karyawan_shd()
    {
        $query = "
            update site.absensi_karyawan_shd a
            LEFT JOIN site.master_user b
            on b.username = a.username_employee or a.employee_name = b.name
            set a.userid_web = b.id
            WHERE a.userid_web is null and b.kode_company = 000
        ";
        return $this->db->query($query);
    }

    public function insert_temp_absensi_shd($response)
    {
        $truncate_absensi   = $this->db->truncate('site.absensi_temp_master_shd');
        // echo 'A';die;
        for ($i=0; $i < count($response['data']) ; $i++)
        { 
            $data = [
                'username_employee'     => $response['data'][$i]["username_employee"],
                'employee_name'         => $response['data'][$i]["employee_name"],
                'attendance_date'       => $response['data'][$i]["attendance_date"],
                'company_name'          => $response['data'][$i]["company_name"],
                'site_id'               => $response['data'][$i]["site_id"],
                'site_name'             => $response['data'][$i]["site_name"],
                'jabatan_name'          => $response['data'][$i]["jabatan_name"],
                'checkin_time'          => $response['data'][$i]["checkin_time"],
                'checkout_time'         => $response['data'][$i]["checkout_time"],
                'latitude_checkin'      => $response['data'][$i]["latitude_checkin"],
                'longitude_checkin'     => $response['data'][$i]["longitude_checkin"],
                'latitude_checkout'     => $response['data'][$i]["latitude_checkout"],
                'longitude_checkout'    => $response['data'][$i]["longitude_checkout"],
                'location_checkin'      => $response['data'][$i]["location_checkin"],
                'location_checkout'     => $response['data'][$i]["location_checkout"],
                'created_at'            => $this->model_outlet_transaksi->timezone(),
                'created_by'            => $this->session->userdata('id'),
            ];

            $this->db->insert('site.absensi_temp_master_shd', $data);
        }
    }

    public function konsolidasi_shade_vs_mpm($tahun, $bulan)
    {
        // echo "tahun : ".$tahun;
        // echo "bulan : ".$bulan;

        $query = "
            select  a.username_employee, a.attendance_date, a.checkin_time, a.checkout_time,
                    a.latitude_checkin, a.longitude_checkin, a.latitude_checkout, a.longitude_checkout,
                    a.created_at, a.created_by, a.employee_name, a.company_name,a.site_id, a.site_name, a.jabatan_name,
                    a.location_checkin, a.location_checkout,
                    b.username_employee as username_employee_master,
                    b.attendance_date as  attendance_date_master,
                    b.checkin_time as checkin_time_master, 
                    b.checkout_time as checkout_time_master
            from site.absensi_temp_master_shd a left join (
                select *
                from site.absensi_master_shd a 
                where month(a.attendance_date) = $bulan and year(a.attendance_date) = $tahun
            )b on a.username_employee = b.username_employee and a.attendance_date = b.attendance_date 
        ";

        $proses = $this->db->query($query);
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        foreach ($proses->result() as $a) {
            $username_employee = $a->username_employee;
            $attendance_date   = $a->attendance_date;
            $checkin_time      = $a->checkin_time;
            $checkout_time     = $a->checkout_time;
            $latitude_checkin   = $a->latitude_checkin;
            $longitude_checkin  = $a->longitude_checkin;
            $latitude_checkout  = $a->latitude_checkout;
            $longitude_checkout = $a->longitude_checkout;
            $location_checkin   = $a->location_checkin;
            $location_checkout  = $a->location_checkout;
            $created_at        = $a->created_at;
            $created_by        = $a->created_by;
            $employee_name     = $a->employee_name;
            $company_name      = $a->company_name;
            $site_id           = $a->site_id;
            $site_name         = $a->site_name;
            $jabatan_name      = $a->jabatan_name;

            $username_employee_master = $a->username_employee_master;
            $attendance_date_master   = $a->attendance_date_master;
            $checkin_time_master      = $a->checkin_time_master;
            $checkout_time_master     = $a->checkout_time_master;


            if ($username_employee == $username_employee_master && $attendance_date == $attendance_date_master && $checkin_time == $checkin_time_master && $checkout_time == $checkout_time_master) {
                // echo "data sama";
            }else{
                // echo "tidak sama";
                
                if ($username_employee == $username_employee_master && $attendance_date == $attendance_date_master) {
                    // lakukan update
                    $data = [
                        "checkin_time"   => $checkin_time,
                        "checkout_time"  => $checkout_time,
                        "latitude_checkin"   => $latitude_checkin,
                        "longitude_checkin"  => $longitude_checkin,
                        "latitude_checkout"  => $latitude_checkout,
                        "longitude_checkout" => $longitude_checkout,
                        "created_at"     => $created_at,
                        "created_by"     => $created_by
                    ];

                    $this->db->where('username_employee', $username_employee);
                    $this->db->where('attendance_date', $attendance_date);
                    $this->db->update('site.absensi_master_shd', $data);


                }else{
                    // lakukan insert
                    $data = [
                        "username_employee" => $username_employee,
                        "checkin_time"   => $checkin_time,
                        "checkout_time"  => $checkout_time,
                        "latitude_checkin"   => $latitude_checkin,
                        "longitude_checkin"  => $longitude_checkin,
                        "latitude_checkout"  => $latitude_checkout,
                        "longitude_checkout" => $longitude_checkout,
                        "location_checkin"   => $location_checkin,
                        "location_checkout"  => $location_checkout,
                        "created_at"     => $created_at,
                        "created_by"     => $created_by,
                        "employee_name"     => $employee_name,
                        "company_name"   => $company_name,
                        "site_id"   => $site_id,
                        "site_name"   => $site_name,
                        "jabatan_name"   => $jabatan_name,
                        "attendance_date"   => $attendance_date
                    ];

                    $this->db->insert('site.absensi_master_shd', $data);
                }
            }
        }

        // get seluruh userid yang melakukan absen shd
        $query = "
            select *
            from site.absensi_master_shd a 
            where month(a.attendance_date) = $bulan and year(a.attendance_date) = $tahun
            GROUP BY a.username_employee
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        $data = $this->db->query($query);

        $truncate = $this->db->query("truncate site.absensi_master_shd_full_weekend");
        
        foreach ($data->result() as $key) {
            $username_employee = $key->username_employee;
            // echo "username_employee : ".$username_employee."<br>";

            $get_userid_web = "
                select *
                from site.absensi_karyawan_shd a
                where a.username_employee = '$username_employee'
            ";

            $userid_web = ($this->db->query($get_userid_web)->num_rows() > 0) ? $this->db->query($get_userid_web)->row()->userid_web : null;

            $query_insert = "
                insert into site.absensi_master_shd_full_weekend
                select  '', a.tanggal, a.hari as status_hari, '$username_employee', b.attendance_date, b.site_name, 
                        b.jabatan_name, b.checkin_time, b.checkout_time, b.latitude_checkin, b.longitude_checkin, 
                        latitude_checkout, longitude_checkout, b.created_at, b.created_by, $userid_web
                from site.absensi_master_tanggal a left join (
                    select a.*
                    from site.absensi_master_shd a
                    where month(a.attendance_date) = $bulan and year(a.attendance_date) = $tahun and a.username_employee ='$username_employee'
                )b on a.tanggal = date(b.attendance_date)
                where a.tahun = $tahun and month(a.tanggal) = $bulan
                order by date(a.tanggal)
            ";

            echo "<pre>";
            print_r($query_insert);
            echo "</pre>";

            $this->db->query($query_insert);
        }
    }

    // public function insert_absensi_master_shd($tahun, $bulan)
    // {
    //     $query = "
    //         insert into site.absensi_master_shd
    //         select a.*
    //         from site.absensi_temp_master_shd a
    //         LEFT JOIN( 
    //             SELECT *
    //             from site.absensi_master_shd a
    //             where year(a.attendance_date) = '$tahun' and month(a.attendance_date) = '$bulan'
    //         )b on a.attendance_date = b.attendance_date and a.username_employee = b.username_employee
    //         WHERE b.attendance_date is null
    //     ";

    //     return $this->db->query($query);

    // }

    public function transfer_data_absensi_transaksi_shd($tahun, $bulan, $jam_kerja)
    {

        $query = "
            select a.*, time(TIMEDIFF(a.checkout_time,a.checkin_time)) as total_jam_kerja
            from site.absensi_master_shd_full_weekend a 
        ";

        $proses = $this->db->query($query);
        foreach ($proses->result() as $key) {
            $tanggal = $key->tanggal;
            $status_hari = $key->status_hari;
            $username_employee = $key->username_employee;
            $attendance_date = $key->attendance_date;
            $checkin_time = $key->checkin_time;
            // echo "checkin_time : ".$checkin_time."<br>";
            $checkout_time = $key->checkout_time;
            $total_jam_kerja = $key->total_jam_kerja;
            $created_at = $key->created_at;
            $created_by = $key->created_by;
            $userid_web = $key->userid_web;

            $query_cek = "
                select *
                from site.absensi_transaksi a 
                where a.userid = $userid_web and a.tanggal = '$tanggal'
            ";

            echo "<pre>";
            print_r($query_cek);
            echo "</pre>";

            $cek_data = $this->db->query($query_cek);

            if ($cek_data->num_rows() > 0) {
                // update data
                echo "update_data : ".$tanggal."<br>";
                // update actual_masuk
                $data = [
                    "actual_masuk" => $checkin_time,
                    "flag_terlambat" => date("H:i", strtotime($checkin_time)) > date("H:i", strtotime($jam_kerja->row()->jam_masuk)) ? 1 : 0,
                    // "actual_keluar" => $checkout_time,
                    "total_jam_kerja" => $total_jam_kerja,
                    "status_hari" => $status_hari
                ];

                $where = "`userid` = '$userid_web' AND `tanggal` = '$tanggal' AND (`actual_masuk` is null)";
                $this->db->where($where);
                // $this->db->where("actual_masuk", null);
                // $this->db->where("userid", $userid_web);
                // $this->db->where("tanggal", $tanggal);
                $this->db->update("site.absensi_transaksi", $data);
                print_r($this->db->last_query());

                // update actual_keluar
                $data = [
                    // "actual_masuk" => $checkin_time,
                    "actual_keluar" => $checkout_time,
                    "total_jam_kerja" => $total_jam_kerja,
                    "status_hari" => $status_hari
                ];

                $where = "`userid` = '$userid_web' AND `tanggal` = '$tanggal' AND (`actual_keluar` is null)";
                $this->db->where($where);
                // $this->db->where("actual_masuk", null);
                // $this->db->where("userid", $userid_web);
                // $this->db->where("tanggal", $tanggal);
                $this->db->update("site.absensi_transaksi", $data);
                print_r($this->db->last_query());
            } else {
                // insert data
                echo "insert_data : ".$tanggal."<br>";
                echo "checkin_time : ".$checkin_time."<br>";

                $data = [
                    "username_shd" => $username_employee,
                    "userid" => $userid_web,
                    "tanggal" => $tanggal,
                    "actual_masuk" => ($checkin_time) ? $checkin_time : null,
                    "actual_keluar" => $checkout_time,
                    "total_jam_kerja" => $total_jam_kerja,
                    "status_hari" => $status_hari,
                    'jam_masuk_kantor'  => $jam_kerja->row()->jam_masuk,
                    'jam_keluar_kantor' => $jam_kerja->row()->jam_keluar,
                    'flag_terlambat'    => date("H:i", strtotime($checkin_time)) > date("H:i", strtotime($jam_kerja->row()->jam_masuk)) ? 1 : 0,
                    'signature'         => 'absensi-' . md5($userid_web.$tahun.$bulan),
                ];

                $this->db->insert("site.absensi_transaksi", $data);
            }

        }
    }

    public function generate_tanggal($tahun, $bulan)
    {
        // echo "tahun : ".$tahun."<br>";
        // echo "bulan : ".$bulan."<br>";

        $last_day = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        

        $tanggal = $tahun."-".$bulan."-01";

        // echo "last_day : ".$last_day."<br>";

        for ($i=1; $i <= $last_day ; $i++) { 
            $tanggal = $tahun."-".$bulan."-".$i;
            // echo $tanggal."<br>";

            $query = "insert into site.absensi_master_tanggal (tahun,tanggal, hari) values('$tahun','$tanggal', date_format('$tanggal', '%w'))";
            $this->db->query($query);

        }



    }

    public function insert_temp_summary_sales($year, $month)
    {
        $truncate_temp_summary_sales  = $this->db->truncate('site.temp_summary_sales');

        $query = "
            insert into site.temp_summary_sales 
            select nodokjdi, TGLDOKJDI, HRDOK, bulan, thndok, concat(kode_comp,nocab) as site_code, supp, kodeprod, namaprod, banyak, tot1 
            from data$year.fi a
            where bulan = $month and (kodeprod in (select kodeprod from mpm.tabprod where (grup != 'gimmick' or grup is null)))
            union ALL
            select 	nodokjdi, TGLDOKJDI, HRDOK, bulan, thndok, concat(kode_comp,nocab) as site_code, supp, kodeprod, namaprod, banyak, tot1 
            from data$year.ri
            where bulan = $month and  (kodeprod in (select kodeprod from mpm.tabprod where (grup != 'gimmick' or grup is null)))
            ";

        $data = $this->db->query($query);
        echo "<pre>";
        print_r($query);
        echo "</pre>";
    }

    public function get_detail_summary_sales($site_code)
    {
        $query = "
            select a.site_code, max(a.tgldokjdi) as maxtanggal, a.bulan, a.supp, b.namasupp, format(SUM(a.tot1),2) as tot1
            from site.temp_summary_sales  a
            join site.master_supplier b
            on a.supp = b.supp
            where a.site_code = '$site_code'
            GROUP BY a. site_code, a.supp
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_temp_summary_po($year, $month)
    {
        $truncate_temp_summary_po  = $this->db->truncate('site.temp_summary_po');

        $query = "
            insert site.temp_summary_po (id_ref, userid, supp, nopo, tglpo, tglpesan, open, status, created_by, company, kode_alamat, total_value)
            select a.id, a.userid, a.supp, a.nopo, a.tglpo, a.tglpesan, a.open, a.status, a.created_by, a.company, a.kode_alamat, a.total_value
            from mpm.po a
            where a.deleted = 0 and year(a.tglpesan) = $year and month(a.tglpesan) = $month

        ";

        $data = $this->db->query($query);
        echo "<pre>";
        print_r($query);
        echo "</pre>";
    }

    public function get_detail_summary_po($site_code)
    {
        $query = "
            select b.namasupp, a.*, format(a.total_value,2) as total_value, month(a.tglpesan) as bulan
            from site.temp_summary_po a
            join site.master_supplier b
            on a.supp = b.supp
            where a.kode_alamat = '$site_code'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_temp_email_pengajuan_retur()
    {
        $truncate_temp_email_pengajuan_retur  = $this->db->truncate('management_inventory.temp_email_pengajuan_retur');
        $query = "
            insert into management_inventory.temp_email_pengajuan_retur (no_pengajuan, tipe, site_code, nama, 
            supp, tanggal_pengajuan, status, nama_status, note, keterangan_lain, signature, digital_signature, 
            created_at, created_by, last_updated, last_updated_by)
            select a.no_pengajuan, a.tipe, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.status, a.nama_status, a.note, 
            a.keterangan_lain, a.signature, a.digital_signature, a.created_at, a.created_by, a.last_updated, a.last_updated_by
            from management_inventory.pengajuan_retur a
            WHERE deleted is null and no_pengajuan is not null and (site_code not like 'MPI%' and site_code not like 'penta%') and status in ('1', '9', '5')
        ";
        $data = $this->db->query($query);
        echo "<pre>";
        print_r($query);
        echo "</pre>";

    }

    public function header_send_email_to()
    {
        $query = "
            select a.*, b.id, b.email 
            from site.master_site a
            left join site.master_user b
            on left(a.site_code,3) = b.username
            where a.status_email_otomatis = 1
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        
        return $this->db->query($query);
    }

    public function detail_data_temp_email_pengajuan_retur($site_code)
    {
        $query = "
            select a.*, b.namasupp, c.email, date(a.tanggal_pengajuan) as date_pengajuan
            from management_inventory.temp_email_pengajuan_retur a
            LEFT JOIN site.master_supplier b
            on LEFT(a.supp,3) = b.supp
            LEFT JOIN (
                    SELECT a.email, a.id
                    from site.master_user a
            )c on a.created_by = c.id
            where a.site_code = '$site_code'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        
        return $this->db->query($query);
        // die;
    }

    public function get_site_code($site_code)
    {
        $query = "
            select *
            from site.master_site a
            WHERE a.site_code = '$site_code'
        ";

        return $this->db->query($query);
    }

    public function get_rekap_email_summary($year, $month, $day)
    {
        $query = "
            select *
            from site.email_summary a
            where year(a.created_at) =  $year and month(a.created_at) = $month and day(a.created_at) = $day and a.status_email = 9
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function create_table_temp_selloutproduct($format_time)
    {
        $create_table = "
            create table if not exists site.temp_sell_out_product_$format_time(
                site_code varchar(20),
                branch_name varchar(50),
                nama_comp varchar(50),
                region varchar(10),
                kode_lang varchar(20),
                nama_lang varchar(50),
                kode_type varchar(10),
                kodesalur varchar(20),
                kodesales varchar(20),
                kodeprod varchar(20),
                namaprod varchar(50),
                namasupp varchar(50),
                nama_group varchar(50),
                kode_group varchar(20),
                nama_sub_group varchar(50),
                kode_sub_group varchar(20),
                banyak varchar(20),
                tot1 varchar(20),
                bulan varchar(10),
                tgldokjdi varchar(20),
                created_by int(11),
                created_at datetime
            )
        ";
        $create_table = $this->db->query($create_table);

        if ($create_table) {
            $data = [
                'name_table' => "temp_sell_out_product_$format_time",
                'created_at' => $this->created_at,
                'created_by' => $this->created_by,
                'status'    => 1
            ];

            $this->db->insert('site.temp_sell_out_product_log', $data);
        }

        return $create_table;
    }

    public function create_index_temp_selloutproduct($format_time)
    {
        $create_index = "
            create index idx_temp_sell_out_product_$format_time on site.temp_sell_out_product_$format_time(site_code, tgldokjdi, kodeprod)
        ";
        $create_index = $this->db->query($create_index);
        return $create_index;
    }

    public function insert_temp_sell_out_product($data, $time)
    {
        $tahun = $data['tahun'];

        $query = "
            insert into site.temp_sell_out_product_$time
            select  a.site_code, b.branch_name, b.nama_comp, b.region, a.kode_lang, a.nama_lang, 
                    a.kode_type, a.kodesalur, a.kodesales, a.kodeprod,
                    c.namaprod, c.namasupp, c.nama_group, c.grup, c.nama_sub_group, c.subgroup,
                    a.banyak, a.tot1, a.bulan, a.tgldokjdi, '$this->created_by', '$time'
            from 
            (
                select 	concat(a.kode_comp, a.nocab) as site_code, 
                        concat(a.kode_comp, a.kode_lang) as kode_lang, a.nama_lang,
                        a.kode_type, a.kodesalur, a.kodesales, a.bulan, a.kodeprod, a.banyak, a.tot1, a.tgldokjdi
                from data$tahun.fi a 
                union all 
                select 	concat(a.kode_comp, a.nocab) as site_code, null as kode_lang, null as nama_lang,
                        a.kode_type, a.kodesalur, a.kodesales, a.bulan, a.kodeprod, a.banyak, a.tot1, a.tgldokjdi
                from data$tahun.ri a 
            )a left join (
                select a.site_code, a.branch_name, a.nama_comp, a.region
                from site.master_site a 
            )b on a.site_code = b.site_code left join (
                select a.kodeprod, a.namaprod, a.namasupp, a.nama_group, a.grup, a.nama_sub_group, a.subgroup
                from site.master_product_with_harga a 
            )c on a.kodeprod = c.kodeprod
        ";
        return $this->db->query($query);
    }

    public function get_closing_bulanan_group_by_tahun()
    {
        $query = "
            select a.tahun, a.bulan, a.status_closing, count(*) as count
            from site.temp_sell_out_product_closing_bulanan a
            where a.status_closing = 0
            GROUP BY a.tahun
        ";

        return $this->db->query($query);
    }

    public function get_closing_bulanan($tahun)
    {
        $query = "
            select a.tahun, a.bulan, a.status_closing
            from site.temp_sell_out_product_closing_bulanan a
            where a.status_closing = 0 and a.tahun = '$tahun'
        ";

        return $this->db->query($query);
    }

    public function delete_firi($tahun, $bulan)
    {
        $query = "
            delete from site.temp_sell_out_product_firi
            where tahun = '$tahun' and bulan in ($bulan)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);

    }

    public function insert_firi($tahun, $bulan)
    {
        $query = "
            insert into site.temp_sell_out_product_firi
            select  concat(a.kode_comp, a.nocab) as site_code, 
                    concat(a.kode_comp, a.kode_lang) as kode_lang, a.nama_lang,
                    a.kode_type, a.kodesalur, concat(a.kodesales, a.nocab) as kodesales, 
                    a.bulan, a.kodeprod, a.banyak, a.tot1, a.tgldokjdi, '$this->created_at', $tahun
            from data$tahun.fi a 
            where a.bulan in ($bulan)
            union all 
            select  concat(a.kode_comp, a.nocab) as site_code, 
                    null as kode_lang, null as nama_lang,
                    a.kode_type, a.kodesalur, concat(a.kodesales, a.nocab) as kodesales, 
                    a.bulan, a.kodeprod, a.banyak, a.tot1, a.tgldokjdi, '$this->created_at', $tahun
            from data$tahun.ri a 
            where a.bulan in ($bulan)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_master_firi($tahun, $bulan)
    {
        $query = "
            delete from site.master_firi_v1
            where tahun = '$tahun' and bulan in ($bulan)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_master_firi_tipe_class($tahun, $bulan)
    {
        $query = "
            delete from site.master_firi_v1_tipe_class
            where tahun = '$tahun' and bulan in ($bulan)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function delete_master_firi_v2($tahun, $bulan)
    {
        $query = "
            delete from site.master_firi_v2
            where tahun = '$tahun' and bulan in ($bulan)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);

    }

    public function insert_master_firi($tahun, $bulan)
    {

        $query = "
            insert into site.master_firi_v1
            select 	a.site_code, b.branch_name, b.nama_comp, b.region,
                    a.kode_lang, a.nama_lang, 
                    a.kode_type, e.nama_type, e.sektor, e.segment, 
                    a.kodesalur, d.namasalur, d.groupsalur, 
                    a.kodesales, f.namasales,
                    a.tgldokjdi, a.bulan, 
                    c.namasupp, a.kodeprod, c.namaprod, c.nama_group, c.nama_sub_group, 
                    sum(a.banyak) as unit, sum(a.tot1) as value, count(distinct(a.kode_lang)) as trans, a.tahun
            from
            (
                select *
                from site.temp_sell_out_product_firi a
                where a.tahun = '$tahun' and a.bulan in ($bulan)
            )a left join (
                select a.site_code, a.branch_name, a.nama_comp, a.region
                from site.master_site a 
            )b on a.site_code = b.site_code left join (
                select a.kodeprod, a.namaprod, a.nama_group, a.nama_sub_group, a.namasupp
                from site.master_product_with_harga a 
            )c on a.kodeprod = c.kodeprod left join (
                select a.kode, a.jenis as namasalur, a.`group` as groupsalur
                from mpm.tbl_tabsalur a
            )d on a.kodesalur = d.kode left join (
                select a.kode_type, a.nama_type, a.sektor, a.segment
                from mpm.tbl_bantu_type a
            )e on a.kode_type = e.kode_type left join (
                select concat(kodesales,nocab) as kodesales, namasales
                from data$tahun.tabsales
                group by concat(kodesales,nocab)
            )f on a.kodesales = f.kodesales
            group by a.site_code, a.kodeprod, a.bulan
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_master_firi_tipe_class($tahun, $bulan)
    {

        $query = "
            insert into site.master_firi_v1_tipe_class
            select 	a.site_code, b.branch_name, b.nama_comp, b.region,
                    a.kode_lang, a.nama_lang, 
                    a.kode_type, e.nama_type, e.sektor, e.segment, 
                    a.kodesalur, d.namasalur, d.groupsalur, 
                    a.kodesales, f.namasales,
                    a.tgldokjdi, a.bulan, 
                    c.namasupp, a.kodeprod, c.namaprod, c.nama_group, c.nama_sub_group, 
                    sum(a.banyak) as unit, sum(a.tot1) as value, count(distinct(a.kode_lang)) as trans, a.tahun
            from
            (
                select *
                from site.temp_sell_out_product_firi a
                where a.tahun = '$tahun' and a.bulan in ($bulan)
            )a left join (
                select a.site_code, a.branch_name, a.nama_comp, a.region
                from site.master_site a 
            )b on a.site_code = b.site_code left join (
                select a.kodeprod, a.namaprod, a.nama_group, a.nama_sub_group, a.namasupp
                from site.master_product_with_harga a 
            )c on a.kodeprod = c.kodeprod left join (
                select a.kode, a.jenis as namasalur, a.`group` as groupsalur
                from mpm.tbl_tabsalur a
            )d on a.kodesalur = d.kode left join (
                select a.kode_type, a.nama_type, a.sektor, a.segment
                from mpm.tbl_bantu_type a
            )e on a.kode_type = e.kode_type left join (
                select concat(kodesales,nocab) as kodesales, namasales
                from data$tahun.tabsales
                group by concat(kodesales,nocab)
            )f on a.kodesales = f.kodesales
            group by a.site_code, a.kodeprod, a.bulan, a.kode_type, a.kodesalur
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_master_firi_v2($tahun, $bulan)
    {
        $query = "
            insert into site.master_firi_v2
            select 	a.site_code, b.branch_name, b.nama_comp, b.region,
                    a.kode_lang, a.nama_lang, 
                    a.kode_type, e.nama_type, e.sektor, e.segment, 
                    a.kodesalur, d.namasalur, d.groupsalur, 
                    a.kodesales, f.namasales,
                    a.tgldokjdi, a.bulan, 
                    c.namasupp, a.kodeprod, c.namaprod, c.nama_group, c.nama_sub_group, 
                    sum(a.banyak) as unit, sum(a.tot1) as value, count(distinct(a.kode_lang)) as trans, a.tahun
            from
            (
                select *
                from site.temp_sell_out_product_firi a
                where a.tahun = '$tahun' and a.bulan in ($bulan)
            )a left join (
                select a.site_code, a.branch_name, a.nama_comp, a.region
                from site.master_site a 
            )b on a.site_code = b.site_code left join (
                select a.kodeprod, a.namaprod, a.nama_group, a.nama_sub_group, a.namasupp
                from site.master_product_with_harga a 
            )c on a.kodeprod = c.kodeprod left join (
                select a.kode, a.jenis as namasalur, a.`group` as groupsalur
                from mpm.tbl_tabsalur a
            )d on a.kodesalur = d.kode left join (
                select a.kode_type, a.nama_type, a.sektor, a.segment
                from mpm.tbl_bantu_type a
            )e on a.kode_type = e.kode_type left join (
                select concat(kodesales,nocab) as kodesales, namasales
                from data$tahun.tabsales
                group by concat(kodesales,nocab)
            )f on a.kodesales = f.kodesales
            group by a.site_code, a.kodeprod, a.bulan a.kode_type, d.groupsalur
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function create_table_master_firi()
    {
        $this->db->query("drop table if exists site.master_firi_v1");

        $create_table = "
            create table if not exists site.master_firi_v1(
                site_code varchar(20),
                branch_name varchar(255),
                nama_comp varchar(255),
                region varchar(10),
                kode_lang varchar(20),
                nama_lang varchar(255),
                kode_type varchar(10),
                nama_type varchar(50),
                sektor varchar(50),
                segment varchar(50),
                kodesalur varchar(20),
                namasalur varchar(50),
                groupsalur varchar(50),
                kodesales varchar(20),
                namasales varchar(50),
                tgldokjdi varchar(20),
                bulan varchar(10),
                namasupp varchar(50),
                kodeprod varchar(20),
                namaprod varchar(50),
                nama_group varchar(50),
                nama_sub_group varchar(50),
                unit varchar(50),
                value varchar(255),
                trans varchar(50),
                tahun varchar(4)
            )
        ";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function create_table_master_firi_tipe_class()
    {
        $this->db->query("drop table if exists site.master_firi_v1_tipe_class");

        $create_table = "
            create table if not exists site.master_firi_v1_tipe_class(
                site_code varchar(20),
                branch_name varchar(255),
                nama_comp varchar(255),
                region varchar(10),
                kode_lang varchar(20),
                nama_lang varchar(255),
                kode_type varchar(10),
                nama_type varchar(50),
                sektor varchar(50),
                segment varchar(50),
                kodesalur varchar(20),
                namasalur varchar(50),
                groupsalur varchar(50),
                kodesales varchar(20),
                namasales varchar(50),
                tgldokjdi varchar(20),
                bulan varchar(10),
                namasupp varchar(50),
                kodeprod varchar(20),
                namaprod varchar(50),
                nama_group varchar(50),
                nama_sub_group varchar(50),
                unit varchar(50),
                value varchar(255),
                trans varchar(50),
                tahun varchar(4)
            )
        ";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function create_table_master_firi_v2()
    {
        $this->db->query("drop table if exists site.master_firi_v2");

        $create_table = "
            create table if not exists site.master_firi_v2(
                site_code varchar(20),
                branch_name varchar(255),
                nama_comp varchar(255),
                region varchar(10),
                kode_lang varchar(20),
                nama_lang varchar(255),
                kode_type varchar(10),
                nama_type varchar(50),
                sektor varchar(50),
                segment varchar(50),
                kodesalur varchar(20),
                namasalur varchar(50),
                groupsalur varchar(50),
                kodesales varchar(20),
                namasales varchar(50),
                tgldokjdi varchar(20),
                bulan varchar(10),
                namasupp varchar(50),
                kodeprod varchar(20),
                namaprod varchar(50),
                nama_group varchar(50),
                nama_sub_group varchar(50),
                unit varchar(50),
                value varchar(255),
                trans varchar(50),
                tahun varchar(4)
            )
        ";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function create_table_master_firi_duplicate($filename)
    {
        $this->db->query("drop table if exists site.$filename");

        $create_table = "
            create table if not exists site.$filename(
                site_code varchar(20),
                branch_name varchar(255),
                nama_comp varchar(255),
                region varchar(10),
                kode_lang varchar(20),
                nama_lang varchar(255),
                kode_type varchar(10),
                nama_type varchar(50),
                sektor varchar(50),
                segment varchar(50),
                kodesalur varchar(20),
                namasalur varchar(50),
                groupsalur varchar(50),
                kodesales varchar(20),
                namasales varchar(50),
                tgldokjdi varchar(20),
                bulan varchar(10),
                namasupp varchar(50),
                kodeprod varchar(20),
                namaprod varchar(50),
                nama_group varchar(50),
                nama_sub_group varchar(50),
                unit varchar(50),
                value varchar(255),
                trans varchar(50),
                tahun varchar(4)
            )
        ";
        $create_table = $this->db->query($create_table);
        return $create_table;
    }

    public function create_index_master_firi_v1()
    {
        $create_index = "
            create index idx_v1 on site.master_firi_v1(site_code, tgldokjdi, kodeprod, kode_type, kodesalur)
        ";
        $create_index = $this->db->query($create_index);
        return $create_index;
    }

    public function create_index_master_firi_v1_tipe_class()
    {
        $create_index = "
            create index idx_tipe_class on site.master_firi_v1_tipe_class(site_code, tgldokjdi, kodeprod, kode_type, kodesalur)
        ";
        $create_index = $this->db->query($create_index);
        return $create_index;
    }

    public function insert_log($filename, $status)
    {
        $data = [
            'name_table' => $filename,
            'created_at' => $this->created_at,
            'created_by' => $this->userid,
            'status'    => $status
        ];

        return $this->db->insert('site.temp_sell_out_product_log', $data);

    }

    public function insert_temp_duplicate($filename, $destination)
    {
        $query = "
            insert into site.$destination
            select 	*
            from site.$filename
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_temp_sell_out_product_log()
    {
        $query = "
            select *
            from site.temp_sell_out_product_log a 
            where a.`status` = 1 and a.name_table = 'master_firi_v1'
            ORDER BY a.id DESC
            limit 1
        ";
        return $this->db->query($query);
    }

    public function get_temp_sell_out_product_log_tipe_class()
    {
        $query = "
            select *
            from site.temp_sell_out_product_log a 
            where a.`status` = 2 and a.name_table = 'master_firi_v1_tipe_class'
            ORDER BY a.id DESC
            limit 1
        ";
        return $this->db->query($query);
    }
}