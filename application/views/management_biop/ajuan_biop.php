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

    .form-control[readonly] 
    {
        background-color: #f8f9fa;
        background-image: linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0),
                            linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0);
        background-size: 20px 20px;
        background-position: 0 0, 10px 10px;
        color: #6c757d;
        cursor: not-allowed;
        border-color: #ced4da;
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

        <div class="row">
            <div class="col-md-2">
                <button onclick="toggleKonten()" class="btn btn-submit-black" id="button_form">Form Biop</button>
            </div>
        </div>
        
        <!-- Form -->
        <div class="row mt-2" id="form">
            <div class="col-md-12">
                <div class="card">
                    <div>
                        <h3>Form Biop</h3>
                        <?= form_open_multipart($url,  ['method' => 'post', 'class' => 'mt-3']) ?>
                            <div class="row mt-1" id="divform_pic">
                                <div class="col-md-2">
                                    <label for="pic">User</label>
                                </div>
                                <div class="col-md-6">
                                    <Select class="form-select" style="text-transform: capitalize;" name="pic" id="pic" required>
                                        <?php foreach ($pic as $key => $value) { ?>
                                            <option value="<?= $value->id; ?>"> <?= $value->username; ?> </option>
                                        <?php } ?>
                                    </Select>
                                </div>
                            </div>

                            <div class="row mt-2" id="divform_jabatan">
                                <div class="col-md-2">
                                    <label for="jabatan">Jabatan</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="jabatan" value="<?= $pic[0]->jabatan; ?>" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mt-2" id="divform_periode">
                                <div class="col-md-2">
                                    <label for="from">Periode</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md mt-1">
                                            <label for="from">From</label>
                                            <input type="date" name="from" id="from" min="2025-01-01" class="form-control" required>
                                        </div>

                                        <div class="col-md mt-1">
                                            <label for="to">To</label>
                                            <input type="date" name="to" id="to" min="2025-01-01" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4" id="divform_jabatan">
                                <div class="col-md-2">
                                    <label for="jabatan">Admin Biop</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="admin_biop" value="<?= $pic[0]->username_admin_biop; ?>" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mt-2" id="divform_jabatan">
                                <div class="col-md-2">
                                    <label for="jabatan">Atasan 1</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="atasan1" value="<?= $pic[0]->username_verifikasi1; ?>" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mt-2" id="divform_jabatan">
                                <div class="col-md-2">
                                    <label for="jabatan">Atasan 2</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="atasan2" value="<?= $pic[0]->username_verifikasi2; ?>" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mt-2" id="divform_jabatan">
                                <div class="col-md-2">
                                    <label for="jabatan">Admin Finance</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="admin_finance" value="<?= $pic[0]->username_admin_finance; ?>" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mt-2" id="divform_jabatan">
                                <div class="col-md-2">
                                    <label for="jabatan">Head Finance</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="head_finance" value="<?= $pic[0]->username_head_finance; ?>" class="form-control" readonly>
                                </div>
                            </div>                            
                                                        
                            <div class="row mt-4">
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-submit-red" style="height: 44px; padding: 10px 20px 10px 20px;">Simpan Ajuan Baru dan Lanjut ke Detail</button>
                                </div>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- Table -->
        <div class="row mt-3">
            <div class="col-md">
                <div class="card">
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
        <!-- <tr>
            <th colspan="2">Total Biaya (Rp)</th>
        </tr>
        <tr>
            <th class="text-center">No Biop</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Periode</th>
            <th class="text-center">Claim</th>
            <th class="text-center">Approve</th>
            <th class="text-center">Status</th>
            <th class="text-center">#</th>
        </tr> -->
        <tr>
            <th rowspan="2" class="text-center">No Biop</th>
            <th rowspan="2" class="text-center">Nama</th>
            <th rowspan="2" class="text-center">Periode</th>
            <th colspan="3" class="header-group text-center">Total Biaya (Rp)</th>
            <th rowspan="2" class="text-center">Status</th>
            <th rowspan="2" class="text-center">Aksi</th>
        </tr>
        <tr>
            <!-- Kolom untuk No Biop, Nama, dan Periode sudah di-cover oleh rowspan di atas -->
            <th class="text-center">Ajuan</th>
            <th class="text-center">Approve</th>
            <th class="text-center">Selisih</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($get_data as $key) { ?>
            <tr>
                <td><?= $key->no_ajuan; ?></td>
                <td style="text-transform: capitalize;"><?= $key->pic_name; ?></td>
                <td>
                    <?php
                    if ($key->to != null) {
                        echo date('d F Y', strtotime($key->from)) . ' - ' . date('d F Y', strtotime($key->to));
                    } ?>
                </td>
                <td><?= number_format($key->total_biaya); ?></td>
                <td><?= number_format($key->total_biaya_adjustment); ?></td>
                <td><?= number_format($key->selisih); ?></td>
                <td style="text-transform: uppercase;">
                <?php 
                    if ($key->nama_status == 'approved') {
                        $color = "btn btn-success btn-sm";
                        $label = $key->nama_status; // tanpa pic_on_duty_name
                    } else {
                        $color = "btn btn-warning btn-sm";
                        $label = $key->nama_status.' ('.$key->pic_on_duty_name.')';
                    }
                ?>
                <a href='<?= base_url("$url_proses/$key->signature"); ?>' class="<?= $color; ?>">
                    <?= $label; ?>
                </a>
            </td>

                <td>
                    <a href='<?= base_url("$url_detail/$key->signature"); ?>' class="btn btn-info">Detail</a>
                </td>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

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

<script>
    function convertTable() {
        var table = document.getElementById("tabel-ajuan-biop");

        // Convert table ke worksheet
        var workbook = XLSX.utils.table_to_book(table, {sheet: "Data Ajuan"});

        // Download file Excel
        XLSX.writeFile(workbook, "data_ajuan_biop.xlsx");
    }
</script>

<!-- <script>
  // Ambil tanggal hari ini
  const today = new Date();

  // Hitung tanggal minimum = awal bulan lalu
  const minDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);

  // Format ke yyyy-mm-dd
  const formatDate = (date) => {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
  };

  const fromInput = document.getElementById('from');
  const toInput = document.getElementById('to');

  // Set min dan max value
  fromInput.min = formatDate(minDate);
  fromInput.max = formatDate(today);
  toInput.min = formatDate(minDate);
  toInput.max = formatDate(today);

  // Supaya tanggal 'to' tidak lebih kecil dari 'from'
  fromInput.addEventListener('change', () => {
    toInput.min = fromInput.value;
  });
</script> -->