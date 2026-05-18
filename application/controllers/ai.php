<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ai extends MY_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title'] = 'AI';
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_ai'));
        $this->session_id = $this->session->userdata('id');
        $this->session_username = $this->session->userdata('username');
        $this->session_supp = $this->session->userdata('supp');
    }

    public function index()
    {
        $data = [
            'title' => 'AI',
            'webhook_url' => 'https://n8n.muliaputramandiri.com/webhook/chat-bot'            
            // 'webhook_url' => 'https://n8n.muliaputramandiri.com/webhook-test/chat-bot'            
        ];        
        $this->render('ai/index', $data);

    }

    public function list_ai()
    {
        $data = [
            'title' => 'List AI Agent',     
            'list_agent' => $this->model_ai->get_list_agent()     
        ];        
        $this->render('ai/list_ai', $data);
    }

    public function detail_agent($signature)
    {
        $nama_agent = $this->model_ai->get_list_agent($signature);
        echo "nama agent : ".$nama_agent->row()->nama_agent;

        $data = [
            'title' => 'AI '.$nama_agent->row()->nama_agent,
            // 'webhook_url' => 'https://n8n.muliaputramandiri.com/webhook-test/agent-retur',
            'webhook_url' => 'https://n8n.muliaputramandiri.com/webhook/mpm-retur-claim-po',
            'userid'    => $this->session_id,
            'username'    => $this->session_username,
        ];        
        $this->render('ai/agent_retur', $data);

    }

    public function test()
    {
        $data = [];
        $this->render('ai/test', $data);
    }
    
}
?>
