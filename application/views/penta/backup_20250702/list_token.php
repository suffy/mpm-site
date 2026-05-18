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


<div class="row">
  <div class="container">
        <div class="code-block">
            <pre>Information !

Secara Otomatis, website akan mengupdate data penta di jam berikut :
06.00, 12.00, 15.00, dan 21.00 WIB
            </pre>
        </div>
</div>


    <div class="row mt-1">
        <div class="col-md-12">

            <div class="row mb-3">
                <div class="col-lg-12">
                    <a href="<?= base_url().'penta/get_token' ?>" class="pastel-orange-btn">Request Token</a>
                </div>
            </div>

            <?php echo form_open($url); ?>

            <div class="row mt-5">
                <div class="col-lg-2">
                    <label for="supp">Bulan</label> 
                </div>
                <div class="col-lg-3">
                    <input type="month" name="bulan" id="bulan" class="form-control" required>
                </div>
                <div class="col-lg-6">
                    <button type="submit" class="pastel-btn pastel-mint" id="btnKirim" onclick="return button()">Get data sales live</button>
                    <button class="btn btn-loading" id="btnLoading" type="button" disabled>
                    ... Please wait ...
                    </button>                    
                    <a href="<?= base_url().'penta/get_penta_stock' ?>" class="pastel-btn pastel-mint">Get data stock live</a>
                </div>
            </div>            
        </div>
    </div>



<div class="card-block mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <table id="tabel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Token</th>
                        <th>ExpiredAt</th>
                        <th>Type</th>
                        <th>CreatedAt</th>
                        <th>CreatedBy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach ($get_data->result() as $p) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= ($p->token == null) ? ' <span class="badge badge-danger">failed </span>' : substr($p->token, 0, 10) . '...' ?></td>
                        <td><?= ($p->expired_at == null) ? ' <span class="badge badge-danger">failed </span>' : $p->expired_at ?></td>
                        <td><?= ($p->token_type == null) ? ' <span class="badge badge-danger">failed </span>' : $p->token_type ?></td>
                        <td><?= $p->created_at ?></td>
                        <td><?= $p->username ?></td>
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

<script>
    function button()
    {
        
        var bulan  = document.getElementById('bulan').value;
        if (bulan) 
        {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
        $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>
