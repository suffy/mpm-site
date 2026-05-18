
<!-- <?php $this->load->view('management_claim/css/style') ?> -->

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


<!-- <div class="container-fluid"> -->
    
<?php echo form_open_multipart($url); ?>

<div class="card">
  <div class="card-body">
      <h5 class="card-title"><?= $title ?></h5>

      <div class="row mt-5">
          <div class="col-md-12 az-content-label text-center">
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

      <div class="row">
          <div class="col-md-2">
              <label for="tanggal_terima_barang">PIC MPM</label>
          </div>
          <div class="col-md-5 d-flex flex-row">
              <label for="username" readonly><?= $username ?></label>
          </div>
      </div>

      <div class="row">
          <div class="col-md-2">
              <label for="first_pic">First PIC</label>
          </div>
          <div class="col-md-5 d-flex flex-row">
              <input type="text" class="form-control" name="first_pic" value="<?= $pic ?>" readonly style="width: 100%; background-color: var(--bs-body-bg);">
          </div>
      </div>

      <div class="row mt-2">
          <div class="col-md-2">
              <label for="status_validasi">Status Validasi</label>
          </div>
          <div class="col-md-5">
              <input type="text" class="form-control" name="status_validasi" value="<?= $nama_status_validasi." | ".$keterangan ?>" readonly style="width: 100%; background-color: var(--bs-body-bg);">
          </div>
      </div>

      <div class="row mt-2">
          <div class="col-md-2">
              <label for="status_validasi">Deadline</label>
          </div>
          <div class="col-md-5 d-flex flex-row">
              <label for="status_validasi" class="form-control" readonly style="width: 100%; background-color: var(--bs-body-bg);"><?= $duedate ?></label>
          </div>
      </div>
      
      <div class="row">
          <div class="col-md-2">
              <label for="status_validasi">Template</label>
          </div>
          <div class="col-lg-5 d-flex flex-row">
              <?php 

                  if ($tahun_folder == 2024) {
                      $url = "http://backup.muliaputramandiri.com:81/cisk/assets/uploads/management_claim/2024/";
                  }else{
                      $url = base_url()."assets/uploads/management_claim/2025/";
                  }

                  if ($filename) { ?>
                      <a href="<?= $url.'/template/'.$filename ?>" class="btn btn-submit-orange" target="_blank" style="border: none; padding: 10px;"><?= $nama_template ?></a>
                  <?php
                  }else{ ?>
                      <label for="status_validasi" class="form-control" readonly style="width: 100%; background-color: var(--bs-body-bg);">-</label>
                  <?php
                  }
              ?>
              
          </div>
      </div>
  </div>
</div>

