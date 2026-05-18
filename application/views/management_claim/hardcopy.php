</div>
<div class="container-fluid">
    
<?php echo form_open_multipart($url); ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>

<div class="row">
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

<div class="row mt-2">
    <div class="col-lg-11">
        (*) Isi data pengiriman hardcopy di bawah ini :
      <hr class="batas2">
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-3">
        <label for="nama_pengirim_hardcopy">Nama Pengirim Hardcopy</label>
    </div>
    <div class="col-lg-5">
        <input class="form-control form-control-md" id="nama_pengirim_hardcopy" type="text" name="nama_pengirim_hardcopy" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-3">
        <label for="email_pengirim_hardcopy">Email Pengirim Hardcopy</label>
    </div>
    <div class="col-lg-5">
        <input class="form-control form-control-md" id="email_pengirim_hardcopy" type="text" name="email_pengirim_hardcopy" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-3">
        <label for="nomor_hardcopy">Nomor Resi</label>
    </div>
    <div class="col-lg-5">
        <input class="form-control form-control-md" id="nomor_hardcopy" type="text" name="nomor_hardcopy" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-3">
        <label for="tanggal_kirim_hardcopy">Tanggal Kirim Resi</label>
    </div>
    <div class="col-lg-5">
        <input class="form-control form-control-md" id="tanggal_kirim_hardcopy" type="date" name="tanggal_kirim_hardcopy" min="2024-01-01" required>
    </div>
</div>

<input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
<input type="hidden" name="signature_program" value="<?= $signature_program ?>">
<input type="hidden" name="id_log" value="<?= $id_log ?>">

<div class="row mt-2">
    <div class="col-lg-3">
        <label for="file_resi">File Resi</label>
    </div>
    <div class="col-lg-5">
        <input class="form-control form-control-md" id="file_resi" type="file" name="file_resi" required>
    </div>
</div>

<div class="row mt-4 mb-5">
    <div class="col-lg-3">
        
    </div>
    <div class="col-md-5">
        <?php 
            if ($status_authorized) { ?>
                <button type="submit" class="btn btn-submit-red" id="btnKirim" onclick="return button()">Submit Resi</button>
            <?php
            }else{ ?>
                <label for="" class="form-label" style="border: 1px solid black; padding: 5px; width: 50%">menunggu verifikasi : <?= $pic_on_duty ?></label>
            <?php
            }
        ?>
        <button class="btn btn-info" id="btnLoading" type="button" disabled>
        ... Sedang verifikasi data. Mohon menunggu ...
        </button>
        <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-submit-black" id="btnBack">Back</a>
    </div>
</div>

</form>


</div>
</div>

<script>
    function button()
    {
        var nama_pengirim_hardcopy  = document.getElementById('nama_pengirim_hardcopy').value;
        var email_pengirim_hardcopy = document.getElementById('email_pengirim_hardcopy').value;
        var nomor_hardcopy          = document.getElementById('nomor_hardcopy').value;
        var file_resi               = document.getElementById('file_resi').value;
        var tanggal_kirim_hardcopy     = document.getElementById('tanggal_kirim_hardcopy').value;
        if (nama_pengirim_hardcopy && email_pengirim_hardcopy && nomor_hardcopy && file_resi && tanggal_kirim_hardcopy) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>