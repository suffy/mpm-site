<?php

class Model_apps extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->userid = $this->session->userdata('id');
    }

    public function get_login($data)
    {    
        $username = $data['username'];
        $password = $data['password'];

        // $query = "
        //     select *
        //     from mpm.user a
        //     where a.username = '$username' and a.password = md5('$password')
        // ";

        $query = "
            select a.id, a.username, a.email, a.name, a.kode_company, a.kode_apps, a.supp,
                a.image, a.`level`, b.view_aktivitas_mpm, b.view_aktivitas_deltomed_gt, b.view_aktivitas_deltomed_mt
            from mpm.user a left join (
                select *
                from site.master_team_apps a 
            )b on a.id = b.userid
            where a.username = '$username' and a.password = md5('$password')
        ";
        return $this->db->query($query)->result_array();
    }

    public function get_user($data)
    {
        $username = $data['username'];
        $query = "
            select *
            from mpm.user a
            where a.username = '$username' 
        ";
        // echo $query;
        return $this->db->query($query)->result_array();
    }

    public function input_profile_log($data)
    {
        $this->db->insert('profile_log', $data);
        return $this->db->insert_id();
    }

    public function update_profile($data)
    {
        $this->db->update('mpm.user', $data, array('username' => $data['username']));
        return $this->db->affected_rows();
    }

    public function input_posting_log($data)
    {
        $this->db->insert('posting_log', $data);
        return $this->db->insert_id();
    }

    public function get_posting($data){
        
        $team_id = $data['team_id'];
        $limit = $data['limit'];
        $from = $data['from'];
        $to = $data['to'];
        $username = $data['username'];
        $view_aktivitas_mpm = $data['view_aktivitas_mpm'];

        if($view_aktivitas_mpm == 'nasional'){
            $params_view = "";
        }else{
            $params_view = "and d.view_aktivitas_mpm = '$view_aktivitas_mpm'";
        }

        if($team_id){
            $params = "and a.team_id = '$team_id'";
        }else{
            $params = "";
        }

        if($limit){
            $params_limit= " limit $limit";
        }else{
            $params_limit = " limit 100";
        }

        if($from && $to){
            $params_periode = "and date(a.created_at) between '$from' and '$to'";
        }else{
            $params_periode = "";
        }

        if($username){
            $params_username = "and a.username = '$username'";
        }else{
            $params_username = "";
        }

        $query = "
            select 	a.id, a.email, a.username, a.content, a.content_competitor, a.image, a.toko,
                    a.team_id, a.created_at, 
                    b.image as profile_image, d.view_aktivitas_mpm, 
                    c.count_comment, 
                    IF(a.category_id = 1, 'market visit',
                    IF(a.category_id = 2, 'cek kompetitor',
                    IF(a.category_id = 3, 'cek program',  'others'))) as category, a.latitude, a.longitude
            from dbrest.posting_log a left join (
                select a.id, a.username, a.email, a.kode_company, a.kode_apps, a.image
                from site.master_user a 
            )b	on a.username = b.username left join (
                    select a.post_id, count(*) as count_comment
                    from dbrest.`comment` a
                    where a.deleted_at is null
                    group by a.post_id
            )c on a.id = c.post_id left join (
                select a.id, a.userid, a.view_aktivitas_mpm
                from site.master_team_apps a 
            )d on b.id = d.userid
            where a.deleted_at is null $params $params_periode $params_username $params_view
            order by a.id desc
            $params_limit
        ";

        // $query = "
        //     select 	a.id, a.email, a.username, a.content, a.content_competitor, a.image, a.toko,
        //             a.team_id, a.created_at, 
        //             b.image as profile_image, d.view_aktivitas_mpm, 
        //             c.count_comment
        //     from dbrest.posting_log a left join (
        //         select a.id, a.username, a.email, a.kode_company, a.kode_apps, a.image
        //         from site.master_user a 
        //     )b	on a.username = b.username left join (
        //             select a.post_id, count(*) as count_comment
        //             from dbrest.`comment` a
        //             where a.deleted_at is null
        //             group by a.post_id
        //     )c on a.id = c.post_id left join (
        //         select a.id, a.userid, a.view_aktivitas_mpm
        //         from site.master_team_apps a 
        //     )d on b.id = d.userid
        //     where a.deleted_at is null
        //     order by a.id desc
        // ";
        return $this->db->query($query)->result_array();
    }

    public function input_absensi_log($data)
    {
        $this->db->insert('absensi_log', $data);
        return $this->db->insert_id();
    }

    public function get_absensi($data){
        
        $email = $data['email'];

        if($email){
            $params = "and a.email = '$email'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.image as profile_image
            from dbrest.absensi_log a left join mpm.user b 
                on a.username = b.username
            where a.deleted_at is null $params
            order by a.id desc
            limit 10
        ";
        return $this->db->query($query)->result_array();
    }

    public function input_absensi_transaksi_log($data)
    {
        $this->db->insert('absensi_transaksi_log', $data);
        return $this->db->insert_id();
    }

    public function get_spreading_products(){
        $query = "
            select *
            from dbrest.spreading_product a
        ";
        return $this->db->query($query)->result_array();
    }

    public function input_spreading($data)
    {
        $this->db->insert('spreading_post', $data);
        return $this->db->insert_id();
    }

    public function get_spreading_post($data){
        $username = $data['username'];
        $view_aktivitas_deltomed_gt = $data['view_aktivitas_deltomed_gt'];

        if($view_aktivitas_deltomed_gt == 'nasional'){
            // $params = "and a.view_aktivitas_deltomed_gt = '$view_aktivitas_deltomed_gt'";
            // $params = "and a.username = '$username'";
            $params = "";
        }else{
            $params = "and a.username = '$username'";
        }

        // if($username){
        //     $params = "and a.username = '$username'";
        // }else{
        //     $params = "";
        // }
        $query = "
            select 	a.*,
                    b.count_avaibility, b.product_array, format(c.total_value,0) as total_value, c.count_product_transaksi, c.transaksi_array,
                    d.image_after, d.image_before
            from dbrest.spreading_post a LEFT JOIN (
                select a.id as id_avaibility , a.id_spreading, count(*) as count_avaibility, GROUP_CONCAT(b.kodeprod) AS product_array
                from dbrest.spreading_survei a left join dbrest.spreading_products_survei b 
                on a.id = b.id_spreading_survei
                GROUP BY a.id
            )b on a.id_survei = b.id_avaibility left join (
                select a.id, a.id_tagging, a.total_value, a.catatan, a.created_at, count(*) as count_product_transaksi,
                        GROUP_CONCAT(concat(b.kodeprod, '(qty:' , b.qty,')')) as transaksi_array
                from dbrest.spreading_transaksi a left join dbrest.spreading_transaksi_products b 
                    on a.id = b.id_spreading_transaksi
                group by a.id
            )c on a.id_transaksi = c.id left join 
            (
                select a.id_spreading_tagging,a.image_after, a.image_before
                from dbrest.spreading_images a                 
                GROUP BY a.id_spreading_tagging
            )d on a.id =d.id_spreading_tagging
            where a.deleted_at is null $params
            order by a.id desc
        ";
        // print_r($query);
        return $this->db->query($query)->result_array();
    }

    public function input_spreading_survei($data)
    {
        $this->db->insert('spreading_survei', $data);
        return $this->db->insert_id();
    }

    public function insert_spreading_products_survei($data){
        $this->db->insert('spreading_products_survei', $data);
        return $this->db->insert_id();
    }

    public function update_spreading_post($data, $id){
        $this->db->update('spreading_post', $data, array('id' => $id));
        return $this->db->affected_rows();
    }

    public function input_spreading_transaksi($data)
    {
        $this->db->insert('spreading_transaksi', $data);
        return $this->db->insert_id();
    }

    public function insert_spreading_transaksi_products($data){
        $this->db->insert('spreading_transaksi_products', $data);
        return $this->db->insert_id();
    }

    public function get_spreading_summary($data){
        $email = $data['email'];

        if($email){
            $params = "and a.email = '$email'";
        }else{
            $params = "";
        }
        $query = "
            select format(sum(b.total_value),0) as total_value, count(*) as count_tagging
            from dbrest.spreading_post a left join (
                select a.id_tagging, sum(a.total_value) as total_value
                from dbrest.spreading_transaksi a 
                where a.deleted_at is null
                GROUP BY a.id_tagging
            )b on b.id_tagging = a.id
            where a.deleted_at is null $params      
        ";
        // print_r($query);
        return $this->db->query($query)->result_array();
    }

    public function input_spreading_delete($data)
    {
        $this->db->insert('spreading_delete_log', $data);
        return $this->db->insert_id();
    }

    public function input_spreading_images($data)
    {
        $this->db->insert('spreading_images', $data);
        return $this->db->insert_id();
    }

    public function input_spreading_checkout($data)
    {
        $this->db->insert('spreading_checkout_log', $data);
        return $this->db->insert_id();
    }

    public function get_spreading_post_where_status($email, $status)
    {
        $query = "
            select *
            from dbrest.spreading_post a 
            where a.deleted_at is null and a.email ='$email' and a.status ='$status'
        ";
        return $this->db->query($query);
    }

    public function input_service($data)
    {
        $this->db->insert('service_post', $data);
        return $this->db->insert_id();
    }

    public function get_service(){

        $query = "
            select *
            from dbrest.service_post a 
            where a.deleted_at is null     
        ";
        // print_r($query);
        return $this->db->query($query)->result_array();
    }

    public function get_master_printer(){

        $query = "
            select *
            from dbrest.master_printer a 
            where a.deleted_at is null     
        ";
        // print_r($query);
        return $this->db->query($query)->result_array();
    }

    public function input_master_printer($data)
    {
        $this->db->insert('master_printer', $data);
        return $this->db->insert_id();
    }

    public function get_toner($data=''){

        if($data)
        {
            $params = "and a.id_printer = ".$data['id_printer'];
        }else{
            $params = "";
        }

        $query = "
            select *
            from dbrest.master_printer_toner a 
            where a.deleted_at is null $params
        ";
        // print_r($query);
        // die;
        return $this->db->query($query)->result_array();
    }

    public function input_toner($data)
    {
        $this->db->insert('master_printer_toner', $data);
        return $this->db->insert_id();
    }

    public function input_refill($data)
    {
        $this->db->insert('transaksi_refill', $data);
        return $this->db->insert_id();
    }

    public function input_refill_detail($data)
    {
        $this->db->insert('transaksi_refill_detail', $data);
        return $this->db->insert_id();
    }

    public function get_refill($data=''){

        if($data)
        {
            $params = "and a.id = ".$data['id'];
        }else{
            $params = "";
        }

        $query = "
            select *
            from dbrest.transaksi_refill a
            where a.deleted_at is null $params
            order by a.id desc
            
        ";
        // print_r($query);
        // die;
        return $this->db->query($query)->result_array();
    }

    public function get_refill_detail($id_refill=''){
        $query = "
            select *
            from dbrest.transaksi_refill_detail a
            where a.deleted_at is null and a.id_transaksi_refill = $id_refill
        ";
        // print_r($query);
        // die;
        return $this->db->query($query)->result_array();
    }

    public function get_master_user($username)
    {
        $query = "
            select *
            from site.master_user a 
            where a.username = '$username' and a.active = 1
        ";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi($userid, $tahun, $bulan, $tanggal)
    {
        $query = "
            select a.*,TIMEDIFF(a.actual_keluar,a.actual_masuk) as total_jam_kerja
            from site.absensi_transaksi a 
            where a.userid = $userid and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tanggal = '$tanggal'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_by_id($id)
    {
        $query = "
            select a.actual_masuk, a.actual_keluar, 
            if(timediff(a.actual_keluar, a.actual_masuk) is null, 0,timediff(a.actual_keluar, a.actual_masuk)) as total_jam_kerja
            from site.absensi_transaksi a 
            where a.id = $id
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_absensi_transaksi_by_userid_tanggal($userid, $tanggal)
    {
        $query = "
            select a.*
            from site.absensi_transaksi a 
            where a.userid = $userid and a.tanggal = '$tanggal'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function update_absensi_transaksi($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.absensi_transaksi', $data);
        return $this->db->affected_rows();
    }

    public function get_kalender()
    {
        $query = "
             select concat(a.kode_comp, a.nocab) as site_code, max(hrdok) as tanggal, b.nama_comp, 
                    b.branch_name, c.lastupload, c.status_closing
            from data2025.fi a left join site.master_site b 
            on concat(a.kode_comp, a.nocab) = b.site_code left join 
            (
                select a.id, a.userid, max(a.lastupload) as lastupload, a.filename, 
                        substr(a.filename,3,2) as nocab, a.status_closing
                from mpm.upload a 
                where a.tahun = 2025 and a.bulan = 3 and a.filename like 'DT%'
                GROUP BY a.userid
            )c on a.nocab = c.nocab
            where a.bulan = 3 and concat(a.kode_comp, a.nocab) in ('AMBA5','ASAA2','BB11B','BB22B','BBJ1G','BBT25','BD152','BDG02','BGR04','BIH1C','BIMD3','BJNB2','BJRB6','BKL35','BKMV5','BLGW4','BLR32','BLT69','BONV4','BRBS0','BTGB8','BYWB1','CAKC4','CARC1','CJR79','CKGC2','CKRC3','CLP12','CRB08','CSSK3','DASD1','DBSD4','DIY98','DMK76','GIDG3','GRTG2','GSKG1','GTO87','IDMI1','JAMB0','JBGY6','JBLJ2','JBR95','JK101','JK342','JKT88','JMB29','JMI72','JTM91','KBB1F','KBMK4','KBT1E','KDR24','KDS19','KLTU5','KNGK5','KRBK7','KRPK6','LANL2','LL101','LL202','LL303','LL41A','LLGL5','LM11M','LM24M','LM35M','LM46M','LM57M','LM68M','LMJL1','LOML3','LS11L','LS22L','LS33L','LS44L','LS55L','LS66L','LS77L','LS88L','LS99L','LSWL6','MAJ3M','MANW5','MBO2M','MDU23','MEDM1','MGL15','MJKY5','MKBM5','MLG27','MLPM3','MMMM4','MNBM9','MNLM8','MNPM6','MWRW6','PAD1P','PALV2','PARV3','PATP3','PBNP9','PKB51','PKL63','PKRP8','PML73','PNGP5','PRBP1','PSIP6','PSNP4','PTK82','PURP7','PWD64','PWJ66','PWT61','RPA43','S1D1D','S2D2D','S3D3D','S4D4D','S5D5D','S6D6D','S7D7D','S8D8D','S9D9D','SB4S3','SBG47','SBLJ3','SD11S','SD22S','SD33S','SD44S','SD55S','SD66S','SD77S','SD88S','SD99S','SDO46','SIDS6','SKB59','SKHU3','SKW82','SL378','SMG14','SMRB7','SOL96','SPTU4','SRAU6','SRG03','SSJD2','SSMS9','STBS1','TBNT1','TEBT3','TGA26','TGL06','TGR39','TJPT6','TMGT4','TSM60','TSRT7','VBTV1','WTKW1')
            GROUP BY concat(a.kode_comp, a.nocab)
            order by max(hrdok) asc
        ";
        return $this->db->query($query);
    }

    public function input_kirim_refill($data)
    {
        $this->db->insert('transaksi_kirim_refill', $data);
        return $this->db->insert_id();
    }

    public function update_refill($data, $id){
        
        $this->db->where('id', $id);
        $this->db->update('transaksi_refill', $data);
        return $this->db->affected_rows();
    }

    public function get_version($data)
    {
        // var_dump($data);
        // die;
        $version = $data['version'];
        $query = "
            select a.version, a.file
            from dbrest.update a 
            where a.is_latest = 1 and a.version = '$version'
        ";
        return $this->db->query($query)->result_array();
    }

    public function get_version_latest()
    {
        $query = "
            select a.version, a.file
            from dbrest.update a 
            where a.is_latest = 1
        ";
        return $this->db->query($query)->result_array();
    }

    public function get_mt_market_visit($email, $status=null)
    {
        if($status)
        {
            $params_status = " and a.status = '$status' ";
        }else{
            $params_status = "";
        }

        if($email == 'suffy@muliaputramandiri.com' || $email == 'jimmy.gunawan@deltomed.com' || $email == 'krisnanto@deltomed.com' ||$email == 'vischa.diani@deltomed.com')
        {
            $params_email = "";
        }else{
            $params_email = " and a.email = '$email' ";
        }

        $query = "
            select *
            from dbrest.mt_market_visit a  
            where a.deleted_at is null $params_email $params_status
            order by a.id desc
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_mt_market_visit_status($email, $status)
    {
        $query = "
            select *
            from dbrest.mt_market_visit a  
            where a.deleted_at is null and a.email = '$email' and a.status = '$status'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function input_mt_market_visit($data)
    {
        $this->db->insert('mt_market_visit', $data);
        return $this->db->insert_id();
    }

    public function get_mt_market_visit_detail_by_id_header($id)
    {
        $query = "
            select *
            from mt_market_visit_detail a  
            where a.deleted_at is null and a.id_header = '$id'
        ";
        return $this->db->query($query);
    }

    public function get_mt_market_visit_detail_by_id($id)
    {
        $query = "
            select *
            from dbrest.mt_market_visit_detail a left join dbrest.mt_market_visit b 
                on a.id_header = b.id
            where a.deleted_at is null and a.id = '$id'
        ";
        return $this->db->query($query);
    }

    public function input_mt_market_visit_detail($data)
    {
        $this->db->insert('mt_market_visit_detail', $data);
        return $this->db->insert_id();
    }

    public function update_mt_market_visit($data, $id){
        $this->db->update('mt_market_visit', $data, array('id' => $id));
        return $this->db->affected_rows();
    }

    public function input_comment($data)
    {
        $this->db->insert('comment', $data);
        return $this->db->insert_id();
    }

    public function get_comment($data){
        
        $post_id = $data['post_id'];
        $query = "
            select a.*, b.image as profile_image
            from dbrest.`comment` a left join mpm.user b 
                on a.username = b.username
            where a.post_id = '$post_id'
        ";
        return $this->db->query($query)->result_array();
    }

    public function get_absensi_monthly($data)
    {    
        $userid = $data['userid'];
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];
        $flag_weekend = $data['flag_weekend'];

        if($flag_weekend == '1'){
            $params_status_hari = " and a.status_hari not in (0)";
        }else{
            $params_status_hari = " and a.status_hari not in (6,0)";
        }

        // $query = "
        //     select *
        //     from site.absensi_transaksi a 
        //     where a.userid = $userid and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan $params_status_hari
        // ";

        $query = "
            select 	a.tanggal, a.jam_masuk_kantor, a.jam_keluar_kantor,
                    a.actual_masuk, a.actual_keluar, a.total_jam_kerja, a.flag_terlambat,
                    a.keterangan, a.status_hari, b.flag_need_action
            from site.absensi_transaksi a left join (
                select a.tanggal, 1 as flag_need_action
                from site.absensi_transaksi a 
                where a.userid = $userid and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan $params_status_hari 
                and (a.actual_masuk is null or a.actual_keluar is null or a.flag_terlambat = 1) and (a.keterangan is null or a.keterangan = '')
            )b on a.tanggal = b.tanggal
            where a.userid = $userid and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan $params_status_hari 
            ORDER BY a.tanggal
        ";

        // echo $query;
        return $this->db->query($query)->result_array();
    }

    public function get_flag_weekend($data)
    {
        $userid = $data['userid'];
        $query = "
            select a.userid_pelaksana, a.flag_weekend
            from management_rpd.m_karyawan a
            where a.userid_pelaksana = $userid 
        ";
        return $this->db->query($query)->result_array();
    }

    public function input_absensi_note($data)
    {
        $this->db->insert('absensi_note', $data);
        return $this->db->insert_id();
    }

    public function input_absensi_request_approval($data)
    {
        $this->db->insert('absensi_request_approval', $data);
        return $this->db->insert_id();
    }

    public function cek_absensi($tahun, $bulan, $userid)
    {
        $query = "
            select *
            from site.absensi a 
            where a.tahun = $tahun and a.bulan = $bulan and a.userid = $userid
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query)->result_array();
    }

    public function input_absensi($data)
    {
        $this->db->insert('site.absensi', $data);
        return $this->db->insert_id();
    }

    public function absensi_summary($tahun, $bulan, $userid, $flag_weekend)
    {
        if($flag_weekend == '1'){
            $params_status_hari = " and a.status_hari not in (0)";
        }else{
            $params_status_hari = " and a.status_hari not in (6,0)";
        }

        $query = "
            select sum(a.tidak_lengkap) as total_tidak_lengkap, sum(a.terlambat) as total_terlambat
            from 
            (
                    select count(*) as tidak_lengkap, '' as terlambat
                    from site.absensi_transaksi a 
                    where 	a.userid = $userid and year(a.tanggal) = $tahun and 
                                month(a.tanggal) = $bulan $params_status_hari and 
                                (a.actual_masuk is null or a.actual_keluar is null or 
                                (a.actual_keluar < (select jam_keluar from site.absensi_jam_kerja))) and 
                                (a.flag_status_absensi is null or a.flag_status_absensi = 0)
                    union all 
                    select '', count(*) as terlambat
                    from site.absensi_transaksi a 
                    where 	a.userid = $userid and year(a.tanggal) = $tahun and 
                                month(a.tanggal) = $bulan and a.flag_terlambat = 1
            )a 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query)->result_array();
    }

    public function get_history_approval_history($data)
    {    
        $userid = $data['userid'];

        $query = "
            select a.tahun, a.bulan, a.total_hari_kerja, a.hadir, a.terlambat, a.tidak_lengkap, a.status as `status`, a.verifikasi_keterangan as keterangan, a.updated_at, a.created_at
            from site.absensi a 
            where a.userid = $userid
        ";

        // echo $query;
        return $this->db->query($query)->result_array();
    }

    public function get_absensi_team($data)
    {    
        $userid = $data['userid'];

        $query = "
            select a.id, a.tahun, a.bulan, a.total_hari_kerja, a.hadir, a.terlambat, a.tidak_lengkap, a.`status`, a.flag_status, 
			a.verifikasi_status, a.verifikasi_keterangan, a.verifikasi_at, c.username
            from site.absensi a left join (
                select * 
                from management_rpd.m_karyawan a 
            )b on a.userid = b.userid_pelaksana left join site.master_user c 
                on a.userid = c.id 
            where b.userid_verifikasi1 = $userid
            ORDER BY a.id desc
        ";

        // echo $query;
        return $this->db->query($query)->result_array();
    }

    public function input_absensi_verifikasi($data)
    {
        $this->db->insert('absensi_verifikasi', $data);
        return $this->db->insert_id();
    }

    public function update_absensi($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.absensi', $data);
        return $this->db->affected_rows();
    }

    public function get_open_credit_limit($data){
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];
        $limit = $data['limit'];
        $query = "
             select 	a.id, if(a.status_approval is null, 'doi checking','finance approval') as status,
                        a.company, e.namasupp as principal, date(a.tglpesan) as tglorder, date(a.tglpo) as tglpo, 
                        a.nopo,a.total_value, a.kode_alamat,
                        b.bank_garansi, b.cl, b.kode_lang, 
                        c.saldoakhir, c.jt, d.total_value_current_month,
                        e.namasupp, a.open, a.status_approval, a.open_by, f.username as open_by,
                        a.total_value + if(d.total_value_current_month is null,0,d.total_value_current_month) as total_estimasi, a.signature
            from mpm.po a inner join
            (
                select a.id, a.username, a.kode_lang, a.bank_garansi, a.cl
                from mpm.user a 
                where a.active = 1 
            )b on a.userid = b.id left join (
                select kode_lang, sum(saldoakhir) as saldoakhir, sum(jt) as jt
                FROM
                (
                    select  if(right(a.kode_lang,5) = '20252','20156',
                            if(right(a.kode_lang,5) = '20251','20250',
                            if(right(a.kode_lang,5) = '20268','20256',
                            if(right(a.kode_lang,5) = '20267','20256',
                            right(a.kode_lang,5))))) as kode_lang, 
                            a.saldoakhir, a.jt
                    from    db_analisis.t_temp_piutang a 
                )a GROUP BY kode_lang      
            )c on b.kode_lang = c.kode_lang left join (
                select a.userid, sum(a.total_value) as total_value_current_month
                from mpm.po a 
                where year(a.tglpo) = $tahun and month(a.tglpo) = $bulan
                GROUP BY a.userid
            )d on a.userid = d.userid left join (
                select a.supp, a.namasupp
                from site.master_supplier a 
            )e on a.supp = e.supp left join site.master_user f 
            on a.open_by = f.id
            where a.open = 0
            order by a.id desc
            limit $limit
        ";

        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;

        return $this->db->query($query)->result_array();
    }

    public function input_open_credit_limit($data)
    {
        $this->db->insert('open_credit_limit', $data);
        return $this->db->insert_id();
    }

    public function update_open_credit_limit($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('mpm.po', $data);
        return $this->db->affected_rows();
    }

    public function get_activity_summary($data){
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];
        $username = $data['username'];
        $query = "
             select a.username, count(*) as total_aktivitas
            from dbrest.posting_log a 
            where a.deleted_at is null
            GROUP BY a.username
            ORDER BY count(*) desc
        ";

        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;

        return $this->db->query($query)->result_array();
    }

    public function input_gt_mapping_outlet($data)
    {
        $this->db->insert('gt_mapping_outlet', $data);
        return $this->db->insert_id();
    }

    public function input_gt_market_visit($data)
    {
        $this->db->insert('gt_market_visit', $data);
        return $this->db->insert_id();
    }

    public function get_gt_mapping_outlet($data)
    {
        $username = $data['username'];
        $query = "
             select 	a.nama_pasar, a.nama_kecamatan, a.nama_toko, a.posisi_toko, a.class_toko, a.tipe_toko, a.status_ob, 
                        a.availability_obat_masuk_angin, a.availability_obat_batuk_sachet, a.availability_obat_masuk_angin_anak,
                        a.availability_obat_pegel_linu, a.image,
                        a.latitude, a.longitude, a.city, a.district, a.formatted_address, a.username, a.created_at
            from dbrest.gt_mapping_outlet a 
            where a.deleted_at is null
        ";

        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;

        return $this->db->query($query)->result_array();
    }

    public function input_gt_realisasi_event($data)
    {
        $this->db->insert('gt_realisasi_event', $data);
        return $this->db->insert_id();
    }

    public function input_gt_branding_delto_corner($data)
    {
        $this->db->insert('gt_branding_delto_corner', $data);
        return $this->db->insert_id();
    }

    public function get_surat_jalan($data)
    {
        $username = $data['username'];
        $from = $data['from'];
        $to = $data['to'];
        $principal = $data['principal'];

        if ($principal == '001') {
            $tabel = "site.surat_jalan_deltomed";
        }elseif($principal == '005'){
            $tabel = "site.surat_jalan";
        }else{
            return false;
        }

        $query = "
            select 	a.id, a.supp, a.nodo, a.tgldo, a.nopo, date(a.tglpo) as tglpo, 
                    a.kode_surat_jalan, a.company, a.kode_alamat, 
                    a.alamat_po, a.alamat_kirim_po, a.alamat_gudang, a.created_at
            from $tabel a 
            where a.deleted_at is null and date(a.tgldo) between '$from' and '$to'
        ";

        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;

        return $this->db->query($query)->result_array();
    }

    public function get_surat_jalan_detail($data)
    {
        $username = $data['username'];
        $id = $data['id'];
        $principal = $data['principal'];

        if ($principal == '001') {
            $tabel = "site.surat_jalan_detail_deltomed";
        }elseif($principal == '005'){
            $tabel = "site.surat_jalan_detail";
        }else{
            return false;
        }

        $query = "
            select  a.kodeprod, a.namaprod, a.banyak, a.total_karton, a.total_karton_berat,
                    a.total_karton_volume, a.batch_number, a.ed
            from $tabel a 
            where a.id_ref = $id
        ";

        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;

        return $this->db->query($query)->result_array();
    }

    public function get_region($userid)
    {
        $query = "
            select  a.region
            from site.map_akses_region a 
            where a.userid = $userid and a.`status` = 1
            ORDER BY a.region = 'nasional' desc
            "
        ;

        return $this->db->query($query)->result_array();
    }

    public function get_sitecode($region)
    {
        $query = "
            select a.site_code
            from site.master_site a
            where a.active = 1 and (a.region in ('$region') or a.sub_region in ('$region'))
            group by a.site_code"
        ;

        return $this->db->query($query)->result_array();
    }

    public function get_sitecode_all()
    {
        $query = "
            select a.site_code
            from site.master_site a
            where a.active = 1
            group by a.site_code"
        ;

        return $this->db->query($query)->result_array();
    }

    public function get_sales_subbranch($data)
    {
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];
        $site_code = $data['site_code'];

        $query = "            
            select  a.site_code, a.total_value, b.branch_name, b.nama_comp, $tahun, $bulan, a.tanggal
            from 
            (
                select a.site_code, sum(a.tot1) as total_value, a.tanggal
                from 
                (
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as tot1, max(a.hrdok) as tanggal
                    from data$tahun.fi a 
                    where a.bulan = $bulan and concat(a.kode_comp, a.nocab) in ($site_code)
                    GROUP BY concat(a.kode_comp, a.nocab) 
                    union all 
                    select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as tot1, max(a.hrdok) as tanggal
                    from data$tahun.ri a 
                    where a.bulan = $bulan and concat(a.kode_comp, a.nocab) in ($site_code)
                    GROUP BY concat(a.kode_comp, a.nocab) 
                )a GROUP BY site_code
            )a left join (
                select a.site_code, a.branch_name, a.nama_comp
                from site.master_site a 
            )b on a.site_code = b.site_code
        ";

        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;

        return $this->db->query($query)->result_array();
    }

    public function input_mpm_market_visit($data)
    {
        $this->db->insert('mpm_market_visit', $data);
        return $this->db->insert_id();
    }

    public function input_mpm_activity($data)
    {
        $this->db->insert('mpm_activity', $data);
        return $this->db->insert_id();
    }

    public function get_username_team($userid)
    {        
        $query = "
            select b.username
            from management_rpd.m_karyawan a left join (
                select a.id, a.username
                from site.master_user a 
            )b on a.userid_pelaksana = b.id
            where a.userid_verifikasi1 = $userid or a.userid_verifikasi2 = $userid or a.userid_pelaksana = $userid
            GROUP BY a.userid_pelaksana
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_username_team_gt($userid)
    {        
        if ($userid == 318 || $userid == 613 || $userid == 297 || $userid == 521 || $userid == 1074 || $userid == 572) {
            $params = "";
        }else{
            $params = "where a.userid_verifikasi1 = $userid or a.userid_verifikasi2 = $userid or a.userid_pelaksana = $userid";
        }
        $query = "
            select b.username
            from management_rpd.m_karyawan a inner join (
                select a.id, a.username
                from site.master_user a                 
                where a.kode_apps in ('deltomed_gt', 'deltomed_gt_nasional') 
            )b on a.userid_pelaksana = b.id
            $params
            GROUP BY a.userid_pelaksana
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_username_team_mt($userid)
    {        
        if ($userid == 788 || $userid == 1023 || $userid == 1045 || $userid == 1047 || $userid == 297) {
            $params = "";
        }else{
            $params = "where a.userid_verifikasi1 = $userid or a.userid_verifikasi2 = $userid or a.userid_pelaksana = $userid";
        }
        $query = "
            select b.username
            from management_rpd.m_karyawan a inner join (
                select a.id, a.username
                from site.master_user a                 
                where a.kode_apps in ('deltomed_mt', 'deltomed_mt_nasional') 
            )b on a.userid_pelaksana = b.id
            $params
            GROUP BY a.userid_pelaksana
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_mpm_dashboard_activity($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $username_team = $data['username_team'];
        $query = "
            select 	a.username, a.type, count(*) as total_activity
            from dbrest.mpm_activity a 
            where date(a.created_at) BETWEEN '$from' and '$to' and a.username in ($username_team)
            GROUP BY a.username, a.type
            union all
            select 	a.username, 'market_visit', count(*) as total_market_visit
            from dbrest.mpm_market_visit a 
            where date(a.created_at) BETWEEN '$from' and '$to' and a.username in ($username_team)
            GROUP BY a.username
        ";
        return $this->db->query($query)->result_array();
    }

    public function get_market_audit_groupby_user($username = null)
    {
        if ($username) {
            $params = "where a.username in ($username)";
        }else{
            $params = "";
        }

        $query = "
            select a.username, b.id
            from dbrest.gt_market_audit a left join (
                select a.id, a.username
                from site.master_user a 
            ) b on a.username = b.username
            $params
            GROUP BY a.username
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_gt_market_audit($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team_gt($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        $query = "
            select  a.id, a.username, a.nama_pasar, a.nama_kecamatan, a.nama_toko, a.posisi_toko, a.class_toko,
                    a.tipe_toko, a.status_ob, a.availability_obat_masuk_angin,
                    a.availability_obat_batuk_sachet,
                    a.availability_obat_masuk_angin_anak, 
                    a.availability_obat_pegel_linu,
                    a.availability_permen,
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.foto_toko) as foto_toko, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.foto_display_produk) as foto_display_produk, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.foto_branding) as foto_branding, 
                    a.latitude, a.longitude, a.formatted_address, a.city, a.created_at
            from dbrest.gt_market_audit a  
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
            
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;
        
        return $this->db->query($query);

    }

    public function get_gt_join_call($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team_gt($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        $query = "
            select  a.nama_toko, a.result_visit,
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image) as image,  
                    a.username, a.latitude, a.longitude, a.city, a.formatted_address, a.created_at
            from dbrest.gt_market_visit a   
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        return $this->db->query($query);

    }

    public function get_join_call_groupby_user($username = null)
    {
        if ($username) {
            $params = "where a.username in ($username)";
        }else{
            $params = "";
        }
        $query = "
            select a.username, b.id
            from dbrest.gt_market_visit a left join (
                select a.id, a.username
                from site.master_user a 
            ) b on a.username = b.username
            $params
            GROUP BY a.username
        ";
        return $this->db->query($query);
    }

    public function get_realisasi_event_groupby_user($username = null)
    {
        if ($username) {
            $params = "where a.username in ($username)";
        }else{
            $params = "";
        }
        $query = "
            select a.username, b.id
            from dbrest.gt_realisasi_event a left join (
                select a.id, a.username
                from site.master_user a 
            ) b on a.username = b.username
            $params
            GROUP BY a.username
        ";
        return $this->db->query($query);
    }

    public function get_gt_realisasi_event($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team_gt($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        $query = "
            select 	a.nama, a.tanggal, a.lokasi, a.brand, a.kategori, a.audience, 
                    a.target_selling, a.actual_selling, a.achievement,
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_activity1) as image_activity1, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_activity2) as image_activity2, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_branding) as image_branding, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_faktur_penyelesaian) as image_faktur_penyelesaian, 
                    a.username, a.latitude, a.longitude, a.city, a.formatted_address, a.created_at
            from dbrest.gt_realisasi_event a 
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
        ";
        return $this->db->query($query);
    }

    public function get_branding_delto_corner_groupby_user($username = null)
    {
        if ($username) {
            $params = "where a.username in ($username)";
        }else{
            $params = "";
        }
        $query = "
            select a.username, b.id
            from dbrest.gt_branding_delto_corner a left join (
                select a.id, a.username
                from site.master_user a 
            ) b on a.username = b.username
            $params
            GROUP BY a.username
        ";
        return $this->db->query($query);
    }

    public function get_gt_branding_delto_corner($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team_gt($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        // $query = "
        //     select  a.username, a.nama_toko, a.kode_toko, a.brand, 
        //             concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_sticker) as image_sticker, 
        //             concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_door_frame) as image_door_frame, 
        //             concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_spanduk) as image_spanduk, 
        //             concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_coc) as image_coc, 
        //             concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image_hanging_mobile) as image_hanging_mobile, 
        //             a.latitude, a.longitude, a.formatted_address, a.city, a.created_at
        //     from dbrest.gt_branding_delto_corner a
        //     where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
        // ";
        $query = "
            select  a.username, a.nama_toko, a.kode_toko, a.brand, 
                    concat('http://backup.muliaputramandiri.com:81/cisk/assets/file/202507/', a.image_sticker) as image_sticker, 
                    concat('http://backup.muliaputramandiri.com:81/cisk/assets/file/202507/', a.image_door_frame) as image_door_frame, 
                    concat('http://backup.muliaputramandiri.com:81/cisk/assets/file/202507/', a.image_spanduk) as image_spanduk, 
                    concat('http://backup.muliaputramandiri.com:81/cisk/assets/file/202507/', a.image_coc) as image_coc, 
                    concat('http://backup.muliaputramandiri.com:81/cisk/assets/file/202507/', a.image_hanging_mobile) as image_hanging_mobile, 
                    a.latitude, a.longitude, a.formatted_address, a.city, a.created_at
            from dbrest.gt_branding_delto_corner a
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_spreading_groupby_user($username = null)
    {
        if ($username) {
            $params = "where a.username in ($username)";
        }else{
            $params = "";
        }
        $query = "
            select a.username, b.id
            from dbrest.gt_branding_delto_corner a left join (
                select a.id, a.username
                from site.master_user a 
            ) b on a.username = b.username
            $params
            GROUP BY a.username
        ";
        return $this->db->query($query);
    }

    public function get_gt_spreading($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team_gt($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        $query = "
            select 	a.id, a.username, a.id_survei, a.id_transaksi, a.id_images, a.nama_toko, a.keterangan,
                    a.latitude, a.longitude, a.formatted_address, a.city, b.total_value, c.kodeprod,                     
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', d.image_before) as image_before, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', d.image_after) as image_after, a.created_at,
                    CONCAT('[',
                        GROUP_CONCAT(
                            CONCAT(
                                '{\"catatan\":\"', IFNULL(b.catatan, ''), '\",',
                                '\"kodeprod\":\"', IFNULL(b.kodeprod, ''), '\",',
                                '\"namaprod\":\"', IFNULL(b.namaprod, ''), '\",',
                                '\"qty\":', IFNULL(b.qty, 0), ',',
                                '\"price\":', IFNULL(b.price, 0), ',',
                                '\"subtotal\":', IFNULL(b.subtotal, 0), '}'
                            )
                            SEPARATOR ','
                        ),
                    ']') AS transaksi
            from dbrest.gt_spreading a left join (
                select a.id, a.id_tagging, a.total_value, a.catatan, b.kodeprod, b.namaprod, b.qty, b.price, b.subtotal
                from dbrest.gt_spreading_transaksi a left join (
                    select a.id, a.id_spreading_transaksi, a.kodeprod, a.namaprod, a.price, a.qty, a.subtotal
                    from dbrest.gt_spreading_transaksi_products a 
                )b on a.id = b.id_spreading_transaksi
            )b on a.id_transaksi = b.id left join (
                select a.id, a.kodeprod
                from dbrest.gt_spreading_products_survei a
            )c on a.id_survei = c.id left join (
                select a.id, a.image_before, a.image_after
                from dbrest.gt_spreading_images a
            )d on a.id_images = d.id
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
            GROUP BY a.id, a.username            
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_mt_activity_groupby_user($username = null)
    {
        if ($username) {
            $params = "where a.username in ($username)";
        }else{
            $params = "";
        }
        $query = "
            select a.username, b.id
            from dbrest.mt_activity a left join (
                select a.id, a.username
                from site.master_user a 
            ) b on a.username = b.username
            $params
            GROUP BY a.username
        ";
        return $this->db->query($query);
    }

    public function get_mt_activity($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team_mt($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            if(empty($username_team)) $username_team = "'".$this->userid."'";
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        $query = "
            select 	a.username, a.category, a.result_visit, a.result_competitor, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image) as image, 
                    a.toko,
                    a.latitude, a.longitude, a.city, a.formatted_address,
                    a.checkin_time, a.checkout_time, a.keterangan, a.created_at
            from dbrest.mt_activity a 
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
        ";

        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_mpm_market_visit_groupby_user($username = null)
    {

        if ($username) {
            $params = "where a.username in ($username)";
        }else{
            $params = "";
        }

        $query = "
            select a.username, b.id
            from dbrest.mpm_market_visit a left join (
                select a.id, a.username
                from site.master_user a 
            ) b on a.username = b.username
            $params
            GROUP BY a.username
        ";
        return $this->db->query($query);
    }
    
    public function get_mpm_market_visit($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        $query = "
            select 	a.username, a.status_visit, a.subbranch, a.status_ob, a.toko, a.class_toko,
                    a.tipe_toko, a.materi, a.result, a.deadline_followup, a.pic,
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image) as image, 
                    a.nohp, a.keterangan, a.created_at, a.latitude, a.longitude, a.city, a.formatted_address
            from dbrest.mpm_market_visit a   
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
        ";
        // echo "<pre>";
        // echo $query;
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_mpm_activity_groupby_user($username = null)
    {
        if ($username) {
            $params = "where a.username in ($username)";
        }else{
            $params = "";
        }

        $query = "
            select a.username, b.id
            from dbrest.mpm_activity a left join (
                select a.id, a.username
                from site.master_user a 
            ) b on a.username = b.username
            $params
            GROUP BY a.username
        ";
        return $this->db->query($query);
    }

    public function get_mpm_activity($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        $query = "
            select 	a.username, a.type, a.result, a.created_at, a.latitude, a.longitude,
                    a.formatted_address, a.city, 
                    concat('https://hebron.muliaputramandiri.com/assets/file/mpm_apps/', a.image) as image
            from dbrest.mpm_activity a   
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_mpm_summary_activity($from, $to, $user)
    {
        if ($user == 'all') {
            $get_username_team = $this->get_username_team($this->userid);
            $username_team = "";
            foreach ($get_username_team->result_array() as $a) {
                $username_team.= "'".$a['username']."',";
            }
            $username_team = substr($username_team, 0, -1);
            $params = "and a.username in ($username_team)";
        }else{
            $params = "and a.username = '$user'";
        }
        $query = "
            select 	a.username, a.type, count(*) as count
            from dbrest.mpm_activity a   
            where a.deleted_at is null and date(a.created_at) between '$from' and '$to' $params 
            group by a.username, a.type   
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_tanggal_merah()
    {
        $query = "
            select *
            from site.absensi_tanggal_merah a 
            where a.deleted_at is null
        ";
        return $this->db->query($query);
    }

    public function get_tanggal_merah_by_id($id)
    {
        $query = "
            select *
            from site.absensi_tanggal_merah a 
            where a.deleted_at is null and a.id = '$id'
        ";
        return $this->db->query($query);
    }

    public function insert_tanggal_merah($data)
    {
        $this->db->insert('site.absensi_tanggal_merah', $data);
        return $this->db->insert_id();
    }

    public function update_tanggal_merah($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('site.absensi_tanggal_merah', $data);
        return $this->db->affected_rows();
    }

    public function cek_tanggal_merah($tanggal)
    {
        $query = "
            select *
            from site.absensi_tanggal_merah a 
            where a.tanggal = '$tanggal'
        ";
        return $this->db->query($query);
    }

    public function update_absensi_transaksi_by_tanggal($keterangan, $tanggal)
    {
        $this->db->where('tanggal', $tanggal);
        $this->db->update('site.absensi_transaksi', array('keterangan' => $keterangan));
        return $this->db->affected_rows();
    }

    public function get_master_pasar_deltomed()
    {
        $query = "
            select *
            from site.master_pasar_deltomed a
        ";
        return $this->db->query($query);
    }

    public function get_master_region_deltomed()
    {
        $query = "
            select a.region
            from site.master_region_deltomed a 
            GROUP BY a.region
        ";
        return $this->db->query($query);
    }

    public function get_master_provinsi_deltomed($region)
    {
        $query = "
            select a.provinsi
            from site.master_region_deltomed a 
            where a.region = '$region'
            GROUP BY a.provinsi
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_master_kabupaten_deltomed($provinsi)
    {
        $query = "
            select a.kabupaten
            from site.master_region_deltomed a 
            where a.provinsi = '$provinsi'
            GROUP BY a.kabupaten
        ";
        return $this->db->query($query);
    }

    public function get_master_site()
    {
        $query = "
            select a.site_code, a.branch_name, a.nama_comp
            from site.master_site a 
            where a.site_code not like 'MPI%' and a.site_code not like 'PENTA%' and a.branch_name not in ('SURYA DONASIN', 'SUPRALITA MANDIRI')
            order by a.branch_name, a.nama_comp
        ";
        return $this->db->query($query);
    }

    public function get_master_pasar_by_kabupaten($nama_pasar, $kabupaten)
    {
        $query = "
            select *
            from site.master_pasar_deltomed a 
            where a.nama_pasar = '$nama_pasar' and a.kabupaten = '$kabupaten'
        ";
        return $this->db->query($query);
    }

    public function generate_kode_pasar($site_code)
    {
        $site_code = $this->db->escape_str($site_code);
        echo "site_code : ".$site_code;
        
        // Query alternatif jika data tidak dalam format yang diharapkan
        $query = "
            SELECT kode_pasar
            FROM site.master_pasar_deltomed 
            WHERE site_code LIKE '$site_code%'
            ORDER BY site_code DESC
            LIMIT 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;
        
        $result = $this->db->query($query);
        
        if ($result->num_rows() > 0) {
            $row = $result->row();
            $last_kode = $row->kode_pasar;
            
            // Pisahkan kode berdasarkan tanda dash
            $parts = explode('-', $last_kode);

            print_r($parts);


            echo "last_kode : ".$last_kode." - ".count($parts);

            // die;
            
            if (count($parts) == 2) {
                // Jika format benar, ambil bagian angka
                $last_number = intval($parts[1]);
                $new_number = $last_number + 1;
            } else {
                // Jika format tidak sesuai, mulai dari 1
                $new_number = 1;
            }
        } else {
            // Jika tidak ada data, mulai dari 1
            $new_number = 1;
        }
        
        // Format angka menjadi 4 digit dengan leading zeros
        $kode_angka = str_pad($new_number, 4, "0", STR_PAD_LEFT);

        // echo "kode_angka : ".$kode_angka;
        // die;
        
        // Gabungkan dengan site_code
        $kode_baru = $site_code . '-' . $kode_angka;
        
        return $kode_baru;
    }

    public function insert_master_pasar($data)
    {
        $this->db->insert('site.master_pasar_deltomed', $data);
        return $this->db->insert_id();
    }

    public function update_master_pasar($kode_pasar, $data)
    {
        $this->db->where('kode_pasar', $kode_pasar);
        $this->db->update('site.master_pasar_deltomed', $data);
        return $this->db->affected_rows();
        // echo $this->db->last_query();
        // die;
    }

    public function cek_master_pasar($kode_pasar)
    {
        $query = "
            select *
            from site.master_pasar_deltomed a 
            where a.kode_pasar = '$kode_pasar'
        ";
        return $this->db->query($query);
    }


}
