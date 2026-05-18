
<!-- <style>
    .content {
        font-size: 12px;
    }
</style> -->

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; justify-content: center; align-items: center; flex-direction: column; backdrop-filter: blur(3px);">
    <div style="text-align: center;">
        <div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem; border-width: 0.25em; color: #B43F3F !important;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p style="margin-top: 15px; color: #333; font-weight: 500; font-size: 16px;">Processing your request...</p>
        <p style="margin-top: 5px; color: #666; font-size: 14px;">Please wait</p>
    </div>
</div>

 <style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --success-color: #2ecc71;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --light-color: #ecf0f1;
        --dark-color: #34495e;
    }

    .content {
        font-size: 12px;
    }
    
    .info-box {
        background: linear-gradient(135deg, #3498db, #2c3e50);
        color: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .filter-section {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #ddd;
        padding: 8px 12px;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-weight: 500;
    }
    
    .btn-outline-secondary {
        border-radius: 6px;
        padding: 8px 16px;
        font-weight: 500;
    }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-processing {
        background-color: #cce7ff;
        color: #004085;
    }
    
    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .action-buttons .btn {
        margin-right: 5px;
        padding: 5px 10px;
        font-size: 0.85rem;
    }
    
    .deadline-warning {
        color: var(--danger-color);
        font-weight: 600;
    }
    
    .deadline-normal {
        color: var(--success-color);
        font-weight: 600;
    }
    
    .delete-btn {
        color: var(--danger-color);
        background: none;
        border: none;
        font-size: 1.1rem;
        cursor: pointer;
    }
    
    .delete-btn:hover {
        color: #c0392b;
    }
    
    /* @media (max-width: 768px) {
        .filter-section .col-md-3 {
            margin-bottom: 15px;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
        }
        
        .action-buttons .btn {
            margin-bottom: 5px;
            margin-right: 0;
        }
    } */
</style>

<?php
foreach ($site_code->result() as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $site[$a->site_code] = $a->branch_name . ' - ' . $a->nama_comp . ' (' . $a->site_code . ')';
}
?>

</div>

<div class="container-fluid mt-4">

    <div class="row">
        <div class="col-md-12">
            <h5 class="card-title"><?= $title ?></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            
            <?php
            if ($this->session->flashdata('pesan')) { ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
            <?php
            } elseif ($this->session->flashdata('pesan_success')) { ?>
                <div class="alert alert-success mt-3" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>



    <?= form_open_multipart($url); ?>
    <div class="row mt-4">
        <div class="col-md-2">
            <label for="batch_number">Subbranch</label>
        </div>
        <div class="col-md-4">
            <?php
            echo form_dropdown('site_code', $site, '', 'class="form-control"  id="site_code" required');
            ?>
        </div>
    </div>

    <?php
    if ($status_mpi == 1) { ?>

        <div class="row mt-3" id="principal">
            <div class="col-md-2">
                <label for="supp">Principal</label>
            </div>
            <div class="col-md-4">
                <select id="supp" name="supp" class="form-select" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001-GT-PHARMA"> Deltomed - GT - PHARMA </option>
                    <option value="001-NKA"> Deltomed - NKA </option>
                    <option value="001-herbana"> Deltomed - Herbana Herbamojo </option>
                    <!-- <option value="002"> Marguna </option> -->
                </select>
            </div>
        </div>

    <?php } else if ($status_penta == 1) { ?>

        <div class="row mt-3" id="principal">
            <div class="col-md-2">
                <label for="supp">Principal</label>
            </div>
            <div class="col-md-4">
                <select id="supp" name="supp" class="form-select" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001-GT-PHARMA"> Deltomed - GT - PHARMA </option>
                    <option value="001-NKA"> Deltomed - NKA </option>
                    <option value="001-herbana"> Deltomed - Herbana Herbamojo </option>
                    <!-- <option value="002"> Marguna </option> -->
                </select>
            </div>
        </div>

    <?php } else if ($status_surdon) { ?>

        <div class="row mt-3" id="principal">
            <div class="col-md-2">
                <label for="supp">Principal</label>
            </div>
            <div class="col-md-4">
                <select id="supp" name="supp" class="form-select" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001-RTD"> Deltomed - RTD </option>
                </select>
            </div>
        </div>


    <?php } else { ?>

        <div class="row mt-3" id="principal">
            <div class="col-md-2">
                <label for="supp">Principal</label>
            </div>
            <div class="col-md-4">
                <select id="supp" name="supp" class="form-select" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001-GT"> Deltomed - GT </option>
                    <!-- <option value="001-GT-PHARMA"> Deltomed - GT - PHARMA  </option> -->
                    <option value="001-MTI"> Deltomed - MTI </option>
                    <?php if ($this->session->userdata('username') == 'GID' || $this->session->userdata('username') == 'JKT' || $this->session->userdata('username') == 'BGR' || $this->session->userdata('username') == 'TGR' || $this->session->userdata('username') == 'CKG' || $this->session->userdata('username') == 'PV1') {
                        echo '<option value="001-NKA"> Deltomed - NKA </option>';
                    } else if ($this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'melinda') {
                        echo '<option value="001-RTD"> Deltomed - RTD </option>
                        <option value="001-herbana"> Deltomed - Herbana Herbamojo </option>';
                    } ?>
                    <option value="001-herbana"> Deltomed - Herbana Herbamojo </option>
                    <!-- <option value="002"> Marguna </option> -->
                    <option value="004"> Jaya Agung Makmur </option>
                    <option value="005"> Ultra Sakti </option>
                    <option value="012"> Intrafood </option>
                    <option value="013"> Strive </option>
                    <option value="015"> MDJ </option>
                </select>
            </div>
        </div>

    <?php
    } ?>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="tipe">Tipe</label>
        </div>
        <div class="col-md-4">
            <select id="tipe" name="tipe" class="form-select" required>
            </select>
        </div>
    </div>

    <div class="row mt-3" id="pic">
        <div class="col-md-2">
            <label for="nama">Nama Yang Mengajukan</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
    </div>

    <div class="row mt-3" id="signature">
        <div class="col-md-2">
            <label for="file">Manage Signature Digital</label>
        </div>
        <div class="col-md-4">


                <?php
                $file = './assets/uploads/signature/' . $this->session->userdata('username') . '-signature.png'; // 'images/'.$file (physical path)
                if (file_exists($file)) { ?>
                <div>
                    <a href="<?= base_url() ?>management_profile/signature" class="btn btn-outline-dark btn-sm"
                        target="_blank">
                        <img src="<?= base_url() . 'assets/uploads/signature/' . $this->session->userdata('username') . '-signature.png' ?>"
                            alt="<?= $this->session->userdata('username') . '-signature' ?>" width="150px">
                    </a>
                </div>
                <?php
                } else { ?>
                <div>
                    <a href="<?= base_url() ?>management_profile/signature" class="btn btn-outline-dark btn-sm"
                        target="_blank">
                        click here
                    </a>
                </div>
                <?php
                }
                ?>
        </div>
    </div>

    <?php 
    if ($status_mpi != 1 && $username != 'PENTA-10') { 
        ?>
    <div class="row mt-4">
        <div class="col-md-2">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-submit-red">Lanjut ke Pengisian Produk</button>
        </div>
    </div>
    <?php
    } ?>

    <?= form_close(); ?>
    <hr>

    <div class="row mt-5">
        <div class="col-md-12 text-center">
            <h4>History Pengajuan Retur</h4>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2"></i>Information</h5>
                    <ul class="mb-0 mt-2">
                        <li>Deadline barang sampai = 60 hari sejak approval dari principal HO</li>
                        <li>Sisa hari = deadline barang sampai - tanggal sekarang</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12">
            <div class="form-inline row">
                <div class="col-sm-12 text-center">
                    <form action="<?= $url_search ?>" method="GET">
                        From
                        <input class="form-control" type="date" name="from" value="<?= $this->input->get('from') ?>"
                            required />
                        To
                        <input class="form-control" type="date" name="to" value="<?= $this->input->get('to') ?>"
                            required />
                        <select name="status" class="form-control">
                            <option value="0" <?= $this->input->get('status') == 0 ? 'selected' : '' ?>> All Status
                            </option>
                            <option value="1" <?= $this->input->get('status') == 1 ? 'selected' : '' ?>> Pending DP
                            </option>
                            <option value="2" <?= $this->input->get('status') == 2 ? 'selected' : '' ?>> Pending MPM
                            </option>
                            <option value="3" <?= $this->input->get('status') == 3 ? 'selected' : '' ?>> Pending
                                Principal Area </option>
                            <option value="4" <?= $this->input->get('status') == 4 ? 'selected' : '' ?>> Pending
                                Principal HO </option>
                            <option value="5" <?= $this->input->get('status') == 5 ? 'selected' : '' ?>> Pending Kirim
                                Barang </option>
                            <option value="6" <?= $this->input->get('status') == 6 ? 'selected' : '' ?>> Pending Terima
                                Barang </option>
                            <option value="8" <?= $this->input->get('status') == 8 ? 'selected' : '' ?>> Barang di
                                Terima </option>
                            <option value="7" <?= $this->input->get('status') == 7 ? 'selected' : '' ?>> Pending
                                Pemusnahan </option>
                            <option value="9" <?= $this->input->get('status') == 9 ? 'selected' : '' ?>> Pemusnahan
                                Selesai </option>
                            <option value="10" <?= $this->input->get('status') == 10 ? 'selected' : '' ?>> Reject
                                Principal Ho </option>
                            <option value="11" <?= $this->input->get('status') == 11 ? 'selected' : '' ?>> Retur Sample
                            </option>
                            <option value="12" <?= $this->input->get('status') == 12 ? 'selected' : '' ?>> Pemusanahan Tervalidasi
                            </option>
                            <option value="13" <?= $this->input->get('status') == 13 ? 'selected' : '' ?>> Reject
                            </option>
                        </select>
                        <button type="submit" value="1" class="btn btn-outline-danger btn-sm" name="btn_type">Search</button>
                        <button type="submit" value="2" class="btn btn-outline-danger btn-sm" name="btn_type">Export To CSV</button>
                        <a href="<?= base_url() ?>management_inventory" class="btn btn-outline-dark btn-sm">Reset</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th>Tgl</th>
                        <th>No Retur</th>
                        <th>Tipe</th>
                        <th>Principal</th>
                        <th>Site</th>
                        <th>Status</th>
                        <th>Deadline Barang Sampai di Pabrik (Sisa Hari)</th>
                        <th>Del</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($get_pengajuan->result() as $a) : ?>
                        <tr>
                            <td class="content"><?= $a->tanggal_pengajuan ?></td>
                            <td class="content">
                                <a href="<?= base_url() . 'management_inventory/generate_pdf/' . $a->signature . '/' . $a->supp ?>"
                                    class="btn btn-submit-black"><?= ($a->no_pengajuan) ? $a->no_pengajuan : 'NULL'; ?></a>
                            </td>
                            <td class="content" style="text-transform: uppercase"><?= $a->tipe ?></td>
                            <td class="content"><?= $a->namasupp ?></td>
                            <td class="content"><?= $a->nama_comp ?></td>
                            <td class="content">
                                <?php
                                if ($a->status == 1) { // PROSES DP
                                    $color = "btn-info btn-sm rounded";
                                } elseif ($a->status == 2) { // PROSES MPM
                                    $color = "btn-warning btn-sm rounded";
                                } elseif ($a->status == 3) { // PROSES PRINCIPAL AREA
                                    $color = "btn-danger btn-sm rounded";
                                } elseif ($a->status == 4) { // PROSES PRINCIPAL HO
                                    $color = "btn-danger btn-sm rounded";
                                } elseif ($a->status == 5) { // PROSES KIRIM BARANG
                                    $color = "btn-info btn-sm rounded";
                                } elseif ($a->status == 6) { // PROSES TERIMA BARANG
                                    $color = "btn-danger btn-sm rounded";
                                } elseif ($a->status == 7) { // PROSES PEMUSNAHAN
                                    $color = "btn-info btn-sm rounded";
                                } elseif ($a->status == 8 || $a->status == 9 || $a->status == 12) { // BARANG DITERIMA dan Pemusnahan
                                    $color = "btn-dark btn-sm rounded";
                                } else {
                                    $color = "btn-info btn-sm rounded";
                                }

                                ?>
                                <a href="<?= base_url() . 'management_inventory/routing/' . $a->signature ?>"
                                    class="btn <?= $color ?> btn-sm"><?= $a->nama_status ?></a>
                            </td>
                            <td style="font-weight: bold;">
                                <?php
                                if ($a->status == 5) { ?>
                                    <?= $a->deadline_kirim_barang . ' (' . $a->sisa_hari . ' Hari)' ?>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($a->status == 1) { ?>
                                    <a href="<?= base_url() ?>management_inventory/delete_pengajuan/<?= $a->signature ?>" class="delete-button" onclick="return confirm('Hapus Pengajuan ini ?')" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
                                <?php
                                }
                                ?>
                            </td>
                            
                            <!-- <td>
                            <?php
                            if ($a->noseri) { ?>
                                    <a href="#" class="btn btn-primary">DONE</a>
                                <?php } else { ?>
                                    <i>belum tersedia</i>
                                <?php
                            }
                                ?>
                        </td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#example").DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
    });
</script>

<script>
    $("select[name = supp]").on("change", function() {
        var supp_terpilih = document.getElementById('supp').value;
        // console.log(supp_terpilih);
        $.ajax({
            type: 'POST',
            url: "<?= base_url('management_inventory/master_tipe'); ?>",
            data: {
                supp: supp_terpilih,
                username: '<?= $this->session->userdata('username'); ?>',
            },
            success: function(hasil_tipe) {
                $("select[name = tipe]").html(hasil_tipe);
            }
        });

        if (supp_terpilih == '001-NKA') {
            $("div#principal").after(
                '<div class="row mt-3" id="account">' +
                '<div class="col-md-2">' +
                '<label for="nama" class="form-label">Key Account</label>' +
                '</div>' +
                '<div class="col-md-4">' +
                '<Select class="form-select" name="key_account" id="key_account" required>' +
                '<option value=""> -- Pilih Account -- </option>' +
                '<?php foreach ($key_account->result() as $key) {
                        echo '<option value="'.$key->key_account. '">'.$key->key_account.' (pic : '.$key->username.' | email : '.$key->email.')'.'</option>';
                    } ?>' +
                '</Select>' +
                '</div>' +
                '</div>'
            );
            $("div#pic").after(
                '<div class="row mt-3" id="nrb">' +
                '<div class="col-md-2">' +
                '<label for="nama" class="form-label">Tanggal Nomor Registrasi Barang (NRB)</label>' +
                '</div>' +
                '<div class="col-md-4">' +
                '<input type="date" class="form-control" id="tgl_nrb" name="tgl_nrb" >' +
                '</div>' +
                '</div>'
            );
        } else {
            $("div#account").remove();
            $("div#nrb").remove();
        }
    });
</script>

<script>
    $("select[name = tipe]").on("change", function() {
        $("div#upload").remove();
        var tipe_terpilih = document.getElementById('tipe').value;
        var supp_terpilih = document.getElementById('supp').value;
        console.log(tipe_terpilih);
        if (tipe_terpilih == 'retur_administrasi') {
            $("div#signature").before(
                '<div class="row" id="upload">' +
                    '<div class="row mt-3">' +
                        '<div class="col-md-2">' +
                            '<label for="file" id="label_attachment" class="form-label">Upload Email Capture (Wajib)</label>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<input type="file" class="form-control" id="file1" name="file1" required>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row mt-3">' +
                        '<div class="col-md-2">' +
                            '<label for="file" id="label_attachment" class="form-label">Upload Tanda Terima (Wajib)</label>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<input type="file" class="form-control" id="file2" name="file2" required>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row mt-3">' +
                        '<div class="col-md-2">' +
                            '<label for="file" id="label_attachment" class="form-label">Upload foto (Wajib)</label>'+
                        '</div>'+
                        '<div class="col-md-4">'+
                            '<input type="file" class="form-control" id="file3" name="file3" required>'+
                        '</div>'+
                    '</div>'+
                '</div>'    
            )
        } else if (tipe_terpilih == 'retur_khusus') {
            $("div#signature").before(
                '<div class="row" id="upload">' +
                    '<div class="row mt-3">' +
                        '<div class="col-md-2">' +
                            '<label for="file" id="label_attachment" class="form-label">Upload Email Capture (Wajib)</label>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<input type="file" class="form-control" id="file1" name="file1" required>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row mt-3">' +
                        '<div class="col-md-2">' +
                            '<label for="file" id="label_attachment" class="form-label">Upload foto (Wajib)</label>'+
                        '</div>'+
                        '<div class="col-md-4">'+
                            '<input type="file" class="form-control" id="file3" name="file3" required>'+
                        '</div>'+
                    '</div>'+
                '</div>'    
            )
        } else if (supp_terpilih == '001-NKA') {
            $("div#signature").before(
                '<div class="row" id="upload">' +
                    '<div class="row mt-3">' +
                        '<div class="col-md-2">' +
                            '<label for="file" id="label_attachment">Upload File Pendukung (wajib)</label>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<input type="file" class="form-control" id="file1" name="file1" required>' +
                        '</div>' +
                    '</div>' +
                '</div>'    
            )
        } else {
            $("div#signature").before(
                '<div class="row" id="upload">' +
                    '<div class="row mt-3">' +
                        '<div class="col-md-2">' +
                            '<label for="file" id="label_attachment">Upload File Pendukung (opsional)</label>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<input type="file" class="form-control" id="file1" name="file1">' +
                        '</div>' +
                    '</div>' +
                '</div>'    
            )
        }
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<!-- <script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script> -->

<script>
// Fungsi untuk menampilkan loading overlay
function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

// Fungsi untuk menyembunyikan loading overlay
function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// Event listener untuk form submit (pengajuan retur)
document.addEventListener('DOMContentLoaded', function() {
    // Ambil semua form yang ada di halaman
    var forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Cek apakah submit berasal dari tombol "Lanjut ke Pengisian Produk"
            var submitter = e.submitter;
            if (submitter && submitter.type === 'submit') {
                // Validasi form jika diperlukan (opsional)
                var requiredFields = form.querySelectorAll('[required]');
                var isValid = true;
                
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        isValid = false;
                    }
                });
                
                if (isValid) {
                    showLoading();
                }
            }
        });
    });
    
    // Sembunyikan loading saat halaman selesai dimuat
    hideLoading();
    
    // Sembunyikan loading saat user menggunakan tombol back browser
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoading();
        }
    });
});

