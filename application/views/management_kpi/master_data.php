</div>

<div class="container-fluid">

    <div class="az-content">
        <div class="col-12">
            <div class="container-fluid">
                <?= $this->load->view('management_kpi/component/sidebar'); ?>

                <div class="col">

                    <!-- master team -->
                    <?php echo form_open($url_master_team_member); ?>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="row" id="master-team-member">
                                <div class="col-md-12">
                                    <?php 
                            if($this->session->flashdata('pesan_gagal_master_team_member')){ ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $this->session->flashdata('pesan_gagal_master_team_member'); ?>
                                    </div>
                                    <?php
                            }elseif($this->session->flashdata('pesan_success_master_team_member')){ ?>
                                    <div class="alert alert-success" role="alert">
                                        <?= $this->session->flashdata('pesan_success_master_team_member'); ?>
                                    </div>
                                    <?php
                            }
                            ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Master Team Member</h4>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="user_event" class="form-label">User Web</label>
                                </div>
                                <div class="col-md-9">
                                    <select id="user_event" name="user_event" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="rank" class="form-label">Rank</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="rank" id="rank" class="form-control" required>
                                        <option value=""> -- Pilih Rank --</option>
                                        <option value="spo">SPO</option>
                                        <option value="asps">ASPS / ASPH</option>
                                        <option value="rsph">RSPH</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4 mb-5">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-submit-black" id="btnKirimMasterTeamMember"
                                        onclick="return button()">Submit Master Team Member</button>
                                    <button class="btn btn-loading" id="btnLoadingMasterTeamMember" type="button"
                                        disabled>
                                        ... Please wait ...
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="table-master-team-member" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 5px;">No</th>
                                                <th style="width: 100px;">Username</th>
                                                <th>Email</th>
                                                <th>Rank</th>
                                                <th style="width: 50px;">UpdateAt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                    $no = 1;
                                    foreach ($get_master_team_member->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><?= $a->username ?></td>
                                                <td><?= $a->email ?></td>
                                                <td><?= $a->rank ?></td>
                                                <td><?= $a->updated_at ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!-- end master team -->

                    <!-- master team struktural -->
                    <?php echo form_open($url_master_team_member_struktural); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" id="master-team-member-struktural">
                                <div class="col-md-12">
                                    <?php 
                            if($this->session->flashdata('pesan_gagal_master_team_member_struktural')){ ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $this->session->flashdata('pesan_gagal_master_team_member_struktural'); ?>
                                    </div>
                                    <?php
                            }elseif($this->session->flashdata('pesan_success_master_team_member_struktural')){ ?>
                                    <div class="alert alert-success" role="alert">
                                        <?= $this->session->flashdata('pesan_success_master_team_member_struktural'); ?>
                                    </div>
                                    <?php
                            }
                            ?>
                                </div>
                            </div>

                            <div class="row mt-5" id="master-team-struktural">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Master Team Struktural</h4>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="team_member" class="form-label">Team Member</label>
                                </div>
                                <div class="col-md-9">
                                    <select id="team_member" name="team_member" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="pic_approval" class="form-label">PIC Approval</label>
                                </div>
                                <div class="col-md-9">
                                    <select id="team_member_approval" name="team_member_approval" class="form-control"
                                        required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4 mb-5">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-submit-black"
                                        id="btnKirimMasterTeamMemberStruktural" onclick="return button()">Submit Master
                                        Team
                                        Member Struktural</button>
                                    <button class="btn btn-loading" id="btnLoadingMasterTeamMemberStruktural"
                                        type="button" disabled>
                                        ... Please wait ...
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="table-master-team-member-struktural">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>User</th>
                                                <th>Email</th>
                                                <th>Rank</th>
                                                <th>Approval</th>
                                                <th>Email</th>
                                                <th>Rank</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                    $no = 1;
                                    foreach ($get_master_team_member_struktural->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><?= $a->nama_user ?></td>
                                                <td><?= $a->email_user ?></td>
                                                <td><?= $a->rank_user ?></td>
                                                <td><?= $a->nama_approval ?></td>
                                                <td><?= $a->email_approval ?></td>
                                                <td><?= $a->rank_approval ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!-- end master struktural -->

                    <!-- master perhitungan -->
                    <?php echo form_open($url_master_perhitungan); ?>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="row" id="master-perhitungan">
                                <div class="col-md-12">
                                    <?php 
                            if($this->session->flashdata('pesan_gagal_master_perhitungan')){ ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $this->session->flashdata('pesan_gagal_master_perhitungan'); ?>
                                    </div>
                                    <?php
                            }elseif($this->session->flashdata('pesan_success_master_perhitungan')){ ?>
                                    <div class="alert alert-success" role="alert">
                                        <?= $this->session->flashdata('pesan_success_master_perhitungan'); ?>
                                    </div>
                                    <?php
                            }
                            ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Master Perhitungan</h4>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="nama_program">Category</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="category" class="form-control" required>
                                        <option value="">-- Pilih Category --</option>
                                        <option value="event">Event</option>
                                        <option value="pemerataan_product">Spreading (Pemerataan Product Non OB DP)</option>
                                        <option value="visibility">Spreading (Visibility / Branding OB DP)</option>
                                        <option value="surveyor">Surveyor</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="kuartal">Quarterly</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="kuartal" class="form-control" required>
                                        <option value="">-- Pilih Quarterly --</option>
                                        <option value="Q1">Q1</option>
                                        <option value="Q2">Q2</option>
                                        <option value="Q3">Q3</option>
                                        <option value="Q4">Q4</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="parameter">Parameter</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="parameter" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="minimum_target">Minimum target</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" name="minimum_target" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="bobot">Bobot</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="bobot" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="rank">Rank</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="rank" id="rank" class="form-control" required>
                                        <option value=""> -- Pilih Rank --</option>
                                        <option value="spo">SPO</option>
                                        <option value="asps">ASPS / ASPH</option>
                                        <option value="rsph">RSPH</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-submit-black" id="btnKirimMasterPerhitungan"
                                        onclick="return button()">Submit Master Perhitungan</button>
                                    <button class="btn btn-loading" id="btnLoadingMasterPerhitungan" type="button"
                                        disabled>
                                        ... Please wait ...
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="table-master-perhitungan">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Category</th>
                                                <th>Quarterly</th>
                                                <th>Parameter</th>
                                                <th>Min Target</th>
                                                <th>Bobot</th>
                                                <th>Rank</th>
                                                <th>UpdatedAt</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                    $no = 1;
                                    foreach ($get_master_perhitungan->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><?= $a->category ?></td>
                                                <td><?= $a->kuartal ?></td>
                                                <td><?= $a->parameter ?></td>
                                                <td><?= $a->min_target ?></td>
                                                <td><?= $a->bobot ?></td>
                                                <td><?= $a->rank ?></td>
                                                <td><?= $a->updated_at ?></td>
                                                <td>
                                                    <a href="<?= base_url('kpi/master_perhitungan_detail/'.$a->signature) ?>"
                                                        class="btn btn-submit-red" target="_blank">detail point</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!-- end master perhitungan -->

                    <!-- master brand -->
                    <?php echo form_open($url_master_brand); ?>
                    <div class="row mt-5">
                        <div class="col-md-12">

                            <div class="row" id="master-brand">
                                <div class="col-md-12">
                                    <?php 
                                if($this->session->flashdata('pesan_gagal_master_brand')){ ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $this->session->flashdata('pesan_gagal_master_brand'); ?>
                                    </div>
                                    <?php
                                }elseif($this->session->flashdata('pesan_success_master_brand')){ ?>
                                    <div class="alert alert-success" role="alert">
                                        <?= $this->session->flashdata('pesan_success_master_brand'); ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4 class="title-square">Master Brand</h4>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <label for="brand">Brand</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="brand" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-submit-black" id="btnKirimMasterBrand"
                                        onclick="return button()">Submit Master Brand</button>
                                    <button class="btn btn-loading" id="btnLoadingMasterBrand" type="button" disabled>
                                        ... Please wait ...
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3 mb-5">
                                <div class="col-md-12">
                                    <table id="table-master-brand">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">No</th>
                                                <th>Brand</th>
                                                <th>CreatedAt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                        $no = 1;
                                        foreach ($get_master_brand->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><?= $a->brand ?></td>
                                                <td><?= $a->updated_at ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!-- end master brand -->

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnLoadingMasterTeamMember").hide();
        $('#table-master-team-member').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $(document).ready(function () {
        $("#btnLoadingMasterTeamMemberStruktural").hide();
        $('#table-master-team-member-struktural').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $(document).ready(function () {
        $("#btnLoadingMasterPerhitungan").hide();
        $('#table-master-perhitungan').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    $(document).ready(function () {
        $("#btnLoadingMasterBrand").hide();
        $('#table-master-brand').DataTable({
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
        url: "<?php echo base_url('management_claim/master_user_mpm');?>",
        data: '',
        success: function (result) {
            $("select[name = user_event]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('kpi/master_team_member'); ?>",
        data: '',
        success: function (result) {
            $("select[name = team_member]").html(result);
            $("select[name = team_member_approval]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('kpi/master_brand'); ?>",
        data: '',
        success: function (result) {
            $("select[name = brand]").html(result);
        }
    });
</script>