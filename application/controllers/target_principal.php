<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Target_principal extends MY_Controller
{    
    function target_principal()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_target_principal'));
    }
    function index()
    {
        $this->deltomed_breakdown_subbranch();
    }

    public function deltomed_breakdown_subbranch(){

        $data = [
            'title'     => 'Input Target Deltomed | breakdown sub branch',
            'get_log_import'   => $this->model_target_principal->get_log_import(),
            'url'       => 'target_principal/import_deltomed_target_by_subbranch'
        ];

        $this->load->view('mti/header');
        $this->load->view('target_principal/deltomed_by_subbranch', $data);
        $this->load->view('mti/footer');

    }

    public function template_deltomed_import_subbranch(){
        $query = "
            select  '' as bulan, '' as site_code, '' as target_in_unit, '' as target_in_value
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Deltomed_By_Subbranch.csv');
    }

    public function import_deltomed_target_by_subbranch(){

        if (!is_dir('./assets/uploads/target_principal/import/')) {
            @mkdir('./assets/uploads/target_principal/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/target_principal/import/';
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
            $object = PHPExcel_IOFactory::load("assets/uploads/target_principal/import/$filename");

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
                
                    $bulan = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $site_code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $target_in_unit = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $target_in_value = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                                        

                    $data = [
                        'bulan'          => $bulan,
                        'site_code'      => $site_code,
                        'target_in_unit' => $target_in_unit,
                        'target_in_value'=> $target_in_value,
                        'filename'       => $filename,
                        'signature'      => $signature,
                        'created_at'     => $created_at,
                        'created_by'     => $this->session->userdata('id')
                    ];

                    $this->db->insert('site.target_import_deltomed_by_subbranch',$data);
                }
            }
        }else{
           echo "gagal";
        };

        // die;

        $get_count = "select count(*) as count from site.target_import_deltomed_by_subbranch a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'pola'          => 'deltomed_by_subbranch',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('site.target_log_import', $upload);

        redirect('target_principal/draft_deltomed_target_by_subbranch/'.$signature);

    }

    public function draft_deltomed_target_by_subbranch($signature){
        $data = [
            'title'             => 'Draft Target Deltomed | breakdown sub branch',
            'get_data_import'   => $this->model_target_principal->get_data_import($signature),
            'url'               => 'target_principal/mapping_deltomed_target_by_subbranch',
            'signature'         => $signature
        ];

        $this->load->view('mti/header');
        $this->load->view('target_principal/draft_deltomed_target_by_subbranch', $data);
        $this->load->view('mti/footer');
    }

    public function mapping_deltomed_target_by_subbranch(){

        $signature = $this->input->post('signature');
        $mapping = $this->model_target_principal->mapping_deltomed_target_by_subbranch($signature);

        $data = [
            'title'             => 'Mapping Target Deltomed | breakdown sub branch',
            'get_data_mapping'  => $this->model_target_principal->get_data_mapping_deltomed_by_subbranch($signature),
            'url'               => 'target_principal/mapping_deltomed_target_by_subbranch',
            'signature'         => $signature
        ];

        $this->load->view('mti/header');
        $this->load->view('target_principal/mapping_deltomed_target_by_subbranch', $data);
        $this->load->view('mti/footer');
    }

    

}
?>
