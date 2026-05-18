
</div>

<div class="container">

<div class="row mt-1 ms-5">
    <div class="col-md-12 az-content-label text-center">
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
<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

</form>

    <?= form_open_multipart($url); ?>   

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_toko">Nama Toko</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nama_toko" id="nama_toko" value="<?= $nama_toko ?>" required>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="alamat">Alamat</label>
        </div>
        <div class="col-md-5">
            <textarea name="alamat" id="alamat" class="form-control" cols="10" rows="5"><?= $alamat ?></textarea>
        </div>
    </div>

     <div class="row mt-3">
        <div class="col-md-3">
            <label for="tanggal">Tanggal</label>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input type="datetime-local" name="tanggal" id = "tanggal" value="<?= $tanggal ?>" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">Hasil Market Survey</label>
        </div>
        <div class="col-md-5">
            <textarea name="keterangan" name="keterangan" id="keterangan" class="form-control" cols="10" rows="5"><?= $keterangan ?></textarea>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12 az-content-label">
            Attachment
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">foto</label>
        </div>
        <div class="col-md-5">
            <?php 
                $file = './assets/uploads/kpi/'.$attach_1; // 'images/'.$file (physical path)
                if (file_exists($attach_1)) { ?>
                    <a href="<?= base_url() ?>assets/uploads/kpi/<?= $attach_1 ?>" class="btn btn-pending mb-2" target="_blank">
                        <img src="<?= base_url().'assets/uploads/kpi/'.$attach_1 ?>" alt="" width="100%">
                    </a>  
                <?php
                } else { ?>
                    <a href="<?= base_url() ?>assets/uploads/kpi/<?= $attach_1 ?>" class="btn btn-pending mb-2" target="_blank">
                        <?= $attach_1 ?>
                    </a>  
                <?php 
                }
            ?>
            <input type="hidden" class="form-control mb-2" name="attach1_old" value="<?= $attach_1 ?>" readonly">
            <input type="file" class="form-control mb-2" name="attach_1">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">kpi market survey</label>
        </div>
        <div class="col-md-5">
            <?php 
                $file = './assets/uploads/kpi/'.$attach_2; // 'images/'.$file (physical path)
                if (file_exists($attach_2)) { ?>
                    <a href="<?= base_url() ?>assets/uploads/kpi/<?= $attach_2 ?>" class="btn btn-pending mb-2" target="_blank">
                        <img src="<?= base_url().'assets/uploads/kpi/'.$attach_2 ?>" alt="" width="100%">
                    </a>  
                <?php
                } else { ?>
                    <a href="<?= base_url() ?>assets/uploads/kpi/<?= $attach_2 ?>" class="btn btn-pending mb-2" target="_blank">
                        <?= $attach_2 ?>
                    </a>  
                <?php 
                }
            ?>
            <input type="hidden" class="form-control mb-2" name="attach2_old" value="<?= $attach_2 ?>" readonly">
            <input type="file" class="form-control mb-2" name="attach_2">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <input type="hidden" name="status" value="<?= $status ?>" readonly">
            <input type="hidden" name="nama_status" value="<?= $nama_status ?>" readonly">
            <input type="hidden" name="id_market_survey" value="<?= $id_market_survey ?>" readonly">
            <input type="hidden" name='id_workspace' value = <?= $id_workspace ?>>
            <input type="hidden" name='signature_workspace' value = <?= $signature_workspace ?>>
            <input type="hidden" name='signature_market_survey' value = <?= $signature_market_survey ?>>
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-generate" id="btnKirim" onclick="return button()">Update Pelaporan Market Survey</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
            <a href="<?= base_url('kpi/manage_workspace').'/'.$signature_workspace ?>" class="btn btn-back" id="btnBack">back to create market survey</a>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>

</div>

<div class="container">
    <div class="row mt-5 ms-5 mb-3">
        <div class="col-md-12 az-content-label text-center">
            List Market Survey
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 10px">No</th>
                        <th class="text-center">No Market Visit</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Pelaksana</th>
                        <th class="text-center">Nama Toko</th>
                        <th class="text-center">Alamat</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center" style="width: 150px">Hasil</th>
                        <th class="text-center" style="width: 150px">Attachment</th>
                        <th class="text-center" style="width: 50px">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    $no = 1;
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a->no_pelaporan ?></td>
                        <td>                            
                            <a href="<?= base_url('kpi/review_market_survey/'.$a->signature.'/'.$signature_workspace) ?>"><?= $a->nama_status ?></a>
                        </td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->nama_toko ?></td>
                        <td><?= $a->alamat ?></td>
                        <td><?= $a->tanggal ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td align="center">                                
                            <?php 
                                if ($a->attach_1) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_1 ?>" target="_blank" class="btn btn-pending">attach_1</a>
                                <?php
                                }else{
                                    echo "-";
                                }
                            ?>                        
                            <?php 
                                if ($a->attach_2) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_2 ?>" target="_blank" class="btn btn-pending">attach_2</a>
                                <?php
                                }else{
                                    echo "-";
                                }
                            ?>     
                        </td>
                        <td align="center">   
                            <a href="<?= base_url('kpi/edit_market_survey/'.$a->signature.'/'.$signature_workspace) ?>" class="btn btn-pending"><i class="fa-regular fa-pen-to-square"></i></a>                      
                            <a href="<?= base_url('kpi/delete_market_survey/'.$a->signature.'/'.$signature_workspace) ?>" class="btn btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>                      
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
    

<script>
    function button()
    {       
        // $("#btnKirim").hide();
        //     $("#btnBack").hide();
        //     $("#btnLoading").show(); 
        var nama_toko = document.getElementById('nama_toko').value;
        var alamat = document.getElementById('alamat').value;
        var tanggal = document.getElementById('tanggal').value;
        var keterangan = document.getElementById('keterangan').value;
        
        if (nama_toko && alamat && tanggal && keterangan) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
    $("#example").DataTable({
        "pageLength": 10,
        "ordering": true,
        "order": [0, 'desc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        "fixedHeader": {
            header: true,
            footer: true
        },
        scrollX: true
    });
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
