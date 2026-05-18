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

        <div class="row mt-1">
            <div class="col-md-12">

                <?php echo form_open($url); ?>

                <div class="row mt-3">
                    <div class="col-lg-2">
                        <label for="supp" >Bulan</label> 
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
                
                <?php echo form_close(); ?>            
            </div>
        </div>
    </div>
    </div>
</div>



<script>
    function button()
    {
        $("#btnKirim").hide();
        $("#btnBack").hide();
        $("#btnLoading").show();
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>