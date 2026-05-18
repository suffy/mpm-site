<?php
foreach ($site_code->result() as $a) {
    $site_code      = $a->site_code;
    $nama_comp      = $a->nama_comp;
    $branch_name    = $a->branch_name;
    $site[$a->site_code] = $a->branch_name.' - '.$a->site_code;
}
?>

</div>
<div class="container mt-3">
    
<?php echo form_open_multipart($url); ?>

<div class="row mt-2">
    <div class="col-md-12 az-content-label text-center">
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

<div class="row">
    <div class="col-md-12">
        Silahkan isi data anda pada form di bawah ini :
      <!-- <hr class="batas2"> -->
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-2">
        <label for="nama_pengirim_hardcopy">Nama Pengirim Hardcopy</label>
    </div>
    <div class="col-md-8">
        <input class="form-control" id="nama_pengirim_hardcopy" type="text" name="nama_pengirim_hardcopy" placeholder = "isi nama pengirim hardcopy ..." required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="email_pengirim_hardcopy">Email Pengirim Hardcopy</label>
    </div>
    <div class="col-md-8">
        <input class="form-control" id="email_pengirim_hardcopy" type="text" name="email_pengirim_hardcopy" placeholder = "isi email pengirim hardcopy ..." required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="noresi">Nomor RESI</label>
    </div>
    <div class="col-md-8">
        <input class="form-control" id="noresi" type="text" name="noresi" placeholder = "isi nomor resi ..." required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="tanggal_kirim">Tanggal Kirim</label>
    </div>
    <div class="col-md-8">
        <input class="form-control" id="tanggal_kirim" type="date" name="tanggal_kirim" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="file">Upload Data</label>
    </div>
    <div class="col-md-8">
        <input class="form-control" id="file" type="file" name="file" required>
    </div>
</div>

<input type="hidden" name="signature_program" value="<?= $signature_program ?>">
<input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
<input type="hidden" name="id_program" value="<?= $id_program ?>">
<input type="hidden" name="id_ajuan" value="<?= $id_ajuan ?>">

<div class="row mt-4 mb-5">
    <div class="col-md-2">
        
    </div>
    <div class="col-md-5">
        <?php 
            if ($status_hardcopy == 6) { ?>
                <button class="btn btn-submit-black" type="button" disabled>
                approved
                </button>
            <?php
            }else{ ?>
                <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Proses Pengiriman Hardcopy</button>
            <?php
            }
        ?>
        
        <button class="btn btn-loading" id="btnLoading" type="button" disabled>
        ... Sedang verifikasi data. Mohon menunggu ...
        </button>
        <a href="<?= base_url().'management_claim/ajuan_claim_mti' ?>" class="btn btn-submit-black" id="btnBack">Back</a>
    </div>
</div>

</form>


</div>
</div>

<script>
    function button()
    {
        var nama_pengirim   = document.getElementById('nama_pengirim').value;
        var email_pengirim  = document.getElementById('email_pengirim').value;
        var file            = document.getElementById('file').value;
        if (nama_pengirim && email_pengirim && file) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>