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
                                Data Customer Penta Palu ditarik otomatis oleh sistem.<br>
                                Silakan cek hasilnya pada menu <b>Data Customer</b>.<br>
                                Terima kasih.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KANAN : SUMMARY -->
                <div class="col-md-6">

                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">

                            <h6 class="mb-2 text-muted">Total Data Invalid</h6>

                            <h1 style="font-size:48px; font-weight:bold;"
                                class="<?= $get_customer_summary->num_rows() > 0 ? 'text-danger' : 'text-success' ?>">
                                
                                <?= $get_customer_summary->num_rows(); ?>

                            </h1>

                            <small class="text-muted text-center">
                                Data customer yang belum memiliki <b>Type, Class, atau Spot</b>
                            </small>

                        </div>
                    </div>

                </div>

            </div>
            <!-- BUTTON -->
            <div class="row mt-3">
                <div class="col-lg-4">
                    <a href="<?= base_url().'penta/get_penta_customer' ?>" class="btn btn-info">
                        Get Data Customer
                    </a>
                </div>
            </div>

            <!-- TOGGLE -->
            <div class="card-body mt-4 mb-3">
                <button class="btn btn-submit-black active" id="btn-berjalan">History</button>
                <button class="btn btn-submit-black" id="btn-closing">Data Customer</button>
            </div>

            <!-- HISTORY -->
            <div id="tabel-berjalan">
                <div class="card-body">

                    <div style="overflow-x:auto;">
                        <table id="tabel-berjalan1" class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Token</th>
                                    <th>Expired</th>
                                    <th>Created</th>
                                    <th>User</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $no=1; foreach ($get_data_log_customer->result() as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p->token ? substr($p->token,0,10).'...' : '-' ?></td>
                                    <td><?= $p->expired_at ?></td>
                                    <td><?= $p->created_at ?></td>
                                    <td><?= $p->username ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CUSTOMER -->
            <div id="tabel-closing" style="display:none;">
                <div class="card-body">

                    <div style="overflow-x:auto;">
                        <table id="tabel-closing2" class="table table-bordered table-striped table-sm" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Org Name</th>
                                    <th>Kode Customer</th>
                                    <th>Nama Customer</th>
                                    <!-- <th>City</th>
                                    <th>Province</th> -->
                                    <th>Kode Salesman</th>
                                    <th>Salesman</th>
                                    <th>Type</th>
                                    <th>Class</th>
                                    <th>Spot</th>
                                    <th>Address</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $no=1; foreach ($get_data_customer->result() as $v): ?>
                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td><b><?= $v->org_name ?></b></td>

                                    <td><?= $v->location ?></span></td>

                                    <td><?= $v->bill_ship_cust_name ?></td>
                                    <!-- <td><?= $v->city ?></td>
                                    <td><?= $v->province ?></td> -->
                                    <td><?= $v->primary_salesrep_id ?></td>
                                    <td><?= $v->salesman_name ?></td>


                                    <td><?= $v->typeid ?: '-' ?></td>
                                    <td><?= $v->classid ?: '-' ?></td>
                                    <td><?= $v->spot ?: '-' ?></td>

                                    <td style="max-width:250px; white-space:normal;">
                                    <?= $v->address1 ?><br>
                                    <?= $v->address2 ?><br>
                                    <?= $v->address3 ?>
                                    </td>

                                    <td>
                                    <a href="<?= base_url('penta/edit_customer/'.$v->id); ?>" 
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