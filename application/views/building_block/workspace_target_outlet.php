<style>
    th{
        font-weight: bold;
        background-color: #FFEAA7;
        border: 0.5px solid #383838;
        color: #000000;
        font-size: 13px;
    }
    td{
        background-color: #ffffff;
        border: 0.5px solid #000000;
        font-size: 12px;
        line-height: 5px;
        overflow:hidden;
    }

    table {
        border-collapse: collapse;
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

    .btn-delete {
        color: #f0f0f0;
        background-color: #D04848;
        border-radius: 10px;
        border: 2px solid black;
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

<form action=<?= base_url().$url_generate ?> method="post">
<div class="container mb-2">
    <div class="row mt-3 ms-5">
        <div class="col-md-3">
            <label for="tahun">Pilih Tahun</label>
        </div>
        <div class="col-md-3">
            <input type="month" class="form-control" id="bulan" name="bulan" min="2024-01" max="2024-12" value="<?= $this->input->get('bulan_target') ?>" required>
        </div>
    </div>
</div>

<div class="container mb-2">
    <div class="row mt-3 ms-5">
        <div class="col-md-3">
            <label for="tahun">Pilih Site Code</label>
        </div>
        <div class="col-md-3">
            <input type="hidden" name="bulan_target" value="<?= $this->input->get('bulan_target') ?>">
            <select name="site_code" id="site_code" class="form-control" required>
            </select>     
        </div>
    </div>
</div>

<div class="container mb-2">
    <div class="row mt-3 ms-5">
        <div class="col-md-3">
            <label for="tahun">Pilih Raw Data</label>
        </div>
        <div class="col-md-3">
            <select name="periode[]" id="raw_data" class="form-control" required multiple>
            </select>     
        </div>
    </div>
</div>

<div class="container mb-2">
    <div class="row mt-3 ms-5">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-3">
            <input type="submit" class="btn btn-generate" value="Generate workspace" id="btnKirim" onclick="return button()">
            <button class="btn btn-info" id="btnLoading" type="button" disabled>
            ... Generating Data .. Mohon menunggu ...
            </button>
        </div>
    </div>
</div>

</form>
<hr>

<div class="container mt-4">
    <div class="row mt-2 ms-5">
        <div class="col-md-12">
            <table id="workspace" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center col-1">No</th>
                        <th>Periode</th>
                        <th>DP</th>
                        <th>PeriodeRawData</th>
                        <th>CreatedAt</th>
                        <th>CreatedBy</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>  
                    <?php
                    $no = 1;
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $a->tahun_building_block.'-'.$a->bulan_building_block ?></td>
                        <td><?= $a->branch_name.' - '.$a->nama_comp.' - '.$a->site_code ?></td>
                        <td><?= $a->periode_raw_data ?></td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->username ?></td>
                        <td class="text-center">
                            <a href="<?= base_url().'building_block/delete_workspace_target_outlet/'.$a->signature ?>" onclick="return confirm('Anda yakin menghapus data ini ?')" class="btn btn-delete">del</a>
                            <a href="<?= base_url().'building_block/target_by_outlet/'.$a->signature ?>" class="btn btn-generate">Manage</a>
                        </td>
                    </tr>
                    <?php
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
    $("#example").DataTable({
        "pageLength": 100,
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
        $('#workspace').DataTable(
            {
                scrollX: true
            }
        );
    });
</script>

<script>
    function button()
    {        
        var bulan   = document.getElementById('bulan').value;
        var site_code  = document.getElementById('site_code').value;
        var raw_data  = document.getElementById('raw_data').value;
        if (bulan && site_code && raw_data) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
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
            $("select[id = raw_data]").html(hasil_d1);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('building_block/site_code_by_user') ?>',
        data: 'userid=<?= $userid ?>',
        success: function(hasil_branch) {
            $("select[name = site_code]").html(hasil_branch);
        }
    });
</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>