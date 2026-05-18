<style>
    /* .custom-file-input {
    padding: 2px 4px;
    font-size: 0.75rem;
    height: auto;
} */

.custom-blue-btn {
    background-color:rgb(55, 178, 216);   /* Biru muda Bootstrap */
    color: #fff;
    border: none;
    transition: background-color 0.3s ease;
}

.custom-blue-btn:hover {
    background-color:rgb(22, 94, 116);  /* Biru muda lebih gelap saat hover */
    color: #fff;
}

.upload-btn-wrapper.d-none {
    display: none;
}

@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.2; }
}

.blink-new {
  animation: blink 1s linear infinite;
}

.blink-new.stopped {
  animation: none !important;
}

</style>
</div>
<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
    </div>

    <div class="row">
        <div class="col-md-12 text-center">
            <?php 
                if($this->session->flashdata('pesan')){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('pesan'); ?>
                    </div>
                <?php
                }elseif($this->session->flashdata('pesan_success')){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('pesan_success'); ?>
                    </div>
                <?php
                }
            ?>
        </div>
    </div>

    <div class="card-block">
        <div class="row">
            <div class="col-md-12">
                <table id="tabel" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Principal</th>
                            <th>File</th>
                            <th>Keterangan</th>
                            <th>Download</th>
                            <th>Update Berita Acara</th>
                            <!-- <th>Created_at</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list_data as $key => $value) { ?>
                        <tr>
                            <td><?= $value->id;?></td>
                            <td><?= $value->NAMASUPP;?></td>
                            <td><?= $value->nama;?></td>
                            <td><?= $value->keterangan;?></td>
                            <td>
                                <?php 
                                    if ($value->target_csv && file_exists('./assets/file/portal_raw/raw_data/' . $value->target_csv)) {?>
                                    <a href="<?= base_url("management_raw_data/download_file/" . $value->target_csv) ?>" 
                                    class="btn btn-sm custom-blue-btn position-relative d-inline-flex align-items-center gap-1 px-3">
                                        <i class="bi bi-download me-1"></i>Download
                                        <?php if ($value->status == 'new'): ?>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger blink-new">
                                                NEW
                                            </span>
                                        <?php endif; ?>
                                    </a>
                                <?php 
                                    } else {
                                        echo "<span class='text-muted fst-italic'>belum tersedia</span>";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if ($value->filename != null) {
                                        echo anchor(
                                            base_url("management_raw_data/download_zip/$value->signature"),
                                            'Download Berita Acara',
                                            "class='d-block text-success fw-bold mb-1'"
                                        );
                                    // } 
                                    // else {
                                    //     echo anchor(
                                    //         base_url("management_raw_data/download_zip/$value->signature"),
                                    //         'Download Berita Acara',
                                    //         "class='d-block text-success fw-bold mb-1'"
                                    //     );
                                        
                                    }?>
                            
                                <!-- Form Upload -->

                                <!-- <form method="POST" action="<?= base_url('management_raw_data/attachment_config/'.$value->id); ?>" enctype="multipart/form-data">
                                    <input type="file" name="berita_acara" class="form-control form-control-sm mb-1 berita-acara-input" data-btn-id="upload-btn-<?= $value->id ?>">
                                    
                                    <button id="upload-btn-<?= $value->id ?>" type="submit" class="btn btn-sm btn-outline-primary mt-1 d-none">
                                        <i class="bi bi-upload me-1"></i> Upload
                                    </button>
                                </form> -->
                                <?php if ($username == 'milla') { ?>
                                <!-- <form action="<?= base_url('management_raw_data/attachment_config/' . $value->id) ?>" method="post" enctype="multipart/form-data" class="upload-form">
                                    <div class="position-relative">
                                        <input type="file" name="berita_acara" class="form-control form-control-sm mb-1 file-input" required hidden>
                                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 choose-file-btn">
                                            <i class="bi bi-folder2-open me-1"></i> Pilih File
                                        </button>
                                        <div class="upload-btn-wrapper mt-1 d-none">
                                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="bi bi-upload me-1"></i>Upload
                                            </button>
                                        </div>
                                    </div>
                                </form> -->

                                <!-- <form action="<?= base_url('management_raw_data/attachment_config/' . $value->id) ?>" method="post" enctype="multipart/form-data" class="upload-form">
                                    <div class="position-relative">
                                        <input type="file" name="berita_acara[]" class="form-control form-control-sm mb-1 file-input" required hidden multiple>
                                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 choose-file-btn">
                                            <i class="bi bi-folder2-open me-1"></i> Pilih File
                                        </button>
                                        <div class="upload-btn-wrapper mt-1 d-none">
                                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="bi bi-upload me-1"></i>Upload
                                            </button>
                                        </div>
                                    </div>
                                </form> -->

                                <form action="<?= base_url('management_raw_data/attachment_config/' . $value->id) ?>" method="post" enctype="multipart/form-data" class="upload-form">
                                    <div class="position-relative">
                                        <input type="file" name="berita_acara[]" class="form-control form-control-sm mb-1 file-input" required hidden multiple>
                                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 choose-file-btn">
                                            <i class="bi bi-folder2-open me-1"></i> Pilih File
                                        </button>
                                        <div class="upload-btn-wrapper mt-1 d-none">
                                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="bi bi-upload me-1"></i>Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel').DataTable({
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

    $.ajax({ 
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_kategori') ?>',
        success: function(result) {
            $("select[name = kategori]").html(result);
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.upload-form').forEach(form => {
        const fileInput = form.querySelector('.file-input');
        const chooseBtn = form.querySelector('.choose-file-btn');
        const uploadWrapper = form.querySelector('.upload-btn-wrapper');

        chooseBtn.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                chooseBtn.classList.add('d-none');
                uploadWrapper.classList.remove('d-none');
            }
        });
    });
});
</script>


<script>
    // Setelah DOM ready
    window.addEventListener('DOMContentLoaded', function () {
        const badges = document.querySelectorAll('.blink-new');

        setTimeout(() => {
            badges.forEach(badge => badge.classList.add('stopped'));
        }, 5000); // 10000ms = 10 detik
    });
</script>


</body>
</html>