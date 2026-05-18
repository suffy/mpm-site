<style>
    body {
        background-image: linear-gradient(to right, #ffe6e6, rgb(226, 236, 226), rgb(213, 238, 248));
        height: 100vh;
        width: 100vw;
    }

    td {
        font-size: 13px;
    }

    .select2-selection {
        font-size: 13px;
        border-radius: 0 !important;
        border: solid 1px #c4c4c4 !important;
        padding-left: 4px;
        color: #000;
        background-color: #000;
    }

    .select2-selection__choice {
        background-color: #CAF1FF !important;
        color: #333 !important;
        border: none !important;
        border-radius: 3px !important;
    }
</style>

</div>
<?php $this->load->view('management_claim/css/style') ?>
<div class="container-fluid">

    <?php echo form_open($url); ?>


    <?php
    // var_dump($principal);
    // var_dump($source);
    // die;
    ?>


    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <?php
            if ($this->session->flashdata('pesan')) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
            <?php
            } elseif ($this->session->flashdata('pesan_success')) { ?>
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="card col-md-7">
        <div class="card-body">
            <!-- <div><h5 class="card-title"><?= $title ?></h5></div>      -->
            <div>
                <h5 class="card-title">Periode Sales</h5>
            </div>

            <div class="mt-4 mb-4">
                <span style="font-size: 12px; color: #c4c4c4; font-style: italic; font-weight: bold;">Jika ingin ke menu lama, klik tombol berikut</span>
                <a href="<?= base_url() . 'sales_omzet/sell_out_product' ?>" class="btn-submit-cream" style="padding: 5px 5px 5px 5px; color: #000; font-weight: bold;">versi lama</a>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label for="periode" class="form-label">Periode</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="date" name="from" id="from" min="2024-01-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                        <input type="date" name="to" id="to" min="2024-01-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
                    </div>
                </div>
            </div>

            <!-- <div class="row mt-3">
            <div class="col-md-2">
                <label for="breakdown" class="form-label">Breakdown</label>
            </div>
            <div class="col-md-5">
                <select name="breakdown" id="breakdown" class="form-control" required>
                    <option value="" selected> -- Pilih breakdown --</option>
                    <option value="v1">subbranch, bulan</option>
                    <option value="v2">subbranch, bulan, kodeproduk</option>
                    <option value="v3">subbranch, bulan, kodeproduk, class, tipe</option>
                </select>
            </div>
        </div> -->

            <!-- <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-center align-items-center">
                <div class="col-md-2">
                    <hr style="border: dotted 1px #c4c4c4;">
                </div>
                <div class="col-md-10" style="font-size: 12px; color: #c4c4c4; font-style: italic; font-weight: bold;">
                    source data bersifat dinamis disesuaikan dengan breakdown pilihan anda
                </div>
            </div>
        </div> -->

            <!-- <div class="row">
            <div class="col-md-2">
                <label for="source" class="form-label">Source</label>
            </div>
            <div class="col-md-5">
                <select name="source" id="source" class="form-control" required>
                    <option value="" selected> -- choose source data based on your selected breakdown --</option>
                </select>
            </div>
        </div>   -->

        </div>
    </div>

    <div class="card mt-4 col-md-7">
        <div class="card-body">

            <div>
                <h5 class="card-title">Breadown & Data Source</h5>
            </div>

            <div class="row mt-4">
                <div class="col-md-3">
                    <label for="breakdown" class="form-label">Breakdown</label>
                </div>
                <div class="col-md-8">
                    <select name="breakdown" id="breakdown" class="form-control" required>
                        <option value="" selected> -- Pilih breakdown --</option>
                        <option value="v1">subbranch, bulan</option>
                        <option value="v2">subbranch, bulan, kodeproduk</option>
                        <option value="v3">subbranch, bulan, kodeproduk, class, tipe</option>
                    </select>
                </div>
            </div>

            <!-- <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-center align-items-center">
                <div class="col-md-2">
                    <hr style="border: dotted 1px #c4c4c4;">
                </div>
                <div class="col-md-10" style="font-size: 12px; color: #c4c4c4; font-style: italic; font-weight: bold;">
                    source data bersifat dinamis disesuaikan dengan breakdown pilihan anda
                </div>
            </div>
        </div> -->

            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="source" class="form-label">Source</label>
                </div>
                <div class="col-md-8">
                    <select name="source" id="source" class="form-control" required>
                        <option value="" selected> -- choose source data based on your selected breakdown --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 mb-5">
        <div class="card-body">
            <h5 class="card-title"><?= $title2 ?></h5>

            <div class="card-block mt-4 mb-1">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tabel-produk" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 1%">
                                        <!-- <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" style="color: black; background-color: grey"> -->
                                        <input class="form-check-input" type="checkbox" value="" id="select-all">
                                        <br>
                                        <label class="form-check-label" for="select-all">
                                            Click All
                                        </label>
                                    </th>
                                    <th class="text-center" style="width: 20%">Kodeproduk</th>
                                    <th class="text-center" style="width: 20%">Principal</th>
                                    <th class="text-center" style="width: 10%">Group</th>
                                    <th class="text-center" style="width: 10%">SubGroup</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($kodeprod->result() as $a) : ?>
                                    <tr>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="<?= $a->kodeprod; ?>" class="select-row" name="options[]" value="<?= $a->kodeprod; ?>">
                                            </center>
                                        </td>
                                        <td><?= $a->kodeprod . ' - ' . $a->namaprod ?></td>
                                        <td><?= $a->namasupp ?></td>
                                        <td><?= $a->nama_group ?></td>
                                        <td><?= $a->nama_sub_group ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <?php
                        if ($this->session->flashdata('pesan')) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $this->session->flashdata('pesan'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->flashdata('pesan_success')) { ?>
                            <div class="alert alert-success" role="alert">
                                <?= $this->session->flashdata('pesan_success'); ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <input type="submit" value="retrieve data" class="btn btn-submit-red" style="height: 45px; width: 130px;">
                        <!-- <input type="submit" value="generate data" class="btn btn-submit-orange" style="height: 45px;"> -->
                        <a href="<?= base_url() . 'management_sales/history_penarikan' ?>" class="btn btn-submit-black" style="height: 45px;width: 200px; padding-top: 10px;">history penarikan data</a>
                        <a href="<?= base_url() . 'sales_omzet/sell_out_product' ?>" class="btn btn-submit-black" style="height: 45px;width: 130px; padding-top: 10px;">versi lama</a>
                    </div>
                </div>


            </div>


        </div>
    </div>
    <?php echo form_close(); ?>
</div>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<!-- fungsi js select supp -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $(".principal").select2({
            placeholder: "-- Silahkan Pilih --"
        });

        $('#tabel-produk').DataTable({
            paging: false,
            scrollCollapse: true,
            scrollY: '500px'
        });

        // Inisialisasi DataTable
        var table = $('#tabel-produk').DataTable();

        // Array untuk menyimpan id baris yang dipilih
        var selectedRows = [];

        // check search
        // // Menambahkan event listener untuk perubahan pencarian
        // table.on('search.dt', function() {
        //     $('#select-all').prop('checked', false);
        // });

        // Select/Deselect all checkbox di seluruh halaman (termasuk hasil pencarian)
        $('#select-all').on('click', function() {
            var isChecked = this.checked;

            // Jika checkbox select-all dicentang
            if (isChecked) {
                // Pilih semua baris yang ditampilkan oleh DataTable (termasuk hasil pencarian)
                table.rows({
                    filter: 'applied'
                }).every(function() {
                    var row = this.node();
                    $(row).find('.select-row').prop('checked', true);
                    var rowIndex = this.index();
                    if (!selectedRows.includes(rowIndex)) {
                        selectedRows.push(rowIndex); // Menyimpan index baris yang dipilih
                    }
                });
            } else {
                // Jika checkbox select-all dibatalkan, kosongkan array selectedRows
                selectedRows = [];
                table.rows({
                    filter: 'applied'
                }).every(function() {
                    var row = this.node();
                    $(row).find('.select-row').prop('checked', false);
                });
            }
        });

        // Ketika checkbox per baris diubah
        $('#table-produk tbody').on('change', '.select-row', function() {
            var rowIndex = table.row($(this).closest('tr')).index();

            if (this.checked) {
                // Tambahkan baris yang dipilih ke array
                if (!selectedRows.includes(rowIndex)) {
                    selectedRows.push(rowIndex);
                }
            } else {
                // Hapus baris yang dibatalkan pemilihannya
                selectedRows = selectedRows.filter(function(index) {
                    return index !== rowIndex;
                });
            }

            // Update checkbox Select All berdasarkan baris yang dipilih
            if (selectedRows.length === table.rows({
                    filter: 'applied'
                }).count()) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
        });
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_sales/master_principal') ?>',
        data: '',
        success: function(result) {
            $("select[id = principal]").html(result);
        }
    });
</script>

<script>
    /*console.log('start')
    $(document).ready(function () {
        $('#tabel').DataTable({
            "pageLength": 5000,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 30, 40, 50, 60, 70, 80, -1],
                [10, 20, 30, 40, 50, 60, 70, 80, "All"]
            ],
            scrollX: true,
            // scrollCollapse: true
            scrollY: 500
        });
    });*/

    $("select[name = breakdown]").on("change", function() {
        let breakdown = document.getElementById('breakdown').value;
        console.log('breakdown ' + breakdown)

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('management_sales/master_source') ?>',
            data: {
                'breakdown': breakdown,
            },
            success: function(result) {
                $("select[name = source]").html(result);
            }
        });


    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>