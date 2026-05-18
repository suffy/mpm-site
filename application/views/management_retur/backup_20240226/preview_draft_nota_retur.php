<style>
    input[type=button] {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
</style>

<?php 
    foreach ($data->result() as $a) {
        $customerid = $a->customerid;
        $nama_customer = $a->nama_customer;
        $userid = $a->userid;
        $npwp = $a->npwp;
        $nama_wp = $a->nama_wp;
        $email = $a->email;
        $alamat_wp = $a->alamat_wp;
        $ref = $a->ref;
        $nodo = $a->nodo;
        $nodo_beli = $a->nodo_beli;
        $noseri = $a->noseri;
        $noseri_beli = $a->noseri_beli;
        $nopo = $a->nopo;
        $tgldo_beli = $a->tgldo_beli;
        $tglbuat = $a->tglbuat;
        $tgl_beli = $a->tgl_beli;
        $signature = $a->signature;
        $signature_ajuan_retur = $a->signature_ajuan_retur;
    }
?>

</div>
<div class="container">

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
            <input type="text" class="form-control" name="customerid" value="<?= $customerid ?>" readonly>
            <input type="hidden" class="form-control" name="signature" value="<?= $signature ?>" readonly>
            <input type="hidden" class="form-control" name="signature_ajuan_retur" value="<?= $signature_ajuan_retur ?>"
                readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="nama_customer">Nama Customer</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nama_customer" value="<?= $nama_customer ?>" readonly>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-md-4">
            <label for="userid">user web</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="userid" value="<?= $userid ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="npwp">Npwp</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="npwp" value="<?= $npwp ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="nama_wp">Nama Wp</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nama_wp" value="<?= $nama_wp ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="email">Email</label>
        </div>
        <div class="col-md-5">
            <textarea name="email" class="form-control" cols="30" rows="3" readonly><?= $email ?></textarea>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="alamat_wp">Alamat Wp</label>
        </div>
        <div class="col-md-5">
            <textarea name="alamat_wp" class="form-control" cols="30" rows="3" readonly><?= $alamat_wp ?></textarea>
        </div>
    </div>


    <div class="row mt-1">
        <div class="col-md-4">
            <label for="ref">Ref</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="ref" value="<?= $ref ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="nodo">No Retur (nodo)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nodo" value="<?= $nodo ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="nodo_beli">No Retur Pembelian (nodo_beli)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nodo_beli" value="<?= $nodo_beli ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="noseri">No Seri Acuan Penjualan (noseri - masterDbsls)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="noseri" value="<?= $noseri ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="noseri_beli">No Seri Acuan Pembelian (noseri_beli)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="noseri_beli" value="<?= $noseri_beli ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="nopo">No Tanda Terima (nopo)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nopo" value="<?= $nopo ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="tglbuat">Tanggal Pembelian (tgldo_beli)</label>
        </div>
        <div class="col-md-5">
            <input type="date" class="form-control" name="tgldo_beli" value="<?= $tgldo_beli ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="tglbeli">Tanggal Proses (tglbuat)</label>
        </div>
        <div class="col-md-5">
            <input type="date" class="form-control" name="tglbuat" value="<?= $tglbuat ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="tglbeli">Tanggal Beli (tgl_beli)</label>
        </div>
        <div class="col-md-5">
            <input type="date" class="form-control" name="tgl_beli" value="<?= $tgl_beli ?>" readonly>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-md-12">

            <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
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
                // var_dump($get_draft_nota_retur);
                // die;
                foreach ($data->result() as $a) : ?>
                    <tr>
                        <td><?= $a->kodeprod ?></td>
                        <td><?= $a->namaprod ?></td>
                        <td><?= $a->qty_kecil ?></td>
                        <td><?= $a->retur ?></td>
                        <td><?= $a->qty_ajuan_retur ?></td>
                        <td><?= $a->qty_lpk ?></td>
                        <td><?= $a->selisih_qty ?></td>
                        <td><?= $a->qty_nota_retur ?></td>
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
            <button type="submit" class="btn btn-info aktif" onclick="myFunction()">Submit Nota Retur, Update Master DBSLS, Update Ajuan Retur dan
                Create Nota Retur</button>
            <button type="submit" class="btn btn-info non-aktif disabled" disabled>Mohon Menunggu . . .</button>
        </div>

    </div>

    <br><br>
    <?= form_close();?>

</div>




<script>
    $("button.non-aktif").hide()
    $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 10000,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
    });
</script>

<script>
    function myFunction() {
        $("button.aktif").hide()
        $("button.non-aktif").show()
    }
</script>

<script>
    $(document).ready(function () {
        $("#example2").DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script>