<style>

    input, textarea, select {
    padding: 10px;
    max-width: 100%;
    width:100%;
    line-height: 1.5;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: 1px 1px 1px #999;
    }
</style>

<?= $this->load->view('spk/component/title'); ?>

<?php echo form_open($url); ?>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="company">Di kirim kepada</label>
    </div>
    <div class="col-md-6">
        <input type="text" name="company" id="company" value="<?= $company ?>" readonly>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="npwp">NPWP</label>
    </div>
    <div class="col-md-6">
        <input type="text" name="npwp" id="npwp" value="<?= $npwp ?>" readonly>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="alamat">Alamat DP</label>
    </div>
    <div class="col-md-6">
        <textarea name="alamat" cols="30" rows="5" readonly><?= $alamat ?></textarea>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="email">Email</label>
    </div>
    <div class="col-md-6">
        <textarea name="email" cols="30" rows="3" readonly><?= $email ?></textarea>
    </div>
</div>



<div class="row mt-1">
    <div class="col-md-2">
        <label for="alamat">Tipe Dok</label>
    </div>
    <div class="col-md-6">
        <input type="text" name="tipe_tampil" id="tipe_tampil" value="<?= $tipe == 'S' ? 'SPK' : 'Alokasi' ?>" readonly>
        <input type="hidden" name="tipe" id="tipe" class="form-control" value="<?= $tipe ?>" readonly>
        <input type="hidden" name="kode_alamat" id="kode_alamat" class="form-control" value="<?= $kode_alamat ?>" readonly>
        <input type="hidden" name="id_header" id="id_header" class="form-control" value="<?= $id_header ?>" readonly>
    </div>
</div>

<div class="card-block mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">

            <?php
            foreach ($get_supp->result() as $a) : ?>

                <div class="row">
                    <div class="mt-1 mb-2">
                        <h4><?= $a->namasupp ?></h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php

                        // echo "supp : ".$a->supp;
                        // echo "id_header : ".$a->id_header;
                        // echo "kode_alamat : ".$kode_alamat;
                        // die;

                        $get_produk = $this->model_spk->get_temp_spk_detail_by_supp_id_header_site_code($a->supp, $a->id_header, $kode_alamat);
                        $get_sum = $this->model_spk->get_sum_in_temp_spk_detail_by_supp_id_header($a->supp, $a->id_header);

                        // var_dump($get_produk);
                        // die;

                        ?>
                        <table id="<?= $a->supp ?>">
                            <thead>
                                <tr>
                                    <th width="10%">Kodeprod</th>
                                    <th width="50%">Namaprod</th>
                                    <th width="10%">Karton</th>
                                    <th width="10%">Berat</th>
                                    <th width="10%">Volume</th>
                                    <th width="10%">Average</th>
                                    <th width="10%">Ratio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($get_produk->result() as $p) : ?>
                                    <tr>
                                        <td><?= $p->kodeprod ?></td>
                                        <td><?= $p->namaprod ?></td>
                                        <td align="right"><?= $p->jml_karton ?></td>
                                        <td align="right"><?= $p->berat_produk ?></td>
                                        <td align="right"><?= $p->volume_produk ?></td>
                                        <td align="right"><?= $p->average_karton ?></td>
                                        <td align="right"><?= $p->ratio ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan=2 style="height:50px;" class="text-center"><strong>SUB TOTAL</strong></td>
                                    <td class="text-end"><strong>
                                            <font size="4px"><?= $get_sum->row()->jml_karton ?> Karton</font>
                                        </strong></td>
                                    <td class="text-end"><strong>
                                            <font size="4px"><?= $get_sum->row()->berat_produk ?> Kg</font>
                                        </strong></td>
                                    <td class="text-end"><strong>
                                            <font size="4px"><?= $get_sum->row()->volume_produk ?> m3</font>
                                        </strong></td>
                                    <td class="text-end"><strong>
                                            <font size="4px"></font>
                                        </strong></td>
                                    <td class="text-end"><strong>
                                            <font size="4px"></font>
                                        </strong></td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
if ($get_supp->row('supp') == 005) {
    if ($get_sum->row()->berat_produk < $moq_us) {
        echo '<div class="row mb-1">
            <div class="col-lg-6">
                <div class="code-block">
                    <pre>
Information !
Pesanan anda akan di kirim ke alamat HO, Karena total berat pesanan anda
kurang dari '.$moq_us.' Kg.
                    </pre>
                </div>
            </div>
        </div>';
    }
}
?>

<!-- <div class="row mb-1">
    <div class="col-lg-6">
        <input type="checkbox" name="check" id="check" value="1" required>
        <label for="check">Saya sudah mengecek ulang semua data di atas</label>
    </div>
</div> -->

<div class="form-check mb-4">
  <input class="form-check-input" name="check" type="checkbox" value="1" id="check" required>
  <label class="form-check-label" for="check">
    Saya sudah mengecek ulang semua data di atas
  </label>
</div>


<div class="row mb-5">
    <div class="col-lg-12 d-flex justify-content-center btn-group">
        <a href="<?= base_url('spk/pengiriman') ?>" class="btn btn-submit-black" style="width: 50%">Kembali</a>
        <input type="submit" value="Checkout" class="btn btn-submit-orange" style="width: 50%">
    </div>
</div>

<br><br><br>

<script>
    $(document).ready(function() {
        $('#001').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#002').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#004').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#005').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#010').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#011').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
        $('#012').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo": false,
            "bPaginate": false
            // scrollX: true
        });
    });
</script>