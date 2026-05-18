<div class="container-fluid">
    <div class="row">
        <div class="accordion" id="accordionTwo">
            <div class="card">
                <div class="card-header" style="background-color: #fff;" id="headingOne">
                    <h5 class="mb-0">
                        <span class="btn btn-submit-cream" style="cursor: pointer; font-size: 14px; border-radius: 5px; border: none; padding: 10px 10px 0px 10px;" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">Tambah product via form | Tipe retur : <strong><?= $tipe ?> </strong> </span>
                    </h5>
                </div>

                <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionTwo"
                    style="width:100%; overflow:hidden;">
                    <div class="card-body">

                        <?= form_open($url); ?>
                        <div class="container-fluid">
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="kodeprod" class="form-label">Kodeproduk</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="kodeprod" id="id_kodeprod" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="batch_number" class="form-label">batch number</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="batch_number" name="batch_number"
                                        required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="satuan" class="form-label">Satuan</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="satuan" id="satuan" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="ed" class="form-label">Expired Date</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" id="ed" name="ed" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="nama_outlet" class="form-label">Nama Outlet</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="nama_outlet" name="nama_outlet"
                                        required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="alasan_retur" class="form-label">alasan retur</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="alasan_retur" id="alasan_retur" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                </div>
                                <div class="col-md-4">
                                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"
                                        placeholder="Gunakan kolom ini agar pengajuan retur anda CLEAR dan mudah dipahami"></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <input type="hidden" name="id_pengajuan" value="<?= $id_pengajuan ?>">
                                    <input type="hidden" name="signature" value="<?= $signature ?>">
                                    <input type="hidden" name="supp" value="<?= $supp ?>">
                                </div>
                                <div class="col-md-4">
                                    <?php
                                        if (!$tanggal_pengajuan) { ?>
                                    <button type="submit" class="btn btn-submit-red">Add Product</button>
                                    <?php 
                                        }else{ ?>
                                    <button type="submit" class="btn btn-submit-black" disabled>permintaan sudah
                                        diajukan</button>
                                    <?php
                                        }
                                    ?>
                                    <!-- <a href="<?= base_url().'management_inventory/' ?>" class="btn btn-submit-black">back</a> -->
                                </div>
                            </div>
                        </div>
                        <?= form_close();?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="accordion" id="accordionTwo">
            <div class="card">
                <div class="card-header" style="background-color: #fff;" id="headingOne">
                    <h5 class="mb-0">
                        <a href="<?= base_url().'management_inventory/export_template_pengajuan_retur' ?>" style="font-size: 12px; font-weight: bold; text-decoration: none;">
                        <span class="btn btn-submit-cream" style="cursor: pointer; font-size: 14px; border-radius: 5px; border: none; padding: 10px 10px 0px 10px;" data-toggle="collapse" data-target="#collapseTwox" aria-expanded="true" aria-controls="collapseTwo">Tambah product via import excel : download template disini</span></a>
                        
                    </h5>
                </div>

                <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionTwo"
                    style="width:100%; overflow:hidden;">
                    <div class="card-body">

                        <?= form_open_multipart($url_import); ?>
                        <div class="container-fluid">
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="file_import" class="form-label">File Import</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" class="form-control" name="file" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <input type="hidden" name="signature" value="<?= $signature ?>">
                                    <input type="hidden" name="supp" value="<?= $supp ?>">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <?php
                                        if (!$tanggal_pengajuan) { ?>
                                    <button type="submit" class="btn btn-submit-red">Import</button>
                                    <?php 
                                        }else{ ?>
                                    <button type="submit" class="btn btn-submit-black" disabled>permintaan sudah
                                        diajukan</button>
                                    <?php
                                        }
                                    ?>
                                    <!-- <a href="<?= base_url().'management_inventory/' ?>" class="btn btn-submit-black">back to dashboard</a> -->
                                </div>
                            </div>

                        </div>
                        <?= form_close();?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid">
    <div class="row mt-5">
        <div class="col-md-12">
            <?php 
                // echo "aaaaaaa";
                if($this->session->flashdata('pesan')){ ?>
            <div class="alert alert-danger" role="alert">
                <center><strong>Informasi ! Harap baca pesan dibawah ini</strong></center><br>
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
            <!-- Detail Product -->
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="detail">
                <thead>
                    <tr>
                        <th class="text-center">Verifikasi</th>
                        <th class="text-center">Deskripsi</th>
                        <th class="text-center">Kodeprod</th>
                        <th class="text-center">Batch</th>
                        <th class="text-center" style="width: 100px">ED</th>
                        <th class="text-center">Qty Ajuan</th>
                        <th class="text-center">Qty Approval</th>
                        <!-- <th class="text-center">Satuan</th> -->
                        <th class="text-center" style="width: 100px">Nama Outlet</th>
                        <th class="text-center">Alasan</th>
                        <th class="text-center" style="width: 100px">Ket</th>
                        <th class="text-center"> 
                            <?php 
                            if (!$tanggal_pengajuan) { ?>
                            <a href="<?= base_url().'management_inventory/delete_detail/'.$signature.'/'.$supp ?>"
                                class="btn btn-danger btn-sm rounded"
                                onclick="return confirm('Anda yakin menghapus semua produk ?')">Delete All</a>
                            <?php
                            }else{ ?>
                            <label class="btn btn-secondary btn-sm btn-outline rounded">Delete All</label>
                            <?php
                            }?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($get_pengajuan_detail->result() as $a) : ?>
                    <tr>
                        <td>
                            <?php 
                                if($a->status == 4) { ?>
                            <p style="color: white; background-color: red;"><?= $a->nama_status ?></p>
                            <?php
                                }elseif($a->status == null){ ?>
                            <p><i>pending verifikasi</i></p>
                            <?php
                                }elseif($a->status == 3){ ?>
                            <p style="color: white; background-color: green;"><?= $a->nama_status ?></p>
                            <?php
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                                if ($a->status == 4) { ?>
                            <p style="color: white; background-color: red;">
                                <?= $a->deskripsi ?>
                            </p>
                            <?php
                                }elseif($a->status == null){ ?>
                            <p><i>pending verifikasi</i></p>
                            <?php
                                }elseif($a->status == 3){ ?>
                            <p style="color: white; background-color: green;"><?= $a->deskripsi ?></p>
                            <?php
                                }
                            ?>
                        </td>
                        <td><?= $a->kodeprod." - ".$a->namaprod ?></td>
                        <td><?= $a->batch_number ?></td>
                        <td><?= date('d M Y', strtotime($a->expired_date)); ?></td>
                        <td class="text-center"><?= $a->jumlah." ".$a->satuan ?></td>
                        <td class="text-center"><?= $a->qty_approval ?></td>
                        <!-- <td>
                            <?php 
                                if ($a->satuan) {
                                    echo $a->satuan; 
                                }else{ ?>
                            <label style="background-color: red;">
                                <font color="white"><i>&nbsp; blank &nbsp;</i> </font>
                            </label>
                            <?php
                                }
                            ?>
                        </td> -->
                        <td><?= $a->nama_outlet ?></td>
                        <td class="text-center"><?= $a->nama_alasan ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td class="text-center">
                            <?php 
                                if (!$tanggal_pengajuan) { ?>
                            
                            <a href="<?= base_url().'management_inventory/delete_product/'.$a->signature.'/'.$supp.'/'.$signature ?>" onclick="return confirm('Ingin menghapus baris ini ?')" class="delete-button" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>

                            <?php
                                }else{ ?>
                            <label class="btn btn-secondary btn-sm btn-outline">X</label>
                            <?php
                                }
                            ?>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <h4>Preview Data Pengajuan</h4>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <p>Count Produk</p>
        </div>
        <div class="col-md-4">
            <p>: <?= $count_kodeprod ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <p>Sum QTY Pengajuan</p>
        </div>
        <div class="col-md-4">
            <p>: <?= $sum_qty_pengajuan ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <p>Value RBP</p>
        </div>
        <div class="col-md-4">
            <p>: <?= number_format($value_rbp) ?></p>
        </div>
    </div>
    <hr>

    <?php echo form_open($url_pengajuan); ?>
    <div class="row mb-5">
        
        <div class="col-md-12">
            <h4>Mohon Perhatiannya !</h4>

            <div class="row mt-2">
                <div class="col-md-12" style="margin-left: 20px!important;">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" required>
                    <label class="form-check-label" style="font-weight: bold!important;" for="defaultCheck1">
                        Pastikan Batch Number dan ED yang diinput sesuai fisik (tidak sampling)
                    </label>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12" style="margin-left: 20px!important;">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck2" required>
                    <label class="form-check-label" style="font-weight: bold!important;" for="defaultCheck2">
                        Batch Number atau ED tidak sesuai fisik akan di tolak pabrik
                    </label>
                </div>
            </div><br>Jika sudah ok, klik Button di bawah ini :
        </div>

        <div class="col-md-12 mt-2">
            <div class="">
                <div class="">

                    <?php
                        if (!$tanggal_pengajuan) { ?>
                    <input type="hidden" name="signature" value="<?= $signature ?>">
                    <input type="hidden" name="supp" value="<?= $supp ?>">
                    <input type="hidden" name="tipe" value="<?= $tipe ?>">
                    <input type="submit" value="Submit Pengajuan Retur" class="btn btn-submit-red" id="btnKirim"
                        onclick="return button()">
                    <!-- <a href="<?= base_url().'management_inventory/' ?>" class="btn btn-submit-black">back</a> -->
                    <?php 
                        }else{ ?>
                    <button type="submit" class="btn btn-dark" disabled>permintaan sudah diajukan</button>
                    <!-- <a href="<?= base_url().'management_inventory/' ?>" class="btn btn-submit-black">back to dashboard</a> -->
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <?= form_close();?>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#detail').DataTable({
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
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js">
</script>

<script>
    console.log(111)
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('management_inventory/kodeprod'); ?>",
        data: 'supp=<?= $supp; ?>',
        success: function (hasil_kodeprod) {
            console.log(hasil_kodeprod);
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });

    $("select[name = kodeprod]").on("change", function () {
        var kodeprod_terpilih = $("option:selected", this).attr("id_kodeprod");
        console.log(kodeprod_terpilih);
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('management_inventory/satuan') ?>",
            data: 'kodeprod=' + kodeprod_terpilih,
            success: function (hasil_satuan) {
                // var_dump(hasil_satuan);
                $("select[name = satuan]").html(hasil_satuan);
            }
        });
    });
</script>

<script>
    // console.log('teststrukturx')
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('management_inventory/master_alasan'); ?>",
        data: 
        {
            supp : '<?= $supp; ?>',
            tipe : '<?= $tipe; ?>'
        },
        success: function (hasil_alasan) {
            console.log(hasil_alasan);
            $("select[name = alasan_retur]").html(hasil_alasan);
        }
    });
</script>

<script>
    function button() {
        $("#btnBack").hide();
    }

    $(document).ready(function () {
        $("#btnLoading").hide();
    });
</script>