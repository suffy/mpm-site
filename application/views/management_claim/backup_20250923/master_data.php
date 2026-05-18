<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- <style>
    body 
    {
        font-family: 'Poppins';
        background-color: #181818;
        /* background-color: #222; */
        font-weight: 500;
        font-style: normal;
    }

    .card {
        background-color: #222;
    }

    .title, .card-title, .form-label, .card-text {
        color: #939185;
    }

    .form-control, .custom-input {
        background-color: #222; 
        color: #939185;
    }

    .code-block {
        background-color: #2F3645; 
    }

    .code-block > pre {
        color: #939185;
    }

    .form-control:focus {
        background-color: #e0e0e0; 
    }

    .dark-mode, th {
        background-color: #000;
        color: #939185;
        border: 1px solid #333;
    }    

    tbody > tr > td {
        background-color: #939185;
        font-color: #222;
    }
    
    .filter-nav ul {
        list-style-type: none;
        display: flex;
        padding: 0;
        margin: 0;
        overflow-x: auto;
    }

    .filter-nav li {
        background-color: #272727;
        color: #fff;
        padding: 8px 12px;
        margin-right: 10px;
        border-radius: 8px;
        white-space: nowrap;
        cursor: pointer;
    }

   
    .filter-nav li.active {
        background-color: #B43F3F;
        color: #fff;
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .filter-nav ul::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .filter-nav ul {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    .filter-nav {
        position: sticky;
        top: 0;
        /* background-color: #0f0f0f;  */
        padding: 10px 0;
        z-index: 1; /* Ensure it stays on top of other content */
        transition: background-color 0.7s ease; /* Add transition for smooth fade */
    }

    a {
        color: #fff; /* Set to your desired color */
        text-decoration: none; /* Removes underline */
    }

    a:hover {
        color: #B43F3F;
    }

</style> -->

<?php $this->load->view('management_claim/css/style') ?>

</div>

<?php 
    echo $this->uri->segment(3);
?>

<div class="container-fluid">

<div class="row mt-1" id="input_program">
    <div class="col-md-12 az-content-label title">
        <?= $title ?>   
    </div>
</div>

<nav class="filter-nav">
<ul>
    <li><a href="#master_region" id="myLink">Master Region</a></li>
    <li><a href="#master_template" id="myLink">Master Template</a></li>
    <li><a href="#master_kategori" id="myLink">Master Kategori</a></li>
    <li><a href="#master_segment" id="myLink">Master Segment by Principal</a></li>
    <div style="margin-right: 10px; text-align: center; display: flex; align-items: center">|</div>
    <li><a href="<?= base_url('management_claim/registrasi_program') ?>" id="myLink" target="_blank">Registrasi Program</a></li>
    <li><a href="<?= base_url('management_claim/ajuan_claim') ?>" id="myLink" target="_blank">Pengajuan Claim</a></li>
    <li><a href="<?= base_url('management_claim/monitoring') ?>" id="myLink" target="_blank">Monitoring</a></li>
</ul>
</nav>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title" id="master_region">Master Region Principal</h5>

            <div class="row mt-3">
                <div class="col-md-12">
                <?php 
                    if($this->session->flashdata('pesan_region')){ ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $this->session->flashdata('pesan_region'); ?>
                        </div>
                    <?php
                    }elseif($this->session->flashdata('pesan_success_region')){ ?>
                        <div class="alert alert-success" role="alert">
                            <?= $this->session->flashdata('pesan_success_region'); ?>
                        </div>
                    <?php
                    }
                ?>
                </div>
            </div>

            <div class="row">
                <div class="container">
                    <div class="code-block">
<pre>Information !
- Gunakan menu ini untuk menentukan PIC 1 & 2 berdasarkan site_code dan segment
</pre>
                    </div>
                </div>
            </div>

            <?php echo form_open_multipart($url_master_region); ?>

            <div class="row mt-3">
                <div class="col-lg-3">
                    <label for="site_code" class="form-label">Site</label> 
                </div>
                <div class="col-md-4">
                    <select id="site_code" name="site_code" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-3">
                    <label for="supp" class="form-label">Principal</label> 
                </div>
                <div class="col-lg-4">
                    <select id="supp" name="supp" class="form-control" required>
                        <option value="">Principal ?</option>
                        <?php foreach ($get_principal->result() as $a) { ?>
                            <option value="<?= $a->supp ?>"><?= $a->namasupp ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-3">
                    <label for="site_code" class="form-label">Segment</label> 
                </div>
                <div class="col-md-4">
                    <select name="segment" id="segment" class="form-control" required>
                        <option value="">segment ?</option>
                        <option value="GT">GT</option>
                        <option value="MT">MT</option>
                        <option value="MTI">MTI</option>
                        <option value="all">ALL</option>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-3">
                    <label for="pic_mpm" class="form-label">PIC MPM</label> 
                </div>
                <div class="col-md-4">
                    <select id="pic_mpm" name="pic_mpm" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-3">
                    <label for="pic_principal_1" class="form-label">PIC Principal 1 (ASPS/H)</label> 
                </div>
                <div class="col-md-4">
                    <select id="pic_principal_1" name="pic_principal_1" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-3">
                    <label for="pic_principal_2" class="form-label">PIC Principal 2 (RSPH)</label> 
                </div>
                <div class="col-md-4">
                    <select id="pic_principal_2" name="pic_principal_2" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-lg-3">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-red" id="btnKirim" onclick="return button()">Submit</button>
                    <button class="btn btn-loading" id="btnLoadingRegion" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>
        
            <?php echo form_close(); ?>

            <div class="row mt-3">
                <div class="col-md-12 mt-4">  
                    <table id="table-master-region">
                        <thead>
                            <tr>    
                                <th class="text-center col-1">
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()">
                                </th>
                                <th style="width:1%" class="text-center">No</th>   
                                <th style="width:10px">Site Code</th>                    
                                <th style="width:10px">Branch</th>                    
                                <th style="width:10px">Subbranch</th>                    
                                <th style="width:10px">Segment</th>                    
                                <th style="width:10px">PIC Principal 1</th>                
                                <th style="width:10px">PIC Principal 2</th>                
                                <th style="width:10px">PIC MPM</th>                
                                <th style="width:1%">CreatedBy</th>                  
                                <th style="width:1%">CreatedAt</th>         
                                <th style="width:1%" class="text-center">#</th>      
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($get_master_region->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>                            
                            <td><?= $a->site_code ?></td>
                            <td><?= $a->branch_name ?></td>
                            <td><?= $a->nama_comp ?></td>
                            <td><?= $a->segment ?></td>
                            <td><?= $a->name_principal_1 ?></td>
                            <td><?= $a->name_principal_2 ?></td>
                            <td><?= $a->name_mpm ?></td>
                            <td><?= $a->name_created_by ?></td>
                            <td><?= $a->created_at ?></td>
                            <td class="text-center">  
                                <a href="<?= base_url('management_claim/delete_master_region/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title" id="master_template">Master Template</h5>

            <div class="row mt-3">
                <div class="col-md-12">
                <?php 
                    if($this->session->flashdata('pesan_template')){ ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $this->session->flashdata('pesan_template'); ?>
                        </div>
                    <?php
                    }elseif($this->session->flashdata('pesan_success_template')){ ?>
                        <div class="alert alert-success" role="alert">
                            <?= $this->session->flashdata('pesan_success_template'); ?>
                        </div>
                    <?php
                    }
                ?>
                </div>
            </div>

            <div class="row">
                <div class="container">
                    <div class="code-block">
<pre>Information !
- Master Template akan menjadi sumber data di menu Registrasi Program
</pre>
                    </div>
                </div>
            </div>

            <?php echo form_open_multipart($url_master_template); ?>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="supp" class="form-label">Principal</label> 
                </div>
                <div class="col-lg-4">
                    <select id="supp" name="supp" class="form-control" required>
                        <option value="">Principal ?</option>
                        <?php foreach ($get_principal->result() as $a) { ?>
                            <option value="<?= $a->supp ?>"><?= $a->namasupp ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-2">
                    <label for="kategori" class="form-label">Kategori</label> 
                </div>
                <div class="col-md-4">
                    <select id="kategori" name="kategori" class="form-control" required>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-2">
                    <label for="segment" class="form-label">Segment</label> 
                </div>
                <div class="col-md-4">
                    <select name="segment" id="segment" class="form-control" required>
                        <option value="">segment ?</option>
                        <option value="GT">GT</option>
                        <option value="MT">MT</option>
                        <option value="MTI">MTI</option>
                        <option value="all">ALL</option>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-2">
                    <label for="nama_template" class="form-label">Nama Template</label> 
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="nama_template" name="nama_template" required>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-2">
                    <label for="file_template" class="form-label">Attach</label> 
                </div>
                <div class="col-md-4">
                    <input type="file" class="form-control" id="file_template" name="file_template" required>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-red" id="btnKirimTemplate" onclick="return button()">Submit</button>
                    <button class="btn btn-loading" id="btnLoadingTemplate" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <div class="row">
                <div class="col-md-12 mt-4">  
                    <table id="master-template">
                        <thead>
                            <tr>    
                                <th class="text-center col-1">
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()">
                                </th>
                                <th style="width:1%" class="text-center">No</th>   
                                <th style="width:10px">Principal</th>                    
                                <th style="width:10px">Kategori</th>                    
                                <th style="width:1%">Segment</th>                    
                                <th style="width:15%">Name</th>                    
                                <th style="width:1%">Template</th>               
                                <th style="width:1%">UpdatedBy</th>                  
                                <th style="width:1%">UpdatedAt</th>         
                                <th style="width:1%" class="text-center">#</th>      
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($master_template->result() as $a) : ?>
                        <?php 
                            if ($a->tahun_folder == 2024) {
                                $url = "http://backup.muliaputramandiri.com:81/cisk/assets/uploads/management_claim/2024/";
                            }else{
                                $url = base_url()."assets/uploads/management_claim/2025/";
                            }
                        ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>                            
                            <td><?= $a->namasupp ?></td>
                            <td><?= $a->nama_kategori ?></td>
                            <td><?= $a->segment ?></td>
                            <td><?= $a->nama_template ?></td>
                            <td>
                                <!-- <a href="<?= base_url().'assets/uploads/management_claim/template/'.$a->filename ?>" class="btn btn-submit pending-scm">download</a> -->
                                <a href="<?= $url.'/template/'.$a->filename ?>" class="btn btn-submit pending-scm">download</a>
                            </td>
                            <td><?= $a->username ?></td>
                            <td><?= $a->updated_at ?></td>
                            <td class="text-center">  
                                <a href="<?= base_url('management_claim/delete_master_template/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>


<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title" id="master_kategori">Master Kategori</h5>

            <div class="row mt-3">
                <div class="col-md-12">
                <?php 
                    if($this->session->flashdata('pesan_kategori')){ ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $this->session->flashdata('pesan_kategori'); ?>
                        </div>
                    <?php
                    }elseif($this->session->flashdata('pesan_success_kategori')){ ?>
                        <div class="alert alert-success" role="alert">
                            <?= $this->session->flashdata('pesan_success_kategori'); ?>
                        </div>
                    <?php
                    }
                ?>
                </div>
            </div>

            <div class="row">
                <div class="container">
                    <div class="code-block">
<pre>Information !
- Master Kategori akan menjadi sumber data di menu Registrasi Program
</pre>
                    </div>
                </div>
            </div>

            <?php echo form_open_multipart($url_master_kategori); ?>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="nama_kategori" class="form-label">Nama Kategori</label> 
                </div>
                <div class="col-md-4">
                    <input id="nama_kategori" name="nama_kategori" class="form-control" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-red" id="btnKirimKategori" onclick="return button()">Submit</button>
                    <button class="btn btn-loading" id="btnLoadingKategori" type="button" disabled>
                    ... Please wait ...
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>

            <div class="row">
                <div class="col-md-12 mt-4">  
                    <table id="master-kategori">
                        <thead>
                            <tr>    
                                <th class="text-center col-1">
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()">
                                </th>
                                <th style="width:1%" class="text-center">No</th>   
                                <th style="width:10px">Kategori</th>               
                                <th style="width:1%">UpdatedBy</th>                  
                                <th style="width:1%">UpdatedAt</th>         
                                <th style="width:1%" class="text-center">#</th>      
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($master_kategori->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>        
                            <td><?= $a->nama_kategori ?></td>
                            <td><?= $a->username ?></td>
                            <td><?= $a->updated_at ?></td>
                            <td class="text-center">  
                                <a href="<?= base_url('management_claim/delete_master_kategori/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title" id="master_segment">Master Segment by Principal</h5>

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

            <div class="row">
                <div class="container">
                    <div class="code-block">
<pre>Information !
- Data Segment akan mengikuti Principal yang dipilih
</pre>
                    </div>
                </div>
            </div>

            <?php echo form_open_multipart($url_master_segment); ?>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="supp" class="form-label">Principal</label> 
                </div>
                <div class="col-lg-4">
                    <select id="supp" name="supp" class="form-control" required>
                        <option value="">Principal ?</option>
                        <?php foreach ($get_principal->result() as $a) { ?>
                            <option value="<?= $a->supp ?>"><?= $a->namasupp ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label for="segment" class="form-label">Nama Segment</label> 
                </div>
                <div class="col-md-4">
                    <select name="segment" id="segment" class="form-control" required>
                        <option value="">segment ?</option>
                        <option value="GT">GT</option>
                        <option value="MT">MT</option>
                        <option value="MTI">MTI</option>
                        <option value="NKA">NKA</option>
                        <option value="all">ALL</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3 mb-5">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-4 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-red" id="btnKirimSegment" onclick="return button()">Submit</button>                    
                </div>
            </div>

            <?php echo form_close(); ?>

            <div class="row">
                <div class="col-md-12 mt-4">  
                    <table id="table-master-segment">
                        <thead>
                            <tr>    
                                <th class="text-center col-1">
                                    <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                                    value="click all" onclick="click_all_request()">
                                </th>
                                <th style="width:1%" class="text-center">No</th>   
                                <th style="width:10px">Principal</th>               
                                <th style="width:10px">Segment</th>               
                                <th style="width:1%">UpdatedBy</th>                  
                                <th style="width:1%">UpdatedAt</th>         
                                <th style="width:1%" class="text-center">#</th>      
                            </tr>
                        </thead>
                        <tbody>    
                        <?php $no = 1;
                        foreach ($master_segment->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                                </center>
                            </td>  
                            <td class="text-center"><?= $no++ ?></td>        
                            <td><?= $a->namasupp ?></td>
                            <td><?= $a->nama_segment ?></td>
                            <td><?= $a->username ?></td>
                            <td><?= $a->updated_at ?></td>
                            <td class="text-center">  
                                <a href="<?= base_url('management_claim/delete_master_segment/'.$a->signature) ?>" class="btn-pending" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: red"></i></a>  
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

    </div>
</div>

<br><br>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoadingTemplate").hide();
        
        $("#btnLoadingKategori").hide();
        $('#master-template').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        $('#master-kategori').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });

    
</script>

<script>
    $(document).ready(function () {
        $("#btnBackAccount").show();
        $("#btnLoadingAccount").hide();
        $('#table-account').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackMappingAccount").show();
        $("#btnLoadingMappingAccount").hide();
        $('#table-mapping-account').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackMasterBrand").show();
        $("#btnLoadingMasterBrand").hide();
        $('#table-master-brand').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackFinance").show();
        $("#btnLoadingFinance").hide();
        $('#table-master-finance').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackApproval").show();
        $("#btnLoadingApproval").hide();
        $('#table-mapping-struktural').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackRegion").show();
        $("#btnLoadingRegion").hide();
        $('#table-master-region').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBackSegment").show();
        $("#btnLoadingSegment").hide();
        $('#table-master-segment').DataTable({
            "pageLength": 10,
            "ordering": true,
            // "order": [5, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>

<script>

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_user_principal') ?>',
        data: '',
        success: function(result) {
            $("select[name = userid_approval]").html(result);
            $("select[name = userid_head]").html(result);
            $("select[name = pic_principal_1]").html(result);
            $("select[name = pic_principal_2]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_user_mpm') ?>',
        data: '',
        success: function(result) {
            $("select[name = pic_mpm]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_site') ?>',
        data: '',
        success: function(result) {
            $("select[name = site_code]").html(result);
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_kategori') ?>',
        data: '',
        success: function(result) {
            $("select[name = kategori]").html(result);
        }
    });

</script>

<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
window.addEventListener('scroll', function() {
  var nav = document.querySelector('.filter-nav');
  var sticky = nav.offsetTop;

  if (window.pageYOffset > sticky) {
    nav.classList.add('sticky');
  } else {
    nav.classList.remove('sticky');
  }
});
</script>