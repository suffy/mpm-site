<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Building_block extends MY_Controller
{    
    function building_block()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_building_block'));
    }
    function index()
    {
        $this->target_by_principal();
    }

    function navbar($data){
        
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
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function workspace_target_principal(){
        
        $userid = $this->session->userdata('id');
        
        $data = [
            'title'             => 'Target By Principal',
            'url_generate'      => 'building_block/generate_workspace_target_principal',
            'get_data'          => $this->model_building_block->get_data_workspace_target_principal($userid),
            'userid'            => $userid
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('building_block/workspace_target_principal', $data);
        $this->load->view('kalimantan/footer');
    }

    public function target_by_principal($signature_workspace){

        $get_workspace_target_principal = $this->model_building_block->get_workspace_target_principal($signature_workspace);
        if ($get_workspace_target_principal->num_rows() > 0) {
            $id_workspace = $get_workspace_target_principal->row()->id;
            $tahun_bb = $get_workspace_target_principal->row()->tahun_building_block;
            $bulan_bb = $get_workspace_target_principal->row()->bulan_building_block;
            $periode_bb = $tahun_bb.'-'.$bulan_bb;
        }

        $userid = $this->session->userdata('id');

        $data = [
            'title'             => 'Input Target By Principal',
            'url_generate'      => 'building_block/generate_target_by_principal',
            'url_search'        => 'building_block/target_by_principal',
            'url_average'       => 'building_block/update_average_target',
            'url_target'        => 'building_block/update_target',
            'get_data_target_principal' => $this->model_building_block->get_data_target_principal_by_id_workspace($id_workspace),
            'get_data_summary'  => $this->model_building_block->get_data_summary($userid),
            'get_data_summary_groupby_bulan_tahun'  => $this->model_building_block->get_data_summary_groupby_bulan_tahun($userid),
            'userid'            => $userid,
            'periode_bb'        => $periode_bb,
            'signature'         => $signature_workspace,
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('building_block/target_by_principal', $data);
        $this->load->view('kalimantan/footer');
    }    

    public function update_target(){

        $options = $this->input->post('options');

        if (!$options) {
            echo "<br><br><br><center>Tidak ada satupun Row yang di checklist</center><br>";
            ?>
            <a href="javascript: history.go(-1)"><center>klik untuk kembali</center></a>
        <?php
            die;
        }

        $signature_workspace = $this->input->post('signature');
        $bulan_target = $this->input->post('bulan_target');
        $params_tahun = substr($bulan_target, 0, 4);
        $params_bulan = substr($bulan_target, 5, 2);

        foreach ($options as $a) {

            $target_gt = str_replace(',', '', trim($this->input->post('target_gt')[$a]));
            $target_mt = str_replace(',', '', trim($this->input->post('target_mt')[$a]));
            $target_ph = str_replace(',', '', trim($this->input->post('target_ph')[$a]));

            // echo "target_ph : ".$target_ph;
            // echo "<br>";
            // echo str_replace(' ', '', $target_ph);
            // die;

            $data = [
                'target_gt' => $target_gt,
                'target_mt' => $target_mt,
                'target_ph' => $target_ph,                
            ];

            $this->db->where('id', $a);
            $this->db->update('db_building_block.data_target_by_principal', $data);

        }

        $get_deltomed_all = $this->model_building_block->get_deltomed_all($params_tahun, $params_bulan);
        foreach ($get_deltomed_all->result() as $a) {

            // echo $a->site_code . ' - ' . $a->divisi . ' - ' . $a->sum_target_gt . ' - ' . $a->sum_average_gt . PHP_EOL;
            
            $data = [
                'target_gt'    => $a->sum_target_gt,
                'average_gt'   => $a->sum_average_gt,
                'target_mt'    => $a->sum_target_mt,
                'average_mt'   => $a->sum_average_mt,
                'target_ph'    => $a->sum_target_ph,
                'average_ph'   => $a->sum_average_ph
            ];

            $this->db->where('site_code', $a->site_code);
            $this->db->where('tahun', $params_tahun);
            $this->db->where('divisi', 'DELTOMED_ALL');
            $this->db->where('bulan', $params_bulan);
            $this->db->update('db_building_block.data_target_by_principal', $data);
        }

        // die;

        redirect('building_block/target_by_principal/'.$signature_workspace, 'refresh');
    }

    public function update_average_target($divisi){

        $bulan_target = $this->input->post('bulan_target');
        $periode = $this->input->post('periode');
        $signature_workspace = $this->input->post('signature');

        $userid = $this->session->userdata('id');
        $created_at = $this->model_outlet_transaksi->timezone();

        $params_tahun = substr($bulan_target, 0, 4);
        $params_bulan = substr($bulan_target, 5, 2);

        $signature = 'temp-avg-d1-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $count = count($periode);

        for ($i=0; $i < $count; $i++) { 

            $tahun_raw = substr($periode[$i], 0, 4);
            $bulan_raw = substr($periode[$i], 5, 2);

            $get_data_target_by_principal = $this->model_building_block->get_data_target_by_divisi($params_tahun, $params_bulan, $userid, $divisi);
            foreach ($get_data_target_by_principal->result() as $key) {

                $site_code = $key->site_code;
                $divisi = $key->divisi;

                $get_data_summary = $this->model_building_block->get_data_summary_group_tahun_bulan_sitecode_divisi_userid($tahun_raw, $bulan_raw, $site_code, $divisi, $userid);
                
                foreach ($get_data_summary->result() as $a) {
                    
                    $bruto_gt = $a->bruto_gt;
                    $bruto_mt = $a->bruto_mt;
                    $bruto_ph = $a->bruto_ph;

                    $data = [
                        'userid'    => $userid,
                        'site_code' => $site_code,
                        'divisi'    => $divisi,
                        'tahun_raw' => $tahun_raw,
                        'bulan_raw' => $bulan_raw,
                        'bruto_gt'  => $bruto_gt,
                        'bruto_mt'  => $bruto_mt,
                        'bruto_ph'  => $bruto_ph,
                        'signature' => $signature,
                        'created_at'=> $created_at,
                        'created_by'=> $userid
                    ];

                    $this->db->insert('db_building_block.temp_average_target', $data);
                }
            }
        }

        $get_temp_average = $this->model_building_block->get_temp_average($signature);
        foreach ($get_temp_average->result() as $b) {
            $data = [
                'site_code'                     => $b->site_code, 
                'divisi'                        => $b->divisi,
                'average_gt'                    => $b->average_gt,
                'average_mt'                    => $b->average_mt,
                'average_ph'                    => $b->average_ph,
                'signature_temp_average_target' => $b->signature,
                'created_at'                    => $created_at,
                'created_by'                    => $userid,
                'userid'                        => $userid,
            ];
            $this->db->insert('db_building_block.temp_average_target_result', $data);

        }

        $get_temp_average_target_result = $this->model_building_block->get_temp_average_target_result($signature);
        foreach ($get_temp_average_target_result->result() as $c) {
            
            $data = [
                'average_gt'    => $c->average_gt,
                'average_mt'    => $c->average_mt,
                'average_ph'    => $c->average_ph,
                'updated_at'    => $created_at,
                'updated_by'    => $userid
            ];

            $this->db->where('site_code', $c->site_code);
            $this->db->where('divisi', $c->divisi);
            $this->db->where('tahun', $params_tahun);
            $this->db->where('bulan', $params_bulan);
            $this->db->update('db_building_block.data_target_by_principal', $data);
        }

        $get_deltomed_all = $this->model_building_block->get_deltomed_all($params_tahun, $params_bulan);
        foreach ($get_deltomed_all->result() as $a) {

            // echo $a->site_code . ' - ' . $a->divisi . ' - ' . $a->sum_target_gt . ' - ' . $a->sum_average_gt . PHP_EOL;
            
            $data = [
                'target_gt'    => $a->sum_target_gt,
                'average_gt'   => $a->sum_average_gt,
                'target_mt'    => $a->sum_target_mt,
                'average_mt'   => $a->sum_average_mt,
                'target_ph'    => $a->sum_target_ph,
                'average_ph'   => $a->sum_average_ph
            ];

            $this->db->where('site_code', $a->site_code);
            $this->db->where('tahun', $params_tahun);
            $this->db->where('divisi', 'DELTOMED_ALL');
            $this->db->where('bulan', $params_bulan);
            $this->db->update('db_building_block.data_target_by_principal', $data);
        }

        // die;

        $this->session->set_flashdata("pesan_success", "update $divisi success.");
        redirect('building_block/target_by_principal/' . $signature_workspace, 'refresh');

    }

    public function generate_workspace_target_principal(){
        $bulan_target = $this->input->post('bulan');
        $params_tahun = substr($bulan_target, 0, 4);
        $params_bulan = substr($bulan_target, 5, 2);

        $site_code = $this->input->post('site_code');

        // echo "bulan_target : ".$bulan_target;
        // echo "site_code : ".$site_code;
        // die;
        
        $signature = 'workspace' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');
        $id_workspace = $this->model_building_block->generate_workspace_target_principal($params_tahun, $params_bulan, $site_code, $signature);

        $generate_target = $this->model_building_block->generate_target($params_tahun, $params_bulan, $site_code, $id_workspace);

        $this->session->set_flashdata("pesan_success", "generate data berhasil.");
        redirect('building_block/workspace_target_principal', 'refresh');

        die;
    }

    public function generate_target_by_principal(){
        $bulan = $this->input->post('bulan');
        $params_tahun = substr($bulan, 0, 4);
        $params_bulan = substr($bulan, 5, 2);

        $generate_target = $this->model_building_block->generate_target($params_tahun,$params_bulan);

        $this->session->set_flashdata("pesan_success", "generate data selesai. Anda sudah bisa mengisi data target.");
        redirect('building_block/target_by_principal?bulan_target='.$bulan.'', 'refresh');

        die;
    }

    public function summary_sales(){
        $bulan = $this->input->get('bulan');
        if ($bulan) {
            $params_tahun = substr($bulan, 0, 4);
            $params_bulan = substr($bulan, 5, 2);
        }else{
            $params_tahun = 0;
            $params_bulan = 0;
        }

        $userid = $this->session->userdata('id');

        $data = [
            'title'             => 'Summary Sales',
            'url_generate'      => 'building_block/generate_summary_sales',
            'url_search'        => '',
            'get_raw_data'      => $this->model_building_block->get_data_summary($userid),
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('building_block/summary_sales', $data);
        $this->load->view('kalimantan/footer');
    }

    public function generate_summary_sales(){
        $bulan = $this->input->post('bulan');
        $userid = $this->session->userdata('id');

        $params_tahun = substr($bulan, 0, 4);
        $params_bulan = substr($bulan, 5, 2);

        // echo "params_tahun : ".$params_tahun;
        // echo "params_bulan : ".$params_bulan;

        $delete_data = $this->model_building_block->delete_data($params_tahun, $params_bulan, $userid);

        $get_master_divisi = $this->model_building_block->get_master_divisi();
        foreach ($get_master_divisi->result() as $a) {
            $insert_by_divisi = $this->model_building_block->insert_by_divisi($params_tahun, $params_bulan, $userid, $a->divisi);
        }

        // die;

        redirect('building_block/summary_sales?bulan='.$bulan.'', 'refresh');
    }

    public function summary_d1(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $userid = $this->input->post('userid');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://localhost:81/restapi/api/master_data/summary_d1?token=$token&userid=$userid&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $data_summary = $array_response['data'];
            // var_dump($data_summary);die;
            echo "<option value=''> -- Pilih Source -- </option>";

            foreach ($data_summary as $key => $tiap_summary)
            {
                echo "<option value='". $tiap_summary["tahun"] . "-" . $tiap_summary["bulan"] . "' >";
                echo $tiap_summary["tahun"].'-',$tiap_summary["bulan"];
                echo "</option>";
            }
        }
    }

    public function workspace_target_outlet(){
        
        $userid = $this->session->userdata('id');
        
        $data = [
            'title'             => 'Target By Outlet',
            'url_generate'      => 'building_block/generate_workspace_target_outlet',
            'get_data'          => $this->model_building_block->get_data_workspace_target_outlet($userid),
            'userid'            => $userid
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('building_block/workspace_target_outlet', $data);
        $this->load->view('kalimantan/footer');
    }

    public function delete_workspace_target_outlet($signature){
     
        echo "signature : ".$signature;

        $data = [
            'deleted_at'    => $this->model_outlet_transaksi->timezone(),
            'deleted_by'    => $this->session->userdata('id'),
            'updated_at'    => $this->model_outlet_transaksi->timezone(),
            'updated_by'    => $this->session->userdata('id'),
        ];  

        $this->db->where('signature', $signature);
        $this->db->update('db_building_block.workspace_target_outlet', $data);

        $this->session->set_flashdata("pesan_success", "delete data berhasil.");
        redirect('building_block/workspace_target_outlet', 'refresh');
        
    }

    public function generate_workspace_target_outlet(){
        
        $bulan_target = $this->input->post('bulan');
        $params_tahun = substr($bulan_target, 0, 4);
        $params_bulan = substr($bulan_target, 5, 2);

        $site_code = $this->input->post('site_code');
        $periode = $this->input->post('periode');

        $signature = 'workspace' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $count = count($periode);
        $periodex = '';
        for ($i=0; $i < $count ; $i++) { 
            $periodex.= ",".$periode[$i];
            $tahun_raw = substr($periode[$i], 0, 4);
            $bulan_raw = substr($periode[$i], 5, 2);
            $this->model_building_block->generate_raw_outlet($tahun_raw, $bulan_raw, $site_code, $signature);
        }

        $periode = preg_replace('/,/', '', $periodex,1);

        $data = [
            'periode_raw_data' => $periode,
            'signature' => $signature,
            'site_code' => $site_code,
            'created_at' => $this->model_outlet_transaksi->timezone(),
            'created_by' => $this->session->userdata('id'),
            'tahun_building_block' => $params_tahun,
            'bulan_building_block' => $params_bulan
        ];

        $id_workspace = $this->model_building_block->generate_workspace_target_outlet($data, $signature);

        $generate_target_outlet = $this->model_building_block->generate_target_outlet($params_tahun, $params_bulan, $signature);

        $this->session->set_flashdata("pesan_success", "generate data berhasil.");
        redirect('building_block/workspace_target_outlet', 'refresh');

    }

    public function target_by_outlet($signature_workspace){

        $data = [
            'title'             => 'Input Target By Outlet',
            'url_generate'      => 'building_block/generate_target_by_principal',
            'url_search'        => 'building_block/target_by_principal',
            'url_average'       => 'building_block/update_average_target',
            'url_target'        => 'building_block/update_target',
            'get_data_target_by_outlet' => $this->model_building_block->get_data_target_by_outlet($signature_workspace),
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('building_block/target_by_outlet', $data);
        $this->load->view('kalimantan/footer');
    }    

    public function site_code_by_user(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');
        $userid = 297;

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://localhost:81/restapi/api/master_data/nama_comp_claim?token=$token&X-API-KEY=123",
            CURLOPT_URL => "http://localhost:81/restapi/api/master_data/master_user_by_id?token=$token&X-API-KEY=123&userid=$userid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($datasbranch);die;
            echo "<option value=''> -- Pilih DP -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["site_code"] ."' >";
                echo $tiapbranch["site_code"]." - ".$tiapbranch["nama_comp"];
                echo "</option>";
            }
        }
    }

}
?>
