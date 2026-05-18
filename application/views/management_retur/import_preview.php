<style>
    th {
        font-weight: bold;
        background-color: #FFEAA7;
        border: 0.5px solid #383838;
        color: #000000;
        font-size: 12px;
    }

    td {
        background-color: #ffffff;
        border: 0.5px solid #000000;
        font-size: 12px;
        /* line-height: 5px; */
        overflow: hidden;
    }

    .btn-update {
        background-color: #FFEAA7;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 15px 5px 15px;
    }

    .btn-save {
        color: #f0f0f0;
        background-color: #638889;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 15px 5px 15px;
    }
</style>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="row mt-5">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="col-md-12">
        <p class="mt-3">
            Principal : <?= $get_data->row()->namasupp; ?>
            <br>
            Branch : <?= $get_data->row()->company; ?>
        </p>

        <form action="<?= base_url('management_retur/import_update');?>" method="post" class="mt-3">
            <input type="text" value="<?= $get_data->row()->signature; ?>" name="signature" hidden>

            <table id="example" class="display" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">
                            No
                        </th>
                        <th class="text-center">
                            Tanggal Faktur(m/d/y)
                        </th>
                        <th class="text-center">
                            Noseri Pembelian
                        </th>
                        <th class="text-center">
                            Noseri Penjualan
                        </th>
                        <th class="text-center">
                            Kodeprod
                        </th>
                        <th class="text-center">
                            Nama Produk
                        </th>
                        <th class="text-center">
                            Qty
                        </th>
                        <th class="text-center">
                            Harga Satuan
                        </th>
                        <th class="text-center">
                            Diskon
                        </th>
                        <th class="text-center">
                            Harga Beli
                        </th>
                        <th class="text-center">
                            Diskon Beli
                        </th>
                        <th class="text-center">
                            Tanggal Nr(m/d/y)
                        </th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><input type="text" size="10" value="<?= $a->id; ?>" name="id[]" class="edit_actived"
                                hidden><?= $no++?></td>
                        <td><input type="text" size="10" value="<?= $a->tanggal_faktur; ?>" name="tanggal_faktur[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->tanggal_faktur; ?></p>
                        </td>
                        <td><input type="text" size="10" value="<?= $a->noseri_pembelian; ?>" name="noseri_pembelian[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->noseri_pembelian; ?></p>
                        </td>
                        <td><input type="text" size="10" value="<?= $a->noseri_penjualan; ?>" name="noseri_penjualan[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->noseri_penjualan; ?></p>
                        </td>
                        <td><input type="text" size="10" value="<?= $a->kodeprod; ?>" name="kodeprod[]"
                                class="edit_actived edit_<?= $no ?>" hidden><?= $a->kodeprod; ?></td>
                        <td><?= $a->namaprod; ?></td>
                        <td><input type="text" size="10" value="<?= $a->qty; ?>" name="qty[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->qty; ?></p>
                        </td>
                        <td><input type="text" size="10" value="<?= $a->harga_satuan; ?>" name="harga_satuan[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->harga_satuan; ?></p>
                        </td>
                        <td><input type="text" size="10" value="<?= $a->diskon; ?>" name="diskon[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->diskon; ?></p>
                        </td>
                        <td><input type="text" size="10" value="<?= $a->harga_beli; ?>" name="harga_beli[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->harga_beli; ?></p>
                        </td>
                        <td><input type="text" size="10" value="<?= $a->diskon_beli; ?>" name="diskon_beli[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->diskon_beli; ?></p>
                        </td>
                        <td><input type="text" size="10" value="<?= $a->tanggal_nr; ?>" name="tanggal_nr[]"
                                class="edit_actived edit_<?= $no ?>">
                            <p class="edit_deactived<?= $no ?>"><?= $a->tanggal_nr; ?></p>
                        </td>
                        <td>
                            <a class="btn btn-default btn-lg" onclick="edit_actived(<?= $no ?>)"><i
                                    class="typcn typcn-pen"></i>
                                <font size="2px">edit</font>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div align='center'>
                <button type="submit" class="btn btn-update">Update</button>
                <a href="<?= base_url('management_retur/import_submit/'.$get_data->row()->signature);?>" type="submit"
                    class="btn btn-save">Save</a>
            </div>
        </form>
    </div>
</div>

<script>
    $('.edit_actived').hide();
    $('.btn-update').hide();
    $(document).ready(function () {
        $('#example').DataTable({
            scrollX: true
        });
    });

    function edit_actived(params) {
        $('.edit_' + params).show();
        $('.edit_deactived' + params).hide();
        $('.btn-save ').hide();
        $('.btn-update').show();
    }
</script>