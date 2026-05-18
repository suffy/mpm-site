</div>

<div class="container-fluid">

<div class="az-content">
    <div class="container-fluid">
        <?= $this->load->view('management_rpd/component/sidebar'); ?>        
        <div class="pd-lg-l-40">
    <!-- </div> -->

    <div class="row">
        <div class="row" id="master-team-member-struktural">
            <div class="col-md-12">
                <?php
                if ($this->session->flashdata('pesan_gagal_update_master_data_approval')) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $this->session->flashdata('pesan_gagal_update_master_data_approval'); ?>
                    </div>
                <?php
                } elseif ($this->session->flashdata('pesan_success_update_master_data_approval')) { ?>
                    <div class="alert alert-success" role="alert">
                        <?= $this->session->flashdata('pesan_success_update_master_data_approval'); ?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

    <!-- master team struktural -->
        <?php echo form_open($url_master_limit); ?>
        <div class="row">
            <div class="col-md-12 az-content-label">
                <?= $title ?>
            </div>
        </div>
        
        <div class="row">
            <!-- <div class="col-md-12"> -->
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="max_limit" class="form-label">Update Max Limit</label>
                    </div>
                    <div class="col-md-7">
                        <input class="form-control" type="text" name="max_limit" value=<?= $limit ?> />
                    </div>
                </div>

                <div class="row mt-2 mb-5">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    <?php
                        if ($status_authorized == 1)
                        {?>
                            <button type="submit" class="btn btn-submit-black" id="btnKirimMasterTeamMemberStruktural" onclick="return button()">Update Max Limit</button>
                        <?php
                        } else {?>
                            <a href="#" class="btn btn-submit-black" disable>not your authority</a>
                        <?php
                        }?>
                    </div>
                </div>
            <!-- </div> -->
        </div>
        <?php echo form_close(); ?>
    <!-- end master struktural -->
    </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
            $("#btnLoadingMasterTeamMemberStruktural").hide();
            $('#table-master-team-member-struktural').DataTable({
                "pageLength": 10,
                "ordering": true,
                "order": [1, 'desc'],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
            });
    });
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_user_mpm') ?>',
        data: '',
        success: function(result) {
            $("select[name = userid_pelaksana]").html(result);
            $("select[name = userid_verifikasi1]").html(result);
            $("select[name = userid_verifikasi2]").html(result);
            $("select[name = userid_verifikasi3]").html(result);
        }
    });

</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>