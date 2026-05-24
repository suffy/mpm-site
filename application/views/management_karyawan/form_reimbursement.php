<!-- ============================================================
     views/management_karyawan/reimbursement/form_reimbursement.php
     ============================================================ -->
</div>
<style>
/* ── Root Variables ─────────────────────────── */
:root {
    --primary:    #1a56a0;
    --primary-lt: #e8f0fb;
    --accent:     #f59e0b;
    --success:    #16a34a;
    --danger:     #dc2626;
    --warning:    #d97706;
    --gray-50:    #f8fafc;
    --gray-100:   #f1f5f9;
    --gray-200:   #e2e8f0;
    --gray-400:   #94a3b8;
    --gray-600:   #475569;
    --gray-800:   #1e293b;
    --radius:     10px;
    --shadow:     0 2px 12px rgba(0,0,0,.08);
    --shadow-md:  0 4px 20px rgba(0,0,0,.12);
}

/* ── Layout ─────────────────────────────────── */
.reimb-wrapper { max-width: 1350px; margin: 0 auto; padding: 0 4px 40px; }

/* ── Karyawan Selector (admin only) ─────────── */
.karyawan-selector {
    background: var(--primary-lt);
    border: 1.5px solid #c3d9f5;
    border-radius: var(--radius);
    padding: 16px 20px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.karyawan-selector label { font-weight: 600; color: var(--primary); white-space: nowrap; margin: 0; }
.karyawan-selector select { flex: 1; }

/* ── Info Karyawan ───────────────────────────── */
.karyawan-info-bar {
    background: linear-gradient(135deg, var(--primary) 0%, #1e6ec8 100%);
    border-radius: var(--radius);
    padding: 16px 22px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 22px;
    box-shadow: var(--shadow-md);
}
.karyawan-info-bar .avatar {
    width: 46px; height: 46px;
    background: rgba(255,255,255,.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.karyawan-info-bar .info h5 { margin: 0 0 2px; font-size: 15px; font-weight: 700; }
.karyawan-info-bar .info p  { margin: 0; font-size: 12px; opacity: .8; }
.karyawan-info-bar .badges  { margin-left: auto; display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
.karyawan-info-bar .badge-pill {
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 20px;
    padding: 3px 12px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

/* ── Card Form ───────────────────────────────── */
.card-reimb {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    margin-bottom: 28px;
    overflow: hidden;
}
.card-reimb .card-head {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 14px 22px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.card-reimb .card-head .icon {
    width: 32px; height: 32px;
    background: var(--primary-lt);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: 14px;
}
.card-reimb .card-head h4 { margin: 0; font-size: 14px; font-weight: 700; color: var(--gray-800); }
.card-reimb .card-body  { padding: 22px; }

/* ── Form Fields ─────────────────────────────── */
.form-row-custom { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 0; }
.form-row-custom .field { flex: 1; min-width: 180px; }
.form-row-custom .field.full { flex: 100%; }

.field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 6px;
}
.field label .req { color: var(--danger); margin-left: 2px; }

.field input[type="text"],
.field input[type="date"],
.field input[type="number"],
.field textarea,
.field select {
    width: 100%;
    border: 1.5px solid var(--gray-200);
    border-radius: 8px;
    padding: 9px 13px;
    font-size: 14px;
    color: var(--gray-800);
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
    appearance: none;
}
.field input:focus,
.field textarea:focus,
.field select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,86,160,.1);
}
.field textarea { resize: vertical; min-height: 72px; }

/* ── Upload Box ──────────────────────────────── */
.upload-box {
    border: 2px dashed var(--gray-200);
    border-radius: 8px;
    padding: 18px;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    position: relative;
}
.upload-box:hover, .upload-box.drag-over {
    border-color: var(--primary);
    background: var(--primary-lt);
}
.upload-box input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.upload-box .upload-icon { font-size: 28px; color: var(--gray-400); margin-bottom: 6px; }
.upload-box p { margin: 0; font-size: 13px; color: var(--gray-600); }
.upload-box p span { color: var(--primary); font-weight: 600; }
.upload-box .file-types { font-size: 11px; color: var(--gray-400); margin-top: 4px; }
.upload-preview {
    display: none;
    align-items: center;
    gap: 10px;
    background: var(--primary-lt);
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 10px;
}
.upload-preview .file-icon { font-size: 20px; }
.upload-preview .file-name { font-size: 13px; font-weight: 600; color: var(--primary); flex: 1; word-break: break-all; }
.upload-preview .btn-remove-file { background: none; border: none; color: var(--danger); cursor: pointer; font-size: 16px; padding: 0; }

/* ── Nominal Input ───────────────────────────── */
.nominal-wrap { position: relative; }
.nominal-wrap .prefix {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    font-size: 13px; font-weight: 600; color: var(--gray-600); pointer-events: none;
}
.nominal-wrap input { padding-left: 38px !important; }

/* ── Submit Button ───────────────────────────── */
.btn-submit-reimb {
    background: linear-gradient(135deg, var(--primary) 0%, #1e6ec8 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 11px 30px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: opacity .2s, transform .1s;
    box-shadow: 0 3px 10px rgba(26,86,160,.25);
}
.btn-submit-reimb:hover  { opacity: .9; }
.btn-submit-reimb:active { transform: scale(.98); }
.btn-submit-reimb:disabled { opacity: .6; cursor: not-allowed; }

/* ── Flash Alert ─────────────────────────────── */
.alert-reimb {
    border-radius: 8px;
    padding: 12px 18px;
    margin-bottom: 18px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 13px;
    font-weight: 500;
}
.alert-reimb.success { background: #dcfce7; border: 1px solid #86efac; color: #15803d; }
.alert-reimb.error   { background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; }
.alert-reimb i { margin-top: 1px; flex-shrink: 0; }

/* ── History Table ───────────────────────────── */
.history-section .section-title {
    font-size: 15px; font-weight: 700; color: var(--gray-800);
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
}
.history-section .section-title::after {
    content: ''; flex: 1; height: 1px; background: var(--gray-200);
}

.tbl-history { width: 100%; border-collapse: collapse; font-size: 13px;}
.tbl-history thead th {
    background: var(--gray-50);
    border-bottom: 2px solid var(--gray-200);
    padding: 10px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
}
.tbl-history tbody td {
    padding: 11px 14px;
    border-bottom: 1px solid var(--gray-100);
    color: var(--gray-800);
    vertical-align: middle;
}
.tbl-history tbody tr:hover { background: var(--gray-50); }
.tbl-history tbody tr:last-child td { border-bottom: none; }

/* Status badges */
.badge-status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .3px;
    white-space: nowrap;
}
.badge-status.pending  { background: #fef3c7; color: #92400e; }
.badge-status.approved { background: #dcfce7; color: #15803d; }
.badge-status.rejected { background: #fee2e2; color: #991b1b; }

/* Expand row detail nota */
.btn-expand {
    background: none; border: 1px solid var(--gray-200); border-radius: 6px;
    padding: 4px 10px; font-size: 12px; color: var(--primary);
    cursor: pointer; transition: all .15s;
    display: inline-flex; align-items: center; gap: 4px;
}
.btn-expand:hover { background: var(--primary-lt); border-color: var(--primary); }
.btn-expand .arrow { transition: transform .2s; }
.btn-expand.open .arrow { transform: rotate(180deg); }

.row-detail-nota td {
    background: var(--gray-50) !important;
    padding: 0 !important;
    border-bottom: 2px solid var(--gray-200) !important;
}
.detail-nota-inner { padding: 12px 16px 16px 42px; }
.tbl-nota-inner {
    width: 100%; border-collapse: collapse; font-size: 12px;
    background: #fff; border-radius: 8px; overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.tbl-nota-inner th {
    background: var(--primary); color: #fff;
    padding: 8px 12px; text-align: left; font-size: 11px; font-weight: 600;
}
.tbl-nota-inner td { padding: 8px 12px; border-bottom: 1px solid var(--gray-100); }
.tbl-nota-inner tr:last-child td { border-bottom: none; }
.tbl-nota-inner .link-file {
    color: var(--primary); text-decoration: none; font-weight: 600;
    display: inline-flex; align-items: center; gap: 4px;
}
.tbl-nota-inner .link-file:hover { text-decoration: underline; }

/* Loading spinner */
.spinner-sm {
    width: 16px; height: 16px;
    border: 2px solid var(--gray-200); border-top-color: var(--primary);
    border-radius: 50%; animation: spin .6s linear infinite; display: inline-block;
}
@keyframes spin { to { transform: rotate(360deg); } }

.empty-history {
    text-align: center; padding: 36px 0;
    color: var(--gray-400); font-size: 13px;
}
.empty-history i { font-size: 36px; display: block; margin-bottom: 8px; opacity: .5; }

@media (max-width: 600px) {
    .form-row-custom .field { flex: 100%; }
    .karyawan-info-bar { flex-wrap: wrap; }
    .karyawan-info-bar .badges { margin-left: 0; }
}


</style>

<div class="container-fluid">

  <section class="content">
    <div class="reimb-wrapper">

      <!-- ── FLASH MESSAGE ─────────────────── -->
      <?php if ($this->session->flashdata('pesan_success')): ?>
        <div class="alert-reimb success">
          <i class="fa fa-check-circle"></i>
          <?= $this->session->flashdata('pesan_success') ?>
        </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('pesan')): ?>
        <div class="alert-reimb error">
          <i class="fa fa-exclamation-circle"></i>
          <?= $this->session->flashdata('pesan') ?>
        </div>
      <?php endif; ?>

      <!-- ── FORM INPUT NOTA ────────────────── -->
      <div class="card-reimb">
        <div class="card-head">
          <div class="icon"><i class="fa fa-file-text-o"></i></div>
          <h4>Input Nota Reimbursement</h4>
        </div>
        <div class="card-body">

          <form method="POST" action="<?= site_url($url) ?>" enctype="multipart/form-data" id="formReimubrse">

            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                   value="<?= $this->security->get_csrf_hash() ?>">
            <!-- <input type="hidden" name="id_karyawan" value="<?= $id_karyawan ?>"> -->
            <!-- Opsional: jika ingin append ke reimbursement yang sudah ada,
                 kirim id_reimbursement. Kosongkan untuk auto-create header baru. -->
            <!-- <input type="hidden" name="id_reimbursement" value=""> -->
            <!-- <input type="hidden" name="signature" value="<?= $signature ?>"> -->

            <div class="form-row-custom">

              <!-- Pilih Kategori Reimbursement -->
              <div class="field" style="flex:0 0 180px;">
                <label>Kategori <span class="req">*</span></label>
                <select name="id_kategori" id="id_kategori" class="form-control select2" required>
                  <option value=""></option>
                  <?php foreach ($list_kategori as $k) { ?>
                    <option value="<?= $k->id ?>"><?= $k->nama_kategori ?></option>
                  <?php } ?>
                </select>
              </div>

              <!-- Pilih User -->
              <div class="field" style="flex:0 0 180px;">
                <label>Username Karyawan <span class="req">*</span></label>
                <select name="id_karyawan" id="id_karyawan" class="form-control select2" required>
                    <option value=""></option>
                    <?php foreach ($list_karyawan as $a) { ?>
                        <option value="<?= $a->id ?>"><?= $a->nama_lengkap ?></option>
                        <!-- <input type="hidden" name="id_karyawan" value="<?= $a->id ?>"> -->
                    <?php } ?>
                </select>
              </div>

              <!-- Tanggal Nota -->
              <div class="field" style="flex:0 0 180px;">
                <label>Tanggal Nota <span class="req">*</span></label>
                <input type="date" name="tanggal_nota" id="tanggal_nota"
                       value="<?= date('Y-m-d') ?>" required>
              </div>

              <!-- Nominal -->
              <div class="field" style="flex:0 0 210px;">
                <label>Nominal <span class="req">*</span></label>
                <div class="nominal-wrap">
                  <span class="prefix">Rp</span>
                  <input type="text" name="nominal" id="nominal"
                         placeholder="0" autocomplete="off" required>
                </div>
              </div>

              <!-- Keterangan -->
              <div class="field full">
                <label>Keterangan / Keperluan <span class="req">*</span></label>
                <input type="text" name="keterangan" id="keterangan"
                       placeholder="Contoh: Biaya berobat" 
                       maxlength="255" required>
              </div>

              <!-- Upload File Nota -->
              <div class="field full">
                <label>File Nota / Bukti <small style="font-weight:400;text-transform:none;color:var(--gray-400)">(opsional · JPG, PNG, PDF · maks 2MB)</small></label>
                <div class="upload-box" id="uploadBox">
                  <input type="file" name="file_nota" id="fileNota"
                         accept=".jpg,.jpeg,.png,.pdf">
                  <div id="uploadPlaceholder">
                    <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                    <p>Drag &amp; drop file di sini atau <span>klik untuk pilih</span></p>
                    <p class="file-types">JPG · PNG · PDF</p>
                  </div>
                </div>
                <!-- Preview setelah file dipilih -->
                <div class="upload-preview" id="uploadPreview">
                  <span class="file-icon" id="previewIcon">📄</span>
                  <span class="file-name" id="previewName"></span>
                  <button type="button" class="btn-remove-file" id="btnRemoveFile" title="Hapus file">
                    <i class="fa fa-times-circle"></i>
                  </button>
                </div>
              </div>

            </div><!-- /.form-row-custom -->

            <div style="margin-top:20px; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
              <button type="submit" class="btn-submit-reimb" id="btnSubmit">
                <i class="fa fa-paper-plane"></i> Kirim Nota
              </button>
              <span style="font-size:12px; color:var(--gray-400)">
                <i class="fa fa-info-circle"></i> Setiap nota akan tercatat dalam riwayat di bawah
              </span>
            </div>

          </form>
        </div>
      </div><!-- /.card-reimb -->

      <!-- ── TABEL REIMBURSEMENT (TAMBAHAN) ───────────────── -->
      <!-- <div class="card-reimb history-section"> -->
        <!-- <div class="card-head">
          <div class="icon"><i class="fa fa-table"></i></div>
          <h4>Daftar Reimbursement</h4>
        </div> -->

        <!-- <div class="card-body" style="padding:0;"> -->
          <!-- <div style="overflow-x:auto;"> -->
            <div class="card-reimb" style="padding:16px; margin-top:10px;">
              <form method="post" action="<?= $url_search ?>">
                
                <div class="form-row-custom">

                  <div class="field" style="flex:0 0 250px;">
                    <label>Nama Karyawan</label>
                    <select name="id_karyawan" class="form-control select2">
                      <option value=""></option>
                      <?php foreach ($list_karyawan as $a) { ?>
                        <option value="<?= $a->id ?>"><?= $a->nama_lengkap ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <!-- From Bulan -->
                  <div class="field" style="flex:0 0 160px;">
                    <label>From</label>
                    <input type="month" name="bulan_from" class="form-control">
                  </div>

                  <!-- To Bulan -->
                  <div class="field" style="flex:0 0 160px;">
                    <label>To</label>
                    <input type="month" name="bulan_to" class="form-control">
                  </div>

                  <div class="field" style="flex:0 0 auto; display:flex; align-items:flex-end; gap:8px;">
                    <button type="submit" value="1" name="search" class="btn btn-outline-danger btn-sm">
                      Search
                    </button>

                    <button type="submit" value="2" name="search" class="btn btn-outline-danger btn-sm">
                      Export CSV
                    </button>

                    <a href="<?= site_url('management_karyawan/approve_all') ?>" class="btn btn-outline-dark btn-sm" id="btnApproveAll">
                      Approve All
                    </a>
                  </div>

                </div>

              </form>
            
              <table class="tbl-history" id="tableReimbursement">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>No Pengajuan</th>
                    <th>Tanggal</th>
                    <th>Nama Lengkap</th>
                    <th>Department</th>
                    <th>Divisi</th>
                    <th>Job Level</th>
                    <th>Kategori</th>
                    <th>Total</th>
                    <th>Lampiran</th>
                    <th>Status</th>
                  </tr>
                </thead>

                <tbody>
                  <!-- <?php if (!empty($reimbursement_list)): ?> -->
                    <?php $no = 1; foreach ($reimbursement_list as $r): ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td style="font-weight:600; color:var(--primary);">
                          <?= $r->no_pengajuan ?>
                        </td>
                        <td>
                          <i class="fa fa-calendar-o" style="color:var(--gray-400);margin-right:5px;"></i>
                          <?= date('d-m-Y', strtotime($r->tanggal_pengajuan)) ?>
                        </td>

                        <td style="font-weight:600;"><?= $r->nama_lengkap ?></td>
                        <td><?= $r->departement ?></td>
                        <td><?= $r->divisi ?></td>
                        <td><?= $r->job_level ?></td>
                        <td><?= $r->nama_kategori ?></td>

                        <td style="font-weight:700;color:var(--success);">
                          Rp <?= number_format($r->total, 0, ',', '.') ?>
                        </td>

                        <td>
                          <?php if ($r->file_nota): ?>
                            <a href="<?= base_url('assets/uploads/karyawan/'.$r->file_nota) ?>" 
                              target="_blank" class="link-file">
                              <i class="fa fa-paperclip"></i> Lihat
                            </a>
                          <?php else: ?>
                            <span style="color:var(--gray-400)">—</span>
                          <?php endif; ?>
                        </td>

                        <td>
                          <?php if ($r->status == 1): // pending ?>
                              <a href="<?= site_url('management_karyawan/approve_reimbursement/' . $r->id) ?>" class="btn btn-sm pending-finance" name="id">Pending Release</a>
                              <!-- <button class="btn btn-sm pending-finance" id="btn-Approve" data-id="<?= $r->id ?>"> Pending Release </button> -->
                          <?php elseif ($r->status == 2): ?>
                              <span class="badge-status approved">Released</span>
                          <?php elseif ($r->status == 9): ?>
                              <span class="badge-status rejected">Rejected</span>
                          <?php endif; ?>
                        </td>

                      </tr>
                    <?php endforeach; ?>
                  <!-- <?php else: ?>
                    <tr>
                      <td colspan="11" class="empty-history">
                        <i class="fa fa-inbox"></i>
                        Belum ada data reimbursement
                      </td>
                    </tr>
                  <?php endif; ?> -->
                </tbody>

              </table>
            </div>
          <!-- </div> -->
        <!-- </div> -->
      <!-- </div> -->

    </div>
  </section>
</div>

<script>
// ── Admin: redirect saat pilih karyawan ──────────
function gotoKaryawan(id) {
    if (id) window.location.href = '<?= site_url('management_karyawan/reimbursement') ?>?id_karyawan=' + id;
}

// ── Format nominal (titik ribuan) ────────────────
document.getElementById('nominal') && (function(){
    var el = document.getElementById('nominal');
    el.addEventListener('input', function() {
        var raw = this.value.replace(/\D/g, '');
        this.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    });
})();

// ── Upload preview ───────────────────────────────
(function(){
    var inputFile   = document.getElementById('fileNota');
    var uploadBox   = document.getElementById('uploadBox');
    var placeholder = document.getElementById('uploadPlaceholder');
    var preview     = document.getElementById('uploadPreview');
    var previewName = document.getElementById('previewName');
    var previewIcon = document.getElementById('previewIcon');
    var btnRemove   = document.getElementById('btnRemoveFile');

    if (!inputFile) return;

    function showPreview(file) {
        var isPdf = file.name.toLowerCase().endsWith('.pdf');
        previewIcon.textContent = isPdf ? '📄' : '🖼️';
        previewName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        placeholder.style.display = 'none';
        preview.style.display     = 'flex';
        uploadBox.style.border    = '2px dashed var(--primary)';
        uploadBox.style.background= 'var(--primary-lt)';
    }

    function clearPreview() {
        inputFile.value   = '';
        placeholder.style.display = '';
        preview.style.display     = 'none';
        uploadBox.style.border    = '';
        uploadBox.style.background= '';
    }

    inputFile.addEventListener('change', function() {
        if (this.files && this.files[0]) showPreview(this.files[0]);
    });

    btnRemove.addEventListener('click', function(e) {
        e.stopPropagation();
        clearPreview();
    });

    // Drag & drop visual
    uploadBox.addEventListener('dragover',  function(e){ e.preventDefault(); this.classList.add('drag-over'); });
    uploadBox.addEventListener('dragleave', function(){ this.classList.remove('drag-over'); });
    uploadBox.addEventListener('drop', function(e){
        e.preventDefault(); this.classList.remove('drag-over');
        if (e.dataTransfer.files[0]) {
            inputFile.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        }
    });
})();

// ── Expand detail nota via AJAX ──────────────────
var loadedDetail = {};

function toggleDetail(btn, id) {
    var rowDetail = document.getElementById('detail-' + id);
    var inner     = document.getElementById('detail-inner-' + id);
    var isOpen    = btn.classList.contains('open');

    // Tutup semua yang lain
    document.querySelectorAll('.btn-expand.open').forEach(function(b) {
        if (b !== btn) {
            b.classList.remove('open');
            b.querySelector('span').textContent = 'Lihat';
            var rid = b.getAttribute('onclick').match(/\d+/)[0];
            var rd  = document.getElementById('detail-' + rid);
            if (rd) rd.style.display = 'none';
        }
    });

    if (isOpen) {
        btn.classList.remove('open');
        btn.querySelector('span').textContent = 'Lihat';
        rowDetail.style.display = 'none';
        return;
    }

    btn.classList.add('open');
    btn.querySelector('span').textContent = 'Tutup';
    rowDetail.style.display = 'table-row';

    // Jika sudah pernah dimuat, langsung tampil
    if (loadedDetail[id]) return;

    // AJAX load
    fetch('<?= site_url('management_karyawan/reimbursement_detail_ajax/') ?>' + id)
        .then(function(r){ return r.json(); })
        .then(function(res){
            loadedDetail[id] = true;
            if (!res.data || res.data.length === 0) {
                inner.innerHTML = '<p style="color:var(--gray-400);font-size:12px;margin:4px 0">Tidak ada nota.</p>';
                return;
            }
            var rows = res.data.map(function(d, i){
                var fileHtml = d.file_nota
                    ? '<a href="<?= base_url() ?>' + d.file_nota + '" target="_blank" class="link-file"><i class="fa fa-paperclip"></i> Lihat File</a>'
                    : '<span style="color:var(--gray-400)">—</span>';
                var nominal = parseInt(d.nominal).toLocaleString('id-ID');
                var tgl     = new Date(d.tanggal_nota);
                var tglFmt  = tgl.toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'});
                return '<tr>'
                     + '<td style="width:30px">'+(i+1)+'</td>'
                     + '<td style="white-space:nowrap">'+tglFmt+'</td>'
                     + '<td>'+escHtml(d.keterangan)+'</td>'
                     + '<td style="text-align:right;font-weight:700;">Rp '+nominal+'</td>'
                     + '<td>'+fileHtml+'</td>'
                     + '</tr>';
            }).join('');
            inner.innerHTML = '<table class="tbl-nota-inner">'
                + '<thead><tr><th>#</th><th>Tgl Nota</th><th>Keterangan</th><th class="text-right">Nominal</th><th>File Nota</th></tr></thead>'
                + '<tbody>' + rows + '</tbody>'
                + '</table>';
        })
        .catch(function(){
            inner.innerHTML = '<p style="color:var(--danger);font-size:12px">Gagal memuat data.</p>';
        });
}

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Auto-dismiss flash alert ─────────────────────
setTimeout(function(){
    document.querySelectorAll('.alert-reimb').forEach(function(el){
        el.style.transition = 'opacity .5s';
        el.style.opacity    = '0';
        setTimeout(function(){ el.remove(); }, 500);
    });
}, 4000);
</script>

<script>
  $(document).ready(function() {
      $('#tableReimbursement').DataTable({
          pageLength: 10,
          ordering: true,
          searching: true,
          lengthChange: false,
          language: {
              search: "Search:",
              paginate: {
                  previous: "←",
                  next: "→"
              }
          }
      });
  });
  // $(document).ready(function() {
  //     var table = $('#tableReimbursement').DataTable({
  //         pageLength: 10,
  //         ordering: true,
  //         searching: true,
  //         lengthChange: false,
  //         columnDefs: [
  //             { orderable: false, targets: '_all' }
  //         ],
  //         createdRow: function(row) {
  //             // pastikan setiap row punya 10 kolom
  //         },
  //         language: {
  //             search: "Search:",
  //             paginate: {
  //                 previous: "←",
  //                 next: "→"
  //             }
  //         }
  //     });
  // });
</script>


<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: "-- Pilih --",
        allowClear: true,
        width: '100%'
    });
});
</script>

<script>
$('#btnApproveAll').on('click', function (e) {
    e.preventDefault();

    var url = $(this).attr('href');

    $.ajax({
        url: '<?= site_url("management_karyawan/count_pending") ?>',
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            console.log('RES:', res);

            var total = parseInt(res.total);

            console.log('TOTAL:', total);

            if (!total || total === 0) {
                alert('Tidak ada data pending.');
                return;
            }

            var confirmText = 'Ada ' + total + ' data pending release.\n\nYakin ingin approve semua?';

            if (confirm(confirmText)) {
                window.location.href = url;
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('Gagal mengambil data.');
        }
    });
});
</script>