<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">
    <?= $this->load->view('target/component/sidebar');?>


    <div class="row mt-2">
        <div class="dashboard">
            <div class="card">
                <div class="card-header">
                    <span class="title">Total Tracking</span>
                    <span class="icon"><?= $count_tracking ?></span>
                </div>
                <div class="card-header">
                    <span class="title">Total Outlet</span>
                    <span class="icon"><?= $count_tracking_detail ?></span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="title">Total Tracking</span>
                    <span class="icon"><?= $count_tracking ?></span>
                </div>
                <div class="card-header">
                    <span class="title">Total Outlet</span>
                    <span class="icon"><?= $count_tracking_detail ?></span>
                </div>
            </div>
        </div>
    </div>
    

    <div class="row mt-5">
      <div class="col-md-12 mt-2">
        <button type="button" class="export-excel-btn" onclick="convertTable()">Export to Excel</button>
      </div>
    </div>


    <div class="row mt-5">
        <div class="col-md-12">
            <table id="tabel" class="datatable" width="100%">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th width="10%">Branch</th>
                        <th width="20%">Subbranch</th>
                        <th width="10%">Tanggal Data Berjalan</th>
                        <th width="10%">Closing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1; 
                        foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $p->branch_name ?></td>
                        <td><?= $p->nama_comp ?></td>
                        <td><?= $p->tanggal_data ?></td>
                        <td>
                            <?= ($p->status_closing == 0) ? 'Belum Closing' : 'Sudah Closing' ?>
                        
                        </td>
                    </tr    >
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $("#btnLoading").hide();
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : true,
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
    function button()
    {
        $("#btnKirim").hide();
        $("#btnBack").hide();
        $("#btnLoading").show();
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>