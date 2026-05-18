<?= form_open($url); ?>

<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-md-12">
            <table id="test">
                <thead>
                    <tr>
                        <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                            <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                            value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                        </th>
                        <th>Kodeprod</th>
                        <th>Namaprod</th>
                        <th>Batch</th>
                        <th>ED</th>
                        <th>Alasan</th>
                        <th>Ket</th>
                        <th>Nama Outlet</th>
                        <th>Qty</th>
                        <th>RBP</th>
                        <th>Qty Approval</th>
                        <th>Keterangan Area</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan_detail->result() as $a) : ?>
                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>
                        <td><?= $a->kodeprod ?></td>
                        <td><?= $a->namaprod ?></td>
                        <td><?= $a->batch_number ?></td>
                        <td><?= date('d M Y', strtotime($a->expired_date)); ?></td>
                        <td><?= $a->nama_alasan ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td><?= $a->nama_outlet ?></td>
                        <td><?= $a->jumlah ?></td>
                        <td><strong>Rp. <?= number_format($a->rbp,2) ?></strong></td>
                        <!-- <td><?= $a->nama_status ?></td> -->
                        <td>
                            <?php
                                if ($a->qty_approval == 0 || $a->qty_approval) { ?>
                                    <input type="number" class="form-control" name="qty_approval[<?= $a->id; ?>]" value="<?= $a->qty_approval ?>" max="<?= $a->jumlah ?>">
                                <?php }else{ ?>                                    
                                    <input type="number" class="form-control" name="qty_approval[<?= $a->id; ?>]" value="">
                                <?php
                                }
                            ?>
                        </td>
                        <td>
                            <?= ($a->keterangan_principal_area) ? "$a->keterangan_principal_area" : "<span class='btn btn-danger btn-sm rounded'>$a->qty_approval belum di verifikasi</span>" ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">Pilih Status Verifikasi</label>
        </div>
        <div class="col-md-4">
            <select name="status_approval" class="form-control" id="status_approval" required>
                <option value="">-- Status --</option>
                <option value="12">APPROVE FULL ROW</option>
                <option value="11">APPROVE PARTIAL ROW</option>
                <option value="13">REJECT ROW</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="keterangan_principal_area">Keterangan</label>
        </div>
        <div class="col-md-4">
            <textarea name="keterangan_principal_area" id="keterangan_principal_area" cols="30" rows="3" class="form-control"></textarea>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4">
            Yang berhak melakukan approval ajuan retur ini adalah username : <label><?= $username_pic_terkait ?></label>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <?php 
                if (!$principal_area_at) { ?>
                    <input type="submit" class="btn btn-submit-black" value="update data" <?= ($params_hak_akses == 1) ? '' : 'disabled' ?> >
                <?php 
                }
            ?>
            <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-submit-black">Back to dashboard</a>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>

    <div class="row mt-5">
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
    

    <div class="row mb-5 mt-2">
        <div class="col-md-12 text-center">
            <p>Cek kembali data anda. Jika sudah ok, klik Button di bawah ini :</p>
        </div>

        <div class="col-md-12 d-flex justify-content-center">
            <div class="form-inline row">
                <div class="col-sm-12">

                    <?php                    
                        if (!$principal_area_at) { ?>
                            <?php echo form_open($url_proses_mpm); ?>
                                <input type="hidden" name="signature" value="<?= $signature ?>">
                                <input type="hidden" name="supp" value="<?= $supp ?>">
                                <input type="submit" style = "width: 100%" value="Proses ke MPM" class="btn btn-submit" <?= ($params_hak_akses == 1) ? '' : 'disabled' ?>>
                            <?= form_close();?>
                        <?php 
                        }else{ ?>
                            <button type="submit" class="btn btn-dark" disabled>permintaan sudah diajukan</button>
                        <?php
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <?= form_close();?>

    <hr><br>

<script>
    $(document).ready(function () {
        $("#test").DataTable({
            // "pageLength": 100,
            // // "ordering": false,
            // "aLengthMenu": [
            //     [10, 20, 50, -1],
            //     [10, 20, 50, "All"]
            // ]
            paging: false,
            scrollCollapse: true,
            scrollY: '500px'
        });
        
        $("#example").DataTable({
            "pageLength": 5,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ]
        });
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('database_afiliasi/kodeprod') ?>",
        data: 'supp=<?= $supp; ?>',
        success: function(hasil_kodeprod) {
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });

</script>