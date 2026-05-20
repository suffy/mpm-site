<?php
/*
 * VIEW: changelogs/history_update
 * Menampilkan SEMUA changelog aktif — baik sudah maupun belum dibaca
 * Belum dibaca → card punya highlight + tombol "Tandai Sudah Dibaca" di modal
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
$total_unread      = 0;
$total_menu_active = !empty($menu_list) ? count($menu_list) : 0;

if (!empty($history)) {
    foreach ($history as $rows) {
        foreach ($rows as $row) {
            $total_item++;
            if (empty($row->is_read) || $row->is_read == 0) $total_unread++;
        }
    }
}

$active_menu_name = 'Semua Menu';
if (!empty($active_filter) && !empty($menu_list)) {
    foreach ($menu_list as $m) {
        if ($m->id == $active_filter) { $active_menu_name = $m->menu; break; }
    }
}
?>

<style>
.rh-page { max-width: 1350px; margin: 0 auto; padding: 12px 4px 60px; }

.rh-title { font-size: 26px; font-weight: 700; color: var(--bs-body-color); letter-spacing: -.4px; margin: 0 0 4px; }
.rh-title span { color: #e67e22; font-style: italic; }
.rh-sub { font-size: 13px; color: var(--bs-secondary-color); margin: 0; }

/* stats */
.rh-stats { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
.rh-stat {
  flex: 1; min-width: 120px;
  background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color);
  border-radius: 12px; padding: 14px 16px;
  display: flex; align-items: center; gap: 12px;
}
.rh-stat-icon {
  width: 36px; height: 36px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; font-size: 15px;
}
.rh-stat-val { font-size: 22px; font-weight: 700; line-height: 1; color: var(--bs-body-color); }
.rh-stat-lbl { font-size: 11.5px; color: var(--bs-secondary-color); margin-top: 2px; }

