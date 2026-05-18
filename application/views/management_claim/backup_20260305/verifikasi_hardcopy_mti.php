</div>
<div class="container">
    
<?php echo form_open_multipart($url); ?>

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
    <div class="col-md-3">
        <label for="status">Status</label>
    </div>
    <div class="col-md-4">
        <select name="status" id="status" class="form-control" required>
            <option value=""> -- Pilih Status -- </option>
            <option value="1" <?= ($status_hardcopy == 1) ? 'selected' : '' ?>> PENDING MPI </option>
            <option value="3" <?= ($status_hardcopy == 3) ? 'selected' : '' ?>> TERIMA MPM </option>
            <!-- <option value="4"> PENDING PRINCIPAL/KAM</option> -->
            <option value="5" <?= ($status_hardcopy == 5) ? 'selected' : '' ?>> PENDING PRINCIPAL/FINANCE</option>
            <option value="6" <?= ($status_hardcopy == 6) ? 'selected' : '' ?>> APPROVE</option>
            <option value="7" <?= ($status_hardcopy == 7) ? 'selected' : '' ?>> REJECT</option>
        </select>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="keterangan">Tanggal Terima Hardcopy</label>
    </div>
    <div class="col-md-4">
        <input type="date" class="form-control" name="tanggal_terima_hardcopy" id="tanggal_terima_hardcopy" value="<?= $tanggal_terima_hardcopy ?>">
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="file">File Tanda Terima ke Principal</label>
    </div>
    <div class="col-md-4">


        <?php 
            if (!empty($file_tanda_terima_hardcopy_ke_principal)) { ?>
                <a href="<?= base_url().'assets/uploads/management_claim/mti/'.$file_tanda_terima_hardcopy_ke_principal ?>" target="_blank" class ="btn btn-submit-black"><?= $file_tanda_terima_hardcopy_ke_principal ?></a>
            <?php
            }else{
                echo '<label class="form-control" readonly>Tidak ada file</label>';
            }
        ?>  
        <input class="form-control" id="file" type="file" name="file">
        <input type="hidden" class="form-control" name="file_old" value="<?= $file_tanda_terima_hardcopy_ke_principal; ?>">
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="nama_penerima">Nama PIC Principal</label>
    </div>
    <div class="col-md-4">
        <input class="form-control" id="nama_penerima" type="text" name="nama_penerima" value="<?= $tanda_terima_hardcopy_ke_principal_nama ?>">
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="file">Tanggal Serah Terima ke Principal</label>
    </div>
    <div class="col-md-4">
        <input class="form-control" type="date" name="tanggal_serah_terima" value = "<?= $tanggal_tanda_terima_hardcopy_ke_principal ?>">
    </div>
</div>

<input type="hidden" class="form-control" name="file_old" value="<?= $file_tanda_terima_hardcopy_ke_principal; ?>">
<input type="hidden" name="id_ajuan" value="<?= $id_ajuan ?>">
<input type="hidden" name="id_program" value="<?= $id_program ?>">
<input type="hidden" name="signature_program" value="<?= $signature_program ?>">
<input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">

<div class="row mt-4 mb-5">
    <div class="col-md-3">
        
    </div>
    <div class="col-md-5">
        <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Update Status Hardcopy</button>
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
        var status   = document.getElementById('status').value;
        var keterangan  = document.getElementById('keterangan').value;
        if (status && keterangan) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>