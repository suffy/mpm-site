</div>

<div class="container-fluid">

<?php if(!$signature){ ?>
    <div class="title-square">Silahkan pilih event yang ingin anda verifikasi pada tabel di bawah ini</div>
<?php
}else{ ?>

<?php echo form_open_multipart($url); ?>

<div class="row mt-1">
    <div class="col-md-12 az-content-label">
        Verifikasi Event
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
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
    <div class="col-md-2">
        <label for="nama_program">No Pelaporan</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= $no_pelaporan_event ?>" readonly>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Status Pelaporan</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= $nama_status ?>" readonly>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">User Pelaksana</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= $name. ' - '.$jabatan. ' - '.$email ?>" readonly>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12 az-content-label">
        Informasi Pelaksanaan
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Nama Event</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= $nama_event ?>" readonly>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Periode</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= $event_from ?> - <?= $event_to ?>" readonly>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Lokasi</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= $lokasi_event ?>" readonly>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Omzet</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= number_format($omzet,0) ?>" readonly>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Biaya</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= number_format($biaya,0) ?>" readonly>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Cost Ratio</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= $cost_ratio ?>" readonly>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Crowd</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= number_format($crowd,0) ?>" readonly>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="nama_program">Brand</label>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" value="<?= $brand ?>" readonly>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12 az-content-label">
        Attachment
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-2">
        <label for="nama_program">Proposal Referensi</label>
    </div>
    <div class="col-md-5">
        <?php 
            if ($attach_1) { ?>
                <a href="<?= base_url() ?>assets/uploads/kpi/<?= $attach_1 ?>" class="btn btn-submit-black" target="_blank">
                    <?= $attach_1 ?>
                </a>  
            <?php
            }
        ?>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-2">
        <label for="nama_program">Foto Kegiatan</label>
    </div>
    <div class="col-md-5">
        <?php 
            if ($attach_3) { ?>
                <a href="<?= base_url() ?>assets/uploads/kpi/<?= $attach_3 ?>" class="btn btn-submit-black" target="_blank">
                    <?= $attach_3 ?>
                </a>  
            <?php
            }
        ?>
    </div>
</div>

<div class="row mt-4 mb-5">
    <div class="col-md-2">
        <label for="nama_program">KPI Event</label>
    </div>
    <div class="col-md-5">
        <?php 
            if ($attach_2) { ?>
                <a href="<?= base_url() ?>assets/uploads/kpi/<?= $attach_2 ?>" class="btn btn-submit-black" target="_blank">
                    <?= $attach_2 ?>
                </a>  
            <?php
            }
        ?>
    </div>
</div>

<hr>

<div class="row mt-5">
    <div class="col-md-12 az-content-label">
        Proses Verifikasi SPO
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="approval">Approve / Reject ?</label>
    </div>
    <div class="col-md-5">
        <input type="hidden" name="signature" value="<?= $signature ?>">
        <select name="approval" class="form-control" required>
            <option value="">-- Pilih -- </option>
            <option value="2">Approve</option>
            <option value="0">Reject</option>
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="status_supervisi">Apakah anda ikut dalam supervisi langsung ?</label>
    </div>
    <div class="col-md-5">
        <select name="status_supervisi" id="status_supervisi" class="form-control" required>
            <option value="">-- Pilih -- </option>
            <option value="1">Ya, Saya ikut</option>
            <option value="0">Tidak</option>
        </select>
    </div>
</div>

<div class="row mt-3" hidden id="div_header">
    <div class="col-md-12">
        <p class="mt-2 mb-5"><strong>Silahkan isi data supervisi anda</strong></p>
    </div>
</div>

<div class="row mt-2" hidden id="div_foto_supervisi">
    <div class="col-md-2">
        <label for="foto_supervisi">Foto</label>
    </div>
    <div class="col-md-5">
        <input type="file" class="form-control mb-2" id = "foto_supervisi" name="foto_supervisi">
    </div>
</div>

<div class="row mt-3" hidden id="div_keterangan_supervisi">
    <div class="col-md-2">
        <label for="keterangan_supervisi">Keterangan</label>
    </div>
    <div class="col-md-5">
        <textarea name="keterangan_supervisi" id="keterangan_supervisi" class="form-control" rows="3" cols="3"></textarea>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-2">
        <label for="nama_program"></label>
    </div>
    <div class="col-md-5">
        <input type="submit" value="Submit Verifikasi" class="btn btn-submit-black">
        <a href="<?= base_url() ?>kpi/manage_activity" class="btn btn-submit-black">Back</a>
    </div>
</div>

<?php }?>

