<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bridging extends MY_Controller
{    
    function bridging()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_bridging'));
        $this->userid = $this->session->userdata('id');
        $this->username = $this->session->userdata('username');
        $this->tahun_folder = date('Y');
    }

    function index()
    {
        $this->dashboard();
    }

    function navbar($data)
    {
        // echo "level : ".$this->session->userdata('level');
        if ($this->session->userdata('level') === '4') { // jika dp
            $this->load->view('management_office/top_header_dp', $data);
        }elseif ($this->session->userdata('level') === '3') { // jika principal
            $this->load->view('management_office/top_header_principal', $data);
        }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales 
            $this->load->view('management_office/top_header_principal_nosales', $data);
        }elseif ($this->session->userdata('level') === "3b") { // jika principal hanya raw data, claim, rpd 
            $this->load->view('management_office/top_header_principal_rawdata', $data);
        }elseif ($this->session->userdata('level') === "3c") { // jika principal raw_data dan retur dan rpd = RSPH = ghozali yoseph sudarsono
            $this->load->view('management_office/top_header_principal_rawdata_retur', $data);
        }elseif ($this->session->userdata('level') === "3d") { // jika principal rpd
            $this->load->view('management_office/top_header_principal_rpd', $data);
        }elseif ($this->session->userdata('level') === '5') { // jika dp mpi
            $this->load->view('management_office/top_header_dp_mpi', $data);
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function dashboard()
    {
        $data = [
            "title" => "Bridging",
            'url'   => 'bridging/dashboard',
            'site_code' => $this->model_bridging->get_bridging_list_subbranch(),
        ];
        $this->view($data, false, "dashboard");
    }

    public function routing($signature)
    {
        if(!isset($signature) || $signature == "") {
            redirect('bridging/dashboard','refresh');
        }

        $site_code = $this->model_bridging->get_bridging_list_subbranch($signature)->row();
        if(!$site_code) {
            redirect('bridging/dashboard','refresh');
        }

        if($site_code->site_code == 'SMB2C')
        {
            redirect('bridging/bontang','refresh');
        }   

        if($site_code->site_code == 'SAM2A')
        {
            redirect('bridging/samarinda','refresh');
        }  
        
        if($site_code->site_code == 'KLK1V')
        {
            redirect('bridging/kolaka','refresh');
        }  

        if($site_code->site_code == 'PUM1N')
        {
            redirect('bridging/kendari','refresh');
        }
        
        if($site_code->site_code == 'BBU1W')
        {
            redirect('bridging/baubau','refresh');
        }

        if($site_code->site_code == 'MMS1O')
        {
            redirect('bridging/mms_makasar','refresh');
        }
        
        if($site_code->site_code == 'BNE1T')
        {
            redirect('bridging/mms_bone','refresh');
        }

        if($site_code->site_code == 'PRE1U')
        {
            redirect('bridging/mms_parepare','refresh');
        }

        if($site_code->site_code == 'PKB51')
        {
            redirect('bridging/pekanbaru','refresh');
        }

        if($site_code->site_code == 'SUP2G')
        {
            redirect('bridging/sup_makasar','refresh');
        }

        if($site_code->site_code == 'KBT1E')
        {
            redirect('bridging/tarakan','refresh');
        }

        if($site_code->site_code == 'KBB1F')
        {
            redirect('bridging/berau','refresh');
        }
    }

    public function routing_stock($signature)
    {
        if(!isset($signature) || $signature == "") {
            redirect('bridging/dashboard','refresh');
        }

        $get_list_subbranch = $this->model_bridging->get_bridging_list_subbranch($signature)->row();
        if(!$get_list_subbranch) {
            redirect('bridging/dashboard','refresh');
        }
        $onsitecode = $get_list_subbranch->site_code;

        if($this->username != 'milla')
        {
            // Cek apakah site_code milik user ada yang sama dengan $onsitecode
            $cek_hak = $this->model_bridging->get_bridging_hak_akses_userid($this->userid)->result();
            $akses_ditemukan = false;
            foreach ($cek_hak as $hak) {
                if ($onsitecode == $hak->site_code) {
                    $akses_ditemukan = true;
                    break;
                }
            }
            // echo 'akses ditemukan: ' . $akses_ditemukan;die;
            if (!$akses_ditemukan) {
                $this->session->set_flashdata("pesan", "User anda tidak diijinkan mengakses halaman ini");
                redirect('bridging/dashboard', 'refresh');
            }
        }
        
        $data = [
            "title" => "Stock Import",
            "title2" => "History Import",
            'url'   => 'bridging/proses_import',
            'userid' => $this->userid,
            'sitecode' => $onsitecode,
            'get_data' => $this->model_bridging->get_history_import($onsitecode),
        ];
        $this->view($data, false, "dashboard_stock");
    }

    public function proses_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $sitecode = $this->input->post('sitecode');

        $signature = 'stock-' . md5($sitecode . $month . $this->model_outlet_transaksi->timezone());
        
        // echo 'sitecode '.$sitecode;die;

        // inisialisasi upload
        $init_upload = $this->attachment_config('',$sitecode);        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/stock/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'D') {
                echo "<script>alert('upload file gagal karena melebihi max column'); </script>";
                redirect('bridging','refresh');
            }

            $input_log_history = [
                "site_code"     => $sitecode,
                "tahun"         => $tahun,
                "bulan"         => $bulan,
                "filename"      => $filename_excel,
                "created_at"    => $this->model_outlet_transaksi->timezone(),
                "created_by"    => $this->userid,
                "signature"     => $signature,
            ];

            $id_log = $this->model_bridging->input_log_history($input_log_history);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 500) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 500 ROW.");
                    redirect('bridging','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    // cek kodeproduk mpm
                    $kodeprod    = (strlen($temp = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())) == 5) ? '0' . $temp : $temp;
                    // echo $kodeprod;
                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($kodeprod);
                    if($get_kodeprod->num_rows() > 0) {
                        $kode_prc = $get_kodeprod->row()->kode_prc;
                        $namaprod = $get_kodeprod->row()->namaprod;
                        $supp = $get_kodeprod->row()->supp;
                        $GRUPPROD = $get_kodeprod->row()->GRUPPROD;
                        $isisatuan = $get_kodeprod->row()->isisatuan;
                        $is_valid_kodeprod = 1;
                    }else{
                        $kode_prc = '';
                        $namaprod = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                        $supp = '';
                        $GRUPPROD = '';
                        $isisatuan = '';
                        $is_valid_kodeprod = 0;
                    }

                    // echo $GRUPPROD;die;

                    // $namaprod    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $satuan    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $stockakhir_pcs    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    
                    $data = [
                        'kodeprod'          => $kodeprod,
                        'namaprod'          => $namaprod,
                        'kode_prc'          => $kode_prc,
                        'supp'              => $supp,
                        'grupprod'          => $GRUPPROD,
                        'isisatuan'         => $isisatuan,
                        'satuan'            => $satuan,
                        'stockakhir_pcs'    => $stockakhir_pcs,
                        'is_valid_kodeprod' => $is_valid_kodeprod,
                        'id_history'        => $id_log,
                        'created_at'        => $this->model_outlet_transaksi->timezone(),
                        'created_by'        => $this->userid,
                        'signature'         => $signature
                    ];

                    $insert = $this->model_bridging->insert_stock_import_detail($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_import/'.$signature.'','refresh');
    }

    public function preview_import($signature)
    {
        $get_data = $this->model_bridging->get_stock_import_detail($signature);
        if ($get_data->num_rows() == 0) {
            $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
            redirect('bridging','refresh');
        }
        $id_log = $get_data->row()->id_history;

        $is_invalid = $this->model_bridging->get_stock_import_where_is_valid_false($signature);
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"             => "Preview Import",
            'url'               => 'bridging/import_stock',
            'params_invalid'    => $params_invalid,
            'get_data'          => $get_data,
            'id_log'            => $id_log,
            'get_summary'       => $this->model_bridging->get_stock_import_detail_summary($signature),
        ];
        $this->view($data, false, "preview_import");
    }

    public function import_stock()
    {
        $id_log = $this->input->post('id_log');

        $get_data_log = $this->model_bridging->get_history_import_by_id($id_log);
        if ($get_data_log->num_rows() == 0) {
            $this->session->set_flashdata("pesan", "Id tidak ditemukan. Silahkan ulangi kembali.");
            redirect('bridging','refresh');
        }
        $site_code = $get_data_log->row()->site_code;
        $tahun = $get_data_log->row()->tahun;
        // $tahun_stock = substr($tahun, 2, 2);
        // $bulan = $get_data_log->row()->bulan;
        $tahunbulan = $get_data_log->row()->tahunbulan;

        $get_master_site = $this->model_bridging->get_master_site($site_code);
        if ($get_master_site->num_rows() == 0) {
            $this->session->set_flashdata("pesan", "Site tidak ditemukan. Silahkan ulangi kembali.");
            redirect('bridging','refresh');
        }

        $kode_comp = $get_master_site->row()->kode_comp;
        $nocab = $get_master_site->row()->nocab;

        $this->model_bridging->delete_st($kode_comp, $nocab, $tahunbulan, $tahun);

        $get_data = $this->model_bridging->get_stock_import_detail_by_id_history($id_log)->result();

        foreach ($get_data as $key) {
            $data_insert = [
                'kodeprod'          => $key->kodeprod,
                'kode_prc'          => $key->kode_prc,
                'namaprod'          => $key->namaprod,
                'supp'              => $key->supp,
                'grupprod'          => $key->grupprod,
                'satuan'            => $key->satuan,
                'isisatuan'         => $key->isisatuan,
                'saldo_awal'        => $key->stockakhir_pcs,
                'kode_gdg'          => 'PST',
                'nama_gdg'          => 'PUSAT',
                'stok_akhir'        => '0',
                'nick_site'         => $kode_comp,
                'gudang_id'         => '1',
                'nama_gudang'       => 'Gudang Baik/Inti',
                'nocab'             => $nocab,
                'bulan'             => $tahunbulan
            ];

            $this->model_bridging->insert_st($tahun, $data_insert);
        }
        
        if (!empty($data_insert)) {
            $this->session->set_flashdata("pesan_success", "upload file excel berhasil");
            redirect('bridging','refresh');
        }else{
            $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
            redirect('bridging','refresh');
        }
    }

    public function download_template_stock()
    {
        $query = "
            select 	'' as kodeproduk,
                    '' as namaproduk,
                    '' as satuan,
                    '' as stockakhir_pcs
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kodeproduk',
            'namaproduk',
            'satuan',
            'stockakhir_pcs'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kodeproduk',
            'namaproduk',
            'satuan',
            'stockakhir_pcs'
        ));
        $this->excel_generator->set_width(array(10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Stock Import'); 
    }
    
    public function master_sitecode()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'userid'    => $this->session->userdata('id'),
            'tahun'     => date('Y'),
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/site_code?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $result = $array_response['data'];
            echo "<option value=''> -- Pilih Company -- </option>";

            foreach ($result as $key => $r)
            {
                echo "<option value='". $r["site_code"] . "' >";
                echo $r["company"] . " ___ " . $r["site_code"];
                echo "</option>";
            }
        }
    }

    public function bontang()
    {
        $site_code = 'SMB2C';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Bontang Sales",
            "title_customer" => "Bridging Customer (Outlet)",
            'url'   => 'bridging/bontang_import',
            'url_customer'   => 'bridging/bontang_import_customer',
            'bridging'  => "bontang",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "bontang");

    }

    public function bontang_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('SMB');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/bontang','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/bontang','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_bontang_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_bontang_import");
            redirect('bridging/bontang','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('bontang');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/bontang/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/bontang','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'CI') {
                echo "<script>alert('upload file gagal karena melebihi max column yang sudah disepakati'); </script>";
                redirect('bridging/bontang','refresh');
            }

            $input_log_data = [
                "site_code" => "SMB2C",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("SMB2C".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 1000 ROW.");
                    redirect('bridging/bontang','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/bontang','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $distributor    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $cabang    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tipetrans    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $divisi    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $principal    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $productgroup1    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $productgroup2    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $productgroup3    = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $brand    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $kodeproduk    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $kodevarian    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    
                    // cek kodeproduk mpm
                    $kodeprodukprincipal = (strlen($temp = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue())) == 5) ? '0' . $temp : $temp;

                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($kodeprodukprincipal);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $namaproduk    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $packaging    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $productclass = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kodecustomer= trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_bontang_customer($kodecustomer);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $namacustomer = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $alamatcustomer = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $area = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $subarea = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $channel = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $subchannel = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $customergroup = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $keyaccount= trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $kodesalesman = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $namasalesman = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $kodesalesco = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $namasalesco = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $kodespv = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $namaspv = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $tahunbulan = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $bulan = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    
                    // cek tanggal
                    $cell = $worksheet->getCellByColumnAndRow(32, $row);
                    $cellValue = $cell->getValue();

                    $is_valid_tanggal = 1; // Nilai default valid

                    if (is_numeric($cellValue)) {
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }
                    } else {
                        $tanggal = $cellValue;
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    
                    $weekno = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $nomornota = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $salesmethod = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $sellingtype = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $qtysold = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $qtysoldcrt = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $qtysolduom1 = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $qtysolduom2 = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $qtysolduom3 = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $qtysolduom4 = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $qtysoldtotalpcs = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $freegoodtotalpcs = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $tonnage = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $volume = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $grossamount = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $linediscount1 = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $linediscount2 = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $linediscount3 = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $linediscount4 = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $linediscount5 = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $totallinediscount = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $discountnota1 = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $discountnota2 = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $discountnota3 = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $totaldiscountnota = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $dpp = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $ppn = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $ppnbm = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $tax = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $netamount = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $warehouse = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $customerpo = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $customerjoindate = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $nofakturpajak = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $tanggalfakturpajak = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $nomorfakturproforma = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $tanggalfakturproforma = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $term = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $uom1 = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $uom2 = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $uom3 = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $uom4 = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $isiuom1 = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $isiuom2 = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $isiuom3 = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $sellingprice = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $cogs = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $sellingpriceinkg = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $caseweightinkg = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $qtyordertotalpcs = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $tslqtysoldnfg = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $tslconvpcstoctn = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());
                    $tsltonnagesoldfg = trim($worksheet->getCellByColumnAndRow(85, $row)->getValue());
                    $end = trim($worksheet->getCellByColumnAndRow(86, $row)->getValue());

                    $data = [
                        'distributor'      => $distributor,
                        'cabang'          => $cabang,
                        'tipetrans'        => $tipetrans,
                        'divisi'           => $divisi,
                        'principal'        => $principal,
                        'productgroup1'    => $productgroup1,
                        'productgroup2'    => $productgroup2,
                        'productgroup3'    => $productgroup3,
                        'brand'            => $brand,
                        'kodeproduk'       => $kodeproduk,
                        'kodevarian'       => $kodevarian,
                        'kodeprodukprincipal' => $kodeprodukprincipal,
                        'namaproduk'       => $namaproduk,
                        'packaging'        => $packaging,
                        'productclass'     => $productclass,
                        'kodecustomer'     => $kodecustomer,
                        'namacustomer'     => $namacustomer,
                        'alamatcustomer'   => $alamatcustomer,
                        'area'             => $area,
                        'subarea'          => $subarea,
                        'channel'          => $channel,
                        'subchannel'       => $subchannel,
                        'customergroup'    => $customergroup,
                        'keyaccount'       => $keyaccount,
                        'kodesalesman'     => $kodesalesman,
                        'namasalesman'     => $namasalesman,
                        'kodesalesco'      => $kodesalesco,
                        'namasalesco'      => $namasalesco,
                        'kodespv'          => $kodespv,
                        'namaspv'          => $namaspv,
                        'tahunbulan'       => $tahunbulan,
                        'bulan'            => $bulan,
                        'tanggal'          => $tanggal,
                        'weekno'           => $weekno,
                        'nomornota'        => $nomornota,
                        'salesmethod'      => $salesmethod,
                        'sellingtype'      => $sellingtype,
                        'qtysold'          => $qtysold,
                        'qtysoldcrt'       => $qtysoldcrt,
                        'qtysolduom1'      => $qtysolduom1,
                        'qtysolduom2'      => $qtysolduom2,
                        'qtysolduom3'      => $qtysolduom3,
                        'qtysolduom4'      => $qtysolduom4,
                        'qtysoldtotalpcs'  => $qtysoldtotalpcs,
                        'freegoodtotalpcs' => $freegoodtotalpcs,
                        'tonnage'          => $tonnage,
                        'volume'           => $volume,
                        'grossamount'      => $grossamount,
                        'linediscount1'    => $linediscount1,
                        'linediscount2'    => $linediscount2,
                        'linediscount3'    => $linediscount3,
                        'linediscount4'    => $linediscount4,
                        'linediscount5'    => $linediscount5,
                        'totallinediscount' => $totallinediscount,
                        'discountnota1'    => $discountnota1,
                        'discountnota2'    => $discountnota2,
                        'discountnota3'    => $discountnota3,
                        'totaldiscountnota' => $totaldiscountnota,
                        'dpp'              => $dpp,
                        'ppn'              => $ppn,
                        'ppnbm'            => $ppnbm,
                        'tax'              => $tax,
                        'netamount'        => $netamount,
                        'warehouse'        => $warehouse,
                        'customerpo'       => $customerpo,
                        'customerjoindate' => $customerjoindate,
                        'nofakturpajak'    => $nofakturpajak,
                        'tanggalfakturpajak' => $tanggalfakturpajak,
                        'nomorfakturproforma' => $nomorfakturproforma,
                        'tanggalfakturproforma' => $tanggalfakturproforma,
                        'term'             => $term,
                        'uom1'             => $uom1,
                        'uom2'             => $uom2,
                        'uom3'             => $uom3,
                        'uom4'             => $uom4,
                        'isiuom1'          => $isiuom1,
                        'isiuom2'          => $isiuom2,
                        'isiuom3'          => $isiuom3,
                        'sellingprice'     => $sellingprice,
                        'cogs'             => $cogs,
                        'sellingpriceinkg' => $sellingpriceinkg,
                        'caseweightinkg'   => $caseweightinkg,
                        'qtyordertotalpcs' => $qtyordertotalpcs,
                        'tslqtysoldnfg'    => $tslqtysoldnfg,
                        'tslconvpcstoctn'  => $tslconvpcstoctn,
                        'tsltonnagesoldfg' => $tsltonnagesoldfg,
                        'end'              => $end,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_bontang_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/bontang','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_bontang','refresh');
    }

    public function preview_bontang()
    {
        $get_data = $this->model_bridging->get_bontang_import();
        $id_bridging_log = $get_data->row()->id_bridging_log;
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_bontang_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging Bontang",
            'url'           => 'bridging/submit_bontang',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_bontang_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_bontang");
    }

    public function submit_bontang()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/bontang','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/bontang','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_bontang($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_bontang($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_bontang($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_fi = $this->model_bridging->insert_fi_bontang_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_bontang($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_bontang($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_bontang($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_bontang($tahun_upload, $nocab);

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);


        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_result_success", "proses upload success");
        redirect('bridging/bontang','refresh');
        
    }

    public function download_template_bontang()
    {
        $query = "
            select 	'' as distributor,
                    '' as cabang,
                    '' as tipetrans,
                    '' as divisi,
                    '' as principal,
                    '' as productgroup1,
                    '' as productgroup2,
                    '' as productgroup3,
                    '' as brand,
                    '' as kodeproduk,
                    '' as kodevarian,
                    '' as kodeprodukprincipal,
                    '' as namaproduk,
                    '' as packaging,
                    '' as productclass,
                    '' as kodecustomer,
                    '' as namacustomer,
                    '' as alamatcustomer,
                    '' as area,
                    '' as subarea,
                    '' as channel,
                    '' as subchannel,
                    '' as customergroup,
                    '' as keyaccount,
                    '' as kodesalesman,
                    '' as namasalesman,
                    '' as kodesalesco,
                    '' as namasalesco,
                    '' as kodespv,
                    '' as namaspv,
                    '' as tahunbulan,
                    '' as bulan,
                    '' as tanggal,
                    '' as weekno,
                    '' as nomornota,
                    '' as salesmethod,
                    '' as sellingtype,
                    '' as qtysold,
                    '' as qtysoldcrt,
                    '' as qtysolduom1,
                    '' as qtysolduom2,
                    '' as qtysolduom3,
                    '' as qtysolduom4,
                    '' as qtysoldtotalpcs,
                    '' as freegoodtotalpcs,
                    '' as tonnage,
                    '' as volume,
                    '' as grossamount,
                    '' as linediscount1,
                    '' as linediscount2,
                    '' as linediscount3,
                    '' as linediscount4,
                    '' as linediscount5,
                    '' as totallinediscount,
                    '' as discountnota1,
                    '' as discountnota2,
                    '' as discountnota3,
                    '' as totaldiscountnota,
                    '' as dpp,
                    '' as ppn,
                    '' as ppnbm,
                    '' as tax,
                    '' as netamount,
                    '' as warehouse,
                    '' as customerpo,
                    '' as customerjoindate,
                    '' as nofakturpajak,
                    '' as tanggalfakturpajak,
                    '' as nomorfakturproforma,
                    '' as tanggalfakturproforma,
                    '' as term,
                    '' as uom1,
                    '' as uom2,
                    '' as uom3,
                    '' as uom4,
                    '' as isiuom1,
                    '' as isiuom2,
                    '' as isiuom3,
                    '' as sellingprice,
                    '' as cogs,
                    '' as sellingpriceinkg,
                    '' as caseweightinkg,
                    '' as qtyordertotalpcs,
                    '' as tslqtysoldnfg,
                    '' as tslconvpcstoctn,
                    '' as tsltonnagesoldfg,
                    '' as `end`
            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'distributor','cabang','tipetrans','divisi','principal','productgroup1','productgroup2','productgroup3','brand','kodeproduk','kodevarian','kodeprodukprincipal', 'namaproduk', 'packaging', 'productclass', 'kodecustomer', 'namacustomer', 'alamatcustomer', 'area', 'subarea', 'channel', 'subchannel', 'customergroup', 'keyaccount', 'kodesalesman', 'namasalesman', 
            'kodesalesco',
            'namasalesco',
            'kodespv',
            'namaspv',
            'tahunbulan',
            'bulan',
            'tanggal',
            'weekno',
            'nomornota',
            'salesmethod',
            'sellingtype',
            'qtysold',
            'qtysoldcrt',
            'qtysolduom1',
            'qtysolduom2',
            'qtysolduom3',
            'qtysolduom4',
            'qtysoldtotalpcs',
            'freegoodtotalpcs',
            'tonnage',
            'volume',
            'grossamount',
            'linediscount1',
            'linediscount2',
            'linediscount3',
            'linediscount4',
            'linediscount5',
            'totallinediscount',
            'discountnota1',
            'discountnota2',
            'discountnota3',
            'totaldiscountnota',
            'dpp',
            'ppn',
            'ppnbm',
            'tax',
            'netamount',
            'warehouse',
            'customerpo',
            'customerjoindate',
            'nofakturpajak',
            'tanggalfakturpajak',
            'nomorfakturproforma',
            'tanggalfakturproforma',
            'term',
            'uom1',
            'uom2',
            'uom3',
            'uom4',
            'isiuom1',
            'isiuom2',
            'isiuom3',
            'sellingprice',
            'cogs',
            'sellingpriceinkg',
            'caseweightinkg',
            'qtyordertotalpcs',
            'tslqtysoldnfg',
            'tslconvpcstoctn',
            'tsltonnagesoldfg',
            'end'
        ));
        $this->excel_generator->set_column(array
        ( 
            'distributor','cabang','tipetrans','divisi','principal','productgroup1','productgroup2','productgroup3','brand','kodeproduk','kodevarian','kodeprodukprincipal', 'namaproduk', 'packaging', 'productclass', 'kodecustomer', 'namacustomer', 'alamatcustomer', 'area', 'subarea', 'channel', 'subchannel', 'customergroup', 'keyaccount', 'kodesalesman', 'namasalesman', 
            'kodesalesco',
            'namasalesco',
            'kodespv',
            'namaspv',
            'tahunbulan',
            'bulan',
            'tanggal',
            'weekno',
            'nomornota',
            'salesmethod',
            'sellingtype',
            'qtysold',
            'qtysoldcrt',
            'qtysolduom1',
            'qtysolduom2',
            'qtysolduom3',
            'qtysolduom4',
            'qtysoldtotalpcs',
            'freegoodtotalpcs',
            'tonnage',
            'volume',
            'grossamount',
            'linediscount1',
            'linediscount2',
            'linediscount3',
            'linediscount4',
            'linediscount5',
            'totallinediscount',
            'discountnota1',
            'discountnota2',
            'discountnota3',
            'totaldiscountnota',
            'dpp',
            'ppn',
            'ppnbm',
            'tax',
            'netamount',
            'warehouse',
            'customerpo',
            'customerjoindate',
            'nofakturpajak',
            'tanggalfakturpajak',
            'nomorfakturproforma',
            'tanggalfakturproforma',
            'term',
            'uom1',
            'uom2',
            'uom3',
            'uom4',
            'isiuom1',
            'isiuom2',
            'isiuom3',
            'sellingprice',
            'cogs',
            'sellingpriceinkg',
            'caseweightinkg',
            'qtyordertotalpcs',
            'tslqtysoldnfg',
            'tslconvpcstoctn',
            'tsltonnagesoldfg',
            'end'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template'); 


    }

    public function bontang_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_bontang_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_bontang_import_customer");
            redirect('bridging/bontang','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_bontang_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_bontang_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('bontang');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/bontang/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/bontang','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 1000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 1000 ROW.");
                    redirect('bridging/bontang','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/bontang','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($kategori == "" || $nama_site == "" || $regional == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $nama_type == "" || $class_id = "" || $class = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/bontang','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_bontang_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/bontang','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_bontang_customer','refresh');
    }

    public function result_bontang_customer()
    {
        $data = [
            "title" => "Result Bridging Bontang Customer",
            'url'   => 'bridging/bontang_submit_customer',
            'get_data' => $this->model_bridging->get_bontang_import_customer(),
            'get_summary' => $this->model_bridging->get_bontang_import_customer_summary(),
        ];
        $this->view($data, false, "result_bontang_customer");
    }

    public function download_template_bontang_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_bontang_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Bontang Customer'); 
    }

    public function samarinda()
    {
        $site_code = 'SAM2A';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Samarinda Sales",
            "title_customer" => "Bridging Customer Samarinda (Outlet)",
            'url'   => 'bridging/samarinda_import',
            'url_customer'   => 'bridging/samarinda_import_customer',
            'bridging'  => "samarinda",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "samarinda");
    }

    public function samarinda_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('SAM');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/samarinda','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');

        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/samarinda','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_samarinda_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_samarinda_import");
            redirect('bridging/samarinda','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('samarinda');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/samarinda/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/samarinda','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'CI') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/samarinda','refresh');
            }

            $input_log_data = [
                "site_code" => "SAM2A",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("SAM2A".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 1000 ROW.");
                    redirect('bridging/samarinda','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/samarinda','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $distributor    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $cabang    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tipetrans    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $divisi    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $principal    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $productgroup1    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $productgroup2    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $productgroup3    = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $brand    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $kodeproduk    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $kodevarian    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    
                    // cek kodeproduk mpm
                    $kodeprodukprincipal = (strlen($temp = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue())) == 5) ? '0' . $temp : $temp;
                    // echo $kodeprodukprincipal;
                    // echo '</br>';

                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($kodeprodukprincipal);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $namaproduk    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $packaging    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $productclass = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kodecustomer= trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_samarinda_customer($kodecustomer);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $namacustomer = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $alamatcustomer = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $area = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $subarea = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $channel = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $subchannel = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $customergroup = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $keyaccount= trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $kodesalesman = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $namasalesman = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $kodesalesco = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $namasalesco = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $kodespv = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $namaspv = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $tahunbulan = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $bulan = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    
                    // cek tanggal
                    $cell = $worksheet->getCellByColumnAndRow(32, $row);
                    $cellValue = $cell->getValue();

                    // echo $cellValue; echo '<br>';
                    $is_valid_tanggal = 1; // Nilai default valid

                    if (is_numeric($cellValue)) {
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }
                    } else {
                        $tanggal = $cellValue;
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    
                    $weekno = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $nomornota = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $salesmethod = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $sellingtype = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $qtysold = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $qtysoldcrt = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $qtysolduom1 = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $qtysolduom2 = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $qtysolduom3 = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $qtysolduom4 = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $qtysoldtotalpcs = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $freegoodtotalpcs = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $tonnage = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $volume = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $grossamount = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $linediscount1 = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $linediscount2 = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $linediscount3 = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $linediscount4 = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $linediscount5 = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $totallinediscount = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $discountnota1 = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $discountnota2 = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $discountnota3 = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $totaldiscountnota = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $dpp = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $ppn = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $ppnbm = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $tax = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $netamount = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $warehouse = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $customerpo = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $customerjoindate = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $nofakturpajak = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $tanggalfakturpajak = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $nomorfakturproforma = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $tanggalfakturproforma = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $term = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $uom1 = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $uom2 = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $uom3 = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $uom4 = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $isiuom1 = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $isiuom2 = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $isiuom3 = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $sellingprice = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $cogs = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $sellingpriceinkg = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $caseweightinkg = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $qtyordertotalpcs = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $tslqtysoldnfg = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $tslconvpcstoctn = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());
                    $tsltonnagesoldfg = trim($worksheet->getCellByColumnAndRow(85, $row)->getValue());
                    $end = trim($worksheet->getCellByColumnAndRow(86, $row)->getValue());

                    $data = [
                        'distributor'      => $distributor,
                        'cabang'          => $cabang,
                        'tipetrans'        => $tipetrans,
                        'divisi'           => $divisi,
                        'principal'        => $principal,
                        'productgroup1'    => $productgroup1,
                        'productgroup2'    => $productgroup2,
                        'productgroup3'    => $productgroup3,
                        'brand'            => $brand,
                        'kodeproduk'       => $kodeproduk,
                        'kodevarian'       => $kodevarian,
                        'kodeprodukprincipal' => $kodeprodukprincipal,
                        'namaproduk'       => $namaproduk,
                        'packaging'        => $packaging,
                        'productclass'     => $productclass,
                        'kodecustomer'     => $kodecustomer,
                        'namacustomer'     => $namacustomer,
                        'alamatcustomer'   => $alamatcustomer,
                        'area'             => $area,
                        'subarea'          => $subarea,
                        'channel'          => $channel,
                        'subchannel'       => $subchannel,
                        'customergroup'    => $customergroup,
                        'keyaccount'       => $keyaccount,
                        'kodesalesman'     => $kodesalesman,
                        'namasalesman'     => $namasalesman,
                        'kodesalesco'      => $kodesalesco,
                        'namasalesco'      => $namasalesco,
                        'kodespv'          => $kodespv,
                        'namaspv'          => $namaspv,
                        'tahunbulan'       => $tahunbulan,
                        'bulan'            => $bulan,
                        'tanggal'          => $tanggal,
                        'weekno'           => $weekno,
                        'nomornota'        => $nomornota,
                        'salesmethod'      => $salesmethod,
                        'sellingtype'      => $sellingtype,
                        'qtysold'          => $qtysold,
                        'qtysoldcrt'       => $qtysoldcrt,
                        'qtysolduom1'      => $qtysolduom1,
                        'qtysolduom2'      => $qtysolduom2,
                        'qtysolduom3'      => $qtysolduom3,
                        'qtysolduom4'      => $qtysolduom4,
                        'qtysoldtotalpcs'  => $qtysoldtotalpcs,
                        'freegoodtotalpcs' => $freegoodtotalpcs,
                        'tonnage'          => $tonnage,
                        'volume'           => $volume,
                        'grossamount'      => $grossamount,
                        'linediscount1'    => $linediscount1,
                        'linediscount2'    => $linediscount2,
                        'linediscount3'    => $linediscount3,
                        'linediscount4'    => $linediscount4,
                        'linediscount5'    => $linediscount5,
                        'totallinediscount' => $totallinediscount,
                        'discountnota1'    => $discountnota1,
                        'discountnota2'    => $discountnota2,
                        'discountnota3'    => $discountnota3,
                        'totaldiscountnota' => $totaldiscountnota,
                        'dpp'              => $dpp,
                        'ppn'              => $ppn,
                        'ppnbm'            => $ppnbm,
                        'tax'              => $tax,
                        'netamount'        => $netamount,
                        'warehouse'        => $warehouse,
                        'customerpo'       => $customerpo,
                        'customerjoindate' => $customerjoindate,
                        'nofakturpajak'    => $nofakturpajak,
                        'tanggalfakturpajak' => $tanggalfakturpajak,
                        'nomorfakturproforma' => $nomorfakturproforma,
                        'tanggalfakturproforma' => $tanggalfakturproforma,
                        'term'             => $term,
                        'uom1'             => $uom1,
                        'uom2'             => $uom2,
                        'uom3'             => $uom3,
                        'uom4'             => $uom4,
                        'isiuom1'          => $isiuom1,
                        'isiuom2'          => $isiuom2,
                        'isiuom3'          => $isiuom3,
                        'sellingprice'     => $sellingprice,
                        'cogs'             => $cogs,
                        'sellingpriceinkg' => $sellingpriceinkg,
                        'caseweightinkg'   => $caseweightinkg,
                        'qtyordertotalpcs' => $qtyordertotalpcs,
                        'tslqtysoldnfg'    => $tslqtysoldnfg,
                        'tslconvpcstoctn'  => $tslconvpcstoctn,
                        'tsltonnagesoldfg' => $tsltonnagesoldfg,
                        'end'              => $end,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_samarinda_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/samarinda','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_samarinda','refresh');
    }

    public function preview_samarinda()
    {
        $get_data = $this->model_bridging->get_samarinda_import();
        $id_bridging_log = $get_data->row()->id_bridging_log; 
        // echo "id_bridging_log : ".$id_bridging_log; die;

        $is_invalid = $this->model_bridging->get_samarinda_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();die;
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging Samarinda",
            'url'           => 'bridging/submit_samarinda',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_samarinda_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_samarinda");
    }

    public function download_template_samarinda()
    {
        $query = "
            select 	'' as distributor,
                    '' as cabang,
                    '' as tipetrans,
                    '' as divisi,
                    '' as principal,
                    '' as productgroup1,
                    '' as productgroup2,
                    '' as productgroup3,
                    '' as brand,
                    '' as kodeproduk,
                    '' as kodevarian,
                    '' as kodeprodukprincipal,
                    '' as namaproduk,
                    '' as packaging,
                    '' as productclass,
                    '' as kodecustomer,
                    '' as namacustomer,
                    '' as alamatcustomer,
                    '' as area,
                    '' as subarea,
                    '' as channel,
                    '' as subchannel,
                    '' as customergroup,
                    '' as keyaccount,
                    '' as kodesalesman,
                    '' as namasalesman,
                    '' as kodesalesco,
                    '' as namasalesco,
                    '' as kodespv,
                    '' as namaspv,
                    '' as tahunbulan,
                    '' as bulan,
                    '' as tanggal,
                    '' as weekno,
                    '' as nomornota,
                    '' as salesmethod,
                    '' as sellingtype,
                    '' as qtysold,
                    '' as qtysoldcrt,
                    '' as qtysolduom1,
                    '' as qtysolduom2,
                    '' as qtysolduom3,
                    '' as qtysolduom4,
                    '' as qtysoldtotalpcs,
                    '' as freegoodtotalpcs,
                    '' as tonnage,
                    '' as volume,
                    '' as grossamount,
                    '' as linediscount1,
                    '' as linediscount2,
                    '' as linediscount3,
                    '' as linediscount4,
                    '' as linediscount5,
                    '' as totallinediscount,
                    '' as discountnota1,
                    '' as discountnota2,
                    '' as discountnota3,
                    '' as totaldiscountnota,
                    '' as dpp,
                    '' as ppn,
                    '' as ppnbm,
                    '' as tax,
                    '' as netamount,
                    '' as warehouse,
                    '' as customerpo,
                    '' as customerjoindate,
                    '' as nofakturpajak,
                    '' as tanggalfakturpajak,
                    '' as nomorfakturproforma,
                    '' as tanggalfakturproforma,
                    '' as term,
                    '' as uom1,
                    '' as uom2,
                    '' as uom3,
                    '' as uom4,
                    '' as isiuom1,
                    '' as isiuom2,
                    '' as isiuom3,
                    '' as sellingprice,
                    '' as cogs,
                    '' as sellingpriceinkg,
                    '' as caseweightinkg,
                    '' as qtyordertotalpcs,
                    '' as tslqtysoldnfg,
                    '' as tslconvpcstoctn,
                    '' as tsltonnagesoldfg,
                    '' as `end`
            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'distributor','cabang','tipetrans','divisi','principal','productgroup1','productgroup2','productgroup3','brand','kodeproduk','kodevarian','kodeprodukprincipal', 'namaproduk', 'packaging', 'productclass', 'kodecustomer', 'namacustomer', 'alamatcustomer', 'area', 'subarea', 'channel', 'subchannel', 'customergroup', 'keyaccount', 'kodesalesman', 'namasalesman', 
            'kodesalesco',
            'namasalesco',
            'kodespv',
            'namaspv',
            'tahunbulan',
            'bulan',
            'tanggal',
            'weekno',
            'nomornota',
            'salesmethod',
            'sellingtype',
            'qtysold',
            'qtysoldcrt',
            'qtysolduom1',
            'qtysolduom2',
            'qtysolduom3',
            'qtysolduom4',
            'qtysoldtotalpcs',
            'freegoodtotalpcs',
            'tonnage',
            'volume',
            'grossamount',
            'linediscount1',
            'linediscount2',
            'linediscount3',
            'linediscount4',
            'linediscount5',
            'totallinediscount',
            'discountnota1',
            'discountnota2',
            'discountnota3',
            'totaldiscountnota',
            'dpp',
            'ppn',
            'ppnbm',
            'tax',
            'netamount',
            'warehouse',
            'customerpo',
            'customerjoindate',
            'nofakturpajak',
            'tanggalfakturpajak',
            'nomorfakturproforma',
            'tanggalfakturproforma',
            'term',
            'uom1',
            'uom2',
            'uom3',
            'uom4',
            'isiuom1',
            'isiuom2',
            'isiuom3',
            'sellingprice',
            'cogs',
            'sellingpriceinkg',
            'caseweightinkg',
            'qtyordertotalpcs',
            'tslqtysoldnfg',
            'tslconvpcstoctn',
            'tsltonnagesoldfg',
            'end'
        ));
        $this->excel_generator->set_column(array
        ( 
            'distributor','cabang','tipetrans','divisi','principal','productgroup1','productgroup2','productgroup3','brand','kodeproduk','kodevarian','kodeprodukprincipal', 'namaproduk', 'packaging', 'productclass', 'kodecustomer', 'namacustomer', 'alamatcustomer', 'area', 'subarea', 'channel', 'subchannel', 'customergroup', 'keyaccount', 'kodesalesman', 'namasalesman', 
            'kodesalesco',
            'namasalesco',
            'kodespv',
            'namaspv',
            'tahunbulan',
            'bulan',
            'tanggal',
            'weekno',
            'nomornota',
            'salesmethod',
            'sellingtype',
            'qtysold',
            'qtysoldcrt',
            'qtysolduom1',
            'qtysolduom2',
            'qtysolduom3',
            'qtysolduom4',
            'qtysoldtotalpcs',
            'freegoodtotalpcs',
            'tonnage',
            'volume',
            'grossamount',
            'linediscount1',
            'linediscount2',
            'linediscount3',
            'linediscount4',
            'linediscount5',
            'totallinediscount',
            'discountnota1',
            'discountnota2',
            'discountnota3',
            'totaldiscountnota',
            'dpp',
            'ppn',
            'ppnbm',
            'tax',
            'netamount',
            'warehouse',
            'customerpo',
            'customerjoindate',
            'nofakturpajak',
            'tanggalfakturpajak',
            'nomorfakturproforma',
            'tanggalfakturproforma',
            'term',
            'uom1',
            'uom2',
            'uom3',
            'uom4',
            'isiuom1',
            'isiuom2',
            'isiuom3',
            'sellingprice',
            'cogs',
            'sellingpriceinkg',
            'caseweightinkg',
            'qtyordertotalpcs',
            'tslqtysoldnfg',
            'tslconvpcstoctn',
            'tsltonnagesoldfg',
            'end'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Samarinda'); 


    }

    public function samarinda_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_samarinda_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_samarinda_import_customer");
            redirect('bridging/samarinda','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_samarinda_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_samarinda_import_customer('mapping_uli');

        // echo'disini'; die;
        // inisialisasi upload
        $init_upload = $this->attachment_config('samarinda'); 
        if ($this->upload->do_upload('file_customer')) 
        {
            // echo 'disini'; die;
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/samarinda/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/samarinda','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/samarinda','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/samarinda','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    // echo 'cek_class ' .$cek_class;die; 
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($kategori == "" || $nama_site == "" || $regional == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $nama_type == "" || $class_id = "" || $class = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/samarinda','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_samarinda_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            // redirect('bridging/samarinda','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_samarinda_customer','refresh');
    }

    public function download_template_samarinda_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_samarinda_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Samarinda Customer'); 
    }

    public function result_samarinda_customer()
    {
        $data = [
            "title" => "Result Bridging Samarinda Customer",
            'url'   => 'bridging/samarinda_submit_customer',
            'get_data' => $this->model_bridging->get_samarinda_import_customer(),
            'get_summary' => $this->model_bridging->get_samarinda_import_customer_summary(),
        ];
        $this->view($data, false, "result_samarinda_customer");
    }

    public function submit_samarinda()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');
        // echo  $id_bridging_log; die;

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/samarinda','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        // die;

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/samarinda','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_samarinda($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_samarinda($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_samarinda($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_fi = $this->model_bridging->insert_fi_samarinda_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_samarinda($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_samarinda($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_samarinda($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_samarinda($tahun_upload, $nocab);

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);


        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_result_success", "proses upload success");
        redirect('bridging/samarinda','refresh');
        
    }

    public function kolaka()
    {
        $site_code = 'KLK1V';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Kolaka Sales",
            "title_customer" => "Bridging Customer (Outlet)",
            'filename' => 'Kolaka',
            'url'   => 'bridging/kolaka_import',
            'url_customer'   => 'bridging/kolaka_import_customer',
            'bridging'  => "kolaka",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "kolaka");

    }

    public function kolaka_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('KLK');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        // echo 'status closing : '.$status_closing_bulan_lalu;die;

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/kolaka','refresh');
        }
        
        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');

        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        // echo 'status_closing : '.$status_closing;die;
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/kolaka','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_kolaka_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_kolaka_import");
            redirect('bridging/kolaka','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('kolaka');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/kolaka/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/kolaka','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/kolaka','refresh');
            }

            $input_log_data = [
                "site_code" => "KLK1V",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("KLK1V".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 1000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 1000 ROW.");
                    redirect('bridging/kolaka','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/kolaka','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $siteid    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        $tanggal = $cellValue;
                        // echo "tanggal : ".$tanggal."<br>";
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $salesmanid    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_kolaka_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $nama_customer    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    // cek kodeproduk mpm
                    $productid = (strlen($temp = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())) == 5) ? '0' . $temp : $temp;

                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($productid);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $product_descr    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal= trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());

                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_Descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => $bruto,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => $netto,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_kolaka_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/kolaka','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_kolaka','refresh');
    }

    public function preview_kolaka()
    {
        $get_data = $this->model_bridging->get_kolaka_import();
        $id_bridging_log = $get_data->row()->id_bridging_log;
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_kolaka_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging Kolaka",
            'url'           => 'bridging/submit_kolaka',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_kolaka_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_kolaka");
    }

    // public function download_template_kolaka()
    // {
    //     $query = "
    //         select 	'' as siteid,
    //                 '' as nosales,
    //                 '' as tanggal_sales,
    //                 '' as salesmanid,
    //                 '' as nama_salesman,
    //                 '' as customerid,
    //                 '' as nama_customer,
    //                 '' as productid,
    //                 '' as product_descr,
    //                 '' as flag_retur,
    //                 '' as flag_bonus,
    //                 '' as harga,
    //                 '' as qty,
    //                 '' as bruto,
    //                 '' as disc_cabang,
    //                 '' as rp_cabang,
    //                 '' as disc_prinsipal,
    //                 '' as rp_prinsipal,
    //                 '' as disc_xtra,
    //                 '' as rp_xtra,
    //                 '' as disc_cash,
    //                 '' as rp_cash,
    //                 '' as netto            
    //     ";
    //     $hasil = $this->db->query($query);   
    
    //     $this->excel_generator->set_query($hasil);

    //     $this->excel_generator->set_header(array
    //     (
    //         'siteid',
    //         'nosales',
    //         'tanggal_sales',
    //         'salesmanid',
    //         'nama_salesman',
    //         'customerid',
    //         'nama_customer',
    //         'productid',
    //         'product_descr',
    //         'flag_retur',
    //         'flag_bonus',
    //         'harga',
    //         'qty',
    //         'bruto',
    //         'disc_cabang',
    //         'rp_cabang',
    //         'disc_prinsipal',
    //         'rp_prinsipal',
    //         'disc_xtra',
    //         'rp_xtra',
    //         'disc_cash',
    //         'rp_cash',
    //         'netto'
    //     ));
    //     $this->excel_generator->set_column(array
    //     ( 
    //         'siteid',
    //         'nosales',
    //         'tanggal_sales',
    //         'salesmanid',
    //         'nama_salesman',
    //         'customerid',
    //         'nama_customer',
    //         'productid',
    //         'product_descr',
    //         'flag_retur',
    //         'flag_bonus',
    //         'harga',
    //         'qty',
    //         'bruto',
    //         'disc_cabang',
    //         'rp_cabang',
    //         'disc_prinsipal',
    //         'rp_prinsipal',
    //         'disc_xtra',
    //         'rp_xtra',
    //         'disc_cash',
    //         'rp_cash',
    //         'netto'
    //     ));
    //     $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
    //     $this->excel_generator->exportTo2007('Download Template Kolaka'); 
    // }

    public function download_template_kolaka_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_kolaka_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Kolaka Customer'); 
    }

    public function kolaka_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_kolaka_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_kolaka_import_customer");
            redirect('bridging/kolaka','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_kolaka_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_kolaka_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('kolaka');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/kolaka/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/kolaka','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/kolaka','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/kolaka','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($kategori == "" || $nama_site == "" || $regional == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $nama_type == "" || $class_id = "" || $class = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/kolaka','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_kolaka_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/kolaka','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_kolaka_customer','refresh');
    }

    public function result_kolaka_customer()
    {
        $data = [
            "title" => "Result Bridging Kolaka Customer",
            'url'   => 'bridging/kolaka_submit_customer',
            'get_data' => $this->model_bridging->get_kolaka_import_customer(),
            'get_summary' => $this->model_bridging->get_kolaka_import_customer_summary(),
        ];
        $this->view($data, false, "result_kolaka_customer");
    }

    public function submit_kolaka()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/kolaka','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/kolaka','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_kolaka($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_kolaka($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_kolaka($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_fi = $this->model_bridging->insert_fi_kolaka_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_kolaka($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_kolaka($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_kolaka($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_kolaka($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/kolaka','refresh');
        
    }

    public function kendari()
    {
        $site_code = 'PUM1N';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Kendari Sales",
            "title_customer" => "Bridging Customer Kendari (Outlet)",
            'filename' => 'Kendari',
            'url'   => 'bridging/kendari_import',
            'url_customer'   => 'bridging/kendari_import_customer',
            'bridging'  => "kendari",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "kendari");
    }

    public function kendari_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('PUM');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/kendari','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/kendari','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_kendari_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_kendari_import");
            redirect('bridging/kendari','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('kendari');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/kendari/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/kendari','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/kendari','refresh');
            }

            $input_log_data = [
                "site_code" => "PUM1N",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("PUM1N".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/kendari','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/kendari','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $siteid    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        $tanggal = $cellValue;
                        // echo "tanggal : ".$tanggal."<br>";
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $salesmanid    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_kendari_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $nama_customer    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    // cek kodeproduk mpm
                    $productid = (strlen($temp = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())) == 5) ? '0' . $temp : $temp;

                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($productid);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $product_descr    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal= trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());

                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_Descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => $bruto,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => $netto,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_kendari_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/kendari','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_kendari','refresh');
    }

    public function preview_kendari()
    {
        $get_data = $this->model_bridging->get_kendari_import();
        $id_bridging_log = $get_data->row()->id_bridging_log;
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_kendari_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging Kendari",
            'url'           => 'bridging/submit_kendari',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_kendari_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_kendari");
    }

    public function submit_kendari()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/kendari','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/kendari','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_kendari($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_kendari($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_kendari($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_fi = $this->model_bridging->insert_fi_kendari_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_kendari($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_kendari($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_kendari($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_kendari($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/kendari','refresh');
        
    }

    public function download_template_kendari()
    {
        $query = "
            select 	'' as siteid,
                    '' as nosales,
                    '' as tanggal_sales,
                    '' as salesmanid,
                    '' as nama_salesman,
                    '' as customerid,
                    '' as nama_customer,
                    '' as productid,
                    '' as product_descr,
                    '' as flag_retur,
                    '' as flag_bonus,
                    '' as harga,
                    '' as qty,
                    '' as bruto,
                    '' as disc_cabang,
                    '' as rp_cabang,
                    '' as disc_prinsipal,
                    '' as rp_prinsipal,
                    '' as disc_xtra,
                    '' as rp_xtra,
                    '' as disc_cash,
                    '' as rp_cash,
                    '' as netto            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto'
        ));
        $this->excel_generator->set_column(array
        ( 
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Kendari'); 
    }

    public function download_template_kendari_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_kendari_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Kendari Customer'); 
    }

    public function kendari_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_kendari_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_kendari_import_customer");
            redirect('bridging/kendari','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_kendari_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_kendari_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('kendari');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/kendari/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/kendari','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/kendari','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/kendari','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($kategori == "" || $nama_site == "" || $regional == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $nama_type == "" || $class_id = "" || $class = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/kendari','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_kendari_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/kendari','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_kendari_customer','refresh');
    }

    public function result_kendari_customer()
    {
        $data = [
            "title" => "Result Bridging Kendari Customer",
            'url'   => 'bridging/kendari_submit_customer',
            'get_data' => $this->model_bridging->get_kendari_import_customer(),
            'get_summary' => $this->model_bridging->get_kendari_import_customer_summary(),
        ];
        // var_dump($data);die;
        $this->view($data, false, "result_kendari_customer");
    }


    public function attachment_config($kategori, $site_code)
    {
        $stock = 'stock';
        $base_path = './assets/uploads/bridging/'.$this->tahun_folder;
        
        if (!is_dir($base_path)) {
            @mkdir($base_path, 0777, true);
        }

        if ($kategori != '' && $site_code == null) 
        {
            $base_path .= '/' . $kategori;
            if (!is_dir($base_path)) {
                @mkdir($base_path, 0777, true);
            }
        }elseif ($kategori == null && $site_code != '')
        {
            $base_path .= '/' . $stock;
            if (!is_dir($base_path)) {
                @mkdir($base_path, 0777, true);
            }
        }

        $file_name_suffix = $site_code != '' ? $site_code : $kategori;

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = $base_path;
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $config['file_name'] = $this->tahun_folder."-".rand(1000, 9999)."-"."-".$file_name_suffix;

        $proses = $this->upload->initialize($config);
        return $proses;
    }

    private function view($data, $flag_accordion, $view)
    {
        $data = [
            "navbar"        => $this->navbar($data),
            "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
            "css"           => $this->load->view('management_claim/css', $data),
            "view"          => $this->load->view('bridging/'.$view.'', $data),
            "footer"        => $this->load->view('kalimantan/footer')
        ];
        return $data;       
    }


    public function baubau()
    {
        $site_code = 'BBU1W';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Baubau Sales",
            "title_customer" => "Bridging Customer Baubau (Outlet)",
            'filename' => 'Baubau',
            'url'   => 'bridging/baubau_import',
            'url_customer'   => 'bridging/baubau_import_customer',
            'bridging'  => "baubau",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
            
        ];
        $this->view($data, false, "baubau");
    }

    public function baubau_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('BBU');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/baubau','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/baubau','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_baubau_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_baubau_import");
            redirect('bridging/baubau','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('baubau');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/baubau/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/baubau','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/baubau','refresh');
            }

            $input_log_data = [
                "site_code" => "BBU1W",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("BBU1W".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/baubau','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/baubau','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $siteid    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        $tanggal = $cellValue;
                        // echo "tanggal : ".$tanggal."<br>";
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $salesmanid    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_baubau_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $nama_customer    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    // cek kodeproduk mpm
                    $productid = (strlen($temp = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())) == 5) ? '0' . $temp : $temp;

                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($productid);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $product_descr    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal= trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());

                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_Descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => $bruto,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => $netto,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_baubau_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/baubau','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_baubau','refresh');
    }

    public function preview_baubau()
    {
        $get_data = $this->model_bridging->get_baubau_import();
        $id_bridging_log = $get_data->row()->id_bridging_log;
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_baubau_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging Baubau",
            'url'           => 'bridging/submit_baubau',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_baubau_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_baubau");
    }

    public function submit_baubau()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/baubau','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/baubau','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_baubau($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_baubau($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_baubau($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_fi = $this->model_bridging->insert_fi_baubau_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_baubau($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_baubau($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_baubau($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_baubau($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/baubau','refresh');
        
    }
    
    public function download_template_sales($filename)
    {
        $query = "
            select 	'' as siteid,
                    '' as nosales,
                    '' as tanggal_sales,
                    '' as salesmanid,
                    '' as nama_salesman,
                    '' as customerid,
                    '' as nama_customer,
                    '' as productid,
                    '' as product_descr,
                    '' as flag_retur,
                    '' as flag_bonus,
                    '' as harga,
                    '' as qty,
                    '' as bruto,
                    '' as disc_cabang,
                    '' as rp_cabang,
                    '' as disc_prinsipal,
                    '' as rp_prinsipal,
                    '' as disc_xtra,
                    '' as rp_xtra,
                    '' as disc_cash,
                    '' as rp_cash,
                    '' as netto            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto'
        ));
        $this->excel_generator->set_column(array
        ( 
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template '.$filename); 
    }

    public function download_template_baubau_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_baubau_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Baubau Customer'); 
    }

    public function baubau_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_baubau_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_baubau_import_customer");
            redirect('bridging/baubau','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_baubau_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_baubau_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('baubau');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/baubau/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/baubau','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/baubau','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/baubau','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($kategori == "" || $nama_site == "" || $regional == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $nama_type == "" || $class_id = "" || $class = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/baubau','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_baubau_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/baubau','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_baubau_customer','refresh');
    }

    public function result_baubau_customer()
    {
        $data = [
            "title" => "Result Bridging Baubau Customer",
            'url'   => 'bridging/baubau_submit_customer',
            'get_data' => $this->model_bridging->get_baubau_import_customer(),
            'get_summary' => $this->model_bridging->get_baubau_import_customer_summary(),
        ];
        // var_dump($data);die;
        $this->view($data, false, "result_baubau_customer");
    }

    public function mms_makasar()
    {
        $site_code = 'MMS1O';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Makasar Sales",
            "title_customer" => "Bridging Customer mms_makasar (Outlet)",
            'url'   => 'bridging/mms_makasar_import',
            'url_customer'   => 'bridging/mms_makasar_import_customer',
            'bridging'  => 'mms_makasar',
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
            
        ];
        $this->view($data, false, "mms_makasar");
    }

    public function mms_makasar_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('MMS');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/mms_makasar','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/mms_makasar','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_mms_makasar_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_mms_makasar_import");
            redirect('bridging/mms_makasar','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('mms_makasar');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/mms_makasar/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/mms_makasar','refresh');
            }

            $input_log_data = [
                "site_code" => "MMS1O",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("MMS1O".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/mms_makasar','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/mms_makasar','refresh');
                }

                for ($row = 6; $row <= $highestRow; $row++) 
                {   
                    $no    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    
                    $cell = $worksheet->getCellByColumnAndRow(1, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";die;
                    
                    // Membuat objek DateTime dari format asal
                    $tanggalAsli = DateTime::createFromFormat('d-m-Y', trim($cellValue));

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";die;
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        
                        if($tanggalAsli){ 
                            $tanggal = $tanggalAsli->format('Y-m-d');
                        }else{
                            $tanggal = $cellValue;
                        }
                        // echo "tanggal : ".$tanggal."<br>";die;
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            // echo strtotime($tanggal)."<br>";    
                            // echo "is_valid_tanggal : ".$tanggal_ym."<br>";die;
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                                
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $nota_manual    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_pelanggan    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $kode_barang    = (strlen($temp = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue())) == 7) ? substr($temp, 0, 6) : $temp;

                    // mencari data master produk di mpm
                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($kode_barang);
                    if($get_kodeprod->num_rows() > 0) {
                        $m_qty1 = $get_kodeprod->row()->qty1;
                        $m_besar = $get_kodeprod->row()->besar;
                        $m_qty2 = $get_kodeprod->row()->qty2;
                        $m_sedang = $get_kodeprod->row()->sedang;
                        $m_qty3 = $get_kodeprod->row()->qty3;
                        $m_kecil = $get_kodeprod->row()->kecil;
                        $is_valid_kodeprod = 1;
                    }else{
                        $m_qty1 = '';
                        $m_besar = '';
                        $m_qty2 = '';
                        $m_sedang = '';
                        $m_qty3 = '';
                        $m_kecil = '';
                        $is_valid_kodeprod = 0;
                    }
                    
                    // echo $m_qty1." ".$m_besar." ".$m_qty2." ".$m_sedang." ".$m_qty3." ".$m_kecil."<br>";die;

                    $nama_barang    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $raw_qty1    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $raw_besar    = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $raw_qty2    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $raw_sedang    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $raw_qty3    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $raw_kecil    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());

                    // menghitung result qty
                    $r_qty1 = $raw_qty1 * $m_qty1;
                    $r_qty2 = $raw_qty2 * $m_qty2;
                    $r_qty3 = $raw_qty3 * $m_qty3;

                    $total_unit = $r_qty1 + $r_qty2 + $r_qty3;

                    $harga    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $jumlah    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $tot_diskon    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $netto    = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $persen_global    = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $netto_2    = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_cabang    = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_prinsipal    = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_prinsipal    = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $disc_xtra    = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $rp_xtra    = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $pot_rp    = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $salesman_id    = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $alamat    = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $kota    = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_mms_makasar_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }


                    $data = [
                        'no' => $no,
                        'tanggal' => $tanggal,
                        'nota_manual' => $nota_manual,
                        'nama_pelanggan' => $nama_pelanggan,
                        'kode_barang' => $kode_barang,
                        'nama_barang' => $nama_barang,
                        'raw_qty1' => $raw_qty1,
                        'raw_besar' => $raw_besar,
                        'raw_qty2' => $raw_qty2,
                        'raw_sedang' => $raw_sedang,
                        'raw_qty3' => $raw_qty3,
                        'raw_kecil' => $raw_kecil,
                        'm_qty1' => $m_qty1,
                        'm_besar' => $m_besar,
                        'm_qty2' => $m_qty2,
                        'm_sedang' => $m_sedang,
                        'm_qty3' => $m_qty3,
                        'm_kecil' => $m_kecil,
                        'r_qty1' => $r_qty1,
                        'r_qty2' => $r_qty2,
                        'r_qty3' => $r_qty3,
                        'total_unit' => $total_unit,
                        'harga' => $harga,
                        'jumlah' => $jumlah,
                        'tot_diskon' => $tot_diskon,
                        'netto' => $netto,
                        'persen_global' => $persen_global,
                        'netto_2' => $netto_2,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'pot_rp' => $pot_rp,
                        'salesman_id' => $salesman_id,
                        'nama_salesman' => $nama_salesman,
                        'alamat' => $alamat,
                        'kota' => $kota,
                        'customerid' => $customerid,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_mms_makasar_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/mms_makasar','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_mms_makasar','refresh');
    }

    public function preview_mms_makasar()
    {
        $get_data = $this->model_bridging->get_mms_makasar_import();
        if ($get_data->num_rows() > 0) {
            $id_bridging_log = $get_data->row()->id_bridging_log;
        }else{
            $id_bridging_log = 0;
            $this->session->set_flashdata("pesan", "data yang anda upload kosong");
            redirect('bridging/mms_makasar','refresh');
        }
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_mms_makasar_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging MMS Makasar",
            'url'           => 'bridging/submit_mms_makasar',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_mms_makasar_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_mms_makasar");
    }

    public function submit_mms_makasar()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/mms_makasar','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/mms_makasar','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_mms_makasar($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_fi = $this->model_bridging->insert_fi_mms_makasar_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_mms_makasar($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/mms_makasar','refresh');
        
    }

    public function download_template_mms_makasar_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_mms_makasar_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template MMS Makasar Customer'); 
    }

    public function mms_makasar_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_mms_makasar_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_mms_makasar_import_customer");
            redirect('bridging/mms_makasar','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_mms_makasar_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_mms_makasar_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('mms_makasar');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/mms_makasar/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/mms_makasar','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/mms_makasar','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/mms_makasar','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($nama_site == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $class_id = "" || $class = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai nama_site or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/mms_makasar','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_mms_makasar_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/mms_makasar','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_mms_makasar_customer','refresh');
    }

    public function result_mms_makasar_customer()
    {
        $data = [
            "title" => "Result Bridging MMS Makasar Customer",
            'url'   => 'bridging/mms_makasar_submit_customer',
            'get_data' => $this->model_bridging->get_mms_makasar_import_customer(),
            'get_summary' => $this->model_bridging->get_mms_makasar_import_customer_summary(),
        ];
        // var_dump($data);die;
        $this->view($data, false, "result_mms_makasar_customer");
    }

    public function mms_bone()
    {
        $site_code = 'BNE1T';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging bone Sales",
            "title_customer" => "Bridging Customer MMS Bone (Outlet)",
            'url'   => 'bridging/mms_bone_import',
            'url_customer'   => 'bridging/mms_bone_import_customer',
            'bridging'  => "mms_bone",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
            
        ];
        $this->view($data, false, "mms_bone");
    }

    public function mms_bone_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('BNE');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/mms_bone','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/mms_bone','refresh');
        }


        // create table
        $create = $this->model_bridging->create_table_mms_bone_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_mms_bone_import");
            redirect('bridging/mms_bone','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('mms_bone');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/mms_bone/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/mms_bone','refresh');
            }

            $input_log_data = [
                "site_code" => "BNE1T",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("BNE1T".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/mms_bone','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/mms_bone','refresh');
                }

                for ($row = 6; $row <= $highestRow; $row++) 
                {   
                    $no    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    
                    $cell = $worksheet->getCellByColumnAndRow(1, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";die;
                    
                    // Membuat objek DateTime dari format asal
                    $tanggalAsli = DateTime::createFromFormat('d-m-Y', trim($cellValue));

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";die;
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        
                        if($tanggalAsli){ 
                            $tanggal = $tanggalAsli->format('Y-m-d');
                        }else{
                            $tanggal = $cellValue;
                        }
                        // echo "tanggal : ".$tanggal."<br>";die;
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            // echo strtotime($tanggal)."<br>";    
                            // echo "is_valid_tanggal : ".$tanggal_ym."<br>";die;
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                                
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $nota_manual    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_pelanggan    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $kode_barang    = (strlen($temp = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue())) == 7) ? substr($temp, 0, 6) : $temp;

                    // mencari data master produk di mpm
                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($kode_barang);
                    if($get_kodeprod->num_rows() > 0) {
                        $m_qty1 = $get_kodeprod->row()->qty1;
                        $m_besar = $get_kodeprod->row()->besar;
                        $m_qty2 = $get_kodeprod->row()->qty2;
                        $m_sedang = $get_kodeprod->row()->sedang;
                        $m_qty3 = $get_kodeprod->row()->qty3;
                        $m_kecil = $get_kodeprod->row()->kecil;
                        $is_valid_kodeprod = 1;
                    }else{
                        $m_qty1 = '';
                        $m_besar = '';
                        $m_qty2 = '';
                        $m_sedang = '';
                        $m_qty3 = '';
                        $m_kecil = '';
                        $is_valid_kodeprod = 0;
                    }
                    
                    // echo $m_qty1." ".$m_besar." ".$m_qty2." ".$m_sedang." ".$m_qty3." ".$m_kecil."<br>";die;

                    $nama_barang    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $raw_qty1    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $raw_besar    = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $raw_qty2    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $raw_sedang    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $raw_qty3    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $raw_kecil    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());

                    // menghitung result qty
                    $r_qty1 = $raw_qty1 * $m_qty1;
                    $r_qty2 = $raw_qty2 * $m_qty2;
                    $r_qty3 = $raw_qty3 * $m_qty3;

                    $total_unit = $r_qty1 + $r_qty2 + $r_qty3;

                    $harga    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $jumlah    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $tot_diskon    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $netto    = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $persen_global    = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $netto_2    = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_cabang    = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_prinsipal    = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_prinsipal    = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $disc_xtra    = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $rp_xtra    = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $pot_rp    = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $salesman_id    = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $alamat    = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $kota    = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_mms_bone_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }


                    $data = [
                        'no' => $no,
                        'tanggal' => $tanggal,
                        'nota_manual' => $nota_manual,
                        'nama_pelanggan' => $nama_pelanggan,
                        'kode_barang' => $kode_barang,
                        'nama_barang' => $nama_barang,
                        'raw_qty1' => $raw_qty1,
                        'raw_besar' => $raw_besar,
                        'raw_qty2' => $raw_qty2,
                        'raw_sedang' => $raw_sedang,
                        'raw_qty3' => $raw_qty3,
                        'raw_kecil' => $raw_kecil,
                        'm_qty1' => $m_qty1,
                        'm_besar' => $m_besar,
                        'm_qty2' => $m_qty2,
                        'm_sedang' => $m_sedang,
                        'm_qty3' => $m_qty3,
                        'm_kecil' => $m_kecil,
                        'r_qty1' => $r_qty1,
                        'r_qty2' => $r_qty2,
                        'r_qty3' => $r_qty3,
                        'total_unit' => $total_unit,
                        'harga' => $harga,
                        'jumlah' => $jumlah,
                        'tot_diskon' => $tot_diskon,
                        'netto' => $netto,
                        'persen_global' => $persen_global,
                        'netto_2' => $netto_2,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'pot_rp' => $pot_rp,
                        'salesman_id' => $salesman_id,
                        'nama_salesman' => $nama_salesman,
                        'alamat' => $alamat,
                        'kota' => $kota,
                        'customerid' => $customerid,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_mms_bone_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/mms_bone','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_mms_bone','refresh');
    }

    public function preview_mms_bone()
    {
        $get_data = $this->model_bridging->get_mms_bone_import();
        if ($get_data->num_rows() > 0) {
            $id_bridging_log = $get_data->row()->id_bridging_log;
        }else{
            $id_bridging_log = 0;
            $this->session->set_flashdata("pesan", "data yang anda upload kosong");
            redirect('bridging/mms_bone','refresh');
        }

        $is_invalid = $this->model_bridging->get_mms_bone_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging MMS Bone",
            'url'           => 'bridging/submit_mms_bone',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_mms_bone_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_mms_bone");
    }

    public function submit_mms_bone()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/mms_bone','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/mms_bone','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_mms_bone($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_fi = $this->model_bridging->insert_fi_mms_bone_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_mms_bone($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/mms_bone','refresh');
        
    }

    public function download_template_mms_bone_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_mms_bone_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template MMS Bone Customer'); 
    }

    public function mms_bone_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_mms_bone_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_mms_bone_import_customer");
            redirect('bridging/mms_bone','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_mms_bone_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_mms_bone_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('mms_bone');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/mms_bone/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/mms_bone','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/mms_bone','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/mms_bone','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($nama_site == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $class_id = "" || $class = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai nama_site or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/mms_bone','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_mms_bone_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/mms_bone','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_mms_bone_customer','refresh');
    }

    public function result_mms_bone_customer()
    {
        $data = [
            "title" => "Result Bridging MMS Bone Customer",
            'url'   => 'bridging/mms_bone_submit_customer',
            'get_data' => $this->model_bridging->get_mms_bone_import_customer(),
            'get_summary' => $this->model_bridging->get_mms_bone_import_customer_summary(),
        ];
        // var_dump($data);die;
        $this->view($data, false, "result_mms_bone_customer");
    }

    public function mms_parepare()
    {
        $site_code = 'PRE1U';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Pare-Pare Sales",
            "title_customer" => "Bridging Customer MMS Pare-Pare (Outlet)",
            'url'   => 'bridging/mms_parepare_import',
            'url_customer'   => 'bridging/mms_parepare_import_customer',
            'bridging'  => "mms_parepare",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
            
        ];
        $this->view($data, false, "mms_parepare");
    }

    public function mms_parepare_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('PRE');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/mms_parepare','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/mms_parepare','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_mms_parepare_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_mms_parepare_import");
            redirect('bridging/mms_parepare','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('mms_parepare');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/mms_parepare/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/mms_parepare','refresh');
            }

            $input_log_data = [
                "site_code" => "PRE1U",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("PRE1U".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/mms_parepare','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/mms_parepare','refresh');
                }

                for ($row = 6; $row <= $highestRow; $row++) 
                {   
                    $no    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    
                    $cell = $worksheet->getCellByColumnAndRow(1, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";die;
                    
                    // Membuat objek DateTime dari format asal
                    $tanggalAsli = DateTime::createFromFormat('d-m-Y', trim($cellValue));

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";die;
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        
                        if($tanggalAsli){ 
                            $tanggal = $tanggalAsli->format('Y-m-d');
                        }else{
                            $tanggal = $cellValue;
                        }
                        // echo "tanggal : ".$tanggal."<br>";die;
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            // echo strtotime($tanggal)."<br>";    
                            // echo "is_valid_tanggal : ".$tanggal_ym."<br>";die;
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                                
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $nota_manual    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_pelanggan    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $kode_barang    = (strlen($temp = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue())) == 7) ? substr($temp, 0, 6) : $temp;

                    // mencari data master produk di mpm
                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($kode_barang);
                    if($get_kodeprod->num_rows() > 0) {
                        $m_qty1 = $get_kodeprod->row()->qty1;
                        $m_besar = $get_kodeprod->row()->besar;
                        $m_qty2 = $get_kodeprod->row()->qty2;
                        $m_sedang = $get_kodeprod->row()->sedang;
                        $m_qty3 = $get_kodeprod->row()->qty3;
                        $m_kecil = $get_kodeprod->row()->kecil;
                        $is_valid_kodeprod = 1;
                    }else{
                        $m_qty1 = '';
                        $m_besar = '';
                        $m_qty2 = '';
                        $m_sedang = '';
                        $m_qty3 = '';
                        $m_kecil = '';
                        $is_valid_kodeprod = 0;
                    }
                    
                    // echo $m_qty1." ".$m_besar." ".$m_qty2." ".$m_sedang." ".$m_qty3." ".$m_kecil."<br>";die;

                    $nama_barang    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $raw_qty1    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $raw_besar    = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $raw_qty2    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $raw_sedang    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $raw_qty3    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $raw_kecil    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());

                    // menghitung result qty
                    $r_qty1 = $raw_qty1 * $m_qty1;
                    $r_qty2 = $raw_qty2 * $m_qty2;
                    $r_qty3 = $raw_qty3 * $m_qty3;

                    $total_unit = $r_qty1 + $r_qty2 + $r_qty3;

                    $harga    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $jumlah    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $tot_diskon    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $netto    = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $persen_global    = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $netto_2    = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_cabang    = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_prinsipal    = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_prinsipal    = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $disc_xtra    = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $rp_xtra    = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $pot_rp    = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $salesman_id    = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $alamat    = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $kota    = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_mms_parepare_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }


                    $data = [
                        'no' => $no,
                        'tanggal' => $tanggal,
                        'nota_manual' => $nota_manual,
                        'nama_pelanggan' => $nama_pelanggan,
                        'kode_barang' => $kode_barang,
                        'nama_barang' => $nama_barang,
                        'raw_qty1' => $raw_qty1,
                        'raw_besar' => $raw_besar,
                        'raw_qty2' => $raw_qty2,
                        'raw_sedang' => $raw_sedang,
                        'raw_qty3' => $raw_qty3,
                        'raw_kecil' => $raw_kecil,
                        'm_qty1' => $m_qty1,
                        'm_besar' => $m_besar,
                        'm_qty2' => $m_qty2,
                        'm_sedang' => $m_sedang,
                        'm_qty3' => $m_qty3,
                        'm_kecil' => $m_kecil,
                        'r_qty1' => $r_qty1,
                        'r_qty2' => $r_qty2,
                        'r_qty3' => $r_qty3,
                        'total_unit' => $total_unit,
                        'harga' => $harga,
                        'jumlah' => $jumlah,
                        'tot_diskon' => $tot_diskon,
                        'netto' => $netto,
                        'persen_global' => $persen_global,
                        'netto_2' => $netto_2,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'pot_rp' => $pot_rp,
                        'salesman_id' => $salesman_id,
                        'nama_salesman' => $nama_salesman,
                        'alamat' => $alamat,
                        'kota' => $kota,
                        'customerid' => $customerid,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_mms_parepare_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/mms_parepare','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_mms_parepare','refresh');
    }

    public function preview_mms_parepare()
    {
        $get_data = $this->model_bridging->get_mms_parepare_import();
        if ($get_data->num_rows() > 0) {
            $id_bridging_log = $get_data->row()->id_bridging_log;
        }else{
            $id_bridging_log = 0;
            $this->session->set_flashdata("pesan", "data yang anda upload kosong");
            redirect('bridging/mms_parepare','refresh');
        }

        $is_invalid = $this->model_bridging->get_mms_parepare_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging MMS Pare-Pare",
            'url'           => 'bridging/submit_mms_parepare',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_mms_parepare_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_mms_parepare");
    }

    public function submit_mms_parepare()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/mms_parepare','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/mms_parepare','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_mms_parepare($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_mms_parepare($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_mms_parepare($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_fi = $this->model_bridging->insert_fi_mms_parepare_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_mms_parepare($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_mms_parepare($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_mms_parepare($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_mms_parepare($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/mms_parepare','refresh');
        
    }

    public function download_template_mms_parepare_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_mms_parepare_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template MMS Pare-Pare Customer'); 
    }

    public function mms_parepare_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_mms_parepare_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_mms_parepare_import_customer");
            redirect('bridging/mms_parepare','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_mms_parepare_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_mms_parepare_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('mms_parepare');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/mms_parepare/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/mms_parepare','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/mms_parepare','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/mms_parepare','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($nama_site == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $class_id = "" || $class = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai nama_site or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/mms_parepare','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_mms_parepare_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/mms_parepare','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_mms_parepare_customer','refresh');
    }

    public function result_mms_parepare_customer()
    {
        $data = [
            "title" => "Result Bridging MMS Pare-Pare Customer",
            'url'   => 'bridging/mms_parepare_submit_customer',
            'get_data' => $this->model_bridging->get_mms_parepare_import_customer(),
            'get_summary' => $this->model_bridging->get_mms_parepare_import_customer_summary(),
        ];
        // var_dump($data);die;
        $this->view($data, false, "result_mms_parepare_customer");
    }

    public function pekanbaru_old()
    {
        $site_code = 'PKB51';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Pekanbaru Sales",
            "title_customer" => "Bridging Customer (Outlet)",
            'filename' => 'pekanbaru',
            'url'   => 'bridging/pekanbaru_import',
            'url_customer'   => 'bridging/pekanbaru_import_customer',
            'bridging'  => "pekanbaru",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "pekanbaru");

    }

    public function pekanbaru_import_old()
    {
        $month = $this->input->post('month');
        
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('PKB');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun);
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/pekanbaru','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_pekanbaru_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_pekanbaru_import");
            redirect('bridging/pekanbaru','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('pekanbaru');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/pekanbaru/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/pekanbaru','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'X') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/pekanbaru','refresh');
            }

            $input_log_data = [
                "site_code" => "PKB51",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("PKB51".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/pekanbaru','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/pekanbaru','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $siteid    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        $tanggal = $cellValue;
                        // echo "tanggal : ".$tanggal."<br>";
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $salesmanid    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_pekanbaru_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $nama_customer    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    // cek kodeproduk mpm
                    $productid = (strlen($temp = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())) == 7) ? substr($temp, 1, 6) : $temp;
                    
                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($productid);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $product_descr    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal= trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $qty_bonus = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());

                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_Descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => $bruto,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => $netto,
                        'qty_bonus' => $qty_bonus,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_pekanbaru_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/pekanbaru','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_pekanbaru','refresh');
    }

    public function preview_pekanbaru_old()
    {
        $get_data = $this->model_bridging->get_pekanbaru_import();
        $id_bridging_log = $get_data->row()->id_bridging_log;
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_pekanbaru_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging Pekanbaru",
            'url'           => 'bridging/submit_pekanbaru',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_pekanbaru_import_summary_old(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_pekanbaru");
    }

    public function submit_pekanbaru_old()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/pekanbaru','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/pekanbaru','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_pekanbaru($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_pekanbaru($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_pekanbaru($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_fi = $this->model_bridging->insert_fi_pekanbaru_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_ri = $this->model_bridging->insert_ri_pekanbaru($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_pekanbaru($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_pekanbaru($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_pekanbaru($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/pekanbaru','refresh');
        
    }

    public function download_template_pekanbaru_old()
    {
        $query = "
            select 	'' as siteid,
                    '' as nosales,
                    '' as tanggal_sales,
                    '' as salesmanid,
                    '' as nama_salesman,
                    '' as customerid,
                    '' as nama_customer,
                    '' as productid,
                    '' as product_descr,
                    '' as flag_retur,
                    '' as flag_bonus,
                    '' as harga,
                    '' as qty,
                    '' as bruto,
                    '' as disc_cabang,
                    '' as rp_cabang,
                    '' as disc_prinsipal,
                    '' as rp_prinsipal,
                    '' as disc_xtra,
                    '' as rp_xtra,
                    '' as disc_cash,
                    '' as rp_cash,
                    '' as netto,
                    '' as qty_bonus            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto',
            'qty_bonus'
        ));
        $this->excel_generator->set_column(array
        ( 
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto',
            'qty_bonus'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Sales Pekanbaru'); 
    }

    public function pekanbaru()
    {
        $site_code = 'PKB51';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Pekanbaru Sales",
            "title_customer" => "Bridging Customer (Outlet)",
            'filename' => 'pekanbaru',
            'url'   => 'bridging/pekanbaru_import',
            'url_customer'   => 'bridging/pekanbaru_import_customer',
            'bridging'  => "pekanbaru",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "pekanbaru");

    }

    public function pekanbaru_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('PKB');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/pekanbaru','refresh');
        }

        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/pekanbaru','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_pekanbaru_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_pekanbaru_import");
            redirect('bridging/pekanbaru','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('pekanbaru');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/pekanbaru/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/pekanbaru','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'DB') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/pekanbaru','refresh');
            }

            $input_log_data = [
                "site_code" => "PKB51",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("PKB51".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/pekanbaru','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/pekanbaru','refresh');
                }

                for ($row = 5; $row <= $highestRow; $row++) 
                {   
                    $NOTA = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    // echo "NOTA : ".$NOTA."<br>";die;
                    $NO = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        $tanggal = $cellValue;
                        // echo "tanggal : ".$tanggal."<br>";
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $TGLJATUH = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());

                    $KODE = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $cek_customer = $this->model_bridging->get_pekanbaru_customer($KODE);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }
                    
                    $KODEWILA = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $KODENPWP = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $POF = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $LKS = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $CASH = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());

                    // $KODEBARA = (strlen($temp = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue())) == 7) ? substr($temp, 1, 6) : $temp;
                    $temp = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());

                    if ($temp == 'D060001') {
                        // echo "KODEBARA : ".$temp."<br>";die;
                        $this->session->set_flashdata("pesan", "gagal upload file excel, silahkan perbaiki kode produk 'D060001'".$this->upload->display_errors());
                        redirect('bridging/pekanbaru','refresh');
                    }
                    
                    // Ambil hanya angka
                    $angka_saja = preg_replace('/[^0-9]/', '', $temp);
                    // echo "angka_saja : ".$angka_saja."<br>";
                    $len = strlen($angka_saja);

                    if ($len === 5) {
                        $KODEBARA = "0" . substr($angka_saja, 0, 5);
                        // echo "KODEBARA panjang 5: ".$KODEBARA."<br>";
                    }else {
                        $KODEBARA = $angka_saja;
                        // echo "KODEBARA panjang selain 5: ".$KODEBARA."<br>";
                    }
                    // echo "KODEBARA : ".$KODEBARA."<br>";
                    // die;
                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($KODEBARA);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $NAMABARA = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $GOL = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $MERK = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $SATUAN = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $QTY = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $RASIO = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $QTYKECIL = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $QTYBONUS = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $HARGA = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $DISCPERSEN = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $DISCISI1 = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $DISCISI2 = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $DISCPCS = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $DISCNOM = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $JUMLAH = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $ppn = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $catat1 = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $catat2 = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $username = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $auditname = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $tglinput = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $ket = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $terpilih = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $audit = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $kategori = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $kodeout = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $printsp = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $printspb = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $panjar = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $ongkos = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $ongppn = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $notappn = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $jenisjual = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $jenisdisc = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $persenppn = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $poin1 = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $nota_po = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $urut_po = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $opname = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $sn = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $ref = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $km = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $garansi = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $check1 = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $qtypo = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $bayar = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $eppn = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $hrgvalas = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $kodevalas = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $kurs = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $kodealias = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $nobatch = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $tgl_exp = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $kondisi = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $tglretur = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $divisi = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $discisi3 = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $discpcs2 = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $discpcs3 = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $dbox = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $card = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $namacard = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $namabank = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $konsinyasi = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $cekst = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $cekop = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $hapus = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $hrgdasar = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $hrgdisc = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $upload = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $notapanjar = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $potopanjar = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $potoppn = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $jam = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());
                    $bd = trim($worksheet->getCellByColumnAndRow(85, $row)->getValue());
                    $expedisi = trim($worksheet->getCellByColumnAndRow(86, $row)->getValue());
                    $notadok = trim($worksheet->getCellByColumnAndRow(87, $row)->getValue());
                    $tgldok = trim($worksheet->getCellByColumnAndRow(88, $row)->getValue());
                    $fn = trim($worksheet->getCellByColumnAndRow(89, $row)->getValue());
                    $qtybruto = trim($worksheet->getCellByColumnAndRow(90, $row)->getValue());
                    $kadar = trim($worksheet->getCellByColumnAndRow(91, $row)->getValue());
                    $reward = trim($worksheet->getCellByColumnAndRow(92, $row)->getValue());
                    $printdo = trim($worksheet->getCellByColumnAndRow(93, $row)->getValue());
                    $realisasi = trim($worksheet->getCellByColumnAndRow(94, $row)->getValue());
                    $materai = trim($worksheet->getCellByColumnAndRow(95, $row)->getValue());
                    $fee = trim($worksheet->getCellByColumnAndRow(96, $row)->getValue());
                    $pph = trim($worksheet->getCellByColumnAndRow(97, $row)->getValue());
                    $jlh_pph = trim($worksheet->getCellByColumnAndRow(98, $row)->getValue());
                    $kodearea = trim($worksheet->getCellByColumnAndRow(99, $row)->getValue());
                    $nama = trim($worksheet->getCellByColumnAndRow(100, $row)->getValue());
                    $alamat1 = trim($worksheet->getCellByColumnAndRow(101, $row)->getValue());
                    $kota = trim($worksheet->getCellByColumnAndRow(102, $row)->getValue());
                    $barkode = trim($worksheet->getCellByColumnAndRow(103, $row)->getValue());
                    $namapof = trim($worksheet->getCellByColumnAndRow(104, $row)->getValue());
                    $jalur = trim($worksheet->getCellByColumnAndRow(105, $row)->getValue());

                    
                    $data = [
                        'nota' => $NOTA,
                        'no' => $NO,
                        'tgl' => $tanggal,
                        'tgljatuh' => $TGLJATUH,
                        'kode' => $KODE,
                        'KODEWILA' => $KODEWILA,
                        'KODENPWP' => $KODENPWP,
                        'POF' => $POF,
                        'LKS' => $LKS,
                        'CASH' => $CASH,
                        'KODEBARA' => $KODEBARA,
                        'NAMABARA' => $NAMABARA,
                        'GOL' => $GOL,
                        'MERK' => $MERK,
                        'SATUAN' => $SATUAN,
                        'QTY' => $QTY,
                        'RASIO' => $RASIO,
                        'QTYKECIL' => $QTYKECIL,
                        'QTYBONUS' => $QTYBONUS,
                        'HARGA' => $HARGA,
                        'DISCPERSEN' => $DISCPERSEN,
                        'DISCISI1' => $DISCISI1,
                        'DISCISI2' => $DISCISI2,
                        'DISCPCS' => $DISCPCS,
                        'DISCNOM' => $DISCNOM,
                        'JUMLAH' => $JUMLAH,
                        'ppn' => $ppn,
                        'catat1' => $catat1,
                        'catat2' => $catat2,
                        'username' => $username,
                        'auditname' => $auditname,
                        'tglinput' => $tglinput,
                        'ket' => $ket,
                        'terpilih' => $terpilih,
                        'audit' => $audit,
                        'kategori' => $kategori,
                        'kodeout' => $kodeout,
                        'printsp' => $printsp,
                        'printspb' => $printspb,
                        'panjar' => $panjar,
                        'ongkos' => $ongkos,
                        'ongppn' => $ongppn,
                        'notappn' => $notappn,
                        'jenisjual' => $jenisjual,
                        'jenisdisc' => $jenisdisc,
                        'persenppn' => $persenppn,
                        'poin1' => $poin1,
                        'nota_po' => $nota_po,
                        'urut_po' => $urut_po,
                        'opname' => $opname,
                        'sn' => $sn,
                        'ref' => $ref,
                        'km' => $km,
                        'garansi' => $garansi,
                        'check1' => $check1,
                        'qtypo' => $qtypo,
                        'bayar' => $bayar,
                        'eppn' => $eppn,
                        'hrgvalas' => $hrgvalas,
                        'kodevalas' => $kodevalas,
                        'kurs' => $kurs,
                        'kodealias' => $kodealias,
                        'nobatch' => $nobatch,
                        'tgl_exp' => $tgl_exp,
                        'kondisi' => $kondisi,
                        'tglretur' => $tglretur,
                        'divisi' => $divisi,
                        'discisi3' => $discisi3,
                        'discpcs2' => $discpcs2,
                        'discpcs3' => $discpcs3,
                        'dbox' => $dbox,
                        'card' => $card,
                        'namacard' => $namacard,
                        'namabank' => $namabank,
                        'konsinyasi' => $konsinyasi,
                        'cekst' => $cekst,
                        'cekop' => $cekop,
                        'hapus' => $hapus,
                        'hrgdasar' => $hrgdasar,
                        'hrgdisc' => $hrgdisc,
                        'upload' => $upload,
                        'notapanjar' => $notapanjar,
                        'potopanjar' => $potopanjar,
                        'potoppn' => $potoppn,
                        'jam' => $jam,
                        'bd' => $bd,
                        'expedisi' => $expedisi,
                        'notadok' => $notadok,
                        'tgldok' => $tgldok,
                        'fn' => $fn,
                        'qtybruto' => $qtybruto,
                        'kadar' => $kadar,
                        'reward' => $reward,
                        'printdo' => $printdo,
                        'realisasi' => $realisasi,
                        'materai' => $materai,
                        'fee' => $fee,
                        'pph' => $pph,
                        'jlh_pph' => $jlh_pph,
                        'kodearea' => $kodearea,
                        'nama' => $nama,
                        'alamat1' => $alamat1,
                        'kota' => $kota,
                        'barkode' => $barkode,
                        'namapof' => $namapof,
                        'jalur' => $jalur,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_pekanbaru_import($data);
                }
                // die;
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/pekanbaru','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_pekanbaru','refresh');
    }

    public function preview_pekanbaru()
    {
        $get_data = $this->model_bridging->get_pekanbaru_import();
        if ($get_data->num_rows() > 0) {
            $id_bridging_log = $get_data->row()->id_bridging_log;
        }else{
            $id_bridging_log = 0;
            $this->session->set_flashdata("pesan", "data yang anda upload kosong");
            redirect('bridging/pekanbaru','refresh');
        }

        $is_invalid = $this->model_bridging->get_pekanbaru_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging Pekanbaru",
            'url'           => 'bridging/submit_pekanbaru',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_pekanbaru_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_pekanbaru");
    }

    public function submit_pekanbaru()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/pekanbaru','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/pekanbaru','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_pekanbaru($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_pekanbaru($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_pekanbaru($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_fi = $this->model_bridging->insert_fi_pekanbaru_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_ri = $this->model_bridging->insert_ri_pekanbaru($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_pekanbaru($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_pekanbaru($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_pekanbaru($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/pekanbaru','refresh');
        
    }

    public function download_template_pekanbaru()
    {
        $query = "
            select 	'' as siteid,
                    '' as nosales,
                    '' as tanggal_sales,
                    '' as salesmanid,
                    '' as nama_salesman,
                    '' as customerid,
                    '' as nama_customer,
                    '' as productid,
                    '' as product_descr,
                    '' as flag_retur,
                    '' as flag_bonus,
                    '' as harga,
                    '' as qty,
                    '' as bruto,
                    '' as disc_cabang,
                    '' as rp_cabang,
                    '' as disc_prinsipal,
                    '' as rp_prinsipal,
                    '' as disc_xtra,
                    '' as rp_xtra,
                    '' as disc_cash,
                    '' as rp_cash,
                    '' as netto,
                    '' as qty_bonus            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto',
            'qty_bonus'
        ));
        $this->excel_generator->set_column(array
        ( 
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto',
            'qty_bonus'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Sales Pekanbaru'); 
    }

    public function download_template_pekanbaru_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    CONCAT(\"'\", mapping_uli) AS mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_pekanbaru_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Pekanbaru Customer'); 
    }

    public function pekanbaru_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_pekanbaru_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_pekanbaru_import_customer");
            redirect('bridging/pekanbaru','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_pekanbaru_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_pekanbaru_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('pekanbaru');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/pekanbaru/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/pekanbaru','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 10000 ROW.");
                    redirect('bridging/pekanbaru','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/pekanbaru','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());   

                    $temp = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());

                    $mapping_uli = str_replace(["'", '"'], '', $temp); //hilangkan tanda petik

                    // echo "mapping_uli : ".$mapping_uli;die;

                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($nama_site == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $class_id = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/pekanbaru','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_pekanbaru_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/pekanbaru','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_pekanbaru_customer','refresh');
    }

    public function result_pekanbaru_customer()
    {
        $data = [
            "title" => "Result Bridging Pekanbaru Customer",
            'url'   => 'bridging/pekanbaru_submit_customer',
            'get_data' => $this->model_bridging->get_pekanbaru_import_customer(),
            'get_summary' => $this->model_bridging->get_pekanbaru_import_customer_summary(),
        ];
        $this->view($data, false, "result_pekanbaru_customer");
    }

    public function update_status($signature, $dp_bridging)
    {
        // echo $dp_bridging;die;
        $get_bridging_log_bysignature = $this->model_bridging->get_bridging_log_bysignature($signature);
        if(!$get_bridging_log_bysignature->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Data upload tidak ditemukan");
            redirect('bridging/'.$dp_bridging,'refresh');
        }

        $id_upload = $get_bridging_log_bysignature->row()->id_upload;
        $status_closing = $get_bridging_log_bysignature->row()->status_closing;

        // $get_mpm_upload = $this->model_bridging->get_mpm_upload($id_upload);
        if($id_upload == '' || $id_upload == null)
        {
            $this->session->set_flashdata("pesan", "Tidak Bisa Melakukan Closing Karena Data Upload Tidak Ditemukan");
            redirect('bridging/'.$dp_bridging,'refresh');
        }

        if($status_closing == 0)
        {
            $data = [
                "status_closing" => 1,
            ];
            $this->model_bridging->update_mpm_upload($data, $id_upload);

        }else{
             $this->session->set_flashdata("pesan", "Tidak Bisa Merubah Status Closing Silahkan Hubungi IT");
            redirect('bridging/'.$dp_bridging,'refresh');
        }

        $this->session->set_flashdata("pesan_success", "Data berhasil diupdate");
        redirect('bridging/'.$dp_bridging,'refresh');
    }

    public function sup_makasar()
    {
        $site_code = 'SUP2G';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging SUP Makasar Sales",
            "title_customer" => "Bridging Customer (Outlet)",
            'filename' => 'sup_makasar',
            'url'   => 'bridging/sup_makasar_import',
            'url_customer'   => 'bridging/sup_makasar_import_customer',
            'bridging'  => "sup_makasar",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "sup_makasar");

    }

    public function sup_makasar_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('SUP');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/sup_makasar','refresh');
        }

        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/sup_makasar','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_sup_makasar_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_sup_makasar_import");
            redirect('bridging/sup_makasar','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('sup_makasar');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/sup_makasar/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/sup_makasar','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/sup_makasar','refresh');
            }

            $input_log_data = [
                "site_code" => "SUP2G",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("SUP2G".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/sup_makasar','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/sup_makasar','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $siteid    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        $tanggal = $cellValue;
                        // echo "tanggal : ".$tanggal."<br>";
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $salesmanid    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_sup_makasar_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $nama_customer    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    // cek kodeproduk mpm
                    $productid = (strlen($temp = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())) == 5) ? '0' . $temp : $temp;
                    
                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($productid);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }
                    // echo "productid : ".$productid."<br>";

                    $product_descr    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal= trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $qty_bonus = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    
                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_Descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => $bruto,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => $netto,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_sup_makasar_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/sup_makasar','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_sup_makasar','refresh');
    }

    public function preview_sup_makasar()
    {
        $get_data = $this->model_bridging->get_sup_makasar_import();
        $id_bridging_log = $get_data->row()->id_bridging_log;
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_sup_makasar_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging_sup_makasar",
            'url'           => 'bridging/submit_sup_makasar',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_sup_makasar_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_sup_makasar");
    }

    public function submit_sup_makasar()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/sup_makasar','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/sup_makasar','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_sup_makasar($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_sup_makasar($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_sup_makasar($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_sup_makasar($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_sup_makasar($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_sup_makasar($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_sup_makasar($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/sup_makasar','refresh');
        
    }

    public function download_template_sup_makasar()
    {
        $query = "
            select 	'' as siteid,
                    '' as nosales,
                    '' as tanggal_sales,
                    '' as salesmanid,
                    '' as nama_salesman,
                    '' as customerid,
                    '' as nama_customer,
                    '' as productid,
                    '' as product_descr,
                    '' as flag_retur,
                    '' as flag_bonus,
                    '' as harga,
                    '' as qty,
                    '' as bruto,
                    '' as disc_cabang,
                    '' as rp_cabang,
                    '' as disc_prinsipal,
                    '' as rp_prinsipal,
                    '' as disc_xtra,
                    '' as rp_xtra,
                    '' as disc_cash,
                    '' as rp_cash,
                    '' as netto,
                    '' as qty_bonus            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto',
            'qty_bonus'
        ));
        $this->excel_generator->set_column(array
        ( 
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto',
            'qty_bonus'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Sales SUP MAKASAR'); 
    }

    public function download_template_sup_makasar_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_sup_makasar_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template SUP Makasar Customer'); 
    }

    public function sup_makasar_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_sup_makasar_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_sup_makasar_import_customer");
            redirect('bridging/sup_makasar','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_sup_makasar_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_sup_makasar_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('sup_makasar');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/sup_makasar/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/sup_makasar','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 10000 ROW.");
                    redirect('bridging/sup_makasar','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/sup_makasar','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    // $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $type_id = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $worksheet->getCellByColumnAndRow(36, $row)->getValue()));

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($nama_site == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $class_id = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/sup_makasar','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_sup_makasar_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/sup_makasar','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_sup_makasar_customer','refresh');
    }

    public function result_sup_makasar_customer()
    {
        $data = [
            "title" => "Result Bridging SUP Makasar Customer",
            'url'   => 'bridging/sup_makasar_submit_customer',
            'get_data' => $this->model_bridging->get_sup_makasar_import_customer(),
            'get_summary' => $this->model_bridging->get_sup_makasar_import_customer_summary(),
        ];
        $this->view($data, false, "result_sup_makasar_customer");
    }

    public function tarakan()
    {
        $site_code = 'KBT1E';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Tarakan Sales",
            "title_customer" => "Bridging Customer Tarakan (Outlet)",
            'url'   => 'bridging/tarakan_import',
            'url_customer'   => 'bridging/tarakan_import_customer',
            'bridging'  => "tarakan",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "tarakan");
    }

    public function tarakan_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('PUM');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/tarakan','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/tarakan','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_tarakan_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_tarakan_import");
            redirect('bridging/tarakan','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('tarakan');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/tarakan/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/tarakan','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/tarakan','refresh');
            }

            $input_log_data = [
                "site_code" => "KBT1E",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("KBT1E".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/tarakan','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/tarakan','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {   
                    $siteid    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        $tanggal = $cellValue;
                        // echo "tanggal : ".$tanggal."<br>";
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $salesmanid    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_tarakan_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $nama_customer    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    // cek kodeproduk mpm
                    $productid = (strlen($temp = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())) == 5) ? '0' . $temp : $temp;

                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($productid);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $product_descr    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal= trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());

                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_Descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => $bruto,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => $netto,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_tarakan_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/tarakan','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_tarakan','refresh');
    }

    public function preview_tarakan()
    {
        $get_data = $this->model_bridging->get_tarakan_import();
        $id_bridging_log = $get_data->row()->id_bridging_log;
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_tarakan_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging tarakan",
            'url'           => 'bridging/submit_tarakan',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_tarakan_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_tarakan");
    }

    public function submit_tarakan()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/tarakan','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/tarakan','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_tarakan($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_tarakan($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_tarakan($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_fi = $this->model_bridging->insert_fi_tarakan_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_tarakan($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_tarakan($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_tarakan($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_tarakan($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/tarakan','refresh');
        
    }

    public function download_template_tarakan()
    {
        $query = "
            select 	'' as siteid,
                    '' as nosales,
                    '' as tanggal_sales,
                    '' as salesmanid,
                    '' as nama_salesman,
                    '' as customerid,
                    '' as nama_customer,
                    '' as productid,
                    '' as product_descr,
                    '' as flag_retur,
                    '' as flag_bonus,
                    '' as harga,
                    '' as qty,
                    '' as bruto,
                    '' as disc_cabang,
                    '' as rp_cabang,
                    '' as disc_prinsipal,
                    '' as rp_prinsipal,
                    '' as disc_xtra,
                    '' as rp_xtra,
                    '' as disc_cash,
                    '' as rp_cash,
                    '' as netto            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto'
        ));
        $this->excel_generator->set_column(array
        ( 
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Tarakan'); 
    }

    public function download_template_tarakan_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_tarakan_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template Tarakan Customer'); 
    }

    public function tarakan_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_tarakan_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_tarakan_import_customer");
            redirect('bridging/tarakan','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_tarakan_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_tarakan_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('tarakan');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/tarakan/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/tarakan','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 10000 ROW.");
                    redirect('bridging/tarakan','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/tarakan','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    // $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $type_id = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $worksheet->getCellByColumnAndRow(36, $row)->getValue()));

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    // $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $kodesalur = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $worksheet->getCellByColumnAndRow(38, $row)->getValue()));

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($nama_site == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $class_id = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/tarakan','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_tarakan_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/tarakan','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_tarakan_customer','refresh');
    }

    public function result_tarakan_customer()
    {
        $data = [
            "title" => "Result Bridging tarakan Customer",
            'url'   => 'bridging/tarakan_submit_customer',
            'get_data' => $this->model_bridging->get_tarakan_import_customer(),
            'get_summary' => $this->model_bridging->get_tarakan_import_customer_summary(),
        ];
        // var_dump($data);die;
        $this->view($data, false, "result_tarakan_customer");
    }

    public function berau()
    {
        $site_code = 'KBB1F';
        $cek_hak = $this->model_bridging->get_bridging_hak_akses_by_site_code_userid($site_code, $this->userid)->row();
        if(!$cek_hak) {
            $this->session->set_flashdata("pesan", "user anda tidak diijinkan mengakses halaman ini");
            redirect('bridging/dashboard','refresh');
        }

        $data = [
            "title" => "Bridging Berau Sales",
            "title_customer" => "Bridging Customer Berau (Outlet)",
            'url'   => 'bridging/berau_import',
            'url_customer'   => 'bridging/berau_import_customer',
            'bridging'  => "berau",
            'get_bridging_log'  => $this->model_bridging->get_bridging_log_by_site_code($site_code)
        ];
        $this->view($data, false, "berau");
    }

    public function berau_import()
    {
        $month = $this->input->post('month');
        $bulan = explode('-', $month)[1];
        $tahun = explode('-', $month)[0];

        $newDate_sebelumnya = date('Y-m', strtotime($month. ' -1 months')); // mencari periode -1 bulan sebelumnya
        $tahun_sebelumnya = explode('-', $newDate_sebelumnya)[0];
        $bulan_sebelumnya = explode('-', $newDate_sebelumnya)[1];

        // cek data apakah bulan sebelumnya sudah closing bulanan atau belum
        $get_mpm_user = $this->model_bridging->get_mpm_user('KBB');
        $userid = $get_mpm_user->row()->id;

        $get_mpm_upload_bulan_lalu = $this->model_bridging->get_mpm_upload($userid, $bulan_sebelumnya, $tahun_sebelumnya, '1');
        if ($get_mpm_upload_bulan_lalu->num_rows() > 0) {
            $status_closing_bulan_lalu = $get_mpm_upload_bulan_lalu->row()->status_closing;
        } else {
            $status_closing_bulan_lalu = null; // atau default value lain seperti 0 atau ''
        }

        if ($status_closing_bulan_lalu == null) {
            $this->session->set_flashdata("pesan", "upload file gagal, data bulan sebelumnya belum di closing! silahkan upload data closing terlebih dahulu");
            redirect('bridging/berau','refresh');
        }

        // cek data apakah sudah closing bulanan atau belum
        $get_mpm_uplaod = $this->model_bridging->get_mpm_upload($userid, $bulan, $tahun, '');
        if ($get_mpm_uplaod->num_rows() > 0) {
            $status_closing = $get_mpm_uplaod->row()->status_closing;
        } else {
            $status_closing = null; // atau default value lain seperti 0 atau ''
        }
        
        if ($status_closing == '1') {
            $this->session->set_flashdata("pesan", "upload file gagal, data sudah di closing! silahkan hubungi IT");
            redirect('bridging/berau','refresh');
        }

        // create table
        $create = $this->model_bridging->create_table_berau_import();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_berau_import");
            redirect('bridging/berau','refresh');
        }

        // inisialisasi upload
        $init_upload = $this->attachment_config('berau');        
        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/berau/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/berau','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('bridging/berau','refresh');
            }

            $input_log_data = [
                "site_code" => "KBB1F",
                "bulan" => $month,
                "filename"  => $filename_excel,
                "signature" => md5("KBB1F".$month.$this->model_outlet_transaksi->timezone()),
                "created_at" => $this->model_outlet_transaksi->timezone(),
                "created_by" => $this->session->userdata('id'),
            ];

            $id_log = $this->model_bridging->input_bridging_log($input_log_data);

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 5000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 5000 ROW.");
                    redirect('bridging/berau','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/berau','refresh');
                }

                for ($row = 7; $row <= $highestRow; $row++) 
                {   
                    $siteid    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $cellValue = $cell->getValue();
                    // echo "cellValue : ".$cellValue."<br>";

                    $is_valid_tanggal = 1; // Nilai default valid
                    // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    if (is_numeric($cellValue)) {
                        // echo "is_numeric : ".$cellValue."<br>";
                        $unixTimestamp = ($cellValue - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                        $tanggal = date('Y-m-d', $unixTimestamp); // Format as needed
                        // echo "tanggal : ".$tanggal."<br>";

                        // Ekstrak tahun-bulan dari $tanggal untuk perbandingan
                        $tanggal_ym = date('Y-m', $unixTimestamp);
                        // Bandingkan dengan $month
                        if ($tanggal_ym !== $month) {
                            $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                        }

                        // echo "tanggal_ym : ".$tanggal_ym."<br>";    
                        // echo "month : ".$month."<br>";
                        // echo "is_valid_tanggal : ".$is_valid_tanggal."<br>";

                    } else {
                        $tanggal = $cellValue;
                        // echo "tanggal : ".$tanggal."<br>";
                        // Jika format $cellValue adalah string tanggal, coba ekstrak bulan
                        if (strtotime($tanggal)) {
                            $tanggal_ym = date('Y-m', strtotime($tanggal));
                            
                            if ($tanggal_ym !== $month) {
                                $is_valid_tanggal = 0; // Tandai sebagai tidak valid jika bulan berbeda
                            }
                        } else {
                            // Jika bukan format tanggal yang valid, tandai tidak valid
                            $is_valid_tanggal = 0;
                        }
                    }
                    // die;

                    $salesmanid    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman    = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid    = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                    // cek kodecustomer 
                    $cek_customer = $this->model_bridging->get_berau_customer($customerid);
                    if($cek_customer->num_rows() > 0) {
                        $is_valid_customer = 1;
                    }else{
                        $is_valid_customer = 0;
                    }

                    $nama_customer    = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    // cek kodeproduk mpm
                    $productid = (strlen($temp = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())) == 5) ? '0' . $temp : $temp;

                    $get_kodeprod = $this->model_bridging->get_master_product_by_kodeprod($productid);
                    if($get_kodeprod->num_rows() > 0) {
                        $is_valid_kodeprod = 1;
                    }else{
                        $is_valid_kodeprod = 0;
                    }

                    $product_descr    = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur    = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus    = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga    = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty    = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto    = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang    = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal= trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());

                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_Descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => $bruto,
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => $netto,
                        'is_valid_kodeprod'=> $is_valid_kodeprod,
                        'is_valid_tanggal' => $is_valid_tanggal,
                        'is_valid_customer'=> $is_valid_customer,
                        'id_bridging_log'  => $id_log
                    ];

                    $insert = $this->model_bridging->insert_berau_import($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/berau','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/preview_berau','refresh');
    }

    public function preview_berau()
    {
        $get_data = $this->model_bridging->get_berau_import();
        $id_bridging_log = $get_data->row()->id_bridging_log;
        // echo "id_bridging_log : ".$id_bridging_log;

        $is_invalid = $this->model_bridging->get_berau_import_where_is_valid_false();
        if($is_invalid->num_rows() > 0)
        {
            // echo "is_invalid : ".$is_invalid->num_rows();
            $params_invalid = 1;
        }else{
            $params_invalid = 0;
        }

        $data = [
            "title"         => "Preview Bridging berau",
            'url'           => 'bridging/submit_berau',
            'get_data'      => $get_data,
            'id_bridging_log'   => $id_bridging_log,
            'get_summary'   => $this->model_bridging->get_berau_import_summary(),
            'params_invalid' => $params_invalid
        ];
        $this->view($data, false, "preview_berau");
    }

    public function submit_berau()
    {
        $id_bridging_log = $this->input->post('id_bridging_log');

        $get_data_log = $this->model_bridging->get_bridging_log($id_bridging_log);
        if($get_data_log->num_rows() > 0)
        {
            $site_code = $get_data_log->row()->site_code;
            $kode_comp = substr($site_code, 0, 3);
            $nocab = substr($site_code, 3, 2);

            $bulan = $get_data_log->row()->bulan;

            $tahun_upload = substr($bulan, 0, 4);
            $bulan_upload = substr($bulan, 5, 2);
        }

        $get_userid = $this->model_bridging->get_userid_by_kode_comp($kode_comp);
        if($get_userid->num_rows() > 0)
        {
            $userid = $get_userid->row()->id;
        }else{
            $this->session->set_flashdata("pesan", "gagal mendapatkan userid, ".$this->upload->display_errors());
            redirect('bridging/berau','refresh');
        }

        $get_last_upload = $this->model_bridging->get_mpm_upload_where_closing_by_userid($userid);
        if($get_last_upload->num_rows() > 0)
        {
            $tahun_last_upload = $get_last_upload->row()->tahun;
            $bulan_last_upload = $get_last_upload->row()->bulan;
        }   

        if ($tahun_upload < $tahun_last_upload || ($tahun_upload == $tahun_last_upload && $bulan_upload <= $bulan_last_upload)) {
            $this->session->set_flashdata("pesan", "gagal upload file excel, tahun dan bulan lebih kecil dari tahun dan bulan terakhir diupload");
            redirect('bridging/berau','refresh');
        } 

        $delete_fi = $this->model_bridging->delete_fi_berau($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $delete_ri = $this->model_bridging->delete_ri_berau($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        
        $proses_fi = $this->model_bridging->insert_fi_berau($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        // $proses_fi = $this->model_bridging->insert_fi_berau_bonus($kode_comp, $nocab, $tahun_upload, $bulan_upload);
        $proses_ri = $this->model_bridging->insert_ri_berau($kode_comp, $nocab, $tahun_upload, $bulan_upload);

        $delete_tblang = $this->model_bridging->delete_tblang_berau($tahun_upload, $nocab);
        $delete_tabsales = $this->model_bridging->delete_tabsales_berau($tahun_upload, $nocab);
        $delete_tbkota = $this->model_bridging->delete_tbkota_berau($tahun_upload, $nocab);
        
        // die;

        $insert_tblang = $this->model_bridging->insert_tblang($tahun_upload, $site_code);
        $insert_tabsales = $this->model_bridging->insert_tabsales($tahun_upload, $site_code);
        $insert_tbkota = $this->model_bridging->insert_tbkota($tahun_upload, $nocab);

        // update bridging_log
        $get_data_result = $this->model_bridging->get_result($site_code, $tahun_upload, $bulan_upload);
        if($get_data_result->num_rows() > 0)
        {
            $total_unit = $get_data_result->row()->total_unit;
            $total_value = $get_data_result->row()->total_value;
        }else{
            $total_unit = 0;
            $total_value = 0;            
        }

        // die;

        $insert_upload = [
            "userid"        => $userid,
            "lastupload"    => $this->model_outlet_transaksi->timezone(),
            "tanggal"       => date('d', strtotime($this->model_outlet_transaksi->timezone())),
            "filename"      => "",
            "omzet"         => $total_value,
            "status"        => 1,
            "tahun"         => $tahun_upload,
            "bulan"         => $bulan_upload,
            "status_closing" => 0
        ];
        $id_upload = $this->model_bridging->insert_upload($insert_upload);

        // echo "id_upload : ".$id_upload;
        // die;

        $update_bridging = [
            'sum_omzet' => $total_value,
            'sum_unit' => $total_unit,
            'id_upload' => $id_upload
        ];
        $this->model_bridging->update_bridging_log($update_bridging, $id_bridging_log);

        $this->session->set_flashdata("pesan_success", "proses upload success");
        redirect('bridging/berau','refresh');
        
    }

    public function download_template_berau()
    {
        $query = "
            select 	'' as siteid,
                    '' as nosales,
                    '' as tanggal_sales,
                    '' as salesmanid,
                    '' as nama_salesman,
                    '' as customerid,
                    '' as nama_customer,
                    '' as productid,
                    '' as product_descr,
                    '' as flag_retur,
                    '' as flag_bonus,
                    '' as harga,
                    '' as qty,
                    '' as bruto,
                    '' as disc_cabang,
                    '' as rp_cabang,
                    '' as disc_prinsipal,
                    '' as rp_prinsipal,
                    '' as disc_xtra,
                    '' as rp_xtra,
                    '' as disc_cash,
                    '' as rp_cash,
                    '' as netto            
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto'
        ));
        $this->excel_generator->set_column(array
        ( 
            'siteid',
            'nosales',
            'tanggal_sales',
            'salesmanid',
            'nama_salesman',
            'customerid',
            'nama_customer',
            'productid',
            'product_descr',
            'flag_retur',
            'flag_bonus',
            'harga',
            'qty',
            'bruto',
            'disc_cabang',
            'rp_cabang',
            'disc_prinsipal',
            'rp_prinsipal',
            'disc_xtra',
            'rp_xtra',
            'disc_cash',
            'rp_cash',
            'netto'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template berau'); 
    }

    public function download_template_berau_customer()
    {
        $query = "
            select 	kategori,
                    nama_site,
                    regional,
                    customer_id,
                    mapping_uli,
                    mapping_nd6,
                    mapping_warung_pintar,
                    mapping_pbf,
                    prefix,
                    nama_customer,
                    alamat,
                    tipe_bayar,
                    top,
                    status_konsinyasi,
                    status_fuguh,
                    kelurahan_id,
                    nama_kelurahan,
                    kecamatan_id,
                    nama_kecamatan,
                    kota_id,
                    nama_kota,
                    propinsi_id,
                    nama_propinsi,
                    kode_pos,
                    telp,
                    fax,
                    email,
                    head_office_id,
                    nama_head_office,
                    company_id,
                    nama_company,
                    branch_id,
                    nama_branch_office,
                    site_id,
                    segment_id,
                    nama_segment, 
                    type_id,
                    nama_type,
                    class_id,
                    class,
                    spot_id,
                    no_ktp,
                    kartu_keluarga,
                    pln,
                    nama_penghubung,
                    alamat_penghubung,
                    telp_penghubung,
                    hubungan,
                    latitude,
                    longitude,
                    member,
                    black_list,
                    aktif,
                    show_alamat_pkp,
                    data_create,
                    pbf_izin_no_tdp_tgl,
                    pbf_izin_no_tdp,
                    pbf_izin_no_siup_tgl,
                    pbf_izin_no_siup,
                    pbf_izin_no_sito_tgl,
                    pbf_izin_no_sito,
                    pbf_izin_no_sipa_tgl,
                    pbf_izin_no_sipa,
                    pbf_izin_no_sia_tgl,
                    pbf_izin_no_sia,
                    pbf_izin_no_nib_tgl,
                    pbf_izin_no_nib,
                    pbf_izin_no_cdob_tgl,
                    pbf_izin_no_cdob,
                    pbf_asis_apoteker_tgl_sipa,
                    pbf_asis_apoteker_tgl_lahir,
                    pbf_asis_apoteker_telpon,
                    pbf_asis_apoteker_no_sipa,
                    pbf_asis_apoteker_no_ktp,
                    pbf_asis_apoteker_email,
                    pbf_asis_apoteker_nama,
                    pbf_asis_apoteker_alamat,
                    pbf_apoteker_tgl_sipa,
                    pbf_apoteker_tgl_lahir,
                    pbf_apoteker_telpon,
                    pbf_apoteker_no_sipa,
                    pbf_apoteker_no_ktp,
                    pbf_apoteker_nama,
                    pbf_apoteker_alamat,
                    pbf_apoteker_email
        from site.bridging_berau_import_customer a
        ";

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);

        $this->excel_generator->set_header(array
        (
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kategori',
            'nama_site',
            'regional',
            'customer_id',
            'mapping_uli',
            'mapping_nd6',
            'mapping_warung_pintar',
            'mapping_pbf',
            'prefix',
            'nama_customer',
            'alamat',
            'tipe_bayar',
            'top',
            'status_konsinyasi',
            'status_fuguh',
            'kelurahan_id',
            'nama_kelurahan',
            'kecamatan_id',
            'nama_kecamatan',
            'kota_id',
            'nama_kota',
            'propinsi_id',
            'nama_propinsi',
            'kode_pos',
            'telp',
            'fax',
            'email',
            'head_office_id',
            'nama_head_office',
            'company_id',
            'nama_company',
            'branch_id',
            'nama_branch_office',
            'site_id',
            'segment_id',
            'nama_segment', 
            'type_id',
            'nama_type',
            'class_id',
            'class',
            'spot_id',
            'no_ktp',
            'kartu_keluarga',
            'pln',
            'nama_penghubung',
            'alamat_penghubung',
            'telp_penghubung',
            'hubungan',
            'latitude',
            'longitude',
            'member',
            'black_list',
            'aktif',
            'show_alamat_pkp',
            'data_create',
            'pbf_izin_no_tdp_tgl',
            'pbf_izin_no_tdp',
            'pbf_izin_no_siup_tgl',
            'pbf_izin_no_siup',
            'pbf_izin_no_sito_tgl',
            'pbf_izin_no_sito',
            'pbf_izin_no_sipa_tgl',
            'pbf_izin_no_sipa',
            'pbf_izin_no_sia_tgl',
            'pbf_izin_no_sia',
            'pbf_izin_no_nib_tgl',
            'pbf_izin_no_nib',
            'pbf_izin_no_cdob_tgl',
            'pbf_izin_no_cdob',
            'pbf_asis_apoteker_tgl_sipa',
            'pbf_asis_apoteker_tgl_lahir',
            'pbf_asis_apoteker_telpon',
            'pbf_asis_apoteker_no_sipa',
            'pbf_asis_apoteker_no_ktp',
            'pbf_asis_apoteker_email',
            'pbf_asis_apoteker_nama',
            'pbf_asis_apoteker_alamat',
            'pbf_apoteker_tgl_sipa',
            'pbf_apoteker_tgl_lahir',
            'pbf_apoteker_telpon',
            'pbf_apoteker_no_sipa',
            'pbf_apoteker_no_ktp',
            'pbf_apoteker_nama',
            'pbf_apoteker_alamat',
            'pbf_apoteker_email'  
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Download Template berau Customer'); 
    }

    public function berau_import_customer()
    {        
        // create table
        $create = $this->model_bridging->create_table_berau_import_customer();
        if(!$create) {
            $this->session->set_flashdata("pesan", "gagal membuat table bridging_berau_import_customer");
            redirect('bridging/berau','refresh');
        }

        // add unique constraint
        $unique_customer_id = $this->model_bridging->add_unique_berau_import_customer('customer_id');
        $unique_mapping_uli = $this->model_bridging->add_unique_berau_import_customer('mapping_uli');

        // inisialisasi upload
        $init_upload = $this->attachment_config('berau');        
        if ($this->upload->do_upload('file_customer')) 
        {
            $upload_data = $this->upload->data();
            $filename_excel = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/bridging/$this->tahun_folder/berau/$filename_excel");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('bridging/berau','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet) 
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 10000) {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 10000 ROW.");
                    redirect('bridging/berau','refresh');
                }

                if ($highestRow <= 1) {
                    $this->session->set_flashdata("pesan", "Data yang anda upload kosong. Silahkan ulangi kembali.");
                    redirect('bridging/berau','refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++) 
                {  
                    $kategori    = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nama_site   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $regional    = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());                    
                    $mapping_uli = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $mapping_nd6 = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $mapping_warung_pintar = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $mapping_pbf = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $tipe_bayar = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $status_konsinyasi = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $status_fuguh = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $kelurahan_id = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $kecamatan_id = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kota_id = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());     
                    $propinsi_id = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $kode_pos = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $telp = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());     
                    $fax = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $email = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $head_office_id = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $nama_head_office = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());      
                    $company_id = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $nama_company = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $branch_id = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $nama_branch_office = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $site_id = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $segment_id = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());    
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    // $type_id = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $type_id = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $worksheet->getCellByColumnAndRow(36, $row)->getValue()));

                    // cek type_id
                    $cek_type = $this->model_bridging->get_type($type_id);
                    if(!$cek_type->num_rows() > 0) { // jika tidak ada
                        $is_valid_type_id = 0;
                    }else{
                        $is_valid_type_id = 1;
                    }

                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    // $kodesalur = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $kodesalur = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $worksheet->getCellByColumnAndRow(38, $row)->getValue()));

                    // cek kodesalur
                    $cek_class = $this->model_bridging->get_class($kodesalur);
                    if(!$cek_class->num_rows() > 0) { // jika tidak ada
                        $is_valid_class_id = 0;
                    }else{
                        $is_valid_class_id = 1;
                    }

                    $namasalur = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $spot_id = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $no_ktp = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $kartu_keluarga = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $pln = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_penghubung = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $alamat_penghubung = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $telp_penghubung = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $latitude = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $longitude = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $member = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $black_list = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $aktif = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $show_alamat_pkp = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $data_create = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $pbf_izin_no_tdp_tgl = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $pbf_izin_no_tdp_no = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $pbf_izin_no_siup_tgl = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $pbf_izin_no_siup = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $pbf_izin_no_sito_tgl = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $pbf_izin_no_sito = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $pbf_izin_no_sipa_tgl = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $pbf_izin_no_sipa = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $pbf_izin_no_sia_tgl = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $pbf_izin_no_sia = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $pbf_izin_no_nib_tgl = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $pbf_izin_no_nib = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $pbf_izin_no_cdob_tgl = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $pbf_izin_no_cdob = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $pbf_asis_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $pbf_asis_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $pbf_asis_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    $pbf_asis_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(72, $row)->getValue());
                    $pbf_asis_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(73, $row)->getValue());
                    $pbf_asis_apoteker_email = trim($worksheet->getCellByColumnAndRow(74, $row)->getValue());
                    $pbf_asis_apoteker_nama = trim($worksheet->getCellByColumnAndRow(75, $row)->getValue());
                    $pbf_asis_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(76, $row)->getValue());
                    $pbf_apoteker_tgl_sipa = trim($worksheet->getCellByColumnAndRow(77, $row)->getValue());
                    $pbf_apoteker_tgl_lahir = trim($worksheet->getCellByColumnAndRow(78, $row)->getValue());
                    $pbf_apoteker_telpon = trim($worksheet->getCellByColumnAndRow(79, $row)->getValue());
                    $pbf_apoteker_no_sipa = trim($worksheet->getCellByColumnAndRow(80, $row)->getValue());
                    $pbf_apoteker_no_ktp = trim($worksheet->getCellByColumnAndRow(81, $row)->getValue());
                    $pbf_apoteker_nama = trim($worksheet->getCellByColumnAndRow(82, $row)->getValue());
                    $pbf_apoteker_alamat = trim($worksheet->getCellByColumnAndRow(83, $row)->getValue());
                    $pbf_apoteker_email = trim($worksheet->getCellByColumnAndRow(84, $row)->getValue());

                    if($nama_site == "" || $customer_id == "" || $mapping_uli == ""  || $type_id == "" || $class_id = "") {
                        $this->session->set_flashdata("pesan_customer", "Data anda mempunyai kategori or nama_site or regional or customer_id or mapping_uli kosong.. Silahkan ulangi kembali.");
                        redirect('bridging/berau','refresh');
                    }

                    $data = [
                        "kategori"    => $kategori,
                        "nama_site"   => $nama_site,
                        "regional"    => $regional,
                        "customer_id" => $customer_id,
                        "mapping_uli" => $mapping_uli,
                        "mapping_nd6" => $mapping_nd6,
                        "mapping_warung_pintar" => $mapping_warung_pintar,
                        "mapping_pbf" => $mapping_pbf,
                        "prefix" => $prefix,
                        "nama_customer" => $nama_customer,
                        "alamat" => $alamat,
                        "tipe_bayar" => $tipe_bayar,
                        "top" => $top,
                        "status_konsinyasi" => $status_konsinyasi,
                        "status_fuguh" => $status_fuguh,
                        "kelurahan_id" => $kelurahan_id,
                        "nama_kelurahan" => $nama_kelurahan,
                        "kecamatan_id" => $kecamatan_id,
                        "nama_kecamatan" => $nama_kecamatan,
                        "kota_id" => $kota_id,
                        "nama_kota" => $nama_kota,
                        "propinsi_id" => $propinsi_id,
                        "nama_propinsi" => $nama_propinsi,
                        "kode_pos" => $kode_pos,
                        "telp" => $telp,
                        "fax" => $fax,
                        "email" => $email,
                        "head_office_id" => $head_office_id,
                        "nama_head_office" => $nama_head_office,
                        "company_id" => $company_id,
                        "nama_company" => $nama_company,
                        "branch_id" => $branch_id,
                        "nama_branch_office" => $nama_branch_office,
                        "site_id" => $site_id,
                        "segment_id" => $segment_id,
                        "nama_segment" => $nama_segment,
                        "type_id" => $type_id,
                        "nama_type" => $nama_type,
                        "class_id" => $kodesalur,
                        "class" => $namasalur,
                        "spot_id" => $spot_id,
                        "no_ktp" => $no_ktp,
                        "kartu_keluarga" => $kartu_keluarga,
                        "pln" => $pln,
                        "nama_penghubung" => $nama_penghubung,
                        "alamat_penghubung" => $alamat_penghubung,
                        "telp_penghubung" => $telp_penghubung,
                        "hubungan" => $hubungan,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "member" => $member,
                        "black_list" => $black_list,
                        "aktif" => $aktif,
                        "show_alamat_pkp" => $show_alamat_pkp,
                        "data_create" => $data_create,
                        "pbf_izin_no_tdp_tgl" => $pbf_izin_no_tdp_tgl,
                        "pbf_izin_no_tdp" => $pbf_izin_no_tdp_no,
                        "pbf_izin_no_siup_tgl" => $pbf_izin_no_siup_tgl,
                        "pbf_izin_no_siup" => $pbf_izin_no_siup,
                        "pbf_izin_no_sito_tgl" => $pbf_izin_no_sito_tgl,
                        "pbf_izin_no_sito" => $pbf_izin_no_sito,
                        "pbf_izin_no_sipa_tgl" => $pbf_izin_no_sipa_tgl,
                        "pbf_izin_no_sipa" => $pbf_izin_no_sipa,
                        "pbf_izin_no_cdob_tgl" => $pbf_izin_no_cdob_tgl,
                        "pbf_izin_no_cdob" => $pbf_izin_no_cdob,
                        "pbf_asis_apoteker_tgl_sipa" => $pbf_asis_apoteker_tgl_sipa,
                        "pbf_asis_apoteker_tgl_lahir" => $pbf_asis_apoteker_tgl_lahir,
                        "pbf_asis_apoteker_telpon" => $pbf_asis_apoteker_telpon,
                        "pbf_asis_apoteker_no_sipa" => $pbf_asis_apoteker_no_sipa,
                        "pbf_asis_apoteker_no_ktp" => $pbf_asis_apoteker_no_ktp,
                        "pbf_asis_apoteker_email" => $pbf_asis_apoteker_email,
                        "pbf_asis_apoteker_nama" => $pbf_asis_apoteker_nama,
                        "pbf_asis_apoteker_alamat" => $pbf_asis_apoteker_alamat,
                        "pbf_apoteker_tgl_sipa" => $pbf_apoteker_tgl_sipa,
                        "pbf_apoteker_tgl_lahir" => $pbf_apoteker_tgl_lahir,
                        "pbf_apoteker_telpon" => $pbf_apoteker_telpon,
                        "pbf_apoteker_no_sipa" => $pbf_apoteker_no_sipa,
                        "pbf_apoteker_no_ktp" => $pbf_apoteker_no_ktp,
                        "pbf_apoteker_nama" => $pbf_apoteker_nama,
                        "pbf_apoteker_alamat" => $pbf_apoteker_alamat,
                        "pbf_apoteker_email" => $pbf_apoteker_email,
                        "is_valid_type_id" => $is_valid_type_id,
                        "is_valid_class_id" => $is_valid_class_id                 
                    ];

                    $insert = $this->model_bridging->insert_berau_import_customer($data);
                }
            }
        }else
        {
            $this->session->set_flashdata("pesan", "gagal upload file excel, ".$this->upload->display_errors());
            redirect('bridging/berau','refresh');
        };

        $this->session->set_flashdata("pesan_success", "upload file excel berhasil, ".$this->upload->display_errors());
        redirect('bridging/result_berau_customer','refresh');
    }

    public function result_berau_customer()
    {
        $data = [
            "title" => "Result Bridging berau Customer",
            'url'   => 'bridging/berau_submit_customer',
            'get_data' => $this->model_bridging->get_berau_import_customer(),
            'get_summary' => $this->model_bridging->get_berau_import_customer_summary(),
        ];
        // var_dump($data);die;
        $this->view($data, false, "result_berau_customer");
    }

}
?>
