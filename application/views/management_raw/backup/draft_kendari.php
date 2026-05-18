<style>

td {
  text-align: left;
  font-size: 12px;
}

th {
  text-align: left;
  font-size: 13px;
}

</style>

<?php echo form_open_multipart($url); ?>
<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>

    <div class="card-block">    
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>distributor</th>
                            <th>cabang</th>
                            <th>kodeproduk</th>
                            <th>namaproduk</th>
                            <th>kodecustomer</th>
                            <th>namacustomer</th>
                            <!-- <th>alamat</th>
                            <th>kodesalesman</th>
                            <th>namasalesman</th> -->
                            <th>tahun-bulan</th>
                            <th>grossamount</th>
                            <!-- <th>netamount</th> -->
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_raw_draft->result() as $a) : ?>
                        <tr>
                            <!-- <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $a->id; ?>" name="options[]" class = "<?php echo $a->id; ?>" value="<?php echo $a->id; ?>">
                                </center>
                            </td> -->
                            <td><?= $a->NAMAAREA; ?></td>
                            <td><?= $a->AREA; ?></td>
                            <td><?= $a->KODEPROD; ?></td>
                            <td><?= $a->NAMAPROD; ?></td>
                            <td><?= $a->KODELANG; ?></td>
                            <td><?= $a->NAMALANG; ?></td>
                            <!-- <td><?= $a->ALMTLANG; ?></td>
                            <td><?= date($a->TGLDOKJDI); ?></td>
                            <td><?= $a->namasalesman; ?></td> -->
                            <td><?= date("Y - m" ,strtotime($a->TGLDOKJDI)); ?></td>
                            <td><?= $a->TOTHNA; ?></td>
                            <!-- <td><?= $a->netamount; ?></td> -->
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <?php echo form_submit('submit', 'lanjut proses mapping', 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
        
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [1000, 2000, -1],
                [1000, 2000, "All"]
            ],
        });
    });
</script>