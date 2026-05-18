</div>
<?php $this->load->view('management_claim/css/style') ?>
<div class="container-fluid">
    
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

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="kode_outlet" class="form-label">Kode Outlet</label>
            </div>
            <div class="col-md-5 d-flex flex-row">
                <label for=""><?= $kode_outlet ?></label>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="nama_outlet" class="form-label">Nama Outlet</label>
            </div>
            <div class="col-md-5 d-flex flex-row">
                <label for=""><?= $nama_outlet ?></label>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="no_ktp" class="form-label">No KTP</label>
            </div>
            <div class="col-md-5 d-flex flex-row">
                <input type="text" class="form-control" name="no_ktp" value="<?= $no_ktp ?>" required placeholder="No Ktp">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="file_ktp" class="form-label">File KTP</label>
            </div>
            <div class="col-md-5">
                <a href="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$file_ktp) ?>" target="_blank">
                    <img src="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$file_ktp) ?>" alt="<?= $file_ktp ?>" class="img-fluid">
                </a>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="file_npwp" class="form-label">No NPWP</label>
            </div>
            <div class="col-md-5 d-flex flex-row">
                <input type="text" class="form-control" name="no_npwp" value="<?= $no_npwp ?>" required placeholder="No NPWP">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="no_npwp" class="form-label">File NPWP</label>
            </div>
            <div class="col-md-5">
                <a href="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$file_npwp) ?>" target="_blank">
                    <img src="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/master_outlet/'.$file_npwp) ?>" alt="<?= $file_npwp ?>" class="img-fluid">
                </a>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="alamat" class="form-label">Alamat</label>
            </div>
            <div class="col-md-5 d-flex flex-row">
                <textarea name="alamat" class="form-control" rows="5"><?= $alamat ?></textarea>
            </div>
        </div>
        
        <div class="row mt-2">
            <div class="col-md-2">
                <label for="site_code" class="form-label">No Telp</label>
            </div>
            <div class="col-md-5 d-flex flex-row">
                <input type="text" class="form-control" name="no_telp" value="<?= $no_telp ?>" required placeholder="No Telp">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="file_ktp" class="form-label">File SKP</label>
            </div>
            <div class="col-md-5">

                <?php 
                    if($file_skp){ ?>
                        <div class="mt-2">
                            <a href="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/'.$folder.'/'.$file_skp) ?>" target="_blank">
                                <img src="<?= base_url('assets/uploads/management_claim/'.$tahun_folder.'/'.$folder.'/'.$file_skp) ?>" alt="<?= $file_skp ?>" class="img-fluid">
                            </a>
                        </div>
                        <div class="mt-2">
                            <input type="file" class="form-control" name="file_skp">
                            <input type="hidden" class="form-control" name="file_skp_old" value="<?= $file_skp ?>">
                        </div>
                        
                    <?php
                    }else{ ?>                        
                        <div class="mt-2">
                            <input type="file" class="form-control" name="file_skp">
                            <input type="hidden" class="form-control" name="file_skp_old" value="<?= $file_skp ?>">
                        </div>
                    <?php
                    }
                ?>

            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="file_npwp" class="form-label">Paket</label>
            </div>
            <div class="col-md-5 ms-4">
                <input type="radio" name="paket" class="form-check-input" value="A" required <?= $paket == 'A' ? 'checked' : '' ?>>
                <label for="paket" class="form-label">A</label>
                <input type="radio" name="paket" class="form-check-input" value="B" required <?= $paket == 'B' ? 'checked' : '' ?>>
                <label for="paket" class="form-label">B</label>
                <input type="radio" name="paket" class="form-check-input" value="C" required <?= $paket == 'C' ? 'checked' : '' ?>>
                <label for="paket" class="form-label">C</label>
                <input type="radio" name="paket" class="form-check-input" value="D" required <?= $paket == 'D' ? 'checked' : '' ?>>
                <label for="paket" class="form-label">D</label>
                <input type="radio" name="paket" class="form-check-input" value="E" required <?= $paket == 'E' ? 'checked' : '' ?>>
                <label for="paket" class="form-label">E</label>
                <input type="radio" name="paket" class="form-check-input" value="F" required <?= $paket == 'F' ? 'checked' : '' ?>>
                <label for="paket" class="form-label">F</label>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
            </div>
            <div class="col-md-5 d-flex flex-row gap-1">
                <input type="hidden" name="signature_peserta" value="<?= $signature_peserta ?>">
                <input type="hidden" name="signature_program" value="<?= $signature_program ?>">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="id_program" value="<?= $id_program ?>">
                <button type="submit" class="btn btn-submit-red" style="height: 45px">Update Data</button>                 
            </div>
        </div>

    </div>
</div>

<?php echo form_close(); ?>


<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel-ajuan-claim').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>