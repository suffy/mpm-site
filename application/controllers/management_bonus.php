<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_bonus extends MY_Controller
{    
    function management_bonus()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_bonus'));
    }
    function index()
    {
        $this->master_data();
    }

    public function dashboard(){
        $data = [
            'title'             => 'Dashboard',
            'get_master_data'   => $this->model_management_bonus->get_master_data(),
            'url'               => 'management_bonus/import_master_data'
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_bonus/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_data(){
        $data = [
            'title'             => 'Master Data Monitoring Bonus',
            'get_master_data'   => $this->model_management_bonus->get_master_data(),
            'url'               => 'management_bonus/import_master_data'
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_bonus/master_data', $data);
        $this->load->view('kalimantan/footer');
    }

    public function truncate_master_data(){
        $this->db->query("truncate management_bonus.master_data");
        redirect('management_bonus/master_data');
    }

    // public function export_master_data(){
    //     $query="
    //     select 	a.program_bulan, a.site_code, a.kodeprod, a.qty_claim
    //     from management_bonus.master_data a
    //     ";                        
    //     $hsl = $this->db->query($query);
    //     query_to_csv($hsl,TRUE,'Export / Template Master Data.csv');
    // }

    public function export_master_data_excel(){

        $query = "
            select a.site_code, a.kodeprod, a.qty_bonus
            from management_bonus.master_data a
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'kodeprod', 'qty_bonus'
        ));
        $this->excel_generator->set_column(array
        ( 
            'site_code', 'kodeprod', 'qty_bonus'
        ));
        $this->excel_generator->set_width(array(10,10,10));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('Export Master Data Monitoring Bonus'); 
    }

    public function import_master_data(){

        $nama_program = $this->input->post('nama_program');

        if (!is_dir('./assets/uploads/management_bonus/')) {
            @mkdir('./assets/uploads/management_bonus/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_bonus/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_bonus/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_bonus/master_data','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $site_code = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $kodeprod = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $qty_bonus = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                    if(strlen("$kodeprod") == '5')
                    {
                        $kodeprodx = '0'.$kodeprod;
                    }else{
                        $kodeprodx = $kodeprod;
                    } 
                    
                    $data = [
                        'nama_program'   => $nama_program,
                        'site_code'   => $site_code,
                        'kodeprod'    => $kodeprodx,
                        'qty_bonus'    => $qty_bonus,
                        'signature'    => $signature,
                        'created_at'   => $created_at,
                        'created_by'   => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_bonus.master_data',$data);
                }
            }
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        redirect('management_bonus/master_data');

    }

    public function tracking(){
        $data = [
            'title'          => 'Tracking',
            'get_data'       => $this->model_management_bonus->get_history_tracking(),
            'url'            => 'management_bonus/tracking_form',
            'url_export'     => 'export_log',
            'get_nodo'       => $this->model_management_bonus->get_nodo(),
            'get_body'       => $this->model_management_bonus->get_body(),   
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_bonus/tracking', $data);
        $this->load->view('kalimantan/footer');
    }

    public function tracking_form(){
        

        $data = [
            'get_data'      => $this->model_management_bonus->get_data_tracking($this->input->post('site_code'), $this->input->post('nama_program')),
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $this->session->userdata('id'),
            'url'           => 'management_bonus/tracking_tambah',
            'url_status'    => 'management_bonus/tracking_update',
            'nama_program'  => $this->input->post('nama_program'),
            'site_code'     => $this->input->post('site_code'),
            'title'         => 'tracking bonus'
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_bonus/tracking_form', $data);
        $this->load->view('kalimantan/footer');

    }

    public function tracking_tambah()
    {
        
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = md5($this->model_outlet_transaksi->timezone());

        $data = array();
        $count = count($this->input->post('qty_penggantian'));

        // echo "count : ".$count;

        $this->db->trans_start();

        for($i=0; $i < $count; $i++) {

            if ($this->input->post('qty_penggantian')[$i]) {
                $data = [
                    'kodeprod'          => $this->input->post('kodeprod')[$i],
                    'qty_penggantian'   => $this->input->post('qty_penggantian')[$i],
                    'keterangan'        => $this->input->post('keterangan')[$i],
                    'tgldo'             => $this->input->post('tgldo'),
                    'nodo'              => $this->input->post('nodo'),
                    'nama_program'      => $this->input->post('nama_program'),
                    'site_code'         => $this->input->post('site_code'),
                    'signature'         => $signature,
                    'created_at'        => $created_at,
                    'created_by'        => $this->session->userdata('id')
                ];

                $this->db->insert('management_bonus.tracking', $data);

            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan saat insert tracking. Mungkin disebabkan internet. rollback diaktifkan ke proses sebelumnya";
            die;
        }

        redirect('management_bonus/tracking');
    }

    public function tracking_update(){

        $site_code = $this->input->post('site_code');
        $nama_program = $this->input->post('nama_program');
        $signature = $this->input->post('signature');

        // echo $site_code;
        // echo $nama_program;
        // echo $signature;

        // $this->db->trans_start();

        $cek_closed = $this->model_management_bonus->get_status($site_code, $nama_program, $signature);

        if ($cek_closed -> num_rows() > 0) {
            $params_closed = $cek_closed->row()->closed;
        }else{
            $params_closed = 0;
        }

        $data = [
            'closed'    => $params_closed
        ];
        $this->db->where('site_code', $site_code);
        $this->db->where('signature', $signature);
        $this->db->where('nama_program', $nama_program);
        $proses = $this->db->update('management_bonus.master_data', $data);


        redirect('management_bonus/tracking');

    }

    public function export_log(){

        $nama_program = $this->input->post('nama_program');

        $query = "
        select 	a.nama_program, a.site_code, a.nodo, a.tgldo, a.kodeprod, a.qty_penggantian, 
                a.created_at, a.signature, a.keterangan, b.nama_comp, c.closed
        from management_bonus.tracking a left join (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            group by concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN 
        (
            select a.site_code, a.nama_program, a.closed
            from management_bonus.master_data a 
            where a.nama_program = '$nama_program'
            GROUP BY a.site_code, a.nama_program
        )c on a.site_code = c.site_code and a.nama_program = c.nama_program
        ";

        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,"Export Log Tracking Bonus - $nama_program.csv");

    }

    public function tracking_edit($nodo, $signature){

        $get_data_tracking = $this->model_management_bonus->get_data_tracking_single($nodo, $signature)->row();
        $site_code = $get_data_tracking->site_code;
        $nama_program = $get_data_tracking->nama_program;
        $tgldo = $get_data_tracking->tgldo;

        $data = [
            'get_data'      => $this->model_management_bonus->get_data_tracking_by_nodo($nodo, $signature, $site_code, $nama_program),
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $this->session->userdata('id'),
            'url'           => 'management_bonus/tracking_update_data',
            'url_status'    => 'management_bonus/tracking_update',
            'nama_program'  => $this->input->post('nama_program'),
            'site_code'     => $this->input->post('site_code'),
            'title'         => 'tracking bonus',
            'nodo'          => $nodo,
            'tgldo'         => $tgldo,
            'signature'     => $signature,
        ];

        $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_bonus/tracking_edit', $data);
        $this->load->view('kalimantan/footer');

    }

    public function tracking_update_data(){


        $created_at = $this->model_outlet_transaksi->timezone();

        $signature = $this->input->post('signature');

        // $signature = md5($this->model_outlet_transaksi->timezone());

        $data = array();
        $count = count($this->input->post('qty_penggantian'));

        // echo "count : ".$count;
        // die;

        $this->db->trans_start();

        for($i=0; $i < $count; $i++) {

            if ($this->input->post('qty_penggantian')[$i]) {

                $kodeprod = $this->input->post('kodeprod')[$i];
                // echo $kodeprod;
                // echo $signature;
                
                $cek = $this->model_management_bonus->get_data_tracking_by_kodeprod($kodeprod, $signature)->num_rows();

                if ($cek > 0) {

                    $data = [
                       
                        'qty_penggantian'   => $this->input->post('qty_penggantian')[$i],
                        'keterangan'        => $this->input->post('keterangan')[$i],
                        'tgldo'             => $this->input->post('tgldo'),
                        'nodo'              => $this->input->post('nodo'),
                        'created_at'        => $created_at,
                        'created_by'        => $this->session->userdata('id')
                    ];
                    $this->db->where('kodeprod', $kodeprod);
                    $this->db->where('signature', $signature);
                    $this->db->update('management_bonus.tracking', $data);

                }else{
                    $data = [
                        'kodeprod'          => $this->input->post('kodeprod')[$i],
                        'qty_penggantian'   => $this->input->post('qty_penggantian')[$i],
                        'keterangan'        => $this->input->post('keterangan')[$i],
                        'tgldo'             => $this->input->post('tgldo'),
                        'nodo'              => $this->input->post('nodo'),
                        'nama_program'      => $this->input->post('nama_program'),
                        'site_code'         => $this->input->post('site_code'),
                        'signature'         => $signature,
                        'created_at'        => $created_at,
                        'created_by'        => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_bonus.tracking', $data);
                }
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan saat insert tracking. Mungkin disebabkan internet. rollback diaktifkan ke proses sebelumnya";
            die;
        }

        redirect('management_bonus/tracking');


    }
    


}
?>
