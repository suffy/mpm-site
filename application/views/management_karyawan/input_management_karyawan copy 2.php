<style>
    #form {
    display: none;
    }

    #form.show {
        display: block;
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

    /* Samakan tinggi Select2 dengan input Bootstrap */
    .select2-container .select2-selection--single {
        height: 38px !important;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        background-image: linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0),
                            linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0);
        background-size: 20px 20px;
        background-position: 0 0, 10px 10px;
        color: #6c757d;
        cursor: not-allowed;
        border-color: #ced4da;
    }

    .section-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .dropdown-menu,
    .dropdown-menu * {
        opacity: 1 !important;
    }

    /* Kunci layout tabel */
    #tabel-data-karyawan {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    /* Kunci tinggi baris */
    #tabel-data-karyawan tbody tr {
        height: 50px !important;
    }

    /* Semua cell center vertikal */    
    #tabel-data-karyawan td,
    #tabel-data-karyawan th {
        vertical-align: middle !important;
    }

    /* Kolom aksi */
    #tabel-data-karyawan td.action-column {
        text-align: center !important;
        padding: 0 !important;
    }

    /* Dropdown TIDAK ikut stretch */
    #tabel-data-karyawan td.action-column .dropdown {
        display: inline-block !important;
    }

    /* Lebar kolom aksi */
    #tabel-data-karyawan th:last-child,
    #tabel-data-karyawan td:last-child {
        width: 90px !important;
        min-width: 90px !important;
    }

    /* FIX scrollbar */
    .table-responsive {
        overflow-x: auto;
        overflow-y: hidden !important;
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
                <button onclick="toggleKonten()" class="btn btn-submit-black" id="button_form">Form Input Data Karyawan</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-submit-black" id="button_form">Request Karyawan (Comming Soon)</button>
            </div>
        </div>
        
        <!-- Form -->
        <div class="row mt-2" id="form">
            <div class="col-md-12">
                <div class="card">
                    <div>
                        <h3>Form Input Data Karyawan</h3>
                        <?= form_open_multipart($url,  ['method' => 'post', 'class' => 'mt-3', 'id' => 'employeeForm']) ?>

                            <div class="row">

                                <!-- ================= KOLOM KIRI ================= -->
                                <div class="col-md-6">

                                    <div class="section-title">
                                        <i class="fas fa-user"></i> Data Pribadi
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nama Perusahaan (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="nama_perusahaan" id = "nama_perusahaan" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="MOMHO" >PT. MULIA PUTRA MANDIRI</option>
                                                <option value="JKT88">JAVAS KARYA TRIPTA</option>
                                                <option value="JBR95">JAYA BAKTI RAHARJA</option>
                                                <option value="JTM91" >JAVAS TRIPTA MANDALA</option>
                                                <option value="SMG14">JAVAS TRIPTA GEMALA</option>
                                                <option value="JBLJ2">JAVAS BALI LESTARI</option>
                                                <option value="DIY98">DUTA INTRA YASA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Sub Branch / DP (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="kode_dp" id="kode_dp" class="form-control" required>
                                                <option value="">-- Pilih DP --</option>
                                            </select>
                                        </div>
                                    </div>


                                    <!-- <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label>Username Web</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="username" id="username" class="form-control select2">
                                                <option value=""></option>
                                                <?php foreach ($get_username as $a) { ?>
                                                    <option value="<?= $a->username ?>"><?= $a->name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div> -->
                                    
                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>No. Kepegawaian (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="no_kepegawaian" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nama Lengkap (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_lengkap" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Jenis Kelamin (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="jenis_kelamin" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <option>Laki-laki</option>
                                                <option>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Tempat Lahir (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="tempat_lahir" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Tanggal Lahir (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="date" name="tanggal_lahir" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Golongan Darah (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="golongan_darah" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="AB">AB</option>
                                                <option value="O">O</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Status Perkawinan (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="status_perkawinan" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="Kawin">Kawin</option>
                                                <option value="Belum Kawin">Belum Kawin</option>
                                                <option value="Duda/Janda">Duda/Janda</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Agama (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="agama" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Alamat KTP (*)</label></div>
                                        <div class="col-md-8">
                                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Alamat Domisili (*)</label></div>
                                        <div class="col-md-8">
                                            <textarea name="alamat_domisili" class="form-control" rows="2" required></textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Email Gmail (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="email" id="email" name="email" class="form-control" required>
                                            <small id="emailError" style="color:red; display:none;">
                                                Email harus menggunakan Gmail (@gmail.com)
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Email Perusahaan</label></div>
                                        <div class="col-md-8">
                                            <input type="email" id="email_perusahaan" name="email_perusahaan" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nomor HP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="phone" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nama Kontak Darurat (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_kontak_darurat" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>No. Kontak Darurat (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_kontak_darurat" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Status Karyawan (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="status_karyawan" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="tetap">Tetap</option>
                                                <option value="kontrak">Kontrak</option>
                                                <option value="phl">PHL</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Tgl Mulai Kerja (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="date" name="tanggal_mulai_kerja" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="section-title mt-4">
                                        <i class="fas fa-graduation-cap"></i> Pendidikan
                                    </div>

                                    <div id="pendidikan-wrapper">
                                        <?php 
                                        // CEK JIKA DATA PENDIDIKAN ADA DI DATABASE
                                        if (!empty($list_pendidikan)) {
                                            foreach ($list_pendidikan as $key => $edu) { ?>
                                                <div class="row mt-2 pendidikan-item" data-index="<?= $key; ?>">
                                                    <div class="col-md-3">
                                                        <label>Jenjang</label>
                                                        <select name="pendidikan[<?= $key; ?>][jenjang]" class="form-control">
                                                            <option value="">-- Pilih --</option>
                                                            <?php 
                                                            $levels = ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'];
                                                            foreach ($levels as $l) {
                                                                $sel = ($edu->pendidikan_terakhir == $l) ? 'selected' : '';
                                                                echo "<option value='$l' $sel>$l</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <input type="hidden" name="pendidikan[<?= $key; ?>][id]" value="<?= $edu->id; ?>">
                                                        <input type="hidden" name="pendidikan[<?= $key; ?>][deleted]" value="0" class="deleted-input">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label>Institusi</label>
                                                        <input type="text" name="pendidikan[<?= $key; ?>][institusi]" class="form-control" value="<?= $edu->institusi_pendidikan; ?>">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Jurusan</label>
                                                        <input type="text" name="pendidikan[<?= $key; ?>][jurusan]" class="form-control" value="<?= $edu->jurusan; ?>">
                                                    </div>

                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <?php if ($key == 0) { ?>
                                                            <button type="button" class="btn btn-success add-pendidikan"><i class="fa fa-plus"></i></button>
                                                        <?php } else { ?>
                                                            <button type="button" class="btn btn-danger remove-pendidikan"><i class="fa fa-trash"></i></button>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } 
                                        } else { ?>
                                            <div class="row mt-2 pendidikan-item" data-index="0">
                                                <div class="col-md-3">
                                                    <label>Jenjang</label>
                                                    <select name="pendidikan[0][jenjang]" class="form-control">
                                                        <option value="">-- Pilih --</option>
                                                        <option value="SD">SD</option>
                                                        <option value="SMP">SMP</option>
                                                        <option value="SMA/SMK">SMA/SMK</option>
                                                        <option value="D3">D3</option>
                                                        <option value="S1">S1</option>
                                                        <option value="S2">S2</option>
                                                        <option value="S3">S3</option>
                                                    </select>
                                                    <input type="hidden" name="pendidikan[0][id]" value="">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Institusi</label>
                                                    <input type="text" name="pendidikan[0][institusi]" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Jurusan</label>
                                                    <input type="text" name="pendidikan[0][jurusan]" class="form-control">
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-success add-pendidikan"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="section-title mt-4">
                                        <i class="fas fa-users"></i> Data Keluarga
                                    </div>

                                    <div id="keluarga-wrapper">
                                        <?php 
                                        if (!empty($list_keluarga)) {
                                            foreach ($list_keluarga as $key => $fam) { ?>
                                                <div class="row mt-2 keluarga-item" data-index="<?= $key; ?>">
                                                    
                                                    <input type="hidden" name="keluarga[<?= $key; ?>][id]" value="<?= $fam->id; ?>">
                                                    <input type="hidden" name="keluarga[<?= $key; ?>][deleted]" value="0" class="deleted-flag">

                                                    <div class="col-md-3">
                                                        <label>Nama</label>
                                                        <input type="text" name="keluarga[<?= $key; ?>][nama]" class="form-control" value="<?= $fam->nama; ?>">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Hubungan</label>
                                                        <input type="text" name="keluarga[<?= $key; ?>][hubungan]" class="form-control" value="<?= $fam->hubungan; ?>">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Pendidikan</label>
                                                        <input type="text" name="keluarga[<?= $key; ?>][pendidikan]" class="form-control" value="<?= $fam->pendidikan; ?>">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Pekerjaan</label>
                                                        <input type="text" name="keluarga[<?= $key; ?>][pekerjaan]" class="form-control" value="<?= $fam->pekerjaan; ?>">
                                                    </div>

                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <?php if($key == 0){ ?>
                                                            <button type="button" class="btn btn-success add-keluarga"><i class="fa fa-plus"></i></button>
                                                        <?php } else { ?>
                                                            <button type="button" class="btn btn-danger remove-keluarga"><i class="fa fa-trash"></i></button>
                                                        <?php } ?>
                                                    </div>

                                                </div>
                                            <?php }
                                        } else { ?>
                                            <div class="row mt-2 keluarga-item" data-index="0">
                                                <input type="hidden" name="keluarga[0][id]" value="">
                                                <input type="hidden" name="keluarga[0][deleted]" value="0" class="deleted-flag">

                                                <div class="col-md-3">
                                                    <label>Nama</label>
                                                    <input type="text" name="keluarga[0][nama]" class="form-control">
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Hubungan</label>
                                                    <input type="text" name="keluarga[0][hubungan]" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Pendidikan</label>
                                                    <input type="text" name="keluarga[0][pendidikan]" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Pekerjaan</label>
                                                    <input type="text" name="keluarga[0][pekerjaan]" class="form-control">
                                                </div>

                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-success add-keluarga"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>

                                <!-- ================= KOLOM KANAN ================= -->
                                <div class="col-md-6">

                                    <div class="section-title">
                                        <i class="fas fa-file-alt"></i> Data Dokumen & Kepegawaian
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nomor KTP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_ktp" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>File KTP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="file" name="file_ktp" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nomor KK (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_kk" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>File KK (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="file" name="file_kk" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>NPWP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="npwp" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>File NPWP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="file" name="file_npwp" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>No. BPJS TK (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_bpjs_ketenagakerjaan" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>File BPJS TK (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="file" name="file_bpjs_ketenagakerjaan" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>No. BPJS Kes (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_bpjs_kesehatan" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>File BPJS Kes (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="file" name="file_bpjs_kesehatan" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Bank (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_bank" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>No. Rekening (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_rekening" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nama Rekening (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_rekening" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Departement (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="departement" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Divisi (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="divisi" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Job Level (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="job_level" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Atasan Langsung (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_atasan_langsung" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="section-title mt-4">
                                        <i class="fas fa-shield-alt"></i> Data Asuransi
                                    </div>

                                    <div id="asuransi-wrapper">
                                        <?php 
                                        if (!empty($list_asuransi)) {
                                            foreach ($list_asuransi as $key => $as) { ?>
                                                <div class="row mt-2 asuransi-item" data-index="<?= $key; ?>">
                                                    
                                                    <input type="hidden" name="asuransi[<?= $key; ?>][id]" value="<?= $as->id; ?>">
                                                    <input type="hidden" name="asuransi[<?= $key; ?>][deleted]" value="0" class="deleted-flag">

                                                    <div class="col-md-3">
                                                        <label>No. Kartu Asuransi</label>
                                                        <input type="text" name="asuransi[<?= $key; ?>][nomor_kartu]" class="form-control" value="<?= $as->nomor_kartu_asuransi; ?>">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>No. Polis</label>
                                                        <input type="text" name="asuransi[<?= $key; ?>][nomor_polis]" class="form-control" value="<?= $as->nomor_polis_asuransi; ?>">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Plan Asuransi</label>
                                                        <input type="text" name="asuransi[<?= $key; ?>][plan]" class="form-control" value="<?= $as->plan_asuransi; ?>">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>No. Peserta</label>
                                                        <input type="text" name="asuransi[<?= $key; ?>][nomor_peserta]" class="form-control" value="<?= $as->nomor_peserta_asuransi; ?>">
                                                    </div>

                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <?php if($key == 0){ ?>
                                                            <button type="button" class="btn btn-success add-asuransi"><i class="fa fa-plus"></i></button>
                                                        <?php } else { ?>
                                                            <button type="button" class="btn btn-danger remove-asuransi"><i class="fa fa-trash"></i></button>
                                                        <?php } ?>
                                                    </div>

                                                </div>
                                            <?php }
                                        } else { ?>
                                            <div class="row mt-2 asuransi-item" data-index="0">
                                                <input type="hidden" name="asuransi[0][id]" value="">
                                                <input type="hidden" name="asuransi[0][deleted]" value="0" class="deleted-flag">

                                                <div class="col-md-3">
                                                    <label>No. Kartu Asuransi</label>
                                                    <input type="text" name="asuransi[0][nomor_kartu]" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>No. Polis Asuransi</label>
                                                    <input type="text" name="asuransi[0][nomor_polis]" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Plan Asuransi</label>
                                                    <input type="text" name="asuransi[0][plan]" class="form-control">
                                                </div>

                                                <div class="col-md-2">
                                                    <label>No. Peserta</label>
                                                    <input type="text" name="asuransi[0][nomor_peserta]" class="form-control">
                                                </div>

                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-success add-asuransi"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>


                            <div class="row mt-4 mb-4">
                                <div class="col-md-12 text-center">
                                    <!-- <button type="submit" class="btn btn-submit-green px-4">
                                        Simpan Draft
                                    </button> -->
                                    <a href="#" class="btn btn-secondary px-4">
                                        Simpan Draft
                                    </a>
                                    <button type="submit" class="btn btn-submit-red px-4">
                                        Simpan dan Ajukan ke HRD
                                    </button>
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
                            <!-- <button type="button" class="btn btn-submit-orange" onclick="convertTable()"  id="exportExcel">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button> -->
                            <div class="form-inline row mt-3">
                                <h5>Nama Company</h5>
                                <div class="col-md-12">
                                    <?= form_open($url_search, ['method' => 'post']);?>
                                        <select name="status" class="form-control" required>
                                            <option value=""<?= $search === Null ? 'selected' : '' ?>>-- Pilih --</option>
                                            <option value="MOMHO" <?= $search === 'MOMHO'? 'selected' : '' ?>>PT. MULIA PUTRA MANDIRI</option>
                                            <option value="JKT88" <?= $search === 'JKT88'? 'selected' : '' ?>>JAVAS KARYA TRIPTA</option>
                                            <option value="JBR95" <?= $search === 'JBR95'? 'selected' : '' ?>>JAYA BAKTI RAHARJA</option>
                                            <option value="JTM91" <?= $search === 'JTM91'? 'selected' : '' ?>>JAVAS TRIPTA MANDALA</option>
                                            <option value="SMG14" <?= $search === 'SMG14'? 'selected' : '' ?>>JAVAS TRIPTA GEMALA</option>
                                            <option value="JBLJ2" <?= $search === 'JBLJ2'? 'selected' : '' ?>>JAVAS BALI LESTARI</option>
                                            <option value="DIY98" <?= $search === 'DIY98'? 'selected' : '' ?>>DUTA INTRA YASA</option>
                                            <option value="">-- Semua Perusahaan --</option>
                                        </select>
                                        <button type="submit" value="1" class="btn btn-outline-danger btn-sm"
                                            name="search">Search</button>
                                        <!-- <button type="submit" value="2" class="btn btn-outline-danger btn-sm" onclick="convertTable()"  id="exportExcel">Export To
                                            CSV</button> -->
                                        <button type="submit" value="2" class="btn btn-outline-danger btn-sm" name="search">Export To CSV</button>
                                        <a href="<?= base_url('management_karyawan') ?>" class="btn btn-outline-dark btn-sm">Reset</a>
                                    <?= form_close();?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-md-4">
                        <select id="filterPrincipal" class="form-control">
                            <option value="">-- Semua Perusahaan --</option>
                            <option value="PT. MULIA PUTRA MANDIRI">PT. MULIA PUTRA MANDIRI</option>
                            <option value="JAVAS KARYA TRIPTA">JAVAS KARYA TRIPTA</option>
                            <option value="JAYA BAKTI RAHARJA">JAYA BAKTI RAHARJA</option>
                            <option value="JAVAS TRIPTA MANDALA">JAVAS TRIPTA MANDALA</option>
                            <option value="JAVAS TRIPTA GEMALA">JAVAS TRIPTA GEMALA</option>
                            <option value="JAVAS BALI LESTARI">JAVAS BALI LESTARI</option>
                            <option value="DUTA INTRA YASA">DUTA INTRA YASA</option>
                        </select>
                    </div> -->

                    <div class="row mt-3">
                        <div class="col-md-12 table-responsive">
                            <table id="tabel-data-karyawan">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Branch Name</th>
                                        <th class="text-center">Nama Comp</th>
                                        <th class="text-center">Nama Lengkap</th>
                                        <th class="text-center">Departement</th>
                                        <th class="text-center">Divisi</th>
                                        <th class="text-center">Job Level</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php foreach ($get_data->result() as $key) { ?>
                                        <?php
                                        $start = isset($start) ? $start : 0;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= ++$start; ?></td>
                                            <td><?= $key->branch_name; ?></td>
                                            <td><?= $key->nama_comp; ?></td>
                                            <td style="text-transform: capitalize;"><?= $key->nama_lengkap; ?></td>
                                            <td><?= $key->departement; ?></td>
                                            <td><?= $key->divisi; ?></td>
                                            <td><?= $key->job_level; ?></td>
                                            <td class="text-center">
                                                <?php
                                                if ($key->flag_status == '1') {
                                                    $nama_status = $key->nama_status;
                                                    $style = "font-size:14px";
                                                    $class = "pending-scm";
                                                } elseif ($key->flag_status == '2') {
                                                    $nama_status = $key->nama_status;
                                                    $style = "font-size:14px";
                                                    $class = "pending-finance";
                                                } elseif ($key->flag_status == '3') {
                                                    $nama_status = $key->nama_status;
                                                    $style = "font-size:14px";
                                                    $class = "finish";
                                                }else{
                                                    $nama_status = "";
                                                }
                                                ?>
                                                <?php if($nama_status == "" || $key->flag_status == null) { ?>
                                                    <span>-</span>
                                                <?php } else {?>
                                                <a href="<?= base_url() ?>management_karyawan/edit_management_karyawan/<?= $key->signature ?>" class="btn btn-submit status <?= $class ?>" target="_blank" style="<?= $style ?>"><?= $nama_status ?></a>
                                                <?php } ?>
                                            </td>
                                            <!-- <td> -->
                                                <!-- Tombol Detail -->
                                                <!-- <a href='<?= base_url("$url_detail/$key->signature"); ?>' class="btn btn-info btn-sm">Detail</a> -->
                                                
                                                <!-- Tombol Edit -->
                                                <!-- Tombol Edit - PINDAH KE HALAMAN BARU -->
                                                <!-- <a href='<?= base_url("management_karyawan/edit_management_karyawan/$key->signature"); ?>' class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i> Detail
                                                </a>
                                            </td> -->

                                            <td class ="action-column aksispesial">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="<?= base_url("management_karyawan/edit_management_karyawan/$key->signature"); ?>">Profile</a></li>
                                                        <li><a class="dropdown-item" href="<?= base_url("management_karyawan/export_pdf/$key->signature"); ?>">Export PDF</a></li>
                                                        <!-- <li><a class="dropdown-item" href="<?= base_url("management_karyawan/export_csv/$key->signature"); ?>">Export Excel</a></li> -->
                                                        <li><a class="dropdown-item" href="#">Budget(Coming Soon)</a></li>
                                                        <li><a class="dropdown-item" href="#">Resignation(Coming Soon)</a></li>
                                                    </ul>
                                                </div>
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
            tombol_form.textContent = 'Form Input Data Karyawan';
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('#tabel-data-karyawan').DataTable({
            "pageLength": 10,
            "ordering": true,
            "scrollX" : false,
            "scrollY": false,
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
        var table = document.getElementById("tabel-data-karyawan");

        // Convert table ke worksheet
        var workbook = XLSX.utils.table_to_book(table, {sheet: "Data Ajuan"});

        // Download file Excel
        XLSX.writeFile(workbook, "list_data_karyawan.xlsx");
    }
</script>

<script>
    $(document).ready(function () {
        $('#username').select2({
            placeholder: "-- Pilih Username Web --",
            allowClear: true,
            width: '100%',
            language: {
                noResults: () => "Data tidak ditemukan"
            }
        });
    });
</script>

<!-- script add delete pendidikan -->
<script>
    let indexPendidikan = <?= !empty($list_pendidikan) ? count($list_pendidikan) : 1 ?>;

    document.addEventListener('click', function(e) {

        // TAMBAH PENDIDIKAN
        if (e.target.closest('.add-pendidikan')) {
            const wrapper = document.getElementById('pendidikan-wrapper');

            const html = `
            <div class="row mt-2 pendidikan-item" data-index="${indexPendidikan}">
                <div class="col-md-3">
                    <select name="pendidikan[${indexPendidikan}][jenjang]" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option>SD</option>
                        <option>SMP</option>
                        <option>SMA/SMK</option>
                        <option>D3</option>
                        <option>S1</option>
                        <option>S2</option>
                        <option>S3</option>
                    </select>
                    <input type="hidden" name="pendidikan[${indexPendidikan}][id]" value="">
                    <input type="hidden" name="pendidikan[${indexPendidikan}][deleted]" value="0" class="deleted-input">
                </div>

                <div class="col-md-4">
                    <input type="text" name="pendidikan[${indexPendidikan}][institusi]" class="form-control"
                        placeholder="Nama Sekolah / Kampus">
                </div>

                <div class="col-md-3">
                    <input type="text" name="pendidikan[${indexPendidikan}][jurusan]" class="form-control"
                        placeholder="Jurusan">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-pendidikan">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>`;

            wrapper.insertAdjacentHTML('beforeend', html);
            indexPendidikan++;
        }

        // HAPUS / SOFT DELETE
        if (e.target.closest('.remove-pendidikan')) {
            const row = e.target.closest('.pendidikan-item');

            // Cek apakah input hidden id ada (data lama)
            const inputId = row.querySelector('input[name*="[id]"]');
            const inputDeleted = row.querySelector('.deleted-input');

            if (inputId && inputId.value) {
                // Data lama -> soft delete
                inputDeleted.value = 1;
                row.style.display = 'none';
            } else {
                // Data baru -> hapus langsung
                row.remove();
            }
        }

    });
</script>

<!-- script add delete keluarga -->
<script>
    $(document).ready(function(){

        // Tambah keluarga
        $(document).on('click', '.add-keluarga', function(){
            let wrapper = $('#keluarga-wrapper');
            let index = wrapper.find('.keluarga-item').length;

            let newRow = `
            <div class="row mt-2 keluarga-item" data-index="${index}">
                <input type="hidden" name="keluarga[${index}][id]" value="">
                <input type="hidden" name="keluarga[${index}][deleted]" value="0" class="deleted-flag">

                <div class="col-md-3">
                    <label>Nama</label>
                    <input type="text" name="keluarga[${index}][nama]" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>Hubungan</label>
                    <input type="text" name="keluarga[${index}][hubungan]" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Pendidikan</label>
                    <input type="text" name="keluarga[${index}][pendidikan]" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Pekerjaan</label>
                    <input type="text" name="keluarga[${index}][pekerjaan]" class="form-control">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-keluarga"><i class="fa fa-trash"></i></button>
                </div>
            </div>`;

            wrapper.append(newRow);
        });

        // Hapus row (soft delete)
        $(document).on('click', '.remove-keluarga', function(){
            let row = $(this).closest('.keluarga-item');
            row.find('.deleted-flag').val(1); // tandai deleted
            row.hide(); // sembunyikan row
        });

    });
</script>

<!-- script add delete asuransi -->
 <script>
    let indexAsuransi = <?= !empty($list_asuransi) ? count($list_asuransi) : 1 ?>;

    document.addEventListener('click', function(e) {

        // TAMBAH ROW
        if (e.target.closest('.add-asuransi')) {
            const wrapper = document.getElementById('asuransi-wrapper');

            const html = `
            <div class="row mt-2 asuransi-item" data-index="${indexAsuransi}">
                <input type="hidden" name="asuransi[${indexAsuransi}][id]" value="">
                <input type="hidden" name="asuransi[${indexAsuransi}][deleted]" value="0" class="deleted-flag">

                <div class="col-md-3">
                    <input type="text" name="asuransi[${indexAsuransi}][nomor_kartu]" class="form-control" placeholder="No. Kartu Asuransi">
                </div>

                <div class="col-md-2">
                    <input type="text" name="asuransi[${indexAsuransi}][nomor_polis]" class="form-control" placeholder="No. Polis Asuransi">
                </div>

                <div class="col-md-3">
                    <input type="text" name="asuransi[${indexAsuransi}][plan]" class="form-control" placeholder="Plan Asuransi">
                </div>

                <div class="col-md-2">
                    <input type="text" name="asuransi[${indexAsuransi}][nomor_peserta]" class="form-control" placeholder="No. Peserta">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-asuransi"><i class="fa fa-trash"></i></button>
                </div>
            </div>`;

            wrapper.insertAdjacentHTML('beforeend', html);
            indexAsuransi++;
        }

        // HAPUS / SOFT DELETE
        if (e.target.closest('.remove-asuransi')) {
            const row = e.target.closest('.asuransi-item');
            const inputDeleted = row.querySelector('.deleted-flag');

            if (inputDeleted) {
                inputDeleted.value = 1; // mark deleted
                row.style.display = 'none'; // sembunyikan
            } else {
                row.remove(); // hapus baru
            }
        }

    });
</script>

<!-- script email only gmail -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const form = document.getElementById("employeeForm");
        const emailInput = document.getElementById("email");
        const emailError = document.getElementById("emailError");

        if (!form) return;

        form.addEventListener("submit", function (e) {

            const email = emailInput.value.trim().toLowerCase();

            emailError.style.display = "none";

            // Validasi gmail saja
            if (!email.endsWith("@gmail.com")) {
                e.preventDefault(); // 🚫 STOP SUBMIT
                emailError.style.display = "block";
                emailInput.focus();
            }
        });
    });
</script>

<!-- script subbranch -->
<script>
    $(document).ready(function () {

        $('#nama_perusahaan').change(function () {

            let kode_perusahaan = $(this).val();
            console.log('Perusahaan dipilih:', kode_perusahaan);

            $('#kode_dp').html('<option value="">Loading...</option>');

            if (kode_perusahaan !== '') {
                $.ajax({
                    url: "<?= base_url('management_karyawan/get_dp_by_perusahaan'); ?>",
                    type: "POST",
                    data: { kode_perusahaan: kode_perusahaan },
                    dataType: "json",
                    success: function (res) {

                        console.log('Response DP:', res);

                        let option = '<option value="">-- Pilih DP --</option>';

                        if (res.length > 0) {
                            $.each(res, function (i, dp) {
                                option += `
                                    <option value="${dp.site_code}">
                                        ${dp.nama_comp}
                                    </option>`;
                            });
                        } else {
                            option += '<option value="">DP tidak ditemukan</option>';
                        }

                        $('#kode_dp').html(option);
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        $('#kode_dp').html('<option value="">Error load DP</option>');
                    }
                });
            } else {
                $('#kode_dp').html('<option value="">-- Pilih DP --</option>');
            }
        });

        // Ambil site_code saat DP dipilih
        $('#kode_dp').change(function () {
            console.log('DP dipilih:', $(this).val());
        });

    });
</script>

<!-- script dropdown -->
