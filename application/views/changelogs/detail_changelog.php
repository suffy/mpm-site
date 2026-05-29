<?php
/*
 * VIEW: changelogs/detail_changelog
 * Halaman detail changelog — isi konten + daftar user yang sudah baca + tombol reset
 */
?>

<style>
.dc-wrap { max-width: 1350px; margin: 0 auto; padding: 8px 0 60px; }

/* back link */
.dc-back {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 13px; font-weight: 600; color: var(--bs-secondary-color);
  text-decoration: none; margin-bottom: 20px; transition: color .15s;
}
.dc-back:hover { color: var(--bs-body-color); text-decoration: none; }

/* panel */
.dc-panel {
  background: var(--bs-body-bg); border: 1.5px solid var(--bs-border-color);
  border-radius: 14px; padding: 24px 26px; margin-bottom: 20px;
  animation: dcFade .3s ease both;
}
.dc-panel:nth-child(2) { animation-delay: .05s; }
.dc-panel:nth-child(3) { animation-delay: .10s; }
@keyframes dcFade { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

.dc-panel-title {
  font-size: 14px; font-weight: 700; color: var(--bs-body-color);
  display: flex; align-items: center; gap: 8px; margin-bottom: 18px;
  padding-bottom: 14px; border-bottom: 1px solid var(--bs-border-color);
}
.dc-panel-title i { color: #667eea; }

/* header info */
.dc-header { display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-start; }
.dc-header-body { flex: 1; min-width: 0; }
.dc-title { font-size: 20px; font-weight: 700; color: var(--bs-body-color); line-height: 1.3; margin-bottom: 10px; }
.dc-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
.dc-chip {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px; border-radius: 20px; font-size: 12px;
  background: var(--bs-tertiary-bg); color: var(--bs-secondary-color);
  border: 1px solid var(--bs-border-color);
}
.dc-chip i { font-size: 10px; }
.dc-chip.status-on  { background: rgba(39,174,96,.1);  color: #27ae60; border-color: rgba(39,174,96,.3); }
.dc-chip.status-off { background: var(--bs-tertiary-bg); color: var(--bs-secondary-color); }
[data-bs-theme="dark"] .dc-chip.status-on { background: rgba(39,174,96,.2); color: #5dca80; }

/* menu chips */
.dc-menu-chips { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 14px; }
.dc-menu-chip {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600;
  background: rgba(102,126,234,.1); color: #667eea;
  border: 1px solid rgba(102,126,234,.2);
}
[data-bs-theme="dark"] .dc-menu-chip { background: rgba(102,126,234,.2); color: #a5b4fc; }

/* changes box */
.dc-changes-lbl { font-size: 10.5px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--bs-secondary-color); margin-bottom: 8px; }
.dc-changes-box {
  background: var(--bs-tertiary-bg); border: 1.5px solid var(--bs-border-color);
  border-radius: 10px; padding: 16px 18px;
  font-size: 13.5px; color: var(--bs-body-color); line-height: 1.75;
  white-space: pre-line;
}

/* foto */
.dc-foto-wrap { margin-top: 16px; }
.dc-foto-lbl { font-size: 10.5px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--bs-secondary-color); margin-bottom: 8px; }
.dc-foto {
  border-radius: 10px; overflow: hidden; border: 1px solid var(--bs-border-color);
  cursor: zoom-in; position: relative; display: inline-block; max-width: 100%;
}
.dc-foto img { max-width: 100%; max-height: 320px; object-fit: cover; display: block; transition: transform .25s; }
.dc-foto:hover img { transform: scale(1.01); }
.dc-foto-hint {
  position: absolute; bottom: 0; left: 0; right: 0;
  padding: 6px 10px; font-size: 11px; color: #fff;
  background: linear-gradient(transparent, rgba(0,0,0,.45));
  text-align: center; opacity: 0; transition: opacity .2s;
}
.dc-foto:hover .dc-foto-hint { opacity: 1; }

/* readers section */
.dc-reader-header {
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 10px; margin-bottom: 16px;
}
.dc-reader-count {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 13px; color: var(--bs-secondary-color);
}
.dc-reader-count strong { color: var(--bs-body-color); font-size: 20px; }

/* reset button */
.dc-btn-reset {
  display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
  font-size: 13px; font-weight: 600; font-family: inherit;
  background: rgba(231,76,60,.1); color: #e74c3c;
  border: 1.5px solid rgba(231,76,60,.25); border-radius: 9px;
  cursor: pointer; text-decoration: none; transition: all .18s;
}
.dc-btn-reset:hover {
  background: #e74c3c; color: #fff; border-color: #e74c3c;
  text-decoration: none; transform: translateY(-1px);
}

/* search readers */
.dc-reader-search {
  position: relative; margin-bottom: 14px;
}
.dc-reader-search input {
  width: 100%; padding: 9px 36px 9px 36px;
  font-size: 13px; font-family: inherit;
  background: var(--bs-tertiary-bg); color: var(--bs-body-color);
  border: 1.5px solid var(--bs-border-color); border-radius: 9px;
  outline: none; transition: border-color .18s;
  box-sizing: border-box;
}
.dc-reader-search input:focus { border-color: #667eea; }
.dc-reader-search input::placeholder { color: var(--bs-secondary-color); }
.dc-reader-search .dc-search-icon {
  position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
  font-size: 13px; color: var(--bs-secondary-color); pointer-events: none;
}
.dc-reader-search .dc-search-clear {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  font-size: 13px; color: var(--bs-secondary-color);
  cursor: pointer; display: none; padding: 2px 4px;
  transition: color .15s;
}
.dc-reader-search .dc-search-clear:hover { color: var(--bs-body-color); }

/* reader list */
.dc-reader-list { display: flex; flex-direction: column; gap: 8px; }
.dc-reader-item {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 14px; border-radius: 10px;
  background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color);
  transition: background .15s;
}
.dc-reader-item:hover { background: var(--bs-secondary-bg); }

.dc-avatar {
  width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700; color: #fff; text-transform: uppercase;
}
.dc-reader-name { font-size: 13.5px; font-weight: 600; color: var(--bs-body-color); }
.dc-reader-sub  { font-size: 11.5px; color: var(--bs-secondary-color); margin-top: 1px; }
.dc-reader-time {
  margin-left: auto; font-size: 11.5px; color: var(--bs-secondary-color);
  display: flex; align-items: center; gap: 5px; white-space: nowrap;
}

/* highlight match */
.dc-highlight { background: rgba(102,126,234,.2); color: #667eea; border-radius: 3px; padding: 0 2px; }
[data-bs-theme="dark"] .dc-highlight { background: rgba(102,126,234,.35); color: #a5b4fc; }

/* empty / no result */
.dc-empty-readers {
  text-align: center; padding: 36px 20px;
  color: var(--bs-secondary-color); font-size: 13.5px;
}
.dc-empty-readers i { font-size: 32px; display: block; margin-bottom: 10px; opacity: .4; }
.dc-no-result {
  text-align: center; padding: 28px 20px;
  color: var(--bs-secondary-color); font-size: 13.5px; display: none;
}
.dc-no-result i { font-size: 28px; display: block; margin-bottom: 8px; opacity: .4; }

/* alert */
.dc-alert { padding: 12px 16px; border-radius: 10px; font-size: 13.5px; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; border: 1.5px solid; }
.dc-alert-success { background: rgba(39,174,96,.1); border-color: rgba(39,174,96,.3); color: #27ae60; }
.dc-alert-danger  { background: rgba(231,76,60,.1); border-color: rgba(231,76,60,.3); color: #e74c3c; }
[data-bs-theme="dark"] .dc-alert-success { background: rgba(39,174,96,.15); color: #5dca80; }
[data-bs-theme="dark"] .dc-alert-danger  { background: rgba(231,76,60,.15); color: #f1948a; }

/* lightbox */
#dcLightbox { display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,.88); backdrop-filter:blur(5px); align-items:center; justify-content:center; cursor:zoom-out; }
#dcLightbox.open { display:flex; animation: lbFade .2s ease both; }
@keyframes lbFade { from{opacity:0;} to{opacity:1;} }
#dcLightboxImg { max-width:90vw; max-height:90vh; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,.6); cursor:default; animation: lbScale .25s cubic-bezier(.34,1.5,.64,1) both; }
@keyframes lbScale { from{transform:scale(.85);opacity:0;} to{transform:scale(1);opacity:1;} }
#dcLightboxClose { position:fixed; top:18px; right:22px; width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.3); color:#fff; font-size:20px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
#dcLightboxClose:hover { background:rgba(231,76,60,.8); border-color:transparent; }
</style>

<div class="dc-wrap">

  <!-- ALERTS -->
  <?php if ($this->session->flashdata('pesan_success')): ?>
  <div class="dc-alert dc-alert-success">
    <i class="bi bi-check-circle-fill"></i>
    <?= $this->session->flashdata('pesan_success') ?>
  </div>
  <?php elseif ($this->session->flashdata('pesan')): ?>
  <div class="dc-alert dc-alert-danger">
    <i class="bi bi-exclamation-circle-fill"></i>
    <?= $this->session->flashdata('pesan') ?>
  </div>
  <?php endif; ?>

  <!-- BACK -->
  <a href="<?= base_url('changelogs/logs') ?>" class="dc-back">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
  </a>

  <!-- PANEL 1: DETAIL KONTEN -->
  <div class="dc-panel">
    <div class="dc-panel-title">
      <i class="bi bi-file-text-fill"></i>
      Detail Changelog
    </div>

    <div class="dc-header">
      <div class="dc-header-body">

        <div class="dc-title"><?= htmlspecialchars($changelog->title) ?></div>

        <!-- Meta chips -->
        <div class="dc-meta">
          <span class="dc-chip <?= $changelog->status_aktif ? 'status-on' : 'status-off' ?>">
            <i class="bi <?= $changelog->status_aktif ? 'bi-broadcast' : 'bi-pause-circle' ?>"></i>
            <?= $changelog->status_aktif ? 'Aktif di Popup' : 'Nonaktif' ?>
          </span>
          <span class="dc-chip">
            <i class="bi bi-person"></i>
            <?= htmlspecialchars($changelog->username) ?>
          </span>
          <span class="dc-chip">
            <i class="bi bi-calendar3"></i>
            <?= date('d M Y, H:i', strtotime($changelog->created_at)) ?>
          </span>
          <span class="dc-chip">
            <i class="bi bi-people"></i>
            <?= count($readers) ?> pembaca
          </span>
        </div>

        <!-- Menu chips -->
        <?php if (!empty($changelog->menus)): ?>
        <div class="dc-menu-chips">
          <?php foreach (explode('||', $changelog->menus) as $mn): ?>
            <span class="dc-menu-chip">
              <i class="bi bi-grid-3x3-gap-fill" style="font-size:9px;"></i>
              <?= htmlspecialchars(trim($mn)) ?>
            </span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Isi perubahan -->
        <div class="dc-changes-lbl">Isi Keterangan</div>
        <div class="dc-changes-box"><?= htmlspecialchars($changelog->changes) ?></div>

        <!-- Foto -->
        <?php if (!empty($changelog->foto)): ?>
        <?php $fotoUrl = base_url('assets/uploads/changelog/'.date('Y', strtotime($changelog->created_at)).'/'.$changelog->foto); ?>
        <div class="dc-foto-wrap">
          <div class="dc-foto-lbl">Foto Pendukung</div>
          <div class="dc-foto" onclick="dcOpenLightbox('<?= $fotoUrl ?>', event)">
            <img src="<?= $fotoUrl ?>" alt="<?= htmlspecialchars($changelog->title) ?>">
            <div class="dc-foto-hint">🔍 Klik untuk perbesar</div>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- PANEL 2: DAFTAR PEMBACA -->
  <div class="dc-panel">
    <div class="dc-panel-title">
      <i class="bi bi-people-fill"></i>
      Dilihat Oleh
    </div>

    <div class="dc-reader-header">
      <div class="dc-reader-count">
        <strong id="dcReaderCount"><?= count($readers) ?></strong>
        <span id="dcReaderLabel">user sudah membaca changelog ini</span>
      </div>

      <!-- RESET LOG BACA -->
      <?php if (!empty($readers)): ?>
      <a href="<?= base_url('changelogs/reset_changelog_read/'.$changelog->signature) ?>"
         class="dc-btn-reset"
         onclick="return confirm('Reset semua log baca changelog ini?\n\nPopup akan muncul kembali untuk semua user yang sudah membaca.')">
        <i class="bi bi-arrow-counterclockwise"></i>
        Reset Log Baca
      </a>
      <?php endif; ?>
    </div>

    <?php if (!empty($readers)): ?>

    <!-- SEARCH BOX -->
    <div class="dc-reader-search">
      <i class="bi bi-search dc-search-icon"></i>
      <input type="text" id="dcSearchReader" placeholder="Cari nama atau username pembaca..." autocomplete="off">
      <i class="bi bi-x-lg dc-search-clear" id="dcSearchClear" title="Hapus pencarian"></i>
    </div>

    <div class="dc-reader-list" id="dcReaderList">
      <?php foreach ($readers as $r): ?>
      <?php
        $name    = !empty($r->name) ? $r->name : $r->username;
        $initial = strtoupper(substr($name, 0, 1));
      ?>
      <div class="dc-reader-item"
           data-name="<?= strtolower(htmlspecialchars($name)) ?>"
           data-username="<?= strtolower(htmlspecialchars($r->username)) ?>"
           data-name-orig="<?= htmlspecialchars($name) ?>"
           data-username-orig="<?= htmlspecialchars($r->username) ?>">
        <div class="dc-avatar"><?= $initial ?></div>
        <div>
          <div class="dc-reader-name"><?= htmlspecialchars($name) ?></div>
          <div class="dc-reader-sub">@<?= htmlspecialchars($r->username) ?></div>
        </div>
        <div class="dc-reader-time">
          <i class="bi bi-eye" style="font-size:11px;"></i>
          <?= date('d M Y, H:i', strtotime($r->dibaca_at)) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="dc-no-result" id="dcNoResult">
      <i class="bi bi-person-x"></i>
      Tidak ada pembaca yang cocok dengan "<span id="dcNoResultQuery"></span>".
    </div>

    <?php else: ?>
    <div class="dc-empty-readers">
      <i class="bi bi-eye-slash"></i>
      Belum ada user yang membaca changelog ini.
    </div>
    <?php endif; ?>

  </div>

</div>

<!-- LIGHTBOX -->
<div id="dcLightbox" onclick="dcCloseLightbox()">
  <img id="dcLightboxImg" src="" alt="" onclick="event.stopPropagation()">
  <button id="dcLightboxClose" onclick="dcCloseLightbox()">&#x2715;</button>
</div>

<script>
/* ─── LIGHTBOX ─── */
function dcOpenLightbox(src, e) {
  if (e) e.stopPropagation();
  document.getElementById('dcLightboxImg').src = src;
  document.getElementById('dcLightbox').classList.add('open');
}
function dcCloseLightbox() {
  document.getElementById('dcLightbox').classList.remove('open');
  setTimeout(function(){ document.getElementById('dcLightboxImg').src = ''; }, 200);
}
document.addEventListener('keydown', function(e){
  if (e.key === 'Escape') dcCloseLightbox();
});

/* ─── READER SEARCH ─── */
(function () {
  var searchInput = document.getElementById('dcSearchReader');
  if (!searchInput) return;

  var clearBtn    = document.getElementById('dcSearchClear');
  var list        = document.getElementById('dcReaderList');
  var noResult    = document.getElementById('dcNoResult');
  var noResultQ   = document.getElementById('dcNoResultQuery');
  var countEl     = document.getElementById('dcReaderCount');
  var labelEl     = document.getElementById('dcReaderLabel');
  var items       = list ? Array.from(list.querySelectorAll('.dc-reader-item')) : [];
  var total       = items.length;

  function escapeRegex(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  function highlight(text, q) {
    if (!q) return text;
    var re = new RegExp('(' + escapeRegex(q) + ')', 'gi');
    return text.replace(re, '<mark class="dc-highlight">$1</mark>');
  }

  function doSearch(q) {
    var shown = 0;

    items.forEach(function (item) {
      var nameKey = item.dataset.name;
      var userKey = item.dataset.username;
      var match   = !q || nameKey.includes(q) || userKey.includes(q);

      item.style.display = match ? '' : 'none';

      if (match) {
        shown++;
        /* update highlight */
        var nameEl = item.querySelector('.dc-reader-name');
        var subEl  = item.querySelector('.dc-reader-sub');
        nameEl.innerHTML = highlight(item.dataset.nameOrig, q);
        subEl.innerHTML  = '@' + highlight(item.dataset.usernameOrig, q);
      } else {
        /* restore original text */
        var nameEl = item.querySelector('.dc-reader-name');
        var subEl  = item.querySelector('.dc-reader-sub');
        nameEl.textContent = item.dataset.nameOrig;
        subEl.textContent  = '@' + item.dataset.usernameOrig;
      }
    });

    /* no result state */
    var isEmpty = shown === 0 && q !== '';
    noResult.style.display = isEmpty ? 'block' : 'none';
    if (noResultQ) noResultQ.textContent = q;

    /* counter */
    if (q) {
      countEl.textContent = shown + ' / ' + total;
      labelEl.textContent = 'hasil ditemukan';
    } else {
      countEl.textContent = total;
      labelEl.textContent = 'user sudah membaca changelog ini';
    }

    /* clear button visibility */
    clearBtn.style.display = q ? 'block' : 'none';
  }

  /* store original text into data attrs for highlight restore */
  items.forEach(function (item) {
    item.dataset.nameOrig     = item.querySelector('.dc-reader-name').textContent.trim();
    item.dataset.usernameOrig = item.querySelector('.dc-reader-sub').textContent.replace(/^@/, '').trim();
  });

  searchInput.addEventListener('input', function () {
    doSearch(this.value.trim().toLowerCase());
  });

  clearBtn.addEventListener('click', function () {
    searchInput.value = '';
    searchInput.focus();
    doSearch('');
  });
})();

/* ─── AUTO HIDE ALERTS ─── */
setTimeout(function(){
  document.querySelectorAll('.dc-alert').forEach(function(a){
    a.style.transition = 'opacity .6s';
    a.style.opacity    = '0';
    setTimeout(function(){ a.style.display = 'none'; }, 650);
  });
}, 4000);
</script>