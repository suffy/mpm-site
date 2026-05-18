<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('assets/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

class Mypdf
{
    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function generate($view, $data = array(), $filename = '', $paper = 'A4', $orientation = 'portrait')
    {
        $dompdf = new Dompdf();
        $html = $this->ci->load->view($view, $data, TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        ob_clean();
        $dompdf->stream($filename . ".pdf", array("Attachment" => FALSE));
    }

    public function download($view, $data = array(), $filename = 'retur', $paper = 'A4', $orientation = 'portrait')
    {
        $dompdf = new Dompdf();
        $html = $this->ci->load->view($view, $data, TRUE);
        // $dompdf->loadHtml($html);
        $dompdf->loadHtml(html_entity_decode($html));
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        
        $output = $dompdf->output();
        $file_to_save = 'assets/file/retur/'.$filename.'.pdf';
        file_put_contents($file_to_save, $output);       

        ob_clean();
    }

    public function download_dc($view, $data = array(), $filename = 'retur', $paper = 'A4', $orientation = 'portrait')
    {
        $dompdf = new Dompdf();
        $html = $this->ci->load->view($view, $data, TRUE);
        // $dompdf->loadHtml($html);
        $dompdf->loadHtml(html_entity_decode($html));
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        
        $output = $dompdf->output();
        $file_to_save = 'assets/file/dc/'.$filename.'.pdf';
        file_put_contents($file_to_save, $output);       

        ob_clean();
    }

    public function download_rpd($view, $data = array(), $filename = 'rpd', $paper = 'A4', $orientation = 'portrait')
    {
        $dompdf = new Dompdf();
        $html = $this->ci->load->view($view, $data, TRUE);
        // $dompdf->loadHtml($html);
        $dompdf->loadHtml(html_entity_decode($html));
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        
        $output = $dompdf->output();
        $file_to_save = 'assets/file/rpd/'.$filename.'.pdf';
        file_put_contents($file_to_save, $output);       

        ob_clean();
    }

    public function generate_landscape($view, $data = array(), $filename = '', $paper = 'A4', $orientation = 'landscape')
    {
        $dompdf = new Dompdf();
        $html = $this->ci->load->view($view, $data, TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        ob_clean();
        $dompdf->stream($filename . ".pdf", array("Attachment" => FALSE));
    }
}