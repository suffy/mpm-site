  <div class="card mt-4">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <p><?= $title ?></p>
        </div>
      </div>
      <?=  form_open($url,['method' => 'get']) ?>

      <div class="row">
        <div class="col-lg-5 d-flex gap-1">
          <input type="date" name="from" id="from" min="2026-01-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
          <input type="date" name="to" id="from" min="2026-01-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
          
        </div>
        <div class="col-lg-5">
            <button type="submit" value="search" name="submit" class="btn btn-primary" style="height: 45px;">Search</button>
            <!-- <button type="submit" value="export" name="submit" class="btn btn-success" style="height: 45px;">Export</button> -->
        </div>
      </div>
      <?php echo form_close(); ?>

      <hr>

      <div class="row mt-5">
        <div class="col-md-12">
          <p><b>Summary</b></p>
        </div>
        <div class="col-md-12">
          <table class="table-striped" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 1%;">No</th>
                <th>Name</th>
                <th>Type</th>
                <th>Count</th>
                <th style="width: 15%;">#</th>              
              </tr>
            </thead>
            <tbody>
              <?php 
              $no = 1;
              foreach($get_summary->result() as $a) : ?> 
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $a->username ?></td>
                <td><?= $a->type ?></td>
                <td><?= $a->count ?></td>
                <td> - </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <hr>

      <div class="row mt-5">
        <div class="col-md-12">
          <p><b>Detail Absensi</b></p>
        </div>
        <div class="col-md-12">
          <table id="table" class="table-striped" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 1%;">No</th>
                <th>Name</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 15%;">Absensi</th>
                <th style="width: 1%;">Terlambat</th>
                <th>Keterangan</th>
                <th>Activity</th>
                <th>Lihat Foto</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $no = 1;
              foreach($get_absensi->result() as $a) : ?> 
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $a->username ?></td>
                <td>
                    <?= $a->tanggal ?>
                    <?php 
                      if($a->status_hari == 6)
                      {
                        echo " (sabtu)";
                      }
                    ?>
                </td>
                <td><?= $a->actual_masuk ? $a->actual_masuk : '__:__:__' ?> - <?= $a->actual_keluar ? $a->actual_keluar : '__:__:__' ?></td>
                <td><?= $a->flag_terlambat ? $a->flag_terlambat : '-' ?></td>
                <td><?= $a->keterangan ?></td>
                <td><?= $a->count ?></td>
                <td>
                  <a href="<?= base_url().'management_office/detail_activity/'.$a->id.'/'.$a->tanggal ?>">Detail</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script>
$(document).ready(function () {    
    $('#table').DataTable({
        
        "ordering": true,
        "order": [0, 'asc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        scrollX: true,
        responsive: true,
        lengthChange: false, // Menonaktifkan opsi untuk mengubah jumlah data per halaman.
        displayLength: 20 //Menentukan jumlah data per halaman yang ditampilkan.
    });
});
</script>