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
                        <th width="5%">Kode Outlet</th>
                        <th width="10%">Nama Outlet</th>
                        <th width="10%">Tracking</th>
                        <th width="10%">Actual Value</th>
                        <th width="10%">Target Value</th>
                        <th width="10%">Gap Value</th>
                        <th width="10%">Gap %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $p->kode_outlet ?></td>
                        <td><?= $p->nama_outlet ?></td>
                        <td><?= $p->nama_tracking ?></td>
                        <td>
                            Rp. <?= number_format($p->actual_value,0) ?>
                        </td>
                        <td>
                            Rp. <?= number_format($p->target_value,0) ?>
                        </td>
                        <td>
                            <?php if ($p->gap < 0) { ?>
                            <span class="pending-finance">Rp. <?= number_format($p->gap,0) ?></span>
                            <?php }else{ ?>
                            <span class="pending-scm">Rp. <?= number_format($p->gap,0) ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($p->gap_persen < 0) { ?>
                            <span class="pending-finance">Rp. <?= number_format($p->gap_persen,0) ?></span>
                            <?php }else{ ?>
                            <span class="pending-scm">Rp. <?= number_format($p->gap_persen,0) ?></span>
                            <?php } ?>
                        </td>
                    </tr>
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
            "pageLength": 1000,
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