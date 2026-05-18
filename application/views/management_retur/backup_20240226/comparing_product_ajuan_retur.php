<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
    }
</style>

</div>

<div class="container">

<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        Branch : <?= $branch_name ?>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">
        SubBranch : <?= $nama_comp ?>
    </div>
</div>
    
</form>



<?= form_open($url); ?>

    <div class="card-block mt-3">
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="display" style="display: inline-block; overflow-y: scroll" width="100%">
                    <thead>
                        <tr>
                            <th colspan="10" style="background-color: darkslategray;" class="text-center"><font color="white"><strong><i>Data Original Ajuan Retur (setelah di sum)</i></strong></font></th>
                            <th colspan="1" class="text-center">#</font></th>
                        </tr>
                        <tr>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">Kodeprod</th>
                            <th style="background-color: darkslategray;" class="text-center col-md-3"><font color="white">Namaprod</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">BatchNumber</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">ED</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">Jumlah</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">Alasan</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">Satuan</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">Outlet</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">Keterangan</th>
                            <th style="background-color: darkslategray;" class="text-center"><font color="white">QtyLPK</th>
                            <th class="text-center">Qty LPK</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_product_ajuan_retur->result() as $a) : 
                        if ($versi == 2) {
                            $jumlah = $a->qty_approval;
                        } else {
                            $jumlah = $a->jumlah;
                        }?>
                            <!-- # versi baru jumlah dirubah jadi qty approval -->
                            <tr>
                                <td><?= $a->kodeprod; ?></td>                            
                                <td><?= $a->namaprod; ?></td>
                                <td><?= $a->batch_number; ?></td>
                                <td><?= $a->expired_date; ?></td>
                                <td><?= $jumlah; ?></td>
                                <td><?= $a->alasan; ?></td>
                                <td><?= $a->satuan; ?></td>
                                <td><?= $a->nama_outlet; ?></td>
                                <td><?= $a->keterangan; ?></td>
                                <td>
                                    <?= ($a->qty_lpk) ? $a->qty_lpk : '<font color="red"><i>NULL</i></font>' ?>
                                    <!-- <?= $a->qty_lpk; ?> -->
                                </td>
                                <td>
                                    <input type="hidden" name="id[]" value="<?= $a->id; ?>" size="3">
                                    <input type="number" name="qty_lpk[]" value="<?= ($a->qty_lpk != NULL && $a->qty_lpk != 0) ? $a->qty_lpk : $jumlah ?>" size="2">
                                </td>
                            </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-md-5">  
            <input type="hidden" name="signature_ajuan_retur" value="<?= $signature_ajuan_retur ?>">
            <button type="submit" class="btn btn-info">Update Qty LPK dan Lanjut ke Create Draft Nota Retur</button>
        </div>
    </div>
    
    <?= form_close();?>

    <br>
    <br>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 1000,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });
</script>

<script>
      $(document).ready(function () {
        $("#table-sum").DataTable({
            "pageLength": 1000,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // "fixedHeader": {
            //     header: true,
            //     footer: true
            // }
        });
      });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/branch') ?>',
        data: '',
        success: function(hasil_branch) {
            $("select[name = branch]").html(hasil_branch);
        }
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
