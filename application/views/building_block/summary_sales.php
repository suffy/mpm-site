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
        line-height: 13px;
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
<div class="container">
    <div class="row mt-1 ms-5">
        <div class="col-md-3">
            <label for="tahun">Generate Summary</label>
        </div>
        <div class="col-md-3">
            <input type="month" class="form-control" id="bulan" name="bulan" min="2023-01" max="2024-12" required>
        </div>
        <div class="col-md-6">
            <input type="submit" class="btn btn-submit" value="Generate Summary Sales" id="btnKirim" onclick="return button()">
            <button class="btn btn-info" id="btnLoading" type="button" disabled>
            ... Generating Data .. Mohon menunggu ...
            </button>
        </div>
    </div>
</div>
</form>

<hr>

<form action=<?= base_url().$url_search ?> method="get">
<div class="container mt-3">
    <div class="row mb-1">            
        <div class="col-md-3">
            <label for="bulan_target">Show Summary Sales</label>
        </div>
        <div class="col-md-3">
            <input type="month" class="form-control" id="bulan_target" name="bulan_target" min="2023-01" max="2024-12" value="<?= $this->input->get('bulan_target') ?>" required>
        </div>
        <div class="col-md-6">
            <input type="submit" class="btn btn-submit" value="Show Summary Sales" id="btnSearch" onclick="return buttonSearch()">            
        </div>
    </div>
</div>
</form>

<div class="container mt-4">
    <div class="row mt-2 ms-5">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th colspan="5" class="text-center">-</th>
                        <th colspan="3" class="text-center">Bruto</th>
                        <th colspan="1" class="text-center">-</th>
                    </tr>
                    <tr>
                        <th class="text-center">Sitecode</th>
                        <th class="text-center">Branch</th>
                        <th class="text-center">Subbranch</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Divisi</th>
                        <th class="text-center">GT</th>
                        <th class="text-center">MT</th>
                        <th class="text-center">PH</th>
                        <th class="text-center">CreatedAt</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_raw_data->result() as $a) : ?>

                    <tr>
                        <td><?= $a->site_code ?></td>
                        <td><?= $a->branch_name ?></td>
                        <td><?= $a->nama_comp ?></td>
                        <td><?= $a->tahun.'-'.$a->bulan ?></td>
                        <td><?= $a->divisi ?></td>
                        <td><?= number_format($a->bruto_gt) ?></td>
                        <td><?= number_format($a->bruto_mt) ?></td>
                        <td><?= number_format($a->bruto_ph) ?></td>
                        <td><?= $a->created_at ?></td>
                    </tr>
                    
                    <?php endforeach; ?>   
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
    function button()
    {        
        $("#btnKirim").hide();
        $("#btnLoading").show();
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>