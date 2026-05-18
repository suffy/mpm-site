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

<div class="container-fluid mt-2 pp_approval">
    <form action="<?= base_url("$url_approv_pp") ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="signature" value="<?= $signature ?>">
        <div class="row mt-2">
            <div class="col-md-12">
                <label for="nomor_po" style="font-weight:bold">Approval Purchase Plan</label>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="alasan" >Approved By</label>
            </div>
            <div class="col-md-4">
                <select id="user" name="user" class="form-select" required>
                    <option value="433">I Gede Indra Wirama - igede.iw@muliaputramandiri.com</option>
                    <option value="445">Mohammad Firmansah - iman@muliaputramandiri.com </option>
                </select>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="alasan" >Upload File</label>
            </div>
            <div class="col-md-4">
                <input type="file" class="form-control" name="file" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">

            </div>
            <div class="col-md-9">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <?php
                if ($pp_approved_file == null) { ?>
                    <button type="submit" class="btn btn-submit-black">Submit</button>
                <?php
                } else { ?>
                    <button type="submit" class="btn btn-submit-black" disabled>Anda Sudah Upload File</button>
                <?php
                }
                ?>
            </div>
        </div>
    </form>
</div>

<hr class="mt-5 pp_approval">

<div class="container-fluid mt-2">
    <?php echo form_open($url_finance); ?>
    <div class="row mt-2">
        <div class="col-md-12">
            <label for="nomor_po" style="font-weight:bold">1. Request Approval to Finance</label>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="alasan" >Alasan Approval</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="alasan" value="By Pass" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">

        </div>
        <div class="col-md-9">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <?php
            if ($status == 2 && $status_approval == 1 && $flag_open == 0) { ?>
                <button type="submit" class="btn btn-submit-black" disabled>Please Wait Finance Approval</button>
            <?php
            } elseif ($status == 2 && $status_approval == 1 && $flag_open == 1) { ?>
                <button type="submit" class="btn btn-submit-black" disabled>Already open at <?= $open_date ?></button>
            <?php
            } else { ?>
                <button type="submit" class="btn btn-submit-black">Request Approval to Finance</button>
            <?php
            }
            ?>
        </div>
    </div>
    <?= form_close(); ?>
</div>

<hr class="mt-5">


<div class="container-fluid mt-5">
    <?php echo form_open($url_rilis); ?>

    <div class="row mt-2">
        <div class="col-md-12">
            <label for="nomor_po" style="font-weight:bold">2. Rilis PO</label>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="Note" >Note</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="note" value="<?= $note ?>">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="po_ref" >PO REF (* wajib diisi)</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="po_ref" value="<?= $po_ref ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">

        </div>
        <div class="col-md-9">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <?php
            if ($nopo) { ?>
                <button type="submit" class="btn btn-submit-black" disabled>released number : <?= $nopo ?></button>
                <a href="<?= base_url() ?>spk/email_po/<?= $signature ?>" class="btn btn-submit-red" style="height: 45px; padding-top: 10px" target="_blank">Email PO</a>
            <?php
            } else { ?>
                <?php
                if ($flag_open == 1) { ?>
                    <button type="submit" class="btn btn-submit-black">Rilis PO</button>
                <?php
                } else { ?>
                    <button type="submit" class="btn btn-submit-black" disabled>Please Wait Finance Approval</button>
                <?php
                }
                ?>

            <?php
            }
            ?>

        </div>
    </div>
    <?= form_close(); ?>
</div>

<hr class="mt-5">

<?php echo form_open($url_update); ?>

<div class="container-fluid mt-5">
    <div class="row mt-2">
        <div class="col-md-2">
            <label for="tipe" >Tipe</label>
        </div>
        <div class="col-md-4">
            <select name="tipe" class="form-control" required>
                <option value="S" <?php if ($tipe == 'S') echo "selected"; ?>>SPK</option>
                <option value="A" <?php if ($tipe == 'A') echo "selected"; ?>>ALOKASI</option>
                <option value="R" <?php if ($tipe == 'R') echo "selected"; ?>>REPLENISHMENT</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="note" >Note</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="note" value="<?= $note ?>">

        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="po_ref" >Po Ref</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="po_ref" value="<?= $po_ref ?>">

        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">

        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <button type="submit" class="btn btn-submit-red" style="height:44px;">Update Data</button>
        </div>
    </div>

    <?= form_close(); ?>
