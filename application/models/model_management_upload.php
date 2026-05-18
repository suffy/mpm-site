<?php
defined('BASEPATH') or exit('No direct script access allowed');

class model_management_upload extends CI_Model
{
    public function get_temp_portal_akses()
    {
        $sql = "
            SELECT *
            FROM site.temp_portal_akses a
            ORDER BY a.id DESC
        "; 
        return $this->db->query($sql);
    }
    public function get_dataUpload($id)
    {
        $query = "
            SELECT a.*, b.username FROM mpm.upload a
            LEFT JOIN mpm.user b on a.userid = b.id
            WHERE a.userid = $id and a.status = 1
            ORDER BY a.id DESC
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }
    
    public function get_dataUpload_all_status($id)
    {
        $query = "
            SELECT a.*, b.username FROM mpm.upload a
            LEFT JOIN mpm.user b on a.userid = b.id
            WHERE a.userid = $id
            ORDER BY a.id DESC
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_dataUpload_status_closing($data)
    {
        $userid = $data["userid"];
        $tanggal = $data['bulan'].$data['tahun'];

        $query = "
            SELECT a.*, b.username FROM mpm.upload a
            LEFT JOIN mpm.user b on a.userid = b.id
            WHERE a.userid = $userid and concat(a.bulan, a.tahun) = '$tanggal' and a.status = 1 and a.status_closing = 1
            ORDER BY a.id DESC
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_dataUpload_status_closing_before($data, $batas)
    {
        $userid = $data["userid"];
        $tanggal = $batas['bulan'].$batas['tahun'];

        $query = "
            SELECT a.*, b.username FROM mpm.upload a
            LEFT JOIN mpm.user b on a.userid = b.id
            WHERE a.userid = $userid and concat(a.bulan, a.tahun) = '$tanggal' and a.status = 1 and a.status_closing = 1
            ORDER BY a.id DESC
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_tbl_tabcomp($username, $nocab)
    {
        $query = "
            select a.kode_comp, a.nocab
            from mpm.tbl_tabcomp a
            where a.kode_comp ='$username' and a.nocab = '$nocab' and a.active = 1
        ";
                
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function proses_insert_db_upload($data)
    {

        $nocab = substr($data['filename'], 2, 2);
        $tanggal = $data['tanggal'];
        $year = substr($data['tahun'], 2, 2);
        $month = $data['bulan'];
        $lastupload = $data['lastupload'];
        $userid = $this->session->userdata('id');
        $kode_comp = $this->session->userdata('username');

        // echo "nocab : ".$nocab;

        set_time_limit(0);
        ini_set('mysql.connect_timeout', 3000);
        ini_set('default_socket_timeout', 3000);
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $ddl1 = array('fi', 'ri', 'st', 'ekspedisi');
        $ddl2 = array('tblang', 'tbkota');
        $ddl3 = array('tabsupp', 'tabsales', 'tabsalur', 'tabtype', 'tbrayon', 'tabgrupp');
        $load = "LOAD DATA INFILE 'C:/xampp/htdocs/cisk/assets/uploads/unzip/" . $nocab . "/";
        foreach ($ddl1 as $ddl) {
            $fields = $this->db->field_data("db_upload." . $ddl);
            $name = '(';
            $set = ',';
            $i = 1;
            foreach ($fields as $field) {
                if ($field->type == 'date') {
                    $name .= '@date' . $i . ',';
                    $set .= $field->name . '=' . 'str_to_date(@date' . $i . ',"%d-%m-%Y"),';
                    $i++;
                } else {
                    $name .= $field->name . ", ";
                }
            }
            $name = substr($name, 0, -2);
            $name .= ')';
            $set  = substr($set, 0, -1);
            $file = './assets/uploads/unzip/' . $nocab . '/' . strtoupper( $ddl) . $nocab . $year . $month . '.TXT';
            if (file_exists($file)) {
                if ($ddl == 'st' || $ddl == 'ekspedisi') {
                    $sql_del = "delete from db_upload.$ddl where nocab='$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . $year . $month . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET NOCAB='$nocab', BULAN='$year$month' " . $set;
                    $this->db->query($sql);
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';
                } else {
                    $sql_del = "delete from db_upload.$ddl where nocab='$nocab' and kode_comp not in ('ll1','ll2','ll3')";
                    $this->db->query($sql_del); $sql = $load . strtoupper($ddl) . $nocab . $year . $month . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET NOCAB='" . $nocab . "', BULAN='" . $month . "' " . $set;
                    $this->db->query($sql);
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';
                }
            } 
        }

        foreach ($ddl2 as $ddl) {
            $fields = $this->db->field_data("db_upload." . $ddl);
            $name = '(';
            $set = ',';
            $i = 1;
            foreach ($fields as $field) {
                if ($field->type == 'date') {
                    $name .= '@date' . $i . ',';
                    $set .= $field->name . '=' . 'str_to_date(@date' . $i . ',"%d-%m-%Y"),';
                    $i++;
                } else {
                    $name .= $field->name . ", ";
                }
            }
            $name = substr($name, 0, -2);
            $name .= ')';
            $set  = substr($set, 0, -1);
            $file = './assets/uploads/unzip/' . $nocab . '/' . strtoupper($ddl) . $nocab . '.TXT';
            if (is_file($file)) {
                    $sql_del = "delete from db_upload.$ddl where nocab = '$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET NOCAB='$nocab' " . $set;
                    $proses = $this->db->query($sql);
            }
        }
        
        $sql_del = "delete from db_upload.tblang where nocab='$nocab' and kode_lang =''";
        $this->db->query($sql_del);
        foreach ($ddl3 as $ddl) {
            $fields = $this->db->field_data("db_upload." . $ddl);
            $name = '(';
            $set = ',';
            $i = 1;
            foreach ($fields as $field) {
                if ($field->type == 'date') {
                    $name .= '@date' . $i . ',';
                    $set .= $field->name . '=' . 'str_to_date(@date' . $i . ',"%d-%m-%Y"),';
                    $i++;
                } else {
                    $name .= $field->name . ", ";
                }
            }
            $name = substr($name, 0, -2);
            $name .= ')';
            $set  = substr($set, 0, -1);
            $file = './assets/uploads/unzip/' . $nocab . '/' . strtoupper($ddl) . '.TXT';
            if (is_file($file)) {
                $sql_del = "delete from db_upload.$ddl where nocab='$nocab'";
                $this->db->query($sql_del);
                $sql = $load . strtoupper($ddl) . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET NOCAB='$nocab' " . $set;
                $this->db->query($sql);
            } else {
            }
        }

        $file = './assets/uploads/unzip/' . $nocab . '/TBPROD.TXT';
        if (is_file($file)) {
            $sql_del = "delete from db_upload.tabprod where nocab='$nocab'";
            $this->db->query($sql_del);
            $sql = $load . "TBPROD.TXT' INTO TABLE db_upload.tabprod FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB='$nocab'";
            $this->db->query($sql);
        } else {
            $msg[] = 'tbprod~1.TXT' . ' not found<BR />';
        }

        $file = './assets/uploads/unzip/' . $nocab . '/TBRAYO~1.TXT';
        if (is_file($file)) {
            $sql_del = "delete from db_upload.tbrayon where nocab='$nocab'";
            $this->db->query($sql_del);
            $sql = $load . "TBRAYO~1.TXT' INTO TABLE db_upload.tbrayon FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB='$nocab'";
            $this->db->query($sql);
        } else {
            $msg[] = 'tbrayo~1.TXT' . ' not found<BR />';
        }

        $file = './assets/uploads/unzip/' . $nocab . '/TBRAYO~1.TXT';
        if (is_file($file)) {
            $sql_del = "delete from db_upload.tbrayon where nocab='$nocab'";
            $this->db->query($sql_del);
            $sql = $load . "TBRAYO~1.TXT' INTO TABLE db_upload.tbrayon FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB='$nocab'";
            $this->db->query($sql);
        } else {
            $msg[] = 'tbrayo~1.TXT' . ' not found<BR />';
        }

        // update tgl gto
        if ($kode_comp == 'GTO') {
            # code...
            $update = "
            update db_upload.fi a
            set a.TGLDOKJDI = REPLACE(a.tgldokjdi,'/','-')
            where kode_comp ='$kode_comp'
            ";
            $this->db->query($update);

            $update_ri = "
            update db_upload.ri a
            set a.TGLDOKJDI = REPLACE(a.tgldokjdi,'/','-')
            where kode_comp ='$kode_comp'
            ";
            $this->db->query($update_ri);
        }
    }

    public function total_omzet($data)
    {
        
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];
        $nocab = substr($data['filename'], 2, 2);

        $sql = "
            select format(sum(val),2) as omzet
            from
            (
                select sum(tot1) val from db_upload.fi where BLNDOK = $bulan and THNDOK = $tahun and nocab = '$nocab'
                union ALL
                select sum(tot1) val from db_upload.ri where BLNDOK = $bulan and THNDOK = $tahun and nocab = '$nocab'
            )a
        ";
        return $this->db->query($sql);
    }

    public function submitOmzet($data)
    {
        //$prosesData = $this->load->database('prosesData',TRUE);
        $userid = $data->row()->userid;
        $nocab = substr($data->row()->filename, 2, 2);
        $tahun = $data->row()->tahun;
        $tahun_stock = substr($tahun, 2, 2);
        $bulan = $data->row()->bulan;
        $tanggal = $data->row()->tanggal;

        // =================== INSERT PORTAL START ====================
        $sql = "
            INSERT INTO site.temp_portal_akses
            SELECT '' as id, 'upload_file' as proses, 1, $userid, now();
        ";
        $proses_portal = $this->db->query($sql);
        // ======================================================

        // ==================== INSERT DATA ====================
        $sql = "
            delete from data$tahun.fi
            where bulan = $bulan and nocab = '$nocab'
        ";
        $proses_fi_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.fi
            select * from db_upload.fi
            where bulan = $bulan and nocab = '$nocab'
        ";
        $proses_fi = $this->db->query($sql);
        $sql = "
            delete from data$tahun.ri
            where bulan = $bulan and nocab = '$nocab'
        ";

        $proses_ri_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.ri
            select * from db_upload.ri
            where bulan = $bulan and nocab = '$nocab'
        ";
        $proses_ri = $this->db->query($sql);
        $sql = "
            delete from data$tahun.st
            where bulan = $tahun_stock$bulan and nocab = '$nocab'
        ";

        $proses_st_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.st
            select * from db_upload.st
            where bulan = $tahun_stock$bulan and nocab = '$nocab'
        ";

        $proses_st = $this->db->query($sql);
        $sql = "
            delete from data$tahun.st_detail
            where bulan = $tahun_stock$bulan and nocab = '$nocab' and tanggal = $tanggal
        ";
        $proses_st_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.st_detail
            select * from db_upload.st_detail
            where bulan = $tahun_stock$bulan and nocab = '$nocab' and tanggal = $tanggal
        ";

        $proses_st = $this->db->query($sql);
        $sql = "
            delete from data$tahun.tabsales
            where nocab = '$nocab'
        ";
        $proses_tabsales_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.tabsales
            select * from db_upload.tabsales
            where nocab = '$nocab'
        ";
        $proses_tabsales = $this->db->query($sql);

        $sql = "
            delete from data$tahun.tabtype
            where nocab = '$nocab'
        ";
        $proses_tabtype_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.tabtype
            select * from db_upload.tabtype
            where nocab = '$nocab'
        ";
        $proses_tabtype = $this->db->query($sql);

        $sql = "
            delete from data$tahun.tblang
            where nocab = '$nocab'
        ";
        $proses_tblang_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.tblang
            select * from db_upload.tblang
            where nocab = '$nocab'
        ";

        $proses_tblang = $this->db->query($sql);
        $sql = "
            delete from data$tahun.tbrayon
            where nocab = '$nocab'
        ";
        $proses_tbrayon_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.tbrayon
            select * from db_upload.tbrayon
            where nocab = '$nocab'
        ";

        $proses_tbrayon = $this->db->query($sql);

        $sql = "
            delete from data$tahun.tbkota
            where nocab = '$nocab'
        ";
        $proses_tbkota_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.tbkota
            select * from db_upload.tbkota
            where nocab = '$nocab'
        ";
        $proses_tbkota = $this->db->query($sql);

        $sql = "
            delete from data$tahun.ekspedisi
            where bulan = $tahun_stock$bulan and nocab = '$nocab'
        ";
        $proses_tbkota_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.ekspedisi
            select * from db_upload.ekspedisi
            where bulan = $tahun_stock$bulan and nocab = '$nocab'
        ";
        $proses_tbkota = $this->db->query($sql);


        // ======================================================
        
        // =================== INSERT PORTAL FINISH ====================
        $sql = "
            INSERT INTO site.temp_portal_akses
            SELECT '' as id, 'Upload File' as proses, 0, $userid, now();
        ";
        $proses_portal = $this->db->query($sql);
        // ======================================================
    }
}