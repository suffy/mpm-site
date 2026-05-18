<style>
    input, textarea, select 
    {
        padding: 10px;
        max-width: 100%;
        width:100%;
        line-height: 1.5;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-shadow: 1px 1px 1px #999;
    }

    .span-text{
        font-weight: bold;
        /* background-color: #f1f1f1; */
        padding: 5px 10px 5px 10px;
        border-radius: 10px;
        line-height: 25px;
        display: inline-block;
    }

    .table-container {
        padding: 20px;
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        /* border-collapse: collapse;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden; */
    }
    
    thead {
        /* background-color: #f8f9fa; */
    }
    
    th {
        /* padding: 16px 12px;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #e9ecef;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px; */
    }
    
    td {
        /* padding: 16px 12px;
        border-bottom: 1px solid #e9ecef;
        color: #495057;
        font-size: 14px; */
    }
    
    tbody tr {
        transition: all 0.2s ease;
    }
    
    tbody tr:hover {
        background-color: #f1f5fd;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }
    
    .radio-cell {
        text-align: center;
    }
    
    .custom-radio {
        display: inline-block;
        position: relative;
        cursor: pointer;
        width: 20px;
        height: 20px;
    }
    
    .custom-radio input {
        opacity: 0;
        position: absolute;
        width: 0;
        height: 0;
    }
    
    .radio-checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #fff;
        border: 2px solid #adb5bd;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .custom-radio:hover .radio-checkmark {
        border-color: #4b6cb7;
    }
    
    .custom-radio input:checked ~ .radio-checkmark {
        background-color: #4b6cb7;
        border-color: #4b6cb7;
    }
    
    .radio-checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    
    .custom-radio input:checked ~ .radio-checkmark:after {
        display: block;
    }
    
    .custom-radio .radio-checkmark:after {
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    .address-cell {
        max-width: 400px;
    }
    
    .address-text {
        width: 100%;
        padding: 10px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background-color: #f8f9fa;
        font-size: 14px;
        line-height: 1.5;
        resize: none;
        transition: all 0.2s ease;
    }
    
    .address-text:focus {
        outline: none;
        border-color: #4b6cb7;
        background-color: white;
    }
    
    .footer {
        padding: 15px 30px;
        /* background-color: #f8f9fa; */
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e9ecef;
    }
    
    .selected-info {
        font-size: 14px;
        color: #6c757d;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
    }
    
    .btn-primary {
        background-color: #4b6cb7;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #3a5795;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(75, 108, 183, 0.3);
    }
    
    .btn-outline {
        background-color: transparent;
        border: 1px solid #6c757d;
        color: #6c757d;
    }
    
    .btn-outline:hover {
        background-color: #6c757d;
        color: white;
    }

    .span-text{
        font-weight: bold;
        background-color: #f1f1f1;
        color: #000000;
        padding: 5px 10px 5px 10px;
        border-radius: 10px;
        line-height: 25px;
        display: inline-block;
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
        <span class="span-text"><?= $company ?></span>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="npwp">NPWP</label> 
    </div>
    <div class="col-md-4">
         <span class="span-text"><?= $npwp ?></span>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-2">
        <label for="email">Email</label> 
    </div>
    <div class="col-md-4">
        <span class="span-text"><?= $email ?></span>
    </div>
</div>

<div class="row mt-3">
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

<input type="hidden" name="company" value="<?= $company ?>">
<input type="hidden" name="npwp" value="<?= $npwp ?>">
<input type="hidden" name="email" value="<?= $email ?>">

<div class="card-block mt-1">
    <div class="row">
        <div class="col-md-12">
            <!-- <div class="table-container"> -->
                <table id="table-data">
                    <thead>
                        <tr>
                            <th width="5%">Pilih</th>
                            <th width="10%">KodeAlamat</th>
                            <th width="20%">Subbranch</th>
                            <th width="65%">Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td class="text-center radio-cell">
                                <label class="custom-radio">
                                    <input type="radio" name="kode_alamat" id="kode_alamat[<?= $a->kode_alamat ?>]" value="<?= $a->kode_alamat ?>" required>
                                    <span class="radio-checkmark"></span>
                                </label>
                            </td>
                            <td><label for="kode_alamat[<?= $a->kode_alamat ?>]"><span class="span-text"><?= $a->kode_alamat ?></span></label></td>
                            <td><label for="kode_alamat[<?= $a->kode_alamat ?>]"><span class="span-text"><?= $a->nama_comp ?></span></label></td>
                            <td class="address-cell">
                                <!-- <textarea class="address-text" cols="30" rows="3" readonly><?= $a->alamat ?></textarea> -->
                                <label for="kode_alamat[<?= $a->kode_alamat ?>]"><span class="span-text"><?= $a->alamat ?></span></label>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <!-- </div> -->
            <div class="footer">
                <div class="selected-info">
                    <i class="fas fa-info-circle"></i> Pilih salah satu alamat untuk melanjutkan
                </div>
                <div class="footer-actions">
                    <a href="<?= base_url('spk/keranjang_belanja') ?>" class="btn btn-outline">
                        <i class="fas fa-times"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Konfirmasi Pilihan</button>
                </div>
            </div>
        </div>
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