<!-- JavaScript -->
<script>
// Data untuk JavaScript
const phpData = {
    selectedDate: '<?php echo isset($selected_date) ? $selected_date : date("Y-m-d"); ?>',
    baseUrl: '<?php echo site_url("afiliasi/monthly_planning"); ?>',
    siteUrl: '<?php echo site_url(); ?>',
    planData: <?php echo json_encode($plan); ?>,
    allPlans: <?php echo json_encode($all_plans); ?>,
    countPlan: <?php echo json_encode($count_plan); ?>
};

// Debug: Tampilkan data di console untuk memastikan
console.log('Selected Date:', phpData.selectedDate);
console.log('All Plans Data:', phpData.allPlans);
console.log('Count Plans:', phpData.countPlan);

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
    return str.replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#39;');
}

// Fungsi untuk mendapatkan aktivitas berdasarkan tanggal dari allPlans
function getActivitiesByDate(dateString) {
    if (!phpData.allPlans || phpData.allPlans.length === 0) {
        console.log('No allPlans data available');
        return [];
    }
    
    // Filter plan berdasarkan tanggal
    const filtered = phpData.allPlans.filter(activity => {
        const activityDate = activity.date;
        return activityDate === dateString;
    });
    
    console.log(`Activities for ${dateString}:`, filtered);
    return filtered;
}

