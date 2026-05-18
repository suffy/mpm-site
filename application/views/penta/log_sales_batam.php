<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">

        <?= $this->load->view('penta/component/sidebar');?>

<div class="az-content-body pd-lg-l-40 d-flex flex-column">
    <h2 id="form_spk"><?= $title; ?></h2>
    <div class="row">
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


<?php echo form_open($url); ?>

<div class="row mt-1">
    <div class="col-md-12">

        <?php echo form_open($url); ?>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="supp">Bulan</label> 
            </div>
            <div class="col-lg-4">
                <input type="month" name="bulan" class="form-control" value=<?= $pilih_bulan; ?>>
            </div>
            <div class="col-lg-4">
                <!-- <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Show Data</button> -->
                <button class="pastel-orange-btn" id="btnKirim" onclick="return button()">Show Data</button>
                <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                ... Please wait ...
                </button>
            </div>
        </div>            
    </div>
</div>

<?php echo form_close(); ?>


<div class="card-block mb-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Closing</th>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Total Gross</th>
                        <th>Total Net</th>
                        <th>Created_at</th>
                        <th>Created_by</th>
                        <th>Token</th>
                        <th></th>
                        <!-- <th>Signature</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>                          
                          <a href="<?= base_url().'penta/update_status/'.$p->signature ?>" class="btn btn-submit status pending-rilis-po" style="font-size:14px"><?= ($p->flag_closing == 1) ? '<span class="badge badge-success">Closing</span>' : 'False' ?></a>
                        </td>
                        <td><?= $p->tahun ?></td>
                        <td><?= $p->bulan ?></td>
                        <td>                         
                            <?php 
                                if(strpos($p->signature, 'penta-sales-ext') !== false) { ?>
                                    <label class="status pending-finance" style="font-size:14px">Ini data outlet</label>
                                <?php
                                }else{ ?>
                                    <label class="status pending-rilis-po" style="font-size:14px"><?= number_format($p->total_gross,0) ?></label>
                                <?php
                                }
                            ?>
                        </td>
                        <td>
                            
                            <?php 
                                if(strpos($p->signature, 'penta-sales-ext') !== false) { ?>
                                    <label class="status pending-finance" style="font-size:14px">Ini data outlet</label>
                                <?php
                                }else{ ?>
                                    <label class="status pending-rilis-po" style="font-size:14px"><?= number_format($p->total_net,0) ?></label>
                                <?php
                                }
                            ?>
                        </td>
                        <td><?= $p->created_at ?></td>
                        <td><?= $p->username ?></td>
                        <td><?= substr($p->token,0,10).'...' ?></td>
                        <td>
                            <a href="<?= base_url().'penta/export_sales/'.$p->signature ?>" class="pastel-btn pastel-mint">Download</a>
                        </td>
                        <!-- <td><?= $p->signature ?></td> -->
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel').DataTable({
            "pageLength": 1000,
            "ordering": false,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : false,
            "bPaginate": false
        });
    });

   
</script>
