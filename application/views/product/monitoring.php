<style>
  /* Custom CSS untuk price comparison */
  .price-comparison {
      font-size: 0.9rem;
      line-height: 1.6;
  }

  .price-comparison .badge {
      font-size: 0.7rem;
      margin-right: 5px;
      padding: 3px 6px;
  }

  .price-comparison .price-value {
      font-weight: 500;
  }

  .table td {
      vertical-align: middle;
  }

  /* Hover effect untuk baris */
  .table-hover tbody tr:hover {
      background-color: rgba(0,123,255,0.1) !important;
  }

  /* Custom untuk summary cards */
  .small-box {
      border-radius: 0.5rem;
      box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
      margin-bottom: 20px;
      position: relative;
      display: block;
      transition: all 0.3s;
  }

  .small-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  }

  .small-box > .inner {
      padding: 15px;
  }

  .small-box h3 {
      font-size: 2.2rem;
      font-weight: bold;
      margin: 0 0 10px 0;
      white-space: nowrap;
      padding: 0;
      color: white;
  }

  .small-box p {
      color: white;
      font-size: 1rem;
      margin: 0;
  }

  .small-box .icon {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 3rem;
      color: rgba(255,255,255,0.5);
  }

  /* Warna cards */
  .bg-info { background-color: #17a2b8 !important; }
  .bg-success { background-color: #28a745 !important; }
  .bg-warning { background-color: #ffc107 !important; }
  .bg-danger { background-color: #dc3545 !important; }

  /* Badge colors */
  .badge-success { background-color: #28a745; color: white; }
  .badge-danger { background-color: #dc3545; color: white; }
  .badge-warning { background-color: #ffc107; color: black; }
  .badge-secondary { background-color: #6c757d; color: white; }

  /* Text colors */
  .text-danger { color: #dc3545 !important; }
  .text-success { color: #28a745 !important; }

  .card{
    border-radius: 10px;
    padding: 20px;
    /* box-shadow: 0 2px 5px rgba(0,0,0,0.1); */
    flex: 1;
    border: 4px solid var(--bs-light-bg-subtle);
  }


</style>

</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php
      if ($this->session->flashdata('pesan')) { ?>
          <div class="alert alert-danger" role="alert">
              <?= $this->session->flashdata('pesan'); ?>
          </div>
      <?php
      } elseif ($this->session->flashdata('pesan_success')) { ?>
          <div class="alert alert-success" role="alert">
              <?= $this->session->flashdata('pesan_success'); ?>
          </div>
      <?php
      }
      ?>
    </div>
  </div>

  <div class="card col-md-12">
    <div class="card-body">
      <h2 id="form_spk"><?= $title; ?></h2>
        <div class="row mt-4">
          <div class="col-md-2">
            <label for="label">Nomor Ticket | Status</label>
          </div>
          <div class="col-md-4">
            <div class="input-group">
              <strong><?= $nomor_ticket.' | '.$nama_status.' - '.$on_duty_username ; ?></strong>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <label for="label">Request By</label>
          </div>
          <div class="col-md-4">
            <div class="input-group">
              <?= $created_by_username.'&nbsp;<i> at '.$created_at.'</i>' ; ?>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <label for="label">Principal</label>
          </div>
          <div class="col-md-4">
            <div class="input-group">
              <?= $namasupp; ?>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <label for="label">Keterangan</label>
          </div>
          <div class="col-md-4">
            <div class="input-group">
              <?= $keterangan; ?>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <label for="label">File</label>
          </div>
          <div class="col-md-4">
            <div class="d-flex gap-2">
              <a href="<?= base_url() . "assets/uploads/kenaikan_harga/$file" ?>" target="_blank" class="btn btn-submit pending-rilis-po btn-sm" style="padding-top: 8px;">File Utama</a>
                <?php
                if ($attachments) {
                  $dataArray = json_decode($attachments, true);
                  $no = 1;
                  if (json_last_error() === JSON_ERROR_NONE) {
                      foreach ($dataArray as $fileName) 
                      {
                        $link = base_url("assets/uploads/kenaikan_harga/$fileName");
                        echo "
                        <a href='$link' target='_blank'>
                            <button class='btn btn-submit pending-rilis-po btn-sm' target='_blank'>Attachment $no</button>
                        </a>
                        ";
                        $no++;
                      }
                  }
                }
                ?>
            </div>
          </div>
        </div>
        <hr>
        
        <?php echo form_open($url); ?>
        <input type="text" name="id_ticket" value="<?= $id_ticket ?>" class="form-control" hidden>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="status">Override Status to</label>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-control" id="status" required>
                    <option value=""> -- Pilih Status -- </option>
                    <option value="2">PENDING APPROVAL / menunggu approval pembuat ticket</option>
                    <option value="20">OPEN API / membuka akses dp hit api</option>
                    <option value="90">CLOSE API / menutup akses dp hit api</option>
                    <option value="10">APPROVE / menyetujui harga yang di setup</option>
                    <option value="99">REJECT / menolak data harga yang di setup</option>
                </select>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <!-- Label kosong untuk menjaga alignment -->
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input type="checkbox" name="disclaimer" class="form-check-input" id="disclaimer" required>
                    <label class="form-check-label" for="disclaimer">
                        Saya sudah re-check seluruh harga di bawah ini
                    </label>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <!-- Label kosong untuk menjaga alignment -->
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" id="btnKirim" name="btnKirim" value="90">Override Status</button>
            </div>
        </div>

        <?php echo form_close(); ?>

    </div>
  </div>     
        
  <div class="card col-md-12 mt-2">
    <div class="card-body">
      <div class="mt-2 mb-4"><h5 class="card-title">Zona / Cluster Harga</h5></div>   
        <?php 
          $cluster_count = 0;
          foreach ($get_header->result() as $key) { 
          $cluster_count++;
          $site_code_string = $key->site_code;
          $cleaned_string = str_replace(['[', ']', '"', "'"], '', $site_code_string);
          $site_codes_array = explode(',', $cleaned_string);
          $site_codes_array = array_map('trim', $site_codes_array);
          $site_codes_array = array_filter($site_codes_array);
          $total_site_codes = count($site_codes_array);
          $id_header = $key->id;
        ?>

        <div class="row">
          <div class="col-md-8">
            <strong><?= $key->label.' ('.$total_site_codes.')'; ?></strong>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <?= implode(', ', $site_codes_array) ?>
          </div>
        </div>

        <?php
          $get_detail = $this->model_products->get_kenaikan_harga_detail_by_id_header($id_header);
          if ($get_detail->num_rows() > 0) { ?>                               
            <div class="row mt-4 mb-5">
              <table id="tabel_cluster_<?= $cluster_count ?>" class="table table-striped" style="width:100%">    
                <thead>
                  <tr>                
                    <th>No</th>
                    <th>Kodeprod</th>
                    <th>Namaprod</th>
                    <th>Harga Jual Grosir</th>
                    <th>Harga Jual Retail</th>
                    <th>Harga Jual Motoris Retail</th>
                    <th>Harga Jual MT</th>                        
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1; 
                  foreach ($get_detail->result() as $a) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $a->kodeprod; ?></td>
                        <td><?= $a->namaprod; ?></td>
                        <td><?= $a->harga_jual_grosir; ?></td>
                        <td><?= $a->harga_jual_retail; ?></td>
                        <td><?= $a->harga_jual_motoris_retail; ?></td>
                        <td><?= $a->harga_jual_mt; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <?php
          } ?>
          <div class="row">
            <div class="col-md-12"><hr></div>
          </div>
                        
    <?php } ?>
        </div>
      </div>  

      <div class="card col-md-12 mt-2">
        <div class="card-body">
          <div class="mt-2 mb-4"><h5 class="card-title">DP Belum HIT API GET</h5></div>   
            <div class="row mt-4 mb-5">
              <table id="tabel_api_get_not_in" class="table table-striped" style="width:100%">    
                <thead>
                    <tr>                
                        <th style="width: 2px;">No</th>
                        <th>Branch</th>
                        <th>Subbranch</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1; 
                foreach ($get_not_in->result() as $a) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $a->branch_name; ?></td>
                        <td><?= $a->nama_comp.' - '.$a->site_code_registered; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
        
      <div class="card col-md-12 mt-2">
        <div class="card-body">
          <div class="mt-2 mb-4"><h5 class="card-title">Monitoring API Get</h5></div>   
            <div class="row mt-4 mb-5">
              <table id="tabel_api_get" class="table table-striped" style="width:100%">    
                <thead>
                    <tr>                
                        <th style="width: 5px;">No</th>
                        <th style="width: 5px;">Site_code</th>
                        <th>Subbranch</th>
                        <th>CreatedAt</th>                
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1; 
                foreach ($get_hit_api_get->result() as $a) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $a->site_code; ?></td>
                        <td><?= $a->nama_comp; ?></td>
                        <td><?= $a->created_at; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- <div class="card col-md-12 mt-2 mb-5">
          <div class="card-body">
            <div class="mt-2 mb-4">
              <h5 class="card-title">Monitoring API Feedback</h5>
              <a href="<?= base_url() ?>products/updating_monitoring_feedback/<?= $signature_ticket; ?>">update data</a>
            </div>  
            <div class="row mt-4 mb-5">
              <table id="tabel_api_feedbacks" class="table table-striped" style="width:100%">    
                  <thead>
                    <tr>                
                      <th>Subbranch</th>
                      <th>Kodeprod</th>                
                      <th>hargaJualGrosir</th>                
                      <th>hargaJualRetail</th>                  
                      <th>hargaJualMotorisRetail</th>                  
                      <th>hargaJualMT</th>                      
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $no = 1; 
                  foreach ($get_monitoring_feedback->result() as $a) : ?>
                      <tr>
                          <td>
                            <?= $a->nama_comp; ?>
                            
                            <?php 
                            //jika ada data yang tidak sama, maka warna merah
                            if ($a->harga_jual_grosir != $a->harga_jual_grosir_feedback) {
                                echo '<span class="badge badge-danger">grosir</span>';
                            }
                            if ($a->harga_jual_retail != $a->harga_jual_retail_feedback) {
                                echo '<span class="badge badge-danger">retail</span>';
                            }
                            if ($a->harga_jual_motoris_retail != $a->harga_jual_motoris_retail_feedback) {
                                echo '<span class="badge badge-danger">motoris retail</span>';
                            }
                            if ($a->harga_jual_mt != $a->harga_jual_mt_feedback) {
                                echo '<span class="badge badge-danger">mt</span>';
                            }
                            ?>
                            <span></span>
                          </td>
                          <td><?= $a->kodeprod; ?></td>
                          <td><?= $a->harga_jual_grosir.' => '.($a->harga_jual_grosir_feedback ? $a->harga_jual_grosir_feedback : 'null'); ?></td>
                          <td><?= $a->harga_jual_retail.' => '.($a->harga_jual_retail_feedback ? $a->harga_jual_retail_feedback : 'null'); ?></td>
                          <td><?= $a->harga_jual_motoris_retail.' => '.($a->harga_jual_motoris_retail_feedback ? $a->harga_jual_motoris_retail_feedback : 'null'); ?></td>
                          <td><?= $a->harga_jual_mt.' => '.($a->harga_jual_mt_feedback ? $a->harga_jual_mt_feedback : 'null'); ?></td>
                      </tr>
                  <?php endforeach; ?>
                  </tbody>
              </table>
            </div> 
          </div>
        </div> -->
      </div>
<!-- </div> -->


<div class="card col-md-12 mt-2 mb-5">
    <div class="card-body">
        <div class="mt-2 mb-4">
            <h5 class="card-title">Monitoring API Feedback</h5>
            <a href="<?= base_url() ?>products/updating_monitoring_feedback/<?= $signature_ticket; ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-sync-alt"></i> Update Data
            </a>
        </div>  
        
        <div class="row mt-4 mb-5">
            <div class="col-md-12">
                <!-- Summary Cards -->
                <!-- Summary Cards dengan 1 kali loop -->
<div class="row mb-4">
    <?php
    $total_data = $get_monitoring_feedback->num_rows();
    $total_aman = 0;
    $total_beda = 0;
    $total_null = 0;
    
    foreach ($get_monitoring_feedback->result() as $a) {
        if (is_null($a->harga_jual_grosir_feedback)) {
            $total_null++;
        } else {
            // Cek apakah semua harga sesuai
            if ($a->harga_jual_grosir == $a->harga_jual_grosir_feedback &&
                $a->harga_jual_retail == $a->harga_jual_retail_feedback &&
                $a->harga_jual_motoris_retail == $a->harga_jual_motoris_retail_feedback &&
                $a->harga_jual_mt == $a->harga_jual_mt_feedback) {
                $total_aman++;
            } else {
                $total_beda++;
            }
        }
    }
    ?>
    
    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $total_data; ?></h3>
                <p>Total Data</p>
            </div>
            <div class="icon">
                <i class="fas fa-database"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $total_aman; ?></h3>
                <p>Data Sesuai</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $total_beda; ?></h3>
                <p>Data Berbeda</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $total_null; ?></h3>
                <p>Belum Feedback</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
</div>

<!-- Debug info untuk verifikasi -->
<div class="row mb-2">
    <div class="col-md-12">
        <div class="alert alert-info">
            <strong>Summary:</strong> 
            Total: <?= $total_data; ?> | 
            Sesuai: <?= $total_aman; ?> | 
            Berbeda: <?= $total_beda; ?> | 
            Belum Feedback: <?= $total_null; ?> |
            Validasi: <?= ($total_aman + $total_beda + $total_null == $total_data) ? '✓' : '✗'; ?>
        </div>
    </div>
</div>

                <!-- Tabel Monitoring Feedback -->
                <table id="tabel_api_feedback" class="table table-hover table-bordered" style="width:100%">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th style="width: 5px;">No</th>
                            <th>Subbranch</th>
                            <th>Kodeprod</th>
                            <th>Namaprod</th>
                            <th>Harga Jual Grosir</th>
                            <th>Harga Jual Retail</th>
                            <th>Harga Jual Motoris Retail</th>
                            <th>Harga Jual MT</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1; 
                    foreach ($get_monitoring_feedback->result() as $a) : 
                        // Cek apakah semua harga sesuai
                        $all_match = ($a->harga_jual_grosir == $a->harga_jual_grosir_feedback &&
                                     $a->harga_jual_retail == $a->harga_jual_retail_feedback &&
                                     $a->harga_jual_motoris_retail == $a->harga_jual_motoris_retail_feedback &&
                                     $a->harga_jual_mt == $a->harga_jual_mt_feedback);
                        
                        // Cek apakah ada feedback
                        $has_feedback = !is_null($a->harga_jual_grosir_feedback);
                        
                        // Tentukan class untuk baris
                        $row_class = '';
                        if (!$has_feedback) {
                            $row_class = 'table-secondary'; // Belum feedback
                        } elseif ($all_match) {
                            $row_class = 'table-success'; // Data sesuai
                        } else {
                            $row_class = 'table-warning'; // Data berbeda
                        }
                    ?>
                        <tr class="<?= $row_class ?>">
                            <td><?= $no++; ?></td>
                            <td>
                                <strong><?= $a->nama_comp; ?></strong>
                                <?php if (!$has_feedback): ?>
                                    <br><small class="text-muted">(Belum feedback)</small>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= $a->kodeprod; ?></strong></td>
                            <td><?= $a->namaprod; ?></td>
                            
                            <!-- Harga Jual Grosir -->
                            <td>
                                <div class="price-comparison">
                                    <span class="badge badge-secondary">Request</span>
                                    <span class="price-value"><?= number_format($a->harga_jual_grosir, 0, ',', '.'); ?></span>
                                    <br>
                                    <?php if ($has_feedback): ?>
                                        <span class="badge <?= ($a->harga_jual_grosir == $a->harga_jual_grosir_feedback) ? 'badge-success' : 'badge-danger'; ?>">
                                            Feedback
                                        </span>
                                        <span class="price-value <?= ($a->harga_jual_grosir != $a->harga_jual_grosir_feedback) ? 'text-danger font-weight-bold' : ''; ?>">
                                            <?= number_format($a->harga_jual_grosir_feedback, 0, ',', '.'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Feedback</span>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <!-- Harga Jual Retail -->
                            <td>
                                <div class="price-comparison">
                                    <span class="badge badge-secondary">Request</span>
                                    <span class="price-value"><?= number_format($a->harga_jual_retail, 0, ',', '.'); ?></span>
                                    <br>
                                    <?php if ($has_feedback): ?>
                                        <span class="badge <?= ($a->harga_jual_retail == $a->harga_jual_retail_feedback) ? 'badge-success' : 'badge-danger'; ?>">
                                            Feedback
                                        </span>
                                        <span class="price-value <?= ($a->harga_jual_retail != $a->harga_jual_retail_feedback) ? 'text-danger font-weight-bold' : ''; ?>">
                                            <?= number_format($a->harga_jual_retail_feedback, 0, ',', '.'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Feedback</span>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <!-- Harga Jual Motoris Retail -->
                            <td>
                                <div class="price-comparison">
                                    <span class="badge badge-secondary">Request</span>
                                    <span class="price-value"><?= number_format($a->harga_jual_motoris_retail, 0, ',', '.'); ?></span>
                                    <br>
                                    <?php if ($has_feedback): ?>
                                        <span class="badge <?= ($a->harga_jual_motoris_retail == $a->harga_jual_motoris_retail_feedback) ? 'badge-success' : 'badge-danger'; ?>">
                                            Feedback
                                        </span>
                                        <span class="price-value <?= ($a->harga_jual_motoris_retail != $a->harga_jual_motoris_retail_feedback) ? 'text-danger font-weight-bold' : ''; ?>">
                                            <?= number_format($a->harga_jual_motoris_retail_feedback, 0, ',', '.'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Feedback</span>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <!-- Harga Jual MT -->
                            <td>
                                <div class="price-comparison">
                                    <span class="badge badge-secondary">Request</span>
                                    <span class="price-value"><?= number_format($a->harga_jual_mt, 0, ',', '.'); ?></span>
                                    <br>
                                    <?php if ($has_feedback): ?>
                                        <span class="badge <?= ($a->harga_jual_mt == $a->harga_jual_mt_feedback) ? 'badge-success' : 'badge-danger'; ?>">
                                            Feedback
                                        </span>
                                        <span class="price-value <?= ($a->harga_jual_mt != $a->harga_jual_mt_feedback) ? 'text-danger font-weight-bold' : ''; ?>">
                                            <?= number_format($a->harga_jual_mt_feedback, 0, ',', '.'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Feedback</span>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="text-center">
                                <?php if (!$has_feedback): ?>
                                    <span class="badge badge-secondary">⏳ Menunggu Feedback</span>
                                <?php elseif ($all_match): ?>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Sesuai
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Berbeda
                                    </span>
                                <?php endif; ?>
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
    $(document).ready(function() {
        // Initialize DataTable for each cluster table dynamically
        <?php for($i = 1; $i <= $cluster_count; $i++): ?>
            $('#tabel_cluster_<?= $i ?>').DataTable({
                "pageLength": 10,
                "order": [[0, 'asc']],
                "responsive": true,
                "dom": '<"top"f>rt<"bottom"lip><"clear">'
            });
        <?php endfor; ?>
        
        $('#tabel_api_get').DataTable({
            "pageLength": 10,
            "order": [[0, 'asc']],
            "responsive": true
        });

        $('#tabel_api_get_not_in').DataTable({
            "pageLength": 10,
            "order": [[0, 'asc']],
            "responsive": true
        });
        
        // $('#tabel_api_feedback').DataTable({
        //     "pageLength": 100,
        //     "order": [[0, 'asc']],
        //     "responsive": true
        // });
        
        $('#form-helpdesk').hide();
    });
</script>

<script>
$(document).ready(function() {
    $('#tabel_api_feedback').DataTable({
        "pageLength": 100,
        "order": [[0, 'asc']],
        "responsive": true,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "columnDefs": [
            {
                "targets": [4,5,6,7], // Kolom harga
                "className": "text-right"
            },
            {
                "targets": [8], // Kolom status
                "className": "text-center"
            }
        ]
    });
});
</script>