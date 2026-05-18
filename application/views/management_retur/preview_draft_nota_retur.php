<style>
    input[type=button] {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
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

        <?= form_open($url); ?>

        <div class="row mt-5">
            <div class="col-md-4">
                <label for="customerid">CustomerId</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="customerid" value="<?= $data['customerid']; ?>" readonly>
                <input type="hidden" class="form-control" name="signature" value="<?= $data['signature'] ?>" readonly>
                <input type="hidden" class="form-control" name="signature_ajuan_retur"
                    value="<?= $data['signature_ajuan_retur'] ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="nama_customer">Nama Customer</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="nama_customer" value="<?= $data['nama_customer'] ?>"
                    readonly>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-4">
                <label for="userid">user web</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="userid" value="<?= $data['userid']?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="npwp">Npwp</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="npwp" value="<?= $data['npwp'] ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="nama_wp">Nama Wp</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="nama_wp" value="<?= $data['nama_wp'] ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="email">Email</label>
            </div>
            <div class="col-md-5">
                <textarea name="email" class="form-control" cols="30" rows="3" readonly><?= $data['email'] ?></textarea>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="alamat_wp">Alamat Wp</label>
            </div>
            <div class="col-md-5">
                <textarea name="alamat_wp" class="form-control" cols="30" rows="3"
                    readonly><?= $data['alamat_wp'] ?></textarea>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="nodo">No Retur (nodo)</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="nodo" value="<?= $data['nodo'] ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="nodo_beli">No Retur Pembelian (nodo_beli)</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="nodo_beli" value="<?= $data['nodo_beli'] ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="nopo">No Tanda Terima (nopo)</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="nopo" value="<?= $data['nopo'] ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="tglbeli">Tanggal Proses (tglbuat)</label>
            </div>
            <div class="col-md-5">
                <input type="date" class="form-control" name="tglbuat" value="<?= $data['tglbuat'] ?>" readonly>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-4">
                <label for="tglbeli">Tanggal Beli (tgl_beli)</label>
            </div>
            <div class="col-md-5">
                <input type="date" class="form-control" name="tgl_beli" value="<?= $data['tgl_beli'] ?>" readonly>
            </div>
        </div>


        <div class="row mt-4">
            <div class="col-md-12">

                <table id="example" class="display" style="display: inline-block;">
                    <thead>
                        <tr>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Productid
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-2">
                                <font color="white">NamaProduct
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QtyKecil
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Retur
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QtyAjuanRetur
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QtyLpk
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">SelisihQty
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QTY Nota Retur
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Beli
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Jual
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Disc Cabang
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Disc Beli
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                    foreach ($data_detail->result() as $a) : ?>
                        <tr>
                            <td><?= $a->kodeprod ?></td>
                            <td><?= $a->namaprod ?></td>
                            <td><?= $a->qty_kecil ?></td>
                            <td><?= $a->retur ?></td>
                            <td><?= $a->jumlah ?></td>
                            <td><?= $a->qty_lpk ?></td>
                            <td><?= ($a->selisih_qty);?></td>
                            <td><?= $a->qty_lpk ?></td>
                            <td><?= $a->beli ?></td>
                            <td><?= $a->jual ?></td>
                            <td><?= $a->disc_cabang ?></td>
                            <td><?= $a->disc_beli ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>


        <div class="row mt-5">
            <div class="col-md-12">
                <button type="submit" class="btn btn-info aktif" onclick="myFunction()">Submit Nota Retur, Update Master
                    DBSLS, Update Ajuan Retur dan
                    Create Nota Retur</button>
                <button type="submit" class="btn btn-info non-aktif disabled" disabled>Mohon Menunggu . . .</button>
            </div>

        </div>

        <br><br>
        <?= form_close();?>

    </div>
</div>




<script>
    $("button.non-aktif").hide()
    $(document).ready(function () {
        $("#example").DataTable({
            paging: false,
            scrollCollapse: true,
            scrollX: true
        });
    });
</script>

<script>
    function myFunction() {
        $("button.aktif").hide()
        $("button.non-aktif").show()
    }
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script>