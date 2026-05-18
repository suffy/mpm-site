<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  td{
    font-size: 12px;
  }
  td:hover{
    cursor: pointer;
    font-size: 15px;
    font-weight: bold;
  }
  .select2-container--default .select2-selection--single {
      height: 38px;
      padding: 5px;
      border-radius: 0.25rem;
      background-color: var(--bs-body-bg);
      border: 2px solid var(--bs-light-bg-subtle);
      color: var(--bs-body-color);
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 36px;
      background-color: var(--bs-body-bg);
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: var(--bs-body-color);
  }
  
  .select2-container--default .select2-dropdown {
      background-color: var(--bs-body-bg);
      border: 2px solid var(--bs-light-bg-subtle);
  }
</style>
<div class="container-fluid">

<div class="row">
  <div class="col-md-12 mt-4">
    <h4><?= $title ?></h4>
  </div>
</div>

<form action="<?= $url ?>">
<div class="row mt-3">
  <div class="col-md-2">
    <label for="subbranch">Subbranch</label> 
  </div>
  <div class="col-md-5">
    <select name="subbranch" id="subbranch" class="form-control select2">
    <?php foreach ($get_customer->result() as $a) : ?>
        <option value=<?= $a->site_code ?> <?= ($this->input->get('subbranch') == $a->site_code) ? 'selected' : '' ?>><?= $a->company.' | '.$a->nama_comp ?></option>
    <?php endforeach; ?>  
    </select>
  </div>
</div>

<div class="row mt-1">
  <div class="col-md-2">
    <label for="from">Periode</label> 
  </div>
  <div class="col-md-5">
    <div class="input-group">
      <input type="date" name="from" id="from" min="2026-01-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
      <input type="date" name="to" id="to" min="2026-01-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-2">
  </div>
  <div class="col-md-10">
    <input type="submit" value="Search" class="btn btn-submit-red" style="height: 45px;">
    <a href="<?= base_url().'finance/list_data' ?>" class="btn btn-submit-black" style="height: 45px; padding-top: 10px;">Reset View</a>
    <a href="<?= base_url().'finance/update_piutang_from_dbsls' ?>" class="btn btn-submit-black" style="height: 45px; padding-top: 10px;">Update Piutang From SDS : <?= date('d M Y h:i', strtotime($max_piutang_date)) ?></a>
    <a href="<?= base_url().'all_transaction/open_credit_limit' ?>" class="btn btn-submit-black" style="height: 45px; padding-top: 10px;" target="_blank">Versi Lama</a>      
  </div>
</div>
<?php echo form_close(); ?>

<div class="row mt-3">
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

<div class="row mb-5">
  <div class="col-md-12">
    <table id="tabel" style="width: '100%';">
      <thead>
        <tr>
          <th>Order</th>           
          <th>Subbranch</th>           
          <th>Principal</th>    
          <th>Nopo</th>    
          <th>Value</th>  
          <th>Piutang</th>  
          <th>creditLimit</th>  
          <th>bankGaransi</th>  
          <th>PoThisMonth</th>  
          <th>status</th>  
          <th>duedate(>7)</th>  
          <th>totalEstimasi</th>  
        </tr>
      </thead>
      <tbody>     
          <?php foreach ($get_data->result() as $a) : ?>
              <tr>
                <td><?= $a->tglorder ?></td>
                <td><a href="<?= base_url().'finance/detail_po/'.$a->nopo.$a->signature ?>" target="_blank"><?= $a->company ?></a></td>
                <td>
                  <?php 
                    if($a->supp == '001'){
                      echo 'deltomed';
                    }elseif($a->supp == '005'){
                      echo 'us';
                    }else{
                      echo $a->namasupp;
                    }
                  ?>
                  <td><?= ($a->nopo) ? $a->nopo : '-' ?></td>
                  <td><?= number_format($a->total_value) ?></td>
                  <td><?= number_format($a->saldoakhir) ?></td>
                  <td><?= ($a->cl) ? number_format($a->cl) : '<span style="color: red">not found</span>' ?></td>               
                  <td><?= ($a->bank_garansi) ? $a->bank_garansi : '<span style="color: red">not found</span>' ?></td>
                  <td><?= number_format($a->total_value_current_month) ?></td>
                  <?php 
                  if (($a->cl ? $a->cl : 0) < $a->total_estimasi) { ?>               
                  
                  <td style="background-color: #FFC0CB">
                  <?php 
                    if ($a->open == '0') {
                      if ($periode_now > $a->periode) {
                      ?>
                        <span class="pending-finance">expired</span>
                        <?php
                        }elseif ($a->status_approval <> '1') { ?>
                            <span class="pending-scm">doi-check</span>
                        <?php
                      }else{
                      ?>
                      <div>
                          <a href="<?= base_url().'finance/unlock/'.$a->signature ?>" class="pending-finance" style="padding: 10px; border-radius: 10px">Lock</a>
                          <?php 
                              if (($a->cl ? $a->cl : 0) < $a->total_estimasi) { ?>
                              <?php
                              }
                          ?>                                      
                      </div>
                      <?php
                      }

                          }elseif ($a->open == '3') {?>
                              <span class="pending-scm">expired</span>
                          <?php
                          }else{ ?>
                              <span class="pending-scm">open:<?= $a->username ?></span>
                          <?php                                        
                          }
                          
                      ?>
                      </td>

                      <?php 
                      }else{ ?>

                      <td>
                      <?php 
                      if ($a->open == '0') {
                          if ($periode_now > $a->periode) {
                              ?>
                              <span class="pending-finance">expired</span>
                              <?php
                          }elseif ($a->status_approval <> '1') { ?>
                              <span class="pending-scm">doi-check</span>
                          <?php
                          }else{
                              // echo "<a href=unlock/$a->id><font color = 'red'><strong>LOCK</a></strong></font>";
                              
                              ?>
                              <div>
                                  <a href="<?= base_url().'finance/unlock/'.$a->signature ?>" class="pending-finance" style="padding: 10px; border-radius: 10px">Lock</a>
                                  <?php 
                                      if (($a->cl ? $a->cl : 0) < $a->total_estimasi) { ?>
                                      <?php
                                      }
                                  ?>
                                  
                              </div>

                          <?php
                          }

                      }elseif ($a->open == '3') {?>
                          <span class="pending-scm">expired</span>
                      <?php
                      }else{ ?>
                          <span class="pending-scm">open:<?= $a->username ?></span>
                      <?php                                        
                      }
                      
                  ?>
                  </td>
                      <?php
                      }
                  ?>
                  <td><?= number_format($a->jt) ?></td>
                  <td><?= number_format($a->total_estimasi) ?></td>
              </tr>
          <?php endforeach; ?>
      
      </tbody>
    </table>

  </div>
</div>

<script>
$(document).ready(function () {
    $("#btnBack").show();
    $("#btnLoading").hide();
    $('#tabel').DataTable({
        "pageLength": 50,
        // "ordering": true,
        "ordering": false,
        "order": [0, 'asc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        scrollX: true,
    });
});
</script>

<script>
  $(document).ready(function() {
    // Inisialisasi Select2 dengan konfigurasi pencarian
    $('.select2').select2({
        placeholder: "-- Pilih Subbranch --",
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Data tidak ditemukan";
            }
        }
    });
    
    // Opsional: Menangani event ketika opsi dipilih
    $('#subbranch').on('select2:select', function (e) {
        var data = e.params.data;
        console.log('subbranch terpilih:', data);
        // Tambahkan logika lain yang diperlukan di sini
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>