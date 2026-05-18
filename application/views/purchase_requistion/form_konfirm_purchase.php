<!-- Modal -->
<div class="modal fade" id="konfirm_purchase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvePurchaseModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('purchase_requistion/purchase_requistion_konfirm_purchasing'); ?>" method="post">
                <div class="modal-body">
                    <input class="form-control" name="id" id="id" hidden>
                    <div class="form-group">
                        <label for="barang">Barang</label>
                        <textarea class="form-control" id="barang" name="barang" cols="30" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="spesifikasi">Spesifikasi</label>
                        <textarea class="form-control" id="spesifikasi" name="spesifikasi" cols="30"
                            rows="3"></textarea>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="keterangan_purchasing">Keterangan Purchasing</label>
                        <textarea class="form-control" id="keterangan_purchasing" name="keterangan_purchasing" cols="30"
                            rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="simpan" value="3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>