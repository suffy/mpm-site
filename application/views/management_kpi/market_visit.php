
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
            <label for="nama_program">Lokasi</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="lokasi" required>
        </div>
    </div>

     <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">Tanggal Market Visit</label>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input type="datetime-local" name="from" class="form-control" required>
                <input type="datetime-local" name="to" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">Keterangan</label>
        </div>
        <div class="col-md-5">
            <textarea name="keterangan" name="keterangan" class="form-control" cols="10" rows="5"></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">Referensi RPD (Rencana Perjalanan Dinas)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="ref_perdin">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="nama_program">Attachment</label>
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control mb-2" name="attach1" required>
            <input type="file" class="form-control mb-2" name="attach2" required>
            <input type="file" class="form-control mb-2" name="attach3" required>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-md-3">
            <input type="hidden" name='id_workspace' value = <?= $id_workspace ?>>
            <input type="hidden" name='signature_workspace' value = <?= $signature_workspace ?>>
        </div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-generate">Create Pelaporan Market Visit</button>
            <a href="<?= base_url('kpi/workspace') ?>" class="btn btn-back">back to workspace</a>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>

</div>

<div class="container">
    <div class="row mt-5 ms-5 mb-3">
        <div class="col-md-12 az-content-label text-center">
            List Event
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th class="text-center col-1">No</th>
                        <th class="text-center col-2">No Market Visit</th>
                        <th class="text-center col-2">Status</th>
                        <th class="text-center col-2">Pelaksana</th>
                        <th class="text-center col-2">Lokasi</th>
                        <th class="text-center col-2">Tanggal</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Ref Rpd</th>
                        <th class="text-center col-2">Attachment</th>
                        <th class="text-center" style="width: 100px">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    $no = 1;
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $a->no_pelaporan_market_visit ?></td>
                        <td>                            
                            <a href="<?= base_url('kpi/review_market_visit/'.$a->signature.'/'.$signature_workspace) ?>"><?= $a->nama_status ?></a>
                        </td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->lokasi ?></td>
                        <td><?= $a->from.' - '.$a->to ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td><?= $a->referensi_rpd ?></td>
                        <td>
                            <div class="col-12 d-flex justify-content-center">
                                <div class="col-md-4">
                                    <?php 
                                        if ($a->attach_1) { ?>
                                            <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_1 ?>" target="_blank" class="btn-pending">attach_1</a>
                                        <?php
                                        }else{
                                            echo "-";
                                        }
                                    ?>                        
                                </div>
                                <div class="col-md-4">
                                    <?php 
                                        if ($a->attach_2) { ?>
                                            <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_2 ?>" target="_blank" class="btn-pending">attach_2</a>
                                        <?php
                                        }else{
                                            echo "-";
                                        }
                                    ?>                        
                                </div>
                                <div class="col-md-4">
                                    <?php 
                                        if ($a->attach_3) { ?>
                                            <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_3 ?>" target="_blank" class="btn-pending">attach_3</a>
                                        <?php
                                        }else{
                                            echo "-";
                                        }
                                    ?>                        
                                </div>
                            </div>
                        </td>
                        <td align="center">                            
                            <!-- <div class="col-md-12"> -->
                                <a href="<?= base_url('kpi/delete_market_visit/'.$a->signature.'/'.$signature_workspace) ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: white"></i> del</a>                      
                                <a href="<?= base_url('kpi/edit_market_visit/'.$a->signature.'/'.$signature_workspace) ?>" class="btn btn-pending"><i class="fa-regular fa-pen-to-square"></i> edit</a>                  
                            <!-- </div> -->
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
    

<script>
    $(document).ready(function () {
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
