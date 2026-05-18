<?php
foreach ($site_code->result() as $a) {
    $site_code      = $a->site_code;
    $nama_comp      = $a->nama_comp;
    $branch_name    = $a->branch_name;
    $site[$a->site_code] = $a->branch_name.' - '.$a->site_code;
}
?>

</div>
<div class="container-fluid">
    
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
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-2">
        <label for="tanggal_terima_barang">Status</label>
    </div>
    <div class="col-md-8">
        <label class="form-control" readonly>
            <?php
                if($nama_status_dp){
                    echo $nama_status_dp;
                }else{ ?>
                    belum ada. Silahkan ajukan claim terlebih dahulu
                <?php
                }
            ?>
        </label>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <label for="nomor_ajuan">Nomor Ajuan</label>
    </div>
    <div class="col-md-8">
        <label for="nomor_ajuan" class="form-control" readonly> <?= $nomor_ajuan ? $nomor_ajuan : 'Belum ada' ?></label>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="tanggal_terima_barang">Branch | SubBranch</label>
    </div>
    <div class="col-md-8">
        <label for="nomor_ajuan" class="form-control" readonly> <?= $branch_name ? $branch_name.' - '.$nama_comp.' - '.$site_code : 'Belum ada' ?></label>
        <input type="hidden" class="form-control" name="site_code" value="<?= $site_code ?>" readonly>
        <input type="hidden" class="form-control" name="branch_name" value="<?= $branch_name ?>" readonly>
        <input type="hidden" class="form-control" name="nama_comp" value="<?= $nama_comp ?>" readonly>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-2">
        <label for="nama_pengirim">Nama Pengirim</label>
    </div>
    <div class="col-md-8">
        <input class="form-control" id="nama_pengirim" type="text" name="nama_pengirim" placeholder = "isi nama pengirim ..." required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="email_pengirim">Email Pengirim</label>
    </div>
    <div class="col-md-8">
        <input class="form-control" id="email_pengirim" type="text" name="email_pengirim" placeholder = "isi email pengirim ..." required>
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

<div class="row mt-2">
    <div class="col-md-2">
        <label for="file">Upload File SKP</label>
    </div>
    <div class="col-md-8">
        <input class="form-control" id="file_skp" type="file" name="file_skp" required>
    </div>
</div>

<input type="hidden" name="signature_program" value="<?= $signature_program ?>">
<input type="hidden" name="id_program" value="<?= $id_program ?>">

<div class="row mt-4 mb-5">
    <div class="col-md-2">
        
    </div>
    <div class="col-md-5">
        <?php
            if ($status == 1 || $status == NULL) { 
                ?>
                    <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Proses Pengajuan Claim</button>
                <?php 
            }else{ ?>
                <button type="submit" class="btn btn-submit-black" disabled>data anda sudah masuk</button>              
            <?php }
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
        var file_skp            = document.getElementById('file_skp').value;
        if (nama_pengirim && email_pengirim && file && file_skp) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>