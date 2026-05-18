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
                <a href="<?= base_url().'management_sales/export/'.$filename ?>">
                    <span class="btn btn-submit-orange pt-2 pb-2" style="cursor: pointer; font-size: 13px; font-weight: bold;border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">Export</span>
                </a>
                <a href="<?= base_url().'management_sales/export_horizontal/'.$filename ?>">
                    <span class="btn btn-submit-orange pt-2 pb-2" style="cursor: pointer; font-size: 13px; font-weight: bold;border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">Export Horizontal</span>
                </a>
                <a href="<?= base_url().'management_sales/history_penarikan' ?>" target="_blank">
                    <span class="btn btn-submit-orange pt-2 pb-2" style="cursor: pointer; font-size: 13px; font-weight: bold;border-radius: 5px; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">History</span>
                </a>
                
            </div>
        </div>

        <div class="card-block mt-5 mb-1">
            <div class="row">
                <div class="col-md-12 d-flex flex-row gap-2">

                    <div class="card">
                        <div class="card-header" style="background-color: #fff;">
                            <span class="title">Sum Value</span>
                        </div>
                        <div class="card-content">
                            <div class="main-value" style="font-size: 16px; font-weight: bold; color: black; background-color: #fff;">Rp. <?= number_format($total_value, 0, ',', '.') ?></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="background-color: #fff;">
                            <span class="title">Sum Unit</span>
                        </div>
                        <div class="card-content">
                            <div class="main-value" style="font-size: 16px; font-weight: bold; color: black; background-color: #fff;"><?= number_format($total_unit, 0, ',', '.') ?></div>
                        </div>
                    </div>
                

                    <div class="card">
                        <div class="card-header" style="background-color: #fff;">
                            <span class="title">Count Kode Produk</span>
                        </div>
                        <div class="card-content">
                            <div class="main-value" style="font-size: 16px; font-weight: bold; color: black; background-color: #fff;"><?= number_format($count_kodeprod, 0, ',', '.') ?></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" style="background-color: #fff;">
                            <span class="title">Count sub branch</span>
                        </div>
                        <div class="card-content">
                            <div class="main-value" style="font-size: 16px; font-weight: bold; color: black; background-color: #fff;"><?= number_format($count_site_code, 0, ',', '.') ?></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" style="background-color: #fff;">
                            <span class="title">Count Row</span>
                        </div>
                        <div class="card-content">
                            <div class="main-value" style="font-size: 16px; font-weight: bold; color: black; background-color: #fff;"><?= number_format($count_row, 0, ',', '.') ?></div>
                        </div>
                    </div>
        
                </div>
            </div>
        </div>

        <div class="card-block mt-5 mb-1">
            <div class="row">
                <div class="col-md-12">
                    <table id="tabel" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 1%">site_code</th>              
                                <th class="text-center" style="width: 10%">branch_name</th>              
                                <th class="text-center" style="width: 10%">nama_comp</th>              
                                <th class="text-center" style="width: 1%">bulan</th>              
                                <th class="text-center" style="width: 1%">unit</th>              
                                <th class="text-center" style="width: 1%">value</th>              
                                <th class="text-center" style="width: 1%">ot</th>              
                            </tr>
                        </thead>
                        <tbody>     
                            <?php
                            foreach ($data->result() as $a) : ?>
                            <tr>
                                <td><?= $a->site_code ?></td>
                                <td><?= $a->branch_name ?></td>
                                <td><?= $a->nama_comp ?></td>
                                <td><?= $a->bulan ?></td>
                                <td><?= $a->unit ?></td>
                                <td>Rp. <?= number_format($a->value) ?></td>
                                <td><?= $a->trans ?></td>
                            </tr>
                            <?php endforeach; ?>   
                        </tbody>
                    </table>

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
