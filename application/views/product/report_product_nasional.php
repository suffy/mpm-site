</div>

<div class="container-fluid">
    <div class="az-content-body pd-lg-l-40 d-flex flex-column">
        <h2 id="form_spk"><?= $title; ?></h2>

        <div class="row mt-2">
            <div class="col-md">
                <a href='<?= base_url().'products/kenaikan_harga' ?>' class="btn btn-dark">Kembali</a>
            </div>
        </div>   
        
        <div class="card col-md-12 mt-2">
            <div class="card-body">
                <div class="mt-2 mb-4"><h5 class="card-title">Report Product Nasional</h5></div>   

                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?= base_url($url_export); ?>" class="pastel-orange-btn">Export Data</a>
                        </div>
                    </div>
                                
                    <div class="row mt-4 mb-5">
                        <table id="tabel" class="table-striped dataTable" style="width:100%">    
                            <thead>
                                <tr>                
                                    <th>Principal</th>
                                    <th>Kodeprod</th>
                                    <th>Namaprod</th>
                                    <th>TanggalAktif</th>
                                    <th>Label</th>
                                    <th>Harga Jual Grosir</th>
                                    <th>Harga Jual Retail</th>
                                    <th>Harga Jual Motoris Retail</th>
                                    <th>Harga Jual MT</th>                        
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1; 
                            foreach ($get_data->result() as $a) : ?>
                                <tr>
                                    <td><?= $a->namasupp; ?></td>
                                    <td><?= $a->kodeprod; ?></td>
                                    <td><?= $a->namaprod; ?></td>
                                    <td><?= $a->tanggal_aktif; ?></td>
                                    <td><?= $a->label; ?></td>
                                    <td><?= $a->harga_jual_grosir; ?></td>
                                    <td><?= $a->harga_jual_retail; ?></td>
                                    <td><?= $a->harga_jual_motoris_retail; ?></td>
                                    <td><?= $a->harga_jual_mt; ?></td>
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
            "pageLength": 5000,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 30, 40, 50, 60, 70, 80, -1],
                [10, 20, 30, 40, 50, 60, 70, 80, "All"]
            ],
            scrollX: true,
            // scrollCollapse: true
            scrollY: 500
        });
    });
</script>