<style>
    td {
        font-size: 11px;
    }

    th {
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

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="form-inline row">
                <div class="col-sm-6">
                    <form action="<?= $url_search ?>">
                        <input class="form-control" type="date" name="from" required />
                        <input class="form-control" type="date" name="to" required />
                        <button type="submit" value="1" class="btn btn-outline-danger btn-sm" name="type">Search By
                            Date</button>
                    </form>
                </div>

                <!-- Example single danger button -->
                <div class="col-sm-6" align="right">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Pilih Versi
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= base_url('management_retur/ajuan_retur/1')?>">Versi 1 (Data Lama)</a>
                        <a class="dropdown-item" href="<?= base_url('management_retur/ajuan_retur/2')?>">Versi 2 (Data Baru)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= form_open($url); ?>

    <div class="card-block mt-5">
        <div class="row">
            <div class="col-md-12">

                <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th class="text-center">
                                #
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">tanggal ajuan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">Ajuan
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">LPK
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">principal
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">subBranch
                            </th>
                            <th style="background-color: darkslategray;" class="text-center">
                                <font color="white">status Closed
                            </th>
                            <th style="background-color: darkslategray;">
                                <font color="white">Count Nota Retur</font>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($get_ajuan_retur->result() as $a) : ?>
                        <tr>
                            <td class="col-1">
                                <center>
                                    <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>"
                                        value="<?= $a->id; ?>">
                                </center>
                            </td>
                            <td><?= $a->tanggal_pengajuan; ?></td>
                            <td>
                                <a href="<?= base_url() ?>retur/log_retur/<?= $a->signature.'/'.$a->supp.'/'.$versi ?>"
                                    target="_blank" class="btn btn-dark btn-sm"><?= $a->no_pengajuan; ?></a>
                            </td>
                            <td><?= $a->no_terima; ?></td>
                            <td><?= $a->principal; ?></td>
                            <td><?= $a->nama_comp; ?></td>
                            <td><?= $a->nama_status; ?></td>
                            <td><a href="<?= base_url() ?>management_retur/comparing_product_ajuan/<?= $a->signature.'/'.$versi; ?>"
                                    class="btn btn-warning btn-sm" target="_blank"><?= $a->count_nota_retur ?></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="row mb-5 mt-2">
        <div class="col-md-6">
            <input type="submit" class="btn btn-info" value="Proses Pengajuan Retur">
        </div>
    </div>


    <?= form_close();?>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>