<!-- Kalender -->
<div class="row mt-4">
  <div class="col-lg-12">
    <div class="card shadow-sm">
      <div class="card-header var(--bs-body-bg) py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">       
          <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" id="prev-month">
              <i class="fas fa-chevron-left me-1"></i> Bulan Sebelumnya
            </button>
            <button class="btn btn-outline-primary btn-sm" id="today-btn">
              <i class="fas fa-calendar-day me-1"></i> Hari Ini
            </button>
            <button class="btn btn-outline-secondary btn-sm" id="next-month">
              Bulan Berikutnya <i class="fas fa-chevron-right ms-1"></i>
            </button>
          </div>
        </div>
      </div>
      
      <div class="card-body p-3">
        <!-- Header Bulan -->
        <div class="text-center mb-4">
          <h3 class="calendar-month-year mb-0 fw-bold" id="calendar-month-year"></h3>
        </div>
        
        <!-- Legenda -->
        <div class="d-flex justify-content-center gap-4 mb-3 flex-wrap">
          <div class="d-flex align-items-center gap-2">
            <div class="calendar-legend-today"></div>
            <small class="text-muted">Hari Ini</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="calendar-legend-active"></div>
            <small class="text-muted">Tanggal Aktif</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="calendar-legend-other"></div>
            <small class="text-muted">Bulan Lain</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-circle text-success" style="font-size: 10px;"></i>
            <small class="text-muted">Ada Aktivitas</small>
          </div>
        </div>
        
        <!-- Nama Hari -->
        <div class="calendar-weekdays d-grid mb-2">
          <div class="text-center fw-bold py-2 rounded bg-light">Senin</div>
          <div class="text-center fw-bold py-2 rounded bg-light">Selasa</div>
          <div class="text-center fw-bold py-2 rounded bg-light">Rabu</div>
          <div class="text-center fw-bold py-2 rounded bg-light">Kamis</div>
          <div class="text-center fw-bold py-2 rounded bg-light">Jumat</div>
          <div class="text-center fw-bold py-2 rounded bg-light">Sabtu</div>
          <div class="text-center fw-bold py-2 rounded bg-light text-danger">Minggu</div>
        </div>
        
        <!-- Grid Hari -->
        <div class="calendar-days" id="calendar-days"></div>
      </div>
    </div>
  </div>
</div>