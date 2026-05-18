<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>

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

        <form action="<?= $url ?>" method="post">

        <div class="row mt-5">
            <div class="col-md-2">
                <label for="tanggal">Pilih Tanggal</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="tanggal" id="tanggal" min="2025-01-01" class="form-control" value="<?= $this->input->get('tanggal') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="user">Keterangan</label> 
            </div>
            <div class="col-md-4">
                <textarea name="keterangan" cols="30" rows="3" class="form-control" required></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp"></label> 
            </div>
            <div class="col-md-10">
                <input type="submit" class="btn btn-submit-red" name="submit" value="Simpan" style="height: 45px;">  
            </div>
        </div>
        </form>
    </div>

    <?php if (is_object($get_data)) { ?>

    <div class="card-block mt-4 mb-5">
        <div class="row">
            <div class="col-md-12">
                <table id="tabel" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>           
                            <th class="text-center" width="1%">No</th>  
                            <th class="text-center">Tanggal</th>         
                            <th class="text-center">Keterangan</th>        
                            <th class="text-center">#</th>        
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                            $no = 1;
                            foreach ($get_data->result() as $a) : ?>        
                            <tr>  
                                <td><?= $no++ ?></td>
                                <td><?= $a->tanggal ?></td> 
                                <td><?= $a->keterangan ?></td> 
                                <td>
                                    <a href="<?= base_url('apps/attendance_tanggal_merah_delete/'.$a->id) ?>" class="btn btn-delete" onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')">Delete</a>
                                    <a href="<?= base_url('apps/attendance_tanggal_merah_override/'.$a->id) ?>" class="btn btn-warning">Override</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <?php } ?>
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