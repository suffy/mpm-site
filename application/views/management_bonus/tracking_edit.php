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

</form>

<div class="row mb-5">
    <div class="col-md-12 text-center">

    <?= form_open($url_status); ?>

        <input type="hidden" name="site_code" value="<?= $get_data->row()->site_code ?>">
        <input type="hidden" name="nama_program" value="<?= $get_data->row()->nama_program ?>">
        <input type="hidden" name="signature" value="<?= $get_data->row()->signature ?>">

        <input type="submit" class="btn btn-<?= ($get_data->row()->closed == null || $get_data->row()->closed == 0) ? 'info' : 'dark'  ?>" value="<?= ($get_data->row()->closed == null || $get_data->row()->closed == 0) ? 'status saat ini : OPEN' : 'status saat ini : CLOSED'  ?>">

    <?= form_close();?>

    </div>
</div>

<?= form_open($url); ?>

    <div class="card-block">
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th style="background-color: darkgreen;" class="text-center col-1"><font color="white">Program</th>
                            <!-- <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">Branch</th> -->
                            <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">Subbranch</th>
                            <th style="background-color: darkgreen;" class="text-center col-1"><font color="white">Kodeprod</th>
                            <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">Namaprod</th>
                            <th style="background-color: darkgreen;" class="text-center col-1"><font color="white">Qty Bonus</th>
                            <th style="background-color: maroon;" class="text-center col-1"><font color="white">Qty Sisa</th>
                            <th style="background-color: maroon;" class="text-center col-1"><font color="white">Qty Penggantian</th>
                            <th style="background-color: maroon;" class="text-center col-2"><font color="white">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        // var_dump($get_draft_nota_retur);
                        // die;
                        foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td><?= $a->nama_program; ?></td>
                            <!-- <td><?= $a->branch_name; ?></td> -->
                            <td><?= $a->nama_comp; ?></td>
                            <td><?= $a->kodeprod; ?></td>
                            <td><?= $a->namaprod; ?></td>                            
                            <td><?= $a->qty_bonus; ?></td>                            
                            <td><?= $a->sisa; ?></td>                            
                            <td>
                                <input type="hidden" name="kodeprod[]" value="<?= $a->kodeprod ?>" size="10">
                                <input type="number" name="qty_penggantian[]" value="<?= $a->qty_penggantian; ?>" size="10">
                            </td>
                            <td>
                                <textarea name="keterangan[]" cols="20" rows="2"><?= $a->keterangan; ?></textarea>
                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-3">
                <label for="customerid">Nomor DO</label>
            </div>
            <div class="col-md-5">
                <input type="hidden" name="nama_program" class="form-control" value="<?= $nama_program ?>">
                <input type="hidden" name="site_code" class="form-control" value="<?= $site_code ?>">
                <input type="text" name="nodo" class="form-control" value="<?= $nodo ?>">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-3">
                <label for="customerid">Tgl DO</label>
            </div>
            <div class="col-md-5">
                <input type="date" name="tgldo" class="form-control" value="<?= $tgldo ?>">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-3">
               
            </div>
            <div class="col-md-5">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <input type="submit" value="Update Tracking" class="btn btn-danger">
            </div>
        </div>

        <br><br>
        <?= form_close();?>

    </div>



<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 200,
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

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
