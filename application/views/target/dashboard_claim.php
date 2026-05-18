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
                        <th width="5%">Principal</th>
                        <th width="1%">NoSurat</th>
                        <th width="50%">NamaProgram</th>
                        <th>Subbranch</th>
                        <th>NoAjuanClaim</th>
                        <th>Status</th>
                        <th>StatusInternal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1; 
                        foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $p->namasupp ?></td>
                        <td>
                            
                            <a href="<?= base_url().'assets/uploads/management_claim/'.$p->upload_pdf ?>" class="btn btn-submit-black" target="_blank"><?= (strlen($p->nomor_surat > 50) ? substr($p->nomor_surat, 0, 50).' ...' : $p->nomor_surat); ?></a>
                        </td>
                        <td><?= $p->nama_program ?></td>
                        <td><?= $p->nama_comp.' - '.$p->site_code ?></td>
                        <td><?= $p->nomor_ajuan ?></td>
                        <td><?= $p->nama_status ?></td>
                        <td><?= $p->nama_status_internal ?></td>
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