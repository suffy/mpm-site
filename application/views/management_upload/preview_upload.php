<div class="container-fluid">
  <div class="col-md" id="detail_upload">
  <div class="row">
      <div class="col-md-12 az-content-label">
        <?= $title ?>
        <?php
          if ($this->session->flashdata('pesan_gagal')) { ?>
            <div class="alert alert-danger mt-3" role="alert">
              <?= $this->session->flashdata('pesan_gagal'); ?>
            </div>
          <?php
          } elseif ($this->session->flashdata('pesan_success')) { ?>
            <div class="alert alert-success mt-3" role="alert">
              <?= $this->session->flashdata('pesan_success'); ?>
            </div>
          <?php }?>
      </div>
    </div>

    <div class="row" id="detail">
      <!-- Detail data upload website -->
      <div class="col-md" id="detail">
        <div class="card mt-3 ">
          <div class="card-header">
            <h5>Preview Upload</h5>
          </div>

          <div class="card-body">
            <div class="row mt-3">
                <h5>Anda sudah meng-Upload Data sebagai berikut :</h5>
            </div>

            <div class="row">
                <div class="col-4">
                    1. Nama File
                </div>
                <div class="col-6">
                    : <font size="4px"><strong><?= $data_upload['filename'] ?></strong></font>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    2. Tanggal Proses Data
                </div>
                <div class="col-6">
                    : <font size="4px"><strong><?= $data_upload['lastupload'] ; ?></strong></font>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    3. Status Data
                </div>
                <div class="col-6">
                    : <font size="4px"><strong><?= ($data_upload['status_closing'] == 1 ? "Closing" : "Harian") ?></strong></font>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    4. Total Omzet
                </div>
                <div class="col-6">
                    : <font size="4px"><strong><?= "Rp. " . $data_upload['omzet'] ; ?></strong></font>
                </div>
            </div>
            <div class="row mt-3">
                  Pastika total omzet sudah sesuai
                  <br> - Jika total omzet sudah benar, klik save
                  <br> - jika tidak, silahkan klik re-upload
            </div>
            <div class="row mt-3">
              <div class="col" style="text-align: center;">
                  <a href="<?= base_url($url_simpan) ?>" class="btn btn-success btn-submit-black" type="submit" id="btn-submit">Save</a>
                  <a href="<?= base_url($url_reupload) ?>" class="btn btn-submit-black" type="submit">Re-upload</a>
              </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    $('#btn-submit').click(function(event) {
        $(this).css('color', 'grey'); // Mengubah warna link agar terlihat tidak aktif
        $(this).css('pointer-events', 'none'); // Menghentikan klik lebih lanjut
    });
</script>