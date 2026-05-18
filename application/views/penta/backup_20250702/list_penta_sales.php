<?php echo form_open($url); ?>

<div class="az-content">
    <div class="container-fluid">

        <?= $this->load->view('penta/component/sidebar');?>

<div class="az-content-body pd-lg-l-40 d-flex flex-column">
    <h2 class="az-content-title" id="form_spk"><?= $title; ?></h2>
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

    <div class="row mt-1">
        <div class="col-md-12">
            <a href="<?= base_url().'penta/get_token' ?>" class="btn btn-submit-black">Generate Token</a>
        </div>
    </div>


<div class="card-block mt-1 mb-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Token</th>
                        <th>ExpiredAt</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $p) : ?>
                    <tr>
                        
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
