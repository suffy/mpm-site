<style>
  .card{
    border-radius: 10px !important;
    border-width: 3px !important;
    border-color: var(--bs-light-border-subtle) !important;
  }
  
  /* Style untuk loading overlay */
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }
  
  .loading-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    max-width: 300px;
  }
  
  .spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #dc3545;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  .loading-text {
    color: #333;
    font-size: 16px;
    margin-bottom: 10px;
  }
  
  .loading-subtext {
    color: #666;
    font-size: 14px;
  }

  .select2-container--default .select2-selection--single {
      height: 38px;
      padding: 5px;
      border-radius: 0.25rem;
      background-color: var(--bs-body-bg);
      border: 2px solid var(--bs-light-bg-subtle);
      color: var(--bs-body-color);
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 36px;
      background-color: var(--bs-body-bg);
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: var(--bs-body-color);
  }
  
  .select2-container--default .select2-dropdown {
      background-color: var(--bs-body-bg);
      border: 2px solid var(--bs-light-bg-subtle);
  }

</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-content">
    <div class="spinner"></div>
    <div class="loading-text">Memproses Pengajuan Claim</div>
    <div class="loading-subtext">Mohon tunggu sebentar...</div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 mt-2 mb-2">
      <h4><?= $title ?></h4> 
    </div>
  </div>

  <div class="row mt-2">
    <div class="col-md-12 text-center">
      <?php 
        if($this->session->flashdata('pesan')){ ?>
          <div class="alert alert-danger" role="alert">
            <?= $this->session->flashdata('pesan'); ?>
          </div>
        <?php
        }elseif($this->session->flashdata('pesan_success')){ ?>
          <div class="alert alert-success" role="alert">
            <?= $this->session->flashdata('pesan_success'); ?>
          </div>
        <?php
        }
      ?>
    </div>
  </div>

  <button onclick="toggleKonten()" class="btn btn-submit-black" id="button_form">Form Ajuan</button>

  <div class="card show" id="form" style="display: block !important;">
    <?= form_open_multipart($url, ['method' => 'post', 'id' => 'claimForm'])?> 
      <div class="row mb-4">
        <div class="col-md-12">
          <h5><?= $title ?></h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6" id="divform1">
          <div class="row mt-3">
              <div class="col-lg-4">
                  <label for="site_code">Subbranch</label> 
              </div>
              <div class="col-lg-8">
                  <select name="site_code" id="site_code" class="form-select" required>
                  <?php 
                      foreach ($get_site_code->result() as $key) {
                          echo '<option value="'.$key->site_code.'">'.$key->nama_comp.' ('.$key->site_code.')</option>';
                      }
                  ?>
                  </select>
              </div>
          </div>
          <div class="row mt-1" id="divform_no_klaim">
              <div class="col-lg-4">
                  <label for="no_klaim">Channel</label> 
              </div>
              <div class="col-lg-8">
                  <select name="channel" id="channel" class="form-select">
                      <option value="">-- Pilih Channel --</option>
                      <option value="nka">NKA</option>
                      <option value="nka_herbana">D3 (Herbana)</option>
                      <option value="pharma">PHARMA</option>
                  </select>
              </div>
          </div>

          <div class="row mt-1" id="divform_kategori">
              <div class="col-lg-4">
                  <label for="kategori">Kategori</label>
              </div>
              <div class="col-lg-8">
                  <Select class="form-select" name="kategori" id="kategori" required>
                      <option value=""> -- Pilih Kategori -- </option>
                  </Select>
              </div>
          </div>

          <!-- PERBAIKAN 1: Tambahkan style display: none; agar tersembunyi saat awal -->
          <div class="row mt-1" id="divform_key_account" style="display: none;">
              <div class="col-lg-4">
                  <label for="key_account">Key Account</label>
              </div>
              <div class="col-lg-8">
                  <select class="form-select" name="key_account" id="key_account" required>
                      <option value=""> -- Pilih Key Account -- </option>
                  </select>
              </div>
          </div>
            
          <div class="row mt-1" id="divform_no_klaim">
              <div class="col-lg-4">
                  <label for="no_klaim">Nomor Klaim</label> 
              </div>
              <div class="col-lg-8">
                  <input type="text" class="form-control" name="no_klaim" id="no_klaim" placeholder="Masukan Nomor Klaim" required>
              </div>
          </div>

          <div class="row mt-1" id="divform_no_invoice">
              <div class="col-lg-4">
                  <label for="no_invoice">Nomor Invoice/SKP/Trading Term</label>
              </div>
              <div class="col-lg-8">
                  <input type="text" class="form-control" name="no_invoice" id="no_invoice" placeholder="Masukan No Invoice / SKP / Trading Term" required>
              </div>
          </div>

          <div class="row mt-1" id="divform_periode">
              <div class="col-lg-4">
                  <label for="from">Periode</label> 
              </div>
              <div class="col-lg-8">
                  <div class="input-group">
                      <input type="date" name="from" id="from" min="2026-02-01" class="form-control" required>
                      <input type="date" name="to" id="to" min="2026-02-01" class="form-control" required>
                  </div>
              </div>
          </div>

          <div class="row mt-1" id="divform_keterangan">
              <div class="col-lg-4">
                  <label for="keterangan">Keterangan</label>
              </div>
              <div class="col-lg-8">
                  <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukan Keterangan Klaim" required></textarea>
              </div>
          </div>

          <div class="row mt-1" id="divform_nominal_dpp">
              <div class="col-lg-4">
                  <label for="nominal_dpp">Nominal Claim</label>
              </div>
              <div class="col-lg-8">
                  <input type="number" class="form-control" name="nominal_dpp" id="nominal_dpp" placeholder="Masukan Nominal Claim" required>
              </div>
          </div>

          <div class="row mt-1" id="divform_nama">
              <div class="col-lg-4">
                  <label for="nama">Nama</label>
              </div>
              <div class="col-lg-8">
                  <input type="Text" class="form-control" name="nama" id="nama" placeholder="Masukan Nama" required>
              </div>
          </div>

          <div class="row mt-1" id="divform_email">
              <div class="col-lg-4">
                  <label for="email">Email</label>
              </div>
              <div class="col-lg-8">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Masukan Email" required>
              </div>
          </div>
        </div>
      </div>

      <div class="row mt-3" style="text-align: center;">
        <div class="col-md-12">
          <?= form_submit('submit', 'Submit Pengajuan Claim', 'class="btn btn-primary" id="submitBtn"'); ?>
        </div>
      </div>
      <?= form_close(); ?>
  </div>

