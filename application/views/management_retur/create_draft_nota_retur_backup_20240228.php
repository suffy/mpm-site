<?php
    $jum_row = $cek_duplicate->row()->jum_row;
    if ($jum_row >= 2) {
        $link = base_url("$back_url");
        echo ("<script LANGUAGE='JavaScript'>
                window.alert('Row Terdapat Duplikasi, Mohon Infokan Ke Tim IT ');
                window.location.href='$link';
                </script>");
    }
?>

<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
    }
</style>

</div>
<div class="container">

<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>    
</form>

<?= form_open($url); ?>

<hr>

    <div class="row">
        <div class="col-md-2">
            Tanggal Dbsls
        </div>
        <div class="col-md-5">
            : <?= $get_draft_nota_retur->row()->tanggal; ?>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-2">
            Ref
        </div>
        <div class="col-md-5">
            : <?= $get_draft_nota_retur->row()->ref; ?>
        </div>
    </div>

<hr>

    <div class="row">
        <div class="col-md-12">

            <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
                <thead>
                    <tr>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Productid</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">NamaProduct</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Batch</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">QtyKecil</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Retur</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">QtyAjuanRetur</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">QtyLPK</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">SelisihQty</th>
                        <th class="text-center col-1">QTY Nota Retur</th>
                        <th class="text-center col-1">Beli</th>
                        <th class="text-center col-1">Jual</th>
                        <th class="text-center col-1">Disc Cabang</th>
                        <th class="text-center col-1">Disc Beli</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    // var_dump($get_draft_nota_retur);
                    // die;
                    foreach ($get_draft_nota_retur->result() as $a) : ?>
                    <tr>
                        <td>
                            <input type="hidden" name="kodeprod[]" value="<?= $a->productid; ?>">
                            <?= $a->productid; ?>
                        </td>
                        <td>
                            <input type="hidden" name="namaprod[]" value="<?= $a->nama_product; ?>">
                            <?= $a->nama_product; ?>
                        </td>
                        <td>
                            <input type="hidden" name="batch_number[]" value="<?= $a->batch_number; ?>">
                            <?= $a->batch_number; ?>
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
                            <input type="hidden" name="qty_ajuan_retur[]" value="<?= $a->qty_ajuan_retur; ?>">
                            <?= $a->qty_ajuan_retur; ?>
                        </td>
                        <td>
                            <input type="hidden" name="qty_lpk[]" value="<?= $a->qty_lpk; ?>">
                            <?= $a->qty_lpk; ?>
                        </td>
                        <td>
                            <input type="hidden" name="selisih_qty[]" value="<?= $a->selisih_qty; ?>">
                            <?= $a->selisih_qty; ?>
                            <input type="hidden" name="brandid[]" value="<?= $a->brandid; ?>">
                            <input type="hidden" name="kode_prc[]" value="<?= $a->kode_prc; ?>">
                        </td>
                        <td><input type="text" name="qty_nota_retur[]" value="<?= $a->qty_lpk; ?>" size="10"></td>
                        <td><input type="text" name="beli[]" value="<?= $a->beli; ?>" size="10"></td>
                        <td><input type="text" name="jual[]" value="<?= $a->jual; ?>" size="10"></td>
                        <td><input type="text" name="disc_cabang[]" value="<?= $a->disc_cabang; ?>" size="10"></td>
                        <td><input type="text" name="disc_beli[]" value="<?= $a->disc_beli; ?>" size="10"></td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <label for="customerid">CustomerId</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="customerid" value="<?= $customerid ?>" readonly>
            <input type="hidden" class="form-control" name="signature" value="<?= $signature ?>" readonly>
            <input type="hidden" class="form-control" name="signature_ajuan_retur" value="<?= $signature_ajuan_retur ?>" readonly>
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
            <textarea name="alamat_wp" cols="30" rows="3" class="form-control" readonly><?= $alamat_wp ?></textarea>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <label for="ref">Ref</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="ref" value="<?= $get_draft_nota_retur->row()->ref; ?>">
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
            <label for="noseri">No Seri Acuan Penjualan (noseri - masterDbsls)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="noseri" value="<?= $no_seri_pajak ?>" readonly>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label for="noseri_beli">No Seri Acuan Pembelian (noseri_beli)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="noseri_beli" value="<?= $get_draft_nota_retur->row()->no_inv; ?>">
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
            <label for="tglbuat">Tanggal Pembelian (tgldo_beli)</label>
        </div>
        <div class="col-md-5">
            <input type="date" class="form-control" name="tgldo_beli" value="<?= $get_draft_nota_retur->row()->tanggal; ?>">
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

    <div class="row mt-1">
        <div class="col-md-4"></div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-info">Next Preview Draft Nota Retur</button>
        </div>

    </div>

    <br><br>
    <?= form_close();?>


    


<script>
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
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
