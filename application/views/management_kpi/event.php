
</div>

<div class="container-fluid">

<div class="row mt-1">
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
        <div class="col-md-2">
            <label for="nama_program">Nama Event</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nama_event" id="nama_event" required>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Tanggal Event</label>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input type="datetime-local" name="from" id="from" class="form-control" required>
                <input type="datetime-local" name="to" id="to" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Lokasi Event</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="lokasi_event" id="lokasi_event" required>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="biaya">Biaya</label>
        </div>
        <div class="col-md-5">
            <input type="number" class="form-control" name="biaya" id="biaya" onkeyup="keyupFunction()" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="omzet">Omzet</label>
        </div>
        <div class="col-md-5">
            <input type="number" class="form-control" name="omzet" id="omzet" onkeyup="keyupFunction()" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="cost_ratio">Cost Ratio</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="cost_ratio" id="cost_ratio" readonly>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="crowd">Crowd</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="crowd" id="crowd">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="brand">Brand</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="brand" id="brand">
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12 az-content-label">
            Attachment
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-2">
            <label for="nama_program">Proposal Referensi</label>
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control mb-2" id = "attach1" name="attach1" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">foto</label>
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control mb-2" id = "attach2" name="attach2" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">kpi event</label>
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control mb-2" id = "attach3" name="attach3" required>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-2">
            <input type="hidden" name='id_workspace' value = <?= $id_workspace ?>>
            <input type="hidden" name='signature_workspace' value = <?= $signature_workspace ?>>
        </div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-submit-black" id="btnKirim" onclick="return button()">Submit Event</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
            <a href="<?= base_url('kpi/workspace') ?>" class="btn btn-submit-black" id="btnBack">back</a>
        </div>
    </div>

    <?= form_close();?>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">NoEvent</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Pelaksana</th>
                        <th class="text-center">NamaEvent</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Biaya</th>
                        <th class="text-center">Value</th>
                        <th class="text-center">Ratio</th>
                        <th class="text-center">Crowd</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center" style="width: 170px;">Attachment</th>
                        <th class="text-center" style="width: 70px;">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    $no = 1;
                    foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td align ="center"><?= $no++ ?></td>
                        <td><?= $a->no_pelaporan_event ?></td>
                        <td>                            
                            <a href="<?= base_url('kpi/review_event/'.$a->signature.'/'.$signature_workspace) ?>" class="btn btn-submit-black"><?= $a->nama_status ?></a>
                        </td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->nama_event ?></td>
                        <td><?= $a->event_from.' - '.$a->event_to ?></td>
                        <td><?= $a->lokasi_event ?></td>
                        <td><?= number_format($a->biaya) ?></td>
                        <td><?= number_format($a->omzet) ?></td>
                        <td><?= $a->cost_ratio ?></td>
                        <td><?= $a->crowd ?></td>
                        <td><?= $a->brand ?></td>
                        <td align ="center">                            
                            <?php 
                                if ($a->attach_1) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_1 ?>" target="_blank" class="btn btn-submit-black">attach_1</a>
                                <?php
                                }else{
                                    echo "-";
                                }
                            ?>           
                            <?php 
                                if ($a->attach_2) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_2 ?>" target="_blank" class="btn btn-submit-black">attach_2</a>
                                <?php
                                }else{
                                    echo "-";
                                }
                            ?>    
                            <?php 
                                if ($a->attach_3) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/kpi/'.$a->attach_3 ?>" target="_blank" class="btn btn-submit-black">attach_3</a>
                                <?php
                                }else{
                                    echo "-";
                                }
                            ?>      
                        </td>
                        <td align="center">                   
                            <a href="<?= base_url('kpi/edit_event/'.$a->signature.'/'.$signature_workspace) ?>" class="btn-submit-black"><i class="fa-regular fa-pen-to-square" style="color: grey"></i></a>    
                            <a href="<?= base_url('kpi/delete_event/'.$a->signature.'/'.$signature_workspace) ?>" class="btn-submit-black" onclick="return confirm('Are you sure?')" style = "background-color: red"><i class="fa-solid fa-trash-can" style="color: white"></i></a>                      
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
        var nama_event   = document.getElementById('nama_event').value;
        var from = document.getElementById('from').value;
        var to = document.getElementById('to').value;
        var lokasi_event = document.getElementById('lokasi_event').value;
        var omzet = document.getElementById('omzet').value;
        var biaya = document.getElementById('biaya').value;
        var cost_ratio = document.getElementById('cost_ratio').value;
        var crowd = document.getElementById('crowd').value;
        var brand = document.getElementById('brand').value;
        var attach_1 = document.getElementById('attach1').value;
        var attach_2 = document.getElementById('attach2').value;
        var attach_3 = document.getElementById('attach3').value;
        
        if (nama_event && from && to && lokasi_event && omzet && biaya && cost_ratio && crowd && brand && attach_1 && attach_2 && attach_3) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }

    function keyupFunction(){
        var biaya = document.getElementById('biaya').value;
        var omzet = document.getElementById('omzet').value;
        var cost_ratio = document.getElementById('cost_ratio').value;
        var result = biaya / omzet;
        
        document.getElementById("cost_ratio").value = result;        
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
            ]
        });
    });
</script>
