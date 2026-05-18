</div>
<div class="container-fluid">

<div class="row mb-4">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
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

<?php echo form_open($url); ?>
<div class="card-block mt-1 mb-5">
    <div class="row">
        <div class="col-md-12">
            
            <?php 
            foreach ($get_data->result() as $a) : ?>

            <div class="row">

                <div class="mt-3 mb-2">
                    <h4><?= $a->namasupp ?></h4>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php 
                        $get_produk = $this->model_spk->get_keranjang_belanja_detail_by_supp_and_id_header($a->supp, $a->id_header, $site_code);
                        $get_sum = $this->model_spk->get_sum_in_keranjang_belanja_detail_by_supp_and_id_header($a->supp, $a->id_header);                    
                    ?>

                    <table id="<?= $a->supp ?>" class="display mb-4" style="display: inline-block; overflow-y: scroll; width: 100%">
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
                                <td class="text-end"><strong><font size="4px"><?= $get_sum->row()->jml_karton ?> Karton</font></strong></td>
                                <td class="text-end"><strong><font size="4px"><?= $get_sum->row()->berat_produk ?> Kg</font></strong></td>
                                <td class="text-end"><strong><font size="4px"><?= $get_sum->row()->volume_produk ?> m3</font></strong></td>
                                <td class="text-end"><strong><font size="4px"></font></strong></td>
                                <td class="text-end"><strong><font size="4px"></font></strong></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
            <?php endforeach; ?> 
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-12 d-flex justify-content-center btn-group">
        <!-- <button type="button" class="btn btn-submit-black" onclick="javascript:history.go(-1)" style="width: 50%">Tambah Produk Lainnya</button> -->
         <a href="<?= base_url('spk/keranjang_belanja') ?>" class="btn btn-submit-black" style="width: 50%">Kembali</a>
        <input type="submit" value="Lanjut ke Alamat Kirim" class="btn btn-submit-orange" style="width: 50%">
    </div>
</div>

<?= form_close(); ?>

<script>
    $(document).ready(function () 
    {
        $('#001').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : false,
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
            "bInfo" : false,
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
            "bInfo" : false,
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
            "bInfo" : false,
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
            "bInfo" : false,
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
            "bInfo" : false,
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
            "bInfo" : false,
            "bPaginate": false
            // scrollX: true
        });
    });

    
    
</script>