</div>

<div class="container-fluid">
    <div class="row mt-2">
        <div class="col-md-12 az-content-label text-center">
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

    <div>
        <form action="<?= base_url($url); ?>" method="post">
            <div class="row">
                <div class="col-md-12">
                    <table id="test">
                        <thead>
                            <tr>
                                <th>Kodeprod</th>
                                <th>Namaprod</th>
                                <th>Batch</th>
                                <th>ED</th>
                                <th>Nama Outlet</th>
                                <th>Qty Pemusnahan</th>
                                <th>Qty Validasi Pemusnahan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach ($get_pengajuan_detail->result() as $a) : ?>
                            <tr>
                                <td><?= $a->kodeprod ?></td>
                                <td><?= $a->namaprod ?></td>
                                <td><?= $a->batch_number ?></td>
                                <td><?= date('d M Y', strtotime($a->expired_date)); ?></td>
                                <td><?= $a->nama_outlet ?></td>
                                <td>
                                    <?= $a->qty_pemusnahan ?>
                                    <input type="number" class="form-control" name="qty_pemusnahan[]"
                                        value="<?= $a->qty_pemusnahan ?>" hidden>
                                </td>
                                <td>
                                    <input type="number" id="<?= $a->id; ?>" name="id_detail[]" class="<?= $a->id; ?>"
                                        value="<?= $a->id; ?>" hidden>
                                    <?php
                                        if ($a->qty_final == null) { ?>
                                    <input type="number" class="form-control" name="qty_final[]"
                                        value="<?= $a->qty_pemusnahan ?>" min="0" max="<?= $a->qty_pemusnahan ?>"
                                        required>
                                    <?php }else{ ?>
                                    <input type="number" class="form-control" name="qty_final[]"
                                        value="<?= $a->qty_final ?>" min="0" max="<?= $a->qty_pemusnahan ?>" required>
                                    <?php } ?>
                                </td>
                                <td><textarea class="form-control" cols="50" name="keterangan_final[]"
                                        placeholder="Masukan keterangan (Opsional)"><?= $a->keterangan_pemusnahan; ?></textarea>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-4 mb-4">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <input type="hidden" name="supp" value="<?= $supp ?>">
                <div class="col-md-12" style="text-align: center;">
                    <?php          
                        // echo "status_ho : ".$status_ho;
                        if ($status_ho->num_rows() > 0) {
                            if ($get_pengajuan->row()->validasi_pemusnahan_at) {
                                echo '<button type="button" class="btn btn-dark" disabled>data anda sudah masuk</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-submit-black">Submit Data</button>';
                            }
                        }
                    ?>
                    <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-submit-black">Back to
                        dashboard</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#test").DataTable({
            "paging": false,
            "scrollCollapse": true,
            "scrollY": '500px',
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
        $("#example").DataTable({
            "pageLength": 5,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
    });
</script>