<script>
  // PERBAIKAN 2: Modifikasi event change pada channel
  $("select[name=channel]").on("change", function() 
  {    
    let channel = document.getElementById('channel').value;
    let divKeyAccount = document.getElementById('divform_key_account');
    
    // Tampilkan atau sembunyikan divform_key_account berdasarkan nilai channel
    if (channel == "nka" || channel == "nka_herbana") {
      divKeyAccount.style.display = "flex"; // atau "block" tergantung layout, "flex" karena row menggunakan flex
    } else {
      divKeyAccount.style.display = "none";
    }

    // jika channel pharma, maka hilangkan required di keyaccount
    if(channel == "pharma"){
      document.getElementById("key_account").removeAttribute("required");
    }
      
    $.ajax({
      type: 'POST',
      url: '<?php echo base_url('management_claim/master_kategori_nka') ?>',
      data: {
        'channel': channel,     
      },
      success: function(result) {
        $("select[name = kategori]").html(result);
      }
    });

    $.ajax({
      type: 'POST',
      url: '<?php echo base_url('management_claim/master_key_account') ?>',
      data: {
        'channel': channel,     
      },
      success: function(result) {        
        $("select[name = key_account]").html(result);
      }
    });
  });

  // PERBAIKAN 3: Tambahkan event saat halaman selesai dimuat
  // untuk mengecek apakah channel sudah memiliki nilai (misalnya karena validasi gagal)
  $(document).ready(function() {
    let channelValue = document.getElementById('channel').value;
    let divKeyAccount = document.getElementById('divform_key_account');
    
    if (channelValue !== "") {
      divKeyAccount.style.display = "flex";
    } else {
      divKeyAccount.style.display = "none";
    }
  });
</script>

<script>
  function toggleKonten() {
    const form = document.getElementById('form');
    const tombol_form = document.getElementById('button_form');
    form.classList.toggle('show');
    if (form.classList.contains('show')) {
        tombol_form.textContent = 'Close Form';
    } else {
        tombol_form.textContent = 'Form Ajuan';
    }
  }
  
  // Script untuk menampilkan loading saat submit
  document.getElementById('claimForm').addEventListener('submit', function(e) {
    // Validasi form
    if (this.checkValidity()) {
      // Tampilkan loading overlay
      document.getElementById('loadingOverlay').style.display = 'flex';
      
      // Disable tombol submit untuk mencegah double submit
      document.getElementById('submitBtn').disabled = true;
      
      // Optional: Ubah teks tombol
      document.getElementById('submitBtn').value = 'Memproses...';
    }
  });
</script>

<script>
  // Sembunyikan loading jika ada flashdata (setelah halaman direfresh)
  window.addEventListener('load', function() {
    // Cek apakah ada flashdata (indikasi form sudah diproses)
    <?php if($this->session->flashdata('pesan') || $this->session->flashdata('pesan_success')): ?>
      document.getElementById('loadingOverlay').style.display = 'none';
      document.getElementById('submitBtn').disabled = false;
      document.getElementById('submitBtn').value = 'Submit Pengajuan Claim';
    <?php endif; ?>
  });
</script>
<script src="<?= base_url('assets/js/form_image_retur_nka.js') ?>"></script>