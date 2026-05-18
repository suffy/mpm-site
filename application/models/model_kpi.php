<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_kpi extends CI_Model
{
    public function get_workspace($tahun = '', $bulan = '', $kategori = ''){

        if ($tahun == '' || $bulan == '') {
            $params_tahun_bulan = '';
        }else{
            $params_tahun_bulan = "and a.tahun = '$tahun' and a.bulan = '$bulan'";
        }

        if ($kategori) {
            $params_kategori = "and a.kategori = '$kategori'";
        }else{
            $params_kategori = '';
        }

        $userid = $this->session->userdata('id');

        $query = "
            select 	a.id, a.kategori, a.tahun, a.bulan, a.signature, a.created_at, a.created_by, a.status_review, a.nama_status_review, a.count_event, a.count_review, a.average_point,
                    b.*
            from site.workspace_list a left join (
                select a.id, a.username, a.jabatan, a.name
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null and a.created_by = $userid $params_tahun_bulan $params_kategori
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function get_workspace_by_signature($signature){

        $query = "
            select 	a.id, a.kategori, a.tahun, a.bulan, a.signature, a.created_at, a.created_by, a.status_review, a.nama_status_review,
                    b.id as userid, b.username, b.jabatan, b.name
            from site.workspace_list a left join (
                select a.id, a.username, a.jabatan, a.name
                from mpm.user a
            )b on a.created_by = b.id
            where a.signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function generate($created_at){

        $bulan_now = date('m',strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            select a.no_pelaporan_event, max(substr(a.no_pelaporan_event,7,3)) as urut
            from site.kpi_event a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_pelaporan_event is not null
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $no_current = $this->db->query($query);
        if ($no_current->num_rows() > 0) {
            $params_urut = $no_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "EVENT-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "EVENT-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "EVENT-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "EVENT-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    public function generate_market_survey($created_at){

        $bulan_now = date('m',strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            select a.no_pelaporan, max(substr(a.no_pelaporan,14,3)) as urut
            from site.kpi_market_survey a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_pelaporan is not null
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $no_current = $this->db->query($query);
        if ($no_current->num_rows() > 0) {
            $params_urut = $no_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "MarketSurvey-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "MarketSurvey-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "MarketSurvey-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "MarketSurvey-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    public function generate_channel_baru($created_at){

        $bulan_now = date('m',strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            select a.no_pelaporan, max(substr(a.no_pelaporan,5,3)) as urut
            from site.kpi_channel_baru a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_pelaporan is not null
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $no_current = $this->db->query($query);
        if ($no_current->num_rows() > 0) {
            $params_urut = $no_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "NOO-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "NOO-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "NOO-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "NOO-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    function getRomawi($bln){
        switch ($bln){
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public function get_event()
    {
        $query = "
            select a.*, b.*
            from site.kpi_event a left join (
                select a.id, a.username, a.name, a.email, a.jabatan
                from mpm.user a
            )b on a.created_by = b.id
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    // public function get_event($id_workspace){

    //     $query = "
    //         select a.*, b.*, c.average_point
    //         from site.kpi_event a LEFT JOIN (
    //             select a.id, a.username, a.`name`
    //             from mpm.user a
    //         )b on a.created_by = b.id left join (
    //             select avg(a.point) as average_point, a.id_event
    //             from site.kpi_review_event a
    //             GROUP BY a.id_event
    //         )c on a.id = c.id_event
    //         where a.deleted_at is null and a.id_workspace = $id_workspace
    //     ";

    //     echo "<pre>";
    //     print_r($query);
    //     echo "</pre>";

    //     return $this->db->query($query);
    // }

    public function get_workspace_by_id($id){
        $query = "
            select *
            from site.workspace_list
            where id = $id
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function update_count_event($id_workspace){
        $get_count = $this->get_event($id_workspace)->num_rows();
        $update = [
            'count_event' => $get_count
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function update_count_review($id_workspace){
        $get_count = $this->get_review_event_by_id_workspace($id_workspace)->num_rows();
        $update = [
            'count_review' => $get_count
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function update_average_point($id_workspace){
        // $average_point = $this->get_average_point_by_id_workspace($id_workspace)->row()->average_point;
        $average_point = $this->get_average_point_by_id_workspace($id_workspace);
        if ($average_point->num_rows() > 0) {
            $average_point = $average_point->row()->average_point;
        }else{
            $average_point = 0;
        }
        $update = [
            'average_point' => $average_point
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function get_average_point_by_id_event($id_event){
        $query = "
            select avg(a.point) as average_point
            from site.kpi_review_event a
            where a.deleted_at is null and a.id_event = $id_event
            GROUP BY a.id_event
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_average_point_by_id_workspace($id_workspace){
        $query = "
            select avg(a.point) as average_point
            from site.kpi_review_event a INNER JOIN (
                select a.id, a.id_workspace
                from site.kpi_event a
                where a.deleted_at is null
            )b on a.id_event = b.id
            where a.deleted_at is null and a.id_workspace = $id_workspace
            GROUP BY a.id_workspace
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_event_by_signature($signature){
        $query = "
            select a.*, b.*
            from site.kpi_event a LEFT JOIN (
                select a.id as userid, a.username, a.`name`, a.email, a.jabatan
                from mpm.user a
            )b on a.created_by = b.userid
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_event_by_id($id){
        $query = "
            select a.*, b.username, b.name
            from site.kpi_event a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.id = $id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_event_tim_by_userid($kuartal, $tahun, $created_by){
        $query = "
            
        SELECT a.userid_tim, a.rank, b.*, c.name
        FROM
        (
            SELECT a.userid, IF(a.userid_tim is null,a.userid,a.userid_tim) as userid_tim, a.rank
            FROM site.kpi_master_team_struktural_view a
            WHERE a.userid = '$created_by'
        )a
        LEFT JOIN
        (
            SELECT *
            FROM site.kpi_event a 
            INNER JOIN
            (
                SELECT a.kuartal, a.bulan
                FROM site.kpi_kuartal a
                WHERE a.kuartal = '$kuartal'
            )b on month(a.event_from) = b.bulan
            where a.deleted_at is null and year(a.event_from) = '$tahun' and a.status = 2
        )b on a.userid_tim = b.created_by or a.userid = b.created_by
        left join mpm.user c on a.userid_tim = c.id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_review_event_by_id_workspace($id_workspace){
        $query = "
            select a.*, b.username, b.name
            from site.kpi_review_event a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id INNER JOIN (
                select a.id, a.id_workspace
                from site.kpi_event a
                where a.deleted_at is null
            )c on a.id_event = c.id
            where a.deleted_at is null and a.id_workspace = $id_workspace
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_review_event_by_id_event($id_event){
        $query = "
            select a.*, b.username, b.name
            from site.kpi_review_event a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null and a.id_event = $id_event
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_market_survey(){
        $query = "
            select a.*, b.name
            from site.kpi_market_survey a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_market_survey_by_signature($signature){
        $query = "
            select a.*, b.*
            from site.kpi_market_survey a LEFT JOIN (
                select a.id as userid, a.username, a.`name`, a.email
                from mpm.user a
            )b on a.created_by = b.userid
            where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;

        return $this->db->query($query);
    }
    
    public function get_market_survey_tim_by_userid($kuartal, $tahun, $created_by){
        $query = "
            
        SELECT a.userid_tim, a.rank, b.*, c.name
        FROM
        (
            SELECT a.userid, IF(a.userid_tim is null,a.userid,a.userid_tim) as userid_tim, a.rank
            FROM site.kpi_master_team_struktural_view a
            WHERE a.userid = '$created_by'
        )a
        LEFT JOIN
        (
            SELECT *
            FROM site.kpi_market_survey a 
            INNER JOIN
            (
                SELECT a.kuartal, a.bulan
                FROM site.kpi_kuartal a
                WHERE a.kuartal = '$kuartal'
            )b on month(a.survey_from) = b.bulan
            where a.deleted_at is null and year(a.survey_from) = '$tahun' and a.status = 2
        )b on a.userid_tim = b.created_by or a.userid = b.created_by
        left join mpm.user c on a.userid_tim = c.id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_channel_baru_by_signature($signature){
        $query = "
            select a.*, b.*
            from site.kpi_channel_baru a LEFT JOIN (
                select a.id as userid, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.userid
            where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_market_survey_by_id_workspace($id){
        $query = "
            select a.*, b.*
            from site.kpi_market_survey a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null and a.id_workspace = $id
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_channel_baru_by_id_workspace($id){
        $query = "
            select a.*, b.*
            from site.kpi_channel_baru a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null and a.id_workspace = $id
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_review_market_survey_by_id_market_survey($id_market_survey){
        $query = "
            select a.*, b.username, b.name
            from site.kpi_review_market_survey a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null and a.id_market_survey = $id_market_survey
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function update_count_market_survey($id_workspace){
        $get_count = $this->get_market_survey_by_id_workspace($id_workspace)->num_rows();
        $update = [
            'count_event' => $get_count
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function update_count_channel_baru($id_workspace){
        $get_count = $this->get_channel_baru_by_id_workspace($id_workspace)->num_rows();
        $update = [
            'count_event' => $get_count
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function update_count_review_market_survey($id_workspace){
        $get_count = $this->get_review_market_survey_by_id_workspace($id_workspace)->num_rows();
        $update = [
            'count_review' => $get_count
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function update_count_review_channel_baru($id_workspace){
        $get_count = $this->get_review_channel_baru_by_id_workspace($id_workspace)->num_rows();
        $update = [
            'count_review' => $get_count
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function get_review_market_survey_by_id_workspace($id_workspace){
        $query = "
            select a.*, b.username, b.name
            from site.kpi_review_market_survey a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id INNER JOIN (
                select a.id, a.id_workspace
                from site.kpi_market_survey a
                where a.deleted_at is null
            )c on a.id_market_survey = c.id
            where a.deleted_at is null and a.id_workspace = $id_workspace
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_review_channel_baru_by_id_workspace($id_workspace){
        $query = "
            select a.*, b.username, b.name
            from site.kpi_review_channel_baru a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id INNER JOIN (
                select a.id, a.id_workspace
                from site.kpi_channel_baru a
                where a.deleted_at is null
            )c on a.id_channel_baru = c.id
            where a.deleted_at is null and a.id_workspace = $id_workspace
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function update_average_point_market_survey($id_workspace){
        // $average_point = $this->get_average_point_by_id_workspace($id_workspace)->row()->average_point;
        $average_point = $this->get_average_point_market_survey_by_id_workspace($id_workspace);
        if ($average_point->num_rows() > 0) {
            $average_point = $average_point->row()->average_point;
        }else{
            $average_point = 0;
        }
        $update = [
            'average_point' => $average_point
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function update_average_point_channel_baru($id_workspace){
        $average_point = $this->get_average_point_channel_baru_by_id_workspace($id_workspace);
        if ($average_point->num_rows() > 0) {
            $average_point = $average_point->row()->average_point;
        }else{
            $average_point = 0;
        }
        $update = [
            'average_point' => $average_point
        ];
        $this->db->update('site.workspace_list', $update, ['id' => $id_workspace]);
    }

    public function get_average_point_market_survey_by_id_workspace($id_workspace){
        $query = "
            select avg(a.point) as average_point
            from site.kpi_review_market_survey a INNER JOIN (
                select a.id, a.id_workspace
                from site.kpi_market_survey a
                where a.deleted_at is null
            )b on a.id_market_survey = b.id
            where a.deleted_at is null and a.id_workspace = $id_workspace
            GROUP BY a.id_workspace
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_average_point_channel_baru_by_id_workspace($id_workspace){
        $query = "
            select avg(a.point) as average_point
            from site.kpi_review_channel_baru a INNER JOIN (
                select a.id, a.id_workspace
                from site.kpi_channel_baru a
                where a.deleted_at is null
            )b on a.id_channel_baru = b.id
            where a.deleted_at is null and a.id_workspace = $id_workspace
            GROUP BY a.id_workspace
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_review_channel_baru_by_id_channel_baru($id_channel_baru){
        $query = "
            select a.*, b.username, b.name
            from site.kpi_review_channel_baru a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null and a.id_channel_baru = $id_channel_baru
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";
        return $this->db->query($query);
    }

    public function nama_status_event($status = null)
    {
        if ($status == 1) {
            $nama_status = 'PENDING VERIFIKASI';
        }elseif ($status == 2) {
            $nama_status = 'APPROVED';
        }elseif ($status == 0) {
            $nama_status = 'REJECTED';
        }

        return $nama_status;
    }


    public function get_event_by_status($kuartal = '', $tahun = '')
    {
        if ($kuartal == '') {
            $params = '';
        }else{
            $params = "WHERE b.kuartal = '$kuartal'";
        }
        if ($tahun == '') {
            $params2 = '';
        }else{
            $params2 = " and year(a.event_from) = '$tahun'";
        }

        // echo "from : ".$from;

        $query = "
            SELECT a.status, a.nama_status, a.event_from, b.kuartal, a.bulan, a.total
            FROM
            (
                select a.status, a.nama_status, a.event_from, month(a.event_from) as bulan, count(*) as total
                from site.kpi_event a
                where a.deleted_at is null $params2
                GROUP BY a.`status`, month(a.event_from)
            )a
            LEFT JOIN site.kpi_kuartal b on a.bulan = b.bulan
            $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_team_member($userid = null)
    {
        if ($userid) {
            $params = "and a.userid = '$userid'";
        }else{
            $params = '';
        }

        $query = "
            select a.id, a.userid, a.rank, a.flag_active, a.signature, a.created_at, a.updated_at, b.*
            from site.kpi_master_team_member a LEFT JOIN (
                select a.id as id_user, a.username, a.name, a.email, a.jabatan
                from mpm.user a
            )b on a.userid = b.id_user
            where a.deleted_at is null $params
        ";

        return $this->db->query($query);
    }

    public function get_master_team_member_by_signature($signature)
    {
        $query = "
            select *
            from site.kpi_master_team_member a
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_event_by_userid($kuartal = '', $tahun = '')
    {
        if ($kuartal == '') {
            $params = '';
        }else{
            $params = "WHERE b.kuartal = '$kuartal'";
        }

        if ($tahun == '') {
            $params2 = '';
        }else{
            $params2 = " and year(a.event_from) = '$tahun'";
        }

        $query = "
            select  a.name, a.rank, b.kuartal, a.event_from, a.bulan, a.total
            from
            (
                select b.name, c.rank, a.event_from, a.bulan, a.total
                from
                (
                    select a.status, a.nama_status, a.event_from, month(a.event_from) as bulan, a.created_by as userid, count(*) as total
                    from site.kpi_event a
                    where a.deleted_at is null $params2
                    GROUP BY a.created_by, month(a.event_from)
                )a left join (
                    select a.id as user_id, a.name, a.username, a.jabatan, a.email
                    from mpm.user a
                )b on a.userid = b.user_id left join (
                    select a.userid, a.rank
                    from site.kpi_master_team_member a
                )c on a.userid = c.userid
            )a LEFT JOIN site.kpi_kuartal b on a.bulan = b.bulan
            $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_event_by_userid_spo($from = '', $to = '')
    {
        if ($from == '' && $to == '') {
            $params = '';
        }else{
            $params = "and a.event_from between '$from' and '$to'";
        }

        $query = "
            select b.name, a.bulan, a.total
            from
            (
                select 	a.status, a.nama_status, month(a.event_from) as bulan, a.created_by,
                        a.supervisi_spo_at, a.supervisi_spo_by, count(*) as total
                from site.kpi_event a
                where a.deleted_at is null and a.status_supervisi_spo = 1
                group by a.created_by, month(a.event_from)
            )a left join (
                select a.id as user_id, a.name, a.username, a.jabatan, a.email
                from mpm.user a
            )b on a.supervisi_spo_by = b.user_id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_team_member_struktural()
    {
        $query = "
            select  a.*,
                    b.name as nama_user, b.email as email_user, b.jabatan as jabatan_user, b.username as username_user, d.rank as rank_user,
                    c.name as nama_approval, c.email as email_approval, c.jabatan as jabatan_approval, c.username as username_approval, e.rank as rank_approval
            from site.kpi_master_team_member_struktural a LEFT JOIN (
                select a.id as user_id, a.name, a.username, a.jabatan, a.email
                from mpm.user a
            )b on a.userid = b.user_id LEFT JOIN (
                select a.id as user_id, a.name, a.username, a.jabatan, a.email
                from mpm.user a
            )c on a.userid_approval = c.user_id LEFT JOIN (
                select a.userid, a.rank
                from site.kpi_master_team_member a
            )d on a.userid = d.userid LEFT JOIN (
                select a.userid, a.rank
                from site.kpi_master_team_member a
            )e on a.userid_approval = e.userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_team_member_struktural_by_userid_and_head($userid_pelaksana, $userid_head)
    {
        $query = "
            select a.*,
                    b.name as nama_user, b.email as email_user, b.jabatan as jabatan_user, b.username as username_user,
                    c.name as nama_approval, c.email as email_approval, c.jabatan as jabatan_approval, c.username as username_approval
            from site.kpi_master_team_member_struktural a LEFT JOIN (
                select a.id as user_id, a.name, a.username, a.jabatan, a.email
                from mpm.user a
            )b on a.userid = b.user_id LEFT JOIN (
                select a.id as user_id, a.name, a.username, a.jabatan, a.email
                from mpm.user a
            )c on a.userid_approval = c.user_id
            where a.userid = $userid_pelaksana and a.userid_approval = $userid_head
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_master_team_member_struktural_by_userid($userid)
    {
        $query = "
            select *
            from site.kpi_master_team_member_struktural a
            where a.userid = $userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    // public function insert_generate_report_event($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    // {
    //     $query = "
    //         INSERT INTO site.kpi_generate_report_event
    //         SELECT '' as id, a.no_pelaporan_event, a.created_by, a.rank, a.kuartal, a.tahun, a.supervisi_spo_by, a.status_supervisi_spo, IF(c.rsph is null, a.supervisi_spo_by, c.rsph) as rsph,
    //         '$created_at', '$created_by'
    //         FROM
    //         (
    //                 SELECT a.no_pelaporan_event, a.created_by, c.rank, b.kuartal, year(a.event_from) as tahun, a.status_supervisi_spo, a.supervisi_spo_by
    //                 from site.kpi_event a
    //                 INNER JOIN
    //                 (
    //                     SELECT a.kuartal, a.bulan
    //                     FROM site.kpi_kuartal a
    //                     WHERE a.kuartal = '$kuartal'
    //                 )b on month(a.event_from) = b.bulan
    //                 LEFT JOIN site.kpi_master_team_member c on a.created_by = c.userid
    //                 where a.deleted_at is null and year(a.event_from) = '$tahun'
    //         )a
    //         LEFT JOIN site.kpi_master_spo c on a.created_by = c.userid
    //     ";
    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";
        
    //     $this->db->query($query);
    // }

    public function insert_generate_report_rank_spo($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            INSERT INTO site.kpi_generate_report
            SELECT '' as id, a.userid, a.rank, b.kuartal, b.tahun, b.total_event, b.total_event_verified, b.total_supervisi,
            a.jml_tim, a.userid_atasan, c.kpi, c.point, '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view b on a.userid = b.userid
            )a
            INNER JOIN
            (
                    SELECT a.created_by, b.kuartal, YEAR(a.event_from) as tahun, Count(*) as total_event, Count(a.supervisi_spo_at) as total_event_verified,
                    SUM(status_supervisi_spo) as total_supervisi
                    from site.kpi_event a
                    INNER JOIN
                    (
                            SELECT a.kuartal, a.bulan
                            FROM site.kpi_kuartal a
                            WHERE a.kuartal = '$kuartal'
                    )b on month(a.event_from) = b.bulan
                    LEFT JOIN site.kpi_master_team_member c on a.created_by = c.userid
                    where a.deleted_at is null and year(a.event_from) = '$tahun'
                    GROUP BY a.created_by
            )b on a.userid = b.created_by
            LEFT JOIN
            (
                    SELECT b.kpi, b.point
                    FROM site.kpi_master_perhitungan a
                    LEFT JOIN (
                            select a.*
                            from site.kpi_master_perhitungan_detail a
                    )b on a.id = b.id_master_perhitungan
                    where a.category = 'event' and a.kuartal = '$kuartal' and a.rank = 'spo'
            )c on b.total_event_verified = c.kpi
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        
        $this->db->query($query);
    }

    public function insert_generate_report_rank_asps($kuartal = '', $tahun = '', $created_at = '', $created_by = '') {
        $query = "
            INSERT INTO site.kpi_generate_report
            SELECT '' id, a.userid, a.rank, b.kuartal, b.tahun, sum(b.total_event) as total_event,
            sum(b.total_event_verified) as total_event_verified, sum(b.total_supervisi) as total_supervisi, a.jml_tim, a.userid_atasan,
            round(sum(b.total_event_verified)*0.25) as kpi, 
            (sum(b.point)/a.jml_tim) as point,
            '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view b on a.userid = b.userid
                WHERE a.rank = 'asps'
            )a 
            INNER JOIN
            (
                SELECT *
                FROM site.kpi_generate_report a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' and
                a.created_at = '$created_at' and a.created_by = '$created_by'
            )b on a.userid_tim = b.userid
            GROUP BY a.userid
        ";
        
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_generate_report_rank_rsph($kuartal = '', $tahun = '', $created_at = '', $created_by = '') {
        $query = "
            INSERT INTO site.kpi_generate_report
            SELECT '' as id, a.userid, a.rank, b.kuartal, b.tahun, sum(b.total_event) as total_event, sum(b.total_event_verified) as total_event_verified, '0' as total_supervisi, a.jml_tim, a.userid_atasan,
            '0' as kpi, 
            (sum(b.point)/a.jml_tim) as point,
            '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view b on a.userid = b.userid
                WHERE a.rank = 'rsph'
            )a 
            INNER JOIN
            (
                SELECT *
                FROM site.kpi_generate_report a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' and
                a.created_at = '$created_at' and a.created_by = '$created_by'
            )b on a.userid_tim = b.userid
            GROUP BY a.userid
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        
        return $this->db->query($query);
    }

    public function insert_generate_report_log($kuartal = null, $tahun = null, $created_at = null, $created_by = null)
    {
        $data = [
            'kuartal'       => $kuartal,
            'tahun'         => $tahun,
            'created_at'    => $created_at,
            'created_by'    => $created_by,
        ];

        $this->db->insert('site.kpi_generate_report_log', $data);
    }

    // public function get_generate_report_event($kuartal = '', $created_at = '', $created_by = '')
    // {
    //     $query = "
    //         SELECT a.*, b.name, c.name as asps, d.name as rsph
    //         FROM site.kpi_generate_report_event a
    //         LEFT JOIN mpm.user b on a.userid = b.id
    //         LEFT JOIN mpm.user c on a.userid_approval = c.id
    //         LEFT JOIN mpm.user d on a.userid_rsph = d.id
    //         WHERE a.kuartal = '$kuartal' and a.created_at = '$created_at' and a.created_by = '$created_by'
    //     ";
    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";

    //     return $this->db->query($query);
    // }

    // public function get_generate_report_tables_by_rank($rank = '', $kuartal = '', $created_at = '', $created_by = '')
    // {
    //     $query = "
    //         SELECT a.*, b.name
    //         FROM site.kpi_generate_report a
    //         LEFT JOIN mpm.user b on a.userid = b.id
    //         WHERE a.rank = '$rank' and a.kuartal = '$kuartal' and a.created_at = '$created_at' and a.created_by = '$created_by'
    //     ";
    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "</pre>";

    //     return $this->db->query($query);
    // }

    public function get_generate_report_tim_by_userid($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            SELECT a.userid_tim, a.rank, b.*, c.name
            FROM
            (
                SELECT a.userid, a.userid_tim, a.rank
                FROM site.kpi_master_team_struktural_view a
                WHERE a.userid = '$created_by' and a.userid_tim is not null
            )a 
            LEFT JOIN
            (
                SELECT * 
                FROM site.kpi_generate_report a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' and a.created_at = '$created_at' and a.created_by = '$created_by'
            )b on a.userid_tim = b.userid
            LEFT JOIN mpm.user c on a.userid_tim = c.id
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_generate_report_by_userid($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            SELECT a.*, b.name
            FROM
            (
                SELECT * 
                FROM site.kpi_generate_report a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' 
                and a.created_at = '$created_at' and a.created_by = '$created_by'
                and a.userid = '$created_by'
            )a
            LEFT JOIN mpm.user b on a.userid = b.id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_perhitungan($category = '', $kuartal = '')
    {
        if ($kuartal == '') {
            $params = '';
        }else{
            $params = "and a.kuartal = '$kuartal'";
        }

        $query = "
            select a.category, a.kuartal, a.min_target, a.bobot, a.rank, a.parameter, b.*
            from site.kpi_master_perhitungan a left join
            (
                select a.id_master_perhitungan, a.kpi, a.point
                from site.kpi_master_perhitungan_detail a
            )b on a.id = b.id_master_perhitungan
            where a.category = '$category' $params
            group by a.id
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_perhitungan($category = null, $kuartal = null, $rank = null){

        if($category != null || $rank != null){
            $params = "where a.category ='$category' and a.kuartal ='$kuartal' and a.rank = '$rank'";
        }else{
            $params = "";
        }

        $query = "
            select *
            from site.kpi_master_perhitungan a
            $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_perhitungan_by_signature($signature){
        $query = "
            select *
            from site.kpi_master_perhitungan a
            where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_master_brand($brand = '')
    {
        if ($brand) {
            $params = "and a.brand = '$brand'";
        }else{
            $params =  "";
        }

        $query = "
            select *
            from site.kpi_master_brand a
            where a.deleted_at is null $params
        ";
        return $this->db->query($query);
    }

    public function get_master_perhitungan_detail_by_id_header($id_header)
    {
        $query = "
            select *
            from site.kpi_master_perhitungan_detail a
            where a.id_master_perhitungan = $id_header
        ";

        return $this->db->query($query);
    }

    public function get_master_perhitungan_detail_by_id_header_n_kpi($id_header, $kpi)
    {
        $query = "
            select *
            from site.kpi_master_perhitungan_detail a
            where a.id_master_perhitungan = $id_header and a.kpi = $kpi
        ";

        return $this->db->query($query);
    }

    public function get_surveyor_by_status($kuartal = '', $tahun = '')
    {
        if ($kuartal == '') {
            $params = '';
        }else{
            $params = "WHERE b.kuartal = '$kuartal'";
        }
        if ($tahun == '') {
            $params2 = '';
        }else{
            $params2 = " and year(a.survey_from) = '$tahun'";
        }

        // echo "from : ".$from;

        $query = "
            SELECT a.status, a.nama_status, a.survey_from, b.kuartal, a.bulan, a.total
            FROM
            (
                select a.status, a.nama_status, a.survey_from, month(a.survey_from) as bulan, count(*) as total
                from site.kpi_market_survey a
                where a.deleted_at is null $params2
                GROUP BY a.`status`, month(a.survey_from)
            )a
            LEFT JOIN site.kpi_kuartal b on a.bulan = b.bulan
            $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_surveyor_by_userid($kuartal = '', $tahun = '')
    {
        if ($kuartal == '') {
            $params = '';
        }else{
            $params = "WHERE b.kuartal = '$kuartal'";
        }

        if ($tahun == '') {
            $params2 = '';
        }else{
            $params2 = " and year(a.survey_from) = '$tahun'";
        }

        $query = "
            select  a.name, a.rank, b.kuartal, a.survey_from, a.bulan, a.total
            from
            (
                select b.name, c.rank, a.survey_from, a.bulan, a.total
                from
                (
                    select a.status, a.nama_status, a.survey_from, month(a.survey_from) as bulan, a.created_by as userid, count(*) as total
                    from site.kpi_market_survey a
                    where a.deleted_at is null $params2
                    GROUP BY a.created_by, month(a.survey_from)
                )a left join (
                    select a.id as user_id, a.name, a.username, a.jabatan, a.email
                    from mpm.user a
                )b on a.userid = b.user_id left join (
                    select a.userid, a.rank
                    from site.kpi_master_team_member a
                )c on a.userid = c.userid
            )a LEFT JOIN site.kpi_kuartal b on a.bulan = b.bulan
            $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_dashboard_market_survey_rank_spo($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            INSERT INTO site.kpi_dashboard_market_survey
            SELECT '' as id, a.userid, a.rank, b.kuartal, b.tahun, SUM(b.total_survey) as total_survey, 
            SUM(b.total_survey_verified) as total_survey_verified, a.jml_tim, a.userid_atasan, '' as kpi, '' as point, '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view_view b on a.userid = b.userid
                WHERE a.rank = 'spo'
            )a
            INNER JOIN
            (
                SELECT a.created_by, b.kuartal, YEAR(a.survey_from) as tahun, count(*) as total_survey, COUNT(a.verifikasi_at) as total_survey_verified
                FROM site.kpi_market_survey a 
                INNER JOIN
                (
                    SELECT a.kuartal, a.bulan
                    FROM site.kpi_kuartal a
                    WHERE a.kuartal = '$kuartal'
                )b on month(a.survey_from) = b.bulan
                where a.deleted_at is null and year(a.survey_from) = '$tahun'
                GROUP BY a.created_by
            )b on a.userid_tim = b.created_by or a.userid = b.created_by
            GROUP BY a.userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        
        $this->db->query($query);
    }

    public function insert_dashboard_market_survey_rank_asps($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            INSERT INTO site.kpi_dashboard_market_survey
            SELECT '' as id, a.userid, a.rank, b.kuartal, b.tahun, SUM(b.total_survey) as total_survey, 
            SUM(b.total_survey_verified) as total_survey_verified, a.jml_tim, a.userid_atasan, '' as kpi, '' as point, '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view_view b on a.userid = b.userid
                WHERE a.rank = 'asps'
            )a
            INNER JOIN
            (
                SELECT a.created_by, b.kuartal, YEAR(a.survey_from) as tahun, count(*) as total_survey, COUNT(a.verifikasi_at) as total_survey_verified
                FROM site.kpi_market_survey a 
                INNER JOIN
                (
                    SELECT a.kuartal, a.bulan
                    FROM site.kpi_kuartal a
                    WHERE a.kuartal = '$kuartal'
                )b on month(a.survey_from) = b.bulan
                where a.deleted_at is null and year(a.survey_from) = '$tahun'
                GROUP BY a.created_by
            )b on a.userid_tim = b.created_by or a.userid = b.created_by
            GROUP BY a.userid
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function update_dashboard_market_survey_point($kuartal = '', $created_at = '', $created_by = '')
    {
        $query = "
            UPDATE site.kpi_dashboard_market_survey a
            JOIN (
                select b.kpi, b.point
                from site.kpi_master_perhitungan a
                LEFT JOIN (
                    select a.*
                    from site.kpi_master_perhitungan_detail a
                )b on a.id = b.id_master_perhitungan
                where a.category = 'surveyor' and a.kuartal = '$kuartal' and a.rank = 'spo'
            )b on round(a.total_market_survey_verified/if(a.jml_tim = 0, 1, a.jml_tim)) = b.kpi
            set a.point = b.point, a.kpi = b.kpi
            Where a.created_at = '$created_at' and a.created_by = '$created_by'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_dashboard_market_survey_log($kuartal = null, $tahun = null, $created_at = null, $created_by = null)
    {
        $data = [
            'kuartal'       => $kuartal,
            'tahun'         => $tahun,
            'created_at'    => $created_at,
            'created_by'    => $created_by,
        ];

        $this->db->insert('site.kpi_dashboard_market_survey_log', $data);
    }

    public function get_dashboard_market_survey_tim_by_userid($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            SELECT a.userid_tim, a.rank, b.*, c.name
            FROM
            (
                SELECT a.userid, a.userid_tim, a.rank
                FROM site.kpi_master_team_struktural_view a
                WHERE a.userid = '$created_by' and a.userid_tim is not null
            )a 
            LEFT JOIN
            (
                SELECT * 
                FROM site.kpi_dashboard_market_survey a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' and a.created_at = '$created_at' and a.created_by = '$created_by'
            )b on a.userid_tim = b.userid
            LEFT JOIN mpm.user c on a.userid_tim = c.id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_dashboard_market_survey_by_userid($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            SELECT a.*, b.name
            FROM
            (
                SELECT * 
                FROM site.kpi_dashboard_market_survey a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' 
                and a.created_at = '$created_at' and a.created_by = '$created_by'
                and a.userid = '$created_by'
            )a
            LEFT JOIN mpm.user b on a.userid = b.id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function generate_pemerataan_product($created_at){

        $bulan_now = date('m',strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            select a.no_pelaporan, max(substr(a.no_pelaporan,14,3)) as urut
            from site.kpi_pemerataan_product a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_pelaporan is not null
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $no_current = $this->db->query($query);
        if ($no_current->num_rows() > 0) {
            $params_urut = $no_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "PEMERATAAN-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "PEMERATAAN-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "PEMERATAAN-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "PEMERATAAN-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    public function get_pemerataan_product(){
        $query = "
            select a.*, b.name
            from site.kpi_pemerataan_product a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_pemerataan_product_by_signature($signature){
        $query = "
            select a.*, b.*
            from site.kpi_pemerataan_product a LEFT JOIN (
                select a.id as userid, a.username, a.`name`, a.email
                from mpm.user a
            )b on a.created_by = b.userid
            where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_pemerataan_product_tim_by_userid($kuartal, $tahun, $created_by){
        $query = "
            
        SELECT a.userid_tim, a.rank, b.*, c.name
        FROM
        (
            SELECT a.userid, IF(a.userid_tim is null,a.userid,a.userid_tim) as userid_tim, a.rank
            FROM site.kpi_master_team_struktural_view a
            WHERE a.userid = '$created_by'
        )a
        LEFT JOIN
        (
            SELECT *
            FROM site.kpi_pemerataan_product a 
            INNER JOIN
            (
                SELECT a.kuartal, a.bulan
                FROM site.kpi_kuartal a
                WHERE a.kuartal = '$kuartal'
            )b on month(a.tanggal) = b.bulan
            where a.deleted_at is null and year(a.tanggal) = '$tahun' and a.status = 2
        )b on a.userid_tim = b.created_by or a.userid = b.created_by
        left join mpm.user c on a.userid_tim = c.id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function insert_dashboard_pemerataan_product_rank_spo($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            INSERT INTO site.kpi_dashboard_pemerataan_product
            SELECT '' as id, a.userid, a.rank, b.kuartal, b.tahun, SUM(b.total_pemerataan_product) as total_pemerataan_product, 
            SUM(b.total_pemerataan_product_verified) as total_pemerataan_product_verified, a.jml_tim, a.userid_atasan,
            '' as kpi, '' as point, '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view_view b on a.userid = b.userid
                WHERE a.rank = 'spo'
            )a
            INNER JOIN
            (
                SELECT a.created_by, b.kuartal, YEAR(a.tanggal) as tahun, count(*) as total_pemerataan_product, COUNT(a.verifikasi_at) as total_pemerataan_product_verified
                FROM site.kpi_pemerataan_product a 
                INNER JOIN
                (
                    SELECT a.kuartal, a.bulan
                    FROM site.kpi_kuartal a
                    WHERE a.kuartal = '$kuartal'
                )b on month(a.tanggal) = b.bulan
                where a.deleted_at is null and year(a.tanggal) = '$tahun'
                GROUP BY a.created_by
            )b on a.userid_tim = b.created_by or a.userid = b.created_by
            GROUP BY a.userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        
        $this->db->query($query);
    }

    public function insert_dashboard_pemerataan_product_rank_asps($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            INSERT INTO site.kpi_dashboard_pemerataan_product
            SELECT '' as id, a.userid, a.rank, b.kuartal, b.tahun, SUM(b.total_pemerataan_product) as total_pemerataan_product, 
            SUM(b.total_pemerataan_product_verified) as total_pemerataan_product_verified, a.jml_tim, a.userid_atasan,
            '' as kpi, '' as point, '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view_view b on a.userid = b.userid
                WHERE a.rank = 'asps'
            )a
            INNER JOIN
            (
                SELECT a.created_by, b.kuartal, YEAR(a.tanggal) as tahun, count(*) as total_pemerataan_product, COUNT(a.verifikasi_at) as total_pemerataan_product_verified
                FROM site.kpi_pemerataan_product a 
                INNER JOIN
                (
                    SELECT a.kuartal, a.bulan
                    FROM site.kpi_kuartal a
                    WHERE a.kuartal = '$kuartal'
                )b on month(a.tanggal) = b.bulan
                where a.deleted_at is null and year(a.tanggal) = '$tahun'
                GROUP BY a.created_by
            )b on a.userid_tim = b.created_by or a.userid = b.created_by
            GROUP BY a.userid
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function update_dashboard_pemerataan_product_point($kuartal = '', $created_at = '', $created_by = '')
    {
        $query = "
            UPDATE site.kpi_dashboard_pemerataan_product a
            JOIN (
                select b.kpi, b.point
                from site.kpi_master_perhitungan a
                LEFT JOIN (
                    select a.*
                    from site.kpi_master_perhitungan_detail a
                )b on a.id = b.id_master_perhitungan
                where a.category = 'pemerataan_product' and a.kuartal = '$kuartal' and a.rank = 'spo'
            )b on round(a.total_pemerataan_product_verified/if(a.jml_tim = 0, 1, a.jml_tim)) = b.kpi
            set a.point = b.point, a.kpi = b.kpi
            Where a.created_at = '$created_at' and a.created_by = '$created_by'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_dashboard_pemerataan_product_log($kuartal = null, $tahun = null, $created_at = null, $created_by = null)
    {
        $data = [
            'kuartal'       => $kuartal,
            'tahun'         => $tahun,
            'created_at'    => $created_at,
            'created_by'    => $created_by,
        ];

        $this->db->insert('site.kpi_dashboard_pemerataan_product_log', $data);
    }

    public function get_dashboard_pemerataan_product_tim_by_userid($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            SELECT a.userid_tim, a.rank, b.*, c.name
            FROM
            (
                SELECT a.userid, a.userid_tim, a.rank
                FROM site.kpi_master_team_struktural_view a
                WHERE a.userid = '$created_by' and a.userid_tim is not null
            )a 
            LEFT JOIN
            (
                SELECT * 
                FROM site.kpi_dashboard_pemerataan_product a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' and a.created_at = '$created_at' and a.created_by = '$created_by'
            )b on a.userid_tim = b.userid
            LEFT JOIN mpm.user c on a.userid_tim = c.id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_dashboard_pemerataan_product_by_userid($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            SELECT a.*, b.name
            FROM
            (
                SELECT * 
                FROM site.kpi_dashboard_pemerataan_product a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' 
                and a.created_at = '$created_at' and a.created_by = '$created_by'
                and a.userid = '$created_by'
            )a
            LEFT JOIN mpm.user b on a.userid = b.id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }
    
    public function generate_visibility($created_at)
    {

        $bulan_now = date('m',strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            select a.no_pelaporan, max(substr(a.no_pelaporan,14,3)) as urut
            from site.kpi_visibility a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_pelaporan is not null
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        $no_current = $this->db->query($query);
        if ($no_current->num_rows() > 0) {
            $params_urut = $no_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "VISIBILITY-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "VISIBILITY-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "VISIBILITY-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "VISIBILITY-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    public function get_visibility()
    {
        $query = "
            select a.*, b.name
            from site.kpi_visibility a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a
            )b on a.created_by = b.id
            where a.deleted_at is null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_visibility_by_signature($signature)
    {
        $query = "
            select a.*, b.*
            from site.kpi_visibility a LEFT JOIN (
                select a.id as userid, a.username, a.`name`, a.email
                from mpm.user a
            )b on a.created_by = b.userid
            where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_visibility_tim_by_userid($kuartal, $tahun, $created_by){
        $query = "
            
        SELECT a.userid_tim, a.rank, b.*, c.name
        FROM
        (
            SELECT a.userid, IF(a.userid_tim is null,a.userid,a.userid_tim) as userid_tim, a.rank
            FROM site.kpi_master_team_struktural_view a
            WHERE a.userid = '$created_by'
        )a
        LEFT JOIN
        (
            SELECT *
            FROM site.kpi_visibility a 
            INNER JOIN
            (
                SELECT a.kuartal, a.bulan
                FROM site.kpi_kuartal a
                WHERE a.kuartal = '$kuartal'
            )b on month(a.tanggal) = b.bulan
            where a.deleted_at is null and year(a.tanggal) = '$tahun' and a.status = 2
        )b on a.userid_tim = b.created_by or a.userid = b.created_by
        left join mpm.user c on a.userid_tim = c.id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function insert_dashboard_visibility_rank_spo($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            INSERT INTO site.kpi_dashboard_visibility
            SELECT '' as id, a.userid, a.rank, b.kuartal, b.tahun, SUM(b.total_visibility) as total_visibility, 
            SUM(b.total_visibility_verified) as total_visibility_verified, a.jml_tim, a.userid_atasan,
            '' as kpi, '' as point, '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view_view b on a.userid = b.userid
                WHERE a.rank = 'spo'
            )a
            INNER JOIN
            (
                SELECT a.created_by, b.kuartal, YEAR(a.tanggal) as tahun, count(*) as total_visibility, 
                COUNT(a.verifikasi_at) as total_visibility_verified
                FROM site.kpi_visibility a 
                INNER JOIN
                (
                    SELECT a.kuartal, a.bulan
                    FROM site.kpi_kuartal a
                    WHERE a.kuartal = '$kuartal'
                )b on month(a.tanggal) = b.bulan
                where a.deleted_at is null and year(a.tanggal) = '$tahun'
                GROUP BY a.created_by
            )b on a.userid_tim = b.created_by or a.userid = b.created_by
            GROUP BY a.userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        
        $this->db->query($query);
    }

    public function insert_dashboard_visibility_rank_asps($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            INSERT INTO site.kpi_dashboard_visibility
            SELECT '' as id, a.userid, a.rank, b.kuartal, b.tahun, SUM(b.total_visibility) as total_visibility, 
            SUM(b.total_visibility_verified) as total_visibility_verified, a.jml_tim, a.userid_atasan,
            '' as kpi, '' as point, '$created_at', '$created_by'
            FROM
            (
                SELECT a.*, b.jml_tim
                FROM site.kpi_master_team_struktural_view a
                LEFT JOIN site.kpi_jml_tim_view_view b on a.userid = b.userid
                WHERE a.rank = 'asps'
            )a
            INNER JOIN
            (
                SELECT a.created_by, b.kuartal, YEAR(a.tanggal) as tahun, count(*) as total_visibility,
                COUNT(a.verifikasi_at) as total_visibility_verified
                FROM site.kpi_visibility a 
                INNER JOIN
                (
                    SELECT a.kuartal, a.bulan
                    FROM site.kpi_kuartal a
                    WHERE a.kuartal = '$kuartal'
                )b on month(a.tanggal) = b.bulan
                where a.deleted_at is null and year(a.tanggal) = '$tahun'
                GROUP BY a.created_by
            )b on a.userid_tim = b.created_by or a.userid = b.created_by
            GROUP BY a.userid
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function update_dashboard_visibility_point($kuartal = '', $created_at = '', $created_by = '')
    {
        $query = "
            UPDATE site.kpi_dashboard_visibility a
            JOIN (
                select b.kpi, b.point
                from site.kpi_master_perhitungan a
                LEFT JOIN (
                    select a.*
                    from site.kpi_master_perhitungan_detail a
                )b on a.id = b.id_master_perhitungan
                where a.category = 'visibility' and a.kuartal = '$kuartal' and a.rank = 'spo'
            )b on round(a.total_visibility_verified/if(a.jml_tim = 0, 1, a.jml_tim)) = b.kpi
            set a.point = b.point, a.kpi = b.kpi
            Where a.created_at = '$created_at' and a.created_by = '$created_by'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function insert_dashboard_visibility_log($kuartal = null, $tahun = null, $created_at = null, $created_by = null)
    {
        $data = [
            'kuartal'       => $kuartal,
            'tahun'         => $tahun,
            'created_at'    => $created_at,
            'created_by'    => $created_by,
        ];

        $this->db->insert('site.kpi_dashboard_visibility_log', $data);
    }

    public function get_dashboard_visibility_tim_by_userid($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            SELECT a.userid_tim, a.rank, b.*, c.name
            FROM
            (
                SELECT a.userid, a.userid_tim, a.rank
                FROM site.kpi_master_team_struktural_view a
                WHERE a.userid = '$created_by' and a.userid_tim is not null
            )a 
            LEFT JOIN
            (
                SELECT * 
                FROM site.kpi_dashboard_visibility a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' and a.created_at = '$created_at' and a.created_by = '$created_by'
            )b on a.userid_tim = b.userid
            LEFT JOIN mpm.user c on a.userid_tim = c.id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_dashboard_visibility_by_userid($kuartal = '', $tahun = '', $created_at = '', $created_by = '')
    {
        $query = "
            SELECT a.*, b.name
            FROM
            (
                SELECT * 
                FROM site.kpi_dashboard_visibility a
                WHERE a.kuartal = '$kuartal' and a.tahun = '$tahun' 
                and a.created_at = '$created_at' and a.created_by = '$created_by'
                and a.userid = '$created_by'
            )a
            LEFT JOIN mpm.user b on a.userid = b.id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }
}