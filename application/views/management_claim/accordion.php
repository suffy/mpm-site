<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<style>
    body 
    {
        font-family: 'Poppins';
        font-style: normal;
    }
    /* .collapse {
        visibility: hidden;
    } */
    .collapse.show {
        visibility: visible;
        display: block;
    }
    .collapsing {
        position: relative;
        height: 0;
        overflow: hidden;
        -webkit-transition-property: height, visibility;
        transition-property: height, visibility;
        -webkit-transition-duration: 0.8s;
        transition-duration: 0.8s;
        -webkit-transition-timing-function: ease;
        transition-timing-function: ease;
    }
   
    textarea {
    font-size: 0.8rem;
    letter-spacing: 1px;
    }

    textarea {
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

<div class="row mt-3">
    <div class="col-md-12">
        <p>
            <button class="btn btn-submit-red" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2" style="border-radius: 10px; border: none;">Lihat Detail Program</button>
            <button class="btn btn-submit" type="button" data-toggle="collapse" data-target=".multi-collapse-history" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2" style="border: none; border-radius: 10px;">Lihat History</button>
            <button class="btn btn-warning" type="button" data-toggle="collapse" data-target=".multi-collapse-dokumentasi" aria-expanded="false" aria-controls="multiCollapseExample3 multiCollapseExample3" style="border-radius: 10px;">Dokumentasi</button>
        </p>

        <div class="row" style="display: flex; justify-content: center;">                    
            <div class="col-md-6">
                <div class="collapse multi-collapse" id="multiCollapseExample1">
                    <div class="card card-body">
                        <div class="container">    
                            
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="kategori" ><strong>DATA PROGRAM</strong></label>
                                </div>
                            </div>                        
                        
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="kategori" >Status</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori" readonly><?= ($nama_status) ? $nama_status : 'BLANK' ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >Status Internal</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= ($nama_status_internal) ? $nama_status_internal : 'BLANK' ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >Principal</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= $namasupp ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >Segment</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= $segment ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >No Ajuan Claim</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= ($nomor_ajuan) ? $nomor_ajuan : 'BLANK' ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >Kategori</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= $kategori ?></label>
                                </div>
                            </div>

                            

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >Periode</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= $from.' s/d '.$to ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >Nama Program</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea id="" cols="30" rows="3" readonly><?= $nama_program ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="kategori" >Nomor Surat</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= $nomor_surat ?></label>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="status_validasi" >Status Validasi</label>
                                </div>
                                <div class="col-md-9">
                                    <!-- <label for="status_validasi"  readonly><?= $nama_status_validasi.' - '.$keterangan ?></label> -->
                                    <textarea name="" id=""  readonly><?= $nama_status_validasi.' - '.$keterangan ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="kategori" >Attachment</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 

                                    // echo "tahun_folder : ".$tahun_folder;
                                    if ($tahun_folder == 2024) {
                                        $url = "http://backup.muliaputramandiri.com:81/cisk/assets/uploads/management_claim/2024/";
                                    }else{
                                        $url = base_url()."assets/uploads/management_claim/2025/";
                                    }
                                        

                                        if ($upload_pdf) { ?>
                                            <a href="<?= $url.'/registrasi_program/'.$upload_pdf ?>" class='btn btn-submit-red' style="border:none">
                                            download</a>
                                        <?php
                                        }else{ ?>
                                            <label ><i>no file</i></label>
                                        <?php
                                        }
                                    ?>    
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="kategori" >Registrasi Program By</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= $username ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="deadline" >Deadline</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="deadline"  readonly><?= $duedate ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >First PIC</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= $pic ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" >PIC On Duty</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="kategori"  readonly><?= $pic_on_duty.' - '.$email_on_duty ?></label>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="collapse multi-collapse" id="multiCollapseExample1">
                    <div class="card card-body">
                        <div class="container">     

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="kategori" ><strong>DATA CLAIM DP</strong></label>
                                </div>
                            </div>  

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="supp">Nomor Pengajuan</label>
                                </div>
                                <div class="col-md-9">
                                    <!-- <input type="text" name="nomor_ajuan" value="<?= $nomor_ajuan ?>" readonly> -->
                                    <textarea name="" id=""><?= $nomor_ajuan ?></textarea>
                                </div>
                            </div>
                    
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="supp">Branch</label>
                                </div>
                                <div class="col-md-9">
                                    <!-- <input type="text"  name="branch_name" value="<?= $branch_name.' - '.$nama_comp.' - '.$site_code ?>" readonly> -->
                                    <textarea name="" id=""><?= $branch_name.' - '.$nama_comp.' - '.$site_code ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Nama Pengirim</label>
                                </div>
                                <div class="col-md-9">
                                    <label  readonly><?= $nama_pengirim ?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Email Pengirim</label>
                                </div>
                                <div class="col-md-9">
                                    <label  readonly><?= $email_pengirim ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="tanggal_claim">Tanggal Claim</label>
                                </div>
                                <div class="col-md-9">
                                    <label  readonly><?= $tanggal_claim ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="tanggal_claim">Created_at</label>
                                </div>
                                <div class="col-md-9">
                                    <label  readonly><?= $created_at ?></label>
                                </div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Attachment Data</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        // echo "params_folder : ".$params_folder;
                                        if ($params_folder == "import") {
                                            $params_folder_url = "import/";
                                        }else{
                                            $params_folder_url = "";
                                        }

                                        

                                        if ($ajuan_excel) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$params_folder_url.''.$ajuan_excel ?>" class='btn btn-submit-cream'>
                                            <?= $ajuan_excel ?></a>
                                        <?php
                                        }else{ ?>
                                            <label ><i>file not found</i></label>
                                        <?php
                                        }

                                        if ($ajuan_zip) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$params_folder_url.''.$ajuan_zip ?>" class='btn btn-submit-cream'>
                                            <?= $ajuan_zip ?></a>
                                        <?php
                                        }else{ ?>
                                            <label ><i>file not found</i></label>
                                        <?php
                                        }
                                    ?>     
                                </div>
                            </div>     -->
                            
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="tanggal_terima_barang">Attachment Data</label>
                                </div>
                                <div class="col-md-9">
                                    <?php 
                                        if ($tahun_folder_ajuan == 2024) {
                                            $url = "http://backup.muliaputramandiri.com:81/cisk/assets/uploads/management_claim/2024/";
                                        }else{
                                            $url = base_url()."assets/uploads/management_claim/2025/";
                                        }

                                        if ($ajuan_excel) { ?>
                                            <a href="<?= $url.$id_kategori.'/'.$ajuan_excel ?>" class='btn btn-submit-cream' style="border:none">
                                            click here</a>
                                        <?php
                                        }

                                        if ($ajuan_zip) { ?>
                                            <a href="<?= $url.$id_kategori.'/'.$ajuan_zip ?>" class='btn btn-submit-cream' style="border:none">
                                            click here</a>
                                        <?php
                                        }
                                    ?>     
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>        
        </div>

        <div class="row mt-2 mb-2">
                    
            <div class="col-md-12">
                <div class="collapse multi-collapse-history" id="multiCollapseExample1">
                    <div class="card card-body">
                        <div class="container-fluid">    
                            
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="kategori" ><strong>Log Status</strong></label>
                                </div>
                            </div>          
                            
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="log-history" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <!-- <th>StatusApproval</th> -->
                                                <th class="text-center" style="width: 100px;">User <i class="typcn typcn-arrow-right-outline"> on Duty</th>
                                                <!-- <th>NextOnDuty</th> -->
                                                
                                                <th class="text-center" style="width: 100px;">Keterangan</th>
                                                <th class="text-center">CreatedAt</th>
                                                <th class="text-center">DueDate</th>
                                                <!-- <th>StatusClaim</th> -->
                                                <th class="text-center">StatusClaimDetail</th>
                                                <th class="text-center">File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($get_log->result() as $a) : ?>
                                            <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <!-- <td>
                                                    <?php
                                                        if ($a->deleted_at) { ?>
                                                            <span class="badge bg-danger">Deleted</span>
                                                        <?php
                                                        }else{ 
                                                            if ($a->status_approval == 1) { ?>
                                                                <span class="badge bg-success">Approved</span>
                                                            <?php
                                                            }else{ ?>
                                                                <span class="badge bg-danger">On Progress</span>
                                                            <?php
                                                            }
                                                        }
                                                    ?>
                                                </td> -->
                                                <td><?= $a->username ?> <i class="typcn typcn-arrow-right-outline"></i> <strong><?= $a->on_duty_username ?></strong></td>
                                                
                                                <td><?= $a->keterangan ?></td>
                                                <td class="text-center"><?= date('d M y', strtotime($a->created_at)); ?></td>
                                                <td>
                                                    <?php 
                                                        if ($a->duedate_response) { 
                                                            if ($a->duedate_response < date('Y-m-d')) { ?>
                                                                <span for="" class="pending-finance" style="font-size: 12px; padding: 5px;border-radius: 5px">
                                                                <?php
                                                                    echo date('d M y', strtotime($a->duedate_response)). ' ('.date_diff(date_create(date('Y-m-d')), date_create($a->duedate_response))->format('%a days ago').')';
                                                                ?>
                                                                </span>
                                                            <?php
                                                            }else{ ?>
                                                            <span for="" class="pending-scm" style="font-size: 12px; padding: 5px;border-radius: 5px">
                                                            <?php
                                                                echo date('d M y', strtotime($a->duedate_response)). ' ('.date_diff(date_create(date('Y-m-d')), date_create($a->duedate_response))->format('%a days left').')';
                                                            ?>
                                                            </span>
                                                            <?php
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                                <!-- <td>
                                                    <?php 
                                                    if ($a->status == '1') { 
                                                        $class = " pending-finance";
                                                    }
                                                    elseif ($a->status == '2') { 
                                                        $class = " pending-scm";
                                                    }elseif ($a->status == '5') { 
                                                        $class = " pending-rilis-po";
                                                    }else{
                                                        $class = " finish";
                                                    }
                                                    ?>
                                                    <label for="" class=<?= $class ?>><?= strtolower($a->nama_status) ?></label>
                                                    
                                                </td> -->
                                                <td class="text-center">
                                                    <?php 
                                                    if ($a->status_internal_pic == 'MPM') { 
                                                        $class = " pending-finance";
                                                    }
                                                    elseif ($a->status_internal_pic == 'PRINCIPAL') { 
                                                        $class = " pending-scm";
                                                    }else{
                                                        $class = " pending-rilis-po";
                                                    }
                                                    ?>
                                                    <span for="" class=<?= $class ?> style="padding: 5px 10px 5px 10px; border-radius: 5px;"><?= strtolower($a->nama_status_internal) ?></span>
                                                    
                                                </td>
<td class="text-center">
    <div class="d-flex flex-row justify-content-center gap-2">
        <div>
            <?php 
                if ($a->tahun_folder == 2024) {
                    $url = "http://backup.muliaputramandiri.com:81/cisk/assets/uploads/management_claim/2024/";
                }else{
                    $url = base_url()."assets/uploads/management_claim/2025/";
                }
            ?>
            <?php 
            $session_userid = $this->session->userdata('id');
                if ($a->file) { ?>
                <a href="<?= $url.$id_kategori.'/'.$a->file ?>" class='btn btn-submit-cream' style="border:none" onclick="trackClick(<?= $a->id ?>, <?= $session_userid ?>)">file</a>
                <?php
                }
            ?>
        </div>
        
            <?php 
                if ($a->file_zip) { ?>
                <div>
                    <a href="<?= $url.$id_kategori.'/'.$a->file_zip ?>" class='btn btn-submit-cream' style="border:none">zip</a>
                
                </div>
                <?php
                }
            ?>

    </div>
</td>
                                            </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        
                        
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-2">
                    
            <div class="col-md-12">
                <div class="collapse multi-collapse-dokumentasi" id="multiCollapseExample3">
                    <div class="card card-body">
                        <div class="container-fluid">    
                            
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="kategori" ><strong>Dokumentasi</strong></label>
                                </div>
                            </div>          
                            
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table id="dokumentasi" style="width: 100%" >
                                        <thead>
                                            <tr>
                                                <th>Status Internal</th>
                                                <th style="width: 80%">Deskripsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php
                                            $no = 1;
                                            foreach ($get_status_internal->result() as $a) : ?>
                                            <tr>
                                                <td><?= $a->nama_status ?></td>
                                                <td><?= $a->keterangan ?></td>
                                            </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        
                        
                        </div>
                    </div>
                </div>
            </div>

        </div>


        
        
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#log-history').DataTable({
            "pageLength": 50,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
        $('#dokumentasi').DataTable({
            "columnDefs": [{ width: 1, targets: 0 }],
            "fixedColumns": true,
            "paging": false,
            "pageLength": 10,
            "ordering": false,
            // "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
        });
    });
</script>




<script>
function trackClick(log_id, user_id) {
    // alert(log_id + " " + user_id);
    $.ajax({
        type: 'POST',
        url: '<?= base_url("management_claim/track_click_claim") ?>',
        dataType: 'json',
        data: {
            log_id: log_id,
            user_id: user_id
        },
        success: function(response) {
            // Optional: Handle response if needed
            console.log(response);
            alert('Click successfully!. Log inserted.');
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Error tracking click: ' + error);
        }
    });
}
</script>