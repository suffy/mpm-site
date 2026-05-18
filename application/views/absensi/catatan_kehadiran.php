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
                        <div class="col-8">
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
                                            <input type="month" class="form-control" name="bulan" required>
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

                
                    <!-- table data absensi -->
                    <div class="row mt-5 mb-3">
                        <div class="col-md-12 az-content-label text-center">
                            Log Absensi
                        </div>
                    </div>

                    <div class="row">
                            <div class="table-responsive">
                                <table id="tabel-data" class="display table-striped table-bordered"
                                    style="width: 100%; text-align: center;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Nama Karyawan</th>
                                            <th class="text-center">Bulan</th>
                                            <th class="text-center">Total Hari Kerja</th>
                                            <th class="text-center">Hadir</th>
                                            <th class="text-center">Terlambat</th>
                                            <th class="text-center">Tidak Lengkap</th>
                                            <th class="text-center">Keterangan Atasan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($get_absensi->result() as $key) { ?>
                                        <tr>
                                            <td>
                                                <?php if ($key->flag_status == '2' || $key->flag_status == '3') { ?>
                                                <a class="btn btn-submit status pending-rilis-po"
                                                    style="font-size:14px">
                                                    <?= $key->status ?></a>
                                                <?php } else { ?><a class="btn btn-submit status pending-finance"
                                                    style="font-size:14px">
                                                    <?= $key->status ?></a>
                                                <?php } ?>
                                            </td>
                                            <td><?= $key->username ?></td>
                                            <td><?= $key->tahun.'-'.$key->bulan ?></td>
                                            <td><label class="btn btn-submit status pending-rilis-po"
                                                    style="font-size:14px"><?= $key->total_hari_kerja ?></label></td>
                                            <td><label class="btn btn-submit status pending-rilis-po" style="font-size:14px"><?= $key->hadir ?></label></td>
                                            <td><label class="btn btn-submit status pending-finance"
                                                    style="font-size:14px"><?= $key->terlambat ?></label>
                                            </td>
                                            <td><label class="btn btn-submit status pending-finance" style="font-size:14px"><?= $key->tidak_lengkap ?></label></td>
                                            <td><?= $key->verifikasi_keterangan ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table -->


                        </div>
                    <!-- end table -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-ajuan').DataTable({
            "order": [0, 'asc'],
            // "aLengthMenu": [
            //     [10, 20, 50, -1],
            //     [10, 20, 50, "All"]
            // ],
            "paging": true,
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            "pagingType": "full_numbers",
            "pageLength": 50,
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#tabel-data').DataTable({
            "info": true,
        });
    });
</script>