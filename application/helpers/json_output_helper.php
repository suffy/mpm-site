<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function json_output($statusHeader, $response){
    	$ci =& get_instance();
      $ci->output->set_content_type('application/json');
      $ci->output->set_output(json_encode($response));
    	
    }
 
?>
