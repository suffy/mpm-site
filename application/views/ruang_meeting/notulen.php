<style>
    td {
        text-align: center;
        font-size: 17px;
        height: 40px;
    }

    th {
        text-align: center;
        font-size: 20px;
    }

    /* .select2-selection {
    height: 34px !important; 
    font-size: 13px;
    font-family: 'Open Sans', sans-serif;
    border-radius: 0 !important;
    border: solid 1px #c4c4c4 !important;
    padding-left: 4px;
    } */

    .select2-selection {
        /* height: 34px !important;  */
        font-size: 13px;
        /* font-family: 'Open Sans', sans-serif; */
        border-radius: 0 !important;
        border: solid 1px #c4c4c4 !important;
        padding-left: 4px;
        /* color: 'red'; */
        /* background-color: 'red'; */
        color: #000;
        background-color: #000;
    }

    /* .select2-selection--multiple {
    height: 154px !important;
    width: 366px !important;
    } */

    /* .select2-selection__choice {
    height: 40px;
    line-height: 40px;
    padding-right: 16px !important;
    padding-left: 16px !important;
    background-color: #CAF1FF !important;
    color: #333 !important;
    border: none !important;
    border-radius: 3px !important;
    } */
    .select2-selection__choice {
    /* height: 40px;
    line-height: 40px;
    padding-right: 16px !important;
    padding-left: 16px !important; */
    background-color: #CAF1FF !important;
    color: #333 !important;
    border: none !important;
    border-radius: 3px !important;
    }

    /* .select2-search--inline .select2-search__field {
    line-height: 40px;
    color: #333;
    } */

    /* .select2-container:hover,
    .select2-container:focus,
    .select2-container:active,
    .select2-selection:hover,
    .select2-selection:focus,
    .select2-selection:active {
    outline-color: transparent;
    outline-style: none;
    } */

    /* .select2-results__options li {
    display: block; 
    } */

    /* .select2-selection__rendered {
    transform: translateY(2px);
    } */

    /* .select2-selection__arrow {
    display: none;
    } */

    /* .select2-results__option--highlighted {
    background-color: #CAF1FF !important;
    color: #333 !important;
    } */

    /* .select2-dropdown {
    border-radius: 0 !important;
    box-shadow: 0px 3px 6px 0 rgba(0,0,0,0.15) !important;
    border: none !important;
    margin-top: 4px !important;
    width: 366px !important;
    } */

    /* .select2-results__option {
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    line-height: 24px !important;
    vertical-align: middle !important;
    padding-left: 8px !important;
    } */

    /* .select2-results__option[aria-selected="true"] {
    background-color: #eee !important; 
    } */

    /* .select2-search__field {
    font-family: 'Open Sans', sans-serif;
    color: #333;
    font-size: 13px;
    padding-left: 8px !important;
    border-color: #c4c4c4 !important;
    } */

    /* .select2-selection__placeholder {
    color: #c4c4c4 !important; 
    } */
}

</style>

</div>

<div class="container-fluid">
    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('absensi/component/sidebar');?>

                <div class="col ml-5">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><?= $title ?></h3>
                        </div>
                    </div>

                    <!-- SESSION FLASH-->
                    <div class="row mt-2">
                        <div class="col-8">
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
                    <!-- END SESSION FLASH -->


                   

                  <?php echo form_open_multipart($url); ?>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label >Peserta Meeting (*)</label>
                                </div>
                                <div class="col-md-6">
                                <?php 
                                    if ($get_notulen) 
                                    { 
                                        $peserta = explode(",", $get_notulen->row()->peserta);
                                        $no = 1;
                                        foreach ($peserta as $key => $value) {
                                            $username = $this->model_ruang_meeting->get_username_by_id($value);
                                            echo $no++.'. '.$username->row()->username.' ('.$username->row()->email.')';
                                            echo "<br>";
                                        }
                                    }else{ ?>
                                        <select id="peserta" name="peserta[]" class="form-control peserta" multiple="multiple" required></select>
                                    <?php
                                    }
                                ?>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label >Isi Notulen (*)</label>
                                </div>
                                <div class="col-md-6">
                                    <?php 
                                        if ($get_notulen) 
                                        { 
                                            echo $get_notulen->row()->isi_notulen;
                                        }else{ ?>
                                            <textarea name="notulen" id="notulen" class="form-control" cols="10" rows="5" required></textarea>
                                        <?php
                                        }
                                    ?>    
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label >Attachment</label>
                                </div>
                                <div class="col-md-6">
                                    <?php 
                                        if ($get_notulen && $get_notulen->row()->file) 
                                        {?>                                                            
                                            <a href="<?= base_url() ?>assets/uploads/ruang_meeting/<?= $get_notulen->row()->file ?>" class="btn btn-submit-black">download file</a>                            
                                        <?php
                                        }elseif ($get_notulen && !$get_notulen->row()->file) {
                                            echo 'no file';
                                        }
                                        else{ ?>
                                            <input type="file" class="form-control" name="attachment">
                                        <?php
                                        }
                                    ?>  
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-8">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-6">
                                    

                                    <?php 
                                        if (!$get_notulen) 
                                        {?>             
                                            <input type="hidden" name="signature" value="<?= $signature ?>">
                                            <input type="submit" value="Simpan" class="btn pastel-orange-btn">                                               
                                            <a href="<?= base_url() ?>ruang_meeting" class="btn btn-submit-black" style="width: 70px;">back</a>                            
                                        <?php
                                        }
                                    ?>   
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php echo form_close(); ?>     
                    

                    <div class="row mt-1">
                        <div class="col-md-8">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-6">
                    
                                    <?php 
                                        if ($get_notulen) 
                                        {?>                     
                                            <?php echo form_open($url_delete); ?>     
                                            <input type="hidden" name="signature" value="<?= $signature ?>">
                                            <input type="hidden" name="id_notulen" value="<?= $get_notulen->row()->id ?>">
                                            <input type="submit" value="Delete" class="btn pastel-orange-btn">
                                            <a href="<?= base_url() ?>ruang_meeting" class="btn btn-submit-black" style="width: 70px;">back</a>
                                        <?php
                                        }
                                    ?>   


                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-data').DataTable({
            "searching": false,
            "info": false,
            "paging": false,
        });
    });

    $(document).ready(function () {
        $('#tabel-data2').DataTable({
            "searching": false,
            "info": false,
            "paging": false,
        });
    });
</script>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<!-- fungsi js select supp -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $(".peserta").select2({
            placeholder: "-- Silahkan Pilih --"
        });
    });
</script>
<!-- end fungsi js select supp -->

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_user_mpm') ?>',
        data: '',
        success: function(result) {
            $("select[id = peserta]").html(result);
        }
    });
</script>