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

  <div class="card" id="form">
    <?= form_open_multipart($url, ['method' => 'post'])?> 
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
                      <option value="pharma" disabled>PHARMA</option>
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
                      <input type="date" name="from" id="from" min="2025-12-01" class="form-control" required>
                      <input type="date" name="to" id="to" min="2025-12-01" class="form-control" required>
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
          <?= form_submit('submit', 'Submit Pengajuan Claim', 'class="btn btn-submit-red"'); ?>
        </div>
      </div>
      <?= form_close(); ?>
  </div>

<script>
$("select[name = channel]").on("change", function() 
{    
  $("#divform_key_account").remove();
  let channel = document.getElementById('channel').value;   
  // alert(channel);         
    
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
    
  if (channel === 'nka') {
    var divKeyAccount =
      `<div class="row mt-1" id="divform_key_account">
        <div class="col-lg-4">
          <label for="key_account">Key Account</label>
        </div>
        <div class="col-lg-8">
          <Select class="form-select" name="key_account" id="key_account">
          <option value=""> -- Pilih Account -- </option>
          <?php foreach ($key_account->result() as $key) {
            echo '<option value="'.$key->key_account. '">'.$key->key_account.'</option>';
          } ?>
          </Select>
        </div>
      </div>`
        
    $("div#divform_no_invoice").after(divKeyAccount);
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
</script>

<script src="<?= base_url('assets/js/form_image_retur_nka.js') ?>"></script>