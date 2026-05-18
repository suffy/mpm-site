<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <a href="<?php echo base_url() . "assets/file/olshop/template_transaksi_olshop.csv"; ?>" class="btn btn-dark" role="button">download template terlebih dahulu</a>

        </div>

        <hr>

        <div class="card-block">
            
        <?php echo form_open_multipart($url);?>
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="file" class="col-form-label">Attach FIle</label>
                </div>
                <div class="col-auto">
                    <input type="file" class="form-control" name="file">
                </div>
                <div class="col-auto">
                    <label for="file" class="col-form-label">Olshop</label>
                </div>
                <div class="col-auto">
                    <select name="olshop" class="form-control">
                        <option value="tokopedia">tokopedia</option>
                        <option value="shopee">shopee</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="submit" class = "btn btn-primary" value="proses mapping">
                </div>
            </div>
        </form>
        </div>

    </div>
</div>


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
            <h4 class="text-center"> - history upload - </h4>
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-columned">
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center"> -- data upload csv --</th>
                            <th> -- pengambilan barang --</th>
                            <th colspan="1"> -- data SDS --</th>
                        </tr>
                        <tr>
                            <th>Olshop</th>
                            <th>TanggalUpload</th>
                            <th>Total Invoice</th>
                            <th>Total SKU</th>
                            <th>Total QTY</th>
                            <th>Filename</th>
                            <th>NoPengambilanBarang</th>
                            <th width=1>InvoiceSDS</th>
                            <th>PenarikanSaldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_header->result() as $key) : ?>
                            <tr>
                                <td><?php echo $key->olshop; ?></td>
                                <td><?php echo $key->created_at ; ?></td>
                                <td><?php echo $key->total_invoice; ?></td>
                                <td><?php echo $key->total_produk; ?></td>
                                <td><?php echo $key->total_qty_olshop; ?></td>
                                <td><?php echo $key->filename; ?></td>
                                <td>
                                    <?php 
                                        echo anchor('olshop/detail_history/'.$key->signature_header,$key->status_pengambilan, array('class' => 'btn btn-danger btn-sm'));
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    // echo $key->faktur_sds;
                                        if($key->faktur_sds == null){ ?>
                                            <button type="button" class="btn btn-warning btn-sm" id="testOnclick" onclick="get_signature_header('<?= $key->signature_header ?>','<?= $key->id ?>')" data-toggle="modal" data-target="#vendor">belum ada data</button>
                                        <?php    
                                        }else{ ?>
                                            <button type="button" class="btn btn-warning btn-sm" id="testOnclick" onclick="get_signature_header('<?= $key->signature_header ?>','<?= $key->id ?>')" data-toggle="modal" data-target="#vendor"><?php echo $key->faktur_sds; ?></button>
                                        <?php
                                        }
                                    ?>
                                    <?php $this->load->view('olshop/modal_invoice') ?> 
                                </td>
                                <td><a href="penarikan_saldo" class="btn btn-success btn-sm">klik</a></td>
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
        <h4 class="text-center"> - Report - </h4>
            <div class="dt-responsive table-responsive mt-4">
                <table id="table-cart" class="table table-columned">
                    <thead>
                        <tr>
                            <th>Tgl</th>
                            <th>Invoice</th>
                            <th>Buyer</th>
                            <th>Courier</th>
                            <th>Resi</th>
                            <th>QtyPaket</th>
                            <th>PaketTokopedia</th>
                            <th>Kodeprod</th>
                            <th>Namaprod</th>
                            <th>FakturJkt</th>
                            <th>Retail</th>
                            <th>SubTotal</th>
                            <!-- <th>Total</th> -->
                            <!-- <th>BayarJkt</th>
                            <th>HasilPenjualan</th>
                            <th>BiayaLayanan</th> -->
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($get_report->result() as $key) { ?>
                            <tr>
                                <td><?= $key->tgl_olshop; ?></td>
                                <td><?= $key->inv_olshop; ?></td>
                                <td><?= $key->pembeli_olshop; ?></td>
                                <td>
                                    <?php 
                                        if ($key->courier == null) {
                                            $courier = "switch";
                                        }else{
                                            $courier = $key->courier;
                                        }
                                    ?>
                                <button type="button" class="btn btn-dark btn-sm" id="testOnclick" onclick="get_courier('<?= $key->inv_olshop; ?>')" data-toggle="modal" data-target="#courier"><?= $courier; ?></button>

                                <?php $this->load->view('olshop/modal_courier') ?>
                                </td>
                                <td>
                                    <?php 
                                        if ($key->resi == null) {
                                            $resi = "switch";
                                        }else{
                                            $resi = $key->resi;
                                        }
                                    ?>
                                <button type="button" class="btn btn-dark btn-sm" id="testOnclick" onclick="get_resi('<?= $key->inv_olshop; ?>')" data-toggle="modal" data-target="#resi"><?= $resi; ?></button>

                                <?php $this->load->view('olshop/modal_resi') ?>
                                </td>
                                <td><?= $key->qty_olshop; ?></td>
                                <td><?= $key->namaprod_olshop; ?></td>
                                <td><?= $key->kodeprod_mpm; ?></td>
                                <td><?= $key->namaprod; ?></td>
                                <td><?= $key->faktur_sds; ?></td>
                                <td><?= $key->harga_retail; ?></td>
                                <td><?= $key->sub_total; ?></td>
                                <!-- <td><?= $key->nominal; ?></td> -->
                            </tr>
                        
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
        <h4 class="text-center"> - Commission Report - </h4>
            <div class="dt-responsive table-responsive mt-4">
                <table id="table-vendor" class="table table-columned">
                    <thead>
                        <tr>
                            <th>ProductId</th>
                            <th>ProductName</th>
                            <th>InvoiceNo</th>
                            <th>TotalProductAmount</th>
                            <th>Promo</th>
                            <th>PromoRate</th>
                            <th>PromoCode</th>
                            <th>FinishDate</th>
                            <th>ServiceFeeRate</th>
                            <th>ServiceFeeGross</th>
                            <th>ServiceFeeNet</th>
                            <th>PPN</th>
                            <th>PPH</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
    function get_signature_header(params_signature, params_id){
        console.log(params_signature)
        console.log(params_id)
        $("#signature_header").val(params_signature);
        $("#id_ref").val(params_id);
    }
</script>
