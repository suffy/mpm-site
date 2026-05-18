<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>

<?php
foreach ($site_code as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $company_dp = $a->company;
    $site[$a->site_code] = $a->nama_comp;
}

?>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <a href="<?php echo base_url() . "assets/file/stock/template_import_stock.csv"; ?>" class="btn btn-dark" role="button">download template terlebih dahulu</a>

        </div>

        <hr>

        <div class="card-block">
            
        <?php echo form_open_multipart($url);?>
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="file" class="col-form-label">Attach FIle</label>
                </div>
                <div class="col-auto">
                    <input type="file" class="form-control" name="file">
                </div>
                <div class="col-auto">
                    <label for="file" class="col-form-label">Pilih Bulan</label>
                </div>
                <div class="col-auto">
                    <input type="month" name = "bulan" class="form-control" required>
                </div>
                <div class="col-auto">
                    <label for="file" class="col-form-label">Pilih DP</label>
                </div>
                <div class="col-auto">
                    <?php
                        echo form_dropdown('site_code', $site, '', 'class="form-control"  id="site_code" required');
                    ?>
                </div>
                
                <!-- <div class="col-auto">
                    <input type="submit" class = "btn btn-primary" value="proses stock">
                </div> -->
            </div>

            <div class="row align-items-center mt-4">                
                <div class="col-md-12 text-center">
                    <input type="submit" class = "btn btn-primary" value="proses stock">
                </div>
            </div>

        </form>
        </div>

    </div>
</div>


<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-columned">
                    <thead>
                        <tr>
                            <th width=1>No</th>
                            <th>Subbranch</th>
                            <th width=1>Tahun</th>
                            <th width=1>Bulan</th>
                            <th>Filename</th>
                            <th width=1>CreatedAt</th>
                            <th width=1>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_header as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->nama_comp; ?></td>
                                <td><?php echo $key->tahun; ?></td>
                                <td><?php echo $key->bulan; ?></td>
                                <td><?php echo $key->filename; ?></td>
                                <td><?php echo $key->created_at; ?></td>
                                <td><a href="<?= base_url() ?>stok/export_stock/<?= $key->signature ?>" class = "btn btn-warning btn-sm" target="_blank">Export</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
