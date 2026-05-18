<!-- verifikasi_principal.php -->
<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; justify-content: center; align-items: center; flex-direction: column; backdrop-filter: blur(3px);">
    <div style="text-align: center;">
        <div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem; border-width: 0.25em; color: #B43F3F !important;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p style="margin-top: 15px; color: #333; font-weight: 500; font-size: 16px;">Processing your request...</p>
        <p style="margin-top: 5px; color: #666; font-size: 14px;">Please wait</p>
    </div>
</div>
    
<?php echo form_open_multipart($url); ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>

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

    <!-- <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_internal">Status</label>
        </div>
        <div class="col-md-4">
            <select name="status_internal" id="status_internal" class="form-control" required>
                <option value=""> -- Pilih Status -- </option>
                <option value="4">APPROVE</option>
                <option value="3">REVISI</option>
            </select>
        </div>
    </div> -->

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

    <!-- <div class="row mt-2">
        <div class="col-md-2">
            <label for="list_log">List Log</label>
        </div>
        <div class="col-md-4">
            <select name="list_log" id="list_log" class="form-control" required>
            </select>
        </div>
    </div> -->

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
            <input class="form-control form-control-md" id="file" type="file" name="file" required>
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-8">
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
            
            <!-- <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-submit-black" id="btnBack" style="width: 100px;height: 40px;padding: 10px 5px 10px 5px">Back</a> -->
        </div>
    </div>
<?php echo form_close(); ?>

<script>
// Fungsi untuk menampilkan loading overlay
function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

// Fungsi untuk menyembunyikan loading overlay
function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// Fungsi button() untuk validasi form dan menampilkan loading
function button() {
    // Validasi form sebelum submit
    var status_internal = document.querySelector('select[name="status_internal"]').value;
    var keterangan = document.querySelector('textarea[name="keterangan"]').value;
    var file = document.querySelector('input[name="file"]').value;
    
    if (!status_internal) {
        alert('Status harus dipilih!');
        return false;
    }
    
    if (!keterangan) {
        alert('Keterangan / Note harus diisi!');
        return false;
    }
    
    if (!file) {
        alert('Attach file harus diupload!');
        return false;
    }
    
    // Tampilkan loading
    showLoading();
    
    // Form akan disubmit secara normal
    return true;
}

// Event listener untuk form submit
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Cek apakah submit berasal dari tombol dengan id btnKirim
            var isFromButton = e.submitter && e.submitter.id === 'btnKirim';
            
            // Jika validasi sudah dilakukan di fungsi button(), form akan submit
            if (isFromButton) {
                // Validasi sudah dilakukan di fungsi button()
                // Loading akan ditampilkan di fungsi button()
            }
        });
    }
    
    // Sembunyikan loading saat halaman selesai dimuat (jika ada)
    hideLoading();
    
    // Sembunyikan loading saat user menggunakan tombol back browser
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoading();
        }
    });
});

// Tampilkan loading untuk link jika ada tombol back
document.addEventListener('DOMContentLoaded', function() {
    // Untuk tombol Back jika ada di masa mendatang
    var backBtn = document.getElementById('btnBack');
    if (backBtn) {
        backBtn.addEventListener('click', function(e) {
            showLoading();
        });
    }
});

// Script untuk select status internal (yang sudah ada)
$("select[name = status_internal]").on("change", function() {
    var status_internal_terpilih = document.getElementById('status_internal').value;
    console.log(status_internal_terpilih);

    if (status_internal_terpilih == "23") { //jika supp = deltomed
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('management_claim/action_principal') ?>',
            data: {
                'id_ajuan': '<?= $id_log ?>',   
            },
            success: function(hasil_action) {
                $("select[name = list_log]").html(hasil_action);
            }
        });
    }else{
        console.log(100)
    }
});
</script>