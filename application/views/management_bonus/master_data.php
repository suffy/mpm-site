<style>
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
    }
</style>

</div>

<div class="container">
    
<?php echo form_open_multipart($url); ?>

<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-5">        
        <label for="nama_program" class="form-label">Periode Program</label>
        <input class="form-control form-control-md" type="text" name="nama_program" required>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-5">        
        <label for="upload_dbsls" class="form-label">Upload Master Data</label>
        <input class="form-control form-control-md" type="file" id="formFileMultiple" name="file" multiple required>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-11">
        <button type="submit" class="btn btn-danger">upload master data</button>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-11 d-flex justify-content-center">
        <a href="<?= base_url() ?>management_bonus/truncate_master_data" class="btn btn-outline-danger">Truncate / Clear data</a>
        <a href="<?= base_url() ?>management_bonus/export_master_data_excel" class="btn btn-outline-success">Export Master Data (.xls)</a>
    </div>
</div>
    
</form>

</div>
</div>

<div class="container">

  <div class="card-block mt-5">
        <div class="row">
            <div class="col-md-12">

                <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th style="background-color: maroon;" class="text-center col-auto"><font color="white">SiteCode</th>
                            <th style="background-color: maroon;" class="text-center col-3"><font color="white">Nama Program</th>
                            <th style="background-color: maroon;" class="text-center col-3"><font color="white">Branch</th>
                            <th style="background-color: maroon;" class="text-center col-3"><font color="white">SubBranch</th>
                            <th style="background-color: maroon;" class="text-center col-auto"><font color="white">Kodeprod</th>
                            <th style="background-color: maroon;" class="text-center col-3"><font color="white">Namaprod</th>
                            <th style="background-color: maroon;" class="text-center col-1"><font color="white">QtyBonus</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_master_data->result() as $a) : ?>
                        <tr>
                            <td><?= $a->site_code; ?></td>
                            <td><?= $a->nama_program; ?></td>
                            <td><?= $a->branch_name; ?></td>
                            <td><?= $a->nama_comp; ?></td>
                            <td><?= $a->kodeprod; ?></td>
                            <td><?= $a->namaprod; ?></td>
                            <td><?= $a->qty_bonus; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>