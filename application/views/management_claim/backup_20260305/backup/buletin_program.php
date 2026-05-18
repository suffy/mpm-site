</div>

<div class="container">
    
<?php echo form_open_multipart($url); ?>

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

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

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label">Principal</label> 
        </div>
        <div class="col-md-4">
            <select id="supp" name="supp" class="form-control" required>
                <option value=""> -- pilih principal -- </option>
                <option value="001"> Deltomed </option>
                <option value="002"> Marguna </option>
                <option value="005"> Ultra Sakti </option>
                <option value="012"> Intrafood </option>
                <option value="013"> Strive </option>
                <option value="015"> MDJ </option>
                <option value="025"> PT. GOOD PHARMA DERMATOLOGY </option>
                <option value="026"> PT. GUNUNG SUBUR SEJAHTERA </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="from" class="form-label">Bulan</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="bulan" type="month" name="periode" min="2024-01" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="keterangan" class="form-label">Keterangan</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <textarea name="keterangan" class="form-control form-control-md" id="keterangan" cols="30" rows="5"></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="attachment" class="form-label">Upload File (.zip)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="attachment" type="file" name="attachment" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4 d-flex flex-row">
            <button type="submit" class="btn btn-generate" id="btnKirim" onclick="return button()">Save Buletin Program</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
        </div>
    </div>
</form>

</div>
</div>

<div class="container">

    <div class="card-block mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <hr class="batas">
            </div>
        
            <div class="col-md-12">
                <table id="example" style="width: 100%">
                    <thead>
                        <tr>
                            <th class = "text-center col-1" style="height: 40px">No</th>  
                            <th class = "text-center col-1">Bulan</th>                          
                            <th class = "text-center col-2">Principal</th>                          
                            <th class = "text-center col-3">Keterangan</th>                          
                            <th class = "text-center col-1">File</th>                          
                            <th class = "text-center col-1">Total Download Anda</th>                          
                            <th class = "text-center col-1">Total Download</th>                          
                            <th class = "text-center col-2">#</th>                          
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td class="text-center" style="height: 40px"><?= $no++; ?></td>    
                            <td><?= $a->tahun.'-'.$a->bulan; ?></td>                        
                            <td><?= $a->namasupp; ?></td>                        
                            <td><?= $a->keterangan; ?></td>                        
                            <td class = "text-center">
                                <?php 
                                    if ($a->file) { ?>
                                        <!-- <a href="<?= base_url() . 'assets/uploads/management_claim/buletin_program/'.$a->file ?>" target="_blank" class="btn-pending">download</a> -->
                                        <a href="<?= base_url('management_claim/tracking_download_buletin/'.$a->signature) ?>" target="_blank" class="btn-pending">download</a>
                                    <?php
                                    }else{
                                        echo "-";
                                    }
                                ?>  
                            </td>   
                            <td class = "text-center">
                                <?php 
                                    if($a->count){
                                        echo $a->count;
                                    }else{
                                        echo "0";
                                    }
                                ?>
                            </td> 
                            <td class = "text-center">
                                <?php 
                                    if($a->total_download){
                                        echo $a->total_download;
                                    }else{
                                        echo "0";
                                    }
                                ?>
                            </td> 
                            <td class = "text-center">
                                <div class="col-md-12">
                                    <a href="<?= base_url('management_claim/delete_buletin/'.$a->signature) ?>" class="btn btn-delete btn-sm" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: white"></i> del</a>
                
                                    <a href="<?= base_url('management_claim/edit_buletin/'.$a->signature) ?>" class="btn btn-manage btn-sm"><i class="fa-regular fa-pen-to-square"></i> edit</a>
                                </div>
                            </td>                     
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>

<script>
    function button()
    {
        var supp   = document.getElementById('supp').value;
        var bulan = document.getElementById('bulan').value;
        var attachment = document.getElementById('attachment').value;

        if (supp && bulan && attachment) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>

<script>
    $(document).ready(function () {
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