<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_ai extends CI_Model 
{
    public function get_list_agent($signature = '')
    {
        if($signature)
        {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }
        $query = "
            select a.id, a.nama_agent, a.deskripsi, a.tipe, a.is_active, a.created_at, a.signature
            from site.ai_list_agent a 
            $params
        ";

        return $this->db->query($query);
    }


}