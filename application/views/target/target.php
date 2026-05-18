<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">
    <?= $this->load->view('target/component/sidebar');?>

    <div class="row mt-1">
        <div class="col-md-12">

            <?php echo form_open($url); ?>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="supp" class="form-label">Kode Outlet</label> 
                </div>
                <div class="col-lg-6">
                    <input type="text" name="kode_outlet" id="kode_outlet" class="form-control" required>
                </div>
            </div>  

            <div class="row mt-3">
                <div class="col-lg-2">
                </div>
                <div class="col-lg-6">
                    <button type="submit" class="pastel-btn pastel-mint" id="btnKirim" onclick="return button()">Submit Target</button>
                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>            
        </div>
    </div>

<div class="card-block mt-3 mb-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel" class="datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Outlet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a->kode_outlet ?></td>
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
            "pageLength": 1000,
            "ordering": false,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : false,
            "bPaginate": false
        });
    });

   
</script>

<script>
    function button()
    {
        
        var bulan  = document.getElementById('bulan').value;
        if (bulan) 
        {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
        $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>
