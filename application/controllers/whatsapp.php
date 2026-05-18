<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Whatsapp extends MY_Controller
{
    function whatsapp()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->model(array('m_dc', 'M_menu', 'model_outlet_transaksi', 'model_whatsapp'));
        $this->load->helper('url');
        $this->load->helper('csv');
        // $this->load->model('m_dc');
        // $this->load->model('M_menu');
        // $this->load->model('model_outlet_transaksi');
        $this->load->database();
    }

    public function dashboard()
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Orderan Makanan',
            'get_label' => $this->M_menu->get_label(),
            'get_order'    => $this->model_whatsapp->get_order()->result()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('whatsapp/dashboard', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function tambah_order()
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Tambah Orderan Makanan',
            'get_label' => $this->M_menu->get_label(),
            'get_order' => $this->model_whatsapp->get_order()->result(),
            'get_user'  => $this->model_whatsapp->get_user()->row(),
            'url'       => 'whatsapp/simpan_order'
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('whatsapp/tambah_order', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function simpan_order()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');
        $tanggal = $this->input->post('tanggal');
        $signature = $this->input->post('nama');
        $pesan = $this->input->post('pesan');
        $perkiraan_harga_makanan = $this->input->post('perkiraan_harga_makanan');
        $uang_masuk = $this->input->post('uang_masuk');

        $this->db->where('signature', $signature);
        $nama = $this->db->get('whatsapp.t_user')->row()->nama;

        $this->db->where('signature', $signature);
        $whatsapp = $this->db->get('whatsapp.t_user')->row()->whatsapp;

        $signature = md5($created_at . $nama . $whatsapp);

        //cek_saldo_terakhir 
        $query = "
            select *
            from whatsapp.t_order a
            where a.whatsapp = '$whatsapp'
            order by a.tanggal desc, a.id desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $get_saldo_akhir = $this->db->query($query)->row();
        if ($get_saldo_akhir) {
            $saldo_awal = $get_saldo_akhir->saldo_akhir;
        } else {
            $saldo_awal = 0;
        }

        // echo $saldo_awal;
        // die;

        $data = [
            'tanggal'       => $tanggal,
            'nama'          => $nama,
            'whatsapp'      => $whatsapp,
            'pesan'         => $pesan,
            'perkiraan_harga_makanan' => $perkiraan_harga_makanan,
            'uang_masuk'    => $uang_masuk,
            'saldo_awal'    => $saldo_awal,
            'created_at'    => $created_at,
            'created_by'    => $created_by,
            'signature'     => $signature
        ];

        $input = $this->db->insert('whatsapp.t_order', $data);

        $query = "
            update whatsapp.t_order a 
            set a.sisa = a.saldo_awal + a.uang_masuk - a.uang_keluar,
                a.saldo_akhir = a.saldo_awal + a.uang_masuk - a.uang_keluar - a.dikembalikan
            where a.signature = '$signature'
        ";
        $this->db->query($query);

        redirect('whatsapp/dashboard');
    }

    public function update_order($signature)
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Update Orderan Makanan',
            'get_label' => $this->M_menu->get_label(),
            'get_order' => $this->model_whatsapp->get_order($signature)->row(),
            'get_user'  => $this->model_whatsapp->get_user()->row(),
            'url'       => 'whatsapp/proses_update_order'
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('whatsapp/update_order', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_update_order()
    {
        $updated_at = $this->model_outlet_transaksi->timezone();
        $updated_by = $this->session->userdata('id');

        $harga_makanan = $this->input->post('harga_makanan');
        $uang_keluar = $this->input->post('uang_keluar');
        $dikembalikan = $this->input->post('dikembalikan');
        $signature = $this->input->post('signature');

        $data = [
            'harga_makanan' => $harga_makanan,
            'uang_keluar' => $uang_keluar,
            'dikembalikan' => $dikembalikan,
            'updated_at' => $updated_at,
            'updated_by' => $updated_by,
        ];

        $this->db->where('signature', $signature);
        $this->db->update('whatsapp.t_order', $data);

        $query = "
            update whatsapp.t_order a 
            set a.sisa = a.saldo_awal + a.uang_masuk - a.uang_keluar,
                a.saldo_akhir = a.saldo_awal + a.uang_masuk - a.uang_keluar - a.dikembalikan
            where a.signature = '$signature'
        ";
        $this->db->query($query);

        redirect('whatsapp/update_order/' . $signature);
    }

    public function tambah_user()
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Tambah User',
            'get_label' => $this->M_menu->get_label(),
            'get_user'  => $this->model_whatsapp->get_user()->result(),
            'url'       => 'whatsapp/simpan_user'
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('whatsapp/tambah_user', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function simpan_user()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');
        $nama = $this->input->post('nama');
        $whatsapp = $this->input->post('whatsapp');

        $signature = md5($created_at . $nama . $whatsapp);

        $data = [
            'nama'          => $nama,
            'whatsapp'      => $whatsapp,
            'signature'     => $signature,
            'status_aktif'  => 1,
            'created_at'    => $created_at,
            'created_by'    => $created_by
        ];

        $input = $this->db->insert('whatsapp.t_user', $data);

        redirect('whatsapp/tambah_user');
    }

    public function delete_user($signature)
    {
        $this->db->where('signature', $signature);
        $this->db->delete('whatsapp.t_user');

        redirect('whatsapp/tambah_user');
    }

    public function list_user()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/whatsapp/user?X-API-KEY=123&secret=suffy",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datauser = $array_response['data'];
            echo "<option value=''> -- Pilih User -- </option>";

            foreach ($datauser as $key => $tiapdatauser) {
                echo "<option value='" . $tiapdatauser["signature"] . "' >";
                echo $tiapdatauser["nama"] . " - " . $tiapdatauser["whatsapp"];
                echo "</option>";
            }
        }
    }
}
