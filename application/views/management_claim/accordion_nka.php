<style>
  #divLog, #divDokumentasi 
  {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: max-height 0.5s ease, opacity 0.5s ease;
  }

  #divLog.show, #divDokumentasi.show 
  {
    max-height: 100%;
    opacity: 1;
    transition: all 0.15s ease-in-out;
    margin-top: 1rem;
    margin-bottom: 1rem;
  }

  .card{
    border-radius: 10px !important;
    border-width: 3px !important;
    border-color: var(--bs-light-border-subtle) !important;
  }
</style>
</div>
<div class="container-fluid">
  <div class="col-md-12">        
    <div class="row mb-3">
      <div class="col-md-12">
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

    <button onclick="toggleSection('divLog')" class="btn btn-submit" type="button" style="border: none; border-radius: 10px;">Lihat History</button>

    <!-- Log History -->
    <div class="row mt-3 show" id="divLog">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <label><strong>History Status</strong></label>
            <div class="table-responsive">
              <table id="tabel-log-history">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">User->On Duty</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Created At</th>
                        <th class="text-center">Status</th>      
                    </tr>
                </thead>
                <tbody> 
                  <?php 
                  $no = 1;
                  foreach ($get_log->result() as $key => $value) {?>
                      <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td><?= implode(' / ',$user[$key]); ?><strong> -> <?= implode(' / ',$pic[$key]); ?></strong></td>
                          <td><?= $value->keterangan ?></td>
                          <td class="text-center"><?= date('d M Y', strtotime($value->created_at)); ?></td>
                          <td class="text-center"><?= $value->nama_status ?></td>
                      </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail pengajuan -->
    <div class="row mt-3" id="divDetail">
      <div class="col-md-6">
        <div class="card" style="text-transform: capitalize;">
          <div class="card-body">
            <div class="row mt-1">
              <div class="col-md-12">
                <label><strong>Data Pengajuan Claim</strong></label>
              </div>
            </div>

            <div class="row mt-1">
              <div class="col-md-4">
                <label>Status</label>
              </div>
              <div class="col-md-8">
                <p style="text-transform: uppercase;"><?= $get_data->row()->nama_status.' - '.$get_data->row()->username_on_duty ?></p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Nomor Klaim</label>
              </div>
              <div class="col-md-8">
                <p><?= $get_data->row()->nomor_ajuan?></p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Nomor Invoice/ SKP/ Trading Term</label>
              </div>
              <div class="col-md-8">
                <p><?= $get_data->row()->nomor_invoice?></p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Channel</label>
              </div>
              <div class="col-md-8">
                <p style="text-transform: uppercase;"><?= $get_data->row()->channel?></p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Kategori</label>
              </div>
              <div class="col-md-8">
                <p><?= $get_data->row()->kategori?></p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Key Account</label>
              </div>
              <div class="col-md-8">
                <p><?= $get_data->row()->channel == 'nka' ? $get_data->row()->key_account : '-';?></p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Periode</label>
              </div>
              <div class="col-md-8">
                <p><?= $get_data->row()->periode_start .' - '. $get_data->row()->periode_end ?></p>
              </div>
            </div>

              <div class="row">
                <div class="col-md-4">
                  <label>Keterangan</label>
                </div>
                <div class="col-md-8">
                  <p><?= $get_data->row()->keterangan?></p>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <label>Nominal Claim</label>
                </div>
                <div class="col-md-8">
                  <p>Rp. <?= number_format($get_data->row()->nominal_dpp)?></p>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <label>Site Code</label>
                </div>
                <div class="col-md-8">
                  <p><?= $get_data->row()->site_code?></p>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <label>PIC</label>
                </div>
                <div class="col-md-8">
                  <p><?= $get_data->row()->pic_nama?></p>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <label>Email PIC</label>
                </div>
                <div class="col-md-8">
                  <p style="text-transform: none;"><?= $get_data->row()->pic_email?></p>
                </div>
              </div>

              <div class="row">
                  <div class="col-md-4">
                    <label>Attachment</label>
                  </div>
                  <div class="col-md-8"><?php  $no = 1;
                    $attachment = json_decode($get_data->row()->attachment);
                    foreach ($attachment as $key_attachment) {?>
                        <?= $no++ .'.' ?>
                        <a href="<?= base_url() . 'assets/uploads/management_claim/nka/' .$get_data->row()->kategori .'/'. $key_attachment ?>" style="text-transform: none; text-decoration: none; color: #FFB33F;">
                            <?= $key_attachment ?>
                            
                        </a>
                        <br>
                    <?php } ?>
                  </div>
              </div>
          </div>
        </div>
      </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>Data Verifikasi Principal & MPM</strong></label>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-4">
                            <label>Verifikasi KAM</label>
                        </div>
                        <div class="col-md-8">
                            <p><strong><?= $get_data->row()->username_principal ?></strong> 
                            <?php 
                                if($get_data->row()->principal_at)
                                {
                                    echo " - ".date('d M Y', strtotime($get_data->row()->principal_at));
                                }else{  
                                    echo "";
                                }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Status</label>
                        </div>
                        <div class="col-md-8">
                            <?php 
                                if($get_data->row()->principal_status == 1){ ?>
                                    <span class='badge badge-success'><?= $get_data->row()->principal_nama_status; ?></span>
                                <?php
                                }elseif($get_data->row()->principal_status == "0"){ ?>
                                    <span class='badge badge-danger'><?= $get_data->row()->principal_nama_status; ?></span>
                                <?php
                                }elseif($get_data->row()->principal_status == null){ ?>
                                    <p><i>belum ada</i></p>
                                <?php
                                }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Keterangan</label>
                        </div>
                        <div class="col-md-8">
                            <?php 
                                if($get_data->row()->principal_keterangan)
                                {
                                    echo $get_data->row()->principal_keterangan;
                                }else{
                                    echo "<i>belum ada</i>";
                                }
                            ?>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Verifikasi <?= $get_data->row()->channel == 'nka' ? "MPM" : "Principal"; ?></label>
                        </div>
                        <div class="col-md-8">
                            <p><strong><?= $get_data->row()->username_mpm ?></strong> 
                            <?php 
                                if($get_data->row()->mpm_at)
                                {
                                    echo " - ".date('d M Y', strtotime($get_data->row()->mpm_at));
                                }else{
                                    echo "";
                                }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Status</label>
                        </div>
                        <div class="col-md-8">
                            <?php 
                                if($get_data->row()->mpm_status == 1){ ?>
                                    <span class='badge badge-success'><?= $get_data->row()->mpm_nama_status; ?></span>
                                <?php
                                }elseif($get_data->row()->mpm_status == '0'){ ?>
                                    <span class='badge badge-danger'><?= $get_data->row()->mpm_nama_status; ?></span>
                                <?php
                                }elseif($get_data->row()->mpm_status == null){ ?>
                                    <p><i>belum ada</i></p>
                                <?php
                                }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Keterangan</label>
                        </div>
                        <div class="col-md-8">
                            <?php 
                                if($get_data->row()->mpm_keterangan)
                                {
                                    echo $get_data->row()->mpm_keterangan;
                                }else{
                                    echo "<i>belum ada</i>";
                                }
                            ?>
                        </div>
                    </div>                       
                    
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Verifikasi Admin MPM</label>
                        </div>
                        <div class="col-md-8">
                            <p><strong><?= $get_data->row()->username_admin_mpm ?></strong> 
                            <?php 
                                if($get_data->row()->admin_mpm_at)
                                {
                                    echo " - ".date('d M Y', strtotime($get_data->row()->admin_mpm_at));
                                }else{
                                    echo "";
                                }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Status</label>
                        </div>
                        <div class="col-md-8">
                            <?php 
                                if($get_data->row()->admin_mpm_status == 1){ ?>
                                    <span class='badge badge-success'><?= $get_data->row()->admin_mpm_nama_status; ?></span>
                                <?php
                                }elseif($get_data->row()->admin_mpm_status == '0'){ ?>
                                    <span class='badge badge-danger'><?= $get_data->row()->admin_mpm_nama_status; ?></span>
                                <?php
                                }elseif($get_data->row()->admin_mpm_status == null){ ?>
                                    <p><i>belum ada</i></p>
                                <?php
                                }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Keterangan</label>
                        </div>
                        <div class="col-md-8">
                            <?php 
                                if($get_data->row()->admin_mpm_keterangan)
                                {
                                    echo $get_data->row()->admin_mpm_keterangan;
                                }else{
                                    echo "<i>belum ada</i>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </div>


<script>
  $(document).ready(function () {
    $('#tabel-log-history').DataTable({
      "pageLength": 10,
      "ordering": true,
      "order": [0, 'desc'],
      "aLengthMenu": [
          [10, 20, 50, -1],
          [10, 20, 50, "All"]
      ],
      // scrollX: true,
    });

    $('#tabel-dokumentasi').DataTable({
      info: false,
      paging: false,     // menghilangkan pagination
      searching: false,   // menghilangkan search box
        ordering: false
    });
  });
</script>

<script>
  function toggleSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
      element.classList.toggle('show');
    }
  }
</script>