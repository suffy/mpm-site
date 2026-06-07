<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #e74c3c;
            --text-color: #2c3e50;
            --light-bg: #f8f9fa;
            --border-radius: 8px;
        }
        
        body {
            background-color: #f5f5f5;
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 2rem;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 1rem auto;
            padding: 0 15px;
        }
        
        .card {
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
            background-color: white;
            border: none;
        }
        
        @media (max-width: 576px) {
            .card {
                padding: 1rem;
            }
        }
        
        .card-title {
            color: var(--text-color);
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }
        
        .card-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .instruction-box {
            background-color: var(--light-bg);
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            margin-bottom: 2rem;
        }
        
        .instruction-box ol {
            margin-bottom: 0;
            padding-left: 1.5rem;
        }
        
        @media (max-width: 576px) {
            .instruction-box {
                padding: 0.75rem;
            }
            
            .instruction-box ol {
                padding-left: 1rem;
            }
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            border-color: var(--primary-color);
        }
        
        .btn-submit {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            width: auto;
        }
        
        @media (max-width: 576px) {
            .btn-submit, .status-closing, .status-false {
                width: 100%;
                margin-top: 0.5rem;
            }
        }
        
        .btn-submit:hover:not(.loading) {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-submit.loading {
            background-color: #c0392b;
            cursor: not-allowed;
            padding-left: 3.5rem;
        }
        
        .btn-submit .spinner {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }
        
        .btn-submit.loading .spinner {
            display: inline-block;
        }
        
        .alert {
            border-radius: var(--border-radius);
            border: none;
            padding: 1rem;
        }
        
        .file-upload-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .file-upload-label {
            display: block;
            background-color: var(--light-bg);
            border: 1px dashed #ccc;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            background-color: #e9ecef;
        }
        
        @media (max-width: 576px) {
            .file-upload-label {
                padding: 1rem;
            }
        }
        
        .file-upload-info {
            display: none;
            margin-top: 0.5rem;
            word-break: break-word;
        }
        
        .download-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            word-break: break-word;
        }
        
        .download-link:hover {
            text-decoration: underline;
        }
        
        .download-link i {
            margin-right: 0.5rem;
        }
        
        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
        }
        
        .loading-overlay.active {
            visibility: visible;
            opacity: 1;
        }
        
        .loading-content {
            background-color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 90%;
            width: 300px;
        }
        
        @media (max-width: 576px) {
            .loading-content {
                padding: 1.5rem;
                max-width: 90%;
            }
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            margin: 0 auto 1rem;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--secondary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-group-row {
                margin-bottom: 1rem;
            }
            
            .form-label-col {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h4>Memproses Data</h4>
            <p>Mohon tunggu sebentar...</p>
        </div>
    </div>

    <div class="container main-container">
        <!-- First Form Card -->
        <div class="card">
            <h5 class="card-title"><?= $title ?></h5>
            
            <div class="instruction-box">
                <div class="row">
                    <div class="col-12">
                        <ol>
                            <li>Pastikan selalu mengikuti template yang sudah di sepakati. 
                                <a href="<?= base_url().'bridging/download_template_banjarmasin' ?>" class="download-link">
                                    <i class="fas fa-file-excel"></i> Download template excel di sini
                                </a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <?php if($this->session->flashdata('pesan') || $this->session->flashdata('pesan_success')): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <?php if($this->session->flashdata('pesan')): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= $this->session->flashdata('pesan'); ?>
                            </div>
                        <?php elseif($this->session->flashdata('pesan_success')): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= $this->session->flashdata('pesan_success'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo form_open_multipart($url, ['class' => 'upload-form', 'id' => 'uploadForm1']); ?>
                <div class="row form-group-row mb-4">
                    <div class="col-md-3 form-label-col">
                        <label for="month" class="form-label">Bulan</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="month" class="form-control" id="month" name="month" value="<?= date('Y-m') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row form-group-row mb-4">
                    <div class="col-md-3 form-label-col">
                        <label for="file" class="form-label">File Excel Raw</label>
                    </div>
                    <div class="col-md-9">
                        <div class="file-upload-wrapper">
                            <input type="file" class="form-control file-input" id="file" name="file" accept=".xlsx,.xls" required style="display: none;">
                            <label for="file" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt mb-2" style="font-size: 2rem; color: var(--primary-color);"></i>
                                <div>Klik atau drag file di sini</div>
                                <div class="text-muted small">Format file: .xls, .xlsx</div>
                            </label>
                            <div class="file-upload-info alert alert-info">
                                <i class="fas fa-file-excel me-2"></i>
                                <span class="file-name">No file selected</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group-row">
                    <div class="col-md-3 d-none d-md-block"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-submit" name="submit">
                            <div class="spinner-border spinner-border-sm text-light spinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <i class="fas fa-cogs me-2"></i>Process Data
                        </button>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>

        <!-- Second Form Card - Customer Data -->
        <div class="card">
            <h5 class="card-title"><?= $title_customer ?></h5>
            
            <div class="instruction-box">
                <div class="row">
                    <div class="col-12">
                        <ol>
                            <li>Pastikan selalu mengikuti template yang sudah di sepakati. 
                                <a href="<?= base_url().'bridging/download_template_tarakan_customer' ?>" class="download-link">
                                    <i class="fas fa-file-excel"></i> Download template excel customer di sini
                                </a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <?php if($this->session->flashdata('pesan_customer') || $this->session->flashdata('pesan_success_customer')): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <?php if($this->session->flashdata('pesan_customer')): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= $this->session->flashdata('pesan_customer'); ?>
                            </div>
                        <?php elseif($this->session->flashdata('pesan_success_customer')): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= $this->session->flashdata('pesan_success_customer'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo form_open_multipart($url_customer, ['class' => 'upload-form', 'id' => 'uploadForm2']); ?>
                <div class="row form-group-row mb-4">
                    <div class="col-md-3 form-label-col">
                        <label for="file_customer" class="form-label">File Excel Raw Customer</label>
                    </div>
                    <div class="col-md-9">
                        <div class="file-upload-wrapper">
                            <input type="file" class="form-control file-input" id="file_customer" name="file_customer" accept=".xlsx,.xls" required style="display: none;">
                            <label for="file_customer" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt mb-2" style="font-size: 2rem; color: var(--primary-color);"></i>
                                <div>Klik atau drag file di sini</div>
                                <div class="text-muted small">Format file: .xls, .xlsx</div>
                            </label>
                            <div class="file-upload-info alert alert-info">
                                <i class="fas fa-file-excel me-2"></i>
                                <span class="file-name">No file selected</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group-row">
                    <div class="col-md-3 d-none d-md-block"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-submit" name="submit">
                            <div class="spinner-border spinner-border-sm text-light spinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <i class="fas fa-cogs me-2"></i>Process Data Customer
                        </button>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class="container main-container">
        <!-- First Form Card -->
        <div class="card">
            <?php if($this->session->flashdata('pesan') || $this->session->flashdata('pesan_result_success')): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <?php if($this->session->flashdata('pesan_result')): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= $this->session->flashdata('pesan_result'); ?>
                            </div>
                        <?php elseif($this->session->flashdata('pesan_result_success')): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= $this->session->flashdata('pesan_result_success'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabel-ajuan-claim" class="tabel-ajuan-claim table-striped table-hover" style="width:100%">
                        <!-- <table id="tabel-ajuan-claim"> -->
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No</th>       
                                    <th class="text-center">Closing</th> 
                                    <th class="text-center">SiteCode</th>       
                                    <th class="text-center">Bulan</th>       
                                    <th class="text-center">Filename</th>       
                                    <th class="text-center">Sum Omzet</th>       
                                    <th class="text-center">Sum Unit</th>       
                                    <th class="text-center">CreatedAt</th>       
                                </tr>
                            </thead>
                            <tbody>  
                                <?php 
                                $no = 1;
                                foreach ($get_bridging_log->result() as $a) : 
                                ?>  
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td> 
                                        <td>                  
                                            <a href="<?= base_url().'bridging/update_status/'.$a->signature.'/'.$bridging ?>" ><?= ($a->status_closing == 1) ? '<span class="btn-status status-closing">Closing</span>' : '<span class="btn-status status-false">False</span>' ?></a>
                                        </td>
                                        <td class="text-center"><?= $a->site_code ?></td> 
                                        <td class="text-center"><?= $a->bulan ?></td> 
                                        <td class="text-center"><?= $a->filename ?></td> 
                                        <td class="text-center"><?= number_format($a->sum_omzet) ?></td> 
                                        <td class="text-center"><?= $a->sum_unit ?></td> 
                                        <td class="text-center"><?= $a->created_at ?></td> 
                                    </tr>
                                <?php endforeach; ?> 
                            </tbody>
                        </table>
                    </div>
                </div>

            
        </div>


    </div>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script>
        $(document).ready(function() {
            // Fix for label click on mobile
            $('.file-upload-label').on('click', function(e) {
                if ($(window).width() <= 768) {
                    e.preventDefault();
                    $(this).siblings('.file-input').click();
                }
            });
            
            // File upload interaction
            $('.file-input').change(function() {
                var fileName = $(this).val().split('\\').pop();
                var fileInfoDiv = $(this).closest('.file-upload-wrapper').find('.file-upload-info');
                var fileNameSpan = fileInfoDiv.find('.file-name');
                var fileLabel = $(this).closest('.file-upload-wrapper').find('.file-upload-label');
                
                if (fileName) {
                    fileNameSpan.text(fileName);
                    fileInfoDiv.show();
                    fileLabel.css('border-color', 'var(--primary-color)');
                } else {
                    fileInfoDiv.hide();
                    fileLabel.css('border-color', '#ccc');
                }
            });
            
            // Drag and drop functionality - disable on mobile
            if (window.matchMedia('(min-width: 768px)').matches) {
                $('.file-upload-label').on('dragover', function(e) {
                    e.preventDefault();
                    $(this).css('background-color', '#e9ecef');
                });
                
                $('.file-upload-label').on('dragleave', function(e) {
                    e.preventDefault();
                    $(this).css('background-color', 'var(--light-bg)');
                });
                
                $('.file-upload-label').on('drop', function(e) {
                    e.preventDefault();
                    $(this).css('background-color', 'var(--light-bg)');
                    
                    // Find the associated file input
                    var fileInput = $(this).siblings('.file-input');
                    fileInput[0].files = e.originalEvent.dataTransfer.files;
                    fileInput.trigger('change');
                });
            }
            
            // Form submission with loading state
            $('.upload-form').on('submit', function(e) {
                // Check if form is valid (HTML5 validation)
                if (this.checkValidity()) {
                    // Show loading state
                    var submitBtn = $(this).find('.btn-submit');
                    submitBtn.addClass('loading');
                    submitBtn.prop('disabled', true);
                    
                    // Show loading overlay
                    $('#loadingOverlay').addClass('active');
                    
                    // You could add a timeout to ensure the loading state is shown
                    // even if the form submits very quickly
                    setTimeout(function() {
                        // Form will submit naturally
                    }, 300);
                }
            });
            
            // Handle window resize
            $(window).resize(function() {
                // Adjust any responsive behaviors if needed
                if (window.matchMedia('(max-width: 768px)').matches) {
                    // Mobile view adjustments if needed
                } else {
                    // Desktop view adjustments if needed
                }
            });
        });
        
    </script>

    <script>
        $(document).ready(function () {
            $("#btnBack").show();
            $("#btnLoading").hide();
            $('#tabel-ajuan-claim').DataTable({
                "pageLength": 10,
                "ordering": true,
                "order": [6, 'desc'],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                scrollX: true,
            });
        });

        $.ajax({ 
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_kategori') ?>',
            success: function(result) {
                $("select[name = kategori]").html(result);
            }
        });
    </script>

</body>
</html>