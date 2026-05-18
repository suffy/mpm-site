<div class="card" id="search">
  <div class="row">
    <div class="row">
      <div class="col-md-12">
        <?= form_open_multipart($url_search, ['method' => 'get'])?> 
          <div class="row mb-4">
            <div class="col-md-12">
              <h5>Penarikan Report</h5>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <label for="from">Periode</label> 
            </div>
            <div class="col-md-5">
              <div class="input-group">
                <input type="date" name="from" id="from" min="2026-02-01" class="form-control" value="<?= $this->input->get('from');?>" required>
                <input type="date" name="to" id="to" min="2026-02-01" class="form-control" value="<?= $this->input->get('to');?>" required>
              </div>
            </div>
          </div>

          <div class="row mt-1">
            <div class="col-md-2">
              <label for="from">Channel</label> 
            </div>
            <div class="col-md-5">
                <select name="channel_filter" class="form-select" required>
                    <option value="all" <?= $this->input->get('channel_filter') == 'all' ? 'selected' : ''; ?>> ALL </option>
                    <option value="nka" <?= $this->input->get('channel_filter') == 'nka' ? 'selected' : ''; ?>>NKA</option>
                    <option value="pharma" <?= $this->input->get('channel_filter') == 'pharma' ? 'selected' : ''; ?>>PHARMA</option>
                </select>
            </div>
          </div>

          <div class="row mt-1">
            <div class="col-md-2">
              <label for="from">Kategori</label> 
            </div>
            <div class="col-md-5">
                <select class="form-select" name="kategori" id="kategori_filter">
                    <option value="all"> ALL </option>
                    <?php 
                        foreach ($get_kategori->result() as $key) {
                            echo '<option value="'.$key->nama_kategori.'" '.($this->input->get('kategori') == $key->nama_kategori ? 'selected' : '').'>'.$key->nama_kategori.'</option>';
                        }
                    ?>
                </select>
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-lg-2">
            </div>
            <div class="col-lg-9">
                <button type="submit" value="search" name="submit" class="btn btn-submit" style="height: 45px;">Search</button>
                <a href="<?= base_url().'management_claim/ajuan_claim_nka';?>" class="btn btn-submit-black" style="height: 45px;">Reset View</a>
                <button type="submit" value="export" name="submit" class="btn btn-submit-black" style="height: 45px;">Export</button>
            </div>
          </div>
        <?= form_close(); ?>
      </div>
    </div>
  </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-12">
      <table id="tabel-ajuan-claim">
          <thead>
              <tr>
                  <th class="text-center">No Ajuan</th>
                  <th class="text-center">Subbranch</th>
                  <th class="text-center">No Klaim</th>
                  <th class="text-center">No Invoice</th>
                  <th class="text-center">Channel</th>
                  <th class="text-center">Kategori</th>
                  <th class="text-center">Key Account</th>
                  <th class="text-center">Periode</th>
                  <th class="text-center" style="width: 100px;">Status</th>     
              </tr>
          </thead>
          <tbody>     
              <?php foreach ($get_data->result() as $key)  {?>
                  <tr>
                      <td><?= $key->nomor_ajuan;?></td>
                      <td><?= $key->nama_comp;?></td>
                      <td><?= $key->nomor_klaim;?></td>
                      <td><?= $key->nomor_invoice;?></td>
                      <td style="text-transform: uppercase;"><?= $key->channel;?></td>
                      <td><?= $key->kategori;?></td>
                      <td><?= $key->channel == 'nka' ? $key->key_account : '-';?></td>
                      <td>
                          <?php
                              if($key->periode_end != null){
                                  echo date( 'd M Y', strtotime($key->periode_start)) . ' - ' . date( 'd M Y', strtotime($key->periode_end));
                              } else {
                                  echo date( 'M Y', strtotime($key->periode_start));
                              }
                          ;?>
                      </td>
                      <td style="text-transform: uppercase;">
                          <?php 
                              if ($key->status == 1) { // PROSES PENDING KAM
                                  $color = "btn-warning btn-sm rounded";
                              } elseif($key->status == 2){ // PROSES PENDING MPM
                                  $color = "btn-warning btn-sm rounded";
                              } elseif($key->status == 3){ // PROSES REJECT KAM
                                  $color = "btn-danger btn-sm rounded"; 
                              } elseif($key->status == 4){ // PROSES PENDING ADMIN MPM
                                  $color = "btn-dark btn-sm rounded";
                              } elseif($key->status == 11){ // REJECT KAM PRINCIPAL
                                  $color = "btn-danger btn-sm rounded";
                              } elseif($key->status == 12){ // REJECT MPM
                                  $color = "btn-danger btn-sm rounded";
                              } elseif($key->status == 13){ // REJECT KAM MPM
                                  $color = "btn-danger btn-sm rounded";
                              } elseif($key->status == 15){ // REJECT KAM MPM
                                  $color = "btn-danger btn-sm rounded";
                              } elseif($key->status == 16){ // REJECT PRINCIPAL
                                  $color = "btn-danger btn-sm rounded";
                              } else{
                                  $color = "btn-danger btn-sm rounded";
                              }                   
                          ?>
                          <?php 
                          if($key->status == 4)
                          { ?>
                              <a href='<?= base_url("$url_detail/$key->signature"); ?>'class="btn <?= $color ?> btn-sm" target="_blank">
                                  <div class="font-btn"><?= $key->nama_status ?></div>
                              </a>
                          <?php
                          }else
                          { ?>
                              <a href='<?= base_url("$url_detail/$key->signature"); ?>'class="btn <?= $color ?> btn-sm" target="_blank">
                                  <div class="font-btn"><?= $key->nama_status." - ".$key->on_duty_name ?></div>
                              </a>
                          <?php
                          }
                          ?>
                    </tr>
              <?php } ?>
          </tbody>
      </table>
    </div>
  </div>
</div>


<script>
    $(document).ready(function () {
        $('#tabel-ajuan-claim').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // scrollX: true,
        });
    });

</script>