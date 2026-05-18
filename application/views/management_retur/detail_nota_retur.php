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
    </div>
</div>


<?= form_open($url);?>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-2">
            <div class="col-8">
                <?php 
                if($this->session->flashdata('pesan')){ ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
                <?php
                }elseif($this->session->flashdata('pesan_success')){ ?>
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                </div>
                <?php
                }
            ?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <label for="nodo">No Retur (nodo)</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="nodo" value="<?= $get_retur_by_id->row()->nodo ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="nodo_beli">No Retur Pembelian (nodo_beli)</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="nodo_beli"
                    value="<?= $get_retur_by_id->row()->nodo_beli ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="nodo_beli">No Seri Acuan Penjualan (noseri)</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="noseri" value="<?= $get_retur_by_id->row()->noseri ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="nodo_beli">No Seri Acuan Pembelian (noseri_beli)</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="noseri_beli"
                    value="<?= $get_retur_by_id->row()->noseri_beli ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="nodo_beli">No Tanda Terima (nopo)</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="nopo" value="<?= $get_retur_by_id->row()->nopo ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="nodo_beli">Tanggal Pembelian (tgldo_beli)</label>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="tgldo_beli"
                    value="<?= $get_retur_by_id->row()->tgldo_beli ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="nodo_beli">Tanggal Penjualan (tgldo)</label>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="tgldo" value="<?= $get_retur_by_id->row()->tgldo ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="nodo_beli">Tanggal Proses (tglbuat)</label>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="tglbuat" value="<?= $get_retur_by_id->row()->tglbuat ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="nodo_beli">Tanggal Beli (tgl_beli)</label>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="tgl_beli"
                    value="<?= $get_retur_by_id->row()->tgl_beli ?>">
                <input type="hidden" class="form-control" name="id" value="<?= $get_retur_by_id->row()->id ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-3" align="center">
                <div class="row">
                    <div class="col-md-5 mt-3">
                        <input type="submit" class="btn btn-info" value="Save Data">
                    </div>
                    <div class="col-md-7 mt-3">
                        <a href="<?= base_url().'management_retur/dashboard' ?>" class="btn btn-dark">Back to
                            dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="card-block mt-5">
            <div class="row">
                <div class="col-md-12">
                    <table id="example" class="display" width="100%">
                        <thead>
                            <tr>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Kodeprod
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Namaprod
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Qty
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Harga
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Diskon
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Harga Beli
                                </th>
                                <th style="background-color: darkslategray;" class="text-center">
                                    <font color="white">Diskon Beli
                                </th>
                                <th style="background-color: darkslategray;" class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($get_retur_by_id->result() as $a) : ?>
                            <tr>
                                <td><input type="text" size="10" value="<?= $a->kodeprod; ?>" name="kodeprod[]"
                                        class="edit" hidden><?= $a->kodeprod; ?></td>
                                <td><?= $a->namaprod; ?></td>
                                <td><input type="text" size="10" value="<?= $a->banyak; ?>" name="banyak[]"
                                        class="edit">
                                    <p class="text"><?= $a->banyak; ?></p>
                                </td>
                                <td><input type="text" size="10" value="<?= $a->harga; ?>" name="harga[]" class="edit">
                                    <p class="text"><?= number_format($a->harga,2); ?></p>
                                </td>
                                <td><input type="text" size="10" value="<?= $a->diskon; ?>" name="diskon[]"
                                        class="edit">
                                    <p class="text"><?= $a->diskon; ?></p>
                                </td>
                                <td><input type="text" size="10" value="<?= $a->harga_beli; ?>" name="harga_beli[]"
                                        class="edit">
                                    <p class="text"><?= number_format($a->harga_beli,2); ?></p>
                                </td>
                                <td><input type="text" size="10" value="<?= $a->diskon_beli; ?>" name="diskon_beli[]"
                                        class="edit">
                                    <p class="text"><?= $a->diskon_beli; ?></p>
                                </td>
                                <td>
                                    <a class="btn btn-default btn-lg" onclick="edit_actived()"><i
                                            class="typcn typcn-pen"></i>
                                        <font size="2px">edit</font>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close();?>

<!-- Button trigger modal -->

<script>
    $('.edit').hide();
    $(document).ready(function () {
        $('#example').DataTable();
    });

    function edit_actived() {
        $('.edit').show();
        $('p.text').hide();
    }
</script>