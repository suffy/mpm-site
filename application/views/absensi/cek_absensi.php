<style>
    th {
        text-align: center;
        font-size: 12px;
        font-weight: bold;
    } 
    
    td {
        text-align: center;
        font-size: 12px;
        padding: 1px 5px 1px 5px;
    }

    #status_kehadiran {
        cursor : pointer;
    }

    /* .btn .btn-warning {
        border-radius: 10px;
        background-color: yellow;
        width: 10px;
    } */
    /* .btn .btn-warning .bx .bx-transfer-alt {
        width: 5px;
        height: 5px;
        font-size: 50px;
    } */
</style>
</div>

<div class="container-fluid">
    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('absensi/component/sidebar');?>
                <div class="col">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><?= $title ?></h3>
                        </div>
                    </div>

                    <!-- SESSION FLASH-->
                    <div class="row mt-2">
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
                    <!-- END SESSION FLASH -->

                    <!-- Search -->
                    <form action="<?= base_url($url); ?>" method="GET">
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="nama_program">Bulan Absensi</label>
                                    </div>
                                    <div class="col-md">
                                        <div class="input-group">
                                        <!-- <input type="month" class="form-control" name="bulan" required> -->
                                            <input type="month" class="form-control" name="bulan"
                                                value="<?= $this->input->get('bulan'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <input type="submit" value="Show Data" class="btn pastel-orange-btn">
                            </div>
                        </div>
                    </form>
                    <!-- end search -->

                    <!-- summary data -->
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <main class="main">
                                <section class="widget" style="border-radius: 20px;">
                                    <h4>Total hari kerja : <?= $total_hari_kerja;?> hari</h4>
                                </section>
                                <section class="widget" style="border-radius: 20px;">
                                    <h4>Terlambat : <?= $total_terlambat;?> hari</h4>
                                </section>
                                <section class="widget" style="border-radius: 20px;">
                                    <h4>Tidak Lengkap : <?= $total_tidak_lengkap;?> hari</h4>
                                </section>
                            </main>
                        </div>
                    </div>
                    <!-- end summary data -->

                    <br>

                    <!-- table data absensi -->