// Tambahkan loading untuk link-link yang ada
document.addEventListener('DOMContentLoaded', function() {
    // Untuk link Generate PDF, Routing, Delete, dll
    var links = document.querySelectorAll('a:not([href*="javascript"]):not([target="_blank"])');
    
    links.forEach(function(link) {
        // Skip link yang memiliki target blank atau link yang tidak menuju ke halaman lain
        if (link.target !== '_blank' && link.href && !link.href.startsWith('#')) {
            // Jangan tambahkan loading untuk link yang menuju ke file download
            if (!link.href.includes('.pdf') && !link.href.includes('.xlsx') && !link.href.includes('.csv')) {
                link.addEventListener('click', function(e) {
                    // Tampilkan loading hanya jika link tersebut membawa ke halaman lain
                    showLoading();
                });
            }
        }
    });
    
    // Khusus untuk tombol Search dan Export To CSV
    var searchButton = document.querySelector('button[name="btn_type"][value="1"]');
    var exportButton = document.querySelector('button[name="btn_type"][value="2"]');
    
    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            // Validasi form search
            var fromDate = document.querySelector('input[name="from"]');
            var toDate = document.querySelector('input[name="to"]');
            
            if (fromDate && toDate && fromDate.value && toDate.value) {
                showLoading();
            }
        });
    }
    
    if (exportButton) {
        exportButton.addEventListener('click', function(e) {
            // Validasi form search untuk export
            var fromDate = document.querySelector('input[name="from"]');
            var toDate = document.querySelector('input[name="to"]');
            
            if (fromDate && toDate && fromDate.value && toDate.value) {
                showLoading();
            }
        });
    }
    
    // Untuk tombol Reset
    var resetButton = document.querySelector('a[href*="management_inventory"]');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            showLoading();
        });
    }
});
</script>