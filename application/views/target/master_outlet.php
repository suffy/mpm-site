<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">
    <?= $this->load->view('target/component/sidebar');?>

<div class="row mt-1">
    <div class="col-md-12">

        <?php echo form_open($url); ?>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="supp" class="form-label">Tahun</label> 
            </div>
            <div class="col-lg-4">
                <input type="text" class="form-control" name="tahun" value="" id="datepicker" required>
            </div>
        </div> 

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="supp" class="form-label">Site Code</label> 
            </div>
            <div class="col-lg-4">
                <input type="text" class="form-control" name="site_code" id="site_code" required>
            </div>
        </div> 

        <div class="row mt-3">
            <div class="col-lg-2">
                
            </div>
            <div class="col-lg-6">
                <!-- <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Show Data</button> -->
                <button class="pastel-orange-btn" id="btnKirim" onclick="return button()">Update Data Master Outlet Nasional</button>
                <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                ... Please wait ...
                </button>
            </div>
        </div> 

    </div>
</div>

<div class="card-block mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel" class="datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Kode Outlet</th>
                        <th>Nama Outlet</th>
                        <th>Site</th>
                        <th>Kode Type</th>
                        <th>Kode Salur</th>
                        <th>CreatedAt</th>
                        <th>CreatedBy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a->tahun ?></td>
                        <td><?= $a->kode_outlet ?></td>
                        <td><?= $a->nama_outlet ?></td>
                        <td><?= $a->site_code ?></td>
                        <td><?= $a->kode_type ?></td>
                        <td><?= $a->kodesalur ?></td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->created_by ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": false,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : true,
            "bPaginate": true
        });
    });
</script>

<script>
    function button()
    {
        let tahun = document.getElementById('datepicker').value;
        let site_code = document.getElementById('site_code').value;

        if (tahun && site_code) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>

<!-- fungsi untuk menampilkan format tanggal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
    rel="stylesheet" />
<script>
    $(document).ready(function () {
        $('#datepicker').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });
    });
</script>