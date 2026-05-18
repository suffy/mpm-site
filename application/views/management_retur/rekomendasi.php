<style>
    td {
        font-size: 13px;
        text-align: center;
    }

    th {
        font-size: 14px;
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

        <div class="card-block mt-3">
            <div class="row">
                <div class="col-md-12">

                    <table id="example" class="display" style="display: inline-block;">
                        <thead>
                            <tr>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Customer
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">NoSeri Pajak
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Productid
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">NamaProduct
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">BatchNumber
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">QtyKecil
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Retur
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">QtyAjuanRetur
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">QtyLpk
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">QTYKecil-QtyLpk
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_raw_rekomendasi->result() as $a) : ?>
                            <tr>
                                <td><?= $a->nama_customer; ?></td>
                                <td><?= $a->no_seri_pajak; ?></td>
                                <td><?= $a->productid; ?></td>
                                <td><?= $a->namaprod; ?></td>
                                <td><?= $a->batch_number; ?></td>
                                <td><?= $a->qty_kecil; ?></td>
                                <td><?= $a->retur; ?></td>
                                <td><?= $a->qty_ajuan_retur; ?></td>
                                <td><?= $a->qty_lpk; ?></td>
                                <td><?= $a->selisih_qty; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        Note : Noseri pajak yang tampil berdasarkan group ref (Dengan Max tanggal retur). <a
            href="<?= base_url('assets/css/images/note ajuan_retur.png');?>" target="_blank">Lihat Gambar</a>

        <div class="card-block mt-3">
            <div class="row">
                <div class="col-md-12 mt-5">

                    <table id="example2" class="display" style="display: inline-block;" width="100%">
                        <thead>
                            <tr>
                                <th style="background-color: darkslategray;" class="text-center col-md-3">
                                    <font color="white">Tanggal
                                </th>
                                <th style="background-color: darkslategray;" class="text-center col-md-5">
                                    <font color="white">NoSeri Pajak
                                </th>
                                <th style="background-color: darkslategray;" class="text-center col-md-3">
                                    <font color="white">Ref
                                </th>
                                <th style="background-color: darkslategray;" class="text-center col-md-2">
                                    <font color="white">Count
                                </th>
                                <th style="background-color: darkslategray;" class="text-center col-md-3">
                                    <font color="white">Selisih
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_rekomendasi->result() as $a) : ?>
                            <tr>
                                <td><?= $a->tanggal; ?></td>
                                <td><?= $a->no_seri_pajak; ?></td>
                                <td><?= $a->ref; ?></td>
                                <td><?= $a->count_no_seri_pajak; ?></td>
                                <td><?= $a->selisih_qty; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?= form_open($url); ?>

        <div class="row mt-5">
            <div class="col-md-2">
                <label for="customerid">customerid</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="customerid" value="<?= $customerid ?>" readonly>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-2">
                <label for="userid">userid web</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="userid" value="<?= $userid ?>" readonly>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-2">
                <label for="nama_customer">nama customer</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="nama_customer" value="<?= $nama_customer ?>" readonly>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-2">
                <label for="customerid">no seri pajak</label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="no_seri_pajak" placeholder="masukkan no seri pajak">
                <input type="hidden" class="form-control" name="signature" value="<?= $signature ?>">
                <input type="hidden" class="form-control" name="signature_ajuan_retur"
                    value="<?= $signature_ajuan_retur ?>">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
            </div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-info">Create Draft Nota Retur</button>
            </div>
        </div>
        <?= form_close();?>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#example").DataTable({
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