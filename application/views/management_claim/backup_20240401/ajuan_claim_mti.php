<!-- <style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    .batas{
        border: 1px dotted grey;
        border-radius: 5px;
    }

    th{
        font-weight: bold;
        background-color: #383838;
        border: 1px solid #383838;
        color: #f0f0f0;
        align-items: center;
        align-content: center;
        font-size: 13px;
        /* text-align: center; */
    }
    td{
        background-color: #ffffff;
        border: 1px solid #383838;
        font-size: 11px;
        
        /* align-items: center;
        align-content: center; */
    }

    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }

    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #365486;
    }

    .btn-hardcopy {
        color: #f0f0f0;
        background-color: #37B5B6;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-hardcopy:hover {
        color: black;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #FE7A36;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingprincipal {
        color: #f0f0f0;
        background-color: #D04848;
        border-radius: 10px;
        border: 2px solid black;
    }
    
    .btn-null {
        color: black;
        background-color: #F9EFDB;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-null:hover {
        color: black;
        background-color: #E0CCBE;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-ikut{
        color: #000;
        background-color: #BED1CF;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-tidakikut{
        color: #fff;
        background-color: #3C3633;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-tidakikut:hover{
        color: black;
        background-color: #747264;
    }

    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
    .btn-custom{
        background-color: white;
        color: black;
        border-radius: 5px;
        border: 2px solid red;
        /* margin-left: 1px; */
        /* margin-top: 20px; */
        padding: 2px;
        width: 10px;
        height: 10px;
    }
</style> -->

</div>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>
</div>

<div class="container">

    <form action="<?= $url ?>">

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label">Principal</label> 
            </div>
            <div class="col-md-4">
                <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001" <?= $this->input->get('supp') == 001 ? 'selected' : '' ?>> Deltomed</option>
                    <option value="002" <?= $this->input->get('supp') == 002 ? 'selected' : '' ?>> Marguna </option>
                    <option value="005" <?= $this->input->get('supp') == 005 ? 'selected' : '' ?>> Ultra Sakti </option>
                    <option value="012" <?= $this->input->get('supp') == 012 ? 'selected' : '' ?>> Intrafood </option>
                    <option value="013" <?= $this->input->get('supp') == 013 ? 'selected' : '' ?>> Strive </option>
                    <option value="015" <?= $this->input->get('supp') == 015 ? 'selected' : '' ?>> MDJ </option>
                    <option value="025" <?= $this->input->get('supp') == 025 ? 'selected' : '' ?>> PT. GOOD PHARMA DERMATOLOGY </option>
                    <option value="026" <?= $this->input->get('supp') == 026 ? 'selected' : '' ?>> PT. GUNUNG SUBUR SEJAHTERA </option>

                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="from" class="form-label">Periode</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="from" id="from" min="2023-12-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                    <input type="date" name="to" id="to" min="2023-12-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="pic" class="form-label">Nama PIC</label>
            </div>
            <div class="col-md-4">
                <select id="pic" name="pic" class="form-control" required>
                    <option value="all"> All </option>
                    <option value="18" <?= $this->input->get('pic') == '18' ? 'selected' : '' ?>> Ismi </option>
                    <option value="444" <?= $this->input->get('pic') == '444' ? 'selected' : '' ?>> Ambar </option>
                    <option value="561" <?= $this->input->get('pic') == '561' ? 'selected' : '' ?>> Adi </option>
                    <option value="557" <?= $this->input->get('pic') == '557' ? 'selected' : '' ?>> Rani </option>
                    <option value="99" <?= $this->input->get('pic') == '99' ? 'selected' : '' ?>> Yuli </option>
                    <option value="812" <?= $this->input->get('pic') == '812' ? 'selected' : '' ?>> Dea </option>
                    <option value="297" <?= $this->input->get('pic') == '297' ? 'selected' : '' ?>> Suffy </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
            </div>
            <div class="col-md-4">
                <select id="status" name="status" class="form-control" required>
                    <option value=""> -- Pilih Status -- </option>
                    <option value="all" <?= $this->input->get('status') == 'all' ? 'selected' : '' ?>> All </option>
                    <option value="1" <?= $this->input->get('status') == '1' ? 'selected' : '' ?>> PENDING DP </option>
                    <option value="2" <?= $this->input->get('status') == '2' ? 'selected' : '' ?>> PENDING MPM </option>
                    <option value="3" <?= $this->input->get('status') == '3' ? 'selected' : '' ?>> REJECT MPM </option>
                    <option value="4" <?= $this->input->get('status') == '4' ? 'selected' : '' ?>> PENDING PRINCIPAL </option>
                    <option value="5" <?= $this->input->get('status') == '5' ? 'selected' : '' ?>> REJECT PRINCIPAL </option>
                    <option value="6" <?= $this->input->get('status') == '6' ? 'selected' : '' ?>> APPROVE </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label"></label> 
            </div>
            <div class="col-md-4">
                <input type="submit" value="cari program" class="btn btn-submit">
                <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-null">Tampilkan ALL Data</a>
            </div>
        </div>

    </form>
