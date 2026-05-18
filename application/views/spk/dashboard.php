<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">

        <?php
        if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'fakhrul' || $this->session->userdata('username') == 'suffy') { ?>
            <?= $this->load->view('spk/component/sidebar_admin'); ?>
        <?php
        } else { ?>
            <?= $this->load->view('spk/component/sidebar'); ?>
        <?php
        }
        ?>

        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
            <h2 id="form_spk"><?= $title; ?></h2>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($this->session->flashdata('pesan')) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $this->session->flashdata('pesan'); ?>
                        </div>
                    <?php
                    } elseif ($this->session->flashdata('pesan_success')) { ?>
                        <div class="alert alert-success" role="alert">
                            <?= $this->session->flashdata('pesan_success'); ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-12">

                    <?php echo form_open($url); ?>

                    <div class="row mt-3">
                        <div class="col-lg-2">
                            <label for="supp">Bulan</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="month" name="bulan" class="form-control" value=<?= $pilih_bulan; ?>>
                        </div>
                        <div class="col-lg-4">
                            <button class="pastel-orange-btn" id="btnKirim" onclick="return button()">Show Data</button>
                            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                                ... Please wait ...
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">

                <!-- <main class="main">

        <section class="widget">
          <h3>Total Value</h3>
          <div class="metric">
              <span class="label"><?= $total_value ?></span>
          </div>
        </section>

        <section class="widget">
          <h3>Total Deltomed</h3>
          <div class="metric">
              <span class="label">label</span>
          </div>
        </section>

        <section class="widget">
          <h3>Total DP</h3>
          <div class="metric">
              <span class="label">label</span>
              <span class="value">value</span>
          </div>
        </section>

        

      </main> -->


                <div class="dashboard">
                    <div class="card">
                        <div class="card-header">
                            <span class="title">Total Value</span>
                            <span class="icon">Rp.</span>
                        </div>
                        <div class="card-content">
                            <div class="main-value">Rp. <?= number_format($total_value, 0) ?></div>
                            <div class="sub-value">berasal dari <?= $count_po ?> PO</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="title">Total Deltomed</span>
                            <span class="icon">Rp.</span>
                        </div>
                        <div class="card-content">
                            <div class="main-value">Rp. <?= number_format($total_value_deltomed, 0) ?></div>
                            <div class="sub-value">berasal dari <?= $count_po_deltomed ?> PO</div>
                        </div>
                    </div>


                </div>




            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <h3>Detail</h3>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="button" class="export-excel-btn" onclick="convertTable()">Export to Excel</button>
                </div>
            </div>


            <div class="row mt-5">
                <div class="col-md-12">
                    <table id="tabel" class="datatable">
                        <thead>
                            <tr>
                                <th width="80%">Principal</th>
                                <th width="10%">Value</th>
                                <th width="10%">Count PO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($get_data->result() as $p) : ?>
                                <tr>
                                    <td><?= $p->namasupp ?></td>
                                    <td>Rp. <?= number_format($p->total_value, 0) ?></td>
                                    <td><?= $p->count_po ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <script>
            $(document).ready(function() {
                $("#btnLoading").hide();
                $('#tabel').DataTable({
                    "pageLength": 1000,
                    "ordering": true,
                    "order": [0, 'desc'],
                    "aLengthMenu": [
                        [10, 20, 50, -1],
                        [10, 20, 50, "All"]
                    ],
                    "bInfo": true,
                    "bPaginate": true,
                    "fixedHeader": true,
                    "scrollCollapse": true
                });
            });
        </script>

        <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

        <script>
            const convertTable = () => {
                let convertedTable = XLSX.utils.table_to_book(document.getElementById("tabel"));
                XLSX.writeFile(convertedTable, "<?= $title ?>.xlsx");
            }
        </script>

        <script>
            function button() {
                $("#btnKirim").hide();
                $("#btnBack").hide();
                $("#btnLoading").show();
            }

            $(document).ready(function() {
                $("#btnLoading").hide();
            });
        </script>