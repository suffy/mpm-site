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
            <label for="nomor_surat" class="form-label">Nomor Surat</label>
        </div>
        <div class="col-md-4">
            <input type="text" class ="form-control" id="nomor_surat" name="nomor_surat" placeholder ="masukkan nomor surat ... " required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="from" class="form-label">Periode</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="from" type="date" name="from" required>
            <input class="form-control form-control-md" id="to" type="date" name="to" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_kam" class="form-label">Nama KAM</label> 
        </div>
        <div class="col-md-4">
            <select id="userid_kam" name="userid_kam" class="form-control" required>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="account" class="form-label">Account</label> 
        </div>
        <div class="col-md-4">
            <select id="account" name="account" class="form-control" required>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="area" class="form-label">Area</label> 
        </div>
        <div class="col-md-4">
            <select id="area" name="area" class="form-control" required>
                <option value=""> -- Pilih Area -- </option>
                <option value="NASIONAL"> NASIONAL </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="brand" class="form-label">Brand</label> 
        </div>
        <div class="col-md-4">
            <select id="brand" name="brand" class="form-control" required>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="item" class="form-label">Item</label>
        </div>
        <div class="col-md-4">
           <textarea name="item" class="form-control" id="item" cols="30" rows="3"></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="mekanisme" class="form-label">Mekanisme</label>
        </div>
        <div class="col-md-4">
           <textarea name="mekanisme" class="form-control" id="mekanisme" cols="30" rows="3"></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="expose" class="form-label">Expose</label>
        </div>
        <div class="col-md-4">
           <textarea name="expose" class="form-control" id="expose" cols="30" rows="3"></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4 d-flex flex-row">
            <button type="submit" class="btn btn-submit" id="btnKirim" onclick="return button()">Save Registrasi Program MTI</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
        </div>
    </div>
</form>

    <hr>
    <?php echo form_open_multipart($url_import); ?>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="expose" class="form-label">File Import</label>
        </div>
        <div class="col-md-4">
           <input type="file" class="form-control" name="file" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-submit">Import</button>
            <a href="<?= base_url('management_claim/export_template_registrasi_mti') ?>" class="btn btn-null">Download Template</a>
        </div>
    </div>    
    </form>

    <div class="row mt-3">
        <div class="col-md-12 mt-4">  
            <table id="example">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama KAM</th>
                        <th>Email KAM</th>
                        <th>Account</th>
                        <th>Area</th>
                        <th>Brand</th>
                        <th>Item</th>
                        <th style="width:200px">Mekanisme</th>
                        <th>Expose</th>
                        <th>Periode</th>               
                        <th style="width:95px" class="text-center col-3">#</th>             
                    </tr>
                </thead>
                <tbody>     
                    <?php $no = 1;
                    foreach ($get_registrasi_program_mti->result() as $a) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        
                        <td><?= $a->name ?></td>
                        <td><?= $a->email ?></td>
                        <td><?= $a->account ?></td>
                        <td><?= $a->area ?></td>
                        <td><?= $a->brand ?></td>
                        <td><?= $a->item ?></td>
                        <td><?= $a->mekanisme ?></td>
                        <td><?= $a->expose ?></td>
                        <td><?= $a->from.' - '.$a->to.'' ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('management_claim/edit_registrasi_program_mti/'.$a->signature) ?>" class="btn btn-pending"><i class="fa-regular fa-pen-to-square"></i></a>    
                            <a href="<?= base_url('management_claim/delete_registrasi_program_mti/'.$a->signature) ?>" class="btn btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#example').DataTable({
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

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_user_kam') ?>',
        data: '',
        success: function(result) {
            $("select[name = userid_kam]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_account_mti') ?>',
        data: '',
        success: function(result) {
            $("select[name = account]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_brand_mti') ?>',
        data: '',
        success: function(result) {
            $("select[name = brand]").html(result);
        }
    });
</script>

<script>
    function button()
    {
        var nomor_surat     = document.getElementById('nomor_surat').value;
        var from            = document.getElementById('from').value;
        var to              = document.getElementById('to').value;
        var userid_kam      = document.getElementById('userid_kam').value;
        var account         = document.getElementById('account').value;
        var brand           = document.getElementById('brand').value;
        var item            = document.getElementById('item').value;
        var mekanisme       = document.getElementById('mekanisme').value;
        var expose          = document.getElementById('expose').value;
        if (nomor_surat &&from && to && userid_kam && account && brand && item && mekanisme && expose) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>