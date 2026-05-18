</div>

<div class="container-fluid">

    <?= form_open_multipart($url); ?>

    <div class="row">
        <div class="col-md-12">
            <table id="tabel-ho">
                <thead>
                    <tr>
                        <th colspan="12" class="text-center">Tipe Retur : <?= $tipe ?></th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-center" style="background-color: #F9E6CF;">data pengajuan dp</th>
                        <th colspan="2" class="text-center" style="background-color:rgb(251, 242, 230);">Principal Area</th>
                        <th colspan="2" class="text-center">Principal HO</th>
                    </tr>
                    <tr>
                        <th style="background-color: #F9E6CF;">Kodeprod</th>
                        <th style="background-color: #F9E6CF;">Namaprod</th>
                        <th style="background-color: #F9E6CF;">Batch</th>
                        <th style="background-color: #F9E6CF;">ED</th>
                        <th style="background-color: #F9E6CF;">Alasan</th>
                        <th style="background-color: #F9E6CF;">Nama Outlet</th>
                        <th style="background-color: #F9E6CF;">Qty DP</th>
                        <th style="background-color: rgb(251, 242, 230);">Qty Approval Area</th>
                        <th style="background-color: rgb(251, 242, 230);">Keterangan Approval Area</th>
                        <th>Qty Approval HO</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($get_pengajuan_detail->result() as $a) : ?>
                    <tr>
                        <td style="background-color: #F9E6CF;"><?= $a->kodeprod ?></td>
                        <td style="background-color: #F9E6CF;"><?= $a->namaprod ?></td>
                        <td style="background-color: #F9E6CF;"><?= $a->batch_number ?></td>
                        <td style="background-color: #F9E6CF;"><?= date('d M Y', strtotime($a->expired_date)); ?></td>
                        <td style="background-color: #F9E6CF;"><?= $a->nama_alasan ?></td>
                        <td style="background-color: #F9E6CF;"><?= $a->nama_outlet ?></td>
                        <td style="background-color:#F9E6CF;"><?= $a->jumlah ?></td>
                        <td style="background-color: rgb(251, 242, 230);">
                            <?= $a->qty_approval ?>
                            <input type="number" class="form-control" name="qty_approval[]"
                                value="<?= $a->qty_approval ?>" hidden>
                        </td>
                        <td style="background-color: rgb(251, 242, 230);">
                            <?= $a->keterangan_principal_area ?>
                        </td>
                        <td>
                            <input type="number" id="<?= $a->id; ?>" name="id_detail[]" class="<?= $a->id; ?>"
                                value="<?= $a->id; ?>" hidden>
                            <?php
                                if ($a->qty_approval_ho == null) { ?>
                            <input type="number" class="form-control" name="qty_approval_ho[]" value="<?= $a->qty_approval ?>"
                                min="0" max="<?= $a->qty_approval ?>" required>
                            <?php }else{ ?>
                            <input type="number" class="form-control" name="qty_approval_ho[]" value="<?= $a->qty_approval_ho ?>"
                                min="0" max="<?= $a->qty_approval ?>" required>
                            <?php
                                }
                            ?>
                        </td>
                        <td><textarea class="form-control" cols="50" name="keterangan[]"
                                placeholder="Masukan keterangan (Opsional)"><?= $a->keterangan_principal_ho; ?></textarea>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="status_principal_ho">Approve / Reject ?</label>
        </div>
        <div class="col-md-4">
            <select name="status_principal_ho" class="form-control" id="status_principal_ho" required>
                <option value="">Pilih</option>
                <option value="14">Approve</option>
                <option value="15">Reject</option>
            </select>
        </div>
    </div>

    <div class="row mt-2" hidden id="mydiv">
        <div class="col-md-2">
            <label for="status">Pilih Action</label>
        </div>
        <div class="col-md-4">
            <select name="status" id="status" class="form-control" required>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="catatan_principal_ho">Catatan</label>
        </div>
        <div class="col-md-4">
            <textarea name="catatan_principal_ho" id="catatan_principal_ho" cols="30" rows="5"
                class="form-control"></textarea>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="catatan">Upload File Pendukung (opsional)</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="file" name="file">
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

    <div class="row mt-2 mb-5">
        <div class="col-md-2">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <!-- <?= $principal_ho_at ?> -->
            <?php 
                if ($principal_ho_at) { ?>
            <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>
            <?php
                }else{ ?>
            <?php          
                        // echo "status_ho : ".$status_ho->row()->status_ho."<br>";
                        if ($status_ho->num_rows() > 0) {
                            
                            if ($status_ho->row()->status_ho == 1) { ?>
            <input type="submit" class="btn btn-submit-black" value="Submit Data">
            <?php
                            }
                        }else{ 

                            if ($this->session->userdata('id') == 588 || $this->session->userdata('id') == 857 || $this->session->userdata('id') == 1048) { ?>
            <input type="submit" class="btn btn-submit-black" value="Submit Data">
            <?php
                            }                        
                        }
                    ?>
            <?php
                } ?>

            <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-submit-black">Back to
                dashboard</a>

        </div>
    </div>
</div>


<?= form_close();?>

<br><br>
<script>
    $(document).ready(function () {
        // $("#test").DataTable({
        //     "paging": false,
        //     "scrollCollapse": true,
        //     "scrollY": '500px',
        //     "aLengthMenu": [
        //         [10, 20, 50, -1],
        //         [10, 20, 50, "All"]
        //     ]
        // });

        $('#tabel-ho').DataTable({
            // "pageLength": 10,
            // "ordering": true,
            // "order": [0, 'desc'],
            // "aLengthMenu": [
            //     [10, 20, 50, -1],
            //     [10, 20, 50, "All"]
            // ],
            // scrollX: true,
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
    // $.ajax({
    //     type: 'POST',
    //     url: "<?php echo base_url('database_afiliasi/kodeprod') ?>",
    //     data: 'supp=<?= $supp; ?>',
    //     success: function (hasil_kodeprod) {
    //         $("select[name = kodeprod]").html(hasil_kodeprod);
    //     }
    // });

    $("select[name = status_principal_ho]").on("change", function () {
        // var status_principal_ho_terpilih = $("option:selected", this).attr("status_principal_ho");
        var status_principal_ho_terpilih = document.getElementById('status_principal_ho').value;
        console.log(status_principal_ho_terpilih);
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('management_inventory/action_pengajuan_retur') ?>",
            data: {
                'status_principal_ho_terpilih': status_principal_ho_terpilih,
                'supp': '<?= $supp; ?>',
                'signature': '<?= $signature; ?>',
            },
            success: function (hasil_action) {
                $("select[name = status]").html(hasil_action);
            }
        });

        let element = document.getElementById("mydiv");
        if (status_principal_ho_terpilih ==
            '14') { //jika appprove tampil action kirim barang atau pemusnahan
            // document.getElementById("file").required = true;
            element.removeAttribute("hidden");
        } else {
            element.setAttribute("hidden", "hidden");
            document.getElementById('status').removeAttribute('required');
        }

    });
</script>