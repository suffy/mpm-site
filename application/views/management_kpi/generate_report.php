</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
    rel="stylesheet" />
<style>
    td {
        text-transform: uppercase;
    }
</style>

<div class="container-fluid" id="dashboard">

    <div class="row mt-1">
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

    <form action="<?= $url ?>" method="GET">

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="nama_program">Pilih Quarterly</label>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <select name="kuartal" class="form-control" required>
                        <option value="">-- Pilih Quarterly --</option>
                        <option value="Q1" <?= ($this->input->get('kuartal') == 'Q1') ? 'selected' : '' ?>>Q1</option>
                        <option value="Q2" <?= ($this->input->get('kuartal') == 'Q2') ? 'selected' : '' ?>>Q2</option>
                        <option value="Q3" <?= ($this->input->get('kuartal') == 'Q3') ? 'selected' : '' ?>>Q3</option>
                        <option value="Q4" <?= ($this->input->get('kuartal') == 'Q4') ? 'selected' : '' ?>>Q4</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="nama_program">Pilih Tahun</label>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="tahun" value="<?= $this->input->get('tahun') ?>"
                        id="datepicker">
                </div>
            </div>
        </div>

        <!-- <div class="row mt-3">
            <div class="col-md-2">
                <label for="nama_program">Pilih Rank</label>
            </div>
            <div class="col-md-4">
                <select name="rank" id="rank" class="form-control" required>
                    <option value=""> -- Pilih Rank --</option>
                    <option value="spo" <?= ($this->input->get('rank') == 'spo') ? 'selected' : '' ?>>SPO</option>
                    <option value="asps" <?= ($this->input->get('rank') == 'asps') ? 'selected' : '' ?>>ASPS / ASPH
                    </option>
                    <option value="rsph" <?= ($this->input->get('rank') == 'rsph') ? 'selected' : '' ?>>RSPH</option>
                </select>
            </div>
        </div> -->

        <div class="row mt-4">
            <div class="col-md-2">
                <label for="nama_program"></label>
            </div>
            <div class="col-md-4">
                <input type="submit" value="Submit Generate Report" class="btn btn-submit-black">
                <a href="<?= base_url() ?>kpi/manage_activity" class="btn btn-submit-black">Back</a>
            </div>
        </div>

    </form>

    <hr style="border: 1px solid black; box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);"
        class="mt-5">

    <div class="row mt-4 mb-5 d-flex justify-content-center gap-4">
        <div class="card" style="width: 30rem;">
            <div class="card-body">
                <h5 class="card-title">Event By Status</h5>
                <p class="card-text">
                    <table id="table-event-dashboard">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Quarterly</th>
                                <th>Bulan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_event_by_status->result() as $a) : ?>
                            <tr>
                                <td><?= $a->nama_status ?></td>
                                <td><?= $a->kuartal ?></td>
                                <td><?= date('F', strtotime($a->event_from)) ?></td>
                                <td><?= $a->total ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </p>
            </div>
        </div>

        <div class="card" style="width: 30rem;">
            <div class="card-body">
                <h5 class="card-title">Event By Nama</h5>
                <p class="card-text">
                    <table id="table-event-dashboard-by-user">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Rank</th>
                                <th>Quarterly</th>
                                <th>Bulan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_event_by_userid->result() as $a) : ?>
                            <tr>
                                <td><?= $a->name ?></td>
                                <td><?= $a->rank ?></td>
                                <td><?= $a->kuartal ?></td>
                                <td><?= date('F', strtotime($a->event_from)) ?></td>
                                <td><?= $a->total ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </p>
            </div>
        </div>

        <div class="card" style="width: 40rem;">
            <div class="card-body">
                <h5 class="card-title">Ketentuan</h5>
                <p class="card-text">
                    <table id="table-event-dashboard-perhitungan">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Quarterly</th>
                                <th>Parameter</th>
                                <th>Min Target</th>
                                <th>Bobot</th>
                                <th>Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_perhitungan->result() as $a) : ?>
                            <tr>
                                <td><?= $a->category ?></td>
                                <td><?= $a->kuartal ?></td>
                                <td><?= $a->parameter ?></td>
                                <td><?= $a->min_target ?></td>
                                <td><?= $a->bobot ?></td>
                                <td><?= $a->rank ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </p>
            </div>
        </div>

    </div>

    <hr>
    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <h4 style="text-align: center;">Report Event</h4>
            <button type="button" class="btn btn-success" onclick="convertTableEvent()">Export</button>
            <br><br>
            <table id="table-event">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama</th>
                        <th>Rank</th>
                        <th>Quarterly</th>
                        <th class="text-center">Total Event</th>
                        <th class="text-center">Total Event Verified</th>
                        <th class="text-center">User Approval</th>
                        <th class="text-center">RSPH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                $no = 1;
                foreach ($generate_report_event->result() as $a) : ?>
                    <tr>
                        <td align="center"><?= $no++ ?></td>
                        <td><?= $a->no_pelaporan_event ?></td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->rank ?></td>
                        <td><?= $a->kuartal ?></td>
                        <td><?= $a->tahun ?></td>
                        <td><?= $a->asps ?></td>
                        <td><?= $a->rsph ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="button" class="mt-5 btn btn-submit-black" onclick="report_click()">Generate Report</button>
    </div>
