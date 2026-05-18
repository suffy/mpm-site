<style>
    td {
        height: 40px;
        font-size: 14px;
    }

    th {
        height: 40px;
        font-size: 15px;
    }
    pre{
        /* white-space: pre-wrap;
        word-wrap: break-word; */
        background-color: var(--bs-dark-border-subtle);
        color: var(--bs-body-color);
        padding: 10px;
        border-radius: 10px;
    }
</style>

</div>

<div class="container-fluid mt-2">

    <!-- </div> -->

    <div class="card-block mt-5 mb-5">
        <div class="row">
            <h5>Tabel Order Produk</h5>
            <!-- <div class="col-md-12 mt-2">
                <a href="<?= base_url($url_update_pp_po) ?>" type="button" class="btn btn-submit-black btn-sm">Cek Purchase Plan</a>
            </div> -->

            <div class="table-responsive mt-3">
                <table id="tabel-data">
                    <thead>
                        <tr>
                            <th width="10%">Kodeprod</th>
                            <!-- <th width="10%">prc</th> -->
                            <th width="20%">Namaprod</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Karton</th>
                            <th>Berat</th>
                            <th>Volume</th>
                            <th>IsiSatuan</th>
                            <th>PP_unit</th>
                            <th>Actual PO</th>
                            <th>Selisih</th>
                            <th class="text-center">UpdateAt</th>
                            <th class="text-center">UpdatedBy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_data->result() as $a) : ?>
                            <tr>
                                <td>
                                    <?php
                                    if ($a->deleted == 1) { ?>
                                        <span class="strike"><?= $a->kodeprod ?></span>
                                    <?php
                                    } else {
                                        echo $a->kodeprod;
                                    }
                                    ?>
                                </td>
                                <!-- <td>
                                    <?php
                                    if ($a->deleted == 1) { ?>
                                        <span class="strike"><?= $a->kode_prc ?></span>
                                    <?php
                                    } else {
                                        echo $a->kode_prc;
                                    }
                                    ?>
                                </td> -->
                                <td>
                                    <?php
                                    if ($a->deleted == 1) { ?>
                                        <span class="strike"><?= $a->namaprod ?></span>
                                    <?php
                                    } else {
                                        echo $a->namaprod;
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($a->deleted == 1) { ?>
                                        <span class="strike">
                                            <p class="status pending-finance" style="padding: 5px 15px; margin-top: 15px; font-weight: bold; font-size: 14px"><?= $a->banyak ?></p>
                                        </span>
                                    <?php
                                    } else { ?>
                                        <p class="status pending-finance" style="padding: 5px 15px; margin-top: 15px; font-weight: bold; font-size: 14px"><?= $a->banyak ?></p>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?= $a->banyak_karton ?>
                                </td>
                                <td class="text-center"><?= $a->berat ?></td>
                                <td class="text-center"><?= $a->volume ?></td>
                                <td class="text-center"><?= $a->isisatuan ?></td>
                                <td class="text-center"><?= $a->pp_unit ?></td>
                                <td class="text-center"><?= $a->actual_po_bulan_ini ?></td>
                                <td class="text-center"><?= $a->selisih_po ?></td>
                                <td class="text-center"><?= $a->updated_at ?></td>
                                <td class="text-center"><?= $a->username ?></td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            fixedHeader: {
                header: true,
            },
            paging: false,
            scrollCollapse: true,
            scrollY: '500px'
        });

        var is_pp_approval = <?= $is_pp_approval; ?>

        if (is_pp_approval != 1) {
            $('.pp_approval').remove()
        } 
    });
</script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>