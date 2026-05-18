<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


<?php $this->load->view('management_claim/css/style') ?>

</div>

<div class="container-fluid mb-5">
    
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

            <!-- <h5 class="card-title">Data Principal : </h5> -->

            <div class="row mt-3">
                <div class="col-lg-6">
                    <h5 class="card-title">Data Principal : </h5>
                </div>
                
                <div class="col-lg-2">
                    <label for="supp">Current</label> 
                </div>

            </div>

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
                <div class="col-lg-2">
                    <label for="supp"><i><?= $namasupp; ?></i></label> 
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
                <div class="col-lg-2">
                    <label for="supp"><i><?= $kategori; ?></i></label> 
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
                <div class="col-lg-2">
                    <label for="supp"><i><?= $nama_status_validasi; ?></i></label> 
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
                <div class="col-lg-2">
                    <label for="supp"><i><?= $segment; ?></i></label> 
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
                <div class="col-lg-2">
                    <label for="supp"><i><?= $pic; ?></i></label> 
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
                <div class="col-lg-2">
                    <label for="supp"><?= ($nama_template == null) ? '<i>no template</i>' : $nama_template; ?></label> 
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
                    <input class="form-control form-control-md custom-input" id="from" type="date" name="from" value="<?= $from ?>" required>
                    <input class="form-control form-control-md custom-input" id="from" type="date" name="to" value="<?= $to ?>" required>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="nomor_surat">Nomor Surat Program</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <input class="form-control form-control-md custom-input" id="nomor_surat" type="text" name="nomor_surat" value="<?= $nomor_surat ?>" required>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="nama_program">Nama Program</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <input class="form-control form-control-md custom-input" id="nama_program" type="text" name="nama_program" value="<?= $nama_program ?>" required>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="duedate">Deadline Ajuan Claim</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <input class="form-control form-control-md custom-input" id="duedate" type="date" name="duedate" value="<?= $duedate ?>" required>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-lg-2">
                    <label for="upload_pdf">Upload Dokumen (.pdf)</label>
                </div>
                <div class="col-lg-4 d-flex flex-row">
                    <?php 
                        if ($tahun_folder == 2024) {
                            $url = "http://backup.muliaputramandiri.com:81/cisk/assets/uploads/management_claim/2024/";
                        }else{
                            $url = base_url()."assets/uploads/management_claim/2025/";
                        }
                    ?> 
                    <a href="<?= $url.'/registrasi_program/'.$upload_pdf ?>" class='btn btn-submit-cream'>
                    <?= $upload_pdf ?></a>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-lg-2">
                    
                </div>
                <div class="col-lg-4 d-flex flex-row">                    
                    <input class="form-control form-control-md custom-input" id="upload_pdf" type="file" name="upload_pdf">
                    <input class="form-control form-control-md custom-input" type="hidden" name="upload_pdf_old" value=<?= $upload_pdf ?>>
                </div>
            </div>

            <input type="hidden" class="custom-input" name="signature_program" value="<?= $signature_program ?>">
            <input type="hidden" class="custom-input" name="id_program" value="<?= $id_program ?>">

            <div class="row mt-5">
                <div class="col-lg-2"></div>
                <div class="col-lg-5 d-flex flex-row">
                    <button type="submit" class="btn btn-submit-red">Update Program</button>
                </div>
            </div>
        
            
        </div>
    </div>

    </form>

    <script>
        $(document).ready(function () {
            $("#btnBack").show();
            $("#btnLoading").hide();
            $('#tabel-registrasi').DataTable({
                "pageLength": 10,
                "ordering": true,
                "order": [0, 'desc'],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                scrollX: true
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