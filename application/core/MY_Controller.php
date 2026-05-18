<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// Load the MX_Controller class
require APPPATH . 'third_party/MX/Controller.php';

class MY_Controller extends MX_Controller {

    private $_ci;
    protected $data = array(); // Data yang akan dipass ke view
    protected $navbar_view = ''; // View navbar yang akan digunakan

    public function __construct()
    {
        parent::__construct();
        $this->_ci =& get_instance();

        // Inisialisasi data default
        $this->data['page_title'] = 'Default Title';
        $this->data['page_description'] = '';
        
        // Set navbar view berdasarkan level user
        $this->_set_navbar_view();

    }

    /**
     * Set navbar view berdasarkan level user
     */
    private function _set_navbar_view()
    {
        $level = $this->session->userdata('level');
        // echo "level : ".$level;
        
        switch($level) {
            case '4': // dp
                $this->navbar_view = 'management_office/top_header_dp';
                break;
            case '3': // principal
                $this->navbar_view = 'management_office/top_header_principal';
                break;
            case '3a': // principal tanpa sales
                $this->navbar_view = 'management_office/top_header_principal_nosales';
                break;
            case '3b': // principal hanya raw data, claim, rpd
                $this->navbar_view = 'management_office/top_header_principal_rawdata';
                break;
            case '3c': // principal raw_data dan retur dan rpd
                $this->navbar_view = 'management_office/top_header_principal_rawdata_retur';
                break;
            case '3d': // principal rpd
                $this->navbar_view = 'management_office/top_header_principal_rpd';
                break;
            case '5': // dp mpi
                $this->navbar_view = 'management_office/top_header_dp_mpi';
                break;
            default:
                $this->navbar_view = 'management_office/top_header';
                break;
        }
    }

    /**
     * Render view dengan layout
     */
    protected function render($view, $data = array())
    {
        // Merge data dari controller dengan data default
        $view_data = array_merge($this->data, $data);
        
        // Load navbar
        $view_data['navbar'] = $this->load->view($this->navbar_view, $view_data, TRUE);
        
        // Load content view
        $view_data['content'] = $this->load->view($view, $view_data, TRUE);
        
        // Load main template
        $this->load->view('layouts/main', $view_data);
    }

    /**
     * Render multiple views
     */
    protected function render_multiple($views = array(), $data = array())
    {
        // Merge data
        $view_data = array_merge($this->data, $data);
        
        // Set navbar
        $view_data['navbar'] = $this->load->view($this->navbar_view, $view_data, TRUE);
        
        // Load semua views dan gabungkan
        $content = '';
        foreach($views as $view) {
            $content .= $this->load->view($view, $view_data, TRUE);
        }
        
        $view_data['content'] = $content;
        
        // Load main template
        $this->load->view('layouts/main', $view_data);
    }

    /**
     * Load Javascript inside the page's body
     * @access  public
     * @param   string  $script
     */
    public function _load_script($script)
    {
        if (isset($this->_ci->template) && is_object($this->_ci->template))
        {
            // Queue up the script to be executed after the page is completely rendered
            echo <<< JS
<script>
    var CIS = CIS || { Script: { queue: [] } };
    CIS.Script.queue.push(function() { $script });
</script>
JS;
        }
        else
        {
            echo '<script>' . $script . '</script>';
        }
    }
}

class Ajax_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->library('response');
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */