</div>
<div class="container-fluid">
    
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

<div class="row">
    <div class="col-md-12">
        Update Status Pengajuan Claim MTI :
      <hr class="batas2">
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="status">Status</label>
    </div>
    <div class="col-md-5">
        <select name="status" id="status" class="form-control" required>
            <option value=""> -- Pilih Status -- </option>
            <option value="1"> PENDING MPI -> MPI harus memperbaiki datanya</option>
            <option value="3"> PENDING HEAD OF MTI -> akan dilanjutkan ke head of mti</option>
        </select>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="keterangan">Keterangan / Note</label>
    </div>
    <div class="col-md-5">
        <textarea name="keterangan" cols="30" rows="10" id="keterangan" class="form-control" required></textarea>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="file">Attach File</label>
    </div>
    <div class="col-md-5">
        <input class="form-control form-control-md" id="file" type="file" name="file">
    </div>
</div>

<input type="hidden" name="id_ajuan" value="<?= $id_ajuan ?>">
<input type="hidden" name="id_program" value="<?= $id_program ?>">
<input type="hidden" name="signature_program" value="<?= $signature_program ?>">
<input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">

<div class="row mt-4 mb-5">
    <div class="col-md-2">
        
    </div>
    <div class="col-md-5">
        <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Update Status Verifikasi MPI</button>
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