<style>
    th{
        font-weight: bold;
        background-color: #FFEAA7;
        border: 1px solid #383838;
        color: #000000;
        font-size: 13px;
    }
    td{
        background-color: #ffffff;
        border: 1px solid #000000;
        font-size: 12px;
        line-height: 1px;
        overflow:hidden;
    }

    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
        border-radius: 10px;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #7077A1;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #2D3250;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
        border-radius: 10px;
    }
    .btn-pendingdp:hover {
        color: #f0f0f0;
        background-color: #383838;
    }

    .btn-average {
        color: #f0f0f0;
        background-color: #6962AD;
        border-radius: 10px;
        border: 2px solid black;
    }
    .btn-average:hover {
        color: #f0f0f0;
        background-color: #6962AD;
    }

    .btn-average {
        color: #f0f0f0;
        background-color: #6962AD;
        border-radius: 10px;
        border: 2px solid black;
    }
    .btn-average:hover {
        color: #f0f0f0;
        background-color: #6962AD;
    }

    .btn-generate {
        color: #f0f0f0;
        background-color: #638889;
        border-radius: 10px;
        border: 2px solid black;
    }
    .btn-average:hover {
        color: #f0f0f0;
        background-color: #638889;
    }

</style>

</div>

<div class="container">

    <div class="row">
        <div class="col-md-12 mt-5 az-content-label">
            <?= $title ?>
        </div>
    </div>
</div>

<div class="container mt-s">
    <div class="row mt-2 ms-5">
        <div class="col-md-12 az-content-label text-center">
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
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h5>Target By Outlet </h5>
        </div>
    </div>
</div>

<form action=<?= base_url().$url_target ?> method="post">
<div class="container mt-4">
    <div class="row mt-2 ms-5">
        <div class="col-md-12">
            <table id="outlet" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">SiteCode</th>
                        <th class="text-center">KodeOutlet</th>
                        <th class="text-center">NamaOutlet</th>
                        <th class="text-center">KodeType</th>
                        <th class="text-center">NamaType</th>
                        <th class="text-center">Target Value</th>
                    </tr>
                </thead>
                <tbody>     
                   <?php 
                    foreach ($get_data_target_by_outlet->result() as $a) : ?>

                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>
                        <td><?= $a->site_code ?></td>                       
                        <td><?= $a->kode_lang ?></td>                       
                        <td><?= $a->nama_lang ?></td>                       
                        <td><?= $a->kode_type ?></td>                       
                        <td><?= $a->nama_type ?></td>                       
                        <td></td>                       
                    </tr>
                    
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container mt-2">
    <div class="row mb-1">            

        <div class="col-md-6">
            <input type="submit" class="btn btn-generate" value="Update Data Target on Checklist" id="btnTarget" onclick="return buttonTarget()">
            <input type="submit" class="btn btn-generate" value="Export Import">
            <input type="hidden" name="bulan_target" value="<?= $this->input->get('bulan_target') ?>">
            <button class="btn btn-info" id="btnLoadingTarget" type="button" disabled>
            ... Generating Data .. Mohon menunggu ...
            </button>
        </div>
    </div>
</div>
</form>


<hr>


<script>
    $(document).ready(function () {
    $("#outlet").DataTable({
        "pageLength": 10000,
        "ordering": true,
        "order": [0, 'desc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        "fixedHeader": {
            header: true,
            footer: true
        },
    });
    });
</script>

<script>
    $(document).ready(function () {
        $('#test').DataTable(
            {
                scrollX: true
            }
        );
    });
</script>

<script>
    function button()
    {        
        $("#btnKirim").hide();
        $("#btnLoading").show();
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
        $("#btnLoadingSearch").hide();
        $("#btnLoadingTarget").hide();
    });

    function buttonSearch()
    {        
        $("#btnSearch").hide();
        $("#btnLoadingSearch").show();
    }

    function buttonTarget()
    {        
        $("#btnTarget").hide();
        $("#btnLoadingTarget").show();
    }

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('building_block/summary_d1') ?>',
        data: 'userid=<?= $userid ?>',
        success: function(hasil_d1) {
            $("select[id = periode]").html(hasil_d1);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('building_block/site_code_by_user') ?>',
        data: 'userid=<?= $userid ?>',
        success: function(hasil_site_code) {
            $("select[id = site_code]").html(hasil_site_code);
        }
    });

</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>