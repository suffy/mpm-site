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
        font-size: 14px;
        text-align: center;
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
            <div class="col-md-12">
                Subbranch : <?= $branch_name." - ".$nama_comp ?>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-12">
                No : <?= $no_pengajuan ?>
            </div>
        </div>

        <?= form_open($url); ?>

        <div class="card-block mt-3">
            <div class="row">
                <div class="col-md-12">
                    <table id="example">
                        <thead>
                            <tr>
                                <th colspan="11"><strong><i>Data Original Ajuan Retur (setelah di sum)</i></strong></th>
                            </tr>
                            <tr>
                                <th>Kodeprod</th>
                                <th>BatchNumber</th>
                                <th>ED</th>
                                <!-- <th>Alasan</th> -->
                                <th>Satuan</th>
                                <th>Outlet</th>
                                <th>Keterangan</th>
                                <th>QtyAjuan</th>
                                <th>QtyLPK</th>
                                <th>QtyLPK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_product_ajuan_retur->result() as $a) : ?>
                            <!-- # versi baru jumlah dirubah jadi qty approval -->
                            <tr>
                                <td><?= $a->kodeprod.' - '.$a->namaprod; ?></td>
                                <td><?= $a->batch_number; ?></td>
                                <td><?= $a->expired_date; ?></td>
                                <!-- <td><?= $a->alasan; ?></td> -->
                                <td><?= $a->satuan; ?></td>
                                <td><?= $a->nama_outlet; ?></td>
                                <td><?= $a->keterangan; ?></td>
                                <td><?= $a->jumlah; ?></td>
                                <td>
                                    <?= ($a->qty_lpk) ? $a->qty_lpk : '<font color="red"><i>NULL</i></font>' ?>
                                    <!-- <?= $a->qty_lpk; ?> -->
                                </td>
                                <td>
                                    <input type="hidden" name="id[]" value="<?= $a->id; ?>" size="3">
                                    <input type="number" name="qty_lpk[]" value="<?= ($a->qty_lpk != NULL && $a->qty_lpk != 0) ? $a->qty_lpk : ($a->qty_final != NULL) ? $a->qty_final : $a->qty_approval_ho ?>" size="2" class="form-control">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-3 mb-5">
            <div class="col-md-5">
                <input type="hidden" name="signature_ajuan_retur" value="<?= $signature_ajuan_retur ?>">
                <button type="submit" class="btn btn-info">Update Qty LPK dan Lanjut ke Create Draft Nota Retur</button>
            </div>
        </div>

        <?= form_close();?>

    </div>
</div>

<script>
    $(document).ready(function () {
        $("#example").DataTable({
            paging: false,
            scrollCollapse: true,            
        });
    });
</script>