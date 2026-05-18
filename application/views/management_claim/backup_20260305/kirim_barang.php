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
        <div class="col-md-2">
            <label for="status_approval">Tanggal Kirim Barang</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="tanggal_kirim_barang" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">Estimasi Tanggal Tiba</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="est_tanggal_tiba" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">Nama Ekspedisi</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="nama_ekspedisi" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="catatan">Attach Resi Pengiriman</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <?php 
                if ($tanggal_kirim_barang) { ?>
                    <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>
                <?php
                }else{ ?>
                    <?php
                        if (substr($site_code,0,3) == strtoupper($this->session->userdata('username')) || $this->session->userdata('id') == 588 || $this->session->userdata('id') == 857 || $site_code == $this->session->userdata('username')) { ?>
                            <input type="submit" class="btn btn-submit-black" value="Submit Data">
                        <?php
                        }
                    ?>
                <?php
                } ?>
                
                <a href="<?= base_url().'management_inventory/pengajuan_retur' ?>" class="btn btn-submit-black">Back to dashboard</a>
                
        </div>
    </div>

    <?= form_close();?>

    <hr><br>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 5,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
      });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?= base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?= base_url('database_afiliasi/kodeprod') ?>',
        data: 'supp=<?= $supp; ?>',
        success: function(hasil_kodeprod) {
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });

</script>