</div>

<div class="container mt-4">

    <div class="row mt-2 ms-5">
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

    <div class="card-block mt-1 mb-5">
        <div class="row">
            <div class="col-md-12">

                <!-- <table id="ajuan" class="display" style="overflow-x: scroll;"> -->
                <table id="ajuan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>DP</th>
                            <th>Status Pelaporan</th>
                            <th>Status Hardcopy</th>
                            <th style="width:120px">Ikut Program ?</th>
                            <th>No Ajuan Claim</th>
                            <th>Principal</th>
                            <th>PIC</th>
                            <th>Jatuh Tempo</th>
                            <th>Kategori</th>
                            <th>Nama Program</th>
                            <th>No Surat</th>
                            <th>Periode</th>
                            <th style="width:150px" class="text-center col-3">Syarat</th>                            
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <?php 
                                    if ($a->nama_comp) { ?>
                                        <label class="form-label"><?= $a->nama_comp; ?></label>
                                    <?php
                                    }else{ ?>
                                        <label class="form-label">Belum Mengajukan</label>
                                    <?php
                                    }
                                ?>                                
                            </td> 
                            <td>
                                <?php 
                                if ($a->status == 1) { // PROSES DP
                                    $color = "btn-pendingdp";
                                }elseif($a->status == 2){ // PROSES MPM
                                    $color = "btn-pendingmpm";
                                }elseif($a->status == 3){ // PROSES PRINCIPAL AREA
                                    $color = "btn-danger"; 
                                }elseif($a->status == 4){ // PROSES PRINCIPAL
                                    $color = "btn-pendingprincipal";
                                }elseif($a->status == 5){ // PROSES KIRIM BARANG
                                    $color = "btn-info";
                                }elseif($a->status == 6){ // PROSES TERIMA BARANG
                                    $color = "btn-danger";
                                }elseif($a->status == 7){ // PROSES PEMUSNAHAN
                                    $color = "btn-info";
                                }elseif($a->status == 8 || $a->status == 9){ // BARANG DITERIMA dan Pemusnahan
                                    $color = "btn-dark";
                                }elseif($a->status == 10){ // REJECT PRINCIPAL HO
                                    $color = "btn-dark";
                                }else{
                                    $color = "btn-null";
                                }
                                ?>

                                <?php 
                                    if ($a->status == null) { ?>
                                        <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?> btn-sm">Belum ada</a>
                                    <?php
                                    }elseif($a->status == 99){ ?>
                                        <label class="btn btn-tidakikut"><?= $a->nama_status_keikutsertaan ?></label>
                                    <?php
                                    }else{ ?>
                                        <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?> btn-sm"><?= $a->nama_status ?></a>
                                    <?php
                                    }
                                ?>
                            </td>    
                            <td>
                                <?php 
                                    if ($a->status_hardcopy) { ?>
                                        <a href="<?= base_url().'management_claim/routing_hardcopy/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-pendingmpm"><?= $a->nama_status_hardcopy ?></a>                                        
                                    <?php
                                    }else{ ?>
                                        <a href="<?= base_url().'management_claim/routing_hardcopy/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-null">Belum ada</a>
                                    <?php
                                    }
                                ?>
                            </td>       
                            <td>
                                <?php 
                                    if ($a->status_keikutsertaan == 1) { ?>
                                        <label class="btn btn-ikut"><?= $a->nama_status_keikutsertaan ?></label>
                                    <?php 
                                    }elseif ($a->status_keikutsertaan == null && $a->status == null) { ?>
                                        
                                        <a href="<?= base_url().'management_claim/flag_keikutsertaan/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-null" onclick="return confirm('Apakah anda yakin tidak ingin claim program ini ?')">Tidak Ikut ? </a>
                                    <?php
                                    }elseif ($a->status_keikutsertaan == null && $a->status <> null) { ?>
                                        <label class="btn btn-ikut">Ya, saya ikut</label>
                                    <?php
                                    }                                    
                                    else{ ?>
                                        <label class="btn btn-tidakikut"><?= $a->nama_status_keikutsertaan ?></label>
                                    <?php
                                    }
                                ?>
                            </td>                     
                            <td><?= $a->nomor_ajuan; ?></td>  
                            <td><?= $a->namasupp; ?></td>                                                   
                            <td><?= $a->username; ?></td>   
                            <td><?= $a->duedate; ?></td>    
                            <td><?= $a->kategori; ?></td>
                            <td><?= $a->nama_program; ?></td>
                            <td>
                                <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_pdf ?>" class="btn btn-null" target="_blank"><?= $a->nomor_surat; ?></a>                                
                            </td>
                            <td><?= $a->from.' sd '.$a->to; ?></td>                        
                            <td><?= $a->syarat; ?></td>                              
                             
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>




<script>
    $(document).ready(function () {
        $('#ajuan').DataTable(
            {
                scrollX: true
            }
        );
    });
</script>