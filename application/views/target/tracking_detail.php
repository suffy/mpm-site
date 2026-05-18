<style>
        h1,
        h3 {
            text-align: center;
        }
 
        table {
            border-spacing: 0px;
            table-layout: fixed;
            margin-left: auto;
            margin-right: auto;
        }

        td {
            word-wrap: break-word;
        }
    </style>
<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">
    <?= $this->load->view('target/component/sidebar');?>

<div class="row mt-1">
    <div class="col-md-12">

        <?php echo form_open($url); ?>

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="kode_outlet" class="form-label">Kode Outlet</label> 
            </div>
            <div class="col-lg-4">
                <input type="text" class="form-control" name="kode_outlet" id="kode_outlet" required>
            </div>
        </div> 

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="target_value" class="form-label">Target Value</label> 
            </div>
            <div class="col-lg-4">
                <input type="text" class="form-control" name="target_value" id="target_value" required>
            </div>
        </div> 

        <div class="row mt-3">
            <div class="col-lg-2">
                <label for="target_value" class="form-label">Product</label> 
            </div>
            <div class="col-lg-4">
                <textarea name="kodeprod" id="kodeprod" class="form-control" cols="30" rows="10"></textarea>
            </div>
        </div> 

        <div class="row mt-3">
            <div class="col-lg-2">
                
            </div>
            <div class="col-lg-6">
                <input type="hidden" class="form-control" name="id_tracking" id="id_tracking" value="<?= $id_tracking ?>">
                <input type="hidden" class="form-control" name="signature" id="signature" value="<?= $signature ?>">
                <button class="pastel-orange-btn" id="btnKirim" onclick="return button()">Submit Data</button>
                <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                ... Please wait ...
                </button>

                <a href="<?= base_url().'target_outlet/master_tracking/' ?>" class="btn btn-submit-black" role="button">Kembali</a>

            </div>
        </div> 

        <?= form_close(); ?>

    </div>
</div>
</div>
</div>



<div class="card-block mt-5 mb-5 ms-2">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel" class="datatable" width="100%">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Kode Outlet</th>
                        <th>Nama Outlet</th>
                        <th>Type</th>
                        <th>Class</th>
                        <th width="20%">Kodeprod</th>
                        <th>Target value</th>
                        <th>CreatedAt</th>
                        <th>CreatedBy</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td class="text-center" width="1%"><?= $no++ ?></td>
                        <td><?= $a->kode_outlet ?></td>
                        <td>
                            <?php 
                                if ($a->nama_outlet) { ?>
                                    <?= $a->nama_outlet ?>
                                <?php
                                }else{ ?>
                                    <a href="<?= base_url() ?>target_outlet/generate_tracking_detail/<?= $a->signature ?>" class="btn btn-submit-black">Generate</a>
                                <?php
                                }
                            ?>
                        </td>
                        <td><?= $a->kode_type ?></td>
                        <td><?= $a->kode_class ?></td>
                        <td>
                            <?= (strlen($a->kodeprod) > 100) ? substr($a->kodeprod, 0, 100).'...' : $a->kodeprod ?>
                        </td>
                        <td>
                            <?php echo form_open($url_update_target_value); ?>
                            <div class="btn-group">
                                <input type="number" name="target_value" id="target_value" value="<?= $a->target_value ?>" class="form-control">
                                <input type="submit" value="✔️" class="btn btn-submit-black">
                            </div>
                            <?= form_close(); ?>
                        </td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->username ?></td>
                        <td>
                            <a href="<?= base_url('target_outlet/delete_tracking_detail/'.$a->signature) ?>" class="delete-button" onclick="return confirm('Hapus data ini ?')">del</a> 
                        </td>
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
            "pageLength": 10,
            "ordering": false,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : true,
            "bPaginate": true
        });
    });
</script>

<script>
    function button()
    {
        let kode_outlet = document.getElementById('kode_outlet').value;
        let target_value = document.getElementById('target_value').value;
        let kodeprod = document.getElementById('kodeprod').value;

        if (kode_outlet && target_value && kodeprod) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>