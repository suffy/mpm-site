<?php $this->load->view('management_claim/css/style') ?>

</div>

<div class="container-fluid mb-5 mt-5">

    
<?php echo form_open_multipart($url); ?>

    <div class="row mt-1" id="input_program">
        <div class="col-md-12 az-content-label title">
            <?= $title ?>   
        </div>
    </div>

    <nav class="filter-nav">
<ul>
    <li><a href="#input_program" id="myLink">Input Program</a></li>
    <li><a href="#tabel_program" id="myLink">Tabel Program</a></li>
    <div style="margin-right: 10px; text-align: center; display: flex; align-items: center">|</div>
    <li><a href="<?= base_url('management_claim/master_data') ?>" id="myLink" target="_blank">Master Data</a></li>
    <li><a href="<?= base_url('management_claim/ajuan_claim') ?>" id="myLink" target="_blank">Pengajuan Claim</a></li>
    <li><a href="<?= base_url('management_claim/monitoring') ?>" id="myLink" target="_blank">Monitoring</a></li>
</ul>
</nav>

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

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Data Principal : </h5>

            <div class="row mt-3">
                <div class="col-lg-2">
                    <label for="supp">Principal</label> 
                </div>
                <div class="col-lg-4">
                    <select id="supp" name="supp" class="form-control custom-input" required>
                        <option value="">Principal ?</option>
                        <?php foreach ($get_principal->result() as $a) { ?>
                            <option value="<?= $a->supp ?>"><?= $a->namasupp ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="kategori">Kategori</label>
                </div>
                <div class="col-lg-4">
                    <select id="kategori" name="kategori" class="form-control custom-input" required>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="flag_validasi">Flag Validasi</label>
                </div>
                <div class="col-lg-4">
                    <select name="flag_validasi" id="flag_validasi" class="form-control custom-input" required>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="segment">Segment</label> 
                </div>
                <div class="col-lg-4">
                    <select name="segment" id="segment" class="form-control custom-input" required>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="pic">First PIC</label>
                </div>
                <div class="col-lg-4">
                    <select id="pic" name="pic" class="form-control custom-input" required>
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="template">
                        Template
                        <!-- <a href="javascript:void(0)" onclick="get_template()" class="btn pending-scm" style="color: white; background-color: red; padding: 5px; border-radius: 5px;">search</a> -->
                    </label>
                </div>
                <div class="col-lg-4">
                    <select id="id_template" name="id_template" class="form-control custom-input" required>
                    </select>            
                </div>
            </div>            
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Data Program</h5>

            <div class="row mt-3">
                <div class="col-lg-2">
                    <label for="from">Periode Program</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <input class="form-control form-control-md custom-input" id="from" type="date" name="from" required>
                    <input class="form-control form-control-md custom-input" id="from" type="date" name="to" required>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="nomor_surat">Nomor Surat Program</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <input class="form-control form-control-md custom-input" id="nomor_surat" type="text" name="nomor_surat" required>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="nama_program">Nama Program</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <input class="form-control form-control-md custom-input" id="nama_program" type="text" name="nama_program" required>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="duedate">Deadline Ajuan Claim</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <input class="form-control form-control-md custom-input" id="duedate" type="date" name="duedate" required>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="upload_pdf">Upload Dokumen (.pdf)</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <input class="form-control form-control-md custom-input" id="upload_pdf" type="file" name="upload_pdf" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-2"></div>
                <div class="col-lg-5 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-red">Save Registrasi Program</button>
                </div>
            </div>
        
            
        </div>
    </div>

    </form>

    <form action="<?= $url_search ?>" method="GET">

    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex gap-4">
                <div>
                    <h5 class="card-title" id="tabel_program">Tabel Program</h5>
                </div>
                <?php 
                    if ($this->input->get('from') && $this->input->get('to')) { ?>

                    <?php    
                    }else{ ?>
                        <div>
                            <span class="card-text" style="padding : 10px 5px 10px 5px; border-radius: 5px">"secara default, hanya menampilkan 100 row"</span>
                        </div>
                    <?php
                    } ?>
            </div>

            <div class="row mt-5">
                <div class="col-lg-2">
                    <label>Periode Program </label>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="date" name="from" id="from" class="form-control custom-input" value="<?= $this->input->get('from') ?>" required>
                        <input type="date" name="to" id="to" class="form-control custom-input" value="<?= $this->input->get('to') ?>" required>
                    </div>
                </div>
            </div>
    
            <div class="row mt-2">
                <div class="col-lg-2">
                    <label for="nama_program"></label>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="submit" value="Search Data" class="btn btn-submit-red">
                    </div>
                </div>
            </div>
        
    </form>

    <?php echo form_open($url_deadline); ?>

            <div class="row mt-4">        
                <div class="col-md-12"> 
                    <!-- <table id="tabel-registrasi" style="width: 100%; table-layout: fixed; overflow: hidden"> -->
                    <table id="tabel-registrasi-new">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" style="color: black; background-color: grey">
                                </th>
                                <th class="text-center">Principal</th>
                                <th class="text-center">NomorSurat</th>
                                <th class="text-center">NamaProgram</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center" style="width: 200px">Periode</th>
                                <th class="text-center">UpdatedBy</th>
                                <th class="text-center">Deadline</th>
                                <th class="text-center">First PIC</th>
                                <th class="text-center">Dokumen</th>
                                <th class="text-center">Template</th>
                                <th class="text-center">Del</th>                            
                                <!-- <th class="text-center">#</th> -->
                                <th>Peserta Loyalty</th>
                            </tr>
                        </thead>
                        <tbody>     
                            <?php
                            foreach ($get_data->result() as $a) : ?>
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
                                    <input type="checkbox" id="<?= $a->id; ?>" name="options[]" value="<?= $a->id; ?>">
                                    </center>
                                </td> 
                                <td><?= $a->namasupp; ?></td>
                                <td><?= $a->nomor_surat; ?></td>
                                <td>
                                    <?php 
                                        if (strlen($a->nama_program) > 20) { ?>
                                            <?= substr($a->nama_program, 0, 20).'...'; ?>
                                        <?php
                                        }else{
                                            echo $a->nama_program;
                                        }
                                    ?>
                                
                                </td>
                                <td><?= $a->nama_kategori; ?></td>
                                <!-- <td><?= $a->from.' sd '.$a->to; ?></td> -->
                                <td><?= date('d M', strtotime($a->from)). ' - '.date('d M y', strtotime($a->to)); ?></td>
                                <td>
                                    <?php 
                                        if ($a->updated_at) {
                                            $updated_at = date('d M y', strtotime($a->updated_at));
                                        }else{
                                            $updated_at = date('d M y', strtotime($a->created_at));
                                        }
                                    ?>
                                    <?= $a->username.' at '.$updated_at; ?>
                                    
                                </td>   
                                <td><?= date('d M y', strtotime($a->duedate)) ?></td>   
                                <td><?= $a->pic; ?></td>   
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= $url.'/registrasi_program/'.$a->upload_pdf ?>" class="btn btn-submit pending-scm" target="_blank"><font size="2px">download</font></a>
                                    </div>
                                </td>   
                                <td align="center">
                                    <?php 
                                        if ($a->id_template) { ?>
                                            <a href="<?= $url.'/template/'.$a->filename ?>" class="btn btn-submit-orange" target="_blank">download</a>
                                        <?php
                                        }else{ ?>
                                            <label><font size="2px"><i>blank</i></font></label>    
                                        <?php
                                        }
                                    ?>


                                    
                                </td>                   
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url().'management_claim/edit_registrasi_program/'.$a->signature ?>" class="btn btn-submit pending-rilis-po">edit</a>
                                        <a href="<?= base_url().'management_claim/delete_registrasi_program/'.$a->signature ?>" onclick="return confirm('Anda yakin menghapus data ini ?')" class="btn btn-submit pending-finance">del</a>
                                    </div>
                                </td>         
                                <td>
                                    <?php 
                                        if ($a->kategori == '1') { ?>
                                            <a href="<?= base_url().'management_claim/download_peserta_loyalty/'.$a->signature ?>" class="btn btn-submit-orange">export</a>
                                        <?php
                                        }
                                    ?>
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
            <h5 class="card-title">Cara mengubah deadline : </h5>
            <p class="card-text">1. Ceklist program yang ingin diubah <br>
            2. Masukkan tanggal deadline dan Klik Update Deadline<br></p>

            <div class="row mt-3">
                <div class="col-lg-2">
                    <label for="nama_program">Deadline yang diinginkan </label>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input type="date" name="deadline" class="form-control custom-input" required>
                    </div>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-lg-2"></div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input type="submit" value="Update Data Deadline" class="btn btn-submit-red">
                    </div>
                </div>
            </div>            
        </div>
    </div>

    
    <?= form_close(); ?>

    <script>
        $(document).ready(function () {
            $("#btnBack").show();
            $("#btnLoading").hide();
            $('#tabel-registrasi-new').DataTable({
                "pageLength": 10,
                "ordering": false,
                // "order": [0, 'desc'],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                scrollX: true,
            });
        });


        $("select[name = supp]").on("change", function() 
        {    
            let supp = document.getElementById('supp').value;            
            console.log('supp ' + supp)

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('management_claim/master_flag_validasi') ?>',
                data: {
                    'supp': supp,     
                },
                success: function(result) {
                    $("select[name = flag_validasi]").html(result);
                }
            });

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('management_claim/master_segment') ?>',
                data: {
                    'supp': supp,     
                },
                success: function(result) {
                    $("select[name = segment]").html(result);
                }
            });

            $.ajax({
                
                type: 'POST',
                url: '<?php echo base_url('management_claim/master_kategori') ?>',
                success: function(result) {
                    $("select[name = kategori]").html(result);
                }
            });

            $.ajax({
                
                type: 'POST',
                url: '<?php echo base_url('management_claim/master_flag_pic') ?>',
                data: {
                    'supp': supp,     
                },
                success: function(result) {
                    $("select[name = pic]").html(result);
                }
            }); 
            
        });

        $("select[name = kategori]").on("change", function() 
        {    
            get_template();
        });

        $("select[name = segment]").on("change", function() 
        { 
            get_template();
        });

        function get_template()
        {
            let suppx = document.getElementById('supp').value;
            let segmentx = document.getElementById('segment').value;
            let kategorix = document.getElementById('kategori').value;
            // console.log("supp : "+ suppx)
            // console.log("segmentx : "+ segmentx)
            // console.log("kategorix : "+ kategorix)

            $.ajax({
                
                type: 'POST',
                url: '<?php echo base_url('management_claim/master_template') ?>',
                data: {
                    'supp': suppx,     
                    'segment': segmentx,
                    'kategori': kategorix
                },
                success: function(result) {
                    $("select[name = id_template]").html(result);
                }
            }); 
        }
    </script>

    <script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>