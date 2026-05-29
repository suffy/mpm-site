<?php
/*
 * VIEW: changelogs/history_update
 * Di-render via $this->render(...) — sudah ada dalam wrapper main template
 * Bootstrap 5 + dark/light theme otomatis ikut data-bs-theme
 */

$palette = array(
    array('color'=>'#e67e22','bg'=>'rgba(230,126,34,.12)','border'=>'rgba(230,126,34,.3)'),
    array('color'=>'#2980b9','bg'=>'rgba(41,128,185,.12)','border'=>'rgba(41,128,185,.3)'),
    array('color'=>'#8e44ad','bg'=>'rgba(142,68,173,.12)','border'=>'rgba(142,68,173,.3)'),
    array('color'=>'#27ae60','bg'=>'rgba(39,174,96,.12)','border'=>'rgba(39,174,96,.3)'),
    array('color'=>'#f39c12','bg'=>'rgba(243,156,18,.12)','border'=>'rgba(243,156,18,.3)'),
    array('color'=>'#c0392b','bg'=>'rgba(192,57,43,.12)','border'=>'rgba(192,57,43,.3)'),
    array('color'=>'#16a085','bg'=>'rgba(22,160,133,.12)','border'=>'rgba(22,160,133,.3)'),
    array('color'=>'#2c3e50','bg'=>'rgba(44,62,80,.12)',  'border'=>'rgba(44,62,80,.3)'),
);

$color_map = array();
if (!empty($menu_list)) {
    foreach ($menu_list as $i => $m) {
        $color_map[$m->id] = $palette[$i % count($palette)];
    }
}

$total_item        = 0;
$total_menu_active = !empty($menu_list) ? count($menu_list) : 0;
// $total_hari        = !empty($history)   ? count($history)   : 0;
if (!empty($history)) {
    foreach ($history as $rows) { $total_item += count($rows); }
}

$active_menu_name = 'Semua Menu';
if (!empty($active_filter) && !empty($menu_list)) {
    foreach ($menu_list as $m) {
        if ($m->id == $active_filter) { $active_menu_name = $m->menu; break; }
    }
}
?>

<style>
/* ── Scoped hanya untuk halaman ini, semua pakai BS5 variables ── */

.rh-page { max-width: 1350px; margin: 0 auto; padding: 12px 4px 60px; }

