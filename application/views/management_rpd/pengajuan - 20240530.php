</div>

<div class="container-fluid">

<div class="az-content">
    <div class="container-fluid">
        <div class="az-content-left az-content-left-components">
            <div class="component-item">
                <label>Pengajuan</label>
                <nav class="nav flex-column">
                    <a href="<?= base_url().'management_rpd/pengajuan' ?>" class="nav-link-new" target=_blank>Pengajuan RPD</a>
                </nav>
                <label>Master Data</label>
                <nav class="nav flex-column">
                    <!-- <a href="#master-team" class="nav-link-new">Master Team</a> -->
                    <a href="<?= base_url().'management_rpd/master_data' ?>" class="nav-link-new" target=_blank>Master Approval</a>
                </nav>
            </div>
        </div>        
        
        <div class="pd-lg-l-40">

        <!-- event -->
        <?php echo form_open_multipart($url); ?>
        <div class="row">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
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
            <label for="nama_program">Pelaksana</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="pelaksana" value="<?= $name ?>" readonly>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Jabatan / Job Level</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="jabatan" value="<?= $jabatan. ' / ' .$level_karyawan; ?>" readonly>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Maksud Perjalanan Dinas</label>
        </div>
        <div class="col-md-5">
            <textarea name="maksud_perjalanan_dinas" class="form-control" cols="30" rows="5" required></textarea>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Periode Perdin</label>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input class="form-control" type="date" name="tanggal_mulai" required />
                <input class="form-control" type="date" name="tanggal_akhir" required />
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Radius Perjalanan</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="text" name="radius_perjalanan" placeholder="Contoh : 100 KM" />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Attachment Map</label> 
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control" id="files" name="attachment_radius_perjalanan">
        </div>
    </div>

    <hr>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Tanggal Berangkat</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="datetime-local" name="tanggal_berangkat" required />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Tempat Berangkat</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="text" name="tempat_berangkat" required />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Tanggal Tiba</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="datetime-local" name="tanggal_tiba" required />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Tempat Tiba</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="text" name="tempat_tiba" required />
        </div>
    </div>


    <div class="row mt-2 mb-2">
        <div class="col-md-2"></div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-submit-black">Simpan dan Lanjut ke Pengisian Aktivitas</button>
        </div>
    </div>
    
    <?= form_close();?>
    

    <div class="row mt-5 ms-5">
        <div class="col-md-12 az-content-label text-center">
            History Perjalanan Dinas
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 150px;">Detail RPD</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Pelaksana</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Total Biaya</th>
                        <th class="text-center" style="width: 200px;">Maksud RPD</th>
                        <th class="text-center">realisasi | akomodasi</th>
                        <th class="text-center">export</th>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th class="text-center">createdAt</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan->result() as $a) : ?>
                    <tr>
                        <td align="center">
                            <?php 
                                if ($a->no_rpd) { ?>
                                    <a href="<?= base_url().'management_rpd/aktivitas/'.$a->signature ?>" class="btn btn-submit-black"><?= $a->no_rpd; ?></a>
                                <?php
                                }else{ ?>
                                <div class="btn-group">
                                    <a href="<?= base_url().'management_rpd/aktivitas/'.$a->signature ?>" class="btn btn-submit-black">Belum di ajukan</a>
                                </div>
                                <?php
                                }
                            ?>
                        </td>
                        <td><?= $a->nama_status; ?></td>
                        <td><?= $a->pelaksana; ?></td>
                        <td><?= $a->tanggal_mulai.' - '.$a->tanggal_akhir; ?></td>
                        <!-- <td><?= $a->tempat_tiba.' at '.$a->tanggal_tiba; ?></td> -->
                        <td><strong><?= number_format($a->total_biaya) ?></strong></td>
                        <td><?= $a->maksud_perjalanan_dinas; ?></td>
                        
                        <td>
                            <div class="btn-group">
                                <?php 
                                    if ($a->status_realisasi == 1) {
                                        $params_realisasi = "realisasi";
                                        ?>
                                    <a href="<?= base_url().'management_rpd/realisasi/'.$a->signature ?>" class="btn btn-submit-black" target="_blank"><?= $params_realisasi ?></a>
                                    <?php
                                    }else{
                                        $params_realisasi = "realisasi";
                                        ?>
                                    <a href="<?= base_url().'management_rpd/realisasi/'.$a->signature ?>" class="btn btn-submit-black" target="_blank"><?= $params_realisasi ?></a>
                                    <?php
                                    }
                                ?>
                                <a href="<?= base_url().'management_rpd/input_akomodasi/'.$a->signature ?>" class="btn btn-submit-black" target="_blank">akomodasi</a>
                            
                            </div>
                            
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= base_url().'management_rpd/generate_pdf/'.$a->signature ?>" class="btn btn-submit-black" target="_blank">pdf</a>
                                <a href="<?= base_url().'management_rpd/generate_excel/'.$a->signature ?>" class="btn btn-submit-black" target="_blank">excel</a>
                            </div>
                        </td>
                        <td align="center">
                            <a href="<?= base_url('management_rpd/pengajuan_delete_soft/'.$a->signature) ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: white"></i> del</a>
                        </td>
                        <td>
                            <?= $a->created_at ?>
                        </td>
                       
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>

        </div>
    </div>
</div>
        <?php echo form_close(); ?>
        <!-- end event -->
</div>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "scrollX": true,
            "pageLength": 10,
            "ordering": true,
            "order": [9, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });
</script>
<script>
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

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('kpi/master_brand') ?>',
        data: '',
        success: function(result) {
            $("select[name = brand]").html(result);
        }
    });

    function keyupFunction(){
        var biaya = document.getElementById('biaya').value;
        var omzet = document.getElementById('omzet').value;
        var cost_ratio = document.getElementById('cost_ratio').value;
        var result = biaya / omzet;
        
        document.getElementById("cost_ratio").value = result;        
    }
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>