<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
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
          <div class="col-md-12">
            <?php foreach ($data_header->result() as $a) : ?>
              <label for="">Cluster / Zonasi : <?= $a->label ?></label><br>
              <label for="">Sitecode : <?= $a->site_code ?></label><br>
            <?php endforeach; ?>
          </div>
        </div>
    </div>
  </div>

  <?php echo form_open($url); ?>

  <div class="card col-md-12 mt-3">
    <div class="card-body">
      <h3><?= $title; ?></h3>  
      <div class="row mt-4">
        <div class="col-md-2">
            <label for="harga_jual_retail">Pilih Kode Product</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <select name="kodeprod" id="kodeprod" class="form-control select2">
                <option value="">Pilih Kode Product</option>
                <?php foreach ($get_data_product->result() as $a) { ?>
                  <option value="<?= $a->kodeprod ?>"><?= $a->kodeprod ?> - <?= $a->namaprod ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
      </div>
        
      <input type="text" name="id_header" value="<?= $id_header ?>" class="form-control" hidden>
      <input type="text" name="signature_header" value="<?= $signature_header ?>" class="form-control" hidden>

      <div class="row mt-2">
        <div class="col-md-2">
            <label for="harga_jual_grosir">Harga Jual Grosir</label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" name="harga_jual_grosir" class="form-control" placeholder="harga jual grosir ..." required>
            </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-2">
            <label for="harga_jual_retail">Harga Jual Retail</label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" name="harga_jual_retail" class="form-control" placeholder="harga jual retail ..." required>
            </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-2">
            <label for="harga_jual_motoris_retail">Harga Jual Motoris Retail (OT)</label>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" name="harga_jual_motoris_retail" class="form-control" placeholder="harga jual motoris retail ..." required>
            </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-2">
          <label for="harga_jual_mt">Harga Jual MT</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="text" name="harga_jual_mt" class="form-control" placeholder="harga jual mt ..." required>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            <input type="submit" value="Simpan Data" class="btn btn-submit-red" style="height: 45px; width: 130px;">
        </div>
      </div>

    </div>
  </div>      

  <?php echo form_close(); ?>

  <?php echo form_open_multipart($url_import); ?>
  <div class="card col-md-12 mt-2">
    <div class="card-body">
      <div class="mb-4"><h5 class="card-title">Import Harga</h5></div>    
      <div class="row">
        <div class="col-md-2">
          <label for="import">Import File</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="file" name="file" class="form-control" required>
          </div>
        </div>
      </div>

      <input type="text" name="signature_header" value="<?= $signature_header ?>" class="form-control" hidden>
      <input type="text" name="signature_ticket" value="<?= $signature_ticket ?>" class="form-control" hidden>
      <input type="text" name="supp" value="<?= $supp ?>" class="form-control" hidden>

      <div class="row mt-2">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
          <input type="submit" value="Import Data" class="btn btn-submit" style="height: 45px; width: 130px;">
          <a href="<?= base_url().'products/template_import_kenaikan_harga/'.$signature_header.'/'.$signature_ticket ?>" class="btn btn-submit-black">download template terlebih dahulu</a>
        </div>
      </div>

    </div>
  </div>
  <?php echo form_close(); ?>
        
    <div class="row mt-4 mb-5">
      <table id="tabel" class="table-striped dataTable" style="width:100%">    
        <thead>
            <tr>                
                <th>No</th>
                <th>Kodeprod</th>
                <th>Namaprod</th>
                <th>Harga Jual Grosir</th>
                <th>Harga Jual Retail</th>
                <th>Harga Jual Motoris Retail</th>
                <th>Harga Jual MT</th>
                <th>#</th>                                    
            </tr>
        </thead>
        <tbody>
          <?php
          $no = 1; 
          foreach ($get_data_detail->result() as $a) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $a->kodeprod; ?></td>
                <td><?= $a->namaprod; ?></td>
                <td><?= $a->harga_jual_grosir; ?></td>
                <td><?= $a->harga_jual_retail; ?></td>
                <td><?= $a->harga_jual_motoris_retail; ?></td>
                <td><?= $a->harga_jual_mt; ?></td>
                <td>
                    <a href="<?= base_url().'products/kenaikan_harga_detail_delete/'.$a->signature.'/'.$signature_header ?>" class="btn btn-submit-red btn-sm" style="padding-top: 8px;" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Delete</a>                        
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
        $('#tabel').DataTable({
            "pageLength": 5000,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 30, 40, 50, 60, 70, 80, -1],
                [10, 20, 30, 40, 50, 60, 70, 80, "All"]
            ],
            scrollX: true,
            // scrollCollapse: true
            scrollY: 500
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 dengan konfigurasi pencarian
        $('.select2').select2({
            placeholder: "-- Pilih Kodeprod --",
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Data tidak ditemukan";
                }
            }
        });
        
        // Opsional: Menangani event ketika opsi dipilih
        $('#kodeprod').on('select2:select', function (e) {
            var data = e.params.data;
            console.log('Kodeprod terpilih:', data);
            // Tambahkan logika lain yang diperlukan di sini
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
