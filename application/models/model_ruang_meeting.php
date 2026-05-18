<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model_ruang_meeting extends CI_Model
{
    public function get_data_ruang_meeting($date, $room = '')
    {
        if ($date != '') {
            $date = "where a.tanggal = '$date'";
        }else{
            $date = '';
        }

        if ($room != '') {
            $room = "where a.room_id = '$room'";
        }else{
            $room = '';
        }

        $query = "
            select a.*, b.*, c.username
            from
            (
                select a.id as room_id, a.room, b.id as jam_id, b.jam
                from site.master_room a join site.master_jam b
                ORDER BY a.room, b.jam
            )a left join (
                select a.id, a.room_id as id_room, a.jam_id as id_jam, a.tanggal, a.booking_by, a.created_at, a.signature
	            from site.booking a
	            $date and deleted is null
            )b on a.room_id = b.id_room and a.jam_id = b.id_jam
                left join( 
                select a.id, a.username
                from site.master_user a
            ) c on b.booking_by = c.id
            $room
            ORDER BY a.room desc, a.jam_id asc

        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_data_booking($room = '')
    {
        if ($room != '') {
            $room = "where a.room_id = '$room' and deleted is null";
        }else{
            $room = '';
        }

        $query = "
            select *
            from (
                select * 
                from site.booking a
                $room
            )a LEFT JOIN (
                select a.id as id_room, a.room, b.id as id_jam, b.jam
                from site.master_room a join site.master_jam b
            )b on a.room_id = b.id_room and a.jam_id = b.id_jam
            LEFT JOIN site.master_user c
            on a.booking_by = c.id
            order by a.id desc
            limit 10
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_data_booking_group_tanggal()
    {
        $query = "
            select * 
            from site.booking a
            where a.deleted is null
            GROUP BY a.tanggal
            ORDER BY a.tanggal desc
            limit 10
        ";

        return $this->db->query($query);
    }

    public function get_data_booking_by_tanggal_and_room($tanggal, $room)
    {
        $query = "
            select *
            from (
                select * 
                from site.booking a
                where a.room_id = $room and a.tanggal = '$tanggal' and a.deleted is null
            )a LEFT JOIN (
                select a.id as id_room, a.room, b.id as id_jam, b.jam
                from site.master_room a join site.master_jam b
            )b on a.room_id = b.id_room and a.jam_id = b.id_jam LEFT JOIN site.master_user c
                on a.booking_by = c.id
            order by a.id desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_count_booking($date, $room)
    {
        $query = "
            select COUNT(a.id) as count
            from site.booking a
            WHERE a.tanggal = '$date' and a.room_id = '$room' and deleted is null
        ";
        return $this->db->query($query);
    }

    public function get_booking_by_signature($signature)
    {
        $query = "
            select *
            from site.booking a 
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    public function get_notulen($id)
    {
        if($id){
            $params = "where a.id = $id";
        }else{
            $params = '';
        }

        $query = "
            select *
            from site.booking_notulen a  
            $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_notulen($data)
    {
        $this->db->insert('site.booking_notulen', $data);
        return $this->db->insert_id();
    }

    public function update_booking($data, $signature)
    {
        $this->db->where('created_by', $this->session->userdata('id'));
        $this->db->where('signature', $signature);
        $this->db->update('site.booking', $data);

        // print_r($this->db->last_query()); die;
    }

    public function get_username_by_id($id)
    {
        $query = "
            select a.username, a.email
            from site.master_user a
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function update_notulen($data, $id)
    {
        $this->db->where('created_by', $this->session->userdata('id'));
        $this->db->where('id', $id);
        $this->db->update('site.booking_notulen', $data);
    }

    public function get_booking_by_signature_and_created_by($signature, $created_by)
    {
        $query = "
            select *
            from site.booking a 
            where a.signature = '$signature' and a.created_by = '$created_by'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_notulen_by_id_and_peserta($id, $peserta)
    {
        $query = "
            select *
            from site.booking_notulen a 
            where a.id = $id and a.peserta like '%$peserta%' or a.created_by in ($peserta)
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }
}

