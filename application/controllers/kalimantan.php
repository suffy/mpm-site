<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kalimantan extends MY_Controller
{    
    function kalimantan()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/kalimantan','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_kalimantan', 'model_monitor','model_inventory'));
    }
    function index()
    {
        $this->dashboard();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function dashboard(){

        $data = [
            'title'                   => 'MPM Monitoring',
            'get_dashboard_monitor'   => '',
            // 'signature'               => $this->model_kalimantan->get_last_signature()->row()->signature
        ];

        $this->load->view('kalimantan/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/content', $data);
        // $this->load->view('kalimantan/kalimantan',$data);
        $this->load->view('kalimantan/footer');

    }

    public function ajuan_retur(){
        $data = [
            'title'         => 'MPM Monitoring | Ajuan Retur',
            'get_retur_pending_dp'   => $this->model_kalimantan->get_raw_retur(1),
            'get_retur_proses_mpm'   => $this->model_kalimantan->get_raw_retur(2),
            'get_retur_proses_dp'   => $this->model_kalimantan->get_raw_retur(3),
            'get_retur_pending_principal'   => $this->model_kalimantan->get_raw_retur(4),
            'get_retur_proses_kirim_barang'   => $this->model_kalimantan->get_raw_retur(5),
            'get_retur_proses_pemusnahan'   => $this->model_kalimantan->get_raw_retur(6),
            'get_retur_proses_principal_terima_barang'   => $this->model_kalimantan->get_raw_retur(7),
            'get_retur_barang_diterima_principal'   => $this->model_kalimantan->get_raw_retur(8),
            'get_retur_pemusnahan_oleh_dp'   => $this->model_kalimantan->get_raw_retur(9),
            'get_retur_lainnya'   => $this->model_kalimantan->get_raw_retur(10),
            'get_ajuan_retur_created_at'    => $this->model_kalimantan->get_ajuan_retur_created_at()
        ];

        // $this->load->view('kalimantan/top_header', $data);
        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/ajuan_retur', $data);
        $this->load->view('monitor/footer'); 
    }

    public function raw_data_retur(){

        $query="
            select *
            from site.dashboard_kalimantan_raw_ajuan_retur a
        ";                                      
        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Raw Data Pengajuan Retur.csv');
    }

    public function po(){
        $data = [
            'title'         => 'MPM Monitoring | PO',
            'get_po'   => $this->model_kalimantan->get_po(),
        ];

        // $this->load->view('kalimantan/top_header', $data);        
        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/po', $data);
        $this->load->view('monitor/footer'); 
    }

    public function update_retur(){

        $created_at = $this->model_outlet_transaksi->timezone();

        $truncate = $this->db->query("truncate site.dashboard_kalimantan_raw_ajuan_retur");
        if ($truncate) {
            
            $query = "
            insert into site.dashboard_kalimantan_raw_ajuan_retur
            select 	'', a.site_code, if(d.branch_name is null, e.name, d.branch_name) as branch_name,
                        if(d.nama_comp is null, e.company, d.nama_comp) as nama_comp, 
                        if(a.supp ='001-herbal', 'DELTOMED-HERBAL',if(a.supp='001-herbana','DELTOMED-HERBANA',c.namasupp)) as principal,
                        a.no_pengajuan, a.tanggal_pengajuan, a.file, a.status, a.nama_status, a.keterangan_principal, a.file_principal,
                        a.tanggal_approval, a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.tanggal_tiba, a.file_pengiriman, 
                        a.tanggal_terima, a.nama_penerima, a.no_terima, a.file_terima, a.tanggal_pemusnahan, a.nama_pemusnahan, 
                        a.file_pemusnahan, a.foto_pemusnahan_1, a.foto_pemusnahan_2, b.*,  f.harga_rbp, (b.jumlah * f.harga_rbp) as value_perkiraan, 			a.keterangan_lain, '$created_at'
            from 	db_temp.t_temp_pengajuan_retur a INNER JOIN
            (
                select 	a.id_pengajuan, a.kodeprod, a.namaprod, a.batch_number, a.expired_date, a.jumlah, a.alasan, a.satuan, 
                            a.nama_outlet, a.keterangan, a.total_karton, a.total_dus, a.total_pcs, a.harga_karton, a.harga_dus,a.harga_pcs,
                            a.value, a.kode_produksi, a.deskripsi			
                from db_temp.t_temp_produk_pengajuan_retur a
                where a.deleted is null
            )b on a.id = b.id_pengajuan LEFT JOIN mpm.tabsupp c 
                on a.supp = c.SUPP LEFT JOIN 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code LEFT JOIN
            (
                select a.id, a.username, a.name, a.company, a.kode_alamat
                from mpm.user a 
            )e on a.site_code = e.kode_alamat left join 
            (
                select a.kodeprod, b.h_dp as harga_rbp
                from mpm.tabprod a inner join 
                (
                    select a.kodeprod, a.h_dp
                    from mpm.prod_detail a 
                    where a.tgl = (
                        select max(b.tgl) 
                        from mpm.prod_detail b
                        where a.kodeprod = b.kodeprod
                        GROUP BY b.kodeprod
                    )
                )b on a.kodeprod = b.kodeprod
            )f on b.kodeprod = f.kodeprod
            where a.deleted is null and left(a.site_code, 3) in ('BRB','SSM','PBN','PKR','SPT','BTG','DAS','GTO','MAN','BLG','BKM','BON','PAL','PAR','VBT','CSS','PTK','SSJ','SMR','MDO') 
            and year(a.tanggal_pengajuan) in (2022, 2023)
            ORDER BY a.site_code, a.no_pengajuan, b.kodeprod
            ";
    
            $proses = $this->db->query($query);

        }        
        redirect('kalimantan/ajuan_retur');

    }

    public function raw_data_po(){
        $query="
            select 	a.id, a.company, a.nopo, date(a.tglpo) as tglpo, date(a.tglpesan) as tglpesan, a.supp, a.tipe, 
                    a.userid,a.open as open,a.status,
                    b.banyak, b.harga, sum(b.banyak * b.harga) as total,
                    d.branch_name, d.nama_comp, e.NAMASUPP as namasupp
            from mpm.po a INNER JOIN mpm.po_detail b 
                on a.id = b.id_ref LEFT JOIN mpm.`user` c 
                on a.userid = c.id LEFT JOIN
                (
                    select a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where `status` = 1
                    GROUP BY a.kode_comp
                )d on c.username = d.kode_comp LEFT JOIN mpm.tabsupp e
                on a.supp = e.SUPP
            where b.deleted = 0 and a.deleted = 0 and left(a.kode_alamat, 3) in ('BRB','SSM','PBN','PKR','SPT','BTG','DAS','GTO','MAN','BLG','BKM','BON','PAL','PAR','VBT','CSS','PTK','SSJ','SMR','MDO') and
            ((
                year(a.tglpesan) in (year(date(now()))) and month(a.tglpesan) in (month(date(now())))
            ) or
            (
                year(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                month(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
            ))
            GROUP BY a.id
            ORDER BY id desc
        ";                                      
        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Report PO.csv');
    }

    public function piutang(){
        $data = [
            'title'         => 'MPM Monitoring | Piutang',
            'get_po'   => $this->model_kalimantan->get_po(),
        ];

        $this->load->view('kalimantan/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/piutang', $data);
        $this->load->view('monitor/footer'); 
    }

}
?>
