<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>

        </div>
    </div>

    

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card sale-card">

                            


                                <div class="card-header">



                                    <h5><?php echo $title; ?></h5>


                                    
                                

                                
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url); ?>

                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="form-group row">
                                                <label class="col-sm-2">Produk</label>
                                                <div class="col-sm-5">
                                                    <select name="kodeprod" id="id_kodeprod" class="form-control" required>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2">Qty kecil</label>
                                                <div class="col-sm-5">
                                                    <input type="number" class="form-control" name="qty" required>
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" name="signature" value="<?= $this->uri->segment('3'); ?>">
                                            <input type="hidden" class="form-control" name="principal" value="<?= $this->uri->segment('4'); ?>">
                                            <input type="hidden" class="form-control" name="id_ref" value="<?= $id_ref; ?>">

                                            <div class="form-group row">
                                                <label class="col-sm-2">&nbsp;</label>
                                                <div class="col-sm-9">
                                                    
                                                    <!-- <div class="btn-group d-inline"> -->

                                                        
                                                        <?php echo form_submit('submit', 'Tambah Produk', 'class="btn btn-primary" required'); ?>
                                                        <?php echo form_close(); ?> 


                                                        <!-- <a href="<?= base_url().'relokasi/preview_relokasi/'.$signature ?>" class="btn btn-warning">Preview relokasi</a> -->

                                                    <!-- </div> -->

                                                    
                                                </div>
                                            </div>  

                                        </div>
                                        <div class="col-sm-6">                                            

                                        </div>
                                    </div>

                                    <hr>

                                    <?php echo form_open_multipart($url_upload); ?>
                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="form-group row">
                                                <label class="col-sm-2">Metode Import Excel</label>
                                                <div class="col-sm-5">
                                                    <input type="file" class="form-control" name="file">
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" name="signature" value="<?= $this->uri->segment('3'); ?>">
                                            <input type="hidden" class="form-control" name="principal" value="<?= $this->uri->segment('4'); ?>">
                                            <input type="hidden" class="form-control" name="id_ref" value="<?= $id_ref; ?>">

                                            <div class="form-group row">
                                                <label class="col-sm-2">&nbsp;</label>
                                                <div class="col-sm-9">
                                                    <?php echo form_submit('submit', 'Import Excel', 'class="btn btn-primary" required'); ?>
                                                    <a href="<?= base_url().'relokasi/download_template' ?>" class="btn btn-outline-danger">download template excel</a>
                                                    <?php echo form_close(); ?>

                                                </div>
                                            </div>  

                                        </div>
                                        <div class="col-sm-6">                                            

                                        </div>
                                    </div>

                                </div>

                                <div class="card-block">
                                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th class="col-1"><font size="2px">kodeprod</th>
                                                <th class="col-1"><font size="2px">namaprod</th>
                                                <th class="col-1"><font size="2px">qty</th>
                                                <th class="col-1"><font size="2px">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                        
                                            <?php foreach ($history_produk->result() as $a) : ?>
                                            <tr>
                                                <td><font size="2px"><?= $a->kodeprod; ?></td>
                                                <td><font size="2px"><?= $a->namaprod; ?></td>
                                                <td><font size="2px"><?= $a->qty; ?></td>
                                                <td>
                                                <?php echo
                                                    anchor(
                                                        'relokasi/delete_produk/'.$a->kodeprod.'/'.$signature.'/'.$principal,
                                                        ' ',
                                                        array(
                                                            'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                                            'onclick' => 'return confirm(\'Yakin menghapus row ini ?\')'
                                                        )
                                                    );
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>    
                                        </tbody>
                                    </table>
                                </div>

                                <a href="<?= base_url().'relokasi/preview_relokasi/'.$signature ?>" class="btn btn-warning">Lanjut ke preview relokasi</a>
                                                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>                      
                       
<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/kodeprod') ?>',
        data: 'supp=<?= $this->uri->segment('4') ?>',
        success: function(hasil_kodeprod) {
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });
</script>