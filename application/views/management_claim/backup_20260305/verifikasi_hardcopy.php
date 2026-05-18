</div>

<div class="container-fluid">

<hr>
    
<?php echo form_open_multipart($url); ?>

    <div class="row mt-5">
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
            <label for="status_internal">Status Hardcopy</label>
        </div>
        
        <div class="col-md-4">
            <select name="status_internal" id="status_internal" class="form-control" required>
                <option value=""> -- Pilih Status -- </option>
                <option value="10" <?= ($status_internal == '10') ? 'selected' : ''; ?>>REVISI ADMIN MPM .....10</option>
                <option value="5" <?= ($status_internal == '5') ? 'selected' : ''; ?>>PENDING HARDCOPY DP .....5</option>
                <option value="6" <?= ($status_internal == '6') ? 'selected' : ''; ?>>PENDING TERIMA HARDCOPY .....6</option>
                <option value="7" <?= ($status_internal == '7') ? 'selected' : ''; ?>>REVISI HARDCOPY .....7</option>
                <option value="8" <?= ($status_internal == '8') ? 'selected' : ''; ?>>APPROVE HARCOPY .....8</option>
                <option value="12" <?= ($status_internal == '12') ? 'selected' : ''; ?>>PENDING PRINCIPAL HO .....12</option>
                <option value="13" <?= ($status_internal == '13') ? 'selected' : ''; ?>>REVISI PRINCIPAL HO .....13</option>
                <option value="14" <?= ($status_internal == '14') ? 'selected' : ''; ?>>APPROVE PRINCIPAL HO .....14</option>
                <option value="20" <?= ($status_internal == '20') ? 'selected' : ''; ?>>HARDCOPY DITERIMA .....20</option>
                <option value="21" <?= ($status_internal == '21') ? 'selected' : ''; ?>>PROSES DN (FINANCE) .....21</option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="tanggal_terima_hardcopy">Tanggal Terima Hardcopy dari DP</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="tanggal_terima_hardcopy" id="tanggal_terima_hardcopy" value="<?= $tanggal_terima_hardcopy; ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="file_tanda_terima_hardcopy_ke_principal">File Tanda Terima Hardcopy ke Principal</label>
        </div>
        <div class="col-md-4">
            <?php 
                if ($file_tanda_terima_hardcopy_ke_principal) { ?>
                    <a href="<?= base_url().'assets/uploads/management_claim/'.$file_tanda_terima_hardcopy_ke_principal ?>" class='btn btn-submit-cream'>
                    <?= $file_tanda_terima_hardcopy_ke_principal ?></a>
                <?php
                }else{ ?>
                    <label class="form-control"><i>user tidak melampirkan file</i></label>
                <?php
                }
            ?>         
            <br>
            <input type="hidden" class="form-control" name="file_tanda_terima_hardcopy_ke_principal_old" value="<?= $file_tanda_terima_hardcopy_ke_principal; ?>">
            <div class="mt-2">
                <input type="file" class="form-control" id="file_tanda_terima_hardcopy_ke_principal" name="file_tanda_terima_hardcopy_ke_principal">
            </div>            
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="tanda_terima_hardcopy_ke_principal_nama">Nama Staff Principal Penerima Hardcopy</label>
        </div>
        <div class="col-md-4">
            <input class="form-control form-control-md" type="text" id="tanda_terima_hardcopy_ke_principal_nama" name="tanda_terima_hardcopy_ke_principal_nama" value="<?= $tanda_terima_hardcopy_ke_principal_nama; ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="tanggal_tanda_terima_hardcopy_ke_principal">Tanggal Penyerahan Hardcopy ke Principal</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="tanggal_tanda_terima_hardcopy_ke_principal" id="tanggal_tanda_terima_hardcopy_ke_principal" value="<?= $tanggal_tanda_terima_hardcopy_ke_principal; ?>">
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-3">
            <input type="hidden" name="signature_program" value="<?= $signature_program ?>">
            <input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
            <?php 
                if ($status_authorized) { ?>
                    <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Submit Verifikasi</button>
                <?php
                }else{ ?>
                    <a href="#" class="btn btn-submit-black" disable>not your authority</a>
                <?php
                }
            ?>
            <button class="btn btn-info" id="btnLoading" type="button" disabled>
            ... Sedang update data. Mohon menunggu ...
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
        $("#btnKirim").hide();
        $("#btnBack").hide();
        $("#btnLoading").show(); 
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>
