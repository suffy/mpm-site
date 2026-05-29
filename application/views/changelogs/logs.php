<?php
/*
 * VIEW: changelogs/logs
 * Di-render via $this->render(...) — sudah dalam wrapper main template
 * Bootstrap 5 dark/light compatible via var(--bs-*)
 */

/*
 * $aktif_per_fn = array dari controller, contoh:
 * [ 11 => 2, 14 => 3, 20 => 1 ]
 * artinya function 14 sudah penuh (3 aktif), yang lain masih bisa
 */
?>

<style>
.hl-wrap { max-width: 1350px; margin: 0 auto; padding: 8px 0 60px; }

.hl-page-title { font-size: 24px; font-weight: 700; color: var(--bs-body-color); letter-spacing: -.4px; margin: 0 0 4px; }
.hl-page-title i { color: #667eea; margin-right: 8px; }
.hl-page-sub { font-size: 13px; color: var(--bs-secondary-color); margin: 0; }

.hl-panel {
  background: var(--bs-body-bg); border: 1.5px solid var(--bs-border-color);
  border-radius: 14px; padding: 24px 26px; margin-bottom: 20px; transition: box-shadow .2s;
  animation: hlFade .3s ease both;
}
.hl-panel:nth-child(2) { animation-delay: .06s; }
.hl-panel:nth-child(3) { animation-delay: .12s; }
@keyframes hlFade { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.hl-panel:hover { box-shadow: 0 4px 20px rgba(0,0,0,.06); }
[data-bs-theme="dark"] .hl-panel:hover { box-shadow: 0 4px 20px rgba(0,0,0,.3); }

.hl-panel-title { font-size: 15px; font-weight: 700; color: var(--bs-body-color); display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
.hl-panel-title i { color: #667eea; }

.hl-form-group { margin-bottom: 16px; }
.hl-label { display: block; font-size: 12.5px; font-weight: 600; color: var(--bs-secondary-color); letter-spacing: .04em; text-transform: uppercase; margin-bottom: 7px; }
.hl-label .req { color: #e74c3c; margin-left: 2px; }

.hl-input, .hl-textarea {
  width: 100%; padding: 10px 14px; font-size: 13.5px; font-family: inherit;
  background: var(--bs-tertiary-bg); border: 1.5px solid var(--bs-border-color);
  border-radius: 9px; color: var(--bs-body-color); outline: none;
  transition: border-color .2s, box-shadow .2s; -webkit-appearance: none;
}
.hl-input:focus, .hl-textarea:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.15); background: var(--bs-body-bg); }
.hl-input::placeholder, .hl-textarea::placeholder { color: var(--bs-secondary-color); opacity: .7; }
.hl-textarea { resize: vertical; min-height: 100px; line-height: 1.65; }
.hl-char-hint { font-size: 11px; color: var(--bs-secondary-color); text-align: right; margin-top: 4px; }

/* ── CHECKBOX GRID ── */
.hl-fn-topbar { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.hl-fn-selectall-btn {
  display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px;
  font-size: 12px; font-weight: 600; font-family: inherit;
  border: 1.5px solid var(--bs-border-color); border-radius: 7px;
  background: var(--bs-tertiary-bg); color: var(--bs-secondary-color);
  cursor: pointer; transition: all .15s;
}
.hl-fn-selectall-btn:hover { border-color: #667eea; color: #667eea; }
.hl-fn-count { font-size: 12px; color: var(--bs-secondary-color); }
.hl-fn-count strong { color: #667eea; }

.hl-fn-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
  gap: 7px; padding: 12px; background: var(--bs-tertiary-bg);
  border: 1.5px solid var(--bs-border-color); border-radius: 9px;
  max-height: 210px; overflow-y: auto;
}
.hl-fn-grid::-webkit-scrollbar { width: 3px; }
.hl-fn-grid::-webkit-scrollbar-thumb { background: var(--bs-border-color); border-radius: 4px; }

.hl-fn-item {
  display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 8px;
  cursor: pointer; border: 1.5px solid var(--bs-border-color);
  background: var(--bs-body-bg); transition: all .15s; user-select: none;
}
.hl-fn-item:hover { border-color: #667eea; }
.hl-fn-item.is-checked { border-color: #667eea; background: rgba(102,126,234,.08); }
[data-bs-theme="dark"] .hl-fn-item.is-checked { background: rgba(102,126,234,.18); }
.hl-fn-item input[type="checkbox"] { display: none; }
.hl-fn-box {
  width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0;
  border: 2px solid var(--bs-border-color); background: var(--bs-body-bg);
  display: flex; align-items: center; justify-content: center; transition: all .15s;
}
.hl-fn-item.is-checked .hl-fn-box { background: #667eea; border-color: #667eea; }
.hl-fn-box svg { display: none; }
.hl-fn-item.is-checked .hl-fn-box svg { display: block; }
.hl-fn-name { font-size: 12.5px; font-weight: 500; color: var(--bs-body-color); flex: 1; }
.hl-fn-item.is-checked .hl-fn-name { font-weight: 600; color: #667eea; }
[data-bs-theme="dark"] .hl-fn-item.is-checked .hl-fn-name { color: #a5b4fc; }
.hl-fn-item.is-disabled { opacity: .45; cursor: not-allowed; background: var(--bs-tertiary-bg); border-color: var(--bs-border-color) !important; }
.hl-fn-item.is-disabled:hover { border-color: var(--bs-border-color) !important; }
.hl-fn-off {
  font-size: 10px; font-weight: 600; color: var(--bs-secondary-color);
  background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color);
  padding: 1px 6px; border-radius: 20px; white-space: nowrap; margin-left: auto;
}

/* submit btn */
.hl-btn-submit {
  display: inline-flex; align-items: center; gap: 8px; padding: 11px 24px;
  font-size: 14px; font-weight: 600; font-family: inherit;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff; border: none; border-radius: 10px; cursor: pointer; transition: all .2s;
  box-shadow: 0 3px 10px rgba(102,126,234,.3);
}
.hl-btn-submit:hover { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); transform: translateY(-1px); box-shadow: 0 5px 16px rgba(102,126,234,.4); }
.hl-btn-submit:active { transform: translateY(0); }

/* ── TABLE ── */
table.hl-table { width: 100%; border-collapse: collapse; font-size: 13px; margin: 0; }
.hl-table thead th { padding: 10px 14px; font-size: 11px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: var(--bs-secondary-color); background: var(--bs-tertiary-bg); border-bottom: 2px solid var(--bs-border-color); white-space: nowrap; }
.hl-table tbody tr { border-bottom: 1px solid var(--bs-border-color); transition: background .15s; }
.hl-table tbody tr:last-child { border-bottom: none; }
.hl-table tbody tr:hover { background: var(--bs-tertiary-bg); }
.hl-table td { padding: 11px 14px; color: var(--bs-body-color); vertical-align: middle; }

.hl-badge-aktif { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; }
.hl-badge-aktif.on { background: rgba(39,174,96,.12); color: #27ae60; border: 1px solid rgba(39,174,96,.3); }
.hl-badge-aktif.off { background: var(--bs-tertiary-bg); color: var(--bs-secondary-color); border: 1px solid var(--bs-border-color); }
[data-bs-theme="dark"] .hl-badge-aktif.on { background: rgba(39,174,96,.2); color: #5dca80; }

.hl-menu-chips { display: flex; flex-wrap: wrap; gap: 4px; }
.hl-menu-chip { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 20px; font-size: 10.5px; font-weight: 600; background: rgba(102,126,234,.1); color: #667eea; border: 1px solid rgba(102,126,234,.2); white-space: nowrap; }
[data-bs-theme="dark"] .hl-menu-chip { background: rgba(102,126,234,.2); color: #a5b4fc; }

.hl-title-text { font-weight: 600; font-size: 13px; color: var(--bs-body-color); }
.hl-changes-cell { color: var(--bs-secondary-color); font-size: 12.5px; line-height: 1.5; max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.hl-time { display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; color: var(--bs-secondary-color); white-space: nowrap; }

/* action buttons */
.hl-actions { display: flex; align-items: center; gap: 5px; flex-wrap: nowrap; }
.hl-btn-act {
  display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px;
  font-size: 11px; font-weight: 600; font-family: inherit; border-radius: 7px;
  cursor: pointer; transition: all .18s; text-decoration: none; border: 1.5px solid; white-space: nowrap;
}
.hl-btn-act.activate { background: rgba(39,174,96,.1); color: #27ae60; border-color: rgba(39,174,96,.25); }
.hl-btn-act.activate:hover { background: #27ae60; color: #fff; border-color: #27ae60; transform: translateY(-1px); text-decoration: none; }
.hl-btn-act.deactivate { background: rgba(245,158,11,.1); color: #d97706; border-color: rgba(245,158,11,.25); }
.hl-btn-act.deactivate:hover { background: #d97706; color: #fff; border-color: #d97706; transform: translateY(-1px); text-decoration: none; }
.hl-btn-act.disabled-act { background: var(--bs-tertiary-bg); color: var(--bs-secondary-color); border-color: var(--bs-border-color); opacity: .45; cursor: not-allowed; pointer-events: none; }
.hl-btn-del { display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; font-size: 11px; font-weight: 600; font-family: inherit; background: rgba(231,76,60,.1); color: #e74c3c; border: 1.5px solid rgba(231,76,60,.25); border-radius: 7px; cursor: pointer; transition: all .18s; text-decoration: none; }
.hl-btn-del:hover { background: #e74c3c; color: #fff; border-color: #e74c3c; text-decoration: none; transform: translateY(-1px); }
[data-bs-theme="dark"] .hl-btn-del { background: rgba(231,76,60,.15); border-color: rgba(231,76,60,.3); }

/* active info bar */
.hl-active-info {
  display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
  padding: 10px 14px; border-radius: 9px; margin-bottom: 16px; font-size: 13px;
  background: rgba(102,126,234,.07); border: 1.5px solid rgba(102,126,234,.18); color: var(--bs-body-color);
}

/* empty + alert */
.hl-empty { text-align: center; padding: 56px 24px; }
.hl-empty-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--bs-tertiary-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 26px; color: var(--bs-secondary-color); }
.hl-empty h5 { font-size: 16px; font-weight: 700; color: var(--bs-body-color); margin-bottom: 5px; }
.hl-empty p  { font-size: 13px; color: var(--bs-secondary-color); margin: 0; }

.hl-alert { padding: 12px 16px; border-radius: 10px; font-size: 13.5px; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; border: 1.5px solid; }
.hl-alert-success { background: rgba(39,174,96,.1); border-color: rgba(39,174,96,.3); color: #27ae60; }
.hl-alert-danger  { background: rgba(231,76,60,.1); border-color: rgba(231,76,60,.3); color: #e74c3c; }
[data-bs-theme="dark"] .hl-alert-success { background: rgba(39,174,96,.15); color: #5dca80; }
[data-bs-theme="dark"] .hl-alert-danger  { background: rgba(231,76,60,.15); color: #f1948a; }

.hl-count-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; background: rgba(102,126,234,.12); color: #667eea; border: 1px solid rgba(102,126,234,.25); margin-left: 8px; }
[data-bs-theme="dark"] .hl-count-badge { background: rgba(102,126,234,.2); color: #a5b4fc; }

/* DataTables override */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
  background: var(--bs-tertiary-bg); border: 1.5px solid var(--bs-border-color);
  border-radius: 7px; color: var(--bs-body-color); padding: 5px 10px;
  outline: none; font-family: inherit; font-size: 13px;
}
.dataTables_wrapper .dataTables_filter input:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.15); }
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate { font-size: 13px; color: var(--bs-secondary-color); padding: 10px 0; }
.dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 7px !important; font-size: 13px; color: var(--bs-body-color) !important; border: 1px solid transparent !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: rgba(102,126,234,.1) !important; border-color: rgba(102,126,234,.2) !important; color: #667eea !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #667eea !important; border-color: #667eea !important; color: #fff !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: .4; }
</style>

<div class="hl-wrap">

  <!-- ALERTS -->
  <?php if ($this->session->flashdata('pesan_success')): ?>
  <div class="hl-alert hl-alert-success">
    <i class="bi bi-check-circle-fill"></i>
    <?= $this->session->flashdata('pesan_success') ?>
  </div>
  <?php elseif ($this->session->flashdata('pesan')): ?>
  <div class="hl-alert hl-alert-danger">
    <i class="bi bi-exclamation-circle-fill"></i>
    <?= $this->session->flashdata('pesan') ?>
  </div>
  <?php endif; ?>

  <!-- PAGE HEADER -->
  <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div>
      <h1 class="hl-page-title"><i class="bi bi-clock-history"></i><?= $title ?></h1>
      <!-- <p class="hl-page-sub">Catat dan kelola perubahan dari setiap menu aplikasi.</p> -->
    </div>
  </div>

  <!-- FORM PANEL -->
  <div class="hl-panel">
    <div class="hl-panel-title">
      <i class="bi bi-plus-circle-fill"></i>
      Tambah Data
    </div>

    <?php echo form_open($url, array('enctype' => 'multipart/form-data')); ?>

      <!-- Judul -->
      <div class="hl-form-group">
        <label class="hl-label">Title <span class="req">*</span></label>
        <input type="text" name="title" id="titleInput" class="hl-input"
               placeholder="Contoh: Penambahan fitur export PDF" maxlength="255" required>
        <div class="hl-char-hint"><span id="titleCount">0</span> / 255</div>
      </div>

      <!-- Detail -->
      <div class="hl-form-group">
        <label class="hl-label">Keterangan <span class="req">*</span></label>
        <textarea name="changes" id="changesArea" class="hl-textarea"
                  placeholder="Deskripsikan perubahan yang dilakukan secara detail..."
                  required></textarea>
        <div class="hl-char-hint"><span id="changesCount">0</span> karakter</div>
      </div>

      <!-- Upload Foto -->
      <div class="hl-form-group">
        <label class="hl-label">
          image
          <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--bs-secondary-color);">— opsional</span>
        </label>
        <input type="file" name="foto" accept="image/*" class="hl-input" style="padding:7px 14px;cursor:pointer;">
      </div>

      <!-- Multi checkbox menu -->
      <div class="hl-form-group">
        <label class="hl-label">Visibility <span class="req">*</span></label>

        <div class="hl-fn-topbar">
          <button type="button" class="hl-fn-selectall-btn" id="btnSelectAll" onclick="toggleSelectAll()">
            <i class="bi bi-check2-square"></i> Pilih Semua
          </button>
          <span class="hl-fn-count">Dipilih: <strong id="fnSelectedCount">0</strong> menu</span>
        </div>

        <div class="hl-fn-grid" id="fnGrid">
          <?php foreach ($function_names->result() as $func): ?>
          <?php $disabled = ($func->active == 0); ?>
          <label class="hl-fn-item <?= $disabled ? 'is-disabled' : '' ?>"
                 <?= $disabled ? '' : 'onclick="toggleFnItem(this)"' ?>>
            <input type="checkbox" name="function_ids[]" value="<?= $func->id ?>"
                   <?= $disabled ? 'disabled' : '' ?>>
            <div class="hl-fn-box">
              <svg width="10" height="8" viewBox="0 0 10 8" fill="none">
                <path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <span class="hl-fn-name"><?= htmlspecialchars($func->menu) ?></span>
          </label>
          <?php endforeach; ?>
        </div>
      </div>

      <button type="submit" class="hl-btn-submit">
        <i class="bi bi-save2-fill"></i> Simpan Perubahan
      </button>

    <?php echo form_close(); ?>
  </div>

  <!-- TABLE PANEL -->
  <div class="hl-panel">
    <div class="hl-panel-title">
      <i class="bi bi-list-ul"></i>
      Daftar Perubahan
      <span class="hl-count-badge" id="logCountBadge">— data</span>
    </div>

    <?php
      $log_rows  = $logs->result();
      $log_count = count($log_rows);

      // aktif_per_fn: [id_function => count] dari controller
      // dipakai untuk cek per-row apakah tombol On boleh ditampilkan
    ?>
    <script>document.getElementById('logCountBadge').textContent = '<?= $log_count ?> data';</script>

    <!-- Info aktif per menu -->
    <div class="hl-active-info">
      <i class="bi bi-broadcast" style="color:#667eea;font-size:15px;flex-shrink:0;"></i>
      <span style="font-size:13px;">
        Batas aktif per menu: <strong>3</strong>.
        Setiap menu dihitung terpisah — satu changelog bisa aktif di banyak menu selama belum penuh.
      </span>
    </div>

    <?php if ($log_count > 0): ?>

    <table class="hl-table" id="dtLogs">
      <thead>
        <tr>
          <th style="width:4%;">#</th>
          <th style="width:9%;">Status</th>
          <th style="width:17%;">Judul</th>
          <th style="width:20%;">Detail Perubahan</th>
          <th style="width:20%;">Tampil di Menu</th>
          <th style="width:9%;">Foto</th>
          <th style="width:10%;">Dibuat</th>
          <th style="width:6%;">Oleh</th>
          <th style="width:5%;text-align:center;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($log_rows as $i => $log): ?>
        <?php
          $is_aktif = ($log->status_aktif == 1);

          // Cek apakah semua function dari changelog ini sudah penuh
          // jika salah satu function sudah >= 3 aktif, tombol On di-disable
          $fn_penuh = false;
          if (!$is_aktif && !empty($log->menus)) {
            // id_functions log ini ada di kolom fn_ids (tambah di query get_all_changelogs)
            if (!empty($log->fn_ids)) {
              foreach (explode(',', $log->fn_ids) as $fn_id) {
                $fn_id = (int)trim($fn_id);
                if (isset($aktif_per_fn[$fn_id]) && $aktif_per_fn[$fn_id] >= 3) {
                  $fn_penuh = true;
                  break;
                }
              }
            }
          }
        ?>
        <tr>
          <td style="color:var(--bs-secondary-color);font-size:12px;"><?= $i + 1 ?></td>

          <!-- Status -->
          <td>
            <?php if ($is_aktif): ?>
              <span class="hl-badge-aktif on">
                <i class="bi bi-broadcast" style="font-size:9px;"></i> Aktif
              </span>
            <?php else: ?>
              <span class="hl-badge-aktif off">
                <i class="bi bi-pause-circle" style="font-size:9px;"></i> Nonaktif
              </span>
            <?php endif; ?>
          </td>

          <!-- Judul -->
          <td><div class="hl-title-text"><?= htmlspecialchars($log->title ? $log->title : '—') ?></div></td>

          <!-- Detail -->
          <td>
            <div class="hl-changes-cell" title="<?= htmlspecialchars($log->changes) ?>">
              <?= htmlspecialchars($log->changes) ?>
            </div>
          </td>

          <!-- Menu chips -->
          <td>
            <div class="hl-menu-chips">
              <?php if (!empty($log->menus)): ?>
                <?php foreach (explode('||', $log->menus) as $mn): ?>
                  <span class="hl-menu-chip">
                    <i class="bi bi-grid-3x3-gap-fill" style="font-size:8px;"></i>
                    <?= htmlspecialchars(trim($mn)) ?>
                  </span>
                <?php endforeach; ?>
              <?php else: ?>
                <span style="color:var(--bs-secondary-color);font-size:12px;">—</span>
              <?php endif; ?>
            </div>
          </td>

          <!-- Foto thumbnail -->
          <td>
            <?php if (!empty($log->foto)): ?>
              <?php $fotoUrl = base_url('assets/uploads/changelog/'.date('Y', strtotime($log->created_at)).'/'.$log->foto); ?>
              <a href="<?= $fotoUrl ?>" target="_blank">
                <img src="<?= $fotoUrl ?>" alt="foto"
                     style="width:44px;height:44px;object-fit:cover;border-radius:6px;border:1px solid var(--bs-border-color);">
              </a>
            <?php else: ?>
              <span style="color:var(--bs-secondary-color);font-size:12px;">—</span>
            <?php endif; ?>
          </td>

          <!-- Dibuat -->
          <td>
            <span class="hl-time">
              <i class="bi bi-clock" style="font-size:10px;"></i>
              <?= date('d/m/Y H:i', strtotime($log->created_at)) ?>
            </span>
          </td>

          <!-- Oleh -->
          <td style="font-size:12.5px;color:var(--bs-secondary-color);">
            <?= htmlspecialchars($log->username) ?>
          </td>

          <!-- Aksi -->
          <td>
            <div class="hl-actions">
              <?php if ($is_aktif): ?>
                <!-- Nonaktifkan -->
                <a href="<?= base_url('changelogs/toggle_change_status/'.$log->signature.'/0') ?>"
                   class="hl-btn-act deactivate"
                   onclick="return confirm('Nonaktifkan update ini dari popup?')"
                   title="Nonaktifkan">
                  <i class="bi bi-pause-circle"></i> Off
                </a>
              <?php elseif ($fn_penuh): ?>
                <!-- Salah satu function sudah penuh -->
                <span class="hl-btn-act disabled-act"
                      title="Salah satu menu sudah mencapai batas 3 aktif">
                  <i class="bi bi-broadcast"></i> On
                </span>
              <?php else: ?>
                <!-- Aktifkan -->
                <a href="<?= base_url('changelogs/toggle_change_status/'.$log->signature.'/1') ?>"
                   class="hl-btn-act activate"
                   onclick="return confirm('Aktifkan update ini di popup?')"
                   title="Aktifkan">
                  <i class="bi bi-broadcast"></i> On
                </a>
              <?php endif; ?>

              <a href="<?= base_url('changelogs/detail_changelog/'.$log->signature) ?>"
                class="hl-btn-act"
                style="background:rgba(59,130,246,.1);color:#3b82f6;border-color:rgba(59,130,246,.25);"
                title="Detail">
                <i class="bi bi-eye"></i>
              </a>

              <a href="<?= base_url('changelogs/changelogs_delete/'.$log->signature) ?>"
                 class="hl-btn-del"
                 onclick="return confirm('Hapus data changelog ini?')"
                 title="Hapus">
                <i class="bi bi-trash3-fill"></i>
              </a>
              
            </div>
          </td>

        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php else: ?>
    <div class="hl-empty">
      <div class="hl-empty-icon"><i class="bi bi-inbox"></i></div>
      <h5>Belum ada data</h5>
      <p>Tambahkan changelog pertama lewat form di atas.</p>
    </div>
    <?php endif; ?>
  </div>

</div>

<script>
/* char counters */
var titleInput  = document.getElementById('titleInput');
var changesArea = document.getElementById('changesArea');
if (titleInput)  titleInput.addEventListener('input',  function(){ document.getElementById('titleCount').textContent  = this.value.length; });
if (changesArea) changesArea.addEventListener('input', function(){ document.getElementById('changesCount').textContent = this.value.length; });

/* checkbox item toggle */
function toggleFnItem(label) {
  var cb = label.querySelector('input[type="checkbox"]');
  cb.checked = !cb.checked;
  label.classList.toggle('is-checked', cb.checked);
  updateFnCount();
}
function updateFnCount() {
  var n = document.querySelectorAll('#fnGrid input[type="checkbox"]:checked').length;
  document.getElementById('fnSelectedCount').textContent = n;
}

/* select all — skip disabled */
var _allSelected = false;
function toggleSelectAll() {
  _allSelected = !_allSelected;
  var cbs = document.querySelectorAll('#fnGrid input[type="checkbox"]:not([disabled])');
  cbs.forEach(function(cb) {
    cb.checked = _allSelected;
    cb.closest('.hl-fn-item').classList.toggle('is-checked', _allSelected);
  });
  var btn = document.getElementById('btnSelectAll');
  btn.innerHTML = _allSelected
    ? '<i class="bi bi-x-square"></i> Batal Semua'
    : '<i class="bi bi-check2-square"></i> Pilih Semua';
  updateFnCount();
}

/* form validation */
document.querySelector('form').addEventListener('submit', function(e) {
  var n = document.querySelectorAll('#fnGrid input[type="checkbox"]:checked').length;
  if (n === 0) {
    e.preventDefault();
    alert('Pilih minimal 1 menu untuk menampilkan update ini.');
  }
});

/* auto hide alerts */
setTimeout(function(){
  document.querySelectorAll('.hl-alert').forEach(function(a){
    a.style.transition = 'opacity .6s'; a.style.opacity = '0';
    setTimeout(function(){ a.style.display = 'none'; }, 650);
  });
}, 4000);
</script>

<script>
$(document).ready(function(){
  $('#dtLogs').DataTable({
    pageLength: 10,
    aLengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'Semua']],
    scrollX: true,
    language: {
      search:      'Cari:',
      lengthMenu:  'Tampilkan _MENU_ data',
      info:        'Menampilkan _START_ - _END_ dari _TOTAL_ data',
      infoEmpty:   'Tidak ada data',
      infoFiltered:'(difilter dari _MAX_ total)',
      paginate: { first:'Pertama', last:'Terakhir', next:'Selanjutnya', previous:'Sebelumnya' },
      zeroRecords: 'Data tidak ditemukan',
      emptyTable:  'Belum ada data',
    },
    columnDefs: [
      { orderable: false, targets: [4, 5, 8] }, // menu chips, foto, aksi tidak bisa sort
    ],
  });
});
</script>