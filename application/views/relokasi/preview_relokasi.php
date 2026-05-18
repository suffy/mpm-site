<?php 
// var_dump($history_relokasi->row());

    $tanggal_pengajuan = $history_relokasi->row()->tanggal_pengajuan;
    $no_relokasi = $history_relokasi->row()->no_relokasi;
    $from_nama_comp = $history_relokasi->row()->from_nama_comp;
    $to_nama_comp = $history_relokasi->row()->to_nama_comp;
    $nama = $history_relokasi->row()->nama;
    $namasupp = $history_relokasi->row()->namasupp;
    $nama_status = $history_relokasi->row()->nama_status;
    $approve_supplychain_at = $history_relokasi->row()->approve_supplychain_at;
    $approve_finance_at = $history_relokasi->row()->approve_finance_at;

    // echo "tanggal_pengajuan : ".$tanggal_pengajuan;

    // die;

?>

<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>

        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group row">
                                                <label class="col-sm-4">No Relokasi</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="no_relokasi" value="<?= $no_relokasi; ?>" readonly required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4">PIC</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tanggal_pengajuan" value="<?= $nama; ?>" readonly required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4">Principal</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tanggal_pengajuan" value="<?= $namasupp; ?>" readonly required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4">Approve Supplychain at</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tanggal_pengajuan" value="<?= $approve_supplychain_at; ?>" readonly required>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group row">
                                                <label class="col-sm-4">Tanggal Pengajuan</label>
                                                <div class="col-sm-7">
                                                    <input type="date" class="form-control" name="tanggal_pengajuan" value="<?= $tanggal_pengajuan; ?>" readonly required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4">From -> To</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" value="<?= $from_nama_comp. ' -> '.$to_nama_comp; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4">Status</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tanggal_pengajuan" value="<?= $nama_status; ?>" readonly required>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-sm-4">Approve Finance at</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tanggal_pengajuan" value="<?= $approve_finance_at; ?>" readonly required>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                            

                                    <br>

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

                                    <br>

                                    <a href="<?= base_url().'relokasi/produk_pengajuan/'.$signature.'/'.$principal ?>" class="btn btn-dark">Kembali</a>

                                    <?php 
                                        if ($status == 1 || $status == 2) { ?>
                                            
                                            <a href="<?= base_url().'relokasi/approval_supplychain/'.$signature ?>" class="btn btn-warning" target="_blank">Meminta approval Supplychain</a>
                                        
                                        <?php }elseif($status == 3){ ?>

                                            <a href="<?= base_url().'relokasi/approval_finance/'.$signature ?>" class="btn btn-danger" target="_blank">Meminta approval Finance</a>

                                        <?php }
                                    ?>

                                    
                                </div>

                                                               
                            </div>
                        </div>
                    </div>
                </div>

                

            </div>
        </div>
    </div>


</div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/subbranch') ?>',
        data: {},
        success: function(hasil) {
            $("select[name = from_site]").html(hasil);
            $("select[name = to_site]").html(hasil);
        }
    });
</script>

                        
                        