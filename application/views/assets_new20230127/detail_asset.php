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
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <a href="<?= base_url()."assets_new/view_asset"; ?>  " class="btn btn-dark" role="button"><span
                            class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        Kembali</a>
                    <a href="<?= base_url()."assets_new/qrcode/".$this->uri->segment(3).""; ?>  " target="_blank"
                        class="btn btn-success" role="button"><span class="glyphicon glyphicon-qrcode"
                            aria-hidden="true"></span> Generate QR Code</a>
                    <br><br>
                    <div class="row">
                        <!-------------- detail asset ----------------->
                        <div class="col-12">
                            <?= form_open_multipart('assets_new/update_asset/' .$this->uri->segment(3)); ?>
                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5>Detail Asset</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-sm" id="edit">
                                            Edit
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">No Voucher</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="nv"
                                                    value="<?= $asset->kode ;?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">No. POF</label>
                                            <div class="col-sm-8">
                                                <input class="form-control edit-input" type="text" name="nopo" id="nopo"
                                                    value="<?= $asset->no_po ;?>" readonly />
                                                <?php 
                                                // echo "xxxx : ".$a->no_po;
                                                // die; 
                                                if ($asset->no_po == '0' || $asset->no_po == '' ){
                                                    $nopo=array();
                                                    // $nopo['Hanya Mutasi']='Hanya Mutasi';
                                                    foreach($pr as $value)
                                                    {
                                                        $nopo['']= "- Pilih -";
                                                        $nopo[$value->no_po.'-'.$value->id_barang]= "$value->no_po - $value->id_barang | $value->username_penerima";
                                                    }
                                                }
                                                else{
                                                    $nopo=array();
                                                    $nopo[$asset->no_po]=$asset->no_po;
                                                    // foreach($no_po->result() as $value)
                                                    // {
                                                    //     $nopo[$value->no_po]= "$value->no_po - $value->username";
                                                    // }
                                                }
                                                echo form_dropdown('nopo', $nopo,'','class="form-control edit-dropdown" id="nopo"');
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">No. PR</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="no_pr"
                                                    value="<?= $asset->no_pr ;?>" id="no_pr" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Nama Barang</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="nb"
                                                    value="<?= $asset->namabarang ;?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">S/N</label>
                                            <div class="col-sm-8">
                                                <input class="form-control edit" type="text" name="sn"
                                                    value="<?= $asset->sn ;?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Jumlah Barang</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="jb"
                                                    value="<?= $asset->jumlah ;?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Keperluan</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="kpr"
                                                    value="<?= $asset->untuk ;?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Tanggal Payroll</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="date" name="tp"
                                                    value="<?= $asset->tglperol ;?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Nilai Perolehan</label>
                                            <div class="col-sm-8">
                                                <input class="form-control edit edit-num" type="number" name="np"
                                                    value="<?= $asset->np ;?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Tanggal Jual</label>
                                            <div class="col-sm-8">
                                                <?php if ($asset->tgljual == '1970-01-01'){
                                                    $tgl_jual = '';
                                                }else{
                                                    $tgl_jual = "$asset->tgljual";
                                                }
                                                ?>
                                                <input class="form-control edit" type="date" name="tj"
                                                    value="<?= $tgl_jual ;?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Nilai Jual</label>
                                            <div class="col-sm-8">
                                                <input class="form-control edit edit-num" type="number" name="nj"
                                                    value="<?= $asset->nj ;?>" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Deskripsi</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control edit" name="deskripsi" id="" cols="30"
                                                    rows="3" readonly><?= $asset->deskripsi;?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Upload Faktur (<font color="red">*PDF
                                                </font>)</label>
                                            <div class="col-sm-8">
                                                <?php if($asset->upload_faktur != ''){
                                                    echo anchor(base_url()."assets_new/file/faktur_asset/".$asset->upload_faktur, 'Lihat Faktur', "class='btn btn-primary btn-sm' target='_blank'");
                                                    echo '<br><br>';
                                                } ?>
                                                <input type="file" name="file" id="file" class="form-control edit"
                                                    readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div align="center">
                                    <?= form_submit('submit','update', 'class="btn btn-success save"');?>
                                    <?= form_close();?>
                                    <br><br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------------------------------- history --------------------------------->
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5>History Mutasi</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="col-sm-12">
                                        <div class="dt-responsive table-responsive">
                                            <table id="multi-colum-dt"
                                                class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            User
                                                        </th>
                                                        <th>
                                                            Nama Barang
                                                        </th>
                                                        <th>
                                                            Tanggal
                                                        </th>
                                                        <th>
                                                            Status
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($history as $a) : ?>
                                                    <tr>
                                                        <td>
                                                            <?= $a->username; ?>
                                                        </td>
                                                        <td>
                                                            <?= $a->namabarang; ?>
                                                        </td>
                                                        <td>
                                                            <?= date('d F Y', strtotime($a->tgl_pengiriman)); ?>
                                                        </td>
                                                        <td>
                                                            <?= $a->status; ?>
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // $('.mutasi').hide();
        $('.edit-dropdown').hide();
        $('.save').hide();

        $("#edit").click(function () {
            $('.edit').attr('readonly', false);
            $('.edit-num').attr('type', 'number');
            $('.edit-dropdown').show();
            $('.edit-input').remove();
            $('.save').show();
        });

    });
        
    $("select#nopo").change(function () {
        var no_po = document.getElementById("nopo").value;
        $.ajax({
            type: "GET",
            url: "<?= base_url().'assets_new/get_data';?>",
            data: {
                id: no_po
            },
            dataType: "json",
            success: function (response) {
                $("input#no_pr").val(response.get_pr[0].no_pr)
                    .change()
            }
        });
    });
</script>