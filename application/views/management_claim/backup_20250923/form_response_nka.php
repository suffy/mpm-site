<div class="container-fluid">
    <div class="col-md-12">
        <!-- form -->
        <div class="row mt-3">
            <div class="card">
                <div class="card-body">
                    <?= form_open_multipart($url,  ['method' => 'post'])?> 
                        <div class="row mt-3">
                            <input type="text" name="signature" value="<?= $signature;?>" hidden>
                            <div class="col-md" id="divform1">
                                <div class="row mt-1" id="divform_status">
                                    <div class="col-lg-3">
                                        <label for="status">Status</label> 
                                    </div>
                                    <div class="col-lg-4">
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="">- Pilih Status -</option>
                                            <option value="1">Approve</option>
                                            <option value="9">Reject</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-1" id="divform_keterangan">
                                    <div class="col-lg-3">
                                        <label for="keterangan">Keterangan</label>
                                    </div>
                                    <div class="col-lg-9">
                                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukan Keterangan" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3" style="text-align: center;">
                            <div class="col-md-12">
                                <?= form_submit('submit', 'Submit', 'class="btn btn-submit-black"'); ?>
                            </div>
                        </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>