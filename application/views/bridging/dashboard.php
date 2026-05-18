</div>
<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
    </div>

    <div class="row">
        <div class="col-md-12 text-center">
            <?php 
                if($this->session->flashdata('pesan')){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('pesan'); ?>
                    </div>
                <?php
                }elseif($this->session->flashdata('pesan_success')){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('pesan_success'); ?>
                    </div>
                <?php
                }
            ?>
        </div>
    </div>

    <div class="card-block">
        <div class="row">
            <div class="col-md-12">
                <table id="tabel" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">no</th>         
                            <th class="text-center">site_code</th>         
                            <th class="text-center">branch</th>   
                            <th class="text-center">subbranch</th>   
                            <th class="text-center">Upload Sales</th>
                            <th class="text-center">Upload Stock</th>   
                        </tr>
                    </thead>
                    <tbody>  
                        <?php 
                        $no = 1;
                        foreach ($site_code->result() as $a) : ?>  
                            <tr>
                                <td class="text-center"><?= $no++ ?></td> 
                                <td class="text-center"><?= $a->site_code ?></td> 
                                <td class="text-center"><?= $a->branch_name ?></td> 
                                <td class="text-center"><?= $a->nama_comp ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url().'bridging/routing/'.$a->signature ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-upload me-1"></i>Upload Form
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url().'bridging/routing_stock/'.$a->signature ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-upload me-1"></i>Upload Stock
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?> 
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });

    $.ajax({ 
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_kategori') ?>',
        success: function(result) {
            $("select[name = kategori]").html(result);
        }
    });
</script>

</body>
</html>