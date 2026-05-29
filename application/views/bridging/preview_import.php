<style>
    /* Full page loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loading-content {
        text-align: center;
    }
    
    .group-header {
        cursor: pointer;
        font-weight: bold;
        /* margin-top: 20px; */
        /* background: #eee; */
        /* padding: 5px 10px; */
        user-select: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .icon-play {
        width: 0; 
        height: 0; 
        border-left: 10px solid #6c757d; 
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        transition: transform 0.2s ease;
    }
    .icon-play.rotate {
        transform: rotate(90deg);
    }

    /* Ratakan kolom angka */
    .text-right {
        text-align: right;
        width: 120px;   /* sesuaikan ukuran supaya semua sama */
        white-space: nowrap; /* supaya angka tidak turun ke bawah */
    }

    /* Hilangkan semua border tabel */
    .table-clean {
        /* table-layout: fixed; */
        /* background-color: var(--bs-body-bg); */
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .table-clean thead {
        background-color: #f5f5f5;
        /* background-color: var(--bs-body-bg); */
    }

    /* Sedikit jarak antar baris */
    .table-clean tbody tr {
        border-bottom: 8px solid transparent; /* jarak antar baris */
    }

    .table-clean th, 
    .table-clean td {
        padding: 8px 10px;
        text-align: left;
        border: none;
        color: black;
    }

    .table-clean tbody tr:nth-child(even) {
        background-color: #f9f9f9; /* warna abu muda untuk striping */
    }

    .table-clean tbody tr:nth-child(odd) {
        background-color: #fff; /* putih untuk baris ganjil */
    }
    .card{
        border: 1px solid #e0e0e0;
    }

</style>

<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0"><?= $title ?></h5>
        </div>
        
        <div class="card-body">
            <?php echo form_open_multipart($url); ?>

            <!-- Flash Messages -->
            <?php if($this->session->flashdata('pesan')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif($this->session->flashdata('pesan_success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Data Table Section -->
            <?php
                foreach ($data_by_supp as $supp_code => $items):
                    $supplier_name = isset($supplier_names[$supp_code]) ? $supplier_names[$supp_code] : "What Supplier ? $supp_code";
                    $total = isset($totals[$supp_code]) ? $totals[$supp_code] : ['unit_kecil' => 0, 'unit_karton' => 0];
                ?>
                <div class="card mb-3 ">
                    <div class="group-header" onclick="toggleGroup(this)">
                        <div class="icon-play"></div>
                        <?= htmlspecialchars($supplier_name) ?>
                    </div>
                    <table class="table-clean" style="display:table;">
                        <thead>
                            <tr>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th class="text-right">Unit Kecil</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Unit Karton</th>
                                <th>Satuan Besar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $a): ?>
                            <tr>
                                <td>
                                    <?= $a->kodeprod ?>
                                    <?php if($a->is_valid_kodeprod == 0): ?>
                                        <i class="fas fa-exclamation-circle ms-1 text-danger"></i>
                                        <span class="badge bg-danger">Invalid</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $a->namaprod ?></td>
                                <td class="text-right"><?= $a->qty_kecil ?></td>
                                <td class="text-right"><?= number_format($a->harga, 2)?></td>
                                <td class="text-right"><?= $a->qty_besar ?></td>
                                <td><?= $a->satuan_besar ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total Unit</th>
                                <th class="text-right"><?= $total['unit_kecil'] ?></th>
                                <th class="text-right"></th>
                                <th class="text-right"><?= $total['unit_karton'] ?></th>
                                <th class="text-right"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endforeach; ?>

            <!-- Summary Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-muted">Summary Information</h6>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="count_row" class="col-sm-5 col-form-label">Count Row</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control bg-success-subtle border-success" id="count_row" name="count_row" 
                                        value="<?= $get_summary->row()->count ?>" readonly>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="count_row" class="col-sm-5 col-form-label">Total Omzet Stock</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control bg-success-subtle border-success" id="count_row" name="count_row" 
                                        value="<?=number_format($total_value->row()->total_value, 2) ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="invalid_kodeprod" class="col-sm-5 col-form-label fw-bold text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Invalid Productid
                                </label>
                                <div class="col-sm-7">
                                    <div class="position-relative">
                                        <input type="text" class="form-control bg-danger-subtle border-danger text-danger" 
                                            id="invalid_kodeprod" name="invalid_kodeprod" 
                                            value="<?= number_format($get_summary->row()->invalid_kodeprod, 0, ',', '.') ?>" readonly>
                                        <?php if($get_summary->row()->invalid_kodeprod > 0): ?>
                                            <div class="position-absolute top-0 end-0 mt-1 me-2">
                                                <span class="badge bg-danger rounded-pill">Attention needed</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="valid_kodeprod" class="col-sm-5 col-form-label">Valid Productid</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control bg-success-subtle border-success" id="valid_kodeprod" name="valid_kodeprod" 
                                        value="<?= number_format($get_summary->row()->valid_kodeprod, 0, ',', '.') ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="count_row" class="col-sm-5 col-form-label">Total Unit PCS</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control bg-success-subtle border-success" id="count_row" name="count_row" 
                                        value="<?= number_format($total_value_kecil,2) ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="button" id="btnBack" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </button>
                <button type="button" id="btnLoading" class="btn btn-secondary" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <input type="hidden" name="id_log" value="<?= $id_log ?>">
                
                <?php 
                    if($params_invalid ) { ?>
                        <p class="ms-auto">
                            tidak bisa melanjutkan karena ada data invalid
                        </p>
                    <?php
                    } else { ?>
                        <button type="submit" id="btnLanjutkan" class="btn btn-primary ms-auto">
                            Lanjutkan <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    <?php
                    }
                ?>

                <!-- <button type="submit" id="btnLanjutkan" class="btn btn-primary ms-auto">
                    Lanjutkan <i class="fas fa-arrow-right ms-1"></i>
                </button> -->

                <button type="button" id="btnLanjutkanLoading" class="btn btn-primary" disabled style="display:none;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Add this at the end of your HTML body, before closing body tag -->
<div id="fullPageLoading" class="loading-overlay" style="display:none;">
    <div class="loading-content">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h4 class="mt-3 text-light">Loading...</h4>
    </div>
</div>

<!-- Update your JavaScript section -->
<script>
    $(document).ready(function () {
        // Toggle button visibility
        $("#btnBack").show();
        $("#btnLoading").hide();
        $("#btnLanjutkanLoading").hide();

        // Back button click handler
        $("#btnBack").on("click", function() {
            $(this).hide();
            $("#btnLoading").show();
            window.history.back();
        });
        
        // Highlight the invalid kodeprod if greater than 0
        if (parseInt($("#invalid_kodeprod").val().replace(/\./g, '')) > 0) {
            $("#invalid_kodeprod").parent().parent().addClass('animate__animated animate__pulse animate__repeat-3');
        }
        
        // Form submit handler (for the Lanjutkan button)
        $("form").on("submit", function() {
            $("#btnLanjutkan").hide();
            $("#btnLanjutkanLoading").show();
            
            // Show full page loading overlay
            $("#fullPageLoading").fadeIn(300);
            
            // If form validation is needed
            if(parseInt($("#invalid_kodeprod").val().replace(/\./g, '')) > 0) {
                // Show confirmation for continuing with invalid codes
                if(!confirm("Ada kode produk yang tidak valid. Apakah Anda yakin ingin melanjutkan?")) {
                    $("#btnLanjutkan").show();
                    $("#btnLanjutkanLoading").hide();
                    // Hide loading overlay if canceled
                    $("#fullPageLoading").fadeOut(300);
                    return false;
                }
            }
            
            return true;
        });
    });
</script>

<!-- Add this in your head section for the animation effects -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    /* Add some animations and styling for invalid rows */
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.15) !important;
    }
    
    /* Enhanced hover effects for rows */
    #tabel tbody tr:hover td.bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.25) !important;
    }
</style>

<script>
function toggleGroup(header) {
    const icon = header.querySelector('.icon-play');
    const table = header.nextElementSibling;
    if(table.style.display === 'none') {
        table.style.display = 'table';
        icon.classList.add('rotate');
    } else {
        table.style.display = 'none';
        icon.classList.remove('rotate');
    }
}
</script>