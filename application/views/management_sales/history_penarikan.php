<style>
    td {
        font-size: 13px;
    }
    .select2-selection {
        font-size: 13px;
        border-radius: 0 !important;
        border: solid 1px #c4c4c4 !important;
        padding-left: 4px;
        color: #000;
        background-color: #000;
    }
    .select2-selection__choice {
    background-color: #CAF1FF !important;
    color: #333 !important;
    border: none !important;
    border-radius: 3px !important;
    }
</style>

</div>
<?php $this->load->view('management_claim/css/style') ?>
<div class="container-fluid">
    
<?php echo form_open($url); ?>


<?php 
    // var_dump($principal);
    // var_dump($source);
    // die;
?>

<div class="card mt-4 mb-5">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>

        <div class="card-block mt-5 mb-1">
            <div class="row">
                <div class="col-md-12">
                    <table id="tabel" style="width: 100%">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">#</th>
                                <th colspan="3" class="text-center" style="background-color: #CAF1FF">count</th>
                                <th colspan="2" class="text-center" style="background-color: #CAF1FF">sum</th>
                                <th colspan="2" class="text-center">#</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width: 1%">No</th>       
                                <th class="text-center" style="width: 1%">Filename</th>       
                                <th class="text-center" style="width: 5%;background-color: #CAF1FF">Kode Produk</th>       
                                <th class="text-center" style="width: 5%;background-color: #CAF1FF">Site Code</th>       
                                <th class="text-center" style="width: 5%;background-color: #CAF1FF">Row</th>       
                                <th class="text-center" style="width: 5%;background-color: #CAF1FF">Omzet</th>       
                                <th class="text-center" style="width: 5%;background-color: #CAF1FF">Unit</th>       
                                <th class="text-center" style="width: 1%">CreatedAt</th>       
                                <th class="text-center">Download</th>       
                            </tr>
                        </thead>
                        <tbody>     
                            <?php
                            $no = 1;
                            foreach ($data->result() as $a) : ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <div><?= $a->name_table ?></div>
                                        <div>
                                            <?php 
                                                if ($a->status == 1) { ?>
                                                    <span class="badge badge-danger"> New</span>
                                                <?php
                                                }
                                            ?>
                                        </div>
                                    </div>                                    
                                </td>
                                <td class="text-center"><?= $a->count_kodeprod ?></td>
                                <td class="text-center"><?= $a->count_site_code ?></td>
                                <td class="text-center"><?= number_format($a->count_row, 0, ',', '.') ?></td>
                                <td class="text-center"><?= number_format($a->total_value, 0, ',', '.') ?></td>
                                <td class="text-center"><?= number_format($a->total_unit, 0, ',', '.') ?></td>
                                <td class="text-center"><?= date('d M y H:i', strtotime($a->created_at)); ?></td>
                                <td class="text-center">
                                    <?php 
                                        if ($a->breakdown == 'v1') { ?>
                                            <a href="<?= base_url().'management_sales/export/'.$a->name_table ?>" class="btn btn-submit-orange btn-sm">export</a>
                                            <a href="<?= base_url().'management_sales/export_horizontal/'.$a->name_table ?>" class="btn btn-submit-orange btn-sm">export hz</a> 
                                        <?php
                                        }elseif ($a->breakdown == 'v2') { ?>
                                            <a href="<?= base_url().'management_sales/export_by_kodeprod/'.$a->name_table ?>" class="btn btn-submit-orange btn-sm">export</a>
                                            <a href="<?= base_url().'management_sales/export_by_kodeprod_horizontal/'.$a->name_table ?>" class="btn btn-submit-orange btn-sm">export hz</a> 
                                        <?php
                                        }elseif ($a->breakdown == 'v3') { ?>
                                            <a href="<?= base_url().'management_sales/export_by_kodeprod_tipe_class/'.$a->name_table ?>" class="btn btn-submit-orange btn-sm">export</a>
                                            <a href="<?= base_url().'management_sales/export_by_kodeprod_tipe_class_horizontal/'.$a->name_table ?>" class="btn btn-submit-orange btn-sm">export hz</a> 
                                        <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>   
                        </tbody>
                    </table>

                </div>
            </div>


            <div class="row mt-4">
                <div class="col-md-12">
                    <a href="<?= base_url().'management_sales/sell_out_product' ?>" class="btn btn-submit-black" style="height: 45px;width: 200px; padding-top: 10px;">sell out product</a>
                    <a href="<?= base_url().'sales_omzet/sell_out_product' ?>" class="btn btn-submit-black" style="height: 45px;width: 130px; padding-top: 10px;">versi lama</a>
                </div>
            </div>


        </div>


    </div>
</div>



<?php echo form_close(); ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<!-- fungsi js select supp -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script>
    $(document).ready(function() {
        $(".principal").select2({
            placeholder: "-- Silahkan Pilih --"
        });
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_sales/master_principal') ?>',
        data: '',
        success: function(result) {
            $("select[id = principal]").html(result);
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 30, -1],
                [10, 20, 30, "All"]
            ],
            scrollX: true,
        });
    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>