</div>

<div class="container-fluid" id="report">
    <button type="button" class="btn btn-submit-black" onclick="dashboard_click()">Back To Dashboard</button>
    <div class="row">
        <div class="col-md-12">
            <h4 style="text-align: center;">Report SPO</h4>
            <button type="button" class="btn btn-success" onclick="convertTableSpo()">Export</button>
            <br><br>
            <table id="table-spo">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama</th>
                        <th>Rank</th>
                        <th>Quarterly</th>
                        <th class="text-center">Total Event</th>
                        <th class="text-center">Total Event Verified</th>
                        <th class="text-center">KPI</th>
                        <th class="text-center">Point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                $no = 1;
                foreach ($generate_report_spo->result() as $a) : ?>
                    <tr>
                        <td align="center"><?= $no++ ?></td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->rank ?></td>
                        <td><?= $a->kuartal ?></td>
                        <td><?= $a->total_event ?></td>
                        <td><?= $a->total_event_verified ?></td>
                        <td><?= $a->kpi ?></td>
                        <td><?= $a->point ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <h4 style="text-align: center;">Report ASPS</h4>
            <button type="button" class="btn btn-success" onclick="convertTableAsps()">Export</button>
            <br><br>
            <table id="table-asps">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama</th>
                        <th>Rank</th>
                        <th>Quarterly</th>
                        <th class="text-center">Total Event</th>
                        <th class="text-center">Total Supervisi</th>
                        <th class="text-center">Min Supervisi 25% dari Total Event</th>
                        <th class="text-center">Jumlah Tim Spo</th>
                        <th class="text-center">Point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($generate_report_asps->result() as $a) : ?>
                    <tr>
                        <td align="center"><?= $no++ ?></td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->rank ?></td>
                        <td><?= $a->kuartal ?></td>
                        <td><?= $a->total_event_verified ?></td>
                        <td><?= $a->total_supervisi ?></td>
                        <td><?= $a->kpi ?></td>
                        <td><?= $a->jml_tim ?></td>
                        <td><?= $a->point ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <h4 style="text-align: center;">Report RSPH</h4>
            <button type="button" class="btn btn-success" onclick="convertTableRsph()">Export</button>
            <br><br>
            <table id="table-rsph">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama</th>
                        <th>Rank</th>
                        <th>Quarterly</th>
                        <th class="text-center">Total Event</th>
                        <th class="text-center">Jumlah Tim Asps</th>
                        <th class="text-center">Point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    foreach ($generate_report_rsph->result() as $key => $value) : ?>
                    <tr>
                        <td align="center"><?= $no++ ?></td>
                        <td><?= $value->name ?></td>
                        <td><?= $value->rank ?></td>
                        <td><?= $value->kuartal ?></td>
                        <td><?= $value->total_event_verified ?></td>
                        <td><?= $value->jml_tim ?></td>
                        <td><?= $value->point ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- fungsi datatable -->
<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $("#report").hide();
        $('#table-event').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        $('#table-spo').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [4, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        $('#table-asps').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [4, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        $('#table-rsph').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [3, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $(document).ready(function () {
        $('#table-event-dashboard').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });

    });

    $(document).ready(function () {
        $('#table-event-dashboard-by-user').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $(document).ready(function () {
        $('#table-event-dashboard-perhitungan').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $(document).ready(function () {
        $('#table-event-spo').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<!-- fungsi untuk menampilkan format tanggal -->
<script>
    $("#datepicker").datepicker({
        format: " yyyy", // Notice the Extra space at the beginning
        viewMode: "years",
        minViewMode: "years"
    });
</script>
<!-- fungsi show hide dashboard -->
<script>
    function report_click(param) {
        $("#dashboard").hide();
        $("#report").show();
    }
    function dashboard_click(param) {
        $("#dashboard").show();
        $("#report").hide();
    }
</script>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
    const convertTableEvent = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("table-event"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
    const convertTableSpo = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("table-spo"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
    const convertTableAsps = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("table-asps"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
    const convertTableRsph = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("table-rsph"));
        XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
    }
</script>