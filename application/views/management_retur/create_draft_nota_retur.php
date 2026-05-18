<style>
    input[type=button] {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }

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

        <?= form_open($url); ?>
        <div class="row">
            <div class="col-md-12 mt-3">
                <a href='<?= base_url("$back_url")?>' class="btn btn-warning mb-3"
                    style="background-color: darkslategray; color:white;" id="btn-produk">Tambah Produk</a>

                <table id="example" class="display" style="display: inline-block;">
                    <thead>
                        <tr>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Kodeprod
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">NamaProduk
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Tahun
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">NoSeri Pajak
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Tanggal
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Ref
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Noseri Pembelian
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QTY Kecil
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Retur
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Qty Ajuan Retur
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QtyLPK
                            </th>
                            <th class="text-center col-1">Beli</th>
                            <th class="text-center col-1">Jual</th>
                            <th class="text-center col-1">Disc Cabang</th>
                            <th class="text-center col-1">Disc Beli</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_draft_nota_retur_product->result() as $a) : 
                        if ($a->beli == null) {
                            if ($a->brandid == 002) {
                                $harga_beli = $a->jual;
                            } else {
                                $harga_beli = $a->beli;
                            }
                        } else {
                            $harga_beli = $a->beli;
                        }?>
                        <tr>
                            <td>
                                <input type="hidden" name="kodeprod[]" value="<?= $a->kodeprod; ?>">
                                <input type="hidden" name="brandid[]" value="<?= $a->brandid; ?>">
                                <input type="hidden" name="kode_prc[]" value="<?= $a->kode_prc; ?>">
                                <?= $a->kodeprod; ?>
                            </td>
                            <td>
                                <input type="hidden" name="namaprod[]" value="<?= $a->namaprod; ?>">
                                <?= $a->namaprod; ?>
                            </td>
                            <td>
                                <input type="hidden" name="tahun[]" value="<?= $a->tahun; ?>">
                                <?= $a->tahun; ?>
                            </td>
                            <td>
                                <input type="hidden" name="noseri[]" value="<?= $a->noseri; ?>">
                                <?= $a->noseri; ?>
                            </td>
                            <td>
                                <?= $a->tgldo; ?>
                            </td>
                            <td>
                                <input type="hidden" name="ref[]" value="<?= $a->ref; ?>">
                                <input type="hidden" name="tgldo_beli[]" value="<?= $a->tgldo; ?>">
                                <?= $a->ref; ?>
                            </td>
                            <td>
                                <input type="text" name="noseri_beli[]" value="<?= $a->noseri_beli; ?>" required>
                                <!-- <?= $a->noseri_beli; ?> -->
                            </td>
                            <td>
                                <input type="hidden" name="qty_kecil[]" value="<?= $a->qty_kecil; ?>">
                                <?= $a->qty_kecil; ?>
                            </td>
                            <td>
                                <input type="hidden" name="retur[]" value="<?= $a->retur; ?>">
                                <?= $a->retur; ?>
                            </td>
                            <td>
                                <input type="hidden" name="jumlah[]" value="<?= $a->jumlah; ?>">
                                <?= $a->jumlah; ?>
                            </td>
                            <td><input type="hidden" name="qty_lpk[]" value="<?= $a->qty_lpk; ?>" size="10">
                                <?= $a->qty_lpk; ?>
                            </td>
                            <td><input type="text" name="beli[]" value="<?= $harga_beli; ?>" size="10"></td>
                            <td><input type="text" name="jual[]" value="<?= $a->jual; ?>" size="10"></td>
                            <td><input type="text" name="disc_cabang[]" value="<?= $a->disc_cabang; ?>" size="10"></td>
                            <td><input type="text" name="disc_beli[]" value="<?= $a->disc_beli; ?>" size="10"></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div align='center' class="mt-3 mb-5">
            <a class="btn btn-info" id="btn-proses">Lanjutkan Proses</a>
        </div>

        <div class="proses">
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <label for="customerid">CustomerId</label>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="customerid" value="<?= $customerid ?>" readonly>
                    <input type="hidden" class="form-control" name="signature" value="<?= $signature ?>" readonly>
                    <input type="hidden" class="form-control" name="signature_ajuan_retur"
                        value="<?= $signature_ajuan_retur ?>" readonly>
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
                    <label for="userid">User Id</label>
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
                    <label for="nama_wp">Nama WP</label>
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
                    <textarea name="email" cols="30" rows="3" class="form-control" readonly><?= $email ?></textarea>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-4">
                    <label for="alamat_wp">Alamat Wp</label>
                </div>
                <div class="col-md-5">
                    <textarea name="alamat_wp" cols="30" rows="3" class="form-control"
                        readonly><?= $alamat_wp ?></textarea>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-4">
                    <label for="nodo">No Retur (nodo)</label>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="nodo">
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-4">
                    <label for="nodo_beli">No Retur Pembelian (nodo_beli)</label>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="nodo_beli">
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-4">
                    <label for="nopo">No Tanda Terima (nopo *)</label>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="nopo" required>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-4">
                    <label for="tglbeli">Tanggal Proses (tglbuat)</label>
                </div>
                <div class="col-md-5">
                    <input type="date" class="form-control" name="tglbuat">
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-4">
                    <label for="tglbeli">Tanggal Beli (tgl_beli)</label>
                </div>
                <div class="col-md-5">
                    <input type="date" class="form-control" name="tgl_beli">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4"></div>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-info">Next Preview Draft Nota Retur</button>
                </div>
            </div>
        </div>
        <?= form_close();?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".proses").hide()
        $("#example").DataTable({
            scrollX: true,
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

        $("a#btn-proses").click(function (e) {
            $(".proses").show()
            $("a#btn-proses").remove()
            $("a#btn-produk").remove()
        });
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script>