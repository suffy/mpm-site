<div class="container-fluid">
    <div class="col-md">
        <div class="row">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
            </div>
        </div>

        <div class="row mt-2">
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

        <div class="row mt-2" id="table">
            <div class="card mb-3">
                <h3 class="form-title">Data Pengajuan Biop</h3>
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <button type="button" class="btn btn-submit-orange" onclick="convertTable()"  id="exportExcel">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 table-responsive">
                        <table id="tabel-ajuan-biop">
                            <thead>
                                <tr>
                                    <th class="text-center">No Biop</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Total Biaya</th>
                                    <th class="text-center">Total Biaya Adjusment</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($get_data as $key) { ?>
                                    <tr>
                                        <td><?= $key->no_ajuan; ?></td>
                                        <td style="text-transform: capitalize;"><?= $key->pic_name; ?></td>
                                        <td><?= $key->jabatan; ?></td>
                                        <td>
                                            <?php
                                            if ($key->to != null) {
                                                echo date('d F Y', strtotime($key->from)) . ' - ' . date('d F Y', strtotime($key->to));
                                            }; ?>
                                        </td>
                                        <td>Rp. <?= number_format($key->total_biaya); ?></td>
                                        <td>Rp. <?= number_format($key->total_biaya_adjustment); ?></td>
                                        <td style="text-transform: uppercase;">
                                            <?php 
                                                if ($key->nama_status == 'approved') { // PROSES DP
                                                    $color = "btn btn-success btn-sm";
                                                } else {
                                                    $color = "btn btn-warning btn-sm";
                                                }
                                            ?>
                                            <a href="<?= base_url("$url/$key->signature"); ?>"
                                                class="<?= $color; ?>"><?= $key->nama_status.' ('.$key->pic_on_duty_name.')'; ?></a>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleKonten() {
        const form = document.getElementById('form');
        const tombol_form = document.getElementById('button_form');

        form.classList.toggle('show');

        if (form.classList.contains('show')) {
            tombol_form.textContent = 'Close Form';
        } else {
            tombol_form.textContent = 'Form Ajuan';
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('#tabel-ajuan-biop').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
    });
</script>