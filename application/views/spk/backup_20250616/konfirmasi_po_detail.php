</div>
<div class="container-fluid">
    
    <h2 id="form_spk"><?= $title; ?></h2>
    <div class="row">
        <div class="col-md-12">
            <?php
            if ($this->session->flashdata('pesan')) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
            <?php
            } elseif ($this->session->flashdata('pesan_success')) { ?>
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <a href="<?= base_url()."spk/export_konfirmasi_po_detail/$signature"; ?>" class="btn btn-submit-black">export data</a>
        </div>
    </div>
                    
    <?php echo form_open_multipart($url); ?>
    <div class="row mt-3">
        <div class="col-md-12 mt-4">
            <table id="tabel-data" class="table-striped dataTable no-footer">    
            <thead>                
                <tr>
                    <th colspan='7'></th>
                    <th colspan='2' style="background-color: #1d1d1d; color: white; text-align: center">Isi Disini</th>
                </tr>
                <tr>                
                    <th width="1%"><center><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>                  
                    <th width="1%"><font size="2px">NoDO</th>                        
                    <th width="1%"><font size="2px">TglDO</th>                        
                    <th width="1%"><font size="2px">Kodeprod</th>                        
                    <th width="1%"><font size="2px">Namaprod</th>       
                    <th width="1%"><font size="2px">Batch Number</th>                        
                    <th width="1%"><font size="2px">QTY DO</th>                        
                    <th width="1%"><font size="2px">Tanggal Terima</th>                        
                    <th width="1%"><font size="2px">QTY Terima</th>                        
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($get_do->result() as $key) : 
                    $item_id = $key->nodo.'|'.$key->kodeprod.'|'.$key->batch_number;
                ?>
                <tr>
                    <td>
                        <center>
                            <input type="checkbox" name="options[]" 
                            value="<?= $item_id; ?>" id="check_<?= $no; ?>">
                            <!-- Add a hidden input that will be submitted when the checkbox is checked -->
                            <input type="hidden" name="item_index[<?= $no; ?>]" value="<?= $item_id; ?>">
                        </center>
                    </td>
                    <td><?= $key->nodo; ?></td>
                    <td><?= $key->tgldo; ?></td>
                    <td><?= $key->kodeprod; ?></td>
                    <td><?= $key->namaprod; ?></td>
                    <td><?= $key->batch_number; ?></td>
                    <td><?= $key->qty; ?></td>
                    <td>
                        <?php 
                            if($key->tanggal_terima == null){
                                echo "<font color='red'>belum terima</font>";
                            }else{ ?>
                                <label style="color: green;">sudah diterima di <?= $key->tanggal_terima; ?></label>
                            <?php
                            }
                        ?>
                    </td>
                    <td>
                        <input type="number" name="qty_terima[<?= $no; ?>]" class="form-control" value="<?= $key->qty_terima ? $key->qty_terima : $key->qty; ?>">
                    </td>
                </tr>
                <?php $no++; endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form-group row">
            <label class="col-sm-2 col-form-label">Tanggal terima (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <!-- <textarea name="note" cols="30" rows="3" class = "form-control" required></textarea> -->
                    <input class="form-control" type="date" name="tanggal_terima" required />
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-submit-black">Submit</button>
                    <a href="<?= base_url("$url_back"); ?>" class="btn btn-submit-black">Back Konfirmasi Po</a>
                </div>
            </div>
        </div>
    </div>

    <?php echo form_close(); ?>

</div>

<script>
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            "pageLength": 100,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
