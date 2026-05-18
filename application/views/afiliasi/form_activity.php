<!-- application/views/afiliasi/form_activity.php -->
<style>
    /* Additional styles for better UI */
    .month-filter {
        max-width: 300px;
    }
    
    .activity-check {
        cursor: pointer;
        width: 24px;
        height: 24px;
        border: 2px solid #dee2e6;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: all 0.2s;
    }
    
    .activity-check.checked {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
    
    .activity-check:hover {
        border-color: #28a745;
    }
    
    .day-header {
        font-size: 11px;
        font-weight: bold;
        padding: 8px 4px !important;
        text-align: center;
        min-width: 35px;
    }
    
    .day-header.today {
        background-color: #fff3cd;
        color: #856404;
        position: relative;
    }
    
    .day-header.today::after {
        content: "H";
        font-size: 8px;
        position: absolute;
        top: 2px;
        right: 2px;
    }
    
    .activity-name {
        font-size: 13px;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        padding: 8px 5px;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .activity-name:hover {
        background-color: #f8f9fa;
    }
    
    .activity-name.expanded {
        white-space: normal;
        overflow: visible;
        word-wrap: break-word;
        background-color: #f0f8ff;
        border-left: 3px solid #3498db;
        padding-left: 10px;
    }
    
    .activity-name .expand-icon {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: #3498db;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .activity-name:hover .expand-icon {
        opacity: 1;
    }
    
    .activity-name.expanded .expand-icon {
        opacity: 1;
        transform: translateY(-50%) rotate(180deg);
    }
    
    .frequency-badge {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .harian-badge { background-color: #d4edda; color: #155724; }
    .mingguan-badge { background-color: #d1ecf1; color: #0c5460; }
    .bulanan-badge { background-color: #f8d7da; color: #721c24; }
    
    .summary-count {
        font-weight: bold;
        font-size: 16px;
    }
    
    .pending-box {
        /* background-color: #f8f9fa; */
        background-color: var(--bs-light-bg-subtle);
        border-left: 4px solid #dc3545;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .pending-box h6 {
        font-size: 14px;
        margin-bottom: 5px;
        color: #dc3545;
    }
    
    .table-container {
        overflow-x: auto;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }
    
    .table-container table {
        margin-bottom: 0;
        /* min-width: 1200px; */
        width: 100%;
    }

</style>

<div class="container-fluid">
    <!-- Header and Month Filter -->
    <div class="row mb-3 mt-3">
        <div class="col-md-6">
            <h4><?= $title ?></h4>
        </div>
        <div class="col-md-6 text-end">
            <form action="<?= site_url('afiliasi'); ?>" method="GET" class="d-inline-flex">
                <input type="month" class="form-control form-control-sm month-filter me-2" name="month" id="month" value="<?= $month ?>" required>
                
                <button type="submit" class="btn btn-dark btn-sm d-inline-flex justify-items-center align-items-center gap-2">
                    <i class="fas fa-search"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if($this->session->flashdata('pesan') || $this->session->flashdata('pesan_success')): ?>
    <div class="row">
        <div class="col-md-12">
            <?php if($this->session->flashdata('pesan')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if($this->session->flashdata('pesan_success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Activity Form -->
    <div class="row">
        <div class="col-md-12">
          
          <form action="<?= site_url('afiliasi/save_activity'); ?>" method="POST">
            <input type="hidden" name="month" value="<?= $month ?>">
            
            <div class="table-container">
              <div class="row my-4">
                <div class="col-md-12 text-center">
                  <h5>Cheklist on <?= date('F Y', strtotime($month . '-01')) ?></h5>
                </div>
              </div>
              
              <table id="tabel-activity" class="table-striped" >
                  <thead>
                      <tr>
                          <th class="text-center" style="width: 40px">No</th>   
                          <th style="min-width: 200px">Activity</th>   
                          <th class="text-center" style="width: 80px">Frekuensi</th>   
                          <th style="width: 100px">Alat Kerja</th>   
                          <?php 
                              $today_day = date('d', strtotime($today));
                              for ($i = 1; $i <= $total_hari; $i++): 
                                  $is_today = ($i == $today_day && $month == date('Y-m')) ? 'today' : '';
                          ?>
                          <th class="text-center day-header <?= $is_today ?>"><?= $i; ?></th>   
                          <?php endfor; ?>  
                      </tr>
                  </thead>
                  <tbody>
                      <?php
                          $no = 1; 
                          foreach ($get_activity->result() as $key)  {
                              $frequency_class = strtolower($key->frekuensi) . '-badge';
                              // Check if text is long (more than 40 characters)
                              $is_long_text = strlen($key->nama_activity) > 40;
                      ?>
                      <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td>
                              <div class="activity-name <?= $is_long_text ? 'truncated' : '' ?>" 
                                    onclick="toggleActivityText(this)"
                                    title="<?= $is_long_text ? 'Klik untuk melihat lengkap' : $key->nama_activity ?>">
                                  <?= $key->nama_activity; ?>
                                  <?php if($is_long_text): ?>
                                  <span class="expand-icon">
                                      <i class="fas fa-chevron-down"></i>
                                  </span>
                                  <?php endif; ?>
                              </div>
                          </td>
                          <td class="text-center">
                              <span class="frequency-badge <?= $frequency_class ?>">
                                  <?= $key->frekuensi; ?>
                              </span>
                          </td>
                          <td><?= $key->alat_kerja; ?></td>
                          <?php for ($i = 1; $i <= $total_hari; $i++): 
                              $day_column = 'day_' . $i;
                              $is_checked = false;
                              
                              if (isset($key->$day_column) && $key->$day_column == 1) {
                                  $is_checked = true;
                              }
                              
                              $checkbox_id = 'check_' . $key->id . '_' . $i;
                          ?>
                          <td class="text-center" style="padding: 5px;">
                              <input type="checkbox" 
                                  name="activity[<?= $key->id; ?>][<?= $i; ?>]" 
                                  value="1"
                                  id="<?= $checkbox_id ?>"
                                  <?= $is_checked ? 'checked' : '' ?>
                                  style="display: none;">
                              <div class="activity-check <?= $is_checked ? 'checked' : '' ?>" 
                                    onclick="toggleCheckbox('<?= $checkbox_id ?>')">
                                  <?php if($is_checked): ?>✓<?php endif; ?>
                              </div>
                          </td>
                          <?php endfor; ?>
                      </tr>  
                      <?php } ?>
                  </tbody>
              </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-submit-red">
                    <i class="fas fa-save me-1"></i> Update Data
                </button>

                <a href="<?= site_url('afiliasi/export_activity/'.$month) ?>" class="btn btn-primary">Export Data
                <?php 
                if(isset($month)) {
                    $date = new DateTime($month);
                    echo $date->format('F Y');
                } else {
                    echo date('F Y');
                }
                ?></a>
            </div>
          </form>
        </div>
    </div>

    <div class="row my-3">
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    <!-- Pending Activities -->
    <div class="row">
        <?php if($harian->num_rows() > 0): ?>
        <div class="col-md-4">
            <div class="pending-box">
                <h6>
                    <i class="fas fa-calendar-alt me-1"></i>
                    Harian Tertunda 
                    <span class="badge bg-danger float-end"><?= $harian->num_rows() ?></span>
                </h6>
                <small><?= $today ?></small>
                <div class="mt-2">
                    <table class="table table-sm">
                        <tbody>
                            <?php $no = 1; foreach ($harian->result() as $key): ?>
                            <tr>
                                <td width="20"><?= $no++ ?>.</td>
                                <td><?= $key->nama_activity ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if($bulanan->num_rows() > 0): ?>
        <div class="col-md-4">
            <div class="pending-box">
                <h6>
                    <i class="fas fa-calendar-alt me-1"></i>
                    Bulanan Tertunda 
                    <span class="badge bg-info float-end"><?= $bulanan->num_rows() ?></span>
                </h6>
                <small><?= date('F Y', strtotime($month . '-01')) ?></small>
                <div class="mt-2">
                    <table class="table table-sm">
                        <tbody>
                            <?php $no = 1; foreach ($bulanan->result() as $key): ?>
                            <tr>
                                <td width="20"><?= $no++ ?>.</td>
                                <td><?= $key->nama_activity ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($not_bulanan_harian->num_rows() > 0): ?>
        <div class="col-md-4">
            <div class="pending-box">
                <h6>
                    <i class="fas fa-calendar-alt me-1"></i>
                    Lainnya
                    <span class="badge bg-warning float-end"><?= $not_bulanan_harian->num_rows() ?></span>
                </h6>
                <small>-</small>
                <div class="mt-2">
                    <table class="table table-sm">
                        <tbody>
                            <?php $no = 1; foreach ($not_bulanan_harian->result() as $key): ?>
                            <tr>
                                <td width="20"><?= $no++ ?>.</td>
                                <td><?= $key->nama_activity ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
    </div>

</div>

<script>
$(document).ready(function () {
    // Initialize DataTables untuk tabel activity
    $('#tabel-activity').DataTable({
        "pageLength": 100,
        "ordering": false,
        // "scrollX": true,
        "lengthChange": false,
        "info": false,
        "paging": false,
        // "searching": true
    });

    // Initialize DataTables untuk tabel summary
    setTimeout(function() {
        $('#tabel-summary').DataTable({
            "pageLength": 50,
            "ordering": false,
            "lengthChange": true,
            "info": true,
            "paging": true,
            "language": {
                "emptyTable": "Tidak ada data yang tersedia",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "lengthMenu": "Tampilkan _MENU_ data",
                "loadingRecords": "Memuat...",
                "processing": "Memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang cocok",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    }, 100);

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});

function toggleCheckbox(checkboxId) {
    var checkbox = document.getElementById(checkboxId);
    var checkDiv = checkbox.nextElementSibling;
    
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        checkDiv.classList.add('checked');
        checkDiv.innerHTML = '✓';
    } else {
        checkDiv.classList.remove('checked');
        checkDiv.innerHTML = '';
    }
}

// Function to toggle activity text expansion
function toggleActivityText(element) {
    element.classList.toggle('expanded');
    
    // Adjust row height if needed
    var row = element.closest('tr');
    if (row) {
        row.style.height = 'auto';
    }
}

// Quick check all for today
function checkToday() {
    var today = <?= date('d', strtotime($today)) ?>;
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
    checkboxes.forEach(function(checkbox) {
        var name = checkbox.getAttribute('name');
        if (name && name.includes('[' + today + ']')) {
            if (!checkbox.checked) {
                checkbox.checked = true;
                var checkDiv = checkbox.nextElementSibling;
                checkDiv.classList.add('checked');
                checkDiv.innerHTML = '✓';
            }
        }
    });
    
    // Show notification
    alert('Semua aktivitas hari ini telah ditandai!');
}
</script>