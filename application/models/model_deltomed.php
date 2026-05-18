<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_deltomed extends CI_Model 
{

    public function __construct() {
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->created_by = $this->session->userdata('id');
    }

    public function get_spreading_post($from = '', $to ='')
    {
        if ($from != '' && $to != '') {
            $params_periode = " and a.created_at between '$from 00:00:00' and '$to 23:59:59' ";
        }else{
            $params_periode = "";
        }

        $query = "
            select  a.id, a.id_transaksi, a.nama_toko, a.keterangan, a.latitude, a.longitude, 
                    a.city, a.district, a.formatted_address, a.name_address, a.postal_code, a.region, a.street, a.street_number,
                    a.name, a.created_at, b.total_value
            from dbrest.spreading_post a left join (
                select a.id, a.id_tagging, a.total_value
                from dbrest.spreading_transaksi a 
                where a.deleted_at is null
            )b on a.id_transaksi = b.id
            where a.deleted_at is null $params_periode
        ";
        return $this->db->query($query);
    }

    public function get_posting_post($from = '', $to ='')
    {
        if ($from != '' && $to != '') {
            $params_periode = " and a.created_at between '$from 00:00:00' and '$to 23:59:59' ";
        }else{
            $params_periode = "";
        }

        $query = "
            select *
            from dbrest.posting_log a
            where a.deleted_at is null and a.city is not null $params_periode
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function export_spreading($from = '', $to = '')
    {
        if ($from != '' && $to != '') {
            $params_periode = " and a.created_at between '$from 00:00:00' and '$to 23:59:59' ";
        }else{
            $params_periode = "";
        }

        $query = "
            select  a.id, a.id_transaksi, a.nama_toko, a.keterangan, a.latitude, a.longitude, 
                    a.city, a.district, a.formatted_address, a.name_address, a.postal_code, a.region, a.street, a.street_number,
                    a.name, a.created_at, b.total_value, c.count_product, a.email
            from dbrest.spreading_post a left join (
                select a.id, a.id_tagging, a.total_value
                from dbrest.spreading_transaksi a 
                where a.deleted_at is null
            )b on a.id_transaksi = b.id left join (
                select a.id, b.count_product
                from dbrest.spreading_survei a left join (
                    select a.id, a.id_spreading_survei, a.kodeprod, count(*) as count_product
                    from dbrest.spreading_products_survei a
                    GROUP BY a.id_spreading_survei
                )b on a.id = b.id_spreading_survei
            )c on a.id_survei = c.id
            where a.deleted_at is null $params_periode
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'email', 'nama_toko', 'keterangan', 'latitude', 'longitude', 'city', 'district', 'formatted_address', 'name_address', 'postal_code', 'region', 'street', 'street_number', 'total_value', 'count_product', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'email', 'nama_toko', 'keterangan', 'latitude', 'longitude', 'city', 'district', 'formatted_address', 'name_address', 'postal_code', 'region', 'street', 'street_number', 'total_value', 'count_product', 'created_at'
        ));
        $this->excel_generator->set_width(array( 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15 )); 
        $this->excel_generator->exportTo2007('raw data spreading'); 
    }

    public function export_spreading_products($from = '', $to = '')
    {
        if ($from != '' && $to != '') {
            $params_periode = " and a.created_at between '$from 00:00:00' and '$to 23:59:59' ";
        }else{
            $params_periode = "";
        }

        $query = "
            select 	a.id, a.id_survei, a.nama_toko, a.latitude, a.longitude, a.city, a.district, 
                    a.formatted_address, a.name_address, a.postal_code, a.region, a.street, a.street_number,
                    a.sub_region, b.kodeprod, a.email, a.created_at, c.namaprod
            from dbrest.spreading_post a left join (
                select a.id, b.kodeprod
                from dbrest.spreading_survei a left join (
                    select a.id, a.id_spreading_survei, a.kodeprod
                    from dbrest.spreading_products_survei a
                )b on a.id = b.id_spreading_survei
            )b on a.id_survei = b.id left join 
            (
                select a.kodeprod, a.namaprod, a.harga
                from dbrest.spreading_product a 
                where a.flag_active = 1
            )c on b.kodeprod = c.kodeprod
            where a.deleted_at is null $params_periode
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'email', 'nama_toko', 'kodeprod', 'namaprod', 'latitude', 'longitude', 'city', 'district', 'formatted_address', 'name_address', 'postal_code', 'region', 'street', 'street_number', 'sub_region', 'created_at'
        ));
        $this->excel_generator->set_column(array
        ( 
            'email', 'nama_toko', 'kodeprod', 'namaprod', 'latitude', 'longitude', 'city', 'district', 'formatted_address', 'name_address', 'postal_code', 'region', 'street', 'street_number', 'sub_region', 'created_at'
        ));
        $this->excel_generator->set_width(array(20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20)); 
        $this->excel_generator->exportTo2007('raw data spreading by products'); 
    }

}