</div>

<div class="container-fluid">

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

    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <?= form_open_multipart($url);?>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">Pelaksana</label>
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $pelaksana ?></label>
            <!-- <input type="text" class="form-control" name="aktivitas" value="<?= $a->aktivitas ?>"> -->
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">Jabatan / Level karyawan</label>
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $jabatan ?></label>
            <!-- <input type="text" class="form-control" name="aktivitas" value="<?= $a->aktivitas ?>"> -->
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">No RPD</label>
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $no_rpd ?></label>
            <!-- <input type="text" class="form-control" name="aktivitas" value="<?= $a->aktivitas ?>"> -->
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">Radius Perjalanan</label>
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $radius_perjalanan ?></label>
            <!-- <input type="text" class="form-control" name="aktivitas" value="<?= $a->aktivitas ?>"> -->
        </div>
    </div>

    <div class="row mt-2 mb-5">
        <div class="col-md-2">
            <label for="keterangan_realisasi">Attachment Map</label>
        </div>
        <div class="col-md-4">
            <?php
            if (!$attachment_radius_perjalanan == null) { 
                $file = './assets/file/rpd/'.$attachment_radius_perjalanan; ?>
                <a href="<?= base_url($file) ?>">
                    <img src="<?= base_url($file) ?>" alt="<?= $file ?>" width="100%" height="auto" />
                </a>
            <?php
            }else{
                echo 'File tidak ada';
            }
            ?>
        </div>
    </div>

    <div class="row mt-2 mb-5">
        <div class="col-md-2">
            <label for="keterangan_akomodasi">Keterangan Akomodasi</label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>">
            <textarea name="keterangan_akomodasi" cols="30" rows="5" class="form-control" placeholder="masukkan nomor voucher hotel, pesawat, dll"><?= $keterangan_akomodasi ?></textarea>
        </div>
    </div>

    <div class="row mt-2 mb-5">
        <div class="col-md-2">
            <label for="keterangan_realisasi">Attachment Akomodasi</label>
        </div>
        <div class="col-md-4">
            <?php
            if ($attachment_akomodasi) {
                $file = './assets/file/rpd/'.$attachment_akomodasi;
                if (file_exists($file)) {
                    echo '<a href="'.base_url($file).'" class="btn btn-pdf btn-sm" download="'.$attachment_akomodasi.'">Download '.$attachment_akomodasi.'</a>';
                    echo '<br><br><input type="file" class="form-control" id="files" name="attachment_akomodasi" required>';
                }else{
                    echo 'File tidak ada';
                }
            }else{?>
                <input type="file" class="form-control" id="files" name="attachment_akomodasi" required>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="row mt-2 mb-5">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>">
            <input type="submit" class="btn btn-submit-black" value="Submit Akomodasi" <?= $this->session->userdata('username') == 'admin_deltomed' || $this->session->userdata('username') == 'imas' ? 'enabled' : 'disabled' ?>>
            <a href="<?= base_url().'management_rpd/pengajuan' ?>" class="btn btn-submit-black">back to pengajuan RPD</a>
        </div>
    </div>

    <?= form_close();?>

    <br>

</div>


<script>
    $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/kodeprod') ?>',
        data: 'supp=<?= $this->uri->segment('4') ?>',
        success: function(hasil_kodeprod) {
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });

</script>