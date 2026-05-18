<!-- Modal -->
<div class="modal fade" id="form_pr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Purchase Requistion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url($url) ;?>" method="post">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" id="divisi" class="form-control" name="divisi">
                                <option value="">- Pilih Divisi -</option>
                                <option value="AUDIT">AUDIT</option>
                                <option value="FINANCE & ACCOUNTING">FINANCE & ACCOUNTING</option>
                                <option value="KAM">KAM</option>
                                <option value="IT">IT</option>
                                <option value="SALES & MARKETING">SALES & MARKETING</option>
                                <option value="SUPPLY CHAIN">SUPPLY CHAIN</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group input-barang">
                        <label for="barang">Barang</label>
                        <textarea type="text" name="barang" id="barang" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>