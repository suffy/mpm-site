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
