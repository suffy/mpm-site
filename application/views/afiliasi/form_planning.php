<!-- Form Planning -->
<div class="row mt-4">
  <div class="col-lg-6 mx-auto">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-calendar-plus me-2 text-primary"></i>Tambah Aktivitas Baru</h5>
      </div>
      <div class="card-body">
        <form id="add-event-form" method="POST" action="<?php echo site_url($url); ?>">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" id="selected-date-value" name="selected_date" value="<?php echo isset($selected_date) ? $selected_date : date('Y-m-d'); ?>">
                
            <div class="mb-3">
                <label for="event-title" class="form-label fw-semibold">Title *</label>
                <select class="form-select" id="event-title" name="activity_id" required>
                    <option value="">-- Pilih Activity --</option>
                    <?php foreach($get_activity as $activity): ?>
                        <option value="<?= $activity->id; ?>"><?= $activity->nama_activity; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                
            <div class="mb-3">
                <label for="event-description" class="form-label fw-semibold">Keterangan</label>
                <textarea class="form-control" id="event-description" name="keterangan" rows="3" placeholder="Masukkan keterangan"><?php echo isset($form_data['keterangan']) ? $form_data['keterangan'] : ''; ?></textarea>
            </div>
                
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal</label>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <strong id="form-selected-date">
                        <?php 
                        if(isset($selected_date)) {
                            $date = new DateTime($selected_date);
                            echo $date->format('d F Y');
                        } else {
                            echo date('d F Y');
                        }
                        ?>
                    </strong>
                    <small class="text-muted d-block mt-1" id="form-selected-day">
                        <i class="fas fa-clock me-1"></i>
                        <?php 
                        if(isset($selected_date)) {
                            $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $date = new DateTime($selected_date);
                            echo $dayNames[$date->format('w')];
                        } else {
                            $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            echo $dayNames[date('w')];
                        }
                        ?>
                    </small>
                </div>
            </div>
                
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-save me-2"></i>Simpan Data
            </button>
        </form>
      </div>
    </div>
  </div>
</div>