// Fungsi untuk mendapatkan daftar unik aktivitas per tanggal
function getUniqueActivitiesByDate(dateString) {
    const activities = getActivitiesByDate(dateString);
    
    // Buat map untuk menghindari duplicate activity
    const uniqueMap = new Map();
    activities.forEach(activity => {
        if (!uniqueMap.has(activity.id_activity)) {
            uniqueMap.set(activity.id_activity, activity);
        }
    });
    
    return Array.from(uniqueMap.values());
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

// Fungsi hapus aktivitas
function deleteActivity(activityId, dateString) {
    if (confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')) {
        showLoading();
        const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        const csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        
        fetch('<?php echo site_url("afiliasi/delete_monthly_plan"); ?>', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `activity_id=${activityId}&selected_date=${dateString}&${csrfName}=${csrfHash}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                hideLoading();
                alert(data.message || 'Terjadi kesalahan saat menghapus data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
            alert('Terjadi kesalahan saat menghapus data');
        });
    }
}

// Fungsi navigasi bulan
function changeMonth(direction) {
    let currentDate = new Date(phpData.selectedDate);
    if (direction === 'prev') {
        currentDate.setMonth(currentDate.getMonth() - 1);
    } else if (direction === 'next') {
        currentDate.setMonth(currentDate.getMonth() + 1);
    }
    const newDate = formatDate(currentDate);
    window.location.href = phpData.baseUrl + '?date=' + newDate;
}

function goToToday() {
    const today = new Date();
    window.location.href = phpData.baseUrl + '?date=' + formatDate(today);
}

// Fungsi render kalender
function renderCalendar(date) {
    const monthYear = document.getElementById("calendar-month-year");
    const daysGrid = document.getElementById("calendar-days");
    if (!monthYear || !daysGrid) return;
    
    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    monthYear.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
    daysGrid.innerHTML = "";
    
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    const prevMonthLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
    
    // Menyesuaikan index hari (Senin = 0)
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
        const isToday = i === today.getDate() && 
                       date.getMonth() === today.getMonth() && 
                       date.getFullYear() === today.getFullYear();
        const isSelected = selectedDateFromPhp && 
                          i === selectedDateFromPhp.getDate() && 
                          date.getMonth() === selectedDateFromPhp.getMonth() && 
                          date.getFullYear() === selectedDateFromPhp.getFullYear();
        
        // Dapatkan aktivitas untuk tanggal ini
        const activities = getActivitiesByDate(dateString);
        const activityCount = activities.length;
        const uniqueActivities = getUniqueActivitiesByDate(dateString);
        
        const dayElement = createDayElement(i, false, isToday, isSelected, dateString, activityCount, uniqueActivities);
        daysGrid.appendChild(dayElement);
    }
    
    // Next month days (total 42 cells = 6 minggu)
    const totalCells = 42;
    const nextMonthDays = totalCells - (firstDayIndex + lastDay.getDate());
    for (let i = 1; i <= nextMonthDays; i++) {
        const dateObj = new Date(date.getFullYear(), date.getMonth() + 1, i);
        const dateString = formatDate(dateObj);
        const dayElement = createDayElement(i, true, false, false, dateString);
        daysGrid.appendChild(dayElement);
    }
}

// Fungsi membuat elemen hari dengan list aktivitas
function createDayElement(dayNumber, isOtherMonth, isToday, isSelected, dateString, activityCount = 0, activities = []) {
    const dayElement = document.createElement("div");
    dayElement.classList.add("calendar-day");
    if (isOtherMonth) dayElement.classList.add("other-month");
    if (isToday) dayElement.classList.add("today");
    if (isSelected) dayElement.classList.add("active");
    dayElement.setAttribute("data-date", dateString);
    
    // Konten HTML untuk hari
    let html = `<div class="day-number">${dayNumber}</div>`;
    
    // Tampilkan daftar aktivitas (max 2 items)
    if (activities.length > 0 && !isOtherMonth) {
        html += `<div class="activity-list mt-1 w-100">`;
        const maxDisplay = 2;
        
        for (let i = 0; i < Math.min(activities.length, maxDisplay); i++) {
            const activity = activities[i];
            let shortTitle = activity.title;
            if (shortTitle && shortTitle.length > 25) {
                shortTitle = shortTitle.substring(0, 22) + '...';
            }
            html += `<div class="activity-item small mb-1 text-start" title="${escapeHtml(activity.title || '')}">
                        <i class="fas fa-check-circle text-success" style="font-size: 10px;"></i>
                        <span class="ms-1">${escapeHtml(shortTitle || 'No Title')}</span>
                    </div>`;
        }
        if (activities.length > maxDisplay) {
            html += `<div class="text-muted small mt-1 text-center">
                        <i class="fas fa-plus-circle"></i> +${activities.length - maxDisplay} aktivitas lainnya
                    </div>`;
        }
        html += `</div>`;
    } else if (activityCount === 0 && !isOtherMonth) {
        html += `<div class="text-muted small mt-2 text-center">
                    <i class="fas fa-plus-circle"></i> Tambah
                </div>`;
    }
    
    dayElement.innerHTML = html;
    
    // Event click untuk menampilkan modal aktivitas
    dayElement.addEventListener("click", (e) => {
        e.stopPropagation();
        showActivitiesModal(dateString);
    });
    
    return dayElement;
}

// Fungsi untuk menampilkan modal dengan list aktivitas dan form
function showActivitiesModal(dateString) {
    const modalBody = document.getElementById('activitiesModalBody');
    if (!modalBody) return;
    
    // Format tanggal untuk display
    const dateObj = new Date(dateString);
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const formattedDate = `${dayNames[dateObj.getDay()]}, ${dateObj.getDate()} ${monthNames[dateObj.getMonth()]} ${dateObj.getFullYear()}`;
    
    // Update modal title
    const modalLabel = document.getElementById('activitiesModalLabel');
    if (modalLabel) {
        modalLabel.innerHTML = `<i class="fas fa-calendar-alt me-2"></i> Planning - ${formattedDate}`;
    }
    
    // Simpan tanggal di modal untuk referensi
    const modalElement = document.getElementById('activitiesModal');
    if (modalElement) {
        modalElement.setAttribute('data-current-date', dateString);
    }
    
    // Dapatkan SEMUA aktivitas untuk tanggal ini
    const allActivities = getActivitiesByDate(dateString);
    
    // Buat konten modal
    let modalContent = `
        <!-- Form Tambah Aktivitas -->
        <div class="card mb-4 border-primary">
            <div class="card-header var(--bs-body-bg) text-white">
                <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Planning Baru</h6>
            </div>
            <div class="card-body">
                <form id="modal-add-event-form" method="POST" action="<?php echo site_url($url); ?>">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="selected_date" value="${dateString}">
                    
                    <div class="mb-3">
                        <label for="modal-event-title" class="form-label fw-semibold">Pilih Aktivitas *</label>
                        <select class="form-select" id="modal-event-title" name="activity_id" required>
                            <option value="">-- Pilih Activity --</option>
                            <?php foreach($get_activity as $activity): ?>
                                <option value="<?= $activity->id; ?>"><?= addslashes($activity->nama_activity); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal-event-description" class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control" id="modal-event-description" name="keterangan" rows="2" placeholder="Masukkan keterangan (opsional)"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Simpan Planning
                    </button>
                </form>
            </div>
        </div>
    `;
    
    // Tambahkan daftar aktivitas yang sudah ada
    if (allActivities.length > 0) {
        modalContent += `
            <div class="card">
                <div class="card-header var(--bs-body-bg) text-white">
                    <h6 class="mb-0"><i class="fas fa-list-check me-2"></i>Daftar Aktivitas (${allActivities.length})</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
        `;
        
        allActivities.forEach(activity => {
            modalContent += `
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-primary">
                                <i class="fas fa-check-circle me-2"></i>
                                ${escapeHtml(activity.title)}
                            </h6>
                            ${activity.keterangan ? `<p class="mb-1 text-secondary small"><i class="fas fa-align-left me-1"></i> ${escapeHtml(activity.keterangan)}</p>` : ''}
                            <div class="text-muted small mt-2">
                                <div><i class="fas fa-user me-1"></i> ${escapeHtml(activity.nama)}</div>
                                <div><i class="fas fa-building me-1"></i> ${escapeHtml(activity.nama_divisi)}</div>
                                <div><i class="fas fa-briefcase me-1"></i> ${escapeHtml(activity.nama_jabatan)}</div>
                                <div><i class="fas fa-clock me-1"></i> Dibuat: ${formatDateTime(activity.created_at)}</div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-danger ms-2" onclick="deleteActivity('${activity.id_activity}', '${dateString}')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        modalContent += `
                    </div>
                </div>
            </div>
        `;
    } else {
        modalContent += `
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Belum ada aktivitas untuk tanggal ini</h6>
                    <p class="small text-muted">Silakan tambah aktivitas menggunakan form di atas</p>
                </div>
            </div>
        `;
    }
    
    modalBody.innerHTML = modalContent;
    
    // Tampilkan modal
    const modal = new bootstrap.Modal(document.getElementById('activitiesModal'));
    modal.show();
    
    
    // Event listener untuk form di modal
    const modalForm = document.getElementById('modal-add-event-form');
    if (modalForm) {
        modalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    hideLoading();
                    alert(data.message || 'Terjadi kesalahan saat menyimpan data');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            });
        });
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    hideLoading();
    
    // Set current calendar date dari selected_date
    if (phpData.selectedDate) {
        const [year, month, day] = phpData.selectedDate.split("-");
        currentCalendarDate = new Date(year, month - 1, day);
    }
    
    // Render calendar
    renderCalendar(currentCalendarDate);
    
    // Event listeners untuk tombol navigasi
    const prevBtn = document.getElementById('prev-month');
    const nextBtn = document.getElementById('next-month');
    const todayBtn = document.getElementById('today-btn');
    
    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showLoading();
            changeMonth('prev');
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showLoading();
            changeMonth('next');
        });
    }
    
    if (todayBtn) {
        todayBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showLoading();
            goToToday();
        });
    }
    
    // Loading saat submit form utama
    const mainForm = document.getElementById('add-event-form');
    if (mainForm) {
        mainForm.addEventListener('submit', function() {
            showLoading();
        });
    }
    
    // Tombol Tambah Aktivitas di modal
    const addBtn = document.getElementById('addActivityFromModal');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            const modal = document.getElementById('activitiesModal');
            const currentDate = modal ? modal.getAttribute('data-current-date') : null;
            if (currentDate) {
                const formElement = document.querySelector('#activitiesModalBody .card:first-child');
                if (formElement) {
                    formElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    }
    
    // Hilangkan loading saat page load kembali
    window.addEventListener('pageshow', () => hideLoading());
});


