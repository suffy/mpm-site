<!-- Modal Popup untuk menampilkan aktivitas per tanggal -->
<div class="modal fade" id="activitiesModal" tabindex="-1" aria-labelledby="activitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="activitiesModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i> Planning
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="activitiesModalBody" style="max-height: 500px; overflow-y: auto;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data aktivitas...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModalBtn" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
                <button type="button" class="btn btn-primary" id="addActivityFromModal">
                    <i class="fas fa-plus me-1"></i> Tambah Planning
                </button>
            </div>
        </div>
    </div>
</div>