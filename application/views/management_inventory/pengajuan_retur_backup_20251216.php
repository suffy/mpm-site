<style>
    .content {
        font-size: 12px;
    }
</style>
<?php
foreach ($site_code->result() as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $site[$a->site_code] = $a->branch_name . ' - ' . $a->nama_comp . ' (' . $a->site_code . ')';
}
?>

</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?= $title ?>
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
            <label for="batch_number" class="form-label">Subbranch</label>
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
                <label for="supp" class="form-label">Principal</label>
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
                <label for="supp" class="form-label">Principal</label>
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
                <label for="supp" class="form-label">Principal</label>
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
                <label for="supp" class="form-label">Principal</label>
            </div>
            <div class="col-md-4">
                <select id="supp" name="supp" class="form-select" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001-GT"> Deltomed - GT </option>
                    <option value="001-MTI"> Deltomed - MTI </option>
                    <?php if ($this->session->userdata('username') == 'GID' || $this->session->userdata('username') == 'JKT' || $this->session->userdata('username') == 'BGR' || $this->session->userdata('username') == 'TGR' || $this->session->userdata('username') == 'CKG') {
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
            <label for="tipe" class="form-label">Tipe</label>
        </div>
        <div class="col-md-4">
            <select id="tipe" name="tipe" class="form-select" required>
            </select>
        </div>
    </div>

    <div class="row mt-3" id="pic">
        <div class="col-md-2">
            <label for="nama" class="form-label">Nama Yang Mengajukan</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
    </div>

    <div class="row mt-3" id="signature">
        <div class="col-md-2">
            <label for="file" class="form-label">Manage Signature Digital</label>
        </div>
        <div class="col-md-4">


                <?php
                $file = './assets/uploads/signature/' . $this->session->userdata('username') . '-signature.png'; // 'images/'.$file (physical path)
                if (file_exists($file)) { ?>
                <div>
                    <a href="<?= base_url() ?>management_inventory/signature_digital" class="btn btn-outline-dark btn-sm"
                        target="_blank">
                        <img src="<?= base_url() . 'assets/uploads/signature/' . $this->session->userdata('username') . '-signature.png' ?>"
                            alt="<?= $this->session->userdata('username') . '-signature' ?>" width="150px">
                    </a>
                </div>
                <?php
                } else { ?>
                <div>
                    <a href="<?= base_url() ?>management_inventory/signature_digital" class="btn btn-outline-dark btn-sm"
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
        <div class="col-md-12 az-content-label text-center">
            History Pengajuan Retur
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
                            '<label for="file" id="label_attachment" class="form-label">Upload File Pendukung (wajib)</label>' +
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
                            '<label for="file" id="label_attachment" class="form-label">Upload File Pendukung (opsional)</label>' +
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