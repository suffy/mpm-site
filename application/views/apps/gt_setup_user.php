<!-- Menambahkan CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->

<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>

<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
        <form action="<?= $url ?>" method="post">

        <div class="row mt-1">
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

            <div class="row mt-5">
                <div class="col-md-2">
                    <label for="userid">User</label> 
                </div>
                <div class="col-md-4">
                    <select name="userid" id="userid" class="form-control" selected>
                        <option value="" class="form-control"> -- Pilih User --</option>
                        <?php foreach ($get_user->result() as $a) { ?>
                            <option value="<?= $a->id ?>"><?= $a->username.' - '.$a->email ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2">
                    <label for="userid">Member</label> 
                </div>
                <div class="col-md-4">
                    <select name="userid_member" id="userid_member" class="form-control" selected>
                        <option value="" class="form-control"> -- Pilih Member --</option>
                        <?php foreach ($get_user->result() as $a) { ?>
                            <option value="<?= $a->id ?>"><?= $a->username.' - '.$a->email ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-submit-red">Simpan</button>
                </div>
            </div>

        </form>
    </div>

    <div class="card-body mt-5">
        <h5 class="card-title">Master User</h5>
        <table id="tabel" class="table table-striped" style="width:100%">
            <thead>
                <tr>       
                    <th class="text-center" width="5%">no</th>         
                    <th class="text-center">username</th>         
                    <th class="text-center">username member</th>  
                    <th class="text-center">createdAt</th>  
                    <th class="text-center" style="width: 10%">is active</th> 
                </tr>
            </thead>
            <tbody>     
                <?php 
                $no = 1;
                foreach ($get_data->result() as $a) : ?>        
                    <tr> 
                        <td><?= $no++ ?></td>
                        <td><?= $a->username.' - '.$a->email ?></td>   
                        <td><?= $a->username_member. ' - '.$a->email_member ?></td>  
                        <td><?= $a->created_at ?></td>
                        <td>
                            <?php if ($a->is_active == 1) { ?>
                                <a class="badge badge-success" onclick="return confirm('Are you sure to change status?')" href="<?= base_url('apps/active_user/'.$a->signature.'/'.$a->is_active) ?>">Active</a> 
                            <?php } else { ?>
                                <a class="badge badge-danger" onclick="return confirm('Are you sure to change status?')" href="<?= base_url('apps/active_user/'.$a->signature.'/'.$a->is_active) ?>">Not Active</a> 
                            <?php } ?>
                        </td>   
                    </tr>
                <?php endforeach; ?>   
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel').DataTable({
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
</script>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> -->
