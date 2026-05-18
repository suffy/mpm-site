

<div class="container-fluid">

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; justify-content: center; align-items: center; flex-direction: column; backdrop-filter: blur(3px);">
    <div style="text-align: center;">
        <div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem; border-width: 0.25em; color: #B43F3F !important;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p style="margin-top: 15px; color: #333; font-weight: 500; font-size: 16px;">Processing your request...</p>
        <p style="margin-top: 5px; color: #666; font-size: 14px;">Please wait</p>
    </div>
</div>

<!-- Flash Message -->
<div class="row mt-3">
    <div class="col-md-12">
        <?php 
        if($this->session->flashdata('pesan')){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('pesan'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }elseif($this->session->flashdata('pesan_success')){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('pesan_success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>
    </div>
</div>

<!-- Card Utama -->
<div class="card shadow-sm">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><?= $title.' ( '.$nama.' - '.$nama_divisi.' - '.$nama_jabatan. ')' ?></h4>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Baris Tombol Export & Import -->
        <div class="row g-3 mb-4">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= site_url('afiliasi/export_monthly_planning/'.$selected_date) ?>" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file-export me-1"></i> Export Plan Bulan 
                        <?php 
                        if(isset($selected_date)) {
                            $date = new DateTime($selected_date);
                            echo $date->format('F Y');
                        } else {
                            echo date('F Y');
                        }
                        ?>
                    </a>
                    <a href="<?= site_url('afiliasi/export_template_import') ?>" class="btn btn-warning" target="_blank">
                        <i class="fas fa-download me-1"></i> Export Template Import
                    </a>
                    <a href="<?= site_url('afiliasi/export_master_activity') ?>" class="btn btn-success" target="_blank">
                        <i class="fas fa-download me-1"></i> Export Master Activity
                    </a>
                </div>
            </div>
        </div>

        <!-- Baris Import File -->
        <div class="row">
            <div class="col-lg-12">
                <div class="border rounded p-3">
                    <label class="fw-semibold mb-2">Import Data</label>
                    <?php echo form_open_multipart($url_import); ?>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <input type="file" name="file" class="form-control" style="max-width: 300px;">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-upload me-1"></i> Import File Template
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Reminder -->
<?php if(!empty($reminder_bulanan) || !empty($reminder_harian) || !empty($reminder_not_harian_bulanan)): ?>
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card shadow-sm border-warning">
            <div class="card-header bg-warning bg-opacity-10">
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

<!-- Kalender -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="calendar-container">
                    <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                        <h3 class="calendar-month-year mb-0" id="calendar-month-year"></h3>
                        <div class="calendar-nav-buttons d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" id="prev-month">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="btn btn-outline-primary btn-sm" id="today-btn">
                                <i class="fas fa-calendar-day me-1"></i> Hari Ini
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" id="next-month">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="calendar-weekdays d-grid mb-2" style="grid-template-columns: repeat(7, 1fr); gap: 5px;">
                        <div class="text-center fw-bold py-2 rounded bg-light">Sen</div>
                        <div class="text-center fw-bold py-2 rounded bg-light">Sel</div>
                        <div class="text-center fw-bold py-2 rounded bg-light">Rab</div>
                        <div class="text-center fw-bold py-2 rounded bg-light">Kam</div>
                        <div class="text-center fw-bold py-2 rounded bg-light">Jum</div>
                        <div class="text-center fw-bold py-2 rounded bg-light">Sab</div>
                        <div class="text-center fw-bold py-2 rounded bg-light text-danger">Ming</div>
                    </div>
                    
                    <div class="calendar-days" id="calendar-days">
                        <!-- Hari-hari akan diisi dengan JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        
                        <button class="btn btn-outline-secondary rounded-circle position-absolute end-0 top-50 translate-middle-y" 
                                id="scrollRightBtn" style="z-index: 10;">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Form Tambah Aktivitas -->
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

<!-- Modal Popup untuk menampilkan aktivitas per tanggal -->
<div class="modal fade" id="activitiesModal" tabindex="-1" aria-labelledby="activitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="activitiesModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i> Aktivitas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="activitiesModalBody" style="max-height: 500px; overflow-y: auto;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data aktivitas...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
                <button type="button" class="btn btn-primary" id="addActivityFromModal">
                    <i class="fas fa-plus me-1"></i> Tambah Aktivitas
                </button>
            </div>
        </div>
    </div>
</div>

</div>

<!-- JavaScript -->
<script>
// Data untuk JavaScript
const phpData = {
    selectedDate: '<?php echo isset($selected_date) ? $selected_date : date("Y-m-d"); ?>',
    baseUrl: '<?php echo site_url("afiliasi/monthly_planning"); ?>',
    siteUrl: '<?php echo site_url(); ?>'
};

// Variabel global untuk kalender
let currentCalendarDate = new Date();

// Fungsi format tanggal
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}

// Fungsi format datetime
function formatDateTime(dateTimeStr) {
    if (!dateTimeStr) return '-';
    const date = new Date(dateTimeStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${day}/${month}/${year} ${hours}:${minutes}`;
}

// Fungsi escape HTML
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

// Fungsi untuk menampilkan loading overlay
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.style.display = 'none';
}

// Fungsi untuk menampilkan popup aktivitas
function showActivitiesPopup(dateString) {
    const modalBody = document.getElementById('activitiesModalBody');
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data aktivitas...</p>
            </div>
        `;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('activitiesModal'));
    modal.show();
    
    const dateObj = new Date(dateString);
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const formattedDate = `${dayNames[dateObj.getDay()]}, ${dateObj.getDate()} ${monthNames[dateObj.getMonth()]} ${dateObj.getFullYear()}`;
    
    const modalLabel = document.getElementById('activitiesModalLabel');
    if (modalLabel) {
        modalLabel.innerHTML = `<i class="fas fa-calendar-alt me-2"></i> Aktivitas - ${formattedDate}`;
    }
    
    const modalElement = document.getElementById('activitiesModal');
    if (modalElement) modalElement.setAttribute('data-current-date', dateString);
    
    // AJAX request
    const ajaxUrl = phpData.baseUrl + '/afiliasi/get_activities_by_date?date=' + dateString;
    // console.log("AJAX URL:", ajaxUrl);
    
    fetch(ajaxUrl, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success && result.data && result.data.length > 0) {
            let activitiesHtml = '<div class="activities-list">';
            result.data.forEach(activity => {
                activitiesHtml += `
                    <div class="activity-item-card mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-check-circle me-2"></i>
                                ${escapeHtml(activity.title)}
                            </h6>
                            <button class="btn btn-sm btn-danger delete-activity-btn" 
                                    data-id="${activity.id}" data-date="${dateString}"
                                    onclick="deleteActivity('${activity.id}', '${dateString}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        ${activity.keterangan ? `<div class="mb-2 text-secondary small"><i class="fas fa-align-left me-1"></i> ${escapeHtml(activity.keterangan)}</div>` : ''}
                        <hr class="my-2">
                        <div class="text-muted small">
                            <div><i class="fas fa-user me-1"></i> ${escapeHtml(activity.nama)}</div>
                            <div><i class="fas fa-building me-1"></i> ${escapeHtml(activity.nama_divisi)}</div>
                            <div><i class="fas fa-briefcase me-1"></i> ${escapeHtml(activity.nama_jabatan)}</div>
                            <div><i class="fas fa-clock me-1"></i> ${formatDateTime(activity.created_at)}</div>
                        </div>
                    </div>
                `;
            });
            activitiesHtml += '</div>';
            if (modalBody) modalBody.innerHTML = activitiesHtml;
        } else {
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <h6>Tidak ada aktivitas untuk tanggal ini</h6>
                        <p class="small">Klik tombol di bawah untuk menambahkan aktivitas</p>
                    </div>
                `;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center py-5 text-danger">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h6>Gagal memuat data</h6>
                    <p class="small">Silakan coba lagi</p>
                </div>
            `;
        }
    });
}

