<!-- signature -->

<?php 
    $tanggal_pengajuan = $history_relokasi->row()->tanggal_pengajuan;
    $no_relokasi = $history_relokasi->row()->no_relokasi;
    $from_nama_comp = $history_relokasi->row()->from_nama_comp;
    $to_nama_comp = $history_relokasi->row()->to_nama_comp;
    $nama = $history_relokasi->row()->nama;
    $namasupp = $history_relokasi->row()->namasupp;
    $nama_status = $history_relokasi->row()->nama_status;
    $status = $history_relokasi->row()->status;
    $approve_supplychain_at = $history_relokasi->row()->approve_supplychain_at;
    $approve_finance_at = $history_relokasi->row()->approve_finance_at;
    $alasan = $history_relokasi->row()->alasan;

    // echo "status : ".$status;
    // die;
?>

<?php echo form_open('master_data/accept_supplychain_proses'); ?>

<input type="hidden" name="signature" value="<?= $signature ?>">

<div class="card">

    <?php 
        if ($status == 2) { ?>
            
          <div class="row mt-5">
              <div class="col-auto">&nbsp;</div>
              <div class="col-md-2"><label>Draw your signature</label></div>
              <div class="col-md-8">
                  <div id="sig"></div>
              </div>
          </div>

          <div class="row mt-3">
              <div class="col-auto">&nbsp;</div>
              <div class="col-md-2"></div>
              <div class="col-md-8">
                  <textarea name="signed" id="signature64" style="display: none;"></textarea>
                  <button class="btn btn-primary" type="submit">Accept Supplychain</button>
                  <button id="clear" class="btn btn-default" type="reset">Clear</button>
              </div>
          </div>

        <?php }elseif($status == 5){ ?>

          <div class="row mt-5">
              <div class="col-auto">&nbsp;</div>
              <div class="col-md-4">status : <i><b>"REJECTED BY SUPPLYCHAIN"</b></i> at <?= $approve_supplychain_at; ?>
                <img src="<?= base_url().'assets/uploads/signature/'.$signature.'-signature.png' ?>" alt="" srcset="">
              </div>
          </div>
    
        <?php } ?>

    
    
    <br><br><hr>

    <div class="row">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"><h4>Preview Relokasi</h4></div>
    </div>
    
    <div class="row">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - No Relokasi</div>
        <div class="col-md-3">: <?= $no_relokasi; ?></div>
    </div>
    <div class="row mt-1">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - Principal</div>
        <div class="col-md-3">: <?= $namasupp; ?></div>
    </div>
    <div class="row mt-1">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - Tanggal Pengajuan</div>
        <div class="col-md-3">: <?= $tanggal_pengajuan; ?></div>
    </div>
    <div class="row mt-1">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - From -> To</div>
        <div class="col-md-3">: <?= $from_nama_comp.' -> '.$to_nama_comp; ?></div>
    </div>
    <div class="row mt-1">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - PIC</div>
        <div class="col-md-3">: <?= $nama; ?></div>
    </div>
    <div class="row mt-1">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - Alasan</div>
        <div class="col-md-3">: <?= $alasan; ?></div>
    </div>
    <div class="row mt-1">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - Current status</div>
        <div class="col-md-3">: <?= $nama_status; ?></div>
    </div>
    <div class="row mt-1">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - Approve/Reject supplychain at</div>
        <div class="col-md-3">: <?= $approve_supplychain_at; ?></div>
    </div>
    <div class="row mt-1">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-2"> - Approve/Reject finance at</div>
        <div class="col-md-3">: <?= $approve_finance_at; ?></div>
    </div>
    
    <hr>
    <div class="row mt-3">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-11">
        List status = draft -> need supplychain approval -> need finance approval -> approved
        </div>
    </div>
    <!-- <hr> -->
    

    <div class="row mt-3">
        <div class="col-auto">&nbsp;</div>
        <div class="col-md-11">
            <table class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th class="col-1"><font size="2px">kodeprod</th>
                    <th class="col-2"><font size="2px">namaprod</th>
                    <th class="col-1"><font size="2px">qty</th>
                    <th class="col-1"><font size="2px">created at</th>
                    <th class="col-1"><font size="2px">created by</th>
                </tr>
            </thead>
            <tbody>                                        
                <?php 
                // var_dump($history_produk->result());
                foreach ($history_produk->result() as $a) : ?>
                <tr>
                    <td><font size="2px"><?= $a->kodeprod; ?></td>
                    <td><font size="2px"><?= $a->namaprod; ?></td>
                    <td><?= $a->qty; ?></td>                                                
                    <td><?= $a->created_at; ?></td>                                                
                    <td><?= $a->username; ?></td>                                                
                </tr>
                <?php endforeach; ?>    
            </tbody>
        </table>
        </div>
    </div>

    
    
</div> 
</form>


<script type="text/javascript">
    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
    $('#clear').click(function (e){
      e.preventDefault();
      sig.signature('clear');
      $("#signature64").val('');
    });
  </script>

    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
      integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
      crossorigin="anonymous"
    ></script>