<style>
.custom-blue-btn {
    background-color: rgb(55, 178, 216);
    color: #fff;
    border: none;
}

.btn-submit-black {
    background-color: transparent;
    color: black;
    border: 1px solid black;
}

.btn-submit-black.active {
    background-color: #535353ff !important;
    color: white !important;
}

/* 🔥 FIX SIDEBAR + TABLE */
.az-content-body {
    max-width: 100%;
    overflow-x: auto;
}

.dataTables_wrapper {
    width: 100%;
    overflow-x: auto;
}

table.dataTable {
    width: 100% !important;
}

.card {
    border: 1px solid #ddd;
    /* border-radius: 5px; */
    /* background-color : #777; */
}

.card-body span {
    color: #777 !important;
    font-size: 12px;
}

.card-body b {
    font-size: 16px;
}
</style>

<div class="az-content">
    <div class="container-fluid">
        <?= $this->load->view('penta/component/sidebar');?>
            <div class="az-content-body pd-lg-l-40 d-flex flex-column">
                <h2><?= $title; ?></h2>
                <div class="row">
                    <div class="col-md-12">
                    <?php 
                        if($this->session->flashdata('pesan')){ ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $this->session->flashdata('pesan'); ?>
                            </div>
                        <?php
                        }elseif($this->session->flashdata('pesan_success')){ ?>
                            <div class="alert alert-success" role="alert">
                                <?= $this->session->flashdata('pesan_success'); ?>
                            </div>
                        <?php
                        }
                    ?>
                    </div>
                </div>

<!-- <div class="row">
    <div class="container">
        <div class="code-block">
            <pre>Information !

Data Customer Penta Palu ditarik otomatis oleh sistem.
Silahkan Cek Data Customer di Button <b>Data Customer</b> untuk melihat hasilnya.
Terima kasih.
            </pre>
        </div>
    </div>
</div> -->

            <div class="row mt-3">

                <!-- KIRI : INFO -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6><b>Information</b></h6>
                            <p class="mb-0" style="font-size:13px;">
                                Data Sales Penta Palu ditarik otomatis oleh sistem.<br>
                                Silakan cek hasilnya pada menu <b>Data Sales</b>.<br>
                                Terima kasih.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KANAN : SUMMARY -->
                <div class="col-md-6">

                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">

                            <h6 class="mb-2 text-muted">Total Master Product Invalid</h6>

                            <h1 style="font-size:48px; font-weight:bold;"
                                class="<?= $get_master_product_summary->num_rows() > 0 ? 'text-danger' : 'text-success' ?>">
                                
                                <?= $get_master_product_summary->num_rows(); ?>

                            </h1>

                            <small class="text-muted text-center">
                                Data Master Product yang belum memiliki <b>Kode Produk MPM, Nama Produk MPM, atau Qty</b>
                            </small>

                        </div>
                    </div>

                </div>

            </div>
            <!-- BUTTON -->
            <div class="row mt-3">
                <div class="col-lg-4">
                    <a href="<?= base_url().'penta/get_penta_sales_palu' ?>" class="btn btn-info">
                        Get Data Sales
                    </a>
                    <a href="<?= base_url('penta/download_master_produk') ?>" class="btn btn-success" style="border: 1px solid black; display: inline-flex;">Download Master Produk</a>
                </div>
            </div>

            <!-- TOGGLE -->
            <div class="card-body mt-4 mb-3">
                <button class="btn btn-submit-black active" id="btn-berjalan">History Sales</button>
                <button class="btn btn-submit-black" id="btn-closing">Master Produk Penta</button>
                </div>

            <!-- HISTORY -->
            <div id="tabel-berjalan">
                <div class="card-body">

                    <div style="overflow-x:auto;">
                        <div class="table-responsive">
                            <table id="tabel-ajuan-claim" class="tabel-ajuan-claim table-striped table-hover" style="width:100%">
                            <!-- <table id="tabel-ajuan-claim"> -->
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">No</th>    
                                        <th class="text-center">Closing</th>     
                                        <th class="text-center">SiteCode</th>
                                        <th class="text-center">tahun</th>       
                                        <th class="text-center">Bulan</th>       
                                        <!-- <th class="text-center">Filename</th>       -->
                                        <th class="text-center">Sum Omzet</th>       
                                        <!-- <th class="text-center">Sum Unit</th>        -->
                                        <th class="text-center">CreatedAt</th>       
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php 
                                    $no = 1;
                                    foreach ($get_log_sales->result() as $a) : 
                                    ?>  
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td> 
                                            <td>                  
                                                <a href="<?= base_url().'penta/update_status_sales/'.$a->signature ?>" ><?= ($a->status_closing == 1) ? '<span class="btn-status status-closing">Closing</span>' : '<span class="btn-status status-false">False</span>' ?></a>
                                            </td>
                                            <td class="text-center"><?= $a->site_code ?></td>
                                            <td class="text-center"><?= $a->tahun ?></td> 
                                            <td class="text-center"><?= $a->bulan ?></td> 
                                            <!-- <td class="text-center"><?= $a->filename ?></td>  -->
                                            <td class="text-center"><?= number_format($a->total_gross) ?></td> 
                                            <!-- <td class="text-center"><?= $a->sum_unit ?></td>  -->
                                            <td class="text-center"><?= $a->created_at ?></td> 
                                        </tr>
                                    <?php endforeach; ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Master Product -->
            <div id="tabel-closing" style="display:none;">
                <div class="card-body">

                    <div style="overflow-x:auto;">
                        <table id="tabel-closing2" class="table table-bordered table-striped table-sm" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk Penta</th>
                                    <th>Item Id Vend Penta</th>
                                    <th>Nama Product Penta</th>
                                    <th>UOM</th>
                                    <th>Kode Produk MPM</th>
                                    <th>Nama Produk MPM</th>
                                    <th>Qty</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $no=1; foreach ($get_master_product->result() as $v): ?>
                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td><b><?= $v->kode_produk_penta ?></b></td>

                                    <td><?= $v->item_id_vend_penta ?></span></td>

                                    <td><?= $v->nama_produk_penta ?></td>
                                    <td><?= $v->uom ?></td>

                                    <td><?= $v->kode_produk_mpm ?: '-' ?></td>
                                    <td><?= $v->nama_produk_mpm ?: '-' ?></td>
                                    <td><?= $v->qty ?: '-' ?></td>

                                    <td>
                                    <a href="<?= base_url('penta/edit_product/'.$v->id); ?>" 
                                    class="btn btn-sm btn-warning">Edit</a>
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

<!-- JS -->
<script>
$(function(){

$('#tabel-berjalan1').DataTable({
    scrollX:true,
    autoWidth:false
});

let dt2 = null;

$('#btn-berjalan').click(function(){
    $('#tabel-berjalan').show();
    $('#tabel-closing').hide();
    $(this).addClass('active');
    $('#btn-closing').removeClass('active');
});

$('#btn-closing').click(function(){
    $('#tabel-berjalan').hide();
    $('#tabel-closing').show();
    $(this).addClass('active');
    $('#btn-berjalan').removeClass('active');

    if(!dt2){
        dt2 = $('#tabel-closing2').DataTable({
            scrollX:true,
            autoWidth:false
        });
    }
});

});
</script>

<script>
        $(document).ready(function () {
            $("#btnBack").show();
            $("#btnLoading").hide();
            $('#tabel-ajuan-claim').DataTable({
                "pageLength": 10,
                "ordering": true,
                // "order": [6, 'desc'],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                scrollX: true,
            });
        });
    </script>