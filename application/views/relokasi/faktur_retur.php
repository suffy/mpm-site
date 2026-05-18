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
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>Nota Retur
                                </div>

                                <div class="card-block">
                                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap" style="display: inline-block; overflow-y: scroll">
                                <thead>
                                    <tr>
                                        <th width="10%"><font size="2px">no_relokasi</th>
                                        <th width="10%"><font size="2px">nota retur</th>
                                        <th width="10%"><font size="2px">faktur pajak</th>
                                        <th width="10%"><font size="2px">noseri</th>
                                        <th width="100%"><font size="2px">company</th>
                                        <th width="100%"><font size="2px">nopo</th>
                                        <th width="100%"><font size="2px">nodo</th>
                                        <th width="100%"><font size="2px">alasan</th>  
                                        <th>#</th>                                      
                                    </tr>
                                </thead>
                                <tbody>                                        
                                    <?php foreach ($get_trans->result() as $a) : ?>
                                    <tr>
                                        <td><font size="1px"><?= $a->no_ajuan_relokasi; ?></td>
                                        <td><font size="1px"><?= $a->nodo_beli; ?></td>
                                        <td><font size="1px"><?= $a->noseri_beli; ?></td>
                                        <td><font size="1px"><?= $a->noseri; ?></td>
                                        <td><font size="1px"><?= $a->company; ?></td>
                                        <td><font size="1px"><?= $a->nopo; ?></td>
                                        <td><font size="1px"><?= $a->nodo; ?></td>
                                        <td><font size="1px"><?= $a->alasan; ?></td>   
                                        <td>
                                            <a href="<?= base_url().'trans/retur/print_beli/'.$a->id; ?>" target="_blank" class="btn btn-primary btn-sm">pdf
                                            </a>
                                        </td>                                     
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
        </div>
    </div>
</div>                        
                        