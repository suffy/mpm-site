<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.group-header {
    cursor: pointer;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 8px;
}

.icon-play {
    width: 0;
    height: 0;
    border-left: 10px solid #6c757d;
    border-top: 6px solid transparent;
    border-bottom: 6px solid transparent;
    transition: transform .2s ease;
}

.icon-play.rotate {
    transform: rotate(90deg);
}

.table-clean {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table-clean th,
.table-clean td {
    padding: 8px;
    border: none;
}

.table-clean thead {
    background: #f5f5f5;
}

.table-clean tbody tr:nth-child(even) {
    background: #f9f9f9;
}

.text-right {
    text-align: right;
}
</style>

<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0"><?= $title ?></h5>
        </div>

        <div class="card-body">
            <?= form_open_multipart($url); ?>

            <!-- Flash Message -->
            <?php if($this->session->flashdata('pesan')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
            <?php endif; ?>

            <!-- Data Grouped by Tanggal -->
            <?php foreach ($data_by_tanggal as $tanggal => $items): ?>
                <div class="card mb-3">
                    <div class="card-header group-header" onclick="toggleGroup(this)">
                        <div class="icon-play"></div>
                        <!-- <?php if($items[0]->is_valid_tanggal == 1): ?>
                            <span class="badge bg-success" style="left:;">Valid</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Invalid</span>
                        <?php endif; ?> -->
                        <?= date('d F Y', strtotime($tanggal)) ?>
                    </div>

                    <div class="card-body p-0">
                        <table class="table-clean" style="display:table;">
                            <thead>
                                <tr>
                                    <th>ID Activity</th>
                                    <th>Nama Activity</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $row): ?>
                                    <tr>
                                        <td><?= $row->id_activity ?></td>
                                        <td><?= $row->title ?></td>
                                        <td><?= $row->keterangan ?></td>
                                        <td>
                                            <?php if($row->is_valid_activity == 1 && $row->is_valid_tanggal == 1): ?>
                                                <span class="badge bg-success">Valid</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Invalid</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Summary -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Summary</h6>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label>Total Row</label>
                            <input type="text" class="form-control bg-light" 
                                   value="<?= $get_summary->row()->total_row ?>" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>Valid Activity</label>
                            <input type="text" class="form-control bg-success-subtle border-success" 
                                   value="<?= $get_summary->row()->valid_activity ?>" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>Invalid Activity</label>
                            <input type="text" class="form-control bg-danger-subtle border-danger" 
                                   value="<?= $get_summary->row()->invalid_activity ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button Section -->
            <div class="mt-4 d-flex gap-2">
                <button type="button" id="btnBack" class="btn btn-secondary">
                    Back
                </button>

                <?php if($params_invalid): ?>
                    <p class="text-danger ms-auto align-self-center">
                        Tidak bisa melanjutkan karena ada activity tidak valid atau tanggal tidak valid. Silakan perbaiki data terlebih dahulu.
                    </p>
                <?php else: ?>
                    <button type="submit" id="btnLanjutkan" class="btn btn-primary ms-auto">
                        Lanjutkan
                    </button>
                <?php endif; ?>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="fullPageLoading" class="loading-overlay">
    <div class="text-center text-white">
        <div class="spinner-border"></div>
        <h5 class="mt-3">Processing...</h5>
    </div>
</div>

<script>
function toggleGroup(header) {
    const icon = header.querySelector('.icon-play');
    const body = header.nextElementSibling;
    const table = body.querySelector('table');

    if (table.style.display === 'none') {
        table.style.display = 'table';
        icon.classList.add('rotate');
    } else {
        table.style.display = 'none';
        icon.classList.remove('rotate');
    }
}

document.getElementById('btnBack')?.addEventListener('click', function(){
    window.history.back();
});

document.querySelector("form")?.addEventListener("submit", function(){
    document.getElementById("fullPageLoading").style.display = "flex";
});
</script>