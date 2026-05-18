<style>
:root {
  --primary-color: #3498db;
  --secondary-color: #2c3e50;
  --success-color: #2ecc71;
  --warning-color: #f39c12;
  --danger-color: #e74c3c;
  --light-color: #ecf0f1;
  --dark-color: #34495e;
}

.info-box {
  background: linear-gradient(135deg, #3498db, #2c3e50);
  color: white;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 20px;
}

.filter-section {
  background-color: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.form-control, .form-select {
  border-radius: 6px;
  border: 1px solid #ddd;
  padding: 8px 12px;
}

.btn-primary {
  background-color: var(--primary-color);
  border: none;
  border-radius: 6px;
  padding: 8px 16px;
  font-weight: 500;
}

.btn-outline-secondary {
  border-radius: 6px;
  padding: 8px 16px;
  font-weight: 500;
}

.status-badge {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
}

.status-pending {
  background-color: #fff3cd;
  color: #856404;
}

.status-processing {
  background-color: #cce7ff;
  color: #004085;
}

.status-completed {
  background-color: #d4edda;
  color: #155724;
}

.status-rejected {
  background-color: #f8d7da;
  color: #721c24;
}

.action-buttons .btn {
  margin-right: 5px;
  padding: 5px 10px;
  font-size: 0.85rem;
}

.deadline-warning {
  color: var(--danger-color);
  font-weight: 600;
}

.deadline-normal {
  color: var(--success-color);
  font-weight: 600;
}

.delete-btn {
  color: var(--danger-color);
  background: none;
  border: none;
  font-size: 1.1rem;
  cursor: pointer;
}

.delete-btn:hover {
  color: #c0392b;
}
</style>


<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-md-12">
      <h4><?= $title ?></h4>
    </div>
  </div>

  <div class="row mt-1">
    <div class="col-md-12 az-content-label text-center">
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

  <div class="row mb-2  ">
      <div class="col-md-12">
          <div class="info-box">
              <h5><i class="fas fa-info-circle me-2"></i>Information</h5>
              <ul class="mb-0 mt-2">
                  <li>Deadline barang sampai = 60 hari sejak approval dari principal HO</li>
                  <li>Sisa hari = deadline barang sampai - tanggal sekarang</li>
              </ul>
          </div>
      </div>
  </div>

  <div class="row mt-2">
      <div class="col-md-12">
          <div class="form-inline row">
              <div class="col-sm-12 text-center">
                  <form action="<?= $url_search ?>">
                      From
                      <input class="form-control" type="date" name="from" value="<?= $this->input->get('from') ?>"
                          required />
                      To
                      <input class="form-control" type="date" name="to" value="<?= $this->input->get('to') ?>"
                          required />
                      <select name="status" class="form-control">
                          <option value="0" <?= $this->input->get('status') == 0 ? 'selected' : '' ?>> All Status
                          </option>
                          <option value="1" <?= $this->input->get('status') == 1 ? 'selected' : '' ?>> Pending DP
                          </option>
                          <option value="2" <?= $this->input->get('status') == 2 ? 'selected' : '' ?>> Pending MPM
                          </option>
                          <option value="3" <?= $this->input->get('status') == 3 ? 'selected' : '' ?>> Pending
                              Principal Area </option>
                          <option value="4" <?= $this->input->get('status') == 4 ? 'selected' : '' ?>> Pending
                              Principal HO </option>
                          <option value="5" <?= $this->input->get('status') == 5 ? 'selected' : '' ?>> Pending Kirim
                              Barang </option>
                          <option value="6" <?= $this->input->get('status') == 6 ? 'selected' : '' ?>> Pending Terima
                              Barang </option>
                          <option value="8" <?= $this->input->get('status') == 8 ? 'selected' : '' ?>> Barang di
                              Terima </option>
                          <option value="7" <?= $this->input->get('status') == 7 ? 'selected' : '' ?>> Pending
                              Pemusnahan </option>
                          <option value="9" <?= $this->input->get('status') == 9 ? 'selected' : '' ?>> Pemusnahan
                              Selesai </option>
                          <option value="10" <?= $this->input->get('status') == 10 ? 'selected' : '' ?>> Reject
                              Principal Ho </option>
                          <option value="11" <?= $this->input->get('status') == 11 ? 'selected' : '' ?>> Retur Sample
                          </option>
                          <option value="12" <?= $this->input->get('status') == 12 ? 'selected' : '' ?>> Pemusanahan Tervalidasi
                          </option>
                          <option value="13" <?= $this->input->get('status') == 13 ? 'selected' : '' ?>> Reject
                          </option>
                      </select>
                      <button type="submit" value="1" class="btn btn-outline-danger btn-sm"
                          name="type">Search</button>
                      <?php 
                          if ($this->session->userdata('supp') == 005) { ?>

                      <?php
                          }else{ ?>
                      <button type="submit" value="2" class="btn btn-outline-danger btn-sm" name="type">Export To
                          CSV</button>
                      <?php
                          }
                          ?>
                      <button type="submit" value="3" class="btn btn-outline-danger btn-sm" name="type">Export Log To
                          CSV</button>
                      <a href="<?= base_url() ?>management_inventory" class="btn btn-outline-dark btn-sm">Reset</a>
                  </form>
              </div>
          </div>
      </div>
  </div>

  <div class="row mt-2">
    <div class="col-md-12">
      <table id="example" style="width: '100%';">
        <thead>
          <tr>
            <th>Tgl</th>
            <th>No Retur</th>
            <th style="width: '1%';">Principal</th>
            <th>Tipe</th>
            <th>Company</th>
            <th>Site</th>
            <th>Status</th>
            <th>Deadline tiba di Pabrik (Sisa Hari)</th>
            <th>Override</th>
            <!-- <th>Log</th> -->
            <th>#</th>
          </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($get_pengajuan->result() as $a) : ?>
            <tr>
              <!-- <td class="content"><?= date('d M Y', strtotime($a->tanggal_pengajuan)) ?></td> -->
              <td class="content"><?= $a->tanggal_pengajuan ?></td>
              <td><a href="<?= base_url().'management_inventory/generate_pdf/'.$a->signature.'/'.$a->supp ?>"
                  class="btn btn-submit-black content"
                  target="_blank"><?= ($a->no_pengajuan) ? $a->no_pengajuan : 'NULL'; ?></a>
              </td>
              <td class="content"><?= $a->namasupp ?></td>
              <td style="text-transform: uppercase" class="content"><?= $a->tipe ?></td>
              <td class="content"><?= $a->branch_name ?></td>
              <td class="content"><?= $a->nama_comp ?></td>
              <td>
                  <?php 
                      if ($a->status == 1) { // PROSES DP
                          $color = "btn-info btn-sm rounded content";
                      }elseif($a->status == 2){ // PROSES MPM
                          $color = "btn-warning btn-sm rounded content";
                      }elseif($a->status == 3){ // PROSES PRINCIPAL AREA
                          $color = "btn-danger btn-sm rounded content"; 
                      }elseif($a->status == 4){ // PROSES PRINCIPAL HO
                          $color = "btn-danger btn-sm rounded content";
                      }elseif($a->status == 5){ // PROSES KIRIM BARANG
                          $color = "btn-info btn-sm rounded content";
                      }elseif($a->status == 6){ // PROSES TERIMA BARANG
                          $color = "btn-danger btn-sm rounded content";
                      }elseif($a->status == 7){ // PROSES PEMUSNAHAN
                          $color = "btn-info btn-sm rounded content";
                      }elseif($a->status == 8 || $a->status == 9 || $a->status == 12){ // BARANG DITERIMA dan Pemusnahan
                          $color = "btn-dark btn-sm rounded content";
                      }elseif($a->status == 10){ // REJECT PRINCIPAL HO
                          $color = "btn-dark btn-sm rounded content";
                      }elseif($a->status == 13){ // REJECT
                          $color = "btn-dark btn-sm rounded content";
                      }else{
                          $color = "btn-info btn-sm rounded content";
                      }
                      
                  ?>
                  <a href="<?= base_url().'management_inventory/routing/'.$a->signature ?>"
                      class="btn <?= $color ?> btn-sm content" target="_blank"><?= $a->nama_status ?></a>
              </td>
              <td style="font-weight: bold;">
                  <?php 
                      if($a->status == 5) { ?>
                          <?= $a->deadline_kirim_barang.' ('.$a->sisa_hari.' Hari)' ?></td>
                      <?php
                      }
                  ?>
              <td>
                  <a href="<?= base_url().'management_inventory/form_override_status/'.$a->signature ?>"
                      class="btn btn-submit-black content" target="_blank">Override</a>
              </td>
              <!-- <td>
                  <a href="<?= base_url().'management_inventory/retur_log/'.$a->id ?>"
                      class="btn btn-submit-black content" target="_blank">Log</a>
              </td> -->
              <td>
                  <!-- <a href="<?= base_url().'management_inventory/delete_pengajuan/'.$a->signature ?>" class="btn btn-submit-red" target="_blank" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></a> -->
                  <a href="<?= base_url().'management_inventory/delete_pengajuan/'.$a->signature ?>" onclick="return confirm('Ingin menghapus retur ini ?')" class="delete-button" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
              </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#example').DataTable({
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
        });

        var table = new DataTable('#example');

        // #column3_search is a <input type="text"> element
        $('#column3_search').on('keyup', function () {
            table
                .columns(4)
                .search(this.value)
                .draw();
        });


    });
</script>

<!-- <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script> -->
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script>