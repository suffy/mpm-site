<style>
    th {
        /* text-align: center; */
        /* font-size: 12px; */
    }

    td {
        font-size: 18px;
    }

    table th,
    table td {
        white-space: normal !important;
    }
</style>
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="row">

                    <div class="col-sm-12">
                        <div class="card sale-card">
                            <div class="card-header">
                                <h5><?= $title; ?></h5>
                            </div>
                            <div class="card-block">
                                <button type="button" class="btn btn-primary btn-sm" onclick="input_pr()">
                                    <i class="fa fa-plus"></i>Tambah
                                </button>
                                <br><br>
                                <div class="dt-responsive table-responsive">
                                    <table id="table-pr" class="table table-hover m-b-0 table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No. Purchase Requistion</th>
                                                <th>Tanggal</th>
                                                <th>Keterangan</th>
                                                <th>Status</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pr_pribadi as $key => $value) :?>
                                            <tr>
                                                <td><a href="#"
                                                        onclick="Detail(<?=$value->id;?>)"><?= $value->no_pr;?></a></td>
                                                <td><?= date("d F Y", strtotime($value->created_at));?></td>
                                                <td><?= $value->keterangan;?></td>
                                                <td><?= $value->nama_status;?></td>
                                                <td><a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-sm" target="_blank">View PDF</a></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12" id="non_it">
                        <div class="card sale-card">
                            <div class="card-header">
                                <h5>History Purchase Request</h5>
                            </div>

                            <!-- Atasan -->
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table id="multi-colum-dt" class="table table-hover m-b-0">
                                        <thead>
                                            <tr>
                                                <th width=220>Status</th>
                                                <th width=180>PR</th>
                                                <th width=150>Tanggal</th>
                                                <th width=100>Created by</th>
                                                <th>
                                                    <center>#</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pr as $key => $value) :?>
                                            <?php 
                                                    if ($value->status == 0) {
                                                        $bg_aktif = 'style="background-color: orange; color:white"';
                                                    } else{
                                                        $bg_aktif = '';
                                                    }
                                                ?>
                                            <tr>
                                                <td <?= $bg_aktif; ?>><?php 
                                                        // echo $value->status; 
                                                        if($value->status == 0){
                                                            $params_status = '<i>Open (Butuh approval anda)</i>';
                                                        }else{
                                                            $params_status = $value->nama_status;
                                                        }
                                                    ?>
                                                    <?= $params_status;?>
                                                </td>
                                                <td><a href="#"
                                                        onclick="Detail(<?=$value->id;?>)"><?= $value->no_pr;?></a></td>
                                                <td><?= date("d F Y", strtotime($value->created_at));?></td>
                                                <td><?= $value->username;?></td>
                                                <?php if ($value->status == 0) {?>
                                                <td align="center">
                                                    <button class="btn btn-success btn-md"
                                                        onclick="Approve(<?=$value->id;?>)">Approve</button>
                                                    <button class="btn btn-danger btn-md"
                                                        onclick="Reject(<?=$value->id;?>)">Reject</button>
                                                    <!-- <a href="<?= base_url()."assets_new/download_pdf/$value->no_pr";?>" type="button" class="btn btn-warning btn-sm">PDF</a> -->
                                                    <a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-md"
                                                        target="_blank">View PDF</a>
                                                </td>
                                                <?php } else { ?>
                                                <td align="center">
                                                    <button class="btn btn-success btn-md disabled">Approve</button>
                                                    <button class=" btn btn-danger btn-md disabled">Reject</button>
                                                    <!-- <a href="<?= base_url()."assets_new/download_pdf/$value->no_pr";?>" type="button" class="btn btn-warning btn-sm">PDF</a> -->
                                                    <a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-md"
                                                        target="_blank">View PDF</a>
                                                </td>
                                                <?php }?>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12" id="it">
                        <div class="card sale-card">
                            <div class="card-header">
                                <h5>Suggestion Spec (IT)</h5>
                            </div>
                            <!-- IT -->
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table id="table-detail" class="table table-hover m-b-0">
                                        <thead>
                                            <th width=220>Status</th>
                                            <th width=180>PR</th>
                                            <th width=150>Tanggal</th>
                                            <th width=100>Created by</th>
                                            <th>
                                                <center>#</center>
                                            </th>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pr as $key => $value) :?>
                                            <?php 
                                                    if ($value->status == 1) {
                                                        $bg_aktif = 'style="background-color: orange; color:white"';
                                                    } else{
                                                        $bg_aktif = '';
                                                    }
                                                ?>
                                            <tr>
                                                <td <?= $bg_aktif; ?>><?= $value->nama_status;?></td>
                                                <td><a href="#"
                                                        onclick="Detail(<?=$value->id;?>)"><?= $value->no_pr;?></a></td>
                                                <td><?= date("d F Y", strtotime($value->created_at));?></td>
                                                <td><?= $value->username;?></td>

                                                <?php if ($value->status == 1) {?>
                                                <td align="center">
                                                    <button class="btn btn-success btn-md"
                                                        onclick="Approve_it(<?=$value->id;?>)">Approve</button>
                                                    <button class="btn btn-danger btn-md"
                                                        onclick="Reject_it(<?=$value->id;?>)">Reject</button>
                                                    <a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-md">View PDF</a>
                                                </td>
                                                <?php } else { ?>
                                                <td align="center">
                                                    <button class="btn btn-success btn-md disabled">Approve</button>
                                                    <button class=" btn btn-danger btn-md disabled">Reject</button>
                                                    <a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-md"
                                                        target="_blank">View PDF</a>
                                                </td>
                                                <?php }?>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12" id="finance">
                        <div class="card sale-card">
                            <div class="card-header">
                                <h5>Final Approval (Finance)</h5>
                            </div>
                            <!-- Finance -->
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table id="table-asset" class="table table-hover m-b-0">
                                        <thead>
                                            <tr align="center">
                                                <th width=220>Status</th>
                                                <th width=180>PR</th>
                                                <th width=150>Tanggal</th>
                                                <th width=100>Created by</th>
                                                <th>
                                                    <center>#</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pr as $key => $value) :?>
                                            <?php 
                                                    if ($value->status == 2 && $value->flag_bypass == 1) {
                                                        $bg_aktif = 'style="background-color: orange; color:white"';
                                                    }else if ($value->status == 3 && $value->flag_bypass == 0) {
                                                        $bg_aktif = 'style="background-color: orange; color:white"';
                                                    } else{
                                                        $bg_aktif = '';
                                                    }
                                                ?>
                                            <tr>
                                                <td <?= $bg_aktif; ?>><?= $value->nama_status;?></td>
                                                <td><a href="#"
                                                        onclick="Detail(<?=$value->id;?>)"><?= $value->no_pr;?></a></td>
                                                <td><?= date("d F Y", strtotime($value->created_at));?></td>
                                                <td><?= $value->username;?></td>

                                                <?php if ($value->status == 2 && $value->flag_bypass == 1 || $value->status == 3) {?>
                                                <td align="center">
                                                    <button class="btn btn-success btn-md"
                                                        onclick="Approve_finance(<?=$value->id;?>)">Approve</button>
                                                    <button class="btn btn-danger btn-md"
                                                        onclick="Reject_finance(<?=$value->id;?>)">Reject</button>
                                                    <a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-md"
                                                        target="_blank">View PDF</a>
                                                </td>
                                                <?php } else { ?>
                                                <td align="center">
                                                    <button class="btn btn-success btn-md disabled">Approve</button>
                                                    <button class=" btn btn-danger btn-md disabled">Reject</button>
                                                    <a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-md"
                                                        target="_blank">View PDF</a>
                                                </td>
                                                <?php }?>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12" id="purchasing">
                        <div class="card sale-card">
                            <div class="card-header">
                                <h5>Purchase Request (Purchasing)</h5>
                            </div>
                            <!-- Purchasing -->
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table id="#table-itasset" class="table table-hover m-b-0">
                                        <thead>
                                            <tr>
                                                <th width=250>Status</th>
                                                <th width=220>PR</th>
                                                <th width=180>Tanggal</th>
                                                <th width=150>Created by</th>
                                                <th>
                                                    <center>#</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pr as $key => $value) :?>
                                            <?php 
                                                    if ($value->status == 2 && $value->flag_bypass == 0) {
                                                        $bg_aktif = 'style="background-color: orange; color:white"';
                                                    } else{
                                                        $bg_aktif = '';
                                                    }
                                                ?>
                                            <tr>
                                                <td <?= $bg_aktif; ?>><?= $value->nama_status;?></td>
                                                <td><a href="#"
                                                        onclick="Detail(<?=$value->id;?>)"><?= $value->no_pr;?></a></td>
                                                <td><?= date("d F Y", strtotime($value->created_at));?></td>
                                                <td><?= $value->username;?></td>

                                                <?php if ($value->status == 2 && $value->flag_bypass == 0 ) {?>
                                                <td align="center">
                                                    <button class="btn btn-success btn-md"
                                                        onclick="Approve_purchase(<?=$value->id;?>)">Proses</button>
                                                    <a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-md"
                                                        target="_blank">View PDF</a>
                                                </td>
                                                <?php } else { ?>
                                                <td align="center">
                                                    <button class="btn btn-success btn-md disabled">Proses</button>
                                                    <a href="<?= base_url()."purchase_requistion/download_pdf/$value->no_pr";?>"
                                                        type="button" class="btn btn-warning btn-md"
                                                        target="_blank">View PDF</a>
                                                </td>
                                                <?php }?>
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
        </div>
    </div>
