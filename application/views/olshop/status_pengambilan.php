<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
        word-wrap: break-word;
    }
    
</style>


<div class="card table-card">
    <div class="card-header">
        
        <!-- <div class="card-block">
            <div class="col-md-8">
                <a href="<?= base_url()."olshop/detail_history/".$this->uri->segment('3') ?>" class="btn btn-dark">back to detail</a>
                <a href="" target="_blank" class="btn btn-success">send email</a>
                <a href="" target="_blank" class="btn btn-primary">export csv</a>
                <a href="" target="_blank" class="btn btn-danger">export pdf</a>
            </div>
        </div> -->


        
        <?php 
        if ($this->session->flashdata('msg_success')) { ?>
            <div class="alert alert-primary"><h3> <?= $this->session->flashdata('msg_success') ?> </h3></div>
        <?php } ?>

        <?php if ($this->session->flashdata('msg_error')) { ?>
            <div class="alert alert-danger"> <?= $this->session->flashdata('msg_failed') ?> </div>
        <?php } ?>

        
        <?php echo form_open_multipart($url); ?>        

        <div class="class-block">
            <div class="col">
                <div class="row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="no" class="col-form-label">No</label>
                    </div>
                    <div class="col-md-8">
                        <input type="hidden" id="no" class="form-control" name="generate_draft" value="<?= $no_barang_diambil; ?>">
                        <input type="hidden" id="no" class="form-control" name="signature_header" value="<?= $this->uri->segment('3') ?>">
                        <?= $no_barang_diambil; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="class-block">
            <div class="col">
                <div class="row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="pic" class="col-form-label">Nama PIC</label>
                    </div>
                    <div class="col-md-3">
                        <?= $pic; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="class-block">
            <div class="col">               
                <div class="row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="tanggal_pengambilan" class="col-form-label">Tanggal</label>
                    </div>
                    <div class="col-md-3">
                        <?= $tanggal_pengambilan; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="class-block mt-4">
            <div class="col">               
                <div class="row g-3 align-items-center">
                    <div class="col-md-12">

                        <a href="<?= base_url()."olshop/detail_history/".$this->uri->segment('3') ?>" class="btn btn-dark">back to detail</a>
                        <a href="<?= base_url()."olshop/email_status_pengambilan_barang/".$this->uri->segment('3') ?>" class="btn btn-success">send email</a>
                        <!-- <a href="<?= base_url()."olshop/export_status_pengambilan_barang/".$this->uri->segment('3') ?>" class="btn btn-primary">export csv</a> -->
                        <!-- <a href="" target="_blank" class="btn btn-danger">export pdf</a> -->

                    </div>
                    
                </div>
            </div>
        </div>

        
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <table id="table-assetx" class="table">
                    <thead>
                        <tr>
                            <th>Kodeprod</th>
                            <th>Namaprod</th>
                            <th>Total QTY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_summary->result() as $key) : ?>
                            <tr>
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
