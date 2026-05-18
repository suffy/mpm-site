<div class="container-fluid mt-4">
  <?php echo form_open($url); ?>
  <!-- <div class="az-content-body pd-lg-l-40 d-flex flex-column"> -->


  <!-- session -->
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
  <!-- session -->

  <div class="card col-md-12">
    <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <?php foreach ($data_ticket->result() as $a) : ?>
              <label for="">No Ticket : <?= $a->nomor_ticket ?></label><br>
              <label for="">Principal : <?= $a->namasupp ?></label><br>
              <label for="">Memo ID | Tgl Memo : <?= $a->memo_id. ' | '.$a->tgl_memo ?></label><br>
              <label for="">Tanggal Naik Harga : <?= $a->tgl_naik ?></label><br>
              <label for="">Created : <?= $a->username.' at ',$a->created_at ?></label>
            <?php endforeach; ?>
          </div>
        </div>
    </div>
  </div>

  <div class="card col-md-12 mt-3">
    <div class="card-body">
      <div class="row">
        <h3><?= $title; ?></h3>
      </div>
      <div class="row mt-3">
          <div class="col-md-2">
              <label for="label">Label / Cluster / Zonasi</label>
          </div>
          <div class="col-md-4">
              <div class="input-group">
                <input type="text" name="label" class="form-control" placeholder="masukkan label ..." required>
              </div>
          </div>
      </div>
        
      <input type="text" name="id_ticket" value="<?= $id_ticket ?>" class="form-control" hidden>
      <input type="text" name="signature_ticket" value="<?= $signature_ticket ?>" class="form-control" hidden>

      <div class="row mt-2">
        <div class="col-md-12">
          <table id="tabel" style="width: 100%">
              <thead>
                  <tr>
                      <th class="text-center" style="width: 1%">
                          <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" style="color: black; background-color: grey">
                      </th>
                      <th class="text-center">site code</th>      
                      <th class="text-center">Subbranch</th>      
                      <th class="text-center">Branch</th>      
                      <th class="text-center">Region</th>      
                  </tr>
              </thead>
              <tbody>     
                  <?php
                  foreach ($get_site_code->result() as $a) : ?>
                  <tr>
                      <td>
                          <center>
                          <input type="checkbox" id="<?= $a->site_code; ?>" name="options[]" value="<?= $a->site_code; ?>">
                          </center>
                      </td>
                      <td><?= $a->site_code ?></td>
                      <td><?= $a->nama_comp ?></td>
                      <td><?= $a->branch_name ?></td>
                      <td><?= $a->region ?></td>
                  </tr>
                  <?php endforeach; ?>   
              </tbody>
          </table>

        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-12">
          <input type="submit" value="Simpan Data" class="btn btn-submit" style="height: 45px; width: 130px;">
        </div>
      </div>

    </div>
  </div>        

  <div class="card col-md-12 mt-3">
    <div class="card-body">

      <div class="row mb-5">
        <table id="tabel2" class="table-striped dataTable" style="width:100%">      
          <thead>
            <tr>                
              <th>No</th>
              <th>label</th>
              <th>site code</th>
              <!-- <th>tanggal aktif</th> -->
              <th>flag harga</th>
              <th>count product</th>
              <th>delete</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1; 
            foreach ($get_data_header->result() as $a) : 
              $site_code_string = $a->site_code;
              $cleaned_string = str_replace(['[', ']', '"', "'"], '', $site_code_string);
              $site_codes_array = explode(',', $cleaned_string);
              $site_codes_array = array_map('trim', $site_codes_array);
              $site_codes_array = array_filter($site_codes_array);
              $total_site_codes = count($site_codes_array);
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <a href="<?= base_url().'products/kenaikan_harga_product/'.$a->signature ?>" class="btn btn-submit btn-sm"><?= $a->label; ?></a>
                </td>
                <td style="white-space: normal; word-wrap: break-word; max-width: 300px;">
                    total <?= $total_site_codes ?> site code : 
                    <?= implode(', ', $site_codes_array) ?>
                </td>
                <!-- <td style="white-space: normal; word-wrap: break-word; max-width: 10px;"><?= $a->tanggal_aktif; ?></td> -->
                <td>
                  <?php
                  if ($a->flag_harga == 1) { ?>
                    <span style="color: #fff; background-color: #28a745;padding: 5px;border-radius: 5px;">TRUE</span>
                  <?php
                  }else{ ?>
                    <span style="color: #fff; background-color: #dc3545;padding: 5px;border-radius: 5px;">FALSE</span>
                  <?php
                  }
                  ?>
                </td>
                <td><?= $a->count_product ?></td>
                <td>
                    <a href="<?= base_url().'products/kenaikan_harga_header_delete/'.$a->signature ?>" class="btn btn-submit-red btn-sm" style="padding-top: 8px;" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>

    </div>
  </div>

  <?php echo form_close(); ?>
</div>

<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            "pageLength": 5000,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 30, 40, 50, 60, 70, 80, -1],
                [10, 20, 30, 40, 50, 60, 70, 80, "All"]
            ],
            scrollX: true,
            scrollY: 500,
            "bLengthChange": false,  // Menyembunyikan dropdown show entries
            "bInfo": false,           // Menyembunyikan informasi "Showing X to Y of Z entries"
            "bPaginate": false        // Menyembunyikan navigasi pagination
        });
        $('#tabel2').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 30, 40, 50, 60, 70, 80, -1],
                [10, 20, 30, 40, 50, 60, 70, 80, "All"]
            ],
            // scrollX: true,
            // scrollY: 200
        });
    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>