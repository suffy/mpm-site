<style>
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
    }
</style>

</div>

<div class="container">
    
<?php echo form_open_multipart($url); ?>

<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

    
</form>

</div>
</div>



<div class="container">

<div class="row mt-4">
    <div class="col-md-5">
        
        <a href="<?= base_url() ?>management_retur/export_nota_retur" class="btn btn-info">Export Nota Retur</a>
        <a href="<?= base_url() ?>management_retur/truncate_nota_retur" class="btn btn-danger">Truncate Nota Retur (Delete All)</a>
    </div>
</div>

  <div class="card-block mt-3">
        <div class="row">
            <div class="col-md-12">

                <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">customerid</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">nama_customer</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">noseri</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">noseri_beli</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">kodeprod</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">namaprod</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">qty_nota_retur</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">beli</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">jual</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">disc_cabang</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">disc_beli</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td><?= $a->customerid; ?></td>
                            <td><?= $a->nama_customer; ?></td>
                            <td><?= $a->noseri; ?></td>
                            <td><?= $a->noseri_beli; ?></td>
                            <td><?= $a->kodeprod; ?></td>
                            <td><?= $a->namaprod; ?></td>
                            <td><?= $a->qty_nota_retur; ?></td>
                            <td><?= $a->beli; ?></td>
                            <td><?= $a->jual; ?></td>
                            <td><?= $a->disc_cabang; ?></td>
                            <td><?= $a->disc_beli; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>