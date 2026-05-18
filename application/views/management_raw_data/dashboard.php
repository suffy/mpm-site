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

.btn-submit-black {
    background-color: transparent;
    color: black;
    border: 1px solid black;
    transition: all 0.2s ease-in-out;
}

.btn-submit-black.active {
    background-color: #535353ff !important;  /* Abu kehitaman muda */
    color: white !important;
    font-weight: bold;
    border-color: #5a5a5a;
}

.btn-submit-black:hover {
    background-color: #e0e0e0;
}

</style>
</div>
<div class="container-fluid">

<div class="card">
    <div class="card-body pd-2">
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

    <!-- button untuk upload dan history -->
    <div class="card-body">
        <button class="btn btn-submit-black active" type="button" id="btn-berjalan">
            Raw Data Berjalan
        </button>
        <button class="btn btn-submit-black" type="button" id="btn-closing">
            Raw Data Closing
        </button>
     </div>

    <!-- Tabel Raw Data Berjalan -->
    <div id="tabel-berjalan">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="tabel-berjalan1" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 25px;">No</th>
                                <th class="text-center" style="width: 300px;">Principal</th>
                                <th class="text-center" style="width: 250px;">File</th>
                                <th class="text-center" style="width: 150px;">Keterangan</th>
                                <th class="text-center">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($list_data_harian as $key => $value) { ?>
                            <tr>
                                <td class="text-center"><?= $no++;?></td>
                                <td><?= $value->NAMASUPP;?></td>
                                <td><?= $value->nama;?></td>
                                <td class="text-center"><?= $value->keterangan;?></td>
                                <td class="text-center">
                                    <?php 
                                        if ($value->target_csv && file_exists('./assets/file/portal_raw/raw_data/' . $value->target_csv)) {?>
                                        <a href="<?= base_url("management_raw_data/download_file/" . $value->target_csv) ?>" 
                                        class="btn btn-sm custom-blue-btn position-relative d-inline-flex align-items-center gap-1 px-3">
                                            <i class="bi bi-download me-1"></i>Download
                                            <?php if ($value->status == 'new' && $value->filename != null): ?>
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger blink-new">
                                                    NEW
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                    <?php 
                                        } else {
                                            echo "<span class='text-muted fst-italic'>sudah tidak tersedia</span>";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Raw Data Closing -->
    <div id="tabel-closing" style="display: none;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="tabel-closing2" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20px;">No</th>
                                <th class="text-center" style="width: 270px;">Principal</th>
                                <th class="text-center" style="width: 200px;">File</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center" style="width: 150px;">Download</th>
                                <th class="text-center">Update Berita Acara</th>
                                <th class="text-center">Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($list_data_closing_bulanan as $key => $value) { ?>
                            <tr>
                                <td class="text-center"><?= $no++;?></td>
                                <td><?= $value->NAMASUPP;?></td>
                                <td><?= $value->nama;?></td>
                                <td class="text-center"><?= $value->keterangan;?></td>
                                <td class="text-center">
                                    <?php 
                                        if ($value->target_csv && file_exists('./assets/file/portal_raw/raw_data/' . $value->target_csv)) {?>
                                        <a href="<?= base_url("management_raw_data/download_file/" . $value->target_csv) ?>" 
                                        class="btn btn-sm custom-blue-btn position-relative d-inline-flex align-items-center gap-1 px-3">
                                            <i class="bi bi-download me-1"></i>Download
                                            <?php if ($value->status == 'new' && $value->filename != null): ?>
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger blink-new">
                                                    NEW
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                    <?php 
                                        } else {
                                            echo "<span class='text-muted fst-italic'>sudah tidak tersedia</span>";
                                        }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        if ($value->filename != null) {
                                            echo anchor(
                                                base_url("management_raw_data/download_zip/$value->signature"),
                                                'Download Berita Acara',
                                                "class='d-block text-success fw-bold mb-1'"
                                            );
                                        }else{echo "<span class='text-muted fst-italic'>tidak ada revisi</span>";}?>
                                
                                    <!-- Form Upload -->
                                    <?php if ($username == 'milla' || $username == 'rifqi') { ?>
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
                                <td class="text-center"><?= $value->created_at;?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


</div>

<script>
$(document).ready(function () {
    let dtBerjalan = $('#tabel-berjalan1').DataTable({
        pageLength: 13,
        ordering: true,
        scrollX: true
    });

    let dtClosing = null;

    // Toggle ke tabel berjalan
    $('#btn-berjalan').on('click', function () {
        $('#tabel-berjalan').show();
        $('#tabel-closing').hide();

        $(this).addClass('active');
        $('#btn-closing').removeClass('active');
    });

    // Toggle ke tabel closing + inisialisasi jika belum
    $('#btn-closing').on('click', function () {
        $('#tabel-berjalan').hide();
        $('#tabel-closing').show();

        $(this).addClass('active');
        $('#btn-berjalan').removeClass('active');

        if (!dtClosing) {
            dtClosing = $('#tabel-closing2').DataTable({
                pageLength: 10,
                // ordering: true,
                scrollX: true
            });
        }
    });
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