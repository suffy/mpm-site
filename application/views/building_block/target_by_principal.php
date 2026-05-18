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

<div class="container mt-3 mb-5">
    <div class="row mb-1">                   
        <div class="col-md-3">
            <label for="periode">Periode D1</label>

            <form action=<?= base_url().$url_average.'/D1' ?> method="post"> 
                <input type="hidden" name="bulan_target" value="<?= $periode_bb ?>">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <select name="periode[]" id="periode" class="form-control" required multiple>
                </select>                
                <input type="submit" class="btn btn-average" value="update average d1">            
            </form>
        </div>

        <div class="col-md-3">
            <label for="periode">Periode D2</label>

            <form action=<?= base_url().$url_average.'/D2' ?> method="post"> 
                <input type="hidden" name="bulan_target" value="<?= $periode_bb ?>">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <select name="periode[]" id="periode" class="form-control" required multiple>
                </select>                
                <input type="submit" class="btn btn-average" value="update average d2">            
            </form>
        </div>

        <div class="col-md-3">
            <label for="periode">Periode Herbana</label>

            <form action=<?= base_url().$url_average.'/herbana' ?> method="post"> 
                <input type="hidden" name="bulan_target" value="<?= $periode_bb ?>">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <select name="periode[]" id="periode" class="form-control" required multiple>
                </select>                
                <input type="submit" class="btn btn-average" value="update average herbana">            
            </form>
        </div>

        <div class="col-md-3">
            <label for="periode">Periode Marguna</label>

            <form action=<?= base_url().$url_average.'/marguna' ?> method="post"> 
                <input type="hidden" name="bulan_target" value="<?= $periode_bb ?>">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <select name="periode[]" id="periode" class="form-control" required multiple>
                </select>                
                <input type="submit" class="btn btn-average" value="update average marguna">            
            </form>
        </div>

    </div>
</div>

<div class="container mt-3 mb-5">
    <div class="row mb-1">                   
        <div class="col-md-3">
            <label for="periode">Periode Us</label>

            <form action=<?= base_url().$url_average.'/us' ?> method="post"> 
                <input type="hidden" name="bulan_target" value="<?= $periode_bb ?>">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <select name="periode[]" id="periode" class="form-control" required multiple>
                </select>                
                <input type="submit" class="btn btn-average" value="update average us">            
            </form>
        </div>

        <div class="col-md-3">
            <label for="periode">Periode Mdj</label>

            <form action=<?= base_url().$url_average.'/mdj' ?> method="post"> 
                <input type="hidden" name="bulan_target" value="<?= $periode_bb ?>">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <select name="periode[]" id="periode" class="form-control" required multiple>
                </select>                
                <input type="submit" class="btn btn-average" value="update average mdj">            
            </form>
        </div>


    </div>
</div>

<hr class="batas mt-3 mb-4">

<form action=<?= base_url().$url_target ?> method="post">
<div class="container mt-4">
    <div class="row mt-2 ms-5">
        <div class="col-md-12">
            <table id="test">
                <thead>
                    <tr>
                        <th colspan="4" class="text-center">-</th>
                        <th colspan="3" class="text-center">GT</th>
                        <th colspan="3" class="text-center">MT</th>
                        <th colspan="3" class="text-center">PH</th>
                    </tr>
                    <tr>
                        <th class="col-1 text-center">
                            <input type="button" class="btn btn-sm btn-custom" id="toggle"
                            value="click all" onclick="click_all_request()">
                        </th>
                        <th class="text-center">Subbranch</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Divisi</th>
                        <th class="text-center" style="width:100px">Target</th>
                        <th class="text-center">AVG Sales</th>
                        <th class="text-center">%</th>
                        <th class="text-center" style="width:100px">Target</th>
                        <th class="text-center">AVG Sales</th>
                        <th class="text-center">%</th>
                        <th class="text-center" style="width:100px">Target</th>
                        <th class="text-center">AVG Sales</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_data_target_principal->result() as $a) : ?>

                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>
                        <td><?= $a->nama_comp ?></td>
                        <td><?= $a->tahun.'-'.$a->bulan ?></td>
                        <td><?= $a->divisi ?></td>
                        <td>
                            <input type="text" class="form-control" value="<?= number_format($a->target_gt) ?>" name="target_gt[<?= $a->id ?>]" <?= $a->divisi == 'DELTOMED_ALL' ? 'readonly' : '' ?>>
                        </td>
                        <td>
                            <?= number_format($a->average_gt) ?>
                        </td>
                        <td>
                            <?= number_format($a->persen_gt,2) ?>
                        </td>
                        <td>
                            <input type="text" class="form-control" value="<?= number_format($a->target_mt) ?>" name="target_mt[<?= $a->id ?>]" <?= $a->divisi == 'DELTOMED_ALL' ? 'readonly' : '' ?>>
                        </td>
                        <td>
                            <?= number_format($a->average_mt) ?>
                        </td>
                        <td>
                            <?= number_format($a->persen_mt,2) ?>
                        </td>
                        <td>
                            <input type="text" class="form-control" value="<?= number_format($a->target_ph) ?>" name="target_ph[<?= $a->id ?>]" <?= $a->divisi == 'DELTOMED_ALL' ? 'readonly' : '' ?>>
                        </td>
                        <td>
                            <?= number_format($a->average_ph) ?>
                        </td>
                        <td>
                            <?= number_format($a->persen_ph,2) ?>
                        </td>
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
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="bulan_target" value="<?= $periode_bb ?>">
            <input type="submit" class="btn btn-generate" value="Update Data Target on Checklist" id="btnTarget" onclick="return buttonTarget()">
            <input type="submit" class="btn btn-generate" value="Export Import">
            <a href="<?= base_url().'building_block/workspace_target_principal' ?>" class="btn btn-generate">back</a>
            
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

</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>