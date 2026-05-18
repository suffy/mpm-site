<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  /* Warna background dan border */
  .select2-container--default .select2-selection--single {
      height: 38px;
      padding: 5px;
      border: 1px solid var(--bs-dark-border-subtle); /* Warna border abu-abu */
      background-color: var(--bs-dark-rgb);
  }

  /* Warna teks yang dipilih */
  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: var(--bs-light-text-emphasis);
      line-height: 28px;
  }

  /* Warna placeholder/option default */
  .select2-container--default .select2-selection--single .select2-selection__placeholder {
      color: var(--bs-light-text-emphasis);
  }

  /* Warna arrow/dropdown icon */
  .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 36px;
  }

  /* Hover effect */
  .select2-container--default .select2-selection--single:hover {
      border-color: var(--bs-dark-border-subtle);
  }

  /* Focus effect */
  .select2-container--default.select2-container--focus .select2-selection--single {
      border-color: var(--bs-dark-border-subtle);
      outline: 0;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
  }

  /* Warna dropdown options */
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: var(--bs-dark-bg-subtle);
      color:var(--bs-dark-text-emphasis);
  }

  /* Warna option yang sedang dipilih di dropdown */
  .select2-container--default .select2-results__option[aria-selected=true] {
      background-color: var(--bs-dark-bg-subtle);
  }

  /* Warna teks option */
  .select2-container--default .select2-results__option {
      color:var(--bs-dark-text-emphasis);
  }

  .select2-dropdown {
      background-color: var(--bs-dark-bg-subtle);
      border: 1px solid var(--bs-dark-border-subtle);
  }

  /* Tab Styling */
  .nav-tabs .nav-link.active {
      color: var(--bs-dark-text-emphasis);
      font-weight: 800;
      background-color: var(--bs-dark-bg-subtle);
      border-color: var(--bs-dark-border-subtle);
  }

  .nav-tabs .nav-link {
      color: var(--bs-dark-text-emphasis);
      font-weight: 100;
  }

  .tab-content {
      padding: 20px 0;
  }
  .text-right {
      text-align: right;
  }

  /* Card Styling */
  .card-header {
      padding: 0.75rem 1.25rem;
  }
  .border {
      border: 1px solid #dee2e6 !important;
  }
  .bg-light {
      background-color: #f8f9fa !important;
  }

  /* Slider styling */
  #threshold_slider .ui-slider-range {
      background: #007bff;
  }

  #threshold_slider .ui-slider-handle {
      border-color: #007bff;
      background: #007bff;
      border-radius: 50%;
      width: 1.2em;
      height: 1.2em;
      cursor: pointer;
  }

  #threshold_slider .ui-slider-handle:focus {
      outline: none;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
  }

  #threshold_slider {
      height: 8px;
      background: #dee2e6;
      border: none;
      border-radius: 4px;
  }
</style>

