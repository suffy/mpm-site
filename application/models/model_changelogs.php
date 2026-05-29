<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_changelogs extends CI_Model 
{

    public function get_menu()
    {
        $query = "
            SELECT id, function_name, menu, active
            FROM site.logs_menu
            ORDER BY menu ASC
        ";

        return $this->db->query($query);
    }
    
    public function insert_changelogs($data)
    {
        // echo '<pre>'; print_r($data); die;
        $this->db->insert('site.changelogs', $data);
        return $this->db->insert_id();
    }

    public function delete_changelogs($signature, $userid)
    {

        $query = "
            update site.changelogs
            set deleted_at = NOW(),
                deleted_by = '$userid'
            WHERE signature = '$signature'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query);
    }

    // public function get_read_history_all($id_user, $id_function = null, $limit = 100)
    // {
    //     $where_function = $id_function ? "AND a.id_function = $id_function" : "";

    //     $query = "
    //         SELECT 
    //             a.id,
    //             a.id_function,
    //             a.title,
    //             a.changes,
    //             DATE(a.created_at) as tanggal,
    //             a.created_at,
    //             b.created_at as dibaca_at,
    //             f.menu as nama_menu,
    //             f.function_name
    //         FROM site.changelogs a
    //         INNER JOIN site.logs_read b
    //             ON b.history_id = a.id
    //             AND b.id_function = a.id_function
    //             AND b.created_by = $id_user
    //         LEFT JOIN site.logs_menu f
    //             ON f.id = a.id_function
    //         WHERE 1=1 $where_function
    //         ORDER BY a.created_at DESC
    //         LIMIT $limit
    //     ";

    //     return $this->db->query($query)->result();
    // }

    // public function get_read_history_all($id_user, $id_function = null, $limit = 100)
    // {
    //     $where_function = $id_function ? "AND cd.id_function = $id_function" : "";

    //     $query = "
    //         SELECT 
    //             a.id,
    //             a.title,
    //             a.changes,
    //             a.foto,
    //             DATE(a.created_at) as tanggal,
    //             a.created_at,
    //             b.created_at as dibaca_at,
    //             GROUP_CONCAT(f.menu SEPARATOR '||') as nama_menu,
    //             GROUP_CONCAT(f.function_name SEPARATOR '||') as function_name,
    //             GROUP_CONCAT(f.id SEPARATOR '||') as id_function
    //         FROM site.changelogs a
    //         INNER JOIN site.logs_read b
    //             ON b.history_id = a.id
    //             AND b.created_by = $id_user
    //         INNER JOIN site.changelogs_detail cd
    //             ON cd.history_id = a.id
    //         LEFT JOIN site.logs_menu f
    //             ON f.id = cd.id_function
    //         WHERE a.deleted_at IS NULL
    //             $where_function
    //         GROUP BY 
    //             a.id, a.title, a.changes, a.foto,
    //             a.created_at, b.created_at
    //         ORDER BY a.created_at DESC
    //         LIMIT $limit
    //     ";

    //     // echo '<pre>'; print_r($query); die;
    //     return $this->db->query($query)->result();
    // }

    public function get_read_history_all($id_user, $id_function = null, $limit = 50)
    {
        $where_function = $id_function ? "AND cd.id_function = $id_function" : "";

        $query = "
            SELECT 
                a.id,
                a.title,
                a.changes,
                a.foto,
                DATE(a.created_at) as tanggal,
                a.created_at,
                b.created_at as dibaca_at,
                CASE WHEN b.history_id IS NOT NULL THEN 1 ELSE 0 END as is_read,
                GROUP_CONCAT(DISTINCT f.menu SEPARATOR '||') as nama_menu,
                GROUP_CONCAT(DISTINCT f.function_name SEPARATOR '||') as function_name,
                GROUP_CONCAT(DISTINCT f.id SEPARATOR '||') as id_function
            FROM site.changelogs a
            LEFT JOIN site.logs_read b
                ON b.history_id = a.id
                AND b.created_by = $id_user
            INNER JOIN site.changelogs_detail cd
                ON cd.history_id = a.id
            LEFT JOIN site.logs_menu f
                ON f.id = cd.id_function
            WHERE a.deleted_at IS NULL and a.status_aktif = 1
                $where_function
            GROUP BY 
                a.id, a.title, a.changes, a.foto,
                a.created_at, b.created_at, b.history_id
            ORDER BY a.created_at DESC
            LIMIT $limit
        ";

        return $this->db->query($query)->result();
    }

    // Ambil semua menu yang pernah dibaca user ini (untuk opsi filter)
    // public function get_read_function_list($id_user)
    // {
    //     $query = "
    //         SELECT DISTINCT 
    //             f.id,
    //             f.menu,
    //             f.function_name,
    //             COUNT(a.id) as total
    //         FROM site.changelogs a
    //         INNER JOIN site.logs_read b
    //             ON b.history_id = a.id
    //             AND b.id_function = a.id_function
    //             AND b.created_by = $id_user
    //         LEFT JOIN site.logs_menu f ON f.id = a.id_function
    //         GROUP BY f.id, f.menu, f.function_name
    //         ORDER BY f.menu ASC
    //     ";

    //     // echo '<pre>'; print_r($query);
    //     return $this->db->query($query)->result();
    // }

    // public function get_read_function_list($id_user)
    // {
        // $query = "
        //     SELECT 
        //         f.id,
        //         f.menu,
        //         f.function_name,
        //         COUNT(DISTINCT a.id) as total
        //     FROM site.changelogs a
        //     INNER JOIN site.logs_read b
        //         ON b.history_id = a.id
        //         AND b.created_by = $id_user
        //     INNER JOIN site.changelogs_detail cd
        //         ON cd.history_id = a.id
        //     LEFT JOIN site.logs_menu f
        //         ON f.id = cd.id_function
        //     WHERE a.deleted_at IS NULL
        //     GROUP BY f.id, f.menu, f.function_name
        //     ORDER BY f.menu ASC
        //  ";

    //     return $this->db->query($query)->result();
    // }

    public function get_read_function_list($id_user)
    {
        $query = "
            SELECT 
                f.id,
                f.menu,
                f.function_name,
                COUNT(DISTINCT a.id) as total,
                SUM(CASE WHEN b.history_id IS NULL THEN 1 ELSE 0 END) as unread
            FROM site.changelogs a
            INNER JOIN site.changelogs_detail cd
                ON cd.history_id = a.id
            LEFT JOIN site.logs_read b
                ON b.history_id = a.id
                AND b.created_by = $id_user
            LEFT JOIN site.logs_menu f
                ON f.id = cd.id_function
            WHERE a.deleted_at IS NULL
                AND a.status_aktif = 1
            GROUP BY f.id, f.menu, f.function_name
            ORDER BY f.menu ASC
        ";

        return $this->db->query($query)->result();
    }
     
    public function get_history_detail($id)
    {
        return $this->db->get_where('site.changelogs', array('id' => $id))->row();
    }

    public function insert_changelogs_detail($history_id, $function_ids)
    {
        foreach ($function_ids as $id_function) {
            $this->db->insert('site.changelogs_detail', array(
                'history_id'  => $history_id,
                'id_function' => $id_function,
            ));
        }
    }

    // public function count_aktif_changelogs()
    // {
    //     $query = "SELECT id_function, COUNT(*) as total FROM site.changelogs WHERE status_aktif = 1 AND deleted_at IS NULL GROUP BY id_function";
    //     $row = $this->db->query($query)->row();
    //     return (int)$row->total;
    // }

    public function count_aktif_per_function($history_id)
    {
        // Ambil id_function dari changelog yang mau diaktifkan
        // lalu cek berapa changelog aktif yang punya function yang sama
        $query = "
            SELECT MAX(cnt) as max_count
            FROM (
                SELECT cd.id_function, COUNT(DISTINCT a.id) as cnt
                FROM site.changelogs a
                INNER JOIN site.changelogs_detail cd ON cd.history_id = a.id
                WHERE a.status_aktif = 1
                    AND a.deleted_at IS NULL
                    AND cd.id_function IN (
                        SELECT id_function 
                        FROM site.changelogs_detail 
                        WHERE history_id = $history_id
                    )
                GROUP BY cd.id_function
            ) as per_function
        ";
        $row = $this->db->query($query)->row();
        return (int)($row ? $row->max_count : 0);
    }

    public function update_changelogs_status($signature, $status)
    {
        $this->db->where('signature', $signature);
        $this->db->update('site.changelogs', array('status_aktif' => $status));
        // print_r($this->db->last_query()); die;
    }

