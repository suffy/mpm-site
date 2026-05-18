<style>
    td {
        font-size: 12px;
    }

    th {
        font-size: 12px !important;
        text-align: center !important
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
                <div class="form-inline row">
                    <div class="col-sm-6">
                        <form action="<?= $url_search ?>">
                            <input class="form-control" type="date" name="from" value="<?= $from ?>" required />
                            <input class="form-control" type="date" name="to" value="<?= $to ?>" required />
                            <button type="submit" value="1" class="btn btn-outline-danger btn-sm" name="type">Search By
                                Date</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-block mt-5">
            <?php 
                if($this->session->flashdata('pesan_gagal')){ ?>
            <div class="alert alert-danger" role="alert">
                <?= $this->session->flashdata('pesan_gagal'); ?>
            </div>
            <?php
                }elseif($this->session->flashdata('pesan_berhasil')){ ?>
            <div class="alert alert-success" role="alert">
                <?= $this->session->flashdata('pesan_berhasil'); ?>
            </div>
            <?php   } ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- <table id="example" class="display" style="display: inline-block;" width="100%"> -->
                    <table id="tabel-ajuan-biop" class="table table-striped table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>tanggal ajuan</th>
                                <th>Ajuan</th>
                                <th>LPK</th>
                                <th>principal</th>
                                <th width="20%">subBranch</th>
                                <th>nama status</th>
                                <th width="10%">Count Nota Retur</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($get_ajuan_retur)) : ?>
                            <?php foreach ($get_ajuan_retur->result() as $a) : ?>
                            <tr>
                                <td><?= date('Y-m-d', strtotime($a->tanggal_pengajuan)); ?></td>
                                <td>
                                    <a href="<?= base_url() ?>retur/log_retur/<?= $a->signature.'/'.$a->supp.'/'.$a->versi?>"
                                        target="_blank" class="btn btn-dark btn-sm"><?= $a->no_pengajuan; ?></a>
                                </td>
                                <td><?= $a->no_terima; ?></td>
                                <td><?= $a->principal; ?></td>
                                <td><?= $a->nama_comp; ?></td>
                                <td><?= $a->nama_status; ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url() ?>management_retur/comparing_product_ajuan/<?= $a->signature.'/'.$a->versi; ?>" class="btn btn-warning btn-sm" target="_blank"><?= $a->count_nota_retur; ?></a>
                                    <?php if ($a->count_nota_retur > 0) {
                                    echo '<a href="'.base_url('management_retur/reset/'.$a->signature).'" class="btn btn-danger btn-sm">Reset</a>';
                                }?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>

                </div>

                <div class="col-md-6">
                    <!-- <input type="submit" class="btn btn-info" value="Proses Pengajuan Retur"> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tabel-ajuan-biop').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
    });
</script>