<form action="<?= base_url($url_update); ?>" method="post">
    <div class="card-block mt-1 mb-5">
        <div class="row">
            <div class="table-responsive">
                <table id="tabel-data" class="display table-striped table-bordered" style="width: 100%; text-align: center;">
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align: center;width: 120px;">Nama Karyawan</th>
                            <th rowspan="2" style="text-align: center;width: 50px;">Tanggal</th>
                            <th colspan="2" style="text-align: center;">Jadwal Kerja</th>
                            <th colspan="2" style="text-align: center;">Kehadiran Harian</th>
                            <th rowspan="2" style="text-align: center; width: 50px;">Total Jam Kerja</th>
                            <th rowspan="2" style="text-align: center;width: 100px;">Status Kehadiran</th>
                            <th rowspan="2" style="text-align: center;">Keterangan</th>
                            <th rowspan="2" style="text-align: center;width: 10px;">Del</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;width: 20px;">Masuk</th>
                            <th style="text-align: center;width: 20px;">Keluar</th>
                            <th style="text-align: center;width: 20px;">Jam Masuk</th>
                            <th style="text-align: center;width: 20px;">Jam Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_absensi->result() as $a) : ?>
                            <tr>
                                <td>
                                    <?php if ($a->name == NULL) { ?>
                                    <?= $nama_karyawan ;?>
                                    <?php }else{?>
                                    <?= $a->name ;?>
                                    <?php }?>
                                </td>
                                <td><input type="text" name="tanggal[]" class="form-control" value="<?= $a->tanggal; ?>" hidden>
                                    <?= date('d M y', strtotime($a->tanggal)); ?></td>
                                <td><?= $a->jam_masuk_kantor; ?></td> 
                                <td><?= $a->jam_keluar_kantor; ?></td>
                                <td><?= $a->actual_masuk; ?></td>
                                <td><?= $a->actual_keluar; ?></td>
                                <td><?= $a->total_jam_kerja; ?></td>
                                <td>
                                    <!-- <?= $a->flag_status_absensi; ?>
                                    <?= $a->status_hari; ?> -->
                                    <?php 
                                        // echo "flag_weekend : ".$flag_weekend;
                                        if ($flag_weekend == 1) {
                                            if ($a->status_hari == 0) { ?>
                                                <label style="font-size:14px; color: #006A67; ">Weekend</label>
                                            <?php
                                            }else{
                                                if ($a->flag_terlambat == 1) { ?>
                                                    <a href="<?= base_url() ?>absensi/edit_terlambat/<?= $a->id.'/'. $signature.'/'. $bulan ?>" onclick="return confirm('Yakin mengubah status ?')">    
                                                    <label class="status pending-finance" id="status_kehadiran" style="font-size:14px">Terlambat *</label>
                                                <?php 
                                                }elseif($a->flag_status_absensi == 1) {?>
                                                    <label class="status pending-finance" style="font-size:14px">Izin</label>
                                                    <a href="<?= base_url() ?>absensi/edit_terlambat/<?= $a->id.'/'. $signature.'/'. $bulan ?>" class='btn btn-warning bx bx-transfer-alt' style="font-size: 20px; font-weight: normal; color:white;"></a>
                                                <?php
                                                }else
                                                { 
                                                    if ($a->actual_masuk == "" || $a->actual_keluar == "" || $a->jam_keluar_kantor >= $a->actual_keluar) 
                                                    { ?>
                                                        <label class="status pending-finance" style="font-size:14px">tidak lengkap</label>
                                                    <?php
                                                    }else
                                                    { 
                                                        ?>
                                                            <label style="font-size:14px; color: #608BC1; ">complete</label>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }else{
                                            if ($a->status_hari == 0 || $a->status_hari == 6) { ?>
                                                <label style="font-size:14px; color: #006A67; ">Weekend</label>
                                            <?php
                                            }else{
                                                if ($a->flag_terlambat == 1) { ?>
                                                    <a href="<?= base_url() ?>absensi/edit_terlambat/<?= $a->id.'/'. $signature.'/'. $bulan ?>" onclick="return confirm('Yakin mengubah status ?')">    
                                                    <label class="status pending-finance" id="status_kehadiran" style="font-size:14px">Terlambat *</label>
                                                <?php 
                                                }elseif($a->flag_status_absensi == 1) {?>
                                                    <a href="<?= base_url() ?>absensi/edit_terlambat/<?= $a->id.'/'. $signature.'/'. $bulan ?>" onclick="return confirm('Yakin mengubah status ?')">
                                                    <label class="status pending-finance" id="status_kehadiran" style="font-size:14px">Izin *</label>
                                                    </a>
                                                
                                                <?php
                                                }else{
                                                    if ($a->actual_masuk == "" || $a->actual_keluar == "" ||  $a->jam_keluar_kantor >= $a->actual_keluar) 
                                                    { ?>
                                                        <a href="<?= base_url() ?>absensi/edit_terlambat/<?= $a->id.'/'. $signature.'/'. $bulan ?>" onclick="return confirm('Yakin mengubah status ?')">  
                                                        <label class="status pending-finance" id="status_kehadiran" style="font-size:14px">tidak lengkap *</label>
                                                    <?php
                                                    }else
                                                    { 
                                                        ?>
                                                            <label style="font-size:14px; color: #608BC1; ">complete</label>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }

                                        
                                    ?>
                                </td>
                                <td      style="text-align:left">                                                    
                                                    <?php                    
                                                    // echo "flag_weekend : ".$flag_weekend;
                                                    if ($flag_weekend == 1) 
                                                    {                                                                                
                                                        if ($a->status_hari == 0) 
                                                        { ?>
                                                            <label style="font-size:14px; color: #006A67; ">-</label>
                                                        <?php 
                                                        }else
                                                        {
                                                            if (($a->flag_terlambat == 1 || $a->flag_status_absensi == 1 || $a->jam_keluar_kantor >= $a->actual_keluar) && $a->userid == $this->session->userdata('id')) 
                                                            { 
                                                                if ($a->keterangan == "" || $a->keterangan == NULL) 
                                                                { ?>
                                                                    <input type="hidden" name="id_absensi[]" class="form-control" value="<?= $a->id; ?>">
                                                                    <textarea name="keterangan[]" class="form-control" cols="30" rows="2" placeholder="input disini "><?= $a->keterangan; ?></textarea>
                                                                <?php
                                                                }else{
                                                                    echo $a->keterangan;
                                                                }                       
                                                            } else 
                                                            {  
                                                                if ($a->actual_masuk == "" || $a->actual_keluar == "" || $a->jam_keluar_kantor >= $a->actual_keluar) 
                                                                { 
                                                                    if ($a->keterangan == "" || $a->keterangan == NULL) 
                                                                    { ?>
                                                                        <input type="hidden" name="id_absensi[]" class="form-control" value="<?= $a->id; ?>">
                                                                        <textarea name="keterangan[]" class="form-control" cols="30" rows="2" placeholder="Input disini"><?= $a->keterangan; ?></textarea>
                                                                    <?php
                                                                    }else{ ?>
                                                                            <label style="font-size:14px; color: #006A67;" ><?= $a->keterangan; ?></label>
                                                                    <?php
                                                                    }    
                                                                }else
                                                                {  
                                                                    if ($a->keterangan == "" || $a->keterangan == NULL) 
                                                                    { ?>
                                                                    <label style="font-size:14px; color: #006A67;" >-</label>
                                                                    <?php
                                                                    } else { ?>
                                                                    <label style="font-size:14px; color: #006A67;" ><?= $a->keterangan; ?></label>
                                                                    <?php
                                                                    }?>                                                      
                                                                <?php 
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        if ($a->status_hari == 0 || $a->status_hari == 6) 
                                                        { ?>
                                                            <label style="font-size:14px; color: #006A67; ">-</label>
                                                        <?php 
                                                        }else
                                                        {
                                                            if (($a->flag_terlambat == 1 || $a->flag_status_absensi == 1 || $a->jam_keluar_kantor >= $a->actual_keluar)  && $a->userid == $this->session->userdata('id')) 
                                                            { 
                                                                if ($a->keterangan == "" || $a->keterangan == NULL) 
                                                                { ?>
                                                                    <input type="hidden" name="id_absensi[]" class="form-control" value="<?= $a->id; ?>">
                                                                    <textarea name="keterangan[]" class="form-control" cols="30" rows="2" placeholder="input disini "><?= $a->keterangan; ?></textarea>
                                                                <?php
                                                                }else{?>
                                                                    <label style="font-size:14px; color: #006A67;" ><?= $a->keterangan; ?></label>
                                                                <?php
                                                                }                       
                                                            } else 
                                                            {  
                                                                if ($a->actual_masuk == "" || $a->actual_keluar == "" || $a->jam_keluar_kantor >= $a->actual_keluar) 
                                                                { 
                                                                    if ($a->keterangan == "" || $a->keterangan == NULL) 
                                                                    { ?>
                                                                        <input type="hidden" name="id_absensi[]" class="form-control" value="<?= $a->id; ?>">
                                                                        <textarea name="keterangan[]" class="form-control" cols="30" rows="2" placeholder="Input disini"><?= $a->keterangan; ?></textarea>
                                                                    <?php
                                                                    }else{ ?>
                                                                            <label style="font-size:14px; color: #006A67;" ><?= $a->keterangan; ?></label>
                                                                    <?php
                                                                    }    
                                                                }else{
                                                                    if ($a->keterangan == "" || $a->keterangan == NULL) 
                                                                    { ?>
                                                                    <label style="font-size:14px; color: #006A67;" >-</label>
                                                                    <?php
                                                                    } else { ?>
                                                                    <label style="font-size:14px; color: #006A67;" ><?= $a->keterangan; ?></label>
                                                                    <?php
                                                                    }?>  
                                                                <?php
                                                                } ?>                                                            
                                                            <?php 
                                                            } 
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <!-- <td> 
                                                    <?php
                                                    if ($flag_weekend == 1) {
                                                        // echo 'A';
                                                        if ($a->status_hari == 0) { 
                                                            ?>
                                                            <label style="font-size:14px; color: #006A67; "></label>
                                                            <?php
                                                        }else{
                                                            if ($a->actual_masuk == "" || $a->actual_keluar == "" || $a->flag_terlambat == 1 || $a->flag_status_absensi == 1) 
                                                            { ?>
                                                            <a href="<?= base_url() ?>absensi/edit_terlambat/<?= $a->id.'/'. $signature.'/'. $bulan ?>" class='btn btn-warning bx bx-transfer-alt' style="font-size: 20px; font-weight: normal; color:white;"></a>
                                                            <?php
                                                            }?>
                                                        <?php 
                                                        }
                                                    }else{
                                                        // echo $a->status_hari;
                                                        if (($a->status_hari == 0) || ($a->status_hari == 6)){
                                                            ?>
                                                            <label style="font-size:14px; color: #006A67;" class=""></label>
                                                            <?php
                                                        }else{
                                                            if ($a->actual_masuk == "" || $a->actual_keluar == "" || $a->flag_terlambat == 1 || $a->flag_status_absensi == 1) 
                                                            { ?>
                                                            <a href="<?= base_url() ?>absensi/edit_terlambat/<?= $a->id.'/'. $signature.'/'. $bulan ?>" class='btn btn-warning bx bx-transfer-alt' style="font-size: 20px; font-weight: normal; color:white;"></a>
                                                            <?php
                                                            }?>
                                                            <?php
                                                        }
                                                    }?>
                                                </td> -->
                                                <td>
                                                    <a href="<?= base_url() ?>absensi/delete_keterangan/<?= $a->id.'/'. $signature.'/'. $bulan ?>" class="delete-button" onclick="return confirm('Hapus keterangan ini ?')" style="width: 10px;height: 10px;padding: 10px 7px 10px 15px"></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

    <div class="row mb-5">
        <div class="col-lg-12 d-flex justify-content-center gap-3">
            <input type="text" name="userid_karyawan" value="<?= $userid_karyawan; ?>" hidden>
            <input type="text" name="signature" value="<?= $signature; ?>" hidden>
            <input type="text" name="bulan" value="<?= $this->input->get('bulan'); ?>" hidden>

            <button type="submit" class="btn btn-submit-black" style="border-radius: 20px; width: 200px;" onclick="convertTable()">Convert To Excel</button>

            <?php 
                echo "a->flag_status : ".$a->flag_status;
                if ($a->flag_status == 0 || $a->flag_status == 9) 
                {
                    if ($total_tidak_lengkap_and_keterangan_null == 0 && $total_terlambat_and_keterangan_null == 0) { ?>
                        <button type="submit" class="btn btn-submit-black" style="border-radius: 20px; width: 150px;" value="1" name="submit">request approval</button>
                        <?php    
                        }else{ ?>
                            <button type="submit" class="btn btn-submit-black" style="border-radius: 20px; width: 200px;" value="0" name="submit">Simpan Data Keterangan</button>
                        <?php
                        }?>                                
                    <?php }else{ ?>
                <?php } ?>

        </div>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-data').DataTable({
            "info": true,
            "paging": false,
        });
    });
</script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>