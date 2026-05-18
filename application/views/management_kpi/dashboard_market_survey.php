</div>

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

        <div class="row mt-4">
            <div class="col-md-2">
                <label for="nama_program"></label>
            </div>
            <div class="col-md-4">
                <input type="submit" value="Submit" class="btn btn-submit-black">
                <a href="<?= base_url() ?>kpi/manage_activity" class="btn btn-submit-black">Back</a>
            </div>
        </div>
    </form>

<hr style="border: 1px solid black; box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);"
    class="mt-5">

    <div class="row mt-4 mb-5 d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="text-align: center;">Report Tim</h5>
                    <button type="button" class="mb-3 btn btn-success" onclick="convertTableReportTim()">Export</button>
                    <table id="table-report-tim">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Rank</th>
                                <th>Kuartal</th>
                                <th>Tahun</th>
                                <th>Total Survey</th>
                                <th>Total Survey Verified</th>
                                <th>Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1 ;
                        foreach ($get_report_point_tim->result() as $a) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $a->name ?></td>
                                <td><?= $a->rank ?></td>
                                <td><?= $a->kuartal ?></td>
                                <td><?= $a->tahun ?></td>
                                <td><?= $a->total_market_survey ?></td>
                                <td><?= $a->total_market_survey_verified ?></td>
                                <td><?= $a->point ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="text-align: center;">Report Anda</h5>
                    <button type="button" class="mb-3 btn btn-success"
                        onclick="convertTableReportPoint()">Export</button>
                    <table id="table-report-point">
                        <thead>
                            <tr>
                                <th>Kuartal</th>
                                <th>Tahun</th>
                                <th>Total Survey</th>
                                <th>Total Survey Verified</th>
                                <th>Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        foreach ($get_report_point->result() as $a) : ?>
                            <tr>
                                <td><?= $a->kuartal ?></td>
                                <td><?= $a->tahun ?></td>
                                <td><?= $a->total_market_survey ?></td>
                                <td><?= $a->total_market_survey_verified ?></td>
                                <td><?= $a->point ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-5 d-flex justify-content-center">
        <div class="col-md-6" style="text-align: center;">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ketentuan</h5>
                    <table id="table-ketentuan">
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
                </div>
            </div>
        </div>

        <div class="col-md-6" style="text-align: center;">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Point Anda</h5>
                    <p style="font-size: 70px;">
                        <?php if ($get_report_point->num_rows() > 0) {
                            echo $get_report_point->row()->point;
                            echo '<br><button type="button" class="btn btn-submit-black" onclick="review_click()">Review Market
                            Survey</button>';
                        } else {
                            echo '0';
                        }?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mb-5" id="review">
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-submit-black" onclick="dashboard_click()">Back To Dashboard</button>
            <h5 class="card-title" style="text-align: center;">Review Market Survey</h5>
            <button type="button" class="mb-3 btn btn-success" onclick="convertTableReview()">Export</button>
            <table id="table-review">
                <thead>
                    <th>No</th>
                    <th>No Market Survey</th>
                    <th>Tanggal</th>
                    <th>Pelaksana</th>
                    <th>Nama Toko</th>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_review->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a->no_pelaporan ?></td>
                        <td><?= $a->survey_from ?></td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->nama_toko ?></td>
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
        $("#review").hide();
        $('#table-report-tim').DataTable({
            "paging": true,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });

        $('#table-report-point').DataTable({
            "paging": true,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });


        $('#table-ketentuan').DataTable({
            "paging": true,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });

        $('#table-review').DataTable({
            "paging": true,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<!-- fungsi button -->
<script>
    function review_click() {
        $('#dashboard').hide();
        $('#review').show();
    }

    function dashboard_click() {
        $('#dashboard').show();
        $('#review').hide();
    }
</script>

<!-- fungsi input tahun -->
<script>
    $("#datepicker").datepicker({
        format: " yyyy", // Notice the Extra space at the beginning
        viewMode: "years",
        minViewMode: "years"
    });
</script>

<!-- fungsi export -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
    const convertTableReportTim = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("table-report-tim"));
        XLSX.writeFile(convertedTable, "<?= $title.'_report_tim' ?>.xlsx");
    }
    const convertTableReportPoint = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("table-report-point"));
        XLSX.writeFile(convertedTable, "<?= $title.'_report' ?>.xlsx");
    }
    const convertTableReview = () => {
        let convertedTable = XLSX.utils.table_to_book(document.getElementById("table-review"));
        XLSX.writeFile(convertedTable, "<?= $title.'_review' ?>.xlsx");
    }
</script>