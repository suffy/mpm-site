</div>

<div class="container-fluid">

    <div class="az-content">
        <div class="container-fluid">

            <?php
            if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'fakhrul' || $this->session->userdata('username') == 'suffy') { ?>
                <?= $this->load->view('spk/component/sidebar_admin'); ?>
            <?php
            } else { ?>
                <?= $this->load->view('spk/component/sidebar'); ?>
            <?php
            }
            ?>

            <div class="az-content-body pd-lg-l-40 d-flex flex-column">
                <h2 id="form_spk"><?= $title; ?></h2>
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

                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="form-inline row">
                            <div class="col-sm-12 mb-2">
                                <h5>Import File</h5>
                                <form action="<?= base_url($url_import) ?>" method="post" enctype="multipart/form-data">
                                    Pilih File <input type="file" class="form-control" name="file" id="file" required>
                                    <button type="submit" value="3" class="btn btn-submit-black">Import</button>
                                    <a href="<?= base_url('spk/export_master_site'); ?>" type="button" , class="btn btn-submit-black">Download Master Site</a>
                                    <p>Silakan klik link untuk <a href="<?= base_url('spk/template_purchase_plan'); ?>">download</a> template kosong</p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h5>Export</h5>
                    </div>
                </div>

                <form action="<?= base_url($url_export) ?>" method="post">

                <div class="row mt-2">
                    <div class="col-md-2">
                        <label for="month">Bulan</label>
                    </div>
                    <div class="col-md-5">
                        <input type="month" class="form-control" id="month" name="month" value="<?= date('Y-m') ?>" required>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">
                        
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-submit-approve" id="export">Export</button>
                    </div>
                </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#update').hide()

        $('#tabel-data').DataTable({
            paging: false,
            scrollCollapse: true,
            scrollY: '500px'
        });

        // Inisialisasi DataTable
        var table = $('#tabel-data').DataTable();

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
                $('#update').show();
            } else {
                // Jika checkbox select-all dibatalkan, kosongkan array selectedRows
                selectedRows = [];
                table.rows({
                    filter: 'applied'
                }).every(function() {
                    var row = this.node();
                    $(row).find('.select-row').prop('checked', false);
                });
                $('#update').hide();
            }
        });

        // Ketika checkbox per baris diubah
        $('#tabel-data tbody').on('change', '.select-row', function() {
            var rowIndex = table.row($(this).closest('tr')).index();

            if (this.checked) {
                // Tambahkan baris yang dipilih ke array
                if (!selectedRows.includes(rowIndex)) {
                    selectedRows.push(rowIndex);
                    $('#update').show();
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
                $('#update').show();
            } else {
                $('#select-all').prop('checked', false);
                $('#update').show();
            }
        });
    });
</script>