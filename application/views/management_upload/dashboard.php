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
    
    <!-- button untuk upload dan history -->
    <button class="btn btn-submit-black" type="button" onclick="toggleActiveForm()" id="btn-formUpload">
        Upload File
    </button>
    <button class="btn btn-submit-black" type="button" onclick="toggleActiveHistory()" id="btn-historyUpload">
        History Upload
    </button>

    <div class="row" id="history">
      <!-- History data upload website -->
      <div class="col-md">
        <div class="card mt-3 ">
          <div class="card-header">
            <h5>History Upload</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="table-history">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>PIC Upload</th>
                    <th>Filename</th>
                    <th>Omzet</th>
                    <th>File</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data_uploadhistory->result() as $key) :?>
                    <tr>
                      <th><?= "$key->tahun/$key->bulan/$key->tanggal"; ?></th>
                      <th><?= $key->username; ?></th>
                      <th><?= $key->filename; ?></th>
                      <th><?= "Rp. $key->omzet"; ?></th>
                      <th><?= ($key->status_closing == 1 ? "Closing" : "Harian"); ?></th>
                      <th><?= ($key->status == 1 ? "<b style='color:green;'>Success</b>" : ($key->status == 2 ? "Re-Upload" : "<b style='color:red;'>Failed</b>")); ?></th>
                    </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row" id="detail">
      <!-- form upload -->
      <div class="col-md" id="form-upload">
        <div class="card mt-3">
          <div class="card-header">
            <h5>Upload File</h5>
          </div>
          <div class="card-body">
            <form action="<?= base_url("$url")?>" method="post" enctype="multipart/form-data">
              <div class="row mt-3">
                <div class="col-6">
                  <p>Pilih Tahun File Yang Diupload :</p>
                </div>
                <div class="col-6">
                  <?php
                    // $interval = date('Y') - 2024;
                    // for ($i = 1; $i <= $interval; $i++) {
                    //   $options[$i + 2024] = $i + 2024;
                    // }
                    // echo form_dropdown('year', $options, date('Y'), 'class="form-control"');

                    $options = [];
                    // Jika get_upload NOT NULL, maka $max_tahun dari controller adalah 2026
                    // Kita buat start dan end tahunnya sama agar hanya muncul 1 pilihan
                    $year_display = $max_tahun; 
                    $options[$year_display] = $year_display;
                    echo form_dropdown('year', $options, $year_display, 'class="form-control"');
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <p>Upload data (DTXXXXXX.ZIP) :</p>
                </div>
                <div class="col-6">
                  <input type="file" name="userfile" class="form-control" id="userfile" required />
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <p>Status Data :</p>
                </div>
                <div class="col-6">
                  <select name="status_closing" id="status_closing" class="form-control" required>
                    <option value="">Pilih</option>
                    <option value="0">Bukan Closing Bulan Ini</option>
                    <option value="1" name=>Ya, Closing Bulan Ini</option>
                  </select>
                </div>
              </div>

              <div class="col-md-12" id="loading" align="center">
                <img src="<?= base_url() . 'assets/gif/loading.gif' ?>" alt="">
              </div>
              
              <div class="row mt-3">
                <button class="btn btn-round btn-sm btn-success submit" type="submit" id="btn-uploadData">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Detail data upload website -->
      <div class="col-md" id="detail">
        <div class="card mt-3 ">
          <div class="card-header">
            <h5>Detail Last Updated</h5>
          </div>
          <?php if ($data_upload->num_rows() > 0) { ?>
            <div class="card-body">
              <div class="row mt-3">
                  <h5>Di bawah ini adalah data upload terakhir Sub Branch anda yang sudah masuk ke dalam website</h5>
              </div>
  
              <div class="row">
                  <div class="col-4">
                      1. Nama File
                  </div>
                  <div class="col-6">
                      : <font size="4px"><strong><?= $data_upload->row('filename')?></strong></font>
                  </div>
              </div>
  
              <div class="row">
                  <div class="col-4">
                      2. Dikirim secara
                  </div>
                  <div class="col-6">
                      : <font size="4px" style="text-transform: capitalize;">
                          <strong>
                          <?= ($data_upload->row('flag') == 3 ? "otomatis dikirim dan diproses oleh SDS" : ($data_upload->row('flag') == 2 ? "konversi excel di Tim IT" : "manual melalui upload di web ini")) ?>
                          </strong>
                      </font>
                  </div>
              </div>
  
              <div class="row">
                  <div class="col-4">
                      3. Tanggal Kirim Data
                  </div>
                  <div class="col-6">
                      : <font size="4px"><strong><?= $data_upload->row('lastupload'); ?></strong></font>
                  </div>
              </div>
  
              <div class="row">
                  <div class="col-4">
                      4. Status Submit
                  </div>
                  <div class="col-6">
                  : <font size="4px" style="text-transform: capitalize;">
                          <strong>
                          <?= ($data_upload->row('status') == 0 ? "Belum (data harus di submit agar masuk ke database website)" : "Berhasil (data sudah masuk ke database website)") ?>
                          </strong>
                      </font>
                  </div>
              </div>
  
              <div class="row">
                  <div class="col-4">
                      5. Tanggal Transaksi
                  </div>
                  <div class="col-6">
                      : <font size="4px"><strong><?= $data_upload->row('tanggal') . '-' . $data_upload->row('bulan') . '-' . $data_upload->row('tahun'); ?></strong></font>
                  </div>
              </div>
  
              <div class="row">
                  <div class="col-4">
                      6. Total Omzet
                  </div>
                  <div class="col-6">
                      : <font size="4px"><strong><?= "Rp. " . $data_upload->row('omzet'); ?></strong></font>
                  </div>
              </div>
            </div>
          <?php } else { ?>
            <h5>Anda belum pernah melakukan proses upload</h5>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $("div#history").hide();
  $("#form-upload").hide();
  $("#loading").hide();
  $('#table-history').DataTable({
    "pageLength": 10,
    "ordering": true,
    "order": [0, 'desc'],
    "aLengthMenu": [
        [10, 20, 50, -1],
        [10, 20, 50, "All"]
    ],
    "fixedHeader": {
        header: true,
        footer: true
    },
    // table
    // .columns(3)
    // .search(this.value)
    // .draw()
  });
</script>

<script>
  function toggleActiveForm() {
    var button = document.getElementById("btn-formUpload");

    // Toggle between the 'active' and 'non-active' classes
    if (button.classList.contains("non-active")) {
      button.classList.remove("non-active");
      button.classList.add("active");
      button.textContent = "Upload File"; // Change button text

      $("#form-upload").hide();
      window.location.href = "<?= base_url('management_upload#detail');?>";
    } else {
      window.location.href = "<?= base_url('management_upload#detail');?>";
      button.classList.remove("active");
      button.classList.add("non-active");
      button.textContent = "Upload File"; // Change button text
      
      $("#form-upload").show();
    }
  }
  
  function toggleActiveHistory() {
    var button = document.getElementById("btn-historyUpload");
    
    // Toggle between the 'active' and 'non-active' classes
    if (button.classList.contains("non-active")) {
      button.classList.remove("non-active");
      button.classList.add("active");
      button.textContent = "History Upload"; // Change button text
      
      $("div#history").hide();
      window.location.href = "<?= base_url('management_upload#history');?>";
    } else {
      button.classList.remove("active");
      button.classList.add("non-active");
      button.textContent = "History Upload"; // Change button text
      
      $("div#history").show();
      window.location.href = "<?= base_url('management_upload#history');?>";
    }
  }
</script>

<script>
  // Ambil elemen input dan tombol
  const userfile = document.getElementById('userfile');
  const status_closing = document.getElementById('status_closing');
  const submitButton = document.getElementById('btn-uploadData');

  // Fungsi untuk memeriksa apakah input kosong atau tidak
  function checkInput() {
      if (userfile.value.trim() === '' || status_closing.options[status_closing.selectedIndex].value.trim() === '') {
          submitButton.disabled = true; // Nonaktifkan tombol jika input kosong
      } else {
          submitButton.disabled = false; // Aktifkan tombol jika input tidak kosong
      }
  }

  // Menambahkan event listener untuk mendeteksi perubahan pada input
  userfile.addEventListener('input', checkInput);
  status_closing.addEventListener('change', checkInput);
  
  // Panggil fungsi cek input saat pertama kali halaman dimuat
  checkInput();

  
  $('#btn-uploadData').click(function(event) {
        $(this).css('color', 'grey'); // Mengubah warna link agar terlihat tidak aktif
        $(this).css('pointer-events', 'none'); // Menghentikan klik lebih lanjut
    });
</script>