<?= $this->load->view('spk/component/title');?>

<?php echo form_open_multipart($url_import); ?>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="expose" class="form-label">File Import</label>
    </div>
    <div class="col-md-4">
        <input type="file" class="form-control" name="file" required>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        
    </div>
    <div class="col-md-9">
        <button type="submit" class="btn btn-submit-red" style="height:44px;width:80px">Import</button>
        <a href="<?= base_url('spk/export_template_alokasi') ?>" class="btn btn-submit-black">Download Template Alokasi</a>        
        <button type="button" class="btn btn-submit-black" onclick="convertTable()">Export to Excel</button>
        <a href="<?= base_url('spk/export_master_site') ?>" class="btn btn-submit-black">Download Master Site</a>        
        <a href="<?= base_url('spk') ?>" class="btn btn-submit-black">Kembali</a>
    </div>
</div> 
<?= form_close(); ?>

<hr class="mt-5">

<?php echo form_open($url); ?>
<div class="card-block mt-5 mb-1">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel-data" class="display" style="display: inline-block; overflow-y: scroll; width: 100%">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th width="15%">Principal</th>                    
                        <th width="10%">DP Header</th>                    
                        <th width="10%">DP Tujuan</th>                    
                        <th width="10%">Kode Produk</th>                    
                        <th>Nama Produk</th>                    
                        <th width="12%">Jumlah Karton</th>                 
                    </tr>
                </thead>
                <tbody>     
                    <?php
                    $no = 1;
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a->namasupp ?></td>
                        <td><?= $a->nama_comp_header ?></td>
                        <td><?= $a->nama_comp_tujuan ?></td>
                        <td><?= $a->kodeprod ?></td>
                        <td><?= $a->namaprod ?></td>
                        <td>
                            <input type="hidden" name="id_header" class="form-control" value="<?= $a->id ?>">
                            <input type="hidden" name="id_detail[]" class="form-control" value="<?= $a->id_detail ?>">
                            <input type="number" name="jml_karton[]" class="form-control" min="0" value="<?= $a->jml_karton ?>">
                        </td>
                        
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-12 d-flex justify-content-center btn-group">
         <a href="<?= base_url('spk/form_alokasi') ?>" class="btn btn-submit-black" style="width: 50%">Kembali</a>
        <input type="submit" value="Simpan data & Lanjut ke Verifikasi Data" class="btn btn-submit-orange" style="width: 50%">
    </div>
</div>

<?= form_close(); ?>

<script>
    $(document).ready(function () 
    {
        $('#tabel-data').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // scrollX: true
        });
    });
</script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>