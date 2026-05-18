</div>
<div class="container-fluid">

<div class="row mb-4">
    <div class="col-md-12">
        <h4><?= $title ?></h4>
    </div>
</div>

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