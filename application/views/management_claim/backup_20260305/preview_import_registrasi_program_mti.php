</div>

<div class="container-fluid">
<?php echo form_open_multipart($url_import); ?>
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
            <button type="submit" class="btn btn-submit-black">Import</button>
            <a href="<?= base_url('management_claim/export_template_registrasi_mti') ?>" class="btn btn-submit-black">Download Template</a>
            <a href="<?= base_url('management_claim/registrasi_program_mti') ?>" class="btn btn-submit-black">back</a>
        </div>
    </div> 
    <?= form_close(); ?>

    <hr>

    <div class="row mt-5">
        <div class="col-md-12 text-center">
            <h4>Preview Data</h4>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-12 mt-4">
            <table id="example">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width:100px">Principal</th>
                        <th style="width:100px">NomorSurat</th>
                        <th>NamaKAM</th>
                        <th>EmailKAM</th>
                        <th>Account</th>
                        <th>Area</th>
                        <th>Brand</th>
                        <th>Item</th>
                        <th style="width:200px">Mekanisme</th>
                        <th>Expose</th>
                        <th style="width:100px">Periode</th>                     
                    </tr>
                </thead>
                <tbody>     
                    <?php $no = 1;
                    foreach ($get_import_registrasi_program_mti->result() as $a) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $a->namasupp ? $a->namasupp : '<span style="color:red; background-color:#f8d7da">not found</span>'.'' ?></td>
                        <td><?= $a->nomor_surat ?></td>
                        <td><?= $a->validasi_userid_kam == 0 ? "<span style='color:red; background-color:#f8d7da'>$a->name</span>" : $a->name ?></td>
                        <td><?= $a->validasi_userid_kam == 0 ? "<span style='color:red; background-color:#f8d7da'>$a->email</span>" : $a->email ?></td>
                        <td><?= $a->validasi_account == 0 ? "<span style='color:red; background-color:#f8d7da'>$a->account</span>" : $a->account ?></td>
                        <td><?= $a->area ?></td>
                        <td><?= $a->validasi_brand == 0 ? "<span style='color:red; background-color:#f8d7da'>$a->brand</span>" : $a->brand ?></td>
                        <td><?= $a->item ?></td>
                        <td><?= $a->mekanisme ?></td>
                        <td><?= $a->expose ?></td>
                        <td><?= $a->from.' - '.$a->to.'' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php echo form_open_multipart($url); ?>

    <div class="row mt-3 mb-5">
        <div class="col-md-12">

            <input type="hidden" name="signature" value="<?= $signature ?>">
            
            <?php 
                if ($flag_invalid == 1) { ?>
                    <button type="submit" id="btnBack" class="btn btn-submit-black" style ="width: 100%;" disabled>Silahkan perbaiki datanya terlebih dahulu</button>
                <?php }else{ ?>
                    <button type="submit" id="btnBack" class="btn btn-submit-black" style ="width: 100%;">Submit Data</button>
                <?php
                }
            ?>

        </div>
    </div>

    </form>
    <?= form_close(); ?>


</div>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#example').DataTable({
            "pageLength": 10,
            "ordering": false,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
        });
    });
</script>


<script>
    function button()
    {
        var from   = document.getElementById('from').value;
        var to   = document.getElementById('to').value;
        var userid_kam   = document.getElementById('userid_kam').value;
        var account   = document.getElementById('account').value;
        var brand   = document.getElementById('brand').value;
        var item   = document.getElementById('item').value;
        var mekanisme   = document.getElementById('mekanisme').value;
        var expose   = document.getElementById('expose').value;
        if (from && to && userid_kam && account && brand && item && mekanisme && expose) {
            $("#btnKirim").hide();
            $("#btnBack").hide();
            $("#btnLoading").show();
        }
    }
</script>