// Update get_all_history — join ke pivot untuk ambil nama menu
    // public function get_all_changelogs()
    // {
    //     $query = "
    //         SELECT
    //             a.id, a.title, a.changes, a.created_at, a.status_aktif,
    //             c.username,
    //             GROUP_CONCAT(f.menu SEPARATOR '||') as menus
    //         FROM site.changelogs a
    //         LEFT JOIN site.master_user c ON a.created_by = c.id
    //         LEFT JOIN site.changelogs_detail hlf ON hlf.history_id = a.id
    //         LEFT JOIN site.logs_menu f ON f.id = hlf.id_function
    //         WHERE a.deleted_at IS NULL
    //         GROUP BY a.id, a.title, a.changes, a.created_at, a.status_aktif, c.username
    //         ORDER BY a.created_at DESC
    //     ";
    //     return $this->db->query($query);
    // }

    public function get_all_changelogs()
    {
        $query = "
            SELECT
                a.id, a.title, a.changes, a.foto,
                a.created_at, a.status_aktif,
                c.username,
                GROUP_CONCAT(DISTINCT f.menu    SEPARATOR '||') as menus,
                GROUP_CONCAT(DISTINCT cd.id_function SEPARATOR ',')  as fn_ids, 
                a.signature
            FROM site.changelogs a
            LEFT JOIN site.master_user c ON a.created_by = c.id
            LEFT JOIN site.changelogs_detail cd ON cd.history_id = a.id
            LEFT JOIN site.logs_menu f ON f.id = cd.id_function
            WHERE a.deleted_at IS NULL
            GROUP BY a.id, a.title, a.changes, a.foto, a.created_at, a.status_aktif, c.username
            ORDER BY a.id DESC
        ";
        return $this->db->query($query);
    }

    public function get_aktif_per_function()
    {
        $query = "
            SELECT cd.id_function, COUNT(DISTINCT a.id) as total
            FROM site.changelogs a
            INNER JOIN site.changelogs_detail cd ON cd.history_id = a.id
            WHERE a.status_aktif = 1 AND a.deleted_at IS NULL
            GROUP BY cd.id_function
        ";
        $rows   = $this->db->query($query)->result();
        $result = array();
        foreach ($rows as $r) {
            $result[$r->id_function] = (int)$r->total;
        }
        return $result; // array: [id_function => count]
    }

    public function get_id_function_by_signature($signature)
    {
        $query = "
            SELECT *
            FROM site.changelogs a
            INNER JOIN site.changelogs_detail cd ON cd.history_id = a.id
            WHERE a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function mark_single_read($id_user, $history_id, $id_function = null)
    {
        // Cek dulu jangan sampai duplikat
        $cek = $this->db->get_where('site.logs_read', array(
            'history_id' => $history_id,
            'created_by' => $id_user,
        ))->row();

        if (empty($cek)) {
            $this->db->insert('site.logs_read', array(
            'id_function' => $id_function,
            'history_id' => $history_id,
            'created_by' => $id_user,
            'created_at' => date('Y-m-d H:i:s'),
            ));
        }
        return true;
    }

    // public function mark_single_read($id_user, $history_id, $id_function = null)
    // {

    //     // echo "Marking history_id $history_id as read for user $id_user and function $id_function"; // Debug log
    //     // die;
    //     // cek existing
    //     $cek = $this->db->get_where('site.logs_read', array(
    //         'history_id' => $history_id,
    //         'created_by' => $id_user,
    //     ))->row();

    //     if (empty($cek)) {

    //         // insert parent dulu
    //         $this->db->insert('site.logs_read', array(
    //             'history_id' => $history_id,
    //             'created_by' => $id_user,
    //             'created_at' => date('Y-m-d H:i:s'),
    //         ));

    //         $logs_read_id = $this->db->insert_id();

    //         // ✅ pecah id_function
    //         $functions = preg_split('/[|,]+/', $id_function);

    //         foreach ($functions as $fn) {
    //             $fn = trim($fn);
    //             if ($fn != '') {
    //                 $this->db->insert('site.logs_read_function', array(
    //                     'logs_read_id' => $logs_read_id,
    //                     'id_function'  => $fn
    //                 ));
    //             }
    //         }
    //     }

    //     return true;
    // }

    public function check_is_read($id_user, $history_id)
    {
        $row = $this->db->get_where('site.logs_read', array(
            'history_id' => $history_id,
            'created_by' => $id_user,
        ))->row();
        return !empty($row) ? 1 : 0;
    }

    public function get_readers_by_history($history_id)
    {
        $query = "
            SELECT 
                b.username,
                a.created_at
            FROM site.logs_read a
            LEFT JOIN site.master_user b
                ON a.created_by = b.id
            WHERE a.history_id = $history_id
            ORDER BY a.created_at DESC
        ";

        return $this->db->query($query);
    }

    public function reset_read_log($history_id)
    {
        return $this->db->where('history_id', $history_id)->delete('logs_read');
    }

    public function get_changelog_by_signature($signature)
    {
        $query = "
            SELECT 
                a.id, a.title, a.changes, a.foto, a.created_at,
                a.status_aktif, a.signature,
                c.username,
                GROUP_CONCAT(DISTINCT f.menu SEPARATOR '||') as menus
            FROM site.changelogs a
            LEFT JOIN site.master_user c ON a.created_by = c.id
            LEFT JOIN site.changelogs_detail cd ON cd.history_id = a.id
            LEFT JOIN site.logs_menu f ON f.id = cd.id_function
            WHERE a.signature = '$signature' AND a.deleted_at IS NULL
            GROUP BY a.id, a.title, a.changes, a.foto,
                    a.created_at, a.status_aktif, a.signature, c.username
        ";
        return $this->db->query($query)->row();
    }

    public function get_readers_by_history_id($history_id)
    {
        $query = "
            SELECT 
                u.username, u.name,
                b.created_at as dibaca_at
            FROM site.logs_read b
            LEFT JOIN site.master_user u ON u.id = b.created_by
            WHERE b.history_id = $history_id
            ORDER BY b.created_at DESC
        ";
        return $this->db->query($query)->result();
    }

    public function delete_logs_read_by_history($history_id)
    {
        $this->db->where('history_id', $history_id);
        $this->db->delete('site.logs_read');
    }

    // ===================================================== model untuk tampilan di menu2 popup =============================================
    public function get_unread_history($id_user, $id_function)
    {
        $query = "
            SELECT 
                a.id,
                a.title,
                a.changes,
                a.foto,
                DATE(a.created_at) as tanggal,
                a.created_at
            FROM site.changelogs a
            INNER JOIN site.changelogs_detail cd
                ON cd.history_id = a.id
                AND cd.id_function = $id_function
            LEFT JOIN site.logs_read b
                ON b.history_id = a.id
                AND b.created_by = $id_user
            WHERE a.deleted_at IS NULL
                AND a.status_aktif = 1
                AND b.history_id IS NULL
            ORDER BY a.created_at DESC
            LIMIT 5
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";die;
        return $this->db->query($query)->result();
    }

    public function save_multiple_read($id_user, $ids)
    {

        foreach ($ids as $id) {
            $this->db->insert('site.logs_read', [
                // 'id_function' => $id_function,
                'created_by' => $id_user,
                'history_id' => $id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // var_dump($this->db->affected_rows().' rows inserted'); // Debug log
        // die;
        // return true;
    }

    // Semua history yang sudah dibaca user ini
    public function get_read_history($id_user, $id_function, $limit = 50)
    {
        $query = "
            SELECT 
                a.id,
                a.title,
                a.changes,
                DATE(a.created_at) as tanggal,
                a.created_at,
                b.created_at as dibaca_at
            FROM site.changelogs a
            INNER JOIN site.logs_read b
                ON b.history_id = a.id
                AND b.id_function = a.id_function
                AND b.created_by = $id_user
            WHERE a.id_function = $id_function
            ORDER BY a.created_at DESC
            LIMIT $limit
        ";

        return $this->db->query($query)->result();
    }

    // Detail satu item (untuk modal/popup detail)
    // public function get_history_detail($id)
    // {
    //     return $this->db->get_where('site.changelogs', ['id' => $id])->row();
    // }

    public function get_all_unread_ids($id_user, $id_function)
    {
        $query = "
            SELECT a.id
            FROM site.changelogs a
            LEFT JOIN site.logs_read b
                ON b.id_function = a.id_function
                AND b.history_id = a.id
                AND b.created_by = $id_user
            WHERE a.id_function = $id_function
                AND b.history_id IS NULL
                AND a.deleted_at IS NULL
            ORDER BY a.created_at DESC
        ";
        return $this->db->query($query)->result();
    }

    public function get_redirect_by_id($id_function)
    {
        $query = "
            SELECT redirect
            FROM site.logs_menu
            WHERE id = $id_function
        ";

        $row = $this->db->query($query)->row();
        return $row ? $row->redirect : null;
    }

    //============================================================== end model untuk tampilan di menu2 popup==================================

}