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
            <label for="supp" class="form-label">Principal (*)</label> 
        </div>
        <div class="col-md-4">
            <select id="supp" name="supp" class="form-control" required>
                <option value=""> -- pilih principal -- </option>
                <option value="001" <?php if($supp == '001') echo 'selected'; ?>> Deltomed </option>
                <option value="001-herbana" <?php if($supp == '001-herbana') echo 'selected'; ?>> Herbana </option>
                <option value="002" <?php if($supp == '002') echo 'selected'; ?>> Marguna </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nomor_surat" class="form-label">Nomor Surat</label>
        </div>
        <div class="col-md-4">
            <input type="text" class ="form-control" id="nomor_surat" name="nomor_surat" value = "<?= $nomor_surat ?>" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="from" class="form-label">Periode</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="from" type="date" name="from" value = "<?= $from ?>" required>
            <input class="form-control form-control-md" id="to" type="date" name="to" value = "<?= $to ?>" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_kam" class="form-label">Nama KAM &nbsp;
                <a href="<?= base_url('management_claim/master_kam/') ?>" target="_blank" style="color: black;"><i class="fa-solid fa-gear"></i></a></label> 
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
                <option value="NASIONAL" <?php if($area == 'NASIONAL') echo 'selected'; ?>> NASIONAL </option>
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
           <textarea name="item" class="form-control" id="item" cols="30" rows="3"><?= $item ?></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="mekanisme" class="form-label">Mekanisme</label>
        </div>
        <div class="col-md-4">
           <textarea name="mekanisme" class="form-control" id="mekanisme" cols="30" rows="3"><?= $mekanisme ?></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="expose" class="form-label">Expose</label>
        </div>
        <div class="col-md-4">
           <textarea name="expose" class="form-control" id="expose" cols="30" rows="3"><?= $expose ?></textarea>
        </div>
    </div>

    <div class="row mt-3 mb-5">
        <div class="col-md-2">
            <input type="hidden" name="signature_program" value = "<?= $signature_program ?>">
            <input type="hidden" name="id_program" value = "<?= $id_program ?>">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-submit" id="btnKirim" onclick="return button()">Update Registrasi Program MTI</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
            <a href="<?= base_url().'management_claim/registrasi_program_mti' ?>" class="btn btn-back" id="btnBack">Back</a>
        </div>
    </div>
<?php echo form_close(); ?>

    <div class="row mt-3">
        <div class="col-md-12 mt-4">  
            <table id="example">
                <thead>
                    <tr>                        
                        <th class="text-center col-1" style="background-color: darkslategray;" >
                            <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                            value="click all" onclick="click_all_request()">
                        </th>
                        <th style="width:50px" class="text-center col-3">#</th>                   
                        <th>Principal</th>
                        <th>SKP</th>
                        <th>TradingTerm</th>
                        <th>NoSurat</th>
                        <th>Nama KAM</th>
                        <th>Email KAM</th>
                        <th>Account</th>
                        <th>Area</th>
                        <th>Brand</th>
                        <th>Item</th>
                        <th style="width:200px">Mekanisme</th>
                        <th>Expose</th>
                        <th>Periode</th>                         
                        <th>CreatedAt</th>                         
                    </tr>
                </thead>
                <tbody>     
                    <?php $no = 1;
                    foreach ($get_registrasi_program_mti->result() as $a) : ?>
                    <tr>                  
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>     
                        <td class="text-center">
                            <a href="<?= base_url('management_claim/edit_registrasi_program_mti/'.$a->signature) ?>" class="btn-pending"><i class="fa-regular fa-pen-to-square"></i></a> |   
                            <a href="<?= base_url('management_claim/delete_registrasi_program_mti/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                        </td>
                        <td><?= $a->namasupp ?></td>
                        <td><a href="<?= base_url().'assets/uploads/management_claim/mti/'.$a->file_skp ?>"><?= $a->file_skp ?></a></td>
                        <td><a href="<?= base_url().'assets/uploads/management_claim/mti/'.$a->file_trading_term ?>"><?= $a->file_trading_term ?></a></td>
                        <td><?= $a->nomor_surat ?></td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->email ?></td>
                        <td><?= $a->account ?></td>
                        <td><?= $a->area ?></td>
                        <td><?= $a->brand ?></td>
                        <td><?= $a->item ?></td>
                        <td><?= $a->mekanisme ?></td>
                        <td><?= $a->expose ?></td>
                        <td><?= $a->from.' - '.$a->to.'' ?></td>       
                        <td><?= $a->created_at ?></td>                 
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#example').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [12, 'desc'],
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
    $("select[name = supp]").on("change", function() {
        var supp_terpilih = document.getElementById('supp').value;
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('management_claim/master_kam_by_supp') ?>',
            data: 'supp=' + supp_terpilih,
            success: function(result) {
                $("select[name = userid_kam]").html(result);
            }
        });

        
    });
</script>


<script>
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

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>