<div class="container-fluid mt-4">
    <h2><?= $title; ?></h2>
    
    <!-- Alert Messages -->
    <div class="row">
        <div class="col-md-12">
          <?php if ($this->session->flashdata('pesan')) { ?>
            <div class="alert alert-danger" role="alert"><?= $this->session->flashdata('pesan'); ?></div>
          <?php } elseif ($this->session->flashdata('pesan_success')) { ?>
            <div class="alert alert-success" role="alert"><?= $this->session->flashdata('pesan_success'); ?></div>
          <?php } ?>
        </div>
    </div>

    <!-- Filter Form -->
    <?php echo form_open($url, ['method' => 'get']); ?>
    <div class="row">
        <div class="col-md-12">
          <!-- Tahun -->
          <div class="row mt-4">
            <div class="col-md-2"><label>Tahun (*)</label></div>
            <div class="col-md-4">
              <select name="tahun" class="form-control" required>
                <option value="">Pilih Tahun</option>
                <option value="2025,2026" <?= ($tahun == '2025,2026') ? 'selected' : '' ?>>2025,2026</option>
              </select>
            </div>
          </div>

          <!-- Principal -->
          <div class="row mt-2">
              <div class="col-md-2"><label>Principal</label></div>
              <div class="col-md-4">
                  <select name="supp" class="form-control" required>
                      <option value="">Pilih Principal</option>
                      <?php foreach ($get_master_principal->result() as $key) { ?>
                          <option value="<?= $key->supp ?>" <?= ($supp == $key->supp) ? 'selected' : '' ?>>
                              <?= $key->namasupp ?>
                          </option>
                      <?php } ?>
                  </select>
              </div>
          </div>

          <!-- Periode -->
          <div class="row mt-2">
              <div class="col-md-2"><label>Periode (*)</label></div>
              <div class="col-md-4">
                  <select name="periode" class="form-control" required>
                      <option value="">- Pilih Periode -</option>
                      <option value="q1" <?= ($periode == 'q1') ? 'selected' : '' ?>>Q1</option>
                      <option value="q2" <?= ($periode == 'q2') ? 'selected' : '' ?>>Q2</option>
                      <option value="q3" <?= ($periode == 'q3') ? 'selected' : '' ?>>Q3</option>
                      <option value="q4" <?= ($periode == 'q4') ? 'selected' : '' ?>>Q4</option>
                      <option value="all" <?= ($periode == 'all') ? 'selected' : '' ?>>full year</option>
                  </select>
              </div>
          </div>

          <!-- Type (Pharma) -->
          <div class="row mt-2">
              <div class="col-md-2"><label>Include Pharma (*)</label></div>
              <div class="col-md-4">
                  <select name="type" class="form-control" required>
                      <option value="">- Pilih Type -</option>
                      <option value="include_pharma" <?= ($type == 'include_pharma') ? 'selected' : '' ?>>include pharma (PBF, TOB, APT)</option>
                      <option value="exclude_pharma" <?= ($type == 'exclude_pharma') ? 'selected' : '' ?>>exclude pharma (PBF, TOB, APT)</option>
                  </select>
              </div>
          </div>

          <!-- Class (Ritel) -->
          <div class="row mt-2">
              <div class="col-md-2"><label>Include Ritel (*)</label></div>
              <div class="col-md-4">
                  <select name="class" class="form-control" required>
                      <option value="">- Pilih Type -</option>
                      <option value="include_ritel" <?= ($class == 'include_ritel') ? 'selected' : '' ?>>include ritel (RT)</option>
                      <option value="exclude_ritel" <?= ($class == 'exclude_ritel') ? 'selected' : '' ?>>exclude ritel (RT)</option>
                  </select>
              </div>
          </div>

          <!-- Subbranch -->
          <div class="row mt-2">
            <div class="col-md-2"><label>Subbranch (*)</label></div>
            <div class="col-md-4">
              <select name="site_code" id="site_code" class="form-control select2" required>
                <option value="">- Pilih Site -</option>
                <?php foreach ($get_master_site->result() as $key) { ?>
                    <option value="<?= $key->site_code ?>">
                        <?= $key->branch_name.' - '.$key->nama_comp.' ('.$key->site_code.')' ?>
                    </option>
                <?php } ?>
              </select>
            </div>
          </div>

            <!-- Buttons -->
            <div class="row mt-4">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <button class="btn btn-primary">Search</button>
                    <?php if ($flag_export) { ?>
                        <!-- <a href="<?= base_url() ?>pareto/export_raw_data/<?= $tahun ?>/<?= $supp ?>" class="btn btn-warning">Export Raw Data</a> -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: var(--bs-dark-bg-subtle); color: var(--bs-dark-text-emphasis);">
                    <h5 class="mb-0">Pareto Analysis</h5>
                </div>
                <div class="card-body">
                    <!-- Input untuk Threshold Range -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style="background-color: var(--bs-dark-bg-subtle); color: var(--bs-dark-text-emphasis);">
                <h6 class="mb-0">Threshold Akumulasi Contribution Range</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text">Min</span>
                            <input type="number" id="min_threshold" class="form-control" value="<?= isset($_GET['min_threshold']) ? htmlspecialchars($_GET['min_threshold']) : 0 ?>" min="0" max="100" step="1">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text">Max</span>
                            <input type="number" id="max_threshold" class="form-control" value="<?= isset($_GET['max_threshold']) ? htmlspecialchars($_GET['max_threshold']) : 50 ?>" min="0" max="100" step="1">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" id="apply_threshold_range">Apply</button>
                    </div>
                </div>
                
                <!-- Slider Range -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div id="threshold_slider" style="margin-top: 10px;"></div>
                        <div class="d-flex justify-content-between mt-1">
                            <small>0%</small>
                            <small id="slider_values">
                                <?= isset($_GET['min_threshold']) ? $_GET['min_threshold'] : 0 ?>% - 
                                <?= isset($_GET['max_threshold']) ? $_GET['max_threshold'] : 50 ?>%
                            </small>
                            <small>100%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// DEFINISIKAN VARIABLE DI SINI (SEBELUM DIGUNAKAN)
$min_threshold = isset($_GET['min_threshold']) ? floatval($_GET['min_threshold']) : 0;
$max_threshold = isset($_GET['max_threshold']) ? floatval($_GET['max_threshold']) : 50;
$growth_target = isset($_GET['growth_target']) ? floatval($_GET['growth_target']) : 10;

// Validasi threshold
if ($max_threshold <= 0) $max_threshold = 50;
if ($min_threshold < 0) $min_threshold = 0;
if ($min_threshold > $max_threshold) $min_threshold = $max_threshold;

// Fungsi untuk menghitung data Pareto per tahun (TELAH DIPERBAIKI)
function calculateParetoData($data, $tahun, $max_threshold) {
    $result = array(
        'data' => array(),
        'total_omzet' => 0,
        'outlet_range' => array(),
        'total_value_range' => 0,
        'outlet_details' => array()
    );
    
    // Filter data by tahun
    foreach ($data->result() as $a) {
        if ($a->tahun == $tahun) {
            $result['data'][] = $a;
            $result['total_omzet'] += $a->omzet;
        }
    }
    
    // CEK APAKAH DATA KOSONG ATAU TOTAL OMZET = 0
    if (empty($result['data']) || $result['total_omzet'] == 0) {
        return $result; // Kembalikan array kosong
    }
    
    // Urutkan descending
    usort($result['data'], function($x, $y) {
        if ($x->omzet == $y->omzet) return 0;
        return ($x->omzet < $y->omzet) ? 1 : -1;
    });
    
    // Hitung akumulasi dan filter berdasarkan threshold
    $akumulasi = 0;
    foreach ($result['data'] as $a) {
        // AMAN KARENA SUDAH DICEK $result['total_omzet'] > 0
        $contribution = ($a->omzet / $result['total_omzet'] * 100);
        $akumulasi += $contribution;
        
        // Gunakan pembulatan untuk menghindari floating point issues
        $akumulasi_bulat = round($akumulasi, 4);
        $max_bulat = round($max_threshold, 4);
        
        // Simpan data outlet
        $outlet_detail = array(
            'nama' => $a->nama_outlet . ' (' . $a->nama_comp . ')',
            'omzet' => $a->omzet,
            'contribution' => $contribution,
            'akumulasi' => $akumulasi,
            'akumulasi_bulat' => $akumulasi_bulat,
            'raw_data' => $a
        );
        
        $result['outlet_details'][] = $outlet_detail;
        
        // Hanya masukkan ke range jika akumulasi <= max_threshold
        if ($akumulasi_bulat <= $max_bulat) {
            $result['outlet_range'][] = $outlet_detail;
            $result['total_value_range'] += $a->omzet;
        }
        
        // Hentikan jika sudah melewati max threshold
        if ($akumulasi > $max_threshold) {
            break;
        }
    }
    
    return $result;
}

// Hitung data untuk kedua tahun
$data_2025 = calculateParetoData($get_data, 2025, $max_threshold);
$data_2026 = calculateParetoData($get_data, 2026, $max_threshold);

// Tampilkan pesan jika data kosong
if (empty($data_2025['data']) && empty($data_2026['data'])) {
    echo '<div class="alert alert-warning">Tidak ada data untuk tahun 2025 dan 2026</div>';
} else {
    if (empty($data_2025['data'])) {
        echo '<div class="alert alert-warning">Tidak ada data untuk tahun 2025</div>';
    }
    if (empty($data_2026['data'])) {
        echo '<div class="alert alert-warning">Tidak ada data untuk tahun 2026</div>';
    }
}
?>

<!-- Range Summary -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="border p-3 rounded">
            <h6 class="text-primary">Tahun 2025 - Akumulasi Contribution ≤ <?= $max_threshold ?>%</h6>
            <?php if (!empty($data_2025['data'])): ?>
                <p class="mb-1"><strong>Total Outlet:</strong> <?= count($data_2025['outlet_range']) ?> outlet</p>
                <p class="mb-1"><strong>Total Value:</strong> Rp <?= number_format($data_2025['total_value_range'], 0, ',', '.') ?> 
                    (<?= number_format(($data_2025['total_omzet'] > 0) ? ($data_2025['total_value_range'] / $data_2025['total_omzet'] * 100) : 0, 2, ',', '.') ?>% dari total)</p>
                <p class="mb-0"><strong>Outlet:</strong></p>
                <ol class="mb-0" style="padding-left: 20px; max-height: 200px; overflow-y: auto;">
                    <?php foreach ($data_2025['outlet_range'] as $i => $outlet) { ?>
                        <li>
                            <?= htmlspecialchars($outlet['nama']) ?> - 
                            Rp <?= number_format($outlet['omzet'], 0, ',', '.') ?> 
                            (<?= number_format($outlet['contribution'], 2, ',', '.') ?>%) - 
                            Akum: <?= number_format($outlet['akumulasi'], 2, ',', '.') ?>%
                        </li>
                    <?php } ?>
                </ol>
            <?php else: ?>
                <p class="text-muted">Tidak ada data untuk tahun 2025</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="border p-3 rounded">
            <h6 class="text-primary">Tahun 2026 - Akumulasi Contribution ≤ <?= $max_threshold ?>%</h6>
            <?php if (!empty($data_2026['data'])): ?>
                <p class="mb-1"><strong>Total Outlet:</strong> <?= count($data_2026['outlet_range']) ?> outlet</p>
                <p class="mb-1"><strong>Total Value:</strong> Rp <?= number_format($data_2026['total_value_range'], 0, ',', '.') ?> 
                    (<?= number_format(($data_2026['total_omzet'] > 0) ? ($data_2026['total_value_range'] / $data_2026['total_omzet'] * 100) : 0, 2, ',', '.') ?>% dari total)</p>
                <p class="mb-0"><strong>Outlet:</strong></p>
                <ol class="mb-0" style="padding-left: 20px; max-height: 200px; overflow-y: auto;">
                    <?php foreach ($data_2026['outlet_range'] as $i => $outlet) { ?>
                        <li>
                            <?= htmlspecialchars($outlet['nama']) ?> - 
                            Rp <?= number_format($outlet['omzet'], 0, ',', '.') ?> 
                            (<?= number_format($outlet['contribution'], 2, ',', '.') ?>%) - 
                            Akum: <?= number_format($outlet['akumulasi'], 2, ',', '.') ?>%
                        </li>
                    <?php } ?>
                </ol>
            <?php else: ?>
                <p class="text-muted">Tidak ada data untuk tahun 2026</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Growth Analysis -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="border p-3 rounded">
            <h6 class="text-success">Growth Analysis (Berdasarkan Outlet dengan Akumulasi ≤ <?= $max_threshold ?>%)</h6>
            
            <?php
            // Hitung metrics growth (DENGAN PENGECEKAN DIVISION BY ZERO)
            $total_value_range_2025 = isset($data_2025['total_value_range']) ? $data_2025['total_value_range'] : 0;
            $total_value_range_2026 = isset($data_2026['total_value_range']) ? $data_2026['total_value_range'] : 0;
            $total_omzet_2025 = isset($data_2025['total_omzet']) ? $data_2025['total_omzet'] : 0;
            $total_omzet_2026 = isset($data_2026['total_omzet']) ? $data_2026['total_omzet'] : 0;
            
            $target_value_2026 = $total_value_range_2025 * (1 + ($growth_target / 100));
            $selisih = $target_value_2026 - $total_value_range_2026;
            
            // Pengecekan division by zero untuk semua perhitungan persentase
            $persen_pencapaian = 0;
            if ($target_value_2026 > 0) {
                $persen_pencapaian = ($total_value_range_2026 / $target_value_2026 * 100);
            }
            
            $growth_aktual = 0;
            if ($total_value_range_2025 > 0) {
                $growth_aktual = (($total_value_range_2026 - $total_value_range_2025) / $total_value_range_2025 * 100);
            }
            
            $contribution_2025_to_total = 0;
            if ($total_omzet_2025 > 0) {
                $contribution_2025_to_total = ($total_value_range_2025 / $total_omzet_2025 * 100);
            }
            
            $contribution_2026_to_total = 0;
            if ($total_omzet_2026 > 0) {
                $contribution_2026_to_total = ($total_value_range_2026 / $total_omzet_2026 * 100);
            }
            ?>
            
            <!-- Input Growth Target -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">Target Growth</span>
                        <input type="number" id="growth_input" class="form-control" value="<?= $growth_target ?>" min="0" max="1000" step="0.1">
                        <span class="input-group-text">%</span>
                        <button class="btn btn-primary" id="apply_growth">Apply</button>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="alert alert-info mb-0 py-2">
                        <strong>Growth Aktual (Outlet dengan Akumulasi ≤ <?= $max_threshold ?>%):</strong> 
                        <?= number_format($growth_aktual, 2, ',', '.') ?>%
                    </div>
                </div>
            </div>
            
            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="p-2 rounded" style="background-color: var(--bs-dark-bg-subtle);">
                        <small class="text-muted">Value 2025 (≤<?= $max_threshold ?>% Outlet)</small>
                        <h6 class="mb-0">Rp <?= number_format($total_value_range_2025, 0, ',', '.') ?></h6>
                        <small class="text-muted">
                            <?= isset($data_2025['outlet_range']) ? count($data_2025['outlet_range']) : 0 ?> outlet | 
                            <?= number_format($contribution_2025_to_total, 1, ',', '.') ?>% dari total
                        </small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-2 rounded" style="background-color: var(--bs-dark-bg-subtle);">
                        <small class="text-muted">Value 2026 (≤<?= $max_threshold ?>% Outlet)</small>
                        <h6 class="mb-0">Rp <?= number_format($total_value_range_2026, 0, ',', '.') ?></h6>
                        <small class="text-muted">
                            <?= isset($data_2026['outlet_range']) ? count($data_2026['outlet_range']) : 0 ?> outlet | 
                            <?= number_format($contribution_2026_to_total, 1, ',', '.') ?>% dari total
                        </small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-2 rounded" style="background-color: var(--bs-dark-bg-subtle);">
                        <small class="text-muted">Target 2026 (<?= $growth_target ?>% growth)</small>
                        <h6 class="mb-0 <?= ($target_value_2026 > $total_value_range_2026) ? 'text-danger' : 'text-success' ?>">
                            Rp <?= number_format($target_value_2026, 0, ',', '.') ?>
                        </h6>
                        <small class="text-muted">
                            ± Rp <?= number_format(abs($target_value_2026 - $total_value_range_2025), 0, ',', '.') ?> growth
                        </small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-2 rounded" style="background-color: var(--bs-dark-bg-subtle);">
                        <small class="text-muted">Pencapaian Target</small>
                        <h6 class="mb-0 <?= ($persen_pencapaian >= 100) ? 'text-success' : 'text-danger' ?>">
                            <?= number_format($persen_pencapaian, 2, ',', '.') ?>%
                        </h6>
                        <small class="text-muted">
                            <?= ($selisih > 0) ? 'Kurang' : 'Lebih' ?> Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Detailed Analysis -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header py-2" style="background-color: var(--bs-dark-bg-subtle);">
                            <small><strong>Detail Outlet 2025 (≤<?= $max_threshold ?>%)</strong></small>
                        </div>
                        <div class="card-body p-2" style="max-height: 200px; overflow-y: auto;">
                            <?php if (!empty($data_2025['outlet_range'])): ?>
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="60%">Outlet</th>
                                        <th width="20%">Value</th>
                                        <th width="15%">Akum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($data_2025['outlet_range'] as $outlet): 
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><small><?= htmlspecialchars(substr($outlet['nama'], 0, 20)) ?>...</small></td>
                                        <td class="text-right"><small>Rp <?= number_format($outlet['omzet'], 0, ',', '.') ?></small></td>
                                        <td class="text-right"><small><?= number_format($outlet['akumulasi'], 1, ',', '.') ?>%</small></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                                <p class="text-muted text-center">Tidak ada data outlet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header py-2" style="background-color: var(--bs-dark-bg-subtle);">
                            <small><strong>Detail Outlet 2026 (≤<?= $max_threshold ?>%)</strong></small>
                        </div>
                        <div class="card-body p-2" style="max-height: 200px; overflow-y: auto;">
                            <?php if (!empty($data_2026['outlet_range'])): ?>
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="60%">Outlet</th>
                                        <th width="20%">Value</th>
                                        <th width="15%">Akum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($data_2026['outlet_range'] as $outlet): 
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><small><?= htmlspecialchars(substr($outlet['nama'], 0, 20)) ?>...</small></td>
                                        <td class="text-right"><small>Rp <?= number_format($outlet['omzet'], 0, ',', '.') ?></small></td>
                                        <td class="text-right"><small><?= number_format($outlet['akumulasi'], 1, ',', '.') ?>%</small></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                                <p class="text-muted text-center">Tidak ada data outlet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gap Analysis -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <?php if (!empty($data_2025['data']) || !empty($data_2026['data'])): ?>
                        <?php if ($selisih > 0) { ?>
                            <div class="alert alert-warning mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Masih Kurang:</strong> Rp <?= number_format($selisih, 0, ',', '.') ?> 
                                        untuk mencapai target <?= $growth_target ?>% growth pada <?= $max_threshold ?>% outlet
                                    </div>
                                    <div>
                                        <small>Need additional sales: 
                                            <strong>Rp <?= number_format($selisih / max(1, count($data_2026['outlet_range'])), 0, ',', '.') ?></strong> per outlet (rata-rata)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-success mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-check-circle"></i>
                                        <strong>Selamat!</strong> 
                                        Target growth <?= $growth_target ?>% pada <?= $max_threshold ?>% outlet sudah tercapai 
                                        dengan kelebihan Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                                    </div>
                                    <div>
                                        <small>Excess: 
                                            <strong><?= number_format($persen_pencapaian - 100, 1, ',', '.') ?>%</strong> di atas target
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <strong>Info:</strong> Tidak ada data untuk dianalisis
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables with Tabs -->
    <div class="row mt-4">
        <div class="col-md-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="yearTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-2025" data-bs-toggle="tab" data-bs-target="#year-2025" type="button" role="tab">Tahun 2025</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-2026" data-bs-toggle="tab" data-bs-target="#year-2026" type="button" role="tab">Tahun 2026</button>
                </li>
            </ul>
            
            <!-- Tab panes -->
            <div class="tab-content mt-3">
                <?php
                // Fungsi untuk merender tabel per tahun (TELAH DIPERBAIKI)
                function renderYearTable($year, $get_data) {
                    $data_year = array();
                    $total_omzet = 0;
                    
                    foreach ($get_data->result() as $a) {
                        if ($a->tahun == $year) {
                            $data_year[] = $a;
                            $total_omzet += $a->omzet;
                        }
                    }
                    
                    // Jika tidak ada data, tampilkan pesan
                    if (empty($data_year)) {
                        echo '<div class="alert alert-info">Tidak ada data untuk tahun ' . $year . '</div>';
                        return;
                    }
                    
                    // Urutkan descending berdasarkan omzet
                    usort($data_year, function($x, $y) {
                        if ($x->omzet == $y->omzet) return 0;
                        return ($x->omzet < $y->omzet) ? 1 : -1;
                    });
                    
                    $no = 1;
                    $akumulasi_omzet = 0;
                    $akumulasi_contribution = 0;
                    ?>
                    
                    <table id="tabel-data-<?= $year ?>" style="width:100%">    
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Subbranch</th>
                                <th>Outlet</th>
                                <th>Class</th>
                                <th>Type</th>
                                <th>Periode</th>
                                <th>Value <?= $year ?></th>
                                <th>Contribution (%)</th>
                                <th>Akumulasi Omzet</th>
                                <th>Akumulasi Contribution (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_year as $a) : 
                                // Pengecekan division by zero
                                $contribution = ($total_omzet > 0) ? ($a->omzet / $total_omzet * 100) : 0;
                                $akumulasi_omzet += $a->omzet;
                                $akumulasi_contribution += $contribution;
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($a->nama_comp) ?></td>
                                    <td><?= htmlspecialchars($a->nama_outlet) ?></td>
                                    <td><?= $a->nama_class . ' (' . $a->kode_class . ')' ?></td>
                                    <td><?= $a->nama_type . ' (' . $a->kode_type . ')' ?></td>
                                    <td><?= $a->periode ?></td>
                                    <td class="text-right"><?= number_format($a->omzet, 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($contribution, 2, ',', '.') ?>%</td>
                                    <td class="text-right"><?= number_format($akumulasi_omzet, 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($akumulasi_contribution, 2, ',', '.') ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold; background-color: var(--bs-dark-bg-subtle); color: var(--bs-dark-text-emphasis);">
                                <td colspan="6" class="text-right"><strong>TOTAL</strong></td>
                                <td class="text-right"><strong><?= number_format($total_omzet, 0, ',', '.') ?></strong></td>
                                <td class="text-right"><strong>100%</strong></td>
                                <td class="text-right"><strong><?= number_format($total_omzet, 0, ',', '.') ?></strong></td>
                                <td class="text-right"><strong>100%</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php } ?>
                
                <!-- Tab 2025 -->
                <div class="tab-pane active" id="year-2025" role="tabpanel">
                    <?php renderYearTable(2025, $get_data); ?>
                </div>
                
                <!-- Tab 2026 -->
                <div class="tab-pane" id="year-2026" role="tabpanel">
                    <?php renderYearTable(2026, $get_data); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan jQuery UI untuk slider -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    /* ========== INISIALISASI KOMPONEN ========== */
    
    // 1. Initialize DataTables (hanya jika tabel ada)
    if ($('#tabel-data-2025').length) {
        $('#tabel-data-2025').DataTable({
            "pageLength": 10,
            "order": [[6, 'desc']], // Sort by Value column (index 6) descending
            "language": {
                "decimal": ",",
                "thousands": "."
            }
        });
    }
    
    if ($('#tabel-data-2026').length) {
        $('#tabel-data-2026').DataTable({
            "pageLength": 10,
            "order": [[6, 'desc']], // Sort by Value column (index 6) descending
            "language": {
                "decimal": ",",
                "thousands": "."
            }
        });
    }
    
    // 2. Initialize Select2
    $('.select2').select2({
        placeholder: "-- Pilih Site Code --",
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Data tidak ditemukan"; }
        }
    });
    
    
    /* ========== KONFIGURASI SLIDER RANGE ========== */
    
    // Ambil nilai threshold dari URL atau gunakan default
    var minThreshold = <?= isset($_GET['min_threshold']) ? $_GET['min_threshold'] : 0 ?>;
    var maxThreshold = <?= isset($_GET['max_threshold']) ? $_GET['max_threshold'] : 50 ?>;
    
    // Validasi nilai threshold
    minThreshold = Math.max(0, Math.min(100, minThreshold));
    maxThreshold = Math.max(0, Math.min(100, maxThreshold));
    if (minThreshold > maxThreshold) {
        minThreshold = maxThreshold;
    }
    
    // Inisialisasi slider dengan range
    $("#threshold_slider").slider({
        range: true,
        min: 0,
        max: 100,
        values: [minThreshold, maxThreshold],
        slide: function(event, ui) {
            // Update input values saat slider digeser
            $("#min_threshold").val(ui.values[0]);
            $("#max_threshold").val(ui.values[1]);
            $("#slider_values").text(ui.values[0] + "% - " + ui.values[1] + "%");
        }
    });
    
    // Update slider saat input manual diubah
    $("#min_threshold, #max_threshold").on("change", function() {
        updateSliderFromInputs();
    });
    
    
    /* ========== EVENT HANDLERS ========== */
    
    // 1. Handler untuk apply threshold range
    $("#apply_threshold_range").on("click", function() {
        applyThresholdRange();
    });
    
    // 2. Handler untuk apply growth target
    $("#apply_growth").on("click", function() {
        applyGrowthTarget();
    });
    
    // 3. Handler untuk enter key pada semua input
    $("#growth_input, #min_threshold, #max_threshold").on("keypress", function(e) {
        if (e.which == 13) { // Enter key
            var inputId = $(this).attr('id');
            
            switch(inputId) {
                case 'growth_input':
                    applyGrowthTarget();
                    break;
                case 'min_threshold':
                case 'max_threshold':
                    applyThresholdRange();
                    break;
            }
        }
    });
    
    
    /* ========== FUNGSI-FUNGSI ========== */
    
    // Fungsi untuk update slider dari input
    function updateSliderFromInputs() {
        var min = parseInt($("#min_threshold").val()) || 0;
        var max = parseInt($("#max_threshold").val()) || 0;
        
        // Validasi nilai
        min = Math.max(0, Math.min(100, min));
        max = Math.max(0, Math.min(100, max));
        
        // Pastikan min tidak lebih besar dari max
        if (min > max) {
            if ($(this).attr("id") == "min_threshold") {
                min = max;
                $("#min_threshold").val(min);
            } else {
                max = min;
                $("#max_threshold").val(max);
            }
        }
        
        // Update slider dan tampilan
        $("#threshold_slider").slider("values", [min, max]);
        $("#slider_values").text(min + "% - " + max + "%");
    }
    
    // Fungsi untuk apply threshold range
    function applyThresholdRange() {
        var minThreshold = $("#min_threshold").val();
        var maxThreshold = $("#max_threshold").val();
        var currentUrl = window.location.href.split('?')[0];
        var params = new URLSearchParams(window.location.search);
        
        params.set('min_threshold', minThreshold);
        params.set('max_threshold', maxThreshold);
        params.delete('threshold'); // Hapus parameter threshold lama jika ada
        
        window.location.href = currentUrl + '?' + params.toString();
    }
    
    // Fungsi untuk apply growth target
    function applyGrowthTarget() {
        var growth = $('#growth_input').val();
        var currentUrl = window.location.href.split('?')[0];
        var params = new URLSearchParams(window.location.search);
        
        params.set('growth_target', growth);
        
        window.location.href = currentUrl + '?' + params.toString();
    }
    
});
</script>