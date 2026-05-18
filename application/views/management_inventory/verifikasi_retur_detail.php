</div>

<?= form_open($url); ?>

<div class="container-fluid">
    <div class="row mt-1">
        <div class="col-md-12">
            <table id="example2">
                <thead>
                    <tr>
                        <th class="text-center col-1" style="background-color: #1d1d1d; color: white" >
                            <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                            value="click all" onclick="click_all_request()" style="background-color: #1d1d1d; color: white">
                        </th>
                        <th>status</th>
                        <th>Ket MPM</th>
                        <th>Kodeprod</th>
                        <th>Namaprod</th>
                        <th>Batch</th>
                        <th>ED</th>
                        <th>Alasan DP</th>
                        <th>Jumlah</th>
                        <th>Nama Outlet</th>
                        <th>Ket</th>
                        <th>Qty Approval</th>
                        <th>Ket Principal Area</th>
                    </tr>
                </thead>
                <tbody>     
                    
                    <?php 
                    foreach ($get_pengajuan_detail->result() as $a) : ?>
                    <tr>
                        <td>
                            <?php 
                                if($a->qty_approval > 0 || $a->qty_approval == null){ ?>
                                    <center>
                                    <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                    </center>
                                <?php
                                }
                            ?>
                        </td>
                        <td><?= ($a->status == 4) ? "<span class='btn btn-danger btn-sm rounded'>$a->nama_status" : "$a->nama_status"  ?></td>
                        <td><?= ($a->status == 4) ? "<span class='btn btn-danger btn-sm rounded'>$a->deskripsi" : "$a->deskripsi" ?></td>
                        <td><?= $a->kodeprod ?></td>
                        <td><?= $a->namaprod ?></td>
                        <td><?= $a->batch_number ?></td>
                        <td><?= date('d M Y', strtotime($a->expired_date)); ?></td>
                        <td><?= $a->nama_alasan ?></td>
                        <td><?= $a->jumlah ?></td>
                        <td><?= $a->nama_outlet ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td>
                            <?= ($a->qty_approval == 0) ? "<span class='btn btn-danger btn-sm rounded'>$a->qty_approval</span>" : "$a->qty_approval" ?></td>
                        <!-- <td><?= ($a->qty_approval == 0) ? "<span class='btn btn-danger btn-sm rounded'>$a->qty_approval belum di verifikasi</span>" : "$a->keterangan_principal_area" ?></td> -->
                        <td><?= ($a->qty_approval == 0) ? "<span class='btn btn-danger btn-sm rounded'>$a->keterangan_principal_area</span>" : "$a->keterangan_principal_area" ?></td>
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
                <option value="">-- Pilih --</option>
                <option value="3">Data sesuai (verified)</option>
                <option value="4">Data tidak sesuai (not verified)</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="deskripsi">Deskripsi</label>
        </div>
        <div class="col-md-4">
            <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control"></textarea>
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
                if (!$verifikasi_at){ 
                    if ($this->session->userdata('id') == '297' || $this->session->userdata('id') == '588' || $this->session->userdata('id') == 857  || $this->session->userdata('id') == 1048) { ?>
                        <input type="submit" class="btn btn-submit-black" value="update status verifikasi">
                    <?php 
                    }
                }
            ?>
            
            <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-submit-black">Back to dashboard</a>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>

    <div class="row mt-5 ms-5">
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
                        if (!$verifikasi_at) { ?>

                            <?php if ($supp == '002' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015' || $supp == '001-herbana') { ?>                                
                                <a href="<?= base_url().'management_inventory/update_status_pengajuan/'.$signature.'/1'.'/'.$supp ?>" class="btn btn-submit">Proses DP</a>
                                <a href="<?= base_url().'management_inventory/update_status_pengajuan/'.$signature.'/3'.'/'.$supp ?>" class="btn btn-submit">Proses Principal</a>
                            <?php
                            }else{ ?>
                                <a href="<?= base_url().'management_inventory/update_status_pengajuan/'.$signature.'/3'.'/'.$supp ?>" class="btn btn-submit">Proses Principal</a>
                            <?php
                            } ?>

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

<script>
    $(document).ready(function () {
        $("#example2").DataTable({
            // "pageLength": 100,
            // // "ordering": false,
            // "aLengthMenu": [
            //     [10, 20, 50, -1],
            //     [10, 20, 50, "All"]
            // ],
            // "fixedHeader": {
            //     header: true,
            //     footer: true
            // }
            paging: false,
            scrollCollapse: true,
            scrollY: '500px'
        });
        $("#example").DataTable({
            "pageLength": 5,
            // "ordering": false,
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