<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <a href="<?= base_url('assets_new/purchase_asset_input_barang'); ?>" type="button" class="btn btn-dark">Kembali</a>
                            <br><br>
                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5>Purchase Asset</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url);?>
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No. PO</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" minlength="12" maxlength="12"
                                                name="np" placeholder="( CONTOH: 000MPM032020 )" required />
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No. PR</label>
                                        <div class="col-sm-5 no_pr">
                                            <select name="no_pr" id="no_pr" class="form-control">
                                                <option value="">- Pilih -</option>
                                                <?php foreach($no_pr as $value){?>
                                                <option value="<?= $value->no_pr;?>"><?= $value->no_pr;?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                            <a href="#" type="button"
                                                class="btn waves-effect waves-light btn-info btn-outline-info btn-sm detail" onclick="Detail()">Detail</a>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Username Requester</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="user_req" id="user_req"
                                                hidden />
                                            <input class="form-control" type="text" id="username_req" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Tanggal</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="date" name="tgl" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Nama Toko</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="nt" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Alamat</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" name="alamat" cols="30" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No. Telp</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="telp" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Fax</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="fax" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Attn</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="attn" required />
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Upload</label>
                                        <div class="col-sm-6">
                                            <input type="file" name="file" id="file" class="form-control" required />
                                        </div>
                                    </div> -->
                                    <div align="center">
                                        <?php echo form_submit('submit','Simpan', 'class="btn btn-success"');?>
                                        <?php echo form_close();?>
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

<?= $this->load->view('purchase_requistion/modal_detail'); ?>

<script>
    $(document).ready(function () {
        $("a.detail").hide();
        $("select#no_pr").change(function () {
            var no_pr = document.getElementById("no_pr").value;
            if (no_pr == '0') {
                $("a.detail").hide();
            } else {
                $("a.detail").attr('onclick', 'Detail("'+ no_pr + '")').show();
            }
            $.ajax({
                type: "GET",
                url: "<?= base_url().'purchase_requistion/get_data';?>",
                data: {
                    id: no_pr
                },
                dataType: "json",
                success: function (response) {
                    $("input#user_req").val(response.pr[0].created_by)
                    $("input#username_req").val(response.pr[0].username)
                        .change()
                }
            });
        });
    });

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
                const d = new Date(response.pr[0].created_at);
                const tahun = d.getFullYear();
                const bulan = d.getMonth();
                const tgl = d.getDate();
                const tanggal = tahun + '-' + bulan + '-' + tgl;
                $('input#no_pr').val(response.pr[0].no_pr).attr("readonly", true);
                $('input#username').val(response.pr[0].username).attr("readonly", true);
                $('input#tanggal').val(tanggal).attr("readonly", true);
                $('input#divisi').val(response.pr[0].divisi).attr("readonly", true);
                $('textarea#barang').val(response.pr[0].barang).attr("readonly",
                    true);
                $('textarea#spesifikasi').val(response.pr[0].spesifikasi).attr("readonly",
                    true);

                $('textarea#keterangan').val(response.pr[0].keterangan).attr("readonly", true);
                $('textarea#keterangan_atasan').val(response.pr[0].keterangan_atasan).attr("readonly", true);
                $('textarea#keterangan_it').val(response.pr[0].keterangan_it).attr("readonly", true);
                $('textarea#keterangan_finance').val(response.pr[0].keterangan_finance).attr("readonly", true);
                $('textarea#keterangan_purchasing').val(response.pr[0].keterangan_purchasing).attr("readonly", true);
            }
        });
    }
</script>