<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>

<?php 
    foreach ($get_header_current as $key) {
        $filename = $key->filename;
        $tahun = $key->tahun;
        $bulan = $key->bulan;
    }
?>

<div class="card table-card">
    <div class="card-header">

        <div class="card-block">

            <a href="<?php echo base_url() . "stok/dashboard"; ?>" class="btn btn-dark" role="button">Kembali ke halaman awal</a>

        </div>
        <hr>
        <div class="card-block">

            

            <div class="row align-items-center">
                <div class="col-md-12 text-center">
                    <label for="file" class="col-form-label"><h4>Berikut adalah data yang sudah anda upload</h4></label>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="card table-card">
    <div class="card-header">

        <div class="card-block">

            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="file" class="col-form-label">Filename :</label>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control" value = "<?php echo $filename; ?>">                    
                </div>
                <div class="col-auto">
                    <label for="file" class="col-form-label">Tahun Bulan</label>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control" value = "<?php echo $tahun.' - '.$bulan; ?>">                      
                </div>
            </div>

        </div>

        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-columned">
                    <thead>
                        <tr>
                            <th colspan="4"><center>csv dp</center></th>
                            <th colspan="3"><center>hasil mapping</center></th>
                        </tr>
                        <tr>
                            <th width=1>No</th>
                            <th>Kodeprod_file</th>
                            <th>Namaprod_file</th>
                            <th>Stock_file</th>
                            <th>Kodeprod</th>
                            <th>Namaprod</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_import_current as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->kodeprod_file; ?></td>
                                <td><?php echo $key->namaprod_file; ?></td>
                                <td><?php echo $key->stock_file; ?></td>
                                <td><?php echo $key->kodeprod; ?></td>
                                <td><?php echo $key->namaprod; ?></td>
                                <td><?php echo $key->stock; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
