<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_barcode extends CI_Model 
{
    public function get_barcode_request($signature = "")
    {
        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }
        $query = "
            select a.*, b.username
            from site.barcode_request a left join site.master_user b 
                on a.created_by = b.id
            $params
            order by a.status = 1 desc
        ";

        return $this->db->query($query);
    }

    public function insert_request($data)
    {
        $this->db->insert('site.barcode_request', $data);
        return $this->db->insert_id();
    }

    public function update_barcode_request($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.barcode_request', $data);
        return $this->db->affected_rows();
    }

}