// Fungsi hapus aktivitas
function deleteActivity(activityId, dateString) {
    if (confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')) {
        showLoading();
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        const csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        
        fetch('<?php echo site_url("afiliasi/delete_monthly_plan"); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `activity_id=${activityId}&selected_date=${dateString}&${csrfName}=${csrfHash}`
        })
        .then(() => window.location.reload())
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
            alert('Terjadi kesalahan saat menghapus data');
        });
    }
}

// Fungsi render kalender
function renderCalendar(date) {
    const monthYear = document.getElementById("calendar-month-year");
    const daysGrid = document.getElementById("calendar-days");
    if (!monthYear || !daysGrid) return;
    
    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    monthYear.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
    daysGrid.innerHTML = "";
    
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    const prevMonthLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
    
    let firstDayIndex = firstDay.getDay();
    if (firstDayIndex === 0) firstDayIndex = 6;
    else firstDayIndex = firstDayIndex - 1;
    
    // Previous month days
    for (let i = firstDayIndex; i > 0; i--) {
        const day = prevMonthLastDay - i + 1;
        const dateObj = new Date(date.getFullYear(), date.getMonth() - 1, day);
        const dateString = formatDate(dateObj);
        const dayElement = createDayElement(day, true, false, false, dateString);
        daysGrid.appendChild(dayElement);
    }
    
    // Current month days
    const today = new Date();
    let selectedDateFromPhp = phpData.selectedDate ? new Date(phpData.selectedDate) : null;
    
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const dateObj = new Date(date.getFullYear(), date.getMonth(), i);
        const dateString = formatDate(dateObj);
        const isToday = i === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear();
        const isSelected = selectedDateFromPhp && i === selectedDateFromPhp.getDate() && date.getMonth() === selectedDateFromPhp.getMonth() && date.getFullYear() === selectedDateFromPhp.getFullYear();
        const dayElement = createDayElement(i, false, isToday, isSelected, dateString);
        daysGrid.appendChild(dayElement);
    }
    
    // Next month days
    const totalCells = 42;
    const nextMonthDays = totalCells - (firstDayIndex + lastDay.getDate());
    for (let i = 1; i <= nextMonthDays; i++) {
        const dateObj = new Date(date.getFullYear(), date.getMonth() + 1, i);
        const dateString = formatDate(dateObj);
        const dayElement = createDayElement(i, true, false, false, dateString);
        daysGrid.appendChild(dayElement);
    }
}

