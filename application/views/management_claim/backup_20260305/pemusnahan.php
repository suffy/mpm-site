</div>

<div class="container-fluid">

    <?= form_open_multipart($url); ?>

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
        <div class="col-md-3">
            <label for="status_approval">Tanggal Pemusnahan</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="tanggal_pemusnahan" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="nama_pemusnahan">Nama PIC Pemusnahan</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="nama_pemusnahan" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="file_pemusnahan">File Berita Acara Pemusnahan</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="file_pemusnahan" name="file_pemusnahan" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="foto_pemusnahan_1">File Foto Pemusnahan 1</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="foto_pemusnahan_1" name="foto_pemusnahan_1" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="foto_pemusnahan_2">File Foto Pemusnahan 2</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="foto_pemusnahan_2" name="foto_pemusnahan_2" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="foto_pemusnahan_2">File Video</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="video" name="video" required>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-md-3">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <?php 
                if ($pemusnahan_at) { ?>
            <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>
            <?php
                }else{ ?>
            <?php 
                        
                        if (strtolower(substr($site_code,0,3)) == strtolower($this->session->userdata('username')) || $this->session->userdata('id') == 588 || ($site_code == $this->session->userdata('username'))) { ?>
            <input type="submit" class="btn btn-submit-black" value="Submit Data">
            <?php
                        }
                        
                    ?>
            <?php
                } ?>
            <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-submit-black">Back to
                dashboard</a>

        </div>
    </div>
    
    <?= form_close();?>
</div>

<hr><br>

<script>
    $(document).ready(function () {
        $("#test").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
        $("#example").DataTable({
            "pageLength": 5,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
    });
</script>