<div class="row mt-5">
    <div class="col-md-12">
        <table id="table-event">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
                    <th>Status Supervisi</th>
                    <th>Foto Supervisi</th>
                    <th>Keterangan Supervisi</th>
                    <th>NoEvent</th>
                    <th>Pelaksana</th>
                    <th>NamaEvent</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Biaya</th>
                    <th>Value</th>
                    <th>Ratio</th>
                    <th>Crowd</th>
                    <th>Brand</th>
                </tr>
            </thead>
            <tbody>     
                <?php 
                $no = 1;
                foreach ($get_event->result() as $a) : ?>
                <tr>
                    <td align ="center"><?= $no++ ?></td>
                    <td>
                        <a href="<?= base_url().'kpi/verifikasi_event/'.$a->signature ?>" class ="btn btn-submit-black"><?= $a->nama_status ?></a>
                    </td>
                    <td>
                        <?php 
                            if ($a->status_supervisi_spo == 1) {
                                echo "Ya";
                            }else{
                                echo "Belum diverifikasi";
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            if ($a->foto_supervisi_spo) { ?>
                                <a href="<?= base_url().'assets/uploads/kpi/'.$a->foto_supervisi_spo ?>" class ="btn btn-submit-black"><?= $a->foto_supervisi_spo ?></a>
                            <?php }else{
                                echo "tidak ada foto";
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            if($a->keterangan_supervisi_spo){
                                echo $a->keterangan_supervisi_spo;
                            }else{
                                echo "tidak ada keterangan";
                            }
                        ?>
                    </td>
                    <td><?= $a->no_pelaporan_event ?></td>
                    <td><?= $a->name ?></td>
                    <td><?= $a->nama_event ?></td>
                    <td><?= $a->event_from.' - '.$a->event_to ?></td>
                    <td><?= $a->lokasi_event ?></td>
                    <td><?= number_format($a->biaya) ?></td>
                    <td><?= number_format($a->omzet) ?></td>
                    <td><?= $a->cost_ratio ?></td>
                    <td><?= $a->crowd ?></td>
                    <td><?= $a->brand ?></td>
                </tr>
                <?php endforeach; ?>   
            </tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function () 
    {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#table-event').DataTable(
        {
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $(document).ready(function () 
    {
        $('#table-event-dashboard').DataTable(
        {
            "pageLength": 10,
            "ordering": true,
            "order": [9, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });

    });

    $(document).ready(function () 
    {
        $('#table-event-dashboard-by-user').DataTable(
        {
            "pageLength": 10,
            "ordering": true,
            "order": [9, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });

    });

    $(document).ready(function () 
    {
        $("#btnLoadingUserEvent").hide();
        $('#table-event-user').DataTable(
        {
            "pageLength": 10,
            "ordering": true,
            "order": [1, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $(document).ready(function () 
    {
        $("#btnLoadingUserEventPicApproval").hide();
        $('#table-event-pic-approval').DataTable(
        {
            "pageLength": 10,
            "ordering": true,
            "order": [1, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_user_mpm') ?>',
        data: '',
        success: function(result) {
            $("select[name = user_event]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('kpi/master_user_event') ?>',
        data: '',
        success: function(result) {
            $("select[name = user_event_terdaftar]").html(result);
            $("select[name = pic_approval]").html(result);
        }
    });

    function keyupFunction()
    {
        var biaya = document.getElementById('biaya').value;
        var omzet = document.getElementById('omzet').value;
        var cost_ratio = document.getElementById('cost_ratio').value;
        var result = biaya / omzet;
        
        document.getElementById("cost_ratio").value = result;        
    }
</script>

<script>    
    $("select[name = status_supervisi]").on("change", function() {
        var status_supervisi_terpilih = document.getElementById('status_supervisi').value;
        let div_foto_supervisi = document.getElementById("div_foto_supervisi");
        let div_keterangan_supervisi = document.getElementById("div_keterangan_supervisi");
        let div_header = document.getElementById("div_header");
        console.log(status_supervisi_terpilih);
        if (status_supervisi_terpilih == 1) { //jika ya
            document.getElementById("foto_supervisi").required = true;
            document.getElementById("keterangan_supervisi").required = true;
            div_foto_supervisi.removeAttribute("hidden");
            div_keterangan_supervisi.removeAttribute("hidden");
            div_header.removeAttribute("hidden");
        }else{
            div_foto_supervisi.setAttribute("hidden", "hidden");
            div_keterangan_supervisi.setAttribute("hidden", "hidden");
            div_header.setAttribute("hidden", "hidden");
            document.getElementById('foto_supervisi').removeAttribute('required');
            document.getElementById('keterangan_supervisi').removeAttribute('required');
        }
    });
</script>