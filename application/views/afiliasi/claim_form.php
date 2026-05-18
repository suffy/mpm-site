<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Claim Aktivitas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* CSS sebelumnya tetap di sini */
    /* CSS tetap sama seperti sebelumnya */
    .table-container {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        max-height: 600px;
        position: relative;
    }
    
    #tabel-ajuan-claim {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    
    #tabel-ajuan-claim thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    #tabel-ajuan-claim th {
        padding: 12px 5px;
        text-align: center;
        font-weight: 600;
        border-right: 1px solid rgba(255,255,255,0.1);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    #tabel-ajuan-claim th:first-child,
    #tabel-ajuan-claim th:nth-child(2) {
        min-width: 200px;
        background: #5a67d8;
    }

    /* Sticky header untuk kolom Activity dan Alat Kerja */
    #tabel-ajuan-claim th.sticky-col {
        position: sticky;
        left: 0;
        z-index: 20;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }

    #tabel-ajuan-claim th.sticky-col:nth-child(2) {
        left: 200px;
    }

    #tabel-ajuan-claim tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: background-color 0.2s;
    }

    #tabel-ajuan-claim tbody tr:hover {
        background-color: #f7fafc;
    }

    #tabel-ajuan-claim td {
        padding: 10px 5px;
        border-right: 1px solid #e2e8f0;
        text-align: center;
        vertical-align: middle;
    }

    #tabel-ajuan-claim td:first-child,
    #tabel-ajuan-claim td:nth-child(2) {
        position: sticky;
        left: 0;
        background: white;
        z-index: 5;
        min-width: 200px;
        text-align: left;
        padding-left: 15px;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }

    #tabel-ajuan-claim td:nth-child(2) {
        left: 200px;
        min-width: 200px;
    }

    /* Styling untuk checkbox */
    .day-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .day-checkbox:checked {
        background-color: #4299e1;
        border-color: #4299e1;
    }

    .day-checkbox:checked::before {
        content: "✓";
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Warna hari Sabtu dan Minggu */
    .day-sabtu {
        background-color: #e6fffa !important;
    }

    .day-minggu {
        background-color: #fff5f5 !important;
    }

    /* Container untuk scroll horizontal */
    .table-container {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        max-height: 600px;
        position: relative;
    }

    /* Header untuk tanggal */
    .day-header {
        font-size: 11px;
        font-weight: normal;
        display: block;
    }

    .day-number {
        font-size: 14px;
        font-weight: bold;
        display: block;
    }

    /* Footer untuk jumlah checklist */
    #tabel-ajuan-claim tfoot {
        background-color: #edf2f7;
        font-weight: bold;
    }

    #tabel-ajuan-claim tfoot td {
        padding: 12px 5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-container {
        font-size: 11px;
    }
    
    #tabel-ajuan-claim td:first-child,
    #tabel-ajuan-claim td:nth-child(2),
    #tabel-ajuan-claim th:first-child,
    #tabel-ajuan-claim th:nth-child(2) {
        min-width: 150px;
    }
    
    #tabel-ajuan-claim td:nth-child(2) {
        left: 150px;
    }
    
    #tabel-ajuan-claim th.sticky-col:nth-child(2) {
        left: 150px;
    }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Form Header -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Form Claim Aktivitas Harian</h4>
            </div>
            <div class="card-body">
                <!-- Form untuk memilih periode -->
                <form method="get" action="" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            <?php for($i=1; $i<=12; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $i == $bulan ? 'selected' : ''; ?>>
                                    <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            <?php for($i=date('Y')-2; $i<=date('Y')+2; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $i == $tahun ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-filter"></i> Tampilkan
                        </button>
                    </div>
                </form>
                
                <!-- Notifikasi -->
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Form Claim -->
        <form id="form-claim" method="post" action="<?php echo site_url('afiliasi/submit'); ?>">
            <!-- CSRF Token untuk CodeIgniter 2 -->
            <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_token_hash; ?>" />
            
            <input type="hidden" name="periode_bulan" value="<?php echo $bulan; ?>">
            <input type="hidden" name="periode_tahun" value="<?php echo $tahun; ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            
            <!-- Tombol Aksi -->
            <div class="mb-3 d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-warning" onclick="selectAll()">
                        <i class="fas fa-check-double"></i> Pilih Semua
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="clearAll()">
                        <i class="fas fa-times"></i> Hapus Semua
                    </button>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveAjax()">
                        <i class="fas fa-paper-plane"></i> Simpan (AJAX)
                    </button>
                </div>
            </div>
            
            <!-- Tabel -->
            <div class="table-container">
                <table id="tabel-ajuan-claim" class="table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center sticky-col">Activity</th>   
                            <th class="text-center sticky-col">Alat Kerja</th>   
                            <?php 
                            // Generate hari dalam bulan
                            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                            for ($day = 1; $day <= $days_in_month; $day++) {
                                $timestamp = strtotime("$tahun-$bulan-$day");
                                $dayOfWeek = date('N', $timestamp);
                                $dayName = date('D', $timestamp);
                                
                                $dayClass = '';
                                if ($dayOfWeek == 6) {
                                    $dayClass = 'day-sabtu';
                                } elseif ($dayOfWeek == 7) {
                                    $dayClass = 'day-minggu';
                                }
                                
                                echo "<th class='text-center $dayClass'>
                                        <span class='day-header'>$dayName</span>
                                        <span class='day-number'>$day</span>
                                        <br>
                                        <small>
                                            <input type='checkbox' 
                                                   class='form-check-input day-all-checkbox' 
                                                   onchange='toggleAllDay($day)'
                                                   title='Pilih semua untuk hari ini'>
                                        </small>
                                      </th>";
                            }
                            ?>
                            <th class="text-center" style="background: #2d3748; color: white;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_activity->result() as $key) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($key->nama_activity); ?></td>
                            <td><?php echo htmlspecialchars($key->alat_kerja); ?></td>
                            <?php 
                            for ($day = 1; $day <= $days_in_month; $day++) {
                                $timestamp = strtotime("$tahun-$bulan-$day");
                                $dayOfWeek = date('N', $timestamp);
                                
                                $dayClass = '';
                                if ($dayOfWeek == 6) {
                                    $dayClass = 'day-sabtu';
                                } elseif ($dayOfWeek == 7) {
                                    $dayClass = 'day-minggu';
                                }
                                
                                $checkboxId = 'day_' . $key->id . '_' . $day;
                                
                                echo "<td class='text-center $dayClass'>
                                        <input type='checkbox' 
                                               class='day-checkbox' 
                                               id='$checkboxId'
                                               name='day[$key->id][$day]'
                                               value='1'
                                               data-activity='$key->id'
                                               data-day='$day'
                                               onchange='updateTotal(this)'>
                                      </td>";
                            }
                            ?>
                            <td class="text-center total-col" id="total_<?php echo $key->id; ?>" style="background: #f7fafc; font-weight: bold;">
                                0
                            </td>
                        </tr>  
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right" style="text-align: right; padding-right: 20px;">
                                <strong>Total Per Hari:</strong>
                            </td>
                            <?php 
                            for ($day = 1; $day <= $days_in_month; $day++) {
                                echo "<td class='text-center day-total' id='day_total_$day' style='background: #e2e8f0;'>
                                        0
                                      </td>";
                            }
                            ?>
                            <td class="text-center" id="grand_total" style="background: #2d3748; color: white;">
                                0
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Summary -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5>Summary</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Total Aktivitas:</strong> <?php echo $get_activity->num_rows(); ?></p>
                            <p><strong>Total Hari dalam Bulan:</strong> <?php echo $days_in_month; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Checklist:</strong> <span id="summary_total">0</span></p>
                            <p><strong>Bulan/Tahun:</strong> <?php echo date('F', mktime(0, 0, 0, $bulan, 1)); ?> <?php echo $tahun; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi JavaScript tetap sama
    function updateTotal(checkbox) {
        const activityId = checkbox.getAttribute('data-activity');
        const day = checkbox.getAttribute('data-day');
        
        let total = 0;
        document.querySelectorAll('input[data-activity="' + activityId + '"]:checked').forEach(function(cb) {
            total++;
        });
        
        document.getElementById('total_' + activityId).textContent = total;
        updateDayTotal(day);
        updateGrandTotal();
    }
    
    function updateDayTotal(day) {
        let dayTotal = 0;
        document.querySelectorAll('input[data-day="' + day + '"]:checked').forEach(function(cb) {
            dayTotal++;
        });
        
        document.getElementById('day_total_' + day).textContent = dayTotal;
    }
    
    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.day-checkbox:checked').forEach(function(cb) {
            grandTotal++;
        });
        
        document.getElementById('grand_total').textContent = grandTotal;
        document.getElementById('summary_total').textContent = grandTotal;
    }
    
    function toggleAllDay(day) {
        const checkboxes = document.querySelectorAll('input[data-day="' + day + '"]');
        const allChecked = Array.from(checkboxes).every(function(cb) {
            return cb.checked;
        });
        
        checkboxes.forEach(function(cb) {
            cb.checked = !allChecked;
            // Trigger change event
            const event = new Event('change');
            cb.dispatchEvent(event);
        });
    }
    
    function selectAll() {
        document.querySelectorAll('.day-checkbox').forEach(function(cb) {
            cb.checked = true;
            const event = new Event('change');
            cb.dispatchEvent(event);
        });
    }
    
    function clearAll() {
        document.querySelectorAll('.day-checkbox').forEach(function(cb) {
            cb.checked = false;
            const event = new Event('change');
            cb.dispatchEvent(event);
        });
    }
    
    // Fungsi AJAX untuk CodeIgniter 2
    function saveAjax() {
        // Ambil CSRF token dari form
        var csrfName = '<?php echo $csrf_token_name; ?>';
        var csrfHash = '<?php echo $csrf_token_hash; ?>';
        
        // Buat FormData
        var formData = new FormData(document.getElementById('form-claim'));
        
        // Tambahkan CSRF token ke FormData
        formData.append(csrfName, csrfHash);
        
        // Tampilkan loading
        var submitBtn = document.querySelector('button[onclick="saveAjax()"]');
        var originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        
        $.ajax({
            url: '<?php echo site_url("claim/save_ajax"); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Data berhasil disimpan! Total hari: ' + response.total_hari);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error saving data: ' + error);
            },
            complete: function() {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize totals
        for (var day = 1; day <= <?php echo $days_in_month; ?>; day++) {
            updateDayTotal(day);
        }
        updateGrandTotal();
        
        // Add event listener untuk semua checkbox
        document.querySelectorAll('.day-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateTotal(this);
            });
        });
    });
    </script>
</body>
</html>