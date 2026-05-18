<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Monitor extends MY_Controller
{    
    function monitor()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/monitor','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_monitor'));
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
            'title'                   => 'MPM Monitoring | Deltomed',
            'get_dashboard_monitor'   => $this->model_monitor->get_dashboard_monitor(),
            // 'get_dashboard_monitor_custom'   => $this->model_monitor->get_dashboard_monitor_custom(2023),
            // 'get_mti_herbal'          => $this->model_monitor->get_mti_breakdown_site_code('herbal'),
            // 'get_mti_candy'           => $this->model_monitor->get_mti_breakdown_site_code('candy'),
            // 'get_mti_rtd'             => $this->model_monitor->get_mti_breakdown_site_code('rtd'),
            'signature'               => $this->model_monitor->get_last_signature()->row()->signature
        ];

        $this->load->view('monitor/top_header', $data);
        $this->load->view('monitor/header_full_width', $data);
        $this->load->view('monitor/content', $data);
        $this->load->view('monitor/chart',$data);
        $this->load->view('monitor/footer');

    }

    public function update_data(){

        if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 140) {
        
            $data_source = [
                'd1'                => $this->model_monitor->get_kodeprod_by_group('G0101'),
                'd2_exclude_rtd'   => $this->model_monitor->get_kodeprod_by_group_exception('G0102', '010121'),
                // 'all_principal'     => $this->model_monitor->get_kodeprod_by_supp(),
            ];

            $update = $this->model_monitor->update_data($data_source);

            if ($update) {
                redirect('monitor/dashboard');
            }else{
                echo "something happen. Please call IT";
            }        

        }else{
            redirect('monitor/dashboard');
        }

    }

    function email(){

        if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 140) {
            
            $email_to = $this->db->query("select * from site.dashboard_monitor_email")->row()->email_to;
            $email_cc = $this->db->query("select * from site.dashboard_monitor_email")->row()->email_cc;

            $from = "suffy@muliaputramandiri.net";
            $to = $email_to;
            $cc = $email_cc;

            // to setelah rilis : felix, andy, fardison, gede, jun, yayang, hendy, gio

            
            $subject = "Report Closing Deltomed DP (Non MPI)";
            // echo "no_relokasi : ".$no_relokasi;

            $this->load->model('model_relokasi');
            $this->model_relokasi->email();

            $data = [
                'data'          => $this->model_monitor->get_dashboard_monitor_custom(),
                // 'signature'     => $signature,
                'created_at'    => $this->model_monitor->get_max_created_at()->row()->created_at
            ];
            $message = $this->load->view("monitor/email",$data,TRUE);

            $this->email->from($from,'PT. Mulia Putra Mandiri');
            $this->email->to($to);
            $this->email->cc($cc);
            $this->email->subject($subject);
            $this->email->message($message);
            $send = $this->email->send();
            if ($send) {
                echo "<script>alert('pengiriman email berhasil'); </script>";
                redirect('monitor/dashboard','refresh');
            }else{
                echo "<script>alert('pengiriman email gagal'); </script>";
                redirect('monitor/dashboard','refresh');
            } 

        }else{

            redirect('monitor/dashboard');
        }        
    }

    public function manage_email(){

        $data = [
            'title'     => 'MPM Monitoring | Manage Email',
            'url'       => 'monitor/update_email',
            'get_email' => $this->model_monitor->get_email()
        ];

        $this->load->view('monitor/top_header', $data);
        // $this->load->view('monitor/header', $data);
        $this->load->view('monitor/manage_email', $data);
        $this->load->view('monitor/footer');

    }

    public function update_email(){

        $signature = 'x';
        $data = [
            'email_to'  => $this->input->post('email_to'),
            'email_cc'  => $this->input->post('email_cc'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.dashboard_monitor_email', $data);
        redirect('monitor/manage_email'); 

    }

    public function chart($tahun = ''){
        $data = [
            'title'     => 'MPM Monitoring | Deltomed',
            'get_dashboard_monitor'   => $this->model_monitor->get_dashboard_monitor_custom($tahun),
            // 'get_mti_herbal'          => $this->model_monitor->get_mti_breakdown_site_code('herbal'),
            // 'get_mti_candy'           => $this->model_monitor->get_mti_breakdown_site_code('candy'),
            // 'get_mti_rtd'             => $this->model_monitor->get_mti_breakdown_site_code('rtd'),
            'signature'               => $this->model_monitor->get_last_signature()->row()->signature
        ];

        $this->load->view('monitor/header', $data);
        // $this->load->view('monitor/header_chart', $data);
        $this->load->view('monitor/chart', $data);
        $this->load->view('monitor/footer');

    }

    public function library_raw_data(){

        $data = [
            'title'     => 'MPM Monitoring | Library Raw Data',
            'get_library_raw_data'   => $this->model_monitor->get_library_raw_data(),
            'signature'               => $this->model_monitor->get_last_signature()->row()->signature
        ];

        $this->load->view('monitor/top_header', $data);
        $this->load->view('monitor/header', $data);
        $this->load->view('monitor/library_raw_data', $data);
        // $this->load->view('monitor/chart',$data);
        $this->load->view('monitor/footer');

    }

    public function export(){
        $query = "
        select 	a.tahun, a.bulan, 
                sum(if(a.divisi = 'D1', a.omzet, 0)) as D1,
                sum(if(a.divisi = 'D2-EXCLUDE-RTD', a.omzet, 0)) as D2,
                sum(if(a.divisi = 'RTD', a.omzet, 0)) as RTD,
                sum(if(a.divisi = 'TOTAL', a.omzet, 0)) as TOTAL,
                a.created_at, a.signature
        from site.dashboard_monitor a
        where a.created_at = (
            select max(b.created_at) 
            from site.dashboard_monitor b
            where a.tahun = b.tahun
        ) 
        GROUP BY a.bulan, a.tahun
        ORDER BY a.tahun desc, a.bulan asc
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Export Deltomed Monitoring.csv');
    }




}
?>
