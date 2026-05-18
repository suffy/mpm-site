
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
                        <!-- <div class="row mt-4">
                            <div class="col-lg-2">
                                <label>Nama Karyawan </label>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="karyawan">
                                    <option value="">-- Pilih Karyawan --</option>
                                    <?php foreach ($get_karyawan->result() as $key) { ?>
                                    <option value="<?= $key->id; ?>">
                                        <?= $key->nama.' - '.$key->id.' ('.$key->id_absensi.')' ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> -->

                        <div class="row mt-4">
                            <div class="col-lg-2">
                                <label>Bulan Absensi </label>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="month" name="bulan" id="bulan" class="form-control"
                                        value="<?= $this->input->get('bulan') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-2">
                                <label for="nama_program"></label>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="submit" value="Show Data" class="btn pastel-orange-btn">
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end search -->

                    <!-- table data absensi -->
                    <div class="row mt-5" id="data-absensi">
                    

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
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($get_absensi_by_userverifikasi->result() as $key) { ?>
                                        <tr>
                                            <td>
                                                <?php if ($key->flag_status == '2' || $key->flag_status == '3') { ?>
                                                <a class="btn btn-submit status pending-rilis-po" style="font-size:14px"><?= $key->status ?></a>
                                                <?php } else { ?><a class="btn btn-submit status pending-finance" style="font-size:14px"><?= $key->status ?></a>
                                                <?php } ?>
                                            </td>
                                            <td><?= $key->username ?></td>
                                            <td><?= $key->tahun.'-'.$key->bulan ?></td>
                                            <td><label class="btn btn-submit status pending-rilis-po"
                                                    style="font-size:14px"><?= $key->total_hari_kerja ?></label></td>
                                            <td><label class="btn btn-submit status pending-rilis-po"
                                                    style="font-size:14px"><?= $key->hadir ?></label></td>
                                            <td><label class="btn btn-submit status pending-finance"
                                                    style="font-size:14px"><?= $key->terlambat ?></label>
                                            </td>
                                            <td><label class="btn btn-submit status pending-finance" style="font-size:14px"><?= $key->tidak_lengkap ?></label></td>
                                            <td>
                                                <a href="<?= base_url('absensi/verifikasi_atasan/'.$key->signature); ?>"
                                                    class="btn btn-submit">Verifikasi</a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table -->


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // var bulan = "<?= $this->input->get('bulan') ?>";
        // var karyawan = "<?= $this->input->get('karyawan') ?>";
        // if (bulan == '' && karyawan == '') {
        //     $("div#data-absensi").hide();
        // } else {
        //     $("div#data-absensi").show();
        // }

        $('#tabel-data').DataTable({
            "info": false,
            "paging": false,
            "searching": false,
            "order": [3, 'asc'],
        });
    });
</script>