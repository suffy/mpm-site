</div>
<?php $this->load->view('management_claim/css/style') ?>
<div class="container-fluid">
    
<div class="card mb-5">
    
    <?php echo form_open($url_insert); ?>

    <?php 
    // echo "flag_result = ".$flag_result;
        if ($flag_result) { ?>
            
        <div class="card-body">
            <h5 class="card-title"><span class="btn pending-scm">Result of <?= date('M Y', strtotime($month)); ?> </span></h5>

            <div class="card-block mt-5 mb-1">
                <div class="row">
                    <div class="col-md-12">
                        <table id="generate-outlet" style="width: 100%">
                            <thead>
                                <tr>   
                                    <th class="text-center" style="text-align: center; vertical-align: middle; width: 1%;">
                                        <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" style="width: 100%; font-size: 10px; background-color: darkslategray">
                                    </th>
                                    <th style="text-align: center;">Kode</th>          
                                    <th style="text-align: center;">Nama</th>  
                                    <th style="text-align: center;">Tipe</th>  
                                    <th style="text-align: center;">Class</th>  
                                </tr>
                            </thead>
                            
                            <?php 
                                if ($get_data) { ?>
                                    
                                    <tbody>     
                                        <?php   
                                        foreach ($get_data->result() as $a):                                     
                                        ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    if ($a->status_register) { ?>
                                                        
                                                    <?php
                                                    }else{ ?>
                                                    <center>                                            
                                                        <input type="checkbox" name="kode_outlet[]" value="<?= $a->kode_outlet; ?>">
                                                    </center>
                                                    <?php
                                                    }
                                                ?>                                                
                                            </td> 
                                            <td><?= $a->kode_outlet; ?></td>
                                            <td style="text-align: left;">
                                                <div class="d-flex flex-row gap-5">
                                                    <div><?= $a->nama_outlet_fi; ?></div>
                                                    <div>
                                                        <?php 
                                                            if ($a->status_register) { ?>
                                                                <span class="pending-rilis-po" style="height: 30px; padding: 5px 10px 5px 10px; border-radius: 5px">already registered</span>
                                                            <?php
                                                            }else{ ?>
                                                                <span class="pending-scm" style="height: 30px; padding: 5px 10px 5px 10px; border-radius: 5px">available</span>
                                                            <?php
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $a->kode_type_fi; ?></td>
                                            <td><?= $a->kode_class_fi; ?></td>
                                        </tr>
                                        <?php endforeach; ?>  
                                    </tbody>
                                <?php
                                }
                            ?>
                        </table>
                        
                        <?php 
                            if($flag_button_save){ ?>
                            <div class="row mt-4">
                                <div>
                                <input type="hidden" name="site_code" value="<?= $site_code ?>">
                                <input type="submit" class="btn btn-submit-red" value="save data" style="height: 45px;">
                                <a href="<?= base_url().'management_claim/generate_master_outlet' ?>" class="btn btn-submit-black" style="height: 45px;  padding-top: 10px;">back</a>
                                </div>
                            </div>
                            <?php
                            }else{ ?>
                                <a href="<?= base_url().'management_claim/generate_master_outlet' ?>" class="btn btn-submit-black" style="height: 45px;  padding-top: 10px;">back</a>
                            <?php
                            }
                        ?>   
                      
                    </div>
                </div>
            </div>
        </div>



        <?php
        }
    ?>

</div>

<?php echo form_close(); ?>

<!-- <div class="card mt-5 mb-5">
    <div class="card-body">
        <h5 class="card-title">Data Master Outlet (ktp Lengkap)</h5>

        <div class="card-block mt-5 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <table id="master-outlet" style="width: 100%">
                        <thead>
                            <tr>   
                                <th style="text-align: center; vertical-align: middle; width: 350px;">File KTP</th>          
                                <th style="text-align: center; vertical-align: middle; width: 100px;">File NPWP</th>          
                                <th style="text-align: center;">Alamat</th>            
                            </tr>
                        </thead>
                        
                        <?php 
                            if ($get_master_outlet) { ?>
                                
                                <tbody>     
                                    <?php   
                                    foreach ($get_master_outlet->result() as $a) :                                     
                                    ?>
                                    <tr>
                                        <td>
                                            <?php if ($a->file_ktp != '') { ?>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <div>
                                                        <a href="<?= base_url('assets/uploads/management_claim/master_outlet/'.$a->file_ktp) ?>" target="_blank">
                                                            <img src="<?= base_url('assets/uploads/management_claim/master_outlet/'.$a->file_ktp) ?>" alt="<?= $a->file_ktp ?>" width="150" height="90" style="border: 2px solid black; border-radius: 5px">
                                                        </a>
                                                        <input type="hidden" name="id[]" value="<?= $a->id; ?>">
                                                        <input type="hidden" name="file_ktp[<?= $a->id; ?>]" value="<?= $a->file_ktp; ?>">
                                                        <input type="hidden" name="no_ktp[<?= $a->id; ?>]" value="<?= $a->no_ktp; ?>">
                                                        <input type="hidden" name="file_npwp[<?= $a->id; ?>]" value="<?= $a->file_npwp; ?>">
                                                        <input type="hidden" name="no_npwp[<?= $a->id; ?>]" value="<?= $a->no_npwp; ?>">
                                                    </div>                                                    
                                                    <div>
                                                        <label class="" style="width: 100%">Kode : <?= $a->kode_outlet; ?></label>
                                                        <label class="" style="width: 100%">Nama : <?= $a->nama_outlet; ?></label>
                                                        <label class="" style="width: 100%">Ktp: <?= $a->no_ktp; ?></label>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td style="text-align: left;">
                                            <?php if ($a->file_npwp != '') { ?>
                                                <div class="d-flex justify-content-left gap-2">
                                                    <div>
                                                        <a href="<?= base_url('assets/uploads/management_claim/master_outlet/'.$a->file_npwp) ?>" target="_blank">
                                                            <img src="<?= base_url('assets/uploads/management_claim/master_outlet/'.$a->file_npwp) ?>" alt="<?= $a->file_npwp ?>" width="150" height="90" style="border: 2px solid black; border-radius: 5px">
                                                        </a>
                                                    </div>                                                    
                                                    <div>
                                                        <label class="" style="width: 100%">No : <?= $a->no_npwp; ?></label>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <div style="width: 100%;">    
                                            <?php 
                                                if ($a->alamat) { ?>
                                                <label for=""><?= $a->alamat; ?><?= " Telp : ".$a->no_telp; ?></label>
                                                <?php
                                                }
                                            ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>  
                                </tbody>
                            <?php
                            }
                        ?>
                    </table>

                </div>
            </div>
        </div>


    </div>
</div> -->


<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();

        $('#generate-outlet').DataTable({
            "pageLength": 10000,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });

    });
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>