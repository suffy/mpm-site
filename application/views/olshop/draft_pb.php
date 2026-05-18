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

        
        <?php echo form_open_multipart($url); ?>        

        <div class="class-block">
            <div class="col">
                <div class="row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="no" class="col-form-label">No</label>
                    </div>
                    <div class="col-auto">
                        <input type="hidden" id="no" class="form-control" name="generate_draft" value="<?= $generate_code; ?>">
                        <input type="hidden" id="no" class="form-control" name="signature_header" value="<?= $this->uri->segment('3') ?>">
                        <?= $generate_code; ?>
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
                    <div class="col-md-4">
                        <input type="text" id="pic" class="form-control" name="pic" require>
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
                    <div class="col-md-4">
                        <input type="date" id="tanggal_pengambilan" class="form-control" name="tanggal_pengambilan" require>
                    </div>
                </div>
            </div>
        </div>

        <div class="class-block mt-4">
            <div class="col">               
                <div class="row g-3 align-items-center">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-8">
                        <a href="<?= base_url()."olshop/detail_history/".$this->uri->segment('3') ?>" class="btn btn-dark">back to detail</a>
                        <?php echo form_submit('submit', 'Proses Pengambilan Barang', 'class="btn btn-primary"'); ?>
                        <?php echo form_close(); ?>
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
