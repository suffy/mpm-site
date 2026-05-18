</div>
<?php $this->load->view('management_claim/css/style') ?>

<div class="container-fluid">

    <div class="card mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title"><?= $title ?></h5>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url().'penta/export_stok_dan_doi/'.$periode?>">
                        <span class="btn btn-submit-orange pt-2 pb-2" style="cursor: pointer; font-size: 13px; font-weight: bold;border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">Export</span>
                    </a>
                </div>
            </div>

            <div class="card-block mt-5 mb-1">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tabel" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 1%">bulan</th>              
                                    <th class="text-center" style="width: 10%">tahun</th>              
                                    <th class="text-center" style="width: 10%">area_id</th>              
                                    <th class="text-center" style="width: 1%">nama_area</th>              
                                    <th class="text-center" style="width: 1%">kode_produk</th>              
                                    <th class="text-center" style="width: 1%">item_id_vend</th>              
                                    <th class="text-center" style="width: 1%">nama_produk</th>
                                    <th class="text-center" style="width: 1%">qty</th>
                                    <th class="text-center" style="width: 1%">avg_unit</th>   
                                    <th class="text-center" style="width: 1%">doi_unit</th>              
                                </tr>
                            </thead>
                            <tbody>     
                                <?php
                                foreach ($get_data->result() as $a) : ?>
                                <tr>
                                    <td><?= $a->bulan ?></td>
                                    <td><?= $a->tahun ?></td>
                                    <td><?= $a->area_id ?></td>
                                    <td><?= $a->nama_area ?></td>
                                    <td><?= $a->kode_produk ?></td>
                                    <td><?= $a->item_id_vend ?></td>
                                    <td><?= $a->nama_produk ?></td>
                                    <td><?= $a->qty ?></td>
                                    <td><?= $a->avg_unit ?></td>
                                    <td><?= $a->doi_unit ?></td>
                                </tr>
                                <?php endforeach; ?>   
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });
</script>