/* filter box */
.rh-filter-box {
  background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color);
  border-radius: 12px; padding: 16px 18px; margin-bottom: 24px;
}
.rh-filter-lbl {
  font-size: 10.5px; font-weight: 700; letter-spacing: .07em;
  text-transform: uppercase; color: var(--bs-secondary-color); margin-bottom: 10px;
}
.rh-search-wrap { position: relative; margin-bottom: 14px; }
.rh-search-wrap i {
  position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
  color: var(--bs-secondary-color); font-size: 13px; pointer-events: none;
}
.rh-search {
  width: 100%; padding: 9px 13px 9px 36px; font-size: 13.5px;
  background: var(--bs-body-bg); border: 1.5px solid var(--bs-border-color);
  border-radius: 8px; color: var(--bs-body-color); outline: none;
  transition: border-color .2s; font-family: inherit;
}
.rh-search:focus { border-color: #e67e22; }
.rh-search::placeholder { color: var(--bs-secondary-color); }

/* pills */
.rh-pills { display: flex; flex-wrap: wrap; gap: 7px; }
.rh-pill {
  display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px;
  font-size: 12.5px; font-weight: 500; border-radius: 20px;
  border: 1.5px solid var(--bs-border-color); background: var(--bs-body-bg);
  color: var(--bs-secondary-color); cursor: pointer; text-decoration: none; transition: all .18s;
}
.rh-pill:hover { color: var(--bs-body-color); border-color: var(--bs-secondary-color); text-decoration: none; }
.rh-pill.active { font-weight: 600; color: var(--pill-color); background: var(--pill-bg); border-color: var(--pill-border); }
.pill-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--pill-color, #e67e22); display: none; }
.rh-pill.active .pill-dot { display: block; }
.pill-ct { font-size: 10.5px; font-weight: 700; padding: 1px 6px; border-radius: 10px; background: rgba(0,0,0,.07); color: inherit; }
[data-bs-theme="dark"] .pill-ct { background: rgba(255,255,255,.12); }

/* date group */
.rh-date-grp { margin-bottom: 22px; opacity: 0; transform: translateY(10px); animation: rhFadeUp .35s ease forwards; }
.rh-date-grp:nth-child(1){animation-delay:.04s} .rh-date-grp:nth-child(2){animation-delay:.08s}
.rh-date-grp:nth-child(3){animation-delay:.12s} .rh-date-grp:nth-child(4){animation-delay:.16s}
.rh-date-grp:nth-child(5){animation-delay:.20s}
@keyframes rhFadeUp { to { opacity:1; transform:translateY(0); } }

.rh-date-head { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.rh-date-line { flex:1; height:1px; background: var(--bs-border-color); }
.rh-date-txt { font-size: 10.5px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--bs-secondary-color); white-space: nowrap; }
.rh-date-ct { font-size: 10.5px; font-weight: 600; background: var(--bs-secondary-bg); color: var(--bs-secondary-color); padding: 2px 8px; border-radius: 20px; }

/* ── CARD ── */
.rh-card {
  background: var(--bs-body-bg); border: 1.5px solid var(--bs-border-color);
  border-radius: 12px; padding: 14px 16px; margin-bottom: 9px;
  cursor: pointer; display: flex; gap: 14px; align-items: flex-start;
  position: relative; overflow: hidden;
  transition: border-color .18s, box-shadow .18s, transform .14s;
}
.rh-card::before {
  content:''; position:absolute; left:0; top:0; bottom:0; width:3px;
  background: var(--card-accent, #e67e22);
  transform: scaleY(0); transform-origin: top; transition: transform .22s ease;
}
.rh-card:hover { border-color: var(--bs-secondary-bg); box-shadow: 0 3px 14px rgba(0,0,0,.08); transform: translateX(2px); }
.rh-card:hover::before { transform: scaleY(1); }

/* UNREAD card — lebih menonjol */
.rh-card.unread {
  border-color: rgba(99,102,241,.35);
  background: rgba(99,102,241,.03);
}
[data-bs-theme="dark"] .rh-card.unread { background: rgba(99,102,241,.07); }
.rh-card.unread::before { transform: scaleY(1); }
.rh-card.unread:hover { border-color: rgba(99,102,241,.55); box-shadow: 0 3px 16px rgba(99,102,241,.15); }

/* unread dot */
.rh-unread-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: #6366f1; flex-shrink: 0; margin-top: 6px;
  box-shadow: 0 0 0 3px rgba(99,102,241,.2);
  animation: dotPulse 2s ease-in-out infinite;
}
@keyframes dotPulse {
  0%,100% { box-shadow: 0 0 0 3px rgba(99,102,241,.2); }
  50%      { box-shadow: 0 0 0 5px rgba(99,102,241,.08); }
}

.rh-card-body { flex:1; min-width:0; }
.rh-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 5px; }
.rh-card-title { font-size: 13.5px; font-weight: 600; color: var(--bs-body-color); line-height: 1.4; flex:1; display: -webkit-box; -webkit-line-clamp:2; -webkit-box-orient: vertical; overflow: hidden; }
.rh-card.unread .rh-card-title { font-weight: 700; }

.rh-menu-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; border: 1px solid; white-space: nowrap; flex-shrink: 0; }
.rh-card-preview { font-size: 12px; color: var(--bs-secondary-color); line-height: 1.6; margin-bottom: 9px; display: -webkit-box; -webkit-line-clamp:2; -webkit-box-orient: vertical; overflow: hidden; }

