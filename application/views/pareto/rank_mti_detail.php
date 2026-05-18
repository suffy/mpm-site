</div>
<div class="container-fluid">

  <div class="row">
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

  <div class="card mt-1">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <h5 class="card-title"><?= $title ?></h5>
        </div>
      </div>
      <hr>
      <div class="row mt-4">
        <div class="col-md-12">
          <span class="btn btn-light">data updated_at : <?= $data_date ?></span>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">

          <!-- Keterangan -->
          <div class="row mt-3">
              <div class="col-md-12">
                  <div class="alert alert-info">
                      <strong>Keterangan:</strong><br>
                      <span class="text-success">▲ Hijau = Kenaikan</span><br>
                      <span class="text-danger">▼ Merah = Penurunan</span><br>
                      <span>● Hitam = Tetap</span>
                  </div>
              </div>
          </div>

          <!-- Tabel Perbandingan -->
          <div class="table-responsive">
              <table id="tabel" class="table-striped table-hover" style="width:100%">
                  <thead class="bg-primary text-white">
                      <tr>
                          <th width="1%" class="text-center">No</th> 
                          <th>Subbranch</th>               
                          <th>Outlet</th>               
                          <th>Nama Outlet</th>               
                          <th>Type</th>
                          <th>Bulan</th>
                          <th class="text-right">Bruto 2025</th>
                          <th class="text-right">Bruto 2026</th>
                          <th class="text-right">Selisih</th>
                          <th class="text-center">Pertumbuhan</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php 
                      $no = 1;                        
                      foreach($get_data->result() as $a) : 
                          $selisih = $a->bruto_2026 - $a->bruto_2025;
                          $warna_selisih = ($selisih >= 0) ? 'text-success' : 'text-danger';
                          $warna_persen = ($a->pertumbuhan >= 0) ? 'text-success' : 'text-danger';
                          $icon = ($selisih > 0) ? '▲' : (($selisih < 0) ? '▼' : '●');
                      ?>
                      <tr>
                          <td class="text-center"><?= $no++ ?></td>
                          <td><?= $a->nama_comp ?></td>                
                          <td><?= $a->outlet ?></td>                
                          <td><?= $a->nama_outlet ?></td>                
                          <td><?= $a->kode_type ?></td>                
                          <td class="text-center"><?= isset($bulan_array[$a->bulan]) ? $bulan_array[$a->bulan] : $a->bulan ?></td>                
                          <td class="text-right">Rp <?= number_format($a->bruto_2025, 0, ',', '.') ?></td>                
                          <td class="text-right">Rp <?= number_format($a->bruto_2026, 0, ',', '.') ?></td>                
                          <td class="text-right <?= $warna_selisih ?>">
                              <?= $icon ?> Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                          </td>                
                          <td class="text-center <?= $warna_persen ?>">
                              <?= number_format($a->pertumbuhan, 2) ?>%
                          </td>                
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
                  <tfoot class="bg-light">
                      <?php 
                      $total_selisih = $total_2026 - $total_2025;
                      $total_persen = ($total_2025 > 0) ? ($total_selisih / $total_2025) * 100 : 0;
                      $warna_total = ($total_selisih >= 0) ? 'text-success' : 'text-danger';
                      $icon_total = ($total_selisih > 0) ? '▲' : (($total_selisih < 0) ? '▼' : '●');
                      ?>
                      <tr>
                          <th colspan="6" class="text-right">TOTAL:</th>
                          <th class="text-right">Rp <?= number_format($total_2025, 0, ',', '.') ?></th>
                          <th class="text-right">Rp <?= number_format($total_2026, 0, ',', '.') ?></th>
                          <th class="text-right <?= $warna_total ?>">
                              <?= $icon_total ?> Rp <?= number_format(abs($total_selisih), 0, ',', '.') ?>
                          </th>
                          <th class="text-center <?= $warna_total ?>">
                              <?= number_format($total_persen, 2) ?>%
                          </th>
                      </tr>
                  </tfoot>
              </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  $(document).ready(function () {
    $('#tabel').DataTable({
      "pageLength": 100,
      "ordering": true,
      "order": [0, 'asc'],
      "aLengthMenu": [
          [10, 20, 50, -1],
          [10, 20, 50, "All"]
      ],
      scrollX: true,
    });

  });
</script>


