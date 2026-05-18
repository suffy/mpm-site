</div>

<div class="container-fluid">
    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('absensi/component/sidebar');?>
                <div class="col">

                    <div class="row">
                        <div class="col-md-12 az-content-label">
                            <?= $title ?>
                        </div>
                    </div>

                    <!-- Search -->
                    <form action="<?= base_url($url); ?>" method="GET">
                        <div class="row mt-4">
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
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-2">
                                <label>Tahun</label>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="tahun"
                                        value="<?= $this->input->get('tahun') ?>" placeholder="(contoh : 2024)"
                                        id="datepicker">
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
                        <div class="row mt-5 mb-5">
                            <div class="col-md-12 az-content-label text-center">
                                Report Perfomance Karyawan
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="button" class="export-excel-btn" onclick="convertTable()">Export to Excel</button>
                            </div>
                        </div>
                        

                        <div class="row">
                            <div class="table-responsive">
                                <table id="tabel-data" class="display table-striped table-bordered"
                                    style="width: 100%; text-align: center;">
                                    <thead>
                                        <tr>
                                            <th>No Report</th>
                                            <th>Nama Karyawan</th>
                                            <th>Bulan</th>
                                            <th>Total Hari Kerja</th>
                                            <th>Hadir</th>
                                            <th>Terlambat</th>
                                            <th>Tidak Lengkap</th>
                                            <th>Note Atasan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data_report->result() as $key) { ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url('absensi/detail_absensi_by_month/'.$key->tahun.'-'.$key->bulan.'/'.$key->userid); ?>"
                                                    class="btn btn-submit status pending-rilis-po"
                                                    style="font-size:14px"><?= $key->no_generate_report ?></a>
                                            </td>
                                            <td><?= $key->name ?></td>
                                            <td><?= $key->bulan ?></td>
                                            <td><label class="btn btn-submit status pending-rilis-po"
                                                    style="font-size:14px"><?= $key->total_hari_kerja ?></label></td>
                                            <td><label class="btn btn-submit status pending-rilis-po"
                                                    style="font-size:14px"><?= $key->hadir ?></label></td>
                                            <td><label class="btn btn-submit status pending-finance"
                                                    style="font-size:14px"><?= $key->terlambat ?></label></td>
                                            <td><label class="btn btn-submit status pending-finance" style="font-size:14px"><?= $key->tidak_lengkap ?></label></td>
                                            <td><?= $key->verifikasi_keterangan ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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
        var tahun = "<?= $this->input->get('tahun') ?>";
        var karyawan = "<?= $this->input->get('karyawan') ?>";
        if (tahun == '' && karyawan == '') {
            $("div#data-absensi").hide();
        } else {
            $("div#data-absensi").show();
        }

        $('#tabel-data').DataTable({
            "info": false,
            "paging": false,
            "searching": false,
        });
    });
</script>

<!-- fungsi input tahun -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
    rel="stylesheet" />
<script>
    $("#datepicker").datepicker({
        format: "yyyy", // Notice the Extra space at the beginning
        viewMode: "years",
        minViewMode: "years"
    });
</script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

<script>
    const convertTable = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel-data"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>