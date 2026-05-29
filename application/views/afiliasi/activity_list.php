<!-- Daftar Aktivitas (Horizontal Scroll/Carousel) -->
<div class="row mt-4">
  <div class="col-lg-12">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list-alt me-2 text-success"></i>Aktivitas untuk Tanggal Terpilih</h5>
        <span class="badge bg-primary rounded-pill"><?= count($plan); ?> Aktivitas</span>
      </div>
      <div class="card-body">
        <?php if(empty($plan)): ?>
          <div class="text-center py-5 text-muted">
            <i class="fas fa-calendar-times fa-3x mb-3"></i>
            <p class="mb-0">Belum ada aktivitas untuk tanggal ini.</p>
          </div>
        <?php else: ?>
          <div class="position-relative">
            <button class="btn btn-outline-secondary rounded-circle position-absolute start-0 top-50 translate-middle-y" 
                    id="scrollLeftBtn" style="z-index: 10; display: none;">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div id="horizontalScrollContainer" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap; padding-bottom: 15px; scroll-behavior: smooth;">
                <div style="display: inline-flex; gap: 20px; padding: 0 10px;">
                    <?php foreach($plan as $activity): ?>
                        <div class="card border" style="min-width: 350px; max-width: 350px; white-space: normal; display: inline-block;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                                        <?php echo htmlspecialchars($activity['title']); ?>
                                    </h6>
                                    <form method="POST" action="<?php echo site_url('afiliasi/delete_monthly_plan'); ?>" 
                                        class="d-inline" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')">
                                        <input type="hidden" name="activity_id" value="<?php echo $activity['id']; ?>">
                                        <input type="hidden" name="selected_date" value="<?php echo $selected_date; ?>">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <?php if(!empty($activity['keterangan'])): ?>
                                    <div class="mb-2 text-secondary" style="font-size: 0.9rem;">
                                        <?php echo nl2br(htmlspecialchars($activity['keterangan'])); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <hr class="my-2">
                                
                                <div class="text-muted small">
                                    <div><i class="fas fa-user me-1"></i> <?php echo $activity['nama']; ?></div>
                                    <div><i class="fas fa-building me-1"></i> <?php echo $activity['nama_divisi']; ?></div>
                                    <div><i class="fas fa-briefcase me-1"></i> <?php echo $activity['nama_jabatan']; ?></div>
                                    <div><i class="fas fa-clock me-1"></i> <?php echo date('d M Y H:i', strtotime($activity['created_at'])); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <button class="btn btn-outline-secondary rounded-circle position-absolute end-0 top-50 translate-middle-y" id="scrollRightBtn" style="z-index: 10;">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
