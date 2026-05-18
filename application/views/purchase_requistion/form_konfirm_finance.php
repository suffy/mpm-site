<!-- Modal -->
<div class="modal fade" id="konfirm_finance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveFinanceModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('purchase_requistion/purchase_requistion_konfirm_finance') ?>" method="post">
                <div class="modal-body">
                    <input type="text" id="id" name="id" hidden>
                    <div>
                        <div class="form-group">
                            <label for="keterangan_finance">Keterangan / Alasan</label>
                            <textarea class="form-control" id="keterangan_finance" name="keterangan_finance" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success approve" name="simpan" value="4">Approve</button>
                    <button type="submit" class="btn btn-danger reject" name="simpan" value="9">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>