/* header */
.rh-title {
  font-size: 26px;
  font-weight: 700;
  color: var(--bs-body-color);
  letter-spacing: -.4px;
  margin: 0 0 4px;
}
.rh-title span { color: #e67e22; font-style: italic; }
.rh-sub { font-size: 13px; color: var(--bs-secondary-color); margin: 0; }

/* stats */
.rh-stats { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
.rh-stat {
  flex: 1; min-width: 120px;
  background: var(--bs-tertiary-bg);
  border: 1px solid var(--bs-border-color);
  border-radius: 12px;
  padding: 14px 16px;
  display: flex; align-items: center; gap: 12px;
}
.rh-stat-icon {
  width: 36px; height: 36px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; font-size: 15px;
}
.rh-stat-val {
  font-size: 22px; font-weight: 700; line-height: 1;
  color: var(--bs-body-color);
}
.rh-stat-lbl { font-size: 11.5px; color: var(--bs-secondary-color); margin-top: 2px; }

/* filter box */
.rh-filter-box {
  background: var(--bs-tertiary-bg);
  border: 1px solid var(--bs-border-color);
  border-radius: 12px;
  padding: 16px 18px;
  margin-bottom: 24px;
}
.rh-filter-lbl {
  font-size: 10.5px; font-weight: 700;
  letter-spacing: .07em; text-transform: uppercase;
  color: var(--bs-secondary-color); margin-bottom: 10px;
}

/* search */
.rh-search-wrap { position: relative; margin-bottom: 14px; }
.rh-search-wrap i {
  position: absolute; left: 12px; top: 50%;
  transform: translateY(-50%);
  color: var(--bs-secondary-color); font-size: 13px;
  pointer-events: none;
}
.rh-search {
  width: 100%;
  padding: 9px 13px 9px 36px;
  font-size: 13.5px;
  background: var(--bs-body-bg);
  border: 1.5px solid var(--bs-border-color);
  border-radius: 8px;
  color: var(--bs-body-color);
  outline: none;
  transition: border-color .2s;
  font-family: inherit;
}
.rh-search:focus { border-color: #e67e22; }
.rh-search::placeholder { color: var(--bs-secondary-color); }

/* menu pills */
.rh-pills { display: flex; flex-wrap: wrap; gap: 7px; }
.rh-pill {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 5px 12px;
  font-size: 12.5px; font-weight: 500;
  border-radius: 20px;
  border: 1.5px solid var(--bs-border-color);
  background: var(--bs-body-bg);
  color: var(--bs-secondary-color);
  cursor: pointer; text-decoration: none;
  transition: all .18s;
}
.rh-pill:hover {
  color: var(--bs-body-color);
  border-color: var(--bs-secondary-color);
  text-decoration: none;
}
.rh-pill.active {
  font-weight: 600;
  color: var(--pill-color);
  background: var(--pill-bg);
  border-color: var(--pill-border);
}
.pill-dot {
  width: 7px; height: 7px; border-radius: 50%;
  background: var(--pill-color, #e67e22);
  display: none;
}
.rh-pill.active .pill-dot { display: block; }
.pill-ct {
  font-size: 10.5px; font-weight: 700;
  padding: 1px 6px; border-radius: 10px;
  background: rgba(0,0,0,.07);
  color: inherit;
}
[data-bs-theme="dark"] .pill-ct { background: rgba(255,255,255,.12); }

/* date group */
.rh-date-grp { margin-bottom: 22px; }
.rh-date-head {
  display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
}
.rh-date-line { flex:1; height:1px; background: var(--bs-border-color); }
.rh-date-txt {
  font-size: 10.5px; font-weight: 700;
  letter-spacing: .08em; text-transform: uppercase;
  color: var(--bs-secondary-color); white-space: nowrap;
}
.rh-date-ct {
  font-size: 10.5px; font-weight: 600;
  background: var(--bs-secondary-bg);
  color: var(--bs-secondary-color);
  padding: 2px 8px; border-radius: 20px;
}

/* history card */
.rh-card {
  background: var(--bs-body-bg);
  border: 1.5px solid var(--bs-border-color);
  border-radius: 12px;
  padding: 14px 16px;
  margin-bottom: 9px;
  cursor: pointer;
  display: flex; gap: 14px; align-items: flex-start;
  position: relative; overflow: hidden;
  transition: border-color .18s, box-shadow .18s, transform .14s, background .18s;
}
.rh-card::before {
  content:''; position:absolute; left:0; top:0; bottom:0;
  width:3px; background: var(--card-accent, #e67e22);
  transform: scaleY(0); transform-origin: top;
  transition: transform .22s ease;
}
.rh-card:hover {
  border-color: var(--bs-secondary-bg);
  box-shadow: 0 3px 14px rgba(0,0,0,.08);
  transform: translateX(2px);
}
.rh-card:hover::before { transform: scaleY(1); }

/* card parts */
.rh-card-body { flex:1; min-width:0; }
.rh-card-top {
  display: flex; justify-content: space-between;
  align-items: flex-start; gap: 10px; margin-bottom: 5px;
}
.rh-card-title {
  font-size: 13.5px; font-weight: 600;
  color: var(--bs-body-color); line-height: 1.4; flex:1;
  display: -webkit-box; -webkit-line-clamp:2;
  -webkit-box-orient: vertical; overflow: hidden;
}
.rh-menu-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 600;
  padding: 3px 9px; border-radius: 20px; border: 1px solid;
  white-space: nowrap; flex-shrink: 0;
}
.rh-card-preview {
  font-size: 12px; color: var(--bs-secondary-color);
  line-height: 1.6; margin-bottom: 9px;
  display: -webkit-box; -webkit-line-clamp:2;
  -webkit-box-orient: vertical; overflow: hidden;
}
.rh-card-footer { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
.rh-chip {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; padding: 3px 9px; border-radius: 20px;
}
.rh-chip-time {
  background: var(--bs-secondary-bg);
  color: var(--bs-secondary-color);
}
.rh-chip-read {
  background: rgba(39,174,96,.12);
  color: #27ae60; font-weight: 500;
}
[data-bs-theme="dark"] .rh-chip-read {
  background: rgba(39,174,96,.2);
  color: #5dca80;
}
.rh-card-arrow {
  flex-shrink:0; color: var(--bs-secondary-color);
  margin-top:4px; transition: transform .18s, color .18s;
}
.rh-card:hover .rh-card-arrow {
  transform: translateX(3px);
  color: var(--card-accent, #e67e22);
}

/* empty */
.rh-empty {
  text-align: center; padding: 64px 24px;
}
.rh-empty-icon {
  width: 64px; height: 64px; border-radius: 16px;
  background: var(--bs-secondary-bg);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px; font-size: 26px;
  color: var(--bs-secondary-color);
}
.rh-empty h5 { font-size: 18px; font-weight: 700; color: var(--bs-body-color); margin-bottom: 6px; }
.rh-empty p  { font-size: 13.5px; color: var(--bs-secondary-color); margin: 0; }

#rh-no-results {
  display: none; text-align: center;
  padding: 32px 0; font-size: 14px;
  color: var(--bs-secondary-color);
}

/* detail modal uses BS5 natively */
#rhDetailModal .modal-header { border-bottom: 1px solid var(--bs-border-color); }
#rhDetailModal .modal-footer { border-top:   1px solid var(--bs-border-color); }
.rh-dmod-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 700;
  letter-spacing: .06em; text-transform: uppercase;
  padding: 4px 10px; border-radius: 20px; border: 1px solid;
  margin-bottom: 10px;
}
.rh-dmod-title {
  font-size: 20px; font-weight: 700;
  color: var(--bs-body-color); margin: 0; line-height: 1.3;
}
.rh-dmod-date {
  display: flex; align-items: center; gap: 6px;
  font-size: 13px; color: var(--bs-secondary-color);
  margin-top: 8px;
}
.rh-changes-lbl {
  font-size: 10.5px; font-weight: 700;
  letter-spacing: .08em; text-transform: uppercase;
  color: var(--bs-secondary-color); margin-bottom: 10px;
}
.rh-changes-box {
  background: var(--bs-tertiary-bg);
  border: 1.5px solid var(--bs-border-color);
  border-radius: 10px; padding: 16px 18px;
  font-size: 13.5px; color: var(--bs-body-color);
  line-height: 1.75; white-space: pre-line;
  max-height: 280px; overflow-y: auto;
}
.rh-changes-box::-webkit-scrollbar { width:4px; }
.rh-changes-box::-webkit-scrollbar-thumb {
  background: var(--bs-border-color); border-radius:4px;
}

/* skeleton */
.rh-skel {
  background: var(--bs-secondary-bg);
  border-radius: 5px;
  animation: rhShimmer 1.3s infinite linear;
  background-size: 200% 100%;
}
@keyframes rhShimmer {
  0%   { opacity: .6; }
  50%  { opacity: 1;  }
  100% { opacity: .6; }
}

/* fade in animation for groups */
.rh-date-grp {
  opacity: 0; transform: translateY(10px);
  animation: rhFadeUp .35s ease forwards;
}
.rh-date-grp:nth-child(1){animation-delay:.04s}
.rh-date-grp:nth-child(2){animation-delay:.08s}
.rh-date-grp:nth-child(3){animation-delay:.12s}
.rh-date-grp:nth-child(4){animation-delay:.16s}
.rh-date-grp:nth-child(5){animation-delay:.20s}
@keyframes rhFadeUp {
  to { opacity:1; transform:translateY(0); }
}
</style>

<div class="rh-page">

  <!-- PAGE HEADER -->
  <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div>
      <h1 class="rh-title">History <span>Management Informasi</span></h1>
      <p class="rh-sub">Informasi perubahan yang sudah kamu baca di seluruh menu.</p>
    </div>
  </div>

  <!-- STATS -->
  <div class="rh-stats">
    <div class="rh-stat">
      <div class="rh-stat-icon" style="background:rgba(230,126,34,.12);color:#e67e22;">
        <i class="bi bi-card-list"></i>
      </div>
      <div>
        <div class="rh-stat-val"><?= $total_item ?></div>
        <div class="rh-stat-lbl">Total dibaca</div>
      </div>
    </div>
    <div class="rh-stat">
      <div class="rh-stat-icon" style="background:rgba(41,128,185,.12);color:#2980b9;">
        <i class="bi bi-grid"></i>
      </div>
      <div>
        <div class="rh-stat-val"><?= $total_menu_active ?></div>
        <div class="rh-stat-lbl">Menu aktif</div>
      </div>
    </div>
  </div>

  <!-- FILTER BOX -->
  <div class="rh-filter-box">
    <div class="rh-search-wrap">
      <i class="bi bi-search"></i>
      <input type="text" class="rh-search" id="rhSearch" placeholder="Cari judul atau isi perubahan...">
    </div>
    <div class="rh-filter-lbl">Filter menu</div>
    <div class="rh-pills">

      <!-- All -->
      <a href="<?= base_url('changelogs/history_update') ?>"
         class="rh-pill <?= empty($active_filter) ? 'active' : '' ?>"
         style="--pill-color:var(--bs-body-color);--pill-bg:var(--bs-secondary-bg);--pill-border:var(--bs-border-color);">
        <span class="pill-dot" style="background:var(--bs-body-color);"></span>
        Semua
        <span class="pill-ct"><?= $total_item ?></span>
      </a>

      <?php if (!empty($menu_list)): ?>
        <?php foreach ($menu_list as $i => $m):
          $c = $palette[$i % count($palette)];
          $is_active = (!empty($active_filter) && $active_filter == $m->id);
        ?>
        <a href="<?= base_url('changelogs/history_update?menu='.$m->id) ?>"
           class="rh-pill <?= $is_active ? 'active' : '' ?>"
           style="--pill-color:<?= $c['color'] ?>;--pill-bg:<?= $c['bg'] ?>;--pill-border:<?= $c['border'] ?>;">
          <span class="pill-dot"></span>
          <?= htmlspecialchars($m->menu) ?>
          <span class="pill-ct"><?= $m->total ?></span>
        </a>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </div>

  <!-- LIST -->
  <?php if (!empty($history)): ?>

  <div id="rhList">
    <?php foreach ($history as $tanggal => $rows): ?>

    <div class="rh-date-grp" data-group>
      <div class="rh-date-head">
        <div class="rh-date-line"></div>
        <span class="rh-date-txt"><?= date('l, d M Y', strtotime($tanggal)) ?></span>
        <div class="rh-date-line"></div>
        <span class="rh-date-ct"><?= count($rows) ?></span>
      </div>

      <?php foreach ($rows as $row):
        $fn_id     = $row->id_function;
        $c         = isset($color_map[$fn_id]) ? $color_map[$fn_id] : $palette[0];
        $menu_name = !empty($row->nama_menu) ? $row->nama_menu : 'Menu #'.$fn_id;
      ?>

      <div class="rh-card"
           style="--card-accent:<?= $c['color'] ?>;"
           data-id="<?= $row->id ?>"
           data-fn="<?= $fn_id ?>"
           data-search="<?= strtolower(htmlspecialchars($row->title . ' ' . $row->changes . ' ' . $menu_name)) ?>"
           onclick="rhOpenDetail(<?= $row->id ?>, <?= $fn_id ?>)">

        <div class="rh-card-body">
          <div class="rh-card-top">
            <div class="rh-card-title"><?= htmlspecialchars($row->title) ?></div>
            <span class="rh-menu-badge"
                  style="color:<?= $c['color'] ?>;background:<?= $c['bg'] ?>;border-color:<?= $c['border'] ?>;">
              <?= htmlspecialchars($menu_name) ?>
            </span>
          </div>
          <div class="rh-card-preview"><?= htmlspecialchars($row->changes) ?></div>
          <div class="rh-card-footer">
            <span class="rh-chip rh-chip-time">
              <i class="bi bi-clock" style="font-size:10px;"></i>
              <?= date('H:i', strtotime($row->created_at)) ?>
            </span>
            <span class="rh-chip rh-chip-read">
              <i class="bi bi-check2" style="font-size:11px;"></i>
              Sudah dibaca
            </span>
            <?php if (!empty($row->dibaca_at)): ?>
            <span class="rh-chip rh-chip-time">
              <i class="bi bi-eye" style="font-size:10px;"></i>
              <?= date('d M, H:i', strtotime($row->dibaca_at)) ?>
            </span>
            <?php endif; ?>
          </div>
        </div>

        <div class="rh-card-arrow">
          <i class="bi bi-chevron-right"></i>
        </div>

      </div>

      <?php endforeach; ?>
    </div>

    <?php endforeach; ?>

    <div id="rh-no-results">Tidak ada hasil yang cocok.</div>
  </div>

  <?php else: ?>

  <div class="rh-empty">
    <div class="rh-empty-icon"><i class="bi bi-inbox"></i></div>
    <h5>Belum ada riwayat</h5>
    <p>
      <?php if (empty($active_filter)): ?>
        Kamu belum membaca informasi update apapun.
      <?php else: ?>
        Belum ada update yang dibaca untuk menu <strong><?= htmlspecialchars($active_menu_name) ?></strong>.
      <?php endif; ?>
    </p>
  </div>

  <?php endif; ?>

</div><!-- /rh-page -->


<!-- DETAIL MODAL — jQuery modal() agar tidak konflik BS4/BS5 -->
<div class="modal fade" id="rhDetailModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" style="max-width:580px;" role="document">
    <div class="modal-content">

      <div class="modal-header pb-2">
        <div style="flex:1;">
          <div class="rh-dmod-badge" id="rhDmodBadge">—</div>
          <h5 class="rh-dmod-title" id="rhDmodTitle">Memuat...</h5>
          <div class="rh-dmod-date">
            <i class="bi bi-calendar3" style="font-size:12px;"></i>
            <span id="rhDmodDate">—</span>
          </div>
        </div>
        <!-- pakai onclick jQuery, bukan data-bs-dismiss -->
        <button type="button" onclick="$('#rhDetailModal').modal('hide')"
                style="background:none;border:none;font-size:20px;line-height:1;cursor:pointer;color:var(--bs-secondary-color);padding:4px 8px;"
                aria-label="Close">&times;</button>
      </div>

      <div class="modal-body">
        <div class="rh-changes-lbl">Isi Perubahan</div>
        <div class="rh-changes-box" id="rhDmodChanges">
          <div class="rh-skel mb-2" style="height:13px;width:75%;"></div>
          <div class="rh-skel mb-2" style="height:13px;width:60%;"></div>
          <div class="rh-skel"      style="height:13px;width:70%;"></div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm"
                onclick="$('#rhDetailModal').modal('hide')">Tutup</button>
      </div>

    </div>
  </div>
</div>


<script>
var rhColorMap  = <?= json_encode($color_map) ?>;
<?php
$menu_names_js = array();
if (!empty($menu_list)) {
    foreach ($menu_list as $m) { $menu_names_js[$m->id] = $m->menu; }
}
?>
var rhMenuNames = <?= json_encode($menu_names_js) ?>;

/* ── SEARCH ── */
document.getElementById('rhSearch').addEventListener('input', function(){
  var q   = this.value.toLowerCase().trim();
  var any = false;

  document.querySelectorAll('.rh-card').forEach(function(card){
    var match = !q || (card.dataset.search || '').indexOf(q) !== -1;
    card.style.display = match ? '' : 'none';
    if (match) any = true;
  });

  document.querySelectorAll('[data-group]').forEach(function(grp){
    var vis = grp.querySelectorAll('.rh-card:not([style*="none"])').length > 0;
    grp.style.display = vis ? '' : 'none';
  });

  document.getElementById('rh-no-results').style.display = any ? 'none' : 'block';
});

/* ── DETAIL MODAL ── */
function rhOpenDetail(id, fnId) {
  var c    = rhColorMap[fnId]  || {color:'#e67e22', bg:'rgba(230,126,34,.12)', border:'rgba(230,126,34,.3)'};
  var name = rhMenuNames[fnId] || ('Menu #' + fnId);

  var badge = document.getElementById('rhDmodBadge');
  badge.textContent = name;
  badge.style.color       = c.color;
  badge.style.background  = c.bg;
  badge.style.borderColor = c.border;

  document.getElementById('rhDmodTitle').textContent = 'Memuat...';
  document.getElementById('rhDmodDate').textContent  = '—';
  document.getElementById('rhDmodChanges').innerHTML =
    '<div class="rh-skel mb-2" style="height:13px;width:75%;"></div>' +
    '<div class="rh-skel mb-2" style="height:13px;width:60%;"></div>' +
    '<div class="rh-skel"      style="height:13px;width:70%;"></div>';

  /* Buka modal pakai jQuery — aman di lingkungan BS4+BS5 mix */
  $('#rhDetailModal').modal('show');

  $.ajax({
    url: '<?= base_url("changelogs/get_detail_history") ?>',
    method: 'GET',
    data: { id: id },
    dataType: 'json',
    success: function(res) {
      if (res.status === 'ok') {
        document.getElementById('rhDmodTitle').textContent   = res.title;
        document.getElementById('rhDmodDate').textContent    = res.tanggal;
        document.getElementById('rhDmodChanges').innerHTML   = res.changes;
      } else {
        document.getElementById('rhDmodChanges').textContent = 'Gagal memuat: ' + (res.message || 'error');
      }
    },
    error: function(xhr) {
      document.getElementById('rhDmodChanges').textContent = 'Terjadi kesalahan koneksi. (' + xhr.status + ')';
    }
  });
}
</script>