</div>
<div class="container-fluid">
    
<?php echo form_open_multipart($url); ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>

    <div class="row mt-3">
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
        <div class="col-md-2">
            <label for="status">Status</label>
        </div>
        <div class="col-md-4">
            <select name="status_internal" id="status_internal" class="form-control" required>
                <option value="">status ?</option>
                <?php 
                    foreach ($get_status_internal->result() as $s) { ?>
                        <option value="<?= $s->id ?>"><?= $s->nama_status ?></option>
                    <?php
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="keterangan">Keterangan / Note</label>
        </div>
        <div class="col-md-4">
            <textarea name="keterangan" cols="30" rows="10" id="keterangan" class="form-control" required></textarea>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="file">Attach File</label>
        </div>
        <div class="col-md-4">
            <input class="form-control form-control-md" id="ajuan_excel" type="file" name="file">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-5">
            <input type="hidden" name="signature_program" value="<?= $signature_program ?>">
            <input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
            <input type="hidden" name="id_log" value="<?= $id_log ?>">
            <?php 
                if ($status_authorized) { ?>
                    <button type="submit" class="btn btn-submit-red" id="btnKirim" onclick="return button()">Submit Verifikasi</button>
                <?php
                }else{ ?>
                    <label for="" class="form-label" style="border: 1px solid black; padding: 5px; width: 50%">menunggu verifikasi : <?= $pic_on_duty ?></label>
                <?php
                }
            ?>
            
            <button class="btn btn-info" id="btnLoading" type="button" disabled>
            ...... Sedang update data. Mohon menunggu ......
            </button>
            <!-- <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-submit-black" id="btnBack">Back</a> -->
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
        if (status) {
            if (keterangan) {
                $("#btnKirim").hide();
                $("#btnBack").hide();
                $("#btnLoading").show();  
            }
        }
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>
