<style>
    th {
        text-align: center;
        font-size: 12px;
    }

    td {
        font-size: 12px;
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
                    <!-- <i class="feather icon-home bg-c-blue"></i> -->
                    <div class="d-inline">
                        <!-- <h5>Dashboard Sales</h5> -->
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <!-- <ul class=" breadcrumb breadcrumb-title">
            <li class="breadcrumb-item">
                <a href="#"><i class="feather icon-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="#!">Dashboard</a> </li>
        </ul> -->
                </div>
            </div>
        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <button type="button" class="btn btn-primary" onclick="Purchase()" id="btnPurchase">
                    Purchase Requistion
                </button>
                <br><br>
                <div class="row">
                    <!-- table purchase -->
                    <?php if (count($pr) >= 0) {?>
                    <div class="col-sm-12" id="divPurchase">
                        <div class="card sale-card">
                            <div class="card-header">
                                <h5>Purchase Requistion</h5>
                            </div>
                            <div class="card-block">
                                <button type="button" class="btn btn-primary btn-sm" onclick="input_pr()">
                                    <i class="fa fa-plus"></i>Tambah
                                </button>
                                <br><br>
                                <div class="dt-responsive table-responsive">
                                    <table id="table-detail" class="table table-hover m-b-0 table-bordered">
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
                                            <?php foreach ($pr as $key => $value) :?>
                                            <tr>
                                                <td><a href="#"
                                                        onclick="Detail(<?=$value->id;?>)"><?= $value->no_pr;?></a></td>
                                                <td><?= date("d F Y", strtotime($value->created_at));?></td>
                                                <td><?= $value->keterangan;?></td>
                                                <td><?= $value->nama_status;?></td>
                                                <td><a href="<?= base_url()."assets_new/download_pdf/$value->no_pr";?>" type="button" class="btn btn-warning btn-sm">PDF</a></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }?>

                    <!-- table konfirm asset -->
                    <?php if (count($konfirmasi) > 0) {?>
                    <div class="col-sm-12">
                        <div class="card sale-card">
                            <div class="card-header">
                                <h5>Konfirmasi Asset</h5>
                            </div>
                            <br>
                            <div class="card-block table-border-style">
                                <div class="table-responsive">
                                    <table class="table table-inverse">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama Barang</th>
                                                <th>Jumlah</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($konfirmasi as $a) :?>
                                            <tr>
                                                <td><?= $a->kode; ?></td>
                                                <td><?= $a->namabarang; ?></td>
                                                <td><?= $a->jumlah; ?></td>
                                                <td><a href="<?= base_url() . "assets_new/konfirmasi_asset/$a->id/$a->id_mutasi"; ?>"
                                                        class="btn btn-success btn-sm" role="button">Konfirmasi</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }?>

                    <!-- table my asset -->
                    <div class="col-sm-12">
                        <div class="card sale-card">
                            <div class="card-header">
                                <h5><?php echo $title; ?></h5>
                            </div>
                            <div class="card-block">
                                <div class="dt-responsive table-responsive">
                                    <div class="col-12">
                                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No. PO</th>
                                                    <th>No. PR</th>
                                                    <th>Nama Barang</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                        foreach ($asset as $a) :?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= $a->no_po; ?></td>
                                                    <td><?= $a->no_pr; ?></td>
                                                    <td><?= $a->namabarang;?></td>
                                                    <td><?= $a->tgl_pengiriman; ?></td>
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
</div>

<?php $this->load->view('purchase_requistion/form_pr'); ?>
<?php $this->load->view('purchase_requistion/modal_detail'); ?>

<script>
    $("div#divPurchase").hide();

    function Purchase() {
        var x = document.getElementById("divPurchase");
        var y = document.getElementById("btnPurchase");
        if (x.style.display === "none") {
            x.style.display = "block";
            y.classList.add("btn-danger");
        } else {
            x.style.display = "none";
            y.classList.remove("btn-danger");
        }
    }

    function input_pr() {
        $('#form_pr').modal();
        $('textarea#barang').val('').attr("readonly", false);
        $('textarea#keterangan').val('').attr('readonly', false);
    }

    function Detail(param) {
        $('#detail').modal();
        $('div#product').remove();
        $('div#type').remove();
        $('div#spesifikasi').remove();
        $('div#harga').remove();
        $.ajax({
            type: "GET",
            url: "<?= base_url('purchase_requistion/get_data');?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                const d = new Date(response.pr[0].created_at);
                const tahun = d.getFullYear();
                const bulan = d.getMonth();
                const tgl = d.getDate();
                const tanggal = tahun + '-' + bulan + '-' + tgl;
                $('input#no_pr').val(response.pr[0].no_pr).attr("readonly", true);
                $('input#username').val(response.pr[0].username).attr("readonly", true);
                $('input#tanggal').val(tanggal).attr("readonly", true);
                $('input#divisi').val(response.pr[0].divisi).attr("readonly", true);

                // var jml = response.pr.length;
                // for (let index = 0; index < response.pr.length; index++) {
                //     var i = (+index + 1);
                //     $('div.product').append('<div id="product"><label for="product">Product ' + i +
                //         '</label><input type="text" name="product[]" id="product' + index +
                //         '" class="form-control"></div>');
                //     $('input#product' + index).val(response.pr[index].product).attr("readonly", true);

                //     $('div.type').append(
                //         '<div id="type"><label for="type">Type ' + i +
                //         '</label><textarea type="text" name="type[]" id="type' + index +
                //         '" class="form-control"></textarea></div>');
                //     $('textarea#type' + index).val(response.pr[index].type).attr("readonly",
                //         true);

                //     $('div.spesifikasi').append(
                //         '<div id="spesifikasi"><label for="spesifikasi">Spesifikasi ' + i +
                //         '</label><textarea type="text" name="spesifikasi[]" id="spesifikasi' + index +
                //         '" class="form-control"></textarea></div>');
                //     $('textarea#spesifikasi' + index).val(response.pr[index].spesifikasi).attr("readonly",
                //         true);

                //     $('div.harga').append(
                //         '<div id="harga"><label for="harga">Harga ' + i +
                //         '</label><input type="text" name="harga[]" id="harga' + index +
                //         '" class="form-control"></div>');
                //     $('input#harga' + index).val(response.pr[index].harga).attr("readonly",
                //         true);
                // }
                // $.each(response.pr, function (index, obj) {
                // })

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
                $('textarea#keterangan_purchasing').val(response.pr[0].keterangan_finance).attr("readonly",
                    true);

            }
        });
    }
</script>