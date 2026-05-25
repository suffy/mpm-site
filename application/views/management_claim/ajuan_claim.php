<div class="container-fluid">

<div class="card mt-4 mb-4">
  <div class="card-body">
    <h5 class="card-title"><?= $title ?></h5>

    <form action="<?= $url ?>">
      <div class="row mt-5">
          <div class="col-lg-2">
              <label for="supp">Principal</label> 
          </div>
          <div class="col-md-4">
              <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                  <option value="">Principal ?</option>
                  <?php 
                      if($this->session->userdata('level') == 5) { ?>
                          <?php foreach ($get_principal->result() as $a) { ?>
                              <option value="<?= $a->supp ?>" <?= $this->input->get('supp') == $a->supp ? 'selected' : '' ?>><?= $a->namasupp ?></option>
                          <?php } 
                      
                      }else{
                          if ($this->session->userdata('supp') == '000') { ?>
                                  <option value="all">All</option>
                              <?php
                              }
                          ?>
                          <?php foreach ($get_principal->result() as $a) { ?>
                              <option value="<?= $a->supp ?>" <?= $this->input->get('supp') == $a->supp ? 'selected' : '' ?>><?= $a->namasupp ?></option>
                          <?php } 
                      }
                  ?>
              </select>
          </div>
      </div>

      <div class="row mt-1">
          <div class="col-lg-2">
              <label for="kategori">Kategori</label>
          </div>
          <div class="col-lg-4">
              <select id="kategori" name="kategori" class="form-control custom-input" required>
              </select>
          </div>
      </div>

      <div class="row mt-1">
          <div class="col-md-2">
              <label for="from">Periode</label> 
          </div>
          <div class="col-md-4">
              <div class="input-group">
                  <input type="date" name="from" id="from" min="2023-12-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                  <input type="date" name="to" id="to" min="2023-12-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
              </div>
          </div>
      </div>

      <div class="row mt-1">
          <div class="col-md-2">
              <label for="pic">Nama PIC</label>
          </div>
          <div class="col-md-4">
              <select id="pic" name="pic" class="form-control" required>
                  <option value="all"> All </option>
                  <option value="18" <?= $this->input->get('pic') == '18' ? 'selected' : '' ?>> Ismi </option>
                  <option value="444" <?= $this->input->get('pic') == '444' ? 'selected' : '' ?>> Ambar </option>
                  <option value="561" <?= $this->input->get('pic') == '561' ? 'selected' : '' ?>> Adi </option>
                  <option value="557" <?= $this->input->get('pic') == '557' ? 'selected' : '' ?>> Rani </option>
                  <option value="99" <?= $this->input->get('pic') == '99' ? 'selected' : '' ?>> Yuli </option>
                  <option value="812" <?= $this->input->get('pic') == '812' ? 'selected' : '' ?>> Dea </option>
                  <option value="1069" <?= $this->input->get('pic') == '1069' ? 'selected' : '' ?>> Rima </option>
              </select>
          </div>
      </div>

      <div class="row mt-1">
          <div class="col-lg-2">
              <label for="flag_delete">Flag Delete</label> 
          </div>
          <div class="col-lg-4">
              <select name="flag_delete" class="form-control">
                  <option value="">Active</option>
                  <option value="1" <?php if($flag_delete == 1) echo "selected"; ?>>Deleted</option>
              </select>
          </div>
      </div>

      <div class="row mt-3">
          <div class="col-md-2">
              <label for="supp"></label> 
          </div>
          <div class="col-md-10">
              <input type="submit" value="cari program" class="btn btn-submit-red" style="height: 45px;">
              <!-- <input type="submit" value="cari program" class="btn btn-primary" style="height: 45px;"> -->
              <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-submit-black" style="height: 45px; padding-top: 10px;">Reset View</a>
              
              <input type="submit" value="export" name="submit" class="btn btn-submit-black" style="height: 45px;">
          </div>
      </div>
    <?php echo form_close(); ?>

    <hr>

    <div class="row mt-2">
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

    <div class="row">
      <div class="col-md-12 text-center">
        <button type="button" class="btn btn-submit-orange" onclick="convertTable()" style="border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">Convert data below to Excel</button>
      </div>
    </div>    
</div>

