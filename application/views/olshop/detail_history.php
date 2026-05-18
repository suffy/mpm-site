<style>
    th {
        font-size: 14px;
    }

    td {
        font-size: 14px;
        word-wrap: break-word;
    }
    
</style>

<?php 
if ($this->session->flashdata('msg_success')) { ?>
    <div class="alert alert-primary"><h3> <?= $this->session->flashdata('msg_success') ?> </h3></div>
<?php } ?>

<?php if ($this->session->flashdata('msg_error')) { ?>
    <div class="alert alert-danger"> <?= $this->session->flashdata('msg_error') ?> </div>
<?php } ?>

<?php 
    $signature_header = $this->uri->segment('3');
?>



<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dtx" class="table table-columned">
                    <thead>
                        <tr>
                            <th colspan="6"><center>Data import excel</center></th>
                        </tr>
                        <tr>
                            <!-- <th>Olshop</th> -->
                            <th width="1%">Inv</th>
                            <th width="1%">Tgl</th>
                            <th width="1%">Pembeli</th>
                            <th width="1%">Kodeprod</th>
                            <th width="1%">Namaprod</th>
                            <th width="1%">Qty</th>
                            <!-- <th width=1>#</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_detaill_by_signature->result() as $key) : ?>
                            <tr>
                                <!-- <td><?php echo $key->olshop; ?></td> -->
                                <td><?php echo $key->inv_olshop; ?></td>
                                <td><?php echo $key->tgl_olshop; ?></td>
                                <td><?php echo $key->pembeli_olshop; ?></td>
                                <td><?php echo $key->kodeprod_olshop; ?></td>
                                <td><?php echo $key->namaprod_olshop; ?></td>
                                <td><?php echo $key->qty_olshop; ?></td>
                                <!-- <td><?php echo $key->username.' at '.$key->updated_at ; ?></td> -->
                                <!-- <td>
                                    <a href="<?= base_url() ?>olshop/delete_detail_history/<?= $key->signature_header ?>" class = "btn btn-danger btn-sm">delete</a>
                                </td> -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <table id="table-assetx" class="table">
                    <thead>
                        <!-- <tr>
                            <th colspan="3"><center>Data Hasil Mapping</center></th>
                        </tr> -->
                        <tr>
                            <th width="1%">Kodeprod olshop</th>
                            <th width="1%">Namaprod olshop</th>
                            <th width="1%">kodeprod mpm</th>
                            <th width="2%">Namaprod mpm</th>
                            <th width="90%">Qty yang dibutuhkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_summary->result() as $key) : ?>
                            <tr>
                                <td><?php echo $key->kodeprod_olshop; ?></td>
                                <td><?php echo $key->namaprod_olshop; ?></td>
                                <td><?php echo $key->kodeprod_mpm; ?></td>
                                <td><?php echo $key->namaprod; ?></td>
                                <td><font size="5"><?php echo $key->total_qty; ?></font></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <a href="<?= base_url() ?>olshop/dashboard" class="btn btn-dark">back to dashboard</a>
                <a href="<?php echo base_url()."olshop/export_pdf/".$signature_header ?>" target="blank" class="btn btn-warning">export pdf</a>
                <a href="<?= base_url()."olshop/draft_pb/".$signature_header ?>" class="btn btn-primary">status pengambilan barang</a>
            </div>
        </div>
    </div>
</div>
