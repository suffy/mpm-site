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

            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#barang_masuk">Barang Masuk</button>
                    <?php $this->load->view('dc/modal_barang_masuk_rev') ?>

                    <button class="btn btn-success" onclick="barang_keluar()">Barang Keluar</button>
                    <?php $this->load->view('dc/modal_barang_keluar'); ?>
                </div>
            </div>
            <br><br>

            <div class="row">
                <div class="col-md">
                    <div class="title">
                        <div class="row">
                            <div class="col">
                                <h3>Total Stock Produk</h3>
                            </div>
                            <div class="col text-right">
                                <a href="<?= base_url() ?>dc/export_total_stock" target="_blank" class="btn btn-warning">export</a>                                
                            </div>
                        </div>
                    </div>

                    <center>
                    <div class="col-md mt-5">

                        <div class="col-xl-4 col-md-6 text-center">
                            <div class="card ticket-card">
                                <div class="card-body">
                                    <p class="m-b-30 bg-c-red lbl-card"><i class="fas fa-folder-open"></i> Total Stock DC</p>
                                    <div class="text-center">
                                        <h2 class="m-b-0 d-inline-block text-c-red">
                                            <?php 
                                                if ($get_data_row_mutasi_by_produk_total->total == null) {
                                                    echo "0";
                                                }else{
                                                    echo $get_data_row_mutasi_by_produk_total->total; 
                                                }
                                            ?>
                                        </h2>
                                        <p class="m-b-0 d-inline-block"><strong>UNIT</strong></p>
                                        <p class="m-b-0 m-t-15">
                                            
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    </center>

                </div>

            </div>

            <hr>

            <div class="title">
                <div class="row">
                    <div class="col">
                        <h3>History Keluar Gudang</h3>
                    </div>
                    <div class="col text-right">
                        <a href="<?= base_url() ?>dc/export_keluar" target="_blank" class="btn btn-warning">export</a>
                    </div>
                </div>
            </div>
            <div class="dt-responsive table-responsive mt-4">
                <!-- <table id="table-dc" class="table table-striped table-bordered nowrap"> -->
                <table id="table-dc" class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <th>Kode</th>
                            <th>Email</th>
                            <th>Wa</th>
                            <th>Nodo</th>
                            <th>Tgldo</th>
                            <th>Nopo</th>
                            <th>Tglpo</th>
                            <th>Company</th>
                            <th>TotalUnit</th>
                            <th>Status</th>
                            <th>CreatedAt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_data_row_keluar as $key) : ?>
                            <tr>
                                <!-- <td><?= $no++; ?></td> -->
                                <td>
                                    <a href="<?= base_url() ?>dc/generate_pdf_keluar/<?= $key->signature; ?>" class=" btn btn-primary btn-sm" target="blank"><?php echo $key->kode; ?></a>
                                </td>
                                <td><a href="<?= base_url(); ?>dc/email_download/<?= $key->signature; ?>" class="btn btn-danger btn-sm" target="blank">email</a></td>
                                <td><a href="<?= base_url(); ?>dc/send_wa/<?= $key->signature; ?>" class="btn btn-success btn-sm" target="blank">wa</a></td>
                                <td><?php echo $key->nodo; ?></td>
                                <td><?php echo $key->tgldo; ?></td>
                                <td><?php echo $key->nopo; ?></td>
                                <td><?php echo $key->tglpo; ?></td>
                                <td><?php echo $key->company; ?></td>
                                <td><?php echo $key->total; ?></td>
                                <td><?php echo $key->status_kirim; ?></td>
                                <td><?php echo $key->created_at; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <hr>
            <br><br><br>

            <div class="title">
                <div class="row">
                    <div class="col">
                        <h3>Mapping Vendor</h3>
                    </div>
                    <div class="col text-right">
                        <a href="<?= base_url() ?>dc/export_keluar" target="_blank" class="btn btn-warning">export</a>
                    </div>
                </div>
            </div>
            <div class="dt-responsive table-responsive mt-4">
                <!-- <table id="table-dc" class="table table-striped table-bordered nowrap"> -->
                <table id="table-vendor" class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <th>site_code</th>
                            <th>subbranch</th>
                            <th>vendor</th>
                            <th>#</th>
                            <th>created by</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_vendor_dc as $key) : ?>
                            <tr>
                                <td><?php echo $key->site_code; ?></td>
                                <td><?php echo $key->nama_comp; ?></td>
                                <td><?php echo $key->vendor; ?></td>
                                <td>
                                    <button type="button" class="btn btn-dark btn-sm" id="testOnclick" onclick="get_site_code('<?= $key->site_code ?>')" data-toggle="modal" data-target="#vendor">switch</button>

                                    <?php $this->load->view('dc/modal_vendor') ?>
                                </td>
                                <td><?php echo $key->username.' - '.$key->created_at; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('dc/nodo_barang_keluar') ?>',
            success: function(hasil_kode) {
                $("select[name = kode_masuk]").html(hasil_kode);
            }
        });
    })
</script>