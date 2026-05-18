<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_whatsapp extends CI_Model
{
    public function get_order($signature = '')
    {
        if ($signature != '') {
            $this->db->where('signature', $signature);
            return $this->db->get('whatsapp.t_order');
        } else {
            return $this->db->get('whatsapp.t_order');
        }
    }

    public function get_user()
    {
        return $this->db->get('whatsapp.t_user');
    }
}