</div>

<hr class="mt-5">

<!-- <div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="code-block">
                
            </div>
        </div>
    </div>
</div> -->

<div class="container-fluid mb-5">
    <div class="row">
       <div class="col-md-12">
<pre>
Fitur import di bawah ini, menggunakan "Unit terkecil" <span class="badge badge-warning"><i>bukan</i></span> "karton". Sehingga dapat dimanfaatkan untuk Luliana.
</pre>
       </div>
    </div>
</div>



<?php echo form_open_multipart($url_import); ?>

<div class="container-fluid mt-1">

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="expose" >File Import</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" name="file" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">

        </div>
        <div class="col-md-9">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <button type="submit" class="btn btn-submit-red" style="height:44px;width:80px">Import</button>
            <a href="<?= base_url('spk/export_template_list_order/' . $signature) ?>" class="btn btn-submit-black">Download Template List Order</a>
            <button type="button" class="btn btn-submit-black" onclick="convertTable()">Export to Excel</button>
            <a href="<?= base_url('spk/list_order') ?>" class="btn btn-submit-black">Kembali</a>
        </div>
    </div>
    <?= form_close(); ?>

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
                            <th class="text-center">Action</th>
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
                                    <?php
                                    if ($a->deleted == 1) { ?>
                                        <span class="strike">
                                            <p class="status pending-scm" style="padding: 5px 15px; margin-top: 15px; font-weight: bold; font-size: 14px"><?= $a->banyak_karton ?></p>
                                        </span>
                                    <?php
                                    } else { ?>
                                        <?php echo form_open($url_update_karton); ?>
                                            <input type="hidden" name="signature" value="<?= $signature ?>">
                                            <input type="hidden" name="id_po_detail" value="<?= $a->id ?>">
                                            <input type="hidden" name="isisatuan" value="<?= $a->isisatuan ?>">
                                            <input type="number" value="<?= $a->banyak_karton ?>" name="banyak_karton" class="form-control">
                                            <input type="submit" value="Update" class="btn btn-submit-black" style="width: 100%">
                                        <?= form_close(); ?>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-center"><?= $a->berat ?></td>
                                <td class="text-center"><?= $a->volume ?></td>
                                <td class="text-center"><?= $a->isisatuan ?></td>
                                <td class="text-center"><?= $a->pp_unit ?></td>
                                <td class="text-center"><?= $a->actual_po_bulan_ini ?></td>
                                <td class="text-center"><?= $a->selisih_po ?></td>
                                <td class="text-center"><?= $a->updated_at ?></td>
                                <td class="text-center"><?= $a->username ?></td>
                                <td>
                                    <div class="btn-group">
                                        <?php
                                        if ($a->deleted == 1) { ?>
                                            <span class="strike">
                                                <a href="<?= base_url() ?>spk/list_order_detail_undelete/<?= $a->id . '/' . $signature ?>" class="delete-button" onclick="return confirm('Kembalikan data ini ?')" style="background-color: #EF9C66;"><span style="color: #000;"><strong>Undo</strong></span></a>
                                            </span>
                                        <?php
                                        } else { ?>
                                            <!-- <a href="<?= base_url() ?>spk/list_order_detail_delete/<?= $a->id . '/' . $signature ?>" class="delete-button" onclick="return confirm('Hapus data ini ?')">delete</a> -->

                                            <a href="<?= base_url() ?>spk/list_order_detail_delete/<?= $a->id . '/' . $signature ?>" onclick="return confirm('Ingin menghapus data ini ?')" class="delete-button" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>

                                        <?php
                                        }
                                        ?>

                                    </div>
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
            // fixedHeader: {
            //     header: true,
            // },
            // paging: false,
            // scrollCollapse: true,
            // scrollY: '500px',
            aLengthMenu: [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });

        var is_pp_approval = <?= $is_pp_approval; ?>

        if (is_pp_approval != 1) {
            $('.pp_approval').remove()
        } 
    });

    // $.ajax({
    //     type: 'POST',
    //     url: "<?= base_url('spk/master_user'); ?>",
    //     data: {},
    //     success: function(hasil_user) {
    //         $("select[name = user]").html(hasil_user);
    //     }
    // });
</script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>