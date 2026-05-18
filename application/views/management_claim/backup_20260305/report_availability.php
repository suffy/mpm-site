
<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title" id="master_region"><?= $title ?></h5>

        <div class="row mt-3">
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

        <?php echo form_open_multipart($url); ?>

        <div class="row mt-5">
            <div class="col-lg-2">
                <label for="supp">Principal</label> 
            </div>
            <div class="col-lg-4">
                <select id="supp" name="supp" class="form-control custom-input" required>
                    <option value="">Principal ?</option>
                    <?php foreach ($get_principal->result() as $a) { ?>
                        <option value="<?= $a->supp ?>"><?= $a->namasupp ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-2">
                <label for="kategori">Kategori</label>
            </div>
            <div class="col-lg-4">
                <select id="kategori" name="kategori" class="form-control custom-input" required>
                </select>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-2">
                <label for="from">Periode Program</label>
            </div>
            <div class="col-lg-4 d-flex flex-row">
                <input class="form-control form-control-md custom-input" id="from" type="date" name="from" required>
                <input class="form-control form-control-md custom-input" id="to" type="date" name="to" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-2"></div>
            <div class="col-lg-5 d-flex flex-row">
                <button type="submit" class="btn btn-submit-red">Search</button>
            </div>
        </div>  

        <?php echo form_close(); ?>

        <?php echo form_open_multipart($url_search); ?>

        <div class="row mt-5">
            <div class="col-lg">
                <table id="table" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" style="color: black; background-color: grey">
                            </th>
                            <th class="text-center" style="color: var(--bs-dark-text-emphasis); background-color: var(--bs-dark-bg-subtle);font-size: 13px">Principal</th>
                            <th class="text-center" style="color: var(--bs-dark-text-emphasis); background-color: var(--bs-dark-bg-subtle);font-size: 13px">Kategori</th>
                            <th class="text-center" style="color: var(--bs-dark-text-emphasis); background-color: var(--bs-dark-bg-subtle);font-size: 13px">NomorSurat</th>
                            <th class="text-center" style="color: var(--bs-dark-text-emphasis); background-color: var(--bs-dark-bg-subtle);font-size: 13px">NamaProgram</th>
                            <th class="text-center" style="color: var(--bs-dark-text-emphasis); background-color: var(--bs-dark-bg-subtle);font-size: 13px">Periode</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php
                        if($get_program==[]) {
                        }else{
                            foreach ($get_program->result() as $a) : ?>
                            <tr>
                                <td>
                                    <center>
                                    <input type="checkbox" id="<?= $a->id; ?>" name="options[]" value="<?= $a->id; ?>">
                                    </center>
                                </td> 
                                <td><?= $a->namasupp; ?></td>
                                <td><?= $a->nama_kategori; ?></td>
                                <td><?= $a->nomor_surat; ?></td>
                                <td><?= $a->nama_program; ?></td>
                                <td><?= $a->from.' - '.$a->to; ?></td>
                                
                            </tr>
                            <?php endforeach; ?>  

                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
            if($get_program==[]) { 
            }else{ ?>
                
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit" style="height: 44px; width:100%">Search</button>
                    </div>
                </div>

            <?php
            } ?>
        <?php echo form_close(); ?>

    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#table').DataTable({
            "pageLength": 10,
            "ordering": false,
            // "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });


    $("select[name = supp]").on("change", function() 
    {    
        let supp = document.getElementById('supp').value;            
        console.log('supp ' + supp)

        $.ajax({
            
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_kategori') ?>',
            success: function(result) {
                $("select[name = kategori]").html(result);
            }
        });
        
    });
    
</script>

 <script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>