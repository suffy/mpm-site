<div class="row mt-2">
    <div class="col-md-12 text-center">
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

<div class="container-fluid mt-2">

  <?php echo form_open($url_finance); ?>
  <div class="card">
    <div class="card-body">
      <div class="row mt-2">
        <div class="col-md-2">
            <label for="alasan" >Alasan Approval</label>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="alasan" value="By Pass" required>
        </div>
        <div class="col-md-7">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <?php
            if ($status == 2 && $status_approval == 1 && $flag_open == 0) { ?>
                <button type="submit" class="btn btn-submit" disabled>Please Wait Finance Approval</button>
            <?php
            } elseif ($status == 2 && $status_approval == 1 && $flag_open == 1) { ?>
                <button type="submit" class="btn btn-submit" disabled>Already open at <?= $open_date ?></button>
            <?php
            } else { ?>
                <button type="submit" class="btn btn-submit">Request Approval to Finance</button>
            <?php
            }
            ?>
        </div>
      </div>
    </div>
  </div>
  <?= form_close(); ?>
    
  <?php echo form_open($url_rilis); ?>
  <div class="card mt-4">
    <div class="card-body">
      <div class="row mt-1">
        <div class="col-md-2">
            <label for="Note">Note</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="note" value="<?= $note ?>">
        </div>
      </div>

      <div class="row mt-1">
        <div class="col-md-2">
          <label for="po_ref" >PO Ref (*)</label>
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control" name="po_ref" value="<?= $po_ref ?>" required>
        </div>
      </div>

      <div class="row mt-1">
        <div class="col-md-2">
          <label for="alasan_order" >Alasan Order DP</label>
        </div>
        <div class="col-md-4">
          <textarea name="alasan_order" class="form-control" cols="5" rows="4" readonly><?= $alasan_order ?></textarea>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-2"></div>
        <div class="col-md-4">
          <?php 
            // echo 'flag_selisih : '.$flag_selisih;
            if($flag_selisih == 1 || $spk_pp_karton == 0) { ?>
              <span class="text-danger my-3">Perhatian !! Ditemukan selisih pada data SPK di bawah. Sehingga silahkan masukkan "alasan rilis"</span>
            <?php
            }
          ?>
        </div>
      </div>

      <div class="row mt-1">
        <div class="col-md-2">
          <label for="keterangan_spk" >Alasan Rilis</label>
        </div>
        <div class="col-md-4">
          <textarea name="alasan_rilis" class="form-control" cols="5" rows="4" <?= ($flag_selisih == 1 || $spk_pp_karton == 0) ? 'required' : 'readonly' ?>><?= $alasan_rilis ?></textarea>
        </div>
      </div>

      <!-- <?php echo "alasan_rilis : ".$alasan_rilis; ?>
      <?php echo "flag_selisih : ".$flag_selisih; ?> -->

      <div class="row mt-3">
        <div class="col-md-2"></div>
        <div class="col-md-9">
          <input type="hidden" name="signature" value="<?= $signature ?>">
          <?php
          if ($nopo) { ?>
              <button type="submit" class="btn btn-submit-black" disabled>released number : <?= $nopo ?></button>
              <a href="<?= base_url() ?>spk/email_po/<?= $signature ?>" class="btn btn-submit-red" style="height: 45px; padding-top: 10px" target="_blank">Email PO</a>
          <?php
          } else { ?>
              <?php
              if ($flag_open == 1) { ?>
                  <button type="submit" class="btn btn-submit">Rilis PO</button>
              <?php
              } else { ?>
                  <button type="submit" class="btn btn-submit-black" disabled>Please Wait Finance Approval</button>
              <?php
              }
              ?>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <?= form_close(); ?>

  <?php echo form_open($url_update); ?>
  <div class="card mt-4">
    <div class="card-body">
      <div class="row mt-2">
        <div class="col-md-2">
            <label for="tipe" >Tipe</label>
        </div>
        <div class="col-md-4">
          <select name="tipe" class="form-control" required>
            <option value="S" <?php if ($tipe == 'S') echo "selected"; ?>>Spk</option>
            <option value="A" <?php if ($tipe == 'A') echo "selected"; ?>>Alokasi</option>
            <!-- <option value="R" <?php if ($tipe == 'R') echo "selected"; ?>>REPLENISHMENT</option> -->
          </select>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-2">
          <label for="note" >Note</label>
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control" name="note" value="<?= $note ?>">
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-2">
          <label for="po_ref">Po Ref</label>
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control" name="po_ref" value="<?= $po_ref ?>">
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-2">
          <label for="alasan_rilis">Alasan Rilis</label>
        </div>
        <div class="col-md-4">
          <textarea name="alasan_rilis" class="form-control" cols="5" rows="4"><?= $alasan_rilis ?></textarea>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-2"></div>
        <div class="col-md-4">
          <input type="hidden" name="signature" value="<?= $signature ?>">
          <button type="submit" class="btn btn-submit">Update Data</button>
        </div>
      </div>

    </div>
  </div>
  <?= form_close(); ?>

  
  <div class="card mt-4">
    <div class="card-body">
      
      <?php echo form_open_multipart($url_import); ?>
      <div class="row">
        <div class="col-md-12">          
          <p>Fitur import di bawah ini, menggunakan "Unit terkecil" <span class="badge badge-warning"><i>bukan</i></span> "karton". Sehingga dapat dimanfaatkan untuk Luliana.</p>  
        </div>
      </div>
      <div class="row mt-2">
        <div class="col-md-2">
          <label for="expose" >File Import</label>
        </div>
        <div class="col-md-4">
          <input type="file" class="form-control" name="file" required>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-2"></div>
          <div class="col-md-9">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <button type="submit" class="btn btn-submit-red" style="height:44px;width:80px">Import</button>
            <a href="<?= base_url('spk/export_template_list_order/' . $signature) ?>" class="btn btn-submit-black">Download Template List Order</a>
            <button type="button" class="btn btn-submit-black" onclick="convertTable()">Export to Excel</button>
              <a href="<?= base_url('spk/list_order') ?>" class="btn btn-submit-black">Kembali</a>
          </div>
      </div>
      <?= form_close(); ?>

      <div class="row mt-2">
        <div class="col-md-12">
          <table id="tabel-data" class="table-striped" style="width: 100%;"> 
            <thead>
              <tr>
                <th width="15%">Product</th>
                <th>Unit</th>
                <th width="20%">Karton</th>
                <th>Berat | Volume | IsiSatuan</th>
                <th>PP(Karton)</th>
                <th>Actual PO</th>
                <th>Selisih</th>
                <th class="text-center">UpdateAt</th>
                <th class="text-center">UpdatedBy</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($get_data->result() as $a) : ?>
              <tr>
                <td><span <?php if ($a->deleted == 1) echo "class='strike'" ?> <?php if($a->selisih_po < 0 || $a->spk_pp_karton == 0) echo "style='color: red';" ?> data-toggle="tooltip" title="<?= 'PP - ORDER : '.$a->selisih_po ?>")><?= $a->kodeprod.' - '.$a->namaprod ?></span></td>
                <td><span <?php if ($a->deleted == 1) echo "class='strike'" ?> <?php if($a->selisih_po < 0 || $a->spk_pp_karton == 0) echo "style='color: red';" ?> data-toggle="tooltip" title="<?= 'PP - ORDER : '.$a->selisih_po ?>")><?= $a->banyak ?></span></td>
                <td>
                  <?php echo form_open($url_update_karton); ?>
                    <input type="hidden" name="signature" value="<?= $signature ?>">
                    <input type="hidden" name="id_po_detail" value="<?= $a->id ?>">
                    <input type="hidden" name="isisatuan" value="<?= $a->isisatuan ?>">
                    <div class="d-flex justify-center gap-1">
                      <input type="number" value="<?= $a->banyak_karton ?>" name="banyak_karton" style="width: 50%" class="form-control">
                      <input type="submit" value="Update" class="btn btn-submit">
                    </div>
                  <?= form_close(); ?>
                </td>
                <td><span <?php if ($a->deleted == 1) echo "class='strike'" ?> <?php if($a->selisih_po < 0 || $a->spk_pp_karton == 0) echo "style='color: red';" ?> data-toggle="tooltip" title="<?= 'PP - ORDER : '.$a->selisih_po ?>")><?= $a->berat.' | '.$a->volume. ' | '.$a->isisatuan ?></span></td>
                <td><span <?php if ($a->deleted == 1) echo "class='strike'" ?> <?php if($a->selisih_po < 0 || $a->spk_pp_karton == 0) echo "style='color: red';" ?> data-toggle="tooltip" title="<?= 'PP - ORDER : '.$a->selisih_po ?>")><?= $a->pp_karton ?></span></td>
                <td><span <?php if ($a->deleted == 1) echo "class='strike'" ?> <?php if($a->selisih_po < 0 || $a->spk_pp_karton == 0) echo "style='color: red';" ?> data-toggle="tooltip" title="<?= 'PP - ORDER : '.$a->selisih_po ?>")><?= $a->actual_po_bulan_ini ?></span></td>
                <td>
                  <?php 
                    if ($a->selisih_po < 0) {
                      $nilai = $a->selisih_po;
                      $color = 'red';
                    } elseif ($a->spk_pp_karton == 0) {
                      $nilai = $a->spk_pp_karton - $a->banyak_karton - $a->actual_po_bulan_ini;
                      $color = 'red';
                    } else {
                      $nilai = $a->selisih_po;
                      $color = 'black';
                    }
                    echo '<span style="color: '.$color.';">'.$nilai.'</span>';
                  ?>  
                </td>
                <td class="text-center"><?= $a->updated_at ?></td>
                <td class="text-center"><?= $a->username ?></td>
                <td>
                  <div class="btn-group">
                      <?php
                      if ($a->deleted == 1) { ?>
                          <span class="strike">
                            <a href="<?= base_url() ?>spk/list_order_detail_undelete/<?= $a->id . '/' . $signature ?>" class="delete-button" onclick="return confirm('Kembalikan data ini ?')" style="background-color: #EF9C66;"><span style="color: #000;"><strong>Undo</strong></span></a>
                          </span>
                      <?php
                      } else { ?>
                        <a href="<?= base_url() ?>spk/list_order_detail_delete/<?= $a->id . '/' . $signature ?>" onclick="return confirm('Ingin menghapus data ini ?')" class="delete-button" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
                      <?php
                      }
                      ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    
    </div>
  </div>
  <?= form_close(); ?>    

</div>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
  $(document).ready(function() {
    $('#tabel-data').DataTable({
      aLengthMenu: [
          [10, 20, 50, -1],
          [10, 20, 50, "All"]
      ],
    });
  });
</script>

<script>
  const convertTable = () => {
    let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
    XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
  }
</script>