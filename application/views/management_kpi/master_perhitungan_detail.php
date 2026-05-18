</div>

<div class="container-fluid">

<div class="row mt-1">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
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
<?php 
echo form_open_multipart($url); 
?>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="kpi">Input KPI</label>
    </div>
    <div class="col-md-4">
        <input type="number" class="form-control" name="kpi" required>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="point">Input Point</label>
    </div>
    <div class="col-md-4">
        <input type="number" class="form-control" name="point" required>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-2">
        <label for="nama_program"></label>
    </div>
    <div class="col-md-4">
        <input type="hidden" class="form-control" name="id_header" value="<?= $id_header ?>">
        <input type="hidden" class="form-control" name="signature" value="<?= $signature ?>">
        <input type="submit" value="Submit Data" class="btn btn-submit-black">
        <a href="<?= base_url() ?>kpi/master_data#master-perhitungan" class="btn btn-submit-black">Back</a>
    </div>
</div>

</form>

<hr style="border: 1px solid black; box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);" class="mt-5">

<div class="row mt-5 mb-5">
    <div class="col-md-12">
        <table id="table-data">
            <thead>
                <tr>
                    <th style="width: 10px;">No</th>
                    <th style="width: 50px;">Category</th>
                    <th style="width: 200px;">Parameter</th>
                    <th style="width: 50px;">Min Target</th>
                    <th style="width: 50px;">KPI</th>
                    <th style="width: 50px;">Point</th>
                </tr>
            </thead>
            <tbody>     
                <?php 
                $no = 1;
                foreach ($get_data->result() as $a) : ?>
                <tr>
                    <td align ="center"><?= $no++ ?></td>
                    <td><?= $category ?></td>
                    <td><?= $parameter ?></td>
                    <td><?= $min_target ?></td>
                    <td><?= $a->kpi ?></td>
                    <td><?= $a->point ?></td>
                </tr>
                <?php endforeach; ?>   
            </tbody>
        </table>
    </div>
</div>
    

<script>
    $(document).ready(function () 
    {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#table-data').DataTable(
        {
            "pageLength": 10,
            "ordering": true,
            "order": [9, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

</script>