<table id="tabel-ajuan-claim" class="table-striped">
  <thead>
    <tr>
        <th class="text-center">Principal</th>
        <th class="text-center" style="width: 150px">No Surat</th>
        <th class="text-center">Kategori</th>
        <th class="text-center" style="width: 150px">Nama Program</th>
        <th>Subbranch</th>
        <th class="text-center" style="width: 1%">Status Internal</th> 
        <th class="text-center">Duedate Response</th>  
        <th class="text-center">Duedate Program</th>  
        <th style="width: 150px">No Ajuan Claim</th>                               
        <th class="text-center">Periode</th>               
        <th class="text-center">Bulan</th>                  
        <th class="text-center" style="width: 1%">#</th>               
    </tr>
  </thead>
  <tbody>     
      <?php
      foreach ($get_data->result() as $a) : ?>
      <?php 
          $tahun_folder = $a->tahun_folder;
          if ($tahun_folder == 2024) {
              $url = "http://backup.muliaputramandiri.com:81/cisk/assets/uploads/management_claim/2024/";
          }else{
              $url = base_url()."assets/uploads/management_claim/2025/";
          }
      ?>
      <tr>
        <td><?= strlen($a->namasupp) > 13 ? substr($a->namasupp,0,13).' ('.$a->segment.')' : $a->namasupp.' ('.$a->segment.')' ?></td>   
        <td>
          <a href="<?= $url.'registrasi_program/'.$a->upload_pdf ?>" class="btn btn-submit pending-scm" target="_blank" style="border:none"><?= strlen($a->nomor_surat) > 30 ? substr($a->nomor_surat,0,30).'...' : $a->nomor_surat ?></a>
        </td>
        <td><?= strlen($a->nama_kategori) > 10 ? substr($a->nama_kategori,0,10).'...' : $a->nama_kategori ?></td>
        <td><?= strlen($a->nama_program) > 30 ? substr($a->nama_program,0,30).'...' : $a->nama_program ?></td>                    
        <td>
            <?php 
                if ($a->nama_comp) { ?>
                    <?= $a->nama_comp; ?>
                <?php
                }else{ ?>
                    Belum Mengajukan
                <?php
                }
            ?>
        </td>
        <td class="text-center">
            <?php 
            // jika status "tidak ikut", maka tampil status "tidak ikut"
            if ($a->status == 17) { ?>
                <label class="btn btn-submit pending-finance"><?= $a->nama_status_keikutsertaan ?></label>
            <?php
            }else
            {                                    
                if ($a->status_internal) { ?>
                    <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit pending-scm" target="_blank" style="border:none"><?= ($a->pic_userid_username) ? $a->nama_status_internal.' - '.$a->pic_userid_username : $a->nama_status_internal ?></a>                                        
                <?php
                }else{ ?>
                    <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-red" target="_blank">belum ada</a>
                <?php
                }
            }
            ?>
                
        </td>                            
        <td>
          <?php 
            if ($a->duedate_response) { 
              if ($a->duedate_response < date('Y-m-d')) { ?>
                <label for="" class="pending-finance" style="font-size: 12px; padding: 5px">
                <?php
                    echo date_diff(date_create(date('Y-m-d')), date_create($a->duedate_response))->format('%a days ago');
                ?>
                </label>
              <?php
              }else{ ?>
              <label for="" class="pending-scm" style="font-size: 12px; padding: 5px">
              <?php
                echo date_diff(date_create(date('Y-m-d')), date_create($a->duedate_response))->format('%a days left');
              ?>
              </label>
              <?php
              }
            }
          ?>
        </td>
        <td><?= date('d M y', strtotime($a->duedate)); ?></td>               
        <td><?= $a->nomor_ajuan; ?></td>               
        <td><?= date('d M', strtotime($a->from)). ' - '.date('d M y', strtotime($a->to)); ?></td>
        <td><?= substr($a->from, 5, 2) ?></td>
        <td class="text-center">
          <?php 
            if ($a->deleted_at && $a->nomor_ajuan) { ?>
                <a href="<?= base_url().'management_claim/undelete_ajuan_claim/'.$a->signature_ajuan ?>" class="btn btn-submit-red" onclick="return confirm('Apakah anda yakin mengembalikan claim program ini ?')">deleted</a>
            <?php 
            }elseif($a->nomor_ajuan){ ?>
                <a href="<?= base_url('management_claim/delete_ajuan_claim/'.$a->signature_ajuan) ?>" onclick="return confirm('Ingin menghapus claim ini ?')" class="delete-button" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
            <?php
            }elseif($a->status_keikutsertaan == "0")
            { ?>                                    
                <a href="<?= base_url().'management_claim/flag_keikutsertaan_reset/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-black" onclick="return confirm('Apakah anda yakin ingin kembali mengikuti claim program ini ?')">saya tidak ikut</a>
            <?php
            }else{ 
                // echo "status keikutsertaan = ".$a->status_keikutsertaan;
                ?>
                <a href="<?= base_url().'management_claim/flag_keikutsertaan/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-submit-cream" onclick="return confirm('Apakah anda yakin tidak ingin mengikuti program ini ?')" style="border:none">?</a>
            <?php
            }
          ?>
        </td>
      </tr>
      <?php endforeach; ?>   
  </tbody>
</table>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel-ajuan-claim').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });

    $.ajax({ 
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_kategori') ?>',
        success: function(result) {
            $("select[name = kategori]").html(result);
        }
    });
</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-ajuan-claim"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>