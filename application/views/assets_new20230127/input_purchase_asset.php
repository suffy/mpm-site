<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <a href="<?= base_url()."assets_new/purchase_asset"; ?>  " class="btn btn-dark"
                                role="button"><span class="glyphicon glyphicon-plus"
                                    aria-hidden="true"></span>Kembali</a>
                            <br><br>
                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5><?= $title; ?></h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <?= form_open($url);?>
                                    <ul>
                                        <!-- <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">No. Purchase Requistion</label>
                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="np" readonly />
                                            </div>
                                        </div> -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Nama Barang</label>
                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="nb" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tipe</label>
                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="tipe" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Jumlah Barang</label>
                                            <div class="col-sm-6">
                                                <input class="form-control" type="number" name="jb" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Harga</label>
                                            <div class="col-sm-6">
                                                <input class="form-control" type="number" name="harga" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label"></label>
                                            <div class="col-sm-6">
                                                <div class="checkbox-color checkbox-primary">
                                                    <input id="checkbox1" type="checkbox" name="tax" value="11">
                                                    <label for="checkbox1">
                                                        Tax
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" align="center">
                                            <?= form_submit('submit','Tambah', 'class="btn btn-success"');?>
                                            <?= form_close();?>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card table-card">
                        <div class="card-header">
                            <div class="card-block">
                                <?= form_open($url2);?>
                                <div class="dt-responsive table-responsive">
                                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th width="1">
                                                    NAMA BARANG
                                                </th>
                                                <th>
                                                    TIPE
                                                </th>
                                                <th>
                                                    JUMLAH
                                                </th>
                                                <th>
                                                    HARGA
                                                </th>
                                                <th>
                                                    TAX
                                                </th>
                                                <th>
                                                    Total
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($barang as $a) : ?>
                                            <tr>
                                                <td>
                                                    <?= $a->nama_barang; ?>
                                                </td>
                                                <td>
                                                    <?= $a->tipe; ?>
                                                </td>
                                                <td>
                                                    <?= number_format($a->jumlah); ?>
                                                </td>
                                                <td>
                                                    <?= number_format($a->harga); ?>
                                                </td>
                                                <td>
                                                    <?= number_format($a->tax); ?>
                                                </td>
                                                <td>
                                                    <?= number_format($a->jumlah*$a->harga+$a->tax); ?>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php
                                                                echo anchor('assets_new/purchase_asset_delete_barang_temp/' . $a->id, ' ',
                                                                    array('class' => 'ti-trash btn btn-danger btn-xs',
                                                                        'onclick'=>'return confirm(\'Are you sure?\')'));   
                                                            ?>
                                                    </center>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th width="1">
                                                    NAMA BARANG
                                                </th>
                                                <th>
                                                    TIPE
                                                </th>
                                                <th>
                                                    JUMLAH
                                                </th>
                                                <th>
                                                    HARGA
                                                </th>
                                                <th>
                                                    TAX
                                                </th>
                                                <th>
                                                    Total
                                                </th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div align="center">
                            <?= form_submit('submit','Confirm', 'class="btn btn-success"');?>
                            <?= form_close();?>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>