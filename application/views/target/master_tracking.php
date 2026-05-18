<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">
    <?= $this->load->view('target/component/sidebar');?>

<div class="row mt-1">
    <div class="col-md-12">

        <?php echo form_open($url); ?>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="nama_tracking" class="form-label">Nama Tracking</label> 
            </div>
            <div class="col-lg-5">
                <input type="text" class="form-control" name="nama_tracking" id="nama_tracking" required>
            </div>
        </div> 

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="supp" class="form-label">Bulan</label> 
            </div>
            <div class="col-lg-5 d-flex flex-row">
                <input type="month" class="form-control" name="from" id="from">
                <input type="month" class="form-control" name="to" id="to">
            </div>
        </div> 

        <div class="row mt-5">
            <div class="col-lg-2">
                
            </div>
            <div class="col-lg-6">
                <!-- <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Show Data</button> -->
                <button class="pastel-orange-btn" id="btnKirim" onclick="return button()">Submit data</button>
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
                        <th>Nama Tracking</th>
                        <th>Periode</th>
                        <th>CreatedAt</th>
                        <th>CreatedBy</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <a href="<?= base_url() ?>target_outlet/tracking_detail/<?= $a->signature ?>" class="btn pending-scm"><?= $a->nama_tracking ?></a>
                        </td>
                        <td><?= $a->from.' - '.$a->to ?></td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->username ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= base_url('target_outlet/delete_master_tracking/'.$a->signature) ?>" class="delete-button" onclick="return confirm('Hapus data ini ?')">del</a>
                                <a href="<?= base_url('target_outlet/start_tracking/'.$a->signature) ?>" class="btn btn-submit">run</a>
                            </div>
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
        let nama_tracking = document.getElementById('nama_tracking').value;
        let from = document.getElementById('from').value;
        let to = document.getElementById('to').value;

        if (nama_tracking && from && to) {
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

