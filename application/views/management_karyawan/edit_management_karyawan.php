<style>
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
            <div class="col-md-12">
                <a href="<?= base_url('management_karyawan/input_management_karyawan'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Edit Data Karyawan</h3>
                        
                        <?= form_open_multipart('management_karyawan/update_karyawan',  ['method' => 'post', 'class' => 'mt-3']) ?>
                            
                            <input type="hidden" name="signature" value="<?= $karyawan->signature; ?>">

                            <div class="row">
                                <!-- ================= KOLOM KIRI ================= -->
                                <div class="col-md-6">
                                    <div class="section-title">
                                        <i class="fas fa-user"></i> Data Pribadi
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nama Perusahaan (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="nama_perusahaan" class="form-control" disabled>
                                                <option value="">-- Pilih --</option>
                                                <option name="kode_dp" value="MPMHO"
                                                    <?= isset($karyawan) && $karyawan->site_code == 'MPMHO' ? 'selected' : '' ?>>
                                                    PT. MULIA PUTRA MANDIRI
                                                </option>
                                                <option name="kode_dp" value="TGR39"
                                                    <?= isset($karyawan) && $karyawan->site_code == 'TGR39' ? 'selected' : '' ?>>
                                                    JAVAS KARYA TRIPTA
                                                </option>
                                                <option name="kode_dp" value="JBR95"
                                                    <?= isset($karyawan) && $karyawan->site_code == 'JBR95' ? 'selected' : '' ?>>
                                                    JAYA BAKTI RAHARJA
                                                </option>
                                                <option name="kode_dp" value="JTM91"
                                                    <?= isset($karyawan) && $karyawan->site_code == 'JTM91' ? 'selected' : '' ?>>
                                                    JAVAS TRIPTA MANDALA
                                                </option>
                                                <option name="kode_dp" value="SMG14"
                                                    <?= isset($karyawan) && $karyawan->site_code == 'SMG14' ? 'selected' : '' ?>>
                                                    JAVAS TRIPTA GEMALA
                                                </option>
                                                <option name="kode_dp" value="JBLJ2"
                                                    <?= isset($karyawan) && $karyawan->site_code == 'JBLJ2' ? 'selected' : '' ?>>
                                                    JAVAS BALI LESTARI
                                                </option>
                                                <option name="kode_dp" value="DIY98"
                                                    <?= isset($karyawan) && $karyawan->site_code == 'DIY98' ? 'selected' : '' ?>>
                                                    DUTA INTRA YASA
                                                </option>
                                                </select>

                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Sub Branch / DP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_comp" class="form-control" value="<?= $karyawan->nama_comp; ?>" readonly>
                                        </div>
                                    </div>

                                    <!-- <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label>Username Web (*)</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="username" id="username" class="form-control select2">
                                                <option value=""></option>
                                                <?php foreach ($get_username as $a) { ?>
                                                    <option value="<?= $a->username ?>"
                                                        <?= isset($karyawan) && $karyawan->username_web == $a->username ? 'selected' : '' ?>>
                                                        <?= $a->name ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div> -->

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>No. Kepegawaian</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="no_kepegawaian" class="form-control" value="<?= $karyawan->nomor_kepegawaian; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nama Lengkap (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_lengkap" class="form-control" value="<?= $karyawan->nama_lengkap; ?>" required>
                                            <input type="hidden" name="id_karyawan" value="<?= $karyawan->id; ?>">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Jenis Kelamin (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="jenis_kelamin" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <option <?= ($karyawan->jenis_kelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                                <option <?= ($karyawan->jenis_kelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Tempat Lahir (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="tempat_lahir" class="form-control" value="<?= $karyawan->tempat_lahir; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Tanggal Lahir (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="date" name="tanggal_lahir" class="form-control" value="<?= $karyawan->tanggal_lahir; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Golongan Darah (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="golongan_darah" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <option <?= ($karyawan->golongan_darah == 'A') ? 'selected' : ''; ?>>A</option>
                                                <option <?= ($karyawan->golongan_darah == 'B') ? 'selected' : ''; ?>>B</option>
                                                <option <?= ($karyawan->golongan_darah == 'AB') ? 'selected' : ''; ?>>AB</option>
                                                <option <?= ($karyawan->golongan_darah == 'O') ? 'selected' : ''; ?>>O</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Status Perkawinan (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="status_perkawinan" class="form-control" required>
                                                <option value="">-- Pilih --</option>
                                                <option <?= ($karyawan->status_perkawinan == 'Kawin') ? 'selected' : ''; ?>>Kawin</option>
                                                <option <?= ($karyawan->status_perkawinan == 'Belum Kawin') ? 'selected' : ''; ?>>Belum Kawin</option>
                                                <option <?= ($karyawan->status_perkawinan == 'Duda/Janda') ? 'selected' : ''; ?>>Duda/Janda</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Agama (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="agama" class="form-control" value="<?= $karyawan->agama; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Alamat KTP (*)</label></div>
                                        <div class="col-md-8">
                                            <textarea name="alamat" class="form-control" rows="2" required><?= $karyawan->alamat_ktp; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Alamat Domisili (*)</label></div>
                                        <div class="col-md-8">
                                            <textarea name="alamat_domisili" class="form-control" rows="2" required><?= $karyawan->alamat_domisili; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Email (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="email" name="email" class="form-control" value="<?= $karyawan->email; ?>" required>
                                            <small id="emailError" style="color:red; display:none;">
                                                Email harus menggunakan Gmail (@gmail.com)
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Email Perusahaan</label></div>
                                        <div class="col-md-8">
                                            <input type="email" id="email_perusahaan" name="email_perusahaan" class="form-control" value="<?= $karyawan->email_perusahaan; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nomor HP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="phone" class="form-control" value="<?= $karyawan->phone; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nama Kontak Darurat (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_kontak_darurat" class="form-control" value="<?= $karyawan->nama_kontak_darurat; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>No. Kontak Darurat (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_kontak_darurat" class="form-control" value="<?= $karyawan->nomor_kontak_darurat; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Status Karyawan (*)</label></div>
                                        <div class="col-md-8">
                                            <select name="status_karyawan" id="status_karyawan" class="form-control" onchange="toggleStatus()" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="tetap" <?= ($karyawan->status_karyawan == 'tetap') ? 'selected' : ''; ?>>Tetap</option>
                                                <option value="kontrak" <?= ($karyawan->status_karyawan == 'kontrak') ? 'selected' : ''; ?>>Kontrak</option>
                                                <option value="phl" <?= ($karyawan->status_karyawan == 'phl') ? 'selected' : ''; ?>>PHL</option>
                                            </select>
                                        </div>
                                    </div>

                                    <?php if ($this->session->userdata('username') == 'ratri') : ?>
                                    <!-- Field untuk Probation / Kontrak / PHL -->
                                    <div id="group_kontrak" style="display:none;">

                                        <div class="row mt-2">
                                            <div class="col-md-4"><label>Tanggal Mulai Percobaan/Kontrak/PHL</label></div>
                                            <div class="col-md-8">
                                                <input type="date" name="tgl_mulai_kontrak" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-4"><label>Tanggal Selesai Percobaan/Kontrak/PHL</label></div>
                                            <div class="col-md-8">
                                                <input type="date" name="tgl_selesai_kontrak" class="form-control">
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Field untuk Tetap -->
                                    <div id="group_tetap" style="display:none;">

                                        <div class="row mt-2">
                                            <div class="col-md-4"><label>Tanggal Karyawan Tetap</label></div>
                                            <div class="col-md-8">
                                                <input type="date" name="tgl_karyawan_tetap" class="form-control">
                                            </div>
                                        </div>

                                    </div>

                                    <?php endif; ?>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Tgl Mulai Kerja (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="date" name="tanggal_mulai_kerja" class="form-control" value="<?= $karyawan->tanggal_mulai_kerja; ?>" required>
                                        </div>
                                    </div>

                                    <?php
                                    if($username == 'ratri' ){ ?>
                                        <div class="row mt-2">
                                            <div class="col-md-4"><label>Tgl Selesai Kerja</label></div>
                                                <div class="col-md-8">
                                                    <input type="date" name="tanggal_selesai_kerja" class="form-control" value="<?= $karyawan->tanggal_selesai_kerja; ?>">
                                                </div>
                                        </div>
                                    <?php } ?>

                                    

                                </div>

                                <!-- ================= KOLOM KANAN ================= -->
                                <div class="col-md-6">
                                    
                                    <div class="section-title">
                                        <i class="fas fa-file-alt"></i> Data Dokumen & Kepegawaian
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nomor KTP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_ktp" class="form-control" 
                                                value="<?= $karyawan->nomor_ktp; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>File KTP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="file" name="file_ktp" class="form-control" >
                                            <?php if (!empty($karyawan->file_ktp)) { ?>
                                                <small class="text-muted d-block mt-1">
                                                    File saat ini: 
                                                    <a href="<?= base_url('assets/uploads/karyawan/' . $karyawan->file_ktp); ?>" 
                                                    target="_blank" 
                                                    class="text-primary">
                                                        <i class="fas fa-file"></i> <?= $karyawan->file_ktp; ?>
                                                    </a>
                                                </small>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nomor KK (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_kk" class="form-control" 
                                                value="<?= $karyawan->nomor_kk; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>File KK (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="file" name="file_kk" class="form-control" >
                                            <?php if (!empty($karyawan->file_kk)) { ?>
                                                <small class="text-muted d-block mt-1">
                                                    File saat ini: 
                                                    <a href="<?= base_url('assets/uploads/karyawan/' . $karyawan->file_kk); ?>" 
                                                    target="_blank" 
                                                    class="text-primary">
                                                        <i class="fas fa-file"></i> <?= $karyawan->file_kk; ?>
                                                    </a>
                                                </small>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>NPWP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="npwp" class="form-control" 
                                                value="<?= $karyawan->npwp; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>File NPWP (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="file" name="file_npwp" class="form-control" >
                                            <?php if (!empty($karyawan->file_npwp)) { ?>
                                                <small class="text-muted d-block mt-1">
                                                    File saat ini: 
                                                    <a href="<?= base_url('assets/uploads/karyawan/' . $karyawan->file_npwp); ?>" 
                                                    target="_blank" 
                                                    class="text-primary">
                                                        <i class="fas fa-file"></i> <?= $karyawan->file_npwp; ?>
                                                    </a>
                                                </small>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Bank (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_bank" class="form-control" 
                                                value="<?= $karyawan->nama_bank; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>No. Rekening (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_rekening" class="form-control" 
                                                value="<?= $karyawan->nomor_rekening; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Nama Rekening (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_rekening" class="form-control" 
                                                value="<?= $karyawan->nama_rekening; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Departement (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="departement" class="form-control" 
                                                value="<?= $karyawan->departement; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Divisi (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="divisi" class="form-control" 
                                                value="<?= $karyawan->divisi; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Job Level</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="job_level" class="form-control" 
                                                value="<?= $karyawan->job_level; ?>">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4"><label>Atasan Langsung (*)</label></div>
                                        <div class="col-md-8">
                                            <input type="text" name="nama_atasan_langsung" class="form-control" 
                                                value="<?= $karyawan->nama_atasan_langsung; ?>" readonly>
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
                            </div>

                            <div class="row mt-4 mb-4">
                                <div class="col-md-12 text-center">
                                    <!-- <a href="<?= base_url('management_karyawan/input_management_karyawan'); ?>" class="btn btn-secondary px-4">
                                        <i class="fas fa-times"></i> Batal
                                    </a> -->
                                    <button type="submit" class="btn btn-primary px-4 ml-2" name="button_action" value="2">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                    <a href="<?= base_url('management_karyawan/edit_management_karyawan/' . $karyawan->signature . '?status_action=approve'); ?>" class="btn btn-success px-4 ml-2">
                                        <i class="fas fa-check-circle"></i> Approve
                                    </a>
                                    <?php
                                    if($username == 'ratri' ){ ?>
                                        <button type="update" class="btn btn-primary px-4 ml-2" disabled>
                                            <i class="fas fa-save"></i> Update Jabatan (Comming Soon)
                                        </button>
                                    <?php } ?>
                                    <!-- <button type="submit" name="status_action" value="approve" class="btn btn-success px-4 ml-2">
                                        <i class="fas fa-check-circle"></i> Approve -->
                                    </button>
                                </div>
                            </div>

                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

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



