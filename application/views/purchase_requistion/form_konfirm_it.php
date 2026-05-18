<!-- Modal -->
<div class="modal fade" id="konfirm_it" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveITModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="approve">
                <form action="<?= base_url('purchase_requistion/purchase_requistion_konfirm_it'); ?>" method="post">
                    <div class="modal-body">
                        <input class="form-control" name="id" id="id" hidden>
                        <div class="form-group">
                            <label for="barang">Barang</label>
                            <textarea class="form-control" id="barang" name="barang" cols="30" rows="3"></textarea>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="spesifikasi">Spesifikasi</label>
                            <textarea class="form-control" id="spesifikasi" name="spesifikasi" cols="30"
                                rows="3"></textarea>
                        </div>
                        <div class="form-group keterangan_it">
                            <label for="keterangan_it">Keterangan IT</label>
                            <textarea class="form-control" name="keterangan_it" id="keterangan_it" cols="30"
                                rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bypass" id="bypass" value="1">
                                <label class="form-check-label" for="bypass">
                                    Bypass
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="simpan" value="2">Save</button>
                    </div>
                </form>
            </div>

            <div class="reject">
                <form action="<?= base_url('purchase_requistion/purchase_requistion_konfirm_it'); ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group keterangan_it">
                            <input class="form-control" name="id" id="id" hidden>
                            <label for="keterangan_it">Keterangan IT</label>
                            <textarea class="form-control" name="keterangan_it" id="keterangan_it" cols="30"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" name="simpan" value="10">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>