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
                                            <input type="month" class="form-control" name="bulan" value="<?= $this->input->get('bulan') ?>">
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
                    <div class="row mt-5" id="data-absensi">


                        <div class="row">
                            <div class="col-md-12 mb-3">
                                Bulan : <?= ($this->input->get('bulan') == null)? date('Y-m') : $this->input->get('bulan') ?>
                            </div>
                            <div class="table-responsive">
                                <form action="<?= base_url("$url2"); ?>" method="post">
                                    <table id="tabel-data" class="display table-striped table-bordered"
                                        style="width: 100%; text-align: center;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Karyawan</th>
                                                <th>Status</th>
                                                <th>No Report</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>HariKerja</th>
                                                <th>Terlambat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                        $no = 1;
                                        foreach ($data_absensi->result() as $a) { ?>
                                            <tr>
                                                <td><input type="text" name="signature[]" value="<?= $a->signature; ?>"
                                                        hidden><?= $no++; ?></td>
                                                <td><input type="text" name="userid[]" value="<?= $a->id; ?>"
                                                        hidden><?= $a->name; ?></td>
                                                <td>
                                                    <input type="text" name="flag_status[]"
                                                        value="<?= $a->flag_status; ?>" hidden>
                                                    <?php if ($a->flag_status == '2' || $a->flag_status == '3') { ?>
                                                    <a href="<?= base_url('absensi/detail_absensi_by_month/'.$month.'/'.$a->id); ?>" class="btn btn-submit status pending-rilis-po"
                                                        style="font-size:14px">
                                                        <?= $a->status ?></a>
                                                    <?php } else if($a->flag_status == '9') { ?>
                                                    <a href="<?= base_url('absensi/detail_absensi_by_month/'.$month.'/'.$a->id); ?>" class="btn btn-submit status pending-finance"
                                                        style="font-size:14px">
                                                        <?= $a->status ?></a>
                                                    <?php } else { ?>
                                                    <!-- <a href="<?= base_url('absensi/detail_absensi/'.$a->signature); ?>" class="btn btn-submit status pending-finance" style="font-size:14px"> -->
                                                    <a href="<?= base_url('absensi/detail_absensi_by_month/'.$month.'/'.$a->id); ?>" class="btn btn-submit status pending-finance" style="font-size:14px">
                                                        <?= ($a->status == Null ? 'Pending User' : $a->status) ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td><input type="text" name="no_generate_report[]"
                                                        value="<?= $a->no_generate_report; ?>"
                                                        hidden><?= $a->no_generate_report; ?></td>
                                                <td><input type="text" name="bulan[]" value="<?= $a->bulan; ?>"
                                                        hidden><?= $a->bulan; ?></td>
                                                <td><input type="text" name="tahun[]" value="<?= $a->tahun; ?>"
                                                        hidden><?= $a->tahun; ?></td>
                                                <td>
                                                    <?php 
                                                        if($a->total_hari_kerja) { ?>
                                                            <label class="btn btn-submit status pending-rilis-po" style="font-size:14px"><?= $a->total_hari_kerja ?></label>
                                                        <?php
                                                        }
                                                    ?>                                                    
                                                </td>
                                                <td>
                                                    <?php 
                                                        if($a->terlambat) { ?>
                                                            <label class="btn btn-submit status pending-finance" style="font-size:14px"><?= $a->terlambat ?></label>
                                                        <?php
                                                        }else{
                                                            echo '-';
                                                        }
                                                    ?>                                                
                                                </td>
                                            </tr>
                                            <?php } ?>
                                    </table>
                                    <div class="mt-4" style="text-align: center;">
                                        <button type="submit" class="btn btn-submit status"
                                            style="width: 20%">Simpan</button>
                                    </div>
                                    
                                </form>
                            </div>
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
        var bulan = '<?= $this->input->get('bulan') ?>';
        if (bulan == '') {
            $("div#data-absensi").hide();
        } else {
            $("div#data-absensi").show();
        }

        $('#tabel-data').DataTable({
            "info": false,
            "paging": false,
            "searching": false,
            "order": [0, 'asc'],
        });
    });
</script>