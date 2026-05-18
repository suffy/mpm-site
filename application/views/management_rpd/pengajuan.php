<style>
    textarea, input {
        padding: 10px;
        max-width: 100%;
        width:100%;
        line-height: 1.5;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-shadow: 1px 1px 1px #999;
    }
</style>
</div>

<div class="container-fluid">

<div class="az-content">
    <div class="container-fluid">
        <?= $this->load->view('management_rpd/component/sidebar'); ?>
        
        <div class="pd-lg-l-40 col ml-6">

            <div class="row mt-1">
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
            <!-- Pengajuan -->
            <?php echo form_open_multipart($url); ?>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h3><?= $title ?></h3>
                </div>
            </div>

            <div class="row">
                <div class="row mt-3">
                    <div class="col-md-2">
                        <label for="nama_program">Pelaksana</label>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="pelaksana" value="<?= $name ?>" readonly>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">
                        <label for="nama_program">Jabatan / Job Level</label>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="jabatan" value="<?= $jabatan. ' / ' .$level_karyawan; ?>" readonly>
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

                <div class="row mt-4 mb-2">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-submit-black">Simpan dan Lanjut ke Pengisian Aktivitas</button>
                    </div>
                </div>
            </div>
            <?= form_close();?>
        </div>
    </div>
</div>

<div class="row mt-5 ms-5">
    <div class="col-md-12 az-content-label text-center">
        History Perjalanan Dinas
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <table id="example" style="width: 100%;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 150px;" >Detail RPD</th>
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
                    <td><strong><?= number_format($a->total_biaya) ?></strong></td>
                    <td class="px-1"><?= $a->maksud_perjalanan_dinas; ?></td>
                    
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
                        <a href="<?= base_url('management_rpd/pengajuan_delete_soft/'.$a->signature) ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: white"></i> del</a>
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
    <br>

<script>
    $(document).ready(function () {
        $("#example").DataTable({
            // "scrollX": true,
            // "scrollCollapse" : true,
            "pageLength": 10,
            "ordering": true,
            "order": [9, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<!-- <script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
</script> -->