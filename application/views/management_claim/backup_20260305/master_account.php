</div>


<?php echo form_open_multipart($url); ?>
<div class="container">
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
            <label for="kam" class="form-label">User Kam &nbsp;
        </div>
        <div class="col-md-4">
            <select id="kam" name="kam" class="form-control" required>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label">Account (*)</label> 
        </div>
        <div class="col-md-4">
            <input type="text" class ="form-control" id="account" name="account" placeholder ="masukkan account ... " required>
        </div>
    </div>

    <div class="row mt-3 mb-5">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4 d-flex flex-row">
            <button type="submit" class="btn btn-submit" id="btnKirim" onclick="return button()">Save Master Account</button>
            <button class="btn btn-loading" id="btnLoading" type="button" disabled>
            ... Please wait ...
            </button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<?php echo form_open($url); ?>
<div class="container">
    <div class="row mt-3">
        <div class="col-md-12 mt-4">  
            <table id="example">
                <thead>
                    <tr>    
                        <th class="text-center col-1" style="background-color: darkslategray;" >
                            <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                            value="click all" onclick="click_all_request()">
                        </th>
                        <th style="width:5%" class="text-center">No</th>     
                        <th style="width:5%" class="text-center">#</th>              
                        <th style="width:200px">Username</th>                       
                        <th style="width:200px">Account</th>                       
                    </tr>
                </thead>
                <tbody>    
                <?php $no = 1;
                foreach ($get_master_account->result() as $a) : ?>
                <tr>
                   <td>
                        <center>
                        <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                        </center>
                   </td>  
                   <td class="text-center"><?= $no++ ?></td>
                   <td class="text-center">  
                        <a href="<?= base_url('management_claim/delete_master_account/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                    </td>
                   <td><?= $a->username ?></td>
                   <td><?= $a->account ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-2 mb-5">
        <div class="col">
            <input type="submit" class="btn btn-export" id="btnDelete" value="Delete file yang dipilih" onclick="return confirm('Are you sure?')">
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_kam_single') ?>',
        data: '',
        success: function(result) {
            $("select[name = kam]").html(result);
        }
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#example').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [3, 'asc'],
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

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>