<!-- Modal -->
<div class="modal fade" id="input_penyerahan_asset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url($url); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="no_po" class="col-sm-4 col-form-label">No. POF</label>
                        <div class="col-sm-6">
                            <input list="data_no_po" name="no_po" id="input_no_po" class="form-control">
                            <datalist id="data_no_po">
                                <option value="automatic">AUTOMATIC</option>
                                <?php foreach($pr as $value){?>
                                <option value="<?= $value->no_po.'-'.$value->id_barang;?>"><?= $value->no_po.'-'.$value->id_barang;?></option>
                                <?php }?>
                            </datalist>
                        </div>
                        <div class="col-2">
                            <a href="#" type="button"
                                class="btn waves-effect waves-light btn-info btn-outline-info btn-sm detail"
                                onclick="Detail()">Detail</a>

                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="no_pr" class="col-sm-4 col-form-label">No. PR</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="no_pr" id="no_pr" readonly>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label for="tanggal" class="col-sm-4 col-form-label">Tanggal Penyerahan</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="tanggal" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ekspedisi" class="col-sm-4 col-form-label">Ekspedisi</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="ekspedisi" id="ekspedisi">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="resi" class="col-sm-4 col-form-label">Resi</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="resi" id="resi">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="penerima" class="col-sm-4 col-form-label">Nama Penerima</label>
                        <div class="col-sm-8">
                            <select name="penerima" id="penerima" class="form-control" required>
                                <option value="">- Pilih -</option>
                                <?php foreach($user as $value){?>
                                <option value="<?= $value->id;?>"><?= $value->username;?> | <?= $value->email;?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga" class="col-sm-4 col-form-label">Ongkir</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="harga" id="harga">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status" id="status" required>
                                <option value="">- Pilih -</option>
                                <option value="baru">Baru</option>
                                <option value="mutasi">Mutasi</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>