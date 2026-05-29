<!-- Alert Reminder -->
<?php if(!empty($reminder_bulanan) || !empty($reminder_harian) || !empty($reminder_not_harian_bulanan)): ?>
<div class="row mt-4">
  <div class="col-lg-12">
    <div class="card shadow-sm border-warning">
      <div class="card-header var(--bs-body-bg) bg-opacity-10">
        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Reminder & Peringatan</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
            <?php if(!empty($reminder_bulanan)): ?>
                <div class="col-md-4">
                    <div class="alert alert-warning mb-0 h-100">
                        <strong><i class="fas fa-calendar-alt me-2"></i> Reminder Bulanan!</strong><br>
                        Anda memiliki <b><?= count($reminder_bulanan); ?></b> aktivitas yang belum direncanakan:
                        <ul class="mb-0 mt-2">
                            <?php foreach($reminder_bulanan as $r): ?>
                                <li><?= $r['nama_activity']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($reminder_harian)): ?>
                <div class="col-md-4">
                    <div class="alert alert-danger mb-0 h-100">
                        <strong><i class="fas fa-clock me-2"></i> Reminder Harian!</strong><br>
                        Anda memiliki <b><?= count($reminder_harian); ?></b> aktivitas yang harus direncanakan hari ini:
                        <ul class="mb-0 mt-2">
                            <?php foreach($reminder_harian as $r): ?>
                                <li><?= $r['nama_activity']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($reminder_not_harian_bulanan)): ?>
                <div class="col-md-4">
                    <div class="alert alert-info mb-0 h-100">
                        <strong><i class="fas fa-exclamation-circle me-2"></i> Reminder Lainnya!</strong><br>
                        Anda memiliki <b><?= count($reminder_not_harian_bulanan); ?></b> aktivitas yang belum direncanakan:
                        <ul class="mb-0 mt-2">
                            <?php foreach($reminder_not_harian_bulanan as $r): ?>
                                <li><?= $r['nama_activity']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

