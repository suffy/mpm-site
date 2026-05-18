<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Spk extends MY_Controller
{
    function spk()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_spk', 'model_master_data', 'model_inventory', 'model_management_sales'));
        $this->email_tim = 'tria@muliaputramandiri.com';
        $this->created_at = $this->model_outlet_transaksi->timezone();
        $this->userid = $this->session->userdata('id');

        // if ($this->session->userdata('username') != "suffy") {
        //     echo "<script>alert('maaf sedang maintenance'); </script>";
        //     redirect('login_sistem/','refresh');
        // }

    }

    function index()
    {
        $this->form_pesanan();
    }

    public function dashboard()
    {
        $pilih_bulan = $this->input->post('bulan');

        $tahun = substr($pilih_bulan,0,4);
        $bulan = substr($pilih_bulan,5,2);

        if ($tahun) {
            $params_tahun = $tahun;
        }else{
            $params_tahun = 0;
        }

        if ($bulan > 0) {
            $params_bulan = $bulan;
        }else{
            $params_bulan = 0;
        }


        $get_total_po = $this->model_spk->get_total_po($params_tahun, $params_bulan);
        if ($get_total_po->num_rows() > 0) {
            $total_value = $get_total_po->row()->total_value;
            $count_po = $get_total_po->row()->count_po;
        }

        $get_total_po_deltomed = $this->model_spk->get_total_po($params_tahun, $params_bulan, "001");
        if ($get_total_po_deltomed->num_rows() > 0) {
            $total_value_deltomed = $get_total_po_deltomed->row()->total_value;
            $count_po_deltomed = $get_total_po_deltomed->row()->count_po;
        }

        $get_data = $this->model_spk->get_total_po_groupby_supp($params_tahun, $params_bulan);
        // if ($get_data->num_rows() > 0) {
        //     $data = $get_data->result();
        // }

        $supp = $this->input->post('supp');
        $data = [
            'title'     => 'Dashboard',
            'url'       => 'spk/dashboard',
            'total_value'   => $total_value,
            'count_po'      => $count_po,
            'total_value_deltomed'   => $total_value_deltomed,
            'count_po_deltomed'      => $count_po_deltomed,
            'pilih_bulan' => $pilih_bulan,
            'get_data'      => $get_data
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    function navbar($data){
        if ($this->session->userdata('level') === '4') { // jika dp
            $this->load->view('management_office/top_header_dp', $data);
        }elseif ($this->session->userdata('level') === '3') { // jika principal
            $this->load->view('management_office/top_header_principal', $data);
        }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales 
            $this->load->view('management_office/top_header_principal_nosales', $data);
        }elseif ($this->session->userdata('level') === "3b") { // jika principal hanya raw data, claim, rpd 
            $this->load->view('management_office/top_header_principal_rawdata', $data);
        }elseif ($this->session->userdata('level') === "3c") { // jika principal raw_data dan retur dan rpd = RSPH = ghozali yoseph sudarsono
            $this->load->view('management_office/top_header_principal_rawdata_retur', $data);
        }elseif ($this->session->userdata('level') === "3d") { // jika principal rpd
            $this->load->view('management_office/top_header_principal_rpd', $data);
        }elseif ($this->session->userdata('level') === '5') { // jika dp mpi
            $this->load->view('management_office/top_header_dp_mpi', $data);
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function form_pesanan()
    {
        $supp = $this->input->post('supp');
        $data = [
            'title'     => 'Form SPK',
            'url_search_produk'   => 'spk/form_pesanan',
            'url_tambah_produk'   => 'spk/form_pesanan_proses',
            'get_produk'    => $this->model_spk->get_produk_by_supp($supp),
            'get_namasupp'  => $this->model_master_data->get_namasupp_by_supp(),
            'supp'      => $supp
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/form_pesanan', $data);
        $this->load->view('kalimantan/footer');
    }

    public function form_alokasi()
    {
        $supp = $this->input->post('supp');
        $data = [
            'title'             => 'Form Alokasi',
            'url_search_produk' => 'spk/form_alokasi',
            'url_tambah_produk' => 'spk/form_alokasi_proses',
            'get_produk'        => $this->model_spk->get_produk_by_supp($supp),
            'get_namasupp'      => $this->model_master_data->get_namasupp_by_supp(),
            'supp'              => $supp
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/form_alokasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function form_pesanan_proses()
    {
        $id = $this->input->post('options');
        $supp = $this->input->post('supp');
        $kodeprod = $this->input->post('kodeprod');
        $jml_karton = $this->input->post('jml_karton');
        $count = count($id);
        $count_jml_karton = count($jml_karton);
        $signature = 'spk-' . rand() . md5($this->created_at) . date('Ymd');

        // echo "signature : ".$signature;
        // echo "supp : ".$supp;
        // echo "kodeprod : ".$kodeprod;
        // var_dump($supp);
        // echo "<hr>";
        // var_dump($kodeprod);
        // die;

        // get site_code by username
        $get_tabcomp_by_kode_comp = $this->model_master_data->get_tabcomp_by_kode_comp($this->session->userdata('username'));
        if (!$get_tabcomp_by_kode_comp->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "user anda tidak terdaftar");
            redirect('spk', 'refresh');
        }else{
            $site_code = $get_tabcomp_by_kode_comp->row()->site_code;
        }

        // cek apakah sudah ada di keranjang belanja yang flag_selesai is null
        $get_keranjang_belanja_by_userid = $this->model_spk->get_temp_spk_join_temp_spk_detail_by_userid($this->session->userdata('id'));
        if ($get_keranjang_belanja_by_userid->num_rows() > 0)
        {
            $id_header = $get_keranjang_belanja_by_userid->row()->id;
        }
        else
        {
            $data = [
                "site_code"     => $site_code,
                "created_at"    => $this->created_at,
                "created_by"    => $this->session->userdata('id'),
                "signature"     => $signature
            ];
            $proses = $this->db->insert('site.temp_spk', $data);
            $id_header = $this->db->insert_id();
        }

        for ($i=0; $i < $count ; $i++)
        {
            $signature_detail = 'spk-detail-' . rand() . md5($this->created_at) . date('Ymd');
            $params_id = $id[$i];

            // cek apakah sudah ada di keranjang belanja
            $get_keranjang_belanja_detail_by_id_header_and_kodeprod = $this->model_spk->get_temp_spk_detail_by_id_header_and_kodeprod($id_header, $kodeprod[$params_id]);

            // cek kodeproduk exists
            if ($get_keranjang_belanja_detail_by_id_header_and_kodeprod->num_rows() > 0)
            {
                $kodeprod_keranjang = $get_keranjang_belanja_detail_by_id_header_and_kodeprod->row()->kodeprod;
                $jumlah_karton_keranjang = $get_keranjang_belanja_detail_by_id_header_and_kodeprod->row()->jml_karton;
                $id_detail = $get_keranjang_belanja_detail_by_id_header_and_kodeprod->row()->id;
                if ($kodeprod_keranjang === $kodeprod[$params_id])
                {
                    $supp = $this->model_spk->get_produk_by_kodeprod($kodeprod[$params_id])->row()->supp;
                    $jumlah_karton = $jumlah_karton_keranjang + $jml_karton[$params_id];
                    $data = [
                        "supp"          => $supp,
                        "kodeprod"      => $kodeprod[$params_id],
                        "jml_karton"    => $jumlah_karton,
                        "created_at"    => $this->created_at,
                        "created_by"    => $this->session->userdata('id'),
                        "signature"     => $signature_detail
                    ];
                    $this->db->where('id_header', $id_header);
                    $this->db->where('kodeprod', $kodeprod_keranjang);
                    $this->db->update('site.temp_spk_detail', $data);

                    // jalankan update master berat dan master volume
                    $update = $this->model_spk->update_master_berat_volume_in_keranjang_belanja_by_id_and_kodeprod($id_detail, $kodeprod[$params_id]);

                }
                else
                {
                    $supp = $this->model_spk->get_produk_by_kodeprod($kodeprod[$params_id])->row()->supp;

                    $jumlah_karton = $jml_karton[$params_id];
                    $data = [
                        "id_header"     => $id_header,
                        "supp"          => $supp,
                        "kodeprod"      => $kodeprod[$params_id],
                        "jml_karton"    => $jml_karton[$params_id],
                        "created_at"    => $this->created_at,
                        "created_by"    => $this->session->userdata('id'),
                        "signature"     => $signature_detail
                    ];
                    $this->db->insert('site.temp_spk_detail', $data);

                    // jalankan update master berat dan master volume
                    $update = $this->model_spk->update_master_berat_volume_in_keranjang_belanja_by_id_and_kodeprod($id_detail, $kodeprod[$params_id]);

                }
            }else
            {
                $supp = $this->model_spk->get_produk_by_kodeprod($kodeprod[$params_id])->row()->supp;

                $jumlah_karton = $jml_karton[$params_id];
                $data = [
                    "id_header"     => $id_header,
                    "supp"          => $supp,
                    "kodeprod"      => $kodeprod[$params_id],
                    "jml_karton"    => $jml_karton[$params_id],
                    "created_at"    => $this->created_at,
                    "created_by"    => $this->session->userdata('id'),
                    "signature"     => $signature_detail
                ];

                $this->db->insert('site.temp_spk_detail', $data);
                $id_detail = $this->db->insert_id();

                // jalankan update master berat dan master volume
                $update = $this->model_spk->update_master_berat_volume_in_keranjang_belanja_by_id_and_kodeprod($id_detail, $kodeprod[$params_id]);
            }
        }

        $this->session->set_flashdata("pesan_success", "Berhasil menambahkan produk");
        redirect('spk/keranjang_belanja/');
        die;
    }

    public function form_alokasi_proses()
    {
        $id = $this->input->post('options');
        $supp = $this->input->post('supp');
        $kodeprod = $this->input->post('kodeprod');
        $jml_karton = $this->input->post('jml_karton');
        $count = count($id);
        $count_jml_karton = count($jml_karton);
        $signature = 'alokasi-' . rand() . md5($this->created_at) . date('Ymd');


        // cek apakah sudah ada di keranjang belanja yang flag_selesai is null
        $get_temp_alokasi_by_userid = $this->model_spk->get_temp_alokasi_by_userid($this->userid);
        if ($get_temp_alokasi_by_userid->num_rows() > 0)
        {
            $id_header = $get_temp_alokasi_by_userid->row()->id;
        }
        else
        {
            $data = [
                "site_code"     => $site_code,
                "created_at"    => $this->created_at,
                "created_by"    => $this->session->userdata('id'),
                "signature"     => $signature
            ];
            $proses = $this->db->insert('site.temp_alokasi', $data);
            $id_header = $this->db->insert_id();
        }

        for ($i=0; $i < $count ; $i++)
        {
            $signature_detail = 'alokasi-detail-' . rand() . md5($this->created_at) . date('Ymd');
            $params_id = $id[$i];

            // cek apakah sudah ada di keranjang belanja
            $get_keranjang_alokasi_detail_by_id_header_and_kodeprod = $this->model_spk->get_keranjang_alokasi_detail_by_id_header_and_kodeprod($id_header, $kodeprod[$params_id]);

            // cek kodeproduk exists
            if ($get_keranjang_alokasi_detail_by_id_header_and_kodeprod->num_rows() > 0)
            {
                $kodeprod_keranjang = $get_keranjang_alokasi_detail_by_id_header_and_kodeprod->row()->kodeprod;
                $jumlah_karton_keranjang = $get_keranjang_alokasi_detail_by_id_header_and_kodeprod->row()->jml_karton;
                if ($kodeprod_keranjang === $kodeprod[$params_id])
                {
                    $jumlah_karton = $jumlah_karton_keranjang + $jml_karton[$params_id];
                    $data = [
                        "supp"          => $supp,
                        "kodeprod"      => $kodeprod[$params_id],
                        "jml_karton"    => $jumlah_karton,
                        "created_at"    => $this->created_at,
                        "created_by"    => $this->session->userdata('id'),
                        "signature"     => $signature_detail
                    ];
                    $this->db->where('id_header', $id_header);
                    $this->db->where('kodeprod', $kodeprod_keranjang);
                    $this->db->update('site.temp_alokasi_detail', $data);

                    // jalankan update master berat dan master volume
                    $update = $this->model_spk->update_master_berat_volume_in_keranjang_alokasi_by_id_and_kodeprod($id_detail, $kodeprod[$params_id]);

                }
                else
                {
                    $jumlah_karton = $jml_karton[$params_id];
                    $data = [
                        "id_header"     => $id_header,
                        "supp"          => $supp,
                        "kodeprod"      => $kodeprod[$params_id],
                        "jml_karton"    => $jml_karton[$params_id],
                        "created_at"    => $this->created_at,
                        "created_by"    => $this->session->userdata('id'),
                        "signature"     => $signature_detail
                    ];
                    $this->db->insert('site.temp_alokasi_detail', $data);

                    // jalankan update master berat dan master volume
                    $update = $this->model_spk->update_master_berat_volume_in_keranjang_alokasi_by_id_and_kodeprod($id_detail, $kodeprod[$params_id]);

                }
            }else
            {
                $jumlah_karton = $jml_karton[$params_id];
                $data = [
                    "id_header"     => $id_header,
                    "supp"          => $supp,
                    "kodeprod"      => $kodeprod[$params_id],
                    "jml_karton"    => $jml_karton[$params_id],
                    "created_at"    => $this->created_at,
                    "created_by"    => $this->session->userdata('id'),
                    "signature"     => $signature_detail
                ];

                $this->db->insert('site.temp_alokasi_detail', $data);
                $id_detail = $this->db->insert_id();

                // jalankan update master berat dan master volume
                $update = $this->model_spk->update_master_berat_volume_in_keranjang_alokasi_by_id_and_kodeprod($id_detail, $kodeprod[$params_id]);
            }
        }

        $this->session->set_flashdata("pesan_success", "Berhasil menambahkan produk");
        redirect('spk/keranjang_alokasi/');
        die;
    }

    public function keranjang_belanja($signature = "")
    {
        if (!$signature)
        {
            $get_temp_spk_join_temp_spk_detail_by_userid = $this->model_spk->get_temp_spk_join_temp_spk_detail_by_userid($this->session->userdata('id'));
            if ($get_temp_spk_join_temp_spk_detail_by_userid->num_rows() > 0)
            {
                $get_data = $get_temp_spk_join_temp_spk_detail_by_userid;
            }else
            {
                $get_data = $get_temp_spk_join_temp_spk_detail_by_userid;
                // $this->session->set_flashdata("pesan", "silahkan pilih produk terlebih dahulu");
                // redirect('spk', 'refresh');
            }
        }else{
            $get_keranjang_belanja = $this->model_spk->get_keranjang_belanja($signature);
            $supp = $get_keranjang_belanja->row()->supp;
            $get_data = $get_keranjang_belanja;
        }

        $data = [
            'title'     => 'Keranjang Belanja',
            // 'url'       => 'spk/verifikasi_pesanan',
            'url'       => 'spk/pengiriman',
            'url_import'=> 'spk/import_spk',
            'get_data'  => $get_data
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/keranjang_belanja', $data);
        $this->load->view('kalimantan/footer');
    }

    public function delete_keranjang($signature_detail)
    {
        $get_keranjang_belanja_detail_by_signature = $this->model_spk->get_keranjang_belanja_detail_by_signature($signature_detail);
        if (!$get_keranjang_belanja_detail_by_signature->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "proses yang anda lakukan gagal. data not found");
            redirect('spk', 'refresh');
        }else
        {
            $data = [
                "deleted_at"    => $this->created_at,
                "deleted_by"    => $this->session->userdata('id')
            ];

            $this->db->where('signature', $signature_detail);
            $this->db->update('site.temp_spk_detail', $data);

            $this->session->set_flashdata("pesan_success", "Berhasil menghapus produk");
            redirect('spk/keranjang_belanja/');
        }
    }

    public function verifikasi_pesanan()
    {
        $get_keranjang_belanja_by_userid = $this->model_spk->get_keranjang_belanja_by_userid($this->session->userdata('id'));
        if (!$get_keranjang_belanja_by_userid->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "silahkan pilih produk dan masukkan jumlah karton terlebih dahulu");
            redirect('spk/keranjang_belanja', 'refresh');
        }else
        {
            $site_code = $get_keranjang_belanja_by_userid->row()->site_code;

            // harus berdasarkan step keranjang belanja
            $jml_karton = $this->input->post('jml_karton');
            $id_detail = $this->input->post('id_detail');
            $id_header = $this->input->post('id_header');

            if (!$jml_karton) { // jika by pass langsung ke url verifikasi_pesanan, maka akan di redirect ke keranjang belanja
                $this->session->set_flashdata("pesan", "Illegal route. You will be go back to keranjang belanja");
                redirect('spk/keranjang_belanja', 'refresh');
            }

            $count = count($jml_karton);
            for ($i=0; $i < $count ; $i++)
            {
                $data = [
                    "jml_karton"    => $jml_karton[$i],
                    "updated_at"    => $this->created_at,
                    "updated_by"    => $this->session->userdata('id')
                ];

                $this->db->where("id", $id_detail[$i]);
                $this->db->where("id_header", $id_header);
                $this->db->update("site.temp_spk_detail", $data);

                // jalankan update berat dan volume
                $update = $this->model_spk->update_berat_volume_in_keranjang_belanja_by_id($id_detail[$i]);

            }

            // mencari supp dengan cara group by untuk di looping ke halaman view
            $get_keranjang_belanja_detail_by_id_header_group_by_supp = $this->model_spk->get_keranjang_belanja_detail_by_id_header_group_by_supp($id_header);
            if (!$get_keranjang_belanja_detail_by_id_header_group_by_supp->num_rows() > 0)
            {
                $this->session->set_flashdata("pesan", "silahkan pilih produk terlebih dahulu");
                redirect('spk', 'refresh');
            }
        }

        $data = [
            'title'     => 'Verifikasi Pesanan',
            'url'       => 'spk/pengiriman',
            'get_data'  => $get_keranjang_belanja_detail_by_id_header_group_by_supp,
            'site_code' => $site_code
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/verifikasi_pesanan', $data);
        $this->load->view('kalimantan/footer');

    }

    public function pengiriman()
    {
        $get_temp_spk_join_temp_spk_detail_by_userid = $this->model_spk->get_temp_spk_join_temp_spk_detail_by_userid($this->session->userdata('id'));
        if (!$get_temp_spk_join_temp_spk_detail_by_userid->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "silahkan pilih produk terlebih dahulu");
            redirect('spk', 'refresh');
        }else
        {
            $site_code = $get_temp_spk_join_temp_spk_detail_by_userid->row()->site_code;

            // harus berdasarkan step keranjang belanja
            $jml_karton = $this->input->post('jml_karton');
            $id_detail = $this->input->post('id_detail');
            $id_header = $this->input->post('id_header');

            if (!$jml_karton) { // jika by pass langsung ke url verifikasi_pesanan, maka akan di redirect ke keranjang belanja
                $this->session->set_flashdata("pesan", "Illegal route. You will be go back to keranjang belanja");
                redirect('spk/keranjang_belanja', 'refresh');
            }

            $count = count($jml_karton);
            for ($i=0; $i < $count ; $i++)
            {
                $data = [
                    "jml_karton"    => $jml_karton[$i],
                    "updated_at"    => $this->created_at,
                    "updated_by"    => $this->session->userdata('id')
                ];

                $this->db->where("id", $id_detail[$i]);
                $this->db->where("id_header", $id_header);
                $this->db->update("site.temp_spk_detail", $data);

                // jalankan update berat dan volume
                $update = $this->model_spk->update_berat_volume_in_keranjang_belanja_by_id($id_detail[$i]);
            }

            // // filter minimum order
            // $get_temp_spk_join_temp_spk_detail_by_userid_update = $this->model_spk->get_temp_spk_join_temp_spk_detail_by_userid($this->session->userdata('id'));
            // $produk = $get_temp_spk_join_temp_spk_detail_by_userid_update->result();
            // // var_dump($produk);die;
            // for ($i=0; $i < count($produk) ; $i++) {
            //     if ($produk[$i]->jml_karton < $produk[$i]->moq) {
            //         $namaprod = $produk[$i]->namaprod;
            //         $this->session->set_flashdata("pesan", "Produk $namaprod kurang dari minimum order, silahkan tambah quantity produk terlebih dahulu");
            //         redirect('spk/keranjang_belanja', 'refresh');
            //     }
            // }
            // // end filter minimum order


            // mencari supp dengan cara group by untuk di looping ke halaman view
            $get_data = $this->model_spk->get_temp_spk_detail_by_id_header_group_by_supp($id_header);
            if (!$get_data->num_rows() > 0)
            {
                $this->session->set_flashdata("pesan", "silahkan pilih produk terlebih dahulu");
                redirect('spk', 'refresh');
            }

        }

        $get_alamat_by_userid = $this->model_spk->get_alamat_by_userid($this->session->userdata('id'),'');
        if (!$get_alamat_by_userid->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "alamat anda belum terdaftar di database");
            redirect('spk', 'refresh');
        }else{
            $company = $get_alamat_by_userid->row()->company;
            $npwp = $get_alamat_by_userid->row()->npwp;
            $email = $get_alamat_by_userid->row()->email;
            $branch_name = $get_alamat_by_userid->row()->branch_name;
            $nama_comp = $get_alamat_by_userid->row()->nama_comp;
        }

        $data = [
            'title'     => 'Alamat Kirim',
            'url'       => 'spk/preview_spk',
            'get_data'  => $get_alamat_by_userid,
            'company'   => $company,
            'npwp'      => $npwp,
            'email'     => $email,
            'branch_name' => $branch_name,
            'nama_comp' => $nama_comp,
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/pengiriman', $data);
        $this->load->view('kalimantan/footer');
    }

    public function preview_spk()
    {
        $get_data = $this->model_spk->get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton($this->session->userdata('id'));
        if (!$get_data->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "silahkan pilih produk terlebih dahulu");
            redirect('spk', 'refresh');
        }

        $id_header = $get_data->row()->id;
        $kode_alamat = $this->input->post('kode_alamat');

        // khusus alamat, mengambil langsung dari database based on kode_alamat
        $get_alamat_by_userid = $this->model_spk->get_alamat_by_userid($this->session->userdata('id'),$kode_alamat);
        if (!$get_alamat_by_userid->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "alamat anda belum terdaftfar di database");
            redirect('spk/keranjang_belanja', 'refresh');
            die;
        }

        // start update po_detail
        $cek_actual = [
            'id'    => $id_header,
            'site_code' => $kode_alamat,
            'tahun' => date('Y', strtotime($this->created_at)),
            'bulan' => date('m', strtotime($this->created_at)),
            'periode' => date('Y-m', strtotime($this->created_at)),
        ];
        $this->model_spk->update_po_detail_actual_po_bulan_temp_spk($cek_actual);

        die;

        $this->model_spk->update_po_detail_pp_unit_and_selisih_po_temp_spk($cek_actual);
        // end update po_detail

        $get_supp = $this->model_spk->get_temp_spk_detail_by_id_header_group_by_supp($id_header)->result();

        for ($i=0; $i < count($get_supp) ; $i++) {
            $get_produk = $this->model_spk->get_temp_spk_detail_by_supp_id_header_site_code($get_supp[$i]->supp, $get_supp[$i]->id_header, $kode_alamat)->result();
            $supplier[] = $get_supp[$i]->supp;
            $nama_supplier[] = $get_supp[$i]->namasupp;
            for ($j=0; $j < count($get_produk) ; $j++) {
                $kodeprod[$i][] = $get_produk[$j];
                $jml_karton[$i][] = $get_produk[$j]->jml_karton;
                $berat_produk[$i][] = $get_produk[$j]->berat_produk;
                $volume_produk[$i][] = $get_produk[$j]->volume_produk;
            }
        };
        // var_dump(array_sum($jml[0]));die;

        $data = [
            'title'     => 'Preview SPK',
            'url'       => 'spk/submit_spk',
            'company'   => $this->input->post('company'),
            'npwp'      => $this->input->post('npwp'),
            'email'     => $this->input->post('email'),
            'tipe'      => $this->input->post('tipe'),
            'alamat'    => $get_alamat_by_userid->row()->alamat,
            // 'get_data'  => $get_keranjang_belanja_by_userid_with_jumlah_karton,
            // 'get_supp'  => $get_supp,
            'data_produk' => [
                'supplier' => $supplier,
                'nama_supplier' => $nama_supplier,
                'kodeprod' => $kodeprod,
                'jml_karton' => $jml_karton,
                'berat_produk' => $berat_produk,
                'volume_produk' => $volume_produk,
            ],
            'moq_us'    => $get_data->row()->moq_us,
            'kode_alamat'   => $kode_alamat,
            'id_header'     => $id_header
        ];

        // var_dump($data);die;

        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/preview_spk', $data);
        $this->load->view('kalimantan/footer');
    }

    public function submit_spk()
    {
        $company    = $this->input->post('company');
        $npwp       = $this->input->post('npwp');
        $alamat     = $this->input->post('alamat');
        $tipe       = $this->input->post('tipe');
        $kode_alamat= $this->input->post('kode_alamat');
        $email      = $this->input->post('email');
        $id_header  = $this->input->post('id_header');

        $get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton = $this->model_spk->get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton($this->session->userdata('id'));
        if (!$get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses yang anda lakukan gagal. silahkan pilih produk terlebih dahulu");
            redirect('spk', 'refresh');
        }else{
            $id = $get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton->row()->id;
            $supp = $get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton->row()->supp;
            $site_code = $get_temp_spk_join_temp_spk_detail_by_userid_with_jumlah_karton->row()->site_code;
        }

        $get_temp_spk_detail_by_id_header_group_by_supp = $this->model_spk->get_temp_spk_detail_by_id_header_group_by_supp($id);
        if (!$get_temp_spk_detail_by_id_header_group_by_supp->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "silahkan pilih produk terlebih dahulu");
            redirect('spk', 'refresh');
            die;
        }

        foreach ($get_temp_spk_detail_by_id_header_group_by_supp->result() as $key)
        {
            $get_temp_spk_detail_by_supp_id_header_site_code = $this->model_spk->get_temp_spk_detail_by_supp_id_header_site_code($key->supp, $key->id_header, $kode_alamat);

            for ($i=0; $i < $get_temp_spk_detail_by_supp_id_header_site_code->num_rows(); $i++) {
                foreach ($get_temp_spk_detail_by_supp_id_header_site_code->result() as $cek_produk) {
                    if ($cek_produk->selisih_po < 0) {
                        $this->session->set_flashdata("pesan", "Ditemukan produk yang sudah melebihi batas order bulan ini, yaitu kodeprod : $cek_produk->kodeprod");
                        redirect('spk/keranjang_belanja', 'refresh');
                        die;
                    }
                }
            }

            if ($key->supp == '005')
            {
                $get_mapping_dc_by_site_code = $this->model_spk->get_mapping_dc_by_site_code($kode_alamat);
                if ($get_mapping_dc_by_site_code->num_rows() > 0)
                {
                    $alamat_kirim = $get_mapping_dc_by_site_code->row()->alamat_kirim;
                }else
                {
                    $alamat_kirim = $alamat;
                }

                // echo "alamat_kirim : ".$alamat_kirim;

                // die;

                $signature = 'spk-' . rand() . md5($this->created_at) . date('Ymd');

                $data = [
                    'userid'    => $this->userid,
                    'supp'      => $key->supp,
                    'company'   => $company,
                    'npwp'      => $npwp,
                    'email'     => $email,
                    'alamat'    => $alamat_kirim,
                    'alamat_kirim' => $alamat_kirim,
                    'kode_alamat' => $kode_alamat,
                    'tglpesan' => $this->created_at,
                    'tipe'      => $tipe,
                    'created'   => $this->created_at,
                    'created_by' => $this->userid,
                    'signature' => $signature
                ];

                $id_insert = $this->model_spk->insert_po($data);

                foreach ($get_temp_spk_detail_by_supp_id_header_site_code->result() as $po_detail)
                {
                    $data_po_detail = [
                        'id_ref'    => $id_insert,
                        'supp'      => $key->supp,
                        'kodeprod'  => $po_detail->kodeprod,
                        'namaprod'  => $po_detail->namaprod,
                        'kode_prc'  => $po_detail->kode_prc,
                        'banyak'    => $po_detail->jml_karton*$po_detail->isisatuan,
                        'banyak_karton' => $po_detail->jml_karton,
                        'harga' => ($po_detail->h_dp),
                        'berat' => $po_detail->master_berat,
                        'volume' => $po_detail->master_volume,
                        'userid' => $this->userid,
                    ];
                    $proses = $this->model_spk->insert_po_detail($data_po_detail);
                    $update_flag_selesai = $this->model_spk->update_flag_selesai_by_id($key->id_header);
                }

                $get_total = $this->model_spk->get_total_value_po_detail_by_id_ref($id_insert);
                if (!$get_total->num_rows() > 0) {
                    $total_value = 0;
                }else{
                    $total_value = $get_total->row()->total_value;
                }

                $data = [
                    "total_value"   => $total_value
                ];
                $this->model_spk->update_po($data, $id_insert);

            }else
            {
                $signature = 'spk-' . rand() . md5($this->created_at) . date('Ymd');

                $data = [
                    'userid'    => $this->userid,
                    'supp'      => $key->supp,
                    'company'   => $company,
                    'npwp'      => $npwp,
                    'email'     => $email,
                    'alamat'    => $alamat,
                    'alamat_kirim' => $alamat,
                    'kode_alamat' => $kode_alamat,
                    'tglpesan' => $this->created_at,
                    'tipe'      => $tipe,
                    'created'   => $this->created_at,
                    'created_by' => $this->userid,
                    'signature' => $signature
                ];
                $id_insert = $this->model_spk->insert_po($data);
                
                foreach ($get_temp_spk_detail_by_supp_id_header_site_code->result() as $po_detail)
                {
                    $kodeprod = $po_detail->kodeprod;

                    $data_po_detail = [
                        'id_ref'    => $id_insert,
                        'supp'      => $key->supp,
                        'kodeprod'  => $po_detail->kodeprod,
                        'namaprod'  => $po_detail->namaprod,
                        'kode_prc'  => $po_detail->kode_prc,
                        'banyak'    => $po_detail->jml_karton*$po_detail->isisatuan,
                        'banyak_karton' => $po_detail->jml_karton,
                        'harga' => ($po_detail->h_dp),
                        'berat' => $po_detail->master_berat,
                        'volume' => $po_detail->master_volume,
                        'userid' => $this->userid,
                    ];
                    $proses = $this->model_spk->insert_po_detail($data_po_detail);
                    $update_flag_selesai = $this->model_spk->update_flag_selesai_by_id($key->id_header);
                }

                $get_total = $this->model_spk->get_total_value_po_detail_by_id_ref($id_insert);
                if (!$get_total->num_rows() > 0) {
                    $total_value = 0;
                }else{
                    $total_value = $get_total->row()->total_value;
                }

                $data = [
                    "total_value"   => $total_value
                ];
                $this->model_spk->update_po($data, $id_insert);
            }
        }

        $this->session->set_flashdata('pesan_success', 'Input SPK Berhasil');
        redirect('spk/list_order');
    }

    public function keranjang_alokasi($signature = "")
    {
        if ($this->session->userdata('username') <> 'melinda' && $this->session->userdata('username') <> 'tria' && $this->session->userdata('username') <> 'suffy' && $this->session->userdata('username') <> 'fakhrul')
        {
            $this->session->set_flashdata("pesan", "Delete Gagal. User anda tidak diizinkan menuju menu alokasi");
            redirect('spk/list_order');
        }

        if (!$signature)
        {
            $get_data = $this->model_spk->get_temp_alokasi_by_userid($this->userid);
        }else{
            $get_temp_alokasi = $this->model_spk->get_temp_alokasi($signature);
            $supp = $get_temp_alokasi->row()->supp;
            $get_data = $get_temp_alokasi;
        }

        $data = [
            'title'     => 'Keranjang Alokasi',
            'url'       => 'spk/preview_alokasi',
            'url_import'=> 'spk/import_alokasi',
            'get_data'  => $get_data
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/keranjang_alokasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function preview_alokasi()
    {
        $get_temp_alokasi_by_userid = $this->model_spk->get_temp_alokasi_by_userid($this->userid);
        if (!$get_temp_alokasi_by_userid->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "silahkan pilih produk terlebih dahulu");
            redirect('spk', 'refresh');
        }else
        {
            $site_code = $get_temp_alokasi_by_userid->row()->site_code;
            // harus berdasarkan step dari keranjang belanja
            $jml_karton = $this->input->post('jml_karton');
            $id_detail = $this->input->post('id_detail');
            $id_header = $this->input->post('id_header');

            // echo "id_header : ".$id_header;

            if (!$jml_karton) { // jika by pass langsung ke url verifikasi_pesanan, maka akan di redirect ke keranjang belanja
                $this->session->set_flashdata("pesan", "Illegal route. You will be go back to keranjang alokasi");
                redirect('spk/keranjang_alokasi', 'refresh');
            }

            $count = count($jml_karton);
            for ($i=0; $i < $count ; $i++)
            {
                $data = [
                    "jml_karton"    => $jml_karton[$i],
                    "updated_at"    => $this->created_at,
                    "updated_by"    => $this->session->userdata('id')
                ];
                $update = $this->model_spk->update_temp_alokasi_detail_by_id($data, $id_detail[$i]);
                // jalankan update berat dan volume
                $update_berat_volume = $this->model_spk->update_berat_volume_temp_alokasi_detail_by_id($id_detail[$i]);
            }

            // mencari supp dengan cara group by untuk di looping ke halaman view
            $get_temp_alokasi_detail_group_by_supp = $this->model_spk->get_temp_alokasi_detail_group_by_supp($id_header);
            if (!$get_temp_alokasi_detail_group_by_supp->num_rows() > 0)
            {
                $this->session->set_flashdata("pesan", "silahkan pilih produk terlebih dahulu");
                redirect('spk/keranjang_alokasi', 'refresh');
            }
        }

        $get_supp = $get_temp_alokasi_detail_group_by_supp;

        $data = [
            'title'     => 'Preview Alokasi',
            'url'       => 'spk/submit_alokasi',
            'get_supp'  => $get_supp,
            'userid'    => $this->userid
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/preview_alokasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function submit_alokasi()
    {
        $supp = $this->input->post('supp');
        $site_code = $this->input->post('site_code');
        $kode_alamat = $this->input->post('kode_alamat');
        $alamat = $this->input->post('alamat');
        $npwp = $this->input->post('npwp');
        $userid_tujuan = $this->input->post('userid_tujuan');
        $company = $this->input->post('company');
        $email = $this->input->post('email');
        $id_header = $this->input->post('id_header');
        $id_detail = $this->input->post('id_detail');

        $tipe = $this->input->post('tipe');

        // var_dump($alamat);die;

        $count_alamat = count($alamat);
        // echo "count_alamat : ".$count_alamat;
        for ($i=0; $i < $count_alamat ; $i++)
        {
            // echo "alamat[$i] : ".$alamat[$i];
            // echo "<hr>";

            if ($alamat[$i] == '') {
                $this->session->set_flashdata('pesan', 'Input Alokasi Gagal. Ada data yang alamatnya kosong');
                redirect('spk/keranjang_alokasi', 'refresh');
            }
        }

        //proses cek apakah ada alamat yang null


        // die;

        $count = count($site_code);
        for ($i=0; $i < $count ; $i++)
        {
            if ($supp[$i] == '005')
            {
                $get_mapping_dc_by_site_code = $this->model_spk->get_mapping_dc_by_site_code($kode_alamat[$i]);
                if ($get_mapping_dc_by_site_code->num_rows() > 0)
                {
                    $alamat_kirim = $get_mapping_dc_by_site_code->row()->alamat_kirim;
                }else
                {
                    $alamat_kirim = $alamat[$i];
                }

                $signature = 'alokasi-' . rand() . md5($this->created_at) . date('Ymd');

                $data = [
                    'userid'    => $userid_tujuan[$i],
                    'supp'      => $supp[$i],
                    'company'   => $company[$i],
                    'npwp'      => $npwp[$i],
                    'email'     => $email[$i],
                    'alamat'    => $alamat_kirim,
                    'alamat_kirim' => $alamat_kirim,
                    'kode_alamat' => $kode_alamat[$i],
                    'tglpesan' => $this->created_at,
                    'tipe'      => $tipe,
                    'created'   => $this->created_at,
                    'created_by' => $this->userid,
                    'signature' => $signature
                ];
                $id_insert = $this->model_spk->insert_po($data);

                $get_data = $this->model_spk->get_temp_alokasi_detail_by_supp_id_header_site_code($supp[$i], $id_header[$i], $kode_alamat[$i]);

                foreach ($get_data->result() as $po_detail)
                {
                    $data_po_detail = [
                        'id_ref'    => $id_insert,
                        'supp'      => $supp[$i],
                        'kodeprod'  => $po_detail->kodeprod,
                        'namaprod'  => $po_detail->namaprod,
                        'kode_prc'  => $po_detail->kode_prc,
                        'banyak'    => $po_detail->jml_karton*$po_detail->isisatuan,
                        'banyak_karton' => $po_detail->jml_karton,
                        'harga' => ($po_detail->h_dp),
                        'berat' => $po_detail->master_berat,
                        'volume' => $po_detail->master_volume,
                        'userid'    => $userid_tujuan[$i],
                    ];
                    $proses = $this->model_spk->insert_po_detail($data_po_detail);
                    $update_flag_selesai = $this->model_spk->update_temp_alokasi_flag_selesai_by_id($id_header[$i]);
                }

                $get_total = $this->model_spk->get_total_value_po_detail_by_id_ref($id_insert);
                if (!$get_total->num_rows() > 0) {
                    $total_value = 0;
                }else{
                    $total_value = $get_total->row()->total_value;
                }

                $data = [
                    "total_value"   => $total_value
                ];
                $this->model_spk->update_po($data, $id_insert);

            }else
            {
                $signature = 'alokasi-' . rand() . md5($this->created_at) . date('Ymd');
                $data = [
                    'userid'    => $userid_tujuan[$i],
                    'supp'      => $supp[$i],
                    'company'   => $company[$i],
                    'npwp'      => $npwp[$i],
                    'email'     => $email[$i],
                    'alamat'    => $alamat[$i],
                    'alamat_kirim' => $alamat[$i],
                    'kode_alamat' => $kode_alamat[$i],
                    'tglpesan' => $this->created_at,
                    'tipe'      => $tipe,
                    'created'   => $this->created_at,
                    'created_by' => $this->userid,
                    'signature' => $signature
                ];
                $id_insert = $this->model_spk->insert_po($data);

                $get_data = $this->model_spk->get_temp_alokasi_detail_by_supp_id_header_site_code($supp[$i], $id_header[$i], $kode_alamat[$i]);

                foreach ($get_data->result() as $po_detail)
                {
                    $data_po_detail = [
                        'id_ref'    => $id_insert,
                        'supp'      => $supp[$i],
                        'kodeprod'  => $po_detail->kodeprod,
                        'namaprod'  => $po_detail->namaprod,
                        'kode_prc'  => $po_detail->kode_prc,
                        'banyak'    => $po_detail->jml_karton*$po_detail->isisatuan,
                        'banyak_karton' => $po_detail->jml_karton,
                        'harga' => ($po_detail->h_dp),
                        'berat' => $po_detail->master_berat,
                        'volume' => $po_detail->master_volume,
                        'userid'    => $userid_tujuan[$i],
                    ];
                    $proses = $this->model_spk->insert_po_detail($data_po_detail);
                    $update_flag_selesai = $this->model_spk->update_temp_alokasi_flag_selesai_by_id($id_header[$i]);
                }

                $get_total = $this->model_spk->get_total_value_po_detail_by_id_ref($id_insert);
                if (!$get_total->num_rows() > 0) {
                    $total_value = 0;
                }else{
                    $total_value = $get_total->row()->total_value;
                }

                $data = [
                    "total_value"   => $total_value
                ];
                $this->model_spk->update_po($data, $id_insert);
            }
        }

        $this->session->set_flashdata('pesan_success', 'Input Alokasi Berhasil');
        redirect('spk/list_order', 'refresh');

    }

    public function truncate_alokasi()
    {
        $this->model_spk->truncate();
        redirect('spk/keranjang_alokasi', 'refresh');
    }

    public function master_supp()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token' => $token,
            'X-API-KEY' => $api_key
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/namasupp?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datas = $array_response['data'];
            // var_dump($datas);die;
            echo "<option value=''> -- Pilih Principal -- </option>";

            foreach ($datas as $key => $data)
            {
                echo "<option value='". $data["supp"] ."' >";
                echo $data["namasupp"];
                echo "</option>";
            }
        }
    }

    public function form_generate_average_sales()
    {
        $data = [
            'title'     => 'Generate Average Sales',
            'url'       => 'spk/form_generate_average_sales_proses',
            'get_data'  => $this->model_spk->get_average_sales()
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/form_generate_average_sales', $data);
        $this->load->view('kalimantan/footer');
    }

    public function form_generate_average_sales_proses()
    {
        $userid = $this->session->userdata('id');
        $cycle = $this->input->post('cycle');

        $generate_sales = $this->model_spk->generate_sales($cycle);
        $generate_average = $this->model_spk->generate_average($cycle);

        if ($generate_average) {
            $this->session->set_flashdata("pesan_success", "Generate Average Sales Success");
            redirect('spk/form_generate_average_sales', 'refresh');
        }

    }

    public function export_template_spk()
    {
        $query = "
            select '' as kodeproduk, '' as qty_in_karton
        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'kodeproduk', 'qty_in_karton'
        ));
        $this->excel_generator->set_column(array
        (
            'kodeproduk', 'qty_in_karton'
        ));
        $this->excel_generator->set_width(array(15,15));
        $this->excel_generator->exportTo2007('Template Import SPK');
    }

    public function export_template_alokasi()
    {
        $query = "
            select '' as site_code, '' as kode_alamat, '' as kodeproduk, '' as qty_in_karton
        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'kode_alamat', 'kodeproduk', 'qty_in_karton'
        ));
        $this->excel_generator->set_column(array
        (
            'site_code', 'kode_alamat', 'kodeproduk', 'qty_in_karton'
        ));
        $this->excel_generator->set_width(array(15,15,15,15));
        $this->excel_generator->exportTo2007('Template Import Alokasi');
    }

    public function export_master_site()
    {
        $query = "
            select a.site_code, a.branch_name, a.nama_comp
            from site.master_site a
        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'site_code', 'branch_name', 'nama_comp'
        ));
        $this->excel_generator->set_column(array
        (
            'site_code', 'branch_name', 'nama_comp'
        ));
        $this->excel_generator->set_width(array(15,15,15));
        $this->excel_generator->exportTo2007('Master Site');
    }

    public function export_template_list_order($signature)
    {
        $get_po = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
            redirect('spk/list_order');
        }

        $query = "
            select '' as kodeproduk, '' as qty_in_unit
        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'kodeproduk', 'qty_in_unit'
        ));
        $this->excel_generator->set_column(array
        (
            'kodeproduk', 'qty_in_unit'
        ));
        $this->excel_generator->set_width(array(15,15));
        $this->excel_generator->exportTo2007('Template Import List Order');
    }

    public function import_spk()
    {
        $file = $this->input->post('file');
        $signature = 'import-spk-' . rand() . md5($this->created_at) . date('Ymd');
        if (!is_dir('./assets/uploads/spk/import/')) {
            @mkdir('./assets/uploads/spk/import/', 0777);
        }
        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/spk/import/';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;

        $this->upload->initialize($config);
        if ($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/spk/import/$file_name");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1)
            {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('spk/keranjang_belanja','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            foreach ($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 100)
                {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 100 ROW.");
                    redirect('spk/keranjang_belanja');
                }

                for ($row = 2; $row <= $highestRow; $row++)
                {
                    $kodeprod       = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $qty_in_karton  = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    // validasi kodeprod
                    if(strlen("$kodeprod") == '5')
                    {
                        $params_kodeprod = '0'.$kodeprod;
                    }else
                    {
                        $params_kodeprod = $kodeprod;
                    }

                    $get_kodeprod = $this->model_spk->get_tabprod_spk($params_kodeprod);
                    if (!$get_kodeprod->num_rows() > 0)
                    {
                        $this->session->set_flashdata("pesan", "masukkan kodeproduk yang sesuai dengan standar MPM. Anda memasukkan kodeproduk : $kodeprod");
                        redirect('spk/keranjang_belanja', 'refresh');
                        die;
                    }else
                    {
                        $supp = $get_kodeprod->row()->supp;
                    }

                    // cek apakah sudah ada di keranjang belanja yang flag_selesai is null
                    $get_data = $this->model_spk->get_temp_spk_join_temp_spk_detail_by_userid($this->session->userdata('id'));
                    if ($get_data->num_rows() > 0)
                    {
                        $id_header = $get_data->row()->id;
                        $site_code = $get_data->row()->site_code;
                    }
                    else
                    {
                        // get site_code by username
                        $get_tabcomp_by_kode_comp = $this->model_master_data->get_tabcomp_by_kode_comp($this->session->userdata('username'));
                        if (!$get_tabcomp_by_kode_comp->num_rows() > 0) {
                            $this->session->set_flashdata("pesan", "user anda tidak terdaftar");
                            redirect('spk', 'refresh');
                        }else{
                            $site_code = $get_tabcomp_by_kode_comp->row()->site_code;
                        }

                        $data = [
                            "site_code"     => $site_code,
                            "created_at"    => $this->created_at,
                            "created_by"    => $this->session->userdata('id'),
                            "signature"     => $signature
                        ];
                        $proses = $this->db->insert('site.temp_spk', $data);
                        $id_header = $this->db->insert_id();
                    }

                    $signature_detail = 'spk-detail-import-' . rand() . md5($this->created_at) . date('Ymd');

                    // cek apakah sudah ada di keranjang belanja
                    $get_data = $this->model_spk->get_temp_spk_detail_by_id_header_and_kodeprod($id_header, $params_kodeprod);

                    // cek kodeproduk exists
                    if ($get_data->num_rows() > 0)
                    {
                        $id_detail = $get_data->row()->id;

                        $kodeprod_keranjang = $get_data->row()->kodeprod;
                        $jumlah_karton_keranjang = $get_data->row()->jml_karton;
                        if ($kodeprod_keranjang === $params_kodeprod)
                        {
                            // jumlahkan qty karton dengan yang sudah ada
                            $jumlah_karton = $jumlah_karton_keranjang + $qty_in_karton;

                            $data = [
                                "supp"          => $supp,
                                "kodeprod"      => $params_kodeprod,
                                "jml_karton"    => $jumlah_karton,
                                "created_at"    => $this->created_at,
                                "created_by"    => $this->session->userdata('id'),
                                "signature"     => $signature_detail
                            ];
                            $this->db->where('id_header', $id_header);
                            $this->db->where('kodeprod', $kodeprod_keranjang);
                            $this->db->update('site.temp_spk_detail', $data);

                            // jalankan update master berat dan master volume
                            $update = $this->model_spk->update_master_berat_volume_in_keranjang_belanja_by_id_and_kodeprod($id_detail, $params_kodeprod);

                        }
                        else
                        {
                            // echo "bbb";
                            // die;
                            $data = [
                                "id_header"     => $id_header,
                                "supp"          => $supp,
                                "kodeprod"      => $kodeprod[$params_id],
                                "jml_karton"    => $qty_in_karton,
                                "created_at"    => $this->created_at,
                                "created_by"    => $this->session->userdata('id'),
                                "signature"     => $signature_detail
                            ];
                            $this->db->insert('site.temp_spk_detail', $data);
                            $id_detail = $this->db->insert_id();

                            // jalankan update master berat dan master volume
                            $update = $this->model_spk->update_master_berat_volume_in_keranjang_belanja_by_id_and_kodeprod($id_detail, $params_kodeprod);
                        }
                    }
                    else
                    {
                        $data = [
                            "id_header"     => $id_header,
                            "supp"          => $supp,
                            "kodeprod"      => $params_kodeprod,
                            "jml_karton"    => $qty_in_karton,
                            "created_at"    => $this->created_at,
                            "created_by"    => $this->session->userdata('id'),
                            "signature"     => $signature_detail
                        ];
                        $this->db->insert('site.temp_spk_detail', $data);
                        $id_detail = $this->db->insert_id();

                        // jalankan update master berat dan master volume
                        $update = $this->model_spk->update_master_berat_volume_in_keranjang_belanja_by_id_and_kodeprod($id_detail, $params_kodeprod);
                    }
                }

                $this->session->set_flashdata("pesan_success", "Import data selesai. Selalu cek ulang keranjang belanja anda sebelum checkout");
                redirect('spk/keranjang_belanja');

            }
        }else{
            $this->session->set_flashdata("pesan", "File yang anda upload bukan file excel");
            redirect('spk/keranjang_belanja');
        }


    }

    public function import_alokasi()
    {
        $this->model_spk->truncate();

        $file = $this->input->post('file');
        if (!is_dir('./assets/uploads/alokasi/import/')) {
            @mkdir('./assets/uploads/alokasi/import/', 0777);
        }
        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/alokasi/import/';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;

        $this->upload->initialize($config);
        if ($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/alokasi/import/$file_name");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1)
            {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('spk/keranjang_belanja','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            foreach ($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 1000)
                {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 1000 ROW.");
                    redirect('spk/keranjang_alokasi');
                }

                for ($row = 2; $row <= $highestRow; $row++)
                {
                    $site_code      = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $kode_alamat    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $kodeprod       = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $qty_in_karton  = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());

                    // cek kodeprod
                    $params_kodeprod = $this->model_spk->fix_kodeprod_length($kodeprod);
                    $get_kodeprod = $this->model_spk->get_produk_by_kodeprod($params_kodeprod);
                    if (!$get_kodeprod->num_rows() > 0)
                    {
                        $this->session->set_flashdata("pesan", "masukkan kodeproduk yang sesuai dengan standar MPM. Anda memasukkan kodeproduk : $kodeprod");
                        redirect('spk/keranjang_alokasi', 'refresh');
                        die;
                    }else
                    {
                        $supp = $get_kodeprod->row()->supp;
                    }

                    $signature = 'import-alokasi-' . rand() . md5($this->created_at) . date('Ymd');
                    $data = [
                        "site_code"     => $site_code,
                        "kode_alamat"   => $kode_alamat,
                        "kodeprod"      => $params_kodeprod,
                        "qty_in_karton" => $qty_in_karton,
                        "created_at"    => $this->created_at,
                        "created_by"    => $this->session->userdata('id'),
                        "signature"     => $signature
                    ];
                    $insert = $this->model_spk->insert_temp_alokasi_original_excel($data);
                }

                $get_temp_alokasi_original_excel_group_by_site_code = $this->model_spk->get_temp_alokasi_original_excel_group_by_site_code();
                foreach ($get_temp_alokasi_original_excel_group_by_site_code->result() as $a)
                {
                    $site_code = $a->site_code;
                    $get_temp_alokasi_original_excel_by_site_code_group_by_kode_alamat = $this->model_spk->get_temp_alokasi_original_excel_by_site_code_group_by_kode_alamat($site_code);
                    foreach ($get_temp_alokasi_original_excel_by_site_code_group_by_kode_alamat->result() as $b)
                    {
                        $kode_alamat = $b->kode_alamat;
                        // echo "kode_alamat : ".$kode_alamat."<br>";

                        $signature = 'alokasi-' . rand() . md5($this->created_at) . date('Ymd');

                        $data_header = [
                            "site_code"     => $site_code,
                            "kode_alamat"   => $kode_alamat,
                            "signature"     => $signature,
                            "created_at"    => $this->created_at,
                            "created_by"    => $this->userid
                        ];

                        $insert_header = $this->model_spk->insert_temp_alokasi($data_header);

                        $get_temp_alokasi_original_excel_by_site_code_and_kode_alamat_group_by_kodeprod = $this->model_spk->get_temp_alokasi_original_excel_by_site_code_and_kode_alamat_group_by_kodeprod($site_code, $kode_alamat);
                        foreach ($get_temp_alokasi_original_excel_by_site_code_and_kode_alamat_group_by_kodeprod->result() as $c)
                        {

                            $kodeprod = $c->kodeprod;
                            $qty_in_karton = $c->qty_in_karton;
                            $signature_detail = 'alokasi-detail' . rand() . md5($this->created_at) . date('Ymd');

                            $data_detail = [
                                "id_header"          => $insert_header,
                                "supp"               => $this->model_master_data->master_product_by_kodeprod($kodeprod)->row()->supp,
                                "kodeprod"           => $kodeprod,
                                "jml_karton"         => $qty_in_karton,
                                "master_berat"       => $this->model_master_data->master_product_by_kodeprod($kodeprod)->row()->berat,
                                "master_volume"      => $this->model_master_data->master_product_by_kodeprod($kodeprod)->row()->volume,
                                "created_at"         => $this->created_at,
                                "created_by"         => $this->userid,
                                "signature"          => $signature_detail
                            ];

                            $insert_detail = $this->model_spk->insert_temp_alokasi_detail($data_detail);

                            $update_berat_volume_temp_alokasi_detail_by_id = $this->model_spk->update_berat_volume_temp_alokasi_detail_by_id($insert_detail);
                        }
                    }
                }
                $this->session->set_flashdata("pesan_success", "Import data selesai. Selalu cek ulang keranjang alokasi anda sebelum checkout");
                redirect('spk/keranjang_alokasi');
            }
        }else{
            $this->session->set_flashdata("pesan", "File yang anda upload bukan file excel");
            redirect('spk/keranjang_alokasi');
        }
    }

    public function import_list_order_detail()
    {
        $signature = $this->input->post('signature');

        $get_po = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
            redirect('spk/list_order');
        }else{
            $id_po = $get_po->row()->id;
            $supp = $get_po->row()->supp;
        }

        $file = $this->input->post('file');
        $signature_import = 'import-list_order_detail-' . rand() . md5($this->created_at) . date('Ymd');
        if (!is_dir('./assets/uploads/spk/import/')) {
            @mkdir('./assets/uploads/spk/import/', 0777);
        }
        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/spk/import/';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;

        $this->upload->initialize($config);
        if ($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/spk/import/$file_name");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1)
            {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('spk/list_order_detail/'.$signature,'refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            foreach ($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                if ($highestRow > 100)
                {
                    $this->session->set_flashdata("pesan", "Import Gagal. Terlalu banyak ROW. Maximal 100 ROW.");
                    redirect('spk/list_order_detail/'.$signature,'refresh');
                }

                for ($row = 2; $row <= $highestRow; $row++)
                {
                    $kodeprod       = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $qty_in_unit    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    // validasi kodeprod
                    if(strlen("$kodeprod") == '5')
                    {
                        $params_kodeprod = '0'.$kodeprod;
                    }else
                    {
                        $params_kodeprod = $kodeprod;
                    }

                    $get_kodeprod = $this->model_spk->get_master_product_with_harga($params_kodeprod, $supp);
                    if (!$get_kodeprod->num_rows() > 0)
                    {
                        $this->session->set_flashdata("pesan", "masukkan kodeproduk yang sesuai dengan standar MPM dan Principal di PO ini. Anda memasukkan kodeproduk : $kodeprod");
                        redirect('spk/list_order_detail/'.$signature,'refresh');
                        die;
                    }else
                    {
                        $supp = $get_kodeprod->row()->supp;
                        $namaprod = $get_kodeprod->row()->namaprod;
                        $kode_prc = $get_kodeprod->row()->kode_prc;
                        $berat = $get_kodeprod->row()->berat;
                        $volume = $get_kodeprod->row()->volume;
                        $isisatuan = $get_kodeprod->row()->isisatuan;
                        $h_dp = $get_kodeprod->row()->h_dp;
                        $banyak_karton = $qty_in_unit / $isisatuan;
                    }

                    $get_data = $this->model_spk->get_po_detail_by_id_po_kodeprod($id_po, $params_kodeprod);
                    if ($get_data->num_rows() > 0)
                    {
                        $id_po_detail = $get_data->row()->id;
                        // lakukan update
                        $data_po_detail = [
                            'banyak'        => $qty_in_unit + $get_data->row()->banyak,
                            'berat'         => $berat,
                            'volume'        => $volume,
                            'updated_by'    => $this->userid,
                            'updated_at'    => $this->created_at
                        ];
                        $proses = $this->model_spk->update_po_detail($data_po_detail, $id_po_detail);
                        // lakukan update karton
                        $update_karton = $this->model_spk->update_karton_in_po_detail($id_po_detail, $isisatuan);
                    }else
                    {
                        // lakukan insert
                        $data_po_detail = [
                            'id_ref'    => $id_po,
                            'supp'      => $supp,
                            'kodeprod'  => $params_kodeprod,
                            'namaprod'  => $namaprod,
                            'kode_prc'  => $kode_prc,
                            'banyak'    => $qty_in_unit,
                            'banyak_karton' => $banyak_karton,
                            'berat' => $berat,
                            'volume' => $volume,
                            'harga' => $h_dp,
                            'userid' => $this->userid,
                            'created_at' => $this->created_at,
                            'created_by' => $this->userid,
                            'updated_at' => $this->created_at,
                            'updated_by' => $this->userid,
                        ];
                        $proses = $this->model_spk->insert_po_detail($data_po_detail);
                    }
                }

                $this->session->set_flashdata("pesan_success", "Import data selesai. Selalu cek ulang data anda");
                redirect('spk/list_order_detail/'.$signature,'refresh');
            }
        }else{
            $this->session->set_flashdata("pesan", "File yang anda upload bukan file excel");
            redirect('spk/keranjang_belanja');
        }
    }

    public function list_order()
    {
        $site_code = $this->model_master_data->get_tabcomp_by_kode_comp($this->session->userdata('username'));
        if ($site_code->num_rows() > 0)
        {
            $site_code_session = $site_code->row()->site_code;
        }else{
            $site_code_session = '';
        }

        $flag_delete = $this->input->post('flag_delete');
        if($this->input->post('from'))
        {
            $advanced['from']           = $this->input->post('from');
            $advanced['to']             = $this->input->post('to');
            $advanced['site_code']      = $this->input->post('site_code');
            $advanced['limit']          = $this->input->post('limit');
            $advanced['flag_delete']    = $flag_delete;
        }
        else
        {
            $advanced = null;
        }

        $data = [
            'title'     => 'List Order',
            'url'       => 'spk/list_order',
            'get_data'  => $this->model_spk->get_po($advanced),
            'from'      => ($this->input->post('from')) ? $this->input->post('from') : '',
            'to'        => ($this->input->post('to')) ? $this->input->post('to') : '',
            'limit'     => ($this->input->post('limit')) ? $this->input->post('limit') : '',
            'flag_delete' => $flag_delete
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/list_order', $data);
        $this->load->view('kalimantan/footer');
    }

    public function list_order_detail($signature)
    {
        if ($this->session->userdata('username') <> 'melinda' && $this->session->userdata('username') <> 'tria' && $this->session->userdata('username') <> 'suffy' && $this->session->userdata('username') <> 'fakhrul')
        {
            $this->session->set_flashdata("pesan", "Anda tidak diijinkan untuk mengakses halaman ini");
            redirect('spk/list_order');
        }

        $get_po = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
            redirect('spk/list_order');
        }

        if ($get_po->row()->nopo == NULL || $get_po->row()->nopo == '') {
            $this->update_pp_po($signature);
            $get_po = $this->model_spk->get_po_by_signature($signature);
        }
        $data = [
            'title'         => 'List Order Detail',
            'url'           => 'spk/list_order_detail',
            'url_finance'   => 'spk/update_finance',
            'url_update'    => 'spk/update_po',
            'url_rilis'     => 'spk/rilis_po',
            'url_import'    => 'spk/import_list_order_detail',
            'url_update_karton' => 'spk/update_karton_po_detail',
            'url_update_pp_po'  => "spk/update_pp_po/$signature",
            'url_approv_pp' => "spk/approv_pp/",
            'get_data'      => $this->model_spk->get_po_detail_by_id_po_include_delete($get_po->row()->id),
            'nopo'          => $get_po->row()->nopo,
            'namasupp'      => $get_po->row()->namasupp,
            'branch_name'   => $get_po->row()->branch_name,
            'nama_comp'     => $get_po->row()->nama_comp,
            'npwp'          => $get_po->row()->npwp,
            'email'         => $get_po->row()->email,
            'kode_alamat'   => $get_po->row()->kode_alamat,
            'company'       => $get_po->row()->company,
            'alamat_kirim'  => $get_po->row()->alamat_kirim,
            'alamat'        => $get_po->row()->alamat,
            'tipe'          => $get_po->row()->tipe,
            'tglpesan'      => $get_po->row()->tglpesan,
            'flag_open'     => $get_po->row()->open,
            'status'        => $get_po->row()->status,
            'status_approval' => $get_po->row()->status_approval,
            'alasan_approval' => $get_po->row()->alasan_approval,
            'open_date'     => $get_po->row()->open_date,
            'signature'     => $signature,
            'note'          => $get_po->row()->note,
            'po_ref'        => $get_po->row()->po_ref,
            'id_po'         => $get_po->row()->id,
            'is_pp_approval'    => $get_po->row()->is_pp_approval,
            'pp_approved_file'  => $get_po->row()->pp_approved_file
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/accordion_list_order_detail', $data);
        $this->load->view('spk/list_order_detail', $data);
        $this->load->view('kalimantan/footer');
    }

    public function delete_po($signature, $tahun)
    {
        if ($this->session->userdata('username') <> 'melinda' && $this->session->userdata('username') <> 'tria' && $this->session->userdata('username') <> 'suffy' && $this->session->userdata('username') <> 'fakhrul')
        {
            $this->session->set_flashdata("pesan", "Delete Gagal. User anda tidak diizinkan menghapus data ini");
            redirect('spk/list_order');
        }

        $get_id_po = $this->model_spk->get_po_by_signature_tahun($signature, $tahun);
        if (!$get_id_po->num_rows() > 0) {
            $this->session->set_flashdata("pesan", "Delete Gagal. Data not found");
            redirect('spk/list_order');
        }else{
            $id_po = $get_id_po->row()->id;
        }

        $data = [
            "deleted"       => 1,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->userid,
            "deleted_at"    => $this->created_at,
            "deleted_by"    => $this->userid
        ];
        $this->model_spk->delete_po($data, $id_po);
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('spk/list_order');
    }

    public function master_sitecode()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'userid'    => $this->session->userdata('id'),
            'tahun'     => date('Y'),
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/site_code?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $result = $array_response['data'];
            echo "<option value=''> -- Pilih Company -- </option>";
            echo "<option value='all'>All Company</option>";

            foreach ($result as $key => $r)
            {
                echo "<option value='". $r["site_code"] . "' >";
                echo $r["company"] . " ___ " . $r["site_code"];
                echo "</option>";
            }
        }
    }

    public function list_order_detail_delete($id, $signature)
    {
        if ($this->session->userdata('username') <> 'melinda' && $this->session->userdata('username') <> 'tria' && $this->session->userdata('username') <> 'suffy' && $this->session->userdata('username') <> 'fakhrul')
        {
            $this->session->set_flashdata("pesan", "Delete Gagal. User anda tidak diizinkan menghapus data ini");
            redirect('spk/list_order');
        }

        $get_po = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
            redirect('spk/list_order_detail/' . $id. '/' . $signature);
        }

        $id_po = $get_po->row()->id;

        $data = [
            "deleted"       => 1,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->userid,
            "deleted_at"    => $this->created_at,
            "deleted_by"    => $this->userid
        ];
        $this->model_spk->delete_po_detail($data, $id);


        $get_total = $this->model_spk->get_total_value_po_detail_by_id_ref($id_po);
        if (!$get_total->num_rows() > 0) {
            $total_value = 0;
        }else{
            $total_value = $get_total->row()->total_value;
        }

        $data = [
            "total_value"   => $total_value
        ];
        $this->model_spk->update_po($data, $id_po);
        $this->session->set_flashdata("pesan_success", "Delete Berhasil");
        redirect('spk/list_order_detail/' . $signature);

    }

    public function list_order_detail_undelete($id, $signature)
    {
        if ($this->session->userdata('username') <> 'melinda' && $this->session->userdata('username') <> 'tria' && $this->session->userdata('username') <> 'suffy' && $this->session->userdata('username') <> 'fakhrul')
        {
            $this->session->set_flashdata("pesan", "Delete Gagal. User anda tidak diizinkan menghapus data ini");
            redirect('spk/list_order');
        }

        $get_po = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
            redirect('spk/list_order_detail/' . $id. '/' . $signature);
        }

        $id_po = $get_po->row()->id;

        $data = [
            "deleted"       => 0,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->userid,
            "deleted_at"    => $this->created_at,
            "deleted_by"    => $this->userid
        ];
        $this->model_spk->delete_po_detail($data, $id);


        $get_total = $this->model_spk->get_total_value_po_detail_by_id_ref($id_po);
        if (!$get_total->num_rows() > 0) {
            $total_value = 0;
        }else{
            $total_value = $get_total->row()->total_value;
        }

        $data = [
            "total_value"   => $total_value
        ];
        $this->model_spk->update_po($data, $id_po);
        $this->session->set_flashdata("pesan_success", "Undo Delete Berhasil");
        redirect('spk/list_order_detail/' . $signature);

    }

    public function update_finance()
    {
        $signature = $this->input->post("signature");
        $alasan =  $this->input->post("alasan");

        // start update pp_po and is_pp_approval
        $this->update_pp_po($signature);
        // end update pp_po and is_pp_approval

        $get_po = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
            redirect('spk/list_order_detail/'.$signature);
        }

        if ($get_po->row('is_pp_approval') == 1 && ($get_po->row('pp_approved_file') == '' || $get_po->row('pp_approved_date') == null || $get_po->row('pp_approved_by') == null)) {
            $this->session->set_flashdata("pesan", "Proses Gagal. Silakan melakukan proses approval purchase plan terlebih dahulu !");
            redirect('spk/list_order_detail/'.$signature);
        }
        $id_po = $get_po->row()->id;

        $data = [
            "status"            => 2,
            "status_approval"   => 1,
            "alasan_approval"   => $alasan
        ];
        $this->model_spk->update_po($data, $id_po);
        $this->session->set_flashdata("pesan_success", "Update Status Berhasil. Silahkan tunggu approval finance");
        redirect('spk/list_order_detail/'.$signature);
    }

    public function rilis_po()
    {
        $signature = $this->input->post("signature");
        $note =  $this->input->post("note");
        $po_ref =  $this->input->post("po_ref");

        if ($this->session->userdata('username') <> 'melinda' && $this->session->userdata('username') <> 'tria' && $this->session->userdata('username') <> 'suffy' && $this->session->userdata('username') <> 'fakhrul')
        {
            $this->session->set_flashdata("pesan", "Delete Gagal. User anda tidak diizinkan me-rilis po");
            redirect('spk/list_order_detail/'.$signature);
        }


        // start update pp_po and is_pp_approval
        $this->update_pp_po($signature);
        // end update pp_po and is_pp_approval


        $get_po = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
            redirect('spk/list_order');
        }else{

            $id_po = $get_po->row()->id;
            $supp = $get_po->row()->supp;
            $tglpesan = $get_po->row()->tglpesan;
            $is_pp_approval = $get_po->row()->is_pp_approval;
            $pp_approved_file = $get_po->row()->pp_approved_file;
            $pp_approved_by = $get_po->row()->pp_approved_by;
            $pp_approved_date = $get_po->row()->pp_approved_by;
        }
        // start jika is_pp_approval true(1), maka pp_approved_file &&  pp_approved_by && pp_approved_date is exists
        if ($is_pp_approval == 1 && ($pp_approved_file == '' || $pp_approved_by == null || $pp_approved_date == null)) {
            $this->session->set_flashdata("pesan", "Rilis PO Gagal dikarenakan belom melakukan approval purchase plan");
            redirect('spk/list_order_detail/'.$signature);
        }
        // end jika is_pp_approval true(1), maka pp_approved_file &&  pp_approved_by && pp_approved_date is exists

        // get total value po

        $get_total = $this->model_spk->get_total_value_po_detail_by_id_ref($id_po);
        if (!$get_total->num_rows() > 0) {
            $total_value = 0;
        }else{
            $total_value = $get_total->row()->total_value;
        }

        // get status_locked
        $get_status_locked = $this->model_spk->get_status_locked();
        if (!$get_status_locked->num_rows() > 0) {
            $status_locked = 0;
        }else{
            $status_locked = $get_status_locked->row()->is_locked;
        }

        if ($status_locked == 1) {
            // list_order_detail
            $this->session->set_flashdata("pesan", "Rilis PO Gagal dikarenakan dalam antrian, Silahkan ulangi kembali");
            redirect('spk/list_order_detail/'.$signature);
        }

        // insert status locked agar mencegah duplikasi nopo
        $this->model_spk->insert_status_locked($id_po, 1);

        $data = [
            "nopo"          => $this->model_spk->generate_nopo($supp, $tglpesan),
            "tglpo"         => $this->created_at,
            "note"          => $note,
            "po_ref"        => $po_ref,
            "total_value"   => $total_value
        ];
        $this->model_spk->update_po($data, $id_po);

        $this->model_spk->insert_status_locked($id_po, 0);

        $this->session->set_flashdata("pesan_success", "Rilis PO Berhasil");
        redirect('spk/list_order_detail/'.$signature);
    }

    public function update_po()
    {
        $signature = $this->input->post("signature");
        $tipe =  $this->input->post("tipe");
        $note =  $this->input->post("note");
        $po_ref =  $this->input->post("po_ref");

        if ($this->session->userdata('username') <> 'melinda' && $this->session->userdata('username') <> 'tria' && $this->session->userdata('username') <> 'suffy' && $this->session->userdata('username') <> 'fakhrul')
        {
            $this->session->set_flashdata("pesan", "Delete Gagal. User anda tidak diizinkan me-rilis po");
            redirect('spk/list_order_detail/'.$signature);
        }

        $get_po = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po->num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Proses Gagal. Data not found");
            redirect('spk/list_order');
        }else{
            $id_po = $get_po->row()->id;
        }

        $data = [
            "tipe" => $tipe,
            "note" => $note,
            "po_ref" => $po_ref,
            "updated_at"    => $this->created_at,
            "updated_by"    => $this->userid
        ];
        $this->model_spk->update_po($data, $id_po);
        $this->session->set_flashdata("pesan_success", "Update Tipe PO / Note / PO Ref Berhasil");
        redirect('spk/list_order_detail/'.$signature);
    }

    public function form_generate_po_outstanding()
    {
        if ($this->session->userdata('username') <> 'melinda' && $this->session->userdata('username') <> 'tria' && $this->session->userdata('username') <> 'suffy' && $this->session->userdata('username') <> 'fakhrul')
        {
            $this->session->set_flashdata("pesan", "Delete Gagal. User anda tidak diizinkan me-rilis po");
            redirect('spk');
        }

        $data = [
            'title'             => 'Generate PO Outstanding',
            'url'               => 'spk/form_generate_po_outstanding_proses',
            'get_data'          => $this->model_spk->get_log_po_outstanding(),
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/form_generate_po_outstanding', $data);
        $this->load->view('kalimantan/footer');

    }

    public function form_generate_po_outstanding_proses()
    {
        $tahun = $this->input->post("tahun");

        $signature = 'po-outstanding-' . rand() . md5($this->created_at) . date('Ymd');

        $data = [
            "created_at"    => $this->created_at,
            "created_by"    => $this->userid,
            "signature"     => $signature
        ];
        $this->model_spk->insert_log_po_outstanding($data);
        $id_log = $this->db->insert_id();

        $data = [
            "status"        => 1,
            "created_date"  => $this->created_at,
            "created_by"    => $this->userid
        ];
        $lock = $this->model_spk->update_lock_po($data, 77);

        $this->model_spk->truncate_delete_po_outstanding($tahun);

        $this->model_spk->insert_do_deltomed_by_tahun($tahun);

        $this->model_spk->insert_do_us_by_tahun($tahun);

        $this->model_spk->insert_po_by_tahun($tahun);

        $this->model_spk->insert_po_outstanding_deltomed($tahun);

        $this->model_spk->insert_po_outstanding_us($tahun);

        $this->model_spk->insert_po_outstanding_intrafood($tahun);

        $this->model_spk->insert_po_outstanding_marguna($tahun);

        $this->model_spk->insert_po_outstanding_jaya($tahun);

        $this->model_spk->insert_po_outstanding_strive($tahun);

        $this->model_spk->insert_po_outstanding_hni($tahun);

        $this->model_spk->insert_po_outstanding_mdj($tahun);

        $data = [
            "status"        => 0,
            "created_date"  => $this->created_at,
            "created_by"    => $this->userid
        ];
        $lock = $this->model_spk->update_lock_po($data, 77);

        $data = [
            "finished_at"   => $this->model_outlet_transaksi->timezone(),
        ];
        $this->model_spk->update_log_po_outstanding($data, $id_log);

        $this->session->set_flashdata("pesan_success", "Generate PO Outstanding Berhasil");
        redirect('spk/form_generate_po_outstanding');
    }

    public function master_principal()
    {
        $data = [
            'title'             => 'Master Principal',
            'url'               => 'spk/master_principal_proses',
            'get_data'          => $this->model_spk->get_master_principal(),
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/master_principal', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_principal_proses()
    {
        $id = $this->input->post('options');
        $count = count($id);
        $email_po = $this->input->post('email_po');
        $email_retur = $this->input->post('email_retur');
        $prefix_po = $this->input->post('prefix_po');

        for ($i=0; $i < $count ; $i++)
        {
            $id_supp = $id[$i];
            $params_email_po = $email_po[$id_supp];
            $params_email_retur = $email_retur[$id_supp];
            $params_prefix_po = $prefix_po[$id_supp];

            $data = [
                "email"         => $params_email_po,
                "email_retur"   => $params_email_retur,
                "prefix_po"     => $params_prefix_po
            ];
            $this->model_spk->update_master_principal($data, $id_supp);
        }

        $this->session->set_flashdata("pesan_success", "Update Master Principal Berhasil");
        redirect('spk/master_principal');

    }

    public function master_produk()
    {
        $data = [
            'title'             => 'Master Produk',
            'url'               => 'spk/master_produk_proses',
            'get_data'          => $this->model_spk->get_master_produk(),
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/master_produk', $data);
        $this->load->view('kalimantan/footer');
    }

    public function master_produk_proses()
    {
        $id = $this->input->post('options');
        $count = count($id);
        $namaprod = $this->input->post('namaprod');
        $isisatuan = $this->input->post('isisatuan');
        $qty1 = $this->input->post('qty1');
        $qty2 = $this->input->post('qty2');
        $qty3 = $this->input->post('qty3');

        for ($i=0; $i < $count ; $i++)
        {
            $id_produk = $id[$i];
            $params_namaprod = $namaprod[$id_produk];
            $params_isisatuan = $isisatuan[$id_produk];
            $params_qty1 = $qty1[$id_produk];
            $params_qty2 = $qty2[$id_produk];
            $params_qty3 = $qty3[$id_produk];

            $data = [
                "namaprod"      => $params_namaprod,
                "isisatuan"     => $params_isisatuan,
                "qty1"          => $params_qty1,
                "qty2"          => $params_qty2,
                "qty3"          => $params_qty3
            ];
            $this->model_spk->update_master_produk($data, $id_produk);
        }

        $this->session->set_flashdata("pesan_success", "Update Master Produk Berhasil");
        redirect('spk/master_produk');

    }

    public function email_po($signature)
    {
        // echo "Signature : ".$signature;

        $this->load->model('model_relokasi');

        $get_po_by_signature = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po_by_signature -> num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('spk/list_order_detail/'.$signature);
        }

        $supp = $get_po_by_signature->row()->supp;
        $email = $get_po_by_signature->row()->email;
        $nopo  = $get_po_by_signature->row()->nopo;
        $company = $get_po_by_signature->row()->company;
        $id_po = $get_po_by_signature->row()->id;
        $branch_name = $get_po_by_signature->row()->branch_name;
        $nama_comp = $get_po_by_signature->row()->nama_comp;
        $kode_alamat = $get_po_by_signature->row()->kode_alamat;
        $namasupp = $get_po_by_signature->row()->namasupp;
        $tipe = ($get_po_by_signature->row()->tipe) == "S" ? "SPK" : "ALOKASI";
        $npwp = $get_po_by_signature->row()->npwp;
        $alamat_kirim = $get_po_by_signature->row()->alamat_kirim;
        $alamat = $get_po_by_signature->row()->alamat;

        $this->model_relokasi->email();

        $get_email_principal = $this->model_spk->get_supplier_by_supp($supp);
        if (!$get_email_principal -> num_rows() > 0) {
            $email_principal = "";
        }else{
            $email_principal = $get_email_principal->row()->email;
        }

        $from   = "tria@muliaputramandiri.com";
        $to     = $email_principal;
        // $to     = 'melinda.yanuar@gmail.com';
        $cc     = $this->email_tim.",".$email;
        // $cc     = 'melinda.yanuar@gmail.com';

        $data = [
            "nopo"  => $nopo,
            "id_po" => $id_po,
            "branch_name"   => $branch_name,
            "nama_comp"     => $nama_comp,
            "namasupp"      => $namasupp,
            "kode_alamat"   => $kode_alamat,
            "tipe"          => $tipe,
            "company"       => $company,
            "npwp"          => $npwp,
            "alamat_kirim"  => $alamat_kirim,
            "alamat"        => $alamat,
            "get_po_detail" => $this->model_spk->get_po_detail_by_id_po($id_po)
        ];

        $message = $this->load->view("spk/email_po",$data,TRUE);
        $subject = "MPM SITE | PO : $nopo | ".$company;

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger(); die;
        if ($send)
        {
            $this->session->set_flashdata("pesan_success", "Send Email PO Berhasil");
            redirect('spk/list_order_detail/'.$signature);
        }else{
            $this->session->set_flashdata("pesan", "Send Email PO Gagal");

            redirect('spk/list_order_detail/'.$signature);
        }
    }

    public function update_karton_po_detail()
    {
        $banyak_karton = $this->input->post('banyak_karton');
        $isisatuan = $this->input->post('isisatuan');
        $signature = $this->input->post('signature');
        $id_po_detail = $this->input->post('id_po_detail');

        $get_po_by_signature = $this->model_spk->get_po_by_signature($signature);
        if (!$get_po_by_signature -> num_rows() > 0)
        {
            $this->session->set_flashdata("pesan", "Data not found");
            redirect('spk/list_order_detail/'.$signature);
        }else{
            $id_ref = $get_po_by_signature->row()->id;
        }

        $data = [
            'banyak_karton' => $banyak_karton,
            'banyak'        => $isisatuan * $banyak_karton,
            'updated_at'    => $this->created_at,
            'updated_by'    => $this->userid
        ];

        // echo "signature : ".$signature;
        // echo "id_po_detail : ".$id_po_detail;
        // echo "banyak_karton : ".$banyak_karton;

        // die;

        $this->model_spk->update_po_detail($data, $id_po_detail);

        $get_total = $this->model_spk->get_total_value_po_detail_by_id_ref($id_ref);
        if (!$get_total->num_rows() > 0) {
            $total_value = 0;
        }else{
            $total_value = $get_total->row()->total_value;
        }

        $data = [
            "total_value"   => $total_value
        ];
        $this->model_spk->update_po($data, $id_ref);

        $this->session->set_flashdata("pesan_success", "Update data berhasil");
        redirect('spk/list_order_detail/'.$signature);

    }

    public function purchase_plan()
    {
        $data = [
            'title' => 'Purchase Plan',
            'url_import'   => 'spk/import_purchase_plan',
            'url'   => 'spk/updated_purchase_plan',
            'data_purchaseplan' => $this->model_spk->get_purchase_plan()
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/purchase_plan', $data);
        $this->load->view('kalimantan/footer');
    }

    public function template_purchase_plan()
    {
        $query = "
            select '' as bulan, '' as site_code, '' as kodeprod, '' as pp_unit
        ";
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'bulan (yyyy-mm)', 'site_code', 'kodeprod', 'pp_unit'
        ));
        $this->excel_generator->set_column(array
        (
            'bulan', 'site_code', 'kodeprod', 'pp_unit'
        ));
        $this->excel_generator->set_width(array(15,15,15,15));
        $this->excel_generator->exportTo2007('Template Import Purchase Plan');
    }

    public function import_purchase_plan()
    {
        // $signature = 'import-spk-' . rand() . md5($this->created_at) . date('Ymd');
        if (!is_dir('./assets/uploads/spk/import/')) {
            @mkdir('./assets/uploads/spk/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/spk/import/';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = true;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/spk/import/$file_name");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1)
            {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('spk/purchase_plan','refresh');
            }

            foreach ($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++)
                {
                    $bulan      = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $site_code  = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $kodeprod   = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $pp_unit    = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());

                    // validasi kodeprod
                    if(strlen("$kodeprod") == '5')
                    {
                        $params_kodeprod = '0'.$kodeprod;
                    }else
                    {
                        $params_kodeprod = $kodeprod;
                    }

                    $get_kodeprod = $this->model_spk->get_tabprod_spk($params_kodeprod);
                    if (!$get_kodeprod->num_rows() < 0)
                    {
                        $this->session->set_flashdata("pesan", "masukkan kodeproduk yang sesuai dengan standar MPM. Anda memasukkan kodeproduk : $kodeprod");
                        redirect('spk/purchase_plan', 'refresh');
                        die;
                    }

                    // cek apakah sudah ada di purchase plan
                    $get_data = $this->model_spk->get_purchase_plan_with_bulan_sitecode_kodeprod($bulan, $site_code, $params_kodeprod);

                    // cek kodeproduk exists
                    if ($get_data->num_rows() < 1)
                    {
                        $data = [
                            "bulan"         => $bulan,
                            "site_code"     => $site_code,
                            "kodeprod"      => $params_kodeprod,
                            "pp_unit"       => $pp_unit,
                            "created_at"    => $this->created_at,
                            "created_by"    => $this->session->userdata('id')
                        ];
                        $this->db->insert('site.spk_purchase_plan', $data);
                    }
                }
                $this->session->set_flashdata("pesan_success", "Import data selesai.");
                redirect('spk/purchase_plan');
            }
        }else{
            $this->session->set_flashdata("pesan", "File yang anda upload bukan file excel.");
            redirect('spk/purchase_plan');
        }
    }

    public function updated_purchase_plan()
    {
        $checklist  = $this->input->post('options');

        for ($i=0; $i < count($checklist); $i++) {
            $data   = [
                'pp_unit' => $this->input->post("$checklist[$i]")
            ];
            $this->model_spk->update_spk_purchase_plan($data, $checklist[$i]);
        }

        $this->session->set_flashdata("pesan_success", "Update data berhasil");
        redirect('spk/purchase_plan/');
    }

    public function update_pp_po($signature)
    {
        // start ambil data mpm po untuk ambil
        $data_po = $this->model_spk->get_po_with_signature($signature);
        // end ambil data mpm po untuk ambil

        // start variable berdasarkan data mpm po
        $data = [
            'id_po' => $data_po->row('id'),
            'tahun' => date('Y', strtotime($data_po->row('tglpesan'))),
            'bulan' => date('m', strtotime($data_po->row('tglpesan'))),
            'periode' => date('Y-m', strtotime($data_po->row('tglpesan'))),
            'site_code' => $data_po->row('kode_alamat'),
        ];

        // var_dump($data);
        // die;
        // end variable berdasarkan data mpm po

        // start update po_detail
        $this->model_spk->update_po_detail_actual_po_bulan($data);
        $this->model_spk->update_po_detail_pp_unit_and_selisih_po($data);
        // end update po_detail

        // die;

        // start update po is_pp_approval
        $this->model_spk->update_po_is_pp_approval($data);
        // end update po is_pp_approval

        // die;
    }

    public function approv_pp()
    {
        $signature = $this->input->post('signature');
        $userid = $this->input->post('user');

        if (!is_dir('./assets/uploads/spk/approval_pp/')) {
            @mkdir('./assets/uploads/spk/approval_pp/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/uploads/spk/approval_pp/';
        $config['allowed_types'] = '*';
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
            $data = [
                'pp_status' => '1',
                'pp_approved_file' => $filename,
                'pp_approved_by' => $userid,
                'pp_approved_date' => $this->created_at,
            ];

            $this->db->where('signature', $signature);
            $this->db->update('mpm.po',$data);

            $this->session->set_flashdata("pesan_success", "Upload file berhasil.");
            redirect("spk/list_order_detail/$signature");
        }else{
            $this->session->set_flashdata("pesan", "File yang anda upload tidak terdeteksi.");
            redirect("spk/list_order_detail/$signature");
        }
    }

    public function master_user()
    {
        $curl = curl_init();
        $api_url = getenv('API_URL');
        $token = getenv('API_TOKEN');
        $api_key = getenv('API_KEY');
        $userid = $this->session->userdata('id');   

        $params = array(
            'token'     => $token,
            'X-API-KEY' => $api_key,
            'userid'    => $userid,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url . "restapi/api/master_data/master_user_mpm?" . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataalasan = $array_response['data'];
            // var_dump($dataalasan);die;
            echo "<option value=''> -- Pilih User -- </option>";

            foreach ($dataalasan as $key => $a)
            {
                echo "<option value='". $a["id"] . "' >";
                echo $a["username"].' - '. $a["email"];
                echo "</option>";
            }
        }
    }

    public function po_outstanding()
    {        

        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $principal = $this->input->post('principal');
        // echo "from : ".$from;
        // echo "to : ".$to;
        // echo "principal : ".$principal;

        if ($from && $to && $principal) {
            
            $site_code = $this->model_spk->get_site_code($this->session->userdata('id'));

            if ($principal == '001') {
                return $this->export_deltomed('po_outstanding_deltomed '.$from.'-'.$to, $site_code, $from, $to);
            }else if ($principal == '005') {
                return $this->export_us('po_outstanding_us '.$from.'-'.$to, $site_code, $from, $to);
            }else{
                return $this->export('po_outstanding '.$from.'-'.$to, $site_code, $from, $to);
            }
        }        

        $data = [
            'title'     => 'PO Outstanding',
            'url'       => 'spk/po_outstanding',
            'get_data' => '',
            'get_principal' => $this->model_spk->get_principal($this->session->userdata('supp')),
        ];
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_claim/css');
        $this->load->view('spk/po_outstanding', $data);
        $this->load->view('kalimantan/footer');
    }

    public function export_deltomed($filename, $site_code, $from, $to)
    {
        $query = "
            select 	a.branch_name, a.nama_comp, a.company, a.tglpo, a.nopo, a.tipe,
                    a.kodeprod, a.namaprod, a.qty_po, a.qty_pemenuhan, a.harga,
                    a.value_po, a.value_pemenuhan, a.berat, a.volume,
                    a.fulfilment, a.leadtime_proses_do,
                    a.po_ref, a.note, a.tanggal_terima, a.leadtime_proses_kirim,
                    a.outstanding_po, a.kode_alamat, a.data_surat_jalan
            from db_po_new.t_po_outstanding_deltomed a 
            where date(a.tglpo) between '$from' and '$to' and a.kode_alamat in ($site_code)
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'branch_name', 'nama_comp', 'company', 'tglpo', 'nopo', 'tipe',
            'kodeprod', 'namaprod', 'qty_po', 'qty_pemenuhan', 'harga',
            'value_po', 'value_pemenuhan', 'berat', 'volume',
            'fulfilment', 'leadtime_proses_do',
            'po_ref', 'note', 'tanggal_terima', 'leadtime_proses_kirim',
            'outstanding_po', 'kode_alamat', 'data_surat_jalan'
        ));
        $this->excel_generator->set_column(array
        ( 
            'branch_name', 'nama_comp', 'company', 'tglpo', 'nopo', 'tipe',
            'kodeprod', 'namaprod', 'qty_po', 'qty_pemenuhan', 'harga',
            'value_po', 'value_pemenuhan', 'berat', 'volume',
            'fulfilment', 'leadtime_proses_do',
            'po_ref', 'note', 'tanggal_terima', 'leadtime_proses_kirim',
            'outstanding_po', 'kode_alamat', 'data_surat_jalan'
        ));
        $this->excel_generator->set_width(array(15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15)); 
        $this->excel_generator->exportTo2007($filename); 

    }

    public function export_us($filename, $site_code, $from, $to)
    {
        $query = "
            select 	a.branch_name, a.nama_comp, a.company, a.tglpo, a.nopo, a.tipe,
                    a.kodeprod, a.namaprod, a.qty_po, a.qty_pemenuhan, a.harga,
                    a.value_po, a.value_pemenuhan, a.berat, a.volume,
                    a.fulfilment, a.leadtime_proses_do,
                    a.po_ref, a.note, a.tanggal_terima, a.leadtime_proses_kirim,
                    a.outstanding_po, a.kode_alamat, a.data_surat_jalan
            from db_po_new.t_po_outstanding_us a 
            where date(a.tglpo) between '$from' and '$to' and a.kode_alamat in ($site_code)
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'branch_name', 'nama_comp', 'company', 'tglpo', 'nopo', 'tipe',
            'kodeprod', 'namaprod', 'qty_po', 'qty_pemenuhan', 'harga',
            'value_po', 'value_pemenuhan', 'berat', 'volume',
            'fulfilment', 'leadtime_proses_do',
            'po_ref', 'note', 'tanggal_terima', 'leadtime_proses_kirim',
            'outstanding_po', 'kode_alamat', 'data_surat_jalan'
        ));
        $this->excel_generator->set_column(array
        ( 
            'branch_name', 'nama_comp', 'company', 'tglpo', 'nopo', 'tipe',
            'kodeprod', 'namaprod', 'qty_po', 'qty_pemenuhan', 'harga',
            'value_po', 'value_pemenuhan', 'berat', 'volume',
            'fulfilment', 'leadtime_proses_do',
            'po_ref', 'note', 'tanggal_terima', 'leadtime_proses_kirim',
            'outstanding_po', 'kode_alamat', 'data_surat_jalan'
        ));
        $this->excel_generator->set_width(array(15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15)); 
        $this->excel_generator->exportTo2007($filename); 

    }

    public function export($filename, $site_code, $from, $to)
    {
        $query = "
            select 	a.principal, a.branch_name, a.nama_comp, a.company, a.tglpo, a.nopo, a.tipe,
                    a.kodeprod, a.namaprod, a.qty_po, a.qty_pemenuhan, a.harga,
                    a.value_po, a.value_pemenuhan, a.berat, a.volume,
                    a.fulfilment, a.leadtime_proses_do,
                    a.po_ref, a.note, a.tanggal_terima, a.leadtime_proses_kirim,
                    a.outstanding_po, a.kode_alamat, a.data_surat_jalan
            from db_po_new.t_po_outstanding_all a 
            where date(a.tglpo) between '$from' and '$to' and a.kode_alamat in ($site_code)
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'principal', 'branch_name', 'nama_comp', 'company', 'tglpo', 'nopo', 'tipe',
            'kodeprod', 'namaprod', 'qty_po', 'qty_pemenuhan', 'harga',
            'value_po', 'value_pemenuhan', 'berat', 'volume',
            'fulfilment', 'leadtime_proses_do',
            'po_ref', 'note', 'tanggal_terima', 'leadtime_proses_kirim',
            'outstanding_po', 'kode_alamat', 'data_surat_jalan'
        ));
        $this->excel_generator->set_column(array
        ( 
            'principal', 'branch_name', 'nama_comp', 'company', 'tglpo', 'nopo', 'tipe',
            'kodeprod', 'namaprod', 'qty_po', 'qty_pemenuhan', 'harga',
            'value_po', 'value_pemenuhan', 'berat', 'volume',
            'fulfilment', 'leadtime_proses_do',
            'po_ref', 'note', 'tanggal_terima', 'leadtime_proses_kirim',
            'outstanding_po', 'kode_alamat', 'data_surat_jalan'
        ));
        $this->excel_generator->set_width(array(15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15)); 
        $this->excel_generator->exportTo2007($filename); 

    }
}
?>
