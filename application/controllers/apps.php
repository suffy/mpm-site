<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Apps extends MY_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'Apps';
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_apps'));
        $this->userid = $this->session->userdata('id');
    }

    // function apps()
    // {       
    //     $logged_in= $this->session->userdata('logged_in');
    //     if(!isset($logged_in) || $logged_in != TRUE)
    //     {
    //         redirect('login_sistem/','refresh');
    //     }
    //     set_time_limit(0);
        
    //     $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
    //     $this->load->helper(array('url', 'csv'));
    //     $this->load->model(array('model_outlet_transaksi','model_apps'));
    //     $this->userid = $this->session->userdata('id');
    // }

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

    public function landing()
    {
        $data = [
            "title"              => "Landing MPM Web x MPM Apps",
            'url_market_audit'   => 'gt_market_audit',
            'url_realisasi_event'=> 'gt_realisasi_event',
            'url_spreading'      => 'gt_spreading',
            'url_branding_delto_corner'=> 'gt_branding_delto_corner',
            'url_mt_activity'=> 'mt_activity',
            'url_mpm_market_visit'=> 'mpm_market_visit',
            'url_mpm_activity'=> 'mpm_activity',
            'url_join_call'=> 'gt_join_call',
            'url_attendance_setup'   => 'attendance_setup',
        ];
        
        $this->view($data, false, "landing", false);

        
        // $this->render('apps/landing', $data);
    }

    public function gt_join_call()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        if($from != "" && $to != "")
        {
            $get_data  = $this->model_apps->get_gt_join_call($from, $to, $user);
        }else{
            $get_data = [];
        }

        if ($submit == "export") {
            $this->export_gt_join_call($from, $to, $user);
            return;
        }

        $get_username_team = $this->model_apps->get_username_team_gt($this->userid);
        $username_team = "";
        foreach ($get_username_team->result() as $a) {
            $username_team.= "'".$a->username."',";
        }
        $username_team = substr($username_team, 0, -1);
        
        $data = [
            "title" => "GT Join Call",
            'url'   => 'gt_join_call',
            'get_data'  => $get_data,
            'users'  => $this->model_apps->get_join_call_groupby_user($username_team),
        ];
        
        $this->view($data, false, "gt_join_call", true);
    }

    public function export_gt_join_call($from, $to, $user)
    {
        $get_data = $this->model_apps->get_gt_join_call($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','nama_toko','result_visit','latitude','longitude','formatted_address','city', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','nama_toko','result_visit','latitude','longitude','formatted_address','city', 'created_at'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export gt join call"); 
    }

    public function gt_market_audit()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        if($from != "" && $to != "")
        {
            $get_data  = $this->model_apps->get_gt_market_audit($from, $to, $user);
        }else{
            $get_data = [];
        }

        if ($submit == "export") {
            $this->export_gt_market_audit($from, $to, $user);
            return;
        }

        if ($submit == "export_pdf") {
            $this->export_gt_market_audit_pdf($from, $to, $user);
            return;
        }

        $get_username_team = $this->model_apps->get_username_team_gt($this->userid);
        $username_team = "";
        foreach ($get_username_team->result() as $a) {
            $username_team.= "'".$a->username."',";
        }
        $username_team = substr($username_team, 0, -1);
        
        $data = [
            "title" => "GT Market Audit",
            'url'   => 'gt_market_audit',
            'get_data'  => $get_data,
            'users'  => $this->model_apps->get_market_audit_groupby_user($username_team),
        ];
        
        $this->view($data, false, "gt_market_audit", true);
    }

    public function export_gt_market_audit($from, $to, $user)
    {
        $get_data = $this->model_apps->get_gt_market_audit($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','nama_pasar','nama_kecamatan','nama_toko','posisi_toko','class_toko','tipe_toko','status_ob','availability_obat_masuk_angin',
            'availability_obat_batuk_sachet','availability_obat_masuk_angin_anak','availability_obat_pegel_linu',
            'availability_permen',
            'latitude','longitude','formatted_address','city', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','nama_pasar','nama_kecamatan','nama_toko','posisi_toko','class_toko','tipe_toko', 'status_ob','availability_obat_masuk_angin',
            'availability_obat_batuk_sachet','availability_obat_masuk_angin_anak','availability_obat_pegel_linu',
            'availability_permen','latitude','longitude','formatted_address','city', 'created_at'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export gt market audit"); 
    }

    public function export_gt_market_audit_pdf($from, $to, $user)
    {
        $this->load->library('mypdf');        
        // Get data from model
        $query = $this->model_apps->get_gt_market_audit($from, $to, $user);
        $data_list = [];
        
        // Cache untuk gambar yang sudah di-download (in-memory selama request ini)
        $image_cache = [];
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // Gunakan cache untuk menghindari download gambar yang sama berulang
                $image_data = $this->get_cached_image($row->foto_toko, $image_cache);
                $image_data_produk = $this->get_cached_image($row->foto_display_produk, $image_cache);
                $image_data_branding = $this->get_cached_image($row->foto_branding, $image_cache);
                
                $data_list[] = [
                    'username' => $row->username,
                    'nama_pasar' => $row->nama_pasar,
                    'nama_toko' => $row->nama_toko,
                    'foto_toko' => $image_data,
                    'foto_url' => $row->foto_toko,
                    'foto_produk' => $image_data_produk,
                    'foto_url_produk' => $row->foto_display_produk,
                    'foto_branding' => $image_data_branding,
                    'foto_url_branding' => $row->foto_branding,
                ];
            }
        }
        
        $data = [
            'title' => "GT Market Audit",   
            'get_data' => $data_list,
            'from_date' => $from,
            'to_date' => $to
        ];

        $generate_pdf = $this->mypdf->generate('apps/template_gt_market_audit', $data, 'test', 'A4', 'landscape');   
    }

    private function get_cached_image($image_url, &$cache)
    {
        if (empty($image_url)) {
            return null;
        }
        
        // Cek cache in-memory terlebih dahulu
        if (isset($cache[$image_url])) {
            return $cache[$image_url];
        }
        
        $cache_key = md5($image_url);
        $cache_dir = APPPATH . 'cache/image_cache/';
        $cache_file = $cache_dir . $cache_key . '.cache';
        
        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, 0755, true);
        }
        
        // Cek cache file (1 hari expiry)
        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 86400) {
            $image_data = file_get_contents($cache_file);
            $cache[$image_url] = $image_data;
            return $image_data;
        }
        
        // Download gambar
        $image_content = $this->download_image($image_url);
        if ($image_content === false) {
            // Fallback ke cache lama jika ada
            if (file_exists($cache_file)) {
                $image_data = file_get_contents($cache_file);
                $cache[$image_url] = $image_data;
                return $image_data;
            }
            $cache[$image_url] = null;
            return null;
        }
        
        // Optimasi gambar: resize dan kompresi
        $optimized_image = $this->optimize_image($image_content);
        
        if ($optimized_image === false) {
            $cache[$image_url] = null;
            return null;
        }
        
        // Simpan ke cache file
        file_put_contents($cache_file, $optimized_image);
        
        // Simpan ke memory cache
        $cache[$image_url] = $optimized_image;
        
        return $optimized_image;
    }

    private function download_image($url)
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 15,
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n"
            ]
        ]);
        
        return @file_get_contents($url, false, $context);
    }

    private function optimize_image($image_content, $max_width = 800, $quality = 70)
    {
        // Coba create image dari string
        $image = @imagecreatefromstring($image_content);
        if ($image === false) {
            return false;
        }
        
        // Dapatkan dimensi original
        $original_width = imagesx($image);
        $original_height = imagesy($image);
        
        // Jika gambar sudah lebih kecil dari max_width, hanya kompres saja
        if ($original_width <= $max_width) {
            $optimized_image = $this->compress_image($image, $quality);
            imagedestroy($image);
            return $optimized_image;
        }
        
        // Calculate new dimensions
        $new_width = $max_width;
        $new_height = intval($original_height * ($max_width / $original_width));
        
        // Create new image
        $new_image = imagecreatetruecolor($new_width, $new_height);
        
        // Preserve transparency untuk PNG/GIF
        if (function_exists('imagealphablending') && function_exists('imagesavealpha')) {
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
            imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
        }
        
        // Resize image
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
        
        // Kompresi gambar
        $optimized_content = $this->compress_image($new_image, $quality);
        
        // Clean up
        imagedestroy($image);
        imagedestroy($new_image);
        
        return $optimized_content;
    }

    private function compress_image($image_resource, $quality = 70)
    {
        ob_start();
        
        // Coba save sebagai JPEG dulu (lebih kecil)
        if (function_exists('imagejpeg')) {
            imagejpeg($image_resource, null, $quality);
        } 
        // Fallback ke PNG jika JPEG tidak support
        else if (function_exists('imagepng')) {
            imagepng($image_resource, null, 9); // PNG compression level 0-9
        }
        // Fallback terakhir
        else {
            ob_end_clean();
            return false;
        }
        
        $compressed_content = ob_get_clean();
        
        // Konversi ke base64
        $mime_type = 'image/jpeg'; // Default ke JPEG
        $base64_data = 'data:' . $mime_type . ';base64,' . base64_encode($compressed_content);
        
        return $base64_data;
    }

    public function gt_realisasi_event()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        if($from != "" && $to != "")
        {
            $get_data  = $this->model_apps->get_gt_realisasi_event($from, $to, $user);
        }else{
            $get_data = [];
        }

        if ($submit == "export") {
            $this->export_gt_realisasi_event($from, $to, $user);
            return;
        }

        $get_username_team = $this->model_apps->get_username_team_gt($this->userid);
        $username_team = "";
        foreach ($get_username_team->result() as $a) {
            $username_team.= "'".$a->username."',";
        }
        $username_team = substr($username_team, 0, -1);
        
        $data = [
            "title" => "GT Realisasi Event",
            'url'   => 'gt_realisasi_event',
            'get_data'  => $get_data,
            'users'  => $this->model_apps->get_realisasi_event_groupby_user($username_team),
        ];
        
        $this->view($data, false, "gt_realisasi_event", true);
    }

    public function export_gt_realisasi_event($from, $to, $user)
    {
        $get_data = $this->model_apps->get_gt_realisasi_event($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'nama','tanggal','lokasi','brand','kategori','audience','target_selling','actual_selling','achievement','latitude','longitude','formatted_address','city', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'nama','tanggal','lokasi','brand','kategori','audience','target_selling','actual_selling','achievement','latitude','longitude','formatted_address','city', 'created_at'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export gt realisasi event"); 
    }

    public function gt_branding_delto_corner()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        if($from != "" && $to != "")
        {
            $get_data  = $this->model_apps->get_gt_branding_delto_corner($from, $to, $user);
        }else{
            $get_data = [];
        }

        if ($submit == "export") {
            $this->export_gt_branding_delto_corner($from, $to, $user);
            return;
        }

        $get_username_team = $this->model_apps->get_username_team_gt($this->userid);
        $username_team = "";
        foreach ($get_username_team->result() as $a) {
            $username_team.= "'".$a->username."',";
        }
        $username_team = substr($username_team, 0, -1);
        
        $data = [
            "title" => "GT Branding Delto Corner",
            'url'   => 'gt_branding_delto_corner',
            'get_data'  => $get_data,
            'users'  => $this->model_apps->get_branding_delto_corner_groupby_user($username_team),
        ];
        
        $this->view($data, false, "gt_branding_delto_corner", true);
    }

    public function export_gt_branding_delto_corner($from, $to, $user)
    {
        $get_data = $this->model_apps->get_gt_branding_delto_corner($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','nama_toko','kode_toko','brand', 'latitude','longitude','formatted_address','city', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','nama_toko','kode_toko','brand', 'latitude','longitude','formatted_address','city', 'created_at'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export gt branding delto corner"); 
    }

    public function gt_spreading()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        if($from != "" && $to != "")
        {
            $get_data  = $this->model_apps->get_gt_spreading($from, $to, $user);
        }else{
            $get_data = [];
        }

        if ($submit == "export") {
            $this->export_gt_spreading($from, $to, $user);
            return;
        }

        $get_username_team = $this->model_apps->get_username_team_gt($this->userid);
        $username_team = "";
        foreach ($get_username_team->result() as $a) {
            $username_team.= "'".$a->username."',";
        }
        $username_team = substr($username_team, 0, -1);
        
        $data = [
            "title" => "GT Spreading",
            'url'   => 'gt_spreading',
            'get_data'  => $get_data,
            'users'  => $this->model_apps->get_spreading_groupby_user($username_team),
        ];
        
        $this->view($data, false, "gt_spreading", true);
    }

    public function export_gt_spreading($from, $to, $user)
    {
        $get_data = $this->model_apps->get_gt_spreading($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username', 'nama_toko', 'keterangan', 'total_value', 'kodeprod', 'latitude', 'longitude', 'formatted_address', 'city', 'created_at', 'transaksi'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username', 'nama_toko', 'keterangan', 'total_value', 'kodeprod', 'latitude', 'longitude', 'formatted_address', 'city', 'created_at', 'transaksi'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export gt spreading"); 
    }

    public function mt_activity()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        if($from != "" && $to != "")
        {
            $get_data  = $this->model_apps->get_mt_activity($from, $to, $user);
        }else{
            $get_data = [];
        }

        if ($submit == "export") {
            $this->export_mt_activity($from, $to, $user);
            return;
        }

        // echo "aa";
        // die;

        $get_username_team = $this->model_apps->get_username_team_mt($this->userid);
        $username_team = "";
        foreach ($get_username_team->result() as $a) {
            $username_team.= "'".$a->username."',";
        }
        $username_team = substr($username_team, 0, -1);
        
        $data = [
            "title" => "MT Activity",
            'url'   => 'mt_activity',
            'get_data'  => $get_data,
            'users'  => $this->model_apps->get_mt_activity_groupby_user($username_team),
        ];
        
        $this->view($data, false, "mt_activity", true);
    }

    public function export_spreading($from, $to, $user)
    {
        $get_data = $this->model_apps->get_spreading_join_survei_transaksi_image($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'nama_toko','keterangan','city','district','formatted_address', 
            'name_address', 'postal_code', 'region', 'street', 'total_value','checkin_time','checkout_time','durasi',
            'count_availability', 'kodeprod_availability', 'image_before', 'image_after'
        ));
        $this->excel_generator->set_column(array
        ( 
            'nama_toko','keterangan','city','district','formatted_address', 
            'name_address', 'postal_code', 'region', 'street', 'total_value','checkin_time','checkout_time','durasi',
            'count_availability', 'kodeprod_availability', 'image_before', 'image_after'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export"); 
    }

    public function export_market_visit($from, $to, $user)
    {
        $get_data = $this->model_apps->get_market_visit($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','nama_pasar','nama_kecamatan','nama_toko','posisi_toko','class_toko','tipe_toko','status_ob',
            'availability_obat_masuk_angin','availability_obat_batuk_sachet','availability_obat_masuk_angin_anak',
            'availability_obat_pegel_linu'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','nama_pasar','nama_kecamatan','nama_toko','posisi_toko','class_toko','tipe_toko','status_ob',
            'availability_obat_masuk_angin','availability_obat_batuk_sachet','availability_obat_masuk_angin_anak',
            'availability_obat_pegel_linu'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export market visit"); 
    }

    public function export_mt_activity($from, $to, $user)
    {
        $get_data = $this->model_apps->get_mt_activity($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','toko', 'category', 'result_visit', 'result_competitor', 'keterangan', 
            'checkin_time', 'checkout_time',
            'latitude', 'longitude', 'formatted_address', 'city', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','toko', 'category', 'result_visit', 'result_competitor', 'keterangan', 
            'checkin_time', 'checkout_time',
            'latitude', 'longitude', 'formatted_address', 'city', 'created_at'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export mt activity"); 
    }

    public function mpm_market_visit()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        if($from != "" && $to != "")
        {
            $get_data  = $this->model_apps->get_mpm_market_visit($from, $to, $user);
        }else{
            $get_data = [];
        }

        if ($submit == "export") {
            $this->export_mpm_market_visit($from, $to, $user);
            return;
        }

        $get_username_team = $this->model_apps->get_username_team($this->userid);
        $username_team = "";
        foreach ($get_username_team->result() as $a) {
            $username_team.= "'".$a->username."',";
        }
        $username_team = substr($username_team, 0, -1);
        
        $data = [
            "title" => "MPM Market Visit",
            'url'   => 'mpm_market_visit',
            'get_data'  => $get_data,
            'users'  => $this->model_apps->get_mpm_market_visit_groupby_user($username_team),
        ];
        
        $this->view($data, false, "mpm_market_visit", true);
    }

    public function mpm_activity()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        if($from != "" && $to != "")
        {
            $get_data  = $this->model_apps->get_mpm_activity($from, $to, $user);
            $get_summary  = $this->model_apps->get_mpm_summary_activity($from, $to, $user);
        }else{
            $get_data = [];
            $get_summary = [];
        }

        if ($submit == "export") {
            $this->export_mpm_activity($from, $to, $user);
            return;
        }

        $get_username_team = $this->model_apps->get_username_team($this->userid);
        $username_team = "";
        foreach ($get_username_team->result() as $a) {
            $username_team.= "'".$a->username."',";
        }
        $username_team = substr($username_team, 0, -1);
        
        $data = [
            "title" => "MPM Activity",
            'url'   => 'mpm_activity',
            'get_data'  => $get_data,
            'get_summary'  => $get_summary,
            'users'  => $this->model_apps->get_mpm_activity_groupby_user($username_team),
        ];
        
        $this->view($data, false, "mpm_activity", true);
    }

    public function export_realisasi_event($from, $to, $user)
    {
        $get_data = $this->model_apps->get_realisasi_event($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','nama','tanggal','lokasi','brand','kategori','audience','target_selling','actual_selling','achievement'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','nama','tanggal','lokasi','brand','kategori','audience','target_selling','actual_selling','achievement'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export realisasi event"); 
    }

    public function export_branding_delto_corner($from, $to, $user)
    {
        $get_data = $this->model_apps->get_branding_delto_corner($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','nama_toko','kode_toko','brand'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','nama_toko','kode_toko','brand'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export branding delto corner"); 
    }

    public function export_mt_market_visit($from, $to, $user)
    {
        $get_data = $this->model_apps->get_mt_market_visit($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','nama_toko','category','durasi','result_visit', 'result_competitor'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','nama_toko','category','durasi','result_visit', 'result_competitor'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export mt market visit"); 
    }

    public function export_mpm_market_visit($from, $to, $user)
    {
        $get_data = $this->model_apps->get_mpm_market_visit($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username', 'status_visit','subbranch','status_ob', 'toko', 'class_toko', 'tipe_toko',
            'materi', 'result', 'deadline_followup', 'pic', 'nohp', 'keterangan', 'created_at',
            'latitude', 'longitude', 'city', 'formatted_address'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username', 'status_visit','subbranch','status_ob', 'toko', 'class_toko', 'tipe_toko',
            'materi', 'result', 'deadline_followup', 'pic', 'nohp', 'keterangan', 'created_at',
            'latitude', 'longitude', 'city', 'formatted_address'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export mpm market visit"); 
    }

    public function export_mpm_activity($from, $to, $user)
    {
        $get_data = $this->model_apps->get_mpm_activity($from, $to, $user);
        // var_dump($get_data);

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username', 'type', 'result', 'created_at',
            'latitude', 'longitude', 'city', 'formatted_address'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username', 'type', 'result', 'created_at',
            'latitude', 'longitude', 'city', 'formatted_address'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export mpm daily activity"); 
    }

    public function activity()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');
        $submit = $this->input->get('submit');
        $user = $this->input->get('user');

        // echo "user : ".$user;
        // echo "submit : ".$submit;
        // echo "from : ".$from;
        // echo "to : ".$to;

        if($from && $to)
        {
            $get_data  = $this->model_apps->get_posting_log($from, $to, $user);
        }else{
            $get_data = [];
        }

        if ($submit == "export") {
            $this->export_posting_log($from, $to, $user);
            return;
        }
        
        $data = [
            "title" => "MPM Activity",
            'url'   => 'activity',
            'get_data'  => $get_data,
            'users'  => $this->model_apps->get_posting_log_groupby_username(),
        ];
        
        $this->view($data, false, "activity");
    }

    public function export_posting_log($from, $to, $user)
    {
        $get_data = $this->model_apps->get_posting_log($from, $to, $user);
        // var_dump($get_data);
        // die;

        $this->excel_generator->set_query($get_data);
        $this->excel_generator->set_header(array
        (
            'username','toko','result','result_competitor','formatted_address', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'username','toko','content','content_competitor','formatted_address', 'created_at'
        ));
        $this->excel_generator->set_width(array(15, 15, 15, 15, 15, 15)); 
        $this->excel_generator->exportTo2007("export mpm activity"); 
    }

    public function dashboard()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');

        $submit = $this->input->get('submit');

        // echo "submit : ".$submit;

        if ($submit == "export") {
            $this->model_deltomed->export_spreading($from, $to);
            return;
        }

        if ($submit == "export_by_products") {
            $this->model_deltomed->export_spreading_products($from, $to);
            return;
        }
        

        // echo "from : ".$from." to : ".$to;

        $data = [
            "title" => "Deltomed Spreading",
            'url'   => 'deltomed/dashboard',
            'get_data'  => $this->model_deltomed->get_spreading_post($from, $to),
        ];
        $this->view($data, false, "dashboard");
    }

    private function view($data, $flag_accordion, $view, $css_cisk = false)
    {
        if($css_cisk == false)
        {
            $data = [
                "navbar"        => $this->navbar($data),
                "css"           => $this->load->view('apps/components/header', $data),
                "view"          => $this->load->view('apps/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        }else{
            $data = [
                "navbar"        => $this->navbar($data),
                "full_width"    => $this->load->view('kalimantan/header_full_width', $data),
                "css"           => $this->load->view('management_claim/css', $data),
                "view"          => $this->load->view('apps/'.$view.'', $data),
                "footer"        => $this->load->view('kalimantan/footer')
            ];
        }
        return $data;       
    }

    public function posting()
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');

        // echo "from : ".$from." to : ".$to;

        $data = [
            "title" => "Daily Activity MPM AREA",
            'url'   => 'deltomed/posting',
            'get_data'  => $this->model_deltomed->get_posting_post($from, $to),
        ];
        $this->view($data, false, "posting");
    }

    public function test_transaction()
    {
        $this->db->trans_start();
        
        // Operasi 1
        $this->db->insert('test.test_table', ['field' => 'value1']);
        
        // Operasi 2 (sengaja error)
        $this->db->insert('test.test_table', ['invalid_field' => 'value2']);
        
        $this->db->trans_complete();
        
        echo $this->db->trans_status() ? 'Success' : 'Failed';
    }

    public function attendance_setup()
    {        
        $data = [
            "title" => "Attendance | Setup Tanggal Merah",
            'url'   => 'attendance_tanggal_merah_submit',
            'get_data'  => $this->model_apps->get_tanggal_merah(),
        ];
        
        // $this->view($data, false, "attendance_tanggal_merah", true);

        // $data = [
        //         "navbar"        => $this->navbar($data),
        //         "css"           => $this->load->view('apps/components/header', $data),
        //         "view"          => $this->load->view('apps/'.$view.'', $data),
        //         "footer"        => $this->load->view('kalimantan/footer')
        //     ];
        
        $this->render('apps/attendance_tanggal_merah', $data);
    }

    public function attendance_tanggal_merah_submit()
    {        
        $tanggal = $this->input->post('tanggal');
        $keterangan = $this->input->post('keterangan');

        // cek tanggal, jika tanggal sama maka lakukan update
        $cek = $this->model_apps->cek_tanggal_merah($tanggal);
        if($cek->num_rows() > 0)
        {
            $id = $cek->row()->id;
            $data = [
                'keterangan' => $keterangan,
                'updated_at' => $this->model_outlet_transaksi->timezone(),
                'updated_by' => $this->session->userdata('id'),
            ];
            $this->model_apps->update_tanggal_merah($data, $id);
            $this->session->set_flashdata('pesan_success', 'Data Berhasil Diupdate');
            redirect('apps/attendance_setup');
            die;
        }

        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'keterangan' => $this->input->post('keterangan'),
        ];

        $insert = $this->model_apps->insert_tanggal_merah($data);

        $this->session->set_flashdata('pesan_success', 'Data Berhasil Ditambahkan');
        redirect('apps/attendance_setup');
    }

    public function attendance_tanggal_merah_delete($id)
    {
        $data = [
            'deleted_at' => $this->model_outlet_transaksi->timezone(),
            'deleted_by' => $this->session->userdata('id'),
        ];
        $this->model_apps->update_tanggal_merah($data, $id);
        $this->session->set_flashdata('pesan_success', 'Data Berhasil Dihapus');
        redirect('apps/attendance_setup');
    }

    public function attendance_tanggal_merah_override($id)
    {
        $get_data = $this->model_apps->get_tanggal_merah_by_id($id);        
        $tanggal = $get_data->row()->tanggal;
        $keterangan = $get_data->row()->keterangan;

        //update data ke absensi_transaksi
        $this->model_apps->update_absensi_transaksi_by_tanggal($keterangan, $tanggal);
        $this->session->set_flashdata('pesan_success', 'Data Berhasil Diupdate');
        redirect('apps/attendance_setup');
    }

    public function gt_setup_pasar()
    {        
        $data = [
            "title" => "Deltomed GT | Setup Pasar",
            "url"   => "gt_setup_pasar_proses",
            "get_data" => $this->model_apps->get_master_pasar_deltomed(),
            "get_region" => $this->model_apps->get_master_region_deltomed(),
            "get_site" => $this->model_apps->get_master_site(),
        ];
        
        $this->view($data, false, "gt_setup", true);
    }

    public function gt_setup_pasar_proses()
    {
        $nama_pasar = $this->input->post('nama_pasar');
        $region = $this->input->post('region');
        $provinsi = $this->input->post('provinsi');
        $kabupaten = $this->input->post('kabupaten');
        $site = $this->input->post('site');

        // echo strlen($nama_pasar);
        // echo "nama_pasar : ".$nama_pasar;

        // validasi nama pasar 

        // hilangkan kalimat ps di depan data
        $nama_pasar = str_replace('ps ', '', $nama_pasar);
        $nama_pasar = str_replace('ps. ', '', $nama_pasar);
        $nama_pasar = str_replace('psr ', '', $nama_pasar);
        $nama_pasar = str_replace('psr. ', '', $nama_pasar);
        $nama_pasar = str_replace('pasar ', '', $nama_pasar);

        // hapus semua spasi di akhir data
        $nama_pasar = trim($nama_pasar);

        // hapus spasi di awal data 
        $nama_pasar = ltrim($nama_pasar);

        // ubah menjadi huruf kecil
        $nama_pasar = strtolower($nama_pasar);

        // hilangkan tanda baca selain spasi
        $nama_pasar = preg_replace('/[^a-zA-Z0-9\s]/', '', $nama_pasar);

        // hapus spasi di tengah data
        $nama_pasar = preg_replace('/\s+/', ' ', $nama_pasar);

        // cek apakah di kabupaten yang sama sudah exist
        $cek_exist = $this->model_apps->get_master_pasar_by_kabupaten($nama_pasar, $kabupaten);
        if($cek_exist->num_rows() > 0)
        {
            $this->session->set_flashdata('pesan_error', 'Kabupaten yang sama sudah ada');
            return false;
        }

        // generate kode
        $generate_kode_pasar = $this->model_apps->generate_kode_pasar($site);
        // echo $generate_kode_pasar;

        // inser 
        $data = [
            "kode_pasar" => $generate_kode_pasar,
            "nama_pasar" => $nama_pasar,
            "site_code" => $site,
            "provinsi" => $provinsi,
            "kabupaten" => $kabupaten,
            "is_active" => 1,
            "created_at" => $this->model_outlet_transaksi->timezone(),
            "created_by" => $this->session->userdata('id'),
        ];

        $insert = $this->model_apps->insert_master_pasar($data);
        $this->session->set_flashdata('pesan_success', 'Data Berhasil Ditambahkan');
        redirect('apps/gt_setup_pasar');

    }

    public function master_provinsi()
    {
        $region = $this->input->post('region');
        $response = $this->model_apps->get_master_provinsi_deltomed($region);
        if(!$response || $response->num_rows() == 0) {
            echo "<option value=''> -- Data provinsi tidak ditemukan -- </option>";
            return;
        }
        $options = "<option value=''> -- Pilih Provinsi -- </option>";       
        foreach ($response->result_array() as $row) {
            $provinsi = isset($row['provinsi']) ? $row['provinsi'] : '';
            $value = htmlspecialchars($provinsi, ENT_QUOTES, 'UTF-8');
            $display = htmlspecialchars($provinsi, ENT_QUOTES, 'UTF-8');   
            $options .= "<option value='".$value."'>".$display."</option>";
        }
        echo $options;
    }

    public function master_kabupaten()
    {
        $provinsi = $this->input->post('provinsi');
        $response = $this->model_apps->get_master_kabupaten_deltomed($provinsi);
        if(!$response || $response->num_rows() == 0) {
            echo "<option value=''> -- Data kabupaten tidak ditemukan -- </option>";
            return;
        }
        $options = "<option value=''> -- Pilih Kabupaten -- </option>";       
        foreach ($response->result_array() as $row) {
            $kabupaten = isset($row['kabupaten']) ? $row['kabupaten'] : '';
            $value = htmlspecialchars($kabupaten, ENT_QUOTES, 'UTF-8');
            $display = htmlspecialchars($kabupaten, ENT_QUOTES, 'UTF-8');   
            $options .= "<option value='".$value."'>".$display."</option>";
        }
        echo $options;
    }

    public function active_pasar($kode_pasar, $is_active)
    {
        echo "kode_pasar : ".$kode_pasar;
        if(empty($kode_pasar)) {
            $this->session->set_flashdata('pesan_error', 'Data Tidak Ditemukan');
            redirect('apps/gt_setup_pasar');
        }

        if(!$this->model_apps->cek_master_pasar($kode_pasar)) {
            $this->session->set_flashdata('pesan_error', 'Data Tidak Ditemukan');
            redirect('apps/gt_setup_pasar');
        }

        // echo "is_active : ".$is_active;
        // die;

        if($is_active == 1) {
            $status = 0;
        }else{
            $status = 1;
        }

        $data = [
            "kode_pasar" => $kode_pasar,
            "is_active" => $status,
            "updated_at" => $this->model_outlet_transaksi->timezone(),
            "updated_by" => $this->session->userdata('id'),
        ];

        $this->model_apps->update_master_pasar($kode_pasar, $data);
        $this->session->set_flashdata('pesan_success', 'Data Berhasil Diubah');
        redirect('apps/gt_setup_pasar');
    }

}
?>
