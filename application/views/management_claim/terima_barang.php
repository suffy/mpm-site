</div>

<div class="container-fluid">
    
    <?= form_open_multipart($url);?>

    <div class="row mt-2">
        <div class="col-md-12 az-content-label text-center">
            <?php 
                if($this->session->flashdata('pesan')){ ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $this->session->flashdata('pesan'); ?>
                    </div>
                <?php
                }elseif($this->session->flashdata('pesan_success')){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= $this->session->flashdata('pesan_success'); ?>
                    </div>
                <?php
                }
            ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="test">
                <thead>
                    <tr>
                        <th>Kodeprod</th>
                        <th>Namaprod</th>
                        <th>Batch</th>
                        <th>ED</th>
                        <th>Alasan</th>
                        <th>Nama Outlet</th>
                        <th>Qty</th>
                        <th>Qty LPK</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan_detail->result() as $a) : ?>
                    <tr>
                        <td><?= $a->kodeprod ?></td>
                        <td><?= $a->namaprod ?></td>
                        <td><?= $a->batch_number ?></td>
                        <td><?= $a->expired_date ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td><?= $a->nama_outlet ?></td>
                        <td><?= $a->qty_approval ?></td>
                        <td>
                            <input type="number" id="<?= $a->id; ?>" name="id_detail[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>" hidden>
                            <?php
                                if ($a->qty_lpk == null) { ?>
                                    <input type="number" class="form-control" name="qty_lpk[]" value="<?= $a->qty_approval ?>" max="<?= $a->jumlah ?>">
                                <?php }else{ ?>                                    
                                    <input type="number" class="form-control" name="qty_lpk[]" value="<?= $a->qty_lpk ?>">
                                <?php
                                }
                            ?>
                        </td>
                        <td><textarea class="form-control" cols="50" name="keterangan[]" placeholder="Masukan keterangan (Opsional)"><?= $a->keterangan_terima_barang ?></textarea></td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="tanggal_terima_barang">Tanggal Terima Barang</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="tanggal_terima_barang" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_penerima">Nama Penerima</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="nama_penerima" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">No Terima Barang (SPBR)</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="nomor_lpk" id="nomor_lpk">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="catatan">Attach Tanda Bukti Terima Barang</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <?php 
                if ($terima_barang_at) { ?>
                    <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>
                <?php
                }else{ ?>

                <?php          
                    // echo "status_ho : ".$status_ho;
                if ($status_ho->num_rows() > 0) {
                    
                    if ($status_ho->row()->status_ho == 1) { ?>
                        <input type="submit" class="btn btn-submit-black" value="Submit Data">
                    <?php
                    }
                }else{ 

                    if ($this->session->userdata('id') == 588 || $this->session->userdata('id') == 297 || $this->session->userdata('id') == 857) { ?>
                        <input type="submit" class="btn btn-submit-black" value="Submit Data">
                    <?php
                    }                        
                }
                ?> 

                <?php
                } ?>
                    
                <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-submit-black">Back to dashboard</a>
                
        </div>
    </div>

    <?= form_close();?>

    <hr><br>
</div>

<script>
    $(document).ready(function () {
        $("#test").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });

        var supp = "<?= substr($supp,0,3); ?>";
        if (supp == '001') {
            $('#nomor_lpk').attr('required', false)
            $('#file').attr('required', false)
        } else {
            $('#nomor_lpk').attr('required', true)
            $('#file').attr('required', true)
        }
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/kodeprod') ?>',
        data: 'supp=<?= $supp; ?>',
        success: function(hasil_kodeprod) {
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });

</script>