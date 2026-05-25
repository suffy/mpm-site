<div class="container-fluid mb-5">

  <div class="card mt-4 mb-4">
    <div class="card-body">
      <h5 class="card-title"><?= $title ?></h5>

      <?= form_open_multipart($url); ?>
      <div class="row mt-5">
        <div class="col-lg-2">
          <label for="supp">Nama Lengkap</label>
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control" name="username" value="<?= $nama_lengkap ?>" readonly style="width: 100%; background-color: var(--bs-body-bg);">
        </div>
      </div>

      <div class="row mt-1">
        <div class="col-lg-2">
          <label for="supp">Department</label>
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control" name="username" value="<?= $departement ?>" readonly style="width: 100%; background-color: var(--bs-body-bg);">
        </div>
      </div>

      <div class="row mt-1">
        <div class="col-lg-2">
          <label for="supp">Divisi</label>
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control" name="username" value="<?= $divisi ?>" readonly style="width: 100%; background-color: var(--bs-body-bg);">
        </div>
      </div>

      <!-- <div class="row mt-1">
        <div class="col-lg-2">
          <label for="weight">Berat Badan</label>
        </div>
        <div class="col-md-4">
          <input type="number" class="form-control" name="weight" max="120" min="30" placeholder="masukkan dalam satuan kg" required>
        </div>
      </div>

      <div class="row mt-1">
        <div class="col-lg-2">
          <label for="height">Tinggi Badan</label>
        </div>
        <div class="col-md-4">
          <input type="number" class="form-control" name="height" max="200" min="100" placeholder="masukkan dalam satuan cm" required>
        </div>
      </div> -->

      <div class="row mt-4">
        <div class="col-lg-2">
          <label for="month">Month</label>
        </div>
        <div class="col-md-4">
          <input type="month" class="form-control" min="2026-05" max="2026-12" name="month" required>
        </div>
      </div>

      <div class="row mt-1">
        <div class="col-lg-2">
          <label for="step">Jumlah Langkah</label>
        </div>
        <div class="col-md-4">
          <input type="number" class="form-control" name="step" required>
        </div>
      </div>

      <div class="row mt-1">
        <div class="col-lg-2">
          <label for="capture">Capture Google FIT <a href="<?= base_url('assets/uploads/step/sample/sample-google-fit.jpg') ?>" target="_blank">contoh file</a></label>
        </div>
        <div class="col-md-4">
          <input type="file" class="form-control" name="capture" required>
          
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          <input type="submit" value="Submit Data" class="btn btn-submit-red" style="height: 45px;">
        </div>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row mt-4 mb-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="text-muted mb-2">Total Steps MPM</h6>
          <h3 class="mb-0"><?= isset($total_steps) ? number_format($total_steps) : '0' ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="text-muted mb-2">Rata-rata Steps MPM</h6>
          <h3 class="mb-0"><?= isset($avg_steps) ? number_format($avg_steps) : '0' ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="text-muted mb-2">Steps Terbanyak MPM</h6>
          <h3 class="mb-0"><?= isset($max_steps) ? number_format($max_steps) : '0' ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="text-muted mb-2">Total Bulan</h6>
          <h3 class="mb-0"><?= isset($total_months) ? $total_months : '0' ?></h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-4">📊 Total Steps MPM per Bulan</h5>
      <canvas id="stepsChart" style="max-height: 400px;"></canvas>
    </div>
  </div>

  <!-- Top 3 Users dengan Steps Terbanyak -->
  <div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="card-title mb-0">🏆 Top 3 Peringkat Steps Terbanyak</h5>
        <select id="monthFilter" class="form-select w-auto">
          <option value="">Pilih Bulan</option>
          <?php
          // Ambil daftar bulan yang tersedia
          $months_query = $this->db->query("SELECT DISTINCT month FROM site.step_employee ORDER BY month DESC");
          foreach ($months_query->result() as $month_row) {
            echo '<option value="' . $month_row->month . '">' . $month_row->month . '</option>';
          }
          ?>
        </select>
      </div>

      <div id="topUsersContainer">
        <?php if (isset($top_users) && $top_users->num_rows() > 0): ?>
          <div class="row">
            <?php
            $rank = 1;
            foreach ($top_users->result() as $user):
              ?>
              <div class="col-md-4 mb-3">
                <div class="d-flex align-items-center p-3 border rounded">
                  <div class="me-3">
                    <?php if ($rank == 1): ?>
                      <span class="fs-1">🥇</span>
                    <?php elseif ($rank == 2): ?>
                      <span class="fs-1">🥈</span>
                    <?php elseif ($rank == 3): ?>
                      <span class="fs-1">🥉</span>
                    <?php endif; ?>
                  </div>
                  <div class="d-flex justify-content-between w-100 align-items-center">                    
                    <div>
                      <h6 class="mb-0 fw-bold"><?= $user->username ?></h6>
                      <small class="text-muted"><?= number_format($user->total_steps) ?> steps</small>
                    </div>
                    <div>
                      <a href="<?= base_url('assets/uploads/step/' . $user->capture ) ?>" target="_blank" style="font-size: 12px; text-decoration: none; color: var(--bs-light-text-emphasis); font-weight: bold; border: 2px solid; border-radius: 25px; padding: 5px;">view image</a>
                    </div> 
                  </div>
                
                </div>
              </div>
              <?php
              $rank++;
            endforeach;
            ?>
          </div>
        <?php else: ?>
          <div class="text-center text-muted p-4">
            <p>Belum ada data untuk ditampilkan</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <table id="tabel" class="table-striped" style="width:100%">
    <thead>
      <tr>
        <th class="text-center" width="5%">No</th>
        <th class="text-center">Nama</th>
        <!-- <th class="text-center">Berat</th>
        <th class="text-center">Tinggi</th> -->
        <th class="text-center">Bulan</th>
        <th class="text-center">Step</th>
        <th class="text-center">FIle</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      foreach ($get_data->result() as $key):
        ?>
        <tr>
          <td class="text-center"><?= $no++; ?></td>
          <td class="text-center"><?= $key->username; ?></td>
          <!-- <td class="text-center"><?= $key->weight; ?></td>
          <td class="text-center"><?= $key->height; ?></td> -->
          <td class="text-center">
            <?php 
              $bulan_indo = array(
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
              );
              
              $bulan_inggris = date('F', strtotime($key->month . '-01'));
              $tahun = date('Y', strtotime($key->month . '-01'));
              echo $bulan_indo[$bulan_inggris] . ' ' . $tahun;
            ?>
          </td>
          <td class="text-center"><?= number_format($key->steps); ?></td>
          <td class="text-center"><a href="<?= base_url('assets/uploads/step/' . $key->capture ) ?>" target="_blank" rel="noopener noreferrer">view</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  $(document).ready(function () {
    $('#tabel').DataTable({
      "pageLength": 10,
      "ordering": true,
      "order": [0, 'asc'],
      "aLengthMenu": [
        [10, 20, 50, -1],
        [10, 20, 50, "All"]
      ],
      scrollX: true,
    });

    // Chart data dari PHP
    var chartLabels = <?= json_encode($chart_labels) ?>;
    var chartData = <?= json_encode($chart_values) ?>;

    // Create Line Chart
    var ctx = document.getElementById('stepsChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartLabels,
        datasets: [{
          label: 'Jumlah Langkah',
          data: chartData,
          backgroundColor: 'rgba(108, 117, 125, 0.1)',
          borderColor: 'rgba(108, 117, 125, 1)',
          borderWidth: 2,
          pointBackgroundColor: 'rgba(108, 117, 125, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              font: { size: 12 },
              color: '#6c757d'
            }
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                return 'Steps: ' + context.parsed.y.toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Jumlah Langkah',
              color: '#6c757d',
              font: { size: 12 }
            },
            ticks: {
              callback: function (value) {
                return value.toLocaleString('id-ID');
              }
            },
            grid: {
              color: 'rgba(0,0,0,0.05)'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Bulan',
              color: '#6c757d',
              font: { size: 12 }
            },
            ticks: {
              color: '#6c757d'
            },
            grid: {
              display: false
            }
          }
        }
      }
    });
  });

  $(document).ready(function () {
    $('#monthFilter').change(function () {
      var month = $(this).val();
      if (month) {
        $.ajax({
          url: 'step/get_top3_by_month_ajax/' + month,
          type: 'GET',
          success: function (response) {
            $('#topUsersContainer').html('<div class="row">' + response + '</div>');
          }
        });
      }
    });
  });
</script>