// Fungsi membuat elemen hari
function createDayElement(dayNumber, isOtherMonth, isToday, isSelected, dateString) {
    const dayElement = document.createElement("div");
    dayElement.classList.add("calendar-day");
    if (isOtherMonth) dayElement.classList.add("other-month");
    if (isToday) dayElement.classList.add("today");
    if (isSelected) dayElement.classList.add("active");
    dayElement.setAttribute("data-date", dateString);
    dayElement.innerHTML = `<div class="day-number">${dayNumber}</div>`;
    dayElement.addEventListener("click", (e) => {
        e.stopPropagation();
        showActivitiesPopup(dateString);
    });
    return dayElement;
}

// Fungsi navigasi
function changeMonth(direction) {
    let currentDate = new Date(phpData.selectedDate);
    if (direction === 'prev') currentDate.setMonth(currentDate.getMonth() - 1);
    else if (direction === 'next') currentDate.setMonth(currentDate.getMonth() + 1);
    const newDate = formatDate(currentDate);
    window.location.href = phpData.baseUrl + '?date=' + newDate;
}

function goToToday() {
    const today = new Date();
    window.location.href = phpData.baseUrl + '?date=' + formatDate(today);
}

// Fungsi horizontal scroll
function setupHorizontalScroll() {
    const container = document.getElementById('horizontalScrollContainer');
    const scrollLeftBtn = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');
    if (!container) return;
    
    function updateScrollButtons() {
        if (scrollLeftBtn && scrollRightBtn) {
            const maxScrollLeft = container.scrollWidth - container.clientWidth;
            scrollLeftBtn.style.display = container.scrollLeft > 0 ? 'flex' : 'none';
            scrollRightBtn.style.display = container.scrollLeft < maxScrollLeft - 5 ? 'flex' : 'none';
        }
    }
    
    container.addEventListener('scroll', updateScrollButtons);
    if (scrollLeftBtn) scrollLeftBtn.addEventListener('click', () => container.scrollBy({ left: -350, behavior: 'smooth' }));
    if (scrollRightBtn) scrollRightBtn.addEventListener('click', () => container.scrollBy({ left: 350, behavior: 'smooth' }));
    setTimeout(updateScrollButtons, 100);
    window.addEventListener('resize', updateScrollButtons);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    hideLoading();
    window.addEventListener('pageshow', () => hideLoading());
    
    if (phpData.selectedDate) {
        const [year, month, day] = phpData.selectedDate.split("-");
        currentCalendarDate = new Date(year, month - 1, day);
    }
    renderCalendar(currentCalendarDate);
    
    document.getElementById('prev-month')?.addEventListener('click', (e) => { e.preventDefault(); showLoading(); changeMonth('prev'); });
    document.getElementById('next-month')?.addEventListener('click', (e) => { e.preventDefault(); showLoading(); changeMonth('next'); });
    document.getElementById('today-btn')?.addEventListener('click', (e) => { e.preventDefault(); showLoading(); goToToday(); });
    
    document.getElementById('add-event-form')?.addEventListener('submit', function() {
        const activitySelect = document.getElementById('event-title');
        const selectedDate = document.getElementById('selected-date-value');
        if (activitySelect?.value && selectedDate?.value) showLoading();
    });
    
    document.querySelectorAll('form[action*="import"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const fileInput = form.querySelector('input[name="file"]');
            if (!fileInput?.files.length) {
                e.preventDefault();
                alert('Harap pilih file yang akan diimport!');
            } else showLoading();
        });
    });
    
    document.querySelectorAll('form[action*="delete_monthly_plan"]').forEach(form => {
        form.addEventListener('submit', () => showLoading());
    });
    
    setupHorizontalScroll();
    
    // Tombol Tambah Aktivitas di modal
    document.getElementById('addActivityFromModal')?.addEventListener('click', function() {
        const modal = document.getElementById('activitiesModal');
        const currentDate = modal?.getAttribute('data-current-date');
        if (currentDate) window.location.href = phpData.baseUrl + '?date=' + currentDate;
    });
});
</script>