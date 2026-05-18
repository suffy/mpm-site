<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>

<?php echo form_open($url); ?>

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
                <table id="multi-colum-dtx" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>
                            <th>FakturSDS</th>
                            <th>TanggalFaktur</th>
                            <th>NominalFaktur</th>
                            <th>Capture</th>
                            <th>CreateAt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_history_invoice->result() as $key) : ?>
                            <tr>
                                <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $key->id; ?>" name="options[]" class = "<?php echo $key->id; ?>" value="<?php echo $key->id; ?>">
                                </center>
                                </td>  
                                <td><?php echo $key->faktur_sds ; ?></td>
                                <td><?php echo $key->tanggal_faktur ; ?></td>
                                <td><?php echo number_format($key->nominal_faktur); ?></td>
                                <td>
                                    <a href="<?= base_url()."assets/file/olshop/faktur/$key->capture_faktur" ?>" target="_blank">
                                        <img src="<?= base_url()."assets/file/olshop/faktur/$key->capture_faktur" ?>" width="70px" alt="<?= $key->capture_faktur ; ?>">
                                    </a>
                                </td>
                                <td><?php echo $key->created_at ; ?></td>
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

        <div class="row">
            <div class="col-md-2">
                <label for="nominal" class="form-label">Nominal Saldo</label>
            </div>
            <div class="col-md-5">
                <input class="form-control" type="number" placeholder="0.00" required name="nominal" min="0" value="0" step="0.01" id="nominal" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.parentNode.parentNode.style.backgroundColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'inherit':'orange'">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-2">
                <label for="tanggalPenarikan" class="form-label">Tanggal Penarikan</label>
            </div>
            <div class="col-md-5">
                <input class="form-control" type="date" placeholder="0.00" required name="tanggalPenarikan" id="tanggalPenarikan">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="no_rekening" class="form-label">NoRekeningTujuan</label>
            </div>
            <div class="col-md-5">
                <input class="form-control" type="text" required name="no_rekening" id="no_rekening">
            </div>
        </div>

        <div class="row mt-3">
            
            <div class="col-md-2">
                <label for="pemilik_rekening" class="form-label">Atas Nama</label>
            </div>
            <div class="col-md-5">
                <input class="form-control" type="text" required name="pemilik_rekening" id="pemilik_rekening">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="catatan" class="form-label">Catatan</label>
            </div>
            <div class="col-md-5">
                <textarea name="catatan" id="catatan" class="form-control" cols="30" rows="5"></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                &nbsp;
            </div>
            <div class="col-md-2">
                <input type="submit" class="btn btn-primary" value="Simpan penarikan saldo">
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
                            <th>noPenarikan</th>
                            <th>Nominal</th>
                            <th>TanggalPenarikan</th>
                            <th>NoRekening</th>
                            <th>AtasNama</th>
                            <th>Catatan</th>
                            <th>FakturSDS</th>
                            <th>CreateAt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_history_penarikan_saldo->result() as $key) : ?>
                            <tr>
                                <td class="col-1"><?php echo $key->no_penarikan_saldo ; ?></td>
                                <td class="col-1"><?php echo number_format($key->nominal) ; ?></td>
                                <td><?php echo $key->tanggal_penarikan_saldo ; ?></td>
                                <td><?php echo $key->no_rekening ; ?></td>
                                <td><?php echo $key->pemilik_rekening ; ?></td>
                                <td><?php echo $key->catatan ; ?></td>                                
                                <td><?php echo $key->faktur_sds ; ?></td>                                
                                <td><?php echo $key->created_at ; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>