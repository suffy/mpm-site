<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_raw extends MY_Controller
{    
    function management_raw()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_raw'));
    }
    function index()
    {
        $this->banjarmasin();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function banjarmasin(){

        $data = [
            'title'     => 'Management Raw / Import data banjarmasin',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_banjarmasin',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('SSMS9')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    public function template_banjarmasin(){        
        $query = "
            select  '' as distributor, '' as cabang, '' as tipetrans, '' as divisi, '' as principal, '' as productgroup1, '' as productgroup2, '' as productgroup3, '' as brand, '' as kodeproduk, '' as kodevarian, '' as kodeprodukprincipal, '' as namaproduk, '' as packaging, '' as productclass, '' as kodecustomer,'' as namacustomer, '' as alamatcustomer, '' as area, '' as subarea, '' as channel, '' as subchannel, '' as customergroup, '' as keyaccount, '' as kodesalesman, '' as namasalesman, '' as kodesalesco, '' as namasalesco, '' as kodespv, '' as namaspv, '' as tahunbulan, '' as bulan, '' as tanggal, '' as weekno, '' as nomornota, '' as salesmethod, '' as sellingtype, '' as qtysold, '' as qtysoldcrt, '' as qtysolduom1, '' as qtysolduom2, '' as qtysolduom3, '' as qtysolduom4, '' as qtysoldtotalpcs, '' as freegoodtotalpcs, '' as tonnage, '' as volumeltr, '' as grossamount, '' as linediscount1, '' as linediscount2, '' as linediscount3, '' as linediscount4, '' as linediscount5, '' as totallinediscount, '' as discountnota1, '' as discountnota2, '' as discountnota3, '' as totaldiscountnota, '' as dpp, '' as ppn, '' as ppnbm, '' as tax3, '' as netamount, '' as warehouse, '' as customerpo, '' as customerjoindate, '' as nofakturpajak, '' as tglfakturpajak, '' as nomorfakturproforma, '' as tglfakturproforma, '' as term, '' as uom1, '' as uom2, '' as uom3, '' as uom4, '' as isiuom1, '' as isiuom2, '' as isiuom3, '' as sellingprice, '' as cogs, '' as sellingpriceinkg, '' as caseweightinkg, '' as qtyordertotalpcs, '' as end
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Banjarmasin.csv');
    }

    public function barabai(){

        $data = [
            'title'     => 'Management Raw / Import data barabai',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_barabai',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('BRBS0')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/barabai', $data);
        $this->load->view('mes/footer');
    }

    public function template_barabai(){        
        $query = "
            select  '' as distributor, '' as cabang, '' as tipetrans, '' as divisi, '' as principal, '' as productgroup1, '' as productgroup2, '' as productgroup3, '' as brand, '' as kodeproduk, '' as kodevarian, '' as kodeprodukprincipal, '' as namaproduk, '' as packaging, '' as productclass, '' as kodecustomer,'' as namacustomer, '' as alamatcustomer, '' as area, '' as subarea, '' as channel, '' as subchannel, '' as customergroup, '' as keyaccount, '' as kodesalesman, '' as namasalesman, '' as kodesalesco, '' as namasalesco, '' as kodespv, '' as namaspv, '' as tahunbulan, '' as bulan, '' as tanggal, '' as weekno, '' as nomornota, '' as salesmethod, '' as sellingtype, '' as qtysold, '' as kartonutuh, '' as qtysoldpcs, '' as freegoodpcs, '' as tonnage, '' as volumeltr, '' as grossamount, '' as linediscount1, '' as linediscount2, '' as linediscount3, '' as linediscount4, '' as linediscount5, '' as totallinediscount, '' as discountnota1, '' as discountnota2, '' as discountnota3, '' as totaldiscountnota, '' as dpp, '' as ppn, '' as ppnbm, '' as tax3, '' as netamount, '' as warehouse, '' as customerpo, '' as customerjoindate, '' as nofakturpajak, '' as tglfakturpajak, '' as nomorfakturproforma, '' as tglfakturproforma, '' as cogs, '' as caseweightinkg, '' as end
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Barabai.csv');
    }

    public function import_barabai(){
        // $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 703 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/barabai');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/barabai');
                die;
            }

        }

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $distributor = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $cabang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tipetrans = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $divisi = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $principal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $productgroup1 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $productgroup2 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $productgroup3 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $brand = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $kodeproduk = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kodevarian = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kodeprodukprincipal = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $namaproduk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $packaging = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $productclass = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $kodecustomer = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $namacustomer = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $alamatcustomer = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $area = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $channel = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $subchannel = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $customergroup = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $keyaccount = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $kodesalesman = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $namasalesman = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $kodesalesco = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $namasalesco = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $kodespv = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $namaspv = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $tahunbulan = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $bulan = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $weekno = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $nomornota = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $salesmethod = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $sellingtype = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $qtysold = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $kartonutuh = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $qtysoldpcs = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $freegoodpcs = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $tonnage = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $volumeltr = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $grossamount = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $linediscount1 = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $linediscount2 = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $linediscount3 = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $linediscount4 = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $linediscount5 = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $totallinediscount = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $discountnota1 = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $discountnota2 = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $discountnota3 = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $totaldiscountnota = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $dpp = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $ppn = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $ppnbm = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $tax3 = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $netamount = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $warehouse = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $customerpo = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $customerjoindate = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $nofakturpajak = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $tglfakturpajak = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $nomorfakturproforma = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $tglfakturproforma = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $cogs = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $caseweightinkg = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $end = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                                        

                    $data = [
                        'distributor'      => $distributor,
                        'cabang'      => $cabang,
                        'tipetrans'      => $tipetrans,
                        'divisi'      => $divisi,
                        'principal'      => $principal,
                        'productgroup1'      => $productgroup1,
                        'productgroup2'      => $productgroup2,
                        'productgroup3'      => $productgroup3,
                        'brand'      => $brand,
                        'kodeproduk'      => $kodeproduk,
                        'kodevarian'      => $kodevarian,
                        'kodeprodukprincipal'      => $kodeprodukprincipal,
                        'namaproduk'      => $namaproduk,
                        'packaging'      => $packaging,
                        'productclass'      => $productclass,
                        'kodecustomer'      => $kodecustomer,
                        'namacustomer'      => $namacustomer,
                        'alamatcustomer'      => $alamatcustomer,
                        'area'      => $area,
                        'subarea'      => $subarea,
                        'channel'      => $channel,
                        'subchannel'      => $subchannel,
                        'customergroup'      => $customergroup,
                        'keyaccount'      => $keyaccount,
                        'kodesalesman'      => $kodesalesman,
                        'namasalesman'      => $namasalesman,
                        'kodesalesco'      => $kodesalesco,
                        'namasalesco'      => $namasalesco,
                        'kodespv'      => $kodespv,
                        'namaspv'      => $namaspv,
                        'tahunbulan'      => $tahunbulan,
                        'bulan'      => $bulan,
                        'tanggal'      => $tanggal,
                        'weekno'      => $weekno,
                        'nomornota'      => $nomornota,
                        'salesmethod'      => $salesmethod,
                        'sellingtype'      => $sellingtype,
                        'qtysold'      => $qtysold,
                        'kartonutuh'      => $kartonutuh,
                        'qtysoldpcs'      => $qtysoldpcs,
                        'freegoodpcs'      => $freegoodpcs,
                        'tonnage'      => $tonnage,
                        'volumeltr'      => $volumeltr,
                        'grossamount'      => $grossamount,
                        'linediscount1'      => $linediscount1,
                        'linediscount2'      => $linediscount2,
                        'linediscount3'      => $linediscount3,
                        'linediscount4'      => $linediscount4,
                        'linediscount5'      => $linediscount5,
                        'totallinediscount'      => $totallinediscount,
                        'discountnota1'      => $discountnota1,
                        'discountnota2'      => $discountnota2,
                        'discountnota3'      => $discountnota3,
                        'totaldiscountnota'      => $totaldiscountnota,
                        'dpp'      => $dpp,
                        'ppn'      => $ppn,
                        'ppnbm'      => $ppnbm,
                        'tax3'      => $tax3,
                        'netamount'      => $netamount,
                        'warehouse'      => $warehouse,
                        'customerpo'      => $customerpo,
                        'nofakturpajak'      => $nofakturpajak,
                        'customerjoindate'      => $customerjoindate,
                        'tglfakturpajak'      => $tglfakturpajak,
                        'nomorfakturproforma'      => $nomorfakturproforma,
                        'tglfakturproforma'      => $tglfakturproforma,
                        'cogs'      => $cogs,
                        'caseweightinkg'      => $caseweightinkg,
                        'end'      => $end,
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_barabai',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_barabai a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.grossamount * 1.11) as omzet_raw from management_raw.raw_barabai a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'BRBS0',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'      => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'      => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_barabai_draft/'.$signature);

    }

    public function import_barabai_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data barabai',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('BRBS0', $signature),
            'url'=> 'management_raw/proses_mapping_barabai',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales('BRBS0', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_barabai', $data);
        $this->load->view('mes/footer');
    }    

    public function template_customer_banjarmasin(){        
        $query = "
            select  '' as customer_id, '' as customer_id_nd6, '' as nama_customer, '' as alamat, '' as kode_type, '' as kode_class, '' as kode_kota, '' as nama_kota, '' as kode_kecamatan, '' as nama_kecamatan, '' as kode_kelurahan, '' as nama_kelurahan
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Banjarmasin.csv');
    }

    public function template_customer_barabai(){        
        $query = "
            select  '' as customer_id, '' as customer_id_nd6, '' as nama_customer, '' as alamat, '' as kode_type, '' as kode_class, '' as kode_kota, '' as nama_kota, '' as kode_kecamatan, '' as nama_kecamatan, '' as kode_kelurahan, '' as nama_kelurahan
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Barabai.csv');
    }

    public function template_customer_batulicin(){        
        $query = "
            select  '' as customer_id, '' as customer_id_nd6, '' as nama_customer, '' as alamat, '' as kode_type, '' as kode_class
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Batulicin.csv');
    }

    public function import_banjarmasin(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 465 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/banjarmasin');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/banjarmasin');
                die;
            }

        }

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $distributor = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $cabang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tipetrans = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $divisi = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $principal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $productgroup1 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $productgroup2 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $productgroup3 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $brand = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $kodeproduk = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kodevarian = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kodeprodukprincipal = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $namaproduk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $packaging = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $productclass = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $kodecustomer = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $namacustomer = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $alamatcustomer = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $area = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $channel = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $subchannel = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $customergroup = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $keyaccount = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $kodesalesman = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $namasalesman = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $kodesalesco = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $namasalesco = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $kodespv = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $namaspv = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $tahunbulan = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $bulan = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $weekno = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $nomornota = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $salesmethod = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $sellingtype = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $qtysold = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $qtysoldcrt = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $qtysolduom1 = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $qtysolduom2 = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $qtysolduom3 = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $qtysolduom4 = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $qtysoldtotalpcs = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $freegoodtotalpcs = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $tonnage = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $volumeltr = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $grossamount = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $linediscount1 = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $linediscount2 = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $linediscount3 = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $linediscount4 = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $linediscount5 = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $totallinediscount = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $discountnota1 = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $discountnota2 = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $discountnota3 = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $totaldiscountnota = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $dpp = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $ppn = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $ppnbm = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $tax3 = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $netamount = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $warehouse = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $customerpo = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $customerjoindate = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $nofakturpajak = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $tglfakturpajak = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $nomorfakturproforma = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                    $tglfakturproforma = $worksheet->getCellByColumnAndRow(69, $row)->getValue();
                    $term = $worksheet->getCellByColumnAndRow(70, $row)->getValue();
                    $uom1 = $worksheet->getCellByColumnAndRow(71, $row)->getValue();
                    $uom2 = $worksheet->getCellByColumnAndRow(72, $row)->getValue();
                    $uom3 = $worksheet->getCellByColumnAndRow(73, $row)->getValue();
                    $uom4 = $worksheet->getCellByColumnAndRow(74, $row)->getValue();
                    $isiuom1 = $worksheet->getCellByColumnAndRow(75, $row)->getValue();
                    $isiuom2 = $worksheet->getCellByColumnAndRow(76, $row)->getValue();
                    $isiuom3 = $worksheet->getCellByColumnAndRow(77, $row)->getValue();
                    $sellingprice = $worksheet->getCellByColumnAndRow(78, $row)->getValue();
                    $cogs = $worksheet->getCellByColumnAndRow(79, $row)->getValue();
                    $sellingpriceinkg = $worksheet->getCellByColumnAndRow(80, $row)->getValue();
                    $caseweightinkg = $worksheet->getCellByColumnAndRow(81, $row)->getValue();
                    $qtyordertotalpcs = $worksheet->getCellByColumnAndRow(82, $row)->getValue();
                    $end = $worksheet->getCellByColumnAndRow(83, $row)->getValue();

                    $data = [
                        'distributor'      => $distributor,
                        'cabang'      => $cabang,
                        'tipetrans'      => $tipetrans,
                        'divisi'      => $divisi,
                        'principal'      => $principal,
                        'productgroup1'      => $productgroup1,
                        'productgroup2'      => $productgroup2,
                        'productgroup3'      => $productgroup3,
                        'brand'      => $brand,
                        'kodeproduk'      => $kodeproduk,
                        'kodevarian'      => $kodevarian,
                        'kodeprodukprincipal'      => $kodeprodukprincipal,
                        'namaproduk'      => $namaproduk,
                        'packaging'      => $packaging,
                        'productclass'      => $productclass,
                        'kodecustomer'      => $kodecustomer,
                        'namacustomer'      => $namacustomer,
                        'alamatcustomer'      => $alamatcustomer,
                        'area'      => $area,
                        'subarea'      => $subarea,
                        'channel'      => $channel,
                        'subchannel'      => $subchannel,
                        'customergroup'      => $customergroup,
                        'keyaccount'      => $keyaccount,
                        'kodesalesman'      => $kodesalesman,
                        'namasalesman'      => $namasalesman,
                        'kodesalesco'      => $kodesalesco,
                        'namasalesco'      => $namasalesco,
                        'kodespv'      => $kodespv,
                        'namaspv'      => $namaspv,
                        'tahunbulan'      => $tahunbulan,
                        'bulan'      => $bulan,
                        'tanggal'      => $tanggal,
                        'weekno'      => $weekno,
                        'nomornota'      => $nomornota,
                        'salesmethod'      => $salesmethod,
                        'sellingtype'      => $sellingtype,
                        'qtysold'      => $qtysold,
                        'qtysoldcrt'      => $qtysoldcrt,
                        'qtysolduom1'      => $qtysolduom1,
                        'qtysolduom2'      => $qtysolduom2,
                        'qtysolduom3'      => $qtysolduom3,
                        'qtysolduom4'      => $qtysolduom4,
                        'qtysoldtotalpcs'      => $qtysoldtotalpcs,
                        'freegoodtotalpcs'      => $freegoodtotalpcs,
                        'tonnage'      => $tonnage,
                        'volumeltr'      => $volumeltr,
                        'grossamount'      => $grossamount,
                        'linediscount1'      => $linediscount1,
                        'linediscount2'      => $linediscount2,
                        'linediscount3'      => $linediscount3,
                        'linediscount4'      => $linediscount4,
                        'linediscount5'      => $linediscount5,
                        'totallinediscount'      => $totallinediscount,
                        'discountnota1'      => $discountnota1,
                        'discountnota2'      => $discountnota2,
                        'discountnota3'      => $discountnota3,
                        'totaldiscountnota'      => $totaldiscountnota,
                        'dpp'      => $dpp,
                        'ppn'      => $ppn,
                        'ppnbm'      => $ppnbm,
                        'tax3'      => $tax3,
                        'netamount'      => $netamount,
                        'warehouse'      => $warehouse,
                        'customerpo'      => $customerpo,
                        'nofakturpajak'      => $nofakturpajak,
                        'customerjoindate'      => $customerjoindate,
                        'tglfakturpajak'      => $tglfakturpajak,
                        'nomorfakturproforma'      => $nomorfakturproforma,
                        'tglfakturproforma'      => $tglfakturproforma,
                        'term'      => $term,
                        'uom1'      => $uom1,
                        'uom2'      => $uom2,
                        'uom3'      => $uom3,
                        'uom4'      => $uom4,
                        'isiuom1'      => $isiuom1,
                        'isiuom2'      => $isiuom2,
                        'isiuom3'      => $isiuom3,
                        'sellingprice'      => $sellingprice,
                        'cogs'      => $cogs,
                        'sellingpriceinkg'      => $sellingpriceinkg,
                        'caseweightinkg'      => $caseweightinkg,
                        'qtyordertotalpcs'      => $qtyordertotalpcs,
                        'end'      => $end,
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_banjarmasin',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_banjarmasin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.grossamount * 1.11) as omzet_raw from management_raw.raw_banjarmasin a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'SSMS9',
            'filename'      => $filename,
            'signature'     => $signature,
            'type_file'      => 'raw_sales',
            'count_raw'      => $count,
            'omzet_raw'     => $omzet_raw,
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_banjarmasin_draft/'.$signature);
    }

    public function import_banjarmasin_draft($signature){
     
        $data = [
            'title'     => 'Management Raw / Preview Import data banjarmasin',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('SSMS9', $signature),
            'url'=> 'management_raw/proses_mapping_banjarmasin',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales('SSMS9', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    public function customer_banjarmasin(){
        $data = [
            'title'     => 'Customer banjarmasin',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_customer_banjarmasin',
            'get_log_customer_upload'    => $this->model_management_raw->get_log_customer_upload('SSMS9')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    public function customer_barabai(){
        $data = [
            'title'     => 'Customer barabai',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_customer_barabai',
            'get_log_customer_upload'    => $this->model_management_raw->get_log_customer_upload('BRBS0')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_barabai', $data);
        $this->load->view('mes/footer');
    }

    public function customer_batulicin(){
        $data = [
            'title'     => 'Customer batulicin',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_customer_batulicin',
            'get_log_customer_upload'    => $this->model_management_raw->get_log_customer_upload('SSJD2')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_batulicin', $data);
        $this->load->view('mes/footer');
    }

    public function import_customer_banjarmasin(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $customer_id_nd6 = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $alamat = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $kode_type = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $kode_class = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $kode_kota = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $nama_kota = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $kode_kecamatan = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kode_kelurahan = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                    $signature = md5($this->model_outlet_transaksi->timezone().$customer_id_nd6);

                    $cek_customer = $this->model_management_raw->get_raw_customer('SSMS9', $customer_id_nd6);
                    if($cek_customer->num_rows() > 0){

                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'kode_class'      => $kode_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];

                        $this->db->where('customer_id_nd6', $customer_id_nd6);
                        $this->db->update('management_raw.raw_customer_banjarmasin',$data);
                        
                    }else{
                        
                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'kode_class'      => $kode_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.raw_customer_banjarmasin',$data);

                    }

                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_customer_banjarmasin a";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code'     => 'SSMS9',
            'signature'     => $signature,
            'filename'      => $filename,
            'type_file'      => 'raw_customer',
            'count_raw'      => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        echo "<br><center><i>Upload Customer Banjarmasin Done ... </i></b><br>";
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/customer_banjarmasin');
    }

    public function import_customer_barabai(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $customer_id_nd6 = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $nama_customer = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $kode_type = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $kode_class = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $kode_kota = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $nama_kota = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $kode_kecamatan = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kode_kelurahan = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                    $signature = md5($this->model_outlet_transaksi->timezone().$customer_id_nd6);

                    $cek_customer = $this->model_management_raw->get_raw_customer('BRBS0', $customer_id_nd6);
                    if($cek_customer->num_rows() > 0){

                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'kode_class'      => $kode_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];

                        $this->db->where('customer_id_nd6', $customer_id_nd6);
                        $this->db->update('management_raw.raw_customer_barabai',$data);
                        
                    }else{
                        
                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'kode_class'      => $kode_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.raw_customer_barabai',$data);

                    }
                    
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_customer_barabai a";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code'     => 'BRBS0',
            'signature'     => $signature,
            'filename'      => $filename,
            'type_file'      => 'raw_customer',
            'count_raw'      => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        echo "<br><center><i>Upload Customer Barabai Done ... </i></b><br>";
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/customer_barabai');
    }

    public function import_customer_barabai_draft($signature){
       
        $data = [
            'title'     => 'Preview Import customer barabai',
            'id'        => $this->session->userdata('id'),
            'get_raw_customer_draft'  => $this->model_management_raw->get_raw_customer_draft_barabai($signature),
            'url'=> 'management_raw/proses_mapping_customer_barabai',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_customer_banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_customer_barabai(){

        $signature = $this->input->post('signature');
        $trim_customer_barabai = $this->model_management_raw->trim_customer_barabai($signature);

        if ($trim_customer_barabai) {
            echo "<br><center><i>trimming kode_type, kode_class, customer_id, customer_id_nd6 done ... </i></b><br>";
            $update_type_n_class = $this->model_management_raw->update_type_n_class_barabai($signature);
        
            if ($update_type_n_class) {
                echo "<br><center><i>updating nama_type, sektor, segment, nama_class done ... </i></b><br>";
                
                $inner = $this->model_management_raw->inner_customer_barabai($signature);
                if ($inner) {
                    
                    echo "<br><center><i>deleting redundant customer_id_nd5 done ... </i></b><br>";
                    $get_count = "select count(*) as count from management_raw.inner_raw_customer_barabai a where a.signature = '$signature'";
                    $count = $this->db->query($get_count)->row()->count;

                    $update = [
                        'count_mapping'  => $count
                    ];
                    $this->db->where('signature', $signature);
                    $this->db->update('management_raw.log_upload', $update);

                    echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
                    header('Refresh: 5; URL='.base_url().'management_raw/customer_barabai');

                }

            }
        }
    }

    public function import_customer_banjarmasin_draft($signature){
       
        $data = [
            'title'     => 'Preview Import customer banjarmasin',
            'id'        => $this->session->userdata('id'),
            'get_raw_customer_draft'  => $this->model_management_raw->get_raw_customer_draft($signature),
            'url'=> 'management_raw/proses_mapping_customer_banjarmasin',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_customer_banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    

    public function import_customer_batulicin(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $customer_id_nd6 = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $nama_customer = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $kode_type = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $kode_class = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

                    $data = [
                        'customer_id'      => $customer_id,
                        'customer_id_nd6'      => $customer_id_nd6,
                        'nama_customer'      => $nama_customer,
                        'alamat'      => $alamat,
                        'kode_type'      => $kode_type,
                        'kode_class'      => $kode_class,
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_customer_batulicin',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_customer_banjarmasin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code'     => 'SSMS9',
            'signature'     => $signature,
            'filename'      => $filename,
            'type_file'      => 'raw_customer',
            'count_raw'      => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_customer_banjarmasin_draft/'.$signature);
    }

    public function proses_mapping_banjarmasin(){

        $signature = $this->input->post('signature');
        $update_kodeproduk = $this->model_management_raw->update_kodeproduk('SSMS9', $signature);
        if ($update_kodeproduk) {            
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }
        
        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_banjarmasin('SSMS9', $signature);
        if ($inner_kodeproduk) { 
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod('SSMS9', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>updating namaproduk done ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('SSMS9', $signature);
        if ($update_branch) {                       
            echo "<br><center><i>updating branch_name dan nama_comp done ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('SSMS9', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('SSMS9', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        // update customer, type, class, dll

        $update_inner_customer_id_banjarmasin = $this->model_management_raw->update_inner_customer_id_banjarmasin($signature);           
        if ($update_inner_customer_id_banjarmasin) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat, kode_type, nama_type, kode_class, nama_class, group_class done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_banjarmasin('SSMS9', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_banjarmasin('SSMS9', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('SSMS9', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('SSMS9', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_banjarmasin('SSMS9', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }       

        $get_count = "select count(*) as count from management_raw.inner_raw_banjarmasin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'SSMS9')->row()->omzet;

        $update = [
            'count_mapping'  => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/banjarmasin');
    
    }

    public function proses_mapping_customer_banjarmasin(){

        $signature = $this->input->post('signature');
        $trim_customer_banjarmasin = $this->model_management_raw->trim_customer_banjarmasin($signature);

        if ($trim_customer_banjarmasin) {
            echo "<br><center><i>trimming kode_type, kode_class, customer_id, customer_id_nd6 done ... </i></b><br>";
            $update_type_n_class = $this->model_management_raw->update_type_n_class($signature);
        
            if ($update_type_n_class) {
                echo "<br><center><i>updating nama_type, sektor, segment, nama_class done ... </i></b><br>";
                
                $inner = $this->model_management_raw->inner_customer_banjarmasin($signature);
                if ($inner) {
                    
                    echo "<br><center><i>deleting redundance customer_id_nd5 done ... </i></b><br>";
                    $get_count = "select count(*) as count from management_raw.inner_raw_customer_banjarmasin a where a.signature = '$signature'";
                    $count = $this->db->query($get_count)->row()->count;

                    $update = [
                        'count_mapping'  => $count
                    ];
                    $this->db->where('signature', $signature);
                    $this->db->update('management_raw.log_upload', $update);

                    echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
                    header('Refresh: 5; URL='.base_url().'management_raw/customer_banjarmasin');

                }

            }
        }
    }
    
    

    public function download_raw($site_code, $signature){

        if ($site_code == 'SSJD2') {
            $params = "inner_raw_batulicin";
            $title = "batulicin";
        }elseif ($site_code == 'SSMS9') {
            $params = "inner_raw_banjarmasin";
            $title = "banjarmasin";
        }

        $query = "
            select *
            from management_raw.$params a 
            where a.signature = '$signature'
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,"raw $title already mapping.csv");
    }
    

    public function download_customer_banjarmasin($signature){
        $query = "
            select *
            from management_raw.inner_raw_customer_banjarmasin a 
            where a.signature = '$signature'
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'customer_banjarmasin_already_mapping.csv');
    }


    public function batulicin(){

        $data = [
            'title'     => 'Management Raw / Import data batulicin',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_batulicin',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('SSJD2')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/batulicin', $data);
        $this->load->view('mes/footer');
    }

    public function template_batulicin(){        
        $query = "
            select  '' as distributor, '' as cabang, '' as tipetrans, '' as divisi, '' as principal, '' as productgroup1, '' as productgroup2, '' as productgroup3, '' as brand, '' as kodeproduk, '' as kodevarian, '' as kodeprodukprincipal, '' as namaproduk, '' as packaging, '' as productclass, '' as kodecustomer,'' as namacustomer, '' as alamatcustomer, '' as area, '' as subarea, '' as channel, '' as subchannel, '' as customergroup, '' as keyaccount, '' as kodesalesman, '' as namasalesman, '' as kodesalesco, '' as namasalesco, '' as kodespv, '' as namaspv, '' as tahunbulan, '' as bulan, '' as tanggal, '' as weekno, '' as nomornota, '' as salesmethod, '' as sellingtype, '' as qtysold, '' as kartonutuh, '' as qtysoldpcs, '' as freegoodpcs, '' as tonnage, '' as volumeltr, '' as grossamount, '' as linediscount1, '' as linediscount2, '' as linediscount3, '' as linediscount4, '' as linediscount5, '' as totallinediscount, '' as discountnota1, '' as discountnota2, '' as discountnota3, '' as totaldiscountnota, '' as dpp, '' as ppn, '' as ppnbm, '' as tax3, '' as netamount, '' as warehouse, '' as customerpo, '' as customerjoindate, '' as nofakturpajak, '' as tglfakturpajak, '' as nomorfakturproforma, '' as tglfakturproforma, '' as cogs, '' as caseweightinkg, '' as end
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Batulicin.csv');
    }

    public function import_batulicin(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 624 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/batulicin');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/batulicin');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $distributor = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $cabang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tipetrans = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $divisi = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $principal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $productgroup1 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $productgroup2 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $productgroup3 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $brand = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $kodeproduk = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kodevarian = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kodeprodukprincipal = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $namaproduk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $packaging = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $productclass = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $kodecustomer = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $namacustomer = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $alamatcustomer = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $area = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $channel = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $subchannel = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $customergroup = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $keyaccount = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $kodesalesman = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $namasalesman = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $kodesalesco = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $namasalesco = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $kodespv = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $namaspv = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $tahunbulan = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $bulan = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $weekno = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $nomornota = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $salesmethod = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $sellingtype = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $qtysold = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $kartonutuh = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $qtysoldpcs = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $freegoodpcs = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $tonnage = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $volumeltr = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $grossamount = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $linediscount1 = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $linediscount2 = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $linediscount3 = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $linediscount4 = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $linediscount5 = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $totallinediscount = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $discountnota1 = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $discountnota2 = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $discountnota3 = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $totaldiscountnota = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $dpp = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $ppn = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $ppnbm = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $tax3 = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $netamount = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $warehouse = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $customerpo = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $customerjoindate = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $nofakturpajak = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $tglfakturpajak = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $nomorfakturproforma = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $tglfakturproforma = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $cogs = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $caseweightinkg = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $end = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                                        

                    $data = [
                        'distributor'      => $distributor,
                        'cabang'      => $cabang,
                        'tipetrans'      => $tipetrans,
                        'divisi'      => $divisi,
                        'principal'      => $principal,
                        'productgroup1'      => $productgroup1,
                        'productgroup2'      => $productgroup2,
                        'productgroup3'      => $productgroup3,
                        'brand'      => $brand,
                        'kodeproduk'      => $kodeproduk,
                        'kodevarian'      => $kodevarian,
                        'kodeprodukprincipal'      => $kodeprodukprincipal,
                        'namaproduk'      => $namaproduk,
                        'packaging'      => $packaging,
                        'productclass'      => $productclass,
                        'kodecustomer'      => $kodecustomer,
                        'namacustomer'      => $namacustomer,
                        'alamatcustomer'      => $alamatcustomer,
                        'area'      => $area,
                        'subarea'      => $subarea,
                        'channel'      => $channel,
                        'subchannel'      => $subchannel,
                        'customergroup'      => $customergroup,
                        'keyaccount'      => $keyaccount,
                        'kodesalesman'      => $kodesalesman,
                        'namasalesman'      => $namasalesman,
                        'kodesalesco'      => $kodesalesco,
                        'namasalesco'      => $namasalesco,
                        'kodespv'      => $kodespv,
                        'namaspv'      => $namaspv,
                        'tahunbulan'      => $tahunbulan,
                        'bulan'      => $bulan,
                        'tanggal'      => $tanggal,
                        'weekno'      => $weekno,
                        'nomornota'      => $nomornota,
                        'salesmethod'      => $salesmethod,
                        'sellingtype'      => $sellingtype,
                        'qtysold'      => $qtysold,
                        'kartonutuh'      => $kartonutuh,
                        'qtysoldpcs'      => $qtysoldpcs,
                        'freegoodpcs'      => $freegoodpcs,
                        'tonnage'      => $tonnage,
                        'volumeltr'      => $volumeltr,
                        'grossamount'      => $grossamount,
                        'linediscount1'      => $linediscount1,
                        'linediscount2'      => $linediscount2,
                        'linediscount3'      => $linediscount3,
                        'linediscount4'      => $linediscount4,
                        'linediscount5'      => $linediscount5,
                        'totallinediscount'      => $totallinediscount,
                        'discountnota1'      => $discountnota1,
                        'discountnota2'      => $discountnota2,
                        'discountnota3'      => $discountnota3,
                        'totaldiscountnota'      => $totaldiscountnota,
                        'dpp'      => $dpp,
                        'ppn'      => $ppn,
                        'ppnbm'      => $ppnbm,
                        'tax3'      => $tax3,
                        'netamount'      => $netamount,
                        'warehouse'      => $warehouse,
                        'customerpo'      => $customerpo,
                        'nofakturpajak'      => $nofakturpajak,
                        'customerjoindate'      => $customerjoindate,
                        'tglfakturpajak'      => $tglfakturpajak,
                        'nomorfakturproforma'      => $nomorfakturproforma,
                        'tglfakturproforma'      => $tglfakturproforma,
                        'cogs'      => $cogs,
                        'caseweightinkg'      => $caseweightinkg,
                        'end'      => $end,
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_batulicin',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_batulicin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select sum(a.grossamount * 1.11) as omzet_raw from management_raw.raw_batulicin a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'SSJD2',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_batulicin_draft/'.$signature);
    }

    public function import_batulicin_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data batulicin',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('SSJD2', $signature),
            'url'=> 'management_raw/proses_mapping_batulicin',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales('SSJD2', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_batulicin', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_batulicin(){

        $signature = $this->input->post('signature');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk('SSJD2', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk('SSJD2', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod('SSJD2', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('SSJD2', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('SSJD2', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $update_inner_customer_id_batulicin = $this->model_management_raw->update_inner_customer_id_batulicin($signature);
        if ($update_inner_customer_id_batulicin) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat done ...</i></b><br>";
        }

        $update_master_customer_batulicin = $this->model_management_raw->update_master_customer_batulicin($signature);
        if ($update_master_customer_batulicin) {
            echo "<br><center><i>penambahan master customer batulicin done ...</i></b><br>";
        }else{
            echo "<br><center><i>tidak ada penambahan master customer batulicin ...</i></b><br>";
        }

        $update_inner_customer_id_batulicin = $this->model_management_raw->update_inner_customer_id_batulicin($signature);
        if ($update_inner_customer_id_batulicin) {
            echo "<br><center><i>updating ulang customer_id, nama_customer, alamat done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('ssjd2', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_batulicin('ssjd2', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_batulicin('ssjd2', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('ssjd2', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('ssjd2', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_batulicin('ssjd2', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_batulicin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'SSJD2')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('SSJD2', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/batulicin');    
    }

    public function proses_mapping_barabai(){

        $signature = $this->input->post('signature');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_left('BRBS0', $signature);
        if ($update_kodeproduk) {
            echo "<br><center><i>fix kodeproduk dan kodeprodukprincipal ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_barabai('BRBS0', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod('BRBS0', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }
        
        $update_branch = $this->model_management_raw->update_branch('BRBS0', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('BRBS0', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('BRBS0', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        // update customer, type, class, dll

        $update_inner_customer_id_barabai = $this->model_management_raw->update_inner_customer_id_barabai($signature);           
        if ($update_inner_customer_id_barabai) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat, kode_type, nama_type, kode_class, nama_class, group_class done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_barabai('BRBS0', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_barabai('BRBS0', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('BRBS0', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('BRBS0', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_banjarmasin('BRBS0', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_barabai a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;
        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'BRBS0')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('BRBS0', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/barabai');    
        
        // die;
    
    }

    public function samarinda(){
        $data = [
            'title'     => 'Management Raw / Import data samarinda',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_samarinda',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('SMRB7')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/samarinda', $data);
        $this->load->view('mes/footer');
    }

    public function template_samarinda(){        
        $query = "
            select  '' as tipe_order, '' as status_cutomer, '' as group_divisi, '' as flag_sp_tt, '' as tipe, '' as status_site, '' as nama_branch, 
                    '' as siteid, '' as nama_site, '' as salesmanid, '' as nama_salesman, '' as status_faktur, '' as type_sales, '' as tipe_trans,
                    '' as categoryid, '' as nama_category, '' as productid, '' as nama_invoice, '' as ket, '' as qty_kecil, '' as qty_bonus, 
                    '' as rp_kotor, '' as rp_discount, '' as rp_netto, '' as brandid, '' as nama_brand, '' as varianid, '' as nama_varian, 
                    '' as groupid, '' as nama_group, '' as customerid, '' as nama_customer, '' as prefix, '' as alamat, '' as segmentid, '' as nama_segment,  
                    '' as typeid, '' as nama_type, '' as group1, '' as propinsiid, '' as nama_propinsi, '' as regionalid, '' as nama_regional,
                    '' as areaid, '' as nama_area, '' as no_sales, '' as ref, '' as tgl_po, '' as tanggal, '' as month, '' as year, '' as classid,
                    '' as nama_class, '' as kotaid, '' as nama_kota, '' as kecamatanid, '' as nama_kecamatan, '' as nama_kelurahan, '' as kelurahanid,
                    '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra, '' as disc_cash, '' as subareaid, '' as subarea, '' as keterangan, 
                    '' as areaid2, '' as nama_area2, '' as spot_id, '' as nama_spot, '' as subareaid2, '' as subarea2, '' as regionalid2, '' as nama_regional2, 
                    '' as status_outlet, '' as rp_net_reguler, '' as rp_net_ritel, '' as rp_net_selisih, '' as rp_net_ritel_motoris, '' as tipe_trans1, 
                    '' as qty_kecil_crt, '' as qty_bonus_crt, '' as latitude, '' as longitude, '' as tanggal_fjp, '' as status_ob 
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_samarinda.csv');
    }

    public function import_samarinda(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 620 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/samarinda');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/samarinda');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $tipe_order = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $status_cutomer = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $group_divisi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $flag_sp_tt = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $tipe = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $status_site = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $nama_branch = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $siteid = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $nama_site = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $salesmanid = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $nama_salesman = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $status_faktur = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $type_sales = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $tipe_trans = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $categoryid = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $nama_category = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $productid = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $nama_invoice = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $ket = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $qty_kecil = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $qty_bonus = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $rp_kotor = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $rp_discount = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $rp_netto = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $brandid = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $nama_brand = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $varianid = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $nama_varian = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $groupid = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $nama_group = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $customerid = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $nama_customer = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $prefix = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $segmentid = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $nama_segment = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $typeid = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $nama_type = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $group1 = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $propinsiid = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $nama_propinsi = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $regionalid = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $nama_regional = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $areaid = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $nama_area = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $no_sales = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $ref = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $tgl_po = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $month = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $year = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $classid = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $nama_class = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $kotaid = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $nama_kota = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $kecamatanid = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $kelurahanid = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $disc_cabang = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $disc_prinsipal = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $disc_xtra = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $disc_cash = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $subareaid = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $keterangan = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $areaid2 = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $nama_area2 = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $spot_id = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                    $nama_spot = $worksheet->getCellByColumnAndRow(69, $row)->getValue();
                    $subareaid2 = $worksheet->getCellByColumnAndRow(70, $row)->getValue();
                    $subarea2 = $worksheet->getCellByColumnAndRow(71, $row)->getValue();
                    $regionalid2 = $worksheet->getCellByColumnAndRow(72, $row)->getValue();
                    $nama_regional2 = $worksheet->getCellByColumnAndRow(73, $row)->getValue();
                    $status_outlet = $worksheet->getCellByColumnAndRow(74, $row)->getValue();
                    $rp_net_reguler = $worksheet->getCellByColumnAndRow(75, $row)->getValue();
                    $rp_net_ritel = $worksheet->getCellByColumnAndRow(76, $row)->getValue();
                    $rp_net_selisih = $worksheet->getCellByColumnAndRow(77, $row)->getValue();
                    $rp_net_ritel_motoris = $worksheet->getCellByColumnAndRow(78, $row)->getValue();
                    $tipe_trans1 = $worksheet->getCellByColumnAndRow(79, $row)->getValue();
                    $qty_kecil_crt = $worksheet->getCellByColumnAndRow(80, $row)->getValue();
                    $qty_bonus_crt = $worksheet->getCellByColumnAndRow(81, $row)->getValue();
                    $latitude = $worksheet->getCellByColumnAndRow(82, $row)->getValue();
                    $longitude = $worksheet->getCellByColumnAndRow(83, $row)->getValue();
                    $tanggal_fjp = $worksheet->getCellByColumnAndRow(84, $row)->getValue();
                    $status_ob = $worksheet->getCellByColumnAndRow(85, $row)->getValue();
                        
                    $data = [
                        'tipe_order'      => $tipe_order,
                        'status_cutomer'      => $status_cutomer,
                        'group_divisi'      => $group_divisi,
                        'flag_sp_tt'      => $flag_sp_tt,
                        'tipe'      => $tipe,
                        'status_site'      => $status_site,
                        'nama_branch'      => $nama_branch,
                        'siteid'      => $siteid,
                        'nama_site'      => $nama_site,
                        'salesmanid'      => $salesmanid,
                        'nama_salesman'      => $nama_salesman,
                        'status_faktur'      => $status_faktur,
                        'type_sales'      => $type_sales,
                        'tipe_trans'      => $tipe_trans,
                        'categoryid'      => $categoryid,
                        'nama_category'      => $nama_category,
                        'productid'      => $productid,
                        'nama_invoice'      => $nama_invoice,
                        'ket'      => $ket,
                        'qty_kecil'      => $qty_kecil,
                        'qty_bonus'      => $qty_bonus,
                        'rp_kotor'      => $rp_kotor,
                        'rp_discount'      => $rp_discount,
                        'rp_netto'      => $rp_netto,
                        'brandid'      => $brandid,
                        'nama_brand'      => $nama_brand,
                        'varianid'      => $varianid,
                        'nama_varian'      => $nama_varian,
                        'groupid'      => $groupid,
                        'nama_group'      => $nama_group,
                        'customerid'      => $customerid,
                        'nama_customer'      => $nama_customer,
                        'prefix'      => $prefix,
                        'alamat'      => $alamat,
                        'segmentid'      => $segmentid,
                        'nama_segment'      => $nama_segment,
                        'typeid'      => $typeid,
                        'nama_type'      => $nama_type,
                        'group1'      => $group1,
                        'propinsiid'      => $propinsiid,
                        'nama_propinsi'      => $nama_propinsi,
                        'regionalid'      => $regionalid,
                        'nama_regional'      => $nama_regional,
                        'areaid'      => $areaid,
                        'nama_area'      => $nama_area,
                        'no_sales'      => $no_sales,
                        'ref'      => $ref,
                        'tgl_po'      => $tgl_po,
                        'tanggal'      => $tanggal,
                        'month'      => $month,
                        'year'      => $year,
                        'classid'      => $classid,
                        'nama_class'      => $nama_class,
                        'kotaid'      => $kotaid,
                        'nama_kota'      => $nama_kota,
                        'kecamatanid'      => $kecamatanid,
                        'nama_kecamatan'      => $nama_kecamatan,
                        'nama_kelurahan'      => $nama_kelurahan,
                        'kelurahanid'      => $kelurahanid,
                        'disc_cabang'      => $disc_cabang,
                        'disc_prinsipal'      => $disc_prinsipal,
                        'disc_xtra'      => $disc_xtra,
                        'disc_cash'      => $disc_cash,
                        'subareaid'      => $subareaid,
                        'subarea'      => $subarea,
                        'keterangan'      => $keterangan,
                        'areaid2'      => $areaid2,
                        'nama_area2'      => $nama_area2,
                        'spot_id'      => $spot_id,
                        'nama_spot'      => $nama_spot,
                        'subareaid2'      => $subareaid2,
                        'subarea2'      => $subarea2,
                        'regionalid2'      => $regionalid2,
                        'nama_regional2'      => $nama_regional2,
                        'status_outlet'      => $status_outlet,
                        'rp_net_reguler'      => $rp_net_reguler,
                        'rp_net_ritel'      => $rp_net_ritel,
                        'rp_net_selisih'      => $rp_net_selisih,
                        'rp_net_ritel_motoris'      => $rp_net_ritel_motoris,
                        'tipe_trans1'      => $tipe_trans1,
                        'qty_kecil_crt'      => $qty_kecil_crt,
                        'qty_bonus_crt'      => $qty_bonus_crt,
                        'latitude'      => $latitude,
                        'longitude'      => $longitude,
                        'tanggal_fjp'      => $tanggal_fjp,
                        'status_ob'      => $status_ob,                        
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_samarinda',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_samarinda a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.rp_kotor) as omzet_raw from management_raw.raw_samarinda a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'SMRB7',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_samarinda_draft/'.$signature);
    }

    public function import_samarinda_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data samarinda',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('SMRB7', $signature),
            'url'=> 'management_raw/proses_mapping_samarinda',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales_samarinda('SMRB7', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_samarinda', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_samarinda(){

        $signature = $this->input->post('signature');

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_samarinda('SMRB7', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_samarinda('SMRB7', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('SMRB7', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('SMRB7', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('SMRB7', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_samarinda('SMRB7', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_samarinda('SMRB7', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('SMRB7', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('SMRB7', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_samarinda('SMRB7', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_samarinda a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'SMRB7')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('SMRB7', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/samarinda');    

    }

    public function bontang(){
        $data = [
            'title'     => 'Management Raw / Import data bontang',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_bontang',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('BTGB8')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/bontang', $data);
        $this->load->view('mes/footer');
    }

    public function template_bontang(){        
        $query = "
            select  '' as tipe_order, '' as status_cutomer, '' as group_divisi, '' as flag_sp_tt, '' as tipe, '' as status_site, '' as nama_branch, 
                    '' as siteid, '' as nama_site, '' as salesmanid, '' as nama_salesman, '' as status_faktur, '' as type_sales, '' as tipe_trans,
                    '' as categoryid, '' as nama_category, '' as productid, '' as nama_invoice, '' as ket, '' as qty_kecil, '' as qty_bonus, 
                    '' as rp_kotor, '' as rp_discount, '' as rp_netto, '' as brandid, '' as nama_brand, '' as varianid, '' as nama_varian, 
                    '' as groupid, '' as nama_group, '' as customerid, '' as nama_customer, '' as prefix, '' as alamat, '' as segmentid, '' as nama_segment,  
                    '' as typeid, '' as nama_type, '' as group1, '' as propinsiid, '' as nama_propinsi, '' as regionalid, '' as nama_regional,
                    '' as areaid, '' as nama_area, '' as no_sales, '' as ref, '' as tgl_po, '' as tanggal, '' as month, '' as year, '' as classid,
                    '' as nama_class, '' as kotaid, '' as nama_kota, '' as kecamatanid, '' as nama_kecamatan, '' as nama_kelurahan, '' as kelurahanid,
                    '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra, '' as disc_cash, '' as subareaid, '' as subarea, '' as keterangan, 
                    '' as areaid2, '' as nama_area2, '' as spot_id, '' as nama_spot, '' as subareaid2, '' as subarea2, '' as regionalid2, '' as nama_regional2, 
                    '' as status_outlet, '' as rp_net_reguler, '' as rp_net_ritel, '' as rp_net_selisih, '' as rp_net_ritel_motoris, '' as tipe_trans1, 
                    '' as qty_kecil_crt, '' as qty_bonus_crt, '' as latitude, '' as longitude, '' as tanggal_fjp, '' as status_ob 
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_bontang.csv');
    }

    public function import_bontang(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 621 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/bontang');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/bontang');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $tipe_order = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $status_cutomer = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $group_divisi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $flag_sp_tt = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $tipe = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $status_site = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $nama_branch = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $siteid = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $nama_site = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $salesmanid = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $nama_salesman = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $status_faktur = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $type_sales = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $tipe_trans = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $categoryid = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $nama_category = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $productid = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $nama_invoice = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $ket = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $qty_kecil = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $qty_bonus = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $rp_kotor = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $rp_discount = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $rp_netto = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $brandid = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $nama_brand = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $varianid = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $nama_varian = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $groupid = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $nama_group = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $customerid = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $nama_customer = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $prefix = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $segmentid = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $nama_segment = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $typeid = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $nama_type = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $group1 = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $propinsiid = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $nama_propinsi = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $regionalid = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $nama_regional = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $areaid = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $nama_area = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $no_sales = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $ref = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $tgl_po = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $month = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $year = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $classid = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $nama_class = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $kotaid = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $nama_kota = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $kecamatanid = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $kelurahanid = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $disc_cabang = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $disc_prinsipal = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $disc_xtra = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $disc_cash = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $subareaid = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $keterangan = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $areaid2 = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $nama_area2 = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $spot_id = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                    $nama_spot = $worksheet->getCellByColumnAndRow(69, $row)->getValue();
                    $subareaid2 = $worksheet->getCellByColumnAndRow(70, $row)->getValue();
                    $subarea2 = $worksheet->getCellByColumnAndRow(71, $row)->getValue();
                    $regionalid2 = $worksheet->getCellByColumnAndRow(72, $row)->getValue();
                    $nama_regional2 = $worksheet->getCellByColumnAndRow(73, $row)->getValue();
                    $status_outlet = $worksheet->getCellByColumnAndRow(74, $row)->getValue();
                    $rp_net_reguler = $worksheet->getCellByColumnAndRow(75, $row)->getValue();
                    $rp_net_ritel = $worksheet->getCellByColumnAndRow(76, $row)->getValue();
                    $rp_net_selisih = $worksheet->getCellByColumnAndRow(77, $row)->getValue();
                    $rp_net_ritel_motoris = $worksheet->getCellByColumnAndRow(78, $row)->getValue();
                    $tipe_trans1 = $worksheet->getCellByColumnAndRow(79, $row)->getValue();
                    $qty_kecil_crt = $worksheet->getCellByColumnAndRow(80, $row)->getValue();
                    $qty_bonus_crt = $worksheet->getCellByColumnAndRow(81, $row)->getValue();
                    $latitude = $worksheet->getCellByColumnAndRow(82, $row)->getValue();
                    $longitude = $worksheet->getCellByColumnAndRow(83, $row)->getValue();
                    $tanggal_fjp = $worksheet->getCellByColumnAndRow(84, $row)->getValue();
                    $status_ob = $worksheet->getCellByColumnAndRow(85, $row)->getValue();
                        
                    $data = [
                        'tipe_order'      => $tipe_order,
                        'status_cutomer'      => $status_cutomer,
                        'group_divisi'      => $group_divisi,
                        'flag_sp_tt'      => $flag_sp_tt,
                        'tipe'      => $tipe,
                        'status_site'      => $status_site,
                        'nama_branch'      => $nama_branch,
                        'siteid'      => $siteid,
                        'nama_site'      => $nama_site,
                        'salesmanid'      => $salesmanid,
                        'nama_salesman'      => $nama_salesman,
                        'status_faktur'      => $status_faktur,
                        'type_sales'      => $type_sales,
                        'tipe_trans'      => $tipe_trans,
                        'categoryid'      => $categoryid,
                        'nama_category'      => $nama_category,
                        'productid'      => $productid,
                        'nama_invoice'      => $nama_invoice,
                        'ket'      => $ket,
                        'qty_kecil'      => $qty_kecil,
                        'qty_bonus'      => $qty_bonus,
                        'rp_kotor'      => $rp_kotor,
                        'rp_discount'      => $rp_discount,
                        'rp_netto'      => $rp_netto,
                        'brandid'      => $brandid,
                        'nama_brand'      => $nama_brand,
                        'varianid'      => $varianid,
                        'nama_varian'      => $nama_varian,
                        'groupid'      => $groupid,
                        'nama_group'      => $nama_group,
                        'customerid'      => $customerid,
                        'nama_customer'      => $nama_customer,
                        'prefix'      => $prefix,
                        'alamat'      => $alamat,
                        'segmentid'      => $segmentid,
                        'nama_segment'      => $nama_segment,
                        'typeid'      => $typeid,
                        'nama_type'      => $nama_type,
                        'group1'      => $group1,
                        'propinsiid'      => $propinsiid,
                        'nama_propinsi'      => $nama_propinsi,
                        'regionalid'      => $regionalid,
                        'nama_regional'      => $nama_regional,
                        'areaid'      => $areaid,
                        'nama_area'      => $nama_area,
                        'no_sales'      => $no_sales,
                        'ref'      => $ref,
                        'tgl_po'      => $tgl_po,
                        'tanggal'      => $tanggal,
                        'month'      => $month,
                        'year'      => $year,
                        'classid'      => $classid,
                        'nama_class'      => $nama_class,
                        'kotaid'      => $kotaid,
                        'nama_kota'      => $nama_kota,
                        'kecamatanid'      => $kecamatanid,
                        'nama_kecamatan'      => $nama_kecamatan,
                        'nama_kelurahan'      => $nama_kelurahan,
                        'kelurahanid'      => $kelurahanid,
                        'disc_cabang'      => $disc_cabang,
                        'disc_prinsipal'      => $disc_prinsipal,
                        'disc_xtra'      => $disc_xtra,
                        'disc_cash'      => $disc_cash,
                        'subareaid'      => $subareaid,
                        'subarea'      => $subarea,
                        'keterangan'      => $keterangan,
                        'areaid2'      => $areaid2,
                        'nama_area2'      => $nama_area2,
                        'spot_id'      => $spot_id,
                        'nama_spot'      => $nama_spot,
                        'subareaid2'      => $subareaid2,
                        'subarea2'      => $subarea2,
                        'regionalid2'      => $regionalid2,
                        'nama_regional2'      => $nama_regional2,
                        'status_outlet'      => $status_outlet,
                        'rp_net_reguler'      => $rp_net_reguler,
                        'rp_net_ritel'      => $rp_net_ritel,
                        'rp_net_selisih'      => $rp_net_selisih,
                        'rp_net_ritel_motoris'      => $rp_net_ritel_motoris,
                        'tipe_trans1'      => $tipe_trans1,
                        'qty_kecil_crt'      => $qty_kecil_crt,
                        'qty_bonus_crt'      => $qty_bonus_crt,
                        'latitude'      => $latitude,
                        'longitude'      => $longitude,
                        'tanggal_fjp'      => $tanggal_fjp,
                        'status_ob'      => $status_ob,                        
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_bontang',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_bontang a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.rp_kotor) as omzet_raw from management_raw.raw_bontang a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'BTGB8',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_bontang_draft/'.$signature);
    }

    public function import_bontang_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data bontang',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('BTGB8', $signature),
            'url'=> 'management_raw/proses_mapping_bontang',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales_samarinda('BTGB8', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_bontang', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_bontang(){

        $signature = $this->input->post('signature');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_bontang('BTGB8', $signature);
        if ($update_kodeproduk) {            
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }
        
        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_samarinda('BTGB8', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_samarinda('BTGB8', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('BTGB8', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('BTGB8', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('BTGB8', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_samarinda('BTGB8', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_samarinda('BTGB8', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('BTGB8', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('BTGB8', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_samarinda('BTGB8', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_bontang a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'BTGB8')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('BTGB8', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/bontang');    

    }

    public function pontianak(){
        $data = [
            'title'     => 'Management Raw / Import data pontianak',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_pontianak',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('PTK82')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/pontianak', $data);
        $this->load->view('mes/footer');
    }

    public function template_pontianak(){        
        $query = "
            select  '' as tipe_order, '' as status_cutomer, '' as group_divisi, '' as flag_sp_tt, '' as tipe, '' as status_site, '' as nama_branch, 
                    '' as siteid, '' as nama_site, '' as salesmanid, '' as nama_salesman, '' as status_faktur, '' as type_sales, '' as tipe_trans,
                    '' as categoryid, '' as nama_category, '' as productid, '' as nama_invoice, '' as ket, '' as qty_kecil, '' as qty_bonus, 
                    '' as rp_kotor, '' as rp_discount, '' as rp_netto, '' as brandid, '' as nama_brand, '' as varianid, '' as nama_varian, 
                    '' as groupid, '' as nama_group, '' as customerid, '' as nama_customer, '' as prefix, '' as alamat, '' as segmentid, '' as nama_segment,  
                    '' as typeid, '' as nama_type, '' as group1, '' as propinsiid, '' as nama_propinsi, '' as regionalid, '' as nama_regional,
                    '' as areaid, '' as nama_area, '' as no_sales, '' as ref, '' as tgl_po, '' as tanggal, '' as month, '' as year, '' as classid,
                    '' as nama_class, '' as kotaid, '' as nama_kota, '' as kecamatanid, '' as nama_kecamatan, '' as nama_kelurahan, '' as kelurahanid,
                    '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra, '' as disc_cash, '' as subareaid, '' as subarea, '' as keterangan, 
                    '' as areaid2, '' as nama_area2, '' as spot_id, '' as nama_spot, '' as subareaid2, '' as subarea2, '' as regionalid2, '' as nama_regional2, 
                    '' as status_outlet, '' as rp_net_reguler, '' as rp_net_ritel, '' as rp_net_selisih, '' as rp_net_ritel_motoris, '' as tipe_trans1, 
                    '' as qty_kecil_crt, '' as qty_bonus_crt, '' as latitude, '' as longitude, '' as tanggal_fjp, '' as status_ob 
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_pontianak.csv');
    }

    public function import_pontianak(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 30 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/pontianak');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/pontianak');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $tipe_order = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $status_cutomer = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $group_divisi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $flag_sp_tt = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $tipe = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $status_site = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $nama_branch = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $siteid = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $nama_site = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $salesmanid = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $nama_salesman = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $status_faktur = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $type_sales = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $tipe_trans = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $categoryid = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $nama_category = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $productid = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $nama_invoice = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $ket = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $qty_kecil = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $qty_bonus = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $rp_kotor = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $rp_discount = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $rp_netto = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $brandid = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $nama_brand = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $varianid = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $nama_varian = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $groupid = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $nama_group = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $customerid = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $nama_customer = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $prefix = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $segmentid = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $nama_segment = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $typeid = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $nama_type = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $group1 = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $propinsiid = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $nama_propinsi = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $regionalid = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $nama_regional = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $areaid = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $nama_area = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $no_sales = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $ref = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $tgl_po = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $month = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $year = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $classid = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $nama_class = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $kotaid = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $nama_kota = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $kecamatanid = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $kelurahanid = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $disc_cabang = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $disc_prinsipal = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $disc_xtra = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $disc_cash = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $subareaid = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $keterangan = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $areaid2 = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $nama_area2 = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $spot_id = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                    $nama_spot = $worksheet->getCellByColumnAndRow(69, $row)->getValue();
                    $subareaid2 = $worksheet->getCellByColumnAndRow(70, $row)->getValue();
                    $subarea2 = $worksheet->getCellByColumnAndRow(71, $row)->getValue();
                    $regionalid2 = $worksheet->getCellByColumnAndRow(72, $row)->getValue();
                    $nama_regional2 = $worksheet->getCellByColumnAndRow(73, $row)->getValue();
                    $status_outlet = $worksheet->getCellByColumnAndRow(74, $row)->getValue();
                    $rp_net_reguler = $worksheet->getCellByColumnAndRow(75, $row)->getValue();
                    $rp_net_ritel = $worksheet->getCellByColumnAndRow(76, $row)->getValue();
                    $rp_net_selisih = $worksheet->getCellByColumnAndRow(77, $row)->getValue();
                    $rp_net_ritel_motoris = $worksheet->getCellByColumnAndRow(78, $row)->getValue();
                    $tipe_trans1 = $worksheet->getCellByColumnAndRow(79, $row)->getValue();
                    $qty_kecil_crt = $worksheet->getCellByColumnAndRow(80, $row)->getValue();
                    $qty_bonus_crt = $worksheet->getCellByColumnAndRow(81, $row)->getValue();
                    $latitude = $worksheet->getCellByColumnAndRow(82, $row)->getValue();
                    $longitude = $worksheet->getCellByColumnAndRow(83, $row)->getValue();
                    $tanggal_fjp = $worksheet->getCellByColumnAndRow(84, $row)->getValue();
                    $status_ob = $worksheet->getCellByColumnAndRow(85, $row)->getValue();
                        
                    $data = [
                        'tipe_order'      => $tipe_order,
                        'status_cutomer'      => $status_cutomer,
                        'group_divisi'      => $group_divisi,
                        'flag_sp_tt'      => $flag_sp_tt,
                        'tipe'      => $tipe,
                        'status_site'      => $status_site,
                        'nama_branch'      => $nama_branch,
                        'siteid'      => $siteid,
                        'nama_site'      => $nama_site,
                        'salesmanid'      => $salesmanid,
                        'nama_salesman'      => $nama_salesman,
                        'status_faktur'      => $status_faktur,
                        'type_sales'      => $type_sales,
                        'tipe_trans'      => $tipe_trans,
                        'categoryid'      => $categoryid,
                        'nama_category'      => $nama_category,
                        'productid'      => $productid,
                        'nama_invoice'      => $nama_invoice,
                        'ket'      => $ket,
                        'qty_kecil'      => $qty_kecil,
                        'qty_bonus'      => $qty_bonus,
                        'rp_kotor'      => $rp_kotor,
                        'rp_discount'      => $rp_discount,
                        'rp_netto'      => $rp_netto,
                        'brandid'      => $brandid,
                        'nama_brand'      => $nama_brand,
                        'varianid'      => $varianid,
                        'nama_varian'      => $nama_varian,
                        'groupid'      => $groupid,
                        'nama_group'      => $nama_group,
                        'customerid'      => $customerid,
                        'nama_customer'      => $nama_customer,
                        'prefix'      => $prefix,
                        'alamat'      => $alamat,
                        'segmentid'      => $segmentid,
                        'nama_segment'      => $nama_segment,
                        'typeid'      => $typeid,
                        'nama_type'      => $nama_type,
                        'group1'      => $group1,
                        'propinsiid'      => $propinsiid,
                        'nama_propinsi'      => $nama_propinsi,
                        'regionalid'      => $regionalid,
                        'nama_regional'      => $nama_regional,
                        'areaid'      => $areaid,
                        'nama_area'      => $nama_area,
                        'no_sales'      => $no_sales,
                        'ref'      => $ref,
                        'tgl_po'      => $tgl_po,
                        'tanggal'      => $tanggal,
                        'month'      => $month,
                        'year'      => $year,
                        'classid'      => $classid,
                        'nama_class'      => $nama_class,
                        'kotaid'      => $kotaid,
                        'nama_kota'      => $nama_kota,
                        'kecamatanid'      => $kecamatanid,
                        'nama_kecamatan'      => $nama_kecamatan,
                        'nama_kelurahan'      => $nama_kelurahan,
                        'kelurahanid'      => $kelurahanid,
                        'disc_cabang'      => $disc_cabang,
                        'disc_prinsipal'      => $disc_prinsipal,
                        'disc_xtra'      => $disc_xtra,
                        'disc_cash'      => $disc_cash,
                        'subareaid'      => $subareaid,
                        'subarea'      => $subarea,
                        'keterangan'      => $keterangan,
                        'areaid2'      => $areaid2,
                        'nama_area2'      => $nama_area2,
                        'spot_id'      => $spot_id,
                        'nama_spot'      => $nama_spot,
                        'subareaid2'      => $subareaid2,
                        'subarea2'      => $subarea2,
                        'regionalid2'      => $regionalid2,
                        'nama_regional2'      => $nama_regional2,
                        'status_outlet'      => $status_outlet,
                        'rp_net_reguler'      => $rp_net_reguler,
                        'rp_net_ritel'      => $rp_net_ritel,
                        'rp_net_selisih'      => $rp_net_selisih,
                        'rp_net_ritel_motoris'      => $rp_net_ritel_motoris,
                        'tipe_trans1'      => $tipe_trans1,
                        'qty_kecil_crt'      => $qty_kecil_crt,
                        'qty_bonus_crt'      => $qty_bonus_crt,
                        'latitude'      => $latitude,
                        'longitude'      => $longitude,
                        'tanggal_fjp'      => $tanggal_fjp,
                        'status_ob'      => $status_ob,                        
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_pontianak',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_pontianak a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.rp_kotor) as omzet_raw from management_raw.raw_pontianak a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'PTK82',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_pontianak_draft/'.$signature);
    }

    public function import_pontianak_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data pontianak',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('PTK82', $signature),
            'url'=> 'management_raw/proses_mapping_pontianak',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales_samarinda('PTK82', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_pontianak', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_pontianak(){

        $signature = $this->input->post('signature');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_bontang('PTK82', $signature);
        if ($update_kodeproduk) {            
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }
        
        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_samarinda('PTK82', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_samarinda('PTK82', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('PTK82', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('PTK82', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('PTK82', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_samarinda('PTK82', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_samarinda('PTK82', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('PTK82', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('PTK82', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_samarinda('PTK82', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_pontianak a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'PTK82')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('PTK82', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/pontianak');    

    }

    public function kendari(){

        $data = [
            'title' => 'Management Raw / Import data kendari',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_kendari',
            'get_log_upload' => $this->model_management_raw->get_log_upload('CSSK3')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/kendari', $data);
        $this->load->view('mes/footer');
    }

    public function import_kendari(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 382 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/kendari');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/kendari');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {         
                    $KDDOKJDI = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $NODOKJDI = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $TGLDOKJDI = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $NODOKACU = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $KODEPROD = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $NAMAPROD = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $AREA = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $NAMAAREA = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $KODEJAJA = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $NAMAJAJA = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $KODELANG = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $NAMALANG = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $TYPELANG = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $DESCLANG = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $CLASS = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $ALMTLANG = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $BANYAK = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $BANYS = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $HNA = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $POTONGAN = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $TOTHNA = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                                        

                    $data = [
                        'KDDOKJDI' => $KDDOKJDI,
                        'NODOKJDI' => $NODOKJDI,
                        'TGLDOKJDI' => $TGLDOKJDI,
                        'NODOKACU' => $NODOKACU,
                        'KODEPROD' => $KODEPROD,
                        'NAMAPROD' => $NAMAPROD,
                        'AREA' => $AREA,
                        'NAMAAREA' => $NAMAAREA,
                        'KODEJAJA' => $KODEJAJA,
                        'NAMAJAJA' => $NAMAJAJA,
                        'KODELANG' => $KODELANG,
                        'NAMALANG' => $NAMALANG,
                        'TYPELANG' => $TYPELANG,
                        'DESCLANG' => $DESCLANG,
                        'CLASS' => $CLASS,
                        'ALMTLANG' => $ALMTLANG,
                        'BANYAK' => $BANYAK,
                        'BANYS' => $BANYS,
                        'HNA' => $HNA*1.11,
                        'POTONGAN' => $POTONGAN*1.11,
                        'TOTHNA' => $TOTHNA,
                        'SIGNATURE' => $signature,
                        'CREATED_AT' => $created_at,
                        'CREATED_BY' => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_kendari',$data);
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_kendari a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select sum(a.tothna * 1.11) as omzet_raw from management_raw.raw_kendari a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'CSSK3',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_kendari_draft/'.$signature);
    }
    
    public function import_kendari_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data kendari',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('CSSK3', $signature),
            'url'=> 'management_raw/proses_mapping_kendari',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_kendari', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_kendari(){

        $signature = $this->input->post('signature');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_kendari('CSSK3', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_kendari('CSSK3', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_kendari('CSSK3', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('CSSK3', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_kendari('CSSK3', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        // $update_inner_customer_id_kendari = $this->model_management_raw->update_inner_customer_id_kendari($signature);
        // if ($update_inner_customer_id_kendari) {
        //     echo "<br><center><i>updating customer_id, nama_customer, alamat done ...</i></b><br>";
        // }

        // $update_master_customer_kendari = $this->model_management_raw->update_master_customer_kendari($signature);
        // if ($update_master_customer_kendari) {
        //     echo "<br><center><i>penambahan master customer kendari done ...</i></b><br>";
        // }else{
        //     echo "<br><center><i>tidak ada penambahan master customer kendari ...</i></b><br>";
        // }

        // $update_inner_customer_id_kendari = $this->model_management_raw->update_inner_customer_id_kendari($signature);
        // if ($update_inner_customer_id_kendari) {
        //     echo "<br><center><i>updating ulang customer_id, nama_customer, alamat done ...</i></b><br>";
        // }

        $delete_tabel = $this->model_management_raw->delete_tabel('CSSK3', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_kendari('CSSK3', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_kendari('CSSK3', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('CSSK3', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('CSSK3', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_kendari('CSSK3', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_kendari a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'CSSK3')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_kendari('CSSK3', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/kendari');    
    }

    public function pangkalanbun(){
        $data = [
            'title'     => 'Management Raw / Import data pangkalan bun',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_pangkalanbun',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('PBNP9')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/pangkalanbun', $data);
        $this->load->view('mes/footer');
    }

    public function template_pangkalanbun(){        
        $query = "
            select  '' as nomor, '' as tanggal, '' as id_karyawan_tenaga, '' as nama_tenaga_penjual, '' as id_pelanggan, '' as blank_1, '' as blank_2, 
                    '' as nama_pelanggan, '' as kode, '' as nama_barang, '' as harga, '' as kuantitas, '' as blank_3, '' as satuan,
                    '' as kts, '' as diskon, '' as diskon_percent, '' as default_diskon, '' as penjualan
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_pangkalanbun.csv');
    }

    public function import_pangkalanbun(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 545 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/pangkalanbun');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/pangkalanbun');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row < $highestRow; $row++) {          
                
                    $nomor = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $id_karyawan_tenaga = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $nama_tenaga_penjual = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $id_pelanggan = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $blank_1 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $blank_2 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $nama_pelanggan = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $kode = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $nama_barang = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $harga = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kuantitas = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $blank_3 = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $satuan = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $kts = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $diskon = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $diskon_percent = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $default_diskon = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $penjualan = $worksheet->getCellByColumnAndRow(18, $row)->getValue();

                    // echo "<pre>";
                    // echo "penjualan : ".$penjualan;
                    // echo "<br>";
                    // echo "</pre>";
                        
                    $data = [
                        'nomor'      => $nomor,
                        'tanggal'      => $tanggal,
                        'id_karyawan_tenaga'      => $id_karyawan_tenaga,
                        'nama_tenaga_penjual'      => $nama_tenaga_penjual,
                        'id_pelanggan'      => $id_pelanggan,
                        'blank_1'      => $blank_1,
                        'blank_2'      => $blank_2,
                        'nama_pelanggan'      => $nama_pelanggan,
                        'kode'      => $kode,
                        'nama_barang'      => $nama_barang,
                        'harga'      => $harga,
                        'kuantitas'      => $kuantitas,
                        'blank_3'      => $blank_3,
                        'satuan'      => $satuan,
                        'diskon'      => $diskon,
                        'kts'      => $kts,
                        'diskon_percent'      => $diskon_percent,
                        'default_diskon'      => $default_diskon,
                        'penjualan'      => $penjualan,                
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_pangkalanbun',$data);
                }
            }
        }else{
           
        };

        $query_fix_penjualan = "
            update management_raw.raw_pangkalanbun a
            set a.penjualan = replace(replace(a.penjualan,',', ''),'.00','')
            where a.signature = '$signature'
        ";
        $this->db->query($query_fix_penjualan);

        $get_count = "select count(*) as count from management_raw.raw_pangkalanbun a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.penjualan) as omzet_raw from management_raw.raw_pangkalanbun a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'PBNP9',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_pangkalanbun_draft/'.$signature);
    }

    public function import_pangkalanbun_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data pangkalanbun',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('PBNP9', $signature),
            'url'=> 'management_raw/proses_mapping_pangkalanbun',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales_pangkalanbun('PBNP9', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_pangkalanbun', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_pangkalanbun(){

        $signature = $this->input->post('signature');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_pangkalanbun('PBNP9', $signature);
        if ($update_kodeproduk) {            
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        die;
        
        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_samarinda('BTGB8', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_samarinda('BTGB8', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('BTGB8', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('BTGB8', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('BTGB8', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_samarinda('BTGB8', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_samarinda('BTGB8', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('BTGB8', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('BTGB8', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_samarinda('BTGB8', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_bontang a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'BTGB8')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('BTGB8', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/pangkalanbun');    

    }

}
?>