// ========== FIX TOMBOL TUTUP MODAL ==========
// Pastikan modal bisa tertutup dengan benar
document.addEventListener('DOMContentLoaded', function() {
    // Fix untuk tombol tutup modal
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modalElement = document.getElementById('activitiesModal');
    
    if (closeModalBtn && modalElement) {
        closeModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            } else {
                // Jika tidak ada instance, buat instance baru lalu tutup
                const newModal = new bootstrap.Modal(modalElement);
                newModal.hide();
            }
        });
    }
    
    // Juga fix untuk tombol close (X) di header
    const closeBtn = document.querySelector('#activitiesModal .btn-close');
    if (closeBtn && modalElement) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            } else {
                const newModal = new bootstrap.Modal(modalElement);
                newModal.hide();
            }
        });
    }
});

// Override showActivitiesModal untuk memastikan modal bisa ditutup
const originalShowActivitiesModal = showActivitiesModal;
window.showActivitiesModal = function(dateString) {
    originalShowActivitiesModal(dateString);
    
    // Setelah modal ditampilkan, pastikan tombol tutup berfungsi
    setTimeout(function() {
        const modalElement = document.getElementById('activitiesModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const closeBtn = document.querySelector('#activitiesModal .btn-close');
        
        if (modalElement) {
            // Pastikan modal instance tersedia
            let modal = bootstrap.Modal.getInstance(modalElement);
            if (!modal) {
                modal = new bootstrap.Modal(modalElement);
            }
            
            // Event untuk tombol tutup
            if (closeModalBtn) {
                closeModalBtn.onclick = function(e) {
                    e.preventDefault();
                    modal.hide();
                };
            }
            
            // Event untuk tombol close (X)
            if (closeBtn) {
                closeBtn.onclick = function(e) {
                    e.preventDefault();
                    modal.hide();
                };
            }
        }
    }, 100);
};

// Fix simple untuk tombol tutup modal
document.querySelectorAll('#activitiesModal [data-bs-dismiss="modal"]').forEach(btn => {
    btn.onclick = () => { bootstrap.Modal.getInstance(document.getElementById('activitiesModal'))?.hide(); };
});


</script>