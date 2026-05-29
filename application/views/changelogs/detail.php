</div>
<div class="hl-wrap">

  <div class="hl-panel">
    <div class="hl-panel-title">
      <i class="bi bi-file-text"></i> Detail Changelog
    </div>

    <h4><?= htmlspecialchars($log->title) ?></h4>
    <p style="color:var(--bs-secondary-color);font-size:13px;">
      Dibuat: <?= date('d M Y H:i', strtotime($log->created_at)) ?>
    </p>

    <div style="margin-top:15px;">
      <?= nl2br(htmlspecialchars($log->changes)) ?>
    </div>

    <?php if ($log->foto): ?>
      <div style="margin-top:15px;">
        <img src="<?= base_url('assets/uploads/changelog/'.date('Y', strtotime($log->created_at)).'/'.$log->foto) ?>"
             style="max-width:300px;border-radius:8px;">
      </div>
    <?php endif; ?>
  </div>

  <!-- Readers -->
  <div class="hl-panel">
    <div class="hl-panel-title">
      <i class="bi bi-people"></i> User yang sudah membaca
    </div>

    <?php if (!empty($readers)): ?>
      <table class="hl-table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Waktu Baca</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($readers as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r->username) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($r->read_at)) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="color:var(--bs-secondary-color);">Belum ada yang membaca.</p>
    <?php endif; ?>

    <!-- RESET BUTTON -->
    <div style="margin-top:15px;">
      <a href="<?= base_url('changelogs/reset_read/'.$log->id) ?>"
         class="hl-btn-del"
         onclick="return confirm('Reset semua log baca? Popup akan muncul kembali ke semua user.')">
        <i class="bi bi-arrow-counterclockwise"></i> Reset Read Log
      </a>
    </div>

  </div>

</div>