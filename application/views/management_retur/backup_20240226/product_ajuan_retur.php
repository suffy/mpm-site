
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

<div class="container">
    <?php
        session_start();
        if (isset($_SESSION['status'])) {
    ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error : </strong> <?= $_SESSION['status']; ?>
    </div>
    <?php
        unset ($_SESSION['status']);
    }
    ?>
    
    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            Branch : <?= $branch_name ?>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            SubBranch : <?= $nama_comp ?>
        </div>
    </div>

    </form>


    <div class="card-block mt-3">
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="display" style="display: inline-block; overflow-y: scroll" width="100%">
                    <thead>
                        <tr>
                            <th colspan="10" style="background-color: darkslategray;" class="text-center">
                                <font color="white"><strong><i> -- Data Original Ajuan Retur -- </i></strong></font>
                            </th>
                        </tr>
                        <tr>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Kodeprod
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-md-3">
                                <font color="white">Namaprod
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">BatchNumber
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Tahun
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">ED
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Jumlah
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Alasan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Satuan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Outlet
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Keterangan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">QTY LPK
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($get_product_ajuan_retur->result() as $a) : 
                            if ($versi == 2) {
                                # code
                                $jumlah = $a->qty_approval;
                            } else {
                                # code...
                                $jumlah = $a->jumlah;
                            }
                        ?>
                        <tr>
                            <td><?= $a->kodeprod; ?></td>
                            <td><?= $a->namaprod; ?></td>
                            <td><?= $a->batch_number; ?></td>
                            <td>
                                <?php if (substr($a->batch_number,-2) >= 20 AND substr($a->batch_number,-2) <= 24) {
                                    echo '20'.substr($a->batch_number,-2);
                                } else {
                                    echo 'Null';
                                }
                                ?>
                            </td>
                            <td><?= $a->expired_date; ?></td>
                            <td><?= $jumlah ?></td>
                            <td><?= $a->alasan; ?></td>
                            <td><?= $a->satuan; ?></td>
                            <td><?= $a->nama_outlet; ?></td>
                            <td><?= $a->keterangan; ?></td>
                            <td><?= $a->qty_lpk; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?= form_open($url); ?>

    <div class="card-block mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">

                <table id="table-sum" class="display" style="display: inline-block; overflow-y: scroll" width="100%">
                    <thead>
                        <tr>
                            <th colspan="10" style="background-color: darkslategray;" class="text-center">
                                <font color="white"><strong><i> -- Data Setelah di SUM BY KODEPROD -- </i></strong>
                                </font>
                            </th>
                        </tr>
                        <tr>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font size="1px" color="white"><input type="button" class="btn btn-default btn-sm"
                                        id="toggle" value="click all" onclick="click_all_request()">
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Kodeprod
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-md-2">
                                <font color="white">Namaprod
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">BatchNumber
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Tahun
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">ED
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">Jumlah Pengajuan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QTY LPK
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QtyNotaRetur
                            </th>
                            <th style="background-color: darkslategray;" class="text-center col-1">
                                <font color="white">QtyLPK - QtyNotaRetur
                            </th>
                            <!-- <th style="background-color: darkgreen;" class="text-center"><font color="white">Alasan</th>
                            <th style="background-color: darkgreen;" class="text-center"><font color="white">Satuan</th>
                            <th style="background-color: darkgreen;" class="text-center"><font color="white">Outlet</th>
                            <th style="background-color: darkgreen;" class="text-center"><font color="white">Keterangan</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <form action="<?= base_url() ?>management_retur/search_from_dbsls" method="get"></form>
                        <?php 
                        foreach ($get_product_ajuan_retur_sum->result() as $a) : 
                            if ($versi == 2) {
                                # code
                                $jumlah = $a->qty_approval;
                            } else {
                                # code...
                                $jumlah = $a->jumlah;
                            }
                        ?>
                        <tr>
                            <td class="col-1">
                                <?php 
                                    if ($a->selisih == null && $a->qty_lpk <> 0) { ?>
                                <center>
                                    <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>"
                                        value="<?= $a->id.'|'.$a->kodeprod.'|'.$a->qty_lpk.'|'.$jumlah.'|'.$a->batch_number; ?>">
                                </center>
                                <?php }
                                ?>

                            </td>
                            <td>
                                <label for="<?= $a->id; ?>"><?= $a->kodeprod; ?></label>
                            </td>
                            <td><?= $a->namaprod; ?></td>
                            <td><?= $a->batch_number; ?></td>
                            <td>
                                <?php if (substr($a->batch_number,-2) >= 20 AND substr($a->batch_number,-2) <= 24) {
                                    echo '20'.substr($a->batch_number,-2);
                                } else {
                                    echo 'Null';
                                }
                                ?>
                            </td>
                            <td><?= $a->expired_date; ?></td>
                            <td><?= $jumlah?></td>
                            <td><?= $a->qty_lpk; ?></td>
                            <!-- <td><?= $a->alasan; ?></td>
                            <td><?= $a->satuan; ?></td>
                            <td><?= $a->nama_outlet; ?></td>
                            <td><?= $a->keterangan; ?></td> -->
                            <td><?= $a->qty_nota_retur; ?></td>
                            <td><?= $a->selisih; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="tahun">Periode</label>
        </div>
        <div class="col-md-3">
            <input type="month" name="from" id="from" class="form-control" required>
        </div>
        TO
        <div class="col-md-3">
            <input type="month" name="to" id="to" class="form-control" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="branch">Customer / Branch</label>
        </div>
        <div class="col-md-3">
            <select name="branch" id="branch" class="form-control" required>
            </select>
        </div>
    </div>
    <br>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="branch">&nbsp;</label>
            <input type="hidden" name="signature_ajuan_retur" value="<?= $signature_ajuan_retur ?>">
        </div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-info">cari faktur pajak</button>
        </div>
    </div>


    <?= form_close();?>

    <br>
    <br>

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
            $("#table-sum").DataTable({
                "pageLength": 1000,
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                // "fixedHeader": {
                //     header: true,
                //     footer: true
                // }
            });
        });
    </script>

    <script>
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/branch'); ?>',
            data: '',
            success: function (hasil_branch) {
                $("select[name = branch]").html(hasil_branch);
            }
        });
    </script>

    <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
    </script>