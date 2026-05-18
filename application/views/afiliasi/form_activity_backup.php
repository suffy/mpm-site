<!-- application/views/afiliasi/form_activity.php -->
<style>
    /* Custom Styles untuk Form Activity */
    .card-title {
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .activity-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .activity-stats {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    
    .stat-card {
        flex: 1;
        min-width: 200px;
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-left: 4px solid #3498db;
    }
    
    .stat-card h6 {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }
    
    .stat-card .count {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
    }
    
    .table-container {
        position: relative;
        overflow: auto;
        max-height: 600px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .activity-table {
        min-width: 1200px;
        margin-bottom: 0;
    }
    
    .activity-table thead th {
        position: sticky;
        top: 0;
        background-color: var(--bs-dark-border-subtle);
        z-index: 10;
        font-weight: 600;
        font-size: 12px;
        padding: 8px 5px;
        text-align: center;
        vertical-align: middle;
    }
    
    .activity-table tbody td {
        padding: 5px;
        vertical-align: middle;
        font-size: 12px;
    }
    
    .activity-table .activity-name {
        min-width: 200px;
        max-width: 200px;
        word-wrap: break-word;
    }
    
    .activity-table .checkbox-cell {
        width: 40px;
        text-align: center;
        padding: 2px !important;
    }
    
    .day-header {
        font-size: 10px;
        font-weight: bold;
        color: #2c3e50;
        width: 35px;
    }
    
    /* Checkbox styling */
    .activity-checkbox {
        display: none;
    }
    
    .check-label {
        display: block;
        width: 28px;
        height: 28px;
        border: 2px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        margin: 0 auto;
        position: relative;
        transition: all 0.2s ease;
    }
    
    .check-label:hover {
        border-color: #3498db;
        transform: scale(1.1);
    }
    
    .activity-checkbox:checked + .check-label {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .activity-checkbox:checked + .check-label::after {
        content: '✓';
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 14px;
        font-weight: bold;
    }
    
    /* Tab styling */
    .activity-tabs {
        margin-bottom: 20px;
    }
    
    .activity-tabs .nav-link {
        color: #666;
        font-weight: 500;
        border: none;
        padding: 10px 20px;
        border-radius: 10px 10px 0 0;
        margin-right: 5px;
        background: #f8f9fa;
    }
    
    .activity-tabs .nav-link.active {
        background: white;
        color: #3498db;
        border-bottom: 3px solid #3498db;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    }
    
    .activity-tabs .nav-link:hover:not(.active) {
        background: #e9ecef;
    }
    
    /* Badge untuk tabel summary */
    .count-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        min-width: 40px;
        text-align: center;
    }
    
    .count-badge.month1 { background-color: #e3f2fd; color: #1976d2; }
    .count-badge.month2 { background-color: #f3e5f5; color: #7b1fa2; }
    .count-badge.month3 { background-color: #e8f5e8; color: #388e3c; }
    .count-badge.total { background-color: #fff3e0; color: #f57c00; }
    
    /* Alert untuk activity yang belum dilakukan */
    .alert-activity {
        border-left: 4px solid #dc3545;
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    
    .alert-activity h6 {
        color: #721c24;
        margin-bottom: 10px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .activity-table {
            min-width: 1000px;
        }
        
        .stat-card {
            min-width: 150px;
        }
        
        .activity-stats {
            flex-direction: column;
        }
    }
    
    /* Scrollbar styling */
    .table-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Today highlight */
    .today-cell {
        background-color: rgba(255, 193, 7, 0.2) !important;
        position: relative;
    }
    
    .today-cell::after {
        content: 'Today';
        position: absolute;
        top: -8px;
        right: 2px;
        font-size: 8px;
        color: #ff9800;
        font-weight: bold;
    }
    
    /* Frequency badges */
    .frequency-badge {
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .frequency-harian { background-color: #d4edda; color: #155724; }
    .frequency-mingguan { background-color: #cce5ff; color: #004085; }
    .frequency-bulanan { background-color: #fff3cd; color: #856404; }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="activity-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><?= $title ?></h4>
                <p class="mb-0">Monitoring Aktivitas Cabang - <?= date('F Y', strtotime($month . '-01')) ?></p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark fs-6">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <?= date('d F Y', strtotime($today)) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="activity-stats">
        <div class="stat-card">
            <h6><i class="fas fa-calendar-day me-2"></i>Hari Ini</h6>
            <div class="count"><?= date('d', strtotime($today)) ?></div>
            <small class="text-muted">Hari ke-<?= date('d', strtotime($today)) ?> dari <?= date('t', strtotime($month . '-01')) ?></small>
        </div>
        
        <div class="stat-card">
            <h6><i class="fas fa-clock me-2"></i>Aktivitas Tertunda</h6>
            <div class="count text-warning">
                <?= $harian->num_rows() + $mingguan->num_rows() + $bulanan->num_rows() ?>
            </div>
            <small class="text-muted">Total aktivitas yang belum dilakukan</small>
        </div>
        
        <div class="stat-card">
            <h6><i class="fas fa-tasks me-2"></i>Minggu Ini</h6>
            <div class="count"><?= $current_week ?></div>
            <small class="text-muted">Minggu ke-<?= $current_week ?> dalam tahun ini</small>
        </div>
        
        <div class="stat-card">
            <h6><i class="fas fa-filter me-2"></i>Bulan Filter</h6>
            <form action="<?= site_url('afiliasi'); ?>" method="GET" class="mt-2">
                <div class="input-group input-group-sm">
                    <input type="month" class="form-control" name="month" id="month" value="<?= $month ?>" required>
                    <button type="submit" class="btn btn-submit-black">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Form -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Form Aktivitas Harian</h5>
                <button type="button" class="btn btn-submit-green" onclick="checkAll()">
                    <i class="fas fa-check-double me-2"></i>Check All Today
                </button>
            </div>
            
            <form action="<?= site_url('afiliasi/save_activity'); ?>" method="POST">
                <input type="hidden" name="month" value="<?= $month ?>">
                
                <div class="table-container">
                    <table class="table table-bordered activity-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px">No</th>
                                <th class="activity-name">Activity</th>
                                <th class="text-center" style="width: 80px">Frekuensi</th>
                                <th class="text-center" style="width: 100px">Alat Kerja</th>
                                <?php 
                                    $today_day = date('d', strtotime($today));
                                    for ($i = 1; $i <= $total_hari; $i++): 
                                        $is_today = ($i == $today_day && $month == date('Y-m')) ? 'today-cell' : '';
                                ?>
                                <th class="text-center day-header <?= $is_today ?>" style="width: 35px">
                                    <?= $i; ?>
                                </th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($get_activity->result() as $key):
                                    $frequency_class = 'frequency-' . $key->frekuensi;
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="activity-name">
                                    <strong><?= $key->nama_activity; ?></strong>
                                </td>
                                <td class="text-center">
                                    <span class="frequency-badge <?= $frequency_class ?>">
                                        <?= ucfirst($key->frekuensi) ?>
                                    </span>
                                </td>
                                <td class="text-center"><?= $key->alat_kerja; ?></td>
                                <?php for ($i = 1; $i <= $total_hari; $i++): 
                                    $day_column = 'day_' . $i;
                                    $is_checked = (isset($key->$day_column) && $key->$day_column == 1);
                                    $is_today = ($i == $today_day && $month == date('Y-m')) ? 'today-cell' : '';
                                ?>
                                <td class="checkbox-cell <?= $is_today ?>">
                                    <input type="checkbox" 
                                        class="activity-checkbox"
                                        name="activity[<?= $key->id; ?>][<?= $i; ?>]" 
                                        value="1"
                                        <?= $is_checked ? 'checked' : '' ?>
                                        id="check_<?= $key->id ?>_<?= $i ?>">
                                    <label for="check_<?= $key->id ?>_<?= $i ?>" class="check-label"></label>
                                </td>
                                <?php endfor; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Klik pada kotak untuk menandai aktivitas yang sudah dilakukan
                        </small>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-submit-red px-4">
                            <i class="fas fa-save me-2"></i>Update Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Tabs -->
    <div class="activity-tabs">
        <ul class="nav nav-tabs" id="activityTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                    <i class="fas fa-clock me-2"></i>Aktivitas Tertunda
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary" type="button" role="tab">
                    <i class="fas fa-chart-bar me-2"></i>Summary Aktivitas
                </button>
            </li>
        </ul>
        
        <div class="tab-content p-3 border border-top-0 rounded-bottom" style="background: white;">
            <!-- Pending Activities Tab -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                <div class="row">
                    <!-- Harian -->
                    <?php if($harian->num_rows() > 0): ?>
                    <div class="col-md-4 mb-3">
                        <div class="alert-activity">
                            <h6>
                                <i class="fas fa-calendar-day me-2"></i>
                                Harian - <?= $today ?>
                                <span class="badge bg-danger float-end"><?= $harian->num_rows() ?></span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <?php foreach ($harian->result() as $key): ?>
                                        <tr>
                                            <td style="width: 20px">•</td>
                                            <td><?= $key->nama_activity ?></td>
                                            <td class="text-end">
                                                <small class="text-muted"><?= $key->alat_kerja ?></small>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Mingguan -->
                    <?php if($mingguan->num_rows() > 0): ?>
                    <div class="col-md-4 mb-3">
                        <div class="alert-activity">
                            <h6>
                                <i class="fas fa-calendar-week me-2"></i>
                                Mingguan - Week <?= $current_week ?>
                                <span class="badge bg-warning float-end"><?= $mingguan->num_rows() ?></span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <?php foreach ($mingguan->result() as $key): ?>
                                        <tr>
                                            <td style="width: 20px">•</td>
                                            <td><?= $key->nama_activity ?></td>
                                            <td class="text-end">
                                                <small class="text-muted"><?= $key->alat_kerja ?></small>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Bulanan -->
                    <?php if($bulanan->num_rows() > 0): ?>
                    <div class="col-md-4 mb-3">
                        <div class="alert-activity">
                            <h6>
                                <i class="fas fa-calendar-alt me-2"></i>
                                Bulanan - <?= date('F Y', strtotime($month . '-01')) ?>
                                <span class="badge bg-info float-end"><?= $bulanan->num_rows() ?></span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <?php foreach ($bulanan->result() as $key): ?>
                                        <tr>
                                            <td style="width: 20px">•</td>
                                            <td><?= $key->nama_activity ?></td>
                                            <td class="text-end">
                                                <small class="text-muted"><?= $key->alat_kerja ?></small>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($harian->num_rows() == 0 && $mingguan->num_rows() == 0 && $bulanan->num_rows() == 0): ?>
                    <div class="col-12">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Semua aktivitas telah dilakukan! Tetap semangat!
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Summary Tab -->
            <div class="tab-pane fade" id="summary" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0"><?= $title_2 ?></h5>
                    <div>
                        <span class="badge bg-primary me-2"><?= $month1_label ?></span>
                        <span class="badge bg-success me-2"><?= $month2_label ?></span>
                        <span class="badge bg-info"><?= $month3_label ?></span>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabel-summary">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2" style="width: 50px">No</th>
                                <th rowspan="2">Username</th>
                                <th rowspan="2">Divisi | Jabatan</th>
                                <th rowspan="2">Activity</th>
                                <th rowspan="2">Alat Kerja</th>
                                <th rowspan="2">Frekuensi</th>
                                <th colspan="3" style="background-color: #f8f9fa">Count Activity per Month</th>
                                <th rowspan="2">Total</th>
                            </tr>
                            <tr class="text-center">
                                <th><?= $month1_label; ?></th>
                                <th><?= $month2_label; ?></th>
                                <th><?= $month3_label; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($get_report->num_rows() > 0): 
                                $no = 1; 
                                foreach ($get_report->result() as $key): 
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><strong><?= $key->username; ?></strong></td>
                                <td>
                                    <small class="text-muted"><?= $key->nama_divisi; ?></small><br>
                                    <?= $key->nama_jabatan; ?>
                                </td>
                                <td><?= $key->nama_activity; ?></td>
                                <td><?= $key->alat_kerja; ?></td>
                                <td>
                                    <span class="frequency-badge frequency-<?= strtolower($key->frekuensi) ?>">
                                        <?= $key->frekuensi ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge month1"><?= $key->count_month1; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge month2"><?= $key->count_month2; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge month3"><?= $key->count_month3; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge total"><?= $key->total_count; ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">No data available</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Initialize DataTables
    $('#tabel-summary').DataTable({
        "pageLength": 50,
        "ordering": true,
        "order": [[0, 'asc']],
        "scrollX": true,
        "lengthChange": true,
        "info": true,
        "paging": true,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Checkbox click handler
    $('.check-label').click(function() {
        var checkbox = $(this).prev('.activity-checkbox');
        checkbox.prop('checked', !checkbox.prop('checked'));
        $(this).toggleClass('checked', checkbox.prop('checked'));
    });

    // Highlight today's column on hover
    $('.today-cell').hover(
        function() {
            $(this).css('background-color', 'rgba(255, 193, 7, 0.3)');
        },
        function() {
            $(this).css('background-color', 'rgba(255, 193, 7, 0.2)');
        }
    );
});

function checkAll() {
    var today = <?= date('d', strtotime($today)) ?>;
    var currentMonth = '<?= date('Y-m') ?>';
    var selectedMonth = '<?= $month ?>';
    
    if (currentMonth !== selectedMonth) {
        alert('Hanya bisa check all untuk bulan berjalan!');
        return;
    }
    
    $('.activity-checkbox').each(function() {
        var name = $(this).attr('name');
        if (name && name.includes('[' + today + ']')) {
            $(this).prop('checked', true);
            $(this).next('.check-label').addClass('checked');
        }
    });
    
    // Show success message
    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
        '<i class="fas fa-check-circle me-2"></i>' +
        'Semua aktivitas hari ini telah ditandai!' +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>');
    
    $('.container-fluid').prepend(alertDiv);
    
    setTimeout(function() {
        alertDiv.alert('close');
    }, 3000);
}

// Tab functionality
$('#activityTab button').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
});

// Export function
function exportToExcel() {
    // Implement export functionality here
    alert('Export to Excel functionality will be implemented here.');
}
</script>