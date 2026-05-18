<div class="az-content-left az-content-left-components">
    <div class="component-item">
        <a href="<?= base_url().'target_outlet/dashboard_loyalty' ?>" class="nav-link"><label><strong>Dashboard Loyalty</label></strong></a>

        <nav class="nav flex-column"> 
            <a href="<?= base_url().'target_outlet/master_tracking' ?>" class="nav-link-new">Master Tracking</a>
            <a href="<?= base_url().'target_outlet/master_outlet' ?>" class="nav-link-new">Master Outlet</a>
        </nav>
        <hr>
        <a href="<?= base_url().'target_outlet/dashboard_po' ?>" class="nav-link"><label><strong>Dashboard PO</label></strong></a>
        <a href="<?= base_url().'target_outlet/dashboard_retur' ?>" class="nav-link"><label><strong>Dashboard Retur</label></strong></a>
        <a href="<?= base_url().'target_outlet/dashboard_claim' ?>" class="nav-link"><label><strong>Dashboard Claim</label></strong></a>
        <a href="<?= base_url().'target_outlet/kalender_data' ?>" class="nav-link"><label><strong>Kalender Data</label></strong></a>

    </div>
</div>


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