<?php
foreach ($site_code_form->result() as $a) {
    $site_code      = $a->site_code;
    $nama_comp      = $a->nama_comp;
    $branch_name    = $a->branch_name;
    $site[$a->site_code] = $a->branch_name.' - '.$a->site_code;
}
?>

</div>

<div class="container-fluid">
    
<?php echo form_open_multipart($url); ?>

<div class="row mt-5 ms-5">
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
    <div class="col-md-7">
        (*) Isi data pengiriman hardcopy di bawah ini :
      <hr class="batas2">
    </div>
    <div class="col-md-4">
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="nama_pengirim_hardcopy">Nama Pengirim Hardcopy</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="nama_pengirim_hardcopy" type="text" name="nama_pengirim_hardcopy" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="email_pengirim_hardcopy">Email Pengirim Hardcopy</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="email_pengirim_hardcopy" type="text" name="email_pengirim_hardcopy" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="nomor_hardcopy">Nomor Resi</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="nomor_hardcopy" type="text" name="nomor_hardcopy" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="tanggal_kirim_hardcopy">Tanggal Kirim Resi</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="tanggal_kirim_hardcopy" type="date" name="tanggal_kirim_hardcopy" min="2024-01-01" required>
    </div>
</div>

<input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
<input type="hidden" name="signature_program" value="<?= $signature_program ?>">

<div class="row mt-2">
    <div class="col-md-3">
        <label for="file_resi">File Resi</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="file_resi" type="file" name="file_resi" required>
    </div>
</div>

<div class="row mt-4 mb-5">
    <div class="col-md-3">
        
    </div>
    <div class="col-md-5">
        <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Submit Hardcopy</button>
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