<div class="card mt-2 mb-3">
    <div class="card-body">

        <div class="row">
            <div class="col-md-2">
                <label for="site_code">Site Code</label>
            </div>
            <div class="col-md-5">
                <input type="text" name="site_code" class="form-control" value="<?= $site_code ?>" readonly style="width: 100%; background-color: var(--bs-body-bg);">
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-2">
                <label for="nama">Nama</label>
            </div>
            <div class="col-md-5">
                <input type="text" name="nama" class="form-control" required>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-2">
                <label for="email">Email</label>
            </div>
            <div class="col-md-5">
                <input type="text" name="email" class="form-control" required>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="ajuan_excel">Attach File (Excel)</label>
            </div>
            <div class="col-md-5">
                <input class="form-control form-control-md" id="ajuan_excel" type="file" name="ajuan_excel" required>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="ajuan_zip">Attach File (Zip)</label>
            </div>
            <div class="col-md-5">
                <input class="form-control form-control-md" id="ajuan_zip" type="file" name="ajuan_zip" required>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-2">
                
            </div>
            <div class="col-md-8">
                <input type="hidden" name="signature_program" value="<?= $signature_program ?>">
                <input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
                <input type="hidden" name="id_log" value="<?= $id_log ?>">
                <?php 
                    // echo "status_authorized : ".$status_authorized;
                    if ($status_authorized) 
                    { 
                        // echo "selisih : ".$selisih;
                        if($selisih >= 0)
                        { 
                            // echo "status_keikutsertaan : ".$status_keikutsertaan;                            
                            if($status_keikutsertaan == 1)
                            { ?>
                                <?php 
                                // jika loyalty_peserta ada, maka button submit muncul
                                // echo "kategori : ".$kategori;
                                if ($kategori == "Loyaltyx") {
                                    
                                    if ($id_loyalty_peserta) { ?>
                                        <button type="submit" class="btn btn-submit-red" id="btnKirim" onclick="return button()" style="height: 45px">Submit Data</button>                
                                        <a href="<?= base_url().'management_claim/registrasi_peserta_loyalty/'.$signature_program ?>" class="btn btn-submit-black" id="btnBack" target="_blank">Registrasi Peserta Loyalty</a>
                                    <?php
                                    }else{ ?>
                                        <a href="<?= base_url().'management_claim/registrasi_peserta_loyalty/'.$signature_program ?>" class="btn btn-submit-black" id="btnBack" target="_blank">Registrasi Peserta Loyalty</a>
                                    <?php
                                    }

                                }else{ ?>
                                    <!-- cek status internal, jangan sampai status pending hardocpy tp dp bisa claim -->
                                    
                                    <?php 
                                        // jika pending hardcopy, maka tidak bisa submit button
                                        if ($status_internal == 5) { ?>
                                            <label for="" class="form-label" style="border: 1px solid black; padding: 5px; width: 50%">seharusnya anda menuju ke menu input hardcopy</label>
                                        <?php
                                        }else{ ?>
                                         <button type="submit" class="btn btn-submit-red" id="btnKirim" onclick="return button()" style="height: 45px">Submit Data</button> 
                                        <?php
                                        }
                                    ?>

                                    
                                <?php
                                } ?>

                            <?php
                            }else{ ?> 
                            <!-- jika sama sekali belum klik "ikut kepesertaan program" -->
                                <?php 
                                    // echo "status_internal : ".$status_internal;
                                    if ($status_internal == 5) { ?>
                                        <label for="" class="form-label" style="border: 1px solid black; padding: 5px; width: 50%">seharusnya anda menuju ke menu input hardcopy</label>
                                    <?php
                                    }else{ 

                                        // jika kategori loyalty
                                        // echo "kategori : ".$kategori;
                                        if ($kategori == "Loyaltyx") 
                                        {
                                            // echo "id_loyalty_peserta : ".$id_loyalty_peserta;
                                            if ($id_loyalty_peserta) { ?>
                                                <button type="submit" class="btn btn-submit-red" id="btnKirim" onclick="return button()" style="height: 45px">Submit Data</button>      
                                                <a href="<?= base_url().'management_claim/registrasi_peserta_loyalty/'.$signature_program ?>" class="btn btn-submit-black" id="btnBack" target="_blank">Registrasi Peserta Loyalty</a>   
                                                       
                                            <?php
                                            }else{ ?>
                                                <a href="<?= base_url().'management_claim/registrasi_peserta_loyalty/'.$signature_program ?>" class="btn btn-submit-black" id="btnBack" target="_blank">Registrasi Peserta Loyalty</a>
                                            <?php
                                            }

                                        }else{ ?>
                                            <button type="submit" class="btn btn-submit-red" id="btnKirim" onclick="return button()" style="height: 45px">Submit Data</button> 
                                        <?php
                                        }                                     
                                    }
                            }
                            ?>
                        <?php
                        }else{ ?>

                            <?php 
                                if ($nomor_ajuan) { ?>
                                    <button type="submit" class="btn btn-submit-red" id="btnKirim" onclick="return button()" style="height: 45px">Submit Data</button>
                                <?php
                                }else{ ?>
                                    <label for="" class="form-label" style="border: 1px solid black; padding: 5px; width: 50%">sudah melebihi deadline</label> 
                                <?php
                                }
                            ?>

                            
                        <?php
                        }?>
                    <?php
                    }else{ ?>
                        

                        <?php
                        // jika kategori loyalty
                        if ($kategori == "Loyaltyx") 
                        {
                            ?>
                            <label for="" style="border: 1px solid red; padding: 5px; width: 50%">menunggu verifikasi : <?= $pic_on_duty ?></label>
                            <a href="<?= base_url().'management_claim/registrasi_peserta_loyalty/'.$signature_program ?>" class="btn btn-submit-black" id="btnBack" target="_blank">Registrasi Peserta Loyalty</a>
                        <?php
                        }else{ ?>
                            <label for="" class="form-label" style="border: 1px solid black; padding: 5px; width: 50%">menunggu verifikasi : <?= $pic_on_duty ?></label>
                        <?php
                        }    

                    }
                ?>
                <!-- <?php 
                    if($kategori == "Loyaltyx"){ ?>
                    <a href="<?= base_url().'management_claim/registrasi_peserta_loyalty/'.$signature_program ?>" class="btn btn-submit-black" id="btnBack" target="_blank">Registrasi Peserta Loyalty</a>
                <?php
                    }
                ?> -->
                <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-submit-black" id="btnBack" style="width: 100px;">Back</a>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>



<script>
// Fungsi untuk menampilkan loading overlay
function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

// Fungsi untuk menyembunyikan loading overlay
function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// Modifikasi fungsi button() yang sudah ada
function button() {
    // Validasi form sebelum submit
    var nama = document.querySelector('input[name="nama"]').value;
    var email = document.querySelector('input[name="email"]').value;
    var fileExcel = document.querySelector('input[name="ajuan_excel"]').value;
    var fileZip = document.querySelector('input[name="ajuan_zip"]').value;
    
    if (!nama || !email || !fileExcel || !fileZip) {
        alert('Semua field harus diisi!');
        return false;
    }
    
    // Tampilkan loading
    showLoading();
    
    // Form akan disubmit secara normal
    return true;
}

// Optional: Tambahkan event listener untuk form submit
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Cek apakah submit berasal dari tombol dengan validasi
            var isFromButton = e.submitter && e.submitter.id === 'btnKirim';
            
            // Jika validasi sudah dilakukan di fungsi button(), form akan submit
            // Tapi kita perlu memastikan loading tetap muncul
            if (isFromButton) {
                // Validasi sudah dilakukan di fungsi button()
                // Loading akan ditampilkan di fungsi button()
            }
        });
    }
    
    // Sembunyikan loading saat halaman selesai dimuat (jika ada)
    hideLoading();
    
    // Sembunyikan loading saat user menggunakan tombol back browser
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoading();
        }
    });
});

// Optional: Tampilkan loading saat link diklik
document.addEventListener('DOMContentLoaded', function() {
    // Untuk link Registrasi Peserta Loyalty
    var regisLink = document.querySelector('a[href*="registrasi_peserta_loyalty"]');
    if (regisLink) {
        regisLink.addEventListener('click', function(e) {
            showLoading();
        });
    }
    
    // Untuk tombol Back
    var backBtn = document.getElementById('btnBack');
    if (backBtn) {
        backBtn.addEventListener('click', function(e) {
            showLoading();
        });
    }
});
</script>