<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>

<?php 
if ($this->session->flashdata('msg_success')) { ?>
    <div class="alert alert-primary"><h3> <?= $this->session->flashdata('msg_success') ?> </h3></div>
<?php } ?>

<!-- <?php if ($this->session->flashdata('msg_error')) { ?>
    <div class="alert alert-danger"> <?= $this->session->flashdata('category_error') ?> </div>
<?php } ?> -->

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <a href="<?= base_url() ?>olshop/dashboard" class="btn btn-dark">back to dashboard</a>
            </div>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1">No</th>
                            <th>FakturSDS</th>
                            <th>TanggalFaktur</th>
                            <th>NominalFaktur</th>
                            <th>Capture</th>
                            <th>CreateAt</th>
                            <th width="1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_history_invoice->result() as $key) : ?>
                            <tr>
                                <td><?php echo $no++ ; ?></td>
                                <td><?php echo $key->faktur_sds ; ?></td>
                                <td><?php echo $key->tanggal_faktur ; ?></td>
                                <td><?php echo $key->nominal_faktur ; ?></td>
                                <td>
                                    <a href="<?= base_url()."assets/file/olshop/faktur/$key->capture_faktur" ?>" target="_blank">
                                        <img src="<?= base_url()."assets/file/olshop/faktur/$key->capture_faktur" ?>" width="70px" alt="<?= $key->capture_faktur ; ?>">
                                    </a>
                                </td>
                                <td><?php echo $key->created_at ; ?></td>
                                <td>
                                <?php 
                                echo anchor('olshop/delete_history_invoice/' . $key->signature,' ', array(
                                        'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                        'onclick' => 'return confirm(\'Are you sure?\')'
                                    ));
                                ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>