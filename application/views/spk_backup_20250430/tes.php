<style>
    body {
        background: linear-gradient(45deg, #ffffff 0%, #f0f8ff 100%);
    }
</style>

</div>

< class="container-fluid">

    <div class="az-content">
        <div class="container-fluid">
            <?php
            if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'fakhrul') { ?>
                <?= $this->load->view('spk/component/sidebar_admin'); ?>
            <?php
            } else { ?>
                <?= $this->load->view('spk/component/sidebar'); ?>
            <?php
            }
            ?>

            <div class="az-content-body pd-lg-l-40 d-flex flex-column">
                <h2 class="az-content-title" id="form_spk"><?= $title; ?></h2>
                <div class="row">
                    <div class="col-md-12">
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

                <?php echo form_open_multipart($url_search_produk); ?>
                <!-- <form action="<?= $url_search_produk ?>"> -->

                <div class="row mt-3">
                    <div class="col-md-2">
                        <label for="supp" class="form-label">Principal</label>
                    </div>
                    <div class="col-md-4">
                        <select id="supp" name="supp" class="form-control" required>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-4 d-flex flex-row">
                        <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Tampilkan Produk</button>
                        <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                            ... Please wait ...
                        </button>
                    </div>
                </div>

                <?php echo form_close(); ?>

                <?php echo form_open($url_tambah_produk); ?>

                <div class="row mt-1">
                    <div class="col-md-12 mt-4">
                        <table id="tabel-produk" class="datatable" style="display: inline-block; overflow-y: scroll; height:600px;">
                            <thead>
                                <tr>
                                    <th class="text-center col-1" style="background-color: #1d1d1d; color: white;">
                                        <input class="form-check-input" type="checkbox" value="" id="select-all">
                                        <br>
                                        <label class="form-check-label" for="select-all">
                                            Click All
                                        </label>
                                    </th>
                                    <th>Principal</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah Karton</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($get_produk->result() as $a) : ?>
                                    <tr>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="<?= $a->kodeprod; ?>" class="select-row" name="options[]" value="<?= $a->id; ?>">

                                            </center>
                                        </td>
                                        <td><?= $a->namasupp ?></td>
                                        <td>
                                            <?= $a->kodeprod ?>
                                            <input type="text" name="kodeprod[<?= $a->id ?>]" class="form-control" value="<?= $a->kodeprod ?>" hidden>
                                        </td>
                                        <td><?= $a->namaprod ?></td>
                                        <td><input type="number" name="jml_karton[<?= $a->id ?>]" class="form-control" min="0" placeholder="Jumlah Karton"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <input type="hidden" name="supp" value="<?= $supp; ?>">

                <div class="row mb-5 mt-3">
                    <div class="col-lg-12 d-flex justify-content-center btn-group">
                        <input type="submit" value="Proses Semua Produk Yang Anda Pilih" class="btn btn-submit-orange" style="width: 100%;" onclick="return ValidateCompare()">
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var table = $('#tabel-produk').DataTable();

        // Array untuk menyimpan id baris yang dipilih
        var selectedRows = [];

        // Select/Deselect all checkbox di seluruh halaman
        $('#select-all').on('click', function() {
            var isChecked = this.checked;

            // Jika checkbox select-all dicentang
            if (isChecked) {
                // Pilih semua baris di seluruh halaman
                table.rows().every(function() {
                    var row = this.node();
                    $(row).find('.select-row').prop('checked', true);
                    selectedRows.push(this.index()); // Menyimpan index baris yang dipilih
                });
            } else {
                // Jika checkbox select-all dibatalkan, kosongkan array selectedRows
                selectedRows = [];
                table.rows().every(function() {
                    var row = this.node();
                    $(row).find('.select-row').prop('checked', false);
                });
            }
        });

        // Ketika checkbox per baris berubah
        $('#tabel-produk tbody').on('change', '.select-row', function() {
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

            // Update checkbox Select All
            if (selectedRows.length === table.rows().count()) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
        });
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('spk/master_supp') ?>',
        data: '',
        success: function(result) {
            $("select[name = supp]").html(result);
        }
    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
    function ValidateCompare() {
        var c = document.getElementsByName("options[]");
        var count = 0;
        for (var i = 0; i < c.length; i++) {
            if (c[i].checked) {
                count++;
            }
        }
        if (count < 1) {
            alert("Anda belum memilih satu datapun.");
            return false;
        }
        return true;
    }
</script>