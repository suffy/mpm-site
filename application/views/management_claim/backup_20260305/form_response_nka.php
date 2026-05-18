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
                        <div class="col-md-2">
                            <label for="status">Status</label> 
                        </div>
                        <div class="col-md-4">
                            <select name="action" id="action" class="form-select" required>
                                <option value="">- Pilih Status -</option>
                                <option value="1">Approve</option>
                                <option value="0">Reject</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1" id="divform_keterangan">
                        <div class="col-md-2">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="col-md-4">
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="7" placeholder="Masukan Keterangan" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <!-- cek can_approve -->
                      <?php 
                        if($can_approve){ ?>
                            <?php 
                                if($status == 4){ ?>
                                    <label class="form-label" style="color: black; border: 1px solid black; padding: 5px;" ><?= $nama_status; ?></label>
                                <?php
                                }else{ ?>
                                    <button type="submit" class="btn btn-submit-red">Submit Data</button>
                                <?php
                                }
                            ?>
                            
                        <?php
                        }else{ ?>
                            <label class="form-label" style="color: red; border: 1px solid red; padding: 2px;" >menunggu response dari : <?= $username_on_duty; ?></label>
                        <?php
                        }
                    // <?= form_submit('submit', 'Submit', 'class="btn btn-submit-red"'); ?>
                </div>
            </div>
          <?= form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>