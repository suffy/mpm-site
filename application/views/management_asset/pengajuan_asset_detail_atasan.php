<style>
    .detail {
        /* cursor: pointer; */
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        /* border: 2px solid;
        border-radius: 25px; */
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;

    }
</style>
<div class="container">
    <div class="col-md-12" style="margin: 10px;">
        <p class="az-content-label">PENGAJUAN ASSET - KONFIRMASI PENGAJUAN ASSET</p>
        <br>
        <div class="detail">
            <div class="col-12">
                <br>
                <h3 align="Center" style="text-transform: uppercase;"><u>PENGAJUAN ASSET</u></h3>
                <br>
                <?php foreach ($pr_summary as $key): ?>
                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="no_pr">No. Purchase Request</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control"><?= $key->no_pr; ?></label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="divisi">Divisi</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control"><?= $key->divisi; ?></label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="username">Nama Yang Mengajukan</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize"><?= $key->username; ?></label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="tanggal">Tanggal</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control"><?= $key->created_at; ?></label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="barang">Status</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize"><?= $key->nama_status; ?></label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="barang">Barang</label>
                    </div>
                    <div class="col-md-7 mb-2">
                        <textarea class="form-control" style="text-transform:capitalize"><?= $key->barang; ?></textarea>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="keterangan">Keterangan</label>
                    </div>
                    <div class="col-md-7 mb-2">
                        <textarea class="form-control" style="text-transform:capitalize"><?= $key->keterangan; ?></textarea>
                    </div>
                </div>

                <hr>

                <?php if ($key->kategori == 'non_it' && $key->created_by == 362) {?>
                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label>Verifikasi By (HRGA)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php 
                        if ($key->flag_it == 1) {
                            echo "Approved at $key->tgl_konfirmasi_it by $key->username_it "; 
                        } elseif ($key->flag_it == 9) {
                            echo "Rejected at $key->tgl_konfirmasi_it by $key->username_it "; 
                        }else {
                            echo "-";
                        }
                        ?>
                        </label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label>Verifikasi By (Finance)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php 
                        if ($key->flag_finance == 1) {
                            echo "Approved at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                        } elseif ($key->flag_finance == 9) {
                            echo "Rejected at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                        }else {
                            echo "-";
                        }
                        ?>
                        </label>
                    </div>
                </div>
                <?php } elseif ($key->kategori == 'non_it') { ?>
                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label>Verifikasi By (Atasan)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php 
                        if ($key->flag_atasan == 1) {
                            echo "Approved at $key->tgl_konfirmasi_atasan by $key->username_atasan "; 
                        } elseif ($key->flag_atasan == 9) {
                            echo "Rejected at $key->tgl_konfirmasi_atasan by $key->username_atasan "; 
                        }else {
                            echo "-";
                        }
                        ?>
                        </label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label>Verifikasi By (HRGA)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php 
                        if ($key->flag_it == 1) {
                            echo "Approved at $key->tgl_konfirmasi_it by $key->username_it "; 
                        } elseif ($key->flag_it == 9) {
                            echo "Rejected at $key->tgl_konfirmasi_it by $key->username_it "; 
                        }else {
                            echo "-";
                        }
                        ?>
                        </label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label>Verifikasi By (Finance)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php 
                        if ($key->flag_finance == 1) {
                            echo "Approved at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                        } elseif ($key->flag_finance == 9) {
                            echo "Rejected at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                        }else {
                            echo "-";
                        }
                        ?>
                        </label>
                    </div>
                </div>
                <?php } else { ?>
                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label>Verifikasi By (Atasan)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php 
                        if ($key->flag_atasan == 1) {
                            echo "Approved at $key->tgl_konfirmasi_atasan by $key->username_atasan "; 
                        } elseif ($key->flag_atasan == 9) {
                            echo "Rejected at $key->tgl_konfirmasi_atasan by $key->username_atasan "; 
                        }else {
                            echo "-";
                        }
                        ?>
                        </label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label>Verifikasi By (IT)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php 
                        if ($key->flag_it == 1) {
                            echo "Approved at $key->tgl_konfirmasi_it by $key->username_it "; 
                        } elseif ($key->flag_it == 9) {
                            echo "Rejected at $key->tgl_konfirmasi_it by $key->username_it "; 
                        }else {
                            echo "-";
                        }
                        ?>
                        </label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label>Verifikasi By (Finance)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php 
                        if ($key->flag_finance == 1) {
                            echo "Approved at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                        } elseif ($key->flag_finance == 9) {
                            echo "Rejected at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                        }else {
                            echo "-";
                        }
                        ?>
                        </label>
                    </div>
                </div>
                <?php }?>

                <?php if ($key->flag_atasan == 0 && $userid == $key->userid_atasan) { ?>
                <form action="<?= base_url("management_asset/pengajuan_asset_konfirm_atasan") ?>" method="post">
                    <?php 
                        $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
                        if (file_exists($file)) { ?>
                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label> Tanda Tangan Digital</label>
                            <input type="text" class="form-control" style="text-transform: capitalize;"
                                value="<?= $signature; ?>" name="signature" id="signature" hidden>
                        </div>
                        <div class="col-md-7">
                            <a href="<?= base_url("management_asset/signature_digital/$signature/detail_atasan") ?>"
                                class="btn btn-outline-dark btn-sm">
                                <img src="<?= base_url().'assets/uploads/signature/'.$this->session->userdata('username').'-signature.png' ?>"
                                    alt="<?= $this->session->userdata('username').'-signature' ?>" width="75%">
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="row" align="center">
                        <div class="col-md-12" align="center">
                            <button type="submit" class="btn btn-info" value="1" name="submit"
                                id="approved">Setujui</button>
                            <button type="submit" class="btn btn-danger" value="9" name="submit"
                                id="reject">Tolak</button>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label> Tanda Tangan Digital</label>
                            <input type="text" class="form-control" style="text-transform: capitalize;"
                                value="<?= $signature; ?>" name="signature" id="signature" hidden>
                        </div>
                        <div class="col-md-7">
                            <a href="<?= base_url("management_asset/signature_digital/$signature/detail_atasan") ?>"
                                class="btn btn-outline-dark btn-sm">
                                click here
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                    <br>
                </form>
                <?php } ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('button#reject').click(function (e) {
        $("#signature64").removeAttr("required");
    });
</script>

<script type="text/javascript">
    var sig = $('#sig').signature({
        syncField: '#signature64',
        syncFormat: 'PNG'
    });
    $('#clear').click(function (e) {
        //   e.preventDefault();
        sig.signature('clear');
        $("#signature64").val('');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>