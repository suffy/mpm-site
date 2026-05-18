<style>
    #form {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: max-height 0.5s ease, opacity 0.5s ease;
}

    #form.show {
        max-height: 100%; /* cukup besar agar semua konten terlihat */
        opacity: 1;
        transition: all 0.15s ease-in-out;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
</style>

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

        <button onclick="toggleKonten()" class="btn btn-submit-black" id="button_form">Form Ajuan</button>

        <div class="row mt-2 mb-4" id="form">
            <div class="col-md-6">
                <?= form_open_multipart($url,  ['method' => 'post']) ?>
                <div class="row mt-3">
                    <div id="divform1">
                        <div class="row mt-1" id="divform_pic">
                            <div class="col-md-3">
                                <label for="pic">PIC</label>
                            </div>
                            <div class="col-md-9">
                                <Select class="form-select" style="text-transform: capitalize;" name="pic" id="pic" required>
                                    <?php foreach ($pic as $key => $value) { ?>
                                        <option value="<?= $value->id; ?>"> <?= $value->username; ?> </option>
                                    <?php } ?>
                                </Select>
                            </div>
                        </div>


                        <div class="row mt-1" id="divform_jabatan">
                            <div class="col-md-3">
                                <label for="jabatan">Jabatan</label>
                            </div>
                            <div class="col-md-9">
                                <Select class="form-select" style="text-transform: capitalize;" name="jabatan" id="jabatan" required>
                                        <option value="">- Pilih Jabatan -</option>
                                        <option value="area manager">Area Manager</option>
                                        <option value="director">Director</option>
                                        <option value="general manager">General Manager</option>
                                        <option value="head departement">Head Departement</option>
                                        <option value="kam">KAM</option>
                                        <option value="manager">Manager</option>
                                        <option value="nkam">NKAM</option>
                                        <option value="regional manager">Regional Manager</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="staff">Staff</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-1" id="divform_periode">
                            <div class="col-md-3">
                                <label for="from">Periode</label>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md mt-1">
                                        <label for="from" class="form-label">From</label>
                                        <input type="date" name="from" id="from" min="2023-12-01" class="form-control" required>
                                    </div>

                                    <div class="col-md mt-1">
                                        <label for="to" class="form-label">To</label>
                                        <input type="date" name="to" id="to" min="2023-12-01" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-3" style="text-align: center;">
                    <div>
                        <?= form_submit('submit', 'Submit Pengajuan Biop', 'class="btn btn-submit-black"'); ?>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>

        <div class="row mt-2" id="table">
            <div class="card mb-3">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-submit-orange" onclick="convertTable()" style="border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">Convert data below to Excel</button>
                        </div>
                    </div>

                    <div class="card-block mt-3 mb-5">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table id="tabel-ajuan-biop">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No Biop</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Jabatan</th>
                                            <th class="text-center">Periode</th>
                                            <th class="text-center">Total Biaya</th>
                                            <th class="text-center">On Duty</th>
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
                                                    } ?>
                                                </td>
                                                <td></td>
                                                <td style="text-transform: capitalize;"><?= $key->pic_on_duty_name; ?></td>
                                                <td style="text-transform: uppercase;">
                                                    <a href='<?= base_url("$url_proses/$key->signature"); ?>'
                                                        class="btn btn-warning btn-sm"><?= $key->nama_status ?></a>
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