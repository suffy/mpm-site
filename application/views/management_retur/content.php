<style>
    td {
        font-size: 11px;
    }

    th {
        font-size: 12px;
    }
</style>

</div>

<div class="container-fluid">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-5">
                <label for="upload_dbsls" class="form-label">Upload DBSLS</label>
                <input class="form-control form-control-md" type="file" id="formFileMultiple" name="file" multiple>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-10">
                <button type="submit" class="btn btn-warning">Upload DBSLS</button>
                <a href="<?= base_url() ?>management_retur/truncate_master_dbsls"
                    class="btn btn-outline-danger">Truncate /
                    Clear data</a>
                <a href="<?= base_url() ?>management_retur/export_master_dbsls" class="btn btn-outline-success">Export /
                    Template</a>
                <a href="<?= base_url() ?>management_retur/update_data_dbsls" class="btn btn-outline-primary">Update
                    data</a>
            </div>
        </div>

        </form>

    </div>
</div>

<div class="container-fluid">
    <div class="col-12">
        <div class="card-block mt-5">
            <div class="row">
                <div class="col-md-12">

                    <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
                        <thead>
                            <tr>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">tanggal
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">productid
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">nama product
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">nama customer
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">nama brand
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">ref
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">no seri pajak
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">qty kecil
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">retur
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">beli
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">jual
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">disc cabang
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">disc beli
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_master_dbsls->result() as $a) : ?>
                            <tr>
                                <td><?= $a->tanggal; ?></td>
                                <td><?= $a->productid; ?></td>
                                <td><?= $a->nama_product; ?></td>
                                <td><?= $a->nama_customer.' - '.$a->customerid; ?></td>
                                <td><?= $a->nama_brand.' - '.$a->brandid; ?></td>
                                <td><?= $a->ref; ?></td>
                                <td><?= $a->no_seri_pajak; ?></td>
                                <td><?= $a->qty_kecil; ?></td>
                                <td><?= $a->retur; ?></td>
                                <td><?= $a->beli; ?></td>
                                <td><?= $a->jual; ?></td>
                                <td><?= $a->disc_cabang; ?></td>
                                <td><?= $a->disc_beli; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>