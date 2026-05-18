
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
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

            <!-- Summary Section -->
            <div class="card mb-4 border-0 bg-light">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-muted">Summary Information</h6>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="count_row" class="col-sm-5 col-form-label">Count Row</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="count_row" name="count_row" 
                                           value="<?= $get_summary->row()->count ?>" readonly>
                                </div>
                            </div>
                        </div>                        
                        
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="invalid_kodeprod" class="col-sm-5 col-form-label fw-bold text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Invalid Type ID
                                </label>
                                <div class="col-sm-7">
                                    <div class="position-relative">
                                        <input type="text" class="form-control bg-danger-subtle border-danger text-danger" 
                                               id="invalid_kodeprod" name="invalid_kodeprod" 
                                               value="<?= number_format($get_summary->row()->invalid_type_id, 0, ',', '.') ?>" readonly>
                                        <?php if($get_summary->row()->invalid_type_id > 0): ?>
                                            <div class="position-absolute top-0 end-0 mt-1 me-2">
                                                <span class="badge bg-danger rounded-pill">Attention needed</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="invalid_kodeprod" class="col-sm-5 col-form-label fw-bold text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Invalid Class ID
                                </label>
                                <div class="col-sm-7">
                                    <div class="position-relative">
                                        <input type="text" class="form-control bg-danger-subtle border-danger text-danger" 
                                               id="invalid_kodeprod" name="invalid_kodeprod" 
                                               value="<?= number_format($get_summary->row()->invalid_class_id, 0, ',', '.') ?>" readonly>
                                        <?php if($get_summary->row()->invalid_class_id > 0): ?>
                                            <div class="position-absolute top-0 end-0 mt-1 me-2">
                                                <span class="badge bg-danger rounded-pill">Attention needed</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="card">
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabel" class="table table-striped table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No</th>       
                                    <th class="text-center">Customer_id</th>
                                    <th class="text-center">Mapping_uli</th>
                                    <th class="text-center">Nama_customer</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Type_id</th>
                                    <th class="text-center">Nama_type</th>
                                    <th class="text-center">Class_id</th>
                                    <th class="text-center">Nama_class</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <?php 
                                $no = 1;
                                foreach ($get_data->result() as $a) : 
                                    $is_valid = isset($a->is_valid_kodeprod) ? $a->is_valid_kodeprod : 1; // Default to valid if not set
                                ?>  
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td> 
                                        <td><?= $a->customer_id ?></td>
                                        <td><?= $a->mapping_uli ?></td>
                                        <td><?= $a->nama_customer ?></td>
                                        <td><?= $a->alamat ?></td>
                                        <td>
                                            <?= $a->type_id ?>
                                            <?php if($a->is_valid_type_id == 0): ?>
                                                <i class="fas fa-exclamation-circle ms-1 text-danger"></i>
                                                <span class="badge bg-danger">Invalid</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $a->nama_type ?></td>
                                        <td>
                                            <?= $a->class_id ?>
                                            <?php if($a->is_valid_class_id == 0): ?>
                                                <i class="fas fa-exclamation-circle ms-1 text-danger"></i>
                                                <span class="badge bg-danger">Invalid</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $a->class ?></td>
                                    </tr>
                                <?php endforeach; ?> 
                            </tbody>
                        </table>
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
                <button type="button" id="btnLanjutkanLoading" class="btn btn-primary" disabled style="display:none;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Toggle button visibility
        $("#btnBack").show();
        $("#btnLoading").hide();
        $("#btnLanjutkanLoading").hide();
        
        // Highlight the invalid kodeprod if greater than 0
        if (parseInt($("#invalid_kodeprod").val().replace(/\./g, '')) > 0) {
            $("#invalid_kodeprod").parent().parent().addClass('animate__animated animate__pulse animate__repeat-3');
        }
        
        // Initialize DataTable with improved options and custom rendering
        var table = $('#tabel').DataTable({
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            pageLength: 10,
            ordering: true,
            order: [0, 'desc'],
            lengthMenu: [
                [10, 25, 50, 100, 200, -1],
                [10, 25, 50, 100, 200, "All"]
            ],
            responsive: true,
            scrollX: true,
        });
        
        // Add a custom filter for valid/invalid status
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var status = $('#status-filter').val();
                var rowStatus = $(data[7]).text().trim();
                
                if (status === 'all') {
                    return true;
                } else if (status === 'invalid' && rowStatus === 'Invalid') {
                    return true;
                } else if (status === 'valid' && rowStatus === 'Valid') {
                    return true;
                }
                return false;
            }
        );
        
       
        
        // Back button click handler
        $("#btnBack").on("click", function() {
            $(this).hide();
            $("#btnLoading").show();
            window.history.back();
        });
        
        // Form submit handler (for the Lanjutkan button)
        $("form").on("submit", function() {
            $("#btnLanjutkan").hide();
            $("#btnLanjutkanLoading").show();
            
            // If form validation is needed
            if(parseInt($("#invalid_kodeprod").val().replace(/\./g, '')) > 0) {
                // Show confirmation for continuing with invalid codes
                if(!confirm("Ada kode produk yang tidak valid. Apakah Anda yakin ingin melanjutkan?")) {
                    $("#btnLanjutkan").show();
                    $("#btnLanjutkanLoading").hide();
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
    
    /* Make the filter controls look better */
    #status-filter {
        min-width: 150px;
    }
</style>