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

                    <br><br>

                    
                    <!-- table data absensi -->
                    

                    <button type="submit" class="btn btn-submit-black" onclick="convertTable()">Convert To Excel</button>


                    <div class="row">
                        <div class="col-md-12">
                            <table id="tabel-data" class="display table-striped table-bordered" style="width: 100%; text-align: center;">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center;width: 120px;">Nama Karyawan</th>
                                        <th rowspan="2" style="text-align: center;width: 50px;">Tanggal</th>
                                        <th colspan="2" style="text-align: center;">Jadwal Kerja</th>
                                        <th colspan="2" style="text-align: center;">Kehadiran Harian</th>
                                        <th rowspan="2" style="text-align: center; width: 100px;">Total Jam Kerja</th>
                                        <th rowspan="2" style="text-align: center;width: 120px;">Status Kehadiran</th>
                                        </th>
                                        <th rowspan="2" style="text-align: center;">Keterangan</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;width: 20px;">Masuk</th>
                                        <th style="text-align: center;width: 20px;">Keluar</th>
                                        <th style="text-align: center;width: 100px;">Jam Masuk</th>
                                        <th style="text-align: center;width: 100px;">Jam Keluar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_absensi->result() as $a) : ?>
                                    <tr>
                                        <td><?= $a->name;?></td>
                                        <td><?= $a->tanggal; ?></td>
                                        <td><?= $a->jam_masuk_kantor; ?></td>
                                        <td><?= $a->jam_keluar_kantor; ?></td>
                                        <td><?= $a->actual_masuk; ?></td>
                                        <td><?= $a->actual_keluar; ?></td>
                                        <td><?= $a->total_jam_kerja; ?></td>
                                        <td>
                                            <?php 
                                                // echo "flag_weekend : ".$flag_weekend;
                                                if ($flag_weekend == 1) {
                                                    if ($a->status_hari == 0) { ?>
                                                        <label style="font-size:14px; color: #006A67; ">Weekend</label>
                                                    <?php
                                                    }else{
                                                        if ($a->flag_terlambat == 1) { ?>
                                                            <label class="status pending-finance" style="font-size:14px">Terlambat</label>
                                                        <?php }else
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
                                                            <label class="status pending-finance" style="font-size:14px">Terlambat</label>
                                                        <?php }else
                                                        { 
                                                            if ($a->actual_masuk == "" || $a->actual_keluar == "" || $a->jam_keluar_kantor >= $a->actual_keluar ) 
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
                                                }

                                                
                                            ?>
                                        </td>
                                        <td style="text-align: left;">                                                    
                                            <?php
                                            if ($a->keterangan) { ?>
                                                <label style="font-size:14px; color: #006A67; "><?= $a->keterangan; ?></label>
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
                    <!-- end table -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-absensi').DataTable({
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

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