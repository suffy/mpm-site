<style>

    input, textarea, select {
    padding: 10px;
    max-width: 100%;
    width:100%;
    line-height: 1.5;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: 1px 1px 1px #999;
    }
</style>

</div>
<div class="container-fluid">

<div class="row mb-4">
    <div class="col-md-12">
        <h3><?= $title ?></h3>
    </div>
</div>

<div class="row">
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

<?php echo form_open($url); ?>

<div class="row mt-3">
    <div class="col-md-2">
        <label for="company">Company</label> 
    </div>
    <div class="col-md-4">
        <input type="text" name="company" id="company" value="<?= $company ?>" readonly>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="npwp">NPWP</label> 
    </div>
    <div class="col-md-4">
        <input type="text" name="npwp" id="npwp" value="<?= $npwp ?>" readonly>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="email">Email</label> 
    </div>
    <div class="col-md-4">
        <textarea name="email" cols="30" rows="3" readonly><?= $email ?></textarea>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-2">
        <label for="email">Tipe</label> 
    </div>
    <div class="col-md-4">
        <select name="tipe"  required>
            <option value="">Pilih Tipe</option>
            <option value="S">SPK</option>
            <option value="A" disabled>Alokasi</option>
        </select>
    </div>
</div>


<div class="card-block mt-1 mb-5">
    <div class="row">
        <div class="col-md-12">
             <table id="table-data" class="display mb-4">
                <thead>
                    <tr>
                        <th width="5%">Pilih</th>
                        <th width="10%">KodeAlamat</th>
                        <th width="20%">Subbranch</th>
                        <th width="70%">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get_data->result() as $a) : ?>
                    <tr>
                        <td class="text-center">
                            <input class="" type="radio" name="kode_alamat" value="<?= $a->kode_alamat ?>" required>
                        </td>
                        <td><?= $a->kode_alamat ?></td>
                        <td><?= $a->nama_comp ?></td>
                        <td>
                            <textarea cols="30" rows="3" readonly><?= $a->alamat ?></textarea>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-12 d-flex justify-content-center btn-group">
         <a href="<?= base_url('spk/keranjang_belanja') ?>" class="btn btn-submit-black" style="width: 50%">Kembali</a>
        <input type="submit" value="Preview SPK" class="btn btn-submit-orange" style="width: 50%">
    </div>
</div>


<?= form_close(); ?>



<script>
    $(document).ready(function () 
    {
        $('#table-data').DataTable({
            "pageLength": 100,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "bInfo" : false,
            "bPaginate": false
            // scrollX: true
        });
       
    });

    
    
</script>