</div>

<?= 
$this->load->view('purchase_requistion/form_konfirm');
$this->load->view('purchase_requistion/form_konfirm_it');
$this->load->view('purchase_requistion/form_konfirm_purchase');
$this->load->view('purchase_requistion/form_konfirm_finance');
$this->load->view('purchase_requistion/modal_detail'); 
$this->load->view('purchase_requistion/form_pr');
?>


<script>
    $(document).ready(function () {
        var userid = "<?= $this->session->userdata('id');?>";
        if (userid == 547) {
            $('div#non_it').remove();
            $('div#finance').remove();
            $('div#purchasing').remove();
        } else if (userid == 297) {
            $('div#finance').remove();
            $('div#purchasing').remove();
        } else if (userid == 634) {
            $('div#it').remove();
            $('div#non_it').remove();
            $('div#finance').remove();
        } else if (userid == 231 || userid == 134) {
            $('div#it').remove();
            $('div#purchasing').remove();
        } else {
            $('div#it').remove();
            $('div#finance').remove();
            $('div#purchasing').remove();
        }
    });

    function input_pr() {
        $('#form_pr').modal();
        $('textarea#barang').val('').attr("readonly", false);
        $('textarea#keterangan').val('').attr('readonly', false);
    }

    // Non IT
    function Approve(param) {
        $('#konfirm').modal();
        $('input#id').val(param);
        $('button.approve').show();
        $('button.reject').hide();
        $('textarea#keterangan_atasan').val('').attr('readonly', false);
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                var no_pr = response.pr[0].no_pr;
                document.getElementById("konfirmModalLabel").innerHTML = 'Approve | ' + no_pr;
            }
        });

        $('textarea#keterangan_atasan').val('').attr("readonly", false);
    }

    function Reject(param) {
        $('#konfirm').modal();
        $('input#id').val(param);
        $('button.reject').show();
        $('button.approve').hide();
        $('textarea#keterangan_atasan').val('').attr('readonly', false);
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                var no_pr = response.pr[0].no_pr;
                document.getElementById("konfirmModalLabel").innerHTML = 'Reject | ' + no_pr;
            }
        });

        $('textarea#keterangan_atasan').val('').attr("readonly", false);
    }

    function Detail(param) {
        $('#detail').modal();
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                // console.log('hello')
                // console.log(response.pr[0].created_at)
                const d = new Date(response.pr[0].created_at);
                const tahun = d.getFullYear();
                const bulan = d.getMonth();
                const tgl = d.getDate();
                const tanggal = tahun + '-' + bulan + '-' + tgl;
                // console.log(bulan)
                // console.log(tanggal)
                $('input#no_pr').val(response.pr[0].no_pr).attr("readonly", true);
                $('input#username').val(response.pr[0].username).attr("readonly", true);
                // $('input#tanggal').val(tanggal).attr("readonly", true);
                $('input#tanggal').val(response.pr[0].created_at).attr("readonly", true);
                $('input#divisi').val(response.pr[0].divisi).attr("readonly", true);
                $('textarea#barang').val(response.pr[0].barang).attr("readonly",
                    true);
                $('textarea#spesifikasi').val(response.pr[0].spesifikasi).attr("readonly",
                    true);

                $('textarea#keterangan').val(response.pr[0].keterangan).attr("readonly", true);
                $('textarea#keterangan_atasan').val(response.pr[0].keterangan_atasan).attr("readonly",
                true);
                $('textarea#keterangan_it').val(response.pr[0].keterangan_it).attr("readonly", true);
                $('textarea#keterangan_finance').val(response.pr[0].keterangan_finance).attr("readonly",
                    true);
                $('textarea#keterangan_purchasing').val(response.pr[0].keterangan_purchasing).attr(
                    "readonly", true);
            }
        });
    }

    // IT
    function Approve_it(param) {
        $('#konfirm_it').modal();
        $('input#bypass').prop('checked', false)
        const checkbox = document.getElementById('bypass')
        $('div.approve').show();
        $('div.reject').hide();
        $('input#id').val(param);
        $('textarea#spesifikasi').val('').attr('readonly', false);
        $('textarea#keterangan_it').val('').attr('readonly', false);
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                document.getElementById("approveITModalLabel").innerHTML = 'Approve IT | ' + response.pr[0]
                    .no_pr;
                $('textarea#barang').val(response.pr[0]
                    .barang).attr('readonly', true);
            }
        });
    }

    function Reject_it(param) {
        $('#konfirm_it').modal();
        $('div.approve').hide();
        $('div.reject').show();
        $('input#id').val(param);
        $('textarea#keterangan_it').val('').attr('readonly', false);

        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                document.getElementById("approveITModalLabel").innerHTML = 'Reject IT | ' + response.pr[0]
                    .no_pr;
            }
        });
    }

    // finance
    function Approve_finance(param) {
        $('#konfirm_finance').modal();
        $('button.approve').show();
        $('button.reject').hide();
        $('input#id').val(param);
        $('textarea#keterangan_finance').val('').attr('readonly', false);
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                document.getElementById("approveFinanceModalLabel").innerHTML = 'Approve Finance | ' +
                    response
                    .pr[0].no_pr;
            }
        });

    }

    function Reject_finance(param) {
        $('#konfirm_finance').modal();
        $('button.approve').hide();
        $('button.reject').show();
        $('input#id').val(param);
        $('textarea#keterangan_finance').val('').attr('readonly', false);
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                document.getElementById("approveFinanceModalLabel").innerHTML = 'Reject Finance | ' +
                    response
                    .pr[0].no_pr;
            }
        });

    }

    // purchase
    function Approve_purchase(param) {
        $('#konfirm_purchase').modal();
        $('input#id').val(param);
        $('textarea#keterangan_purchasing').val('').attr('readonly', false);
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                document.getElementById("approvePurchaseModalLabel").innerHTML = 'Approve Purchasing | ' +
                    response
                    .pr[0].no_pr;
                $('textarea#barang').val(response.pr[0].barang).attr('readonly', true);
                $('textarea#spesifikasi').val(response.pr[0].spesifikasi).attr('readonly', true);
            }
        });
    }
</script>