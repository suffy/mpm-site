</div>

<div class="container-fluid">

<div class="az-content">
    <div class="container-fluid">
        <?= $this->load->view('management_rpd/component/sidebar'); ?>        
        <div class="pd-lg-l-40">
    
        <div class="row">
            <div class="col-md-12">
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

                <!-- master data approval -->
                <?php echo form_open($url_master_data_approval); ?>
                <div class="row">
                    <div class="col-md-12 az-content-label">
                        <?= $title ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="userid_pelaksana" class="form-label">User Pelaksana</label>
                            </div>
                            <div class="col-md-7">
                                <select id="userid_pelaksana" name="userid_pelaksana" class="form-control" required>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="userid_verifikasi1" class="form-label">Approval Atasan 1</label>
                            </div>
                            <div class="col-md-7">
                                <select id="userid_verifikasi1" name="userid_verifikasi1" class="form-control" required>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="userid_verifikasi2" class="form-label">Approval Atasan 2</label>
                            </div>
                            <div class="col-md-7">
                                <select id="userid_verifikasi2" name="userid_verifikasi2" class="form-control" required>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="userid_verifikasi3" class="form-label">Approval Atasan 3</label>
                            </div>
                            <div class="col-md-7">
                                <select id="userid_verifikasi3" name="userid_verifikasi3" class="form-control" required>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4 mb-5">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-6">
                                <?php
                                if ($status_authorized == 1)
                                {?>
                                    <button type="submit" class="btn btn-submit-black" id="btnKirimMasterTeamMemberStruktural" onclick="return button()">Submit Master Data Approval</button>
                                <?php
                                } else {?>
                                    <a href="#" class="btn btn-submit-black" disable>not your authority</a>
                                <?php
                                }?>
                                <!-- <button type="submit" class="btn btn-submit-black" id="btnKirimMasterTeamMemberStruktural" onclick="return button()" <?= $this->session->userdata('username') == 'admin_deltomed' || $this->session->userdata('username') == 'imas' || $this->session->userdata('username') == 'milla' ? 'enabled' : 'disabled' ?>>Submit Master Data Approval</button>
                                <button class="btn btn-loading" id="btnLoadingMasterTeamMemberStruktural" type="button" disabled>
                                    ... Please wait ...
                                </button> -->
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <table id="table-master-team-member-struktural">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>User Pelaksana</th>
                                            <th>Approval Atasan 1</th>
                                            <th>Approval Atasan 2</th>
                                            <th>Approval Atasan 3</th>
                                            <!-- <th>Created At</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($get_master_data_approval->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><?= $a->name_pelaksana ?></td>
                                                <td><?= $a->name_verifikasi1 ?></td>
                                                <td><?= $a->name_verifikasi2 ?></td>
                                                <td><?= $a->name_verifikasi3 ?></td>
                                                <!-- <td><?= $a->created_at ?></td> -->
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <!-- end data approval -->
            </div>
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