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

</form>

    <?= form_open($url); ?>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="nama_program">Periode Program</label>
        </div>
        <div class="col-md-5">
            <select name="nama_program" class="form-control" required>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="branch">Subbranch</label>
        </div>
        <div class="col-md-5">
            <select name="site_code" class="form-control" required>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2"></div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-danger">next to pengisian tracking</button>
        </div>
    </div>
    
    <?= form_close();?>
    
    <hr>

    <div class="row mt-5">
        <div class="col-md-12 az-content-label">
            Log Pengisian Tracking
        </div>
    </div>
    

    <div class="row mt-5">
        <div class="col-md-12 d-flex justify-content-center">
            <div class="form-inline row">
                <div class="col-sm-12">
                    <form action="<?= $url_export ?>" method="POST">
                        <select name="nama_program" class="form-control" required>
                        </select>
                        <button type="submit" value="2" class="btn btn-outline-info btn-sm" name="type">Export To CSV</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12">
            <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
                <thead>
                    <tr>
                        <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">Program</th>
                        <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">Subbranch</th>
                        <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">nodo</th>
                        <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">tgldo</th>
                        <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">kodeprod</th>
                        <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">qty_penggantian</th>
                        <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">keterangan</th>
                        <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">status</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    // var_dump($get_draft_nota_retur);
                    // die;
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $a->nama_program; ?></td>
                        <td><?= $a->nama_comp; ?></td>
                        <td>                            
                            <a href="<?= base_url().'management_bonus/tracking_edit/'.$a->nodo.'/'.$a->signature ?>" class="btn btn-info btn-sm"><?= $a->nodo; ?></a>
                        </td>
                        <td><?= $a->tgldo; ?></td>
                        <td><?= $a->kodeprod; ?></td>
                        <td><?= $a->qty_penggantian; ?></td>
                        <td><?= $a->keterangan; ?></td>
                        <td>
                            <?= ($a->closed == 1) ? 'CLOSED' : 'OPEN' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>
<hr>
    <div class="row mt-5">
        <div class="col-md-12">
            <table id="example2" class="display" style="display: inline-block; overflow-y: scroll">
                <thead>
                    <tr>
                        <th style="background-color: maroon;" class="text-center col-1"><font color="white">No</th>
                        <th style="background-color: maroon;" class="text-center col-2"><font color="white">Program</th>
                        <th style="background-color: maroon;" class="text-center col-2"><font color="white">Subbranch</th>
                        <th style="background-color: maroon;" class="text-center col-1"><font color="white">Kodeprod</th>
                        <th style="background-color: maroon;" class="text-center col-2"><font color="white">Namaprod</th>
                        <th style="background-color: maroon;" class="text-center col-1"><font color="white">qty bonus</th>
                        
                        <?php 
                            foreach ($get_nodo->result() as $key) { ?>
                                
                                <th style="background-color: darkgreen;" class="text-center col-1"><font color="white">
                                    <?= $key->nodo; ?>
                                </th>

                            <?php
                            }
                        ?>
                        <th style="background-color: darkgreen;" class="text-center col-1"><font color="white">sisa</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php $no = 1;
                        foreach ($get_body->result() as $b) { ?>
                           
                           <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $b->nama_program ?></td>
                                <td><?= $b->nama_comp ?></td>
                                <td><?= $b->kodeprod ?></td>
                                <td><?= $b->namaprod ?></td>
                                <td><?= $b->qty_bonus ?></td>
                                <?php 
                                    foreach ($get_nodo->result() as $key) { ?>                                        
                                        <td class="col-2">
                                            
                                            <?php 
                                                if ($this->model_management_bonus->get_qty_single($key->nodo, $b->site_code, $b->kodeprod)->num_rows() > 0) { 
                                                    echo $this->model_management_bonus->get_qty_single($key->nodo, $b->site_code, $b->kodeprod)->row()->qty;
                                                }else{
                                                    echo "-";
                                                }
                                            ?>
                                        </td>
                                    <?php
                                    }
                                ?>
                                <td><?= $b->sisa ?></td>

                            </tr>
                           
                        <?php
                        }
                    ?>
                        <!-- <tr>
                            <td>no</td>
                            <td>site_code</td>
                            <td>kodeprod</td>
                            <?php 
                                foreach ($get_nodo->result() as $key) { ?>
                                    
                                    <td class="col-2">
                                        <?= $key->nodo; ?>
                                    </td>

                                <?php
                                }
                            ?>
                        </tr> -->
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <br>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 10,
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
        $("#example2").DataTable({
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
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/nama_comp_bonus') ?>',
        data: '',
        success: function(hasil_branch) {
            $("select[name = site_code]").html(hasil_branch);
        }
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/nama_program_bonus') ?>',
        data: '',
        success: function(hasil_branch) {
            $("select[name = nama_program]").html(hasil_branch);
        }
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
