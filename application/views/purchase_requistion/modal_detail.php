<!-- Modal -->
<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="no_pr">No. Purchase Request</label>
                    <input class="form-control" name="no_pr" id="no_pr">
                </div>
                <div class="form-group">
                    <label for="username">Created By</label>
                    <input class="form-control" name="username" id="username">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tanggal">Created At</label>
                        <input type="text" class="form-control" id="tanggal" name="tanggal">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="divisi">Divisi</label>
                        <input name="divisi" id="divisi" class="form-control" name="divisi">
                    </div>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="3"></textarea>
                    <hr>
                </div>
                <div class="form-group">
                    <label for="keterangan_atasan">Keterangan Atasan</label>
                    <textarea class="form-control" name="keterangan_atasan" id="keterangan_atasan" cols="30"
                    rows="3"></textarea>
                    <hr>
                </div>
                <div class="form-group">
                    <label for="barang">Barang</label>
                    <textarea class="form-control" name="barang" id="barang" cols="30" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="spesifikasi">Spesifikasi</label>
                    <textarea class="form-control" name="spesifikasi" id="spesifikasi" cols="30" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="keterangan_it">Keterangan IT</label>
                    <textarea class="form-control" name="keterangan_it" id="keterangan_it" cols="30" rows="3"></textarea>
                </div>
                <hr>
                <div class="form-group">
                    <label for="keterangan_purchasing">Keterangan Purchasing</label>
                    <textarea class="form-control" name="keterangan_purchasing" id="keterangan_purchasing" cols="30" rows="3"></textarea>
                </div>
                <hr>
                <div class="form-group">
                    <label for="keterangan_finance">Keterangan Finance</label>
                    <textarea class="form-control" name="keterangan_finance" id="keterangan_finance" cols="30" rows="3"></textarea>
                </div>
            </div>
            <!-- <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div> -->
        </div>
    </div>
</div>