<!-- <style>
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
</style> -->

<div class="container-fluid">
    <div class="col-md-12">
        <div class="col-md-12 mt-5">
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
                    <?php
                        if ($this->session->flashdata('pesan')) { ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?= $this->session->flashdata('pesan'); ?>
                            </div>
                        <?php
                        } elseif ($this->session->flashdata('pesan_success')) { ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?= $this->session->flashdata('pesan_success'); ?>
                            </div>
                        <?php
                        }
                    ?>
                </div>

                <div class="col-md-12">
                    Branch : <?= $branch_name ?>
                </div>
                <div class="col-md-12">
                    SubBranch : <?= $nama_comp ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="col-md-12">
        <?= form_open($url); ?>
        <div class="card-block mt-3 mb-5">
            <a href="<?= base_url($url_reset); ?>" class="btn btn-danger" type="button">Reset</a>
            <div class="row">
                <div class="col-md-12">
                    <table id="table-sum" class="display" style="display: inline-block;" width="100%">
                        <thead>
                            <tr>
                                <th colspan="10" class="text-center"><strong><i> -- Data Ajuan -- </i></strong></th>
                            </tr>
                            <tr>
                                <th class="text-center col-1" style="background-color: #1d1d1d; color: white">
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm"
                                            id="toggle" value="click all" onclick="click_all_request()"
                                            style="background-color: #1d1d1d; color: white">
                                </th>
                                <th class="text-center">
                                    Kodeprod
                                </th>
                                <th class="text-center col-md-2">
                                    Namaprod
                                </th>
                                <th class="text-center">
                                    BatchNumber
                                </th>
                                <th class="text-center">
                                    Tahun
                                </th>
                                <th class="text-center">
                                    ED
                                </th>
                                <th class="text-center col-1">
                                    Jumlah Pengajuan
                                </th>
                                <th class="text-center col-1">
                                    QTY LPK
                                </th>
                                <!-- <th style="background-color: darkslategray;" class="text-center col-1">
                                        <font color="white">QtyNotaRetur
                                    </th>
                                    <th style="background-color: darkslategray;" class="text-center col-1">
                                        <font color="white">QtyLPK - QtyNotaRetur
                                    </th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($get_product_ajuan_retur->result() as $a) : ?>
                            <tr>
                                <td class="col-1">
                                    <?php 
                                        if ($a->qty_lpk <> 0 && $a->noseri == null) { ?>
                                    <center><input type="checkbox" id="<?= $a->id; ?>" name="options[]"
                                            class="<?= $a->id; ?>" value="<?= $a->id; ?>"></center>
                                    <?php }
                                        ?>

                                </td>
                                <td>
                                    <label for="<?= $a->id; ?>"><?= $a->kodeprod; ?></label>
                                </td>
                                <td><?= $a->namaprod; ?></td>
                                <td><?= $a->batch_number; ?></td>
                                <td><?= $a->tahun; ?></td>
                                <td><?= $a->expired_date; ?></td>
                                <td><?= $a->jumlah?></td>
                                <td><?= $a->qty_lpk; ?></td>
                                <!-- <td><?= $a->qty_nota_retur; ?></td>
                                    <td><?= $a->selisih; ?></td> -->
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
            <div class="col-md-1">
                <label for="tahun">TO</label>
            </div>
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
        <div class="row mt-3">
            <div class="col-md-2">
                <label for="branch">Group By</label>
            </div>
            <div class="col-md-3">
                <select name="group" id="group" class="form-control" required>
                    <!-- <option value="">Pilih</option> -->
                    <option value="1">Kodeprod</option>
                    <!-- <option value="2">Tahun</option> -->
                </select>
            </div>
        </div>
        <div class="row mt-3 mb-2">
            <div class="col-md-2">
                <label for="branch">&nbsp;</label>
                <input type="hidden" name="signature_ajuan_retur" value="<?= $signature_ajuan_retur ?>">
            </div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-info">cari faktur pajak</button>
            </div>
        </div>
        <?= form_close();?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#table-sum").DataTable({
            paging: false,
            scrollCollapse: true,
            scrollY: '50vh'
        });
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?= base_url("database_afiliasi/branch"); ?>',
        data: '',
        success: function (hasil_branch) {
            $("select[name = branch]").html(hasil_branch);
        }
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script>