.rh-card-footer { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
.rh-chip { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; padding: 3px 9px; border-radius: 20px; }
.rh-chip-time { background: var(--bs-secondary-bg); color: var(--bs-secondary-color); }
.rh-chip-read { background: rgba(39,174,96,.12); color: #27ae60; font-weight: 500; }
[data-bs-theme="dark"] .rh-chip-read { background: rgba(39,174,96,.2); color: #5dca80; }
.rh-chip-unread { background: rgba(99,102,241,.1); color: #6366f1; font-weight: 600; border: 1px solid rgba(99,102,241,.2); }
[data-bs-theme="dark"] .rh-chip-unread { background: rgba(99,102,241,.18); }

.rh-card-arrow { flex-shrink:0; color: var(--bs-secondary-color); margin-top:4px; transition: transform .18s, color .18s; }
.rh-card:hover .rh-card-arrow { transform: translateX(3px); color: var(--card-accent, #e67e22); }

/* empty */
.rh-empty { text-align: center; padding: 64px 24px; }
.rh-empty-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--bs-secondary-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 26px; color: var(--bs-secondary-color); }
.rh-empty h5 { font-size: 18px; font-weight: 700; color: var(--bs-body-color); margin-bottom: 6px; }
.rh-empty p  { font-size: 13.5px; color: var(--bs-secondary-color); margin: 0; }
#rh-no-results { display: none; text-align: center; padding: 32px 0; font-size: 14px; color: var(--bs-secondary-color); }

/* detail modal */
#rhDetailModal .modal-header { border-bottom: 1px solid var(--bs-border-color); }
#rhDetailModal .modal-footer { border-top: 1px solid var(--bs-border-color); gap: 8px; }
.rh-dmod-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 4px 10px; border-radius: 20px; border: 1px solid; margin-bottom: 10px; }
.rh-dmod-title { font-size: 20px; font-weight: 700; color: var(--bs-body-color); margin: 0; line-height: 1.3; }
.rh-dmod-date { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--bs-secondary-color); margin-top: 8px; }
.rh-changes-lbl { font-size: 10.5px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--bs-secondary-color); margin-bottom: 10px; }
.rh-changes-box {
  background: var(--bs-tertiary-bg); border: 1.5px solid var(--bs-border-color);
  border-radius: 10px; padding: 16px 18px; font-size: 13.5px;
  color: var(--bs-body-color); line-height: 1.75; white-space: pre-line;
  max-height: 260px; overflow-y: auto;
}
.rh-changes-box::-webkit-scrollbar { width:4px; }
.rh-changes-box::-webkit-scrollbar-thumb { background: var(--bs-border-color); border-radius:4px; }

/* foto di modal */
.rh-dmod-foto { margin-top: 14px; border-radius: 10px; overflow: hidden; border: 1px solid var(--bs-border-color); cursor: zoom-in; position: relative; }
.rh-dmod-foto img { width: 100%; max-height: 220px; object-fit: cover; display: block; transition: transform .25s; }
.rh-dmod-foto:hover img { transform: scale(1.02); }
.rh-dmod-foto-hint { position: absolute; bottom: 0; left: 0; right: 0; padding: 5px 10px; font-size: 11px; color: #fff; background: linear-gradient(transparent, rgba(0,0,0,.45)); text-align: center; opacity: 0; transition: opacity .2s; }
.rh-dmod-foto:hover .rh-dmod-foto-hint { opacity: 1; }

/* tombol mark read di modal */
.btn-mark-read {
  display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
  font-size: 13.5px; font-weight: 700; border: none; border-radius: 9px;
  background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
  color: #fff; cursor: pointer; transition: all .2s;
  box-shadow: 0 3px 10px rgba(79,70,229,.3);
}
.btn-mark-read:hover { background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%); transform: translateY(-1px); box-shadow: 0 5px 16px rgba(79,70,229,.4); }
.btn-mark-read:active { transform: translateY(0); }
.btn-mark-read:disabled { opacity: .5; cursor: not-allowed; transform: none; }

/* skeleton */
.rh-skel { background: var(--bs-secondary-bg); border-radius: 5px; animation: rhShimmer 1.3s infinite linear; }
@keyframes rhShimmer { 0%,100% { opacity:.6; } 50% { opacity:1; } }

/* lightbox */
#rhLightbox { display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,.88); backdrop-filter:blur(5px); align-items:center; justify-content:center; cursor:zoom-out; }
#rhLightbox.open { display:flex; animation: lbFade .2s ease both; }
@keyframes lbFade { from{opacity:0;} to{opacity:1;} }
#rhLightboxImg { max-width:90vw; max-height:90vh; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,.6); cursor:default; animation: lbScale .25s cubic-bezier(.34,1.5,.64,1) both; }
@keyframes lbScale { from{transform:scale(.85);opacity:0;} to{transform:scale(1);opacity:1;} }
#rhLightboxClose { position:fixed; top:18px; right:22px; width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.3); color:#fff; font-size:20px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
#rhLightboxClose:hover { background:rgba(231,76,60,.8); border-color:transparent; }
</style>

<div class="rh-page">

  <!-- PAGE HEADER -->
  <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div>
      <h1 class="rh-title">History <span>Management Informasi</span></h1>
      <p class="rh-sub">Semua informasi perubahan — sudah maupun belum dibaca.</p>
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
        <div class="rh-stat-lbl">Total update</div>
      </div>
    </div>
    <div class="rh-stat">
      <div class="rh-stat-icon" style="background:rgba(99,102,241,.12);color:#6366f1;">
        <i class="bi bi-envelope-open"></i>
      </div>
      <div>
        <div class="rh-stat-val"><?= $total_unread ?></div>
        <div class="rh-stat-lbl">Belum dibaca</div>
      </div>
    </div>
    <div class="rh-stat">
      <div class="rh-stat-icon" style="background:rgba(39,174,96,.12);color:#27ae60;">
        <i class="bi bi-check2-all"></i>
      </div>
      <div>
        <div class="rh-stat-val"><?= $total_item - $total_unread ?></div>
        <div class="rh-stat-lbl">Sudah dibaca</div>
      </div>
    </div>
    <!-- <div class="rh-stat">
      <div class="rh-stat-icon" style="background:rgba(41,128,185,.12);color:#2980b9;">
        <i class="bi bi-grid"></i>
      </div>
      <div>
        <div class="rh-stat-val"><?= $total_menu_active ?></div>
        <div class="rh-stat-lbl">Menu aktif</div>
      </div>
    </div> -->
  </div>

  <!-- FILTER BOX -->
  <div class="rh-filter-box">
    <div class="rh-search-wrap">
      <i class="bi bi-search"></i>
      <input type="text" class="rh-search" id="rhSearch" placeholder="Cari judul atau isi perubahan...">
    </div>
    <div class="rh-filter-lbl">Filter menu</div>
    <div class="rh-pills">
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
        // Ambil function id & warna dari id_function pertama
        $fn_ids   = !empty($row->id_function) ? explode('||', $row->id_function) : array();
        $fn_id    = !empty($fn_ids) ? (int)trim($fn_ids[0]) : 0;
        $c        = isset($color_map[$fn_id]) ? $color_map[$fn_id] : $palette[0];

        // Nama menu — ambil semua, tampilkan pertama + jumlah sisanya
        $menu_names   = !empty($row->nama_menu) ? explode('||', $row->nama_menu) : array();
        $menu_display = !empty($menu_names) ? trim($menu_names[0]) : 'Menu';
        $menu_extra   = count($menu_names) > 1 ? ' +'.( count($menu_names) - 1) : '';

        $is_read = !empty($row->is_read) && $row->is_read == 1;
      ?>

      <div class="rh-card <?= $is_read ? '' : 'unread' ?>"
           style="--card-accent:<?= $c['color'] ?>;"
           data-id="<?= $row->id ?>"
           data-read="<?= $is_read ? 1 : 0 ?>"
           data-search="<?= strtolower(htmlspecialchars($row->title . ' ' . $row->changes . ' ' . implode(' ', $menu_names))) ?>"
           onclick="rhOpenDetail(<?= $row->id ?>, <?= $fn_id ?>, this)">

        <?php if (!$is_read): ?>
        <div class="rh-unread-dot"></div>
        <?php endif; ?>

        <div class="rh-card-body">
          <div class="rh-card-top">
            <div class="rh-card-title"><?= htmlspecialchars($row->title) ?></div>
            <span class="rh-menu-badge"
                  style="color:<?= $c['color'] ?>;background:<?= $c['bg'] ?>;border-color:<?= $c['border'] ?>;">
              <?= htmlspecialchars($menu_display . $menu_extra) ?>
            </span>
          </div>
          <div class="rh-card-preview"><?= htmlspecialchars($row->changes) ?></div>
          <div class="rh-card-footer">
            <span class="rh-chip rh-chip-time">
              <i class="bi bi-clock" style="font-size:10px;"></i>
              <?= date('H:i', strtotime($row->created_at)) ?>
            </span>
            <?php if ($is_read): ?>
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
            <?php else: ?>
              <span class="rh-chip rh-chip-unread">
                <i class="bi bi-dot" style="font-size:14px;line-height:1;"></i>
                Belum dibaca
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
    <h5>Belum ada data</h5>
    <p>
      <?php if (empty($active_filter)): ?>
        Belum ada changelog aktif saat ini.
      <?php else: ?>
        Belum ada changelog untuk menu <strong><?= htmlspecialchars($active_menu_name) ?></strong>.
      <?php endif; ?>
    </p>
  </div>
  <?php endif; ?>

</div>

<!-- LIGHTBOX -->
<div id="rhLightbox" onclick="rhCloseLightbox()">
  <img id="rhLightboxImg" src="" alt="" onclick="event.stopPropagation()">
  <button id="rhLightboxClose" onclick="rhCloseLightbox()">&#x2715;</button>
</div>

<!-- DETAIL MODAL -->
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
        <button type="button" onclick="$('#rhDetailModal').modal('hide')"
                style="background:none;border:none;font-size:22px;line-height:1;cursor:pointer;color:var(--bs-secondary-color);padding:4px 8px;"
                aria-label="Close">&times;</button>
      </div>

      <div class="modal-body">
        <div class="rh-changes-lbl">Updated</div>
        <div class="rh-changes-box" id="rhDmodChanges">
          <div class="rh-skel mb-2" style="height:13px;width:75%;"></div>
          <div class="rh-skel mb-2" style="height:13px;width:60%;"></div>
          <div class="rh-skel"      style="height:13px;width:70%;"></div>
        </div>
        <!-- foto — muncul kalau ada -->
        <div id="rhDmodFotoWrap" style="display:none;">
          <div class="rh-dmod-foto" onclick="rhOpenLightbox(document.getElementById('rhDmodFotoImg').src, event)">
            <img id="rhDmodFotoImg" src="" alt="">
            <div class="rh-dmod-foto-hint">🔍 Klik untuk perbesar</div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <!-- Tombol mark read — hanya muncul kalau belum dibaca -->
        <button type="button" class="btn-mark-read" id="btnMarkRead"
                style="display:none;" onclick="rhMarkRead()">
          <i class="bi bi-check2-circle"></i>
          Tandai Sudah Dibaca
        </button>
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
var rhMenuNames  = <?= json_encode($menu_names_js) ?>;
var rhCurrentId  = null;  // id changelog yang sedang dibuka
var rhCurrentCard = null; // referensi DOM card yang diklik

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

/* ── OPEN DETAIL MODAL ── */
function rhOpenDetail(id, fnId, cardEl) {
  rhCurrentId   = id;
  rhCurrentFnId = fnId;
  rhCurrentCard = cardEl;

  var c    = rhColorMap[fnId]  || {color:'#e67e22', bg:'rgba(230,126,34,.12)', border:'rgba(230,126,34,.3)'};
  var name = rhMenuNames[fnId] || ('Menu #' + fnId);

  var badge = document.getElementById('rhDmodBadge');
  badge.textContent      = name;
  badge.style.color      = c.color;
  badge.style.background = c.bg;
  badge.style.borderColor= c.border;

  // Reset modal
  document.getElementById('rhDmodTitle').textContent   = 'Memuat...';
  document.getElementById('rhDmodDate').textContent    = '—';
  document.getElementById('rhDmodChanges').innerHTML   =
    '<div class="rh-skel mb-2" style="height:13px;width:75%;"></div>' +
    '<div class="rh-skel mb-2" style="height:13px;width:60%;"></div>' +
    '<div class="rh-skel"      style="height:13px;width:70%;"></div>';
  document.getElementById('rhDmodFotoWrap').style.display = 'none';
  document.getElementById('btnMarkRead').style.display    = 'none';

  $('#rhDetailModal').modal('show');

  $.ajax({
    url: '<?= base_url("changelogs/get_detail_history") ?>',
    method: 'GET',
    data: { id: id },
    dataType: 'json',
    success: function(res) {
      if (res.status === 'ok') {
        document.getElementById('rhDmodTitle').textContent = res.title;
        document.getElementById('rhDmodDate').textContent  = res.tanggal;
        document.getElementById('rhDmodChanges').innerHTML = res.changes;

        // Foto
        if (res.foto_url) {
          document.getElementById('rhDmodFotoImg').src       = res.foto_url;
          document.getElementById('rhDmodFotoWrap').style.display = 'block';
        }

        // Tombol mark read — tampilkan hanya kalau belum dibaca
        if (res.is_read === 0) {
          document.getElementById('btnMarkRead').style.display = 'inline-flex';
        }
      } else {
        document.getElementById('rhDmodChanges').textContent = 'Gagal memuat: ' + (res.message || 'error');
      }
    },
    error: function(xhr) {
      document.getElementById('rhDmodChanges').textContent = 'Terjadi kesalahan koneksi. (' + xhr.status + ')';
    }
  });
}

/* ── MARK SINGLE READ ── */
function rhMarkRead() {
  if (!rhCurrentId) return;

  var btn = document.getElementById('btnMarkRead');
  btn.disabled = true;
  btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';

  $.ajax({
    url: '<?= base_url("changelogs/mark_single_read") ?>',
    method: 'POST',
    data: { id: rhCurrentId},
    dataType: 'json',
    success: function(res) {
      if (res.status === 'ok') {
        btn.style.display = 'none';

        // Update card di list tanpa reload halaman
        if (rhCurrentCard) {
          rhCurrentCard.classList.remove('unread');
          rhCurrentCard.setAttribute('data-read', '1');

          // Hapus dot
          var dot = rhCurrentCard.querySelector('.rh-unread-dot');
          if (dot) dot.remove();

          // Ganti chip "Belum dibaca" jadi "Sudah dibaca"
          var chipUnread = rhCurrentCard.querySelector('.rh-chip-unread');
          if (chipUnread) {
            chipUnread.className = 'rh-chip rh-chip-read';
            chipUnread.innerHTML = '<i class="bi bi-check2" style="font-size:11px;"></i> Sudah dibaca';
          }
        }

        // Tutup modal setelah sebentar
        setTimeout(function(){ $('#rhDetailModal').modal('hide'); }, 700);
      } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check2-circle"></i> Tandai Sudah Dibaca';
        alert('Gagal menyimpan. Coba lagi.');
      }
    },
    error: function() {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-check2-circle"></i> Tandai Sudah Dibaca';
      alert('Terjadi kesalahan koneksi.');
    }
  });
}

/* ── LIGHTBOX ── */
function rhOpenLightbox(src, e) {
  if (e) e.stopPropagation();
  document.getElementById('rhLightboxImg').src = src;
  document.getElementById('rhLightbox').classList.add('open');
}
function rhCloseLightbox() {
  document.getElementById('rhLightbox').classList.remove('open');
  setTimeout(function(){ document.getElementById('rhLightboxImg').src = ''; }, 200);
}
document.addEventListener('keydown', function(e){
  if (e.key === 'Escape') rhCloseLightbox();
});
</script>
