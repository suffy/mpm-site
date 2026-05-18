<?php
$this->load->view('management_claim/monitoring_form');
?>

<?php 
    foreach ($data->result() as $group) { ?>

        <div class="container-fluid mt-4 mb-4">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-4 mt-4">
                        <div class="col-lg-8">
                            <h4>status : <?= $group->nama_status_internal.' ('.$group->count.')'; ?></h4>
                        </div>
                    </div>

                    <div class="row">
                        <?php 
                            $get_detail = $this->model_management_claim->get_log_aktivitas_by_status_internal_and_kategori($group->status_internal, $group->kategori);
                            foreach ($get_detail->result() as $d) { ?>
                                
                                <div class="col-md-4">
                                    <div class="card mt-2" >
                                        <div class="card-body">
                                            <h5 class="card-title">user : <?= $d->username; ?></h5>
                                            <h6 class="card-subtitle mb-2 text-body-secondary mt-4"><?= $d->namasupp; ?></h6>
                                            <h6 class="card-subtitle mb-2 text-body-secondary">No surat : 
                                            <a href="<?= base_url().'assets/uploads/management_claim/registrasi_program/'.$d->upload_pdf ?>" target="_blank" style="color:white; background-color: black; padding-left: 5px;padding-right: 5px; border-radius: 5px;""><?= $d->nomor_surat ?></a>
                                            </h6>
                                            <h6 class="card-subtitle mb-2 text-body-secondary">nama : <?= $d->nama_program; ?></h6>
                                            <h6 class="card-subtitle mb-2 text-body-secondary">kategori : <?= $d->nama_kategori; ?></h6>
                                            <h6 class="card-subtitle mb-2 text-body-secondary mt-4">No claim : <?= $d->nomor_ajuan; ?></h6>
                                            
                                            <h6 class="card-subtitle mb-2 text-body-secondary"><?= $d->updated_at; ?></h6>
                                            <h6 class="card-subtitle mb-2 text-body-secondary mt-3">
                                                <a href="<?= base_url().'management_claim/routing/'.$d->signature_program.'/'.$d->signature_ajuan ?>" class="btn btn-submit pending-scm" target="_blank">details</a></h6>
                                        </div>
                                    </div>                    
                                </div>
                            <?php
                            }
                        ?>      
                    </div>
                </div>
            </div>
        </div>
    <?php    
    }?>



<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel-registrasi-new').DataTable({
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


    $("select[name = supp]").on("change", function() 
    {    
        let supp = document.getElementById('supp').value;            
        console.log('supp ' + supp)

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_flag_validasi') ?>',
            data: {
                'supp': supp,     
            },
            success: function(result) {
                $("select[name = flag_validasi]").html(result);
            }
        });

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_segment') ?>',
            data: {
                'supp': supp,     
            },
            success: function(result) {
                $("select[name = segment]").html(result);
            }
        });

        $.ajax({
            
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_kategori') ?>',
            success: function(result) {
                $("select[name = kategori]").html(result);
            }
        });

        $.ajax({
            
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_flag_pic') ?>',
            data: {
                'supp': supp,     
            },
            success: function(result) {
                $("select[name = pic]").html(result);
            }
        }); 
        
    });

    $("select[name = kategori]").on("change", function() 
    {    
        get_template();
    });

    $("select[name = segment]").on("change", function() 
    { 
        get_template();
    });

    function get_template()
    {
        let suppx = document.getElementById('supp').value;
        let segmentx = document.getElementById('segment').value;
        let kategorix = document.getElementById('kategori').value;
        // console.log("supp : "+ suppx)
        // console.log("segmentx : "+ segmentx)
        // console.log("kategorix : "+ kategorix)

        $.ajax({
            
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_template') ?>',
            data: {
                'supp': suppx,     
                'segment': segmentx,
                'kategori': kategorix
            },
            success: function(result) {
                $("select[name = id_template]").html(result);
            }
        }); 
    }
</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>