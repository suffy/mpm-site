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
                <?php echo form_open($url_master_karyawan); ?>
                <div class="row">
                    <div class="col-md-12 az-content-label">
                        <?= $title ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="userid_karyawan" class="form-label">Nama Karyawan</label>
                            </div>
                            <div class="col-md-7">
                                <select id="userid_karyawan" name="userid_karyawan" class="form-control" required>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="email" class="form-label">Email</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="jabatan" id ="jabatan">
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="level_karyawan" class="form-label">Level Karyawan</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="level_karyawan" id = "level_karyawan">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="status_karyawan" class="form-label">Status Karyawan</label>
                            </div>
                            <div class="col-md-7">
                                <select name="status_karyawan" id="status_karyawan" class="form-control" required>
                                    <option value=""> -- Pilih Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="kode_apps" class="form-label">Kode Apps</label>
                            </div>
                            <div class="col-md-7">
                                <select name="kode_apps" id="kode_apps" class="form-control">
                                    <option value=""> -- Pilih Kode Apps --</option>
                                    <option value="deltomed_gt">deltomed_gt</option>
                                    <option value="deltomed_mt">deltomed_mt</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4 mb-5">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-4">
                            <?php
                                if ($status_authorized == 1)
                                {?>
                                    <button type="submit" class="btn btn-submit-black" id="btnKirimMasterTeamMemberStruktural" onclick="return button()">Update Master Karyawan</button>
                                <?php
                                } else {?>
                                    <a href="#" class="btn btn-submit-black" disable>not your authority</a>
                                <?php
                                }?>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <table id="table-master-team-member-struktural">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan</th>
                                            <th>Email</th>
                                            <th>Jabatan</th>
                                            <th>Level Karyawan</th>
                                            <th>Status Karyawan</th>
                                            <th>Kode Apps</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($get_master_karyawan->result() as $a) : ?>
                                            <tr>
                                                <td align="center"><?= $no++ ?></td>
                                                <td><?= $a->name ?></td>
                                                <td><?= $a->email ?></td>
                                                <td><?= $a->jabatan ?></td>
                                                <td><?= $a->level_karyawan ?></td>
                                                <td> <?php if($a->active == 1) {
                                                        echo "Active";
                                                }
                                                else echo "Inactive"; ?></td>
                                                <td><?= $a->kode_apps ?></td>
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
                "order": [1, 'asc'],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
            });
    });
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_user_mpm') ?>',
        data: 'id',
        success: function(result) {
            $("select[name = userid_karyawan]").html(result);
        }
    });

</script>

<script>    
    $("select[name = userid_karyawan]").on("change", function() {
        const userid_terpilih = document.getElementById('userid_karyawan').value;
        // var id_provinsi_terpilih = $("option:selected", this).attr("id_provinsi");
        // console.log('aa')
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('management_rpd/mpm_user') ?>',
            data: 'id=' + userid_terpilih,
            success: function(result) {
                // $("select[name = userid_karyawan]").html(result);
                // console.log('result')
                var json = result,
                    obj = JSON.parse(json);
                    $('#email').val(obj.email);
                    $('#jabatan').val(obj.jabatan);
                    $('#level_karyawan').val(obj.level_karyawan);

                // console.log(result)
                // document.getElementById("email").value = result;
                // document.getElementById("jabatan").value = result;
                // document.getElementById("level_karyawan").value = result;
            }
        });
    });
</script>