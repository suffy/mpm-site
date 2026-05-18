<style>
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
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-content">
    <div class="spinner"></div>
    <div class="loading-text">Memproses Data</div>
    <div class="loading-subtext">Mohon tunggu sebentar...</div>
  </div>
</div>

<div class="container-fluid mb-5">
  <div class="col-md-12">
      <!-- form -->
    <div class="row mt-3">
      <div class="card">
        <div class="card-body">
          <?= form_open_multipart($url,  ['method' => 'post', 'id' => 'approvalForm'])?> 
            <div class="row mt-3">
                <input type="text" name="signature" value="<?= $signature;?>" hidden>
                <div class="col-md" id="divform1">
                    <div class="row mt-1" id="divform_status">
                        <div class="col-md-2">
                            <label for="status">Status</label> 
                        </div>
                        <div class="col-md-4">
                            <select name="action" id="action" class="form-select" required>
                                <option value="">- Pilih Status -</option>
                                <option value="1">Approve</option>
                                <option value="0">Reject</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1" id="divform_keterangan">
                        <div class="col-md-2">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="col-md-4">
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="7" placeholder="Masukan Keterangan" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <!-- cek can_approve -->
                      <?php 
                        if($can_approve){ ?>
                            <?php 
                                if($status == 4){ ?>
                                    <label class="form-label" style="color: black; border: 1px solid black; padding: 5px;" ><?= $nama_status; ?></label>
                                <?php
                                }else{ ?>
                                    <button type="submit" class="btn btn-submit-red" id="submitBtn">Submit Data</button>
                                <?php
                                }
                            ?>
                            
                        <?php
                        }else{ ?>
                            <label class="form-label" style="color: #FF8B5A; border: 2px solid #FF5A5A; padding: 2px 15px;border-radius: 5px" >menunggu response dari : <?= $username_on_duty; ?></label>
                        <?php
                        }?>
                </div>
            </div>
          <?= form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Script untuk menampilkan loading saat submit
  document.getElementById('approvalForm').addEventListener('submit', function(e) {
    // Validasi form
    if (this.checkValidity()) {
      // Tampilkan loading overlay
      document.getElementById('loadingOverlay').style.display = 'flex';
      
      // Disable tombol submit untuk mencegah double submit
      const submitBtn = document.getElementById('submitBtn');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';
      }
    }
  });
</script>

<script>
  // Sembunyikan loading jika ada flashdata (setelah halaman direfresh)
  window.addEventListener('load', function() {
    // Cek apakah ada flashdata (indikasi form sudah diproses)
    <?php if($this->session->flashdata('pesan') || $this->session->flashdata('pesan_success')): ?>
      document.getElementById('loadingOverlay').style.display = 'none';
      const submitBtn = document.getElementById('submitBtn');
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Data';
      }
    <?php endif; ?>
  });
</script>